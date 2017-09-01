<?php
use App\services\member\BalanceService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户积分 
*	@author  lijiaping
*   @copyright www.iwide.cn
*   @version 4.0
*   @Email lijiaping@mofly.cn
*/
class Balance extends MY_Front_Member
{
	//会员余额记录
	public function index(){
		$data = array();
        if(!$this->is_restful()){
            $data = BalanceService::getInstance()->index($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        }
        $data['page_title'] = '储值记录';
        $this->template_show('member',$this->_template,'balance',$data);
	}


    //余额支付密码设置
    public function setpwd(){
        $data = array();
        if(!$this->is_restful()){
    	   $data = BalanceService::getInstance()->setpwd($this->inter_id,$this->openid,$this->_token);
        }
        if(!empty($data['redirect'])){
	    	Header( "Location:".$data['redirect'] );exit;
        }
        $data['page_title'] = '设置支付密码';
        $this->template_show('member',$this->_template,'setpwd',$data);
    }

    //保存支付密码设置
    public function save_setpwd(){
    	$status = BalanceService::getInstance()->save_setpwd($this->inter_id,$this->openid,$this->_token);
    	echo json_encode($status);
    }

    //余额支付密码修改
    public function changepwd(){
        $data = array();
        if(!$this->is_restful()){
           $data = BalanceService::getInstance()->changepwd($this->inter_id,$this->openid,$this->_token);
        }
        if(!empty($data['redirect'])){
        	Header( "Location:".$data['redirect']);exit;
        }
        $data['page_title'] = '修改支付密码';
        $this->template_show('member',$this->_template,'changepwd',$data);
    }

    //保存支付密码修改
    public function save_changepwd(){
    	$status = BalanceService::getInstance()->save_changepwd($this->inter_id,$this->openid,$this->_token);
    	echo json_encode($status);
    }

    public function pay(){
        $data = array();
        if(!$this->is_restful()){
           $data = BalanceService::getInstance()->pay($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        }
        $data['page_title'] = '储值支付';

        /*获取微信JSSDK配置*/
        //主动隐藏某些菜单
        $base_api_list = array('hideMenuItems', 'showMenuItems');
        $js_api_list = '';
        foreach ($base_api_list as $v) {
            $js_api_list .= "'{$v}',";
        }
        $data['js_api_list'] = substr($js_api_list, 0, -1);

        $js_menu_hide = array('menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl');
        $menu_hide_list = '';
        foreach ($js_menu_hide as $v) {
            $menu_hide_list .= "'{$v}',";
        }
        $data['js_menu_hide'] = substr($menu_hide_list, 0, -1);
        /*end*/

        $this->template_show('member',$this->_template,'balancepay',$data);

    }

    public function sub_pay(){
        $status = BalanceService::getInstance()->sub_pay($this->inter_id,$this->openid);
        if(!empty($status['err'])){
            $this->_ajaxReturn($status['msg'],$status['data'],0);
        }else{
            $this->_ajaxReturn($status['msg'],$status['data'],1);
        }
    }

    //储值支付成功
    public function okpay(){
        $data = array();
        $view = 'okpay';
        if(!$this->is_restful()){
        $data = BalanceService::getInstance()->okpay($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        }else{
            $view = 'okpay_card';
        }
        $data['page_title'] = '支付结果';

        /*获取微信JSSDK配置*/
        //主动隐藏某些菜单
        $base_api_list = array('hideMenuItems', 'showMenuItems');
        $js_api_list = '';
        foreach ($base_api_list as $v) {
            $js_api_list .= "'{$v}',";
        }
        $data['js_api_list'] = substr($js_api_list, 0, -1);

        $js_menu_hide = array('menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl');
        $menu_hide_list = '';
        foreach ($js_menu_hide as $v) {
            $menu_hide_list .= "'{$v}',";
        }
        $data['js_menu_hide'] = substr($menu_hide_list, 0, -1);
        /*end*/

        $this->template_show('member',$this->_template,$view,$data);
    }

    //储值支付失败
    public function nopay(){
        $data = array();
        $view = 'nopay';
        if(!$this->is_restful()){
        $data = BalanceService::getInstance()->nopay($this->inter_id);
        }else{
            $view = 'nopay_card';
        }
        $data['page_title'] = '支付结果';

        /*获取微信JSSDK配置*/
        //主动隐藏某些菜单
        $base_api_list = array('hideMenuItems', 'showMenuItems');
        $js_api_list = '';
        foreach ($base_api_list as $v) {
            $js_api_list .= "'{$v}',";
        }
        $data['js_api_list'] = substr($js_api_list, 0, -1);

        $js_menu_hide = array('menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl');
        $menu_hide_list = '';
        foreach ($js_menu_hide as $v) {
            $menu_hide_list .= "'{$v}',";
        }
        $data['js_menu_hide'] = substr($menu_hide_list, 0, -1);
        /*end*/

        $this->template_show('member',$this->_template,$view,$data);
    }
}
?>