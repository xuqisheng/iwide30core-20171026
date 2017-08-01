<?php
class Redis_model extends CI_Model {
	protected  $redis;
	function __construct() {
		parent::__construct ();
		if (!isset($this->redis)){
			$this->redis=new Redis();
			$sysconfig = & get_config();
			if (ENVIRONMENT!='production'){
				$redis_host=$sysconfig['test_redis_host'];
				$redis_port=$sysconfig['test_redis_port'];
			}else{
				$redis_host=$sysconfig['prod_redis_host'];
				$redis_port=$sysconfig['prod_redis_port'];
			}
			$this->redis->connect($redis_host,$redis_port);
		}
	}
	
	function get_data($key){
	    $value = $this->redis->get ( $key );
	    MYLOG::w("get_data | key={$key} value={$value}","redis_model_logs");
		return $value;
	}
	function set_data($key,$value,$time){
	    MYLOG::w("set_data | key={$key} value={$value} time={$time}","redis_model_logs");
		return $this->redis->set ( $key,$value,$time );
	}
}