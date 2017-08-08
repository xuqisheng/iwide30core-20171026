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
class Resetpassword extends MY_Front_Member
{
	//用户重置密码页面
	public function index(){
        $data = array();
        if(!$this->is_restful()){
            $data = ResetpasswordService::getInstance()->index($this->inter_id);
        }
        $data['page_title'] = '会员卡重置密码';
        $this->template_show('member',$this->_template,'resetpassword',$data);
    }

	//保存重置密码
	public function saveresetpassword(){
		$login_result = ResetpasswordService::getInstance()->saveresetpassword($this->inter_id,$this->openid);
		echo json_encode($login_result);
	}

    //储值卡重置密码页面
    public function resetbindpwd(){
        $data = array();
        if(!$this->is_restful()){
            $data = ResetpasswordService::getInstance()->resetbindpwd($this->inter_id);
        }
        $this->template_show('member',$this->_template,'resetbindpwd',$data);
    }

    //保存重置密码
    public function saveresetbindpwd(){
        $login_result = ResetpasswordService::getInstance()->saveresetbindpwd($this->inter_id,$this->openid);
        echo json_encode($login_result);
    }
}
?>