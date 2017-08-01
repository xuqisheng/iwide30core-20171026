<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户重置密码
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Changepassword extends MY_Front_Member
{
	//用户重置密码页面
	public function index(){
		$post_config_url = PMS_PATH_URL."adminmember/getupdateconfig";
		$post_config_data =  array(
			'inter_id'=>$this->inter_id,
			);
		//请求注册配置
		$res = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $data['password_config'] = $res;
        $this->template_show('member',$this->_template,'changepassword',$data);
    }

	//保存重置密码
	public function saveupdatepassword(){
		$post_save_password_url = PMS_PATH_URL."member/updatepassword";

        $oldpassword = $this->input->post('oldpassword');
        $newpassword = $this->input->post('newpassword');
        $confirm_pwd = $this->input->post('newpassword_con');

        if(empty($oldpassword) || empty($newpassword) || empty($confirm_pwd ) ){
            echo json_encode(array('err'=>40003,'msg'=>'修改失败，请按确认输入的信息无误'));
            exit;
        }else if($newpassword != $confirm_pwd){
            echo json_encode(array('err'=>40003,'msg'=>'输入的两个新密码不一致'));
            exit;
        }


        $post_save_password_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'oldpassword'=>$this->input->post('oldpassword'),
			'newpassword'=>$this->input->post('newpassword'),
			'sms'=>$this->input->post('phonesms'),
        );

        //如果有验证码,验证
//        $conf_url = PMS_PATH_URL."adminmember/getupdateconfig";
//        $post_data =  array('inter_id'=>$this->inter_id);
//        //请求登录配置
//        $pwdconfig = $this->doCurlPostRequest($conf_url,$post_data);
//        $pwdconfig = isset($pwdconfig['data'])?$pwdconfig['data']:array();
//        if(isset($pwdconfig['phone']) && $pwdconfig['phone']['show']=='1' && $pwdconfig['phone']['check']=='1'){
//            if(!isset($_POST['phonesms'])) {
//                echo json_encode(array('err'=>'40003','msg'=>'验证码不存在'));exit;
//            }
//            $checkSmsData = $post_login_data;
//            $checkSmsData['data']['sms']=$_POST['phonesms'];
//            $checkSmsData['cellphone']=$post_login_data['phone'];
//            $checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
//            $res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
//            if($res['err']>0){
//                echo json_encode($res);exit;
//            }
//        }

		$update_result = $this->doCurlPostRequest( $post_save_password_url , $post_save_password_data );
		echo json_encode($update_result);
	}

}
?>