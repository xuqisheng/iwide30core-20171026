<?php
use App\services\member\BonusService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户积分 
*	@author  lijiaping
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email lijiaping@mofly.cn
*/
class Bonus extends MY_Front_Member_Iapi
{
	//会员积分列表
	public function index(){
		$data = BonusService::getInstance()->index($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        $ext['links']['get'] = site_url('iapi/membervip/bonus')."?id=$this->inter_id&credit_type=1";
        $ext['links']['consume'] = site_url('iapi/membervip/bonus')."?id=$this->inter_id&credit_type=2";
        $this->out_put_msg(1,'',$data,'membervip/bonus/index',$ext);
    }

	//Ajax积分列表
	public function ajax_bouns(){
		$data = BonusService::getInstance()->ajax_bouns($this->inter_id,$this->openid);
        $this->out_put_msg(1,'',$data,'membervip/balance/ajax_bouns');
	}

}
?>