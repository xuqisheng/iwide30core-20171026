<?php
namespace App\libraries\Iapi;

class FrontConst extends BaseConst
{

    static function get_dehydrate_samples($sample)
    {
        if (isset (self::$dehydrate_samples [$sample])) {
            $dehydrate_samples = self::$dehydrate_samples [$sample];
            if (!empty ($dehydrate_samples ['common'])) {
                $path = explode('/', $sample);
                if (isset (self::$common_dehydrate_samples [$path [0]])) {
                    foreach ($dehydrate_samples ['common'] as $com) {
                        if (isset (self::$common_dehydrate_samples [$path [0]] [$com])) {
                            if (self::$common_dehydrate_samples [$path [0]] [$com] ['type'] == 'keys') {
                                $dehydrate_samples [self::$common_dehydrate_samples [$path [0]] [$com] ['type']] [] = $com;
                            } else {
                                $dehydrate_samples [self::$common_dehydrate_samples [$path [0]] [$com] ['type']] [$com] = self::$common_dehydrate_samples [$path [0]] [$com];
                            }
                        }
                    }
                }
            }

            return $dehydrate_samples;
        }

        return array();
    }

    public static function get_enums($type, $key = null, $value = null)
    {
        switch ($type) {
            case 'oper_status' :
                $data = self::$oper_status;
                break;
            default :
                $vars = get_class_vars(__CLASS__);
                $data = isset ($vars [$type]) ? $vars [$type] : null;
        }
        if (is_array($data)) {
            if (isset ($key)) {
                return isset ($data [$key]) ? $data [$key] : null;
            }
            if (isset ($value)) {
                return in_array($value, $data);
            }
        }

        return $data;
    }

