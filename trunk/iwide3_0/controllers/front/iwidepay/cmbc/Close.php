<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Close extends MY_Controller {
	public function __construct() {
		parent::__construct ();
	}
	
	public function hotel_order(){
		$order_no = $this->input->get('order_no');
		$module = $this->input->get('m');
		$this->load->model('iwidepay/Iwidepay_model');
		$res = $this->Iwidepay_model->close_order($order_no,$module);
		if($res){
			exit ("订单{$order_no}关闭成功");
		}
		exit("订单{$order_no}关闭失败");
	}

	public function closetest(){
		$data = array(
				'requestNo' => time().rand(10000,99999),
				'transId' => '21',
				'orderDate' => date('Ymd'),
				'orderNo' => time().rand(1000,9999),
				'origOrderNo' => '1500367636',//原支付订单号
				'origOrderDate' => '20170718',//原订单日期
				);
		$this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
		$res = $this->IwidePayApi->closeOrderRequest($data);
		var_dump($res);exit;
	}

	public function paytest(){
		$data = array(
				'requestNo' => time().rand(10000,99999),
				'transId' => '04',
				'orderNo' => '1500363153',//原支付订单号
				'orderDate' => '20170718',//原订单日期
				);
		$this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
		$res = $this->IwidePayApi->queryPayStatusRequest($data);
		var_dump($res);exit;
	}

}