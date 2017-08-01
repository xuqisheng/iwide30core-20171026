<?php
// +----------------------------------------------------------------------
// | Author: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// Membercardevent.php 2017-05-19
class Membercardevent extends MY_Admin_Api{
    public function __construct(){
        parent::__construct();
        $this->load->model('membervip/common/Public_model','common_model');
        $this->load->model('membervip/common/Public_log_model','common_logm');
    }

    //核销优惠券
    public function chargeoff(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $post = $this->input->get();
            $inter_id = $inter_id = $this->session->get_admin_inter_id();
            $member_info_id = !empty($post['mid'])?$post['mid']:'';
            $remark = !empty($post['remark'])?$post['remark']:'后台管理员手动操作核销';
            if(empty($post['code'])){
                $msg['err'] = '4003';
                $msg['msg'] = '券码为空';
                $this->_ajaxReturn($msg);
            }
            $coupon_code = $this->input->get('code');

            //获取会员信息
            $where = array(
                'inter_id'=>$inter_id,
                'member_info_id'=>$member_info_id
            );
            $member_info = $this->common_model->get_info($where,'member_info');
            if(empty($member_info)){
                $msg['err'] = '4004';
                $msg['msg'] = '找不到用户信息';
                $this->_ajaxReturn($msg);
            }

            //获取优惠券信息
            $where = array(
                'inter_id'=>$inter_id,
                'coupon_code'=>$coupon_code
            );
            $coupon = $this->common_model->get_info($where,'member_card');
            if(empty($coupon)){
                $msg['err'] = '4005';
                $msg['msg'] = '找不到领取的优惠券';
                $this->_ajaxReturn($msg);
            }

            $where = array(
                'inter_id'=>$inter_id,
                'card_id'=>$coupon['card_id']
            );
            $card = $this->common_model->get_info($where,'card','title');
            if(empty($card)){
                $msg['err'] = '4005';
                $msg['msg'] = '找不到优惠券信息';
                $this->_ajaxReturn($msg);
            }

            $name = !empty($member_info['name'])?$member_info['name']:$member_info['nickname'];
            $title = $card['title'];
            $logs = array(
                'title'=>'优惠券后台核销',
                'filter'=>array('createtime','last_update_time'),
                'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
                'name'=> "{$name} - 「{$coupon_code} - {$title} - 券码核销」"
            );
            $admin_profile = $this->session->userdata('admin_profile');
            $post_data = array(
                'inter_id'=>$inter_id,
                'coupon_code'=>$coupon_code,
                'openid'=>$member_info['open_id'],
                'member_info_id'=>$member_info['member_info_id'],
                'member_card_id'=>$coupon['member_card_id'],
                'module'=>'vip',
                'scene'=>'后台核销',
                'remark'=>$remark,
                'use_type'=>1,
                'operator'=>$admin_profile['username'].'_@@@_'.$admin_profile['admin_id'],
            );
            $req_url = INTER_PATH_URL.'membercard/use_verifie_by_code'; //核销优惠券
            $result = $this->doCurlPostRequest($req_url, $post_data);
            $usetime = date('Y-m-d,H:i:s');
            $save_data = $coupon;
            $save_data['is_use'] = 't';
            $save_data['is_useoff'] = 't';
            if(!isset($result['err']) OR $result['err'] > 0){
                $this->common_logm->save_log_init($coupon,$save_data,$coupon['member_card_id'],$result['msg'],'verification',$this->common_model,$logs); //添加操作记录
                $msg['err'] = '4006';
                $msg['msg'] = !empty($result['msg'])?$result['msg']:'核销失败';
                $this->_ajaxReturn($msg);
            }
            $this->common_logm->save_log_init($coupon,$save_data,$coupon['card_id'],$result['msg'],'verification',$this->common_model,$logs); //添加操作记录
            $msg['status'] = 1;
            $msg['err'] = '0';
            $msg['msg'] = '核销成功';
            $msg['text'] = '已核销';
            if(strpos($usetime,',')!==false){
                $usetime = implode('<br>',explode(',',$usetime));
            }
            $msg['usetime'] = $usetime;
            $this->_ajaxReturn($msg);
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    public function chargeinvalid(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $post = $this->input->get();
            $inter_id = $inter_id = $this->session->get_admin_inter_id();
            $member_info_id = !empty($post['mid'])?$post['mid']:'';
            $remark = !empty($post['remark'])?$post['remark']:'后台管理员手动操作核销';
            if(empty($post['code'])){
                $msg['err'] = '4003';
                $msg['msg'] = '券码为空';
                $this->_ajaxReturn($msg);
            }
            $coupon_code = $this->input->get('code');

            //获取会员信息
            $where = array(
                'inter_id'=>$inter_id,
                'member_info_id'=>$member_info_id
            );
            $member_info = $this->common_model->get_info($where,'member_info');
            if(empty($member_info)){
                $msg['err'] = '4004';
                $msg['msg'] = '找不到用户信息';
                $this->_ajaxReturn($msg);
            }

            //获取优惠券信息
            $where = array(
                'inter_id'=>$inter_id,
                'coupon_code'=>$coupon_code
            );
            $coupon = $this->common_model->get_info($where,'member_card');
            if(empty($coupon)){
                $msg['err'] = '4005';
                $msg['msg'] = '找不到领取的优惠券';
                $this->_ajaxReturn($msg);
            }

            $where = array(
                'inter_id'=>$inter_id,
                'card_id'=>$coupon['card_id']
            );
            $card = $this->common_model->get_info($where,'card','title');
            if(empty($card)){
                $msg['err'] = '4005';
                $msg['msg'] = '找不到优惠券信息';
                $this->_ajaxReturn($msg);
            }

            $name = !empty($member_info['name'])?$member_info['name']:$member_info['nickname'];
            $title = $card['title'];
            $logs = array(
                'title'=>'优惠券后台核销',
                'filter'=>array('createtime','last_update_time'),
                'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
                'name'=> "{$name} - 「{$coupon_code} - {$title} - 设为无效」"
            );
            $where = array(
                'inter_id'=>$inter_id,
                'member_card_id'=>$coupon['member_card_id']
            );
            $save_data = array(
                'is_active'=>'f'
            );
            $result = $this->common_model->update_save($where,$save_data,'member_card');
            if($result===false){
                $this->common_logm->save_log_init($coupon,$save_data,$coupon['member_card_id'],$result,'verification',$this->common_model,$logs); //添加操作记录
                $msg['err'] = '4006';
                $msg['msg'] = '操作失败';
                $this->_ajaxReturn($msg);
            }

            $data = array(
                'inter_id' => $inter_id,
                'member_info_id' => $member_info_id,
                'member_card_id' => $coupon['member_card_id'],
                'card_id' => $coupon['card_id'],
                'card_type' => $coupon['card_type'],
                'log_type' => 0,
                'num' => 1,
                'module' => 'vip',
                'scene' => 'adminmembervip',
                'remark' => $remark,
                'createtime' => time(),

            );
            $this->common_model->add_data($data,'card_log');

            $this->common_logm->save_log_init($coupon,$save_data,$coupon['card_id'],$result,'verification',$this->common_model,$logs); //添加操作记录
            $msg['status'] = 1;
            $msg['err'] = '0';
            $msg['msg'] = '操作成功';
            $msg['text'] = '无效';
            $this->_ajaxReturn($msg);
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    public function check_scanqr(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $redis = $this->common_model->get_vip_redis();
            $rand_code = trim($this->input->get('fc'));
            $msg['code'] = $rand_code;
            $inter_id = $this->session->get_admin_inter_id();
            $lockKey = "{$inter_id}{$rand_code}-membercard-scanqr_auth-scan-success";
            $val = $redis->get($lockKey);
            if(!$val){
                $msg['err'] = '4001';
                $msg['msg'] = '扫描失败';
                $this->_ajaxReturn($msg);
            }
            $redis->del($lockKey);
            $msg['status'] = 1;
            $msg['err'] = '0';

            $msg['msg'] = '扫描成功';
            $this->_ajaxReturn($msg);
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    public function check_applyauth(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $redis = $this->common_model->get_vip_redis();
            $rand_code = trim($this->input->get('fc'));
            $inter_id = $this->session->get_admin_inter_id();
            $lockKey = "{$inter_id}{$rand_code}-scanqr_auth-apply";
            $val = $redis->get($lockKey);
            if(!$val){
                $msg['err'] = '4007';
                $msg['msg'] = '没有申请信息';
                $this->_ajaxReturn($msg);
            }else{
                $str_val = explode('|',$val);
                $inter_id = !empty($str_val[0])?$str_val[0]:$inter_id;
                $openid = !empty($str_val[1])?$str_val[1]:'';
                $where = array('openid'=>$openid,'inter_id'=>$inter_id);
                $msg['where'] = $where;
                $applyuser = $this->common_model->db->where($where)->get('fans')->row_array();
                if(empty($applyuser)){
                    $msg['err'] = '4008';
                    $msg['msg'] = '请先关注该公众号';
                    $this->_ajaxReturn($msg);
                }
                $this->load->model('membervip/front/Member_model','user_model');
                $user = $this->user_model->get_user_info($inter_id,$openid);
                $user['headimgurl'] = $applyuser['headimgurl'];
                $name = $user['nickname'];
                $redis->del($lockKey);
                $msg['status'] = 1;
                $msg['err'] = '0';
                $msg['headimgurl'] = $user['headimgurl'];
                $msg['name'] = $name;
                $msg['auth'] = "{$inter_id}***{$user['open_id']}";;
                $msg['msg'] = '申请成功';
                $this->_ajaxReturn($msg);
            }
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    public function applyauth(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $auth = trim($this->input->get('auth'));
            $inter_id = $this->session->get_admin_inter_id();

            $redis = $this->common_model->get_vip_redis();
            $rand_code = trim($this->input->get('fc'));
            $_lockKey = "{$inter_id}{$rand_code}-scanqr_auth-scan-trans";
            $redis->del($_lockKey);

            $str_val = explode('***',$auth);
            $inter_id = !empty($str_val[0])?$str_val[0]:$inter_id;
            $openid = !empty($str_val[1])?$str_val[1]:'';
            $where = array('openid'=>$openid,'inter_id'=>$inter_id);
            $applyuser = $this->common_model->get_info($where,'scanqr_auth');
            if(empty($applyuser)){
                $msg['err'] = '4009';
                $msg['msg'] = '请先申请';
                $this->_ajaxReturn($msg);
            }else if($applyuser['status'] == 1){
                $msg['status'] = 1;
                $msg['err'] = '0';
                $msg['msg'] = '您已授权！';
                $this->_ajaxReturn($msg);
            }

            $save_data = array(
                'status'=>1,
                'authtime'=>date('Y-m-d H:i:s')
            );
            $result = $this->common_model->update_save($where,$save_data,'scanqr_auth');
            if($result){
                $msg['status'] = 1;
                $msg['err'] = '0';
                $msg['msg'] = '授权成功！';
                $this->_ajaxReturn($msg);
            }
            $msg['msg'] = '授权失败！';
            $this->_ajaxReturn($msg);
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    public function invalidauth(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $id = intval($this->input->get('id'));
            if(!$id) {
                $msg['err'] = '4100';
                $msg['msg'] = '取消授权失败，请联系管理员！';
                $this->_ajaxReturn($msg);
            }
            $where = array(
                'id'=>$id,
                'inter_id'=>$this->session->get_admin_inter_id()
            );

            $result = $this->common_model->delete_data($where,'scanqr_auth');
            if($result){
                $msg['status'] = 1;
                $msg['err'] = '0';
                $msg['msg'] = '操作成功！';
                $this->_ajaxReturn($msg);
            }
            $msg['msg'] = '操作失败！';
            $this->_ajaxReturn($msg);
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    public function getqrcode(){
        $msg = array(
            'status'=>0,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(is_ajax_request()){
            $this->load->helper('encrypt');
            $encrypt_util= new Encrypt();
            $rand_code = $this->common_model->randCode(16).microtime(true);
            $encrypt = $encrypt_util->encrypt(json_encode(array(
                'key'=>'verifie',	//暂时用固定的key，二维码长期有效
                'inter_id'=> $this->session->get_admin_inter_id(),
                'code'=>$rand_code
            )));
            $code = base64_encode($encrypt);
            $msg['status'] = 1;
            $msg['err'] = '0';
            $msg['msg'] = '授权成功！';
            $msg['key'] = $code;
            $msg['code'] = $rand_code;
            $this->_ajaxReturn($msg);
        }else{
            $this->_ajaxReturn($msg);
        }
    }

    /**
     * Ajax方式返回数据到客户端
     * @param array $data 要返回的数据
     * @param string $type AJAX返回数据格式
     * @param int $json_option JSON 常量
     */
    protected function _ajaxReturn($data = array(), $type = '',$json_option=0) {

        $data['referer'] = !empty($data['url']) ? $data['url'] : "";
        $data['state']= !empty($data['status']) ? "success" : "fail";
        if(empty($type)) $type  =   'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit($this->common_model->xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            case 'AJAX_UPLOAD':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data,$json_option));
            default :
                // 中断程序
                exit(0);
        }
    }
}