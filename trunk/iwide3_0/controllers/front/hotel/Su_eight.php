<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Su_eight extends MY_Controller {
	function __construct() {
		parent::__construct ();
		set_time_limit ( 0 );
	}
	function add_city_code() {
		// $this->load->library ( 'Baseapi/Subaapi_webservice', array (
		// 'testModel' => TRUE
		// ), 'su' );
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj ();
		$data = $s8->GetCity ();
		$inter_id = 'a455510007';
		$data = $data ['GetCityResult'] ['Content'] ['RegionModel'];
		$tmp = array (
				'hotel_id' => 0,
				'inter_id' => $inter_id,
				'webservice_name' => 'suba',
				'value_type' => 'city_code' 
		);
		$tmp_des = array (
				'hotel_id' => 0,
				'inter_id' => $inter_id,
				'webservice_name' => 'suba',
				'value_type' => 'city_code_des' 
		);
		$this->load->model ( 'common/Webservice_model' );
		$citys = $this->Webservice_model->get_web_reflect ( $inter_id, 0, 'suba', array (
				'city_code',
				'city_code_des' 
		), 1, 'w2l' );
		$city = empty ( $citys ['city_code'] ) ? array () : $citys ['city_code'];
		$city_des = empty ( $citys ['city_code_des'] ) ? array () : $citys ['city_code_des'];
		$add_count = 0;
		$update_count = 0;
		$des_add_count = 0;
		$des_update_count = 0;
		foreach ( $data as $d ) {
			if (empty ( $city [$d ['RegionCode']] )) {
				$tmp ['web_value'] = $d ['RegionCode'];
				$tmp ['local_value'] = json_encode ( array (
						'r' => $d ['RegionName'],
						'p' => $d ['PinyinName'],
						'n' => 0 
				), JSON_UNESCAPED_UNICODE );
				$this->db->insert ( 'webservice_field_config', $tmp );
				$add_count ++;
			} else {
				$updata = array (
						'local_value' => json_encode ( array (
								'r' => $d ['RegionName'],
								'p' => $d ['PinyinName'],
								'n' => 0 
						) ) 
				);
				$this->db->where ( array (
						'hotel_id' => 0,
						'inter_id' => $inter_id,
						'webservice_name' => 'suba',
						'value_type' => 'city_code',
						'web_value' => $d ['RegionCode'] 
				) );
				$this->db->update ( 'webservice_field_config', $updata );
				$update_count ++;
			}
			if (empty ( $city_des [$d ['RegionCode']] )) {
				$tmp_des ['web_value'] = $d ['RegionCode'];
				$tmp_des ['local_value'] = $d ['RegionName'];
				$this->db->insert ( 'webservice_field_config', $tmp_des );
				$des_add_count ++;
			} else {
				$updata_des = array (
						'local_value' => $d ['RegionName'] 
				);
				$this->db->where ( array (
						'hotel_id' => 0,
						'inter_id' => $inter_id,
						'webservice_name' => 'suba',
						'value_type' => 'city_code_des',
						'web_value' => $d ['RegionCode'] 
				) );
				$this->db->update ( 'webservice_field_config', $updata_des );
				$des_update_count ++;
			}
		}
		echo 'add:' . $add_count . '<br />';
		echo 'update:' . $update_count . '<br />';
		echo 'des_add_count:' . $des_add_count . '<br />';
		echo 'des_update_count:' . $des_update_count . '<br />';
		$this->change_hot_city ();
	}
	function add_landmark_code() {
		$this->load->library ( 'Baseapi/Subaapi_webservice', array (
				'testModel' => TRUE 
		), 'su' );
		$data = $this->su->GetCity ();
		$inter_id = 'a455510007';
		$data = $data ['GetCityResult'] ['Content'] ['RegionModel'];
		$tmp = array (
				'hotel_id' => 0,
				'inter_id' => $inter_id,
				'webservice_name' => 'suba',
				'value_type' => 'city_code' 
		);
		$tmp_des = array (
				'hotel_id' => 0,
				'inter_id' => $inter_id,
				'webservice_name' => 'suba',
				'value_type' => 'city_code_des' 
		);
		$this->load->model ( 'common/Webservice_model' );
		$citys = $this->Webservice_model->get_web_reflect ( $inter_id, 0, 'suba', array (
				'city_code',
				'city_code_des' 
		), 1, 'w2l' );
		$city = empty ( $citys ['city_code'] ) ? array () : $citys ['city_code'];
		$city_des = empty ( $citys ['city_code_des'] ) ? array () : $citys ['city_code_des'];
		$add_count = 0;
		$update_count = 0;
		$des_add_count = 0;
		$des_update_count = 0;
		foreach ( $data as $d ) {
			if (empty ( $city [$d ['RegionCode']] )) {
				$tmp ['web_value'] = $d ['RegionCode'];
				$tmp ['local_value'] = json_encode ( array (
						'r' => $d ['RegionName'],
						'p' => $d ['PinyinName'],
						'n' => 0 
				), JSON_UNESCAPED_UNICODE );
				$this->db->insert ( 'webservice_field_config', $tmp );
				$add_count ++;
			} else {
				$updata = array (
						'local_value' => json_encode ( array (
								'r' => $d ['RegionName'],
								'p' => $d ['PinyinName'],
								'n' => 0 
						) ) 
				);
				$this->db->where ( array (
						'hotel_id' => 0,
						'inter_id' => $inter_id,
						'webservice_name' => 'suba',
						'value_type' => 'city_code',
						'web_value' => $d ['RegionCode'] 
				) );
				$this->db->update ( 'webservice_field_config', $updata );
				$update_count ++;
			}
			if (empty ( $city_des [$d ['RegionCode']] )) {
				$tmp_des ['web_value'] = $d ['RegionCode'];
				$tmp_des ['local_value'] = $d ['RegionName'];
				$this->db->insert ( 'webservice_field_config', $tmp_des );
				$des_add_count ++;
			} else {
				$updata_des = array (
						'local_value' => $d ['RegionName'] 
				);
				$this->db->where ( array (
						'hotel_id' => 0,
						'inter_id' => $inter_id,
						'webservice_name' => 'suba',
						'value_type' => 'city_code_des',
						'web_value' => $d ['RegionCode'] 
				) );
				$this->db->update ( 'webservice_field_config', $updata_des );
				$des_update_count ++;
			}
		}
		echo 'add:' . $add_count . '<br />';
		echo 'update:' . $update_count . '<br />';
		echo 'des_add_count:' . $des_add_count . '<br />';
		echo 'des_update_count:' . $des_update_count . '<br />';
		$this->change_hot_city ();
	}
	function change_hot_city() {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj ();
		$inter_id = 'a455510007';
		$data = $s8->sendTo ( 'region', "GetHotCity", '' );
		$data = $data ['GetHotCityResult'] ['Content'] ['RegionModel'];
		$this->load->model ( 'common/Webservice_model' );
		$city = $this->Webservice_model->get_web_reflect ( $inter_id, 0, 'suba', array (
				'city_code' 
		), 1, 'w2l' );
		$city = empty ( $city ['city_code'] ) ? array () : $city ['city_code'];
		$tmp = array (
				'hotel_id' => 0,
				'inter_id' => $inter_id,
				'webservice_name' => 'suba',
				'value_type' => 'city_code' 
		);
		$num = count ( $data ) + 1;
		$add_count = 0;
		$update_count = 0;
		foreach ( $data as $d ) {
			if (empty ( $city [$d ['RegionCode']] )) {
				$tmp ['web_value'] = $d ['RegionCode'];
				$tmp ['local_value'] = json_encode ( array (
						'r' => $d ['RegionName'],
						'p' => $d ['PinyinName'],
						'n' => $num 
				), JSON_UNESCAPED_UNICODE );
				$this->db->insert ( 'webservice_field_config', $tmp ) . ';' . '<br />';
				$add_count ++;
			} else {
				$updata = array (
						'local_value' => json_encode ( array (
								'r' => $d ['RegionName'],
								'p' => $d ['PinyinName'],
								'n' => $num 
						) ) 
				);
				$this->db->where ( array (
						'hotel_id' => 0,
						'inter_id' => $inter_id,
						'webservice_name' => 'suba',
						'value_type' => 'city_code',
						'web_value' => $d ['RegionCode'] 
				) );
				$this->db->update ( 'webservice_field_config', $updata );
				$update_count ++;
			}
			$num --;
		}
		echo 'add:' . $add_count . '<br />';
		echo 'update:' . $update_count;
	}
	function grab_hotel() {
		set_time_limit ( 0 );
		$inter_id = 'a455510007';
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj ();
		$search_model = new HotelSearchModel ();
		$search_model->ArrDate = date ( 'Y-m-d' );
		$search_model->OutDate = date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) );
		$search_model->CityCode = '';
		$search_model->PageIndex = 1;
		$search_model->PageSize = 2000;
		
		$web_hotels = $this->Suba_hotel_ext_model->get_web_hotel ( $search_model );
		$hotels = array ();
		$lowest = array ();
		foreach ( $web_hotels as $web_id => $h ) {
			$wh = $this->Suba_hotel_ext_model->get_web_hotel_detail ( $web_id );
			$data = array (
					'name' => $wh ['HotelName'],
					'address' => $wh ['Address'],
					'latitude' => $wh ['Latitude'],
					'longitude' => $wh ['Longitude'],
					'tel' => $wh ['BizPhone'],
					'intro' => empty ( $wh ['Introduction'] ) ? '' : $wh ['Introduction'],
					'short_intro' => $wh ['Merit'],
					'intro_img' => $wh ['HotelPic'],
					'services' => '',
					'email' => '',
					'fax' => '',
					'star' => 0,
					'country' => '中国',
					'province' => '',
					'web' => '',
					'status' => 1,
					'city' => $wh ['CityName'],
					'sort' => 0,
					'book_policy' => '' 
			);
			$hotels [$web_id] = $data;
			$lowest [$web_id] = $wh ['MinPrice'];
		}
		$addition = array (
				'pms_auth' => '',
				'pms_room_state_way' => 1,
				'pms_member_way' => 1 
		);
		$this->load->model ( 'common/Pms_model' );
		$params = array (
				'lowest' => $lowest 
		);
		$this->Pms_model->update_pms_hotel ( $inter_id, 'suba', $hotels, $addition, $params );
	}
	function grab_room() {
		set_time_limit ( 0 );
		$inter_id = 'a455510007';
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj ();
		$search_model = new RoomSearchModel ();
		$search_model->HotelID = '';
		$search_model->ArrDate = date ( 'Y-m-d' );
		$search_model->OutDate = date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) );
		
		$web_hotels = $this->Suba_hotel_ext_model->get_web_hotel ( $search_model );
		$hotels = array ();
		$lowest = array ();
		foreach ( $web_hotels as $web_id => $h ) {
			$wh = $this->Suba_hotel_ext_model->get_web_hotel_detail ( $web_id );
			$data = array (
					'name' => $wh ['HotelName'],
					'address' => $wh ['Address'],
					'latitude' => $wh ['Latitude'],
					'longitude' => $wh ['Longitude'],
					'tel' => $wh ['BizPhone'],
					'intro' => empty ( $wh ['Introduction'] ) ? '' : $wh ['Introduction'],
					'short_intro' => $wh ['Merit'],
					'intro_img' => $wh ['HotelPic'],
					'services' => '',
					'email' => '',
					'fax' => '',
					'star' => 0,
					'country' => '中国',
					'province' => '',
					'web' => '',
					'status' => 1,
					'city' => $wh ['CityName'],
					'sort' => 0,
					'book_policy' => '' 
			);
			$hotels [$web_id] = $data;
			$lowest [$web_id] = $wh ['MinPrice'];
		}
		$addition = array (
				'pms_auth' => '',
				'pms_room_state_way' => 1,
				'pms_member_way' => 1 
		);
		$this->load->model ( 'common/Pms_model' );
		$params = array (
				'lowest' => $lowest 
		);
		$this->Pms_model->update_pms_hotel ( $inter_id, 'suba', $hotels, $addition, $params );
	}
	function get_hotel_detail() {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_hotel_detail ( '628' );
		var_dump ( $s8 );
	}
	function get_web_hotel() {
		$inter_id = 'a455510007';
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj ();
		$search_model = new HotelSearchModel ();
		$search_model->ArrDate = date ( 'Y-m-d' );
		$search_model->OutDate = date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) );
		$search_model->CityCode = '';
		$search_model->PageIndex = 1;
		$search_model->PageSize = 10;
		$search_model->CityCode = '440100';
		$search_model->RegionCode = '440183';
		// $search_model->Latitude = '23.136425';
		// $search_model->Longitude = '113.32873';
		// $search_model->LandMarkID = '466';
		
		$web_hotels = $this->Suba_hotel_ext_model->get_web_hotel ( $search_model );
		var_dump ( $search_model );
		var_dump ( $web_hotels );
	}
	function get_web_comment_count() {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$data = $this->Suba_hotel_ext_model->get_web_comment_count ( 'a455510007', 1099, '2028' );
		var_dump ( $data );
	}
	function get_web_comments() {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$this->load->model ( 'common/Pms_model' );
		// $web_hotels=$this->Pms_model->get_hotels_pms_set('a455510007','suba');
		// foreach ($web_hotels as $wh){
		// if (!empty($wh['hotel_web_id'])){
		$data = $this->Suba_hotel_ext_model->get_web_comments ( 'a455510007', 1099, '409' );
		// if (count($data['GetHotelCommentsResult']['Content']['ListContent'])>1){
		// var_dump($wh['hotel_web_id']);
		var_dump ( $data );
		// exit;
		// }
		// }
		// }
		// var_dump($web_hotels);
	}
	// 骄傲店 = 1 << 0
	// 质量之星 = 1 << 1
	// 特色酒店 = 1 << 2
	function get_hotel_honor() {
		set_time_limit ( 0 );
		$inter_id = 'a455510007';
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$this->load->model ( 'common/Pms_model' );
		$web_hotels = $this->Pms_model->get_hotels_pms_set ( $inter_id, 'suba' );
		$reflect = array (
				59,
				58,
				57 
		);
		$count = count ( $reflect );
		$this->load->model ( 'hotel/Hotel_config_model' );
		$params = array ();
		$params ['effect'] = 1;
		$params ['default'] = 1;
		$data = $this->Hotel_config_model->get_hotels_config ( $inter_id, 'HOTEL', array_keys ( $web_hotels ), 'ICONS_IMG_SERACH_RESULT', $params );
		$add_count = 0;
		$update_count = 0;
		$config = array (
				'inter_id' => $inter_id,
				'module' => 'HOTEL',
				'param_name' => 'ICONS_IMG_SERACH_RESULT' 
		);
		foreach ( $web_hotels as $wh ) {
			if (! empty ( $wh ['hotel_web_id'] )) {
				$s8 = $this->Suba_hotel_ext_model->get_web_hotel_detail ( $wh ['hotel_web_id'] );
				$tmp = str_pad ( decbin ( $s8 ['Honour'] ), 3, 0, STR_PAD_LEFT );
				$honor = '';
				for($i = 0; $i < $count; $i ++) {
					$honor .= $tmp [$i] == 1 ? ',' . $reflect [$i] : '';
				}
				if (! empty ( $honor ))
					$honor = substr ( $honor, 1 );
				if (isset ( $data [$wh ['hotel_id']] ['ICONS_IMG_SERACH_RESULT'] )) {
					$cur = current ( $data [$wh ['hotel_id']] ['ICONS_IMG_SERACH_RESULT'] );
					$this->db->where ( 'id', $cur ['id'] );
					$this->db->update ( 'hotel_config', array (
							'param_value' => $honor 
					) );
					$update_count ++;
				} else {
					$config ['hotel_id'] = $wh ['hotel_id'];
					$config ['param_value'] = $honor;
					$this->db->insert ( 'hotel_config', $config );
					$add_count ++;
				}
			}
		}
		echo 'add_count:' . $add_count . '<br />';
		echo 'update_count:' . $update_count . '<br />';
	}
	function get_web_landmark() {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$data = $this->Suba_hotel_ext_model->get_web_landmark ( 'a455510007', '110100' );
		var_dump ( $data );
	}
	function get_webtype_landmark() {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$data = $this->Suba_hotel_ext_model->get_web_landmark_by_type ( 'a455510007', '110100', 6 );
		var_dump ( $data );
	}
	function get_web_order() {
		$this->load->model ( 'hotel/pms/Suba_hotel_model' );
		$data = $this->Suba_hotel_model->get_order_info ( array (
				'web_orderid' => '32495730' 
		) );
		var_dump ( $data );
	}
}
