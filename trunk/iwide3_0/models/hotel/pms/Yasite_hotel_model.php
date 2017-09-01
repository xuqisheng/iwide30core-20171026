<?php

class Yasite_hotel_model extends MY_Model{
	const TAB_HO = 'hotel_orders';
	const TAB_HOA = 'hotel_order_additions';

	private $local_test = false;

	public function __construct(){
		parent::__construct();
	}

	public function get_rooms_change($rooms, $idents, $condit, $pms_set = array()){
		$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], true);

		//所订房间数量数组，不存在数据数组，默认所有都为1间，元素：room_id=>$room_num
		if(!isset($condit['nums'])){
			$nums = array();
			foreach($rooms as $temp_room){

				$nums[$temp_room['room_id']] = 1;

			}
		} else{
			$nums = $condit['nums'];
		}

		/*$in_date = date("Y-m-d", strtotime($condit['startdate']));

		$out_date = date("Y-m-d", strtotime($condit['enddate']));

		//PMS上酒店ID
		$hotel_web_id = $pms_set['hotel_web_id'];
		//获取酒店房间信息
		$_data = array(
			'hotelID' => $hotel_web_id,
		);
		$res = $this->sub_to_web($pms_set, 'GetAllHotelHouseStatusInfo', $_data);

		if(!empty($res['error'])){
			return array();
		}

		//酒店房间组合
		$_rooms = array();

		//返回数据没有以酒店房间ID分组，需要组合
		foreach($res as $v){
			$_rooms[$v['RoomTypeID']][] = $v;
		}

		//临时数组
		$temp_rooms = array();
		foreach($_rooms as $k => $v){
			$t = array_shift($v);
			$t['num']=$t['RoomCount'] - $t['CheckInCount'] - $t['BookInCount'] - $t['StopSaleCount'];
			$temp_rooms[$k] = $t;//弹出第一个，作为房间信息
		}*/

		$member_type = $condit ['member_level'];
		$this->load->model('common/Webservice_model');
		$level_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array(
			//			'member_level',
			'basic_price_code',
			'member_price_code',
			'web_price_code_set',
			//'show_price_code',
		), 1, 'l2w');
		//2,3,4,5,45
		//PMS上的等级
		$web_level = null;
		$price_level = null;
		$show_level = array();
		/*if(!empty ($level_reflect ['member_level']) && isset ($level_reflect ['member_level'] [$member_type])){
			$web_level = $level_reflect ['member_level'] [$member_type];
			$price_level = $web_level;
		}*/

		if($pms_set ['pms_room_state_way'] == 3 && isset ($level_reflect ['basic_price_code'])){
			$basic_price_code = $level_reflect ['basic_price_code'];
			$web_level = current($basic_price_code);
		}

		if($pms_set['pms_room_state_way'] == 3 && isset($level_reflect['show_price_code'])){
			$show_price_code = $level_reflect ['show_price_code'];
			$show_level = current($show_price_code);
			$show_level = explode(',', $show_level);
		}

		$countday = get_room_night($condit['startdate'],$condit['enddate'],'ceil',$condit);//至少有一个间夜
		$web_rids = array();
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}

		$params = array(
			'member_type'   => $member_type,
			'web_level'     => $web_level,
			'price_level'   => $price_level,
			'idents'        => $idents,
			'condit'        => $condit,
			//			'pre_pays'      => $pre_pays,
			//			'no_pays'       => $no_pays,
			'countday'      => $countday,
			'web_rids'      => $web_rids,
			'level_reflect' => $level_reflect,
			'show_level'    => $show_level,
		);
		if(isset ($web_level)){
			$pms_data = $this->get_web_roomtype($pms_set, $idents ['hotel_web_id'], $web_level, $condit ['startdate'], $condit ['enddate'], $params);
		}
		if(!empty ($pms_data)){
			switch($pms_set ['pms_room_state_way']){
				case 1 :
				case 2 :
					$result = $this->get_rooms_change_allpms($pms_data, array(
						'rooms' => $rooms
					), $params);
					return $result;
					break;
				case 3 :
					$result = $this->get_rooms_change_lmem($pms_data, array(
						'rooms' => $rooms
					), $params);
					return $result;
					break;
				default :
					return array();
					break;
			}
		}
		return array();
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

	public function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()){
		$this->load->model('hotel/Order_model');
		$order = $this->Order_model->get_main_order($inter_id, array(
			'orderid' => $orderid,
			'idetail' => array(
				'i'
			)
		));
		if(empty($order)){
			return array(
				's'      => 0,
				'errmsg' => '提交订单失败'
			);
		}
		$order = $order [0];
		$res = $this->order_reserve($order, $pms_set);

		//返回信息处理
		if($res['result']){
			$web_orderid = $res['oid'];
			$this->db->where(array(
				                 'orderid'  => $order ['orderid'],
				                 'inter_id' => $order ['inter_id']
			                 ));
			$this->db->update('hotel_order_additions', array(        //更新pms单号到本地
			                                                         'web_orderid' => $web_orderid
			));
			if($order ['status'] != 9){
				$this->change_order_status($order ['inter_id'], $order ['orderid'], 1);
				$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
			}
			/*foreach ( $order ['order_details'] as $k => $od ) {				//如果pms有分单机制，将分单号保存到本地，否则省略
				$this->db->where ( array (
									   'id' => $od ['sub_id'],
									   'inter_id' => $inter_id,
									   'orderid' => $order ['orderid']
								   ) );
				$everyday_amt = explode ( ',', $result [$k]->everyday_amt );
				$this->db->update ( 'hotel_order_items', array (
					'webs_orderid' => $result [$k]->acctnum,
					'iprice' => array_sum ( $everyday_amt ) - array_pop ( $everyday_amt )
				) );
			}*/
			if(!empty ($params ['trans_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
				$this->add_web_bill($web_orderid, $order, $pms_set, $params ['trans_no']);
			}
			return array( // 返回成功
			              's' => 1
			);
		} else{
			$this->change_order_status($inter_id, $orderid, 10);
			return array(
				's'      => 0,
				'errmsg' => '提交订单失败,' . $res ['errmsg']
			);
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
			'hotelID'  => $pms_set ['hotel_web_id'],
			'orderID'  => $web_orderid,
			'tradeid'  => $trans_no,
			'agent'    => '微信',
			'payMoney' => $order['price'],
		);

		$res = $this->sub_to_web($pms_set, 'Payment', $params, true);

		if(isset($res['result']) && $res['result'] == 'success'){
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

	public function cancel_order_web($inter_id, $order, $pms_set = array()){
		if(empty ($order ['web_orderid'])){
			return array(
				's'      => 0,
				'errmsg' => '取消失败'
			);
		}
		/*
			构造取消订单数据
		*/
		$params = array(
			'hotelID' => $pms_set['hotel_web_id'],
			'folioID' => $order['web_orderid'],
		);
		$result = $this->sub_to_web($pms_set, 'CancelOrder', $params, true);
		$errmsg = '';
		if(isset($result['result']) && $result['result'] == 'success'){
			//取消成功，直接这样return，接下来的程序会继续处理
			return array(
				's'      => 1,
				'errmsg' => '取消成功'
			);
		} elseif(empty($result['error'])){
			$errmsg .= '，' . $result['msg'];
		}
		return array(
			's'      => 0,
			'errmsg' => '取消失败' . $errmsg,
		);
	}

	public function update_web_order($inter_id, $order, $pms_set){
		$web_order = $this->get_web_order($order['web_orderid'], $pms_set);
		//		echo '<pre>';
		//		print_r($web_order);exit;
		$web_order['Arrival'] = $this->handleDate($web_order['Arrival']);
		$web_order['Arrorig'] = $this->handleDate($web_order['Arrorig']);
		$web_order['Depart'] = $this->handleDate($web_order['Depart']);
		$web_order['Deporig'] = $this->handleDate($web_order['Deporig']);
		$web_order['CreateTime'] = $this->handleDate($web_order['CreateTime']);
		$web_order['CreateAccDate'] = $this->handleDate($web_order['CreateAccDate']);
		$web_order['ArrAccDate'] = $this->handleDate($web_order['ArrAccDate']);
		$web_order['DepAccDate'] = $this->handleDate($web_order['DepAccDate']);
		$web_order['ChkOutTime'] = $this->handleDate($web_order['ChkOutTime']);
		$web_order['ChkOutAccDate'] = $this->handleDate($web_order['ChkOutAccDate']);
		$status = -1;
		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');

			$status = $status_arr [$web_order['FolioState']];
			if($order ['status'] == 4 && $status == 5){
				$status = 4;
			}

			if($status != $order ['status'] && $status !== false){
				$this->load->model('hotel/Order_model');
				$this->change_order_status($order['inter_id'], $order['orderid'], $status);
				$this->Order_model->handle_order($inter_id, $order ['orderid'], $status, $order['openid']);

			}

		}
		return $status;
	}

	function pms_enum($type){
		switch($type){
			case 'status' :
				return array(
					/*
					 * 0 ：未在线支付
1：等待提交给支付机构
2：等待支付结果
3：支付成功
4：支付失败
5：支付取消
					 */

					////FolioState:1=预订，2=取消，3=未到，4=入住，5=退房
					//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付

					'1' => 1,
					'2' => 5,
					'3' => 8,
					'4' => 2,
					'5' => 3,
				);
				break;
			default :
				return array();
				break;
		}
	}

	public function get_web_order($web_orderid, $pms_set){
		$params = array(
			'oid' => $web_orderid,
		);

		$res = $this->sub_to_web($pms_set, 'QueryOrder', $params, true);
		if(!is_array($res)){
			return array();
		}
		if(!empty($res['error'])){
			return array();
		}
		return $res;
	}


	function get_web_roomtype($pms_set, $hotel_web_id, $member_type, $startdate, $enddate, $params){
		$in_date = date("Y-m-d", strtotime($params['condit']['startdate']));

		$out_date = date("Y-m-d", strtotime($params['condit']['enddate']));

		//PMS上酒店ID
		$hotel_web_id = $pms_set['hotel_web_id'];
		//获取酒店房间信息
		$_data = array(
			'HotelID'   => $hotel_web_id,
			'StartDate' => $in_date,
			'EndDate'   => $out_date,

		);
		$pms_data = $this->sub_to_web($pms_set, 'GetAllHotelHouseStatusInfo', $_data);

		if(!empty($pms_data['error'])){
			return array();
		}

		$price_arr = array();
		foreach($pms_data as $v){
			$v['AccDate'] = $this->handleDate($v['AccDate']);
			$price_arr[$v['RoomTypeID']][$v['RoomRateTypeID']][] = array(
				'RoomRate'      => $v['RoomRate'],
				'RoomCount'     => $v['RoomCount'],
				'CheckInCount'  => $v['CheckInCount'],
				'BookInCount'   => $v['BookInCount'],
				'StopSaleCount' => $v['StopSaleCount'],
				'ControlCount'  => $v['ControlCount'],
				'AccDate'       => $v['AccDate'],
				'LeftCount'     => $v['RoomCount'] - $v['CheckInCount'] - $v['BookInCount'] - $v['StopSaleCount'] - $v['ControlCount'],
			);
		}

		//酒店房间组合

		//返回数据没有以酒店房间ID分组，需要组合
		foreach($pms_data as &$v){
			$v['AccDate'] = $this->handleDate($v['AccDate']);
			unset($v['RoomRate'], $v['RoomCount'], $v['CheckInCount'], $v['BookInCount'], $v['StopSaleCount'], $v['ControlCount'], $v['AccDate']);
		}

		/*foreach($pms_data as &$v){
			foreach($v as &$t){
				$t['DailyPrice'] = $price_arr[$t['RoomTypeID']][$t['RoomRateTypeID']];
			}
		}*/

		//临时数组
		/*$temp_rooms = array();
		foreach($_rooms as $k => $v){
			$t = array_shift($v); //弹出第一个，作为房间信息
			$t['num'] = $t['RoomCount'] - $t['CheckInCount'] - $t['BookInCount'] - $t['StopSaleCount'];
			$temp_rooms[$k] = $t;
		}*/


		$pms_state = array();
		$valid_state = array();
		$exprice = array();

		if(!empty($pms_data)){
			foreach($pms_data as $v){
				if(array_key_exists($v['RoomTypeID'], $params['web_rids'])){
					//					if(in_array($v['RoomRateTypeID'], $params['show_level'])){
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['price_name'] = $v['RoomRateTypeName'];
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['price_type'] = 'pms';
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['price_code'] = $v['RoomRateTypeID'];
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['extra_info'] = array(
						'type'     => 'code',
						'pms_code' => $v ['RoomRateTypeID'],
						//								'channel_code' => $rd ['channelCode']
					);
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['des'] = '';
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['sort'] = 0;
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['disp_type'] = 'buy';

					$web_set = array();
					if(isset ($params ['level_reflect'] ['web_price_code_set'] [$v['RoomRateTypeName']])){
						$web_set = json_decode($params['level_reflect']['web_price_code_set'][$v['RoomRateTypeName']], true);
					}

					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['condition'] = $web_set;

					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['condition'] = array();

					if(isset ($params ['web_rids'] [$v['RoomTypeID']])){
						$nums = empty ($params ['condit'] ['nums'] [$params ['web_rids'] [$v['RoomTypeID']]]) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$v['RoomTypeID']]];
					} else{
						$nums = 1;
					}


					$allprice = array();
					$amount = 0;

					$least_arr = [];
					$least_count = 0;
					$can_book = true;

					//						array_pop($price_arr[$v['RoomTypeID']][$v['RoomRateTypeID']]);

					$ct = count($price_arr[$v['RoomTypeID']][$v['RoomRateTypeID']]);
					for($i = 0; $i < $ct - 1; $i++){
						$daily = $price_arr[$v['RoomTypeID']][$v['RoomRateTypeID']][$i];
						$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['date_detail'][date('Ymd', $daily['AccDate'])] = array(
							'price' => $daily['RoomRate'],
							'nums'  => $daily['LeftCount']
						);
						$allprice[] = $daily['RoomRate'];
						$amount += $daily['RoomRate'];
//						$least_count = $daily['LeftCount'] > 0 ? 1 : 0;
						$least_arr[] = $daily['LeftCount'];
						$can_book = $can_book && $daily['LeftCount'] > $nums;
					}
					if($least_arr){
						$least_arr[] = 1;
						$least_count = min($least_arr);
					}

					$least_count > 0 or $least_count = 0;

					/*foreach($price_arr[$v['RoomTypeID']][$v['RoomRateTypeID']] as $daily){
						$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['date_detail'][date('Ymd', $daily['AccDate'])] = array(
							'price' => $daily['RoomRate'],
							'nums'  => $daily['LeftCount']
						);
						$allprice[] = $daily['RoomRate'];
						$amount += $daily['RoomRate'];
						$least_count = $daily['LeftCount'] > 0 ? 1 : 0;
						$can_book = $can_book && $daily['LeftCount'] > $nums;
					}*/

					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['allprice'] = implode(',', $allprice);
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['total'] = $amount;
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['related_des'] = '';
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['extra_info'] = array(
						'type'     => 'code',
						'pms_code' => $v ['RoomRateTypeID'],
						//								'channel_code' => $rd ['channelCode']
					);
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['total_price'] = $amount * $nums;

					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['avg_price'] = number_format($amount / $params ['countday'], 2, '.', '');
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['price_resource'] = 'webservice';
					/*if($v ['num'] > 1){
						$v ['num'] = 1;
					}*/
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['least_num'] = $least_count;
					$book_status = 'full';
					$valid_state [$v['RoomTypeID']] [$v['RoomRateTypeID']] = $pms_state [$v['RoomTypeID']] [$v['RoomRateTypeID']];

					if($can_book){
						$book_status = 'available';
					}
					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['book_status'] = $book_status;
					$exprice [$v['RoomTypeID']] [] = $pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['avg_price'];

					//					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['condition']['pre_pay'] = empty ($params ['pre_pays'] [$price_types [$room_detail ['ratePlanCode']] ['payment_code']]) ? 0 : 1;
					//					$pms_state[$v['RoomTypeID']][$v['RoomRateTypeID']]['condition'] = ['no_pay_way'] = empty ($params ['no_pays'] [$price_types [$room_detail ['ratePlanCode']] ['payment_code']]) ? array() : $params ['no_pays'] [$price_types [$room_detail ['ratePlanCode']] ['payment_code']];


					//					}
				}
			}
		}
		return array(
			'pms_state'   => $pms_state,
			'valid_state' => $valid_state,
			'exprice'     => $exprice
		);
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

	function get_rooms_change_lmem($pms_data, $rooms, $params){
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_type'];
		$price_level = $params ['price_level'];
		$condit = $params ['condit'];
		$this->load->model('hotel/Order_model');
		//		echo '<pre>';print_r($local_rooms);exit;
		$data = $this->Order_model->get_rooms_change($local_rooms, $params ['idents'], $params ['condit']);
		$pms_state = $pms_data ['pms_state'];
		$valid_state = $pms_data ['valid_state'];
		//		echo '<pre>';
		//		print_r($pms_data);
		//		exit;
		//		print_r($data);exit;
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
						$external_code = $params ['level_reflect'] ['member_level'] [$si ['external_code']];
						$external_code_reflect = $params ['level_reflect'] ['member_price_code'] [$external_code];
						$external_code = $params ['level_reflect'] ['member_price_code'] [$price_level];
					}*/
					if($si ['external_code'] !== ''){
						//						$external_code_reflect = $params ['level_reflect'] ['member_level'] [$si ['external_code']];
						$external_code_reflect = $params ['level_reflect'] ['member_price_code'] [$si ['external_code']];
					}
					//					exit($external_code_reflect);
					if(isset ($external_code_reflect) && !empty ($pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect])){
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect];
						//						echo '<pre>';print_r($tmp);exit;
						// $otmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$external_code];
						$nums = isset ($condit ['nums'] [$lrm ['room_info'] ['room_id']]) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;

						/*if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member') {
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						} else {
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $data [$room_key] ['state_info'] [$sik] ['least_num'] <= $tmp ['least_num'] ? $data [$room_key] ['state_info'] [$sik] ['least_num'] : $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
							if ($data [$room_key] ['state_info'] [$sik] ['least_num'] <= 0) {
								$data [$room_key] ['state_info'] [$sik] ['book_status'] = 'full';
							}
						}*/

						$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
						$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];


						$data [$room_key] ['state_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];
						// $data [$room_key] ['state_info'] [$sik] ['extra_info'] ['channel_code'] = $price_level;
						$allprice = '';
						$amount = 0;
						//						echo '<pre>';
						//						print_r($tmp);
						//						print_r($lrm);
						foreach($tmp ['date_detail'] as $dk => $td){
							if($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member'){
								$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'));
							} else{
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
						$data [$room_key] ['state_info'] [$sik] ['condition']['no_pay_way'] = array(
							'weixin',
						);
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
				$data [$room_key] ['lowest'] = empty ($min_price) ? 0 : min($min_price);
				$data [$room_key] ['highest'] = empty ($min_price) ? 0 : max($min_price);
				if(!$lrm['show_info']){
					$lrm['show_info'] = $lrm['state_info'];
					$data [$room_key] ['show_info'] = $lrm['state_info'];
				}
				foreach($lrm ['show_info'] as $sik => $si){
					if($si ['external_code'] !== ''){
						//						$external_code_reflect = $params ['level_reflect'] ['member_level'] [$si ['external_code']];
						$external_code_reflect = $params ['level_reflect'] ['member_price_code'] [$si ['external_code']];
					}
					if(isset ($external_code_reflect) && !empty ($pms_state [$lrm ['room_info'] ['webser_id']] [$external_code_reflect])){
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
					}
				}
			}
			if(empty ($data [$room_key] ['state_info'])){
				unset ($data [$room_key]);
			}
		}

		//		echo '<pre>';print_r($data);exit;
		return $data;
	}


	public function sub_to_web($pms_set, $fun_name, $params, $auth = false){
		if(!is_array($pms_set ['pms_auth'])){
			$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], true);
		}
		$now = time();
		try{
			$soap = new soapclient ($pms_set ['pms_auth'] ['url'], array(
				'encoding' => 'UTF-8',
			));

			if($auth === true){
				/*$var_arr = array(
					'UserID'   => $pms_set['pms_auth']['user'],
					'PassWord' => $pms_set['pms_auth']['pwd'],
				);
				$var_obj = new stdClass;
//				$var_obj->Token=$var_arr;
				$var_obj->UserID = $pms_set['pms_auth']['user'];
				$var_obj->PassWord = $pms_set['pms_auth']['pwd'];
//				$var_arr=array($var_arr);
				$var_xml = '<ns:myHeader><UserID>' . $pms_set['pms_auth']['user'] . '</UserID><PassWord>' . $pms_set['pms_auth']['pwd'] . '</PassWord></ns:myHeader>';
//				$var_xml='<ns1:Auth><item><key>UserID</key><value>'.$pms_set['pms_auth']['user'].'</value></item><item><key>PassWord</key><value>'.$pms_set['pms_auth']['pwd'].'</value></item></ns1:Auth>';
				$soap_var = new SoapVar($var_xml, XSD_ANYXML);
//				$soap_var = new SoapVar($var_obj, SOAP_ENC_OBJECT);
//				$soap_var = new SoapVar($var_arr, SOAP_ENC_ARRAY);
				$soap_header = new SoapHeader('http://tempuri.org/', 'myHeader', $soap_var, TRUE);
//				$soap_header=new SoapHeader('http://tempuri.org/', ':myHeader',$var_arr);
				$soap->__setSoapHeaders($soap_header);*/

				$params['token'] = strtoupper(md5($pms_set['pms_auth']['user'] . $pms_set['pms_auth']['pwd']));


			}

			/*print_r($soap->__soapCall($fun_name, array(
				'parameters' => $params
			)));*/

			$s = $soap->__soapCall($fun_name, array(
				'parameters' => $params
			))->{$fun_name . 'Result'};

			$Err = array(
				'0',
				'0',
				'0'
			);
			//		$params ['Err'] = $Err;
			//		$params ['user_cd'] = $pms_set ['pms_auth'] ['user'];
			//		$params ['password'] = $pms_set ['pms_auth'] ['pwd'];
			//		$params ['lang'] = $pms_set ['pms_auth'] ['lang'];
			if($this->local_test == false){
				$mirco_time = microtime();
				$mirco_time = explode(' ', $mirco_time);
				$wait_time = $mirco_time[1] - $now + number_format($mirco_time [0], 2, '.', '');
				$this->db->insert('webservice_record', array(
					'send_content'    => json_encode($params),
					'receive_content' => $s,
					'record_time'     => $now,
					'inter_id'        => $pms_set ['inter_id'],
					'service_type'    => 'yasite',
					'web_path'        => $pms_set ['pms_auth'] ['url'] . '/' . $fun_name,
					'record_type'     => 'webservice',
					'openid'          => $this->session->userdata($pms_set ['inter_id'] . 'openid'),
					'wait_time'       => $wait_time
				));
			}

			if($json = json_decode($s, true)){
				return $json;
			} else{
				return $s;
			}
		} catch(SoapFault $e){
			$error['error'] = $e;

			$mirco_time = microtime();
			$mirco_time = explode(' ', $mirco_time);
			$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');

			if($this->local_test == false){

				$this->db->insert('webservice_record', array(
					'send_content'    => json_encode($params),
					'receive_content' => json_encode($error),
					'record_time'     => $now,
					'inter_id'        => $pms_set ['inter_id'],
					'service_type'    => 'yasite',
					'web_path'        => $pms_set ['pms_auth'] ['url'] . '/' . $fun_name,
					'record_type'     => 'webservice',
					'openid'          => $this->session->userdata($pms_set ['inter_id'] . 'openid'),
					'wait_time'       => $wait_time
				));
			}
			return $error;
		}

	}

	function order_reserve($order, $pms_set){
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
		//			$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], TRUE);
		$union_order = new stdClass();
		$union_order->bookNum = $order['roomnums'];
		$union_order->bookPerson = $order ['name'];
		$union_order->bookTel = $order['tel'];
		$union_order->edate = date('Y-m-d', strtotime($order['enddate']));
		$union_order->sdate = date('Y-m-d', strtotime($order['startdate']));
		$union_order->checkInPerson = $order ['name'];
		$union_order->hotelId = $pms_set ['hotel_web_id'];
		$union_order->hotelnm = '';
		$union_order->houseTypeId = $room_codes['room']['webser_id'];
		$union_order->houseTypeName = '';//$room_codes['room']['name'];
		$union_order->keepTime = $order['holdtime'];
		$remark = '';
		if(!empty ($params ['trans_no'])){
			$resType = 1;
			$remark = '系统备注：此订单为微信端网上支付订单，客人已支付房费' . $order ['price'] . '元。请客人出示手机核实微信支付记录。';
		}
		$daily_price = explode(',', $order ['first_detail'] ['allprice']);
		if($order ['coupon_favour'] > 0){
			$daily_price [0] -= $order ['coupon_favour'];
			$remark .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
		}
		$union_order->note = $remark;
		$union_order->price = $order['price'];
		//			$union_order->uid=$order['member_no'];
		$this->load->model('member/Imember');
		$check = $this->Imember->getMemberInfoByOpenId($order ['openid'], $pms_set['inter_id'], 0);
		if(!empty ($check->membership_number)){
			$union_order->uid = $check->membership_number;
		}

		$res = $this->sub_to_web($pms_set, 'OrderReserve', array('order' => $union_order), true);
		if(isset($res['result']) && $res['result'] == 'success'){
			return array(
				'result' => true,
				'oid'    => $res['order']['oid'],
			);
		}
		return array(
			'result' => false,
			'errmsg' => isset($res ['msg']) ? $res['msg'] : '',
		);
	}

	private function handleDate($value){
		return substr($value, strpos($value, '(') + 1, 10);
	}

	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		$web_order = $this->get_web_order($order['web_orderid'], $pms_set);
		$web_order['Arrival'] = $this->handleDate($web_order['Arrival']);
		$web_order['Arrorig'] = $this->handleDate($web_order['Arrorig']);
		$web_order['Depart'] = $this->handleDate($web_order['Depart']);
		$web_order['Deporig'] = $this->handleDate($web_order['Deporig']);
		$web_order['CreateTime'] = $this->handleDate($web_order['CreateTime']);
		$web_order['CreateAccDate'] = $this->handleDate($web_order['CreateAccDate']);
		$web_order['ArrAccDate'] = $this->handleDate($web_order['ArrAccDate']);
		$web_order['DepAccDate'] = $this->handleDate($web_order['DepAccDate']);
		$web_order['ChkOutTime'] = $this->handleDate($web_order['ChkOutTime']);
		$web_order['ChkOutAccDate'] = $this->handleDate($web_order['ChkOutAccDate']);
		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');
			$status = $status_arr [$web_order['FolioState']];
		}
		if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
			return true;
		} else{
			return false;
		}
	}
}