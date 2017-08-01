<?php

class JinService{
	private $URL_HTTPS = "https://sw.test.hubs1.net/switch/servlet/SwitchReceiveServlet";
	private $URL_HTTP = "http://sw.test.hubs1.net/switch/servlet/SwitchReceiveServlet";    //基础接口地址
	private $TEST_URL = "http://sw.test.hubs1.net/switch/servlet/SwitchReceiveServlet";    //测试基础接口地址
	private $M_NEW_RESV = "newresv";                                            //新增订单接口
	private $M_RELIEF_HOLD_RESV = "reliefholdresv";                                        //支付成功通知，暂不试用分销
	private $M_MOD_RESV = "modresv";                                            //修改订单
	private $M_CANCEL_RESV = "cancelresv";                                            //取消订单
	private $M_GET_PROP_RESV = "getpropresv";                                        //新增订单接口
	private $M_GET_RESV_AUDIT = "getresvaudit";                                        //审核订单

	private $M_GET_PROP_LIST = "getproplist";                                        //酒店列表
	private $M_GET_PROPERTY = "getProperty";                                        //酒店基础信息可用性
	private $M_GET_DESC = "getdesc";                                            //酒店描述信息可用性说明
	private $M_GET_ROOM_OBJ = "getroomobj";                                            //酒店房型可用性说明
	private $M_GET_RATE_OBJ = "getrateobj";                                            //酒店房价代码可用性说明
	private $M_GET_PLAN_OBJ = "getplanobj";                                            //酒店产品计划代码可用性说明
	private $M_GET_IMAGE = "getimage";                                            //酒店图片
	private $M_GET_CRATEMAP = "getcratemap";                                        //酒店缓存ARI可用性说明
	private $M_GET_ONLINE_RATEMAP = "getonlineratemap";                                    //酒店实时ARI可用性说明
	private $M_JREZ_RATE_CHANGE = "jrezratechange";                                        //消息变化可用性说明
	private $M_GET_INCPROMOTION_LIST = "getincpromotionlist";                                //酒店产品增值内容全量可用性说明
	private $M_GET_INCPROMOTION = "getincpromotion";                                    //酒店产品增值内容可用性说明

	private $U_USER = "ids_207_900111_bestayapp";
	private $U_PWD = "900111bestayapp";

	const IATA = 900111;

	private $inter_id='';
	private $CI;

	public function __construct($config = array()){
		if(isset($config['url'])){
			$this->URL_HTTP = $config['url'];
			$this->URL_HTTPS = str_replace('http://', 'https://', $config['url']);
		}
		if(isset($config['user'])){
			$this->U_USER = $config['user'];
		}
		if(isset($config['pwd'])){
			$this->U_PWD = $config['pwd'];
		}

		$this->CI=&get_instance();
		$this->CI->load->helper('common');
		$this->CI->load->model('common/Webservice_model');
	}

	public function setPmsAuth($pms_auth = array()){
		if(isset($pms_auth['url'])){
			$this->URL_HTTP = $pms_auth['url'];
			$this->URL_HTTPS = str_replace('http://', 'https://', $pms_auth['url']);
		}
		if(isset($pms_auth['user'])){
			$this->U_USER = $pms_auth['user'];
		}
		if(isset($pms_auth['pwd'])){
			$this->U_PWD = $pms_auth['pwd'];
		}
		$this->inter_id=$pms_auth['inter_id'];
	}

	public function getUrl(){
		return $this->URL_HTTP;
	}

	/**
	 * 基准头部信息，携带鉴权信息
	 * @param string $propId
	 * @param string $method
	 */
	private function setUserAuthInfo($propId = "", $method){
		$xml = '<crsmessage PropID="' . $propId . '" user="' . $this->U_USER . '" pass="' . $this->U_PWD . '" msgtype="' . $method . '" language="zh">';
		return $xml;
	}

