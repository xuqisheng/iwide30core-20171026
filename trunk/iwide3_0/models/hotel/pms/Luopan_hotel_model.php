<?php
class Luopan_hotel_model extends CI_Model {
	const TAB_HO = 'hotel_orders';
	function __construct() {
		parent::__construct ();
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array(), $pms_set = array()) {
		$days = get_room_night($condit ['startdate'],$condit ['enddate'],'round',$condit);//至少有一个间夜
		$web_rids = array ();
		foreach ( $rooms as $r ) {
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		$this->load->model ( 'common/Webservice_model' );
		$level_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
				'member_level',
				'basic_price_code'
		), 1, 'l2w' );
		$web_level = NULL;
		if ($pms_set ['pms_room_state_way'] == 3 && isset ( $level_reflect ['basic_price_code'] )) {
			$basic_price_code = $level_reflect ['basic_price_code'];
			$web_level = current ( $basic_price_code );
		}
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		
		$params = array (
				'idents' => $idents,
				'condit' => $condit,
				'web_rids' => $web_rids,
				'days' => $days,
				'basic_code' => $web_level,
				'multi_rooms'=>!empty($pms_auth['multi_rooms']),
		);
		$pms_data = $this->get_web_roomtype ( $pms_set, $condit ['startdate'], $condit ['enddate'], $params );
		$day_diff = round ( (strtotime ( $condit ['startdate'] ) - strtotime ( date ( 'Ymd' ) )) / 86400 );
		// $discount_des = $this->get_rooms_discount_des ( $pms_set, array (
		// 'days' => $days,
		// 'day_diff' => $day_diff,
		// 'startdate' => $condit ['startdate'],
		// 'enddate' => $condit ['enddate']
		// ) );
		// $params ['discount_des'] = $discount_des;
		$discount_data = array ();
		// if (! empty ( $discount_des )) {
		// $discount_data = $this->get_rooms_discount ( $pms_set, $condit ['startdate'], $condit ['enddate'], $params );
		// }
		
		
		if (! empty ( $pms_data )) {
			$allprice = array ();
			$this->load->model('hotel/Member_model');
			$levels = $this->Member_model->get_member_levels ( $idents ['inter_id'] );
			$member_level = $condit ['member_level'];
			$params ['member_level'] = $member_level;
			$params ['levels'] = $levels;
			$params ['level_reflect'] = $level_reflect;
			$params ['discount_data'] = $discount_data;
			switch ($pms_set ['pms_room_state_way']) {
				case 1 :
				case 2 :
					return $this->get_rooms_change_allpms ( $pms_data, array (
							'rooms' => $rooms
					), $params );
					break;
				case 3 :
					return $this->get_rooms_change_lmem ( $pms_data, array (
							'rooms' => $rooms
					), $params );
					break;
				default :
					return array ();
					break;
			}
		}
	}
	function get_rooms_change_allpms($pms_state, $rooms, $params) {
		$data = array ();
		foreach ( $rooms ['rooms'] as $rm ) {
			if (! empty ( $pms_state ['pms_state'] [$rm ['webser_id']] )) {
				$data [$rm ['room_id']] ['room_info'] = $rm;
				// $data [$rm ['room_id']] ['state_info'] = empty ( $pms_state ['valid_state'] [$rm ['webser_id']] ) ? array () : $pms_state ['valid_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['state_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				// $data [$rm ['room_id']] ['show_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['lowest'] = min ( $pms_state ['exprice'] [$rm ['webser_id']] );
				$data [$rm ['room_id']] ['highest'] = max ( $pms_state ['exprice'] [$rm ['webser_id']] );
			}
		}
		return $data;
	}
	function get_rooms_change_lmem($pms_data, $rooms, $params) {
		$data = array ();
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_level'];
		$condit = $params ['condit'];
		$this->load->model ( 'hotel/Order_model' );
		$params ['condit'] ['only_room_info'] = 1;
		$data = $this->Order_model->get_rooms_change ( $local_rooms, $params ['idents'], $params ['condit'] );
		$pms_state = $pms_data ['pms_state'];
		
		// var_dump($data);
		foreach ( $data as $room_key => $lrm ) {
			$min_price = array ();
			if (empty ( $pms_state [$lrm ['room_info'] ['webser_id']] )) {
				unset ( $data [$room_key] );
				continue;
			}
			if (! empty ( $lrm ['state_info'] )) {
				foreach ( $lrm ['state_info'] as $sik => $si ) {
					$external_code_reflect = NULL;
					if ($si ['external_code'] !== '') {
						if(isset($params ['level_reflect'] ['basic_price_code'] [$si ['external_code']])){
							$external_code_reflect = $params ['level_reflect'] ['basic_price_code'] [$si ['external_code']];
						}else{
							$external_code_reflect=$si['external_code'];
						}
					}
					if (isset ( $external_code_reflect ) && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect] )) {
						// if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						if(empty($params['multi_rooms'])){
							$tmp ['least_num'] = $tmp ['least_num'] > 1 ? 1 : $tmp ['least_num'];
						}else{
							$tmp ['least_num'] = min($tmp ['least_num'],5);
						}
						if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member') {
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						} else {
							if (isset ( $si ['condition'] ['member_level'] ) && $si ['condition'] ['member_level'] != $member_level) {
								unset ( $data [$room_key] ['state_info'] [$sik] );
								continue;
							}
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $data [$room_key] ['state_info'] [$sik] ['least_num'] <= $tmp ['least_num'] ? $data [$room_key] ['state_info'] [$sik] ['least_num'] : $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
							if ($data [$room_key] ['state_info'] [$sik] ['least_num'] <= 0) {
								$data [$room_key] ['state_info'] [$sik] ['book_status'] = 'full';
							}
						}
						
						$data [$room_key] ['state_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];
						$allprice = '';
						$amount = 0;
						foreach ( $tmp ['date_detail'] as $dk => $td ) {
							
							if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member'||(!empty($si ['related_cal_value'])&&!empty($si ['related_cal_way']))) {
								$tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
							} else {
								$tmp ['date_detail'] [$dk] ['price'] = round ( $data [$room_key] ['state_info'] [$sik] ['date_detail'] [$dk] ['price'] );
							}
							$tmp ['date_detail'] [$dk] ['nums'] = $data [$room_key] ['state_info'] [$sik] ['least_num'];
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
						
						$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
						$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['state_info'] [$sik] ['total'] = intval ( $amount );
						$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
						$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
						
						// } else {
						// unset ( $data [$room_key] ['state_info'] [$sik] );
						// }
						// } else {
						// $min_price [] = $si ['avg_price'];
					}else{
						if($si['avg_price']<=0){
							unset($data[$room_key]['state_info'][$sik]);
						}
					}
				}
				$data [$room_key] ['lowest'] = empty ( $min_price ) ? 0 : min ( $min_price );
				$data [$room_key] ['highest'] = empty ( $min_price ) ? 0 : max ( $min_price );
				foreach ( $lrm ['show_info'] as $sik => $si ) {
// 					if (isset ( $si ['condition'] ['member_level'] ) && $si ['price_type'] != 'member') {
// 						unset ( $data [$room_key] ['show_info'] [$sik] );
// 						continue;
// 					}
					if ($si ['external_code'] !== '') {
						if(isset($params ['level_reflect'] ['basic_price_code'] [$si ['external_code']])){
							$external_code_reflect = $params ['level_reflect'] ['basic_price_code'] [$si ['external_code']];
						}else{
							$external_code_reflect=$si['external_code'];
						}
					}
					if (isset ( $external_code_reflect ) && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect] )) {
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						$tmp ['least_num'] = $tmp ['least_num'] > 1 ? 1 : $tmp ['least_num'];
						$data [$room_key] ['show_info'] [$sik] ['least_num'] = $tmp ['least_num'];
						$data [$room_key] ['show_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						$allprice = '';
						$amount = 0;
						foreach ( $tmp ['date_detail'] as $dk => $td ) {
							$tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
							$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['show_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
						
						$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
						$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['show_info'] [$sik] ['total'] = intval ( $amount );
						$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
					} else {
						unset ( $data [$room_key] ['show_info'] [$sik] );
					}
				}
			}
			if (! empty ( $params ['discount_data'] [$lrm ['room_info'] ['webser_id']] )) {
				foreach ( $params ['discount_data'] [$lrm ['room_info'] ['webser_id']] as $dd ) {
					$sik = $dd ['price_code'];
					$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
					$dd ['least_num'] = $dd ['least_num'] > 1 ? 1 : $dd ['least_num'];
					$data [$room_key] ['state_info'] [$sik] ['price_code'] = $dd ['price_code'];
					$data [$room_key] ['state_info'] [$sik] ['price_name'] = $dd ['price_name'];
					$data [$room_key] ['state_info'] [$sik] ['least_num'] = $dd ['least_num'];
					$data [$room_key] ['state_info'] [$sik] ['des'] = $dd ['des'];
					$data [$room_key] ['state_info'] [$sik] ['price_type'] = $dd ['price_type'];
					$data [$room_key] ['state_info'] [$sik] ['book_status'] = $dd ['book_status'];
					$data [$room_key] ['state_info'] [$sik] ['extra_info'] = $dd ['extra_info'];
					$allprice = '';
					$amount = 0;
					foreach ( $dd ['date_detail'] as $dk => $td ) {
						$dd ['date_detail'] [$dk] ['price'] = round ( $td ['price'] );
						$dd ['date_detail'] [$dk] ['nums'] = $dd ['least_num'];
						$allprice .= ',' . $dd ['date_detail'] [$dk] ['price'];
						$amount += $dd ['date_detail'] [$dk] ['price'];
					}
					$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $dd ['date_detail'];
					
					$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
					$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
					$data [$room_key] ['state_info'] [$sik] ['total'] = intval ( $amount );
					$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
					$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
				}
			}
			if (empty ( $data [$room_key] ['state_info'] )) {
				unset ( $data [$room_key] );
			}
		}
		return $data;
	}
	function get_web_roomtype($pms_set, $startdate, $enddate, $params) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . 'search_hotel_rates?' . $pms_auth ['url_auth'];
		if (isset ( $params ['basic_code'] )) {
			$code_des = array (
					$params ['basic_code'] => array (
							'name' => '基础价'
					)
			);
			$search_codes = $params ['basic_code'];
		} else {
			$code_des = $this->get_rate_type_list ( $pms_auth, $pms_set ['inter_id'] );
			$search_codes = array_keys ( $code_des );
			$search_codes = implode ( ',', $search_codes );
		}
		$data = array (
				"condition.hotel_id" => $pms_set ['hotel_web_id'],
				"condition.hotelgroup_id" => "",
				"condition.check_in_date" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"condition.check_out_date" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"condition.rate_codes" => $search_codes,
				"condition.room_quantity" => "0",
				"condition.room_type_id" => "",
				"condition.city_name" => "",
				"condition.city_code" => "",
				"condition.name_or_address" => "",
				"condition.latitude" => "",
				"condition.longitude" => "",
				"condition.biz_source_id" => "13",
				"condition.brand" => "",
				"condition.rate_promotion_id" => "",
				"condition.page" => "",
				"condition.page_size" => ""
		);
		$res = $this->get_to ( $url, $data, $pms_set ['inter_id'] );
		$pms_state = array ();
		$exprice = array ();
		if (! empty ( $res ['room_avail_records'] )) {
			$states = current ( $res ['room_avail_records'] );
			
			if (! empty ( $states ['room_type_statuses'] )) {
				
				// var_dump($search_codes);
				// var_dump($states);
				// exit;
				foreach ( $states ['room_type_statuses'] as $rl ) {
					foreach ( $rl ['prices'] as $web_code => $rlp ) {
						if (! empty ( $code_des [$web_code] )) {
							$pms_state [$rl ['room_type_id']] [$web_code] ['price_name'] = $code_des [$web_code] ['name'];
							$pms_state [$rl ['room_type_id']] [$web_code] ['price_type'] = 'pms';
							$pms_state [$rl ['room_type_id']] [$web_code] ['extra_info'] = array (
									'type' => 'code',
									'pms_code' => $web_code
							);
							$pms_state [$rl ['room_type_id']] [$web_code] ['price_code'] = $web_code;
							$pms_state [$rl ['room_type_id']] [$web_code] ['des'] = '';
							$pms_state [$rl ['room_type_id']] [$web_code] ['sort'] = 0;
							$pms_state [$rl ['room_type_id']] [$web_code] ['disp_type'] = 'buy';
							
							$allprice = '';
							$amount = '';
							for($i = 0; $i < $params ['days']; $i ++) {
								$pms_state [$rl ['room_type_id']] [$web_code] ['date_detail'] [date ( 'Ymd', strtotime ( '+ ' . $i . ' day ', strtotime ( $startdate ) ) )] = array (
										'price' => $rlp [$i],
										'nums' => $rl ['quantities'] [$i]
								);
								$allprice .= ',' . $rlp [$i];
								$amount += $rlp [$i];
							}
							if ($amount <= 0) {
								unset ( $pms_state [$rl ['room_type_id']] [$web_code] );
								continue;
							}
							if (isset ( $params ['web_rids'] [$rl ['room_type_id']] )) {
								$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['room_type_id']]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['room_type_id']]];
							} else {
								$nums = 1;
							}
							$pms_state [$rl ['room_type_id']] [$web_code] ['allprice'] = substr ( $allprice, 1 );
							$pms_state [$rl ['room_type_id']] [$web_code] ['total'] = $amount;
							$pms_state [$rl ['room_type_id']] [$web_code] ['related_des'] = '';
							$pms_state [$rl ['room_type_id']] [$web_code] ['total_price'] = $amount * $nums;
							$pms_state [$rl ['room_type_id']] [$web_code] ['avg_price'] = number_format ( $amount / $params ['days'], 2, '.', '' );
							;
							$pms_state [$rl ['room_type_id']] [$web_code] ['price_resource'] = 'webservice';
							$pms_state [$rl ['room_type_id']] [$web_code] ['least_num'] = min ( $rl ['quantities'] );
							$book_status = 'full';
							if ($pms_state [$rl ['room_type_id']] [$web_code] ['least_num'] >= $nums)
								$book_status = 'available';
							$pms_state [$rl ['room_type_id']] [$web_code] ['book_status'] = $book_status;
							$exprice [$rl ['room_type_id']] [] = $pms_state [$rl ['room_type_id']] [$web_code] ['avg_price'];
							// if ($room_detail ['canBook'] == 1) {
							// $valid_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] = $pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']];
							// }
						}
					}
				}
			}
		}
		return array (
				'pms_state' => $pms_state,
				'exprice' => $exprice
		);
	}
	
	/*
	 * get_rate_type_list	查看可用价格代码列表
	 */
	public function get_rate_type_list($pms_auth, $inter_id = '') {
		$url = $pms_auth ['url'] . 'get_rate_type_list?' . $pms_auth ['url_auth'];
		$res = $this->get_to ( $url, array (), $inter_id );
		$web_codes = array ();
		if (! empty ( $res ['rate_types'] )) {
			foreach ( $res ['rate_types'] as $rr ) {
				$web_codes [$rr ['rate_code']] = $rr;
			}
		}
		return $web_codes;
		/*
		 * 返回的信息：
		 * {"rate_types":[{"rate_code":"159_A","name":"a","min_days":0,"advance_day":0},{"rate_code":"159_AAAA","name":"旅游节促销","min_days":0,"advance_day":0},{"rate_code":"159_ABCDE","name":"集团会员八折价","min_days":0,"advance_day":0},{"rate_code":"159_CJCXJ","name":"春节促销价","min_days":0,"advance_day":0},{"rate_code":"159_HYJ","name":"会员价","min_days":0,"advance_day":0},{"rate_code":"159_HYJG","name":"会员","min_days":0,"advance_day":0},{"rate_code":"159_JZBF","name":"铂金卡","min_days":0,"advance_day":0},{"rate_code":"159_LZT","name":"连住三天","min_days":0,"advance_day":0},{"rate_code":"159_TEST","name":"TEST","min_days":0,"advance_day":0},{"rate_code":"159_ZZK","name":"自尊卡价","min_days":0,"advance_day":0},{"rate_code":"COMPANY","name":"普通公司协议价","min_days":0,"advance_day":0},{"rate_code":"LIST","name":"门市价","min_days":0,"advance_day":0},{"rate_code":"TEAM","name":"普通团队价","min_days":0,"advance_day":0},{"rate_code":"USER","name":"网站注册会员价","min_days":0,"advance_day":0},{"rate_code":"WEB","name":"普通订房中心价","min_days":0,"advance_day":0}]}
		 */
	}
	function cancel_order_web($inter_id, $order, $pms_set = array()) {
		$web_orderid = isset ( $order ['web_orderid'] ) ? $order ['web_orderid'] : "";
		if (! $web_orderid) {
			return array (
					's' => 0,
					'errmsg' => '取消失败'
			);
		}
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . 'cancel_order?' . $pms_auth ['url_auth'];
		
		$data = array (
				"room_order_id" => $web_orderid,
				"mobile_or_email" => $order ['tel'],
				"cancel_reason" => ""
		);
		$res = $this->get_to ( $url, $data, $inter_id );
		if (! empty ( $res ['room_order_id'] )) {
			return array (
					's' => 1,
					'errmsg' => '取消成功'
			);
		}
		return array (
				's' => 0,
				'errmsg' => '取消失败'
		);
	}
	public function update_web_order($inter_id, $list, $params) {
		$pms_auth = json_decode ( $params ['pms_set'] ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . 'get_order_detail?' . $pms_auth ['url_auth'];
		$data = array (
				"room_order_id" => $list ['web_orderid'],
				"mobile_or_email" => $list ['tel']
		);
		$res = $this->get_to ( $url, $data, $inter_id );
		$new_status = null;
		$istatus=-1;
		if (! empty ( $res ['order'] )) {
			$status_arr = $this->pms_enum ( 'status' );
			$new_status = $status_arr [$res ['order'] ['order_status']];
			
			if ($list['status'] == 4 && $new_status == 5) {
				$new_status = 4;
			}
			$this->load->model ( 'hotel/Order_model' );
			
			if(!empty($res['registers'])){
				is_array(current($res['registers'])) or $res['registers']=[$res['registers']];
				//本地订单列表是否已更新过入住ID
				$local_items = [];
				$local_noitem = [];
				
				foreach($list['order_details'] as $od){
					if(!empty($od['webs_orderid'])){
						$local_items[$od['webs_orderid']] = $od;
					}else{
						$local_noitem[] = $od;
					}
				}
				
				$order_count=count($list['order_details']);
				if($new_status==3&&count($res['registers']) < $order_count){
					//离店时，判断返回的子订单数据
					$limit_count = $order_count - count($res['registers']);
					
					//缺少的子订单数据与主订单一致，
					$web_order_copy = end($res['registers']);
					//若订单状态为入住/离店时，复制的订单状态为取消
					$web_order_copy['istatus'] = 4;
					//子订单号改为空值
					$web_order_copy['id'] = '';
					
					for($i = 0; $i < $limit_count; $i++){
						$res['registers'][] = $web_order_copy;
					}
				}
				
				$i = 0;
				foreach($res['registers'] as $v){
					$updata = [];
					if(isset($local_items[$v['id']])){
						$od = $local_items[$v['id']];
					}else{
						//不存在本地订单中
						$od = $local_noitem[$i];
						//有子订单号时才执行更新操作
						if($v['id'])
							$this->db->where(['id' => (int)$od['sub_id']])->update('hotel_order_items', ['webs_orderid' => $v['id']]);

//							$updata['webs_orderid'] = $v['FolioID'];
						$i++;
					}
					
					//存在istatus是，酒店取消
					if(!empty($v['istatus'])&&$v['istatus']==4){
						$istatus=$v['istatus'];
					}else{
						$istatus=$new_status;
						
						//判断日期，价格
						//PMS上的入住，离店时间
						$web_start = date('Ymd', $v['check_in_date']);
						$web_end = date('Ymd', $v['check_out_date']);
						$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
						
						//判断实际入住时间，订单记录的入住时间
						$ori_day_diff = get_room_night($od['startdate'], $od['enddate'], 'ceil', $od);//至少有一个间夜
						$web_day_diff = get_room_night($web_start, $web_end, 'ceil');//至少有一个间夜
						$day_diff = $web_day_diff - $ori_day_diff;
						
						$updata['startdate'] = $web_start;
						$updata['enddate'] = $web_end;
						if($day_diff != 0 || $web_start != $od['startdate'] || $web_end != $od['enddate']){
							$updata['no_check_date'] = 1;
						}
						
						$new_price=$od['room_rate'];
						if($new_price >= 0 && $new_price != $od['iprice']){
							$updata['no_check_date'] = 1;
							$updata['new_price'] = $new_price;
						}
					}
					
					if($istatus != $od['istatus']&&$istatus!=-1){
						$updata['istatus'] = $istatus;
					}
					
					if(!empty ($updata)){
						$this->Order_model->update_order_item($inter_id, $list['orderid'], $od['sub_id'], $updata);
					}
					
				}
				
			}else{
				if ($new_status != $list ['status']) {
					$this->db->where ( array (
						'inter_id' => $list ['inter_id'],
						'orderid' => $list ['orderid']
					) );
					$this->db->update ( 'hotel_orders', array (
						'status' => $new_status
					) );
					$this->Order_model->handle_order ( $inter_id, $list ['orderid'], $new_status, $list ['openid'] );
				}
			}
			
		}
		return $new_status;
	}
	function pms_enum($type,$key=null) {
		$arr=[];
		switch ($type) {
			case 'status' :
				$arr= array (
						'0' => 1,
						'1' => 2,
						'2' => 3,
						'3' => 5,
						'4' => 8
				);
				break;
			case 'func_name':
				$arr=[
					'search_hotel_rates' => '获取房价',
					'create_order'=>'新增订单',
					'get_order_detail'=>'查询订单',
					'cancel_order'=>'取消订单',
					'deposit_order'=>'网络支付入账',
				];
				break;
		}
		
		if($key === null){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}
	function order_to_web($inter_id, $orderid, $paras = array(), $pms_set = array()) {
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $inter_id, array ( // 取订单信息，包含订单主单信息和子单信息
				'orderid' => $orderid,
				'idetail' => array (
						'i'
				)
		) );
		if (! empty ( $order )) {
			$order = $order [0]; // 订单信息
			$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
			$room_codes = json_decode ( $order ['room_codes'], TRUE );
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			
			$url = $pms_auth ['url'] . 'create_order?' . $pms_auth ['url_auth'];
			$payment_mode_id = 0;
			// if (! empty ( $paras ['trans_no'] )) {
			if ($order ['paytype'] == 'weixin') {
				$payment_mode_id = 7;
			}
			$custom_price = '';
			$allprice = explode ( ',', $order ['first_detail'] ['allprice'] );
			$total_favour = $order ['coupon_favour'] + $order ['point_favour'];
			$favour = number_format ( $total_favour / $order ['roomnums'], 2, '.', '' );
			if ($favour > 0) {
				for($i = 0; $i < count ( $allprice ); $i ++) {
					if ($allprice [$i] >= $favour) {
						$allprice [$i] -= $favour;
						$favour = 0;
						break;
					} else {
						$favour -= $allprice [$i];
						$allprice [$i] = 0;
					}
					if ($favour == 0)
						break;
				}
			}
			$i = 0;
			foreach ( $allprice as $ap ) {
				$custom_price .= '|' . date ( 'Y-m-d', strtotime ( '+ ' . $i . 'day', strtotime ( $order ['first_detail'] ['startdate'] ) ) ) . '#' . $ap;
				$i ++;
			}
			
			$this->load->model('api/Vmember_model', 'vm');
			$member = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
			
			$custom_price = substr ( $custom_price, 1 );
			$rate_code = $room_codes ['code'] ['extra_info'] ['pms_code'];
			$favour_info = $order['coupon_favour']>0? '使用优惠券：' . $order['coupon_favour'] . '。':'';
			$favour_info .= $order['point_favour']>0 ?  '积分使用 数量/抵扣：' . $order['point_used_amount'].'/'.$order['point_favour'] . '。':'';
			$favour_info.=$order['paytype']=='point'?'使用积分【'.$order['point_used_amount'].'】兑换':'';
			$data = array (
					"order.room_order_id" => "",
					"order.hotel_id" => $pms_set ['hotel_web_id'],
					"order.currency_code" => isset($pms_auth['currency_code'])?$pms_auth['currency_code']:'',
					"order.exchange_rate" => "1.0",
					"order.room_type_id" => $room_codes ['room'] ['webser_id'],
					"order.room_quantity" => $order ['roomnums'],
					"order.adult_quantity" => '1',
					"order.child_quantity" => '0',
					"order.check_in_date" => date ( 'Y-m-d', strtotime ( $order ['startdate'] ) ),
					"order.check_out_date" => date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ),
					"order.rate_code" => $rate_code,
					"order.total_order_money" => $order ['price'],
					"order.total_score" => "0.0",
					"order.contacter" => $order ['name'],
					"order.mobile" => $order ['tel'],
					"order.email" => "",
					"order.phone" => $order ['tel'],
					"order.fax" => "",
					"order.note" => "微信订单/" . $order['first_detail']['price_code_name'].'。'.$favour_info,
					"order.payment_mode_id" => $payment_mode_id,
					"order.arrive_info" => "",
					"order.earliest_arrive" => "",
					"order.latest_arrive" => "",
//					 "order.card_type_id" => !empty($member['pms_user_id'])?$member['lvl_pms_code']:'', // 103630
//					 "order.card_no" => !empty($member['pms_user_id'])?$member['membership_number']:'', // 50005409
					"order.voucher_type_id" => '',
					"order.voucher_no" => '',
					"order.voucher_count" => '',
					"order.reserve_hour" => '',
					"order.guarantee_score" => '',
					"order.biz_source_id" => "13", // 13 微信
					"order.rate_promotion_id" => '',
					"order.ip" => "",
					"order.referer_url" => '',
					"order.custom_price" => $custom_price
			); // 格式：{日期}#{房价}|{日期}#{房价}，例如2008-09-08#99|2008-09-09#528|2008-09-10#528
			
			if($member['logined']&&!empty($pms_auth['order_card_no'])){
				$data['order.card_type_id']=$member['lvl_pms_code'];
				$data['order.card_no']=$member['membership_number'];
			}
			
			if($order['paytype']=='point'){
				$data['order.total_score']=$order['point_used_amount'];
			}
			
			
			if(!empty($room_codes['code']['extra_info']['discount_id'])&&$order['inter_id']=='a452223043'){
				$data ['order.rate_promotion_id'] = $room_codes ['code'] ['extra_info'] ['discount_id'];
				if (! empty ( $room_codes ['code'] ['extra_info'] ['need_member'] )) {
					$data ['order.card_type_id'] = '103630';
					$data ['order.card_no'] = '50005409';
				}
			}
// 			echo $url;
// 			var_dump ( $data );
// 			exit ();
			$res = $this->get_to ( $url, $data, $inter_id );
			if (! empty ( $res ['room_order_id'] )) {
				$web_orderid = $res ['room_order_id'];
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id']
				) );
				$this->db->update ( 'hotel_order_additions', array ( // 将pms的单号更新到相应订单
						'web_orderid' => $web_orderid
				) );
				// $this->Order_model->update_order_status ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
				$upstatus=null;
				$has_paid=null;
				if ($order ['status'] != 9) {
					$upstatus=1;
					/*$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id'] 
					) );
					$this->db->update ( 'hotel_orders', array (
							'status' => 1 
					) );
					$this->Order_model->handle_order ( $inter_id, $orderid, 1 );*/ // 若pms的订单是即时确认的，执行确认操作
				}
				
				if($order['paytype']=='point'){
					$this->load->model('hotel/Hotel_config_model');
					$config_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $order ['hotel_id'], array(
						'PMS_POINT_REDUCE_WAY',
						'POINT_PAY_WITH_BILL',
					    'NO_LOCAL_REDUCE'
					));
					
					if(!empty($config_data['PMS_POINT_REDUCE_WAY']) && $config_data['PMS_POINT_REDUCE_WAY'] == 'after'){
						$point_params = [
							'crsNo' => $web_orderid
						];
						
						if(!empty($config_data['NO_LOCAL_REDUCE'])){
							$point_params['extra']['no_local_reduce']=1;
						}elseif(!empty($config_data['POINT_PAY_WITH_BILL'])){
							$point_params['extra'] = [
								'deposit'      => $order['price'],
								'reserve_hour' => $pms_auth['reserve_hour'],
							];
							$point_params['extra']['point_pay_with_bill'] = 1;
						}
						
						$this->load->model('hotel/Member_model');
						
						if(!$this->Member_model->consum_point($order['inter_id'], $order['orderid'], $order['openid'], $order['point_used_amount'], $point_params)){
							$this->Order_model->update_point_reduce($inter_id, $order['orderid'], 3);
							$info = $this->Order_model->cancel_order($inter_id, array(
								'only_openid'   => $order ['openid'],
								'member_no'     => '',
								'orderid'       => $order['orderid'],
								'cancel_status' => 5,
								'no_tmpmsg'     => 1,
								'delete'        => 2,
								'idetail'       => array(
									'i'
								)
							));
							return array(
								's'      => 0,
								'errmsg' => '积分扣减失败'
							);
						}else{
							$has_paid = 1;
						}
						
					}
				}elseif($order['paytype']=='balance'){
					$this->load->model('hotel/Hotel_config_model');
					$config_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $order ['hotel_id'], array(
						'PMS_BANCLANCE_REDUCE_WAY',
						'BALANCE_PAY_WITH_BILL'
					));
					if(!empty($config_data['PMS_BANCLANCE_REDUCE_WAY']) && $config_data['PMS_BANCLANCE_REDUCE_WAY'] == 'after'){
						$this->load->model('hotel/Member_model');
						$sub_orders = [];
						foreach($order['order_details'] as $v){
							$sub_orders[$v['sub_id']] = $v['iprice'];
						}
						$balance_param = [
							'crsNo' => $web_orderid,
							'extra' => [
								'orders' => $sub_orders,
								'reserve_hour' => $pms_auth['reserve_hour'],
							],
						];
						
						if(!empty($room_codes['room']['consume_code'])){
							$balance_param['password']=$room_codes ['room']['consume_code'];
						}
						if(!empty($config_data['BALANCE_PAY_WITH_BILL'])){
							$balance_param['extra']['balance_pay_with_bill']=1;
						}
						
						$rs = $this->Member_model->reduce_balance($inter_id, $order['openid'], $order['price'], $order['orderid'], '订房订单余额支付', $balance_param, $order);
						if(!$rs){
							//支付失败，取消订单
							$info = $this->Order_model->cancel_order($inter_id, array(
								'only_openid'   => $order ['openid'],
								'member_no'     => '',
								'orderid'       => $order ['orderid'],
								'cancel_status' => 5,
								'no_tmpmsg'     => 1,
								'delete'        => 2,
								'idetail'       => array(
									'i'
								)
							));
							
							return [
								's'      => 0,
								'errmsg' => '储值支付失败！',
							];
						}
						
						$has_paid = 1;
					}
				}
				
				if (!empty($paras['trans_no'])) {
					$this->add_web_bill($web_orderid, $order, $pms_auth, $paras['trans_no']);
				}
				// 返回成功
				return array (
						's' => 1,
				        'upstatus'=>$upstatus,
				        'has_paid'=>$has_paid
				);
			} else {
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id']
				) );
				$this->db->update ( 'hotel_orders', array ( // 提交失败，把订单状态改为下单失败
						'status' => 10
				) );
				$errmsg = empty ( $res ['exception_description'] ) ? '提交订单失败' : $res ['exception_description'];
				return array ( // 返回失败
						's' => 0,
						'errmsg' => $errmsg
				);
			}
		}
		return array (
				's' => 0,
				'errmsg' => '提交订单失败'
		);
	}
	function add_web_bill($web_orderid, $order, $pms_auth, $trans_no = '') {
		// deposit_order
		$url = $pms_auth ['url'] . 'deposit_order?' . $pms_auth ['url_auth'];
		$data = array (
				"deposit_account.room_order_id" => $web_orderid,
				"deposit_account.money" => $order ['price'],
				"deposit_account.prepaid_source_code" => "weixin",
				"deposit_account.note" => "微信支付",
				"deposit_account.payment_seq" => $trans_no
		);
		$res = $this->get_to ( $url, $data, $order ['inter_id'] );
		$web_paid = 2;
		$s = FALSE;
		if (isset ( $res ['room_order_id'] )) {
			$web_paid = 1;
			$s = TRUE;
		}
		$this->db->where ( array (
				'orderid' => $order ['orderid'],
				'inter_id' => $order ['inter_id']
		) );
		$this->db->update ( 'hotel_order_additions', array ( // 将pms的单号更新到相应订单
				'web_paid' => $web_paid
		) );
		return $s;
	}
	function get_rooms_discount_rate($pms_set, $params) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . 'get_rate_promotion_list?' . $pms_auth ['url_auth'];
		$data = $this->get_to ( $url, array (), $pms_set ['inter_id'] );
		$discounts = array ();
		foreach ( $data as $d ) {
			$source_ids = explode ( '#', $d ['biz_source_ids'] );
			if (in_array ( 13, $source_ids )) {
				foreach ( $d ['rate_promotion_items'] as $dr ) {
					$effect_start = date ( 'Ymd', strtotime ( $d ['valid_date'] ) );
					$effect_end = date ( 'Ymd', strtotime ( $d ['expire_date'] ) );
					if ($dr ['hotel_id'] == $pms_set ['hotel_web_id'] && $params ['startdate'] >= $effect_start && $params ['enddate'] <= $effect_end && $params ['days'] >= $d ['min_days'] && $params ['day_diff'] >= $d ['min_advance']) {
						$room_codes = explode ( '#', $d ['room_code'] );
						$web_code = $dr ['rate_promotion_id'];
						foreach ( $room_codes as $rc ) {
							if (! empty ( $rc )) {
								$discounts [$rc] [$web_code] ['price_name'] = $d ['name'];
								$discounts [$rc] [$web_code] ['price_type'] = 'pms';
								$discounts [$rc] [$web_code] ['extra_info'] = array (
										'type' => 'discount',
										'pms_code' => $web_code
								);
								$discounts [$rc] [$web_code] ['price_code'] = $web_code;
								$discounts [$rc] [$web_code] ['des'] = '';
								$discounts [$rc] [$web_code] ['sort'] = 0;
								$discounts [$rc] [$web_code] ['disp_type'] = 'buy';
								
								$allprice = '';
								$amount = '';
								for($i = 0; $i < $params ['days']; $i ++) {
									$discounts [$rc] [$web_code] ['date_detail'] [date ( 'Ymd', strtotime ( '+ ' . $i . ' day ', strtotime ( $params ['startdate'] ) ) )] = array (
											'price' => $dr ['room_rate'],
											'nums' => 1
									);
									$allprice .= ',' . $dr ['room_rate'];
									$amount += $dr ['room_rate'];
								}
								if ($amount <= 0) {
									unset ( $discounts [$rc] [$web_code] );
									continue;
								}
								// if (isset ( $params ['web_rids'] [$rl ['room_type_id']] )) {
								// $nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['room_type_id']]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['room_type_id']]];
								// } else {
								// $nums = 1;
								// }
								$discounts [$rc] [$web_code] ['allprice'] = substr ( $allprice, 1 );
								$discounts [$rc] [$web_code] ['total'] = $amount;
								$discounts [$rc] [$web_code] ['related_des'] = '';
								$discounts [$rc] ['total_price'] = $amount;
								$discounts [$rc] [$web_code] ['avg_price'] = number_format ( $amount / $params ['days'], 2, '.', '' );
								$discounts [$rc] [$web_code] ['price_resource'] = 'webservice';
								$discounts [$rc] [$web_code] ['least_num'] = 1;
								$discounts [$rc] [$web_code] ['book_status'] = 'full';
							}
						}
					}
				}
			}
		}
		return $discounts;
	}
	function get_rooms_discount_des($pms_set, $params) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . 'get_rate_promotion_list?' . $pms_auth ['url_auth'];
		$data = $this->get_to ( $url, array (), $pms_set ['inter_id'] );
		$discounts = array ();
		foreach ( $data as $d ) {
			$source_ids = explode ( '#', $d ['biz_source_ids'] );
			if (in_array ( 13, $source_ids )) {
				foreach ( $d ['rate_promotion_items'] as $dr ) {
					$effect_start = date ( 'Ymd', strtotime ( $d ['valid_date'] ) );
					$effect_end = date ( 'Ymd', strtotime ( $d ['expire_date'] ) );
					if ($dr ['hotel_id'] == $pms_set ['hotel_web_id'] && $params ['startdate'] >= $effect_start && $params ['enddate'] <= $effect_end && $params ['days'] >= $d ['min_days'] && $params ['day_diff'] >= $d ['min_advance']) {
						$web_code = $dr ['rate_promotion_id'];
						$discounts [$dr ['rate_promotion_id']] ['discount_id'] = $dr ['rate_promotion_id'];
						$discounts [$dr ['rate_promotion_id']] ['discount_name'] = $d ['name'];
						$discounts [$dr ['rate_promotion_id']] ['need_member'] = $d ['need_member'];
					}
				}
			}
		}
		return $discounts;
	}
	function get_rooms_discount($pms_set, $startdate, $enddate, $params) {
		$room_discounts = array ();
		if (! empty ( $params ['discount_des'] )) {
			$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
			$url = $pms_auth ['url'] . 'search_hotel_rates?' . $pms_auth ['url_auth'];
			$discount = $params ['discount_des'];
			if (isset ( $params ['basic_code'] )) {
				$code_des = array (
						$params ['basic_code'] => array (
								'name' => '基础价'
						)
				);
				$search_codes = $params ['basic_code'];
			} else {
				$code_des = $this->get_rate_type_list ( $pms_auth, $pms_set ['inter_id'] );
				$search_codes = array_keys ( $code_des );
				$search_codes = implode ( ',', $search_codes );
			}
			foreach ( $discount as $k => $ddes ) {
				$data = array (
						"condition.hotel_id" => $pms_set ['hotel_web_id'],
						"condition.hotelgroup_id" => "",
						"condition.check_in_date" => date ( 'Y-m-d', strtotime ( $startdate ) ),
						"condition.check_out_date" => date ( 'Y-m-d', strtotime ( $enddate ) ),
						"condition.rate_codes" => $search_codes,
						"condition.room_quantity" => "0",
						"condition.room_type_id" => "",
						"condition.city_name" => "",
						"condition.city_code" => "",
						"condition.name_or_address" => "",
						"condition.latitude" => "",
						"condition.longitude" => "",
						"condition.biz_source_id" => "13",
						"condition.brand" => "",
						"condition.rate_promotion_id" => $ddes ['discount_id'],
						"condition.page" => "",
						"condition.page_size" => ""
				);
				$discount [$k] ['discount_data'] = $this->get_to ( $url, $data, $pms_set ['inter_id'] );
			}
			// var_dump ( $discount );
			// exit ();
			$pms_state = array ();
			$exprice = array ();
			foreach ( $discount as $k => $res ) {
				if (! empty ( $res ['discount_data'] )) {
					$states = current ( $res ['discount_data'] ['room_avail_records'] );
					// var_dump($states);exit;
					if (! empty ( $states ['room_type_statuses'] )) {
						foreach ( $states ['room_type_statuses'] as $rl ) {
							foreach ( $rl ['prices'] as $code => $rlp ) {
								if (! empty ( $code_des [$code] )) {
									$web_code = $res ['discount_id'];
									$pms_state [$rl ['room_type_id']] [$web_code] ['price_name'] = $res ['discount_name'];
									$pms_state [$rl ['room_type_id']] [$web_code] ['price_type'] = 'pms';
									$pms_state [$rl ['room_type_id']] [$web_code] ['extra_info'] = array (
											'type' => 'discount',
											'pms_code' => $code,
											'rel_code' => $code,
											'discount_id' => $web_code,
											'need_member' => $res ['need_member']
									);
									$pms_state [$rl ['room_type_id']] [$web_code] ['price_code'] = $web_code;
									$pms_state [$rl ['room_type_id']] [$web_code] ['des'] = '';
									$pms_state [$rl ['room_type_id']] [$web_code] ['sort'] = 0;
									$pms_state [$rl ['room_type_id']] [$web_code] ['disp_type'] = 'buy';
									
									$allprice = '';
									$amount = '';
									for($i = 0; $i < $params ['days']; $i ++) {
										$pms_state [$rl ['room_type_id']] [$web_code] ['date_detail'] [date ( 'Ymd', strtotime ( '+ ' . $i . ' day ', strtotime ( $startdate ) ) )] = array (
												'price' => $rlp [$i],
												'nums' => $rl ['quantities'] [$i]
										);
										$allprice .= ',' . $rlp [$i];
										$amount += $rlp [$i];
									}
									if ($amount <= 0) {
										unset ( $pms_state [$rl ['room_type_id']] [$web_code] );
										continue;
									}
									if (isset ( $params ['web_rids'] [$rl ['room_type_id']] )) {
										$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['room_type_id']]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['room_type_id']]];
									} else {
										$nums = 1;
									}
									$pms_state [$rl ['room_type_id']] [$web_code] ['allprice'] = substr ( $allprice, 1 );
									$pms_state [$rl ['room_type_id']] [$web_code] ['total'] = $amount;
									$pms_state [$rl ['room_type_id']] [$web_code] ['related_des'] = '';
									$pms_state [$rl ['room_type_id']] [$web_code] ['total_price'] = $amount * $nums;
									$pms_state [$rl ['room_type_id']] [$web_code] ['avg_price'] = number_format ( $amount / $params ['days'], 2, '.', '' );
									;
									$pms_state [$rl ['room_type_id']] [$web_code] ['price_resource'] = 'webservice';
									$pms_state [$rl ['room_type_id']] [$web_code] ['least_num'] = min ( $rl ['quantities'] );
									$book_status = 'full';
									if ($pms_state [$rl ['room_type_id']] [$web_code] ['least_num'] >= $nums)
										$book_status = 'available';
									$pms_state [$rl ['room_type_id']] [$web_code] ['book_status'] = $book_status;
									$exprice [$rl ['room_type_id']] [] = $pms_state [$rl ['room_type_id']] [$web_code] ['avg_price'];
								}
							}
						}
					}
				}
			}
		}
		return $pms_state;
	}
	function get_web_discount($pms_auth) {
		$url = $pms_auth ['url'] . 'get_rate_promotion_list?' . $pms_auth ['url_auth'];
		return $this->get_to ( $url );
	}
	function get_to($url, $data = array(), $inter_id = '') {
		$this->load->helper ( 'common' );
		$r_url = $url;
		$url .= '&' . http_build_query ( $data );
		$now = time ();
		$s = doCurlGetRequest ( $url );
		
		$openid=$this->session->userdata ( $inter_id . 'openid' );
		$microtime=microtime();
		
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'luopan', $r_url, $data, $s,'query_get', $now, $microtime, $openid);
		
		$run_alarm = empty($s) ? 1:0;
		
		$s = json_decode ( $s, true );
		
		$func_data ['openid'] = $openid;
		$this->checkWebResult($inter_id, $r_url, $data, $s, $now, $microtime, $func_data, array(
			'run_alarm' => $run_alarm
		),$inter_id);
		
		
		return $s;
	}
	function post_to($url, $data = array(), $inter_id = '') {
		$this->load->helper ( 'common' );
		$send_content = http_build_query ( $data );
		$now = time ();
		$s = doCurlPostRequest ( $url, $send_content );
		$openid=$this->session->userdata ( $inter_id . 'openid' );
		$microtime=microtime();
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'luopan', $url, $data, $s,'query_post', $now, $microtime, $openid);
		$run_alarm = empty($s) ? 1:0;
		$s = json_decode ( $s, true );
		
		$func_data['openid'] = $openid;
		$this->checkWebResult ( $inter_id, $url, $data, $s, $now, $microtime, $func_data, array (
			'run_alarm' => $run_alarm
		));
		
		return $s;
	}
	//判断订单是否能支付
	function check_order_canpay($list, $params) {
		$pms_auth = json_decode ( $params ['pms_set'] ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . 'get_order_detail?' . $pms_auth ['url_auth'];
		$data = array (
				"room_order_id" => $list ['web_orderid'],
				"mobile_or_email" => $list ['tel']
		);
		$res = $this->get_to ( $url, $data, $list['inter_id'] );
		if (! empty ( $res ['order'] )) {
			$status_arr = $this->pms_enum ( 'status' );
			$new_status = $status_arr [$res ['order'] ['order_status']];
		}
		if(isset($new_status) && ($new_status == 1 || $new_status == 0)){//订单预定或确认
			return true;
		}else{
			return false;
		}
	}
	
	protected function checkWebResult($inter_id, $web_path, $send, $receive, $now, $micro_time, $func_data = [], $params = []){
		$func_name = substr ( $web_path, strrpos ( $web_path, '/' ) + 1);
		$func_name=substr($func_name,0,strpos($func_name, '?')+1);
		$func_name_des = $this->pms_enum('func_name', $func_name);
		isset ($func_name_des) or $func_name_des = $func_name; // 方法名描述\
		$err_msg = ''; // 错误提示信息
		$err_lv = NULL; // 错误级别，1报警，2警告
		$alarm_wait_time = null; // 默认超时时间
		if(!empty($params['run_alarm'])){ // 程序运行报错，直接报警
			$err_msg = '程序报错,' . json_encode($receive, JSON_UNESCAPED_UNICODE);
			$err_lv = 1;
		}else{
			switch($func_name){
				case 'search_hotel_rates':
					if(empty($receive['room_avail_records'])){
						$err_lv=2;
						$err_msg='空数据';
					}
					break;
				case 'create_order':
					if(!empty($receive['error'])){
						$err_lv=1;
						$err_msg='接口出错：'.json_encode($receive['error'],JSON_UNESCAPED_UNICODE);
					}
					break;
				case 'cancel_order':
					if(!empty($receive['error'])){
						$err_lv=1;
						$err_msg='接口出错：'.json_encode($receive['error'],JSON_UNESCAPED_UNICODE);
					}
					break;
				case 'get_order_detail':
					if(!empty($receive['error'])){
						$err_lv=2;
						$err_msg='接口出错：'.json_encode($receive['error'],JSON_UNESCAPED_UNICODE);
					}
					break;
				case 'deposit_order':
					if(!empty($receive['error'])){
						$err_lv=1;
						$err_msg='接口出错：'.json_encode($receive['error'],JSON_UNESCAPED_UNICODE);
					}
					break;
			}
		}
		
		$this->Webservice_model->webservice_error_log($inter_id, 'luopan', $err_lv, $err_msg, array(
			'web_path'        => $web_path,
			'send'            => $send,
			'receive'         => $receive,
			'send_time'       => $now,
			'receive_time'    => $micro_time,
			'fun_name'        => $func_name_des,
			'alarm_wait_time' => $alarm_wait_time
		), $func_data);
	}
	
}