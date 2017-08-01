<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sales_order_link_model
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Product_package_link_model extends MY_Model_Soma
{

    public function table_primary_key()
    {
        return 'id';
    }

    public function table_name($inter_id = null)
    {
        return $this->_shard_table('soma_product_package_link', $inter_id);
    }

    /**
     * 批量插入组合商品信息
     *
     * @param      array  $data   The combine product data
     *
     * @return     bool   TRUE if save combine product data success, FALSE otherwise.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function saveCombineProductInfo($data)
    {
        $inter_id   = $data[0]['inter_id'];
        $parent_pid = $data[0]['parent_pid'];
        $tb_name    = $this->table_name($inter_id);
        $pk         = $this->table_primary_key();

        $ids = $insert = $update = array();
        foreach($data as $row)
        {
            if(isset($row[$pk]) && $row[$pk] > 0) {
                $ids[]    = $row[$pk];
                $update[] = $row;
            } else {
                unset($row[$pk]);
                $insert[] = $row;
            }
        }

        $delete_res = $insert_res = $update_res = true;
        if(!empty($ids))
        {
            $delete_res = $this->_shard_db($inter_id)
                ->where('parent_pid', $parent_pid)->where_not_in($pk, $ids)->delete($tb_name);
        }
        else
        {
            $delete_res = $this->_shard_db($inter_id)
                ->where('parent_pid', $parent_pid)->delete($tb_name);
        }

        if(!empty($insert))
        {
            $insert_res = $this->_shard_db($inter_id)->insert_batch($tb_name, $insert);
            if($insert_res && $insert_res == count($insert))
            {
                $insert_res = true;
            }
            else
            {
                $insert_res = false;
            }
        }

        if(!empty($update))
        {
            $update_res = $this->_shard_db($inter_id)->update_batch($tb_name, $update, $pk);
            if($update_res && $update_res == count($update))
            {
                $update_res = true;
            }
            else
            {
                $update_res = false;
            }
        }

        return ($delete_res && $insert_res && $update_res);
    }

    /**
     * Gets the combine child product list.
     *
     * @param      <type>  $parent_pid  The parent identifier
     * @param      <type>  $inter_id   The inter identifier
     */
    public function getCombineChildProductList($parent_pid, $inter_id = null)
    {
        return $this->_shard_db($inter_id)
            ->get_where($this->table_name(), array('parent_pid'=>$parent_pid))->result_array();
    }
}