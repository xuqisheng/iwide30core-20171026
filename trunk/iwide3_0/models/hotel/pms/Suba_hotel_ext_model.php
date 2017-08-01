<?php
// include_once '../iwidelibraries/Baseapi/Suba_webservice.php';
class Suba_hotel_ext_model extends CI_Model {
	protected $service_name = 'suba';
	function __construct() {
		parent::__construct ();
	}
	function get_web_hotel($search_model) {
		$s8 = $this->get_web_obj ();
		$send_data = array (
				'searchModel' => $search_model 
		);
		$result = $s8->sendTo ( 'hotel', "GetHotels", $send_data );
		$data = array ();
		if (! empty ( $result ['GetHotelsResult'] ['Content'] ['ListContent'] ) && $result ['GetHotelsResult'] ['IsError'] == false) {
			if ($result ['GetHotelsResult'] ['Content'] ['PageIndex'] > $result ['GetHotelsResult'] ['Content'] ['PageCount']) {
				return $data;
			}
			if (isset ( $result ['GetHotelsResult'] ['Content'] ['ListContent'] ['HotelInfo'] ['HotelID'] )) {
				$result ['GetHotelsResult'] ['Content'] ['ListContent'] ['HotelInfo'] = array (
						$result ['GetHotelsResult'] ['Content'] ['ListContent'] ['HotelInfo'] 
				);
			}
			foreach ( $result ['GetHotelsResult'] ['Content'] ['ListContent'] ['HotelInfo'] as $h ) {
				$data [$h ['HotelID']] = $h;
			}
		}
		return $data;
	}
	function get_web_hotel_detail($hotel_web_id, $pms_set = array()) {
		$s8 = $this->get_web_obj ();
		$send_data = array (
				'hotelID' => $hotel_web_id 
		);
		$result = $s8->sendTo ( 'hotel', "GetHotelDetail", $send_data );
		$data = array ();
		if (! empty ( $result ['GetHotelDetailResult'] ) && $result ['GetHotelDetailResult'] ['IsError'] == false) {
			$data = $result ['GetHotelDetailResult'] ['Content'];
			$this->update_honor ( $pms_set ['hotel_id'], $data ['Honour'] );
		}
		return $data;
	}
	function update_honor($hotel_id, $honor) {
		$inter_id = 'a455510007';
		$where = array (
				'inter_id' => $inter_id,
				'ident' => $hotel_id,
				'cache_type' => 'search_icon_uptime' 
		);
		$this->readDB()->where ( $where );
		$cache = $this->readDB()->get ( 'hotel_cache' )->row_array ();
		$new_honor = '';
		if (empty ( $cache ) || $cache ['update_time'] + 86400 < time ()) {
			$this->readDB()->where ( array (
					'param_name' => 'ICONS_IMG_SERACH_RESULT',
					'inter_id' => $inter_id,
					'hotel_id' => $hotel_id 
			) );
			$icon = $this->readDB()->get ( 'hotel_config' )->row_array ();
			$reflect = array (
					904,
					82,
					81,
					80 
			);
			// $count = count ( $reflect );
			$tmp = str_pad ( decbin ( $honor ), 4, 0, STR_PAD_LEFT );
			for($i = 0; $i < 4; $i ++) {
				$new_honor .= $tmp [$i] == 1 ? ',' . $reflect [$i] : '';
			}
			if (! empty ( $new_honor ))
				$new_honor = substr ( $new_honor, 1 );
			if (empty ( $icon )) {
				$config = array (
						'inter_id' => $inter_id,
						'module' => 'HOTEL',
						'param_name' => 'ICONS_IMG_SERACH_RESULT' 
				);
				$config ['hotel_id'] = $hotel_id;
				$config ['param_value'] = $new_honor;
			} else {
				$this->db->where ( 'id', $icon ['id'] );
				$this->db->update ( 'hotel_config', array (
						'param_value' => $new_honor 
				) );
			}
			$this->load->model ( 'hotel/Hotel_cache_model' );
			$where ['value'] = $new_honor;
			$where ['update_time'] = time ();
			$this->Hotel_cache_model->replace_cache_data ( $where );
		}
		// var_dump($data );
	}
	function search_hotel_front($inter_id, $paras, $pms_set = array()) {
		$s8 = $this->get_web_obj ();
		$search_model = new HotelSearchModel ();
		$search_model->ArrDate = $search_model->dateFormat ( $paras ['startdate'] );
		$search_model->OutDate = $search_model->dateFormat ( $paras ['enddate'] );
		isset ( $paras ['keyword'] ) ? $search_model->Keywords = $paras ['keyword'] : 1;
		$search_model->CityCode = '';
		if (isset ( $paras ['offset'] )) {
			$search_model->PageIndex = intval ( $paras ['offset'] / $paras ['nums'] ) + 1;
			$search_model->PageSize = $paras ['nums'];
		} else {
			$search_model->PageIndex = 1;
			$search_model->PageSize = 20;
		}
		
		if (! empty ( $paras ['sort_type'] )) {
			$enum = $this->pms_enum ( 'sort_type' );
			$search_model->SortType = isset ( $enum [$paras ['sort_type']] ) ? $enum [$paras ['sort_type']] : 1;
			if (! empty ( $paras ['check_distance'] ) && $paras ['sort_type'] == 'distance') {
				$this->load->helper ( 'calculate' );
				$data = gcj2bd ( $paras ['longitude'], $paras ['latitude'] ); // 速八使用的是百度坐标
				$search_model->Latitude = $data ['latitude'];
				$search_model->Longitude = $data ['longitude'];
			}
		}
		if (! empty ( $paras ['city'] )) {
			$this->load->model ( 'common/Webservice_model' );
			$city = $this->get_city_des ( $inter_id, $pms_set ['pms_type'], $paras ['city'] );
			if (! empty ( $city )) {
				$search_model->CityCode = $city;
			}
		}
		if (! empty ( $paras ['extra_condition'] )) {
			$extra_condition = json_decode ( $paras ['extra_condition'], TRUE );
			isset ( $extra_condition ['land_mark'] ) ? $search_model->LandMarkID = $extra_condition ['land_mark'] : 0;
			isset ( $extra_condition ['region'] ) ? $search_model->RegionCode = $extra_condition ['region'] : 0;
			// if (! empty ( $paras ['sort_type'] ) && $paras ['sort_type'] == 'distance') {
			// $search_model->SortType = 1;
			// }
			
			// 如果是地标搜索和地图坐标搜索，需要按距离搜,sortType要传4至速8
			if (empty ( $paras ['sort_type'] ) && (isset ( $extra_condition ['land_mark'] ) || isset ( $extra_condition ['region'] ) || isset ( $extra_condition ['bdmap'] ))) {
				$search_model->SortType = 4;
			}
			
			if (isset ( $extra_condition ['bdmap'] )) {
				
				$lng_lat_array = explode ( ",", $extra_condition ['bdmap'] );
				
				if (count ( $lng_lat_array ) == 2) {
					
					$search_model->Longitude = $lng_lat_array [1];
					$search_model->Latitude = $lng_lat_array [0];
				}
			}
			
			if (isset ( $extra_condition ['price'] )) {
				
				$search_model->SpecialPriceType = $extra_condition ['price'];
			}
		}
		
		if ($search_model->Longitude && $search_model->Latitude) {
			
			// 有坐标就不传keywords
			$search_model->Keywords = '';
		}
		
		// 如有地标id也不传keywords
		if ($search_model->LandMarkID > 0) {
			
			$search_model->Keywords = '';
		}
		$search_model->Keywords = '';
		$web_hotels = $this->get_web_hotel ( $search_model );
// 		var_dump($search_model);
// 		var_dump($paras);
// 		exit ();
		$datas = array ();
		if (! empty ( $web_hotels )) {
			$hotel_ids = array_keys ( $web_hotels );
			$this->load->model ( 'common/Pms_model' );
			$this->load->model ( 'hotel/Hotel_model' );
			$pms_hotels = $this->Pms_model->get_hotels_pms_set ( $inter_id, 'suba', $hotel_ids, 'hotel_web_id', 'hotel_id' );
			$hotels = $this->Hotel_model->get_hotel_by_ids ( $inter_id, implode ( ',', array_keys ( $pms_hotels ) ), 1, 'key', 'object' );
			foreach ( $hotels as $h ) {
				$h->comment_data = array (
						'comment_count' => $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['CommentNum'],
						'good_rate' => $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['CommentRate'] 
				);
				$h->lowest = $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['MinPrice'];
				
				// 额外增加属性，用于速8标示新开业，1为新开业，2为非新开业
				$h->is_new_open = $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['IsNewOpen'];
				
				// 额外增加属性:团购（1：有，0：无），用于速8标示是否有特殊代码，暂时为1024（1<<10)为团购，后面增再做配置
				if ($web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['SpecialPriceType'] == 1024 || $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['SpecialPriceType'] == 4195328) {
					$h->is_tuan = 1;
				} else {
					$h->is_tuan = 0;
				}
				
				// 额外增加属性:用于速8标示是否支付余额付，以速8 IfAdvancePayMent 为标准，0：不支持；1：支持；
				$h->is_balance_pay = $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['IfAdvancePayMent'];
				$h->intro_img= $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['HotelPic'];
			}
			if (! empty ( $paras ['check_distance'] ) && $search_model->SortType == 4) {
				foreach ( $hotels as $h ) {
					$h->distance = number_format ( $web_hotels [$pms_hotels [$h->hotel_id] ['hotel_web_id']] ['Distance'], 1, '.', '' );
				}
				return $hotels;
			} else {
				$datas = array ();
				$hotel_reflcet = array ();
				foreach ( $pms_hotels as $ph ) {
					$hotel_reflcet [$ph ['hotel_web_id']] = $ph ['hotel_id'];
				}
				foreach ( $hotel_ids as $hid ) {
					if (!empty($hotel_reflcet [$hid])){
						if (! empty ( $web_hotels [$hid] ['Distance'] )) {
							$hotels [$hotel_reflcet [$hid]]->distance = number_format ( $web_hotels [$hid] ['Distance'], 1, '.', '' );
						}
						$datas [$hotel_reflcet [$hid]] = $hotels [$hotel_reflcet [$hid]];
					}
				}
				return $datas;
			}
		}
	}
	function get_hotel_citys($inter_id, $params = array()) {
		$this->load->model ( 'common/Webservice_model' );
		$city = $this->Webservice_model->get_web_reflect ( $inter_id, 0, $params ['pms_set'] ['pms_type'], array (
				'city_code' 
		), 1, 'w2l' );
		$city = empty ( $city ['city_code'] ) ? array () : $city ['city_code'];
		$py = array ();
		$this->load->helper ( 'string' );
		foreach ( $city as $c ) {
			$tmp = json_decode ( $c, true );
			$city_py = strtoupper ( get_first_py ( $tmp ['p'] ) );
			$py [$city_py] [] = array (
					'city' => $tmp ['r'],
					'hotel_num' => $tmp ['n'] 
			);
		}
		ksort ( $py );
		return $py;
	}
	function get_order_state($order, $pms_set,$status_des) {
		$state = array ();
		if ($order ['handled'] == 0 && ! empty ( $order ['web_orderid'] )) {
			$this->load->model ( 'hotel/pms/Suba_hotel_model' );
			$web_order = $this->Suba_hotel_model->get_order_info ( $order, $pms_set );
			// var_dump($web_order);
			if (! empty ( $web_order )) {
				$cancels = $this->pms_enum ( 'cancel_status' );
				$can_cancel = NULL;
				$web_re_pay = NULL;
				$web_check = NULL;
				$web_des = $status_des[$order['status']];
				if ($web_order ['OrderStatus'] < 10) {
					if ($order ['paytype'] == 'daofu') {
						$can_cancel = 1;
					} else if ($order ['paytype'] == 'weixin' && $order ['status'] == 9) {
						$can_cancel = 1;
					} else if ($web_order ['IsCancel'] == 1 && strtotime ( $web_order ['LastCancelTime'] ) > time ()) {
						$can_cancel = 1;
					} else {
						$can_cancel = 0;
					}
					if ($web_order ['PayStatus'] == 2 && ($order ['paytype'] == 'daofu' || ($order ['paytype'] == 'weixin' && $order ['paid'] == 0)) && strtotime ( $web_order ['ArrDate'] ) > time () && $web_order ['HotelCanPrepay'] == 1) {
						$web_re_pay = 1;
					} else {
						$web_re_pay = 0;
					}
				} else {
					$can_cancel = 0;
					$web_re_pay = 0;
				}
				if ($order ['status'] == 9) {
					$web_check = 1;
				}
				$state ['can_cancel'] = $can_cancel;
				$state ['re_pay'] = $web_re_pay;
				$state ['web_check'] = $web_check;
				$state ['web_des'] = $web_des;
				$state ['web_comment'] = 0;
			}
		}
		return $state;
	}
	function get_hotel_comment_count($inter_id, $hotel_id = null, $status = null, $params = array()) {
		$data = $this->get_web_comment_count ( $inter_id, $hotel_id, $params ['pms_set'] ['hotel_web_id'], $params );
		return $data;
	}
	
