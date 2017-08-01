<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Goods_order_model extends MY_Model_Hotel {
    function __construct() {
        parent::__construct ();
    }
    public $goods_order_fields = array (
            'ORDER_ITEM' => ' `gorderid`,`goods_name`,`nums`,`gprice`,`oprice`,`gstatus`,`external_channel`,`external_orderid`,`sale_way`,`goods_id`,`handled`' 
    );
    public $order_status = array (
            'not_paid' => 9,
            'ensure' => 1,
            'user_cancel' => 4,
            'sys_cancel' => 11 
    );
    /*'11等待支付'
     '15拼团中',
     '12购买成功'
     '13申请发票'
     '10订单挂起'
     '14订单取消'
     '17订单退款'
     '61支付异常'
     '18未支付取消'*/
    public $soma_order_status = array (
            '11' => 9,
            '12' => 1,
            '14' => 4,
            '18' => 11 
    );
    public $soma_order_status_des = array (
            '9' => '未支付',
            '1' => '未使用',
            '4' => '已取消',
            '2' => '部分消费',
            '3' => '已使用',
            '11' => '未支付取消' 
    );
    public $handle_status = array (
            3,
            4,
            11 
    );
    public function get_price_package_state($inter_id, $rooms, $goods_ids = array(), $params = array()) {
        if (empty ( $goods_ids )) {
            $goods_ids = array ();
            foreach ( $rooms as $room_key => $r ) {
                if (! empty ( $r ['state_info'] )) {
                    foreach ( $r ['state_info'] as $state_key => $state ) {
                        if (! empty ( $state ['goods_info'] ['items'] )) {
                            $goods_ids = array_merge ( $goods_ids, array_column ( $state ['goods_info'] ['items'], 'gs_id' ) );
                        }
                    }
                }
            }
            $goods_ids and $goods_ids = array_unique ( $goods_ids );
        }
        $this->load->model ( 'hotel/goods/Goods_info_model' );
        $goods_info = $this->Goods_info_model->get_goods_info ( $inter_id, $goods_ids, 1, TRUE, 'STATE_FIELD', array (
                'valid_external' => 1 
        ) );
        $packages = array ();
        $room_night = get_room_night ( $params ['startdate'], $params ['enddate'], 'ceil' );
        foreach ( $rooms as $room_key => $r ) {
            if (! empty ( $r ['state_info'] )) {
                foreach ( $r ['state_info'] as $state_key => $state ) {
                    if (! empty ( $state ['goods_info'] ['items'] )) {
                        $tmp = array ();
                        $break_tag = 0;
                        $book_status = '';
                        if ($state ['book_status'] != 'available') {
                            $book_status = 'disabled';
                        }
                        $disable_count = 0;
                        $selected_package_price = 0;
                        foreach ( $state ['goods_info'] ['items'] as $i ) {
                            if (! empty ( $goods_info [$i ['gs_id']] )) {
                                $tmp ['package_info'] ['items'] [$i ['gs_id']] = array (
                                        'goods_id' => $i ['gs_id'],
                                        'goods_name' => $goods_info [$i ['gs_id']] ['external_info'] ['name'],
                                        'details' => $goods_info [$i ['gs_id']] ['external_info'] ['details'],
                                        'intro_img' => $goods_info [$i ['gs_id']] ['external_info'] ['intro_img'],
                                        'sale_way' => $state ['goods_info'] ['sale_way'],
                                        'price' => $goods_info [$i ['gs_id']] ['price'],
                                        'oprice' => empty ( $goods_info [$i ['gs_id']] ['external_info'] ['price'] ) ? 0 : $goods_info [$i ['gs_id']] ['external_info'] ['price'],
                                        'short_intro' => $goods_info [$i ['gs_id']] ['short_intro'],
                                        'unit' => $goods_info [$i ['gs_id']] ['unit'],
                                        'selectnum' => 0 
                                );
                                if ($state ['goods_info'] ['sale_way'] == 2) {
                                    $tmp ['package_info'] ['items'] [$i ['gs_id']] ['nums'] = $goods_info [$i ['gs_id']] ['external_info'] ['nums'] > $i ['num'] ? intval ( $i ['num'] ) : $goods_info [$i ['gs_id']] ['external_info'] ['nums'];
                                    $tmp ['package_info'] ['items'] [$i ['gs_id']] ['book_status'] = $tmp ['package_info'] ['items'] [$i ['gs_id']] ['nums'] > 0 ? 'available' : 'full';
                                } else {
                                    $package_num = intval ( $i ['num'] );
                                    if ($state ['goods_info'] ['count_way'] == 1) {
                                        $roomnums = empty ( $params ['roomnums'] ) ? 1 : $params ['roomnums'];
                                        $package_num *= $room_night * $roomnums;
                                    }
                                    $tmp ['package_info'] ['items'] [$i ['gs_id']] ['nums'] = $package_num;
                                    $tmp ['package_info'] ['items'] [$i ['gs_id']] ['book_status'] = $tmp ['package_info'] ['items'] [$i ['gs_id']] ['nums'] <= $goods_info [$i ['gs_id']] ['external_info'] ['nums'] ? 'available' : 'full';
                                }
                                if ($state ['goods_info'] ['sale_way'] == 2 && ! $selected_package_price && $tmp ['package_info'] ['items'] [$i ['gs_id']] ['book_status'] == 'available') {
                                    $tmp ['package_info'] ['items'] [$i ['gs_id']] ['selectnum'] = 1;
                                    $selected_package_price = $tmp ['package_info'] ['items'] [$i ['gs_id']] ['price'];
                                }
                                if ($tmp ['package_info'] ['items'] [$i ['gs_id']] ['book_status'] != 'available' && ! $book_status) {
                                    if ($state ['goods_info'] ['sale_way'] == 1) {
                                        $book_status = 'disabled';
                                    } else if ($state ['goods_info'] ['sale_way'] == 2) {
                                        $disable_count ++;
                                    }
                                }
                            } else if ($state ['goods_info'] ['sale_way'] == 1) {
                                $break_tag = 1;
                                break;
                            }
                        }
                        if ($break_tag == 0 && ! empty ( $tmp ['package_info'] ['items'] )) {
                            $tmp ['room_info'] = $r ['room_info'];
                            $tmp ['state_info'] = $state;
                            if (! $book_status) {
                                if ($state ['goods_info'] ['sale_way'] == 2 && $disable_count >= count ( $state ['goods_info'] ['items'] )) {
                                    $book_status = 'disabled';
                                } else {
                                    $book_status = 'available';
                                }
                            }
                            $tmp ['show_price_name'] = $r ['room_info'] ['name'] . '+' . $state ['price_name'];
                            $tmp ['book_status'] = $book_status;
                            $tmp ['package_info'] ['sale_notice'] = isset ( $state ['goods_info'] ['sale_notice'] ) ? $state ['goods_info'] ['sale_notice'] : '';
                            $tmp ['package_info'] ['sale_way'] = $state ['goods_info'] ['sale_way'];
                            $tmp ['package_info'] ['total_show_price'] = $state ['goods_info'] ['sale_way'] == 2 ? $state ['total_price'] + $selected_package_price : $state ['total_price'];
                            $packages [] = $tmp;
                        }
                    }
                }
            }
        }
        return $packages;
    }
    function check_order_package($inter_id, $package_config, $packages, $params = array()) {
        $result = array (
                's' => 0 
        );
        $zero_count = 0;
        $goods_ids = array ();
        // var_dump ( $package_config );
        // var_dump ( $packages );
        // exit ();
        $room_night = get_room_night ( $params ['startdate'], $params ['enddate'], 'ceil' );
        foreach ( $packages as $goods_id => $p ) {
            if ($p ['nums'] < 0 || empty ( $package_config ['items'] [$goods_id] )) {
                $result ['errmsg'] = '所选商品数量不对';
                return $result;
            }
            if ($package_config ['sale_way'] == 2 && $p ['nums'] > $package_config ['items'] [$goods_id] ['num']) {
                $result ['errmsg'] = '所选商品数量超出限制';
                return $result;
            }
            if ($package_config ['sale_way'] == 1) {
                $package_num = $package_config ['items'] [$goods_id] ['num'];
                if ($package_config ['count_way'] == 1) {
                    $roomnums = empty ( $params ['roomnums'] ) ? 1 : $params ['roomnums'];
                    $package_num *= $room_night * $roomnums;
                }
                if ($package_num > $p ['nums']) {
                    $result ['errmsg'] = '组合商品库存不足';
                    return $result;
                }
                $packages [$goods_id] ['nums'] = $package_num;
            }
            if ($p ['nums'] == 0) {
                if ($package_config ['sale_way'] == 1) {
                    $result ['errmsg'] = '请选择商品';
                    return $result;
                } else {
                    $zero_count ++;
                }
            }
            $goods_ids [] = $goods_id;
        }
        if ($zero_count >= count ( $packages )) {
            $result ['errmsg'] = '请选择商品数量';
            return $result;
        }
        $this->load->model ( 'hotel/goods/Goods_info_model' );
        $goods_info = $this->Goods_info_model->get_goods_info ( $inter_id, $goods_ids, 1, TRUE, 'BOOK_FIELD', array (
                'valid_external' => 1 
        ) );
        if ($goods_info) {
            $total_price = 0;
            foreach ( $goods_ids as $gid ) {
                if (empty ( $goods_info [$gid] )) {
                    $result ['errmsg'] = '该商品已下架';
                    return $result;
                } else {
                    if ($goods_info [$gid] ['external_info'] ['nums'] < $packages [$gid] ['nums']) {
                        $result ['errmsg'] = $goods_info [$gid] ['external_info'] ['name'] . '库存不足';
                        return $result;
                    } else if ($goods_info [$gid] ['external_info'] ['book_status'] != 'available') {
                        $result ['errmsg'] = '无法购买' . $goods_info [$gid] ['external_info'] ['name'];
                        return $result;
                    } else {
                        $result ['data'] [$gid] = array (
                                'goods_id' => $gid,
                                'nums' => $packages [$gid] ['nums'],
                                'oprice' => $goods_info [$gid] ['price'] > 0 ? $goods_info [$gid] ['price'] : $goods_info [$gid] ['external_info'] ['price'],
                                'external_id' => $goods_info [$gid] ['external_id'],
                                'external_channel' => $goods_info [$gid] ['external_channel'],
                                'goods_name' => $goods_info [$gid] ['external_info'] ['name'],
                                'unit' => $goods_info [$gid] ['unit'] 
                        );
                        $result ['data'] [$gid] ['sale_way'] = $package_config ['sale_way'];
                        $result ['data'] [$gid] ['oprice'] *= 1;
                        $result ['data'] [$gid] ['price'] = $result ['data'] [$gid] ['sale_way'] == 1 ? 0 : $result ['data'] [$gid] ['oprice'];
                        if ($result ['data'] [$gid] ['price'] <= 0 && $package_config ['sale_way'] != 1) {
                            $result ['errmsg'] = $goods_info [$gid] ['external_info'] ['name'] . '价格错误';
                            return $result;
                        }
                        $result ['data'] [$gid] ['total_price'] = $result ['data'] [$gid] ['price'] * $result ['data'] [$gid] ['nums'];
                        $total_price += $result ['data'] [$gid] ['total_price'];
                    }
                }
            }
            $result ['s'] = 1;
            $result ['total_price'] = $total_price;
            return $result;
        } else {
            $result ['errmsg'] = '所选商品已下架';
            return $result;
        }
    }
    function create_goods_order($inter_id, $main_orderid, $openid, $goods_data) {
        $order = array ();
        $channel_order = array ();
        $this->load->library ( 'Hotel/Orderid_pump' );
        foreach ( $goods_data as $goods_id => $order_data ) {
            $order_item = array (
                    'orderid' => $main_orderid,
                    'inter_id' => $inter_id 
            );
            $order_item ['gorderid'] = Orderid_pump::pump_orderid ( 'hotel_goods' );
            $order_item ['goods_id'] = $order_data ['goods_id'];
            $order_item ['gprice'] = $order_data ['price'] * $order_data ['nums'];
            $order_item ['nums'] = $order_data ['nums'];
            $order_item ['oprice'] = $order_data ['oprice'];
            $order_item ['goods_name'] = $order_data ['goods_name'];
            $order_item ['external_channel'] = $order_data ['external_channel'];
            $order_item ['sale_way'] = $order_data ['sale_way'];
            $order_item ['gstatus'] = $this->order_status ['not_paid'];
            $order [] = $order_item;
            $order_item ['external_id'] = $order_data ['external_id'];
            $channel_order [$order_item ['external_channel']] [$order_data ['external_id']] = $order_item;
        }
        $db = $this->_load_db ( 'main' );
        if ($db->insert_batch ( self::TAB_GOODS_ORDER, $order )) {
            if (! empty ( $channel_order ['soma'] )) {
                $soma_order_result = $this->create_channel_order ( $inter_id, $main_orderid, 'soma', $openid, $channel_order ['soma'] );
                if ($soma_order_result ['s'] == 1) {
                    foreach ( $soma_order_result ['orders'] as $gid => $o ) {
                        $db->limit ( 1 );
                        $db->where ( array (
                                'inter_id' => $inter_id,
                                'orderid' => $main_orderid,
                                'gorderid' => $channel_order ['soma'] [$gid] ['gorderid'] 
                        ) );
                        $db->update ( self::TAB_GOODS_ORDER, array (
                                'external_orderid' => $o 
                        ) );
                    }
                } else {
                    return $soma_order_result;
                }
            }
            return array (
                    's' => 1 
            );
        }
        return array (
                's' => 0,
                'errmsg' => '套餐商品下单失败' 
        );
    }
    function create_channel_order($inter_id, $orderid, $channel, $openid, $order_data) {
        $order_result = array ();
        switch ($channel) {
            case 'soma' :
                $order_result = $this->order_to_soma ( $inter_id, $orderid, $openid, $order_data );
                break;
        }
        return $order_result;
    }
    function order_to_soma($inter_id, $orderid, $openid, $order_info) {
        $this->load->model ( 'soma/Sales_order_model' );
        foreach ( $order_info as $o ) {
            $goods_info [$o ['external_id']] ['qty'] = $o ['nums'];
            $goods_info [$o ['external_id']] ['price'] = $o ['oprice'];
        }
        $order_result = $this->Sales_order_model->batchHotelPackageOrder ( $inter_id, $openid, $goods_info, $orderid );
        MYLOG::w ( 'order_to_soma:batchHotelPackageOrder' . MYLOG::_SQER . $inter_id . MYLOG::_SQER . $openid . MYLOG::_SQER . json_encode ( $goods_info ) . MYLOG::_SQER . $orderid . MYLOG::_SQER . json_encode ( $order_result ), 'hotel' . DS . 'soma', '_order' );
        if (is_array ( $order_result )) {
            if (isset ( $order_result ['res'] ) && $order_result ['res'] == TRUE) {
                return array (
                        's' => 1,
                        'orders' => $order_result ['data'] 
                );
            } else {
                return array (
                        's' => 0,
                        'errmsg' => isset ( $order_result ['msg'] ) ? $order_result ['msg'] : '套票下单失败' 
                );
            }
        } else {
            return array (
                    's' => 0,
                    'errmsg' => $order_result 
            );
        }
    }
    function syn_soma_order($inter_id, $orders) {
        $this->load->model ( 'soma/Sales_order_model' );
        $result = $this->Sales_order_model->queryHotelPackageOrdersInfo ( $inter_id, array_keys ( $orders ) );
        MYLOG::w ( 'get_soma_order:queryHotelPackageOrdersInfo' . MYLOG::_SQER . $inter_id . MYLOG::_SQER . json_encode ( $orders ) . MYLOG::_SQER . json_encode ( $result ), 'hotel' . DS . 'soma', '_order' );
        $syn_result = array ();
        if (is_array ( $result )) {
            if (isset ( $result ['res'] ) && $result ['res'] == TRUE) {
                $status_reflect = $this->soma_order_status;
                $db = $this->_load_db ();
                foreach ( $orders as $external_orderid => $o ) {
                    $result ['data'] [$external_orderid] ['status'];
                    if (isset ( $result ['data'] [$external_orderid] ) && isset ( $status_reflect [$result ['data'] [$external_orderid] ['status']] )) {
                        $upstatus = $status_reflect [$result ['data'] [$external_orderid] ['status']];
                        if ($upstatus == 1) {
                            if ($result ['data'] [$external_orderid] ['consume_status'] == 22) {
                                $upstatus = 2;
                            } else if ($result ['data'] [$external_orderid] ['consume_status'] == 23) {
                                $upstatus = 3;
                            }
                        }
                        if ($upstatus == $o ['gstatus']) {
                            continue;
                        }
                        $updata = array (
                                'gstatus' => $upstatus 
                        );
                        if (in_array ( $upstatus, $this->handle_status )) {
                            $updata ['handled'] = 1;
                        }
                        $db->limit ( 1 );
                        $db->where ( array (
                                'inter_id' => $inter_id,
                                'orderid' => $o ['orderid'],
                                'external_orderid' => $external_orderid 
                        ) );
                        $db->update ( self::TAB_GOODS_ORDER, $updata );
                        $syn_result [$o ['orderid']] [$o ['gorderid']] = $updata;
                    }
                }
            }
        }
        return $syn_result;
    }
    function tran_channel_status() {
    }
    function syn_order_status($channel, $inter_id, $orders) {
        $order_results = array ();
        switch ($channel) {
            case 'soma' :
                $order_results = $this->syn_soma_order ( $inter_id, $orders );
                break;
        }
        return $order_results;
    }
    function get_order_goods($inter_id, $orderid, $selects = ' * ', $format = FALSE, $params = array()) {
        if (! empty ( $params ['main_db'] )) {
            $db = $this->_load_db ( 'main' );
        } else {
            $db = $this->_load_db ( 'read' );
        }
        $func_select = $this->get_enums ( 'goods_order_fields', $selects ) and $selects = $func_select;
        $selects .= ',orderid';
        $db->select ( $selects );
        $db->where ( 'inter_id', $inter_id );
        is_array ( $orderid ) ? $db->where_in ( 'orderid', $orderid ) : $db->where ( 'orderid', $orderid );
        $result = $db->get ( self::TAB_GOODS_ORDER )->result_array ();
        if ($result) {
            if (! empty ( $params ['get_goods_info'] )) {
                $this->load->model ( 'hotel/goods/Goods_info_model' );
                $get_external_info = $params ['get_goods_info'] == 2 ? TRUE : FALSE;
                $goods_info = $this->Goods_info_model->get_goods_info ( $inter_id, array_column ( $result, 'goods_id' ), 1, $get_external_info, 'ORDER_INFO_FIELD' );
                foreach ( $result as $k => $r ) {
                    $result [$k] ['goods_info'] = isset ( $goods_info [$r ['goods_id']] ) ? $goods_info [$r ['goods_id']] : array ();
                }
            }
            $syn_status = empty ( $params ['syn_status'] ) ? 0 : 1;
            if ($syn_status) {
                $channel_orders = array ();
                foreach ( $result as $r ) {
                    if ($r ['external_orderid'] && $r ['handled'] == 0) {
                        $channel_orders [$r ['external_channel']] [$r ['external_orderid']] = $r;
                    }
                }
                if (! empty ( $channel_orders ['soma'] )) {
                    $syn_result = $this->syn_order_status ( 'soma', $inter_id, $channel_orders ['soma'] );
                }
                if (! empty ( $syn_result )) {
                    foreach ( $result as $k => $r ) {
                        if (isset ( $syn_result [$r ['orderid']] [$r ['gorderid']] )) {
                            $result [$k] = array_merge ( $r, $syn_result [$r ['orderid']] [$r ['gorderid']] );
                        }
                    }
                }
            }
            if ($format) {
                $data = array ();
                foreach ( $result as $r ) {
                    $data [$r ['orderid']] [] = $r;
                }
                return $data;
            }
        }
        return $result;
    }
    function order_pay($order) {
        $success = 0;
        if (! empty ( $order ['goods_details'] )) {
            $channel_order = array ();
            $success_order = array ();
            $fail_order = array ();
            foreach ( $order ['goods_details'] as $gorder ) {
                if ($gorder ['gstatus'] == $this->order_status ['not_paid']) {
                    $channel_order [$gorder ['external_channel']] [$gorder ['gorderid']] = $gorder;
                }
            }
            if (! empty ( $channel_order ['soma'] )) {
                $paid_result = $this->soma_order_pay ( $order ['inter_id'], $order ['orderid'], array_column ( $channel_order ['soma'], 'external_orderid' ) );
                if ($paid_result ['s'] == 1) {
                    $success_order = array_merge ( $success_order, array_keys ( $channel_order ['soma'] ) );
                } else {
                    $fail_order = array_merge ( $fail_order, array_keys ( $channel_order ['soma'] ) );
                }
            }
            if (! empty ( $channel_order ['hotel'] )) {
                $success_order = array_merge ( $success_order, array_keys ( $channel_order ['hotel'] ) );
            }
            $db = $this->_load_db ( 'main' );
            if ($success_order) {
                $success = 1;
                $db->where ( array (
                        'inter_id' => $order ['inter_id'],
                        'orderid' => $order ['orderid'],
                        'gstatus' => $this->order_status ['not_paid'] 
                ) );
                $db->where_in ( 'gorderid', $success_order );
                $db->update ( self::TAB_GOODS_ORDER, array (
                        'gstatus' => $this->order_status ['ensure'] 
                ) );
            }
            if ($fail_order) {
                $success and $success = 2;
            }
            return array (
                    's' => $success,
                    'success_orders' => $success_order,
                    'fail_order' => $fail_order 
            );
        }
        return array (
                's' => $success,
                'errmsg' => '无套餐订单' 
        );
    }
    function soma_order_pay($inter_id, $main_orderid, $orderids) {
        $this->load->model ( 'soma/Sales_order_model' );
        $result = $this->Sales_order_model->batchHotelPackageOrderPay ( $inter_id, $orderids, $main_orderid );
        MYLOG::w ( 'pay_soma_order:batchHotelPackageOrderPay' . MYLOG::_SQER . $inter_id . MYLOG::_SQER . $main_orderid . MYLOG::_SQER . json_encode ( $orderids ) . MYLOG::_SQER . json_encode ( $result ), 'hotel' . DS . 'soma', '_order' );
        if (is_array ( $result )) {
            if (isset ( $result ['res'] ) && $result ['res'] == TRUE) {
                return array (
                        's' => 1,
                        'result' => $result ['data'] 
                );
            } else {
                return array (
                        's' => 0,
                        'errmsg' => isset ( $result ['msg'] ) ? $result ['msg'] : '支付失败' 
                );
            }
        } else {
            return array (
                    's' => 0,
                    'errmsg' => $result 
            );
        }
        return $result;
    }
}