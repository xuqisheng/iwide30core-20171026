<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IwideApi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function index()
	{
		
		//后期作过漏处理
		//$post_data = $_POST;
		$data = $this->input->post("data");
		
		$action = $this->input->post("action");
		
		if($action == 'sendModelMsg'){
			
			$this->sendModelMsg();
			
		}else{
			
			$this->sendClient(1001,'action error','');
			
		}
		
		
	}
	
	private function sendModelMsg(){
		
		$data = $this->input->post("data");
		
		$action = $this->input->post("action");
		
		$appid = $this->input->post("appId");
		
		$ts = $this->input->post("ts");
		
		$client_sign = $this->input->post("sign");
		
		//$client_sign =
		//暂不验证
		$server_sign = md5( $data.$ts.$appid."" );
		
		//data+ts+appId+secret key
		$data = json_decode($data);
		if($data['content'] == '' || $data['modelID'] == '' || $data['openId'] == ''){
				
				$this->sendClient(0,'','');
				
		}
		
		
	}
	
	
	private function sendClient($error_code,$error_msg,$return_data){
		
		$data['error_code'] = $error_code;
		$data['error_msg'] = $error_msg;
		$data['return_data'] = $return_data;
		ob_clean();
		echo json_encode($data);
		
	}
    
	
	
	
	
	
}
