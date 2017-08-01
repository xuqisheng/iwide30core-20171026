<?php
use App\services\member\PerfectinfoService;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户资料 
*	@author  lijiaping
*   @copyright www.iwide.cn
*   @version 4.0
*   @Email lijiaping@mofly.cn
*/
class Perfectinfo extends MY_Front_Member
{
	//会员卡资料修改页面
	public function index(){
        $data = array();
        if(!$this->is_restful()){
            $data = PerfectinfoService::getInstance()->index($this->inter_id,$this->openid);
		}
        $this->template_show('member',$this->_template,'perfectinfo',$data);
    }

    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 保存修改资料（本地化同步）
     */
	public function save(){
        $save_result = PerfectinfoService::getInstance()->save($this->inter_id,$this->openid,$this->_token);
        echo json_encode($save_result);
	}

}
?>