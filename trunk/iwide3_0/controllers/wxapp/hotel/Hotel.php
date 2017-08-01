<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Hotel extends MY_Front_Wxapp {
	public $common_data;
	public $openid;
	function __construct() {
		parent::__construct ();
		/*if ($this->inter_id=='a455510007') {
			$this->openid='o4F21jsYI7TIg_Kgp8558oef1VX4';
		}*/
		$this->module = 'hotel';
		$this->member_no = '';
		$this->member_lv = '';
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $this->inter_id,
				'hotel_id' => 0 
		), 'pub_pmsa' );
		$member = $this->pub_pmsa->check_openid_member ( $this->inter_id, $this->openid, array (
				'create' => TRUE 
		) );
		if (! empty ( $member ) && isset ( $member->mem_id )) {
			$this->member_no = $member->mem_card_no;
			$this->member_lv = $member->level;
		}
		$this->common_data ['member'] = $member;
		$this->common_data ['inter_id'] = $this->inter_id;
	}
	function search() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		// 可预订的开始日期
		$pre_sp_date = $this->preSpDate ();
		$startime = time () + ($pre_sp_date * 86400);
		$data ['startdate'] = date ( 'Y/m/d', $startime );
		
		$data ['enddate'] = date ( 'Y/m/d', strtotime ( '+ 1 day', $startime ) );
		$this->load->model ( 'wx/Publics_model' );
		$imgs = $this->Publics_model->get_pub_imgs ( $this->inter_id, 'hotelslide_wxapp' );
		if(count($imgs) > 0){
			$data ['pubimgs'] = $imgs;
		}else{
			$data ['pubimgs'] = $this->Hotel_model->get_imgs ( 'pub', $this->inter_id );
		}
		$cities = $this->Hotel_model->get_hotel_citys ( $this->inter_id );
		$data ['citys'] = $cities ['citys'];
		$data ['first_city'] = $cities ['first_city'];
		$data ['hot_city'] = $cities ['hot_city'];
		
		$data ['last_orders'] = $this->Order_model->get_last_order ( $this->inter_id, $this->openid, 5, true, 'h.city' );
		$data ['hotel_collection'] = $this->Hotel_model->get_front_marks ( array (
		'inter_id' => $this->inter_id,
		'openid' => $this->openid,
		'mark_type' => 'hotel_collection',
		'status' => 1
		), 'mark_nums desc', 5, 0 );
		// $data ['hotel_visited'] = $this->Hotel_model->get_front_marks ( array (
		// 'inter_id' => $this->inter_id,
		// 'openid' => $this->openid,
		// 'mark_type' => 'hotel_visited',
		// 'status' => 1
		// ), 'mark_time desc', 5, 0 );
		
		$data ['pre_sp_date'] = $pre_sp_date;
		
		$this->out_put_msg ( 1, '', $data,'hotel/search' );
	}
	function sresult() {
		$data = $this->common_data;
		
		$city = $this->get_source ( 'city' );
		if (empty ( $city )) {
			$city = $this->input->get ( 'city', TRUE );
			if (! empty ( $city )) {
				$city = json_decode ( '["' . str_replace ( '%', "\\", $city ) . '"]' );
				if (! empty ( $city [0] ))
					$city = $city [0];
			}
		}
		$city = addslashes ( $city );
		$startdate = $this->get_source ( 'startdate' );
		$enddate = $this->get_source ( 'enddate' );
		$extra_condition = $this->get_source ( 'ec' );
		
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id );
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		
		$keyword = $this->get_source ( 'keyword' );
		$keyword = addslashes ( $keyword );
		$this->load->model ( 'hotel/Hotel_check_model' );
		$result = $this->Hotel_model->search_hotel_front ( $this->inter_id, array (
				'keyword' => $keyword,
				'city' => $city,
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'extra_condition' => $extra_condition 
		) );
		$data ['city'] = $city;
		$data ['keyword'] = $keyword;
		$data ['extra_condition'] = $extra_condition;
		$data ['hotel_ids'] = '';
		if (! empty ( $result )) {
			foreach ( $result as $rt ) {
				$data ['hotel_ids'] .= ',' . $rt->hotel_id;
			}
			$data ['hotel_ids'] = substr ( $data ['hotel_ids'], 1 );
			$this->load->model ( 'hotel/Hotel_check_model' );
			$result = $this->Hotel_check_model->get_extra_info ( $this->inter_id, $result, array (
					'hotel_service',
					'lowest_price',
					'search_icons',
					'comment_data' 
			), array (
					'startdate' => $data ['startdate'],
					'enddate' => $data ['enddate'],
					'member_level' => $this->member_lv 
			) );
			$data ['result'] = $result;
			
			$this->load->model ( 'hotel/Hotel_config_model' );
			$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
					'HOTEL_RESULT_ICON' 
			) );
			$data ['icons_set'] = array ();
			if (! empty ( $config_data ['HOTEL_RESULT_ICON'] )) {
				$data ['icons_set'] = json_decode ( $config_data ['HOTEL_RESULT_ICON'], TRUE );
			}
		}
		// $this->load->model ( 'common/Record_model' );
		// $this->Record_model->visit_log ( array (
		// 'openid' => $this->openid,
		// 'inter_id' => $this->inter_id,
		// 'title' => '搜索结果',
		// 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
		// 'des' => "城市：$city,关键字：$keyword"
		// ) );
		// 可预订前几天的房型
		$data ['pre_sp_date'] = $this->preSpDate ();
		$this->out_put_msg ( 1, '', $data );
	}
	
	function sresult2() {
		$data = $this->common_data;
	
		$city = $this->get_source ( 'city' );
		if (empty ( $city )) {
			$city = $this->input->get ( 'city', TRUE );
			if (! empty ( $city )) {
				$city = json_decode ( '["' . str_replace ( '%', "\\", $city ) . '"]' );
				if (! empty ( $city [0] ))
					$city = $city [0];
			}
		}
		$city = addslashes ( $city );
		$startdate = $this->get_source ( 'startdate' );
		$enddate = $this->get_source ( 'enddate' );
		$extra_condition = $this->get_source ( 'ec' );
	
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id );
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
	
		$keyword = $this->get_source ( 'keyword' );
		$keyword = addslashes ( $keyword );
		$this->load->model ( 'hotel/Hotel_check_model' );
		$result = $this->Hotel_model->search_hotel_front ( $this->inter_id, array (
				'keyword' => $keyword,
				'city' => $city,
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'extra_condition' => $extra_condition
		) );
		$data ['city'] = $city;
		$data ['keyword'] = $keyword;
		$data ['extra_condition'] = $extra_condition;
		$data ['hotel_ids'] = '';
		if (! empty ( $result )) {
			foreach ( $result as $rt ) {
				$data ['hotel_ids'] .= ',' . $rt->hotel_id;
			}
			$data ['hotel_ids'] = substr ( $data ['hotel_ids'], 1 );
			$this->load->model ( 'hotel/Hotel_check_model' );
			$result = $this->Hotel_check_model->get_extra_info ( $this->inter_id, $result, array (
					'hotel_service',
					'lowest_price',
					'search_icons',
					'comment_data'
			), array (
					'startdate' => $data ['startdate'],
					'enddate' => $data ['enddate'],
					'member_level' => $this->member_lv
			) );
			$data ['result'] = $result;
				
			$this->load->model ( 'hotel/Hotel_config_model' );
			$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
					'HOTEL_RESULT_ICON'
			) );
			$data ['icons_set'] = array ();
			if (! empty ( $config_data ['HOTEL_RESULT_ICON'] )) {
				$data ['icons_set'] = json_decode ( $config_data ['HOTEL_RESULT_ICON'], TRUE );
			}
		}
		// $this->load->model ( 'common/Record_model' );
		// $this->Record_model->visit_log ( array (
		// 'openid' => $this->openid,
		// 'inter_id' => $this->inter_id,
		// 'title' => '搜索结果',
		// 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
		// 'des' => "城市：$city,关键字：$keyword"
		// ) );
		// 可预订前几天的房型
		$data ['pre_sp_date'] = $this->preSpDate ();
		$this->out_put_msg ( 1, '', $data ,'hotel/sresult');
	}
	
	function return_lowest_price() {
		$this->load->model ( 'hotel/Order_model' );
		$hotel_ids = $this->input->get ( 'hs' );
		$startdate = $this->input->get ( 's' );
		$enddate = $this->input->get ( 'e' );
		$lowests = $this->Order_model->get_lowest_price ( $this->inter_id, array (
				'startdate' => $startdate,
				'enddate' => $enddate,
				'hotel_ids' => $hotel_ids 
		) );
		echo json_encode ( $lowests );
	}
	function room_state() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Gallery_model' );
		$hotel_id = $this->get_source ( 'hotel_id' );
		$hotel_id = $this->Hotel_model->get_a_hotel_id ( $this->inter_id, $hotel_id, true );
		$data ['hotel'] = $this->Hotel_model->get_hotel_detail ( $this->inter_id, $hotel_id, array (
				'img_type' => array (
						'hotel_service',
						'hotel_lightbox' 
				),
				'icon_type' => array (
						'ICONS_IMG_SERACH_RESULT' 
				) 
		) );
		$gallery_count = $this->Gallery_model->get_gallery_count ( $this->inter_id, $data ['hotel'] ['hotel_id'] );
		$data ['gallery_count'] = 0;
		foreach ( $gallery_count as $gc ) {
			$data ['gallery_count'] += $gc ['g_nums'];
		}
		$collect_check = $this->Hotel_model->get_type_mark ( array (
				'inter_id' => $this->inter_id,
				'mark_name' => $hotel_id,
				'openid' => $this->openid,
				'mark_type' => 'hotel_collection' 
		) );
		$data ['collect_id'] = empty ( $collect_check ) ? 0 : $collect_check ['mark_id'];
		$startdate = $this->get_source ( 'startdate' );
		$enddate = $this->get_source ('enddate');
		$this->load->model ( 'hotel/Order_model' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id );
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		$rooms = $this->Hotel_model->get_hotel_rooms ( $this->inter_id, $hotel_id, 1 );
		
		$rooms = $this->Hotel_model->get_rooms_detail ( $this->inter_id, $hotel_id, $rooms, array (
				'data' => 'value',
				'img_type' => 'hotel_room_service' 
		) );
		
		$condit = array (
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'openid' => $this->openid,
				'member_level' => $this->member_lv 
		);
		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}
		
		$data ['countday'] = ceil ( (strtotime ( $data ['enddate'] ) - strtotime ( $data ['startdate'] )) / 86400 );
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), 'pmsa' );
		
		$data ['rooms'] = $this->pmsa->get_rooms_change ( $rooms, array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), $condit, true );
		
		$this->load->helper('string');
		foreach ($data ['rooms'] as $room_key=>$r){
		    if (!empty($r['state_info'])){
		        foreach ($r['state_info'] as $state_key=>$state){
		            $data ['rooms'][$room_key]['state_info'][$state_key]['des']=htmlblank_replace($state['des']);
		        }
		    }
		}
		
		$this->load->model ( 'hotel/Comment_model' );
		$data ['t_t'] = $this->Comment_model->get_hotel_comment_counts ( $this->inter_id, $hotel_id, 1 );
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
				'HOTEL_RESULT_ICON' 
		) );
		$data ['icons_set'] = array ();
		if (! empty ( $config_data ['HOTEL_RESULT_ICON'] )) {
			$data ['icons_set'] = json_decode ( $config_data ['HOTEL_RESULT_ICON'], TRUE );
		}
		
		// Visit log
		// $this->load->model ( 'common/Record_model' );
		// $this->Record_model->visit_log ( array (
		// 'openid' => $this->openid,
		// 'inter_id' => $this->session->userdata ( 'inter_id' ),
		// 'title' => $data ['hotel'] ['name'],
		// 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
		// 'visit_time' => date ( 'Y-m-d H:i:s' ),
		// 'des' => ''
		// ) );
		// $this->Hotel_model->add_front_mark ( array (
		// 'inter_id' => $this->inter_id,
		// 'openid' => $this->openid,
		// 'mark_name' => $hotel_id,
		// 'mark_type' => 'hotel_visited',
		// 'mark_title' => $data ['hotel'] ['name'],
		// 'mark_link' => site_url ( 'hotel/hotel/index?id=' . $this->inter_id . '&h=' . $hotel_id )
		// ) );
		// $this->load->model ( 'plugins/Advert_model' );
		// $data ['foot_ads'] = $this->Advert_model->get_hotel_ads ( $this->inter_id, $hotel_id, 'index_foot', 1, 1 );
		
		// 开始日期限定
		$data ['pre_sp_date'] = $this->preSpDate ();
		
		//暂时将轮播变为单图，过审后开放
		$data['swiper_show'] = false;
		$data['image_show'] = true;
		
		$this->out_put_msg ( 1, '', $data ,'hotel/room_state');
	}
	function return_more_room() {
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$hotel_id = $this->get_source('hotel_id');
		$startdate = $this->get_source( 'startdate' );
		$enddate = $this->get_source( 'enddate' );
		$protrol_code = $this->get_source( 'protrol_code' );
		if (! empty ( $protrol_code )) {
			$protrol_price_code = $this->Order_model->get_protrol_price_code ( $this->inter_id, $hotel_id, $protrol_code );
		}
		$rooms = $this->Hotel_model->get_hotel_rooms ( $this->inter_id, $hotel_id, 1 );
		$rooms = $this->Hotel_model->get_rooms_detail ( $this->inter_id, $hotel_id, $rooms, array (
				'data' => 'value',
				'img_type' => 'hotel_room_service' 
		) );
		$this->load->model ( 'hotel/Order_model' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id );
		$startdate = $date_check [0];
		$enddate = $date_check [1];
		$errmsg = '';
		$condit = array (
				'startdate' => $startdate,
				'enddate' => $enddate,
				'is_ajax' => 1,
				'openid' => $this->openid,
				'member_level' => $this->member_lv 
		);
		// if ( $this->member_lv !='') {
		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}
		// }
		if (! empty ( $protrol_price_code )) {
			$condit ['extra_price_code'] = $protrol_price_code;
			$condit ['price_type'] = array (
					'protrol' 
			);
		} else if (! empty ( $protrol_code )) {
			$errmsg = '木有这个协议代码哦';
		}
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), 'pmsa' );
		$rooms = $this->pmsa->get_rooms_change ( $rooms, array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), $condit, true );
		$this->out_put_msg(1,$errmsg,$rooms,'hotel/return_more_room');
	}
	function hotel_detail() {
		$data = $this->common_data;
// 		if ($this->input->get ( 'h' ))
// 			$this->session->set_userdata ( array (
// 					$this->inter_id . '_room_hotel_id' => $this->input->get ( 'h' ) 
// 			) );
// 		$this->hotel_id = $this->session->userdata ( $this->inter_id . '_room_hotel_id' );
		$hotel_id=$this->get_source('hotel_id');
		$this->load->model ( 'hotel/Hotel_model' );
		$data ['hotel'] = $this->Hotel_model->get_hotel_detail ( $this->inter_id, $hotel_id, array (
				'img_type' => array (
						'hotel_service',
						'hotel_lightbox' 
				) 
		) );
		$this->load->helper('string');
		$data ['hotel']['intro']=htmlblank_replace($data ['hotel']['intro']);
		$data ['hotel']['short_intro']=htmlblank_replace($data ['hotel']['short_intro']);
		// Visit log
// 		$this->load->model ( 'common/Record_model' );
// 		$this->Record_model->visit_log ( array (
// 				'openid' => $this->openid,
// 				'inter_id' => $this->session->userdata ( 'inter_id' ),
// 				'title' => $data ['hotel'] ['name'],
// 				'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
// 				'visit_time' => date ( 'Y-m-d H:i:s' ),
// 				'des' => '查看酒店详情' 
// 		) );
		$this->out_put_msg(1,'',$data,'hotel/hotel_detail');
	}
	function bookroom() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$hotel_id = intval ( $this->get_source ( 'hotel_id' ) );
		$room_id = intval ( $this->get_source ( 'room_id' ) );
		$data ['price_codes'] = $this->get_source ( 'price_codes' );
		$data ['price_type'] = $this->get_source ( 'price_type' );
		$startdate = $this->get_source ( 'startdate' );
		$enddate = $this->get_source ( 'enddate' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id );
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		$data ['hotel_id'] = $hotel_id;
		$datas = $this->get_source ( 'datas' );
		$price_codes = $data ['price_codes'];
		$price_type = $data ['price_type'];
		if (empty ( $datas ) || empty ( $price_codes )) {
			$this->out_put_msg ( 1, '无可订房间' );
		}
		$data ['hotel'] = $this->Hotel_model->get_hotel_detail ( $this->inter_id, $hotel_id, array (
				'img_type' => array (
						'hotel_service',
						'hotel_lightbox' 
				) 
		) );
		$data_arr = $datas;
		foreach ( $data_arr as $key => $value ) {
			if ($value == 0) {
				unset ( $data_arr [$key] );
			}
		}
		$data ['room_list'] = $this->Hotel_model->get_rooms_detail ( $this->inter_id, $hotel_id, array_keys ( $data_arr ), array (
				'number_realtime' => array (
						's' => $data ['startdate'],
						'e' => $data ['enddate'] 
				),
				'data' => 'key',
				'img_type' => array (
						'hotel_room_service' 
				) 
		) );
		$condit = array (
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'nums' => $data_arr,
				'openid' => $this->openid,
				'member_level' => $this->member_lv,
				'hotel_id' => $hotel_id 
		);
		// if ( $this->member_lv !='') {
		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}
		
		// }
		$protrol_code = $this->get_source ( 'protrol_code' );
		if (! empty ( $protrol_code ) && array_key_exists ( 'protrol', $price_type )) {
			$protrol_price_code = $this->Order_model->get_protrol_price_code ( $this->inter_id, $hotel_id, $protrol_code );
		}
		if (! empty ( $protrol_price_code )) {
			$price_codes [] = $protrol_price_code;
			$condit ['price_type'] = array (
					'protrol' 
			);
		}
		$condit ['price_codes'] = implode ( ',', $price_codes );
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), 'pmsa' );
		
		$data ['rooms'] = $this->pmsa->get_rooms_change ( $data ['room_list'], array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), $condit, true );
		
		$data ['first_room'] = current ( $data ['rooms'] );
		$data ['first_state'] = $data ['first_room'] ['state_info'] [$price_codes [$data ['first_room'] ["room_info"] ['room_id']]];
		if (empty ( $data ['first_state'] )) {
			$this->out_put_msg ( 1, '房间不可订' );
		}
		$data ['total_price'] = 0;
		$data ['total_oprice'] = 0;
		$no_pay_ways = array ();
		foreach ( $data ['rooms'] as $k => $item ) {
			$code_price = $item ['state_info'] [$price_codes [$k]];
			$data ['total_price'] += $code_price ['total_price'];
			$data ['total_oprice'] += empty ( $code_price ['total_oprice'] ) ? 0 : $code_price ['total_oprice'];
			$no_pay_ways = empty ( $code_price ['condition'] ['no_pay_way'] ) ? $no_pay_ways : array_merge ( $no_pay_ways, $code_price ['condition'] ['no_pay_way'] );
		}
		
		// @author lGh 2016-03-14
		$data ['athour'] = 0;
		if ($data ['first_state'] ['price_type'] == 'athour') {
			$data ['athour'] = 1;
		}
		$this->load->model ( 'hotel/Service_model' );
		$this->load->model ( 'hotel/Price_code_model' );
		if (! empty ( $data ['first_state'] ['add_service_set'] )) {
			$data ['services'] = $this->Service_model->replace_service ( $this->inter_id, array (
					'service_type' => 'hotel_order',
					'status' => 1,
					'add_occasion' => array (
							'hotel_order_before',
							'hotel_order_both' 
					) 
			), $data ['first_state'] ['add_service_set'] );
			$data ['services'] = $this->Service_model->classify_service ( $data ['services'] );
		}
		
		// var_dump($data ['first_state'] ['condition'] ['book_time']);
		
		if (! empty ( $data ['first_state'] ['condition'] ['book_time'] )) {
			// $data ['first_state'] ['condition'] ['book_time'] = date('20160328');
			// if ( isset ( $data ['first_state'] ['condition'] ['book_time'] )) {
			// $min_min = isset ( $data ['first_state'] ['condition'] ['min_min'] ) ? $data ['first_state'] ['condition'] ['min_min'] * 60 : 0;
			$min_min = empty ( $data ['first_state'] ['condition'] ['min_min'] ) ? 0 : $data ['first_state'] ['condition'] ['min_min'] * 60;
			$order_times = $this->Price_code_model->get_book_time ( $data ['first_state'] ['condition'] ['book_time'], $min_min );
			$data ['first_state'] ['condition'] ['book_times'] = $order_times ['book_times'];
			$data ['first_state'] ['condition'] ['last_time'] = $order_times ['last_time'];
		}
		if ($data ['athour'] == 1) {
			if (! empty ( $data ['services'] ['add_time'] )) {
				// foreach ( $data ['services'] ['add_time'] as $addk => $addv ) {
				$data ['add_time_service'] = current ( $data ['services'] ['add_time'] );
				$begin_time = empty ( $data ['first_state'] ['condition'] ['last_time'] ) ? date ( 'YmdH00', strtotime ( '+ 1 hour', time () ) ) : $data ['first_state'] ['condition'] ['last_time'];
				$max_time = empty ( $data ['first_state'] ['condition'] ['book_time'] ['e'] ) ? 0 : $data ['first_state'] ['condition'] ['book_time'] ['e'];
				$data ['add_time_service'] ['add_times'] = $this->Service_model->check_service_rule ( 'add_time', array (
						'begin_time' => $begin_time,
						'max_time' => $max_time,
						'max_num' => $data ['add_time_service'] ['max_num'] 
				) );
				// }
			}
		}
		// var_dump($data ['add_time_service']);
		$data ['room_count'] = array_sum ( $data_arr );
		$this->load->model ( 'pay/Pay_model' );
		$this->load->helper ( 'date' );
		$pay_days = get_day_range ( $data ['startdate'], $data ['enddate'], 'array' );
		array_pop ( $pay_days );
		$data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'status' => 1,
				'exclude_type' => $no_pay_ways,
				'check_day' => 1,
				'hotel_ids' => $hotel_id,
				'not_show'=>1 
		), $pay_days );
		$count=count($data['pay_ways']);
		$wxapp_paytype=array('weixin','daofu','point','bonus','balance');
		for ($i=0;$i<$count;$i++){
			if (!in_array($data['pay_ways'][$i]->pay_type,$wxapp_paytype)){
				unset($data['pay_ways'][$i]);
			}
		}
		$data ['pay_ways']=array_values($data ['pay_ways']);
		
		// @Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
		$point_condit = array (
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'nums' => current ( $data_arr ),
				'openid' => $this->openid,
				'member_level' => $this->member_lv,
				'room_id' => key ( $data_arr ),
				'price_code' => current ( $price_codes ),
				'bonus' => isset ( $data ['member']->bonus ) ? $data ['member']->bonus : 0,
				'hotel_id' => $hotel_id,
				'total_price' => $data ['total_price'],
				'roomnums' => $data ['room_count'],
				'paytype' => empty ( $data ['pay_ways'] ) ? '' : $data ['pay_ways'] [0]->pay_type 
		);
		
		$point_consum_set = $this->Member_model->get_point_consum_rate ( $this->inter_id, $this->member_lv, 'room', $member_privilege, $point_condit );
		
		// @Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
		$data ['point_consum_set'] = $point_consum_set ['part_set'];
		$data ['point_consum_rate'] = $point_consum_set ['consum_rate'];
		
		$data ['source_data'] = json_encode ( $data_arr );
		$last_orders = $this->Order_model->get_last_order ( $this->inter_id, $this->openid, 1, false );
		$data ['member'] = $this->pub_pmsa->check_openid_member ( $this->inter_id, $this->openid, array (
				'create' => TRUE,
				'update' => TRUE 
		) );
		empty ( $last_orders ) ?  : $data ['last_order'] = $last_orders [0];
		
		// @author lGh 2016-4-6 21:34:15 积分换房
		$countday = ceil ( (strtotime ( $data ['enddate'] ) - strtotime ( $data ['startdate'] )) / 86400 );
		$avg_price = floatval ( $data ['total_price'] / $countday );
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', $hotel_id, array (
				'PRICE_EXCHANGE_POINT',
				'BANCLANCE_COMSUME_CODE_NEED' 
		) );
		if (! empty ( $config_data ['PRICE_EXCHANGE_POINT'] )) {
			$this->load->model ( 'hotel/Member_model' );
			$data ['point_exchange'] = $this->Member_model->room_point_exchange ( $this->inter_id, $data ['member'], array (
					'countday' => $countday,
					'price' => $avg_price,
					'config' => $config_data ['PRICE_EXCHANGE_POINT'],
					'roomnums' => 1 
			) );
		}
		
		// 储值消费码
		$data ['banlance_code'] = 0;
		if (! empty ( $config_data ['BANCLANCE_COMSUME_CODE_NEED'] ) && $config_data ['BANCLANCE_COMSUME_CODE_NEED'] == 1) {
			$data ['banlance_code'] = 1;
		}
		
		// 获取券的额外参数
		$data ['extra_para'] = array ();
		$first_room = current ( $data ['room_list'] );
		if (! empty ( $first_room ['webser_id'] )) {
			$data ['extra_para'] ['web_room_id'] = $first_room ['webser_id'];
			if (! empty ( $data ['first_state'] ['extra_info'] ['pms_code'] )) {
				$data ['extra_para'] ['pms_code'] = $data ['first_state'] ['extra_info'] ['pms_code'];
			}
		}
		$data ['extra_para'] = json_encode ( $data ['extra_para'] );
		if(isset($data['hotel']['book_policy'])){
			$data['hotel']['book_policy'] = "无";
		}
		$this->out_put_msg ( 1, '', $data,'hotel/bookroom' );
	}
	function saveorder() {
// 		var_dump($this->get_source());exit;
		// Visit log
// 		$now = date ( 'Y-m-d H:i:s' );
		
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$this->load->model ( 'hotel/Hotel_config_model' );
		$startdate = date ( 'Ymd', strtotime ( $this->get_source( 'startdate' ) ) );
		$enddate = date ( 'Ymd', strtotime ( $this->get_source ( 'enddate' ) ) );
		$hotel_id = intval ( $this->get_source ( 'hotel_id' ) );
		$price_codes =  $this->get_source ( 'price_codes' );
		$datas = $this->get_source ( 'datas' );
		$price_type =  $this->get_source ( 'price_type' );
		$coupons =   $this->get_source ( 'coupons' );
		$roomnos =   $this->get_source ( 'roomnos' );
		// @author lGh 加服务配置
		$add_service = $this->get_source ( 'add_service' ) ;
		$consume_code = $this->get_source ( 'consume_code' );
		
		$name = htmlspecialchars ( $this->get_source ( 'name' ) );
		$tel = htmlspecialchars ( $this->get_source ( 'tel' ) );
		$paytype = htmlspecialchars ( $this->get_source ( 'paytype' ) );
		$bonus = intval ( $this->get_source ( 'bonus' ) );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', $hotel_id, array (
				'HOTEL_ORDER_ENSURE_WAY',
				// 'HOTEL_IS_PMS',
				'PMS_AFT_SUBMIT',
				'PRICE_EXCHANGE_POINT',
				'BANCLANCE_COMSUME_CODE_NEED',
				'HOTEL_BONUS_CONFIG',
				'HOTEL_BALANCE_PART_PAY',
				'PMS_POINT_REDUCE_WAY' 
		) );
		$info = array ();
		
		$this->load->helper ( 'string' );
		$name = trim_space ( $name );
		
		// 判断开始日期是否可以预订
		$sp_pre_date = $this->preSpDate ();
		$enable_start = date ( 'Ymd', time () + ($sp_pre_date * 86400) );
		
		if (! $datas || ! $name || ! $tel || ! strtotime ( $this->get_source ( 'startdate' ) ) || ! strtotime ( $this->get_source ( 'enddate' ) ) || $startdate < $enable_start || $enddate <= $startdate) {
			$info ['s'] = 0;
			$info ['errmsg'] = '请填写有效信息';
			$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
			exit ();
		}
		if (! empty ( $config_data ['BANCLANCE_COMSUME_CODE_NEED'] ) && $config_data ['BANCLANCE_COMSUME_CODE_NEED'] == 1 && $paytype == 'balance') {
			if (empty ( $consume_code )) {
				$info ['s'] = 0;
				$info ['errmsg'] = '请填写消费密码';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder',2);
				exit ();
			}
		} else {
			$consume_code = '';
		}
		$data_arr = $datas;
		foreach ( $data_arr as $key => $value ) {
			if ($value == 0) {
				unset ( $data_arr [$key] );
			}
		}
		$room_list = $this->Hotel_model->get_rooms_detail ( $this->inter_id, $hotel_id, array_keys ( $data_arr ), array (
				'number_realtime' => array (
						's' => $startdate,
						'e' => $enddate 
				),
				'data' => 'key' 
		) );
		$condit = array (
				'startdate' => $startdate,
				'enddate' => $enddate,
				'price_codes' => implode ( ',', $price_codes ),
				'nums' => $data_arr,
				'openid' => $this->openid,
				'member_level' => $this->member_lv 
		);
		// if ( $this->member_lv !='') {
		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}
		// }
		if (! empty ( $price_type )) {
			$condit ['price_type'] = array_keys ( $price_type );
		}
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), 'pmsa' );
		$rooms = $this->pmsa->get_rooms_change ( $room_list, array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id 
		), $condit, true );
		$order_data = array ();
		$order_additions = array ();
		$order_data ['price'] = 0;
		$no_pay_ways = array ();
		$order_data ['roomnums'] = array_sum ( $data_arr );
		
		$subs = array ();
		$room_codes = array ();
		if (empty ( $rooms )) {
			$info ['s'] = 0;
			$info ['errmsg'] = '无可订房间！';
			$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
			exit ();
		}
		
		$related_coupons = array ();
		
		foreach ( $rooms as $k => $rm ) {
			$code_price = $rm ['state_info'] [$price_codes [$k]];
			
			// @Editor lGh 2016-7-10 11:39:46 券关联
			if (! empty ( $code_price ['coupon_condition'] ['couprel'] )) {
				$related_coupons [$code_price ['coupon_condition'] ['couprel']] = 1;
			}
			
			// @Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
			if (! empty ( $code_price ['coupon_condition'] ['no_coupon'] ) && ! empty ( $coupons )) {
				$info ['s'] = 0;
				$info ['errmsg'] = '此价格不能用券！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
			if (! empty ( $code_price ['bonus_condition'] ['no_part_bonus'] ) && ! empty ( $bonus )) {
				$info ['s'] = 0;
				$info ['errmsg'] = '此价格不能用积分！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
			if (! empty ( $code_price ['bonus_condition'] ['poc'] ) && (! empty ( $bonus ) && ! empty ( $coupons ))) {
				$info ['s'] = 0;
				$info ['errmsg'] = '此价格不能同时使用积分与优惠券！请重新选择';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
			
			$room_info = $rm ['room_info'];
			$room_codes [$room_info ['room_id']] ['code'] ['price_type'] = $code_price ['price_type'];
			if (! empty ( $consume_code )) {
				$room_codes [$room_info ['room_id']] ['room'] ['consume_code'] = $consume_code;
			}
			$room_codes [$room_info ['room_id']] ['code'] ['extra_info'] = empty ( $code_price ['extra_info'] ) ? '' : $code_price ['extra_info'];
			$room_codes [$room_info ['room_id']] ['room'] ['webser_id'] = $rm ['room_info'] ['webser_id'];
			if ($code_price ['book_status'] != 'available') {
				$info ['s'] = 0;
				$info ['errmsg'] = '房间数不足！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
			if (! empty ( $roomnos [$k] )) {
				$tmp_nos = array_keys ( $room_info ['number_realtime'] );
				foreach ( $roomnos [$k] as $rk => $no ) {
					if (! in_array ( $rk, $tmp_nos )) {
						$info ['s'] = 0;
						$info ['errmsg'] = $room_info ['name'] . ' 的房号' . $no . '已被选！';
						$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
						exit ();
					}
				}
			}
			
			$order_data ['price'] += $code_price ['total_price'];
			$no_pay_ways = empty ( $code_price ['condition'] ['no_pay_way'] ) ? $no_pay_ways : array_merge ( $no_pay_ways, $code_price ['condition'] ['no_pay_way'] );
			
			$subs [$room_info ['room_id']] ['allprice'] = $code_price ['allprice'];
			$subs [$room_info ['room_id']] ['roomname'] = $room_info ['name'];
			$subs [$room_info ['room_id']] ['iprice'] = $code_price ['total'];
			$subs [$room_info ['room_id']] ['price_code'] = $price_codes [$k];
			$subs [$room_info ['room_id']] ['price_code_name'] = $code_price ['price_name'];
		}
		$order_additions ['room_codes'] = json_encode ( $room_codes );
		$member = $this->pub_pmsa->check_openid_member ( $this->inter_id, $this->openid, array (
				'create' => TRUE,
				'update' => TRUE 
		) );
		
		if ($paytype == 'bonus') {
			// @author lGh 2016-4-6 21:34:15 积分换房
			$countday = ceil ( (strtotime ( $enddate ) - strtotime ( $startdate )) / 86400 );
			$avg_price = floatval ( $order_data ['price'] / ($countday * $order_data ['roomnums']) );
			// $this->load->model ( 'hotel/Hotel_config_model' );
			// $config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', $hotel_id, 'PRICE_EXCHANGE_POINT');
			if (! empty ( $config_data ['PRICE_EXCHANGE_POINT'] )) {
				$this->load->model ( 'hotel/Member_model' );
				$point_exchange = $this->Member_model->room_point_exchange ( $this->inter_id, $member, array (
						'countday' => $countday,
						'price' => $avg_price,
						'config' => $config_data ['PRICE_EXCHANGE_POINT'],
						'roomnums' => $order_data ['roomnums'] 
				) );
			}
			if (empty ( $point_exchange ) || $point_exchange ['can_exchange'] == 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '积分不足兑换！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			} else {
				$order_additions ['point_favour'] = $order_data ['price'];
				$order_additions ['point_used'] = 1;
				$order_additions ['point_used_amount'] = $point_exchange ['point_need'];
				$order_data ['price'] -= $order_additions ['point_favour'];
				$bonus_paid = 1;
			}
		}
		
		// 使用积分
		if (! empty ( $bonus ) && ! empty ( $member ) && empty ( $bonus_paid )) {
			// @Editor lGh 2016-5-27 19:25:23 增加积分支付方式
			if ($paytype == 'point') {
				$info ['s'] = 0;
				$info ['errmsg'] = '您选择了积分支付，不能再使用积分抵扣';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
			
			if ($member->bonus < $bonus) {
				$info ['s'] = 0;
				$info ['errmsg'] = '积分不足！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
			
			// @Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
			$point_condit = array (
					'startdate' => $startdate,
					'enddate' => $enddate,
					'nums' => $order_data ['roomnums'],
					'openid' => $this->openid,
					'member_level' => $this->member_lv,
					'room_id' => key ( $data_arr ),
					'price_code' => current ( $price_codes ),
					'bonus' => $this->common_data ['member']->bonus,
					'hotel_id' => $hotel_id,
					'used' => $bonus 
			);
			
			$this->load->model ( 'hotel/Member_model' );
			$point_consum_rate = $this->Member_model->get_point_consum_rate ( $this->inter_id, $this->member_lv, 'room', $member_privilege, $point_condit );
			if (! empty ( $point_consum_rate )) {
				if ($point_consum_rate ['s'] != 0 && ! empty ( $point_consum_rate ['consum_rate'] )) {
					$order_additions ['point_favour'] = $bonus * $point_consum_rate ['consum_rate'];
					$order_additions ['point_used'] = 1;
					$order_additions ['point_used_amount'] = $bonus;
					$order_data ['price'] -= $order_additions ['point_favour'];
				} else if ($point_consum_rate ['s'] == 0) {
					$info ['s'] = 0;
					$info ['errmsg'] = $point_consum_rate ['errmsg'];
					$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
					exit ();
				}
			}
			if ($order_data ['price'] < 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '不能用那么多积分哦！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
		}
		
		// 使用代金券
		$coupon_rel = array ();
		if ((! empty ( $related_coupons ) || ! empty ( $coupons )) && empty ( $bonus_paid )) {
			$this->load->model ( 'hotel/Coupon_model' );
			$params = array ();
			$params ['days'] = round ( strtotime ( $enddate ) - strtotime ( $startdate ) ) / 86400;
			$params ['amount'] = $order_data ['price'];
			$params ['hotel'] = $hotel_id;
			$params ['rooms'] = $order_data ['roomnums'];
			$params ['product_num'] = $order_data ['roomnums'];
			$params ['product'] = array_keys ( $data_arr );
			$params ['level'] = $this->member_lv;
			reset ( $data_arr );
			$params ['category'] = key ( $data_arr );
			$params ['price_code'] = current ( $price_codes );
			$params ['paytype'] = $paytype;
			$params ['order_items'] = $subs;
			
			// 获取券的额外参数
			$params ['startdate'] = $startdate;
			$params ['enddate'] = $enddate;
			$params ['extra_para'] = array ();
			$first_room = current ( $room_list );
			if (! empty ( $first_room ['webser_id'] )) {
				$params ['extra_para'] ['web_room_id'] = $first_room ['webser_id'];
				if (! empty ( $room_codes [$first_room ['room_id']] ['code'] ['extra_info'] ['pms_code'] )) {
					$params ['extra_para'] ['pms_code'] = $room_codes [$first_room ['room_id']] ['code'] ['extra_info'] ['pms_code'];
				}
			}
			$coupon_check = $this->Coupon_model->check_coupon_using ( $this->inter_id, $this->openid, $params, array_keys ( $coupons ), $coupons, $related_coupons );
			
			if ($coupon_check ['s'] == 0) {
				$this->out_put_msg(1,$coupon_check['errmsg'],$coupon_check,'hotel/saveorder');
			}
			$order_additions ['coupon_favour'] = $coupon_check ['coupon_amount'];
			$order_additions ['coupon_des'] = json_encode ( $coupon_check ['coupon_info'], JSON_UNESCAPED_UNICODE );
			$order_additions ['coupon_used'] = 1;
			if (! empty ( $coupon_check ['coupon_rel'] )) {
				$coupon_rel = $coupon_check ['coupon_rel'];
			}
			$order_data ['price'] -= $order_additions ['coupon_favour'];
			if ($order_data ['price'] < 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '不能用那么多券哦！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
		}
		
		// @Editor lGh 2016-5-27 19:21:22 增加积分支付方式 bonus为积分兑换，兑换后订单价格为0，point为积分支付，类似储值支付
		if ($paytype == 'point') {
			$countday = ceil ( (strtotime ( $enddate ) - strtotime ( $startdate )) / 86400 );
			$this->load->model ( 'hotel/Member_model' );
			$point_exchange = $this->Member_model->point_pay_check ( $this->inter_id, $member, array (
					'countday' => $countday,
					'price' => $order_data ['price'],
					'roomnums' => $order_data ['roomnums'],
					'hotel_id' => $hotel_id 
			) );
			if (empty ( $point_exchange ) || $point_exchange ['can_exchange'] == 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '积分不足支付！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			} else {
				
				if ($this->inter_id == 'a421641095') { // 定制，碧桂园需要拥有的积分大于所需的200才可兑换
					if ($member->bonus - $point_exchange ['point_need'] < 200) {
						$info ['s'] = 0;
						$info ['errmsg'] = '您的积分需比订单积分多200积分才可预订！';
						$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
						exit ();
					}
				}
				
				$order_additions ['point_used'] = 1;
				$order_additions ['point_used_amount'] = $point_exchange ['point_need'];
				$point_paid = 1;
			}
		}
		
		// 储值支付
		if ($paytype == 'balance') {
			if (empty ( $member ) || $member->balance < $order_data ['price']) {
				$info ['s'] = 0;
				$info ['errmsg'] = '余额不足！';
				$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
				exit ();
			}
		}
		
		if ($order_data ['price'] <= 0 && empty ( $bonus_paid )) {
			$info ['s'] = 0;
			$info ['errmsg'] = '价格错误！';
			$this->out_put_msg(1,$info['errmsg'],$info,'hotel/saveorder');
			exit ();
		}
		
		// 保存订单
		$order_data ['hotel_id'] = $hotel_id;
		$order_data ['inter_id'] = $this->inter_id;
		$order_data ['openid'] = $this->openid;
		$order_data ['name'] = $name;
		$order_data ['tel'] = $tel;
		$order_data ['startdate'] = $startdate;
		$order_data ['enddate'] = $enddate;
		$order_data ['status'] = 0;
		
		$order_data ['channel'] = 'wxapp';
		
		$order_data ['paytype'] = $paytype; // 支付类型
		$this->load->model ( 'pay/Pay_model' );
		$pre_pay = $this->Pay_model->is_online_pay ( $order_data ['paytype'] );
		if ($pre_pay == 1) {
			$order_data ['status'] = 9;
		} else if (! empty ( $config_data ['HOTEL_ORDER_ENSURE_WAY'] ) && $config_data ['HOTEL_ORDER_ENSURE_WAY'] == 'instant') {
			$order_data ['status'] = 1;
		}
		
		if ($member)
			$order_data ['member_no'] = $member->mem_card_no;
		$info = $this->Order_model->create_order ( $this->inter_id, array (
				'main_order' => $order_data,
				'order_additions' => $order_additions,
				'coupon_rel' => $coupon_rel 
		), $data_arr, $subs, $roomnos );
		
		if ($info ['s'] == 1) {
			
			if ($pre_pay != 1) {
				$msg = $this->pmsa->order_submit ( $this->inter_id, $info ['orderid'], array (
						'room_codes' => $room_codes 
				) );
				if ($msg ['s'] == 0) {
					$this->Order_model->handle_order ( $this->inter_id, $info ['orderid'], 10, $this->openid ); // pms下单失败，退回
					$info = $msg;
				} else {
					$this->Order_model->handle_order ( $this->inter_id, $info ['orderid'], 'ss' );
					if ($order_data ['status'] == 1) {
						$this->Order_model->handle_order ( $this->inter_id, $info ['orderid'], $order_data ['status'], $this->openid );
					}
				}
			} else {
				if ((empty ( $config_data ['PMS_AFT_SUBMIT'] ) || $config_data ['PMS_AFT_SUBMIT'] == 0) || (! empty ( $config_data ['PMS_POINT_REDUCE_WAY'] ) && $config_data ['PMS_POINT_REDUCE_WAY'] == 'after' && $paytype == 'point')) {
					$msg = $this->pmsa->order_submit ( $this->inter_id, $info ['orderid'], array (
							'room_codes' => $room_codes 
					) );
					if ($msg ['s'] == 0) {
						$this->Order_model->handle_order ( $this->inter_id, $info ['orderid'], 10, $this->openid ); // pms下单失败，退回
						$info = $msg;
					} else {
						$this->Order_model->handle_order ( $this->inter_id, $info ['orderid'], 'ss' );
					}
				}
			}
		}
		$errmsg=isset($info['errmsg'])?$info['errmsg']:'';
		$this->out_put_msg(1,$errmsg,$info,'hotel/saveorder');
		
		// Visit log
// 		$this->load->model ( 'common/Record_model' );
// 		$this->Record_model->visit_log ( array (
// 				'openid' => $this->openid,
// 				'inter_id' => $this->session->userdata ( 'inter_id' ),
// 				'title' => '提交订单',
// 				'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
// 				'visit_time' => date ( 'Y-m-d H:i:s' ),
// 				'des' => $now . '-' . getIp () 
// 		) );
	}
	function add_hotel_collection() {
		//$hotel_id = $this->input->get ( 'hid' );
		$hotel_id = $this->get_source ( 'hid' );
		$mark_title = $this->get_source( 'hname' );
		$data = array (
				'mark_name' => $hotel_id,
				'inter_id' => $this->inter_id,
				'openid' => $this->openid,
				'mark_type' => 'hotel_collection',
				'mark_title' => $mark_title,
				'mark_link' => site_url ( 'hotel/hotel/index' ) . '?id=' . $this->inter_id . '&h=' . $hotel_id 
		);
		$this->load->model ( 'hotel/Hotel_model' );
		$insert_id = $this->Hotel_model->add_front_mark ( $data );
				
		if($insert_id > 0){
			
			//current_status 1 已收藏
			$info = array(
				'current_status' => 1	
					
			);
			$this->out_put_msg(1,"收藏成功",$info);
			
		}else{
			
			$this->out_put_msg(1001,"操作超时~");
			
		}
		
		
		
	}
	function clear_visited_hotel() {
		$this->load->model ( 'hotel/Hotel_model' );
		$this->Hotel_model->update_mark_status ( $this->inter_id, $this->openid, 2, 'hotel_visited', 'mark_type' );
		echo 1;
	}
	function cancel_one_mark() {
		$mark_id = $this->get_source( 'mid' );
		$this->load->model ( 'hotel/Hotel_model' );
		$this->Hotel_model->update_mark_status ( $this->inter_id, $this->openid, 2, $mark_id, 'mark_id' );
		
		//current_status 1当前未收藏
		$info = array(
				'current_status' => 0
					
		);
		
		$this->out_put_msg(1,"取消收藏成功",$info);
		
		//echo 1;
	}
	function orderdetail() {
		$data = $this->common_data;
		$oid = $this->get_source ( 'orderid' );
		$this->load->model ( 'hotel/Order_model' );
		$list = $this->Order_model->get_main_order ( $this->inter_id, array (
				'orderid' => $oid,
				'openid' => $this->openid,
				'member_no' => $this->member_no,
				'idetail' => array (
						'i' 
				) 
		) );
		if ($list) {
			$list = $list [0];
			$flag = 1;
			$comment = 0;
			$this->load->model ( 'common/Enum_model' );
			$data ['status_des'] = $this->Enum_model->get_enum_des ( array (
					'HOTEL_ORDER_STATUS',
					'PAY_WAY',
					'HOTEL_ORDER_PAY_STATUS' 
			), array (
					1,
					2 
			) );
			
			// 显示订单状态，判断评论和可否取消
			$this->load->model ( 'hotel/Order_check_model' );
			$state = $this->Order_check_model->check_order_state ( $list, $data ['status_des'] ['HOTEL_ORDER_STATUS'] );
			$list ['status_des'] = $state ['des'];
			$list ['show_orderid'] = empty ( $list ['web_orderid'] ) ? $list ['orderid'] : $list ['web_orderid'];
			$data ['not_same'] = $state ['not_same'];
			$data ['can_cancel'] = $state ['can_cancel'];
			$data ['can_comment'] = $state ['can_comment'];
			$data ['re_pay'] = $state ['re_pay'];
			$data ['order_sequence'] = array();
			if ($state ['not_same'] == 0) {
				$data ['order_sequence'] = $this->Order_model->get_order_sequence ( $list ['status'] );
			}
			
			if ($state ['pms_check'] == 1) {
				$this->load->library ( 'PMS_Adapter', array (
						'inter_id' => $this->inter_id,
						'hotel_id' => $list ['hotel_id'] 
				), 'pmsa' );
				$this->pmsa->update_web_order ( $this->inter_id, $list );
			}
			
			$data ['order'] = $list;
			$data ['pagetitle'] = '订单详情';
			
			$this->load->model ( 'hotel/Hotel_model' );
			$data ['first_room'] = $this->Hotel_model->get_room_detail ( $this->inter_id, $list ['hotel_id'], $data ['order'] ['first_detail'] ['room_id'], array (
					'img_type' => 'hotel_room_service' 
			) );
			
			$this->out_put_msg ( 1, '', $data,'hotel/orderdetail' );
		} else {
			$this->out_put_msg ( 1, '找不到订单' );
		}
		// Visit log
// 		$this->load->model ( 'common/Record_model' );
// 		$this->Record_model->visit_log ( array (
// 				'openid' => $this->openid,
// 				'inter_id' => $this->inter_id,
// 				'title' => '订单详情',
// 				'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
// 				'des' => "订单id：" . $oid 
// 		) );
	}
	function myorder() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Order_model' );
		$handled = $this->get_source ( 'hl', 'int' );
		$orders = $this->Order_model->get_main_order ( $this->inter_id, array (
				'openid' => $this->openid,
				'member_no' => $this->member_no,
				'handled' => $handled,
				'idetail' => array (
						'r' 
				) 
		) );
		$this->load->model ( 'common/Enum_model' );
		$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
		$this->load->model ( 'hotel/Order_check_model' );
		foreach ( $orders as $ok => $o ) {
			$state = $this->Order_check_model->check_order_state ( $o, $status_des );
			$orders [$ok] ['status_des'] = $state ['des'];
		}
		$data ['orders'] = $orders;
		$data ['handled'] = $handled;
		$this->load->model ( 'pay/Pay_model' );
		
		$data ['online_pay'] = $this->Pay_model->is_online_pay ();
		
		$this->out_put_msg ( 1, '', $data, 'hotel/myorder' );
	}
	function hotel_photo() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Gallery_model' );
		$data ['hotel_id'] = $hotel_id = $this->Hotel_model->get_a_hotel_id ( $this->inter_id, $this->input->get ( 'h' ), false );
		$data ['gallery_count'] = $this->Gallery_model->get_gallery_count ( $this->inter_id, $data ['hotel_id'] );
		$data ['first_gallery'] = $data ['gallery_count'] [0];
		$data ['cur_gallery'] = $this->Gallery_model->get_gallery ( $this->inter_id, array (
				'hotel_id' => $data ['hotel_id'],
				'gallery_id' => $data ['first_gallery'] ['gid'] 
		), true, 3, 0 );
		$this->display ( 'hotel/hotel_photo/hotel_photo', $data );
	}
	function get_new_gallery() {
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Gallery_model' );
		$hotel_id = $hotel_id = $this->Hotel_model->get_a_hotel_id ( $this->inter_id, $this->input->get ( 'h' ), false );
		$gid = $this->input->get ( 'gid' );
		$nums = $this->input->get ( 'nums' );
		$offset = $this->input->get ( 'offset' );
		$new_gallery = $this->Gallery_model->get_gallery ( $this->inter_id, array (
				'hotel_id' => $hotel_id,
				'gallery_id' => $gid 
		), true, $nums, $offset );
		$this->load->helper ( 'ajaxdata' );
		$new_gallery = data_dehydrate ( $new_gallery, array (
				'gid',
				'gallery_name',
				'image_url',
				'info' 
		) );
		echo json_encode ( $new_gallery );
	}
	function my_marks() {
		$data = $this->common_data;
		$data ['pagetitle'] = '我的收藏';
		$data ['mark_type'] = intval ( $this->input->get ( 'mt' ) );
		$this->load->model ( 'hotel/Hotel_model' );
		$condit = $this->Hotel_model->return_mark_condi ( $data ['mark_type'] );
		$data ['marks'] = array ();
		if (! empty ( $condit )) {
			$data ['marks'] = $this->Hotel_model->get_front_marks ( array (
					'inter_id' => $this->inter_id,
					'openid' => $this->openid,
					'mark_type' => $condit ['type'],
					'status' => 1 
			), $condit ['sort'] );
		}
		$this->display ( 'hotel/my_marks/often_like', $data );
	}
	function get_near_hotel() {
		$latitude = $this->input->get ( 'lat', true );
		$longitude = $this->input->get ( 'lnt', true );
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->helper ( 'calculate' );
		$hotels = $this->Hotel_model->get_all_hotels ( $this->inter_id, 1 );
		$count = count ( $hotels );
		for($i = 0; $i < $count; $i ++) {
			$hotels [$i] ['distance'] = get_distance ( $hotels [$i] ['longitude'], $hotels [$i] ['latitude'], $longitude, $latitude );
		}
		$hotels = $this->Hotel_model->sort_dyd_array ( $hotels, 'distance', 'gt', 5 );
		$this->load->helper ( 'ajaxdata' );
		echo json_encode ( data_dehydrate ( $hotels, array (
				'name',
				'hotel_id' 
		), 'hotel_id' ) );
	}
	function hotel_comment() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Comment_model' );
		$hotel_id = $this->Hotel_model->get_a_hotel_id ( $this->inter_id, $this->get_source('hotel_id'), false );
		$data ['t_t'] = $this->Comment_model->get_hotel_comment_counts ( $this->inter_id, $hotel_id, 1 );
		$data ['comments'] = $this->Comment_model->get_hotel_comments ( $this->inter_id, $hotel_id, 1 );
		$data ['hotel_id'] = $hotel_id;
		
		//暂时去掉type 为hotel的评论
		$common_data = array();
		
		$current_key = 0;
		foreach($data['comments'] as $key => $d){
			
			if(isset($d['type']) && $d['type'] == "hotel"){
				
				if($current_key <= 0){
					continue;
				}
				
				$common_data[$current_key-1]['hotel_said'] = (array)$d;
			}else{
				$d = (array)$d;
				$d['hotel_said'] = array();
				$common_data[$current_key] = $d;
				$current_key++;
			}
			
			
		}
		$data ['comments'] = $common_data;
		
		$this->out_put_msg(1,'',$data,'hotel/hotel_comment');
	}
	function ajax_hotel_comments() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Comment_model' );
		$hotel_id = $this->Hotel_model->get_a_hotel_id ( $this->inter_id, $this->input->get ( 'h' ), false );
		$offset = $this->input->get ( 'off', TRUE );
		$offset = empty ( intval ( $offset ) ) ? 0 : intval ( $offset );
		$nums = $this->input->get ( 'num', TRUE );
		$nums = empty ( intval ( $nums ) ) ? 20 : intval ( $nums );
		$nums = $nums > 20 ? 20 : $nums;
		$data ['comments'] = $this->Comment_model->get_hotel_comments ( $this->inter_id, $hotel_id, 1, '', $nums, $offset );
		if (! empty ( $data ['comments'] )) {
			$html = $this->display ( 'hotel/ajax_hotel_comments/ajax_comment_list', $data, '', array (), TRUE );
			echo json_encode ( array (
					's' => 1,
					'data' => $html 
			), JSON_UNESCAPED_UNICODE );
			exit ();
		}
		echo json_encode ( array (
				's' => 0,
				'data' => '' 
		) );
	}
	function to_comment() {
		$data = $this->common_data;
		$orderid = $this->get_source( 'orderid' ) ;
		$this->load->model ( 'hotel/Order_model' );
		$list = $this->Order_model->get_main_order ( $this->inter_id, array (
				'orderid' => $orderid,
				'openid' => $this->openid,
				'member_no' => $this->member_no,
				'idetail' => array (
						'i' 
				) 
		) );
		if ($list) {
			$this->load->model ( 'common/Enum_model' );
			$data ['status_des'] = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
			$list = $list [0];
			$comment = 0;
			$complete_status = array (
					2,
					3 
			);
			if ($list ['handled'] == 1) {
				foreach ( $list ['order_details'] as $od ) {
					if (in_array ( $od ['istatus'], $complete_status )) {
						$comment = 1;
						break;
					}
				}
			} else if (count ( $list ['order_details'] ) == 1) {
				$list ['status_des'] = $data ['status_des'] [$list ['status']];
				if (in_array ( $list ['status'], $complete_status )) {
					$comment = 1;
				}
			}
			
			$this->load->model ( 'hotel/Comment_model' );
			$data ['comment_info'] = $this->Comment_model->get_order_comment ( $this->inter_id, $list ['orderid'], $this->openid );
			$data ['order'] = $list;
			$data ['comment'] = $comment;
			$this->load->model ( 'hotel/Hotel_model' );
			$data ['first_room'] = $this->Hotel_model->get_room_detail ( $this->inter_id, $list ['hotel_id'], $data ['order'] ['first_detail'] ['room_id'], array (
					'img_type' => 'hotel_room_service' 
			) );
			$this->out_put_msg(1,'',$data,'hotel/to_comment');
		} else {
			$this->out_put_msg(1,'无此订单');
		}
		// Visit log
// 		$this->load->model ( 'common/Record_model' );
// 		$this->Record_model->visit_log ( array (
// 				'openid' => $this->openid,
// 				'inter_id' => $this->inter_id,
// 				'title' => '订单评论',
// 				'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
// 				'des' => "订单id：" . $oid 
// 		) );
	}
	function return_usable_coupon() {
		$this->load->model ( 'hotel/Coupon_model' );
		$params = array ();
		$start = $this->get_source( 'startdate' );
		$end = $this->get_source ( 'enddate' );
		$params ['days'] = round ( strtotime ( $end ) - strtotime ( $start ) ) / 86400;
		$params ['amount'] = $this->get_source ( 'total' );
		$params ['hotel'] = $this->get_source ( 'hotel_id' );
		$params ['paytype'] = $this->get_source( 'paytype' );
		
		// 增加获取券参数
		$params ['startdate'] = $start;
		$params ['enddate'] = $end;
		$params ['extra_para'] = $this->get_source ( 'extra_para' );
		$params ['extra_para'] = empty ( $params ['extra_para'] ) ? array () :   $params ['extra_para'] ;
		$params ['level'] = $this->member_lv;
		$data_arr =  $this->get_source ( 'datas' );
		$price_codes =  $this->get_source ( 'price_code' ) ;
		if (! empty ( $data_arr )) {
			foreach ( $data_arr as $key => $value ) {
				if ($value == 0) {
					unset ( $data_arr [$key] );
				}
			}
			$params ['rooms'] = array_sum ( $data_arr );
			$params ['product_num'] = array_sum ( $data_arr );
			$params ['product'] = array_keys ( $data_arr );
			reset ( $data_arr );
			$params ['category'] = key ( $data_arr );
		}
		if (! empty ( $price_codes )) {
			$params ['price_code'] = current ( $price_codes );
		}
		$cards = $this->Coupon_model->get_usable_coupon ( $this->inter_id, $this->openid, $params, TRUE );
		$this->out_put_msg(1,'',$cards,'hotel/return_usable_coupon');
	}
	function cancel_main_order() {
		$this->load->model ( 'hotel/Order_model' );
		$orderid = $this->get_source( 'orderid' );
		$info = $this->Order_model->cancel_order ( $this->inter_id, array (
				'openid' => $this->openid,
				'member_no' => $this->member_no,
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		$this->out_put_msg(1,$info['errmsg'],$info);
	}
	function comment_sub() {
		$orderid = $this->get_source( 'orderid' );
		$this->load->model ( 'hotel/Order_model' );
		$list = $this->Order_model->get_main_order ( $this->inter_id, array (
				'orderid' => $orderid,
				'openid' => $this->openid,
				'member_no' => $this->member_no,
				'idetail' => array (
						'i'
				)
		) );
		if ($list) {
			$list = $list [0];
			$data ['hotel_id'] = $list['hotel_id'];
			$data ['orderid'] = $orderid;
			$data ['openid'] = $this->openid;
			$data ['inter_id'] = $this->inter_id;
			$data ['content'] = htmlspecialchars ( $this->get_source( 'content' ) );
			$data ['score'] = intval ( $this->get_source ( 'score' ) );
			$data ['order_info'] ['hotel_name'] = $list['hname'];
			$data ['order_info'] ['room_name'] =  $list['first_detail']['roomname'];
			$this->load->model ( 'hotel/Comment_model' );
			$result=$this->Comment_model->add_comment ( $data );
			$this->out_put_msg(1,$result['errmsg'],$result,'hotel/comment_sub');
		} else {
			$this->out_put_msg(1,'无此订单');
		}
		
	}
	function return_room_detail() {
		$this->load->model ( 'hotel/Hotel_model' );
		$hotel_id = intval ( $this->input->post ( 'h' ) );
		$room_id = intval ( $this->input->post ( 'r' ) );
		$detail = $this->Hotel_model->get_room_detail ( $this->inter_id, $hotel_id, $room_id, array (
				'img_type' => array (
						'hotel_room_service',
						'hotel_room_lightbox' 
				) 
		), 1 );
		$room = array ();
		$room ['name'] = $detail ['name'];
		$room ['room_img'] = $detail ['room_img'];
		$room ['imgs'] = empty ( $detail ['imgs'] ) ? array () : $detail ['imgs'];
		$detail ['book_policy'] = $detail ['book_policy'];
		if (empty ( $detail ['book_policy'] )) {
			$hotel = $this->Hotel_model->get_hotel_detail ( $this->inter_id, $hotel_id );
			$detail ['book_policy'] = $hotel ['book_policy'];
		}
		$room ['book_policy'] = nl2br ( $detail ['book_policy'] );
		echo json_encode ( $room );
	}
	function display($paras, $data, $skin = '', $extra_views = array(), $return = false) {
		if ($this->session->userdata ( $this->inter_id . 'skin' )) {
			$skin = $this->session->userdata ( $this->inter_id . 'skin' );
		}
		if ($return == TRUE)
			return parent::display ( $paras, $data, $skin, $extra_views, $return );
		parent::display ( $paras, $data, $skin, $extra_views, $return );
	}
	
	/**
	 * 可预订前几天的房型
	 * @return int
	 */
	private function preSpDate() {
		$this->load->model ( 'hotel/Hotel_config_model' );
		$start_val = 0;
		$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
				'BOOK_DATE_VALIDATE' 
		) );
		if (! empty ( $config_data ['BOOK_DATE_VALIDATE'] )) {
			$condition = json_decode ( $config_data ['BOOK_DATE_VALIDATE'], true );
			if (! empty ( $condition ['startdate'] )) {
				foreach ( $condition ['startdate'] as $v ) {
					$hour = $v ['hour'];
					switch ($v ['compare']) {
						case 'less' : // 当前时间少于值
							if (date ( 'H' ) < $hour) {
								$start_val = $v ['val'];
							}
							break;
						case 'more' :
							if (date ( 'H' ) > $hour) {
								$start_val = $v ['val'];
							}
							break;
					}
					// 循环，出现多次条件匹配，以最后为准
				}
			}
		}
		return ( int ) $start_val;
	}
	public function test(){
		$this->set_user_session('a',time());
		$this->out_put_msg(1,'',$this->user_session('a'));
	}
	
	/**
	 * 临时使用通过坐标取地区
	 */
	public function getCityByLngLat(){
		
		$API = 'http://api.map.baidu.com/geocoder/v2/';
		$ak = 'pz5gld9c2A69XY4itpepD8M4';
		$longitude = $this->get_source ( 'lng' );
		$latitude = $this->get_source ( 'lat' );
		
		//gcj2 转百度
		$this->load->helper ( 'calculate' );
		$location=gcj2bd($longitude, $latitude);
		
		$longitude = $location['longitude'];
		$latitude = $location['latitude'];
		
		$param = array(
				'ak' => $ak,
				'location' => implode(',', array($latitude, $longitude)),
				'pois' => 0,
				'output' => 'json'
		);
	
		// 请求百度api
		$result = json_decode($this->toCurl($API, $param),true);
		
		$arr = array();

		$arr['address'] = $result['result']['formatted_address'];
		
		$arr['city'] = $result['result']['addressComponent']['city'];
		
		$arr['province'] = $result['result']['addressComponent']['province'];
		
		$arr['district'] = $result['result']['addressComponent']['district'];
		
		
		
		$this->out_put_msg(1,'',$arr,"hotel/getCityByLngLat");
		
	}
	
	/**
	 * 使用curl调用百度Geocoding API
	 * @param  String $url    请求的地址
	 * @param  Array  $param  请求的参数
	 * @return JSON
	 */
	private function toCurl($url, $param=array()){
	
		$ch = curl_init();
	
		if(substr($url,0,5)=='https'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
		}
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
	
		$response = curl_exec($ch);
	
		if($error=curl_error($ch)){
			return false;
		}
	
		curl_close($ch);
	
		return $response;
	
	}
	
	
	function bookroom_su8() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$hotel_id = intval ( $this->input->post ( 'hotel_id' ) );
		$room_id = intval ( $this->input->post ( 'room_id' ) );
		$data ['price_codes'] = $this->input->post ( 'price_codes' );
		$data ['price_type'] = $this->input->post ( 'price_type' );
		$startdate = $this->input->post ( 'startdate' );
		$enddate = $this->input->post ( 'enddate' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id );
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		$data ['hotel_id'] = $hotel_id;
		$datas = $this->input->post ( 'datas', TRUE );
		$price_codes = $data ['price_codes'];
		$price_type = $data ['price_type'];
		if (empty ( $datas ) || empty ( $price_codes )) {
			redirect ( site_url ( 'hotel/hotel/index' ) . '?id=' . $this->inter_id . '&h=' . $hotel_id );
		}
		$data ['hotel'] = $this->Hotel_model->get_hotel_detail ( $this->inter_id, $hotel_id, array (
				'img_type' => array (
						'hotel_service',
						'hotel_lightbox'
				)
		) );
		$data_arr = $datas;
		foreach ( $data_arr as $key => $value ) {
			if ($value == 0) {
				unset ( $data_arr [$key] );
			}
		}
		$data ['room_list'] = $this->Hotel_model->get_rooms_detail ( $this->inter_id, $hotel_id, array_keys ( $data_arr ), array (
				'number_realtime' => array (
						's' => $data ['startdate'],
						'e' => $data ['enddate']
				),
				'data' => 'key',
				'img_type' => array (
						'hotel_room_service'
				)
		) );
		$condit = array (
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'nums' => $data_arr,
				'openid' => $this->openid,
				'member_level' => $this->member_lv
		);
		// if ( $this->member_lv !='') {
		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}
		$data ['point_consum_rate'] = $this->Member_model->get_point_consum_rate ( $this->inter_id, $this->member_lv );
		// }
		$protrol_code = $this->input->post ( 'protrol_code' );
		if (! empty ( $protrol_code ) && array_key_exists ( 'protrol', $price_type )) {
			$protrol_price_code = $this->Order_model->get_protrol_price_code ( $this->inter_id, $hotel_id, $protrol_code );
		}
		if (! empty ( $protrol_price_code )) {
			$price_codes [] = $protrol_price_code;
			$condit ['price_type'] = array (
					'protrol'
			);
		}
		$condit ['price_codes'] = implode ( ',', $price_codes );
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id
		), 'pmsa' );
	
		$data ['rooms'] = $this->pmsa->get_rooms_change ( $data ['room_list'], array (
				'inter_id' => $this->inter_id,
				'hotel_id' => $hotel_id
		), $condit, true );
	
		$data ['first_room'] = current ( $data ['rooms'] );
		$data ['first_state'] = $data ['first_room'] ['state_info'] [$price_codes [$data ['first_room'] ["room_info"] ['room_id']]];
		if (empty ( $data ['first_state'] )) {
			redirect ( site_url ( 'hotel/hotel/index' ) . '?id=' . $this->inter_id . '&h=' . $hotel_id );
		}
		$data ['total_price'] = 0;
		$data ['total_oprice'] = 0;
		$no_pay_ways = array ();
		foreach ( $data ['rooms'] as $k => $item ) {
			$code_price = $item ['state_info'] [$price_codes [$k]];
			$data ['total_price'] += $code_price ['total_price'];
			$data ['total_oprice'] += empty ( $code_price ['total_oprice'] ) ? 0 : $code_price ['total_oprice'];
			$no_pay_ways = empty ( $code_price ['condition'] ['no_pay_way'] ) ? $no_pay_ways : array_merge ( $no_pay_ways, $code_price ['condition'] ['no_pay_way'] );
		}
	
		// @author lGh 2016-03-14
		$data ['athour'] = 0;
		if ($data ['first_state'] ['price_type'] == 'athour') {
			$data ['athour'] = 1;
		}
		$this->load->model ( 'hotel/Service_model' );
		$this->load->model ( 'hotel/Price_code_model' );
		if (! empty ( $data ['first_state'] ['add_service_set'] )) {
			$data ['services'] = $this->Service_model->replace_service ( $this->inter_id, array (
					'service_type' => 'hotel_order',
					'status' => 1,
					'add_occasion' => array (
							'hotel_order_before',
							'hotel_order_both'
					)
			), $data ['first_state'] ['add_service_set'] );
			$data ['services'] = $this->Service_model->classify_service ( $data ['services'] );
		}
	
		// var_dump($data ['first_state'] ['condition'] ['book_time']);
	
		if (! empty ( $data ['first_state'] ['condition'] ['book_time'] )) {
			// $data ['first_state'] ['condition'] ['book_time'] = date('20160328');
			// if ( isset ( $data ['first_state'] ['condition'] ['book_time'] )) {
			// $min_min = isset ( $data ['first_state'] ['condition'] ['min_min'] ) ? $data ['first_state'] ['condition'] ['min_min'] * 60 : 0;
			$min_min = empty ( $data ['first_state'] ['condition'] ['min_min'] ) ? 0 : $data ['first_state'] ['condition'] ['min_min'] * 60;
			$order_times = $this->Price_code_model->get_book_time ( $data ['first_state'] ['condition'] ['book_time'], $min_min );
			$data ['first_state'] ['condition'] ['book_times'] = $order_times ['book_times'];
			$data ['first_state'] ['condition'] ['last_time'] = $order_times ['last_time'];
		}
		if ($data ['athour'] == 1) {
			if (! empty ( $data ['services'] ['add_time'] )) {
				// foreach ( $data ['services'] ['add_time'] as $addk => $addv ) {
				$data ['add_time_service'] = current ( $data ['services'] ['add_time'] );
				$begin_time = empty ( $data ['first_state'] ['condition'] ['last_time'] ) ? date ( 'YmdH00', strtotime ( '+ 1 hour', time () ) ) : $data ['first_state'] ['condition'] ['last_time'];
				$max_time = empty ( $data ['first_state'] ['condition'] ['book_time'] ['e'] ) ? 0 : $data ['first_state'] ['condition'] ['book_time'] ['e'];
				$data ['add_time_service'] ['add_times'] = $this->Service_model->check_service_rule ( 'add_time', array (
						'begin_time' => $begin_time,
						'max_time' => $max_time,
						'max_num' => $data ['add_time_service'] ['max_num']
				) );
				// }
			}
		}
		// var_dump($data ['add_time_service']);
		$data ['room_count'] = array_sum ( $data_arr );
		$this->load->model ( 'pay/Pay_model' );
		$this->load->helper ( 'date' );
		$pay_days = get_day_range ( $data ['startdate'], $data ['enddate'], 'array' );
		array_pop ( $pay_days );
		$data ['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'status' => 1,
				'exclude_type' => $no_pay_ways,
				'check_day' => 1,
				'hotel_ids' => $hotel_id
		), $pay_days );
		$data ['source_data'] = json_encode ( $data_arr );
		$last_orders = $this->Order_model->get_last_order ( $this->inter_id, $this->openid, 1, false );
		$data ['member'] = $this->pub_pmsa->check_openid_member ( $this->inter_id, $this->openid, array (
				'create' => TRUE,
				'update' => TRUE
		) );
		empty ( $last_orders ) ?  : $data ['last_order'] = $last_orders [0];
	
		// @author lGh 2016-4-6 21:34:15 积分换房
		$countday = ceil ( (strtotime ( $data ['enddate'] ) - strtotime ( $data ['startdate'] )) / 86400 );
		$avg_price = floatval ( $data ['total_price'] / $countday );
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', $hotel_id, array (
				'PRICE_EXCHANGE_POINT',
				'BANCLANCE_COMSUME_CODE_NEED',
				'HOTEL_BALANCE_PART_PAY'
		) );
		if (! empty ( $config_data ['PRICE_EXCHANGE_POINT'] )) {
			$this->load->model ( 'hotel/Member_model' );
			$data ['point_exchange'] = $this->Member_model->room_point_exchange ( $this->inter_id, $data ['member'], array (
					'countday' => $countday,
					'price' => $avg_price,
					'config' => $config_data ['PRICE_EXCHANGE_POINT'],
					'roomnums' => 1
			) );
		}
	
		// if($this->inter_id=='a441624001'){
		// $data['hotel_config']=array();
		// $data['hotel_config']['ROOM_NO_SELECT']=1;
		// }
	
		// var_dump($data['athour']);
		// var_dump($data['first_state']);
		// exit;
	
		// 储值消费码
		$data ['banlance_code'] = 0;
		if (! empty ( $config_data ['BANCLANCE_COMSUME_CODE_NEED'] ) && $config_data ['BANCLANCE_COMSUME_CODE_NEED'] == 1) {
			$data ['banlance_code'] = 1;
		}
	
		// 使用部分储值
		$data ['banlance_part_pay'] = 0;
		if (! empty ( $config_data ['HOTEL_BALANCE_PART_PAY'] ) && $config_data ['HOTEL_BALANCE_PART_PAY'] == 1) {
			$data ['banlance_part_pay'] = 1;
		}
	
		//获取券的额外参数
		$data['extra_para']=array();
		$first_room=current($data ['room_list']);
		if (!empty($first_room['webser_id'])){
			$data['extra_para']['web_room_id']=$first_room['webser_id'];
			if (!empty($data ['first_state']['extra_info']['pms_code'])){
				$data['extra_para']['pms_code']=$data ['first_state']['extra_info']['pms_code'];
			}
		}
		
		$rule = array();
		
		$first_state = $data['first_state'];
		$member = $data['member'];
		$daily_info = "";
		if(isset($first_state) && isset($first_state['extra_info']['daily_info'])  ){
			$daily_info = (array)$first_state['extra_info']['daily_info'];
		}
		//$daily_info = $data['daily_info'];
		$rule['MinCheckinTime'] = "";
		$rule['MaxCheckinTime'] = "";
		$rule['MaxRoomCount'] = 0;
		$rule['CanPrepay'] = true;
		$rule['Balance'] = 0;
		$rule['Maxcoupon'] = isset($data['first_state']['extra_info']['coupon_limit'][0]['Amount'])?$data['first_state']['extra_info']['coupon_limit'][0]['Amount']:0;  // 优惠券限额
		if ($daily_info != "" && !empty($first_state['extra_info']['guaran']) && $first_state['extra_info']['guaran']['stime'] != "" && $first_state['extra_info']['guaran']['etime'] != ""){
		
			$rule['MinCheckinTime'] = date("Y/m/d",(strtotime($daily_info[0]['RoomDay'])))." ".$first_state['extra_info']['guaran']['stime']; //min保留时间 
			
		if($first_state['extra_info']['guaran']['stime'] >= $first_state['extra_info']['guaran']['etime']){
			$rule['MaxCheckinTime'] = date("Y/m/d",(strtotime($daily_info[0]['RoomDay'])+86401))." ".$first_state['extra_info']['guaran']['etime'];
		}else{
			$rule['MaxCheckinTime'] =  date("Y/m/d",strtotime($daily_info[0]['RoomDay']))." ".$first_state['extra_info']['guaran']['etime'];
		} 
	
			$rule['MaxRoomCount'] = intval( $first_state['extra_info']['guaran']['minnum']); // 最大房间数
			$rule['CanPrepay'] = $first_state['extra_info']['guaran']['pre_pay']?'true':'false';  //true 表示支持预付
				
		}
		
		if(!empty($member->mem_id)){
		    	if (!empty($data['banlance_part_pay'])){
		    		$rule['Balance'] = $member->balance;
				}
		}
		
		$data['rule'] = $rule;
		
		$this->out_put_msg ( 1, '', $data ,'hotel/bookroom_su8');
		//$data['extra_para']=json_encode($data['extra_para']);
		//$this->display ( 'hotel/bookroom/submit_order', $data );
	}
	
}
