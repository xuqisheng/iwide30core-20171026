<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modifyinfo extends MY_Admin 
{	
	public function __construct()
	{
		parent::__construct();
	
		$this->initialize();
	}
	
	public function index()
	{
		$this->load->model('member/iconfig');
		$fields = $this->iconfig->getConfig('modify_fields',true,$this->session->get_admin_inter_id());

		if($fields) {
		    $data['fields'] = $this->iconfig->getConfig('modify_fields',true,$this->session->get_admin_inter_id())->value;
		} else {
			$data['fields'] = array();
		}
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
	public function edit_post()
	{		
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
				
			redirect('member/modifyinfo');
			exit;
		}
		
		$data = $this->input->post();
		
		$this->load->model('member/iconfig');
		$fields = $this->iconfig->getConfig('modify_fields',true,$this->session->get_admin_inter_id());
		
		if($fields) {
		    $fields = $this->iconfig->getConfig('modify_fields',true,$this->session->get_admin_inter_id())->value;
		} else {
			$fields = array();
		}

		foreach($fields as $field=>$value) {
			if(isset($data[$field]) && $data[$field]==1) {
				if(substr($field, 0, 6) == 'custom') {
					$fields[$field]['name']=$data[$field.'_name'];
				}
				$fields[$field]['must']=1;
			} else {
				$fields[$field]['must']=0;
			}
		}

		$res = $this->iconfig->addConfig('modify_fields', $fields, true, $this->session->get_admin_inter_id());
		//var_dump($data,$res);exit;
		$this->session->put_success_msg('成功保存信息!');
		
		redirect("member/modifyinfo");
	}
	
	protected function initialize()
	{
		$this->load->model('member/iconfig');
		$data = $this->iconfig->getConfig('modify_fields',true,$this->session->get_admin_inter_id());
	
		if(empty($data)) {
			$info = array();
				
			$info['name'] = array(
				'name'=>'姓名',
				'must'=>0
			);
			$info['telephone'] = array(
				'name'=>'手机号码',
				'must'=>1
			);
			$info['email'] = array(
				'name'=>'Email',
				'must'=>1
			);
			
			$info['oldpassword'] = array(
				'name'=>'旧密码',
				'must'=>1
			);
			
			$info['newpassword'] = array(
				'name'=>'新密码',
				'must'=>1
			);
			
			
			$info['identity_card'] = array(
				'name'=>'身份证号码',
				'must'=>0
			);
			
			
			$info['custom1'] = array(
				'name'=>'自定义字段1',
				'must'=>0
			);
			$info['custom2'] = array(
				'name'=>'自定义字段2',
				'must'=>0
			);
			$info['custom3'] = array(
				'name'=>'自定义字段3',
				'must'=>0
			);
			$info['custom4'] = array(
				'name'=>'自定义字段4',
				'must'=>0
			);
			$info['custom5'] = array(
				'name'=>'自定义字段5',
				'must'=>0
			);
				
			$this->iconfig->addConfig('modify_fields',$info,true,$this->session->get_admin_inter_id());
		}
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