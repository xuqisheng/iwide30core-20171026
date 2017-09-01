<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Memberv extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '会员配置';
	protected $label_action = '';
	protected $common_data = array ();
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		$this->common_data ['inter_id'] = $this->inter_id;
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/Member_new_model';
	}
	function check_priviledge($is_ajax = 0) {
		if ($this->inter_id !== FULL_ACCESS) {
			if ($is_ajax == 1)
				exit ( json_encode ( array (
						'status' => 2,
						'message' => '无权限' 
				) ) );
			exit ( 'no priviledge' );
		}
	}
	public function m2v() {
		$this->check_priviledge ();
		$data = $this->common_data;
		
		$this->load->model ( 'wx/Publics_model' );
		$data ['pubs'] = $this->Publics_model->get_public_hash ();
		$data ['pubs'] = $this->Publics_model->array_to_hash ( $data ['pubs'], 'name', 'inter_id' );
		$this->db->where(array('param_name'=>'NEW_VIP','param_value'=>1,'module'=>'HOTEL','priority >='=>0));
		$vps=$this->db->get('hotel_config')->result_array();
		$data['vps']=array();
		foreach ($vps as $v){
			$data['vps'][$v['inter_id']]=$data['pubs'][$v['inter_id']];
		}
		$this->_render_content ( $this->_load_view_file ( 'm2v' ), $data, false );
	}
	public function to_new() {
		$this->check_priviledge ( 1 );
		$new_vip = $this->input->get ( 'new_vip' );
		$inter_id = $this->input->get ( 'inter_id' );
		$this->load->model ( 'wx/Publics_model' );
		$pubs = $this->Publics_model->get_public_hash ();
		$pubs = $this->Publics_model->array_to_hash ( $pubs, 'name', 'inter_id' );
		if ($new_vip == 1 && isset ( $pubs [$inter_id] )) {
			$this->load->model ( 'hotel/Member_model' );
			$result = $this->Member_model->set_vid ( $inter_id );
			if ($result == 1) {
				echo json_encode ( array (
						'status' => 1,
						'message' => '已修改' 
				) );
				exit ();
			} else if ($result == 2) {
				echo json_encode ( array (
						'status' => 2,
						'message' => '不需再次修改' 
				) );
				exit ();
			}
		}
		echo json_encode ( array (
				'status' => 2,
				'message' => '修改失败' 
		) );
		exit ();
	}
	public function to_tran() {
		$this->check_priviledge ( 1 );
		$inter_id = $this->input->get ( 'inter_id' );
		$this->load->model ( 'wx/Publics_model' );
		$pubs = $this->Publics_model->get_public_hash ();
		$pubs = $this->Publics_model->array_to_hash ( $pubs, 'name', 'inter_id' );
		if (isset ( $pubs [$inter_id] )) {
			
		}
	}
}
