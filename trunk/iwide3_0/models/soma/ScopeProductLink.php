<?php

namespace App\models\soma;

/**
 * Class ScopeProductLink
 * @package App\models\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class ScopeProductLink extends \MY_Model_Soma
{
    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_primary_key()
    {
        return 'id';
    }

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_name()
    {
        return 'soma_scope_product_link';
    }

    /**
     * @param $scopeId
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getByScopeId($scopeId)
    {
        $rows = $this->get(
            [
                'scope_id',
                'deleted_at'
            ],
            [
                $scopeId,
                '0000-00-00 00:00:00'
            ],
            '*',
            [
                'limit'=>1000
            ]
        );

        return $rows;
    }


    /**
     * @param $id
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getById($id)
    {
        $rows = $this->get(
            [
                'id',
                'deleted_at'
            ],
            [
                $id,
                '0000-00-00 00:00:00'
            ]
        );

        if (isset($rows[0])) {
            return $rows[0];
        }
        return array();
    }

    /**
     * 更新一条数据
     * @param $id
     * @param $updateProductLinkData
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function scopeProductLinkUpdate( $id, $updateProductLinkData )
    {
        $this->soma_db_conn->where('id',$id)->limit(1)->update($this->table_name(), $updateProductLinkData);
        if( $this->soma_db_conn->affected_rows() > 0 )
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 保存数据
     * @param $addProductLinkData
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function scopeProductLinkSave( $addProductLinkData )
    {
        $this->soma_db_conn->insert_batch($this->table_name(), $addProductLinkData);
        if( $this->soma_db_conn->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 这里的删除不是真正的删除，只是更新了删除字段
     * @param $deleteProductLinkids
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function scopeProductLinkDelete( $deleteProductLinkids )
    {
        $updateNum = count( $deleteProductLinkids );
        $updateData = array('deleted_at'=>date('Y-m-d H:i:s'));
        $this->soma_db_conn->where_in('id',$deleteProductLinkids)->limit($updateNum)->update($this->table_name(), $updateData);
        if( $this->soma_db_conn->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}