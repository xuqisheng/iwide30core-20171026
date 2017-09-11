<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_report_model extends MY_Model {
	function get_orders_by_roomnight($params, $limit = NULL, $offset = 0, $disp_params = array()) {
		ini_set ( 'memory_limit', '1024M' );
		$inter_id = $params ['inter_id'];
		$where = '';
		$para = array ();
		// if (isset ( $params ['order_status'] ) && $params ['order_status'] !== '') {
		$where .= ' and items.istatus = ? ';
		$para [] = 3;
		// } else {
		// $where .= ' and items.istatus not in (9,10) ';
		// }
		if(!empty($params['entity_hotel_id'])){
			$where .= " and orders.hotel_id in ('" . implode("','",$params['entity_hotel_id']) . "') ";
		}
		if (! empty ( $params ['hotel_id'] )) {
			$where .= ' and orders.hotel_id = ? ';
			$para [] = $params ['hotel_id'];
		}
		if (! empty ( $params ['orderid'] )) {
			$where .= ' and orders.orderid = ? ';
			$para [] = $params ['orderid'];
		}
		if (! empty ( $params ['web_orderid'] )) {
			$where .= ' and additions.web_orderid = ? ';
			$para [] = $params ['web_orderid'];
		}
		if (! empty ( $params ['in_name'] )) {
			$where .= ' and orders.name like ? ';
			$para [] = '%' . $params ['in_name'] . '%';
		}
		if (! empty ( $params ['in_tel'] )) {
			$where .= ' and orders.tel = ? ';
			$para [] = $params ['in_tel'];
		}
		if (! empty ( $params ['order_time_start'] )) {
			$where .= ' and orders.order_time >= ? ';
			$para [] = strtotime ( $params ['order_time_start'] );
		}
		if (! empty ( $params ['order_time_end'] )) {
			$where .= ' and orders.order_time <= ? ';
			$para [] = strtotime ( $params ['order_time_end'] ) + 86399;
		}
		if (! empty ( $params ['start_date_start'] )) {
			$where .= ' and items.startdate >= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['start_date_start'] ) );
		}
		if (! empty ( $params ['start_date_end'] )) {
			$where .= ' and items.startdate <= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['start_date_end'] ) );
		}
		if (! empty ( $params ['end_date_start'] )) {
			$where .= ' and items.enddate >= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['end_date_start'] ) );
		}
		if (! empty ( $params ['end_date_end'] )) {
			$where .= ' and items.enddate <= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['end_date_end'] ) );
		}
		if (! empty ( $params ['leavetime_start'] )) {
			$where .= ' and items.leavetime >= ? ';
			$para [] = date ( 'Y-m-d H:i:s', strtotime ( $params ['leavetime_start'] ) );
		}
		if (! empty ( $params ['leavetime_end'] )) {
			$where .= ' and items.leavetime <= ? ';
			$para [] = date ( 'Y-m-d H:i:s', strtotime ( $params ['leavetime_end'] ) );
		}
		$limit_s = ' order by orders.id desc ';
		if (! is_null ( $limit )) {
			$limit_s .= ' limit ?,? ';
			$para [] = $offset;
			$para [] = $limit;
		}
		$selects = ' orders.roomnums,orders.name in_name,orders.tel in_tel,orders.order_time,orders.paytype paytype,orders.hotel_id o_hotel_id,orders.orderid o_orderid,orders.member_no,orders.status order_status
				   ,items.allprice iall,items.price_code_name,items.webs_orderid,items.id sub_orderid,items.roomname,items.iprice,items.startdate istart,items.enddate iend,items.istatus item_status,items.leavetime,items.mt_room_id
				    ,additions.web_orderid web_orderid,additions.coupon_favour,additions.point_favour,additions.point_used_amount,additions.point_used_amount,additions.balance_part
				     ,member.level member_level,orders.mt_pms_orderid';
		$sql = "SELECT $selects
			   FROM `iwide_hotel_order_items` items JOIN `iwide_hotel_orders` orders 
				 ON items.inter_id=orders.inter_id and items.orderid=orders.orderid 
				  JOIN `iwide_hotel_order_additions` additions 
				    ON orders.inter_id=additions.inter_id and orders.orderid=additions.orderid 
				     LEFT JOIN `iwide_member` member       
				      ON member.inter_id=orders.inter_id  and member.openid=orders.openid
				     WHERE orders.inter_id = '$inter_id' $where $limit_s ";
		$order_data = $this->_db ( 'iwide_rw' )->query ( $sql, $para )->result_array ();
		if ($this->input->get('nobug')==1){
			echo $this->_db ( 'iwide_rw' )->last_query();
		}
		if (! empty ( $disp_params ['just_count'] )) {
			return count ( $order_data );
		}
		// echo $this->_db('iwide_rw')->last_query();exit;
		$this->load->model ( 'hotel/Hotel_model' );
		$hotels = $this->Hotel_model->get_all_hotels ( $inter_id, NULL, 'key' );
		
		$data = array ();
		$this->load->model ( 'hotel/Member_model' );
		$levels = $this->Member_model->get_member_levels ( $inter_id );
		$this->load->model ( 'common/Enum_model' );
		$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
		$this->load->model ( 'pay/Pay_model' );
		$pay_ways = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $inter_id,
				'module' => 'HOTEL',
				'key' => 'value' 
		) );
		$pay_ways ['bonus'] = new stdClass ();
		$pay_ways ['bonus']->pay_name = '积分兑换';
		$favours = array ();
		foreach ( $order_data as &$o ) {
			$o ['in_hotel_name'] = empty ( $hotels [$o ['o_hotel_id']] ) ? '' : $hotels [$o ['o_hotel_id']] ['name'];
			$o ['order_time'] = date ( 'Y-m-d H:i:s', $o ['order_time'] );
			$o ['istart'] = date ( 'Y-m-d', strtotime ( $o ['istart'] ) );
			$o ['iend'] = date ( 'Y-m-d', strtotime ( $o ['iend'] ) );
			$o ['days'] = ceil ( (strtotime ( $o ['iend'] ) - strtotime ( $o ['istart'] )) / 86400 );
			$o ['days'] = $o ['days'] <=0 ? 1 :$o ['days'];
			$o ['nums'] = 1;
			$o ['hotel_id'] = $o['o_hotel_id'];
			$o ['room_night'] = $o ['days'];
			$o ['ori_price'] = number_format ( array_sum ( explode ( ',', $o ['iall'] ) ), 2, '.', '' );
			if (! empty ( $disp_params ['xls'] )) {
				$o ['o_orderid'] = '`' . $o ['o_orderid'];
				if (!empty($o ['web_orderid']))
					$o ['web_orderid'] = '`' . $o ['web_orderid'];
				if (!empty($o ['webs_orderid']))
					$o ['webs_orderid'] = '`' . $o ['webs_orderid'];
			}
			if ($o ['roomnums'] == 1) {
				$o ['coupon_amount'] = $o ['coupon_favour'];
				$o ['point_amount'] = $o ['point_used_amount'];
				$o ['point_favour'] = $o ['point_favour'];
			} else {
				if (! empty ( $favours [$o ['o_orderid']] )) {
					$o ['coupon_amount'] = $favours [$o ['o_orderid']] ['cf'];
					$o ['point_amount'] = $favours [$o ['o_orderid']] ['pa'];
					$o ['point_favour'] = $favours [$o ['o_orderid']] ['pf'];
				} else {
					$avg_cf = intval ( $o ['coupon_favour'] / $o ['roomnums'] );
					$avg_pa = intval ( $o ['point_used_amount'] / $o ['roomnums'] );
					$avg_pf = intval ( $o ['point_favour'] / $o ['roomnums'] );
					$favours [$o ['o_orderid']] ['cf'] = $avg_cf;
					$favours [$o ['o_orderid']] ['pa'] = $avg_pa;
					$favours [$o ['o_orderid']] ['pf'] = $avg_pf;
					$o ['coupon_amount'] = $avg_cf + ($o ['coupon_favour'] - $avg_cf * $o ['roomnums']);
					$o ['point_amount'] = $avg_pa + ($o ['point_used_amount'] - $avg_pa * $o ['roomnums']);
					$o ['point_favour'] = $avg_pf + ($o ['point_favour'] - $avg_pf * $o ['roomnums']);
				}
			}
			$o ['balance_amount'] = 0;
			if ($o ['paytype'] == 'balance') {
				$o ['balance_amount'] = $o ['ori_price'] - $o ['coupon_favour'] - $o ['point_favour'];
			}
			if (! empty ( $o ['balance_part'] )) {
				if ($o ['roomnums'] == 1) {
					$o ['balance_amount'] += $o ['balance_part'];
				} else {
					if (! empty ( $favours [$o ['o_orderid']] ['bp'] )) {
						$o ['balance_amount'] += $favours [$o ['o_orderid']] ['bp'];
					} else {
						$avg_bp = intval ( $o ['balance_part'] / $o ['roomnums'] );
						$favours [$o ['o_orderid']] ['bp'] = $avg_bp;
						$o ['balance_amount'] += $avg_bp + ($o ['balance_part'] - $avg_bp * $o ['roomnums']);
					}
				}
			}
			$o ['paytype'] = isset ( $pay_ways [$o ['paytype']] ) ? $pay_ways [$o ['paytype']]->pay_name : $o ['paytype'];
			$o ['member_level'] = isset ( $levels [$o ['member_level']] ) ? $levels [$o ['member_level']] : $o ['member_level'];
			$o ['item_status'] = $status_des [$o ['item_status']];
		}
		
		return $order_data;
	}
	function get_orders_by_hotel($params, $limit = NULL, $offset = 0, $disp_params = array()) {
		ini_set ( 'memory_limit', '1024M' );
		$inter_id = $params ['inter_id'];
		
		$this->load->model ( 'hotel/Hotel_model' );
		$hotels = $this->Hotel_model->get_all_hotels ( $inter_id, NULL, 'key' );
		if (! empty ( $disp_params ['just_count'] )) {
			return count ( $hotels );
		}
		
		$where = '';
		$para = array ();
		// if (isset ( $params ['order_status'] ) && $params ['order_status'] !== '') {
		$where .= ' and items.istatus = ? ';
		$para [] = 3;
		// } else {
		// $where .= ' and items.istatus not in (9,10) ';
		// }
		// if (! empty ( $params ['hotel_id'] )) {
		// $where .= ' and orders.hotel_id = ? ';
		// $para [] = $params ['hotel_id'];
		// }
		if (! empty ( $params ['order_time_start'] )) {
			$where .= ' and orders.order_time >= ? ';
			$para [] = strtotime ( $params ['order_time_start'] );
		}
		if (! empty ( $params ['order_time_end'] )) {
			$where .= ' and orders.order_time <= ? ';
			$para [] = strtotime ( $params ['order_time_end'] ) + 86399;
		}
		if (! empty ( $params ['start_date_start'] )) {
			$where .= ' and items.startdate >= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['start_date_start'] ) );
		}
		if (! empty ( $params ['start_date_end'] )) {
			$where .= ' and items.startdate <= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['start_date_end'] ) );
		}
		if (! empty ( $params ['end_date_start'] )) {
			$where .= ' and items.enddate >= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['end_date_start'] ) );
		}
		if (! empty ( $params ['end_date_end'] )) {
			$where .= ' and items.enddate <= ? ';
			$para [] = date ( 'Ymd', strtotime ( $params ['end_date_end'] ) );
		}
		
		$selects = ' orders.roomnums,orders.name in_name,orders.tel in_tel,orders.order_time,orders.paytype paytype,orders.hotel_id o_hotel_id,orders.orderid o_orderid,orders.member_no,orders.status order_status
				   ,items.allprice iall,items.price_code_name,items.webs_orderid,items.id sub_orderid,items.roomname,items.iprice,items.startdate istart,items.enddate iend,items.istatus item_status
				    ,additions.web_orderid web_orderid,additions.coupon_favour,additions.point_favour,additions.point_used_amount,additions.point_used_amount,additions.balance_part,additions.coupon_used
				     ';
		$sql = "SELECT $selects
			   FROM `iwide_hotel_order_items` items JOIN `iwide_hotel_orders` orders 
				 ON items.inter_id=orders.inter_id and items.orderid=orders.orderid 
				  JOIN `iwide_hotel_order_additions` additions 
				    ON orders.inter_id=additions.inter_id and orders.orderid=additions.orderid 
				     WHERE orders.inter_id = '$inter_id' $where ";
		$order_data = $this->_db ( 'iwide_rw' )->query ( $sql, $para )->result_array ();
		if ($this->input->get('nobug')==1){
			echo $this->_db ( 'iwide_rw' )->last_query();
		}
		$data = array ();
		$default = array (
				'hotel_name' => 0,
				
				'total_order_count' => 0, // 订单总量
				'sub_order_count'=>0,
				'balance_pay_order_count' => 0, // 储值支付订单总量
				'weixin_pay_order_count' => 0, // 微信支付订单总量
				'daofu_pay_order_count' => 0, // 到店支付订单总量
				'bonus_pay_order_count' => 0, // 积分支付订单总量
				'point_pay_order_count' => 0, // 积分支付订单总量
				'bp_pay_order_count' => 0, // 积分支付订单总量
				'coupon_used_order_count' => 0, // 使用优惠券订单总量
				
				'total_room_night' => 0, // 间夜数量
				'balance_pay_room_night' => 0, // 储值支付间夜总量
				'weixin_pay_room_night' => 0, // 微信支付间夜总量
				'daofu_pay_room_night' => 0, // 到店支付间夜总量
				'bonus_pay_room_night' => 0, // 积分支付间夜总量
				'point_pay_room_night' => 0, // 积分支付间夜总量
				'bp_pay_room_night' => 0, // 积分支付间夜总量
				'coupon_used_room_night' => 0, // 使用优惠券间夜总量
				
				'total_order_money' => 0, // 销售总额
				
				'balance_pay_order_money' => 0, // 储值支付总额
				'weixin_pay_order_money' => 0, // 微信支付总额
				'daofu_pay_order_money' => 0, // 到店支付总额
				
				'total_coupon_used_money' => 0, // 使用优惠券总额
				'total_bonus_amount' => 0, // 使用积分总额
				
				'total_money_sort' => 0 
		);
		
		foreach ( $hotels as $hotel_id => $h ) {
			$data [$hotel_id] = $default;
			$data [$hotel_id] ['hotel_name'] = $h ['name'];
		}
		
		$order_ids = array_column ( $order_data, 'sub_orderid', 'o_orderid' );
		
		foreach ( $order_data as $o ) {
			$hotel_id = $o ['o_hotel_id'];
			if (empty ( $data [$hotel_id] )) {
				continue;
			}
			$paytype = $o ['paytype'];
			$tmp_room_night = round ( (strtotime ( $o ['iend'] ) - strtotime ( $o ['istart'] )) / 86400 );
			$tmp_room_night = $tmp_room_night <= 0 ? 1 : $tmp_room_night;
			$orderid = $o ['o_orderid'];
			if (isset ( $order_ids [$orderid] )) {
				$data [$hotel_id] ['total_order_count'] ++;
				$data [$hotel_id] [$paytype . '_pay_order_count'] ++;
				
				if ($paytype == 'point' || $paytype == 'bonus') {
					$data [$hotel_id] ['bp_pay_order_count'] ++;
				}
				
				if ($paytype != 'point' && $paytype != 'bonus' && $o ['point_used_amount'] > 0) {
					$data [$hotel_id] ['bp_pay_order_count'] ++;
				}
				if ($paytype != 'balance' && $o ['balance_part'] > 0) {
					$data [$hotel_id] ['balance_pay_order_count'] ++;
				}
				
				if ($o ['coupon_used'] == 1) {
					$data [$hotel_id] ['coupon_used_order_count'] ++;
				}
				
				unset ( $order_ids [$orderid] );
			}
			$data[$hotel_id]['sub_order_count']++;
			$data [$hotel_id] ['total_room_night'] += $tmp_room_night;
			
			$data [$hotel_id] [$paytype . '_pay_room_night'] += $tmp_room_night;
			
			if ($paytype == 'point' || $paytype == 'bonus') {
				$data [$hotel_id] ['bp_pay_room_night'] += $tmp_room_night;
			}
			
			if ($paytype != 'point' && $paytype != 'bonus' && $o ['point_used_amount'] > 0) {
				$data [$hotel_id] ['bp_pay_room_night'] += $tmp_room_night;
			}
			if ($paytype != 'balance' && $o ['balance_part'] > 0) {
				$data [$hotel_id] ['balance_pay_room_night'] += $tmp_room_night;
			}
			
			if ($o ['coupon_used'] == 1) {
				$data [$hotel_id] ['coupon_used_room_night'] += $tmp_room_night;
			}
			
			if ($paytype == 'weixin' || $paytype == 'daofu' || $paytype == 'balance') {
				$data [$hotel_id] ['total_order_money'] += $o ['iprice'];
				$data [$hotel_id] [$paytype . '_pay_order_money'] += $o ['iprice'];
			}
			if ($paytype != 'balance' && $o ['balance_part'] > 0) {
				$data [$hotel_id] ['balance_pay_order_money'] += $o ['balance_part'];
			}
			
			if ($o ['coupon_used'] == 1) {
				$data [$hotel_id] ['total_coupon_used_money'] += $o ['coupon_favour'];
			}
			if ($o ['point_used_amount'] > 0) {
				$data [$hotel_id] ['total_bonus_amount'] += $o ['point_used_amount'];
			}
		}
		
		uasort ( $data, function ($a, $b) {
			return $a ['total_order_money'] > $b ['total_order_money'] ? - 1 : 1;
		} );
		$i = 1;
		$tmp = array ();
		foreach ( $data as $hotel_id => $d ) {
			if (! empty ( $d ['hotel_name'] )) {
				if (empty ( $tmp )) {
					$tmp = $d;
				}
				if ($tmp ['total_order_money'] != $d ['total_order_money']) {
					$i ++;
				}
				$data [$hotel_id] ['total_money_sort'] = $i;
				$tmp = $d;
			}
		}
		if (! empty ( $params ['hotel_id'] )) {
			$result = array ();
			if (! empty ( $data [$params ['hotel_id']] )) {
				$result [$params ['hotel_id']] = $data [$params ['hotel_id']];
			}
			return $result;
		}
		if (! is_null ( $limit )) {
			return array_slice ( $data, $offset, $limit );
		}
		return $data;
	}
	function get_rooms_sales($params, $limit = NULL, $offset = 0, $disp_params = arraY()) {
		$db_read = $this->load->database('iwide_r1',true);
		ini_set ( 'memory_limit', '512M' );
		$inter_id = $params ['inter_id'];
		
		$where = '';
		$para = array ();
		$where .= ' and items.istatus = ? ';
		$para [] = 3;
		if (! empty ( $params ['hotel_id'] )) {
			$where .= ' and orders.hotel_id = ? ';
			$para [] = $params ['hotel_id'];
		} else {
			return array ();
		}
		
		$where .= ' and (( items.startdate >= ' . date ( 'Ymd', strtotime ( $params ['check_date_start'] ) ) . ' ';
		$where .= ' and items.startdate <= ' . date ( 'Ymd', strtotime ( $params ['check_date_end'] ) ) . ' ) ';
		$where .= ' or ( items.enddate > ' . date ( 'Ymd', strtotime ( $params ['check_date_start'] ) ) . ' ';
		$where .= ' and items.enddate <= ' . date ( 'Ymd', strtotime ('+ 1 day',strtotime($params ['check_date_end'] )) ) . ' )) ';
		
		$params ['check_date_start'] = date ( 'Ymd', strtotime ( $params ['check_date_start'] ) );
		$params ['check_date_end'] = date ( 'Ymd', strtotime ( $params ['check_date_end'] ) );
		$selects = ' orders.hotel_id o_hotel_id,orders.orderid o_orderid
				   ,items.price_code,items.allprice iall,items.id sub_orderid,items.iprice,items.startdate istart,items.enddate iend,items.room_id
				     ';
		$sql = "SELECT $selects
			   FROM `iwide_hotel_order_items` items JOIN `iwide_hotel_orders` orders 
				 ON items.inter_id=orders.inter_id and items.orderid=orders.orderid 
				     WHERE orders.inter_id = '$inter_id' $where ";
		$order_data = $this->_db ( 'iwide_rw' )->query ( $sql, $para )->result_array ();

		if ($this->input->get('nobug')==1){
			echo $this->_db ( 'iwide_rw' )->last_query();
		}
		$this->load->model ( 'hotel/Rooms_model' );
		$hotel = $db_read->get_where ( 'hotels', array (
				'inter_id' => $inter_id,
				'hotel_id' => $params ['hotel_id'] 
		) )->row_array ();
		$rooms = $this->Rooms_model->get_hotel_rooms ( $inter_id, $params ['hotel_id'] )->result_array ();

		$data = array ();
		$default = array (
				'hotel_name' => 0,
				'room_name' => '',
				'total_count' => 0,
				'total_sold_count' => 0,
				'total_sold_rate' => 0,
				'total_sold_money' => 0 
		);
		
		// $price_codes = $this->get_hotels_price_code ( $inter_id, 'price_code' );
		// $price_sets = $this->get_hotels_price_set ( $inter_id, $params ['hotel_id'], 'nums' );
		// $price_states = $this->get_hotels_price_state ( $inter_id, $params ['hotel_id'], $params ['check_date_start'], $params ['check_date_end'], 'nums' );
		$check_days = round ( (strtotime ( $params ['check_date_end'] ) - strtotime ( $params ['check_date_start'] )) / 86400 );
		foreach ( $rooms as $r ) {
			$data [$r ['room_id']] = $default;
			$data [$r ['room_id']] ['hotel_name'] = $hotel ['name'];
			$data [$r ['room_id']] ['room_name'] = $r ['name'];
			$data [$r ['room_id']] ['nums'] = $r ['nums'];
			$data [$r ['room_id']] ['total_count'] = $r ['nums'] * $check_days;
		}
		
		// $tmp_date = $params ['check_date_start'];
		// for($i = 0; $i < $check_days; $i ++) {
		// foreach ( $price_codes as $p ) {
		// foreach ( $data as $room_id => $d ) {
		// if (isset ( $price_states [$room_id] [$p['price_code']][$tmp_date]['nums'] )) {
		// $data [$r ['room_id']]['total_count']+=$price_states [$room_id] [$p['price_code']][$tmp_date]['nums'];
		// }else if(isset($price_sets[$room_id][$p['price_code']]['nums'])){
		// $data [$r ['room_id']]['total_count']+=$price_sets[$room_id][$p['price_code']]['nums'];
		// }else {
		// $data [$r ['room_id']]['total_count']+=$d['nums'];
		// }
		// }
		// }
		// $tmp_date=date('Ymd',strtotime('+ 1 day',strtotime($tmp_date)));
		// }
		// var_dump($data);exit;
		foreach ( $order_data as $o ) {
			$counts = $this->get_ranges_day ( $params ['check_date_start'], $params ['check_date_end'], $o ['istart'], date('Ymd',strtotime('- 1 day',strtotime($o ['iend']))) );
			$day_count = $counts ['day_count'];
			$days = round ( (strtotime ( $o ['iend'] ) - strtotime ( $o ['istart'] )) / 86400 );
			$days = $days <=0 ? 1 : $days;
			$data [$o ['room_id']] ['total_sold_count'] += $day_count;
			$avg_price = intval ( $o ['iprice'] / $days );
			$extra_price = $o ['iprice'] - $avg_price * $days;
			$tmp_date = $counts ['start'];
			$data [$o ['room_id']] ['total_sold_money'] += $avg_price * $day_count;
			$data [$o ['room_id']] ['total_sold_money'] += $extra_price;
		}
		
		foreach ( $data as &$d ) {
			$d ['total_sold_money'] = number_format ( $d ['total_sold_money'], 2, '.', '' );
			if ($d ['total_count'] > 0)
				$d ['total_sold_rate'] = number_format ( $d ['total_sold_count'] / $d ['total_count'], 4, '.', '' ) * 100;
			$d ['total_sold_rate'] .= '%';
		}
		$data['total']=array(
			'hotel_name' => '总计:',
			'room_name' => '房型数量:'.count($data),
			'total_count' => array_sum(array_column($data, 'total_count')),
			'total_sold_count' => array_sum(array_column($data, 'total_sold_count')),
			'total_sold_money' => '总销售额:'.array_sum(array_column($data, 'total_sold_money'))
		);
		$data['total']['total_sold_rate'] = '总出租率:';
		if ($data['total'] ['total_count'] > 0){
			$data['total'] ['total_sold_rate'] .= number_format ( $data['total'] ['total_sold_count'] / $data['total'] ['total_count'], 4, '.', '' ) * 100;
			$data['total']['total_count']='总间数:'.$data['total']['total_count'];
			$data['total']['total_sold_count']='总出租数:'.$data['total']['total_sold_count'];
		}else {
			$data['total'] ['total_sold_rate'] .='0';
		}
		$data['total'] ['total_sold_rate'] .= '%';
		return $data;
	}
	function get_hotels_price_set($inter_id, $hotel_id, $select = '*') {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select ( $select . ' ,price_code,room_id' );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'price_code >' => 0 
		) );
		$price_set = $db_read->get ( 'hotel_price_set' )->result_array ();
		$data = array ();
		if (! empty ( $price_set )) {
			foreach ( $price_set as $p ) {
				$data [$p ['room_id']] [$p ['price_code']] = $p;
			}
		}
		return $data;
	}
	function get_hotels_price_state($inter_id, $hotel_id, $startdate, $enddate, $select = '*') {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select ( $select . ' ,price_code,room_id,date' );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'price_code >' => 0,
				'date >=' => $startdate,
				'date <=' => $enddate 
		) );
		$price_state = $db_read->get ( 'hotel_room_state' )->result_array ();
		$data = array ();
		if (! empty ( $price_state )) {
			foreach ( $price_state as $p ) {
				$data [$p ['room_id']] [$p ['price_code']] [$p ['date']] = $p;
			}
		}
		return $data;
	}
	function get_hotels_price_code($inter_id, $select = '*') {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select ( $select );
		$db_read->where ( array (
				'inter_id' => $inter_id 
		) );
		$price_code = $db_read->get ( 'hotel_price_info' )->result_array ();
		return $price_code;
	}
	function get_ranges_day($fix_range_start, $fix_range_end, $cal_range_start, $cal_range_end) {
		if (empty ( $fix_range_start ) || $fix_range_start < $cal_range_start)
			$fix_range_start = $cal_range_start;
		if (empty ( $fix_range_end ) || $fix_range_end > $cal_range_end)
			$fix_range_end = $cal_range_end;
		$day_count = round ( (strtotime ( $fix_range_end ) - strtotime ( $fix_range_start )) / 86400 )+1;
		return array (
				'start' => $fix_range_start,
				'end' => $fix_range_end,
				'day_count' => $day_count 
		);
	}
	/**
	 * 指定时间房间预订数据
	 * @param unknown $inter_id
	 * @param string $btime 下单开始时间
	 * @param string $etime 下单结束时间
	 * @param string $limit
	 * @param number $offset
	 */
	public function get_booking_summary($inter_id,$btime = '',$etime = '',$limit=NULL,$offset=0){
		$sql = "SELECT o.hotel_id,COUNT(i.id) total_count,SUM(i.istatus=4 OR i.istatus=5) cancel_count,SUM(o.paytype='weixin' OR o.paytype='balance') prepay_count,SUM(i.istatus=2) check_in_count,SUM(IF(i.istatus=2,i.iprice,0)) check_in_amount,SUM(i.istatus=3) check_out_count,SUM(IF(i.istatus=3,i.iprice,0)) check_out_amount FROM iwide_hotel_orders o LEFT JOIN iwide_hotel_order_items i ON o.inter_id=i.inter_id AND o.orderid=i.orderid WHERE o.inter_id=?";
		$params[] = $inter_id;
		if(!empty($btime)){
			$sql .= ' AND o.order_time>=?';
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= ' AND o.order_time<=?';
			$params[] = $etime;
		}
		$sql .= '  GROUP BY o.hotel_id';
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params);
	}
	public function get_booking_summary_count($inter_id,$btime = '',$etime = ''){
		$sql = 'SELECT COUNT(DISTINCT o.hotel_id) counts FROM iwide_hotel_orders o WHERE o.inter_id=?';
		$params[] = $inter_id;
		if(!empty($btime)){
			$sql .= ' AND o.order_time>=?';
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= ' AND o.order_time<=?';
			$params[] = $etime;
		}
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params)->row()->counts;
	}
	//复购率统计
	public function get_orders_re_purchase($params)
	{
		// $inter_id = 'a429262687';
		$inter_id = $params ['inter_id'];
		
		$this->load->library('calendar');
		$date = $params['month_start'];
		$data = array();//返回数组
		while ( $date <= $params['month_end']) {
			//初始化
			$where = '';
			$para = array ();
			$mydata = array(
				'date'=>$date,
				'order_count'=>0,
				'user_count'=>0,
				'count2'=> 0,
				'count3'=> 0,
				'count5'=> 0,
				'count10'=> 0,
				'allcount2'=> 0,
				'allcount3'=> 0,
				'allcount5'=> 0,
				'allcount10'=> 0,
				'o2'=> 0,
				'o3'=> 0,
				'o5'=> 0,
				'o10'=> 0,
				'u2'=> 0,
				'u3'=> 0,
				'u5'=> 0,
				'u10'=> 0,
			);
			$where .= ' and status = ? ';
			$para [] = 3;
			if (! empty ( $params ['hotel_id'] )) {
				$where .= ' and hotel_id = ? ';
				$para [] = $params ['hotel_id'];
			}
			if($params['time_type']==1){//下单时间
				$where .= " and FROM_UNIXTIME(`order_time`,'%Y%m')<= ? ";
				$para [] = $date;
			}elseif($params['time_type']==2){//入住时间
				$where .= " and left(`startdate`,6)<= ? ";
				$para [] = $date;
			}elseif($params['time_type']==3){//离店时间
				$where .= " and left(`enddate`,6)<= ? ";
				$para [] = $date;
			}
			$sql = "SELECT count(id) as order_count
					FROM `iwide_hotel_orders` 
					WHERE inter_id = '$inter_id' $where ";//总订单数
			$order_count = $this->_db ( 'iwide_r1' )->query ( $sql, $para )->row();
			if($order_count->order_count == 0){
				goto end;
			}
			$mydata['order_count'] = $order_count->order_count;
			// $data[$date]['sql1'] = $this->_db ( 'iwide_r1' )->last_query();
			$sql = "SELECT count(distinct(openid)) as user_count
					FROM `iwide_hotel_orders` 
					WHERE inter_id = '$inter_id' $where ";//总用户数
			$user_count = $this->_db ( 'iwide_r1' )->query ( $sql, $para )->row();
			$mydata['user_count'] = $user_count->user_count;
			// $data[$date]['sql2'] = $this->_db ( 'iwide_r1' )->last_query();
			
			$mycount  = array(2,3,5,10);//统计的次数
			foreach ($mycount as $i) { 
				$sql = "SELECT count(id) as ordercount
						FROM `iwide_hotel_orders` 
						WHERE inter_id = '$inter_id' $where GROUP BY openid having ordercount>=$i";//总用户数
				$count = $this->_db ( 'iwide_r1' )->query ( $sql, $para )->result_array();
				$mydata['count'.$i] = count($count);
				$mydata['u'.$i] = round(($mydata['count'.$i]/$mydata['user_count'])*100,2);//用户复购率
				$allcount = 0;//订单复购分子
				foreach ($count as $c) {
					$allcount = $allcount + $c['ordercount'] - $i + 1;
				}
				$mydata['allcount'.$i] = $allcount;
				$mydata['o'.$i] = round(($mydata['allcount'.$i]/$mydata['order_count'])*100,2);//订单复购率
				// $data[$date]['sql3'][] = $this->_db ( 'iwide_r1' )->last_query();
			}
			end:
			$data[] = $mydata;
			$year = substr($date,0,4);
			$month = substr($date,4,2);
			$datearr = $this->calendar->adjust_date($month+1, $year);
			$date = $datearr['year'].$datearr['month'];
		}
		return $data;
	}


    function get_all_orders_by_roomnight($params, $limit = NULL, $offset = 0, $disp_params = array()) {
        ini_set ( 'memory_limit', '1024M' );
        $inter_id = $params ['inter_id'];
        $where = '';
        $para = array ();
        // if (isset ( $params ['order_status'] ) && $params ['order_status'] !== '') {
        $where .= ' and items.istatus = ? ';
        $para [] = 3;
        // } else {
        // $where .= ' and items.istatus not in (9,10) ';
        // }
        if (! empty ( $params ['hotel_id'] )) {
            $where .= ' and orders.hotel_id = ? ';
            $para [] = $params ['hotel_id'];
        }
        if (! empty ( $params ['orderid'] )) {
            $where .= ' and orders.orderid = ? ';
            $para [] = $params ['orderid'];
        }
        if (! empty ( $params ['web_orderid'] )) {
            $where .= ' and additions.web_orderid = ? ';
            $para [] = $params ['web_orderid'];
        }
        if (! empty ( $params ['in_name'] )) {
            $where .= ' and orders.name like ? ';
            $para [] = '%' . $params ['in_name'] . '%';
        }
        if (! empty ( $params ['in_tel'] )) {
            $where .= ' and orders.tel = ? ';
            $para [] = $params ['in_tel'];
        }
        if (! empty ( $params ['order_time_start'] )) {
            $where .= ' and orders.order_time >= ? ';
            $para [] = strtotime ( $params ['order_time_start'] );
        }
        if (! empty ( $params ['order_time_end'] )) {
            $where .= ' and orders.order_time <= ? ';
            $para [] = strtotime ( $params ['order_time_end'] ) + 86399;
        }
        if (! empty ( $params ['start_date_start'] )) {
            $where .= ' and items.startdate >= ? ';
            $para [] = date ( 'Ymd', strtotime ( $params ['start_date_start'] ) );
        }
        if (! empty ( $params ['start_date_end'] )) {
            $where .= ' and items.startdate <= ? ';
            $para [] = date ( 'Ymd', strtotime ( $params ['start_date_end'] ) );
        }
        if (! empty ( $params ['end_date_start'] )) {
            $where .= ' and items.enddate >= ? ';
            $para [] = date ( 'Ymd', strtotime ( $params ['end_date_start'] ) );
        }
        if (! empty ( $params ['end_date_end'] )) {
            $where .= ' and items.enddate <= ? ';
            $para [] = date ( 'Ymd', strtotime ( $params ['end_date_end'] ) );
        }
        $limit_s = ' order by orders.id desc ';
        if (! is_null ( $limit )) {
            $limit_s .= ' limit ?,? ';
            $para [] = $offset;
            $para [] = $limit;
        }
        $selects = ' orders.inter_id,orders.roomnums,orders.name in_name,orders.tel in_tel,orders.order_time,orders.paytype paytype,orders.hotel_id o_hotel_id,orders.orderid o_orderid,orders.member_no,orders.status order_status
				   ,items.allprice iall,items.price_code_name,items.webs_orderid,items.id sub_orderid,items.roomname,items.iprice,items.startdate istart,items.enddate iend,items.istatus item_status,items.mt_room_id
				    ,additions.web_orderid web_orderid,additions.coupon_favour,additions.point_favour,additions.point_used_amount,additions.point_used_amount,additions.balance_part
				     ,member.level member_level';

        if(!empty($inter_id)){
            $inter_str='';
            $inter_arr=explode(',',$inter_id);
            $length=count($inter_arr);
            foreach($inter_arr as $key=>$arr){
                if($key==($length-1)){
                    $inter_str .='\''.$arr.'\'';
                }else{
                    $inter_str .='\''.$arr.'\',';
                }
            }
        }

        $sql = "SELECT $selects
			   FROM `iwide_hotel_order_items` items JOIN `iwide_hotel_orders` orders
				 ON items.inter_id=orders.inter_id and items.orderid=orders.orderid
				  JOIN `iwide_hotel_order_additions` additions
				    ON orders.inter_id=additions.inter_id and orders.orderid=additions.orderid
				     LEFT JOIN `iwide_member` member
				      ON member.inter_id=orders.inter_id  and member.openid=orders.openid
				     WHERE orders.inter_id in ($inter_str) $where $limit_s ";
        $order_data = $this->_db ( 'iwide_rw' )->query ( $sql, $para )->result_array ();
        if ($this->input->get('nobug')==1){
            echo $this->_db ( 'iwide_rw' )->last_query();
        }
        if (! empty ( $disp_params ['just_count'] )) {
            return count ( $order_data );
        }
        // echo $this->_db('iwide_rw')->last_query();exit;
        $this->load->model ( 'hotel/Hotel_model' );
        $hotels = $this->Hotel_model->get_all_hotels ( $inter_id, NULL, 'key' );

        $data = array ();
        $this->load->model ( 'hotel/Member_model' );
        $levels = $this->Member_model->get_member_levels ( $inter_id );
        $this->load->model ( 'common/Enum_model' );
        $status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
        $this->load->model ( 'pay/Pay_model' );
        $pay_ways = $this->Pay_model->get_pay_way ( array (
            'inter_id' => $inter_id,
            'module' => 'HOTEL',
            'key' => 'value'
        ) );
        $pay_ways ['bonus'] = new stdClass ();
        $pay_ways ['bonus']->pay_name = '积分兑换';
        $favours = array ();
        foreach ( $order_data as &$o ) {
            $o ['in_hotel_name'] = empty ( $hotels [$o ['o_hotel_id']] ) ? '' : $hotels [$o ['o_hotel_id']] ['name'];
            $o ['order_time'] = date ( 'Y-m-d H:i:s', $o ['order_time'] );
            $o ['istart'] = date ( 'Y-m-d', strtotime ( $o ['istart'] ) );
            $o ['iend'] = date ( 'Y-m-d', strtotime ( $o ['iend'] ) );
            $o ['days'] = ceil ( (strtotime ( $o ['iend'] ) - strtotime ( $o ['istart'] )) / 86400 );
            $o ['days'] = $o ['days'] <=0 ? 1 :$o ['days'];
            $o ['nums'] = 1;
            $o ['room_night'] = $o ['days'];
            $o ['ori_price'] = number_format ( array_sum ( explode ( ',', $o ['iall'] ) ), 2, '.', '' );
            if (! empty ( $disp_params ['xls'] )) {
                $o ['o_orderid'] = '`' . $o ['o_orderid'];
                if (!empty($o ['web_orderid']))
                    $o ['web_orderid'] = '`' . $o ['web_orderid'];
                if (!empty($o ['webs_orderid']))
                    $o ['webs_orderid'] = '`' . $o ['webs_orderid'];
            }
            if ($o ['roomnums'] == 1) {
                $o ['coupon_amount'] = $o ['coupon_favour'];
                $o ['point_amount'] = $o ['point_used_amount'];
                $o ['point_favour'] = $o ['point_favour'];
            } else {
                if (! empty ( $favours [$o ['o_orderid']] )) {
                    $o ['coupon_amount'] = $favours [$o ['o_orderid']] ['cf'];
                    $o ['point_amount'] = $favours [$o ['o_orderid']] ['pa'];
                    $o ['point_favour'] = $favours [$o ['o_orderid']] ['pf'];
                } else {
                    $avg_cf = intval ( $o ['coupon_favour'] / $o ['roomnums'] );
                    $avg_pa = intval ( $o ['point_used_amount'] / $o ['roomnums'] );
                    $avg_pf = intval ( $o ['point_favour'] / $o ['roomnums'] );
                    $favours [$o ['o_orderid']] ['cf'] = $avg_cf;
                    $favours [$o ['o_orderid']] ['pa'] = $avg_pa;
                    $favours [$o ['o_orderid']] ['pf'] = $avg_pf;
                    $o ['coupon_amount'] = $avg_cf + ($o ['coupon_favour'] - $avg_cf * $o ['roomnums']);
                    $o ['point_amount'] = $avg_pa + ($o ['point_used_amount'] - $avg_pa * $o ['roomnums']);
                    $o ['point_favour'] = $avg_pf + ($o ['point_favour'] - $avg_pf * $o ['roomnums']);
                }
            }
            $o ['balance_amount'] = 0;
            if ($o ['paytype'] == 'balance') {
                $o ['balance_amount'] = $o ['ori_price'] - $o ['coupon_favour'] - $o ['point_favour'];
            }
            if (! empty ( $o ['balance_part'] )) {
                if ($o ['roomnums'] == 1) {
                    $o ['balance_amount'] += $o ['balance_part'];
                } else {
                    if (! empty ( $favours [$o ['o_orderid']] ['bp'] )) {
                        $o ['balance_amount'] += $favours [$o ['o_orderid']] ['bp'];
                    } else {
                        $avg_bp = intval ( $o ['balance_part'] / $o ['roomnums'] );
                        $favours [$o ['o_orderid']] ['bp'] = $avg_bp;
                        $o ['balance_amount'] += $avg_bp + ($o ['balance_part'] - $avg_bp * $o ['roomnums']);
                    }
                }
            }
            $o ['paytype'] = isset ( $pay_ways [$o ['paytype']] ) ? $pay_ways [$o ['paytype']]->pay_name : $o ['paytype'];
            $o ['member_level'] = isset ( $levels [$o ['member_level']] ) ? $levels [$o ['member_level']] : $o ['member_level'];
            $o ['item_status'] = $status_des [$o ['item_status']];
        }

        return $order_data;
    }

    function get_nodeal_data($saler_date,$deal_times){
		$db_read = $this->load->database('iwide_r1',true);

    	$row = $db_read->get_where ( 'hotel_saler_order_statistics', array (
				'saler_date' => $saler_date,
				'deal_times' => $deal_times,
				'done_time' => null
		) )->row_array ();
		if(empty($row))
			return false;
		return $row;
    }
}