	/**
	 * 2.1
	 * 新增订单
	 * @param 酒店id $propId
	 * @param      array 数据集  $data
	 */
	public function setNewResv($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_NEW_RESV);
		$xml = $xml . "<reservation>";

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);
		$xml = $xml . '</reservation>';
		$xml = $xml . '</crsmessage>';
		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 2.2
	 * 支付成功通知
	 * @param 酒店id $propId
	 * @param      array 数据集  $data
	 */
	public function setReliefHoldResv($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_RELIEF_HOLD_RESV);
		$xml = $xml . "<reservation>";

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);

		$xml = $xml . '</reservation>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 2.3
	 * 修改订单
	 * @param 酒店id $propId
	 * @param      array 数据集 $data
	 */
	public function setModResv($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_MOD_RESV);
		$xml = $xml . "<reservation>";

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);

		$xml = $xml . '</reservation>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;

	}

	/**
	 * 2.4
	 * 取消订单
	 * @param 酒店id $propId
	 * @param      array 数据集 $data
	 */
	public function setCancelResv($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_CANCEL_RESV);
		$xml = $xml . "<reservation>";

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);

		$xml = $xml . '</reservation>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 2.5
	 * 获取订单
	 * @param 酒店Id $propId
	 * @param      array 数据集 $data
	 */
	public function getPropResv($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_PROP_RESV);
		$xml = $xml . "<channel>Website</channel>";
		$xml = $xml . "<reservation>";

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);

		$xml = $xml . '</reservation>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 2.6
	 * 审核订单
	 * @param 酒店Id $propId
	 * @param      array 数据集  $data
	 */
	public function getResvAudit($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_RESV_AUDIT);

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);

		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.1
	 * 读取酒店列表
	 * @param 日期 $date 2011-07-01
	 * @return string|mixed
	 */
	public function getPropList($date = ""){
		$xml = $this->setUserAuthInfo("", $this->M_GET_PROP_LIST);
		$xml = $xml . '<PropLimits>';
		$xml = $xml . '<date>' . $date . '</date>';
		$xml = $xml . '</PropLimits>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.2
	 * 获取具体酒店的基础信息
	 * @param string $propId
	 */
	public function getProperty($propId){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_PROPERTY);
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.3
	 * 获取酒店的详细信息
	 * @param string $propId
	 */
	public function getPropDesc($propId){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_DESC);
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.4
	 * 获取酒店房新可用性说明
	 * @param string $roomCode 获取指定酒店的房间代码
	 * @return string|mixed
	 */
	public function getRoomObj($propId, $roomCode = NULL){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_ROOM_OBJ);

		$xml = $xml . '<roomobjmap>';
		$xml = $xml . '<roomobjdata>';
		$xml = $xml . '<roomobjlist>';
		//Edit By Peng
		if($roomCode !== NULL){
			is_array($roomCode) or $roomCode = array($roomCode);
			foreach($roomCode as $v){
				$xml .= '<roomtype>' . $v . '</roomtype>';
			}
		}
//					$xml = $xml.'<roomtype>'.$roomCode.'</roomtype>';
		//Edit End
		$xml = $xml . '</roomobjlist>';
		$xml = $xml . '</roomobjdata>';
		$xml = $xml . '</roomobjmap>';

		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}


	/**
	 * 3.5
	 * 获取酒店房价代码可用性说明
	 * @param 酒店id $propId
	 * @param 价格代码 $priceCode
	 */
	public function getRateObj($propId, $priceCode){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_RATE_OBJ);
		$xml = $xml . '<roomobjmap>';
		$xml = $xml . '<roomobjdata>';
		$xml = $xml . '<roomobjlist>';
		$xml = $xml . '<rateclass>' . $priceCode . '</rateclass>';
		$xml = $xml . '</roomobjlist>';
		$xml = $xml . '</roomobjdata>';
		$xml = $xml . '</roomobjmap>';
		$xml = $xml . '</crsmessage>';

		return $this->post($this->URL_HTTP, $xml);
	}

	/**
	 * 3.6
	 * 酒店产品计划代码可用性说明
	 * @param integer $propId 酒店id
	 * @param mixed   $planId 酒店产品计划id
	 */
	public function getPlanObj($propId, $planId = NULL){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_PLAN_OBJ);
		$xml = $xml . '<planobjmap>';
		$xml = $xml . '<planobjdata>';
		$xml = $xml . '<planobjlist>';
		//Edit By Peng 2016-05-27
		if($planId !== NULL){
			is_array($planId) or $planId = array($planId);
			foreach($planId as $v){
				$xml = $xml . '<planid>' . $v . '</planid>';

			}
		}
