<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Live_user extends MY_Admin_Soma {

	protected function main_model_name() {
		return 'soma/Live_user_model';
	}

	// 显示预订单列表
	public function grid() {
		$filter = array();
	    $inter_id = $this->session->get_admin_inter_id();

		if(!$inter_id) {
			$inter_id = 'deny';
		}
		if($inter_id != FULL_ACCESS){
			$filter['inter_id'] = $inter_id;
		}
	    
		$this->_grid($filter);
	}

	public function edit_post() {

		$this->load->model($this->main_model_name(), 'u_model');
		$labels = $this->u_model->attribute_labels();

		$base_rule = array(
			'inter_id'=> array(
	            'field' => 'inter_id',
	            'label' => $labels['inter_id'],
	            'rules' => 'trim|required',
	        ),
			'username'=> array(
	            'field' => 'username',
	            'label' => $labels['username'],
	            'rules' => 'trim|required|min_length[4]',
	        ),
	        'password'=> array(
	            'field' => 'password',
	            'label' => $labels['password'],
	            'rules' => 'trim|required|min_length[8]',
	        ),
	        'password_comfirm'=> array(
	            'field' => 'password_comfirm',
	            'label' => '确认密码',
	            'rules' => 'trim|required|min_length[8]',
	        ),
		);

		$this->load->library('form_validation');
		$this->form_validation->set_rules($base_rule);
		$message = '';
		if ($this->form_validation->run() == TRUE) {
			$post = $this->input->post(null, true);
			if($post['password'] == $post['password_comfirm']) {

				$post['create_time'] = date('Y-m-d H:i:s');
				$admin = $this->session->admin_profile;
				$post['create_admin'] = $admin['username'];

				if($this->u_model->save_user($post)) {
					$this->session->put_success_msg('数据保存成功！');
				} else {
					$this->session->put_error_msg('操作失败，请稍后再重新尝试！');
				}
				$this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
			} else {
				$message = '两次输入密码不一致！';
			}
		} else {
			$message = $this->form_validation->error_html();
		}

		$fields_config= $this->u_model->get_field_config('form');

	    $view_params= array(
	        'model'=> $this->u_model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );

		$this->session->put_error_msg($message);
	    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	}

	public function test_valid_user() {
		$username = 'F.oris';
		$password = '123123123';
		$this->load->model($this->main_model_name(), 'u_model');
		var_dump($this->u_model->valid_user($username, $password));
	}

	public function test_sales_publics() {
		$this->load->model('soma/statis_product_model', 'ss_model');
		var_dump($this->ss_model->get_live_sales_data('11111', '2016-11-21 00:00:00'));
	}

}