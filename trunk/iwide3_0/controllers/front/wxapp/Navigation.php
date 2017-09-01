<?php

class Navigation extends MY_Controller {
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL );
		ini_set ( 'display_errors', 1 );
		set_time_limit ( 0 );
		$this->output->enable_profiler ( FALSE );

		$pro_id = $this->input->get('pro_id');
		$pro_id = intval($pro_id);
		MYLOG::common_tracker("wxapp_share_page",$pro_id);
	}
	function index(){
 		$this->load->view('wxapp/index');
	}

	function search() {
		$type = $this->input->get('type');
		$keyword = $this->input->get('keyword');
		$type = trim($type);
		$keyword = trim($keyword);
		$this->load->model('wx/Programs_model');
		$condit = array(
			'type'=>$type,
			'keywords'=>$keyword
		);
		$list = $this->Programs_model->get_list($condit);
		$return = array(
			'data' => $list
		);
		if(!empty($list)){
			$return['status'] = 1;
			$return['msg'] = '成功';
		}else{
			$return['status'] = 2;
			$return['msg'] = '数据为空';
		}
		echo json_encode($return);
	}
	
	function detail(){
		$pro_id = $this->input->get('pro_id');
		$pro_id = intval($pro_id);
		if(empty($pro_id) || $pro_id<1){
			echo json_encode(array('status'=>2,'msg'=>'失败'));
		}
		$this->load->model('wx/Programs_model');

		$row = $this->Programs_model->get_row($pro_id);
		$row['detail_img'] = json_decode(stripslashes($row['detail_img']),true);
		$return = array(
			'data' => $row
		);
		if(!empty($row)){
			$return['status'] = 1;
			$return['msg'] = '成功';
		}else{
			$return['status'] = 2;
			$return['msg'] = '数据为空';
		}
		echo json_encode($return);
	}
	function ajax_hit(){
		$pro_id = $this->input->post('pro_id');
		$pro_id = intval($pro_id);
		if(empty($pro_id) || $pro_id<1){
			echo json_encode(array('status'=>2,'msg'=>'失败'));
		}
		$click_array = $this->session->userdata ( 'program_hit' );
		if(is_array($click_array) && in_array($pro_id,$click_array)){
			echo json_encode(array('status'=>1,'msg'=>'成功','click_array'=>$click_array));exit;
		}
		$this->load->model('wx/Programs_model');
		$re = $this->Programs_model->update_hit($pro_id);
		if($re){
			if(is_array($click_array)){
				array_push($click_array,$pro_id);
			}else{
				$click_array = array($pro_id);
			}
			$this->session->set_userdata ( array (
				'program_hit' => $click_array
			) );
			echo json_encode(array('status'=>1,'msg'=>'成功'));
		}else{
			echo json_encode(array('status'=>2,'msg'=>'失败'));
		}
	}
}

	