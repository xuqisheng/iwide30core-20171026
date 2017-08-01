<?php
class Temp_msg_auth_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	function save(){
		$openid = $this->session->userdata($this->session->userdata('inter_id').'openid');
		if($openid){
			$datas['openid']    = $openid;
			$datas['name']      = $this->input->post('name');
			$datas['cellphone'] = $this->input->post('cellphone');
			$datas['inter_id']  = $this->session->userdata('inter_id');
			if($this->input->post('key')){
				
			}else{
				$db = $this->load->database('iwide_r1',true);
				$db->where(array('openid'=>$openid,'inter_id'=>$this->session->userdata('inter_id')));
				$db->limit(1);
				$query = $db->get('temp_msg_auth');
				$datas['status']    = 2;
				if($query->num_rows() > 0){
					return array('errmsg'=>'用户已注册过');
				}
				if($this->db->insert('temp_msg_auth',$datas) > 0){
					return array('errmsg'=>'ok');
				}else{
					return array('errmsg'=>'注册失败');
				}
			}
		}else{
			return array('errmsg'=>'授权错误');	
		}
	}
	function get_config($openid,$inter_id,$status = NULL,$type = NULL){
		$db = $this->load->database('iwide_r1',true);
		$where['openid']   = $openid;
		$where['inter_id'] = $inter_id;
		if(!empty($status)){
			$where['status'] = $status;
		}
		if(!empty($type)){
			$where['type'] = $type;
		}
		$db->where($where);
		$db->limit(1);
		return $db->get('temp_msg_auth')->row_array();
	}
	function get_openids($inter_id,$status = NULL,$type = NULL){
		$db = $this->load->database('iwide_r1',true);
		$where['inter_id'] = $inter_id;
		if(!empty($status)){
			$where['status'] = $status;
		}
		if(!empty($type)){
			$where['type'] = $type;
		}
		$db->where($where);
		return $db->get('temp_msg_auth')->result();
	}
}