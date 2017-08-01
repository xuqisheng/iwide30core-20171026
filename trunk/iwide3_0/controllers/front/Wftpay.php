<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wftpay extends MY_Front {
    
	public function __construct() {
		parent::__construct ();
		// 开发模式下开启性能分析
		$this->output->enable_profiler ( false );
		$this->load->library('WftPay/Utils');
		$this->load->library('WftPay/RequestHandler',null,'RequestHandler');
		$this->load->library('WftPay/PayHttpClient',null,'PayHttpClient');
		$this->load->library('WftPay/ClientResponseHandler',null,'ClientResponseHandler');
	}
	function hotel_order() {
		$orderid = $this->input->get ( 'orderid', true );
		if ($orderid) {
			$this->load->model ( 'pay/Pay_model' );
			$this->load->model ( 'hotel/Order_model' );
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
				$this->load->library ( 'PMS_Adapter', array (
					'inter_id' => $this->inter_id,
					'hotel_id' => $order_details['hotel_id']
				), 'pmsa' );
				//pms
				$pmsre = $this->pmsa->check_order_canpay ( $order_details );
				if($re['re_pay']!=1 || !$pmsre){
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
				//获取配置				
				$pay_paras = $this->Pay_model->get_pay_paras ( $order_details ['inter_id'], 'weifutong' );
				
		        $this->RequestHandler->setParameter('service','pay.weixin.jspay');//接口类型：pay.weixin.jspay
		        //必填项，商户号，由威富通分配
	        	if(isset($pay_paras['sub_mch_id_h_'.$order_details['hotel_id']]) && !empty($pay_paras['sub_mch_id_h_'.$order_details['hotel_id']]))
	        		$this->RequestHandler->setParameter("mch_id",$pay_paras['sub_mch_id_h_'.$order_details['hotel_id']]);
	        	else
	        		$this->RequestHandler->setParameter ( "mch_id", $pay_paras ['mch_id'] );

	        	if(isset($pay_paras['sub_key_h_'.$order_details['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order_details['hotel_id']]))
	        		$this->RequestHandler->setKey($pay_paras ['sub_key_h_'.$order_details['hotel_id']]);
	        	else
	        		$this->RequestHandler->setKey($pay_paras ['key']);


		        $this->RequestHandler->setParameter('version','2.0');
		        $this->RequestHandler->setParameter('out_trade_no',$pay_orderid);
		        $this->RequestHandler->setParameter('sub_openid',$this->session->userdata ( $this->session->userdata ( 'inter_id' ) . 'openid' ));
		        $this->RequestHandler->setParameter('body',$order_details ['first_detail'] ['roomname']);
		        $this->RequestHandler->setParameter('total_fee',bcmul($order_details ['price'],100));

		        $this->RequestHandler->setParameter('mch_create_ip',GetHostByName($_SERVER['SERVER_NAME']));
		        //通知地址，必填项
				$this->RequestHandler->setParameter('notify_url',site_url ( 'Wftpayreturn/hotel_payreturn/'.$order_details ['inter_id'] ));//通知回调地址，目前默认是空格，商户在测试支付和上线时必须改为自己的，且保证外网能访问到
				$this->RequestHandler->setParameter('callback_url',site_url ( 'hotel/hotel/myorder' ) . '?id=' . $this->inter_id . '&type=' . $order_details ['price_type']);
		        $this->RequestHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
		        
		        //添加订单超时时间 add by ping
				// $timeStamp =time();
				// if(isset($pay_paras['outtime']) && $pay_paras['outtime']>=5 && $pay_paras['outtime']<=30){
				// 	$out_time = $pay_paras['outtime'] * 60 + $timeStamp;
				// }else{
				// 	$out_time = 900 + $timeStamp;//默认15分钟超时
				// }
				// $this->RequestHandler->setParameter ( "time_expire", date('YmdHis',$out_time) ); // 超时时间


		        $this->RequestHandler->createSign();//创建签名
		        
		        $data = Utils::toXml($this->RequestHandler->getAllParameters());
		        $this->PayHttpClient->setReqContent($this->RequestHandler->getGateURL(),$data);
				$this->load->library('MYLOG');
                MYLOG::w(json_encode($data),'wftpay_q');

		        if($this->PayHttpClient->call()){
		            $this->ClientResponseHandler->setContent($this->PayHttpClient->getResContent());
		            $this->ClientResponseHandler->setKey($this->RequestHandler->getKey());
		            if($this->ClientResponseHandler->isTenpaySign()){
		                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
		                if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
		                    redirect('https://pay.swiftpass.cn/pay/jspay?token_id='.$this->ClientResponseHandler->getParameter('token_id').'&showwxtitle=1');
		                    exit();
		                }else{
		                    // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('err_code').' Error Message:'.$this->ClientResponseHandler->getParameter('err_msg')));
		                    echo $this->ClientResponseHandler->getParameter('err_msg');
		                    exit();
		                }
		            }
		            // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('status').' Error Message:'.$this->ClientResponseHandler->getParameter('message')));
		            echo $this->ClientResponseHandler->getParameter('message');
		        }else{
		            // echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
		            echo $this->PayHttpClient->getErrInfo();
		        }
			}
		}

        
	}

	//商城wft支付
	public function soma_pay()
    {

        $this->load->somaDatabase($this->db_soma);
        $this->load->somaDatabaseRead($this->db_soma_read);

		$order_id = $this->input->get('order_id', true );
		// $order_id = 1000007471;
		$inter_id = $this->inter_id;
		$openid= $this->openid;
		// $inter_id = 'a463642921';
// var_dump( $order_id, $inter_id, $openid );
		if( !$order_id || !$inter_id || !$openid ){
			die('wft，参数错误！');
		}

		//初始化数据库分片配置
		if( $this->inter_id ){
			$this->load->model('soma/shard_config_model', 'model_shard_config');
			$this->current_inter_id= $this->inter_id;
			$this->db_shard_config= $this->model_shard_config->build_shard_config($inter_id);
		}

		$this->load->model('soma/Sales_order_model','OrderModel');
		$OrderModel = $this->OrderModel;
		$order_detail = $OrderModel->get_order_simple($order_id);
		if( !$order_detail ){
			die('wft，获取订单详情失败！');
		}

		//判断状态
		if( $order_detail['status'] != $OrderModel::STATUS_WAITING ){
			die('wft，订单为不可支付状态！');
		}

		//判断公众号
		if( $order_detail['inter_id'] != $inter_id ){
			die('wft，公众号不匹配！');
		}

		//判断用户
		if( $order_detail['openid'] != $openid ){
			die('wft，用户不匹配！');
		}

		//获取配置				
		$this->load->model ( 'pay/Pay_model' );
		$pay_paras = $this->Pay_model->get_pay_paras( $inter_id, 'weifutong' );
		if( !$pay_paras ){
			die('wft，获取支付基本配置失败！');
		}
		
        $this->RequestHandler->setParameter( 'service', 'pay.weixin.jspay' );//接口类型：pay.weixin.jspay
        //必填项，商户号，由威富通分配
    	if( isset($pay_paras['sub_mch_id_h_'.$order_detail['hotel_id']] ) && !empty( $pay_paras['sub_mch_id_h_'.$order_detail['hotel_id']] ) )
    		$this->RequestHandler->setParameter( "mch_id", $pay_paras['sub_mch_id_h_'.$order_detail['hotel_id']] );
    	else
    		$this->RequestHandler->setParameter( "mch_id", $pay_paras['mch_id'] );

    	if( isset( $pay_paras['sub_key_h_'.$order_detail['hotel_id']] ) && !empty( $pay_paras['sub_key_h_'.$order_detail['hotel_id']] ) )
    		$this->RequestHandler->setKey( $pay_paras['sub_key_h_'.$order_detail['hotel_id']] );
    	else
    		$this->RequestHandler->setKey( $pay_paras['key'] );

    	$this->load->model('wx/publics_model');
		$public = $this->publics_model->get_public_by_id($inter_id);
		if( !$public ){
			die('wft，获取公众号信息失败！');
		}

    	$business_type= $OrderModel->get_business_type();  //各种业务类型中文标识：套票|
		$settle_type= $OrderModel->get_settle_label();  //各种结算方式中文标识：普通购买|拼团购买
		$order_desc= $public['name']. '_';
		$order_desc.= array_key_exists($order_detail['business'], $business_type)? $business_type[$order_detail['business']]: '';
		$order_desc.= array_key_exists($order_detail['settlement'], $settle_type)? $settle_type[$order_detail['settlement']]: '';
		$order_desc.= '#'. $order_id;
// echo $order_desc;die;

		//商户订单号
		$wft_order_id= $OrderModel->wx_out_trade_no_encode($order_id, $order_detail['settlement'], $order_detail['business']);

        $this->RequestHandler->setParameter('version','2.0');
        $this->RequestHandler->setParameter('out_trade_no',$wft_order_id);
        $this->RequestHandler->setParameter('sub_openid',$openid);
        $this->RequestHandler->setParameter('body',$order_desc);
        $this->RequestHandler->setParameter('total_fee',bcmul("{$order_detail['grand_total']}", '100'));
        $this->RequestHandler->setParameter('mch_create_ip',GetHostByName($_SERVER['SERVER_NAME']));
        //通知地址，必填项
		$this->RequestHandler->setParameter('notify_url',Soma_const_url::inst()->get_wft_payment_return() .'/'. $order_id );//通知回调地址，目前默认是空格，商户在测试支付和上线时必须改为自己的，且保证外网能访问到

		$urlParams = array(
			'id'=> $inter_id,
			'order_id'=> $order_id,
			'settlement'=>$order_detail['settlement']
		);
		$successUrl = Soma_const_url::inst()->get_payment_success($order_detail['business'],$urlParams);
		// $this->RequestHandler->setParameter('callback_url',Soma_const_url::inst()->get_url( 'soma/order/my_order_list', array('id'=>$inter_id ) ) );
		$this->RequestHandler->setParameter('callback_url',$successUrl );
        $this->RequestHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        
        //添加订单超时时间 add by ping
		// $timeStamp =time();
		// if(isset($pay_paras['outtime']) && $pay_paras['outtime']>=5 && $pay_paras['outtime']<=30){
		// 	$out_time = $pay_paras['outtime'] * 60 + $timeStamp;
		// }else{
		// 	$out_time = 900 + $timeStamp;//默认15分钟超时
		// }
		// $this->RequestHandler->setParameter ( "time_expire", date('YmdHis',$out_time) ); // 超时时间
        
        if( $order_detail['settlement']== Sales_order_model::SETTLE_KILLSEC ){
            //对于秒杀限定其支付有效期
            $this->wxpay_model->setParameter("time_expire", date('YmdHis', strtotime('+5 minutes')));
        } else {
            $this->wxpay_model->setParameter("time_expire", date('YmdHis', strtotime('+10 minutes')));
        }   


        $this->RequestHandler->createSign();//创建签名
        
        $data = Utils::toXml($this->RequestHandler->getAllParameters());
        $this->PayHttpClient->setReqContent($this->RequestHandler->getGateURL(),$data);
		$this->load->library('MYLOG');
        MYLOG::w(json_encode($data),'wftpay_q');

        if($this->PayHttpClient->call()){
            $this->ClientResponseHandler->setContent($this->PayHttpClient->getResContent());
            $this->ClientResponseHandler->setKey($this->RequestHandler->getKey());
            if($this->ClientResponseHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                    redirect('https://pay.swiftpass.cn/pay/jspay?token_id='.$this->ClientResponseHandler->getParameter('token_id').'&showwxtitle=1');
                    exit();
                }else{
                    // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('err_code').' Error Message:'.$this->ClientResponseHandler->getParameter('err_msg')));
                    echo $this->ClientResponseHandler->getParameter('err_msg');
                    exit();
                }
            }
            // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('status').' Error Message:'.$this->ClientResponseHandler->getParameter('message')));
            echo $this->ClientResponseHandler->getParameter('message');
        }else{
            // echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
            echo $this->PayHttpClient->getErrInfo();
        }

	}

    //快乐付wft支付
    public function okpay_pay(){
		//统计探针
		$this->load->library('MYLOG');
		MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));
        $this->db->where(array(
            'out_trade_no'=>$this->input->get('oid'),
            'inter_id'=>$this->input->get('id'),
            'hotel_id'=>$this->input->get('hid')
        ));

        $this->db->limit(1);
        $order = $this->db->get('okpay_orders')->row_array();
        $inter_id = $this->inter_id;
        $openid= $this->openid;
        if(!$inter_id || !$openid || !$order){
            die('wft，参数错误！');
        }
        if($inter_id != $order['inter_id']){
            die('wft，inter_id参数错误！');
        }
        if($openid != $order['openid']){
            die('wft，openid参数错误！');
        }
        $openid = 'oqhiduAlqRfyIKsisT9WFQOHkKc4';
        if(!empty($order) && $order['pay_status'] == 3){
            $inter_id = $this->input->get('id');
            $data = array();
            $data['id'] = $this->input->get('id');
            $data['hotel_id'] = $this->input->get('hid');
            $data['oid'] = $this->input->get('oid');
            $data['paycode'] = $order['sale'];

            $this->display ('okpay/okpay/pay_redirect', $data);

        }else if($order){
            $this->load->model ( 'pay/Pay_model' );
            //获取配置
            $pay_paras = $this->Pay_model->get_pay_paras ( $inter_id, 'weifutong' );
            if( !$pay_paras ){
                die('wft，获取支付基本配置失败！');
            }
            $this->RequestHandler->setParameter('service','pay.weixin.jspay');//接口类型：pay.weixin.jspay
            //必填项，商户号，由威富通分配
            if(isset($pay_paras['sub_mch_id_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_mch_id_h_'.$order['hotel_id']]))
                $this->RequestHandler->setParameter("mch_id",$pay_paras['sub_mch_id_h_'.$order['hotel_id']]);
            else
                $this->RequestHandler->setParameter ( "mch_id", $pay_paras ['mch_id'] );

            if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
                $this->RequestHandler->setKey($pay_paras ['sub_key_h_'.$order['hotel_id']]);
            else
                $this->RequestHandler->setKey($pay_paras ['key']);


            $this->RequestHandler->setParameter('version','2.0');
            $this->RequestHandler->setParameter('out_trade_no', $order['out_trade_no']);
            $this->RequestHandler->setParameter('sub_openid',$openid);
            $this->RequestHandler->setParameter('body','快乐付');
            $this->RequestHandler->setParameter('total_fee',bcmul($order ['pay_money'],100));
            //测试
            //$this->RequestHandler->setParameter('total_fee',1);

            $this->RequestHandler->setParameter('mch_create_ip',GetHostByName($_SERVER['SERVER_NAME']));
            //通知地址，必填项
            $this->RequestHandler->setParameter('notify_url',site_url ( 'Wftpayreturn/okpay_payreturn/'.$inter_id ));//通知回调地址，目前默认是空格，商户在测试支付和上线时必须改为自己的，且保证外网能访问到
            $this->RequestHandler->setParameter('callback_url',site_url( 'okpay/okpay/pay_success' ).'?id='.$inter_id.'&oid='. $this->input->get('oid'));
            $this->RequestHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位

            //添加订单超时时间 add by ping
            // $timeStamp =time();
            // if(isset($pay_paras['outtime']) && $pay_paras['outtime']>=5 && $pay_paras['outtime']<=30){
            // 	$out_time = $pay_paras['outtime'] * 60 + $timeStamp;
            // }else{
            // 	$out_time = 900 + $timeStamp;//默认15分钟超时
            // }
            // $this->RequestHandler->setParameter ( "time_expire", date('YmdHis',$out_time) ); // 超时时间


            $this->RequestHandler->createSign();//创建签名

            $data = Utils::toXml($this->RequestHandler->getAllParameters());
            $this->PayHttpClient->setReqContent($this->RequestHandler->getGateURL(),$data);
            $this->load->library('MYLOG');
            MYLOG::w(json_encode($data),'wftpay_q');

            if($this->PayHttpClient->call()){
                $this->ClientResponseHandler->setContent($this->PayHttpClient->getResContent());
                $this->ClientResponseHandler->setKey($this->RequestHandler->getKey());
                if($this->ClientResponseHandler->isTenpaySign()){
                    //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                    if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                        redirect('https://pay.swiftpass.cn/pay/jspay?token_id='.$this->ClientResponseHandler->getParameter('token_id').'&showwxtitle=1');
                        exit();
                    }else{
                        // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('err_code').' Error Message:'.$this->ClientResponseHandler->getParameter('err_msg')));
                        echo $this->ClientResponseHandler->getParameter('err_msg');
                        exit();
                    }
                }
                // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('status').' Error Message:'.$this->ClientResponseHandler->getParameter('message')));
                echo $this->ClientResponseHandler->getParameter('message');
            }else{
                // echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
                echo $this->PayHttpClient->getErrInfo();
            }
            //$this->display ('pay/mall_pay/wxpay', $data );
        }else{
            exit('参数错误');
        }
    }

	//微服务wft支付
    public function roomservice_pay(){
        $order_id= (int)$this->input->get('order_id');
        $inter_id= $this->inter_id;
        $openid= $this->openid;
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $order= $orderModel->get_order_simple(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id,'pay_status'=>'unpay'));

        if(!$inter_id || !$openid || !$order){
            die('wft，参数错误！');
        }
        if($inter_id != $order['inter_id']){
            die('wft，inter_id参数错误！');
        }
        if($openid != $order['openid']){
            die('wft，openid参数错误！');
        }
        $openid = 'oqhiduAlqRfyIKsisT9WFQOHkKc4';
        if(!empty($order) && $order['pay_status'] == $orderModel::IS_PAYMENT_YES){//已经付款的 跳转到详情页
            redirect(site_url('roomservice/roomservice/order_detail?id='.$inter_id.'&hotel_id='.$order['hotel_id'].'&oid='.$order_id));
            die;
        }else if($order && $order['pay_status'] == $orderModel::IS_PAYMENT_NOT){//未付款的
            $this->load->model ( 'pay/Pay_model' );
            //获取配置
            $pay_paras = $this->Pay_model->get_pay_paras ( $inter_id, 'weifutong' );
            if( !$pay_paras ){
                die('wft，获取支付基本配置失败！');
            }
            $this->RequestHandler->setParameter('service','pay.weixin.jspay');//接口类型：pay.weixin.jspay
            //必填项，商户号，由威富通分配
            if(isset($pay_paras['sub_mch_id_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_mch_id_h_'.$order['hotel_id']]))
                $this->RequestHandler->setParameter("mch_id",$pay_paras['sub_mch_id_h_'.$order['hotel_id']]);
            else
                $this->RequestHandler->setParameter ( "mch_id", $pay_paras ['mch_id'] );

            if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
                $this->RequestHandler->setKey($pay_paras ['sub_key_h_'.$order['hotel_id']]);
            else
                $this->RequestHandler->setKey($pay_paras ['key']);
            $this->load->model ( 'wx/publics_model' );
            $public = $this->publics_model->get_public_by_id($inter_id);
            $order_desc= $public['name']. '_微服务订单';
            $order_desc.= '#'. $order['order_sn'];
            $this->RequestHandler->setParameter('version','2.0');
            $this->RequestHandler->setParameter('out_trade_no', $order['order_sn']);//商户订单号
            $this->RequestHandler->setParameter('sub_openid',$openid);
            $this->RequestHandler->setParameter('body',$order_desc);
            $this->RequestHandler->setParameter('total_fee',bcmul($order ['sub_total'],100));
            //测试
            //$this->RequestHandler->setParameter('total_fee',1);

            $this->RequestHandler->setParameter('mch_create_ip',GetHostByName($_SERVER['SERVER_NAME']));
            //通知地址，必填项
            $this->RequestHandler->setParameter('notify_url',site_url ( 'Wftpayreturn/roomservice_rtn/'.$inter_id ));//通知回调地址，目前默认是空格，商户在测试支付和上线时必须改为自己的，且保证外网能访问到
            $this->RequestHandler->setParameter('callback_url',site_url('roomservice/roomservice/order_detail?id='.$inter_id.'&hotel_id='.$order['hotel_id'].'&oid='.$order_id));
            $this->RequestHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位

            $this->RequestHandler->createSign();//创建签名

            $data = Utils::toXml($this->RequestHandler->getAllParameters());
            $this->PayHttpClient->setReqContent($this->RequestHandler->getGateURL(),$data);
            $this->load->library('MYLOG');
            MYLOG::w(json_encode($data),'wftpay_roomservice');

            if($this->PayHttpClient->call()){
                $this->ClientResponseHandler->setContent($this->PayHttpClient->getResContent());
                $this->ClientResponseHandler->setKey($this->RequestHandler->getKey());
                if($this->ClientResponseHandler->isTenpaySign()){
                    //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                    if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                        redirect('https://pay.swiftpass.cn/pay/jspay?token_id='.$this->ClientResponseHandler->getParameter('token_id').'&showwxtitle=1');
                        exit();
                    }else{
                        // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('err_code').' Error Message:'.$this->ClientResponseHandler->getParameter('err_msg')));
                        echo $this->ClientResponseHandler->getParameter('err_msg');
                        exit();
                    }
                }
                // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('status').' Error Message:'.$this->ClientResponseHandler->getParameter('message')));
                echo $this->ClientResponseHandler->getParameter('message');
            }else{
                // echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
                echo $this->PayHttpClient->getErrInfo();
            }
            //$this->display ('pay/mall_pay/wxpay', $data );
        }else{
            exit('参数错误');
        }
    }

    //门票wft支付
    public function ticket_pay(){
        $order_id= (int)$this->input->get('order_id');
        $inter_id= $this->inter_id;
        $openid= $this->openid;
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $order= $orderModel->get_order_simple(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id,'pay_status'=>'unpay'));

        if(!$inter_id || !$openid || !$order){
            die('wft，参数错误！');
        }
        if($inter_id != $order['inter_id']){
            die('wft，inter_id参数错误！');
        }
        if($openid != $order['openid']){
            die('wft，openid参数错误！');
        }
        $openid = 'oqhiduAlqRfyIKsisT9WFQOHkKc4';
        if(!empty($order) && $order['pay_status'] == $orderModel::IS_PAYMENT_YES){//已经付款的 跳转到详情页
            redirect(site_url('ticket/ticket/order_detail?id='.$inter_id.'&hotel_id='.$order['hotel_id'].'&oid='.$order_id));
            die;
        }else if($order && $order['pay_status'] == $orderModel::IS_PAYMENT_NOT){//未付款的
            $this->load->model ( 'pay/Pay_model' );
            //获取配置
            $pay_paras = $this->Pay_model->get_pay_paras ( $inter_id, 'weifutong' );
            if( !$pay_paras ){
                die('wft，获取支付基本配置失败！');
            }
            $this->RequestHandler->setParameter('service','pay.weixin.jspay');//接口类型：pay.weixin.jspay
            //必填项，商户号，由威富通分配
            if(isset($pay_paras['sub_mch_id_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_mch_id_h_'.$order['hotel_id']]))
                $this->RequestHandler->setParameter("mch_id",$pay_paras['sub_mch_id_h_'.$order['hotel_id']]);
            else
                $this->RequestHandler->setParameter ( "mch_id", $pay_paras ['mch_id'] );

            if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
                $this->RequestHandler->setKey($pay_paras ['sub_key_h_'.$order['hotel_id']]);
            else
                $this->RequestHandler->setKey($pay_paras ['key']);
            $this->load->model ( 'wx/publics_model' );
            $public = $this->publics_model->get_public_by_id($inter_id);
            $order_desc= $public['name']. '_微服务订单';
            $order_desc.= '#'. $order['order_sn'];
            $this->RequestHandler->setParameter('version','2.0');
            $this->RequestHandler->setParameter('out_trade_no', $order['order_sn']);//商户订单号
            $this->RequestHandler->setParameter('sub_openid',$openid);
            $this->RequestHandler->setParameter('body',$order_desc);
            $this->RequestHandler->setParameter('total_fee',bcmul($order ['sub_total'],100));
            //测试
            //$this->RequestHandler->setParameter('total_fee',1);

            $this->RequestHandler->setParameter('mch_create_ip',GetHostByName($_SERVER['SERVER_NAME']));
            //通知地址，必填项
            $this->RequestHandler->setParameter('notify_url',site_url ( 'Wftpayreturn/ticket_rtn/'.$inter_id ));//通知回调地址，目前默认是空格，商户在测试支付和上线时必须改为自己的，且保证外网能访问到
            $this->RequestHandler->setParameter('callback_url',site_url('ticket/ticket/order_detail?id='.$inter_id.'&hotel_id='.$order['hotel_id'].'&oid='.$order_id));
            $this->RequestHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位

            $this->RequestHandler->createSign();//创建签名

            $data = Utils::toXml($this->RequestHandler->getAllParameters());
            $this->PayHttpClient->setReqContent($this->RequestHandler->getGateURL(),$data);
            $this->load->library('MYLOG');
            MYLOG::w(json_encode($data),'wftpay_roomservice');

            if($this->PayHttpClient->call()){
                $this->ClientResponseHandler->setContent($this->PayHttpClient->getResContent());
                $this->ClientResponseHandler->setKey($this->RequestHandler->getKey());
                if($this->ClientResponseHandler->isTenpaySign()){
                    //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                    if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                        redirect('https://pay.swiftpass.cn/pay/jspay?token_id='.$this->ClientResponseHandler->getParameter('token_id').'&showwxtitle=1');
                        exit();
                    }else{
                        // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('err_code').' Error Message:'.$this->ClientResponseHandler->getParameter('err_msg')));
                        echo $this->ClientResponseHandler->getParameter('err_msg');
                        exit();
                    }
                }
                // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('status').' Error Message:'.$this->ClientResponseHandler->getParameter('message')));
                echo $this->ClientResponseHandler->getParameter('message');
            }else{
                // echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
                echo $this->PayHttpClient->getErrInfo();
            }
            //$this->display ('pay/mall_pay/wxpay', $data );
        }else{
            exit('参数错误');
        }
    }
	
}