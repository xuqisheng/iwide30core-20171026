<?php
class Order_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_H = 'hotels';
	const TAB_HO = 'hotel_orders';
	const TAB_HOI = 'hotel_order_items';
	const TAB_HR = 'hotel_rooms';
	const TAB_HPS = 'hotel_price_set';
	const TAB_HPI = 'hotel_price_info';
	const TAB_HRS = 'hotel_room_state';
	function get_last_order($inter_id, $openid, $nums, $effect = true) {
		$sql = 'SELECT o.*,h.name hname,h.city hcity from (SELECT * FROM ' . $this->db->dbprefix ( self::TAB_HO ) . " WHERE `inter_id` = '$inter_id' AND `openid` = '$openid'";
		if ($effect == true)
			$sql .= " AND status IN (1,2,3) ";
		$sql .= " ORDER BY order_time desc limit 0,$nums) o JOIN (SELECT * FROM " . $this->db->dbprefix ( self::TAB_H ) . " WHERE inter_id='$inter_id' AND status=1 ) h 
				ON h.hotel_id=o.hotel_id AND h.inter_id=o.inter_id ";
		return $this->db->query ( $sql )->result_array ();
	}
	function date_validate($startdate, $enddate) {
		if (! strtotime ( $startdate ) || date ( "Ymd", strtotime ( $startdate ) ) < date ( 'Ymd' )) {
			$startdate = date ( 'Ymd' );
		}
		$startdate = date ( "Ymd", strtotime ( $startdate ) );
		if (! strtotime ( $enddate ) || date ( "Ymd", strtotime ( $enddate ) ) <= $startdate) {
			$enddate = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $startdate ) ) );
		}
		$enddate = date ( "Ymd", strtotime ( $enddate ) );
		return array (
				$startdate,
				$enddate 
		);
	}
	function get_rooms_change_part($rooms, $idents = array(), $condit = array(), $needimg = false) {
		$enddate = date ( "Ymd", strtotime ( '- 1 day', strtotime ( $condit ['enddate'] ) ) );
		$this->load->helper ( 'date' );
		$day_range = get_day_range ( $condit ['startdate'], $enddate );
		$room_ids = '';
		foreach ( $rooms as $rm ) {
			$room_ids .= ',' . $rm ['room_id'];
		}
		$room_ids = substr ( $room_ids, 1 );
		if (empty ( $room_ids ))
			return $rooms;
		$channel_code = empty ( $idents ['channel_code'] ) ? 'Weixin' : $idents ['channel_code'];
		$price_codes = empty ( $condit ['price_codes'] ) ? '' : $condit ['price_codes'];
		$extra_price_code = empty ( $condit ['extra_price_code'] ) ? '' : $condit ['extra_price_code'];
		$type = empty ( $condit ['price_type'] ) ? " in ('common','member')" : " in ('common','member','" . implode ( "','", $condit ['price_type'] ) . "')";
		$datas = array ();
		$price_code_sql = "SELECT i.* FROM `" . $this->db->dbprefix ( self::TAB_HPS ) . "` s 
				 join `" . $this->db->dbprefix ( self::TAB_HPI ) . "` i 
				  on s.inter_id=i.inter_id and s.price_code=i.price_code 
				   where s.hotel_id=" . $idents ['hotel_id'] . " and s.status in (1,2) and i.status = 1 and i.channel_code='" . $channel_code . "' 
				    and i.inter_id='" . $idents ['inter_id'] . "' and i.type $type";
		if (! empty ( $price_codes ))
			$price_code_sql .= " and i.price_code in ( " . $price_codes . ')';
		else if (! empty ( $extra_price_code )) {
			$price_code_sql .= " and (i.price_code in ( " . $extra_price_code . ") or i.type in ('common','member'))";
		}
		$price_code_sql .= " order by sort desc";
		$price_code_set = $this->db->query ( $price_code_sql )->result_array ();
		$set = array ();
		$related_codes = array ();
		$price_code_in = '';
		foreach ( $price_code_set as $pcs ) {
			$price_code_in .= ',' . $pcs ['price_code'];
			$pcs ['use_condition'] = json_decode ( $pcs ['use_condition'], TRUE );
			$pcs ['disp_type'] = 'buy';
			if (! is_null ( $pcs ['related_code'] )) {
				$related_codes [] = $pcs ['related_code'];
			}
			if (isset ( $pcs ['use_condition'] ['member_level'] )) {
				// if (isset ( $condit ['member_level'] ) && $condit ['member_level'] == $pcs ['use_condition'] ['member_level'] && ! empty ( $condit ['member_privilege'] ) && array_key_exists ( $pcs ['use_condition'] ['member_level'], $condit ['member_privilege'] )) {
				if (isset ( $condit ['member_level'] ) && ! empty ( $condit ['member_privilege'] ) && array_key_exists ( $pcs ['use_condition'] ['member_level'], $condit ['member_privilege'] )) {
					$pcs ['related_cal_way'] = 'multi';
					$pcs ['related_cal_value'] = $condit ['member_privilege'] [$pcs ['use_condition'] ['member_level']] ['room'];
					if ($condit ['member_level'] != $pcs ['use_condition'] ['member_level']) {
						$pcs ['disp_type'] = 'only_show';
					} else {
						$pcs ['disp_type'] = 'buy_show';
					}
					$set [$pcs ['price_code']] = $pcs;
				}
			} else
				$set [$pcs ['price_code']] = $pcs;
		}
		$price_code_in = substr ( $price_code_in, 1 );
		$state_sql = "SELECT * FROM `" . $this->db->dbprefix ( self::TAB_HRS ) . "` 
				     where hotel_id=" . $idents ['hotel_id'] . " and inter_id='" . $idents ['inter_id'] . "' and date in ($day_range) and room_id in ($room_ids) 
					  and price_code in ($price_code_in,-1)";
		$room_state = $this->db->query ( $state_sql )->result_array ();
		$state = array ();
		$default_nums = array ();
		$related_state = array ();
		foreach ( $room_state as $rs ) {
			$state [$rs ['room_id']] [$rs ['price_code']] [] = $rs;
		}
		$related_codes [] = '-1';
		$this->db->where ( array (
				'hotel_id' => $idents ['hotel_id'],
				'inter_id' => $idents ['inter_id'] 
		) );
		$this->db->where_in ( 'price_code', $related_codes );
		$this->db->where ( "room_id in ($room_ids)", null, false );
		$this->db->where ( "date in ($day_range)", null, false );
		$def_nums = $this->db->get ( self::TAB_HRS )->result_array ();
		foreach ( $def_nums as $rs ) {
			if ($rs ['price_code'] == - 1) {
				$default_nums [$rs ['room_id']] [$rs ['date']] = $rs;
			}
			$related_state [$rs ['room_id']] [$rs ['price_code']] [] = $rs;
		}
		$countday = ceil ( (strtotime ( $condit ['enddate'] ) - strtotime ( $condit ['startdate'] )) / 86400 ); // 获得相差天数
		$i = 0;
		foreach ( $rooms as $rm ) {
			$room_id = $rm ['room_id'];
			if (! empty ( $condit ['is_ajax'] )) {
				$room_id = $i;
			}
			$datas [$room_id] ['room_info'] = $rm;
			$datas [$room_id] ['state_info'] = array ();
			$j = 0;
			foreach ( $set as $st ) {
				if (! is_null ( $st ['related_code'] )) {
					if (! empty ( $related_state [$rm ['room_id']] [$st ['related_code']] ) && count ( $related_state [$rm ['room_id']] [$st ['related_code']] ) == $countday) {
						for($n = 0; $n < $countday; $n ++) {
							$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
							$price_id = $st ['price_code'];
							if (! empty ( $condit ['is_ajax'] )) {
								$price_id = $j;
							}
							foreach ( $related_state [$rm ['room_id']] [$st ['related_code']] as $sr ) {
								if (! isset ( $datas [$room_id] ['state_info'] [$price_id] ['price_code'] )) {
									$datas [$room_id] ['state_info'] [$price_id] ['price_name'] = $st ['price_name'];
									$datas [$room_id] ['state_info'] [$price_id] ['price_type'] = $st ['type'];
									$datas [$room_id] ['state_info'] [$price_id] ['price_code'] = $st ['price_code'];
									$datas [$room_id] ['state_info'] [$price_id] ['des'] = $st ['des'];
									$datas [$room_id] ['state_info'] [$price_id] ['sort'] = $st ['sort'];
									$datas [$room_id] ['state_info'] [$price_id] ['disp_type'] = $st ['disp_type'];
									$datas [$room_id] ['state_info'] [$price_id] ['related_code'] = $st ['related_code'];
									$datas [$room_id] ['state_info'] [$price_id] ['related_cal_way'] = $st ['related_cal_way'];
									$datas [$room_id] ['state_info'] [$price_id] ['related_cal_value'] = $st ['related_cal_value'];
									$datas [$room_id] ['state_info'] [$price_id] ['condition'] = $st ['use_condition'];
								}
								if ($sr ['date'] == $tmpdate) {
									switch ($st ['related_cal_way']) {
										case 'plus' :
											$tmp ['price'] = $sr ['price'] + $st ['related_cal_value'];
											break;
										case 'reduce' :
											$tmp ['price'] = $sr ['price'] - $st ['related_cal_value'];
											break;
										case 'multi' :
											$tmp ['price'] = $sr ['price'] * $st ['related_cal_value'];
											break;
										case 'divide' :
											$tmp ['price'] = $sr ['price'] / $st ['related_cal_value'];
											break;
										default :
											$tmp ['price'] = $sr ['price'];
											break;
									}
									$tmp ['oprice'] = $sr ['price'];
									if (! is_null ( $sr ['nums'] )) {
										$tmp ['nums'] = $sr ['nums'];
									} else if (! empty ( $default_nums [$rm ['room_id']] [$tmpdate] ) && ! is_null ( $default_nums [$rm ['room_id']] [$tmpdate] ['nums'] )) {
										$tmp ['nums'] = $default_nums [$rm ['room_id']] [$tmpdate] ['nums'];
									}
									empty ( $datas [$room_id] ['state_info'] [$price_id] ['total'] ) ? $datas [$room_id] ['state_info'] [$price_id] ['total'] = $tmp ['price'] : $datas [$room_id] ['state_info'] [$price_id] ['total'] += $tmp ['price'];
									empty ( $datas [$room_id] ['state_info'] [$price_id] ['ototal'] ) ? $datas [$room_id] ['state_info'] [$price_id] ['ototal'] = $tmp ['oprice'] : $datas [$room_id] ['state_info'] [$price_id] ['ototal'] += $tmp ['oprice'];
									empty ( $datas [$room_id] ['state_info'] [$price_id] ['allprice'] ) ? $datas [$room_id] ['state_info'] [$price_id] ['allprice'] = $tmp ['price'] : $datas [$room_id] ['state_info'] [$price_id] ['allprice'] .= ',' . $tmp ['price'];
									$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
									break;
								}
							}
						}
					}
				} else {
					if (! empty ( $state [$rm ['room_id']] [$st ['price_code']] ) && count ( $state [$rm ['room_id']] [$st ['price_code']] ) == $countday) {
						for($n = 0; $n < $countday; $n ++) {
							$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
							$price_id = $st ['price_code'];
							if (! empty ( $condit ['is_ajax'] )) {
								$price_id = $j;
							}
							foreach ( $state [$rm ['room_id']] [$st ['price_code']] as $sr ) {
								if (! isset ( $datas [$room_id] ['state_info'] [$price_id] ['price_code'] )) {
									$datas [$room_id] ['state_info'] [$price_id] ['price_name'] = $st ['price_name'];
									$datas [$room_id] ['state_info'] [$price_id] ['price_type'] = $st ['type'];
									$datas [$room_id] ['state_info'] [$price_id] ['price_code'] = $st ['price_code'];
									$datas [$room_id] ['state_info'] [$price_id] ['des'] = $st ['des'];
									$datas [$room_id] ['state_info'] [$price_id] ['sort'] = $st ['sort'];
									$datas [$room_id] ['state_info'] [$price_id] ['disp_type'] = $st ['disp_type'];
									$datas [$room_id] ['state_info'] [$price_id] ['related_code'] = $st ['related_code'];
									$datas [$room_id] ['state_info'] [$price_id] ['condition'] = $st ['use_condition'];
								}
								if ($sr ['date'] == $tmpdate) {
									$tmp ['price'] = $sr ['price'];
									$tmp ['oprice'] = $rm ['oprice'];
									if (! is_null ( $sr ['nums'] )) {
										$tmp ['nums'] = $sr ['nums'];
									} else if (! empty ( $default_nums [$rm ['room_id']] [$tmpdate] ) && ! is_null ( $default_nums [$rm ['room_id']] [$tmpdate] ['nums'] )) {
										$tmp ['nums'] = $default_nums [$rm ['room_id']] [$tmpdate] ['nums'];
									}
									empty ( $datas [$room_id] ['state_info'] [$price_id] ['total'] ) ? $datas [$room_id] ['state_info'] [$price_id] ['total'] = $tmp ['price'] : $datas [$room_id] ['state_info'] [$price_id] ['total'] += $tmp ['price'];
									empty ( $datas [$room_id] ['state_info'] [$price_id] ['ototal'] ) ? $datas [$room_id] ['state_info'] [$price_id] ['ototal'] = $tmp ['oprice'] : $datas [$room_id] ['state_info'] [$price_id] ['ototal'] += $tmp ['oprice'];
									empty ( $datas [$room_id] ['state_info'] [$price_id] ['allprice'] ) ? $datas [$room_id] ['state_info'] [$price_id] ['allprice'] = $tmp ['price'] : $datas [$room_id] ['state_info'] [$price_id] ['allprice'] .= ',' . $tmp ['price'];
									$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
									break;
								}
							}
						}
					}
				}
				$j ++;
			}
			$i ++;
		}
		foreach ( $datas as $k => $d ) {
			$lowest = 999999;
			$nums = isset ( $condit ['nums'] [$k] ) ? $condit ['nums'] [$k] : 1;
			$volume = array ();
			$shows = array ();
			foreach ( $d ['state_info'] as $dk => $dd ) {
				$tmp_p = number_format ( $dd ['total'] / $countday, 2, '.', '' );
				$d ['state_info'] [$dk] ['total_price'] = $dd ['total'] * $nums;
				$d ['state_info'] [$dk] ['total_oprice'] = $dd ['ototal'] * $nums;
				$d ['state_info'] [$dk] ['avg_price'] = $tmp_p;
				$d ['state_info'] [$dk] ['sort_key'] = $dk;
				$d ['state_info'] [$dk] ['avg_oprice'] = number_format ( $dd ['ototal'] / $countday, 2, '.', '' );
				if ($tmp_p < $lowest) {
					$lowest = $tmp_p;
				}
				$least_num = 999999;
				$cflag = 0;
				$state = 'full';
				foreach ( $dd ['date_detail'] as $ddt ) {
					if ($ddt ['nums'] < $least_num) {
						$least_num = $ddt ['nums'];
						$d ['state_info'] [$dk] ['least_num'] = $least_num;
						if ($ddt ['nums'] >= $nums) {
							$state = 'available';
						} else {
							$cflag = 1;
						}
					}
				}
				if ($cflag == 1 && $state == 'full')
					$d ['state_info'] [$dk] ['book_status'] = 'full';
				else if ($cflag == 1 && $state == 'available')
					$d ['state_info'] [$dk] ['book_status'] = 'lack';
				else if ($cflag == 0)
					$d ['state_info'] [$dk] ['book_status'] = 'available';
				if ($dd ['disp_type'] == 'only_show' || $dd ['disp_type'] == 'buy_show') {
					if (! is_null ( $dd ['related_code'] )) {
						switch ($dd ['related_cal_way']) {
							case 'plus' :
								$d ['state_info'] [$dk] ['related_des'] = '(+' . $dd ['related_cal_value'] . ')';
								break;
							case 'reduce' :
								$d ['state_info'] [$dk] ['related_des'] = '(-' . $dd ['related_cal_value'] . ')';
								break;
							case 'multi' :
								$d ['state_info'] [$dk] ['related_des'] = '(' . ($dd ['related_cal_value'] * 10) . '折)';
								break;
							case 'divide' :
								$d ['state_info'] [$dk] ['related_des'] = '(' . (10 / $dd ['related_cal_value']) . '折)';
								break;
							default :
								$d ['state_info'] [$dk] ['related_des'] = '';
								break;
						}
					}
					$shows [$dk] = $d ['state_info'] [$dk];
					if ($dd ['disp_type'] == 'only_show')
						unset ( $d ['state_info'] [$dk] );
				}
			}
			uasort ( $d ['state_info'], function ($a, $b) {
				return $b ['sort'] - $a ['sort'];
			} );
			$datas [$k] ['state_info'] = $d ['state_info'];
			$datas [$k] ['show_info'] = $shows;
			$datas [$k] ['lowest'] = number_format ( $lowest, 2, '.', '' );
		}
		return $datas;
	}
	
	/**
	 * 获取一段时间内房态
	 *
	 * return $datas like
	 * $datas=array(
	 * $room_id1=>array(
	 * 'room_info'=>$rm,
	 * 'state_info'=array(
	 * $price_code1=>array(
	 * 'date_detail'=array(
	 * '20151111'=>array('price'=>100,'ori_price'=>120,'leftnums'=>3),
	 * '20151112'=>array('price'=>110,'ori_price'=>120,'leftnums'=>3)
	 * ),
	 * 'price_name'=>'双十一特惠',
	 * 'avg_price'=>105,
	 * 'total'=>210,
	 * 'price_code'=>$price_code1,
	 * 'book_status'=>1
	 * ),
	 * 'lowest'=>105
	 * )
	 * )
	 * )
	 */
	function get_rooms_change($rooms, $idents = array(), $condit = array(), $needimg = false) {
		$countday = ceil ( (strtotime ( $condit ['enddate'] ) - strtotime ( $condit ['startdate'] )) / 86400 ); // 获得相差天数
		$enddate = date ( "Ymd", strtotime ( '- 1 day', strtotime ( $condit ['enddate'] ) ) );
		$this->load->helper ( 'date' );
		$day_range = get_day_range ( $condit ['startdate'], $enddate );
		$day_range .= ',-1';
		$room_ids = '';
		foreach ( $rooms as $rm ) {
			$room_ids .= ',' . $rm ['room_id'];
		}
		$room_ids = substr ( $room_ids, 1 );
		$channel_code = empty ( $idents ['channel_code'] ) ? 'Weixin' : $idents ['channel_code'];
		$datas = array ();
		$state_sql = "select si.*,st.room_id,st.date,st.price,st.nums,st.oprice from 
				(SELECT i.* FROM `" . $this->db->dbprefix ( self::TAB_HPS ) . "` s 
				 join `" . $this->db->dbprefix ( self::TAB_HPI ) . "` i 
				  on s.inter_id=i.inter_id and s.price_code=i.price_code 
				   where s.hotel_id=" . $idents ['hotel_id'] . " and s.status=1 and i.status=1 and i.channel_code='" . $channel_code . "' and i.inter_id='" . $idents ['inter_id'] . "'";
		if (! empty ( $condit ['price_codes'] ))
			$state_sql .= " and i.price_code in ( " . $condit ['price_codes'] . ')';
		$state_sql .= " order by sort desc) si 
				    join (SELECT * FROM `" . $this->db->dbprefix ( self::TAB_HRS ) . "` 
				     where hotel_id=" . $idents ['hotel_id'] . " and inter_id='" . $idents ['inter_id'] . "' and date in ($day_range) and room_id in ($room_ids)";
		if (! empty ( $condit ['price_code'] ))
			$state_sql .= " and price_code in ( " . $condit ['price_codes'] . ')';
		$state_sql .= ") st 
				      on si.price_code=st.price_code and si.channel_code=st.channel_code";
		$room_state = $this->db->query ( $state_sql )->result_array ();
		$state = array ();
		$default_state = array ();
		$default_nums = array ();
		foreach ( $room_state as $rs ) {
			$state [$rs ['room_id']] [$rs ['price_code']] [] = $rs;
			if ($rs ['date'] == - 1) {
				$default_state [$rs ['room_id']] [$rs ['price_code']] = $rs;
			}
		}
		$this->db->where ( array (
				'hotel_id' => $idents ['hotel_id'],
				'inter_id' => $idents ['inter_id'],
				'price_code' => - 1 
		) );
		$this->db->where ( "room_id in ($room_ids)", null, false );
		$def_nums = $this->db->get ( self::TAB_HRS )->result_array ();
		foreach ( $def_nums as $rs ) {
			$default_nums [$rs ['room_id']] [$rs ['date']] = $rs;
		}
		/*
		 * if ($needimg) {
		 * $roomservice = array ();
		 * $roomintro = array ();
		 * $roomservice = $this->db->get_where ( 'Hotels_rooms_image', array (
		 * 'hotel_id' => $rm->hotel_id,
		 * 'inter_id' => $rm->inter_id,
		 * 'room_id' => $rm->room_id,
		 * 'type' => 2,
		 * 'status' => 0
		 * ) )->result (); // 取房型提供的服务的图标
		 * $this->db->limit ( 1 )->order_by ( 'sort asc' );
		 * $roomintro = $this->db->get_where ( 'Hotels_rooms_image', array (
		 * 'hotel_id' => $rm->hotel_id,
		 * 'inter_id' => $rm->inter_id,
		 * 'room_id' => $rm->room_id,
		 * 'type' => 1,
		 * 'status' => 0
		 * ) )->row_array (); //
		 * $rm->services = $roomservice;
		 * $rm->roomimg = $roomintro;
		 * }
		 */
		foreach ( $rooms as $rm ) {
			$datas [$rm ['room_id']] ['room_info'] = $rm;
			$datas [$rm ['room_id']] ['state_info'] = array ();
			for($n = 0; $n < $countday; $n ++) {
				$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
				if (! empty ( $state [$rm ['room_id']] )) {
					foreach ( $state [$rm ['room_id']] as $sr ) {
						foreach ( $sr as $s ) {
							$datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['price_name'] = $s ['price_name'];
							$datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['price_code'] = $s ['price_code'];
							$datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['des'] = $s ['des'];
							$datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['condition'] = json_decode ( $s ['use_condition'], true );
							$tmp = array ();
							if ($s ['date'] == $tmpdate) {
								if (! is_null ( $s ['price'] )) {
									$tmp ['price'] = $s ['price'];
									$tmp ['oprice'] = $s ['oprice'];
								} else if (! is_null ( $default_state [$rm ['room_id']] [$s ['price_code']] ['price'] )) {
									$tmp ['price'] = $default_state [$rm ['room_id']] [$s ['price_code']] ['price'];
									$tmp ['oprice'] = $default_state [$rm ['room_id']] [$s ['price_code']] ['oprice'];
								} else {
									$tmp ['price'] = $rm ['price'];
									$tmp ['oprice'] = $rm ['oprice'];
								}
								if (! is_null ( $s ['nums'] )) {
									$tmp ['nums'] = $s ['nums'];
								} else if (! empty ( $default_state [$rm ['room_id']] [$s ['price_code']] ) && ! is_null ( $default_state [$rm ['room_id']] [$s ['price_code']] ['nums'] )) {
									$tmp ['nums'] = $default_state [$rm ['room_id']] [$s ['price_code']] ['nums'];
								} else if (! empty ( $default_nums [$rm ['room_id']] [$tmpdate] ) && ! is_null ( $default_nums [$rm ['room_id']] [$tmpdate] ['nums'] )) {
									$tmp ['nums'] = $default_nums [$rm ['room_id']] [$tmpdate] ['nums'];
								} else {
									$tmp ['nums'] = $rm ['nums'];
								}
								break;
							} else {
								if (! empty ( $default_state [$rm ['room_id']] [$s ['price_code']] ) && ! is_null ( $default_state [$rm ['room_id']] [$s ['price_code']] ['price'] )) {
									$tmp ['price'] = $default_state [$rm ['room_id']] [$s ['price_code']] ['price'];
									$tmp ['oprice'] = $default_state [$rm ['room_id']] [$s ['price_code']] ['oprice'];
								} else {
									$tmp ['price'] = $rm ['price'];
									$tmp ['oprice'] = $rm ['oprice'];
								}
								if (! empty ( $default_state [$rm ['room_id']] [$s ['price_code']] ) && ! is_null ( $default_state [$rm ['room_id']] [$s ['price_code']] ['nums'] )) {
									$tmp ['nums'] = $default_state [$rm ['room_id']] [$s ['price_code']] ['nums'];
								} else if (! empty ( $default_nums [$rm ['room_id']] [$tmpdate] ) && ! is_null ( $default_nums [$rm ['room_id']] [$tmpdate] ['nums'] )) {
									$tmp ['nums'] = $default_nums [$rm ['room_id']] [$tmpdate] ['nums'];
								} else {
									$tmp ['nums'] = $rm ['nums'];
								}
							}
						}
						empty ( $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['total'] ) ? $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['total'] = $tmp ['price'] : $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['total'] += $tmp ['price'];
						empty ( $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['ototal'] ) ? $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['ototal'] = $tmp ['oprice'] : $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['ototal'] += $tmp ['oprice'];
						empty ( $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['allprice'] ) ? $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['allprice'] = $tmp ['price'] : $datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['allprice'] .= ',' . $tmp ['price'];
						$datas [$rm ['room_id']] ['state_info'] [$s ['price_code']] ['date_detail'] [$tmpdate] = $tmp;
					}
				} else {
					$datas [$rm ['room_id']] ['state_info'] [0] ['price_name'] = '微信价';
					$datas [$rm ['room_id']] ['state_info'] [0] ['price_code'] = 0;
					$datas [$rm ['room_id']] ['state_info'] [0] ['date_detail'] [$tmpdate] ['price'] = $rm ['price'];
					$datas [$rm ['room_id']] ['state_info'] [0] ['date_detail'] [$tmpdate] ['oprice'] = $rm ['oprice'];
					$datas [$rm ['room_id']] ['state_info'] [0] ['date_detail'] [$tmpdate] ['nums'] = $rm ['nums'];
					$datas [$rm ['room_id']] ['state_info'] [0] ['total'] = $rm ['price'] * $countday;
					$datas [$rm ['room_id']] ['state_info'] [0] ['ototal'] = $rm ['oprice'] * $countday;
					$datas [$rm ['room_id']] ['state_info'] [0] ['des'] = '';
					empty ( $datas [$rm ['room_id']] ['state_info'] [0] ['allprice'] ) ? $datas [$rm ['room_id']] ['state_info'] [0] ['allprice'] = $rm ['price'] : $datas [$rm ['room_id']] ['state_info'] [0] ['allprice'] .= ',' . $rm ['price'];
				}
			}
		}
		foreach ( $datas as $k => $d ) {
			$lowest = 999999;
			$nums = isset ( $condit ['nums'] [$k] ) ? $condit ['nums'] [$k] : 1;
			foreach ( $d ['state_info'] as $dk => $dd ) {
				$tmp_p = number_format ( $dd ['total'] / $countday, 2, '.', '' );
				$datas [$k] ['state_info'] [$dk] ['total_price'] = $dd ['total'] * $nums;
				$datas [$k] ['state_info'] [$dk] ['total_oprice'] = $dd ['ototal'] * $nums;
				$datas [$k] ['state_info'] [$dk] ['avg_price'] = $tmp_p;
				$datas [$k] ['state_info'] [$dk] ['avg_oprice'] = number_format ( $dd ['ototal'] / $countday, 2, '.', '' );
				if ($tmp_p < $lowest) {
					$lowest = $tmp_p;
				}
				$least_num = 999999;
				$cflag = 0;
				$state = 'full';
				foreach ( $dd ['date_detail'] as $ddt ) {
					if ($ddt ['nums'] < $least_num) {
						$least_num = $ddt ['nums'];
						$datas [$k] ['state_info'] [$dk] ['least_num'] = $least_num;
						if ($ddt ['nums'] >= $nums) {
							$state = 'available';
						} else {
							$cflag = 1;
						}
					}
				}
				if ($cflag == 1 && $state == 'full')
					$datas [$k] ['state_info'] [$dk] ['book_status'] = 'full';
				else if ($cflag == 1 && $state == 'available')
					$datas [$k] ['state_info'] [$dk] ['book_status'] = 'lack';
				else if ($cflag == 0)
					$datas [$k] ['state_info'] [$dk] ['book_status'] = 'available';
			}
			$datas [$k] ['lowest'] = number_format ( $lowest, 2, '.', '' );
		}
		return $datas;
	}
	function create_order($inter_id, $data, $datas, $subs, $roomnos = '') {
		$data ['order_time'] = time ();
		$data ['orderid'] = str_replace ( '-', 'o', substr ( $data ['openid'], - 2, 2 ) . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT ) );
		$this->db->trans_start();
		if ($this->db->insert (self::TAB_HO , $data )) {
			$tmp = array (
					'orderid' => $data ['orderid'],
					'startdate' => $data ['startdate'],
					'enddate' => $data ['enddate'],
					'inter_id' => $inter_id 
			);
			$sub_orders = array ();
			$room_nos = json_decode ( $roomnos, true );
			/*
			 * if($room_nos){
			 * $room_numbers=array();
			 * $room_open=array();
			 * foreach($room_nos as $k=>$v){
			 * if(!empty($v)){
			 * foreach($v as $kk=>$vv){
			 * $room_numbers[$k][]=$kk;
			 * $room_open[$k][$kk]=$vv;
			 * }
			 * }
			 * }
			 * }
			 */
			foreach ( $subs as $rid => $s ) {
				for($i = 0; $i < $datas [$rid]; $i ++) {
					$tmp ['iprice'] = $subs [$rid] ['iprice'];
					$tmp ['allprice'] = $subs [$rid] ['allprice'];
					$tmp ['roomname'] = $subs [$rid] ['roomname'];
					// $tmp['room_no']=array_shift($room_numbers[$item->webser_id]);
					// $tmp['net_open_door']=$room_open[$item->webser_id][$tmp['room_no']];
					$tmp ['room_id'] = $rid;
					$tmp ['price_code'] = $subs [$rid] ['price_code'];
					$sub_orders [] = $tmp;
				}
			}
			if ($this->db->insert_batch ( self::TAB_HOI, $sub_orders )) {
				/* if ($data ['voucherused'] == 1) {
					$this->update_voucher_rel ( $inter_id, $data ['orderid'], 0, 1 );
				} */
			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				return false;
			}
			return true;
		}
		return false;
	}
}