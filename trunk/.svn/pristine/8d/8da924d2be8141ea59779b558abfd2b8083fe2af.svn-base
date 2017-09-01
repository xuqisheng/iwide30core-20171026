<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wall extends MY_Front {

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
	var $nickname;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->database();
		$this->maxnum = 2;		
		
		$this->iad = $this->input->get('iad');
		$this->iad = intval($this->iad);
		
		if ( $this->iad < 1 ) {
			echo 'err:iad';
			die();
		}
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		if ( empty ( $this->inter_id ) ) {
			$this->inter_id = $this->session->userdata ( 'inter_id' );
		}
				
		$sql = "SELECT * FROM ".$this->db->dbprefix."fans where inter_id='".$this->inter_id."' and openid='".$this->openid."';";
		
		$this->fans = $this->db->query($sql)->result_array();
		$this->fans = $this->fans[0];
		/*权限接口文件*/
	}
	
	public function index()
	{
		
		$data['fans'] = $this->fans;
		
		$submit = $this->input->get('submit');
		
		if ($submit == 1) {
			$msg = trim($this->input->get('msg'));
			
			if (empty($msg)) {
				die();
			}
			
			$datain['msg'] = $msg;
				
			$datain['addtime'] = time();
			
			$datain['status'] = 1;
			
			$datain['openid'] = $this->openid;
			
			$datain['iad'] = $this->iad;
			
			$datain['inter_id'] = $this->inter_id;
			
			$this->db->insert('chat_wall',$datain);
			
			echo 1;
			
			die();
		}
		
		$act = $this->input->get('act');
		if ($act == 'on') {
				
			die();
		}
		
		$sql = "SELECT * FROM ".$this->db->dbprefix."chat_wall where inter_id='".$this->inter_id."' and iad='".$this->iad."' and openid='".$this->openid."' order by id desc limit 30";
		$chat = $this->db->query($sql)->result_array();

		sort($chat);
		
		$data['chat'] = $chat;
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$this->display('chat/wall.index',$data);
		
	}
	
}
