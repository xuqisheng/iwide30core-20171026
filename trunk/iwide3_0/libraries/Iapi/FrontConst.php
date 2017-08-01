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
                ),
            ),
            'signPackage' => array(
                'type' => 'arr',
                'keys' => array(
                    'appId',
                    'nonceStr',
                    'timestamp',
                    'url',
                    'signature',
                    'rawString',
                ),
            ),
            'pagetitle'   => array(
                'type' => 'keys',
            ),
        ),
    );
    static $dehydrate_samples = array(
        'hotel/hotel/search'               => array(
            'common'  => array(
                'member',
                'signPackage',
                'pagetitle',
            ),
            'keys'    => array(
                'pre_sp_date',
                'first_city',
                'hot_city',
                'citys',
            ),
            'mul_arr' => array(
                'last_orders'      => array(
                    'keys' => array(
                        'hname',
                        'hotel_id',
                    ),
                ),
                'hotel_collection' => array(
                    'keys' => array(
                        'mark_name',
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
                'signPackage',
                'pagetitle',
            ),
            'keys'   => array(
                'inter_id',
                'startdate',
                'enddate',
                'city',
                'area',
                'hotel_ids',
                'pre_sp_date',
            ),
        ),
        'hotel/hotel/return_lowest_price'  => array(
            'common' => array(
                'member',
                'signPackage',
                'pagetitle',
            ),
            'keys'   => array(
                'return_lowest_price',
            ),
        ),
        'hotel/hotel/index'                => array(
            'common'  => array(
                'member',
                'signPackage',
                'pagetitle',
            ),
            'keys'    => array(
                'inter_id',
                'csrf_token',
                'csrf_value',
                'hotel',
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
                't_t',
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
        'hotel/hotel/return_more_room'     => array(
            'common'  => array(
                'member',
                'signPackage',
                'pagetitle',
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
                'mark_id',
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
            'common' => array(
                'member',
                'signPackage',
                'pagetitle',
            ),
            'keys'   => array(
                'inter_id',
                'status_des',
                'pay_ways',
                'not_same',
                'can_cancel',
                'can_comment',
                're_pay',
                'states',
                'order_sequence',
                'order',
                'first_room',
                'hotel',
                'timeout',
                'invoice_info',
            ),
        ),
        'hotel/hotel/myorder'              => array(
            'keys'    => array(
                'handled',
            ),
            'mul_arr' => array(
                'orders' => array(),
            ),
        ),
    );
}