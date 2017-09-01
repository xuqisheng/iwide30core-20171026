<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Statis_product extends MY_Admin_Soma {

	protected $label_controller= '产品销售统计';		//在文件定义
    protected $label_action= '';				//在方法中定义
    
    const MAX_DAYS= 90;  //查看最长统计天数
    const MIN_DAYS= 1;  //查看最短统计天数

	public function index() {
		$this->show_product_data();
	}

	public function show_product_data($export = false) {
		
		$this->label_action = "产品统计信息";
		$this->_init_breadcrumb($this->label_action);

		$inter_id = $this->session->get_admin_inter_id();
		// $inter_id = $this->session->get_temp_inter_id();
		if(!$inter_id) { $inter_id = 'deny'; }
		// $inter_id = 'a457946152';

		$ent_ids= $this->session->get_admin_hotels();
		$hotel_ids= $ent_ids? explode(',', $ent_ids ): array();

		$s_date = $this->input->get('s_date', true);
		$e_date = $this->input->get('e_date', true);
		if(!$s_date) { $s_date = date('Y-m-d'); }
		if(!$e_date) { $e_date = date('Y-m-d'); }

		$pid = $this->input->get('pid', true);

		$where = array();
		if($inter_id != FULL_ACCESS) { $where['inter_id'] = $inter_id; }
		if(count($hotel_ids) > 0) { $where['hotel_id'] = $hotel_ids; }
		$where['statis_date'] = array('s_date' => $s_date, 'e_date' => $e_date);
		if($pid) { $where['product_id'] = $pid; }

		// 汇总信息最小粒度：公众号，酒店号
		$pks = array('inter_id', 'hotel_id', 'product_id', /*"statis_date"*/);
		$filter['where'] = $where;
		// var_dump($filter);exit;
		
		$this->load->model('soma/statis_product_model', 's_model');
		$tb_header = $this->s_model->get_summary_header();
		$tb_content = $this->s_model->get_summary_statis_data($filter, $pks);
		$data['column_set'] = $this->s_model->format_grid_header($tb_header);
		$data['data_set'] = $this->s_model->format_grid_content($tb_content, $inter_id);
		$data['s_date'] = $s_date;
		$data['e_date'] = $e_date;

		if($export) {
			return array('header' => $tb_header, 'data' => $data['data_set']);
			// $this->_do_export($tb_content, $tb_header, 'csv', TRUE );exit;
		}

		$html= $this->_render_content($this->_load_view_file('index'), $data, TRUE);
		echo $html;

	}

	public function export() {
		$data = $this->show_product_data(true);
		$export = array();
		foreach($data['data'] as $key => $row) {
			$_tmp = $row;
			$_tmp[5] = str_replace(',', '', $_tmp[5]);
			$_tmp[7] = str_replace(',', '', $_tmp[7]);
			$_tmp[8] = str_replace(',', '', $_tmp[8]);
			$export[] = $_tmp;
		}
		$this->_do_export($export, $data['header'], 'csv', TRUE );
	}

	public function test_data() {
		$this->load->model('soma/statis_product_model', 's_model');
        $s_time = date('Y-m-d', strtotime("-15 days"));
        $e_time = date('Y-m-d', strtotime("+15 days", strtotime($s_time)));
        $this->s_model->update_statis_data($s_time, $e_time);
	}

	public function init_sales_count() {

		// 限制仅允许开发人员使用此功能
		$this->_toolkit_writelist();

		$s_date = $this->input->get('s_date', true);
		$e_date = $this->input->get('e_date', true);

		$where['statis_date'] = array('s_date' => $s_date, 'e_date' => $e_date);
		$pks = array('inter_id', 'hotel_id', 'product_id', /*"statis_date"*/);
		$filter['where'] = $where;

		$fmt_data = array();
		$this->load->model('soma/statis_product_model', 's_model');
		$data = $this->s_model->get_summary_statis_data($filter, $pks);

		foreach ($data as $key => $row) {
			$fmt_data[] = array('product_id' => $row['product_id'], 'sales_cnt' => $row['sales_qty']);
		}

		$this->load->model('soma/Product_package_model', 'p_model');
		$res = $this->p_model->import_sales_count($fmt_data);

		echo $res ? 'SUCCESS' : 'FAILURE';

	}

}