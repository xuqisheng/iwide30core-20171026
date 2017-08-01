<?php
/**
 * 速8 webservice 接口封装类
 *
 * 注意：接口公共调用方法名称及返回数据与速8文档百分百一致，如果封装类做了接口输出的修改
 *       必须编写相应的接口文档和增加备注
 *
 * 调试说明：
 * 		1.如接口是int类型，如为空时必须填写0。
 *      2.所有页数(pageIndex)都用1开头为第一页
 *
 *  酒店接口（Hotel）：http://ct.super8.com.cn/WeChatAPI/Hotel.svc?wsdl
订单接口（Book）：http://ct.super8.com.cn/WeChatAPI/Book.svc?wsdl
会员接口（Mem）：http://ct.super8.com.cn/WeChatAPI/Mem.svc?wsdl
公共接口（Common）：http://ct.super8.com.cn/WeChatAPI/Common.svc?wsdl
城市区域接口（Region）：http://ct.super8.com.cn/WeChatAPI/Region.svc?wsdl
详写速8接口文档
 * @author Race
 * @since 2016-04-18
 * @version v0.5
 * @change date 2016-04-26
 *
 */
class Subaapi_webservice{

	private $inter_id = "";

	private $open_id = "";

	private $CI = "";

	const SEND_REGISTER_MSG = 9;    //分销员注册=9，
	const SEND_VERIFY_MSG    = 10;  //微信短信验证=10，
	const SEND_SET_PASSWORD_MSG = 11;  //微信密码找回=11，

	//酒店接口api名称，与下面api_url key相对应
	const API_HOTEL = 'hotel';

	//订单接口api名称，与下面api_url key相对应
	const API_BOOK = 'book';

	//会员接口api名称，与下面api_url key相对应
	const API_MEM = 'mem';

	//会员接口api名称，与下面api_url key相对应
	const API_COMMON = 'common';

	//会员接口api名称，与下面api_url key相对应
	const API_REGION = 'region';

	//密码密钥
	const PASSWORD_KEY = "^s#8(ioz!~";


	//正式接口
	var $api_url = array(

		//酒店接口
		'hotel'=>'http://wxapi.super8.com.cn/Hotel.svc?wsdl',

		//订单接口
		'book'=>'http://wxapi.super8.com.cn/Book.svc?wsdl',

		//会员接口
		'mem'=>'http://wxapi.super8.com.cn/Mem.svc?wsdl',

		//公共接口
		'common'=>'http://wxapi.super8.com.cn/Common.svc?wsdl',

		//城市区域接口
		'region'=>'http://wxapi.super8.com.cn/Region.svc?wsdl',

	);

	//测试接口
	var $api_url_test = array(

		//酒店接口
		'hotel'=>'http://ct.super8.com.cn/WeChatAPI/Hotel.svc?wsdl',

		//订单接口
		'book'=>'http://ct.super8.com.cn/WeChatAPI/Book.svc?wsdl',

		//会员接口
		'mem'=>'http://ct.super8.com.cn/WeChatAPI/Mem.svc?wsdl',

		//公共接口
		'common'=>'http://ct.super8.com.cn/WeChatAPI/Common.svc?wsdl',

		//城市区域接口
		'region'=>'http://ct.super8.com.cn/WeChatAPI/Region.svc?wsdl',

	);


	//测试模式,true使用测试的接口，为false使用正式的接口
	var $_testModel = false;

	//是否debug调试，为true即没有返回，只有输出
	var $debug = false;

	//本地测试（为true时只输出，没有返回）
	var $local_test = false;

	//本地开发，如果为true即在本地调用服务端来转发
	var $local_develop = false;


	/**
	 *
	 * @param unknown $inter_id 酒店集团id
	 * @param unknown $open_id 用户openid
	 */
	function __construct($testModel = false,$inter_id = '',$open_id = ''){

		$this->_testModel = false;

		$this->inter_id = $inter_id;

		$this->open_id = $open_id;

		if($this->local_test == false){

			$this->CI = CI_Controller::get_instance();

		}

	}

	/**
	 * 地区列表接口，参照速8文档，没有对接口返回进行修改
	 */
	public function GetCity(){


		return $this->sendTo(self::API_REGION,"GetCity", "");

	}


	/**
	 * 酒店列表接口，参照速8文档，没有对接口返回进行修改
	 * @param string $CityCode 城市代码
	 * @param string $ArrDate 入住日期	“yyyy-MM-dd”
	 * @param string $OutDate 离店日期	“yyyy-MM-dd”
	 * @param string $RoomCount 房间数量	默认1间
	 * @param number $SortType
	 * 						排序类型	推荐：1（默认）
	按价格：2
	按好评率：3
	按距离排序：4
	 * @param number $PageIndex 当前页数
	 * @param number $PageSize 每页条数
	 */
	public function GetHotels($CityCode,$ArrDate,$OutDate,$RoomCount = 1,$SortType = 1,$PageIndex = 1,$PageSize = 1000){

		/*
		数据说明：
		HotelID	Nullable<int>	否	酒店标示
		CityCode	String	是	城市代码
		RegionCode	String	否	行政区域代码
		LandMarkID	Nullable<int>	否	商圈/地铁标示
		ArrDate	String	是	入住日期	“yyyy-MM-dd”
		OutDate	String	是	离店日期	“yyyy-MM-dd”
		RoomCount	Int	是	房间数量	默认1间
		Honour
		Nullable<int>	否	酒店荣誉	通过位运算取多个运算后的结果
		NewOpen	Nullable<int>	否	是否新开业	1：是；2：否；
		Exchange	Nullable<int>	否	是否包含兑换房	1：是；2：否；
		Vouchers	Nullable<int>	否	是否可用代金券	1：是；2：否；
		Longitude	Nullable<double>	否	经度	位置信息，用于酒店距离排序
		Latitude	Nullable<double>	否	纬度
		SortType	Int	是	排序类型	推荐：1（默认）
		按价格：2
		按好评率：3
		按距离排序：4
		PageIndex	Int	是	当前页数
		PageSize	Int	是	每页条数 */

		$searchModel = array(
			//	'HotelID'=>'',
			'CityCode'=>$CityCode,
			//'RegionCode'=>'',
			//'LandMarkID'=>'',
			'ArrDate'=>$ArrDate,
			'OutDate'=>$OutDate,
			'RoomCount'=>$RoomCount,
			/*'Honour'=>'',
			   'NewOpen'=>'',
			  'Exchange'=>'',
			  'Vouchers'=>'',
			  'Longitude'=>'',
			  'Latitude'=>'', */
			'SortType'=>$SortType,
			'PageIndex'=>$PageIndex,
			'PageSize'=>$PageSize
		);

		$send_data = array(

			'searchModel'=>$searchModel

		);



		return $this->sendTo( self::API_HOTEL,"GetHotels", $send_data);

	}



