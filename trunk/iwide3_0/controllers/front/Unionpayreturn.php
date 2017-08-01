<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Unionpayreturn extends MY_Controller {
    
	function hotel_payreturn() {
		$body = $_POST;
		$this->load->library('MYLOG');
        $arr = json_encode($body);
        MYLOG::w($arr,'unionpayreturn');

		$data = array ();
        $data ['inter_id'] = $this->uri->segment(3);
        $data ['out_trade_no'] = str_replace('yinlian','_',str_replace('union','-',$body['orderId']));
        $data ['transaction_id'] = $body['queryId'];
        $data ['pay_time'] = time ();
        $data ['rtn_content'] = $arr;
        $data ['type'] = 'unionpay';

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

        if (isset ( $body ['signature'] )) {
            require_once APPPATH.'/libraries/UnionPay/acp_service.php';
            com\unionpay\acp\sdk\UnionPayConfig::setVerifyCertDir ( '../certs/');
            $result =  com\unionpay\acp\sdk\AcpService::validate ( $body );

            if($result){
                if($body ['respCode']=='00' || $body ['respCode']=='A6'){
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
	function hotel_refund_return(){
        $body = $_POST;
        $this->load->library('MYLOG');
        $arr = json_encode($body);
        MYLOG::w($arr,'unionrefundreturn');

        $inter_id = $this->uri->segment(3);
        //获取配置
        $this->load->model ( 'pay/Pay_model' );
        $pay_paras = $this->Pay_model->get_pay_paras ( $inter_id, 'unionpay' );

        if (isset ( $body ['signature'] )) {
            require_once APPPATH.'/libraries/UnionPay/acp_service.php';
            com\unionpay\acp\sdk\UnionPayConfig::setVerifyCertDir ( '../certs/');
            $result =  com\unionpay\acp\sdk\AcpService::validate ( $body );

            if($result){
                $refund_orderid = str_replace('refund','',str_replace('yinlian','_',str_replace('union','-',$body['orderId'])));
                if($body ['respCode']=='00' || $body ['respCode']=='A6'){
                   //退款成功
                    $this->db->where ( array (
                            'out_trade_no' => $refund_orderid,
                            'transaction_id' => $body['origQryId'],
                            'refund_result' => 2
                    ) );
                    $this->db->update ( 'pay_refund', array (
                            'refund_result' => 1,
                            'up_time' => time(),
                            'res_content' => $arr
                    ) );
                    ob_clean();
                    echo 'success';
                    exit();
                }else{
                    $this->db->where ( array (
                            'out_trade_no' => $refund_orderid,
                            'transaction_id' => $body['origQryId'],
                            'refund_result' => 2
                    ) );
                    $this->db->update ( 'pay_refund', array (
                            'up_time' => time(),
                            'res_content' => $arr
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
}
