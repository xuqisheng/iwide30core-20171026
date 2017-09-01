<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vcardmanage extends MY_Admin 
{
	public function index()
	{
		$this->load->model('member/iconfig');
		$members = $this->iconfig->getConfig('level',true,$this->session->get_admin_inter_id());
		if($members) {
		    $data['members'] = $members->value;
		} else {
			$data['members'] = array();
		}
		
		$this->load->model('member/icard');
		$cards = $this->icard->getCardList(null,true,array('inter_id'=>$this->session->get_admin_inter_id()));
		if($cards) {
			$data['cards'] = $cards;
		} else {
			$data['cards'] = array();
		}

		$this->load->model('member/iconfig');
		$members = $this->iconfig->getConfig('vcardset',true,$this->session->get_admin_inter_id());
		if($members) {
			$data['vcards'] = $members->value;
		} else {
			$data['vcards'] = array();
		}
		
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
	public function edit_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/basicinfo');
			exit;
		}
		
		$postData = $this->input->post();
		
		$data = array();
		foreach($postData['card'] as $key=>$val) {
			if($val=='-1') continue;
			
			$data[$val] = array(
				'card'=>$val,
				'mem_id'=>$postData['mem_id'][$key],
				'type'=>$postData['type'][$key],
				'value'=>$postData['value'][$key],
				'is_balance'=>$postData['is_balance'][$key],
				'is_balance_card'=>$postData['is_balance_card'][$key],
				'maxnum'=>$postData['maxnum'][$key]
			);
		}
		
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('vcardset',$data,true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/vcardmanage');
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