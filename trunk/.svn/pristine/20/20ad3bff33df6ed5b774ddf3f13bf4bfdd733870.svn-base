<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programs extends MY_Admin {

	protected $label_module= '小程序信息';
	protected $label_controller= '小程序列表';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'wx/Programs_model';
	}

	public function grid()
	{
		$this->label_action = '小程序管理';
		$this->_init_breadcrumb ( $this->label_action );
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$condit = array(
			'select'=>'status,recommend,create_time',
			'all'=>true
		);
		$data['list'] = $model->get_list($condit);
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit()
	{
		$this->label_action= '小程序添加/编辑';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		$id= $this->input->get('ids');
		$view_params = array(
			'model' => $model
		);
		if($id){
			$view_params['row'] = $model->get_row($id,',recommend,status');
			$view_params['row']['detail_img'] = stripslashes($view_params['row']['detail_img']);			
		}
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
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
				'name'=> array(
						'field' => 'name',
						'label' => $labels['name'],
						'rules' => 'trim|required',
				),
				'short_intro'=> array(
						'field' => 'short_intro',
						'label' => $labels['short_intro'],
						'rules' => 'trim|required',
				),
				'intro'=> array(
						'field' => 'intro',
						'label' => $labels['intro'],
						'rules' => 'trim',
				),
				'intro_img'=> array(
						'field' => 'intro_img',
						'label' => $labels['intro_img'],
						'rules' => 'trim',
				),
				'status'=> array(
						'field' => 'status',
						'label' => $labels['status'],
						'rules' => 'trim',
				),
				'recommend'=> array(
						'field' => 'recommend',
						'label' => $labels['recommend'],
						'rules' => 'trim',
				),
				'qrcode_img'=> array(
						'field' => 'qrcode_img',
						'label' => $labels['qrcode_img'],
						'rules' => 'trim',
				),
				'author'=> array(
						'field' => 'author',
						'label' => $labels['author'],
						'rules' => 'trim',
				),
				'detail_img'=> array(
						'field' => 'detail_img',
						'label' => $labels['detail_img'],
						'rules' => 'trim',
				)
		);
	
		 
		if( empty($post['pro_id']) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
				$post['create_time']= date('Y-m-d H:i:s');
				$result= $model->m_sets($post)->m_save($post,FALSE);
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
				$result= $model->load($post[$pk])->m_sets($post)->m_save($post);
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