	/**
	 *
	 * 酒店详情接口，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */
	public function GetHotelDetail($hotelID){



		$searchModel = array(

			'hotelID'=>$hotelID,

		);

		$send_data = $searchModel;


		return $this->sendTo(self::API_HOTEL,"GetHotelDetail", $send_data);

	}

    /**
     * *通过CustomerID获取会员信息
     * @param $CustomerID
     * @return array
     */
    public function GetCustomerByCustomerID($CustomerID){

        $data = array(
            'customerId'=>$CustomerID,
        );
        $res = $this->sendTo(self::API_MEM,"GetCustomerByCustomerID", $data);

        return $res;
    }

    /**
     * *激活绑定会员卡
     * @param $openid
     * @param $cardNo
     * @return array
     */
    public function ScanCodeBindWeixinCustomer($openid,$cardNo){
        $bind_data=array(
            'openId'=>$openid,
            'cardNo'=>$cardNo
        );

        $res=$this->sendTo(self::API_MEM,"ScanCodeBindWeixinCustomer", $bind_data);

        if ( !empty($res) && isset($res['ScanCodeBindWeixinCustomerResult']) && $res['ScanCodeBindWeixinCustomerResult']['Content']){
            return  array(
                'status' => true,
                'msg'    => ''
            );
        }else{
            return array(
                'status' => false,
                'msg' => '会员绑定失败'
            );
        }
    }

	/**
	 *
	 * 获取某酒店所有房型接口，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 * @param string $ArrDate 入住日期	“yyyy-MM-dd”
	 * @param string $OutDate 离店日期	“yyyy-MM-dd”
	 * @param string $RoomCount 房间数量	默认1间
	 */
	public function GetHotelRooms($hotelID,$ArrDate,$OutDate,$RoomCount){


		/*
		 	数据参考
			HotelID	int	是	酒店标示
			ArrDate	String	是	入住日期	“yyyy-MM-dd”
			OutDate	String	是	离店日期	“yyyy-MM-dd”
			RoomCount	Int	是	房间数量	默认1间
			RoomTypeID	Nullable<int>	否	房型标示	获取房型详情时，房型标示和房型代码不可全为空
			RoomCode
			String	否	房型代码
			CardTypeID
			Nullable<int>	否	当前会员类型标示

		 */

		$searchModel = array(

			'HotelID'=>$hotelID,
			'ArrDate'=>$ArrDate,
			'OutDate'=>$OutDate,
			'RoomCount'=>$RoomCount,

		);

		$send_data = array(

			'searchModel'=>$searchModel

		);

		//$this->local_test = true;
		return $this->sendTo( self::API_HOTEL , "GetHotelRooms", $send_data);


	}


	/**
	 *
	 * 获取某酒店所有图片，包括外观，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */
	public function GetHotelImgs($hotelID){


		/*
		 数据参考
		hotelID	int	是	酒店标示


		*/

		$searchModel = array(

			'hotelID'=>$hotelID


		);

		$send_data = $searchModel;


		return $this->sendTo( self::API_HOTEL, "GetHotelImgs", $send_data);

	}



	/**
	 *
	 * 获取某酒店点评总数据，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */
	public function GetHotelCommentCount($hotelID){


		/*
		 数据参考
		hotelID	int	是	酒店标示


		*/

		$searchModel = array(

			'hotelID'=>$hotelID

		);

		$send_data = $searchModel;


		return $this->sendTo(self::API_HOTEL , "GetHotelCommentCount", $send_data);

		/*
		 *返回数据解析：
		 *	数据变量名	数据类型	作用说明	备注
			HotelID	Int	酒店标示
			CommentRate	String	好评率
			CommentCount	Int	评论总数
			WebCount	Int	网络评论数
			SMSCount	Int	短信评论数
			T1Count30	Int	30天非常满意
			T2Count30	Int 	30天满意
			T3Count30	Int	30天一般
			T4Count30	Int	30天不满意
			T5Count30	Int	30天非常不满意
			T1Count90	Int	90天非常满意
			T2Count90	Int	90天满意
			T3Count90	Int	90天一般
			T4Count90	Int	90天不满意
			T5Count90	Int	90天非常不满意
			T1Count180	Int	180天非常满意
			T2Count180	Int	180天满意
			T3Count180	Int	180天一般
			T4Count180	Int	180天不满意
			T5Count180	Int	180天非常不满意

		 */


	}

