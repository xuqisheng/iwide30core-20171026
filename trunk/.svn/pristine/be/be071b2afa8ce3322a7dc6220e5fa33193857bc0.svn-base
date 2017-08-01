<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	发送短信
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Sendsms extends MY_Front_Member
{
	public function index(){
		$post_sendsms_url = PMS_PATH_URL."member/sendsms";
		$post_sendsms_data = array(
			'inter_id'=>$this->inter_id,
			'phone'=>$this->input->post('phone'),
			'openid'=>$this->openid,
			'smstype'=>$this->input->post('smstype'),
			'data'=>$this->input->post(),
        );
		//请求发送短信
		$sms_result = $this->doCurlPostRequest($post_sendsms_url,$post_sendsms_data);
		echo json_encode($sms_result);
	}



}
?>