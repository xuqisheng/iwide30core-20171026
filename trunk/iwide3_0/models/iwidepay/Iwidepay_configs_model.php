<?php
class Iwidepay_configs_model extends MY_Model{
	const TAB_CONFIGS = 'iwidepay_configs';
	const CONF_UN_SPLIT_DIST = 'un_split_dist';
	const MODULE_IWIDEPAY = 'iwidepay';
	function __construct() {
		parent::__construct ();
	}

	//查出指定类型的有效配置数据
	public function get_configs_by_type($type,$value=1,$module=''){
		$this->db->where(array(
			'type' => $type,
			'value' => $value,
			));
		if(!empty($module)){
			$this->db->where('module',$module);
		}
		return $this->db->get(self::TAB_CONFIGS)->result_array();
	}

	//查出全部不计算分销的号
	public function get_unsplit_configs_by_iwidepay(){
		$this->db->where(array(
			'type' => self::CONF_UN_SPLIT_DIST,
			'value' => 1,
			'module' => self::MODULE_IWIDEPAY,
			));
		$res = $this->db->get(self::TAB_CONFIGS)->result_array();
		$result = array();
		foreach ($res as $key => $value) {
			$result[] = $value['inter_id'];
		}
		return $result;
	}

	//查出部分模块不计算分销的号
	public function get_unsplit_configs_no_iwidepay(){
		$this->db->where(array(
			'type' => self::CONF_UN_SPLIT_DIST,
			'value' => 1,
			'module!=' => self::MODULE_IWIDEPAY,
			));
		$res = $this->db->get(self::TAB_CONFIGS)->result_array();
		$result = array('ids'=>array(),'modules'=>array());
		foreach ($res as $key => $value) {
			$result['ids'][] = $value['inter_id'];
			$result['modules'][] = $value['module'];
		}
		$result['modules'] = array_unique($result['modules']);
		return $result;
	}

	//获取指定inter_id的配置
	public function get_configs_by_interid($inter_id,$format=0,$type='',$module=''){
		$this->db->where(array(
			'inter_id' => $inter_id,
			));
		if(!empty($type)){
			$this->db->where(array(
				'type' => $type,
				));
		}
		if(!empty($module)){
			$this->db->where(array(
				'module' => $module,
				));
		}
		if($format==1){
			return $this->db->get(self::TAB_CONFIGS)->row_array();
		}
		return $this->db->get(self::TAB_CONFIGS)->result_array();
	}

	//获取开启分账的inter_id
	public function get_transfer_inter_id(){
		$sql = "SELECT inter_id FROM iwide_publics WHERE split_status = 1";
		return $this->db->query($sql)->result_array();
	}

}