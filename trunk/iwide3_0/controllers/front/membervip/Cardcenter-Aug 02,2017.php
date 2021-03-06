﻿<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	会员优惠券中心
 *	@author  liwensong
 *	@copyright www.iwide.cn
 *	@version 4.0
 *	@Email 171708252lux@gmail.com
 */
class Cardcenter extends MY_Front_Member
{   private $initTime;
    private $endt;

    private $show_openid;
    private $show_inter_id;
    private $assign_data = array();

    public function __construct()
    {
        $this->initTime = microtime(true);
        parent::__construct();
        $this->endt = microtime(true);

        $sem = $this->input->get('f');
        if($sem) $sem = base64_decode($sem);
        $segment_arr = explode('***', $sem);
        if (empty($segment_arr[0]) || empty($segment_arr[1]) || empty($segment_arr[2])){
            die('参数不全');
//            redirect ( site_url ( '/distribute/dis_ext/perror' ) . '?id=' . $this->input->get ( 'id' ) );
        }
        $this->load->model('wx/Publics_model','publics_model');
        $this->load->helper('qfglobal');
        //验证签名
        $m_inter_id = $segment_arr[0];
        $m_open_id  = $segment_arr[1];
        $decrypt    = $segment_arr[2];
        $m_public = $this->publics_model->get_public_by_id ($m_inter_id);
        $key = $m_public['app_secret'];
        $ec_data = $m_inter_id.$m_open_id;
        $encrypt = urlencode(kecrypt($ec_data,$key));
        if($decrypt != $encrypt){
            die('验证不通过');
        }

        $this->show_inter_id = $m_inter_id;
        $this->show_openid =$m_open_id;

        $this->load->model ( 'distribute/openid_rel_model' );
        $flag = $this->openid_rel_model->new_rel ( array (
            'openid'     =>  $this->show_openid,
            'inter_id'   =>  $this->show_inter_id,
            'm_inter_id' => $this->session->userdata ( 'inter_id' ),
            'm_openid'   => $this->session->userdata ( $this->session->userdata ( 'inter_id' ) . 'openid' )
        )) ;
        if($flag){
            $this->member_template( $this->show_inter_id ); //模板设置
            $this->template_filed_name_set( $this->show_inter_id ); //自定义名字
        }else{
            log_message ( 'error', '公众号openid关联失败，FROM:' . $this->input->post ( 'inter_id' ) . '-' . $this->input->post ( 'openid' ) . ' TO:' . $this->input->post ( 'inter_id' ) );
        }

        $ec_data = $this->show_inter_id.$this->show_openid;
        $encrypt = urlencode(kecrypt($ec_data,$key));
        $segments = base64_encode("{$this->show_inter_id}***{$this->show_openid}***{$encrypt}");
        $this->assign_data['segments'] = $segments;
        $this->assign_data['org_inter_id'] = $this->show_inter_id;
        $this->assign_data['share_inter_id'] = $this->inter_id;


        $this->assign_data['org_domain'] = '';
        $this->assign_data['share_domain'] = '';
        $_pattern = "/^((http:\/\/)|(https:\/\/))?([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}/"; //匹配网址域名
        preg_match_all($_pattern,$m_public['domain'],$match);
        if(!$match){
            $this->assign_data['org_domain'] = $m_public['domain'];
        }else{
            if(strpos($match[0][0],'http://') !== false OR strpos($match[0][0],'https://') !== false){
                $public_host = $match[0][0];
            }else{
                $public_host = "http://{$match[0][0]}";
            }
            $this->assign_data['org_domain'] = $public_host;
        }

        $public = $this->publics_model->get_public_by_id ($this->inter_id);
        preg_match_all($_pattern,$public['domain'],$matchs);
        if(!$matchs){
            $this->assign_data['share_domain'] = $public['domain'];
        }else{
            if(strpos($matchs[0][0],'http://') !== false OR strpos($matchs[0][0],'https://') !== false){
                $public_host = $matchs[0][0];
            }else{
                $public_host = "http://{$matchs[0][0]}";
            }
            $this->assign_data['share_domain'] = $public_host;
        }

        //设置前端需要用到的URL
        $this->assign_data['cardcenter_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter?id={$this->inter_id}&f={$segments}";
        $this->assign_data['cardinfo_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/cardinfo?id={$this->inter_id}&f={$segments}";
        $this->assign_data['pcardinfo_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/pcardinfo?id={$this->inter_id}&f={$segments}";
        $this->assign_data['center_url'] = "{$this->assign_data['share_domain']}/membervip/center?id={$this->inter_id}&f={$segments}";
        $this->assign_data['qrcodecon_url'] = "{$this->assign_data['share_domain']}/membervip/center/qrcodecon?id={$this->inter_id}";
        $this->assign_data['gift_card_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/gift_card?id={$this->inter_id}&f={$segments}";
        $this->assign_data['passwduseoff_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/passwduseoff?id={$this->inter_id}&f={$segments}";
        $this->assign_data['getpackage_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/getpackage?id={$this->inter_id}&f={$segments}";
        $this->assign_data['addcard_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/addcard?id={$this->inter_id}&f={$segments}";
        $this->assign_data['givecard_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/givecard?id={$this->inter_id}&f={$segments}";
        $this->assign_data['hang_card_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/hang_card?id={$this->inter_id}&f={$segments}";
        $this->assign_data['savegivecard_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/savegivecard?id={$this->inter_id}&f={$segments}";
        $this->assign_data['receive_card_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/receive_card?id={$this->inter_id}&f={$segments}";
        $this->assign_data['check_useoff_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/check_useoff?id={$this->inter_id}&f={$segments}";
    }

    //会员卡券列表
    public function index(){

        //start show
        $inter_id = $this->show_inter_id;
        $open_id = $this->show_openid;
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$open_id,
        );
        //请求用户登录(默认)会员卡信息
        $member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
        $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
        //获取会员卡券列表
        $do_curl = INTER_PATH_URL."membercard/getcardlist";
        $post_data = array(
            'inter_id'=>$inter_id,
            'member_info_id'=>$member_info_id,
            'openid'=>$open_id,
            'token'=>$this->_token,
            'range'=>'0,100',
            'template'=>$this->_template
        );

        $card_info = $this->doCurlPostRequest($do_curl,$post_data);
        $this->assign_data['all'] = array();
        $this->assign_data['usableCardLists'] = array();
        $this->assign_data['unusedCardLists'] = array();
        $this->assign_data['expiredCardLists'] = array();
        $phase2_arr=['zhouji','phase2','yasite','highclass','green'];
        if(!empty($this->_template) &&in_array($this->_template,$phase2_arr) ){
            if(isset($card_info['err']) && $card_info['err']=='0'){
                $this->assign_data['all'] = isset($card_info['return']['all']['data'])?$card_info['return']['all']['data']:array();
                $this->assign_data['usableCardLists'] = isset($card_info['return']['valids']['data'])?$card_info['return']['valids']['data']:array();
                $this->assign_data['unusedCardLists'] = isset($card_info['return']['is_use']['data'])?$card_info['return']['is_use']['data']:array();
                $this->assign_data['expiredCardLists'] = isset($card_info['return']['expired']['data'])?$card_info['return']['expired']['data']:array();
            }
        }else{
            $this->assign_data['cardlist'] = $card_info['data'];
        }

        $this->load->model('wx/Publics_model');
        $this->assign_data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
        $this->assign_data['next_id'] = isset($card_info['next_id']) ? $card_info['next_id'] : 0;
        $this->assign_data['inter_id'] = $inter_id;

        $this->assign_data['f_sign'] = $this->encrypt($inter_id,$open_id);
        $this->template_show('member',$this->_template,'card',$this->assign_data);

    }


    public function cardinfo(){
        $member_card_id = intval($this->input->get('member_card_id'));
        if(empty($member_card_id)){ //参数为空
            if(!empty($this->assign_data['share_domain'])){
                redirect("{$this->assign_data['share_domain']}/membervip/cardcenter?id={$this->inter_id}&f={$this->assign_data['segments']}");
            }else{
                redirect(site_url('membervip/card').'?id='.$this->show_inter_id);
            }
        }

        $this->load->model('membervip/front/Member_model','mem');
        $user = $this->mem->get_user_info($this->show_inter_id,$this->show_openid,'member_info_id,open_id,member_mode,is_login');
        if(empty($user)){ //找不到会员
            if(!empty($this->assign_data['org_domain'])){
                redirect("{$this->assign_data['org_domain']}/membervip/center?id={$this->show_inter_id}");
            }else{
                redirect(site_url('membervip/center').'?id='.$this->show_inter_id);
            }
        }

        $my_member_info_ids = array(
            $user['member_info_id']
        );
        if($user['member_mode'] == 2){
            $extra = array(
                'member_mode' => 1,
                'is_login'=>'f'
            );
            $fens_user = $this->mem->get_user_info($this->show_inter_id,$this->show_openid,'member_info_id',$extra);
            if(!empty($fens_user)){
                $my_member_info_ids[] = $fens_user['member_info_id'];
            }
        }

        $post_cardinfo_data = array(
            'member_card_id' =>$member_card_id,
            'token'=>$this->_token,
            'openid'=>$this->show_openid,
            'inter_id'=>$this->show_inter_id,
        );

        $this->assign_data['inter_id'] = $this->show_inter_id;
        $this->assign_data['openid'] = $this->show_openid;
        $card_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_cardinfo_data )['data'];
        $my_card = false;
        $auth_useoff = false;
        if(!empty($card_info)){
            if(in_array($card_info['member_info_id'],$my_member_info_ids)) $my_card = true;
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
        //没有连接的话，默认跳转到酒店

        $this->assign_data['card_info'] = $card_info;
        $this->assign_data['my_card'] = $my_card;
        $this->assign_data['auth_useoff'] = $auth_useoff;

        $this->load->model('wx/access_token_model');
        $this->load->model('wx/Publics_model');
        $this->assign_data['public'] = $this->Publics_model->get_public_by_id($this->show_inter_id);

        /*获取微信JSSDK配置*/
        $this->assign_data['wx_config'] = $this->_get_sign_package($this->inter_id);
        $js_api_list = $menu_show_list = $menu_hide_list= '';
        $this->assign_data['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        $this->assign_data['js_api_list'] = $this->assign_data['base_api_list'];

        foreach ($this->assign_data['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }

        $this->assign_data['js_api_list']= substr($js_api_list, 0, -1);

        //主动显示某些菜单
        $this->assign_data['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline');
        $menu_show_list = '';
        foreach ($this->assign_data['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $this->assign_data['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        $this->assign_data['js_menu_hide']= array('menuItem:share:appMessage','menuItem:share:timeline','menuItem:copyUrl','menuItem:share:email','menuItem:originPage','menuItem:favorite');
        $menu_hide_list = '';
        foreach ($this->assign_data['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $this->assign_data['js_menu_hide']= substr($menu_hide_list, 0, -1);

        $this->assign_data['js_share_config']['title'] = isset($card_info['title'])?$card_info['title']:'';

        //加密参数
        $this->load->helper('qfglobal');
        $ec_data = $card_info['member_card_id'].'**'.$card_info['coupon_code'].'**'.$user['member_info_id'];
        $key = base64_encode($this->show_inter_id);
        $this->api_write_log($key,'card_key');
        $encrypt = encrypt($ec_data,$key);
        $encrypt = base64_encode($encrypt);
        $this->api_write_log($encrypt,'card_encrypt');
        if(!empty($this->assign_data['share_domain'])){
            $this->assign_data['js_share_config']['link'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/before_receive?id={$this->inter_id}&f={$this->assign_data['segments']}&sf={$encrypt}";
        }else{
            $this->assign_data['js_share_config']['link'] = EA_const_url::inst()->get_url('*/*/receive',array('id'=>$this->show_inter_id,'sf'=>$encrypt));
        }
        $this->assign_data['js_share_config']['imgUrl'] = $card_info['logo_url'];
        $this->assign_data['js_share_config']['desc'] = "送你一张【".$card_info['title']."】帮你轻松享优惠";
        /*end*/

        //如果连接地址为空的话，则强行加上订房连接
        if(empty($this->assign_data['card_info']['header_url']) && empty($this->assign_data['card_info']['shop_header_url'])&&empty($this->assign_data['card_info']['hotel_header_url'])&&empty($this->assign_data['card_info']['soma_header_url'])){
            $this->assign_data['card_info']['hotel_header_url'] = $this->assign_data['org_domain'].'/index.php/hotel/hotel/search?id='.$this->inter_id;
        }

        //获取优惠券转赠授权信息
        $auth_gift = false;
        $this->load->model('membervip/common/Public_model','common_model');
        $where = array(
            'inter_id'=>'ALL_INTER_ID',
            'type_code'=>'member_auth_gift'
        );
        $member_auth_gift = $this->common_model->get_info($where,'inter_member_config','value');
        $auth_inter = !empty($member_auth_gift['value'])?explode(',',$member_auth_gift['value']):array();
        if(in_array($this->show_inter_id,$auth_inter)){
            $auth_gift = true;
        }

        $this->assign_data['auth_gift'] = $auth_gift;
        $this->template_show('member',$this->_template,'cardinfo',$this->assign_data);
    }

    //加密链接参数
    public function encrypt($inter_id,$open_id){
        $this->load->model('wx/Publics_model','publics_model');

        //加密参数
        $this->load->helper('qfglobal');
        $public = $this->publics_model->get_public_by_id ($this->inter_id);
        $key = $public['app_secret'];
        $ec_data = $inter_id.$open_id;
        $sign = kecrypt($ec_data,$key);   $this->load->model('wx/Publics_model','publics_model');
        return base64_encode($this->inter_id.'***'.$this->openid.'***'.$sign);
    }

    /**
     * 获取微信JSSDK配置信息
     * @param $inter_id
     * @param string $url
     * @return array
     */
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

    //转赠优惠券挂起
    public function gift_card(){
        //卡券转赠挂起
        $give_url = INTER_PATH_URL.'membercard/set_giving';
        $give_data = array(
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'openid'=>$this->show_openid,
            'member_card_id'=>floatval($this->input->post('mcid')),
            'receive_module'=>$this->input->post('module'),
            'coupon_code'=>$this->input->post('card_code')
        );
        $result = $this->doCurlPostRequest($give_url,$give_data);
        echo json_encode($result);
        exit(0);
    }

    /**
     *	消费码核销
     *
     */
    public function passwduseoff(){
        $member_card_id = $this->input->post('member_card_id');
        $passwd = $this->input->post('passwd');
        if(!$passwd){
            echo json_encode(array('err'=>110,'msg'=>'消费码不能为空'));exit;
        }
        if(!$member_card_id){
            echo json_encode(array('err'=>11,'msg'=>'卡券ID不存在'));exit;
        }
        $post_cardinfo_data = array(
            'member_card_id' =>$member_card_id,
            'token'=>$this->_token,
            'openid'=>$this->show_openid,
            'inter_id'=>$this->show_inter_id,
        );
        $cardInfo = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_cardinfo_data );
        if(isset($cardInfo['err'])){
            echo json_encode($cardInfo);exit;
        }else{
            $cardInfo = $cardInfo['data'];
        }
        if($cardInfo['is_online']==1){
            echo json_encode(array('err'=>122,'msg'=>'卡券类型不支持消费码消费'));exit;
        }
        if($cardInfo['passwd']!=$passwd){
            echo json_encode(array('err'=>121,'msg'=>'消费码不正确'));exit;
        }
        //消费码使用
        $post_useone_url = INTER_PATH_URL.'membercard/useone';
        $post_useone_data = array(
            'member_card_id'=>$member_card_id,
            'token'=>$this->_token,
            'openid'=>$this->show_openid,
            'inter_id'=>$this->show_inter_id,
            'module'=>'vip',
            'scene'=>'vip',
            'remark'=>'消费码使用',
            'offline'=>2,
        );
        $useone = $this->doCurlPostRequest( $post_useone_url , $post_useone_data );
        if($useone['err'] == 0){
            $post_useoff_url = INTER_PATH_URL.'membercard/useoff';
            $post_useone_data = array(
                'member_card_id'=>$member_card_id,
                'token'=>$this->_token,
                'openid'=>$this->show_openid,
                'inter_id'=>$this->show_inter_id,
                'module'=>'vip',
                'scene'=>'vip',
                'remark'=>'消费码核销',
            );
            $useoff = $this->doCurlPostRequest( $post_useoff_url , $post_useone_data );
            echo json_encode($useoff);exit;
        }else{
            echo json_encode($useone);exit;
        }

    }

    public function pcardinfo(){
        $member_card_id = $this->input->get('member_card_id');
        $my_card = false;
        $auth_useoff = false;
        if(!empty($member_card_id)){
            $post_center_url = PMS_PATH_URL."member/center";
            $post_center_data =  array(
                'inter_id'=>$this->show_inter_id,
                'openid' =>$this->show_openid,
            );
            //请求用户登录(默认)会员卡信息
            $member_info = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
            $member_info_id = isset($member_info['member_info_id'])?$member_info['member_info_id']:0;
            $member_mode = isset($member_info['member_mode'])?$member_info['member_mode']:'';

            //获取会员卡券列表
            $post_member_card_url = INTER_PATH_URL."membercard/get_pms_card_info";
            $post_member_card_data = array(
                'inter_id'=>$this->show_inter_id,
                'member_info_id'=>$member_info_id,
                'member_card_id'=>$member_card_id,
                'token'=>$this->_token,
                'num'=>30,
            );
            $card_info = $this->doCurlPostRequest( $post_member_card_url , $post_member_card_data );

            if(isset($card_info['err']) && $card_info['err']>0){
                $this->assign_data['card_info'] = array();
                $this->assign_data['next_id'] = 0;
            }else{
                $this->assign_data['card_info'] = $card_info['data'];
                $this->assign_data['next_id'] = isset($card_info['next_id'])?$card_info['next_id']:0;

                $my_member_info_ids = array(
                    $member_info_id
                );
                if($member_mode == 2){
                    $this->load->model('membervip/front/Member_model','mem');
                    $extra = array(
                        'member_mode' => 1,
                        'is_login'=>'f'
                    );
                    $fens_user = $this->mem->get_user_info($this->show_inter_id,$this->show_openid,'member_info_id',$extra);
                    if(!empty($fens_user)){
                        $my_member_info_ids[] = $fens_user['member_info_id'];
                    }
                }
                if(in_array($this->assign_data['card_info']['member_info_id'],$my_member_info_ids)) $my_card = true;

                if($this->assign_data['card_info']['is_use']=='f' && $this->assign_data['card_info']['is_useoff']=='f' && $this->assign_data['card_info']['is_giving']=='f' && $this->assign_data['card_info']['is_active']=='t' && $my_card === true && ($this->assign_data['card_info']['card_type'] == 3 OR $this->assign_data['card_info']['is_online']=='2')){
                    $auth_useoff = true;
                }
            }
        }
        $this->assign_data['my_card'] = $my_card;
        $this->assign_data['auth_useoff'] = $auth_useoff;

        //获取优惠券转赠授权信息
        $auth_gift = false;
        $this->load->model('membervip/common/Public_model','common_model');
        $where = array(
            'inter_id'=>'ALL_INTER_ID',
            'type_code'=>'member_auth_gift'
        );
        $member_auth_gift = $this->common_model->get_info($where,'inter_member_config','value');
        $auth_inter = !empty($member_auth_gift['value'])?explode(',',$member_auth_gift['value']):array();
        if(in_array($this->show_inter_id,$auth_inter)){
            $auth_gift = true;
        }

        $this->assign_data['auth_gift'] = $auth_gift;

        $this->load->model('wx/access_token_model');
        $this->load->model('wx/Publics_model');
        $this->assign_data['signpackage'] = $this->access_token_model->getSignPackage($this->show_inter_id);
        $this->assign_data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
        $this->assign_data['inter_id'] = $this->show_inter_id;
        $this->template_show('member',$this->_template,'pcardinfo',$this->assign_data);
    }

    //领取卡券
    public function addcard(){
        $card_rule_id = isset($_POST['card_rule_id'])?(int)$_POST['card_rule_id']:0;
        //获取领取卡券的信息
        $post_card = array(
            'inter_id'=>$this->show_inter_id,
            'card_rule_id'=>$card_rule_id,
            'active'=>'gaze',
        );
        $card_info = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_rule_card_info" , $post_card )['data'];
        if(!$card_info){
            echo json_encode(array('err'=>3,'msg'=>'卡券信息不存在'));exit;
        }
        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$this->show_inter_id,
            'openid' =>$this->show_openid,
        );
        //请求用户登录(默认)会员卡信息
        $memberInfo= $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data )['data'];
        if(!$memberInfo['member_info_id']){
            echo json_encode(array('err'=>3,'msg'=>'会员卡信息不存在'));exit;
        }
        //获取用户已领取过该券的总数
        $post_card_gain = array(
            'inter_id'=>$this->show_inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'card_id'=> isset($card_info['card_id'])?$card_info['card_id']:0,
        );
        $gain_count = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/member_gain_card_count" , $post_card_gain )['data'];
        if(  $gain_count>=$card_info['frequency']){
            echo json_encode(array('err'=>2,'msg'=>'您已领取过卡券了'));exit;
        }
        //领取卡券
        $add_card_data = array(
            'inter_id'=>$this->show_inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'card_id'=>$card_info['card_id'],
            'module'=>'vip',
            'token'=>$this->_token,
            'uu_code'=>$this->show_openid.'gaze'.uniqid()
        );
        $add_card_result = $this->doCurlPostRequest( INTER_PATH_URL."intercard/receive" , $add_card_data );
        echo json_encode($add_card_result);
    }


    public function getpackage(){
        $package_id = isset($_POST['package_id'])?(int)$_POST['package_id']:0;
        $frequency = isset($_POST['frequency'])?(int)$_POST['frequency']:0;
        $card_rule_id = isset($_POST['card_rule_id'])? intval($_POST['card_rule_id']):0;
        //获取领取礼包的信息
        $post_card = array(
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'status'=>1,
            'package_id'=>$package_id
        );
        $rule_info = $this->doCurlPostRequest( INTER_PATH_URL."package/getinfo" , $post_card );
        if(!isset($rule_info['data']) || empty($rule_info['data'])){
            echo json_encode(array('err'=>3,'msg'=>'礼包信息不存在'));exit;
        }

        //获取用户的详细信息
        $post_center_data =  array(
            'inter_id'=>$this->show_inter_id,
            'openid' =>$this->show_openid,
        );
        //请求用户登录(默认)会员卡信息
        $memberInfo= $this->doCurlPostRequest( PMS_PATH_URL."member/center" , $post_center_data )['data'];
        if(!$memberInfo['member_info_id']){
            echo json_encode(array('err'=>3,'msg'=>'会员卡信息不存在'));exit;
        }

        //获取用户已领取过礼包的总数
        $post_card_gain = array(
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'member_info_id'=>$memberInfo['member_info_id'],
            'package_id'=> $package_id,
            'openid' =>$this->show_openid,
        );
        $package_count = $this->doCurlPostRequest( INTER_PATH_URL."package/member_gain_package_count" , $post_card_gain );

        if(isset($package_count['data']) && $package_count['data']>=$frequency){
            echo json_encode(array('err'=>2,'msg'=>'您已领取过礼包了'));exit;
        }

        //发送优惠套餐
        $packge_url = INTER_PATH_URL.'package/give';
        $package_data = array(
            'card_rule_id'=>$card_rule_id,
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'openid'=>$this->show_openid,
            'uu_code'=>$this->show_openid.'gaze'.uniqid(),
            'package_id'=>$package_id,
            'number'=>$frequency
        );
        $package = $this->doCurlPostRequest( $packge_url , $package_data );
        echo json_encode($package);
    }

    //卡券转赠页面
    public function givecard(){
        if(!empty($this->assign_data['org_domain'])){
            redirect("{$this->assign_data['org_domain']}/upgrade_page?id={$this->show_inter_id}");
        }else{
            redirect(site_url('./upgrade_page').'?id='.$this->show_inter_id);
        }
        $this->load->model('wx/Publics_model');
        $data['info'] =$this->Publics_model->get_fans_info($this->show_openid);
//	    $this->check_user_login();
        //获取卡券的详细
        $card_openid = isset($_GET['cardOpenid'])?$_GET['cardOpenid']:$this->show_openid;
        $member_card_id = isset($_GET['member_card_id'])?(int)$_GET['member_card_id']:0;
        $post_card_info_data = array(
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'openid'=>$card_openid,
            'member_card_id'=>$member_card_id,
        );
        $card_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/getinfo" , $post_card_info_data );
        if(isset($card_info['data'])){
            $data['card_info']=$card_info['data'];
        }else{
            $data['card_info']=array();
        }
        $data['card_openid'] = $card_openid;
        $data['openid'] = $this->show_openid;
        $data['inter_id'] = $this->show_inter_id;
        $this->load->model('wx/access_token_model');
        $this->load->model('wx/Publics_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->show_inter_id);
        $data['public'] = $this->Publics_model->get_public_by_id($this->show_inter_id);
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->show_inter_id);
        $this->template_show('member',$this->_template,'givecard',$data);
    }

    //转赠卡券挂起
    public function hang_card(){
        //卡券转赠挂起
        $post_give_status_url = INTER_PATH_URL.'membercard/setgiving';
        $post_give_statue_data = array(
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'openid'=>$this->show_openid,
            'member_card_id'=>$this->input->post('card_id'),
        );
        $result = $this->doCurlPostRequest( $post_give_status_url , $post_give_statue_data );
        echo json_encode($result);
    }

    //保存卡券转赠信息
    public function savegivecard(){
        $openid = isset($_POST['cardOpenid'])?$_POST['cardOpenid']:'';
        if(!$openid){ echo json_encode(array( 'err'=>1,'msg'=>'卡券用户信息不存在' )); }
        $card_id = isset($_POST['card_id'])?$_POST['card_id']:'';
        if(!$card_id){ echo json_encode(array( 'err'=>2,'msg'=>'卡券信息不存在' )); }
        $cardModule = 'vip';
        //卡券转赠
        $post_give_card_data = array(
            'member_card_id'=>$card_id,
            'from_openid'=>$openid,
            'to_openid'=>$this->show_openid,
            'token'=>$this->_token,
            'inter_id'=>$this->show_inter_id,
            'module'=>$cardModule,
            'scene'=>'give',
            'remark'=>'好友转赠',
        );
        $give_info = $this->doCurlPostRequest( INTER_PATH_URL."membercard/give" , $post_give_card_data );
        echo json_encode($give_info);
    }


    public function before_receive(){
        $gift_encrypt = $this->input->get('sf');
        $public = $this->publics_model->get_public_by_id($this->show_inter_id);
        $_pattern = "/^((http:\/\/)|(https:\/\/))?([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}/"; //匹配网址域名
        preg_match_all($_pattern,$public['domain'],$match);
        if(!$match){
            $org_domain = $public['domain'];
        }else{
            if(strpos($match[0][0],'http://') !== false OR strpos($match[0][0],'https://') !== false){
                $public_host = $match[0][0];
            }else{
                $public_host = "http://{$match[0][0]}";
            }
            $org_domain = $public_host;
        }
        $share_url = "{$org_domain}/membervip/card/receive?id={$this->show_inter_id}&share_interid={$this->inter_id}&sf={$gift_encrypt}";
        redirect($share_url);
    }

    //卡券详细页面
    public function receive(){
        $ec_code = $this->input->get('sf');
        MYLOG::w(@json_encode(array('id'=>$this->show_inter_id,'openid'=>$this->show_openid,'ec_code'=>$ec_code)),'membervip/debug-log','receive');
        if(empty($ec_code)) {
            if(!empty($this->assign_data['org_domain'])){
                redirect("{$this->assign_data['org_domain']}/membervip/center?id={$this->show_inter_id}");
            }else{
                redirect(site_url('membervip/center').'?id='.$this->show_inter_id);
            }
        }
        $assign_data['ec_code'] = $ec_code;
        $this->load->helper('qfglobal');
        $ec_code = base64_decode($ec_code);
        $ec_data = decrypt($ec_code,base64_encode($this->show_inter_id));
        MYLOG::w("decrypt | ".@json_encode(array('id'=>$this->show_inter_id,'openid'=>$this->show_openid,'ec_data'=>$ec_data)),'membervip/debug-log','receive');
        if(empty($ec_data)) {
            if(!empty($this->assign_data['org_domain'])){
                redirect("{$this->assign_data['org_domain']}/membervip/center?id={$this->show_inter_id}");
            }else{
                redirect(site_url('membervip/center').'?id='.$this->show_inter_id);
            }
        }
        $data = explode('**',$ec_data);
        MYLOG::w("receive_data | ".@json_encode(array('id'=>$this->show_inter_id,'openid'=>$this->show_openid,'data'=>$data)),'membervip/debug-log','receive');
        $member_card_id = isset($data[0])?$data[0]:0;
        $coupon_code = isset($data[1])?$data[1]:'';
        $gift_mem_id = isset($data[2])?$data[2]:0;
        //获取赠送优惠券的用户的信息
        $this->load->model('membervip/front/Member_model','mem');
        $gift_mem_info = $this->mem->get_member_info($gift_mem_id,'member_info_id,inter_id,open_id,name,member_mode,is_login,nickname');
        MYLOG::w("get_member_info | ".@json_encode(array('id'=>$this->show_inter_id,'openid'=>$this->show_openid,'data'=>$gift_mem_info)),'membervip/debug-log','receive');
        if(empty($gift_mem_info)) {
            if(!empty($this->assign_data['org_domain'])){
                redirect("{$this->assign_data['org_domain']}/membervip/center?id={$this->show_inter_id}");
            }else{
                redirect(site_url('membervip/center').'?id='.$this->show_inter_id);
            }
        }
        $assign_data['gift_mem_info'] = $gift_mem_info;

        $user = $this->mem->get_user_info($this->show_inter_id,$this->show_openid,'member_info_id,open_id,member_mode,is_login');
        MYLOG::w("get_user_info | ".@json_encode(array('id'=>$this->show_inter_id,'openid'=>$this->show_openid,'data'=>$user)),'membervip/debug-log','receive');
        $assign_data['user'] = $user;

        $post_data = array(
            'member_card_id' =>$member_card_id,
            'token'=>$this->_token,
            'openid'=>$gift_mem_info['open_id'],
            'inter_id'=>$gift_mem_info['inter_id'],
            'coupon_code'=>$coupon_code,
            'is_active'=>'t',
            'is_giving'=>'t',
            'is_use'=>'f',
            'is_useoff'=>'f'
        );

        $assign_data['inter_id'] = $this->show_inter_id;
        $assign_data['openid'] = $this->show_openid;
        $_card_info = $this->doCurlPostRequest(INTER_PATH_URL."membercard/get_info_by_code",$post_data);
        MYLOG::w("membercard_getinfo | ".@json_encode(array('id'=>$this->show_inter_id,'openid'=>$this->show_openid,'data'=>$_card_info)),'membervip/debug-log','receive');
        if(empty($gift_mem_info)) redirect(site_url('membervip/center').'?id='.$this->show_inter_id);
        $card_info = array();
        if(isset($_card_info['data'])) $card_info = $_card_info['data'];
        if(empty($card_info)) {
            if(!empty($this->assign_data['org_domain'])){
                redirect("{$this->assign_data['org_domain']}/membervip/center?id={$this->show_inter_id}");
            }else{
                redirect(site_url('membervip/center').'?id='.$this->show_inter_id);
            }
        }
        if(!empty($card_info)){
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
        }

        $assign_data['card_info'] = $card_info;

        $this->load->model('wx/access_token_model');
        $this->load->model('wx/Publics_model');
        $assign_data['public'] = $this->Publics_model->get_public_by_id($this->show_inter_id);

        $this->template_show('member',$this->_template,'receive',$assign_data);
    }


    //转赠优惠券挂起
    public function receive_card(){
        $ec_code = $this->input->post('ec_code');
        $this->api_write_log($ec_code,'ec_code');
        if(empty($ec_code)) {
            echo json_encode(array('err'=>40003,'msg'=>'领取失败'));exit;
        }
        $this->load->helper('qfglobal');
        $ec_code = base64_decode($ec_code);
        $ec_data = decrypt($ec_code,base64_encode($this->show_inter_id));
        MYLOG::w(array($ec_code,$ec_data,$this->show_inter_id,$this->show_openid),'membervip/debug-log','receive_card');

        if(empty($ec_data)) {
            echo json_encode(array('err'=>40003,'msg'=>'领取失败'));exit;
        }
        $data = explode('**',$ec_data);
        MYLOG::w(array($data,$this->show_inter_id,$this->show_openid),'membervip/debug-log','receive_card');

        $member_card_id = isset($data[0])?$data[0]:0;
        $coupon_code = isset($data[1])?$data[1]:'';
        $gift_mem_id = isset($data[2])?$data[2]:0;
        //获取赠送优惠券的用户的信息
        $this->load->model('membervip/front/Member_model','mem');
        $gift_mem_info = $this->mem->get_member_info($gift_mem_id,'member_info_id,inter_id,member_mode,is_login,open_id,name,nickname');
        MYLOG::w(array($gift_mem_info,$this->show_inter_id,$this->show_openid),'membervip/debug-log','receive_card');

        if(empty($gift_mem_info)){
            echo json_encode(array('err'=>40003,'msg'=>'转赠信息已失效'));exit;
        }
        $post_data = array(
            'member_card_id' =>$member_card_id,
            'token'=>$this->_token,
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
        $card_info = array();
        if(isset($_card_info['data'])) $card_info = $_card_info['data'];
        if(empty($card_info)){
            echo json_encode(array('err'=>40003,'msg'=>'优惠券已丢失'));exit;
        }

        //卡券转赠领取
        $receive_url = INTER_PATH_URL.'intercard/gift_receive';
        $post_data = array(
            'member_card_id' =>$member_card_id,
            'token'=>$this->_token,
            'openid'=>$this->show_openid,
            'inter_id'=>$this->show_inter_id,
            'gift_mem_info_id'=>$gift_mem_id,
            'coupon_code'=>$coupon_code,
            'uu_code'=>uniqid().time(),
            'card_id'=>$card_info['card_id'],
            'coupon_code'=>$card_info['coupon_code'],
            'module'=>$card_info['receive_module'],
            'scene'=>$card_info['receive_module'].' - receive',
            'remark'=>'领取好友优惠券'
        );
        $result = $this->doCurlPostRequest($receive_url,$post_data);
        $result['url'] = site_url("membervip/cardcenter?id={$this->inter_id}&f={$this->assign_data['segments']}");
        echo json_encode($result);exit;
    }

    //通过券码检测优惠券是否已经使用和核销
    public function check_useoff(){
        if(is_ajax_request()){
            $this->load->model('membervip/common/Public_model','common_model');
            $coupon_code = $this->input->post("coupon_code");

            $this->load->model('membervip/front/Member_model','mem');
            $user = $this->mem->get_user_info($this->show_inter_id,$this->show_openid,'member_info_id');

            $where = array(
                'open_id'=>$this->show_openid,
                'inter_id'=>$this->show_inter_id,
                'coupon_code'=>$coupon_code,
                'member_info_id'=>!empty($user['member_info_id'])?$user['member_info_id']:0
            );

            $member_card = $this->common_model->get_info($where,'member_card','is_use,is_useoff');
            if(!empty($member_card) && $member_card['is_use'] == 't' && $member_card['is_useoff'] == 't'){
                $this->_ajaxReturn('使用核销成功!',$this->assign_data['cardcenter_url'],1);
            }
            $this->_ajaxReturn('使用核销失败!');
        }
        $this->_ajaxReturn('很抱歉，请求失败，请联系管理员');
    }
}