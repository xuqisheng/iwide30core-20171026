<?php

/**
 * 优惠劵到期通知
 * User: ibuki
 * Date: 16/7/26
 * Time: 下午7:07
 */

class TimingTask extends MY_Controller {
    public $db_shard_config= array();
    public $current_inter_id= '';

    public function __construct()
    {
        parent::__construct();
    }

    public function sendPackageToWx(){
        $this->load->helper('common_helper');
        $args = get_args();
        MYLOG::w(@json_encode(array('args'=>$this->args,'client_ip'=>$this->input->ip_address())),'front/membervip/debug-log','send-package');
        $this->load->model('membervip/common/Public_model','common_model');
        $inter_id = !empty($args['id'])?$args['id']:'';
        $package_id = !empty($args['package_id'])?$args['package_id']:'';
        $send = !empty($args['send'])?$args['send']:0;
        $level_config = $this->common_model->get_admin_member_lvl($inter_id,array('member_lvl_id','lvl_name','is_default'));
        MYLOG::w(array('level_config'=>$level_config,'id'=>$inter_id),'front/membervip/debug-log','send-package');
        if(empty($level_config)){
            echo "LevelConfig is Null\r\n";exit(0);
        }
        $first_level_conf = reset($level_config);
        $level_id = $first_level_conf['member_lvl_id'];
        $this->load->model('membervip/admin/Member_model','member_model');
        $level_infos = $this->member_model->get_user_by_lvl($inter_id,$level_id);
        MYLOG::w(array('level_infos'=>$level_infos,'id'=>$inter_id,'level_id'=>$level_id),'front/membervip/debug-log','send-package');
        if(empty($level_infos)){
            echo "FirstLevelUsers is Null\r\n";exit(0);
        }

        $this->load->model('membervip/admin/Package_model','package_model');
        $package_id = intval($package_id);
        $package_info = $this->package_model->get_packages_elements_list($inter_id,$package_id);
        if(empty($package_info)){
            echo "Package is Null\r\n";exit(0);
        }

        $fcount = 0;
        $scount = 0;
        $Total = count($level_infos);
        $send_count = 0;
        foreach ($level_infos as $vo){
            $packge_url = INTER_PATH_URL.'package/receive';
            $package_data = array(
                'token'=>'',
                'inter_id'=>$inter_id,
                'openid'=>$vo['open_id'],
                'uu_code'=>uniqid(),
                'package_id'=>$package_id,
            );
            $package_result = $this->doCurlPostRequest($packge_url,$package_data);
            $send_count++;
            MYLOG::w(array('result'=>$package_result,'url'=>$packge_url,'post_data'=>$package_data),'front/membervip/debug-log','send-package');
            if(!isset($package_result['err']) && $package_result['err'] > 0) {
                $fcount++;
                MYLOG::w("Total: {$Total} | {$send_count} | open_id: {$vo['open_id']} | success_count: {$scount} | fail_count: {$fcount}",'front/membervip/debug-log','send-package-fail');
                $msg = !empty($result['msg'])?"{$result['msg']}，open_id: {$vo['open_id']}":"领取失败，open_id: {$vo['open_id']}";
                echo $msg."\n";
                continue;
            }elseif(isset($package_result['err']) && $package_result['err'] == '0'){
                $scount++;
                MYLOG::w("Total: {$Total} | {$send_count} | open_id: {$vo['open_id']} | success_count: {$scount} | fail_count: {$fcount}",'front/membervip/debug-log','send-package-ok');

                if($send=='1') {
                    $card = !empty($package_info['card'])?reset($package_info['card']):array();
                    $card_info['name'] = !empty($card['title'])?$card['title']:'';
                    $card_info['count'] = !empty($card['count'])?$card['count']:5;
                    $card_info['curtime'] = time();
                    $this->load->model('member/Message_wxtemp_model','wxtemp_model');
                    $wxtemp_model = $this->wxtemp_model;
                    $type = $wxtemp_model::CREDITED_NOTICE;
                    $res = $wxtemp_model->send_template_coupon_msg($inter_id,$vo['open_id'],$type,$card_info);
                    if($res){
                        $return = json_decode($res,true);
                        if($return['code'] == '1001'){
                            echo "发送模版消息成功 open_id: {$vo['open_id']} \r\n";
                        }else{
                            echo "发送模版消息失败 open_id: {$vo['open_id']} \r\n";
                        }
                    }else{
                        echo "发送模版消息失败 open_id: {$vo['open_id']} \r\n";
                    }
                }

                echo "领取成功，open_id: {$vo['open_id']} \r\n";
            }
        }

        MYLOG::w("Total: {$Total} | {$send_count} | success_count: {$scount} | fail_count: {$fcount}",'front/membervip/debug-log','send-card-ok');
        echo "执行完成 | Total: {$Total} | {$send_count} | success_count: {$scount} | fail_count: {$fcount} \r\n";
    }

