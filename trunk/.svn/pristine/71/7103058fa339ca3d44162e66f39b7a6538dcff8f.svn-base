<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends MY_Model_Soma {
    

    const STATUS_TRUE  = 1;
    const STATUS_FALSE = 2;
    
    const ACT_TYPE_GROUPON = 1;
    const ACT_TYPE_KILLSEC = 2;

    public function act_type_status()
    {
        return array(
            self::ACT_TYPE_GROUPON => '拼团',
            self::ACT_TYPE_KILLSEC => '秒杀',
        );
    }

    //添加一个活动
    public function _activity_save( $post, $inter_id=NULL )
    {

        try {

            $this->_shard_db($inter_id)->trans_begin ();

            $product_id = isset( $post['product_id'] ) ? $post['product_id'] : '';
            $act_name = isset( $post['act_name'] ) ? $post['act_name'] : '';
            $status = isset( $post['status'] ) ? $post['status'] : '';
            $act_type = isset( $post['act_type'] ) && !empty( $post['act_type'] ) ? $post['act_type'] : NULl;

            //添加活动主单内容
            //添加活动类型和活动名称到activity_idx表
            $data = array();
            $data['act_name'] = $act_name;
            $data['act_type'] = $act_type;
            $data['status'] = $status;

            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_idx');
            $this->_shard_db( $inter_id )->insert( $table, $data );
            $act_id = $this->_shard_db( $inter_id )->insert_id();

            //添加活动价格表内容
            $data = array();
            $data['act_id'] = $act_id;
            $data['act_name'] = $act_name;
            $data['product_id'] = $product_id;
            if( $act_type == self::ACT_TYPE_GROUPON ){
            	$data['price'] = isset( $post['group_price'] ) ? $post['group_price'] : '';
            }elseif( $act_type == self::ACT_TYPE_KILLSEC ){

            	$data['price'] = isset( $post['killsec_price'] ) ? $post['killsec_price'] : '';
            }else{
            	$data['price'] = NULL;
            }

            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_product_price');
            $this->_shard_db( $inter_id )->insert( $table, $data );

            $pk = $this->table_primary_key();
            $post[$pk] = $act_id;

            //保存秒杀内容
            $result = $this->_m_save($post);
            
            $this->_shard_db($inter_id)->trans_complete();
            
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
            
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }

        } catch (Exception $e) {
             
            return FALSE;
        }
    }

    //修改一个活动
    public function _activity_edit( $post, $inter_id=NULL )
    {
    
        try {
    
            $this->_shard_db($inter_id)->trans_begin ();
            
            $pk = $this->table_primary_key();

            $product_id = isset( $post['product_id'] ) ? $post['product_id'] : '';
            $act_name = isset( $post['act_name'] ) ? $post['act_name'] : '';
            $status = isset( $post['status'] ) ? $post['status'] : '';
    
            //添加活动主单内容
            //添加活动类型和活动名称到activity_idx表
            $data = array();
            $data['act_name'] = $act_name;
            $data['status'] = $status;
            
            $where = array();
            $where[$pk] = $post[$pk];
    
            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_idx');
            $this->_shard_db( $inter_id )->where( $where )->update( $table, $data );
    
            //添加活动价格表内容
            $data = array();
            $data['act_name'] = $act_name;
            $data['product_id'] = $product_id;

            $act_type = isset( $post['act_type'] ) && !empty( $post['act_type'] ) ? $post['act_type'] : NULL;
            if( $act_type == self::ACT_TYPE_GROUPON ){
            	$data['price'] = isset( $post['group_price'] ) ? $post['group_price'] : '';
            }elseif( $act_type == self::ACT_TYPE_KILLSEC ){

            	$data['price'] = isset( $post['killsec_price'] ) ? $post['killsec_price'] : '';
            }else{
            	$data['price'] = NULL;
            }
            
            $where = array();
            $where[$pk] = $post[$pk];
    
            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_product_price');
            $this->_shard_db( $inter_id )->where( $where )->update( $table, $data );
    
            //保存团购内容
            $result = $this->load( $post[$pk] )->m_sets( $post )->m_save();
    
            $this->_shard_db($inter_id)->trans_complete();
    
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
    
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
    
        } catch (Exception $e) {
             
            return FALSE;
        }
    }
    
}
