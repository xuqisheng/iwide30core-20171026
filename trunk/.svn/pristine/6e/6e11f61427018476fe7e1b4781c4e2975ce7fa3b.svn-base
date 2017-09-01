<?php
/**
 * @author OuNianfeng
 * @todo 分销规则模型
 * @since 2016-04-22
 * @version 1.0 Beta
 */
class Rules_model{
	
	public function create_rule($params){
		if(empty($params['inter_id']) || empty($params['excitation_type']) || empty($params['excitation_value']) || empty($params['excitation_category']))
			return false;
		$params = $this->check_data($params, 'distribute_config');
		return $this->_db('iwide_rw')->insert('distribute_config',$params);
	}
	
	public function delete_rule(){
		return false;
	}
	
	public function update_rule($rule_id,$inter_id,$params){
		$params = $this->check_data($params, 'distribute_config');
		$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'id'=>$rule_id));
		return $this->_db('iwide_rw')->update('distribute_config',$params);
	}
	
	public function get_rules_by_id($rule_id,$inter_id = NULL){
		$where['id'] = $rule_id;
		if(!is_null($inter_id))
			$where['inter_id'] = $inter_id;
		$this->_db('iwide_r1')->where($where);
		$this->_db('iwide_r1')->limit(1);
		return $this->_db('iwide_r1')->get('distribute_config')->row();
	}
	
	public function get_public_rules($inter_id){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id));
		return $this->_db('iwide_r1')->get('distribute_config')->result();
	}
	
	private function check_data($params,$table_name){
		$files = $this->_db('iwide_r1')->list_files ( $table_name );
		foreach ( $params as $key => $val ) {
			if (! array_key_exists ( $key, $files ))
				unset ( $params [$key] );
		}
		return $params;
	}
}