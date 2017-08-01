<?php
/**
 * @author John
 * 粉丝编号分配
 */
class Fans_key_model extends MY_Model{
	public function get_fans_key(){
		$this->db->insert('fans_key',array('id'=>null));
		
		log_message('error', 'GET_FANS_KEY | '.time() .' | '.microtime(TRUE) .' | '.$this->db->insert_id().' | ' . $this->db->last_query());
		return $this->db->insert_id();
	}
} 