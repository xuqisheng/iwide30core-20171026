<?php
include APPPATH . 'libraries/Soap/nusoap.php';

class ShijiApi{
	const AVAILABILITY = 'AvailabilityService.asmx?wsdl'; //(房态查询)服务
	const RESEVATION = 'ReservationService.asmx?wsdl';
	const PROFILE = 'ProfileService.asmx?wsdl';
	const INFORMATION = 'InformationService.asmx?wsdl';
	const SECURITY = 'SecurityService.asmx?wsdl';
	
	private $api_base;
	private $secret;
	
	private $inter_id;
	
	private $channel_code;
	private $market_code;
	private $source_code;
	private $member_source;
	
	private $user;
	private $pwd;
	
	private $CI;
	
	public function __construct($config){
		$this->CI =& get_instance();
		$this->CI->load->helper('common');
		$this->inter_id = $config['inter_id'];
		$this->api_base = $config['url'];
		$this->CI->load->model('common/Webservice_model');
		
		$this->channel_code = $config['channel_code'];
		if(!empty($config['market_code']))
			$this->market_code = $config['market_code'];
		if(!empty($config['source_code']))
			$this->source_code = $config['source_code'];
		if(!empty($config['member_source']))
			$this->member_source = $config['member_source'];
		
		if(!empty($config['user']))
			$this->user = $config['user'];
		
		if(!empty($config['pwd']))
			$this->pwd = $config['pwd'];
		if(!empty($config['url']))
			$this->url = $config['url'];
		
		if(!empty($config['secret'])){
			$this->secret = $config['secret'];
		}elseif(!empty($config['user']) && !empty($config['pwd'])){
			//登陆
			$this->appLogin($this->user, $this->pwd);
		}
	}
	
	/**
	 * 查询房型房价详情内容
	 * @param $params
	 *  array(
	 *  hotel_code=>酒店代码
	 *  arrival=>入住日期
	 *  departure=>离店日期
	 *  rooms=>房间数
	 *  rate_code=>价格代码
	 *  room_type=>房型代码
	 *  )
	 * @return mixed
	 */
	public function getRateDetailDaily($params){
		array_key_exists('rooms', $params) or $params['rooms'] = 1;
		$params['channel_code'] = $this->channel_code;
		
		$service = [
			'uri'  => self::AVAILABILITY,
			'func' => 'GetRateDetailDaily',
		];
		
		$res = $this->postService($service, $params);
		if(4001 == $res['header']['RetCode']){
			return $res['result'];
		}
		return $res['header'];
	}
	
	/**
	 * 查询单个酒店房型房价
	 * @param $params
	 *  array(
	 *  hotel_code：酒店代码
	 *  arrival：入住日期
	 *  departure：离店日期
	 *  extra_bed：加床数 OPTIONAL
	 *  adults：成人数
	 *  room_num：房间数
	 *  guesttype_code：客人类型【0000：散客，0001：协议客户，0002：会员，0003：团队】
	 *  cust_account：协议客户预订号 OPTIONAL
	 *  card_no：信用卡号 OPTIONAL
	 *  children：儿童数 OPTIONAL
	 *  channel：渠道代码【固定值：WEB】
	 *  )
	 * @return mixed
	 */
	public function getAvailability($params,$func_data=[]){
		array_key_exists('extra_bed', $params) or $params['extra_bed'] = 0;
		array_key_exists('adults', $params) or $params['adults'] = 1;
		array_key_exists('room_num', $params) or $params['room_num'] = 1;
		array_key_exists('children', $params) or $params['children'] = 0;
		array_key_exists('cust_account', $params) or $params['cust_account'] = '';
		array_key_exists('card_no', $params) or $params['card_no'] = '';
		array_key_exists('guesttype_code', $params) or $params['guesttype_code'] = '0000';
		
		$params['channel'] = $this->channel_code;
		
		$service = [
			'uri'  => self::AVAILABILITY,
			'func' => 'GetAvailability',
		];
		
		$res = $this->postService($service, $params,false,$func_data);
		if(4001 == $res['header']['RetCode']){
			return $res['result'];
		}
		return $res['header'];
	}
	