	// GetHotelCommentCount
	function get_web_comment_count($inter_id, $hotel_id, $hotel_web_id, $params = array()) {
		$s8 = $this->get_web_obj ();
		$send_data = array (
				'hotelID' => $hotel_web_id 
		);
		$result = $s8->sendTo ( 'hotel', "GetHotelCommentCount", $send_data );
		$data = array ();
		$data ['comment_count'] = 0;
		$data ['comment_score'] = 0;
		$data ['score_count'] = 0;
		$data ['good_rate'] = - 1;
		if (! empty ( $result ['GetHotelCommentCountResult'] ['Content'] ) && $result ['GetHotelCommentCountResult'] ['IsError'] == false) {
			$content = $result ['GetHotelCommentCountResult'] ['Content'];
			$data ['comment_count'] = isset ( $params ['commented'] [$hotel_id] ['comment_count'] ) ? $params ['commented'] [$hotel_id] ['comment_count'] : $content ['CommentCount'];
			$data ['good_rate'] = isset ( $params ['commented'] [$hotel_id] ['good_rate'] ) ? $params ['commented'] [$hotel_id] ['good_rate'] : str_replace ( '%', '', $content ['CommentRate'] );
			$total_score = 0;
			$comment_reflect = array (
					'T1Count30' => 5,
					'T2Count30' => 4,
					'T3Count30' => 3,
					'T4Count30' => 2,
					'T5Count30' => 1,
					'T1Count90' => 5,
					'T2Count90' => 4,
					'T3Count90' => 3,
					'T4Count90' => 2,
					'T5Count90' => 1,
					'T1Count180' => 5,
					'T2Count180' => 4,
					'T3Count180' => 3,
					'T4Count180' => 2,
					'T5Count180' => 1 
			);
			foreach ( $content as $k => $t ) {
				if (isset ( $comment_reflect [$k] )) {
					$data ['score_count'] += $t;
					$total_score += $t * $comment_reflect [$k];
				}
			}
			$data ['comment_score'] = empty ( $data ['score_count'] ) ? 0 : number_format ( $total_score / $data ['score_count'], 1, '.', '' );
		}
		return $data;
	}
	function get_hotel_comments($inter_id, $hotel_id = null, $status = null, $order_by = '', $nums = null, $offset = null, $params = array()) {
		return $this->get_web_comments ( $inter_id, $hotel_id, $params ['pms_set'] ['hotel_web_id'], $nums, $offset, $params );
	}
	function get_web_comments($inter_id, $hotel_id, $hotel_web_id, $nums = null, $offset = null, $params = array()) {
		$s8 = $this->get_web_obj ();
		$send_data = array (
				'hotelID' => $hotel_web_id,
				'commentType' => 0,
				'pageIndex' => 1,
				'pageSize' => 20 
		);
		if (isset ( $offset )) {
			$send_data ['pageIndex'] = intval ( $offset / $nums ) + 1;
			$send_data ['pageSize'] = $nums;
		}
		$result = $s8->sendTo ( 'hotel', "GetHotelComments", $send_data );
		$data = array ();
		if (! empty ( $result ['GetHotelCommentsResult'] ['Content'] ) && $result ['GetHotelCommentsResult'] ['IsError'] == false) {
			if ($result ['GetHotelCommentsResult'] ['Content'] ['PageIndex'] > $result ['GetHotelCommentsResult'] ['Content'] ['PageCount']) {
				return $data;
			}
			$content = $result ['GetHotelCommentsResult'] ['Content'] ['ListContent'] ['HotelComment'];
			if (isset ( $content ['CommentNo'] )) {
				$content = array (
						$content 
				);
			}
			$comment_reflect = $this->pms_enum ( 'comment_score' );
			foreach ( $content as $c ) {
				$tmp = array (
						'content' => $c ['Content'],
						'headimgurl' => '',
						'nickname' => $c ['CommentNo'],
						'score' => $comment_reflect [$c ['CommentTag']],
						'member_level' => $c ['CardType'],
						'comment_time' => strtotime ( $c ['CommentDate'] ),
						'order_info' => array () 
				); // 可为空
				if (!empty($c['ReplyContent'])){
					$tmp['reply_content']=$c['ReplyContent'];
				}
				$data [] = $tmp;
			}
		}
		return $data;
	}
	function get_city_filter($inter_id, $city, $params = array(), $pms_set = array()) {
		$this->load->model ( 'common/Webservice_model' );
		$citys = $this->get_city_des ( $inter_id, $pms_set ['pms_type'], $city );
		$data = array ();
		if (! empty ( $citys )) {
			$types = $this->pms_enum ( 'land_mark_type' );
			$mark_types = array (
					2,
					5 
			);
// 			foreach ( $mark_types as $m ) {
// 				$marks = $this->get_web_landmark_by_type ( $inter_id, $citys, $m );
// 				if (! empty ( $marks )) {
// 					$data ['land_mark'] [$m] = array (
// 							'filter_type_id' => $m,
// 							'filter_type_name' => $types [$m],
// 							'marks' => $this->get_web_landmark_by_type ( $inter_id, $citys, $m ) 
// 					);
// 				}
// 			}
// 			$data ['region'] = array (
// 					'filter_type_id' => 'region',
// 					'filter_type_name' => '行政区域',
// 					'marks' => $this->get_region ( $inter_id, $citys ) 
// 			);
			$data =array(1);
			// 速8增加优惠搜索显示，不用对接接口，暂时只有团队价
// 			$data ['price'] = array (
// 					'filter_type_id' => 'discount',
// 					'filter_type_name' => '特价',
// 					'marks' => array (
// 							// 1024是
// 							'1024' => array (
// 									'filter_id' => 1024,
// 									'filter_name' => "团购价" 
// 							) 
// 					) 
// 			);
		}
		return $data;
	}
	
