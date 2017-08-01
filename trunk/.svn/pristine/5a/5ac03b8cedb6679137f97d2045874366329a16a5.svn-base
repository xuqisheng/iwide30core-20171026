<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shuxiang extends CI_Model
{	
	protected $request_url = "http://61.177.58.132:11001/ipms/";
	
	/**
	 * 'cardId'=>'280244'
	 * 会员储值信息
	 * @param unknown $data
	 * @return mixed
	 */
	public function getAccountList($data)
	{
		$url = "membercard/getAccountList";
		
		return $this->doRequest($url, $data);
	}
	
	//不行
	public function bindMember($data)
	{
		$url = "membercard/bindOpenId";
		return $this->doRequest($url, $data);
	}
	
	public function saveWebpay($data)
	{
		$url = "CRS/saveWebPay";
		return $this->doRequest($url, $data);
	}
	
	/**
	 * 验证openid的会员是否存在
	 * @param unknown $data
	 * @return mixed
	 */
	public function verifyOpenid($data)
	{
		$url = "membercard/verifyOpenIdIsExists";
		
		return $this->doRequest($url, $data);
	}
	
	/** 
	 *  微信会员注册
	 *  'name'=>'George',
     *	'sex'=>1,
     *	'mobile'=>'13265909492',
     *	'email'=>'abc@abc.com',
     *	'idType'=>"01",
     *	'idNo'=>"11000019810419093X",
     *	'openIdUserId'=>'sssssssssssss',
     *	'password'=>'',
     *	'openIdType'=>'WEIXIN',
	 */
	public function register($data)
	{
		$url = "membercard/registerMemberCardWithOutVerify";
		
		return $this->doRequest($url, $data);
		
	}
	
	/**
	 * 微信会员登录
	 * 'loginId'=>'sssssssssssss',
     * 'loginPassword'=>true,
     * 'loginType'=>5
	 */
	public function login($data)
	{
		$url = "membercard/memberLogin";
	
		return $this->doRequest($url, $data);
	
	}
	
	public function doRequest($url,$data)
	{
		$data['hotelGroupId'] = 2;
		$query = http_build_query($data);
		$url = $this->request_url.$url."?".$query;
		
		$result = $this->http_post($url);
		
		return json_decode($result);
	}
	
	public function http_post($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
			return 'ERROR '.curl_error($curl);
		}
		curl_close($curl);
		return $result;
	}
}