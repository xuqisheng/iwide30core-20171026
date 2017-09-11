<?php
use App\services\hotel\HotelService;

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Hotel extends MY_Front_Hotel {
	public $default_skin='default2';
	public $common_show;
	function __construct() {
		parent::__construct ();

		$this->load->model ( 'wx/Access_token_model' );
		$this->common_show ['signPackage'] = $this->Access_token_model->getSignPackage ( $this->inter_id );
		$this->common_show ['pagetitle'] = $this->public ['name'];
		$this->share ['title'] = $this->public ['name'] . '-微信订房';
		$slink = $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		if (strpos ( $slink, '?' ))
			$slink = $slink . "&id=" . $this->inter_id;
		else
			$slink = $slink . "?id=" . $this->inter_id;
		$this->share ['link'] = $slink;
		$this->share ['imgUrl'] = 'http://7n.cdn.iwide.cn/public/uploads/201609/qf051934149038.jpg';
		$this->share ['desc'] = $this->public ['name'] . '欢迎您使用微信订房,享受快捷服务...';
		$this->share ['type'] = '';
		$this->share ['dataUrl'] = '';
		$this->common_show ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_show ['csrf_value'] = $this->security->get_csrf_hash ();
		$this->common_show ['share'] = $this->share;
		$this->common_show ['url_param'] = $this->url_param;
		$this->common_show ['inter_id'] = $this->inter_id;
		$this->common_show ['csrf_token_arr'] = array(
		        $this->common_show ['csrf_token']=>$this->common_show ['csrf_value']
		);

	}
	function search() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/search');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->search(),$data);
        }
		$type = $this->input->get ( 'type', TRUE );
		if($type == 'athour'){
			$this->display ( 'hotel/search_athour', $data,'',$module_view );
		}elseif($type == 'ticket'){
			$this->display ( 'hotel/search_ticket', $data,'',$module_view );
		}else{
			$this->display ( 'hotel/search', $data,'',$module_view );
		}
	}
	function sresult() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/sresult');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->sresult(),$data);
        }
		$type = $this->input->get ( 'type', TRUE );
		if($type == 'athour'){
			$this->display ( 'hotel/sresult/search_results_athour', $data,'',$module_view  );
		}elseif($type == 'ticket'){
			$this->display ( 'hotel/sresult/search_results_ticket', $data,'',$module_view  );
		}else{
			$this->display ( 'hotel/sresult/search_results', $data,'',$module_view  );
		}
	}
	function return_lowest_price() {
		$data = HotelService::getInstance()->return_lowest_price();
		echo json_encode ( $data );
	}
	function index() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/index');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->index(),$data);
        }
		$type = $this->input->get ( 'type', TRUE );

		if($type == 'athour'){
			$this->display ( 'hotel/index/room_list_athour', $data,'',$module_view  );
		}elseif($type == 'ticket'){
			$this->display ( 'hotel/index/room_list_ticket', $data,'',$module_view );
		}else{
            $this->display ( 'hotel/index/room_list', $data,'',$module_view  );
		}

	}
	function return_more_room() {
		$data = HotelService::getInstance()->return_more_room();
		echo json_encode ( $data );
	}
	function hotel_detail() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/hotel_detail');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->hotel_detail(),$data);
        }
		$this->display ( 'hotel/hotel_detail/hotel_detail', $data,'',$module_view );
	}
	function arounds() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/arounds');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->arounds(),$data);
        }
		$this->display ( 'hotel/arounds/arounds', $data,'',$module_view );
	}
	function bookroom() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/bookroom');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->bookroom(),$data);
            if(isset($data['redirect'])){
            	redirect($data['redirect']);
            }
        }
		$type = $this->input->get ( 'type', TRUE );
		if($type == 'athour'){
			$this->display ( 'hotel/bookroom/submit_order_athour', $data,'',$module_view );
		}elseif($type == 'ticket'){
			$this->display ( 'hotel/bookroom/submit_order_ticket', $data,'',$module_view );
		}else{
			$this->display ( 'hotel/bookroom/submit_order', $data,'',$module_view );
		}

	}

	function saveorder() {
		$data = HotelService::getInstance()->saveorder();
		echo json_encode ( $data );
	}
	function add_hotel_collection() {
		echo HotelService::getInstance()->add_hotel_collection();
	}
	function clear_visited_hotel() {
		echo HotelService::getInstance()->clear_visited_hotel();
	}
	function cancel_one_mark() {
		echo HotelService::getInstance()->cancel_one_mark();
	}
	function orderdetail() {
		$data = $this->common_show;
		$data['pagetitle'] = '订单详情';
        $module_view=$this->get_display_view('hotel/orderdetail');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->orderdetail(),$data);
            if(isset($data['redirect'])){
            	redirect($data['redirect']);
            }
        }
		$this->display ( 'hotel/orderdetail/order_detail', $data,'',$module_view );
	}
	function myorder() {
		$data = $this->common_show;
		$data['pagetitle'] = '我的订单';
        $module_view=$this->get_display_view('hotel/myorder');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->myorder(),$data);
        }
		$this->display ( 'hotel/myorder/my_order', $data,'',$module_view );

	}
	function hotel_photo() {
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/hotel_photo');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->hotel_photo(),$data);
        }

		$this->display ( 'hotel/hotel_photo/hotel_photo', $data, '' ,$module_view );
	}
	function get_new_gallery() {
		$data = HotelService::getInstance()->get_new_gallery();
		echo json_encode ( $data );
	}
	function my_marks() {
		$data = $this->common_show;
		$data['pagetitle'] = '我的收藏';
        $module_view=$this->get_display_view('hotel/my_marks');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->my_marks(),$data);
        }
		$this->display ( 'hotel/my_marks/often_like', $data, '' ,$module_view );
	}
	function get_near_hotel() {
		$data = HotelService::getInstance()->get_near_hotel();
		echo json_encode ( $data );
	}
	function hotel_comment() {
		$data = $this->common_show;
		$data['pagetitle'] = '酒店评论';
        $module_view=$this->get_display_view('hotel/hotel_comment');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->hotel_comment(),$data);
        }
		$this->display ( 'hotel/hotel_comment/hotel_reviews', $data, '' ,$module_view );
	}
	function ajax_hotel_comments(){
		$data = HotelService::getInstance()->ajax_hotel_comments();
		if($data['s']==1){
			echo json_encode ( $data, JSON_UNESCAPED_UNICODE );
		}else{
			echo json_encode ( $data );
		}
	}
	function to_comment() {
		$data = $this->common_show;
		$data['pagetitle'] = '订单评论';
        $module_view=$this->get_display_view('hotel/to_comment');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->to_comment(),$data);
            if(isset($data['redirect'])){
            	redirect($data['redirect']);
            }
        }
		$this->display ( 'hotel/hotel/to_comment', $data, '' ,$module_view );
	}
	function return_usable_coupon() {
		$data = HotelService::getInstance()->return_usable_coupon();
		echo json_encode( $data );
	}

	//@Editor lGh 返回积分配置
	function return_point_set() {
		$data = HotelService::getInstance()->return_point_set();
		echo json_encode( $data );
	}
	//@Editor lGh 返回积分配置
	function return_pointpay_set() {
		$data = HotelService::getInstance()->return_pointpay_set();
		echo json_encode( $data );
	}
	function cancel_main_order() {
		$data = HotelService::getInstance()->cancel_main_order();
		echo json_encode( $data );
	}
	function comment_sub() {
		$data = HotelService::getInstance()->comment_sub();
		echo json_encode( $data );
	}
	function return_room_detail() {
		$data = HotelService::getInstance()->return_room_detail();
		echo json_encode( $data );
	}


	function new_comment_sub() {    //提交评论
		$data = HotelService::getInstance()->new_comment_sub();
		echo json_encode($data);
	}


    function comment_no_order(){
    	$data = $this->common_show;
        $data['pagetitle'] = '酒店点评';
        $module_view=$this->get_display_view('hotel/comment_no_order');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->comment_no_order(),$data);
            if(isset($data['redirect'])){
            	redirect($data['redirect']);
            }
        }
        $this->display ( 'hotel/hotel/to_comment', $data, '' ,$module_view );

    }


    //专题页面
    function thematic_index(){
		
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/thematic_index');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->thematic_index(),$data);
        }
		$this->display ( 'hotel/thematic_index/thematic_index', $data, '' ,$module_view);
    }

    public function check_self_continue() {
        $data = HotelService::getInstance()->check_self_continue();
        echo json_encode ( $data );
    }


    // 酒店相册
    public function photo_list(){
        $data = $this->common_show;
        $module_view=$this->get_display_view('hotel/photo_list');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->photo_list(),$data);
        }
        $this->display('hotel/bigger/hotel_photo',$data, '' ,$module_view);
    }


    // 房型列表
	public function room_list(){
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/room_list');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->room_list(),$data);
        }

        $this->display('hotel/bigger/room_list',$data, '' ,$module_view);
	}
    // 套餐列表
	public function package_list(){
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/package_list');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->package_list(),$data);
        }

        $this->display('hotel/bigger/package_list',$data, '' ,$module_view);
	}

    // 提交订单
	public function submit_order(){
		$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/submit_order');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->submit_order(),$data);
        }

        $this->display('hotel/bigger/submit_order',$data, '' ,$module_view);
	}
    // 我的订单
    public function my_order(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/my_order');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->my_order(),$data);
        }

        $this->display('hotel/bigger/my_order',$data, '' ,$module_view);
    }

    // 订单详情
    public function order_details(){
        //$data['inter_id'] = $this->inter_id;
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/order_details');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->order_details(),$data);
        }
        $this->display('hotel/bigger/order_detail',$data, '' ,$module_view);
    }

    // 订单详情
    public function packages_use(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/packages_use');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->packages_use(),$data);
        }
        $this->display('hotel/bigger/packages_use',$data, '' ,$module_view);
    }
    // 房型+套餐详情
    public function package_details(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/package_details');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->package_details(),$data);
        }
        $this->display('hotel/bigger/package_details',$data, '' ,$module_view);
    }
	
    // 选择套餐
    public function package_select(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/package_select');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->package_select(),$data);
        }
        $this->display('hotel/bigger/package_select',$data, '' ,$module_view);
    }

    //城市列表
    public function city_list(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/city_list');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->city_list(),$data);
        }
        $this->display('hotel/bigger/city_list',$data, '' ,$module_view);
    }

    // 评价页面
    public function comment_list(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/comment_list');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->comment_list(),$data);
        }
        $this->display('hotel/bigger/hotel_reviews',$data, '' ,$module_view);
    }

    // 发表评价
    public function comment(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/comment');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->comment(),$data);
        }
        $this->display('hotel/bigger/to_comment',$data, '' ,$module_view);
    }

    // 酒店介绍
    public function hotel_details(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/hotel_details');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->hotel_details(),$data);
        }
        $this->display('hotel/bigger/hotel_details',$data, '' ,$module_view);
    }


    // 地图
    public function map(){
    	$data = $this->common_show;
        $module_view=$this->get_display_view('hotel/map');
		$module_view=array(
				'module_view'=>$module_view
		);
        if(!$this->is_restful($module_view['module_view']['skin_name'])){
            $data = array_merge(HotelService::getInstance()->map(),$data);
        }
        $this->display('hotel/bigger/map',$data, '' ,$module_view);
    }


}