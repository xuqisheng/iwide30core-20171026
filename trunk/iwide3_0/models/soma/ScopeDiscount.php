<?php

namespace App\models\soma;

/**
 * Class ScopeDiscount
 * @package App\models\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class ScopeDiscount extends \MY_Model_Soma
{
    /**
     * 可用状态
     */
    const STATUS_OK = 1;
    /**
     * 停用状态
     */
    const STATUS_STOP = 2;

    /**
     * 价格范围 社区
     */
    const SCOPE_SOCIAL = 1;

    /**
     * 是否限购，1为不限购，2为限购，暂时只用在后台页面判断
     * 实际是否限购以 limit_num 的值为准
     */
    const BUY_UNLIMIT   = 1;
    const BUY_LIMIT     = 2;

    /**
     * @var array 后台价格添加的商品规格信息
     */
    public $addProductLinkData = array();

    /**
     * @var array 后台价格更新的商品规格信息
     */
    public $updateProductLinkData = array();

    /**
     * @var array 后台价格删除的价格配置商品ID列表
     */
    public $deleteProductLinkIds = array();

    /**
     * 获取类型
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getScope()
    {
        return array(
            self::SCOPE_SOCIAL => '社群客',
        );
    }

    /**
     * 获取状态
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getStatus()
    {
        return array(
            self::STATUS_OK     => '有效',
            self::STATUS_STOP   => '无效',
        );
    }

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
        return $this->_shard_table('soma_scope_discount');
    }


    /**
     * @param $interID
     * @param $scope
     * @param int $page
     * @param int $limit
     * @param string $orderBy
     * @return array|string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getAvailableList($interID, $scope, $page = 1, $limit = 10, $orderBy = 'id desc')
    {
        $currentTime = date('Y-m-d H:i:s');
        return $this->get(
            array(
                'scope',
                'status',
                'inter_id',
                'start_time <',
                'end_time >'
            ),
            array(
                $scope,
                self::STATUS_OK,
                $interID,
                $currentTime,
                $currentTime
            ),
            '*',
            array(
                'limit' => $limit,
                'offset' => ($page - 1) * $limit,
                'orderBy' => $orderBy,
                'debug' => false
            )
        );
    }

    /**
     *
     * 返回可用的
     * @param $ids
     * @return array|string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getAvailableByIds($ids)
    {
        if(empty($ids)) {
            return [];
        }
        $currentTime = date('Y-m-d H:i:s');
        return $this->get(
            array(
                'status',
                'id',
                'start_time <',
                'end_time >'
            ),
            array(
                ScopeDiscount::STATUS_OK,
                $ids,
                $currentTime,
                $currentTime
            ),
            '*',
            array(
                'limit' => count($ids),
                'debug' => false
            )
        );
    }

    /**
     * 后台显示列表
     * @param $interID
     * @param int $page
     * @param int $limit
     * @param string $orderBy
     * @return array|string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getScopeProductList($interID, $page = 1, $limit = 10, $orderBy = 'id desc')
    {
        return $this->get(
            array(
                'inter_id',
            ),
            array(
                $interID,
            ),
            '*',
            array(
                'limit' => $limit,
                'offset' => ($page - 1) * $limit,
                'orderBy' => $orderBy,
                'debug' => false
            )
        );
    }

    /**
     * 搜索
     * @param $interID
     * @param $search
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function searchScopeProductList($interID, $search)
    {
        $statusSearch = $search;
        if( strpos($search,'无') !==FALSE )
        {
            $statusSearch = 2;
        } elseif( strpos($search,'有') !==FALSE ){
            $statusSearch = 1;
        }

        $table = $this->table_name();
        $result = $this->soma_db_conn_read
                    ->where('inter_id',$interID)
                    ->where("(
                                `id` LIKE '%{$search}%' ESCAPE '!' 
                                OR `name` LIKE '%{$search}%' ESCAPE '!'
                                OR `status` LIKE '%{$statusSearch}%' ESCAPE '!'
                                OR `start_time` LIKE binary '%{$search}%' ESCAPE '!'
                                OR `end_time` LIKE binary '%{$search}%' ESCAPE '!'
                            )")
                    ->get($table)
                    ->result_array();
        //echo $this->soma_db_conn_read->last_query();die;
        return $result;
    }

    /**
     * 获取公众号下面有多少条记录
     * @param $interID
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getScopeProductTotal($interID)
    {
        $table = $this->table_name();
        return $this->soma_db_conn_read->where('inter_id',$interID)->from($table)->count_all_results();
    }

    /**
     * 保存价格配置
     * @param $scopeData
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function scopeDiscountSave( $scopeData )
    {
        $this->soma_db_conn->trans_begin();

        try {
            $this->soma_db_conn->insert($this->table_name(), $scopeData);
            $id = $this->soma_db_conn->insert_id();

            $ScopeProductLinkModel = new ScopeProductLink();
            $addRes = TRUE;

            $addProductLinkData = $this->addProductLinkData;
            if( $addProductLinkData )
            {
                foreach( $addProductLinkData as $k=>$v )
                {
                    $addProductLinkData[$k]['scope_id'] = $id;
                }
                $addRes = $ScopeProductLinkModel->scopeProductLinkSave( $addProductLinkData );

            }
//            var_dump( $addProductLinkData );die;

            if( $id && $addRes )
            {
                $this->soma_db_conn->trans_commit();
                return TRUE;
            } else {
                $this->soma_db_conn->trans_rollback();
                return FALSE;
            }
        } catch (Exception $e) {
            $this->soma_db_conn->trans_rollback();
            return FALSE;
        }

    }

    /**
     * 更新价格配置
     * @param $id
     * @param $scopeData
     * @param $interId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function scopeDiscountUpdate( $id, $scopeData, $interId )
    {
        $this->soma_db_conn->trans_begin();

        try {
            $this->soma_db_conn->where('id',$id)->where('inter_id',$interId)->update($this->table_name(), $scopeData);
            if( $this->soma_db_conn->affected_rows() > 0 )
            {
                $res = TRUE;
            } else {
                $res = FALSE;
            }

            $ScopeProductLinkModel = new ScopeProductLink();
            $addRes = $updateRes = $deleteRes = TRUE;

            $addProductLinkData = $this->addProductLinkData;
            if( $addProductLinkData )
            {
                $addRes = $ScopeProductLinkModel->scopeProductLinkSave( $addProductLinkData );
            }

            $updateProductLinkData = $this->updateProductLinkData;
            if( $updateProductLinkData )
            {
                foreach( $updateProductLinkData as $k=>$v )
                {
                    $updateRes = $ScopeProductLinkModel->scopeProductLinkUpdate( $v['id'], $v );
                    if( !$updateRes )
                    {
                        break;
                    }
                }
            }

            $deleteProductLinkIds = $this->deleteProductLinkIds;
            if( $deleteProductLinkIds )
            {
                $deleteRes = $ScopeProductLinkModel->scopeProductLinkDelete( $deleteProductLinkIds );
            }

//var_dump( $res, $addRes, $updateRes, $deleteRes );die;
            if( $res && $addRes && $updateRes && $deleteRes )
            {
                $this->soma_db_conn->trans_commit();
                return TRUE;
            } else {
                $this->soma_db_conn->trans_rollback();
                return FALSE;
            }
        } catch (Exception $e) {
            $this->soma_db_conn->trans_rollback();
            return FALSE;
        }
    }

    //计划任务，修改无效状态
    public function updateStatus($inter_id = NULL)
    {
        if (!$inter_id) {
            return FALSE;
        }

        $time = date('Y-m-d H:i:s');
        $table  = $this->table_name();
        return $this->soma_db_conn
                    ->where('inter_id', $inter_id)
                    ->where('status', self::STATUS_OK)
                    ->where('end_time < ', $time)
                    ->update( $table, array('status' => self::STATUS_STOP) );
    }
}