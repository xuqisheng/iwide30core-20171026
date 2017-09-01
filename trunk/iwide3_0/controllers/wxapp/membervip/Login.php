<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户登录
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Login extends MY_Front_Member_Wxapp
{
	//会员卡登录页面
	public function index(){
		
		//莫林定制
		if($this->inter_id=='a452223043'){
			
			$this->binning_login();
			return;
			 
		}
		
		
		$post_config_url = PMS_PATH_URL."adminmember/getloginconfig";
		$post_config_data =  array(
			'inter_id'=>$this->inter_id,
        );
		//请求登录配置
        $login_config = array();
        $login_conf = $this->doCurlPostRequest( $post_config_url , $post_config_data );
        if(isset($login_conf['data']) && !empty($login_conf['data'])){
            $login_config = $login_conf['data'];
        }
        //'^[1][35789][0-9]{9}$'
        if($this->inter_id=='a421641095'){
            if(isset($login_config['phone']) && isset($login_config['phone']['regular']) && !empty($login_config['phone']['regular'])){
                $login_config['phone']['regular'] = '^[0-9]{6,}$';
            }
           
        }
        $data['login_model'] = 1;
        
        $data['login_config'] = $login_config;
       // $this->load->view('member/'.$this->_template.'/login',$data);
        $this->out_put_msg ( 1,'', $data ,'membervip/login/index');
	}

	//绑定登录模式
	public function binning_login(){
		$post_config_url = PMS_PATH_URL."adminmember/getloginconfig";
		$post_config_data =  array(
			'inter_id'=>$this->inter_id,
			);
		//请求登录配置
		$data['login_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
		
		
		$data['login_model'] = 3;
		
		$this->out_put_msg ( 1,'', $data ,'membervip/login/binning_login');
		//$this->load->view('membervip/login/binning_login',$data);
	}

	//储值卡绑定
	public function bindcard(){
        $post_config_url = PMS_PATH_URL."adminmember/getloginconfig";
        $post_config_data =  array(
            'inter_id'=>$this->inter_id,
        );
        //请求登录配置
        $data['login_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $this->load->view('member/'.$this->_template.'/bindcard',$data);
    }

    //保存储值卡绑定
    public function savebindcard(){
        $bind_url = PMS_PATH_URL."member/bindcard";
        $bind_data = array(
            'inter_id'=>$this->inter_id,
            'openid'=>$this->openid,
            'data'=>$_POST,
        );
        $bind_result = $this->doCurlPostRequest( $bind_url , $bind_data );
        echo json_encode($bind_result);
    }

	//登录保存
	public function savelogin(){
		$post_login_url = PMS_PATH_URL."member/login";
		$post_login_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'data'=>$_POST,
        );
		
		if($this->inter_id=='a421641095'){
	
			if( !isset($_POST['phone']) || $_POST['phone'] == ""){
		
				$this->out_put_msg ( 2,'手机号码不能为空', $login_result ,'membervip/reg/savereg');
				return;
		
			}
			 
			if( !isset($_POST['phonesms']) || $_POST['phonesms'] == ""){
		
				$this->out_put_msg ( 2,'验证码不能为空', $login_result ,'membervip/reg/savereg');
				return;
		
			}
			 
		}
		
        //如果有验证码,验证
        if(isset($_POST['phonesms']) || $this->inter_id=='a421641095'){
            $checkSmsData = $post_login_data;
            $checkSmsData['data']['sms']=$_POST['phonesms'];
            $checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
            $checkSmsData['cellphone']=$checkSmsData['phone'];
            $checkSmsData['sms']=$_POST['phonesms'];
            $checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
            $res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
            if($res['err']>0){
                //echo json_encode($res);exit;
                $this->out_put_msg ( 2, $res['msg'], $res ,'membervip/center');
            }
        }

		$login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
		//echo json_encode($login_result);
		if($login_result['err']>0){
			
			if($this->inter_id=='a421641095'){
				if(strpos($login_result['msg'],"密码") > 0){
					$login_result['msg'] = "不存在此手机号码的会员，请先注册";
				}
			}
			
			$this->out_put_msg ( 2, $login_result['msg'], $login_result ,'membervip/Login/savelogin');
		}else{
			$this->out_put_msg ( 1, $login_result['msg'], $login_result ,'membervip/Login/savelogin');
		}
	}
	
	
	//登录保存
	public function savelogin2(){
		$this->session->unset_tempdata($this->inter_id.'vip_user');
		$post_login_url = PMS_PATH_URL."member/login";
		if($this->inter_id == 'a480304439'){ //优程定制
			$this->load->model ( 'distribute/Fans_model' );
			$fans = $this->Fans_model->get_fans_beloning($this->inter_id,$this->openid);
			if(!empty($fans)){
				$_POST['hotel_id']  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '' ;
			}
		}
		$post_login_data = array(
				'inter_id'=>$this->inter_id,
				'openid'=>$this->openid,
				'data'=>$_POST,
		);
	
		//如果有验证码,验证
		$conf_url = PMS_PATH_URL."adminmember/getloginconfig";
		$post_data =  array('inter_id'=>$this->inter_id);
		//请求登录配置
		$loginconfig = $this->doCurlPostRequest($conf_url,$post_data);
		$loginconfig = isset($loginconfig['data'])?$loginconfig['data']:array();
		if(isset($loginconfig['phonesms']) && $loginconfig['phonesms']['show']=='1' && $loginconfig['phonesms']['check']=='1'){
			if(!isset($_POST['phonesms'])) {
				echo json_encode(array('err'=>'40003','msg'=>'验证码不存在'));exit;
			}
			$checkSmsData = $post_login_data;
			$checkSmsData['data']['sms']=$_POST['phonesms'];
			$checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
			$checkSmsData['cellphone']=$checkSmsData['phone'];
			$checkSmsData['sms']=$_POST['phonesms'];
			$checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
			$res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
			if($res['err']>0){
				echo json_encode($res);exit;
			}
		}
	
		$login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
	/* 	if($login_result['err']=='0'){
			$this->load->model('membervip/front/Member_model');
			$this->Member_model->check_user_info($this->inter_id,$this->openid);
		} */
		if($login_result['err']>0){
				

				
			$this->out_put_msg ( 2, $login_result['msg'], $login_result ,'membervip/Login/savelogin');
		}else{
			$this->out_put_msg ( 1, $login_result['msg'], $login_result ,'membervip/Login/savelogin');
		}
		echo json_encode($login_result);
	}

	//退出登录
	public function outlogin(){
		$post_login_url = PMS_PATH_URL."member/outlogin";
		$post_login_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			);
		$login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
		echo json_encode($login_result);
	}

}
?>