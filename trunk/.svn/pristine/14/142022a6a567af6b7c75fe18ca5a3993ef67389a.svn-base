<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Weixin_login {

	var $ci;

	function __construct(){

		$this->ci = &get_instance();

	}

	public function login()
	{

		$data = $this->ci->input->get();
		$this->write_log("Public_oauth:index->get() : " . json_encode($data));

		$this->ci->load->model('wx/Publics_model');
		$public=$this->ci->Publics_model->get_public_by_id($this->ci->input->get('id'));
		if( $this->ci->input->get('id') == "a429262688" ){
		    $_GET['scope'] = "snsapi_userinfo";
		}
		if (! $this->ci->input->get ( 'code' )) {
			//没有code参数，跳转到weixin获取code，再跳回此地址
			$this->auth_get_code($public);
		} else {
			//有code参数，进行数据存储。
			$code = $this->ci->input->get ( 'code' );
			$redirect_uri = urldecode($this->ci->input->get ( 'refer' ));
			$inter_id = $this->ci->input->get('id');
				
			if(strtolower($this->ci->uri->segment(1)) == 'public_oauth'){
				$this->api_auth($public);
			}
			$result = $this->_auth_res($this->ci->input->get ( 'code' ),$this->ci->input->get('id'));
			MYLOG::w(json_encode($result),'public_oauth','_get_openid_');
			$result = json_decode ( $result, TRUE );

			log_message('error', 'public_auth-->get_openid_error-->'.json_encode($result));
			$openid = isset( $result ['openid'] ) ? $result ['openid'] : '';
				
			//如果openid找不到重新授权登录
			if($openid == ""){
				$this->auth_get_code($public);
			}
				
			$this->ci->session->set_userdata ( array ( $this->ci->session->userdata ( 'inter_id' ) . 'openid' => $openid ) );
		
			if( $this->ci->input->get('id') == "a429262688" ){
			    if(file_exists(APPPATH."config/zb_config.php")){
			    //$this->ci->Publics_model->update_wxuser_info ( $inter_id, $result ['openid'], $result ['access_token'] );
			    //如果是直播的公众号就增加fans_ext表
			        include_once APPPATH."config/zb_config.php";
    			    if($inter_id == ZB_INTER_ID){
    			    
    			        $result ['unionid'] = isset($result ['unionid'])?$result ['unionid']:"";
    			        
    			        MYLOG::w("UPDATE | {$inter_id} | openid={$openid} | unionid={$result ['unionid']}","insert_update_iwide_fans_ext","_before_update");
    			        	
    			        $this->ci->load->model('wxapp/User_model');
    			        	
    			        $this->ci->load->model ( 'livebc/Zb_fans_model' );
    			        
    			        $iwideid = $this->ci->User_model->addUnionidToUserNoUnionid($inter_id,$openid,"null");
    			        
    			        if($iwideid != null){
    			            $this->ci->Zb_fans_model->insertFansInfo($iwideid);
    			        }
    			        
    			    
    			    }
			    }
			    
			}
			
			if ($result ['openid']) {
				$accessstoken = null;
				if ($this->ci->input->get ( 'scope' )) {
					$accessstoken = $result ['access_token'];
				}
					
				// 处理月饼说跨域丢失inter_id
				$inter_id = $this->ci->session->userdata ( 'inter_id' );
				if (! $inter_id) {
					$inter_id = $this->ci->input->get ( 'id' );
				}
					
				$this->ci->Publics_model->update_wxuser_info ( $inter_id, $result ['openid'], $accessstoken );
			}
			if(strtolower($this->ci->uri->segment(1)) == 'public_oauth'){

				// 判断域名白名单
				if (! empty ( $public ['white_domains'] )) {
					$this->ci->load->helper ( 'string' );
					$url_domain = get_url_domain ( $redirect_uri );
					$domain = str_replace ( 'http://', '', $public ['domain'] );
					$white_domains = json_decode ( $public ['white_domains'], TRUE );
					if (isset ( $white_domains [$url_domain] ) || $domain == $url_domain) {
						if (in_array ( 'openid', $white_domains [$url_domain] )) {
							if (strpos ( $redirect_uri, '?' ))
								$redirect_uri = $redirect_uri . "&openid=" . $result ['openid'];
							else
								$redirect_uri = $redirect_uri . "?openid=" . $result ['openid'];
						}
						redirect ( $redirect_uri );
					} else {
						echo 'block';
						exit ();
					}
				}

				redirect ( $redirect_uri );
				exit ();
			}
				
			return true;
				
		}
	}

	private function api_auth($public){
		$redirect_uri = urldecode ( $this->ci->input->get ( 'refer' ) );
		$code = $this->ci->input->get('code');
		// 月饼说授权必须需要携带code参数
		if (strpos ( $redirect_uri, 'mooncake_decode_cb' ) !== FALSE) {
			if (strpos ( $redirect_uri, '?' ) !== FALSE) {
				$redirect_uri = $redirect_uri . '&code=' . $code;
			} else {
				$redirect_uri = $redirect_uri . '?code=' . $code;
			}
		}

		// 处理2.0接口跳转授权
		if ($this->ci->input->get ( '__auth20' )) {
			if (strpos ( $redirect_uri, '?' ) !== FALSE) {
				$redirect_uri = $redirect_uri . '&code=' . $code;
			} else {
				$redirect_uri = $redirect_uri . '?code=' . $code;
			}
			redirect ( $redirect_uri );
			exit ();
		}

		// @Editor lGh 判断域名白名单
		$white_domain = NULL;
		if (! empty ( $public ['white_domains'] )) {
			$white_domain = $this->check_white_domain ( $redirect_uri, $public ['domain'], $public ['white_domains'] );
			if ($white_domain === FALSE) {
				echo 'block';
				exit ();
			}
		}
		// @Editor lGh 跳转的域名需要带上code
		if (! empty ( $white_domain ) && in_array ( 'code', $white_domain )) {
			if (strpos ( $redirect_uri, '?' ))
				$redirect_uri = $redirect_uri . "&icode=" . $this->ci->input->get ( 'code' );
				else
					$redirect_uri = $redirect_uri . "?icode=" . $this->ci->input->get ( 'code' );
					redirect ( $redirect_uri );
					exit ();
		}
	}

	private function auth_get_code($public) {
		if (isset ( $_SERVER ['SERVER_SOFTWARE'] ) && $_SERVER ['SERVER_SOFTWARE'] == 'nginx')
			$url = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		else
			$url = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
		
		$scope = 'snsapi_base';
		if (isset($_GET['scope'])) {
			// $scope = 'snsapi_userinfo';
			$scope = $_GET['scope'];
		}
		
		$url = str_replace ( "&code=", "&ncode=", $url );
		
		$url = urlencode ( $url );
		if ($public ['is_authed'] == 2) { // 第三方开放平台授权
			$platform_info=$this->ci->Publics_model->get_public_by_id('a111111111');
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$public ['app_id']}&redirect_uri={$url}&response_type=code&scope={$scope}&state=STATE&component_appid={$platform_info['app_id']}#wechat_redirect";
		} else {
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$public ['app_id']}&redirect_uri={$url}&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
		}
		if($_GET['id'] == 'a429262688'){
		    MYLOG::w($url,'debug-log');
		}
		// $this->mylog("outh_1");
		redirect ( $url );
		exit ();
	}

	/**判断域名白名单
	 * @param string $redirect_uri 要验证的链接
	 * @param string $domain 公众号本身域名
	 * @param string $white_domain 域名白名单
	 */
	protected function check_white_domain($redirect_uri,$domain,$white_domains){
		$this->ci->load->helper('string');
		$url_domain=get_url_domain($redirect_uri);
		$domain = get_url_domain($domain);
		$white_domains=json_decode($white_domains,TRUE);
		if (isset($white_domains[$url_domain])||$domain==$url_domain){
			if (isset($white_domains[$url_domain]))
				return $white_domains[$url_domain];
				else if($domain==$url_domain)
					return array();
		}else {
			return FALSE;
		}
	}

	/**
	 * @param JSON {"itd":"公众号在系统对应的ID","code":"网页授权返回的CODE","noncestr":"32位随机字符串","timestamp":"时间戳","signature":"签名"}
	 * @todo 对外输出接口取网页授权数据
	 * 接受参数类型为JSON：{"itd":"公众号在系统对应的ID","code":"网页授权返回的CODE","noncestr":"32位随机字符串","timestamp":"时间戳","signature":"签名"}
	 * 签名方法跟微信的签名算法一致，即对所有待签名参数按照字段名的ASCII 码从小到大排序（字典序）后，
	 * 使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串string1（参数名均为小写字符），
	 * 然后在加上双方约定的秘钥key即（即key1=value1&key2=value2…&key=秘钥）拼接成string2,
	 * 对string2作sha1加密，字段名和字段值都采用原始值，不进行URL 转义对string2进行sha1签名得到最终签名signature
	 * @version 1.0 bata
	 * @author ounianfeng
	 * @since 2016-01-15
	 * @return JSON 微信网页授权返回的原始数据
	 */
	public function auth_res()
	{
		$source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		if ($source) {
			if (! isset ( $source ['signature'] )) {
				echo '{"errmsg":"Invalid Signature"}';
				exit ();
			}
			$sign = $source ['signature'];
			unset ( $source ['signature'] );
			$this->ci->load->model ( 'api/signiture_model' );
			$this->ci->load->model ( 'api/common_model' );
			$token = $this->ci->common_model->get_inter_id_token ( $source ['itd'] );
			if (empty ( $token )) {
				echo '{"errmsg":"Invalid Parameter\"itd\""}';
				exit ();
			}
			$signature = $this->ci->signiture_model->get_sign ( $source, $token );
			if ($sign != $signature) {
				echo '{"errmsg":"Signiture error"}';
				exit ();
			}
			$result = $this->_auth_res ($source ['code'],$source ['itd']);;
			echo $result;
		} else {
			echo '{"error":"-1"}';
		}
	}

	/**
	 * 网页授权通过code获取用户信息
	 * @param String code
	 * @param String 公众号识别码
	 * @return JSON 请求微信返回结果
	 */
	private function _auth_res($code,$inter_id)
	{
		$this->ci->load->model('wx/Publics_model');
		$public=$this->ci->Publics_model->get_public_by_id($inter_id);
		if($public['is_authed'] == 2){
			$platform_info = $this->ci->Publics_model->get_public_by_id('a111111111');
			$this->ci->load->model('wx/access_token_model');
			$component_access_token = $this->ci->access_token_model->get_component_access_token();
			$url = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$public['app_id']}&code={$code}&grant_type=authorization_code&component_appid={$platform_info['app_id']}&component_access_token={$component_access_token}";
		}else{
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $public ['app_id'] . "&secret=" . $public ['app_secret'] . "&code=$code&grant_type=authorization_code";
		}

		//$this->mylog($url);


		$this->ci->load->helper('common');

		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		$this->write_log($res);
		//取出openid
		return $res;

		/* $test = doCurlGetRequest($url);
		 $this->mylog($test);
		 return $test; */
	}



	//日志写入
	public function write_log( $content )
	{
		$file= date('Y-m-d'). '.txt';
		//echo $tmpfile;die;
		$path= APPPATH.'logs'.DS. 'public_oauth'. DS;
		if( !file_exists($path) ) {
			@mkdir($path, 0777, TRUE);
		}
		$fp = fopen( $path. $file, 'a');

		$CI = & get_instance();
		$ip= $CI->input->ip_address();
		$content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
				."\n". $ip. "\n". $content. "\n";
				fwrite($fp, $content);
				fclose($fp);
	}

	public function mylog($str){

		//, session id :".$_COOKIE['PHPSESSID']."  ".date("Y-m-d H:i:s")."  ".microtime()

		$user_IP = $_SERVER["REMOTE_ADDR"];

		$fp = fopen("all.csv", "a");

		$arr = explode(" ", microtime());

		$str  = "\r\n".date("Y-m-d H:i:s").",".$arr[0].",".session_id().",".$_SERVER['HTTP_USER_AGENT'].",".$user_IP.",".$str;

		@fwrite($fp, $str,strlen($str));

		fclose($fp);

		if(isset( $_GET['debuglog'] ) ){
				
			$user_IP = $_SERVER["REMOTE_ADDR"];
				
			$fp = fopen("debug.csv", "a");
				
			$arr = explode(" ", microtime());
				
			$str  = "\r\n".date("Y-m-d H:i:s").",".$arr[0].",".session_id().",".$_SERVER['HTTP_USER_AGENT'].",".$user_IP.",".$str;
				
			@fwrite($fp, $str,strlen($str));
				
			fclose($fp);
				
		}


	}

}
