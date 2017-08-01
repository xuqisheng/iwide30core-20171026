<?php
class Buding_hotel_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	function set_sessionid($soap, $pms_set, $fresh = false, $sessions = array()) {
		if ($fresh == TRUE) {
			$session_id = $this->refresh_session_id ( $pms_set ['inter_id'], $pms_set ['pms_auth'] ['url'] );
		} else {
			$session_id = $this->session->userdata ( $pms_set ['inter_id'] . '_net_session_id' );
			if (empty ( $session_id )) {
				$session_id = $this->refresh_session_id ( $pms_set ['inter_id'], $pms_set ['pms_auth'] ['url'] );
			}
		}
		$soap->__setCookie ( 'ASP.NET_SessionId', $session_id );
		if (! empty ( $sessions )) {
			foreach ( $sessions as $sid => $s ) {
				$soap->__setCookie ( $sid, $s );
			}
		}
		$this->session->set_userdata ( array (
				$pms_set ['inter_id'] . '_net_session_id' => $session_id 
		) );
		return $soap;
	}
	function refresh_session_id($inter_id, $url) {
		$soap = new SoapClient ( $url, array (
				'encoding' => 'UTF-8' 
		) );
		$now = time ();
		$security_result = $soap->__Call ( 'GetSecurityKey', array () );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'webservice_record', array (
				'send_content' => '--cookie->' . json_encode ( $soap->_cookies ),
				'receive_content' => json_encode ( $security_result ),
				'record_time' => $now,
				'inter_id' => $inter_id,
				'service_type' => 'buding',
				'web_path' => $url . '/' . 'GetSecurityKey',
				'record_type' => 'webservice',
				'openid'=>$this->session->userdata($inter_id.'openid'),
				'wait_time'=>$wait_time 
		) );
		$security_key = $security_result->GetSecurityKeyResult;
		$this->session->set_userdata ( array (
				$inter_id . '_encrypt_key' => $security_key 
		) );
		$cookies = $soap->_cookies ["ASP.NET_SessionId"] [0];
		return $cookies;
	}
	function webservice_login($pms_set) {
		$soap = new soapclient ( $pms_set ['pms_auth'] ['url'], array (
				'encoding' => 'UTF-8' 
		) );
		$this->set_sessionid ( $soap, $pms_set, TRUE );
		$user_name = $this->des_encode ( $soap, $pms_set ['pms_auth'] ['user'], $pms_set, 'web' );
		$password = $this->des_encode ( $soap, $pms_set ['pms_auth'] ['pwd'], $pms_set, 'web' );
		$data = array (
				"userName" => $user_name,
				"password" => $password 
		);
		$now = time ();
		$result = $soap->__call ( 'WebServiceLogin', array (
				'parameters' => $data 
		) );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'webservice_record', array (
				'send_content' => json_encode ( $data ) . '--cookie->' . json_encode ( $soap->_cookies ),
				'receive_content' => json_encode ( $result ),
				'record_time' => $now,
				'inter_id' => $pms_set ['inter_id'],
				'service_type' => 'buding',
				'web_path' => $pms_set ['pms_auth'] ['url'] . '/' . 'WebServiceLogin',
				'record_type' => 'query_post',
				'openid'=>$this->session->userdata($pms_set ['inter_id'].'openid'),
				'wait_time'=>$wait_time 
		) );
		if (! empty ( $result->WebServiceLoginResult ) && $result->WebServiceLoginResult == 'OK') {
			return $soap;
		}
		return FALSE;
	}
	
	// 加密
	/**
	 *
	 * @param obj $soap
	 *        	php SoapClient
	 * @param string $text
	 *        	string to crypt
	 * @return string string after crypt
	 */
	function des_encode($soap, $text, $pms_set, $crypt_type = 'local') {
		switch ($crypt_type) {
			case 'web' :
				$data = array (
						'str' => $text 
				);
				$now = time ();
				$encode_result = $soap->__Call ( 'Encode', array (
						'parameters' => $data 
				) );
				$mirco_time = microtime ();
				$mirco_time = explode ( ' ', $mirco_time );
				$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
				$this->db->insert ( 'webservice_record', array (
						'send_content' => json_encode ( $data ) . '--cookie->' . json_encode ( $soap->_cookies ),
						'receive_content' => json_encode ( $encode_result ),
						'record_time' => $now,
						'inter_id' => $pms_set ['inter_id'],
						'service_type' => 'buding',
						'web_path' => $pms_set ['pms_auth'] ['url'] . '/' . 'Encode',
						'record_type' => 'query_post',
						'openid'=>$this->session->userdata($pms_set ['inter_id'].'openid'),
						'wait_time'=>$wait_time 
				) );
				return $encode_result->EncodeResult;
				break;
			case 'local' :
				$this->load->library ( 'Des_crypt', NULL, 'des' );
				return $this->des->encrypt ( $text, '123' );
				break;
			default :
				return '';
		}
	}
	function local_des_encrypt($text, $key) {
		if (empty ( $this->des )) {
			$this->load->library ( 'Des_crypt', NULL, 'des' );
		}
		return $this->des->encrypt ( $text, $key );
	}
	function get_rooms_change($rooms, $idents, $condit, $pms_set) {
		$days = get_room_night($condit['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$params = array (
				'days' => $days 
		);
		$this->load->model ( 'common/Webservice_model' );
		$level_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
				'member_price_code_name',
				'basic_price_code',
				'member_price_code' 
		), 1, 'w2l' );
		$params ['level_reflect'] = $level_reflect;
		$member_type = $condit ['member_level'];
		$params ['web_code'] = empty ( $level_reflect ['member_price_code'] [$member_type] ) ? current ( $level_reflect ['basic_price_code'] ) : $level_reflect ['member_price_code'] [$member_type];
		$pms_data = $this->get_web_roomtype ( $pms_set, $condit ['startdate'], $condit ['enddate'], $params );
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
	function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()) {
		$this->load->model ( 'hotel/Order_model' );
		$this->load->model ( 'hotel/Hotel_model' );
		$order = $this->Order_model->get_main_order ( $inter_id, array (
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $order )) {
			$order = $order [0];
			$hotel = $this->Hotel_model->get_hotel_detail ( $inter_id, $order ['hotel_id'] );
			$room_codes = json_decode ( $order ['room_codes'], TRUE );
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			$pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
			$soap = new soapclient ( $pms_set ['pms_auth'] ['url'], array (
					'encoding' => 'UTF-8' 
			) );
			$this->set_sessionid ( $soap, $pms_set );
			$web_orderid = $this->no_member_order ( $soap, $pms_set, $order, array (
					'hotel' => $hotel,
					'room_codes' => $room_codes 
			) );
			if (! empty ( $web_orderid )) {
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_order_additions', array (
						'web_orderid' => $web_orderid 
				) );
				if ($order ['status'] != 9) {
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id'] 
					) );
					$this->db->update ( 'hotel_orders', array (
							'status' => 1 
					) );
					$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
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
						'errmsg' => '提交订单失败' . ',' . $result->RetMsg 
				);
			}
		}
		return array (
				's' => 0,
				'errmsg' => '提交订单失败' 
		);
	}
	function no_member_order($soap, $pms_set, $order, $data) {
		$memid_crt = $this->des_encode ( $soap, '699949', $pms_set, 'web' );
		$params = array (
				"memID" => $memid_crt,
				"cardNo" => '10000148',
				"cardType" => '200',
				"HotelID" => $pms_set ['hotel_web_id'],
				'hotelName' => $data ['hotel'] ['name'],
				'InDate' => date ( 'Y-m-d', strtotime ( $order ['startdate'] ) ),
				'InDate2' => date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ),
				'RoomType' => $data ['room_codes'] ['room'] ['webser_id'],
				'RoomTypeName' => $order ['first_detail'] ['roomname'],
				'RoomCount' => $order ['roomnums'],
				'InName' => $order ['name'],
				'LinkEmail' => '',
				'LinkMobile' => $order ['tel'],
				'from' => '22641',
				'OtherOrderNo' => $order['orderid'],
				'payWay' => $this->pms_enum ( 'pay_type', $order ['paytype'] ),
				'totalMoney' => $order ['price'],
				'priceType' => '' 
		);
		$s = $this->sub_to_web ( $pms_set, 'SaveOrder4_2', $params );
		if (! empty ( $s->SaveOrder4_2Result )) {
			$result = simplexml_load_string ( $s->SaveOrder4_2Result );
			if (isset ( $result->RetCode ) && $result->RetCode == 0) {
				return $result->OrderNo;
			}
		}
		return FALSE;
	}
	function add_web_bill($order, $pms_set, $paras) {
		$params = array (
				'OrderNo' => $order ['web_orderid'],
				'OtherOrderNo' => $order ['orderid'],
				'money' => $order ['price'],
				'payMethodNo' => '107' 
		);
		$s = $this->sub_to_web ( $pms_set, 'DepositOrder', $params );
	}
	function cancel_order_web($inter_id, $order, $pms_set) {
		$web_orderid = isset ( $order ['web_orderid'] ) ? $order ['web_orderid'] : "";
		if (! $web_orderid) {
			return array (
					's' => 0,
					'errmsg' => '取消失败' 
			);
		}
		$params = array (
				'orderId' => $web_orderid,
				'mobile' => '',
				'DispoteMoney' => 0,
				'cancelReson' => '客人取消' 
		);
		$s = $this->sub_to_web ( $pms_set, 'CancelOrder', $params );
		if ($s->CancelOrderResult == 'OK') {
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
	function get_web_order($web_orderid, $pms_set, $paras = array()) {
		$pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
		$soap = new soapclient ( $pms_set ['pms_auth'] ['url'], array (
				'encoding' => 'UTF-8' 
		) );
		$this->set_sessionid ( $soap, $pms_set );
		$web_orderid = $this->des_encode ( $soap, $web_orderid, $pms_set, 'web' );
		// $web_orderid = $this->local_des_encrypt( $web_orderid);
		$params = array (
				'orderID' => $web_orderid,
				'mobile' => '',
				'bDailyPrice' => '1',
				'realReserveHour' => '',
				'fact' => '1' 
		);
		$s = $this->sub_to_web ( $pms_set, 'GetOrderDetail', $params );
		return $s;
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
	function get_web_roomtype($pms_set, $startdate, $enddate, $params = array()) {
		$member_price_code = $params ['web_code'];
		$web_state = $this->LoadHotelDetailPriceInfo4 ( $pms_set, $startdate, $enddate, $params );
		// $web_state = $this->LoadNextAllPrice_short ( $pms_set, $startdate, $enddate, $params );
		// $web_code = $params ['web_code'];
		
		// $data = array(
		// "userName"=>'645030813@qq.com',
		// "IsTravel"=>"0",
		// 'password'=>'123123'
		// );
		// $p=$this->sub_to_web ( $pms_set, 'Login', $data );
		// var_dump($p);
		
		// var_dump ( $web_state );
		// exit ();
		$pms_state = array ();
		$exprice = array ();
		$valid_state = array ();
		if (! empty ( $web_state ['Prices'] )) {
			$web_state = $web_state ['Prices'];
			foreach ( $web_state as $rl ) {
				$book_status = $rl ['CanBook'] == 2 ? 'available' : 'full';
				$least_num = $rl ['CanBook'] == 2 ? 1 : 0;
				foreach ( $rl as $pk => $pi ) {
					if (isset ( $params ['level_reflect'] ['member_price_code_name'] [$pk] ) && ! empty ( $pi )) {
						$web_code = $pk;
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['price_name'] = $params ['level_reflect'] ['member_price_code_name'] [$pk];
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['price_type'] = 'pms';
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['extra_info'] = array (
								'type' => 'code',
								'pms_code' => $web_code 
						);
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['price_code'] = $web_code;
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['des'] = '';
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['sort'] = 0;
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['disp_type'] = 'buy';
						
						$allprice = '';
						$amount = '';
						$least_nums = array ();
						
						for($i = 0; $i < $params ['days']; $i ++) {
							$pms_state [$rl ['RoomTypeId']] [$web_code] ['date_detail'] [date ( 'Ymd', strtotime ( '+ ' . $i . ' day ', strtotime ( $startdate ) ) )] = array (
									'price' => $pi,
									'nums' => $least_num 
							);
							$allprice .= ',' . $pi;
							$amount += $pi;
							$least_nums [] = $least_num;
						}
						if ($amount <= 0) {
							unset ( $pms_state [$rl ['RoomTypeId']] [$web_code] );
							continue;
						}
						if (isset ( $params ['web_rids'] [$rl ['RoomTypeId']] )) {
							$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['RoomTypeId']]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['RoomTypeId']]];
						} else {
							$nums = 1;
						}
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['allprice'] = substr ( $allprice, 1 );
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['total'] = $amount;
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['related_des'] = '';
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['total_price'] = $amount * $nums;
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['avg_price'] = number_format ( $amount / $params ['days'], 2, '.', '' );
						;
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['price_resource'] = 'webservice';
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['least_num'] = min ( $least_nums );
						$book_status = 'full';
						if ($pms_state [$rl ['RoomTypeId']] [$web_code] ['least_num'] >= $nums)
							$book_status = 'available';
						$pms_state [$rl ['RoomTypeId']] [$web_code] ['book_status'] = $book_status;
						if ($web_code == $member_price_code) {
							$exprice [$rl ['RoomTypeId']] [] = $pms_state [$rl ['RoomTypeId']] [$web_code] ['avg_price'];
							$valid_state [$rl ['RoomTypeId']] [$web_code] = $pms_state [$rl ['RoomTypeId']] [$web_code];
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
	function LoadNextAllPrice($pms_set, $startdate, $enddate, $para = array()) {
		$price_code = empty ( $para ['web_code'] ) ? 'USER' : $para ['web_code'];
		$params = array (
				"HotelNo" => $pms_set ['hotel_web_id'],
				"startDate" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"endDate" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"RateCode" => $price_code,
				"roomCount" => "1" 
		);
		$s = $this->sub_to_web ( $pms_set, 'LoadNextAllPrice', $params );
		return $s;
	}
	
	/**
	 *
	 * @param unknown $pms_set        	
	 * @param unknown $startdate        	
	 * @param unknown $enddate        	
	 * @param array $para        	
	 * @return unknown D 日期 yyyy-MM-dd
	 *         L 门市价格, 等于字符串空时为无价格。
	 *         P 价格, 等于字符串空时为无价格。
	 *         B 是否可预订,2有房，0无房,[部分使用新规则，0:满房，否则为剩于房间数，>3时也只返回3]
	 *        
	 */
	function LoadNextAllPrice_short($pms_set, $startdate, $enddate, $para = array()) {
		$params = array (
				"HotelNo" => $pms_set ['hotel_web_id'],
				"startDate" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"endDate" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"RateCode" => $para ['web_code'],
				"resultType" => "json",
				'roomCount' => 1,
				'isListP' => 1 
		);
		$s = $this->sub_to_web ( $pms_set, 'LoadNextAllPrice_short', $params );
		return $s;
	}
	
	/**
	 *
	 * @param unknown $pms_set        	
	 * @param unknown $startdate        	
	 * @param unknown $enddate        	
	 * @param array $para        	
	 * @return mixed PriceUser 网站会员，手机端显示，只取这个价格。
	 *         PriceVip4 亿卡 (匿名下单使用这个价格)
	 *         IsMerage 是否合并多个价格，只取PriceUser
	 *         PriceVip5 乐卡
	 *         PriceVip9 Z卡
	 *         CanBook 状态，2：充足，1：紧张，0：无房
	 */
	function LoadHotelDetailPriceInfo4($pms_set, $startdate, $enddate, $para = array()) {
		$params = array (
				"hotelNo" => $pms_set ['hotel_web_id'],
				"start" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"end" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				'count' => 1,
				"linkTag" => '',
				'v' => '' 
		);
		$s = $this->sub_to_web ( $pms_set, 'LoadHotelDetailPriceInfo3', $params );
		return json_decode ( $s->LoadHotelDetailPriceInfo3Result, TRUE );
	}
	function LoadHotelDetailPriceInfo($pms_set, $startdate, $enddate) {
		$params = array (
				"hotelNo" => $pms_set ['hotel_web_id'],
				"start" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"end" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"count" => 1 
		);
		$s = $this->sub_to_web ( $pms_set, 'LoadHotelDetailPriceInfo', $params );
		return $s;
	}
	function search_hotel($pms_set, $startdate, $enddate) {
		$params = array (
				"start" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"end" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"city" => "0",
				"hotelName" => "",
				"porder" => "0",
				"plorder" => "0",
				"pageIndex" => "1",
				"pageSize" => "100",
				"resultType" => "xml" 
		);
		$s = $this->sub_to_web ( $pms_set, 'SearchHotel', $params );
		return json_decode ( json_encode ( simplexml_load_string ( $s->SearchHotelResult ) ), TRUE );
	}
	function SearchHotelMobileNew3($pms_set, $startdate, $enddate) {
		$params = array (
				"start" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"end" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"pageIndex" => 1,
				"pageSize" => 100,
				"city" => 0,
				"area" => 0,
				"areaType" => 1,
				"hotelName" => "",
				"porder" => 0,
				"plorder" => 0,
				"priceCode" => "LIST",
				"dataType" => "xml",
				"map" => '',
				"fun" => '',
				"brand" => '1',
				"linkTag" => '0' 
		);
		$s = $this->sub_to_web ( $pms_set, 'SearchHotelMobileNew3', $params );
		return json_decode ( json_encode ( simplexml_load_string ( $s->SearchHotelMobileNew3Result ) ), TRUE );
	}
	function search_hotel4($pms_set, $startdate, $enddate) {
		$params = array (
				"start" => date ( 'Y-m-d', strtotime ( $startdate ) ),
				"end" => date ( 'Y-m-d', strtotime ( $enddate ) ),
				"city" => "0",
				"hotelName" => "",
				"porder" => "0",
				"plorder" => "0",
				"pageIndex" => "1",
				"pageSize" => "100",
				"resultType" => "xml" 
		);
		$s = $this->sub_to_web ( $pms_set, 'SearchHotel4', $params );
		return json_decode ( json_encode ( simplexml_load_string ( $s->SearchHotel4Result ) ), TRUE );
	}
	function get_hotel_rooms($pms_set) {
		$params = array (
				"hotelNo" => $pms_set ['hotel_web_id'] 
		);
		$s = $this->sub_to_web ( $pms_set, 'LoadHotelRoomDetails', $params );
		return json_decode ( json_encode ( simplexml_load_string ( $s->LoadHotelRoomDetailsResult ) ), TRUE );
	}
	function update_web_order($inter_id, $list, $params) {
		$s = $this->get_web_order ( $list ['web_orderid'], $params ['pms_set'], array (
				'order' => $list 
		) );
		$new_status = null;
		if (! empty ( $s->GetOrderDetailResult )) {
			$result = json_decode ( json_encode ( simplexml_load_string ( $s->GetOrderDetailResult ) ), TRUE );
			if (! empty ( $result )) {
				$new_status = $this->pms_enum ( 'status', $result ['order_status_code'] );
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
		}
		return $new_status;
	}
	function pms_enum($type, $value) {
		$reflect = array ();
		switch ($type) {
			case 'status' :
				// 0:入住前 1:入住中 2已完成 3:已取消 4:Noshow (这个返回为，前台取消)
				$reflect = array (
						'0' => 1,
						'1' => 2,
						'2' => 3,
						'3' => 5,
						'4' => 8 
				);
				break;
			case 'pay_type' :
				$reflect = array (
						'weixin' => 3,
						'daofu' => 0 
				);
				break;
			default :
				break;
		}
		return isset ( $reflect [$value] ) ? $reflect [$value] : FALSE;
	}
	
	/**
	 *
	 * @param unknown $pms_set        	
	 * @param unknown $fun_name        	
	 * @param unknown $params        	
	 * @return unknown 提交流程：设置session_id，若session有保存，使用，若没有，刷新，再提交到pms，若返回 EX_无权限 ，重新登录，再次提交
	 */
	function sub_to_web($pms_set, $fun_name, $params, $sessions = array()) {
		if (! is_array ( $pms_set ['pms_auth'] ))
			$pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
		$soap = new soapclient ( $pms_set ['pms_auth'] ['url'], array (
				'encoding' => 'UTF-8' 
		) );
		$member_session_id = $this->session->userdata ( $pms_set ['inter_id'] . '_member_session_id' );
		if (! empty ( $member_session_id )) {
			$soap->__setCookie ( '.ASPXAUTH', $member_session_id );
		}
		$soap = $this->set_sessionid ( $soap, $pms_set, false, $sessions );
		$now = time ();
		$s = $soap->__Call ( $fun_name, array (
				'parameters' => $params 
		) );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$f = json_decode ( json_encode ( $s ), TRUE );
		$pri = current ( $f );
		if ($pri == 'EX_无权限') {
			$soap = $this->webservice_login ( $pms_set );
			$s = $soap->__Call ( $fun_name, array (
					'parameters' => $params 
			) );
		}
		$soap_cookies = $soap->_cookies;
		if (isset ( $soap_cookies ['.ASPXAUTH'] )) {
			$this->session->set_userdata ( array (
					$pms_set ['inter_id'] . '_member_session_id' => $soap_cookies ['.ASPXAUTH'] [0] 
			) );
		}
		$this->db->insert ( 'webservice_record', array (
				'send_content' => json_encode ( $params ) . '--cookie->' . json_encode ( $soap_cookies ),
				'receive_content' => json_encode ( $s ),
				'record_time' => $now,
				'inter_id' => $pms_set ['inter_id'],
				'service_type' => 'buding',
				'web_path' => $pms_set ['pms_auth'] ['url'] . '/' . $fun_name,
				'record_type' => 'webservice',
				'openid'=>$this->session->userdata($pms_set ['inter_id'].'openid'),
				'wait_time'=>$wait_time 
		) );
		return $s;
	}
	//判断订单是否能支付
	function check_order_canpay($list, $params){
		$s = $this->get_web_order ( $list ['web_orderid'], $params ['pms_set'], array (
				'order' => $list 
		) );
		if (! empty ( $s->GetOrderDetailResult )) {
			$result = json_decode ( json_encode ( simplexml_load_string ( $s->GetOrderDetailResult ) ), TRUE );
			if (! empty ( $result )) {
				$new_status = $this->pms_enum ( 'status', $result ['order_status_code'] );
			}
		}
		if(isset($new_status) && ($new_status==1 || $new_status==0)){//订单确认
			return true;
		}else{
			return false;
		}
	}
}