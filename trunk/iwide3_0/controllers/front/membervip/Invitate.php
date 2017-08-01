<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	邀请好友中心
*	@author  liwensong
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 171708252@qq.com
*/
class Invitate extends MY_Front_Member
{
    protected $vip_user = array();
    protected $user_id = 0;
    protected $view_conf = array();
    protected $assign_data = array();
    public function __construct(){
        parent::__construct();
        $this->load->model('membervip/common/Public_model','pm');
        $this->load->model('membervip/front/Member_model','m_model');

        $this->vip_user = $this->m_model->get_user_info($this->inter_id,$this->openid);

        $view_conf = $this->pm->get_info(array('inter_id'=>$this->inter_id),'invite_show_conf');
        if(!empty($view_conf)){
            foreach ($view_conf as &$v){ //转换为数组
                json_decode($v);
                if(json_last_error() == JSON_ERROR_NONE){
                    $v = json_decode($v,true);
                }
            }
        }

        $this->assign_data['vip_user'] = $this->vip_user;
        $this->assign_data['view_conf'] = $view_conf;
        $this->assign_data['inter_id'] = $this->inter_id;
        $this->assign_data['openid'] = $this->openid;
        $this->_template = 'phase2';
        $this->load->library("MYLOG");
        $this->load->model('wx/access_token_model');
        $this->assign_data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
    }

    //领取后页面
    public function success(){
        $msg = !empty($_SESSION[$this->inter_id.$this->openid.'invitate_msg'])?$_SESSION[$this->inter_id.$this->openid.'invitate_msg']:'领取失败';
        $this->assign_data['msg2'] = '';
        if(strpos($msg,'|')!==FALSE){
            $msg = explode('|',$msg);
            $this->assign_data['msg'] = $msg[0];
            $this->assign_data['msg2'] = $msg[1];
        }else{
            $this->assign_data['msg'] = $msg;
        }
        $this->template_show('member',$this->_template,'invitate/success',$this->assign_data);
    }

    //活动、领取相关信息不通过验证
    public function fail(){
        $msg = !empty($_SESSION[$this->inter_id.$this->openid.'invitate_msg'])?$_SESSION[$this->inter_id.$this->openid.'invitate_msg']:'验证失败！';
        $this->assign_data['msg2'] = '';
        if(strpos($msg,'|')!==FALSE){
            $msg = explode('|',$msg);
            $this->assign_data['msg'] = $msg[0];
            $this->assign_data['msg2'] = $msg[1];
        }else{
            $this->assign_data['msg'] = $msg;
        }
        $this->template_show('member',$this->_template,'invitate/fail',$this->assign_data);
    }

    //活动相关信息不通过验证
    public function end(){
        $msg = !empty($_SESSION[$this->inter_id.$this->openid.'invitate_msg'])?$_SESSION[$this->inter_id.$this->openid.'invitate_msg']:'验证失败！';
        $this->assign_data['msg'] = $msg;
        $this->template_show('member',$this->_template,'invitate/end',$this->assign_data);
    }

