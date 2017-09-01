<?php
class Zhuzhe_webservice implements IPMS {
	protected $CI;
	const TOKEN='ejia365_82250_20151023_web:admin';
	function __construct($params) {
		$this->CI = & get_instance ();
		$this->pms_set = $params ['pms_set'];
	}
	public function get_orders($inter_id, $status, $offset, $limit) {
	}
	public function get_hotels($inter_id, $status, $offset, $limit) {
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		
		// ///**酒店数据实时同步 开始 备注，酒店ID一般不会变**/
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$hotelretdata = $this->CI->pms->qfcurl ( $this->CI->pms->path ( '/hotel/' . $this->pms_set ['hotel_web_id'] . '/' . date ( 'Y-m-d', strtotime ( $condit ['startdate'] ) ) . '/' . date ( 'Y-m-d', strtotime ( $condit ['enddate'] ) ) ) );
		
		$days = round ( (strtotime ( $condit ['enddate'] ) - strtotime ( $condit ['startdate'] )) / 3600 / 24 );
		
		preg_match_all ( "/\<houseTypeList\>(.*?)\<\/houseTypeList\>/", $hotelretdata, $hotelretdataarr );
		$allprice = array ();
		$qfrooms = array ();
		$this->CI->load->model ( 'hotel/Member_model' );
		$data ['levels'] = $this->CI->Member_model->get_member_levels ( $idents ['inter_id'] );
		
		if (isset ( $condit ['member_level'] )) {
			$member_level = $condit ['member_level'] ? $condit ['member_level'] : 0;
		} else {
			$member_level = -1;
		}
		
		foreach ( $rooms as $nr ) {
			$needrooms [] = $nr ['webser_id'];
			$web_rooms [$nr ['webser_id']] = $nr;
		}
		
		if (isset ( $hotelretdataarr [0] )) {
			$hotelretxml = $hotelretdataarr [0] [0];
			$xmlobj = simplexml_load_string ( $hotelretxml );
			foreach ( $xmlobj->houseType as $key => $value ) {
				$houseTypeId = intval ( $value->houseTypeId );
				// if (in_array($houseTypeId, $needrooms)) {
				$nums = intval ( $value->available );
				$price = $value->price;
				$everyDayPrice = $value->everyDayPrice;
				// print_r($rooms);
				// print_r($houseTypeId);die();
				$mins = array ();
				if ($everyDayPrice) {
					$dayallprice = explode ( ',', $everyDayPrice );
					foreach ( $dayallprice as $vpr ) {
						
						$vprarr = explode ( '@', $vpr );
						$daylitprice = explode ( '#', $vprarr [0] );
						
						$least_num = $daylitprice ['2'];
						$book_status = 'available';
						if ($least_num == 0) {
							$book_status = 'full';
						}
						$allprice_item = '';
						$total_item = 0;
						$total_price = 0;
						$lowest = $daylitprice ['0'];
						foreach ( $vprarr as $date_v ) {
							$daylitprice1 = explode ( '#', $date_v );
							$date_detail [$daylitprice1 ['1']] = array (
									'price' => $daylitprice1 ['0'],
									'nums' => $daylitprice1 ['2'] 
							);
							if ($daylitprice1 ['2'] < $least_num) {
								$least_num = $daylitprice1 ['2'];
							}
							// if ($daylitprice1['0']<$lowest) {
							// $lowest = $daylitprice1['0'];
							// }
							$allprice_item = $allprice_item . "," . $daylitprice1 ['0'];
							$total_item += $daylitprice1 ['0'];
							
							$total_price += $daylitprice1 ['0'];
							
							unset ( $daylitprice1 );
						}
						$allprice_item = substr ( $allprice_item, 1 );
						$avg_price = $total_item / $days;
						if ($daylitprice ['3'] == $member_level && ! empty ( $data ['levels'] [$member_level] )) {
							$allprice [$daylitprice [3]] = array (
									'price_name' => $data ['levels'] [$daylitprice ['3']],
									'price' => $daylitprice ['0'],
									'day' => $daylitprice ['1'],
									'num' => $daylitprice ['2'],
									'type' => $daylitprice ['3'] 
							);
							$allprice [$daylitprice [3]] ['price_type'] = 'pms';
							
							$allprice [$daylitprice [3]] ['extra_info'] = array (
									'type' => $daylitprice ['3'],
									'pms_code' => $data ['levels'] [$daylitprice ['3']] 
							);
							$allprice [$daylitprice [3]] ['price_code'] = $daylitprice ['3'];
							$allprice [$daylitprice [3]] ['des'] = '';
							$allprice [$daylitprice [3]] ['sort'] = 0;
							$allprice [$daylitprice [3]] ['disp_type'] = 'only_show';
							$allprice [$daylitprice [3]] ['related_code'] = 0;
							$allprice [$daylitprice [3]] ['related_des'] = '';
							$allprice [$daylitprice [3]] ['related_cal_way'] = '';
							$allprice [$daylitprice [3]] ['related_cal_value'] = 0;
							
							$allprice [$daylitprice [3]] ['condition'] = '';
							$allprice [$daylitprice [3]] ['date_detail'] = $date_detail;
							$allprice [$daylitprice [3]] ['price_resource'] = 'webservice';
							if($least_num>0)$least_num=1;
							$allprice [$daylitprice [3]] ['least_num'] = $least_num;
							
							$allprice [$daylitprice [3]] ['book_status'] = $book_status;
							$allprice [$daylitprice [3]] ['allprice'] = $allprice_item;
							$allprice [$daylitprice [3]] ['total'] = $total_item;
							$allprice [$daylitprice [3]] ['total_price'] = $total_price;
							$allprice [$daylitprice [3]] ['avg_price'] = $avg_price;
							$mins [] = $avg_price;
						}
						if (! empty ( $data ['levels'] [$daylitprice ['3']] )) {
							$show_info [$daylitprice [3]] = array (
									'price_name' => $data ['levels'] [$daylitprice ['3']],
									'price' => $daylitprice ['0'],
									'day' => $daylitprice ['1'],
									'num' => $daylitprice ['2'],
									'type' => $daylitprice ['3'] 
							);
							$show_info [$daylitprice [3]] ['price_type'] = 'pms';
							$show_info [$daylitprice [3]] ['extra_info'] = array (
									'type' => $daylitprice ['3'],
									'pms_code' => $data ['levels'] [$daylitprice ['3']] 
							);
							$show_info [$daylitprice [3]] ['price_code'] = $daylitprice ['3'];
							$show_info [$daylitprice [3]] ['des'] = '';
							$show_info [$daylitprice [3]] ['sort'] = 0;
							$show_info [$daylitprice [3]] ['disp_type'] = 'only_show';
							$show_info [$daylitprice [3]] ['related_code'] = 0;
							$show_info [$daylitprice [3]] ['related_des'] = '';
							$show_info [$daylitprice [3]] ['related_cal_way'] = '';
							$show_info [$daylitprice [3]] ['related_cal_value'] = 0;
							$show_info [$daylitprice [3]] ['condition'] = '';
							$show_info [$daylitprice [3]] ['date_detail'] = $date_detail;
							$show_info [$daylitprice [3]] ['price_resource'] = 'webservice';
							$show_info [$daylitprice [3]] ['least_num'] = $least_num;
							$show_info [$daylitprice [3]] ['book_status'] = $book_status;
							$show_info [$daylitprice [3]] ['allprice'] = $allprice_item;
							$show_info [$daylitprice [3]] ['total'] = $total_item;
							$show_info [$daylitprice [3]] ['total_price'] = $total_price;
							$show_info [$daylitprice [3]] ['avg_price'] = $avg_price;
						}
						unset ( $daylitprice );
					}
				}
				
				$name = $value->houseTypeName;
				
				/* if ($houseTypeId>0) {
				 $rooma = $this->CI->pms->get_hotel_rooms( $idents , $houseTypeId );
				 if (!$rooma) {
				 $inter_id = isset($idents['inter_id'])?$idents['inter_id']:"";
				 $hotel_id = isset($idents['hotel_id'])?$idents['hotel_id']:"";
				 $in_rooms['inter_id'] = $inter_id;
				 $in_rooms['hotel_id'] = $hotel_id;
				 $in_rooms['name'] = "$name";//有一次不加双引号竟然插入报错。
				 $in_rooms['price'] = "$price";
				 $in_rooms['oprice'] = "$price";
				 $in_rooms['nums'] = "$nums";
				 $in_rooms['status'] = "1";
				 $in_rooms['webser_id'] = "$houseTypeId";
				 
				 $this->CI->pms->in_hotel_rooms( $in_rooms );
				 $rooma = $this->CI->pms->get_hotel_rooms( $idents , $houseTypeId );
				 }
				 } */
				if (in_array ( $houseTypeId, $needrooms )) {
					$qfrooms [$web_rooms [$houseTypeId] ['room_id']] ['room_info'] = $web_rooms [$houseTypeId];
					$qfrooms [$web_rooms [$houseTypeId] ['room_id']] ['state_info'] = $allprice;
					$qfrooms [$web_rooms [$houseTypeId] ['room_id']] ['show_info'] = empty ( $show_info ) ? array () : $show_info;
					$qfrooms [$web_rooms [$houseTypeId] ['room_id']] ['lowest'] = empty($mins)?'':min ( $mins );
					$qfrooms [$web_rooms [$houseTypeId] ['room_id']] ['highest'] = empty($mins)?'':max ( $mins );
				} /* else{
				   $inter_id = isset($idents['inter_id'])?$idents['inter_id']:"";
				   $hotel_id = isset($idents['hotel_id'])?$idents['hotel_id']:"";
				   $in_rooms['inter_id'] = $inter_id;
				   $in_rooms['hotel_id'] = $hotel_id;
				   $in_rooms['name'] = "$name";
				   $in_rooms['price'] = "$price";
				   $in_rooms['oprice'] = "$price";
				   $in_rooms['nums'] = "$nums";
				   $in_rooms['status'] = "1";
				   $in_rooms['webser_id'] = "$houseTypeId";
				   $this->CI->pms->in_hotel_rooms( $in_rooms );
				   $rooma = $this->CI->pms->get_hotel_rooms( $idents , $houseTypeId );
				   
				   $qfrooms[$rooma[0]['room_id']]['room_info'] = $rooma;
				   $qfrooms[$rooma[0]['room_id']]['state_info'] = $allprice;
				   $qfrooms[$rooma[0]['room_id']]['show_info'] = $show_info;
				   $qfrooms[$rooma[0]['room_id']]['lowest'] = $lowest;
				   } */
				// }
			}
		}
		
		return $qfrooms;
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}
	function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$s = $this->CI->pms->cancel_order_web ( $inter_id, $order );
		
