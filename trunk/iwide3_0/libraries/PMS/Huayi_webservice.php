
<?php
class Huayi_webservice implements IPMS {
	protected $CI;
	protected $_memberModel;
	function __construct($params) {
		$this->CI = & get_instance ();
		$this->pms_set = $params ['pms_set'];
	}
	public function get_orders($inter_id, $status, $offset, $limit) {
		return "I'm function get_orders in Test1_service";
	}
	public function get_hotels($inter_id, $status, $offset, $limit) {
		return "I'm function get_hotels in Test1_service";
	}
	public function get_member() {
		return "I'm function get_member in test1_service";
	}
	public function get_new_hotel($params = array()) {
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$this->CI->load->model ( 'hotel/pms/Huayi_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : '';
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function add_web_bill($order,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Huayi_hotel_model', 'pms' );
		$room_codes = json_decode ( $order ['room_codes'], TRUE );
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $room_codes, $this->pms_set,$trans_no);
	}
	public function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Huayi_hotel_model', 'pms' );
		return $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
	}
	public function update_web_order($inter_id, $order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Huayi_hotel_model', 'pms' );
		$params ['pms_set'] = $this->pms_set;
		return $this->CI->pms->update_web_order ( $inter_id, $order, $params );
	}
	public function check_order_canpay($order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Huayi_hotel_model', 'pms' );
		$params ['pms_set'] = $this->pms_set;
		return $this->CI->pms->check_order_canpay ($order, $params );
	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$update = empty ( $paras ['update'] ) ? '' : 'update';
		$member = $this->getMemberByOpenId ( array (
				$openid,
				$update 
		) );
		if (! empty ( $member ) && ! empty ( $member->mem_id ) && ! empty ( $member->membership_number )) {
			$member->level = $member->mebtype;
			$member->mem_card_no = $member->membership_number;
			return $member;
		}
		return false;
	}
	public function checklogin($params) {
		$result = $this->getPmsMember ( $params [0], $params [1], $params [2], $params [3] );
		return $result;
	}
	public function registerMember($params) {
		$openid = $params [0];
		$data = $params [1];
		$data ['inter_id'] = $params [2];
		
		$url = "http://h5.100inn.cc/MemberService.asmx/RegisterMember";
		$yibodata = array (
				'name' => $data ['name'],
				'mobileNo' => $data ['telephone'],
				'email' => $data ['email'],
				'idNo' => $data ['identity_card'],
				'Password' => $data ['password'],
				'Code' => $data ['sms'] 
		);
		
		$result = $this->curl_post ( $url, $yibodata );
		
		if (isset ( $result->Result ) && $result->Result == 1) {
			$data ['membership_number'] = $result->Data->MebNo;
			
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
					'errmsg' => $result->Message 
			);
		} else {
			return array (
					'code' => 1,
					'errmsg' => $result->Message 
			);
		}
		
