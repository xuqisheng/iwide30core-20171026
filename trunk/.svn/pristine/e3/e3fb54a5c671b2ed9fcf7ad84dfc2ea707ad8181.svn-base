<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center_share_config extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '分享配置';		//在文件定义
	protected $label_action= '';				//在方法中定义

	public function __construct()
	{
		parent::__construct();
		
		//防止其他账号使用该功能。
	    $this->_center_writelist();
	}
	
	protected function main_model_name()
	{
		return 'soma/Center_share_config_model';
	}

	public function grid()
	{

	    $this->label_action= '分享配置';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    // $ent_ids= $this->session->get_admin_hotels();
	    // $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    // if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

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
	    	  
	    $this->_grid($filter);
	}

	public function edit()
	{
	    
	    $this->label_action= '修改分享配置';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	
	    $id= intval($this->input->get('ids'));
	    if($id){
	        $model= $model->load($id);
	    }
	
	    if(!$model) $model= $this->_load_model();
	    $fields_config= $model->get_field_config('form');
	
	    //越权查看数据跳转
	    if( !$this->_can_edit($model) ){
	        $this->session->put_error_msg('找不到该数据');
	        $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	    }
	
	    $inter_id= $this->session->get_admin_inter_id();
	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
	    );
	
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	public function edit_post()
	{

	    $this->label_action= '修改分享配置';
	    $this->_init_breadcrumb($this->label_action);
    	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    $inter_id= $this->session->get_admin_inter_id();
		if( $inter_id==FULL_ACCESS || empty($post['inter_id']) ) 
		    $post['inter_id'] = $inter_id;
		
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'share_title'=> array(
	            'field' => 'share_title',
	            'label' => $labels['share_title'],
	            'rules' => 'trim|required',
	        ),
	        // 'timeline_title'=> array(
	        //     'field' => 'timeline_title',
	        //     'label' => $labels['timeline_title'],
	        //     'rules' => 'trim|required',
	        // ),
	        'start_time'=> array(
	            'field' => 'start_time',
	            'label' => $labels['start_time'],
	            'rules' => 'trim|required',
	        ),
	    );

		if( isset($post['share_img']) && $post['share_img'] ){

	    } else {
		    //检测并上传文件。
		    $post= $this->_do_upload($post, 'share_img');
	    }

	    if( isset($post['timeline_img']) && $post['timeline_img'] ){

	    } else {
		    //检测并上传文件。
		    $post= $this->_do_upload($post, 'timeline_img');
	    }

	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['create_time']= date('Y-m-d H:i:s');
	            $result= $model->_m_save($post);
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
    	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
	            $this->_log($model);
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	    $fields_config= $model->get_field_config('form');

	    $this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));
	    $view_params= array(
	        'model'=> $model,
	        'products'=> $products,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	
	
}
