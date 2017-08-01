<?php

class Argyle_hotel_model extends MY_Model{
	private $serv_api;
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		$this->serv_api = new Argyle_api();
	}
	
	public function get_rooms_change($rooms, $idents, $condit, $pms_set = []){
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], [
			'web_price_code_set',
			//			'rate_to_point',
			//			'point_rate_related',
			//			'point_rate_code',
		], 1, 'w2l');
		
		$web_price_code = '';
		
		$this->load->model('api/Vmember_model', 'vm');
		$member_level = $this->vm->getLvlPmsCode($condit['openid'], $idents['inter_id']);
		
		if(!empty ($condit ['price_codes'])){
			$web_price_code = $condit ['price_codes'];
			
			//对接模式是本地价格代码时，读取对应的external_code值【PMS价格代码】
			if($pms_set['pms_room_state_way'] == 3 || $pms_set['pms_room_state_way'] == 4){
				$web_code_arr = [];
				$price_code_list = $this->readDB()->from('hotel_price_info')->select('external_code')->where(['inter_id' => $pms_set['inter_id']])->where_in('price_code', explode(',', $condit['price_codes']))->get()->result_array();
				foreach($price_code_list as $v){
					$web_code_arr[] = $v['external_code'];
				}
				if($web_code_arr){
					$web_price_code = implode(',', $web_code_arr);
				}
			}
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
		$countday = get_room_night($condit['startdate'], $condit['enddate'], 'ceil');
		$web_rids = [];
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		
		$params = [
			'countday'    => $countday,
			'web_rids'    => $web_rids,
			'condit'      => $condit,
			'web_reflect' => $web_reflect,
			//			'member_level' => $member_level,
			'idents'      => $idents,
		];
		//		$web_price_code = [1, 2, 3, 4];
		
		$pms_data = $this->get_web_roomtype($pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params);
		$data = [];
		if(!empty ($pms_data)){
			switch($pms_set ['pms_room_state_way']){
				case 1 :
				case 2 :
					$data = $this->get_rooms_change_allpms($pms_data, [
						'rooms' => $rooms
					], $params);
					break;
				case 4:
					$data = $this->get_rooms_change_ratecode($pms_data, [
						'rooms' => $rooms
					], $params);
					break;
			}
		}
		return $data;
	}
	
	public function update_web_order($inter_id, $order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['orderid'],['orderid'=>$order['orderid']]);
		$istatus = -1;
		
		if($web_order){
			$status_arr = $this->pms_enum('status');
			$this->load->model('hotel/Order_model');
			//返回的数据与所预订房不一致时，只更新订单状态
			if(count($web_order) != count($order['order_details'])){
				$v = $web_order[$order['web_orderid']];
				$v['reservationStatus'] = strtoupper($v['reservationStatus']);
				$istatus = isset($status_arr[$v['reservationStatus']]) ? $status_arr[$v['reservationStatus']] : $order['status'];
				
				if($order['status'] == 4 && $istatus == 5){
					$istatus = 4;
				}
				
				if($istatus != 0 && $order['status'] == 0){
					$this->db->where(array(
						'orderid'  => $order['orderid'],
						'inter_id' => $inter_id
					));
					$this->db->update('hotel_orders', array(
						'status' => 1
					));
					$this->Order_model->handle_order($inter_id, $order ['orderid'], 1, '', array(
						'no_tmpmsg' => 1
					));
				}
				
				
				if($istatus != $order['status']){
					$this->load->model('hotel/Order_model');
					$this->change_order_status($inter_id, $order['orderid'], $istatus);
					$this->Order_model->handle_order($inter_id, $order['orderid'], $istatus, $order['openid']);
					
				}
			}else{
				//存在子订单，则操作子订单状态，入住/离店时间以及房价更新
				$local_exists = [];  //已更新至本地的子订单号
				$local_noexists = []; //还没有更新子订单号
				
				foreach($order['order_details'] as $od){
					if(!empty($od['webs_orderid'])){
						$local_exists[$od['webs_orderid']] = $od;
					}else{
						$local_noexists[] = $od;
					}
				}
				$i = 0;
				$ensure_check = 0;
				foreach($web_order as $v){
					$v['reservationStatus'] = strtoupper($v['reservationStatus']);
					$updata = [];
					if(isset($local_exists[$v['reservationCode']])){
						//该子订单号已保存至本地
						$od = $local_exists[$v['reservationCode']];
					}else{
						//还没有更新至本地子订单号
						$od = $local_noexists[$i];
						
						$this->db->where(['id' => (int)$od['sub_id']])->update('hotel_order_items', ['webs_orderid' => $v['reservationCode']]);
						
						$i++;
					}
					
					$istatus = isset($status_arr[$v['reservationStatus']]) ? $status_arr[$v['reservationStatus']] : $order['status'];
					if($od ['istatus'] == 4 && $istatus == 5){
						$istatus = 4;
					}
					// 未确认单先确认
					if($istatus != 0 && $od['istatus'] == 0 && $ensure_check == 0){
						$this->db->where(array(
							'orderid'  => $order ['orderid'],
							'inter_id' => $inter_id
						));
						$this->db->update('hotel_orders', array(
							'status' => 1
						));
						$this->Order_model->handle_order($inter_id, $order ['orderid'], 1, '', array(
							'no_tmpmsg' => 1
						));
						$ensure_check = 1;
					}
					
					//实际的入住、离店日期
					$web_start = date('Ymd', strtotime($v['arrivalDate']));
					$web_end = date('Ymd', strtotime($v['departureDate']));
					
					$ori_day_diff = get_room_night($od['startdate'], $od['enddate'], 'ceil', $od);
					$web_day_diff = get_room_night($web_start, $web_end, 'ceil', $od);//至少有一个间夜
					$day_diff = $web_day_diff - $ori_day_diff;
					
					$updata['startdate'] = $web_start;
					$updata['enddate'] = $web_end;
					if($day_diff != 0 || $web_start != $od['startdate'] || $web_end != $od['enddate']){
						$updata ['no_check_date'] = 1;
					}
					
					//每个订单的金额更新
					$ori_all_price = array_sum(explode(',', $od['allprice']));
					$ori_real_price = array_sum(explode(',', $od['real_allprice']));
					
					$discount_price = $ori_all_price - $ori_real_price;
					if(isset($v['Lodging'])){
						$new_price = $v['Lodging'];
						$new_price -= $discount_price;
						if($new_price >= 0 && $od['iprice'] != $new_price){
							$updata['new_price'] = $new_price;
							$updata ['no_check_date'] = 1;
						}
					}
					
					//PMS上的订单状态                                                 z
					if($istatus != $od['istatus']){
						$updata ['istatus'] = $istatus;
					}
					
					if(!empty ($updata)){
						$this->Order_model->update_order_item($inter_id, $order['orderid'], $od['sub_id'], $updata);
					}
				}
			}
		}
		
		return $istatus;
	}
	
	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$web_paid = 2;
		//空订单号
		if(empty($web_orderid)){
			$this->db->where([
				'orderid'  => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			]);
			//更新web_paid 状态，2为失败，1为成功
			$this->db->update('hotel_order_additions', [
				'web_paid' => $web_paid
			]);
			return false;
		}
		$this->apiInit($pms_set);
		//查询网络订单是否存在
		$web_order = $this->serv_api->queryOrder($order['orderid'],['orderid'=>$order['orderid']]);
		
		if(!$web_order){
			$this->db->where([
				'orderid'  => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			]);
			$this->db->update('hotel_order_additions', [
				'web_paid' => $web_paid
			]);
			return false;
		}
		
		//PMS上的入账接口
		$payment = [
			'remark'          => $web_order['remark'] . ' 已支付，支付单号【' . $trans_no . '】',
			'reservationType' => $pms_auth['resv_type']['paid'],
		];
		$result = $this->serv_api->modifyOrder($web_orderid, $payment,['orderid'=>$order['orderid']]);
		if($result){
			$web_paid = 1;
		}
		
		$this->db->where([
			'orderid'  => $order ['orderid'],
			'inter_id' => $order ['inter_id']
		]);
		$this->db->update('hotel_order_additions', [
			'web_paid' => $web_paid
		]);
		return $web_paid == 1;
	}
	
	
	function order_reserve($order, $pms_set, $params = []){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		$starttime = strtotime($order['startdate']);
		$endtime = strtotime($order['enddate']);
		
		if(ceil(($endtime - time()) / 86400) > 30){
			return [
				'result' => 0,
				'errmsg' => '最多可预订未来30天的房',
			];
		}
		
		$count_day = get_room_night($order['startdate'], $order['enddate'], 'ceil', $order);
		if($count_day > 90){
			return [
				'result' => 0,
				'errmsg' => '最多可预订90天的房',
			];
		}
		
		
		$startdate = date('Y-m-d', $starttime);// . 'T12:00:00';
		$enddate = date('Y-m-d', $endtime);// . 'T12:00:00';
		
		$dates = [];
		for($tmpdate = $order['startdate']; $tmpdate < $order['enddate'];){
			$dates[] = $tmpdate;
			$tmpdate = date('Ymd', strtotime($tmpdate) + 86400);
		}
		
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
		$extra_info = $room_codes['code']['extra_info'];
		
		$this->load->model('api/Vmember_model', 'vm');
		
		$member = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		
		$remark = '微信渠道！';
		
		$room_count = $order['roomnums'];
		$total_favour = 0;
		if($order['coupon_favour'] > 0){
			$remark .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
			$total_favour += $order['coupon_favour'];
			/*$coupon_arr = [];
			$coupon_des = json_decode($order['coupon_des'], true);
			if(array_key_exists('cash_token', $coupon_des)){
				$coupon_count = count($coupon_des['cash_token']);
				for($i = 0; $i < $coupon_count; $i++){
					$t = $coupon_des['cash_token'][$i];
					//将券分到每间房的记录，
					$coupon_arr[($i + 1) % $room_count][] = [
						'code'   => $t['code'],
						'amount' => $t['amount'],
					];
				}
			}

			//使用券的备注
			$rn = 0;
			foreach($coupon_arr as $v){
				//每间房的优惠信息
				$rn++;
				$tc = count($v);
				$remark .= '房间' . $rn . '【';
				for($i = 0; $i < $tc; $i++){
					$t = $v[$i];
					$remark .= date('Y-m-d', strtotime($dates[$i])) . '：使用券（' . $t['code'] . '）优惠' . $t['amount'] . '元|';
				}
				$remark .= '】';
			}*/
		}
		if($order['point_favour'] > 0){
			$remark .= '积分扣减：' . $order['point_favour'] . '元。';
			$total_favour += $order['point_favour'];
		}
		
		$daily_price = explode(',', $order ['first_detail'] ['allprice']);
		
		$resv_type = $pms_auth['resv_type']['nopay'];
		if($order['paytype'] == 'daofu'){
			$resv_type = $pms_auth['resv_type']['normal'];
		}elseif($order['paid'] == 1){
			$resv_type = $pms_auth['resv_type']['paid'];
		}
		
		$order_params = [
			'AlternateName'   => $order['name'],
			'ReservationType' => $resv_type,
			'Night'           => $count_day,
			'Adult'           => 1,
			'Child'           => 0,
			'RTC'             => $room_codes['room']['webser_id'],
			'OriginCode'      => $pms_auth['origin_code'],
			'FixedRate'       => 1,
			'PropertyCode'    => $pms_set['hotel_web_id'],
			'RoomType'        => $room_codes['room']['webser_id'],
			'ArrivalDate'     => $startdate,
			'DepartureDate'   => $enddate,
			'Rooms'           => $order['roomnums'],
			'Note'            => $remark,
			'RateCode'        => $extra_info['pms_code'],
			'FixedRateAmount' => $daily_price[0],
			'CRSpwd'          => $order['orderid'],
			//			'CRSNo'           => $order['id'],
			'Package'         => 'BFA2',
			'Source'=>$pms_auth['source'],
		];
		
		if(!empty($member['pms_user_id'])){
			$order_params['MemberCard'] = $member['membership_number'];
			$order_params['MembershipLevel'] = $member['lvl_pms_code'];
		}
		
		if($total_favour){
			$order_params['DiscountAmount'] = $total_favour;
			$order_params['DiscountReason'] = $pms_auth['discount_reason'];
		}
		
		$result = $this->serv_api->createOrder($order_params,['orderid'=>$order['orderid']]);
		
		if(!empty($result['success']) && !empty($result['data'])){
			if($result['data'] > 100){
				return [
					'result'      => 1,
					'web_orderid' => $result['data'],
				];
			}else{
				$order_err = $this->pms_enum('order_err');
				return [
					'result' => 0,
					'errmsg' => isset($order_err[$result['data']]) ? $order_err[$result['data']] : '',
				];
			}
		}else{
			return [
				'result' => 0,
				'errmsg' => isset($result['errmsg']) ? $result['errmsg'] : '',
			];
		}
	}
	
	public function cancel_order_web($inter_id, $order, $pms_set = []){
		if(empty ($order ['web_orderid'])){
			return [
				's'      => 0,
				'errmsg' => '取消失败'
			];
		}
		$this->apiInit($pms_set);
		
		$res = $this->serv_api->cancelOrder($order['web_orderid'], '用户取消',['orderid'=>$order['orderid']]);
		
		if(!empty($res['success'])){
			if($order['paytype'] == 'weixin' && $order['paid'] == 1){
				$this->load->model('hotel/Order_check_model');
				$this->Order_check_model->hotel_weixin_refund($order['orderid'], $inter_id, 'send');
			}
			
			return [
				//取消成功，直接这样return，接下来的程序会继续处理
				's'      => 1,
				'errmsg' => '取消成功'
			];
		}
		
		return [
			's'      => 0,
			'errmsg' => '取消失败,' . (isset($res['errmsg']) ? $res['errmsg'] : ''),
		];
	}
	
	public function order_to_web($inter_id, $orderid, $params = [], $pms_set = []){
		$this->load->model('hotel/Order_model');
		$order = $this->Order_model->get_main_order($inter_id, [
			'orderid' => $orderid,
			'idetail' => [
				'i'
			]
		]);
		if(!empty ($order)){
			$order = $order [0];   //获取本地已保存的订单信息
			$room_codes = json_decode($order ['room_codes'], true);
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
//			$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], TRUE);
			//kdkjlksjijkflija diljijkjosjklj
			
			/*
				构造要提交的数据
			*/
			
			$result = $this->order_reserve($order, $pms_set, $params);//提交订单
			
			if($result['result']){
				$web_orderid = $result['web_orderid'];            //取得返回的pms订单id
				$this->db->where([
					'orderid'  => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				]);
				$this->db->update('hotel_order_additions', [        //更新pms单号到本地
				                                                    'web_orderid' => $web_orderid
				]);
				
				if($order['status'] != 9){
					$this->change_order_status($inter_id, $orderid, 1);
					$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
				}
				
				$first_item = $order['order_details'][0];
				
				$this->db->where(['id' => $first_item['id']])->update('hotel_order_items', ['webs_orderid' => $web_orderid]);
				
				
				if(!empty ($params ['third_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
					$this->add_web_bill($web_orderid, $order, $pms_set, $params ['third_no']);
				}
				return [ // 返回成功
				         's' => 1
				];
			}else{
				$this->change_order_status($inter_id, $orderid, 10);
				return [ // 返回失败
				         's'      => 0,
				         'errmsg' => '提交订单失败' . ',' . $result ['errmsg']
				];
			}
		}
		return [
			's'      => 0,
			'errmsg' => '提交订单失败'
		];
	}
	
	
	public function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		
		$daily_result = $this->serv_api->getDailyPrice($pms_set['hotel_web_id'], $startdate, $enddate,['hotel_id'=>$params['idents']['hotel_id']]);
		
		$countday = $params['countday'];
		
		//每日房价
		$web_room_rate = [];
		foreach($daily_result as $v){
			$in_data = date('Ymd', strtotime($v['Date']));
			$v['in_date'] = $in_data;
//			$v['avail'] = isset($web_room_qty[$v['rmtype']][$in_data]) ? $web_room_qty[$v['rmtype']][$in_data] : 0;
			$web_room_rate[$v['Code']][$v['RateCode']][] = $v;
			//判断该价格是否用于计算积分坐价
		}
		
		$pms_state = [];
		$valid_state = [];
		$exprice = [];
		
		if($web_room_rate){
			foreach($web_room_rate as $web_room => $v){
				if(!array_key_exists($web_room, $params['web_rids'])){
					continue;
				}
				$pms_state[$web_room] = [];
				foreach($v as $web_rate => $t){
					$row = $t[0];
					
					$pms_state[$web_room][$web_rate]['price_name'] = $web_rate;
					$pms_state[$web_room][$web_rate]['price_type'] = 'pms';
					$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
					$pms_state[$web_room][$web_rate]['extra_info'] = [
						'type'     => 'code',
						'pms_code' => $web_rate,
						//								'channel_code' => $rd ['channelCode']
					];
					$pms_state[$web_room][$web_rate]['des'] = '';
					$pms_state[$web_room][$web_rate]['sort'] = 0;
					$pms_state[$web_room][$web_rate]['disp_type'] = 'buy';
					
					$web_set = [];
					if(isset ($params ['web_reflect'] ['web_price_code_set'] [$web_rate])){
						$web_set = json_decode($params ['web_reflect'] ['web_price_code_set'] [$web_rate], true);
					}
					
					$pms_state[$web_room][$web_rate]['condition'] = $web_set;
					
					if(isset($params['web_rids'][$web_room]) && isset($params['condit']['nums'][$params['web_rids'][$web_room]])){
						$nums = $params['condit']['nums'][$params['web_rids'][$web_room]];
					}else{
						$nums = 1;
					}
					
					$allprice = [];
					$amount = 0;
					
					$least_arr = [3];
					
					$date_status = true;
					
					foreach($t as $w){
						if($w['in_date'] < date('Ymd', strtotime($enddate))){
							$w['Price'] = number_format($w['Price'], 2, '.', '');
							$w['Price'] > 0 or $w['Price'] = 0;
							
							$pms_state[$web_room][$web_rate]['date_detail'][$w['in_date']] = [
								'price' => $w['Price'],
								'nums'  => $w['Price'] > 0 ? $w['Inventory'] : 0,
							];
							
							$allprice[$w['in_date']] = $w['Price'];
							$amount += $w['Price'];
							$least_arr[] = $w['Inventory'];
							
							$date_status = $date_status && $w['Inventory'] > 0 && $w['Price'] > 0;
						}
					}
					
					//校验日期价格
					$all_exists = true;
					for($start = date('Ymd', strtotime($startdate)); $start < date('Ymd', strtotime($enddate));){
						if(empty($pms_state[$web_room][$web_rate]['date_detail'][$start])){
							$all_exists = false;
							break;
						}
						$start = date('Ymd', strtotime($start) + 86400);
					}
					
					//是否所有日期都直接价格代码
					if(!$all_exists){
						unset($pms_state[$web_room][$web_rate]);
						continue;
					}
					
					ksort($allprice);
					$least_count = min($least_arr);
					$least_count > 0 or $least_count = 0;
					
					$pms_state[$web_room][$web_rate]['allprice'] = implode(',', $allprice);
					$pms_state[$web_room][$web_rate]['total'] = $amount;
					$pms_state[$web_room][$web_rate]['related_des'] = '';
					$pms_state[$web_room][$web_rate]['total_price'] = $amount * $nums;
					
					$pms_state[$web_room][$web_rate]['avg_price'] = number_format($amount / $params ['countday'], 2, '.', '');
					$pms_state[$web_room][$web_rate]['price_resource'] = 'webservice';
					
					
					$book_status = 'full';
					if($date_status){
						$book_status = 'available';
					}
					
					$pms_state[$web_room][$web_rate]['book_status'] = $book_status;
					$exprice [$web_room][] = $pms_state[$web_room][$web_rate]['avg_price'];
					
					$pms_state[$web_room][$web_rate]['least_num'] = $least_count;
					$valid_state[$web_room][$web_rate] = $pms_state[$web_room][$web_rate];
					
				}
			}
		}
		
		return [
			'pms_state'   => $pms_state,
			'valid_state' => $valid_state,
			'exprice'     => $exprice,
		];
	}
	
	public function get_rooms_change_ratecode($pms_data, $rooms, $params){
		$local_rooms = $rooms ['rooms'];
		$condit = $params ['condit'];
		$this->load->model('hotel/Order_model');
		$data = $this->Order_model->get_rooms_change($local_rooms, $params ['idents'], $params ['condit']);
		$pms_state = $pms_data ['pms_state'];
		$valid_state = $pms_data ['valid_state'];
		
		$merge = [
//			'price_name',
'least_num',
'book_status',
'extra_info',
'date_detail',
//			'avg_price',
//			'allprice',
//			'total',
//			'total_price',
		];
		
		foreach($data as $room_key => $lrm){
			$min_price = [];
			if(empty ($valid_state [$lrm ['room_info'] ['webser_id']])){
				unset ($data [$room_key]);
				continue;
			}
			
			$nums = isset ($condit ['nums'] [$lrm ['room_info'] ['room_id']]) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
			
			if(!empty($lrm['state_info'])){
				
				foreach($lrm['state_info'] as $sik => $si){
					
					//需要设置PMS价格代码值
					$web_rate = $si['external_code'];
					
					if($web_rate === '' || empty($pms_state[$lrm['room_info']['webser_id']][$web_rate])){//PMS上不存在该价格代码
						unset($data[$room_key]['state_info'][$sik]);
						continue;
					}
					
					//PMS上的房态数据
					$tmp = $pms_state[$lrm['room_info']['webser_id']][$web_rate];
					foreach($merge as $w){
						if(isset($tmp[$w])){
							
							if($w == 'date_detail'){
								$allprice = '';
								$amount = 0;
								foreach($tmp [$w] as $dk => $td){
									if($si['related_cal_way'] && $si['related_cal_value']){
										$tmp [$w] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si['related_cal_way'], $si['related_cal_value'], 'price'));
									}else{
										$tmp [$w] [$dk] ['price'] = $td ['price'];
									}
									$tmp [$w] [$dk] ['nums'] = $tmp['least_num'];
									$allprice .= ',' . $tmp [$w] [$dk] ['price'];
									$amount += $tmp [$w] [$dk] ['price'];
								}
								
								$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday'], 2);
								$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr($allprice, 1);
								$data [$room_key] ['state_info'] [$sik] ['total'] = intval($amount);
								$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
							}
							$data[$room_key]['state_info'][$sik][$w] = $tmp[$w];
						}
					}
					$avg_price = str_replace(',', '', $data[$room_key]['state_info'][$sik]['avg_price']);;
					if($avg_price > 0)
						$min_price[] = $avg_price;
//					}
				}
			}
			$data[$room_key]['lowest'] = empty($min_price) ? 0 : min($min_price);
			$data[$room_key]['highest'] = empty($min_price) ? 0 : max($min_price);
			/*if(empty($lrm['show_info'])){
				$lrm['show_info'] = $lrm['state_info'];
				$data[$room_key]['show_info'] = $lrm['state_info'];
			}*/
			foreach($lrm['show_info'] as $sik => $si){
				//需要设置PMS价格代码值
				$web_rate = $si['external_code'];
				if($web_rate === '' || empty($pms_state[$lrm['room_info']['webser_id']][$web_rate])){//PMS上不存在该价格代码
//					echo '<pre>';print_r($pms_state[$lrm['room_info']['webser_id']]);print_r($lrm);exit;
					unset($data[$room_key]['show_info'][$sik]);
					continue;
				}
				
				//PMS上的房态数据
				$tmp = $pms_state[$lrm['room_info']['webser_id']][$web_rate];
				foreach($merge as $w){
					if(isset($tmp[$w])){
						
						if($w == 'date_detail'){
							$allprice = '';
							$amount = 0;
							foreach($tmp [$w] as $dk => $td){
								if($si['related_cal_way'] && $si['related_cal_value']){
									$tmp [$w] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si['related_cal_way'], $si['related_cal_value'], 'price'));
								}else{
									$tmp [$w] [$dk] ['price'] = $td ['price'];
								}
								$tmp [$w] [$dk] ['nums'] = $tmp['least_num'];
								$allprice .= ',' . $tmp [$w] [$dk] ['price'];
								$amount += $tmp [$w] [$dk] ['price'];
							}
							
							$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday'], 2);
							$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr($allprice, 1);
							$data [$room_key] ['show_info'] [$sik] ['total'] = intval($amount);
							$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
						}
					}
					
					$data[$room_key]['show_info'][$sik][$w] = $tmp[$w];
				}
			}
			if(empty($data[$room_key]['state_info'])){
				unset($data[$room_key]);
			}
		}
		return $data;
	}
	
	private function get_rooms_change_allpms($pms_state, $rooms, $params){
		$data = [];
		foreach($rooms ['rooms'] as $rm){
			if(!empty ($pms_state ['pms_state'] [$rm ['webser_id']])){
				$data [$rm ['room_id']] ['room_info'] = $rm;
				$data [$rm ['room_id']] ['state_info'] = empty ($pms_state ['valid_state'] [$rm ['webser_id']]) ? [] : $pms_state ['valid_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['show_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['lowest'] = min($pms_state ['exprice'] [$rm ['webser_id']]);
				$data [$rm ['room_id']] ['highest'] = max($pms_state ['exprice'] [$rm ['webser_id']]);
			}
		}
		
		return $data;
	}
	
	function pms_enum($type){
		switch($type){
			case 'status' :
				return [
					//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
					
					'NOG'          => 1,
					'PP'           => 1,
					'PR'           => 1,
					'CHECKED IN'   => 2,
					'CHECKED OUT'  => 3,
					'CANCELLATION' => 5,
					'NO SHOW'      => 8,
				];
				break;
			case 'order_err':
				return [
					'001' => '入住时间不能早于当天时间',
					'002' => '此房型可用房间数不足',
					'003' => '此房型已关房',
					'004' => '此房型已满房',
					'005' => '此房型未配置房价',
					'006' => '此房型不存在',
					'007' => '此产品不存在',
					'008' => '酒店编码不存在',
					'009' => '预订期间价格无效',
					'010' => '订单失败，不是有效的产品',
					'011' => '订单失败，不是有效的入住时间',
					'012' => '此订单已存在，重复下单',
					'013' => '此房型可用房间数不足',
					'014' => '此房型已满房',
					'015' => '此房型已关房',
					'016' => '超出最大预定限额',
					'017' => '担保错误',
					'018' => '备注信息不接受',
					'019' => '订单失败，不是有效的产品',
					'020' => '订单失败，不是有效的入住时间',
					'021' => '此房型可用房间数不足',
					'022' => '此房型已满房',
					'023' => '此房型已关房',
					'024' => '超出最大预定限额',
					'025' => '担保错误',
					'026' => '订单失败，原订单号不存在',
					'027' => '此订单已取消，重复取消',
					'028' => '取消失败，订单不存在',
					'029' => '取消失败，订单不存在',
					'030' => '此订单已入住，不能取消',
					'031' => '此订单已离店，不能取消',
					'032' => '此订单noshow，不能取消',
					'033' => '取消失败，订单规则为不可取消',
					'034' => '网络问题',
					'035' => '程序错误',
					'036' => '服务器异常',
				];
				break;
			default :
				return [];
				break;
		}
	}
	
	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['orderid']);
		$check = false;
		if($web_order){
			$check = true;
			$status_arr = $this->pms_enum('status');
			foreach($web_order as $v){
				$v['reservationStatus'] = strtoupper($v['reservationStatus']);
				$status = $status = isset($status_arr[$v['reservationStatus']]) ? $status_arr[$v['reservationStatus']] : -1;
				$check = $check && ($status == 1 || $status == 0);
			}
		}
		return $check;
	}
	
	private function change_order_status($inter_id, $orderid, $status){
		$this->db->where([
			'orderid'  => $orderid,
			'inter_id' => $inter_id
		]);
		$this->db->update('hotel_orders', [ // 提交失败，把订单状态改为下单失败
		                                    'status' => (int)$status
		]);
	}
	
	private function apiInit($pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$pms_auth['inter_id'] = $pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
	}
	
	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1', true);
		}
		return $db_read;
	}
	
}

