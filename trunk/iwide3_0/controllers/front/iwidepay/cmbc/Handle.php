<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Handle extends MY_Controller {

    
	public function __construct() {
		parent::__construct ();
		$this->debug = $this->input->get ( 'debug' );
		error_reporting ( 0 );
		if (! empty ( $this->debug )) {
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 1 );
        }
		$this->load->library('MYLOG');
	}

	public function ccc(){
		echo 234;die;
	}

    private function check_arrow(){//访问限制
    	//return true;
        //var_dump($_SERVER['REMOTE_ADDR']);die;
        if(ENVIRONMENT === 'production'){
	        $arrow_ip = array(//内网IP
	        	"10.25.169.253",
				"10.27.229.242",
				"10.26.22.169",
				"10.25.171.206",
				"10.46.74.165",
				"10.25.1.106",
				"10.27.237.22",
				"10.47.98.7",
				"10.27.235.68",
				"10.28.154.161",
				"10.28.153.47",
				"10.28.47.153",
				"10.47.114.221",
				"10.27.234.194",
				"10.171.174.90",
				"10.27.232.209",
				"10.46.75.203",
				"10.168.162.35",
				"10.51.28.219",
	        );//只允许服务器自动访问，不能手动
	        if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
	        	MYLOG::w('非法访问！' . $_SERVER['REMOTE_ADDR'], 'iwidepay/refund');
	            exit('非法访问！');
	        }
    	}else{
    		return true;
    	}
    }
	
	//转账接口
	public function pay(){
		 $this->check_arrow();
		 $data = $this->input->post();
		 MYLOG::w('http转账数据' . json_encode($data), 'iwidepay/pay_handle');
		 $return = array('errcode'=>1,'msg'=>'fail','data'=>array());
		 if(empty($data)){
		 	MYLOG::w('转账数据为空', 'iwidepay/pay_handle');
		 	$return['msg'] = '转账数据为空';
		 	echo json_encode($return);
		 	die;
		 }
		 $this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
		 $chart = IwidePayConfig::TRANSFER_PAY_SECRET;//改配置文件
		 $sign = md5($chart.$data['orderNo'].$data['orderDate'].$data['transAmt'].$data['transId'].$data['customerName'].$data['acctNo'].$chart);
		 if($sign != $data['sign']){
		 	$return['msg'] = '转账签名错误';
		 	MYLOG::w('转账签名错误' . json_encode($data), 'iwidepay/pay_handle');
		 	echo json_encode($return);
		 	die;
		 }
		 //$data = json_decode($data,true);
         $res = $this->IwidePayApi->balancePayRequest($data);
         MYLOG::w('http转账返回|' . json_encode($res), 'iwidepay/pay_handle');
         //返回也要加密
         $res_sign = md5($chart.$res.$chart);
         $return['errcode'] = 0;
         $return['msg'] = 'ok';
         $return['data'] = array('return_data'=>$res,'sign'=>$res_sign);
         echo json_encode($return);
         die;
    }

}