	/**
	 *
	 * 获取某酒店wifi信息
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */
	public function GetHotelWifiInfo($hotelID){



		$searchModel = array(

			'hotelID'=>$hotelID

		);

		$send_data = $searchModel;


		return $this->sendTo(self::API_HOTEL , "GetHotelWifiInfo", $send_data);



	}

	/**
	 *
	 * 获取某酒店点评列表，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */
	public function GetHotelComments($hotelID,$commentType = 0,$pageIndex = 1,$pageSize = 100){


		/*
		 数据参考
		数据变量名	数据类型	必填	作用说明	备注
		hotelID	Int	是	酒店标示
		commentType	Int	是	点评类型	0：全部；
		1：网络；
		2：短信；
		pageIndex	Int	是	当前页数
		pageSize	Int	是	每页条数

		*/

		$searchModel = array(

			'hotelID'=>$hotelID,
			'commentType'=>$commentType,
			'pageIndex'=>$pageIndex,
			'pageSize'=>$pageSize


		);

		$send_data = $searchModel;


		return $this->sendTo(self::API_HOTEL,"GetHotelComments", $send_data);




	}


	/**
	 *
	 * 获取某酒店点评列表，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */

	/**
	 * 获取某酒店点评列表，参照速8文档，没有对接口返回进行修改
	 * @param string $OrderID 订单号
	 * @param string $CustomerID 会员ID 非会员卡，可用会员获取接口获取会员id
	 * @param unknown $Result   满意 = 1
	满意 = 2
	一般 = 3
	不满意 = 4
	非常不满意 = 5
	 * @param unknown $Content 点评内容	长度不能超过500
	 * @param unknown $Feels
	 *                      分项点评结果	一共6位长度字符串,
	每位用0或1表示不满意和满意 （类似荣誉店的移位判断）
	位置1: 服务态度
	位置2: 清洁卫生
	位置3: 性价比
	位置4: 洗浴舒适度
	位置5: 睡眠环境
	位置6: 网速
	 * @param unknown $IpAddress ip地址，转入顾客的地址。
	 * @return Ambigous <void, array>
	 */
	public function AddComment($OrderID,$CustomerID ,$Result,$Content,$Feels,$IpAddress,$Recommend=10){


		/*
		 数据参考
		ActiveVerifyCode	String	否	活动代码	为空(速8自用)
		OrderID	Int	是	订单号
		CustomerID	Long	是	会员ID	当前会员ID
		ChannelType	int	是	点评渠道	手机APP=2
		手机H5=3
		微信=4
		Result	Int	是	点评结果	非常满意 = 1
		满意 = 2
		一般 = 3
		不满意 = 4
		非常不满意 = 5
		Content	String	是	点评内容	长度不能超过500
		Feels	String(6)	是	分项点评结果	一共6位长度字符串,
		每位用0或1表示不满意和满意
		位置1: 服务态度
		位置2: 清洁卫生
		位置3: 性价比
		位置4: 洗浴舒适度
		位置5: 睡眠环境
		位置6: 网速

		IpAddress	string	是	点评用户ID

		Recommend   int 0-10 从不推荐到极力推荐
		*/

		$searchModel = array(

			'OrderID'=>$OrderID,
			'CustomerID'=>$CustomerID,
			'ChannelType'=>4,
			'Result'=>$Result,
			'Content'=>$Content,
			'Feels'=>$Feels,
			'IpAddress'=>$IpAddress,
			'Recommend'=>$Recommend



		);

		$send_data = array(

			'commentInfo' => $searchModel

		);


		return $this->sendTo(self::API_MEM,"AddComment", $send_data);

		/*
		 * 错语时返回
		 * stdClass Object
			(
			    [AddCommentResult] => stdClass Object
			        (
			            [IsError] => 1
			            [ResultCode] => -5
			            [Message] => 此单未离店不可以点评
			            [CurTime] => 2016-04-28T12:04:28.5429942+08:00
			        )

			)
		 */



	}






	/**
	 *
	 * 获取焦点图列表，参照速8文档，没有对接口返回进行修改
	 * @param string $hotelID 酒店id，从酒店列表获得
	 */
	public function GetWebNewsInfo(){


		$searchModel = array(

			'pageIndex'=>1,
			'pageSize'=>5

		);

		$send_data = $searchModel;


		return $this->sendTo( self::API_COMMON,"GetWebNewsInfo", $send_data);


		/*
		 * 部分返回参数说明：
		 * NewsInfoID	Int	焦点图/新闻标示
			Title	String	标题
			Content	String	图片地址/新闻内容
		OrderNo	Int	排序
		URL	String	活动链接

		 */


	}


	/**
	 *
	 * 获取焦点图列表，参照速8文档，没有对接口返回进行修改
	 * @param string $cityCode 城市代码，通过GetCity取得
	 */
	public function GetLandMark($cityCode){


		$searchModel = array(

			'regionCode'=>$cityCode

		);

		$send_data = $searchModel;

		return $this->sendTo(self::API_REGION , "GetLandMark", $send_data);


		/*
		 * 部分返回参数说明：
		* NewsInfoID	Int	焦点图/新闻标示
		Title	String	标题
		Content	String	图片地址/新闻内容
		OrderNo	Int	排序
		URL	String	活动链接

		*/


	}






