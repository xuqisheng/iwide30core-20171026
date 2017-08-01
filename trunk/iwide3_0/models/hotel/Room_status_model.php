<?php
class Room_status_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_HOTEL_TEMP_STOCK = 'hotel_room_temp_stock';
	// 2015年12月4日 16:50:35 line 12 'nums'=>$item['rooms']->'nums'=>$item['nums'] line 9 date(date)>? AND date(date)<? -> date(date)>=? AND date(date)<=?
	function get_room_price($inter_id, $hotel_id, $room_id, $price_code, $begin_date, $end_date) {
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'SELECT * FROM ' . $this->db->dbprefix ( 'hotel_room_state' ) . ' WHERE inter_id=? AND hotel_id=? AND room_id=? AND price_code=? AND `date`>=? AND `date`<=?';
		$query = $db_read->query ( $sql, array (
				$inter_id,
				$hotel_id,
				$room_id,
				$price_code,
				$begin_date,
				$end_date 
		) )->result_array ();
		$res = array ();
		foreach ( $query as $item ) {
			$res [$item ['date']] = array (
					'price' => $item ['price'],
					'nums' => $item ['nums'] 
			);
		}
		return $res;
	}
	function get_room_days_price($inter_id, $hotel_id, $room_id, $price_code, $days,$channel='Weixin'){
		if (!$days){
			return FALSE;
		}
		$db = $this->load->database('iwide_r1',true);
		$db->where(array(
				'inter_id'=>$inter_id,
				'hotel_id'=>$hotel_id,
				'room_id'=>$room_id,
				'price_code'=>$price_code,
				'channel_code'=>$channel
		));
		$db->where_in('date',$days);
		return $db->get('hotel_room_state')->result_array();
	}
	function save_room_price($inter_id, $hotel_id, $room_id, $price_code, $price, $nums, $day_arr,$type='common') {
		$add_log_datas=array();
		$add_log_idents=array();
		$edit_log_datas=array();
		$edit_log_idents=array();
		$states=$this->get_room_days_price($inter_id, $hotel_id, $room_id, $price_code, $day_arr);
		$states=array_column($states, NULL,'date');
		$this->db->trans_begin ();
		foreach ( $day_arr as $day ) {
		    $map=array(
		            'inter_id' => $inter_id,
		            'room_id' => $room_id,
		            'hotel_id' => $hotel_id,
		            'date' => $day,
		            'price_code' => $price_code
		    );
			$data=array (
			        'price'=>$price,
			        'type'=>$type,
					'edittime' => time () 
			) ;
// 			$this->db->replace ( 'hotel_room_state', $data);
			$ident='room_state'.'#'.$inter_id.'_'.$hotel_id.'_'.$room_id.'_'.$price_code;
			$log_idents[]=$ident;
			$updata_diff=array();
			if (isset($states[$day])){
			    $nums=='-' or $data['nums']=$nums;
			    $this->db->where($map);
			    $this->db->update('hotel_room_state',$data);
			    $data=array_merge($data,$map);
				$updata_diff=array();
				foreach ($states[$day] as $k=>$c){
					if ((isset($data[$k])&&$states[$day][$k]!=$data[$k])||$k=='date'){
						$updata_diff[$k]=array('old'=>$c,'new'=>isset($data[$k])?$data[$k]:'');
					}
				}
				unset($updata_diff['edittime']);
				$edit_log_idents[]=$ident;
				$edit_log_datas[]=$updata_diff;
			}else {
			    $data['nums']=$nums=='-'?NULL:$nums;
			    $data=array_merge($data,$map);
			    $data['oprice']=0;
			    $data['channel_code']='Weixin';
			    $this->db->insert('hotel_room_state',$data);
				unset($data['edittime']);
				$add_log_idents[]=$ident;
				$add_log_datas[]=$data;
			}
		}
		$this->db->trans_complete ();
		if ($this->db->trans_status () === FALSE) {
			$this->db->trans_rollback ();
			return false;
		} else {
			$this->db->trans_commit ();
			$this->load->model('hotel/Hotel_log_model');
			if ($add_log_idents)
				$this->Hotel_log_model->add_admin_log($add_log_idents,'add',$add_log_datas);
			if ($edit_log_idents)
				$this->Hotel_log_model->add_admin_log($edit_log_idents,'save',$edit_log_datas);
			return true;
		}
	}
	function get_hotel_temp_stock($inter_id, $hotel_id, $room_ids, $startdate, $enddate) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'date>=' => $startdate,
				'date<=' => $enddate 
		) );
		$db_read->where_in ( 'room_id', explode ( ',', $room_ids ) );
		$data = $db_read->get ( self::TAB_HOTEL_TEMP_STOCK )->result_array ();
		$result = array ();
		if (! empty ( $data )) {
			foreach ( $data as $d ) {
				$result [$d ['room_id']] [$d ['price_code']] [$d ['date']][$d['stock_type']] = $d ['date_stock'];
			}
		}
		return $result;
	}
	function get_hotel_type_stock($inter_id, $hotel_id, $room_ids, $startdate, $enddate) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select ( '*' );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'date>=' => $startdate,
				'date<=' => $enddate,
