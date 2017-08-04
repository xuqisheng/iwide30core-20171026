<?php

namespace App\services\member;

use App\services\MemberBaseService;

/**
 * Class CardService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 */
class CardService extends MemberBaseService
{

    private   $res_data = array();
    protected $args = array();
    private $assign_data = array();

    //加载基本类、设置基础信息
    public function getBase(){
        $this->getCI()->load->library("MYLOG");
        $this->getCI()->load->helper('common_helper');
        $this->getCI()->load->model('wx/Publics_model','Publics_model');
        $this->res_data = array(
            'status'=>2,
            'data'=>array()
        );
    }

    /**
     * 获取服务实例方法
     * @return CardService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     * 优惠券列表
     * @param string $inter_id 微信酒店集团ID
     * @param string $openid 微信用户ID
     * @param string $template 前端模版名称
     * @param array $url_group 链接组
     * @return array
     */
    public function index($inter_id = '',$openid = '',$template = '',$url_group = array()){
        $this->getBase();
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
        );
        //请求用户登录(默认)会员卡信息
        $member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
        $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
        //获取会员卡券列表
        $do_curl = INTER_PATH_URL."membercard/getcardlist";
        $post_data = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$member_info_id,
            'openid'=>$openid,
            'token'=>'',
            'range'=>'0,100',
            'template'=>$template
        );

        $this->assign_data = $url_group;
        $assign_data = $this->assign_data;

        $card_info = $this->doCurlPostRequest($do_curl,$post_data);
        $assign_data['all'] = array();
        $assign_data['usableCardLists'] = array();
        $assign_data['unusedCardLists'] = array();
        $assign_data['expiredCardLists'] = array();
        $phase2_arr = ['zhouji','phase2','yasite','highclass','green'];
        if(!empty($template) && in_array($template,$phase2_arr)){
            if(isset($card_info['err']) && $card_info['err']=='0'){
                $assign_data['all'] = isset($card_info['return']['all']['data'])?$card_info['return']['all']['data']:array();
                $assign_data['usableCardLists'] = isset($card_info['return']['valids']['data'])?$card_info['return']['valids']['data']:array();
                $assign_data['unusedCardLists'] = isset($card_info['return']['is_use']['data'])?$card_info['return']['is_use']['data']:array();
                $assign_data['expiredCardLists'] = isset($card_info['return']['expired']['data'])?$card_info['return']['expired']['data']:array();

                $assign_data['all'] = $this->parse_card_data($assign_data,'all',$url_group);
                $assign_data['usableCardLists'] = $this->parse_card_data($assign_data,'usableCardLists',$url_group);
                $assign_data['unusedCardLists'] = $this->parse_card_data($assign_data,'unusedCardLists',$url_group);
                $assign_data['expiredCardLists'] = $this->parse_card_data($assign_data,'expiredCardLists',$url_group);
            }
        }else{
            $assign_data['cardlist'] = $card_info['data'];
        }
        $assign_data['public'] = $this->getCI()->Publics_model->get_public_by_id($inter_id);
        $assign_data['next_id'] = isset($card_info['next_id']) ? $card_info['next_id'] : 0;
        $assign_data['inter_id'] = $inter_id;
        $assign_data['page_title'] = '我的优惠券';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $assign_data;
        return $this->res_data;
    }

    private function parse_card_data($card_data = array(),$key = 'usableCardLists',$url_group = array()){

        if(!empty($card_data[$key])){
            foreach ($card_data[$key] as &$v){
                if(!isset($v['is_pms_card'])){
                    $v['cardinfo_url'] = !empty($url_group['cardinfo_url'])?"{$url_group['cardinfo_url']}&member_card_id={$v['member_card_id']}":'';
                }else{
                    $v['cardinfo_url'] = !empty($url_group['pcardinfo_url'])?"{$url_group['pcardinfo_url']}&member_card_id={$v['member_card_id']}":'';
                }

                if (isset($v['card_type'])) {
                    switch ($v['card_type']) {
                        case 1:
                            // 抵用券
                            $v['card_type_name'] = $v['reduce_cost'] . '元';
                            break;
                        case 2:
                            // 折扣券样式
                            $v['card_type_name'] = $v['discount'] . '折';
                            break;
                        case 3:
                            // 兑换券样式
                            $v['card_type_name'] = '兑';
                            break;
                        case 4:
                            // 储值卡样式
                            $v['card_type_name'] = $v['money'] . '元';
                            break;
                        default:
                            //错误卡卷样式
                    }
                }

                $v['expire_time_quantum'] = isset($v['expire_time']) ? date('Y.m.d', $v['receive_time']) . ' - ' . date('Y.m.d', $v['expire_time']) : '';
            }
            return $card_data[$key];
        }
        return array();
    }

    protected function parse_card_info($card_info = array()){
        if(!empty($card_info) && !isset($card_info['is_pms_card'])){
            switch ($card_info['card_type']) {
                case 1:
                    // 抵用券样式
                    $card_info['card_type_name'] = $card_info['reduce_cost'] . '元';
                    break;
                case 2:
                    // 折扣券样式
                    $card_info['card_type_name'] = $card_info['discount'] . '折';
                    break;
                case 3:
                    // 兑换券样式
                    $card_info['card_type_name'] = '兑换券';
                    break;
                case 4:
                    // 储值卡样式
                    $card_info['card_type_name'] = $card_info['money'] . '元';
                    break;
                default:
                    //错误卡卷样式
            }
        }
        return $card_info;
    }

    /**
     * PMS优惠券列表
     * @param string $inter_id 微信酒店集团ID
     * @param string $openid 微信用户ID
     * @param array $url_group 链接组
     * @return array
     */
    public function pcard($inter_id = '',$openid = '',$url_group = array()){
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
        );
        //请求用户登录(默认)会员卡信息
        $member_info = $this->doCurlPostRequest($post_center_url,$post_center_data)['data'];
        $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
        //获取会员卡券列表
        $post_member_card_url = INTER_PATH_URL."membercard/get_pms_card_list";
        $post_member_card_data = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$member_info_id,
            'num'=>30,
        );
        $card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );
        $this->assign_data = $url_group;
        $data = $this->assign_data;
        $centerinfo = $this->parse_curl_msg($card_info);
        $data['cardlist'] = $centerinfo['data'];
        $data['cardlist'] = $this->parse_card_data($data,'cardlist',$url_group);

        $data['next_id'] = isset($card_info['data']['next_id'])?$card_info['data']['next_id']:0;
        $data['inter_id'] = $inter_id;
        $data['page_title'] = '我的房券';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    /**
     * PMS优惠券详情
     * @param string $inter_id 微信酒店集团ID
     * @param string $openid 微信用户ID
     * @param integer $member_card_id 会员领取的优惠券ID
     * @param array $url_group 链接组
     * @return array
     */
    public function pcardinfo($inter_id = '',$openid = '', $member_card_id = 0, $url_group = array()){
        $this->getBase();
        $this->assign_data = $url_group;
        $data = $this->assign_data;
        $my_card = false;
        $auth_useoff = false;
        if(!empty($member_card_id)){
            $post_center_url = PMS_PATH_URL."member/center";
            $post_center_data =  array(
                'inter_id'=>$inter_id,
                'openid' =>$openid,
            );
            //请求用户登录(默认)会员卡信息
            $member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data );
            $member_info = $this->parse_curl_msg($member_info);
            $member_info = $member_info['data'];
            $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
            $member_mode = isset($member_info['member_mode'])?$member_info['member_mode']:'';

            //获取会员卡券列表
            $post_member_card_url = INTER_PATH_URL."membercard/get_pms_card_info";
            $post_member_card_data = array(
                'inter_id'=>$inter_id,
                'member_info_id'=>$member_info_id,
                'member_card_id'=>$member_card_id,
                'num'=>30,
            );
            $card_info = $this->doCurlPostRequest($post_member_card_url, $post_member_card_data);
            $_card_info = $this->parse_curl_msg($card_info);
            $card_info = $_card_info['data'];
            $data['card_info'] = array();
            if(empty($card_info)){
                $data['card_info'] = array();
                $data['next_id'] = 0;
            }else{
                $card_info['expire_time_quantum'] = isset($card_info['expire_time']) ? date('Y.m.d', $card_info['receive_time']) . ' - ' . date('Y.m.d', $card_info['expire_time']) : '';
                $data['card_info'] = $card_info;
                $data['next_id'] = isset($_card_info['next_id'])?$_card_info['next_id']:0;

                $my_member_info_ids = array(
                    $member_info_id
                );
                if($member_mode == 2){
                    $this->getCI()->load->model('membervip/front/Member_model','mem');
                    $extra = array(
                        'member_mode' => 1,
                        'is_login'=>'f'
                    );
                    $fens_user = $this->getCI()->mem->get_user_info($inter_id,$openid,'member_info_id',$extra);
                    if(!empty($fens_user)){
                        $my_member_info_ids[] = $fens_user['member_info_id'];
                    }
                }
                if(in_array($data['card_info']['member_info_id'],$my_member_info_ids)) $my_card = true;

                if($data['card_info']['is_use']=='f' && $data['card_info']['is_useoff']=='f' && $data['card_info']['is_giving']=='f' && $data['card_info']['is_active']=='t' && $my_card === true && ($data['card_info']['card_type'] == 3 OR $data['card_info']['is_online']=='2')){
                    $auth_useoff = true;
                }
            }
        }
        $data['inter_id'] = $inter_id;
        $data['my_card'] = $my_card;
        $data['auth_useoff'] = $auth_useoff;

        //获取优惠券转赠授权信息
        $auth_gift = false;
        $this->getCI()->load->model('membervip/common/Public_model','common_model');
        $where = array(
            'inter_id'=>'ALL_INTER_ID',
            'type_code'=>'member_auth_gift'
        );
        $member_auth_gift = $this->getCI()->common_model->get_info($where,'inter_member_config','value');
        $auth_inter = !empty($member_auth_gift['value'])?explode(',',$member_auth_gift['value']):array();
        if(in_array($inter_id,$auth_inter)){
            $auth_gift = true;
        }

        $data['auth_gift'] = $auth_gift;

        $this->getCI()->load->model('wx/access_token_model');
        $data['signpackage'] = $this->getCI()->access_token_model->getSignPackage($inter_id);
        $data['public'] = $this->getCI()->Publics_model->get_public_by_id($inter_id);
        $data['inter_id'] = $inter_id;
        $data['page_title'] = '优惠券详情';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }


    /**
     * Ajax会员卡券列表
     * @param string $inter_id 微信酒店集团ID
     * @param string $openid 微信用户ID
     * @param string $next_id 下一个ID号
     * @param array $url_group 链接组
     * @return array
     */
    public function ajax_card($inter_id = '',$openid = '', $next_id = '',$url_group = array()){
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
        );
        //请求用户登录(默认)会员卡信息
        $member_info = $this->doCurlPostRequest($post_center_url, $post_center_data);
        $member_info = $this->parse_curl_msg($member_info);
        $member_info = $member_info['data'];
        $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
        //获取会员卡券列表
        $post_member_card_url = INTER_PATH_URL."membercard/getlist";
        $post_member_card_data = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$member_info_id,
            'next_id'=>$next_id,
            'num'=>10,
        );
        $card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );
        $data = $this->parse_curl_msg($card_info);
        $data['cardlist'] = $data['data'];
        unset($data['data']);
        $data['cardlist'] = $this->parse_card_data($data,'cardlist',$url_group);
        $data['next_id'] = !empty($data['next_id'])?$data['next_id']:0;

        $data['inter_id'] = $inter_id;
        $this->res_data['status'] = $data['err'] == '0' ? 1 : 3;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = !empty($data['msg'])?$data['msg']:'';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    //会员自主领取优惠页面
    public function getcard($inter_id = '',$openid = '', $card_rule_id = '',$filed_names = array(), $url_group = array()){
        $this->getBase();
        if(empty($card_rule_id)){
            $uri = \EA_const_url::inst()->get_url('*/center',array('id'=>$openid));
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '参数错误！';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = $uri;
            $this->res_data['data'] = array();
            return $this->res_data;
        }
        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
        );

        //请求用户登录(默认)会员卡信息
        $memberInfo = $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data );
        $memberInfo = $this->parse_curl_msg($memberInfo);
        $memberInfo = $memberInfo['data'];
        if(empty($memberInfo['member_info_id'])){
            $uri = \EA_const_url::inst()->get_url('*/center',array('id'=>$openid));
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '会员卡信息错误';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = $uri;
            $this->res_data['data'] = array();
            return $this->res_data;
        }

        //获取领取卡券/礼包的信息
        $post_card = array(
            'inter_id'=>$inter_id,
            'card_rule_id'=>$card_rule_id,
            'type'=>'gaze',
            'is_active'=>'t',
            'status'=>1,
            'member_info_id'=>$memberInfo['member_info_id'],
            'open_id'=>$openid,
            'model'=>'vip'
        );
        $card_info = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_info" , $post_card );
        $card_info = $this->parse_curl_msg($card_info);
        $this->assign_data = $url_group;
        $data = $this->assign_data;
        $data['card_info'] = $card_info['data'];

        $err_msg = '';
        if(!empty($card_info['err']) && $card_info['err'] > 0 && empty($card_info['data'])){
            $err_msg = $card_info['msg'];
        }elseif (!empty($card_info['err']) && $card_info['err'] > 0 && !empty($card_info['data'])){
            $err_msg = $card_info['data']['is_package']=='f'?'抱歉，优惠券已经被领完了':'抱歉，礼包已经被领完了';
        }

        if(!empty($card_info['data']['is_active']) && $card_info['data']['is_active'] == 'f'){
            $err_msg = '抱歉，礼包未激活';
        }

        $data['err_msg'] = $err_msg;
        $data['gain_count'] = isset($data['card_info']['receive_num']) ? intval($data['card_info']['receive_num']) : 0;

        $this->getCI()->load->model('wx/Publics_model');

        //优惠券中心授权信息处理
        $this->getCI()->load->model('membervip/common/Public_model','common_public');
        $where = array(
            'inter_id'=>$inter_id,
            'type_code'=>'member_card_url'
        );
        $card_host_info = $this->getCI()->common_public->get_info($where,'inter_member_config');
        $card_host = !empty($card_host_info['value'])?$card_host_info['value']:'';
        if(!empty($card_host)){
            $_card_host = explode(',',$card_host);
            $public_host = !empty($_card_host[0])?$_card_host[0]:'';
            $public_inter_id = !empty($_card_host[1])?$_card_host[1]:'';
            $ec_data = $inter_id.$openid;
            $public = $this->getCI()->Publics_model->get_public_by_id($inter_id);
            $key = $public['app_secret'];
            $encrypt = urlencode(kecrypt($ec_data,$key));
            $segments = base64_encode("{$inter_id}***{$openid}***{$encrypt}");
            $card_url = "{$public_host}/membervip/cardcenter?id={$public_inter_id}&f={$segments}";
            $data['card_url'] = $card_url;
        }

        $this->getCI()->load->model('wx/access_token_model');
        $data['signpackage'] = $this->getCI()->access_token_model->getSignPackage($inter_id);
        $data['public'] = $this->getCI()->Publics_model->get_public_by_id($inter_id);
        $this->getCI()->load->model('wx/access_token_model');
        $data['signpackage'] = $this->getCI()->access_token_model->getSignPackage($inter_id);
        $data['card_rule_id'] = $card_rule_id;
        $data['inter_id'] = $inter_id;
        $data['filed_name'] = $filed_names;
        $data['page_title'] = '豪礼大放送';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    //领取卡券
    public function addcard($inter_id = '', $openid = '', $card_rule_id = ''){
        $this->getBase();
        //获取领取卡券的信息
        $post_card = array(
            'inter_id'=>$inter_id,
            'card_rule_id'=>$card_rule_id,
            'active'=>'gaze',
        );
        $card_info = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_rule_card_info" , $post_card );
        $card_info = $this->parse_curl_msg($card_info);
        $card_info = $card_info['data'];
        if(!$card_info){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '卡券信息不存在';
            $this->res_data['err'] = 3;
            return $this->res_data;
        }
        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
        );
        //请求用户登录(默认)会员卡信息
        $memberInfo = $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data );
        $memberInfo = $this->parse_curl_msg($memberInfo);
        $memberInfo = $memberInfo['data'];
        if(!$memberInfo['member_info_id']){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '会员卡信息不存在';
            $this->res_data['err'] = 3;
            return $this->res_data;
        }
        //获取用户已领取过该券的总数
        $post_card_gain = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'card_id'=> isset($card_info['card_id'])?$card_info['card_id']:0,
        );
        $gain_count = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/member_gain_card_count" , $post_card_gain );
        $gain_count = $this->parse_curl_msg($gain_count);
        $gain_count = $gain_count['data'];
        if($gain_count>=$card_info['frequency']){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '您已领取过卡券了';
            $this->res_data['err'] = 2;
            return $this->res_data;
        }
        //领取卡券
        $add_card_data = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'card_id'=>$card_info['card_id'],
            'module'=>'vip',
            'uu_code'=>$openid.'gaze'.uniqid()
        );
        $add_card_result = $this->doCurlPostRequest( INTER_PATH_URL."intercard/receive" , $add_card_data );
        $add_card_result = $this->parse_curl_msg($add_card_result);
        $this->res_data = $add_card_result;
        $this->res_data['status'] = $add_card_result['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $add_card_result['data'];
        return $this->res_data;
    }

    //转赠卡券挂起
    public function hang_card($inter_id = '', $openid = '', $member_card_id = 0){
        //卡券转赠挂起
        $post_give_status_url = INTER_PATH_URL.'membercard/setgiving';
        $post_give_statue_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_card_id'=>$member_card_id,
        );
        $result = $this->doCurlPostRequest( $post_give_status_url , $post_give_statue_data );
        $result = $this->parse_curl_msg($result);
        $this->res_data = $result;
        $this->res_data['status'] = $result['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $result['data'];
        return $this->res_data;
    }

    //转赠优惠券挂起
    public function gift_card($inter_id = '', $openid = '', $member_card_id = 0, $module = '',$card_code = ''){
        //卡券转赠挂起
        $give_url = INTER_PATH_URL.'membercard/set_giving';
        $give_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_card_id'=>floatval($member_card_id),
            'receive_module'=>$module,
            'coupon_code'=>$card_code
        );
        $result = $this->doCurlPostRequest($give_url,$give_data);
        $result = $this->parse_curl_msg($result);
        $this->res_data = $result;
        $this->res_data['status'] = $result['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $result['data'];
        return $this->res_data;
    }

    //转赠优惠券挂起
    public function receive_card($inter_id = '', $openid = '', $ec_code = ''){
        $this->getBase();
        if(empty($ec_code)) {
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '领取失败';
            $this->res_data['err'] = 40003;
            return $this->res_data;
        }
        $this->getCI()->load->helper('qfglobal');
        $ec_code = base64_decode($ec_code);
        $ec_data = decrypt($ec_code,base64_encode($inter_id));
        if(empty($ec_data)) {
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '领取失败';
            $this->res_data['err'] = 40003;
            return $this->res_data;
        }
        $data = explode('**',$ec_data);
        \MYLOG::w("receive_card | " . @json_encode(array($ec_code,$ec_data,$data)),'iapi/membervip/debug-log');
        $member_card_id = isset($data[0])?$data[0]:0;
        $coupon_code = isset($data[1])?$data[1]:'';
        $gift_mem_id = isset($data[2])?$data[2]:0;
        //获取赠送优惠券的用户的信息
        $this->getCI()->load->model('membervip/front/Member_model','mem');
        $gift_mem_info = $this->getCI()->mem->get_member_info($gift_mem_id,'member_info_id,inter_id,member_mode,is_login,open_id,name,nickname');
        \MYLOG::w("get_member_info | " . @json_encode(array($gift_mem_id,$gift_mem_info)),'iapi/membervip/debug-log');
        if(empty($gift_mem_info)){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '转赠信息已失效';
            $this->res_data['err'] = 40003;
            return $this->res_data;
        }
        $post_data = array(
            'member_card_id' =>$member_card_id,
            'openid'=>$gift_mem_info['open_id'],
            'inter_id'=>$gift_mem_info['inter_id'],
            'member_info_id'=>$gift_mem_id,
            'coupon_code'=>$coupon_code,
            'is_active'=>'t',
            'is_giving'=>'t',
            'is_use'=>'f',
            'is_useoff'=>'f'
        );

        $_card_info = $this->doCurlPostRequest(INTER_PATH_URL."membercard/get_info_by_code",$post_data);
        \MYLOG::w("get_info_by_code | " . @json_encode(array($post_data,$_card_info)),'iapi/membervip/debug-log');
        $card_info = array();
        if(isset($_card_info['data'])) $card_info = $_card_info['data'];
        if(empty($card_info)){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '优惠券已丢失';
            $this->res_data['err'] = 40003;
            return $this->res_data;
        }

        //卡券转赠领取
        $receive_url = INTER_PATH_URL.'intercard/gift_receive';
        $post_data = array(
            'member_card_id' =>$member_card_id,
            'openid'=>$openid,
            'inter_id'=>$inter_id,
            'gift_mem_info_id'=>$gift_mem_id,
            'coupon_code'=>$coupon_code,
            'uu_code'=>uniqid().time(),
            'card_id'=>$card_info['card_id'],
            'module'=>$card_info['receive_module'],
            'scene'=>$card_info['receive_module'].' - receive',
            'remark'=>'领取好友优惠券'
        );
        $result = $this->doCurlPostRequest($receive_url,$post_data);
        \MYLOG::w("gift_receive | " . @json_encode(array($receive_url,$post_data,$result)),'iapi/membervip/debug-log');
        $result = $this->parse_curl_msg($result);
        $this->res_data = $result;
        $this->res_data['status'] = $result['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $result['data'];
        return $this->res_data;
    }

    //保存卡券转赠信息
    public function savegivecard($inter_id = '',$openid = '', $from_openid = '',$card_id = 0,$cardModule = 'vip'){
        if(!$from_openid){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '卡券用户信息不存在';
            $this->res_data['err'] = 1;
            return $this->res_data;
        }
        if(!$card_id){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '卡券信息不存在';
            $this->res_data['err'] = 2;
            return $this->res_data;
        }
        //卡券转赠
        $post_give_card_data = array(
            'member_card_id'=>$card_id,
            'from_openid'=>$from_openid,
            'to_openid'=>$openid,
            'inter_id'=>$inter_id,
            'module'=>$cardModule,
            'scene'=>'give',
            'remark'=>'好友转赠',
        );
        $give_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/give" , $post_give_card_data );
        $give_info = $this->parse_curl_msg($give_info);
        $this->res_data = $give_info;
        $this->res_data['status'] = $give_info['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $give_info['data'];
        return $this->res_data;
    }

    //卡券详细页面
    public function receive($inter_id = '',$openid = '',$ec_code = '', $url_group = array()){
        $this->getBase();
        $this->assign_data = $url_group;
        $assign_data = $this->assign_data;
        \MYLOG::w(@json_encode(array('id'=>$inter_id,'openid'=>$openid,'ec_code'=>$ec_code)),'iapi/membervip/debug-log','receive');
        if(empty($ec_code)) {
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '系统繁忙，验证出错！';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = site_url('membervip/center').'?id='.$inter_id;
            $this->res_data['data'] = array();
            return $this->res_data;
        }
        $assign_data['ec_code'] = $ec_code;
        $this->getCI()->load->helper('qfglobal');
        $ec_code = base64_decode($ec_code);
        $ec_data = decrypt($ec_code,base64_encode($inter_id));
        \MYLOG::w("decrypt | ".@json_encode(array('id'=>$inter_id,'openid'=>$openid,'ec_data'=>$ec_data)),'iapi/membervip/debug-log','receive');
        if(empty($ec_data)) {
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '系统繁忙，验证出错！';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = site_url('membervip/center').'?id='.$inter_id;
            $this->res_data['data'] = array();
            return $this->res_data;
        }
        $data = explode('**',$ec_data);
        \MYLOG::w("receive_data | ".@json_encode(array('id'=>$inter_id,'openid'=>$openid,'data'=>$data)),'iapi/membervip/debug-log','receive');
        $member_card_id = isset($data[0])?$data[0]:0;
        $coupon_code = isset($data[1])?$data[1]:'';
        $gift_mem_id = isset($data[2])?$data[2]:0;
        //获取赠送优惠券的用户的信息
        $this->getCI()->load->model('membervip/front/Member_model','mem');
        $gift_mem_info = $this->getCI()->mem->get_member_info($gift_mem_id,'member_info_id,inter_id,open_id,name,member_mode,is_login,nickname');
        \MYLOG::w("get_member_info | ".@json_encode(array('id'=>$inter_id,'openid'=>$openid,'data'=>$gift_mem_info)),'iapi/membervip/debug-log','receive');
        if(empty($gift_mem_info)) {
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '赠送信息验证失败';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = site_url('membervip/center').'?id='.$inter_id;
            $this->res_data['data'] = array();
            return $this->res_data;
        }
        $assign_data['gift_mem_info'] = $gift_mem_info;

        $user = $this->getCI()->mem->get_user_info($inter_id,$openid,'member_info_id,open_id,member_mode,is_login');
        \MYLOG::w("get_user_info | ".@json_encode(array('id'=>$inter_id,'openid'=>$openid,'data'=>$user)),'membervip/debug-log','receive');
        $assign_data['user'] = $user;

        $post_data = array(
            'member_card_id' =>$member_card_id,
            'openid'=>$gift_mem_info['open_id'],
            'inter_id'=>$gift_mem_info['inter_id'],
            'coupon_code'=>$coupon_code,
            'is_active'=>'t',
            'is_giving'=>'t',
            'is_use'=>'f',
            'is_useoff'=>'f'
        );

        $assign_data['inter_id'] = $inter_id;
        $assign_data['openid'] = $openid;
        $_card_info = $this->doCurlPostRequest(INTER_PATH_URL."membercard/get_info_by_code",$post_data);
        \MYLOG::w("membercard_getinfo | ".@json_encode(array('id'=>$inter_id,'openid'=>$openid,'data'=>$_card_info)),'membervip/debug-log','receive');

        $_card_info = $this->parse_curl_msg($_card_info);
        $card_info = $_card_info['data'];
        if(empty($card_info)) {
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '赠送的优惠券丢失';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = site_url('membervip/center').'?id='.$inter_id;
            $this->res_data['data'] = array();
            return $this->res_data;
        }

        $card_info['use_way'] = '';
        switch ($card_info['is_online']){
            case '1':
                $card_info['use_way'] = '在线使用';
                if($card_info['is_given_by_friend']=='t') $card_info['use_way'] = '在线使用或赠送朋友';
                break;
            case '2':
                $card_info['use_way'] = '线下使用';
                if($card_info['is_given_by_friend']=='t') $card_info['use_way'] = '线下使用或赠送朋友';
                break;
            case '3':
                $card_info['use_way'] = '可在线上和线下使用';
                if($card_info['is_given_by_friend']=='t') $card_info['use_way'] = '可在线上和线下使用或赠送朋友';
                break;
        }

        $assign_data['card_info'] = $card_info;

        $this->getCI()->load->model('wx/access_token_model');
        $this->getCI()->load->model('wx/Publics_model');
        $assign_data['public'] = $this->getCI()->Publics_model->get_public_by_id($inter_id);
        $assign_data['page_title'] = '领取优惠券';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $assign_data;
        return $this->res_data;
    }

    //卡券详细页面
    public function cardinfo($inter_id = '',$openid = '',$member_card_id = 0,$url_group = array()){
        if(empty($member_card_id)){ //参数为空
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '参数为空';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = site_url('membervip/card').'?id='.$inter_id;
            $this->res_data['data'] = array();
            return $this->res_data;
        }

        $this->getCI()->load->model('membervip/front/Member_model','mem');
        $user = $this->getCI()->mem->get_user_info($inter_id,$openid,'member_info_id,open_id,member_mode,is_login');
        if(empty($user)){ //找不到会员
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '找不到会员';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = site_url('membervip/center').'?id='.$inter_id;
            $this->res_data['data'] = array();
            return $this->res_data;
        }

        $my_member_info_ids = array(
            $user['member_info_id']
        );
        if($user['member_mode'] == 2){
            $extra = array(
                'member_mode' => 1,
                'is_login'=>'f'
            );
            $fens_user = $this->getCI()->mem->get_user_info($inter_id,$openid,'member_info_id',$extra);
            if(!empty($fens_user)){
                $my_member_info_ids[] = $fens_user['member_info_id'];
            }
        }

        $post_cardinfo_data = array(
            'member_card_id' =>$member_card_id,
            'openid'=>$openid,
            'inter_id'=>$inter_id,
        );

        $this->assign_data = $url_group;
        $assign_data = $this->assign_data;
        $assign_data['user'] = $user;

        $assign_data['inter_id'] = $inter_id;
        $assign_data['openid'] = $openid;
        $card_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_cardinfo_data );
        $card_info = $this->parse_curl_msg($card_info);
        $card_info = $card_info['data'];
        $my_card = false;
        $auth_useoff = false;
        if(!empty($card_info)){
            if(in_array($card_info['member_info_id'],$my_member_info_ids)) $my_card = true;

            $card_info['expire_time_quantum'] = isset($card_info['expire_time']) ? date('Y.m.d', $card_info['receive_time']) . ' - ' . date('Y.m.d', $card_info['expire_time']) : '';

            $card_info['use_way'] = '';
            switch ($card_info['is_online']){
                case '1':
                    $card_info['use_way'] = '在线使用';
                    if($card_info['is_given_by_friend']=='t') $card_info['use_way'] = '在线使用或赠送朋友';
                    break;
                case '2':
                    $card_info['use_way'] = '线下使用';
                    if($card_info['is_given_by_friend']=='t') $card_info['use_way'] = '线下使用或赠送朋友';
                    break;
                case '3':
                    $card_info['use_way'] = '可在线上和线下使用';
                    if($card_info['is_given_by_friend']=='t') $card_info['use_way'] = '可在线上和线下使用或赠送朋友';
                    break;
            }

            if($card_info['is_use']=='f' && $card_info['is_useoff']=='f' && $card_info['is_giving']=='f' && $card_info['is_active']=='t' && $my_card === true && ($card_info['card_type'] == 3 OR $card_info['is_online']=='2')){
                $auth_useoff = true;
            }
        }
        $card_info = $this->parse_card_info($card_info);
        $assign_data['card_info'] = $card_info;
        $assign_data['my_card'] = $my_card;
        $assign_data['auth_useoff'] = $auth_useoff;
        $this->getCI()->load->model('wx/access_token_model');
        $assign_data['public'] = $this->getCI()->Publics_model->get_public_by_id($inter_id);

        /*获取微信JSSDK配置*/
        $assign_data['wx_config'] = $this->_get_sign_package($inter_id);
        $js_api_list = $menu_show_list = $menu_hide_list= '';
        $assign_data['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        $assign_data['js_api_list'] = $assign_data['base_api_list'];

        foreach ($assign_data['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }

        $assign_data['js_api_list']= substr($js_api_list, 0, -1);

        //主动显示某些菜单
        $assign_data['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline');
        $menu_show_list = '';
        foreach ($assign_data['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $assign_data['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        $assign_data['js_menu_hide']= array('menuItem:copyUrl','menuItem:share:email','menuItem:originPage','menuItem:favorite');
        $menu_hide_list = '';
        foreach ($assign_data['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $assign_data['js_menu_hide']= substr($menu_hide_list, 0, -1);

        $assign_data['js_share_config']['title'] = isset($card_info['title'])?$card_info['title']:'';

        //加密参数
        $this->getCI()->load->helper('qfglobal');
        $ec_data = $card_info['member_card_id'].'**'.$card_info['coupon_code'].'**'.$user['member_info_id'];
        $key = base64_encode($inter_id);

        $encrypt = encrypt($ec_data,$key);
        $encrypt = base64_encode($encrypt);
        \MYLOG::w("card_encrypt | ".@json_encode(array($key,$ec_data,$encrypt)),'membervip/debug-log');
        $assign_data['js_share_config']['link'] = \EA_const_url::inst()->get_url('*/*/receive',array('id'=>$inter_id,'sf'=>$encrypt));
        $assign_data['js_share_config']['imgUrl'] = $card_info['logo_url'];
        $assign_data['js_share_config']['desc'] = "送你一张【".$card_info['title']."】帮你轻松享优惠";
        /*end*/
        //如果连接地址为空的话，则强行加上订房连接
        if(empty($assign_data['card_info']['header_url']) && empty($assign_data['card_info']['shop_header_url']) && empty($assign_data['card_info']['hotel_header_url']) && empty($assign_data['card_info']['soma_header_url'])){
            $assign_data['card_info']['hotel_header_url']='/index.php/hotel/hotel/search?id='.$inter_id;
        }

        //获取优惠券转赠授权信息
        $auth_gift = false;
        $this->getCI()->load->model('membervip/common/Public_model','common_model');
        $where = array(
            'inter_id'=>'ALL_INTER_ID',
            'type_code'=>'member_auth_gift'
        );
        $member_auth_gift = $this->getCI()->common_model->get_info($where,'inter_member_config','value');
        $auth_inter = !empty($member_auth_gift['value'])?explode(',',$member_auth_gift['value']):array();
        if(in_array($inter_id,$auth_inter)){
            $auth_gift = true;
        }

        $assign_data['auth_gift'] = $auth_gift;
        $assign_data['page_title'] = '优惠券详情';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $assign_data;
        return $this->res_data;
    }

    //卡券扫码使用
    public function codeuseoff($inter_id = '',$openid = ''){
        /*扫描权限地址*/
        $this->getCI()->load->model('membervip/common/Public_model','common_model');
        $where = array(
            'openid'=>$openid,
            'inter_id'=>$inter_id
        );

        $scanqr_auth = $this->getCI()->common_model->get_info($where,'scanqr_auth');
        if(!empty($scanqr_auth) && $scanqr_auth['status'] == 1){
            $header = array(
                'title'=> '扫码核销',
                'type'=>1,
            );

            $this->getCI()->load->helper('encrypt');
            $encrypt_util= new \Encrypt();
            $token = $encrypt_util->encrypt($openid. date('YmdH') );

            //增加以下jsapi
            $base_api_list = array( 'scanQRCode', 'closeWindow' );
            $data= array(
                'message'=> '点击页面，开始核销',
                'callback'=> \EA_const_url::inst()->get_url('*/*/card_callback', array('id'=> $inter_id)),
                'js_api_list'=> $base_api_list,
                'openid'=> $openid,
                't'=> $token,
            );
            $data = array_merge($header,$data);
        }else{
            $header = array(
                'title'=> '认证失败',
                'type'=>2,
            );
            $base_api_list = array( 'closeWindow' );
            $message= '您的微信号未经授权，不能进行此操作。';
            $data= array(
                'message'=> $message,
                'js_api_list'=> $base_api_list,
            );
            $data = array_merge($header,$data);
        }

        $this->getCI()->load->model('wx/access_token_model');
        $data['signpackage'] = $this->getCI()->access_token_model->getSignPackage($inter_id);
        $data['public'] = $this->getCI()->Publics_model->get_public_by_id($inter_id);
        $data['page_title'] = '扫码核销';
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    //扫码核销异步请求
    public function card_callback($inter_id = '',$openid = '',$code = ''){
        //获取用户的信息
        $userinfo = $this->getCI()->Publics_model->get_fans_info_one($inter_id,$openid);
        $post_code_useoff_url = INTER_PATH_URL.'membercard/useoff_code';
        $post_card_useoff_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'coupon_code'=>$code,
            'use_type'=>3,
            'operator'=>$userinfo['nickname'].'_@@@_'.$openid
        );
        $useoff_result = $this->doCurlPostRequest( $post_code_useoff_url , $post_card_useoff_data );
        $useoff_result = $this->parse_curl_msg($useoff_result);
        $this->res_data = $useoff_result;
        $this->res_data['status'] = $useoff_result['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $useoff_result['data'];
        return $this->res_data;
    }

    public function passwduseoff($inter_id = '',$openid = '',$member_card_id = 0, $passwd = ''){
        if(!$passwd){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '消费码不能为空';
            $this->res_data['err'] = 110;
            return $this->res_data;
        }
        if(!$member_card_id){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '卡券ID不存在';
            $this->res_data['err'] = 11;
            return $this->res_data;
        }
        $post_cardinfo_data = array(
            'member_card_id' =>$member_card_id,
            'openid'=>$openid,
            'inter_id'=>$inter_id,
        );
        $cardInfo = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_cardinfo_data );
        $cardInfo = $this->parse_curl_msg($cardInfo);
        if($cardInfo['code'] == '1004'){
            $this->res_data['status'] = 3;
            $this->res_data['data'] = $cardInfo['data'];
            return $this->res_data;
        }else{
            $cardInfo = $cardInfo['data'];
        }
        if($cardInfo['is_online']==1){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '卡券类型不支持消费码消费';
            $this->res_data['err'] = 122;
            return $this->res_data;
        }
        if($cardInfo['passwd']!=$passwd){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '消费码不正确';
            $this->res_data['err'] = 121;
            return $this->res_data;
        }
        //消费码使用
        $post_useone_url = INTER_PATH_URL.'membercard/useone';
        $post_useone_data = array(
            'member_card_id'=>$member_card_id,
            'openid'=>$openid,
            'inter_id'=>$inter_id,
            'module'=>'vip',
            'scene'=>'vip',
            'remark'=>'消费码使用',
            'offline'=>2,
            'use_type'=>2
        );
        $useone = $this->doCurlPostRequest($post_useone_url, $post_useone_data);
        $useone = $this->parse_curl_msg($useone);
        if($useone['code'] == 1000){
            $post_useoff_url = INTER_PATH_URL.'membercard/useoff';
            $post_useone_data = array(
                'member_card_id'=>$member_card_id,
                'openid'=>$openid,
                'inter_id'=>$inter_id,
                'module'=>'vip',
                'scene'=>'vip',
                'remark'=>'消费码核销',
                'use_type'=>2
            );
            $useoff = $this->doCurlPostRequest( $post_useoff_url , $post_useone_data );
            $useoff = $this->parse_curl_msg($useoff);
            $this->res_data = $useoff;
            $this->res_data['status'] = $useoff['code'] == '1000' ? 1 : 3;
            $this->res_data['data'] = $useoff['data'];
            return $this->res_data;
        }else{
            $this->res_data = $useone;
            $this->res_data['status'] = $useone['code'] == '1000' ? 1 : 3;
            $this->res_data['data'] = $useone['data'];
            return $this->res_data;
        }
    }

    public function getpackage($inter_id = '',$openid = '',$package_id = 0, $frequency = 0,$card_rule_id = 0){
        $this->getBase();
        //获取领取礼包的信息
        $post_card = array(
            'inter_id'=>$inter_id,
            'status'=>1,
            'package_id'=>$package_id
        );
        $rule_info = $this->doCurlPostRequest( INTER_PATH_URL."package/getinfo" , $post_card );
        $_rule_info = $this->parse_curl_msg($rule_info);
        $rule_info = $_rule_info['data'];
        if(empty($rule_info)){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = !empty($_rule_info['msg'])?$_rule_info['msg']:'礼包信息不存在';
            $this->res_data['err'] = !empty($_rule_info['err'])?$_rule_info['err']:3;
            return $this->res_data;
        }

        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
        );
        //请求用户登录(默认)会员卡信息
        $memberInfo= $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data )['data'];
        if(!$memberInfo['member_info_id']){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '会员卡信息不存在';
            $this->res_data['err'] = 3;
            return $this->res_data;
        }

        //获取用户已领取过礼包的总数
        $post_card_gain = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'package_id'=> $package_id,
            'openid' =>$openid,
            'card_rule_id'=>$card_rule_id
        );
        $package_count = $this->doCurlPostRequest( INTER_PATH_URL."package/member_gain_package_count" , $post_card_gain );

        if(isset($package_count['data']) && $package_count['data']>=$frequency){
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '您已领取过礼包了';
            $this->res_data['err'] = 2;
            return $this->res_data;
        }

        //发送优惠套餐
        $packge_url = INTER_PATH_URL.'package/give';
        $package_data = array(
            'card_rule_id'=>$card_rule_id,
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'uu_code'=>$openid.'gaze'.uniqid(),
            'package_id'=>$package_id,
            'number'=>$frequency
        );
        $package = $this->doCurlPostRequest( $packge_url , $package_data );
        $package = $this->parse_curl_msg($package);
        $this->res_data = $package;
        $this->res_data['status'] = $package['code'] == '1000' ? 1 : 3;
        $this->res_data['data'] = $package['data'];
        return $this->res_data;
    }

    //通过券码检测优惠券是否已经使用和核销
    public function check_useoff($inter_id = '',$openid = '',$coupon_code = '',$url_group = array()){
        $this->assign_data = $url_group;
        if(is_ajax_request()){
            $this->getCI()->load->model('membervip/common/Public_model','common_model');

            $this->getCI()->load->model('membervip/front/Member_model','mem');
            $user = $this->getCI()->mem->get_user_info($inter_id,$openid,'member_info_id');

            $where = array(
                'open_id'=>$openid,
                'inter_id'=>$inter_id,
                'coupon_code'=>$coupon_code,
                'member_info_id'=>!empty($user['member_info_id'])?$user['member_info_id']:0
            );

            $member_card = $this->getCI()->common_model->get_info($where,'member_card','is_use,is_useoff');
            if(!empty($member_card) && $member_card['is_use'] == 't' && $member_card['is_useoff'] == 't'){
                $this->res_data['status'] = 1;
                $this->res_data['msg_lvl'] = 1;
                $this->res_data['msg'] = '使用核销成功';
                $this->res_data['data'] = $this->assign_data['cardcenter_url'];
                return $this->res_data;
            }
            $this->res_data['status'] = 3;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '使用核销失败';
            $this->res_data['data'] = array();
            return $this->res_data;
        }
        $this->res_data['status'] = 3;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = '很抱歉，请求失败，请联系管理员';
        $this->res_data['data'] = array();
        return $this->res_data;
    }

    /**
     * 获取微信JSSDK配置信息
     * @param $inter_id
     * @param string $url
     * @return array
     */
    protected function _get_sign_package($inter_id, $url=''){
        $this->getCI()->load->helper('common');
        $this->getCI()->load->model('wx/access_token_model');
        $jsapiTicket = $this->getCI()->access_token_model->get_api_ticket( $inter_id );

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if(!$url)
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();
        $public = $this->getCI()->Publics_model->get_public_by_id( $inter_id );

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
}