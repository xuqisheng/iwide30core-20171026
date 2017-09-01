<?php
class Buding_webservice implements IPMS {
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
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : '';
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function add_web_bill($order,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'pms' );
		$room_codes = json_decode ( $order ['room_codes'], TRUE );
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $room_codes, $this->pms_set,$trans_no);
	}
	public function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'pms' );
		return $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
	}
	public function update_web_order($inter_id, $order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'pms' );
		$params ['pms_set'] = $this->pms_set;
		return $this->CI->pms->update_web_order ( $inter_id, $order, $params );
	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$this->CI->load->model('hotel/Member_model');
		return  $this->CI->Member_model->check_openid_member($inter_id,$openid,$paras);
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}

	//登录
	public function checklogin($params){
		//var_dump($params);exit;
		$result = $this->getPmsMember($params[0],$params[1],$params[2],$params[3]);
		return $result;
	}

	/**
	*	登录操作
	*	@param $openid 用户微信的ID
	*	@param $account 用户登录的账号信息
	*	@param $password 用户登录的密码
	*	@param $hoter_id 当前的酒店信息
	*	@return str
	*/

	public function getPmsMember( $openid, $telephone, $password, $inter_id='' ){
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'BudingPms' );
		$data = array(
			'userName'=>$telephone,
			'password'=>$password,
			'IsTravel'=>0,
			);
		$result = $this->CI->BudingPms->sub_to_web( $this->pms_set , 'Login' , $data );
		if(isset($result->LoginResult) && strstr($result->LoginResult,'OK') ){
			$aspxauth_name = $this->pms_set['inter_id'].'_member_session_id';
			$ASPXAUTH = isset($_SESSION[$aspxauth_name])?$_SESSION[$aspxauth_name]:'';
			$result_login = $this->CI->BudingPms->sub_to_web( $this->pms_set , 'GetMemberEntry' , '' , array('.ASPXAUTH'=>$ASPXAUTH) );
			$userInfo = get_object_vars(simplexml_load_string($result_login->GetMemberEntryResult));
			$userdata = array(
				'openid'            => $openid,
				'name'              => $userInfo['PM_NAME'],
				'telephone'         => $userInfo['PM_MOBILE'],
				'email'             => $userInfo['PM_EMAIL'],
				'identity_card'     => $userInfo['PM_ID_NUM'],
				'membership_number' => $userInfo['PM_ID'],
				'password'          => $password,
				'inter_id'          => $inter_id,
			);
			if( strstr($result->LoginResult,'EX') ){
				return false;exit;
			}
			$updateParams = array('openid'=>$openid,'bonus'=>$userInfo['PM_FEN'],'is_login'=>1,'level'=>$this->memberlevel(1),'last_login_time'=>time());
			$this->getMemberModel()->updateMemberByOpenId($updateParams);
			$this->getMemberModel()->updateMemberInfoByOpenId($userdata);
			return true;
			
		}else{
			$this->getMemberModel()->updateMemberByOpenId(array('openid'=>$openid,'is_login'=>0));
			return false;
		}
	}

	//注册信息
	public function registerMember( $params ){
		$openid           = $params[0];
		$data             = $params[1];
		$data['inter_id'] = $params[2];
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'BudingPms' );
		$data = array(
			'email'=>$data['email'],
			'mobile'=>$data['telephone'],
			'password'=>$data['password'],
			'mobileWay'=>'mobile_wap',
			'name'=>$data['name'],
			'sfz'=>$data['identity_card'],
			);
		$result = $this->CI->BudingPms->sub_to_web( $this->pms_set , 'Register2' , $data );
		if(isset($result->Register2Result) && strstr($result->Register2Result,'OK')){
			return array('code'=>1,'errmsg'=>'注册成功');
		} else {
			return array('code'=>0,'errmsg'=>$result->Register2Result);
		}
	}

	//发送短信验证码
	public function sendSms($params)
	{
		$telephone = $params[0]['telephone'];
		$rand      = $params[1];
		if(count($params)>4) {
			$type = $params[2];
		} else {
			$type = 0;
		}
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'BudingPms' );
		$data = array(
			'mobile'=>$telephone,
			'isCheckMobile'=>'',
			);

		$result = $this->CI->BudingPms->sub_to_web( $this->pms_set , 'MobileSendCode' , $data );
		if(isset($result->MobileSendCodeResult) && $result->MobileSendCodeResult=='OK'){
			return '发送成功';
		}
		return '发送失败';
	}

	//检测短信验证码是否合法
	function checkSendSms($params){
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'BudingPms' );
		$data = array(
			'code'=>$params[1],
			);
		$result = $this->CI->BudingPms->sub_to_web( $this->pms_set , 'MobileCheckCode' , $data );
		if(isset($result->MobileCheckCodeResult) && $result->MobileCheckCodeResult=='OK'){
			return true;
		}
		return false;
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
				$memberObject->mebtype = $this->memberlevel ( $memberObject->level, false );
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

	public function getMemberLevel($params) {
		$member = $params [0];
		try {
			$this->getMemberModel ()->getMemberLevel ( $member );
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
		
		return $this;
	}

	public function modifiedMember($params)
	{
		return $params[1];
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

	//匹配会员等级信息
	protected function memberlevel($level,$positive=true)
	{
		static $member = array(
			'CWSK' =>0,
			'CSKK'  => 1, //微信会员价（1早）
			'CRLK' => 2, //微信会员价（2早）
			'CZKK' => 3,	//尚客会员价		
		);
		
		if($positive) {
			if(isset($member[$level])) {
			    return $member[$level];
			} else {
				return '-1';
			}
		} else {
			$reverse_member = array_flip($member);
			
			if(isset($reverse_member[$level])) {
			    return $reverse_member[$level];
			} else {
				return '-1';
			}
		}
	}

	//给出会员的模型
	public function getMemberModel()
	{
		if(!isset($this->_memberModel)) {
			$this->CI->load->model('member/member');
			$this->_memberModel = $this->CI->member;
		}
		return $this->_memberModel;
	}
	public function getAllMemberLevels(){
		return false;
	}
	public function initMember($params)
	{		
		$openid = $params[0];
		$data   = $params[1];
		$inter_id = $params[2];
		$data['is_active'] = 0;
		
// 		$result = $this->createMember($openid, $data, $inter_id);
		$result = $this->createMember(array($openid, $data, $inter_id));//传参错误，作修改 @author lGh
		
		if($result) {
// 			return $this->getMemberByOpenId($openid);
			return $this->getMemberByOpenId(array($openid));//传参错误，作修改 @author lGh
		} else {
			return false;
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

	public function check_order_canpay($order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Buding_hotel_model', 'pms' );
		$params ['pms_set'] = $this->pms_set;
		return $this->CI->pms->check_order_canpay ($order, $params );
	}
}