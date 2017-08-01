<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Refund extends MY_Front {

	protected $_request_url = '';
	//protected $_common_data = array();
    
	public function __construct() {
		parent::__construct ();
		// 开发模式下开启性能分析
		$this->output->enable_profiler ( false );
		$this->load->library('IwidePay/IwidePayApi',null,'IwidePayApi');
		//$this->load->library('IwidePay/IwidePayData');

		$this->_request_url = 'https://gzwkzftest.cmbc.com.cn/payment-gate-web/gateway/api/backTransReq';


	}

	public function order_query(){
         $this->load->model('iwidepay/Iwidepay_model');
         $res = $this->Iwidepay_model->order_query123(123);
         var_dump($res);die;
    }
	
	//退款接口
	public function refund(){
		
		//装装数据
		$arr = array(
			'commodityName' => 'fefjeffe',
            'orderDate' => '20170724',
            'orderNo' => 'tuikuan123'.time(),
            'requestNo' => md5(time()),
            'transAmt' => 1,//单位：分
            'transId' => '02',
            'origOrderDate' => '20170724',
            'origOrderNo' =>'1500889179',
            'returnUrl' => 'http://cmbcpay.jinfangka.com/index.php',
           // 'notifyUrl' => 'http://www.baidu.com',
            'refundReson' => '不爽',
        );
        
		
         $this->load->model('iwidepay/Iwidepay_model');
         $res = $this->Iwidepay_model->refunddd($arr);
         var_dump($res);die;
     }

	//退款回调 先写这里吧
	public function refuncCallback(){
		//通过URL传值？
		$data = $_GET;
		//记录log
		//$this->db->insert('iwidepay_log',$data);
		//验签
		$res = $this->IwidePayApi->payreturnCallBack($data);
		if(!$res){//验签失败
			return false;
		}
		//业务逻辑代码
		
		//最后返回success
		echo 'succes';
		die;
	}

	
}