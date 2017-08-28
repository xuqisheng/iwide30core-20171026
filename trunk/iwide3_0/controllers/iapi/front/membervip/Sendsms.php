<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	发送短信
*	@author  liwensong
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email septet-l@outlook.com
 * @property CI_Input $input
*/
class Sendsms extends MY_Front_Member_Iapi
{
	public function index(){
		//统一处理数据流
		$post = json_decode($this->input->raw_input_stream,true);
		if(!empty($post)&&is_array($post)){
		    foreach ($post as $key => $value) {
		        if(!isset($_POST[$key])){
		            $_POST[$key] = $value;
		        }
		    }
		}
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
        $sms_result = $this->parse_curl_msg($sms_result);
        if($sms_result['code']==1000){
            $this->out_put_msg(1,$sms_result['msg'],$sms_result['data'],'membervip/sendsms/index');
        }else{
            $this->out_put_msg(3,$sms_result['msg'],$sms_result['data'],'membervip/sendsms/index');
        }
	}



}
?>