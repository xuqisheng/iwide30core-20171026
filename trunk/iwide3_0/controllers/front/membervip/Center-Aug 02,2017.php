<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	用户中心
 *	@author  Frandon
 *	@copyright www.iwide.cn
 *	@version 4.0
 *	@Email 489291589@qq.com
 */
class Center extends MY_Front_Member
{
    //会员卡用户中心
    public function index(){
        if($this->inter_id=='a450089706' || $this->_template== 'phase2' || $this->_template== 'green' ||$this->_template== 'yinzuo'||$this->_template== 'zhouji'||$this->_template== 'yasite' ||$this->_template== 'highclass' ||$this->_template== 'changchun') redirect('membervip/center/member_center?id='.$this->inter_id);
        $this->load->model('wx/Publics_model');
        $data['info'] =$this->Publics_model->get_fans_info($this->openid);
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $center_data = $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $data['centerinfo'] = isset($center_data['data']) ? $center_data['data'] : null;
        //获取会员中心菜单列表
        $post_center_config_url = PMS_PATH_URL."adminmember/get_center_info";
        $post_center_config_data = array(
            'inter_id'=>$this->inter_id,
        );
        $center_config = $this->doCurlPostRequest( $post_center_config_url , $post_center_config_data )['data'];
        if(isset($center_config['value'])){
            $data['menukey'] = array_unique( array_column($center_config['value'],'group'));
            sort($data['menukey']);
        }
        //检测是否是分销账号和是否是协议客用户
        $this->load->model ( 'distribute/staff_model' );
        $saler_info = $this->staff_model->saler_info ( $this->openid, $this->inter_id );
        if($saler_info) {
            if ($saler_info && $saler_info ['status'] == 2){
                if(isset($saler_info ['distribute_hidden']) && $saler_info ['distribute_hidden'] == 0){
                    $data['isDistribution'] = 1;
                }else{
                    $data['isDistribution'] = 0;
                }
                $data['is_club'] = $saler_info['is_club'];
            }else{
                $data['isDistribution'] = 0;
                $data['is_club'] = 0;
            }
        }else{
            $data['isDistribution'] = 0;
            $data['is_club'] = 0;
        }
        //检测是否是绑定用户，如果是，绑定过后，，去掉绑定菜单
        if( $data['centerinfo']['value']=='perfect' ){
            if( $data['centerinfo']['id_card_no'] || $data['centerinfo']['pms_user_id'] ){
                $is_binning = true;
            }else{
                $is_binning = false;
            }
        }else{
            $is_binning = false;
        }
        $data['menu'] = isset($center_config['value'])?$center_config['value']:array();

        $g_key = 0;
        $end_group = 1;
        foreach ($data['menu'] as $key => $value) {
            if(isset($data['centerinfo']['is_login']) && $data['centerinfo']['is_login']=='f' && $value['modelname']=='我的电子会员卡') unset($data['menu'][$key]);

            if($data['is_club']==0 && $value['modelname']=='社群客' ){
                unset($data['menu'][$key]);
            }
            if($data['isDistribution']==0 && ($value['modelname']=='全员营销' || $value['modelname']=='分销中心')){
                unset($data['menu'][$key]);
            }

            if($data['isDistribution']==1 && $value['modelname']=='分销注册'){
                unset($data['menu'][$key]);
            }

            if( $is_binning && ($value['modelname']=='会员登录' || $value['modelname']=='会员绑定' || $value['modelname']=='绑定登录' ) ){
                unset($data['menu'][$key]);
            }
        }

        /*扫描权限地址*/
        $this->load->model('membervip/common/Public_model','common_model');
        $where = array(
            'openid'=>$this->openid,
            'inter_id'=>$this->inter_id
        );

        $scanqr_auth = $this->common_model->get_info($where,'scanqr_auth');
        if(!empty($scanqr_auth) && $scanqr_auth['status']==1){
            $end_key = $g_key + 1;
            $data['menu'][$end_key] = array(
                'group'=>$end_group,
                'modelname'=>'扫码核销',
                'ico'=>'ui_icon16',
                'link'=>$scanqr_auth['url'],
            );
        }

        //设置优惠券中心链接 - star
        $this->load->model('membervip/common/Public_model','member_public');
        $this->load->model('wx/Publics_model','publics_model');
        $where = array(
            'inter_id'=>$this->inter_id,
            'type_code'=>'member_card_url'
        );
        $card_host_info = $this->member_public->get_info($where,'inter_member_config');
        $card_host = !empty($card_host_info['value'])?$card_host_info['value']:'';
        if(!empty($card_host)){
            $_card_host = explode(',',$card_host);
            $public_host = !empty($_card_host[0])?$_card_host[0]:'';
            $public_inter_id = !empty($_card_host[1])?$_card_host[1]:'';
            $ec_data = $this->inter_id.$this->openid;
            $public = $this->publics_model->get_public_by_id ($this->inter_id);
            $key = $public['app_secret'];
            $encrypt = urlencode(kecrypt($ec_data,$key));
            $segments = base64_encode("{$this->inter_id}***{$this->openid}***{$encrypt}");
            $card_url = "{$public_host}/membervip/cardcenter?id={$public_inter_id}&f={$segments}";
            $data['card_url'] = $card_url;
        }

        $data['inter_id']=$this->inter_id;
        $data['filed_name'] = $this->_template_filed_names;
        $this->template_show('member',$this->_template,'center',$data);
    }


