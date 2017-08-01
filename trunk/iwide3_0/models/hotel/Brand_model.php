<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Brand_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_HOTEL_BRAND = 'hotels_brand';
	protected function _load_db() {
		return $this->db;
	}
	public function get_brands($inter_id, $status = NULL) {
		$db = $this->_load_db ();
		$db->where ( 'inter_id', $inter_id );
		is_null ( $status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		return $db->get ( self::TAB_HOTEL_BRAND )->result_array ();
	}
	public function get_brand($inter_id, $brand_id, $status = NULL) {
		$db = $this->_load_db ();
		$db->where ( array (
				'inter_id' => $inter_id,
				'brand_id' => $brand_id 
		) );
		is_null ( $status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		$db->limit ( 1 );
		return $db->get ( self::TAB_HOTEL_BRAND )->row_array ();
	}
	function get_brand_hotels($inter_id, $brand_id, $status = NULL, $get_brand = FALSE, $nums = NULL, $offset = NULL) {
		$db = $this->_load_db ();
		$db->where ( array (
				'h.inter_id' => $inter_id,
				'h.brand_id' => $brand_id 
		) );
		is_null ( $status ) ? $db->where_in ( 'h.status', array (
				1,
				2 
		) ) : $db->where ( 'h.status', $status );
		is_null ( $nums ) or $db->limit ( $nums, $offset );
		if ($get_brand) {
			$db->select ( 'h.*,b.name brand_name' );
			$db->from ( self::TAB_HOTEL_BRAND . ' b' );
			$db->join ( 'hotels h', 'h.inter_id=b.inter_id and h.brand_id=b.brand_id' );
			return $db->get ()->result_array ();
		}
		return $db->get ( 'hotels' . ' h' )->result_array ();
	}
	function fields_config() {
		$user_operations = array (
				'edit' => array (
						'<a href="',
						'key' => site_url ( 'hotel/brand/edit' ),
						'" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
				) 
		);
		// $acl_array = $this->session->allow_actions;
		// $acl_array = $acl_array [ADMINHTML];
		// foreach ( $user_operations as $oper => $link ) {
		// if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel'] ['coupons'] ) || ! in_array ( $oper, $acl_array ['hotel'] ['coupons'] ))) {
		// unset ( $user_operations [$oper] );
		// }
		// }
		return array (
				'name' => array (
						'label' => '品牌名' 
				),
				'status' => array (
						'label' => '状态',
						'select' => array (
								'1' => '有效',
								'2' => '无效',
								'3' => '删除' 
						) 
				),
				'create_time' => array (
						'label' => '创建时间' 
				),
				'update_time' => array (
						'label' => '最后更新时间' 
				),
				'user_operations' => array (
						'label' => '操作',
						'user_operations' => $user_operations 
				) 
		);
	}
	function table_fields() {
		return array (
				'brand_id' => '',
				'name' => '',
				'status' => 1 
		);
	}
	function update_brand($inter_id, $brand_id, $data, $hotel_ids = array()) {
		$db = $this->_load_db ();
		$db->where ( array (
				'inter_id' => $inter_id,
				'brand_id' => $brand_id 
		) );
		if ($db->update ( self::TAB_HOTEL_BRAND, $data )) {
			if (! empty ( $hotel_ids )) {
				$brand_hotels = $this->get_brand_hotels ( $inter_id, $brand_id );
				$invalid_ids = array ();
				foreach ( $brand_hotels as $h ) {
					if (! in_array ( $h ['hotel_id'], $hotel_ids )) {
						$invalid_ids [] = $h ['hotel_id'];
					}
				}
				if (! empty ( $invalid_ids )) {
					$db->where ( array (
							'inter_id' => $inter_id,
							'brand_id' => $brand_id 
					) );
					$db->where_in ( 'hotel_id', $invalid_ids );
					$db->update ( 'hotels', array (
							'brand_id' => 0 
					) );
				}
				$db->where ( array (
						'inter_id' => $inter_id,
						'brand_id' => 0 
				) );
				$db->where_in ( 'hotel_id', $hotel_ids );
				$db->update ( 'hotels', array (
						'brand_id' => $brand_id 
				) );
				return TRUE;
			} else {
				$db->where ( array (
						'inter_id' => $inter_id,
						'brand_id' => $brand_id 
				) );
				$db->update ( 'hotels', array (
						'brand_id' => 0 
				) );
			}
			return TRUE;
		}
		return FALSE;
	}
	function add_brand($inter_id, $data, $hotel_ids = array()) {
		$db = $this->_load_db ();
		$data ['inter_id'] = $inter_id;
		$data ['create_time'] = date ( 'Y-m-d H:i:s' );
		if ($db->insert ( self::TAB_HOTEL_BRAND, $data )) {
			$brand_id = $db->insert_id ();
			if (! empty ( $hotel_ids )) {
				$db->where ( array (
						'inter_id' => $inter_id,
						'brand_id' => 0 
				) );
				$db->where_in ( 'hotel_id', $hotel_ids );
				$db->update ( 'hotels', array (
						'brand_id' => $brand_id 
				) );
			}
			return TRUE;
		}
		return FALSE;
	}
}