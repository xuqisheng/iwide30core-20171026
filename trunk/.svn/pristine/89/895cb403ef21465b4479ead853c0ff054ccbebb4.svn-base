<?php
/**
 * @todo 组装生成签名
 * 把数据对象生成URL链接形式的字符串加上校验的token,然后生成MD5摘要返回
 * @author ounianfeng
 * @since 2016-01-15
 * @version 1.0
 */
class Isigniture_model extends CI_Model {
	public $parameters;
	function __construct() {
		parent::__construct ();
	}
	
	private function format_biz_query_para_map($para_map, $urlencode) {
		$buff = "";
		ksort($para_map);
		foreach ($para_map as $k => $v) {
			if($urlencode) {
				$v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	/**
	 * 签名方法
	 * @param Array 参与签名的数组对象
	 * @param string 参与签名的token
	 * @return string 签名结果
	 */
	public function get_sign($Obj,$sign_key = '') {
		foreach ( $Obj as $k => $v ) {
			$parameters [$k] = $v;
		}
		ksort ( $parameters );
		$String = $this->format_biz_query_para_map ( $parameters, false );
		if (! empty ( $sign_key ))
			$String = $String . "&key=" . $sign_key;
		$String  = sha1 ( $String );
		$result_ = strtoupper ( $String );
		return $result_;
	}
}