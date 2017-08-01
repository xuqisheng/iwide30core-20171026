<?php
namespace App\libraries\Iapi;

class AdminConst extends BaseConst
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
            case 'url_seg' :
                $data = self::$url_seg;
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
        'auth_deny'    => 1006 // 1006：无权限访问
    );

    static $msg_lv = array(
        0 => '',
        1 => 'toast',
        2 => 'alert',
    );

    static $common_dehydrate_samples = array();

    static $dehydrate_samples = array(
        'hotel/price/price_codes'       => array(
            'keys'    => array(
                'hotel_id',
                'type',
                'enum_des',
                'fields_config',
            ),
            'mul_arr' => array(
                'hotels' => array(
                    'keys' => array(
                        'hotel_id',
                        'name',
                    ),
                ),
            ),
        ),
        'hotel/goods/get_list'          => array(
            'keys'    => array(
                'csrf_token',
                'csrf_value'
            ),
            'mul_arr' => array(
                'items' => array(
                    'keys' => array(
                        'name',
                        'price_package',
                        'price',
                        'stock',
                        'status',
                        'soma_status',
                        'goods_id',
                        'unit',
                        'validity_date',
                        'un_validity_date',
                        'goods_type',
                        'sort',
                        'short_intro',
                    ),
                ),
            ),
        ),
        'hotel/prices/code_edit'        => array(
            'keys'    => array(
                'csrf_token',
                'csrf_value',
                'price_code',
                'price_codes',
                'enum_des',
                'bf_fields',
                'levels',
                'list',
                'is_pms',
            ),
            'mul_arr' => array(
                'pay_ways'     => array(
                    'keys' => array(
                        'pay_type',
                        'pay_name',
                    ),
                ),
                'coupon_types' => array(
                    'keys' => array(
                        'card_id',
                        'title',
                    ),
                ),
            ),
        ),
        'hotel/rooms/get_rooms'         => array(
            'mul_arr' => array(
                'items' => array(
                    'keys' => array(
                        'hotel_id',
                        'name',
                        'room_ids',
                    ),
                ),
            ),
        ),
        'hotel/rooms/get_rooms_by_code' => array(
            'mul_arr' => array(
                'items' => array(
                    'keys' => array(
                        'hotel_id',
                        'name',
                        'room_ids',
                    ),
                ),
            ),
        ),
        'hotel/orders/get_lists'        => array(
            'keys'    => array(
                'show_status',
            ),
            'mul_arr' => array(
                'lists' => array(
                    'keys'    => array(
                        'id',
                        'orderid',
                        'coupon_favour',
                        'point_favour',
                        'hotel_id',
                        'roomnums',
                        'name',
                        'tel',
                        'order_time',
                        'startdate',
                        'enddate',
                        'status',
                        'paytype',
                        'hname_rname',
                        'show_orderid',
                        'is_paid',
                        'real_price',
                        'no_status',
                        'opt_status',
                        'is_package',
                    ),
                    'mul_arr' => array(
                        'order_details' => array(
                            'keys' => array(
                                'id',
                                'iprice',
                                'istatus',
                                'roomname',
                                'price_code_name',
                                'r_room_img',
                                'item_opt_status',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );
}