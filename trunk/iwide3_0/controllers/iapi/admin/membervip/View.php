<?php
use App\services\member\admin\ViewService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	显示配置
*	@author  liwensong
*   @copyright www.iwide.cn
*   @version 4.0
*   @Email septet-l@outlook.com
*/
class View extends MY_Admin_Iapi
{
	//会员中心显示配置
    public function index(){
        $inter_id = $this->session->get_admin_inter_id();
        $data = ViewService::getInstance()->index($inter_id);
        $ext['links']['save'] = EA_const_url::inst()->get_url('iapi/v1/membervip/view/save_post',array('id'=>$inter_id));
        $ext['links']['index'] = EA_const_url::inst()->get_url('membervip/view',array('id'=>$inter_id));
        $this->out_put_msg(1,'',$data,'membervip/admin/view/index', 200,$ext);
    }

    //皮肤配置
    public function skin(){
        $inter_id = $this->session->get_admin_inter_id();
        $data = ViewService::getInstance()->skin($inter_id);
        $ext['links']['save'] = EA_const_url::inst()->get_url('iapi/v1/membervip/view/save_skin',array('id'=>$inter_id));
        $ext['links']['index'] = EA_const_url::inst()->get_url('membervip/view/skin',array('id'=>$inter_id));
        $this->out_put_msg(1,'',$data,'membervip/admin/view/skin', 200,$ext);
    }

    //保存会员中心显示配置
    public function save_post(){
        $inter_id = $this->session->get_admin_inter_id();
        $result = ViewService::getInstance()->save_post($inter_id);
        $this->out_put_msg($result['status'],$result['msg'],$result['data'],'membervip/card/gift_card',200);
    }

    //保存皮肤配置
    public function save_skin(){
        $inter_id = $this->session->get_admin_inter_id();
        $result = ViewService::getInstance()->save_skin($inter_id);
        $this->out_put_msg($result['status'],$result['msg'],$result['data'],'membervip/card/gift_card',200);
    }
}