    public function test(){
        $this->load->model('membervip/common/Public_model','common_model');
        $send_arr = $this->common_model->do_xlsx_parser('http://test1.lostsk.com/public/a490782373.xlsx');

        if(empty($send_arr[0]['Content'])){
            echo "file is null \r\n";
            exit(0);
        }
        $Total = 0;
        $scount = 0;
        $fcount = 0;
        $sTcount = 0;
        $inter_id = 'a490782373';
        $card_url = INTER_PATH_URL.'intercard/receive'; //领取卡劵
        foreach ($send_arr as $item){
            if(!empty($item['Content'])){
                $Total = ($Total + count($item['Content'])) - 1;
                foreach ($item['Content'] as $key => $vo){
                    if($key > 1){
                        $sTcount++;
                        $open_id = !empty($vo[0])?$vo[0]:'';
                        $card_id = !empty($vo[4])?$vo[4]:'';
                        $send_count = !empty($vo[5])?$vo[5]:'';
                        $scene = !empty($vo[2])?$vo[2]:'2017-07-04手动发券';
                        for ($i = 1; $i <= $send_count; $i++){
                            $card_data = array(
                                'token'=>'',
                                'inter_id'=>$inter_id,
                                'openid'=>$open_id,
                                'card_id'=>$card_id,
                                'uu_code'=>md5(uniqid($card_id.$open_id)).microtime(true),
                                'module'=>'vip',
                                'scene'=>$scene,
                            );
                            $result = $this->doCurlPostRequest($card_url,$card_data);
                            $info = @json_encode($result);
                            $param = @json_encode($card_data);
                            MYLOG::w("result: {$info} | url: {$card_url} | data: {$param}",'membervip/debug-log','send-card-docurl');
                            if(!isset($result['data']) OR (isset($result['err']) && $result['err'] > 0)) {
                                $fcount++;
                                MYLOG::w("Total: {$Total} | {$send_count} | open_id: {$open_id} | success_count: {$scount} | fail_count: {$fcount}",'membervip/debug-log','send-card-fail');
                                $msg = !empty($result['msg'])?"{$result['msg']}，open_id: {$open_id}":"领取失败，open_id: {$open_id}";
                                echo $msg."\n";
                                continue;
                            }else{
                                $scount++;
                                MYLOG::w("Total: {$Total} | {$send_count} | open_id: {$open_id} | success_count: {$scount} | fail_count: {$fcount}",'membervip/debug-log','send-card-ok');
                                echo "领取成功，open_id: {$open_id} \n";
                            }
                        }
                    }
                }
            }
        }
        MYLOG::w("Total: {$Total} | {$sTcount} | success_count: {$scount} | fail_count: {$fcount}",'membervip/debug-log','send-card-ok');
        echo "执行完成 | Total: {$Total} | {$sTcount} | success_count: {$scount} | fail_count: {$fcount} \r\n";
    }