	// GetLandMark
	function get_web_landmark($inter_id, $city_code) {
		$s8 = $this->get_web_obj ();
		$send_data = array (
				'regionCode' => $city_code 
		);
		$result = $s8->sendTo ( 'region', "GetLandMark", $send_data );
		$data = array ();
		if (! empty ( $result ['GetLandMarkResult'] ['Content'] ['LandMark'] ) && $result ['GetLandMarkResult'] ['IsError'] == false) {
			$content = $result ['GetLandMarkResult'] ['Content'] ['LandMark'];
			if (isset ( $content ['LandMarkTypeID'] )) {
				$content = array (
						$content 
				);
			}
			foreach ( $content as $ct ) {
				$data [$ct ['LandMarkTypeID']] ['filter_type_id'] = $ct ['LandMarkTypeID'];
				$data [$ct ['LandMarkTypeID']] ['filter_type_name'] = $ct ['LandMarkName'];
				if (! empty ( $ct ['SubLandMarks'] ['LandMark'] )) {
					$tmp = $ct ['SubLandMarks'] ['LandMark'];
					if (isset ( $tmp ['LandMarkTypeID'] )) {
						$tmp = array (
								$tmp 
						);
					}
					foreach ( $tmp as $t ) {
						$data [$ct ['LandMarkTypeID']] ['filters'] [$t ['LandMarkCode']] ['filter_id'] = $t ['LandMarkCode'];
						$data [$ct ['LandMarkTypeID']] ['filters'] [$t ['LandMarkCode']] ['filter_name'] = $t ['LandMarkName'];
					}
				}
			}
		}
		return $data;
	}
	public function get_service_name() {
		return $this->service_name;
	}
	
