<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * @author lGh
 * redis类重写
 */
class Redis_proxy {
	protected $_redis_proxy;
	protected $_default_config = array (
			'host' => '127.0.0.1',
			'port' => 22122,
			'timeout' => 5 
	);
	protected $_config = array ();
	public $connect_status = FALSE;
	/**
	 * @param array $params=array(
		 'not_init'=>FALSE,//是否连接redis,FALSE为连接，默认FALSE
		 'module'=>'common',//调用模块,根据模块不同连接不同redis，默认common
		 'environment'=>ENVIRONMENT //服务器环境，根据环境不同连接不同redis，默认取全局变量ENVIRONMENT
	 )
	 */
	function __construct($params = array()) {
		if (empty ( $params ['not_init'] )) {
			$params ['module'] = isset ( $params ['module'] ) ? $params ['module'] : 'common';
			$params ['environment'] = isset ( $params ['environment'] ) ? $params ['environment'] : '';
			$this->init_proxy ( $params ['module'], TRUE, $params ['environment'] );
		}
	}
	/** 使用__call调用redis类对应方法
	 * @return NULL|mixed 未连接成功则返回NULL，避免程序挂掉
	 */
	function __call($func, $args) {
		if ($this->connect_status != TRUE) {
			return NULL;
		}
		// 尽量避免使用效率较低的call_user_func_array方法
		switch (count ( $args )) {
			case 0 :
				return $this->_redis_proxy->$func ();
			case 1 :
				return $this->_redis_proxy->$func ( $args [0] );
			case 2 :
				return $this->_redis_proxy->$func ( $args [0], $args [1] );
			case 3 :
				return $this->_redis_proxy->$func ( $args [0], $args [1], $args [2] );
			case 4 :
				return $this->_redis_proxy->$func ( $args [0], $args [1], $args [2], $args [3] );
			case 5 :
				return $this->_redis_proxy->$func ( $args [0], $args [1], $args [2], $args [3], $args [4] );
			default :
				return call_user_func_array ( array (
						$this->_redis_proxy,
						$func 
				), $args );
		}
	}
	/**初始化redis，可以再次调用此方法来更换redis连接
	 * @param string $module //调用模块,根据模块不同连接不同redis，默认common
	 * @param string $refresh //需更换连接时，传TRUE
	 * @param string $environment //服务器环境，根据环境不同连接不同redis，默认取全局变量ENVIRONMENT
	 * @return boolean|Redis
	 */
	public function init_proxy($module = 'common', $refresh = FALSE, $environment = '') {
		if (! isset ( $this->_redis_proxy ) || $refresh) {
			$sysconfig = & get_config ();
			empty ( $environment ) and $environment = ENVIRONMENT;
			if ($environment != 'development' || 1) {//测试环境上ENVIRONMENT也为development
				$this->_config ['host'] = $sysconfig ['redis_proxy_host'];
				$this->_config ['port'] = $sysconfig ['redis_proxy_port'];
				$this->_config ['timeout'] = $sysconfig ['redis_proxy_timeout'];
			} else {
				$this->_config ['host'] = $sysconfig ['test_redis_proxy_host'];
				$this->_config ['port'] = $sysconfig ['test_redis_proxy_port'];
				$this->_config ['timeout'] = $sysconfig ['test_redis_proxy_timeout'];
			}
			$this->_redis_proxy = new Redis ();
			$micro_time = microtime ();
			if (! $this->_redis_proxy->connect ( $this->_config ['host'], $this->_config ['port'], $this->_config ['timeout'] ) && $this->_config != $this->_default_config) {
				$micro_time = explode ( ' ', $micro_time );
				$wait_time = time () - $micro_time [1] + number_format ( $micro_time [0], 2, '.', '' );
				MYLOG::w ( 'first connect:args:' . json_encode ( func_get_args () ) . ',config:' . json_encode ( $this->_config ) . ',wait_time:' . $wait_time, 'redis_log', '_fail_connect' );
				$this->_config = $this->_default_config;
				$micro_time = microtime ();
				if (! $this->_redis_proxy->connect ( $this->_config ['host'], $this->_config ['port'], $this->_config ['timeout'] )) {
					$micro_time = explode ( ' ', $micro_time );
					$wait_time = time () - $micro_time [1] + number_format ( $micro_time [0], 2, '.', '' );
					MYLOG::w ( 'second connect:args:' . json_encode ( func_get_args () ) . ',config:' . json_encode ( $this->_config ) . ',wait_time:' . $wait_time, 'redis_log', '_fail_connect' );
					$this->connect_status = FALSE;
					return FALSE;
				}
			}
		}
		$this->connect_status = TRUE;
		return $this->_redis_proxy;
	}
	public function cur_config() {
		return $this->_config;
	}
}
