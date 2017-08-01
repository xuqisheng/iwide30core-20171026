<?php 
class Lvyun extends CI_Model
{    
	const URL = 'http://61.177.58.132:11011/ipms/';
	const DEBUG = 0;//0代表正式环境，1代表测试环境
		
	/************************************************
	 * 书香会员积分相关接口
	 *
	 * @package wukeylegend
	 * @subpackage application
	 * @category model
	 * @author Liyishuai
	 * @filesource
	 * @copyright CopyRight 2016
	 * @since Version 3.2
	 * @license
	 *
	 *******************************************************************************/
	
	
	/**
	 * 会员积分列表查询
	 * @param unknown $data
	 * @return array|boolean
	 */
	public function querywxScoreList($data)
	{
		//接收参数
		$post['firstResult']=$data['firstResult'];
		$post['pageSize']=$data['pageSize'];
		$post['hotelGroupId']=$data['hotelGroupId'];
		$url=self::URL."membercard/getExchangItemList";
	
		$request_data = http_build_query($post);
		
		$result = $this->http_post($url,$request_data);
		
		$result = json_decode($result);
		
		$result= ($result);
		
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
	public function wxdeductPoints($post)
	{
		//接收参数
		$data['hotelGroupId'] = '2';		//酒店集团编号 2代表书香
		//$data['hotelId']=$post['hotelId'];
		$data['cardId'] = $post['cardId'];	//会员卡id
		$data['cardNo'] = $post['cardNo'];	//会员卡号
		$data['code'] = 'WX001';		//物品类型
		//$data['extraInfo']=$post['extraInfo'];
		$data['amountString'] = $post['amountString'];	//数量
		$data['addr'] = $post['addr'];		//地址
		//$data['disHotel']=$post['disHotel'];
		$data['remark'] = $post['remark'];	//备注
		$url=self::URL."membercard/pointExchange";	//拼接curl
	
		//curl传输数据
		$request_data = http_build_query($data);
		$result = $this->http_post($url,$request_data);
		$result = json_decode($result);

		//验证结果
		if($result->resultCode == 0) {
			//var_dump($result);exit;
			return array('code'=>0,'errmsg'=>$result->resultMsg,'point'=>$result->point);		//正确返回0，msg：成功
		} else {
			return array('code'=>1,'errmsg'=>$result->resultMsg);		//错误返回1，msg：未知错误
		}
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
	
    protected function http_post($url,$data)
    {
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
    	$result = curl_exec($curl);
    	if (curl_errno($curl)) {
    		return 'ERROR '.curl_error($curl);
    	}
    	curl_close($curl);
    	return $result;
    }
    
    function array2req($arr){
    	return "<?xml version='1.0' encoding='utf-8'?><request>".$this->arrayToXml($arr)."</request>";
    }
}