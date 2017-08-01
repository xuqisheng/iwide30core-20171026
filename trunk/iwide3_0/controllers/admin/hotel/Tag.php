<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Tag extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '酒店标签';
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
		return 'hotel/Tag_model';
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
		$data ['fields_config'] = $model->type_fields_config ();
		$data ['list'] = $model->get_tag_types ( $this->inter_id );
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit() {
		$data = $this->common_data;
		$model = $this->main_model ();
		$type_id = $this->input->get ( 'type' );
		if (! empty ( $type_id )) {
			$data ['list'] = $model->get_tag_type ( $this->inter_id, $type_id );
			if (empty ( $data ['list'] )) {
				redirect ( site_url ( 'hotel/tag/index' ) );
			}
		} else {
			$data ['list'] = $model->type_table_fields ();
		}
		$this->_render_content ( $this->_load_view_file ( 'edit' ), $data, FALSE );
	}
	public function edit_post() {
		$model = $this->main_model ();
		$type_id = $this->input->post ( 'type_id' );
		$name = $this->input->post ( 'name' );
		$in_search = $this->input->post ( 'in_search' );
		$in_city = $this->input->post ( 'in_city' );
		$status = $this->input->post ( 'status' );
		
		if (! empty ( $name )) {
			$data ['name'] = htmlspecialchars ( $name );
			$data ['type_id'] = $type_id;
			$data ['status'] = intval ( $status ) == 1 ? 1 : 2;
			$data ['in_search'] = intval ( $in_search );
			$data ['in_city'] = intval ( $in_city ) == 1 ? 1 : 0;
			$data ['sort'] = intval ( $this->input->post ( 'sort' ) );
			if (! empty ( $type_id )) {
				$model->save_type ( $this->inter_id, $data );
			} else {
				$model->save_type ( $this->inter_id, $data, 'add' );
			}
		}
		redirect ( site_url ( 'hotel/tag/index' ) );
	}
	public function items() {
		$data = $this->common_data;
		$model = $this->main_model ();
		$type_id = $this->input->get ( 'type' );
		if (! empty ( $type_id )) {
			$check = $model->get_tag_type ( $this->inter_id, $type_id );
			if (empty ( $check )) {
				redirect ( site_url ( 'hotel/tag/index' ) );
			}
			$data ['list'] = $model->get_tag_items ( $this->inter_id, $type_id );
			$data ['fields_config'] = $model->item_fields_config ();
			$data ['type'] = $check;
		} else {
			redirect ( site_url ( 'hotel/tag/index' ) );
		}
		$this->_render_content ( $this->_load_view_file ( 'items' ), $data, FALSE );
	}
	public function item_edit() {
		$data = $this->common_data;
		$model = $this->main_model ();
		
		$type_id = $this->input->get ( 'type' );
		$item_id = $this->input->get ( 'item' );
		
		if (empty ( $type_id ) || empty ( $data ['type'] = $model->get_tag_type ( $this->inter_id, $type_id ) )) {
			redirect ( site_url ( 'hotel/tag/index' ) );
		}
		
		$this->load->model ( 'hotel/Hotel_model' );
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$data ['hotels'] = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
		} else {
			$data ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id, 1 );
		}
		$cities = $this->Hotel_model->get_hotel_citys ( $this->inter_id );
		$data ['citys'] = $cities ['citys'];
		$data ['type_id'] = $type_id;
		if (! empty ( $item_id )) {
			$data ['list'] = $model->get_tag_item ( $this->inter_id, $type_id, $item_id );
			if (empty ( $data ['list'] )) {
				redirect ( site_url ( 'hotel/tag/index' ) );
			}
			$data ['tag_hotels'] = $model->get_tag_hotels ( $this->inter_id, $item_id, 1 );
			$data ['tag_hotels'] = array_column ( $data ['tag_hotels'], NULL, 'hotel_id' );
		} else {
			$data ['list'] = $model->item_table_fields ();
		}
		$this->_render_content ( $this->_load_view_file ( 'item_edit' ), $data, FALSE );
	}
	public function item_save() {
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
		$item ['sort'] = intval ( $data ['sort'] ) ;
		
		if (empty ( $data ['name'] )) {
			$info ['message'] = '名称不能为空！';
			$info ['status'] = 2;
			echo json_encode ( $info );
			exit ();
		}
		$item ['name'] = $data ['name'];
		if (empty ( $data ['type_id'] ) || empty ( $check = $model->get_tag_type ( $this->inter_id, $data ['type_id'] ) )) {
			$info ['message'] = '数据错误！';
			$info ['status'] = 2;
			echo json_encode ( $info );
			exit ();
		}
		if (empty ( $data ['city'] ) && $check ['in_city'] == 1) {
			$info ['message'] = $check ['name'] . ' 的城市为必填！';
			$info ['status'] = 2;
			echo json_encode ( $info );
			exit ();
		}
		$item ['city'] = empty ( $data ['city'] )?'':$data ['city'];
		
		if (! empty ( $data ['hotel_ids'] )) {
			$this->load->model ( 'hotel/Hotel_model' );
			$entity_id = $this->session->get_admin_hotels ();
			if (! empty ( $entity_id )) {
				$hotels = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
			} else {
				$hotels = $this->Hotel_model->get_all_hotels ( $this->inter_id, 1 );
			}
			$hotels = array_column ( $hotels, NULL, 'hotel_id' );
			foreach ( $data ['hotel_ids'] as $k => $h ) {
				if (! isset ( $hotels [$h] )) {
					unset ( $data ['hotel_ids'] [$k] );
				}
			}
		}
		$hotel_ids = empty ( $data ['hotel_ids'] ) ? array () : $data ['hotel_ids'];
		if (! empty ( $data ['item_id'] )) {
			if (empty ( $model->get_tag_item ( $this->inter_id, $data ['type_id'], $data ['item_id'] ) )) {
				$info ['message'] = '数据错误！';
				$info ['status'] = 2;
				echo json_encode ( $info );
				exit ();
			}
			if ($model->update_tag_item ( $this->inter_id, $data ['type_id'], $data ['item_id'], $item, $hotel_ids )) {
				$info ['status'] = 1;
				$info ['message'] = '保存成功';
			} else {
				$info ['message'] = '保存失败';
			}
		} else {
			if ($model->add_tag_item ( $this->inter_id, $data ['type_id'], $item, $hotel_ids )) {
				$info ['status'] = 10;
				$info ['message'] = '添加成功';
			} else {
				$info ['message'] = '添加失败';
			}
		}
		echo json_encode ( $info );
		exit ();
	}
	public function quick_save() {
		$model = $this->main_model ();
		$data = $this->input->post ();
		
		$info = array (
				'status' => 2,
				'message' => 'error' 
		);
		
		if (empty ( $data ['type_id'] ) || empty ( $data ['data'] ) || empty ( $check = $model->get_tag_type ( $this->inter_id, $data ['type_id'] ) )) {
			$info ['message'] = '数据错误！';
			$info ['status'] = 2;
			echo json_encode ( $info );
			exit ();
		}
		if (empty($items=json_decode($data['data'],TRUE))){
			$info ['message'] = '数据错误！';
			$info ['status'] = 2;
			echo json_encode ( $info );
			exit ();
		}
		foreach ($items as $item_id=>$i){
			if($model->update_tag_item ( $this->inter_id, $data ['type_id'], $item_id, array('sort'=>intval($i['sort'])) )){
				$info ['status'] = 1;
			}
		}
		
		echo json_encode ( $info );
		exit ();
	}
}