    static $oper_status = array(
        1              => 1000, // 1000: 成功
        2              => 1001, // 1001：失败，前端用toast显示错误提示（不需要用户操作，自动消失）
        3              => 1002, // 1002：失败，前端用alert显示错误提示（要点击确认）
        4              => 1003, // 1003：未登状态。
        5              => 1004, // 1004：未知错误。
        'success'      => 1000, // 1000: 成功
        'fail_toast'   => 1001, // 1001：失败，前端用toast显示错误提示（不需要用户操作，自动消失）
        'fail_alert'   => 1002, // 1002：失败，前端用alert显示错误提示（要点击确认）
        'notlogin'     => 1003, // 1003：未登状态。
        'unknow'       => 1004, // 1004：未知错误。
        'stop_service' => 1005, // 1005：公众号已停止服务
        'scan_success' => 1006, // 1006：后台登录扫码成功
    );
    static $msg_lv = array(
        0 => '',
        1 => 'toast',
        2 => 'alert',
    );
    static $common_dehydrate_samples = array(
        'hotel' => array(
            'member'      => array(
                'type' => 'arr',
                'keys' => array(
                    'member_id',
                    'name',
                    'is_login',
                    'logined',
                    'bonus',
                ),
            ),
            'pagetitle'   => array(
                'type' => 'keys',
            ),
        ),
        'membervip' => array(
            'public' => array(
                'type' => 'arr',
                'keys' => array(
                    'app_id',
                    'inter_id',
                    'name',
                    'wechat_name',
                    'create_time',
                    'email',
                    'logo',
                    'domain'
                ),
            ),
            'info' => array(
                'type' => 'arr',
                'keys' => array(
                    'nickname',
                    'sex',
                    'headimgurl',
                    'subscribe_time',
                    'fans_key'
                )
            ),
        ),
        'membervip_center' => array(
            'info' => array(
                'type' => 'arr',
                'keys' => array(
                    'nickname',
                    'sex',
                    'headimgurl',
                    'subscribe_time',
                    'fans_key'
                )
            ),
            'centerinfo' => array(
                'type' => 'arr',
                'keys' => array(
                    'member_id',
                    'mem_card_no',
                    'member_info_id',
                    'member_mode',
                    'name',
                    'sex',
                    'birth',
                    'birth_date',
                    'telephone',
                    'cellphone',
                    'qq',
                    'email',
                    'id_card_no',
                    'is_active',
                    'is_login',
                    'member_lvl_id',
                    'lvl_pms_code',
                    'membership_number',
                    'nickname',
                    'balance',
                    'balance_accumulate',
                    'credit',
                    'member_type',
                    'employee_id',
                    'company_name',
                    'type_code',
                    'value',
                    'lvl_name',
                    'card_count',
                    'lvl_icon',
                    'wx_lvl_bg_url',
                    'member_status'
                )
            )
        )
    );
    static $dehydrate_samples = array(
        'hotel/hotel/search'               => array(
            'common'  => array(
                'member',
            ),
            'keys'    => array(
                'pre_sp_date',
                'first_city',
                'hot_city',
                'citys',
                'area',
                'homepage_set',
            ),
            'mul_arr' => array(
                'last_orders'      => array(
                    'keys' => array(
                        'hname',
                        'link',
                        'hcity',
                    ),
                ),
                'hotel_collection' => array(
                    'keys' => array(
                        'mark_title',
                        'link',
                    ),
                ),
                'pubimgs'          => array(
                    'keys' => array(
                        'image_url',
                        'link',
                    ),
                ),
            ),
        ),
        'hotel/hotel/sresult'              => array(
            'common' => array(
                'member',
            ),
            'keys'   => array(
                'inter_id',
                'startdate',
                'enddate',
                'city',
                'area',
                'hotel_ids',
                'pre_sp_date',
                'nearby',
            ),
        ),
        'hotel/hotel/return_lowest_price'  => array(
            'common' => array(
                'member',
            ),
            'keys'   => array(
                'return_lowest_price',
            ),
        ),
        'hotel/hotel/index'                => array(
            'common'  => array(
                'member',
            ),
            'keys'    => array(
                'inter_id',
                'csrf_token',
                'csrf_value',
                'startdate',
                'enddate',
                'gallery_count',
                'countday',
                'collect_id',
                'middle_ads',
                'max_book_day',
                'pre_sp_date',
                'minSelect',
                'foot_ads',
                'icons_set',
                'packages',
            ),
            'mul_arr' => array(
                'rooms' => array(
                    'keys' => array(
                        'show_info',
                        'lowest',
                        'top_price',
                        'state_info'
                    ),
                    'arr' => array(
                        'room_info' =>array(
                            'keys' => array(
                                'room_id',
                                'room_img',
                                'name',
                                'area',
                                'sub_des',
                                'oprice',
                            ),
                        ),
                    ),
                    'mul_arr' => array(
                        'state_info' =>array(
                            'keys' => array(
                                'price_name',
                                'des',
                                'price_tags',
                                'useable_coupon_favour',
                                'wxpay_favour_sign',
                                'bookpolicy_condition',
                                'avg_price',
                                'avg_oprice',
                                'allprice',
                                'condition',
                                'price_code',
                                'price_type',
                                'book_status',
                            ),
                        ),
                    ),
                ),
            ),
            'arr' => array(
                'hotel'      => array(
                    'keys' => array(
                        'hotel_id',
                        'imgs',
                        'name',
                        'address',
                        'book_policy',
                    ),
                ),
                't_t'      => array(
                    'keys' => array(
                        'comment_score',
                        'comment_count',
                    ),
                ),
            ),
        ),
        'hotel/hotel/return_more_room'     => array(
            'common'  => array(
                'member',
            ),
            'mul_arr' => array(
                'rooms' => array(
                    'keys' => array(
                        'room_info',
                        'state_info',
                        'show_info',
                        'lowest',
                        'highest',
                        'all_full',
                        'top_price',
                    ),
                ),
            ),
        ),
        'hotel/hotel/hotel_detail'         => array(
            'keys' => array(
                'hotel',
            ),
        ),
        'hotel/hotel/add_hotel_collection' => array(
            'keys' => array(
                'inter_id',
                'mid',
            ),
        ),
        'hotel/hotel/clear_visited_hotel'  => array(
            'keys' => array(
                'clear_result',
            ),
        ),
        'hotel/hotel/cancel_one_mark'      => array(
            'keys' => array(
                'cancel_mark',
            ),
        ),
        'hotel/hotel/orderdetail'          => array(
            'keys'   => array(
                'inter_id',
                'not_same',
                're_pay',
                'states',
                'order',
                'hotel',
                'timeout',
                'invoice_info',
                'startdate_weekday',
                'enddate_weekday',
            ),
            'arr' => array(
                'hotel'      => array(
                    'keys' => array(
                        'book_policy',
                        'name',
                        'address',
                        'latitude',
                        'longitude',
                    ),
                ),
                'order'      => array(
                    'keys' => array(
                        'status',
                        'status_des',
                        'paytype',
                        'price',
                        'orderid',
                        'id',
                        'hotel_id',
                        'price_type',
                        'hname',
                        'latitude',
                        'longitude',
                        'haddress',
                        'roomnums',
                        'startdate',
                        'enddate',
                        'name',
                        'tel',
                        'goods_details',
                        'show_orderid',
                        'order_time',
                        'coupon_favour',
                        'point_used_amount',
                        'point_favour',
                        'wxpay_favour',
                        'customer_remark',
                        'paytype_des',
                    ),
                    'arr' => array(
                        'first_detail' =>array(
                            'keys' => array(
                                'roomname',
                                'price_code_name',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'hotel/hotel/myorder'              => array(
            'keys'    => array(
                'handled',
            ),
            'mul_arr' => array(
                'orders' => array(
                    'keys' => array(
                        'INDEX',
                        'ORDERDETAIL',
                        'TO_COMMENT',
                        'CHECK_OUT',
                        'id',
                        'status',
                        'status_des',
                        'hname',
                        'roomnums',
                        'startdate',
                        'enddate',
                        'price',
                        're_pay',
                        'can_comment',
                        'hotel_id',
                        'price_type',
                    ),
                    'arr' => array(
                        'first_detail' =>array(
                            'keys' => array(
                                'roomname',
                            ),
                        ),
                        'orderstate' => array(
                            'keys' => array(
                                'last_repay_time',
                                'repay_url',
                                'self_checkout',
                                'self_checkout_des',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'hotel/check/ajax_hotel_search'    => array(
            'keys'    => array(
                'exe_param',
                'index_url',
            ),
            'mul_arr' => array(
                'result'      => array(
                    'keys' => array(
                        'hotel_id',
                        'name',
                        'link',
                    ),
                ),
            ),
        ),
        'hotel/check/ajax_hotel_list'     => array(
            'keys'    => array(
                'exe_param',
                'index_url',
                'landmark',
            ),
            'mul_arr' => array(
                'result'      => array(
                    'keys' => array(
                        'hotel_id',
                        'name',
                        'intro_img',
                        'comment_data',
                        'distance',
                        'address',
                        'lowest',
                        'link',
                    ),
                ),
            ),
        ),
        'hotel/hotel/bookroom'                => array(
            'common'  => array(
                'member',
            ),
            'keys'    => array(
                'inter_id',
                'csrf_token',
                'csrf_value',
                'my_saler_id',
                'url_param',
                'price_codes',
                'price_type',
                'startdate',
                'enddate',
                'hotel_id',
                'hotel',
                'room_list',
                'first_room',
                'first_state',
                'first_pay_favour',
                'bookpolicy_condition',
                'customer_condition',
                'extra_info',
                'packages',
                'post_packages',
                'packages_price',
                'total_price',
                'total_oprice',
                'addit_service',
                'point_name',
                'athour',
                'room_count',
                'point_pay_set',
                'has_point_pay',
                'extra_pointpay_para',
                'pay_ways',
                'source_data',
                'last_order',
                'point_consum_set',
                'point_consum_rate',
                'banlance_code',
                'point_pay_code',
                'extra_para',
                'select_coupon_favour',
                'use_coupon_code',
                'select_coupons',
                'bonus_setting',
                'exchange_max_point',
                'paytype_icon',
                'type',
                'days',
            ),
            'mul_arr' => array(
                'rooms' => array(
                    'keys' => array(
                        'room_info',
                        'state_info',
                        'show_info',
                        'lowest',
                        'highest',
                        'all_full',
                        'top_price',
                    ),
                ),
            ),
        ),
        'hotel/hotel/to_comment'     => array(
            'keys'    => array(
                'comment',
                'comment_config',
                'comment_info',
                'index_url',
            ),
            'arr' => array(
                'first_room'      => array(
                    'keys' => array(
                        'room_id',
                    ),
                ),
                'order'      => array(
                    'keys' => array(
                        'hotel_id',
                        'hname',
                        'startdate',
                        'enddate',
                        'orderid',
                        'roomnight'
                    ),
                    'arr' => array(
                        'first_detail'=>array(
                            'keys' => array(
                                'roomname',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'membervip_center/member_center' => array(
            'common'  => array(
                'info',
                'centerinfo'
            ),
            'keys' => array(
                'assets_bottons',
                'inter_id',
                'filed_name',
                'isDistribution',
                'is_club',
                'menu'
            )
        ),
        'membervip_center/center/info' => array(
            'common'  => array(
                'info',
                'centerinfo'
            ),
            'keys' => array(
                'modify_config',
                'inter_id',
            )
        ),
        'membervip_center/qrcode' => array(
            'common'  => array(
                'centerinfo'
            )
        ),
        'membervip/card/index' => array(
            'common'  => array(
                'public',
            ),
            'keys'=>array(
                'all',
                'usableCardLists',
                'unusedCardLists',
                'expiredCardLists',
                'next_id',
                'inter_id'
            ),
        ),
        'membervip/card/cardinfo' => array(
            'common'  => array(
                'public',
            ),
            'keys'=>array(
                'user',
                'inter_id',
                'openid',
                'card_info',
                'my_card',
                'auth_useoff',
                'wx_config',
                'base_api_list',
                'js_api_list',
                'js_menu_show',
                'js_menu_hide',
                'js_share_config',
                'auth_gift',
                'authentication_give'
            ),
        ),
        'membervip/card/pcardinfo' => array(
            'common'  => array(
                'public',
            ),
            'keys'=>array(
                'user',
                'inter_id',
                'openid',
                'card_info',
                'my_card',
                'auth_useoff',
                'signpackage',
                'auth_gift',
                'next_id',
                'authentication_give'
            ),
        ),
        'membervip/card/getcard' => array(
            'common'  => array(
                'public',
            ),
            'keys'=>array(
                'card_info',
                'err_msg',
                'gain_count',
                'card_url',
                'signpackage',
                'card_rule_id',
                'inter_id',
                'filed_name',
            ),
        ),
        'membervip/card/receive' => array(
            'common'  => array(
                'public',
            ),
            'keys'=>array(
                'ec_code',
                'gift_mem_info',
                'user',
                'inter_id',
                'openid',
                'card_info',
                'can_received'
            ),
        ),
        'membervip/card/codeuseoff' => array(
            'common'  => array(
                'public',
            ),
            'keys'=>array(
                'title',
                'type',
                'message',
                'callback',
                'js_api_list',
                'openid',
                't',
                'signpackage'
            ),
        ),
        'membervip/depositcard/edituser'               => array(
            'keys'    => array(
                'card_id',
                'card_info',
                'signpackage',
                'pay_type',
            ),
            'arr' => array(
                'public'      => array(
                    'keys' => array(
                        'name',
                        'logo',
                    ),
                ),
                'info'      => array(
                    'keys' => array(
                        'name',
                        'cellphone',
                        'id_card_no',
                    ),
                ),
            ),
        ),
        'membervip/balance/pay'               => array(
            'keys'    => array(
                'orderid',
                'order',
                'links',
                'signpackage',
                'filed_name',
                'total_deposit',
            ),
            'arr' => array(
                'public'      => array(
                    'keys' => array(
                        'name',
                        'logo',
                    ),
                ),
            ),
        ),
        'membervip/reg/index'               => array(
            'keys'    => array(
                'login_config',
                'inter_id',
                'sales_id',
                'succ_url',
                'redir',
            ),
            'arr' => array(
                'public'      => array(
                    'keys' => array(
                        'name',
                        'logo',
                    ),
                ),
            ),
        ),
        'membervip/balance/setpwd'               => array(
            'common'  => array(
                'info',
            ),
        ),
        'membervip/balance/changepwd'               => array(
            'common'  => array(
                'info',
            ),
        ),
        'membervip_center/perfectinfo/index' => array(
            'common'  => array(
                'info',
                'centerinfo'
            ),
            'keys' => array(
                'modify_config',
                'inter_id',
            )
        ),
        'membervip/depositcard/okpay' => array(
            'keys' => array(
                'filed_name',
                'jump_url',
                'orderId',
                'orderNum',
                'inter_id',
            ),
            'arr' => array(
                'info'      => array(
                    'keys' => array(
                        'name',
                        'cellphone',
                        'id_card_no',
                    ),
                ),
            ),
        ),
    );
}