	/**
	 *
	 * 下订单，参照速8文档，没有对接口返回进行修改
	 * 速8的下单是如果预付的订单，10分钟未调支付订单接口就撤消订单
	 * @param string $searchModel 参考接口文档数组
	 */
	public function BookOrder($searchModel){


		/*
		 数据变量名	数据类型	必填	作用说明
		HotelID	Int	是	酒店标示
		Channel
		Int	是	渠道标示
		RoomTypeID	Int	是	房型标示
		ArrDate	String	是	入住日期
		OutDate	String	是	离店日期
		HoldTime	String	是	保留时间
		RoomCount	Int	是	房间数量
		GuestName	String	是	入住人姓名
		GuestMobile	String	是	入住人手机
		RateCode
		String	是	房价代码
		TotalPrice	Double	是	总房价
		ContactName	String	是	联系人姓名
		ContactMobile	String	是	联系人手机
		ContactEmail	String	否	联系人邮箱
		DailyPrices	List<DailyPrice>
		是	每日价格
		UsedAmAccount	Double	否	订单使用账户余额金额
		UsedCoupons	List<UsedCoupon>
		否	订单使用代金券信息
		PayType	Int	是	支付类型
		PayChannelID
		Nullable<int>	否	支付方式
		GuaranteeInfo	GuaranteeInfo	否	担保信息
		CustomerID	Nullable<int>	否	会员标示
		CustomerName	String	否	会员姓名
		CardNo	String	否	会员卡号
		CardTypeID	Nullable<int>	否	会员卡类型标示
		Remark	String	否	订单备注


		*/

		// =

		//$hotelTime = date()strtotime($OutDate)


		$send_data = $searchModel;


		return $this->sendTo(self::API_BOOK ,  "BookOrder", $send_data);



	}


	/**
	 *
	 * 会员登录接口，参照速8文档，没有对接口返回进行修改
	 * @param unknown $loginName 登录名
	 * @param unknown $password 密码
	 * @param number $customerType 会员类型	1：普通；2：企业；
	 * @return Ambigous <void, array>
	 */
	public function Login($loginName,$password,$customerType = 1){

		$password = md5( md5($password) . self::PASSWORD_KEY );

		$searchModel = array(

			'loginName' => $loginName,
			'password'=>$password,
			'customerType'=>$customerType

		);

		/* loginName	String	是	用户名	会员卡号/手机号
		password	String	是	密码	MD5(MD5(password)+”私钥（速8提供）”)
		customerType	Int	是	会员类型	1：普通；2：企业； */

		$send_data = $searchModel;


		return $this->sendTo( self::API_MEM,"Login", $send_data);

		/* [RegisterResult] => stdClass Object
		 (
		 		[IsError] =>
		 		[ResultCode] => 00
		 		[Message] =>
		 		[Content] => stdClass Object
		 		(
		 				[CustomerID] => 13955755
		 				[CustomeName] => 测试007
		 				[LoginName] => 13560428181
		 				[PhoneNum] => 13560428181
		 				[Gender] =>
		 				[IDTypeID] =>
		 				[MainCardNO] => 303503093
		 				[MainCardTypeID] => 1
		 				[TotalPoints] => 0
		 				[UsablePoints] => 0
		 				[UsableAmount] => 0
		 				[UsableCoupon] => 0
		 				[UsableCouponCount] => 0
		 				[FavoriteCount] => 0
		 				[OrderCount] => 0
		 		)

		 		[CurTime] => 2016-04-21T17:00:31.3555171+08:00
		 ) */



	}





	/**
	 *
	 * 会员注册接口，参照速8文档，没有对接口返回进行修改
	 * @param unknown $CustomeName 会员姓名 不能为空
	 * @param unknown $Password 密码 不能为空
	 * @param number $PhoneNum 手机号码 不能为空
	 * @param number $Email 邮件
	 * @param number $Email 性别，1男，2女默认为空
	 * @return Ambigous <void, array>
	 */
	public function Register($CustomeName,$Password,$PhoneNum,$Email = 0,$Gender = 0 ){





		/* OperationType	Int	是	操作类型	1：注册；2：激活；
		 CustomerID	Nullable<int>	否	会员标示	激活必填
		CustomeName	String	是	会员姓名
		Password	String	否	密码	为空则随机生成，并发送到手机
		PhoneNum	String	是	手机号码
		Email	String	否	邮箱
		Gender	Nullable<int>	否	性别
		IDTypeID
		Nullable<int>	否	证件类型
		IDNo	String	否	证件号码
		Address	String	否	地址
		CardNo	String	否	会员卡号	激活必填
		VerifyCode	String	否	卡号验证码	激活必填
		GetMean	Nullable<int>	否	获取方式
		ActivateChannel	Int	是	激活渠道	6
		ActivateCode	String	是	活动代码	无卡注册必填 微信2013
		UserID	Nullable<int>	否	系统用户标示
		*/
		$searchModel = array(

			'OperationType'=>1,

			'CustomeName'=>$CustomeName,
			'Password'=>$Password,

			'Email'=>$Email,
			'Gender'=>$Gender,

			'PhoneNum'=>$PhoneNum,
			'ActivateChannel'=>7,
			'ActivateCode'=>'微信2013'

		);

		if(!$Email){

			unset($searchModel['Email']);

		}

		if(!$Gender){

			unset($searchModel['Gender']);

		}

		//$Email = $Email?$Email:0;
		//$Gender = $Gender?$Gender:0;

		/* loginName	String	是	用户名	会员卡号/手机号
		 password	String	是	密码	MD5(MD5(password)+”私钥（速8提供）”)
		customerType	Int	是	会员类型	1：普通；2：企业； */

		$send_data = array(

			'model'=>$searchModel

		);


		return $this->sendTo(  self::API_MEM,"Register", $send_data);



	}