	// GetLandMarkByMainTypeID
	// 学校附近 1
	// 商业中心 2
	// 旅游景点 3
	// 展馆会场 4
	// 地铁站 5
	// 行政中心 6
	// 交通枢纽 7
	// 服务设施 8
	function get_web_landmark_by_type($inter_id, $city_code, $mark_type) {
		$this->load->model ( 'common/Webservice_model' );
		$landmarks = $this->Webservice_model->get_field_config ( $inter_id, 'web_landmark' . '_' . $mark_type, $this->get_service_name (), array (
				'web_value' => $city_code 
		) );
		$landmark_data = array (
				'uptime' => 0 
		);
		if (! empty ( $landmarks )) {
			$landmark_data = json_decode ( $landmarks ['local_value'], TRUE );
		}
		
		$data = array ();
		if (empty ( $landmarks ) || $landmark_data ['uptime'] + 43200 < time ()) {
			
			$s8 = $this->get_web_obj ();
			$send_data = array (
					'regionCode' => $city_code,
					'landMarkMainTypeID' => $mark_type 
			);
			$result = $s8->sendTo ( 'region', "GetLandMarkByMainTypeID", $send_data );
			if (! empty ( $result ['GetLandMarkByMainTypeIDResult'] ['Content'] ['LandMark'] ) && $result ['GetLandMarkByMainTypeIDResult'] ['IsError'] == false) {
				$content = $result ['GetLandMarkByMainTypeIDResult'] ['Content'] ['LandMark'];
				if (isset ( $content ['LandMarkTypeID'] )) {
					$content = array (
							$content 
					);
				}
				foreach ( $content as $ct ) {
					$data [$ct ['LandMarkTypeID']] ['filter_type_id'] = $ct ['LandMarkTypeID'];
					$data [$ct ['LandMarkTypeID']] ['filter_type_name'] = $ct ['LandMarkName'];
					if (! empty ( $ct ['SubLandMarks'] ['LandMark'] )) {
						$tmp = $ct ['SubLandMarks'] ['LandMark'];
						if (isset ( $tmp ['LandMarkTypeID'] )) {
							$tmp = array (
									$tmp 
							);
						}
						foreach ( $tmp as $t ) {
							$data [$ct ['LandMarkTypeID']] ['filters'] [$t ['LandMarkCode']] ['filter_id'] = $t ['LandMarkCode'];
							$data [$ct ['LandMarkTypeID']] ['filters'] [$t ['LandMarkCode']] ['filter_name'] = $t ['LandMarkName'];
						}
					}
				}
			}
			if (! empty ( $landmarks )) {
				$this->db->where ( array (
						'inter_id' => $inter_id,
						'id' => $landmarks ['id'] 
				) );
				$this->db->update ( 'webservice_field_config', array (
						'local_value' => json_encode ( array (
								'uptime' => time (),
								'data' => $data 
						), JSON_UNESCAPED_UNICODE ) 
				) );
			} else {
				$updata = array (
						'inter_id' => $inter_id,
						'value_type' => 'web_landmark' . '_' . $mark_type,
						'webservice_name' => $this->get_service_name (),
						'web_value' => $city_code,
						'local_value' => json_encode ( array (
								'uptime' => time (),
								'data' => $data 
						), JSON_UNESCAPED_UNICODE ) 
				);
				$this->db->insert ( 'webservice_field_config', $updata );
			}
		} else {
			$data = $landmark_data ['data'];
		}
		return $data;
	}
	// GetRegion
	function get_region($inter_id, $city_code) {
		$this->load->model ( 'common/Webservice_model' );
		$regions = $this->Webservice_model->get_field_config ( $inter_id, 'web_region', $this->get_service_name (), array (
				'web_value' => $city_code 
		) );
		$region_data = array (
				'uptime' => 0 
		);
		if (! empty ( $regions )) {
			$region_data = json_decode ( $regions ['local_value'], TRUE );
		}
		$data = array ();
		if (empty ( $regions ) || $region_data ['uptime'] + 43200 < time ()) {
			$s8 = $this->get_web_obj ();
			$send_data = array (
					'regionCode' => $city_code 
			);
			$result = $s8->sendTo ( 'region', "GetRegion", $send_data );
			if (! empty ( $result ['GetRegionResult'] ['Content'] ['RegionModel'] ) && $result ['GetRegionResult'] ['IsError'] == false) {
				$content = $result ['GetRegionResult'] ['Content'] ['RegionModel'];
				if (isset ( $content ['RegionCode'] )) {
					$content = array (
							$content 
					);
				}
				foreach ( $content as $ct ) {
					$data [$ct ['RegionCode']] = array (
							'filter_id' => $ct ['RegionCode'],
							'filter_name' => $ct ['RegionName'] 
					);
				}
			}
			
			if (! empty ( $regions )) {
				$this->db->where ( array (
						'inter_id' => $inter_id,
						'id' => $regions ['id'] 
				) );
				$this->db->update ( 'webservice_field_config', array (
						'local_value' => json_encode ( array (
								'uptime' => time (),
								'data' => $data 
						), JSON_UNESCAPED_UNICODE ) 
				) );
			} else {
				$updata = array (
						'inter_id' => $inter_id,
						'value_type' => 'web_region',
						'webservice_name' => $this->get_service_name (),
						'web_value' => $city_code,
						'local_value' => json_encode ( array (
								'uptime' => time (),
								'data' => $data 
						), JSON_UNESCAPED_UNICODE ) 
				);
				$this->db->insert ( 'webservice_field_config', $updata );
			}
		} else {
			$data = $region_data ['data'];
		}
		return $data;
	}
	
