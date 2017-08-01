<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
*  
*/
class Getopenid extends MY_Front
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->helpers('common');
	}

	function go()
	{
		$openid = $this->session->userdata($this->inter_id.'openid');
		$money = $this->input->get('m');
		redirect('http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/pay/hotel_pay?openid='.$openid.'&money='.$money);//测试
		// redirect('http://cmbcpay.jinfangka.com/index.php/iwidepay/cmbc/pay/hotel_pay?openid='.$openid.'&money='.$money);//生产
	}

	function to_order()
	{
		$hotels = array('4401','4402','3499');
		$url = "http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/iwidepayreturn/hotel_return";
		$data_r = array(
			'productId' => '0105',
			'transId' => '10',
			'merNo' => '850440053991272',
			'orderNo' => time(),
			'orderDate' => date('Ymd'),
			'respCode' => '0000',
			'respDesc' => '交易成功',
			'payId' => time().rand(1,100),
			'payTime' => date('Y-m-d H:i:s'),
			'returnUrl' => 'http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/pay/success',
			'notifyUrl' => 'http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/iwidepayreturn/hotel_return',
			'transAmt' => rand(10000,50000),
			'commodityName' => '测试商品'.rand(1,1000),
			'openid' => $this->session->userdata($this->inter_id.'openid'),
			'subMerNo' => '21434543535',
			'subMerName' => '酒店名称',
			'signature' => md5('850440053991272'.'21434543535'),
			'inter_id' => 'a469428180',
			'hotel_id' => $hotels[rand(0,2)],
			);
		$data = http_build_query($data_r);
		$res = doCurlPostRequest($url,$data,array(),30);
		echo $res;
		print_r($data_r);
	}
}