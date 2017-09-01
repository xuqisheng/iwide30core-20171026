<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departments extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '分销部门';
	protected $label_controller= '分销部门';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'distribute/departments_model';
	}

    /**
     * 首页模板
     */
	function index()
    {
        $this->grid();
	}
	
	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
		if(is_ajax_request())
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
	    $this->_grid($filter);
	}
	public function edit()
	{
		$this->label_action= '新增部门';
		$this->_init_breadcrumb($this->label_action);

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$id= intval($this->input->get('ids'));
		if($id){
			//for edit page.
			$model= $model->load($id);
			$fields_config= $model->get_field_config('form');
			$detail_field = array();
			if( count($detail_field)>0 ){
				$detail_field= $detail_field[0]['attr_value'];
			} else {
				$detail_field= '';
			}

		} else {
			//for add page.
			$model= $model->load($id);
			if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');
			$detail_field= '';
		}


		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> FALSE,
				'detail_field'=> $detail_field,
// 				'gallery'=> $gallery,
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
				'dept_name'=> array(
						'field' => 'dept_name',
						'label' => $labels['dept_name'],
						'rules' => 'trim',
				),
                'hotel_id'=> array(
                    'field' => 'hotel_id',
                    'label' => $labels['hotel_id'],
                    'rules' => 'trim',
                ),
				'status'=> array(
						'field' => 'status',
						'label' => $labels['hotel_id'],
						'rules' => 'trim',
				)
		);

		$adminid= $this->session->get_admin_id();

		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);

			if ($this->form_validation->run() != FALSE) {
				$this->load->model ( 'distribute/departments_model' );
                $result = $model->add_departments();
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				//$this->_log($model);
// 				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));//@author lGh 修改跳转 2016-3-30 10:39:54
				$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));

			} else
				$model= $this->_load_model();

		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
                $post['inter_id'] = $this->session->get_admin_inter_id();
				$result= $model->load($post[$pk])->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已保存数据！'):
				$this->session->put_notice_msg('此次数据修改失败！');



				$this->_log($model);
// 				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));//@author lGh 修改跳转 2016-3-30 10:39:54
				$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));

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
