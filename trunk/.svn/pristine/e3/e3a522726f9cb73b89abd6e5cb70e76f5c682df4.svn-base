<?php

class Jinjiang_hotel_model extends MY_Model{
	const TAB_HO = 'hotel_orders';
	const TAB_HOA = 'hotel_order_additions';

	private $local_test = true;

	public function __construct(){
		parent::__construct();
		$this->load->library('JinService', array(), 'serv_api');
		$this->load->helper('common');
	}

	public function get_rooms_change($rooms, $idents, $condit, $pms_set = array()){
		//		$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], TRUE);
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array(
			'member_level',
			'web_price_code',
			'web_price_code_set',
		), 1, 'w2l');

		$this->load->model('api/Vmember_model','vm');
		$member_level=$this->vm->getLvlPmsCode($condit['openid'],$idents['inter_id']);

		$member_level = isset ($web_reflect ['member_level'] [$member_level]) ? $web_reflect ['member_level'] [$member_level] : '';
		$web_price_code = '';

		if(!empty ($condit ['price_codes'])){
			$web_price_code = $condit ['price_codes'];
		} else{
			if(!empty ($web_reflect ['web_price_code'])){
				foreach($web_reflect ['web_price_code'] as $wpc){
					$web_price_code .= ',' . $wpc;
				}
			}
			$web_price_code .= isset ($web_reflect ['member_price_code'] [$member_level]) ? ',' . $web_reflect ['member_price_code'] [$member_level] : '';
			$web_price_code = substr($web_price_code, 1);
		}
		/*if(!empty($web_reflect['not_show'])){
			$no_coupon = isset($web_reflect['not_show']['coupon_rate']) ? explode(',', $web_reflect['not_show']['coupon_rate']) : array();
			$no_rate = isset($web_reflect['not_show']['ratecode']) ? explode(',', $web_reflect['not_show']['ratecode']) : array();
		} else{
			$no_coupon = array();
			$no_rate = array();
		}*/
		$web_price_code = explode(',', $web_price_code);
		$countday = get_room_night($condit ['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$web_rids = array();
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		$params = array(
			'countday'     => $countday,
			'web_rids'     => $web_rids,
			'condit'       => $condit,
			'web_reflect'  => $web_reflect,
			'member_level' => $member_level,
			//			'no_coupon'    => $no_coupon,
			//			'no_rate'      => $no_rate
		);

		if(!empty ($web_price_code)){
			$pms_data = $this->get_web_roomtype($pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params);
		}
		$data=[];
		if(!empty ($pms_data)){
			switch($pms_set ['pms_room_state_way']){
				case 1 :
				case 2 :
					$data = $this->get_rooms_change_allpms($pms_data, array(
						'rooms' => $rooms
					), $params);
					break;
			}
		}
		return $data;
	}
	function getresvaudit($web_orderid, $pms_set = array()){
		$data = array(
			'cnfnum' => $web_orderid,
			'iata'   => JinService::IATA,
		);
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		$result = $this->serv_api->getResvAudit($pms_set['hotel_web_id'], $data);
//		$result = xml2array($xml);
		return $result;
	}

	function pms_enum($type = 'status'){
		switch($type){
			case 'status' :
				return array(
					//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
					/*
					 订单处理状态：
酒店待处理(0),
酒店已确认(1),
酒店需手工处理(2),
酒店自行取消(9)

					0	未处理
1	已处理
5	noshow
7	已入住
8	已离店

*/
					'0' => 0,
					'1' => 1,
					'2' => 0,
					'5' => 8,
					'7' => 2,
					'8' => 3,
					'9' => 5,
					'c' => 5
				);
				break;
			case 'roomstatus':
				/*0	未处理
1	入住
2	noshow
3	离店
4	取消单
*/
				return array(
					'0' => 0,
					'1' => 2,
					'2' => 8,
					'3' => 3,
					'4' => 5
				);
				break;
			default :
				return array();
				break;
		}
	}


	public function update_web_order($inter_id, $order, $pms_set){
		$web_order = $this->get_web_order($order['web_orderid'], $pms_set, true);
//		echo '<pre>';
//		print_r($web_order);
//		exit;
		$istatus = -1;

//		$this->load->model('common/Webservice_model', 'wm');
//		$this->wm->log_service_record('PMS订单信息', json_encode($web_order), $order['inter_id'], 'jinjiang', 'get_web_order', 'query_post');

		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');
			$this->load->model('hotel/Order_model');
			$ensure_check = 0;
			/*if((float)$web_order['bookedrates']['totalrevenue'] != (float)$order['price']){
				$this->db->where(array(
					                 'orderid'  => $order ['orderid'],
					                 'inter_id' => $inter_id
				                 ));
				$this->db->update('hotel_orders', array(
					'price' => (float)$web_order['bookedrates']['totalrevenue']
				));
			}*/

			//先判断是否为取消单
			if($web_order['status'] == 'c'){
				$status = $status_arr[$web_order['status']];
			} else{
				$status = $status_arr[$web_order['sign']];
			}

			// 未确认单先确认
			if($status != 0 && $order ['status'] == 0 && $ensure_check == 0){
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

			foreach($order ['order_details'] as $od){
				$webs_orderid = $od ['webs_orderid'];
				if(!isset($web_order['room_list'][$webs_orderid])){
					continue;
				}
				$web_room_order = $web_order['room_list'][$webs_orderid];

				$room_status = $this->pms_enum('roomstatus');

				$istatus = $room_status[$web_room_order['roomstatus']];

//				$this->wm->log_service_record('PMS订单房型信息', json_encode($web_room_order) . '，本地状态：' . $istatus, $order['inter_id'], 'jinjiang', 'get_order_room', 'query_post');

				if($od ['istatus'] == 4 && $status == 5){
					$istatus = 4;
					$status = 4;
				}

				$date_info = $web_order['staydetail'];
				if($web_room_order['realindate']){
					$web_start = date('Ymd', strtotime($web_room_order['realindate']));
				}else{
					$web_start = date('Ymd', strtotime($date_info['indate']));
				}

				if($web_room_order['realoutdate']){
					$web_end = date('Ymd', strtotime($web_room_order['realoutdate']));
				}elseif($date_info['departuredate']){
					$web_end = date('Ymd', strtotime($date_info['departuredate']));
				} else{
					$web_end = date('Ymd', strtotime($date_info['outdate']));
				}
				$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
				$ori_day_diff = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
				$web_day_diff = get_room_night($web_start,$web_end,'ceil');//至少有一个间夜
				$day_diff = $web_day_diff - $ori_day_diff;

				$updata = array();
				if($istatus != $od ['istatus']){
					$updata ['istatus'] = $istatus;
				}
				$updata ['startdate'] = $web_start;
				$updata ['enddate'] = $web_end;
				if($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']){
					$updata ['no_check_date'] = 1;
				}

				if(!empty($web_order['bookedrates']['bookedrate'])){
					$rate_list = $web_order['bookedrates']['bookedrate'];
					is_array(current($rate_list)) or $rate_list = array($rate_list);
					$web_price = 0;
					//订单的每日价格
					/*$daily_rate = array();
					foreach($rate_list as $t){
						$daily_rate[date('Ymd', strtotime($t['date']))] = $t['rate'];
					}*/

					for($j = 0; $j < $web_day_diff; $j++){
						/*if(!empty($daily_rate[date('Ymd', (strtotime($web_start) + 86400 * $j))])){
							$web_price += $daily_rate[date('Ymd', (strtotime($web_start) + 86400 * $j))];
						}else{
							$web_price+=$rate_list[0]['rate'];
						}*/
						$web_price += isset($rate_list[$j]['rate'])?$rate_list[$j]['rate']:0;
					}
					/*foreach($rate_list as $t){
						$web_price += $t['rate'];
					}*/
					//子单价格
					$allp = explode(',', $od['allprice']);
					$all_price = array_sum($allp);
					//含 优惠券的价格
					$rallp = explode(',', $od['real_allprice']);
					$real_price = array_sum($rallp);
					//优惠的金额
					$dis_price = $all_price - $real_price;
					/*if($dis_price > 0){
						$web_price = $web_price - $dis_price;
						$web_price > 0 or $web_price = 0;
					}*/

					$new_price=$web_price-$dis_price;
					if($new_price>=0&&$new_price!=$od['iprice']){
						$updata['new_price'] = $web_price;
						$updata ['no_check_date'] = 1;
					}
				} else{
					if($web_day_diff == 1){
						$updata ['new_price'] = floatval($od ['real_allprice']);
						$updata ['no_check_date'] = 1;
					}
				}

				if(!empty ($updata)){
					$this->Order_model->update_order_item($inter_id, $order ['orderid'], $od ['sub_id'], $updata);
//					$this->wm->log_service_record('子订单更新资料:' . json_encode($updata), '', $order['inter_id'], 'jinjiang', 'update_order_item', 'query_post');
				}
			}

		}
		return $istatus;
	}

	private function fail_order($inter_id, $orderid, $status = 10){
		$this->db->where(array(
			                 'orderid'  => $orderid,
			                 'inter_id' => $inter_id
		                 ));
		$this->db->update('hotel_orders', array( // 提交失败，把订单状态改为下单失败
		                                         'status' => (int)$status
		));
	}

	public function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()){
		$this->load->model('hotel/Order_model');
		$order = $this->Order_model->get_main_order($inter_id, array(
			'orderid' => $orderid,
			'idetail' => array(
				'i'
			)
		));
		if(!$order){
			return array(
				's'      => 0,
				'errmsg' => '订单不存在'
			);
		}

		$order = $order [0];

		$res = $this->order_reserve($order, $pms_set, $params);

		if(!$res['result']){
			$this->fail_order($inter_id, $orderid);
			return array(
				's'      => 0,
				'errmsg' => $res['errmsg']
			);
		} else{
			$web_orderid = $res['oid'];
			$this->db->where(array(
				                 'orderid'  => $order ['orderid'],
				                 'inter_id' => $order ['inter_id']
			                 ));
			$this->db->update('hotel_order_additions', array(        //更新pms单号到本地
			                                                         'web_orderid' => $web_orderid
			));

			//更新子订单
			$child_list = $this->readDB()->where(array(
				                               'orderid'  => $order ['orderid'],
				                               'inter_id' => $order ['inter_id']
			                               ))->from('hotel_order_items')->select('id')->get()->result_array();
			$child_count = count($child_list);
			$child_oid = explode(',', $res['child_oid']);
			for($i = 0; $i < $child_count; $i++){
				if(isset($child_oid[$i])){
					$this->db->where(array('id' => (int)$child_list[$i]['id']))->update('hotel_order_items', array('webs_orderid' => $child_oid[$i]));
				}
			}
			if($order ['status'] != 9 && 'RT' == $res['resvclass']){
				$this->db->where(array(
					                 'orderid'  => $order ['orderid'],
					                 'inter_id' => $order ['inter_id']
				                 ));
				$this->db->update('hotel_orders', array(
					'status' => 1
				));

				$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
			}

			if(!empty ($params ['trans_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
				$this->add_web_bill($web_orderid, $order, $pms_set, $params ['trans_no']);
			}

			return array('s' => 1);
		}

	}

	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no){
		$web_paid = 2;
		//空订单号
		if(empty($web_orderid)){
			$this->db->where(array(
				                 'orderid'  => $order ['orderid'],
				                 'inter_id' => $order ['inter_id']
			                 ));
			$this->db->update('hotel_order_additions', array( //更新web_paid 状态，2为失败，1为成功
			                                                  'web_paid' => $web_paid
			));
			return false;
		}
		//查询网络订单是否存在
		$web_order = $this->get_web_order($web_orderid, $pms_set);

		if(!$web_order){
			$this->db->where(array(
				                 'orderid'  => $order ['orderid'],
				                 'inter_id' => $order ['inter_id']
			                 ));
			$this->db->update('hotel_order_additions', array(
				'web_paid' => $web_paid
			));
			return false;
		}

		$params = array(
			'confnum'  => $web_orderid,
			'iata'     => JinService::IATA,
			'isfrompp' => 1,

		);

		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		$res = $this->serv_api->setReliefHoldResv($pms_set['hotel_web_id'], $params);
//		$res = xml2array($xml);

		if('success' == $res['@attributes']['result']){
			$web_paid = 1;

			$rateplan_arr = explode('-', $web_order['staydetail']['rateplan']);
			$data = [
				'confnum'     => $web_order['confnum'],
				'staydetail'  => [
					'date'      => $web_order['staydetail']['indate'],
					'nights'    => $web_order['staydetail']['nights'],
					'roomtype'  => $web_order['staydetail']['roomtype'],
					'rateclass' => $rateplan_arr[1],
					'rooms'     => $web_order['staydetail']['rooms'],
					'adults'    => $web_order['staydetail']['adults'],
					'children'  => $web_order['staydetail']['children'],
					'channel'   => 'Website',
				],
				'guestinfo'   => [
					'firstname'  => $web_order['guestinfo']['firstname'],
					'lastname'   => $web_order['guestinfo']['lastname'],
					'otherguest' => [],
					'holdTime'   => $web_order['guestinfo']['holdTime'],
					'phone'      => $web_order['guestinfo']['phone'],
					'email'      => $web_order['guestinfo']['email'],
					'mobile'     => $web_order['guestinfo']['mobile'],
					'street1'    => $web_order['guestinfo']['street1'],
				],
				/*'paymentinfo'=>[
					'payment'=>$web_order['paymentinfo']['payment'],
					'paymentstatus'=>1,
					'paymentamount'=>$web_order['paymentinfo']['paymentamount'],
					'paymentsource'=>$web_order['paymentinfo']['paymentsource'],
					'tradeno'=>$trans_no,
//				    'paidurl'=>$web_order['paymentinfo']['payment'],
//				    'returnurl'=>$web_order['paymentinfo']['payment'],
				],*/
				'paymentinfo' => $web_order['paymentinfo'],
				'remarks'     => $web_order['remarks'],
				'memberinfo'  => $web_order['memberinfo'],
				'miscinfo'    => [
					'IATA' => $web_order['miscinfo']['iata'],
				],
				'tracelogid'  => $web_order['tracelogid'],
				'couponinfo'  => $web_order['couponinfo'],
				'bookedrates' => [
					'bookedrate' => $web_order['bookedrates']['bookedrate'],
				],
				'isassure'    => 4,
			];


			if($trans_no){
//				$data['holdresv'] = 1;
				$data['paymentinfo']['paymentstatus'] = 1;
				$data['paymentinfo']['tradeno'] = $trans_no;
			}

			$this->serv_api->setModResv($pms_set['hotel_web_id'], $data);
		}
		$this->db->where(array(
			                 'orderid'  => $order ['orderid'],
			                 'inter_id' => $order ['inter_id']
		                 ));
		$this->db->update('hotel_order_additions', array(
			'web_paid' => $web_paid
		));
		return $web_paid==1;
	}

	public function get_order_state($order, $pms_set, $status_des = array()){
		$web_order = $this->get_web_order($order['web_orderid'], $pms_set);

		if(!$web_order){
			return array();
		}

//		!empty($status_des) or $status_des = $this->pms_enum('status');

		$state = array();
		$web_des = isset($status_des[$order['status']]) ? $status_des[$order['status']] : null;
		$can_cancel = null;
		$web_re_pay = null;
		$web_check = null;
		$web_comment = null;

		//判断订单时间
		$intime = strtotime($order['startdate']);
		if(mktime(12, 0, 0, date('m', $intime), date('d', $intime), date('Y', $intime)) < time()){
			if($order ['paytype'] == 'daofu'){
				//非预付订单，时间超过入住时间当天的12点时不能取消
				$can_cancel = 0;
			}
		}

		$state ['can_cancel'] = $can_cancel;
		$state ['re_pay'] = $web_re_pay;
		$state ['web_check'] = $web_check;
		$state ['web_des'] = $web_des;
		$state['web_comment'] = $web_comment;

		return $state;

	}

	function get_web_order($web_orderid, $pms_set, $sub_order = false){
		$params = array(
			'confnum' => $web_orderid,
			'iata'    => JinService::IATA,
		);

		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		$res = $this->serv_api->getPropResv($pms_set['hotel_web_id'], $params);

//		$res = xml2array($xml);
		if('success' == $res['@attributes']['result'] && !empty($res['resvdata']['reservation'])){
			$web_order = $res['resvdata']['reservation'];
			if(true === $sub_order){
				$args = array(
					'cnfnum' => $web_orderid,
					'iata'   => JinService::IATA,
				);
				$new_res = $this->serv_api->getResvAudit($pms_set['hotel_web_id'], $args);
//				$new_res = xml2array($new_xml);
				if('success' == $new_res['@attributes']['result'] && !empty($new_res['wsResvInfoList']['wsResvInfo']['roomInfoList']['wsRoomInfo'])){
					$room_list = $new_res['wsResvInfoList']['wsResvInfo']['roomInfoList']['wsRoomInfo'];
					is_array(current($room_list)) or $room_list = array($room_list);
					foreach($room_list as $v){
						$web_order['room_list'][$v['singleroomnum']] = $v;
					}
				}
			}
//			echo '<pre>';print_r($web_order);
			return $web_order;
		}
		return array();
	}

	public function cancel_order_web($inter_id, $order, $pms_set = array()){
		if(empty ($order ['web_orderid'])){
			return array(
				's'      => 0,
				'errmsg' => '取消失败'
			);
		}

		//判断订单时间
		$intime = strtotime($order['startdate']);
		if(mktime(12, 0, 0, date('m', $intime), date('d', $intime), date('Y', $intime)) < time() && empty($pms_set['no_check_time'])){
			//时间超过入住时间当天的12点时不能取消
			return array(
				's'      => 0,
				'errmsg' => '只能在入住当天12点前取消订单！',
			);
		}


		//提交到PMS
		$params = array(
			'confnum' => $order['web_orderid'],
			/*'paymentinfo' => array(
				'paymentstatus' => 6,
				'refundamount'  => $order['price'],
				'tradeno'       => $order['trans_no'],
			),*/
		);

		/*$pay_info = $this->readDB()->where(array())->from('pay_log')->get()->row_array();
		if($pay_info){
			$params['paymentinfo'] = array(
				'paymentstatus' => 6,
				'refundamount'  => $pay_info['total_fee'] * 0.01,
				'tradeno'       => $pay_info['transaction_id'],
			);
		}*/

		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		$res = $this->serv_api->setCancelResv($pms_set['hotel_web_id'], $params);
//		$res = xml2array($xml);
		if('success' == $res['@attributes']['result'] && !empty($res['resvdata']['resvdetail'])){

			//取消成功后，向CRM提交取消优惠券
			if($order['coupon_favour'] > 0){
				$this->load->model('api/Vmember_model', 'vm');

				$coupon_des = json_decode($order['coupon_des'], true);
				if(array_key_exists('cash_token', $coupon_des)){

					$couponarr = explode('-', $coupon_des['cash_token'][0]['code']);

					$coupon_params = array(
						'orderId'        => $order['web_orderid'],
						'is_pms'         => true,
						'pms_user_id'    => $this->vm->getPmsUserId($order['openid'], $order['inter_id']),
						'openid'         => $order['openid'],
						'inter_id'       => $order['inter_id'],
						'member_card_id' => $couponarr[0],
						'module'         => 'hotel',

					);
					$this->vm->refundCoupon($coupon_params);
				}
			}

			$room_codes = json_decode($order ['room_codes'], true);
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			//酒店名称，到店日期，离店日期，房型
			$row = $this->getHotelRoomInfoByWebId($order['hotel_id'], $order ['first_detail']['room_id'], $order['inter_id']);
			$stay_days = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
			$sms_result = false;
			if($row){
				$sms_data = array(
					$row['hotel_name'],
					date('Y-m-d', strtotime($order['startdate'])),
					date('Y-m-d', strtotime($order['enddate'])),
					$row['room_name'],
				);
				$sms_result = $this->sendSMS($order['tel'], 'cancel', $sms_data, $order['inter_id']);
			}

			return array(
				's'      => 1,
				'errmsg' => '取消成功'
			);
		}
		return array(
			's'      => 0,
			'errmsg' => '取消失败--' . (isset($res['error']['errormsg']) ? $res['error']['errormsg'] : '')
		);
	}

	private function member_lv($lc, $pms_set = array(), $hotel_id){
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($pms_set['inter_id'], $hotel_id, $pms_set ['pms_type'], array(
			'member_level',
		), 1, 'w2l');


		/*$enum = array(
			14 => 'VIP',
			13 => 'PLA',
			12 => 'GCM',
			11 => 'SIL',
		);*/
		return isset($web_reflect['member_level'][$lc]) ? $web_reflect['member_level'][$lc] : '';
	}

	function order_reserve($order, $pms_set, $params = array()){
		$this->load->helper('common');
		if(ceil((strtotime($order['enddate']) - strtotime(date('Y-m-d'))) / 86400) > 90){
			return array(
				'result' => false,
				'errmsg' => '最长可提前90天进行预订',
			);
		}
		if(ceil((strtotime($order['enddate']) - strtotime($order['startdate'])) / 86400) > 14){
			return array(
				'result' => false,
				'errmsg' => '最长只可预订14天的房',
			);
		}
		if($order['roomnums'] > 3){
			return array(
				'result' => false,
				'errmsg' => '最多只可预订3间相同房型',
			);
		}

		//判断当前会员下的订单
		$map = array(
			'openid'       => $order['openid'],
			'inter_id'     => $pms_set['inter_id'],
			//			'status>'      => 0,
			'order_time>=' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
			'order_time<=' => mktime(23, 59, 59, date('m'), date('d'), date('Y')),
			//			'order_id!='   => $orderid,
		);

		$daily_list = $this->readDB()->from('hotel_orders')->select("count(*) as total,name")->where($map)->where_not_in('status', array(
			4,
			5,
			10,
			11

		))->group_by('name')->get()->result_array();
//		$ls = $this->_shard_db()->last_query();

//		$this->load->model('common/Webservice_model', 'wm');
//		$this->wm->log_service_record('查询同一ID已提交订单数量:' . $ls, json_encode($daily_list), $order['inter_id'], 'jinjiang', 'daily_count', 'query_order');

		if($daily_list){
			$rollback = false;
			if(count($daily_list) > 2){
				$rollback = true;
			} else{
				foreach($daily_list as $td){
					if($td['total'] > 1){
						$rollback = true;
						break;
					}
				}
			}
			if($rollback){
				return array(
					'result' => false,
					'errmsg' => '一个会员id同一天最多可预订两个不同入住人订单',
				);
			}

		}

		$in_day = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
		$hotel_web_id = $pms_set['hotel_web_id'];

		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
		//$room_codes['room']['webser_id'];
		//请求API判断该房型是否可预订
		$api_params = array(
			'staydetail' => array(
				'filter'    => 0,
				'date'      => date('Y-m-d', strtotime($order['startdate'])),
				'nights'    => $in_day,
				'channel'   => 'Website',
				'roomtype'  => $room_codes['room']['webser_id'],
				'rateclass' => isset($room_codes['code']['extra_info']['pms_code']) ? $room_codes['code']['extra_info']['pms_code'] : '',
			),
		);
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		//请求实时API，获取该房型是否可预订
		$arr = $this->serv_api->getOnlineRateMap($hotel_web_id, $api_params);
//		$arr = xml2array($xml);
		$book_status = false;
		$rate_status = false;
		if($arr['@attributes']['result'] == 'success' && !empty($arr['ratemap']['ratedata'])){
			$info = $arr['ratemap']['ratedata'];
			if($info['resv']['ResvStatus'] == 'A'){
				$book_status = true;
			} else{
				$rate_status = true;
				is_array(current($info['ratedetail'])) or $info['ratedetail'] = array($info['ratedetail']);
				foreach($info['ratedetail'] as $v){
					if($v['pr'] == 1 || $v['AvStat'] != 'A'){
						$rate_status = false;
						break;
					}
				}
			}
		}
		//实时房态不可预订直接返回
		if(!$book_status && !$rate_status){
			return array(
				'result' => false,
				'errmsg' => '该房型不可预订！',
			);
		}

		$remark = '';
		if(!empty ($params ['trans_no'])){
			$remark = '系统备注：此订单为微信端网上支付订单，客人已支付房费' . $order ['price'] . '元。请客人出示手机核实微信支付记录。';
		}

		$daily_price = explode(',', $order ['first_detail'] ['allprice']);
		if($order ['coupon_favour'] > 0){
			$daily_price [0] -= $order ['coupon_favour'];
			$remark .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
		}

		//每日房价
		$rate_list = $arr['ratemap']['ratedata']['ratedetail'];
		is_array(current($rate_list)) or $rate_list = array($rate_list);
		$daily_rate = array();
		foreach($rate_list as $v){
			$daily_rate['bookedrate'][] = array(
				'rate'         => $v['Single'],
				'date'         => $v['date'],
				'currencycode' => $v['CurrenyCode'],
			);
		}

		//订单数据
		$data = array(
			'outconfnum'  => $order['orderid'],
			//入住信息
			'staydetail'  => array(
				'date'      => date('Y-m-d', strtotime($order['startdate'])),
				'nights'    => $in_day,
				'roomtype'  => $room_codes['room']['webser_id'],
				'rateclass' => $room_codes['code']['extra_info']['pms_code'],
				'rooms'     => $order['roomnums'],
				'adults'    => 1,
				'channel'   => 'Website',

			),
			//客人资料
			'guestinfo'   => array(
				'firstname' => $order['name'],
				'holdTime'  => $order['holdtime'],
				'phone'     => $order['tel'],
				'mobile'    => $order['tel'],
			),
			//联系人资料
			'contactinfo' => array(
				'name'   => $order['name'],
				'phone'  => $order['tel'],
				'moblie' => $order['tel'],
			),
			//支付信息
			'paymentinfo' => array(
				'payment' => $order['paytype'] == 'weixin' ? 'P100' : 'CASH',
			),
			'remarks'     => array(
				'remark' => $remark,
			),

			'miscinfo'    => array(
				'IATA' => JinService::IATA
			),
			'tracelogid'  => $order['orderid'],
			//房型信息
			'bookedrates' => $daily_rate,
			'isassure'    => 0,
		);

		//会员资料
		//获取 token
		/*$token_res = $this->curl_post('accesstoken/get', array('id' => 'hotel', 'secret' => 'iwide30hotel'));
		if(!empty($token_res['data'])){
			$curl_data = array(
				'openid'   => $order['openid'],
				'token'    => $token_res['data'],
				'inter_id' => $order['inter_id'],
				'is_pms'   => 1
			);
			$member_res = $this->curl_post('member/getinfo', $curl_data);
			if(!empty($member_res['data'])){
				$data['memberinfo'] = array(
					'guestid'     => $member_res['data']['pms_user_id'],
					'memberno'    => $member_res['data']['membership_number'],
					'memberclass' => $this->member_lv((int)$member_res['data']['lvl_pms_code']),
				);
			}
		}*/
		$this->load->model('api/Vmember_model', 'vm');
		$member_res = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		if($member_res){

			$data['memberinfo'] = array(
				'guestid'     => $member_res['pms_user_id'],
				'memberno'    => $member_res['membership_number'],
				'memberclass' => $this->member_lv((int)$member_res['lvl_pms_code'], $pms_set, $order['hotel_id']),
			);
		}

		//优惠券
		if($order['coupon_favour'] > 0){
			$coupon_des = json_decode($order['coupon_des'], true);
			$coupon_count = count($coupon_des);
			$coupon_list = array();
			if(array_key_exists('cash_token', $coupon_des)){
				$coupon_count = count($coupon_des['cash_token']);
				for($i = 0; $i < $coupon_count; $i++){
					$t = $coupon_des['cash_token'][$i];
					$code_arr = explode('-', $t['code']);
					$couponcode = $code_arr[0];
					$vcoupon = $this->vm->getCouponInfo($order['openid'], $order['inter_id'], $couponcode);
					if(!$vcoupon){
						continue;
					}
					$coupon_list[] = array(
						'coupontype'   => $vcoupon['pms_card_info']['ruleType'],
						'couponnum'    => $couponcode,
						'couponamount' => $t['amount']
					);
				}
			}
			if($coupon_list){
				$data['couponinfo'] = array(
					'couponcount'       => $coupon_count,
					'coupon'            => $coupon_list,
					'coupontotalamount' => $order['coupon_favour'],
				);
			}
		}

		//支付信息
		if($order['paytype'] == 'weixin'){
			$data['paymentinfo']['paymentamount'] = $order['price'];
			$data['paymentinfo']['paymentsource'] = 4;
			$data['isassure'] = 4;
			$data['holdresv'] = 1;
		}

		/*if(!empty($params['trans_no'])){
			$data['paymentinfo']['paymentstatus'] = 1;
			$data['paymentinfo']['tradeno'] = $params['trans_no'];
		}*/

		$result = $this->serv_api->setNewResv($pms_set['hotel_web_id'], $data);
//		$result = xml2array($xml);

		if($result['@attributes']['result'] != 'success'){
			return array(
				'result' => false,
				'errmsg' => '下单失败！' . (isset($result['error']['errormsg']) ? $result['error']['errormsg'] : ''),
			);
		}
		if(empty($result['resvdata']['resvdetail'])){
			return array(
				'result' => false,
				'errmsg' => '下单失败~~',
			);
		}
		$web_order = $result['resvdata']['resvdetail'];

		//判断是否使用优惠券，若使用优惠券，则调用接口通知CRM使用优惠券
		if($order['coupon_favour'] > 0){
			$coupon_des = json_decode($order['coupon_des'], true);
			if(array_key_exists('cash_token', $coupon_des)){
				$room_codes = json_decode($order ['room_codes'], true);
				$room_codes = $room_codes [$order ['first_detail'] ['room_id']];

				$coupon_count = count($coupon_des['cash_token']);
				$nd = false;
				$t =& $coupon_des['cash_token'][0];
//				for($i = 0; $i < $coupon_count; $i++){
//					$t =& $coupon_des['cash_token'][$i];
				//判断该优惠券是否已通知过CRM处理
				if(empty($t['pms_handled'])){
					//需要提供的参数
//						$userId = $order['member_no'];//$this->getPmsUserId($order['openid'], $order['inter_id']);

					$arr = explode('-', $t['code']);
					$couponcode = $arr[0];
					$userId = $this->vm->getPmsUserId($order['openid'], $order['inter_id']);
					$vcoupon = $this->vm->getCouponInfo($order['openid'], $order['inter_id'], $couponcode);
					if($vcoupon){
						$coupon_params = array(
							'startTime'    => $order['startdate'],//入住日期
							'endTime'      => $order['enddate'],//离店日期
							'unitId'       => $pms_set['hotel_web_id'],//酒店ID
							'hotellId'     => $pms_set['hotel_web_id'],//酒店ID
							'rmTypeId'     => $room_codes['room']['webser_id'],//房型
							'roomNum'      => $order['roomnums'],
							'orderId'      => $web_order['confnum'],
							'couponAmount' => $coupon_count,
							'userId'       => $userId,
							'ruleId'       => $couponcode,
							'ruleType'     => $vcoupon['pms_card_info']['ruleType'],
							'is_pms'       => true,
							'openid'       => $order['openid'],
							'inter_id'     => $order['inter_id'],
							'module'       => 'hotel',
						);

						$coupon_handle = $this->vm->useCoupon($coupon_params);
						if($coupon_handle){
							$nd = true;
							$t['pms_handled'] = 1;
						}
					}

				}
//				}
				if($nd){
					//标记已处理优惠券
					$this->db->where(array(
						                 'orderid'  => $order ['orderid'],
						                 'inter_id' => $order['inter_id'],
					                 ));
					$this->db->update('hotel_order_additions', array(
						'coupon_des' => json_encode($coupon_des)
					));
				}
			}
		}

//		print_r($web_order);
		$row = $this->getHotelRoomInfoByWebId($order['hotel_id'], $order['first_detail'] ['room_id'], $order['inter_id']);
		$stay_days = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
		$sms_result = false;
		if($row){
			$sms_data = array(
				$row['hotel_name'],
				$web_order['arrival'],
				$order['roomnums'],
				$row['room_name'],
				$stay_days,
				$order['price'],
				$row['address'],
				$row['tel'],
				$order['holdtime']
			);
			//酒店名称，到店日期，房间数，房型名称，停留天数，金额，地址，酒店电话，保留时间
			$sms_result = $this->sendSMS($order['tel'], 'order', $sms_data, $order['inter_id']);
		}

		return array(
			'result'     => true,
			'oid'        => $web_order['confnum'],
			'child_oid'  => $web_order['singleroomnum'],
			'resvclass'  => $web_order['resvclass'],
			'sms_result' => $sms_result,
		);

	}

	function getHotelRoomInfoByWebId($hotel_id, $room_id, $inter_id){
		$where = array(
			'hr.room_id'  => $room_id,
			'hr.hotel_id' => (int)$hotel_id,
			'hr.inter_id' => $inter_id,
		);
		$row = $this->readDB()->select('hr.name as room_name,h.name as hotel_name, h.address, h.tel')->where($where)->from('hotel_rooms hr')->join('hotels h', 'h.hotel_id=hr.hotel_id', 'left')->get()->row_array();
		return $row;
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
		//缓存数据
		if(!empty($params['condit']['recache'])){
			$recache=true;
			unset($params['condit']['recache']);
		}else{
			$recache=false;
		}

		if(!$recache && count($params['web_rids']) == 1 && count($web_price_code) == 1){
			$recache = true; //一间房，一个价格代码时，强制刷新缓存
		}

		ksort($pms_set);

		$condit_arr=[
			'startdate'=>$params['condit']['startdate'],
			'enddate'=>$params['condit']['enddate'],
		];
		$idents_arr=[
			'hotel_id'=>$params['idents']['hotel_id'],
			'hotel_web_id'=>$params['idents']['hotel_web_id'],
			'inter_id'=>$params['idents']['inter_id'],
		];

		$web_reflect_arr=$params['web_reflect'];
		unset($web_reflect_arr['member_level'],$web_reflect_arr['member_price_code'],$web_reflect_arr['web_price_code']);

		$arr_0=$params;
		$arr_0['condit']=$condit_arr;
		$arr_0['idents']=$idents_arr;
		$arr_0['web_reflect']=$web_reflect_arr;

		ksort($arr_0);
		foreach($arr_0 as $k => &$v){
			if(is_array($v)){
				ksort($v);
			}
		}
		reset($arr_0);

		$startdate=date('Ymd',strtotime($startdate));
		$enddate=date('Ymd',strtotime($enddate));

		sort($web_price_code);
		$kjson=json_encode($pms_set) . '|' . json_encode($web_price_code) . '|' . $startdate . '|' . $enddate . '|' . json_encode($arr_0);
		$rk = 'roomtype:' . $pms_set['inter_id'] . ':' . md5($kjson);

		$this->load->helper('common');
		$this->load->library('Cache/Redis_proxy',array(
			'not_init'=>FALSE,
			'module'=>'common',
			'refresh'=>FALSE,
			'environment'=>ENVIRONMENT
		),'redis_proxy');
		$redis=$this->redis_proxy;
		$json = $redis->get($rk);
		$result=json_decode($json,true);
		pms_logger($kjson,$json,__METHOD__.'->redis',$pms_set['inter_id']);

		$member_level = $params['member_level'];

		$countday = $params['countday'];

		if($recache || $json === false || !is_array($result)){
			//count($params['web_rids']) == 1 && count($web_price_code) == 1
			if(count($params['web_rids']) == 1){
				$wr_list=array_keys($params['web_rids']);
				$web_room_k=$wr_list[0];
			}else{
				$web_room_k='';
			}
			if(count($web_price_code)==1){
				$web_ratec=$web_price_code[0];
			}else{
				$web_ratec='';
			}

			//查询API上的缓存数据，该酒店所有房态
			$room_list = $this->getAllRoomsState($pms_set, $startdate, $countday,$web_room_k,$web_ratec);

			//判断数据返回的room_list是否多个数组
			$tmp_keys=array_keys($room_list);
			if(!is_numeric($tmp_keys[0])){
				$room_list=[$room_list];
			}

			//		echo '<pre>';print_r($room_list);exit;
			/*$price_list = array();
			foreach($room_list as $v){
				$price_list[$v['plandetail']['Room']][$v['plandetail']['Rate']] = $v['ratedetail'];
			}*/

			$pms_state = array();
			$valid_state = array();
			$exprice = array();
			if(!empty($room_list)){
				//			print_r($room_list);
				foreach($room_list as $v){
					$plandetail = $v['plandetail'];
					$web_room = $plandetail['Room'];
					$web_rate = $plandetail['Rate'];
					/*if(in_array($web_rate, $params['no_rate'])){
						continue;
					}*/
					if(!in_array($web_rate, $web_price_code)){
						continue;
					}
					$web_mem = $plandetail['rateclass']['memberinfo'];
					//				print_r($web_mem);
					if(array_key_exists($web_room, $params['web_rids'])){
						//					$roomtype = $plandetail['roomtype'];
						$pms_state[$web_room][$web_rate]['price_name'] = $v['plandetail']['name'];
						$pms_state[$web_room][$web_rate]['price_type'] = 'pms';

						$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
						$pms_state[$web_room][$web_rate]['extra_info'] = array(
							'type'        => 'code',
							'pms_code'    => $web_rate,
							'coupon_para' => array('hotel_id')
							//								'channel_code' => $rd ['channelCode']
						);
						$pms_state[$web_room][$web_rate]['des'] = '';
						$pms_state[$web_room][$web_rate]['sort'] = 0;
						if(trim($web_mem['memberclass']) == $member_level || trim($web_mem['ismemberprivilege']) == ''){
							$pms_state[$web_room][$web_rate]['disp_type'] = 'buy_show';
						} else{
							$pms_state[$web_room][$web_rate]['disp_type'] = 'only_show';
						}

						$web_set = array();
						if(isset ($params ['web_reflect'] ['web_price_code_set'] [$web_rate])){
							$web_set = json_decode($params ['web_reflect'] ['web_price_code_set'] [$web_rate], true);
						}

						$pms_state[$web_room][$web_rate]['condition'] = $web_set;

						/*$condition = array();
						if(in_array($web_rate, $params['no_coupon'])){
							$pms_state[$web_room][$web_rate]['condition']['no_coupon'] = 1;
						}
						$pms_state[$web_room][$web_rate]['condition']['no_coupon'] = $condition;*/

						if(isset($params['web_rids'][$web_room]) && isset($params['condit']['nums'][$params['web_rids'][$web_room]])){
							$nums = $params['condit']['nums'][$params['web_rids'][$web_room]];
						} else{
							$nums = 1;
						}

						$allprice = array();
						$amount = 0;

						if($v['resv']['ResvStatus'] == 'A'){
							$room_status = true;
						} else{
							$room_status = false;
						}

						$rate_status = true;

						$ratedetail = $v['ratedetail'];
						is_array(current($ratedetail)) or $ratedetail = array($ratedetail);
						$least_arr = [];
						$least_count = 0;

						foreach($ratedetail as $t){
							$pms_state[$web_room][$web_rate]['date_detail'][date('Ymd', strtotime($t['date']))] = array(
								'price' => $t['Single'],
								'nums'  => $t['Allotment']
							);

							$allprice[] = $t['Single'];
							$amount += $t['Single'];
							$least_arr[] = $t['Allotment'];
							//						$least_count = $t['Allotment'] > 0 ? $t['Allotment'] : 0;

							/*if($room_status === false && $rate_status === true){
								if(!$t['pr'] && $t['AvStat'] == 'A'){
									$rate_status = true;
								} else{
									$rate_status = false;
								}
							}*/

							$rate_status = $rate_status && !$t['pr'] && in_array($t['AvStat'],['A','L']);

						}

						//校验日期价格
						$all_exists=true;
						for($start = date('Ymd', strtotime($startdate)); $start < date('Ymd', strtotime($enddate));){
							if(empty($pms_state[$web_room][$web_rate]['date_detail'][$start])){
								$all_exists=false;
								break;
							}
							$start = date('Ymd', strtotime($start)+86400);
						}

						//是否所有日期都直接价格代码
						if(!$all_exists){
							unset($pms_state[$web_room][$web_rate]);
							continue;
						}

						if($least_arr){
							$least_arr[] = 3;
							$least_count = min($least_arr);
						}
						$least_count > 0 or $least_count = 0;

						$pms_state[$web_room][$web_rate]['allprice'] = implode(',', $allprice);
						$pms_state[$web_room][$web_rate]['total'] = $amount;
						$pms_state[$web_room][$web_rate]['related_des'] = '';
						$pms_state[$web_room][$web_rate]['total_price'] = $amount * $nums;

						$pms_state[$web_room][$web_rate]['avg_price'] = number_format($amount / $params ['countday'], 2, '.', '');
						$pms_state[$web_room][$web_rate]['price_resource'] = 'webservice';
						/*if($v ['num'] > 1){
							$v ['num'] = 1;
						}*/
						$book_status = 'full';
						if($room_status || $rate_status){
							$book_status = 'available';
						}

						$pms_state[$web_room][$web_rate]['book_status'] = $book_status;

						$pms_state[$web_room][$web_rate]['least_num'] = $least_count;
						if(trim($web_mem['memberclass']) == $member_level || trim($web_mem['ismemberprivilege']) == ''){
							$valid_state[$web_room][$web_rate] = $pms_state[$web_room][$web_rate];

							$exprice [$web_room][] = $pms_state[$web_room][$web_rate]['avg_price'];
						}
					}
				}
				//排序
				foreach($pms_state as $k => $v){
					/*$sort_arr = array();
					foreach($v as $key => $t){
						$sort_arr[$key] = $t['total'];
					}
					array_multisort($sort_arr, SORT_DESC, $pms_state[$k]);*/
					uasort($pms_state[$k], function ($a, $b){
						return $a['total'] - $b['total'];
					});
				}

			}

			$result=[
				'pms_state'   => $pms_state,
				'valid_state' => $valid_state,
				'exprice'     => $exprice,
			];
			$redis->set($rk,json_encode($result),3600);
		}
		return $result;

	}

	/*private function getPlans($pms_set){
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		$xml=$this->serv_api->getPlanObj($pms_set['hotel_web_id']);
		$plans_list=xml2array($xml);
		return $plans_list;
	}*/

	private function getAllRoomsState($pms_set, $startdate, $nights = 1,$web_room='',$web_rate=''){
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$pms_auth['inter_id']=$pms_set['inter_id'];
		$this->serv_api->setPmsAuth($pms_auth);
		$params = array(
			'staydetail' => array(
				'filter'  => 0,
				'date'    => date('Y-m-d', strtotime($startdate)),
				'nights'  => $nights,
				'channel' => 'Website',
			),
		);
		if($web_room){
			$params['staydetail']['roomtype']=$web_room;
		}
		if($web_rate){
			$params['staydetail']['rateclass']=$web_rate;
		}
		$list = $this->serv_api->getCrateMap($pms_set['hotel_web_id'], $params);
//		$xml = $this->serv_api->getOnlineRateMap($pms_set['hotel_web_id'], $params);
//		$list = xml2array($xml);

		if(isset($list['@attributes']) && $list['@attributes']['result'] === 'success' && isset($list['ratemap']['ratedata'])){
			return $list['ratemap']['ratedata'];
		}
		return array();
	}


	function get_hotel_extra_info($hotel_id, $pms_set = array()){
		$this->load->model('hotel/Image_model');
		$data = array();
		$icons = $this->Image_model->get_hotels_icon($pms_set['inter_id'], array($hotel_id), 'ICONS_IMG_SERACH_RESULT');
		if(!empty($icons[$hotel_id]['ICONS_IMG_SERACH_RESULT'])){
			$piao_id = '1652';
			if(isset($icons[$hotel_id]['ICONS_IMG_SERACH_RESULT'][$piao_id])){
				$data['top_icons'] = 'http://file.iwide.cn/public/uploads/201606/qf061608045712.png';
				unset($icons[$hotel_id]['ICONS_IMG_SERACH_RESULT'][$piao_id]);
			}
			$data['title_icons'] = $icons[$hotel_id]['ICONS_IMG_SERACH_RESULT'];
		}
		return $data;
	}

	protected function curl_post($uri, $data){
		$url = INTER_PATH_URL2 . $uri;
		$this->load->helper('common');
		$data = http_build_query($data);
		$return = doCurlPostRequest($url, $data);
		return json_decode($return, true);
	}

	/*public function getPmsUserId($openid, $inter_id){
		$userId = '';
		$token_res = $this->curl_post('accesstoken/get', array(
			'id'     => 'hotel',
			'secret' => 'iwide30hotel'
		));
		if(!empty($token_res['data'])){
			$curl_data = array(
				'openid'   => $openid,
				'token'    => $token_res['data'],
				'inter_id' => $inter_id,
				'is_pms'   => 1
			);

			$member_res = $this->curl_post('member/getinfo', $curl_data);
			if(!empty($member_res['data'])){
				$userId = $member_res['data']['pms_user_id'];
			}
		}
		return $userId;
	}*/

	/*public function getVApiToken(){
		$token_res = $this->curl_post('accesstoken/get', array(
			'id'     => 'hotel',
			'secret' => 'iwide30hotel'
		));
		if(!empty($token_res['data'])){
			return $token_res['data'];
		}
		return '';
	}*/

	public function get_useable_coupon($openid, $pms_set, $params){
		$this->load->model('api/Vmember_model');
		$inter_id = $pms_set['inter_id'];
		$user_id = $this->Vmember_model->getPmsUserId($openid, $inter_id);
//		$user_id = $this->getPmsUserId($openid, $inter_id);
		if(!$user_id){
			return array();
		}
		if(empty($params['price_code'])){
			return array();
		}

		$rmid = $params['category'];

		$hotel_data = $this->readDB()->from('hotel_additions')->where(array(
			                                                                 'hotel_id' => (int)$params['hotel'],
			                                                                 'inter_id' => $inter_id
		                                                                 ))->select('hotel_web_id')->get()->row_array();
		if(!$hotel_data){
			return array();
		}
		$web_hotel = $hotel_data['hotel_web_id'];

		$room_data = $this->readDB()->from('hotel_rooms')->where(array(
			                                                            'room_id'  => (int)$rmid,
			                                                            'inter_id' => $inter_id,
		                                                            ))->select('webser_id')->get()->row_array();
		if(!$room_data){
			return array();
		}
		$web_room = $room_data['webser_id'];
		$api_post = array(
			'userId'         => $user_id,
			'startTime'      => isset($params['startdate']) ? date('Y-m-d', strtotime($params['startdate'])) : date('Y-m-d'),
			'endTime'        => isset($params['enddate']) ? date('Y-m-d', strtotime($params['enddate'])) : date('Y-m-d', time() + 86400),
			'unitId'         => $web_hotel,
			'hotelID'        => $web_hotel,
			'rmTypeId'       => $web_room,
			'roomNum'        => isset($params['rooms']) ? $params['rooms'] : 1,
			'promotionTypes' => '',

		);

		$list = $this->curl_post('jinjiang/order_coupon', $api_post);

		$coupons = array();
//		print_r($list);
		if(isset($list['data']) && !empty($list['data']['couponOrderList'])){
			/*$token = $this->getVApiToken();
			$api_post = array(
				'openid'   => $openid,
				'token'    => $token,
				'inter_id' => $inter_id,
				'module'   => 'hotel',
				'num'      => '',
				'type'     => '',
				'is_pms'   => 1,
			);
			$all_coupon = $this->curl_post('membercard/getlist', $api_post);
			$merge_coupon = array();
			if(!empty($all_coupon['data']) && is_array($all_coupon['data'])){
				foreach($all_coupon['data'] as $v){
					$merge_coupon[$v['pms_card_info']['couponId']] = array(
						'brand_name'  => $v['brand_name'],
						'expire_time' => $v['expire_time'],
					);
				}
			}*/
			$this->load->model('api/Vmember_model');
			$coupon_list = $this->Vmember_model->getUserCoupon($openid, $inter_id, 'hotel', '', '', 1);
			$merge_coupon = array();
			foreach($coupon_list as $v){
				$merge_coupon[$v['pms_card_info']['couponId']] = array(
					'brand_name'  => $v['brand_name'],
					'expire_time' => $v['expire_time'],
				);
			}
			foreach($list['data']['couponOrderList'] as $v){
				if($v['amount'] > 0){
					$brand_name = isset($merge_coupon[$v['ruleId']]) ? $merge_coupon[$v['ruleId']]['brand_name'] : '';
					$expire_time = isset($merge_coupon[$v['ruleId']]) ? $merge_coupon[$v['ruleId']]['expire_time'] : 0;
					$result['code'] = $v['ruleId'];
					$result['title'] = $v['ruleName'];
					$result['brand_name'] = $brand_name;
					$result['ci_id'] = $v['ruleId'];
					$result['card_id'] = $v['ruleId'];
					$result['is_wxcard'] = 1;
					$result['restriction']['room_nights'] = 1;
					$result['restriction'] ['order'] = 1;
					$result['extra'] = '';
					$result['coupon_type'] = 'voucher';
					$result['pms_coupon_type'] = $v['ruleType'];
					$result['reduce_cost'] = $v['amount'];
					$result['date_info_end_timestamp'] = $expire_time;
					/*if($v['pms_card_info']['leftNum'] != 0){
						$result['status'] = 1;
					} else{
						$result['status'] = 2;
					}*/
					$result['status'] = 1;
					$arr = (Object)$result;
					$coupons[$v['ruleId']] = $result;
				}
			}
		}
		return $coupons;
	}

	public function sendSMS($mobile, $type, $params, $inter_id){
		$this->load->model('common/Webservice_model');
		$time=time();
		$tmpl = '';
		switch($type){
			case 'cancel':
				//酒店名称，到店日期，离店日期，房型
				$tmpl = '锦江之星：您预订%s %s到%s的%s已取消。';
				break;
			case 'order':
				//酒店名称，到店日期，房间数，房型名称，停留天数，金额，地址，酒店电话，保留时间
				$tmpl = '您预订的%s,%s入住%s间%s %s晚,共%s元,%s ,%s,保留到%s.下载APP更多优惠：http://t.cn/RhmSiOI 。';
				break;

		}
		array_unshift($params, $tmpl);
		$content = call_user_func_array('sprintf', $params);
		$content = urlencode($content);
//		echo $content;
		$url = 'http://apizf.jinjianginns.com:8088/AlipayForUser.aspx?service=api_Texting&content=' . $content . '&telphone=' . $mobile;


		$con = curl_init(( string )$url);
		curl_setopt($con, CURLOPT_HEADER, false);
//		curl_setopt($con, CURLOPT_POST, TRUE);
		curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($con, CURLOPT_TIMEOUT, 2);
//		curl_setopt($con, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);
		$res = curl_exec($con);
		curl_close($con);
//		var_dump($res);
		$result = $res ? 'TRUE' : 'FALSE';

		$this->Webservice_model->add_webservice_record($inter_id,'jinjiang',$url,func_get_args(),$res,'query_post',$time,microtime(),$this->session->userdata($inter_id.'openid'));
	}

	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){

		$web_order = $this->get_web_order($order['web_orderid'], $pms_set, true);
		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');
			//先判断是否为取消单
			if($web_order['status'] == 'c'){
				$status = $status_arr[$web_order['status']];
			} else{
				$status = $status_arr[$web_order['sign']];
			}

		}
		if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
			return true;
		} else{
			return false;
		}
	}

	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
}