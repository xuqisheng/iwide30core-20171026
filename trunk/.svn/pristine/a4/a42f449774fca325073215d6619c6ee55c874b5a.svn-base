<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carts extends MY_Admin_Mall {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '购物车';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_cart';
	}

	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );
	     
/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	     
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	     
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */
	    	  
	    $this->_grid($filter);
	}

	public function delete()
	{
	    try {
	        $model_name= $this->main_model_name();
	        $model= $this->_load_model($model_name);
	
	        $ids= explode(',', $this->input->get('ids'));
	        $result= $model->delete_in($ids);
	
	        if( $result ){
	            $this->session->put_success_msg("删除成功");
	
	        } else {
	            $this->session->put_error_msg('删除失败');
	        }
	
	    } catch (Exception $e) {
	        $message= '删除失败过程中出现问题！';
	        //$message= $e->getMessage();
	        $this->session->put_error_msg('删除失败');
	    }
	    $url= EA_const_url::inst()->get_url('*/*/grid');
	    $this->_redirect($url);
	}
}
