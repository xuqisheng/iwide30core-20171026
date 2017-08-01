<?php
use App\services\member\RegService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户中心
*	@author  lijiaping
*   @copyright www.iwide.cn
*   @version 4.0
*   @Email lijiaping@mofly.cn
*/
class Reg extends MY_Front_Member_Iapi
{
	//会员卡登录页面
    public function index(){
        $data = RegService::getInstance()->index($this->inter_id);
        $ext['links']['login'] = site_url('membervip/login/index')."?id=$this->inter_id";
        $ext['links']['savereg'] = site_url('iapi/membervip/reg/savereg')."?id=$this->inter_id";

        $this->out_put_msg(1,'',$data,'membervip/reg/index',$ext);

    }
    
    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 注册登录
     */
    public function savereg(){
        $login_result = RegService::getInstance()->savereg($this->inter_id,$this->openid,$this->_token);
        $login_result = $this->parse_curl_msg($login_result);
        if($login_result['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/reg/savereg',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/reg/savereg');
        }
    }

    public function send_tmp_msg(){
        $retrun = RegService::getInstance()->send_tmp_msg($this->inter_id,$this->openid);
        if($retrun['code']=='1001'){
            $this->out_put_msg(1,$retrun['msg'],'','membervip/reg/send_tmp_msg');
        }else{
            $this->out_put_msg(3,$retrun['msg'],'','membervip/reg/send_tmp_msg');
        }
    }

    public function pic_code(){
        RegService::getInstance()->pic_code();
    }

    //会员卡激活页面
    public function activate(){
        $data = RegService::getInstance()->activate($this->inter_id);
        $ext['links']['do_activate'] = site_url('iapi/membervip/reg/do_activate')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/reg/activate',$ext);

    }

    //激活会员卡保存
    public function do_activate(){
        $login_result = RegService::getInstance()->do_activate($this->inter_id,$this->openid,$this->_token);
        
        $login_result = $this->parse_curl_msg($login_result);
        if($login_result['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/reg/do_activate',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/reg/do_activate');
        }
    }
}
?>