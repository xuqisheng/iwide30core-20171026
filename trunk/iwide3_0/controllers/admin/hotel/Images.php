<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Images extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '图片管理';
	protected $label_action = '';
	const ENUM_HOTEL_IMG='HOTEL_IMAGE_TYPE';
	const ENUM_HOETL_ROOM_IMG='HOTEL_ROOM_IMAGE_TYPE';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/Image_model';
	}
	public function index() {
		$this->load->model ( 'hotel/Hotel_model' );
		$data ['hotel_id'] = $this->input->get_post ( 'hotel' );
		$data ['hty'] = $this->input->get_post ( 'hty' );
		$data ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id );
		$model = $this->_load_model ( $this->main_model_name () );
		$this->load->model('common/Enum_model');
		$enums=$this->Enum_model->get_enum_des(array (
				self::ENUM_HOTEL_IMG,
		) );
		$data['hotel_img_type']=$enums[self::ENUM_HOTEL_IMG];
		if (! empty ( $data ['hotel_id'] ) && ! empty ( $data ['hty'] )&&array_key_exists($data ['hty'],$data['hotel_img_type'])) {
			$list = $this->Hotel_model->get_imgs ( $data ['hty'],$this->inter_id, $data ['hotel_id']);
			foreach ($list as $k=>$l){
				$list[$k]['disp_status']=$l['status']==1?'显示':'隐藏';
				$list[$k]['image_url']='<p class="s_img" imgsrc="'.$list[$k]['image_url'].'" mode="1">'.$list[$k]['image_url'].'</p>';
			}
			$data['list']=$list;
		} else {
			$data ['hotel_id'] = $data ['hotels'] [0] ['hotel_id'];
		}
		$data['hotel_img_type']=$enums[self::ENUM_HOTEL_IMG];
		$data['grid_fields']=$model->grid_fields();
		$data ['room_list'] = $this->Hotel_model->get_hotel_rooms ( $this->inter_id, $data ['hotel_id'] );
		if (empty ( $data ['room_id'] ) && ! empty ( $data ['room_list'] ))
			$data ['room_id'] = $data ['room_list'] [0] ['room_id'];
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function room_img() {
		$this->load->model ( 'hotel/Hotel_model' );
		$data ['room_id'] = $this->input->get_post ( 'room_id' );
		$data ['hotel_id'] = $this->input->get_post ( 'hotel' );
		$data ['hty'] = $this->input->get_post ( 'hty' );
		$data ['rty'] = $this->input->get_post ( 'rty' );
		$data ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id );
		$model = $this->_load_model ( $this->main_model_name () );
		if (! empty ( $data ['hotel_id'] ) && ! empty ( $data ['room_id'] )) {
			$list = $model->get_room_price_set ( $this->inter_id, $data ['hotel_id'], $data ['room_id'], $data ['price_code'] );
			if (! empty ( $list )) {
				$list = $list [0];
			}
		} else {
			$data ['hotel_id'] = $data ['hotels'] [0] ['hotel_id'];
		}
		$this->load->model('common/Enum_model');
		$enums=$this->Enum_model->get_enum_des(array (
				self::ENUM_HOTEL_IMG,
				self::ENUM_HOETL_ROOM_IMG 
		) );
		$data['hotel_img_type']=$enums[self::ENUM_HOTEL_IMG];
		$data['room_img_type']=$enums[self::ENUM_HOETL_ROOM_IMG];
		$data ['room_list'] = $this->Hotel_model->get_hotel_rooms ( $this->inter_id, $data ['hotel_id'] );
		if (empty ( $data ['room_id'] ) && ! empty ( $data ['room_list'] ))
			$data ['room_id'] = $data ['room_list'] [0] ['room_id'];
		$this->_render_content ( $this->_load_view_file ( 'room_img' ), $data, false );
	}
}