	/**
	 * 匹配城市名和pms对应的代码
	 *
	 * @param unknown $inter_id        	
	 * @param unknown $pms_type        	
	 * @param unknown $city        	
	 * @return unknown
	 */
	function get_city_des($inter_id, $pms_type, $city) {
		$this->load->model ( 'common/Webservice_model' );
		$citys = $this->Webservice_model->get_web_reflect ( $inter_id, 0, $pms_type, array (
				'city_code_des' 
		), 1, 'l2w' );
		if (! empty ( $city )) {
			if (! empty ( $citys ['city_code_des'] [$city] )) {
				return $citys ['city_code_des'] [$city];
			} else if (! empty ( $citys ['city_code_des'] [$city . '市'] )) {
				return $citys ['city_code_des'] [$city . '市'];
			} else {
				$city = str_replace ( '市', '', $city );
				foreach ( $citys ['city_code_des'] as $city_name => $code ) {
					if (strpos ( $city_name, $city ) !== FALSE) {
						return $code;
					}
				}
			}
		}
		return '';
	}
	
	// array(1) {
	// ["AddCommentResult"]=>
	// array(5) {
	// ["IsError"]=>
	// bool(false)
	// ["ResultCode"]=>
	// string(2) "00"
	// ["Message"]=>
	// string(0) ""
	// ["Content"]=>
	// array(8) {
	// ["CommentID"]=>
	// int(1008)
	// ["CardNo"]=>
	// string(9) "303503087"
	// ["Mobile"]=>
	// string(11) "13560428194"
	// ["CommentAmount"]=>
	// int(0)
	// ["Result"]=>
	// int(1)
	// ["Content"]=>
	// string(20) "测试评论32495781"
	// ["Feels"]=>
	// string(6) "111111"
	// ["IpAddress"]=>
	// string(9) "127.0.0.1"
	// }
	// ["CurTime"]=>
	// string(33) "2016-04-28T22:12:40.1524055+08:00"
	// }
	// }
	function add_comment($params) {
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $params ['data'] ['inter_id'], array (
				'orderid' => $params ['data'] ['orderid'] 
		) );
		
