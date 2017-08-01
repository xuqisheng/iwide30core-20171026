<?php
/**
 * 隐居 webservice 接口封装类
 * 
 * 注意：接口公共调用方法名称及返回数据与速8文档百分百一致，如果封装类做了接口输出的修改
 *       必须编写相应的接口文档和增加备注
 *       
 * 调试说明：
 * 		1.如接口是int类型，如为空时必须填写0。
 *      2.所有页数(pageIndex)都用1开头为第一页
 *
 *  /请求地址：http://61.177.58.132:11011/ipms/
 * /
 * @author Jake
 * @since 2016-05-04
 * @version v0.5
 *
 */
class Lvyunapi_webservice{
	
	private $inter_id = "";
	
	private $open_id = "";
	
	private $CI = "";

    var $apiUrl = '';

    private $SHUXIANGUR = 'http://61.177.58.132:11011/ipms/';

	//优惠券列表api名称，与下面api_url key相对应
	const API_COUPON = 'coupon';

	//会员接口api名称，与下面api_url key相对应
	const API_MEM = 'membercard';




	//正式接口
	var $api_url = array(

		//会员
		'membercard'=>'http://202.107.192.24:8090/ipms/membercard/',
			
		//优惠券
		'coupon'=> "http://202.107.192.24:8090/ipms/coupon/"
			
	);
	
	//测试接口
	var $api_url_test = array(

        //会员
//        $SHUXIANGURLS = 'http://61.177.58.132:11011/ipms/';
        'membercard'=>'http://61.177.58.132:11011/ipms/membercard',

        //优惠券
        'coupon'=> "http://202.107.192.24:8090/ipms1/coupon",
				
	);
	
	
	//测试模式,true使用测试的接口，为false使用正式的接口
	var $_testModel = false;
	
	//是否debug调试，为true即没有返回，只有输出
	var $debug = false;
	
	//本地测试（为true时只输出，没有返回）
	var $local_test = false;
	
	//本地开发，如果为true即在本地调用服务端来转发
	var $local_develop = true; 
	
	
	/**
	 * 
	 * @param unknown $inter_id 酒店集团id
	 * @param unknown $open_id 用户openid
	 */
	function __construct($testModel, $inter_id = '',$open_id = ''){

		$this->_testModel = $testModel;
		
		$this->inter_id = $inter_id;
		
		$this->open_id = $open_id;
		
		if($this->local_test == false){
			
			$this->CI = CI_Controller::get_instance();
			
		}
			
	}



