<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom extends MY_Admin {

	protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '交易订单';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/custom';
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
	    $this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	     
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'cat_name'=> array(
	            'field' => 'cat_name',
	            'label' => $labels['cat_name'],
	            'rules' => 'trim|required',
	        ),
	        'parent_id'=> array(
	            'field' => 'parent_id',
	            'label' => $labels['parent_id'],
	            'rules' => 'trim|required',
	        ),
	        'hotel_id'=> array(
	            'field' => 'hotel_id',
	            'label' => $labels['hotel_id'],
	            'rules' => 'trim|required',
	        ),
	        'inter_id'=> array(
	            'field' => 'inter_id',
	            'label' => $labels['inter_id'],
	            'rules' => 'trim|required',
	        ),
	    );
	
	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {
	            $result= $model->m_sets($post)->m_save();
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	
	    $fields_config= $model->get_field_config('form');
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	
}
