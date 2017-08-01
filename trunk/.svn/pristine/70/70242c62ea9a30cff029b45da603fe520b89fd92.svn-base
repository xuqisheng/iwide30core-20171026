<?php

class Kezhan_hotel_model extends MY_Model{
	private $serv_api;

	public function __construct(){
		parent::__construct();
		$this->serv_api = new KezhanApi();
		/*$this->serv_api->setAuthToen(array(
			                             'url'  => 'http://a.qininn.com:10008/JfkSoap',
			                             'user' => 'jfksoap',
			                             'pwd'  => 'jfk.qdkz.2016',
		                             ));*/
	}

	public function get_rooms_change($rooms, $idents, $condit, $pms_set = array()){
		//只取房型库存，再匹配本地记型
		$this->load->model('hotel/Order_model');
		//本地房态
		$data = $this->Order_model->get_rooms_change($rooms, $idents, $condit);

		//房型接口
		$this->serv_api->setAuthToen(json_decode($pms_set['pms_auth'], true));
		$startdate = date('Y-m-d', strtotime($condit['startdate']));
		$enddate = date('Y-m-d', strtotime($condit['enddate']));
		$hotel_web_id = $pms_set['hotel_web_id'];
		$web_inventory = $this->serv_api->getInventoryByHotel($hotel_web_id, $startdate, $enddate);
		if(isset($web_inventory['InventoryPrice'])){
			$_list = json_decode($web_inventory['InventoryPrice'], true);
			$price_list = array();
			foreach($_list as $v){
				$tmp = array();
				foreach($v['stockListRQPrice'] as $t){
					$tmp[date('Ymd', strtotime($t['date']))] = array(
						'nums' => $t['quota']
					);
				}
				$price_list[$v['roomTypeId']] = $tmp;
			}
		}
		foreach($data as &$v){
			$web_room = $v['room_info']['webser_id'];
			if(!empty($v['state_info'])){
				foreach($v['state_info'] as &$t){
					$can_book=true;
					foreach($t['date_detail'] as $k => &$w){
						if(isset($price_list[$web_room][$k])){
							$w['nums'] = $price_list[$web_room][$k]['nums'];
							if($can_book&&$w['nums']<1){
								$can_book=false;
							}
						}
					}
					$book_status='full';
					if($can_book){
						$book_status = 'available';
					}
					$t['book_status']=$book_status;
				}
			}
		}
		reset($data);
		return $data;
	}

