<?php
class Yzhou_webservice {
	var $user_name = "sinocc";
	var $pwd = "yzjtweb";
	var $url = "http://60.191.133.2:888/services/websiteService.ws?wsdl";
	var $debug_url = "http://116.228.78.115:7006/services/websiteService.ws?wsdl";
	function __construct() {
	}
	function sendTo($fun, $in0_xml, $debug = 0, $inter_id = 'a440577876') {
		$pwd = md5 ( $this->pwd );
// 		$url = $this->debug_url;
		$debug=1;
		$url = $debug == 1 ? $this->debug_url : $this->url;
		$soap = new SoapClient ( $url );
		
		$soap->__setSoapHeaders ( Array (
				new YzhouAuthHeader ( $this->user_name, $pwd ) 
		) );
		
		$arr = array (
				"in0" => $in0_xml 
		);
		$now = time ();
		$s = $soap->$fun ( $arr );
		$CI = & get_instance ();
		$CI->load->model ( 'common/Webservice_model' );
		$CI->Webservice_model->add_webservice_record ( $inter_id, 'yuanzhou', $url . '/' . $fun, $arr, $s, 'webservice', $now, microtime (), $CI->session->userdata ( $inter_id . 'openid' ) );
		if (isset ( $s->out )) {
			return $s->out;
		}
		return $s;
	}
}
class YzhouAuthHeader extends SoapHeader {
	private $wss_ns = 'http://www.sinocc.com';
	private $wsu_ns = 'http://www.sinocc.com';
	function __construct($user, $pass) {
		$passdigest = md5 ( $pass );
		
		$auth = new stdClass ();
		
		$auth->Username = new SoapVar ( $user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns );
		$auth->Password = new SoapVar ( $pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns );
		
		parent::__construct ( $this->wss_ns, 'AuthenticationToken', $auth );
	}
}
?>