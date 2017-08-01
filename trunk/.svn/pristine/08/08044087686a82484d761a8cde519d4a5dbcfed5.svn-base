<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wftpayreturn extends MY_Controller {
    
	function hotel_payreturn() {
		$this->load->library('MYLOG');
		$this->load->library('WftPay/ClientResponseHandler',null,'ClientResponseHandler');
        $xml = file_get_contents('php://input');
		// $this->db->insert('weixin_text',array('content'=>'wftreturn'.$xml,'edit_date'=>date("Y-m-d H:i:s")));
        $this->ClientResponseHandler->setContent($xml);
		//var_dump($this->ClientResponseHandler->setContent($xml));
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        //获取配置
        $this->load->model ( 'pay/Pay_model' );
		$pay_paras = $this->Pay_model->get_pay_paras ( $data ['inter_id'], 'weifutong' );

        $openid = $this->ClientResponseHandler->getParameter('sub_openid');
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $this->ClientResponseHandler->getParameter('out_trade_no');
        $data ['transaction_id'] = $this->ClientResponseHandler->getParameter('transaction_id');
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $this->ClientResponseHandler->getContent();
        $data ['type'] = 'wftpay';

        //配置采用pms单号还是本地单号进行支付
        $this->load->model ( 'hotel/Hotel_config_model' );
        $config_data = $this->Hotel_config_model->get_hotel_config ( $data ['inter_id'], 'HOTEL', 0, array (
                'ORDER_PAY_ORDERID'
        ) );
        if(!empty($config_data['ORDER_PAY_ORDERID'])&&$config_data['ORDER_PAY_ORDERID']=='web'){
            $this->load->model ( 'hotel/Order_check_model' );
            $order = $this->Order_check_model->get_order_by_weborderid ( $data ['inter_id'], $data ['out_trade_no'] );
            $data ['out_trade_no']=$order['orderid'];
        }
        
        $this->db->insert ( 'pay_log', $data );

        $this->db->where ( array (
                'orderid' => $data ['out_trade_no']
        ) );
        $order = $this->db->get ( 'hotel_orders' )->row_array ();

        if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
            $this->ClientResponseHandler->setKey($pay_paras['sub_key_h_'.$order['hotel_id']]);
        else
            $this->ClientResponseHandler->setKey($pay_paras['key']);

        if($this->ClientResponseHandler->isTenpaySign()){
            if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                //echo $this->ClientResponseHandler->getParameter('status');
                //此处可以在添加相关处理业务，校验通知参数中的商户订单号out_trade_no和金额total_fee是否和商户业务系统的单号和金额是否一致，一致后方可更新数据库表中的记录。 
                //更改订单状态
				if ($order && $order ['paid'] == 0) {
					$this->db->where ( array (
							'orderid' => $data ['out_trade_no']
					) );
					$this->db->update ( 'hotel_orders', array (
							'paid' => 1,
							'isdel' => 0						
					) );
					$this->load->model ( 'hotel/Order_model' );
					$this->Order_model->pay_return ( $data ['out_trade_no'] );
				}
                MYLOG::w(json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }else{
            	$this->db->where ( array (
            			'orderid' => $data ['out_trade_no']
            	) );
            	$this->db->update ( 'hotel_orders', array (
            			'operate_reason' => '支付失败订单' 
            	) );
                echo 'success';
                exit();
            }
        }else{
            echo 'failure';
        }
    }

    //okpay快乐付威富通支付通知
    public function okpay_payreturn(){
        $this->load->library('MYLOG');
        $this->load->library('WftPay/ClientResponseHandler',null,'ClientResponseHandler');
        $xml = file_get_contents('php://input');
        // $this->db->insert('weixin_text',array('content'=>'wftreturn'.$xml,'edit_date'=>date("Y-m-d H:i:s")));
        $this->ClientResponseHandler->setContent($xml);
        //var_dump($this->ClientResponseHandler->setContent($xml));
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);

        //获取配置
        $this->load->model ( 'pay/Pay_model' );
        $pay_paras = $this->Pay_model->get_pay_paras ( $data ['inter_id'], 'weifutong' );

        $openid = $this->ClientResponseHandler->getParameter('sub_openid');
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $this->ClientResponseHandler->getParameter('out_trade_no');
        $data ['transaction_id'] = $this->ClientResponseHandler->getParameter('transaction_id');
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $this->ClientResponseHandler->getContent();
        $data ['type'] = 'okpay_wftpay';
        $this->db->insert ( 'pay_log', $data );
        $this->db->where ( array (
            'out_trade_no' => $data ['out_trade_no'],
            'inter_id' => $data['inter_id']
        ) );
        //先查询是否已经更改状态
        $order = $this->db->get ( 'okpay_orders' )->row_array ();
        if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
            $this->ClientResponseHandler->setKey($pay_paras['sub_key_h_'.$order['hotel_id']]);
        else
            $this->ClientResponseHandler->setKey($pay_paras['key']);

        if($this->ClientResponseHandler->isTenpaySign()){
            if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                //添加金额判断
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($this->ClientResponseHandler->getParameter('total_fee'), $order['pay_money'] *100);
                if( ! $total_dif ){
                    MYLOG::w($data ['out_trade_no'].' | 威富通支付回调返回金额total_fee【'.$this->ClientResponseHandler->getParameter('total_fee').'】与订单金额【'.$order['pay_money'] *100 .'】不一致','wftpay');
                    echo 'error';
                    die;
                }

                //openID判断
                if($openid != $order['openid']){
                    MYLOG::w($data ['out_trade_no'].' | 威富通支付回调返回openid【'.$openid.'】与订单openid【'.$order['openid'] .'】不一致','wftpay');
                    echo 'error';
                    die;
                }
                if($order && $order['pay_status'] == 1){//未更改状态
                    $this->db->where ( array (
                        'out_trade_no' => $data['out_trade_no'],
                        'inter_id' => $data['inter_id']
                    ) );
                    $this->db->update ('okpay_orders', array (
                        'pay_status' => 3,
                        'trade_no'=>$data['transaction_id'],
                        'pay_time'=>time(),
                        'update_time'=>time()
                    ) );
                    //发送模板消息 stgc 20161229
                    try{
                        $order['pay_time'] = time();
                        $this->load->model ( 'plugins/Template_msg_model' );
                        //发送给用户
                        $res = $this->Template_msg_model->send_okpay_success_msg ( $order, 'okpay_order_success' );
                        //发送给管理员 先查一次授权的管理员
                        $this->load->model('okpay/okpay_type_model');
                        $admins = $this->okpay_type_model->get_type_saler_info($order['inter_id'],$order['pay_type']);
                        if(!empty($admins)){
                            foreach($admins as $k=>$v){
                                $order['openid'] = $v['openid'];
                                $res = $this->Template_msg_model->send_okpay_success_msg ( $order, 'okpay_order_notice' );
                            }
                        }
                        //添加打印订单操作 situguanchen 2017-03-20
                        $this->load->model ( 'plugins/Print_model' );
                        $res  = $this->Print_model->print_okpay_order ($order,'okpay_pay_success');
                    }catch(Exception $e){

                    }
                    //end 模板消息
                }
                MYLOG::w(json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }else{
                MYLOG::w('okpay威富通'.json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }
        }else{
            MYLOG::w('okpay威富通'.json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
            echo 'failure';
        }
    }

    //微服务威富通支付通知
    public function roomservice_rtn(){
        $this->load->library('MYLOG');
        $this->load->library('WftPay/ClientResponseHandler',null,'ClientResponseHandler');
        $xml = file_get_contents('php://input');
        $this->ClientResponseHandler->setContent($xml);
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);

        //获取配置
        $this->load->model ( 'pay/Pay_model' );
        $pay_paras = $this->Pay_model->get_pay_paras ( $data ['inter_id'], 'weifutong' );

        $openid = $this->ClientResponseHandler->getParameter('sub_openid');
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $this->ClientResponseHandler->getParameter('out_trade_no');
        $data ['transaction_id'] = $this->ClientResponseHandler->getParameter('transaction_id');
        $data ['total_fee'] = $this->ClientResponseHandler->getParameter('total_fee');
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $this->ClientResponseHandler->getContent();
        $data ['type'] = 'roomservice_wftpay';
        $this->db->insert ( 'pay_log', $data );
        $this->db->where ( array (
            'order_sn' => $data ['out_trade_no'],
            'inter_id' => $data['inter_id']
        ) );
        //先查询是否已经更改状态
        $order = $this->db->get ( 'roomservice_orders' )->row_array ();
        if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
            $this->ClientResponseHandler->setKey($pay_paras['sub_key_h_'.$order['hotel_id']]);
        else
            $this->ClientResponseHandler->setKey($pay_paras['key']);

        if($this->ClientResponseHandler->isTenpaySign()){
            if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                //添加金额判断
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($this->ClientResponseHandler->getParameter('total_fee'), $order['sub_total'] *100);
                if( ! $total_dif ){
                    MYLOG::w($data ['out_trade_no'].' | 威富通支付回调返回金额total_fee【'.$this->ClientResponseHandler->getParameter('total_fee').'】与订单金额【'.$order['sub_total'] *100 .'】不一致','wftpay');
                    echo 'error';
                    die;
                }

                //openID判断
                if($openid != $order['openid']){
                    MYLOG::w($data ['out_trade_no'].' | 威富通支付回调返回openid【'.$openid.'】与订单openid【'.$order['openid'] .'】不一致','wftpay');
                    echo 'error';
                    die;
                }
                $this->load->model('roomservice/roomservice_orders_model');
                $orderModel = $this->roomservice_orders_model;
                if($order && $order['pay_status'] == $orderModel::IS_PAYMENT_NOT){//未更改状态
                    $this->db->where ( array (
                        'order_sn' => $data['out_trade_no'],
                        'inter_id' => $data['inter_id']
                    ) );
                    $this->db->update ('roomservice_orders', array (
                        'pay_status' => $orderModel::IS_PAYMENT_YES,
                        'pay_time' => date('Y-m-d H:i:s'),
                        'pay_money'=> $data['total_fee'] / 100,
                        'trade_no' => $data['transaction_id'],
                    ) );
                    $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
                }
                MYLOG::w(json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }else{
                MYLOG::w('roomservice威富通:resul_code != 0'.json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }
        }else{
            MYLOG::w('roomservice威富通 验签失败'.json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
            echo 'failure';
        }
    }


    //微服务门票威富通支付通知
    public function ticket_rtn(){
        $this->load->library('MYLOG');
        $this->load->library('WftPay/ClientResponseHandler',null,'ClientResponseHandler');
        $xml = file_get_contents('php://input');
        $this->ClientResponseHandler->setContent($xml);
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);

        //获取配置
        $this->load->model ( 'pay/Pay_model' );
        $pay_paras = $this->Pay_model->get_pay_paras ( $data ['inter_id'], 'weifutong' );

        $openid = $this->ClientResponseHandler->getParameter('sub_openid');
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $this->ClientResponseHandler->getParameter('out_trade_no');
        $data ['transaction_id'] = $this->ClientResponseHandler->getParameter('transaction_id');
        $data ['total_fee'] = $this->ClientResponseHandler->getParameter('total_fee');
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $this->ClientResponseHandler->getContent();
        $data ['type'] = 'roomservice_wftpay';
        $this->db->insert ( 'pay_log', $data );
        $this->db->where ( array (
            'order_sn' => $data ['out_trade_no'],
            'inter_id' => $data['inter_id']
        ) );
        //先查询是否已经更改状态
        $order = $this->db->get ( 'roomservice_orders' )->row_array ();
        if(isset($pay_paras['sub_key_h_'.$order['hotel_id']]) && !empty($pay_paras['sub_key_h_'.$order['hotel_id']]))
            $this->ClientResponseHandler->setKey($pay_paras['sub_key_h_'.$order['hotel_id']]);
        else
            $this->ClientResponseHandler->setKey($pay_paras['key']);

        if($this->ClientResponseHandler->isTenpaySign()){
            if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                //添加金额判断
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($this->ClientResponseHandler->getParameter('total_fee'), $order['sub_total'] *100);
                if( ! $total_dif ){
                    MYLOG::w($data ['out_trade_no'].' | 威富通支付回调返回金额total_fee【'.$this->ClientResponseHandler->getParameter('total_fee').'】与订单金额【'.$order['sub_total'] *100 .'】不一致','wftpay');
                    echo 'error';
                    die;
                }

                //openID判断
                if($openid != $order['openid']){
                    MYLOG::w($data ['out_trade_no'].' | 威富通支付回调返回openid【'.$openid.'】与订单openid【'.$order['openid'] .'】不一致','wftpay');
                    echo 'error';
                    die;
                }
                $this->load->model('roomservice/roomservice_orders_model');
                $orderModel = $this->roomservice_orders_model;
                if($order && $order['pay_status'] == $orderModel::IS_PAYMENT_NOT){//未更改状态
                    $this->db->where ( array (
                        'order_sn' => $data['out_trade_no'],
                        'inter_id' => $data['inter_id']
                    ) );
                    $this->db->update ('roomservice_orders', array (
                        'pay_status' => $orderModel::IS_PAYMENT_YES,
                        'pay_time' => date('Y-m-d H:i:s'),
                        'pay_money'=> $data['total_fee'] / 100,
                        'trade_no' => $data['transaction_id'],
                    ) );

                    //判断店铺 即时确认 自动接单
                    $arr_shop['shop_id'] = $order['shop_id'];
                    $this->load->model ( 'roomservice/Roomservice_shop_model' );
                    $shop_info = $this->roomservice_shop_model->get($arr_shop);
                    if (!empty($shop_info) && $shop_info['instant_confirm'] == 1)
                    {
                        $this->roomservice_orders_model->auto_update_status($order['inter_id'],$order);
                    }

                    $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
                }
                MYLOG::w(json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }else{
                MYLOG::w('roomservice威富通:resul_code != 0'.json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
                echo 'success';
                exit();
            }
        }else{
            MYLOG::w('roomservice威富通 验签失败'.json_encode($this->ClientResponseHandler->getAllParameters()),'wftpay');
            echo 'failure';
        }
    }
}
