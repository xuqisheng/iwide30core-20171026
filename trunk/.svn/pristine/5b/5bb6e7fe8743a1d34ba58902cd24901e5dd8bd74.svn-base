<?php
use App\services\member\PerfectinfoService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户资料 
*	@author  lijiaping
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email lijiaping@mofly.cn
*/
class Perfectinfo extends MY_Front_Member_Iapi
{
	//会员卡资料修改页面
	public function index(){
        $data = PerfectinfoService::getInstance()->index($this->inter_id,$this->openid);

		$ext['links']['save'] = site_url('iapi/membervip/perfectinfo/save')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/perfectinfo/index',$ext);

    }

    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 保存修改资料（本地化同步）
     */
	public function save(){
        $save_result = PerfectinfoService::getInstance()->save($this->inter_id,$this->openid,$this->_token);
        $save_result = $this->parse_curl_msg($save_result);
        if($save_result['code']==1){
            $ext['links']['next'] = site_url('membervip/center')."?id=$this->inter_id";
            $this->out_put_msg(1,$save_result['msg'],$save_result['data'],'membervip/perfectinfo/save',$ext);
        }else{
            $this->out_put_msg(3,$save_result['msg'],$save_result['data'],'membervip/perfectinfo/save');
        }
	}

}
?>