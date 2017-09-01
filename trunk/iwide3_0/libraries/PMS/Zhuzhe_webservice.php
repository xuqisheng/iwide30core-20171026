<?php
class Zhuzhe_webservice implements IPMS {
	protected $CI;
	protected $_memberModel;
	// const TOKEN = 'ejia365_82250_20151023_web:admin';
	function __construct($params) {
		$this->CI = & get_instance ();
		$this->pms_set = $params ['pms_set'];
	}
	public function get_orders($inter_id, $status, $offset, $limit) {
	}
	public function get_hotels($inter_id, $status, $offset, $limit) {
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : null;
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}
	
	public function add_web_bill($order,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $pms_auth, $trans_no);
	}
	
	function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$s = $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
		
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
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		return $this->CI->pms->update_web_order ( $inter_id, $order,$this->pms_set );
	}
	function check_order_canpay($order) {
		$this->CI->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		return $this->CI->pms->check_order_canpay ( $order,$this->pms_set );
	}
	public function get_new_hotel($params = array()) {
		echo '';
	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$this->CI->load->model ( 'hotel/Member_model' );
		return $this->CI->Member_model->check_openid_member ( $inter_id, $openid, $paras );
	}
	
	// 拦截跳转
	public function headerUrlCenter() {
		redirect ( 'member/perfectinfo' );
		exit ();
	}
	
	
	
	public function modifiedMember($params) {
		return $params [1];
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
				'1' => 1, //银卡
				'2' => 1, //银卡
				'3' => 2, //金卡
				'4'  =>3, //铂金卡
				'11'=> 0,
				'22'=> 0,
				'17'=> 2,
				'18'=> 1,
				'19'=> 3,
		);
		if ($positive) {
			if (isset ( $member [$level] )) {
				return $member [$level];
			} else {
				return '0';
			}
		} else {
			$reverse_member = array_flip ( $member );
			
			if (isset ( $reverse_member [$level] )) {
				return $reverse_member [$level];
			} else {
				return '0';
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
	
	//发送短信信息
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
		//var_dump($result);exit;
		return $result;
	}
	protected function getPmsMember($openid, $telephone, $password, $inter_id = '') {
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['apipath'] . "/member/login/$telephone/$password";
		
		$result = $this->post_curl ( $url, '', $pms_auth ['token'] );
		$result = simplexml_load_string ( $result );
		if (isset ( $result->code ) && $result->code == 0) {
			$data = array (
					'openid' => $openid,
					'name' => $result->user->uname,
					'telephone' => $result->user->tel,
					'email' => $result->user->email,
					'identity_card' => '0',
					'membership_number' => $result->user->uid,
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
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['apipath'] . "/member/reg";
		$yibodata = array (
				'hotelId' => 802256,
				'uname' => $data ['name'],
				'tel' => $data ['telephone'],
				'email' => $data ['email'],
				'cardType' => '身份证',
				'cardNum' => $data ['identity_card'],
				'nickName' => $data ['name'],
				'usex' => $data ['sex'],
				'password' => '88888888',
		);
		
		$result = $this->post_curl ( $url, $yibodata, $pms_auth ['token'] );
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
	function post_curl($url, $data = '', $token = '') {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
		curl_setopt ( $ch, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
		curl_setopt ( $ch, CURLOPT_USERPWD, $token );
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
	public function getMemberLevel($params) {
		$member = $params [0];
		try {
			$this->getMemberModel ()->getMemberLevel ( $member );
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return $this;
	}
	public function loginGetUserinfo($params) {
		
		$telephone = $params [1] ['telephone'];
		$openid = $params [0];
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['apipath'] . "/member/info/$telephone/123456";
		$result = $this->post_curl ( $url, '', $pms_auth ['token'] );
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
					'inter_id' => $params [2]
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->user->score,
					'is_login' => 1,
					'level' => $this->yibomemberlevel((int)$result->user->level),
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
	
	public function updateMemberByOpenId($params)
	{
		try {
			$data = $params[0];
			if(!isset($data['openid'])) throw new Exception("openid不存在");
	
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}

	public function initMember($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		$inter_id = $params[2];
		$data['is_active'] = 0;
	
		$result = $this->createMember($openid, $data, $inter_id);
	
		if($result) {
			return $this->getMemberByOpenId($openid);
		} else {
			return false;
		}
	}

	//创建会员信息
	public function createMember($openid, $data, $inter_id)
	{
		// 		$data = $params[1];
		$data['openid'] = $openid;
		$data['inter_id'] = $inter_id;
			
		if(isset($data['is_active']))   $data['is_active'] = intval($data['is_active']);
	
		try {
			$result = $this->getMemberModel()->createMember($data);
			return $result;
		} catch (Exception $e) {
			return false;
		}
	
		return false;
	}
	
	public function getAllMemberLevels($params)
	{
		$inter_id = $params[0];
		try {
			return $this->getMemberModel($inter_id)->getAllMemberLevels($inter_id);
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
	
	//修改密码
	public function modPassword($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		$uid = $data['uid'];
		$opass = $data['oldpassword'];
		$npass = $data['password'];
		
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['apipath'] . "/member/epass/{$uid}/{$opass}/{$npass}";
		
		//var_dump($pms_auth,$url);exit;
		$result = $this->post_curl ( $url, '' , $pms_auth ['token'] );
		
		$result = simplexml_load_string ( $result );
		
		if(isset($result->code) && $result->code){
			return array('code'=>0,'errmsg'=>$result->msg);
		}else{
			return array('code'=>1,'errmsg'=>$result->msg);
		}
		
	}
	
	public function reduceBonus($params)
	{
		$openid       = $params[0];
		$bonus        = $params[1];
		$note         = $params[2];
		$order_id     = $params[3];
		$inter_id     = $params[4];
	
		try {
			$data = array(
					'openid'     => $openid,
					'bonus'      => $bonus
			);
			$result = $this->getMemberModel()->updateBonus($data, false, $note,$order_id,$inter_id);
			return $result;
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
	//@author lGh 缺少addBonus方法 2016-3-30 17:26:39
	public function addBonus($params)
	{
		$openid       = $params[0];
		$bonus        = $params[1];
		$note         = $params[2];
		$order_id     = $params[3];
		$inter_id     = $params[4];
	
		try {
			$data = array(
					'openid'     => $openid,
					'bonus'      => $bonus
			);
			$result = $this->getMemberModel()->updateBonus($data, true, $note,$order_id,$inter_id);
			return $result;
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

}