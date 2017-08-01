<?php
/**
 * 关联管理平台的公众号之间的openid
 * @todo 平台公众号间openid关联管理
 * @author John
 * @since 2016-09-12
 */
class Openid_rel_model extends MY_Model {
	/**
	 * 新建公众号间的关联关系
	 * @todo 新建公众号间的关联关系
	 * @param string $params['m_openid'] 关联的公众号的openid
	 * @param string $params['m_inter_id'] 关联的公众号的编号
	 * @param int $params['fans_id'] 粉丝编号
	 * @param string $params['openid'] 当前公众号openid
	 * @param string $params['inter_id'] 当前公众号编号
	 * @param int $params['status'] 全民分销激活状态
	 * @param string $params['qrcode_url'] 临时二维码链接
	 * @param Datetime $params['qrcode_valid_time'] 临时二维码过期时间
	 * @param Datetime $params['actv_time'] 激活分销时间
	 * @return boolean
	 */
	public function new_rel($params = array()) {
		if (! empty ( $params )) {
			if (empty ( $params ['openid'] ) || empty ( $params ['inter_id'] ) || empty ( $params ['m_openid'] ) || empty ( $params ['m_inter_id'] )) {
				return FALSE;
			} else {
				if ($this->is_relationship_exist ( $params ['inter_id'], $params ['openid'], $params ['m_inter_id'], $params ['m_openid'] )) {
					return $this->update_rel_info ( $params );
				} else {
					if(empty($params['fans_id'])){
						$this->_db('iwide_r1')->where(array('inter_id'=>$params['inter_id'],'openid'=>$params['openid']));
						$this->_db('iwide_r1')->limit(1);
						$query = $this->_db('iwide_r1')->get('fans')->row();
						if(isset($query->fans_key) && !empty($query->fans_key))
							$params['fans_id'] = $query->fans_key;
						else{
							$this->load->model('wx/Fans_key_model');
							$params['fans_id'] = $this->Fans_key_model->get_fans_key();
						}
					}
					$params['create_time'] = date('Y-m-d H:i:s');
					$sql = 'INSERT IGNORE INTO iwide_openid_inter_id_rel ('.implode(',', array_keys($params)).') VALUES (?'.str_repeat(',?', sizeof($params)-1).')';
// 					if($this->_db ( 'iwide_rw' )->insert ( 'openid_inter_id_rel', $params ) ){
					if($this->_db ( 'iwide_rw' )->query ( $sql,  array_values($params)) ){
						$sql = 'INSERT IGNORE INTO iwide_fans (inter_id,openid,fans_key) values (?,?,?)';
						$this->_db('iwide_rw')->query($sql,[$params ['inter_id'],$params ['openid'],$params['fans_id']]);
						return TRUE;
					}else 
						return FALSE;
				}
			}
		} else {
			log_message('error', 'new rel fail');
			return FALSE;
		}
	}
	
	/**
	 * 检查是否已经存在关联关系
	 * @todo 检查是否已经存在关联关系
	 * @param string $inter_id 当前公众号编号
	 * @param string $openid 当前公众号openid
	 * @param string $minter_id 关联的公众号编号
	 * @param string $mopenid 关联的公众号openid
	 * @param boolean $cross_check 是否交叉查询
	 * @return boolean
	 */
	public function is_relationship_exist($inter_id, $openid, $minter_id, $mopenid = '', $cross_check = FALSE) {
		$where = array ( 'openid' => $openid, 'inter_id' => $inter_id, 'm_inter_id' => $minter_id );
		if (! empty ( $mopenid ))
			$where ['m_openid'] = $mopenid;
		$this->_db ( 'iwide_r1' )->where ( $where );
		$res = $this->_db ( 'iwide_r1' )->get ( 'openid_inter_id_rel' );
		if ($cross_check) {
			if ($res->num_rows () > 0)
				return TRUE;
			return $this->is_relationship_exist ( $minter_id, $mopenid, $inter_id, $openid, FALSE );
		} else {
			return $res->num_rows () > 0;
		}
	}
	
	/**
	 * 更新公众号openid关联信息
	 * @param string $params['minter_id'] 关联的公众号的编号
	 * @param string $params['openid'] 当前公众号openid
	 * @param string $params['inter_id'] 当前公众号编号
	 * @param int $params['status'] 全民分销激活状态
	 * @param string $params['qrcode_url'] 临时二维码链接
	 * @param Datetime $params['qrcode_valid_time'] 临时二维码过期时间
	 * @param Datetime $params['actv_time'] 激活分销时间
	 * @return boolean
	 */
	public function update_rel_info($params = array()) {
		$where_params = array (
				'openid'   => $params ['openid'],
				'inter_id' => $params ['inter_id'] 
		);
		if (isset ( $params ['m_inter_id'] ))
			$where_params ['m_inter_id'] = $params ['m_inter_id'];
		$this->_db ( 'iwide_rw' )->where ( $where_params );
		$fields = $this->_db ( 'iwide_rw' )->list_fields ( 'openid_inter_id_rel' );
		if(isset($params ['m_inter_id'])) unset($params ['m_inter_id']);
		foreach ( $params as $k => $v ) {
			if (! in_array ( $k, $fields ))
				unset ( $params [$k] );
		}
		unset ( $params ['openid'] );
		unset ( $params ['inter_id'] );
		unset ( $params ['m_inter_id'] );
		return $this->_db ( 'iwide_rw' )->update ( 'openid_inter_id_rel', $params ) > 0;
	}
	
	/**
	 * 返回一条公众号间openid关联关系，
	 * @param string $inter_id 当前的公众号编号
	 * @param string $openid 当前查询的openid
	 * @param string $minter_id 关联的公众号编号
	 * @return Result-Row
	 */
	public function get_openid_relationship($inter_id, $openid, $minter_id = '',$chk_staff = FALSE) {
// 		$where_params = array (
// 				'inter_id'   => $inter_id,
// 				'openid'     => $openid 
// 		);
// 		if(!empty($minter_id))
// 			$where_params['m_inter_id'] = $minter_id;
// 		$this->_db ( 'iwide_rw' )->where ( $where_params );
// 		$this->_db ( 'iwide_rw' )->limit ( 1 );
		
		$sql = "SELECT r.m_inter_id,r.m_openid,r.`status`,r.qrcode_url,r.qrcode_valid_time,r.actv_time,f.fans_key,f.fans_key saler,f.headimgurl,f.nickname,f.inter_id,f.openid,f.subscribe_time,f.unionid FROM iwide_openid_inter_id_rel r LEFT JOIN iwide_fans f ON r.inter_id=f.inter_id AND r.openid=f.openid WHERE r.inter_id=? AND r.openid=?";
		$params = array($inter_id,$openid);
		if(!empty($minter_id)){
			$sql .= ' AND r.m_inter_id=?';
			$params[] = $minter_id;
		}
		if($chk_staff){
			$sql .= ' AND r.status=?';
			$params[] = 2;
		}
		$sql .= ' LIMIT 1';
		return $this->_db ( 'iwide_r1' )->query ( $sql, $params )->row ();
	}
	protected function _load_cache( $name='Cache' ){
		if(!$name || $name=='cache')
			$name='Cache';
		$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
		return $this->$name;
	}
	public function get_redis_key_status($key = 'CONTINUE_DELIVER'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis->get( $key );
	}
	public function set_redis_key_status($key = 'CONTINUE_DELIVER',$val = 'false'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis->set( $key , $val);
	}
}