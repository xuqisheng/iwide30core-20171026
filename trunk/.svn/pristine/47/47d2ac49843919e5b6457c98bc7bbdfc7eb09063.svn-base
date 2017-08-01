<?php

class Qianlimaapi{
	private $soap;
	private $inter_id;
	private $soap_header;
	private $source_code;
	private $res_clerk;
	private $res_status = 'R';
	private $channel;
	private $CI;
	private $url;

	public function __construct($config = []){
		$time=time();
		$this->CI =& get_instance();
		$this->CI->load->helper('common');
		$soap_opt = array(
			'soap_version' => SOAP_1_1,
			'encoding'     => 'UTF-8',
		);
		$this->inter_id = $config['inter_id'];
		$this->url=$config['url'];

		$var_xml = '<AuthenticationToken><Username>' . $config['user'] . '</Username><Password>' . $config['pwd'] . '</Password></AuthenticationToken>';

		$soap_var = new SoapVar($var_xml, XSD_ANYXML);
		$this->soap_header = new SoapHeader('http://temporg.com/xsd/', 'SoapHeader', $soap_var, true);

		$this->source_code = $config['source'];
		$this->res_clerk = $config['res_clerk'];
		$this->channel = $config['channel'];
		$this->res_status = $config['res_status'];
		$this->res_mode = $config['res_mode'];

		$this->CI->load->model('common/Webservice_model');
		
		try{
			$this->soap = new SoapClient($config['url'], $soap_opt);
		}catch(SoapFault $e){
			$this->checkWebResult('', [], $e, $time, microtime(), [], ['run_alarm' => 1]);
		}catch(Exception $e){
			$this->checkWebResult('', [], $e, $time, microtime(), [], ['run_alarm' => 1]);
		};
	}

	/*public function getRoomRateQty($hotelid){
		$param = ['in0' => $hotelid];
		$list = [];
		$res = $this->postService('getHotelRoomQty', $param);
		if(!empty($res['rmTypes']['RmType'])){
			$list = $res['rmTypes']['RmType'];
			is_array(current($list)) or $list = [$list];
		}
		return $list;
	}

	public function getRoomAndRate($hotelid){
		$param = ['in0' => $hotelid];
		$list = [
			'rates' => [],
			'rooms' => [],
		];
		$res = $this->postService('getRateCodeAndRmTypeByHotelId', $param);
		if(!empty($res['rmType']['RmType'])){
			$list['rooms'] = $res['rmType']['RmType'];
			is_array(current($list['rooms'])) or $list['rooms'] = [$list['rooms']];
		}
		if(!empty($res['ratecodes']['RateCode'])){
			$list['rates'] = $res['ratecodes']['RateCode'];
			is_array(current($list['rates'])) or $list['rates'] = [$list['rates']];
		}
		return $list;
	}*/

	public function getPmsRoomState($hotelid, $ratecode = [], $startdate, $enddate,$func_data=[]){
		$startdate = date('Y-m-d', strtotime($startdate));
		$enddate = date('Y-m-d', strtotime($enddate));
		$list = [];
		$param = [
			'in0' => $hotelid,
			'in2' => $startdate,
			'in3' => $enddate,
		    'in4'=>$this->channel,
		];
		foreach($ratecode as $v){
			$param['in1'] = $v;
			$res = $this->postService('selectHotelRoomRateByChannel', $param,$func_data);
			if(!empty($res['roomRateWS']['RoomRateWS'])){
				$rs = $res['roomRateWS']['RoomRateWS'];
				is_array(current($rs)) || $rs = [$rs];
				$list = array_merge($list, $rs);
			}
		}
		return $list;
	}


