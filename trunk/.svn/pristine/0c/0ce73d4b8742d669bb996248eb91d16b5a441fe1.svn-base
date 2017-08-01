<?php
class Tapi extends MY_Controller {
	//token=c136998cb6;
	//key=APANtByIGI1BpVXZTJgcsAG8GZl8pdwwa8436998cb6;
	//当前公众号id
	private $wx_master_id;
	
	private $appid            = 'wx26cd9be0e8b4e749';
	private $app_secret       = 'afeb7211ae615f59f1592c491e126a14';
	private $encoding_aes_key = 'APANtByIGI1BpVXZTJgcsAG8GZl8pdwwa8436998cb6';
	private $token            = 'c136998cb6';
	
	function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( FALSE );
		
		$this->load->model ( 'wx/Publics_model' );
		$info = $this->Publics_model->get_public_by_id ( 'a111111111' );
		if (isset ( $info ['app_id'] ))
			$this->appid = $info ['app_id'];
		if (isset ( $info ['app_secret'] ))
			$this->app_secret = $info ['app_secret'];
		if (isset ( $info ['token'] ))
			$this->token = $info ['token'];
		if (isset ( $info ['aes_key'] ))
			$this->encoding_aes_key = $info ['aes_key'];
		// $this->token = $this->input->get('id' ); // 公众号内部ID
	}
	
	public function index() {
		$echostr = $this->input->get ( 'echostr' );
		$xml = file_get_contents ( 'php://input' );
 		$this->load->library ( 'WxApi/Prpcrypt', array ( $this->encoding_aes_key ), 'msg_crypter' );
		
 		$content = simplexml_load_string ( $xml, "SimpleXMLElement", LIBXML_NOCDATA );
 		$msg = $this->msg_crypter->decrypt ( $content->Encrypt, $this->appid );
 		$rec_xml = simplexml_load_string ( $msg [1], "SimpleXMLElement", LIBXML_NOCDATA );
		//$rec_xml = simplexml_load_string ( file_get_contents ( 'php://input' ), "SimpleXMLElement", LIBXML_NOCDATA );
		
 		log_message('error', '====>开放平台接口推送->'.$msg [1]);
		if (isset ( $rec_xml->InfoType )) {
			$this->load->model ( 'wx/Access_token_model' );
			$this->load->model ( 'wx/publics_model' );
			switch ($rec_xml->InfoType) {
				case 'component_verify_ticket' :
					$this->Access_token_model->_set_component_verify_ticket ( $rec_xml->ComponentVerifyTicket );
				case 'authorized' :
					$this->publics_model->update_auth_status ( array ( 'app_id' => $rec_xml->AuthorizerAppid, 'info_typ' => 2, 'authorization_code' => $rec_xml->AuthorizationCode, 'expire_time' => time () + $rec_xml->AuthorizationCodeExpiredTime ) );
				case 'updateauthorized' :
					$this->publics_model->update_auth_status ( array ( 'app_id' => $rec_xml->AuthorizerAppid, 'info_typ' => 2, 'authorization_code' => $rec_xml->AuthorizationCode, 'expire_time' => time () + $rec_xml->AuthorizationCodeExpiredTime ) );
				case 'unauthorized' :
					$this->publics_model->update_auth_status ( array ( 'app_id' => $rec_xml->AuthorizerAppid, 'info_typ' => 1, 'expire_time' => time () ) );
				default :
			}
			$pinfo = $this->publics_model->get_public_by_id($rec_xml->AuthorizerAppid,'app_id');
			if($rec_xml->InfoType == 'unauthorized')
				$this->Access_token_model->set_redis_key_status($pinfo['inter_id'].'_AUTH_INFO','');
			else{
				$this->Access_token_model->set_redis_key_status($pinfo['inter_id'].'_AUTH_INFO',json_encode($rec_xml));
			}
			echo 'success';
			exit ();
		}else{
			echo 'success';
		}
	}
	
}

?>