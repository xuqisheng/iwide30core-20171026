<?php
//测试的链接，用default_pms
//http://ihotels.iwide.cn/index.php/hotel/hotel/search?id=a429262687&openid=oX3WojhfNUD4JzmlwTzuKba1MywY

//速8的pms
//http://ihotels.iwide.cn/index.php/hotel/hotel/search?id=a455510007&openid=oX3WojhfNUD4JzmlwTzuKba1Myse
//http://localhost/iwide/www_front/index.php/hotel/hotel/search?id=a455510007&openid=oX3WojhfNUD4JzmlwTzuKba1Myse
//http://localhost/iwide/www_front/index.php/hotel/hotel/index?id=a455510007&h=1099&start=2016/04/24&end=2016/04/25
class Suba_webservice implements IPMS {
	protected $CI;

	protected $pms_member_no;

	function __construct($params) {
		$this->CI = & get_instance ();
		$this->pms_set = $params ['pms_set'];
	}
	public function get_orders($inter_id, $status, $offset, $limit) {
	}
	public function get_hotels($inter_id, $status, $offset, $limit) {
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$this->CI->load->model ( 'hotel/pms/Suba_hotel_model', 'pms' );
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : null;
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Suba_hotel_model', 'pms' );

		//print_r($params)

		//$memberObject = $this->getMemberModel ()->getMemberDetailById ( $openid );

		//$this->pms_member_no = $memberObject->membership_number;

		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}

	public function add_web_bill($order,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Suba_hotel_model', 'pms' );
// 		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		$third_no=empty( $params['third_no'])?'': $params['third_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $this->pms_set, $third_no);
	}

