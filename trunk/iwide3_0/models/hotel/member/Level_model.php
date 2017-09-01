<?php
class Level_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	function get_upgrade_rule($inter_id, $type = 'roomnight', $format = TRUE) {
		$this->load->model ( 'membervip/common/Public_model', 'mempub' );
		$rule = $this->mempub->get_night_upgrade_rule ( $inter_id );
		$result = array ();
		if (! empty ( $rule ['night_upgrade_rule'] )) {
			$result = array_pop ( $rule ['night_upgrade_rule'] );
			if ($format) {
				$result ['calculate_rules'] = json_decode ( $result ['calculate_rules'], TRUE );
				if (empty ( $result ['calculate_rules'] ['pay_code'] ) || $result ['calculate_rules'] ['pay_code'] === 'all') {
					$result ['calculate_rules'] ['pay_code'] = array ();
				}
				if (empty ( $result ['calculate_rules'] ['price_code'] ) || $result ['calculate_rules'] ['price_code'] === 'all') {
					$result ['calculate_rules'] ['price_code'] = array ();
				}
				if (empty ( $result ['calculate_rules'] ['calculation'] ) || $result ['calculate_rules'] ['calculation'] === 'all') {
					$result ['calculate_rules'] ['calculation'] = 2;
				}
			}
		}
		return $result;
	}
	function check_roomnight_rule($inter_id, $order, $rule = array()) {
		if (empty ( $rule ))
			$rule = $this->get_upgrade_rule ( $inter_id, 'roomnight' );
		if (! empty ( $rule )) {
			$check = $this->level_rule_filter ( $rule, $order );
			if ($check) {
				$this->load->helper ( 'date' );
				return get_room_night ( $order ['sdate'], $order ['edate'] );
			}
		}
		return FALSE;
	}
	function level_rule_filter($rule, $order, $rule_type = 'roomnight') {
		if (! empty ( $rule ['calculate_rules'] ['pay_code'] ) && ! in_array ( $order ['paytype'], $rule ['calculate_rules'] ['pay_code'] )) {
			return FALSE;
		}
		if (! empty ( $rule ['calculate_rules'] ['price_code'] ) && ! in_array ( $order ['price_code'], $rule ['calculate_rules'] ['price_code'] )) {
			return FALSE;
		}
		if ($rule ['calculate_rules'] ['calculation'] == 1) {
			$this->load->model ( 'hotel/Hotel_check_model' );
			$adapter = $this->Hotel_check_model->get_hotel_adapter ( $order ['inter_id'], $order ['hotel_id'], TRUE );
			$checkin_type = $adapter->order_checkin_type ( $order ['inter_id'], $order );
			if ($checkin_type != 'self') {
				return FALSE;
			}
		}
		return TRUE;
	}
	function create_roomnight_queue($inter_id, $order, $order_item) {
		$rule = $this->get_upgrade_rule ( $inter_id, 'roomnight' );
		$result = FALSE;
		if (empty ( $rule )) {
			return FALSE;
		} else {
			$check_data = array (
					'sdate' => $order_item ['startdate'],
					'edate' => $order_item ['enddate'],
					'price_code' => $order_item ['price_code'],
					'paytype' => $order ['paytype'],
					'member_no' => $order ['member_no'],
					'openid' => $order ['openid'] 
			);
			empty ( $order ['web_orderid'] ) or $check_data ['web_orderid'] = $order ['web_orderid'];
			$log_data = $check_data;
			$check_data ['inter_id'] = $order ['inter_id'];
			$check_data ['hotel_id'] = $order ['hotel_id'];
			if ($inter_id == 'a441098524' && $order_item ['startdate'] < 20170301) { // 逸柏20170301前入住不计入
				$result = FALSE;
			} else {
				$room_night = $this->check_roomnight_rule ( $inter_id, $check_data, $rule );
				$log_data ['rn'] = $room_night;
				if ($room_night !== FALSE) {
					unset ( $check_data ['price_code'] );
					unset ( $check_data ['paytype'] );
					unset ( $check_data ['web_orderid'] );
					unset ( $check_data ['inter_id'] );
					unset ( $check_data ['hotel_id'] );
					$check_data ['rn'] = $room_night;
					$this->load->model ( 'hotel/Order_queues_model' );
					$result = $this->Order_queues_model->add_queue ( $inter_id, $order ['hotel_id'], $order ['orderid'], 'roomnight_levelup', $check_data, $order_item ['id'] );
				} else {
					$result = FALSE;
				}
				MYLOG::w ( $inter_id . '|' . $order ['orderid'] . '|' . $order_item ['id'] . '|' . json_encode ( $log_data ) . '|' . json_encode ( $result ), 'hotel/roomnight_queue', '_create_log' );
				return $result;
			}
		}
	}
	// {"start_time":"00-00-00 00:00:00","end_time":"00-00-00 00:00:00","orderid":10010,"suborderid":1001000,"night":1}
	function send_roomnight($inter_id, $orderid, $order, $item_id, $room_night) {
		$this->load->model ( 'hotel/Member_new_model' );
		$order_data ['start_time'] = date ( 'Y-m-d 00:00:00', strtotime ( $order ['sdate'] ) );
		$order_data ['end_time'] = date ( 'Y-m-d 00:00:00', strtotime ( $order ['edate'] ) );
		$order_data ['orderid'] = $orderid;
		$order_data ['suborderid'] = $item_id;
		$order_data ['night'] = $room_night;
		return $this->Member_new_model->save_roomnight ( $inter_id, $order ['openid'], $order ['member_no'], $order_data );
	}
}