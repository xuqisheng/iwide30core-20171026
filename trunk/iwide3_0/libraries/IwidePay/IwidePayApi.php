<?php
header ( 'Content-type:text/html;charset=utf-8' );
require_once 'IwidePayConfig.php';
require_once 'IwidePayData.php';

/**
 * 接口访问类
 */

class IwidePayApi{
	/**
	 * 统一支付请求
	 */
	public function unifyPayRequest($arr){
		$url = IwidePayConfig::REQUEST_URL;
		if(empty($arr['notifyUrl'])){
			$arr['notifyUrl'] = IwidePayConfig::NOTIFY_URL;
		}
		if(empty($arr['merNo'])){
			$arr['merNo'] = IwidePayConfig::MERNO;
		}
		if(empty($arr['subChnlMerNo'])){
			$arr['subChnlMerNo'] = IwidePayConfig::SUBCHNLMERNO;
		}
		if(empty($arr['version'])){
			$arr['version'] = IwidePayConfig::VERSION;
		}
		$post = self::spliceParamter($arr);
		MYLOG::w('send cmbc data:'.$post,'iwidepaypay');
		return self::postStrCurl($post,$url);
	}

	/**
	 * 退款请求
	 */
	public function refundRequest($arr,$url=''){
		if(empty($url)){
			$url = IwidePayConfig::REQUEST_URL;
		}
		if(empty($arr['notifyUrl'])){
			$arr['notifyUrl'] = IwidePayConfig::NOTIFY_URL;
		}
		if(empty($arr['merNo'])){
			$arr['merNo'] = IwidePayConfig::MERNO;
		}
		if(empty($arr['version'])){
			$arr['version'] = IwidePayConfig::VERSION;
		}
		$post = self::spliceParamter($arr);
		return self::postStrCurl($post,$url);
	}

	/**
	 * 支付异步回调
	 */
	public function payCallBack($params_str){
		//转成数组
		$params_arr = parseQString($params_str,true);
		//验签
		$isSuccess = self::validate($params_arr);
		if($isSuccess){
			//成功
			return $params_arr;
		}
		//失败
		return false;	
	}

	/**
	 * 退款异步回调
	 */
	public function payreturnCallBack($params_str){
		//转成数组
		$params_arr = parseQString($params_str,true);
		//验签
		$isSuccess = self::validate($params_arr);
		if($isSuccess){
			//成功
			return $params_arr;
		}
		//失败
		return false;	
	}

	/**
	 * 关闭订单请求
	 */
	public function closeOrderRequest($arr){
		$url = IwidePayConfig::REQUEST_URL;
		if(empty($arr['merNo'])){
			$arr['merNo'] = IwidePayConfig::MERNO;
		}
		if(empty($arr['version'])){
			$arr['version'] = IwidePayConfig::VERSION;
		}
		$post = self::spliceParamter($arr);
		return self::postStrCurl($post,$url);
	}

	/**
	 * 查询订单请求
	 */
	public function queryOrderRequest($arr){
		$url = IwidePayConfig::REQUEST_URL;
		if(empty($arr['merNo'])){
			$arr['merNo'] = IwidePayConfig::MERNO;
		}
		if(empty($arr['version'])){
			$arr['version'] = IwidePayConfig::VERSION;
		}
		$post = self::spliceParamter($arr);
		return self::postStrCurl($post,$url);
	}

	/**
	 * 查询支付状态请求
	 */
	public function queryPayStatusRequest($arr){
		$url = IwidePayConfig::REQUEST_URL;
		if(empty($arr['merNo'])){
			$arr['merNo'] = IwidePayConfig::MERNO;
		}
		if(empty($arr['version'])){
			$arr['version'] = IwidePayConfig::VERSION;
		}
		$post = self::spliceParamter($arr);
		return self::postStrCurl($post,$url);
	}

	/**
	 * 余额代付请求（1.6.3.4）
	 */
	public function balancePayRequest($arr){
		$url = IwidePayConfig::REQUEST_URL;
		if(empty($arr['notifyUrl'])){
			$arr['notifyUrl'] = IwidePayConfig::NOTIFY_URL;
		}
		if(empty($arr['merNo'])){
			$arr['merNo'] = IwidePayConfig::MERNO;
		}
		if(empty($arr['version'])){
			$arr['version'] = IwidePayConfig::VERSION;
		}
		$post = self::spliceParamter($arr);
		return self::postStrCurl($post,$url);
	}

	/**
	 * 以post方式提交str到对应的接口url
	 */
	private static function postStrCurl($str,$url,$second = 30){
        //初始化
        $curl = curl_init();
        //设置超时
		curl_setopt($curl, CURLOPT_TIMEOUT, $second);
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $str);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //响应报文验签
        //转成数组
		$params_arr = parseQString($data);
        $isSuccess = self::validate($params_arr);
        if($isSuccess){
        	return $data;
        }
        return 'Validation error:'.$data;
	}

	/**
	 * 拼接最终请求parameter参数
	 */
	private static function	spliceParamter($arr){
		//拼接加密字符串
        $parameter = spliceURL($arr); //获取url串
        $signature = self::spliceEncryptString($parameter);//获取签名加密字符串
        //拼接最终请求parameter参数
        $post = $parameter . "&signature=" . $signature;
        return $post;
	}

	/**
	 * 拼接加密字符串
	 */
	private static function spliceEncryptString($parameter){
		//判断证书存在，并导出私钥
        $privateKey = openssl_pkey_get_private(file_get_contents(IwidePayConfig::SSLPRV_PATH));
        $encrypted = "";
        // $params_sha1 = sha1 ( $parameter, FALSE );
        //加密
        openssl_sign($parameter, $encrypted, $privateKey,OPENSSL_ALGO_SHA1);
        $signature = base64_encode($encrypted);  //返回加密穿
        //加号转译
        $signature = str_replace("+", "%2B", $signature);
        return $signature;
	}

	/**
	 * 验签
	 */
	private static function validate($params){
		//判断证书存在，并导出公钥
        $publicKey = openssl_pkey_get_public(file_get_contents(IwidePayConfig::SSLPUB_PATH));
        //获取签名
       	$signature_str = $params['signature'];
       	unset($params['signature']);
       	$params_str = createLinkString ( $params, true, false );
       	// $params_sha1 = sha1($params_str,false);
       	$signature = base64_decode ( $signature_str );
        $isSuccess = openssl_verify($params_str, $signature, $publicKey,OPENSSL_ALGO_SHA1);
        return $isSuccess;
	}
}