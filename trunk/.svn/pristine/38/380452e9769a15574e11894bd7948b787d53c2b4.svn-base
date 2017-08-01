<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Tmmsg extends MY_Admin_Roomservice {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '模板消息';
	protected $label_action = '';
	const ENUM_ORDER_TMG_TYPE = 'TIPS_ORDERS_TMPMSG_TYPE';
	const ENUM_TMG_MSG_STATUS = 'TMG_MSG_STATUS';
	const ENUM_TMG_MSG_CONT_TYPE = 'HOTEL_ORDER_TMPMSG_CONTENT_TYPE';
	const ENUM_OREDER_URL = 'TIPS_ORDER_URL_DES';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'plugins/Template_msg_model';
	}
	public function index() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$list = $model->get_temp_msg_list ( $this->inter_id, self::ENUM_ORDER_TMG_TYPE );
		$this->load->model ( 'common/Enum_model' );
		$enum_des = $this->Enum_model->get_enum_des ( array (
				self::ENUM_TMG_MSG_STATUS,
				self::ENUM_OREDER_URL 
		) );
		foreach ( $list as $k => $l ) {
			$list [$k] ['status'] = $enum_des [self::ENUM_TMG_MSG_STATUS] [$l ['status']];
			empty($l ['url_type'])?$list [$k] ['url_type']=$l['url']:$list [$k] ['url_type'] = $enum_des [self::ENUM_OREDER_URL] [$l ['url_type']];
		}
		$types = $model->get_temp_msg_types ( 'hotel_order' );
		if(count($list)>=count($types))
			$data ['no_add'] = 1;
		$data ['list'] = $list;
		$data ['fields_config'] = $model->grid_fields ();
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit() {
		$data = $this->common_data;
		$this->label_action = '模板消息设置';
		$this->_init_breadcrumb ( $this->label_action );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$data ['tid'] = $this->input->get ( 'tid' );
		$default = $this->input->get ( 'def' );
		$this->load->model ( 'common/Enum_model' );
		$enum_des = $this->Enum_model->get_enum_des ( array (
				self::ENUM_ORDER_TMG_TYPE,
				self::ENUM_TMG_MSG_STATUS,
				self::ENUM_TMG_MSG_CONT_TYPE,
				self::ENUM_OREDER_URL 
		) );
// 		$data ['order_params'] = $model->order_find_des ();
		$data ['order_params'] = array('{PAY_MONEY}'=>'打赏金额','{ORDER_SN}'=>'订单号');
		
		$data ['status_des'] = $enum_des [self::ENUM_TMG_MSG_STATUS];
		$data ['content_des'] = $enum_des [self::ENUM_TMG_MSG_CONT_TYPE];
		$data ['url_type'] = $enum_des [self::ENUM_OREDER_URL];
		if (! empty ( $data ['tid'] )) {
			$list = $model->get_template ( $this->inter_id, $data ['tid'] );
			if (! empty ( $list )) {
				$list ['desc'] = $enum_des [self::ENUM_ORDER_TMG_TYPE] [$list ['temp_type']];
				$data ['list'] = $list;
			} else if ($default == 1) {
				$list = $model->get_template ( 'defaultmsg', $data ['tid'] );
				$list ['desc'] = $enum_des [self::ENUM_ORDER_TMG_TYPE] [$list ['temp_type']];
				$data ['list'] = $list;
				$data ['is_default'] = 1;
			} else {
				redirect ( site_url ( 'tips/tmmsg/index' ) );
			}
		} else {
			$msg_list = $model->get_temp_msg_list ( $this->inter_id, self::ENUM_ORDER_TMG_TYPE );
			$types = $model->get_temp_msg_types ( 'tips' );
			$msg_keys=array_keys($msg_list);
			foreach($types as $code=>$des){
				if(in_array($code, $msg_keys)){
					unset($types[$code]);
				}
			}
			if(empty($types)){
				redirect ( site_url ( 'tips/tmmsg/index' ) );
				exit;
			}
			$data['types']=$types;
			$data ['list'] = $model->table_fields ();
		}
		$this->_render_content ( $this->_load_view_file ( 'edit' ), $data, FALSE );
	}
	public function edit_post() {
		$tid = $this->input->post ( 'tid' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$data ['temp_id'] = $this->input->post ( 'temp_id' );
		$data ['top_color'] = $this->input->post ( 'top_color' );
		$data ['text_color'] = $this->input->post ( 'text_color' );
		$data ['url_type'] = $this->input->post ( 'url_type' );
		$data ['url'] = $this->input->post ( 'url' );
		$data ['status'] = intval ( $this->input->post ( 'status' ) );
		$data ['content'] = json_encode ( json_decode ( $this->input->post ( 'content_data' ) ) );
		$check = $model->get_template ( $this->inter_id, $tid );
		$flag = 0;
		if ($check) {
			$flag = $model->edit_template_msg ( array (
					'inter_id' => $this->inter_id,
					'temp_type' => $check ['temp_type'] 
			), $data );
		} else {
			$data ['inter_id'] = $this->inter_id;
			$data ['temp_type'] = $tid;
			$flag = $model->add_template_msg ( $data );
		}
		redirect ( site_url ( 'tips/tmmsg/index' ) );
	}
}
