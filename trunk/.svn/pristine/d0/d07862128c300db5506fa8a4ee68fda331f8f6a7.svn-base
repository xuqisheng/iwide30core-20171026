<?php
class Keyword_record_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	
	function log_record($array){
		return $this->db->insert('keyword_record',$array);
	}
	
	function get_new_record(){
		/* set_time_limit(0);
		$find_new = TRUE;
		//直到有新的数据才返回
		while($find_new){
			sleep(3);
			
		} */
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select('r.*,f.nickname customer_name');
		$db_read->from('keyword_record r');
		$db_read->join('fans f','f.openid=r.openid and f.inter_id=r.inter_id','left');
		$db_read->where(array('r.status'=>0,'r.p_id'=>0));
		// $this->db->where_in('r.inter_id',array('a434678028','a434677894','a431058562'));
		// $this->db->where(array('r.status'=>0,'r.p_id'=>0,'r.inter_id'=>'a429262687'));
		return $db_read->get();
	}
	
	function update_status($ids,$status = 1){
		$this->db->where_in('id',$ids);
		return $this->db->update('keyword_record',array('status'=>$status));
	}
	
	function get_record($record_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where('id',$record_id);
		return $db_read->get('keyword_record');
	}
}//End of keyword_record_model.php