<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonusexplain extends MY_Admin 
{	
	public function index()
	{
		$data = $this->input->post();
		$this->load->model('member/iconfig');
		if($data) {
			if(!$this->_checkInterId()) {
				$this->session->put_error_msg('公众号ID不对!');
			
				redirect('member/bonusexplain');
				exit;
			}
		
			foreach($data as $k=>$v) {
				$this->iconfig->addConfig($k,$v,null,$this->session->get_admin_inter_id());
			}
		}

		if($this->iconfig->getConfig('bonus_rule_des_title_1',null,$this->session->get_admin_inter_id())) {
			$data['bonus_rule_des_title_1'] = $this->iconfig->getConfig('bonus_rule_des_title_1',null,$this->session->get_admin_inter_id())->value;
		}
		if($this->iconfig->getConfig('bonus_rule_des_content_1',null,$this->session->get_admin_inter_id())) {
			$data['bonus_rule_des_content_1'] = $this->iconfig->getConfig('bonus_rule_des_content_1',null,$this->session->get_admin_inter_id())->value;
		}
		if($this->iconfig->getConfig('bonus_rule_des_title_2',null,$this->session->get_admin_inter_id())) {
			$data['bonus_rule_des_title_2'] = $this->iconfig->getConfig('bonus_rule_des_title_2',null,$this->session->get_admin_inter_id())->value;
		}
		if($this->iconfig->getConfig('bonus_rule_des_content_2',null,$this->session->get_admin_inter_id())) {
			$data['bonus_rule_des_content_2'] = $this->iconfig->getConfig('bonus_rule_des_content_2',null,$this->session->get_admin_inter_id())->value;
		}
		if($this->iconfig->getConfig('bonus_rule_des_title_3',null,$this->session->get_admin_inter_id())) {
			$data['bonus_rule_des_title_3'] = $this->iconfig->getConfig('bonus_rule_des_title_3',null,$this->session->get_admin_inter_id())->value;
		}
		if($this->iconfig->getConfig('bonus_rule_des_content_3',null,$this->session->get_admin_inter_id())){
			$data['bonus_rule_des_content_3'] = $this->iconfig->getConfig('bonus_rule_des_content_3',null,$this->session->get_admin_inter_id())->value;
		}

		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
// 	public function edit_post()
// 	{		
// 		$postData = $this->input->post();
// 		$members = array();
// 		foreach($postData as $name=>$data) {
// 			foreach($data as $key=>$value) {
// 				$members[$key][$name] = $value;
// 			}
// 		}
        
// 		foreach($members as $k=>$member) {
// 			if(empty($member['name']) || empty($member['bonus'])) {
// 				unset($members[$k]);
// 			}
// 		}
		
// 		$this->load->model('member/iconfig');
// 		$this->iconfig->addConfig('level',$members,true,$this->session->get_admin_inter_id());
		
// 		$this->session->put_success_msg('成功保存信息!');
		
// 		redirect('member/memberlevel');
// 	}
	
	protected function _checkInterId()
	{
		if(preg_match("/a[0-9]{9}/i",$this->session->get_admin_inter_id())) {
			return true;
		} else {
			return false;
		}
	}
}