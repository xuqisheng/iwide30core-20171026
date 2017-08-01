<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membercat extends MY_Admin 
{
	protected $label_controller= '菜单管理';		//在文件定义
	
	public function index()
	{
		$this->load->model('member/iconfig');
		$categories = $this->iconfig->getConfig('rule_categories',true,$this->session->get_admin_inter_id());
		
		if($categories) {
			$data['categories'] = $categories->value;
		} else {
			$data['categories'] = array();
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
		
		$data = $this->input->post();
		
		$categories = array();
		foreach($data['code'] as $k=>$v) {
			if(!empty($v) && !empty($data['name'][$k])) $categories[$v] = $data['name'][$k];
		}
		
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('rule_categories',$categories,true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/membercat');
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