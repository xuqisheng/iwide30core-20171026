<?php
use App\services\member\CenterService;
use App\services\member\PosterService;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	海报
 *	@author  liwensong
 *	@copyright www.iwide.cn
 *	@version 4.0
 *	@Email septet-l@outlook.com
 */
class Poster extends MY_Front_Member
{
    public function posterqrcode(){
        $this->load->helper ('phpqrcode');
        $service = \App\services\soma\IdistributeService::getInstance();
        $urlData = $service->getProductIdistributeUrl($this->inter_id, $this->openid, '150812');
        if(!empty($urlData['status']) && $urlData['status'] == '1'){
            $url = !empty($urlData['data']['url']) ? $urlData['data']['url'] : '#';
            QRcode::png($url,false,'L',7,0,true);
        }
    }


    //我的二维码海报（分销）
    public function mineposter(){
        $data = array();
        $productId = '150812';
        $this->_template = 'phase2';//目前先做一个皮肤下的
        $data['page_title'] = '终极招募令';
        if(!empty($this->input->get('debug_openid'))){
            $this->openid = 'oa7xWwjdu0oAUPtwQ2xguaStgaZM';
        }

        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );

        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $user_info = array();
        $center_data = $this->doCurlPostRequest($post_center_url,$post_center_data);
        if(!empty($center_data['data'])){
            $user_info = $center_data['data'];
        }

        $face = 'noface';
        $this->load->model('distribute/Distribute_ext_model','distribute_ext_model');
        $is_fens = $this->distribute_ext_model->check_fans($this->inter_id,$this->openid,false);

        $identity = '';
        $buycount = '';
        $service = \App\services\soma\IdistributeService::getInstance();
        if(!empty($is_fens)){
            if($is_fens['typ'] == 'FANS' OR $is_fens['typ'] == 'STAFF'){
                $identity = '招募者';
            }

            if($is_fens['typ'] == 'FANS' && isset($is_fens['info'])){
                $is_fens['info'] = (array) $is_fens['info'];
                $fansSalerData = $service->getSalerProductSalesInfo($this->inter_id,$productId,$is_fens['info']['saler'],1);
                if(!empty($fansSalerData['status']) && $fansSalerData['status'] == '1'){
                    $buycount =!empty($fansSalerData['data'][$productId]) ? floatval($fansSalerData['data'][$productId]) : 0;
                }
            }elseif ($is_fens['typ'] == 'STAFF' && isset($is_fens['info'])){
                $is_fens['info'] = (array) $is_fens['info'];
                $salerData = $service->getSalerProductSalesInfo($this->inter_id,$productId,$is_fens['info']['saler'],2);
                if(!empty($salerData['status']) && $salerData['status'] == '1'){
                    $buycount =!empty($salerData['data'][$productId]) ? floatval($salerData['data'][$productId]) : 0;
                }
            }
        }

        $stock = 0;
        $SalesInfo = $service->getProductSalesInfo($this->inter_id, $productId);
        if(!empty($SalesInfo['status']) && $SalesInfo['status'] == '1'){
            $stock =!empty($SalesInfo['data'][$productId]) ? floatval($SalesInfo['data'][$productId]) : 0;
        }

        if($stock > 500) $stock = 500;

        $sales = 10000 - $stock;

        if(empty($identity) && empty($this->input->get('test'))){
            redirect(site_url('soma/package/package_detail?pid=150812') . '&id=' . $this->inter_id);
        }

        $identity2 = '';

        if($buycount > 0){
            $identity2 = '馅饼侠';
            $face = 'face';
        }

        $Aug20 = '2017-08-20';
        $nowDate = date('Y-m-d');
        $count_down = 0;
        if(strtotime($Aug20) > strtotime($nowDate)){
            $count_down = date('d',strtotime($Aug20)) - date('d',strtotime($nowDate));
        }elseif(strtotime($Aug20) < strtotime($nowDate)){
            $count_down = -1;
        }

        $lvl_name = '青铜';
        if($buycount >= 1 && $buycount <= 4){
            $lvl_name = '白银';
        }elseif ($buycount >= 5 && $buycount <= 9){
            $lvl_name = '黄金';
        }elseif ($buycount >= 10){
            $lvl_name = '王者';
        }

        $this->load->model('wx/Publics_model','Publics_model');

        $info = $this->Publics_model->get_fans_info_one($this->inter_id,$this->openid);


        /*获取微信JSSDK配置*/
        $data['wx_config'] = $this->_get_sign_package($this->inter_id);
        $js_api_list = $menu_show_list = $menu_hide_list= '';
        $data['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        $data['js_api_list'] = $data['base_api_list'];

        foreach ($data['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }

        $data['js_api_list']= substr($js_api_list, 0, -1);

        //主动显示某些菜单
        $data['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline');
        $menu_show_list = '';
        foreach ($data['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $data['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        $data['js_menu_hide']= array('menuItem:copyUrl','menuItem:share:email','menuItem:originPage','menuItem:favorite');
        $menu_hide_list = '';
        foreach ($data['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $data['js_menu_hide']= substr($menu_hide_list, 0, -1);

        $data['js_share_config']['title'] = '第三季馅饼侠活动已成功招募10000，完美收官！';
        $data['js_share_config']['link'] = EA_const_url::inst()->get_url('*/*/mineposter',array('id'=>$this->inter_id));
        $data['js_share_config']['imgUrl'] = !empty($info['headimgurl']) ? $info['headimgurl'] : '#';
        $data['js_share_config']['desc'] = "这是我的馅饼侠海报，快点进来看看吧！";

        $data['identity_type'] = $face;
        $data['nickname'] = !empty($user_info['nickname']) ? $user_info['nickname'] : '微信粉丝';
        $data['countdown'] = $count_down;
        $data['buycount'] = $buycount;
        $data['endtime'] = '8月20日24:00';
        $data['identity'] = $identity;
        $data['identity2'] = $identity2;
        $data['lvl_name'] = $lvl_name;
       // $data['stock'] = $stock;
        $data['stock'] = 0;
        //$data['sales'] = $sales;
        $data['sales'] = 10000;
        if($this->input->get('debug') == 'on'){
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
        $this->template_show('member',$this->_template,'mine_poster',$data);
    }


    /**
     * 获取微信JSSDK配置信息
     * @param $inter_id
     * @param string $url
     * @return array
     */
    protected function _get_sign_package($inter_id, $url=''){
        $this->load->helper('common');
        $this->load->model('wx/access_token_model');
        $jsapiTicket = $this->access_token_model->get_api_ticket( $inter_id );

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if(!$url)
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();
        $public = $this->Publics_model->get_public_by_id( $inter_id );

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