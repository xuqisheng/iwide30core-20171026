<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type:text/html;charset=utf-8");
class Wa extends MY_Front {
	public function __construct()
	{
		parent::__construct();
		$this->inter_id=$this->session->userdata('inter_id');
		$this->openid=$this->session->userdata($this->inter_id.'openid');
	}
	public function index(){
		$this->load->model('distribute/welfare_auth_model');
		if($this->welfare_auth_model->is_openid_valid($this->inter_id,$this->openid)){

			$id = $this->uri->segment(4);
			$params = array();
			if(!$this->input->post() && !$id){
				$params['type'] = 'cancel';
			}else if($this->input->post()){
				$id = $this->input->post('token');
				if(!$id){
					$params['type'] = 'failed';
					$params['errmsg'] = '授权失败';
				}
				$this->load->model('distribute/welfare_auth_model');
				if($this->welfare_auth_model->_update_auth_status($this->inter_id,$this->openid,$id,date('Y-m-d H:i:s',time() + 1800)) > 0){
					$params['type'] = 'success';
				}else{
					$params['type'] = 'failed';
					$params['errmsg'] = '授权失败';
				}
			}else{
				$params['token'] = $id;
				$params['type'] = 'index';
			}

		}else{
			$params['type'] = 'cancel';
			$params['errmsg'] = '非法管理员';
		}
		$this->load->view('s/auth',$params);
	}
}