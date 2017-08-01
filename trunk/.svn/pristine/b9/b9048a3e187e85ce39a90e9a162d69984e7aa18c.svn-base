<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Gallery extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '相册管理';
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
		return 'hotel/Gallery_model';
	}
	public function index() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$data ['hotel_id'] = $this->input->get_post ( 'hotel' );
		$this->load->model ( 'hotel/hotel_model' );
// 		$data ['hotels'] = $this->hotel_model->get_all_hotels ( $this->inter_id );
		
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! empty ( $data ['hotel_id'] ) && ! in_array ( $data ['hotel_id'], $hotel_ids )) {
				$data ['hotel_id'] = 0;
			}
			$data ['hotels'] = $this->hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
		} else
			$data ['hotels'] = $this->hotel_model->get_all_hotels ( $this->inter_id );
		
		$data ['fields_config'] = $model->grid_fields ();
		if (! empty ( $data ['hotel_id'] )) {
			$list = $model->get_gallery_count ( $this->inter_id, $data ['hotel_id'], false );
			$data ['list'] = $list;
		} else {
			$data ['hotel_id'] = empty ( $data ['hotels'] [0] ) ? 0 : $data ['hotels'] [0] ['hotel_id'];
		}
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$hotel_id = $this->input->get_post ( 'h' );
		
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if ( ! in_array ( $hotel_id, $hotel_ids )) {
				redirect(site_url('privilege/auth/deny'));
				exit ();
			}
		}
		
		$disp_type = $this->input->get_post ( 'dt' );
		$data ['type'] = $model->get_gallery_type ( $this->inter_id, 0, $disp_type );
		$data ['gallery'] = $model->get_hotel_gallery_by_gid ( $this->inter_id, $hotel_id, $disp_type, 1 );
		$data ['hotel_id'] = $hotel_id;
		$this->_render_content ( $this->_load_view_file ( 'edit' ), $data, false );
	}
	public function edit_post() {
		$datas = $this->input->post ( 'datas' );
		$deles = $this->input->post ( 'deles' );
		$hotel_id = $this->input->post ( 'hotel_id' );
		
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! in_array ( $hotel_id, $hotel_ids )) {
				echo '无权限';
				exit ();
			}
		}
		
		$gallery_id = $this->input->post ( 'gid' );
		$datas = json_decode ( $datas, TRUE );
		$deles = explode ( ',', $deles );
		$model = $this->_load_model ( $this->main_model_name () );
		if (! empty ( $datas )) {
			$model->save_gallery_img_batch ( $this->inter_id, $hotel_id, $gallery_id, $datas );
		}
		if (! empty ( $deles )) {
			$model->change_gallery_img_disp ( $this->inter_id, $hotel_id, $gallery_id, $deles, 0 );
		}
		redirect ( site_url ( 'hotel/gallery/index' ) . '?hotel=' . $hotel_id );
	}
	public function add_gallery() {
		$model = $this->_load_model ( $this->main_model_name () );
		$data ['image_url'] = $this->input->post ( 'image' );
		$data ['info'] = $this->input->post ( 'info' );
		$data ['sort'] = $this->input->post ( 'sort' );
		$data ['hotel_id'] = $this->input->post ( 'hotel_id' );
		$data ['disp_type'] = $this->input->post ( 'gid' );
		$data ['inter_id'] = $this->inter_id;
		$data ['room_id'] = 0;
		$data ['type'] = 'hotel_gallery';
		$id = $model->add_gallery_img ( $data );
		$info = array (
				's' => $id 
		);
		if ($id > 0) {
			$info ['errmsg'] = '保存成功';
		} else {
			$info ['errmsg'] = '保存失败';
		}
		echo json_encode ( $info );
	}
	public function gallery_type() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$list = $model->get_gallery_type ( $this->inter_id, 0 );
		foreach ( $list as $k => $l ) {
			if ($l ['priority'] == - 1) {
				$list [$k] ['priority'] = '无效';
			}
		}
		$data ['fields_config'] = $model->type_fields ();
		$data ['list'] = $list;
		$this->_render_content ( $this->_load_view_file ( 'gallery_type' ), $data, false );
	}
	public function type_edit() {
		$data = $this->common_data;
		$data ['gallery_id'] = $this->input->get ( 'tid' );
		$model = $this->_load_model ( $this->main_model_name () );
		if (! empty ( $data ['gallery_id'] )) {
			$list = $model->get_gallery_type ( $this->inter_id, 0, $data ['gallery_id'] );
			if (! empty ( $list )) {
				$data ['list'] = $list;
			} else {
				redirect ( site_url ( 'hotel/gallery/gallery_type' ) );
			}
		} else {
			$data ['list'] = $model->table_fields ();
		}
		$this->_render_content ( $this->_load_view_file ( 'type_edit' ), $data, false );
	}
	public function type_edit_post(){
		$model = $this->_load_model ( $this->main_model_name () );
		$id = $this->input->post ( 'tid' );
		$status=intval($this->input->post('status'));
		if($status==1){
			$data['priority']=intval($this->input->post('priority'));
		}else{
			$data['priority']=-1;
		}
		$data['param_value']=$this->input->post('name');
		if(!empty($id)){
			$check=$model->get_gallery_type ( $this->inter_id, 0, $id);
			if($check){
				$model->update_gallery_type ( $this->inter_id,$id ,$data);
			}else{
				exit( 'false');
			}
		}else{
			$model->add_hotel_gallery_type ( $this->inter_id,$data);
		}
		redirect ( site_url ( 'hotel/gallery/gallery_type' ));
	}
}
