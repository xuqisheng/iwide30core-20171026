<?php
use App\services\member\CenterService;
// +----------------------------------------------------------------------
// | 会员中心
// +----------------------------------------------------------------------
// | copyright: http://www.iwide.cn
// +----------------------------------------------------------------------
// | Author: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// | version 4.0
// +----------------------------------------------------------------------
// | Center.php 2017-07-12
// +----------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Center
 * @property Member_model $mem
 * @property Member_model $m_model
 * @property CI_Input  $input
 * @property CI_Loader $load
 */
class Center extends MY_Front_Member_Iapi
{
    private   $extra = array();
    protected $args = array();
    private   $client_ip = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->library("MYLOG");
        $this->load->helper('common_helper');
        $this->extra['links'] = array(
            'wxlogin' => EA_const_url::inst()->get_url('membervip/login/dowxlogin',array('inter_id'=>$this->inter_id)),
            'login' => EA_const_url::inst()->get_url('membervip/login/index',array('inter_id'=>$this->inter_id)),
            'reg' => EA_const_url::inst()->get_url('membervip/reg/index',array('inter_id'=>$this->inter_id)),
            'perfectinfo'=>EA_const_url::inst()->get_url('membervip/perfectinfo',array('inter_id'=>$this->inter_id))
        );

        $this->args = get_args();
        $this->client_ip = $this->input->ip_address();
        MYLOG::w(@json_encode(array('args' => $this->args, 'client_ip' => $this->client_ip)), 'iapi/front/membervip/debug-log', 'center-call');
    }

    //会员卡用户中心
    public function index(){
        $this->extra['links']['shop'] = EA_const_url::inst()->get_url('soma/order/my_order_list',array('inter_id'=>$this->inter_id));
        $this->extra['links']['hotel'] = EA_const_url::inst()->get_url('hotel/hotel/myorder',array('inter_id'=>$this->inter_id));
        $member_center_result = CenterService::getInstance()->index($this->inter_id,$this->openid,$this->_template_filed_names);
        $this->out_put_msg($member_center_result['status'],$member_center_result['msg'],$member_center_result['data'],'membervip/center/index',$this->extra);
    }


    //会员卡用户中心
    public function member_center(){
        $this->extra['links']['shop'] = EA_const_url::inst()->get_url('soma/order/my_order_list',array('inter_id'=>$this->inter_id));
        $this->extra['links']['hotel'] = EA_const_url::inst()->get_url('hotel/hotel/myorder',array('inter_id'=>$this->inter_id));
        $member_center_result = CenterService::getInstance()->member_center($this->inter_id,$this->openid,$this->_template_filed_names,true);
        $this->out_put_msg($member_center_result['status'],$member_center_result['msg'],$member_center_result['data'],'membervip/center/member_center',$this->extra);
    }

	//会员卡用户资料
	public function info(){
        $this->extra['links']['outlogin'] = EA_const_url::inst()->get_url('membervip/login/outlogin',array('inter_id'=>$this->inter_id));
        $member_info = CenterService::getInstance()->info($this->inter_id,$this->openid);
        $this->sp_out_put_msg('countent',$member_info['data'],'membervip/center/info',$this->extra);
    }

    //储值卡二维码页面
    public function qrcode(){
        $qrcode = CenterService::getInstance()->qrcode($this->inter_id,$this->openid);
        if(!empty($qrcode['data']['curl_data']['centerinfo']['data'])){
            $centerinfo = $qrcode['data']['curl_data']['centerinfo']['data'];
            if(isset($centerinfo['inter_id']) &&  $centerinfo['inter_id']=='a421641095'){
                $this->extra['links']['qrcode_url'] = isset($centerinfo['membership_number'])?base_url("index.php/membervip/center/qrcodecon?data=MEM").$centerinfo['membership_number']:0;
            }else{
                $this->extra['links']['qrcode_url'] = isset($centerinfo['id_card_no'])?base_url("index.php/membervip/center/qrcodecon?data=").$centerinfo['id_card_no']:0;
            }
        }
        $this->sp_out_put_msg('countent',$qrcode['data'],'membervip/center/qrcode',$this->extra);
    }
}