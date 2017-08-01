<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_const {
	public static $url_seg = array (
			'SEARCH' => 'hotel/hotel/search', // 搜索页
			'SRESULT' => 'hotel/hotel/sresult', // 搜索结果页
			'RETURN_LOWEST_PRICE' => 'hotel/hotel/return_lowest_price', // 获取最低价
			'INDEX' => 'hotel/hotel/index', // 酒店房型列表页
			'RETURN_MORE_ROOM' => 'hotel/hotel/return_more_room', // 更新房型列表
			'HOTEL_DETAIL' => 'hotel/hotel/hotel_detail', // 酒店详情页
			'AROUNDS' => 'hotel/hotel/arounds', // 酒店周边页
			'BOOKROOM' => 'hotel/hotel/bookroom', // 下单页
			'SAVEORDER' => 'hotel/hotel/saveorder', // 提交订单
			'ADD_HOTEL_COLLECTION' => 'hotel/hotel/add_hotel_collection', // 增加收藏
			'CLEAR_VISITED_HOTEL' => 'hotel/hotel/clear_visited_hotel', // 清除浏览记录
			'CANCEL_ONE_MARK' => 'hotel/hotel/cancel_one_mark', // 取消收藏
			'ORDERDETAIL' => 'hotel/hotel/orderdetail', // 订单详情页
			'MYORDER' => 'hotel/hotel/myorder', // 我的订单页
			'HOTEL_PHOTO' => 'hotel/hotel/hotel_photo', // 酒店相册页
			'GET_NEW_GALLERY' => 'hotel/hotel/get_new_gallery', // 加载酒店相册
			'MY_MARKS' => 'hotel/hotel/my_marks', // 我的收藏页
			'GET_NEAR_HOTEL' => 'hotel/hotel/get_near_hotel', // 获取附近酒店
			'HOTEL_COMMENT' => 'hotel/hotel/hotel_comment', // 酒店评论页
			'AJAX_HOTEL_COMMENTS' => 'hotel/hotel/ajax_hotel_comments', // 加载酒店评论
			'TO_COMMENT' => 'hotel/hotel/to_comment', // 提交评论页
			'RETURN_USABLE_COUPON' => 'hotel/hotel/return_usable_coupon', // 加载可用券
			'RETURN_POINT_SET' => 'hotel/hotel/return_point_set', // 返回积分兑换配置
			'RETURN_POINTPAY_SET' => 'hotel/hotel/return_pointpay_set', // 返回积分支付配置
			'CANCEL_MAIN_ORDER' => 'hotel/hotel/cancel_main_order', // 取消订单
			'COMMENT_SUB' => 'hotel/hotel/comment_sub', // 提交评论
			'RETURN_ROOM_DETAIL' => 'hotel/hotel/return_room_detail', // 加载房型详情
			'NEW_COMMENT_SUB' => 'hotel/hotel/new_comment_sub', // 提交评论（新版）
			'COMMENT_NO_ORDER' => 'hotel/hotel/comment_no_order', // 无订单时提交评论
			'NEARBY' => 'hotel/check/nearby', // 附近酒店页
			'MY_COLLECTION' => 'hotel/check/my_collection', // 我的收藏页（有详情）
			'CHECK_REPAY' => 'hotel/check/check_repay', // 检查是否能再支付
			'AJAX_HOTEL_LIST' => 'hotel/check/ajax_hotel_list', // 加载酒店列表
			'AJAX_CITY_FILTER' => 'hotel/check/ajax_city_filter', // 加载搜索筛选项
			'AJAX_HOTEL_SEARCH' => 'hotel/check/ajax_hotel_search', // 异步搜索酒店
			'CHECK_ORDER_CANPAY' => 'hotel/check/check_order_canpay', // 检查是否能再支付
			'CHECK_OUT' => 'hotel/invoice/check_out', // 预约退房页
			'CHECKOUT_POST' => 'hotel/invoice/checkout_post', // 预约退房提交
			'MY_INVOICE' => 'hotel/invoice/my_invoice', // 我的发票页
			'CHOOSE_INVOICE' => 'hotel/invoice/choose_invoice', //
			'CHOOSE' => 'hotel/invoice/choose', //
			'EDIT_INVOICE' => 'hotel/invoice/edit_invoice', //
			'SUBMIT_RESULT' => 'hotel/invoice/submit_result', //
			'INVOICE_POST' => 'hotel/invoice/invoice_post', //
			'BOOK_CHECKOUT' => 'hotel/invoice/book_checkout', //
			'GET_CHECKOUT' => 'hotel/invoice/get_checkout',
			'PROCESSING' => 'hotel/invoice/processing', 
			'THEMATIC_INDEX' => 'hotel/hotel/thematic_index', //专题活动
			'CHECK_SELF_CONTINUE' => 'hotel/hotel/check_self_continue',
			'ASYN_INVOICES' => 'hotel/invoice/asyn_invoices'//异步获取已填写发票列表
	);
	public static $order_channel = array (
			'package' => '套票预订',
			'weixin' => '微信公众号',
			'wxapp' => '微信小程序'
	);
	public static $saler_redirect_url = array (
			'SEARCH',
			'SRESULT',
			'INDEX',
			'NEARBY',
			'THEMATIC_INDEX' 
	);
	public static $query_member_controller = array (
			'hotel',
	        'check'
	);
	public static $fresh_memberinfo_url = array (
			'BOOKROOM',
			'SAVEORDER',
			'INDEX',
	        'RETURN_MORE_ROOM',
// 	        'RETURN_POINT_SET',
// 	        'RETURN_POINTPAY_SET',
	        'ORDERDETAIL',
	        'MYORDER',
	        'TO_COMMENT',
	        'CANCEL_MAIN_ORDER',
// 	        'RETURN_USABLE_COUPON',
	        'CHECK_REPAY',
	        'CHECK_ORDER_CANPAY',
	        'MY_COLLECTION'
	);
	public static $ajax_status = array (
			'1' => 1,
			'0' => 0 
	);
	public static $order_status_oprate = array (
			'1' => '操作确认',
			'2' => '操作入住',
			'3' => '操作离店',
			'5' => '操作取消',
			'8' => '操作未到' 
	);
	public static function enums($type, $key = NULL, $value = NULL) {
		switch ($type) {
			case 'url_seg' :
				$data = self::$url_seg;
				break;
			case 'ajax_status' :
				$data = self::$ajax_status;
				break;
			case 'order_channel' :
				$data = self::$order_channel;
				break;
			case 'order_status_oprate' :
				$data = self::$order_status_oprate;
				break;
			case 'saler_redirect_url' :
				$data = self::abbr2seg(self::$saler_redirect_url);
				break;
			case 'fresh_memberinfo_url' :
				$data = self::abbr2seg(self::$fresh_memberinfo_url);
				break;
			default :
				$vars = get_class_vars ( __CLASS__ );
				$data = isset ( $vars [$type] ) ? $vars [$type] : NULL;
		}
		if (is_array ( $data )) {
			if (isset ( $key )) {
				return isset ( $data [$key] ) ? $data [$key] : NULL;
			}
			if (isset ( $value )) {
				return in_array ( $value, $data );
			}
		}
		return $data;
	}
	public static function abbr2seg($abbrs) {
		$data = array ();
		foreach ( $abbrs as $a ) {
			$data [] = isset ( self::$url_seg [$a] ) ? self::$url_seg [$a] : $a;
		}
		return $data;
	}
}