	function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Suba_hotel_model', 'pms' );
		return $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
	}
	function update_web_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Suba_hotel_model', 'pms' );
		return $this->CI->pms->update_web_order ( $inter_id, $order,$this->pms_set );
	}
	public function get_new_hotel($params = array()) {
		echo '';
	}
	public function check_openid_member($inter_id, $openid, $paras) {

		//速8同步会员信息

		$this->CI->load->model('member/member');
		$this->CI->load->model('hotel/Member_model');
		$this->CI->Member_model->check_openid_member ( $inter_id, $openid, $paras );
		$userInfo = $this->CI->member->getMemberDetailById ( $openid );

		if(isset($userInfo->membership_number) && !empty($userInfo->membership_number)){
			$userInfo->mem_card_no = $userInfo->membership_number;

			$this->loginWithOpenid(array($openid,$inter_id,0));//同步信息
		}else{
			$res = $this->loginWithOpenid(array($openid,$inter_id,0));

			if(!empty($res)){
				$userInfo->mem_card_no = $res['MainCardNO'];
			}else{
				$userInfo->mem_card_no='';
			}
		}

		/*
		$memberObject = $this->getMemberModel ()->getMemberDetailById ( $openid );
		/* print_r($memberObject);
		exit; */
		/* if($memberObject->is_login == 1){

			$this->$pms_member_no = $memberObject->membership_number;

		} */
		//$this->pms_member_no = $memberObject->membership_number;

		//echo "debug:".$this->pms_member_no; */

		return $userInfo;
	}
	public function search_hotel_front($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		return $this->CI->Suba_hotel_ext_model->search_hotel_front($params[0],$params[1],$this->pms_set);
	}
	public function get_hotel_citys($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		$params[1]['pms_set']=$this->pms_set;
		return $this->CI->Suba_hotel_ext_model->get_hotel_citys($params[0],$params[1]);
	}
	public function get_hotel_comment_count($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		$params[3]['pms_set']=$this->pms_set;
		return $this->CI->Suba_hotel_ext_model->get_hotel_comment_count($params[0],$params[1],$params[2],$params[3]);
	}
	public function get_hotel_comments($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		$params[6]['pms_set']=$this->pms_set;
		return $this->CI->Suba_hotel_ext_model->get_hotel_comments($params[0],$params[1],$params[2],$params[3],$params[4],$params[5],$params[6]);
	}
	public function add_comment($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		$params[0]['data']=$params[0];
		$params[0]['pms_set']=$this->pms_set;
		return $this->CI->Suba_hotel_ext_model->add_comment($params[0]);
	}
	public function get_city_filter($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		return $this->CI->Suba_hotel_ext_model->get_city_filter($params[0],$params[1],$params[2],$this->pms_set);
	}
	public function get_order_state($params){
		$this->CI->load->model('hotel/pms/Suba_hotel_ext_model');
		$status_des=empty($params[1])?array():$params[1];
		return $this->CI->Suba_hotel_ext_model->get_order_state($params[0],$this->pms_set,$status_des);
	}

	public function getMemberModel()
	{
		if(!isset($this->_memberModel)) {
			$this->CI->load->model('member/member');
			$this->_memberModel = $this->CI->member;
		}
		return $this->_memberModel;
	}


	/**
	 * 速8注册
	 * @param $params
	 * @return bool|null|unknown
	 */
	public function registerMember($params){
		$openid = $params[0];
		$data   = $params[1];
		$inter_id = $params[2];
		//-------------------速8注册--------------------------------
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$userName  = $data['name'];
		$password  = $data['password'];
		$telephone = $data['telephone'];
		$suba = new Subaapi_webservice(false);
		$subaMember = $suba->Register($userName, $password, $telephone);

		$subaMember['RegisterResult']['IsError']; //[IsError] => 1 注册失败，该会员信息已存在！

		if($subaMember['RegisterResult']['IsError']){
			return $subaMember['RegisterResult'];
		}
		//-------------------速8注册 end --------------------------------
		$subaMemberInfo = $subaMember['RegisterResult']['Content'];
		$data['name'] = $subaMemberInfo['CustomeName'];
		$data['telephone'] = $subaMemberInfo['PhoneNum'];
		$data['membership_number'] = $subaMemberInfo['MainCardNO'];
		$data['level'] = $data['member_type'] = $subaMemberInfo['MainCardTypeID'];

		//---------------- 绑定会员卡 start---------------------
		$bindRs = $suba->BindWeixinCustomer($openid,$data['membership_number']);
		if($bindRs['BindWeixinCustomerResult']['IsError']){
			$this->CI->session->set_userdata('message', "微信绑定会员卡失败！");
			return FALSE;
		}

		$this->updateStatus(array($openid,1));
		$this->CI->load->model ( 'member/member' );

		//登录
		$this->CI->member->updateMemberByOpenId(
			array(
				'openid'=>$openid,
				'level' => $data['level'],
				'is_login'=> 1
			),
			$inter_id
		);

		$result = $this->addMemberInfo( array($openid, $data));

		/*增加绩效*/
		$this->CI->load->model ( 'hotel/pms/suba_hotel_model' );
		$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
		$this->CI->suba_hotel_model->addRegisterDistribute($inter_id,$openid,$memberObject->ma_id,$data['membership_number']);

		return $result;

	}

	/**
	 * 添加会员详细资料
	 * @param string $openid
	 * @param array $data
	 * @return unknown|boolean
	 */
	public function addMemberInfo($params)
	{

		$data  = $params[1];
		$data['openid'] = $params[0];

		try {
			if(isset($data['password'])){
				unset($data['password']);
			}
			$result = $this->updateMemberInfoByOpenId($data);
			return $result;
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
	 * //同步修改速8会员
	 * @param $params
	 * @return bool
	 */
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
	 * 根据OpenId添加更新会员资料
	 * @param array $data
	 * @return bool
	 */
	public function updateMemberInfoByOpenId($data)
	{
		try {
			$openid = strval($data['openid']);
			if($this->getMemberModel()->checkInfoData($data)) {
				$memberModel = $this->getMemberModel();
				$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
				if($memberObject) {
					$writeAdapter = $this->CI->load->database('member_write',true);
					if(isset($memberObject->ma_id) && !empty($memberObject->ma_id)) {
						$result = $writeAdapter->update($memberModel::TABLE_MEMBER_INFO, $data, array('ma_id' => $memberObject->ma_id));

						if($result){
							$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
							$suba = new Subaapi_webservice(false);
							$memberInfo = $this->getMemberModel()->getMemberInfoById($openid);
							if(!empty($memberInfo)){
								$cardNum = $memberInfo->membership_number;
								if(!empty($cardNum)){

									$telephone = $memberInfo->telephone; //不修改电话

									if(isset($data['email'])){
										$email = $data['email'];
									}
									if(isset($data['name'])){
										$name = $data['name'];
									}else{
										$name = $memberInfo->name;
									}
									if(isset($data['sex'])){
										$sex = $data['sex'];
									}else{
										$sex = $memberInfo->sex;
									}
									$suba->ModifyCustomer($cardNum,$name, $telephone,$sex,$email);
								}
							}
						}

						return $result;
					} else {
						$data['mem_id'] = $memberObject->mem_id;
						return $writeAdapter->insert(self::TABLE_MEMBER_INFO,$data);
					}
				} else {
					throw new Exception("会员不存在!");
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			echo $e->getMessage();exit;
			throw new Exception($e->getMessage());
		}

		return false;
	}

	//登录
	public function checklogin($params){
		$openid = $params[0];
		$account = $params[1];
		$password = $params[2];
		$inter_id = $params[3];
		$hotel_id = $params[4];

		//-------------------速8登录--------------------------------
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);

		$subaMember = $suba->login($account, $password);

		if($subaMember['LoginResult']['IsError']){
			$this->CI->session->set_userdata('message', "账号或密码错误！");
			return FALSE;
		}
		//-------------------速8登录 end --------------------------------
		//会员基本信息
		$subaMemberInfo = $subaMember['LoginResult']['Content'];
		$data['name'] = $subaMemberInfo['CustomeName'];
		$data['telephone'] = $subaMemberInfo['PhoneNum'];
		$mainCardNo = $data['membership_number'] = $subaMemberInfo['MainCardNO'];
		$data['level'] = $data['member_type'] = $subaMemberInfo['MainCardTypeID'];
		$data['sex'] = $subaMemberInfo['Gender'];
		$bonus = $subaMemberInfo['UsablePoints'];//可用积分
		$usableAmount = $subaMemberInfo['UsableAmount'];//可用余额


		//---------------- 绑定会员卡 start---------------------
		$bindRs = $suba->BindWeixinCustomer($openid,$mainCardNo);
		if($bindRs['BindWeixinCustomerResult']['IsError']){
			$this->CI->session->set_userdata('message', "微信绑定会员卡失败！");
			return FALSE;
		}


		$this->CI->load->model ( 'member/member' );

		$updateResult = $this->CI->member->updateMemberByOpenId(
			array(
				'openid'=>$openid,
				'bonus'=> $bonus,
				'balance'=> $usableAmount,
				'mem_card_no' => $mainCardNo,
				'level' => $data['level'],
				'is_login'=> 1
			),
			$inter_id,
			$hotel_id
		);
		$data['openid'] = $openid;
		$this->CI->member->updateMemberInfoByOpenId($data);
		if($updateResult){

			/**增加绩效*/
			$this->CI->load->model ( 'hotel/pms/suba_hotel_model' );
			$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
			$this->CI->suba_hotel_model->addRegisterDistribute($inter_id,$openid,$memberObject->ma_id,$data['membership_number']);


			return $updateResult;
		}else{
			$this->CI->session->set_userdata('message', "同步资料失败！");
		}



	}


	//解绑会员卡
	public function unBindMemberCard($params){
		$openid = $params[0];
		//-------------------速8会员卡--------------------------------
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);
		$rs = $suba->UnBindWeixinCustomer($openid);
		if(isset($rs['UnBindWeixinCustomerResult']) && ($rs['UnBindWeixinCustomerResult']['ResultCode']=='00')){
			$this->CI->load->model('member/member','member');
			$data['membership_number'] = '';
			$data['openid'] = $openid;
			$this->CI->member->updateMemberInfoByOpenId($data);
		}else{
			return true;
		}

	}

	//获取会员卡优惠券
	function getIgetcard($param){

		$openid = $param[0];

		//-------------------速8会员卡--------------------------------
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);

		$memberInfo = $this->getMemberModel()->getMemberById($openid);

		if(empty($memberInfo)) return array();
		else if(!$memberInfo->is_login) return array();

		$memberAdditional = $this->getMemberModel()->getMemberInfoById($openid);

		if(empty($memberAdditional)) return array(); //没有会员信息

		$cardNum = $memberAdditional->membership_number;

		if(empty($cardNum)) return array();  //没有会员卡

		$subaCards = $suba->GetCoupons($cardNum);

		if(!isset($subaCards['GetCouponsResult']) || $subaCards['GetCouponsResult']['IsError']) return array();//速8返回有误

		if(empty($subaCards['GetCouponsResult']['Content']['ListContent'])){
			$cards = array();
		}else{
			$cards = $subaCards['GetCouponsResult']['Content']['ListContent']['CardCoupon'];
		}
		return $cards;

	}

	/**
	 * //同步速8会员信息
	 * @param $openid
	 */
	public function sycMemberInfo($params){
		$openid = $params[0];
		$inter_id = $params[1];
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);

		$memberAdditional = $this->getMemberModel()->getMemberInfoById($openid);
		if(empty($memberAdditional)) return FALSE; //没有会员信息
		$cardNum = $memberAdditional->membership_number;
		if(empty($cardNum)) return FALSE;  //没有会员卡

		$subaMember = $suba->GetCustomer($cardNum);

		if($subaMember['GetCustomerResult']['ResultCode']== '00'){  //获取成功

			$subaMemberInfo = $subaMember['GetCustomerResult']['Content'];
			$data['name'] = $subaMemberInfo['CustomeName'];
			$data['telephone'] = $subaMemberInfo['PhoneNum'];
			$data['membership_number'] = $subaMemberInfo['MainCardNO'];
			$data['level'] = $data['member_type'] = $subaMemberInfo['MainCardTypeID'];

			$bonus = $subaMemberInfo['TotalPoints'];//积分
			$usableAmount = $subaMemberInfo['UsableAmount'];//余额

			$this->CI->load->model ( 'member/member' );

			$updateResult = $this->CI->member->updateMemberByOpenId(
				array(
					'openid'=>$openid,
					'level' =>  $data['level'],
					'bonus'=> $bonus,
					'balance'=> $usableAmount
				),
				$inter_id
			);

			return $updateResult;
		}else{
			return FALSE;
		}

	}


	//authorBy Jake
	public function couponCardList($params){
		$data['cards'] = $this->getRulesByParams($params);
		$data['view'] = 'member/super8/super8_cardlist';
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
			if(!$memberInfoObject->is_login){
				$memberInfoObject->balance = 0.00;
				$memberInfoObject->bonus = 0;
			}
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

	//@author Jake
	/**
	 * 订房优惠券返回
	 * @param $params
	 * @return object
	 */
	public function getRulesByParams($params){
		$coupons = $this->getIgetcard($params);

		$cards = array();
		$cardsObj = array();
		foreach($coupons as $k => $v){

			$cards = (object) $cards;

			$cards->code = $v['CouponNo'];
			$cards->reduce_cost = $v['Amount'];
			$cards->ci_id = $v['CouponInfoID'];
			$cards->title = $v['CouponName'];
			$cards->brand_name = '速8连锁酒店';
			$cards->status = 1;
			$cards->restriction = array('room_nights' => 1);
			$cards->date_info_end_timestamp = strtotime($v['ExpiryDate']);
			$cards->extra =  $v['CouponInfoID'];
			$cards->card_id = 0;

			$cardsObj[$k] = $cards;
			$cards = array();
		}
		return $cardsObj;
	}

	/**
	 * /更改代金券状态
	 * @param $params
	 */
	public function updateGcardStatus($params){

		return true;

	}

	/**
	 * 获取会员级别名字
	 * @param int $MainCardTypeID
	 * @return mixed
	 */
	public function getCardTypeName($MainCardTypeID = 0){
		$typeNameArr = array(
			'微信会员',
			'网络会员',
			'贵宾会员',
			'金卡会员',
			'超级会员',
			'协议公司88折',
			'协议公司85折',
			'协议公司82折'

		);
		return $typeNameArr[$MainCardTypeID];
	}

	/**
	 * //获取会员级别
	 * @param $params
	 * @return mixed
	 */
	public function getMemberLevel($params){
		$member = $params[0];
		$member->levelinfo = $this->getCardTypeName($member->level);
		return $member;
	}

	//获取积分记录
	/**
	 * @param $params
	 * @return mixed
	 */
	public function getBonusRecords($params)
	{
		$openid = $params[0];
		$data['bonus'] =  $data['add_bonus'] = $data['reduce_bonus'] = array();

		$memberObject         = $this->getMemberModel()->getMemberByOpenId($openid);

		if(!$memberObject->is_login){ //未登录
			return $data;
		}else{
			$memberInfo = $this->getMemberModel()->getMemberInfoById($openid);
			$cardNum = $memberInfo->membership_number;
			//没有绑定会员卡
			if(empty($cardNum)){
				return $data;
			}

			$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
			$suba = new Subaapi_webservice(false);

			$pointsListObj = $suba->GetPoints($cardNum);
			if(isset($pointsListObj['GetPointsResult']) && $pointsListObj['GetPointsResult']['ResultCode'] == '00'){

				if( empty($pointsListObj['GetPointsResult']['Content']['ListContent']))
					return $data;
				else{
					$cardPointsContent = $pointsListObj['GetPointsResult']['Content'];

					if($cardPointsContent['TotalCount'] == 1){
						$cardPoints[] =  $cardPointsContent['ListContent']['CardPoint'];
					}else{
						$cardPoints =  $cardPointsContent['ListContent']['CardPoint'];
					}
					foreach($cardPoints as $point){
						switch($point['Direction']){
							case 1:
								$data['add_bonus'][] =  $this->formatPointArray($point,$memberInfo->inter_id,$memberInfo->mem_id);
								$data['bonus'][] =$this->formatPointArray($point,$memberInfo->inter_id,$memberInfo->mem_id);
								break;
							case 2:
								$data['reduce_bonus'][] =  $this->formatPointArray($point,$memberInfo->inter_id,$memberInfo->mem_id);
								$data['bonus'][] =$this->formatPointArray($point,$memberInfo->inter_id,$memberInfo->mem_id);
								break;
							default:
								continue;
								break;
						}
					}
				}
			}else{
				return $data;
			}
		}
		if(!empty($data['bonus']))        rsort($data['bonus']);
		if(!empty($data['add_bonus']))    rsort($data['add_bonus']);
		if(!empty($data['reduce_bonus'])) rsort($data['reduce_bonus']);
		return $data;
	}


	/**
	 * 积分格式化
	 * @param $pointArr
	 * @param $inter_id
	 * @param $memberId
	 * @return object
	 */
	public function formatPointArray($pointArr,$inter_id,$memberId){
		$pointArr['inter_id'] = $inter_id;
		$pointArr['mem_id'] = $memberId;
		$pointArr['balance'] = 0;

		$point = (object) array(
			'inter_id' => $inter_id
		);

		$dataFiled = array(
			'Direction'   =>  'type',
			'inter_id'   => 'inter_id',
			'OrderNO'    => 'order_id',
			'mem_id'    => 'mem_id',
			'balance'   =>'balance',
			'RoomDayPoints'=>'bonus',
			'Source'    => 'on_offline',
			'CumulativeDesc'   =>'note',
			'CreateTime'    => 'create_time'
		);

		foreach($dataFiled as $k => $v){
			$point->$v  = $pointArr[$k];
		}
		if( $point->type == 2){
			$point->bonus = '-'.$point->bonus;
		}elseif($point->type == 1){
			$point->bonus = '+'.$point->bonus;
		}

		return $point;

	}


	/**
	 * 余额记录
	 * @param $params
	 * @return mixed
	 */
	public function getBalanceRecords($params)
	{
		$openid = $params[0];

		$data['balances'] =  $data['add_balances'] = $data['reduce_balances'] = array();

		$memberObject         = $this->getMemberModel()->getMemberByOpenId($openid);

		if(!$memberObject->is_login){ //未登录
			return $data;
		}else{
			$memberInfo = $this->getMemberModel()->getMemberInfoById($openid);
			$cardNum = $memberInfo->membership_number;
			//没有绑定会员卡
			if(empty($cardNum)){
				return $data;
			}

			$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
			$suba = new Subaapi_webservice(false);

			$addBalanceObj = $suba->GetAccounts($cardNum,101);
			$reduceBalanceObj = $suba->GetAccounts($cardNum,102);

			if($addBalanceObj['GetAccountsResult']['ResultCode'] == '00'){
				if( isset($addBalanceObj['GetAccountsResult'])
				    && ($addBalanceObj['GetAccountsResult']['ResultCode'] == '00')){
					if( isset($addBalanceObj['GetAccountsResult']['Content']) && ($addBalanceObj['GetAccountsResult']['Content']['TotalCount'] <=0) )
						$data['add_balances'] = array();
					else{
						if($addBalanceObj['GetAccountsResult']['Content']['TotalCount'] ==1){
							$cardsArr[] = $addBalanceObj['GetAccountsResult']['Content']['ListContent']['CardAccount'];
						}else{
							$cardsArr = $addBalanceObj['GetAccountsResult']['Content']['ListContent']['CardAccount'];
						}
						foreach($cardsArr as $v){
							$balance = $this->formatBalanceArray($v,$memberInfo->inter_id,$memberInfo->mem_id);
							$balance->balance = '+'.$balance->balance;
							$data['add_balances'][] = $balance;
							$data['balances'][strtotime($balance->info_time)] = $balance;
						}
					}
				}else{
					$data['add_balances'] = array();
				}
			}else{
				$data['add_balances'] = array();
			}

			$cardsArr = array();
			if(isset($reduceBalanceObj['GetAccountsResult'])
			   && ($reduceBalanceObj['GetAccountsResult']['ResultCode'] == '00')){
				if( isset($reduceBalanceObj['GetAccountsResult']['Content']) && ($reduceBalanceObj['GetAccountsResult']['Content']['TotalCount'] <=0) )
					$data['reduce_balances'] = array();
				else{
					if($reduceBalanceObj['GetAccountsResult']['Content']['TotalCount'] ==1){
						$cardsArr[] = $reduceBalanceObj['GetAccountsResult']['Content']['ListContent']['CardAccount'];
					}else{
						$cardsArr = $reduceBalanceObj['GetAccountsResult']['Content']['ListContent']['CardAccount'];
					}
					foreach($cardsArr as $v){
						$balance = $this->formatBalanceArray($v,$memberInfo->inter_id,$memberInfo->mem_id,'reduce');
						$balance->balance = '-'.$balance->balance;

						//消费取消显示有效
						if(isset($balance->status)) unset($balance->status); //消费取消显示有效

						$data['reduce_balances'][] = $balance;
						$data['balances'][strtotime($balance->info_time)] = $balance;
					}
				}
			}

		}
		$r_data['data_title'] = array('全部记录','充值记录','消费记录');
		$r_data['data_record'] = array($data['balances'],$data['add_balances'],$data['reduce_balances']);

		return $r_data;
	}

	/**
	 * //格式化
	 * @param $balanceArr
	 * @param $inter_id
	 * @param $member_id
	 */
	public function formatBalanceArray($balanceArr,$inter_id,$member_id,$type='add'){
		$balanceArr['inter_id'] = $inter_id;
		$balanceArr['mem_id'] = $member_id;
		$balanceArr['balance'] = 0;

		$balance = (object) array(
			'inter_id' => $inter_id
		);

		if($type=='add'){
			$dataFiled = array(
				'Direction'   =>  'type',
				'inter_id'   => 'inter_id',
				'OrderID'    => 'order_id',
				'mem_id'    => 'mem_id',
				'Amounts'   =>'balance',
				'Status'=>'status',
				'Source'    => 'on_offline',
				'CumulativeDesc'   =>'note',
				'FailDate'    => 'create_time',
				'CreateTime'  => 'info_time'
			);
		}else{
			$dataFiled = array(
				'Direction'   =>  'type',
				'inter_id'   => 'inter_id',
				'OrderID'    => 'order_id',
				'mem_id'    => 'mem_id',
				'Amounts'   =>'balance',
				'Status'=>'status',
				'Source'    => 'on_offline',
				'CumulativeDesc'   =>'note',
				'CreateTime'    => 'create_time',
			);
		}


//        print_r($balanceArr);exit;
		foreach($dataFiled as $k => $v){
			if($k == 'FailDate' && $type=='add'){
				$balance->$v  = '有效期：'. $balanceArr[$k];
			}elseif($type=='reduce' && $k == 'CreateTime'){
				$balance->info_time =  $balanceArr[$k];
				$balance->$v  = $balanceArr[$k];
			}else{
				$balance->$v  = $balanceArr[$k];
			}
		}
		return $balance;
	}

	/**
	 * //发送重置密码
	 * @param $params
	 */
	public function sendSetPassword($params){
		$telephone = $params [0];
//        $rand = $params [1];
//        $inter_id = $params[2];
		//发送
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);

		//获取ip
		$this->CI->load->helper('common');
		$ip = getIp();

		$sendResult = $suba->SendVerifySMS($telephone, $ip, $suba::SEND_SET_PASSWORD_MSG);

		if($sendResult['SendVerifySMSResult']['ResultCode']=='00'){
			$verifyCode = $sendResult['SendVerifySMSResult']['Content']['VerifyCode'];
//            $_SESSION[$inter_id]['resetPwd']['tel'] = $telephone;
//            $_SESSION[$inter_id]['resetPwd']['sms'] = $verifyCode;
			$_SESSION['sms'] = $verifyCode;
			echo '发送成功，请注意查收短信';
		}else{
			echo empty($sendResult['SendVerifySMSResult']['Message'])?'发送失败':$sendResult['SendVerifySMSResult']['Message'];
		}

	}

	/**
	 * //发送验证码
	 * @param $params
	 * @return string
	 */
	public function sendSms($params)
	{
		$phone = $params[0]['telephone']; //电话号码

		if(isset($params[0]['picCode'])){
			$code = $params[0]['picCode'];  //图片验证码
		}

		$resArr = array(
			'status'    => 0, //默认为0，错误
			'msg'       =>  ''
		);

		/*
		if(!$this->CI->session->has_userdata('code')){
			$resArr['msg'] = '请输入图片验证码';
			return  json_encode($resArr);
		}elseif($this->CI->session->code != $code){
			$resArr['msg'] = '图片验证码不正确';
			return  json_encode($resArr);
		}
		*/
		$this->CI->load->model('member/sms','sms');
		$this->CI->sms->setLog();

		//发送速8短信
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);
//        $content = '尊敬的用户，您好。验证码为：'.$code."。验证码有效时间为5分钟。";
		$this->CI->load->helper('common');
		$ip = getIp();

		if(isset($params[0]['VerifyType'])){
			$verifyType = $params[0]['VerifyType'];
		}else{
			$verifyType = $suba::SEND_VERIFY_MSG;
		}

		if(!empty($params[0]['Forced'])){
			$Forced=true;
		}else{
			$Forced=false;
		}

		$result = $suba->SendVerifySMS($phone,$ip,$verifyType,$Forced);
		if(isset($result['SendVerifySMSResult'])){
			if(($result['SendVerifySMSResult']['ResultCode'] == '00')){
				$num = $result['SendVerifySMSResult']['Content']['VerifyCode'];
				$this->CI->session->set_userdata('sms', $num);
				$resArr['status'] = 1;
				$resArr['msg']  = '发送成功，请注意查收短信';
				//重置图片验证码，防刷
				$this->CI->session->set_userdata('code', rand(10000000,999999999999));
			}else{
				$resArr['msg']  =  $result['SendVerifySMSResult']['Message'];
			}
		}
		return  json_encode($resArr);
	}

	/**
	 * //验证短信验证码
	 * @param $params
	 * @return int
	 */