	/**
	 * @param array $order
	 *  [
	 *  adults    数字型    成人人数        预订必要数据
	 *  arrDate    字符型    抵店日期        预订必要数据
	 *  depDate    字符型    离店日期        预订必要数据
	 *  booker    字符型    订房人姓名        预订必要数据
	 *  bookTel    字符型    订房人电话        预订必要数据
	 *  gstName    字符型    客人姓名        预订必要数据
	 *  gstTel    字符型    客人电话        预订必要数据
	 *  hotelId    数字型    酒店序号        预订必要数据
	 *  rateCode    字符型    房价代码        预订必要数据
	 *  source    字符型    客人来源        通用代码(source), 预订必要数据
	 *  resType    字符型    预订类型        通用代码(restype) ，非必要数据
	 *  status    字符型    订单状态        通用代码(resstat) , 预订必要数据
	 *  resMode    字符型    预订方式        通用代码(resmode)，非必要数据
	 *  market    字符型    市场类别        通用代码(market), 预订必要数据
	 *  resClerk    字符型    预订职员        常量，例如用“WEB”标记为在线预订
	 *  rmQty    数字型    房数        必要数据
	 *  rmRate    货币型    房价        必要数据
	 *  rmType    字符型    房类        必要数据
	 *  msgType    字符型    反馈信息发送类型        10:短信;20:邮件;10,20即两种同时发送
	 *  cardNo    字符型    会员卡号
	 *  children    数字型    儿童人数
	 *  contractCode    字符型    销售协议代码
	 *  custId    数字型    会员编号
	 *  docNo    字符型    证件号码
	 *  docType    字符型    证件类别
	 *  email    字符型    客人邮箱
	 *  gender    字符型    性别
	 *  nights    数字型    住店天数
	 *  remarks    字符型    备注
	 *  specials    字符型    特殊要求
	 *
	 *  paytype 字符型 支付方式
	 *  iscrmcust 会员预订时填Y
	 *
	 *  ]
	 * @return mixed|null
	 */
	public function submitOrder($order = [],$func_data=[]){
		array_key_exists('adults', $order) || $order['adults'] = 1;
		array_key_exists('source', $order) || $order['source'] = $this->source_code;
		array_key_exists('status', $order) || $order['status'] = $this->res_status;
		array_key_exists('resMode', $order) || $order['resMode'] = $this->res_mode;

		array_key_exists('resClerk', $order) || $order['resClerk'] = $this->res_clerk;
		array_key_exists('channel', $order) || $order['channel'] = $this->channel;

		array_key_exists('msgType', $order) || $order['msgType'] = 10;
		array_key_exists('gender', $order) || $order['gender'] = 'M';

		array_key_exists('children', $order) || $order['children'] = 0;
		array_key_exists('contractCode', $order) || $order['contractCode'] = '';
		array_key_exists('docNo', $order) || $order['docNo'] = '';
		array_key_exists('docType', $order) || $order['docType'] = '';
		array_key_exists('email', $order) || $order['email'] = '';
		array_key_exists('nights', $order) || $order['nights'] = '';
		array_key_exists('paytype', $order) || $order['paytype'] = '';


		$param['order'] = $order;

		$res = $this->postService('addOrder', $param,$func_data);
		return $res;
	}

	public function modifyOrder($order = [],$func_data=[]){
		$param['in0'] = $order;

		$res = $this->postService('modifyOrder', $param,$func_data);
		return $res;
	}

	public function queryOrder($web_orderid,$func_data=[]){
		$param['accId'] = $web_orderid;

		$res = $this->postService('getOrder', $param,$func_data);

		if(0==$res['result']&&!empty($res['orders']['Order'])){
			return $res['orders']['Order'];
		}
		return [];
	}

	public function queryOrderDetail($resid,$func_data=[]){
		$param['in0']=$resid;
		$res=$this->postService('getGRESDetail',$param,$func_data);
		return $res;
	}

	/**
	 * @param array $param
	 * [
	 * hotelId    数字型    酒店编号        非必填
	 * accId    数字型    预订单号        非必填
	 * custId    数字型    会员编号        非必填
	 * cardNo    字符型    会员卡号        非必填
	 * startDate    字符型    抵店日期        非必填
	 * endDate    字符型    离店日期        非必填
	 * status    字符型    订单状态        非必填
	 * equipmentid    字符型    设备号        非必填
	 * ]
	 * @return array|mixed
	 */
	public function queryOrders($data,$func_data=[]){
		$param['in0']=$data;
		$res=$this->postService('getOrderList',$param,$func_data);
		return $res;
	}

