<?php
/**
 * @author John
* 粉丝编号分配
*/
class Fans_key_model extends MY_Model{
	public function get_fans_key(){
		$this->db->insert('fans_key',array('id'=>null));
		return $this->db->insert_id();
	}
}