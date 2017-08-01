
<?php
class Native_webservice implements IPMS {
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
		$this->CI->load->model ( 'hotel/pms/Luopan_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : '';
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Luopan_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}
	public function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Luopan_hotel_model', 'pms' );
		return $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
	}
	public function update_web_order($inter_id, $order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Luopan_hotel_model', 'pms' );
		$params ['pms_set'] = $this->pms_set;
		return $this->CI->pms->update_web_order ( $inter_id, $order, $params );
	}
	public function check_order_canpay($order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Luopan_hotel_model', 'pms' );
		$params ['pms_set'] = $this->pms_set;
		return $this->CI->pms->check_order_canpay ( $order, $params );
	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$this->CI->load->model ( 'hotel/Member_model' );
		return $this->CI->Member_model->check_openid_member ( $inter_id, $openid, $paras );
	}

	/** 会员系统 START **/

	/**
	 * 根据openid获取会员
	 * @param strint $openid
	 * @return unknown|boolean
	 */
	public function getMemberByOpenId($params)
	{
		try {
			$openid = $params[0];
			$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
			if($memberObject && isset($memberObject->mem_id)) {
				if((isset($params[1]) && $params[1]=='update' && $memberObject->is_login==1) || ($memberObject->is_login==1 && (time()-$memberObject->last_login_time>7200))) {
					$result = $this->getPmsMember($openid, $memberObject->membership_number, $memberObject->password);
					if($result) {
						$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
					}
				}
				$memberObject->mebtype = $this->memberlevel($memberObject->level,false);
				return $memberObject;
			}
			return false;
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

	//更新资料
	protected function getPmsMember($openid, $card_no, $password, $inter_id='')
	{
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$url = $pms_auth['url'] . 'card_login?' . $pms_auth['url_auth'].'&card_no='.$card_no.'&password='.$password;
		$result = $this->doCurlGetRequest($url,'');
		if(isset($result->login_result) && $result->login_result==0) {
			$data = array(
				'openid'            => $params[0],
				'name'              => $result->card->user_name,
				'telephone'         => isset($result->card->mobile)?$result->card->mobile:'',
				'email'             => isset($result->card->email)?$result->card->email:'',
				'identity_card'     => $result->card->id_card_no,
				'membership_number' => $result->card->card_no,
				'password'          => $params[2],
				'inter_id'          => $params[3],
			);
			$updateParams = array('openid'=>$params[0],'bonus'=>$result->card->card_score,'is_login'=>1,'level'=>$this->memberlevel($result->card->card_type_id),'last_login_time'=>time());
			$this->getMemberModel()->updateMemberByOpenId($updateParams);
			$this->getMemberModel()->updateMemberInfoByOpenId($data);
			return true;
		} else {
			$this->getMemberModel()->updateMemberByOpenId(array('openid'=>$openid,'is_login'=>0));
			return false;
		}
	}

	//匹配会员等级信息
	protected function memberlevel($level,$positive=true)
	{
		static $member = array(
			'1' => 0, 
			'2' => 1, 
			'3' => 2, 
			'4' => 3, 
			'5' => 4, 
			'6' => 5,
			'7' => 6,
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
	//新的登录方式
	public function checklogin($params){
		$memberInfo = $this->getMemberByOpenId($params);
		if( ($memberInfo->mem_card_no == $params[1] || $memberInfo->telephone == $params[1] || $memberInfo->identity_card == $params[1] ) && ($memberInfo->password==$params[2]) ){
			$updateParams = array('openid'=>$params[0],'bonus'=>'','is_login'=>1,'last_login_time'=>time());
			$this->getMemberModel()->updateMemberByOpenId ( $updateParams );
			return true;
		}else{
			return false;
		}
		return false;
	}

	//模拟提交的GET方法
	/**
	 * 封装curl的调用接口，get的请求方式
	 * @param string 请求URL
	 * @param array 请求参数值array(key=>value,...)
	 * @param second 超时时间
	 * @return 请求成功返回成功结构，否则返回FALSE
	 */
	public function doCurlGetRequest($url, $data = array(), $timeout = 10) {
		if ($url == "" || $timeout <= 0) {
			return false;
		}
		if ($data != array () && $data) {
			if( strpos($url,'?') ){
				$url = $url.'&'.http_build_query ( $data );
			}else{
				$url = $url . '?' . http_build_query ( $data );
			}
		}
		$con = curl_init ( ( string ) $url );
		curl_setopt ( $con, CURLOPT_HEADER, false );
		curl_setopt ( $con, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $con, CURLOPT_TIMEOUT, ( int ) $timeout );
		curl_setopt ( $con, CURLOPT_SSL_VERIFYPEER, false );
		
		return curl_exec ( $con );
	}

	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberDetailByOpenId($params) {
		$openid = $params [0];
		try {
			$memberInfoObject = $this->getMemberModel()->getMemberDetailById ( $openid );
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

	//给出会员的模型
	public function getMemberModel()
	{
		if(!isset($this->_memberModel)) {
			$this->CI->load->model('member/member');
			$this->_memberModel = $this->CI->member;
		}
		return $this->_memberModel;
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

	public function modifiedMember($params)
	{
		$member = $params[1];
		
		if($member && isset($member->mem_id) && $member->is_active) {
			$this->CI->load->model('member/interface/yuanzhou');
				
			if($member->is_login && !empty($member->custom2)) {
				$request_data = array('valuecardno'=>$member->custom2);
				$vcresult = $this->CI->yuanzhou->WxMemberValueCard($request_data);
				if($vcresult && isset($vcresult->valuecardlimit)) {
					$member->balance = abs((string)$vcresult->valuecardlimit);
				}
			}

			if($member->is_login && !empty($member->telephone) && !empty($member->identity_card)) {
				$request2data = array('phone'=>$member->telephone,'idno'=>$member->identity_card);
				$yzmember = $this->CI->yuanzhou->wxqueryMemberinfo($request2data);
		
				if($yzmember && isset($yzmember->phone)) {
					$member->bonus = (string)$yzmember->score;
					$member->levelinfo = (string)$yzmember->typename;
						
					$u_data['membership_number'] = (string)$yzmember->cardno;
					$u_data['name'] = (string)$yzmember->username;
					$u_data['sex'] = intval((string)$yzmember->sex-1);
		
					$this->addMemberInfo(array($member->openid, $u_data));
				}
			}
		}
		
		return $member;
	}

	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberInfoByOpenId($params)
	{
		$openid  = $params[0];
		try {
			$memberinfoObject = $this->getMemberModel()->getMemberInfoById($openid);
			return $memberinfoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	/**
	 * 更新激活状态
	 * @param string $openid
	 * @param int $active
	 * @return unknown|boolean
	 */
	public function updateStatus($params)
	{
		$openid        = $params[0];
		$active        = $params[1];
	
		$data = array(
				'openid'         => $openid,
				'is_active'      => $active
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}

	/**
	 * 添加会员详细资料
	 * @param string $openid
	 * @param array $data
	 * @return unknown|boolean
	 */
	public function addMemberInfo($params)
	{
		$data           = $params[1];
		$data['openid'] = $params[0];
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}

	//检测短信验证码是否合法
	function checkSendSms($params){
		$getsms = $params[1];
		if(!$_SESSION['sms']) {
			return  0;
		} elseif($_SESSION['sms']==$getsms) {
			return 1;
		}
		return 0;
	}

	//本地发送短信
	function sendSms($params){
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
	//找回密码发送短信
	public function sendSetPassword($params){
		$phone = $params[0];
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

	public function registerMember($params)
	{
		$openid           = $params[0];
		$data             = $params[1];
		$data['inter_id'] = $params[2];
		$data['openid'] = $openid;
		$data['membership_number'] = '';
		$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
		if($result) {
			$data['membership_number'] = $data['membership_number'];
			$updateParams = array(
				'openid'           => $openid,
				'is_login'         => 1,
				'is_active'        => 1,
				'last_login_time'  => time()
			);
			$this->getMemberModel()->updateMemberByOpenId($updateParams);
			$this->addMemberInfo(array($openid,$data));
			//注册送券   START
			
			$this->CI->load->model('member/igetcard','igetcard');
			$this->CI->load->model('member/irule','irule');
			$rules = $this->CI->irule->getRewardByRules('all', 'member_focuks_after', $data['inter_id'] , array('hotel_register'=>1));
		
			foreach ($rules as $key => $value) {
				foreach ($value['reward']['card'] as $ke => $val) {
					$this->CI->igetcard->userGetCard($openid,$data['inter_id'],array($val['ci_id']=>$val['quantity']),null,array('rule_id'=>$key));
				}
			}
			//注册送券   END
			return array('code'=>0,'errmsg'=>'注册成功');
		} else {
			return array('code'=>1,'errmsg'=>'注册失败');
		}
	}

	//找回密码
	public function updatePassWordin($params){
		$openid = $params[0];
		$data = $params[1];
		$inter_id = $params[2];
		$datas['openid'] = $openid;
		$datas['password'] = $data['custom1'];
		$result = $this->getMemberModel()->updateMemberInfoByOpenId($datas);
		if($result){
			$updateParams = array(
				'openid'           => $openid,
				'is_login'         => 0,
				'is_active'        => 0,
				'last_login_time'  => time()
			);
			$this->getMemberModel()->updateMemberByOpenId($updateParams);
			return array('code'=>0,'errmsg'=>'修改成功');
		}else{
			return array('code'=>1,'errmsg'=>'修改失败');
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
	/** 会员系统 END **/
}