	/**
	 *
	 * 获取会员资料接口，参照速8文档，没有对接口返回进行修改
	 * @param unknown $cardNo 会员卡号，注：非会员id
	 */
	public function GetCustomer($cardNo){


		$searchModel = array(

			'cardNo'=>$cardNo,


		);

		/* loginName	String	是	用户名	会员卡号/手机号
		 password	String	是	密码	MD5(MD5(password)+”私钥（速8提供）”)
		customerType	Int	是	会员类型	1：普通；2：企业； */

		$send_data = $searchModel;

		return $this->sendTo( self::API_MEM,"GetCustomer", $send_data);



	}


	/**
	 *
	 * 修改会员资料接口，参照速8文档，没有对接口返回进行修改
	 * @param unknown $cardNo 会员卡号，注：非会员id
	 */
	public function ModifyCustomer($cardNo,$CustomeName,$PhoneNum = NULL,$Gender = 0,$Email = 0){



		/* 	LoginName	String		登录名
			CustomeName	String		会员姓名
			Gender	Nullable<int>		性别
			PhoneNum	String		会员手机
			Email	String		会员邮箱 */
		$Email = $Email?$Email:0;
		$Gender = $Gender?$Gender:0;
		$PhoneNum = $PhoneNum?$PhoneNum:0;

		$searchModel = array(


			'CustomerID'=>0,

			'CustomeName'=>$CustomeName,


			'Gender'=>$Gender,

			'PhoneNum'=>$PhoneNum,

			'Email'=>$Email,

			'MainCardNO'=>$cardNo,

			'TotalPoints'=>0,
			'UsablePoints'=>0,
			'UsableAmount'=>0,
			'UsableCoupon'=>0,
			'UsableCouponCount'=>0,
			'FavoriteCount'=>0,
			'OrderCount'=>0


		);

		if(!$Email){

			unset($searchModel['Email']);

		}

		if(!$Gender){

			unset($searchModel['Gender']);

		}

		if(!$PhoneNum){

			unset($searchModel['PhoneNum']);

		}

		/* loginName	String	是	用户名	会员卡号/手机号
		 password	String	是	密码	MD5(MD5(password)+”私钥（速8提供）”)
		customerType	Int	是	会员类型	1：普通；2：企业； */

		$send_data = array(

			'model'=>$searchModel

		);


		return $this->sendTo(  self::API_MEM,"ModifyCustomer", $send_data);



	}



	/**
	 *
	 * 修改会员密码接口，参照速8文档，没有对接口返回进行修改
	 * @param string $cardNo 会员卡号，注：非会员id
	 * @param string $oldPassword 旧密码
	 * @param string $newPassword 新密码
	 */
	public function ChangePassword($cardNo,$oldPassword,$newPassword){



		/* cardNo	String	是	会员卡号
		oldPassword	String	是	旧密码
		newPassword	String	是	新密码 */



		$searchModel = array(


			'cardNo'=>$cardNo,


			'oldPassword'=>$oldPassword,

			'newPassword'=>$newPassword,



		);

		/* loginName	String	是	用户名	会员卡号/手机号
		 password	String	是	密码	MD5(MD5(password)+”私钥（速8提供）”)
		customerType	Int	是	会员类型	1：普通；2：企业； */

		$send_data = $searchModel;


		return $this->sendTo(  self::API_MEM ,"ChangePassword", $send_data);



	}


	/**
	 *
	 * 获取会员积分接口，参照速8文档，没有对接口返回进行修改
	 * @param string $cardNo 会员卡号，注：非会员id
	 */
	public function GetCardUsablePoint($cardNo){


		$searchModel = array(


			'cardNo'=>$cardNo,


		);


		$send_data = $searchModel;

		return $this->sendTo(  self::API_MEM,"GetCardUsablePoint", $send_data);



	}


	/**
	 *
	 * 获取会员积分列表，参照速8文档，没有对接口返回进行修改
	 * @param string $cardNo 会员卡号，注：非会员id
	 *
	 */
	public function GetPoints($cardNo,$pageIndex = 1,$pageSize = 20){



		/* cardNo	String	是	会员卡号
		pageIndex	Int	是	当前页数
		pageSize	Int	是	每页条数
		*/
		$searchModel = array(

			'cardNo'=>$cardNo,
			'pageIndex'=>$pageIndex,
			'pageSize'=>$pageSize,

		);


		$send_data = $searchModel;


		return $this->sendTo(  self::API_MEM,"GetPoints", $send_data);

		/*
		 *
		返回
		其中有个direction参数，增加为1，减少为2
		array
		(
		[GetPointsResult] => stdClass Object
		(
				[IsError] =>
				[ResultCode] => 00
				[Message] =>
				[Content] => stdClass Object
				(
						[PageIndex] => 1
						[PageSize] => 20
						[PageCount] => 0
						[TotalCount] => 0
						[ListContent] => 详见速8接口文档
						(
						)

				)

				[CurTime] => 2016-04-25T22:20:04.0794073+08:00
		)

		)
		 *
		 */


	}


	/**
	 *
	 * 获取会员积分列表，参照速8文档，没有对接口返回进行修改
	 * @param string $cardNo 会员卡号，注：非会员id
	 * @param string $direction 方向101增加，102减少
	 * @param string $pageIndex 页数
	 * @param string $pageSize 每页条数
	 */
	public function GetAccounts($cardNo,$direction,$pageIndex = 1,$pageSize = 20){



		/* cardNo	String	是	会员卡号
			direction	Int	是	方向
			pageIndex	Int	是	当前页数
			pageSize	Int	是	每页条数

		*/
		$searchModel = array(

			'cardNo'=>$cardNo,
			'direction'=>$direction,
			'pageIndex'=>$pageIndex,
			'pageSize'=>$pageSize,

		);


		$send_data = $searchModel;


		return $this->sendTo(  self::API_MEM,"GetAccounts", $send_data);




	}




