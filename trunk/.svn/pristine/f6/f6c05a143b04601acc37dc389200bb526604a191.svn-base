<?php
/**
 * @author NFOU
 * @since 2015-10-10
 */
class Mine_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}

	function create_address($inter_id,$hotel_id = null,$arr){
		if($this->db->insert('shp_address',$arr) > 0){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	function del_address($inter_id,$hotel_id = null,$id,$openid){

	}
	function update_address($arr){
		$this->db->where(array('id'=>$arr['id'],'openid'=>$arr['openid'],'hotel_id'=>$arr['hotel_id'],'inter_id'=>$arr['inter_id']));
		if($this->db->update('shp_address', array('country'=>$arr['country'], 'province'=>$arr['province'],'city'=>$arr['city'],'region'=>$arr['region'],'address'=>$arr['address'],'zip_code'=>$arr['zip_code'],'contact'=>$arr['contact'],'phone'=>$arr['phone'])>0)){
			return true;
		}else{
			return false;
		}
	}
	function get_address($inter_id,$hotel_id = null,$openid){
		$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		if(!empty($hotel_id))
			$this->db->where('hotel_id',$hotel_id);
		return $this->db->get('shp_address')->result_array();
	}
	function get_single_address($inter_id,$hotel_id = null,$openid,$address_id){
		$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid,'id'=>$address_id,'status'=>0));
		if(isset($hotel_id)){
			$this->db->where('hotel_id',$hotel_id);
		}
		$this->db->limit(1);
		return $this->db->get('shp_address')->row_array();
	}
	function rand_single_address($inter_id,$hotel_id = null,$openid){
		$sql = 'SELECT * FROM (SELECT addr_id FROM ' . $this->db->dbprefix ( 'shp_order_items' ) 
		    . ' WHERE openid=? AND NOT ISNULL(addr_id) ORDER BY order_time DESC LIMIT 1) aid LEFT JOIN ' 
		    . $this->db->dbprefix ( 'shp_address' ) . ' a ON a.id=aid.addr_id LIMIT 1';
		$adr = $this->db->query ( $sql, array ( $openid ) )->row_array ();
		if ($adr) {
			return $adr;
		} else {
			$this->db->where ( array ( 'inter_id' => $inter_id, 'openid' => $openid, 'status' => 0 ) );
			if (isset ( $hotel_id )) {
				$this->db->where ( 'hotel_id', $hotel_id );
			}
			$this->db->limit ( 1 );
			return $this->db->get ( 'shp_address' )->row_array ();
		}
	}
	function get_openid_info($inter_id,$openid){
		$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		$this->db->limit(1);
		return $this->db->get('fans')->row_array();
	}
	function get_fans_details($inter_id,$openid){
		$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		$this->db->limit(1);
		return $this->db->get('fans')->row_array();
	}
	function get_fans_details_by_id($inter_id,$fans_id){
		$this->db->where(array('inter_id'=>$inter_id,'id'=>$fans_id));
		$this->db->limit(1);
		return $this->db->get('fans')->row_array();
	}
	
	
}//End of Mine_model class