<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type:text/html;charset=utf-8");
class Temp_msg_auth extends MY_Front {
	protected $datas = array();
	public function __construct()
	{
		parent::__construct();
		$this->inter_id=$this->session->userdata('inter_id');
		$this->openid=$this->session->userdata($this->inter_id.'openid');
		
		$this->datas['inter_id'] = $this->inter_id;
		if($this->input->get('asd')){
			$this->output->enable_profiler(true);
		}
	}
	function reg(){
		$this->load->model('hotel/temp_msg_auth_model');
		$auth_info = $this->temp_msg_auth_model->get_config($this->openid,$this->inter_id);
		if($auth_info){
			redirect(site_url('hotel/temp_msg_auth/processing').'?id='.$this->inter_id);
		}else{
			$this->load->view('distribute/default/header');
			$this->load->view('template_msg_auth/new_auth',$this->datas);
		}
	}
	function do_reg(){
		$this->load->model('hotel/temp_msg_auth_model');
		if($this->temp_msg_auth_model->save()){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	function processing(){
		$this->load->model ( 'hotel/temp_msg_auth_model' );
		$auth_info = $this->temp_msg_auth_model->get_config ( $this->openid, $this->inter_id );
// 		if ($auth_info) {
// 			redirect ( site_url ('hotel/temp_msg_auth/processing') . '?id=' . $this->inter_id );
// 		} else {
			$datas ['status'] = '';
			if ($auth_info ['status'] == 1) {
				$this->datas ['status'] = 'processing';
			} elseif ($auth_info ['status'] == 2) {
				$this->datas ['status'] = 'complete';
			} elseif ($auth_info ['status'] == 3) {
				$this->datas ['status'] = 'faild';
			}
			$this->load->view('distribute/default/header');
			$this->load->view('template_msg_auth/processing',$this->datas);
// 		}
	}
	
}