	/**
	 *
	 * 支付接口，下单选预付时，确认支付后再调用此接口，参照速8文档，没有对接口返回进行修改
	 * @param $searchModel 按接口输入
	 */
	public function PaymentOrder($searchModel){


		$send_data = $searchModel;

		return $this->sendTo( self::API_BOOK,"PaymentOrder", $send_data);



	}

    /**
	 * 取消订单
	 * @param string $orderNo pms订单号
	 * @param string $remark 取消原因
	 */
	public function CancelOrder($orderNo,$remark){


		$send_data = array(

			'orderNo'=>$orderNo,
			'remark'=>$remark

		);

		return $this->sendTo(  self::API_BOOK, "CancelOrder", $send_data);


	}


	/**
	 * 获取订单，确认支付后再调用此接口，参照速8文档，没有对接口返回进行修改
	 * @param unknown $orderNo
	 * @return string
	 */
	function GetOrder($orderNo){


		//'32495714'
		//传入订单
		$send_data = array(

			'orderNo'=>$orderNo,

		);


		return $this->sendTo(  self::API_BOOK, "GetOrder", $send_data);

		/**
		 *
		 * OrderStatus
		 * 房间状态
		等候 = 1,
		预订 = 5,
		在住 = 10,
		未到 = 15,
		离店 = 20,
		取消 = 25,
		归档 = 30,
		 */


	}


	/**
	 * 取代金券，参照速8文档，没有对接口返回进行修改
	 * @param unknown $cardNo 会员卡号
	 */
	public function GetCoupons($cardNo){

		/* cardNo	String	是	会员卡号
		pageIndex	Int	是	当前页数
		pageSize	Int	是	每页条数 */

		$send_data = array(

			'cardNo'=>$cardNo,
			'pageIndex'=>1,
			'pageSize'=>10000

		);

		return $this->sendTo(  self::API_MEM , "GetCoupons", $send_data);



	}





	/**
	 * 用于通过手机的验证用户身份，参照速8文档，没有对接口返回进行修改
	 * @param string $phoneNum
	 * @param string $newPassword
	 * @param string $verifyCode
	 * @return Ambigous <void, array>
	 */
	public function CheckMemberStatus($phoneNum){


		/* phoneNum	String	是	手机号码
			 */
		$send_data = array(

			'phoneNum'=>$phoneNum,

		);

		return $this->sendTo(  self::API_MEM , "CheckMemberStatus", $send_data);

	}


	/**
	 * 用于获取会员收藏列表，参照速8文档，没有对接口返回进行修改
	 * @param $member_id
	 * @param int $pageIndex
	 * @param int $pageSize
	 * @param null $cityCode
	 * @return array
	 */
	public function GetMyFavorites($cardNo,$pageIndex =1 ,$pageSize=200 ,$cityCode=NULL){

		/* phoneNum	String	是	手机号码
			 */
		$send_data = array(
			'cardNo'     => $cardNo,
			'pageIndex' => $pageIndex,
			'pageSize'  => $pageSize,
			'cityCode'  => $cityCode,
		);

		return $this->sendTo(  self::API_MEM , "GetMyFavorites", $send_data);

	}


	/**
	 * 添加会员收藏，参照速8文档，没有对接口返回进行修改
	 * @param $MyFavoritesID    收藏标示
	 * @param $CustomerID       会员标示
	 * @param $HotelID          酒店标示
	 * @param $CardNo           会员卡号
	 * @param $NameCn           酒店名称
	 * @param $Merit            酒店卖点
	 * @param $Address          酒店地址
	 * @param $MinPrice         最低价格
	 * @param $PictureFile      酒店图片
	 * @param $CommentRate      好评率
	 * @param $CommentNum       好评数
	 * @return array
	 */
	public function AddMyFavorite($MyFavoritesID,$CustomerID,$HotelID,$CardNo,$HotelName,$Merit,$Address,$MinPrice,$PictureFile,$CommentRate,$CommentNum){
		/**
		MyFavoritesID	Int	收藏标示
		CustomerID	Int	会员标示
		HotelID	Int	酒店标示
		CardNo	String	会员卡号
		NameCn	String	酒店名称
		Merit	String	酒店卖点
		Address	String	酒店地址
		MinPrice	Double	最低价格
		PictureFile	String	酒店图片
		CommentRate	Int	好评率
		CommentNum	Int	好评数
		 */
		$MyFavorite = array(
			'MyFavoritesID' =>  $MyFavoritesID,
			'CustomerID' =>     $CustomerID,
			'HotelID' =>        $HotelID,
			'CardNo' =>         $CardNo,
			'HotelName' =>     $HotelName,
			'Merit' =>          $Merit,
			'Address' =>        $Address,
			'MinPrice' =>       $MinPrice,
			'PictureFile' =>    $PictureFile,
			'CommentRate' =>    $CommentRate,
			'CommentNum' =>      $CommentNum
		);
		$send_data = array(
			'myFavorite'    =>  $MyFavorite
		)   ;

		return $this->sendTo(  self::API_MEM , "AddMyFavorite", $send_data);

	}


	/**
	 * 批量删除会员收藏
	 * @param $lists (array)
	 * @return array
	 */
	public function DeleteMyFavorites($lists){

		$send_data = array(
			'myFavoriteIDs'   => $lists

		);
		return $this->sendTo(  self::API_MEM , "DeleteMyFavorites", $send_data);
	}