		return false;
	}
	public function modifiedMember($params) {
		return $params [1];
	}
	public function sendSms($params){
		$phone = $params[0]['telephone'];
		$code = $params[1];
		$this->CI->load->model('member/sms','sms');
		$this->CI->sms->setLog();
		$result = $this->CI->sms->Sendsms($phone,array($code),60225);
		if(!(int)$result['code']){
			return '发送成功';
		}else{
			return '发送失败';
		}
	}
	
	// 获取积分记录
	public function getBonusRecords($params) {
		$openid = $params [0];
		
		$this->CI->load->model ( 'member/iconsume' );
		$memberObject = $this->getMemberByOpenId ( array (
				$openid 
		) );
		$data ['bonus'] = $this->CI->iconsume->getBonusByMember ( $memberObject->mem_id, 'all' );
		$data ['add_bonus'] = $this->CI->iconsume->getBonusByMember ( $memberObject->mem_id, 'charge' );
		$data ['reduce_bonus'] = $this->CI->iconsume->getBonusByMember ( $memberObject->mem_id, 'reduce' );
		
		if (! empty ( $data ['bonus'] ))
			rsort ( $data ['bonus'] );
		if (! empty ( $data ['add_bonus'] ))
			rsort ( $data ['add_bonus'] );
		if (! empty ( $data ['reduce_bonus'] ))
			rsort ( $data ['reduce_bonus'] );
		
		return $data;
	}
	public function getBalanceRecords($params) {
		$openid = $params [0];
		
		$this->CI->load->model ( 'member/iconsume' );
		$memberObject = $this->getMemberByOpenId ( array (
				$openid 
		) );
		$data ['balances'] = $this->CI->iconsume->getBalancesByMember ( $memberObject->mem_id, 'all' );
		$data ['add_balances'] = $this->CI->iconsume->getBalancesByMember ( $memberObject->mem_id, 'charge' );
		$data ['reduce_balances'] = $this->CI->iconsume->getBalancesByMember ( $memberObject->mem_id, 'reduce' );
		
		if (! empty ( $data ['balances'] ))
			rsort ( $data ['balances'] );
		if (! empty ( $data ['add_balances'] ))
			rsort ( $data ['add_balances'] );
		if (! empty ( $data ['reduce_balances'] ))
			rsort ( $data ['reduce_balances'] );
		
		$r_data ['data_title'] = array (
				'全部记录',
				'充值记录',
				'消费记录' 
		);
		$r_data ['data_record'] = array (
				$data ['balances'],
				$data ['add_balances'],
				$data ['reduce_balances'] 
		);
		
		return $r_data;
	}
	
	// 找回密码发送短信
	public function sendSetPassword($params) {
		$telephone = $params [0];
		$rand = $params [1];
		
		$type = 1;
		
		$url = 'http://sendsms.100inn.cc/HotelService.asmx/SendMessage';
		$data ['moblie'] = $telephone;
		$data ['type'] = $type;
		$data ['content'] = $rand;
		$data ['mediumType'] = 1;
		
		$result = $this->curl_post ( $url, $data );
		$message = isset ( $result->Message ) ? $result->Message : '短信发送失败';
		// return $result;
		return $message;
	}
	protected function getPmsMember($openid, $telephone, $password, $inter_id = '') {
		$url = "http://h5.100inn.cc/MemberService.asmx/MemberLogin";
		
		$data ['mobileNo'] = $telephone;
		$data ['password'] = $password;
		
		$result = $this->curl_post ( $url, $data );
		
		if (isset ( $result->Result ) && $result->Result == 1) {
			$data = array (
					'openid' => $openid,
					'name' => $result->Data->Name,
					'telephone' => $result->Data->MobileNo,
					'email' => $result->Data->Email,
					'identity_card' => $result->Data->IdNo,
					'membership_number' => $result->Data->MebNo,
					'password' => $password,
					'inter_id' => $inter_id 
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->Data->Score,
					'is_login' => 1,
					'level' => $this->yibomemberlevel ( $result->Data->MebType ),
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
	protected function yibomemberlevel($level, $positive = true) {
		static $member = array (
				'100231' => 0, // 网站普通会员
				'100880' => 1,
				'1480297' => 2,
				'1480303' => 3,
				'1480305' => 4,
				'1480275' => 5,
				'100198' => 6,
				'1480298' => 7,
				'100882' => 8,
				'100884' => 9,
				'100885' => 10,
				'100883' => 11 
		);
		
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
	protected function curl_post($url, $data) {
		$this->CI->load->helper ( 'common' );
		
		$data ['token'] = "100200";
		$data = http_build_query ( $data );
		$return = doCurlPostRequest ( $url, $data );
		return json_decode ( $return );
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
	public function getMemberById($params) {
		$memid = $params [0];
		try {
			$memberObject = $this->getMemberModel ()->getMemberById ( $memid, 'mem_id' );
			return $memberObject;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 获取会员列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberList($params) {
		$limit = $params [0];
		$offset = $params [1];
		
		$memberObjectList = $this->getMemberModel ()->getMemberList ( $limit, $offset );
		return $memberObjectList;
	}
	
	/**
	 * 根据openid删除会员
	 * @param string $openid
	 * @return boolean|number
	 */
	public function deleteMemberByOpenId($params) {
		$openid = $params [0];
		try {
			$result = $this->getMemberModel ()->deleteMemberByOpenId ( $openid );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	public function initMember($params) {
		$openid = $params [0];
		$data = $params [1];
		$inter_id = $params [2];
		$data ['is_active'] = 0;
		
		$result = $this->createMember ( $openid, $data, $inter_id );
		
		if ($result) {
			return $this->getMemberByOpenId ( $openid );
		} else {
			return false;
		}
	}
	
	/**
	 * 创建会员
	 * @param string $openid          微信OpenID
	 * @param string $code            卡券唯一编号
	 * @param int $growth             会员成长值
	 * @param int $balance            金额
	 * @param int $bonus              积分
	 * @param int $level              等级
	 * @param int $is_active          是否激活
	 * @param timestamp $begin_time   激活有效期
	 * @param timestamp $end_time     激活截止期
	 *
	 * @return bool
	 */
	// public function createMember($params)
	public function createMember($openid, $data, $inter_id) {
		// $data = $params[1];
		$data ['openid'] = $openid;
		$data ['inter_id'] = $inter_id;
		
		if (isset ( $data ['is_active'] ))
			$data ['is_active'] = intval ( $data ['is_active'] );
		
		try {
			$result = $this->getMemberModel ()->createMember ( $data );
			return $result;
		} catch ( Exception $e ) {
			return false;
		}
		
		return false;
	}
	public function updateMemberByOpenId($params) {
		try {
			$data = $params [0];
			if (! isset ( $data ['openid'] ))
				throw new Exception ( "openid不存在" );
			
			$result = $this->getMemberModel ()->updateMemberByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更加卡券的唯一code码
	 * @param string $openid
	 * @param string $code
	 *
	 * @return bool
	 */
	public function updateCode($params) {
		$data = array (
				'openid' => $params [0],
				'code' => $params [1] 
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
	 * 更新会员成长值
	 * @param string $openid
	 * @param int $growth
	 * @return unknown|boolean
	 */
	public function updateGrowth($params) {
		$data = array (
				'openid' => $params [0],
				'growth' => $params [1] 
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
	 * 增加成长值
	 * @param unknown $openid
	 * @param unknown $growth
	 * @return unknown|boolean
	 */
	public function addGrowth($params) {
		try {
			$data = array (
					'openid' => $params [0],
					'growth' => $params [1] 
			);
			$result = $this->getMemberModel ()->updateGrowth ( $data, true );
			return $result;
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
	
	/**
	 * 减少成长值
	 * @param unknown $openid
	 * @param unknown $growth
	 * @return unknown|boolean
	 */
	public function reduceGrowth($params) {
		try {
			$data = array (
					'openid' => $params [0],
					'growth' => $params [1] 
			);
			$result = $this->getMemberModel ()->updateGrowth ( $data, false );
			return $result;
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
	
	/**
	 * 更新会员储值金额
	 * @param string $openid
	 * @param int $balance
	 * @return unknown|boolean
	 */
	public function updateBalance($params) {
		$data = array (
				'openid' => $params [0],
				'balance' => $params [1] 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	public function addBalance($params) {
		try {
			$data = array (
					'openid' => $params [0],
					'balance' => $params [1] 
			);
			$note = $params [2];
			$order_id = $params [3];
			$inter_id = $params [4];
			$result = $this->getMemberModel ()->updateBalance ( $data, true, $note, $order_id, $inter_id );
			return $result;
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
	public function reduceBalance($params) {
		try {
			$data = array (
					'openid' => $params [0],
					'balance' => $params [1] 
			);
			$note = $params [2];
			$order_id = $params [3];
			$inter_id = $params [4];
			$result = $this->getMemberModel ()->updateBalance ( $data, false, $note, $order_id, $inter_id );
			return $result;
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
	
	/**
	 * 更新积分
	 * @param string $openid
	 * @param int $bonus
	 * @return unknown|boolean
	 */
	public function updateBonus($params) {
		$data = array (
				'openid' => $params [0],
				'bonus' => $params [1] 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	public function refund($params) {
		$openid = $params [0];
		$order_id = $params [1];
		$note = $params [2];
		$type = $params [3];
		$inter_id = $params [4];
		
		try {
			$result = $this->getMemberModel ( $inter_id )->refund ( $openid, $order_id, $note, $type, $inter_id );
			return $result;
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
	public function addBonusByRule($params) {
		$openid = $params [0];
		$category = $params [1];
		$num = $params [2];
		$note = $params [3];
		$order_id = $params [4];
		$member_level = $params [5];
		$inter_id = $params [6];
		$type = $params [7];
		
		try {
			$result = $this->getMemberModel ( $inter_id )->addBonusByRule ( $openid, $category, $num, $note, $order_id, $member_level, $inter_id, $type );
			return $result;
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
	public function addBonus($params) {
		$openid = $params [0];
		$bonus = $params [1];
		$note = $params [2];
		$order_id = $params [3];
		$inter_id = $params [4];
		
		try {
			$data = array (
					'openid' => $openid,
					'bonus' => $bonus 
			);
			$result = $this->getMemberModel ()->updateBonus ( $data, true, $note, $order_id, $inter_id );
			return $result;
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
	public function reduceBonus($params) {
		$openid = $params [0];
		$bonus = $params [1];
		$note = $params [2];
		$order_id = $params [3];
		$inter_id = $params [4];
		
		try {
			$data = array (
					'openid' => $openid,
					'bonus' => $bonus 
			);
			$result = $this->getMemberModel ()->updateBonus ( $data, false, $note, $order_id, $inter_id );
			return $result;
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
	
	/**
	 * 更新等级
	 * @param string $openid
	 * @param int $level
	 * @return unknown|boolean
	 */
	public function updateLevel($params) {
		$openid = $params [0];
		$level = $params [1];
		
		$data = array (
				'openid' => $openid,
				'level' => $level 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberByOpenId ( $data );
			return $result;
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
	
	/**
	 * 更新激活状态
	 * @param string $openid
	 * @param int $active
	 * @return unknown|boolean
	 */
	public function updateStatus($params) {
		$openid = $params [0];
		$active = $params [1];
		
		$data = array (
				'openid' => $openid,
				'is_active' => $active 
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
	 * 更新有效时间
	 * @param unknown $openid
	 * @param unknown $begin
	 * @param unknown $end
	 * @return unknown|boolean
	 */
	public function updateValidity($params) {
		$openid = $params [0];
		$begin = $params [1];
		$end = $params [2];
		
		$data = array (
				'openid' => $openid,
				'activate_begin_time' => $begin,
				'activate_end_time' => $end 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	// -----------------------------------------------------------------------------------------------------------------------------------------
	
	// public function upgradeLevel($openid)
	// {
	// try {
	// return $this->getMemberModel()->upgradeLevel($openid);
	// } catch (Exception $e) {
	// $error = new stdClass();
	// $error->error = true;
	// $error->message = $e->getMessage();
	// $error->code = $e->getCode();
	// $error->file = $e->getFile();
	// $error->line = $e->getLine();
	// return $error;
	// }
	
	// return false;
	// }
	public function getAllMemberLevels($params) {
		$inter_id = isset ( $params [0] ) ? ( int ) $params [0] : 0;
		// $inter_id = $params[0];
		try {
			return $this->getMemberModel ( $inter_id )->getAllMemberLevels ( $inter_id );
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
	public function getMemberLevel($params) {
		$member = $params [0];
		try {
			$this->getMemberModel ()->getMemberLevel ( $member );
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return $this;
	}
	
	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberDetailByOpenId($params) {
		$openid = $params [0];
		try {
			$memberInfoObject = $this->getMemberModel ()->getMemberDetailById ( $openid );
			return $memberInfoObject;
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
	public function getMemberDetailByMemId($params) {
		$memid = $params [0];
		try {
			$memberInfoObject = $this->getMemberModel ()->getMemberDetailById ( $memid, array (), 'mem_id' );
			return $memberInfoObject;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 获取所有会员详细信息列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberDetailList($params) {
		$limit = $params [0];
		$offset = $params [1];
		$where = $params [2];
		
		$memberObjectList = $this->getMemberModel ()->getMemberDetailList ( $limit, $offset, $where );
		return $memberObjectList;
	}
	public function getMemberDetailListNumber($limit = null, $offset = null, $where = null, $inter_id = '') {
		$memberObjectList = $this->getMemberModel ( $inter_id )->getMemberDetailList ( $limit, $offset, $where, array (
				array (
						'mem_id' 
				) 
		) );
		return count ( $memberObjectList );
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
	public function getMemberInfoByMemId($params) {
		$memid = $params [0];
		try {
			$memberinfoObject = $this->getMemberModel ()->getMemberInfoById ( $memid, 'mem_id' );
			return $memberinfoObject;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 获取会员附加信息列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberInfoList($params) {
		$limit = $params [0];
		$offset = $params [1];
		$memberInfoObjectList = $this->getMemberModel ()->getMemberInfoList ( $limit, $offset );
		return $memberInfoObjectList;
	}
	
	/**
	 * 根据OpenId删除会员附加资料
	 * @param string $openid
	 * @return unknown|boolean
	 */
	public function deleteMemberInfoByOpenId($params) {
		$openid = $params [0];
		try {
			$result = $this->getMemberModel ()->deleteMemberInfoByOpenId ( $openid );
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
	
	/**
	 * 更新会员卡编号
	 * @param string $openid
	 * @param string $card_number
	 * @return unknown|boolean
	 */
	public function updateMemberInfoCardNumber($params) {
		$openid = $params [0];
		$card_number = $params [1];
		
		$data = array (
				'openid' => $openid,
				'membership_number' => $card_number 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员名字
	 * @param string $openid
	 * @param string $name
	 * @return unknown|boolean
	 */
	public function updateMemberInfoName($params) {
		$openid = $params [0];
		$name = $params [1];
		
		$data = array (
				'openid' => $openid,
				'name' => $name 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员性别
	 * @param unknown $openid
	 * @param unknown $sex
	 * @return unknown|boolean
	 */
	public function updateMemberInfoSex($params) {
		$openid = $params [0];
		$sex = $params [1];
		
		$data = array (
				'openid' => $openid,
				'sex' => $sex 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员出生日期
	 * @param unknown $openid
	 * @param unknown $dob
	 * @return unknown|boolean
	 */
	public function updateMemberInfoDob($params) {
		$openid = $params [0];
		$dob = $params [1];
		
		$data = array (
				'openid' => $openid,
				'dob' => $dob 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员电话号码
	 * @param unknown $openid
	 * @param unknown $telephone
	 * @return unknown|boolean
	 */
	public function updateMemberInfoTelephone($params) {
		$openid = $params [0];
		$telephone = $params [1];
		
		$data = array (
				'openid' => $openid,
				'telephone' => $telephone 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员QQ
	 * @param unknown $openid
	 * @param unknown $qq
	 * @return unknown|boolean
	 */
	public function updateMemberInfoQQ($params) {
		$openid = $params [0];
		$qq = $params [1];
		
		$data = array (
				'openid' => $openid,
				'qq' => $qq 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员邮件
	 * @param unknown $openid
	 * @param unknown $email
	 * @return unknown|boolean
	 */
	public function updateMemberInfoEmail($params) {
		$openid = $params [0];
		$email = $params [1];
		
		$data = array (
				'openid' => $openid,
				'email' => $email 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	public function updateMemberInfoById($params) {
		$ma_id = $params [0];
		$data = $params [1];
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoById ( $ma_id, $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员身份证
	 * @param unknown $openid
	 * @param unknown $idcard
	 * @return unknown|boolean
	 */
	public function updateMemberInfoIdcard($params) {
		$openid = $params [0];
		$idcard = $params [1];
		
		$data = array (
				'openid' => $openid,
				'identity_card' => $idcard 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新会员地址
	 * @param string $openid
	 * @param string $address
	 * @return unknown|boolean
	 */
	public function updateMemberInfoAddress($params) {
		$openid = $params [0];
		$address = $params [1];
		
		$data = array (
				'openid' => $openid,
				'address' => $address 
		);
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return false;
	}
	
	/**
	 * 更新自定义字段
	 * @param string $openid
	 * @param string $custom
	 * @param number $type
	 * @return unknown|boolean
	 */
	public function updateMemberInfoCustom($params) {
		$data ['openid'] = $params [0];
		$data ['custom' . $params [2]] = $params [1];
		
		try {
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
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
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Huayi_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}
	
	/**更新密码
	 * @param unknown $tel
	 * @param unknown $code
	 * @param unknown $new_pwd
	 * @return number[]|string[]|mixed[]
	 */
	public function updatePassWordin($params) {
		$url = 'http://h5.100inn.cc/MemberService.asmx/UpdatePwd';
		$data ['mobileNo'] = $params [0];
		$data ['oldPwd'] = '';
		$data ['newPwd'] = $params [2];
		$s = $this->curl_post ( $url, $data );
		if ($s ['Result'] == 1) {
			$result ['s'] = 1;
			$result ['errmsg'] = '修改成功';
			$this->db->where ( array (
					'id' => $check ['id'] 
			) );
			$this->db->update ( 'web_record', array (
					'handled' => 1 
			) );
		} else if ($s ['Result'] == 0) {
			$result ['s'] = 0;
			$result ['errmsg'] = $s ['Message'];
		} else {
			$result ['s'] = 0;
			$result ['errmsg'] = '修改失败';
		}
		
		return $result;
	}
}