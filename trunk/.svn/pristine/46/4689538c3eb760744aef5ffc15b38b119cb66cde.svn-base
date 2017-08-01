<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/4/6
 * Time: 16:28
 */
class Sales_order_idx_model extends MY_Model_Soma
{
    /**
     * @return string
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function table_primary_key()
    {
        return 'order_id';
    }

    /**
     * @param string $business
     * @param null $inter_id
     * @return string
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function table_name($business = 'package', $inter_id = null)
    {
        return $this->_shard_table('soma_sales_order_idx', $inter_id);
    }

    /**
     * @param array $data
     * @return object
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function save(Array $data)
    {
        return $this->db_conn->insert($this->table_name(), $data);
    }


    /**
     * @param $order_id
     * @param string $key
     * @return array|mixed|string
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function get_extra_value( $order_id ,$key = ''){
        $result= $this->_db()->get_where('soma_sales_order_idx', array($this->table_primary_key() => $order_id) )
            ->result_array();
        if(empty($result))
            return array();
        else{
            $return = json_decode($result[0]['extra'],true);
        }

        if(empty($key)){
            return  empty($return) ? array(): $return;
        }else{

            if(isset($return[$key])){
                return  $return[$key];
            }else{
                return '';
            }
        }


    }

    /**
     * @param $order_id
     * @param $key
     * @param $value
     * @return bool
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function update_extra($order_id, $key, $value){
        $extra = $this->get_extra_value($order_id);
        $extra[$key] = $value;

        $where = array(
            $this->table_primary_key() => $order_id
        );

        $data = json_encode($extra);

        $this->_db( )
            ->where( $where )
            ->update( $this->table_name(), $data );

        if( $this->_db( )->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }

    }


}