	/**
	 * 用于通过手机的找回密码的发送短信接口，参照速8文档，没有对接口返回进行修改
	 * @param      $phoneNum
	 * @param      $client_ip
	 * @param int  $VerifyType
	 * @param bool $Forced
	 * @return array|void
	 */
	public function SendVerifySMS($phoneNum,$client_ip,$VerifyType = 7,$Forced=false){

		$client_ip = $client_ip?$client_ip:NULL;

		/* phoneNum	String	是	手机号码
		 * CustomerID	Nullable<int>	否	会员标示
			VerifyType	Int	是	验证类型	H5短信验证 = 3
            分销员注册=9，
            微信短信验证=10，
            微信密码找回=11，
			H5密码找回 = 7
			IPAddress	String	否	客户端IPAddress

		 */
		$send_data = array(

			'PhoneNum'=>$phoneNum,
			'CustomerID'=>0,
			'VerifyType'=> $VerifyType,
			'IPAddress'=>$client_ip,
			'Forced'=>$Forced

		);

		$data['model'] = $send_data;

		return $this->sendTo(  self::API_COMMON , "SendVerifySMS", $data);

	}



	/**
	 * 找回密码功能，使用前需调用CheckMemberStatus，SendVerifySMS接口，参照速8文档，没有对接口返回进行修改
	 * @param string $phoneNum
	 * @param string $newPassword
	 * @param string $verifyCode
	 * @return Ambigous <void, array>
	 */
	public function ForgetPassword($phoneNum,$newPassword,$verifyCode){


		/* phoneNum	String	是	手机号码
		newPassword	String	是	新密码
		verifyType	Int	是	验证码类型
		verifyCode	String	是	手机验证码 */
		$send_data = array(

			'phoneNum'=>$phoneNum,
			'newPassword'=>$newPassword,
			'verifyType'=> self::SEND_SET_PASSWORD_MSG ,//速8定议
			'verifyCode'=>$verifyCode



		);

		return $this->sendTo(  self::API_MEM , "ForgetPassword", $send_data);

	}


	/**
	 * 微信会员绑定功能，使用前需调用CheckMemberStatus，SendVerifySMS接口，参照速8文档，没有对接口返回进行修改
	 * @param string $openid
	 * @param string $cardNo
	 * @return Ambigous <void, array>
	 */
	public function BindWeixinCustomer($openid,$cardNo){



		$send_data = array(

			'openId'=>$openid,
			'cardNo'=>$cardNo,




		);

		return $this->sendTo(  self::API_MEM , "BindWeixinCustomer", $send_data);

	}


	/**
	 * 微信会解绑功能，使用前需调用UnBindWeixinCustomer，参照速8文档，没有对接口返回进行修改
	 * @param string $openid
	 * @param string $cardNo
	 * @return Ambigous <void, array>
	 */
	public function UnBindWeixinCustomer($openid){
		$send_data = array(
			'openId'=>$openid
		);
		return $this->sendTo(  self::API_MEM , "UnBindWeixinCustomer", $send_data);
	}

	/**
	 * 通过微信openid取会员信息，使用前需调用CheckMemberStatus，SendVerifySMS接口，参照速8文档，没有对接口返回进行修改
	 * @param string $openid
	 * @return Ambigous <void, array>
	 */
	public function GetWeixinCustomer($openid){



		$send_data = array(

			'openId'=>$openid,


		);

		return $this->sendTo(  self::API_MEM , "GetWeixinCustomer", $send_data);

	}

	/**
	 * 通过微信openid取会员信息
	 * @param $openid
	 * @return mixed
	 */
	public function GetBalanceByOpenid($openid){
		$userInfo = $this->GetWeixinCustomer($openid);
		if(isset($userInfo['GetWeixinCustomerResult']) && ($userInfo['GetWeixinCustomerResult']['ResultCode']=='00')){
			return $userInfo['GetWeixinCustomerResult']['Content']['UsableAmount'];
		}

	}




	/**
	 * 速8关注接口：加关注时需接口速8关注接口，参照速8文档，没有对接口返回进行修改
	 * @param string $openid
	 * @return Ambigous <void, array>
	 */
	public function SubscribeWeixin($openid){


		$send_data = array(

			'openId'=>$openid,


		);

		return $this->sendTo(  self::API_MEM , "SubscribeWeixin", $send_data);

	}

	/**
	 * 短信接口
	 * @param unknown $phone_num
	 * @param unknown $content
	 * @return Ambigous <void, array>
	 */
	public function SendSMS($phone_num,$content){

		/* cardNo	String	是	会员卡号
			pageIndex	Int	是	当前页数
		pageSize	Int	是	每页条数 */

		$searchModel = array(

			'SendType'=>1,
			'SMSTypeID'=>9,
			'PhoneNum'=>$phone_num,
			'Content'=>$content,
			'CustomerID'=>0,
			'OrderID'=>0,
			'OrderVersion'=>0
		);

		//$searchModel = $searchModel;
		$send_data = array(

			"model" => $searchModel

		);

		return $this->sendTo(  self::API_COMMON,"SendSMS", $send_data);


	}


	/**
	 * 微信会员发放代金券接口，仅供大转盘活动使用
	 * @param string $data
	 * @return Ambigous <void, array>
	 */
	public function CouponRechargeByActivateCode($data)	{
		return $this->sendTo(  self::API_MEM , "CouponRechargeByActivateCode", $data);
	}


