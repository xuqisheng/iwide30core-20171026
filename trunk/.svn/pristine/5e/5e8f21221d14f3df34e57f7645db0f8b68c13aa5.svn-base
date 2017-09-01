<?php
class Hotel_config_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_HOTEL_CONFIG = 'hotel_config';
	function get_hotel_config($inter_id, $module, $hotel_id, $type, $params = array()) {
		$arr = array ();
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->order_by ( 'hotel_id asc' );
		$db_read->where_in ( 'hotel_id', array (
				0,
				$hotel_id 
		) );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'module' => $module 
		) );
		if (empty ( $params ['effect'] ) || $params ['effect'] == 1) {
			$db_read->where ( 'priority >', - 1 );
		}
		if (is_array ( $type )) {
			$db_read->where_in ( 'param_name', $type );
			$config = $db_read->get ( self::TAB_HOTEL_CONFIG )->result_array ();
			foreach ( $config as $d ) {
				$arr [$d ['param_name']] = $d ['param_value'];
			}
		} else {
			$db_read->where ( 'param_name', $type );
			$config = $db_read->get ( self::TAB_HOTEL_CONFIG )->row_array ();
			if (! empty ( $config ))
				$arr [$config ['param_name']] = $config ['param_value'];
		}
		return $arr;
	}
	function get_hotels_config($inter_id, $module, $hotel_ids, $type, $params = array()) {
		$arr = array ();
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->order_by ( 'hotel_id asc' );
		if (empty ( $params ['default'] ) || $params ['default'] == 1) {
			$hotel_ids [] = 0;
		}
		$db_read->where_in ( 'hotel_id', $hotel_ids );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'module' => $module 
		) );
		if (empty ( $params ['effect'] ) || $params ['effect'] == 1) {
			$db_read->where ( 'priority >', - 1 );
		}
		is_array ( $type )?$db_read->where_in ( 'param_name', $type ):$db_read->where ( 'param_name', $type );
		$config = $db_read->get ( self::TAB_HOTEL_CONFIG )->result_array ();
		foreach ( $config as $d ) {
			$arr [$d ['hotel_id']] [$d ['param_name']] [$d['id']] = $d ;
		}
		return $arr;
	}

	public function replace_config($param){
		if(!empty($param['id'])){
			$map = [
				'id'       => $param['id'],
				'inter_id' => $param['inter_id'],
				'hotel_id' => $param['hotel_id'],
			];
			$db_read = $this->load->database('iwide_r1',true);
			$check=$db_read->get_where('hotel_config',$map)->row_array();
			unset($param['id']);
			$result=$this->db->update('hotel_config', $param, $map);
			if (array_diff_assoc($param,$check)){
				$this->load->model('hotel/Hotel_log_model');
				$this->Hotel_log_model->add_admin_log('hotel_config#'.$map['id'],'save',NULL,$check,$param,array('id'));
			}
			return $result;
		} else{
			unset($param['id']);
			$this->db->insert('hotel_config', $param);
			$id = $this->db->insert_id();
			$this->load->model('hotel/Hotel_log_model');
			$this->Hotel_log_model->add_admin_log('hotel_config#'.$id,'add',$param);
			return $id;
		}
	}

	public function get_hotels_config_row($inter_id, $module, $hotel_id, $type){
		$this->db->where ( array (
			                   'inter_id' => $inter_id,
			                   'module' => $module,
			                   'hotel_id' => $hotel_id,
			                   'param_name' => $type
		                   ) );
		return $this->db->get ( self::TAB_HOTEL_CONFIG )->row_array ();
	}
}