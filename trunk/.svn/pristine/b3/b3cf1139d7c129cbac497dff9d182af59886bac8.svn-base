<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 签到
 * of Sign
 * @author vencelyang
 */
class Sign extends MY_Front_Member
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/front/Signin_model');
    }

    /**
     * 签到主页
     */
    public function index()
    {
        $this->load->model('membervip/front/Member_model','_Member');
        $user_info = $this->_Member->get_user_info($this->inter_id,$this->openid);
        $member_info_id = !empty($user_info['member_info_id'])?$user_info['member_info_id']:0;
        // 获取签到数据
        $signData = $this->Signin_model->get_sign_info($this->inter_id, $this->openid,$member_info_id);
        // 获取签到设置数据
        $confInfo = $this->Signin_model->get_conf_info($this->inter_id);
        $data['filed_name'] = $this->_template_filed_names;
        $this->template_show('member',$this->_template,'sign_index',array('signData' => $signData, 'confInfo' => $confInfo,'filed_name'=>  $data['filed_name']));
    }

    /**
     * 签到操作
     */
    public function sign_in()
    {
        $this->load->model('membervip/front/Member_model','_Member');
        $user_info = $this->_Member->get_user_info($this->inter_id,$this->openid);
        if(empty($user_info)){
            echo json_encode(array('errcode' => 0, 'msg' => '签到失败，系统没有找到您的会员资料'));
            exit(0);
        }
        $result = $this->Signin_model->sign_in_handle($this->inter_id, $this->openid,$user_info['member_info_id']);
        echo json_encode($result);
        exit(0);
    }

    /**
     * 排行榜
     */
    public function ranking_list()
    {
        // 获取排行榜数据
        $result = $this->Signin_model->get_day_ranking_data($this->inter_id);
        $rankingData = $result['data'];
        $rankingData = array_combine(array_column($rankingData, 'openid'), $rankingData);

        // 获取用户签到记录
        $this->load->model('membervip/front/Member_model','_Member');
        $user_info = $this->_Member->get_user_info($this->inter_id,$this->openid,'member_info_id,member_mode,name,nickname,cellphone');
        $member_info_id = !empty($user_info['member_info_id'])?$user_info['member_info_id']:0;
        $lastInfo = $this->Signin_model->get_last_sign_in_record($this->inter_id, $this->openid,$member_info_id);
        $myRankingInfo = array();
        if (!empty($lastInfo) && $lastInfo['ymd'] == date('Ymd')) {// 判断是否已签到
            $name = '微信用户';
            if(!empty($user_info)){
                if($user_info['member_mode']=='1'){
                    $name = !empty($user_info['cellphone'])?$user_info['name']:(!empty($user_info['nickname'])?$user_info['nickname']:$name);
                }elseif($user_info['member_mode']=='2'){
                    $name = !empty($user_info['name'])?$user_info['name']:(!empty($user_info['nickname'])?$user_info['nickname']:$name);
                }
            }
            $lastInfo['name'] = $name;
            $lastInfo['ranking_date'] = date('n月j日 H:i:s', strtotime($lastInfo['sign_at']));
            $myRankingInfo = $lastInfo;
        }
        $this->template_show('member',$this->_template,'ranking_list',array('rankingData' => $rankingData, 'myRankingInfo' => $myRankingInfo));
    }

    /**
     * ajax获取排行榜数据
     */
    public function ajax_get_ranking()
    {
        $page = intval($this->input->get('page'));
        if (empty($page)) {
            $page = 1;
        }

        // 获取排行榜数据
        $result = $this->Signin_model->get_day_ranking_data($this->inter_id, $page);
        echo json_encode($result);
    }
}
