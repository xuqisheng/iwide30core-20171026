<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class MY_Model_Hotel extends MY_Model {
    const DB_RW = 'iwide_rw';
    const DB_READ = 'iwide_r1';
    const TAB_GOODS_ORDER = 'hotel_order_goods';
    const TAB_GOODS = 'hotel_goods';
    protected function _load_db($type = 'main') {
        switch ($type) {
            case 'read' :
                return $this->_db ( self::DB_READ );
                break;
            case 'write' :
                return $this->_db ( self::DB_RW );
                break;
            default :
                return $this->db;
                break;
        }
    }
    protected function get_enums($type, $key = NULL, $value = NULL) {
        $data = NULL;
        switch ($type) {
            default :
                $data = isset ( $this->$type ) ? $this->$type : NULL;
                break;
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
}

