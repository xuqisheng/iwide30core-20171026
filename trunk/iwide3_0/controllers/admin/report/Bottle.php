<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bottle extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '酒店社交';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/Bottle_model';
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		//print_r($filter);die;
		 
		$this->_grid($filter);
	}
	
	public function edit_post()
	{
		
		$infoid = $this->input->post('id');
		$data['title'] = $this->input->post('title');
		$data['inter_id'] = $this->input->post('inter_id');
		
		$infoid = intval($infoid);

		if ($infoid) {
			$this->db->update('chat_config',$data,array('id'=>$infoid));
	
		} else {
			$data['addtime'] = time();
			$this->db->insert('chat_config',$data);
		}
	
		$this->session->put_success_msg('操作成功！');
		$this->_redirect(EA_const_url::inst()->get_url('*/*/index', array('ids'=> $infoid ) ));
		
	
	}
	
	public function delete()
	{
		$ids = $this->input->get('ids');
		$ids = intval($ids);
		if ($ids>0) {
			$this->db->delete('chat_config',array('id'=>$ids));
			$this->session->put_success_msg("删除成功");
		}
		else {
			$this->session->put_error_msg('删除失败');
		}
		$url= EA_const_url::inst()->get_url('*/*/grid');
		$this->_redirect($url);
	}
	
}
