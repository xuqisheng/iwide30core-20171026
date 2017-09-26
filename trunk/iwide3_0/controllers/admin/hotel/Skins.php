<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Skins extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '皮肤设置';
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
		return 'common/Skins_model';
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
	public function skin_theme() {
		$data = $this->common_data;
		$model = $this->main_model ();
		$skin_set = $model->get_skin_set ( $this->inter_id, $this->module );
		if (empty ( $skin_set )) {
			$skin_set = $model->table_fields ();
			$skin_set['skin_name']='default2';
		} else {
			$skin_set ['overall_style'] = json_decode ( $skin_set ['overall_style'], TRUE );
		}
		$data ['skin_set'] = $skin_set;
		$data ['skin_decoras'] = json_decode($model->enums('skin_decora',$skin_set['skin_name']),TRUE);
		$this->_render_content ( $this->_load_view_file ( 'skin_theme' ), $data, false );
	}

    /**
     * 前后端分离后的皮肤管理
     * @author daikanwu <daikanwu@jperation.com>
     *
     */
    public function handle_skin()
    {
        $data = $this->common_data;
        $this->_render_content($this->_load_view_file('handle_skin'), $data, false);
    }

	public function edit_theme() {
		$data = $this->input->post ();
		$model = $this->main_model ();
		$skin_set = $model->get_skin_set ( $this->inter_id, $this->module );
		$set = array ('overall_style'=>'');
		if (empty ( $data ['default_color'] ) && ! empty ( $data ['theme_color'] )) {
			$set ['overall_style'] ['theme_color'] = $data ['theme_color'];
		}
		if (empty ( $data ['default_fx'] ) && ! empty ( $data ['fontx'] )) {
			$set ['overall_style'] ['fontx'] = $data ['fontx'];
		}
		if (! empty ( $data ['skin_decora'] )) {
			$skin_decoras = json_decode($model->enums('skin_decora',$skin_set['skin_name']),TRUE);
			if (isset($skin_decoras[$data['skin_decora']]))
				$set ['overall_style'] ['decora'] = $data ['skin_decora'];
		}
		if (! empty ( $set ['overall_style'] )) {
			$set ['overall_style'] = json_encode ( $set ['overall_style'] );
		}
		$info = array (
				'status' => 2,
				'message' => 'error'
		);
		if (! empty ( $skin_set )) {
			if ($model->update_skin_set ( $this->inter_id, $this->module, $skin_set ['id'], $set )) {
				$info ['status'] = 1;
				$info ['message'] = '保存成功';
			} else {
				$info ['message'] = '保存失败';
			}
		} else {
			$set['skin_name']='default2';
			if ($model->add_skin_set ( $this->inter_id, $this->module, $set )) {
				$info ['status'] = 1;
				$info ['message'] = '保存成功';
			} else {
				$info ['message'] = '保存失败';
			}
		}
		echo json_encode ( $info );
		exit ();
	}
}
