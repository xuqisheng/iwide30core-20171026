
<?php
class Lvyun_webservice implements IPMS {
	protected $CI;
	private $SHUXIANGURL = 'http://61.177.58.132:11011/ipms/';
	private $SHUXIANGURLS = 'http://61.177.58.132:11011/ipms/';

    private $vipCardType = array(
        'YJVIP',  //隐居VIP VP1-VP7
        'EVIP'  //网络会员
    );
    //private $SHUXIANGURL_COUPONS = '';

	private $hotel_group_id = 2;
	protected $_memberModel;
	function __construct($params) {
		$this->CI = & get_instance ();
		$this->pms_set = $params ['pms_set'];
		$auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		//@author lGh 2016-3-31 16:47:58 写死你妹啊 @lGh 都是你写的好咯！你妹妹在我的手上！快来拿

		$this->SHUXIANGURL    = $auth['member_url'];
		$this->SHUXIANGURLS    = $auth['member_url'];

//        $this->SHUXIANGURLS  = 'http://202.107.192.24:8090/ipms/';        //暂时写死测试

		$this->hotel_group_id = $auth['hotelGroupId'];

        //卡券
        //$this->SHUXIANGURL_COUPONS    = $auth['url'];


	}
	public function get_orders($inter_id,$status,$offset,$limit){

	}
	public function get_hotels($inter_id,$status,$offset,$limit){

	}
	public function check_openid_member($inter_id, $openid, $paras) {
		$this->CI->load->model('hotel/Member_model');
		return $this->CI->Member_model->check_openid_member($inter_id,$openid,$paras);
		$update=empty($paras['update'])?'':'update';
		$member=$this->getMemberByOpenId(array($openid,$update));
		if(!empty($member) && ! empty ( $member->mem_id )){
			if($member->is_login==1&& ! empty ( $member->membership_number )){
			// $member->level=$member->mebtype;
				$member->mem_card_no=$member->membership_number;
			}else{
				$member->level=0;
				$member->mem_card_no='';
			}
			return $member;
		}
		return false;
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$this->CI->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		$condit ['member_level'] = isset ( $condit ['member_level'] ) ? $condit ['member_level'] : null;
		return $this->CI->pms->get_rooms_change ( $rooms, $idents, $condit, $this->pms_set );
	}
	public function order_submit($inter_id, $orderid, $params) {
		$this->CI->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		return $this->CI->pms->order_to_web ( $inter_id, $orderid, $params, $this->pms_set );
	}

	public function add_web_bill($order,$params=array()){
		$this->CI->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$trans_no=empty( $params['trans_no'])?'': $params['trans_no'];
		$order['_third_no']=empty($params['third_no'])?'': $params['third_no'];
		return $this->CI->pms->add_web_bill ( $order['web_orderid'], $order, $pms_auth, $trans_no );
	}

