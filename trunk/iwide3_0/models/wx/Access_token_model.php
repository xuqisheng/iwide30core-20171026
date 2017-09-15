<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	/**
	 *
	 * @author John
	 * @package models\wx
	 */
	class Access_token_model extends CI_Model {
		function __construct() {
			parent::__construct ();
		}
		const ACCESS_TOKEN = 0;
		const API_TICKET = 1;
		const CARD_TICKET = 2;
		const ADDRESS_TOKEN = 4;


		const PLATFORM_INTER_ID = 'a111111111';
// 			const PLATFORM_INTER_ID = 'a470628023';
		const COMPONENT_VERIFY_TICKET = 10;
		const COMPONENT_ACCESS_TOKEN = 11;
		const PRE_AUTH_CODE = 12;
		const AUTHORIZER_ACCESS_TOKEN = 13;
		const AUTHORIZER_REFRESH_TOKEN = 14;
		const AUTHORIZER_JSAPI_TICKET = 15;
		const SNS_ACCESS_TOKEN = 16;
		const SNS_REFRESH_TOKEN = 17;

		public function get_ticket_redis($inter_id, $type, $return_expire_time = FALSE) {
// 			log_message('debug', 'GET_ACCESS_TOKEN_REDIS | '.$inter_id);
// 			$res = json_decode($this->get_redis_key_status ( $inter_id . $type ),true);
// 			if ($res) {
// 				if($type == self::COMPONENT_VERIFY_TICKET && isset($res['ticket'][0]))$res['ticket'] = $res['ticket'][0];
// 				if (isset ( $res['ticket'] ) && ($res['expire_in'] - time () > 0 || $type == self::COMPONENT_VERIFY_TICKET || $type == self::AUTHORIZER_REFRESH_TOKEN))
// 					return $return_expire_time ? array ( 'ticket' => $res['ticket'], 'expire_in' => $res['expire_in'] ) : $res['ticket'];
// 					else {
// 						return $this->__reflush_ticket ( $inter_id, $type, $return_expire_time );
// 					}
// 			} else {
				return $this->get_ticket_db ( $inter_id, $type, $return_expire_time );
// 			}
// 			return FALSE;
		}

		// DB过渡方案
		public function get_ticket_db($inter_id, $type, $return_expire_time = FALSE) {
			$db_read = $this->load->database('iwide_r1',true);
			$db_read->where ( array ( 'inter_id' => $inter_id, 'type' => $type ) );
			$db_read->limit ( 1 );
			$access_token_query = $db_read->get ( 'access_tokens' )->row ();
			log_message('debug', 'GET_ACCESS_TOKEN_DB | '.$inter_id .' | '.$type.' | '.json_encode($access_token_query));
			if (isset ( $access_token_query->access_token ) && ( $access_token_query->expire - time () > 0 || $type == self::COMPONENT_VERIFY_TICKET || $type == self::AUTHORIZER_REFRESH_TOKEN)){
				log_message('debug', 'RETURN FROM DB | '.$inter_id .' | '.$type.' | ');
				return $return_expire_time ? array ( 'ticket' => $access_token_query->access_token, 'expire_in' => $access_token_query->expire ) : $access_token_query->access_token;
				}else
					return $this->__reflush_ticket ( $inter_id, $type, $return_expire_time );
					return FALSE;
		}
		private function __reflush_ticket($inter_id, $type, $return_expire_time = FALSE) {
			switch ($type) {
				case self::ACCESS_TOKEN :
					return $this->__get_access_token ( $inter_id, $return_expire_time );
					break;
				case self::API_TICKET :
					return $this->__get_jsapi_ticket ( $inter_id, $return_expire_time );
					break;
				case self::CARD_TICKET :
					return $this->__get_card_ticket ( $inter_id, $return_expire_time );
					break;
				case self::ADDRESS_TOKEN :
					return $this->__get_access_token ( $inter_id, $return_expire_time );
					break;
				case self::AUTHORIZER_ACCESS_TOKEN :
					return $this->__get_authorizer_token ( $inter_id, $return_expire_time );
					break;
				case self::COMPONENT_ACCESS_TOKEN :
					return $this->__get_component_access_token ( $inter_id, $return_expire_time );
					break;
				case self::AUTHORIZER_REFRESH_TOKEN :
					$app_info = json_decode($this->get_redis_key_status($inter_id.'_AUTH_INFO'));
					if($app_info)
						return $this->_get_authorizer_access_token( $app_info->AuthorizerAppid, $app_info->AuthorizationCode );
					return null;
					break;
			}
		}
		private function __get_access_token($inter_id, $return_expire_time = FALSE) {
			log_message('debug', 'GET_ACCESS_TOKEN | '.$inter_id);
			$this->load->model ( 'wx/publics_model' );
			$this->load->helper ( 'common' );
			$public_details = $this->publics_model->get_public_by_id ( $inter_id );

			$data = null;
			switch ($inter_id) {
				case 'a453956624' :
					/**
					 * Calling Domo
					 * https://uat.digital.kargocard.com/CHolder/control/token?grant_type=client_credential&appid=wx5f969321cf58a9d5&secret=32e64d96956c200d19524698c3f59bc6&signature=17db36f866eb221a6078190efc3139916f73dcd74cb903d584c9bc7fd3cf24a8cb12d9ce71953ef58d7b60f6eb11b05f9b8f24e562da8e0d561b3e9367a8d40b4c98d9bf85188f200cd70d97b8dd8aa95f3ae55845fc9a642fd296a56ed7610b
					 */
					$base_string = "grant_type=client_credential&appid=" . $public_details['app_id'] . "&secret=" . $public_details['app_secret'];
					$this->load->library ( 'Mall/Lib_kargo' );
					$signature = Lib_kargo::inst ()->encrytion ( $base_string );
					$api_url = Lib_kargo::inst ()->get_token_api_url ();
					$url = $api_url . "?" . $base_string . "&signature=" . $signature;
					$data = doCurlGetRequest ( $url );
					$data = json_decode ( $data );
					break;
				default :
					$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $public_details['app_id'] . "&secret=" . $public_details['app_secret'];
					$data = doCurlGetRequest ( $url );
					log_message ( 'debug', 'GET_ACCESS_TOKEN_RESULT | ' . $inter_id . ' | ' . $public_details['app_id'] . ' | ' . json_encode ( $data ) );
					$data = json_decode ( $data );
					break;
			}
			if (! isset ( $data->access_token )) {
				log_message ( 'error', 'GET_ACCESS_TOKEN_FAILED | ' . $inter_id . ' | ' . $public_details['app_id'] . ' | ' . json_encode ( $data ) );
				return FALSE;
			} else {
				$expire_in = $data->expires_in + time ();
				// set redis
				$this->set_redis_key_status ( $inter_id . self::ACCESS_TOKEN, json_encode ( array ( 'ticket' => $data->access_token, 'expire_in' => $expire_in ) ) );
				// set db
				$this->_replace_token ( $inter_id, $data->access_token, self::ACCESS_TOKEN, $expire_in );
				return $return_expire_time ? array ( 'ticket' => $data->access_token, 'expire_in' => $expire_in ) : $data->access_token;
			}
		}
		/**
		 * 公众号用于调用微信JS接口的临时票据jsapi_ticket
		 */
		private function __get_jsapi_ticket($inter_id, $return_expire_time = FALSE, $continue = TRUE) {
			log_message('debug', 'GET_JSPAI_TICKET | '.$inter_id);
			$this->load->helper ( 'common' );
			$access_token = $this->get_access_token ( $inter_id );
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
			$res = doCurlGetRequest ( $url );
			$data = json_decode ( $res );
			log_message ( 'debug', 'GET_JSAPI_TICKET_RESULT | ' . $inter_id . ' | ' . json_encode ( $data ) );
			if (! isset ( $data->ticket )) {
				if ($continue && isset ( $data->errcode ) && in_array ( $data->errcode, array ( 40001, 40014, 41001, 42001 ) )) {
					// 刷新ACCESS_TOKEN，重新获取ticket
					$this->__get_access_token ( $inter_id );
					return $this->__get_jsapi_ticket ( $inter_id,$return_expire_time, FALSE );
				} else {
					//log_message ( 'error', 'GET_JSAPI_TICKET_FAILED | ' . $inter_id . ' | ' . json_encode ( $data ) );
					return FALSE;
				}
			} else {
				$expire_in = $data->expires_in + time ();
				// set redis
				$this->set_redis_key_status ( $inter_id . self::API_TICKET, json_encode ( array ( 'ticket' => $data->ticket, 'expire_in' => $expire_in ) ) );
				// set db
				$this->_replace_token ( $inter_id, $data->ticket, self::API_TICKET, $expire_in );
				return $return_expire_time ? array ( 'ticket' => $data->ticket, 'expire_in' => $expire_in ) : $data->ticket;
			}
		}
		private function __get_card_ticket($inter_id, $return_expire_time = FALSE, $continue = TRUE) {
			log_message('debug', 'GET_CART_TICKET | '.$inter_id);
			$this->load->helper ( 'common' );
			$access_token = $this->get_access_token ( $inter_id );
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
			$res = doCurlGetRequest ( $url );
			$data = json_decode ( $res );
			if (! isset ( $data->ticket )) {
				if ($continue && isset ( $data->errcode ) && in_array ( $data->errcode, array ( 40001, 40014, 41001, 42001 ) )) {
					// 刷新ACCESS_TOKEN，重新获取ticket
					$this->__get_access_token ( $inter_id );
					return $this->__get_card_ticket ( $inter_id, FALSE );
				} else {
					log_message ( 'error', 'GET_CARD_TICKET_FAILED | ' . $inter_id . ' | ' . json_encode ( $data ) );
					return FALSE;
				}
			} else {
				$expire_in = $data->expire_in + time ();
				// set redis
				$this->set_redis_key_status ( $inter_id . self::CARD_TICKET, json_encode ( array ( 'ticket' => $data->ticket, 'expire_in' => $expire_in ) ) );
				// set db
				$this->_replace_token ( $inter_id, $data->ticket, self::CARD_TICKET, $expire_in );
				return $return_expire_time ? array ( 'ticket' => $data->ticket, 'expire_in' => $expire_in ) : $data->ticket;
			}
		}

		/**
		 * 获取access_token
		 *
		 * @param $inter_id 公众号ID
		 * @return unknown|string
		 */
		function get_access_token($inter_id, $return_expire_time = FALSE) {
			$app_info = $this->get_redis_key_status ( $inter_id . '_AUTH_INFO' );
			$res = $this->get_ticket_redis ( $inter_id, empty ( $app_info ) ? self::ACCESS_TOKEN : self::AUTHORIZER_ACCESS_TOKEN, $return_expire_time );
			return $return_expire_time ? array ( 'access_token' => $res['ticket'], 'expire' => $res['expire_in'] ) : $res;
		}
		function reflash_access_token($inter_id, $return_expire_time = FALSE) {
			$app_info = $this->get_redis_key_status ( $inter_id . '_AUTH_INFO' );
			return $this->__reflush_ticket ( $inter_id, empty ( $app_info ) ? self::ACCESS_TOKEN : self::AUTHORIZER_ACCESS_TOKEN, $return_expire_time );
		}
		/**
		 * 共享收货地址accesstoken获取
		 *
		 * @param unknown $inter_id
		 * @return unknown|string
		 */
		function get_address_token($inter_id) {
			$db_read = $this->load->database('iwide_r1',true);
			$db_read->where ( array ( 'inter_id' => $inter_id, 'type' => self::ADDRESS_TOKEN ) );
			$db_read->limit ( 1 );
			$access_token_query = $db_read->get ( 'access_tokens' );
			if ($access_token_query->num_rows () > 0) {
				$access_token = $access_token_query->row_array ();
				if (time () - $access_token ['expire'] < 7200) {
					return $access_token ['access_token'];
				}
			}
			$this->load->model ( 'wx/publics_model' );
			$public_details = $this->publics_model->get_public_by_id ( $inter_id );
			$appid = $public_details ['app_id'];
			$secret = $public_details ['app_secret'];

			$this->load->helper ( 'common' );
			$data = doCurlGetRequest ( $url );
			$data = json_decode ( $data, true );
			if ($data ['access_token']) {
				$this->_replace_token ( $inter_id, $data ['access_token'], self::ACCESS_TOKEN, time () );
				return $data ['access_token'];
			} else {
				return "error";
			}
		}
		function get_api_ticket($inter_id, $return_expire_time = FALSE) {
			$app_info = $this->get_redis_key_status ( $inter_id . '_AUTH_INFO' );
			$res = $this->get_ticket_redis ( $inter_id, empty ( $app_info ) ? self::API_TICKET : self::AUTHORIZER_JSAPI_TICKET, $return_expire_time );
			return $return_expire_time ? array ( 'ticket' => $res['ticket'], 'expire_in' => $res['expire_in'] ) : $res;
		}
		function get_card_ticket($inter_id, $return_expire_time = FALSE) {
			$res = $this->get_ticket_redis ( $inter_id, self::CARD_TICKET, $return_expire_time );
			return $return_expire_time ? array ( 'ticket' => $res['ticket'], 'expire_in' => $res['expire_in'] ) : $res;
		}
		private function get_ticket($inter_id, $type) {
			$db_read = $this->load->database('iwide_r1',true);
			$res = $this->get_redis_key_status ( $inter_id . $type );
			if ($res) {
				$res = json_decode ( $res );
				if (isset ( $res->expire_in ) && $res->expire_in > time ())
					return $res->ticket;
			}
			$db_read->where ( array ( 'inter_id' => $inter_id, 'type' => $type ) );
			$db_read->limit ( 1 );
			$query = $db_read->get ( 'access_tokens' );
			if ($query->num_rows () > 0) {
				$query = $query->row_array ();
				if (time () - $query ['expire'] < 7200) {
					$this->set_redis_key_status ( $inter_id . $type, json_encode ( array ( 'ticket' => $query ['access_token'], 'expire' => $query ['expire_in'] ) ) );
					return $query ['access_token'];
				}
			}
			$accessToken = $this->get_access_token ( $inter_id );
			$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=wx_card';
			$this->load->helper ( 'common' );
			$res = json_decode ( doCurlGetRequest ( $url ) );
			$ticket = $res->ticket;
			if ($ticket) {
				$this->_replace_token ( $inter_id, $ticket, self::CARD_TICKET, time () );
			}
			return $ticket;
		}
		public function reflash_ticket_force($inter_id, $type = FALSE) {
			if ($type == FALSE)
				$type = self::API_TICKET;
				if ($type == self::API_TICKET) {
					$accessToken = $this->get_access_token ( $inter_id );
					$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
					$this->load->helper ( 'common' );
					$res = json_decode ( doCurlGetRequest ( $url ) );
					$ticket = empty ( $res->ticket ) ? '' : $res->ticket;
					if ($ticket) {
						$this->_replace_token ( $inter_id, $ticket, self::API_TICKET, time () );
					}
					return $ticket;
				}
		}

		public function getSignPackage($inter_id, $url = '') {
			$this->load->helper ( 'common_helper' );
			$this->load->model ( 'wx/Publics_model' );
			$jsapiTicket = $this->get_api_ticket ( $inter_id );
			$protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
			if (! $url)
				$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$timestamp = time ();
				$nonceStr = $this->createNonceStr();
				$public = $this->Publics_model->get_public_by_id ( $inter_id );
				// 这里参数的顺序要按照 key 值 ASCII 码升序排序
				$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

				$signature = sha1 ( $string );

				$signPackage = array (
						"appId" => $public ['app_id'],
						"nonceStr" => $nonceStr,
						"timestamp" => $timestamp,
						"url" => $url,
						"signature" => $signature,
						"rawString" => $string
				);
				return $signPackage;
		}

		public function get_authorizer_jsapi_ticket($inter_id, $component_inter_id = self::PLATFORM_INTER_ID) {
			$component_access_token = $this->get_component_access_token ( $component_inter_id );
		}

		/**
		 * 第三方公众平台component_token
		 *
		 * @param unknown $inter_id
		 * @return unknown|multitype:number unknown |string
		 */
		public function get_component_access_token($inter_id = self::PLATFORM_INTER_ID, $return_expire_time = FALSE) {
			return $this->get_ticket_redis ( $inter_id, self::COMPONENT_ACCESS_TOKEN, $return_expire_time );
		}
		private function __get_component_access_token($inter_id = self::PLATFORM_INTER_ID, $return_expire_time = FALSE) {
			$this->load->model ( 'wx/publics_model' );
			$this->load->helper ( 'common' );
			$public_details = $this->publics_model->get_public_by_id ( $inter_id );

			$req_data ['component_appid'] = $public_details ['app_id'];
			$req_data ['component_appsecret'] = $public_details ['app_secret'];
			
			log_message('debug', '_get_component_verify_ticket | '.$inter_id.' | '.$this->_get_component_verify_ticket ( $inter_id ));
			
			$req_data ['component_verify_ticket'] = $this->_get_component_verify_ticket ( $inter_id );
			$url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";
			$data = doCurlPostRequest ( $url, json_encode ( $req_data ) );
			log_message('error', '__get_component_access_token | '.json_encode($req_data).' | '.$data);
			$data = json_decode ( $data, true );
			$time = time ();
			if (isset ( $data ['component_access_token'] ) && $data ['component_access_token']) {
				$this->set_redis_key_status ( $inter_id . self::COMPONENT_ACCESS_TOKEN, json_encode ( array ( 'ticket' => $data ['component_access_token'], 'expire_in' => $time + intval ( $data ['expires_in'] ) ) ) );
				$this->_replace_token ( $inter_id, $data ['component_access_token'], self::COMPONENT_ACCESS_TOKEN, $time + intval ( $data ['expires_in'] ) );
				return $return_expire_time ? array ( 'access_token' => $data ['component_access_token'], 'expire' => $time + intval ( $data ['expires_in'] ) ) : $data ['component_access_token'];
			} else {
				return FALSE;
			}
		}
		public function get_pre_auth_code($inter_id, $app_id) {
			$this->load->helper ( 'common' );
			$component_acccess_token = $this->get_component_access_token ( $inter_id );
			$res = json_decode ( doCurlPostRequest ( 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $component_acccess_token, json_encode ( array ( 'component_appid' => $app_id ) ) ) );
			// res : eg.{"pre_auth_code":"Cx_Dk6qiBE0Dmx4EmlT3oRfArPvwSQ-oa3NL_fwHM7VI08r52wazoZX2Rhpz1dEw","expires_in":600}
			return $res;
		}
		/**
		 * 公众号第三方平台公众号access_token
		 *
		 * @param string $inter_id
		 * @param boolean $return_expire_time
		 *        	是否返回过期时间
		 * @return unknown|multitype:number unknown |multitype:NULL
		 */
		public function get_authorizer_access_token($inter_id, $return_expire_time = FALSE) {
			return $this->get_ticket_redis ( $inter_id, self::AUTHORIZER_ACCESS_TOKEN, $return_expire_time );
		}
		/**
		 * 取公众号第三方开放平台公众号authorization_access_token
		 *
		 * @param string $inter_id
		 * @param string $authorization_code
		 * @param string $expire_time
		 * @param string $return_expire_time
		 * @return multitype:NULL number |string
		 */
		public function _get_authorizer_access_token($app_id, $authorization_code, $expire_time = NULL, $return_expire_time = FALSE) {
			$this->load->model ( 'wx/publics_model' );
			$this->load->helper ( 'common' );
			// $public_details = $this->publics_model->get_public_by_id ( $inter_id );

			$req_data ['component_appid'] = $app_id;
			$req_data ['authorization_code'] = $authorization_code;
			$component_access_token = $this->get_component_access_token ();
			$url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=$component_access_token";
			$data = doCurlPostRequest ( $url, json_encode ( $req_data ) );
			$this->write_log(date('Y-m-d H:i:s').' | '.json_encode ( $req_data ).' | '.$data);
			$data = json_decode ( $data, true );
			$time = time ();
			if (isset ( $data ['authorization_info'] ['authorizer_access_token'] ) && $data ['authorization_info'] ['authorizer_access_token']) {
					
				// 公众号授权返回时公众号信息更新
				$res = $this->publics_model->get_authorizer_info ( $data ['authorization_info'] ['authorizer_appid'], $app_id, $component_access_token );
				// $this->db->where ( array ( 'app_id' => $data ['authorization_info'] ['authorizer_appid'] ) );
				// $up_params = array ( 'is_authed' => 2 );
				// if (! empty ( $authorization_code ))
				// $up_params ['auth_code'] = $authorization_code;
				// if (! empty ( $expire_time ))
				// $up_params ['auth_expire_time'] = $expire_time;
				// $this->db->update ( 'publics', $up_params );
				// --
					
				// set redis
				$this->set_redis_key_status ( $res->inter_id . self::AUTHORIZER_ACCESS_TOKEN, json_encode ( array ( 'ticket' => $data ['authorization_info'] ['authorizer_access_token'], 'expire_in' => time () + intval ( $data ['authorization_info'] ['expires_in'] ) ) ) );
				// set db
				$this->_replace_token ( $res->inter_id, $data ['authorization_info'] ['authorizer_access_token'], self::AUTHORIZER_ACCESS_TOKEN, $time + intval ( $data ['authorization_info'] ['expires_in'] ) );
				if (isset ( $data ['authorization_info'] ['authorizer_refresh_token'] ) && $data ['authorization_info'] ['authorizer_refresh_token']) {
					// set redis
					$this->set_redis_key_status ( $res->inter_id . self::AUTHORIZER_REFRESH_TOKEN, json_encode ( array ( 'ticket' => $data ['authorization_info'] ['authorizer_refresh_token'], 'expire_in' => time () + intval ( $data ['authorization_info'] ['expires_in'] ) ) ) );
					// set db
					$this->_replace_token ( $res->inter_id, $data ['authorization_info'] ['authorizer_refresh_token'], self::AUTHORIZER_REFRESH_TOKEN, $time + intval ( $data ['authorization_info'] ['expires_in'] ) );
				}
				if (! $return_expire_time) {
					return $data ['authorization_info'] ['authorizer_access_token'];
				} else {
					return array ( 'access_token' => $data ['authorization_info'] ['authorizer_access_token'], 'expire' => $time + $data ['authorization_info'] ['expires_in'], 'inter_id' => $res->inter_id );
				}
			} else {
				return "error";
			}
		}
		/**
		 * 获取（刷新）授权公众号的接口调用凭据（令牌）
		 *
		 * @param unknown $inter_id
		 * @param string $ret_expires_time
		 *        	是否返回过期时间
		 * @return
		 *
		 */
		public function __get_authorizer_token($inter_id, $ret_expires_time = FALSE, $component_inter_id = self::PLATFORM_INTER_ID) {
			$this->load->model ( 'wx/publics_model' );
			$this->load->helper ( 'common' );
			$platform_info = $this->publics_model->get_public_by_id ( $component_inter_id );
			$public_details = $this->publics_model->get_public_by_id ( $inter_id );
			$req ['component_appid'] = $platform_info ['app_id'];
			$req ['authorizer_appid'] = $public_details ['app_id'];
			$req ['authorizer_refresh_token'] = $this->_get_authorizer_refresh_token ( $inter_id );
			$component_access_token = $this->get_component_access_token ();
			log_message('debug', 'GET AUTHORIZER TOKEN PARAMS | '.json_encode($req));
			$data = doCurlPostRequest ( 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=' . $component_access_token, json_encode ( $req ) );

			log_message ( 'debug', $inter_id . '-refresh-access-token-res-' . json_encode ( $data ) );

			$data = json_decode ( $data );
			if (isset ( $data->authorizer_access_token )) {
				// set redis
				$this->set_redis_key_status ( $inter_id . self::AUTHORIZER_ACCESS_TOKEN, json_encode ( array ( 'ticket' => $data->authorizer_access_token, 'expire_in' => time () + intval ( $data->expires_in ) ) ) );
				// set db
				$this->_replace_token ( $inter_id, $data->authorizer_access_token, self::AUTHORIZER_ACCESS_TOKEN, time () + intval ( $data->expires_in ) );
			}
			if (isset ( $data->authorizer_refresh_token )) {
				// set redis
				$this->set_redis_key_status ( $inter_id . self::AUTHORIZER_REFRESH_TOKEN, json_encode ( array ( 'ticket' => $data->authorizer_refresh_token, 'expire_in' => time () + intval ( $data->expires_in ) ) ) );
				// set db
				$this->_replace_token ( $inter_id, $data->authorizer_refresh_token, self::AUTHORIZER_REFRESH_TOKEN, time () + intval ( $data->expires_in ) );
			}
			if (! $ret_expires_time) {
				return $data->authorizer_access_token;
			} else {
				return array ( 'authorizer_access_token' => $data->authorizer_access_token, 'expires_in' => intval ( $data->expires_in ) );
			}
		}
		public function _get_component_verify_ticket($inter_id = self::PLATFORM_INTER_ID) {
			return $this->get_ticket_redis ( $inter_id, self::COMPONENT_VERIFY_TICKET );
		}
		public function _set_component_verify_ticket($component_verify_ticket, $inter_id = self::PLATFORM_INTER_ID) {
			// set redis
			$this->set_redis_key_status ( $inter_id . self::COMPONENT_VERIFY_TICKET, json_encode ( array ( 'ticket' => $component_verify_ticket, 'expire_in' => time () ) ) );
			// set db
			return $this->_replace_token ( $inter_id, $component_verify_ticket, self::COMPONENT_VERIFY_TICKET, time () );
		}
		public function _get_authorizer_refresh_token($inter_id) {
			return $this->get_ticket_redis ( $inter_id, self::AUTHORIZER_REFRESH_TOKEN );
		}
		private function _replace_token($inter_id, $token_val, $type, $expire_in = NULL) {
			return $this->db->replace ( 'access_tokens', array ( 'inter_id' => $inter_id, 'access_token' => $token_val, 'expire' => empty ( $expire_in ) ? $expire_in : time () + 7200, 'type' => $type ) );
		}
		protected function _load_cache($name = 'Cache') {
			if (! $name || $name == 'cache')
				$name = 'Cache';
				$this->load->driver ( 'cache', array ( 'adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'AUTH_TOKEN_' ), $name );
				return $this->$name;
		}
		public function get_redis_key_status($key) {
			if (empty ( $key ))
				return FALSE;
				$cache = $this->_load_cache ();
				$redis = $cache->redis->redis_instance ();
				return $redis->get ( $key );
		}
		public function set_redis_key_status($key, $val = '') {
			$cache = $this->_load_cache ();
			$redis = $cache->redis->redis_instance ();
			return $redis->set ( $key, $val );
		}
		
		function write_log($content) {
			if(is_array($content) || is_object($content))
				$content = json_encode($content);
				$file= date('Y-m-d'). '.txt';
				$dir_path = APPPATH.'logs/redirect/';
				if( !file_exists($dir_path) ) {
					@mkdir($dir_path, 0777, TRUE);
				}
				$fp = fopen( $dir_path. $file, 'a');
				$content= "\n". $content;
				fwrite($fp, $content);
				fclose($fp);
		}

		public function createNoncestr($length = 32){
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
            $str = "";
            for($i = 0; $i < $length; $i++){
                $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            }
            return $str;
        }
	}