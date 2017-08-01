<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户中心
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Invitate extends MY_Front_Mapi{
    protected $vip_user = array();
    protected $user_id = 0;
    protected $view_conf = array();
    protected $assign_data = array();
    protected $_token = '';
    public function __construct(){
        parent::__construct();
        $this->_token = $this->get_Token();
        $this->load->library("MYLOG");
        $this->load->model('membervip/common/Public_model','pm');
        $this->load->model('membervip/front/Member_model','m_model');
        $this->vip_user = $this->m_model->get_user_info($this->inter_id,$this->openid);
    }

    //微信带参二维码
    public function subqrcode(){
        $subscribe_url = $this->session->tempdata($this->inter_id.$this->openid.'invitate_subscribe');
        $this->load->helper('phpqrcode');
        if(empty($subscribe_url)){
            $this->load->model('wx/access_token_model');
            $this->load->helper('common');
            $memberid = $this->input->get('mid');
            $access_token = $this->access_token_model->get_access_token($this->inter_id);
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
            // 临时码
            $qrcode = '{"expire_seconds": 86400,"action_name": "QR_SCENE","action_info": {"scene": {"scene_id": '.$memberid.'}}}';
            $output = json_decode(doCurlPostRequest($url,$qrcode),true);
            if(isset($output['url'])){
                $this->session->set_tempdata($this->inter_id.$this->openid.'invitate_subscribe',$output['url'],86400);
                QRcode::png($output['url'],false,'L',6,0,true);
            }
        }else{
            QRcode::png($subscribe_url,false,'L',6,0,true);
        }
    }

    //发起邀请事务
    public function start(){
        $lvlcode = $this->input->post('code');
        $channel = $this->input->post('channel');
        MYLOG::w(json_encode(array($this->input->post())),'membervip/api/invitate','start_post');
        if(empty($lvlcode)) $this->_ajaxReturn('很抱歉，邀请失败，请刷新后试试！');
        //邀请活动配置
        $invite_settings = $this->pm->get_info(array('inter_id'=>$this->inter_id,'is_active'=>'t'),'invite_settings');
        MYLOG::w(json_encode(array('data'=>$invite_settings,'where'=>array('inter_id'=>$this->inter_id,'is_active'=>'t'))),'membervip/api/invitate','start_invite_settings');
        if(empty($invite_settings)) $this->_ajaxReturn('活动不存在，请联系管理员'); //邀请活动配置不存在

        //平台等级配置
        $member_lvl = $this->pm->get_field_by_level_config($this->inter_id,'member_lvl_id,lvl_name');
        MYLOG::w(json_encode(array('data'=>$member_lvl,'inter_id'=>$this->inter_id)),'membervip/api/invitate','start_member_lvl');
        if(empty($member_lvl)) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //没有配置平台等级

        if(empty($member_lvl[$this->vip_user['member_lvl_id']])) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //匹配平台等级失败

        $level_equity = $this->pm->get_info(array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']),'invite_level_equity');
        MYLOG::w(json_encode(array('data'=>$level_equity,'where'=>array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']))),'membervip/api/invitate','start_level_equity');
        if(empty($level_equity)) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //邀请权益不存在

        $hold_lvl_group = json_decode($level_equity['hold_lvl_group'],true);
        if(empty($hold_lvl_group[$this->vip_user['member_lvl_id']])) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //邀请权益匹配不成功

        $lvl_group_arr = $hold_lvl_group[$this->vip_user['member_lvl_id']];
        $lvl_id = 0;
        foreach ($lvl_group_arr as $k=>$c){
            if($lvlcode==md5($k)) $lvl_id = $k;
        }
        MYLOG::w(json_encode(array('lvl_group_arr'=>$lvl_group_arr,'lvlcode'=>$lvlcode,'lvl_id'=>$lvl_id,'member_lvl'=>$member_lvl)),'membervip/api/invitate','lvl_group_arr');

        if(empty($hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]) || $hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]==0) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //邀请等级匹配不成功或者可邀请次数为0

        if(empty($member_lvl[$lvl_id])) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //匹配邀请等级失败

        $invited_code = sha1($this->vip_user['member_info_id'].'.'.$this->vip_user['member_lvl_id'].'.'.$lvl_id.'.'.$invite_settings['id']);
        $where = array('code'=>$invited_code);
        $invited_record = $this->pm->get_info($where,'member_invited_record');
        MYLOG::w(json_encode(array('data'=>$invited_record,'where'=>$where)),'membervip/api/invitate','start_invited_record');
        if(!empty($invited_record)){
            $check_key_value = array(
                'inter_id'=>$this->inter_id,
                'act_id'=>$invite_settings['id'],
                'lvl_id'=>$lvl_id,
                'member_info_id'=>$this->vip_user['member_info_id'],
                'member_lvl_id'=>$this->vip_user['member_lvl_id'],
            );

            foreach ($check_key_value as $k=>$v){
                if($invited_record[$k]!=$v) $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员'); //验证不通过
            }

            if($invited_record['count']<=0) $this->_ajaxReturn('您的'.$member_lvl[$lvl_id].'邀请资格已经用完');
        }

        $redis_code = $this->session->tempdata($this->vip_user['member_info_id'].$lvl_id.$invite_settings['id'].'invitate_code');
        MYLOG::w(json_encode(array('inter_id'=>$this->inter_id,'member_info_id'=>$this->vip_user['member_info_id'],'code'=>$redis_code)),'membervip/api/invitate','start_redis_code');
        if(!empty($redis_code)) $this->_ajaxReturn('邀请成功!',$redis_code,1);

        $lvl_count = $hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id];
        if(!empty($invited_record)) {
            $lvl_count = $invited_record['count'];
        }

        $invited_lvl_code = sha1($this->vip_user['member_info_id'].'.'.$this->vip_user['member_lvl_id'].'.'.$lvl_id.'.'.$lvl_count.'.'.$invite_settings['id']);
        $where = array(
            'act_id'=>$invite_settings['id'],
            'inter_id'=>$this->inter_id,
            'invited_mid'=>$this->vip_user['member_info_id'],
            'invited_mid_lvl'=>$this->vip_user['member_lvl_id'],
            'invited_lvl'=>$lvl_id,
            'invited_code'=>$invited_lvl_code
        );
        $invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //邀请者记录
        MYLOG::w(json_encode(array('res'=>$invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'invited_lvl_record');

        $this->pm->_shard_db(true)->trans_begin(); //开启事务
        $srand = $this->vip_user['member_info_id'].time();
        $code = $this->pm->randCode(16,$srand); //获取唯一随机码
        try{
            $content = $this->vip_user['member_info_id'].'.'.$lvl_id.'.'.$invite_settings['id'].'.'.$channel; //邀请者的邀请信息
            $save_initiate = array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id'],'content'=>$content,'type'=>3,'code'=>$code,'createtime'=>time(),'expiretime'=>strtotime('+48 hours'));
            if(!empty($invited_record)) {
                $invited_count = intval($hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]);
                if($invited_count<=0) $this->_ajaxReturn('您的'.$member_lvl[$lvl_id].'邀请资格已用完'); //可邀请次数为0
                $initiate_add = $this->pm->add_data($save_initiate,'member_initiate'); //添加邀请凭证记录
                MYLOG::w(json_encode(array('inter_id'=>$this->inter_id,'member_info_id'=>$this->vip_user['member_info_id'],'res'=>$initiate_add)),'membervip/api/invitate','start_initiate_add');
                if(!$initiate_add) throw new Exception();
            }else{
                $initiate_add = $this->pm->add_data($save_initiate,'member_initiate'); //添加邀请记录
                MYLOG::w(json_encode(array('inter_id'=>$this->inter_id,'member_info_id'=>$this->vip_user['member_info_id'],'res'=>$initiate_add)),'membervip/api/invitate','start_initiate_add');
                if(!$initiate_add) throw new Exception();
                $count = intval($hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]);
                $save_invited_record = array(
                    'inter_id'=>$this->inter_id,
                    'act_id'=>$invite_settings['id'],
                    'lvl_id'=>$lvl_id,
                    'member_info_id'=>$this->vip_user['member_info_id'],
                    'member_lvl_id'=>$this->vip_user['member_lvl_id'],
                    'count'=>$count,
                    'code'=>$invited_code,
                    'createtime'=>time()
                );
                $invited_record_add = $this->pm->add_data($save_invited_record,'member_invited_record'); //添加会员邀请等级权限数量记录
                MYLOG::w(json_encode(array('inter_id'=>$this->inter_id,'member_info_id'=>$this->vip_user['member_info_id'],'res'=>$invited_record_add)),'membervip/api/invitate','start_invited_record_add');
                if(!$invited_record_add) throw new Exception();
            }

            if(empty($invited_lvl_record)){
                $save_lvl_record = array(
                    'inter_id'=>$this->inter_id,
                    'act_id'=>$invite_settings['id'],
                    'invited_mid'=>$this->vip_user['member_info_id'],
                    'invited_mid_lvl'=>$this->vip_user['member_lvl_id'],
                    'invited_lvl'=>$lvl_id,
                    'invited_code'=>$invited_lvl_code,
                    'invited_count'=>$lvl_count,
                    'createtime'=>time()
                );
                $invited_lvl_record_add = $this->pm->add_data($save_lvl_record,'invited_lvl_record'); //添加邀请者记录记录
                MYLOG::w(json_encode(array('res'=>$invited_lvl_record_add,'params'=>$save_lvl_record)),'front/membervip/invitate', 'invited_lvl_record_add');
                if(!$invited_lvl_record_add) throw new Exception();
            }
            $this->pm->_shard_db(true)->trans_commit();// 事务提交
        }catch (Exception $e){
            $this->pm->_shard_db(true)->trans_rollback(); //回滚事务
            $this->_ajaxReturn('很抱歉，邀请失败，请联系管理员');
        }

        $this->session->set_tempdata($this->vip_user['member_info_id'].$lvl_id.$invite_settings['id'].'invitate_code',$code,300);
        $this->_ajaxReturn('邀请成功!',$code,1);
    }

    //注册领取事务
    public function reg_receive_execute(){
        $post_data = $this->input->post();
        MYLOG::w(json_encode($post_data),'front/membervip/invitate', 'reg_receive_post');

        $code = $post_data['code'];
        $check_receive = $this->check_receive($code);
        MYLOG::w(json_encode(array('check_receive'=>$check_receive,'code'=>$code)),'front/membervip/invitate', 'check_receive');

        if(!$check_receive['status'] || floatval($check_receive['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $check_receive['msg'];
            $this->_ajaxReturn($check_receive['msg'],$check_receive['url'],$check_receive['status']);
        }

        if(empty($check_receive['data'])) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),0);
        }
        $content = $check_receive['data']['content'];
        $invite_settings = $check_receive['data']['invite_settings'];
        $member_info = $check_receive['data']['member_info'];
        $member_lvl = $check_receive['data']['member_lvl'];
        $invited_record = $check_receive['data']['invited_record'];
        $member_initiate = $check_receive['data']['member_initiate'];
        $invite_user_lvl = $member_lvl[$content[1]]; //邀请等级
        $username = empty($member_info['name'])?$member_info['nickname']:$member_info['name'];

        $invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record['count'].'.'.$invite_settings['id']);
        $where = array(
            'act_id'=>$invite_settings['id'],
            'inter_id'=>$this->inter_id,
            'invited_mid'=>$member_info['member_info_id'],
            'invited_mid_lvl'=>$member_info['member_lvl_id'],
            'invited_lvl'=>$content[1],
            'invited_code'=>$invited_lvl_code
        );
        $invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //邀请者记录
        MYLOG::w(json_encode(array('res'=>$invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'reg_invited_lvl_record');
        if(empty($invited_lvl_record)) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),0);
        }

        $this->pm->_shard_db(true)->trans_begin(); //开启事务
        try{
            //扣取邀请数量
            $invited_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invite_settings['id']);
            $params = array('inter_id'=>$this->inter_id,'code'=>$invited_code,'count >'=>0);
            $update_deduct = $this->pm->update_deduct($params,'count','count-1','member_invited_record');
            MYLOG::w(json_encode(array('res'=>$update_deduct,'params'=>$params)),'front/membervip/invitate', 'update_deduct');

            if(!$update_deduct) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //扣取领取数量失败

            $params = array(
                'act_id'=>$invite_settings['id'],
                'inter_id'=>$this->inter_id,
                'invited_mid'=>$member_info['member_info_id'],
                'invited_mid_lvl'=>$member_info['member_lvl_id'],
                'invited_lvl'=>$content[1],
                'invited_code'=>$invited_lvl_code,
                'is_receive'=>'f'
            );
            $set_data = array('is_receive'=>'t');
            $invited_lvl_record_update = $this->pm->update_save($params,$set_data,'invited_lvl_record');
            MYLOG::w(json_encode(array('res'=>$invited_lvl_record_update,'params'=>$params,'data'=>$set_data)),'front/membervip/invitate', 'invited_lvl_record_update');
            if(!$invited_lvl_record_update) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //设置邀请状态失败

            //更新邀请者的邀请记录
            $invited_record_count = $invited_record['count'] - 1;
            if($invited_record_count > 0){
                $_invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record_count.'.'.$invite_settings['id']);
                $where = array(
                    'act_id'=>$invite_settings['id'],
                    'inter_id'=>$this->inter_id,
                    'invited_mid'=>$member_info['member_info_id'],
                    'invited_mid_lvl'=>$member_info['member_lvl_id'],
                    'invited_lvl'=>$content[1],
                    'invited_code'=>$_invited_lvl_code
                );
                $_invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //更新后的邀请者记录
                MYLOG::w(json_encode(array('res'=>$_invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'invited_lvl_record_update');

                if(empty($_invited_lvl_record)){
                    $save_lvl_record = array(
                        'inter_id'=>$this->inter_id,
                        'act_id'=>$invite_settings['id'],
                        'invited_mid'=>$member_info['member_info_id'],
                        'invited_mid_lvl'=>$member_info['member_lvl_id'],
                        'invited_lvl'=>$content[1],
                        'invited_code'=>$_invited_lvl_code,
                        'invited_count'=>$invited_record_count,
                        'createtime'=>time()
                    );
                    $invited_lvl_record_add = $this->pm->add_data($save_lvl_record,'invited_lvl_record'); //添加邀请者记录记录
                    MYLOG::w(json_encode(array('res'=>$invited_lvl_record_add,'params'=>$save_lvl_record)),'front/membervip/invitate', 'invited_lvl_record_add');
                    if(!$invited_lvl_record_add) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员');
                }
            }
            /*end*/

            $reg_url = PMS_PATH_URL."member/reg";
            $reg_data = array(
                'inter_id'=>$this->inter_id,
                'openid'=>$this->openid,
                'data'=>$post_data,
            );

            $reg_result = $this->doCurlPostRequest($reg_url,$reg_data); //注册会员
            MYLOG::w(json_encode(array('res'=>$reg_result,'data'=>$reg_data)),'front/membervip/invitate', 'reg_result');
            if(!isset($reg_result['err']) || floatval($reg_result['err'])>0) { //注册失败
                $msg = !empty($reg_result['msg'])?$reg_result['msg']:'注册失败';
                throw new Exception($msg,-1);
            }
            $new_member_info = !empty($reg_result['data'])?$reg_result['data']:array();
            if(empty($new_member_info)) {
                throw new Exception('很抱歉，领取失败，请重新领取或联系管理员');
            }
            $new_member_info_id = !empty($new_member_info['member_info_id'])?$new_member_info['member_info_id']:0;
            $org_lvl = $new_member_info['member_lvl_id'];

            if(empty($member_lvl[$new_member_info['member_lvl_id']])){ //不存在被邀请者的会员等级
                throw new Exception('很抱歉，领取失败',107);
            }

            $user_lvl = $member_lvl[$new_member_info['member_lvl_id']]; //被邀请者等级

            //检测比较邀请等级于当前会员等级高低
            $vk=0; //邀请的等级
            $mk=0; //当前会员等级
            $ik=0;
            foreach ($member_lvl as $k=>$vo){
                if($k==$content[1]) $vk = $ik;
                if($k==$new_member_info['member_lvl_id']) $mk = $ik;
                $ik++;
            }

            if($mk==$vk) { //当前会员等级等于领取的等级
                $msg = '您已经是'.$invite_user_lvl.'资格了|本次邀请无效';
                throw new Exception($msg,116);
            }elseif ($mk>$vk){ //当前会员等级大于领取的等级
                $msg = '您当前等级'.$user_lvl.'高于领取的等级'.$invite_user_lvl.'|本次邀请无效';
                throw new Exception($msg,116);
            }

            //添加模版消息队列
            $save_data =array(
                'inter_id'=>$this->inter_id,
                'openid'=>$member_info['open_id'],
                'business_model'=>2,
                'content'=>$new_member_info_id.'.'.$content[1],
                'message_type'=>6,
                'createtime'=>time(),
                'expiretime'=>strtotime('+1 day'),
            );
            $add_message_queue = $this->pm->add_data($save_data,'template_message_queue');
            MYLOG::w(json_encode(array('res'=>$add_message_queue,'params'=>$save_data)),'front/membervip/invitate', 'add_message_queue');

            $rise_lvl_url = INTER_PATH_URL."member/rise_lvl";
            $rise_lvl_data = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'openid'=>$this->openid,
                'member_info_id'=>$new_member_info_id,
                'lvl_id'=>$content[1],
                'remark'=>'邀请好友注册领取等级'
            );
            $rise_lvl_result = $this->doCurlPostRequest($rise_lvl_url,$rise_lvl_data); //升级会员
            MYLOG::w(json_encode(array('res'=>$rise_lvl_result,'data'=>$rise_lvl_data)),'front/membervip/invitate', 'rise_lvl_result');

            $save_data = array(
                'act_id'=>$invite_settings['id'],
                'invited_lvl_id'=>$invited_lvl_record['id'],
                'inter_id'=>$this->inter_id,
                'to_mid'=>$member_info['member_info_id'],
                'accept_mid'=>$new_member_info_id,
                'org_lvl'=>$org_lvl,
                'member_lvl_id'=>$content[1],
                'in_channel'=>$content[3],
                'ob_channel'=>'reg',
                'invited_time'=>$member_initiate['createtime'],
                'createtime'=>time()
            );
            $add_result = $this->pm->add_data($save_data,'invited_record');
            MYLOG::w(json_encode(array('res'=>$add_result,'data'=>$save_data)),'front/membervip/invitate', 'add_invited_record');

            if(!$add_result) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //添加领取记录失败

            /*优惠处理star*/
            //获取优惠信息
            $post_card = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'is_active'=>'t',
                'type'=>'reg',
            );
            $rule_info= $this->doCurlPostRequest(PMS_PATH_URL."cardrule/get_package_card_rule_info",$post_card);
            MYLOG::w(json_encode(array('res'=>$rule_info,'data'=>$post_card)),'front/membervip/invitate', 'get_package_card_rule_info');
            $rule_infos = array();
            if(!empty($rule_info['data'])){
                $rule_infos = $rule_info['data'];
            }

            if(!empty($rule_infos) && is_array($rule_infos)){
                $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
                $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
                foreach ($rule_infos as $key => $item){
                    if( isset($item['is_package']) && $item['is_package']=='t'){
                        $package_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'uu_code'=>md5(uniqid()),
                            'package_id'=>$item['package_id'],
                            'card_rule_id'=>$item['card_rule_id'],
                            'number'=>$item['frequency']
                        );
                        $result = $this->doCurlPostRequest( $packge_url , $package_data );
                        MYLOG::w(json_encode(array('res'=>$result,'data'=>$package_data)),'front/membervip/invitate', 'package_give');

                    }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                        $card_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'card_id'=>$item['card_id'],
                            'type'=>'reg'
                        );
                        $result = $this->doCurlPostRequest( $card_url , $card_data );
                        MYLOG::w(json_encode(array('res'=>$result,'data'=>$card_data)),'front/membervip/invitate', 'reg_gain_card');
                    }
                }
            }
            /*优惠处理end*/
            $this->pm->_shard_db(true)->trans_commit();// 事务提交
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '恭喜你成功获得'.$username.'邀请的'.$invite_user_lvl.'资格';
            $this->_ajaxReturn('恭喜你成功获得'.$username.'邀请的'.$invite_user_lvl.'资格',site_url('membervip/invitate/success?id='.$this->inter_id),1);

        }catch (Exception $e){
            $this->pm->_shard_db(true)->trans_rollback(); //回滚事务
            MYLOG::w(json_encode(array('line'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e->getMessage())),'front/membervip/invitate', 'reg_receive_exception');
            $message = !empty($e->getMessage())?$e->getMessage():'很抱歉，领取失败，请重新领取或联系管理员';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $message;
            $status = !empty($e->getCode())?$e->getCode():0;
            $jump_url = site_url('membervip/invitate/fail?id='.$this->inter_id);
            $this->_ajaxReturn($message,$jump_url,$status);
        }
    }

    //登录领取事务
    public function login_receive_execute(){
        $post_data = $this->input->post();
        MYLOG::w(json_encode($post_data),'front/membervip/invitate', 'login_receive_post');

        $code = $post_data['code'];
        $check_receive = $this->check_receive($code);
        MYLOG::w(json_encode(array('check_receive'=>$check_receive,'code'=>$code)),'front/membervip/invitate', 'check_receive');

        if(!$check_receive['status'] || floatval($check_receive['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $check_receive['msg'];
            $this->_ajaxReturn($check_receive['msg'],$check_receive['url'],$check_receive['status']);
        }

        if(empty($check_receive['data'])) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),0);
        }
        $content = $check_receive['data']['content'];
        $invite_settings = $check_receive['data']['invite_settings'];
        $member_info = $check_receive['data']['member_info'];
        $member_lvl = $check_receive['data']['member_lvl'];
        $invited_record = $check_receive['data']['invited_record'];
        $member_initiate = $check_receive['data']['member_initiate'];
        $org_lvl = $this->vip_user['member_lvl_id'];
        $invite_user_lvl = $member_lvl[$content[1]]; //邀请等级
        $username = empty($member_info['name'])?$member_info['nickname']:$member_info['name']; //邀请人名称

        $invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record['count'].'.'.$invite_settings['id']);
        $where = array(
            'act_id'=>$invite_settings['id'],
            'inter_id'=>$this->inter_id,
            'invited_mid'=>$member_info['member_info_id'],
            'invited_mid_lvl'=>$member_info['member_lvl_id'],
            'invited_lvl'=>$content[1],
            'invited_code'=>$invited_lvl_code
        );
        $invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //邀请者记录
        MYLOG::w(json_encode(array('res'=>$invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'login_invited_lvl_record');
        if(empty($invited_lvl_record)) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),0);
        }

        //获取会员模式
        $where = array('inter_id'=>$this->inter_id,'type_code'=>'member');
        $member_config = $this->pm->get_info($where,'inter_member_config','inter_id,value');
        if(empty($member_config)){
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),106);
        }

        if($member_config['value']=='login'){
            //初始化当前微信用户下的所有会员登录状态
            $params = array('inter_id'=>$this->inter_id,'open_id'=>$this->openid,'is_active'=>'t');
            $set_data = array('is_login'=>'f');
            $login_update = $this->pm->update_save($params,$set_data,'member_info');
            MYLOG::w(json_encode(array('res'=>$login_update,'params'=>$params,'data'=>$set_data)),'front/membervip/invitate', 'login_update');
        }

        $this->pm->_shard_db(true)->trans_begin(); //开启事务
        try{
            //扣取邀请数量
            $invited_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invite_settings['id']);
            $params = array('inter_id'=>$this->inter_id,'code'=>$invited_code,'count >'=>0);
            $update_deduct = $this->pm->update_deduct($params,'count','count-1','member_invited_record');
            MYLOG::w(json_encode(array('res'=>$update_deduct,'params'=>$params)),'front/membervip/invitate', 'update_deduct');

            if(!$update_deduct) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //扣取领取数量失败

            $invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record['count'].'.'.$invite_settings['id']);
            $params = array(
                'act_id'=>$invite_settings['id'],
                'inter_id'=>$this->inter_id,
                'invited_mid'=>$member_info['member_info_id'],
                'invited_mid_lvl'=>$member_info['member_lvl_id'],
                'invited_lvl'=>$content[1],
                'invited_code'=>$invited_lvl_code,
                'is_receive'=>'f'
            );
            $set_data = array('is_receive'=>'t');
            $invited_lvl_record_update = $this->pm->update_save($params,$set_data,'invited_lvl_record');
            MYLOG::w(json_encode(array('res'=>$invited_lvl_record_update,'params'=>$params,'data'=>$set_data)),'front/membervip/invitate', 'invited_lvl_record_update');
            if(!$invited_lvl_record_update) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //设置邀请状态失败

            //更新邀请者的邀请记录
            $invited_record_count = $invited_record['count'] - 1;
            if($invited_record_count > 0){
                $_invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record_count.'.'.$invite_settings['id']);

                $where = array(
                    'act_id'=>$invite_settings['id'],
                    'inter_id'=>$this->inter_id,
                    'invited_mid'=>$member_info['member_info_id'],
                    'invited_mid_lvl'=>$member_info['member_lvl_id'],
                    'invited_lvl'=>$content[1],
                    'invited_code'=>$_invited_lvl_code
                );
                $_invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //更新后的邀请者记录
                MYLOG::w(json_encode(array('res'=>$_invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'invited_lvl_record_update');
                if(empty($_invited_lvl_record)){
                    $save_lvl_record = array(
                        'inter_id'=>$this->inter_id,
                        'act_id'=>$invite_settings['id'],
                        'invited_mid'=>$member_info['member_info_id'],
                        'invited_mid_lvl'=>$member_info['member_lvl_id'],
                        'invited_lvl'=>$content[1],
                        'invited_code'=>$_invited_lvl_code,
                        'invited_count'=>$invited_record_count,
                        'createtime'=>time()
                    );
                    $invited_lvl_record_add = $this->pm->add_data($save_lvl_record,'invited_lvl_record'); //添加邀请者记录记录
                    MYLOG::w(json_encode(array('res'=>$invited_lvl_record_add,'params'=>$save_lvl_record)),'front/membervip/invitate', 'invited_lvl_record_add');
                    if(!$invited_lvl_record_add) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员');
                }
            }
            /*end*/

            if($member_config['value']=='login'){
                $login_url = PMS_PATH_URL."member/login";
                $login_data = array(
                    'inter_id'=>$this->inter_id,
                    'openid'=>$this->openid,
                    'data'=>$post_data,
                );

                $login_result = $this->doCurlPostRequest($login_url,$login_data); //登录会员
                MYLOG::w(json_encode(array('res'=>$login_result,'data'=>$login_data)),'front/membervip/invitate', 'login_result');
                if(!isset($login_result['err']) || floatval($login_result['err'])>0) { //登录会员
                    $msg = !empty($login_result['msg'])?$login_result['msg']:'登录失败';
                    throw new Exception($msg,-1);
                }
                $new_member_info = !empty($login_result['data'])?$login_result['data']:array();
                $new_member_info_id = $new_member_info['member_info_id'];
                if(empty($new_member_info)) {
                    throw new Exception('很抱歉，领取失败，请重新领取或联系管理员');
                }
                $org_lvl = $new_member_info['member_lvl_id'];
            }else{
                $new_member_info = $this->vip_user;
                $new_member_info_id = !empty($this->vip_user['member_info_id'])?$this->vip_user['member_info_id']:0;
            }

            if($member_info['member_info_id']==$new_member_info_id){
                $msg = '您不能领取自己邀请的'.$invite_user_lvl.'资格|本次邀请无效';
                throw new Exception($msg,116);
            }

            if(empty($member_lvl[$new_member_info['member_lvl_id']])){ //不存在被邀请者的会员等级
                throw new Exception('很抱歉，领取失败',107);
            }

            $user_lvl = $member_lvl[$new_member_info['member_lvl_id']]; //被邀请者等级

            $where = array(
                'act_id'=>$invite_settings['id'],
                'inter_id'=>$this->inter_id,
                'to_mid'=>$member_info['member_info_id'],
                'accept_mid'=>$new_member_info_id,
                'member_lvl_id'=>$content[1]
            );
            $record = $this->pm->get_info($where,'invited_record');
            MYLOG::w(json_encode(array('data'=>$record,'where'=>$where)),'front/membervip/invitate', 'check_record');
            if(!empty($record)) {//已经领取过
                $msg = '您已经领取过来自'.$username.'的'.$invite_user_lvl.'资格邀请|本次邀请无效';
                throw new Exception($msg,116);
            }

            //检测比较邀请等级于当前会员等级高低
            $vk=0; //邀请的等级
            $mk=0; //当前会员等级
            $ik=0;
            foreach ($member_lvl as $k=>$vo){
                if($k==$content[1]) $vk = $ik;
                if($k==$new_member_info['member_lvl_id']) $mk = $ik;
                $ik++;
            }

            if($mk==$vk) { //当前会员等级等于领取的等级
                $msg = '您已经是'.$invite_user_lvl.'资格了|本次邀请无效';
                throw new Exception($msg,116);
            }elseif ($mk>$vk){ //当前会员等级大于领取的等级
                $msg = '您当前等级'.$user_lvl.'高于领取的等级'.$invite_user_lvl.'|本次邀请无效';
                throw new Exception($msg,116);
            }

            //添加模版消息队列
            $save_data =array(
                'inter_id'=>$this->inter_id,
                'openid'=>$member_info['open_id'],
                'business_model'=>2,
                'content'=>$new_member_info_id.'.'.$content[1],
                'message_type'=>6,
                'createtime'=>time(),
                'expiretime'=>strtotime('+1 day'),
            );
            $add_message_queue = $this->pm->add_data($save_data,'template_message_queue');
            MYLOG::w(json_encode(array('res'=>$add_message_queue,'params'=>$save_data)),'front/membervip/invitate', 'add_message_queue');

            $rise_lvl_url = INTER_PATH_URL."member/rise_lvl";
            $rise_lvl_data = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'openid'=>$this->openid,
                'member_info_id'=>$new_member_info_id,
                'lvl_id'=>$content[1],
                'remark'=>'邀请好友注册领取等级'
            );
            $rise_lvl_result = $this->doCurlPostRequest($rise_lvl_url,$rise_lvl_data); //升级会员
            MYLOG::w(json_encode(array('res'=>$rise_lvl_result,'data'=>$rise_lvl_data)),'front/membervip/invitate', 'rise_lvl_result');

            $save_data = array(
                'act_id'=>$invite_settings['id'],
                'invited_lvl_id'=>$invited_lvl_record['id'],
                'inter_id'=>$this->inter_id,
                'to_mid'=>$member_info['member_info_id'],
                'accept_mid'=>$new_member_info_id,
                'org_lvl'=>$org_lvl,
                'member_lvl_id'=>$content[1],
                'in_channel'=>$content[3],
                'ob_channel'=>'login',
                'invited_time'=>$member_initiate['createtime'],
                'createtime'=>time()
            );
            $add_result = $this->pm->add_data($save_data,'invited_record');
            MYLOG::w(json_encode(array('res'=>$add_result,'data'=>$save_data)),'front/membervip/invitate', 'add_invited_record');

            if(!$add_result) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //添加领取记录失败

            $this->pm->_shard_db(true)->trans_commit();// 事务提交
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '恭喜你成功获得'.$username.'邀请的'.$invite_user_lvl.'资格';
            $this->_ajaxReturn('恭喜你成功获得'.$username.'邀请的'.$invite_user_lvl.'资格',site_url('membervip/invitate/success?id='.$this->inter_id),1);
        }catch (Exception $e){
            $this->pm->_shard_db(true)->trans_rollback(); //回滚事务
            MYLOG::w(json_encode(array('line'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e->getMessage())),'front/membervip/invitate', 'login_receive_exception');
            $message = !empty($e->getMessage())?$e->getMessage():'很抱歉，领取失败，请重新领取或联系管理员';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $message;
            $status = !empty($e->getCode())?$e->getCode():0;
            $this->_ajaxReturn($message,site_url('membervip/invitate/fail?id='.$this->inter_id),$status);
        }
    }

    //登录领取事务
    public function perfect_receive_execute(){
        $post_data = $this->input->post();
        MYLOG::w(json_encode($post_data),'front/membervip/invitate', 'perfect_receive_post');

        $code = $post_data['code'];
        $check_receive = $this->check_receive($code);
        MYLOG::w(json_encode(array('check_receive'=>$check_receive,'code'=>$code)),'front/membervip/invitate', 'check_receive');

        if(!$check_receive['status'] || floatval($check_receive['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $check_receive['msg'];
            $this->_ajaxReturn($check_receive['msg'],$check_receive['url'],$check_receive['status']);
        }

        if(empty($check_receive['data'])) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),0);
        }
        $content = $check_receive['data']['content'];
        $invite_settings = $check_receive['data']['invite_settings'];
        $member_info = $check_receive['data']['member_info'];
        $member_lvl = $check_receive['data']['member_lvl'];
        $invited_record = $check_receive['data']['invited_record'];
        $member_initiate = $check_receive['data']['member_initiate'];
        $invite_user_lvl = $member_lvl[$content[1]]; //邀请等级
        $username = empty($member_info['name'])?$member_info['nickname']:$member_info['name']; //邀请人名称

        $invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record['count'].'.'.$invite_settings['id']);
        $where = array(
            'act_id'=>$invite_settings['id'],
            'inter_id'=>$this->inter_id,
            'invited_mid'=>$member_info['member_info_id'],
            'invited_mid_lvl'=>$member_info['member_lvl_id'],
            'invited_lvl'=>$content[1],
            'invited_code'=>$invited_lvl_code
        );
        $invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //邀请者记录
        MYLOG::w(json_encode(array('res'=>$invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'perfect_invited_lvl_record');
        if(empty($invited_lvl_record)) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败，请联系管理员';
            $this->_ajaxReturn('很抱歉，领取失败，请联系管理员',site_url('membervip/invitate/fail?id='.$this->inter_id),0);
        }

        if($member_info['member_info_id']==$this->vip_user['member_info_id']){
            $msg = '您不能领取自己邀请的'.$invite_user_lvl.'资格|本次邀请无效';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $msg;
            $this->_ajaxReturn($msg,site_url('membervip/invitate/fail?id='.$this->inter_id),116);
        }

        $where = array(
            'act_id'=>$invite_settings['id'],
            'inter_id'=>$this->inter_id,
            'to_mid'=>$member_info['member_info_id'],
            'accept_mid'=>$this->vip_user['member_info_id'],
            'member_lvl_id'=>$content[1]
        );
        $record = $this->pm->get_info($where,'invited_record');
        MYLOG::w(json_encode(array('data'=>$record,'where'=>$where)),'front/membervip/invitate', 'check_record');
        if(!empty($record)) {//已经领取过
            $msg = '您已经领取过来自'.$username.'的'.$invite_user_lvl.'资格邀请|本次邀请无效';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $msg;
            $this->_ajaxReturn($msg,site_url('membervip/invitate/fail?id='.$this->inter_id),116);
        }

        if(empty($member_lvl[$this->vip_user['member_lvl_id']])){ //不存在被邀请者的会员等级
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '很抱歉，领取失败';
            $this->_ajaxReturn('很抱歉，领取失败',site_url('membervip/invitate/fail?id='.$this->inter_id),107);
        }

        $user_lvl = $member_lvl[$this->vip_user['member_lvl_id']]; //被邀请者等级

        //检测比较邀请等级于当前会员等级高低
        $vk=0; //邀请的等级
        $mk=0; //当前会员等级
        $ik=0;
        foreach ($member_lvl as $k=>$vo){
            if($k==$content[1]) $vk = $ik;
            if($k==$this->vip_user['member_lvl_id']) $mk = $ik;
            $ik++;
        }

        if($mk==$vk) { //当前会员等级等于领取的等级
            $msg = '您已经是'.$invite_user_lvl.'资格了|本次邀请无效';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $msg;
            $this->_ajaxReturn($msg,site_url('membervip/invitate/fail?id='.$this->inter_id),116);
        }elseif ($mk>$vk){ //当前会员等级大于领取的等级
            $msg = '您当前等级'.$user_lvl.'高于领取的等级'.$invite_user_lvl.'|本次邀请无效';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $msg;
            $this->_ajaxReturn($msg,site_url('membervip/invitate/fail?id='.$this->inter_id),116);
        }

        $this->pm->_shard_db(true)->trans_begin(); //开启事务
        try{
            //扣取邀请数量
            $invited_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invite_settings['id']);
            $params = array('inter_id'=>$this->inter_id,'code'=>$invited_code,'count >'=>0);
            $update_deduct = $this->pm->update_deduct($params,'count','count-1','member_invited_record');
            MYLOG::w(json_encode(array('res'=>$update_deduct,'params'=>$params)),'front/membervip/invitate', 'update_deduct');

            if(!$update_deduct) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //扣取领取数量失败

            $invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record['count'].'.'.$invite_settings['id']);
            $params = array(
                'act_id'=>$invite_settings['id'],
                'inter_id'=>$this->inter_id,
                'invited_mid'=>$member_info['member_info_id'],
                'invited_mid_lvl'=>$member_info['member_lvl_id'],
                'invited_lvl'=>$content[1],
                'invited_code'=>$invited_lvl_code,
                'is_receive'=>'f'
            );
            $set_data = array('is_receive'=>'t');
            $invited_lvl_record_update = $this->pm->update_save($params,$set_data,'invited_lvl_record');
            MYLOG::w(json_encode(array('res'=>$invited_lvl_record_update,'params'=>$params,'data'=>$set_data)),'front/membervip/invitate', 'invited_lvl_record_update');
            if(!$invited_lvl_record_update) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //设置邀请状态失败

            //更新邀请者的邀请记录
            $invited_record_count = $invited_record['count'] - 1;
            if($invited_record_count > 0){
                $_invited_lvl_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invited_record_count.'.'.$invite_settings['id']);

                $where = array(
                    'act_id'=>$invite_settings['id'],
                    'inter_id'=>$this->inter_id,
                    'invited_mid'=>$member_info['member_info_id'],
                    'invited_mid_lvl'=>$member_info['member_lvl_id'],
                    'invited_lvl'=>$content[1],
                    'invited_code'=>$_invited_lvl_code
                );
                $_invited_lvl_record = $this->pm->get_info($where,'invited_lvl_record'); //更新后的邀请者记录
                MYLOG::w(json_encode(array('res'=>$_invited_lvl_record,'params'=>$where)),'front/membervip/invitate', 'invited_lvl_record_update');
                if(empty($_invited_lvl_record)){
                    $save_lvl_record = array(
                        'inter_id'=>$this->inter_id,
                        'act_id'=>$invite_settings['id'],
                        'invited_mid'=>$member_info['member_info_id'],
                        'invited_mid_lvl'=>$member_info['member_lvl_id'],
                        'invited_lvl'=>$content[1],
                        'invited_code'=>$_invited_lvl_code,
                        'invited_count'=>$invited_record_count,
                        'createtime'=>time()
                    );
                    $invited_lvl_record_add = $this->pm->add_data($save_lvl_record,'invited_lvl_record'); //添加邀请者记录记录
                    MYLOG::w(json_encode(array('res'=>$invited_lvl_record_add,'params'=>$save_lvl_record)),'front/membervip/invitate', 'invited_lvl_record_add');
                    if(!$invited_lvl_record_add) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员');
                }
            }
            /*end*/

            $save_url = PMS_PATH_URL."member/save_memberinfo";
            unset($post_data['code']);
            if(!empty($post_data['birthday'])){
                $post_data['birthday'] = strtotime($post_data['birthday']);
                $post_data['birth'] = $post_data['birthday'];
                unset($post_data['birthday']);
            }

            if(!empty($post_data['idno'])){
                $post_data['id_card_no'] = $post_data['idno'];
                unset($post_data['idno']);
            }

            if(!empty($post_data['phone'])){
                $post_data['cellphone']=$post_data['phone'];
                unset($post_data['phone']);
            }

            if(!empty($post_data['phonesms'])){
                $post_data['sms']=$post_data['phonesms'];
                unset($post_data['phonesms']);
            }

            $_save_data = array(
                'inter_id'=>$this->inter_id,
                'openid'=>$this->openid,
                'data'=>$post_data,
            );


            $save_result = $this->doCurlPostRequest($save_url,$_save_data); //保存会员资料
            MYLOG::w(json_encode(array('res'=>$save_result,'data'=>$_save_data)),'front/membervip/invitate', 'perfect_result');
            if(!isset($save_result['err']) || floatval($save_result['err'])>0) { //保存失败
                $msg = !empty($save_result['msg'])?$save_result['msg']:'保存失败';
                throw new Exception($msg,-1);
            }
            $new_member_info_id = $this->vip_user['member_info_id'];

            //添加模版消息队列
            $save_data =array(
                'inter_id'=>$this->inter_id,
                'openid'=>$member_info['open_id'],
                'business_model'=>2,
                'content'=>$new_member_info_id.'.'.$content[1],
                'message_type'=>6,
                'createtime'=>time(),
                'expiretime'=>strtotime('+1 day'),
            );
            $add_message_queue = $this->pm->add_data($save_data,'template_message_queue');
            MYLOG::w(json_encode(array('res'=>$add_message_queue,'params'=>$save_data)),'front/membervip/invitate', 'add_message_queue');

            $rise_lvl_url = INTER_PATH_URL."member/rise_lvl";
            $rise_lvl_data = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'openid'=>$this->openid,
                'member_info_id'=>$new_member_info_id,
                'lvl_id'=>$content[1],
                'remark'=>'邀请好友注册领取等级'
            );
            $rise_lvl_result = $this->doCurlPostRequest($rise_lvl_url,$rise_lvl_data); //升级会员
            MYLOG::w(json_encode(array('res'=>$rise_lvl_result,'data'=>$rise_lvl_data)),'front/membervip/invitate', 'rise_lvl_result');

            $save_data = array(
                'act_id'=>$invite_settings['id'],
                'invited_lvl_id'=>$invited_lvl_record['id'],
                'inter_id'=>$this->inter_id,
                'to_mid'=>$member_info['member_info_id'],
                'accept_mid'=>$new_member_info_id,
                'org_lvl'=>$this->vip_user['member_lvl_id'],
                'member_lvl_id'=>$content[1],
                'in_channel'=>$content[3],
                'ob_channel'=>'perfect',
                'invited_time'=>$member_initiate['createtime'],
                'createtime'=>time()
            );
            $add_result = $this->pm->add_data($save_data,'invited_record');
            MYLOG::w(json_encode(array('res'=>$add_result,'data'=>$save_data)),'front/membervip/invitate', 'add_invited_record');

            if(!$add_result) throw new Exception('很抱歉，领取失败，请重新领取或联系管理员'); //添加领取记录失败

            /*优惠处理star*/
            //获取优惠信息
            $post_card = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'is_active'=>'t',
                'type'=>'reg',
            );
            $rule_info= $this->doCurlPostRequest(PMS_PATH_URL."cardrule/get_package_card_rule_info",$post_card);
            MYLOG::w(json_encode(array('res'=>$rule_info,'data'=>$post_card)),'front/membervip/invitate', 'get_package_card_rule_info');
            $rule_infos = array();
            if(!empty($rule_info['data'])){
                $rule_infos = $rule_info['data'];
            }

            if(!empty($rule_infos) && is_array($rule_infos)){
                $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
                $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
                foreach ($rule_infos as $key => $item){
                    if( isset($item['is_package']) && $item['is_package']=='t'){
                        $package_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'uu_code'=>md5(uniqid()),
                            'package_id'=>$item['package_id'],
                            'card_rule_id'=>$item['card_rule_id'],
                            'number'=>$item['frequency']
                        );
                        $result = $this->doCurlPostRequest( $packge_url , $package_data );
                        MYLOG::w(json_encode(array('res'=>$result,'data'=>$package_data)),'front/membervip/invitate', 'package_give');

                    }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                        $card_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'card_id'=>$item['card_id'],
                            'type'=>'reg'
                        );
                        $result = $this->doCurlPostRequest( $card_url , $card_data );
                        MYLOG::w(json_encode(array('res'=>$result,'data'=>$card_data)),'front/membervip/invitate', 'reg_gain_card');
                    }
                }
            }
            /*优惠处理end*/

            $this->pm->_shard_db(true)->trans_commit();// 事务提交
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '恭喜你成功获得'.$username.'邀请的'.$invite_user_lvl.'资格';
            $this->_ajaxReturn('恭喜你成功获得'.$username.'邀请的'.$invite_user_lvl.'资格',site_url('membervip/invitate/success?id='.$this->inter_id),1);
        }catch (Exception $e){
            $this->pm->_shard_db(true)->trans_rollback(); //回滚事务
            MYLOG::w(json_encode(array('line'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e->getMessage())),'front/membervip/invitate', 'perfect_receive_exception');
            $message = !empty($e->getMessage())?$e->getMessage():'很抱歉，领取失败，请重新领取或联系管理员';
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $message;
            $status = !empty($e->getCode())?$e->getCode():0;
            $this->_ajaxReturn($message,site_url('membervip/invitate/fail?id='.$this->inter_id),$status);
        }
    }

    protected function _get_sign_package($inter_id, $url=''){
        $this->load->helper('common');
        $this->load->model('wx/publics_model', 'publics');
        $this->load->model('wx/access_token_model');
        $jsapiTicket = $this->access_token_model->get_api_ticket( $inter_id );

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if(!$url)
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();
        $public = $this->publics->get_public_by_id( $inter_id );

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $public['app_id'],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    //检测领取场景和领取信息是否合格
    protected function check_receive($code=''){
        $jump_url = site_url('membervip/invitate/fail?id='.$this->inter_id);
        //会员邀请凭证记录
        $member_initiate = $this->pm->get_info(array('code'=>$code),'member_initiate');
        MYLOG::w(json_encode(array('data'=>$member_initiate,'code'=>$code)),'front/membervip/api/invitate', 'check_member_initiate');
        if(empty($member_initiate)){ //不存在邀请凭证记录
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>101);
        }

        //邀请活动配置
        $invite_settings = $this->pm->get_info(array('inter_id'=>$this->inter_id,'is_active'=>'t'),'invite_settings');
        MYLOG::w(json_encode(array('data'=>$invite_settings,'where'=>array('inter_id'=>$this->inter_id,'is_active'=>'t'))),'front/membervip/api/invitate', 'check_invite_settings');
        if(empty($invite_settings)) {//邀请活动配置不存在
            return array('url'=>$jump_url,'msg'=>'很抱歉，活动已终止','status'=>102);
        }

        //平台等级配置
        $member_lvl = $this->pm->get_field_by_level_config($this->inter_id,'member_lvl_id,lvl_name,lvl_up_sort');
        MYLOG::w(json_encode(array('data'=>$member_lvl,'inter_id'=>$this->inter_id)),'front/membervip/api/invitate', 'check_member_lvl');
        if(empty($member_lvl)){
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>103);
        }

        if($member_initiate['inter_id']!=$this->inter_id || $member_initiate['act_id']!=$invite_settings['id']){ //验证不通过
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请验证不通过','status'=>104);
        }

        $_content = $member_initiate['content'];
        $content = explode('.',$_content);
        if(empty($content[0]) || empty($content[1]) || empty($content[2]) || empty($content[3])){
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请验证不通过','status'=>105);
        }

        //邀请者会员信息
        $member_info = $this->m_model->get_member_info($content[0],'member_info_id,open_id,member_lvl_id,name,nickname');
        MYLOG::w(json_encode(array('data'=>$member_info,'content'=>$content)),'front/membervip/api/invitate', 'check_member_info');
        if(empty($member_info)){
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>106);
        }

        if(empty($member_lvl[$member_info['member_lvl_id']])){ //不存在邀请者的会员等级
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>107);
        }

        if(empty($member_lvl[$content[1]])){ //不存在邀请的会员等级
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>107);
        }

        $invite_user_lvl = $member_lvl[$content[1]]; //邀请等级

        $username = empty($member_info['name'])?$member_info['nickname']:$member_info['name'];
        if($member_initiate['expiretime']<=time()){
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的'.$invite_user_lvl.'资格邀请已过期','status'=>108);
        }

        //邀请权益
        $level_equity = $this->pm->get_info(array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']),'invite_level_equity');
        MYLOG::w(json_encode(array('data'=>$level_equity,'where'=>array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']))),'front/membervip/api/invitate', 'check_level_equity');
        if(empty($level_equity)) {//邀请权益不存在
            return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>109);
        }

        $hold_lvl_group = json_decode($level_equity['hold_lvl_group'],true);
        if(empty($hold_lvl_group[$member_info['member_lvl_id']])) {//邀请权益匹配不成功
            return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>110);
        }

        if(empty($hold_lvl_group[$member_info['member_lvl_id']][$content[1]])) {//邀请等级匹配不成功或者可邀请次数为0
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的'.$invite_user_lvl.'资格邀请已失效','status'=>111);
        }

        if($hold_lvl_group[$member_info['member_lvl_id']][$content[1]]==0) {//邀请等级匹配不成功或者可邀请次数为0
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的'.$invite_user_lvl.'资格邀请无法领取','status'=>112);
        }


        $invited_code = sha1($member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invite_settings['id']);
        $where = array('code'=>$invited_code);
        $invited_record = $this->pm->get_info($where,'member_invited_record'); //用户邀请权益数量记录
        MYLOG::w(json_encode(array('data'=>$invited_record,'where'=>$where)),'front/membervip/api/invitate', 'check_invited_record');
        if(empty($invited_record)) return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>113);

        if($invited_record['inter_id']!=$this->inter_id || $invited_record['act_id']!=$invite_settings['id'] || $invited_record['lvl_id']!=$content[1] || $invited_record['member_info_id']!=$member_info['member_info_id']){
            return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>114); //验证不通过
        }

        if($invited_record['count']<=0)
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的|'.$invite_user_lvl.'资格邀请已被领完','status'=>115); //已经领完了

        return array('data'=>array('content'=>$content,'invite_settings'=>$invite_settings,'member_info'=>$member_info,'member_lvl'=>$member_lvl,'invited_record'=>$invited_record,'member_initiate'=>$member_initiate),'msg'=>'ok','status'=>1);
    }
}
?>