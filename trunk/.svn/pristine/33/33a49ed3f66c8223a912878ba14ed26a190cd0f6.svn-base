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
class Balance extends MY_Front_Member_Iapi
{
	//会员余额记录
	public function index(){
		$data = BalanceService::getInstance()->index($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        $ext['links']['recharge'] = site_url('iapi/membervip/balance')."?id=$this->inter_id&credit_type=1";
        $ext['links']['consume'] = site_url('iapi/membervip/balance')."?id=$this->inter_id&credit_type=2";
        $this->out_put_msg(1,'',$data,'membervip/balance/index',$ext);
	}


    //余额支付密码设置
    public function setpwd(){
    	$data = BalanceService::getInstance()->setpwd($this->inter_id,$this->openid,$this->_token);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
        }
        $ext['links']['save_setpwd'] = site_url('iapi/membervip/balance/save_setpwd')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/balance/setpwd',$ext);
    }

    //保存支付密码设置
    public function save_setpwd(){
        $status = BalanceService::getInstance()->save_setpwd($this->inter_id,$this->openid,$this->_token);
        $status = $this->parse_curl_msg($status);
        
    	if($status['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$status['msg'],$status['data'],'membervip/balance/save_setpwd',$ext);
        }else{
            $this->out_put_msg(3,$status['msg'],$status['data'],'membervip/balance/save_setpwd');
        }
    }

    //余额支付密码修改
    public function changepwd(){
    	$data = BalanceService::getInstance()->changepwd($this->inter_id,$this->openid,$this->_token);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
        }
        $ext['links']['save_changepwd'] = site_url('iapi/membervip/balance/save_changepwd')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/balance/changepwd',$ext);
    }

    //保存支付密码修改
    public function save_changepwd(){
    	$status = BalanceService::getInstance()->save_changepwd($this->inter_id,$this->openid,$this->_token);
        $status = $this->parse_curl_msg($status);
        
        if($status['code']==1000){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$status['msg'],$status['data'],'membervip/balance/save_changepwd',$ext);
        }else{
            $this->out_put_msg(3,$status['msg'],$status['data'],'membervip/balance/save_changepwd');
        }
    }

    public function pay(){
        $data = BalanceService::getInstance()->pay($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        $ext['links']['sub_pay'] = site_url('iapi/membervip/balance/sub_pay')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/balance/pay',$ext);

    }

    public function sub_pay(){
        $status = BalanceService::getInstance()->sub_pay($this->inter_id,$this->openid);
        $status = $this->parse_curl_msg($status);
        $ext = array();
        if(!empty($status['data'])){
            $ext['links']['next'] = $status['data'];
        }
        if($status['code']==1000){
            $this->out_put_msg(1,$status['msg'],'','membervip/balance/sub_pay',$ext);
        }else{
            $this->out_put_msg(3,$status['msg'],'','membervip/balance/sub_pay',$ext);
        }
    }

    //储值支付成功
    public function okpay(){
        $data = BalanceService::getInstance()->okpay($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        $ext['links']['center'] = $data['jump_url'];
        $ext['links']['balance'] = site_url('membervip/balance')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/balance/okpay',$ext);
    }

    //储值支付失败
    public function nopay(){
        $data = BalanceService::getInstance()->nopay($this->inter_id);
        $ext['links']['center'] = $data['jump_url'];
        $ext['links']['pay'] = $data['restarturl'];
        $this->out_put_msg(1,'',$data,'membervip/balance/nopay',$ext);

    }
}
?>