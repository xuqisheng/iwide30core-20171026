<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suform extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '酒店订单量报表';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/Suform_model';
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
	
	
	
	
	public function edit2()
	{
		$view_params = array();
		//echo $this->_load_view_file('edit');
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}
	
	public function delete()
	{
		$ids = $this->input->get('ids');
		$ids = intval($ids);
		if ($ids>0) {
			$this->db->delete('custom',array('id'=>$ids));
			$this->db->delete('custom_input',array('cid'=>$ids));
			//$this->db->delete('custom_info',array('cid'=>$ids));
			$this->session->put_success_msg("删除成功");
		}
		else {
			$this->session->put_error_msg('删除失败');
		}
		$url= EA_const_url::inst()->get_url('*/*/grid');
		$this->_redirect($url);
	}
}
