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
}