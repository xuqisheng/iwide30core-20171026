<?php
if (! defined ( 'BASEPATH' ))
	exit ( json_encode ( array (
			'status' => '1003' 
	) ) );
class User extends MY_Front_Wxapp {
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * 发送token、微信getUserInfo的encryptData以登录
	 */
	public function login() {
		$this->load->model ( 'wxapp/Auth_model' );
		$this->load->model ( 'wxapp/User_model' );
		$source = $this->source;
		
		$this->load->library ("MYLOG");
				
		$session_data = empty ( $this->fans_ext ) ? $this->get_token_session ( $this->inter_id, $this->get_source ( 'token', '', FALSE ),'login' ) : $this->fans_ext;
		//MYLOG::w("session_data = {$session_data}","wxapp_log","sessiondata");
		if (! empty ( $session_data )) {
		    MYLOG::w("session_data=".json_encode($session_data),"wxapp_log","sessiondata");
			// 校验签名以及解密结果
			if ($this->Auth_model->check_signature ( $source ['send_data'] ['rawData'], $source ['send_data'] ['signature'], $session_data )) {
// 				$decrypt_data = $this->Auth_model->check_decrypt_data ( $source ['send_data'] ['encryptData'], $session_data );
			    //MYLOG::w("2","wxapp_log","sessiondata");
			    $decrypt_data = $this->Auth_model->check_decrypt_data ( $source ['send_data'] , $session_data );
				if ($decrypt_data) {
					$data ['member_lv_name'] = '微信会员';
					//MYLOG::w("3","wxapp_log","sessiondata");
					MYLOG::w(json_encode($decrypt_data),"wxapp_log","decrypt_data_log");
					if ($this->debug) {
						$data ['decrypt_data'] = $decrypt_data;
					}
					$union_id=empty ( $decrypt_data ['unionId'] )?'':$decrypt_data ['unionId'];
					$this->User_model->save_fans_ext ( $this->inter_id, $session_data ['wxapp_openid'], $session_data ,$union_id);
					$this->out_put_msg ( 1, '', $data );
				}
			}
		}
		$this->out_put_msg ( 2, '登录失败，请重试', array () );
	}
	public function wx_login() {
		$this->load->model ( 'wxapp/Auth_model' );
		$this->load->model ( 'wxapp/User_model' );
		$this->load->model ( 'wxapp/Icommon_model' );
		$source = $this->source;
		MYLOG::w(json_encode($source),"wxapp_log","wx_login_data_log");
		$result = $this->Auth_model->get_app_sessionkey ( $source ['inter_id'], $source ['send_data'] ['code'] );
		if (isset ( $result ['session_key'] )) {
			$result = $this->User_model->save_session_token ( $source ['inter_id'], $result ['session_key'], $result ['expires_in'], $result ['openid'] );
			$this->token = $result ['token'];
			$this->set_user_session ( 'fans_ext', json_encode($result ['session_data'] ));
			$data ['token'] = $result ['token'];
			if ($this->debug) {
				$data ['wx_result'] = $result;
			}
			 MYLOG::w("return | ".json_encode($data),"wxapp_log","wx_login_data_log");
			$this->out_put_msg ( 1, '', $data );
		} else {
			$this->out_put_msg ( 2, '获取session_key失败' );
		}
	}
	
	public function app_login() {
		$this->load->model ( 'wxapp/Auth_model' );
		$this->load->model ( 'wxapp/User_model' );
		$this->load->model ( 'wxapp/Icommon_model' );
		$source = $this->source;
		//MYLOG::w(json_encode($source),"app_log","app_login_data_log");
		$result = $this->Auth_model->get_userinfo_by_accesstoken ( $source ['inter_id'], $source ['send_data'] ['rtoken'],$source ['send_data'] ['openid'] );
		
		MYLOG::w("source=".json_encode($source)." | getweixin=".json_encode($result),"app_log","app_login_data_log");
		
		if ($result ['openid'] == $source ['send_data'] ['openid']) {
			
			$token = md5($source ['send_data'] ['rtoken'].$result['openid'].time());
			
			$this->User_model->app_save_fans_ext ( $this->inter_id,$result ['openid'] ,$result ['unionid'],$token);
			//$result = $this->User_model->save_session_token ( $source ['inter_id'], $result ['session_key'], $result ['expires_in'], $result ['openid'] );
			$this->token = $token;
			$data ['token'] = $token;	
			$data ['member_lv_name'] = '微信会员';
			$data ['decrypt_data'] = $result;
				
			MYLOG::w("return | ".json_encode($data),"app_log","app_login_data_log");
			$this->out_put_msg ( 1, '', $data );
		} else {
			$this->out_put_msg ( 2, '登录失败' );
		}
	}
	
	public function get_config(){
		$this->load->model ( 'wxapp/Auth_model' );
		$appinfo = $this->Auth_model->get_public_by_id($this->inter_id);
		
		$this->load->model('app/App_config_model');
		//$this->App_config_model->get_hotel_config($this->inter_id, array('menu_config','share_config'), 'wxapp', 'hotel',0);
		$common_config = $this->App_config_model->get_hotel_config($this->inter_id, array('share_config'), 'wxapp', 'common',0);
		$member_config = $this->App_config_model->get_hotel_config($this->inter_id, array('center_menu','member_model'), 'wxapp', 'member',0);
		//center_menu
		$data = array();
		
		
/* 		$config_menu = array(
				'show_hotel'=>true,
				'show_soma'=>true,
				'show_bookroom'=>false,
				'show_buysoma'=>true
		); */
		$field = array('share_title','share_content','share_url');
		
		foreach($field as $key){
			if(isset($common_config['$share_config'][$key])){
				$data[$key] = $common_config['$share_config'][$key];
			}
		}

		//单店模式，默认为false
		if(isset($member_config['member_model']['model'])){
			$data['one_hotel_model'] = $member_config['member_model']['model']?true:false;
		}
		
		if(isset($member_config['center_menu']['menus'])){
			$config_menu = json_decode($member_config['center_menu']['menus'],true);
			if(is_array($config_menu))
				foreach($config_menu as $key=> $d){
					$data[$key] = $d;
				}
		}

		$this->out_put_msg ( 1, '', $data);
	
	}
}
