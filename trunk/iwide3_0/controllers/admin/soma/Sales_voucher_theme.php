<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_voucher_theme extends MY_Admin_Soma {

	protected function main_model_name()
	{
		return 'soma/Sales_voucher_theme_model';
	}

	public function edit() {

		$this->label_action= '礼品卡券主题配置';
		$this->_init_breadcrumb($this->label_action);
	    $inter_id= $this->session->get_admin_inter_id();
	    
	    $error_msg = $model = null;
	    $fields_config = array();

	    if(!$inter_id || $inter_id == FULL_ACCESS) {
	    	$error_msg = '不允许跨公众号账号进行该操作!';
	    } else {
			$model_name= $this->main_model_name();
			$model= $this->_load_model($model_name);
			$model->load_by_inter_id($inter_id);
			$fields_config= $model->get_field_config('form');
		}

		$params = array('error_msg' => $error_msg, 
			'model' => $model, 'fields_config' => $fields_config, 'check_data' => FALSE);
		$html= $this->_render_content($this->_load_view_file('edit'), $params, TRUE);
		echo $html;
	}

	public function edit_post() {

		$this->label_action= '礼品卡券主题配置';
	    $inter_id= $this->session->get_admin_inter_id();
		if(!$inter_id || $inter_id == FULL_ACCESS) {
	    	$this->session->put_error_msg('不允许跨公众号账号进行该操作!');
	    } else {
	    	// die('111');
			$post = $this->input->post(null, true);

			$base_rules = array(
				'page_content' => array(
					'field' => 'page_content',
	            	'label' => '首页文字内容',
		            'rules' => 'trim|max_length[20]',
				),
			);

			$this->load->library('form_validation');
			$this->form_validation->set_rules($base_rules);
			if(!$this->form_validation->run()) {
				$this->session->put_error_msg($this->form_validation->error_string());
				$this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
			}

			$model_name= $this->main_model_name();
	    	$model= $this->_load_model($model_name);
	    	$model->load_by_inter_id($inter_id);

	    	//检测并上传文件。
			$post= $this->_do_upload($post, 'bg_img');
			$post= $this->_do_upload($post, 'btn_img');

			$pk = $model->table_primary_key();
			$post[$pk] = $model->m_get($pk);
			if($model->m_sets($post)->m_save()) {
				$this->session->put_success_msg('此次数据保存成功！');
			} else {
				$this->session->put_error_msg('此次数据保存失败！');
			}
		}

		$this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
	}

}