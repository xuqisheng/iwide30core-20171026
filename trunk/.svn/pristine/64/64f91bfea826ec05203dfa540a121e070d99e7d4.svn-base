<?php

class Yage_hotel_model extends MY_Model{
	private $local_test = false;

//	private $serv_api;

	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
//		$this->serv_api = new YageAPI(0);
	}

	public function get_rooms_change($rooms, $idents, $condit, $pms_set = array()){
		statistic('A1');
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array(
			//			'member_level',
			'web_price_code',
			'member_price_code',
			'web_price_code_set'
		), 1, 'w2l');

		//		$member_level = isset ($web_reflect ['member_level'] [$condit ['member_level']]) ? $web_reflect ['member_level'] [$condit ['member_level']] : '';


		$this->load->model('api/Vmember_model', 'vm');
		$member_level = $this->vm->getLvlPmsCode($condit['openid'], $idents['inter_id']);

		$web_price_code = '';

		if(!empty ($condit ['price_codes'])){
			$web_price_code = $condit ['price_codes'];
		}else{
			if(!empty ($web_reflect ['web_price_code'])){
				foreach($web_reflect ['web_price_code'] as $wpc){
					$web_price_code .= ',' . $wpc;
				}
			}
			$web_price_code .= isset ($web_reflect ['member_price_code'] [$member_level]) ? ',' . $web_reflect ['member_price_code'] [$member_level] : '';
			$web_price_code = substr($web_price_code, 1);
		}
		$web_price_code = explode(',', $web_price_code);
		$countday = get_room_night($condit ['startdate'], $condit ['enddate'], 'ceil', $condit);//至少有一个间夜
		$web_rids = array();
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		statistic('A2');
		$params = array(
			'countday'     => $countday,
			'web_rids'     => $web_rids,
			'condit'       => $condit,
			'web_reflect'  => $web_reflect,
			'member_level' => $member_level,
			'idents'       => $idents,
		);

		if(!empty ($web_price_code)){
			$pms_data = $this->get_web_roomtype($pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params);
		}
		statistic('A3');
		$data = array();
		if(!empty ($pms_data)){
			switch($pms_set ['pms_room_state_way']){
				case 1 :
				case 2 :
					$data = $this->get_rooms_change_allpms($pms_data, array(
						'rooms' => $rooms
					), $params);
					break;
				case 3:
					$data = $this->get_rooms_change_lmem($pms_data, array(
						'rooms' => $rooms
					), $params);
					break;
			}
		}
		statistic('A4');

		$a_b = statistic('A1', 'A2');//查询本地配置
		$b_c = statistic('A2', 'A3');//请求PMS实时房态
		$c_d = statistic('A3', 'A4');//与本地房型匹配
		$a_d = statistic('A1', 'A4');//获取房型
		$timer_arr = array(
			'查询本地配置'    => $a_b . '秒',
			'请求PMS实时房态' => $b_c . '秒',
			'与本地房型匹配'   => $c_d . '秒',
			'获取房型房态总耗时' => $a_d . '秒',
			'执行时间'      => date('Y-m-d H:i:s'),
		);
		pms_logger(func_get_args(), $timer_arr, __METHOD__ . '->query_time', $pms_set['inter_id']);
		return $data;
	}

	function get_rooms_change_lmem($pms_data, $rooms, $params){
		$local_rooms = $rooms ['rooms'];
		$condit = $params ['condit'];
		$this->load->model('hotel/Order_model');
		$data = $this->Order_model->get_rooms_change($local_rooms, $params ['idents'], $params ['condit']);
		$pms_state = $pms_data ['pms_state'];
		$valid_state = $pms_data ['valid_state'];
		foreach($data as $room_key => $lrm){
			$min_price = array();
			if(empty ($valid_state [$lrm ['room_info'] ['webser_id']])){
				unset ($data [$room_key]);
				continue;
			}
			if(!empty ($lrm ['state_info'])){
				foreach($lrm ['state_info'] as $sik => $si){
					// if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
					/*if ($si ['external_code'] !== '') {
						$external_code = $params ['web_reflect'] ['member_level'] [$si ['external_code']];
						$external_code_reflect = $params ['web_reflect'] ['member_price_code'] [$external_code];
						$external_code = $params ['web_reflect'] ['member_price_code'] [$price_level];
					}*/
					if($si ['external_code'] !== ''){
//						$external_code_reflect = $params ['web_reflect'] ['member_level'] [$si ['external_code']];
						$external_code_reflect = $params ['web_reflect'] ['member_price_code'] [$si ['external_code']];
					}
//					print_r($si ['external_code']);
//					exit($external_code_reflect);
					if(isset ($external_code_reflect)){
						$external_code_arr = explode(',', $external_code_reflect);
						foreach($external_code_arr as $w){
							if(empty($pms_state [$lrm ['room_info'] ['webser_id']] [$w])){
								continue;
							}

							$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$w];

							$nums = isset ($condit ['nums'] [$lrm ['room_info'] ['room_id']]) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;

							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];

							$data [$room_key] ['state_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];

							$data[$room_key]['state_info'][$sik]['extra_info']['local_price_name'] = $si['price_name'];

							// $data [$room_key] ['state_info'] [$sik] ['extra_info'] ['channel_code'] = $price_level;
							$allprice = '';
							$amount = 0;
							foreach($tmp ['date_detail'] as $dk => $td){
								if($si['price_type'] == 'member'){
									$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'));
								}else{
//								$tmp ['date_detail'] [$dk] ['price'] = round ( $data [$room_key] ['state_info'] [$sik] ['date_detail'] [$dk] ['price'] );
									$tmp ['date_detail'] [$dk] ['price'] = $td ['price'];
								}

								$tmp ['date_detail'] [$dk] ['nums'] = $data [$room_key] ['state_info'] [$sik] ['least_num'];
								$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
								$amount += $tmp ['date_detail'] [$dk] ['price'];
							}
							$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
							$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday'], 1);
							$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr($allprice, 1);
							$data [$room_key] ['state_info'] [$sik] ['total'] = intval($amount);
							$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
							$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
						}
//					} else{
//						unset ($data [$room_key] ['state_info'] [$sik]);
					}

				}
				$data [$room_key] ['lowest'] = empty ($min_price) ? 0 : min($min_price);
				$data [$room_key] ['highest'] = empty ($min_price) ? 0 : max($min_price);
				if(!$lrm['show_info']){
					$lrm['show_info'] = $lrm['state_info'];
					$data [$room_key] ['show_info'] = $lrm['state_info'];
				}
				foreach($lrm ['show_info'] as $sik => $si){
					if($si ['external_code'] !== ''){
//						$external_code_reflect = $params ['web_reflect'] ['member_level'] [$si ['external_code']];
						$external_code_reflect = $params ['web_reflect'] ['member_price_code'] [$si ['external_code']];
					}

					if(isset($external_code_reflect)){
						$external_code_arr = explode(',', $external_code_reflect);
						foreach($external_code_arr as $w){
							if(empty($pms_state [$lrm ['room_info'] ['webser_id']] [$w])){
								continue;
							}
							$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$w];

							$nums = isset ($condit ['nums'] [$lrm ['room_info'] ['room_id']]) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
							$data [$room_key] ['show_info'] [$sik] ['least_num'] = $tmp ['least_num'];
							$data [$room_key] ['show_info'] [$sik] ['book_status'] = $tmp ['book_status'];
							$allprice = '';
							$amount = 0;
							foreach($tmp ['date_detail'] as $dk => $td){
								if($si['price_type'] == 'member'){
									$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'));
								}else{
									$tmp ['date_detail'] [$dk] ['price'] = $td ['price'];
								}

								$tmp ['date_detail'] [$dk] ['nums'] = $data [$room_key] ['show_info'] [$sik] ['least_num'];
								$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
								$amount += $tmp ['date_detail'] [$dk] ['price'];
							}
							$data [$room_key] ['show_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];

							$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday'], 1);
							$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr($allprice, 1);
							$data [$room_key] ['show_info'] [$sik] ['total'] = intval($amount);
							$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
						}
					}else{
						unset ($data [$room_key] ['show_info'] [$sik]);
					}

					/*if(isset ($external_code_reflect) && !empty ($pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect])){
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
						$nums = isset ($condit ['nums'] [$lrm ['room_info'] ['room_id']]) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						$data [$room_key] ['show_info'] [$sik] ['least_num'] = $tmp ['least_num'];
						$data [$room_key] ['show_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						$allprice = '';
						$amount = 0;
						foreach($tmp ['date_detail'] as $dk => $td){
							$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'));
							$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['show_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];

						$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday'], 1);
						$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr($allprice, 1);
						$data [$room_key] ['show_info'] [$sik] ['total'] = intval($amount);
						$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
					} else{
						unset ($data [$room_key] ['show_info'] [$sik]);
					}*/
				}
			}

			if(empty ($data [$room_key] ['state_info'])){
				unset ($data [$room_key]);
			}
		}
		return $data;
	}

	public function update_web_order($inter_id, $order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->getOrder($order['web_orderid']);
		$status = -1;
		if($web_order){
			$status_arr = $this->pms_enum('status');

			$ensure_check = 0;

			$status = isset($status_arr[$web_order['Status_code']['code']]) ? $status_arr[$web_order['Status_code']['code']] : -1;

			if($order ['status'] == 4 && $status == 5){
				$status = 4;
			}

			if($status != $order ['status'] && $status !== false){
				$this->load->model('hotel/Order_model');
				$this->change_order_status($inter_id, $order['orderid'], $status);
				$this->Order_model->handle_order($inter_id, $order ['orderid'], $status, $order['openid']);

			}
		}

		return $status;
	}

	public function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()){
		$this->load->model('hotel/Order_model');
		$order = $this->Order_model->get_main_order($inter_id, array(
			'orderid' => $orderid,
			'idetail' => array(
				'i'
			)
		));
		if(!empty ($order)){
			$order = $order [0];   //获取本地已保存的订单信息
			$room_codes = json_decode($order ['room_codes'], true);
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
//			$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], TRUE);

			/*
				构造要提交的数据
			*/

			$result = $this->order_reserve($order, $inter_id, $pms_set, $params);//提交订单

			if($result['result']){
				$web_orderid = $result['web_orderid'];            //取得返回的pms订单id
				$this->db->where(array(
					'orderid'  => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				));
				$this->db->update('hotel_order_additions', array(        //更新pms单号到本地
				                                                         'web_orderid' => $web_orderid
				));
				if($order ['status'] != 9){
					$this->change_order_status($inter_id, $orderid, 1);
					$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
				}
				/*if (! empty ( $paras ['trans_no'] )) { // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
					$this->add_web_bill ( $web_orderid, $order, $pms_set, $paras ['trans_no'] );
				}*/
				return array( // 返回成功
				              's' => 1
				);
			}else{
				$this->change_order_status($inter_id, $orderid, 10);
				return array( // 返回失败
				              's'      => 0,
				              'errmsg' => '提交订单失败' . ',' . $result ['errmsg']
				);
			}
		}
		return array(
			's'      => 0,
			'errmsg' => '提交订单失败'
		);
	}

	private function change_order_status($inter_id, $orderid, $status){
		$this->db->where(array(
			'orderid'  => $orderid,
			'inter_id' => $inter_id
		));
		$this->db->update('hotel_orders', array( // 提交失败，把订单状态改为下单失败
		                                         'status' => (int)$status
		));
	}

	public function cancel_order_web($inter_id, $order, $pms_set = array()){

		/*if($this->local_test === false){
			$this->serv_api->setApiAuth(json_decode($pms_set['pms_auth'], true));
		}*/

		if(empty ($order ['web_orderid'])){
			return array(
				's'      => 0,
				'errmsg' => '取消失败'
			);
		}
		$ri_edit = array();
		/*
			构造取消订单数据
		*/
		$this->apiInit($pms_set);
		$res = $this->serv_api->cancelReservation($order['web_orderid']);
		if(isset($res['result']) && $res['result']){
			return array(                        //取消成功，直接这样return，接下来的程序会继续处理
			                                     's'      => 1,
			                                     'errmsg' => '取消成功'
			);
		}

		return array(
			's'      => 0,
			'errmsg' => '取消失败,' . (isset($res['ErrReason']) ? $res['ErrReason'] : ''),
		);
	}

	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no = ''){
		/*if($this->local_test === false){
			$this->serv_api->setApiAuth(json_decode($pms_set['pms_auth'], true));
		}*/
		$this->apiInit($pms_set);
		$web_paid = 2;
		$web_order = $this->serv_api->getOrder($web_orderid);
		if(!$web_order){
			$this->db->where(array(
				'orderid'  => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			));
			$this->db->update('hotel_order_additions', array( //更新web_paid 状态，2为失败，1为成功
			                                                  'web_paid' => $web_paid
			));
			return false;
		}

		$remark = $web_order['Comments'];
		$remark .= '该订单已使用微信支付，支付单号:' . $trans_no;
//		$remark.='测试修改订单';
		$web_order['Comments'] = $remark;
		$post_data['oOrderInfo'] = $web_order;

		$res = $this->serv_api->modifyOrder($post_data);

		if(!empty($res['oOrderInfo'])){
			$web_paid = 1;
		}

		$this->db->where(array(
			'orderid'  => $order ['orderid'],
			'inter_id' => $order ['inter_id']
		));
		$this->db->update('hotel_order_additions', array(
			'web_paid' => $web_paid
		));
		return true;

	}

	function order_reserve($order, $inter_id, $pms_set, $params = array()){
		$this->apiInit($pms_set);
		/*if($this->local_test === false){
			$this->serv_api->setApiAuth(json_decode($pms_set['pms_auth'], true));
		}*/
		$starttime = strtotime($order['startdate']);
		$endtime = strtotime($order['enddate']);
		$startdate = date('Y-m-d', $starttime);
		$enddate = date('Y-m-d', $endtime);

		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];

		if(!isset($room_codes['code']['extra_info']['pms_code'])){
			return array(
				'result' => false,
				'errmsg' => '不存在价格代码',
			);
		}

		$data = array(
			'hotel_code' => $pms_set['hotel_web_id'],
			'arrival'    => $startdate,
			'departure'  => $enddate,
			'room_type'  => $room_codes['room']['webser_id'],
			'rate_code'  => $room_codes['code']['extra_info']['pms_code']

		);

		$rate_detail = $this->serv_api->getRateDetailDaily($data);
		if(!empty($rate_detail['RetCode'])){
			return array(
				'result' => false,
				'errmsg' => $rate_detail['ErrReason']
			);
		}
		if($rate_detail['Rate']['AdvBookin']){
			$bday = ceil((strtotime($order['startday']) - strtotime(date('Y-m-d')) / 86400));
			if($bday > $rate_detail['Rate']['AdvBookin']){
				return array(
					'result' => false,
					'errmsg' => '超过可预订时间',
				);
			}
		}

		if(empty($rate_detail['RoomRateDetails']['RoomRateDetail']['RateDetailDailys']['RateDetailDaily'])){
			return array(
				'result' => false,
				'errmsg' => '房型信息不存在'
			);
		}

		$count_day = get_room_night($starttime, $endtime, 'ceil');//至少有一个间夜

		$daily_args = $rate_detail['RoomRateDetails']['RoomRateDetail']['RateDetailDailys']['RateDetailDaily'];
		is_array(current($daily_args)) or $daily_args = array($daily_args);
		$real_price = explode(',', $order['first_detail']['real_allprice']);
		$stay_info = array();
		for($i = 0; $i < $count_day; $i++){
//		foreach($daily_args as $v){
			$v = $daily_args[$i];
			$house_date = explode('T', $v['InHouseDate']);
			$stay_info['OrderRoomStayInfo'][] = array(
				'DT'            => $house_date[0],
				'RoomTypeCode'  => $room_codes['room']['webser_id'],
				'RateAmount'    => isset($real_price[$i]) ? doubleval($real_price[$i]) : $v['prs_1'],
				'Tax'           => $v['Tax'],
				'ServiceCharge' => $v['ServiceCharge'],
				'CurrencyType'  => 'CNY',
				'RoomNum'       => 1,
				'Adults'        => 1,
				'ExtraBed'      => 0,
				'Children'      => 0,
				'RateCode'      => $room_codes['code']['extra_info']['pms_code'],
				'FixedRate'     => false,
				//				'InsertDate'      => date('Y-m-d') . 'T00:00:00',
				//				'UpdateDate'      => date('Y-m-d') . 'T00:00:00',
				//				'ID'              => 0,
				//				'OrderID'         => 0,
				//				'Points'          => 0,
				//				'DiscountAmount'  => 0,
				//				'DiscountPercent' => 0,
				//				'CouponNum'       => '',
			);
		}

		$this->load->model('api/Vmember_model', 'vm');
		$member_info = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		$member_id = '';
		$lvl_name=isset($member_info['lvl_name'])?$member_info['lvl_name']:'';
		$remark = '会员等级：' . $lvl_name . '，价格代码：' . $room_codes['code']['extra_info']['local_price_name'] . '。';
		if($order['coupon_favour'] > 0){
			$remark .= '使用优惠券:' . $order['coupon_favour'] . '元。';
		}

		if(!empty($paras['trans_no'])){
			$remark .= '该订单已使用微信支付，支付单号:' . $params['trans_no'];
		}
