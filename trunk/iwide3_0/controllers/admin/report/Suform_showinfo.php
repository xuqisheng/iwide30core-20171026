<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suform_showinfo extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '酒店订单量报表';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/Suform_showinfo_model';
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
	
}
