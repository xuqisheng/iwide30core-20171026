<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Lakalapayreturn extends MY_Controller {
    
	function hotel_payreturn() {
		$body = file_get_contents ( 'php://input' );
		$this->load->library('MYLOG');
        MYLOG::w($body,'lakalapayreturn');
        $sign = $_SERVER['HTTP_SIGN'];
        MYLOG::w($sign,'lakalapayreturn_sign');

        $arr = json_decode($body,true);
		

		$data = array ();
        $data ['inter_id'] = $arr['data']['subject'];
        //获取配置
        $this->load->model ( 'pay/Pay_model' );
		$pay_paras = $this->Pay_model->get_pay_paras ( $data ['inter_id'], 'lakala' );

        
        $data ['out_trade_no'] = $arr['data']['order_no'];
        $data ['transaction_id'] = $arr['data']['transaction_no'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $body;
        $data ['type'] = 'lakalapay';

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
        

        $this->db->where ( array (
                'orderid' => $data ['out_trade_no']
        ) );
        $order = $this->db->get ( 'hotel_orders' )->row_array ();

        $data ['openid'] = $order['openid'];
        $this->db->insert ( 'pay_log', $data );
        //读取公钥文件
        $pubKey = file_get_contents("../certs/paymax_rsa_public_key_".$pay_paras ['mch_id'].".pem");

        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($body, base64_decode($sign), $res);

        //释放资源
        openssl_free_key($res);

        if($result){
            if($arr['type'] == "CHARGE" && $arr['data']['status'] == "SUCCEED"){
               //支付成功
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
                MYLOG::w($body,'lakalapay_sucess');
                ob_clean();
                echo 'success';
                exit();
            }else{
            	$this->db->where ( array (
            			'orderid' => $data ['out_trade_no']
            	) );
            	$this->db->update ( 'hotel_orders', array (
            			'operate_reason' => '支付失败订单' 
            	) );
            	ob_clean();
                echo 'success';
                exit();
            }
        }else{
        	ob_clean();
            echo 'failure';
        }
	}
	
}
