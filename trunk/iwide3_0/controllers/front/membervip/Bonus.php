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
class Bonus extends MY_Front_Member
{
	//会员积分列表
	public function index(){
		$data = array();
        $data['page_title'] = '积分记录';
        if( !empty($this->_template_filed_names['credit_name'])){
            $data['page_title'] = $this->_template_filed_names['credit_name'].'记录';
        }
        if(!$this->is_restful()){
            $data = BonusService::getInstance()->index($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        }
        $this->template_show('member',$this->_template,'bonus',$data);
    }

	//Ajax积分列表
	public function ajax_bouns(){
		$data = BonusService::getInstance()->ajax_bouns($this->inter_id,$this->openid);
		echo json_encode($data);
	}

}
?>