//		$remark.='…………这是测试订单';
//		$remark=unicode_encode($remark);
//		$remark=mb_convert_encoding($remark,'UCS-2');
//		$remark=iconv('utf-8','gbk',$remark);

		$post_data['oOrderInfo'] = array(
			'Arrival'            => date('Y-m-d', strtotime($order['startdate'])),
			'ArrivalTime'        => '18:00',
			//						'Keep_hour'             => $order['holdtime'],
			'Departure'          => date('Y-m-d', strtotime($order['enddate'])),
			'Room_num'           => $order['roomnums'],
			'Adults'             => 1,
			'Children'           => 0,
			'Extra_bed'          => 0,
			'Firstname'          => $order['name'],
			'Lastname'           => $order['name'],
			'Mobile'             => $order['tel'],
			'Phone'              => $order['tel'],
			'ChineseName'        => $order['name'],
			//						'Email'                 => $order['email'],
			//			'Email_confirm'         => $order['email'],
			'OrderRoomStayInfos' => $stay_info,
			'Hotel_code'         => array(
				'code' => $pms_set['hotel_web_id'],
			),
			'Guesttype_code'     => array(
				'code' => $member_id ? "0002" : "0000",
			),
			'Roomtype_code'      => array(
				'code' => $room_codes['room']['webser_id']
			),
			'Rate_code'          => array(
				'code' => $room_codes['code']['extra_info']['pms_code'],
			),
			/*'Channel'            => array(
				'code' => 'WEB',
			),
			'Source'             => array(
				'code' => 'LOP',
			),
			'Market'             => array(
				'code' => 'WEB',
			),*/

			'Member_id'             => array('code' => $member_id),
			'Reservation_type'      => array(
				'code' => '1',
			),
			'OrderInfoAccompanying' => array(
				'FirstName' => $order['name'],
				'Mobile'    => $order['tel'],
				//				'Email'     => $order['email'],
			),
			'Total_revenue'         => $order['price'],
			'Title'                 => array(
				'code' => 'Mm',
				'name' => '先生/女士',
			),
			'Comments'              => $remark,
		);
