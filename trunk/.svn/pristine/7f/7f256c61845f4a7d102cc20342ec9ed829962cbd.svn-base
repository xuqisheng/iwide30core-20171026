<?php

namespace App\services\member;

use App\services\MemberBaseService;

/**
 * Class BalanceService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class BalanceService extends MemberBaseService
{

    /**
     * 获取服务实例方法
     * @return BalanceService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    //会员余额记录
    public function index($inter_id,$openid,$_token,$_template,$_template_filed_names){
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        $last_balance_id = $this->getCI()->input->get('last_balance_id');
        $credit_type = $this->getCI()->input->get('credit_type');
        $post_bonus_url = PMS_PATH_URL."member/getbalance";
        $post_bonus_data =  array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'last_balance_id'=>$last_balance_id,
            'credit_type'=>$credit_type,
            'pagenum'=>6,
            );
        //请求余额记录
        $bonus_list = $this->doCurlPostRequest( $post_bonus_url , $post_bonus_data )['data'];

        $total_deposit = 0.00;
        //请求用户总余额
        $deposit_url = INTER_PATH_URL."deposit/getinfo";
        $web_post = array('token'=>$_token,'inter_id'=>$inter_id,'openid'=>$openid,'member_info_id'=>$member_info_id);
        $deposit_data = $this->doCurlPostRequest($deposit_url,$web_post);
        if(isset($deposit_data['data']) && floatval($deposit_data['data'])>0){
            $total_deposit = floatval($deposit_data['data']);
        }

        if($bonus_list){
            if(isset($bonus_list[0]['balance_log_id']))
                $last_balance_id = max(array_column($bonus_list,'balance_log_id'));
            else
                $last_balance_id = 0;
        }else{
            $bonus_list = array();
            $last_balance_id = 0;
        }

        $data = array(
            'total_deposit'=>number_format($total_deposit,2),
            'bonuslist'=>$bonus_list,
            'last_cradit_id'=>$last_balance_id,

            );
        if($_template == 'version4'){
            $data['credit_type'] = empty($credit_type) ? '' :$credit_type;
        }else{
            $data['credit_type'] = empty($credit_type) ? 1 :$credit_type;
        }

        $data['filed_name'] = $_template_filed_names;
        return $data;
    }


    //余额支付密码设置
    public function setpwd($inter_id,$openid,$_token){
        $data['info'] =$this->getCI()->Publics_model->get_fans_info($openid);
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        //获取支付密码设置状态
        $post_url = INTER_PATH_URL.'setpassword/pay_password_status';
        $post_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'token'=>$_token,
            );
        $status= $this->doCurlPostRequest( $post_url , $post_data );
        if($status['err']==0){
            $data['redirect'] = base_url('index.php/membervip/balance/changepwd?id='.$inter_id);
        }
        return $data;
    }

    //保存支付密码设置
    public function save_setpwd($inter_id,$openid,$_token){
        $edit_data = $this->getCI()->input->post();
        if($edit_data['password']!=$edit_data['confirm_pwd']){
            return array('err'=>40003,'msg'=>'密码不一致');
        }
        if( strlen($edit_data['password'])<6 ){
            return array('err'=>40003,'msg'=>'密码长度小于6位');
        }
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        //获取支付密码设置状态
        $post_url = INTER_PATH_URL.'setpassword/set_pay_password';
        $post_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'password'=>$edit_data['password'],
            'token'=>$_token,
            );
        $status= $this->doCurlPostRequest( $post_url , $post_data );
        $jump_url = $this->getCI()->session->userdata('JFK_member_vip_jump_url'); //跳转链接
        if(!empty($jump_url)){
            $status['jump_url'] = $jump_url;
        }else{
            $status['jump_url'] = site_url("membervip/center?id={$inter_id}");
        }
        return $status;
    }

    //余额支付密码修改
    public function changepwd($inter_id,$openid,$_token){
        $data['info'] =$this->getCI()->Publics_model->get_fans_info($openid);
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        //获取支付密码设置状态
        $post_url = INTER_PATH_URL.'setpassword/pay_password_status';
        $post_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'token'=>$_token,
            );
        $status= $this->doCurlPostRequest( $post_url , $post_data );
        if($status['err']>0){
            $data['redirect'] = base_url('index.php/membervip/balance/setpwd?id='.$inter_id);
        }
        return $data;
    }

    //保存支付密码修改
    public function save_changepwd($inter_id,$openid,$_token){
        $edit_data = $this->getCI()->input->post();
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$inter_id,
            'openid' =>$openid,
            );
        //请求用户登录(默认)会员卡信息
        $centerinfo= $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $member_info_id = isset($centerinfo['data']['member_info_id'])?$centerinfo['data']['member_info_id']:'';
        $balance_passwd = isset( $centerinfo['data']['balance_passwd'] )?$centerinfo['data']['balance_passwd']:null;
        if( !$balance_passwd ){
            return array('err'=>40003,'msg'=>'用户密码获取错误');
        }
        if( sha1( $edit_data['oldpassword'].'jfkhp' ) != $balance_passwd ){
            return array('err'=>40003,'msg'=>'原始密码不正确');  
        }
        if($edit_data['newpassword']!=$edit_data['confirm_pwd']){
            return array('err'=>40003,'msg'=>'确认密码不一致');  
        }
        $post_url = INTER_PATH_URL.'setpassword/set_pay_password';
        $post_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$member_info_id,
            'password'=>$edit_data['newpassword'],
            'token'=>$_token,
        );
        $status= $this->doCurlPostRequest( $post_url , $post_data );
        $jump_url = $this->getCI()->session->userdata('JFK_member_vip_jump_url'); //跳转链接
        if(!empty($jump_url)){
            $status['jump_url'] = $jump_url;
        }else{
            $status['jump_url'] = site_url("membervip/center?id={$inter_id}");
        }
        return $status;
    }

    public function pay($inter_id,$openid,$_token,$_template,$_template_filed_names){
        $this->getCI()->load->model('membervip/front/Member_model','Member');
        $orderId = $this->getCI()->input->get('orderId');
        $user_info = $this->getCI()->Member->get_user_info($inter_id,$openid); //获取会员信息

        $public = $this->getCI()->Publics_model->get_public_by_id($inter_id);

        //获取订单的详细信息
        $post_order_info = INTER_PATH_URL.'depositorder/get_order';
        $post_order_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'orderId'=>$orderId,
            'token'=>'',
        );
        $order_info = $this->doCurlPostRequest($post_order_info,$post_order_data);
        $order = !empty($order_info['data'])?$order_info['data']:array();
        $links = array(
            array(
                'name'=>'我要充值',
                'url'=>site_url('membervip/depositcard/buydeposit?id='.$inter_id),
            )
        );
        if(!empty($user_info['balance_passwd'])){
            $links[] = array(
                'name'=>'修改支付密码',
                'url'=>site_url('membervip/balance/changepwd?id='.$inter_id)
            );
        }else{
            $links[] = array(
                'name'=>'设置支付密码',
                'url'=>site_url('membervip/balance/setpwd?id='.$inter_id)
            );
        }
//        $this->session->set_userdata('JFK_member_vip_jump_url',site_url("membervip/balance/pay?orderId={$orderId}"));
        $jump_url = '';
        $this->getCI()->load->model('wx/access_token_model','token_model');
        $data = array(
            'public'=>$public,
            'user_info'=>$user_info,
            'orderid'=>$orderId,
            'order'=>$order,
            'links'=>$links,
            'signpackage'=>$this->getCI()->token_model->getSignPackage($inter_id)
        );
        $data['filed_name'] = $_template_filed_names;
        return $data;

    }

    public function sub_pay($inter_id,$openid){
        $orderId = $this->getCI()->input->post('orderid')?(int)$this->getCI()->input->post('orderid'):0;
        $passwd = $this->getCI()->input->post('password');
//        if(empty($passwd)){
//            $this->_ajaxReturn('请输入支付密码');
//        }

        $this->getCI()->load->model('membervip/front/Member_model','Member');
        $user_info = $this->getCI()->Member->get_user_info($inter_id,$openid); //获取会员信息

        if(empty($user_info)){
            return array('err'=>40003,'msg'=>'会员信息不存在');  
        }

//        if(empty($user_info['balance_passwd'])){
//            $this->_ajaxReturn('您还没设置支付密码');
//        }
//
//        if(sha1($passwd.'jfkhp') != $user_info['balance_passwd']){
//            $this->_ajaxReturn('支付密码错误');
//        }

        //获取验证的
        //获取订单的详细信息
        $post_order_info = INTER_PATH_URL.'depositorder/get_order';
        $post_order_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'orderId'=>$orderId,
            'token'=>'',
        );
        $order_info = $this->doCurlPostRequest( $post_order_info , $post_order_data );
        \MYLOG::w('Balance pay | Type 获取订单的详细信息'.@json_encode(array('result'=>$order_info,'url'=>$post_order_info,'param'=>$post_order_data)),'membervip/debug-log');
        $order_info = !empty($order_info['data'])?$order_info['data']:array();
        if(empty($order_info) || (!empty($order_info['deposit_type']) && $order_info['deposit_type'] == 'c')){
            return array('err'=>40003,'msg'=>'支付失败！订单信息错误。','data'=>site_url("membervip/balance/nopay?id={$inter_id}&orderid={$orderId}")); 
        }

        //查询购卡信息
        $post_cardinfo_url = PMS_PATH_URL."depositcard/getinfo";
        $post_cardinfo_data = array(
            'inter_id'=>$inter_id,
            'deposit_card_id'=>$order_info['deposit_card_id'],
        );

        $card_info = $this->doCurlPostRequest( $post_cardinfo_url , $post_cardinfo_data );
        \MYLOG::w('Balance pay | Type 查询购卡信息 '.@json_encode(array('result'=>$card_info,'url'=>$post_cardinfo_url,'param'=>$post_cardinfo_data)),'membervip/debug-log');
        if(empty($card_info['data'])){
            return array('err'=>40003,'msg'=>'支付失败！购卡信息已失效。','data'=>site_url("membervip/balance/nopay?id={$inter_id}&orderid={$orderId}")); 

        }
        $deposit_data = $card_info['data'];
        $request_data = array(
            'token'=>$openid,
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'member_info_id'=>$user_info['member_info_id'],
            'count'=>$order_info['pay_money'],
            'password'=>$passwd,
            'uu_code'=>$openid.'balance'.uniqid(),
            'module'=>'vip',
            'scene'=>'会员储值支付',
            'note'=>'会员购卡储值支付',
            'hotel_web_id'=>0,
            'trans_id'=>0,
        );
        //扣减储值
        $pay_url = INTER_PATH_URL."deposit/useoff";
        $pay_res = $this->doCurlPostRequest($pay_url,$request_data);
        if(!isset($pay_res['err']) OR $pay_res['err'] > 0){
            $msg = !empty($pay_res['msg'])?$pay_res['msg']:'支付失败! ';
            return array('err'=>40003,'msg'=>$msg,'data'=>site_url("membervip/balance/nopay?id={$inter_id}&orderid={$orderId}")); 

        }

        $this->getCI()->load->model('membervip/common/Public_model','p_model');
        $this->getCI()->load->model('membervip/front/Member_model','mem_model');

        $user_info = $this->getCI()->mem_model->get_user_info($inter_id,$openid,'member_info_id,balance');

        $params['inter_id'] = $inter_id;
        $params['pk'] = 'member_info_id';
        $params['member_info_id'] = $user_info['member_info_id'];
        $balance = floatval($user_info['balance']) - floatval($order_info['pay_money']);
        if($balance < 0){
            return array('err'=>40003,'msg'=>'支付失败！余额不足。','data'=>site_url("membervip/depositcard/buydeposit?id={$inter_id}")); 
        }
//         $save_data = array(
//             'balance' => $balance
//         );
//         $member_result = $this->p_model->update_save($params,$save_data,'member_info');
//         if(!$member_result){
//             $this->_ajaxReturn('支付失败！',site_url("membervip/balance/nopay?id={$inter_id}&orderid={$orderId}"));
//         }

        //修改订单信息，以及增加储值的金额
        $post_upOrder_url = INTER_PATH_URL.'depositorder/update_order';
        $post_upOrder_data = array(
            'token'=>'',
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'deposit_card_pay_id'=>$orderId,
        );
        $update_result = $this->doCurlPostRequest( $post_upOrder_url , $post_upOrder_data );
        \MYLOG::w('Balance pay | Type 修改订单信息 '.@json_encode(array('result'=>$update_result,'url'=>$post_upOrder_url,'param'=>$post_upOrder_data)),'membervip/debug-log');
        if($update_result['err']>0){
            return array('err'=>40003,'msg'=>'支付失败！','data'=>site_url("membervip/balance/nopay?id={$inter_id}&orderid={$orderId}")); 
        }

        //检查是否属于泛分销
        if( isset($order_info['distribution_num']) && $order_info['distribution_num'] > 0 &&  $order_info['distribution_type'] == 'FANS' ){
            $where = array(
                'id'=>$order_info['distribution_num'],
                'inter_id'=>$inter_id,
            );
            $pan_sales = $this->getCI()->p_model->get_info($where,'distribution_member');
            \MYLOG::w("Balance pay | Type Get pan sales info | ".@json_encode(array('result'=>$pan_sales,'where'=>$where)),'membervip/debug-log');
            if(!empty($pan_sales)){
                $this->getCI()->load->model('distribute/Idistribute_model','idistribute');
                $fansInfo = $this->getCI()->idistribute->fans_is_saler($inter_id,$pan_sales['open_id']);
                \MYLOG::w("Balance pay | Type Get dis fansInfo | ".@json_encode(array('result'=>$fansInfo,'params'=>$pan_sales)),'membervip/debug-log');
                $salesInfo = json_decode($fansInfo,true);
                if($salesInfo && $salesInfo['typ'] == 'FANS'){
                    //TODO
                    $this->getCI()->load->model('pay/Company_pay_model','pay_model');
                    $reward = 0;
                    switch ($order_info['deposit_card_id']){
                        case 138:$reward = 100;break;
                        case 139:$reward = 60;break;
                        case 173:$reward = 10;break;
                    }
//                    $reward = 0.01;
                    //插入发放记录
                    $insert_data = array(
                        'inter_id'=>$inter_id,
                        'open_id'=>$openid,
                        'type'=>'dis_pan',
                        'record_title'=>'购卡泛分销',
                        'sales_id'  => $order_info['distribution_num'],
                        'reward'=>$reward,
                        'sn'=>"card{$order_info['order_num']}",
                        'createtime'=>date('Y-m-d H:i:s'),
                        'status'=>'f'
                    );
                    $add_sales = $this->getCI()->p_model->add_data($insert_data,'distribution_record');
                    \MYLOG::w("Balance pay | Type Insert record 插入发放记录| ".@json_encode(array('result'=>$add_sales,'data'=>$insert_data)),'membervip/debug-log');
                    $distribute_arr = array(
                        'inter_id'=>$inter_id,
                        'hotel_id'=>0,
                        'saler'=>$salesInfo['info']['saler'],
                        'grade_openid'=>$openid,
                        'grade_table'=>'iwide_member4_fans',
                        'grade_id'=>$order_info['deposit_card_pay_id'],
                        'order_amount'=>$order_info['pay_money'],
                        'grade_total'=>$reward,
                        'remark'=>$deposit_data['title'],
                        'grade_amount'=>$order_info['pay_money'],
                        'order_time'    => date("Y-m-d H:i:s",time()),
                        'status'=>1,
                        'grade_typ' => 2,
                        'product'=>$deposit_data['title'],
                        'order_status'=>'已完成'
                    );
                    $distribute_result = $this->getCI()->idistribute->create_ext_grade( $distribute_arr );
                    \MYLOG::w("Balance pay | Type create_dist 绩效发放记录| ".@json_encode(array('result'=>$distribute_result,'param'=>$distribute_arr)),'membervip/debug-log');
                    if($distribute_result){
                        $save_data = array(
                            'status'=>'t'
                        );
                        $params = array(
                            'inter_id'=>$inter_id,
                            'open_id'=>$openid,
                            'sn'=>"card{$order_info['order_num']}",
                            'type'=>'dis_pan',
                            'status'=>'f'
                        );
                        $update_sales = $this->getCI()->p_model->update_save($params,$save_data,'distribution_record');
                        \MYLOG::w("Balance pay | Type update 绩效发放记录| ".@json_encode(array('result'=>$update_sales,'data'=>$save_data)),'membervip/debug-log');
                    }
                }
            }
        }
        //加入分销数据
        else if( isset( $deposit_data['distribution_money']) && $deposit_data['distribution_money']>0 && isset($order_info['distribution_num']) && $order_info['distribution_num'] > 0){
            $this->getCI()->load->model('distribute/Idistribute_model','idistribute');
            $distribute_arr = array(
                'inter_id'=>$inter_id,
                'hotel_id'=>0,
                'saler'=>$order_info['distribution_num'],
                'grade_openid'=>$openid,
                'grade_table'=>'iwide_member4_order',
                'grade_id'=>$order_info['deposit_card_pay_id'],
                'grade_id_name'=>'充值订单ID',
                'order_amount'=>$order_info['pay_money'],
                'grade_total'=>$deposit_data['distribution_money'],
                'grade_amount'=>$order_info['pay_money'],
                'grade_amount_rate'=>$deposit_data['distribution_money'],
                'grade_rate_type'=>0,
                'status'=>1,
                'remark'=>$deposit_data['title'],
                'product'=>$deposit_data['title'],
                'order_status'=>'已完成',
                'order_id'=>$order_info['order_num'],
            );
            $distribute_result = $this->getCI()->idistribute->create_dist( $distribute_arr );
            \MYLOG::w(@json_encode(array('result'=>$distribute_result,'param'=>$distribute_arr)),'membervip/debug-log');
        }

        //赠送套餐
        if($card_info['data']['is_package']=='t'){
            $packge_url = INTER_PATH_URL.'package/give';
            $package_data = array(
                'token'=>'',
                'inter_id'=>$inter_id,
                'openid'=>$openid,
                'uu_code'=>uniqid(),
                'package_id'=>$card_info['data']['package_id'],
            );
            $package_deposit = $this->doCurlPostRequest( $packge_url , $package_data );
            \MYLOG::w("Balance pay | Type package/give | ".@json_encode(array('result'=>$package_deposit,'url'=>$packge_url,'param'=>$package_data)),'membervip/debug-log');
        }
        return array('err'=>0,'msg'=>'支付成功！','data'=>site_url("membervip/balance/okpay?id={$inter_id}&orderid={$orderId}")); 
    }

    //储值支付成功
    public function okpay($inter_id,$openid,$_token,$_template,$_template_filed_names){
        $data['filed_name'] = $_template_filed_names;
        $data['jump_url'] = site_url("membervip/center?id={$inter_id}");
        return $data;
    }

    //储值支付失败
    public function nopay($inter_id){
        $orderid = isset($_GET['orderid'])?(int)$_GET['orderid']:0;
        //查询订单信息
        $data = array(
            'restarturl'=>base_url('index.php/membervip/balance/pay?orderid='.$orderid),
        );
        $data['jump_url'] = site_url("membervip/center?id={$inter_id}");
        return $data;
    }
}