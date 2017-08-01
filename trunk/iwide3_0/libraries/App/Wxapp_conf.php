<?php
class Wxapp_conf {
	static function get_dehydrate_samples($sample) {
		if (isset ( self::$dehydrate_samples [$sample] )) {
			return self::$dehydrate_samples [$sample];
		}
		return array ();
	}
	static function get_enums($type) {
		if (isset ( self::$enums [$type] )) {
			return self::$enums [$type];
		}
		return array ();
	}
	static $enums = array (
			// 1000=>成功
			// 1001：失败，前端用toast显示错误提示（不需要用户操作，自动消失）
			// 1002：失败，前端用alert显示错误提示（要点击确认）
			// 1003：未登状态。
			'status' => array (
					1 => 1000,
					2 => 1001,
					3 => 1000,
					4 => 1003 
			),
			'errcode' => array (),
			'msg_lv' => array (
					0 => '',
					1 => 'toast',
					2 => 'alert' 
			) 
	);
	static $no_html_blank_funcs=array(
	        'hotel/room_state',
	        'hotel/hotel_detail'
	);
	static $dehydrate_samples = array (
			'hotel/search' => array (
					'ks' => array (
							'pre_sp_date',
							'first_city',
							'hot_city',
							'citys',
							'hotel_collection'
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name',
											'is_login' 
									) 
							) 
					),
					'fks' => array (
							'last_orders' => array (
									'ks' => array (
											'hname',
											'hotel_id' 
									) 
							),
							'pubimgs' => array (
									'ks' => array (
											'image_url',
											'link' 
									) 
							) 
					) 
			),
			'hotel/bookroom' => array (
					'ks' => array (
							'athour',
							'banlance_code',
							'extra_para',
							'hotel_id',
							'total_price',
							'total_oprice',
							'source_data',
							'point_consum_rate',
							'point_consum_set',
							'point_exchange',
							'price_codes',
							'price_type',
							'startdate',
							'enddate' 
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name',
											'bonus',
											'balance',
											'mem_id'
									) 
							),
							'first_room' => array (
									'kas' => array (
											'room_info' => array (
													'ks' => array (
															'area',
															'name',
															'sub_des' 
													) 
											) 
									) 
							),
							'hotel' => array (
									'ks' => array (
											'name',
											'book_policy' 
									) 
							),
							'last_order' => array (
									'ks' => array (
											'name',
											'tel' 
									) 
							),
							'first_state' => array (
									'ks' => array (
											'add_service_set',
											'bonus_condition',
											'condition',
											'coupon_condition',
											'least_num',
											'price_code',
											'price_name',
											'price_type',
											'total',
											'total_price',
									        'allprice'
									) 
							) 
					),
					'fks' => array (
							'pay_ways' => array (
									'ks' => array (
											'pay_name',
											'pay_type' 
									) 
							) 
					) 
			),
			'hotel/orderdetail' => array (
					'ks' => array (
							'not_same',
							'can_cancel',
							'can_comment',
							're_pay',
							'order_sequence',
							'status_des' 
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name' 
									) 
							),
							'order' => array (
									'ks' => array (
											'handled',
											'order_time',
											'hname',
											'htel',
											'haddress',
											'holdtime',
											'startdate',
											'show_orderid',
											'enddate',
											'startdate',
											'latitude',
											'longitude',
											'status_des',
											'paytype',
											'price',
											'paid',
											'roomnums',
											'hotel_id' 
									),
									'kas' => array (
											'first_detail' => array (
													'ks' => array (
															'roomname',
															'price_code_name' 
													) 
											) 
									),
									'fks' => array (
											'order_details' => array (
													'ks' => array (
															'istatus' 
													) 
											) 
									) 
							),
							'first_room' => array (
									'ks' => array (
											'sub_des',
											'name' 
									) 
							) 
					) 
			),
			'hotel/sresult' => array (
					'ks' => array (
							'city',
							'keyword',
							'extra_condition',
							'pre_sp_date' 
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name' 
									) 
							) 
					),
					'fks' => array (
							'result' => array (
									'ks' => array (
											'hotel_id',
											'address',
											'comment_data',
											'short_intro',
											'intro_img',
											'latitude',
											'longitude',
											'lowest',
											'name',
											'characters'
									) 
							) 
					) 
			),
			'hotel/room_state' => array (
					'ks' => array (
							'pre_sp_date',
							't_t',
							'gallery_count',
							'startdate',
							'enddate' ,
							'collect_id',
							'swiper_show',
							'image_show',
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name' 
									) 
							),
							'hotel' => array (
									'ks' => array (
											'hotel_id',
											'name',
											'address',
											'latitude',
											'longitude',
											'province',
											'city',
											'tel',
											'intro',
											'short_intro',
											'book_policy' 
									),
									'kas' => array (
											'imgs' => array (
													'fks' => array (
															'hotel_lightbox' => array (
																	'ks' => array (
																			'image_url',
																			'info' 
																	) 
															) 
													) 
											) 
									) 
							) 
					),
					'fks' => array (
							'rooms' => array (
									'ks' => array (
											'all_full',
											'highest',
											'lowest',
											'top_price' 
									),
									'kas' => array (
											'room_info' => array (
													'ks' => array (
															'area',
															'bed_num',
															'name',
															'room_id',
															'room_img',
															'sub_des' 
													),
													'kas' => array (
															'imgs' => array (
																	'fks' => array (
																			'hotel_room_service' => array (
																					'ks' => array (
																							'image_url',
																							'info' 
																					) 
																			) 
																	) 
															) 
													) 
											) 
									),
									'fks' => array (
											'state_info' => array (
													'ks' => array (
															'avg_price',
															'book_status',
															'des',
															'price_code',
															'price_name',
															'price_type' 
													) 
											),
											'show_info' => array (
													'ks' => array (
															'avg_price',
															'price_code',
															'price_name'
													)
											)
									) 
							) 
					) 
			),
			'hotel/myorder' => array (
					'ks' => array (
							'inter_id' 
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name' 
									) 
							) 
					),
					'fks' => array (
							'orders' => array (
									'ks' => array (
											'name',
											'tel',
											'id',
											'startdate',
											'enddate',
											'holdtime',
											'status_des',
											'status',
											'price',
											'orderid',
											'hname' 
									),
									'kas' => array (
											'first_detail' => array (
													'ks' => array (
															'id',
															'roomname' 
													) 
											) 
									),
									'fks' => array (
											'order_details' => array (
													'ks' => array (
															'id',
															'roomname' 
													) 
											) 
									) 
							) 
					) 
			),
			'hotel/hotel_comment' => array (
					'ks' => array (
							't_t',
							'hotel_id' 
					),
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name' 
									) 
							) 
					),
					'fks' => array (
							'comments' => array (
									'ks' => array (
											'comment_time',
											'content',
											'headimgurl',
											'liked',
											'nickname',
											'order_info',
											'orderid',
											'score',
											'type',
											'member_level',
											'hotel_said'
									) 
							) 
					) 
			),
			'hotel/hotel_detail' => array (
					'kas' => array (
							'member' => array (
									'ks' => array (
											'member_id',
											'name' 
									) 
							),
							'hotel' => array (
									'ks' => array (
											'address',
											'city',
											'hotel_id',
											'intro',
											'intro_img',
											'latitude',
											'longitude',
											'name',
											'short_intro',
											'star',
											'tel' 
									),
									'kas' => array (
											'imgs' => array (
													'fks' => array (
															'hotel_service' => array (
																	'ks' => array (
																			'image_url',
																			'info' 
																	) 
															) 
													) 
											) 
									) 
							) 
					) 
			),
			'membervip/center' => array () 
	);
}