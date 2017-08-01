<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	邀金中心
*	@author  liwensong
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 171708252@qq.com
*/
class Invitatedkim extends MY_Front_Member
{
    protected $vip_user = array();
    protected $user_id = 0;
    protected $dis_conf = array();
    protected $assign_data = array();
    public function __construct(){
        parent::__construct();
        $this->load->library("MYLOG");

        //以缓存方式保存用户数据
        $this->load->model('membervip/front/Member_model');
        $this->vip_user = $this->Member_model->check_user_info($this->inter_id,$this->openid);

        $this->load->model('membervip/front/Kiminvited_model');

        //缓存显示配置
//        $this->dis_conf = $this->session->tempdata($this->inter_id.'vip_display_config');
        $this->dis_conf = $this->Kiminvited_model->get_display_config($this->inter_id);
        if(empty($this->dis_conf) || (isset($_GET['ref']) && $_GET['ref']==1)){
            $this->dis_conf = $this->Kiminvited_model->get_display_config($this->inter_id);
        }
        if(isset($this->dis_conf['title_config']['action_title']) && $this->dis_conf['title_config']['action_title']=='custom'){
            $this->dis_conf['page_title'] = $this->dis_conf['title_config']['custom_title'];
        }else{
            $this->dis_conf['page_title'] = '"邀金"哪里跑';
        }
        $this->assign_data['user'] = $this->vip_user;
        $this->assign_data['dis_conf'] = $this->dis_conf;
    }

    //首页
	public function index(){
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        MYLOG::w(json_encode(array('res'=>$info)),'membervip/invitatedkim','get_vip_user');
        if(!empty($info)) $this->assign_data['info'] = $info;

        //获取会员模式
        $_params['inter_id'] = $this->inter_id;
        $_params['type_code'] = 'member';
        $_params['field'] = 'inter_id,value';
        $member_sync = $this->Kiminvited_model->get_kiminvited_info($_params,'inter_member_config');
        MYLOG::w(json_encode(array('res'=>$member_sync,'params'=>$_params)),'membervip/invitatedkim','inter_member_config');

        $jump_url = site_url('membervip/invitatedkim?id='.$this->inter_id);
        if(!empty($member_sync['value']) && $member_sync['value']=='login'){
            $jump_url = site_url('membervip/invitatedkim/register?id='.$this->inter_id); //未注册会员
            if($this->vip_user['ismembercard']==1){ //已注册会员
                $jump_url = site_url('membervip/invitatedkim/raiders?id='.$this->inter_id);
                if($this->vip_user['is_login']=='f' || $this->vip_user['member_mode']==1) //未登录
                    $jump_url = site_url('membervip/invitatedkim/login?id='.$this->inter_id);
            }
        }elseif (!empty($member_sync['value']) && $member_sync['value']=='perfect'){
            $jump_url = site_url('membervip/invitatedkim/register?id='.$this->inter_id); //未完善資料
            if(!empty($this->vip_user['cellphone'])){//已完善資料
                $jump_url = site_url('membervip/invitatedkim/raiders?id='.$this->inter_id);
            }
        }
        $this->assign_data['member_jump_url'] = $jump_url;
        $this->template_show('member',$this->_template,'Invitatedkim/index',$this->assign_data);
    }