	/**
	 * 创建订单
	 * @param array $params
	 *          array(
	 *          Arrival=>到店日期
	 *          ArrivalTime=>到店时间
	 *          Keep_hour=>保留到几点
	 *          Departure=>离店日期
	 *          DepartTime=>离店时间 (optional)
	 *          Room_num=>房间数
	 *          Adults=>成人数
	 *          Children=>儿童数
	 *          Extra_bed=>加床数
	 *          Rate=>首日总价
	 *          Firstname=>入住人名字
	 *          Lastname=>入住人姓
	 *          Account=>客户协议号，如果是协议客户预订，则必填
	 *          Company=>公司名称 (optional)
	 *          Address=>地址 (optional)
	 *          Fax=>传真 (optional)
	 *          Phone=>电话 (optional)
	 *          Office_phone=>办公电话 (optional)
	 *          Email=>电邮 (optional)
	 *          Email_confirm=>电邮确定 (optional)
	 *          Zip=>邮编 (optional)
	 *          Mobile=>移动电话 (optional)
	 *          Address1=>地址1 (optional)
	 *          Address2=>地址2 (optional)
	 *          Address3=>地址3 (optional)
	 *          Address4=>地址4 (optional)
	 *          Id_no=>证件号码 (optional)
	 *          OrderRoomStayInfo=>array(
	 *          array(
	 *          DT=>日期
	 *          RoomTypeCode=>房型代码
	 *          RateAmount=>价格
	 *          Tax=>税费
	 *          ServiceCharge=>服务费
	 *          CurrencyType=>币种，CNY
	 *          RoomNum=>房间数 （1间）
	 *          Adults=>成人数
	 *          ExtraBed=>加床 （0）
	 *          Children=>儿童数（0）
	 *          RateCode=>价格代码
	 *          FixedRate=>固定休息（TRUE，FALSE）
	 *          Hotel_code=>array(
	 *          code=>酒店代码
	 *          )
	 *          Guesttype_code=>array(
	 *          code=>客人类型
	 *          )
	 *          Roomtype_code=>array(
	 *          code=>房型代码
	 *          )
	 *          Rate_code=>array(
	 *          code=>价格代码
	 *          )
	 *          Market=>array(
	 *          code=>市场代码
	 *          )
	 *          Source=>array(
	 *          code=>来源代码 (LOP)
	 *          )
	 *          Channel=>array(
	 *          code=>渠道代码（WEB）
	 *          )
	 *          Member_id=>会员卡号
	 *          Reservation_type=>array(
	 *          code=>预订类型代码
	 *          )
	 *          OrderInfoAccompanying=>array(
	 *          FirstName=>入住人名称
	 *          LastName=>入住人姓
	 *          Mobile=>手机号
	 *          Email=>电邮
	 *          CountryCode=>国家代码
	 *          ProvinceCode=>省代码
	 *          Address=>地址
	 *          Birthday=>生日
	 *          Title=>称呼
	 *          )
	 *
	 * )
	 * @return mixed
	 */
	public function createReservation($params,$func_data=[]){
		array_key_exists('ArrivalTime', $params) or $params['ArrivalTime'] = '18:00';
		$params['Com_err'] = 0;
		
		$params['oOrderInfo']['Channel']['code'] = $this->channel_code;
		$params['oOrderInfo']['Source']['code'] = $this->source_code;
		$params['oOrderInfo']['Market']['code'] = $this->market_code;
		
		$params['oOrderInfo']['CountryCode']['code'] = 'CN';
		$params['oOrderInfo']['Languag_code']['code'] = 'C';


//		array_key_exists('Reservation_type', $params) or $params['Reservation_type']['code'] = $this->member_source;
		
		$service = [
			'uri'  => self::RESEVATION,
			'func' => 'CreateReservation',
		];
		
		$res = $this->postService($service, $params, true,$func_data);
		if(5001 == $res['header']['RetCode']){
			return ['oOrderInfo' => $res['result']];
		}
		return $res['header'];
	}
	
	public function modifyOrder($params,$func_data=[]){
//		array_key_exists('ArrivalTime', $params) or $params['ArrivalTime'] = '18:00';
		$params['oOrderInfo']['CountryCode']['code'] = 'CN';
		$params['oOrderInfo']['Languag_code']['code'] = 'C';
		$params['Com_err'] = 0;
		
		$service = [
			'uri'  => self::RESEVATION,
			'func' => 'ModifyReservation',
		];
		
		$res = $this->postService($service, $params,false,$func_data);
		if(5001 == $res['header']['RetCode']){
			return ['oOrderInfo' => $res['result']];
		}
		return $res['header'];
	}
	
	/**
	 * 取消订单
	 * @param $weborderid
	 * @param $comment
	 * @return mixed
	 */
	public function cancelReservation($weborderid, $comment = '',$func_data=[]){
		$params = [
			'id'      => $weborderid,
			'comment' => $comment,
		];
		$service = [
			'uri'  => self::RESEVATION,
			'func' => 'CancelReservation',
		];
		
		$res = $this->postService($service, $params,false,$func_data);
//		print_r($res);
		if(5001 == $res['header']['RetCode']){
			return ['result' => $res['result']];
		}
		return $res['header'];
	}
	
