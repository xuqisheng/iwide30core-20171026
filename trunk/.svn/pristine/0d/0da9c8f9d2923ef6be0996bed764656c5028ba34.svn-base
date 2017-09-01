<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_base {
	private static $_objs = array (); // 对象容器
	public static $_common_url_param = array ();
	public static $_basic_param = array ();
	public function __construct() {
		$this->CI = & get_instance ();
	}
	public static function inst($className = __CLASS__) {
		if (isset ( self::$_objs [$className] )) {
			return self::$_objs [$className];
		} else {
			return self::$_objs [$className] = new $className ( null );
		}
	}
	/**
	 * 未完成
	 *
	 * @param int $result
	 *        	运行结果 具体值看Wxapp_conf
	 * @param string $msg
	 *        	显示给用户的信息
	 * @param array $data
	 *        	数据集
	 * @param string $fun
	 *        	调用的方法的标识 如hotel/search
	 * @param number $msg_lv
	 *        	消息级别 具体值看Wxapp_conf
	 * @param string $exit
	 *        	输出数据后是否退出整个程序
	 */
	public function out_put_msg($result, $msg = '', $data = array(), $fun = '', $exit = TRUE) {
		$info = array ();
		$result = Hotel_const::enums ( 'ajax_status', $result );
		$info ['status'] = $result;
		$info ['msg'] = $msg;
		$info ['msg_type'] = $msg_lvs [$msg_lv];
		if (! empty ( $data )) {
			$data = json_decode ( json_encode ( $data ), TRUE );
			$info ['web_data'] = $this->data_dehydrate ( $data, Wxapp_conf::get_dehydrate_samples ( $fun ) );
		}
		if (! $exit) {
			ob_clean ();
		}
		echo json_encode ( $info, JSON_UNESCAPED_UNICODE );
		if ($exit) {
			exit ();
		}
	}
	public function add_essential_param($param = array()) {
		if (WEB_AREA == 'front') {
			$inter_id = isset ( $this->CI->inter_id ) ? $this->CI->inter_id : substr ( $this->CI->input->get ( 'id', true ), 0, 10 );
			if ($inter_id)
				$param ['id'] = $inter_id;
			$ori_saler = $this->CI->input->get ( 'osaler', true );
			if ($ori_saler) {
				$param ['osaler'] = $ori_saler;
			}
			$link_saler = $this->CI->input->get ( 'lsaler', true );
			if (! empty ( self::$_basic_param ['own_saler'] )) {
				$param ['lsaler'] = self::$_basic_param ['own_saler'];
				if ($link_saler && $link_saler != $param ['lsaler']) {
					$param ['osaler'] = $link_saler;
					self::$_basic_param ['saler_redirect'] = 1;
				}
				if (empty ( $link_saler )) {
					self::$_basic_param ['saler_redirect'] = 1;
				}
			} else {
				if ($link_saler) {
					$param ['lsaler'] = $link_saler;
				}
			}
		} elseif (WEB_AREA == 'admin') {
		}
		return $param;
	}
	public function get_share_url($openid, $route, $param = array(), $token = FALSE) {
	}
	/**
	 * 获取前端url，会加上必须的url参数
	 *
	 * @param $route 系统url
	 *        	传如"model(*)/controller(*)/function"(*为当前模块) 或 在Hotel_const.php中预定义的链接符号(如'SEARCH' = 'hotel/hotel/search')，
	 *        	外部url则原样传入
	 * @param array $param
	 *        	附加在url后的参数，array('id'=>'a452233816')
	 * @param string $foreign
	 *        	外部url传TRUE
	 * @return string 此方法生成的链接会自动加上inter_id和其他有必要的参数
	 */
	public function get_url($route, $param = array(), $foreign = FALSE) {
		if (empty ( $route ) || $route == '#') {
			return $route;
		}
		// 自动追加传递参数
		is_array ( $param ) or parse_str ( $param, $param );
		$param = $this->add_essential_param ( $param );
		empty ( self::$_common_url_param ) or $param = array_merge ( $param, self::$_common_url_param );
		if ($foreign) {
			$ql = strpos ( $route, '?' );
			if ($ql !== FALSE) {
				$url_param = substr ( $route, $ql + 1 );
				parse_str ( $url_param, $url_param );
				$param = array_merge ( $param, $url_param );
				$route = substr ( $route, 0, $ql );
			}
			$route .= '?';
			foreach ( $param as $k => $v ) {
				$route .= $k . '=' . $v . '&';
			}
			return substr ( $route, 0, - 1 );
		}
		$URI = & load_class ( 'URI', 'core', NULL );
		$segments = $URI->segments;
		$module = $segments [1];
		$controller = isset ( $segments [2] ) ? $segments [2] : 'index';
		$action = isset ( $segments [3] ) ? $segments [3] : 'index';
		
		$check_url = Hotel_const::enums ( 'url_seg', strtoupper ( $route ) );
		$routeArr = empty ( $check_url ) ? explode ( '/', $route ) : explode ( '/', $check_url );
		if (isset ( $routeArr [0] ) && $routeArr [0] == '*')
			$routeArr [0] = $module;
		if (isset ( $routeArr [1] ) && $routeArr [1] == '*')
			$routeArr [1] = $controller;
		if (isset ( $routeArr [2] ) && $routeArr [2] == '*')
			$routeArr [2] = $action;
		$path = implode ( '/', $routeArr );
		
		$pam = '?';
		foreach ( $param as $k => $v ) {
			$pam .= $k . '=' . $v . '&';
		}
		$pam = substr ( $pam, 0, - 1 );
		return site_url () . '/' . $path . $pam;
	}
	function url_param($param = array()) {
		$param = $this->add_essential_param ( $param );
		empty ( self::$_common_url_param ) or $param = array_merge ( $param, self::$_common_url_param );
		$s = '';
		foreach ( $param as $k => $v ) {
			$s .= $k . '=' . $v . '&';
		}
		return substr ( $s, 0, - 1 );
	}
}
