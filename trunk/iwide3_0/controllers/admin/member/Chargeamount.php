<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chargeamount extends MY_Admin 
{	
	public function index()
	{
		$this->load->model('member/iconfig');

		$charge_amount = $this->iconfig->getConfig('charge_amount',true,$this->session->get_admin_inter_id());
		if($charge_amount) {
			$data['charge_amount'] = $charge_amount->value;
		} else {
			$data['charge_amount'] = array();
		}

		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
	public function edit_post()
	{		
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/memberlevel');
			exit;
		}
		
		$postData = $this->input->post();

        $data = array();
		foreach($postData['amount'] as $key=>$val) {
			if(empty($val)) continue;
			$data[$val] = $postData['addition_amount'][$key];
		}
		
		ksort($data);

		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('charge_amount',$data,true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/chargeamount');
	}
	
	protected function _checkInterId()
	{
		if(preg_match("/a[0-9]{9}/i",$this->session->get_admin_inter_id())) {
			return true;
		} else {
			return false;
		}
	}
}