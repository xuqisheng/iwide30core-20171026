<?php
class Zhongruan_webservice implements IPMS {
	protected $CI;
	protected $_memberModel;
	protected $_usr;
	protected $_url;
	protected $_pwd;
	function __construct($params) {
		$this->CI = & get_instance ();
		$this->pms_set = $params ['pms_set'];
		$pms_param = json_decode($this->pms_set['pms_auth'],true);
		$this->_usr = $pms_param['user'];
		$this->_url = $pms_param['url'];
		$this->_pwd = $pms_param['pwd'];
	}
	public function get_orders($inter_id, $status, $offset, $limit) {
	}
	public function get_hotels($inter_id, $status, $offset, $limit) {
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$this->CI->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : null;
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}
	
	public function add_web_bill($order,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $this->pms_set, $trans_no);
	}
	
	function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		return $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
	}
	function update_web_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		return $this->CI->pms->update_web_order ( $inter_id, $order,$this->pms_set );
	}
	function check_order_canpay($order) {
		$this->CI->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		return $this->CI->pms->check_order_canpay ($order,$this->pms_set );
	}
	public function get_new_hotel($params = array()) {
		echo '';
	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$this->CI->load->model ( 'hotel/Member_model' );
		return $this->CI->Member_model->check_openid_member ( $inter_id, $openid, $paras );
		
		$update = empty ( $paras ['update'] ) ? '' : 'update';
		$login_type=empty($update)?'':'card_no';
		$member = $this->getMemberByOpenId ( array (
				$openid,
				$update,
				$login_type
		) );
		if (! empty ( $member ) && ! empty ( $member->mem_id ) ) {
			return $member;
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
	protected $err=array('0','0','0');
	
	protected function getSoap()
	{
		return new SoapClient($this->_url, array('encoding'=>'UTF-8'));
	}
	
	protected function getValueSoap()
	{
		return new SoapClient('http://59.37.49.171:8089/PhoenixHotelwebService/Service1.asmx?wsdl', array('encoding'=>'UTF-8'));
	}
	
	public function getUserModel($m=null)
	{
		$this->CI->load->model('pms_cshis/userinfo');
			
		return $this->CI->userinfo;
	}
	
	public function getIcstatModel()
	{
		$this->CI->load->model('pms_cshis/icstat');
	
		return $this->CI->icstat;
	}
	
	// 获取积分记录
	public function getBonusRecords($params) {
		$openid = $params [0];
	
		$this->CI->load->model ( 'member/iconsume' );
		$memberObject = $this->getMemberByOpenId ( array ($openid) );
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
	public function getMemberDetailByMemId($params)
	{
		$memid = $params[0];
		try {
			$memberInfoObject = $this->getMemberModel()->getMemberDetailById($memid,array(),'mem_id');
			return $memberInfoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
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
					$account=$memberObject->telephone;
					if (!empty($params[2])&&$params[2]==='card_no'&&!empty($memberObject->membership_number)){
						$account=$memberObject->membership_number;
					}
					$result = $this->getPmsMember ( $openid, $account, $memberObject->password );
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
	public function getMemberModel() {
		if (! isset ( $this->_memberModel )) {
			$this->CI->load->model ( 'member/member' );
			$this->_memberModel = $this->CI->member;
		}
	
		return $this->_memberModel;
	}
	protected function memberlevel($level, $positive = true) {
		$this->CI->load->model('common/Webservice_model');
		$member = $this->CI->Webservice_model->get_web_reflect($this->CI->session->userdata('inter_id'),0,'zhongruan','member_level',1);
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
		//@author lGh 2016-3-31 18:06:38 短信验证
		if($this->CI->session->userdata('sms') == $params[1]){
			return 1;
		}else 
			return 0;
// 		return $this->CI->session->userdata('sms') == $params[0];
// 		return true;
	}
	
	//发送短信信息
	public function sendSms($params){
		$phone = $params[0]['telephone'];
		$code = $params[1];
		$this->CI->load->model('member/sms','sms');
		$this->CI->sms->setLog();
		$result = $this->CI->sms->Sendsms($phone,array($code),60225);
		$this->CI->session->set_userdata(array('sms'=>$code,'sms_time'=>time()));
		if(!(int)$result['code']){
			return '发送成功';
		}else{
			return '发送失败';
		}
	}

	//碧桂园发送短信信息
	public function sendBgySms($params){
		$phone = $params[0]['telephone'];
		$code = $params[1];
		$this->CI->session->set_userdata('sms', $code);
		$this->CI->load->helper('common');
		$res = doCurlGetRequest('http://biguiyuan.iwide.cn/index.php/ts/welcome/tsmst?tel='.$phone.'&cod='.$code);
		return $res;
	}
	
	//发送短信信息
	public function sendSetPassword($params){
		$phone = $params[0];
		$code = $params[1];
		$num = mt_rand(100000, 999999);
		$this->CI->session->set_userdata('sms', $num);
// 		$this->CI->load->model('member/Bgysms');
		//$this->sendsms->MessageContent = "您的验证码为".$num."请妥善保管并及时输入。";
// 		$this->CI->Bgysms->MessageContent = "您的微信验证码为".$num.",请及时输入。如此操作非您所为,请致电4000-346-999!我们将尽快处理,感谢您的谅解。";
// 		$this->CI->Bgysms->UserNumber = $phone;
		$this->CI->load->helper('common');
// 		$this->CI->Bgysms->send();
		$res = doCurlGetRequest('http://biguiyuan.iwide.cn/index.php/ts/welcome/tsmst?tel='.$phone.'&cod='.$num);
		if($res){
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
			$member_info = $this->getMemberModel ()->getMemberDetailById ( $data ['openid'] );
			if($member_info->membership_number){
				$userinfo = $this->getUserModel();
				$userinfo->Ic_num = $member_info->membership_number;
				$user = $this->getUserInfo($userinfo);
				$user->sex_cd      = '0'.$data ['sex'];
				$user->gh_nm       = $data ['name'];
				$user->crtf_num    = isset($data['identity_card'])?$data['identity_card']:"";
				$user->email    = isset($data['email'])?$data['email']:"";
				$this->modUserinfo($user);
			}
			$result = $this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return $result;
		} catch ( Exception $e ) {
			log_message ( 'error', $e->getMessage () );
		}
	
		return false;
	}
	public function modifiedMember($params)
	{
		return $params[1];
	}
	public function checklogin($params) {
		$result = $this->getPmsMember_v_1 ( $params [0], $params [1], $params [2], $params [3] );
		return $result;
	}
	public function getPmsMemberCard($params) {
		$user_info = $this->getUserModel();
		if(isset($params[2]) && $params[2] == 1)
			$user_info->mobile = $params[0];
		else
			$user_info->Ic_num = $params[0];
		if(!empty($params[1]))
			$user_info->Ic_pwd = $params[1];
		$result = $this->getUserInfo($user_info);
	
		if ($result !== false) {
			return $result;
		} else {
			return false;
		}
	}
	protected function getPmsMember_v_1($openid, $telephone, $password, $inter_id = '') {
		$user_info = $this->getUserModel();
		$user_info->mobile = $telephone;
		$user_info->Ic_pwd = $password;
		$user_info->Ic_typ = 'P';
		$result = $this->getUserInfo_v_1($user_info);
		//卡号登录
		if ($result == false) {
			$user_info->mobile = '';
			$user_info->Ic_num = $telephone;
			$result = $this->getUserInfo_v_1($user_info);
		}
		$check_true = -1;
		$levels = array('Q','R','S');
		if ($result !== false) {
			if(is_array($result)){
				foreach ($result as $user){
					if(in_array($user->Ic_typ, $levels)){
						//@todo 取会员最高级别
						$level_index = array_search ($user->Ic_typ,$levels);
						if($level_index !== FALSE && $check_true < $level_index)
							$check_true = $level_index;
					}
				}
				if($check_true != -1)
					$result = $result[$check_true];
			}else{
				if(in_array($result->Ic_typ, $levels)){
					$check_true = 0;
				}else{
					return false;
				}
			}
			//非会员卡登录，返回登录失败
			if($check_true == -1) return false;
			$data = array (
					'openid' => $openid,
					'name' => $result->gh_nm,
					'telephone' => $result->mobile,
					'email' => $result->email,
					'identity_card' => $result->crtf_num,
					'membership_number' => $result->Ic_num,
					'sex' => $result->sex_cd == '男' ? 1 : 2,
					'dob' => $result->birthday,
					'password' => $password,
					'inter_id' => $inter_id
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->tot_score,
					'balance' => abs($result->ic_bal),
					'is_login' => 1,
					'is_active' => 1,
					'level' => $this->memberlevel($result->Ic_typ,false),
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
	protected function getPmsMember($openid, $telephone, $password, $inter_id = '') {
		$user_info = $this->getUserModel();
		$user_info->mobile = $telephone;
		$user_info->Ic_pwd = $password;
		$user_info->Ic_typ = 'P';
		$result = $this->getUserInfo($user_info);
		//卡号登录
		if ($result == false) {
			$user_info->mobile = '';
			$user_info->Ic_num = $telephone;
			$result = $this->getUserInfo($user_info);
		}
		if ($result !== false) {
			$data = array (
					'openid' => $openid,
					'name' => $result->gh_nm,
					'telephone' => $result->mobile,
					'email' => $result->email,
					'identity_card' => $result->crtf_num,
					'membership_number' => $result->Ic_num,
					'sex' => $result->sex_cd == '男' ? 1 : 2,
					'dob' => $result->birthday,
					'password' => $password,
					'inter_id' => $inter_id
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->tot_score,
					'balance' => abs($result->ic_bal),
					'is_login' => 1,
					'is_active' => 1,
					'level' => $this->memberlevel($result->Ic_typ,false),
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
		//获取分销ID、名称、酒店名称信息
		$staff_info = $this->getMemberModel()->getUserWxStaff( $params[0] , $params[2] );
		$reg_notice = $staff_info['source'].','.$staff_info['name'].','.$staff_info['hotel_name'];
		$openid            = $params [0];
		$data              = $params [1];
		$data ['inter_id'] = $params [2];
	
		$user_info              = $this->getUserModel();
		$user_info->mobile      = $data ['telephone'];
		
		$result = $this->getUserInfo_v_1($user_info);

        $this->CI->load->model('member/Weixin_text','Weixin');
        $time = date('Y-m-d H:i:s',time());
        $data_str = "bgy_register1+".$user_info->mobile."+提交注册+".json_encode($data);
        $this->CI->Weixin->add_weixin_text($data_str,$time);

        $check_true = -1;
        $levels = array('Q','R','S');
        if ($result !== false) {
        	if(is_array($result)){
        		foreach ($result as $user){
        			if(in_array($user->Ic_typ, $levels)){
        				//@todo 取会员最高级别
        				$level_index = array_search ($user->Ic_typ,$levels);
        				if($level_index !== FALSE && $check_true < $level_index)
        					$check_true = $level_index;
        			}
        		}
        		if($check_true != -1)
        			$result = $result[$check_true];
        	}else{
        		if(in_array($result->Ic_typ, $levels)){
        			$check_true = 0;
        		}else{
        			$result = false;
        		}
        	}
        	//非会员卡登录，返回登录失败
        	if($check_true == -1) {
        		$result = false;
        	}
        }
		if ($result === false) {
			
			$user_info->gh_nm       = $data ['name'];
			if(isset($data ['email']))
			$user_info->email       = $data ['email'];
	// 		$user_info->crtf_typ    = $data ['身份证'];
			if(isset($data ['identity_card']))
			$user_info->crtf_num    = $data ['identity_card'];
			if(isset($data ['sex']))
			$user_info->sex_cd      = '0'.$data ['sex'];
			$user_info->Ic_pwd = '888888';
			if(isset($data ['password']))
			$user_info->Ic_pwd      = $data ['password'];
			$user_info->Ic_ref      = 'weixin';
			$user_info->Ic_typ      = 'Q';
			//$user_info->email      = isset($data['email'])?$data['email']:"";
			//$user_info->crtf_num      = isset($data['identity_card'])?$data['identity_card']:"";
			if(isset($data['level']))
				$user_info->Ic_typ  = $data['level'];
			$user_info->ic_stus     = 1;
			$user_info->Company_num = 'T10183';
			$user_info->notice      = $reg_notice;
			$user_info->tot_score   = 0;
			if(isset($data['crtf_typ']) && !empty($data['crtf_typ']))
				$user_info->crtf_typ   = $data['crtf_typ'];
			if(isset($data['crtf_num']) && !empty($data['crtf_num']))
				$user_info->crtf_num   = $data['crtf_num'];
			$result = $this->addUserinfo($user_info);

            $time = date('Y-m-d H:i:s',time());
            $data_str = "bgy_register2+".$user_info->mobile."+提交PMS成功+".json_encode($result);
            $this->CI->Weixin->add_weixin_text($data_str,$time);

			if ($result !== false) {
				$data ['membership_number'] = $result;
					
// 				$updateParams = array (
// 						'openid' => $openid,
// 						'is_login' => 1,
// 						'is_active' => 1,
// 						'last_login_time' => time ()
// 				);
				
// 				$data = array (
// 						'openid' => $openid,
// 						'name' => $result->gh_nm,
// 						'telephone' => $result->mobile,
// 						'email' => $result->email,
// 						'identity_card' => $result->crtf_num,
// 						'membership_number' => $result->Ic_num,
// 						'dob' => $result->birthday,
// 						'password' => $password,
// 						'inter_id' => $inter_id
// 				);
// 				$updateParams = array (
// 						'openid' => $openid,
// 						'bonus' => $user_info->tot_score,
// 						'is_login' => 1,
// 						'level' => $this->memberlevel($user_info->Ic_typ,false),
// 						'last_login_time' => time ()
// 				);
// 				$this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
				
// 				$this->getMemberModel ()->updateMemberByOpenId ( $updateParams );
				
// 				$this->addMemberInfo ( array ( $openid, $data ) );
					
			}

            if($this->getPmsMember_v_1($openid,$user_info->mobile,$user_info->Ic_pwd,$data ['inter_id'])){

                $time = date('Y-m-d H:i:s',time());
                $data_str = "bgy_register3+".$user_info->mobile."+注册成功+".json_encode($result);
                $this->CI->Weixin->add_weixin_text($data_str,$time);

				return array (
						'code' => 1,
						'errmsg' => '注册成功！'
				);
			}else{

                $time = date('Y-m-d H:i:s',time());
                $data_str = "bgy_register4+".$user_info->mobile."+已经注册+".json_encode($result);
                $this->CI->Weixin->add_weixin_text($data_str,$time);

				return array (
						'code' => 0,
						'errmsg' => $result
				);
			}
		}else{

            $time = date('Y-m-d H:i:s',time());
            $data_str = "bgy_register5+".$user_info->mobile."+已经注册+".json_encode($result);
            $this->CI->Weixin->add_weixin_text($data_str,$time);

			return array (
					'code' => 0,
					'errmsg' => '该手机号已注册过会员'
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
	
		$user_info = $this->getUserModel();
		$user_info->mobile = $params [1] ['telephone'];
		$result = $this->getUserInfo($user_info);
	
		if ($result !== false) {
			$data = array (
					'openid'            => $openid,
					'name'              => $result->gh_nm,
					'telephone'         => $result->mobile,
					'email'             => $result->email,
					'identity_card'     => $result->crtf_num,
					'membership_number' => $result->Ic_num,
					'dob'               => $result->birthday,
					'inter_id'          => $params [2]
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->tot_score,
					'is_login' => 1,
					'level' => $this->memberlevel($result->level),
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
	//重置密码
	public function updatePassWordin($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		$uid    = $data['telephone'];
		$opass  = $data['custom1'];
		$npass  = $data['custom2'];
	
		if($opass != $npass)
			return array('code'=>1,'errmsg'=>'密码与确认密码不一致');
		$user_info = $this->getUserModel();
		$user_info->mobile = $uid;
		$result = $this->getUserInfo_v_1($user_info);
		$check_true = -1;
		$levels = array('Q','R','S');
		if($result !== false){
			
			if(is_array($result)){
				foreach ($result as $user){
					if(in_array($user->Ic_typ, $levels)){
						//@todo 取会员最高级别
						$level_index = array_search ($user->Ic_typ,$levels);
						if($level_index !== FALSE && $check_true < $level_index)
							$check_true = $level_index;
					}
				}
				if($check_true != -1)
					$result = $result[$check_true];
			}else{
				if(in_array($result->Ic_typ, $levels)){
					$check_true = 0;
				}else{
					return array('code'=>1,'errmsg'=>'会员不存在');
				}
			}
			//非会员卡登录，返回登录失败
			if($check_true == -1) {
				return array('code'=>1,'errmsg'=>'会员不存在');
			}
			
			$result->Ic_pwd = $npass;
			$res = $this->modUserinfo($result);
			if($res === false){
				return array('code'=>1,'errmsg'=>'修改密码失败');
			}else{
				return array('code'=>0,'errmsg'=>'密码修改成功');
			}
		}else{
			return array('code'=>1,'errmsg'=>'会员不存在');
		}
	
	}
	//修改密码
	public function modPassword($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		$uid    = $data['uid'];
		$opass  = $data['oldpassword'];
		$npass  = $data['password'];
	
	
		$user_info = $this->getUserModel();
		$user_info->mobile = $uid;
		$result = $this->getUserInfo_v_1($user_info);
		$check_true = -1;
		$levels = array('Q','R','S');
		if ($result !== false) {
			if(is_array($result)){
				foreach ($result as $user){
					if(in_array($user->Ic_typ, $levels)){
						//@todo 取会员最高级别
						$level_index = array_search ($user->Ic_typ,$levels);
						if($level_index !== FALSE && $check_true < $level_index)
							$check_true = $level_index;
					}
				}
				if($check_true != -1)
					$result = $result[$check_true];
			}else{
				if(in_array($result->Ic_typ, $levels)){
					$check_true = 0;
				}else{
					return array('code'=>1,'errmsg'=>'旧密码错误或会员不存在');
				}
			}
			//非会员卡登录，返回登录失败
			if($check_true == -1) {
				return array('code'=>1,'errmsg'=>'旧密码错误或会员不存在');
			}
			
			
			$result->Ic_pwd = $npass;
			$res = $this->modUserinfo($result);
			if($res === false){
				return array('code'=>1,'errmsg'=>'修改密码失败');
			}else{
				return array('code'=>0,'errmsg'=>'密码修改成功');
			}
		}else{
			return array('code'=>1,'errmsg'=>'旧密码错误或会员不存在');
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
			//<s:element minOccurs="0" maxOccurs="1" name="ic_num" type="s:string"/>
			// <s:element minOccurs="1" maxOccurs="1" name="trn_dt" type="s:dateTime"/>
			// <s:element minOccurs="0" maxOccurs="1" name="sort_drpt" type="s:string"/>
			// <s:element minOccurs="1" maxOccurs="1" name="trn_amt" type="s:double"/>
			// <s:element minOccurs="1" maxOccurs="1" name="score" type="s:int"/>
			// <s:element minOccurs="0" maxOccurs="1" name="htl_cd" type="s:string"/>
			// <s:element minOccurs="0" maxOccurs="1" name="htl_nm" type="s:string"/>
			// <s:element minOccurs="0" maxOccurs="1" name="sort_typ" type="s:string"/>
			// <s:element minOccurs="0" maxOccurs="1" name="oper_cd" type="s:string"/>
			// <s:element minOccurs="0" maxOccurs="1" name="acct_num" type="s:string"/>
			// <s:element minOccurs="1" maxOccurs="1" name="temp_dt" type="s:dateTime"/>
			// <s:element minOccurs="0" maxOccurs="1" name="notice" type="s:string"/>
			// <s:element minOccurs="0" maxOccurs="1" name="bill_no" type="s:string"/>
			// <s:element minOccurs="1" maxOccurs="1" name="post_tm" type="s:dateTime"/>
			// <s:element minOccurs="0" maxOccurs="1" name="trn_pwd" type="s:string"/>
			//$order ['orderid'].','.$ri_add->ic_num.','.$ri_add->htl_cd
			$orderids=explode(',', $params[3]);
			$data = array(
					'ic_num'=>$orderids[1],
					'trn_dt'=>date('Y-m-d').'T'.date('H:i:s'),
					'sort_drpt'=>'',
					'trn_amt'=>0,
					'score'=>$bonus*-1,
					'htl_cd'=>$orderids[2],
					'htl_nm'=>'',
					'sort_typ'=>'###',
					'oper_cd'=>'',
					'acct_num'=>'',
					'temp_dt'=>'0001-01-01',
					'notice'=>$note.',订单号：'.$orderids[3],
					'bill_no'=>'',
					'post_tm'=>date('Y-m-d').'T'.date('H:i:s'),
					'trn_pwd'=>''
			);
			$this->CI->load->model('hotel/pms/Zhongruan_hotel_model');
			$result=$this->CI->Zhongruan_hotel_model->sub_to_web ( $this->pms_set, 'Set_Icstat', array (
					'iT' => $data
			));
			if (!empty($result)&&$result->Set_IcstatResult==true){
				$data = array(
						'openid'     => $openid,
						'bonus'      => $bonus
				);
				$result = $this->getMemberModel()->updateBonus($data, false, $note,$orderids[0],$inter_id);
				return $result;
			}else {
				return FALSE;
			}
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
	public function getIcTypUpdateInfoModel()
	{
// 		$this->CI->load->model('pms_cshis/IcTypUpdateInfo');
			
// 		return $this->CI->Ictypupdateinfo;
		return new IcTypUpdateInfo();
	}
	public function upgradeLevel($ic_num){
		$user_check = $this->getIcTypUpdateInfoModel();
		$user_check->Ic_num = trim($ic_num[0]);
		$rs = $this->upgradeMemberLevel( $user_check);
		return $rs;
	}
	protected function upgradeMemberLevel($user_check,$lang='CN'){
		$params=array('iuI'=>$user_check,'Err'=>$this->err,'user_cd'=>$this->_usr,'password'=>$this->_pwd,'lang'=>$lang);
		$s=$this->getSoap()->__Call('UpdateIcTyp',array('parameters'=>$params));
		return $s;
	}
	public function getUserInfo($user, $lang='CN'){
		$params=array('sU'=>$user,'Err'=>$this->err,'user_cd'=>$this->_usr,'password'=>$this->_pwd,'lang'=>$lang);
		$result = $this->getSoap()->__Call('GetUserInfo',array('parameters'=>$params));
		if($result->Err->string[1] && isset($result->GetUserInfoResult->UserInfo)) {
			return $result->GetUserInfoResult->UserInfo;
		} else {
			return false;
		}
	}
	public function getUserInfo_v_1($user, $lang='CN'){
		$params=array('sU'=>$user,'Err'=>$this->err,'user_cd'=>$this->_usr,'password'=>$this->_pwd,'lang'=>$lang);
		$result = $this->getSoap()->__Call('GetUserInfoByBGY',array('parameters'=>$params));
		if($result->Err->string[1] && isset($result->GetUserInfoByBGYResult->UserInfo)) {
			return $result->GetUserInfoByBGYResult->UserInfo;
		} else {
			return false;
		}
	}
	
	protected function addUserinfo($user, $lang='CN'){
		$params=array('sU'=>$user,'Err'=>$this->err,'user_cd'=>$this->_usr,'password'=>$this->_pwd,'lang'=>$lang);
		$result = $this->getSoap()->__Call('Add_Userinfo',array('parameters'=>$params));
	
		if($result->Add_UserinfoResult === true) {
			return $result->Err->string[2];
		} else {
			return false;
		}
	}
	protected function modUserinfo($user, $lang='CN') {
		$params=array('sU'=>$user,'Err'=>$this->err,'user_cd'=>$this->_usr,'password'=>$this->_pwd,'lang'=>$lang);
		$result = $this->getSoap()->__Call('Mod_Userinfo',array('parameters'=>$params));
		return $result->Mod_UserInfoResult;
	}
}
class IcTypUpdateInfo{
	public $Ic_typ='Q';
	public $Ic_num='';
	public $Para_drpt='';
	public $Para_cd='01';
	public $Trn_flg='R';
	public $Sgl_rt=0.00;
	public $Dbl_rt=0.00;
}