<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keywords extends MY_Admin {

	protected $label_module= '公众号信息';
	protected $label_controller= '公众号列表';
	protected $label_action= '';
	private $inter_id;
	
	function __construct(){
		parent::__construct();
		$user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $user_profiler['inter_id'];
		if(!$this->inter_id || $this->inter_id == 'ALL_PRIVILEGES')$this->inter_id= 'a429262687';
	}
	
	protected function main_model_name()
	{
		return 'wx/keywords_model';
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
	public function edit()
	{
		$this->label_action= '关键字管理';
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
		$this->load->model('wx/news_model');
		$news = $this->news_model->get_all_news($this->inter_id)->result();
		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> FALSE,
				'detail_field'=> $detail_field,
				'news'=> $news
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
				'keyword'=> array(
						'field' => 'keyword',
						'label' => $labels['keyword'],
						'rules' => 'trim|required',
				),
				'match_type'=> array(
						'field' => 'match_type',
						'label' => $labels['match_type'],
						'rules' => 'trim|required',
				),
				'create_time'=> array(
						'field' => 'create_time',
						'label' => $labels['create_time'],
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
	
	public function save(){
		$this->load->model('wx/keywords_model');
		echo $this->keywords_model->save($this->inter_id);
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
