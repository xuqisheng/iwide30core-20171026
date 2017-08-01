<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_no extends MY_Admin {

	protected $label_module= NAV_HOTELS;
	protected $label_controller= '房间号';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
	}
	
	protected function main_model_name()
	{
		return 'hotel/Room_no_model';
	}
	
	public function index(){
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$data['fields_config']=$model->grid_fields();
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
}
