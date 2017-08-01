<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Admin_MALL {

	protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '商品分类';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_category';
	}

	public function grid()
	{
		$this->label_action= '商品分类';
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

	/**
     * 处理新增和编辑方法
     */
	public function edit()
	{
		$this->label_action= '分类修改';
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

		$view_params= array(
		    'model'=> $model,
		    'imgs'=> $model->get_cat_img(),
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
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
	    if( isset($post['cat_img_']) && $post['cat_img_'] ){
	    	$post['cat_img']= $post['cat_img_'];

	    } else {
		    //检测并上传文件。
		    $post= $this->_do_upload($post, 'cat_img');
	    }


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
		    'imgs'=> $model->get_cat_img(),
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}

	
}