	public function cancel_order_web($inter_id, $order, $pms_set = array()){
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
		$this->serv_api->setAuthToen(json_decode($pms_set['pms_auth'], true));
		$res = $this->serv_api->cancelOrder($order['orderid'], $order['web_orderid'], '取消订单');
		if($res['ResultCode'] == 0){
			return array(                        //取消成功，直接这样return，接下来的程序会继续处理
			                                     's'      => 1,
			                                     'errmsg' => '取消成功....'
			);
		}

		return array(
			's'      => 0,
			'errmsg' => '取消失败,' . (isset($res['Message']) ? $res['Message'] : ''),
		);
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
		$web_order = $this->get_web_order($order['orderid'], $web_orderid, $pms_set);

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

		$this->serv_api->setAuthToen(json_decode($pms_set['pms_auth'], true));
		$res = $this->serv_api->payOrder($order['orderid'], $web_orderid, $trans_no, $order['price'] * 100);
		if($res['ResultCode'] == 0){
			$web_paid = 1;
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

	public function update_web_order($inter_id, $order, $pms_set){
		$web_order = $this->get_web_order($order['orderid'], $order['web_orderid'], $pms_set);
		$status = -1;
		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');
			$this->load->model('hotel/Order_model');
			$ensure_check = 0;
			$status = $status_arr[$web_order['Status']];

			if($order ['status'] == 4 && $status == 5){
				$status = 4;
			}

			if($status != $order ['status'] && $status !== false){
				$this->load->model('hotel/Order_model');
				$this->change_order_status($inter_id, $order['orderid'], $status);
				$this->Order_model->handle_order($inter_id, $order ['orderid'], $status, $order ['openid']);
			}

		}
		return $status;
	}

	private function change_order_status($inter_id, $orderid, $status){
		$this->db->where(array(
			                 'orderid'  => $orderid,
			                 'inter_id' => $inter_id
		                 ));
		$this->db->update('hotel_orders', array(
			'status' => (int)$status
		));
	}

	function pms_enum($type = 'status'){
		switch($type){
			case 'status':
				//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
				/*1	订单已经确认
2	订单已经取消
3	已入住
4	已退房
5	订单删除
*/
				return array(
					1 => 1,
					2 => 5,
					3 => 2,
					4 => 3,
					5 => 6
				);
				break;
		}
		return array();
	}

	/**
	 * 获取订单
	 * @param $orderid
	 * @param $web_orderid
	 * @param $pms_set
	 * @return array
	 */
	function get_web_order($orderid, $web_orderid, $pms_set){

		$this->serv_api->setAuthToen(json_decode($pms_set['pms_auth'], true));
		$result = $this->serv_api->getOrder($orderid, $web_orderid);
		if($result['ResultCode'] == 0){
			return $result;
		} else{
			return array();
		}
	}


	function order_reserve($order, $pms_set, $params){
		$this->serv_api->setAuthToen(json_decode($pms_set['pms_auth'], true));

		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];

		$allprice = explode(',', $order['first_detail']['allprice']);

		$startdate = date('Y-m-d', strtotime($order['startdate']));
		$enddate = date('Y-m-d', strtotime($order['enddate']));

		if(empty($room_codes['room']['webser_id'])){
			return array(
				'result' => false,
			);
		}

		/*if(!isset($room_codes['code']['extra_info']['pms_code'])){
			print_r($room_codes);
			return array(
				'result' => FALSE,
				'errmsg' => '不存在价格代码',
			);
		}*/

		//检查房型库存是否足够
		$check_res = $this->serv_api->checkInventoryByRoom($pms_set['hotel_web_id'], $room_codes['room']['webser_id'], $startdate, $enddate, $order['roomnums']);
		if($check_res['ResultCode'] != 0){
			return array(
				'result' => false,
				'errmsg' => $check_res['Message'],
			);
		}

		$remark = '';
		if(!empty ($params ['trans_no'])){
			$remark = '系统备注：此订单为微信端网上支付订单，客人已支付房费' . $order ['price'] . '元。请客人出示手机核实微信支付记录。';
		}

		if($order['coupon_favour'] > 0){
			$$remark .= '使用优惠券:' . $order['coupon_favour'] . '元';
		}

		$daily_info = array();
		//入住时间
		$btime = strtotime($order['startdate']);
		//停留天数
		$stay_day = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
		//价格分组数量
		$price_count = count($allprice);
		$stay_day = min($price_count, $stay_day);
		for($i = 0; $i < $stay_day; $i++){
			$daily_info[] = array(
				'Price' => bcmul($allprice[$i], 100, 0),
				'Day'   => date('Y-m-d', $btime + (86400 * $i)),
			);
		}

		//订单数据
		$curl_data = array(
			'OrderId'            => $order['orderid'],
			'HotelId'            => $pms_set['hotel_web_id'],
			'RoomTypeId'         => $room_codes['room']['webser_id'],
			'CheckIn'            => $startdate,
			'CheckOut'           => $enddate,
			'EarliestArriveTime' => $startdate . ' 08:00:00',
			'LatestArriveTime'   => $startdate . ' 22:00:00',
			'RoomNum'            => $order['roomnums'],
			'TotalPrice'         => $order['price'] * 100,
			'ContactName'        => $order['name'],
			'PaymentType'        => $order['paytype'] == 'daofu' ? 2 : 1,
			'ContactTel'         => $order['tel'],
			'DailyInfos'         => array('DailyInfo' => $daily_info),
			'OrderGuests'        => array(
				'OrderGuest' => array(
					'Name'    => $order['name'],
					'RoomPos' => 1,
				)
			),

		);
		if($remark != ''){
			$curl_data['Comment'] = $remark;
		}

		$res = $this->serv_api->createOrder($curl_data);
		if($res['ResultCode'] == 0){
			return array(
				'result' => true,
				'oid'    => $res['OrderId'],
			);
		}
		return array(
			'result' => false,
			'errmsg' => $res['Message'],
		);

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

		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
		if(empty($room_codes['room']['webser_id'])){
			return array(
				's' => 1,
			);
		}

		$res = $this->order_reserve($order, $pms_set, $params);


		if(!$res['result']){
			$this->change_order_status($inter_id, $orderid, 10);
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
			/*$child_list = $this->readDB()->where(array(
				                               'orderid'  => $order ['orderid'],
				                               'inter_id' => $order ['inter_id']
			                               ))->from('hotel_order_items')->select('id')->get()->result_array();
			$child_count = count($child_list);
			$child_oid = explode(',', $res['child_oid']);
			for($i = 0; $i < $child_count; $i++){
				if(isset($child_oid[$i])){
					$this->db->where(array('id' => (int)$child_list[$i]['id']))->update('hotel_order_items', array('webs_orderid' => $child_oid[$i]));
				}
			}*/
			if($order ['status'] != 9){
				$this->change_order_status($inter_id, $orderid, 1);

				$this->db->where(array(
					                 'orderid'  => $order ['orderid'],
					                 'inter_id' => $order ['inter_id']
				                 ))->update('hotel_order_items', array(
					'istatus' => 1
				));

				$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
			}

			if(!empty ($params ['trans_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
				$this->add_web_bill($web_orderid, $order, $pms_set, $params ['trans_no']);
			}

			return array('s' => 1);
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


class KezhanApi{
	private $username;
	private $password;
	private $url;

	public function __construct(){

	}

	public function setAuthToen($pms_auth){
		if(isset($pms_auth['user'])){
			$this->username = $pms_auth['user'];
		}
		if(isset($pms_auth['pwd'])){
			$this->password = $pms_auth['pwd'];
		}
		if(isset($pms_auth['url'])){
			$this->url = $pms_auth['url'];
		}
	}

	public function getPostAuth(){
		if($this->username && $this->password){
			return array(
				'AuthenticationToken' => array(
					'Username' => $this->username,
					'Password' => $this->password,
				),
			);
		}
		return array();
	}

	/**
	 * 查询/同步库存
	 * @param string $hotel_id     酒店ID
	 * @param string $room_type_id 房型ID
	 * @param mixed  $checkin      入住日期
	 * @param mixed  $checkout     离店日期
	 * @return array
	 */
	public function getInventoryByRoom($hotel_id, $room_type_id, $checkin, $checkout){
		$data = array(
			'HotelId'    => $hotel_id,
			'RoomTypeId' => $room_type_id,
			'CheckIn'    => $checkin,
			'CheckOut'   => $checkout,
		);
		$data = array_merge($data, $this->getPostAuth());

		$curl_array = array(
			'StockRQ' => $data
		);

		return $this->apiCall($curl_array);
	}

	public function getInventoryByHotel($hotel_id, $checkin, $checkout){
		$data = array(
			'HotelId'  => $hotel_id,
			'CheckIn'  => $checkin,
			'CheckOut' => $checkout,
		);
		$data = array_merge($data, $this->getPostAuth());

		$curl_array = array(
			'StockListRQ' => $data
		);

		return $this->apiCall($curl_array);
	}

	/**
	 * 检查库存
	 * @param string $hotel_id     酒店ID
	 * @param string $room_type_id 房型ID
	 * @param mixed  $checkin      入住日期
	 * @param mixed  $checkout     离店日期
	 * @param int    $room_num     房间数
	 * @return array
	 */
	public function checkInventoryByRoom($hotel_id, $room_type_id, $checkin, $checkout, $room_num = 1){
		$data = array(
			'HotelId'    => $hotel_id,
			'RoomTypeId' => $room_type_id,
			'CheckIn'    => $checkin,
			'CheckOut'   => $checkout,
			'RoomNum'    => (int)$room_num,
		);
		$data = array_merge($data, $this->getPostAuth());

		$curl_array = array(
			'ValidateRQ' => $data
		);

		return $this->apiCall($curl_array);

	}

	/**
	 * 预订
	 * @param $params
	 *          array(
	 *          OrderId=>订单ID
	 *          HotelId=>酒店ID
	 *          RoomTypeId=>房型ID
	 *          CheckIn=>入住日期
	 *          CheckOut=>离店日期
	 *          EarliestArriveTime=>最早到店时间
	 *          LatestArriveTime=>最迟到店时间
	 *          RoomNum=>预订房数
	 *          TotalPrice=>总价格（单位分）
	 *          ContactName=>联系人姓名
	 *          PaymentType=>预订类型（1：预付，2：现付）
	 *          ContactTel=>联系电话
	 *          DailyInfos=>array(  每日价格
	 *          DailyInfo=>array(
	 *          array(
	 *          Day=>日期
	 *          Price=>价格（单位：分）
	 *          )
	 *          )
	 *          )
	 *          OrderGuests=>array(
	 *          OrderGuest=>array(
	 *          Name=>姓名
	 *          RoomPos=>房间序号
	 *          )
	 *          )
	 *          Comment=>备注
	 *
	 * )
	 * @return array
	 */
	public function createOrder($params){
		$params = array_merge($params, $this->getPostAuth());

		$curl_array = array(
			'BookRQ' => $params
		);
		return $this->apiCall($curl_array);
	}

	/**
	 * 获取樟
	 * @param string $zl_orderid 直连订单ID
	 * @param string $orderid    PMS上订单ID
	 * @return array
	 */
	public function getOrder($zl_orderid, $orderid = ''){
		$data = array(
			'ZlOrderId' => $zl_orderid,
			'OrderId'   => $orderid
		);
		$data = array_merge($data, $this->getPostAuth());
		$curl_array = array(
			'QueryStatusRQ' => $data,
		);
		return $this->apiCall($curl_array);
	}

	/**
	 * @param string $zl_orderid  直连订单ID
	 * @param string $orderid     PMS上订单ID
	 * @param string $reson       取消原因
	 * @param bool   $hard_cancel 是否强制取消
	 * @return array
	 */
	public function cancelOrder($zl_orderid, $orderid = '', $reson = '', $hard_cancel = false){
		$data = array(
			'ZlOrderId'  => $zl_orderid,
			'OrderId'    => $orderid,
			'Reason'     => $reson,
			'HardCancel' => $hard_cancel,
		);
		$data = array_merge($data, $this->getPostAuth());
		$curl_array = array(
			'CancelRQ' => $data,
		);
		return $this->apiCall($curl_array);

	}

	/**
	 * 支付订单
	 * @param string $zl_orderid 直连订单ID
	 * @param string $orderid    订单ID
	 * @param string $trade_no   支付记录ID
	 * @param int    $payment    支付金额
	 * @return array
	 */
	public function payOrder($zl_orderid, $orderid, $trade_no, $payment){
		$data = array(
			'ZlOrderId'     => $zl_orderid,
			'OrderId'       => $orderid,
			'AlipayTradeNo' => $trade_no,
			'Payment'       => (int)$payment,
		);
		$data = array_merge($data, $this->getPostAuth());
		$curl_array = array(
			'PaySuccessRQ' => $data,
		);
		return $this->apiCall($curl_array);
	}


	private function apiCall($curl_array){
		$ci =& get_instance();
		$ci->load->helper('common');
		$curl_xml = array2xml($curl_array);
		$extra=['CURLOPT_HTTPHEADER'=>['Content-Type: application/xml']];
		$xml=doCurlPostRequest($this->url, $curl_xml,$extra,30);
		
//		$xml = curl_post_xml($this->url, $curl_xml);
		if($xml){
			$result = obj2array(simplexml_load_string($xml));
			return $result;
		}
		return array(
			'ResultCode' => -9999,
			'Message'    => '没有返回结果',
		);
	}
	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		
		$web_order = $this->get_web_order($order['orderid'], $order['web_orderid'], $pms_set);
		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');
			$status = $status_arr[$web_order['Status']];
		}
		if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
			return true;
		}else{
			return false;
		}
	}
}