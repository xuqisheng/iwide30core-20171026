<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Front {

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
		
		/**
		 * 验证权限接口
		 * 
		 */
		
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
		
	}
	
	public function index()
	{
		
		echo '';
		
	}
	
	public function login() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$data['appid'] = $this->appid;		
		$this->display('chat/member_login', $data );
	}
	
	public function nologin() {
		$data['appid'] = $this->appid;
		$data['authurl'] = $this->authurl;
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$this->display('chat/member_nologin',$data);;
	}
	
	public function noauth() {
		$data['appid'] = $this->appid;
		$data['authurl'] = $this->authurl;
		$to = $this->input->get('to');
	
		header("location:http://iwidecn.iwide.cn/index.php/wxdata_trans/openid_auth?appid=".$this->appid."&re_url=".urlencode('http://iwide.chat.iwide.cn/index.php/chat/member/icode1?to='.urlencode($to)));
		
	}
	
	public function icode1() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$icode = $this->input->get('icode');
		$to = $this->input->get('to');
		
		$data['appid'] = $this->appid;
		
		$newurl = 'http://iwidecn.iwide.cn/index.php/wxdata_trans/openid_trans?appid='.$data['appid'].'&icode='.$icode;
		
		$ret = qfpost($newurl);
		
		$retjson = json_decode($ret,true);
		
		$openid = $retjson['openid'];
		if (isset($openid)) {
			$newdata = array(
			
					'openid'  => $openid,
			
					'hotelid'  => $this->hotelid,
			
					'nickname'=> substr($openid, 10,6)
			);
			
			$this->session->set_userdata($newdata);
			
			$this->input->set_cookie("openid", $openid, 60*60*24*30);
			
			$this->input->set_cookie("hotelid", $this->hotelid, 60*60*24*30);
			
			echo '<script type="text/javascript">location.href = "'.$to.'";</script>';
		}
		
	}
	
	public function logincheck() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$name = $this->input->post("name",true);
		
		$password = $this->input->post("password",true);
		
		$submit = $this->input->post("submit",true);
		
		
		if (isset($submit)) {
			
			$this->db->where(array('openid'=>$name,'hotelid'=>$this->hotelid));
			
			$this->db->select('nickname');
			
			$query= $this->db->get('chat_member');
			
			$user = $query->result();
			
			if ($user && $user[0]) {

				$userstr = $user[0];
				
				$newdata = array(

						'openid'  => $name,
						
						'hotelid'  => $this->hotelid,
						
						'nickname'=> $userstr->nickname
				);
				
				$this->session->set_userdata($newdata);
				
				$this->input->set_cookie("openid", $name, 60*60*24*30);
				
				$this->input->set_cookie("hotelid", $this->hotelid, 60*60*24*30);

				echo '<script type="text/javascript">location.href = "/";</script>';
				
			}
			else {
				
				echo '<script type="text/javascript">alert("openid不存在！");location.href = "/index.php/chat/member/login";</script>';
			
			}
		}
		
		
	}
	
	public function logout() {

		$newdata = array(

				'username'  => ''
		
		);
		
		$this->session->set_userdata($newdata);
		
		echo '<script type="text/javascript">alert("退出成功！");location.href = "/index.php/chat/member/login";</script>';
	}
}