class Argyle_api{
	private $user;
	private $pwd;
	private $lang;
	private $group;
	private $inter_id;
	private $url;
	private $CI;
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->helper('common');
		$this->CI->load->model('common/Webservice_model');
	}
	
	public function setPmsAuth($pms_auth){
		$this->url = $pms_auth['url'];
		$this->user = $pms_auth['user'];
		$this->pwd = $pms_auth['pwd'];
		$this->lang = $pms_auth['lang'];
		$this->group = $pms_auth['group'];
		$this->inter_id = $pms_auth['inter_id'];
	}
	
	public function getDailyPrice($hotel_web_id, $startdate, $end, $func_data = []){
		$params = [
			'PropertyCode'  => $hotel_web_id,
			'ArrivalDate'   => date('Y-m-d', strtotime($startdate)),
			'DepartureDate' => date('Y-m-d', strtotime($end)),
			'Rooms'         => '',
			'Persons'       => '',
		];
		$url = $this->url . '/crs1GetRoomTypeListJson';
		$result = $this->httpPost($url, $params, $func_data);
		if($result['success'] && !empty($result['data'])){
			return $result['data'];
		}
		return [];
	}
	
	public function cancelOrder($web_orderid, $reason = '', $func_data = []){
		$params = [
			'reservationCode'      => $web_orderid,
			'rvCancellationReason' => $reason,
		];
		$url = $this->url . '/crsIfcReservationCancelJson';
		$result = $this->httpPost($url, $params, $func_data);
		if($result['success'] && !empty($result['data'])){
			if(strpos($result['data'][0]['msgInfo'], 'OK') !== false){
				return [
					'success' => true,
				];
			}
			$result = [
				'success' => false,
				'errmsg'  => $result['data'][0]['msgInfo']
			];
		}
		return $result;
	}
	
	public function createOrder($params, $func_data = []){
		array_key_exists('ProductList', $params) or $params['ProductList'] = '';
		$url = $this->url . '/crsRvInsertJson';
		$default_params = [
			'LastName'              => '',
			'FirstName'             => '',
			'AlternateName'         => '',
			'Gender'                => '',
			'Title'                 => '',
			'MemberCard'            => '',
			'MembershipLevel'       => '',
			'Country'               => '',
			'Language'              => '',
			'Nation'                => '',
			'VipLevel'              => '',
			'Preference'            => '',
			'Phone'                 => '',
			'Address'               => '',
			'Company'               => '',
			'Agent'                 => '',
			'Source'                => '',
			'Sales'                 => '',
			'Block'                 => '',
			'Party'                 => '',
			'CallerName'            => '',
			'CallerPhone'           => '',
			'ReserveName'           => '',
			'ReservationType'       => '',
			'ArrivalDate'           => '',
			'Night'                 => '',
			'Adult'                 => '',
			'Child'                 => '',
			'Rooms'                 => '',
			'RoomType'              => '',
			'RTC'                   => '',
			'UpgradeReason'         => '',
			'Room'                  => '',
			'RateCode'              => '',
			'DiscountAmount'        => '',
			'DiscountReason'        => '',
			'ShareCode'             => '',
			'ShareType'             => '',
			'PrintRate'             => '',
			'NoPost'                => '',
			'Package'               => '',
			'MarketCode'            => '',
			'SourceCode'            => '',
			'OriginCode'            => '',
			'PaymentType'           => '',
			'CreditCardNo'          => '',
			'CreditCardExpireDate'  => '',
			'ApproveAmount1'        => '',
			'ApproveCode'           => '',
			'Special'               => '',
			'RoomFeature'           => '',
			'ETA'                   => '',
			'ETD'                   => '',
			'ExtensionRating'       => '',
			'Confidential'          => '',
			'Note'                  => '',
			'CashierNote'           => '',
			'InsertUser'            => '',
			'InsertDate'            => '',
			'OriginalDepartureDate' => '',
			'CRSNo'                 => '',
			'CRSpwd'                => '',
			'FixedRate'             => '',
			'FixedRateAmount'       => '',
			'VOD'                   => '',
			'PropertyCode'          => '',
			'Email'                 => '',
			'MoreInfo'              => '',
		];
		$params = array_merge($default_params, $params);
		$result = $this->httpPost($url, $params, $func_data);
		if($result['success'] && !empty($result['data'])){
			$result['data'] = array_shift($result['data']);
			$result['data'] = end($result['data']);
		}
		return $result;
	}
	
	public function queryOrder($crs_no, $func_data = []){
//		$crs_no='94148516129631705';
		$default_params = [
			'PropertyName'      => '',
			'confirmationNo'    => '',
			'lastName'          => '',
			'firstName'         => '',
			'company'           => '',
			'agent'             => '',
			'source'            => '',
			'block'             => '',
			'ContactName'       => '',
			'arrivalDateFrom'   => '',
			'arrivalDateTo'     => '',
			'departureDateFrom' => '',
			'departureDateTo'   => '',
			'roomType'          => '',
			'room'              => '',
			'rateCode'          => '',
			'rvType'            => '',
			'rvStatus'          => '',
			'rvMarketCode'      => '',
			'rvSourceCode'      => '',
			'rvPaymentType'     => '',
			'pfCountry'         => '',
			'memberCard'        => '',
			'membershipLevel'   => '',
			'pfVipLevel'        => '',
			'rvParty'           => '',
			'rvStayDate'        => '',
		];
		$params = [
			'rvCrsNo' => $crs_no
		];
		$params = array_merge($default_params, $params);
		$url = $this->url . '/crsRvListJson';
		$result = $this->httpPost($url, $params, $func_data);
		$list = [];
		if($result['success'] && !empty($result['data'])){
			foreach($result['data'] as $v){
				$list[$v['reservationCode']] = $v;
			}
		}
		return $list;
	}
	
	public function modifyOrder($web_orderid, $data, $func_data = []){
		$params = [
			'reservationCode' => $web_orderid,
		];
		$url = $this->url . '/crsIfcReservationUpdateJson';
		$state = true;
		foreach($data as $k => $v){
			$params['ColName'] = $k;
			$params['finalValue'] = $v;
			
			$result = $this->httpPost($url, $params, $func_data);
			$st = false;
			if($result['success'] && !empty($result['data'])){
				if(strpos($result['data'][0]['msgInfo'], 'OK') !== false){
					$st = true;
				}
			}
			$state = $state && $st;
		}
		return $state;
	}
	
	private function commonField(){
		$params = [
			'user'      => $this->user,
			'pwd'       => $this->pwd,
			'Language'  => $this->lang,
			'GroupCode' => $this->group,
			'rule'      => '',
		];
		return $params;
	}
	
	public function httpPost($url, $params, $func_data = []){
		
		$params = array_merge($this->commonField(), $params);
		$query_str = http_build_query($params);
		$time = time();
		$res = doCurlPostRequest($url, $query_str, [], 15);
		
		$run_alarm = 0;
		
		$this->CI->Webservice_model->add_webservice_record($this->inter_id, 'argyle', $url, $params, $res, 'query_post', $time, microtime(), $this->CI->session->userdata($this->inter_id . 'openid'));
		
		$s = null;
		if(strpos($res, '<?xml') !== false){
			//返回XML格式
			$arr = xml2array($res);
			$arr = json_decode($arr[0], true);
			$data = [];
			foreach($arr as $k => $v){
				if(isset($v['']) && $v[''] == '|'){
					continue;
				}
				$data[$k] = $v;
			}
			$s = $data;
			$result = [
				'success' => true,
				'data'    => $data,
			];
		}else{
			$run_alarm = 1;
			$s = $res;
			
			$result = [
				'success' => false,
				'errmsg'  => $res,
			];
		}
		
		$func = substr($url, strrpos($url, '/'));
		$this->checkWebResult($func, $params, $s, $time, microtime(), $func_data, ['run_alarm' => $run_alarm]);
		
		return $result;
	}
	
	
	protected function checkWebResult($func_name, $send, $receive, $now, $micro_time, $func_data = [], $params = []){
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
				case 'crs1GetRoomTypeListJson':
					if(empty($receive)){
						$err_msg = '空数据';
						$err_lv = 2;
					}
					break;
				case 'crsIfcReservationCancelJson':
					if(empty($receive)){
						$err_msg = '接口错误';
						$err_lv = 1;
					}elseif(strpos($receive[0]['msgInfo'], 'OK') === false){
						$err_lv = 1;
						$err_msg = $receive[0]['msgInfo'];
					}
					break;
				case 'crsRvInsertJson':
					if(empty($receive)){
						$err_msg = '接口错误';
						$err_lv = 1;
					}else{
						$receive = array_shift($receive);
//							$receive= end($receive);
						if($receive <= 100){
							$err_lv = 1;
							$err_msg = $this->pms_enum('order_err', $receive);
						}
					}
					break;
				case 'crsRvListJson':
					if(empty($receive)){
						$err_lv = 2;
						$err_msg = '空数据';
					}
					break;
				case 'crsIfcReservationUpdateJson':
					if(empty($receive)){
						$err_lv = 1;
						$err_msg = '接口错误';
					}elseif(strpos($receive[0]['msgInfo'], 'OK') === false){
						$err_lv = 1;
						$err_msg = $receive[0]['msgInfo'];
					}
					break;
			}
			
		}
		
		$this->CI->Webservice_model->webservice_error_log($this->inter_id, 'argyle', $err_lv, $err_msg, array(
			'web_path'        => $this->url,
			'send'            => $send,
			'receive'         => $receive,
			'send_time'       => $now,
			'receive_time'    => $micro_time,
			'fun_name'        => $func_name_des,
			'alarm_wait_time' => $alarm_wait_time
		), $func_data);
	}
	
	private function pms_enum($type = '', $key = ''){
		$arr = [];
		switch($type){
			case 'func_name':
				$arr = [
					'crs1GetRoomTypeListJson'     => '房态读取',
					'crsRvListJson'               => '查询订单',
					'crsRvInsertJson'             => '新建订单',
					'crsIfcReservationCancelJson' => '取消订单',
					'crsIfcReservationUpdateJson' => '修改订单',
				];
				break;
			case 'order_err':
				$arr = [
					'001' => '入住时间不能早于当天时间',
					'002' => '此房型可用房间数不足',
					'003' => '此房型已关房',
					'004' => '此房型已满房',
					'005' => '此房型未配置房价',
					'006' => '此房型不存在',
					'007' => '此产品不存在',
					'008' => '酒店编码不存在',
					'009' => '预订期间价格无效',
					'010' => '订单失败，不是有效的产品',
					'011' => '订单失败，不是有效的入住时间',
					'012' => '此订单已存在，重复下单',
					'013' => '此房型可用房间数不足',
					'014' => '此房型已满房',
					'015' => '此房型已关房',
					'016' => '超出最大预定限额',
					'017' => '担保错误',
					'018' => '备注信息不接受',
					'019' => '订单失败，不是有效的产品',
					'020' => '订单失败，不是有效的入住时间',
					'021' => '此房型可用房间数不足',
					'022' => '此房型已满房',
					'023' => '此房型已关房',
					'024' => '超出最大预定限额',
					'025' => '担保错误',
					'026' => '订单失败，原订单号不存在',
					'027' => '此订单已取消，重复取消',
					'028' => '取消失败，订单不存在',
					'029' => '取消失败，订单不存在',
					'030' => '此订单已入住，不能取消',
					'031' => '此订单已离店，不能取消',
					'032' => '此订单noshow，不能取消',
					'033' => '取消失败，订单规则为不可取消',
					'034' => '网络问题',
					'035' => '程序错误',
					'036' => '服务器异常',
				];
				break;
		}
		if($key === ''){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}
}