    protected function redis_setting(){
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $config = array(
                'task'=> array(
                    'socket_type'   => 'tcp',
                    'password'      => NULL,
                    'timeout'       => 5,
                    'cachedb'       => 14,
                    'host'          => 'redis02',
                    'port'          => 6381
                ),
            );
        } else {
            $config = array(
                'task'=> array(
                    'socket_type'   => 'tcp',
                    'password'      => NULL,
                    'timeout'       => 5,
                    'cachedb'       => 2,
                    'host'          => '120.27.132.97',
                    'port'          => 16379
                ),
            );
        }
        return $config;
    }

    /**
     * 此方法用于检测任务的可否执行。计划任务分来3类：
     * 1 类是可以重复执行的，不加任何限制；
     * 2 类是绝对不能重复执行的，要在执行之前加一个 remote_ip 的判断，只允许某一个服务器触发，其他ip一律不认
     * 3 类是 担心会漏发（这个特许授权服务器ip挂掉了），必须在其他服务器加以保障的，跟第1类的区别是，第1类可以少发无实质性影响
     * @param boolean $result TRUE可执行 false不可执行
     */
    protected function _check_access()
    {
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $ip_whitelist= array(
                //'10.46.75.203', //test 1
                '10.25.168.86', //redis01
                '10.25.3.85',  //redis02
                '10.27.232.209', //预发布
                '120.27.132.198',  //crontab
				'10.46.74.165', //crontab SLB
            );
            $client_ip= $this->input->ip_address();
            if( in_array($client_ip, $ip_whitelist) ){
                return TRUE;

            } else {
                $msg= $this->action. ' 拒绝非法IP执行任务！';
                $this->task_write_log($msg);
                return die($msg);
            }

        } else {
            return TRUE;
        }
    }


    protected function get_vip_redis($select = 'task') {
//        $redis_config = $this->redis_setting();
//        $config = $redis_config[$select];
//        if(!is_array($config)) {
//            return false;
//        }
//        $redis = new Redis();

        $this->config->load('redis', true, true);
        $redis_config = $this->config->item('redis');
        $redis = new Redis();
        if ( ! $redis->connect($redis_config['host'], $redis_config['port'], $redis_config['timeout'])) {
            return false;
        }
//
//        $success = $redis->connect($config['host'], $config['port'], $config['timeout']);
//        if(!$success) {
//            return false;
//        }
        return $redis;
    }

    /**
     * 注册分销绩效
     */
    public function distribution_send(){
        $this->_check_access();    //拒绝非法IP执行任务

        /**锁判断**/
        $redis = $this->get_vip_redis();

        $lockKey = 'Vip:Task_Distribution_Send';
        $lockFlag = $redis->setNx($lockKey,'lock');
        if(!$lockFlag){
            $this->task_write_log(" Redis Set Lock Failed ! "," System Error");
            die("Redis Set Lock Failed !");
        }
        /**end锁**/

        $this->load->model('membervip/admin/Distribution_model');
        $this->load->model ( 'distribute/Idistribute_model','idistribute' );
        $records = $this->Distribution_model->get_distribution_list('','reg');
        $i =0;
        foreach($records as $record){
            $i++;
			if($record['sn'] == '') continue;
            $this->Distribution_model->_shard_db()->trans_begin ();
            $record_id = $record['record_id'];
            $inter_id = $record['inter_id'];
            $open_id = $record['open_id'];
            $update_num = $this->Distribution_model->update_distribution_record($record_id,$inter_id,$open_id);
            if($update_num == 1){
                /*分销绩效发送*/
                $distribute_arr = array(
                    'inter_id'=>$inter_id,
                    'hotel_id'=>0,
                    'grade_openid'=>$open_id,
                    'grade_table'=>'iwide_member4_reg',
                    'grade_id'=>$record_id,
                    'grade_id_name'=>'注册分销',
                    'grade_total'=> $record['reward'],
                    'grade_amount'=> $record['reward'],
//                        'grade_amount_rate'=>$deposit_data['distribution_money'],
                    'grade_rate_type'=>0,
                    'status'=>1,
                    'remark'=>$record['record_title'],
                    'product'=>$record['record_title'],
                    'order_status'=>'已完成',
                    'order_id'=>$record['sn'],
                );

                if(isset($record['type']) && $record['type'] == 'reg'){
                    $distribute_arr[ 'product'] = "会员注册奖励";
                    $distribute_arr[ 'order_amount'] = 0 ;
                }


                MYLOG::w("Distribution Record Insert :".json_encode($record)." | Dis_record Result : ".$record_id ." apply data : " .json_encode($distribute_arr),'distribution_record/task');
                $distribute_result = $this->idistribute->create_dist( $distribute_arr );
                if($distribute_result){
                    MYLOG::w("Success Distribution Record :".json_encode($record)." | Member record id : ".$record_id ." apply data : " .json_encode($distribute_arr),'distribution_record/grant');
                   $this->Distribution_model->_shard_db()->trans_commit();// 事务提交
                }else{
                    MYLOG::w("Time Task Failed ! Distribution Record :".json_encode($record)." | Member record id : ".$record_id ." apply data : " .json_encode($distribute_arr),'distribution_record/grant');
                    $this->Distribution_model->_shard_db()->trans_rollback();// 事务回滚
                }
            }else{
                MYLOG::w("Time Task Failed ! Distribution Record :".json_encode($record)." | Result Failed ",'distribution_record/failed');
                $this->Distribution_model->_shard_db()->trans_rollback();// 事务回滚
            }
        }
        $redis->del($lockKey);
        echo "Success !\n";
        MYLOG::w("--------------------Distribution Task Finished !-------------------" ,'distribution_record/task');

    }

    /**
     * 间夜升级
     */
    public function night_upgrade(){
        $this->_check_access();    //拒绝非法IP执行任务
        MYLOG::w(__FUNCTION__,'night_upgrade/task','start');
        /**锁判断**/
        $redis = $this->get_vip_redis();

        $lockKey = 'Vip:Task_Night_Upgrade';
        $lockFlag = $redis->setNx($lockKey,'lock');
        if(!$lockFlag){
            $this->task_write_log(" Redis Set Lock Failed ! "," System Error");
            die("Redis Set Lock Failed !");
        }
        /**end锁**/
        try{
            $this->load->model('membervip/common/Public_model','p_model');
            $where = [
                'upgrade_time >'=>'2000-01-01 00:00:00',
                'expiretime >'=>'2000-01-01 00:00:00',
                'keep_lvl >'=>0,
                'prev_expire_time >'=>'2000-01-01 00:00:00',
                'is_active'=>'t'
            ];
            $member_info = $this->p_model->_shard_db()->where($where)->get('member_info')->result_array();
            if(empty($member_info)) { //用戶信息不存在
                $redis->del($lockKey);
                echo "member_info is empty!\n";exit;
            }
            $doCurl = INTER_PATH_URL.'member/check_night_upgrade';
            foreach ($member_info as $item){
                $Request = [
                    'inter_id'=>$item['inter_id'],
                    'data'=>$item
                ];
                $res = $this->doCurlPostRequest($doCurl,$Request);
                if(isset($res['err']) && $res['err']==0){
                    MYLOG::w("Success Night_Upgrade result :".json_encode($res),'night_upgrade/task','success');
                }
                MYLOG::w("Time Task Failed ! Night_Upgrade result :".json_encode($res),'night_upgrade/task','success');
            }
            echo "Success !\n";
            $redis->del($lockKey);
        }catch (Exception $e){
            $redis->del($lockKey);
            $msg[] = [
                $e->getMessage(),
                $e->getCode(),
                $e->getFile(),
                $e->getLine()
            ];
            $msg = @json_encode($msg);
            MYLOG::w("Night_Upgrade Task Exception !----msg:{$msg}" ,'night_upgrade/task');
            echo "Failed !\n";
        }
    }

    public function send_reg_notice(){
        $this->_check_access();    //拒绝非法IP执行任务
        $this->load->model('membervip/common/Wxtemp_model','wxtemp');
        $return = $this->wxtemp->send_template_message(false,12);
        MYLOG::w("send_reg_notice | {$return}",'membervip/debug-log');
        echo "{$return}\n";exit(0);
    }


    /**
     * 手动发送优惠券
     */
    public function send_card(){
        ini_set('memory_limit',-1); //无内存限制
        set_time_limit(0); //无时间限制
        $file_path = APPPATH.'openids.csv';
        if(!file_exists($file_path)) {
            echo "cannot find file \n";
            exit(0);
        }
        $file = fopen($file_path,'r');
        $lv = 0;
        $openid_list = array();
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            if($lv != 0) {
                $openid_list[] = end($data);
            }
            $lv++;
        }

        MYLOG::w(@json_encode($openid_list),'membervip/debug-log','send-card');

        $this->load->model('member/member_related_model','mr_model');
        $get_data = $this->input->get();
        if(empty($get_data['inter_id'])) {
            echo "inter_id is null \n";
            exit(0);
        }

        if(empty($get_data['card_id'])) {
            echo "card_id is null \n";
            exit(0);
        }

        $inter_id = $get_data['inter_id'];
        $card_id = $get_data['card_id'];
        $scene = !empty($get_data['scene'])?$get_data['scene']:'会员手动发券';

        //卡券信息
        $card_info = $this->mr_model->get_card_info($inter_id,$card_id);
        MYLOG::w(@json_encode($card_info),'membervip/debug-log','send-card-info');
        if(empty($card_info)){
            echo "卡券信息不存在 \n";
            exit(0);
        }

        $lv=0;
        $card_url = INTER_PATH_URL.'intercard/receive'; //领取卡劵
        $Total = count($openid_list);
        $scount = 0;
        $fcount = 0;
        foreach ($openid_list as $open_id){
            $card_data = array(
                'token'=>'',
                'inter_id'=>$inter_id,
                'openid'=>$open_id,
                'card_id'=>$card_id,
                'uu_code'=>md5(uniqid($card_id.$open_id)).microtime(true),
                'module'=>'vip',
                'scene'=>$scene,
            );
            $result = $this->doCurlPostRequest($card_url,$card_data);
            $info = @json_encode($result);
            $param = @json_encode($card_data);
            MYLOG::w("result: {$info} | url: {$card_url} | data: {$param}",'membervip/debug-log','send-card-docurl');
            if(!isset($result['data']) OR (isset($result['err']) && $result['err'] > 0)) {
                $fcount++;
                MYLOG::w("Total: {$Total} | success_count: {$scount} | fail_count: {$fcount}",'membervip/debug-log','send-card-fail');
                $msg = !empty($result['msg'])?"{$result['msg']}，open_id: {$open_id}":"领取失败，open_id: {$open_id}";
                echo $msg."\n";
                continue;
            }else{
                $scount++;
                MYLOG::w("Total: {$Total} | open_id: {$open_id} | success_count: {$scount} | fail_count: {$fcount}",'membervip/debug-log','send-card-ok');
                echo "领取成功，open_id: {$open_id} \n";
            }

            $lv++;
            $_num = $card_info['card_stock'];
            $ress = $this->mr_model->_card_stock($inter_id,$_num,$card_id);
            MYLOG::w("result: {$ress} | number: {$_num} | card_id: {$card_id}",'membervip/debug-log','send-card-stock');
            if(!$ress) {
                echo "卡劵核销库存失败，open_id: {$open_id}、card_id：{$card_id}、num：{$_num} \n";
            }
        }
    }

    public function welfaretask(){
        ini_set('memory_limit',-1); //无内存限制
        set_time_limit(0); //无时间限制
        $this->load->model('membervip/common/Membertask_logic','task_logic');
        $res = $this->task_logic->comply_task_event();
        echo @json_encode($res)."\r\n";
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 5) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->task_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * 把请求/返回记录记入文件
     * @param String $content
     * @param string $type
     */
    protected function task_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d'). '.txt';
        $path= APPPATH. 'logs'. DS. 'task'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= "[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . " | ". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
}