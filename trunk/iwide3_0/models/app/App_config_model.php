<?php
class App_config_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_APP_CONFIG = 'app_config';
	function get_hotel_config($inter_id, $type = '', $channel = 'wxapp', $module = 'hotel', $hotel_id = 0, $params = array()) {
		$arr = array ();
		$db_read = $this->load->database ( 'iwide_r1', true );
		$db_read->order_by ( 'hotel_id asc' );
		$db_read->order_by ( 'priority asc' );
		$db_read->where_in ( 'hotel_id', array (
				0,
				$hotel_id 
		) );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'module' => $module,
				'channel' => $channel 
		) );
		if (empty ( $params ['effect'] ) || $params ['effect'] == 1) {
			$db_read->where ( 'priority >', - 1 );
		}
		if ($type) {
			if (is_array ( $type )) {
				$db_read->where_in ( 'type', $type );
			} else {
				$db_read->where ( 'type', $type );
			}
		}
		$config = $db_read->get ( self::TAB_APP_CONFIG )->result_array ();
		foreach ( $config as $d ) {
			$arr [$d ['type']] [$d ['param_name']] = isset ( $params ['column'] ) ? $d : $d ['param_value'];
		}
		return $arr;
	}
	function get_hotels_config($inter_id, $type, $channel = 'wxapp', $module = 'hotel', $hotel_ids, $params = array()) {
		$arr = array ();
		$db_read = $this->load->database ( 'iwide_r1', true );
		$db_read->order_by ( 'hotel_id asc' );
		$db_read->order_by ( 'priority asc' );
		if (empty ( $params ['default'] ) || $params ['default'] == 1) {
			$hotel_ids [] = 0;
		}
		$db_read->where_in ( 'hotel_id', $hotel_ids );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'module' => $module,
				'channel' => $channel 
		) );
		if (empty ( $params ['effect'] ) || $params ['effect'] == 1) {
			$db_read->where ( 'priority >', - 1 );
		}
		is_array ( $type ) ? $db_read->where_in ( 'type', $type ) : $db_read->where ( 'type', $type );
		$config = $db_read->get ( self::TAB_APP_CONFIG )->result_array ();
		foreach ( $config as $d ) {
			$arr [$d ['type']] [$d ['hotel_id']] [$d ['param_name']] [$d ['id']] = $d;
		}
		return $arr;
	}
	public function replace_config($param) {
		$this->load->model('hotel/Hotel_log_model');
		if (! empty ( $param ['id'] )) {
			$map = [ 
					'id' => $param ['id'],
					'inter_id' => $param ['inter_id'],
					'hotel_id' => $param ['hotel_id'] 
			];
			unset ( $param ['id'] );
			$this->db->update ( self::TAB_APP_CONFIG, $param, $map );
			$this->Hotel_log_model->add_admin_log(self::TAB_APP_CONFIG.'#'.$map['id'],'save',$param);
			return true;
		} else {
			unset ( $param ['id'] );
			$this->db->insert ( self::TAB_APP_CONFIG, $param );
			$id = $this->db->insert_id ();
			$this->Hotel_log_model->add_admin_log(self::TAB_APP_CONFIG.'#'.$id,'add',$param);
			return $id;
		}
	}
}