    //活动中心
    public function kimcenter(){
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;
        $this->assign_data['rank_title'] = isset($this->dis_conf['rank_config']['title_rank'])?$this->dis_conf['rank_config']['title_rank']:'邀金榜';
        $this->assign_data['rank_subtitle'] = isset($this->dis_conf['rank_config']['title2_rank'])?$this->dis_conf['rank_config']['title2_rank']:'';

        $this->assign_data['canon_title'] = isset($this->dis_conf['canon_config']['canon_title'])?$this->dis_conf['canon_config']['canon_title']:'邀金宝典';
        $this->assign_data['canon_subtitle'] = isset($this->dis_conf['canon_config']['canon_title2'])?$this->dis_conf['canon_config']['canon_title2']:'';

        if(isset($this->dis_conf['act_config']['action_reward']) && $this->dis_conf['act_config']['action_reward']=='custom'){
            $this->assign_data['reward_title'] = isset($this->dis_conf['act_config']['reward'])?$this->dis_conf['act_config']['reward']:'活动邀金';
        }else{
            $this->assign_data['reward_title'] = '活动邀金';
        }
        $this->assign_data['reward_subtitle'] = isset($this->dis_conf['act_config']['reward_detail'])?$this->dis_conf['act_config']['reward_detail']:'';

        if(isset($this->dis_conf['act_config']['action_reward']) && $this->dis_conf['act_config']['action_reward']=='custom'){
            $this->assign_data['reward_title'] = isset($this->dis_conf['act_config']['reward'])?$this->dis_conf['act_config']['reward']:'我的奖励';
        }else{
            $this->assign_data['reward_title'] = '我的奖励';
        }
        $this->assign_data['reward_subtitle'] = isset($this->dis_conf['act_config']['reward_detail'])?$this->dis_conf['act_config']['reward_detail']:'';

        if(isset($this->dis_conf['act_config']['action_point']) && $this->dis_conf['act_config']['action_point']=='custom'){
            $this->assign_data['point_title'] = isset($this->dis_conf['act_config']['point'])?$this->dis_conf['act_config']['point']:'活动邀金';
        }else{
            $this->assign_data['point_title'] = '活动邀金';
        }
        $this->assign_data['point_subtitle'] = isset($this->dis_conf['act_config']['point_detail'])?$this->dis_conf['act_config']['point_detail']:'';

        $this->assign_data['total_number'] = 0;
        $this->assign_data['unit_name'] = '';

        //获取奖励设置
        $params['inter_id']=$this->inter_id;
        $params['activited_id']=isset($info['id'])?$info['id']:'';
        $reward_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        $this->vip_write_log($reward_info,'get_kiminvited_info');

        $params['activited_id'] = isset($info['id'])?$info['id']:0;
        $params['member_type']='2';
        $params['member_info_id']=$this->vip_user['member_info_id'];
//        $integral = 0;
        $this->assign_data['star_name'] = '';
        if(!empty($reward_info)){
            switch ($reward_info['mode']){
                case '1':
                    //获取当前会员总活动邀金
                    $params['reward_type']='1';
                    $total = $this->Kiminvited_model->get_total_integral($params);
                    $this->assign_data['total_number'] = (isset($total['reward_value']) && !empty($total['reward_value']))?$total['reward_value']:0;
//                    $integral = floatval($this->assign_data['total_number']);
                    $this->assign_data['unit_name'] = '金';
                    break;
                case '2':
                    //获取当前会员排行
                    $params['reward_type']='2';
                    $this->assign_data['star_name'] = '';
                    $this->assign_data['unit_name'] = '';
                    $total = $this->Kiminvited_model->get_my_ranking($params,$this->vip_user['member_info_id']);
                    $this->vip_write_log($total,'_get_my_ranking');
                    if(isset($total['total_recom']) && floatval($total['total_recom'])>0){
                        $this->assign_data['total_number'] = (isset($total['ranking']) && !empty($total['ranking']))?$total['ranking']:0;
                        $this->assign_data['star_name'] = '第';
                        $this->assign_data['unit_name'] = '名';
                    }else{
                        $this->assign_data['total_number'] = '--';
                    }
                    break;
            }
        }

        $this->template_show('member',$this->_template,'Invitatedkim/kimcenter',$this->assign_data);
    }

    //活动中心
    public function myrecord(){
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;
        if(isset($this->dis_conf['center_config']['action_center']) && $this->dis_conf['center_config']['action_center']=='custom'){
            $this->dis_conf['page_title'] = $this->dis_conf['center_config']['custom_center'];
        }else{
            $this->dis_conf['page_title'] = '个人战绩';
        }

        $params['inter_id']=$this->inter_id;
        $params['activited_id']=isset($info['id'])?$info['id']:'';
        $reward_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        $this->assign_data['reward_info']=$reward_info;
        $this->vip_write_log($reward_info,'get_kiminvited_info');
        $this->assign_data['reward_title']='';
        $this->assign_data['reward_name']='邀请';
        if(isset($reward_info['old_reward_type'])){
            switch ($reward_info['old_reward_type']){
                case 1:
                    $this->assign_data['reward_title']='获得邀金';
                    $this->assign_data['reward_name']='邀金';
                    break;
            }
        }

        $params['inter_id'] = $this->inter_id;
        $params['member_info_id'] = $this->vip_user['member_info_id'];
        $params['activited_id'] = isset($info['id'])?$info['id']:'';
        $result = $this->Kiminvited_model->get_myrecord_info($params,0,50);
        $this->vip_write_log($result,'get_myrecord_info');
        $myrecord_info = $this->Kiminvited_model->handle_myrecord_info($result);
        $this->vip_write_log($myrecord_info,'handle_myrecord_info');
        $myrecord_data = isset($myrecord_info['data'])?$myrecord_info['data']:array();
        if(isset($reward_info['mode']) && $reward_info['mode']=='1'){
            $_count = isset($myrecord_info['total_value'])?$myrecord_info['total_value']:'--';
            $this->assign_data['total_value']=$_count;
        }else{
            $_count = count($result);
            $this->assign_data['total_value'] = '--';
            if(floatval($_count)>0){
                $this->assign_data['total_value'] = count($result).'人';
            }
        }
        $this->assign_data['myrecord_data']=$myrecord_data;
        $this->template_show('member',$this->_template,'Invitatedkim/myrecord',$this->assign_data);
    }