    //首页
	public function index(){
	    //邀请活动配置
        $invite_settings = $this->pm->get_info(array('inter_id'=>$this->inter_id,'is_active'=>'t'),'invite_settings');
        if(empty($invite_settings)){ //活动不存在
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '活动不存在';
            redirect('membervip/invitate/end?id='.$this->inter_id);
        }

        $this->assign_data['invite_settings'] = $invite_settings;

        if(!empty($invite_settings)){
            if($invite_settings['is_open']=='f' || $invite_settings['is_active']=='f'){
                $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '活动还没开始';
                redirect('membervip/invitate/end?id='.$this->inter_id);
            }

            if($invite_settings['expiretime'] < time()){
                $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '活动已过期';
                redirect('membervip/invitate/end?id='.$this->inter_id);
            }
        }

        //平台等级配置
        $_member_lvl = $this->pm->get_list(array('inter_id'=>$this->inter_id),'member_lvl','member_lvl_id,lvl_name,lvl_up_sort,is_default,lvl_icon');
        if(empty($_member_lvl)){ //平台等级配置不存在
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '活动不存在';
            redirect('membervip/invitate/end?id='.$this->inter_id);
        }

        $member_lvl = array();
        foreach ($_member_lvl as $k => $lvl){
            $member_lvl[$lvl['member_lvl_id']]['member_lvl_id'] = $lvl['member_lvl_id'];
            $member_lvl[$lvl['member_lvl_id']]['lvl_name'] = $lvl['lvl_name'];
            $member_lvl[$lvl['member_lvl_id']]['lvl_icon'] = $lvl['lvl_icon'];
            $member_lvl[$lvl['member_lvl_id']]['is_default'] = $lvl['is_default'];
            $member_lvl[$lvl['member_lvl_id']]['lvl_up_sort'] = $lvl['lvl_up_sort'];
        }
        $this->load->helper('common_helper');
        uasort($member_lvl,"my_sort"); //对分组排序，由小到大根据键值排序
        $this->assign_data['member_lvl'] = $member_lvl;

        $level_equity = $this->pm->get_info(array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']),'invite_level_equity');

        if(empty($level_equity)){ //邀请权益不存在
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '活动不存在';
            redirect('membervip/invitate/end?id='.$this->inter_id);
        }

        $level_equity['hold_lvl_group'] = json_decode($level_equity['hold_lvl_group'],true);

//        $invited_code = sha1($this->vip_user['member_info_id'].'.'.$lvl_id.'.'.$invite_settings['id']);
        $where = array(
            'inter_id'=>$this->inter_id,
            'act_id'=>$invite_settings['id'],
            'member_info_id'=>$this->vip_user['member_info_id'],
            'member_lvl_id'=>$this->vip_user['member_lvl_id']
        );
        $invited_record = $this->pm->get_list($where,'member_invited_record');
        $_invited_record = array();
        if(!empty($invited_record)){
            //重组数据
            foreach ($invited_record as $item){
                $_invited_record[$item['lvl_id']] = $item;
            }
        }

        $_lvl_group = array();
        foreach ($level_equity['hold_lvl_group'] as $k=>$vo){
            $kvo = array_keys($vo);
            $_lvl_group = array_merge($kvo,$_lvl_group);
        }
        $lvl_group = array_unique($_lvl_group);
        MYLOG::w(json_encode(array($_lvl_group,$lvl_group)),'membervip/invitate','start_lvl_group');

        $this->assign_data['lvl_group'] = $lvl_group;

        $this->assign_data['global_lvl_group'] = $level_equity['hold_lvl_group'];

        $hold_lvl_group = !empty($level_equity['hold_lvl_group'][$this->vip_user['member_lvl_id']])?$level_equity['hold_lvl_group'][$this->vip_user['member_lvl_id']]:array();

        $_hold_lvl_group = array();
        foreach ($hold_lvl_group as $k => $c){ //获取每个等级可邀请数量
            $_hold_lvl_group[$k]['total'] = $c;
            $_hold_lvl_group[$k]['count'] = $c;
            if(isset($_invited_record[$k])) {
                $_hold_lvl_group[$k]['count'] = $_invited_record[$k]['count'];
            }
        }
        MYLOG::w(json_encode(array('res'=>$_hold_lvl_group)),'membervip/invitate','_hold_lvl_group');

        if(!empty($this->assign_data['view_conf'])){
            if($this->assign_data['view_conf']['activity_rule']['button_type']=='default')
                $this->assign_data['view_conf']['activity_rule']['button_value'] = '查看活动规则';
        }

        $this->assign_data['hold_lvl_group'] = $_hold_lvl_group;
        $this->assign_data['js_menu_show']= "'menuItem:setFont','menuItem:favorite','menuItem:copyUrl'";
        $this->assign_data['js_menu_hide']= "'menuItem:share:appMessage','menuItem:share:timeline'";
        $this->template_show('member',$this->_template,'invitate/index',$this->assign_data);
    }

    //邀请分享页面
    public function share(){
        $lvlcode = $this->input->get('c');
        $channel = !empty($this->input->get('channel'))?$this->input->get('channel'):'share';
        $checkinfo = $this->check_can_share($lvlcode,$channel);
        if(!$checkinfo['status'] || floatval($checkinfo['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $checkinfo['msg'];
            redirect($checkinfo['url']);
        }

        $lvl_name = !empty($checkinfo['lvlname'])?$checkinfo['lvlname']:'';
        $this->assign_data['lvl_name'] = $lvl_name;
        $this->assign_data['code'] = $checkinfo['data'];
        $this->assign_data['lvlcode'] = $lvlcode;
        $this->assign_data['view_conf']['invited_share']['title'] = str_replace(array('{invitename}','{invitelevel}'), array($this->vip_user['name'],$lvl_name),$this->assign_data['view_conf']['invited_share']['title']);

        $this->assign_data['view_conf']['invited_share']['sub_title'] = str_replace(array('{invitename}','{invitelevel}'), array($this->vip_user['name'],$lvl_name),$this->assign_data['view_conf']['invited_share']['sub_title']);

        $this->assign_data['js_menu_show']= "'menuItem:share:appMessage','menuItem:share:timeline'";
        $this->assign_data['js_menu_hide']= "'menuItem:favorite','menuItem:copyUrl'";
        $url = site_url('membervip/invitate/register').'?id='.$this->inter_id.'&share_key='.$checkinfo['data'];
        $this->assign_data['js_share_config']['link'] = $url;
        $_js_api_list = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        $js_api_list = '';
        foreach ($_js_api_list as $v){
            $js_api_list.= "'{$v}',";
        }
        $this->assign_data['js_api_list']= substr($js_api_list, 0, -1);
        $this->template_show('member',$this->_template,'invitate/share',$this->assign_data);
    }

    //注册领取页面
    public function register(){
        $code = $this->input->get('share_key');

        //获取会员模式
        $where = array('inter_id'=>$this->inter_id,'type_code'=>'member');
        $member_config = $this->pm->get_info($where,'inter_member_config','inter_id,value');
        if(empty($member_config)) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '会员模式不正确，请联系管理员';
            site_url('membervip/invitate/end?id='.$this->inter_id);
        }

        if($member_config['value']=='perfect') redirect(site_url('membervip/invitate/perfect?id='.$this->inter_id.'&share_key='.$code));

        $this->assign_data['member_config'] = $member_config;

        $check_receive = $this->check_receive($code,$member_config);
        if(!$check_receive['status'] || floatval($check_receive['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $check_receive['msg'];
            redirect($check_receive['url']);
        }

        $web_url = PMS_PATH_URL."adminmember/getregconfig";
        $request_data = array('inter_id'=>$this->inter_id);
        //请求注册配置
        $reg_conf = array();
        $request_config = $this->doCurlPostRequest($web_url,$request_data);
        if(!empty($request_config['data'])){
            foreach ($request_config['data'] as $key => $item){
                if($item['show']!=1) unset($request_config['data'][$key]);
            }
            $reg_conf = $request_config['data'];
        }
        $this->assign_data['reg_conf'] = $reg_conf;
        $this->assign_data['code'] = $code;
        $this->assign_data['save_url'] = site_url('membervip/api/invitate/reg_receive_execute?id='.$this->inter_id);
        $this->template_show('member',$this->_template,'invitate/register',$this->assign_data);
    }

    //登录领取页面
    public function login(){
        $code = $this->input->get('share_key');
        $check_receive = $this->check_receive($code);
        if(!$check_receive['status'] || floatval($check_receive['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $check_receive['msg'];
            redirect($check_receive['url']);
        }
        //获取会员模式
        $where = array('inter_id'=>$this->inter_id,'type_code'=>'member');
        $member_config = $this->pm->get_info($where,'inter_member_config','inter_id,value');
        if(empty($member_config)) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '会员模式不正确，请联系管理员';
            site_url('membervip/invitate/end?id='.$this->inter_id);
        }
        $this->assign_data['member_config'] = $member_config;

        $web_url = PMS_PATH_URL."adminmember/getloginconfig";
        $request_data = array('inter_id'=>$this->inter_id);
        //请求注册配置
        $login_conf = array();
        $request_config = $this->doCurlPostRequest($web_url,$request_data);
        if(!empty($request_config['data'])){
            foreach ($request_config['data'] as $key => $item){
                if($item['show']!=1) unset($request_config['data'][$key]);
            }
            $login_conf = $request_config['data'];
        }
        $this->assign_data['login_conf'] = $login_conf;
        $this->assign_data['code'] = $code;
        $this->assign_data['save_url'] = site_url('membervip/api/invitate/login_receive_execute?id='.$this->inter_id);
        $this->template_show('member',$this->_template,'invitate/login',$this->assign_data);
    }

    //登录领取页面
    public function perfect(){
        //获取会员模式
        $where = array('inter_id'=>$this->inter_id,'type_code'=>'member');
        $member_config = $this->pm->get_info($where,'inter_member_config','inter_id,value');
        if(empty($member_config)) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = '会员模式不正确，请联系管理员';
            site_url('membervip/invitate/end?id='.$this->inter_id);
        }
        $this->assign_data['member_config'] = $member_config;

        $code = $this->input->get('share_key');
        $check_receive = $this->check_receive($code);
        if(!$check_receive['status'] || floatval($check_receive['status'])>1) {
            $_SESSION[$this->inter_id.$this->openid.'invitate_msg'] = $check_receive['msg'];
            redirect($check_receive['url']);
        }

        $web_url = PMS_PATH_URL."adminmember/getmodifyconfig";
        $request_data = array('inter_id'=>$this->inter_id);
        //请求完善资料配置
        $perfect_conf = array();
        $request_config = $this->doCurlPostRequest($web_url,$request_data);
        if(!empty($request_config['data'])){
            foreach ($request_config['data'] as $key => $item){
                if($item['show']!=1) unset($request_config['data'][$key]);
            }
            $perfect_conf = $request_config['data'];
        }

        foreach ($perfect_conf as $k => &$data){
            if($k=='birthday') $data['type'] = 'date';
        }

        if(!empty($this->assign_data['vip_user']['birth']))
            $this->assign_data['vip_user']['birth'] = date('Y-m-d',$this->assign_data['vip_user']['birth']);
        else
            $this->assign_data['vip_user']['birth'] = date('Y-m-d');

        //解析字段映射
        $mapping = array(
            'phone'=>'cellphone',
            'birthday'=>'birth',
            'idno'=>'id_card_no',
        );
        foreach ($mapping as $k =>$v){
            if(isset($this->assign_data['vip_user'][$v])) $this->assign_data['vip_user'][$k] = $this->assign_data['vip_user'][$v];
        }

        MYLOG::w(json_encode(array('vip_user'=>$this->assign_data['vip_user'],'perfect_conf'=>$perfect_conf)),'membervip/invitate/perfect','assign_data_vip_user');


        $this->assign_data['perfect_conf'] = $perfect_conf;
        $this->assign_data['code'] = $code;
        $this->assign_data['save_url'] = site_url('membervip/api/invitate/perfect_receive_execute?id='.$this->inter_id);
        $this->template_show('member',$this->_template,'invitate/perfect',$this->assign_data);
    }

    //检测是否可以邀请
    protected function check_can_share($lvlcode='',$channel='share'){
        $jump_url = site_url('membervip/invitate/fail?id='.$this->inter_id);
        if(empty($lvlcode)) return array('msg'=>'很抱歉，邀请失败，请刷新后试试！','url'=>$jump_url,'status'=>0);
        //邀请活动配置
        $invite_settings = $this->pm->get_info(array('inter_id'=>$this->inter_id,'is_active'=>'t'),'invite_settings');
        MYLOG::w(json_encode(array('data'=>$invite_settings,'where'=>array('inter_id'=>$this->inter_id,'is_active'=>'t'))),'membervip/invitate/check_can_share','start_invite_settings');
        if(empty($invite_settings)) return array('msg'=>'活动不存在，请联系管理员','url'=>$jump_url,'status'=>0); //邀请活动配置不存在

        //平台等级配置
        $member_lvl = $this->pm->get_field_by_level_config($this->inter_id,'member_lvl_id,lvl_name');
        MYLOG::w(json_encode(array('data'=>$member_lvl,'inter_id'=>$this->inter_id)),'membervip/invitate/check_can_share','start_member_lvl');
        if(empty($member_lvl)) return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //没有配置平台等级

        if(empty($member_lvl[$this->vip_user['member_lvl_id']])) return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //匹配平台等级失败

        $level_equity = $this->pm->get_info(array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']),'invite_level_equity');
        MYLOG::w(json_encode(array('data'=>$level_equity,'where'=>array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']))),'membervip/invitate/check_can_share','start_level_equity');
        if(empty($level_equity)) return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //邀请权益不存在

        $hold_lvl_group = json_decode($level_equity['hold_lvl_group'],true);
        if(empty($hold_lvl_group[$this->vip_user['member_lvl_id']])) return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //邀请权益匹配不成功

        $lvl_group_arr = $hold_lvl_group[$this->vip_user['member_lvl_id']];
        $lvl_id = 0;
        foreach ($lvl_group_arr as $k=>$c){
            if($lvlcode==md5($k)) $lvl_id = $k;
        }
        MYLOG::w(json_encode(array('lvl_group_arr'=>$lvl_group_arr,'lvlcode'=>$lvlcode,'lvl_id'=>$lvl_id,'member_lvl'=>$member_lvl)),'membervip/invitate/check_can_share','lvl_group_arr');

        if(empty($member_lvl[$lvl_id])) return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //匹配邀请等级失败

        if(empty($hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]) || $hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]==0) return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //邀请等级匹配不成功或者可邀请次数为0

        $invited_code = sha1($this->vip_user['member_info_id'].'.'.$this->vip_user['member_lvl_id'].'.'.$lvl_id.'.'.$invite_settings['id']);
        $where = array('code'=>$invited_code);
        $invited_record = $this->pm->get_info($where,'member_invited_record');
        MYLOG::w(json_encode(array('data'=>$invited_record,'where'=>$where)),'membervip/invitate/check_can_share','start_invited_record');
        if(!empty($invited_record)){
            if($invited_record['inter_id']!=$this->inter_id || $invited_record['act_id']!=$invite_settings['id'] || $invited_record['lvl_id']!=$lvl_id || $invited_record['member_info_id']!=$this->vip_user['member_info_id']){
                return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0); //验证不通过
            }

            if($invited_record['count']<=0) return array('msg'=>'您的'.$member_lvl[$lvl_id].'邀请资格已经用完','url'=>$jump_url,'status'=>0);
        }

        $redis_code = $this->session->tempdata($this->vip_user['member_info_id'].$lvl_id.$invite_settings['id'].'invitate_code');
        MYLOG::w(json_encode(array('inter_id'=>$this->inter_id,'member_info_id'=>$this->vip_user['member_info_id'],'code'=>$redis_code)),'membervip/api/invitate','start_redis_code');
        if(!empty($redis_code)) return array('msg'=>'邀请成功!','status'=>1,'data'=>$redis_code,'lvlname'=>$member_lvl[$lvl_id]);

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

        $this->pm->_shard_db()->trans_begin(); //开启事务
        $srand = $this->vip_user['member_info_id'].time();
        $code = $this->pm->randCode(16,$srand); //获取唯一随机码
        try{
            $content = $this->vip_user['member_info_id'].'.'.$lvl_id.'.'.$invite_settings['id'].'.'.$channel; //邀请者的邀请信息
            $save_initiate = array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id'],'content'=>$content,'type'=>3,'code'=>$code,'createtime'=>time(),'expiretime'=>strtotime('+48 hours'));
            if(!empty($invited_record)) {
                $invited_count = intval($hold_lvl_group[$this->vip_user['member_lvl_id']][$lvl_id]);
                if($invited_count<=0) return array('msg'=>'您的'.$member_lvl[$lvl_id].'邀请资格已用完','url'=>$jump_url,'status'=>0); //可邀请次数为0
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
            $this->pm->_shard_db()->trans_commit();// 事务提交
        }catch (Exception $e){
            $this->pm->_shard_db()->trans_rollback(); //回滚事务
            return array('msg'=>'很抱歉，邀请失败，请联系管理员','url'=>$jump_url,'status'=>0);
        }

        $this->session->set_tempdata($this->vip_user['member_info_id'].$lvl_id.$invite_settings['id'].'invitate_code',$code,300);
        return array('msg'=>'邀请成功!','status'=>1,'data'=>$code,'lvlname'=>$member_lvl[$lvl_id]);
    }

    //检测领取场景和领取信息是否合格
    protected function check_receive($code='',$member_config=array()){
        $jump_url = site_url('membervip/invitate/fail?id='.$this->inter_id);

        //会员邀请凭证记录
        $member_initiate = $this->pm->get_info(array('code'=>$code),'member_initiate');
        MYLOG::w(json_encode(array('data'=>$member_initiate,'code'=>$code)),'front/membervip/invitate', 'check_member_initiate');
        if(empty($member_initiate)){ //不存在邀请凭证记录
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>101);
        }

        //邀请活动配置
        $invite_settings = $this->pm->get_info(array('inter_id'=>$this->inter_id,'is_active'=>'t'),'invite_settings');
        MYLOG::w(json_encode(array('data'=>$invite_settings,'where'=>array('inter_id'=>$this->inter_id,'is_active'=>'t'))),'front/membervip/invitate', 'check_invite_settings');
        if(empty($invite_settings)) {//邀请活动配置不存在
            return array('url'=>$jump_url,'msg'=>'很抱歉，活动已终止','status'=>102);
        }

        //平台等级配置
        $member_lvl = $this->pm->get_field_by_level_config($this->inter_id,'member_lvl_id,lvl_name,lvl_up_sort');
        MYLOG::w(json_encode(array('data'=>$member_lvl,'inter_id'=>$this->inter_id)),'front/membervip/invitate', 'check_member_lvl');
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

        $invite_user_lvl = $member_lvl[$content[1]]; //邀请等级

        //邀请者会员信息
        $member_info = $this->m_model->get_member_info($content[0],'member_info_id,member_lvl_id,name,nickname');
        MYLOG::w(json_encode(array('data'=>$member_info,'content'=>$content)),'front/membervip/invitate', 'check_member_info');
        if(empty($member_info)){
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>106);
        }

        if(empty($member_lvl[$member_info['member_lvl_id']])){ //不存在邀请者的会员等级
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>107);
        }

        if(empty($member_lvl[$content[1]])){ //不存在邀请的会员等级
            return array('url'=>$jump_url,'msg'=>'很抱歉，邀请已失效','status'=>107);
        }

        $username = empty($member_info['name'])?$member_info['nickname']:$member_info['name'];
        if($member_initiate['expiretime']<=time()){
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的|'.$invite_user_lvl.'资格邀请已过期','status'=>108);
        }

        //邀请权益
        $level_equity = $this->pm->get_info(array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']),'invite_level_equity');
        MYLOG::w(json_encode(array('data'=>$level_equity,'where'=>array('inter_id'=>$this->inter_id,'act_id'=>$invite_settings['id']))),'front/membervip/invitate', 'check_level_equity');
        if(empty($level_equity)) {//邀请权益不存在
            return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>109);
        }

        $hold_lvl_group = json_decode($level_equity['hold_lvl_group'],true);
        if(empty($hold_lvl_group[$member_info['member_lvl_id']])) {//邀请权益匹配不成功
            return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>110);
        }

        if(empty($hold_lvl_group[$member_info['member_lvl_id']][$content[1]])) {//邀请等级匹配不成功或者可邀请次数为0
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的|'.$invite_user_lvl.'资格邀请已失效','status'=>111);
        }

        if($hold_lvl_group[$member_info['member_lvl_id']][$content[1]]==0) {//邀请等级匹配不成功或者可邀请次数为0
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的|'.$invite_user_lvl.'资格邀请无法领取','status'=>112);
        }

        $_code = $member_info['member_info_id'].'.'.$member_info['member_lvl_id'].'.'.$content[1].'.'.$invite_settings['id'];
        $invited_code = sha1($_code);
        $where = array('code'=>$invited_code);
        $invited_record = $this->pm->get_info($where,'member_invited_record'); //用户邀请权益数量记录
        MYLOG::w(json_encode(array('data'=>$invited_record,'where'=>$where,'_code'=>$_code)),'front/membervip/invitate', 'check_invited_record');
        if(empty($invited_record)) return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>113);

        if($invited_record['inter_id']!=$this->inter_id || $invited_record['act_id']!=$invite_settings['id'] || $invited_record['lvl_id']!=$content[1] || $invited_record['member_info_id']!=$member_info['member_info_id']){
            return array('url'=>$jump_url,'msg'=>'很抱歉,邀请已失效','status'=>114); //验证不通过
        }

        if($invited_record['count']<=0)
            return array('url'=>$jump_url,'msg'=>'很抱歉，您收到来自'.$username.'的|'.$invite_user_lvl.'资格邀请已被领完','status'=>115); //已经领完了

        return array('data'=>array('content'=>$content,'invite_settings'=>$invite_settings,'member_info'=>$member_info,'member_lvl'=>$member_lvl),'msg'=>'ok','status'=>1);
    }
}
?>