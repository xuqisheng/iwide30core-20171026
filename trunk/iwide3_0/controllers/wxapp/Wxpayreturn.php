<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wxpayreturn extends MY_Controller {
    
	function hotel_payreturn() {
// 		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
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
// 		$this->db->insert ( 'pay_log', $data );
		
		//@Editor lGh 支付回调签名验证
// 		$this->load->model('Pay/Wxpay_model');
// 		$check=$this->Wxpay_model->wxpay_return_sign($data ['inter_id'],$arr);
// 		if (!$check){
// 			echo 'error';
// 			exit;
// 		}
		
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
       /* $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras( $data['inter_id'] );
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';

        $params= (array) $arr;
        $sign= $this->get_sign($params, $pay_key);
        if($arr['sign'] != $sign){
            die('签名参数错误！');
        }*/
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
                    $this->load->model('okpay/okpay_msgauth_model');
                    $admins = $this->okpay_msgauth_model->get_auth_admins_openid($order['inter_id']);
                    if(!empty($admins)){
                        foreach($admins as $k=>$v){
                            $order['openid'] = $v['openid'];
                            $res = $this->Template_msg_model->send_okpay_success_msg ( $order, 'okpay_order_notice' );
                        }
                    }
                }
                //end 模板消息
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
	
	
	
	
	public function vipokpay(){
        $xml = file_get_contents('php://input');
        $return_data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
        $sub_openid = empty($return_data['sub_openid'])?$return_data['openid']:$return_data['sub_openid'];
        $inter_id = $this->uri->segment(3);
        $openid = !empty($sub_openid)?$sub_openid:$this->uri->segment(4);
        $order_id = $this->uri->segment(5);
//        $return_data['sp_inter_id'] = $inter_id;
//        $return_data['sp_openid'] = $openid;
//        $return_data['sp_order_id'] = $order_id;

        $okxml = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        $this->_api_write_log(json_encode($return_data),'vipokpay');
        if($return_data['result_code']!='SUCCESS' || $return_data['return_code']!='SUCCESS'){
            echo '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
            exit;
        }

        $_token = $this->_get_Token();

        //查询订单信息
        $post_order_url = INTER_PATH_URL.'depositorder/get_order';
        $post_order_data = array(
            'token'=>$_token,
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'orderId'=>$order_id,
        );
        $order_info = $this->doCurlPostRequest($post_order_url,$post_order_data);
        $this->_api_write_log(json_encode($order_info),'vipokpay_order_info');
        if(isset($order_info['err']) || !isset($order_info['data']) || !isset($order_info['data']['deposit_card_id'])){
            echo $okxml;exit;
        }

        //查询购卡信息
        $post_cardinfo_url = PMS_PATH_URL."depositcard/getinfo";
        $post_cardinfo_data = array(
            'inter_id'=>$inter_id,
            'deposit_card_id'=>$order_info['data']['deposit_card_id'],
        );
        $card_info= $this->doCurlPostRequest( $post_cardinfo_url , $post_cardinfo_data );
        $this->_api_write_log(json_encode($card_info),'vipokpay_card_info');
        $deposit_data = array();
        if(!isset($card_info['data']) || !$card_info['data']){
            $deposit_data['deposit_type'] = 'c';
        }else{
            $deposit_data = $card_info['data'];
        }

        if($order_info['data']['is_bill']=='f'){
            $into_bill_url = INTER_PATH_URL."depositorder/intobill";
            $req_data = array(
                'inter_id'=>$inter_id,
                'openid'=>$openid,
                'transaction_id'=>$return_data['transaction_id'],
                'amount'=>$return_data['total_fee'],
                'deposit_type'=>isset($deposit_data['deposit_type'])?$deposit_data['deposit_type']:'c'
            );
            $res = $this->doCurlPostRequest($into_bill_url,$req_data);
            $this->_api_write_log(json_encode($res),'vipokpay_intobill');
        }
        echo $okxml;exit;
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