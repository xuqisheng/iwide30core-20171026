<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wxdata extends MY_Front {

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
	 * map to /index.php/welcome/method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	var $nickname;
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->database();
		
		$this->load->library('session');
		
		
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		if ( empty ( $this->inter_id ) ) {
			$this->inter_id = $this->session->userdata ( 'inter_id' );
		}
		
		
		
		$getapp = getapp($this->inter_id,$this->openid, $this->db);
		
		if ($getapp['id']==0) {
			echo 'nohotel';
			die();
		}
		
		
		$this->appid = $getapp['appid'];
		
		$this->authurl = $getapp['authurl'];
		
		$this->hotelid = $getapp['id'];
		
		$this->nickname = $getapp['user']['nickname'];
		
		$this->getapp = $getapp;

		/*权限接口文件*/
	}
	
	public function index()
	{
		
	    if( strpos('<img src="/uploads/201509/qf091643524075.jpg" />', '<img src="')=== false ){
	    	echo '无图';
	    }
		else {
			echo '图片';
		}			
		
	}	
	
	public function getcode() {
		
		$code = isset($_GET['code'])?$_GET['code']:'';
		
		if (!$code) {
			
			echo 'err:code';
			
			die();
			
		}
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$ret = qfpost('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->apret.'&code='.$code.'&grant_type=authorization_code');
		
		$retobj = json_decode($ret);
		
		$openid = isset($retobj->openid)?trim($retobj->openid):'';
		
		$accesstoken = isset($retobj->access_token)?trim($retobj->access_token):'';
		
		$refreshtoken = isset($retobj->refresh_token)?trim($retobj->refresh_token):'';
		
		if (!$accesstoken) {
			echo 'err:accesstoken';
			die();
		}
		
		$this->db->where(array('openid'=>$openid,'hotelid'=>$this->hotelid));
			
		$this->db->select('*');
			
		$query= $this->db->get('chat_member');
			
		$user = $query->result();
		
		if ($user && $user[0]) {
		
			$userstr = $user[0];
		
			$oldtokentime = $userstr->tokentime;
			
			if (time() - $oldtokentime > 3600) {
				
				$refresh2token = qfpost('https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$refreshtoken);
					
				$retobjref = json_decode($refresh2token);
					
				$openid = isset($retobjref->openid)?trim($retobjref->openid):'';
					
				$accesstoken = isset($retobjref->access_token)?trim($retobjref->access_token):'';
					
				$refreshtoken = isset($retobjref->refresh_token)?trim($retobjref->refresh_token):'';
					
				if (!$accesstoken) {
				
					echo '<script type="text/javascript">alert("err:accesstoken");</script>';die();
				
				}
					
				$userinfo = $this->getuserinfo($accesstoken, $openid);
					
				$userdata['logo'] = isset($userinfo->headimgurl)?$userinfo->headimgurl:'';
				
				$userdata['nickname'] = isset($userinfo->nickname)?$userinfo->nickname:'';
				
				$userdata['province'] = isset($userinfo->province)?$userinfo->province:'';
				
				$userdata['area'] = isset($userinfo->city)?$userinfo->city:'';
				
				$userdata['openid'] = $openid;
				
				$userdata['hotelid'] = $this->hotelid;
				
				$userdata['sex'] = isset($userinfo->sex)?$userinfo->sex:'';
				
				$userdata['utoken'] = $accesstoken;
					
				if (!$userdata['nickname']) {
					echo '<script type="text/javascript">alert("err:userdata");</script>';die();
				}
					
				$userdata['uptime'] = time();
				
				$userdata['online'] = 1;
					
				$userdata['tokentime'] = time();
				
				$this->db->update('chat_member',$userdata,array('openid'=>$openid,'hotelid'=>$this->hotelid));

			}
			else {
				
				$userdata['logo'] = $userstr->logo;
				
				$userdata['nickname'] = $userstr->nickname;
				
				$userdata['province'] = $userstr->province;
				
				$userdata['area'] = $userstr->area;
				
				$userdata['openid'] = $userstr->openid;
				
				$userdata['hotelid'] = $this->hotelid;
				
				$userdata['sex'] = $userstr->sex;
				
				$userdata['utoken'] = $userstr->utoken;
				
				$userdata['uptime'] = $userstr->uptime;
				
				$userdata['online'] = $userstr->online;
				
				$userdata['tokentime'] = $userstr->tokentime;
				
			}
		
		}
		else {
			
			$refresh2token = qfpost('https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->appid.'&grant_type=refresh_token&refresh_token='.$refreshtoken);
			
			$retobjref = json_decode($refresh2token);
			
			$openid = isset($retobjref->openid)?trim($retobjref->openid):'';
			
			$accesstoken = isset($retobjref->access_token)?trim($retobjref->access_token):'';
			
			$refreshtoken = isset($retobjref->refresh_token)?trim($retobjref->refresh_token):'';
			
			if (!$accesstoken) {
				
				echo '<script type="text/javascript">alert("err:accesstoken");</script>';die();
				
			}
			
			$userinfo = $this->getuserinfo($accesstoken, $openid);
			
			$userdata['logo'] = isset($userinfo->headimgurl)?$userinfo->headimgurl:'';
			
			$userdata['nickname'] = isset($userinfo->nickname)?$userinfo->nickname:'';
			
			$userdata['province'] = isset($userinfo->province)?$userinfo->province:'';
			
			$userdata['area'] = isset($userinfo->city)?$userinfo->city:'';
			
			$userdata['openid'] = $openid;
			
			$userdata['hotelid'] = $this->hotelid;
			
			$userdata['sex'] = isset($userinfo->sex)?$userinfo->sex:'';
			
			$userdata['utoken'] = $accesstoken;
			
			if (!$userdata['nickname']) {
				
				echo '<script type="text/javascript">alert("err:userdata");</script>';die();
				
			}
					
			$userdata['uptime'] = time();
				
			$userdata['online'] = 1;
			
			$userdata['tokentime'] = time();
				
			$this->db->insert('chat_member',$userdata);
			
		}			
		
		$newdata = array(
					
				'openid'  => $openid,
				
				'hotelid'  => $this->hotelid,
					
				'nickname'=> $userdata['nickname']
		);
			
		$this->session->set_userdata($newdata);
			
		$this->input->set_cookie("openid", $openid, 60*60*24*30);
		
		$this->input->set_cookie("hotelid", $this->hotelid, 60*60*24*30);
		
		$to = isset($_GET['to'])?$_GET['to']:'';
		if ($to) {
			header("location:".$to);
			die();
		}
			
		echo '<script type="text/javascript">location.href = "/index.php/chat/bottle/mainbottle/";</script>';
		

	}
	
	function getuserinfo($accesstoken,$openid) {
		
		$userinfoarr = qfpost('https://api.weixin.qq.com/sns/userinfo?access_token='.$accesstoken.'&openid='.$openid.'&lang=zh_CN');
		
		$userinfo = json_decode($userinfoarr);
		
		if (!$userinfo) {
			
			return false;
			
		}
		
		return $userinfo;
	}
	
	
	public function ticket() {
	
		$getaccesstoken = accesstoken($this->appid, $this->apret, $this->db);
	
		$accesstoken = $getaccesstoken->access_token;
	
		echo json_encode( wxtiket($accesstoken, $this->db) );
	}
	
	/**
	 * 现在正用的接口
	 * 上面老接口将停用或用于测试
	 */
	public function iwidecode() {
	
		$code = isset($_GET['icode'])?$_GET['icode']:'';
	
		if (!$code) {
	
			echo 'err:code';
	
			die();
	
		}
	
		$ret = qfpost('http://'.($this->authurl).'/index.php/wxdata_trans/userinfo_trans?appid='.$this->appid.'&icode='.$code);
	
		$retobj = json_decode(trim($ret,chr(239).chr(187).chr(191)));
	
		$openid = isset($retobj->openid)?trim($retobj->openid):'';
	
		$this->db->where(array('openid'=>$openid,'hotelid'=>$this->hotelid));
			
		$this->db->select('*');
			
		$query= $this->db->get('chat_member');
			
		$user = $query->result();
	
		if ($user && $user[0]) {
	
			$userstr = $user[0];
	
			$userdata['logo'] = isset($retobj->headimgurl)?$retobj->headimgurl:'';
	
			$userdata['nickname'] = isset($retobj->nickname)?$retobj->nickname:'';
	
			$userdata['province'] = isset($retobj->province)?$retobj->province:'';
	
			$userdata['area'] = isset($retobj->city)?$retobj->city:'';
	
			$userdata['openid'] = $openid;
	
			$userdata['hotelid'] = $this->hotelid;
	
			$userdata['sex'] = isset($retobj->sex)?$retobj->sex:'';
	
			$userdata['uptime'] = time();
	
			$userdata['online'] = 1;
				
			$this->db->update('chat_member',$userdata,array('openid'=>$openid,'hotelid'=>$this->hotelid));
	
		}
		else {
				
			$userdata['logo'] = isset($retobj->headimgurl)?$retobj->headimgurl:'';
	
			$userdata['nickname'] = isset($retobj->nickname)?$retobj->nickname:'';
	
			$userdata['province'] = isset($retobj->province)?$retobj->province:'';
	
			$userdata['area'] = isset($retobj->city)?$retobj->city:'';
	
			$userdata['openid'] = $openid;
	
			$userdata['hotelid'] = $this->hotelid;
	
			$userdata['sex'] = isset($retobj->sex)?$retobj->sex:'';
	
	
			if (!$userdata['openid']) {
	
				echo 'noopenid';
	
				die();
	
			}
	
			$userdata['uptime'] = time();
	
			$userdata['online'] = 1;
	
			$userdata['tokentime'] = time();
	
			$this->db->insert('chat_member',$userdata);
	
		}
	
		$newdata = array(
					
				'openid'  => $openid,
				
				'hotelid'  => $this->hotelid,
					
				'nickname'=> $userdata['nickname']
		);
			
		$this->session->set_userdata($newdata);
			
		$this->input->set_cookie("openid", $openid, 60*60*24*30);
		
		$this->input->set_cookie("hotelid", $this->hotelid, 60*60*24*30);
	
		$to = isset($_GET['to'])?$_GET['to']:'';
		if ($to) {
			header("location:".$to);
			die();
		}
			
		echo '<script type="text/javascript">location.href = "/";</script>';
	}
}
