<?php

class Xiruanapi{
	
	private $inter_id;
	private $api_base;
	private $cmmcode;
	private $channel = 'WECHAT';
	private $ver = '1.0.0';
	private $sign_hotelid;
	
	private $user;
	private $pwd;
	
	private $restype = 1;
	
	private $CI;
	
	public function __construct($config){
		$this->CI =& get_instance();
		$this->CI->load->helper('common');
		$this->inter_id = $config['inter_id'];
		$this->api_base = $config['url'];
		
		$this->user = $config['user'];
		$this->pwd = $config['pwd'];
		
		$this->cmmcode = $config['cmmcode'];
		if(!empty($config['channel'])){
			$this->channel = $config['channel'];
		}
		$this->sign_hotelid = $config['sign_hotelid'];
		
		$this->CI->load->model('common/Webservice_model');
	}
	
	/**
	 * 查询房量
	 * @param string $hotel_web_id 酒店ID
	 * @param string $startdate    入住日期
	 * @param string $enddate      离店日期
	 * @param null   $webser_id    房型ID
	 * @param null   $ratecode     价格代码
	 * @return array|mixed
	 */
	public function getRoomQty($hotel_web_id, $startdate, $enddate, $webser_id = null, $ratecode = null,$func_data=[]){
		$url = $this->api_base . 'member/getroomamount';
		$func = 'rminfo';
		
		
		$params = [
			'ratecode' => $ratecode,
			'hotelid'  => $hotel_web_id,
			'rmtype'   => $webser_id,
			'end'      => date('Y-m-d', strtotime($enddate)),
			'begin'    => date('Y-m-d', strtotime($startdate)),
		];
		$result = $this->postService($url, $func, $params,$func_data);
		
		if($result['success']){
			return $result['result'];
		}
		return [];
	}
	
	/**
	 * 查询每日房价
	 * @param string $hotel_web_id 酒店ID
	 * @param string $startdate    入住日期
	 * @param string $enddate      离店日期
	 * @param null   $webser_id    房型ID
	 * @param null   $ratecode     价格代码
	 * @return array|mixed
	 */
	public function getRoomDailyPrice($hotel_web_id, $startdate, $enddate, $webser_id = null, $ratecode = null,$func_data=[]){
		$url = $this->api_base . 'member/getdailyrate';
		$func = 'dailyrate';
		
		$params = [
			'ratecode' => $ratecode,
			'hotelid'  => $hotel_web_id,
			'rmtype'   => $webser_id,
			'end'      => date('Y-m-d', strtotime($enddate)),
			'begin'    => date('Y-m-d', strtotime($startdate)),
		];
		$result = $this->postService($url, $func, $params,$func_data);
		
		if($result['success']){
			return $result['result'];
		}
		return [];
	}
	
	/**
	 * 房价查询
	 * @param string $hotel_web_id 酒店ID
	 * @param string $startdate    入住日期
	 * @param string $enddate      离店日期
	 * @param null   $webser_id    房型ID
	 * @param null   $ratecode     价格代码
	 * @param null   $roomnum      房数
	 * @param null   $guestnum     人数
	 * @return array|mixed
	 */
	public function getRoomPrice($hotel_web_id, $startdate, $enddate, $webser_id = null, $ratecode = null, $roomnum = null, $guestnum = null){
		$url = $this->api_base . 'member/ratequery';
		$func = 'ratequery';
		$params = [
			'ratecode'    => $ratecode,
			'hotelid'     => $hotel_web_id,
			'rmtype'      => $webser_id,
			'dep'         => date('Y-m-d', strtotime($enddate)),
			'arr'       => date('Y-m-d', strtotime($startdate)),
			'guestnumber' => $guestnum,
			'roomnum'     => $roomnum,
		];
		$result = $this->postService($url, $func, $params);
		
		if($result['success']){
			return $result['result'];
		}
		return [];
	}
	
