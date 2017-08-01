<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	会员扫码核销权限资格申请
*	@author  liwensong
*	@copyright www.iwide.cn 
*	@version 4.0
*	@Email septet-l@outlook.com
*/
class Auth extends MY_Front_Member{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/common/Public_model','common_model');
    }

    public function scan(){
        $key = $this->input->get('fc');
        $this->session->set_userdata($this->inter_id.$this->openid.'scan_auth_code',$key);

        $this->load->helper('encrypt');
        $encrypt_util= new Encrypt();
        $json = $encrypt_util->decrypt(base64_decode($key));
        $data = json_decode($json,true);
        $key = !empty($data['key'])?$data['key']:'';
        $inter_id = !empty($data['inter_id'])?$data['inter_id']:'';
        $rand_code = !empty($data['code'])?$data['code']:'';
        if($inter_id == $this->inter_id && $key == 'verifie' && !empty($rand_code)){
            $redis = $this->common_model->get_vip_redis();
            $lockKey = "{$this->inter_id}{$rand_code}-membercard-scanqr_auth-scan-success";
            $_lockKey = "{$this->inter_id}{$rand_code}-scanqr_auth-scan-trans";
            $val = $redis->get($_lockKey);
            if(!empty($val)){
                $values = explode('***',$val);
                $apply_inter_id = !empty($values[0])?$values[0]:'';
                $apply_openid = !empty($values[1])?$values[1]:'';
                if($apply_inter_id != $this->inter_id OR $apply_openid != $this->openid){
                    redirect(site_url("membervip/center?id={$this->inter_id}"));
                }
            }else{
                $redis->setex($_lockKey,1800,"{$this->inter_id}***{$this->openid}"); //授权开始
                $redis->setex($lockKey,1800,1); //扫描成功
            }

            $where = array(
                'openid'=>$this->openid,
                'inter_id'=>$this->inter_id
            );
            $scanqr_auth = $this->common_model->get_info($where,'scanqr_auth');
            if(!empty($scanqr_auth) && $scanqr_auth['status'] == 1){
                redirect(site_url("membervip/auth?id={$this->inter_id}&fc={$rand_code}"));
            }else{
                $url = EA_const_url::inst()->get_front_url($this->inter_id, 'membervip/auth',array('id'=>$this->inter_id,'fc'=> $rand_code));
                $url = urlencode($url);
                $scope = 'snsapi_userinfo';
                $this->load->model('wx/Publics_model');
                $public=$this->Publics_model->get_public_by_id($this->inter_id);
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$public ['app_id']}&redirect_uri={$url}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
                redirect($url);
            }
        }else{
            redirect(site_url("membervip/center?id={$this->inter_id}"));
        }
    }

    public function index(){
        $rand_code = trim($this->input->get('fc'));
        $assign_data = array(
            'rand_code'=>$rand_code
        );

        $where = array(
            'openid'=>$this->openid,
            'inter_id'=>$this->inter_id
        );
        $scanqr_auth = $this->common_model->get_info($where,'scanqr_auth');
        if(!empty($scanqr_auth) && $scanqr_auth['status'] == 3){
            $redis = $this->common_model->get_vip_redis();
            $lockKey = "{$this->inter_id}{$rand_code}-scanqr_auth-apply";
            $val = $this->inter_id.'|'.$this->openid;
            $redis->setex($lockKey,1800,$val);
        }
        $assign_data['scanqr_auth'] = $scanqr_auth;
        $this->load->model('wx/access_token_model');
        $assign_data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $this->template_show('member',$this->_template,'auth',$assign_data);
    }

    public function apply(){
        if(is_ajax_request()){
            $rand_code = trim($this->input->post("rand_code"));
            $this->load->model('membervip/common/Public_model','common_model');
            $where = array(
                'openid'=>$this->openid,
                'inter_id'=>$this->inter_id
            );
            $scanqr_auth = $this->common_model->get_info($where,'scanqr_auth');
            $redis = $this->common_model->get_vip_redis();
            $val = $this->inter_id.'|'.$this->openid;
            if(!empty($scanqr_auth)){
                if($scanqr_auth['status'] == 1){
                    $lockKey = "{$this->inter_id}{$rand_code}-membercard-scanqr_auth-scan-success";
                    $redis->del($lockKey);
                    $this->_ajaxReturn('您已授权，无需再次申请');
                }elseif($scanqr_auth['status'] == 3){
                    $lockKey = "{$this->inter_id}{$rand_code}-scanqr_auth-apply";
                    $redis->setex($lockKey,1800,$val);
                    $this->_ajaxReturn('申请成功!',site_url('membervip/center').'?id='.$this->inter_id,1);
                }else{
                    $save_data = array(
                        'status'=>3,
                        'url'=>site_url('membervip/card/codeuseoff').'?id='.$this->inter_id,
                    );
                    $result = $this->common_model->update_save($where,$save_data,'scanqr_auth');
                    if($result){
                        $lockKey = "{$this->inter_id}{$rand_code}-scanqr_auth-apply";
                        $redis->setex($lockKey,1800,$val);
                        $this->_ajaxReturn('申请成功!',site_url('membervip/center').'?id='.$this->inter_id,1);
                    }
                }
            }else{
                $add_data = array(
                    'inter_id'=>$this->inter_id,
                    'openid'=>$this->openid,
                    'url'=>site_url('membervip/card/codeuseoff').'?id='.$this->inter_id,
                    'createtime'=>date('Y-m-d H:i:s'),
                );
                $result = $this->common_model->add_data($add_data,'scanqr_auth');
                if($result){
                    $lockKey = "{$this->inter_id}{$rand_code}-scanqr_auth-apply";
                    $redis->setex($lockKey,1800,$val);
                    $this->_ajaxReturn('申请成功!',site_url('membervip/center').'?id='.$this->inter_id,1);
                }
            }
            $this->_ajaxReturn('申请失败！');
        }
        $this->_ajaxReturn('很抱歉，申请失败，请联系管理员');
    }

    public function check_apply(){
        if(is_ajax_request()){
            $where = array(
                'openid'=>$this->openid,
                'inter_id'=>$this->inter_id
            );

            $scanqr_auth = $this->common_model->get_info($where,'scanqr_auth');
            if(!empty($scanqr_auth) && $scanqr_auth['status'] == 1){
                $this->_ajaxReturn('授权成功!',site_url('membervip/center').'?id='.$this->inter_id,1);
            }
            $this->_ajaxReturn('未授权!');
        }
        $this->_ajaxReturn('很抱歉，请求失败，请联系管理员');
    }
}