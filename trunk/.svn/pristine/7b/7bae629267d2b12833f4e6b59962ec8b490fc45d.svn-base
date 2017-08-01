<?php
class Yuanzhou_hotel_model extends CI_Model {
	function __construct() {
		parent::__construct ();
		// $this->nid = 'a440577876';
	}
	function get_rooms_change($rooms, $idents, $condit, $pms_set = array()) {
		$hotel_no = $idents ['hotel_web_id'];
		$startdate = $condit ['startdate'];
		$enddate = $condit ['enddate'];
		
		$data = array ();
		$webrooms = $this->get_web_roomtype ( $startdate, $enddate, $hotel_no, 2 );
		if (! empty ( $webrooms )) {
			$countday = get_room_night($startdate,$enddate,'ceil',$condit);//至少有一个间夜
			$this->load->model ( 'hotel/Order_model' );
			$data = $this->Order_model->get_rooms_change ( $rooms, $idents, $condit );
			if (! empty ( $pms_set ['pms_room_state_way'] ) && $pms_set ['pms_room_state_way'] == 2) { // 取房态方式为仅房量
				$external = array ();
				$extra = array ();
				$web_room_ids = array_keys ( $webrooms );
				foreach ( $data as $room_id => $state ) {
					$room = $state ['room_info'];
					if (in_array ( $room ['webser_id'], $web_room_ids )) {
						
						$nums = isset ( $condit ['nums'] [$room ['room_id']] ) ? $condit ['nums'] [$room ['room_id']] : 1;
						
						foreach ( $state ['state_info'] as $sk => $s ) {
							$real_nums = $data [$room_id] ['state_info'] [$sk] ['least_num'] <= $webrooms [$room ['webser_id']] ['leftnums'] ? $data [$room_id] ['state_info'] [$sk] ['least_num'] : $webrooms [$room ['webser_id']] ['leftnums'];
							if ($real_nums >= $nums)
								$data [$room_id] ['state_info'] [$sk] ['book_status'] = 'available';
							else
								$data [$room_id] ['state_info'] [$sk] ['book_status'] = 'full';
							$data [$room_id] ['state_info'] [$sk] ['least_num'] = $real_nums; // 替代房间数
						}
					} else {
						unset ( $data [$room_id] );
					}
				}
			}
		}
		return $data;
	}
	function get_web_roomtype($startdate, $enddate, $hotelcode, $membertype = 2) {
		$data ['language'] = '';
		$data ['arrivaldate'] = date ( 'Y-m-d', strtotime ( $startdate ) );
		$data ['leavedate'] = date ( 'Y-m-d', strtotime ( $enddate ) );
		$data ['hotelcode'] = $hotelcode;
		$data ['membertype'] = $membertype;
		$result = $this->post_to ( 'queryRoomtypeList', $data );
		$rooms = array ();
		if (! empty ( $result ['roomtypeinfos'] ['roomtype'] )) {
			$info = $result ['roomtypeinfos'] ['roomtype'];
			foreach ( $info as $i ) {
				$rooms [$i ['roomtypecode']] ['leftnums'] = $i ['availableroom'];
				$rooms [$i ['roomtypecode']] ['price'] = $i ['actualprice'];
			}
		}
		return $rooms;
	}
	function add_web_order($inter_id, $orderid, $params = array(), $pms_set = array()) {
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $inter_id, array (
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $order )) {
			$order = $order [0];
			$hotelcode = $pms_set ['hotel_web_id'];
// 			$this->load->model ( 'member/Imember' );
// 			$member_detail = $this->Imember->getMemberDetailByOpenId ( $order ['openid'], $inter_id, 0 );
			$this->load->model ( 'hotel/Member_model' );
			$member_detail = $this->Member_model->check_openid_member ( $inter_id, $order ['openid'] );
			$memberid = '';
			$member_ship_no = '';
			if (! empty ( $member_detail ) && $member_detail->member_mode == '2' ) {
				$memberid = $member_detail->mem_card_no_old;
				$member_ship_no = $member_detail->membership_number;
			}
			$room_codes = json_decode ( $order ['room_codes'], TRUE );
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			$order_prefix = 'V';
			// if ($room_codes ['code'] ['price_type'] == 'member' && $memberid) {
			if ($memberid && $member_ship_no) {
				$data ['membertype'] = '1'; // 1 会员
				$data ['ratecode'] = 'WXHYJ';
				$order_prefix = 'S';
			} else {
				$data ['membertype'] = '2'; // 2 非会员
				$data ['ratecode'] = 'WXSKJ';
			}
			
			$note = '';
			if ( $order ['coupon_favour'] > 0) {
				$note .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
			}
			
			$data ['hotelcode'] = $hotelcode;
			$data ['roomrate'] = $order ['first_detail'] ['price_code'];
			/*
			 * 会员等级+3位组织号+4位年+2位月+2位日+3位流水号
			 * 会员等级V代表普通会员，即微信会员，S代表银卡会员，G代表金卡会员，P代表白金会员
			 */
			$order_nums = $this->get_order_date_count ( $order ['inter_id'], date ( 'Ymd' ) )->num_rows ();
			$tran_no = intval ( $order_nums ) + 1;
			$data ['orderno'] = $order_prefix . str_pad ( $hotelcode, 3, 0, STR_PAD_LEFT ) . date('Ymd') . str_pad ( $tran_no, 3, 0, STR_PAD_LEFT );
			$data ['wxuserid'] = $memberid;
			$data ['orderstatus'] = '2';
			$data ['roomtypecode'] = $room_codes ['room'] ['webser_id'];
			$data ['roomtypename'] = $order ['first_detail'] ['roomname'];
			// $data['ratecode']='WXOP';
			// $data ['actualprice'] = $order ['price'];
			$allprice = explode ( ',', $order ['first_detail'] ['allprice'] );
			$data ['actualprice'] = $allprice [0];
			$data ['roomqty'] = $order ['roomnums'];
			$data ['arrivaldate'] = date ( 'Y-m-d', strtotime ( $order ['startdate'] ) );
			$data ['leavedate'] = date ( 'Y-m-d', strtotime ( $order ['enddate'] ) );
			$data ['customername'] = $order ['name'];
			$data ['child'] = 0;
			$data ['addbed'] = 0;
			$data ['cardno'] = $member_ship_no; // @author lGh 2016-3-23 10:53:13 补传cardno
			$data ['phone'] = $order ['tel'];
			$data ['email'] = '';
			$data ['note'] = $note;
			$data ['payment'] = array ();
			if (! empty ( $params ['trans_no'] )) {
				$data ['payment'] ['mode'] = '4';
				$data ['payment'] ['serialnumber'] = $params ['trans_no'];
			}else if($order ['paytype'] == 'point'){
				$data ['payment'] ['mode'] = '5';
				$data ['payment'] ['serialnumber'] = '';
			} else {
				$data ['payment'] ['mode'] = '2';
				$data ['payment'] ['serialnumber'] = '';
			}
			// $data ['payment'] ['amount'] = $order ['price'];
			$data ['payment'] ['amount'] = '0'; // 1228 接口修改，不传amount，mode现付传2，在线支付传4
// 			                                    var_dump ( $data );
// 			                                    exit ();
			$result = $this->post_to ( 'booking', $data );
			if ($result ['statuscode'] == '200') {
				$web_orderid = $data ['orderno'];
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_order_additions', array (
						'web_orderid' => $web_orderid 
				) );
				
