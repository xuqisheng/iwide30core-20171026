<?php
use App\services\hotel\HotelService;

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Hotel extends MY_Front_Hotel_Iapi {
	public $default_skin='default2';
	function __construct() {
		parent::__construct ();
	}
	function search() {
		$data = HotelService::getInstance()->search();
		foreach ($data['pubimgs'] as $k => $pi){
			$data['pubimgs'][$k]['link'] = Hotel_base::inst()->get_url($pi['link'],'',TRUE);
		}
		foreach ($data['last_orders'] as $k => $lo){
			$data['last_orders'][$k]['link'] = Hotel_base::inst()->get_url("INDEX",array('h'=>$lo['hotel_id']));
		}
		foreach ($data['hotel_collection'] as $k => $hc){
			$data['hotel_collection'][$k]['link'] = Hotel_base::inst()->get_url($hc['mark_link'],array(),TRUE);
		}
		foreach ($data['homepage_set']['menu'] as $k => $v){
			if($v['code'] == 'athour') $data['homepage_set']['menu'][$k]['link'] = Hotel_base::inst()->get_url("SEARCH",array('type'=>'athour'));
			if($v['code'] == 'order') $data['homepage_set']['menu'][$k]['link'] = Hotel_base::inst()->get_url("MYORDER");
			if($v['code'] == 'ticket') $data['homepage_set']['menu'][$k]['link'] = Hotel_base::inst()->get_url("SEARCH",array('type'=>'ticket'));
		}
		
		$ext['links']['SRESULT'] = Hotel_base::inst()->get_url("SRESULT");
		$ext['links']['AJAX_HOTEL_SEARCH'] = Hotel_base::inst()->get_url("AJAX_HOTEL_SEARCH");
        $this->out_put_msg(1,'',$data,'hotel/hotel/search',$ext);
	}
	function sresult() {
		$data = HotelService::getInstance()->sresult();
		
		$ext['links']['AJAX_HOTEL_LIST'] = Hotel_base::inst()->get_url("AJAX_HOTEL_LIST");
		$ext['links']['AJAX_CITY_FILTER'] = Hotel_base::inst()->get_url("AJAX_CITY_FILTER");
        $this->out_put_msg(1,'',$data,'hotel/hotel/sresult',$ext);
	}
	function return_lowest_price() {
		$data = HotelService::getInstance()->return_lowest_price();
        $this->out_put_msg(1,'',$data,'hotel/hotel/return_lowest_price');
	}
	function index() {
		$data = HotelService::getInstance()->index();

		if(!empty($data['gallery_count'])){
			$ext['links']['HOTEL_PHOTO'] = Hotel_base::inst()->get_url("HOTEL_PHOTO",array('h'=>$data['hotel']['hotel_id']));
		}
		$ext['links']['BOOKROOM'] = Hotel_base::inst()->get_url("BOOKROOM");
		$ext['links']['HOTEL_DETAIL'] = Hotel_base::inst()->get_url("HOTEL_DETAIL",array('h'=>$data['hotel']['hotel_id']));
		$ext['links']['HOTEL_COMMENT'] = Hotel_base::inst()->get_url("HOTEL_COMMENT",array('h'=>$data['hotel']['hotel_id']));
		$ext['links']['RETURN_MORE_ROOM'] = Hotel_base::inst()->get_url("RETURN_MORE_ROOM");
		$ext['links']['RETURN_ROOM_DETAIL'] = Hotel_base::inst()->get_url("RETURN_ROOM_DETAIL");
		$ext['links']['CANCEL_ONE_MARK'] = Hotel_base::inst()->get_url("CANCEL_ONE_MARK");
		$ext['links']['ADD_HOTEL_COLLECTION'] = Hotel_base::inst()->get_url("ADD_HOTEL_COLLECTION");

        $this->out_put_msg(1,'',$data,'hotel/hotel/index',$ext);

	}
	function return_more_room() {
		$data = HotelService::getInstance()->return_more_room();
		$msg = $data['errmsg'];
		unset($data['errmsg']);
		unset($data['s']);
        $this->out_put_msg(1,$msg,$data,'hotel/hotel/return_more_room');
		
	}
	function hotel_detail() {
		$data = HotelService::getInstance()->hotel_detail();
        $this->out_put_msg(1,'',$data,'hotel/hotel/hotel_detail');
	}
	function arounds() {
		$data = HotelService::getInstance()->arounds();
        $this->out_put_msg(1,'',$data,'hotel/hotel/arounds');
	}
	function bookroom() {
		$data = HotelService::getInstance()->bookroom();
		if(!empty($data['redirect'])){
		    $ext['links']['redirect'] = $data['redirect'];
		    $this->out_put_msg(1,'','','hotel/hotel/bookroom',$ext);
		}
		$ext['links']['SAVEORDER'] = Hotel_base::inst()->get_url("SAVEORDER");
		$ext['links']['RETURN_USABLE_COUPON'] = Hotel_base::inst()->get_url("RETURN_USABLE_COUPON");
		$ext['links']['RETURN_POINT_SET'] = Hotel_base::inst()->get_url("RETURN_POINT_SET");
		$ext['links']['RETURN_POINTPAY_SET'] = Hotel_base::inst()->get_url("RETURN_POINTPAY_SET");

        $this->out_put_msg(1,'',$data,'hotel/hotel/bookroom',$ext);

	}

	function saveorder() {

		$data = HotelService::getInstance()->saveorder();
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,'',$data,'hotel/hotel/saveorder');
		}else{
        	$this->out_put_msg(2,$data['errmsg'],'','hotel/hotel/saveorder');
		}
	}
	function add_hotel_collection() {
		$data = HotelService::getInstance()->add_hotel_collection();
		if($data>0){
        	$this->out_put_msg(1,'已收藏',array('mid'=>$data),'hotel/hotel/add_hotel_collection');
		}else{
        	$this->out_put_msg(2,'收藏失败','','hotel/hotel/add_hotel_collection');
		}
	}
	function clear_visited_hotel() {
		$data = HotelService::getInstance()->clear_visited_hotel();
    	$this->out_put_msg(1,'ok','','hotel/hotel/clear_visited_hotel');
		
	}
	function cancel_one_mark() {
		$data = HotelService::getInstance()->cancel_one_mark();
    	$this->out_put_msg(1,'ok','','hotel/hotel/cancel_one_mark');
	}
	function orderdetail() {
		$data = HotelService::getInstance()->orderdetail();
		if(!empty($data['redirect'])){
		    $ext['links']['redirect'] = $data['redirect'];
		    $this->out_put_msg(1,'','','hotel/hotel/orderdetail',$ext);
		}

		$ext['links']['TO_COMMENT'] = Hotel_base::inst()->get_url("TO_COMMENT",array('orderid'=>$data['order']['orderid']));
		$ext['links']['CHECK_OUT'] = Hotel_base::inst()->get_url("CHECK_OUT",array('oid'=>$data['order']['id']));
		$ext['links']['INDEX'] = Hotel_base::inst()->get_url("INDEX",array('h'=>$data['order']['hotel_id'],'type'=>$data['order']['price_type']));
		$ext['links']['CANCEL_MAIN_ORDER'] = Hotel_base::inst()->get_url("CANCEL_MAIN_ORDER");
        $this->out_put_msg(1,'',$data,'hotel/hotel/orderdetail',$ext);
	}
	function myorder() {
		$data = HotelService::getInstance()->myorder();

		if(!empty($data['orders'])){
			foreach($data['orders'] as $k=>$o){
				$data['orders'][$k]['ORDERDETAIL'] = Hotel_base::inst()->get_url("ORDERDETAIL",array('oid'=>$o['id']));
				if ($o['re_pay']!=1){
					if ($o['status']==3&&$o['can_comment']==1){
						$data['orders'][$k]['TO_COMMENT'] = Hotel_base::inst()->get_url("TO_COMMENT",array('oid'=>$o['id']));
					}
					if ($o['orderstate']['self_checkout']==1){
						$data['orders'][$k]['CHECK_OUT'] = Hotel_base::inst()->get_url("CHECK_OUT",array('oid'=>$o['id']));
					}
					$data['orders'][$k]['INDEX'] = Hotel_base::inst()->get_url("INDEX",array('h'=>$o['hotel_id'],'type'=>$o['price_type']));
				}
			}
		}
    	$this->out_put_msg(1,'',$data,'hotel/hotel/myorder');

	}
	function hotel_photo() {
		$data = HotelService::getInstance()->hotel_photo();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/hotel_photo');
	}
	function get_new_gallery() {
		$data = HotelService::getInstance()->get_new_gallery();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/get_new_gallery');
	}
	function my_marks() {
		$data = HotelService::getInstance()->my_marks();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/my_marks');
	}
	function get_near_hotel() {
		$data = HotelService::getInstance()->get_near_hotel();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/get_near_hotel');
	}
	function hotel_comment() {
		$data = HotelService::getInstance()->hotel_comment();
		$ext['links']['INDEX'] = Hotel_base::inst()->get_url("INDEX",array('h'=>$data['hotel_id']));
		$ext['links']['AJAX_HOTEL_COMMENTS'] = Hotel_base::inst()->get_url("AJAX_HOTEL_COMMENTS");
		
    	$this->out_put_msg(1,'',$data,'hotel/hotel/hotel_comment',$ext);
	}
	function ajax_hotel_comments(){
		$data = HotelService::getInstance()->ajax_hotel_comments();
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,'',$data,'hotel/hotel/ajax_hotel_comments');
		}else{
        	$this->out_put_msg(2,'','','hotel/hotel/ajax_hotel_comments');
		}

	}
	function to_comment() {
		$data = HotelService::getInstance()->to_comment();
		if(!empty($data['redirect'])){
		    $ext['links']['redirect'] = $data['redirect'];
		    $this->out_put_msg(1,'','','hotel/hotel/to_comment',$ext);
		}
		if($data['comment']==0 || !empty($data['comment_info'])) {
		    $ext['links']['redirect'] = site_url ( 'hotel/hotel/hotel_comment') . '?id=' . $this->inter_id .'&h='.$data['order']['hotel_id'];
		    $this->out_put_msg(1,'','','hotel/hotel/to_comment',$ext);
		}
		$ext['links']['HOTEL_COMMENT'] = Hotel_base::inst()->get_url("HOTEL_COMMENT",array('h'=>$data['order']["hotel_id"]));
		$ext['links']['NEW_COMMENT_SUB'] = Hotel_base::inst()->get_url("NEW_COMMENT_SUB");
        $this->out_put_msg(1,'',$data,'hotel/hotel/to_comment',$ext);
	}
	function return_usable_coupon() {
		$data = HotelService::getInstance()->return_usable_coupon();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/return_usable_coupon');
	}

	//@Editor lGh 返回积分配置
	function return_point_set() {
		$data = HotelService::getInstance()->return_point_set();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/return_point_set');
	}
	//@Editor lGh 返回积分配置
	function return_pointpay_set() {
		$data = HotelService::getInstance()->return_pointpay_set();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/return_pointpay_set');
	}
	function cancel_main_order() {
		$data = HotelService::getInstance()->cancel_main_order();
		if($data['s']==1){
        	$this->out_put_msg(1,$data['errmsg'],'','hotel/hotel/cancel_main_order');
		}else{
        	$this->out_put_msg(2,$data['errmsg'],'','hotel/hotel/cancel_main_order');
		}
	}
	function comment_sub() {
		$data = HotelService::getInstance()->comment_sub();
		$msg = $data['errmsg'];
		unset($data['errmsg']);
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,$msg,$data,'hotel/hotel/comment_sub');
		}else{
			unset($data['s']);
        	$this->out_put_msg(2,$msg,$data,'hotel/hotel/comment_sub');
		}
	}
	function return_room_detail() {
		$data = HotelService::getInstance()->return_room_detail();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/return_room_detail');
	}


	function new_comment_sub() {    //提交评论
        $data = HotelService::getInstance()->new_comment_sub();
		$msg = $data['errmsg'];
		unset($data['errmsg']);
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,$msg,$data,'hotel/hotel/new_comment_sub');
		}else{
			unset($data['s']);
        	$this->out_put_msg(2,$msg,$data,'hotel/hotel/new_comment_sub');
		}
	}


    function comment_no_order(){

        $data = HotelService::getInstance()->comment_no_order();
		if(!empty($data['redirect'])){
		    $ext['links']['redirect'] = $data['redirect'];
		    $this->out_put_msg(1,'','','hotel/hotel/comment_no_order',$ext);
		}
        $this->out_put_msg(1,'',$data,'hotel/hotel/comment_no_order');

    }


    //专题页面
    function thematic_index(){
		$data = HotelService::getInstance()->thematic_index();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/thematic_index');
    }

    public function check_self_continue() {
        $orderid = $this->input->post ( 'orderid' );
        $item_id = intval ( $this->input->post ( 'item_id' ) );
        $this->load->model ( 'hotel/Order_check_model' );
        $result = $this->Order_check_model->check_self_continue ( $this->inter_id, $orderid, $this->openid, $item_id, array (
                'member_level' => $this->member_lv 
        ) );
        $info = array (
                's' => $result ['s'],
                'errmsg' => $result ['errmsg'] 
        );
        isset ( $result ['pay_link'] ) and $info ['pay_link'] = $result ['pay_link'];
        echo json_encode ( $info );
        $data = HotelService::getInstance()->check_self_continue();
		$msg = $data['errmsg'];
		unset($data['errmsg']);
		if($data['s']==1){
			unset($data['s']);
        	$this->out_put_msg(1,$msg,$data,'hotel/hotel/check_self_continue');
		}else{
			unset($data['s']);
        	$this->out_put_msg(2,$msg,$data,'hotel/hotel/check_self_continue');
		}
    }


    // 酒店相册
    public function photo_list(){
        $data = HotelService::getInstance()->photo_list();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/photo_list');
    }


    // 房型列表
	public function room_list(){
        $data = HotelService::getInstance()->room_list();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/room_list');
	}
    // 套餐列表
	public function package_list(){
        $data = HotelService::getInstance()->package_list();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/package_list');
	}

    // 提交订单
	public function submit_order(){
        $data = HotelService::getInstance()->submit_order();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/submit_order');
	}
    // 我的订单
    public function my_order(){
        $data = HotelService::getInstance()->my_order();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/my_order');
    }

    // 订单详情
    public function order_details(){
        $data = HotelService::getInstance()->order_details();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/order_details');
    }

    // 订单详情
    public function packages_use(){
        $data = HotelService::getInstance()->packages_use();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/packages_use');
    }
    // 房型+套餐详情
    public function package_details(){
        $data = HotelService::getInstance()->package_details();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/package_details');
    }
	
    // 选择套餐
    public function package_select(){
        $data = HotelService::getInstance()->package_select();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/package_select');
    }

    //城市列表
    public function city_list(){
        $data = HotelService::getInstance()->city_list();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/city_list');
    }

    // 评价页面
    public function comment_list(){
        $data = HotelService::getInstance()->comment_list();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/comment_list');
    }

    // 发表评价
    public function comment(){
        $data = HotelService::getInstance()->comment();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/comment');
    }

    // 酒店介绍
    public function hotel_details(){
        $data = HotelService::getInstance()->hotel_details();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/hotel_details');
    }


    // 地图
    public function map(){
    	$data = HotelService::getInstance()->map();
    	$this->out_put_msg(1,'',$data,'hotel/hotel/map');
    }


}