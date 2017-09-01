<?php
class Order_info_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	function get_point_order($inter_id, $params) {
		$db_read = $this->load->database ( 'iwide_r1', true );
		$order_condition='';
		empty($params['min_startdate']) || $order_condition.=' o.startdate >= '.$params['min_startdate'].' and ';
		empty($params['max_startdate']) || $order_condition.=' o.startdate <= '.$params['max_startdate'].' and ';
		empty($params['min_enddate']) || $order_condition.=' o.enddate >= '.$params['min_enddate'].' and ';
		empty($params['max_enddate']) || $order_condition.=' o.enddate <= '.$params['max_enddate'].' and ';
		empty($params['min_booktime']) || $order_condition.=' o.order_time >= '.$params['min_booktime'].' and ';
		empty($params['max_booktime']) || $order_condition.=' o.enddate <= '.$params['max_booktime'].' and ';
		$sql = "SELECT o.tel,o.name,o.orderid,o.order_time,o.member_no,o.hotel_id,o.roomnums,o.paytype,o.paid,o.status,
					 i.id sub_id,i.webs_orderid,i.real_allprice,i.iprice,i.allprice,i.startdate istart,i.enddate iend,i.istatus,i.roomname,
					 a.web_orderid,a.coupon_used,a.coupon_favour,a.point_used_amount
				FROM `iwide_hotel_orders` o 
				JOIN `iwide_hotel_order_additions` a 
				JOIN `iwide_hotel_order_items` i 
				ON  o.inter_id = a.inter_id 
					and o.orderid = a.orderid 
					and o.inter_id = i.inter_id 
					and o.orderid = i.orderid 
				WHERE 
					o.inter_id = '$inter_id' 
					and a.inter_id = '$inter_id' 
					and i.inter_id = '$inter_id'  
					and a.point_used_amount > 0 
					and o.status not in (10) 
					and a.web_orderid != '' 
					and $order_condition 1 ";
		$point_orders = $db_read->query ( $sql )->result_array ();
		return $point_orders;
	}
}