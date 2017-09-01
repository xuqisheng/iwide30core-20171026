<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publics extends MY_Admin {

	protected $label_module= '公众号信息';
	protected $label_controller= '公众号列表';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'wx/public_ext_model';
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
		$this->label_action= '酒店管理';
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
		//获取相册数组
// 		$gallery= $model->get_gallery();
	
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
				'name'=> array(
						'field' => 'name',
						'label' => $labels['name'],
						'rules' => 'trim|required',
				),
				'public_id'=> array(
						'field' => 'public_id',
						'label' => $labels['public_id'],
						'rules' => 'trim|required',
				),
				'wechat_name'=> array(
						'field' => 'wechat_name',
						'label' => $labels['wechat_name'],
						'rules' => 'trim',
				),
				'app_id'=> array(
						'field' => 'app_id',
						'label' => $labels['app_id'],
						'rules' => 'trim',
				),
				'app_secret'=> array(
						'field' => 'app_secret',
						'label' => $labels['app_secret'],
						'rules' => 'trim',
				),
				'app_type'=> array(
						'field' => 'app_type',
						'label' => $labels['app_type'],
						'rules' => 'trim',
				),
				'alipay_id'=> array(
						'field' => 'alipay_id',
						'label' => $labels['alipay_id'],
						'rules' => 'trim',
				),
				'inter_id'=> array(
						'field' => 'inter_id',
						'label' => $labels['inter_id'],
						'rules' => 'trim',
				),
				'status'=> array(
						'field' => 'status',
						'label' => $labels['status'],
						'rules' => 'trim',
				),
				'create_time'=> array(
						'field' => 'create_time',
						'label' => $labels['create_time'],
						'rules' => 'trim',
				),
				'del_time'=> array(
						'field' => 'del_time',
						'label' => $labels['del_time'],
						'rules' => 'trim',
				),
				'crypt_type'=> array(
						'field' => 'crypt_type',
						'label' => $labels['crypt_type'],
						'rules' => 'trim',
				),
				'aes_key'=> array(
						'field' => 'aes_key',
						'label' => $labels['aes_key'],
						'rules' => 'trim',
				),
				'email'=> array(
						'field' => 'email',
						'label' => $labels['email'],
						'rules' => 'trim',
				),
				'logo'=> array(
						'field' => 'logo',
						'label' => $labels['logo'],
						'rules' => 'trim',
				),
				'domain'=> array(
						'field' => 'domain',
						'label' => $labels['domain'],
						'rules' => 'trim|required',
				),
				'is_multy'=> array(
						'field' => 'is_multy',
						'label' => $labels['is_multy'],
						'rules' => 'trim',
				)
		);
	
		//检测并上传文件。
		$post= $this->_do_upload($post, 'logo');
		 
		$adminid= $this->session->get_admin_id();
		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
				$this->load->model('wx/publics_model');
				$pi = $this->publics_model->get_public_by_id($post['app_id'],'app_id');
				if(!$pi){
					$post['add_date']= date('Y-m-d H:i:s');
					$post['add_user']= $adminid;
					$post['inter_id']=  'a'.substr(time(), 1);
					$post['create_time']= date('Y-m-d H:i:s');
					$this->load->helper('common');
					$post['token']= createNoncestr(32);
					$result= $model->m_sets($post)->m_save($post,FALSE);
					$message= ($result)?
					$this->session->put_success_msg('已新增数据！'):
					$this->session->put_notice_msg('此次数据保存失败！');
				}else{
					$this->session->put_error_msg('此公众号已经存在！');
				}
				
				/* 
				 *增加数据统计平台站点 
				 */
				$this->load->model("statistics/Statistics_model");
				$this->Statistics_model->addStatisticsWebsiteByInterid($post['inter_id']);
				//doCurlGetRequest()
				
				$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
				$post['last_update_time']= date('Y-m-d H:i:s');
				$post['last_update_user']= $adminid;
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
	
	public function edit_focus()
	{
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
		$post= $this->input->post();
	
		if($post['del_gallery']){
			$model->delete_gallery($post['del_gallery'], $post[$pk]);
		}
		//检测并上传新的文件。
		$post= $this->_do_upload($post, 'gallery');
		if(isset($post['gallery'])){
			$data= array(
					'gry_url'=> $post['gallery'],
					'gry_desc'=> $post['gry_desc'],
					'gs_id'=> $post['gs_id'],
			);
			$model->plus_gallery($data);
		}
		$this->session->put_success_msg('成功保存产品相册，请继续编辑产品信息');
		$this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $post[$pk]) ));
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
	
	public function verifytxt_upload(){
		$config ['upload_path'] = '../www_front';
		$filename = $_FILES ['txt'] ['name'];
		if (substr($filename, -4,4)!=='.txt'){
			echo json_encode ( array ( 'message' => '文件类型不对','status'=>2 ) );
			exit;
		}
		$config ['allowed_types'] = '*';
		$config ['max_size'] = '1';
		$config ['overwrite'] = TRUE;
		$this->load->library ( 'upload', $config );
		$this->upload->initialize ( $config );

		if ($this->upload->do_upload ( 'txt' )) {
			echo json_encode ( array ( 'message' => '上传成功','status'=>1 ) );
		} else {
			echo json_encode ( array ( 'message' => empty($this->upload->error_msg )?'':$this->upload->error_msg ,'status'=>1));
		}
	}
}
