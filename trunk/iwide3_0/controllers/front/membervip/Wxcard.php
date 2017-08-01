<?php
class Wxcard extends MY_Front{
	function index(){
		$package = $this->getSignPackage();


        $cardId = $this->input->get('card');
        if(empty($cardId))
		    $tmp['cardId']='pcbScjn13YbirrwLKPmjO9Mn2_Io';
        else
            $tmp['cardId']=$cardId;

		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
// 		$apiTicket = 'm7RQzjA_ljjEkt-JCoklRI_esR4R_mcK6OomDr8pNPWvKoaWpJZc3ZZ-z6SMeEo4FtE3u9bAzqHK8DnVsBtHjQ';
		$apiTicket = $this->access_token_model->get_card_ticket($this->input->get('id'));
		$str = createNonceStr();
		$p=$this->getSignCard($tmp['cardId'],$apiTicket,null,$str);

		$tmp['cardExt']=json_encode(array(
				'timestamp'=>$p['timestamp'],
				'signature'=>$p['signature'],
				'nonce_str'=>$str),JSON_FORCE_OBJECT);
		$str = <<<EOF
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset="utf-8">
		<title>领取会员卡</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
		</head>
		<body ontouchstart="">
		</body>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script>
		wx.config({
			debug: false,
			appId: '{$package['appId']}',
			timestamp: {$package['timestamp']},
			nonceStr: '{$package['nonceStr']}',
			signature: '{$package['signature']}',
			jsApiList: [ 'addCard', 'chooseCard', 'openCard' ]
		});
		wx.ready(function () {
		  wx.addCard({
			      cardList: [
			        {cardId: '{$tmp['cardId']}',
			          cardExt: '{$tmp['cardExt']}'}
			      ],
			      success: function (res) {alert('已添加会员卡' );},
			      cancel: function (res) {WeixinJSBridge.call('closeWindow');}
			    });
		    });
			</script>
			</html>
EOF;
		echo $str;
	}
	public function getSignPackage($url='') {
		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
		$jsapiTicket = $this->access_token_model->get_api_ticket($this->input->get('id'));
// 		$jsapiTicket = 'm7RQzjA_ljjEkt-JCoklRI_esR4R_mcK6OomDr8pNPWvKoaWpJZc3ZZ-z6SMeEo4FtE3u9bAzqHK8DnVsBtHjQ';
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		if(!$url)
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$timestamp = time();
			$nonceStr = createNonceStr();
			// 这里参数的顺序要按照 key 值 ASCII 码升序排序
			$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

			$signature = sha1($string);

			$this->load->model('wx/publics_model');
			$public=$this->publics_model->get_public_by_id($this->input->get('id'));
			$signPackage = array(
					"appId"     => $public['app_id'],
					"nonceStr"  => $nonceStr,
					"timestamp" => $timestamp,
					"url"       => $url,
					"signature" => $signature,
					"rawString" => $string
			);
			return $signPackage;
	}
	public function getSignCard($card_id,$app_secret,$code=null,$str=''){
		$timestamp = time();
		$signature = new Signature();
		$signature->add_data( $timestamp );
		if(!is_null($str))$signature->add_data( $str );
		$signature->add_data( $card_id );
		$signature->add_data( $app_secret );
		if(!is_null($code))$signature->add_data( $code );
		return array('signature'=>$signature->get_signature(),'timestamp'=>$timestamp);
	}
}
class Signature{
	function __construct(){
		$this->data = array();
	}
	function add_data($str){
		array_push($this->data, (string)$str);
	}
	function get_signature(){
		sort( $this->data,SORT_LOCALE_STRING );
		$string = implode( $this->data );
		return sha1( $string );
	}
}
?>