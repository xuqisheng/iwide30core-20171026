<?php
//!isset($_SERVER['SERVER_PROTOCOL']) OR exit('No direct script access allowed');
/*
 * 定时处理分组，分组奖励任务
 * author situguanchen 2016-11-17
 */
class Auto_group extends CI_Controller{
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
        //var_dump($_SERVER['REMOTE_ADDR']);die;
        //return true;
        $arrow_ip = array('10.25.168.86','10.25.3.85','10.46.74.165','10.25.1.106');//只允许服务器自动访问，不能手动
       if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
            exit('非法访问！');
        }
    }
	//统计，根据设定的自动分组信息，将分销员进行分组
    public function auto_group_run(){
            $this->check_arrow();
		set_time_limit(0);
		@ini_set('memory_limit','1024M');
		$this->load->model('distribute/distribute_group_model');
		$start = date('Y-m-d H:i:s').' : '.microtime(TRUE).' 分组脚本开始...';
		$this->distribute_group_model->write_log($start);
        //加redis锁 by situguanchen 2017-03-30
        $redis = $this->get_redis_instance();
        $lock_key = "DISTRI_GROUP_G_LOCK_KEY";//分销分组
        $lock = $redis->setnx($lock_key, 'lock');
        if(!$lock) {
            log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'分销分组锁住了');
            die('FAILURE!');
        }
        //先将过期的分组status=0 有效期止小于现在的时候
        //$sql = "update iwide_distribute_group set status = 0 where inter_id = '{$inter_id}' and status = 1 and is_delete = 0 and end_time <".time();
        $this->db->update('iwide_distribute_group',array('status'=>0),array('status'=>1,'is_delete'=>0,'end_time<'=>time()));
		//遍历inter_id
		/*$inter_ids = $this->distribute_group_model->get_all_inter_id();
		if(!empty($inter_ids)){
			foreach($inter_ids as $inter_id){*/
				//获取符合条件的分组（自动组)
                $filter = array(
                    'locked'=>0,//没上锁状态
                    'type'=>2,
                    'last_run_time'=>23*3600,//脚本最后运行时间间隔23小时 测试先为1秒
                  //  'limit' =>5,
                );
				$group_ids = $this->distribute_group_model->get_all_zd_group($filter);//var_dump($group_ids);
				if(!empty($group_ids)){
					foreach($group_ids as $group){
                        //上锁
                        $this->db->update('distribute_group',array('locked'=>1),array('group_id'=>$group['group_id']));
						//获取符合条件的人员信息 进行统计
						$saler_ids = $this->distribute_group_model->get_salers_info_list($group['inter_id'],$group);
						if(!empty($saler_ids)){
							//根据分销号查询统计
								$res = $this->distribute_group_model->check_count_result($group['inter_id'],$saler_ids,$group);
						}
						//每个组更新完后，统计组内人员
						$count_res = $this->distribute_group_model->get_group_member_count_group_by_openid($group);
                        //解锁 并且 更新数据
						$this->db->update('distribute_group',array('his_member_count'=>$count_res['his_count'],'member_count'=>$count_res['week_count'],'last_run_time'=>time(),'locked'=>0),array('group_id'=>$group['group_id']));
					}
				}
			//}
		//}
		$end = date('Y-m-d H:i:s').' : '.microtime(TRUE).' 分组脚本结束...';
		$this->distribute_group_model->write_log($end);
        $redis->delete($lock_key);//解锁
		echo 'done!';die;
	}

    //计算奖励数据,推送给分销中心 stgc 20161110
    public function auto_reward(){
        $this->check_arrow();
        set_time_limit(0);
        @ini_set('memory_limit','1024M');
        $this->load->model('distribute/distribute_group_model');
        $start = date('Y-m-d H:i:s').' : '.microtime(TRUE).' 奖励脚本开始...';
        $this->distribute_group_model->write_log($start);
        echo $start;
        //加redis锁 by situguanchen 2017-03-30
        $redis = $this->get_redis_instance();
        $lock_key = "GROUP_REWARD_R_LOCK_KEY";//分销分组发放
        $lock = $redis->setnx($lock_key, 'lock');
        if(!$lock) {
            log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'分组奖励脚本锁住了');
            die('FAILURE!');
        }
        //遍历奖励规则
        //查询所有奖励规则
        $this->db->where(array(
          //  'inter_id'=>$ivalue['inter_id'],
            'locked'=>0,//无锁状态
            'status' => 1,
            'start_time<='=>date('Y-m-d',time()),
            'end_time>'=>date('Y-m-d',time())
        ));
        $reward = $this->db->get('distribute_group_reward')->result_array();
        if(!empty($reward)){
            foreach($reward as $rkey=>$rvalue){
                //判断已奖人数是否超出限定人数
                if(empty($rvalue['limit_count'])){
                    $log  = 'inter_id:'.$rvalue['inter_id'].'奖励id：'.$rvalue['reward_id'].'没有设置上限人数|'. date('Y-m-d H:i:s').' done...';
                    $this->distribute_group_model->write_log($log);
                    continue;
                }
                if($rvalue['reward_count'] >= $rvalue['limit_count']){
                    $log  = 'inter_id:'.$rvalue['inter_id'].'奖励id：'.$rvalue['reward_id'].'已奖人数超出限制|'. date('Y-m-d H:i:s').' done...';
                    $this->distribute_group_model->write_log($log);
                    continue;
                }
                if(empty($rvalue['reward'])){
                    $log  = 'inter_id:'.$rvalue['inter_id'].'奖励id：'.$rvalue['reward_id'].'没有设置奖励规则|'. date('Y-m-d H:i:s').' : '.microtime(TRUE).' done...';
                    $this->distribute_group_model->write_log($log);
                    continue;
                }
                //先查询分组是否有效，然后查询组内人员 进行奖励记录
                $this->db->where(array(
                    'inter_id'=>$rvalue['inter_id'],
                    'group_id'=>$rvalue['group_id'],
                    'status'=>1,
                    'type'=>2,
                    'is_delete'=>0,
                    'start_time<='=>time(),
                    'end_time>'=>time()
                ));
                $groups = $this->db->get('distribute_group')->result_array();
                $is_can_run = false;//该奖励是否是周期结束一天后跑
                if(isset($groups[0])&&!empty($groups[0])){//分组有效 进行奖励操作
                    //查看奖励是否周期结束后一天，如果是按周，则周一跑，如果是按月，则1号跑，不是则不跑该奖励
                    if($groups[0]['check_date'] == 1){//周
                        $curweekday = date('w');
                        if($curweekday == 1){//周一
                            $is_can_run = true;
                        }
                    }elseif($groups[0]['check_date'] == 2){//按月计算 算出1号
                        if(date('d') == 1){//1号
                            $is_can_run = true;
                        }
                    }
                    if($is_can_run){//是在周期后一天跑
                        $log  = 'inter_id:'.$rvalue['inter_id'].' 奖励id：'.$rvalue['reward_id'].'开始奖励|'. date('Y-m-d H:i:s').' : '.microtime(TRUE).'|';
                        $this->distribute_group_model->write_log($log);
                        //上锁
                        $this->db->update('distribute_group_reward',array('locked'=>1),array('reward_id'=>$rvalue['reward_id']));
                        $res = $this->distribute_group_model->update_reward_member_record($rvalue['inter_id'],$rvalue,$groups[0]);
                    }else{
                        $log  = 'inter_id:'.$rvalue['inter_id'].' 奖励id：'.$rvalue['reward_id'].'|不在周期结束后一天时间运行|'. date('Y-m-d H:i:s');
                        $this->distribute_group_model->write_log($log);
                    }
                }else{
                    $log  = 'inter_id:'.$rvalue['inter_id'].' 奖励id：'.$rvalue['reward_id'].'所属分组已无效|'. date('Y-m-d H:i:s').' : '.microtime(TRUE).' done...';
                    $this->distribute_group_model->write_log($log);
                }
            }
        }
        $end = date('Y-m-d H:i:s').' : 奖励脚本结束...';
		$this->distribute_group_model->write_log($end);
        $redis->delete($lock_key);//解锁
        echo date('Y-m-d H:i');echo "|done...";die;
    }



}

