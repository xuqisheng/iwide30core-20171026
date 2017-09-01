<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Ads extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '广告设置';
	protected $label_action = '';
	const ENUM_AD_AREA_STATUS = 'HOTEL_AD_AREA_STATUS';
	const ENUM_AD_AREA_COEXIST = 'HOTEL_AD_AREA_COEXIST';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'plugins/Advert_model';
	}
	public function index() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$this->load->model ( 'common/Enum_model' );
		$data ['hotel_id'] = intval ( $this->input->get_post ( 'hotel' ) );
		$this->load->model ( 'hotel/Hotel_model' );
		$hotels = $this->Hotel_model->get_all_hotels ( $this->inter_id );
		foreach ( $hotels as $h ) {
			$data ['hotels'] [$h ['hotel_id']] = $h;
		}
		$enum_des = $this->Enum_model->get_enum_des ( array (
				self::ENUM_AD_AREA_STATUS,
				self::ENUM_AD_AREA_COEXIST 
		) );
		if (empty ( $data ['hotel_id'] ))
			$data ['hotel_id'] = 0;
		$list = $model->get_ad_area_list ( $this->inter_id, $this->module, $data ['hotel_id'] );
		foreach ( $list as $k => $l ) {
			if (isset ( $l ['status'] )) {
				$list [$k] ['status'] = $enum_des [self::ENUM_AD_AREA_STATUS] [$l ['status']];
				$list [$k] ['coexist'] = $enum_des [self::ENUM_AD_AREA_COEXIST] [$l ['coexist']];
			} else {
				$list [$k] ['status'] = '未设置';
				$list [$k] ['area_title'] = '未设置';
				$list [$k] ['coexist'] = '未设置';
			}
			if (! empty ( $data ['hotel_id'] )) {
				$list [$k] ['hotel'] = $data ['hotels'] [$data ['hotel_id']] ['name'];
			} else {
				$list [$k] ['hotel'] = '公共设置';
				$list [$k] ['coexist'] = '无';
			}
		}
		$data ['list'] = $list;
		$data ['fields_config'] = $model->grid_fields ();
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit() {
		$data = $this->common_data;
		$this->label_action = '广告配置';
		$this->_init_breadcrumb ( $this->label_action );
		$model = $this->_load_model ( $this->main_model_name () );
		$data ['hotel_id'] = intval ( $this->input->get_post ( 'h' ) );
		$data ['code'] = $this->input->get_post ( 'aid' );
		$data ['list'] = $model->get_ad_area_list ( $this->inter_id, $this->module, $data ['hotel_id'], $data ['code'] );
		$this->load->model ( 'common/Enum_model' );
		$enum_des = $this->Enum_model->get_enum_des ( array (
				self::ENUM_AD_AREA_STATUS,
				self::ENUM_AD_AREA_COEXIST 
		) );
		$data ['coexist_des'] = $enum_des [self::ENUM_AD_AREA_COEXIST];
		$data ['status_des'] = $enum_des [self::ENUM_AD_AREA_STATUS];
		$data ['ads'] = $model->get_hotel_ads ( $this->inter_id, $data ['hotel_id'], $data ['code'] );
		$data ['ad_list'] = $model->get_ad_by_ids ( $this->inter_id, 0, null, 1 ,$data ['code']);
		$this->_render_content ( $this->_load_view_file ( 'edit' ), $data, FALSE );
	}
	public function edit_post() {
		$model = $this->_load_model ( $this->main_model_name () );
		$hotel_id = $this->input->post ( 'hotel_id' );
		$data ['area_title'] = $this->input->post ( 'area_title' );
		$data ['coexist'] = intval ( $this->input->post ( 'coexist' ) );
		$data ['status'] = intval ( $this->input->post ( 'status' ) );
		$data ['ads_ids'] = $this->input->post ( 'ads_str' );
		$area_type = $this->input->post ( 'code' );
		$model->replace_ad_area ( $this->inter_id, $hotel_id, $area_type, $data );
		redirect ( site_url ( 'hotel/ads/index' ) . '?hotel=' . $hotel_id );
	}
	public function ad_list() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$data ['fields_config'] = $model->list_fields ();
		$this->load->model ( 'common/Enum_model' );
		$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_AD_STATUS' );
		$list = $model->get_ad_by_ids ( $this->inter_id, 0 );
		foreach($list as $k=>$l){
			$list[$k]['status']=$status_des[$l['status']];
		}
		$data ['list'] = $list;
		$this->_render_content ( $this->_load_view_file ( 'ad_list' ), $data, false );
	}
	public function ad_edit() {
		$data = $this->common_data;
		$data ['aid'] = $this->input->get ( 'aid' );
		$model = $this->_load_model ( $this->main_model_name () );
		$this->load->model ( 'common/Enum_model' );
		$data['status_des'] = $this->Enum_model->get_enum_des ( 'HOTEL_AD_STATUS' );
		if (! empty ( $data ['aid'] )) {
			$list = $model->get_ad_by_ids ( $this->inter_id, 0, $data ['aid'] );
			if (! empty ( $list )) {
				$data ['list'] = $list [0];
			} else {
				redirect ( site_url ( 'hotel/ads/ad_list' ) );
			}
		} else {
			$data ['list'] = $model->table_fields ();
		}
		$this->db->where ( array (
				'inter_id' => 'defaultimg',
				'type' => 'ad_service' 
		) );
		$data ['services'] = $this->db->get ( 'hotel_images' )->result ();

		$this->_render_content ( $this->_load_view_file ( 'ad_edit' ), $data, false );
	}
	public function ad_edit_post(){
		$model = $this->_load_model ( $this->main_model_name () );
		$id = intval($this->input->post ( 'aid' ));
		$data ['ad_title'] = $this->input->post ( 'ad_title' );
		$data ['ad_link'] = $this->input->post ( 'link' );
		$data ['ad_img'] = $this->input->post ( 'img' );
		$data ['des'] = $this->input->post ( 'des' );
		$data ['status'] = intval($this->input->post ( 'status' ));
		if($this->input->post ( 'ser' )){
			$data ['ad_img'] = $this->input->post ( 'ser' );
		}
		$model->replace_ad ( $this->inter_id, $id, $data );
		redirect ( site_url ( 'hotel/ads/ad_list' ) );
	}
}
