<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Unionpay extends MY_Front {
   
	public function __construct() {
		parent::__construct ();
		// 开发模式下开启性能分析
		$this->output->enable_profiler ( false );
		//配置
		require_once APPPATH.'/libraries/UnionPay/acp_service.php';

	}
	function hotel_order() {

		$data = array ();
		$orderid = $this->input->get ( 'orderid', true );
		if ($orderid) {
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
					
					$pay_paras = $this->Pay_model->get_pay_paras ( $order_details ['inter_id'], 'unionpay' );
					//商户订单号，8-32位数字字母，不能含“-”或“_”
					$pay_orderid = str_replace('_','yinlian',str_replace('-','union',$pay_orderid));
					//unionpay
					$params = array(
							
							//以下信息非特殊情况不需要改动
							'version' => '5.0.0',                 //版本号
							'encoding' => 'utf-8',				  //编码方式
							'txnType' => '01',				      //交易类型
							'txnSubType' => '01',				  //交易子类
							'bizType' => '000201',				  //业务类型
							'frontUrl' =>  site_url ( 'hotel/hotel/orderdetail' ) . '?id=' . $this->inter_id . '&oid=' . $order_details ['id'],  //前台通知地址
							'backUrl' => site_url ( 'unionpayreturn/hotel_payreturn/'.$order_details ['inter_id'] ),	  //后台通知地址
							'signMethod' => '01',	              //签名方法
							'channelType' => '08',	              //渠道类型，07-PC，08-手机
							'accessType' => '0',		          //接入类型
							'currencyCode' => '156',	          //交易币种，境内商户固定156
							
							//TODO 以下信息需要填写
							'merId' => $pay_paras ['mch_id'],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
							'orderId' => $pay_orderid,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
							'txnTime' => date('YmdHis'),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
							'txnAmt' => $order_details ['price']* 100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
					// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据

							//TODO 其他特殊用法请查看 special_use_purchase.php
						);
					com\unionpay\acp\sdk\UnionPayConfig::setSignCertPath ( '../certs/acp_prod_sign_'.$pay_paras ['mch_id'].'.pfx' );
					com\unionpay\acp\sdk\UnionPayConfig::setSignCertPwd ( $pay_paras ['pwd'] );
					com\unionpay\acp\sdk\AcpService::sign ( $params );
					$uri = 'https://gateway.95516.com/gateway/api/frontTransReq.do';
					$html_form = com\unionpay\acp\sdk\AcpService::createAutoFormHtml( $params, $uri );
					echo $html_form;
				}
			}
		}

	}
	
}
