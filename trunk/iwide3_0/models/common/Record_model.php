<?php
class Record_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * @param Array $array
	 * openid
	 * inter_id
	 * title
	 * url
	 * visit_time
	 * desc
	 */
	function visit_log($array){
		$array['visit_time'] = date ( 'Y-m-d H:i:s' );
		$array['post_data'] = json_encode($_POST);
		return $this->db->insert('visit_record',$array);
	}
	
	function get_list($inter_id = NULL,$length = NULL,$offset = NULL){
		if(!is_null($inter_id))$this->db->where('inter_id',$inter_id);
		if(!is_null($length))$this->db->limit($length);
		if(!is_null($length) && !is_null($offset))$this->db->limit($length,$offset);
		return $this->db->get('visit_record');
	}
}