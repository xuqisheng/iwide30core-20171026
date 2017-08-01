<?php

namespace App\services\member;

use App\services\MemberBaseService;

/**
 * Class BonusService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class BonusService extends MemberBaseService
{

    /**
     * 获取服务实例方法
     * @return BonusService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    //会员积分列表
    public function index($inter_id,$openid,$_token,$_template,$_template_filed_names){
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        $last_credit_id = $this->getCI()->input->get('last_credit_id');
        $credit_type = $this->getCI()->input->get('credit_type');
        
        $post_bonus_url = PMS_PATH_URL."member/getbouse";
        $post_bonus_data =  array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'last_credit_id'=>$last_credit_id,
            'credit_type'=>$credit_type,
            'pagenum'=>6,
            );
        //请求积分记录
        $bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];

        //请求用户总积分
        $total_credit = 0;
        $credit_url = INTER_PATH_URL."credit/getinfo";
        $web_post = array('token'=>$_token,'inter_id'=>$inter_id,'openid'=>$openid,'member_info_id'=>$member_info_id);
        $credit_data = $this->doCurlPostRequest($credit_url,$web_post);
        if(isset($credit_data['data']) && floatval($credit_data['data'])>0){
            $total_credit = floatval($credit_data['data']);
        }

        if($bonus_list){
            $credit_log_id = array_column($bonus_list,'credit_log_id');
            if(!empty($credit_log_id))
                $last_credit_id = max($credit_log_id);
            else
                $last_credit_id = 0;
        }else{
            $last_credit_id = 0;
        }

        $data = array(
            'inter_id'  => $inter_id,
            'total_credit'=>$total_credit,
            'bonuslist'=>$bonus_list,
            'last_cradit_id'=>$last_credit_id,
            'credit_type'=>empty($credit_type)?1:$credit_type,
        );

        if($_template == 'phase2'){
            $data['credit_type'] = empty($credit_type)?1:$credit_type;
        }else{
            $data['credit_type'] = empty($credit_type)?'':$credit_type;
        }

        if($inter_id == 'a457946152'){
            $data['page_title'] = '我的悦银';
        }
        $data['filed_name'] = $_template_filed_names;
        return $data;
    }

    //Ajax积分列表
    public function ajax_bouns($inter_id,$openid){
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        $last_credit_id = $this->getCI()->input->get('last_credit_id');
        $credit_type = $this->getCI()->input->get('credit_type');
        $post_bonus_url = PMS_PATH_URL."member/getbouse";
        $post_bonus_data =  array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'last_credit_id'=>$last_credit_id,
            'credit_type'=>$credit_type,
            'pagenum'=>6,
            );
        //请求积分记录
        $bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];
        if($bonus_list){
            $last_credit_id = max(array_column($bonus_list,'credit_log_id'));
        }else{
            $last_credit_id = 0;
        }

        $data = array(
            'bonuslist'=>$bonus_list,
            'last_cradit_id'=>$last_credit_id,
            'credit_type'=>$credit_type,
            );
        return $data;
    }
}