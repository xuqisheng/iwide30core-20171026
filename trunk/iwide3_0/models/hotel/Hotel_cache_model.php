<?php
class Hotel_cache_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_CACHE = 'hotel_cache';
	function get_cache_data($inter_id, $cache_type) {
		$arr = array ();
		$db = $this->load->database('iwide_r1',true);
		$db->where ( 'inter_id', $inter_id );
		if (is_array ( $cache_type )) {
			$db->where_in ( 'cache_type', $cache_type );
			$cache = $db->get ( self::TAB_CACHE )->result_array ();
			foreach ( $cache as $d ) {
				$arr [$d ['cache_type']] [$d ['ident']] = $d;
			}
		} else {
			$db->where ( 'cache_type', $cache_type );
			$cache = $db->get ( self::TAB_CACHE )->result_array ();
			foreach ( $cache as $d ) {
				$arr [$d ['ident']] = $d;
			}
		}
		return $arr;
	}
	function replace_cache_data($data) {
		return $this->db->replace ( self::TAB_CACHE, $data );
	}
	function get_cache($inter_id, $type, $para = array()) {
		$data = array ();
		$cache_data = $this->get_cache_data ( $inter_id, $type );
		$this->load->model ( 'common/Enum_model' );
		$update_set = $this->Enum_model->get_enum_des ( 'HOTEL_CACHE_UPDATE_INTERVAL', 1, $inter_id );
		if (is_array ( $type )) {
			foreach ( $type as $cache_type ) {
				if (! empty ( $update_set [$cache_type] )) {
					$para ['cache'] = empty ( $cache_data [$cache_type] ) ? array () : $cache_data [$cache_type];
					$para ['update_interval'] = $update_set [$cache_type];
					$data [$cache_type] = $this->update_cache ( $inter_id, $cache_type, $para );
				} else {
					$data [$cache_type] = empty ( $cache_data [$cache_type] ) ? array () : $cache_data [$cache_type];
				}
			}
		} else {
			if (! empty ( $update_set [$type] )) {
				$para ['cache'] = $cache_data;
				$para ['update_interval'] = $update_set [$type];
				$data = $this->update_cache ( $inter_id, $type, $para );
			} else {
				$data = $cache_data;
			}
		}
		return $data;
	}
	function update_cache($inter_id, $type, $params = array()) {
		switch ($type) {
			case 'norder_count' :
				return $this->update_order_num ( $inter_id, $type, $params );
				break;
			case 'hotel_lowest' :
				break;
			case 'comment_data' :
				return $this->update_comment_data ( $inter_id, $type, $params );
				break;
			default :
				break;
		}
	}
	// ident:hotel_id.'_'.status_type 1_normal
	function update_order_num($inter_id, $type, $params = array()) {
		$data = array ();
		$this->load->model ( 'hotel/Order_check_model' );
		foreach ( $params ['hotel_ids'] as $hotel_id ) {
			if (empty ( $params ['cache'] [$hotel_id . '_normal'] ) || ($params ['cache'] [$hotel_id . '_normal'] ['update_time']) + $params ['update_interval'] <= time ()) {
				$tmp = array (
						'ident' => $hotel_id . '_normal',
						'inter_id' => $inter_id,
						'cache_type' => $type,
						'value' => $this->Order_check_model->get_order_status_count ( $inter_id, $hotel_id, 'normal' ),
						'update_time' => time () 
				);
				$this->replace_cache_data ( $tmp );
				$data [$hotel_id] = $tmp;
			} else {
				$data [$hotel_id] = $params ['cache'] [$hotel_id . '_normal'];
			}
		}
		return $data;
	}
	// ident:hotel_id.'_'.status_type 1_valid,value:comment_count,avg_score,score_count,good_rate
	function update_comment_data($inter_id, $type, $params = array()) {
		$data = array ();
		$this->load->model ( 'hotel/Comment_model' );
		foreach ( $params ['hotel_ids'] as $hotel_id ) {
			if (empty ( $params ['cache'] [$hotel_id . '_valid'] ) || ($params ['cache'] [$hotel_id . '_valid'] ['update_time']) + $params ['update_interval'] <= time ()) {
				$comment = $this->Comment_model->get_hotel_comment_count ( $inter_id, $hotel_id, 1,$params );
				$s = empty ( $comment ) ? '0:0:0:-1' : implode ( ':', $comment );
				$tmp = array (
						'ident' => $hotel_id . '_valid',
						'inter_id' => $inter_id,
						'cache_type' => $type,
						'value' => $s,
						'update_time' => time () 
				);
				$this->replace_cache_data ( $tmp );
				$data [$hotel_id] = $tmp;
			} else {
				$data [$hotel_id] = $params ['cache'] [$hotel_id . '_valid'];
			}
		}
		return $data;
	}
	
	function get_ident_cache($inter_id,$ident,$cache_type) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( array (
				'inter_id' => $inter_id,
				'ident' => $ident,
				'cache_type' => $cache_type
		) );
		return $db->get ( 'hotel_cache' )->row_array ();
	}
}