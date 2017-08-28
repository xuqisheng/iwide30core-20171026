<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	用户注册、登陆
 *	careate : mingxin
 *
 */
class Account extends MY_Front
{
    protected function getOpenId()
    {
        return $this->openid;
    }
    //登陆页面
    public function login()
    {
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        if($member->is_login=='1')
        {
            redirect('member/center');
        }
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
        }
        if($this->inter_id=='a441624001'){
            $data['tishiMsg'] = '默认密码为888888,请登陆后及时修改密码';
        }
        //获取登录配置信息
        $this->load->model('member/iconfig');
        $fields = $this->iconfig->getConfig('login_fields',true,$this->inter_id);
        if($fields) {
            $data['fields'] = $this->iconfig->getConfig('login_fields',true,$this->inter_id)->value;
        } else {
            $data['fields'] = array();
        }
        $data['inter_id'] = $this->inter_id;
        //var_dump($data);exit;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $this->display('member/userlogin', $data);
    }
    //退出登陆
    public function logout()
    {
        $openid = $this->getOpenId();
        
        $this->load->model('member/imember');
        $memObject = $this->imember->getMemberByOpenId($openid, $this->inter_id, 0);
        
        if($memObject && isset($memObject->mem_id)) {
            $result = $this->imember->updateMemberByOpenId(array('openid'=>$openid, 'level'=>0, 'is_login'=>0,'bonus'=>0,'balance'=>0), $this->inter_id, 0);
            if($result) {
                $unbindMemberCardRs = $this->imember->unBindMemberCard($openid);
                
                echo json_encode(array('result'=>true,'message'=>"退出登录成功！"));
                exit;
            } else {
                echo json_encode(array('result'=>true,'message'=>"退出登录失败！"));
                exit;
            }
        } else {
            echo json_encode(array('result'=>true,'message'=>"退出登录失败，不存在会员！"));
            exit;
        }
    }
    //登陆信息验证及保存
    public function save()
    {
        $openid = $this->getOpenId();
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $this->load->model('member/imember');
        $member_info = $this->imember->getMemberByOpenId($openid, $this->inter_id, 0);
        $result = $this->imember->checklogin($openid, $account, $password, $this->inter_id, 0);
        if($result) {
            if($member_info && empty($member_info->membership_number)){
                $this->load->model('plugins/Template_msg_model');
                $name = isset($member_info->name)?$member_info->name:'';
                $this->Template_msg_model->send_member_msg(array('inter_id'=>$this->inter_id,'openid'=>$openid,'member_name'=>$name),'member_bind_completed');
            }
            $this->session->set_userdata('message', "登录成功！");
            redirect('member/center');
        } else {
            $this->session->set_userdata('message', "账号或密码错误！");
        }
        redirect('member/center');
        
    }
    //注册页面
    public function register()
    {
        if($this->inter_id=='a421641095'){
            //微信返回的信息显示没有关注，则进行高级授权验证
            if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx' )
                $refer =  'http://'. $_SERVER ['HTTP_HOST']. $_SERVER ['REQUEST_URI'] ;
                else
                    $refer =  'http://'. $_SERVER ['SERVER_NAME']. $_SERVER ['REQUEST_URI'] ;
                    $refer = str_replace('/member/','/membervip/',$refer);
                    $refer = str_replace('/account/register','/reg',$refer);
                    redirect($refer);
        }
        
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        //考虑到兼容，新增跳转
        //$this->imember->headerUrlCenter();
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
        }
        
        $this->load->model('member/iconfig');
        $fields = $this->iconfig->getConfig('register_fields',true,$this->inter_id);
        if($fields) {
            $data['fields'] = $this->iconfig->getConfig('register_fields',true,$this->inter_id)->value;
        } else {
            $data['fields'] = array();
        }
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        if($this->session->has_userdata('message')){
            $data['message'] = $this->session->message;
            $this->session->unset_userdata('message');
        }
        $this->display('member/register/register', $data);
    }
    public function owners()
    {
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        //考虑到兼容，新增跳转
        //$this->imember->headerUrlCenter();
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
        }
        
        $this->load->model('member/iconfig');
        $fields = $this->iconfig->getConfig('register_fields',true,$this->inter_id);
        if($fields) {
            $data['fields'] = $this->iconfig->getConfig('register_fields',true,$this->inter_id)->value;
        } else {
            $data['fields'] = array();
        }
        
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        if($this->session->has_userdata('message')){
            $data['message'] = $this->session->message;
            $this->session->unset_userdata('message');
        }
        $this->display('member/register', $data);
    }
    //注册信息保存
    public function registersave()
    {
        
        $openid = $this->getOpenId();
        if(!isset($openid)) {
            redirect('member/center');
            return;
        }
        
        $data = $this->input->post();
        
        
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid ,$this->inter_id , 0);
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
            return;
        }
        
        if($this->inter_id == "a421641095"){
            
            
            if($_SESSION['code'] != $data['smspic']){
                
                $this->session->set_userdata('message', "图片验证码错误！");
                
                redirect('member/account/register');exit;
                
            }
            if($_SESSION['sms'] != $data['sms']){
                
                $this->session->set_userdata('message', "短信验证码有误！");
                
                redirect('member/account/register');exit;
            }
            
        }elseif($this->inter_id == 'a455510007'){ //速8
            
            if($_SESSION['sms'] != $data['sms']){
                
                $this->session->set_userdata('message', "短信验证码有误！");
                
                redirect('member/account/register');exit;
            }
        }
        $data['mem_id'] = $member->mem_id;
        $result = $this->imember->registerMember($openid, $data ,$this->inter_id, 0);
        if($result) {
            
            //速8
            if(isset($result['IsError'])){
                
                $result['errmsg'] = $result['Message'];
                $this->session->set_userdata('message', $result['errmsg']);
                redirect('member/account/register');exit;
                
            }else if(isset($result['code']) && $result['code'] == 1){
                $this->load->model('plugins/Template_msg_model');
                $this->Template_msg_model->send_member_msg(array('inter_id'=>$this->inter_id,'openid'=>$openid,'member_name'=>$this->input->post('name')),'member_reg_completed');
            }
            $this->session->set_userdata('message', $result['errmsg']);
        } else {
            $this->session->set_userdata('message', "注册失败！");
            redirect('member/account/register');exit;
        }
        
        redirect('member/center');
    }
    public function registerowner()
    {
        $openid = $this->getOpenId();
        if(!isset($openid)) {
            redirect('member/center');
            return;
        }
        
        $data = $this->input->post();
        
        if($this->inter_id == "a421641095"){
            
            
            if($_SESSION['code'] != $data['smspic']){
                
                $this->session->set_userdata('message', "图片验证码错误！");
                
                redirect('member/account/owners');exit;
                
            }
            if($_SESSION['sms'] != $data['sms']){
                
                $this->session->set_userdata('message', "短信验证码有误！");
                
                redirect('member/account/owners');exit;
            }
            
        }
        
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid ,$this->inter_id , 0);
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
            return;
        }
        
        
        $data['mem_id'] = $member->mem_id;
        $this->load->model('member/member','member');
        $data['openid'] = $openid;
        $data['inter_id'] = $this->inter_id;
        try {
            $addUserResult = $this->member->updateMemberInfoByOpenId( $data );
        } catch (Exception $e) {
            log_message('error',$e->getMessage());
            $this->session->set_userdata('message', "注册失败！");
            redirect('member/account/registerowner');exit;
        }
        // 		$result = $this->imember->registerMember($openid, $data ,$this->inter_id, 0);
        if($addUserResult) {
            // 	    	if(isset($result['code']) && $result['code'] == 1){
            // 				$this->load->model('plugins/Template_msg_model');
            // 				$this->Template_msg_model->send_member_msg(array('inter_id'=>$this->inter_id,'openid'=>$openid,'member_name'=>$this->input->post('name')),'member_reg_completed');
            // 	    	}
            $this->session->set_userdata('message', '您的资料已经提交审核。若您已经是会员，资料同步更新。');
        } else {
            $this->session->set_userdata('message', "登录失败！");
            redirect('member/account/registerowner');exit;
        }
        
        redirect('member/center');
    }
    
    //找回密码页面
    public function resetpassword() {
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        if($member->is_login=='1')
        {
            redirect('member/center');
        }
        
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
        }
        
        //获取后台设置重置密码的字段
        $this->load->model('member/iconfig');
        $fields = $this->iconfig->getConfig('reset_fields',true,$this->inter_id);
        if($fields) {
            $data['fields'] = $this->iconfig->getConfig('reset_fields',true,$this->inter_id)->value;
        } else {
            $data['fields'] = array();
        }
        
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $data['inter_id'] = $this->inter_id;
        
        if($this->session->has_userdata('message')){
            $data['message'] = $this->session->message;
            $this->session->unset_userdata('message');
        }
        $this->display('member/resetinfo', $data);
    }
    
    //保存找回密码
    public function resetpasswordsave(){
        $openid = $this->getOpenId();
        $data = $this->input->post();
        $this->load->model('member/imember');
        $result = $this->imember->updatePassWordin( $openid, $data, $this->inter_id, 0);
        if($result['code']==0) {
            $this->session->set_userdata('message', $result['errmsg']);
            redirect('member/center');
        } else {
            $this->session->set_userdata('message', $result['errmsg']);
            redirect('member/account/resetpassword');
        }
        redirect('member/center');
    }
    
    //修改密码
    public function modpwd()
    {
        $openid = $this->getOpenId();
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberDetailByOpenId($openid, $this->inter_id, 0);
        
        /*if(!$member || !isset($member->mem_id) || empty($member->is_active)) {
         $this->session->set_userdata('message', "请先登录账户!");
         redirect('member/center');
         exit;
         }*/
        
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        
        if($this->session->has_userdata('message')) {
            $data['message'] = $this->session->message;
            $this->session->unset_userdata('message');
        }
        
        $this->display('member/modpwd', $data);
    }
    
    //修改密码保存
    public function modpwdsave()
    {
        $openid = $this->getOpenId();
        $data = $this->input->post();
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberDetailByOpenId($openid, $this->inter_id, 0);
        
        /* if(!$member || !isset($member->mem_id) || empty($member->is_active) || empty($member->is_login)) {
         $this->session->set_userdata('message', "请先登录账户!");
         redirect('member/center');
         exit;
         } */
        
        $result = $this->imember->modPassword($openid, $data, $this->inter_id, 0);
        // 		var_dump($result);exit;
        $this->session->set_userdata('message', $result["errmsg"]);
        redirect('member/center');
    }
    
    //此处完成了一个新的登录程序方法，但只是暂时的，后期优化吧，没时间了
    public function newlogin(){
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        if($member->is_login=='1')
        {
            $this->session->set_userdata('message', "已经登录过了,不能再次绑定");
            redirect('member/center');
        }
        if(!$member || !isset($member->mem_id)) {
            redirect('member/center');
        }
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $this->display('member/newlogin', $data);
    }
    
    //应急方法，，登录同步开始
    public function savenewlogin(){
        $openid = $this->getOpenId();
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $this->load->model('member/imember');
        $result = $this->imember->newchecklogin($openid, $account, $password, $this->inter_id, 0);
        if($result['error']==0) {
            $this->session->set_userdata('message', $result['message']);
            redirect('member/center');
        } else {
            $this->session->set_userdata('message', $result['message']);
        }
        redirect('member/center');
    }
    //远州临时修改密码方案
    public function updatepassword(){
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $this->display('member/yuanzhourespassword', $data);
    }
    
    //远州保存修改密码
    public function saveupdatepassword(){
        $data = $this->input->post();
        $openid = $this->getOpenId();
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        $dataarr = array(
            'wxuserid'=>$member->custom1,
            'oldpassword'=>$data['password'],
            'newpassword'=>$data['newpassword'],
        );
        $result = $this->imember->modPassword($openid,$dataarr,$this->inter_id,0);
        $this->session->set_userdata('message', $result['errmsg']);
        redirect('member/center');
    }
    
    //书香微信卡券领取
    public function shuxiangcard(){
        $openid = $this->getOpenId();
        $cardid = 'pcbScjnLzqYaaxfhBAYyCby0BBC8';
        $inter_id = $this->inter_id;
        $this->load->model('wx/Access_token_model','apitoken');
        $data['signpackage'] = $this->apitoken->getSignPackage($this->inter_id);
        $code_str = uniqid(10);
        $result = $this->apitoken->getCardPackage( $cardid , $inter_id , $code_str, $openid,$data['signpackage']['nonceStr'],$data['signpackage']['timestamp']);
        $data['card_ext'] = $result[0]['card_ext'];
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        $this->load->model('wx/access_token_model');
        $data['isOn'] = 0;
        $data['openid'] = $openid;
        $data['cardid'] = $cardid;
        $this->display('member/shuxiangcard',$data);
    }
    
    //书香微信卡券领取(2)
    public function shuxiangcardtwe(){
        $openid = $this->getOpenId();
        $cardid = 'pcbScjnLzqYaaxfhBAYyCby0BBC8';
        $inter_id = $this->inter_id;
        $code_str = uniqid(10);
        if($this->input->get('orderid')){
            $this->db->where(array('inter_id'=>$inter_id,'from'=>'checkin','from_id'=>$this->input->get('orderid')));
            $this->db->limit(1);
            $query = $this->db->get('user_get_card')->row_array();
            if(isset($query['code'])){
                $code_str = $query['code'];
                $data['sec'] = 2;
            }else{
                $this->db->insert('user_get_card',array('inter_id'=>$inter_id,'openid'=>$openid,'card_id'=>$cardid,'code'=>$code_str,'from'=>'checkin','create_time'=>date('Y-m-d H:i:s'),'from_id'=>$this->input->get('orderid')));
            }
        }
        $this->load->model('member/member','members');
        $this->load->model('wx/Access_token_model','apitoken');
        $data['signpackage'] = $this->apitoken->getSignPackage($this->inter_id);
        $result = $this->apitoken->getCardPackage( $cardid , $inter_id , $code_str, $openid,$data['signpackage']['nonceStr'],$data['signpackage']['timestamp']);
        $data['card_ext'] = $result[0]['card_ext'];
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        $this->load->model('wx/access_token_model');
        $data['openid'] = $openid;
        $data['cardid'] = $cardid;
        $this->display('member/shuxiangcardtwe',$data);
    }
    /**
     * 书香注册送券
     */
    public function member_reg_card(){
        $openid = $this->getOpenId();
        $cardid = 'pcbScjiAgqP8gf3R4NVeSQZnJ4Ag';
        // 		$cardid = 'pcbScjqtEK34RJRq6AUXQKxVUM8c';
        $inter_id = $this->inter_id;
        $this->load->model('member/member','members');
        $this->load->model('wx/Access_token_model','apitoken');
        $data['signpackage'] = $this->apitoken->getSignPackage($this->inter_id);
        $code_str = uniqid(10);
        $result = $this->apitoken->getCardPackage( $cardid , $inter_id , $code_str, $openid,$data['signpackage']['nonceStr'],$data['signpackage']['timestamp']);
        $data['card_ext'] = $result[0]['card_ext'];
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        $this->load->model('wx/access_token_model');
        $data['openid'] = $openid;
        $data['cardid'] = $cardid;
        $data['title']  = '699元套票券购买资格';
        $this->display('member/shuxiangcardtwe',$data);
    }
    /**
     * 隐居充值送券
     */
    public function member_charge_card(){
        $openid = $this->getOpenId();
        
        $this->load->model('member/ichargeorder');
        $order = $this->ichargeorder->getChargeOrderByOrderNumber($this->input->get('orderid'));
        if($order->amount == 10000){
            $cardid = 'pjAfEjlMaC3_xbK8Pg_EDArfC1Mg';//10000
        }else{
            $cardid = 'pjAfEjqj3-sGEBJwvilg-buOGi3U';//5000
        }
        $inter_id = $this->inter_id;
        $code_str = uniqid(10);
        if($this->input->get('orderid')){
            $this->db->where(array('inter_id'=>$inter_id,'from'=>'member_charge','from_id'=>$this->input->get('orderid')));
            $this->db->limit(1);
            $query = $this->db->get('user_get_card')->row_array();
            if(isset($query['code'])){
                $code_str = $query['code'];
                $data['sec'] = 2;
            }else{
                $this->db->insert('user_get_card',array('inter_id'=>$inter_id,'openid'=>$openid,'card_id'=>$cardid,'code'=>$code_str,'from'=>'member_charge','create_time'=>date('Y-m-d H:i:s'),'from_id'=>$this->input->get('orderid')));
            }
        }
        $this->load->model('member/member','members');
        $this->load->model('wx/Access_token_model','apitoken');
        $data['signpackage'] = $this->apitoken->getSignPackage($this->inter_id);
        $result = $this->apitoken->getCardPackage( $cardid , $inter_id , $code_str, $openid,$data['signpackage']['nonceStr'],$data['signpackage']['timestamp']);
        $data['card_ext'] = $result[0]['card_ext'];
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
        $this->load->model('wx/access_token_model');
        $data['openid'] = $openid;
        $data['cardid'] = $cardid;
        $this->display('member/shuxiangcardtwe',$data);
    }
    
    
    /**
     * 速8会员查重
     */
    public function seMemberCheck(){
        $this->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
        $suba = new Subaapi_webservice(false);
        $phone = $this->input->post('tel');
        
        //图片验证
        $picCode = $this->input->post('picCode');
        if(!empty($picCode)){
            if($this->session->has_userdata('code') && ($picCode != $this->session->code)){
                $result = array(
                    'status'   => 3,
                    'msg'    => '验证码不正确'
                );
                echo json_encode($result);
                exit;
            }
        }
        $rs = $suba->CheckMemberStatus($phone);
        if(isset($rs['CheckMemberStatusResult'])
            && ($rs['CheckMemberStatusResult']['ResultCode'] == '00')
            && ($rs['CheckMemberStatusResult']['Content']['IsMember'] == 1) ){
                $result = array(
                    'status'   => 1,
                    'msg'    => '此手机号码已注册'
                );
                echo json_encode($result);
        }else{
            $result = array(
                'status'   => 2,
                'msg'   => '此手机号码尚未注册'
            );
            echo json_encode($result);
        }
    }
    
    public function pic_code(){
        
        //        session_start();
        //生成验证码图片
        
        $im = imagecreate(60,20); // 画一张指定宽高的图片
        $back = ImageColorAllocate($im, 245,245,245); // 定义背景颜色
        imagefill($im,0,0,$back); //把背景颜色填充到刚刚画出来的图片中
        $vcodes = "";
        srand((double)microtime()*1000000);
        //生成4位数字
        for($i=0;$i<4;$i++){
            $font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255)); // 生成随机颜色
            $authnum=rand(1,9);
            $vcodes.=$authnum;
            imagestring($im, 5, 2+$i*10, 1, $authnum, $font);
        }
        $_SESSION['code'] = $vcodes;
        
        for($i=0;$i<100;$i++) //加入干扰象素
        {
            $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
            imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); // 画像素点函数
        }
        ob_clean();
        Header("Content-type: image/PNG");
        ImagePNG($im);
        ImageDestroy($im);
        
        
    }
    
    //速8会员激活，输入会员信息
    public function activeinfo(){
        $openid = $this->getOpenId();
        if(!isset($openid)){
            redirect('member/center');
            exit;
        }
        $data = array();
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid, $this->inter_id, 0);
        if(!$member || !isset($member->mem_id)){
            redirect('member/center');
            exit;
            //		} elseif($member->is_login == '1'){
            //			redirect('member/center');
        }
        
        $activate_params = $this->session->userdata('activate_member');
        if($activate_params){
            $data['customer'] = $activate_params['customer'];
            $data['telephone'] = $activate_params['telephone'];
        } else{
            $data['customer'] = null;
            $data['telephone'] = null;
        }
        
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $this->display('member/active_info', $data);
    }
    
    //输入会员卡号及验证码（速8会员激活）
    public function activecard(){
        if($this->inter_id != 'a455510007'){
            redirect('member/center');
            exit;
        }
        $openid = $this->getOpenId();
        if(!isset($openid)){
            redirect('member/center');
            exit;
        }
        $data = array();
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberByOpenId($openid, $this->inter_id, 0);
        if(!$member || !isset($member->mem_id)){
            redirect('member/center');
            exit;
            //		} elseif($member->is_login == '1'){
            //			redirect('member/center');
        }
        
        if(!$this->session->userdata('activate_member')){
            redirect('member/account/activeinfo');
            exit;
        }
        
        
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        
        $this->display('member/active_card', $data);
    }
    
    public function activesuccess(){
        
        if($this->inter_id != 'a455510007'){
            redirect('member/center');
            exit;
        }
        
        $data = array();
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $this->session->unset_userdata('sup8mess');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        
        $this->display('member/active_success', $data);
    }
    
    public function activefail(){
        if($this->inter_id != 'a455510007'){
            redirect('member/center');
            exit;
        }
        
        $data = array();
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        
        $data['mess']=$this->session->userdata('sup8mess');
        //		$this->session->unset_userdata('sup8mess');
        
        $this->display('member/active_fail', $data);
    }
    
    public function bind_show()
    {
        $openid = $this->getOpenId();
        $this->load->model('member/member', 'member');
        $addUserInfo = $this->member->addUserInfo($this->inter_id, $openid);
        if ($this->inter_id != 'a455510007') {
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/center')
            ));
        }
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        
        $this->display('member/bind_suba', $data);
    }
    
    public function bind_validate()
    {
        if ($this->inter_id != 'a455510007') {
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/center')
            ));
        }
        $phone = $this->input->post('tel');
        if (empty($phone)) {
            $this->json_out(array(
                'status' => 0,
                'errmsg' => '手机号码不能留空!'
            ));
        }
        $this->load->library('Baseapi/Subaapi_webservice', array(
            'testModel' => false
        ));
        $suba = new Subaapi_webservice(false);
        
        $rs = $suba->CheckMemberStatus($phone);
        if (isset($rs['CheckMemberStatusResult']) && ($rs['CheckMemberStatusResult']['ResultCode'] == '00') && ($rs['CheckMemberStatusResult']['Content']['IsMember'] == 1)) {
            // 已经是会员
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/account/bind_ismember/?tel=' . $phone)
            ));
        } else {
            // 还不是会员
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/account/bind_fail/?tel=' . $phone)
            ));
        }
    }
    
    public function bind_ismember()
    {
        if ($this->inter_id != 'a455510007') {
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/center')
            ));
        }
        $phone = $this->input->get('tel');
        $data['inter_id'] = $this->inter_id;
        $data['openid'] = $this->openid;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $data['tel'] = $phone;
        $this->display('member/bind_ismember', $data);
    }
    
    public function bind_fail()
    {
        if ($this->inter_id != 'a455510007') {
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/center')
            ));
        }
        $phone = $this->input->get('tel');
        $data['inter_id'] = $this->inter_id;
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        $data['tel'] = $phone;
        $this->display('member/bind_fail', $data);
    }
    
    public function bind_check()
    {
        if ($this->inter_id != 'a455510007') {
            $this->json_out(array(
                'status' => 0,
                'redirect' => site_url('member/center')
            ));
        }
        $inter_id = $this->inter_id;
        $sms = $this->input->post('sms');
        $tel = $this->input->post('tel');
        
        if (! empty($sms)) {
            if ($this->session->has_userdata('sms') && ($sms != $this->session->sms)) {
                $this->json_out(array(
                    'status' => 0,
                    'errmsg' => '验证码错误'
                ));
            }
            // 绑定操作
            $openid = $this->getOpenId();
            $this->load->library('Baseapi/Subaapi_webservice', array(
                'testModel' => false
            ));
            $suba_api = new Subaapi_webservice(false);
            $rs = $suba_api->CheckMemberStatus($tel);
            if (isset($rs['CheckMemberStatusResult']) && ($rs['CheckMemberStatusResult']['ResultCode'] == '00') && ($rs['CheckMemberStatusResult']['Content']['IsMember'] == 1)) {
                $cardno = $rs['CheckMemberStatusResult']['Content']['CardNo'];
                $Bind_res = $suba_api->BindWeixinCustomer($openid, $cardno);
                if (isset($Bind_res['BindWeixinCustomerResult']) && ($Bind_res['BindWeixinCustomerResult']['IsError'] == false) && ($rs['CheckMemberStatusResult']['ResultCode'] == '00')) {
                    // 查询会员是否注册过
                    $this->load->model('member/member');
                    $member = $this->member->getMemberInfoByTelephone($tel, $inter_id);
                    $subaMember = $suba_api->GetCustomer($cardno);
                    $subaMemberInfo = $subaMember['GetCustomerResult']['Content'];
                    $data['name'] = $subaMemberInfo['CustomeName'];
                    $data['telephone'] = $subaMemberInfo['PhoneNum'];
                    $data['membership_number'] = $subaMemberInfo['MainCardNO'];
                    $data['level'] = $data['member_type'] = $subaMemberInfo['MainCardTypeID'];
                    $data['openid'] = $openid;
                    if (empty($member)) {
                        // 未注册过
                        $this->load->model('member/member');
                        // 登录
                        $this->member->updateMemberByOpenId(array(
                            'openid' => $openid,
                            'level' => $data['level'],
                            'is_login' => 1
                        ), $inter_id);
                        $this->member->updateMemberInfoByOpenId($data);
                        // 发送模板消息
                        $this->load->model('plugins/Template_msg_model');
                        $res = $this->Template_msg_model->send_member_msg(array(
                            'inter_id' => $this->inter_id,
                            'openid' => $openid,
                            'member_name' => $this->input->post('name')
                        ), 'member_reg_completed');
                        $this->json_out(array(
                            'status' => 0,
                            'redirect' => site_url('member/center')
                        ));
                    }
                    $this->member->updateMemberByOpenId(array(
                        'openid' => $openid,
                        'level' => $data['level'],
                        'is_login' => 1
                    ), $inter_id);
                    // 发送模板消息
                    $this->load->model('plugins/Template_msg_model');
                    $res = $this->Template_msg_model->send_member_msg(array(
                        'inter_id' => $this->inter_id,
                        'openid' => $openid,
                        'member_name' => $this->input->post('name')
                    ), 'member_reg_completed');
                    
                    $this->json_out(array(
                        'status' => 0,
                        'redirect' => site_url('member/center')
                    ));
                }
            }
        }
        
        $this->json_out(array(
            'status' => 0,
            'errmsg' => '绑定失败'
        ));
    }
    
    
    public function validate_sms(){
        if($this->inter_id != 'a455510007'){
            $this->json_out(array(
                'status'   => 0,
                'redirect' => site_url('member/center'),
            ));
        }
        $openid = $this->getOpenId();
        if(!isset($openid)){
            $this->json_out(array(
                'status'   => 0,
                'redirect' => site_url('member/center'),
            ));
        }
        
        
        $post = $this->input->post();
        
        if(trim($post['customer']) == ''){
            $this->json_out(array(
                'status' => 0,
                'errmsg' => '姓名不能留空!',
            ));
        }
        
        if(!trim($post['telephone'])){
            $this->json_out(array(
                'status' => 0,
                'errmsg' => '手机号码不能留空!',
            ));
        }
        
        
        if(!$post['sms'] || $post['sms'] != $this->session->userdata('sms')){
            $this->json_out(array(
                'status' => 0,
                'errmsg' => '短信验证码有误!',
            ));
            //			$this->session->set_userdata('message', "短信验证码有误！");
            //			redirect('member/account/personalinfo');
        }
        unset($post['sms']);
        
        /*$this->load->library('Baseapi/Subaapi_webservice', array('testModel' => true));
         $suba = new Subaapi_webservice(false);
         
         $rs = $suba->CheckMemberStatus($post['telephone']);
         if(isset($rs['CheckMemberStatusResult']) && ($rs['CheckMemberStatusResult']['ResultCode'] == '00') && ($rs['CheckMemberStatusResult']['Content']['IsMember'] == 1)){
         $result = array(
         'status' => 0,
         'errmsg' => '此手机号码已注册'
         );
         echo json_encode($result);
         exit;
         }*/
        
        $this->session->set_userdata('activate_member', $post);
        
        $this->json_out(array(
            'status'   => 1,
            'redirect' => site_url('member/account/activecard'),
        ));
    }
    
    public function validate_card(){
        if($this->inter_id != 'a455510007'){
            $this->json_out(array(
                'status'   => 0,
                'redirect' => site_url('member/center'),
                //			                     'errmsg'=>'错误的inter_id'
            ));
        }
        $openid = $this->getOpenId();
        if(!isset($openid)){
            $this->json_out(array(
                'status'   => 0,
                'redirect' => site_url('member/center'),
                //				                 'errmsg'=>'错误Openid'
            ));
        }
        
        if(!$this->session->userdata('activate_member')){
            $this->json_out(array(
                'status'   => 0,
                'redirect' => site_url('member/center'),
                //			                     'errmsg'=>'active_member数组为空'
            ));
        }
        $post = $this->input->post();
        if(trim($post['card_no']) == ''){
            $this->json_out(array(
                'status' => 0,
                'errmsg' => '请输入会员卡号',
            ));
        }
        if(trim($post['card_verify']) == ''){
            $this->json_out(array(
                'status' => 0,
                'errmsg' => '请输入会员卡验证码',
            ));
        }
        
        $this->load->library('PMS_Adapter', array(
            'inter_id' => $this->inter_id,
            'hotel_id' => 0
        ), 'pmsa');
        $webserv = $this->pmsa->getWebServ();
        
        //激活会员
        $member_params = $this->session->userdata('activate_member');
        $res = $webserv->activeMember($member_params['customer'], $member_params['telephone'], $post['card_no'], $post['card_verify'],$openid);
        if($res['status']){
            $res['redirect'] = site_url('member/account/activesuccess');
        } else{
            if(!empty($res['is_active'])){
                $res['route_to'] = site_url('member/account/login');
            }
            if(!empty($res['activefail'])){
                $res['redirect'] = site_url('member/account/activefail');
            }
        }
        $this->json_out($res);
    }
    
    //修改密码
    public function su8modpwd()
    {
        $openid = $this->getOpenId();
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberDetailByOpenId($openid, $this->inter_id, 0);
        
        if(!$member || !isset($member->mem_id) || empty($member->is_active)) {
            $this->session->set_userdata('message', "请先登录账户!");
            redirect('member/center');
            exit;
        }
        
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
        
        if($this->session->has_userdata('message')) {
            $data['message'] = $this->session->message;
            $this->session->unset_userdata('message');
        }
        
        $this->display('member/change_pwd', $data);
    }
    
    //修改密码保存
    public function su8modpwdsave(){
        $openid = $this->getOpenId();
        $data = $this->input->post();
        
        $this->load->model('member/imember');
        $member = $this->imember->getMemberDetailByOpenId($openid, $this->inter_id, 0);
        
        if(!$member || !isset($member->mem_id) || empty($member->is_active) || empty($member->is_login)) {
            $this->session->set_userdata('message', "请先登录账户!");
            $this->json_out(array(
                'status'=>0,
                'redirect'=>site_url('member/center'),
            ));
        }
        
        if(strlen($data['password'])<6||strlen($data['password'])>16){
            $this->json_out(array(
                'status'=>0,
                'errmsg'=>'密码的长度限制6到16位'
            ));
        }
        if($data['confirm']!=$data['password']){
            $this->json_out(array(
                'status'=>0,
                'errmsg'=>'两次输入的密码不一致'
            ));
        }
        
        $data['uid']=$member->membership_number;
        
        $result = $this->imember->modPassword($openid, $data, $this->inter_id, 0);
        // 		print_r($result);exit;
        
        if($result['code']==1){
            $this->json_out(array(
                'status'=>0,
                'errmsg'=>$result["errmsg"],
            ));
        }else{
            $this->session->set_userdata('message', '密码修改成功');
            $this->json_out(array(
                'status'=>1,
                'redirect'=>site_url('member/center'),
            ));
        }
        
        //		$this->session->set_userdata('message', $result["errmsg"]);
        //		redirect('member/center');
    }
    
    private function json_out($array){
        echo json_encode($array);
        exit;
    }
    
}