				if ($order['status']!=9){
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id']
					) );
					$this->db->update ( 'hotel_orders', array (
							'status' => 1
					) );
					$this->Order_model->handle_order ( $inter_id, $orderid, 1 );
				}else if($order ['paytype'] == 'point'){
					$point_param=array(
							'extra'=>array(
										'pms_order_id'=>$web_orderid,
										'pms_hotel_id'=>$data ['hotelcode']
									)
					);
					$this->load->model ( 'hotel/Member_model' );
					if (! $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'], $order ['openid'], $order ['point_used_amount'],$point_param )) {
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
									'errmsg' => '积分支付失败'
							);
					}else {
						$this->Order_model->update_order_status ( $order ['inter_id'], $order ['orderid'], 1, $order ['openid'], true,true );
					}
				}
				return array (
						's' => 1
				);
			} else {
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_orders', array (
						'status' => 10 
				) );
				return array (
						's' => 0,
						'errmsg' => '提交订单失败' 
				);
			}
		}
		return array (
				's' => 0,
				'errmsg' => '提交订单失败' 
		);
	}
	function get_order_date_count($inter_id, $date) {
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'order_time >=' => strtotime ( $date ),
				'order_time <=' => (strtotime ( $date ) + 86400) 
		) );
		return $this->db->get ( 'hotel_orders' );
	}
	function cancel_web_order($inter_id, $order) {
		$data ['orderno'] = $order ['web_orderid'];
		$result = $this->post_to ( 'Cancelbooking', $data );
		if ($result ['statuscode'] == '200') {
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
	function update_web_order($inter_id, $order) {
		$new_status = $this->get_web_order_status ( $order ['web_orderid'] );
		if (! is_null ( $new_status )) {
			switch ($new_status) {
// 				case '2' :
// 					if ($order ['enddate'] <= date ( 'Ymd', strtotime ( '-3 day', time () ) ))
// 						$new_status = 3;
// 					break;
				case '5' :
					if ($order ['status'] == 4)
						$new_status = 4;
					break;
				default :
					break;
			}
			if ($new_status != $order ['status'] && $new_status !== FALSE) {
				$this->load->model ( 'hotel/Order_model' );
				$this->db->where ( array (
						'inter_id' => $order ['inter_id'],
						'orderid' => $order ['orderid'] 
				) );
				$this->db->update ( 'hotel_orders', array (
						'status' => $new_status 
				) );
				$this->Order_model->handle_order ( $inter_id, $order ['orderid'], $new_status, $order ['openid'] );
			}
		}
		return $new_status;
	}
	function get_web_order_status($orderno) {
		$data ['orderno'] = $orderno;
		$result = $this->post_to ( 'queryOrderState', $data );
		$status_des = $this->web_order_status ();
		$new_status = isset ( $result ['state'] ) && isset ( $status_des [$result ['state']] ) ? $status_des [$result ['state']] : null;
		return $new_status;
	}
	
	function add_web_bill($web_orderid, $order, $trans_no = '',$pms_set){
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $order['inter_id'], array (
				'orderid' => $order['orderid'],
				'idetail' => array (
						'i'
				)
		) );
		$web_paid = 2;
		$s = FALSE;
		if (! empty ( $order )) {
			$order = $order [0];
			$hotelcode = $pms_set ['hotel_web_id'];
			$this->load->model ( 'hotel/Member_model' );
			$member_detail = $this->Member_model->check_openid_member ( $order['inter_id'], $order ['openid'] );
			$memberid = '';
			$member_ship_no = '';
			if (! empty ( $member_detail ) && $member_detail->member_mode == '2' ) {
				$memberid = $member_detail->mem_card_no_old;
				$member_ship_no = $member_detail->membership_number;
			}
			$room_codes = json_decode ( $order ['room_codes'], TRUE );
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			$order_prefix = 'V';
			if ($memberid && $member_ship_no) {
				$data ['membertype'] = '1'; // 1 会员
				$data ['ratecode'] = 'WXHYJ';
				$order_prefix = 'S';
			} else {
				$data ['membertype'] = '2'; // 2 非会员
				$data ['ratecode'] = 'WXSKJ';
			}
		
			$note = '';
			if ( $order ['coupon_favour'] > 0) {
				$note .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
			}
		
			$data ['hotelcode'] = $hotelcode;
			$data ['roomrate'] = $order ['first_detail'] ['price_code'];
			/*
			 * 会员等级+3位组织号+4位年+2位月+2位日+3位流水号
			 * 会员等级V代表普通会员，即微信会员，S代表银卡会员，G代表金卡会员，P代表白金会员
			 */
			$data ['orderno'] = $order['web_orderid'];
			$data ['wxuserid'] = $memberid;
			$data ['orderstatus'] = '2';
			$data ['roomtypecode'] = $room_codes ['room'] ['webser_id'];
			$data ['roomtypename'] = $order ['first_detail'] ['roomname'];
			// $data['ratecode']='WXOP';
			// $data ['actualprice'] = $order ['price'];
			$allprice = explode ( ',', $order ['first_detail'] ['allprice'] );
			$data ['actualprice'] = $allprice [0];
			$data ['roomqty'] = $order ['roomnums'];
			$data ['arrivaldate'] = date ( 'Y-m-d', strtotime ( $order ['startdate'] ) );
			$data ['leavedate'] = date ( 'Y-m-d', strtotime ( $order ['enddate'] ) );
			$data ['customername'] = $order ['name'];
			$data ['child'] = 0;
			$data ['addbed'] = 0;
			$data ['cardno'] = $member_ship_no;
			$data ['phone'] = $order ['tel'];
			$data ['email'] = '';
			$data ['note'] = $note;
			$data ['pay_mode'] = 4;
			$data ['payment'] = array ();
			$data ['payment'] ['mode'] = '2';
			$data ['payment'] ['serialnumber'] = '';
			$data ['payment'] ['amount'] = '0';
// 			return $data;
			$result = $this->post_to ( 'updateOrderinfo', $data );
			if ($result ['statuscode'] == '200') {
				$web_paid = 1;
				$s = TRUE;
			}
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
	
	function web_order_status() {
		return array (
				'1' => 1,
				'3' => 2,
				'99' => 5,
				'98' => 8,
				'97' => 7,
				'4' => 3 
		);
	}
	function _post_to($fun_name, $data, $inter_id = 'a440577876') {
		$this->load->helper ( 'common' );
		$url = 'http://120.76.193.180/yuanzhou/CommonAction';
		$xml = $this->array2req ( $data );
		$post ['func'] = $fun_name;
		$post ['debug'] = 0; // 1 调试
		$post ['content'] = $xml;
		$send_content = http_build_query ( $post );
		$now = time ();
		$s = doCurlPostRequest ( $url, $send_content, '', 20 );
		
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'yuanzhou', $url, $post, $s,'query_post', $now, microtime (), $this->session->userdata ( $inter_id . 'openid' ));
		
		$s = json_decode ( json_encode ( simplexml_load_string ( $s ) ), true );
		return $s;
	}
	function post_to($fun_name, $data, $inter_id = 'a440577876') {
		$this->load->library ( 'Baseapi/Yzhou_webservice.php' );
		$YzhouOj = new Yzhou_webservice ( );
		$xml = $this->array2req ( $data );
		
		$xml = htmlspecialchars_decode($xml);
		$debug=0;
		$s= $YzhouOj->sendTo($fun_name, $xml,$debug,$inter_id);
	
		$s = json_decode ( json_encode ( simplexml_load_string ( $s ) ), true );
		return $s;
	}
	function array2req($arr) {
		return "<?xml version='1.0' encoding='utf-8'?><request>" . $this->arrayToXml ( $arr ) . "</request>";
	}
	
	/**
	 * 作用：array转xml
	 */
	function arrayToXml($arr, $k = null) {
		$xml = '';
		if (! is_null ( $k ))
			$xml .= "<$k>";
		foreach ( $arr as $key => $val ) {
			if (is_array ( $val )) {
				$xml .= $this->arrayToXml ( $val, $key );
			} else if (is_numeric ( $val )) {
				$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
			} else
				$xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
		}
		if (! is_null ( $k ))
			$xml .= "</$k>";
		return $xml;
	}
	//判断订单是否能支付
	function check_order_canpay($order) {
		$new_status = $this->get_web_order_status ( $order ['web_orderid'] );
		if(isset($new_status) && ($new_status == 1 || $new_status == 0)){//订单预定或确认
			return true;
		}else{
			return false;
		}
	}
}