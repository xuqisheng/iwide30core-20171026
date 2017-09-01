<?php
class Yuanzhou_webservice implements IPMS {
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
		$this->CI->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : '';
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function add_web_bill($order,$params=array()){//远洲无入账接口，只能在下单时传是否支付
		$this->CI->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $trans_no,$this->pms_set);
	}
	public function cancel_order($inter_id, $params) {
		$this->CI->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		return $this->CI->pms->cancel_web_order ( $inter_id, $params );
	}
	public function update_web_order($inter_id, $order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		return $this->CI->pms->update_web_order ( $inter_id, $order );
	}
	public function check_order_canpay($order, $params = array()) {
		$this->CI->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		return $this->CI->pms->check_order_canpay ( $order );
	}
	public function order_submit($inter_id,$orderid,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		return $this->CI->pms->add_web_order ( $inter_id, $orderid, $params,$this->pms_set );
	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$this->CI->load->model ( 'hotel/Member_model' );
		return $this->CI->Member_model->check_openid_member ( $inter_id, $openid, $paras );
		$update = empty ( $paras ['update'] ) ? '' : 'update';
		$member = $this->getMemberByOpenId ( array (
				$openid,
				$update 
		) );
		if (! empty ( $member ) && ! empty ( $member->mem_id ) ) {
// 		if (! empty ( $member ) && ! empty ( $member->mem_id ) && ! empty ( $member->membership_number )) {
// 			if ($member->is_login == 1) {
// 				$member->level = $member->mebtype;
// 				$member->mem_card_no = $member->membership_number;
// 			} else {
// 				$member->level = '';
// 				$member->mem_card_no = '';
// 			}
			return $member;
		}
		return false;
	}
	
	public function checklogin($params)
	{
		$openid    = $params[0];
		$telephone = $params[1];
		$password  = $params[2];
		$inter_id  = $params[3];
		$this->CI->load->model('member/interface/yuanzhou');
		
		if (strlen($password)!=32)
			$password=md5($password);
		
		$data = array(
			'password'=>$password,
			'phone'=>$telephone
		);
		$result = $this->CI->yuanzhou->isExistwxCardNew($data);
		if(!$result){
			return false;
		}
		$meminfo = $this->getMemberDetailByOpenId(array($openid));
		if($result) {
			$data = array('phone'=>$telephone);
			$r_data = $this->CI->yuanzhou->wxqueryMemberinfo($data);
			if($r_data) {
				if($meminfo && isset($meminfo->mem_id) && empty($meminfo->membership_number)) {
					$t_data = array(
						'phone'   => $telephone,
						'wxuserid'=> $meminfo->mem_card_no
					);
					$this->CI->yuanzhou->updateWxMember($t_data);
				}
				
				$u_data = array();
				$u_data['telephone'] = $telephone;
				$u_data['password']   = $password;//拼错了passwrod
				
				if(!empty($r_data->cardno))   $u_data['membership_number'] = str_replace(' ','',(string)$r_data->cardno);
				if(!empty($r_data->username)) $u_data['name'] = (string)$r_data->username;
				if(!empty($r_data->idno))     $u_data['identity_card'] = (string)$r_data->idno;
				if(!empty($r_data->sex))      $u_data['sex'] = intval($r_data->sex-1);
				if(!empty($r_data->wxuserid))      $u_data['custom1'] = intval($r_data->wxuserid);
				$result_info = $this->addMemberInfo(array($openid, $u_data));

				if($result_info) {
					$updateParams = array('openid'=>$openid, 'is_login'=>1, 'level'=>1, 'is_active'=>1, 'last_login_time'=>time(),'bonus'=> $r_data->score);
					$this->getMemberModel()->updateMemberByOpenId($updateParams);
					
					return true;
				}
			} else {
				return false;
			}
		} else {
			if($meminfo && isset($meminfo->mem_id)) {
				if($meminfo->password==$password && $meminfo->telephone == $telephone) {
					$this->updateMemberInfoCardNumber(array($openid,''));
					
					$updateParams = array('openid'=>$openid,'is_login'=>1,'level'=>1,'is_active'=>1,'last_login_time'=>time());
					$this->getMemberModel()->updateMemberByOpenId($updateParams);
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}
	
	//验证短信是否合法
	public function checkSendSms($params) {
		$getsms = $params[1];
		$this->CI->load->library('session');
		if(!$this->CI->session->has_userdata('sms')) {
			return  false;
		} elseif($this->CI->session->sms==$getsms) {
			return true;
		}
		return false;
	}
	
	/**找回密码
	 * @param unknown $tel
	* @param unknown $code
	* @param unknown $new_pwd
	* @return number[]|string[]|mixed[]
	*/
	public function updatePassWordin($params) {
		$openid        = $params[0];
		$telephone     = $params[1]['telephone'];
		$this->CI->load->model('member/interface/yuanzhou');
		$member = $this->getMemberDetailByOpenId(array($openid));
		$result = $this->CI->yuanzhou->isExistPhoneIdno(array('phone'=>$telephone));
		if($result){
			return array('code'=>1,'errmsg'=>"该手机号未注册会员!");
		}
		if($member && isset($member->mem_id) && !empty($member->membership_number)) {
			$newpassword = mt_rand(100000, 999999);
			$data = array(
				'wxuserid' => $member->custom1,
				'idno'     => $member->identity_card,
				'phone'    => $telephone,
				'password' => md5($newpassword)
			);
			$r = $this->CI->yuanzhou->WxPassWordInit($data);
		    if($r) {
		    	$this->CI->load->model('member/interface/yzsms');
		    	$this->CI->yzsms->sendNewpwd($telephone,$newpassword);
		    	return array('code'=>0,'errmsg'=>"新密码已经发送到您手机号，请用新密码登录!");
			} else {
				return array('code'=>1,'errmsg'=>"重置密码出错，请稍后再试!");
			}
		} else {
			$newpassword = mt_rand(100000, 999999);
			$data = array(
				'wxuserid' => '',
				'idno'     => '',
				'phone'    => $telephone,
				'password' => md5($newpassword)
			);
			$r = $this->CI->yuanzhou->WxPassWordInit($data);
		    if($r) {
		    	$this->CI->load->model('member/interface/yzsms');
		    	$this->CI->yzsms->sendNewpwd($telephone,$newpassword);
				$this->addMemberInfo(array($openid,array('password'=>md5($newpassword),'telephone'=>$telephone)));
				$this->CI->load->model('member/interface/yzsms');
				$this->CI->yzsms->sendNewpwd($telephone,$newpassword);
		    	return array('code'=>0,'errmsg'=>"新密码已经发送到您手机号，请用新密码登录!");
			} else {
				return array('code'=>1,'errmsg'=>"重置密码出错，请稍后再试!");
			}
		}
		return array('code'=>1,'errmsg'=>"系统出错，请联系客服!");
	}
	
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
// 			return $memberObject;
			
			if($memberObject && isset($memberObject->mem_id)) {
				if((isset($params[1]) && $params[1]=='update' && $memberObject->is_login==1) || ($memberObject->is_login==1 && (time()-$memberObject->last_login_time>7200))) {
					
					if(!$this->checklogin(array($openid,$memberObject->telephone,$memberObject->password,$memberObject->inter_id))){
						$this->updateMemberByOpenId(array(0=>array('openid'=>$openid, 'level'=>0, 'is_login'=>0,'bonus'=>0,'balance'=>0)));
					}
					
					/*$this->CI->load->model('member/interface/yuanzhou');
					$yzmember = $this->CI->yuanzhou->wxqueryMemberinfo(array('phone'=>$memberObject->telephone));
					if($yzmember && isset($yzmember->phone)) {
						$updateParams = array(
							'openid'          => $openid,
							'level'           => $this->memberlevel((string)$yzmember->type_codeb,true),
							'last_login_time' => time(),
							'bonus'           => (string)$yzmember->score,
						);

						if($memberObject->is_login && !empty($memberObject->custom2)) {
							$request_data = array('valuecardno'=>$memberObject->custom2);
							$vcresult = $this->CI->yuanzhou->WxMemberValueCard($request_data);
							if($vcresult && isset($vcresult->valuecardlimit)) {
								$updateParams['balance'] = abs((string)$vcresult->valuecardlimit);
							}
						}

						$this->getMemberModel()->updateMemberByOpenId($updateParams);
						
						$u_data['membership_number'] = (string)$yzmember->cardno;
						$u_data['name']              = (string)$yzmember->username;
						$u_data['sex']               = intval((string)$yzmember->sex-1);
						$this->addMemberInfo(array($openid, $u_data));
					}*/
					$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
				}
				
				return $memberObject;
				
			}
			return FALSE;

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
	
	protected function memberlevel($level,$positive=true)
	{
		static $member = array(
			'01'  => 1, //品悦银卡
			'02'  => 1, //品悦金卡
			'03'  => 1, //品悦白金卡
			'04'  => 1, //品悦银卡
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
	
	public function getMemberById($params)
	{
		$memid = $params[0];
		try {
			$memberObject = $this->getMemberModel()->getMemberById($memid,'mem_id');
			return $memberObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
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
					$u_data['telephone'] = $yzmember->phone;
		
					$this->addMemberInfo(array($member->openid, $u_data));
				}
			}
		}
		
		return $member;
	}
	
	public function sendSms($params)
	{
		$telephone = $params[0]['telephone'];
		$num = mt_rand(100000, 999999);
		$this->CI->load->model('member/interface/yuanzhou');
		$result = $this->CI->yuanzhou->isExistPhoneIdno(array('phone'=>$telephone));
	//2016-03-18 00:06 Edit by OuNianfeng 找回密码动作才需要验证会员是否存在
// 		if($result){
// 			return "该手机号未注册会员";
// 		}
		$this->CI->load->library('session');
		$this->CI->session->set_userdata('sms', $num);
		
		$this->CI->load->model('member/interface/yzsms');
		$this->CI->yzsms->sendSms($telephone,$num);
		return '发送成功!';
	}

	public function sendSetPassword($params)
	{
		$telephone = $params[0];
		$num = mt_rand(100000, 999999);
		$this->CI->load->model('member/interface/yuanzhou');
		$result = $this->CI->yuanzhou->isExistPhoneIdno(array('phone'=>$telephone));
		if($result){
			return "该手机号未注册会员";
		}
		$this->CI->load->library('session');
		$this->CI->session->set_userdata('sms', $num);
		
		$this->CI->load->model('member/interface/yzsms');
		$this->CI->yzsms->sendSms($telephone,$num);
		return '发送成功!';
	}
	
	//获取积分记录
	public function getBonusRecords($params)
	{
		$openid = $params[0];
	
		$memberObject         = $this->getMemberDetailByOpenId(array($openid));
		
	    $data['bonus'] = $data['add_bonus'] = $data['reduce_bonus'] = array();
		if($memberObject->is_active && !empty($memberObject->membership_number)) {

			$this->CI->load->model('member/interface/yuanzhou');
			$rdata = array(
				'phone' => $memberObject->telephone,
				'idno'  => $memberObject->identity_card
			);
			$result = $this->CI->yuanzhou->querywxScoreList($rdata);

			if($result) {
				foreach($result as $key=>$obj) {
				    $data['bonus'][]=(object)array('bonus'=>(string)$obj->score_debit,'create_time'=>(string)$obj->businessdate,'note'=>(string)$obj->hotelname);
					if($obj->score_debit>0) {
					    $data['add_bonus'][] = (object)array('bonus'=>(string)$obj->score_debit,'create_time'=>(string)$obj->businessdate,'note'=>(string)$obj->hotelname);
					} else {
						$data['reduce_bonus'][] = (object)array('bonus'=>(string)$obj->score_debit,'create_time'=>(string)$obj->businessdate,'note'=>(string)$obj->hotelname);
					}
				}
			}
		}

		return $data;
	}
	
	public function getBalanceRecords($params)
	{
		$openid = $params[0];
		$memInfo = $this->getMemberDetailByOpenId(array($openid));
		
		$data['balances'] = $data['pay_balances'] = $data['consume_balances'] = $data['give_balances'] = $data['earn_balances'] = array();

		if($memInfo->is_active && $memInfo->is_login && !empty($memInfo->custom2)) {
			
			$this->CI->load->model('member/interface/yuanzhou');
			$request_data = array('valuecardno'=>$memInfo->custom2);
			$result = $this->CI->yuanzhou->WxMemberLineCard($request_data);

            if($result) {
				foreach($result as $obj) {
					$data['balances'][]=(object)array(
						'balance'=>$obj->sum_money,
						'create_time'=>substr((string)$obj->business_date, 0, 10),
						'note'=>$this->getctype((int)$obj->consume_type),
						'hotel'=>(string)$obj->hotelname
					);

					if((int)$obj->consume_type==1) {
						$data['pay_balances'][]=(object)array(
							'balance'=>$obj->sum_money,
							'create_time'=>substr((string)$obj->business_date, 0, 10),
							'note'=>$this->getctype((int)$obj->consume_type),
							'hotel'=>(string)$obj->hotelname
					    );
					}
					
					if((int)$obj->consume_type==2) {
						$data['consume_balances'][]=(object)array(
							'balance'=>$obj->sum_money,
							'create_time'=>substr((string)$obj->business_date, 0, 10),
							'note'=>$this->getctype((int)$obj->consume_type),
							'hotel'=>(string)$obj->hotelname
					    );
					}
					
					if((int)$obj->consume_type==3) {
						$data['give_balances'][]=(object)array(
							'balance'=>$obj->sum_money,
							'create_time'=>substr((string)$obj->business_date, 0, 10),
							'note'=>$this->getctype((int)$obj->consume_type),
							'hotel'=>(string)$obj->hotelname
						);
					}
					
					if((int)$obj->consume_type==4) {
						$data['earn_balances'][]=(object)array(
							'balance'=>$obj->sum_money,
							'create_time'=>substr((string)$obj->business_date, 0, 10),
							'note'=>$this->getctype((int)$obj->consume_type),
							'hotel'=>(string)$obj->hotelname
						);
					}
				}
            }
		}
		$r_data['data_title'] = array('全部记录','付款','消费','赠送金额','每日收益');
		$r_data['data_record'] = array($data['balances'],$data['pay_balances'],$data['consume_balances'],$data['give_balances'],$data['earn_balances']);
		
		return $r_data;
	}
	
	protected function getctype($key)
	{
		$types = array('1'=>'付款','2'=>'消费','3'=>'赠送金额','4'=>'每日收益');
	
		if(isset($types[$key])) {
			return $types[$key];
		} else {
			return false;
		}
	}
	
	//修改密码
	public function modPassword($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		$member = $this->getMemberDetailByOpenId(array($openid));
		if(!empty($member->membership_number)) {
			$this->CI->load->model('member/interface/yuanzhou');
			$data1 = array(
				'password' => md5($data['oldpassword']),
				'phone'    => $member->telephone
			);
			$result = $this->CI->yuanzhou->isExistwxCardNew($data1);
			if($result) {
				$r_data = array(
					'wxuserid' => $member->custom1,
					'idno'     => $member->identity_card,
					'phone'    => $member->telephone,
					'newpassword' => md5($data['newpassword']),//md5($data['newpassword']),
					'oldpassword' => md5($data['oldpassword']),//md5($data['oldpassword']),
				);
				$r = $this->CI->yuanzhou->updateWxPassword($r_data);
				if($r) {
					$this->updateStatus(array($openid,false));
					return array('code'=>0,'errmsg'=>'修改密码成功，请使用新密码登录!');
				} else {
					return array('code'=>1,'errmsg'=>'修改密码失败!');
				}
			} else {
				return array('code'=>1,'errmsg'=>'修改密码失败!');
			}
		} else {
			if($member->password==md5($data['oldpassword'])) {
				$this->addMemberInfo(array($openid,array('password'=>md5($data['newpassword']))));
				$this->updateStatus(array($openid,false));
				return array('code'=>0,'errmsg'=>'修改密码成功，请使用新密码登录!');
			} else {
				return array('code'=>1,'errmsg'=>'修改密码失败!');
			}
		}
	}
	
	public function registerMember($params)
	{
		$openid           = $params[0];
		$data             = $params[1];
		$data['inter_id'] = $params[2];

		$member = $this->getMemberByOpenId(array($openid));

		$this->CI->load->model('member/interface/yuanzhou');
		/*$result = $this->CI->yuanzhou->wxqueryMemberinfo(array('phone'=>$data['telephone']));

		if($result) {
			return array('code'=>1,'errmsg'=>"该手机号已经注册会员,请直接登录!");
		}*/
		//检测手机号码和身份证号码
		$result = $this->CI->yuanzhou->isExistPhoneIdno(array('phone'=>$data['telephone']));
		if(!$result){
			return array('code'=>1,'errmsg'=>"该手机号已经注册会员,请直接登录!");
		}
		$result = $this->CI->yuanzhou->isExistPhoneIdno(array('idno'=>$data['identity_card']));
		if(!$result){
			return array('code'=>1,'errmsg'=>"该身份证已经注册会员,请直接登录!");
		}
		/*$result2 = $this->CI->yuanzhou->wxqueryMemberinfo(array('idno'=>$data['identity_card']));
		if($result2) {
			return array('code'=>1,'errmsg'=>"该身份证已经注册会员,请直接登录!");
		}*/
		
		$r_data = array(
			'wxuserid' => $member->mem_card_no,
			'username' => $data['name'],
			'phone'    => $data['telephone'],
			'idno'     => $data['identity_card'],
			'password' => md5(substr($data['identity_card'],-6))
		);
		
		$result = $this->CI->yuanzhou->wxregisterMember($r_data);
		if($result && isset($result->wxuserid) && !empty($result->wxuserid)) {
			$data['mem_id'] = $member->mem_id;
			$data['password'] = md5($data['password']);//拼错了passwrod
			$data['membership_number'] = '';
				
			$updateParams = array(
				'openid'           => $openid,
				'level'            => 0,
				'is_login'         => 0,
				'is_active'        => 0,
				'last_login_time'  => time()
			);
			$r1 = $this->getMemberModel()->updateMemberByOpenId($updateParams);
			$r2 = $this->addMemberInfo(array($openid, $data));
			if($r1 && $r2) {
				//增加注册后登录(新版完善)
				$logindata = array($openid,$data['telephone'],substr($data['identity_card'], -6),$data['inter_id']);
				$this->checklogin($logindata);
				return array('code'=>0,'errmsg'=>"注册成功！");
			} else {
				return array('code'=>1,'errmsg'=>"注册失败！".$r1."r2".$r2);
			}
		} else {
			return array('code'=>1,'errmsg'=>"注册失败！");
		}
	}
	
	/**
	 * 获取会员列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberList($params)
	{
		$limit  = $params[0];
		$offset = $params[1];
	
		$memberObjectList = $this->getMemberModel()->getMemberList($limit,$offset);
		return $memberObjectList;
	}
	
	/**
	 * 根据openid删除会员
	 * @param string $openid
	 * @return boolean|number
	 */
	public function deleteMemberByOpenId($params)
	{
		$openid = $params[0];
		try {
			$result = $this->getMemberModel()->deleteMemberByOpenId($openid);
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
	
		// 		$result = $this->createMember($openid, $data, $inter_id);
		$result = $this->createMember(array($openid, $data, $inter_id));//传参错误，作修改 @author lGh
	
		if($result) {
			// 			return $this->getMemberByOpenId($openid);
			return $this->getMemberByOpenId(array($openid));//传参错误，作修改 @author lGh
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
	public function createMember($params)
	{
		$data = $params[1];
		$data['openid'] = $params[0];
		$data['inter_id'] = $params[2];
			
		if(isset($data['is_active']))   $data['is_active'] = intval($data['is_active']);
	
		try {
			$result = $this->getMemberModel()->createMember($data);
			return $result;
		} catch (Exception $e) {
			return false;
		}
	
		return false;
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
	
	/**
	 * 更加卡券的唯一code码
	 * @param string $openid
	 * @param string $code
	 *
	 * @return bool
	 */
	public function updateCode($params)
	{
		$data = array(
				'openid'   => $params[0],
				'code'     => $params[1]
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
	 * 更新会员成长值
	 * @param string $openid
	 * @param int $growth
	 * @return unknown|boolean
	 */
	public function updateGrowth($params)
	{
		$data = array(
				'openid'     => $params[0],
				'growth'     => $params[1]
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
	 * 增加成长值
	 * @param unknown $openid
	 * @param unknown $growth
	 * @return unknown|boolean
	 */
	public function addGrowth($params)
	{
		try {
			$data = array(
					'openid'     => $params[0],
					'growth'     => $params[1]
			);
			$result = $this->getMemberModel()->updateGrowth($data, true);
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
	
	/**
	 * 减少成长值
	 * @param unknown $openid
	 * @param unknown $growth
	 * @return unknown|boolean
	 */
	public function reduceGrowth($params)
	{
		try {
			$data = array(
					'openid'     => $params[0],
					'growth'     => $params[1]
			);
			$result = $this->getMemberModel()->updateGrowth($data, false);
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
	
	/**
	 * 更新会员储值金额
	 * @param string $openid
	 * @param int $balance
	 * @return unknown|boolean
	 */
	public function updateBalance($params)
	{
		$data = array(
				'openid'     => $params[0],
				'balance'    => $params[1]
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function addBalance($params)
	{
		try {
			$data = array(
					'openid'     => $params[0],
					'balance'     => $params[1]
			);
			$note     = $params[2];
			$order_id = $params[3];
			$inter_id = $params[4];
			$result = $this->getMemberModel()->updateBalance($data, true, $note, $order_id, $inter_id);
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
	
	public function reduceBalance($params)
	{
		try {
			$data = array(
					'openid'      => $params[0],
					'balance'     => $params[1]
			);
			$note     = $params[2];
			$order_id = $params[3];
			$inter_id = $params[4];
			$result = $this->getMemberModel()->updateBalance($data, false, $note,$order_id,$inter_id);
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
	
	/**
	 * 更新积分
	 * @param string $openid
	 * @param int $bonus
	 * @return unknown|boolean
	 */
	public function updateBonus($params)
	{
		$data = array(
				'openid'     => $params[0],
				'bonus'      => $params[1]
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function refund($params)
	{
		$openid   = $params[0];
		$order_id = $params[1];
		$note     = $params[2];
		$type     = $params[3];
		$inter_id = $params[4];
	
		try {
			$result = $this->getMemberModel($inter_id)->refund($openid, $order_id, $note, $type, $inter_id);
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
	
	public function addBonusByRule($params)
	{
		$openid       = $params[0];
		$category     = $params[1];
		$num          = $params[2];
		$note         = $params[3];
		$order_id     = $params[4];
		$member_level = $params[5];
		$inter_id     = $params[6];
		$type         = $params[7];
	
		try {
			$result = $this->getMemberModel($inter_id)->addBonusByRule($openid, $category, $num, $note, $order_id, $member_level, $inter_id, $type);
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
	
	public function reduceBonus($params)
	{
		$openid       = $params[0];
		$bonus        = $params[1];
		$note         = $params[2];
		$order_id     = $params[3];
		$inter_id     = $params[4];
	
		try {
			
			//@Editor lGh 2016-5-30 02:02:58 远洲积分支付
			//<wxuserid></wxuserid><!—微信会员号-->
			// <point_credit></point_credit><!—扣减的积分-->
			// <entid></entid>
					
			$this->CI->load->model('member/interface/yuanzhou');
			$orderids=explode(',', $params[3]);
			$data = array(
					'cardno'=>$orderids[1],
					'point_credit'=>$bonus*-1,
					'entid'=>$orderids[2]
			);
			$result = $this->CI->yuanzhou->WxdeductPoints($data);
			if ($result==TRUE){
				$data = array(
						'openid'     => $openid,
						'bonus'      => $bonus
				);
				$result = $this->getMemberModel()->updateBonus($data, false, $note,$order_id,$inter_id);
				return true;
			}else {
				return false;
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
	
	/**
	 * 更新等级
	 * @param string $openid
	 * @param int $level
	 * @return unknown|boolean
	 */
	public function updateLevel($params)
	{
		$openid       = $params[0];
		$level        = $params[1];
	
		$data = array(
				'openid'     => $openid,
				'level'      => $level
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
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
	 * 更新有效时间
	 * @param unknown $openid
	 * @param unknown $begin
	 * @param unknown $end
	 * @return unknown|boolean
	 */
	public function updateValidity($params)
	{
		$openid        = $params[0];
		$begin         = $params[1];
		$end           = $params[2];
	
		$data = array(
				'openid'                 => $openid,
				'activate_begin_time'    => $begin,
				'activate_end_time'      => $end
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------------
	
	// 	public function upgradeLevel($openid)
	// 	{
	// 		try {
	// 			return $this->getMemberModel()->upgradeLevel($openid);
	// 		} catch (Exception $e) {
	// 			$error = new stdClass();
	// 			$error->error = true;
	// 			$error->message = $e->getMessage();
	// 			$error->code = $e->getCode();
	// 			$error->file = $e->getFile();
	// 			$error->line = $e->getLine();
	// 			return $error;
	// 		}
	
	// 		return false;
	// 	}
	
	
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
	
	public function getMemberLevel($params)
	{
		$member = $params[0];
        /*强行处理没有会员卡号码的,或者没有登录的*/
        if(empty($member->membership_number) || ($member->is_login == 0 )){
            $member->level = 0;
        }
		try {
			$this->getMemberModel()->getMemberLevel($member);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return $this;
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
	 * 获取所有会员详细信息列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberDetailList($params)
	{
		$limit  = $params[0];
		$offset = $params[1];
		$where  = $params[2];
	
		$memberObjectList = $this->getMemberModel()->getMemberDetailList($limit, $offset, $where);
		return $memberObjectList;
	}
	
	public function getMemberDetailListNumber($limit=null, $offset=null, $where=null, $inter_id='')
	{
		$memberObjectList = $this->getMemberModel($inter_id)->getMemberDetailList($limit, $offset, $where, array(array('mem_id')));
		return count($memberObjectList);
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
	
	public function getMemberInfoByMemId($params)
	{
		$memid  = $params[0];
		try {
			$memberinfoObject = $this->getMemberModel()->getMemberInfoById($memid, 'mem_id');
			return $memberinfoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 获取会员附加信息列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberInfoList($params)
	{
		$limit  = $params[0];
		$offset = $params[1];
		$memberInfoObjectList = $this->getMemberModel()->getMemberInfoList($limit,$offset);
		return $memberInfoObjectList;
	}
	
	/**
	 * 根据OpenId删除会员附加资料
	 * @param string $openid
	 * @return unknown|boolean
	 */
	public function deleteMemberInfoByOpenId($params)
	{
		$openid  = $params[0];
		try {
			$result = $this->getMemberModel()->deleteMemberInfoByOpenId($openid);
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
		$data = $params [1];
		if (isset ( $data ['membership_number'] ))//2016-03-18 11:39 Edit by OuNianfeng 
			$data ['membership_number'] = str_replace ( ' ', '', $data ['membership_number'] );
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
	public function updateMemberInfoCardNumber($params)
	{
		$openid = $params[0];
		$card_number = $params[1];
	
		$data = array(
				'openid'                 => $openid,
				'membership_number'      => $card_number
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员名字
	 * @param string $openid
	 * @param string $name
	 * @return unknown|boolean
	 */
	public function updateMemberInfoName($params)
	{
		$openid = $params[0];
		$name = $params[1];
	
		$data = array(
				'openid'    => $openid,
				'name'      => $name
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员性别
	 * @param unknown $openid
	 * @param unknown $sex
	 * @return unknown|boolean
	 */
	public function updateMemberInfoSex($params)
	{
		$openid = $params[0];
		$sex    = $params[1];
	
		$data = array(
				'openid'    => $openid,
				'sex'       => $sex
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员出生日期
	 * @param unknown $openid
	 * @param unknown $dob
	 * @return unknown|boolean
	 */
	public function updateMemberInfoDob($params)
	{
		$openid = $params[0];
		$dob    = $params[1];
	
		$data = array(
				'openid'    => $openid,
				'dob'       => $dob
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员电话号码
	 * @param unknown $openid
	 * @param unknown $telephone
	 * @return unknown|boolean
	 */
	public function updateMemberInfoTelephone($params)
	{
		$openid       = $params[0];
		$telephone    = $params[1];
	
		$data = array(
				'openid'         => $openid,
				'telephone'      => $telephone
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员QQ
	 * @param unknown $openid
	 * @param unknown $qq
	 * @return unknown|boolean
	 */
	public function updateMemberInfoQQ($params)
	{
		$openid       = $params[0];
		$qq           = $params[1];
	
		$data = array(
				'openid'    => $openid,
				'qq'        => $qq
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员邮件
	 * @param unknown $openid
	 * @param unknown $email
	 * @return unknown|boolean
	 */
	public function updateMemberInfoEmail($params)
	{
		$openid       = $params[0];
		$email        = $params[1];
	
		$data = array(
				'openid'    => $openid,
				'email'     => $email
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateMemberInfoById($params)
	{
		$ma_id       = $params[0];
		$data        = $params[1];
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoById($ma_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员身份证
	 * @param unknown $openid
	 * @param unknown $idcard
	 * @return unknown|boolean
	 */
	public function updateMemberInfoIdcard($params)
	{
		$openid        = $params[0];
		$idcard        = $params[1];
	
		$data = array(
				'openid'            => $openid,
				'identity_card'     => $idcard
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员地址
	 * @param string $openid
	 * @param string $address
	 * @return unknown|boolean
	 */
	public function updateMemberInfoAddress($params)
	{
		$openid        = $params[0];
		$address       = $params[1];
	
		$data = array(
				'openid'      => $openid,
				'address'     => $address
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
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
	public function updateMemberInfoCustom($params)
	{
		$data['openid']            = $params[0];
		$data['custom'.$params[2]] = $params[1];
	
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function getMemberModel()
	{
		if(!isset($this->_memberModel)) {
			$this->CI->load->model('member/member');
			$this->_memberModel = $this->CI->member;
		}
		return $this->_memberModel;
	}
}