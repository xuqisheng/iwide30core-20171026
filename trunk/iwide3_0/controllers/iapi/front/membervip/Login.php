<?php
use App\services\member\LoginService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户中心
*	@author  lijiaping
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email lijiaping@mofly.cn
*/
class Login extends MY_Front_Member_Iapi
{
	//会员卡登录页面
    public function index(){
        
        $data = LoginService::getInstance()->index($this->inter_id);
        $ext['links']['savelogin'] = site_url('iapi/membervip/login/savelogin')."?id=$this->inter_id";
        $ext['links']['reg'] = site_url('membervip/reg/index')."?id=$this->inter_id";
        $ext['links']['resetpassword'] = site_url('membervip/resetpassword/index')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/login/index',$ext);

    }

    //绑定登录模式
    public function binning_login(){
        
        $data = LoginService::getInstance()->binning_login($this->inter_id);

        $ext['links']['savelogin'] = site_url('iapi/membervip/login/savelogin')."?id=$this->inter_id";

        $this->out_put_msg(1,'',$data,'membervip/login/binning_login',$ext);
    }

    //储值卡绑定
    public function bindcard(){
        
        $data = LoginService::getInstance()->bindcard($this->inter_id);

        $ext['links']['savebindcard'] = site_url('iapi/membervip/login/savebindcard')."?id=$this->inter_id";
        $ext['links']['resetbindpwd'] = site_url('membervip/resetpassword/resetbindpwd')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/login/bindcard',$ext);

    }

    //保存储值卡绑定
    public function savebindcard(){
        $bind_result = LoginService::getInstance()->savebindcard($this->inter_id,$this->openid);
        
        $bind_result = $this->parse_curl_msg($bind_result);
        if($bind_result['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$bind_result['msg'],$bind_result['data'],'membervip/login/savebindcard',$ext);
        }else{
            $this->out_put_msg(3,$bind_result['msg'],$bind_result['data'],'membervip/login/savebindcard');
        }
    }

    //登录保存
    public function savelogin(){
        $login_result = LoginService::getInstance()->savelogin($this->inter_id,$this->openid);
        
        $login_result = $this->parse_curl_msg($login_result);
        if($login_result['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/login/savelogin',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/login/savelogin');
        }
    }

    public function dowxlogin(){
        $login_result = LoginService::getInstance()->wxlogin($this->inter_id,$this->openid);
        if($login_result['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/login/dowxlogin',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/login/dowxlogin');
        }
    }

    //退出登录
    public function outlogin(){
        $login_result = LoginService::getInstance()->outlogin($this->inter_id,$this->openid);
        
        $login_result = $this->parse_curl_msg($login_result);
        if($login_result['code']==1000){
            $ext['links']['login'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/login/outlogin',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/login/outlogin');
        }
    }
}
?>