//						$xml = $xml.'<planid>'.$planId.'</planid>';
		$xml = $xml . '</planobjlist>';
		$xml = $xml . '</planobjdata>';
		$xml = $xml . '</planobjmap>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.7
	 * 获取指定酒店的图片
	 * @param unknown $propId
	 */
	public function getImage($propId){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_IMAGE);
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.8
	 * @param int $propId 酒店id
	 *                    $data=>
	 *                    $xml = $xml.'<options cascade="true"/>';
	 *                    $xml = $xml.'<staydetail>';
	 *                    $xml = $xml.'<date>2011-09-09</date>';
	 *                    $xml = $xml.'<nights>1</nights>';
	 *                    $xml = $xml.'<roomtype>SD</roomtype>';
	 *                    $xml = $xml.'<rateclass>BAR</rateclass>';
	 *                    $xml = $xml.'<rooms>1</rooms>';
	 *                    $xml = $xml.'<adults>1</adults>';
	 *                    $xml = $xml.'<children/>';
	 *                    $xml = $xml.'<filter>0</filter>';
	 *                    $xml = $xml.'<channel>Website</channel>';
	 *                    $xml = $xml.'</staydetail>';
	 *                    $xml = $xml.'<iata>899999</iata>';
	 */
	public function getCrateMap($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_CRATEMAP);
		$xml = $xml . '<options cascade="true"/>';
		//解析数组为xmls
		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.9
	 * 酒店实时ARI可用性说明
	 * 获取酒店指定房型指定价格计划可用性信息
	 * @param int   $propId 酒店id
	 * @param array $data   请求数组数据
	 */
	public function getOnlineRateMap($propId, $data){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_ONLINE_RATEMAP);
		$xml = $xml . '<options cascade="true"/>';

		$a2xml = new A2Xml();
		$xml .= $a2xml->toXml($data);

		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.10
	 * 获取酒店产品计划变化信息
	 *
	 * @param 酒店id $propId
	 * @param 天数   $days
	 * @param      开始时间 Hh:mm 例如：10:00 $starttime
	 * @param      结束时间 Hh:mm 例如：15:00 $endtime
	 * @param 页号   $pageno
	 * @return string|mixed
	 */
	public function getJrezRateChange($propId, $days, $starttime, $endtime, $pageno){
		$xml = $this->setUserAuthInfo($propId, $this->M_JREZ_RATE_CHANGE);
		$xml = $xml . '<changedetail>';
		$xml = $xml . '<propID>' . $propId . '</propID>';
		$xml = $xml . '<days>' . $days . '</days>';
		$xml = $xml . '<starttime>' . $starttime . '</starttime>';
		$xml = $xml . '<endtime>' . $endtime . '</endtime>';
		$xml = $xml . '<pageno>' . $pageno . '</pageno>';
		$xml = $xml . '</changedetail>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 3.11
	 * 获取酒店产品增值内容列表信息
	 * @param 酒店id $propId
	 */
	public function getIncPromotionList($propId){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_INCPROMOTION_LIST);
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}


	/**
	 * 3.12
	 * 获取酒店产品增值内容信息
	 * @param 酒店id $propId
	 * @param      日期 2016-06-06 $date
	 */
	public function getIncPromotion($propId, $date){
		$xml = $this->setUserAuthInfo($propId, $this->M_GET_INCPROMOTION);
		$xml = $xml . '<inDate>' . $date . '</inDate>';
		$xml = $xml . '</crsmessage>';

		$result= $this->post($this->URL_HTTPS, $xml, TRUE);
		$res = xml2array($result);
		return $res;
	}

	/**
	 * 执行http 请求
	 * @param unknown $url
	 * @param unknown $xml
	 */
	public function post($url, $xml, $ssl = FALSE){
		$time=time();
		$header[] = "Content-type: text/xml";
		$ch = curl_init((string)$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		if($ssl === TRUE){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_CAPATH, realpath(APPPATH . '../') . '/certs/hubs1.net.crt');

		}
		$response = curl_exec($ch);

		$this->CI->Webservice_model->add_webservice_record($this->inter_id,'jinjiang',$url,$xml,$response,'query_post',$time,microtime(),$this->CI->session->userdata($this->inter_id.'openid'));

		if(curl_errno($ch)){
			//print curl_error($ch);
			curl_close($ch);
			return "";
		} else{
			curl_close($ch);
			return $response;

			//$result = (array)simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
			/*
				if(!empty($result['props'])){
				$data = json_decode(json_encode($result['props']),true);
				var_dump($data['prop']);
				if(!empty($data[0])){
				return $data[0];
				}else{
				return array();
				}
				}
				*/
		}
	}
}

class A2Xml{
	private $version = '1.0';
	private $encoding = 'UTF-8';
	private $root = 'root';
	private $xml = NULL;

	function __construct(){
//		$this->xml = new XmlWriter();
	}

	/*function toXml($data, $eIsArray = FALSE){
		if(!$eIsArray){
			$this->xml->openMemory();
			//$this->xml->startDocument($this->version, $this->encoding);
			//$this->xml->startElement($this->root);
		}
		foreach($data as $key => $value){
			if(is_array($value)){

				if(!is_numeric($key)){
					$this->xml->startElement($key);
				}
				$this->toXml($value, TRUE);

				if(!is_numeric($key)){
					$this->xml->endElement();
				}
				continue;
			}
			$this->xml->writeElement($key, $value);
		}
		if(!$eIsArray){
			$this->xml->endElement();
			return $this->xml->outputMemory(TRUE);
		}
	}*/


	public function toXml($data, $item = 'item', $id = NULL){
		$xml = $attr = '';
		foreach($data as $key => $val){
			if(is_numeric($key)){
				$id && $attr = " {$id}=\"{$key}\"";
				$key = $item;
			}
			$tag_state = TRUE;

			if(is_array($val) || is_object($val)){
				foreach($val as $k => $t){
					if(is_numeric($k)){
						$tag_state = FALSE;
					}
				}
			}

			if($tag_state === TRUE){
				$xml .= "<{$key}{$attr}>";
			}

			$item = $key;
			$xml .= (is_array($val) || is_object($val)) ? $this->toXml($val, $item, $id) : $val;
			if($tag_state === TRUE){
				$xml .= "</{$key}>";
			}
		}

		return $xml;
	}
}


