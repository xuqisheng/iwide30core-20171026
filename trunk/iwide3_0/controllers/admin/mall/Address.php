<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends MY_Admin_Mall {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '邮寄地址';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_address';
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
	    
	    $this->_grid($filter);
	}
	
	public function export()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
		
		$this->session->put_notice_msg('功能正在完善中！');
		$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	}
}