		if (! empty ( $order )) {
			$order = $order [0];
			if (! empty ( $order ['web_orderid'] ) && !empty ( $order ['member_no'] )) {
				$member = $this->get_web_member ( $order ['member_no'] );
				if ( !empty ( $member )) {
					$s8 = $this->get_web_obj ();
					$score_reflect = $this->pms_enum ( 'comment_score' );
					$score_reflect = array_flip ( $score_reflect );
					
					$feel='111111';
					$recommend=10;
					if (!empty($params['data']['extra_info'])){
						$extra=json_decode($params['data']['extra_info'],TRUE);
						if (!empty($extra['feel'])){
							$items=array('service','clean','worth','shower','sleep','net');
							$feel='';
							foreach ($items as $i){
								if (isset($extra['feel'][$i])){
									$feel.=$extra['feel'][$i]==0?'0':'1';
								}else {
									$feel.='1';
								}
							}
						}
						if (isset($extra['recommend'])&&$extra['recommend']>=0&&$extra['recommend']<=10){
							$recommend=$extra['recommend'];
						}
					}
					
					$this->load->helper ( 'common' );
					$result = $s8->AddComment ( $order ['web_orderid'], $member ['CustomerID'], ceil ( $score_reflect [$params ['data'] ['score']] ), $params ['data'] ['content'], $feel, getIp (),$recommend );
					if ($result ['AddCommentResult'] ['IsError'] == FALSE && ! empty ( $result ['AddCommentResult'] ['Content'] )) {
						$web_comment_id = $result ['AddCommentResult'] ['Content'] ['CommentID'];
						return array (
								's' => 1,
								'errmsg' => '提交成功',
								'web_comment_id' => $web_comment_id 
						);
					} else {
						return array (
								's' => 0,
								'errmsg' => $result ['AddCommentResult'] ['Message'] 
						);
					}
				}
			} else {
				return array (
						's' => 0,
						'errmsg' => '您不能评论此订单' 
				);
			}
		}
		return array (
				's' => 0,
				'errmsg' => '提交评论失败' 
		);
	}
	function get_web_member($member_no) {
		$s8 = $this->get_web_obj ();
		$result = $s8->GetCustomer ( $member_no );
		$data = array ();
		if (! empty ( $result ['GetCustomerResult'] ['Content'] ) && $result ['GetCustomerResult'] ['IsError'] == FALSE) {
			$data = $result ['GetCustomerResult'] ['Content'];
		}
		return $data;
	}
	function pms_enum($type) {
		switch ($type) {
			case 'sort_type' :
				// 推荐：1（默认）
				// 按价格：2
				// 按好评率：3
				// 按距离排序：4
				return array (
						'default' => 1,
						'price_up' => 2,
						'good_rate' => 3,
						'distance' => 4 
				);
				break;
			case 'land_mark_type' :
				// 学校附近 1
				// 商业中心 2
				// 旅游景点 3
				// 展馆会场 4
				// 地铁站 5
				// 行政中心 6
				// 交通枢纽 7
				// 服务设施 8
				return array (
						'1' => '学校附近',
						'2' => '商业中心',
						'3' => '旅游景点',
						'4' => '展馆会场',
						'5' => '地铁站',
						'6' => '行政中心',
						'7' => '交通枢纽',
						'8' => '服务设施' 
				);
				break;
			case 'order_status' :
				// 等候 = 1,
				// 预订 = 5,
				// 在住 = 10,
				// 未到 = 15,
				// 离店 = 20,
				// 取消 = 25,
				// 归档 = 30,
				return array (
						'1' => 0,
						'5' => 1,
						'10' => 2,
						'15' => 8,
						'20' => 3,
						'25' => 5,
						'30' => 3 
				);
				break;
			case 'comment_score' :
				// 1：非常满意；
				// 2：满意；
				// 3：一般；
				// 4：不满意；
				// 5：非常不满意
				return array (
						'1' => 5,
						'2' => 4,
						'3' => 3,
						'4' => 2,
						'5' => 1 
				);
			case 'cancel_status' :
				// 1:可以取消
				// 2：不可以取消
				return array (
						'1' => 1,
						'2' => 0 
				);
			case 'paid_status' :
				// 1:已支付
				// 2：未支付
				return array (
						'1' => 1,
						'2' => 0 
				);
			default :
				break;
		}
	}
	function get_web_obj($test = TRUE) {
		if (! isset ( $this->_s8 )) {
			$this->load->library ( 'Baseapi/Subaapi_webservice' );
			$this->_s8 = new Subaapi_webservice ( $test );
		}
		return $this->_s8;
	}
	function grab_hotel() {
		set_time_limit ( 0 );
		$inter_id = 'a455510007';
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj (FALSE);
		$search_model = new HotelSearchModel ();
		$search_model->ArrDate = date ( 'Y-m-d');
		$search_model->OutDate = date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) );
		$search_model->CityCode = '';
		$search_model->PageIndex = 1;
		$search_model->PageSize = 2000;
	
		$web_hotels = $this->get_web_hotel ( $search_model );
		
		$this->readDB()->where ( 'inter_id', $inter_id );
		$this->readDB()->where ( 'pms_type', 'suba' );
		$this->readDB()->where ( 'hotel_id >', 0 );
		$result = $this->readDB()->get ( 'hotel_additions ')->result_array ();
		$hotel_ids = array ();
		foreach ( $result as $r ) {
			$hotel_ids [$r ['hotel_web_id']] = $r;
		}
			
		
		$hotels = array ();
		$lowest = array ();
		foreach ( $web_hotels as $web_id => $h ) {
			$wh = $this->Suba_hotel_ext_model->get_web_hotel_detail ( $web_id );
			if (empty($wh ['HotelName']))
				$wh ['HotelName']=$web_id;
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
				'lowest' => $lowest,
				'hotel_id' => 1391
		);
		$this->Pms_model->update_pms_hotel ( $inter_id, 'suba', $hotels, $addition, $params );
	}

	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
}