    //活动说明
    public function actdec(){
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;
        $this->template_show('member',$this->_template,'Invitatedkim/actdec',$this->assign_data);
    }

    //排行榜
    public function rank(){
        if(isset($this->dis_conf['rank_config']['action_rank']) && $this->dis_conf['rank_config']['action_rank']=='custom'){
            $this->dis_conf['page_title'] = $this->dis_conf['rank_config']['custom_rank'];
        }else{
            $this->dis_conf['page_title'] = '邀金榜';
        }
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        $this->vip_write_log($info,'get_recent_activity');
        if(!empty($info)) $this->assign_data['info'] = $info;
        $params['inter_id'] = $this->inter_id;
        $params['activited_id'] = isset($info['id'])?$info['id']:0;
        $params['where'] = '1';
        $type = $this->input->get('type');
        $this->assign_data['action_type'] = '1';
        if(!empty($type)) {
            $params['where'] = $type;
            $this->assign_data['action_type'] = $type;
        }
        $result = $this->Kiminvited_model->get_recommend_info($params,0,50,$this->vip_user['member_info_id']);
        $this->vip_write_log($result,'get_recommend_info');
        $this->vip_write_log($this->vip_user,'this_vip_user');
        $this->assign_data['first_list'] = $result['first_list'];
        $this->assign_data['rank_list'] = $result['rank_list'];
        if(!empty($result['myrank'])){
            $this->assign_data['myrank'] = $result['myrank'];
        }else{
            $myfansinfo = $this->Kiminvited_model->get_fans_info($this->openid,'nickname,headimgurl');
            $myuserinfo = array_merge($this->vip_user, $myfansinfo);
            $this->assign_data['myrank'] = $myuserinfo;
        }
        $this->template_show('member',$this->_template,'Invitatedkim/rank',$this->assign_data);
    }

