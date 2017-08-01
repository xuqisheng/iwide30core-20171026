<?php
class Enum_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	function get_enum_des($type, $status = 1, $inter_id = 'defaultdes') {
		$arr = array ();
		$db_read = $this->load->database('iwide_r1',true);
		is_array ( $status ) ? $db_read->where_in ( 'status', $status ) : $db_read->where ( 'status', $status );
		if (empty ( $inter_id ) || $inter_id=='defaultdes') {
			$db_read->where ( array (
					'inter_id' => 'defaultdes' 
			) );
		} else {
			$db_read->order_by ( 'inter_id desc' );
			$db_read->where_in ( 'inter_id', array (
					$inter_id,
					'defaultdes' 
			) );
		}
		if (is_array ( $type )) {
			$db_read->where_in ( 'type', $type );
			$des = $db_read->get ( 'enum_desc' )->result_array ();
			foreach ( $des as $d ) {
				$arr [$d ['type']] [$d ['code']] = $d ['des'];
			}
		} else {
			$db_read->where ( array (
					'type' => $type 
			) );
			$des = $db_read->get ( 'enum_desc' )->result_array ();
			foreach ( $des as $d ) {
				$arr [$d ['code']] = $d ['des'];
			}
		}
		return $arr;
	}
}