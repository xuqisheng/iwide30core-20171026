<?php
class Pms_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_PMS_ADDITION = 'hotel_additions';
	public function check_pms_method($inter_id, $hotel_id, $method) {
		switch ($inter_id) {
			case 'a' :
				$this->get_hotel_pms_set ();
				break;
		}
	}
	public function get_hotel_pms_set($inter_id, $hotel_id) {
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id 
		) );
		return $this->db->get ( self::TAB_PMS_ADDITION )->row_array ();
// 		if (! empty ( $pms_set )) {
// 			$this->load->model ( 'hotel/pms/' . $pms_set ['pms_model_name'] );
// 		}
	}
	// 'hotel_id', 'inter_id', 'name', 'address', 'latitude', 'longitude', 'tel', 'intro', 'short_intro', 'intro_img', 'services', 'email', 'fax', 'star', 'country', 'province', 'web', 'status', 'city', 'sort', 'book_policy'
	//
	function update_pms_hotel($inter_id, $pms_type, $hotels, $addition, $params = array()) {
		$data = array (
				'inter_id' => $inter_id,
				'name' => '',
				'address' => '',
				'latitude' => '',
				'longitude' => '',
				'tel' => '',
				'intro' => '',
				'short_intro' => '',
				'intro_img' => '',
				'services' => '',
				'email' => '',
				'fax' => '',
				'star' => 0,
				'country' => '中国',
				'province' => '',
				'web' => '',
				'status' => 1,
				'city' => '',
				'sort' => 0,
				'book_policy' => '' 
		);
		$hotel_id = empty ( $params ['hotel_id'] ) ? 0 : $params ['hotel_id'];
		$hotels_in = $this->get_hotels_pms_set ( $inter_id, $pms_type, array (), '', 'hotel_web_id' );
		$addition_count = 0;
		$hotel_add_count = 0;
		$hotel_update_count = 0;
		$lowest_count = 0;
		foreach ( $hotels as $web_id => $h ) {
			$tmp = array_merge ( $data, $h );
			if (! empty ( $hotels_in [$web_id] )) {
				if (isset ( $tmp ['hotel_id'] )) {
					unset ( $tmp ['hotel_id'] );
				}
				$this->db->where ( array (
						'inter_id' => $inter_id,
						'hotel_id' => $hotels_in [$web_id] ['hotel_id'] 
				) );
				$this->db->update ( 'hotels', $tmp );
				$tmp ['hotel_id'] = $hotels_in [$web_id] ['hotel_id'];
				$hotel_update_count ++;
			} else {
				if (! empty ( $hotel_id )) {
					$tmp ['hotel_id'] = $hotel_id;
				}
				$this->db->insert ( 'hotels', $tmp );
				if (empty ( $hotel_id )) {
					$tmp ['hotel_id'] = $this->db->insert_id ();
				}
				$hotel_add_count++;
			}
			
			
			if (! empty ( $addition )&& empty ( $hotels_in [$web_id] )) {
				$addition ['hotel_id'] = $tmp ['hotel_id'];
				$addition ['hotel_web_id'] = $web_id;
				$addition ['inter_id'] = $inter_id;
				$addition ['pms_type'] = $pms_type;
				$this->db->insert ( 'hotel_additions', $addition );
				$addition_count ++;
			}
			if (! empty ( $params ['lowest'] [$web_id] )) {
				$this->db->replace ( 'hotel_lowest_price', array (
						'hotel_id' => $tmp ['hotel_id'],
						'inter_id' => $inter_id,
						'lowest_price' => $params ['lowest'] [$web_id],
						'update_time' => date ( 'Y-m-d H:i:s' ) 
				) );
				$lowest_count++;
			}
			if (! empty ( $hotel_id ))
				$hotel_id ++;
		}
		echo 'hotel_add_count:' . $hotel_add_count . '<br />';
		echo 'hotel_update_count:' . $hotel_update_count . '<br />';
		echo 'addition_count:' . $addition_count . '<br />';
		echo 'lowest_count:' . $lowest_count . '<br />';
	}
	
	function update_pms_room(){
		
	}
	
	function get_hotels_pms_set($inter_id, $pms_type = '', $ids = array(), $id_type = 'hotel_id', $key = 'hotel_id') {
		$this->db->where ( 'inter_id', $inter_id );
		$this->db->where ( 'pms_type', $pms_type );
		if (! empty ( $ids ))
			$this->db->where_in ( $id_type, $ids );
		$result = $this->db->get ( self::TAB_PMS_ADDITION )->result_array ();
		$hotels = array ();
		foreach ( $result as $r ) {
			$hotels [$r [$key]] = $r;
		}
		return $hotels;
	}
}