<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Goods_info_model extends MY_Model_Hotel {
    function __construct() {
        parent::__construct ();
    }
    public $goods_field_select = array (
            'STATE_FIELD' => ' `price`, `short_intro`, `unit`, `sort`, `status`',
            'ORDER_INFO_FIELD' => ' `short_intro`, `unit`',
            'BOOK_FIELD' => ' `price`,`unit`' 
    );
    public $soma_status = array (
            '1' => 1,
            '2' => 3,
            '3' => 2 
    );
    public function get_goods_info($inter_id, $goods_id, $status = NULL, $get_external_info = TRUE, $selects = '*', $params = array()) {
        $func_select = $this->get_enums ( 'goods_field_select', $selects ) and $selects = $func_select;
        $selects .= ',external_channel,goods_id,external_id';
        $db = $this->_load_db ( 'read' );
        $db->select ( $selects );
        $db->where ( 'inter_id', $inter_id );
        is_null ( $status ) ? $db->where_in ( 'status', array (
                1,
                2 
        ) ) : $db->where ( 'status', $status );
        is_array ( $goods_id ) ? $db->where_in ( 'goods_id', $goods_id ) : $db->where ( 'goods_id', $goods_id );
        $db->order_by ( 'sort desc ' );
        $goods = $db->get ( self::TAB_GOODS )->result_array ();
        if ($goods) {
            $goods = array_column ( $goods, NULL, 'goods_id' );
            if ($get_external_info) {
                $source_channel = array ();
                foreach ( $goods as $g ) {
                    if ($g ['external_channel']) {
                        $source_channel [$g ['external_channel']] [$g ['goods_id']] = $g ['external_id'];
                    }
                }
                if (! empty ( $source_channel ['soma'] )) {
                    $external_info = $this->get_external_info ( $inter_id, $source_channel ['soma'], 'soma' );
                    if ($external_info) {
                        foreach ( $source_channel ['soma'] as $gid => $extern_id ) {
                            if (isset ( $external_info [$extern_id] )) {
                                if (! empty ( $params ['valid_external'] ) && $external_info [$extern_id] ['status'] != 1) {
                                    unset ( $goods [$gid] );
                                } else {
                                    $goods [$gid] ['external_info'] = $external_info [$extern_id];
                                }
                            } else if (! empty ( $params ['valid_external'] )) {
                                unset ( $goods [$gid] );
                            }
                        }
                    } else if (! empty ( $params ['valid_external'] )) {
                        $goods = array_diff_key ( $goods, $source_channel ['soma'] );
                    }
                }
            }
            return is_array ( $goods_id ) ? $goods : $goods [$goods_id];
        }
        return $goods;
    }
    public function get_external_info($inter_id, $goods_id, $channel, $params = array()) {
        $data = array ();
        switch ($channel) {
            case 'soma' :
                $goods = $this->get_soma_goods ( $inter_id, $goods_id, 'in', array (
                        'check_data' => 1 
                ) );
                if ($goods) {
                    $soma_status = $this->get_enums ( 'soma_status' );
                    foreach ( $goods as $g ) {
                        $data [$g ['product_id']] ['name'] = $g ['name'];
                        $data [$g ['product_id']] ['price'] = $g ['price_package'];
                        $data [$g ['product_id']] ['nums'] = $g ['stock'];
                        if ($data [$g ['product_id']] ['nums'] > 0) {
                            $data [$g ['product_id']] ['book_status'] = 'available';
                        }
                        $data [$g ['product_id']] ['intro_img'] = $g ['face_img'];
                        $data [$g ['product_id']] ['details'] = $g ['img_detail'];
                        $data [$g ['product_id']] ['status'] = $soma_status [$g ['status']];
                    }
                }
                break;
        }
        return $data;
    }
    public function get_soma_goods($inter_id, $goods_id, $id_filter = 'in', $params = array()) {
        $this->load->model ( 'soma/Product_package_model' );
        is_array ( $goods_id ) or $goods_id = array (
                $goods_id 
        );
        $soma_goods_list = $this->Product_package_model->getHotelPackageProductList ( $inter_id, array (
                $id_filter => $goods_id 
        ) );
        return empty ( $params ['check_data'] ) ? $soma_goods_list : (empty ( $soma_goods_list ['data'] ) ? array () : $soma_goods_list ['data']);
    }
    public function create_hg($data) {
        $db = $this->_load_db ( 'write' );
        
        return $db->insert ( self::TAB_GOODS, $data );
    }
    public function get_list($condit = array()) {
        $this->load->model ( 'soma/Product_package_model' );
        // 商品状态
        if (! empty ( $condit ['status'] ) && $condit ['status'] == 'normal') {
            $params ['status'] = Product_package_model::STATUS_ACTIVE;
            $status = array (
                    1 
            );
        } else {
            $params ['status'] = null;
            $status = null;
        }
        // 同步商城商品
        $this->synchro_soma ( $condit ['inter_id'] );
        
        $hotel_goods_list = $this->hotel_goods_list ( $condit ['inter_id'], '*', $status );
        $hotel_good_ids = array ();
        $goods_list = array ();
        foreach ( $hotel_goods_list as $hotel_good ) {
            $hotel_good_ids [] = $hotel_good ['external_id'];
            $goods_list [$hotel_good ['external_id']] = $hotel_good;
        }
        if (empty ( $hotel_good_ids ))
            return false;
            
            // 加载商城的获取商品接口
            // 参数 inter_id,gs_id,size,page
        if (isset ( $condit ['size'] ) && isset ( $condit ['page'] )) {
            $params ['size'] = intval ( $condit ['size'] );
            $params ['page'] = intval ( $condit ['page'] );
            $params ['is_count'] = true;
        } else {
            $params ['size'] = null;
            $params ['page'] = null;
            $params ['is_count'] = false;
        }
        // if(isset($condit['gs_id'])){
        // $params['gs_id'] = $condit['gs_id'];
        // }else{
        // $params['gs_id'] = null;
        // }
        $soma_goods_list = $this->Product_package_model->getHotelPackageProductList ( $condit ['inter_id'], array (
                'in' => $hotel_good_ids 
        ), $params ['page'], $params ['size'], $params ['is_count'], $params ['status'] );
        $return_list = array ();
        $goods_type = $this->Product_package_model->get_goods_type_label ();
        $status_des = $this->Product_package_model->get_status_label ();
        foreach ( $soma_goods_list ['data'] as $soma_good ) {
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] = $goods_list [$soma_good ['product_id']];
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['name'] = $soma_good ['name']; // 名称
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['soma_status'] = $status_des [$soma_good ['status']]; // 商城状态
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['stock'] = $soma_good ['stock']; // 库存
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['price_package'] = $soma_good ['price_package']; // 商城价
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['goods_type'] = $goods_type [$soma_good ['goods_type']]; // 商品类型
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['validity_date'] = $soma_good ['validity_date']; // 上架时间
            $return_list [$goods_list [$soma_good ['product_id']] ['goods_id']] ['un_validity_date'] = $soma_good ['un_validity_date']; // 下架时间
        }
        
        $return = array (
                'items' => $return_list 
        );
        if (isset ( $params ['is_count'] ) && $params ['is_count']) {
            $return ['count'] = $soma_goods_list ['total'];
        }
        return $return;
    }
    public function synchro_soma($inter_id) {
        $hotel_goods_list = $this->hotel_goods_list ( $inter_id, 'external_id' );
        $somaids = array ();
        foreach ( $hotel_goods_list as $v ) {
            $somaids [] = $v ['external_id'];
        }
        // 加载商城的获取商品接口
        $this->load->model ( 'soma/Product_package_model' );
        $soma_goods_list = $this->Product_package_model->getHotelPackageProductList ( $inter_id, array (
                'not_in' => $somaids 
        ) );
        $newdata = array (
                'inter_id' => $inter_id,
                'create_time' => date ( 'Y-m-d H:i:s' ) 
        );
        foreach ( $soma_goods_list ['data'] as $gs_id ) { // 订房库未保存的商品
            $newdata ['external_id'] = $gs_id ['product_id'];
            $newdata ['price'] = $gs_id ['price_package'];
            $newdata ['unit'] = '份';
            $newdata ['external_channel'] = 'soma';
            $this->create_hg ( $newdata );
            $somaids [] = $gs_id ['price_package'];
        }
        
        return $somaids;
    }
    public function hotel_goods_list($inter_id, $select = '*', $status = null, $condit = array()) {
        $db_read = $this->_load_db ( 'read' );
        $db_read->select ( $select );
        $db_read->where ( 'inter_id', $inter_id );
        if ($status !== null && is_array ( $status )) {
            $db_read->where_in ( 'status', $status );
        } else {
            $db_read->where_in ( 'status', array (
                    1,
                    2 
            ) );
        }
        $db_read->from ( self::TAB_GOODS );
        
        $hotel_goods_list = $db_read->get ()->result_array ();
        return $hotel_goods_list;
    }
    public function get_row($inter_id, $id, $condit = array()) {
        $db_read = $this->_load_db ( 'read' );
        
        $db_read->select ( '*' );
        
        $db_read->where ( 'inter_id', $inter_id );
        $db_read->where ( 'id', $id );
        
        if (isset ( $condit ['status'] )) {
            $db_read->where ( 'status', $condit ['status'] );
        }
        
        $db_read->from ( self::TAB_GOODS );
        
        return $db_read->get ()->row_array ();
    }
    
    // 更新
    public function update_data($inter_id, $id, $data) {
        $db = $this->_load_db ( 'write' );
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'goods_id', $id );
        $this->load->helper ( 'array' );
        $data = elements ( array (
                'price',
                'unit',
                'short_intro',
                'sort',
                'status' 
        ), $data );
        return $db->update ( self::TAB_GOODS, $data );
    }
}