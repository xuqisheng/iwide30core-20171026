<?php
class Hotel_price_info_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	
	function get_price_codes($inter_id,$hotel_id){
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'SELECT ps.*,pi.price_name FROM '. $db_read->dbprefix('hotel_price_set') .' ps LEFT JOIN '. $db_read->dbprefix('hotel_price_info') .' pi ON ps.inter_id=pi.inter_id AND pi.price_code=ps.price_code WHERE ps.`status`=1 AND ps.hotel_id=? AND ps.inter_id=?';
		return $db_read->query($sql,array($hotel_id,$inter_id));
	}
	
	function get_hotel_room_infos($inter_id,$hotel_id){
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'SELECT cc.*,hr.`name` FROM (SELECT ps.inter_id,ps.hotel_id,ps.room_id,pi.price_name,pi.price_code FROM iwide_hotel_price_set ps 
	LEFT JOIN iwide_hotel_price_info pi ON ps.inter_id=pi.inter_id AND pi.price_code=ps.price_code 
	WHERE ps.`status`=1 AND ps.hotel_id=? AND ps.inter_id=?) cc LEFT JOIN iwide_hotel_rooms hr ON hr.inter_id=cc.inter_id AND cc.hotel_id=hr.hotel_id';
		$query = $db_read->query($sql,array($hotel_id,$inter_id))->result();
		$temp_arr = array();
		$rooms    = array();
		foreach ($query as $item){
			if(isset($temp_arr[$item->price_code])){
				$temp_arr[$item->price_code]['rooms'][$item->room_id] = $item->name;
			}else{
				$temp_arr[$item->price_code] = array('price_name'=>$item->price_name,'rooms'=>array($item->room_id=>$item->name));
			}
			if(!isset($rooms[$item->room_id])){
				$rooms[$item->room_id] = $item->name;
			}
		}
		return array('codes'=>$temp_arr,'rooms'=>$rooms);
	}
	function get_hotel_room_to_codes($inter_id,$hotel_id,$info_status=NULL,$room_status=NULL){
		$db_read = $this->load->database('iwide_r1',true);
		$rooms=array();
		$sql = 'SELECT cc.*,hr.`name` FROM (SELECT ps.inter_id,ps.hotel_id,ps.room_id,pi.price_name,pi.price_code FROM iwide_hotel_price_set ps 
	LEFT JOIN iwide_hotel_price_info pi ON ps.inter_id=pi.inter_id AND pi.price_code=ps.price_code 
	WHERE ps.`status`=1 AND ps.hotel_id=? AND ps.inter_id=?';
		if (isset($info_status)){
			$sql.=' AND pi.status = '.intval($info_status);
		}
		$sql.=') cc LEFT JOIN iwide_hotel_rooms hr ON hr.inter_id=cc.inter_id AND cc.hotel_id=hr.hotel_id AND hr.room_id=cc.room_id';
		if (isset($room_status)){
			$sql.=' WHERE hr.status = '.intval($room_status);
		}
		$query = $db_read->query($sql,array($hotel_id,$inter_id))->result();
		$temp_arr = array();
		foreach ($query as $item){
			if(isset($temp_arr[$item->room_id])){
				$temp_arr[$item->room_id]['codes'][$item->price_code] = $item->price_name;
			}else{
				
				$temp_arr[$item->room_id] = array('roomname'=>$item->name,'codes'=>array($item->price_code=>$item->price_name));
			}
			if(!isset($rooms[$item->room_id])){
				$rooms[$item->room_id] = $item->name;
			}
		}
		return array('codes'=>$temp_arr,'rooms'=>$rooms);
	}
	//筛选有满足预定天数/连住天数的价格代码
	function filter_codes_by_days($inter_id,$pre_days=0,$min_days=1){
		//获取满足的价格代码
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'SELECT price_code,price_name FROM '. $db_read->dbprefix('hotel_price_info') ." WHERE `status`=1 AND type='common' AND inter_id='".$inter_id."' AND use_condition NOT LIKE '".'%package_only":1%'."'";//过滤套票的
		//提前预定
		if(empty($pre_days)){
			$sql .= " AND ( use_condition NOT like '%pre_d".'":'."%' or use_condition like '%pre_d".'":'."0}' or use_condition like '%pre_d".'":'."0,%' )";
		}else{
			$sql .= " AND ( use_condition like '%pre_d".'":'.$pre_days."}' or use_condition like '%pre_d".'":'.$pre_days.",%' )";
		}
		//连住优惠
		if(empty($min_days)||$min_days<=1){
			$sql .= " AND ( use_condition NOT like '%min_day".'":'."%' or use_condition like '%min_day".'":'."1}' or use_condition like '%min_day".'":'."1,%' )";
		}else{
			$sql .= " AND ( use_condition like '%min_day".'":'.$min_days."}' or use_condition like '%min_day".'":'.$min_days.",%' )";
		}


		$codes = $db_read->query($sql)->result_array ();
		return $codes;
	}

	//筛选有满足价格代码的酒店
	function filter_hotel_by_codes($inter_id,$price_code){
		//获取满足的价格代码
		$db_read = $this->load->database('iwide_r1',true);
		if(empty($price_code)){
			return array();
		}
		$code = implode($price_code, ',');

		$sql = "SELECT h.hotel_id,h.name FROM ". $db_read->dbprefix('hotel_price_set') .' ps LEFT JOIN '. $db_read->dbprefix('hotels') ." h ON ps.inter_id=h.inter_id AND ps.hotel_id=h.hotel_id WHERE ps.`status`=1 AND ps.inter_id='$inter_id' AND price_code IN ($code) group by hotel_id";
		$result = $db_read->query($sql)->result_array ();

		return $result;
	}
}