    //攻略
    public function raiders(){
        //获取会员模式
        $_params['inter_id'] = $this->inter_id;
        $_params['type_code'] = 'member';
        $_params['field'] = 'inter_id,value';
        $member_sync = $this->Kiminvited_model->get_kiminvited_info($_params,'inter_member_config');
        $this->assign_data['member_sync'] = $member_sync;

        //检测是否已经注册并且是否登录
        if(empty($this->vip_user['cellphone'])){
            redirect('membervip/invitatedkim/register?id='.$this->inter_id);
        }else{
            if($this->vip_user['is_login']=='f' && isset($member_sync['value']) && $member_sync['value']=='login'){
                redirect('membervip/invitatedkim/login?id='.$this->inter_id);
            }
        }

        $ec_memid = $this->Kiminvited_model->vip_authencode($this->vip_user['member_info_id'].'|||'.$this->openid,$this->inter_id);
        $this->assign_data['url_code'] = base64_encode($ec_memid);
        $this->assign_data['wx_config'] = $this->_get_sign_package($this->inter_id);
        $js_api_list = $menu_show_list = $menu_hide_list= '';
        $this->assign_data['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        if( isset($this->assign_data['js_api_list']) ) {
            $this->assign_data['js_api_list']+= $this->assign_data['base_api_list'];
        } else {
            $this->assign_data['js_api_list']= $this->assign_data['base_api_list'];
        }
        foreach ($this->assign_data['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }
        $this->assign_data['js_api_list']= substr($js_api_list, 0, -1);

        //主动显示某些菜单
        if( !isset($this->assign_data['js_menu_show']) )
            $this->assign_data['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
        foreach ($this->assign_data['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $this->assign_data['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        if( !isset($this->assign_data['js_menu_hide']) )
            $this->assign_data['js_menu_hide']= array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl', 'menuItem:share:email', 'menuItem:originPage' );
        foreach ($this->assign_data['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $this->assign_data['js_menu_hide']= substr($menu_hide_list, 0, -1);

        if(isset($this->dis_conf) && !empty($this->dis_conf)){
            if($this->dis_conf['face_invite_config']['action_toface']=='custom')
                $this->dis_conf['face_invite_config']['invite_title'] = $this->dis_conf['face_invite_config']['custom_toface'];
            else
                $this->dis_conf['face_invite_config']['invite_title'] = '当面邀请';

            $this->assign_data['invite_title'] = $this->dis_conf['face_invite_config']['invite_title'];

            if($this->dis_conf['share_config']['action_share']=='custom')
                $this->dis_conf['share_config']['share_title'] = $this->dis_conf['share_config']['custom_share'];
            else
                $this->dis_conf['share_config']['share_title'] = '千里传音';

            $this->assign_data['share_title'] = $this->dis_conf['share_config']['share_title'];

            if($this->dis_conf['center_config']['action_center']=='custom')
                $this->dis_conf['center_config']['my_title'] = $this->dis_conf['center_config']['custom_center'];
            else
                $this->dis_conf['center_config']['my_title'] = '个人战绩';

            $this->assign_data['my_title'] = $this->dis_conf['center_config']['my_title'];

            $this->assign_data['js_share_config']['title'] = $this->dis_conf['share_config']['title_share'];
            $this->assign_data['js_share_config']['link'] = site_url('membervip/invitatedkim/register').'?id='.$this->inter_id.'&share='.$this->assign_data['url_code'];
            $this->assign_data['js_share_config']['imgUrl'] = $this->dis_conf['share_banner'];
            $this->assign_data['js_share_config']['desc'] = $this->dis_conf['share_config']['title2_share'];
        }

        if( !isset($this->assign_data['js_share_config']) )
            $this->assign_data['js_share_config']= FALSE;   //array('title','desc','link','imgUrl')

        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;
        $this->vip_write_log($this->assign_data,'assign_data');

        $params['inter_id']=$this->inter_id;
        $params['activited_id']=isset($info['id'])?$info['id']:'';
        $reward_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        $this->vip_write_log($reward_info,'get_kiminvited_info');
        $this->assign_data['reward_info'] = $reward_info;

        //获取排名
        $params['inter_id'] = $this->inter_id;
        $recommend_info = $this->Kiminvited_model->get_my_ranking($params,$this->vip_user['member_info_id']);
        $this->assign_data['recommend_info'] = $recommend_info;

        //获取当前会员总邀金
        $params['member_info_id']=$this->vip_user['member_info_id'];
        $total_integral = $this->Kiminvited_model->get_total_integral($params);
        $this->assign_data['total_integral'] = (isset($total_integral['reward_value']) && !empty($total_integral['reward_value']))?$total_integral['reward_value']:0;
        $this->assign_data['total_number']=0;
        if(isset($reward_info['mode']) && $reward_info['mode']=='1'){
            $this->assign_data['total_number'] = $this->assign_data['total_integral'];
            $this->assign_data['full_number'] = floatval($reward_info['full_gold']);
        }elseif (isset($reward_info['mode']) && $reward_info['mode']=='2'){
            $this->assign_data['total_number'] = isset($recommend_info['ranking'])?$recommend_info['ranking']:0;
            $this->assign_data['full_number'] = floatval($reward_info['full_rank']);
        }
        $this->template_show('member',$this->_template,'Invitatedkim/raiders',$this->assign_data);
    }

    public function qrcode(){
        $this->load->helper('phpqrcode');
        $url_code = !empty($_GET["url_code"])?$_GET["url_code"]:'';
        $url = site_url('membervip/invitatedkim/register').'?id='.$this->inter_id.'&share='.$url_code.'&channel=1';
        QRcode::png($url,false,'L',6,0,true);
    }

    //登录
    public function login(){
        //获取会员模式
        $_params['inter_id'] = $this->inter_id;
        $_params['type_code'] = 'member';
        $_params['field'] = 'inter_id,value';
        $member_sync = $this->Kiminvited_model->get_kiminvited_info($_params,'inter_member_config');
        $this->assign_data['member_sync'] = $member_sync;

        $login_config = array();
        $web_url = PMS_PATH_URL."adminmember/getloginconfig";
        $request_data = array('inter_id'=>$this->inter_id);
        //请求登录配置
        $request_config = $this->doCurlPostRequest($web_url,$request_data);
        if(isset($request_config['data']) && !empty($request_config['data'])){
            foreach ($request_config['data'] as $key => $item){
                if($item['show']!=1) unset($request_config['data'][$key]);
            }
            $login_config = $request_config['data'];
        }

        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;
        $this->assign_data['login_config'] = $login_config;
        $this->template_show('member',$this->_template,'Invitatedkim/login',$this->assign_data);
    }

    public function subqrcode(){
        $this->load->model ( 'wx/access_token_model' );
        $this->load->helper ( 'common' );
        $share_member_id = !empty($this->input->get('mid'))?$this->input->get('mid'):$this->vip_user['member_info_id'];
        $access_token = $this->access_token_model->get_access_token ( $this->inter_id );
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
        // 临时码
        $qrcode = '{"expire_seconds": 86400,"action_name": "QR_SCENE","action_info": {"scene": {"scene_id": '.intval($share_member_id).'}}}';
        $output = json_decode(doCurlPostRequest ( $url, $qrcode ),true);
        if(isset($output['url'])){
            $this->load->helper('phpqrcode');
            QRcode::png($output['url'],false,'L',6,0,true);
        }
    }

    //新用户注册完成引导关注页面
    public function subscribe(){
        $wxuser_info = $this->Member_model->get_wxuser_info($this->inter_id,$this->openid);
        $this->vip_write_log($wxuser_info,$this->inter_id.'subscribe_share');
        if(isset($wxuser_info['subscribe']) && $wxuser_info['subscribe']=='1'){
            redirect('membervip/invitatedkim/index?id='.$this->inter_id);
        }
        $member_info_id = $this->input->get('mid');
        $user_info = $this->Member_model->get_member_info($member_info_id);
        $this->assign_data['user_info'] = $user_info;
        $this->assign_data['share_member_id'] = $this->input->get('mid');
        $this->template_show('member',$this->_template,'Invitatedkim/subscribe',$this->assign_data);
    }

    //注册
    public function register(){
        $share = isset($_GET['share'])?$_GET['share']:'';
        $check_wxuser = $this->Member_model->check_fans_and_member($this->inter_id,$this->openid);
        $this->vip_write_log($check_wxuser,'check_wxuser');
        $share_memid = 0;
        if(!empty($share)){
            $_share = base64_decode($share);
            $de_memid = $this->Kiminvited_model->vip_authdecode($_share,$this->inter_id);
            $de_memid = explode('|||', $de_memid);
            $share_memid = !empty($de_memid[0])?$de_memid[0]:'';
        }
        if($check_wxuser['status']=='3'){
            redirect('membervip/invitatedkim/subscribe?id='.$this->inter_id.'&mid='.$share_memid);
        }elseif ($check_wxuser['status']=='ok'){
            redirect('membervip/invitatedkim/index?id='.$this->inter_id);
        }

        $channel = !empty($this->input->get('channel'))?$this->input->get('channel'):'1';
        $this->assign_data['share_memid'] = $share_memid;
        $this->assign_data['share'] = $share;
        $this->assign_data['channel'] = $channel;
        $this->vip_write_log($share,'register_share');
        $_params['inter_id'] = $this->inter_id;
        $_params['type_code'] = 'member';
        $_params['field'] = 'inter_id,value';
        $member_sync = $this->Kiminvited_model->get_kiminvited_info($_params,'inter_member_config');
        $this->assign_data['member_sync'] = $member_sync;
        $this->assign_data['save_name'] = '创建帐号';
        $this->assign_data['save_msg'] = '创建';
        $this->assign_data['save_url'] = site_url("membervip/reg/savereg");

        $web_url = PMS_PATH_URL."adminmember/getregconfig";
        if(isset($member_sync['value']) && $member_sync['value']=='perfect'){
            if($this->vip_user['cellphone']) $this->assign_data['save_user'] = $this->vip_user;
            $this->assign_data['save_name'] = '提交信息';
            $this->assign_data['save_msg'] = '保存';
            $this->assign_data['save_url'] = site_url("membervip/perfectinfo/save");
            $web_url = PMS_PATH_URL."adminmember/getmodifyconfig";
        }

        $request_data = array('inter_id'=>$this->inter_id);
        $register_config = array();
        //请求注册配置
        $request_config = $this->doCurlPostRequest($web_url,$request_data);
        if(isset($request_config['data']) && !empty($request_config['data'])){
            foreach ($request_config['data'] as $key => $item){
                if($item['show']!=1) unset($request_config['data'][$key]);
            }
            $register_config = $request_config['data'];
        }
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;

        $this->assign_data['register_config'] = $register_config;
        $this->assign_data['inter_id'] = $this->inter_id;
        $this->template_show('member',$this->_template,'Invitatedkim/register',$this->assign_data);
    }

    //邀金明细
    public function pointdetail(){
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;

        //获取奖励设置
        $params['inter_id']=$this->inter_id;
        $params['activited_id']=isset($info['id'])?$info['id']:'';
        $reward_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        $this->assign_data['reward_info'] = $reward_info;
        $this->vip_write_log($reward_info,'get_kiminvited_info');

        //获取当前会员总活动邀金
        $integral = 0;
        $params['reward_type']='1';
        $params['member_info_id']=$this->vip_user['member_info_id'];
        $total = $this->Kiminvited_model->get_total_integral($params);
        $this->vip_write_log($total,'get_total_integral');

        $this->assign_data['total_number'] = (isset($total['reward_value']) && !empty($total['reward_value']))?$total['reward_value']:0;

        //获取已使用邀金
        $params['reward_type'] = '1';
        $use_number = $this->Kiminvited_model->get_use_integral($params);
        $this->vip_write_log($params,'get_use_params');

        $this->vip_write_log($use_number,'get_use_integral');

        $this->assign_data['use_number'] = $use_number;

        //可用邀金
        $isuse_num = floatval($this->assign_data['total_number']) - floatval($use_number);
        $integral = floatval($isuse_num);
        $this->assign_data['isuse_num'] = $integral;

        //兑换记录
        $exchange_record = $this->Kiminvited_model->get_exchange_record($params);
        $this->assign_data['exchange_record'] = $exchange_record;
        $this->template_show('member',$this->_template,'Invitatedkim/pointdetail',$this->assign_data);
    }

    //我的奖励
    public function reward(){
        $info = $this->Kiminvited_model->get_recent_activity($this->inter_id);
        if(!empty($info)) $this->assign_data['info'] = $info;
        $params['inter_id']=$this->inter_id;
        $params['activited_id']=isset($info['id'])?$info['id']:'';
        $params['member_info_id']=$this->vip_user['member_info_id'];
        $reward_record = $this->Kiminvited_model->get_reward_record($params);
        $this->assign_data['reward_record'] = $reward_record;
        $this->template_show('member',$this->_template,'Invitatedkim/reward',$this->assign_data);
    }

    protected function check_login(){
        if($this->vip_user['member_mode']==2 && $this->vip_user['is_login']=='t'){
            return true;
        }else{
            return false;
        }
        return false;
    }

    public function handle_trans(){
        $return['status']=0;
        $return['issend'] = 2;
        $return['data'] = array();
        $return['data']['is_package'] = 2;
        $post = $this->input->post();
        $share = isset($post['share'])?base64_decode($post['share']):'';
        $actId = isset($post['actId'])?$post['actId']:0;
        $channel = isset($post['channel'])?$post['channel']:1;

        if(empty($actId)) {
            $return['msg']='活动还没开始';
            echo json_encode($return);exit;
        };
        if(!empty($share)){
            $de_memid = $this->Kiminvited_model->vip_authdecode($share,$this->inter_id);
            $de_memid = explode('|||', $de_memid);
            $this->vip_write_log($de_memid,'de_memid');
            $share_memid = !empty($de_memid[0])?$de_memid[0]:'';
            if(!isset($de_memid[1])){
                $user_info = $this->Kiminvited_model->get_member_info($share_memid);
                $share_openid = isset($user_info['open_id'])?$user_info['open_id']:'';
            }else{
                $share_openid = !empty($de_memid[1])?$de_memid[1]:'';
            }
        }
        $this->vip_write_log($post,'POST');
        $record_data = array(
            'inter_id'=>$this->inter_id,
            'activited_id'=>$actId,
            'touser_id'=>$this->vip_user['member_info_id'],
            'to_openid'=>$this->openid,
            'fromuser_id'=>$share_memid,
            'from_openid'=>$share_openid,
            'reg_time'=>$this->vip_user['createtime'],
            'channel'=>$channel
        );
        $record_id = $this->Kiminvited_model->add_data($record_data,'kiminvited_record');
        $this->vip_write_log($record_id,'kiminvited_record');
//        if(empty($post))
        $params['inter_id']=$this->inter_id;
        $params['activited_id']=$actId;
        $reward_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        $this->vip_write_log($reward_info,'reward_info');
        if(empty($reward_info)){
            $return['msg']='奖励信息未配置';
            echo json_encode($return);exit;
        }

        if(isset($reward_info['old_reward_type']) && (!empty($reward_info['old_reward_type']) && $reward_info['old_reward_type']!='0')){
            $old_reward_data = array(
                'inter_id'=>$this->inter_id,
                'activited_id'=>$actId,
                'member_type'=>2,
                'reward_type'=>$reward_info['old_reward_type'],
                'reward_value'=>$reward_info['old_reward_value'],
                'member_info_id'=>$share_memid,
                'record_id'=>$record_id,
                'createtime'=>time()
            );
            //处理旧会员奖励
            $reward_data = $old_reward_data;
            $reward_data['openid'] = $share_openid;
            $reward_data['invite_gold'] = $reward_info['invite_gold'];
            $reward_data['invited_userid'] = $this->vip_user['member_info_id'];
            $reward_res = $this->Kiminvited_model->handle_rewards($reward_data);
            $this->vip_write_log($reward_res,'old_reward_record');
            if($reward_res['code']=='100'){
                $result = $this->Kiminvited_model->add_data($old_reward_data,'kiminvited_reward_record');
                $this->vip_write_log($result,'old_reward_record');
            }
        }

        if(isset($reward_info['new_reward_type']) && (!empty($reward_info['new_reward_type']) && $reward_info['new_reward_type']!='0')){
            $new_reward_data = array(
                'inter_id'=>$this->inter_id,
                'activited_id'=>$actId,
                'member_type'=>1,
                'reward_type'=>$reward_info['new_reward_type'],
                'reward_value'=>$reward_info['new_reward_value'],
                'member_info_id'=>$this->vip_user['member_info_id'],
                'record_id'=>$record_id,
                'createtime'=>time()
            );

            //处理新会员奖励
            $reward_data = $new_reward_data;
            $reward_data['openid'] = $this->openid;
            $reward_data['invite_gold'] = $reward_info['invite_gold'];
            $reward_res = $this->Kiminvited_model->handle_rewards($reward_data);
            $this->vip_write_log($reward_res,'new_reward_record');
            if($reward_res['code']=='100'){
                $result = $this->Kiminvited_model->add_data($new_reward_data,'kiminvited_reward_record');
                $this->vip_write_log($result,'new_reward_record');
            }
        }


        //添加活动邀金记录
        $_request['inter_id'] = $this->inter_id;
        $_request['openid'] = $this->openid;
        $_request['invited_userid'] = $this->vip_user['member_info_id'];
        $_request['member_info_id'] = $share_memid;
        $_request['credit_value'] = isset($reward_info['invite_gold'])?floatval($reward_info['invite_gold']):0;
        $_request['activited_id'] = $actId;
        $_request['createtime'] = time();
        $result = $this->Kiminvited_model->add_data($_request,'kiminvited_credits_value');
        $this->vip_write_log($result,'add_data_credits_value');

        $return['status']=1;
        $return['msg']='ok';
        $return['issend']=isset($reward_res['issend'])?$reward_res['issend']:2;
        $return['data']=isset($reward_res['data'])?$reward_res['data']:array();
        $return['data']['is_package'] = 2;
        echo json_encode($return);exit;
    }

    /**
     * 发送模版消息
     */
    public function send_tmp_msg(){
        $get = $_GET;
        $retrun['code'] = '501';
        $retrun['msg'] = '参数不正确';
        $inter_id = isset($get['inter_id'])?$get['inter_id']:'';
        $openid = isset($get['openid'])?$get['openid']:'';
        $name = isset($get['name'])?$get['name']:'微信用户';
        $count = isset($get['count'])?$get['count']:1;
        $curtime = isset($get['curtime'])?$get['curtime']:time();
        $type = isset($get['type'])?$get['type']:'';
        $is_package = isset($get['is_package'])?$get['is_package']:'2';
        if(empty($type)){
            $retrun['msg'] = '发送类型不存在';
            $retrun['code'] = '502';
        }
        //碧桂园发送模版消息
        if($inter_id=='a421641095'){
            $subdata['name'] = $name;
            $subdata['count'] = $count;
            $subdata['curtime'] = $curtime;
            $retrun =  $this->Kiminvited_model->_send_tmp($inter_id,$openid,$subdata,$type);
            $this->vip_write_log($retrun,'_send_tmp');

            if($is_package=='1'){
                $subdata['name'] = isset($get['regname'])?$get['regname']:'微信用户';
                $subdata['count'] = 1;
                $subdata['curtime'] = time();
                $retrun =  $this->Kiminvited_model->_send_tmp($this->inter_id,$this->openid,$subdata,5);
                $this->Kiminvited_model->_write_log($retrun,'reg_send_tmp');
            }
        }
        echo json_encode($retrun);
    }

    /**
     * 积分兑换事务
     */
    public function exchange_reward(){
        $return['msg']='fail';
        $return['status']=0;
        $post = $this->input->post();
        $this->vip_write_log($post,'exchange_reward_post');
        if(empty($post['activited_id'])) {
            $return['msg'] = '缺少活动id';
            echo json_encode($return);exit;
        }
        $params = $post;
        $params['inter_id'] = $this->inter_id;
        $params['openid'] = $this->openid;
        //获取奖励设置
        $where['inter_id']=$params['inter_id'];
        $where['activited_id']=isset($post['activited_id'])?$post['activited_id']:'';
        $reward_info = $this->Kiminvited_model->get_kiminvited_info($where,'kiminvited_reward');
        $this->vip_write_log($reward_info,'exchange_reward_info');

        //获取当前会员总活动邀金
        $integral = 0;
        $params['reward_type']='1';
        $params['activited_id']=isset($post['activited_id'])?$post['activited_id']:0;
        $params['member_info_id']=$this->vip_user['member_info_id'];
        $total = $this->Kiminvited_model->get_total_integral($params);
        $total_num = (isset($total['reward_value']) && !empty($total['reward_value']))?$total['reward_value']:0;

        //获取已使用邀金
        $params['reward_type'] = '1';
        $use_number = $this->Kiminvited_model->get_use_integral($params);

        //可用邀金
        $isuse_num = floatval($total_num) - floatval($use_number);

        $condition = (isset($reward_info['full_gold']) && !empty($reward_info['full_gold']))?$reward_info['full_gold']:0;

        if(!isset($reward_info['full_gold']) || floatval($reward_info['full_gold']) < 1){
            $return['msg'] = '兑换条件不足';
            echo json_encode($return);exit;
        }

        if(floatval($isuse_num)<floatval($condition)){
            $return['msg'] = '邀金不足';
            echo json_encode($return);exit;
        }
        $params['integral'] = $integral;
        $params['member_info_id']=$this->vip_user['member_info_id'];
        $params['value'] = (isset($reward_info['exchange_card']) && !empty($reward_info['exchange_card']))?$reward_info['exchange_card']:0;
        $params['condition'] = $condition;
        //兑换操作
        $res = $this->Kiminvited_model->exchange_reward($params);
        if($res['status']=='1'){
            $return['status']=1;
            $return['msg'] = '亲，您已成功兑换了一张'.$res['name'].'，请前往会员中心查看使用';
            echo json_encode($return);exit;
        }
        $return['msg'] = '兑换失败';
        echo json_encode($return);exit;
    }

    public function _check_exreward($reward_info=array(),$integral=0){
        $return['is_exrw'] = 0;
        if(empty($reward_info)) return $return;
        if(isset($reward_info['mode']) && $reward_info['mode']=='1' && $integral>=floatval($reward_info['full_gold'])){
            $return['is_exrw'] = 1;
            return $return;
        }
        return $return;
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

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function vip_write_log($content,$type='request',$dirpath='Invitatedkim/'){
        if(is_array($content) || is_object($content)) $content = json_encode($content);
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'membervip'. DS.$dirpath;
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
?>