	public function queryOrderList($start, $end,$func_data=[]){
		$param=[
			'in0'=>$start,
		    'in1'=>$end
		];
		$res=$this->postService('getOrderList1',$param,$func_data);
		return $res;
	}

	public function cancelOrder($web_orderid, $remark = '用户取消',$func_data=[]){
		$param = [
			'accId'  => $web_orderid,
			'remark' => $remark,
		];
		$res = $this->postService('cancelOrder', $param,$func_data);
		return $res;
	}

	/**
	 * @param ing    $web_orderid PMS订单号
	 * @param float  $pay_fee     支付金额
	 * @param string $pym         支付方式【银行：银联[UNIONPAY]，支付宝[ALIPAY]：ALIPAY，微信：微信[WECHATPAY]，储存[ZC]，VISA支付[JET]：
	 */
	public function addPayment($web_orderid, $pay_fee, $pym,$func_data=[]){
		$param = [
			'in0' => $web_orderid,
			'in1' => $pay_fee,
			'in2' => $pym,
		];

		$res = $this->postService('updateGres', $param,$func_data);
		return $res;
	}
	
	
	private function postService($func, $params,$func_data=[]){
		$time=time();
		$result = [
			'result'     => -999,
			'errorMsgZh' => '系统错误',
		];
		if($this->soap){
			$run_alarm = 0;
			$s = null;
			
			try{
				$res = $this->soap->__soapCall($func, ['parameters' => $params], null, $this->soap_header);
				
				$this->CI->Webservice_model->add_webservice_record($this->inter_id, 'qianlima', $this->url, func_get_args(), $res, 'query_post', $time, microtime(), $this->CI->session->userdata($this->inter_id . 'openid'));
				
				if(!empty($res->out)){
					$result = obj2array(($res->out));
				}
				
				$s = $res;
				
			}catch(SoapFault $e){
				$s = $e;
				$run_alarm = 1;
			}catch(Exception $e){
				$s = $e;
				$run_alarm = 1;
			}
			
			$this->checkWebResult($func, $params, $s, $time, microtime(), $func_data, ['run_alarm' => $run_alarm]);
		}
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
			if(empty($receive->out)){
				$err_lv=1;
				$err_msg='接口错误:'.$receive;
			}else{
				switch($func_name){
					case 'selectHotelRoomRateByChannel':
						/*if(!empty($res['roomRateWS']['RoomRateWS'])){*/
						if(empty($receive->out->roomRateWS->RoomRateWS)){
							$err_lv=2;
							$err_msg='空数据，'.(!empty($receive->out->errorMsgZh)?$receive->out->errorMsgZh:'');
						}
						break;
					case 'addOrder':
						if($receive->out->result!=0){
							$err_lv=1;
							$err_msg=$receive->out->errorMsgZh;
						}
						break;
					case 'getOrder':
						if($receive->out->result!=0){
							$err_lv=2;
							$err_msg=$receive->out->errMsgZh;
						}elseif(empty($receive->out->orders->Order)){
							$err_lv=2;
							$err_msg='空数据';
						}
						break;
					case 'modifyOrder':
						if($receive->out->result!=0){
							$err_lv=1;
							$err_msg=$receive->out->errorMsgZh;
						}
						break;
					case 'cancelOrder':
						if($receive->out->result!=0){
							$err_lv=1;
							$err_msg=$receive->out->errorMsgZh;
						}
						break;
					case 'updateGres':
						if($receive->out->result!=0){
							$err_lv=1;
							$err_msg=$receive->out->errorMsgZh;
						}
						break;
				}
			}
		}
		
		$this->CI->Webservice_model->webservice_error_log($this->inter_id, 'qianlima', $err_lv, $err_msg, array(
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
					'selectHotelRoomRateByChannel'     => '房态读取',
					'getOrder'               => '查询订单',
					'addOrder'             => '新建订单',
					'cancelOrder' => '取消订单',
					'modifyOrder' => '修改订单',
					'updateGres' => '入账',
				];
				break;
		}
		if($key === ''){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}


}