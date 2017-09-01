<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msg extends MY_Front {

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
	/**
	 * 心跳执行
	 * @author 清风
	 * @param GET
	 */
	var $openid;
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		if ( empty ( $this->inter_id ) ) {
			$this->inter_id = $this->session->userdata ( 'inter_id' );
		}
		
		if ( empty ( $this->openid ) ) {
			echo 'openid';die();
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
	}
	
	
	function on() {

		//踢线线程 开始
		$chat_config = $this->db->query("select * from ".$this->db->dbprefix."chat_config where id='".$this->chat_id."' and inter_id='".$this->inter_id."'")->result_array();
		if (!$chat_config) {
			die();
		}
		$timeoffline = $chat_config[0]['timeoffline'];
		$timeoffline = intval($timeoffline);
		if (time() - $timeoffline >300 ) {
			$timeout = time()-300;
			$this->db->query("UPDATE ".$this->db->dbprefix."chat_member SET online=0 WHERE inter_id='".$this->inter_id."' and uptime<".$timeout);
			$this->db->update('chat_config',array('timeoffline'=>time()),array('id'=>$this->chat_id));
		}
		//踢线线程 结束
		
		//上线线程 开始
		$this->db->query("UPDATE ".$this->db->dbprefix."chat_member SET online=1,uptime='".time()."' WHERE inter_id='".$this->inter_id."' and openid='".$this->openid."'");
		//上线线程 结束
		
		$ret = qfselect("SELECT newmsg,newbottle FROM ".$this->db->dbprefix."chat_member WHERE inter_id='".$this->inter_id."' and openid='".$this->openid."' LIMIT 1", $this->db);
		if ($ret) {
			echo json_encode($ret['0']);
		}
		else {
			echo '{"newmsg":"0","newbottle":"0"}';
		}
	}
	
	function msg() {
		$mbid = isset($_GET['mbid'])?intval($_GET['mbid']) : 0;
		$mid = isset($_GET['mid'])?intval($_GET['mid']) : 0;
		$ret = qfselect("SELECT * FROM ".$this->db->dbprefix."chat where mbid='".$mbid."' and touid='".$this->openid."' and id>".$mid." order by id asc LIMIT 5",$this->db);
		echo json_encode($ret);
	}
	
	function m() {
		$id = isset($_GET['iad'])?intval($_GET['iad']):'';
		if (!$id) {
			die();
		}
			
		$max = isset($_GET['ma'])?intval($_GET['ma']) : 0;
		$min = isset($_GET['mi'])?intval($_GET['mi']) : '';
			
		if ($min) {
			$sql = "SELECT id,msg,addtime,openid FROM ".$this->db->dbprefix."msg where fid='".$id."' and id<".$min." order by id desc LIMIT 20";
		}
		else {
			if ($max == 0) {
				$sql = "SELECT id,msg,addtime,openid FROM ".$this->db->dbprefix."msg where fid='".$id."' order by id desc LIMIT 30";
			}
			else {
				$sql = "SELECT id,msg,addtime,openid FROM ".$this->db->dbprefix."msg where fid='".$id."' and id>".$max." order by id asc LIMIT 5";
			}
		}
	
		$ret = qfselect($sql,$this->db);
		echo json_encode($ret);
	
	}
}
