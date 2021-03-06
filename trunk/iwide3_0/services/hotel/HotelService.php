<?php
namespace App\services\hotel;

use App\services\HotelBaseService;

/**
 * Class HotelService
 * @package App\services\hotel
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class HotelService extends HotelBaseService {
	public $common_data;
	public $module;

	/**
     * 获取服务实例方法
     * @return HotelService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

	function __construct() {
		parent::__construct ();
		\MYLOG::hotel_tracker($this->_hotel_ci->openid,  $this->_hotel_ci->inter_id);
		$this->module = 'hotel';
		$this->member_no = '';
		$this->member_lv = '';
		if (! empty ( $this->_hotel_ci->member_info ) && isset ( $this->_hotel_ci->member_info->mem_id )) {
			$this->member_no = $this->_hotel_ci->member_info->mem_card_no;
			$this->member_lv = $this->_hotel_ci->member_info->level;
		}
		
		$this->common_data ['member'] = $this->_hotel_ci->member_info;

		$this->common_data ['index_url'] = $this->_hotel_ci->public ['is_multy'] == 1 ? \Hotel_base::inst()->get_url('INDEX') : \Hotel_base::inst()->get_url('SEARCH');

		$this->common_data ['my_saler_id']=$this->_hotel_ci->my_saler_id;
		$this->common_data ['url_param'] = $this->_hotel_ci->url_param;

		if ($this->_hotel_ci->input->get ( 'theme' )) {
			$this->_hotel_ci->session->set_userdata ( $this->_hotel_ci->inter_id . 'skin', $this->_hotel_ci->input->get ( 'theme' ) );
		}
	}
	function search() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$this->_hotel_ci->load->model('hotel/Hotel_config_model');
		$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', 0, array (
		        'BOOK_DATE_VALIDATE',
		        'MAX_BOOK_DAY',
		        'LOGIN_ACTION_NAME',
		        'INDEX_TEL',
		        'MIN_START_DATE',
                'SHORT_CITY'
		) );
		$data['login_action_name']=isset($config_data['LOGIN_ACTION_NAME'])?$config_data['LOGIN_ACTION_NAME']:'登录';
		$data['index_tel']=isset($config_data['INDEX_TEL'])?$config_data['INDEX_TEL']:NULL;
		if (isset($config_data['MAX_BOOK_DAY'])&&$config_data['MAX_BOOK_DAY']>0){
		    $data['max_book_day']=$config_data['MAX_BOOK_DAY'];
		}else{
		    $data['max_book_day']=90;
		}
		//可预订的开始日期
		$date_check = $this->_hotel_ci->Order_model->date_validate ( date('Ymd'), date('Ymd',time()+86400), $this->_hotel_ci->inter_id,0,$config_data);
		$data ['startdate'] = $date_check[0];
		$data ['enddate'] = $date_check[1];
		$data['pre_sp_date']=$date_check[2];

		$data ['pubimgs'] = $this->_hotel_ci->Hotel_model->get_imgs ( 'pub', $this->_hotel_ci->inter_id );
		$cities = $this->_hotel_ci->Hotel_model->get_hotel_citys ( $this->_hotel_ci->inter_id );
		$data ['citys'] = $cities['citys'];
        $data['area'] = $cities['area'];

		$data ['first_city'] = $cities['first_city'];
		$data ['hot_city'] = $cities['hot_city'];

		$data ['last_orders'] = $this->_hotel_ci->Order_model->get_last_order ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, 5, true, 'h.city' );
		$data ['hotel_collection'] = $this->_hotel_ci->Hotel_model->get_front_marks ( array (
			                                                                   'inter_id' => $this->_hotel_ci->inter_id,
			                                                                   'openid' => $this->_hotel_ci->openid,
			                                                                   'mark_type' => 'hotel_collection',
			                                                                   'status' => 1,
		                                                                       'replace_host' => $_SERVER['HTTP_HOST']
		                                                                   ), 'mark_nums desc', 5, 0 );
		$data ['hotel_visited'] = $this->_hotel_ci->Hotel_model->get_front_marks ( array (
			                                                                'inter_id' => $this->_hotel_ci->inter_id,
			                                                                'openid' => $this->_hotel_ci->openid,
			                                                                'mark_type' => 'hotel_visited',
			                                                                'status' => 1,
		                                                                    'replace_host' => $_SERVER['HTTP_HOST']
		                                                                ), 'mark_time desc', 5, 0 );


		$this->_hotel_ci->load->model ( 'plugins/Advert_model' );
		$data ['foot_ads'] = $this->_hotel_ci->Advert_model->get_hotel_ads ( $this->_hotel_ci->inter_id, 0, 'search_foot', 1, 1 );

		$type = $this->_hotel_ci->input->get ( 'type', TRUE );
		$data ['type'] = $type;
		//首页配置
		$this->_hotel_ci->load->model ( 'hotel/Views_model' );
		$data['homepage_set'] = $this->_hotel_ci->Views_model->get_homepage_config ($this->_hotel_ci->inter_id,1);

		//获取皮肤配置
		$module_view=$this->_hotel_ci->get_display_view('hotel/search');
		$skin_config=$this->_hotel_ci->get_skin_config($module_view['skin_name'], 'hotel/search');
		$module_view=array(
				'module_view'=>$module_view
		);
		if (!empty($skin_config['fans_info'])){
			$this->_hotel_ci->load->model('wx/Publics_model');
			$data['fans_info']=$this->_hotel_ci->Publics_model->get_fans_info_one($this->_hotel_ci->inter_id,$this->_hotel_ci->openid);
		}

        if (!empty($skin_config['show_area'])){
            if(!empty($data ['citys']) && !empty($data ['area'])){
                foreach($data ['area'] as $py_area=>$arr_area){
                    foreach($arr_area as $arr){
                        $data ['citys'][$py_area][] = $arr;
                    }
                }
                if(isset( $data['citys'][0]))unset($data['citys'][0]);
                ksort ( $data['citys'] );
            }
        }

        if (isset($config_data['SHORT_CITY']) && $config_data['SHORT_CITY']==1){
            if(!empty($data['last_orders'])){
                foreach($data['last_orders'] as $key => $last_orders){
                    $data['last_orders'][$key]['hcity'] = str_replace(array('市','区','县'),'',$last_orders['hcity']);
                }
            }
            if(!empty($data['hot_city'])){
                foreach($data['hot_city'] as $key => $hot_city){
                    $data['hot_city'][$key] = str_replace(array('市','区','县'),'',$hot_city);
                }
            }
            if(!empty($data['citys'])){
                foreach($data['citys'] as $key => $temp_letters){
                    foreach($temp_letters as $t_key => $temp_cities){
                        $data['citys'][$key][$t_key]['city'] = str_replace(array('市','区','县'),'',$temp_cities['city']);
                        if(isset($data['citys'][$key][$t_key]['area'])) $data['citys'][$key][$t_key]['area'] = str_replace(array('市','区','县'),'',$temp_cities['area']);
                    }
                }
            }
        }

        return $data;
	}
	function sresult() {
		$data = $this->common_data;

        $is_near = $this->_hotel_ci->input->get ( 'nearby', TRUE );
        if(!empty($is_near) && $is_near==1)$data['nearby']=1;else $data['nearby']=0;
        $this->_hotel_ci->load->model ( 'hotel/Hotel_config_model' );
        $config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', 0, array (
                'HOTEL_RESULT_ICON',
                'BOOK_DATE_VALIDATE',
                'MAX_BOOK_DAY',
                'MIN_START_DATE'
        ) );

		$city = $this->_hotel_ci->input->post ( 'city', TRUE );
		if (empty($city)){
			$city = $this->_hotel_ci->input->get ( 'city', TRUE );
			if (!empty($city)){
				$city=json_decode('["'.str_replace('%',"\\",$city).'"]');
				if (!empty($city[0]))
					$city=$city[0];
			}
		}
		$city=addslashes($city);

        $area = $this->_hotel_ci->input->post ( 'area', TRUE );
        if (empty($area)){
            $area = $this->_hotel_ci->input->get ( 'area', TRUE );
            if (!empty($area)){
                $area=json_decode('["'.str_replace('%',"\\",$area).'"]');
                if (!empty($area[0]))
                    $area=$area[0];
            }
        }
        $area=addslashes($area);
		$startdate = $this->_hotel_ci->input->post ( 'startdate', TRUE );
		$enddate = $this->_hotel_ci->input->post ( 'enddate', TRUE );
		$extra_condition = $this->_hotel_ci->input->post ( 'ec', TRUE );

		$type = $this->_hotel_ci->input->get ( 'type', TRUE );
		$data ['type'] = $type;

		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate, $this->_hotel_ci->inter_id,0,$config_data);
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		$data ['pre_sp_date']=$date_check [2];

		$keyword = $this->_hotel_ci->input->post ( 'keyword', TRUE );
		$keyword=addslashes($keyword);
		$result=array();

		//获取皮肤配置
		$module_view=$this->_hotel_ci->get_display_view('hotel/sresult');
		$skin_config=$this->_hotel_ci->get_skin_config($module_view['skin_name'], 'hotel/sresult');
		$module_view=array(
				'module_view'=>$module_view
		);

		if(empty($skin_config['no_hotel_list']) && ($type != 'athour' || date('Hi')<2300)){
			$this->_hotel_ci->load->model('hotel/Hotel_check_model');
			$result = $this->_hotel_ci->Hotel_model->search_hotel_front ( $this->_hotel_ci->inter_id, array (
				'keyword' => $keyword,
				'city' => $city,
				'startdate' => $data ['startdate'],
				'enddate' => $data ['enddate'],
				'extra_condition'=>$extra_condition,
				'type' => $type,
                'area'=>$area
			) );
		}
		$data['city']=$city;
        $data['area']=$area;
		$data['keyword']=$keyword;
		$data['extra_condition']=$extra_condition;
		$data ['hotel_ids'] = '';
		if (! empty ( $result )) {
			// foreach ( $result as $rt ) {
			// $rt->service = $this->_hotel_ci->Hotel_model->get_imgs ( 'hotel_service', $this->_hotel_ci->inter_id, $rt->hotel_id );
			// $data ['hotel_ids'] .= ',' . $rt->hotel_id;
			// }
			// $data ['hotel_ids'] = substr ( $data ['hotel_ids'], 1 );
			// $lowests = $this->_hotel_ci->Order_model->get_lowest_price ( $this->_hotel_ci->inter_id, array (
			// 'startdate' => $data ['startdate'],
			// 'enddate' => $data ['enddate'],
			// 'hotel_ids' => $data ['hotel_ids']
			// ) );
			// foreach ( $result as $rt ) {
			// $rt->lowest = empty ( $lowests [$rt->hotel_id] ) ? 0 : $lowests [$rt->hotel_id];
			// }
			foreach ( $result as $rt ) {
				$data ['hotel_ids'] .= ',' . $rt->hotel_id;
			}
			$data ['hotel_ids'] = substr ( $data ['hotel_ids'], 1 );
			$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
			$result = $this->_hotel_ci->Hotel_check_model->get_extra_info ( $this->_hotel_ci->inter_id, $result, array (
				'hotel_service',
				'lowest_price',
				'search_icons',
				'comment_data'
			), array (
				                                                     'startdate' => $data ['startdate'],
				                                                     'enddate' => $data ['enddate'],
				                                                     'member_level'=>$this->member_lv
			                                                     ) );
			$data ['result'] = $result;
			$data ['icons_set'] = array ();
			if (! empty ( $config_data ['HOTEL_RESULT_ICON'] )) {
				$data ['icons_set'] = json_decode ( $config_data ['HOTEL_RESULT_ICON'], TRUE );
			}
		}
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->inter_id,
			                                 'title' => '搜索结果',
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'des' => "城市：$city,关键字：$keyword"
		                                 ) );
		if (isset($config_data['MAX_BOOK_DAY'])&&$config_data['MAX_BOOK_DAY']>0){
		    $data['max_book_day']=$config_data['MAX_BOOK_DAY'];
		}else{
		    $data['max_book_day']=90;
		}


		return $data;

	}
	function return_lowest_price() {
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$hotel_ids = $this->_hotel_ci->input->get ( 'hs' );
		$startdate = $this->_hotel_ci->input->get ( 's' );
		$enddate = $this->_hotel_ci->input->get ( 'e' );
		$lowests = $this->_hotel_ci->Order_model->get_lowest_price ( $this->_hotel_ci->inter_id, array (
			'startdate' => $startdate,
			'enddate' => $enddate,
			'hotel_ids' => $hotel_ids,
			'member_level'=>$this->member_lv,
		) );
		return $lowests;
	}
	function index() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
        $this->_hotel_ci->load->model ( 'hotel/Coupon_model' );
		$this->_hotel_ci->load->model ( 'hotel/Gallery_model' );
		$hotel_id = $this->_hotel_ci->Hotel_model->get_a_hotel_id ( $this->_hotel_ci->inter_id, $this->_hotel_ci->input->get ( 'h' ), true );
		$data ['hotel'] = $this->_hotel_ci->Hotel_model->get_hotel_detail ( $this->_hotel_ci->inter_id, $hotel_id, array (
			'img_type' => array (
				'hotel_service',
				'hotel_lightbox'
			),
			'icon_type' => array(
				'ICONS_IMG_SERACH_RESULT'
			)
		) );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $hotel_id, array (
		        'HOTEL_RESULT_ICON',
		        'ROOM_EMPTY_ALERT',
		        'BOOK_DATE_VALIDATE',
		        'MAX_BOOK_DAY',
		        'NONE_COMMENT',//取消评论
		        'MIN_START_DATE'
		));
		$gallery_count = $this->_hotel_ci->Gallery_model->get_gallery_count ( $this->_hotel_ci->inter_id, $data ['hotel'] ['hotel_id'] );
		$data ['gallery_count'] = 0;
		foreach ( $gallery_count as $gc ) {
			$data ['gallery_count'] += $gc ['g_nums'];
		}
		$collect_check = $this->_hotel_ci->Hotel_model->get_type_mark ( array (
			                                                     'inter_id' => $this->_hotel_ci->inter_id,
			                                                     'mark_name' => $hotel_id,
			                                                     'openid' => $this->_hotel_ci->openid,
			                                                     'mark_type' => 'hotel_collection'
		                                                     ) );
		$data ['collect_id'] = empty ( $collect_check ) ? 0 : $collect_check ['mark_id'];
		$startdate = $this->_hotel_ci->input->get ( 'start' );
		$enddate = $this->_hotel_ci->input->get ( 'end' );
		$type = $this->_hotel_ci->input->get ( 'type', TRUE );
		$data ['type'] = $type;
		if($type == 'ticket'){
			$room_type = 'ticket';
		}else{
			$room_type = 'room';
		}
		
		
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate,$this->_hotel_ci->inter_id,$hotel_id,$config_data);
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		$data['pre_sp_date'] = $date_check [2];
		$rooms = $this->_hotel_ci->Hotel_model->get_hotel_rooms ( $this->_hotel_ci->inter_id, $hotel_id, 1 ,null ,null ,$room_type);

		$rooms = $this->_hotel_ci->Hotel_model->get_rooms_detail ( $this->_hotel_ci->inter_id, $hotel_id, $rooms, array (
			'data' => 'value',
			'img_type' => 'hotel_room_service'
		) );
		
		$condit = array (
			'startdate' => $data ['startdate'],
			'enddate' => $data ['enddate'],
			'openid' => $this->_hotel_ci->openid,
			'member_level' => $this->member_lv,
			'member_bonus'=>isset($this->common_data ['member']->bonus)?$this->common_data ['member']->bonus:0,
			'check_pointpay'=>1,
			'check_type_label'=>1,
		    'check_package'=>1
		);
		// if ( $this->member_lv !='') {
		$this->_hotel_ci->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->_hotel_ci->Member_model->level_privilege ( $this->_hotel_ci->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}

		// }

		$data ['countday'] = get_room_night($data ['startdate'],$data ['enddate'],'ceil',$data);//至少有一个间夜
		$this->_hotel_ci->load->library ( 'PMS_Adapter', array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), 'pmsa' );

		//add by ping
		if($type){
			$condit ['only_type'] = $type;
		}
		// $this->_hotel_ci->load->model('hotel/Order_model');
		// $data ['rooms'] = $this->_hotel_ci->Order_model->get_rooms_change ( $rooms, array (
		//专题活动处理 add by ping
		$tc_id = $this->_hotel_ci->input->get ( 'tc_id', TRUE );
		if($tc_id>0){
			$data ['tc_id'] = $tc_id;
			$this->_hotel_ci->load->model ( 'hotel/Hotel_thematic_model' );
			$tc_row = $this->_hotel_ci->Hotel_thematic_model->get_row($this->_hotel_ci->inter_id,$tc_id,array('nowtime'=>date('Y-m-d H:i:s'),'status'=>1));
			if(!empty($tc_row)){
				$tc_price_codes = json_decode($tc_row['price_codes'],TRUE);
				$condit['price_codes'] = implode(',',$tc_price_codes);
			}
		}
		$data ['rooms'] = $this->_hotel_ci->pmsa->get_rooms_change ( $rooms, array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), $condit, true );
		$data['packages']=empty($data ['rooms']['packages'])?array():$data ['rooms']['packages'];
		$data['rooms']=$data ['rooms']['rooms'];
		$this->_hotel_ci->load->helper('string');

        $pay_days = get_day_range ( $data ['startdate'], $data ['enddate'], 'array' );
        array_pop ( $pay_days );
        $pay_ways = $this->_hotel_ci->Pay_model->get_pay_way ( array (
            'inter_id' => $this->_hotel_ci->inter_id,
            'module' => $this->module,
            'status' => 1,
            'exclude_type' => array(),
            'check_day' => 1,
            'hotel_ids' => $hotel_id,
            'not_show'=>1
        ), $pay_days );


        $mycards = array();
		foreach ($data ['rooms'] as $room_key=>$r){
		    if (!empty($r['state_info'])){
		        foreach ($r['state_info'] as $state_key=>$state){
		           $data ['rooms'][$room_key]['state_info'][$state_key]['des']=htmlblank_replace($state['des']);
                    if (empty($state['condition']['no_coupon'])&&empty($state['coupon_condition']['no_coupon'])){
                    $room_pay_way = $pay_ways;
                    foreach($room_pay_way as $pay_way_key => $pay_way){
                        if(isset($state['condition']['no_pay_way']) && in_array($pay_way->pay_type,$state['condition']['no_pay_way'])){
                            unset($room_pay_way[$pay_way_key]);
                        }
                    }

                        if(!empty($room_pay_way)){
                            reset($room_pay_way);
                            $coupon_paytype = current($room_pay_way)->pay_type;
                        }
                    if(isset($state['bookpolicy_condition']['wxpay_favour']) && !empty($state['bookpolicy_condition']['wxpay_favour']))$coupon_paytype = 'weixin';
                        if (empty($coupon_paytype)){
                            continue;
                        }
                    $params = array (
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                        'hotel' => $hotel_id,
                        'price_code'=>$data ['rooms'][$room_key]['state_info'][$state_key]['price_code'],
                        'product'=>array($room_key),
                        'category'=>$room_key,
                        'rooms'=>1,
                        'product_num'=>1,
                        'extra_para'=>array(),
                        'level'=>$data['member']->level,
                        'amount'=>$data ['rooms'][$room_key]['state_info'][$state_key]['total_price'],
                        'paytype'=>$coupon_paytype

                    );

                    $cardlist = $this->_hotel_ci->Coupon_model->select_coupon($params,$mycards);
                    $coupons = isset($cardlist['cards'])?$cardlist['cards']:'';
                    $mycards = isset($cardlist['mycards'])?$cardlist['mycards']:'';

                    if(isset($coupons['selected']) && !empty($coupons['selected'])){
                        $data ['rooms'][$room_key]['state_info'][$state_key]['coupon_type'] = isset($coupons['selected'][0]['coupon_type'])?$coupons['selected'][0]['coupon_type']:'no_discount';
                        $data ['rooms'][$room_key]['state_info'][$state_key]['useable_coupon_favour'] = $coupons['select_coupon_favour'];
                    }
                    }
		        }
		    }
		}
		
		if(empty($config_data['NONE_COMMENT'])){
			$this->_hotel_ci->load->model('hotel/Comment_model');
			$data ['t_t'] = $this->_hotel_ci->Comment_model->get_hotel_comment_counts($this->_hotel_ci->inter_id, $hotel_id, 1, $this->_hotel_ci->openid);
		}
		$data ['icons_set'] = array ();
		if (! empty ( $config_data ['HOTEL_RESULT_ICON'] )) {
			$data ['icons_set'] = json_decode ( $config_data ['HOTEL_RESULT_ICON'], TRUE );
		}

		if (! empty ( $_GET ['debug'] )) {
			var_dump ( $data ['rooms'] );
			var_dump ( $member_privilege );
			var_dump ( $this->member_lv );
		}
		if(empty($data['rooms']) && !empty($config_data['ROOM_EMPTY_ALERT'])){
			$data['room_empty_alert'] = $config_data['ROOM_EMPTY_ALERT'];
		}

		// Visit log
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->session->userdata ( 'inter_id' ),
			                                 'title' => $data ['hotel'] ['name'],
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'visit_time' => date ( 'Y-m-d H:i:s' ),
			                                 'des' => ''
		                                 ) );
		$this->_hotel_ci->Hotel_model->add_front_mark ( array (
			                                     'inter_id' => $this->_hotel_ci->inter_id,
			                                     'openid' => $this->_hotel_ci->openid,
			                                     'mark_name' => $hotel_id,
			                                     'mark_type' => 'hotel_visited',
			                                     'mark_title' => $data ['hotel'] ['name'],
			                                     'mark_link' => site_url ( 'hotel/hotel/index?id=' . $this->_hotel_ci->inter_id . '&h=' . $hotel_id )
		                                     ) );
		$this->_hotel_ci->load->model ( 'plugins/Advert_model' );
		$data ['foot_ads'] = $this->_hotel_ci->Advert_model->get_hotel_ads ( $this->_hotel_ci->inter_id, $hotel_id, 'index_foot', 1, 1 );
		$data ['middle_ads'] = $this->_hotel_ci->Advert_model->get_hotel_ads ( $this->_hotel_ci->inter_id, $hotel_id, 'index_middle', 1, 1 );
		if (isset($config_data['MAX_BOOK_DAY'])&&$config_data['MAX_BOOK_DAY']>0){
		    $data['max_book_day']=$config_data['MAX_BOOK_DAY'];
		}else{
		    $data['max_book_day']=90;
		}
		//开始日期限定
		$data['minSelect'] = 1;

			if(!empty($tc_row)){
				$data['pre_sp_date'] = $tc_row['pre_days'];
				$data['minSelect'] = $tc_row['min_days'];

		}

		return $data;

	}
	function return_more_room() {
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
        $this->_hotel_ci->load->model ( 'hotel/Coupon_model' );
		$hotel_id = $this->_hotel_ci->input->post ( 'h' );
		$startdate = $this->_hotel_ci->input->post ( 'start' );
		$enddate = $this->_hotel_ci->input->post ( 'end' );
		$protrol_code = $this->_hotel_ci->input->post ( 'protrol_code' );
        // $member_level =$this->_hotel_ci->input->post ( 'mem_level' );
		if (! empty ( $protrol_code )) {
			$protrol_price_code = $this->_hotel_ci->Order_model->get_protrol_price_code ( $this->_hotel_ci->inter_id, $hotel_id, $protrol_code );
		}
		$type = $this->_hotel_ci->input->get ( 'type', TRUE );
		$data ['type'] = $type;
		if($type == 'ticket'){
			$room_type = 'ticket';
		}else{
			$room_type = 'room';
		}
		$rooms = $this->_hotel_ci->Hotel_model->get_hotel_rooms ( $this->_hotel_ci->inter_id, $hotel_id, 1 ,null ,null ,$room_type);
		$rooms = $this->_hotel_ci->Hotel_model->get_rooms_detail ( $this->_hotel_ci->inter_id, $hotel_id, $rooms, array (
			'data' => 'value',
			'img_type' => 'hotel_room_service'
		) );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate,$this->_hotel_ci->inter_id,$hotel_id);
		$startdate = $date_check [0];
		$enddate = $date_check [1];
		$errmsg = '';
		$condit = array (
			'startdate' => $startdate,
			'enddate' => $enddate,
			'is_ajax' => 1,
			'openid' => $this->_hotel_ci->openid,
			'member_level' => $this->member_lv,
			'member_bonus'=>isset($this->common_data ['member']->bonus)?$this->common_data ['member']->bonus:0,
			'check_pointpay'=>1,
			'check_type_label'=>1,
		    'check_package'=>1
		);
		// if ( $this->member_lv !='') {
		$this->_hotel_ci->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->_hotel_ci->Member_model->level_privilege ( $this->_hotel_ci->inter_id );
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
		$this->_hotel_ci->load->library ( 'PMS_Adapter', array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), 'pmsa' );
		if($type){
			$condit ['only_type'] = $type;
		}
		//专题活动处理 add by ping
		$tc_id = $this->_hotel_ci->input->post ( 'tc_id', TRUE );
		if($tc_id>0){
			$data ['tc_id'] = $tc_id;
			$this->_hotel_ci->load->model ( 'hotel/Hotel_thematic_model' );
			$tc_row = $this->_hotel_ci->Hotel_thematic_model->get_row($this->_hotel_ci->inter_id,$tc_id,array('nowtime'=>date('Y-m-d H:i:s'),'status'=>1));
			if(!empty($tc_row)){
				$tc_price_codes = json_decode($tc_row['price_codes'],TRUE);
				$condit['price_codes'] = implode(',',$tc_price_codes);
						}
					}
		$rooms = $this->_hotel_ci->pmsa->get_rooms_change ( $rooms, array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), $condit, true );
		$packages=empty($rooms['packages'])?array():$rooms['packages'];
		$rooms=$rooms['rooms'];
		$empty_notice='';
		if(empty($rooms) && !empty($config_data['ROOM_EMPTY_ALERT'])){
			$empty_notice=$config_data['ROOM_EMPTY_ALERT'];
		}
		$result=[];

        $pay_days = get_day_range ( $startdate, $enddate, 'array' );
        array_pop ( $pay_days );
        $pay_ways = $this->_hotel_ci->Pay_model->get_pay_way ( array (
            'inter_id' => $this->_hotel_ci->inter_id,
            'module' => $this->module,
            'status' => 1,
            'exclude_type' => array(),
            'check_day' => 1,
            'hotel_ids' => $hotel_id,
            'not_show'=>1
        ), $pay_days );

        usort($pay_ways, function ($a, $b){
            return $b->sort != $a->sort?$b->sort - $a->sort:0;
        });

        $array_pay_ways = array();
        foreach($pay_ways as $temp_pay_ways){
            $array_pay_ways[] = $temp_pay_ways->pay_type;
        }

        $mycards = array();
        foreach($rooms as $v){
            $v['state_info'] = array_values($v['state_info']);
            $v['show_info'] = array_values($v['show_info']);

            if (!empty($v['state_info'])){
                foreach ($v['state_info'] as $state_key=>$state){
                    $v['state_info'][$state_key]['des']=htmlblank_replace($state['des']);
                    if (empty($state['condition']['no_coupon'])&&empty($state['coupon_condition']['no_coupon'])){
                    $room_pay_way = $pay_ways;
                    foreach($room_pay_way as $pay_way_key => $pay_way){
                        if(isset($state['condition']['no_pay_way']) && in_array($pay_way->pay_type,$state['condition']['no_pay_way'])){
                            unset($room_pay_way[$pay_way_key]);
                        }
                    }

                    $v['state_info'][$state_key]['wxpay_favour_sign'] = '';

                    if(!empty($state['bookpolicy_condition']['wxpay_favour'])){
                        if(in_array('weixin',$array_pay_ways) ){
                            if((isset($state['condition']['[no_pay_way]']) && !in_array('weinxin',$state['condition']['[no_pay_way]'])) || !isset($state['condition']['[no_pay_way]'])){
                                $v['state_info'][$state_key]['wxpay_favour_sign'] = 1;
                            }
                        }
                    }

                    if(isset($state['bookpolicy_condition']['wxpay_favour']) && !empty($state['bookpolicy_condition']['wxpay_favour']))$coupon_paytype = 'weixin';else $coupon_paytype = current($room_pay_way)->pay_type;

                    $params = array (
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                        'hotel' => $hotel_id,
                        'price_code'=>$v['state_info'][$state_key]['price_code'],
                        'product'=>array($v['room_info']['room_id']),
                        'category'=>$v['room_info']['room_id'],
                        'rooms'=>1,
                        'product_num'=>1,
                        'extra_para'=>array(),
                        'level'=>$this->member_lv,
                        'amount'=>$v['state_info'][$state_key]['total_price'],
                        'paytype'=>$coupon_paytype

                    );

                    $cardlist = $this->_hotel_ci->Coupon_model->select_coupon($params,$mycards);
                    $coupons = isset($cardlist['cards'])?$cardlist['cards']:'';
                    $mycards = isset($cardlist['mycards'])?$cardlist['mycards']:'';

                    if(isset($coupons['selected']) && !empty($coupons['selected'])){
                        $v['state_info'][$state_key]['coupon_type'] = isset($coupons['selected'][0]['coupon_type'])?$coupons['selected'][0]['coupon_type']:'no_discount';
                        $v['state_info'][$state_key]['useable_coupon_favour'] = $coupons['select_coupon_favour'];
                    }
                    }
                }
            }

            $result[] = $v;
        }
        return array (
            	's' => 1,
            	'errmsg' => $errmsg,
            	'rooms' => $result,
				'room_empty_alert' => $empty_notice,
            	'packages' => $packages
        	);

	}
	function hotel_detail() {
		$data = $this->common_data;
		if ($this->_hotel_ci->input->get ( 'h' ))
			$this->_hotel_ci->session->set_userdata ( array (
				                               $this->_hotel_ci->inter_id . '_room_hotel_id' => $this->_hotel_ci->input->get ( 'h' )
			                               ) );
		$this->hotel_id = $this->_hotel_ci->session->userdata ( $this->_hotel_ci->inter_id . '_room_hotel_id' );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$data ['hotel'] = $this->_hotel_ci->Hotel_model->get_hotel_detail ( $this->_hotel_ci->inter_id, $this->hotel_id, array (
			'img_type' => array (
				'hotel_service',
				'hotel_lightbox'
			)
		) );
		// Visit log
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->session->userdata ( 'inter_id' ),
			                                 'title' => $data ['hotel'] ['name'],
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'visit_time' => date ( 'Y-m-d H:i:s' ),
			                                 'des' => '查看酒店详情'
		                                 ) );
		return $data;
	}
	function arounds() {
		$data = $this->common_data;
		if ($this->_hotel_ci->input->get ( 'h' ))
			$this->_hotel_ci->session->set_userdata ( array (
				                               $this->_hotel_ci->inter_id . '_room_hotel_id' => $this->_hotel_ci->input->get ( 'h' )
			                               ) );
		$this->hotel_id = $this->_hotel_ci->session->userdata ( $this->_hotel_ci->inter_id . '_room_hotel_id' );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$data ['hotel'] = $this->_hotel_ci->Hotel_model->get_hotel_detail ( $this->_hotel_ci->inter_id, $this->hotel_id);
		$data['pagetitle']=$data ['hotel']['name'];
		// Visit log
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->session->userdata ( 'inter_id' ),
			                                 'title' => $data ['hotel'] ['name'],
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'visit_time' => date ( 'Y-m-d H:i:s' ),
			                                 'des' => '查看酒店周边'
		                                 ) );
		return $data;
	}
	function bookroom() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
        $this->_hotel_ci->load->model ( 'hotel/Coupon_model' );



		$hotel_id = intval ( $this->_hotel_ci->input->post ( 'hotel_id' ) );
		$room_id = intval ( $this->_hotel_ci->input->post ( 'room_id' ) );
		$data ['price_codes'] = $this->_hotel_ci->input->post ( 'price_codes' );
		$data ['price_type'] = $this->_hotel_ci->input->post ( 'price_type' );

		$startdate = $this->_hotel_ci->input->post ( 'startdate' );
		$enddate = $this->_hotel_ci->input->post ( 'enddate' );



		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate,$this->_hotel_ci->inter_id,$hotel_id);
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		$data ['hotel_id'] = $hotel_id;
		$datas = $this->_hotel_ci->input->post ( 'datas', TRUE );
		$price_codes = json_decode ( $data ['price_codes'], TRUE );
		$price_type = json_decode ( $data ['price_type'], TRUE );
		$type = $this->_hotel_ci->input->post ( 'type', TRUE );
		$package_info = json_decode ( $this->_hotel_ci->input->post ( 'package_info' ), TRUE );
		$countday = get_room_night($data ['startdate'],$data ['enddate'],'ceil',$data);//至少有一个间夜


		if (empty ( $datas ) || empty ( $price_codes )) {
			return array('redirect'=>\Hotel_base::inst()->get_url('INDEX',array('h'=>$hotel_id,'type'=>$type)));
		}
		$data ['hotel'] = $this->_hotel_ci->Hotel_model->get_hotel_detail ( $this->_hotel_ci->inter_id, $hotel_id, array (
			'img_type' => array (
				'hotel_service',
				'hotel_lightbox'
			)
		) );
		
		
		$data_arr = json_decode ( $datas, TRUE );
		foreach ( $data_arr as $key => $value ) {
			if ($value == 0) {
				unset ( $data_arr [$key] );
			}
		}
		$data ['room_list'] = $this->_hotel_ci->Hotel_model->get_rooms_detail ( $this->_hotel_ci->inter_id, $hotel_id, array_keys ( $data_arr ), array (
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
			'openid' => $this->_hotel_ci->openid,
			'member_level' => $this->member_lv,
			'hotel_id'=> $hotel_id
		);
		// if ( $this->member_lv !='') {
		$this->_hotel_ci->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->_hotel_ci->Member_model->level_privilege ( $this->_hotel_ci->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}

		// }
		$protrol_code = $this->_hotel_ci->input->post ( 'protrol_code' );
		if (! empty ( $protrol_code ) && array_key_exists ( 'protrol', $price_type )) {
			$protrol_price_code = $this->_hotel_ci->Order_model->get_protrol_price_code ( $this->_hotel_ci->inter_id, $hotel_id, $protrol_code );
		}
		if (! empty ( $protrol_price_code )) {
			$price_codes [] = $protrol_price_code;
			$condit ['price_type'] = array (
				'protrol'
			);
		}
		$condit ['price_codes'] = implode ( ',', $price_codes );
		$this->_hotel_ci->load->library ( 'PMS_Adapter', array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), 'pmsa' );
		if($type){
			$condit ['only_type'] = $type;
		}
		$data ['rooms'] = $this->_hotel_ci->pmsa->get_rooms_change ( $data ['room_list'], array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), $condit, true );
		reset($data ['rooms']);
		$data ['first_room'] = current ( $data ['rooms'] );
		$data ['first_state'] = $data ['first_room'] ['state_info'] [$price_codes [$data ['first_room'] ["room_info"] ['room_id']]];
		
		//预订政策
		$data ['bookpolicy_condition'] = !empty($data ['first_state']['bookpolicy_condition'])?$data ['first_state']['bookpolicy_condition']:'';
		$data ['customer_condition'] = !empty($data ['first_state']['customer_condition'])?$data ['first_state']['customer_condition']:array();
		//Extra Info
		$data['extra_info']=!empty($data['first_state']['extra_info'])?$data['first_state']['extra_info']:[];
		
		if (!empty($data  ['customer_condition']['multi_fill'])&&!empty($data ['customer_condition']['adult']['num'])){
		    $data['show_multi_inner']=1;
		    if ($data ['customer_condition']['adult']['num']>1||!empty($data ['customer_condition']['child']['num'])){
		        $data ['customer_condition']['show_first']=1;
		    }else{
		        $data ['customer_condition']['show_first']=0;
		    }
		    $data['min_child_birthday']= date('Y-m-d',strtotime('- 4015 day',time()));
		    $data['max_child_birthday']=date('Y-m-d',strtotime('- 1095 day',time()));
		}

		if (empty ( $data ['first_state'] )) {
			return array('redirect'=>\Hotel_base::inst()->get_url('INDEX',array('h'=>$hotel_id,'type'=>$type)));
		}
		
		$data['packages']=array();
        $data['post_packages']='';
		$data['packages_price']=0;
		if (!empty($data ['first_state']['goods_info']['items'])){
		    if (!empty($data ['first_state']['is_packages'])&&empty($package_info)&&$data ['first_state']['goods_info']['sale_way']!=2){
		        return array('redirect'=>\Hotel_base::inst()->get_url('INDEX',array('h'=>$hotel_id,'type'=>$type)));
		    }else if (!empty($package_info)){
        		$goods_ids=array_column($data ['first_state']['goods_info']['items'], 'gs_id');
    		    $this->_hotel_ci->load->model('hotel/goods/Goods_order_model');
                $package_check = $this->_hotel_ci->Goods_order_model->check_order_package($this->_hotel_ci->inter_id,$data ['first_state']['goods_info'],$package_info,array('startdate'=>$data ['startdate'],'enddate'=>$data ['enddate'],'roomnums'=>1));
    		    if ($package_check['s']==0){
    		        return array('redirect'=>\Hotel_base::inst()->get_url('INDEX',array('h'=>$hotel_id,'type'=>$type)));
    		    }else{
    		        $data['packages']=$package_check['data'];
    		        $data['packages_price']=array_sum(array_column($data['packages'], 'total_price'));
    		        if (isset($package_check['min_package_num'])){
    		            $package_check['min_package_num'] < $data['first_state']['least_num'] and $data['first_state']['least_num'] = $package_check['min_package_num'];
    		        }
    		    }
		    }
		}else if (!empty($package_info)){
		    return array('redirect'=>\Hotel_base::inst()->get_url('INDEX',array('h'=>$hotel_id,'type'=>$type)));
		}

        if(!empty($data['packages'])){
            $post_package = array();
            foreach($data['packages'] as $temp_packages){
                $post_package[$temp_packages['goods_id']] = array(
                    'gid'=>$temp_packages['goods_id'],
                    'nums'=>$temp_packages['nums']
                );
            }
            $data['post_packages'] = json_encode($post_package);
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

		$this->_hotel_ci->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $hotel_id, array (
			'PRICE_EXCHANGE_POINT',
			'BANCLANCE_COMSUME_CODE_NEED',
			'POINT_EXCHANGE_ROOM',
			'COUPON_TIPS',
            'BONUS_VIEW_SETTING',
            'POINT_PAY_CODE_NEED',
			'POINT_NAME',
			'HOTEL_PREPAY_FAVOUR',
            'BOOK_ADDIT_SERVICE',
            'MORE_ROOM_NO_FAVOUR'
        ) );
        if(!empty($config_data['MORE_ROOM_NO_FAVOUR'])){
            $data['no_favour']=$config_data['MORE_ROOM_NO_FAVOUR'];
        }else{
            $data['no_favour'] = 0;
        }
		$this->_hotel_ci->load->model ( 'hotel/Service_model' );
		$data['addit_service']=array();
		if (!empty($config_data['BOOK_ADDIT_SERVICE'])){
    	    $data['addit_service']=$this->_hotel_ci->Service_model->format_book_service(json_decode($config_data['BOOK_ADDIT_SERVICE'],TRUE));
		}
		//优惠券温馨提示
		if(!empty($config_data['COUPON_TIPS'])){
			$data['coupon_tips']=nl2br($config_data['COUPON_TIPS']);
		}
		
		$data['point_name']=empty($config_data['POINT_NAME'])?'积分':$config_data['POINT_NAME'];
		$prepay_favour=isset($config_data['HOTEL_PREPAY_FAVOUR'])?json_decode($config_data['HOTEL_PREPAY_FAVOUR'],TRUE):array();

		// @author lGh 2016-03-14 钟点房
		$data ['athour'] = 0;
		if ($data ['first_state'] ['price_type'] == 'athour') {
			$data ['athour'] = 1;
		}
		$this->_hotel_ci->load->model ( 'hotel/Service_model' );
		$this->_hotel_ci->load->model ( 'hotel/Price_code_model' );
		if (! empty ( $data ['first_state'] ['add_service_set'] )) {
			$data ['services'] = $this->_hotel_ci->Service_model->replace_service ( $this->_hotel_ci->inter_id, array (
				'service_type' => 'hotel_order',
				'status' => 1,
				'add_occasion' => array (
					'hotel_order_before',
					'hotel_order_both'
				)
			), $data ['first_state'] ['add_service_set'] );
			$data ['services'] = $this->_hotel_ci->Service_model->classify_service ( $data ['services'] );
		}

		if (! empty ( $data ['first_state'] ['time_condition'] ['book_time'] )) {
			$min_hour = empty ( $data ['first_state'] ['time_condition'] ['min_hour'] ) ? 0 : $data ['first_state'] ['time_condition'] ['min_hour'] *3600;
			$order_times = $this->_hotel_ci->Price_code_model->get_book_time ( $data ['first_state'] ['time_condition'] ['book_time'], $min_hour );
			$data ['first_state'] ['time_condition'] ['book_times'] = $order_times ['book_times'];
			$data ['first_state'] ['time_condition'] ['last_time'] = $order_times ['last_time'];
		}
		if (! empty ( $data ['first_state'] ['time_condition'] ['book_time_range'] )) {
			$min_hour = empty ( $data ['first_state'] ['time_condition'] ['min_hour'] ) ? 0 : $data ['first_state'] ['time_condition'] ['min_hour'] *3600;
			$order_times = $this->_hotel_ci->Price_code_model->get_book_time ( $data ['first_state'] ['time_condition'] ['book_time'], $min_hour );
			$data ['first_state'] ['time_condition'] ['book_times'] = $order_times ['book_times'];
			$data ['first_state'] ['time_condition'] ['last_time'] = $order_times ['last_time'];
		}
		if ($data ['athour'] == 1) {
			if (! empty ( $data ['services'] ['add_time'] )) {
				$data ['add_time_service'] = current ( $data ['services'] ['add_time'] );
				$begin_time = empty ( $data ['first_state'] ['condition'] ['last_time'] ) ? date ( 'YmdH00', strtotime ( '+ 1 hour', time () ) ) : $data ['first_state'] ['condition'] ['last_time'];
				$max_time = empty ( $data ['first_state'] ['condition'] ['book_time'] ['e'] ) ? 0 : $data ['first_state'] ['condition'] ['book_time'] ['e'];
				$data ['add_time_service'] ['add_times'] = $this->_hotel_ci->Service_model->check_service_rule ( 'add_time', array (
					'begin_time' => $begin_time,
					'max_time' => $max_time,
					'max_num' => $data ['add_time_service'] ['max_num']
				) );
			}
		}

		$data ['room_count'] = array_sum ( $data_arr );
		$this->_hotel_ci->load->model ( 'pay/Pay_model' );
		$this->_hotel_ci->load->helper ( 'date' );
		$pay_days = get_day_range ( $data ['startdate'], $data ['enddate'], 'array' );
		array_pop ( $pay_days );
		$data ['pay_ways'] = $this->_hotel_ci->Pay_model->get_pay_way ( array (
			                                                     'inter_id' => $this->_hotel_ci->inter_id,
			                                                     'module' => $this->module,
			                                                     'status' => 1,
			                                                     'exclude_type' => $no_pay_ways,
			                                                     'check_day' => 1,
			                                                     'hotel_ids' => $hotel_id,
																 'not_show'=>1
		                                                     ), $pay_days );
		//积分支付配置
		$data['point_pay_set']=array();
		//判断是否使用PMS自定义的积分换房规则
		$is_pms_reduce=false;
		if(!empty($config_data['POINT_EXCHANGE_ROOM'])){
			$code_point_set = json_decode($config_data['POINT_EXCHANGE_ROOM'], true);
			if(!empty($code_point_set['is_pms'])){
				$is_pms_reduce = true;
			}
		}

		$data ['has_point_pay']=0;
		$has_favour_ways=array();
		$no_favour_ways=array();
		$data['extra_pointpay_para']=array();
		foreach ($data ['pay_ways'] as $k=>$pay_way){
			$data ['pay_ways'][$k]->favour=0;
			$data ['pay_ways'][$k]->des='';
			$data ['pay_ways'][$k]->cosume_code_need = 0;
			switch ($pay_way->pay_type){
				case 'point':
					//PMS的自定义积分计算 Add By 鹏 On 2016-10-17
					$point_params=[
						'countday'     => $countday,
						'startdate'    => $startdate,
						'enddate'      => $enddate,
						'openid'       => $this->_hotel_ci->openid,
						'total_price'  => $data ['total_price'],
						'roomnums'     => $data ['room_count'],
						'hotel_id'     => $data ['hotel_id'],
						'room_id'      => $data ['first_room'] ["room_info"] ['room_id'],
						'bonus'        => isset($this->common_data ['member']->bonus) ? $this->common_data ['member']->bonus : 0,
						'member_level' => $this->member_lv,
						'price_code'   => current($price_codes),
						'is_pms_reduce'=>$is_pms_reduce,
						'point_name'=>$data['point_name'],
						'check_point_name'=>1
					];
					if (!empty($data ['first_state']['pms_point'])){
					    $point_params['extra_para']['pms_point']=$data ['first_state']['pms_point'];
					    $point_params['extra_para']['pms_total_point']=$data ['first_state']['pms_total_point'];
					    $data['extra_pointpay_para']=$point_params['extra_para'];
					}
					$point_pay_set = $this->_hotel_ci->Member_model->point_pay_check($this->_hotel_ci->inter_id, $point_params);
					//End PMS point

					$data['point_pay_set'] = $point_pay_set['pay_set'];
					if((!empty($point_pay_set['can_exchange']) && $point_pay_set['can_exchange'] == 1)||count($data['pay_ways'])==1){
						$data ['pay_ways'][$k]->point_need = $point_pay_set['point_need'];
						$data ['pay_ways'][$k]->des = $point_pay_set['des'];

						if (count($data['pay_ways'])==1){
							$data['total_point']=$point_pay_set['point_need'];
							if (empty($point_pay_set['point_need'])){
								$data ['pay_ways'][$k]->des='-/'.$point_params['bonus'];
								$data ['total_point']='-';
								$data ['pay_ways'][$k]->disable = 1;
							}else if(empty($point_pay_set['can_exchange'])){
								$data ['pay_ways'][$k]->disable = 1;
							}
						}
					} else{
						if(count($data['pay_ways']) > 1){
							if (!empty($point_pay_set['point_need'])){
								$data ['pay_ways'][$k]->point_need = $point_pay_set['point_need'];
								$data ['pay_ways'][$k]->des = $point_pay_set['des'];
								$data ['pay_ways'][$k]->disable = 1;
							}else{
								unset($data ['pay_ways'][$k]);
							}
						}
					}
					if (isset($data ['pay_ways'][$k])&&empty($data ['pay_ways'][$k]->des)&&!empty($data ['pay_ways'][$k]->point_need)){
						$data ['pay_ways'][$k]->des=$data ['pay_ways'][$k]->point_need.'/'.$data['member']->bonus;
					}
					$data ['has_point_pay']=1;
					if (! empty ( $config_data ['POINT_PAY_CODE_NEED'] ) && $config_data ['POINT_PAY_CODE_NEED'] == 1) {
					    $data ['pay_ways'][$k]->cosume_code_need = 1;
					}
					break;
				case 'balance':
					$data ['pay_ways'][$k]->des=$data['member']->balance.'元';
					if (! empty ( $config_data ['BANCLANCE_COMSUME_CODE_NEED'] ) && $config_data ['BANCLANCE_COMSUME_CODE_NEED'] == 1) {
					    $data ['pay_ways'][$k]->cosume_code_need = 1;
					}
					break;
				case 'weixin':
					if (!empty($prepay_favour[$pay_way->pay_type])){
						$data ['pay_ways'][$k]->favour=$prepay_favour[$pay_way->pay_type];
						$data ['pay_ways'][$k]->des='立减'.$data ['pay_ways'][$k]->favour;
					}else if(!empty($data ['first_state']['bookpolicy_condition']['wxpay_favour'])&&$data ['first_state']['bookpolicy_condition']['wxpay_favour']>0){
						$data ['pay_ways'][$k]->favour=$data ['first_state']['bookpolicy_condition']['wxpay_favour'];
						$data ['pay_ways'][$k]->des='立减'.$data ['pay_ways'][$k]->favour;
					}
				default:
					break;
			}
			empty($data ['pay_ways'][$k]->des) or $data ['pay_ways'][$k]->des='('.$data ['pay_ways'][$k]->des.')';
			if (isset($data ['pay_ways'][$k])){
				if ($data ['pay_ways'][$k]->favour>0){
					$has_favour_ways[]=$data ['pay_ways'][$k];
				}else {
					$no_favour_ways[]=$data ['pay_ways'][$k];
				}
			}
		}
		$data['extra_pointpay_para']=json_encode($data['extra_pointpay_para']);
		unset($data ['pay_ways']);
		usort($has_favour_ways, function ($a, $b){
			return $b->favour != $a->favour?$b->favour - $a->favour:0;
		});
		$data ['pay_ways']=array_merge($has_favour_ways,$no_favour_ways);

        if(!empty($data ['post_packages'])){
            foreach($data ['pay_ways'] as $ways_key => $payways_arr){
                if($payways_arr->pay_type =='weixin'){
                    $temp_pay_ways[] =  $payways_arr;
                }
            }
            $data['pay_ways'] = $temp_pay_ways;
        }

		$data ['source_data'] = json_encode ( $data_arr );
		$last_orders = $this->_hotel_ci->Order_model->get_last_order ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, 1, false );
// 		$data ['member'] = $this->pub_pmsa->check_openid_member ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, array (
// 			'create' => TRUE,
// 			'update' => TRUE
// 		) );
		$data ['member'] = $this->_hotel_ci->member_info;
		isset($data['member']->bonus) or $data['member']->bonus=0;
		empty ( $last_orders ) ?  : $data ['last_order'] = $last_orders [0];

		//@Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
		$point_condit = array (
			'startdate' => $data ['startdate'],
			'enddate' => $data ['enddate'],
			'nums' => current($data_arr),
			'openid' => $this->_hotel_ci->openid,
			'member_level' => $this->member_lv,
			'room_id'=>key($data_arr),
			'price_code'=>current($price_codes),
			'bonus'=> isset($data['member']->bonus)?$data['member']->bonus:0,
			'hotel_id'=>$hotel_id,
			'total_price'=>$data['total_price'],
			'roomnums'=>$data ['room_count'],
			'paytype'=>empty($data ['pay_ways'])?'':$data ['pay_ways'][0]->pay_type,
			'point_name'=>$data['point_name'],
			'check_point_name'=>1
		);

		$point_consum_set = $this->_hotel_ci->Member_model->get_point_consum_rate ( $this->_hotel_ci->inter_id, $this->member_lv,'room',$member_privilege,$point_condit );

		//@Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
		$data ['point_consum_set']=$point_consum_set ['part_set'];
		$data ['point_consum_rate']=$point_consum_set ['consum_rate'];

		// @author lGh 2016-4-6 21:34:15 积分换房
		$avg_price = floatval ( $data ['total_price'] / $countday );

		if (! empty ( $config_data ['PRICE_EXCHANGE_POINT'] )) {
			$this->_hotel_ci->load->model ( 'hotel/Member_model' );
			$data ['point_exchange'] = $this->_hotel_ci->Member_model->room_point_exchange ( $this->_hotel_ci->inter_id, $data ['member'], array (
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

        //积分支付密码
        $data ['point_pay_code'] = 0;
        if (! empty ( $config_data ['POINT_PAY_CODE_NEED'] ) && $config_data ['POINT_PAY_CODE_NEED'] == 1) {
            $data ['point_pay_code'] = 1;
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
		$data['extra_para']=json_encode($data['extra_para']);

		$data['select_coupon_favour'] = 0;
		$data['use_coupon_code'] = array();
		$data['use_coupon']=array();
		$data['select_coupons'] = array();
        if (empty($data ['first_state']['condition']['no_coupon'])&&empty($data ['first_state']['coupon_condition']['no_coupon'])){
            if(!isset($temp_pay_ways)){
                reset($data['pay_ways']);
            }

        $coupon_condition = array (
            'startdate' => $startdate,
            'enddate' => $enddate,
            'hotel' => $hotel_id,
            'price_code'=>current($price_codes),
            'product'=>array(key($data_arr)),
            'category'=>key($data_arr),
            'rooms'=>1,
            'product_num'=>1,
            'extra_para'=>json_decode($data['extra_para']),
            'level'=>array($this->member_lv),
            'amount'=>$data['total_price'],
                'paytype'=>current($data['pay_ways'])->pay_type

        );


        $cardlist = $this->_hotel_ci->Coupon_model->select_coupon($coupon_condition);   //自动选择优惠券
        $select_coupon = isset($cardlist['cards'])?$cardlist['cards']:'';

        if(isset($select_coupon['selected']) && !empty($select_coupon['select_coupon_favour'])){
            $data['select_coupon_favour'] = $select_coupon['select_coupon_favour'];
            foreach($select_coupon['selected'] as $arr_coupon){
                    $data['use_coupon_code'][] = $arr_coupon['code'];
                    $data['use_coupon'][$arr_coupon['code']] = $arr_coupon['reduce_cost'];
                    $data['select_coupons'][] = $arr_coupon;
            }
//            $data['total_price'] = $data['total_price'] - $select_coupon['select_coupon_favour'];
            }
        }


        if(isset($config_data['BONUS_VIEW_SETTING'])){
            $data['bonus_setting'] = $config_data['BONUS_VIEW_SETTING'];
        }else{
            $data['bonus_setting'] = 0;
        }

        $data['exchange_max_point'] = isset($data['member']->bonus)?$data['member']->bonus:0;
        if(isset($point_consum_set['part_set']['max_use']) && !empty($point_consum_set['part_set']['max_use'])){
            if($point_consum_set['part_set']['max_use'] < $data['member']->bonus){
                $data['exchange_max_point'] = $point_consum_set['part_set']['max_use'];
            }
        }
        if($data['total_price'] < $data['exchange_max_point']*$point_consum_set ['consum_rate']){
            $data['exchange_max_point'] = round($data['total_price']/$point_consum_set ['consum_rate']);
        }
        if($data['exchange_max_point']*$point_consum_set ['consum_rate']>=$data['total_price']&&$point_consum_set ['consum_rate']>0){
            $data['exchange_max_point'] = ($data['total_price']-1)/$point_consum_set ['consum_rate'];
        }
        if(isset($point_consum_set['part_set']['use_rate']) && !empty($point_consum_set['part_set']['use_rate'])){
            if(fmod($data['exchange_max_point'],$point_consum_set['part_set']['use_rate']) !=0){
                $data['exchange_max_point'] = $data['exchange_max_point'] - fmod($data['exchange_max_point'],$point_consum_set['part_set']['use_rate']);
            }
        }

        $data['exchange_max_point'] = floor($data['exchange_max_point']);

        $data['paytype_icon'] =  $this->_hotel_ci->Hotel_model->bigger_payways_icon();

        $invoice_id = $this->_hotel_ci->input->get('eid');
        if(isset($invoice_id)){
            $this->_hotel_ci->load->model ( 'invoice/Invoice_model' );
            $data['invoice'] =  $this->_hotel_ci->Invoice_model->getInvoiceById($this->_hotel_ci->openid,$invoice_id);
        }

		$data ['type'] = $type;
		if($type == 'athour'){
			//读取售卖时间段 缺省
			if(!isset($data ['first_state']['time_condition']) || empty($data ['first_state']['time_condition'])){
				return array('redirect'=>\Hotel_base::inst()->get_url('INDEX',array('h'=>$hotel_id,'type'=>$type)));
			}else{
				$saletime_start = date('Ymd').$data ['first_state']['time_condition']['book_time']['s'].'00';
				$saletime_end = date('Ymd').$data ['first_state']['time_condition']['book_time']['e'].'00';
				$saletime_mod = $data ['first_state']['time_condition']['book_time']['mod'];
			}
			$thistime = date('YmdHis');
			$selecttime = array();
			if($thistime<=$saletime_start){
				$thistime = $saletime_start;
			}else{
				if($saletime_mod == 60){
					 $thistime = date('YmdHis',strtotime(substr($thistime,0,10).'0000') + 3600);
				}elseif($saletime_mod == 30){
					$nowminu = substr($thistime,-4,2);
					if($nowminu < 30){
						$thistime = date('YmdHis',strtotime(substr($thistime,0,10).'0000') + 1800);
					}else{
						$thistime = date('YmdHis',strtotime(substr($thistime,0,10).'0000') + 3600);
					}
				}else{
					$saletime_mod = 60;
					$thistime = date('YmdHis',strtotime(substr($thistime,0,10).'0000') + 3600);
				}
			}
			while ( $thistime <= $saletime_end) {
				$selecttime[] = date('H:i',strtotime($thistime));
				$thistime = date('YmdHis',strtotime($thistime)+$saletime_mod*60);
			}
			$data['selecttime'] = $selecttime;
		}
		return $data;

	}

	function saveorder() {

		// Visit log
		$now=date ( 'Y-m-d H:i:s' );

		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_config_model' );
		$startdate = date ( 'Ymd', strtotime ( $this->_hotel_ci->input->post ( 'startdate' ) ) );
		$enddate = date ( 'Ymd', strtotime ( $this->_hotel_ci->input->post ( 'enddate' ) ) );
		$hotel_id = intval ( $this->_hotel_ci->input->post ( 'hotel_id' ) );
		$price_codes = json_decode ( $this->_hotel_ci->input->post ( 'price_codes' ), TRUE );
		$datas = $this->_hotel_ci->input->post ( 'datas' );
		$price_type = json_decode ( $this->_hotel_ci->input->post ( 'price_type' ), TRUE );
		$coupons = json_decode ( $this->_hotel_ci->input->post ( 'coupons' ), TRUE );
		$roomnos = json_decode ( $this->_hotel_ci->input->post ( 'roomnos' ), TRUE );
		// @author lGh 加服务配置
		$add_service = json_decode ( $this->_hotel_ci->input->post ( 'add_service' ), TRUE );
		$extra_formdata = json_decode($this->_hotel_ci->input->post ( 'extra_formdata' ),TRUE);
		$custom_remark = $this->_hotel_ci->input->post ( 'custom_remark',TRUE );
		$consume_code = $this->_hotel_ci->input->post ( 'consume_code' );
        $bonus_consume_code = $this->_hotel_ci->input->post ( 'consume_code' );
        $point_pay_code = $this->_hotel_ci->input->post ( 'consume_code' );
        $package_info = json_decode ( $this->_hotel_ci->input->post ( 'package_info' ), TRUE );
    
		$name = htmlspecialchars ( $this->_hotel_ci->input->post ( 'name' ) );
		$tel = htmlspecialchars ( $this->_hotel_ci->input->post ( 'tel' ) );
		$this->_hotel_ci->load->helper('validate');
		if(!check_phone($tel)){
			$info ['s'] = 0;
			$info ['errmsg'] = '请输入正确的手机号码';
			return $info;
		}
		$email = $this->_hotel_ci->input->post ( 'email' ) ;
		$paytype = htmlspecialchars ( $this->_hotel_ci->input->post ( 'paytype' ) );
		$bonus = intval ( $this->_hotel_ci->input->post ( 'bonus' ) );
		$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $hotel_id, array (
			'HOTEL_ORDER_ENSURE_WAY',
			// 'HOTEL_IS_PMS',
			'PMS_AFT_SUBMIT',
			'PRICE_EXCHANGE_POINT',
			'BANCLANCE_COMSUME_CODE_NEED' ,
			'HOTEL_BONUS_CONFIG',
			'HOTEL_BALANCE_PART_PAY',
			'PMS_POINT_REDUCE_WAY',
			'POINT_EXCHANGE_ROOM',//积分换房
            'BONUS_COMSUME_CODE_NEED',
            'POINT_PAY_NEED_CODE',
			'HOTEL_PREPAY_FAVOUR',
			'POINT_NAME',
		    'ORDER_DBL_SUBMIT_CHECK',
		    'BOOK_ADDIT_SERVICE',
		    'BOOK_DATE_VALIDATE',
		    'PAID_ORDER_NOT_AUTO_ENSURE',
		    'MIN_START_DATE',
            'MORE_ROOM_NO_FAVOUR'
		) );
		//检查是否二次提交订单
		if(!empty($config_data['ORDER_DBL_SUBMIT_CHECK'])){
			$this->_hotel_ci->load->helper('common');
			$this->_hotel_ci->load->library('Cache/Redis_proxy', array(
				'not_init'    => FALSE,
				'module'      => 'common',
				'refresh'     => FALSE,
				'environment' => ENVIRONMENT
			), 'redis_proxy');
			$redis = $this->_hotel_ci->redis_proxy;
			$dbl_chk_key=$this->_hotel_ci->inter_id.':order_dbl_check:'.$this->_hotel_ci->openid;
			//判断是否存在KEY
			if($redis->exists($dbl_chk_key)){
				//直接退出
				$info=[
					's'=>0,
				    'errmsg'=>'您的订单正在提交，预计需要30秒左右，请耐心等待！',
				    'wait_time'=>10,
				    'link'=>\Hotel_base::inst()->get_url('MYORDER'),
				];
				if($paytype=='weixin'){
					$info['confirm_text']='支付未完成，请继续支付';
				}
				return $info;
			}
			//保存KEY值，过期时间为判断的时间间隔
			$redis->set($dbl_chk_key,1,intval($config_data['ORDER_DBL_SUBMIT_CHECK']));
		}
		$point_name=empty($config_data['POINT_NAME'])?'积分':$config_data['POINT_NAME'];
		$info = array ('s'=>0,'errmsg'=>'');
		$order_data = array ();
		$order_additions = array ();
		
		if (isset($custom_remark)){
		    if (mb_strlen($custom_remark,'UTF-8')>256){
		        $info ['s'] = 0;
		        $info ['errmsg'] = '备注信息过长，请重新填写';
				return $info;
		    }
    		$order_data['customer_remark']=$custom_remark;
		}else{
    		$order_data['customer_remark']='';
		}
		
		$this->_hotel_ci->load->helper('string');
		$name=trim_space($name);
		
		$this->_hotel_ci->load->model ( 'hotel/Service_model' );
		$book_formdata=isset($extra_formdata['addit_service'])?$extra_formdata['addit_service']:array();
		if (!empty($config_data['BOOK_ADDIT_SERVICE'])){
		    $check_service=$this->_hotel_ci->Service_model->check_book_formdata($book_formdata,json_decode($config_data['BOOK_ADDIT_SERVICE'],TRUE),'addit_service',array(
		            'startdate'=>$startdate,
		            'enddate'=>$enddate,
		            'openid'=>$this->_hotel_ci->openid,
		            'inter_id'=>$this->_hotel_ci->inter_id
		    ));
		    if ($check_service['s']==0){
		        unset($check_service['data']);
				return $check_service;

		    }else{
		        $order_additions['add_service_info']=isset($check_service['data'])?json_encode($check_service['data'],JSON_UNESCAPED_UNICODE):'';
		    }
		}
		
		//可预订的开始日期
		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate, $this->_hotel_ci->inter_id,0,$config_data);
		$enable_start = $date_check[0];

		if (empty($paytype)){
			$info ['s'] = 0;
			$info ['errmsg'] = '请选择支付方式';
			return $info;
		}

		if (! $datas || ! $name || ! $tel || ! strtotime ( $this->_hotel_ci->input->post ( 'startdate' ) ) || ! strtotime ( $this->_hotel_ci->input->post ( 'enddate' ) ) || $startdate < $enable_start || $enddate <= $startdate) {
			$info ['s'] = 0;
			$info ['errmsg'] = '请填写有效信息';
			return $info;
		}
		
		if (isset($email)&&empty($email)){
		    $info ['s'] = 0;
		    $info ['errmsg'] = '请填写邮箱地址';
		    echo json_encode ( $info );
		    exit ();
		}
		$email=htmlspecialchars($email);
		
		$hotel_row=$this->_hotel_ci->Hotel_model->get_hotel_detail($this->_hotel_ci->inter_id,$hotel_id);
		if(!empty($hotel_row['multiple_inner'])){
			$customer = [];
			
			if($this->_hotel_ci->input->post('customer') !== null && is_array($this->_hotel_ci->input->post('customer'))){
				$customer = array_map('trim_space', $this->_hotel_ci->input->post('customer'));
				foreach($customer as $v){
					if(!$v){
						$info ['s'] = 0;
						$info ['errmsg'] = '请填写有效信息';
						return $info;
						break;
					}
				}
			}
			//将主单的入住人放到最前
			array_unshift($customer, $name);
		}
		
		
		if (! empty ( $config_data ['BANCLANCE_COMSUME_CODE_NEED'] ) && $config_data ['BANCLANCE_COMSUME_CODE_NEED'] == 1 && $paytype == 'balance') {
			if (empty ( $consume_code )) {
				$info ['s'] = 0;
				$info ['errmsg'] = '请填写消费密码';
				return $info;
			}
		} else {
			$consume_code = '';
		}
        if (! empty ( $config_data ['BONUS_COMSUME_CODE_NEED'] ) && $config_data ['BONUS_COMSUME_CODE_NEED'] == 1 && !empty($bonus)) {
            if (empty ( $bonus_consume_code )) {
                $info ['s'] = 0;
                $info ['errmsg'] = '请填写消费密码';
				return $info;
            }
        } else {
            $bonus_consume_code = '';
        }
        if (! empty ( $config_data ['POINT_PAY_NEED_CODE'] ) && $config_data ['POINT_PAY_NEED_CODE'] == 1 && $paytype == 'point') {
            if (empty ( $point_pay_code )) {
                $info ['s'] = 0;
                $info ['errmsg'] = '请填写消费密码';
				return $info;
            }
        } else {
            $point_pay_code = '';
        }
//         if(! empty ( $config_data ['HOTEL_BONUS_CONFIG'] )){
//             $checkBonus=$bonus%100;
//             if ($checkBonus!=0) {
//                 $info ['s'] = 0;
//                 $info ['errmsg'] = '消费积分必须是100的倍数';
//                 echo json_encode ( $info );
//                 exit ();
//             }
//         }
		$data_arr = json_decode ( $datas, TRUE );
		foreach ( $data_arr as $key => $value ) {
			if ($value == 0) {
				unset ( $data_arr [$key] );
			}
		}
		$room_list = $this->_hotel_ci->Hotel_model->get_rooms_detail ( $this->_hotel_ci->inter_id, $hotel_id, array_keys ( $data_arr ), array (
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
			'openid' => $this->_hotel_ci->openid,
			'member_level' => $this->member_lv
		);
		// if ( $this->member_lv !='') {
		$this->_hotel_ci->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->_hotel_ci->Member_model->level_privilege ( $this->_hotel_ci->inter_id );
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}
		// }
		if (! empty ( $price_type )) {
			$condit ['price_type'] = array_keys ( $price_type );
		}
		$this->_hotel_ci->load->library ( 'PMS_Adapter', array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), 'pmsa' );
		$rooms = $this->_hotel_ci->pmsa->get_rooms_change ( $room_list, array (
			'inter_id' => $this->_hotel_ci->inter_id,
			'hotel_id' => $hotel_id
		), $condit, true );
		$order_additions ['third_favour_info'] = array ();
		$order_data ['price'] = 0;
		$no_pay_ways = array ();
		$order_data ['roomnums'] = array_sum ( $data_arr );

		$subs = array ();
		$room_codes = array ();
		if (empty ( $rooms )) {
			$info ['s'] = 0;
			$info ['errmsg'] = '无可订房间！';
			return $info;
		}

		$related_coupons=array();
		$first_state=array();
		foreach ( $rooms as $k => $rm ) {
			$code_price = $rm ['state_info'] [$price_codes [$k]];
            empty($first_state) and $first_state=$code_price;
			//@Editor lGh 2016-7-10 11:39:46 券关联
			if (!empty($code_price['coupon_condition']['couprel'])){
				$related_coupons[$code_price['coupon_condition']['couprel']]=1;
			}

			//@Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
			if (!empty($code_price['coupon_condition']['no_coupon'])&&!empty($coupons)){
				$info ['s'] = 0;
				$info ['errmsg'] = '此价格不能用券！';
				return $info;
			}
			if (!empty($code_price['bonus_condition']['no_part_bonus'])&&!empty($bonus)){
				$info ['s'] = 0;
				$info ['errmsg'] = '此价格不能用积分！';
				return $info;
			}
			if (!empty($code_price['bonus_condition']['poc'])&&(!empty($bonus)&&!empty($coupons))){
				$info ['s'] = 0;
				$info ['errmsg'] = '此价格不能同时使用积分与优惠券！请重新选择';
				return $info;
			}

			$room_info = $rm ['room_info'];
			$room_codes [$room_info ['room_id']] ['code'] ['price_type'] = $code_price ['price_type'];
			if (! empty ( $consume_code )) {
				$room_codes [$room_info ['room_id']] ['room'] ['consume_code'] = $consume_code;
			}
            if (! empty ( $bonus_consume_code )) {
                $room_codes [$room_info ['room_id']] ['room'] ['bonus_consume_code'] = $bonus_consume_code;
            }
            if (! empty ( $point_pay_code )) {
                $room_codes [$room_info ['room_id']] ['room'] ['point_pay_code'] = $point_pay_code;
            }
			$room_codes [$room_info ['room_id']] ['code'] ['extra_info'] = empty ( $code_price ['extra_info'] ) ? '' : $code_price ['extra_info'];
			$room_codes [$room_info ['room_id']] ['room'] ['webser_id'] = $rm ['room_info'] ['webser_id'];
			if ($code_price ['book_status'] != 'available') {
				$info ['s'] = 0;
				$info ['errmsg'] = '房间数不足！';
				return $info;
			}
			if (! empty ( $roomnos [$k] )) {
				$tmp_nos = array_keys ( $room_info ['number_realtime'] );
				foreach ( $roomnos [$k] as $rk => $no ) {
					if (! in_array ( $rk, $tmp_nos )) {
						$info ['s'] = 0;
						$info ['errmsg'] = $room_info ['name'] . ' 的房号' . $no . '已被选！';
						return $info;
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
			//子订单增加早餐数记录
			$subs [$room_info ['room_id']] ['breakfast_nums'] = !empty($code_price ['bookpolicy_condition']['breakfast_nums'])?$code_price['bookpolicy_condition']['breakfast_nums']:'';
			
			//多个入住人信息
			if(!empty($customer)){
				$subs[$room_info['room_id']]['customer']=$customer;
			}
		}
		
		if (!empty($first_state['customer_condition']['multi_fill'])){
    		$multi_inner=isset($extra_formdata['multi_inner'])?$extra_formdata['multi_inner']:array();
    		$check_inner=$this->_hotel_ci->Service_model->check_book_formdata($multi_inner,$first_state['customer_condition'],'multi_inner',array('roomnums'=>$order_data['roomnums'],'first_man'=>$name));
    		if ($check_inner['s']==0){
    		    unset($check_inner['data']);
				return $check_inner;
    		}else{
    		    $subs[key ( $data_arr )]['multi_inners']=$check_inner['data'];
    		}
		}
// 		$package_info=json_decode('{"3":{"gid":3,"num":1},"4":{"gid":4,"num":1}}',true);
		$package_data=array();
		if (!empty($first_state['goods_info']['items'])){
    		if (empty($package_info)&&$first_state['is_packages']==1&&$first_state['goods_info']['sale_way']!=2){
    		    $info['errmsg']='请选择套餐';
    		    return $info;
    		}else if (!empty($package_info)){
    		    $this->_hotel_ci->load->model('hotel/goods/Goods_order_model');
    		    $package_check = $this->_hotel_ci->Goods_order_model->check_order_package($this->_hotel_ci->inter_id,$first_state['goods_info'],$package_info,array('startdate'=>$startdate,'enddate'=>$enddate,'roomnums'=>$order_data['roomnums']));
    		    if ($package_check['s']==0){
    		        unset($package_check['data']);
    		        return $package_check;
    		    }else{
    		        $package_data=$package_check['data'];
    		        $order_data ['price']+=$package_check ['total_price'];
    		    }
    		}
		}else if (!empty($package_info)){
		    $info['errmsg']='此价格不能预订套餐';
		    return $info;
		}
		$order_additions ['room_codes'] = json_encode ( $room_codes );
// 		$member = $this->pub_pmsa->check_openid_member ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, array (
// 			'create' => TRUE,
// 			'update' => TRUE
// 		) );
		$member = $this->_hotel_ci->member_info;

		if ($paytype == 'bonus') {
			// @author lGh 2016-4-6 21:34:15 积分换房
			$countday = get_room_night($startdate, $enddate ,'ceil',$order_data);//至少有1个间夜
			$avg_price = floatval ( $order_data ['price'] / ($countday * $order_data ['roomnums']) );
			// $this->_hotel_ci->load->model ( 'hotel/Hotel_config_model' );
			// $config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $hotel_id, 'PRICE_EXCHANGE_POINT');
			if (! empty ( $config_data ['PRICE_EXCHANGE_POINT'] )) {
				$this->_hotel_ci->load->model ( 'hotel/Member_model' );
				$point_exchange = $this->_hotel_ci->Member_model->room_point_exchange ( $this->_hotel_ci->inter_id, $member, array (
					'countday' => $countday,
					'price' => $avg_price,
					'config' => $config_data ['PRICE_EXCHANGE_POINT'],
					'roomnums' => $order_data ['roomnums']
				) );
			}
			if (empty ( $point_exchange ) || $point_exchange ['can_exchange'] == 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '积分不足兑换！';
				return $info;
			} else {
				$order_additions ['point_favour'] = $order_data ['price'];
				$order_additions ['point_used'] = 1;
				$order_additions ['point_used_amount'] = $point_exchange ['point_need'];
				$order_data ['price'] -= $order_additions ['point_favour'];
				$bonus_paid = 1;
			}
		}

		// 使用代金券
		$coupon_rel=array();
		if ((!empty($related_coupons)||! empty ( $coupons )) && empty ( $bonus_paid )) {
			$this->_hotel_ci->load->model ( 'hotel/Coupon_model' );
			$params = array ();
			$params ['days'] = get_room_night($startdate,$enddate,'round',$order_data);//至少有1个间夜
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

			//获取券的额外参数
			$params ['startdate'] = $startdate;
			$params ['enddate'] = $enddate;
			$params['extra_para']=array();
			$first_room=current($room_list);
			if (!empty($first_room['webser_id'])){
				$params['extra_para']['web_room_id']=$first_room['webser_id'];
				if (!empty($room_codes [$first_room ['room_id']] ['code'] ['extra_info']['pms_code'])){
					$params['extra_para']['pms_code']=$room_codes [$first_room ['room_id']] ['code'] ['extra_info']['pms_code'];
				}
			}
			$coupon_check = $this->_hotel_ci->Coupon_model->check_coupon_using ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, $params, array_keys ( $coupons ),$coupons,$related_coupons );

			if ($coupon_check ['s'] == 0) {
				return $coupon_check;
			}
			$order_additions ['coupon_favour'] = $coupon_check ['coupon_amount'];
			$order_additions ['coupon_des'] = json_encode ( $coupon_check ['coupon_info'],JSON_UNESCAPED_UNICODE );
			$order_additions ['coupon_used'] = 1;
			if (!empty($coupon_check['coupon_rel'])){
				$coupon_rel=$coupon_check['coupon_rel'];
			}
			$order_data ['price'] -= $order_additions ['coupon_favour'];
			if ($order_data ['price'] <= 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '不能用那么多券哦！';
				return $info;
			}
		}

		// 部分使用积分
		if (! empty ( $bonus ) && ! empty ( $member ) && empty ( $bonus_paid )) {
			//@Editor lGh 2016-5-27 19:25:23 增加积分支付方式
			if ($paytype=='point') {
				$info ['s'] = 0;
				$info ['errmsg'] = '您选择了积分支付，不能再使用积分抵扣';
				return $info;
			}
			if ($bonus<=0){
				$info ['s'] = 0;
				$info ['errmsg'] = '请输入正确的积分数';
				return $info;
			}
			if ($member->bonus < $bonus) {
				$info ['s'] = 0;
				$info ['errmsg'] = '积分不足！';
				return $info;
			}

			//@Editor lGh 2016-7-29 11:59:33 积分兑换比例配置
			$point_condit = array (
				'startdate' => $startdate,
				'enddate' => $enddate,
				'nums' => $order_data ['roomnums'],
				'openid' => $this->_hotel_ci->openid,
				'member_level' => $this->member_lv,
				'room_id'=>key($data_arr),
				'price_code'=>current($price_codes),
				'bonus'=> isset($this->common_data ['member']->bonus)?$this->common_data ['member']->bonus:0,
				'hotel_id'=>$hotel_id,
				'used'=>$bonus,
				'paytype'=>$paytype,
				'roomnums'=>$order_data['roomnums'],
				'total_price'=>$order_data['price'],
				'point_name'=>$point_name,
				'check_point_name'=>1
			);

			$this->_hotel_ci->load->model ( 'hotel/Member_model' );
			$point_consum_rate = $this->_hotel_ci->Member_model->get_point_consum_rate ( $this->_hotel_ci->inter_id, $this->member_lv,'room',$member_privilege,$point_condit );
			if (! empty ( $point_consum_rate )) {
				if ($point_consum_rate['s']!=0 && !empty($point_consum_rate['consum_rate'])){
					$order_additions ['point_favour'] = $bonus * $point_consum_rate['consum_rate'];
					$order_additions ['point_used'] = 1;
					$order_additions ['point_used_amount'] = $bonus;
					$order_data ['price'] -= $order_additions ['point_favour'];
				}else if ($point_consum_rate['s']==0){
					$info ['s'] = 0;
					$info ['errmsg'] = $point_consum_rate['errmsg'];
					return $info;
				}
			}
			if ($order_data ['price'] <= 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = '不能用那么多积分哦！';
				return $info;
			}
		}

		//@Editor lGh 2016-5-27 19:21:22 增加积分支付方式 bonus为积分兑换，兑换后订单价格为0，point为积分支付，类似储值支付
		if ($paytype == 'point') {
			$countday = get_room_night($startdate,$enddate,'ceil',$order_data);//至少有1个间夜
			/**
			 * PMS的积分换房规则
			 * add by 鹏 On 2016-10-17
			 */
			$room_id=key($data_arr);
			$point_params=[
				'countday'     => $countday,
				'startdate'    => $startdate,
				'enddate'      => $enddate,
				'openid'       => $this->_hotel_ci->openid,
				'total_price'  => $order_data ['price'],
				'roomnums'     => $order_data ['roomnums'],
				'hotel_id'     => $hotel_id,
				'room_id'      => $room_id,
				'bonus'        => isset($this->common_data ['member']->bonus) ? $this->common_data ['member']->bonus : 0,
				'member_level' => $this->member_lv,
				'price_code'   => current($price_codes),
				'point_name'=>$point_name,
				'check_point_name'=>1
			];
			if (!empty($first_state['pms_point'])){
			    $point_params['extra_para']['pms_point']=$first_state['pms_point'];
			    $point_params['extra_para']['pms_total_point']=$first_state['pms_total_point'];
			}
			//判断是否使用PMS自定义的积分换房规则
			$point_params['is_pms_reduce']=false;
			if(!empty($config_data['POINT_EXCHANGE_ROOM'])){
				$code_point_set = json_decode($config_data['POINT_EXCHANGE_ROOM'], true);
				if(!empty($code_point_set['is_pms'])){
					$point_params['is_pms_reduce'] = true;
				}
			}

			$this->_hotel_ci->load->model('hotel/Member_model');
			$point_exchange = $this->_hotel_ci->Member_model->point_pay_check($this->_hotel_ci->inter_id, $point_params);

			if (empty ( $point_exchange ) || $point_exchange ['can_exchange'] == 0) {
				$info ['s'] = 0;
				$info ['errmsg'] = isset($point_exchange['errmsg'])?$point_exchange['errmsg']:'积分不足支付！';
				return $info;
			} else {
				$order_additions ['point_used'] = 1;
				$order_additions ['point_used_amount'] = $point_exchange['point_need'];
				$point_paid = 1;
			}
		}

		// 储值支付
		if ($paytype == 'balance') {
			if (empty ( $member ) || $member->balance < $order_data ['price']) {
				$info ['s'] = 0;
				$info ['errmsg'] = '余额不足！';
				return $info;
			}
		}

		$prepay_favour=isset($config_data['HOTEL_PREPAY_FAVOUR'])?json_decode($config_data['HOTEL_PREPAY_FAVOUR'],TRUE):array();
		if ($paytype == 'weixin') {
			if (isset($prepay_favour['weixin'])){
				$order_additions ['wxpay_favour'] = $prepay_favour['weixin'];
				$order_data ['price'] -= $order_additions ['wxpay_favour'];
			}else if(!empty($first_state['bookpolicy_condition']['wxpay_favour'])&&$first_state['bookpolicy_condition']['wxpay_favour']>0){
			    $order_additions ['wxpay_favour']=$first_state['bookpolicy_condition']['wxpay_favour'];
				$order_data ['price'] -= $order_additions ['wxpay_favour'];
			}
		}

        //检查是否多房间不能使用优惠
        if(isset($config_data['MORE_ROOM_NO_FAVOUR']) && $config_data['MORE_ROOM_NO_FAVOUR']==1 && array_sum ( $data_arr )>1 && (!empty($coupons) || $bonus!=0 || (isset($order_additions ['wxpay_favour']) && $order_additions ['wxpay_favour']>0))){
            $info ['s'] = 0;
            $info ['errmsg'] = '多间房不能使用优惠';
            return $info;
        }

		if ($order_data ['price'] <= 0 && empty ( $bonus_paid )) {
			$info ['s'] = 0;
			$info ['errmsg'] = '价格错误！';
			return $info;
		}

		// 保存订单
		$order_data ['hotel_id'] = $hotel_id;
		$order_data ['inter_id'] = $this->_hotel_ci->inter_id;
		$order_data ['openid'] = $this->_hotel_ci->openid;
		$order_data ['name'] = $name;
		$order_data ['tel'] = $tel;
		$order_data ['email'] = $email;
		$order_data ['startdate'] = $startdate;
		$order_data ['enddate'] = $enddate;
		$order_data ['status'] = 0;
		$order_data ['price_type'] = $condit ['price_type'][0];
		$order_additions['third_favour_info']=empty($order_additions['third_favour_info'])?'':json_encode($order_additions['third_favour_info']);
		//钟点房入住时间
		//读取售卖时间段
		if($condit ['price_type'][0] == 'athour'){
			$order_data ['enddate'] = $startdate;
			if(!isset($first_state['time_condition']) || empty($first_state['time_condition'])){
				$info ['s'] = 0;
				$info ['errmsg'] = '所选入住时间已过';
				return $info;
			}else{
				$saletime_start = date('Ymd').$first_state['time_condition']['book_time']['s'].'00';
				$saletime_end = date('Ymd').$first_state['time_condition']['book_time']['e'].'00';
			}
			$intime = date('Y-m-d ').$this->_hotel_ci->input->post ( 'intime' ).':00';
			if(strtotime($intime)<time() || strtotime($intime)<strtotime($saletime_start) || strtotime($intime)>strtotime($saletime_end)){
				$info ['s'] = 0;
				$info ['errmsg'] = '所选入住时间已过！';
				return $info;
			}
			$order_data ['starttime'] = $intime.':00';
		}

		$order_data ['paytype'] = $paytype; // 支付类型

		$order_data ['own_saler']=$this->_hotel_ci->my_saler_id;
		if (empty($this->_hotel_ci->my_saler_id)){
			$order_data ['link_saler']=$this->_hotel_ci->link_saler_id;
		}else{
			if (!empty($this->_hotel_ci->ori_saler_id)){
				$order_data ['link_saler']=$this->_hotel_ci->ori_saler_id;
			}else {
				$order_data ['link_saler']=$this->_hotel_ci->link_saler_id;
			}
		}
		if(empty($order_data ['link_saler'])){
			$this->_hotel_ci->load->model('distribute/Idistribute_model');
			$true_saler = $this->_hotel_ci->Idistribute_model->get_protection_saler($this->_hotel_ci->openid,$this->_hotel_ci->inter_id);
			if(!empty($true_saler)){
				$order_data ['link_saler'] = $true_saler;
			}
		}

		$this->_hotel_ci->load->model ( 'pay/Pay_model' );
		$pre_pay = $this->_hotel_ci->Pay_model->is_online_pay ( $order_data ['paytype'] );
		if ($pre_pay == 1) {
			$order_data ['status'] = 9;
		} else if (! empty ( $config_data ['HOTEL_ORDER_ENSURE_WAY'] ) && $config_data ['HOTEL_ORDER_ENSURE_WAY'] == 'instant') {
			$order_data ['status'] = 1;
		}

		if ($member){
			$order_data ['member_no'] = $member->mem_card_no;
			$order_data ['jfk_member_no'] = $member->jfk_member_no;
		}
		$info = $this->_hotel_ci->Order_model->create_order ( $this->_hotel_ci->inter_id, array (
			'main_order' => $order_data,
			'order_additions' => $order_additions,
			'coupon_rel'=>$coupon_rel,
		    'package_data'=>$package_data
		), $data_arr, $subs, $roomnos );
        $ori_info=$info;
        $msg=array();
		if ($info ['s'] == 1) {

			// if (! empty ( $config_data ['HOTEL_IS_PMS'] ) && $config_data ['HOTEL_IS_PMS'] == 1) {
			// if ((! empty ( $config_data ['PMS_PRE_SUBMIT'] ) && $config_data ['PMS_PRE_SUBMIT'] == 1) || $pre_pay != 1) {
			if ($pre_pay != 1) {
				$msg = $this->_hotel_ci->pmsa->order_submit ( $this->_hotel_ci->inter_id, $info ['orderid'], array (
					'room_codes' => $room_codes
				) );
				if ($msg ['s'] == 0) {
					$this->_hotel_ci->Order_model->handle_order ( $this->_hotel_ci->inter_id, $info ['orderid'], 10, $this->_hotel_ci->openid ,array('main_db'=>1)); // pms下单失败，退回
					$info = $msg;
				} else {
					$this->_hotel_ci->Order_model->handle_order ( $this->_hotel_ci->inter_id, $info ['orderid'], 'ss','',array('main_db'=>1) );
					if (!empty($info['has_paid'])||!empty($msg['has_paid'])){
					    $status = empty($config_data['PAID_ORDER_NOT_AUTO_ENSURE']) ? 1 :0 ;
						$this->_hotel_ci->Order_model->update_order_status ( $this->_hotel_ci->inter_id, $info ['orderid'], $status, $this->_hotel_ci->openid, true );
					}else if ($order_data ['status'] == 1) {
						$this->_hotel_ci->Order_model->handle_order ( $this->_hotel_ci->inter_id, $info ['orderid'], $order_data ['status'], $this->_hotel_ci->openid ,array('main_db'=>1));
					}else if(isset($msg['upstatus']) && $msg['upstatus'] == 1){
						$this->_hotel_ci->Order_model->update_order_status ( $this->_hotel_ci->inter_id, $info ['orderid'], 1, $this->_hotel_ci->openid, FALSE );
					}
				}
			} else {
				if ((empty ( $config_data ['PMS_AFT_SUBMIT'] ) || $config_data ['PMS_AFT_SUBMIT'] == 0)||(!empty($config_data ['PMS_POINT_REDUCE_WAY'])&&$config_data ['PMS_POINT_REDUCE_WAY']=='after'&&$paytype=='point')) {
					$msg = $this->_hotel_ci->pmsa->order_submit ( $this->_hotel_ci->inter_id, $info ['orderid'], array (
						'room_codes' => $room_codes
					) );
					if ($msg ['s'] == 0) {
						$this->_hotel_ci->Order_model->handle_order ( $this->_hotel_ci->inter_id, $info ['orderid'], 10, $this->_hotel_ci->openid,array('main_db'=>1) ); // pms下单失败，退回
						$info = $msg;
					}else{
						$this->_hotel_ci->Order_model->handle_order ( $this->_hotel_ci->inter_id, $info ['orderid'], 'ss','',array('main_db'=>1) );
						if (!empty($info['has_paid'])||!empty($msg['has_paid'])){
						    $status = empty($config_data['PAID_ORDER_NOT_AUTO_ENSURE']) ? 1 :0 ;
							$this->_hotel_ci->Order_model->update_order_status ( $this->_hotel_ci->inter_id, $info ['orderid'], $status, $this->_hotel_ci->openid, true );
						}
					}
				}
			}


            $invoice_id = $this->_hotel_ci->input->post('invoice');

            if($invoice_id !=0){
                $this->_hotel_ci->load->model ( 'invoice/Invoice_model' );
                $invoice_post = array(
                    'openid'=>$this->_hotel_ci->openid,
                    'inter_id'=>$this->_hotel_ci->inter_id,
                    'orderid'=>$info ['orderid'],
                    'invoice_id'=>$invoice_id,
                    'hotel_id'=>$hotel_id,
                    'amount'=>$order_data ['price'],
                    'createtime'=>date('Y-m-d H:i:s',time())
                );

                $invoice_info = $this->_hotel_ci->Invoice_model->getInvoiceById($this->_hotel_ci->openid,$invoice_id);

                $invoice_content = array(
                    'type'=>$invoice_info['type'],
                    'title'=>$invoice_info['title']
                );

                if(!empty($invoice_info['content'])){
                    $content = json_decode($invoice_info['content']);
                    if(!empty($content)){
                        foreach($content as $key=>$arr){
                            $invoice_content[$key] = $arr;
                        }
                    }
                }

                $invoice_post['invoice_content'] = json_encode($invoice_content);


                $get_invoice = $this->_hotel_ci->Invoice_model->book_invoice($invoice_post);

                if($get_invoice != 0 ){
                    $this->_hotel_ci->Invoice_model->update_order_invoice($this->_hotel_ci->openid,$this->_hotel_ci->inter_id,$info ['orderid']);
                }

            }
		}

		if (isset($info['errmsg'])&&$point_name!='积分'){
			$info['errmsg']=str_replace('积分', $point_name, $info['errmsg']);
		}


		// Visit log
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->session->userdata ( 'inter_id' ),
			                                 'title' => '提交订单',
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'visit_time' => date ( 'Y-m-d H:i:s' ),
			                                 'des' => $now.'-'.getIp().'-'.json_encode($ori_info,JSON_UNESCAPED_UNICODE).'-'.json_encode($msg,JSON_UNESCAPED_UNICODE)
		                                 ) );
		return $info;
	}
	function add_hotel_collection() {
		$hotel_id = $this->_hotel_ci->input->get ( 'hid' );
		$mark_title = $this->_hotel_ci->input->get ( 'hname' );
		$data = array (
			'mark_name' => $hotel_id,
			'inter_id' => $this->_hotel_ci->inter_id,
			'openid' => $this->_hotel_ci->openid,
			'mark_type' => 'hotel_collection',
			'mark_title' => $mark_title,
			'mark_link' => site_url ( 'hotel/hotel/index' ) . '?id=' . $this->_hotel_ci->inter_id . '&h=' . $hotel_id
		);
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		return $this->_hotel_ci->Hotel_model->add_front_mark ( $data );
	}
	function clear_visited_hotel() {
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->Hotel_model->update_mark_status ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, 2, 'hotel_visited', 'mark_type' );
		return 1;
	}
	function cancel_one_mark() {
		$mark_id = $this->_hotel_ci->input->get ( 'mid' );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->Hotel_model->update_mark_status ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, 2, $mark_id, 'mark_id' );
		return 1;
	}
	function orderdetail() {
		$data = $this->common_data;
		$order_condition=array (
			'openid' => $this->_hotel_ci->openid,
			'member_no' => $this->member_no,
			'idetail' => array (
				'i'
			)
		);
		$orderid = $this->_hotel_ci->input->get ( 'orderid' );
		if (!empty($orderid)){
		    $order_condition['orderid']=$orderid;
		    $oid=$order_condition['orderid'];
		}else{
		    $order_condition['oid'] = intval ( $this->_hotel_ci->input->get ( 'oid' ) );
    		$oid=$order_condition['oid'];
		}
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$order_condition ['package_condition'] = array('get_goods_info'=>2,'syn_status'=>1);
		$list = $this->_hotel_ci->Order_model->get_main_order ( $this->_hotel_ci->inter_id, $order_condition );
		if ($list) {
			$list = $list [0];
			$flag = 1;
			$comment = 0;
			$this->_hotel_ci->load->model ( 'common/Enum_model' );
			$this->_hotel_ci->load->model ( 'pay/Pay_model' );
			$data ['status_des'] = $this->_hotel_ci->Enum_model->get_enum_des ( array (
				                                                         'HOTEL_ORDER_STATUS',
				                                                         'PAY_WAY',
				                                                         'HOTEL_ORDER_PAY_STATUS'
			                                                         ), array (
				                                                         1,
				                                                         2
			                                                         ) ,$this->_hotel_ci->inter_id);
			$data ['pay_ways'] = $this->_hotel_ci->Pay_model->get_pay_way ( array (
					'inter_id' => $this->_hotel_ci->inter_id,
					'module' => 'hotel',
					'pay_type'=>array($list['paytype']),
					'key'=>'value'
			) );
			$data ['pay_ways']['bonus'] = new \stdClass();
			$data ['pay_ways']['bonus']->pay_name='积分兑换';
			$list ['paytype_des'] = $data ['pay_ways'][$list['paytype']]->pay_name;
			// 显示订单状态，判断评论和可否取消
			$this->_hotel_ci->load->model ( 'hotel/Order_check_model' );
			$state = $this->_hotel_ci->Order_check_model->check_order_state ( $list, $data ['status_des'] ['HOTEL_ORDER_STATUS'] );
			$list ['status_des'] = $state ['des'];
			$list ['show_orderid'] = empty ( $list ['web_orderid'] ) ? $list ['orderid'] : $list ['web_orderid'];
			$data ['not_same'] = $state ['not_same'];
			$data ['can_cancel'] = $state ['can_cancel'];
			$data ['can_comment'] = $state ['can_comment'];
			$data ['re_pay'] = $state ['re_pay'];
			$data['states']=$state;
			if ($state ['not_same'] == 0) {
				$data ['order_sequence'] = $this->_hotel_ci->Order_model->get_order_sequence ( $list ['status'] );
			}
			$week_arr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
			$data['startdate_weekday']=$week_arr[date('w',strtotime($list['startdate']))];
			$data['enddate_weekday']=$week_arr[date('w',strtotime($list['enddate']))];
			if ($state ['pms_check'] == 1) {
				$this->_hotel_ci->load->library ( 'PMS_Adapter', array (
					'inter_id' => $this->_hotel_ci->inter_id,
					'hotel_id' => $list ['hotel_id']
				), 'pmsa' );
				$this->_hotel_ci->pmsa->update_web_order ( $this->_hotel_ci->inter_id, $list );
			}

			$data ['order'] = $list;
			//additions room_codes数据
			$room_codes = json_decode($list['room_codes'], true);
			$room_codes = $room_codes [$list['first_detail']['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
			$extra_info=$room_codes['code']['extra_info'];
			$data['extra_info']=$extra_info;

			$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
			$data ['first_room'] = $this->_hotel_ci->Hotel_model->get_room_detail ( $this->_hotel_ci->inter_id, $list ['hotel_id'], $data ['order'] ['first_detail'] ['room_id'], array (
				'img_type' => 'hotel_room_service'
			) );

			$data ['hotel'] = $this->_hotel_ci->Hotel_model->get_hotel_detail ( $this->_hotel_ci->inter_id, $list['hotel_id']);
			if($list['status'] == 9 && ($list['paytype'] == 'weixin' || $list['paytype'] == 'weifutong' || $list['paytype'] == 'lakala' || $list['paytype'] == 'lakala_y' || $list['paytype'] == 'unionpay')){//微信未支付
				$this->_hotel_ci->load->model ( 'hotel/Order_queues_model' );
				$data['timeout'] = $this->_hotel_ci->Order_queues_model->get_over_time($list['orderid']);
			}else{
				$data['timeout'] = 0;
			}

            if(isset($data['order']['orderid'])){
                $this->_hotel_ci->load->model ( 'invoice/Invoice_model' );
                $data['invoice_info'] = $this->_hotel_ci->Invoice_model->check_order_invoice($data['order']['orderid']);
            }

		} else {
			return array('redirect'=>\Hotel_base::inst()->get_url('MYORDER'));
		}
		// Visit log
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->inter_id,
			                                 'title' => '订单详情',
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'des' => "订单id：" . $oid
		                                 ) );
		return $data;
	}
	function myorder() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$hl = $this->_hotel_ci->input->get ( 'hl' );
		$handled = isset ( $hl ) ? intval ( $hl ) : null;
		/*统一定时任务去取消
		$from_order = $this->_hotel_ci->input->get ( 'fro' );
		if (! empty ( $from_order )) {
			$info = $this->_hotel_ci->Order_model->cancel_order ( $this->_hotel_ci->inter_id, array (
				'openid' => $this->_hotel_ci->openid,
				'member_no' => $this->member_no,
				'orderid' => $from_order,
				'cancel_status' => 5,
				'no_tmpmsg' => 1,
				'delete' => 2,
				'idetail' => array (
					'i'
				)
			) );
		}*/
		$orders = $this->_hotel_ci->Order_model->get_main_order ( $this->_hotel_ci->inter_id, array (
			'openid' => $this->_hotel_ci->openid,
			'member_no' => $this->member_no,
			'handled' => $handled,
			'idetail' => array (
				'r'
			),
		    'no_goods_order'=>1
		) );
		$this->_hotel_ci->load->model ( 'common/Enum_model' );
		$status_des = $this->_hotel_ci->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
		$this->_hotel_ci->load->model ( 'hotel/Order_check_model' );
		foreach ( $orders as $ok => $o ) {
			$state = $this->_hotel_ci->Order_check_model->check_order_state ( $o, $status_des );
			$orders [$ok] ['status_des'] = $state ['des'];
			$orders[$ok]['can_comment']=$state['can_comment'];
			$orders[$ok]['can_cancel']=$state['can_cancel'];
			$orders[$ok]['re_pay']=$state['re_pay'];
			$orders[$ok]['pms_check']=$state['pms_check'];
			$orders[$ok]['not_same']=$state['not_same'];
			$orders[$ok]['orderstate']=$state;
		}
		$data ['orders'] = $orders;
		$data ['handled'] = $handled;
		$this->_hotel_ci->load->model ( 'pay/Pay_model' );

		$data ['online_pay'] = $this->_hotel_ci->Pay_model->is_online_pay ();

		return $data;

	}
	function hotel_photo() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Gallery_model' );
		$data ['hotel_id'] = $hotel_id = $this->_hotel_ci->Hotel_model->get_a_hotel_id ( $this->_hotel_ci->inter_id, $this->_hotel_ci->input->get ( 'h' ), false );
		$data ['gallery_count'] = $this->_hotel_ci->Gallery_model->get_gallery_count ( $this->_hotel_ci->inter_id, $data ['hotel_id'] );
		$data ['first_gallery'] = $data ['gallery_count'] [0];

        //获取皮肤配置
        $module_view=$this->_hotel_ci->get_display_view('hotel/hotel_photo');
        $skin_config=$this->_hotel_ci->get_skin_config($module_view['skin_name'], 'hotel/hotel_photo');
        $module_view=array(
            'module_view'=>$module_view
        );
        if (!empty($skin_config['all_photo'])){
            $data ['cur_gallery'] = $this->_hotel_ci->Gallery_model->get_gallery ( $this->_hotel_ci->inter_id, array (
                'hotel_id' => $data ['hotel_id']
            ), true );

            if(!empty($data ['cur_gallery'])){
                $photos = array();
                foreach($data ['cur_gallery'] as $key => $cg){
                    $photos[] = array(
                        'gallery_name'=>$cg['gallery_name'],
                        'gid'=>$cg['gid'],
                        'url'=>$cg['image_url'],
                        'info'=>$cg['info']
                    );
                }
                $data['gallery_info'] = json_encode($photos);
                $data['total_nums'] = count($data ['cur_gallery']);
            }

        }else{
            $data ['cur_gallery'] = $this->_hotel_ci->Gallery_model->get_gallery ( $this->_hotel_ci->inter_id, array (
                'hotel_id' => $data ['hotel_id'],
                'gallery_id' => $data ['first_gallery'] ['gid']
            ), true, 3, 0 );
        }
        return $data;
	}
	function get_new_gallery() {
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Gallery_model' );
		$hotel_id = $hotel_id = $this->_hotel_ci->Hotel_model->get_a_hotel_id ( $this->_hotel_ci->inter_id, $this->_hotel_ci->input->get ( 'h' ), false );
		$gid = $this->_hotel_ci->input->get ( 'gid' );
		$nums = $this->_hotel_ci->input->get ( 'nums' );
		$offset = $this->_hotel_ci->input->get ( 'offset' );
		$new_gallery = $this->_hotel_ci->Gallery_model->get_gallery ( $this->_hotel_ci->inter_id, array (
			'hotel_id' => $hotel_id,
			'gallery_id' => $gid
		), true, $nums, $offset );
		$this->_hotel_ci->load->helper ( 'ajaxdata' );
		$new_gallery = data_dehydrate ( $new_gallery, array (
			'gid',
			'gallery_name',
			'image_url',
			'info'
		) );
		return $new_gallery;
	}
	function my_marks() {
		$data = $this->common_data;
		$data ['mark_type'] = intval ( $this->_hotel_ci->input->get ( 'mt' ) );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$condit = $this->_hotel_ci->Hotel_model->return_mark_condi ( $data ['mark_type'] );
		$data ['marks'] = array ();
		if (! empty ( $condit )) {
			$data ['marks'] = $this->_hotel_ci->Hotel_model->get_front_marks ( array (
				                                                        'inter_id' => $this->_hotel_ci->inter_id,
				                                                        'openid' => $this->_hotel_ci->openid,
				                                                        'mark_type' => $condit ['type'],
				                                                        'status' => 1
			                                                        ), $condit ['sort'] );
		}
		return $data;
	}
	function get_near_hotel() {
		$latitude = $this->_hotel_ci->input->get ( 'lat', true );
		$longitude = $this->_hotel_ci->input->get ( 'lnt', true );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->helper ( 'calculate' );
		$hotels = $this->_hotel_ci->Hotel_model->get_all_hotels ( $this->_hotel_ci->inter_id, 1 );
		$count = count ( $hotels );
		for($i = 0; $i < $count; $i ++) {
			$hotels [$i] ['distance'] = get_distance ( $hotels [$i] ['longitude'], $hotels [$i] ['latitude'], $longitude, $latitude );
		}
		$hotels = $this->_hotel_ci->Hotel_model->sort_dyd_array ( $hotels, 'distance', 'gt', 5 );
		$this->_hotel_ci->load->helper ( 'ajaxdata' );
		return data_dehydrate ( $hotels, array (
			'name',
			'hotel_id'
		), 'hotel_id' );
	}
	function hotel_comment() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Comment_model' );

        $hotel_id = $this->_hotel_ci->Hotel_model->get_a_hotel_id ( $this->_hotel_ci->inter_id, $this->_hotel_ci->input->get ( 'h' ), false );
        $data ['t_t'] = $this->_hotel_ci->Comment_model->get_hotel_comment_counts ( $this->_hotel_ci->inter_id, $hotel_id, 1 ,$this->_hotel_ci->openid);

        $module_view=$this->_hotel_ci->get_display_view('hotel/hotel_comment');
        $skin_config=$this->_hotel_ci->get_skin_config($module_view['skin_name'], 'hotel/hotel_comment');

        if (isset($skin_config['comment_pages']) && $skin_config['comment_pages']==1){
            $offset = 0;
            $nums = 20;
            $data['nums'] = $nums;
            $data ['comments'] = $this->_hotel_ci->Comment_model->get_hotel_comments ( $this->_hotel_ci->inter_id, $hotel_id, 1,'',$nums,$offset);
        }else{
            $data ['comments'] = $this->_hotel_ci->Comment_model->get_hotel_comments ( $this->_hotel_ci->inter_id, $hotel_id, 1);
        }
        $comments=array();
        foreach ($data ['comments'] as $k=>$c){
            if((!empty($c['content']) && isset($c['type']) && $c['type']=='user') && ($c['status']==1 || $c['openid']==$this->_hotel_ci->open_id)){
                $comments[$k]=$c;
            }
        }
        $data ['comments'] = $comments;
		$data ['hotel_id'] = $hotel_id;

        $this->_hotel_ci->load->model ( 'hotel/Comment_model' );
        $data ['comment_config'] = $this->_hotel_ci->Comment_model->get_comment_show_type ( $this->_hotel_ci->inter_id);

		return $data;
	}
	function ajax_hotel_comments(){
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Comment_model' );
		$hotel_id = $this->_hotel_ci->Hotel_model->get_a_hotel_id ( $this->_hotel_ci->inter_id, $this->_hotel_ci->input->get ( 'h' ), false );
		$offset = $this->_hotel_ci->input->get ( 'off', TRUE );
		$offset = empty ( intval ( $offset ) ) ? 0 : intval ( $offset );
		$nums = $this->_hotel_ci->input->get ( 'num', TRUE );
		$nums = empty ( intval ( $nums ) ) ? 20 : intval ( $nums );
		$nums = $nums > 20 ? 20 : $nums;
		$data ['comments'] = $this->_hotel_ci->Comment_model->get_hotel_comments ( $this->_hotel_ci->inter_id, $hotel_id, 1 ,'',$nums,$offset);
		if (!empty($data ['comments'])){
            $new_comment = $this->_hotel_ci->Comment_model->get_comment_show_type($this->_hotel_ci->inter_id);
            if(!empty($new_comment)){        //新版评论
                return array (
                    's' => 1,
                    'data' => $data ['comments']
                );
            }else{
                $html=$this->_hotel_ci->display ( 'hotel/ajax_hotel_comments/ajax_comment_list', $data , '', array (), TRUE );
                return  array (
                    's' => 1,
                    'data' => $html
                );
            }
		}
		return array (
               's' => 0,
               'data' => ''
           );
	}
	function to_comment() {
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$order_condition=array (
			'openid' => $this->_hotel_ci->openid,
			'member_no' => $this->member_no,
			'idetail' => array (
				'i'
			)
		);
		$orderid = $this->_hotel_ci->input->get ( 'orderid' );
		if (!empty($orderid)){
		    $order_condition['orderid']=$orderid;
		    $oid=$order_condition['orderid'];
		}else{
		    $order_condition['oid'] = intval ( $this->_hotel_ci->input->get ( 'oid' ) );
		    $oid=$order_condition['oid'];
		}
		$list = $this->_hotel_ci->Order_model->get_main_order ( $this->_hotel_ci->inter_id, $order_condition );
		if ($list) {
			$this->_hotel_ci->load->model ( 'common/Enum_model' );
			$data ['status_des'] = $this->_hotel_ci->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
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
				$list ['status_des'] = $data ['status_des']  [$list ['status']];
				if (in_array ( $list ['status'], $complete_status )) {
					$comment = 1;
				}
			}

			$this->_hotel_ci->load->model ( 'hotel/Comment_model' );
			$data ['comment_info'] = $this->_hotel_ci->Comment_model->get_order_comment ( $this->_hotel_ci->inter_id, $list ['orderid'], $this->_hotel_ci->openid );
            $data ['comment_config'] = $this->_hotel_ci->Comment_model->get_comment_show_type ( $this->_hotel_ci->inter_id);
            if(!empty($data ['comment_config']) && !empty($data ['comment_config']->sign)){
                $data ['comment_config']->sign = explode(',',$data ['comment_config']->sign);
            }
            $list ['roomnight'] = get_room_night( $list['startdate'],$list['enddate']);
			$data ['order'] = $list;
			$data ['comment'] = $comment;
			$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
			$data ['first_room'] = $this->_hotel_ci->Hotel_model->get_room_detail ( $this->_hotel_ci->inter_id, $list ['hotel_id'], $data ['order'] ['first_detail'] ['room_id'], array (
				'img_type' => 'hotel_room_service'
			) );
		} else {
			return array('redirect'=>\Hotel_base::inst()->get_url('MYORDER'));
		}
		// Visit log
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
			                                 'openid' => $this->_hotel_ci->openid,
			                                 'inter_id' => $this->_hotel_ci->inter_id,
			                                 'title' => '订单评论',
			                                 'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			                                 'des' => "订单id：" . $oid
		                                 ) );
		return $data;
	}
	function return_usable_coupon() {
		$this->_hotel_ci->load->model ( 'hotel/Coupon_model' );
		$params = array ();
		$start = $this->_hotel_ci->input->post ( 'start' );
		$end = $this->_hotel_ci->input->post ( 'end' );
		$params ['days']  = get_room_night($start,$end,'round');//至少有1个间夜
		$params ['amount'] = $this->_hotel_ci->input->post ( 'total' );
		$params ['hotel'] = $this->_hotel_ci->input->post ( 'h' );
		$params ['paytype'] = $this->_hotel_ci->input->post ( 'paytype' );
        $pay_favour = $this->_hotel_ci->input->post ( 'pay_favour' );

		//增加获取券参数
		$params ['startdate'] = $start;
		$params ['enddate'] = $end;
		$params ['extra_para'] = $this->_hotel_ci->input->post ( 'extra_para' );
		$params ['extra_para'] = empty($params ['extra_para'])?array():json_decode($params ['extra_para'],TRUE);

		$params ['level'] = $this->member_lv;
		$data_arr = json_decode ( $this->_hotel_ci->input->post ( 'datas' ), TRUE );
		$price_codes = json_decode ( $this->_hotel_ci->input->post ( 'price_code' ), TRUE );
		if(!empty($data_arr)){
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
			//多于配置值，不能使用优惠券
			$this->_hotel_ci->load->model('hotel/Hotel_config_model');
			$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $params['hotel'], array (
				'MAXROOM_CAN_COUPON',
			) );
			if(!empty($config_data['MAXROOM_CAN_COUPON'])&&$config_data['MAXROOM_CAN_COUPON']<$params['rooms']){
				return array('hide_coupon'=>1);
			}
		}
		if(!empty($price_codes)){
			$params ['price_code'] = current ( $price_codes );
		}
		$cards = $this->_hotel_ci->Coupon_model->get_usable_coupon ( $this->_hotel_ci->inter_id, $this->_hotel_ci->openid, $params, TRUE );
		$cards['selected'] = [];
        $cards['auto_coupons'] = [];

        $this->_hotel_ci->load->model('hotel/Hotel_config_model');
        $config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', 0, array (
            'MORE_ROOM_NO_FAVOUR'
        ) );
        if(isset($config_data['MORE_ROOM_NO_FAVOUR']) && $config_data['MORE_ROOM_NO_FAVOUR']==1 && $params ['product_num'] > 1)return $cards;

        if($params ['paytype']=='weixin' && isset($pay_favour) && !empty($pay_favour)){
            $price_total = $params ['amount'] - $pay_favour;
        }else{
            $price_total = $params ['amount'];
        }

		if(!empty($cards['cards'])){ //有优惠券
			$cards_list = json_decode(json_encode($cards['cards']),true);
            uasort($cards_list, function ($a, $b){
                if($a['coupon_type']=='discount' && $b['coupon_type']=='discount'){
                    return $a['reduce_cost'] - $b['reduce_cost'] < 0 ? -1 : 1;
                }elseif($a['coupon_type']=='voucher' && $b['coupon_type']=='voucher'){
                    return $a['reduce_cost'] - $b['reduce_cost'] > 0 ? -1 : 1;
                }elseif($a['coupon_type'] != $b['coupon_type']){
                    return $a['coupon_type']=='discount'? -1 : 1;
                }
//				return $a['reduce_cost'] - $b['reduce_cost'] > 0 ? -1 : 1;
            });
			$select_amount = 0;
			$i = 0;
			foreach($cards_list as $v){
				//循环卡券
				if($price_total > $select_amount + $v['reduce_cost']){
					//当前已默认选择卡券
					$cards['selected'][] = $v;
					$select_amount += $v['reduce_cost'];
					$i++;
				}
                if($v['coupon_type']=='discount')break;
				if($i >= $cards['count']['num']){
					break;
				}
			}
		}
		return $cards;
	}

	//@Editor lGh 返回积分配置
	function return_point_set() {
		$params = array ();
		$start = $this->_hotel_ci->input->post ( 'start' );
		$end = $this->_hotel_ci->input->post ( 'end' );
		$params ['total_price'] = $this->_hotel_ci->input->post ( 'total_price' );
		$params ['hotel_id'] = $this->_hotel_ci->input->post ( 'h' );
		$params ['paytype'] = $this->_hotel_ci->input->post ( 'paytype' );
		$params ['openid'] = $this->_hotel_ci->openid;

		$params ['startdate'] = $start;
		$params ['enddate'] = $end;
		$params ['member_level'] = $this->member_lv;
		$params ['bonus'] = isset($this->common_data ['member']->bonus)?$this->common_data ['member']->bonus:0;
		$data_arr = json_decode ( $this->_hotel_ci->input->post ( 'datas' ), TRUE );
		$price_codes = json_decode ( $this->_hotel_ci->input->post ( 'price_code' ), TRUE );
		if(!empty($data_arr)){
			foreach ( $data_arr as $key => $value ) {
				if ($value == 0) {
					unset ( $data_arr [$key] );
				}
			}
			$params ['roomnums'] = array_sum ( $data_arr );
			reset ( $data_arr );
			$params ['room_id'] = key ( $data_arr );
			//多于配置值，不能使用积分兑换
			$this->_hotel_ci->load->model('hotel/Hotel_config_model');
			$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $params['hotel_id'], array (
				'MAXROOM_CAN_BONUS',
			) );
			if(!empty($config_data['MAXROOM_CAN_BONUS'])&&$config_data['MAXROOM_CAN_BONUS']<$params['roomnums']){
				return array('s'=>1,'hide_bonus'=>1);
			}
		}
		if(!empty($price_codes)){
			$params ['price_code'] = current ( $price_codes );
		}
		$params['point_name']=$this->_hotel_ci->input->post ( 'point_name');
		$params['check_point_name']=1;
		$this->_hotel_ci->load->model ( 'hotel/Member_model' );
		$point_consum_set = $this->_hotel_ci->Member_model->get_point_consum_rate ( $this->_hotel_ci->inter_id, $this->member_lv,'room',array(),$params );
		ob_clean();
		return $point_consum_set;
	}
	//@Editor lGh 返回积分配置
	function return_pointpay_set() {
		$params = array ();
		$start = $this->_hotel_ci->input->post ( 'start' );
		$end = $this->_hotel_ci->input->post ( 'end' );
		$params ['total_price'] = $this->_hotel_ci->input->post ( 'total_price' );
		$params ['hotel_id'] = $this->_hotel_ci->input->post ( 'h' );
		$params ['openid'] = $this->_hotel_ci->openid;

		$params ['startdate'] = $start;
		$params ['enddate'] = $end;
		$params ['member_level'] = $this->member_lv;
		$params ['bonus'] = isset($this->common_data ['member']->bonus)?$this->common_data ['member']->bonus:0;
		$data_arr = json_decode ( $this->_hotel_ci->input->post ( 'datas' ), TRUE );
		$price_codes = json_decode ( $this->_hotel_ci->input->post ( 'price_code' ), TRUE );
		if(!empty($data_arr)){
			foreach ( $data_arr as $key => $value ) {
				if ($value == 0) {
					unset ( $data_arr [$key] );
				}
			}
			$params ['roomnums'] = array_sum ( $data_arr );
			reset ( $data_arr );
			$params ['room_id'] = key ( $data_arr );
		}
		if(!empty($price_codes)){
			$params ['price_code'] = current ( $price_codes );
		}
		
		$params['point_name']=$this->_hotel_ci->input->post ( 'point_name');
		$params['check_point_name']=1;

		$params ['extra_para'] = $this->_hotel_ci->input->post ( 'extra_para' );
		
		$this->_hotel_ci->load->model('hotel/Member_model');
		$point_consum_set = $this->_hotel_ci->Member_model->point_pay_check($this->_hotel_ci->inter_id, $params);
		ob_clean();
		return $point_consum_set;
	}
	function cancel_main_order() {
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$orderid = $this->_hotel_ci->input->get ( 'oid' );
		$info = $this->_hotel_ci->Order_model->cancel_order ( $this->_hotel_ci->inter_id, array (
			'openid' => $this->_hotel_ci->openid,
			'member_no' => $this->member_no,
			'orderid' => $orderid,
			'idetail' => array (
				'i'
			)
		) );
//$this->db->insert('weixin_text',array('content'=>'cancel_main_order+'.json_encode($info),'edit_date'=>date('Y-m-d H:i:s')));
		return $info;
	}
	function comment_sub() {
		$data ['hotel_id'] = intval ( $this->_hotel_ci->input->post ( 'hotel_id' ) );
		$data ['orderid'] = $this->_hotel_ci->input->post ( 'orderid' );
		$data ['openid'] = $this->_hotel_ci->openid;
		$data ['inter_id'] = $this->_hotel_ci->inter_id;
		$data ['content'] = htmlspecialchars ( $this->_hotel_ci->input->post ( 'content' ) );
		$data ['score'] = intval ( $this->_hotel_ci->input->post ( 'score' ) );
		$data ['order_info'] ['hotel_name'] = $this->_hotel_ci->input->post ( 'hotel_name' );
		$data ['order_info'] ['room_name'] = $this->_hotel_ci->input->post ( 'room_name' );
		$this->_hotel_ci->load->model ( 'hotel/Comment_model' );
		return $this->_hotel_ci->Comment_model->add_comment ( $data );
	}
	function return_room_detail() {
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$hotel_id = intval ( $this->_hotel_ci->input->post ( 'h' ) );
		$room_id = intval ( $this->_hotel_ci->input->post ( 'r' ) );
		$detail = $this->_hotel_ci->Hotel_model->get_room_detail ( $this->_hotel_ci->inter_id, $hotel_id, $room_id, array (
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
			$hotel = $this->_hotel_ci->Hotel_model->get_hotel_detail ( $this->_hotel_ci->inter_id, $hotel_id );
			$detail ['book_policy'] = $hotel ['book_policy'];
		}
		$room ['book_policy'] = nl2br ( $detail ['book_policy'] );
        $room['description'] = $detail['description'];
        $room['bed_num'] = $detail['bed_num'];
        $room['area'] = $detail['area'];
		return $room;
	}

	private function preSpDate($hotel_id=0,$config_data=array()){
		$start_val = 0;
		if (!$config_data){
    		$this->_hotel_ci->load->model('hotel/Hotel_config_model');
    		$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', $hotel_id, array (
    			'BOOK_DATE_VALIDATE',
    		) );
		}
		if (! empty ( $config_data ['BOOK_DATE_VALIDATE'] )) {
			$condition=json_decode($config_data['BOOK_DATE_VALIDATE'],true);
			if(!empty($condition['startdate'])){
				foreach($condition['startdate'] as $v){
					$hour = $v['hour'];
					switch($v['compare']){
						case 'less': //当前时间少于值
							if(date('H') < $hour){
								$start_val = $v['val'];
							}
							break;
						case 'more':
							if(date('H') > $hour){
								$start_val = $v['val'];
							}
							break;
					}
					//循环，出现多次条件匹配，以最后为准
				}
			}
		}
		return (int)$start_val;
	}

	function new_comment_sub() {    //提交评论
        $this->_hotel_ci->load->model ( 'hotel/Comment_model' );
        $this->_hotel_ci->load->model ( 'hotel/Member_model' );
		$data ['images'] = $this->_hotel_ci->input->post ( 'images' );
		if(!empty($data ['images'])){
			$data ['images'] = implode(',',$data ['images']);
		}
		$data ['hotel_id'] = intval ( $this->_hotel_ci->input->post ( 'hotel_id' ) );
		$data ['orderid'] = $this->_hotel_ci->input->post ( 'orderid' );
		$data ['openid'] = $this->_hotel_ci->openid;
		$data ['inter_id'] = $this->_hotel_ci->inter_id;
		$data ['content'] = htmlspecialchars ( $this->_hotel_ci->input->post ( 'content' ) );
		$data ['service_score'] = intval ( $this->_hotel_ci->input->post ( 'service_score' ) );
		$data ['net_score'] = intval ( $this->_hotel_ci->input->post ( 'net_score' ) );
		$data ['facilities_score'] = intval ( $this->_hotel_ci->input->post ( 'facilities_score' ) );
		$data ['clean_score'] = intval ( $this->_hotel_ci->input->post ( 'clean_score' ) );
		$data ['score'] = number_format(($data ['service_score'] + $data ['net_score'] + $data ['facilities_score'] + $data ['clean_score']) / 4,2);
		$data ['order_info'] ['hotel_name'] = $this->_hotel_ci->input->post ( 'hotel_name' );
		$data ['order_info'] ['room_name'] = $this->_hotel_ci->input->post ( 'room_name' );
        $data['order_info']['sign']='';
		$mediaid = $this->_hotel_ci->input->post ( 'img_url' );
        $sign = $this->_hotel_ci->input->post ( 'sign' );
        if(!empty($sign)){
            $data['order_info']['sign'] = implode(',',$sign);
        }

        if($mediaid){
            $images_url = '';
            $this->_hotel_ci->load->model('wx/Access_token_model');
            $access_token= $this->_hotel_ci->Access_token_model->get_access_token( $this->_hotel_ci->inter_id );

            foreach($mediaid as $arr){
                $url = $this->ftp_images($access_token,$arr);
                $images_url.=','.$url;
            }
            $data['images'] = substr($images_url,1);
        }

        $res = $this->_hotel_ci->Comment_model->add_comment($data);
        $res['hotel_id'] = $data ['hotel_id'];
        if($res && isset($res['comment_id'])){       //评论成功执行送积分
            $comment_info = $this->_hotel_ci->Comment_model->get_comment_by_id($this->_hotel_ci->inter_id,$res['comment_id']);
            if(!empty($comment_info) && $comment_info['point_give']==0){
                $this->_hotel_ci->Comment_model->comment_give_bonus($this->_hotel_ci->inter_id,$comment_info);
            }
        }

        if($res && isset($res['status']) && $res['status']==1){
            $this->_hotel_ci->Comment_model->update_hotel_score_from_redis($this->_hotel_ci->inter_id,$data ['hotel_id'],$data);
            $keywords = $this->_hotel_ci->Comment_model->get_keyword($this->_hotel_ci->inter_id);
            if($keywords){
                foreach($keywords as $key=>$arr){
                    $count = $this->_hotel_ci->Comment_model->match_keyword($arr['keyword'],array($data));
                    if($count){
                        $keyword_count = json_decode($arr['count']);
                        if(!$keyword_count){
                            $count = json_encode($count);
                        }else{
                            if(isset($keyword_count->{$data['hotel_id']})){
                                $keyword_count->{$data['hotel_id']} = $keyword_count->{$data['hotel_id']} + 1;
                            }else{
                                $keyword_count->{$data['hotel_id']} = 1;
                            }
                            $count = json_encode($keyword_count);
                        }

                        $this->_hotel_ci->Comment_model->update_keyword_count($arr['keyword_id'],$count);
                    }
                }
            }
        }
        return $res;
	}


    function comment_no_order(){

        $data = $this->common_data;
        $openid = $this->_hotel_ci->openid;
//$openid = 'oz1AKv5xDYeeTBfwImfWZHP8QfyU';
        $hotel_id = $this->_hotel_ci->input->get('h');

        $this->_hotel_ci->load->model ( 'hotel/Hotel_config_model' );
        $config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', 0, array (
            'COMMENT_NO_ORDER'
        ) );

        if(empty($hotel_id) || empty($openid)){

            return array('redirect'=>\Hotel_base::inst()->get_url('SEARCH'));


        }else{

            if (! empty ( $config_data ['COMMENT_NO_ORDER']) && $config_data ['COMMENT_NO_ORDER']==1) {

                $this->_hotel_ci->load->model ( 'hotel/Comment_model' );
                $data['comment'] = 1;
                $check = $this->_hotel_ci->Comment_model->check_no_order_comment($this->_hotel_ci->inter_id,$openid,$hotel_id);

                if($check){
                    $data['comment'] = 0;
                }

                $this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
                $data['order']['hotel_id'] = $hotel_id;

            }else{

            	return array('redirect'=>\Hotel_base::inst()->get_url('SEARCH'));
            }

        }
        return $data;
    }


    public function ftp_images($access_token,$url){
        try {
            $data = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$url;
            $file_name = date('YmdHis').rand(1000,9999).'.jpg';
//                file_put_contents('./'.$file_name,base64_decode($data));
            $this->curl_file_get_contents($data,$file_name);

            $this->ftp= $this->_hotel_ci->_ftp_server('prod');
            $base_path= 'media/club/';

            $to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path;

            if(empty($to_file)){
                $this->ftp->mkdir($this->ftp->floder. FD_PUBLIC. '/'. $base_path,0777);
            }

            $up_path = realpath('./').'/'.$file_name;

            $this->ftp->upload($up_path, $to_file.$file_name, 'binary', 0775);
            $this->ftp->close();

            $upload_url= $this->ftp->weburl. '/'. FD_PUBLIC. '/media/club/'.$file_name;

//                保存上传完之后的URL
            return $upload_url;

        }catch (Exception $e){
            return 'error';
        }

    }


    function curl_file_get_contents($durl,$targetName){
        $ch = curl_init($durl); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $targetName;
    }

    //专题页面
    function thematic_index(){
		$data = $this->common_data;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_thematic_model' );
		$tc_id = intval($this->_hotel_ci->input->get ( 'tc_id', TRUE ));
		$data ['hot_city'] = array();
		$data ['first_city'] = '全部';
		$data['list'] = $this->_hotel_ci->Hotel_thematic_model->get_list(array('inter_id'=>$this->_hotel_ci->inter_id,'nowtime'=>date('Y-m-d H:i:s')));
		if(!empty($tc_id)){
			$data['row'] = $this->_hotel_ci->Hotel_thematic_model->get_row($this->_hotel_ci->inter_id,$tc_id);
		}elseif(!empty($data['list'])){
			$data['row'] = $data['list'][0];
		}
		$data['tips']= '没有搜索到相关结果~';
		$data['hidden']= 0;
		
		$pre_sp_date=0;
		$minSelect = 1;

		//没有活动处理
		if(empty($data['row'])){
			$data['tips']= '活动不存在~';
			$row = array();
			$data['row']['id'] = 0;
			$data['row']['act_intro'] = '';
			$data['row']['act_name'] = '';
			$data['hidden']= 1;
		}else{
			//活动过期处理
			if($data['row']['status'] !=1){
				$data['tips']= '活动已失效~';
				$data['hidden']= 1;
			}
			if(strtotime($data['row']['start_time'])>time()){
				$data['tips']= '活动未开始~';
				$data['hidden']= 1;
			}
			if(strtotime($data['row']['end_time'])<time()){
				$data['tips']= '活动已过期~';
				$data['hidden']= 1;
			}

			$tc_hotelids = json_decode($data['row']['hotelids'],TRUE);

			if(!empty($tc_hotelids)){
				$hotel_ids = implode(',',$tc_hotelids);
				//获取推荐城市
				$cities = $this->_hotel_ci->Hotel_model->get_hotel_citys_by_hid ( $this->_hotel_ci->inter_id ,array('hotel_id'=>$hotel_ids));
				$data ['hot_city'] = $cities;
				if(isset($cities[0])){
					$data ['first_city'] = $cities[0]['city'];
				}
			}
			//提前预定
			$pre_sp_date += $data['row']['pre_days'];
			$startime=time()+($pre_sp_date*86400);
			$data ['startdate'] = date ( 'Y/m/d',$startime);
			//连住优惠
			$minSelect = $data['row']['min_days']>0?$data['row']['min_days']:1;
			$data ['enddate'] = date ( 'Y/m/d', strtotime ( '+ '.$minSelect.' day', $startime ) );

			$data['pre_sp_date']=$pre_sp_date;
			$data['minSelect']=$minSelect;
		}
		return $data;
    }

    public function check_self_continue() {
        $orderid = $this->_hotel_ci->input->post ( 'orderid' );
        $item_id = intval ( $this->_hotel_ci->input->post ( 'item_id' ) );
        $this->_hotel_ci->load->model ( 'hotel/Order_check_model' );
        $result = $this->_hotel_ci->Order_check_model->check_self_continue ( $this->_hotel_ci->inter_id, $orderid, $this->_hotel_ci->openid, $item_id, array (
                'member_level' => $this->member_lv 
        ) );
        $info = array (
                's' => $result ['s'],
                'errmsg' => $result ['errmsg'] 
        );
        isset ( $result ['pay_link'] ) and $info ['pay_link'] = $result ['pay_link'];
        return $info;
    }


    // 酒店相册
    public function photo_list(){
        $data = $this->common_data;
        $data['inter_id'] = $this->_hotel_ci->inter_id;
        $hotel_id = $this->_hotel_ci->input->get('h');
        $this->_hotel_ci->load->model ( 'hotel/Gallery_model' );
        $data['gallery'] = $this->_hotel_ci->Gallery_model->get_gallery ( $this->_hotel_ci->inter_id, array('hotel_id'=>$hotel_id) );
        return $data;
    }


    // 房型列表
	public function room_list(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;

        return $data;
	}
    // 套餐列表
	public function package_list(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;

        return $data;
	}

    // 提交订单
	public function submit_order(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;

        return $data;
	}
    // 我的订单
    public function my_order(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;

        return $data;
    }

    // 订单详情
    public function order_details(){
        //$data['inter_id'] = $this->_hotel_ci->inter_id;
        $data['state'] = $_GET['state'];
        return $data;
    }

    // 订单详情
    public function packages_use(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;
        return $data;
    }
    // 房型+套餐详情
    public function package_details(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }
	
    // 选择套餐
    public function package_select(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }

    //城市列表
    public function city_list(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }

    // 评价页面
    public function comment_list(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }

    // 发表评价
    public function comment(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }

    // 酒店介绍
    public function hotel_details(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }


    // 地图
    public function map(){
        $data['inter_id'] = $this->_hotel_ci->inter_id;       
        return $data;
    }


}