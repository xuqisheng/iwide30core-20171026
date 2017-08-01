<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Admin {

	protected $label_module= '公众号图文回复';
	protected $label_controller= '图文回复列表';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'wx/news_model';
	}

	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    $filter['type'] = 1;//只读多图文
	    //print_r($filter);die;
	    
	    $this->_grid($filter);
	}
	public function edit()
	{
		$this->label_action= '图文管理';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		$id= $this->input->get('ids');
		if($id){
			//for edit page.
			$model= $model->load($id);
			$fields_config= $model->get_field_config('form');
// 			$sql= "select a.* from {$this->db->dbprefix}shp_goods_attr as a left join {$this->db->dbprefix}shp_attrbutes as b on a.attr_id=b.attr_id where a.gs_id=". $id;
// 			$detail_field= $this->db->query($sql)->result_array();
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
				'title'=> array(
						'field' => 'title',
						'label' => $labels['title'],
						'rules' => 'trim|required',
				),
				'description'=> array(
						'field' => 'description',
						'label' => $labels['description'],
						'rules' => 'trim|required',
				),
				'pic_url'=> array(
						'field' => 'pic_url',
						'label' => $labels['pic_url'],
						'rules' => 'trim',
				),
				'type'=> array(
						'field' => 'type',
						'label' => $labels['type'],
						'rules' => 'trim',
				),
				'url'=> array(
						'field' => 'url',
						'label' => $labels['url'],
						'rules' => 'trim',
				),
				'inter_id'=> array(
						'field' => 'inter_id',
						'label' => $labels['inter_id'],
						'rules' => 'trim',
				)
		);
	
		$adminid= $this->session->get_admin_id();
		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
				$post['create_time']= date('Y-m-d H:i:s');
				$post['type']= 1;
				$result= $model->m_sets($post)->m_save($post,FALSE);
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
// 				$post['last_update_time']= date('Y-m-d H:i:s');
// 				$post['last_update_user']= $adminid;
				$post['type']= 1;
				$result= $model->load($post[$pk])->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已保存数据！'):
				$this->session->put_notice_msg('此次数据修改失败！');
				$this->_log($model);
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
	
	public function delete(){
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
		$ids = $this->input->get('ids');
		
		
		
		$result= $model->delete($ids);
		$message= ($result)?
		$this->session->put_success_msg('已删除数据！'):
		$this->session->put_notice_msg('此次数据删除失败！');
		$this->_log($model);
		$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	}
	
	
}
