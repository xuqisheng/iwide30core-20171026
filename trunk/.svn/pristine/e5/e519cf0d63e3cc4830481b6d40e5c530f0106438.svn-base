<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_voucher_template extends MY_Admin_Soma {

	const DEV = FALSE;
	// 是否开启审核功能标识

	protected function main_model_name() {
		return 'soma/Sales_voucher_template_model';
	}

	// 显示券码列表
	public function grid() {
		$this->label_action = "模板列表";
	    $filter = $this->_grid_filter();
		$this->_grid($filter);
	}

	private function _grid_filter() {
		$filter = array();
		
		$inter_id = $this->session->get_admin_inter_id();
		// 获取测试的$inter_id
		// $inter_id = $this->session->get_temp_inter_id();
		if(self::DEV) { $inter_id = 'a450089706'; }

		if(!$inter_id) {
			$inter_id = 'deny';
		}
		$filter['inter_id'] = $inter_id;

		$hotel_ids = $this->session->get_admin_hotels();
		if(!$hotel_ids) {
			$filter['hotel_id'] = array();
		} else {
			$filter['hotel_id'] = explode(',', $hotel_ids );
		}

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

		return $filter;
	}

	public function edit() {
		$this->label_action= '券码模板';
		$this->_init_breadcrumb($this->label_action);

		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids', true));
		if($id){
			$model= $model->load($id);
		}

		if(!$model) $model= $this->_load_model();

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		// $model->m_set($model->table_primary_key(), '111');

		// 如果能获取到id，则说明所有值已经填写，禁止用户再次修改
		$disabled = false;
		if($model->m_get($model->table_primary_key())) { $disabled=true; }

		// 以attribute_ui里面的值进行数据重排
		$tmp_config = $model->get_field_config('form');
		$field_sort = $model->attribute_ui();
		$fields_config = array();
		foreach ($field_sort as $key => $value) {
			$fields_config[$key] = $tmp_config[$key];
			if($disabled) {
				$form_ui = $fields_config[$key]['form_ui'];
				if(strpos($form_ui, 'disabled') === FALSE) {
					$form_ui .= ' disabled';
				}
				$fields_config[$key]['form_ui'] = $form_ui;
			}
		}

		$inter_id = $this->session->get_admin_inter_id();
		$hotel_ids = $this->session->get_admin_hotels();
		if(!$hotel_ids) {
			$hotel_ids = array();
		} else {
			$hotel_ids = explode(',', $hotel_ids );
		}

		$product_list = $model->get_product_list($inter_id, $hotel_ids);

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'product_list'=> $product_list,
		    'input_disabled' => $disabled,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	/**
	 * 权限验证->数据验证->数据提取->数据保存
	 * @return [type] [description]
	 */
	public function edit_post() {

		$post = $this->input->post(null, true);
		$model= $this->_load_model();
		// $inter_id = $this->session->get_admin_inter_id();
		$admin = $this->session->admin_profile;

		if($this->_can_edit($model) && $model->template_validation($post)) {

			$op_res = false;
			if(empty($post['template_id'])) {
				$data = array();
				$data['business'] = 'package';
				// $data['inter_id'] = $inter_id;
				$data['op_user'] = $admin['username'];
				$data = array_merge($data, $post);	

				$fmt_data = $model->format_template_data($data);
				$op_res = $model->m_save($fmt_data);
			} else {
				$model->load($post['template_id']);
				if($model) { 
					$op_res = $model->generate_sales_code($post['produce_cnt']);
				}
			}
			if($op_res) {
				$this->session->put_success_msg('操作成功');
			} else {
				$this->session->put_error_msg('操作失败，请稍后再重新尝试');
			}
		} else {
			$this->session->put_notice_msg('不允许修改的模板数据或提交的数据有误，请稍后再重新尝试');
		}

		$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));

	}

	/**
	 * 权限校验-->组装数据-->导出操作
	 * @return [type] [description]
	 */
	public function batch_export() {
		$post = $this->input->post(null, true);

		$tpl_id = isset($post['template_id']) ? intval($post['template_id']) : -1 ;
		$model_name = $this->main_model_name();
		$model = $this->_load_model($model_name);
		$model = $model->load($tpl_id);

		if(!$model || !$this->_can_edit($model)) {
			$this->session->put_error_msg('对不起，您没有该操作的权限！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$data = $model->batch_export($post['batch_no']);
		$header = $model->export_header();
		$this->_do_export($data, $header, 'csv', TRUE );
	}

	/**
	 * 显示批量导入页面
	 * @return [type] [description]
	 */
	public function batch_import_index() {
		//防止其他账号使用该功能。
	    $this->_toolkit_writelist();

	    $this->label_action= '礼品券数据导入';
		$this->_init_breadcrumb($this->label_action);

	    $html= $this->_render_content($this->_load_view_file('batch'), array(), TRUE);
	    //echo $html;die;
	    echo $html;
	}

	/**
	 * 书香酒店券码导入
	 * 模板id，券码csv，限管理员使用
	 * @return [type] [description]
	 */
	public function batch_import() {
		
		//防止其他账号使用该功能。
	    $this->_toolkit_writelist();

		$tpl_id = $this->input->post('template_id', true);
		$model_name = $this->main_model_name();
		$model = $this->_load_model($model_name);
		$model = $model->load($tpl_id);
		
		if(!$model) { die('无法加载模型'); }

        $csv = fopen($_FILES['batch']['tmp_name'], 'r');
        $csv_data = array(); 
        $n = 0; 
        while ($data = fgetcsv($csv)) { 
            $num = count($data); 
            for ($i = 0; $i < $num; $i++) { 
                $csv_data[$n][$i] = mb_convert_encoding($data[$i], 'utf-8', 'gbk');//$data[$i]; 
            } 
            $n++; 
        }
        unset($csv_data[0]);

        $code = array();
     	foreach ($csv_data as $row) { $code[] = $row[0]; }

     	if(count($code) <=0) { die('无有效数据'); }
     	$op_res = $model->generate_sales_code(0, $code);
     	if($op_res) { die('操作成功'); }

	}

}