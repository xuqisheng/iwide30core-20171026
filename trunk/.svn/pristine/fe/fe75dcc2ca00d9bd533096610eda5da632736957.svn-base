<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Tmsg extends MY_Admin {
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
	}

	public function sendMsg(){
		// 用户预约退房成功提醒(发给用户的)
		$order['hotel_checkout_notice'] = array(
			'inter_id'=>'a469428180',
			'openid'=>'ohQSEuA11rCtJh_YM74Z9_Jp2wMo',
			'type'=>'预约退房',
			'check_out_time'=>date('Y-m-d H:i'),//这个传实际记录用户申请的退房的时间
			);
		// 用户退房申请提醒(发给酒店的)
		$order['hotel_checkout_apply_notice'] = array(
			'inter_id'=>'a469428180',
			'openid'=>'ohQSEuA11rCtJh_YM74Z9_Jp2wMo',
			'hotel'=>'金房卡大酒店',//这个传实际用户住的酒店
			'check_out_time'=>date('Y-m-d H:i'),//这个传实际记录用户申请的退房的时间
			'room_num'=>'2012a',//这个传实际记录的房号
			);
		// 发票开具完成提醒(发给用户的)
		$order['hotel_invoice_notice'] = array(
			'inter_id'=>'a469428180',
			'openid'=>'ohQSEuDXwVxVj4g4081nqMR8Bw38',
			'hotel'=>'金房卡大酒店',//这个传实际用户住的酒店
			'check_out_time'=>'2016-12-28 19:53',//date('Y-m-d H:i'),//这个传实际记录的时间
			'amount' => '328',//这个传开票的金额
			'type'=>'普通发票',//这个传发票类型
			'project'=>'住宿费',//这个传发票项目
			'title'=>null,//发票抬头
			);
		$this->load->model('plugins/template_msg_model');
		foreach ($order as $k => $v) {
			$result = $this->template_msg_model->send_checkout_or_invoice_msg($v,$k,1);
			var_dump($result);
		}	
	}
}