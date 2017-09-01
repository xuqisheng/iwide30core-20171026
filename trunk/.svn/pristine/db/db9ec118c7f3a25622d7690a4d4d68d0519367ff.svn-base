<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Thematic extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '专题活动';
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
		return 'hotel/Hotel_thematic_model';
	}
	public function index() {
		$data = $this->common_data;
		$this->label_action = '活动管理';
		$model = $this->_load_model ( $this->main_model_name () );
		$this->_init_breadcrumb ( $this->label_action );
		$data ['list'] = $model->get_list ( array('inter_id'=>$this->inter_id ));
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function edit() {
		$data = $this->common_data;
		$this->label_action = '新增/编辑活动';
		$this->_init_breadcrumb ( $this->label_action );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$data ['tid'] = $this->input->get ( 'tid' );

		if (! empty ( $data ['tid'] )) {
			$row = $model->get_row ( $this->inter_id, $data ['tid']);
			if (! empty ( $row )) {
				$row['hotelids'] = json_decode($row['hotelids'],true);
				$row['price_codes'] = json_decode($row['price_codes'],true);
				$data ['row'] = $row;
			} else {
				redirect ( site_url ( 'hotel/thematic/index' ) );
			}
		}
		$this->_render_content ( $this->_load_view_file ( 'edit' ), $data, FALSE );
	}
	public function edit_post() {
		$tid = $this->input->post ( 'tid' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$data ['status'] = 1;
		$data ['act_name'] = $this->input->post ( 'act_name' );
		$data ['act_intro'] = $this->input->post ( 'act_intro' );
		$data ['start_time'] = $this->input->post ( 'start_time' );
		$data ['end_time'] = $this->input->post ( 'end_time' );
		$data ['intro_img'] = $this->input->post ( 'intro_img' );
		$data ['pre_days'] = intval ( $this->input->post ( 'pre_days' ) );
		$data ['min_days'] = intval ( $this->input->post ( 'min_days' ) );
		$data ['sort'] = intval ( $this->input->post ( 'sort' ) );
		$data['hotelids'] = json_encode($this->input->post ( 'hotel_arr' ));
		$data['price_codes'] = json_encode($this->input->post ( 'code_arr' ));

		if ($tid) {
			$re = $model->update_data ( $this->inter_id,$tid, $data );
		} else {
			$data ['inter_id'] = $this->inter_id;
			$data ['create_time'] = date('Y-m-d H:i:s');
			$re = $model->create_tp ( $data );
		}
		// if($re){
		// 	echo json_encode(array('code'=>0,'msg'=>'成功'));
		// }else{
		// 	echo json_encode(array('code'=>1,'msg'=>'失败'));
		// }
		redirect ( site_url ( 'hotel/thematic/index' ) );
	}
	/*
	public function delete() {
		$tid = $this->input->get ( 'tid' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$data ['status'] = 3;
		$model->edit_template_msg ( array (
					'inter_id' => $this->inter_id,
					'temp_type' => $tid 
			), $data);
		redirect ( site_url ( 'hotel/tmmsg/index' ) );
	}*/
	public function ajax_pricecode_filter(){
		$pre_days = $this->input->get ( 'pre_days' );
		$min_days = $this->input->get ( 'min_days' );
		$this->load->model ( 'hotel/Hotel_price_info_model' );
		$codes = $this->Hotel_price_info_model->filter_codes_by_days($this->inter_id,$pre_days,$min_days);
		echo json_encode(array('code'=>0,'data'=>$codes));
	}
	public function ajax_hotel_filter(){
		$codes = $this->input->get ( 'codes' );
		$this->load->model ( 'hotel/Hotel_price_info_model' );
		$hotels = $this->Hotel_price_info_model->filter_hotel_by_codes($this->inter_id,$codes);
		echo json_encode(array('code'=>0,'data'=>$hotels));
	}
}
