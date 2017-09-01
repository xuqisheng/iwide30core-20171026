<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bottle extends MY_Front {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct() {
		
		parent::__construct();
	
		$this->load->database();
		$this->load->library('session');
		$this->maxnum = 2;
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		if ( empty ( $this->inter_id ) ) {
			$this->inter_id = $this->session->userdata ( 'inter_id' );
		}
		
		if ( empty ( $this->openid ) ) {
			echo 'openid';die();
		}
		
		$this->fans = $this->db->query("select * from ".$this->db->dbprefix."fans where openid='".$this->openid."'")->result_array();
		$this->fans = $this->fans[0];
		
		if (!$this->fans['nickname']) {
			echo '<script>alert("请关注公众号再访问！");</script>';
			die();
		}
		
		//////////////
		$chat_id = $this->input->cookie('chat_id');	
		$chatid = $this->input->get('chatid');
		$chatid = intval($chatid);
		if ($chatid) {
			$this->input->set_cookie('chat_id',$chatid,3600000);
			$chat_id = $chatid;
		}
		
		$this->chat_id = intval($chat_id);
		if (!$this->chat_id) {
			echo '<script>alert("访问地址非法！");</script>';
			die();
		}
		////////////////
		
		
		
		$sql = "SELECT * FROM ".$this->db->dbprefix."chat_member where inter_id='".$this->inter_id."' and openid='".$this->openid."';";
		$chat_member = $this->db->query($sql)->result_array();
		
		if (!$chat_member) {
			$in['openid'] = $this->openid;
			$in['inter_id'] = $this->inter_id;
			$in['logo'] = $this->fans['headimgurl'];
			$in['nickname'] = $this->fans['nickname'];
			$in['province'] = $this->fans['province'];		
			$in['city'] = $this->fans['city'];		
			$in['sex'] = $this->fans['sex'];
			$in['credit'] = 0;
			$in['uptime'] = time();
			$in['online'] = 1;
			$in['newmsg'] = 0;
			$in['newbottle'] = 0;
			
			$this->db->insert('chat_member',$in);
			
			$sql = "SELECT * FROM ".$this->db->dbprefix."chat_member where inter_id='".$this->inter_id."' and openid='".$this->openid."';";
			$chat_member = $this->db->query($sql)->result_array();
		}		
		$this->userinfo =$chat_member['0'];
	}
	
	public function index(){
		
		//http://qingfeng.iwide.cn/index.php/chat/bottle/mainbottle?id=a429262687&openid=hehe&chatid=10011
	}
	
	public function mainbottle() 
	{		
		$chat_config = $this->db->query("select * from ".$this->db->dbprefix."chat_config where id='".$this->chat_id."' and inter_id='".$this->inter_id."'")->result_array();
		if (!$chat_config) {
			echo 'noconfig';
			die();
		}
		
		$data['openid'] = $this->openid;
		$data['inter_id'] = $this->inter_id;
		
		$this->display('chat/show_main',$data);
	} 
	
	public function addbottle() {
		
		$dosubmit = $this->input->post('dosubmit');
		$message = $this->input->post('message');
		$newlogo = $this->input->post('newlogo');
		$nickname = trim($this->input->post('nickname'));
		$messagetime = $this->input->post('messagetime');
		
		if (!$nickname) {
			$nickname = $this->fans['nickname'];
			
		}
		$data['userinfo'] = $this->userinfo;
		
		$uploadfile = $this->input->post('uploadfile');
		if ($dosubmit) {
			
			$lastbottle = md5($message.'|'.$messagetime);
			$lastmsg = $this->input->cookie('qf_lastbottle');
			if ($lastmsg == $lastbottle) {
				echo 'err:msgexist';
				die();
			}

			$datain['openid'] = $this->openid;
			$datain['inter_id'] = $this->inter_id;
			$datain['msg'] = $message;
			$datain['addtime'] = time();
			$datain['countnum'] = 0;			
			$datain['nickname'] = $nickname;			
			$datain['newlogo'] = $newlogo;			
			$datain['sex'] = $data['userinfo']['sex'];
			
			if ( $newlogo != $data['userinfo']['logo'] ) {
				$this->db->update('chat_member',array('logo'=>$newlogo),array('inter_id'=>$this->inter_id,'openid'=>$this->openid));
			}
			if ( $nickname != $data['userinfo']['nickname'] ) {
				$this->db->update('chat_member',array('nickname'=>$nickname),array('inter_id'=>$this->inter_id,'openid'=>$this->openid));
			}
						
			if ( strlen($datain['msg']) < 5 ) {
				echo '<script type="text/javascript">alert("漂流瓶内容不得少于5个字！");</script>';die();
			}
			
			$this->db->insert('chat_bottle',$datain);
			$infoid = $this->db->insert_id();
			
			if ($infoid) {
				if ($uploadfile) {
					$i = 0;
					foreach ($uploadfile as $v) {
						$i += 1;
						if ($i>3) {break;}
						
						$uploaddata['openid'] = $this->openid;						
						$uploaddata['src'] = $v;						
						$uploaddata['infoid'] = $infoid;						
						$this->db->insert('chat_upload',$uploaddata);
					
					}
				}
		
				$this->input->set_cookie('qf_addbottleok','1',3600);
				$this->input->set_cookie('qf_lastbottle',$lastbottle,3600);
				echo '<script type="text/javascript">top.location.href="/index.php/chat/bottle/mainbottle?id='.$this->inter_id.'";</script>';
				die();
			
			}
			
		}
		$this->display('chat/show_addbottle',$data);
	}
	
	public function getbottle() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$act = $this->input->get('act');
		
		$iad = intval($this->input->get('iad'));
		
		if ($act == 'reback') {
			$sql = "UPDATE ".$this->db->dbprefix."chat_mybottle SET status = '0' WHERE bid='".$iad."' and openid='".$this->openid."' limit 1";
			$this->db->query($sql);
			$row = qfselect("SELECT ROW_COUNT() as row;",$this->db) ;
			if ($row['0']['row']>0) {
				$this->db->update('chat_bottle',array('countnum'=>'countnum-1'),array('id'=>$iad));
			}
			$this->input->set_cookie('qf_rebottleok','1',3600);
			echo '<script type="text/javascript">top.location.href="/index.php/chat/bottle/mainbottle/";</script>';die();
			die();
		
		}
		
		if ($act == 'getbottle') {
			$sql = "select * from ".$this->db->dbprefix."chat_bottle where countnum<".$this->maxnum." and inter_id='".($this->inter_id)."' and openid<>'".$this->openid."' and (select count(0) as num from ".$this->db->dbprefix."chat_mybottle where ".$this->db->dbprefix."chat_mybottle.bid = ".$this->db->dbprefix."chat_bottle.id and ".$this->db->dbprefix."chat_mybottle.openid='".$this->openid."') = 0 order by addtime asc limit 1";
			$ret = qfselect($sql,$this->db);
			$data['getbottle'] = array();
			$upload = array();
			
			if ($ret) {				
				$datain['bid'] = $ret[0]['id'];				
				$datain['openid'] = $this->openid;				
				$datain['inter_id'] = $this->inter_id;				
				$datain['fromu'] = $ret[0]['openid'];				
				$datain['msg'] = $ret[0]['msg'];				
				$datain['editetime'] = time();				
				$datain['fnickname'] = $ret[0]['nickname'];				
				$datain['flogo'] = $ret[0]['newlogo'];				
				$datain['fsex'] = $ret[0]['sex'];				
				$this->db->insert('chat_mybottle',$datain);
				$mbid = $this->db->insert_id();
				$countnumnew  = intval($ret[0]['countnum'])+1;
				
				$this->db->update('chat_bottle',array('countnum'=>$countnumnew),array('id'=>$ret[0]['id']));
				$datachat['mbid'] = $mbid;				
				$datachat['msg'] = $ret[0]['msg'];				
				$datachat['addtime'] = time();				
				$datachat['status'] = 1;				
				$datachat['type'] = 0;				
				$datachat['openid'] = $ret[0]['openid'];				
				$this->db->insert('chat',$datachat);
				
				$data['getbottle'] = $ret[0];
				$data['getbottle']['mbid'] = $mbid;
				
				$sql = "SELECT * FROM ".$this->db->dbprefix."chat_upload where infoid='".$data['getbottle']['id']."' limit 3";
				
				$upload = qfselect($sql,$this->db);				
				if ($upload) {					
					foreach ($upload as $v) {						
						$dataimg['mbid'] = $mbid;							
						$dataimg['msg'] = '<img src="'.$v['src'].'" class="preimg" />';							
						$dataimg['addtime'] = time();							
						$dataimg['status'] = 1;							
						$dataimg['type'] = 0;							
						$dataimg['openid'] = $ret[0]['openid'];						
						$this->db->insert('chat',$dataimg);
					}
				}				
				echo json_encode(array('status'=>1,'id'=>$ret[0]['id']));
				die();
				
			}
			else {				
				echo json_encode(array('status'=>0,'id'=>0));
				die();			
			}
	    }
	    
	    if ($iad) {	   
	    	$sql = "SELECT ".$this->db->dbprefix."chat_mybottle.* FROM ".$this->db->dbprefix."chat_bottle,".$this->db->dbprefix."chat_mybottle where ".$this->db->dbprefix."chat_mybottle.bid='".$iad."' and ".$this->db->dbprefix."chat_mybottle.openid='".$this->openid."' and ".$this->db->dbprefix."chat_bottle.id=".$this->db->dbprefix."chat_mybottle.bid limit 1";
	    	
	    	$getbottle = qfselect($sql,$this->db);	    	
	    	if (!$getbottle) {
	    		echo 'err:nobottle';
	    		die();
	    	}	    	
	    	
	    	$data['getbottle'] = $getbottle['0'];	    	
			$sql = "SELECT * FROM ".$this->db->dbprefix."chat_member where inter_id='".$this->inter_id."' and openid='".$data['getbottle']['fromu']."' limit 1";			
			$fromuser = qfselect($sql,$this->db);			
			if (!$fromuser) {				
				echo 'err:3';				
				die();				
			}
			
			$data['fromuser'] = $fromuser['0'];			
			$sql = "SELECT * FROM ".$this->db->dbprefix."chat_upload where infoid='".$data['getbottle']['bid']."' limit 3";			
			$upload = qfselect($sql,$this->db);		
			$data['upload'] = $upload;			
			
			$this->display('chat/show_getbottle',$data);			
	     }	
	}
	
	public function bottle() {
		/**
		 * 我的瓶子
		 * @var array
		 */
		$sql = "SELECT * from ".$this->db->dbprefix."chat_mybottle where inter_id='".($this->inter_id)."' and (openid='".$this->openid."' and status>0) or (fromu='".$this->openid."' and status>1) order by editetime desc limit 100";
		$getbottle = qfselect($sql,$this->db);
		$data['userinfo'] = $this->userinfo;
		$bottle = array();
		foreach ($getbottle as $v) {
			if ($v['openid'] == $this->openid) {			
				$fromuser['nickname'] = $v['fnickname'];
				$fromuser['openid'] = $v['fromu'];
				$fromuser['logo'] = $v['flogo'];
				$fromuser['sex'] = $v['fsex'];
				
			}
			else {
				$sql = "SELECT * from ".$this->db->dbprefix."chat_member where openid='".$v['openid']."' and inter_id='".($this->inter_id)."' limit 1";
				$fromuser1 = qfselect($sql,$this->db);				
				if (!$fromuser1) {
					$fromuser1['0']['nickname']='';
					$fromuser1['0']['openid']='';
					$fromuser1['0']['logo']='';
					$fromuser1['0']['sex']='';
				}
				$fromuser['nickname'] = $fromuser1['0']['nickname'];
				$fromuser['openid'] = $fromuser1['0']['openid'];
				$fromuser['logo'] = $fromuser1['0']['logo'];
				$fromuser['sex'] = $fromuser1['0']['sex'];				
			}			
			$v['from'] = $fromuser;
			$bottle[] = $v;
		}
		
		$data['getbottle'] = $bottle;		
		$this->db->update('chat_member',array('newbottle'=>'0'),array('openid'=>$this->openid,'inter_id'=>$this->inter_id));		
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$this->display('chat/show_bottle',$data);
		
	}
	
	public function message() {		
		echo '您收到一条来自洒店住友的模板消息！快点看看他对你说了些什么？';		
	}
	
	public function chat() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$iad = intval($this->input->get('iad'));		
		$submit = $this->input->get('submit');		
		$isread = $this->input->get('isread');		
		if ($isread) {
			$this->db->update('chat_mybottle',array('status'=>2),array('id'=>intval($isread)));
			die();
		}
		
		if ($submit) {
			$msg = $this->input->get('msg');
			$msg = isset($msg)?trim($msg):'';
			
			if (empty($msg)) {
				die();
			}
			
			$sql = "SELECT * FROM `".$this->db->dbprefix."chat_mybottle` where id='".$iad."' limit 1";
			$mybottle = qfselect($sql,$this->db);
			
			if ($mybottle) {				
				$datain['mbid'] = $iad;			
				$datain['openid'] = $this->openid;				
				if ($mybottle['0']['openid'] == $this->openid) {					
					$touid = $mybottle['0']['fromu'];
					//捡到瓶子的情况
					$status = 5;					
				}
				else {
					$touid = $mybottle['0']['openid'];
					//发出瓶子的情况
					$status = 3;
				}
				
				$this->db->update('chat_member',array('newbottle'=>'1'),array('openid'=>$touid,'inter_id'=>$this->inter_id));
	
				$datain['touid'] = $touid;				
				$datain['addtime'] = time();				
				$datain['msg'] = $msg;				
				$datain['status'] = 1;				
				echo $this->db->insert('chat',$datain);				
				if( strpos($msg, '<img src="')=== false ){}
				else {
					$msg = '[图片]';
				}
				
				$this->db->update('chat_mybottle',array('editetime'=>time(),'status'=>$status,'msg'=>$msg),array('id'=>$iad));				
			}			
			die();			
		}
		
		if (!$iad) {
			echo 'err:id';
			die();
		}
		
		$chat = array();
		$sql = "SELECT * FROM ".$this->db->dbprefix."chat where mbid='".$iad."' order by id desc limit 30";		
		$chat = qfselect($sql,$this->db);		
		sort($chat);		
		$data['chat'] = $chat;		
		$data['userinfo'] = $this->userinfo;
		$data['userinfo']['mbid'] = $iad;		
		$sql = "SELECT * from ".$this->db->dbprefix."chat_mybottle where id='".$iad."' limit 1";
		$getbottle = qfselect($sql,$this->db);
		
		if (!$getbottle) {
			echo 'err:nobottle';
			die();
		}
		
		$data['bottle'] = $getbottle['0'];		
		
		if ($data['bottle']['openid'] == $this->openid) {
			$fromopenid = $data['bottle']['fromu'];
			
		}
		else {
			$fromopenid = $data['bottle']['openid'];
			$fnickname = $data['userinfo']['nickname'];
			$flogo = $data['userinfo']['logo'];
			$fsex = $data['userinfo']['sex'];
		}
		
		$sql = "SELECT * from ".$this->db->dbprefix."chat_member where openid='".$fromopenid."' and inter_id='".($this->inter_id)."' limit 1";
		$fromuser = qfselect($sql,$this->db);
		
		if (!$fromuser) {
			echo 'err:nofrom';
			die();
		}
		
		$data['fromuser'] = $fromuser['0'];
		
		if ($data['bottle']['openid'] == $this->openid) {
			$data['fromuser']['nickname'] = $data['bottle']['fnickname'];
			$data['fromuser']['logo'] = $data['bottle']['flogo'];
			$data['fromuser']['sex'] = $data['bottle']['fsex'];
		}
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$this->display('chat/show_chat',$data);		
	}
	
	function encode_json($str) {		
		return urldecode ( json_encode ( urlencode ( $str ) ) );
		
	}
	
	function url_encode($str) {
		
		if (is_array ( $str )) {			
			foreach ( $str as $key => $value ) {				
				$str [urlencode ( $key )] = urlencode ( $value );				
			}			
		} else {			
			$str = urlencode ( $str );			
		}		
		return $str;
	}	 
}