	public function cancel_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		return $this->CI->pms->cancel_order_web ( $inter_id, $order, $this->pms_set );
	}

	function update_web_order($inter_id, $order) {
		$this->CI->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		return $this->CI->pms->update_web_order ( $inter_id, $order,$this->pms_set );
	}
	function check_order_canpay($order) {
		$this->CI->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		return $this->CI->pms->check_order_canpay ( $order,$this->pms_set );
	}
	public function get_new_hotel($param = array()){

	}

	public function getAllMemberLevels($params) {
		$inter_id = isset ( $params [0] ) ? $params [0] : 0;
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
					$result = $this->getPmsMember ( $openid, $memberObject->telephone, $memberObject->password,'',true);
					if ($result) {
						$memberObject = $this->getMemberModel ()->getMemberDetailById ( $openid );
						$memberObject->pms_info=$result;
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

	//给出会员的模型
	public function getMemberModel()
	{
		if(!isset($this->_memberModel)){
			$this->CI->load->model('member/member');
			$this->_memberModel = $this->CI->member;
		}
		return $this->_memberModel;
	}

	//匹配会员等级信息
	protected function memberlevel($level,$positive=true)
	{
		$this->CI->load->model('common/Webservice_model');
		$member = $this->CI->Webservice_model->get_web_reflect($this->CI->session->userdata('inter_id'),0,'lvyun','member_level',1);

//  		static $member = array(
//  			'CWSK' =>1,
//  			'CSKK'  => 1, //微信会员价（1早）
//  			'CRLK' => 2, //微信会员价（2早）
//  			'CZKK' => 3,	//尚客会员价
//  		);

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

	//发送短信信息
	public function sendSms($params){
		$sessionstr= $params[2].'openid';
		$openid           = $_SESSION["$sessionstr"];
		$data             = $params[0];
		$data['inter_id'] = $params[2];
		$url = $this->SHUXIANGURLS."membercard/registerMemberCardApply";
		$resultdata = array(
				"name" => $data['name'],
				"sex" => $data['sex'],
				"mobile" => $data['telephone'],
				"idType" => "01",
				"idNo" => $data['identity_card'],
				"verifyType" => "0",
				"verifyHost" => $data['email'],
				//"password" => $data['password'],
				"hotelGroupId" => $this->hotel_group_id
		);
		if(isset($data['email'])){
			$resultdata['email'] = $data['email'];
		}
		if(isset($data['identity_card'])){
			$resultdata['idNo'] = $data['identity_card'];
		}
		$result = $this->curl_post( $url , $resultdata);
		$result = json_decode($result);

		if(isset($result->resultCode) && $result->resultCode==0) {
			$_SESSION['APPlyId'] = $result->applyId;
			$data['membership_number'] = $result->applyId;
			$data['inter_id'] = $params[2];
			$updateParams = array(
					'openid'           => $openid,
					'is_login'         => 0,
					'is_active'        => 0,
					'last_login_time'  => time()
			);
			$this->getMemberModel()->updateMemberByOpenId($updateParams);
			$this->addMemberInfo(array($openid,$data));
			return '发送成功';
			//return array('code'=>0,'errmsg'=>$result->resultMsg);
		} else {
			return $result->resultMsg;
			//return array('code'=>1,'errmsg'=>$result->resultMsg);
		}
	}

	public function sendSetPassword($params){
		$data             = $params[0];
		$url = $this->SHUXIANGURLS."membercard/resetPassword";
		$resultdata = array(
				"loginId" => $data,
				"sendType" => 'mobile',
				"hotelGroupId" => $this->hotel_group_id
		);
		$result = $this->curl_post( $url , $resultdata);
		$result = json_decode($result);
		if(isset($result->resultCode) && $result->resultCode==0) {
			return '发送成功';
		} else {
			return $result->resultMsg;
		}
	}
	//检查登陆
	public function checklogin($params) {

		$result = $this->getPmsMember ( $params [0], $params [1], $params [2], $params [3] );
		return $result;
	}

	//@Editor lGh 2016-6-1 11:52:10 给书香商城做获取会员接口
	//获取
	protected function getPmsMember($openid, $telephone, $password, $inter_id = '',$return_member=FALSE) {
        /*
        $pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
        $url = $this->SHUXIANGURLS.'membercard/memberLogin';
        $data = array(
            "loginId" => "$telephone",//cardNo
            "loginPassword" => "$password",
            "hotelGroupId" => $this->hotel_group_id
        );
        $result = $this->doCurlGetRequest ( $url, $data );
        $this->CI->load->model('member/Weixin_text','Weixin');
        $time = date('Y-m-d H:i:s',time());
        $data_str = "lvyun_get".json_encode($result);
        $this->CI->Weixin->add_weixin_text($data_str,$time);
        $result = json_decode($result);
        */
        $this->CI->load->library('Baseapi/Lvyunapi_webservice',array('testModel'=>true));
        $lvyun = new Lvyunapi_webservice(false);
        $lvyun->apiUrl = $this->SHUXIANGURLS;
        $lvyun->local_test = false;

        $lvyunMember = $lvyun->getMemberInfo($telephone, $password);
        $result = json_decode($lvyunMember);

		$_SESSION['cardId']=$result->cardId;


		if (isset ( $result->resultCode ) && $result->resultCode == 0) {

            if( isset($result->cardListDto) && count($result->cardListDto) > 1 && $this->pms_set['inter_id'] == 'a457946152'){ //隐居会员卡

                foreach($result->cardListDto as $card){
                    if( !in_array($card->cardType,$this->vipCardType))
                        continue;
                    else{
                        $result->cardNo    = $card->cardNo;
                        $result->cardLevel =   $card->cardLevel;
                        $result->cardId = $card->cardId;
//                         if ($return_member){
//                         	foreach ($card as $k=>$c){
//                         		$result->$k=$c;
//                         	}
//                         }
                        break;
                    }
                }

                //隐居储值
                $lvyunBalance = $lvyun->getAccountList($result->cardId, $result->cardNo,  '1970-01-01' , date("Y-m-d",time()),$this->hotel_group_id);
//                $lvyunBalance = $lvyun->getCardBalanceInfo($result->cardId, $result->cardNo, $this->hotel_group_id); //系统未支持最新接口
                if(isset ( $lvyunBalance->resultCode ) && $lvyunBalance->resultCode == 0){
                    $result->accountBalanceUse = $lvyunBalance->accountBalance;  //卡余额
//                    $result->pointBalance = $lvyunBalance->pointBalance;  //卡的积分
                }
            }//隐居会员卡 end




			$account_list = $this->get_account_list($inter_id,$result->cardId);
			$data = array (
					'openid' => $openid,
					'name' => $result->name,
					'telephone' => $result->mobile,
					'email' => $result->email,
					'identity_card' => $result->idNo,
					'membership_number' => $result->cardNo,
// 					'dob' => $result->birth,//@Editor lGh 返回数据无此字段
					'password' => $password,
					'inter_id' => $inter_id
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->pointBalance,
					'is_login' => 1,
					'level' => $this->memberlevel($result->cardLevel),
					'last_login_time' => time ()
			);

            //隐居同步会员卡可用余额
            if(isset($result->accountBalanceUse) && $result->accountBalanceUse>=0){
                $updateParams['balance']  = $result->accountBalanceUse;
            }
            //end同步余额

			if($account_list && isset($account_list->accountBalance)){
				$updateParams['balance'] = $account_list->accountBalance;
			}
			$this->getMemberModel ()->updateMemberByOpenId ( $updateParams );
			$this->getMemberModel ()->updateMemberInfoByOpenId ( $data );

			if ($return_member){
				unset($result->cardListDto);
				unset($result->resultCode);
				return $result;
			}
			return true;
		} else {
			$this->getMemberModel ()->updateMemberByOpenId ( array (
					'openid' => $openid,
					'is_login' => 0
			) );
			return false;
		}
	}

	/**
	 * 获取用户充值列表
	 * @param int $card_id
	 * @param DateTime $begin_date
	 * @param DateTime $enddate
	 * @param int $first_result
	 * @param int $page_size
	 * @return Result|boolean
	 */
	protected function get_account_list($inter_id,$card_id,$begin_date = NULL,$enddate = NULL,$first_result = 0,$page_size = 9999){
		$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
		$url = $this->SHUXIANGURLS.'membercard/getAccountList';
		$data['cardId']       = $card_id;
		$data['beginDate']    = '';
		if(!empty($begin_date))
			$data['beginDate']    = $begin_date;
		$data['endDate']      = '';
		if(!empty($enddate))
			$data['endDate']      = $enddate;
		$data['firstResult']  = $first_result;
		$data['pageSize']     = $page_size;
		$data['hotelGroupId'] = $this->hotel_group_id;
		$result = $this->doCurlGetRequest ( $url, $data );
		$result = json_decode($result);

		$this->CI->load->model('common/Webservice_model');
		$this->CI->Webservice_model->log_service_record(json_encode($data,JSON_FORCE_OBJECT),json_encode($result,JSON_UNESCAPED_UNICODE),$inter_id,'lvyun',$url,'query_get');
		if (isset ( $result->resultCode ) && $result->resultCode == 0) {
			return $result;
		} else {
			return false;
		}
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

	public function modifiedMember($params)
	{
		return $params[1];
	}

	public function initMember($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		$inter_id = $params[2];
		$data['is_active'] = 0;

				$result = $this->createMember($openid, $data, $inter_id);
		// $result = $this->createMember(array($openid, $data, $inter_id));//传参错误，作修改 @author lGh

		if($result) {

            //*注册微信会员
            $this->CI->load->library('Baseapi/Lvyunapi_webservice',array('testModel'=>true));
            $lvyun = new Lvyunapi_webservice(false);
            $lvyun->apiUrl = $this->SHUXIANGURLS;
            $lvyun->local_test = true;
            $lvyun->registerWxMember($openid, '', $openid);
			// 			return $this->getMemberByOpenId($openid);
			return $this->getMemberByOpenId(array($openid));//传参错误，作修改 @author lGh
		} else {
			return false;
		}
	}

	//退出登陆
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

	//注册信息
	public function registerMember( $params ){
		$openid           = $params[0];
		$data             = $params[1];
		$data['inter_id'] = $params[2];
		$result = $this->registerMemberCard($_SESSION['APPlyId'],$data['telephone'],$data['sms'],$openid,$data['inter_id']);
		$result = json_decode($result);
		if(isset($result->resultCode) && $result->resultCode==0) {
			$updateParams = array(
					'openid'           => $openid,
					'is_login'         => 0,
					'is_active'        => 0,
					'last_login_time'  => time()
			);
			/*注册成功之后开始跳转送券 START */
// 			$cardId = 'pfleZs5lc_si7TwJxWAf-AsHoZeg';
// // 			$cardId = 'pfleZs-Sen-_nKc-6vFuunGCMPKQ';
// 			$wxcardnum = $this->getMemberModel()->selectWxcardInfo( $openid , $params[2] , $cardId );
// 			if($wxcardnum<1){
// 				//增加数据，跳转
// 				$this->getMemberModel()->addWxcardInfo( $openid , $params[2] , $cardId );
// 				redirect('member/account/shuxiangcard');
// 			}
			/*注册成功之后开始跳转送券 END */
            if($data['inter_id']!='a449675133'){
		    	$this->getMemberModel()->updateMemberByOpenId($updateParams);
            }
			return array('code'=>1,'errmsg'=>$result->resultMsg);
		} else {
			return array('code'=>0,'errmsg'=>$result->resultMsg);
		}
	}

	//检测短信验证码是否合法
	function checkSendSms($params){
		return true;
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
		if ($data != array ()) {

			$url = $url . '?' . http_build_query ( $data );
		}
		$con = curl_init ( ( string ) $url );
		curl_setopt ( $con, CURLOPT_HTTPHEADER,array('charset=UTF-8'));
		curl_setopt ( $con, CURLOPT_HEADER, false );
		curl_setopt ( $con, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $con, CURLOPT_TIMEOUT, ( int ) $timeout );
		curl_setopt ( $con, CURLOPT_SSL_VERIFYPEER, false );
		//echo $url;exit;

		$now = time ();

		$result=curl_exec ( $con );

		$this->CI->load->model('common/Webservice_model');
		$this->CI->Webservice_model->add_webservice_record($this->pms_set['inter_id'], 'lvyun', $url, $data, $result,'query_get', $now, microtime (), $this->CI->session->userdata ( $this->pms_set['inter_id'] . 'openid' ));

		return $result;
	}

	protected function curl_post($url, $data) {
		$this->CI->load->helper ( 'common' );
		$data = http_build_query ( $data );
		$now = time ();
		$return = doCurlPostRequest ( $url, $data );

		$this->CI->load->model('common/Webservice_model');
		$this->CI->Webservice_model->add_webservice_record($this->pms_set['inter_id'], 'lvyun', $url, $data, $return,'query_post', $now, microtime (), $this->CI->session->userdata ( $this->pms_set['inter_id'] . 'openid' ));

		return $return;
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

	//重置密码
	public function updatePassWordin($params){

        $inter_id=$params[2];

        $this->CI->load->model('common/Webservice_model');
        $resetPassword = $this->CI->Webservice_model->get_web_reflect($inter_id,0,'lvyun','resetPassword',1);

        if(isset($resetPassword['resetPassword']) && $resetPassword['resetPassword']==1){

            $data = array(
                'loginId' => $params[1]['telephone'],
                'memberName' => $params[1]['name'],
                'hotelGroupId' => $this->hotel_group_id,
                'sendType'=>'mobile',
            );

        }else{

            $CardId = $params[1]['custom1'];
            $CardName = $params[1]['custom2'];
            $data = array(
                'loginId' => $params[1]['custom1'],
                'memberName' => $params[1]['custom2'],
                'hotelGroupId' => $this->hotel_group_id,
                'sendType'=>'mobile',
            );

        }
            $url = $this->SHUXIANGURLS.'membercard/resetPassword';
            $result = $this->doCurlGetRequest( $url , $data );
            $result = json_decode($result);
            if(isset($result->resultCode) && $result->resultCode){
                return array('code'=>0,'errmsg'=>$result->resultMsg);
            }else{
                return array('code'=>1,'errmsg'=>$result->resultMsg);
            }


	}




	//注册验证
	public function registerMemberCard( $applyId , $mobileOrEmail , $code,$openid,$inter_id ){
		$url = $this->SHUXIANGURLS.'membercard/registerMemberCard';
        $this->CI->load->model('member/Weixin_text','Weixin');
		$data = array(
			'applyId'=>$applyId,
			'mobileOrEmail'=>$mobileOrEmail,
			'verifyCode'=>$code,
			'hotelGroupId'=>$this->hotel_group_id,
			);
		$result = $this->doCurlGetRequest( $url ,$data );

        $pms_set=$this->pms_set;

        $this->CI->load->model('common/Webservice_model');
        $local_value = $this->CI->Webservice_model->get_web_reflect($inter_id,0,'lvyun','auto_login',1);

        if($local_value['auto_login']==1){

            $j_result = json_decode($result);

            if($j_result->passWord){

                $params=array($openid,$mobileOrEmail,$j_result->passWord,$inter_id);

//                $params=array('ocbScjurk-XytAa6sMLpEMOpH_V0','18300174600','990615',$inter_id);

                $this->checklogin($params);

            }

        }

		return $result;
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
				'is_active'      => $active,
				'is_login'		 => '1'
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

	//修改密码
	public function modPassword($params)
	{
		$data   = $params[1];
		$opass = $data['oldpassword'];
		$npass = $data['password'];
		$memberid = $data['uid'];

		$data = array(
			'newPassword' => $npass,
			'oldPassword' => $opass,
			'memberId' => $memberid,
			'hotelGroupId' => $this->hotel_group_id
			);
		//var_dump($data);
		$url = $this->SHUXIANGURLS.'membercard/updateMember';
		$result = $this->doCurlGetRequest( $url , $data );
		$result = json_decode($result);
		//var_dump($result);exit;
		if(isset($result->resultCode) && $result->resultCode){
			return array('code'=>0,'errmsg'=>$result->resultMsg);
		}else{
			return array('code'=>1,'errmsg'=>$result->resultMsg);
		}
	}

	/*
	 * 获取积分记录
	 */
	public function getBonusRecords($params)
	{
		$openid = $params[0];

        $inter_id = $params[1];

        $this->CI->load->model('common/Webservice_model');
        $getBonusDetail = $this->CI->Webservice_model->get_web_reflect($inter_id,0,'lvyun','getBonusDetail',1);

        if(isset($getBonusDetail['getBonusDetail']) && $getBonusDetail['getBonusDetail']==1){

                $member_info = $this->getMemberInfoByOpenId(array($params[0]));

                if($member_info && isset($member_info->password)){
                    $pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
                    $url = $this->SHUXIANGURLS.'membercard/memberLogin';

                    $data=array(
                        "loginId"=>$member_info->telephone,
                        "hotelGroupId" => $this->hotel_group_id,
                        "loginPassword"=>$member_info->password
                    );

                    $getMemberInfo = $this->curl_post ( $url, $data );
                    $getMemberInfo = json_decode($getMemberInfo);


                    $url = $this->SHUXIANGURLS.'membercard/getPointList';
                    $data = array(
                        "hotelGroupId" => $this->hotel_group_id,
                        "cardId"=>$getMemberInfo->cardId
                    );
                    $result = $this->curl_post ( $url, $data );
                    $result = json_decode($result)->cardPointList;


                    if(!empty($result)){

                        $data=array();
                        $data['bonus']=$result;
                        $data['add_bonus']    = array();
                        $data['reduce_bonus'] = array();

                        foreach($data['bonus'] as $arr){
                            $arr->note=$arr->src;
                            $arr->create_time=$arr->pointGenDate;
                            $arr->bonus=$arr->point;

                            if(intval($arr->point)>=0){

                                $data['add_bonus'][]=$arr;

                            }else{

                                $data['reduce_bonus'][]=$arr;

                            }

                        }

                    }

                }


        }else{

            $this->CI->load->model('member/iconsume');
            $memberObject         = $this->getMemberByOpenId(array($openid));
            $data['bonus']        = $this->CI->iconsume->getBonusByMember($memberObject->mem_id, 'all');
            $data['add_bonus']    = $this->CI->iconsume->getBonusByMember($memberObject->mem_id, 'charge');
            $data['reduce_bonus'] = $this->CI->iconsume->getBonusByMember($memberObject->mem_id, 'reduce');

            if(!empty($data['bonus']))        rsort($data['bonus']);
            if(!empty($data['add_bonus']))    rsort($data['add_bonus']);
            if(!empty($data['reduce_bonus'])) rsort($data['reduce_bonus']);

        }
		return $data;
	}


	//订房积分扣减
	public function reduceBonus($params)
	{
		//接收openid
		$openid       = $params[0];
		//查询用户信息
		$member_data = $this->getMemberByOpenId(array($openid));


        $url = $this->SHUXIANGURLS.'membercard/memberLogin';
        $data = array(
            "loginId" => $member_data->telephone,//cardNo
            "loginPassword" => $member_data->password,
            "hotelGroupId" => $this->hotel_group_id
        );
        $pms_member = $this->doCurlGetRequest ( $url, $data );
        $pms_member = json_decode($pms_member);

		//传递参数
		$bonus        = $params[1];
		$note         = $params[2];
		$order_id     = $params[3];
		$inter_id     = $params[4];


		$data['hotelGroupId'] = $this->hotel_group_id;		//酒店集团编号 2代表书香
		//$data['hotelId']=$post['hotelId'];
		$data['cardId'] = $pms_member->cardId;	//会员卡id
		$data['cardNo'] = $pms_member->cardNo;	//会员卡号
		$data['code'] = 'WX001';		//物品类型
		//$data['extraInfo']=$post['extraInfo'];
		$data['amountString'] = $bonus;	//数量
		$data['addr'] = $member_data->address;		//地址
		//$data['disHotel']=$post['disHotel'];
		$data['remark'] = $note;	//备注


		$url=$this->SHUXIANGURL."membercard/pointExchange";	//拼接curl
		$result = $this->curl_post( $url , $data);


		$result = json_decode($result);
		$this->CI->session->set_userdata('text_msg',$result->resultMsg);
		//验证结果
		if($result->resultCode == 0) {
			$data2 = array(
					'openid'     => $openid,
					'bonus'      => $bonus
			);
			$result = $this->getMemberModel()->updateBonus($data2, false, $note,$order_id,$inter_id);
			return $result;
		} else {
			return false;
		}

	}

	/**
	 * 储值扣减
	 * @param Array $params {$openid, $amount, $remark, $orderid, $inter_id,$hotel_id}
	 * @return boolean|stdClass
	 */
	public function reduceBalance($params)
	{
		try {
			$member_info = $this->getMemberInfoByOpenId(array($params[0]));
			if($member_info && isset($member_info->telephone)){
				$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
				$url = $this->SHUXIANGURLS.'membercard/memberLogin';
				$data = array(
						"loginId" => $member_info->telephone,//cardNo
						"loginPassword" => $member_info->password,
						"hotelGroupId" => $this->hotel_group_id
				);
				$result = $this->doCurlGetRequest ( $url, $data );
				$result = json_decode($result);

				//@Editor lGh 2016-5-27 14:59:00 隐居会员卡
				if( isset($result->cardListDto) && count($result->cardListDto) > 1 && $this->pms_set['inter_id'] == 'a457946152'){

					foreach($result->cardListDto as $card){
						if( !in_array($card->cardType,$this->vipCardType))
							continue;
						else{
							$result->cardNo    = $card->cardNo;
							$result->cardLevel =   $card->cardLevel;
							$result->cardId = $card->cardId;
							break;
						}
					}
				}

				//@Editor lGh 2016-4-14 19:53:16
				$orderids=explode(',', $params[3]);
				$data=array();
				$data['hotelGroupId'] = $this->hotel_group_id;
				$data['cardId']       = $result->cardId;
				$data['password']     = $orderids[2];
				$data['cardNo']       = $result->cardNo;
				$data['crsNo']        = $orderids[1];
				$data['taCode']       = $pms_auth['taCode'];//储值、积分分别对应两个付款码 上线之初在ihotel中定好即可
				$data['taNo']         = '';
				$data['taRemark']     = $params[2];
				$data['money']        = $params[1];
				$url = $this->SHUXIANGURLS.'CRS/saveMemberCardPay';
				$result = $this->curl_post ( $url, $data );
// 				var_dump($result);
				$result = json_decode($result);
// 				var_dump(json_encode($data));
				if(isset($result->resultCode) && $result->resultCode == 0){
					$data = array(
							'openid'      => $params[0],
							'balance'     => $params[1]
					);
					$note     = $params[2];
					$order_id = $orderids[0];
					$inter_id = $params[4];
					$result = $this->getMemberModel()->updateBalance($data, false, $note,$order_id,$inter_id);
					return true;
				}else{
					return false;
				}
			}else{
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

    public function getPmsMemberInfo($params){
        $openid = $params[0];
        $memberInfoObj = $this->getMemberInfoByOpenId(array($openid));

        $url = $this->SHUXIANGURLS . 'membercard/memberLogin';
        $data = array(
            "loginId" => $memberInfoObj->membership_number,
            "hotelGroupId" => $this->hotel_group_id,
            "loginPassword" => $memberInfoObj->password
        );

        $getPmsMemberInfo = $this->curl_post($url, $data);
        $getPmsMemberInfo = json_decode($getPmsMemberInfo);
        return $getPmsMemberInfo;
    }

    /**
     * //获取会员卡
     * @param $params
     * @param string $type //卡种类
     * @return mixed
     */
    public function getPmsMemberCard($params,$type= array())
    {
        $openid = $params[0];

        $memberObj = $this->getMemberByOpenId(array($openid));

        if(isset($memberObj->is_login) && $memberObj->is_login ==0 ) return array(); //未登录

        $memberInfoObj = $this->getMemberInfoByOpenId(array($openid));

        if(empty($type) || !is_array($type)){
            $type = $this->vipCardType;
        }

        $this->CI->load->library('Baseapi/Lvyunapi_webservice',array('testModel'=>true));
        $lvyun = new Lvyunapi_webservice(false);
        $lvyun->apiUrl = $this->SHUXIANGURLS;
        $getPmsMemberInfo = $lvyun->getMemberInfo($memberInfoObj->membership_number,$memberInfoObj->password ,$this->hotel_group_id);
//        $url = $this->SHUXIANGURLS . 'membercard/memberLogin';
//        $data = array(
//            "loginId" => $memberInfoObj->membership_number,
//            "hotelGroupId" => $this->hotel_group_id,
//            "loginPassword" => $memberInfoObj->password
//        );

//        $getPmsMemberInfo = $this->curl_post($url, $data);
        $getPmsMemberInfo = json_decode($getPmsMemberInfo);

        if( isset($getPmsMemberInfo->resultCode) &&  ($getPmsMemberInfo->resultCode == 0) ){
            if(count($getPmsMemberInfo->cardListDto) >= 1){
                foreach($getPmsMemberInfo->cardListDto as $card){
                    if( !in_array($card->cardType,$type))
                        continue;
                    else{
                        $card->memberId = $getPmsMemberInfo->memberId;
                        return $card;
                        break;
                    }
                }
            }
        }else{
            return array();
        }


    }

    /**
     * //可用优惠券列表
     * @param $params
     * @return array
     */
    public function couponCardList($params){
        $data['cards'] = $this->getRulesByParams($params);
        return $data;

    }

    //获取会员卡优惠券
    /**
     * @author Jake
     * @param $params
     * @return array
     */
    function getIgetcard($params){
        $openid = $params[0];
        $cardObj = $this->getPmsMemberCard(array($openid));

        $this->CI->load->library('Baseapi/Lvyunapi_webservice',array('testModel'=>true));
        $lvyun = new Lvyunapi_webservice(false);
        $lvyun->apiUrl = $this->SHUXIANGURLS;
        $lvyun->local_test = true;

        if(empty($cardObj)) return array();

        if($this->pms_set['inter_id'] == "a457946152" && count($params)>2){ //带参数获取
            $extra = $params[3];
            $rmtype     =  $extra['extra_para']['web_room_id'];  //房型type
            $rateCode   = $extra['extra_para']['pms_code'];  //费用码
            $useDate    =  date("Y-m-d",strtotime($extra['startdate']));  //入住日期
            $checkOutDate    =  date("Y-m-d",strtotime($extra['enddate']));  //离开店日期
            $hotelGroupId   = $this->hotel_group_id;
            $this->CI->load->model('common/Pms_model');
            $pms_set=$this->CI->Pms_model->get_hotel_pms_set($this->pms_set['inter_id'],$extra['hotel']);
            $hotelId = empty($pms_set)?0:$pms_set['hotel_web_id']; //hotelID
//            $couponType = "RF"; //折扣券
//            $couponType = "DF"; //抵扣券（现金券）
            $coupons = $lvyun->findCouponDetailListByCondi($cardObj->memberId,$rmtype,$rateCode, $hotelId, $useDate, $checkOutDate, $hotelGroupId);

            $logRecordArr = array(
                'member_id'=>$cardObj->memberId,
                'rmtype'    => $rmtype,
                'rateCode'  => $rateCode,
                'hotelId'   => $hotelId,
                'userDate'  => $useDate,
                'checkOutDate'  => $checkOutDate
            );

            $this->CI->load->model('common/Webservice_model');
            $this->CI->Webservice_model->log_service_record(json_encode($logRecordArr,JSON_FORCE_OBJECT).'隐居根据条件获取优惠券',json_encode($coupons,JSON_UNESCAPED_UNICODE),$pms_set['inter_id'],'lvyun','findCouponDetailListByCondi','query_get');

            $result = json_decode($coupons);
            if($result->resultCode == 0 && $result->totalRows >0){
                $cards = $result->listCouponDetailWebDtos;
            }else{
                $cards = array();
            }

        }else{
            $coupons = $lvyun->findCouponDetailListByCardNo($cardObj->cardId,$cardObj->cardNo ,$this->hotel_group_id);

            $result = json_decode($coupons);
            if($result->resultCode == 0 && $result->totalRows >0){
                $cards = $result->couponList;
            }else{
                $cards = array();
            }
        }



        return $cards;
    }

    //@author Jake
    /**
     * 订房优惠券返回
     * @param $params
     * @return object
     */
    public function getRulesByParams($params){

        if($this->pms_set['inter_id']=='a457946152'){ //隐居
            $coupons = $this->getIgetcard($params);

            if($this->pms_set['inter_id'] == "a457946152" && count($params)>2){ //带参数获取
                $myCoupon = false;  //订房优惠券
            }else{
                $myCoupon = true;  //个人中心优惠券
            }


            return $this->yinjuFormatCoupon($coupons,$myCoupon);

        }else{   //书香
            $openid = $params[0];
            $module = $params[1];
            $inter_id = $params[2];
            $param = $params[3];
            $this->CI->load->model ( 'member/Icardrule' );
            return $this->CI->Icardrule->getRulesByParams_local($openid, $module, $inter_id, $param);
        }

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
	/**
	 * 会员卡充值
	 * @param array $params
	 * @return boolean|stdClass
	 */
	public function addBalance($params){
		try {
			$member_info = $this->getMemberInfoByOpenId(array($params[0]));
			if($member_info && isset($member_info->telephone)){
				$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
				$url = $this->SHUXIANGURLS.'membercard/memberLogin';
				$data = array(
						"loginId" => $member_info->telephone,//cardNo
						"loginPassword" => $member_info->password,
						"hotelGroupId" => $this->hotel_group_id
				);
				$result = $this->doCurlGetRequest ( $url, $data );
				$result = json_decode($result);
				$this->CI->load->model('common/Webservice_model');
				if(!empty($result->cardId)){
					$url = $this->SHUXIANGURLS.'membercard/onlinePayForCardAccount';
					$data = array(
							"hotelGroupId" => $this->hotel_group_id,//cardNo
							"cardId"       => $result->cardId,
							"money"        => $params[1],
							"bank"         => 'WEIXIN',
							"remark"       => $params[2],
							"payCode"      => '9007'
					);
					$result = $this->doCurlGetRequest ( $url, $data );
					//log
					$this->CI->Webservice_model->log_service_record(json_encode($data,JSON_FORCE_OBJECT),json_encode($result,JSON_UNESCAPED_UNICODE),$params[4],'lvyun',$url,'query_get');

					$result = json_decode($result);
					if(isset($result->resultCode) && $result->resultCode == 0){
						$account_list = $this->get_account_list($params[4],$data['cardId']);
						if($account_list && isset($account_list->accountBalance)){
							$updateParams['openid']  = $params[0];
							$updateParams['balance'] = $account_list->accountBalance;
							$this->getMemberModel ()->updateMemberByOpenId ( $updateParams );

                            /*会员充值升级*/
                            $this->checkAndUpgrade($params[1],$params[3],$result);

						}
						return true;
					}else{
						return false;
					}
				}else{
					$this->CI->Webservice_model->log_service_record(json_encode($params,JSON_FORCE_OBJECT).'充值写入PMS失败',json_encode($result,JSON_UNESCAPED_UNICODE),$params[4],'lvyun',$url,'query_get');
					return false;
				}
			}else{
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
	public function getBalanceRecords($params){
		try {
			$member_info = $this->getMemberInfoByOpenId ( array ($params [0] ) );
			if ($member_info && isset ( $member_info->telephone )) {
				$pms_auth = json_decode ( $this->pms_set ['pms_auth'], TRUE );
				$url = $this->SHUXIANGURLS . 'membercard/memberLogin';
				$data = array (
						"loginId"       => $member_info->telephone, // cardNo
						"loginPassword" => $member_info->password,
						"hotelGroupId"  => $this->hotel_group_id
				);
				$result = $this->doCurlGetRequest ( $url, $data );
				$result = json_decode ( $result );
				if (! empty ( $result->cardId )) {
					$rs = $this->get_account_list ( $params [1], $result->cardId, NULL, NULL, 0, 9999 );
					$records         = array ();
					$add_balances    = array ();
					$reduce_balances = array ();
					foreach ( $rs->accountList as $record ) {
						$ele = new stdClass();
						$ele->note        = $record->taDescript . '-' . $record->account_type;
						$ele->balance     = $record->amount;
						$ele->create_time = $record->createDate;
						$records [] = $ele;
						if($record->account_type == '消费'){
							$ele = new stdClass();
							$ele->note        = $record->taDescript . '-' . $record->account_type;
							$ele->balance     = $record->amount;
							$ele->create_time = $record->createDate;
							$reduce_balances [] = $ele;
						}else if($record->account_type == '充值'){
							$ele = new stdClass();
							$ele->note        = $record->taDescript . '-' . $record->account_type;
							$ele->balance     = $record->amount;
							$ele->create_time = $record->createDate;
							$add_balances [] = $ele;
						}
					}
					$r_data['data_title'] = array('全部记录','充值记录','消费记录');
					$r_data['data_record'] = array($records,$add_balances,$reduce_balances);
					return $r_data;
				} else {
					return false;
				}
			} else {
				return false;
			}
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

    //隐居优惠券格式化
    /**
     * @param $coupons
     * @param bool $flag //true是个人中心，false是订房调用-处理是因为接口返回格式不一样
     * @return array
     */
    function yinjuFormatCoupon($coupons,$flag=true){
        $couponTypeArr = array(
            'RF' => 'discount', //折扣
            'DF'  => 'voucher' //抵扣
        );
        $cards = array();
        $cardsObj = array();
        if($flag){          //个人优惠券列表
            foreach($coupons as $k => $v){

                $cards = (object) $cards;



                $cards->code = $v->couponDetail->sno;
                $cards->reduce_cost = $v->coupon->presentValue;
                $cards->ci_id = $v->couponDetail->couponCode;
                $cards->title = $v->coupon->name;
                $cards->brand_name = $v->coupon->suitHotelDescript;
                $cards->status = 1;
                if(isset($v->coupon->suitScope) && ($v->coupon->suitScope == 'ONLINE')){
                    $cards->coupon_type = $couponTypeArr[$v->coupon->couponType];
                }else{
                    $cards->coupon_type = "number";
                    $cards->reduce_cost = 1;
                }
                $cards->restriction = array('order' => 1);
                $cards->date_info_end_timestamp = strtotime($v->couponDetail->validToDate);
                $cards->card_id = $v->coupon->id;

                $cards->gc_id = $v->coupon->id;

                $cards->href = base_url("index.php/hotel/hotel/search?id=").$this->CI->inter_id;

                $cardsObj[$k] = $cards;
                $cards = array();
            }
        }else{
            foreach($coupons as $k => $v){

                if(!isset($v->coupon->suitScope) ) continue;

                $cards = (object) $cards;
                $cards->code = $v->couponNo;
                $cards->reduce_cost = $v->presentValue;
                $cards->ci_id = $v->couponCode;
                $cards->title = $v->couponName;
                $cards->brand_name = '';
                $cards->status = 1;
                $cards->coupon_type = $couponTypeArr[$v->couponType];
                $cards->restriction = array('order' => 1);
                $cards->date_info_end_timestamp = strtotime($v->validToDate);
                $cards->card_id = $v->couponNo;

                $cards->gc_id = $v->couponNo;
				
                //@Editor lGh 2016-5-6 19:43:23 增加卡券额外信息
                $cards->extra = array(
                	'coupon_id'=>$cards->ci_id,
                	'source'=>'pms'
                );
                

                //$cards->href = "http://seclusion.iwide.cn/index.php/hotel/hotel/search?id=a457946152";
                $cards->href = base_url("index.php/hotel/hotel/search?id=").$this->CI->inter_id;
                $cardsObj[$k] = $cards;
                $cards = array();
            }
        }
        return $cardsObj;
    }


    //会员升级
    /**
     * @param $chargeAmout //充值金额
     * @param $inter_id
     * @param $pmsMemberInfo //pms返回的用户对象实例
     * @param array $type //
     * @return mixed
     */
//    public function checkAndUpgrade($parmas){
//        $parmas = $parmas[0];
//        $chargeAmount =$parmas[0];
//        $inter_id = $parmas[1];
//        $pmsMemberInfo = $parmas[2];
     public function checkAndUpgrade($chargeAmount,$inter_id,$pmsMemberInfo){
        $targetLevel = 1;
        $targetLevelArray = array(
            1 => 'VP1',
            2 => 'VP2',
            3 => 'VP3',
            4 => 'VP4',
            5 => 'VP5',
            6 => 'VP6',
            7 => 'VP7'
        );
        $this->CI->load->model('member/iconfig');
        $level_balance_boj = $this->CI->iconfig->getConfig('level_balance',true,$inter_id);
        $level_balance = $level_balance_boj->value;
        if(is_array($level_balance) && !empty($level_balance)){
            foreach($level_balance as $k=> $l_balance){
                if($chargeAmount< $l_balance){
                    break;
                }else{
                    $targetLevel = $k;
                }
            }
        }
        //获取会员卡与卡别
        $pmsMemberInfo = (json_decode($pmsMemberInfo));
        if( isset($pmsMemberInfo->cardListDto) && count($pmsMemberInfo->cardListDto) >= 1 && $this->pms_set['inter_id'] == 'a457946152'){ //隐居会员卡
            foreach($pmsMemberInfo->cardListDto as $card){
                if( !in_array($card->cardType,$this->vipCardType))
                    continue;
                else{
                    $pmsMemberInfo->cardNo    = $card->cardNo;
                    $pmsMemberInfo->cardLevel =   $card->cardLevel;
                    $pmsMemberInfo->cardId = $card->cardId;
                    break;
                }
            }
            $currentLevel =  0;
            foreach($targetLevelArray as $k=> $level){
                if($pmsMemberInfo->cardLevel == $level){
                    $currentLevel = $k;
                    break;
                }
            }
            if($targetLevel > $currentLevel){
                $this->CI->load->library('Baseapi/Lvyunapi_webservice',array('testModel'=>true));
                $lvyun = new Lvyunapi_webservice(false);
                $lvyun->apiUrl = $this->SHUXIANGURLS;
                $lvyun->updateCardUpgrade($pmsMemberInfo->cardNo,$pmsMemberInfo->cardType,$targetLevelArray[$targetLevel],$pmsMemberInfo->cardId,$this->hotel_group_id);
            }else{
                return ;//不做处理
            }


        }else{
            return ;//不做处理
        }
    }

}