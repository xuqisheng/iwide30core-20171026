<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Refund extends MY_Controller {

    
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
	
	//退款接口
	public function index(){
		 $data = $this->input->post();
		 MYLOG::w('退款数据' . json_encode($data), 'iwidepay/refund');
		 if(empty($data)){
		 	MYLOG::w('退款数据为空', 'iwidepay/refund');
		 	return false;
		 }
		 $chart = 'jfkTransferqwe1489';//不能变
		 $sign = md5($chart.$data['orderNo'].$data['orderDate'].$data['transAmt'].$data['transId'].$data['origOrderNo'].$data['origOrderDate'].$chart);
		 if($sign != $data['sign']){
		 	echo 'sign error';
		 	MYLOG::w('退款签名错误' . json_encode($data), 'iwidepay/refund');
		 	die;
		 }
		 //$data = json_decode($data,true);
		 $this->load->library('IwidePay/IwidePayApi',null,'IwidePayApi');
         $res = $this->IwidePayApi->refundRequest($data);
         echo $res;
         die;
    }

}