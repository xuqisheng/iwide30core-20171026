<?php

/**
 * 优惠劵到期通知
 * User: ibuki
 * Date: 16/7/26
 * Time: 下午7:07
 */

class Couponmsg extends MY_Controller {
    public $db_shard_config= array();
    public $current_inter_id= '';

    public function __construct()
    {
        parent::__construct();
    }

    public function testdbinfo(){
        $inter_id = $_GET['it'];
        //获取会员卡券列表
        $_url = INTER_PATH_URL."member/getuserinfo";
        $data = array('inter_id'=>$inter_id);
        $user_info = $this->doCurlPostRequest($_url,$data);
        var_dump($user_info);
    }

    public function testpackage(){
        $inter_id = 'a464919542';
        if($this->input->get('inter_id')) $inter_id = $this->input->get('inter_id');
        $data = array(
            'token'=>'ohQSEuJNffTncWBqbPqahcaXWGZ8',
            'inter_id'=>$inter_id,
            'openid'=>'ohQSEuJNffTncWBqbPqahcaXWGZ8',
        );
        $this->_write_log(json_encode($data),'testpackage');
        $_url = INTER_PATH_URL."package/dis_give";
        $resdata = $this->doCurlPostRequest($_url,$data);
        echo json_encode($resdata);
    }

