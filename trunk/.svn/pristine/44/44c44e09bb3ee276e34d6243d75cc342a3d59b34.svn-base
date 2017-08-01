<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Brand extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '酒店品牌';
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
		return 'hotel/Brand_model';
	}
	protected function main_model() {
		if (! isset ( $this->m_model )) {
			$this->load->model ( $this->main_model_name (), 'm_model' );
		}
		return $this->m_model;
	}
	public function index() {
		$data = $this->common_data;
		$model = $this->main_model ();
		$data ['fields_config'] = $model->fields_config ();
		$data ['list'] = $model->get_brands ( $this->inter_id );
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit() {
		$data = $this->common_data;
		$model = $this->main_model ();
		
		$brand_id = $this->input->get ( 'brand' );
		$brand_id = empty ( $brand_id ) ? 0 : $brand_id;
		
		$this->load->model ( 'hotel/Hotel_model' );
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$data ['hotels'] = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
		} else {
			$data ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id, 1 );
		}
		foreach ( $data ['hotels'] as $k => $h ) {
			if (! empty ( $h ['brand_id'] ) && $h ['brand_id'] != $brand_id) {
				unset ( $data ['hotels'] [$k] );
			}
		}
		if (! empty ( $brand_id )) {
			$data ['list'] = $model->get_brand ( $this->inter_id, $brand_id );
			if (empty ( $data ['list'] )) {
				redirect ( site_url ( 'hotel/brand/index' ) );
			}
			$data ['brand_hotels'] = $model->get_brand_hotels ( $this->inter_id, $brand_id );
			$data ['brand_hotels'] = array_column ( $data ['brand_hotels'], NULL, 'hotel_id' );
		} else {
			$data ['list'] = $model->table_fields ();
		}
		$data ['brand_id'] = $brand_id;
		$this->_render_content ( $this->_load_view_file ( 'edit' ), $data, FALSE );
	}
	public function brand_save() {
		$model = $this->main_model ();
		$data = $this->input->post ();
		$this->load->helper ( 'array' );
		$data = jqjson2arr ( json_decode ( $data ['datas'], TRUE ) );
		
		$info = array (
				'status' => 2,
				'message' => 'error' 
		);
		$item = array ();
		$item ['status'] = intval ( $data ['status'] ) == 1 ? 1 : 2;
		
		if (empty ( $data ['name'] )) {
			$info ['message'] = '名称不能为空！';
			$info ['status'] = 2;
			echo json_encode ( $info );
			exit ();
		}
		$item ['name'] = $data ['name'];
		
		if (! empty ( $data ['hotel_ids'] )) {
			$this->load->model ( 'hotel/Hotel_model' );
			$entity_id = $this->session->get_admin_hotels ();
			if (! empty ( $entity_id )) {
				$hotels = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
			} else {
				$hotels = $this->Hotel_model->get_all_hotels ( $this->inter_id, 1 );
			}
			$hotels = array_column ( $hotels, NULL, 'hotel_id' );
			$brand_id=empty($data ['brand_id'])?0:$data ['brand_id'];
			foreach ( $hotels as $k => $h ) {
				if (! empty ( $h ['brand_id'] ) && $h ['brand_id'] != $brand_id) {
					unset ( $hotels [$k] );
				}
			}
			foreach ( $data ['hotel_ids'] as $k => $h ) {
				if (! isset ( $hotels [$h] )) {
					unset ( $data ['hotel_ids'] [$k] );
				}
			}
		}
		$hotel_ids = empty ( $data ['hotel_ids'] ) ? array () : $data ['hotel_ids'];
		if (! empty ( $data ['brand_id'] )) {
			if (empty ( $model->get_brand ( $this->inter_id, $data ['brand_id'] ) )) {
				$info ['message'] = '数据错误！';
				$info ['status'] = 2;
				echo json_encode ( $info );
				exit ();
			}
			if ($model->update_brand ( $this->inter_id, $data ['brand_id'], $item, $hotel_ids )) {
				$info ['status'] = 1;
				$info ['message'] = '保存成功';
			} else {
				$info ['message'] = '保存失败';
			}
		} else {
			if ($model->add_brand ( $this->inter_id , $item, $hotel_ids )) {
				$info ['status'] = 10;
				$info ['message'] = '添加成功';
			} else {
				$info ['message'] = '添加失败';
			}
		}
		echo json_encode ( $info );
		exit ();
	}
}