	/**
	 * 房价查询 （带促销或者消费券）
	 * @param array $params
	 *  [
	 *  hotelid=>酒店
	 *  arr=>开始时间
	 *  dep=>结束时间
	 *  promocode=>促销代码（Optional)
	 *  ratecode=>房价码（Optional)
	 *  useticket=>是否返回消费券价格 T/F（Optional)
	 *  roomtype=>房型（Optional)
	 *  useprom=>是否返回促销价格 T/F（Optional)
	 *  guestnumber=>人数（Optional)
	 *  roomnumber=>房数（Optional)
	 *  restime=>预订时间 当传入促销代码的时候，预订时间必填（Optional)
	 *  ]
	 * @return array|mixed
	 * @author 苦涩茶味 <ap0606240@163.com>
	 */
	/*public function getRoomPromoPrice($params = []){

		$result = $this->postService('xmsopen.reservation.xopgetdailyrate', $params);

		if($result['success']){
			return $result['results'];
		}
		return [];
	}*/
	
	
	/**
	 * 下单，保存订单
	 * @param array $params
	 *    [
	 *    rsvno=>XMS订单账号 该参数传了，即修改订单（Optional）
	 *    ratecode=>房价码
	 *    rmtype=>房型
	 *    rmnum=>房间数量
	 *    rate=>首日价
	 *    hotelid=>酒店编号
	 *    name=>姓名
	 *    mobile=>手机号码（Optional）
	 *    arr=>到达时间
	 *    dep=>离开时间
	 *    gstno=>人数
	 *    restype=>预定类型 //1:一般订单，5：预付，8：积分兑换
	 *    dailyrate=>[ 每日房价列表(Optional)
	 *    [
	 *    rate=>每日价格
	 *    pkg=>包价
	 *    rmrate=>房价
	 *    rtreason=>优惠理由
	 *    ratecode=>每日价格码
	 *    ]
	 *    ]
	 *    cardno=>会员卡号（Optional）
	 *    rtreason=>优惠理由（Optional）
	 *    channel=>预订渠道（Optional）
	 *    children=>儿童（Optional）
	 *    market=>市场码（Optional）
	 *    src=>来源码（Optional）    '
	 *    discount=>优惠额（Optional）
	 *    discountpct=>优惠百分比（Optional）
	 *    payment=>付款方式（Optional）
	 *    payamt=>已付款额（Optional）
	 *    ref=>备注（Optional）
	 *
	 *
	 *  rmtypecharge=>收费房型 rtc（Optional）
	 *  roomno=>房号（Optional）
	 *  saleid=>销售员（Optional）
	 *  rmreason=>换房理由（Optional）
	 *  udfs=>[ udf包(Optional)
	 *   [
	 *    code=>代码
	 *    value=>值
	 *    descript=>描述
	 *   ]
	 *  ]
	 *  guestprofileid=>客人档案号（Optional）
	 *  corpprofileid=>公司档案号（Optional）
	 *  taprofileid=>旅行社档案号（Optional）
	 *  sourceprofileid=>订房中心档案号（Optional）
	 *  salemanid=>销售员（Optional）
	 *  partycode=>联房码（Optional）
	 *  specials=>特殊要求码（Optional）
	 *  blockcode=>留房代码（Optional）
	 *  promcode=>促销代码(Optional)
	 *  contact_mobile=>联系方式(Optional)
	 *  contact_name=>联系人(Optional)
	 *  coupon=>消费券号(Optional)
	 *  comp=>是否是免费房 T/F(Optional)
	 *  fname=>姓(Optional)
	 *  lname=>名(Optional)
	 *  depositcode=>担保代码（Optional）
	 *  email=>email（Optional）
	 *  cxlrule=>取消规则（Optional）
	 *  iscol=>是否是畅联订单(Optional)
	 *  credcode=>信用卡号(Optional)
	 *  holdname=>持卡人(Optional)
	 *  codetype=>信用卡类别(Optional)
	 *  cardexp=>信用卡有效期(Optional)
	 *  crsno=>渠道预订号 中央预订号(Optional)
	 * ]
	 * @return array|string
	 */
	public function saveOrder($params = [],$func_data=[]){
		$url = $this->api_base . 'member/savereservation';
		$func = 'saveres';
		array_key_exists('restype', $params) or $params['restype'] = $this->restype;
		array_key_exists('channel', $params) or $params['channel'] = $this->channel;
		$result = $this->postService($url, $func, $params,$func_data);
		
		return $result;
	}
	
	/**
	 * 取消订单
	 * @param string $hotel_web_id
	 * @param string $web_orderid
	 * @param string $reason
	 * @param null   $force
	 * @return array|string
	 * @author 苦涩茶味 <ap0606240@163.com>
	 */
	public function cancelOrder($hotel_web_id, $web_orderid, $reason, $force = null,$func_data=[]){
		$url = $this->api_base . 'member/cxlreservation';
		$func = 'cxlrsv';
		
		$params = [
			'cxlreason' => $reason,
			'hotelid'   => $hotel_web_id,
			'rsvno'     => $web_orderid,
			'force'     => $force,
		];
		$result = $this->postService($url, $func, $params,$func_data);
		return $result;
	}
	
	/**
	 * 查询订单
	 * @param $web_orderid
	 * @return array|mixed
	 */
	public function queryOrder($web_orderid,$func_data=[]){
		$url = $this->api_base . 'member/queryreservation';
		$func = 'queryrsv';
		
		$params = [
			'rsvno' => $web_orderid,
		];
		
		$result = $this->postService($url, $func, $params,$func_data);
		if($result['success'] && $result['result']){
			$paymoney = 0;
			$list=$result['result'];
			$row=$list[0];
			foreach($list as $v){
				$paymoney += $v['paymoney'];
			}
			$row['paymoney'] = $paymoney;
			return $row;
		}
		return [];
	}
	
