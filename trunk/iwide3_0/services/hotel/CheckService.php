<?php
namespace App\services\hotel;

use App\services\HotelBaseService;

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class CheckService extends HotelBaseService {
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
		$this->common_data ['inter_id'] = $this->_hotel_ci->inter_id;
		$this->common_data ['csrf_token'] = $this->_hotel_ci->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->_hotel_ci->security->get_csrf_hash ();
		
		
		$this->common_data ['index_url'] = $this->_hotel_ci->public ['is_multy'] == 1 ?\Hotel_base::inst()->get_url('INDEX') : \Hotel_base::inst()->get_url('SEARCH');;
		
	}
	
	function nearby() {
		$data = $this->common_data;
		
		$startdate = $this->_hotel_ci->input->get ( 'sd', TRUE );
		$enddate = $this->_hotel_ci->input->get ( 'ed', TRUE );
		
		$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate, $this->_hotel_ci->inter_id);
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		
		// $this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
		// $result = $this->_hotel_ci->Hotel_model->search_hotel_front ( $this->_hotel_ci->inter_id, array () );
		$data ['hotel_ids'] = '';
		// if (! empty ( $result )) {
		// $this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
		// $result = $this->_hotel_ci->Hotel_check_model->get_extra_info ( $this->_hotel_ci->inter_id, $result, array (
		// 'hotel_service',
		// 'lowest_price',
		// 'search_icons',
		// 'comment_data',
		// 'distance'
		// ), array (
		// 'startdate' => $data ['startdate'],
		// 'enddate' => $data ['enddate'],
		// 'latitude' => 23.129163,
		// 'longitude' => 113.264435
		// ) );
		// $data ['result'] = $result;
		// }
		
		$this->_hotel_ci->load->model ( 'common/Record_model' );
		$this->_hotel_ci->Record_model->visit_log ( array (
				'openid' => $this->_hotel_ci->openid,
				'inter_id' => $this->_hotel_ci->inter_id,
				'title' => '附近酒店',
				'url' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
				'des' => "附近酒店" 
		) );
		return $data;
	}
	function my_collection() {
		$data = $this->common_data;
		$data ['mark_type'] = intval ( $this->_hotel_ci->input->get ( 'mt' ) );
		$data ['mark_type'] = 1;
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$condit = $this->_hotel_ci->Hotel_model->return_mark_condi ( $data ['mark_type'] );
		$data ['marks'] = array ();
		$data ['hotels'] = array ();
		if (! empty ( $condit )) {
			$data ['marks'] = $this->_hotel_ci->Hotel_model->get_front_marks ( array (
					'inter_id' => $this->_hotel_ci->inter_id,
					'openid' => $this->_hotel_ci->openid,
					'mark_type' => $condit ['type'],
					'status' => 1 
			), $condit ['sort'] );
			if (! empty ( $data ['marks'] )) {
				$hotel_ids = '';
				foreach ( $data ['marks'] as $mark ) {
					$hotel_ids .= ',' . $mark ['mark_name'];
				}
				$hotel_ids = substr ( $hotel_ids, 1 );
				$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
				$hotels = $this->_hotel_ci->Hotel_model->get_hotel_by_ids ( $this->_hotel_ci->inter_id, $hotel_ids, 1, 'key', 'object' );
				$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
				$info_types = array (
						'hotel_service',
						'lowest_price',
						'search_icons',
//						'comment_data'
				);
				
				$this->_hotel_ci->load->model('hotel/Hotel_config_model');
				$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', 0, [
					'NONE_COMMENT'//取消评论
				]);
				if(empty($config_data['NONE_COMMENT'])){
					$info_types[]='comment_data';
				}
				
				
				$params = array (
						'startdate' => date ( 'Ymd' ),
						'enddate' => date ( 'Ymd', strtotime ( '+ 1 day', time () ) ),
						'member_level'=>$this->member_lv
				);
				$hotels = $this->_hotel_ci->Hotel_check_model->get_extra_info ( $this->_hotel_ci->inter_id, $hotels, $info_types, $params );
				$data ['hotels'] = $hotels;
			}
		}
		return $data;
	}
	
	function check_repay(){
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$orderid = $this->_hotel_ci->input->get ( 'oid' );
		$check = $this->_hotel_ci->Order_model->get_main_order ( $this->_hotel_ci->inter_id, array (
				'openid' => $this->_hotel_ci->openid,
				'member_no' => $this->member_no,
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $check )) {
			$check = $check [0];
			$this->_hotel_ci->load->model ( 'hotel/Order_check_model' );
			$state = $this->_hotel_ci->Order_check_model->check_order_state ( $check );
			if ($state['re_pay']==1){
				return array('s'=>1);
			}
		}
		return array('s'=>0,'errmsg'=>'已无法再支付');
	}
	
	function ajax_hotel_list() {
		$data = $this->common_data;
		$latitude = $this->_hotel_ci->input->get ( 'lat', true );
		$longitude = $this->_hotel_ci->input->get ( 'lnt', true );
		$startdate = $this->_hotel_ci->input->get ( 'start', TRUE );
		$enddate = $this->_hotel_ci->input->get ( 'end', TRUE );
		$city = $this->_hotel_ci->input->get ( 'city', TRUE );
        $area = $this->_hotel_ci->input->get ( 'area', TRUE );
		$keyword = $this->_hotel_ci->input->get ( 'keyword', TRUE );
		$offset = $this->_hotel_ci->input->get ( 'off', TRUE );
		$sort_type = $this->_hotel_ci->input->get ( 'sort_type', TRUE );
		$offset = intval ( $offset );
		$offset = empty ( $offset ) ? 0 : intval ( $offset );
		$nums = $this->_hotel_ci->input->get ( 'num', TRUE );
		$nums = intval ( $nums );
		$nums = empty ( $nums ) ? 5 : intval ( $nums );
		$nums = $nums > 20 ? 20 : $nums;
		$extra_condition = $this->_hotel_ci->input->get ( 'ec', TRUE );
		$type = $this->_hotel_ci->input->get ( 'type', TRUE );
		// $extra_condition = '{"land_mark":110229}';
		$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_model' );
		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
		$date_check = $this->_hotel_ci->Order_model->date_validate ( $startdate, $enddate, $this->_hotel_ci->inter_id);
		$startdate = $date_check [0];
		$enddate = $date_check [1];
		$check_distance = 0;
		$params = array (
				'startdate' => $startdate,
				'enddate' => $enddate,
				'city' => $city,
                'area' => $area,
				'extra_condition' => $extra_condition,
				'keyword'=>$keyword,
				'type'=>$type
		);
		
		if(isset($extra_condition) && !empty($extra_condition)){
			$landmark_info = json_decode($extra_condition);
			if(isset($landmark_info->bdmap)){
				$landmark_info = explode(',',$landmark_info->bdmap);
				if(isset($landmark_info[2])){
					$data['landmark']=  $landmark_info[2];
				}
				//转换百度坐标
				if (isset($landmark_info[0])){
					$this->_hotel_ci->load->helper ( 'calculate' );
					$location=bd2gcj($landmark_info[1], $landmark_info[0]);
					$latitude=$location['latitude'];
					$longitude=$location['longitude'];
				}
		
			}
		}
		
		if (! is_null ( $latitude ) && ! is_null ( $longitude ) && $latitude !== '' && $longitude !== '') {
			$check_distance = 1;
			$params ['latitude'] = $latitude;
			$params ['longitude'] = $longitude;
		}
		$params ['offset'] = $offset;
		$params ['nums'] = $nums;
		$params ['sort_type'] = $sort_type;
		$params ['check_distance'] = $check_distance;
		$result=array();
		if($type != 'athour' || date('Hi')<2300){
			$result = $this->_hotel_ci->Hotel_model->search_hotel_front ( $this->_hotel_ci->inter_id, $params );
		}
		//专题活动过滤hotelid add by ping
		$tc_id = intval($this->_hotel_ci->input->get ( 'tc_id', TRUE ));
		$data['exe_param'] = '';
		if(!empty($tc_id)){
			$data['exe_param'] = '&tc_id='.$tc_id;
			$this->_hotel_ci->load->model ( 'hotel/Hotel_thematic_model' );
			$tc_row = $this->_hotel_ci->Hotel_thematic_model->get_row($this->_hotel_ci->inter_id,$tc_id,array('nowtime'=>date('Y-m-d H:i:s'),'status'=>1));
			$tc_hotelids = json_decode($tc_row['hotelids'],TRUE);

			foreach ($result as $tc_k => $re) {
				if(empty($tc_hotelids) || !in_array($re->hotel_id,$tc_hotelids) ){
					unset($result[$tc_k]);
				}
			}
		}

		if (! empty ( $result )) {
			$info_types = array (
					'hotel_service',
					'lowest_price',
					'search_icons',
//					'comment_data'
			);
			
			$this->_hotel_ci->load->model('hotel/Hotel_config_model');
			$config_data = $this->_hotel_ci->Hotel_config_model->get_hotel_config ( $this->_hotel_ci->inter_id, 'HOTEL', 0, [
				'NONE_COMMENT'//取消评论
			]);
			if(empty($config_data['NONE_COMMENT'])){
				$info_types[]='comment_data';
			}
			
			$params = array (
					'startdate' => $startdate,
					'enddate' => $enddate,
					'member_level'=>$this->member_lv
			);
			$params ['offset'] = $offset;
			$params ['nums'] = $nums;
			$params ['check_distance'] = $check_distance;
			$params ['sort_type'] = $sort_type;
			if ($check_distance == 1) {
				$info_types [] = 'distance';
				$params ['latitude'] = $latitude;
				$params ['longitude'] = $longitude;
			}
			if ($sort_type == 'distance' || $sort_type == 'distance_up') {
				$params ['distance_sort'] = 'lt';
			}
			elseif ($sort_type == 'distance_down') {
				$params ['distance_sort'] = 'gt';
			}
			if(!empty($tc_row)){
				$params ['price_codes'] = json_decode($tc_row['price_codes'],TRUE);//起价价格代码
			}
			$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
			$result = $this->_hotel_ci->Hotel_check_model->get_extra_info ( $this->_hotel_ci->inter_id, $result, $info_types, $params ,$this->_hotel_ci->openid);
			
			$data ['result'] = $result;
			
			return array (
					's' => 1,
					'data' => $data 
			);
		}
		// $this->_hotel_ci->load->helper ( 'ajaxdata' );
		// var_dump ( $result );
		return array (
				's' => 0,
				'data' => '' 
		) ;
		// echo json_encode ( data_dehydrate ( $hotels, array (
		// 'name',
		// 'hotel_id'
		// ), 'hotel_id' ) );
	}
	function ajax_city_filter() {
		$data = $this->common_data;
		$city = $this->_hotel_ci->input->get ( 'city', TRUE );
		$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
		$result = $this->_hotel_ci->Hotel_check_model->get_city_filter ( $this->_hotel_ci->inter_id, $city );
		if (! empty ( $result )) {
			$data ['result'] = $result;
			$html = $this->_hotel_ci->display ( 'hotel/ajax_city_filter/ajax_city_filter', $data, '', array (), TRUE );
			return array (
					's' => 1,
					'data' => $html 
			);
		}
		return array (
				's' => 0,
				'data' => '暂无数据' 
		);
	}
	function ajax_hotel_search() {
		$data = $this->common_data;
		$keyword = $this->_hotel_ci->input->get ( 'keyword', TRUE );
		$city = $this->_hotel_ci->input->get ( 'city', TRUE );
		$city =='全部' and $city='';
		$this->_hotel_ci->load->model ( 'hotel/Hotel_check_model' );
		$paras = array (
				'city' => $city,
				'keyword' => $keyword,
				'nums'=>NULL,
				'offset'=>0
		);
		$result = $this->_hotel_ci->Hotel_check_model->search_hotel_front ( $this->_hotel_ci->inter_id, $paras );

		//专题活动过滤hotelid add by ping
		$tc_id = intval($this->_hotel_ci->input->get ( 'tc_id', TRUE ));
		$data['exe_param']='';
		if(!empty($tc_id)){
			$data['exe_param'] = '&tc_id='.$tc_id;
			$this->_hotel_ci->load->model ( 'hotel/Hotel_thematic_model' );
			$tc_row = $this->_hotel_ci->Hotel_thematic_model->get_row($this->_hotel_ci->inter_id,$tc_id,array('nowtime'=>date('Y-m-d H:i:s'),'status'=>1));
			$tc_hotelids = json_decode($tc_row['hotelids'],TRUE);
			foreach ($result as $tc_k => $re) {
				if(empty($tc_hotelids) || !in_array($re->hotel_id,$tc_hotelids) ){
					unset($result[$tc_k]);
				}
			}
		}
		
		if (! empty ( $result )) {
			$data ['result'] = $result;
			
			return array (
					's' => 1,
					'data' => $data,
					'count'=>count($result)
			);
		}
		return array (
				's' => 0,
				'data' => '暂无数据' 
		);
	}
    function check_order_canpay(){
    	$orderid = $this->_hotel_ci->input->get ( 'oid', true );
    	if ($orderid) {
    		$this->_hotel_ci->load->model ( 'hotel/Order_model' );
    		$order_details = $this->_hotel_ci->Order_model->get_main_order ( $this->_hotel_ci->inter_id, array (
    			'orderid' => $orderid,
    			'idetail' => array (
    				'i'
    			)
    		) );
	    	if ($order_details) {
				$order_details = $order_details [0];
		    	$this->_hotel_ci->load->model ( 'hotel/Order_check_model' );
				$re = $this->_hotel_ci->Order_check_model->check_order_state($order_details);
				if($re['re_pay']!=1){
					return array('s' => 0,'errmsg' => '不可支付' );
				}else{
					return array('s' => 1,'errmsg' => '可支付' );
				}
			}
		}
		return array('s' => 0,'errmsg' => '不可支付' );
    }
}