//    public function checkSendSms($params){
//        $verifyCode = $params[1];
//        $inter_id = $params[2];
//        if(!$_SESSION['sms']) {
//            return  0;
//        } elseif($_SESSION['sms']== $verifyCode) {
//            return 1;
//        }
//    }

	public function loginWithOpenid($params){
		$openid = $params[0];
		$inter_id  = $params[1];

		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);

		$res = $suba->GetWeixinCustomer($openid);
		if(isset($res['GetWeixinCustomerResult'])
		   && $res['GetWeixinCustomerResult']['IsError'] != true 
		   && ($res['GetWeixinCustomerResult']['ResultCode'] == '00')
		   && !empty($res['GetWeixinCustomerResult']['Content'])){

			$subaMemberInfo = $res['GetWeixinCustomerResult']['Content'];

			$data['name'] = $subaMemberInfo['CustomeName'];
			$data['telephone'] = $subaMemberInfo['PhoneNum'];
			$mainCardNo = $data['membership_number'] = $subaMemberInfo['MainCardNO'];
			$data['level'] = $data['member_type'] = $subaMemberInfo['MainCardTypeID'];
			$data['sex'] = $subaMemberInfo['Gender'];
			$bonus = $subaMemberInfo['UsablePoints'];//可用积分
			$usableAmount = $subaMemberInfo['UsableAmount'];//可用余额

			$this->CI->load->model ( 'member/member' );
			$updateResult = $this->CI->member->updateMemberByOpenId(
				array(
					'openid'=>$openid,
					'bonus'=> $bonus,
					'balance'=> $usableAmount,
					'mem_card_no' => $mainCardNo,
					'level' => $data['level'],
					'is_login'=> 1
				),
				$inter_id,
				0
			);
			$data['openid'] = $openid;
			$this->CI->member->updateMemberInfoByOpenId($data);
			return $subaMemberInfo;
		}else{
			return array();
		}
	}

	/**
	 * //速8更新密码
	 * @param $params
	 * @return array
	 */
	public function updatePassWordin($params) {
		$openid        = $params[0];
		$telephone     = $params[1]['telephone'];
		$sms           = $params[1]['sms'];
		$newPassword = array_pop($params[1]);

		if(empty($telephone)){
			return array('code'=>1,'errmsg'=>"请填写手机号码");
		}
		$this->CI->load->helper('validate');
//        if(!check_phone($telephone)){
//            return array('code'=>1,'errmsg'=>"请填写正确的手机号码");
//        }
		if(empty($sms)){
			return array('code'=>1,'errmsg'=>"请填写短信验证码");
		}
		if(empty($newPassword)){
			return array('code'=>1,'errmsg'=>"请填写新密码");
		}

		//更新
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);

		$restResult = $suba->ForgetPassword($telephone, $newPassword,$sms);

		if($restResult['ForgetPasswordResult']['ResultCode'] == '00'){
			return array('code'=>0,'errmsg'=>"密码已经重置，请用新密码登录!");
		}else{
			return array('code'=>1,'errmsg'=>$restResult['ForgetPasswordResult']['Message']);
		}

		//$identity_card = $params[1]['identity_card'];

		return array('code'=>0,'errmsg'=>"新密码已经发送到您手机号，请用新密码登录!");
	}

	/**
	 * 取酒店附加信息，包括wifi,设施
	 * @param unknown $parms
	 */
	public function get_hotel_extra_info($params){

		$web_hotel_id = $this->pms_set['hotel_web_id'];

		$this->CI->load->model ( 'hotel/pms/Suba_hotel_ext_model', 'pms_ext' );

		return $this->CI->pms_ext->get_web_hotel_detail ( $web_hotel_id,$this->pms_set );


	}

	/**
	 * 取房附加信息，包括wifi,设施
	 */
	public function return_room_detail($params){

		//return array();
		$inter_id = $params[0];
		$hotel_id = $params[1];
		$room_id = $params[2];

		$this->CI->load->model ( 'hotel/pms/Suba_hotel_model' );

		$web_hotel_id = $this->pms_set['hotel_web_id'];

		$room = $this->CI->db->get_where ( "hotel_rooms", array (
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'room_id' => $room_id
		) )->row_array ();

		$web_room_id = $room['webser_id'];

		if($web_room_id){

			$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));


			$suba = new Subaapi_webservice(false);

			$hotel_data = $suba->GetHotelRooms($web_hotel_id,date("Y-m-d"),date("Y-m-d",time()+86500),1);

			if( !$hotel_data['GetHotelRoomsResult']['IsError'] && isset( $hotel_data['GetHotelRoomsResult']['Content']['HotelRoom'] ) ){

				$suba_room = $hotel_data['GetHotelRoomsResult']['Content']['HotelRoom'];

				if(empty($suba_room)){

					echo "{}";

				}else{

					$room = array();

					foreach($suba_room as $temp_room){

						if( $temp_room['RoomTypeID'] == $web_room_id  ){

							$room['name'] = $temp_room['RoomName'];

							$room ['room_img'] = $temp_room ['RoomPic'];

							$room ['imgs'] = array();

							$room ['book_policy'] = $temp_room['RoomDesc'];

							$room['room_area'] = $temp_room['RoomArea'];

							$room['bed_size'] = $temp_room['BedSize'];

							$room['bed_name'] = $temp_room['BedName'];

							/* 电脑			= 1 << 0
							冰箱			= 1 << 1
							浴缸			= 1 << 2 */
							$config_arr = array(
								"0"=>"电脑",
								"1"=>"冰箱",
								"2"=>"浴缸"

							);
							$room["setting"] =  $this->CI->Suba_hotel_model->getSettingBybinary($config_arr, intval($temp_room['RoomConfig']) );


							/*  暗窗			= 1 << 0
								廊窗			= 1 << 1
								无窗			= 1 << 2
								半地下	    = 1 << 3
								地下			= 1 << 4
								无烟房		= 1 << 5
								 */
							$config_arr = array(
								"0"=>"暗窗",
								"1"=>"廊窗",
								"2"=>"无窗",
								"3"=>"半地下",
								"4"=>"地下",
								"5"=>"无烟房"

							);
							$room["windows"] =  $this->CI->Suba_hotel_model->getSettingBybinary($config_arr, intval($temp_room['SpecialDesc']) );



							$room["add_bed_price"] =  $temp_room['AddBedPrice'];

							$room["add_bed_desc"] =  $temp_room['AddBedDesc'];

							//$this->CI->Suba_hotel_model->getSettingBybinary()

							echo json_encode ( $room );

						}

					}


				}

			}

			/* {
				"name": "经济大床房",
				"room_img": "http://file.iwide.cn/public/uploads/201603/a421641095hri_1_7.jpg",
				"imgs": [],
				"book_policy": ""
			} */



		}else{

			echo "{}";

		}



	}

	//获取PMS收藏
	public function get_user_front_marks($params){

		$params = $params[0];
		$openid = $params[0]['openid'];
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);
		$suba->local_test = false;

		$res = $suba->GetWeixinCustomer($openid);
		if(isset($res['GetWeixinCustomerResult']) && $res['GetWeixinCustomerResult']['ResultCode'] == '00' && empty($res['GetWeixinCustomerResult']['IsError'])){
			$subaMemberInfo = $res['GetWeixinCustomerResult']['Content'];
			$sort   = $params[1];
			$nums   = empty($params[2])? 200 :$params[2];
			$offset = empty($params[3])? 1 :$params[3];
//            $subaMemberInfo['MainCardNO'] = 303503095;
			$collectRes= $suba->GetMyFavorites($subaMemberInfo['MainCardNO'],$offset,$nums);
			if(isset($collectRes['GetMyFavoritesResult']) && ($collectRes['GetMyFavoritesResult']['ResultCode'] == '00')){
				$collectsArray = $collectRes['GetMyFavoritesResult']['Content']['ListContent'];
				if(isset($collectsArray['MyFavorite']) && (!empty($collectsArray['MyFavorite']))){
					if( $collectRes['GetMyFavoritesResult']['Content']['TotalCount'] ==1){
						$collectsFormatArray[] = $collectsArray['MyFavorite'];
					}else{
						$collectsFormatArray = $collectsArray['MyFavorite'];
					}

					$resultArr = $this->formatCollection($collectsFormatArray,$openid);
					return $resultArr;
				}else{
					return array();
				}
			}else{
				return array();
			}
		}else{
			return array();
		}
	}


	public function get_type_mark($params){
		$params = $params[0];
		$this->CI->load->model ( 'hotel/Hotel_model' );
		$idents = $params[0];
		$staus = $params[1];
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);
		$suba->local_test = false;

		$subaMemberInfo = $suba->GetWeixinCustomer($idents['openid']);
		//@Editor lGh 2016-5-15 20:18:33 增加判断条件
		if(isset($subaMemberInfo['GetWeixinCustomerResult']) && ($subaMemberInfo['GetWeixinCustomerResult']['ResultCode']=='00') && !empty($subaMemberInfo['GetWeixinCustomerResult']['Content'])){
			$fav = $suba->GetMyFavorites($subaMemberInfo['GetWeixinCustomerResult']['Content']['MainCardNO']);
			if( isset($fav['GetMyFavoritesResult']) && ($fav['GetMyFavoritesResult']['ResultCode']=='00') && ($fav['GetMyFavoritesResult']['Content']['TotalCount']>0)){
				if($fav['GetMyFavoritesResult']['Content']['TotalCount'] > 1){
					$favArr =  $fav['GetMyFavoritesResult']['Content']['ListContent']['MyFavorite'];
				}else{
					$favArr[]  =  $fav['GetMyFavoritesResult']['Content']['ListContent']['MyFavorite'];
				}
				foreach($favArr as $v ){
					$MyFavoritesID[$v['HotelID']] = $v['MyFavoritesID'];
					$favIds[] = $v['HotelID'];
				}
				$this->CI->load->model('common/pms_model');
				$localHotelArr = $this->CI->pms_model->get_hotels_pms_set($idents['inter_id'], 'suba', $favIds, 'hotel_web_id', $key = 'hotel_id');
				if(!empty($localHotelArr) && is_array($localHotelArr)){
					foreach($localHotelArr as $k2=>$hotel){
						if($k2 == $idents['mark_name']){
							$hotel['mark_id'] = $MyFavoritesID[$hotel['hotel_web_id']];
							return $hotel;
						}
					}
				}

			}else{
				return array();
			}
		}else{
			return array();
		}
		return array();
	}

	//格式化
	function formatCollection($collectsArray,$openid){
		$result = array();
		foreach($collectsArray as $v){
			$hotel = $this->CI->db->get_where ( 'hotel_additions', array (
				'inter_id' => 'a455510007',
				'hotel_web_id' => $v['HotelID']
			) )->row_array ();
			$v['hotel_id']  = $hotel['hotel_id'];
			$v['mark_id']   = $v['MyFavoritesID'];
			$v['mark_name'] = $v['hotel_id'];
			$v['inter_id']  = 'a455510007';
			$v['openid']    = $openid;
			$v['mark_type'] = 'hotel_collection';
			$v['mark_title'] = $v['HotelName'];
			$v['mark_link'] = base_url('index.php/hotel/hotel/index?id=').$v['inter_id']."&h=".$v['HotelID'];
			$v['mark_time'] = time();
			$v['status']  = $v['mark_nums'] = 1;
			$result[] = $v;

		}

		return $result;
	}

	//添加收藏
	public function add_fav_to_pms($params){
		$param = $params[0];
		$openid = $param[0];
		$hotel_id = $param[1];

		$this->CI->load->model('member/member');
		$userInfo = $this->CI->member->getMemberDetailById ( $openid );

		if(isset($userInfo->membership_number) && !empty($userInfo->membership_number) ){
			$cardNo = $userInfo->membership_number;
			$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
			$suba = new Subaapi_webservice(false);
			$suba->local_test = false;
			$subaMemberInfo = $suba->GetCustomer($cardNo);

			if(isset($subaMemberInfo['GetCustomerResult']) && ($subaMemberInfo['GetCustomerResult']['ResultCode'] == '00')){

				$user = $subaMemberInfo['GetCustomerResult']['Content'];

				$hotel = $this->CI->db->get_where ( 'hotel_additions', array (
					'inter_id' => 'a455510007',
					'hotel_id' => $hotel_id
				) )->row_array ();


				$subaHotel = $suba->GetHotelDetail($hotel['hotel_web_id']);

				if(isset($subaHotel['GetHotelDetailResult']) && ($subaHotel['GetHotelDetailResult']['ResultCode'] == '00')){

					$detail = $subaHotel['GetHotelDetailResult']['Content'];
					//增加收藏
					$suba->AddMyFavorite(1,$user['CustomerID'],$detail['HotelID'],$cardNo,$detail['HotelName'],$detail['Merit'],$detail['Address'],$detail['MinPrice'],$detail['HotelPic'], $detail['CommentRate'], $detail['CommentNum']);

					//收藏完成后获取返回最后一条记录
					$favList = $suba->GetMyFavorites($cardNo);
					if(isset($favList['GetMyFavoritesResult'])
					   && ($favList['GetMyFavoritesResult']['ResultCode'])
					   && ($favList['GetMyFavoritesResult']['Content']['TotalCount'] >0)
					){
						if(($favList['GetMyFavoritesResult']['Content']['TotalCount'] ==1)){
							echo $favList['GetMyFavoritesResult']['Content']['ListContent']['MyFavorite']['MyFavoritesID'];
						}else{
							$lastFavArr = $favList['GetMyFavoritesResult']['Content']['ListContent']['MyFavorite'];
							echo $lastFavArr[count($lastFavArr)-1]['MyFavoritesID'];
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}

	}


	//移除收藏
	public function remove_fav($params){
		$MyFavoritesID = array($params[0][1]);
		$this->CI->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
		$suba = new Subaapi_webservice(false);
		$suba->local_test = false;

		//$MyFavoritesID数组
		$suba->DeleteMyFavorites($MyFavoritesID);
	}

	/**
	 * 修改密码
	 * @param $params
	 * @return array
	 */
	public function modPassword($params){
		$data = $params[1];
		$opass = $data['oldpassword'];
		$npass = $data['password'];
		$memberid = $data['uid'];
		$this->CI->load->library('Baseapi/Subaapi_webservice', array('testModel' => true));
		$suba = new Subaapi_webservice(false);
		$result = $suba->ChangePassword($memberid, $opass, $npass);
		if(isset($result['ChangePasswordResult']) && $result['ChangePasswordResult']['ResultCode'] == '00' && $result['ChangePasswordResult']['Content']){
			return array(
				'code'   => 0,
				'errmsg' => '',
			);
		}
		return array(
			'code'   => 1,
			'errmsg' => $result['ChangePasswordResult']['Message'],
		);
	}

	public function activeMember($customerName, $phoneNum, $cardNo, $verifyCode, $openid){
		$this->CI->load->library('Baseapi/Subaapi_webservice', array('testModel' => true));
		$suba = new Subaapi_webservice(false);
		$card_info = $suba->getCardInfo($cardNo);

		if($card_info['GetCardInfoResult']['ResultCode'] == '00' && !empty($card_info['GetCardInfoResult']['Content'])){
			$card_content = $card_info['GetCardInfoResult']['Content'];
			if($card_content['CardState'] != 1){
				switch($card_content['CardState']){
					case 3:
						return array(
							'status'    => 0,
							'is_active' => true,
							'errmsg'    => '此会员卡已被激活',
						);
						break;
					default:
						return array(
							'status' => 0,
							'errmsg' => '此卡不能激活，请联系4001840018-3-3！',
						);
						break;
				}
			}
		} else{
			return array(
				'status' => 0,
				'errmsg' => '此卡不能激活，请联系4001840018-3-3！',
			);
		}

		$this->CI->load->model('member/imember');
		$member = $this->CI->imember->getMemberDetailByOpenId($openid, $this->pms_set['inter_id'], 0);

		//判断是否已绑定过其他会员卡
		if($member->membership_number){
			$res = $suba->UnBindWeixinCustomer($openid);
			$r = $res['UnBindWeixinCustomerResult'];
			if($r['ResultCode'] != '00' || $r['Content'] != 'true'){

				$this->CI->session->set_userdata('sup8mess', '此会员已绑定其他会员卡，解绑失败！');
				return array(
					'status'   => 0,
					'activefail'=>true,
					//					                 'errmsg'   => '添加会员资料失败！',
				);
			}
		}

		$result = $suba->activeCustomer($customerName, $phoneNum, $cardNo, $verifyCode, $openid);

		if($result['ActivationCardResult']['ResultCode'] == '00'){
			//会员信息
			$data = array();
			$data['mem_id'] = $member->mem_id;
//			$this->session->unset_userdata('activate_member');
			$subaMemberInfo = $result['ActivationCardResult']['Content'];
			$data['name'] = $subaMemberInfo['CustomeName'];
			$data['telephone'] = $subaMemberInfo['PhoneNum'];
			$data['membership_number'] = $subaMemberInfo['MainCardNO'];
			$data['level'] = $data['member_type'] = $subaMemberInfo['MainCardTypeID'];

			$this->CI->load->model('member/member');

			//登录
			$this->CI->member->updateMemberByOpenId(array(
				                                        'openid'    => $openid,
				                                        'level'     => $data['level'],
				                                        'is_login'  => 1,
				                                        'is_active' => 1,
			                                        ), $this->pms_set['inter_id']);

			//添加会员资料
			$result = $this->addMemberInfo(array($openid, $data));
			if(!$result){
				$this->CI->session->set_userdata('sup8mess', '添加会员资料失败!');
				return array(
					'status'   => 0,
					'activefail'=>true,
					//					                 'errmsg'   => '添加会员资料失败！',
				);
			}

			$this->CI->load->model('hotel/pms/suba_hotel_model');
			$memberObject = $this->getMemberModel()->getMemberDetailById($openid);
			$this->CI->suba_hotel_model->addRegisterDistribute($this->pms_set['inter_id'], $openid, $memberObject->ma_id, $data['membership_number']);

			return array(
				'status'   => 1,
			);
		}
		$this->CI->session->set_userdata('sup8mess', $result['RegisterResult']['Message']);
		return array(
			'status'   => 0,
			'activefail'=>true,
			//			                 'errmsg'   => $result['RegisterResult']['Message'],
		);
	}

}