    //会员卡用户中心
    public function member_center(){

        $this->load->model('wx/Publics_model','publics_model');
        $data['info'] =$this->publics_model->get_fans_info($this->openid);
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $center_data = $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $data['centerinfo'] = isset($center_data['data']) ? $center_data['data'] : null;
        //获取会员中心菜单列表
        $post_center_config_url = PMS_PATH_URL."adminmember/get_member_center_info";
        $post_center_config_data = array(
            'inter_id'=>$this->inter_id,
        );
        $center_config = $this->doCurlPostRequest( $post_center_config_url , $post_center_config_data )['data'];
        //检测是否是分销账号和是否是协议客用户
        $this->load->model ( 'distribute/staff_model' );
        $saler_info = $this->staff_model->saler_info ( $this->openid, $this->inter_id );
        if($saler_info) {
            if ($saler_info && $saler_info ['status'] == 2){
                if(isset($saler_info ['distribute_hidden']) && $saler_info ['distribute_hidden'] == 0){
                    $data['isDistribution'] = 1;
                }else{
                    $data['isDistribution'] = 0;
                }
                $data['is_club'] = $saler_info['is_club'];
            }else{
                $data['isDistribution'] = 0;
                $data['is_club'] = 0;
            }
        }else{
            $data['isDistribution'] = 0;
            $data['is_club'] = 0;
        }
        //检测是否是绑定用户，如果是，绑定过后，，去掉绑定菜单
        if( $data['centerinfo']['value']=='perfect' ){
            if( $data['centerinfo']['id_card_no'] || $data['centerinfo']['pms_user_id'] ){
                $is_binning = true;
            }else{
                $is_binning = false;
            }
        }else{
            $is_binning = false;
        }
//        $data['menu'] = isset($center_config['value'])?$center_config['value']:array();
        $data['menu'] = isset($center_config['nav_conf'])? json_decode($center_config['nav_conf'],true):array();


        $this->load->model ( 'club/Clubs_model' );
        $show_club= $this->Clubs_model->show_club_reg ($this->inter_id, $this->openid, $center_data['data']['member_lvl_id']);
        if(!empty($data['menu'])){
            $g_key = 0;
            foreach($data['menu'] as $group_key => &$group_menu){
                $g_key = $group_key;
                foreach($group_menu as $menu_key => &$menu_link){
                    switch($menu_link['modelname']){
                        case '我的电子会员卡':
                            if(isset($data['centerinfo']['is_login']) && $data['centerinfo']['is_login']=='f')
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '社群客':
                            if($data['is_club']==0)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '激活社群客':
                            if($show_club['status']!=1)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '全员营销':
                            if($data['isDistribution']==0)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '分销中心':
                            if($data['isDistribution']==0)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '分销注册':
                            if($data['isDistribution']==1)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '会员登录':
                            if($is_binning)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '会员绑定':
                            if($is_binning)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '绑定登录':
                            if($is_binning)
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '购买会员卡'://洲际购买会员卡定制，只有审核通过会员才显示
                            if($data['centerinfo']['member_lvl_id']=='609'&&$this->inter_id=='a483582961')
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        case '收益中心':        //仅泛分销员可见，粉丝与分销员不可见
                            $this->load->model('distribute/Idistribute_model');
                            $fansInfo = $this->Idistribute_model->fans_is_saler($this->inter_id,$this->openid);
                            if(!$fansInfo){  //非分销人员
                                unset($data['menu'] [$group_key][$menu_key]);
                            }else{
                                $salesInfo = json_decode($fansInfo,true);
                                if($salesInfo['typ'] != 'FANS'){    //非泛分销人员
                                    unset($data['menu'] [$group_key][$menu_key]);
                                }
                            }
                            break;
                        case '提交资料':
                            if($data['centerinfo']['member_lvl_id'] == '958')
                                unset($data['menu'] [$group_key][$menu_key]);
                            break;
                        default:
                            break;
                    }
                }
            }

            /*扫描权限地址*/
            $this->load->model('membervip/common/Public_model','common_model');
            $where = array(
                'openid'=>$this->openid,
                'inter_id'=>$this->inter_id
            );

            $scanqr_auth = $this->common_model->get_info($where,'scanqr_auth');
            if(!empty($scanqr_auth) && $scanqr_auth['status']==1){
                $end_key = $g_key + 1;
                $data['menu'][$end_key][0] = array(
                    'icon'=>'ui_icon16',
                    'modelname'=>'扫码核销',
                    'link'=>$scanqr_auth['url'],
                    'is_login'=>2,
                    'listorder'=>0
                );
            }
        }

        //设置优惠券中心链接 - star
        $this->load->model('membervip/common/Public_model','member_public');
        $where = array(
            'inter_id'=>$this->inter_id,
            'type_code'=>'member_card_url'
        );
        $card_host_info = $this->member_public->get_info($where,'inter_member_config');
        $card_host = !empty($card_host_info['value'])?$card_host_info['value']:'';
        if(!empty($card_host)){
            $_card_host = explode(',',$card_host);
            $public_host = !empty($_card_host[0])?$_card_host[0]:'';
            $public_inter_id = !empty($_card_host[1])?$_card_host[1]:'';
            $ec_data = $this->inter_id.$this->openid;
            $public = $this->publics_model->get_public_by_id ($this->inter_id);
            $key = $public['app_secret'];
            $encrypt = urlencode(kecrypt($ec_data,$key));
            $segments = base64_encode("{$this->inter_id}***{$this->openid}***{$encrypt}");
            $card_url = "{$public_host}/membervip/cardcenter?id={$public_inter_id}&f={$segments}";
            $data['card_url'] = $card_url;
        }

        $data['inter_id'] = $this->inter_id;
        $data['filed_name'] = $this->_template_filed_names;
        if ($this->inter_id=='a476756979'){
            $this->_template='yinzuo';//银座测试用强制转换皮肤
        }

        $this->template_show('member',$this->_template,'center_new',$data);
    }
    //会员卡用户资料
    public function info(){
        $post_config_url = PMS_PATH_URL."adminmember/getmodifyconfig";
        $post_config_data =  array(
            'inter_id'=>$this->inter_id,
        );
        //请求资料信息
        $data['modify_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息
        $data['info'] =$this->Publics_model->get_fans_info($this->openid);
        $data['centerinfo'] = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
        $data['inter_id'] = $this->inter_id;
        $this->template_show('member',$this->_template,'memberinfo',$data);
    }

    //储值卡二维码页面
    public function qrcode(){
        $data['centerinfo'] = array();
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息
        $centerinfo = $this->doCurlPostRequest($post_center_url,$post_center_data);
        if(isset($centerinfo['data']) && !empty($centerinfo['data'])){
            $data['centerinfo'] = $centerinfo['data'];
        }
        $this->template_show('member',$this->_template,'qrcode',$data);
    }

    public function remote(){
        if($this->inter_id!='a449675133'){
            $this->index();
            exit;
        }
        //获取appID
        $this->load->library ("MYLOG");
        $appid=$this->db->query('SELECT * FROM `iwide_publics` WHERE `inter_id` LIKE \'a449675133\'')->result_array()['0']['app_id'];
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $center_data = $this->doCurlPostRequest( $post_center_url , $post_center_data );
        if(!empty($center_data['data']['membership_number'])){
            $url='http://mts.xiezhuwang.com/hotelmaster/firstLookV2?appID='.$appid.'&memberKey='.$center_data['data']['membership_number'];
            header("Location:".$url);
            exit;
        }else {
            $this->index();
            exit;
        }
    }

    public function qrcodecon(){
        $this->load->helper ('phpqrcode');
        $url = urldecode($_GET["data"]);
        $margin = isset($_GET['margin']) ? $_GET['margin']:10;
        QRcode::png($url,false,'Q',30,$margin,true);
    }

    public function soma(){
        $this->load->model('membervip/front/Member_model','mem');
        $user = $this->mem->get_user_info($this->inter_id,$this->openid,'member_mode,is_login');
        if(empty($user) || $user['member_mode']=='1' || $user['is_login']=='f'){
            redirect('membervip/login').'?id='.$this->inter_id;
        }elseif ($user['is_login']=='t'){
            redirect('http://junting.hfmc99.com/index.php/soma/package/index').'?id='.$this->inter_id;
        }
        redirect(site_url('membervip/center').'?id='.$this->inter_id);
    }

    public function bgywechat(){
        $this->load->model('membervip/front/Member_model','m_model');
        $this->load->library("MYLOG");
        $user = $this->m_model->get_user_info($this->inter_id,$this->openid);
        MYLOG::w(json_encode(array('res'=>$user)),'front/membervip/center','bgywechat');
        if(empty($user)) redirect(site_url('membervip/center').'?id='.$this->inter_id);
        if($user['member_mode']==1) redirect(site_url('membervip/login').'?id='.$this->inter_id);
        $jumpurl = "http://h5.bgyhotel.com:9090/m/h5/wechat?icNum={$user['membership_number']}&mobile={$user['telephone']}&openId={$user['open_id']}";
        MYLOG::w(json_encode(array('jumpurl'=>$jumpurl)),'front/membervip/center','bgywechat');
        redirect($jumpurl);
    }


    public function showopenid(){


        echo $this->openid;
    }
}
?>