//		echo json_encode($order);
		$res = $this->serv_api->createReservation($post_data);
		if(!empty($res['oOrderInfo'])){
			return array(
				'result'      => true,
				'web_orderid' => $res['oOrderInfo']['ID'],
			);
		}else{
			return array(
				'result' => false,
				'errmsg' => isset($res['ErrReason']) ? $res['ErrReason'] : ''
			);
		}

	}

	function pms_enum($type = 'status'){
		switch($type){
			case 'status':
				/*0001：RESERVED  ；0003：CANCELED  ；0004：CHECK IN；0005：WAITING；0006：CHECK OUT ；0007：NO SHOW；0008：REJECTED
				*/
				//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
				return array(
					'0001' => 0,
					'0003' => 5,
					'0004' => 2,
					'0005' => 1,
					'0006' => 3,
					'0007' => 8,
					'0008' => 5
				);
				break;
			default:
				return array();
				break;
		}
	}

	function get_rooms_change_allpms($pms_state, $rooms, $params){
		$data = array();
		foreach($rooms ['rooms'] as $rm){
			if(!empty ($pms_state ['pms_state'] [$rm ['webser_id']])){
				$data [$rm ['room_id']] ['room_info'] = $rm;
				$data [$rm ['room_id']] ['state_info'] = empty ($pms_state ['valid_state'] [$rm ['webser_id']]) ? array() : $pms_state ['valid_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['show_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['lowest'] = min($pms_state ['exprice'] [$rm ['webser_id']]);
				$data [$rm ['room_id']] ['highest'] = max($pms_state ['exprice'] [$rm ['webser_id']]);
			}
		}
		return $data;
	}

	function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params = array()){
		$this->apiInit($pms_set);
//		$member_level = $params['member_level'];
//		$countday = $params['countday'];

		/*if($this->local_test === false){
			$this->serv_api->setApiAuth(json_decode($pms_set['pms_auth'], true));
		}*/

		$adata = array(
			'hotel_code' => $pms_set['hotel_web_id'],
			'arrival'    => date('Y-m-d', strtotime($startdate)),
			'departure'  => date('Y-m-d', strtotime($enddate)),
		);

		$pms_state = array();
		$valid_state = array();
		$exprice = array();

		$ailability_result = $this->serv_api->getAvailability($adata);
		if(!empty($ailability_result['RateInfos']['RateInfo'])){

			$rate_list = $ailability_result['RateInfos']['RateInfo'];
			is_array(current($rate_list)) or $rate_list = array($rate_list);
			foreach($rate_list as $v){
				$rate_info = $v['Rate'];
				$rate_name_arr = explode('|', $rate_info['name']);
				$web_rate = $rate_info['code'];
//				print_r($web_price_code);
//				print_r($web_rate);
				/*if(!in_array($web_rate, $web_price_code)){
					continue;
				}*/
//				if(in_array($web_rate, $web_price_code)){
				$room_rate_list = $v['RoomRateDetails']['RoomRateDetail'];
				$multi = false;
				foreach($room_rate_list as $k => $t){
					if(is_int($k)){
						$multi = true;
					}else{
						$multi = false;
					}
					break;
				}
				if(!$multi){
					$room_rate_list = array($room_rate_list);
				}
				foreach($room_rate_list as $t){
					$web_room = $t['RoomTypeDetail']['code'];
					if(array_key_exists($web_room, $params['web_rids'])){
						$pms_state[$web_room][$web_rate]['price_name'] = $rate_name_arr[0];
						$pms_state[$web_room][$web_rate]['price_type'] = 'pms';
						$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
						$pms_state[$web_room][$web_rate]['extra_info'] = array(
							'type'     => 'code',
							'pms_code' => $web_rate
							//																'channel_code' => $rd ['channelCode']
						);
						$pms_state[$web_room][$web_rate]['des'] = '';
						$pms_state[$web_room][$web_rate]['sort'] = 0;
						$pms_state[$web_room][$web_rate]['disp_type'] = 'buy';

						$web_set = array();
						if(isset ($params ['web_reflect'] ['web_price_code_set'] [$web_rate])){
							$web_set = json_decode($params ['web_reflect'] ['web_price_code_set'] [$web_rate], true);
						}

						$pms_state[$web_room][$web_rate]['condition'] = $web_set;

						if(isset($params['web_rids'][$web_room]) && isset($params['condit']['nums'][$params['web_rids'][$web_room]])){
							$nums = $params['condit']['nums'][$params['web_rids'][$web_room]];
						}else{
							$nums = 1;
						}

						$allprice = array();
						$amount = 0;

						if(1 == $t['RoomTypeDetail']['Status']){
							$room_status = true;
						}else{
							$room_status = false;
						}

						$rate_status = true;

						$least_arr = [];
						$least_count = 0;

						$room_rate_daily = $t['RateDetailDailys']['RateDetailDaily'];
						is_array(current($room_rate_daily)) or $room_rate_daily = array($room_rate_daily);
						foreach($room_rate_daily as $w){
							$in_date_arr = explode('T', $w['InHouseDate']);
							$pms_state[$web_room][$web_rate]['date_detail'][date('Ymd', strtotime($in_date_arr[0]))] = array(
								'price' => $w['prs_1'],
								'nums'  => $w['AvailableRooms']
							);

							$allprice[] = $w['prs_1'];
							$amount += $w['prs_1'];
							$least_arr[] = $w['AvailableRooms'];

//							$least_count = min(1, $w['AvailableRooms']);
//							$least_count > 0 or $least_count = 0;

							if($room_status && $rate_status){
								if($w['Status'] != 1){
									$rate_status = false;
								}
							}
						}

						if($least_arr){
							$least_arr[] = 1;
							$least_count = min($least_arr);
						}
						$least_count > 0 or $least_count = 0;


						$pms_state[$web_room][$web_rate]['allprice'] = implode(',', $allprice);
						$pms_state[$web_room][$web_rate]['total'] = $amount;
						$pms_state[$web_room][$web_rate]['related_des'] = '';
						$pms_state[$web_room][$web_rate]['total_price'] = $amount * $nums;

						$pms_state[$web_room][$web_rate]['avg_price'] = number_format($amount / $params ['countday'], 2, '.', '');
						$pms_state[$web_room][$web_rate]['price_resource'] = 'webservice';

						$book_status = 'full';
						if($room_status && $rate_status){
							$book_status = 'available';
						}

						$pms_state[$web_room][$web_rate]['book_status'] = $book_status;
						$exprice [$web_room][] = $pms_state[$web_room][$web_rate]['avg_price'];

						$pms_state[$web_room][$web_rate]['least_num'] = $least_count;
						$valid_state[$web_room][$web_rate] = $pms_state[$web_room][$web_rate];
					}
//					}
				}
			}
		}

		$this->load->model('common/Webservice_model', 'wm');
		$this->wm->log_service_record('获取酒店房型房态:' . json_encode($params), json_encode($pms_state), $pms_set['inter_id'], 'yage', 'get_web_roomtypee', 'quest_pms');

		return array(
			'pms_state'   => $pms_state,
			'valid_state' => $valid_state,
			'exprice'     => $exprice
		);
	}

	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		$this->apiInit($pms_set);

		$web_order = $this->serv_api->getOrder($order['web_orderid']);
		if($web_order){
			$status_arr = $this->pms_enum('status');
			$status = isset($status_arr[$web_order['Status_code']['code']]) ? $status_arr[$web_order['Status_code']['code']] : -1;
		}
		if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
			return true;
		}else{
			return false;
		}
	}

	private function apiInit($pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);

		$conf = array(
			'inter_id'     => $pms_set['inter_id'],
			'url'          => $pms_auth['url'],
			'channel_code' => $pms_auth['channel_code'],
			'secret'       => $pms_auth['secret']
		);
		$this->load->library('Baseapi/Shijiapi', $conf, 'serv_api');
	}
}
