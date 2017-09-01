<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Configs extends MY_Admin {
	protected $label_module = 'APP';
	protected $label_controller = 'App设置';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'app/App_config_model';
	}
	protected function main_model() {
		if (! isset ( $this->m_model )) {
			$this->load->model ( $this->main_model_name (), 'm_model' );
		}
		return $this->m_model;
	}
	public function index() {
		$data = $this->common_data;
		// $this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function wxapp() {
		$data = $this->common_data;
		$model = $this->main_model ();
		$channel = 'wxapp';
		$data ['common_config'] = $model->get_hotel_config ( $this->inter_id, '', $channel, 'common' );
		$data ['member_config'] = $model->get_hotel_config ( $this->inter_id, '', $channel, 'member' );
		$data ['hotel_config'] = $model->get_hotel_config ( $this->inter_id, '', $channel, 'hotel' );
		$this->load->model ( 'common/Enum_model' );
		$enum_des = $this->Enum_model->get_enum_des ( array (
				'WXAPP_SHARE_PAGES',
				'WXAPP_MEMBER_CENTER_MENU' 
		) );
		$data ['share_pages'] = empty ( $enum_des ['WXAPP_SHARE_PAGES'] ) ? array () : $enum_des ['WXAPP_SHARE_PAGES'];
		$member_center_menu = empty ( $enum_des ['WXAPP_MEMBER_CENTER_MENU'] ) ? array () : $enum_des ['WXAPP_MEMBER_CENTER_MENU'];
		$data ['member_config'] ['center_menu'] ['menus'] = empty ( $data ['member_config'] ['center_menu'] ['menus'] ) ? array () : json_decode ( $data ['member_config'] ['center_menu'] ['menus'], true );
		foreach ( $member_center_menu as $code => $des ) {
			$data ['member_config'] ['center_menu'] ['menus'] [$code] = array (
					'des' => $des,
					'checked' => empty ( $data ['member_config'] ['center_menu'] ['menus'] [$code] ) ? 0 : 1 
			);
		}
		$this->_render_content ( $this->_load_view_file ( 'wxapp_config' ), $data, false );
	}
	public function wxapp_save() {
		$data = $this->input->post ();
		$this->load->helper ( 'array' );
		$data = jqjson2arr ( json_decode ( $data ['datas'], TRUE ) );
		$datas = $configs = array ();
		foreach ( $data as $key => $d ) {
			$check = strpos ( $key, ':' );
			if ($check != FALSE) {
				$keys = explode ( ':', $key );
				$datas [$keys [0]] [$keys [1]] [$keys [2]] = $d;
			}
		}
		$model = $this->main_model ();
		$channel = 'wxapp';
		$configs ['common'] = $model->get_hotel_config ( $this->inter_id, '', $channel, 'common', 0, array (
				'column' => 1 
		) );
		$configs ['member'] = $model->get_hotel_config ( $this->inter_id, '', $channel, 'member', 0, array (
				'column' => 1 
		) );
		$configs ['hotel'] = $model->get_hotel_config ( $this->inter_id, '', $channel, 'hotel', 0, array (
				'column' => 1 
		) );
		$info = array (
				'status' => 1,
				'message' => '保存成功' 
		);
		$insert_data = array (
				'inter_id' => $this->inter_id,
				'hotel_id' => 0,
				'channel' => $channel,
				'priority' => 0 
		);
		foreach ( $datas as $module => $config ) {
			foreach ( $config as $type => $params ) {
				foreach ( $params as $name => $val ) {
					if (is_array ( $val )) {
						$val = json_encode ( $val, JSON_UNESCAPED_UNICODE );
					}
					if (isset ( $configs [$module] [$type] [$name] )) {
						$configs [$module] [$type] [$name] ['param_value'] = $val;
						$model->replace_config ( $configs [$module] [$type] [$name] );
					} else {
						$insert_data ['module'] = $module;
						$insert_data ['type'] = $type;
						$insert_data ['param_name'] = $name;
						$insert_data ['param_value'] = $val;
						$model->replace_config ( $insert_data );
					}
				}
			}
		}
		echo json_encode ( $info );
		exit ();
	}
}