	/**
	 * 微信会员发放现金券接口，仅供大转盘活动使用
	 * @param string $data
	 * @return Ambigous <void, array>
	 */
	public function BalanceRechargeBySource($data)	{
		return $this->sendTo(  self::API_MEM , "BalanceRechargeBySource", $data);
	}

/**
     * 推送微信打赏信息，仅供微信打赏活动使用
     * @param string $data
     * @return Ambigous <void, array>
     */
    public function SetRewardInfo($data)	{
        $send_data = array(

            'model'=>$data

        );
        return $this->sendTo(  self::API_MEM , "SetRewardInfo", $send_data);
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

		//本地测试
		if($this->local_test == true){

			$this->localSendToTest($url,$method,$send_data);
			exit;

		}

		$now = time();

		try {


			if($this->local_develop == true){

				$returnData = $this->localSendTo($url, $method, $send_data);

			}else{

				$client = new SoapClient($url);

				if($send_data == ''){

					$returnData = $client->$method();

				}else{

					$returnData = $client->$method($send_data);

				}


			}

			$mirco_time = microtime ();
			$mirco_time = explode ( ' ', $mirco_time );
			$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );

			if($this->local_test == false){
				$this->log($url, $send_data, $returnData,$now, $wait_time, $method);
			}

			//return $client->$method_name();
			if($this->debug != true){

				return $this->objectToArray( $returnData );

			}else{

				$this->outputDebug($send_data, $returnData, $url,$method);

			}




		} catch (SOAPFault $e) {


			//echo "当前速8 接口调用失败（Super8Webservice->sendTo），失败信息为：\n\r\n\r";
			$error['error'] = $e;

			$mirco_time = microtime ();
			$mirco_time = explode ( ' ', $mirco_time );
			$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );

			if($this->local_test == false){
				$this->log( $url, $send_data, $error,$now, $wait_time, $method);
			}


			exit;

		}

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

		if($this->_testModel == true && isset($this->api_url_test[ $api_name ])){

			return $this->api_url_test[ $api_name ];

		}else if(isset($this->api_url[ $api_name ])){

			return $this->api_url[ $api_name ];

		}
		return NULL;
	}


	private function localSendToTest($url,$method,$send_data){

		$data['url'] = urlencode($url);

		$data['data'] = $send_data;

		$data['debug'] = 0;

		$data['method_name'] = $method;

		$jsondata = json_encode($data,JSON_UNESCAPED_UNICODE);


		$url = "http://credit.iwide.cn/index.php/api/Xu8Test/testApi";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式

		//curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));//设置HTTP头信息
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);//设置提交的字符串
		$document = curl_exec($ch);//执行预定义的CURL

		echo $document;

		exit;




	}

	private function localSendTo($url,$method,$send_data){


		$data['url'] = urlencode($url);

		$data['data'] = $send_data;

		$data['debug'] = 0;

		$data['method_name'] = $method;

		$jsondata = json_encode($data,JSON_UNESCAPED_UNICODE);


		$url = "http://credit.iwide.cn/index.php/api/Xu8Test/localTest";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式

		//curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));//设置HTTP头信息
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);//设置提交的字符串
		$document = curl_exec($ch);//执行预定义的CURL


		$data = json_decode( $document );



		return $data;




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

		$send_content = json_encode ( $send_data,JSON_UNESCAPED_UNICODE );
		$receive_content = json_encode($receive_data,JSON_UNESCAPED_UNICODE);

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

		MYLOG::pms_access_record($inter_id, $wait_time, $record_type, $url.'/'.$record_type, $send_content, $receive_content, $open_id);
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

	public function activeCustomer($customerName,$phoneNum,$cardNo,$verifyCode,$openid){
		$searchModel = array(
			'CustomeName'=>$customerName,
			'PhoneNum'=>$phoneNum,
			'CardNo'=>$cardNo,
			'VerifyCode'=>$verifyCode,
			'OpenID'=>$openid,
		);

		$send_data = array(

			'model'=>$searchModel

		);


		return $this->sendTo(  self::API_MEM,"ActivationCard", $send_data);
	}

	public function getCardInfo($card_no){
		$params = array('cardNo' => $card_no);
		return $this->sendTo(self::API_MEM, 'GetCardInfo', $params);
		//卡状态 1为可激活，3为已激活，其他的值为不可用
	}





	//private function get


}
if(!class_exists('HotelSearchModel')){
	class HotelSearchModel{
		public $HotelID=0;
		public $CityCode='';
		public $RegionCode='';
		public $Keywords='';
		public $RoomCount=1;
		public $ArrDate='0000-00-00';
		public $OutDate='0000-00-00';
		public $LandMarkID=0;
		public $CustomerID=0;
		public $Honour=0;
		public $NewOpen=0;
		public $Exchange=0;
		public $Vouchers=0;
		public $HasHourRoom=0;
		public $SpecialPriceType=0;
		public $Longitude=0.0;
		public $Latitude=0.0;
		public $SortType=1;
		public $PageIndex=1;
		public $PageSize=10;
		public  function dateFormat($date){
			return date('Y-m-d',strtotime($date));
		}
	}
}
if(!class_exists('RoomSearchModel')){
	class RoomSearchModel{
		public $HotelID=0;
		public $RoomTypeID=0;
		public $RoomCode='';
		public $RoomCount=1;
		public $ArrDate='0000-00-00';
		public $OutDate='0000-00-00';
		public $CardTypeID=0;
		public  function dateFormat($date){
			return date('Y-m-d',strtotime($date));
		}
	}
}
if(!class_exists('CommentModel')){
	class CommentModel{
		public $ActiveVerifyCode='';
		public $OrderID=0;
		public $CustomerID=0;
		public $ChannelType=4;
		public $Result=1;
		public $Content='';
		public $Feels='111111';
		public $IpAddress='';
	}
}


?>