<?php
use App\services\member\DepositcardService;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	用户购卡
 *	@author  lijiaping
*   @copyright www.iwide.cn
*   @version 4.0
*   @Email lijiaping@mofly.cn
 */
class Depositcard extends MY_Front_Member_Iapi
{

    //会员可购卡列表
    public function index(){

        $data = DepositcardService::getInstance()->index($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/index',$ext);
        }
        if(isset($data['err']) && $data['err']>0){
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/index');
        }
        $ext['links']['buycard'] = site_url('iapi/membervip/depositcard/buycard')."?id=$this->inter_id";
        $ext['links']['info'] = site_url('membervip/depositcard/info')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/depositcard/index',$ext);

    }

    //会员购卡详细信息
    public function info(){
        $data = DepositcardService::getInstance()->info($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/info',$ext);
        }
        if(isset($data['err']) && $data['err']>0){
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/info');
        }
        $ext['links']['buycard'] = site_url('iapi/membervip/depositcard/buycard')."?id=$this->inter_id";
        $this->out_put_msg(1,'',$data,'membervip/depositcard/info',$ext);
    }

    /**
     * 开始购卡(所有的优先进入这个页面,当查询到信息后，检测到需要填写信息则,开始跳转否则,跳转支付)
     */
    public function buycard(){
        $data = DepositcardService::getInstance()->buycard($this->inter_id,$this->openid);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/buycard',$ext);
        }
        $data = $this->parse_curl_msg($data);
        if($data['code']==1000){
            $ext['links']['next'] = $data['data'];
            $this->out_put_msg(1,$data['msg'],'','membervip/depositcard/buycard',$ext);
        }else{
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/buycard');
        }
    }

    //
    public function pay(){
        $data = DepositcardService::getInstance()->pay($this->inter_id,$this->openid,$this->_token);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/pay',$ext);
        }
        $data = $this->parse_curl_msg($data);
        if($data['code']==1000){
            $this->out_put_msg(1,$data['msg'],'','membervip/depositcard/pay');
        }else{
            $this->out_put_msg(3,$data['msg'],$data['data'],'membervip/depositcard/pay');
        }
    }

    /**
     *	储值
     *
     *
     */
    public function buydeposit(){
        $data = DepositcardService::getInstance()->buydeposit($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names,true);
        $ext = array();
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/buydeposit',$ext);
        }
        if(isset($data['err']) && $data['err']>0){
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/buydeposit');
        }
        $this->out_put_msg(1,'',$data,'membervip/depositcard/buydeposit',$ext);

    }

    //填写分销信息
    public function edituser(){
        $data = DepositcardService::getInstance()->edituser($this->inter_id,$this->openid,$this->_token,$this->_template);
        $ext = array();
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/edituser',$ext);
        }
        if(isset($data['err']) && $data['err']>0){
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/edituser');
        }
        $this->out_put_msg(1,'',$data,'membervip/depositcard/edituser',$ext);
    }

    //创建储值订单
    public function save_deposit_order(){
        $data = DepositcardService::getInstance()->save_deposit_order($this->inter_id,$this->openid,$this->_token);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/save_deposit_order',$ext);
        }
        $data = $this->parse_curl_msg($data);
        if($data['code']==1000){
            $ext['links']['next'] = site_url('wxpay/vip_pay').'?id='.$this->inter_id.'&orderId='.$data['data'];
            $this->out_put_msg(1,$data['msg'],'','membervip/depositcard/save_deposit_order',$ext);
        }else{
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/save_deposit_order');
        }
    }
    //开始创建订单
    public function save_order(){
        $data = DepositcardService::getInstance()->save_order($this->inter_id,$this->openid,$this->_token);

        $data = $this->parse_curl_msg($data);
        if($data['code']==1000){
            $ext['links']['next'] = $data['data'];
            $this->out_put_msg(1,$data['msg'],'','membervip/depositcard/save_order',$ext);
        }else{
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/save_order');
        }
    }

    //支付成功
    public function okpay(){
        $data = DepositcardService::getInstance()->okpay($this->inter_id,$this->openid,$this->_token,$this->_template,$this->_template_filed_names);
        $ext['links']['jump_url'] = $data['jump_url'];
        $ext['links']['balance'] = site_url('membervip/balance')."?id=$this->inter_id";
        if( isset($data['info']) && (empty($data['info']['name']) || empty($data['info']['cellphone']) || empty($data['info']['id_card_no']))) {
            $ext['links']['update_order_buyer'] = site_url('iapi/membervip/depositcard/update_order_buyer')."?id=$this->inter_id";
        }
        $this->out_put_msg(1,'',$data,'membervip/depositcard/okpay',$ext);
        
    }
    //支付失败
    public function nopay(){
        $data = DepositcardService::getInstance()->nopay($this->inter_id);
        $ext['links']['jump_url'] = $data['jump_url'];
        $ext['links']['restarturl'] = $data['restarturl'];
        $this->out_put_msg(1,'',$data,'membervip/depositcard/nopay',$ext);
    }

    // /**
    //  * 把请求/返回记录记入文件
    //  * @param String content
    //  * @param String type
    //  */
    // protected function vip_order_write_log( $content, $type='request' )
    // {
    //     DepositcardService::vip_order_write_log($this->inter_id)->nopay($content,$type);
    // }



    // /**
    //  * 把请求/返回记录记入文件
    //  * @param String content
    //  * @param String type
    //  */
    // protected function api_write_log( $content, $type='request' )
    // {
    //     DepositcardService::vip_order_write_log($this->inter_id)->api_write_log($content,$type);
    // }

    /**
     * *更改泛分销状态
     */
    public function update_distribution_stats(){
        $data = DepositcardService::getInstance()->update_distribution_stats($this->inter_id,$this->openid);
        if(!empty($data['redirect'])){
            $ext['links']['redirect'] = $data['redirect'];
            $this->out_put_msg(1,'','','membervip/depositcard/update_distribution_stats',$ext);
        }
        $data = $this->parse_curl_msg($data);
        if($data['code']==1000){
            $this->out_put_msg(1,$data['msg'],$data['data'],'membervip/depositcard/update_distribution_stats');
        }else{
            $this->out_put_msg(3,$data['msg'],$data['data'],'membervip/depositcard/update_distribution_stats');
        }

    }

    /*更新购买者信息*/
    public function update_order_buyer(){
        $data = DepositcardService::getInstance()->update_order_buyer($this->inter_id,$this->openid,$this->_token);
        $data = $this->parse_curl_msg($data);
        if($data['code']==1000){
            $ext['links']['next'] = $data['data'];
            $this->out_put_msg(1,$data['msg'],'','membervip/depositcard/update_order_buyer',$ext);
        }else{
            $this->out_put_msg(3,$data['msg'],'','membervip/depositcard/update_order_buyer');
        }


    }

}
?>