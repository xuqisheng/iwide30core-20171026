<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_config extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '皮肤选择';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Theme_config_model';
	}

	public function grid()
	{
	    $this->label_action= '皮肤选择';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

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
	    $this->label_action= '皮肤选择';
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
	        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	    }

	
	    $inter_id= $this->session->get_admin_inter_id();
	    // $this->load->model('soma/Product_package_model');
	    // $product_model= $this->Product_package_model;
	    // $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));
	    
	    //取出皮肤列表
	    // $themes = $model->get_themes( $inter_id );
	    // $data = array();
	    // if( $themes ){
	    // 	$i = 0;
	    // 	foreach ($themes as $k => $v) {

	    // 		if( $i%4 == 0 && $i != 0 ){
	    // 			$i += 1;
	    // 		}

	    // 		$data[$i][] = $v;
	    // 	}
	    // }
	    // $len = count( $themes );//ceil( count( $themes ) / 4 );
// var_dump( $themes, $data );die;
	    $disabled = FALSE;
	    if( $inter_id == FULL_ACCESS ){
	    	$disabled = TRUE;
	    }

	    $view_params= array(
	        'model'=> $model,
	        // 'products'=> $products,
	        'fields_config'=> $fields_config,
	        'check_data'=> FALSE,
	        'disabled'=> $disabled,
	        // 'themes'=> $data,
	        // 'len'=> $len,
	    );
	
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}

	public function edit_post()
	{
	    $this->label_action= '皮肤选择';
	    $this->_init_breadcrumb($this->label_action);
    	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
// var_dump( $post );exit;
		if( isset( $post['select_inter_id'] ) ){
			//全部适用，就是inter_id为空
			// $post['inter_id'] = NULL;
			unset( $post['inter_id'] );
		}
	    $inter_id= $this->session->get_admin_inter_id();
		if( $inter_id==FULL_ACCESS || empty($post['inter_id']) ) 
		    // $post['inter_id'] = $inter_id;
     
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'theme_name'=> array(
	            'field' => 'theme_name',
	            'label' => $labels['theme_name'],
	            'rules' => 'trim|required',
	        ),
	        'theme_path'=> array(
	            'field' => 'theme_path',
	            'label' => $labels['theme_path'],
	            'rules' => 'trim|required',
	        ),
	    );

	    //检测并上传文件。
	    $post= $this->_do_upload($post, 'thumbnail');

	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $result= $model->m_sets( $post )->m_save( $post );
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $result= $model->load($post[$pk])->m_sets( $post )->m_save( $post );
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

	    // $this->load->model('soma/Product_package_model');
	    // $product_model= $this->Product_package_model;
	    // $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));
	    $view_params= array(
	        'model'=> $model,
	        // 'products'=> $products,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}


	
	
}
