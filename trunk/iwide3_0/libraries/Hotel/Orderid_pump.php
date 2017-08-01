<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Orderid_pump {
    public function __construct() {
    }
    static $business_code = array (
            'hotel_order' => 1,
            'hotel_goods' => 2,
            'default_code' => 9 
    );
    public static function get_enums($type, $key = NULL, $value = NULL) {
        switch ($type) {
            case 'business_code' :
                $data = self::$business_code;
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
    public static function pump_orderid($business, $params = array()) {
        $business_code = self::get_enums ( 'business_code', $business );
        if (! $business_code)
            return NULL;
        $CI = & get_instance ();
        $result = $CI->db->query ( 'SELECT upget_orderid(' . $business_code . ') as orderid' )->row_array ();
        if (! empty ( $result ['orderid'] ))
            return $result ['orderid'];
        if (! empty ( $params ['fail_callback_func'] ) && ! empty ( $params ['fail_callback_obj'] ) && is_object ( $params ['fail_callback_obj'] ) && method_exists ( $params ['fail_callback_obj'], $params ['fail_callback_func'] )) {
            $fail_callback_param = empty ( $params ['fail_callback_param'] ) ? array () : $params ['fail_callback_param'];
            return call_user_func_array ( array (
                    $params ['fail_callback_obj'],
                    $params ['fail_callback_func'] 
            ), $fail_callback_param );
        }
        if (empty ( $params ['no_default'] )) {
            MYLOG::w ( 'not enough code : ' . $business . "($business_code)", 'orderid_pool', '_waring' );
            $result = $CI->db->query ( 'SELECT upget_orderid(' . self::$business_code ['default_code'] . ') as orderid' )->row_array ();
            if (! empty ( $result ['orderid'] ))
                return $result ['orderid'];
        }
        return NULL;
    }
    public static function default_orderid() {
        $now = time ();
        return '9' . str_pad ( ceil ( ($now - 1497801600) / 86400 ), 4, STR_PAD_LEFT ) . substr ( $now, - 5 );
    }
}
