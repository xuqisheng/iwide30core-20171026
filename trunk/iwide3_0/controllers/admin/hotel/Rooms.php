<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Rooms extends MY_Admin {
	protected $label_module = NAV_HOTELS;
	protected $label_controller = '房型列表';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
	}
	protected function main_model_name() {
		return 'hotel/rooms_model';
	}
	public function index() {
		$_POST ['inter_id'] = $this->session->get_admin_inter_id ();
		$_POST ['status'] = array (
				1,
				2 
		);
		$entity_id = $this->session->get_admin_hotels ();
		$hotel_ids = explode ( ',', $entity_id );
		$hotel_id = $this->input->get ( 'h' );
		
		$this->load->model ( 'hotel/hotel_model' );
		$data = array (
				'hotel_id' => $hotel_id 
		);
		if (! empty ( $entity_id )) {
			$data ['hotels'] = $this->hotel_model->get_hotel_by_ids ( $_POST ['inter_id'], $entity_id ,1);
			if (! empty ( $hotel_id ) && in_array ( $hotel_id, $hotel_ids )) {
				$_POST ['hotel_id'] = $hotel_id;
			} else {
				$_POST ['hotel_id'] = $hotel_ids;
			}
		} else {
			$data ['hotels'] = $this->hotel_model->get_all_hotels ( $_POST ['inter_id'] ,1);
			if (! empty ( $hotel_id )) {
				$_POST ['hotel_id'] = $hotel_id;
			}
		}
		
		$this->_grid ( $_POST, $data );
	}
	public function grid() {
		$inter_id = $this->session->get_admin_inter_id ();
		if ($inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($inter_id)
			$filter = array (
					'inter_id' => $inter_id 
			);
		else
			$filter = array (
					'inter_id' => 'deny' 
			);
			// print_r($filter);die;
			
		/* 兼容grid变为ajax加载加这一段 */
		if (is_ajax_request ())
			// 处理ajax请求，参数规格不一样
			$get_filter = $this->input->post ();
		else
			$get_filter = $this->input->get ( 'filter' );
		
		if (! $get_filter)
			$get_filter = $this->input->get ( 'filter' );
		
		if (is_array ( $get_filter ))
			$filter = $get_filter + $filter;
			/* 兼容grid变为ajax加载加这一段 */
		
		$this->_grid ( $filter );
	}
	public function edit() {
		$this->label_action = '房型管理';
		$this->_init_breadcrumb ( $this->label_action );
		
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		
		$id = intval ( $this->input->get ( 'ids' ) );
		$hotel_id = intval ( $this->input->get ( 'h' ) );
		$hotel_name = '';
		if ($id) {
			// for edit page.
			$model = $model->load ( $id );
			if (! $model){
				redirect(site_url('hotel/rooms/index'));
			}
			$fields_config = $model->get_field_config ( 'form' );
			$hotel_name=isset($fields_config['hotel_id']['select'][$model->m_get('hotel_id')])?$fields_config['hotel_id']['select'][$model->m_get('hotel_id')]:'';
			unset($fields_config['hotel_id']);
			unset($fields_config['inter_id']);
			// $sql= "select a.* from {$this->db->dbprefix}shp_goods_attr as a left join {$this->db->dbprefix}shp_attrbutes as b on a.attr_id=b.attr_id where a.gs_id=". $id;
			// $detail_field= $this->db->query($sql)->result_array();
			$detail_field = array ();
			if (count ( $detail_field ) > 0) {
				$detail_field = $detail_field [0] ['attr_value'];
			} else {
				$detail_field = '';
			}
		} else {
			// for add page.
			$model = $model->load ( $id );
			if (! $model)
				$model = $this->_load_model ();
			$fields_config = $model->get_field_config ( 'form' );
			$detail_field = '';
		}
		$this->db->where ( array (
				'inter_id' => $this->session->get_admin_inter_id (),
				'type' => 'hotel_room_service',
				'room_id' => $id,
				'status'=>1
		) );
		$room_ser = $this->db->get ( 'hotel_images' )->result_array ();
		$room_ser = array_column ( $room_ser, 'image_url' );
		$this->db->where ( array (
				'inter_id' => 'defaultimg',
				'type' => 'hotel_room_service' 
		) );
		$services = $this->db->get ( 'hotel_images' )->result ();
		// 获取相册数组
		// $gallery= $model->get_gallery();
		
		$view_params = array (
				'model' => $model,
				'fields_config' => $fields_config,
				'check_data' => FALSE,
				'detail_field' => $detail_field,
				'services' => $services,
				'room_ser' => $room_ser ,
		        'hotel_id' => $hotel_id,
		        'hotel_name' => $hotel_name
		);
		
		$html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		// echo $html;die;
		echo $html;
	}
	public function edit_post() {
		$this->label_action = '信息维护';
		$this->_init_breadcrumb ( $this->label_action );
		
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$pk = $model->table_primary_key ();
		
		$this->load->library ( 'form_validation' );
		$post = $this->input->post ();
		
		$labels = $model->attribute_labels ();
		$base_rules = array (
				'name' => array (
						'field' => 'name',
						'label' => $labels ['name'],
						'rules' => 'trim|required' 
				),
				'price' => array (
						'field' => 'price',
						'label' => $labels ['price'],
						'rules' => 'trim' 
				),
				'oprice' => array (
						'field' => 'oprice',
						'label' => $labels ['oprice'],
						'rules' => 'trim' 
				),
				'description' => array (
						'field' => 'description',
						'label' => $labels ['description'],
						'rules' => 'trim' 
				),
				'nums' => array (
						'field' => 'nums',
						'label' => $labels ['nums'],
						'rules' => 'trim|required' 
				),
				'bed_num' => array (
						'field' => 'bed_num',
						'label' => $labels ['bed_num'],
						'rules' => 'trim' 
				),
				'area' => array (
						'field' => 'area',
						'label' => $labels ['area'],
						'rules' => 'trim|required' 
				),
				'status' => array (
						'field' => 'status',
						'label' => $labels ['status'],
						'rules' => 'trim|required' 
				),
				'sort' => array (
						'field' => 'sort',
						'label' => $labels ['sort'],
						'rules' => 'trim|required' 
				),
				'room_id' => array (
						'field' => 'room_id',
						'label' => $labels ['room_id'],
						'rules' => 'trim' 
				),
				'hotel_id' => array (
						'field' => 'hotel_id',
						'label' => $labels ['hotel_id'],
						'rules' => 'trim|required' 
				),
				'inter_id' => array (
						'field' => 'inter_id',
						'label' => $labels ['inter_id'],
						'rules' => 'trim|required' 
				) 
		);
		
		// 检测并上传文件。
		$post = $this->_do_upload ( $post, 'gs_logo' );
		
		$adminid = $this->session->get_admin_id ();
		
		if (empty ( $post [$pk] )) {
			// add data.
			$this->form_validation->set_rules ( $base_rules );
			
			if ($this->form_validation->run () != FALSE) {
				$post ['add_date'] = date ( 'Y-m-d H:i:s' );
				$post ['add_user'] = $adminid;
				
				$result = $model->m_sets ( $post )->m_save ( $post );
				$model->save_services ( $result );
				$message = ($result) ? $this->session->put_success_msg ( '已新增数据！' ) : $this->session->put_notice_msg ( '此次数据保存失败！' );
				// $this->_log($model);
				$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ).'?h='.$post['hotel_id'] );
			} else
				$model = $this->_load_model ();
		} else {
			unset($base_rules['hotel_id']);
			unset($base_rules['inter_id']);
			unset($post['hotel_id']);
			unset($post['inter_id']);
			$this->form_validation->set_rules ( $base_rules );
			if ($this->form_validation->run () != FALSE) {
				$post ['last_update_time'] = date ( 'Y-m-d H:i:s' );
				$post ['last_update_user'] = $adminid;
				$room=$this->db->get_where('hotel_rooms',array('room_id'=> $post [$pk],'inter_id'=>$this->session->get_admin_inter_id ()))->row_array();
				$_POST['hotel_id']=$room['hotel_id'];
				$result = $model->load ( $post [$pk] )->m_sets ( $post )->m_save ( $post );
				$model->save_services ();
				$message = ($result) ? $this->session->put_success_msg ( '已保存数据！' ) : $this->session->put_notice_msg ( '此次数据修改失败！' );
				$this->_log ( $model );
				$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ).'?h='.$_POST['hotel_id'] );
			} else
				$model = $model->load ( $post [$pk] );
		}
		$this->db->where ( array (
				'inter_id' => $this->session->get_admin_inter_id (),
				'type' => 'hotel_room_service',
				'room_id' => $post [$pk] 
		) );
		$room_ser = $this->db->get ( 'hotel_images' )->result_array ();
		$room_ser = array_column ( $room_ser, 'image_url' );
		$this->db->where ( array (
				'inter_id' => 'defaultimg',
				'type' => 'hotel_room_service' 
		) );
		$services = $this->db->get ( 'hotel_images' )->result ();
		// 验证失败的情况
		$validat_obj = _get_validation_object ();
		$message = $validat_obj->error_html ();
		// 页面没有发生跳转时用寄存器存储消息
		$this->session->put_error_msg ( $message, 'register' );
		
		$fields_config = $model->get_field_config ( 'form' );
		$view_params = array (
				'model' => $model,
				'fields_config' => $fields_config,
				'check_data' => TRUE,
				'services' => $services,
				'room_ser' => $room_ser  
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		echo $html;
	}
	public function edit_focus() {
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$pk = $model->table_primary_key ();
		$post = $this->input->post ();
		
		if ($post ['del_gallery']) {
			$model->delete_gallery ( $post ['del_gallery'], $post [$pk] );
		}
		// 检测并上传新的文件。
		$post = $this->_do_upload ( $post, 'gallery' );
		if (isset ( $post ['gallery'] )) {
			$data = array (
					'gry_url' => $post ['gallery'],
					'gry_desc' => $post ['gry_desc'],
					'gs_id' => $post ['gs_id'] 
			);
			$model->plus_gallery ( $data );
		}
		$this->session->put_success_msg ( '成功保存产品相册，请继续编辑产品信息' );
		$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/edit', array (
				'ids' => $post [$pk] 
		) ) );
	}
}