    /**
     * 运行日志记录
     * @param String $content
     */
    protected function _write_log( $content ,$filename ='' ) {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'member'. DS. 'coupon'. DS;
        if(!empty($filename)){
            $path .= $filename.DS ;
        }
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] Task '". $content. "' starting...";
        fwrite($fp, $content);
        fclose($fp);
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
                '10.46.74.165',
            );
            $client_ip= $this->input->ip_address();
            if( in_array($client_ip, $ip_whitelist) ){
                return TRUE;

            } else {
                $msg= $this->action. ' 拒绝非法IP执行任务！';
                $this->_write_log($msg);
                return die($msg);
            }

        } else {
            return TRUE;
        }
    }

    public function get_card_test(){
        $this->_write_log(__FUNCTION__);
        $this->load->model('member/member_related_model','mr_model');
        $this->_write_log(json_encode($this->input->get()),'get_card_test');
        $flag = $this->input->get('flag');
        $inter_id = $this->input->get('inter_id');
        $scene = $this->input->get('scene');
        $send = $this->input->get('send');
        $of = $this->input->get('of');
        if(empty($of)) $of=0;
        $lm = $this->input->get('lm');
        if(empty($lm)) $lm=1000;

        if(empty($inter_id)){
            echo 'inter_id is null';exit;
        }
        $card_id = $this->input->get('card_id');
        if(empty($card_id)){
            echo 'card_id is null';exit;
        }
        $card_count = $this->input->get('count');
        $wlike=array();
        $_wlike=$this->input->get('sc');
        if(!empty($_wlike)) $wlike=explode('|',$_wlike);
        $where=!empty($wlike)?$wlike:array();
        if($flag!='999'){
            if(empty($where)){
                echo '缺少条件';exit;
            }
        }

        //会员信息，领取券数量
        $user_info=$this->mr_model->get_user_list($where,$inter_id,$flag,$card_id,$of,$lm);
        $this->_write_log(json_encode($user_info),'get_card_test');

        //卡券信息
        $card_info = $this->mr_model->get_card_info($inter_id,$card_id);
        $this->_write_log(json_encode($card_info),'get_card_info');
        if(empty($card_info)){
            echo '卡券信息不存在';exit;
        }

        $lv=0;
        if(!empty($user_info) && is_array($user_info)){
            var_dump($user_info);
            foreach ($user_info as $k => $vo){
                $member_info_id=$vo['member_info_id'];
                $count=isset($vo['count'])?$vo['count']:0;
                $cc=floatval($card_count)-floatval($count);
                if($cc > 0){
                    //增加时间判断逻辑
                    $use_time_end = 0;
                    if($card_info['use_time_end_model']=='y'){
                        $use_time_end = time() + (( 3600*24 ) * $card_info['use_time_end_day']);
                    }elseif ($card_info['use_time_end_model']=='g') {
                        $use_time_end = $card_info['use_time_end'];
                    }
                    $res = false;
                    $coupon_code = ''; //券码
                    $_coupon_code = $this->mr_model->_shard_db()->select('id,code')->where('isset',1)->get('code_depot')->row_array(); //券码
                    if(!empty($_coupon_code)){
                        $coupon_code = $_coupon_code['code'];
                    }
                    $unique_code = $vo['inter_id'] . $coupon_code; //券码唯一标识
                    for($i=1;$i<=$cc;$i++){
                        $membercard = array(
                            'card_id' => $card_info['card_id'],
                            'inter_id' => $vo['inter_id'],
                            'open_id' => $vo['open_id'],
                            'member_id' => $vo['member_id'],
                            'member_info_id' => $member_info_id,
                            'card_type' => $card_info['card_type'],
                            'is_f' => $card_info['is_f'],
                            'is_online' => $card_info['is_online'],
                            'receive_module' => 'vip',
                            'receive_scene' => !empty($scene)?$scene:$card_info['title'],
                            'receive_time' => time(),
                            'page_config' => $card_info['page_config'],
                            'expire_time' => $use_time_end,
                            'remark' => $card_info['remark'],
                            'createtime' => time(),
                            'origin_member_info_id' =>$member_info_id,
                            'consume_code' => trim($this->get_guid(), "{}") ,
                            'friend_member_info_id' => 0,
                            'is_given_by_friend'=>$card_info['can_give_friend'],
                            'coupon_code' => $coupon_code,
                            'unique_code' => $unique_code,
                            'isset' => 2
                        );
                        $res = $this->mr_model->insert_card($membercard);
                        if(!$res) {
                            echo $vo['name'].'领取卡劵'.$card_info['title'].'失败';
                            echo '<br>';exit;
                        }
                        $lv++;
                        $_num=$card_info['card_stock'];
                        $ress = $this->mr_model->_card_stock($inter_id,$_num,$card_info['card_id']);
                        if(!$ress) {
                            echo $card_info['title'].'卡劵核销库存失败'.$card_info['card_id'];
                            echo '<br>';exit;
                        }
                    }
                    var_dump($membercard);
                    if(!empty($vo['telephone'])){
                        echo '手机号:'.$vo['telephone'].'-ok';
                        echo '<br/>';
                    }elseif(!empty($vo['cellphone'])){
                        echo '手机号:'.$vo['cellphone'].'-ok';
                        echo '<br/>';
                    }else{
                        echo '昵称:'.$vo['nickname'].'-ok';
                        echo '<br/>';
                    }

                    $card_info['name'] = $vo['name'];
                    $card_info['count'] = $card_count;
                    $card_info['curtime'] = time();
                    if($res && $send=='1') $this->_send_tmp($inter_id,$vo['open_id'],$card_info);
                }
            }
        }
        echo 'ok: '.$lv.'条数据';
        echo '<br/>';
    }

    /**
     * 手动发送优惠券
     */
    public function send_card(){
        ini_set('memory_limit',-1); //无内存限制
        set_time_limit(0); //无时间限制
        $iwide_hotel_orders = array();
        include_once APPPATH.'iwide_hotel_orders.php';

        $data = $this->input->get();
        MYLOG::w(@json_encode($data),'membervip/debug-log','send-card');

        $this->load->model('member/member_related_model','mr_model');
        if(empty($data['flag'])) {
            echo "flag is null \n";
            exit(0);
        }

        if(empty($data['inter_id'])) {
            echo "inter_id is null \n";
            exit(0);
        }

        if(empty($data['card_id'])) {
            echo "card_id is null \n";
            exit(0);
        }

        $inter_id = $data['inter_id'];
        $flag = $data['flag'];
        $card_id = $data['card_id'];
        $scene = !empty($data['scene'])?$data['scene']:'会员手动发券';
        $send = !empty($data['send'])?$data['send']:0;
        $of = !empty($data['of'])?$data['of']:0;
        $lm = !empty($data['lm'])?$data['lm']:100;
        $card_count = !empty($data['count'])?$data['count']:0;
        $subscribe = !empty($data['subscribe'])?$data['subscribe']:0;
        $wlike=array();
        if(!empty($data['sc'])){
            $_wlike = $data['sc'];
            $wlike=explode('|',$_wlike);
        }

        $where = $wlike;
        if($flag != '999' && $subscribe != 1){
            if(empty($where)){
                echo "缺少条件 \n";
                exit(0);
            }
        }

        $fans_count = 0;
        if($subscribe == 1){
            $_fans = $this->mr_model->db->query("SELECT * FROM (select * FROM (select * from iwide_fans_sub_log WHERE inter_id = '{$inter_id}' ORDER by openid DESC,event_time DESC) a GROUP BY openid) b WHERE event = 2")->result_array();
            $fans_count = count($_fans);
            MYLOG::w(@json_encode(array('res'=>$_fans,'count'=>$fans_count)),'membervip/debug-log','send-card-fans1');
            if(empty($_fans)){
                echo "Can not find subscribe fans \n";
                exit(0);
            }

            $fans = array();
            foreach ($_fans as $vv){
                $fans[$vv['openid']] = $vv;
                $where[] = $vv['openid'];
            }

            if(empty($where)){
                echo "Can not find openids \n";
                exit(0);
            }
        }

        //会员信息，领取券数量
        $user_info = $this->mr_model->get_user_list($where,$inter_id,$flag,$card_id,$of,$lm);
        $member_count = count($user_info);
        echo "fans_count: {$fans_count}  |  member_count: {$member_count} \n";
        MYLOG::w(@json_encode($user_info),'membervip/debug-log','send-card-users');
        if(empty($user_info)){
            echo "Can not find member information \n";
            exit(0);
        }

        //卡券信息
        $card_info = $this->mr_model->get_card_info($inter_id,$card_id);
        MYLOG::w(@json_encode($card_info),'membervip/debug-log','send-card-info');
        if(empty($card_info)){
            echo "卡券信息不存在 \n";
            exit(0);
        }

        $lv=0;
        $card_url = INTER_PATH_URL.'intercard/receive'; //领取卡劵
        $Total = count($user_info);
        $scount = 0;
        $fcount = 0;
        $ignore = 0;
        foreach ($user_info as $k => $vo){
            $member_info_id = $vo['member_info_id'];
            $count = isset($vo['count'])?$vo['count']:0;
            $cc = floatval($card_count) - floatval($count);
            if($cc > 0){
                $res = false;
                $number = 0;
                for($i=1; $i <= $cc; $i++){
                    $card_data = array(
                        'token'=>'',
                        'inter_id'=>$inter_id,
                        'openid'=>$vo['open_id'],
                        'card_id'=>$card_id,
                        'member_info_id'=>$member_info_id,
                        'uu_code'=>md5(uniqid($card_id.$member_info_id.$vo['open_id'])).microtime(true),
                        'module'=>'vip',
                        'scene'=>$scene,
                        'membernum'=>$vo['membership_number']
                    );
                    $result = $this->doCurlPostRequest($card_url,$card_data);
                    $info = @json_encode($result);
                    $param = @json_encode($card_data);
                    MYLOG::w("result: {$info} | url: {$card_url} | data: {$param}",'membervip/debug-log','send-card-docurl');
                    if(!isset($result['data']) OR (isset($result['err']) && $result['err'] > 0)) {
                        $fcount++;
                        MYLOG::w("Total: {$Total} | member_info_id: {$member_info_id} | send_count: {$cc} | count: {$number} | success_count: {$scount} | fail_count: {$fcount} | ignore: {$ignore}",'membervip/debug-log','send-card-fail');
                        $res = false;
                        $msg = !empty($result['msg'])?"{$result['msg']}，member_info_id: {$member_info_id}":"领取失败，member_info_id: {$member_info_id}";
                        echo $msg."\n";
                        continue;
                    }else{
                        $scount++;
                        $number++;
                        MYLOG::w("Total: {$Total} | member_info_id: {$member_info_id} | send_count: {$cc} | count: {$number} | success_count: {$scount} | fail_count: {$fcount} | ignore: {$ignore}",'membervip/debug-log','send-card-ok');
                        $res = true;
                        echo "领取成功，member_info_id: {$member_info_id} \n";
                    }

                    $lv++;
                    $_num = $card_info['card_stock'];
                    $ress = $this->mr_model->_card_stock($inter_id,$_num,$card_id);
                    MYLOG::w("result: {$ress} | number: {$_num} | card_id: {$card_id}",'membervip/debug-log','send-card-stock');
                    if(!$ress) {
                        echo "卡劵核销库存失败，member_info_id：{$member_info_id}、card_id：{$card_id}、num：{$_num} \n";
                    }
                }

                if($res && $send=='1') {
                    $card_info['name'] = $vo['name'];
                    $card_info['count'] = $card_count;
                    $card_info['curtime'] = time();
                    $this->_send_tmp($inter_id,$vo['open_id'],$card_info);
                }
            }else{
                $ignore++;
                MYLOG::w("Total: {$Total} | member_info_id: {$member_info_id} | send_count: {$cc} | count: {$number} | success_count: {$scount} | fail_count: {$fcount} | ignore: {$ignore}",'membervip/debug-log','send-card-ignore');
                echo "member_info_id: {$member_info_id} Already owned card_id: {$card_id} | {$card_count} \n";
                continue;
            }
        }
    }

    public function send_card_test(){
        $this->_write_log(__FUNCTION__);
        $this->load->model('member/member_related_model');
        $open_id = $_GET['openid'];
        $inter_id = $_GET['inter_id'];
        $card_id = $_GET['card_id'];
        $number = $_GET['number'];
        $where['openid'] = $open_id;
        $where['inter_id'] = $inter_id;
        $user_info=$this->member_related_model->send_user_list($where);
        $this->_write_log(json_encode($user_info),'user_info');
        $lv=0;
        if(!empty($user_info) && is_array($user_info)){
            $member_info_id=$user_info['member_info_id'];
            $count=$this->member_related_model->send_card_count($inter_id,$member_info_id,$card_id);
            $this->_write_log($count,'send_card_count');
            if($count < $number){//328--2376019//329 -- 2376018
                $card_info=$this->member_related_model->send_card_info($inter_id,$card_id);
                $this->_write_log(json_encode($card_info),'send_card_info');
                if(empty($card_info)) return;
                //增加时间判断逻辑
                if($card_info['use_time_end_model']=='y'){
                    $use_time_end = time() + (( 3600*24 ) * $card_info['use_time_end_day']);
                }elseif ($card_info['use_time_end_model']=='g') {
                    $use_time_end = $card_info['use_time_end'];
                }
                for($i=1;$i<=$number;$i++){
                    $membercard = array(
                        'card_id' => $card_info['card_id'],
                        'inter_id' => $inter_id,
                        'open_id' => $user_info['open_id'],
                        'member_id' => $user_info['member_id'],
                        'member_info_id' => $member_info_id,
                        'card_type' => $card_info['card_type'],
                        'is_f' => 'f',
                        'is_online' => 1,
                        'receive_module' => 'vip',
                        'receive_scene' => '秒杀赠券',
                        'receive_time' => time(),
                        'page_config' => '',
                        'expire_time' => $use_time_end,
                        'remark' => $card_info['remark'],
                        'createtime' => time(),
                        'origin_member_info_id' =>$member_info_id,
                        'consume_code' => trim($this->get_guid(), "{}") ,
                        'friend_member_info_id' => 0
                    );
                    $res = $this->member_related_model->insert_card($membercard);
                    if(!$res) {
                        echo $user_info['name'].'领取卡劵'.$card_info['title'].'失败';
                        echo '<br>';exit;
                    }
                    $lv++;
                    if(floatval($card_info['card_stock'])>$number)
                    $ress = $this->member_related_model->send_card_stock($inter_id,$card_info['card_id']);
                    if(!$ress) {
                        echo $card_info['title'].'卡劵核销库存失败'.$card_info['card_id'];
                        echo '<br>';exit;
                    }
                }
            }
        }
        echo 'ok: '.$lv.'条数据';
    }

    //获取不重复的随机字符串guid
    public function get_guid(){
        if (function_exists('com_create_guid')){
            return trim( com_create_guid() ,'{}');
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return trim( $uuid , '{}' );
        }
    }

    public function _send_tmp($inter_id='',$open_id='',$data){
        if(empty($data) || empty($inter_id) || empty($open_id)){
            echo '参数不全'."\r\n";exit;
        }
        echo '-----开始发送-----'."\r\n";
        $this->load->model('member/Message_wxtemp_model','wxtemp_model');
        $wxtemp_model = $this->wxtemp_model;
        //获取已配置发送模版消息的公众号的模版信息
        $type = $wxtemp_model::CREDITED_NOTICE;
        $this->load->model('member/member_related_model');
        echo '-----'.$type.'-----'."\r\n";
        $temp = $this->member_related_model->member_card_temp($inter_id,$type);
        $this->_write_log(json_encode($temp),'_send_tmp');
        if($temp){
            echo '-----star-----'."\r\n";
            $res = $wxtemp_model->send_template_coupon_msg($inter_id,$open_id,$type,$data);
            $this->_write_log('发送结果send_template_coupon_msg result --> '.$res);
            if($res){
                $return = json_decode($res,true);
                if($return['code'] == '1001'){
                    $result = true;
                    echo '-----result: 发送成功 -----'."\r\n";
                }
            }
            echo '-----end-----'."\r\n";
        }
        echo '执行完成'."\r\n";
    }

    /**
     * 定时扫描并将会员即将到期的优惠劵通知用户
     * 每10分钟执行/处理量100个
     */
    public function package_maturity_notice(){
        $result = false;
        $this->_check_access();    //拒绝非法IP执行任务
        $this->_write_log(__FUNCTION__);
        $limit = 100;  //每次处理100个
        $this->load->model('member/member_related_model');
        $card_info= $this->member_related_model->get_expired_coupon($limit);
        $this->_write_log('优惠劵数量 count --> '.count($card_info).' result --> '.json_encode($card_info));
        $this->_write_log('member_related_model result --> '.count($card_info));
        if(!empty($card_info) && is_array($card_info)){
            foreach ($card_info as $key => $val){
                $this->load->model('member/Message_wxtemp_model','wxtemp_model');
                $wxtemp_model = $this->wxtemp_model;
                //获取已配置发送模版消息的公众号的模版信息
                $type = $wxtemp_model::PACKAGE_EXPIRE;
                $temp = $this->member_related_model->member_card_temp($val['inter_id'],$type);
                $this->_write_log('模版信息数量 result --> '.$temp);
                if($temp){
                    echo '-----star-----'."\r\n";
                    $res = $wxtemp_model->send_template_coupon_msg($val['inter_id'],$val['open_id'],$type,$val);
                    $this->_write_log('发送结果send_template_coupon_msg result --> '.$res);
                    if($res){
                        $return = json_decode($res,true);
                        if($return['code'] == '1001'){
                            $result = true;
                            echo '-----result: 发送成功 -----'."\r\n";
                            $res_member = $this->member_related_model->save_member_card($val['inter_id'],$val['open_id'],$val['card_id'],'t');
                            $this->_write_log('改变发送状态save_member_card result --> '.$res_member);
                        }
                    }
                    echo '-----end-----'."\r\n";
                }
//                echo $result === true ? 'SUCCESS': 'FAIL';
            }
        }
//        echo $result === true ? 'SUCCESS': 'FAIL';
        echo '执行完成  '.count($card_info)."\r\n";
    }

    /**
     * 定时扫描并将已经成功邀请好友的信息通知用户
     * 每10分钟执行/处理量100个
     */
    public function invitate_notice(){
        $this->load->library("MYLOG");
        MYLOG::w(json_encode(array('res'=>__FUNCTION__,'param'=>'invitate_notice')),'membervip/couponmsg/invitate_notice','star');
//        $this->_check_access();    //拒绝非法IP执行任务
        $limit = 100;  //每次处理100个
        $this->load->model('membervip/common/Public_model','pm');
        $_where = array('business_model'=>2,'message_type'=>6,'expiretime >='=>time(),'is_success !='=>'t');
        $message_queue = $this->pm->get_list($_where,'template_message_queue','*',$limit);
        MYLOG::w(json_encode(array('res'=>$message_queue,'param'=>$_where)),'membervip/couponmsg/invitate_notice','template_message_queue');
        if(!empty($message_queue) && is_array($message_queue)){
            foreach ($message_queue as $key => $val){
                $content = explode('.',$val['content']);
                $member_info_id = !empty($content[0])?$content[0]:0;
                $lvl_id = !empty($content[1])?$content[1]:0;
                $username = '';
                if(!empty($member_info_id)){
                    $user = $this->pm->get_info(array('member_info_id'=>$member_info_id),'member_info','name,nickname');
                    MYLOG::w(json_encode(array('res'=>$user,'member_info_id'=>$member_info_id)),'membervip/couponmsg/invitate_notice','member_info');
                    if(!empty($user)) $username = !empty($user['name'])?$user['name']:$user['nickname'];
                }

                $lvl_name = '';
                if(!empty($lvl_id)){
                    $member_lvl = $this->pm->get_info(array('member_lvl_id'=>$lvl_id),'member_lvl','lvl_name');
                    MYLOG::w(json_encode(array('res'=>$member_lvl,'member_lvl_id'=>$lvl_id)),'membervip/couponmsg/invitate_notice','member_lvl');
                    if(!empty($member_lvl)) $lvl_name = !empty($member_lvl['lvl_name'])?$member_lvl['lvl_name']:'';
                }

                $this->load->model('member/Message_wxtemp_model','wxtemp_model');
                $wxtemp_model = $this->wxtemp_model;
                //获取已配置发送模版消息的公众号的模版信息
                $type = $wxtemp_model::SEND_INVITATE_NOTICE;
                $temp = $this->pm->template_count_by_type($val['inter_id'],$type);
                MYLOG::w(json_encode(array('res'=>$temp,'inter_id'=>$val['inter_id'],'type'=>$type)),'membervip/couponmsg/invitate_notice','template_message_queue');
                if($temp){
                    echo '-----star-----'."\r\n";
                    $val['username'] = $username;
                    $val['lvl_name'] = $lvl_name;
                    $res = $wxtemp_model->send_template_coupon_msg($val['inter_id'],$val['openid'],$type,$val);
                    MYLOG::w(json_encode(array('res'=>$res,'inter_id'=>$val['inter_id'],'openid'=>$val['openid'],'type'=>$type,'val'=>$val)),'membervip/couponmsg/invitate_notice','send_template_coupon_msg');
                    $send_count = floatval($val['send_count']) + 1;
                    $params = array('pk'=>'id','id'=>$val['id'],'inter_id'=>$val['inter_id'],'openid'=>$val['openid'],'is_success !='=>'t','business_model'=>2,'message_type'=>6);
                    $save_data = array('is_success'=>'f','send_count'=>$send_count);
                    if($res){
                        $return = json_decode($res,true);
                        if(!empty($return['code']) && $return['code'] == '1001'){
                            $save_data['is_success'] = 't';
                            echo '-----result: Sending successful -----'."\r\n";
                        }else{
                            echo '-----result: Failed to send -----'."\r\n";
                        }
                    }else{
                        echo '-----result: Failed to send -----'."\r\n";
                    }
                    $update_result = $this->pm->update_save($params,$save_data,'template_message_queue');
                    MYLOG::w(json_encode(array('res'=>$update_result,'params'=>$params,'save_data'=>$save_data)),'membervip/couponmsg/invitate_notice','save_template_message_queue');
                    echo '-----end-----'."\r\n";
                }
            }
        }
        echo 'Execution completed'."\r\n";
    }


    /**
     * 定时扫描并将员工／业主审核的信息通知用户
     * 每10分钟执行/处理量100个
     */
    public function send_member_audit_notice(){
        $this->load->library("MYLOG");
        MYLOG::w(json_encode(array('res'=>__FUNCTION__,'param'=>'send_member_audit_notice')),'membervip/couponmsg/send_member_audit_notice','star');
//        $this->_check_access();    //拒绝非法IP执行任务
        $limit = 100;  //每次处理100个
        $this->load->model('membervip/common/Public_model','pm');
        $_where = array('business_model'=>4,'message_type'=>2,'expiretime >='=>time(),'is_success !='=>'t');
        $message_queue = $this->pm->get_list($_where,'template_message_queue','*',$limit);
        MYLOG::w(json_encode(array('res'=>$message_queue,'param'=>$_where)),'membervip/couponmsg/send_member_audit_notice','template_message_queue');
        if(!empty($message_queue) && is_array($message_queue)){
            foreach ($message_queue as $key => $val){
                $content = json_decode($val['content'],true);
                $this->load->model('member/Message_wxtemp_model','wxtemp_model');
                $wxtemp_model = $this->wxtemp_model;
                //获取已配置发送模版消息的公众号的模版信息
                $type = $wxtemp_model::AUDIT_RESULTS;
                $temp = $this->pm->template_count_by_type($val['inter_id'],$type);
                MYLOG::w(json_encode(array('res'=>$temp,'inter_id'=>$val['inter_id'],'type'=>$type)),'membervip/couponmsg/send_member_audit_notice','template_message_queue');
                if($temp){
                    echo '-----star-----'."\r\n";
                    $res = $wxtemp_model->send_template_coupon_msg($val['inter_id'],$val['openid'],$type,$content);
                    MYLOG::w(json_encode(array('res'=>$res,'inter_id'=>$val['inter_id'],'openid'=>$val['openid'],'type'=>$type,'val'=>$val)),'membervip/couponmsg/send_member_audit_notice','send_template_coupon_msg');
                    $send_count = floatval($val['send_count']) + 1;
                    $params = array('pk'=>'id','id'=>$val['id'],'inter_id'=>$val['inter_id'],'openid'=>$val['openid'],'is_success !='=>'t','business_model'=>4,'message_type'=>2);
                    $save_data = array('is_success'=>'f','send_count'=>$send_count);
                    if($res){
                        $return = json_decode($res,true);
                        if(!empty($return['code']) && $return['code'] == '1001'){
                            $save_data['is_success'] = 't';
                            echo '-----result: Sending successful -----'."\r\n";
                        }else{
                            echo '-----result: Failed to send -----'."\r\n";
                        }
                    }else{
                        echo '-----result: Failed to send -----'."\r\n";
                    }
                    $update_result = $this->pm->update_save($params,$save_data,'template_message_queue');
                    MYLOG::w(json_encode(array('res'=>$update_result,'params'=>$params,'save_data'=>$save_data)),'membervip/couponmsg/send_member_audit_notice','save_template_message_queue');
                    echo '-----end-----'."\r\n";
                }
            }
        }
        echo 'Execution completed'."\r\n";
    }


    /**
     * 刷新邀请好友活动
     * 每次处理500条数据
     * 每个自然年的01-01 00:00:00 时间点执行
     */
    public function refresh_invite_settings(){
        $this->load->model('membervip/common/Public_model','pm');
        //邀请活动配置
        $invite_settings = $this->pm->get_list(array('is_active'=>'t','expiretime <'=>time()),'invite_settings','*',500);
        MYLOG::w(json_encode(array('res'=>$invite_settings,'params'=>array('is_active'=>'t','expiretime <'=>time()))),'membervip/couponmsg/refresh_invite_settings','get_list_invite_settings');
        if(empty($invite_settings)){
            echo 'No refresh required!'."\r\n";exit;
        }

        $this->pm->_shard_db()->trans_begin(); //开启事务
        try{
            foreach ($invite_settings as $item){
                //更新活动状态为完成
                $_where = array('id'=>$item['id'],'inter_id'=>$item['inter_id'],'is_active'=>'t');
                $settings_update = $this->pm->update_save($_where,array('is_active'=>'c'),'invite_settings');
                if(!$settings_update) throw new Exception('update: refresh failed! id:'.$item['id']);

                $effective_time = intval($item['effective_time']);
                $expiretime = strtotime(date('Y-12-31 23:59:59'));
                if($effective_time > 1){
                    $c = $effective_time - 1;
                    $expire = strtotime('+'.$c.' years');
                    $expiretime = strtotime(date('Y-12-31 23:59:59',$expire));
                }
                $save_data = $item;
                $save_data['expiretime'] = $expiretime;
                unset($save_data['id']);
                unset($save_data['lastupdatetime']);
                $add_result = $this->pm->add_data($save_data,'invite_settings');
                MYLOG::w(json_encode(array('res'=>$add_result,'save_data'=>$save_data)),'membervip/couponmsg/refresh_invite_settings','add_invite_settings');
                if(!$add_result){
                    throw new Exception('add: refresh failed! id:'.$item['id']);
                }

                //权益配置
                $level_equity = $this->pm->get_info(array('inter_id'=>$item['inter_id'],'act_id'=>$item['id']),'invite_level_equity');
                MYLOG::w(json_encode(array('res'=>$level_equity,'params'=>array('inter_id'=>$item['inter_id'],'act_id'=>$item['id']))),'membervip/couponmsg/refresh_invite_settings','get_info_invite_level_equity');
                if(!empty($level_equity)){
                    $save_data = $level_equity;
                    unset($save_data['id']);
                    unset($save_data['lastupdatetime']);
                    $save_data['act_id'] = $add_result;
                    $save_data['createtime'] = time();
                    $add_result = $this->pm->add_data($save_data,'invite_level_equity');
                    MYLOG::w(json_encode(array('res'=>$add_result,'save_data'=>$save_data)),'membervip/couponmsg/refresh_invite_settings','add_invite_level_equity');
                    if(!$add_result){
                        throw new Exception('refresh invite_level_equity failed! id:'.$level_equity['id'].' act_id:'.$item['id']);
                    }
                }

                MYLOG::w(json_encode(array('res'=>'refresh succeeded! id:'.$item['id'],'old_data'=>$item)),'membervip/couponmsg/refresh_invite_settings','refresh_succeeded');

                echo 'refresh succeeded! id:'.$item['id']."\r\n";
            }
            $this->pm->_shard_db()->trans_commit();// 事务提交
            echo 'Execution completed'."\r\n";
        }catch (Exception $e){
            $this->pm->_shard_db()->trans_rollback(); //回滚事务
            echo 'Exception: '.$e->getMessage()."\r\n";
        }
    }


    /**
     * 回收赠送中逾期的优惠券
     */
    public function giving_coupon_recycle(){
        $this->_check_access();    //拒绝非法IP执行任务
        $this->load->model('member/member_related_model');
        $this->load->library('MYLOG');
        $expiredList = $this->member_related_model->get_giving_expired_coupon();
        $mcIds = array_column($expiredList, 'member_card_id');
        MYLOG::w("Rollback giving coupon : ".json_encode($mcIds),'front/membervip/coupon','_rollback_giving_process');

        foreach($expiredList as $member_card){

            $result = $this->member_related_model->rollback_giving_coupon($member_card);
            if($result <= 0){
                $content = "退回失败";
            }elseif($result > 0 ){
                $content = "退回成功";
                /*更新记录*/
                $insertRs = $this->member_related_model->rollback_giving_coupon_record($member_card);
                if($insertRs){
                    $content .= ' | 记录插入成功';
                }else{
                    $content .= ' | 记录插入失败';
                }

            }
            MYLOG::w("Card Id : " .$member_card['member_card_id']." Result : ".$content,'front/membervip/coupon','_rollback_giving_result');

        }

        MYLOG::w("Rollback giving coupon Finished : ".count($mcIds),'front/membervip/coupon','_rollback_giving_process');
    }

    public function givepackage(){
        $this->load->model('membervip/common/Public_model','pum');
        $this->load->library('MYLOG');
        $inter_id = $this->input->get('id');
        $openid = $this->input->get('openid');
        if(empty($inter_id)) {
            echo 'inter_id is null';exit;
        }
        if(empty($openid)) {
            echo 'openid is null';exit;
        }
        $deposit_card_id = $this->input->get('dcid');
        if(empty($deposit_card_id)) {
            echo 'deposit_card_id is null';exit;
        }
        $where = array('deposit_card_id'=>$deposit_card_id,'is_active'=>'t');
        $deposit_card = $this->pum->_shard_db()->where($where)->get('deposit_card')->row_array();
        if(empty($deposit_card)){
            echo '不存在储值规则';exit;
        }

        MYLOG::w("deposit_card : " .json_encode($deposit_card).' param: '.json_encode($where),'front/membervip/couponmsg','deposit_card');

        if($deposit_card['is_package']=='t'){
            $packge_url = INTER_PATH_URL.'package/give';
            $package_data = array(
                'token'=>'',
                'inter_id'=>$inter_id,
                'openid'=>$openid,
                'uu_code'=>uniqid(),
                'package_id'=>$deposit_card['package_id'],
            );
            $package_deposit = $this->doCurlPostRequest( $packge_url , $package_data );
            MYLOG::w("package_deposit : " .json_encode($package_deposit).' param: '.json_encode($package_data),'front/membervip/couponmsg','package_give');
            if($package_deposit['err']=='0'){
                echo '礼包发送成功：package_id：'.$deposit_card['package_id'];exit;
            }else{
                echo '礼包发送失败：package_id：'.$deposit_card['package_id'];exit;
            }

        }else{
            echo '储值规则不可赠送礼包';exit;
        }
    }

    
    public function givepackage1(){
         $this->load->model('membervip/common/Public_model','pum');
        $this->load->library('MYLOG');
        $inter_id = $this->input->get('id');
        $openid = $this->input->get('openid');
        $mid = $this->input->get('mid');
        $this->load->model('membervip/front/Member_model','m_model');
        if(empty($inter_id)) {
            echo 'inter_id is null';exit;
        }
        if(empty($mid)){
            if(empty($openid)) {
                echo 'openid is null';exit;
            }
            $vip_user = $this->m_model->get_user_info($inter_id,$openid);
        }else{
            $vip_user = $this->m_model->get_member_info($mid);
        }
        $card_rule_id = $this->input->get('crid');
        $package_id = $this->input->get('paid');
        if(empty($package_id)) {
            echo 'package_id is null';exit;
        }
        $where = array('package_id'=>$package_id,'status'=>'1');
        $package = $this->pum->_shard_db()->where($where)->get('package')->row_array();
        if(empty($package)){
            echo '不存在礼包';exit;
        }

        MYLOG::w("package : " .json_encode($package).' param: '.json_encode($where),'front/membervip/couponmsg','deposit_card');


        if($package['status']=='1'){
            $packge_url = INTER_PATH_URL.'package/give';
            $package_data = array(
                'token'=>'',
                'inter_id'=>$inter_id,
                'openid'=>$openid,
                'uu_code'=>uniqid(),
                'package_id'=>$package_id
            );
            if (!empty($card_rule_id))
            $package_data['card_rule_id']=$card_rule_id;
            $package_res = $this->doCurlPostRequest( $packge_url , $package_data );
            MYLOG::w("package_deposit : " .json_encode($package_res).' param: '.json_encode($package_data),'front/membervip/couponmsg','package_give');
            $telephone = !empty($vip_user['telephone'])?$vip_user['telephone']:$vip_user['cellphone'];
            $msg = '姓名：'.$vip_user['name'].' -- 昵称:'.$vip_user['nickname'].' -- 手机号:'.$telephone;
            if($package_res['err']=='0'){
                echo '礼包发送成功 -- '.$msg;exit;
            }else{
                echo '礼包发送失败 -- '.$msg;exit;
            }

        }else{
            echo '礼包未启用';exit;
        }
    }

    /**
     * 生成随机字符串
     * @param int $length 要生成的随机字符串长度
     * @return string
     */
    public function get_unique_code($length = 6){
        $string = range(0, 9);
        $code = '';
        for ($i = 0; $i < $length; $i++){
            $code .= $string[mt_rand()%count($string)];
        }
        return $code;
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
        $this->api_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * 把请求/返回记录记入文件
     * @param String $content
     * @param string $type
     */
    protected function api_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'admin'. DS. 'apimember'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
}