	/**
	 * 获取单个订单
	 * @param mixed $weborderid
	 * @param string $email
	 * @return mixed
	 */
	public function getOrder($weborderid,$func_data=[]){
		$params = [
			'id' => $weborderid,
		];
		$service = [
			'uri'  => self::RESEVATION,
			'func' => 'GetOrderInfo',
		];
		$res = $this->postService($service, $params,false,$func_data);
		if(5001 == $res['header']['RetCode'] && !empty($res['result'])){
			return $res['result'];
		}
		return [];
		
	}
	/**
	 * 获取订单及其关联单
	 *
	 */
	public function getRelatedOrder($hotel_web_id,$weborderid,$order,$func_data=[]){
	    $QueryOrderPageIn = array (
	            'request' => array (
	                    'id' => 0,
	                    'BeginArrivalDate' => date ( 'Y-m-d', strtotime ( $order ['startdate'] ) ),
	                    'EndArrivalDate' => date ( 'Y-m-d', strtotime ( $order ['startdate'] ) ),
	                    'BeginDepartureDate' => date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ),
	                    'EndDepartureDate' => date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ),
	                    'BeginInsertDate' => date ( 'Y-m-d', strtotime ( $order ['startdate'] ) ),
	                    'EndInsertDate' => date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ),
	                    'Hotels' => $hotel_web_id,
	                    'FirstName' => '',
	                    'LastName' => '',
	                    'ProfileId' => 0,
	                    'Account' => '',
	                    'CardNumber' => '',
	                    'PhoneNumber' => '',
	                    'PageInfo' => array (
	                            'PageIndex' => 1,
	                            'PageSize' => 20,
	                            'TotalRecords'=>200
	                    ),
	                    'BlockCode' => '',
	                    'ChannelConfirmID' => '',
	                    'PmsId' => '',
	                    'ShareCode' => '',
	                    'Channel' => '',
	                    'InsertUser' => '',
	                    'CancelId' => '',
	                    'ChannelCancelId' => '',
	                    'PmsCancelId' => '',
	                    'ChineseName' => '',
	                    'Party' => array (
	                            'PartyNo' => $weborderid,
	                            'PartyAction' => 'SELECT'
	                    )
	            )
	    );
	    $res = $this->postService ( [
	            'uri' => self::RESEVATION,
	            'func' => 'QueryOrderPage'
	    ], $QueryOrderPageIn );
	    if (5001 == $res ['header'] ['RetCode']) {
	        if (! empty ( $res ['result'] ['OrderInfos'] ['OrderInfo'])) {
	            $web_orders=$res ['result'] ['OrderInfos']['OrderInfo'];
	            $related_orders=array();
	            if (count ( $web_orders ) == 1) {
	                $web_orders = array (
	                        '0' => $web_orders
	                );
	            }
	            foreach ( $web_orders as $o ) {
	                $related_orders [$o['ID']] = $o;
	            }
	            return array_reverse($related_orders,TRUE);
	        } else {
	            return [];
	        }
	    }
	    return [];
	}
	
	/**
	 * 添加在线支付
	 * @param $params
	 *  [
	 *  OrderId=> 交易单号，如果是预订则传订单号，如果是充值或购买积分，则传相应的交易ID号,
	 *  GatewayReferenceNo=>第三方交易流水号（能够依据此号在第三方系统中找到对应的交易记录，当Status为PAID时为必填项）
	 *  Amount=>支付金额
	 *  Remark=>备注信息
	 *  Status=>支付状态，已支付：PAID；取消支付：CANCEL,未支付：UNPAID
	 *  PaymentDate=>支付时间
	 *  Amount_type=>固定值“MONEY”
	 *  PaymentCode=>支付方式代码，通过此代码可以标识是哪个支付平台，可从CRS中获得对应的支付方式代码
	 *  OrderType=>RESVROOM：预订在线支付业务, PURCHASEPOINTS：在线购买积分业务,TOPUP在线充值、续费业务
	 *  ]
	 * @return array|mixed
	 */
	public function addPaymentGateway($params,$func_data=[]){
		array_key_exists('PaymentDate', $params) or $params['PaymentDate'] = date('Y-m-d') . 'T' . date('H:i:s');
		array_key_exists('PaymentCode', $params) or $params['PaymentCode'] = 'WCP';
		array_key_exists('Amount_type', $params) or $params['Amount_type'] = 'MONEY';
		array_key_exists('OrderType', $params) or $params['OrderType'] = 'RESVROOM';
		array_key_exists('Status', $params) or $params['Status'] = 'PAID';
		
		$service = [
			'uri'  => self::RESEVATION,
			'func' => 'AddorderInfoPaymentGateway',
		];
		
		$res = $this->postService($service, ['paymentGateway' => $params], true,$func_data);
		if(5001 == $res['header']['RetCode'] && !empty($res['result'])){
			return $res['result'];
		}
		return [];
		
	}
	
	/**
	 * 查询洒店房型
	 * @param $hotel_code
	 * @return mixed
	 */
	public function getHotelRoomType($hotel_code){
		
		$service = [
			'uri'  => self::INFORMATION,
			'func' => 'GetHotelRoomType',
		];
		$params = ['hotelCode' => $hotel_code];
		$res = $this->postService($service, $params);
		if(2001 == $res['header']['RetCode']){
			if(!empty($res['result']['RoomTypeDetail'])){
				return $res['result']['RoomTypeDetail'];
			}else{
				return [];
			}
		}
		return $res['header'];
		
	}
	
	/**
	 * 所有酒店列表
	 * @return mixed
	 */
	public function getHotels(){
		$res = $this->postService(['uri' => self::INFORMATION, 'func' => 'GetHotels'], []);
		if(2001 == $res['header']['RetCode']){
			if(!empty($res['result']['HotelInfoSummary'])){
				return $res['result']['HotelInfoSummary'];
			}else{
				return [];
			}
		}
		return $res['header'];
	}
	
	private function appLogin($username, $pwd){
		$time=time();
		$url = $this->api_base . self::SECURITY;
		$soap_opt = [
			'soap_version' => SOAP_1_1,
			'encoding'     => 'UTF-8',
		];
		$soap=null;
		try{
			$soap = new SoapClient($url, $soap_opt);
		}catch(SoapFault $e){
			$this->checkWebResult('', [], $e, $time, microtime(), [], ['run_alarm' => 1]);
		}catch(Exception $e){
			$this->checkWebResult('', [], $e, $time, microtime(), [], ['run_alarm' => 1]);
		};
		
		$post_params=[
			'username' => $username,
			'password' => $pwd,
		];
		$time=time();
		if($soap){
			try{
				$result = $soap->__soapCall('AppLogin', [
					'parameters' => $post_params
				], null, null, $out_header);
				
				$header = obj2array($out_header);
				
				$res = [
					'result' => $result,
					'header' => $header,
				];
				
				$this->CI->Webservice_model->add_webservice_record($this->inter_id, 'shiji', $this->url, [
					'AppLogin',
					$post_params
				], $res, 'query_post', $time, microtime(), $this->CI->session->userdata($this->inter_id . 'openid'));
				
				if(!empty($result->AppLoginResult)){
					$this->secret = $header['KwsSoapHeader']['SessionId'];
					$this->CI->session->set_userdata($this->inter_id . ':secret', $this->secret);
				}
			}catch(SoapFault $e){
				$this->checkWebResult('AppLogin', $post_params, $e, $time, microtime(), [], ['run_alarm' => 1]);
			}catch(Exception $e){
				$this->checkWebResult('AppLogin', $post_params, $e, $time, microtime(), [], ['run_alarm' => 1]);
			};
		}
	}
	
	/**
	 * SOAP请求
	 * @param array $service 请求连接及方法
	 * @param array $params 请求参数
	 * @return array
	 */
	public function postService($service, $params = [], $special = false,$func_data=[]){
		$inter_id = $this->inter_id;
		static $qs = 0;
		$time=time();
		$url = $this->api_base . $service['uri'];
		
		
		$run_alarm = 0;
		
		try{
			//SOAP HEADER验证
			$var_xml = '<KwsSoapHeader xmlns="http://www.shijinet.com.cn/kunlun/kws/1.1/"><SessionId>' . $this->secret . '</SessionId></KwsSoapHeader>';
			
			if($special === true){
				$soap = new nusoap_client($url, 'wsdl');
				$soap->decode_utf8 = false;
				$soap->setHeaders($var_xml);
				
				
				$result = $soap->call($service['func'], ['parameters' => $params], '', '', false, true);
				$out_header = $soap->getHeader();
				
				if(isset($result[$service['func'] . 'Result'])){
					$res = $result[$service['func'] . 'Result'];
				}else{
					$res = $result;
				}
				$header = obj2array($out_header);
			}else{
				$soap_var = new SoapVar($var_xml, XSD_ANYXML);
				$soap_header = new SoapHeader('http://www.shijinet.com.cn/kunlun/kws/1.1/', 'KwsSoapHeader', $soap_var, true);
				
				$soap_opt = [
					'soap_version' => SOAP_1_1,
					'encoding'     => 'UTF-8',
				];
				$soap = new SoapClient($url, $soap_opt);
				$result = $soap->__soapCall($service['func'], [
					'parameters' => $params,
				], null, $soap_header, $out_header);
				$res = [];
				if(isset($result->{$service['func'] . 'Result'})){
					$res = obj2array($result->{$service['func'] . 'Result'});
				}
				$header = obj2array($out_header);
			}
			
			$return = [
				'header' => isset($header['KwsSoapHeader']) ? $header['KwsSoapHeader'] : $header,
				'result' => $res,
			];
			
			$this->CI->Webservice_model->add_webservice_record($this->inter_id,'shiji',$url,func_get_args(),$return,'query_post',$time,microtime(),$this->CI->session->userdata($this->inter_id.'openid'));
			
			if(strpos($return['header']['RetCode'], '002') !== false){
				//SESSION验证失败
				if($qs < 2){
					$this->appLogin($this->user, $this->pwd);
					$qs++;
					return $this->postService($service, $params, $special);
				}
			}
			$this->checkWebResult($service['func'], $params, $return, $time, microtime(), $func_data, ['run_alarm' => $run_alarm]);
			return $return;
		}catch(SoapFault $e){
			$this->checkWebResult($service['func'], $params, $e, $time, microtime(), $func_data, ['run_alarm' => 1]);
		}catch(Exception $e){
			$this->checkWebResult($service['func'], $params, $e, $time, microtime(), $func_data, ['run_alarm' => 1]);
		}
		
		return [];
		
		
	}
	
	protected function checkWebResult($func_name, $send, $receive, $now, $micro_time, $func_data = [], $params = []){
		$func_name_des = $this->pms_enum('func_name', $func_name);
		isset ($func_name_des) or $func_name_des = $func_name; // 方法名描述\
		$err_msg = ''; // 错误提示信息
		$err_lv = NULL; // 错误级别，1报警，2警告
		$alarm_wait_time = null; // 默认超时时间
		if(!empty($params['run_alarm'])){ // 程序运行报错，直接报警
			$err_msg = '程序报错,' . json_encode($receive, JSON_UNESCAPED_UNICODE);
			$err_lv = 1;
		}else{
			switch($func_name){
				case 'GetAvailability':
					if(4001!=$receive['header']['RetCode']){
						$err_lv=2;
						$err_msg=$receive['header']['ErrReason'];
					}elseif(empty($receive['result']['RateInfos']['RateInfo'])){
						$err_lv=2;
						$err_msg='空数据';
					}
					break;
				case 'CreateReservation':
					if(5001!=$receive['header']['RetCode']){
						$err_lv=1;
						$err_msg=$receive['header']['ErrReason'];
					}
					break;
				case 'CancelReservation':
					if(5001!=$receive['header']['RetCode']){
						$err_lv=1;
						$err_msg=$receive['header']['ErrReason'];
					}
					break;
				case 'GetOrderInfo':
					if(5001!=$receive['header']['RetCode']){
						$err_lv=2;
						$err_msg=$receive['header']['ErrReason'];
					}elseif(empty($receive['result'])){
						$err_lv=2;
						$err_msg='空数据';
					}
					break;
				case 'AddorderInfoPaymentGateway':
					if(5001!=$receive['header']['RetCode']){
						$err_lv=1;
						$err_msg=$receive['header']['ErrReason'];
					}
					break;
				
			}
		}
		
		$this->CI->Webservice_model->webservice_error_log($this->inter_id, 'shiji', $err_lv, $err_msg, array(
			'web_path'        => $this->url,
			'send'            => $send,
			'receive'         => $receive,
			'send_time'       => $now,
			'receive_time'    => $micro_time,
			'fun_name'        => $func_name_des,
			'alarm_wait_time' => $alarm_wait_time
		), $func_data);
	}
	
	private function pms_enum($type = '', $key = ''){
		$arr = [];
		switch($type){
			case 'func_name':
				$arr = [
					'GetAvailability'            => '房态读取',
					'GetOrderInfo'               => '查询订单',
					'CreateReservation'          => '新建订单',
					'CancelReservation'          => '取消订单',
					'AddorderInfoPaymentGateway' => '入账',
				];
				break;
		}
		if($key === ''){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}
}