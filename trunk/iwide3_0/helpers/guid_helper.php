<?php
error_reporting(0);
class Guid {
	/**
	 * 系统当前时间
	 */
	private static function _curTimeMillis() {
		list ( $usec, $sec ) = explode ( "  ", microtime () );
		return $sec . substr ( $usec, 2, 3 );
	}
	
	/**
	 * 客户端相关信息
	 */
	private static function _getHost() {
		$name = empty ( $_SERVER ["HTTP_USER_AGENT"] ) ? 'localhost' : $_SERVER ["HTTP_USER_AGENT"];
		return strtolower ( $name . '/' . self::_clientIp () );
	}
	
	/**
	 * 客户端IP
	 */
	private static function _clientIp() {
		$ip = (@$_SERVER ['HTTP_REALIP']) ? $_SERVER ['HTTP_REALIP'] : ((@$_SERVER ['HTTP_X_FORWARDED_FOR']) ? $_SERVER ['HTTP_X_FORWARDED_FOR'] : @$_SERVER ['REMOTE_ADDR']);
		
		if (empty ( $ip ))
			$ip = '0.0.0.0';
		return $ip;
	}
	
	/**
	 * 随机数
	 */
	private static function _random() {
		$tmp = rand ( 0, 1 ) ? '-' : '';
		return $tmp . rand ( 1000, 9999 ) . rand ( 1000, 9999 ) . rand ( 1000, 9999 ) . rand ( 100, 999 ) . rand ( 100, 999 );
	}
	
	/**
	 * 生成GUID字符串
	 * (长度：32 + 4)
	 * 三段：一段是微秒, 一段是地址, 一段是随机数
	 */
	public static function toString() {
		if (function_exists ( 'com_create_guid' )) {
			 return trim(com_create_guid(), '{}');
		} else {
			$string = md5 ( self::_getHost () . ':' . self::_curTimeMillis () . ':' . self::_random () );
			$raw = strtoupper ( $string );
			return substr ( $raw, 0, 8 ) . '-' . substr ( $raw, 8, 4 ) . '-' . substr ( $raw, 12, 4 ) . '-' . substr ( $raw, 16, 4 ) . '-' . substr ( $raw, 20 );
		}
	}
}