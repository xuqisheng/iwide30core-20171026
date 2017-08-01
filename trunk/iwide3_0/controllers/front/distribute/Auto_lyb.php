<?php
//!isset($_SERVER['SERVER_PROTOCOL']) OR exit('No direct script access allowed');
/*
 * 定时跑琅琊榜排行数据
 * author situguanchen 2017-4-17
 */
class Auto_Lyb extends CI_Controller{
    function __construct() {
        parent::__construct ();
        $this->debug = $this->input->get ( 'debug' );
        error_reporting ( 0 );
        if (! empty ( $this->debug )) {
            error_reporting ( E_ALL );
            ini_set ( 'display_errors', 1 );
        }
        $this->output->enable_profiler ( false );
        $this->load->library('MYLOG');
    }
	
	public function index(){
		echo 'arrival';
        die;
	}
    //添加实例redis stgc 2017-02-24
    protected function get_redis_instance(){
        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();
        return $redis;
    }
    protected function _load_cache( $name='Cache' ){
        if(!$name || $name=='cache')
            $name='Cache';
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
        return $this->$name;
    }

    public function check_arrow(){//访问限制
       // return true;
         if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){ 
            $ip_whitelist= array(
                '10.25.168.86', //redis01
                '10.25.3.85',  //redis02
                '10.46.74.165',
                '10.25.1.106',
            );
            $client_ip= $this->input->ip_address();
            if( in_array($client_ip, $ip_whitelist) ){
                return TRUE;
                 
            } else {
                exit('非法访问！');
            }
            
        } else {
            return TRUE;
        }
    }

    //配置要跑的inter_id
    protected function cache_config(){
        $cache_inter_id = array('a421641095','a455510007','a441098524','a472731996','a452223043','a449675133');//配置需要緩存的inter_id 目前配置讀緩存的有：月收益，總收益，總粉絲 dis_v1.php也要配置一次
        return $cache_inter_id;
    }
	//自动跑，现在要跑的sql有：总粉丝排行，总收益排行,月收益排行
    public function autorun(){
        $this->check_arrow();
		set_time_limit(0);
		@ini_set('memory_limit','1024M');
		$start = date('Y-m-d H:i:s').' : '.microtime(TRUE).' 脚本开始...';
        log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'琅琊榜排行脚本开始运行');
        //加redis锁 by situguanchen 2017-03-30
        /*$redis = $this->get_redis_instance();
        $lock_key = "DIST_LYB_LOCK_KEY";//琅琊榜
        $lock = $redis->setnx($lock_key, 'lock');
        if(!$lock) {
            log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'琅琊榜排行脚本锁住了');
            die('FAILURE!');
        }*/
        $inter_ids = $this->cache_config();
        $this->load->model('distribute/staff_model');
        foreach($inter_ids as $k=>$v){
            //获取总收益排行sql
            $this->staff_model->get_auto_data_res($v,'AMT','ALL');//总排行
            //获取收益月排行
            $this->staff_model->get_auto_data_res($v,'AMT','MONTH');//月排行
            //获取粉丝总排行
            $this->staff_model->get_auto_data_res($v,'FANS','ALL');//粉丝总排行排行
        }
		$end = date('Y-m-d H:i:s').' : '.microtime(TRUE).' 脚本结束...';
        log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'琅琊榜排行脚本结束');
        /*$redis->delete($lock_key);*///解锁
		echo 'done!';die;
	}

}

