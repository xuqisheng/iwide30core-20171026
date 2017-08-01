<?php
class Huayi_hotel_model extends CI_Model {
	const TAB_HO = 'hotel_orders';
	const YUNMENG_URL = 'http://114.215.188.191:18080/HotelCRSREQ/app/hotelSelfService';
	const ZURL = 'http://218.244.135.198:18080/HotelCRSREQ_01/app/hotelSelfService';
	// const YUNMENG_URL = 'http://218.244.135.198:18080/HotelCRSREQ/app/hotelSelfService';
	function __construct() {
		parent::__construct ();
	}
	function get_rooms_change($rooms, $idents, $condit, $pms_set = array()) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$startdate = $condit ['startdate'];
		$enddate = $condit ['enddate'];
		$member_type = $condit ['member_level'];
		$this->load->model ( 'common/Webservice_model' );
		$level_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
				'member_level',
				'basic_price_code',
				'member_price_code',
				'level_price_code'
		), 1, 'l2w' );
		$web_level = NULL;
		$price_level = NULL;
		if (! empty ( $level_reflect ['member_level'] ) && isset ( $level_reflect ['member_level'] [$member_type] )) {
			$web_level = $level_reflect ['member_level'] [$member_type];
			$price_level = $web_level;
		}

		
		if ($pms_set ['pms_room_state_way'] == 3 && isset ( $level_reflect ['basic_price_code'] )) {
			$basic_price_code = $level_reflect ['basic_price_code'];
			$web_level = current ( $basic_price_code );
		}
		
		$pre_pays = $this->huayi_enum ( 'pre_pay' );
		$no_pays = $this->huayi_enum ( 'no_pay_way' );
		$countday = get_room_night($startdate,$enddate,'ceil',$condit); //至少有一个间夜
		$web_rids = array ();
		foreach ( $rooms as $r ) {
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		
		$params = array (
				'member_type' => $member_type,
				'web_level' => $web_level,
				'price_level' => $price_level,
				'idents' => $idents,
				'condit' => $condit,
				'pre_pays' => $pre_pays,
				'no_pays' => $no_pays,
				'countday' => $countday,
				'web_rids' => $web_rids,
				'level_reflect' => $level_reflect 
		);
		if (isset ( $web_level )) {
			$pms_data = $this->get_web_roomtype ( $pms_auth, $idents ['hotel_web_id'], $web_level, $startdate, $enddate, $params );
		}
		if (! empty ( $pms_data )) {
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
				$data [$rm ['room_id']] ['state_info'] = empty ( $pms_state ['valid_state'] [$rm ['webser_id']] ) ? array () : $pms_state ['valid_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['show_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['lowest'] = min ( $pms_state ['exprice'] [$rm ['webser_id']] );
				$data [$rm ['room_id']] ['highest'] = max ( $pms_state ['exprice'] [$rm ['webser_id']] );
			}
		}
		return $data;
	}
	function get_rooms_change_lmem($pms_data, $rooms, $params) {
		$data = array ();
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_type'];
		$price_level = $params ['price_level'];
		$condit = $params ['condit'];
		$this->load->model ( 'hotel/Order_model' );
		$data = $this->Order_model->get_rooms_change ( $local_rooms, $params ['idents'], $params ['condit'] );
		$pms_state = $pms_data ['pms_state'];
		$valid_state = $pms_data ['valid_state'];
		foreach ( $data as $room_key => $lrm ) {
			$min_price = array ();
			if (empty ( $valid_state [$lrm ['room_info'] ['webser_id']] )) {
				unset ( $data [$room_key] );
				continue;
			}
			if (! empty ( $lrm ['state_info'] )) {
				foreach ( $lrm ['state_info'] as $sik => $si ) {
					// if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
					if ($si ['external_code'] !== '') {
						$external_code=$si ['external_code'];
						if (isset($params['level_reflect']['level_price_code'][$external_code])){
							$external_code=$params['level_reflect']['level_price_code'][$external_code];
						}
						$external_code = $params ['level_reflect'] ['member_level'] [$external_code];
						$external_code_reflect = $params ['level_reflect'] ['member_price_code'] [$external_code];
						$external_code = $params ['level_reflect'] ['member_price_code'] [$price_level];
					}
					if (isset ( $external_code_reflect ) && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect] )) {
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
						// $otmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						
						if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member') {
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						} else {
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $data [$room_key] ['state_info'] [$sik] ['least_num'] <= $tmp ['least_num'] ? $data [$room_key] ['state_info'] [$sik] ['least_num'] : $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
							if ($data [$room_key] ['state_info'] [$sik] ['least_num'] <= 0) {
								$data [$room_key] ['state_info'] [$sik] ['book_status'] = 'full';
							}
						}
						
						$data [$room_key] ['state_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];
						// $data [$room_key] ['state_info'] [$sik] ['extra_info'] ['channel_code'] = $price_level;
						$allprice = '';
						$amount = 0;
						foreach ( $tmp ['date_detail'] as $dk => $td ) {
							if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member'||(!empty($si ['related_code']))) {
								$tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
							} else {
								$tmp ['date_detail'] [$dk] ['price'] = round ( $data [$room_key] ['state_info'] [$sik] ['date_detail'] [$dk] ['price'] );
							}
							$tmp ['date_detail'] [$dk] ['nums'] = $data [$room_key] ['state_info'] [$sik] ['least_num'];
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
						
						$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['countday'], 1 );
						$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['state_info'] [$sik] ['total'] = intval ( $amount );
						$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
						$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
					}
					// else {
					// unset ( $data [$room_key] ['state_info'] [$sik] );
					// }
					// }
					// else {
					// $min_price [] = $si ['avg_price'];
					// }
				}
				$data [$room_key] ['lowest'] = empty ( $min_price ) ? 0 : min ( $min_price );
				$data [$room_key] ['highest'] = empty ( $min_price ) ? 0 : max ( $min_price );
				
				foreach ( $lrm ['show_info'] as $sik => $si ) {
					if ($si ['external_code'] !== '') {
						$external_code=$si ['external_code'];
						if (isset($params['level_reflect']['level_price_code'][$external_code])){
							$external_code=$params['level_reflect']['level_price_code'][$external_code];
						}
						$external_code_reflect = $params ['level_reflect'] ['member_level'] [$external_code];
						$external_code_reflect = $params ['level_reflect'] ['member_price_code'] [$external_code_reflect];
					}
					if (isset ( $external_code_reflect ) && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect] )) {
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
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
						
						$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['countday'], 1 );
						$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['show_info'] [$sik] ['total'] = intval ( $amount );
						$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
					} else {
						unset ( $data [$room_key] ['show_info'] [$sik] );
					}
				}
			}
			if (empty ( $data [$room_key] ['state_info'] )) {
				unset ( $data [$room_key] );
			}
		}
		return $data;
	}
	function get_web_roomtype($pms_auth, $hotel_web_id, $member_type, $startdate, $enddate, $params) {
		// $url = self::YUNMENG_URL . '?method=SingleHotelSearch';
		$url = $pms_auth ['url'] . '?method=SingleHotelSearch';
		$json = array (
				"channelCode" => $member_type,
				"channelFrom" => $pms_auth ['channelFrom'],
				"userId" => "",
				"priceRange" => "",
				"chainCode" => $pms_auth ['chainCode'],
				"brandCode" => "",
				"hotelCode" => $hotel_web_id,
				"startDate" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"endDate" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"memberCode" => '',
				"cardTypeCode" => '' 
		);
		$pms_data = $this->get_to ( $url, $json, $params ['idents'] ['inter_id'] );
		// var_dump($json);
		// var_dump($pms_data);
		// exit;
		$pms_state = array ();
		$valid_state = array ();
		$exprice = array ();
		if (! empty ( $pms_data ) && $pms_data ['state'] == 1) {
			foreach ( $pms_data ['data'] ['ratePlans'] as $rate_plan ) {
				$price_types [$rate_plan ['ratePlanCode']] ['price_name'] = $rate_plan ['ratePlanName'];
				$price_types [$rate_plan ['ratePlanCode']] ['price_des'] = $rate_plan ['freemeal'] . ',' . $rate_plan ['payment'];
				$price_types [$rate_plan ['ratePlanCode']] ['payment_code'] = $rate_plan ['paymentCode'];
			}
			foreach ( $pms_data ['data'] ['detail'] as $rd ) {
				if ($rd ['channelCode'] == $params ['web_level']) {
					foreach ( $rd ["ratePlans"] as $room_detail ) {
						$min_price = array ();
						foreach ( $room_detail ['roomTypes'] as $type_state ) {
							if (! empty ( $type_state ['rates'] )) {
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['price_name'] = $price_types [$room_detail ['ratePlanCode']] ['price_name'];
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['price_type'] = 'pms';
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['extra_info'] = array (
										'type' => 'code',
										'pms_code' => $room_detail ['ratePlanCode'],
										'channel_code' => $rd ['channelCode'] 
								);
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['price_code'] = $room_detail ['ratePlanCode'];
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['des'] = $price_types [$room_detail ['ratePlanCode']] ['price_des'];
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['sort'] = 0;
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['disp_type'] = 'buy';
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['condition'] ['pre_pay'] = empty ( $params ['pre_pays'] [$price_types [$room_detail ['ratePlanCode']] ['payment_code']] ) ? 0 : 1;
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['condition'] ['no_pay_way'] = empty ( $params ['no_pays'] [$price_types [$room_detail ['ratePlanCode']] ['payment_code']] ) ? array () : $params ['no_pays'] [$price_types [$room_detail ['ratePlanCode']] ['payment_code']];
								$allprice = '';
								$amount = '';
								foreach ( $type_state ['rates'] as $room_state ) {
									$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['date_detail'] [date ( 'Ymd', strtotime ( $room_state ['date'] ) )] = array (
											'price' => $room_state ['rate'],
											'nums' => $type_state ['roomQuantity'] 
									);
									$allprice .= ',' . $room_state ['rate'];
									$amount += $room_state ['rate'];
								}
								if (isset ( $params ['web_rids'] [$type_state ['roomTypeCode']] )) {
									$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$type_state ['roomTypeCode']]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$type_state ['roomTypeCode']]];
								} else {
									$nums = 1;
								}
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['allprice'] = substr ( $allprice, 1 );
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['total'] = $amount;
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['related_des'] = '';
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['extra_info'] = array (
										'type' => 'code',
										'pms_code' => $room_detail ['ratePlanCode'],
										'channel_code' => $rd ['channelCode'] 
								);
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['total_price'] = $amount * $nums;
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['avg_price'] = number_format ( $amount / $params ['countday'], 2, '.', '' );
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['price_resource'] = 'webservice';
								if ($type_state ['roomQuantity'] > 1)
									$type_state ['roomQuantity'] = 1;
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['least_num'] = $type_state ['roomQuantity'];
								$book_status = 'full';
								if ($type_state ['roomQuantity'] >= $nums)
									$book_status = 'available';
								$pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['book_status'] = $book_status;
								$exprice [$type_state ['roomTypeCode']] [] = $pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] ['avg_price'];
								if ($room_detail ['canBook'] == 1) {
									$valid_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] = $pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']];
								}
							}
						}
					}
				}
			}
		}
		return array (
				'pms_state' => $pms_state,
				'valid_state' => $valid_state,
				'exprice' => $exprice 
		);
	}
	function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()) {
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $inter_id, array (
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $order )) {
			$order = $order [0];
			$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
			$room_codes = json_decode ( $order ['room_codes'], TRUE );
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			$url = $pms_auth ['url'] . '?method=bookHotel';
			$resType = 2;
			$remark = '';
			if (! empty ( $params ['trans_no'] )) {
				$resType = 1;
				$remark = '系统备注：此订单为微信端网上支付订单，客人已支付房费' . $order ['price'] . '元。请客人出示手机核实微信支付记录。';
			}
			$daily_price = explode ( ',', $order ['first_detail'] ['allprice'] );
			if ($order ['coupon_favour'] > 0) {
				$daily_price [0] -= $order ['coupon_favour'];
				$remark .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
			}
			$json = array (
					"channelCode" => $room_codes ['code'] ['extra_info'] ['channel_code'],
					"channelFrom" => $pms_auth ['channelFrom'],
					"userId" => "",
					"chainCode" => $pms_auth ['chainCode'],
					"brandCode" => "",
					"hotelCode" => $pms_set ['hotel_web_id'],
					"channelResId" => $order ['orderid'],
					"guestCounts" => "1",
					"guestName" => $order ['name'],
					"ratePlanCode" => $room_codes ['code'] ['extra_info'] ['pms_code'],
					"roomTypeCode" => $room_codes ['room'] ['webser_id'],
					"totalAmount" => $order ['price'],
					"startDate" => date ( 'Y-m-d', strtotime ( $order ['startdate'] ) ),
					"endDate" => date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ),
					"arriveEarliest" => "12:00",
					"arriveLatest" => "18:00",
					"mobile" => $order ['tel'],
					"roomQuantity" => $order ['roomnums'],
					"specialRequest" => $remark,
					"resType" => $resType,
					"rateDaily" => $daily_price,
					"roomId" => [ ],
					"memberCardCode" => "" 
			);
			$result = $this->get_to ( $url, $json, $inter_id );
			/*
			 * 返回信息：
			 * {"message":"成功","data":{"externalResId":"GDS15122313H5N6"},"state":"1","code":"200000"}
			 */
			if ($result ['state'] == 1) {
				$web_orderid = $result ['data'] ['externalResId'];
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_order_additions', array (
						'web_orderid' => $web_orderid 
				) );
				// $this->Order_model->update_order_status ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
				if ($order ['status'] != 9) {
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id'] 
					) );
					$this->db->update ( 'hotel_orders', array (
							'status' => 1 
					) );
					$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
				}else if ($order ['paytype'] == 'balance') {
					$this->load->model ( 'hotel/Hotel_config_model' );
					$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
							'PMS_BANCLANCE_REDUCE_WAY'
					) );
					if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
						$this->load->model ( 'hotel/Member_model' );
						$balance_param=array('crsNo'=>$web_orderid);
						if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'], '订房订单余额支付',$balance_param ,$order)) {
							$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true ,true);
							$this->add_web_bill ( $web_orderid, $order, $room_codes, $pms_set, '' );
						} else {
							$info = $this->Order_model->cancel_order ( $inter_id, array (
									'only_openid' => $order ['openid'],
									'member_no' => '',
									'orderid' => $order ['orderid'],
									'cancel_status' => 5,
									'no_tmpmsg' => 1,
									'delete' => 2,
									'idetail' => array (
											'i'
									)
							) );
							return array (
									's' => 0,
									'errmsg' => '储值支付失败'
							);
						}
					}
				}
				if (! empty ( $paras ['trans_no'] )) { // 提交账务
					$this->add_web_bill ( $web_orderid, $order, $room_codes, $pms_set, $paras ['trans_no'] );
				}
				return array ( // 返回成功
						's' => 1 
				);
			} else if (empty ( $result )) { // 当无返回，使用渠道单号查询是否成功
				$deny_status = array (
						'HAC' => 1,
						'XXX' => 1 
				);
				$check = $this->searchOrderDetail ( '', $room_codes, $pms_set, $order ['orderid'] );
				if (! empty ( $check ) && $check ['state'] == 1 && ! empty ( $check ['data'] ['externalResId'] ) && ! isset ( $deny_status [$check ['data'] ['status']] )) {
					$web_orderid = $check ['data'] ['externalResId'];
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id'] 
					) );
					$this->db->update ( 'hotel_order_additions', array (
							'web_orderid' => $web_orderid 
					) );
					// $this->Order_model->update_order_status ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
					if ($order ['status'] != 9) {
						$this->db->where ( array (
								'orderid' => $order ['orderid'],
								'inter_id' => $order ['inter_id'] 
						) );
						$this->db->update ( 'hotel_orders', array (
								'status' => 1 
						) );
						$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
					}else if ($order ['paytype'] == 'balance') {
						$this->load->model ( 'hotel/Hotel_config_model' );
						$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
								'PMS_BANCLANCE_REDUCE_WAY'
						) );
						if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
							$this->load->model ( 'hotel/Member_model' );
							$balance_param=array('crsNo'=>$web_orderid);
							if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'], '订房订单余额支付',$balance_param,$order)) {
								$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true,true );
								$this->add_web_bill ( $web_orderid, $order, $room_codes, $pms_set, '' );
							} else {
								$info = $this->Order_model->cancel_order ( $inter_id, array (
										'only_openid' => $order ['openid'],
										'member_no' => '',
										'orderid' => $order ['orderid'],
										'cancel_status' => 5,
										'no_tmpmsg' => 1,
										'delete' => 2,
										'idetail' => array (
												'i'
										)
								) );
								return array (
										's' => 0,
										'errmsg' => '储值支付失败'
								);
							}
						}
					}
					if (! empty ( $paras ['trans_no'] )) { // 提交账务
						$this->add_web_bill ( $web_orderid, $order, $room_codes, $pms_set, $paras ['trans_no'] );
					}
					return array ( // 返回成功
							's' => 1 
					);
				} else {
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id'] 
					) );
					$this->db->update ( 'hotel_orders', array ( // 提交失败，把订单状态改为下单失败
							'status' => 10 
					) );
					return array ( // 返回失败
							's' => 0,
							'errmsg' => '提交订单失败。' 
					);
				}
			} else {
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_orders', array ( // 提交失败，把订单状态改为下单失败
						'status' => 10 
				) );
				return array ( // 返回失败
						's' => 0,
						'errmsg' => '提交订单失败' . ',' . $result ['message'] 
				);
			}
		}
		return array (
				's' => 0,
				'errmsg' => '提交订单失败' 
		);
	}
	function huayi_enum($type) {
		switch ($type) {
			case 'pre_pay' :
				return array (
						'T' => '0',
						'S' => '1',
						'Y' => '1' 
				);
				break;
			case 'no_pay_way' :
				return array (
						'T' => array (),
						'S' => array (
								'daofu' 
						),
						'Y' => array (
								'daofu' 
						) 
				);
				break;
			case 'status' :
				/*
				 * CON:预订，RM:选房，IN:现住，OUT:离店，HAC:拒绝，CAN:取消，MISS:失效 XXX 拒单
				 * */
				return array (
						'CON' => 1,
						'RM' => 1,
						'IN' => 2,
						'OUT' => 3,
						'HAC' => 5,
						'CAN' => 5,
						'XXX' => 5,
						'MISS' => 5 
				);
				break;
			default :
				break;
		}
	}
	function update_web_order($inter_id, $list, $params) {
		switch ($inter_id) {
			case 'a445223616' :
				return $this->update_web_order_sub ( $inter_id, $list, $params );
				break;
			default :
				return $this->update_web_order_main ( $inter_id, $list, $params );
				break;
		}
		return FALSE;
	}
	function update_web_order_sub($inter_id, $list, $params) {
		$room_codes = json_decode ( $list ['room_codes'], TRUE );
		$room_codes = $room_codes [$list ['first_detail'] ['room_id']];
		$result = $this->searchOrderDetail ( $list ['web_orderid'], $room_codes, $params ['pms_set'] );
		$new_status = null;
		if ($result ['state'] == 1) {
			$status_arr = $this->huayi_enum ( 'status' );
			$new_status = $status_arr [$result ['data'] ['status']];
			if (! empty ( $result ['data'] ['room'] )) {
				$sub_orders = array ();
				$sub_orderid = array ();
				foreach ( $result ['data'] ['room'] as $rr ) {
					if (empty ( $sub_orders [$rr ['roomNumber']] )) {
						$sub_orders [$rr ['roomNumber']] ['room_no'] = $rr ['roomNumber'];
						$sub_orders [$rr ['roomNumber']] ['startdate'] = date ( 'Ymd', strtotime ( $rr ['checkInDate'] ) );
						$sub_orders [$rr ['roomNumber']] ['enddate'] = date ( 'Ymd', strtotime ( $rr ['checkOutDate'] ) );
						$sub_orders [$rr ['roomNumber']] ['status'] = $status_arr [$rr ['status']];
						$sub_orderid [] = $rr ['roomNumber'];
					}
				}
				$this->load->model ( 'hotel/Order_model' );
				$i = 0;
				foreach ( $list ['order_details'] as $od ) {
					if (empty ( $od ['webs_orderid'] ) || $list ['roomnums'] == 1) {
						$webs_orderid = $sub_orderid [$i];
						$i ++;
						$this->db->where ( array (
								'id' => $od ['sub_id'],
// 								'inter_id' => $inter_id,
// 								'orderid' => $list ['orderid'] 
						) );
						$this->db->update ( 'hotel_order_items', array (
								'webs_orderid' => $webs_orderid 
						) );
					} else {
						$webs_orderid = $od ['webs_orderid'];
					}
					if ($od ['istatus'] == 4 && $sub_orders [$webs_orderid] ['status'] == 5) {
						$sub_orders [$webs_orderid] ['status'] = 4;
					}
					if ($sub_orders [$webs_orderid] ['status'] != $od ['istatus'] || $sub_orders [$webs_orderid] ['enddate'] != $od ['enddate']) {
						$countday = ceil ( (strtotime ( date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['enddate'] ) ) ) - strtotime ( $od ['enddate'] )) / 86400 );
						$new_status = $sub_orders [$webs_orderid] ['status'];
						$updata = array ();
						if ($sub_orders [$webs_orderid] ['status'] != $od ['istatus']) {
							$updata ['istatus'] = $sub_orders [$webs_orderid] ['status'];
						}
						if ($countday != 0) {
							// $avg_price = floatval ( $od ['allprice'] ); // 取首日价格
							// $updata ['no_check_date'] = 1;
							// $updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['startdate'] ) );
							// $updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['enddate'] ) );
							// $updata ['new_price'] = $od ['iprice'] + $countday * $avg_price;
							
							// @author lGh 2016-3-25 17:46:14 云盟返佣机制更改
							// 到付：提前离店，按pms计算；
							// 微信支付：按下单时订单计算；
							if ($countday < 0 && $list ['paid'] == 0) {
								$first_price = floatval ( $od ['allprice'] ); // 取首日价格
								$allprice = explode ( ',', $od ['allprice'] );
								$countday = abs ( $countday );
								if ($sub_orders [$webs_orderid] ['enddate']==$sub_orders [$webs_orderid] ['startdate']){
									$countday--;
								}
								$updata ['no_check_date'] = 1;
								$updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['startdate'] ) );
								$updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['enddate'] ) );
								$reduce_amount = 0;
								for($j = 0; $j < $countday; $j ++) {
									$tmp = array_pop ( $allprice );
									$reduce_amount += empty ( $tmp ) ? $first_price : $tmp;
								}
								$updata ['new_price'] = $od ['iprice'] - $reduce_amount;
							}
						}
						if (! empty ( $updata ))
							$this->Order_model->update_order_item ( $inter_id, $list ['orderid'], $od ['sub_id'], $updata );
					}
				}
			}
			if ($list ['status'] == 4 && $new_status == 5) {
				$new_status = 4;
			}
			if ($new_status != $list ['status']) {
				$this->load->model ( 'hotel/Order_model' );
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
		return $new_status;
	}
	function update_web_order_main($inter_id, $list, $params) {
		$room_codes = json_decode ( $list ['room_codes'], TRUE );
		$room_codes = $room_codes [$list ['first_detail'] ['room_id']];
		$result = $this->searchOrderDetail ( $list ['web_orderid'], $room_codes, $params ['pms_set'] );
		$new_status = null;
		if ($result ['state'] == 1) {
			$status_arr = $this->huayi_enum ( 'status' );
			$new_status = $status_arr [$result ['data'] ['status']];
			if ($list ['status'] == 4 && $new_status == 5) {
				$new_status = 4;
			}
			if ($new_status != $list ['status']) {
				$this->load->model ( 'hotel/Order_model' );
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
		return $new_status;
	}
	function cancel_order_web($inter_id, $order, $pms_set = array()) {
		$web_orderid = isset ( $order ['web_orderid'] ) ? $order ['web_orderid'] : "";
		if (! $web_orderid) {
			return array (
					's' => 0,
					'errmsg' => '取消失败' 
			);
		}
		$room_codes = json_decode ( $order ['room_codes'], TRUE );
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . '?method=cancel';
		$json = array (
				"channelCode" => $room_codes ['code'] ['extra_info'] ['channel_code'],
				"channelFrom" => $pms_auth ['channelFrom'],
				"userId" => "",
				"hotelCode" => $pms_set ['hotel_web_id'],
				"chainCode" => $pms_auth ['chainCode'],
				"brandCode" => "",
				"externalResId" => $web_orderid 
		);
		$result = $this->get_to ( $url, $json, $inter_id );
		if ($result ['state'] == 1) {
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
	
	/*
	 * searchOrderDetail 订单查询详细
	 */
	public function searchOrderDetail($web_orderid, $room_codes, $pms_set, $local_orderid = '') {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . '?method=searchOrderDetail';
		$json = array (
				"channelCode" => $room_codes ['code'] ['extra_info'] ['channel_code'],
				"channelFrom" => $pms_auth ['channelFrom'],
				"id" => "",
				"userId" => "",
				"idCard" => "",
				'channelResId' => $local_orderid,
				"externalResId" => $web_orderid,
				"hotelCode" => $pms_set ['hotel_web_id'],
				"chainCode" => $pms_auth ['chainCode'],
				"brandCode" => "" 
		);
		return $this->get_to ( $url, $json, $pms_set ['inter_id'] );
		/*
		 * 返回信息：
		 * {"message":"成功","data":{"id":"13H5N2","hotelCode":"HY2403","hotelName":"华仪连锁惠新东街店","hotelAddress":"北京市 朝阳区 安贞街道裕民路12号","hotelTel":"010-82356925","channelName":"银卡","channelTel":"","ratePlanCode":"HY~12","ratePlanName":"银卡价格计划","roomTypeCode":"BZ","roomTypeName":"标准间","resRoomTypeInvType":"标准间","roomQuantity":1,"startTime":"2015-12-23","endTime":"2015-12-24","guestCounts":1,"personName":" 王小二;","arriveEarlyTime":"12:00","arriveLateTime":"18:00","payTypeCode":"Y","payType":"全额预付","payTypeName":"全额预付","totalAmount":179,"specialRequest":"","externalResId":"GDS15122213H5N2","createDateTime":"2015-12-22 15:23:31","status":"CON","nightSize":1,"freemeal":"0","rateAmount":[],"userId":"","idCard":"","telephone":"13580506405","room":[{"buildingNo":"","floor":"","section":"","roomNumber":"","roomTypeCode":"BZ","roomTypeName":"标准房","roomDateTime":"","groupNo":"","realName":"王小二","idCard":"","mobile":"13580506405","status":"CON","checkInDate":"2015-12-23","checkOutDate":"2015-12-24"}],"pay":[]},"state":"1","code":"200000"}
		 */
	}
	
	/*
	 * transferAccount 传账
	 */
	public function add_web_bill($web_orderid, $order, $room_codes, $pms_set, $trans_no = '') {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		// $url = $pms_auth ['url'] . '?method=transferAccount';
		// $json = array (
		// "channelCode" => $room_codes ['code'] ['extra_info'] ['channel_code'],
		// "channelFrom" => $pms_auth ['channelFrom'],
		// "hotelCode" => $pms_set ['hotel_web_id'],
		// "exTernalResId" => $web_orderid,
		// "roomNumber" => "",
		// "idCard" => "",
		// "accountCode" => "0",
		// "amount" => $order ['price']
		// );
		// $s = $this->get_to ( $url, $json );
		$s = $this->update_booktype ( $web_orderid, $room_codes, $pms_set, $pms_auth, 1 );
		// if ($s ['state'] == 1) {
		if ($s) {
			$this->db->where ( array (
					'orderid' => $order ['orderid'],
					'inter_id' => $order ['inter_id'] 
			) );
			$this->db->update ( 'hotel_order_additions', array (
					'web_paid' => 1 
			) );
			return true;
		}
		$this->db->where ( array (
				'orderid' => $order ['orderid'],
				'inter_id' => $order ['inter_id'] 
		) );
		$this->db->update ( 'hotel_order_additions', array (
				'web_paid' => 2 
		) );
		return false;
		/*
		 * 返回信息：
		 * {"message":"服务器忙","state":"0","code":"200100"}
		 */
	}
	// 传账
	function transferAccount($web_orderid, $room_codes, $pms_set, $pms_auth, $order) {
		$url = $pms_auth ['url'] . '?method=transferAccount';
		$json = array (
				"channelCode" => $room_codes ['code'] ['extra_info'] ['channel_code'],
				"channelFrom" => $pms_auth ['channelFrom'],
				"hotelCode" => $pms_set ['hotel_web_id'],
				"exTernalResId" => $web_orderid,
				"roomNumber" => '',
				"idCard" => '',
				"accountCode" => '305',
				"amount" => $order ['price'] 
		);
		$s = $this->post_to ( $url, $json, $pms_set ['inter_id'] );
		if ($s ['state'] == 1) {
			return true;
		}
		return false;
	}
	
	/*
	 * bookTypeUpdate 预定类型修改
	 */
	public function update_booktype($web_orderid, $room_codes, $pms_set, $pms_auth, $type) {
		$url = $pms_auth ['url'] . '?method=bookTypeUpdate';
		$json = array (
				"channelCode" => $room_codes ['code'] ['extra_info'] ['channel_code'],
				"channelFrom" => $pms_auth ['channelFrom'],
				"userId" => "",
				"hotelCode" => $pms_set ['hotel_web_id'],
				"chainCode" => "",
				"brandCode" => "",
				"externalResId" => $web_orderid,
				"type" => $type 
		);
		$s = $this->get_to ( $url, $json, $pms_set ['inter_id'] );
		if ($s ['state'] == 1) {
			return true;
		}
		return false;
		/*
		 * 返回信息：
		 * {"message":"成功","state":"1","code":"200000"}
		 */
	}
	function post_to($url, $data = array(), $inter_id = '') {
		$this->load->helper ( 'common' );
		$send_content = http_build_query ( $data );
		$now = time ();
		$s = doCurlPostRequest ( $url, $send_content, array (), 20 );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'webservice_record', array (
				'send_content' => json_encode ( $data ),
				'receive_content' => $s,
				'record_time' => $now,
				'inter_id' => $inter_id,
				'service_type' => 'huayi',
				'web_path' => $url,
				'record_type' => 'query_post',
				'openid' => $this->session->userdata ( $inter_id . 'openid' ),
				'wait_time' => $wait_time 
		) );
		$s = json_decode ( $s, true );
		return $s;
	}
	function get_to($url, $data = array(), $inter_id = '') {
		$json = json_encode ( $data );
		$this->load->helper ( 'common' );
		$r_url = $url;
		$url .= '&json=' . $json;
		$now = time ();
		$s = doCurlGetRequest ( $url, array (), 20 );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'webservice_record', array (
				'send_content' => json_encode ( $data ),
				'receive_content' => $s,
				'record_time' => $now,
				'inter_id' => $inter_id,
				'service_type' => 'huayi',
				'web_path' => $r_url,
				'record_type' => 'query_get',
				'openid' => $this->session->userdata ( $inter_id . 'openid' ),
				'wait_time' => $wait_time 
		) );
		$s = json_decode ( $s, true );
		return $s;
	}
	function update_web_order_sub_fix($inter_id, $list, $params) {
		$room_codes = json_decode ( $list ['room_codes'], TRUE );
		$room_codes = $room_codes [$list ['first_detail'] ['room_id']];
		$result = $this->searchOrderDetail ( $list ['web_orderid'], $room_codes, $params ['pms_set'] );
		$new_status = 1;
		if ($result ['state'] == 1) {
			$status_arr = $this->huayi_enum ( 'status' );
			$new_status = $status_arr [$result ['data'] ['status']];
			if (! empty ( $result ['data'] ['room'] )) {
				$sub_orders = array ();
				$sub_orderid = array ();
				foreach ( $result ['data'] ['room'] as $rr ) {
					if (empty ( $sub_orders [$rr ['roomNumber']] )) {
						$sub_orders [$rr ['roomNumber']] ['room_no'] = $rr ['roomNumber'];
						$sub_orders [$rr ['roomNumber']] ['startdate'] = date ( 'Ymd', strtotime ( $rr ['checkInDate'] ) );
						$sub_orders [$rr ['roomNumber']] ['enddate'] = date ( 'Ymd', strtotime ( $rr ['checkOutDate'] ) );
						$sub_orders [$rr ['roomNumber']] ['status'] = $status_arr [$rr ['status']];
						$sub_orderid [] = $rr ['roomNumber'];
					}
				}
				$this->load->model ( 'hotel/Order_model' );
				$i = 0;
				foreach ( $list ['order_details'] as $od ) {
					if (empty ( $od ['webs_orderid'] ) || $list ['roomnums'] == 1) {
						$webs_orderid = $sub_orderid [$i];
						$i ++;
						$this->db->where ( array (
								'id' => $od ['sub_id'],
// 								'inter_id' => $inter_id,
// 								'orderid' => $list ['orderid'] 
						) );
						$this->db->update ( 'hotel_order_items', array (
								'webs_orderid' => $webs_orderid 
						) );
					} else {
						$webs_orderid = $od ['webs_orderid'];
					}
					if ($od ['istatus'] == 4 && $sub_orders [$webs_orderid] ['status'] == 5) {
						$sub_orders [$webs_orderid] ['status'] = 4;
					}
					if ($sub_orders [$webs_orderid] ['status'] != $od ['istatus'] || $sub_orders [$webs_orderid] ['enddate'] != $list ['enddate']) { // 离店日期有更改
						$countday = ceil ( (strtotime ( date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['enddate'] ) ) ) - strtotime ( $list ['enddate'] )) / 86400 );
						$new_status = $sub_orders [$webs_orderid] ['status'];
						$updata = array ();
						if ($sub_orders [$webs_orderid] ['status'] != $od ['istatus']) {
							$updata ['istatus'] = $sub_orders [$webs_orderid] ['status'];
						}
						if ($countday != 0) {
							$avg_price = floatval ( $od ['allprice'] ); // 取首日价格
							$ori_price = explode ( ',', $od ['allprice'] );
							$ori_price = array_sum ( $ori_price );
							$updata ['no_check_date'] = 1;
							$updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['startdate'] ) );
							$updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['enddate'] ) );
							$updata ['new_price'] = $ori_price + $countday * $avg_price;
							
							// @author lGh 2016-3-25 17:46:14 云盟返佣机制更改
							// 到付：提前离店，按pms计算；
							// 微信支付：按下单时订单计算；
							// if ($countday < 0 && $list ['paid'] == 0) {
							// $first_price = floatval ( $od ['allprice'] ); // 取首日价格
							// $allprice = explode ( ',', $od ['allprice'] );
							// $countday = abs ( $countday );
							// $updata ['no_check_date'] = 1;
							// $updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['startdate'] ) );
							// $updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_orders [$webs_orderid] ['enddate'] ) );
							// $reduce_amount = 0;
							// for($j = 0; $j < $countday; $j ++) {
							// $tmp = array_pop ( $allprice );
							// $reduce_amount += empty ( $tmp ) ? $first_price : $tmp;
							// }
							// $updata ['new_price'] = $od ['iprice'] - $reduce_amount;
							// }
						}
						if (! empty ( $updata )) {
							$this->Order_model->update_order_item ( $inter_id, $list ['orderid'], $od ['sub_id'], $updata );
							return 1;
						}
					}
				}
				return 2;
			}
		}
		return 3;
	}
	//判断订单是否能支付
	function check_order_canpay($list, $params){
		$room_codes = json_decode ( $list ['room_codes'], TRUE );
		$room_codes = $room_codes [$list ['first_detail'] ['room_id']];
		$result = $this->searchOrderDetail ( $list ['web_orderid'], $room_codes, $params ['pms_set'] );
		if ($result ['state'] == 1) {
			$status_arr = $this->huayi_enum ( 'status' );
			$new_status = $status_arr [$result ['data'] ['status']];
		}
		
		if(isset($new_status) && ($new_status==1 || $new_status==0)){//订单确认
			return true;
		}else{
			return false;
		}
		
	}
}