<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Check extends MY_Front_Wxapp {
	public $common_data;
	public $openid;
	public $module;
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
	
	// function display($paras, $data,$skin='',$extra_views = array(), $return = false) {
	// if($this->session->userdata($this->inter_id.'skin')){
	// $skin=$this->session->userdata($this->inter_id.'skin');
	// }
	// parent::display($paras, $data,$skin,$extra_views, $return);
	// }
	function nearby() {
		$data = $this->common_data;
		
		$startdate = $this->get_source ( 'sd' );
		$enddate = $this->get_source ( 'ed' );
		
		$this->load->model ( 'hotel/Hotel_check_model' );
		$this->load->model ( 'hotel/Order_model' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id);
		$data ['startdate'] = $date_check [0];
		$data ['enddate'] = $date_check [1];
		
		// $this->load->model ( 'hotel/Hotel_check_model' );
		// $result = $this->Hotel_model->search_hotel_front ( $this->inter_id, array () );
		$data ['hotel_ids'] = '';
		// if (! empty ( $result )) {
		// $this->load->model ( 'hotel/Hotel_check_model' );
		// $result = $this->Hotel_check_model->get_extra_info ( $this->inter_id, $result, array (
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
		$this->out_put_msg ( 1, '', $data,'hotel/nearby' );
	}
	function my_collection() {
		$data = $this->common_data;
		$data ['pagetitle'] = '我的收藏';
		$data ['mark_type'] = intval ( $this->input->get ( 'mt' ) );
		$data ['mark_type'] = 1;
		$this->load->model ( 'hotel/Hotel_model' );
		$condit = $this->Hotel_model->return_mark_condi ( $data ['mark_type'] );
		$data ['marks'] = array ();
		$data ['hotels'] = array ();
		if (! empty ( $condit )) {
			$data ['marks'] = $this->Hotel_model->get_front_marks ( array (
					'inter_id' => $this->inter_id,
					'openid' => $this->openid,
					'mark_type' => $condit ['type'],
					'status' => 1 
			), $condit ['sort'] );
			if (! empty ( $data ['marks'] )) {
				$hotel_ids = '';
				foreach ( $data ['marks'] as $mark ) {
					$hotel_ids .= ',' . $mark ['mark_name'];
				}
				$hotel_ids = substr ( $hotel_ids, 1 );
				$this->load->model ( 'hotel/Hotel_model' );
				$hotels = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $hotel_ids, 1, 'key', 'object' );
				$this->load->model ( 'hotel/Hotel_check_model' );
				$info_types = array (
						'hotel_service',
						'lowest_price',
						'search_icons',
						'comment_data' 
				);
				$params = array (
						'startdate' => date ( 'Ymd' ),
						'enddate' => date ( 'Ymd', strtotime ( '+ 1 day', time () ) ),
						'member_level'=>$this->member_lv
				);
				$hotels = $this->Hotel_check_model->get_extra_info ( $this->inter_id, $hotels, $info_types, $params );
				$data ['hotels'] = $hotels;
			}
		}
		$this->display ( 'hotel/my_collection/my_collection', $data );
	}
	
	function check_repay(){
		$this->load->model ( 'hotel/Order_model' );
		$orderid = $this->input->get ( 'oid' );
		$check = $this->Order_model->get_main_order ( $this->inter_id, array (
				'openid' => $this->openid,
				'member_no' => $this->member_no,
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $check )) {
			$check = $check [0];
			$this->load->model ( 'hotel/Order_check_model' );
			$state = $this->Order_check_model->check_order_state ( $check );
			if ($state['re_pay']==1){
				echo json_encode(array('s'=>1));
				exit;
			}
		}
		echo  json_encode(array('s'=>0,'errmsg'=>'已无法再支付'));
	}
	
	function ajax_hotel_list() {
		$data = $this->common_data;
		$latitude = $this->get_source( 'lat' );
		$longitude = $this->get_source( 'lnt' );
		$startdate = $this->get_source( 'start' );
		$enddate = $this->get_source ( 'end' );
		$city = $this->get_source ( 'city');
		$keyword = $this->get_source ( 'keyword');
		$offset = $this->get_source ( 'off');
		$sort_type = $this->get_source ( 'sort_type');
		$offset = intval ( $offset );
		$offset = empty ( $offset ) ? 0 : intval ( $offset );
		$nums = $this->get_source ( 'num');
		$nums = intval ( $nums );
		$nums = empty ( $nums ) ? 5 : intval ( $nums );
		$nums = $nums > 20 ? 20 : $nums;
		$extra_condition = $this->get_source ( 'ec');
		$type = $this->get_source( 'type');
		// $extra_condition = '{"land_mark":110229}';
		$this->load->model ( 'hotel/Hotel_check_model' );
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$date_check = $this->Order_model->date_validate ( $startdate, $enddate, $this->inter_id);
		$startdate = $date_check [0];
		$enddate = $date_check [1];
		$check_distance = 0;
		$params = array (
				'startdate' => $startdate,
				'enddate' => $enddate,
				'city' => $city,
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
					$this->load->helper ( 'calculate' );
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
			$result = $this->Hotel_model->search_hotel_front ( $this->inter_id, $params );
		}
		if (! empty ( $result )) {
			$info_types = array (
					'hotel_service',
					'lowest_price',
					'search_icons',
					'comment_data' 
			);
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
			
			$this->load->model ( 'hotel/Hotel_check_model' );
			$result = $this->Hotel_check_model->get_extra_info ( $this->inter_id, $result, $info_types, $params );
			
			//ajax返回后会将key由小到大排列，影响现时的排序，所以去掉hotel_id的下标排序
			$new_result = array();
			foreach($result as $d){
				
				$new_result[] = $d;
				
			}
			
			$data ['result'] = $new_result;
			
			$this->out_put_msg ( 1, '', $data );
			
			/* $html = $this->display ( 'hotel/ajax_hotel_list/ajax_hotel_list', $data, '', array (), TRUE );
			echo json_encode ( array (
					's' => 1,
					'data' => $html 
			), JSON_UNESCAPED_UNICODE );
			exit (); */
		}
		// $this->load->helper ( 'ajaxdata' );
		// var_dump ( $result );
		$this->out_put_msg ( 1, '', array() );
		// echo json_encode ( data_dehydrate ( $hotels, array (
		// 'name',
		// 'hotel_id'
		// ), 'hotel_id' ) );
	}
	function ajax_city_filter() {
		$data = $this->common_data;
		$city = $this->get_source ( 'city');
		$this->load->model ( 'hotel/Hotel_check_model' );
		$result = $this->Hotel_check_model->get_city_filter ( $this->inter_id, $city );
		if (! empty ( $result )) {
			$data ['result'] = $result;
			$html = $this->display ( 'hotel/ajax_city_filter/ajax_city_filter', $data, '', array (), TRUE );
			echo json_encode ( array (
					's' => 1,
					'data' => $html 
			), JSON_UNESCAPED_UNICODE );
			exit ();
		}
		echo json_encode ( array (
				's' => 0,
				'data' => '暂无数据' 
		) );
	}
	function ajax_hotel_search() {
		$data = $this->common_data;
		$keyword = $this->get_source ( 'keyword' );
		$city = $this->get_source ( 'city' );
		
		$data['map_suggestion']=$this->map_suggest($city, $keyword);
		
		$this->load->model ( 'hotel/Hotel_check_model' );
		$paras = array (
				'city' => $city,
				'keyword' => $keyword,
				'nums'=>5,
				'offset'=>0
		);
		$result = $this->Hotel_check_model->search_hotel_front ( $this->inter_id, $paras );
		if (! empty ( $result )) {
			$data ['result'] = $result;
			$new_data = array (
					's' => 1,
					'data' => $data,
					'count'=>count($result));
			$this->out_put_msg ( 1, '', $new_data,'check/ajax_hotel_search' );
			exit ();
		}
		$new_data = array (
				's' => 0,
				'data' => $data,
				'count'=>0);
		$this->out_put_msg ( 1, '', $new_data,'check/ajax_hotel_search' );
	}
	
	/*
	 * 1个ak支持10万次/天,后面应做缓存
	 参数名称	是否必须	默认值	格式		备注
	q(query)	是	无	上地、天安、中关、shanghai		输入建议关键字（支持拼音）
	region	是	无	全国、北京市、131、江苏省等	所属城市/区域名称或代号（指定城市返回结果加权，可能返回其他城市高权重结果）
	city_limit	否	false	"false"or"true"	取值为"true"，仅返回region中指定城市检索结果
	location	否	无	40.047857537164,116.31353434477	传入location参数后，返回结果将以距离进行排序
	output	否	xml	json、xml	返回数据格式，可选json、xml两种
	ak	是	无	E4805d16520de693a3fe707cdc962045	开发者访问密钥，必选。
	sn	否	无		用户的权限签名
	timestamp	否	无		设置sn后该值必选
	 * */
	function map_suggest($city,$keyword){
		$url="http://api.map.baidu.com/place/v2/suggestion?ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS&region=".urlencode($city)."&q=".urlencode($keyword);
		$this->load->helper('common');
		$result=json_decode(json_encode(simplexml_load_string(doCurlGetRequest($url))),TRUE);
		$data=array();
		if (isset($result['status'])&&!empty($result['result'])){
			foreach ($result['result']['name'] as $k=>$r){
				$data[$k]['name']=$r;
				$data[$k]['info']['lat']=$result['result']['location'][$k]['lat'];
				$data[$k]['info']['lng']=$result['result']['location'][$k]['lng'];
			}
		}
		return $data;
	}
	
	
	function display($paras, $data, $skin = '', $extra_views = array(), $return = false) {
		if ($this->session->userdata ( $this->inter_id . 'skin' )) {
			$skin = $this->session->userdata ( $this->inter_id . 'skin' );
		}
		 
		$view=$this->get_display_view($paras);
		if (empty($view)){
			$view=array(
					'skin_name'=>'default2',
					'overall_style'=>'',
					'extra_style'=>NULL,
					'view_subfix'=>NULL,
					'extra_preview'=>NULL,
					'extra_subview'=>NULL
			);
		}
		$extra_views['module_view']=$view;
		if ($return==TRUE)
			return parent::display ( $paras, $data, $skin, $extra_views, $return );
		parent::display ( $paras, $data, $skin, $extra_views, $return );
	}
}