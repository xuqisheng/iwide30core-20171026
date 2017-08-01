<?php
use App\services\member\ResetpasswordService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户重置密码
*	@author  lijiaping
*   @copyright www.iwide.cn
*   @version 4.0
*   @Email lijiaping@mofly.cn
*/
class Resetpassword extends MY_Front_Member_Iapi
{
	//用户重置密码页面
	public function index(){
		$data = ResetpasswordService::getInstance()->index($this->inter_id);
        $ext['links']['saveresetpassword'] = site_url('iapi/membervip/resetpassword/saveresetpassword')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/resetpassword/index',$ext);

    }

	//保存重置密码
	public function saveresetpassword(){
		$login_result = ResetpasswordService::getInstance()->saveresetpassword($this->inter_id,$this->openid);
        $login_result = $this->parse_curl_msg($login_result);
        if($login_result['code']==1){
            $ext['links']['next'] = site_url('membervip/login')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/resetpassword/saveresetpassword',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/resetpassword/saveresetpassword');
        }
	}

    //储值卡重置密码页面
    public function resetbindpwd(){
        $data = ResetpasswordService::getInstance()->resetbindpwd($this->inter_id);
        $ext['links']['saveresetbindpwd'] = site_url('iapi/membervip/perfectinfo/saveresetbindpwd')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/resetpassword/resetbindpwd',$ext);

    }

    //保存重置密码
    public function saveresetbindpwd(){
        $login_result = ResetpasswordService::getInstance()->saveresetbindpwd($this->inter_id,$this->openid);
        $login_result = $this->parse_curl_msg($login_result);
        if($login_result['code']==1){
            $ext['links']['next'] = site_url('membervip/login')."?id=$this->inter_id";
            $this->out_put_msg(1,$login_result['msg'],$login_result['data'],'membervip/resetpassword/saveresetbindpwd',$ext);
        }else{
            $this->out_put_msg(3,$login_result['msg'],$login_result['data'],'membervip/resetpassword/saveresetbindpwd');
        }
    }
}
?>