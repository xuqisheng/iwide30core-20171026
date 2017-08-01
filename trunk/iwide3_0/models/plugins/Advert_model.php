<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Advert_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_ADS = 'hotels_ads';
	const TAB_AD_AREAS = 'hotels_ad_areas';
	const TAB_ENUM = 'enum_desc';
	const ENUM_HOTEL_AD_AREA = 'HOTEL_AD_AREA_DES';
	protected function _load_db($type='main') {
		switch ($type){
			case 'read':
				if (!isset($this->_read_db)){
					$this->_read_db=$this->load->database('iwide_r1',true);
				}
				return $this->_read_db;
				break;
			default:
				return $this->db;
				break;
		}
	}
	public function get_module_type_name($module) {
		$area_type = '';
		switch ($module) {
			case 'hotel' :
				$area_type = self::ENUM_HOTEL_AD_AREA;
				break;
			default :
				break;
		}
		return $area_type;
	}
	public function get_ad_area_des($module) {
		$area_type = $this->get_module_type_name ( $module );
		$this->load->model ( 'common/Enum_model' );
		return $this->Enum_model->get_enum_des ( $area_type );
	}
	/**获取特定区域广告
	 * @param unknown $inter_id
	 * @param number $hotel_id
	 * @param unknown $area_type
	 * @param unknown $status
	 */
	public function get_hotel_ads($inter_id, $hotel_id = 0, $area_type, $status = null, $ad_status = null) {
		$db = $this->_load_db ('read');
		$db->order_by ( 'hotel_id desc' );
		$db->where ( array (
				'inter_id' => $inter_id,
				'area_type' => $area_type 
		) );
		$in = array (
				'0' 
		);
		if (! empty ( $hotel_id )) {
			$in [] = intval ( $hotel_id );
		}
		$db->where_in ( 'hotel_id', $in );
		is_null ( $status ) || $db->where ( 'status', $status );
		$result = $db->get ( self::TAB_AD_AREAS )->result_array ();
		$data = array ();
		if (! empty ( $result )) {
			$data ['title'] = $result [0] ['area_title'];
			$ids = $result [0] ['ads_ids'];
			$first_ad = explode ( ',', $result [0] ['ads_ids'] );
			$extra_ad = array ();
			if (count ( $result ) == 2) {
				if ($status == 1) {
					if ($result [0] ['status'] != $status)
						return array ();
					if ($result [1] ['status'] != $status)
						unset ( $result [1] );
				}
				if ($result [0] ['coexist'] != 0) {
					$ids .= ',' . $result [1] ['ads_ids'];
					$extra_ad = explode ( ',', $result [1] ['ads_ids'] );
				}
			}
			if (! empty ( $ids )) {
				$ads_result = $this->get_ad_by_ids ( $inter_id, 0, $ids, $ad_status );
				$ads = array ();
				$data ['ads'] ['public'] = array ();
				$data ['ads'] ['hotel'] = array ();
				foreach ( $ads_result as $a ) {
					$ads [$a ['id']] = $a;
				}
				if ($result [0] ['hotel_id'] > 0) {
					if (! empty ( $extra_ad )) {
						foreach ( $extra_ad as $ea ) {
							if (! empty ( $ads [$ea] )) {
								$data ['ads'] ['public'] [] = $ads [$ea];
							}
						}
					}
					foreach ( $first_ad as $fa ) {
						if (! empty ( $ads [$fa] )) {
							$data ['ads'] ['hotel'] [] = $ads [$fa];
						}
					}
					if ($result [0] ['coexist'] == 2) {
						asort ( $data ['ads'] ,SORT_NUMERIC);
					}
				} else {
					foreach ( $first_ad as $fa ) {
						if (! empty ( $ads [$fa] )) {
							$data ['ads'] ['public'] [] = $ads [$fa];
						}
					}
				}
				if ($hotel_id == 0) {
					$data ['ads'] ['hotel'] = $data ['ads'] ['public'];
					unset ( $data ['ads'] ['public'] );
				}
			}
		}
		return $data;
	}
	public function get_ad_by_ids($inter_id, $hotel_id = 0, $ids = null, $ad_status = null ,$type='') {
		$db = $this->_load_db ('read');
		$hotel_id = 0;
		$db->where ( 'inter_id', $inter_id );
		$db->where ( 'hotel_id', $hotel_id );
		is_null ( $ad_status ) ? $db->where_in ( 'status', array (
				1,
				2 
		) ) : $db->where ( 'status', $ad_status );
		if (! is_null ( $ids )) {
			$ids = explode ( ',', $ids );
			$db->where_in ( 'id', $ids );
		}
		if($type=='index_middle'){
			$db->like('ad_img', '&#', 'after');
		}
		if($type=='index_foot' || $type=='search_foot'){
			$db->like('ad_img', 'http', 'after');
		}
		return $db->get ( self::TAB_ADS )->result_array ();
	}
	function get_ad_area_list($inter_id, $module, $hotel_id = 0, $code = '') {
		$db = $this->_load_db ('read');
		$area_type = $this->get_module_type_name ( $module );
		$area_sql = 'select * from ' . $db->dbprefix ( self::TAB_AD_AREAS ) . " where inter_id='$inter_id' and hotel_id=$hotel_id";
		$type_sql = 'select * from ' . $db->dbprefix ( self::TAB_ENUM ) . " where type='$area_type' and status=1";
		if($hotel_id>0){
			$type_sql .=  " and code!='search_foot'";
		}
		$type_sql .= empty ( $code ) ? '' : " and code='$code'";
		$sql = "select a.*,e.code,e.des area_type_des from ($type_sql) e left join ($area_sql) a on e.code=a.area_type";
		if (! empty ( $code ))
			return $db->query ( $sql )->row_array ();
		return $db->query ( $sql )->result_array ();
	}
	public function get_ad_area($inter_id, $hotel_id, $area_type) {
		$db = $this->_load_db ('read');
		$db->where ( array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'area_type' => $area_type 
		) );
		return $db->get ( self::TAB_AD_AREAS )->row_array ();
	}
	public function replace_ad_area($inter_id, $hotel_id, $area_type, $data) {
		$check = $this->get_ad_area ( $inter_id, $hotel_id, $area_type );
		$data ['edittime'] = time ();
		if (! empty ( $check )) {
			$this->db->where ( array (
					'area_id' => $check ['area_id'],
					'inter_id' => $inter_id 
			) );
			return $this->db->update ( self::TAB_AD_AREAS, $data );
		} else {
			$data ['inter_id'] = $inter_id;
			$data ['hotel_id'] = $hotel_id;
			$data ['area_type'] = $area_type;
			return $this->db->insert ( self::TAB_AD_AREAS, $data );
		}
	}
	public function replace_ad($inter_id, $id, $data) {
		$check = $this->get_ad_by_ids ( $inter_id, 0, $id );
		$data ['edittime'] = time ();
		if (! empty ( $check )) {
			$this->db->where ( array (
					'id' => $check [0] ['id'],
					'inter_id' => $inter_id 
			) );
			return $this->db->update ( self::TAB_ADS, $data );
		} else {
			$data ['inter_id'] = $inter_id;
			$data ['hotel_id'] = 0;
			return $this->db->insert ( self::TAB_ADS, $data );
		}
	}
	public function list_fields() {
		return array (
				'ad_title' => array (
						'label' => '广告标题' 
				),
				'ad_link' => array (
						'label' => '广告链接' 
				),
				'des' => array (
						'label' => '广告描述' 
				),
				'status' => array (
						'label' => '状态' 
				) 
		);
	}
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields() {
		return array (
				'area_type_des' => array (
						'label' => '广告区域' 
				),
				'area_title' => array (
						'label' => '区域标题' 
				),
				'hotel' => array (
						'label' => '酒店名' 
				),
				'coexist' => array (
						'label' => '显示设定' 
				),
				'status' => array (
						'label' => '状态' 
				) 
		);
	}
	public function table_fields() {
		return array (
				'id' => '',
				'ad_title' => '',
				'ad_link' => '',
				'des' => '',
				'ad_img' => '',
				'status' => 1 
		);
	}
}