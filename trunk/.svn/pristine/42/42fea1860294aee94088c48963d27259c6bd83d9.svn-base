<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Market_interface extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
	}
	public function check_order_status() {
		$orderid = $this->input->get ( 'orderid' );
		$inter_id = $this->input->get ( 'id' );
		$openid = $this->input->get ( 'openid' );
		$status = $this->input->get ( 'status' );
		$check_type = $this->input->get ( 'check_type' );
		$check_type = is_null ( $check_type ) ? 'part' : $check_type;
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( addslashes ( $inter_id ), array (
				'only_openid' => addslashes ( $openid ),
				'orderid' => addslashes ( $orderid ),
				'idetail' => array (
						'r' 
				) 
		) );
		$info = array (
				's' => 0,
				'msg' => '',
				'data' => array () 
		);
		if (! empty ( $order )) {
			$order = $order [0];
			$s = '';
			$info ['s'] = 1;
			$info ['msg'] = '查询成功';
			foreach ( $order ['order_details'] as $od ) {
				$s .= ',' . $od ['id'] . ':' . $od ['istatus'] . ':' . ceil ( (strtotime ( $od ['enddate'] ) - strtotime ( $od ['startdate'] )) / 86400 ) . ':' . $od ['startdate'] . ':' . $od ['enddate'];
			}
			if (! empty ( $s ))
				$s = substr ( $s, 1 );
			$info ['data'] ['statuses'] = $s;
			if (! empty ( $order ['web_orderid'] )) {
				$info ['data'] ['extra'] ['web_orderid'] = $order ['web_orderid'];
			}
			$this->load->model ( 'common/Pms_model' );
			$web_hotel = $this->Pms_model->get_hotel_pms_set ( $inter_id, $order ['hotel_id'] );
			$this->load->model ( 'hotel/Hotel_model' );
			$hotel = $this->Hotel_model->get_hotel_detail ( $inter_id, $order ['hotel_id'] );
			$info ['data'] ['hotel'] ['name'] = $hotel ['name'];
			$info ['data'] ['hotel'] ['pms_id'] = isset ( $web_hotel ['hotel_web_id'] ) ? $web_hotel ['hotel_web_id'] : NULL;
			
			if (! is_null ( $status ) && $status != - 1) {
				$info ['data'] ['check_result'] = $check_type == 'all' ? 1 : 0;
				foreach ( $order ['order_details'] as $od ) {
					if ($od ['istatus'] != intval ( $status ) && $check_type == 'all') {
						$info ['data'] ['check_result'] = 0;
						break;
					}
					if ($od ['istatus'] == intval ( $status ) && $check_type == 'part') {
						$info ['data'] ['check_result'] = 1;
						break;
					}
				}
			}
		} else {
			$info ['msg'] = '查询失败';
		}
		echo json_encode ( $info, JSON_UNESCAPED_UNICODE );
	}
}