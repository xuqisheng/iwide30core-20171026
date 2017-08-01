<?php
class USER_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_FANS_EXT = 'fans_ext';
	protected function _load_db() {
		return $this->db;
	}
	function get_fans_info($inter_id, $wxapp_openid) {
		$db = $this->load->database('iwide_r1',true);
		$db->limit ( 1 );
		$db->where ( array (
				'inter_id' => $inter_id,
				'wxapp_openid' => $wxapp_openid 
		) );
		return $db->get ( self::TAB_FANS_EXT )->row_array ();
	}
	function save_session_token($inter_id, $session_key, $expire_time, $wxapp_openid) {
		$db = $this->_load_db ();
		$ext = $this->get_fans_info ( $inter_id, $wxapp_openid );
		$token = md5 ( $inter_id . $session_key );
		$session_key_validtime = time () + $expire_time - 60;
		$session_data = array (
				'wxapp_token' => $token,
				'session_key_validtime' => $session_key_validtime,
				'wxapp_sessionkey' => $session_key,
				'inter_id' => $inter_id,
				'wxapp_openid' => $wxapp_openid 
		);
		return array (
				'token' => $token,
				'session_data' => $session_data 
		);
	}
	function save_fans_ext($inter_id, $wxapp_openid, $session_data, $union_id = '') {
		$db = $this->_load_db ();
		$sql = ' select * from ' . $this->db->dbprefix ( self::TAB_FANS_EXT ) . " where inter_id = '$inter_id' ";
		$sql .= empty ( $union_id ) ? " and wxapp_openid='$wxapp_openid' " : " and ( wxapp_openid='$wxapp_openid' or unionid='$union_id')";
		$sql .= ' limit 1';
		$ext = $db->query ( $sql )->row_array ();
		$updata = array ();
		empty ( $session_data ['wxapp_token'] ) ?: $updata ['wxapp_token'] = $session_data ['wxapp_token'];
		empty ( $session_data ['session_key_validtime'] ) ?: $updata ['session_key_validtime'] = $session_data ['session_key_validtime'];
		empty ( $session_data ['wxapp_sessionkey'] ) ?: $updata ['wxapp_sessionkey'] = $session_data ['wxapp_sessionkey'];
		if (! empty ( $ext )) {
			//empty ( $ext ['wxapp_openid'] ) and $updata ['wxapp_openid'] = $wxapp_openid;
			//empty ( $ext ['unionid'] ) and $updata ['unionid'] = $union_id;
			//if()
			$updata ['wxapp_openid'] = $wxapp_openid;
			$updata ['unionid'] = $union_id;
			
			if(!empty ($ext ['unionid']) ){
			    
			    $wx_fans_info = $this->getFansByUnionid($inter_id, $union_id);
			   // MYLOG::w(json_encode($wx_fans_info),"temp_log");
			     
			    if($wx_fans_info){
			        $updata ['openid'] = $wx_fans_info['openid'];
			    }
			    
			}
			
			
			if (! empty ( $updata )) {
				$db->where ( array (
						'id' => $ext ['id'],
						'inter_id' => $inter_id 
				) );
				return $db->update ( self::TAB_FANS_EXT, $updata );
			}
			return TRUE;
		} else {
			$updata ['inter_id'] = $inter_id;
			$updata ['wxapp_openid'] = $wxapp_openid;
			$updata ['unionid'] = $union_id;
			$updata ['iwideid'] = $this->create_iwideid ( $inter_id );
			if(!empty ($ext ['unionid']) ){
			     
			    $wx_fans_info = $this->getFansByUnionid($inter_id, $union_id);
			    if($wx_fans_info){
			        $updata ['openid'] = $wx_fans_info['openid'];
			    }
			     
			}
			return $db->insert ( self::TAB_FANS_EXT, $updata );
		}
		return FALSE;
	}
	
	function app_save_fans_ext($inter_id, $app_openid, $union_id ,$token) {
		$db = $this->_load_db ();
		$sql = ' select * from ' . $this->db->dbprefix ( self::TAB_FANS_EXT ) . " where inter_id = '$inter_id' AND unionid = '{$union_id}' ";
		$sql .= ' limit 1';
		$ext = $db->query ( $sql )->row_array ();
		$updata = array ();
		if (! empty ( $ext )) {
			
			if ($ext['app_openid'] == "" || $ext['app_openid'] == "0" ) {
				
				$updata['app_openid'] = $app_openid;
				//暂与小程序共享token
				$updata['wxapp_token'] = $token;
				$db->where ( array (
						'id' => $ext ['id'],
						'inter_id' => $inter_id,
						
				) );
				return $db->update ( self::TAB_FANS_EXT, $updata );
			}
			return TRUE;
		} else {
			$updata ['inter_id'] = $inter_id;
			$updata ['app_openid'] = $app_openid;
			$updata ['unionid'] = $union_id;
			$updata ['iwideid'] = $this->create_iwideid ( $inter_id );
			//暂与小程序共享token
			$updata['wxapp_token'] = $token;
			return $db->insert ( self::TAB_FANS_EXT, $updata );
		}
		return FALSE;
	}
	
	function getFansByUnionid($inter_id,$unionid){
	    
	    $db = $this->_load_db ();
	    
	    $sql = "
	    SELECT id,openid
	    FROM
	       iwide_fans
	    WHERE
	        inter_id = '{$inter_id}'
	        AND unionid = '{$unionid}'
	    ";
	    
	    $data = $db->query ( $sql )->result_array();
	    
	    if(isset($data[0])){
	        return $data[0];
	    }else{
	        return null;
	    }
	    
	}
	
	function save_union_id($inter_id, $wxapp_openid, $unionid) {
		$db = $this->_load_db ();
		$db->where ( array (
				'inter_id' => $inter_id,
				'wxapp_openid' => $wxapp_openid 
		) );
		return $db->update ( self::TAB_FANS_EXT, array (
				'unionid' => $unionid 
		) );
	}
	
	function get_token_session($inter_id, $token) {
		$db = $this->load->database('iwide_r1',true);
		$db->limit ( 1 );
		$db->where ( array (
				'inter_id' => $inter_id,
				'wxapp_token' => $token 
		) );
		$ext = $db->get ( self::TAB_FANS_EXT )->row_array ();
		if (empty ( $ext ) || $ext ['session_key_validtime'] < time ()) {
			return NULL;
		} else
			return $ext;
	}
	public function create_iwideid($inter_id = '') {
		$uid = uniqid ( "", true );
		$data = '';
		$data .= $_SERVER ['REQUEST_TIME'];
		$data .= $_SERVER ['HTTP_USER_AGENT'];
		$data .= $_SERVER ['REMOTE_ADDR'];
		$data .= $_SERVER ['REMOTE_PORT'];
		$this->load->helper ( 'common' );
		$data .= createNoncestr ();
		$hash = md5 ( $inter_id . $uid . $data );
		$iwide_id = '';
		for($i = 0; $i < 32; $i ++) {
			$iwide_id .= is_numeric ( $hash [$i] ) ? $hash [$i] : chr ( ord ( $hash [$i] ) + (mt_rand ( 0, 100 ) % 21) );
		}
		return $iwide_id;
	}
	public function create_iwideid_for_import_fans($inter_id = '',$openid = "") {
		$uid = uniqid ( "", true );
		$data = '';
		$data .= $_SERVER ['REQUEST_TIME'];
		$data .= $_SERVER ['HTTP_USER_AGENT'];
		$data .= $_SERVER ['REMOTE_ADDR'];
		$data .= $_SERVER ['REMOTE_PORT'];
		$this->load->helper ( 'common' );
		$data .= createNoncestr ();
		$hash = md5 ( $inter_id . $uid . $data.$openid );
		$iwide_id = '';
		for($i = 0; $i < 32; $i ++) {
			$iwide_id .= is_numeric ( $hash [$i] ) ? $hash [$i] : chr ( ord ( $hash [$i] ) + (mt_rand ( 0, 100 ) % 21) );
		}
		return $iwide_id;
	}
	
	
	public function addUnionidToUser($inter_id,$openid,$unionid){
		
		$db = $this->_load_db ();
		
		if($inter_id == "" || $openid == "" || $unionid == "" || $unionid == "null"){
			
			return ;
			
		}
		
		$sql = "
				SELECT COUNT(*) AS num
				FROM
					iwide_".self::TAB_FANS_EXT."
				WHERE
					inter_id = '{$inter_id}'
					AND unionid = '{$unionid}'
				";
		
		$data = $db->query ( $sql )->result_array();
		if( $data[0]['num'] > 0){
			
			$pams = array (
							'openid' => $openid
			);
			$db->where ( array (
							'unionid' => $unionid,
							'inter_id' => $inter_id 
					) );
			$db->update ( self::TAB_FANS_EXT, $pams );
			
			MYLOG::w("UPDATE | {$inter_id} | openid={$openid} | unionid={$unionid}","insert_update_iwide_fans_ext");
			
		}else{
			
			$iwideid = $this->create_iwideid_for_import_fans ( $inter_id ,$openid);

			$pams = array (
					'openid' => $openid,
					'iwideid' => $iwideid,
					'inter_id' => $inter_id,
					'unionid' => $unionid,
					
			);
	
			$db->insert ( self::TAB_FANS_EXT, $pams );
			
			MYLOG::w("UPDATE | {$inter_id} | iwideid={$iwideid} | openid={$openid} | unionid={$unionid}","insert_update_iwide_fans_ext");
			
		}
		
		
		
	}
	
	
	public function addUnionidToUserNoUnionid($inter_id,$openid,$unionid=""){
	
	    $db = $this->_load_db ();
	
	
	    $sql = "
				SELECT COUNT(*) AS num
				FROM
					iwide_".self::TAB_FANS_EXT."
						WHERE
						inter_id = '{$inter_id}'
						AND openid = '{$openid}'
						";
	
	    $data = $db->query ( $sql )->result_array();
	    if( $data[0]['num'] > 0){
	        	
	       return null;
	        	
	    }else{
	        	
	        $iwideid = $this->create_iwideid_for_import_fans ( $inter_id ,$openid);
	
	        $pams = array (
	            'openid' => $openid,
	            'iwideid' => $iwideid,
	            'inter_id' => $inter_id,
	            'unionid' => $unionid,
	            	
	        );
	
	        $db->insert ( self::TAB_FANS_EXT, $pams );
	        

	        MYLOG::w("UPDATE | {$inter_id} | iwideid={$iwideid} | openid={$openid} | unionid={$unionid}","insert_update_iwide_fans_ext");
	         
	        
	        return $iwideid;
	        	
	    }
	
	
	
	}
	
}