<?php

namespace App\services\member;

use App\services\MemberBaseService;

/**
 * Class PerfectinfoService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class PerfectinfoService extends MemberBaseService
{

    /**
     * 获取服务实例方法
     * @return PerfectinfoService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    //会员卡资料修改页面
    public function index($inter_id,$openid){
        $post_config_url = PMS_PATH_URL."adminmember/getmodifyconfig";
        $post_config_data =  array(
            'inter_id'=>$inter_id,
            );
        //请求资料修改配置
        $data['modify_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        $data['inter_id']=$inter_id;
        //请求用户登录(默认)会员卡信息
        $data['centerinfo'] = $this->doCurlPostRequest( $post_center_url , $post_center_data )['data'];
        $data['info'] =$this->getCI()->Publics_model->get_fans_info($openid);
        return $data;

    }

    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 保存修改资料（本地化同步）
     */
    public function save($inter_id,$openid,$_token){
        $this->getCI()->session->unset_tempdata($inter_id.'vip_user');
        if($this->getCI()->input->post('name')){ $data['name']=$this->getCI()->input->post('name'); }
        if($this->getCI()->input->post('phone')){ $data['cellphone']=$this->getCI()->input->post('phone'); }
        if($this->getCI()->input->post('email')){ $data['email']=$this->getCI()->input->post('email'); }
        if($this->getCI()->input->post('idno')){ $data['id_card_no']=$this->getCI()->input->post('idno'); }
        if($this->getCI()->input->post('sex')){ $data['sex']=$this->getCI()->input->post('sex'); }
        if($this->getCI()->input->post('birthday')){ $data['birth']=strtotime($this->getCI()->input->post('birthday')); }
        if(isset($_POST['phonesms'])){ $data['sms']=$this->getCI()->input->post('phonesms'); }
        if(isset($_POST['smstype'])){ $data['smstype']=$this->getCI()->input->post('smstype'); }
        $post_savevip_url = PMS_PATH_URL."member/save_memberinfo";
        $post_savevip_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'data'=>$data,
        );
        $save_result = $this->doCurlPostRequest( $post_savevip_url , $post_savevip_data );
        if($save_result['err']==0){
            $this->getCI()->load->model('membervip/front/Member_model');
            $this->getCI()->Member_model->check_user_info($inter_id,$openid);
            //获取优惠信息
            $post_card = array(
                'token'=>$_token,
                'inter_id'=>$inter_id,
                'type'=>'perfect',
                'is_active'=>'t'
            );
            $rule_info= $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_rule_info" , $post_card );
            if(isset($rule_info['data'])){
                $rule_info = $rule_info['data'];
            }
            $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
            $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
            if(!empty($rule_info) && is_array($rule_info)){
                foreach ($rule_info as $key => $item){
                    if( isset($item['is_package']) && $item['is_package']=='t'){
                        $package_data = array(
                            'token'=>$_token,
                            'inter_id'=>$inter_id,
                            'openid'=>$openid,
                            'uu_code'=>$openid.'perfect'.uniqid(),
                            'package_id'=>$item['package_id'],
                            'card_rule_id'=>$item['card_rule_id'],
                            'number'=>$item['frequency']
                        );
                        $this->doCurlPostRequest( $packge_url , $package_data );
                    }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                        $card_data = array(
                            'token'=>$_token,
                            'inter_id'=>$inter_id,
                            'openid'=>$openid,
                            'card_id'=>$item['card_id'],
                            'type'=>'perfect'
                        );
                        $this->doCurlPostRequest( $card_url , $card_data );
                    }
                }
            }
        }
        return $save_result;
    }
}