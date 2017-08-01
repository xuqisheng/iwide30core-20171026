<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 统一分配ID工具类
 * @author libinyan
 */
class Ticket_center_model extends MY_Model {

    const ORDER_TYPE_DEFAULT = 'default';
    const ORDER_TYPE_VIRTUAL = 'virtual';
    const ORDER_TYPE_PACKAGE = 'package';
    const ORDER_TYPE_DINNER  = 'dinner';
    const ORDER_TYPE_MEETING = 'meeting';

    /**
     * 业务标识编号标识
     * @return multitype:string
     */
    public function get_soma_business_types()
    {
        return array(
            self::ORDER_TYPE_DEFAULT => '普通订单',
            self::ORDER_TYPE_VIRTUAL => '虚拟商品',
            self::ORDER_TYPE_PACKAGE => '套票订单',
            self::ORDER_TYPE_DINNER  => '订餐订单',
            self::ORDER_TYPE_MEETING => '会议预定',
        );
    }

    /**
     * 根据业务标识编号获取不同 标识
     * @param string $business
     * @return Ambigous <string>|Ambigous <multitype:string, multitype:string >
     */
    public function get_order_type_label( $business= NULL)
    {
        $types= $this->get_soma_business_types();
        if( $business && key_exists( $business, $types ) ){
            return $types[$business];
        } else {
            return $types;
        }
    }

    /**
     * 根据业务类型获取订单ID、消费单ID、赠送单ID、资产ID
     * @param string $business
     * @param string $segments
     * @throws Exception
     * @return string|boolean
     */
    public function get_order_id( $business, $segments)
    {
        $types= $this->get_soma_business_types();
        if( $business && key_exists( $business, $types ) ){
            if( !in_array($segments, array('order', 'consumer', 'gift', 'asset', 'address', 'order_product_record') ) ){
                //必须正确请求对应单号，不能混淆
                throw new Exception('Error segments values when request order Increment_id.');
            } else {
                $tablename= 'ticket_soma_'. $segments;
                $sql= "REPLACE INTO `{$tablename}` (`stub`) VALUES ('{$business}');";
                $this->_db('ticket_center')->query($sql);
                $return_key= 'increment_id';
                $sql= "SELECT LAST_INSERT_ID() as {$return_key};";
                $result= $this->_db('ticket_center')->query($sql)->result_array();
                $id= $result[0][$return_key];
                //if($id>4000000001) {
                    /** @todo to notify administrator. **/
                //} else 
                return (string) $id;
            }
        } else {
            return FALSE;
        }
    }
    
    /**
     * 根据业务类型获取产品ID、分类ID
     * @param string $business
     * @param string $segments
     * @throws Exception
     * @return string|boolean
     */ 
    public function get_catalog_id($business, $segments)
    {
        $types= $this->get_soma_business_types();
        if( $business && key_exists( $business, $types ) ){
            if( !in_array($segments, array('product', 'category') ) ){
                //必须正确请求对应单号，不能混淆
                throw new Exception('Error segments values when request catalog ID.');
            } else {
                $tablename= 'ticket_soma_'. $segments;
                $sql= "REPLACE INTO `{$tablename}` (`stub`) VALUES ('{$business}');";
                $this->_db('ticket_center')->query($sql);
                $return_key= 'increment_id';
                $sql= "SELECT LAST_INSERT_ID() as {$return_key};";
                $result= $this->_db('ticket_center')->query($sql)->result_array();
                $id= $result[0][$return_key];
                //if($id>4000000001) {
                    /** @todo to notify administrator. **/
                //} else 
                return (string) $id;
            }
        } else {
            return FALSE;
        }
    }

    public function get_increment_id_address($business)
    {
        return $this->get_order_id($business, 'address');
    }
    public function get_increment_id_asset($business)
    {
        return $this->get_order_id($business, 'asset');
    }
    
    public function get_increment_id_order($business)
    {
        return $this->get_order_id($business, 'order');
    }
    public function get_increment_id_order_product_record($business)
    {
        return $this->get_order_id($business, 'order_product_record');
    }
    public function get_increment_id_consumer($business)
    {
        return $this->get_order_id($business, 'consumer');
    }
    public function get_increment_id_gift($business)
    {
        return $this->get_order_id($business, 'gift');
    }
    

    public function get_id_product($business)
    {
        return $this->get_catalog_id($business, 'product');
    }
    public function get_id_category($business)
    {
        return $this->get_catalog_id($business, 'category');
    }
    
    
}