		if ($s ['Result'] == 1) { // 判断取消是否成功
			return array ( // 成功返回
					's' => 1,
					'errmsg' => '取消成功' 
			);
		}
		return array ( // 失败返回
				's' => 0,
				'errmsg' => '取消失败' 
		);
	}
	function update_web_order($inter_id, $order) {
	}
	public function get_new_hotel($params = array()) {
		echo '';
	}
	public function check_openid_member($inter_id, $openid, $paras) {
	}


	//拦截跳转
	public function headerUrlCenter(){
		redirect('member/perfectinfo');
		exit;
	}
	
	/**
	 * 根据openid获取会员
	 * @param strint $openid
	 * @return unknown|boolean
	 */
	public function getMemberByOpenId($params) {
		try {
			$openid = $params [0];
			$memberObject = $this->getMemberModel ()->getMemberDetailById ( $openid );
			if ($memberObject && isset ( $memberObject->mem_id )) {
				if ((isset ( $params [1] ) && $params [1] == 'update' && $memberObject->is_login == 1) || ($memberObject->is_login == 1 && (time () - $memberObject->last_login_time > 7200))) {
					$result = $this->getPmsMember ( $openid, $memberObject->telephone, $memberObject->password );
					if ($result) {
						$memberObject = $this->getMemberModel ()->getMemberDetailById ( $openid );
					}
				}
				$memberObject->mebtype = $this->yibomemberlevel ( $memberObject->level, false );
				return $memberObject;
			}
			return false;
		} catch ( Exception $e ) {
			$error = new stdClass ();
			$error->error = true;
			$error->message = $e->getMessage ();
			$error->code = $e->getCode ();
			$error->file = $e->getFile ();
			$error->line = $e->getLine ();
			return $error;
		}
		
		return false;
	}
	public function getMemberModel() {
		if (! isset ( $this->_memberModel )) {
			$this->CI->load->model ( 'member/member' );
			$this->_memberModel = $this->CI->member;
		}
		
		return $this->_memberModel;
	}
	protected function yibomemberlevel($level, $positive = true) {
		static $member = array (
				'0' => 0, // 网站普通会员
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
		);
		return 1;
		if ($positive) {
			if (isset ( $member [$level] )) {
				return $member [$level];
			} else {
				return '-1';
			}
		} else {
			$reverse_member = array_flip ( $member );
			
			if (isset ( $reverse_member [$level] )) {
				return $reverse_member [$level];
			} else {
				return '-1';
			}
		}
	}
	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberInfoByOpenId($params) {
		$openid = $params [0];
		try {
			$memberinfoObject = $this->getMemberModel ()->getMemberInfoById ( $openid );
			return $memberinfoObject;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	// 验证短信是否合法
	function checkSendSms($params) {
		return true;
	}
	
	/**
	 * 更新激活状态
	 * @param string $openid
	 * @param int $active
	 * @return unknown|boolean
	 */
	public function updateStatus($params) {
		return true;
		$openid = $params [0];
		$active = $params [1];
		
		$data = array (
				'openid' => $openid,
				'is_active' => $active,
				'is_login' => 1 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	/**
	 * 添加会员详细资料
	 * @param string $openid
	 * @param array $data
	 * @return unknown|boolean
	 */
	public function addMemberInfo($params) {
		$data = $params [1];
		$data ['openid'] = $params [0];
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	public function checklogin($params) {
		$result = $this->getPmsMember ( $params [0], $params [1], $params [2], $params [3] );
		return $result;
	}
	protected function getPmsMember($openid, $telephone, $password, $inter_id = '') {
		$url = "http://webapi.zhuzher.com/member/login/$telephone/$password";
		
		$result = $this->post_curl ( $url, '' );
		$result = simplexml_load_string ( $result );
		if (isset ( $result->code ) && $result->code == 0) {
			$data = array (
					'openid' => $openid,
					'name' => $result->user->uname,
					'telephone' => $result->user->tel,
					'email' => $result->user->email,
					'identity_card' => '0',
					'membership_number' => $result->user->id,
					'dob' => $result->user->birthday,
					'password' => $password,
					'inter_id' => $inter_id 
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->user->score,
					'is_login' => 1,
					'level' => $result->user->level,
					'last_login_time' => time () 
			);
			$this->getMemberModel ()->updateMemberByOpenId ( $updateParams );
			$this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return true;
		} else {
			$this->getMemberModel ()->updateMemberByOpenId ( array (
					'openid' => $openid,
					'is_login' => 0 
			) );
			return false;
		}
	}
	public function registerMember($params) {
		// print_r($params);exit;
		$openid = $params [0];
		$data = $params [1];
		$data ['inter_id'] = $params [2];
		
		$url = "http://webapi.zhuzher.com/member/reg";
		$yibodata = array (
				'hotelId' => 802256,
				'uname' => $data ['name'],
				'tel' => $data ['telephone'],
				'email' => $data ['email'],
				'cardType' => '身份证',
				'cardNum' => $data ['identity_card'],
				'nickName' => $data ['name'],
				'usex' => $data ['sex'],
				'password' => $data ['password'] 
		);
		
		$result = $this->post_curl ( $url, $yibodata );
		$result = simplexml_load_string ( $result );
		if (isset ( $result->code ) && $result->code == 0) {
			$data ['membership_number'] = $result->user->uid;
			
			$updateParams = array (
					'openid' => $openid,
					'is_login' => 1,
					'is_active' => 1,
					'last_login_time' => time () 
			);
			$this->getMemberModel ()->updateMemberByOpenId ( $updateParams );
			$this->addMemberInfo ( array (
					$openid,
					$data 
			) );
			
			return array (
					'code' => 0,
					'errmsg' => $result->msg 
			);
		} else {
			return array (
					'code' => 1,
					'errmsg' => $result->msg 
			);
		}
		
		return false;
	}
	function post_curl($url, $data = '') {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
		curl_setopt ( $ch, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
		curl_setopt ( $ch, CURLOPT_USERPWD, self::TOKEN );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
		if (is_array ( $data )) {
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( $data ) );
		} elseif ($data) {
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		}
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 5 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
		$data = curl_exec ( $ch );
		if (curl_errno ( $ch )) {
			echo 'Errno' . curl_error ( $ch ); // 捕抓异常
		}
		curl_close ( $ch );
		return $data;
	}
	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberDetailByOpenId($params)
	{
		$openid = $params[0];
		try {
			$memberInfoObject = $this->getMemberModel()->getMemberDetailById($openid);
			return $memberInfoObject;
		} catch (Exception $e) {
			$error = new stdClass();
			$error->error = true;
			$error->message = $e->getMessage();
			$error->code = $e->getCode();
			$error->file = $e->getFile();
			$error->line = $e->getLine();
			return $error;
		}
	
		return false;
	}

	public function getMemberLevel($params)
	{
		$member = $params[0];
		try {
			$this->getMemberModel()->getMemberLevel($member);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return $this;
	}
}