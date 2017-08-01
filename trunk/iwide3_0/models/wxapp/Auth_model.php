<?php
class Auth_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	protected function _load_db() {
		return $this->db;
	}
	function get_app_sessionkey($inter_id, $code) {
		$pub = $this->get_public_by_id ( $inter_id );
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $pub ['app_id'] . "&secret=" . $pub ['app_secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
		$this->load->helper ( 'common' );
		$data = doCurlGetRequest ( $url );
		MYLOG::w("get weixin session | {$data}","wxapp_log","get_weixin");
		$data = json_decode ( $data, true );
		$data['expires_in'] = time() + 1800;
		return $data;
	}
	
	/**
	 * 微信app登录中针对个人的accesstoken，与公众号调用接口的accesstoken针对的不一样，前者针对每一个用户
	 * @param unknown $inter_id
	 * @param unknown $code
	 * @return unknown
	 */
	function get_userinfo_by_accesstoken($inter_id,$acesstoken,$openid){

		//3Afm2ckq9mjjZ7TzyDcUZkP8QJM_WkyTI2-8WThJUvxaFfmHhNuo_cJdfgljLLA-LkGBW2X9jAiWzS_5AooJsWZuwUDwtNeEoqthY86FnKA
		//$pub = $this->get_public_by_id ( $inter_id );
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$acesstoken}&openid=$openid";
		//$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$pub ['my_app_id']}&secret={$pub ['my_app_secret']}&code={$code}&grant_type=authorization_code";
		MYLOG::w($url,"app_log","app_login_data_log");
		
		$this->load->helper ( 'common' );
		$data = doCurlGetRequest ( $url );
		$data = json_decode ( $data, true );
		return $data;
		
		
	}
	
	
	function get_public_by_id($id, $field = 'inter_id', $status = null) {
		$db = $this->_load_db ();
		if (! is_null ( $status )) {
			$db->where ( 'status', $status );
		}
		return $db->get_where ( 'publics_appinfo', array (
				$field => $id 
		) )->row_array ();
	}
	function app_signature($raw_data, $session_key) {
		if (! is_string ( $raw_data ))
			$raw_data = json_encode ( $raw_data, JSON_UNESCAPED_UNICODE );
		return sha1 ( $raw_data . $session_key );
	}
	function check_decrypt_data($encrypt_data, $session_data) {
		if (! empty ( $encrypt_data ['encryptedData'] ) && ! empty ( $encrypt_data ['iv'] )) {
			$decrypt_data = $this->appdata_decryptiv ( $encrypt_data ['encryptedData'], $session_data ['wxapp_sessionkey'], $encrypt_data ['iv'] );
		} elseif (! empty ( $decrypt_data ['encryptData'] )) {
			$decrypt_data = $this->appdata_decrypt ( $encrypt_data ['encryptData'], $session_data ['wxapp_sessionkey'] );
		}
		if (empty ( $decrypt_data )) {
			return FALSE;
		}
		$decrypt_data = substr ( $decrypt_data, 0, strrpos ( $decrypt_data, '}' ) + 1 ); // 微信返回的encryptData解密后末尾有一个"SOH"字符，MDZZ
		$decrypt_data = json_decode ( $decrypt_data, TRUE );
		if (! empty ( $decrypt_data ) && $decrypt_data ['openId'] == $session_data ['wxapp_openid']) {
			return $decrypt_data;
		}
		return FALSE;
	}
	function check_signature($raw_data, $app_signature, $session_data) {
		$signature = $this->app_signature ( $raw_data, $session_data ['wxapp_sessionkey'] );
		return $signature == $app_signature;
	}
	function appdata_decrypt($encrypt_data, $key) {
		$encryptedData = base64_decode ( $encrypt_data );
		$privateKey = base64_decode ( $key );
		return mcrypt_decrypt ( MCRYPT_RIJNDAEL_128, $privateKey, $encryptedData, MCRYPT_MODE_CBC, $privateKey );
	}
	function appdata_decryptiv($encrypted_data, $key, $iv) {
		$this->load->library ( 'App/Wxapp_decrypt' );
		$decrypt_obj = new Wxapp_decrypt ();
		return $decrypt_obj->decryptData ( $encrypted_data, $key, $iv );
	}
	function check_login($inter_id, $token) {
		;
	}
}