<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wxpay extends MY_Front_Wxapp {

	public function __construct() {
		parent::__construct ();
		// 开发模式下开启性能分析
		$this->output->enable_profiler ( false );
	}
	function hotel_order() {
		$parameters = '';
		$data = array ();
		//oo89wt9oUiHIZoiQA19CxCVRto9U
		//a452233816
		//$orderid = $this->input->get ( 'orderid', true );
		$orderid = $this->get_source ( 'orderid' );
		$data ['fail_url'] = site_url ( 'hotel/hotel/myorder' ) . '?id=' . $this->inter_id;
		$data ['success_url'] = $data ['fail_url'];
		if ($orderid) {
			$this->load->model ( 'pay/Wxpay_model' );
			$this->load->model ( 'pay/Pay_model' );
			$this->load->model ( 'hotel/Order_model' );
			$this->load->model ( 'wx/Publics_model' );
			// 公众号
			$public = $this->Publics_model->get_public_by_id ( $this->inter_id );
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
					$re = $this->Order_check_model->check_order_state($order_details);
					/* if($re['re_pay']!=1){
						redirect ( site_url ( 'hotel/hotel/index' ) . '?id=' . $this->inter_id .'&h=' .$order_details['hotel_id'] );
					} */

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
					$pay_paras = $this->Pay_model->get_pay_paras ( $order_details ['inter_id'], 'weixin' );
					
					
					$pay_paras = array();
					if($this->inter_id == "a455510007"){
						
						//速8支付资料
						$pay_paras['mch_id'] = "1410457902";
						$pay_paras ['app_id'] = "wx76c103d6e73d5c33";
						$pay_paras['key'] = "iwidemofly12345678900987654321as";
						
					}else if($this->inter_id == "a421641095"){
						//碧桂园定制，其他测试号也用碧桂园支付作测试
						$pay_paras['mch_id'] = "1407010302";
						$pay_paras ['app_id'] = "wx59b20d29fac0eea1";
						$pay_paras['key'] = "iwidemofly12345678900987654321as";
						
						
						
						
					}else{
						
						//小程序处理
						$this->load->model ( 'wxapp/Auth_model' );
						$appinfo = $this->Auth_model->get_public_by_id($this->inter_id);
						$pay_paras = $this->Pay_model->get_pay_paras ( $order_details ['inter_id'], 'weixin' );
							
						$pay_paras ['app_id'] = $appinfo['app_id'];
						
						if($appinfo['mch_id'] != "" && $appinfo['mch_key'] != ""){
							$pay_paras['mch_id'] = $appinfo['mch_id'];
							$pay_paras['key'] = $appinfo['mch_key'];
						}
						
					}
					
					
					if (0 && ! empty ( $pay_paras ['sub_mch_id'] )) {
						$this->Wxpay_model->setParameter ( "sub_openid", $this->wxapp_openid );
						if(!empty($pay_paras['sub_mch_id_h_'.$order_details['hotel_id']]))
							$this->Wxpay_model->setParameter("sub_mch_id",$pay_paras['sub_mch_id_h_'.$order_details['hotel_id']]);
						else
							$this->Wxpay_model->setParameter ( "sub_mch_id", $pay_paras ['sub_mch_id'] );
						$this->Wxpay_model->setParameter ( "mch_id", $pay_paras ['mch_id'] );
					} else {
						//碧桂园定制，暂时写死
						
						$this->Wxpay_model->setParameter ( "openid", $this->wxapp_openid );
						$this->Wxpay_model->setParameter ( "mch_id", $pay_paras ['mch_id'] );
						if (empty ( $pay_paras ['app_id'] )) // new
							$pay_paras ['app_id'] = $public ['app_id'];
					}

					$this->Wxpay_model->setParameter ( "body", $order_details ['hname'] . ' - ' .$order_details ['first_detail'] ['roomname'] ); // 商品描述
					$wxpay_reduce = is_null ( $order_details ['wxpay_favour'] ) ? 0 : $order_details ['wxpay_favour'];
					$this->Wxpay_model->setParameter ( "out_trade_no", $pay_orderid ); // 商户订单号
					$this->Wxpay_model->setParameter ( "detail", $order_details ['hname'].'-'.$order_details ['first_detail'] ['roomname'].',单号：'.$order_details ['orderid'] ); // 商品名称明细列表
					$this->Wxpay_model->setParameter ( "total_fee", ($order_details ['price'] - $wxpay_reduce) * 100 ); // 总金额
					$this->Wxpay_model->setParameter ( "notify_url", site_url ( 'Wxpayreturn/hotel_payreturn/'.$order_details ['inter_id'] ) ); // 通知地址
					$this->Wxpay_model->setParameter ( "trade_type", "JSAPI" ); // 交易类型
					$prepay_id = $this->Wxpay_model->getPrepayId ( $pay_paras );
					$this->Wxpay_model->setPrepayId ( $prepay_id );
					$jsApiObj ["appId"] = $pay_paras ['app_id'];
					$timeStamp = time ();
					$jsApiObj ["timeStamp"] = "$timeStamp";
					$jsApiObj ["nonceStr"] = $this->Wxpay_model->createNoncestr ();
					$jsApiObj ["package"] = "prepay_id=$prepay_id";
					$jsApiObj ["signType"] = "MD5";
					$jsApiObj ["paySign"] = $this->Wxpay_model->getSign ( $jsApiObj, $pay_paras );
					$parameters = json_encode ( $jsApiObj );
					$data ['success_url'] = site_url ( 'hotel/hotel/orderdetail' ) . '?id=' . $this->inter_id . '&oid=' . $order_details ['id'];
				}
			}
		}
		$data ['jsApiParameters'] = $parameters;
		$this->out_put_msg ( 1, '', $data ,'Wxpay/hotel_order');
		//$this->display ( 'pay/hotel_order/wxpay', $data );
	}
	
	public function soma_pay()
	{/*
	$inter_id= $this->inter_id;
	$openid= $this->openid;
	$url= site_url('soma/payment/wxppay_invoke'). "?". $_SERVER['QUERY_STRING']. "&inter_id=$inter_id&openid=$openid";
	redirect($url); */
	//MYLOG::soma_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));
	//初始化数据库分片配置
	if( $this->inter_id ){
		$this->load->model('soma/shard_config_model', 'model_shard_config');
		$this->current_inter_id= $this->inter_id;
		$this->db_shard_config= $this->model_shard_config->build_shard_config($this->inter_id);
		//print_r($this->db_shard_config);
	}
	
	//$order_id= $this->input->get('order_id');
	$order_id = $this->get_source ( 'orderid' );
	$inter_id= $this->inter_id;
	//$openid= $this->openid;
	$openid = $this->wxapp_openid;
	//$openid = 'ohBHq0EwkQ4Kb0x9v-wQVVu1govM';
	
	$this->load->model('soma/Sales_order_model');
	$order_detail= $this->Sales_order_model->get_order_simple($order_id);
	
	//$where = array('order_id' => $order_id);
	//$table = $this->Sales_order_model->table_name($inter_id);
	//$order_detail = $this->Sales_order_model->_shard_db($inter_id)->where($where)->get($table)->row_array();
	//$order_detail= $this->Sales_order_model->find(array('order_id'=> $order_id));
	
	if( $order_id && $inter_id && $openid && $order_detail ){
		$this->load->model('pay/wxpay_model');
		$this->load->model('pay/pay_model' );
		$this->load->model('wx/publics_model');
		$public = $this->publics_model->get_public_by_id($inter_id);
	
		
		//$public['app_id'] = "wx59b20d29fac0eea1";
		if(1||empty($public['app_id'])){
			$pay_paras=$this->pay_model->get_pay_paras($inter_id);
			
			//碧桂园定制
					$pay_paras = array();
			
			
					$pay_paras = array();
					if($this->inter_id == "a455510007"){
						
						//速8支付资料
						$pay_paras['mch_id'] = "1410457902";
						$pay_paras ['app_id'] = "wx76c103d6e73d5c33";
						$pay_paras['key'] = "iwidemofly12345678900987654321as";
						
					}else if($this->inter_id == "a421641095"){
						//碧桂园定制，其他测试号也用碧桂园支付作测试
						$pay_paras['mch_id'] = "1407010302";
						$pay_paras ['app_id'] = "wx59b20d29fac0eea1";
						$pay_paras['key'] = "iwidemofly12345678900987654321as";
						
						
						
						
					}else{
						
						//小程序处理
						$this->load->model ( 'wxapp/Auth_model' );
						$appinfo = $this->Auth_model->get_public_by_id($this->inter_id);
						$pay_paras=$this->pay_model->get_pay_paras($inter_id);
							
						$pay_paras ['app_id'] = $appinfo['app_id'];
						
						if($appinfo['mch_id'] != "" && $appinfo['mch_key'] != ""){
							$pay_paras['mch_id'] = $appinfo['mch_id'];
							$pay_paras['key'] = $appinfo['mch_key'];
						}
						
			}
	
			if(0&& isset($pay_paras['sub_mch_id']) && !empty($pay_paras['sub_mch_id']) ){
				$this->wxpay_model->setParameter("sub_openid", $openid);
				$this->wxpay_model->setParameter("mch_id", $pay_paras['mch_id']);
				$this->wxpay_model->setParameter("sub_mch_id", $pay_paras['sub_mch_id']);
	
				//自商户分账-----------
				if( !empty($pay_paras['sub_mch_id_h_'. $order_detail['hotel_id']]) ){
					$this->wxpay_model->setParameter("sub_mch_id", $pay_paras['sub_mch_id_h_'. $order_detail['hotel_id']] );
				}
	
			} else {
				$this->wxpay_model->setParameter("openid", $openid);
				$this->wxpay_model->setParameter("mch_id", $pay_paras['mch_id']);
				if(empty($pay_paras['app_id'])) //new
					$pay_paras['app_id']= $public['app_id'];
			}
	
			$this->load->model('soma/Sales_order_model');
			$business_type= $this->Sales_order_model->get_business_type();  //各种业务类型中文标识：套票|
	
			if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' )
				$business_type= '月饼';
	
			$settle_type= $this->Sales_order_model->get_settle_label();  //各种结算方式中文标识：普通购买|拼团购买
			$order_desc= $public['name']. '_';
			$order_desc.= array_key_exists($order_detail['business'], $business_type)? $business_type[$order_detail['business']]: '';
			$order_desc.= array_key_exists($order_detail['settlement'], $settle_type)? $settle_type[$order_detail['settlement']]: '';
			$order_desc.= '#'. $order_id;
	
			if( $order_detail['settlement']== Sales_order_model::SETTLE_KILLSEC ){
				//对于秒杀限定其支付有效期
				$this->wxpay_model->setParameter("time_expire", date('YmdHis')+ 300);
			}
	
			$wx_order_id= $this->Sales_order_model->wx_out_trade_no_encode($order_id, $order_detail['settlement'], $order_detail['business']);
			$this->wxpay_model->setParameter("body", $order_desc);//商品描述
			$this->wxpay_model->setParameter("total_fee", $order_detail['grand_total']* 100 );//总金额
			$this->wxpay_model->setParameter("out_trade_no", $wx_order_id );//商户订单号
			$this->wxpay_model->setParameter("notify_url", Soma_const_url::inst()->get_payment_return() .'/'. $order_id );//通知地址
			$this->wxpay_model->setParameter("trade_type", "JSAPI");//交易类型
			$prepay_id = $this->wxpay_model->getPrepayId($pay_paras);
			$this->wxpay_model->setPrepayId($prepay_id);
	
			$jsApiObj["appId"]     = $pay_paras['app_id'];
			$jsApiObj["timeStamp"] = (string) time();
			$jsApiObj["nonceStr"]  = $this->wxpay_model->createNoncestr();
			$jsApiObj["package"]   = "prepay_id=$prepay_id";
			$jsApiObj["signType"]  = "MD5";
			$jsApiObj["paySign"]   = $this->wxpay_model->getSign($jsApiObj, $pay_paras);
			$parameters = json_encode($jsApiObj);
			//print_r($parameters);die;
		}
	
		$urlParams = array(
				'id'=> $inter_id,
				'order_id'=> $order_id,
				'settlement'=>$order_detail['settlement']
		);
	
		$successUrl = Soma_const_url::inst()->get_payment_success($order_detail['business'],$urlParams);
	
		$buyType= $this->input->get('bType');
		if(empty($buyType)){
			$data['success_url'] = $successUrl;
		}else{
			$data['success_url'] = $this->Sales_order_model->success_payment_path($inter_id,$buyType,$order_detail,$successUrl);
	
		}
	
		$data['fail_url'] = Soma_const_url::inst()->get_payment_fail($order_detail['business'], $urlParams);
		$data['jsApiParameters'] = $parameters;
		//$this->display ( 'pay/soma_pay/wxpay', $data );
		$this->out_put_msg ( 1, '', $data ,'pay/soma_pay/wxpay');
	
	} else
		exit('参数错误，微信支付失败');
	}
}

/* End of file Wxpaytest.php */
/* Location: ./application/controllers/Wxpaytest.php */