	public function queryHOrder($web_orderid,$func_data=[]){
		$url = $this->api_base . 'member/queryhistoryreservation';
		$func = 'queryhrsv';
		
		$params = [
			'rsvno' => $web_orderid,
		];
		
		$result = $this->postService($url, $func, $params,$func_data);
		if($result['success'] && $result['result']){
			return $result['result'];
		}
		return [];
	}
	
	
	
	
	/**
	 * 网站支付
	 * @param array $params
	 *  [
	 *  rsvno=>XMS订单号
	 *  pay_money=>支付金额
	 *  pay_code=>支付方式：银联等
	 *  payno=>支付流水号
	 *  remark=>备注
	 *  ]
	 * @return array|string
	 */
	public function addPayment($params = [],$func_data=[]){
		$url = $this->api_base . 'member/payment';
		$func = 'payment';
		
		$result = $this->postService($url, $func, $params,$func_data);
		
		if(!empty($result['success'])){
			return true;
		}
		return false;
	}
	
	private function postService($url, $func, $params = [],$func_data=[]){
		static $row;
		
		$request = [
			'rq'      => $func,
			'hotelid' => $this->sign_hotelid,
			'pw'      => $this->pwd,
			'user'    => $this->user,
			'cmmcode' => $this->cmmcode,
		];
		
		if($params){
			$request['param'] = $params;
		}
		$time=time();
		$run_alarm = 0;
		
		$json = json_encode($request);
		$extra=['CURLOPT_HTTPHEADER'=>['Content-Type: application/json']];
		$res=doCurlPostRequest($url, $json,$extra,25);
		$result = json_decode($res, true);
		
		$this->CI->Webservice_model->add_webservice_record($this->inter_id,'xiruan',$url,$request,$res,'query_post',$time,microtime(),$this->CI->session->userdata($this->inter_id.'openid'));
		
		if(!$result){
			$result = [
				'success' => false,
				'result'  => [
					'err' => -9999,
					'msg' => '请求失败！',
				]
			];
		}
		
		$this->checkWebResult($func, $request, $res, $time, microtime(), $func_data, ['run_alarm' => $run_alarm]);
		return $result;
		
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
			$res=json_decode($receive,true);
			if(!is_array($res)||!$res){
				$err_lv=1;
				$err_msg='接口报错，'.$receive;
			}else{
				switch($func_name){
					case 'rminfo':
					case 'queryrsv':
					case 'queryhrsv':
					case 'dailyrate':
						if(!$res['success']){
							$err_lv=2;
							$err_msg='请求失败。'.isset($res['result']['msg']);
						}elseif(empty($res['result'])){
							$err_lv=2;
							$err_msg='空数据';
						}
						break;
					case 'saveres':
					case 'cxlrsv':
					case 'payment':
						if(!$res['success']){
							$err_lv=1;
							$err_msg='接口错误：'.isset($res['result']['msg']) ? $res['result']['msg'] : '';
						}
						break;
				}
			}
		}
		
		$this->CI->Webservice_model->webservice_error_log($this->inter_id, 'xiruan', $err_lv, $err_msg, array(
			'web_path'        => $this->api_base,
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
					'rminfo'            => '获取房量',
					'dailyrate'            => '每日房价',
					'saveres'          => '新建订单',
					'queryrsv'               => '查询订单',
					'queryhrsv'               => '查询历史订单',
					'cxlrsv'          => '取消订单',
					'payment' => '入账',
				];
				break;
		}
		if($key === ''){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}
	
	
	function sign($data){
		$sign_text = $data['appkey'] . $data['hotelid'] . $data['loc'] . $data['ts'];
		
		if(!empty($data['params'])){
			$params = $data['params'];
			
			ksort($params);
			foreach($params as $v){
				$sign_param = strtoupper($this->sign_string($v));
				$sign_text .= $sign_param;
			}
		}
		return md5($sign_text);
	}
	
	function sign_string($str){
		$string = '';
		if(is_null($str)){
			return '';
		} else{
			if($str === false){
				$string .= 'false';
			} elseif($str === true){
				$string .= 'true';
			} else{
				if(is_array($str) || is_object($str)){
					ksort($str, SORT_STRING);
					foreach($str as $k => $v){
						$string .= $this->sign_string($v);
					}
				} else{
					if(strtotime($str)){
						$string .= strtotime($str) . '000';
					} else{
						if(strstr($str, 'E+')){
							$str = number_format($str, 0, '', '');
							$string .= $str;
						} else{
							$string .= $str;
						}
					}
				}
			}
		}
		return $string;
	}
}