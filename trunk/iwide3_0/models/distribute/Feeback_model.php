<?php
/**
 * @author OuNianfeng
 * @package models\distribute
 */
class Feeback_model extends MY_Model{
	
	public function new_feeback($params){
		$dbfields = array_values ( $fields = $this->_db ( 'iwide_r1' )->list_fields ( 'distribute_feebacks' ) );
		foreach ( $params as $k => $v ) {
			if(!in_array($k, $dbfields)){
				unset($params[$k]);
			}
		}
		if(!isset($params['counts'])){
			$params['counts'] = $this->get_feebacks_count($params['inter_id'], $params['openid']);
		}
		if(!isset($params['create_time'])){
			$params['create_time'] = date('Y-m-d H:i:s');
		}
		return $this->_db('iwide_rw')->insert('distribute_feebacks',$params);
	}
	
	/**
	 * 反馈列表
	 * @param array $inter_id
	 * @param array $hotel_id
	 * @param unknown $time_begin
	 * @param unknown $time_end
	 * @param string $name
	 * @param string $key
	 * @param unknown $limit
	 * @param number $offset
	 */
	public function get_feebacks($inter_id = array(),$hotel_id = array(),$time_begin = null,$time_end = null,$name = '',$key = '',$limit = null,$offset = 0,$sort_by='id',$order_by='DESC'){
		$sql    = 'SELECT id,content,inter_id,hotel_id,name,saler,counts,create_time,flag,admin_id,read_time FROM iwide_distribute_feebacks';
		$where  = '';
		$params = array();
		if(!empty($inter_id)){
			if(is_array($inter_id)){
				$where .= ' WHERE inter_id IN ?';
			}else{
				$where .= ' WHERE inter_id=?';
			}
			$params[] = $inter_id;
		}
		if(!empty($hotel_id)){
			if(is_array($hotel_id)){
				$where .= empty($where) ? ' WHERE hotel_id IN ?' : ' AND hotel_id IN ?';
			}else{
				$where .= empty($where) ? ' WHERE hotel_id=?' : ' AND hotel_id=?';
			}
			$params[] = $hotel_id;
		}
		if(!empty($time_begin)){
			$where .= empty($where) ? ' WHERE create_time>=?' : ' AND create_time>=?';
			$params[] = $time_begin;
		}
		if(!empty($time_end)){
			$where .= empty($where) ? ' WHERE create_time<=?' : ' AND create_time<=?';
			$params[] = $time_end;
		}
		if(!empty($name)){
			$where .= empty($where) ? ' WHERE `name` LIKE ?' : ' AND `name` LIKE ?';
			$params[] = "%$name%";
		}
		if(!empty($key)){
			$where .= empty($where) ? ' WHERE content LIKE ?' : ' AND content LIKE ?';
			$params[] = "%$key%";
		}
		$sql .= $where;
		$sql .= " ORDER BY $sort_by $order_by";
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params);
	}
	public function get_feebacks_counts($inter_id = array(),$hotel_id = array(),$time_begin = null,$time_end = null,$name = '',$key = ''){
		$sql    = 'SELECT COUNT(id) counts FROM iwide_distribute_feebacks';
		$where  = '';
		$params = array();
		if(!empty($inter_id)){
			if(is_array($inter_id)){
				$where .= ' WHERE inter_id IN ?';
			}else{
				$where .= ' WHERE inter_id=?';
			}
			$params[] = $inter_id;
		}
		if(!empty($hotel_id)){
			if(is_array($hotel_id)){
				$where .= empty($where) ? ' WHERE hotel_id IN ?' : ' AND hotel_id IN ?';
			}else{
				$where .= empty($where) ? ' WHERE hotel_id=?' : ' AND hotel_id=?';
			}
			$params[] = $hotel_id;
		}
		if(!empty($time_begin)){
			$where .= empty($where) ? ' WHERE create_time>=?' : ' AND create_time>=?';
			$params[] = $time_begin;
		}
		if(!empty($time_end)){
			$where .= empty($where) ? ' WHERE create_time<=?' : ' AND create_time<=?';
			$params[] = $time_end;
		}
		if(!empty($name)){
			$where .= empty($where) ? ' WHERE `name` LIKE ?' : ' AND `name` LIKE ?';
			$params[] = "%$name%";
		}
		if(!empty($key)){
			$where .= empty($where) ? ' WHERE content LIKE ?' : ' AND content LIKE ?';
			$params[] = "%$key%";
		}
		$query = $this->_db('iwide_r1')->query($sql . $where,$params)->row();
		return is_null($query->counts) ? 0 : $query->counts;
	}
	/**
	 * openid反馈次数
	 * @param unknown $inter_id
	 * @param unknown $openid
	 * @return int
	 */
	public function get_feebacks_count($inter_id,$openid){
		$sql = 'SELECT COUNT(id) counts FROM iwide_distribute_feebacks WHERE inter_id=? AND openid=?';
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$openid))->row()->counts;
	}
}
