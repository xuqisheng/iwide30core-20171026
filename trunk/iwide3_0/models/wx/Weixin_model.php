<?php
/**
 * 微信基础
 *
 */
class Weixin_model extends CI_Model {
	var $data = array ();
	var $content = '';
	var $sReqTimeStamp, $sReqNonce, $sEncryptMsg,$token;
	public function __construct() {
		$content = file_get_contents ( 'php://input' );
		$this->content = $content;
		if(empty($content)){
			die ( '这是微信请求的接口地址，直接在浏览器里无效' );
		}
		/**
		 * 微信消息加解密说明
		 * @link https://mp.weixin.qq.com/wiki
		 * 针对开放平台和已启用加解密功能的公众号启用
		 * */
		if (isset($_GET ['encrypt_type']) && $_GET ['encrypt_type'] == 'aes') {
			$this->sReqTimeStamp = $this->input->get('timestamp');
			$this->sReqNonce = $this->input->get('nonce');
			$this->sEncryptMsg = $this->input->get('msg_signature');
			$this->load->model('wx/Publics_model');
			$info = array();
			if($this->uri->segment(3)){
				$info = $this->Publics_model->get_public_by_id('a111111111');
			}else{
				$info = $this->Publics_model->get_public_by_id($this->input->get('id'));
				$info['token'] = $this->input->get('id');
			}
			$this->token = $info ['inter_id'];
			$this->load->library('WxApi/wxbizmsgcrypt',array($info ['token'], $info ['aes_key'], $info ['app_id']),'wxcpt');
			$sMsg = ""; // 解析之后的明文
			$errCode = $this->wxcpt->decryptMsg ( $this->sEncryptMsg, $this->sReqTimeStamp, $this->sReqNonce, $content, $sMsg );
			if ($errCode != 0) {
				exit ();
			} else {
				// 解密成功，sMsg即为xml格式的明文
				$content = $sMsg;
			}
		}
		$data = simplexml_load_string($content,"SimpleXMLElement",LIBXML_NOCDATA);
		$card_events = array('card_pass_check','user_get_card','user_del_card','user_consume_card','user_view_card','user_enter_session_from_card');
		
		if($this->input->get('id')=='a439536396' && !in_array($data['event'],$card_events)){//城市名人a439536396
			$this->load->helper('common');
			echo doCurlPostRequest('http://dx.4008266333.net/cch.aspx',$content);
			exit;
		}
		$this->data = json_decode(json_encode($data),TRUE);
		if(isset($this->data['ToUserName']) && $this->data['ToUserName'] == 'gh_edf5ea3f64a3' && (!empty($this->getCusMsgStatus()) || (isset($this->data['Content']) && ($this->data['Content'] == '人工客服' || $this->data['Content'] == 'kf')))){
			$this->transCusService();
			echo 'SUCCESS';
			exit;
		}
	}
	/* 获取微信平台请求的信息 */
	public function getData() {
		return $this->data;
	}
	/* ========================发送被动响应消息 begin================================== */
	public function replyService($to_user_name,$from_user_name,$interid=""){
		/* $handle = @fopen(APPPATH. 'logs/kefu2.log','a');
		if ($handle) {
			fwrite($handle,date('Y-m-d H:i:s')."__".$interid.' - '.file_get_contents("php://input")."\n");
			fclose($handle);
		} */
		
		//d开头的为测试号， a开头的为正式，目前365和远洲 采用自主多客服系统处理数据
		//白云：a450941565
		/*
		if(!empty($interid) && ($interid == "a489736105" || $interid == "d460605501" || $interid == "d460605502" || $interid == "a450941565" || $interid == "a445223616" || $interid == "a440577876" || $interid == "a452233816" || $interid == "a450939254" || $interid == "a450690696" || $interid == "a456989316")){
			$url = "";
			if($interid == "d460605501" || $interid == "d460605502"){
				$url = "http://kefutest.iwide.cn/frontend/web/index.php?r=public/recivemsg";
			}else{
				$url = "http://kefu.iwide.cn/frontend/web/index.php?r=public/recivemsg";
			}
			
			$msg = array();
			$msg['interid'] = $interid;
			$msg['msgxml']	= file_get_contents("php://input");
			$this->http_post($url,$msg);
			echo "";
			exit();
		}else{
			$msg ['ToUserName']   = $to_user_name;
			$msg ['FromUserName'] = $from_user_name;
			$msg ['CreateTime']   = time();
			$msg ['MsgType']      = 'transfer_customer_service';
			$msg ['FuncFlag']      = 0;
			$xml = new \SimpleXMLElement ( '<xml></xml>' );
			$this->_data2xml ( $xml, $msg );
			$str = $xml->asXML ();
			echo $str;
		}
		*/
		
		//$fp = fopen("show_log.txt", "a");
		//@fwrite($fp, $str,strlen($str));
		
		if(!empty($interid)){
			//要求转到他们自己客服
                        $this->load->config('transfer_customer_service.php');
                        $interIds = $this->config->item('inter_id');
            //恒大亿讯人工客服消息转发
            if(isset($this->data['ToUserName']) && $this->data['ToUserName'] == 'gh_edf5ea3f64a3'){
            	$this->transCusService();
            	exit;
            }
			if(in_array($interid, $interIds)){
				$msg ['ToUserName']   = $to_user_name;
				$msg ['FromUserName'] = $from_user_name;
				$msg ['CreateTime']   = time();
				$msg ['MsgType']      = 'transfer_customer_service';
				$msg ['FuncFlag']      = 0;
				$xml = new \SimpleXMLElement ( '<xml></xml>' );
				$this->_data2xml ( $xml, $msg );
				$str = $xml->asXML ();
				$this->write_log(date('Y-m-d H:i:s').' | '.$_SERVER['REQUEST_URI'].' | '.$str,APPPATH.'logs/wxapi/');
				echo $str;
				
// 				if($interid == 'a450089706' && $to_user_name == 'o9Vbtw9PgJbfM8ia-GJuzq0TTn2k'){
// 					$this->load->model('wx/Access_token_model');
// 					$access_token = $this->Access_token_model->get_access_token('a450089706');
// 					$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
// 					$msg = '{"touser":"'.$to_user_name.'","msgtype":"text","text":{"content":"测试客服消息 | '.time().'"}}';
// 					$this->load->helper('common');
// 					//发送客服消息
// 					doCurlPostRequest($url, $msg);die;
// 				}
				exit();
			}else{
				$url = "";
				if($interid == "d460605501" || $interid == "d460605502"){
					$url = "http://kefutest.iwide.cn/frontend/web/index.php?r=public/recivemsg";
				}else{
					$url = "http://kefu.iwide.cn/frontend/web/index.php?r=public/recivemsg";
				}

				$msg = array();
				$msg['interid'] = $interid;
				$msg['msgxml']	= file_get_contents("php://input");
				//@fwrite($fp, $msg['msgxml'],strlen($msg['msgxml']));
				$this->http_post($url,$msg);
				ob_clean();
				$this->write_log(date('Y-m-d H:i:s').' | '.$_SERVER['REQUEST_URI'].' | SUCCESS ',APPPATH.'logs/wxapi/');
				echo "success";
				exit();
			}
			//@fwrite($fp, "finish",strlen("finish"));
		}else{
			ob_clean();
			echo "success";
			exit();
		}
		
	}
	/* 回复文本消息 */
	public function replyText($content) {
		$msg ['Content'] = $content;
		$this->_replyData ( $msg, 'text' );
	}
	/* 回复图片消息 */
	public function replyImage($media_id) {
		$msg ['Image'] ['MediaId'] = $media_id;
		$this->_replyData ( $msg, 'image' );
	}
	/* 回复语音消息 */
	public function replyVoice($media_id) {
		$msg ['Voice'] ['MediaId'] = $media_id;
		$msg ['Voice'] ['MediaId'] = $media_id;
		$this->_replyData ( $msg, 'voice' );
	}
	/* 回复视频消息 */
	public function replyVideo($media_id, $title = '', $description = '') {
		$msg ['Video'] ['MediaId'] = $media_id;
		$msg ['Video'] ['Title'] = $title;
		$msg ['Video'] ['Description'] = $description;
		$this->_replyData ( $msg, 'video' );
	}
	/* 回复音乐消息 */
	public function replyMusic($media_id, $title = '', $description = '', $music_url, $HQ_music_url) {
		$msg ['Music'] ['ThumbMediaId'] = $media_id;
		$msg ['Music'] ['Title'] = $title;
		$msg ['Music'] ['Description'] = $description;
		$msg ['Music'] ['MusicURL'] = $music_url;
		$msg ['Music'] ['HQMusicUrl'] = $HQ_music_url;
		$this->_replyData ( $msg, 'music' );
	}
	/*
	 * 回复图文消息 articles array 格式如下： array( array('Title'=>'','Description'=>'','PicUrl'=>'','Url'=>''), array('Title'=>'','Description'=>'','PicUrl'=>'','Url'=>'') );
	 */
	public function replyNews($articles) {
		$msg ['ArticleCount'] = count ( $articles );
		$msg ['Articles'] = $articles;
		
		$this->_replyData ( $msg, 'news' );
	}
	/* 发送回复消息到微信平台 */
	private function _replyData($msg, $msgType) {
		$msg ['ToUserName'] = $this->data ['FromUserName'];
		$msg ['FromUserName'] = $this->data ['ToUserName'];
		$msg ['CreateTime'] = time();
		$msg ['MsgType'] = $msgType;
		
		$xml = new \SimpleXMLElement ( '<xml></xml>' );
		$this->_data2xml ( $xml, $msg );
		$str = $xml->asXML ();
		
		// 记录日志
		//addWeixinLog ( $str, '_replyData' );
		
		if (isset($_GET ['encrypt_type']) && $_GET ['encrypt_type'] == 'aes') {
			$sEncryptMsg = ""; // xml格式的密文
			$errCode = $this->wxcpt->EncryptMsg ( $str, $this->sReqTimeStamp, $this->sReqNonce, $sEncryptMsg );
			if ($errCode == 0) {
				$str = $sEncryptMsg;
			} else {
				//addWeixinLog ( $str, "EncryptMsg Error: " . $errCode );
			}
		}
		$this->write_log(date('Y-m-d H:i:s').' | '.$_SERVER['REQUEST_URI'].' | '.$str,APPPATH.'logs/wxapi/');
		echo ($str);
	}

