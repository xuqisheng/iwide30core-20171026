<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonusrule extends MY_Admin 
{	
	public function index()
	{
		$this->load->model('member/iconfig');
		$categories = $this->iconfig->getConfig('rule_categories',true,$this->session->get_admin_inter_id());
		if($categories) {
			$data['categories'] = $categories->value;
		} else {
			$data['categories'] = array();
		}

		$this->load->model('member/imember');
		$members = $this->imember->getAllMemberLevels($this->session->get_admin_inter_id());
		if($members && is_array($members)) {
			$data['members'] = $members;
		}
		
		$this->load->model('member/actions');
		$data['modules'] = $this->actions->getModules();
		
		$this->load->model('member/iconfig');
		$rules = $this->iconfig->getConfig('bonus_rule',true,$this->session->get_admin_inter_id());
		if($rules) {
			$data['rules'] = $rules->value;
		} else {
			$data['rules'] = array();
		}
		
		$this->load->model('member/iconfig');
		$btom = $this->iconfig->getConfig('bonustomoney_rule',true,$this->session->get_admin_inter_id());
		
		if($rules) {
			$data['btom'] = $btom->value;
		} else {
			$data['btom'] = array();
		}
		
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
	public function edit_post()
	{		
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/membercat');
			exit;
		}
		
		$postData = $this->input->post();

		$rules = array();
		foreach($postData as $name=>$data) {
			foreach($data as $key=>$value) {
				if(empty($postData['amount'][$key])) continue;
				if($name=='type') {
					$rules[$key][$value] = array($postData['amount'][$key]=>$postData['bonus'][$key]);
				} elseif($name=='category') {
				    $rules[$key][$name] = $value;
				} elseif($name=='member') {
					$rules[$key][$name] = $value;
				} elseif($name=='module') {
					$rules[$key][$name] = $value;
				}
			}
		}
		
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('bonus_rule',$rules,true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/bonusrule');
	}
	
	public function exchange_edit_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
	
			redirect('member/membercat');
			exit;
		}
	
		$postData = $this->input->post();
	
		$rules = array();
		foreach($postData as $name=>$data) {
			foreach($data as $key=>$value) {
				if(empty($postData['amount'][$key])) continue;
				if($name=='category') {
					$rules[$key][$name] = $value;
				} elseif($name=='member') {
					$rules[$key][$name] = $value;
				} elseif($name=='module') {
					$rules[$key][$name] = $value;
				} else {
					$rules[$key]['bonustomoney']=array($postData['bonus'][$key]=>$postData['amount'][$key]);
				}
			}
		}

		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('bonustomoney_rule',$rules,true,$this->session->get_admin_inter_id());
	
		$this->session->put_success_msg('成功保存信息!');
	
		redirect('member/bonusrule');
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