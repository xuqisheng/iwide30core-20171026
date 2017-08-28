<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disp extends MY_Admin {

	protected $label_module= '分销保护信息';
	protected $label_controller= '分销保护';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'distribute/Distribution_protection_model';
	}

	public function grid()
	{
		
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		//print_r($filter);die;
		
		/** 添加 过滤条件js开始  **/
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		if(is_ajax_request())
			//处理ajax请求，参数规格不一样
			$get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
		else
			$get_filter= $this->input->get('filter');
	
			if(is_array($get_filter)) $filter= $get_filter+ $filter;
	
		/** 添加 过滤条件js结束  **/

		$this->_grid($filter, []);
	}
}
