<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Public_interface extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
		ini_set ( 'display_errors', 0 );
		if (version_compare ( PHP_VERSION, '5.3', '>=' )) {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_USER_NOTICE & ~ E_USER_DEPRECATED );
		} else {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_STRICT & ~ E_USER_NOTICE );
		}
	}
	
	/**
	 * 返回Access_token
	 */
	public function get_access_token() {
		try {
			$this->load->model ( 'wx/access_token_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$access_token = $this->access_token_model->get_access_token ( $source ['itd'], TRUE );
			if (! isset ( $access_token ['access_token'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'system error' );
			}
			$this->Icommon_model->out_put_msg ( TRUE, '', array (
					'access_token' => $access_token ['access_token'] 
			) );
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	
	/**
	 * 刷新并返回Access_token
	 */
	public function reflash_access_token() {
		try {
			$this->load->model ( 'wx/access_token_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$this->load->model ( 'api/signiture_model' );
			$access_token = $this->access_token_model->reflash_access_token ( $source ['itd'], TRUE );
			if (! isset ( $access_token ['access_token'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'system error' );
			}
			$this->Icommon_model->out_put_msg ( TRUE, '', array (
					'access_token' => $access_token ['access_token'] 
			) );
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	public function code_openid() {
		try {
			$this->load->model ( 'interface/Icommon_model' );
			$this->load->model ( 'interface/Isigniture_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			if (empty ( $source ['code'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'wrong code' );
			}
			$inter_id = $source ['itd'];
			$result = array (
					's' => 0,
					'errmsg' => '' 
			);
			$wx_result = json_decode ( $this->_auth_res ( $source ['code'], $source ['itd'] ), true );
			if (! empty ( $wx_result )) {
				if (! empty ( $wx_result ['openid'] )) {
					$this->Icommon_model->out_put_msg ( TRUE, '', array (
							'openid' => $wx_result ['openid'] 
					) );
				} else {
					$this->Icommon_model->out_put_msg ( FALSE, '微信接口调用失败', array (
							'wx_result' => $wx_result 
					) );
				}
			} else {
				$this->Icommon_model->out_put_msg ( FALSE, '无会员登录信息' );
			}
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	
	/**
	 * 网页授权通过code获取用户信息
	 * @param String code
	 * @param String 公众号识别码
	 * @return JSON 请求微信返回结果
	 */
	private function _auth_res($code, $inter_id) {
		$this->load->model ( 'wx/Publics_model' );
		$public = $this->Publics_model->get_public_by_id ( $inter_id );
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $public ['app_id'] . "&secret=" . $public ['app_secret'] . "&code=$code&grant_type=authorization_code";
		
		$this->load->helper ( 'common' );
		return doCurlGetRequest ( $url );
	}
	
	/**
	 *
	 * 仅供给欠费停服系统使用的接口
	 * 可以将某个酒店集团的状态进行设置，状态分为：停服，正常，欠费
	 * 可以设置某个酒店欠费的金额
	 */
	public function setHotelStatus(){
		
		$content = file_get_contents ( 'php://input' );
		
		$data = json_decode ( $content, TRUE );
		
		MYLOG::w(' jboss | set server status | '.$content,'30_api');
		
		$token = $data['token'];
		
		$key = "602jovy3f5ckigtvh0wn4lbbz0um9lyi";
		
		if( $data['inter_id'] == ""){
			$this->sendMsg(-1, 'inter_id is null');
			exit;
		}
		
		if( $data['status'] == ""){
			$this->sendMsg(-1, 'status is null');
			exit;
		}
		
		if( !($data['money'] >= 0) ){
			$this->sendMsg(-1, 'money is error');
			exit;
		}
		
		unset($data['token']);
		ksort($data);
		if( $token === md5($key.http_build_query($data)) ){
			
			$sql = "
					UPDATE
						iwide_publics
					SET
						run_status = '{$data['status']}'
						,arrearage_money = '{$data['money']}'
						,stop_service_time = '{$data['stop_time']}'
					WHERE
						inter_id = '{$data['inter_id']}'
					LIMIT 1
					";
			$this->db->query($sql);
			
			/* if( $this->db->affected_rows() ){
				$this->sendMsg(1);
			}else{
				$this->sendMsg(0,'update fail');
			} */
			$this->sendMsg(1);
			
			
		}else{
			$this->sendMsg(-1, 'token is not match');
		}
		
		
	}
	
	/**
	 * 返回Access_token
	 */
	public function my_get_access_token() {

		$content = file_get_contents ( 'php://input' );
		
		$data = json_decode ( $content, TRUE );
				
		MYLOG::w(' jboss | get_access_token | '.$content,'30_api');
		
		$token = $data['token'];
		
		
		if( $data['inter_id'] == ""){
			$this->sendMsg(-1, 'inter_id is null');
			exit;
		}
		
		
		
		if( $token === $this->myapi_encode_token($data) ){
			
			$this->load->model ( 'wx/access_token_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$access_token = $this->access_token_model->get_access_token ( $data['inter_id'], TRUE );
			
			if($access_token){
				$this->sendMsg(1,"",$access_token);
			}else{
				$this->sendMsg(0,"access_token is error");
			}
			
		}else{
			$this->sendMsg(0,"token is error");
		}
			
		
	}
	
	/**
	 * 刷新并返回Access_token
	 */
	public function my_reflash_access_token() {
		
		$content = file_get_contents ( 'php://input' );
		
		$data = json_decode ( $content, TRUE );
		
		MYLOG::w(' jboss | get_access_token | '.$content,'30_api');
		
		$token = $data['token'];
		
		
		if( $data['inter_id'] == ""){
			$this->sendMsg(-1, 'inter_id is null');
			exit;
		}
		
		
		
		if( $token === $this->myapi_encode_token($data) ){
				
			$this->load->model ( 'wx/access_token_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$access_token = $this->access_token_model->reflash_access_token ( $data['inter_id'], TRUE );
				
			if($access_token){
				$this->sendMsg(1,"",$access_token);
			}else{
				$this->sendMsg(0,"access_token is error");
			}
				
		}else{
			$this->sendMsg(0,"token is error");
		}
	} 
	
	private function sendMsg($status,$msg = '',$data = ""){
		
		$arr = array();
		if($status == 1){
			$arr['code'] = "ok";
		}else{
			$arr['code'] = "error";
		}
		
		$arr['msg'] = $msg;
		
		$arr['data'] = $data;
		
		echo json_encode($arr);
		
		
	}
	
	/**
	 * 供内部模块使用的接口加密处理
	 * @param $data 数据数组
	 */
	private function myapi_encode_token($data){
	
		$key = "602jovy3f5ckigtvh0wn4lbbz0um9lyi";
	
		ksort($data);
		
		return md5($key.http_build_query($data));
	
	}
	
	
}