<?php 
class Yuanzhou extends CI_Model
{    
	const URL = 'http://120.76.193.180/yuanzhou/CommonAction';
	const DEBUG = 0;//0代表正式环境，1代表测试环境
	
	/**
	 * 会员注册
	 * @param unknown $data
	 * @return SimpleXMLElement
	 */
	public function wxregisterMember($data)
	{		
		$post['func']='WxregisterMember';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);
		return $result;
	}
	
	/**
	 * 会员查询 只有卡号的会员才能查询
	 * @param unknown $data
	 * @return SimpleXMLElement
	 */
	public function wxqueryMemberinfo($data)
	{
		$post['func']='WxqueryMemberinfo';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);
		return $result;
	}

	/**
	 * 修改会员信息   只能phone更改wxuserid
	 * @param unknown $data
	 * @return boolean
	 */
	public function updateWxMember($data)
	{
		$post['func']='updateWxMember';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);

		$result=simplexml_load_string($result);
		
		if($result->statuscode==200) {
			return true;
		} else {
			return false;
		}
	}
	
	//还没通过
	public function updateWxPassword($data)
	{
		$post['func']='updateWxPassword';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);
		print_r($result);
		if($result->statuscode==200){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 验证会员登录
	 * @param unknown $data
	 * @return boolean
	 */
	public function isExistwxCardNew($data)
	{
		$post['func']='isExistwxCardNew';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$this->db->insert('weixin_text',array('content'=>'send-'.$request_data.';receive-'.$result,'edit_date'=>date('Y-m-d H:i:s')));
		$result=simplexml_load_string($result);
		if($result->statuscode==200) {
			return true;
		} else {
			return false;
		}
	}

	/**	
	*	验证手机号码和身份证号码是否重复
	*	@param unknown $data 
	*	@return boolean
	*/
	public function isExistPhoneIdno($data){
		$post['func']='isExistPhoneIdno';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);
		if($result->statuscode==200) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 会员积分列表查询
	 * @param unknown $data
	 * @return array|boolean
	 */
	public function querywxScoreList($data)
	{
		$post['func']='querywxScoreList';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);

		if(isset($result->scorelist)) {
			$result = (array)$result;
		    return $result['scorelist'];
		} else {
			return false;
		}
	}
	
	/**
	 * 会员积分扣减   负数为加积分
	 * @param unknown $data
	 * @return boolean
	 */
	public function WxdeductPoints($data)
	{
		$post['func']='WxdeductPoints';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);

		if($result->statuscode==200) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 会员卡储值余额查询
	 * @param unknown $data
	 * @return SimpleXMLElement
	 */
	public function WxMemberValueCard($data)
	{
		$post['func']='WxMemberValueCard';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);
		
		return $result;
	}
	
	/**
	 * 会员储值卡消费记录
	 * @param unknown $data
	 * @return multitype:Ambigous <> |array|boolean
	 */
	public function WxMemberLineCard($data)
	{
		$post['func']='WxMemberLineCard';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);

		$result=simplexml_load_string($result);

		if(isset($result->consumelist)) {
			$result = (array)$result;
			if(is_object($result['consumelist'])) {
				return array($result['consumelist']);
			}
		    return $result['consumelist'];
		} else {
			return false;
		}
	}
	//密码初始化
	public function WxPassWordInit($data)
	{
		$post['func']='WxPassWordInit';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);
		if($result->statuscode==200) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 是否存在储值卡
	 * @param unknown $data
	 * @return boolean
	 */
	public function WxIsExistVipCardDoc($data)
	{
		$post['func']='WxIsExistVipCardDoc';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
	
		$result=simplexml_load_string($result);

		if($result->statuscode==200) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 会员卡间夜数查询
	 * @param unknown $data
	 * @return multitype:Ambigous <> |array|boolean
	 */
	public function WxMemberRoomNights($data)
	{
		$post['func']='WxMemberRoomNights';
		$post['debug']=self::DEBUG;
		$post['content']=$this->array2req($data);
	
		$request_data = http_build_query($post);
		$result = $this->http_post(self::URL,$request_data);
		$result=simplexml_load_string($result);

		if(isset($result->roomnightslist)) {
			$result = (array)$result;
			if(is_object($result['roomnightslist'])) {
				return array($result['roomnightslist']);
			}
			return $result['roomnightslist'];
		} else {
			return false;
		}
	}
	
	function array2req($arr){
		return "<?xml version='1.0' encoding='utf-8'?><request>".$this->arrayToXml($arr)."</request>";
	}
	
	/**
	 * 	作用：array转xml
	 */
	function arrayToXml($arr,$k=null) {
		$xml = '';
		if(!is_null($k))
			$xml.="<$k>";
		foreach ($arr as $key=>$val) {
			if(is_array($val)){
				$xml.=$this->arrayToXml($val,$key);
			}
			else if (is_numeric($val)) {
				$xml.="<".$key.">".$val."</".$key.">";
			}
			else
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
		if(!is_null($k))
			$xml.="</$k>";
		return $xml;
	}
	
    protected function _http_post($url,$data)
    {
    	$now=time();
    	
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 20);//@Editor lGh 增加超时
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    	$result = curl_exec($curl);
    	
    	//@Editor lGh 2016-6-2 22:37:29 加日志
    	$inter_id='a440577876';
    	$CI=& get_instance();
    	$CI->load->model('common/Webservice_model');
    	$CI->Webservice_model->add_webservice_record($inter_id, 'yuanzhou', $url, $data, $result,'query_post', $now, microtime (), $CI->session->userdata ( $inter_id . 'openid' ));
    	
    	if (curl_errno($curl)) {
    		return 'ERROR '.curl_error($curl);
    	}
    	curl_close($curl);
    	return $result;
    }
    protected function http_post($url,$data)
    {
    	$CI=& get_instance();
    	$CI->load->library ( 'Baseapi/Yzhou_webservice.php' );
    	$YzhouOj = new Yzhou_webservice ();
    	parse_str($data,$data);
    	
    	$xml = htmlspecialchars_decode($data['content']);
    	
    	$s= $YzhouOj->sendTo($data['func'], $xml,$data['debug']);
    	
    	return $s;
    }
}