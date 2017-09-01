<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_point extends MY_Admin_Soma {

	protected $label_controller= '会员积分';		//在文件定义
	protected $label_action= '';

	protected function main_model_name()
	{
		return 'soma/Sales_point_model';
	}

	/**
	 * 获取通用过滤条件
	 * 公众号，酒店ID
	 */
	protected function _common_filter() {
		$filter = array();

		$inter_id = $this->session->get_admin_inter_id();
		if(!$inter_id) { $filter['inter_id'] = 'deny';}
		if($inter_id != FULL_ACCESS) { $filter['inter_id'] = $inter_id; }

		// $hotel_ids = $this->session->get_admin_hotels();
		// if(!$hotel_ids) {
		// 	$filter['hotel_id'] = array();
		// } else {
		// 	$filter['hotel_id'] = explode(',', $hotel_ids );
		// }

		return $filter;
	}

	public function grid() {
		$this->label_action = '积分进账规则列表';
		$this->_init_breadcrumb($this->label_action);

		$filter = $this->_common_filter();

		if(is_ajax_request()){
			$model_name= $this->main_model_name();
	    	$model= $this->_load_model($model_name);
			$get_filter= $this->_ajax_params_parse( $this->input->post(null, true), $model );
		} else {
			$get_filter= $this->input->get('filter', true);
		}
		if(is_array($get_filter)) {
			if(isset($get_filter['inter_id'])) {
				unset($get_filter['inter_id']);
			}
			if(isset($get_filter['hotel_id'])) {
				unset($get_filter['hotel_id']);
			}
			$filter += $get_filter;
		}

		$this->_grid($filter);
	}

	public function edit() {
		$this->label_action= '积分进账规则维护';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));
		if($id){ $model= $model->load($id); }
        if(!$model) { $model= $this->_load_model(); }

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		// 阻止非业务账号操作 _get_real_inter_id
		$filter = $this->_common_filter();
		$inter_id = $this->_get_real_inter_id(true);
		$filter['inter_id'] = $inter_id;

		$bonus_data = array();
		$bonus_size = $model->m_get('bonus_size');
		if($bonus_size) {
			$bonus_data = json_decode($bonus_size, true);
		}

		$model->init_member_api($inter_id);
		$lv_data = $model->get_member_level_name();
		foreach ($lv_data as $lk => $lv) {
			if(!isset($bonus_data[$lk])) {
				$bonus_data[$lk] = array('name' => $lv, 'size' => 0);
			} else {
				$bonus_data[$lk] = array('name' => $lv, 'size' => $bonus_data[$lk]);
			}
		}

		$fields_config= $model->get_field_config('form');

		$this->load->model('soma/Product_package_model');
	    $product_model= $this->Product_package_model;
	    $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));

	    $grid_data = array();
	    $this->load->helper('soma/package');
	    foreach ($products as $p) {
	    	$row = array();
	    	$row[] = '';
	    	$row[] = $p['product_id'];
	    	$row[] = $p['name'];
	    	$row[] = $p['price_package'];
	    	$row['DT_RowId'] = $p['product_id'];
	    	$grid_data[] = $row;
	    }

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'bonus_data' => $bonus_data,
		    'check_data'=> FALSE,
		    'products' => $products,
		    'grid_data' => $grid_data,
		    'tr_slt' => $model->m_get('product_ids'),
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	/**
	 * 权限验证->数据验证->数据提取->数据保存
	 * 保存规则表-->保存规则产品表
	 */
	public function edit_post() {
		$post = $this->input->post(null, true);
		$admin = $this->session->admin_profile;
		$post['op_user'] = $admin['username'];
		// var_dump($post);exit;
		$model= $this->_load_model();
		if(isset($post['rule_id'])) { $model->load($post['rule_id']); }

		if($model !=null 
			&& $this->_can_edit($model) 
			&& $model->form_validation($post)) {

			$model->trans_begin();

			$fmt_data = $model->format_rule_data($post);
			$res = $model->m_save($fmt_data);
			if($res) {

				if(!isset($post['rule_id']) 
					|| $post['rule_id'] == '') {
					$post['rule_id'] = $res;
				}

				$p_data = $model->format_rule_product_data($post);
				$p_res = $model->save_rule_product($p_data);
				if($p_res) {
					$model->trans_commit();
					$this->session->put_success_msg('操作成功！');
				} else {
					$model->trans_rollback();
					$this->session->put_error_msg('操作失败，请稍后再重新尝试');
				}
			} else {
				$model->trans_rollback();
				$this->session->put_error_msg('操作失败，请稍后再重新尝试');
			}

		} else {
			$this->session->put_notice_msg('不允许修改的数据或提交的数据有误，请稍后再重新尝试');
		}
		$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	}

}