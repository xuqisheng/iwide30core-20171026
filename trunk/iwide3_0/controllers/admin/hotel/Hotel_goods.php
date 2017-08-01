<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_goods extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '商品管理';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/goods/Goods_info_model';
	}
	public function index() {
		$data = $this->common_data;
		$this->label_action = '商品列表';
		$this->_init_breadcrumb ( $this->label_action );
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}

}
