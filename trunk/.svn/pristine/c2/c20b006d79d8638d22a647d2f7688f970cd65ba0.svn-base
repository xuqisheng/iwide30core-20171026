<?php
if (! defined ( 'BASEPATH' ))
	exit ( json_encode(array('status'=>'1001')) );

class Test extends MY_Controller {
	public function __construct() {
		parent::__construct ();
	}
	
	public function login() {
// 		$url=site_url('wxapp/user/user/login');
		$url="https://bgy.app.iwide.cn/index.php/user/user/login";
		$inter_id=$this->input->get('id');
		$code=$this->input->get('code');
		$debug=$this->input->get('debug');
		$data=array('inter_id'=>$inter_id,'code'=>$code);
		$this->load->helper('common');
		if ($debug){
			echo $inter_id;
			echo $code;
		}
		var_dump(doCurlPostRequest($url, json_encode($data)));
	}
	
	public function test_buildToken(){
		
		$arr = array(
		
					"web_data"=>'tk_123',
					"status"=>1000,
					"msg"=>"",
					"msg_type"=>"",
		
				
		);
		
		echo json_encode($arr);
		
	}
	
	public function test_loginToken(){
		
		
		$arr = array(
		
				"web_data"=>array(
					'member_lv_name'=>'金卡会员'	
						
					),
				"status"=>1000,
				"msg"=>"登陆失败，你重试",
				"msg_type"=>"alert",
		
		
		);
		
		echo json_encode($arr);
		
	}
}