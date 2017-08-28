<?php
namespace App\services\soma;

use App\libraries\Support\Log;
use App\libraries\Support\Tool;
use App\services\BaseService;
use App\services\Result;

/**
 * Class OrderService
 * @package App\services\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class IdistributeService extends BaseService
{
    /**
     * 获取服务实例方法
     * @return OrderService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     * 获取用户订购产品信息
     *
     * @param      string  $interId    公众号
     * @param      string  $openid     用户openid
     * @param      string  $productId  产品id
     * @param      string  $orderId    订单id
     *
     * @return     array   用户订购产品信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getUserOrderProductInfo($interId, $openid, $productId, $orderId = null)
    {
        $this->buildShardConfig($interId);

        $this->getCI()->load->model('soma/Sales_order_model');
        $this->getCI()->load->model('soma/Sales_item_package_model');
        $orderModel = $this->getCI()->Sales_order_model;
        $itemModel  = $this->getCI()->Sales_item_package_model;

        $filter = [
            'inter_id'   => $interId,
            'openid'     => $openid,
            'product_id' => $productId,
        ];

        if (!empty($orderId)) {
            $filter['order_id'] = $orderId;
        }

        $itemData = $itemModel->get(array_keys($filter), array_values($filter), '*', ['limit' => 1000]);
        $itemOrders = [];
        foreach ($itemData as $row) {
            $itemOrders[] = $row['order_id'];
        }
        
        if (empty($itemOrders)) {
            $fmtData = [];
            $productId = is_array($productId) ? $productId : [$productId];
            foreach ($productId as $id) {
                $fmtData[$id] = 0;
            }
            $result = new Result(Result::STATUS_OK, '', $fmtData);
            return $result->toArray();
        }

        $orderFitler    = [
            'order_id' => $itemOrders,
            'status'   => \Sales_order_model::STATUS_PAYMENT
        ];

        $orderData = $orderModel->get(array_keys($orderFitler), array_values($orderFitler), '*', ['limit' => 1000]);
        $paymentOrderIds = [];
        foreach ($orderData as $row) {
            $paymentOrderIds[] = $row['order_id'];
        }

        $fmtData = [];
        foreach ($itemData as $row) {
            if (!in_array($row['order_id'], $paymentOrderIds)) {
                continue;
            }

            if (empty($fmtData[ $row['product_id'] ])) {
                $fmtData[ $row['product_id'] ] = 0;
            }

            // 分时住要做处理
            $qty = $row['qty'];
            if ($row['can_split_use'] == \Soma_base::STATUS_TRUE && $row['use_cnt'] > 1) {
                $qty /= $row['use_cnt'];
            }
            $fmtData[ $row['product_id'] ] += $qty;
        }

        $result = new Result(Result::STATUS_OK, '', $fmtData);
        return $result->toArray();
    }

    /**
     * 获取某一产品的用户分销连接
     *
     * @param      string  $interId    公众号
     * @param      string  $openid     用户openid
     * @param      string  $productId  产品id
     *
     * @return     array   分销连接
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getProductIdistributeUrl($interId, $openid, $productId)
    {
        $this->buildShardConfig($interId);

        $this->getCI()->load->library('Soma/Api_idistribute');
        $api = $this->getCI()->api_idistribute;
        $salerInfo = $api->get_saler_info($interId, $openid);

        $params = [
            'id'        => $interId,
            'pid'       => $productId,
            'forqrcode' => 1,
        ];
        if (!empty($salerInfo)) {
            if ($salerInfo['typ'] == 'STAFF') {
                $params['saler'] = $salerInfo['info']['saler'];
            } else {
                $params['fans_saler'] = $salerInfo['info']['saler'];
            }
        }
        $url = \Soma_const_url::inst()->get_front_url($interId, 'soma/package/package_detail', $params);

        $result = new Result(Result::STATUS_OK, '', ['url' => $url]);
        return $result->toArray();
    }

    /**
     * 获取分销员某个产品的销售信息
     *
     * @param      string   $interId    公众号
     * @param      string   $productId  产品id
     * @param      string   $salerId    分销id
     * @param      integer  $type       分销员类型：1.分销员，2泛分销员
     *
     * @return     array    分销员某个产品的销售信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getSalerProductSalesInfo($interId, $productId, $salerId, $type = 1)
    {
        $this->buildShardConfig($interId);

        $this->getCI()->load->model('soma/Sales_order_model');
        $this->getCI()->load->model('soma/Sales_item_package_model');
        $orderModel = $this->getCI()->Sales_order_model;
        $itemModel  = $this->getCI()->Sales_item_package_model;

        if ($type == 1) {
            $filter['fans_saler_id'] = $salerId;
        } else {
            $filter['saler_id'] = $salerId;
        }
        $filter['inter_id'] = $interId;
        $filter['status']   = \Sales_order_model::STATUS_PAYMENT;

        $orderIds  = $itemData = [];
        $orderData = $orderModel->get(array_keys($filter), array_values($filter), '*', ['limit' => null]);

        foreach ($orderData as $row) {
            $orderIds[] = $row['order_id'];
            // 防止ci数据库操作爆栈
            if (count($orderIds) >= 2000) {
                $itemFilter = ['order_id' => $orderIds, 'product_id' => $productId];
                $tmp = $itemModel->get(array_keys($itemFilter), array_values($itemFilter), '*', ['limit' => null]);
                $itemData = array_merge($itemData, $tmp);
                $orderIds = [];
            }
        }

        if (!empty($orderIds)) {
            $itemFilter = ['order_id' => $orderIds, 'product_id' => $productId];
            $tmp = $itemModel->get(array_keys($itemFilter), array_values($itemFilter), '*', ['limit' => null]);
            $itemData = array_merge($itemData, $tmp);
        }

        if (empty($itemData)) {
            $fmtData = [];
            $productId = is_array($productId) ? $productId : [$productId];
            foreach ($productId as $id) {
                $fmtData[$id] = 0;
            }
            $result = new Result(Result::STATUS_OK, '', $fmtData);
            return $result->toArray();
        }

        $fmtData = [];
        foreach ($itemData as $row) {
            if (empty($fmtData[ $row['product_id'] ])) {
                $fmtData[ $row['product_id'] ] = 0;
            }

            // 分时住要做处理
            $qty = $row['qty'];
            if ($row['can_split_use'] == \Soma_base::STATUS_TRUE && $row['use_cnt'] > 1) {
                $qty /= $row['use_cnt'];
            }
            $fmtData[ $row['product_id'] ] += $qty;
        }

        $result = new Result(Result::STATUS_OK, '', $fmtData);
        return $result->toArray();
    }

    /**
     * 初始化数据库分片
     *
     * @param      string  $inter_id  公众号
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function buildShardConfig($inter_id = null)
    {
        $this->getCI()->load->model('soma/shard_config_model');
        $this->getCI()->current_inter_id = $inter_id;
        $this->getCI()->db_shard_config = $this->getCI()->shard_config_model->build_shard_config($inter_id);
    }

    /**
     * 获取产品销售信息
     *
     * @param      string  $interId    公众号
     * @param      string  $productId  产品ID
     *
     * @return     array   产品销售信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getProductSalesInfo($interId, $productId)
    {
        $this->buildShardConfig($interId);
        $this->getCI()->load->model('soma/Product_package_model');
        $productModel = $this->getCI()->Product_package_model;

        $filter      = ['inter_id' => $interId, 'product_id' => $productId];
        $productData = $productModel->get(array_keys($filter), array_values($filter));
        
        $fmtData = [];
        $productId = is_array($productId) ? $productId : [$productId];
        foreach ($productId as $id) {
            $fmtData[$id] = 0;
        }

        foreach ($productData as $row) {
            $fmtData[ $row['product_id'] ] = $row['sales_cnt'];
            if ($row['product_id'] == '150812') {
                $fmtData[ $row['product_id'] ] = (5000 - $row['sales_cnt'] / $row['use_cnt']) - 569;
            }
        }

        $result = new Result(Result::STATUS_OK, '', $fmtData);
        return $result->toArray();
    }
}