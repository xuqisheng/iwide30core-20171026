<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feebacks extends MY_Admin {

	protected $label_module= '反馈信息';
	protected $label_controller= '反馈信息列表';
	protected $label_action= '';
	private $inter_id;
	protected $status_arr = array ('0' => '未查看','1' => '已查看');

	function __construct(){
		parent::__construct();
		$user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $user_profiler['inter_id'];
	}
	
	/**
	 */
	public function index(){
		$this->_init_breadcrumb($this->label_action);
		$this->load->model ( 'hotel/hotel_model' );
		$this->load->model ( 'wx/publics_model' );
		$filterH ['inter_id'] = $this->session->get_admin_inter_id() == 'ALL_PRIVILEGES' ? array() : explode(',', $this->session->get_admin_inter_id());
		$filterH = empty($filterH['inter_id']) ? array() : $filterH;
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH, array('name', 'hotel_id','inter_id'),'array' );
		
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name','hotel_id' );
		$filterP['inter_id'] = $this->session->get_admin_inter_id() == 'ALL_PRIVILEGES' ? array() : explode(',', $this->session->get_admin_inter_id());
		$publics = $this->publics_model->get_public_hash($filterP, array('name', 'inter_id'),'array');
		$publics = $this->publics_model->array_to_hash($publics,'name','inter_id');
		$view_params= array('hotels'=>$hotels,'publics'=>$publics);
		echo $this->_render_content($this->_load_view_file('feebacks_grid'), $view_params, TRUE);
	}
	public function get_feebacks(){
		$hotel_id   = $this->input->post('hotel_id');
		$saler_name = $this->input->post('saler');
		$inter_id   = $this->input->post('inter_id');
		$time_begin = $this->input->post('time_begin');
		$time_end   = $this->input->post('time_end');
		$keyword    = $this->input->post('keywords');
		$this->load->model('distribute/feeback_model');
		$this->load->model ( 'hotel/hotel_model' );
		$this->load->model ( 'wx/publics_model' );
		$filterP ['inter_id'] = $this->session->get_admin_inter_id() == 'ALL_PRIVILEGES' ? array() : explode(',', $this->session->get_admin_inter_id());
		$filterH = empty($filterP['inter_id']) ? array() : $filterP;
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH, array('name', 'hotel_id'),'array');
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$publics = $this->publics_model->get_public_hash($hotels, array('name', 'inter_id'),'array');
		$publics = $this->publics_model->array_to_hash($publics,'name','inter_id');
		$order   = $this->input->post('order');
		$columns = $this->input->post('columns');
		if(empty($inter_id))
			$inter_id = $filterP ['inter_id'];
		$res = $this->feeback_model->get_feebacks($inter_id,$hotel_id,$time_begin,$time_end,$saler_name,$keyword,intval($this->input->post('length')),intval($this->input->post('start')),$columns[$order[0]['column']]['data'],$order[0]['dir'])->result_array();
		$total_rows = $this->feeback_model->get_feebacks_counts($inter_id,$hotel_id,$time_begin,$time_end,$saler_name,$keyword);
		
		$results = array();
		foreach ($res as $item){
			$item['id']       = 'FK'.(10000000 + $item['id']);
			$item['inter_id'] = isset($publics[$item['inter_id']]) ? $publics[$item['inter_id']] : '--';
			$item['hotel_id'] = isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '--';
			$results[] = $item;
		}
		echo json_encode(array('data'=>$results,'iTotalRecords'=>$total_rows,'iTotalDisplayRecords'=>$total_rows));
	}
	public function get_hotels(){
		$this->load->model ( 'hotel/hotel_model' );
		$admin_profile = $this->session->get_admin_profile();
		$params['inter_id'] = array($this->input->get('inter_id'));
		if(!empty($admin_profile['entity_id']))
			$params['hotel_id'] = explode(',',$admin_profile['entity_id']);
		$hotels = $this->hotel_model->get_hotel_hash ($params, array('name', 'hotel_id'),'array' );
		var_dump($hotels);
		echo json_encode($this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' ));
	}
}