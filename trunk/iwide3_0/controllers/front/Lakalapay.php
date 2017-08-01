<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
use Paymax\config\SignConfig;
use Paymax\model\Charge;
use Paymax\util\PaymaxUtil;
class Lakalapay extends MY_Front {
   
	public function __construct() {
		parent::__construct ();
		// 开发模式下开启性能分析
		$this->output->enable_profiler ( false );
		//配置
		require APPPATH.'/libraries/Paymax/config/PaymaxConfig.php';
		require APPPATH.'/libraries/Paymax/config/SignConfig.php';

		//异常
		require APPPATH.'/libraries/Paymax/exception/PaymaxException.php';
		require APPPATH.'/libraries/Paymax/exception/AuthorizationException.php';
		require APPPATH.'/libraries/Paymax/exception/InvalidRequestException.php';
		require APPPATH.'/libraries/Paymax/exception/InvalidResponseException.php';

		//model
		require APPPATH.'/libraries/Paymax/model/Paymax.php';
		require APPPATH.'/libraries/Paymax/model/ApiResource.php';
		require APPPATH.'/libraries/Paymax/model/Charge.php';
		require APPPATH.'/libraries/Paymax/model/Refund.php';

		//签名和验签
		require APPPATH.'/libraries/Paymax/sign/RSAUtil.php';

		//Util
		require APPPATH.'/libraries/Paymax/util/HttpCurlUtil.php';
		require APPPATH.'/libraries/Paymax/util/PaymaxUtil.php';


	}
	function hotel_order() {

		$parameters = '';
		$data = array ();
		$orderid = $this->input->get ( 'orderid', true );
		$data ['fail_url'] = site_url ( 'hotel/hotel/myorder' ) . '?id=' . $this->inter_id;
		$data ['success_url'] = $data ['fail_url'];
		if ($orderid) {
			$this->load->model ( 'pay/Wxpay_model' );
			$this->load->model ( 'pay/Pay_model' );
			$this->load->model ( 'hotel/Order_model' );
			$this->load->model ( 'wx/Publics_model' );
			// 公众号
			$public = $this->Publics_model->get_public_by_id ( $this->session->userdata ( 'inter_id' ) );
			if (! empty ( $public ['app_id'] )) {
				$order_details = $this->Order_model->get_main_order ( $this->inter_id, array (
						'orderid' => $orderid,
						'idetail' => array (
								'i' 
						) 
				) );
				if ($order_details) {
					$order_details = $order_details [0];
					//若订单已取消，跳转再次预定页面
					$this->load->model ( 'hotel/Order_check_model' );
					//本地
					$re = $this->Order_check_model->check_order_state($order_details);
					// $this->load->library ( 'PMS_Adapter', array (
					// 	'inter_id' => $this->inter_id,
					// 	'hotel_id' => $order_details['hotel_id']
					// ), 'pmsa' );
					//pms
					// $pmsre = $this->pmsa->check_order_canpay ( $order_details );

					if($re['re_pay']!=1){
						redirect ( site_url ( 'hotel/hotel/index' ) . '?id=' . $this->inter_id .'&h=' .$order_details['hotel_id'] );
					}
					
					//配置采用pms单号还是本地单号进行支付
					$pay_orderid=$order_details ['orderid'];
					$this->load->model ( 'hotel/Hotel_config_model' );
					$config_data = $this->Hotel_config_model->get_hotel_config ( $order_details ['inter_id'], 'HOTEL', 0, array (
							'ORDER_PAY_ORDERID'
					) );
					if(!empty($config_data['ORDER_PAY_ORDERID'])&&$config_data['ORDER_PAY_ORDERID']=='web'){
						$pay_orderid=$order_details['web_orderid'];
					}
					
					$data ['fail_url'] .= '&fro='.$orderid;
					$pay_paras = $this->Pay_model->get_pay_paras ( $order_details ['inter_id'], 'lakala' );

					//lakala
					$this->db->where(array('orderid'=>$pay_orderid));
					$lakala = $this->db->get('lakala_text')->row_array();
					if($lakala){
						$arr = json_decode($lakala['content'],true);
					}else{
						SignConfig::setSecretKey($pay_paras ['key']);
						SignConfig::setPrivateKeyPath("../certs/rsa_private_key_".$pay_paras ['mch_id'].".pem");
						SignConfig::setPaymaxPublicKeyPath("../certs/paymax_rsa_public_key_".$pay_paras ['mch_id'].".pem");
						$req_data=array(
				            'amount'=>$order_details ['price'],
				            'subject'=>$this->inter_id,
				            'body'=>$order_details ['hname'] . ' - ' .$order_details ['first_detail'] ['roomname'],
				            'order_no'=>$pay_orderid,
				            'client_ip'=>GetHostByName($_SERVER['SERVER_NAME']),
				            'app'=>$pay_paras ['mch_id'],
				            'currency'=>'CNY',
				            'description'=>$order_details ['hname'].'-'.$order_details ['first_detail'] ['roomname'].',单号：'.$order_details ['orderid'],
				        );

						//判断微信支付或者银行卡支付
						if($order_details['paytype'] == 'lakala_y'){//银行卡
							$req_data['channel'] = 'lakala_h5';
							$req_data['extra']=array(
								'user_id'=>md5($this->session->userdata($this->inter_id.'openid')),
								'return_url'=>site_url ( 'hotel/hotel/orderdetail' ) . '?realid=' . $this->inter_id . '&oid=' . $order_details ['id'],
								'show_url'=>site_url ( 'hotel/hotel/orderdetail' ) . '?realid=' . $this->inter_id . '&oid=' . $order_details ['id']
							);
						}else{//微信
							$req_data['channel'] = 'wechat_wap';
							$req_data['extra'] = array('open_id'=>$this->session->userdata($this->inter_id.'openid') );
						}

				        $res =  Charge::create($req_data);
						$this->db->insert ( 'lakala_text', array('content'=>$res,'create_time'=>time(),'orderid'=>$pay_orderid) );
						
						$arr = json_decode($res,true);
					}

					//如果是银行卡支付，表单形式
					if($order_details['paytype'] == 'lakala_y'){//银行卡
						if(isset($arr['credential']['lakala_h5']) && !empty($arr['credential']['lakala_h5'])){
							echo $arr['credential']['lakala_h5'];
							echo '<script type="text/javascript">document.getElementsByTagName("form")[0].submit();</script>';
						}
						exit;
					}


					if(isset($arr['credential']['wechat_wap']['jsApiParams']) && !empty($arr['credential']['wechat_wap']['jsApiParams'])){
						$jsApiObj = json_decode($arr['credential']['wechat_wap']['jsApiParams'],true);
					}
					//end
					$parameters = json_encode ( $jsApiObj );
					$data ['success_url'] = site_url ( 'hotel/hotel/orderdetail' ) . '?id=' . $this->inter_id . '&oid=' . $order_details ['id'];
				}
			}
		}
		$data ['jsApiParameters'] = $parameters;
		$this->display ( 'pay/hotel_order/wxpay', $data );
	}
	
}
