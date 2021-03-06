<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );

class Wxpayreturn extends MY_Controller {
    
    function hotel_payreturn() {
//      $xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents ( 'php://input' );
        // $this->db->insert ( 'weixin_text', array('content'=>'return') );
        // $this->db->insert ( 'weixin_text', array('content'=>$xml,'edit_date'=>0) );
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'weixin';
        //      $this->db->insert ( 'pay_log', $data );

        //@Editor lGh 支付回调签名验证
//      $this->load->model('Pay/Wxpay_model');
//      $check=$this->Wxpay_model->wxpay_return_sign($data ['inter_id'],$arr);
//      if (!$check){
//          echo 'error';
//          exit;
//      }

        //配置采用pms单号还是本地单号进行支付
        $this->load->model ( 'hotel/Hotel_config_model' );
        $config_data = $this->Hotel_config_model->get_hotel_config ( $data ['inter_id'], 'HOTEL', 0, array (
            'ORDER_PAY_ORDERID'
        ) );
        if(!empty($config_data['ORDER_PAY_ORDERID'])&&$config_data['ORDER_PAY_ORDERID']=='web'){
            $this->load->model ( 'hotel/Order_check_model' );
            $order = $this->Order_check_model->get_order_by_weborderid ( $data ['inter_id'], $data ['out_trade_no'] );
            $arr ['out_trade_no']=$order['orderid'];
        }

        $data ['out_trade_no'] = $arr['out_trade_no'];

        $this->db->insert ( 'pay_log', $data );
MYLOG::w('pay_return-pay_result_log:insert_time:'.microtime().',orderid:'.$arr['out_trade_no'],'hotel_order'.DS.'pay_result','_read');
        if ($arr ["return_code"] == "FAIL") {
            $this->db->where ( array (
                    'orderid' => $arr ['out_trade_no']
                    // 'openid' => $openid 
            ) );
            $this->db->update ( 'hotel_orders', array (
                    'operate_reason' => '支付失败订单' 
            ) );
            // $this->load->model ( 'hotel/hotels_model' );
            // $this->hotels_model->fail_return ( $arr ['out_trade_no'] );
        } elseif ($arr ["result_code"] == "FAIL") {
            $this->db->where ( array (
                    'orderid' => $arr ['out_trade_no']
                    // 'openid' => $openid 
            ) );
            $this->db->update ( 'hotel_orders', array (
                    'operate_reason' => '支付失败订单' 
            ) );
            // $this->load->model ( 'hotel/Order_model' );
            // $this->hotels_model->fail_return ( $arr ['out_trade_no'] );
        } else {
            $this->load->helper ( 'common_helper' );
            
            $this->db->where ( array (
                    'orderid' => $arr ['out_trade_no']
                    // 'openid' => $openid 
            ) );
            $order = $this->db->get ( 'hotel_orders' )->row_array ();
            if ($order && $order ['paid'] == 0) {
                $this->db->where ( array (
                        'orderid' => $arr ['out_trade_no']
                        // 'openid' => $openid 
                ) );
                $this->db->update ( 'hotel_orders', array (
                        'paid' => 1,
                        'isdel' => 0                        
                ) );
                $this->load->model ( 'hotel/Order_model' );
                $this->Order_model->pay_return ( $arr ['out_trade_no'] );
            }
        }
        echo 'success';
    }
    