	/* 组装xml数据 */
	public function _data2xml($xml, $data, $item = 'item') {
		foreach ( $data as $key => $value ) {
			is_numeric ( $key ) && ($key = $item);
			if (is_array ( $value ) || is_object ( $value )) {
				$child = $xml->addChild ( $key );
				$this->_data2xml ( $child, $value, $item );
			} else {
				if (is_numeric ( $value )) {
					$child = $xml->addChild ( $key, $value );
				} else {
					$child = $xml->addChild ( $key );
					$node = dom_import_simplexml ( $child );
					$node->appendChild ( $node->ownerDocument->createCDATASection ( $value ) );
				}
			}
		}
	}
	/* ========================发送被动响应消息 end================================== */
	/* 上传多媒体文件 */
	public function uploadFile($file, $type = 'image', $acctoken = '') {
		$post_data ['type'] = $type; // 媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
		$post_data ['media'] = $file;
		
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$acctoken&type=image";
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		ob_start ();
		curl_exec ( $ch );
		$result = ob_get_contents ();
		ob_end_clean ();
		
		return $result;
	}
	/* 下载多媒体文件 */
	public function downloadFile($media_id, $acctoken = '') {
		// TODO
	}
	/**
	 * GET 请求
	 *
	 * @param string $url        	
	 */
	private function http_get($url) {
		$oCurl = curl_init ();
		if (stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	
	/**
	 * POST 请求
	 *
	 * @param string $url        	
	 * @param array $param        	
	 * @return string content
	 */
	private function http_post($url, $param) {
		$oCurl = curl_init ();
		if (stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
		}
		if (is_string ( $param )) {
			$strPOST = $param;
		} else {
			$aPOST = array ();
			foreach ( $param as $key => $val ) {
				$aPOST [] = $key . "=" . urlencode ( $val );
			}
			$strPOST = join ( "&", $aPOST );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $oCurl, CURLOPT_POST, true );
		curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
		$sContent = curl_exec ( $oCurl );
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	
	function write_log($content,$dir_path='') {
		if(is_array($content) || is_object($content))
			$content = json_encode($content);
			$file= date('Y-m-d'). '.txt';
			if(empty($dir_path))
				$dir_path = 'logs/redirect/';
				if( !file_exists($dir_path) ) {
					@mkdir($dir_path, 0777, TRUE);
				}
				$fp = fopen( $dir_path. $file, 'a');
				$content= "\n". $content;
				fwrite($fp, $content);
				fclose($fp);
	}
	
	protected function get_redis_instance(){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis;
	}
	protected function _load_cache( $name='Cache' ){
		if(!$name || $name=='cache')
			$name='Cache';
			$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'api_'), $name );
			return $this->$name;
	}
	
	/**
	 * 恒大亿讯客服消息转发
	 * @return 请求成功返回成功结构，否则返回FALSE
	 */
	protected function transCusService(){
		$this->load->helper('common');
		$this->setCusMsgOn();
// 		echo $this->content;
// 		var_dump( doCurlPostRequest('http://localhost/30wap/index.php/wxapi/rec', $this->content));
// 		echo '------';
// 		var_dump( doCurlPostRequest('http://credit.iwide.cn/index.php/wxapi/rec', $this->content,null,10030));
// 		var_dump( file_get_contents(, false, $this->content));
// 		$ch = curl_init('http://cllweixintest.ngrok.cc/WeiChat/SinoWeixinServer');
// 		$ch = curl_init('https://183.63.190.230/WeiChat/SinoWeixinServer');
		$ch = curl_init('https://hotelservice.evergrande.com/WeiChat/SinoWeixinServer');
// 		$ch = curl_init('http://localhost:8080/tn/TestAction');
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, str_ireplace('<Content><![CDATA[人工客服]]></Content>', '<Content><![CDATA[kf]]></Content>', $this->content) );
		curl_setopt ( $ch, CURLOPT_NOBODY, true);
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		// 	curl_setopt ( $con, CURLOPT_CUSTOMREQUEST, 'HEAD');
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false); // don't check certificate
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false); // don't check certificate
// 		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		
// 		curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/tn/TestAction');
// // 		curl_setopt($ch, CURLOPT_URL, 'http://credit.iwide.cn/index.php/wxapi/rec');
// // 		curl_setopt($ch, CURLOPT_URL, 'http://cllweixintest.ngrok.cc/WeiChat/SinoWeixinServer');
// 		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
// 		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
// 		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->content );
// 		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
		log_message('error', 'TRANS | '.$this->content.' | '.$data.' | '.date('Y-m-d H:i:s').' | '.curl_error($ch));
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return ($httpcode>=200 && $httpcode<300) ? $data : false;
	}
	
	/**
	 * 设置客服消息标识开启
	 * @return unknown|boolean
	 */
	public function setCusMsgOn(){
		if(isset($this->data['FromUserName'])){
			$redis = $this->get_redis_instance();
			return $redis->set($this->data['FromUserName'],'{"timeout":'.time().'}');
		}
		return false;
	}
	
	/**
	 * 取客服消息标识状态
	 * @return unknown
	 */
	protected function getCusMsgStatus($openid = ''){
		$redids = $this->get_redis_instance();
		if(empty($openid))
			$openid = $this->data['FromUserName'];
		$obj = json_decode($redids->get($openid));
		//会话时效15分钟
		return intval($obj && $obj->timeout) > 0 && (time() - intval($obj->timeout) < 90); 
	}
	
	/**
	 * 客服消息状态标识关闭
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function setCusMsgClose($openid){
		if(!empty($openid)){
			$redis = $this->get_redis_instance();
			return $redis->delete($this->data['FromUserName']);
		}
		return false;
	}
	public function sendCustomMsg($inter_id,$content){
		$this->load->model('wx/Access_token_model');
		$access_token = $this->Access_token_model->get_access_token($inter_id);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
// 		$msg = '{"touser":"'.$data['FromUserName'].'","msgtype":"text","text":{"content":"'.$query_auth_code.'_from_api"}}';
		$this->load->helper('common');
		//发送客服消息
		return doCurlPostRequest($url, $content);
	}
}
