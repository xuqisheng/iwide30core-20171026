<?php
/*
 * 定时拉取公众号粉丝信息
 * author situguanchen  2016-11-28
 */
class Autorun extends MY_Controller {
	function __construct() {

		parent::__construct ();
		$this->debug = $this->input->get ( 'debug' );
		error_reporting ( 0 );
		if (! empty ( $this->debug )) {
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 1 );
        //ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
        }
		//$this->output->enable_profiler ( false );
		$this->load->library('MYLOG');
	}
   

    private function check_arrow(){//访问限制
        //var_dump($_SERVER['REMOTE_ADDR']);die;
        return true;
        $arrow_ip = array('10.25.168.86','10.25.3.85','10.46.74.165','10.25.1.106');//只允许服务器自动访问，不能手动
        if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
            exit('非法访问！');
        }
    }
    //将公众号表新增的数据定期更新好record表中
    private function update_publics_to_record(){
        $sql = "select inter_id from iwide_pull_fans_record  order by id desc limit 1";
        $res = $this->db->query($sql)->result_array();
        $last_inter_id = $res[0]['inter_id'];
        $sql = "insert ignore into iwide_pull_fans_record(`inter_id`,`public_name`,`add_time`) select inter_id,name,create_time from iwide_publics where status = 0 and inter_id > '{$last_inter_id}'";
        MYLOG::w('插入新数据sql:'.$sql, 'auto_pull_fans');
        $this->db->query($sql);
    }

    //传入inter_id 先改状态 在跑
    public function run_fans(){
        $this->check_arrow();
        $inter_id = $this->input->get('id',true);
        $sql = "update iwide_pull_fans_record set locked = 0,next_openid='',sub_locked = 0,sub_next_openid = '' where inter_id = '{$inter_id}'";
        var_dump($this->db->query($sql));
        echo 'done';
        die;
    }

    //处理拉粉 根据fans_sub_log表
    public function pull_fans_data(){
        $this->check_arrow();
        //每周一插入新数据
        if(date('i:s') < '00:30' && date('i:s') >= '00:00'){//每一个小时更一下
            MYLOG::w('每小时插入新数据', 'auto_pull_fans');
            $this->update_publics_to_record();
        }
        if(date('H:i:s') < '01:00:30' && date('H:i:s') >= '01:00:00'){//每天更一下
            MYLOG::w('每天更新数据', 'auto_pull_fans');
            $sql = "update iwide_pull_fans_record set locked = 0,next_openid = '' where  id > 600 and locked = 4";
            $this->db->query($sql);
        }
        if(date('w') == 1 && (date('H:i:s') < '01:00:30' && date('H:i:s') >= '01:00:00')){
            $time = date('Y-m-d',strtotime("-10 days"));//10天前的数据初始化一下 再拉一次
            $sql = "update iwide_pull_fans_record set locked = 0,next_openid = '' where  id > 600 and add_time >='{$time}'";
            $this->db->query($sql);
            $sql = "update iwide_pull_fans_record set sub_locked = 0,sub_next_openid = '' where  id > 600 and add_time >='{$time}'";
            $this->db->query($sql);
            MYLOG::w('每周一更新旧号', 'auto_pull_fans');
        }
        set_time_limit ( 0 );
        @ini_set('memory_limit','2048M');
       /* $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid=NEXT_OPENID";//批量获取openid 最多10000
        $batch_url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=ACCESS_TOKEN';//批量获取用户信息 每次最多100*/
        //查看公众号
        $this->db->where(array(
            'locked'=>1
        ));
       $publics = $this->db->get('pull_fans_record')->result_array();
        if(is_array($publics) && count($publics) >= 2) {
            MYLOG::w('目前有两个任务进行中，不处理|', 'auto_pull_fans');
            exit('目前有两个任务进行中');
        }
        if($this->input->get('id',true)){
            $this->db->where(array(
                'locked'=>0,
                'inter_id'=>$this->input->get('id',true),
            ));
        }else{
           $this->db->order_by('id', 'desc')->limit(1);//每次拿一条
           $this->db->where(array(
                'locked'=>0
            )); 
        }
        
        $worker = $this->db->get('pull_fans_record')->result_array();
        if(!empty($worker)){
            foreach($worker as $value){
                if($value['locked'] == 1){//说明还在运行中
                    MYLOG::w('inter_id'.$value['inter_id'].' 任务进行中|','auto_pull_fans');
                    exit('任务还在进行中！');
                }elseif($value['locked'] == 0){//当前无执行
                    MYLOG::w('inter_id：'.$value['inter_id'].' 任务开始|','auto_pull_fans');
                    $this->db->update('pull_fans_record',array('locked'=>1,'start_time'=>date('Y-m-d H:i:s')),array('inter_id'=>$value['inter_id']));
                    $this->load->helper ( 'common' );
                    $this->load->model ( 'wx/access_token_model' );
                    $access_token = $this->access_token_model->get_access_token ( $value['inter_id'] );
/*                    $access_token = 'qT2AXhraGTt0KfBg05d6wjOVbfQPmRgq1xymVPNELg2V00f8QqITePQuTPAnbNGkHN9EpZBN3UKtIyv2M1auP_uiwIkze17UDnkArbi23T5A0ShByd4OTmGV8PRbWJBgGNAcAFABIV';*/
                    if($access_token == 'error'){//目前还没有数据
                        $this->db->update('pull_fans_record',array('locked'=>3),array('inter_id'=>$value['inter_id']));
                        MYLOG::w('inter_id：'.$value['inter_id'].' 还没有上线|','auto_pull_fans');
                        exit('no data');
                    }
                    //查询next_openid 拼接url
                    if(empty($value['next_openid'])){
                        $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                    }elseif($value['next_openid'] != 'over'){
                        $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=".$value['next_openid'];
                    }
                    //根据token查所有的粉丝数据，返回批量openid 每次最多一万条
                    $openids = $this->doCurlGetRequest($token_url);
                    $openids = json_decode($openids,true);//数组
                    //var_dump($openids);die;
                   if (isset ( $openids ['errcode'] ) && ($openids ['errcode'] == '40001' || $openids ['errcode'] == '42001')){
                        MYLOG::w('第一次授权失败|'.json_encode($openids),'auto_pull_fans');
                        $access_token = $this->access_token_model->reflash_access_token ( $value['inter_id'] );//只刷新一次
                        if(empty($value['next_openid'])){
                            $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                        }elseif($value['next_openid'] != 'over'){
                            $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=".$value['next_openid'];
                        }
                        $openids = $this->doCurlGetRequest($token_url);
                        $openids = json_decode($openids,true);//数组
                    }
                    if(isset ( $openids ['errcode'] )){//第二次就推出了
                        if($openids ['errcode'] != '40001' || $openids ['errcode'] != '42001'){
                            $this->db->update('pull_fans_record',array('locked'=>4),array('inter_id'=>$value['inter_id']));
                        }else{
                            $this->db->update('pull_fans_record',array('locked'=>0),array('inter_id'=>$value['inter_id']));
                        }
                        MYLOG::w('第二次授权失败|'.json_encode($openids),'auto_pull_fans');
                        exit();
                    }else{
                        $now = date('Y-m-d H:i:s');
                        if(isset($openids['data']) && !empty($openids['data'])){
                            foreach($openids['data']['openid'] as $k=>$openid){
                                //查下关注表有没有记录 没有说明没有数据的  有就是有数据的了 不处理了
                                $sql = "select count(*) cc from iwide_fans_subs where inter_id = '{$value['inter_id']}' and openid = '{$openid}'";
                                $row = $this->db->query ( $sql )->row ()->cc;
                                if(empty($row)){//无记录
                                    $this->db->insert ( 'fans_sub_log', array (
                                        'source'      => -2,
                                        'openid'      => $openid,
                                        'event'       => 2,
                                        'inter_id'    => $value['inter_id'],
                                        'event_time'  => $now,
                                        'description' => '公众号拉取'
                                    ) );
                                    //usleep(10);
                                    $sql = "insert ignore into iwide_fans_subs(`event`,`event_time`,`source`,`description`,`openid`,`inter_id`,`cur_status`) values(2,'{$now}',-2,'公众号拉取','{$openid}','{$value['inter_id']}',1)";
                                    $this->db->query($sql);
                                    //记录openid
                                    usleep(10);
                                    $tmp[] = array('openid'=>$openid);
                                    if($k % 300 == 1){sleep(2);}
                                    if(count($tmp) >= 50 || ($k >= ($openids['count'] - 1))){//达到50条时或者已经循环完  请求用户信息
                                        $batch_url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$access_token}";
                                        $post_data['user_list'] = $tmp;
                                        $user_list = $this->doCurlPostRequest($batch_url,json_encode($post_data),1);
                                        $user_list = json_decode($user_list,true);

                                        if(isset($user_list['errcode']) &&  ($user_list ['errcode'] == '40001' || $user_list ['errcode'] == '42001')){//授权失败
                                            MYLOG::w('获取详细信息第一次授权失败，获取新token|'.json_encode($user_list),'auto_pull_fans');
                                            $access_token = $this->access_token_model->reflash_access_token ( $value['inter_id'] );//只刷新一次
                                            $batch_url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$access_token}";
                                            //$post_data['user_list'] = $tmp;
                                            $user_list = $this->doCurlPostRequest($batch_url,json_encode($post_data),1);
                                            $user_list = json_decode($user_list,true);
                                        }
                                        unset($tmp);
                                        //unset($post_data);
                                        if(!isset($user_list['errcode']) && isset($user_list['user_info_list']) && !empty($user_list['user_info_list'])){
                                            $comm_sql = "insert ignore into iwide_fans(`inter_id`,`openid`,`headimgurl`,`nickname`,`sex`,`province`,`city`,`unionid`,`privilege`,`subscribe_time`) values";
                                            $insert_sql = '';
                                            foreach($user_list['user_info_list'] as $uvalue){
                                                $stime = null;
                                                $unionid = isset($uvalue ['unionid']) ? $uvalue ['unionid'] : '';
                                                $privilege = isset($uvalue ['privilege']) ? json_encode($uvalue ['privilege']) : '';
                                                $nick_name = htmlspecialchars(addslashes($uvalue['nickname']));
                                                if(!empty($uvalue['subscribe_time'])){
                                                    $stime = date('Y-m-d H:i:s',$uvalue['subscribe_time']);
                                                }
                                                if(isset($uvalue['headimgurl'])){
                                                    $insert_sql = $comm_sql.' ("'.$value['inter_id'].'","'.$uvalue['openid'].'","'.$uvalue['headimgurl'].'","'.$nick_name.'","'.$uvalue['sex'].'","'.$uvalue['province'].'","'.$uvalue['city'].'","'.$unionid.'","'.$privilege.'","'.$stime.'");';

                                                }else{
                                                    $insert_sql = $comm_sql.' ("'.$value['inter_id'].'","'.$uvalue['openid'].'","","","","","","","","'.$stime.'");';
                                                }
                                                $this->db->query($insert_sql);
                                                usleep(10);
                                            }
                                            //$insert_sql = substr($insert_sql, 0, -1);
                                            unset($insert_sql);
                                            unset($user_list);
                                        }else{
                                            MYLOG::w('获取详细用户信息失败|'.json_encode($user_list).'|post信息'.json_encode($post_data),'auto_pull_fans');
                                            MYLOG::w('开始一条一条获取','auto_pull_fans');
                                            $info_sql = "insert ignore into iwide_fans(`inter_id`,`openid`,`headimgurl`,`nickname`,`sex`,`province`,`city`,`unionid`,`privilege`,`subscribe_time`) values";
                                            foreach($post_data['user_list'] as $pk=>$pv){
                                                $info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$pv['openid']}";
                                                //$post_data['user_list'] = $tmp;
                                                $user_info = $this->doCurlGetRequest($info_url);
                                                if(!empty($user_info)){
                                                    $user_info = json_decode($user_info,true);
                                                    if(isset($user_info['error'])){
                                                        MYLOG::w('openid:'.$pv['openid'].'单个获取有误。'.json_encode($user_info),'auto_pull_fans');
                                                        continue;
                                                    }
                                                    $stime = null;
                                                    $unionid = isset($user_info ['unionid']) ? $user_info ['unionid'] : '';
                                                    $privilege = isset($user_info ['privilege']) ? json_encode($user_info ['privilege']) : '';
                                                    $nick_name = htmlspecialchars(addslashes($user_info['nickname']));
                                                    if(!empty($user_info['subscribe_time'])){
                                                        $stime = date('Y-m-d H:i:s',$user_info['subscribe_time']);
                                                    }
                                                    if(isset($user_info['headimgurl'])){
                                                        $comm_info_sql = $info_sql.' ("'.$value['inter_id'].'","'.$user_info['openid'].'","'.$user_info['headimgurl'].'","'.$nick_name.'","'.$user_info['sex'].'","'.$user_info['province'].'","'.$user_info['city'].'","'.$unionid.'","'.$privilege.'","'.$stime.'");';

                                                    }else{
                                                        $comm_info_sql = $info_sql.' ("'.$value['inter_id'].'","'.$user_info['openid'].'","","","","","","","","'.$stime.'");';
                                                    }
                                                    $this->db->query($comm_info_sql);
                                                    usleep(10);
                                                }
                                            }
                                            MYLOG::w('结束获取','auto_pull_fans');
                                        }
                                    }
                                }
                            }
                        }
                        //解除锁定
                        MYLOG::w('inter_id:'.$value['inter_id'].' fans_subs:完成这次任务，最后一个openid：'.(!empty($openids['next_openid'])?$openids['next_openid']:'木有'),'auto_pull_fans');
                        if(!empty($openids['next_openid'])){
                            $this->db->update('pull_fans_record',array('locked'=>0,'end_time'=>date('Y-m-d H:i:s'),'worktimes'=>($value['worktimes']+1),'next_openid'=>$openids['next_openid']),array('inter_id'=>$value['inter_id']));
                        }else{
                            $this->db->update('pull_fans_record',array('locked'=>2,'end_time'=>date('Y-m-d H:i:s'),'worktimes'=>($value['worktimes']+1),'next_openid'=>'over'),array('inter_id'=>$value['inter_id']));
                        }
                    }
                }
            }
        }
    }


    //处理拉粉 根据fans表
    public function pull_fans_subs(){
        $this->check_arrow();
        set_time_limit ( 0 );
        @ini_set('memory_limit','1048M');
        //查看公众号
        $this->db->where(array(
            'sub_locked'=>1
        ));
        $publics = $this->db->get('pull_fans_record')->result_array();
        if(is_array($publics) && count($publics) >= 2) {
            MYLOG::w('目前有两个任务进行中，不处理|', 'auto_pull_fans');
            exit('目前有两个任务进行中');
        }
        $this->db->order_by('id', 'desc')->limit(1);//每次拿一条
        $this->db->where(array(
            'sub_locked'=>0,
            'locked != '=>3,
            'locked != '=>4,
        ));
        $worker = $this->db->get('pull_fans_record')->result_array();
        if(!empty($worker)){
            foreach($worker as $value){
                if($value['sub_locked'] == 1){//说明还在运行中
                    MYLOG::w('inter_id'.$value['inter_id'].' 任务进行中|','auto_pull_fans');
                    exit('任务还在进行中！');
                }elseif($value['sub_locked'] == 0){//当前无执行
                    MYLOG::w('inter_id：'.$value['inter_id'].' 任务开始|','auto_pull_fans');
                    $this->db->update('pull_fans_record',array('sub_locked'=>1,'start_time'=>date('Y-m-d H:i:s')),array('inter_id'=>$value['inter_id']));
                    $this->load->helper ( 'common' );
                    $this->load->model ( 'wx/access_token_model' );
                    $access_token = $this->access_token_model->get_access_token ( $value['inter_id'] );
                    //$access_token = '98zhD_51bhc8Rbp8skJ9zJu48ly0rr9nMDPjJ5ySKsidyIMR7XR46fV7dOhUQv4nQeO0qnkqQ0QDPBQKi_mWrpmL4tN4Jo3iWwT56F8T9ifsgNp7aVSaC9JQwmF6cZy5ALPbAFAQBF';
                    if($access_token == 'error'){//目前还没有数据
                        $this->db->update('pull_fans_record',array('sub_locked'=>3),array('inter_id'=>$value['inter_id']));
                        MYLOG::w('inter_id：'.$value['inter_id'].' 还没有上线|','auto_pull_fans');
                        exit('no data');
                    }
                    //查询next_openid 拼接url
                    if(empty($value['sub_next_openid'])){
                        $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                    }elseif($value['sub_next_openid'] != 'over'){
                        $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=".$value['sub_next_openid'];
                    }
                    //根据token查所有的粉丝数据，返回批量openid 每次最多一万条
                    $openids = $this->doCurlGetRequest($token_url);
                    $openids = json_decode($openids,true);//数组
                    //var_dump($openids);die;
                    if (isset ( $openids ['errcode'] ) && ($openids ['errcode'] == '40001' || $openids ['errcode'] == '42001')){
                        MYLOG::w('第一次授权失败|'.json_encode($openids),'auto_pull_fans');
                        $access_token = $this->access_token_model->reflash_access_token ( $value['inter_id'] );//只刷新一次
                        if(empty($value['next_openid'])){
                            $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                        }elseif($value['sub_next_openid'] != 'over'){
                            $token_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token."&next_openid=".$value['sub_next_openid'];
                        }
                        $openids = $this->doCurlGetRequest($token_url);
                        $openids = json_decode($openids,true);//数组
                    }
                    if(isset ( $openids ['errcode'] )){//第二次就推出了
                        if($openids ['errcode'] != '40001' || $openids ['errcode'] != '42001'){
                            $this->db->update('pull_fans_record',array('sub_locked'=>4),array('inter_id'=>$value['inter_id']));
                        }else{
                            $this->db->update('pull_fans_record',array('sub_locked'=>0),array('inter_id'=>$value['inter_id']));
                        }
                        MYLOG::w('第二次授权失败|'.json_encode($openids),'auto_pull_fans');
                        exit();
                    }else{
                        $now = date('Y-m-d H:i:s');
                        if(isset($openids['data']) && !empty($openids['data'])){
                            foreach($openids['data']['openid'] as $k=>$openid){
                                //查下subs表
                                $sql = "select * from iwide_fans where inter_id = '{$value['inter_id']}' and openid = '{$openid}'";
                                $row = $this->db->query ( $sql )->row_array();
                                if(empty($row)){//无记录
                                    $sql = "insert ignore into iwide_fans_subs(`event`,`event_time`,`source`,`description`,`openid`,`inter_id`,`cur_status`) values(2,'{$now}',-2,'公众号拉取','{$openid}','{$value['inter_id']}',1)";
                                    $this->db->query($sql);
                                    //记录openid
                                    $tmp[] = array('openid'=>$openid);
                                    if($k % 300 == 1){sleep(2);}
                                    if(count($tmp) >= 50 || ($k >= ($openids['count'] - 1))){//达到100条时或者已经循环完  请求用户信息
                                        $batch_url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$access_token}";
                                        $post_data['user_list'] = $tmp;
                                        $user_list = $this->doCurlPostRequest($batch_url,json_encode($post_data),1);
                                        $user_list = json_decode($user_list,true);

                                        if(isset($user_list['errcode']) &&  ($user_list ['errcode'] == '40001' || $user_list ['errcode'] == '42001')){//授权失败
                                            MYLOG::w('获取详细信息第一次授权失败，获取新token|'.json_encode($user_list),'auto_pull_fans');
                                            $access_token = $this->access_token_model->reflash_access_token ( $value['inter_id'] );//只刷新一次
                                            $batch_url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$access_token}";
                                            $user_list = $this->doCurlPostRequest($batch_url,json_encode($post_data),1);
                                            $user_list = json_decode($user_list,true);
                                        }
                                        unset($tmp);
                                        if(!isset($user_list['errcode']) && isset($user_list['user_info_list']) && !empty($user_list['user_info_list'])){
                                            $comm_sql = "insert ignore into iwide_fans(`inter_id`,`openid`,`headimgurl`,`nickname`,`sex`,`province`,`city`,`unionid`,`privilege`,`subscribe_time`) values";
                                            $insert_sql = '';
                                            foreach($user_list['user_info_list'] as $uvalue){
                                                $stime = null;
                                                $unionid = isset($uvalue ['unionid']) ? $uvalue ['unionid'] : '';
                                                $privilege = isset($uvalue ['privilege']) ? json_encode($uvalue ['privilege']) : '';
                                                $nick_name = htmlspecialchars(addslashes($uvalue['nickname']));
                                                if(!empty($uvalue['subscribe_time'])){
                                                    $stime = date('Y-m-d H:i:s',$uvalue['subscribe_time']);
                                                }
                                                if(isset($uvalue['headimgurl'])){
                                                    $insert_sql = $comm_sql.' ("'.$value['inter_id'].'","'.$uvalue['openid'].'","'.$uvalue['headimgurl'].'","'.$nick_name.'","'.$uvalue['sex'].'","'.$uvalue['province'].'","'.$uvalue['city'].'","'.$unionid.'","'.$privilege.'","'.$stime.'");';

                                                }else{
                                                    $insert_sql = $comm_sql.' ("'.$value['inter_id'].'","'.$uvalue['openid'].'","","","","","","","","'.$stime.'");';
                                                }
                                                $this->db->query($insert_sql);
                                                usleep(10);
                                            }
                                            unset($insert_sql);
                                            unset($user_list);
                                        }else{
                                              MYLOG::w('获取详细用户信息失败|'.json_encode($user_list).'|post信息'.json_encode($post_data),'auto_pull_fans');
                                            MYLOG::w('开始一条一条获取','auto_pull_fans');
                                            $info_sql = "insert ignore into iwide_fans(`inter_id`,`openid`,`headimgurl`,`nickname`,`sex`,`province`,`city`,`unionid`,`privilege`,`subscribe_time`) values";
                                            foreach($post_data['user_list'] as $pk=>$pv){
                                                $info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$pv['openid']}";
                                                //$post_data['user_list'] = $tmp;
                                                $user_info = $this->doCurlGetRequest($info_url);
                                                if(!empty($user_info)){
                                                    $user_info = json_decode($user_info,true);
                                                    if(isset($user_info['error'])){
                                                        MYLOG::w('openid:'.$pv['openid'].'单个获取有误。'.json_encode($user_info),'auto_pull_fans');
                                                        continue;
                                                    }
                                                    $stime = null;
                                                    $unionid = isset($user_info ['unionid']) ? $user_info ['unionid'] : '';
                                                    $privilege = isset($user_info ['privilege']) ? json_encode($user_info ['privilege']) : '';
                                                    $nick_name = htmlspecialchars(addslashes($user_info['nickname']));
                                                    if(!empty($user_info['subscribe_time'])){
                                                        $stime = date('Y-m-d H:i:s',$user_info['subscribe_time']);
                                                    }
                                                    if(isset($user_info['headimgurl'])){
                                                        $comm_info_sql = $info_sql.' ("'.$value['inter_id'].'","'.$user_info['openid'].'","'.$user_info['headimgurl'].'","'.$nick_name.'","'.$user_info['sex'].'","'.$user_info['province'].'","'.$user_info['city'].'","'.$unionid.'","'.$privilege.'","'.$stime.'");';

                                                    }else{
                                                        $comm_info_sql = $info_sql.' ("'.$value['inter_id'].'","'.$user_info['openid'].'","","","","","","","","'.$stime.'");';
                                                    }
                                                    $this->db->query($comm_info_sql);
                                                    usleep(10);
                                                }
                                            }
                                            MYLOG::w('结束获取','auto_pull_fans');
                                        }
                                    }
                                }
                            }
                        }
                        //解除锁定
                        MYLOG::w('inter_id:'.$value['inter_id'].'fans: 完成这次任务，最后一个openid:'.(!empty($openids['next_openid'])?$openids['next_openid']:'木有'),'auto_pull_fans');
                        if(!empty($openids['next_openid'])){
                            $this->db->update('pull_fans_record',array('sub_locked'=>0,'end_time'=>date('Y-m-d H:i:s'),'worktimes'=>($value['worktimes']+1),'sub_next_openid'=>$openids['next_openid']),array('inter_id'=>$value['inter_id']));
                        }else{
                            $this->db->update('pull_fans_record',array('sub_locked'=>2,'end_time'=>date('Y-m-d H:i:s'),'worktimes'=>($value['worktimes']+1),'sub_next_openid'=>'over'),array('inter_id'=>$value['inter_id']));
                        }
                    }
                }
            }
        }
    }

    /**
     * 封装curl的调用接口，get的请求方式
     * @param string 请求URL
     * @param array  请求参数值array(key=>value,...)
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    private function doCurlGetRequest($url, $data = array(), $timeout = 10){
        if($url == "" || $timeout <= 0){
            return false;
        }
        if($data != array()){
            $url = $url . '?' . http_build_query($data);
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);

        $res = curl_exec($con);
        curl_close($con);
        return $res;

    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array  扩展字段值
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    private function doCurlPostRequest($url, $requestString, $extra = array(), $timeout = 10){
        if($url == "" || $requestString == "" || $timeout <= 0){
            return false;
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);

        if(!empty ($extra) && is_array($extra)){
            $headers = array();
            foreach($extra as $opt => $value){
                if(strexists($opt, 'CURLOPT_')){
                    curl_setopt($con, constant($opt), $value);
                } elseif(is_numeric($opt)){
                    curl_setopt($con, $opt, $value);
                } else{
                    $headers [] = "{$opt}: {$value}";
                }
            }
            if(!empty ($headers)){
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
        curl_close($con);
        return $res;
    }


}