    function hotel_debt() {
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $inter_id = $this->uri->segment ( 3 );
        $debtid = $arr ['out_trade_no'];
        $data = array ();
        $data ['inter_id'] = $inter_id;
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr ['out_trade_no'];
        $data ['transaction_id'] = $arr ['transaction_id'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'weixin';
        $this->db->insert ( 'pay_log', $data );
        MYLOG::w ( json_encode ( $data ), 'hotel' . DS . 'wxpay_return' . DS . 'debt' );
        $this->load->model ( 'pay/Wxpay_model' );
        $check = $this->Wxpay_model->wxpay_return_sign ( $data ['inter_id'], $arr );
        if (! $check) {
            echo 'error';
            exit ();
        }
        $this->load->model ( 'hotel/Debts_model' );
        $debt = $this->Debts_model->get_debt_by_id ( $inter_id, $debtid );
        if ($debt && $debt['debt_state']==0) {
            if ($arr ["return_code"] == "SUCCESS") {
                $this->Debts_model->pay_return ( $inter_id, $debtid, $debt ['debt_type'],array('third_no'=>$data['transaction_id']) );
            } elseif ($arr ["result_code"] == "FAIL" || $arr ["return_code"] == "FAIL") {
            }
            echo 'success';
            exit;
        }
        echo 'error';
        exit ();
    }
    
    /**
     * 快乐付
     */
    function okpay_rtn() {
        //$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode (json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ($arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'okpay';
        $this->db->insert('pay_log', $data );

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'okpay');
            die;
        }
        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            MYLOG::w($data['out_trade_no'].'签名错误！', 'okpay');
            die;
        }

		$pay_status = 0;
		if ($arr ["return_code"] == "FAIL") {
		} elseif ($arr ["result_code"] == "FAIL") {
		} else {
			$pay_status = 3;
		}
		//存在订单号，则执行下面的处理
		if(!empty($arr['out_trade_no'])){
			$this->db->where ( array (
                    'out_trade_no' => $arr['out_trade_no'],
                    'inter_id' => $this->uri->segment(3)
			) );
            //先查询是否已经更改状态
            $order = $this->db->get ( 'okpay_orders' )->row_array ();
            if($order && $order['pay_status'] == 1){//未更改状态
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($arr['total_fee'], $order['pay_money'] *100);
                if( ! $total_dif ){

                    MYLOG::w($arr ['out_trade_no']. '微信支付回调返回total_fee['
                        . $arr['total_fee'] .']与订单金额[' . $order['pay_money']*100 .']不一致！','okpay');
                    die;
                }
                $this->db->where ( array (
                    'out_trade_no' => $arr['out_trade_no'],
                    'inter_id' => $this->uri->segment(3)
                ) );
            $this->db->update ('okpay_orders', array (
                    'pay_status' => $pay_status,
                    'trade_no'=>$arr['transaction_id'],
                    'pay_time'=>time(),
                    'update_time'=>time()
            ) );
                //发送模板消息 stgc 20161107
                if(3 == $pay_status){
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
                }
                //end 模板消息
            }
		}
        echo 'success';
    }

    /**
     * 打赏
     */
    function tips_rtn() {
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode (json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ($arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'tips';
        $this->db->insert('pay_log', $data );

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'tips');
            die;
        }
        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            MYLOG::w($data['out_trade_no'].'签名错误！', 'tips');
            die;
        }

        $pay_status = 0;
        if ($arr ["return_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'tips_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
        } elseif ($arr ["result_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
            ) );
            $this->db->update ( 'tips_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
        } else {
            $pay_status = 2;//成功
        }
        //存在订单号，则执行下面的处理
        if(!empty($arr['out_trade_no'])){
            $this->db->where ( array (
                'order_sn' => $arr['out_trade_no'],
                'inter_id' => $this->uri->segment(3),
            ) );
            //先查询是否已经更改状态
            $order = $this->db->get ( 'tips_orders' )->row_array ();
            if($order && $order['pay_status'] == 1){//未更改状态
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($arr['total_fee'], $order['pay_money'] *100);
                if( ! $total_dif ){

                    MYLOG::w($arr ['out_trade_no']. '微信支付回调返回total_fee['
                        . $arr['total_fee'] .']与订单金额[' . $order['pay_money']*100 .']不一致！','tips');
                    die;
                }
                $this->db->where ( array (
                    'order_sn' => $arr['out_trade_no'],
                    'inter_id' => $this->uri->segment(3),
                ) );
                $this->db->update ('tips_orders', array (
                    'pay_status' => $pay_status,
                    'trade_no'=>$arr['transaction_id'],
                    'pay_time'=>date('Y-m-d H:i:s'),
                ) );
                if(2 == $pay_status){//成功
                    //处理支付成功的
                    $this->load->model('tips/tips_orders_model');
                    $this->tips_orders_model->pay_return($order);
                }
            }
        }
        echo 'success';
    }

    //订餐支付回调通知
    function roomservice_rtn() {
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'wx_roomservice';
        $this->load->library('MYLOG');
 		$this->db->insert ( 'pay_log', $data );

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'roomservice');
            die;
        }
        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            MYLOG::w($data['out_trade_no'].'签名错误！', 'roomservice');
            die;
        }

 		/*$this->load->model('Pay/Wxpay_model');
 		$check=$this->Wxpay_model->wxpay_return_sign($data ['inter_id'],$arr);
		if (!$check){$this->_api_write_log($check,'roomservie');
 			echo 'error';
 			exit;
 		}*/

    //    $this->db->insert ( 'pay_log', $data );

        if ($arr ["return_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'roomservice_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
        } elseif ($arr ["result_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'roomservice_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
        } else {
            $this->load->helper ( 'common_helper' );


            $this->load->model ( 'roomservice/roomservice_orders_model' );
            $orderModel = $this->roomservice_orders_model;
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $order = $this->db->get ( 'roomservice_orders' )->row_array ();
            if ($order && $order ['pay_status'] == $orderModel::IS_PAYMENT_NOT) {
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($arr['total_fee'], $order['sub_total'] *100);
                if( ! $total_dif ){

                    MYLOG::w($arr ['out_trade_no']. '微信支付回调返回total_fee['
                        . $arr['total_fee'] .']与订单金额[' . $order['sub_total']*100 .']不一致！','roomservice');
                    die;
                }
                $this->db->where ( array (
                    'order_sn' => $arr ['out_trade_no'],
                    'inter_id' => $data ['inter_id'],
                ) );
                $this->db->update ( 'roomservice_orders', array (
                    'pay_status' => $orderModel::IS_PAYMENT_YES,
                    'pay_time' => date('Y-m-d H:i:s'),
                    'pay_money'=> $arr['total_fee'] / 100,
                    'trade_no' => $arr['transaction_id'],
                ) );
                $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
            }else{
                MYLOG::w($data['out_trade_no'].'订单信息有误！', 'roomservice');
                die;
            }
        }
        echo 'success';
    }
    
//门票支付回调通知
    function ticket_rtn() {
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'wx_ticket';
        $this->load->library('MYLOG');
        $this->db->insert ( 'pay_log', $data );

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'roomservice');
            die;
        }
        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            MYLOG::w($data['out_trade_no'].'签名错误！', 'roomservice');
            die;
        }

        /*$this->load->model('Pay/Wxpay_model');
        $check=$this->Wxpay_model->wxpay_return_sign($data ['inter_id'],$arr);
       if (!$check){$this->_api_write_log($check,'roomservie');
            echo 'error';
            exit;
        }*/

        //    $this->db->insert ( 'pay_log', $data );

        if ($arr ["return_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'roomservice_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
        } elseif ($arr ["result_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'roomservice_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
        } else {
            $this->load->helper ( 'common_helper' );


            $this->load->model ( 'roomservice/roomservice_orders_model' );
            $orderModel = $this->roomservice_orders_model;
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_sn' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $order = $this->db->get ( 'roomservice_orders' )->row_array ();
            if ($order && $order ['pay_status'] == $orderModel::IS_PAYMENT_NOT) {
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($arr['total_fee'], $order['sub_total'] *100);
                if( ! $total_dif ){

                    MYLOG::w($arr ['out_trade_no']. '微信支付回调返回total_fee['
                        . $arr['total_fee'] .']与订单金额[' . $order['sub_total']*100 .']不一致！','roomservice');
                    die;
                }
                $this->db->where ( array (
                    'order_sn' => $arr ['out_trade_no'],
                    'inter_id' => $data ['inter_id'],
                ) );
                $this->db->update ( 'roomservice_orders', array (
                    'pay_status' => $orderModel::IS_PAYMENT_YES,
                    'pay_time' => date('Y-m-d H:i:s'),
                    'pay_money'=> $arr['total_fee'] / 100,
                    'trade_no' => $arr['transaction_id'],
                ) );

                //判断店铺 即时确认 自动接单
                $arr_shop['shop_id'] = $order['shop_id'];
                $this->load->model ( 'roomservice/roomservice_shop_model' );
                $shop_info = $this->roomservice_shop_model->get($arr_shop);
                MYLOG::w(json_encode($shop_info).'自动接单后！', 'roomservice');
                if (!empty($shop_info) && $shop_info['instant_confirm'] == 1)
                {
                    $this->roomservice_orders_model->auto_update_status($order['inter_id'],$order);
                    MYLOG::w($data['out_trade_no'].'自动接单！', 'roomservice');
                }

                $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
            }else{
                MYLOG::w($data['out_trade_no'].'订单信息有误！', 'roomservice');
                die;
            }
        }
        echo 'success';
    }


    //预约核销支付回调通知
    function ticket_book_rtn()
    {
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'wx_ticket_book';
        $this->load->library('MYLOG');
        $this->db->insert ( 'pay_log', $data );

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'ticket');
            die;
        }
        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            MYLOG::w($data['out_trade_no'].'签名错误！', 'ticket');
            die;
        }

        /*$this->load->model('Pay/Wxpay_model');
        $check=$this->Wxpay_model->wxpay_return_sign($data ['inter_id'],$arr);
       if (!$check){$this->_api_write_log($check,'roomservie');
            echo 'error';
            exit;
        }*/

        //    $this->db->insert ( 'pay_log', $data );

        if ($arr ["return_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_no' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'ticket_orders_merge', array (
                'operate_reason' => '支付失败订单'
            ) );
        } elseif ($arr ["result_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_no' => $arr ['out_trade_no']
                // 'openid' => $openid
            ) );
            $this->db->update ( 'ticket_orders_merge', array (
                'operate_reason' => '支付失败订单'
            ) );
        } else {
            $this->load->helper ( 'common_helper' );

            //查询总订单信息
            $this->load->model('ticket/ticket_orders_merge_model');
            $where_arr = array(
                'inter_id' => $data['inter_id'],
                'order_no' => $arr['out_trade_no'],
                'pay_status' => 0
            );
            $order_merge = $this->ticket_orders_merge_model->order_info($where_arr);
            if (!empty($order_merge))
            {
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($arr['total_fee'], $order_merge['pay_fee'] *100);
                if(! $total_dif)
                {
                    MYLOG::w($arr ['out_trade_no']. '微信支付回调返回total_fee['
                        . $arr['total_fee'] .']与订单金额[' . $order_merge['pay_fee']*100 .']不一致！','ticket');
                    die;
                }

                $this->load->model ( 'roomservice/roomservice_orders_model' );
                $orderModel = $this->roomservice_orders_model;
                $this->db->where (array(
                    'inter_id' => $data ['inter_id'],
                    'merge_order_no' => $arr ['out_trade_no'],
                ));
                $orders = $this->db->get('roomservice_orders')->result_array();
                MYLOG::w(json_encode($orders), 'ticket');

                $this->db->trans_begin(); //开启事务
                $auto_confirm = 0;
                $auto_res = 1;
                if (!empty($orders))
                {
                    $order_res = 1;
                    foreach ($orders as $key=> $order)
                    {
                        if ($order['pay_status'] == $orderModel::IS_PAYMENT_NOT)
                        {
                            $this->db->where ( array (
                                'order_id' => $order ['order_id'],
                                'inter_id' => $data ['inter_id'],
                            ) );
                            $this->db->update ( 'roomservice_orders', array (
                                'pay_status' => $orderModel::IS_PAYMENT_YES,
                                'pay_time' => date('Y-m-d H:i:s'),
                                'pay_money'=> $order['sub_total'],
                                'trade_no' => $arr['transaction_id'],
                            ) );

                            $o_res = $this->db->affected_rows();
                            if ($o_res == 0)
                            {
                                $order_res = 0;
                            }

                            //判断店铺 即时确认 自动接单
                            if ($key == 0)
                            {
                                $arr_shop['shop_id'] = $order['shop_id'];
                                $this->load->model ( 'roomservice/roomservice_shop_model' );
                                $shop_info = $this->roomservice_shop_model->get($arr_shop);
                            }

                            MYLOG::w(json_encode($shop_info).'自动接单后！', 'ticket');
                            if (!empty($shop_info) && $shop_info['instant_confirm'] == 1)
                            {
                                $auto_confirm = 1;
                                $auto_res =  $this->roomservice_orders_model->auto_update_status($order['inter_id'],$order);
                                MYLOG::w($data['out_trade_no'].'自动接单！', 'ticket');
                            }

                            $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
                        }
                        else
                        {
                            $this->db->trans_rollback();
                            MYLOG::w($data['out_trade_no'].'订单信息已支付！', 'ticket');
                            die;
                        }
                    }
                }
                else
                {
                    $this->db->trans_rollback();
                    MYLOG::w($data['out_trade_no'].'订单信息有误！', 'ticket');
                    die;
                }

                //更改总单状态
                $update = array(
                    'update_time' => date('Y-m-d H:i:s'),
                    'pay_time' => date('Y-m-d H:i:s'),
                    'order_status' => $auto_confirm == 1 ? 1 : 0,
                    'pay_status' => 2,
                    'out_order_no' => $arr['transaction_id'],
                );
                MYLOG::w(json_encode($update).'更改的数据！', 'ticket');
                $where = array(
                    'merge_orderId' => $order_merge['merge_orderId'],
                );
                $res = $this->ticket_orders_merge_model->update_order($update,$where);
                MYLOG::w($auto_res.$res.$order_res.'状态！', 'ticket');
                if ($auto_res > 0 && $res > 0 && $order_res > 0)
                {
                    //提交事务
                    $this->db->trans_complete();
                }
                else
                {
                    $this->db->trans_rollback();
                    MYLOG::w($data['out_trade_no'].'更改订单失败！', 'ticket');
                    die();
                }
            }
        }
        echo 'success';
    }

    /**
     * 金陵彩虹跑 活动报名支付回调
     * author:沙沙
     */
    public function activity_rainbowRun_rtn()
    {
        $xml = file_get_contents ( 'php://input' );
        $arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
        $openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
        $data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['openid'] = $openid;
        $data ['out_trade_no'] = $arr['out_trade_no'];
        $data ['transaction_id'] = $arr['transaction_id'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $xml;
        $data ['type'] = 'wx_rainbowRun';
        $this->load->library('MYLOG');
        $this->db->insert ( 'pay_log', $data );

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'rainbowRun');
            die;
        }
        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            MYLOG::w($data['out_trade_no'].'签名错误！', 'rainbowRun');
            die;
        }

        if ($arr ["return_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_no' => $arr ['out_trade_no']
            ) );
            $this->db->update ( 'activity_rainbowrun_order', array (
                'operate_reason' => '支付失败订单'
            ) );
        } elseif ($arr ["result_code"] == "FAIL") {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_no' => $arr ['out_trade_no']
            ) );
            $this->db->update ( 'activity_rainbowrun_order', array (
                'operate_reason' => '支付失败订单'
            ) );
        } else {
            $this->load->helper ( 'common_helper' );

            //查询总订单信息
            $this->load->model('activity/rainbowRun_order_model');
            $filter['order_no'] = $arr['out_trade_no'];
            $filter['openid'] = $data['openid'];
            $order_merge = $this->rainbowRun_order_model->get_one($filter);
            if (!empty($order_merge))
            {
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif= float_precision_match($arr['total_fee'], $order_merge['pay_fee'] *100);
                if(! $total_dif)
                {
                    MYLOG::w($arr ['out_trade_no']. '微信支付回调返回total_fee['
                        . $arr['total_fee'] .']与订单金额[' . $order_merge['pay_fee']*100 .']不一致！','rainbowRun');
                    die;
                }

                $this->db->trans_begin(); //开启事务

                //更改总单状态
                $update = array(
                    'pay_time' => date('Y-m-d H:i:s'),
                    'pay_status' => 1,
                    'trade_order_no' => $arr['transaction_id'],
                );
                MYLOG::w(json_encode($update).'更改的数据！', 'rainbowRun');
                $where = array(
                    'act_id' => $order_merge['act_id'],
                );
                $res = $this->rainbowRun_order_model->update_order($where,$update);

                if ($res > 0)
                {
                    //提交事务
                    $this->db->trans_complete();
                }
                else
                {
                    $this->db->trans_rollback();
                    MYLOG::w($data['out_trade_no'].'更改订单失败！', 'rainbowRun');
                    die();
                }
            }
        }
        echo 'success';
    }

	/**
	 * @author libinyan@mofly.cn
	 * 未完善
	 */
	public function mall_rtn()
	{
// 		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
		$xml = file_get_contents ( 'php://input' );
		//$this->db->insert ( 'weixin_text', array('content'=>'return') );
		$this->db->insert ( 'weixin_text', array('content'=>$xml, 'edit_date'=> time() ) );
		$arr = json_decode ( json_encode ( simplexml_load_string ( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
		// 取得商户/子商户的openid
		$openid = empty ( $arr ['sub_openid'] ) ? $arr ['openid'] : $arr ['sub_openid'];
		$data = array ();
		$data ['inter_id'] = $this->uri->segment(3);
		$data ['openid'] = $openid;
		$data ['out_trade_no'] = $arr['out_trade_no'];
		$data ['transaction_id'] = $arr['transaction_id'];
		$data ['pay_time'] = time ();
		$data ['rtn_content'] = $xml;
		$data ['type'] = 'weixin';
		$this->db->insert ( 'pay_log', $data );
		
		
		//签名校验数据的合法性
		$this->load->model('pay/pay_model');
		$pay_config= $this->pay_model->get_pay_paras( $data['inter_id'] );
		$pay_key= isset($pay_config['key'])? $pay_config['key']: '';
		
		$params= (array) $arr;
		$sign= $this->get_sign($params, $pay_key);
		if($arr['sign'] != $sign){
		    die('签名参数错误！');
		}
		
		
		if ($arr ["return_code"] == "FAIL") {
		    //返回失败处理
		    
		} elseif ($arr ["result_code"] == "FAIL") {
		    //支付失败处理
		    
		} else {
			$this->load->model ('mall/shp_orders' );
			$this->shp_orders->update_pay_status($data ['inter_id'], $arr ['out_trade_no'], $openid, $arr ['transaction_id']);

//对于部分对接接口的客户需要回写订单/写回支付状态
if( $data ['inter_id']=='a453956624' ){
    $order= $this->shp_orders->find( array('transaction_id'=> $data['transaction_id'] ) );
     
    if($order && $order['transaction_id']){
        $this->load->model ('mall/shp_order_items' );
        $items= $this->shp_order_items->find( array('order_id'=> $order['order_id'] ) );
     
        $payment= TRUE;
        $this->load->library('Mall/Lib_kargo');
        $result= Lib_kargo::inst()->order_create($order, $items, $payment);
        if($result){
            //获取单号卡购的单号（以供查询之用）和key（解密卡号之用），写入订单中
            $this->orders_model->load($order['order_id'])->m_save( array(
                'out_order_id'=> $result['kc-ord-id'],
                'out_order_key'=> $result['kc-ord-key'],
            ) );
        }
    }
}
            
        }
        echo 'success';
    }

    public function get_sign( array $params, $key)
    {
        $fields= array('sign', );
        foreach ($params as $k => $v) {
            if( in_array($k, $fields) ) unset($params[$k]);
            elseif( !$v ) unset($params[$k]); //参数为空不参与签名
    
        }
        //签名步骤一：按字典序排序参数
        ksort($params);
        $string = http_build_query( $params, false ). "&key=". $key;
        return strtoupper(md5($string));
    }

    /**
     * 会员支付通知回调处理操作
     */
    public function vipokpay(){
        $xml = file_get_contents('php://input');
        $this->_api_write_log($xml,'return_xml');
//        $return_data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
        $return_data = (array) simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA );
        $this->_api_write_log(json_encode($return_data),'return_data');
//        $result = (array) simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA );
        $sub_openid = empty($return_data['sub_openid'])?$return_data['openid']:$return_data['sub_openid'];
        $interId = $this->uri->segment(3);
        $openid = !empty($sub_openid)?$sub_openid:$this->uri->segment(4);
        $orderId = $this->uri->segment(5);
        $orderMoney = isset($return_data['total_fee'])?$return_data['total_fee']:0;

        $okxml = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        $failxml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';

        if($return_data['result_code']!='SUCCESS') {
            echo $failxml;exit;
        }

        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($interId);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if( empty($pay_key) ){
            Soma_base::inst()->show_exception($return_data['out_trade_no']. '微信支付回调商户配置信息不完整！');
            $this->_api_write_log('微信支付回调商户配置信息不完整','show_exception');
            echo $failxml;exit;
        }
        $params= (array) $return_data;
        $sign= $this->get_sign($params, $pay_key);
        if($return_data['sign'] != $sign){
            $file= 'vip_wxpay_return_signerr';
            $this->_api_write_log($xml, $file);      //记录下签名失败的记录
            Soma_base::inst()->show_exception($return_data['out_trade_no']. '微信支付回调签名错误！');
            $this->_api_write_log('微信支付回调签名错误','show_exception');
            echo $failxml;exit;
        }

        //查询订单信息
        $_token = $this->_get_Token();
        $post_order_url = INTER_PATH_URL.'depositorder/get_order';
        $post_order_data = array(
            'token'=>$_token,
            'inter_id'=>$interId,
            'openid'=>$openid,
            'orderId'=>$orderId,
        );
        $order_info = $this->doCurlPostRequest( $post_order_url , $post_order_data );
        $this->_api_write_log(json_encode($order_info),'vipokpay_order_info');
        if(isset($order_info['err']) || !isset($order_info['data']) || empty($order_info['data'])){
            $this->_api_write_log('empty order info','vipokpay_status');
            echo $failxml;exit;
        }
        if($interId!=$interId || $interId != $order_info['data']['inter_id'] ){
            $this->_api_write_log('error is interId','vipokpay_status');
            echo $failxml;exit;
        }
        if($order_info['data']['order_num'] != $return_data['out_trade_no'] ){
            $this->_api_write_log('order number is error','vipokpay_status');
            echo $failxml;exit;
        }


        //========判断订单的order_id, openid, 总金额是否相符
        $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
        $total_dif= float_precision_match($return_data['total_fee'], $order_info['data']['pay_money']*100);
        if( ! $total_dif ){
            $file= 'vip_wxpay_return_exception';
            $this->write_log($xml, $file);      //记录下签名失败的记录
            Soma_base::inst()->show_exception($return_data['out_trade_no']. '微信支付回调返回total_fee['
                . $return_data['total_fee'] .']与订单金额[' . $order_info['data']['pay_money']*100 .']不一致！');
            echo $failxml;exit;
        }

        if($order_info['data']['pay_status']=='t'){
            $this->_api_write_log('order pay is success','vipokpay_status');
            echo $okxml;exit;
        }
        //查询购卡信息
        $post_cardinfo_url = PMS_PATH_URL."depositcard/getinfo";
        $post_cardinfo_data = array(
            'inter_id'=>$interId,
            'deposit_card_id'=>$order_info['data']['deposit_card_id'],
        );
        $card_info= $this->doCurlPostRequest( $post_cardinfo_url , $post_cardinfo_data );
        $this->_api_write_log(json_encode($card_info),'vipokpay_card_info');
        if(!isset($card_info['data']) || !$card_info['data']  ){
            $card_info['data']['is_package'] = 'f';
            $deposit_data['is_balance'] = 't';
            $deposit_data['distribution_money'] = 0;
        }else{
            $deposit_data = $card_info['data'];
        }
        //修改订单信息，以及增加储值的金额
        $post_upOrder_url = INTER_PATH_URL.'depositorder/update_order';
        $post_upOrder_data = array(
            'token'=>$_token,
            'inter_id'=>$interId,
            'openid'=>$openid,
            'deposit_card_pay_id'=>$orderId,
        );
        $update_result = $this->doCurlPostRequest( $post_upOrder_url , $post_upOrder_data );
        $this->_api_write_log(json_encode($update_result),'vipokpay_update_result');
        if($update_result['err']>0){
            $this->_api_write_log('order info error','vipokpay_status');
            echo $failxml;exit;
        }else{
            //判断重置是否计入余额
            if(isset($deposit_data['is_balance']) && $deposit_data['is_balance']=='t'){
                //增加储值
                $add_deposit_url = INTER_PATH_URL.'deposit/add';
                $add_deposit_data = array(
                    'token'=>$_token,
                    'inter_id'=>$interId,
                    'openid'=>$openid,
                    'count'=>($orderMoney/100),
                    'uu_code'=>uniqid(),
                    'module'=>'vip',
                    'scene'=>'vip',
                    'note'=>'会员充值储值',
					'deposit_type'=>isset($deposit_data['deposit_type'])?$deposit_data['deposit_type']:'c',
                    'order_id'  => $return_data['transaction_id'], //微信的流水单号
                    'local_id'  => $orderId
                );
                $add_deposit = $this->doCurlPostRequest( $add_deposit_url , $add_deposit_data );
                $this->_api_write_log(json_encode($add_deposit),'vipokpay_add_deposit');
                if($add_deposit['err']>0){
                    $this->_api_write_log('balance is error','vipokpay_status');
                }
            }
            //检查是否属于泛分销
            MYLOG::w("Wechat pay | Type Order Info | ".@json_encode($order_info),'membervip/debug-log');
            if( isset($order_info['data']['distribution_num']) && $order_info['data']['distribution_num'] > 0 &&  $order_info['data']['distribution_type'] == 'FANS' ){
                $where = array(
                    'id'=>$order_info['data']['distribution_num'],
                    'inter_id'=>$interId,
                );
                $this->load->model('membervip/common/Public_model','p_model');
                $pan_sales = $this->p_model->get_info($where,'distribution_member');
                MYLOG::w("Wechat pay | Type Get pan sales info | ".@json_encode(array('result'=>$pan_sales,'where'=>$where)),'membervip/debug-log');
                if(!empty($pan_sales)){
                    $this->load->model('distribute/Idistribute_model','idistribute');
                    $fansInfo = $this->idistribute->fans_is_saler($interId,$pan_sales['open_id']);
                    MYLOG::w("Wechat pay | Type Get dis fansInfo | ".@json_encode(array('result'=>$fansInfo,'params'=>$pan_sales)),'membervip/debug-log');
                    $salesInfo = json_decode($fansInfo,true);
                    if($salesInfo && $salesInfo['typ'] == 'FANS'){
                        //TODO
                        $this->load->model('pay/Company_pay_model','pay_model');
                        $reward = 0;
                        switch ($order_info['data']['deposit_card_id']){
                            case 138:$reward = 80;break;
                            case 139:$reward = 48;break;
                            case 173:$reward = 10;break;
                        }
//                        $reward = 0.01;
                        //插入发放记录
                        $insert_data = array(
                            'inter_id'=>$interId,
                            'open_id'=>$openid,
                            'type'=>'dis_pan',
                            'record_title'=>'购卡泛分销',
                            'sales_id'  => $order_info['data']['distribution_num'],
                            'reward'=>$reward,
                            'sn'=>"card{$order_info['data']['order_num']}",
                            'createtime'=>date('Y-m-d H:i:s'),
                            'status'=>'f'
                        );
                        $add_sales = $this->p_model->add_data($insert_data,'distribution_record');
                        MYLOG::w("Wechat pay | Type Insert record 插入发放记录| ".@json_encode(array('result'=>$add_sales,'data'=>$insert_data)),'membervip/debug-log');
                        $distribute_arr = array(
                            'inter_id'=>$interId,
                            'hotel_id'=>0,
                            'saler'=>$salesInfo['info']['saler'],
                            'grade_openid'=>$openid,
                            'grade_table'=>'iwide_member4_fans',
                            'grade_id'=>$order_info['data']['deposit_card_pay_id'],
                            'order_amount'=>$order_info['data']['pay_money'],
                            'grade_total'=>$reward,
                            'remark'=>$deposit_data['title'],
                            'grade_amount'=>$order_info['data']['pay_money'],
                            'order_time'    => date("Y-m-d H:i:s",time()),
                            'status'=>1,
                            'grade_typ' => 2,
                            'product'=>$deposit_data['title'],
                            'order_status'=>'已完成'
                        );
                        $distribute_result = $this->idistribute->create_ext_grade( $distribute_arr );
                        MYLOG::w("Wechat pay | Type create_dist 绩效发放记录| ".@json_encode(array('result'=>$distribute_result,'param'=>$distribute_arr)),'membervip/debug-log');
                        if($distribute_result){
                            $save_data = array(
                                'status'=>'t'
                            );
                            $params = array(
                                'inter_id'=>$interId,
                                'open_id'=>$openid,
                                'sn'=>"card{$order_info['data']['order_num']}",
                                'type'=>'dis_pan',
                                'status'=>'f'
                            );
                            $update_sales = $this->p_model->update_save($params,$save_data,'distribution_record');
                            MYLOG::w("Wechat pay | Type update 绩效发放记录| ".@json_encode(array('result'=>$update_sales,'data'=>$save_data)),'membervip/debug-log');
                        }
                    }
                }
            }
            //加入分销数据
            else if( isset( $deposit_data['distribution_money']) && $deposit_data['distribution_money']>0 && isset($order_info['data']['distribution_num']) && $order_info['data']['distribution_num'] > 0 && $card_info['data']['is_distribution'] =='t'){
                $this->load->model('distribute/Idistribute_model','idistribute');
                $distribute_arr = array(
                    'inter_id'=>$interId,
                    'hotel_id'=>0,
                    'saler'=>$order_info['data']['distribution_num'],
                    'grade_openid'=>$openid,
                    'grade_table'=>'iwide_member4_order',
                    'grade_id'=>$order_info['data']['deposit_card_pay_id'],
                    'grade_id_name'=>'充值订单ID',
                    'order_amount'=>$order_info['data']['pay_money'],
                    'grade_total'=>$deposit_data['distribution_money'],
                    'grade_amount'=>$order_info['data']['pay_money'],
                    'grade_amount_rate'=>$deposit_data['distribution_money'],
                    'grade_rate_type'=>0,
                    'status'=>1,
                    'remark'=>$deposit_data['title'],
                    'product'=>$deposit_data['title'],
                    'order_status'=>1,
                    'order_id'=>$order_info['data']['order_num'],
                );
                $distribute_result = $this->idistribute->create_dist( $distribute_arr );
                if(!$distribute_result){
                    //不成功写入日志
                    $log_data = array(
                        'msg'=>'分销记录失败',
                        'info'=>$distribute_arr,
                    );
                    MYLOG::w("Distribution  | Type send 绩效发放 Failed | ".@json_encode($log_data),'membervip/distribution');
//                    $this->vip_order_write_log(serialize($log_data) );
                }
            }

            //赠送套餐
            if($card_info['data']['is_package']=='t'){
                $packge_url = INTER_PATH_URL.'package/give';
                $package_data = array(
                    'token'=>$_token,
                    'inter_id'=>$interId,
                    'openid'=>$openid,
                    'uu_code'=>uniqid(),
                    'package_id'=>$card_info['data']['package_id'],
                );
                $package_deposit = $this->doCurlPostRequest( $packge_url , $package_data );
                $this->_api_write_log(json_encode($package_deposit),'vipokpay_package');
                if($package_deposit['err']>0){
                    $this->_api_write_log('package is error','vipokpay_status');
                }
            }

            //入账
            //if($order_info['data']['is_bill']=='f' && $order_info['data']['inter_id']=='a474597291'){
            if($order_info['data']['is_bill']=='f' && $order_info['data']['inter_id']=='a464919542'){
                $into_bill_url = INTER_PATH_URL."depositorder/intobill";
                $req_data = array(
                    'inter_id'=>$interId,
                    'openid'=>$openid,
                    'deposit_card_pay_id'=>isset($order_info['data']['deposit_card_pay_id'])?$order_info['data']['deposit_card_pay_id']:0,
                    'transaction_id'=>$return_data['transaction_id'],
                    'amount'=>($orderMoney/100),
                    'deposit_type'=>isset($deposit_data['deposit_type'])?$deposit_data['deposit_type']:'c'
                );
                $res = $this->doCurlPostRequest($into_bill_url,$req_data);
                $this->_api_write_log(json_encode($res),'vipokpay_intobill');
            }

        }
        echo $okxml;
        exit(0);
    }

    //获取授权token
    protected function _get_Token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        return isset($token_info['data'])?$token_info['data']:"";
    }

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function _api_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'membervip'. DS.'returnpay'.DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->_api_write_log(serialize($log_data) );
        return json_decode($res,true);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
