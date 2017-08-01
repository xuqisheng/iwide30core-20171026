<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memberlevel extends MY_Admin 
{	
	public function index()
	{
		$this->load->model('member/actions');
		$this->load->model('member/iconfig');
		$categories = $this->iconfig->getConfig('rule_categories',true,$this->session->get_admin_inter_id());
		
		if($categories) {
			$data['categories'] = $categories->value;
		} else {
			$data['categories'] = array();
		}
		
		$members = $this->iconfig->getConfig('level',true,$this->session->get_admin_inter_id());
		if($members) {
		    $data['members'] = $members->value;
		} else {
			$data['members'] = array();
		}

		$level_bonus = $this->iconfig->getConfig('level_bonus',true,$this->session->get_admin_inter_id());
		if($level_bonus) {
			$data['level_bonus'] = $level_bonus->value;
		} else {
			$data['level_bonus'] = array();
		}
		$upgrade_bonus = $this->iconfig->getConfig('upgrade_bonus',true,$this->session->get_admin_inter_id());
		if($upgrade_bonus) {
			$data['upgrade_bonus'] = $upgrade_bonus->value;
		} else {
			$data['upgrade_bonus'] = false;
		}

		$level_balance = $this->iconfig->getConfig('level_balance',true,$this->session->get_admin_inter_id());
		if($level_balance) {
			$data['level_balance'] = $level_balance->value;
		} else {
			$data['level_balance'] = array();
		}
		$upgrade_balance = $this->iconfig->getConfig('upgrade_balance',true,$this->session->get_admin_inter_id());
		if($upgrade_balance) {
			$data['upgrade_balance'] = $upgrade_balance->value;
		} else {
			$data['upgrade_balance'] = false;
		}

		$privilege = $this->iconfig->getConfig('level_privilege',true,$this->session->get_admin_inter_id());
		if($privilege) {
		    $data['privilege'] = $privilege->value;
		} else {
			$data['privilege'] = array();
		}
		
		/*
		$this->load->model('member/icard');
		$cards = $this->icard->getCardList(true, true);
		if($cards) {
			$data['cards'] = $cards;
		} else {
			$data['cards'] = array();
		}
		$level_card = $this->iconfig->getConfig('level_card',true,$this->session->get_admin_inter_id());
		if($level_card) {
			$data['level_card'] = $level_card->value;
		} else {
			$data['level_card'] = array();
		}
		$upgrade_card = $this->iconfig->getConfig('upgrade_card',true,$this->session->get_admin_inter_id());
		if($upgrade_card) {
			$data['upgrade_card'] = $upgrade_card->value;
		} else {
			$data['upgrade_card'] = false;
		}*/

		$data['modules'] = $this->actions->getModules();

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

		$members = array();
		foreach($postData as $data) {
			foreach($data as $key=>$value) {
				if(!empty($value)) $members[$key] = $value;
			}
		}

		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('level',$members,true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/memberlevel');
	}
	
	public function upgrade_bonus_post()
	{
		$postData = $this->input->post();
		
		$data = array();
		foreach($postData['mem_id'] as $key=>$mem_id) {
			if($postData['bonus'][$key]=='') continue;
			
			$data[$mem_id]=$postData['bonus'][$key];
		}
		
		asort($data);
		
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('level_bonus',$data,true,$this->session->get_admin_inter_id());
		$this->iconfig->addConfig('upgrade_bonus',$postData['upgrade_bonus'],true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/memberlevel');
	}
	
	public function upgrade_charge_post()
	{
		$postData = $this->input->post();

		$data = $addition = array();
		foreach($postData['mem_id'] as $key=>$mem_id) {
			if($postData['balance'][$key]=='') continue;
				
			$data[$mem_id]=$postData['balance'][$key];
		}
		
		asort($data);

		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('level_balance',$data,true,$this->session->get_admin_inter_id());
		$this->iconfig->addConfig('upgrade_balance',$postData['upgrade_balance'],true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/memberlevel');
	}
	
	public function upgrade_card_post()
	{
		$postData = $this->input->post();

		$data = array();
		foreach($postData['mem_id'] as $key=>$mem_id) {
			if($postData['card'][$key]=='-1') continue;
		
			$data[$mem_id]=$postData['card'][$key];
		}
		
		ksort($data);
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('level_card',$data,true,$this->session->get_admin_inter_id());
		$this->iconfig->addConfig('upgrade_card',$postData['upgrade_card'],true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/memberlevel');
	}
	
	public function privilege_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/memberlevel');
			exit;
		}
		
		$postData = $this->input->post();
	
		$privilege = array();
		foreach($postData['module'] as $key=>$module) {
			if(empty($postData['val'][$key])) continue;
			$privilege[$module][$postData['mem_id'][$key]][$postData['mem_cat'][$key]][$postData['mode'][$key]] = $postData['val'][$key];
		}
		
// 		foreach($postData['mem_id'] as $key=>$val) {
// 			if(empty($postData['discount'][$key])) continue;
// 			$privilege[$val][$postData['mem_cat'][$key]] = $postData['discount'][$key];
// 		}
	
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('level_privilege',$privilege,true,$this->session->get_admin_inter_id());
	
		redirect('member/memberlevel');
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