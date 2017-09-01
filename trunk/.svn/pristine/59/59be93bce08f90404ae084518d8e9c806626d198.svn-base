<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Imgs extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '图片配置';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/hotel_ext_model';
	}
	public function index() {
	}
	public function afocus() {
		$this->load->model ( 'wx/publics_model' );
		$datas = $this->publics_model->get_pub_imgs ( $this->inter_id, 'hotelslide_wxapp', 'normal' );
		$data = array (
				'datas' => $datas,
				'inter_id' => $this->inter_id 
		);
		
		$html = $this->_render_content ( $this->_load_view_file ( 'afocus' ), $data, true );
		echo $html;
	}
	public function afocus_save() {
		$info = array (
				'status' => 2,
				'message' => 'error' 
		);
		$datas = $this->input->post ();
		if (empty ( $datas ['imgurl'] )) {
			$info ['message'] = '图片不能为空';
			echo json_encode ( $info );
			exit ();
		}
		$data ['image_url'] = $datas ['imgurl'];
		$data ['sort'] = intval ( $datas ['sort'] );
		$data ['info'] = $datas ['describe'];
		$this->load->model ( 'wx/publics_model' );
		$is_success = $this->publics_model->save_lightbox ( $this->inter_id, NULL, $data, 'hotelslide_wxapp' );
		if ($is_success) {
			$info ['status'] = 1;
			$info ['message'] = 'ok';
		} else {
			$info ['status'] = 2;
			$info ['message'] = '保存失败';
		}
		echo json_encode ( $info );
		exit ();
	}
	public function afocus_del() {
		$info = array (
				'status' => 2,
				'message' => 'error' 
		);
		$datas = $this->input->get ();
		if (empty ( $datas ['key'] )) {
			echo json_encode ( $info );
			exit ();
		}
		$data ['status'] = 2;
		$this->load->model ( 'wx/publics_model' );
		$is_success = $this->publics_model->save_lightbox ( $this->inter_id, $datas ['key'], $data, 'hotelslide_wxapp' );
		if ($is_success) {
			$info ['status'] = 1;
			$info ['message'] = 'ok';
		} else {
			$info ['status'] = 2;
			$info ['message'] = '删除失败';
		}
		echo json_encode ( $info );
		exit ();
	}
	public function afocus_update() {
		$info = array (
				'status' => 2,
				'message' => 'error' 
		);
		$datas = $this->input->get ();
		if (empty ( $datas ['key'] )) {
			echo json_encode ( $info );
			exit ();
		}
		$data ['sort'] = intval ( $datas ['sort'] );
		$data ['info'] = $datas ['info'];
		$data ['status'] = intval ( $datas ['status'] ) == 0 ? 0 : 1;
		$this->load->model ( 'wx/publics_model' );
		$is_success = $this->publics_model->save_lightbox ( $this->inter_id, $datas ['key'], $data, 'hotelslide_wxapp' );
		if ($is_success) {
			$info ['status'] = 1;
			$info ['message'] = 'ok';
		} else {
			$info ['status'] = 2;
			$info ['message'] = '保存失败';
		}
		echo json_encode ( $info );
		exit ();
	}
}
