<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shake extends MY_Front {

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
	}
	
	public function index()
	{
		$iad = $this->input->get('iad');
		$act = $this->input->get('act');
		
		if (!$iad) {
			echo 'no.id';
			die();
		}
		$this->shake = $this->db->query("select * from ".$this->db->dbprefix."custom_shake where inter_id='".$this->inter_id."' and id='".$iad."'")->result_array();
		if (!$this->shake) {
			echo 'no.shake';
			die();
		}
		$this->shake = $this->shake[0];
		
		
		if ($act) {
			if ($act=='s') {
				if ( $this->shake['dotimes'] > $this->shake['nowtimes'] && $this->shake['isstart'] == 1 ) {
					echo json_encode(array('start'=>1));
					die();
				}
				echo json_encode(array('start'=>0));
				die();
			}
			if ($act=='m') {
		
				$newcount = $this->input->get('newcount');
				$newcount = isset($newcount)?intval($newcount):'';
				if (!$newcount) {
					die();
				}
				$totime = $this->shake['totime'];
				$nowtimes = $this->shake['nowtimes'];
				
				if (time()-$totime>0) {
					//$this->db->update('custom_shake',array('isstart'=>0,'nowtimes'=>$nowtimes+1),array('id'=>$iad));
					die();		
				}
				else {
					$sql = "UPDATE ".$this->db->dbprefix."custom_shake_value SET num=num+".$newcount." WHERE openid='".$this->openid."' and times='".$nowtimes."' and shid=".$iad;
					$this->db->query($sql);
					$affected_rows = $this->db->affected_rows();
					if ($affected_rows == 0) {
						$in_shake_value['logo'] = isset($this->fans['headimgurl'])?$this->fans['headimgurl']:"";
						$in_shake_value['nickname'] = isset($this->fans['nickname'])?$this->fans['nickname']:"";
						$in_shake_value['openid'] = $this->openid;
						$in_shake_value['shid'] = $iad;
						$in_shake_value['times'] = $nowtimes;
						$in_shake_value['sex'] = isset($this->fans['sex'])?$this->fans['sex']:1;
						$in_shake_value['num'] = $newcount;
						$this->db->insert('custom_shake_value',$in_shake_value);
					}
				}
				echo json_encode(array('dotimes'=>$newcount));
				die();
			}
			die();
		}

		$data['inter_id'] = $this->inter_id;
		$data['openid'] = $this->openid;
		$data['fans'] = $this->fans;
		$data['shake'] = $this->shake;
		$this->display('chat/shake.indexmb',$data);
		
	}
	
}
