<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chargelist extends MY_Admin 
{	
	protected $label_action= '会员充值记录';
	
	protected function main_model_name()
	{
		return 'member/admin/grid/gridbonusrecord';
	}
	
	public function grid()
	{
		$this->load->model('member/member');
		$inter_id= $this->session->get_admin_inter_id();
	
		if($inter_id == FULL_ACCESS) {
			$filter= array();
		} else if($inter_id) {
			$filter= array('inter_id'=>$inter_id );
		} else {
			$filter= array('inter_id'=>'deny' );
		}

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		/* 兼容grid变为ajax加载加这一段 */
		if(is_ajax_request())
		    //处理ajax请求，参数规格不一样
		    $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
		
		else
		    $get_filter= $this->input->get('filter');
		
		if( !$get_filter) $get_filter= $this->input->get('filter');
		
		if(is_array($get_filter)) $filter= $get_filter+ $filter;
		/* 兼容grid变为ajax加载加这一段 */
		
		$this->_grid($filter );
	}
}