// 				'date_stock>' => 0 
		) );
// 		$db_read->group_by ( 'inter_id,hotel_id,room_id,`date`,stock_type' );
		$db_read->where_in ( 'room_id', explode ( ',', $room_ids ) );
		$data = $db_read->get ( self::TAB_HOTEL_TEMP_STOCK )->result_array ();
		$result=array();
		$part_stock=array();
		if (! empty ( $data )) {
			foreach ( $data as $d ) {
				if($d ['stock_type']=='room'){
					empty($result [$d ['room_id']] [$d ['date']] ['room'])?$result [$d ['room_id']] [$d ['date']] ['room'] = $d ['date_stock']:$result [$d ['room_id']] [$d ['date']] ['room'] += $d ['date_stock'];
				}
				$part_stock [$d ['room_id']] [$d ['price_code']] [$d ['date']] [$d ['stock_type']] = $d ['date_stock'];
			}
		}
		return array (
				'all' => $result,
				'part' => $part_stock 
		);
	}
	function get_price_code_stock_type($inter_id, $hotel_id, $room_id, $price_code, $startdate, $enddate) {
		$this->load->model ( 'hotel/Price_code_model' );
		$code_set = $this->Price_code_model->get_price_set ( $inter_id, $hotel_id, $room_id, $price_code );
		$data = $this->get_room_price ( $inter_id, $hotel_id, $room_id, $price_code, $startdate, $enddate );
		$this->load->helper ( 'date' );
		$day_range = get_day_range ( $startdate, $enddate, 'array' );
		$stock_type = array ();
		foreach ( $day_range as $d ) {
			if (! empty ( $data [$d] ) && ! is_null ( $data [$d] ['nums'] )) {
				$stock_type [$d] = 'date';
			} else if (! empty ( $code_set ) && isset ( $code_set ['nums'] )) {
				$stock_type [$d] = 'code';
			} else
				$stock_type [$d] = 'room';
		}
		return $stock_type;
	}
	/**
	 * $condits array('hotel_id'=>$hotel_id,'inter_id'=>$inter_id,'room_id'=>$room_id,'price_code'=>$price_code)
	 */
	function change_hotel_temp_stock($condits, $startdate, $enddate, $nums) {
		if(strtotime($startdate) <  strtotime($enddate)){
			$enddate = date ( "Ymd", strtotime ( '- 1 day', strtotime ( $enddate ) ) );
		}
		$stock_type = $this->get_price_code_stock_type ( $condits ['inter_id'], $condits ['hotel_id'], $condits ['room_id'], $condits ['price_code'], $startdate, $enddate );
		$tmp_stock = $this->get_hotel_temp_stock ( $condits ['inter_id'], $condits ['hotel_id'], $condits ['room_id'], $startdate, $enddate );
		foreach ( $stock_type as $k => $d ) {
			$condits ['date'] = $k;
			$condits ['stock_type'] = $d;
			if (isset ( $tmp_stock [$condits ['room_id']] [$condits ['price_code']] [$k][$d] )) {
				$this->db->where ( $condits );
				$tmp_num = $tmp_stock [$condits ['room_id']] [$condits ['price_code']] [$k][$d] + $nums;
				if ($tmp_num < 0)
					$tmp_num = 0;
				$this->db->set ( 'date_stock', $tmp_num, false );
				$this->db->update ( self::TAB_HOTEL_TEMP_STOCK );
			} else {
				if ($nums > 0)
					$this->db->set ( 'date_stock', $nums );
				$this->db->insert ( self::TAB_HOTEL_TEMP_STOCK, $condits );
			}
		}
	}

	function save_room_price_new($inter_id, $hotel_id, $room_id, $price_code, $price, $nums, $day_arr) {
		$db_read = $this->load->database('iwide_r1',true);
		$add_log_datas=array();
		$add_log_idents=array();
		$edit_log_datas=array();
		$edit_log_idents=array();
		$states=$this->get_room_days_price($inter_id, $hotel_id, $room_id, $price_code, $day_arr);
		$states=array_column($states, NULL,'date');
		$this->db->trans_begin ();
		if($price == '-' || $nums == '-'){
			$this->load->model ( 'hotel/Price_code_model' );
			$list = $this->Price_code_model->get_room_price_set ( $inter_id, $hotel_id, $room_id, $price_code );
		}
		foreach ( $day_arr as $day ) {
			$price_new = $price;
			$nums_new = $nums;
			$data = isset($states[$day])?$states[$day]:array();
			if($price == '-' || $nums == '-'){
// 				$db_read->where(array (
// 					'inter_id' => $inter_id,
// 					'room_id' => $room_id,
// 					'hotel_id' => $hotel_id,
// 					'date' => $day,
// 					'price_code' => $price_code,
// 					'channel_code' => 'Weixin'
// 				));
// 				$data = $db_read->get ( 'hotel_room_state' )->row_array ();
				if($data){
					if($nums == '-')
						$nums_new = $data['nums'];
					if($price == '-')
						$price_new = $data['price'];
				}else{
					if(!empty($list[0])){
						if($nums == '-')
							$nums_new = $list[0]['snums'];
						if($price == '-')
							$price_new = $list[0]['sprice'];
					}
				}
			}
			$updata=array (
					'inter_id' => $inter_id,
					'room_id' => $room_id,
					'hotel_id' => $hotel_id,
					'date' => $day,
					'price' => $price_new,
					'oprice' => 0,
					'price_code' => $price_code,
					'nums' => $nums_new,
					'channel_code' => 'Weixin',
					'edittime' => time () 
			);
			$this->db->replace ( 'hotel_room_state', $updata );
			$ident='room_state'.'#'.$inter_id.'_'.$hotel_id.'_'.$room_id.'_'.$price_code;
			if ($data){
				$updata_diff=array();
				foreach ($data as $k=>$c){
					if ((isset($updata[$k])&&$data[$k]!=$updata[$k])||$k=='date'){
						$updata_diff[$k]=array('old'=>$c,'new'=>isset($updata[$k])?$updata[$k]:'');
					}
				}
				unset($updata_diff['edittime']);
				$edit_log_idents[]=$ident;
				$edit_log_datas[]=$updata_diff;
			}else {
				unset($updata['edittime']);
				$add_log_idents[]=$ident;
				$add_log_datas[]=$updata;
			}
		}
		$this->db->trans_complete ();
		if ($this->db->trans_status () === FALSE) {
			$this->db->trans_rollback ();
			return false;
		} else {
			$this->db->trans_commit ();
			$this->load->model('hotel/Hotel_log_model');
			if ($add_log_idents)
				$this->Hotel_log_model->add_admin_log($add_log_idents,'add',$add_log_datas);
			if ($edit_log_idents)
				$this->Hotel_log_model->add_admin_log($edit_log_idents,'save',$edit_log_datas);
			return true;
		}
	}
}