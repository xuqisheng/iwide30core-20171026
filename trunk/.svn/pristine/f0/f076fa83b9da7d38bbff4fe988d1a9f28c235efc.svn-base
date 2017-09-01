<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Home_setting extends MY_Admin {
	protected $label_controller = '首页设置';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/Views_model';
	}
	public function index() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_config_model' );
		$this->load->model ( 'common/Skins_model' );
		$model = $this->_load_model ( $this->main_model_name () );
		$data ['default_skin'] = $model::DEFAULT_SKIN;
		$data ['select'] = $model->enums ( 'HOME_SETTING_SELECT' );
		;
		$config_data = $this->Hotel_config_model->get_hotels_config_row ( $this->inter_id, 'HOTEL', 0, 'HOME_SETTING' );
		$data ['disp_set'] = $this->Skins_model->get_disp_set ( $this->inter_id, 'hotel/search' );
		if (! empty ( $config_data )) {
			$data ['id'] = $config_data ['id'];
			$data ['config'] = json_decode ( $config_data ['param_value'], true );
			$data ['status'] = $config_data ['priority'] >= 0 ? 1 : 0;
		}
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit_post() {
		$model = $this->_load_model ( $this->main_model_name () );
		$home_disp = $this->input->post ( 'home_disp' );
		$this->load->model ( 'common/Skins_model' );
		$disp_set = $this->Skins_model->get_disp_set ( $this->inter_id, 'hotel/search' );
		$set = array ();
		if (! empty ( $disp_set )) {
			if ($disp_set ['skin_name'] == $model::DEFAULT_SKIN) {
				if ($home_disp == 'new') {
					$set ['view_subfix'] = 'new';
				} else {
					$set ['view_subfix'] = '';
				}
				$this->Skins_model->update_disp_set ( $this->inter_id, 'hotel', $disp_set ['id'], $set );
				$this->session->put_success_msg ( '已保存数据！' );
			} else {
				$this->session->put_notice_msg ( '此次数据保存失败！' );
			}
		} else {
			if ($home_disp == 'new') {
				$set ['view_subfix'] = 'new';
				$set ['func'] = 'search';
				$this->Skins_model->add_skin_set ( $this->inter_id, $this->module, array (
						'skin_name' => 'default2' 
				) );
				$this->Skins_model->add_disp_set ( $this->inter_id, 'hotel', $set );
				$this->session->put_success_msg ( '已保存数据！' );
			}
		}
		
		if ($home_disp == 'new') {
			$id = $this->input->post ( 'id' );
			if ($id > 0) {
				$data ['id'] = $id;
			}
			unset ( $_POST ['id'] );
			$data ['param_value'] = json_encode ( $_POST );
			$data ['param_name'] = 'HOME_SETTING';
			$data ['module'] = 'HOTEL';
			$data ['inter_id'] = $this->inter_id;
			$data ['hotel_id'] = 0;
			$this->load->model ( 'hotel/hotel_config_model' );
			$this->hotel_config_model->replace_config ( $data );
		}
		redirect ( site_url ( 'hotel/home_setting/index' ) );
	}
}