    /**
     * 生成微信会员
     * @param $openId
     * @param $nickname
     * @param int $sex
     * @param $appId
     * @param int $subscribe
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function registerWxMember($openId,$nickname, $appId , $sex =0, $subscribe = 0 ,$hotelGroupId = 2 ){
        /*
     * hotelGroupId	int	集团编号
        hotelId	int	酒店编号	否
        openId	int	微信openId
        nickname	String	用户的昵称
        sex	String	 用户的性别		值为1时是男性，值为2时是女性，值为0时是未知
        passwordC	String	密码	否
        openType	String	第三方类型	否
        appId	int	 用户的标识		对当前公众号唯一
        subscribe	String	 用户是否订阅该公众号标识		值为0时，代表此用户没有关注该公众号，拉取不到其余信息
        city	String	 用户所在城市	否
        province	String	 用户所在省份	否
        country	String	用户所在国家	否
        language	String	 用户的语言	否	简体中文为zh_CN
        headImgUrl	String	 用户头像	否	最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
        subscribeTime	String	 用户关注时间		为时间戳。如果用户曾多次关注，则取最后关注时间
        unionid	String	同一平台下不同公众号相同的微信用户唯一ID	否	 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段
        remark	String	 公众号运营者对粉丝的备注	否	公众号运营者可在微信公众平台用户管理界面对粉丝添加备注
        groupId	String	分组识别码	否
        qrcodeValue	String	推广id	否	推广信息（如二维码）上带的个人信息（销售员等）
        qrcodeType	String	推广场景类型	否
        masterMemberId	String	登记单上的会员id	否
        masterId	String	登记单id	否
     */
        $send_data = array(
            "openId" => $openId,
            "nickname" => $nickname,
            "sex" => $sex,
            "appId"    => $appId,
            "subscribe" => $subscribe,
            "subscribeTime" => time(),
            "hotelGroupId" => $hotelGroupId
        );
        return $this->sendTo( self::API_MEM,"generateMemberWeixin", $send_data);
    }

    /**
     * 检查第三方登陆方式的OpenId是否存在
     * @param $openIdUserId
     * @param string $openIdType
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function verifyOpenIdIsExists($openIdUserId ,$openIdType="WEIXIN", $hotelGroupId=2 ){
        /**
         * hotelGroupId	int	集团编号	是
        openIdUserId	String	第三方登陆唯一标识	是
        openIdType	String	第三方类型	是	微信：WEIXIN
         */
        $send_data = array(
            "openIdUserId" => $openIdUserId,
            "openIdType"   => $openIdType,
            "hotelGroupId" => $hotelGroupId,
        );
        return $this->sendTo( self::API_MEM,"verifyOpenIdIsExists", $send_data);
    }

    /**
     * 微会员完善资料，并升级为网站会员
     * @param $memberId
     * @param $name
     * @param $birth
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function updateMemberInfoForWeiXin( $memberId, $name, $birth , $hotelGroupId = 2){

        /**
         * hotelGroupId	int	集团编号
        hotelId	int	酒店编号	否
        memberId	int	会员id
        name	String	姓名
        sex	String	性别	否
        idCode	String	证件类型	否
        idNo	String	证件号码	否
        birth	Datetime	生日
        mobile	String	手机号	否
        email	String	邮箱	否
        password	String	密码	否
        loginPwd	String	登录密码	否
         */
        $send_data = array(
            '$memberId' => $memberId,
            'name' => $name,
            'birth' => $birth,
            'hotelGroupId' => $hotelGroupId,
        );

        return $this->sendTo( self::API_MEM,"updateMemberInfoForWeiXin", $send_data);

    }


    /**
     * 验证性会员注册
     * @param $name
     * @param $sex
     * @param $idNo
     * @param $mobile
     * @param string $email
     * @param string $idType
     * @param int $verifyType
     * @param string $verifyHost
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function registerMemberCardApply($name, $sex, $idNo, $mobile, $email='',  $idType = '01', $verifyType=0, $verifyHost = "localhost", $hotelGroupId = 2){

        /**
         * hotelGroupId	int	集团编号
        hotelId	int	酒店编号	否	仅当直接为酒店注册会员，而不是集团注册时使用
        name	String	姓名
        sex	String	性别		男=1,女=2
        idType	String	证件类型		请在绿云系统“证件类型”中查询代码
        idNo	String	证件号
        mobile	String	手机号
        email	String	邮箱
        verifyType	String	验证方式		选择手机或邮箱（0表示手机，1表示邮箱），选择手机时发送短信，选择邮箱时发送邮件
        verifyHost	String	验证主机		发送邮件时，会在邮件中附带一个连接，这时候需要一个指定的http://verifyHost/.......来跳转回网站等，完成注册。
        cardType	String	会员计划	可选参数	仅用于信息发送，具体指定由下一步决定

         */
        $send_data = array(
            'name'  => $name,
            'sex'  => $sex,
            'idType'  => $idType,
            'idNo'  => $idNo,
            'mobile'  => $mobile,
            'email'    => $email,
            'verifyType'    => $verifyType,
            'verifyHost'    => $verifyHost,
            'hotelGroupId'  => $hotelGroupId,
        );
        return $this->sendTo( self::API_MEM,"registerMemberCardApply", $send_data);
    }


    /**
     * 验证性会员注册第二步
     * @param $applyId
     * @param $mobileOrEmail
     * @param $verifyCode
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function registerMemberCard( $applyId, $mobileOrEmail, $verifyCode , $hotelGroupId = 2){

        /**
         * hotelGroupId	int	集团编号
        hotelId	int	酒店编号	否	仅当直接为酒店注册会员，而不是集团注册时使用
        applyId	int	注册返回的id
        mobileOrEmail	String	手机号或邮箱		上一步填写的手机号码或邮箱地址。手机号码可由界面传递参数，邮箱号码会带在邮件的连接中
        verifyCode	String	验证码		请在界面提供输入
        openIdType	String	第三方类型	否	默认“”，不绑定第三方关系。
        绑定微信：WEIXIN
        openIdUserId	String	第三方登陆唯一标识	否	默认“”，或填写第三方的openid
        cardType	String	会员计划	否	默认值在参数设置->系统参数->‘默认会员计划’
        cardLevel	String	卡等级	否	默认值在参数设置->系统参数->‘默认会员等级’
        cardSrc	String	来源	否	默认值在参数设置->系统参数->‘会员卡来源’
        cardSales	String	卡销售员	否	默认值在参数设置->系统参数->‘注册时销售员’

         */
        $send_data = array(
            'applyId'  => $applyId,
            'mobileOrEmail'    => $mobileOrEmail,
            'verifyCode'   => $verifyCode,
            'hotelGroupId' => $hotelGroupId
        );

        return $this->sendTo( self::API_MEM,"registerMemberCard", $send_data);
    }


    /**
     * 检查手机，邮箱、证件是否已注册过会员
     * @param null $mobile
     * @param null $email
     * @param null $memberId
     * @param null $idCode
     * @param null $idNo
     * @return bool|mixed
     */
    public function checkDouble( $mobile=NULL, $email=NULL, $memberId=NULL,$idCode=NULL , $idNo=NULL ,$hotelGroupId = 2 ){

        /**
         hotelGroupId	int	集团编号
        memberId	String	会员id	否	新注册会员不填本参数。
        重复判断，将不包含本memberid对应的数据，常常用于更新前检查。
        idCode	String	证件类型	否
        idNo	String	证件号码	否
        mobile	String	手机号	否
        email	String	邮箱	否
         */
        $send_data = array(
            'mobile' => $mobile,
            'memberId'  => $memberId,
            'idCode'    => $idCode,
            'idNo'      => $idNo,
            'email'     => $email,
            'hotelGroupId'  => $hotelGroupId
        );

        $send_data = $this->unsetNullValue($send_data);

        return $this->sendTo( self::API_MEM,"checkDouble", $send_data);
    }

    /**
     * 会员登录
     * @param $loginId
     * @param $loginPassword
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function getMemberInfo($loginId,$loginPassword,$hotelGroupId = 2){
        $send_data = array(
            "loginId" => $loginId,//cardNo
            "loginPassword" => $loginPassword,
            "hotelGroupId" => $hotelGroupId
        );
        return $this->sendTo( self::API_MEM,"memberLogin", $send_data);

    }

    /**
     * //获取会员卡信息
     * @param $loginId
     * @param $loginPassword
     * @param int $hotelGroupId
     * @return bool|mixed
     */
    public function getMemberCard($loginId,$loginPassword, $type= 'YJVIP',$hotelGroupId = 2){
        $memberInfoObj = $this->getMemberInfo($loginId,$loginPassword,$hotelGroupId);
        $memberInfoObj = json_decode($memberInfoObj);

        if($memberInfoObj->resultCode != 0) return array(); //没有卡

        if(count($memberInfoObj->cardListDto) >= 1){
            foreach($memberInfoObj->cardListDto as $card){
                if($card->cardType != $type)
                    continue;
                else{
                    return $card;
                    break;
                }
            }
        }else{
            return array();
        }
    }


    /**
     * 会员卡升降级
     * @param $cardNo
     * @param $cardType
     * @param $cardLevel
     * @param int $hotelGroupId
     * @param null $cardId
     * @param null $hotelId
     */
    public function updateCardUpgrade($cardNo,$cardType,$cardLevel,$cardId,$hotelGroupId = 2,$hotelId=null){

        /**
         * hotelId	int	酒店编号	否
        cardId	int	会员id	是
        cardNo	String	会员卡号	是	这里的卡号有两种情况：1.升级卡号不变，则传入原卡号即可cardNo=原卡号；
        2.升级卡号变化：
        （1）系统自动生成，卡号传入空字符串即可cardNo=；
        （2）接口传入新卡号，cardNo=新卡号
        cardType	String	目标会员计划	是	要升级到的会员计划代码
        cardLevel	String	目标会员卡等级	是	要升级到的会员卡等级代码
         */
        $send_data = array(
            'cardNo'     => $cardNo,
            'cardType'   => $cardType,
            'cardLevel'  => $cardLevel,
            'hotelGroupId'   => $hotelGroupId,
            'cardId'     => $cardId,
            'hotelId'    => $hotelId
        );

        $send_data = $this->unsetNullValue($send_data);

        return $this->sendTo( self::API_MEM,"updateCardUpgrade", $send_data);


     }



    /**
     * @param $cardId
     * @param $cardNo
     * @param $beginDate
     * @param $endDate
     * @param int $hotelGroupId
     * @param string $firstResult
     * @param string $pageSize
     * @return bool|mixed
     */
    public function getAccountList($cardId, $cardNo, $beginDate , $endDate, $hotelGroupId = 2, $firstResult='' ,$pageSize='' ){

        /**
         * hotelGroupId	int	集团编号
        hotelId	int	酒店编号	否
        cardId	int	会员卡id
        cardNo	String	会员卡号
        tag	String	标签	否
        beginDate	Datetime	开始日期
        endDate	Datetime	截止日期
        firstResult	String	从第几条数据开始取	否
        pageSize	String	取的数据条数	否
         */
        $send_data = array(
            'cardId'    => $cardId,
            'cardNo'    => $cardNo,
            'beginDate' => $beginDate,
            'endDate'   => $endDate,
            'hotelGroupId'  => $hotelGroupId,
            'firstResult'  => $firstResult,
            'pageSize'  => $pageSize
        );

        $send_data = $this->unsetNullValue($send_data);

        return $this->sendTo( self::API_MEM,"getAccountList", $send_data);
    }


    public function getPointList($cardNo, $cardId,$beginDate,$endDate, $hotelGroupId=2){

        /**
         * hotelGroupId	int	集团编号
        cardId	int	会员卡id
        cardNo	String	会员卡号
        beginDate	String	开始日期
        endDate	Datetime	截止日期

         */
        $send_data = array(
            'hotelGroupId'  => $hotelGroupId,
            'cardId'        => $cardId,
            'cardNo'        => $cardNo,
            'beginDate'     => $beginDate,
            'endDate'       => $endDate
        );

        return $this->sendTo( self::API_MEM,"getPointList", $send_data);
    }

    /**
     * 获取会员储值余额
     * @param $cardId
     * @param $cardNo
     * @param int $hotelGroupId
     * @return bool|mixed
     * 系统尚未支持最新接口
    public function getCardBalanceInfo($cardId,$cardNo ,$hotelGroupId= 2,$tag = 'BASE'){

//        hotelGroupId	int	集团编号
//        cardId	int	会员卡id
//        cardNo	String	会员卡号

        $send_data = array(
            'hotelGroupId' => $hotelGroupId,
            'cardId'        => $cardId,
            'cardNo'        => $cardNo,
            'tag'           => $tag

        );
        return $this->sendTo( self::API_MEM,"getCardBalanceInfo", $send_data);
    }
    */

    /**
     * 根据会员卡号获取会员优惠券列表
     * @param $cardId 会员卡id
     * @param $cardNo 会员卡号
     * @param $hotelGroupId 集团编号
     * @return bool|mixed
     */
    public function findCouponDetailListByCardNo($cardId,$cardNo,$hotelGroupId = 2){
        $send_data = array(
            "cardId" => $cardId,//cardNo
            "cardNo" => $cardNo,
            "hotelGroupId"  => $hotelGroupId
        );
        return $this->sendTo( self::API_COUPON,"findCouponDetailListByCardNo", $send_data);
    }


    /**
     * 根据memberId获取会员优惠券列表
     * @param $memberId 会员id
     * @param null $isHistory
     * @param null $couponType
     * @param null $pageNum 页数
     * @param null $pageSize 页录数
     * @return bool|mixed
     */
    public function findCouponDetailListByMemberId($memberId, $isHistory=NULL , $couponType = NULL, $pageNum = NULL, $pageSize = NULL ){

        $send_data = array(
            "memberId" => $memberId,//cardNo
            "isHistory" => $isHistory,
            "pageNum"  => $pageNum,
            "pageSize"  => $pageSize,
            "couponType"    => $couponType
        );

        foreach($send_data as $k=>$v){
            if(empty($v)){
                unset($send_data[$k]);
            }
        }
        return $this->sendTo( self::API_COUPON,"findCouponDetailListByCardNo", $send_data);
    }


    /**
     * 根据传入条件获取可用的优惠券列表
     * @param $memberId 会员id
     * @param $rmtype 可升级房型
     * @param $rateCode 费用码
     * @param $useDate 使用日期(入住当天)
     * @param int $hotelGroupId
     * @param string $couponType
     * @return bool|mixed
     */
    //checkInDate=2016-05-06&checkOutDate=2016-05-07&adult=&child=&hotelId=21&rateCode=COUPON&roomType=YDY&cityCode=&bannerImages=BOOKINGIMG
    public function findCouponDetailListByCondi($memberId,$rmtype,$rateCode, $hotelId, $useDate, $useEndDate, $hotelGroupId= 2, $couponType = ""){

        /*
         * 参数名称	数据类型	描述	是否必填	备注
            hotelGroupId	int	集团编号
            hotelId	int	酒店编号	否
            number	String	记录数	否
            memberId	int	会员id
            rmtype	String	可升级房型
            rateCode	String	费用码
            useDate	Datetime	使用日期
            useEndDate	Datetime	使用截止日期	否
            couponType	String	电子券类型
            totalPrice	String	使用券的订单总价	否
            firstPrice	String	使用券的订单首日价	否
            arr	String	到日	否
         */
        $send_data = array(
            'hotelGroupId' => $hotelGroupId,
            'memberId'      => $memberId,
            'rmtype'        => $rmtype,
            'rateCode'      => $rateCode,
            'useDate'       => $useDate,
            'useEndDate'   => $useEndDate,
            'couponType'    => $couponType,
            'hotelId'  => $hotelId,
        );

        $send_data = $this->unsetNullValue($send_data);

        return $this->sendTo( self::API_COUPON,"findCouponDetailListByCondi", $send_data);
    }


	/**
	 * 以客户速度文档为准返回，不加任何处理，如改变了输出结构，必须编写相应的接口文档和备注
	 * @param string $url 接口地址
	 * @param string $method 接口方法
	 * @param string $send_data 发送数组
	 */
	public function sendTo($api_type,$method,$send_data){

		//@Editor lGh 2016-4-22 16:18:57 应优化为传模块类型即可
		$url = $this->getUrl($api_type);
        $url = $url."/".$method;

        $this->CI->load->helper ( 'common' );
        $data = http_build_query ( $send_data );
        $return = doCurlPostRequest ( $url, $data );

        //log
        $ci = $this->get_instance();
        $ci->load->library('session');
        $inter_id = $this->inter_id?$this->inter_id:$ci->session->userdata ( 'inter_id' );
        $this->CI->load->model('common/Webservice_model');
        $this->CI->Webservice_model->log_service_record(json_encode($data,JSON_FORCE_OBJECT),json_encode($return,JSON_UNESCAPED_UNICODE),$inter_id,'lvyun',$url,'query_get');

        return $return;

//
//		//本地测试
//		if($this->local_test == true){
//
//			$this->localSendToTest($url,$method,$send_data);
//			exit;
//
//		}
//
//		$now = time();
//
//		try {
//
//
//			if($this->local_develop == true){
//
//				$returnData = $this->localSendTo($url, $method, $send_data);
//
//			}else{
//
//				$client = new SoapClient($url);
//
//				if($send_data == ''){
//
//					$returnData = $client->$method_name();
//
//				}else{
//
//					$returnData = $client->$method_name($send_data);
//
//				}
//
//
//			}
//
//			$mirco_time = microtime ();
//			$mirco_time = explode ( ' ', $mirco_time );
//			$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
//
//			if($this->local_test == false){
//				$this->log($url, $send_data, $returnData,$now, $wait_time, $method);
//			}
//
//			//return $client->$method_name();
//			if($this->debug != true){
//
//				return $this->objectToArray( $returnData );
//
//			}else{
//
//				 $this->outputDebug($send_data, $returnData, $url,$method);
//
//			}
//
//
//
//
//		} catch (SOAPFault $e) {
//
//
//			//echo "当前速8 接口调用失败（Super8Webservice->sendTo），失败信息为：\n\r\n\r";
//			$error['error'] = $e;
//
//			$mirco_time = microtime ();
//			$mirco_time = explode ( ' ', $mirco_time );
//			$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
//
//			if($this->local_test == false){
//				$this->log( $url, $send_data, $error,$now, $wait_time, $method);
//			}
//
//
//			exit;
//
//		}
		
	}
	
	/**
	 * 
	 * debug输出
	 * @param string $send_data 发送数组
	 * @param string $get_data 取得数组
	 * @param string $url 接口地址
	 * @param string $method 接口方法
	 *
	 */
	private function outputDebug($send_data,$get_data,$url,$method){
		
		echo "调试模块开启，不会跑正式流程，只会输出接口调用数据,要关闭，请将Super8Webservice中的debug变量设为true \n\r";
		
		echo "当前测试模块启动状态为：{$this->_testModel}\n\r";
		
		echo "当前测试模块启动状态为：{$this->_testModel}\n\r\n\r";
		
		echo "发送url: \n\r";
		
		echo $url;
		
		echo "\n\r\n\r";
		
		echo "调用方法: \n\r";
		
		echo $method;
		
		echo "\n\r\n\r";
		
		echo "发送的数组为：\n\r";
		
		print_r($send_data);
		
		echo "\n\r\n\r";
		
		echo "接收的数组为：\n\r";
		
		print_r($get_data);
		
		
		
	}
	

	
	
	/**
	 * 取接口地址
	 * @param string $api_name 接口标识
	 */
	private function getUrl($api_name){

        return $this->apiUrl.$api_name;

		if($this->_testModel == true && isset($this->api_url_test[ $api_name ])){

			return $this->api_url_test[ $api_name ];

		}else if(isset($this->api_url[ $api_name ])){

			return $this->api_url[ $api_name ];

		}
		return NULL;
	}

	
	private function localSendToTest($url,$method,$send_data){

		$url = $url.$method;

        $send_data = http_build_query($send_data);


		echo $this->doCurlPostRequest($url,$send_data);

		exit;
	

	
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
        return curl_exec ( $con );
    }

	
	private function localSendTo($url,$method,$send_data){
        $url = $url.$method;
        $send_data = http_build_query($send_data);

        echo $this->doCurlPostRequest($url,$send_data);
        exit;

	}
	
	private function objectToArray($e){
		$e=(array)$e;
		foreach($e as $k=>$v){
			if( gettype($v)=='resource' ) return;
			if( gettype($v)=='object' || gettype($v)=='array' )
				$e[$k]=(array)$this->objectToArray($v);
		}
		return $e;
	}
	
	public function setLogInterIdAndOpenId($inter_id,$open_id){
		
		$this->inter_id = $inter_id;
		
		$this->open_id = $open_id;
		
	}
	
	
	private function log($url,$send_data,$receive_data,$now_time,$wait_time,$record_type){
		
		
		
		$ci = $this->get_instance();
		
		$ci->load->library('session');
		
// 		$now =  time ();
		//$s = doCurlGetRequest ( $url );
		$inter_id = $this->inter_id?$this->inter_id:$ci->session->userdata ( 'inter_id' );
		
		$open_id = $this->open_id?$this->open_id:$ci->session->userdata ( $inter_id . 'openid' );

		
		$this->CI->db->insert ( 'webservice_record', array (
				'send_content' => json_encode ( $send_data,JSON_UNESCAPED_UNICODE ),
				'receive_content' => json_encode($receive_data,JSON_UNESCAPED_UNICODE),
				'record_time' => $now_time,
				'inter_id' => $inter_id,
				'service_type' => 'suba',
				'web_path' => $url.'/'.$record_type,
				'record_type' => 'webservice',
				'openid'=>$open_id,
				'wait_time'=>$wait_time
		) );
		
		/* $now = time ();
		$s = doCurlGetRequest ( $url );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' ); */
		
	}
	
	private function &get_instance()
	{
		return CI_Controller::get_instance();
	}
	


    private function unsetNullValue($dataArray){
        if(!is_array($dataArray)) return;

        foreach($dataArray as $k => $v){
            if(empty($v))
                unset($dataArray[$k]);
        }
        return $dataArray;
    }
	
}



?>