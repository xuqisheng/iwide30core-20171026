<?php
namespace App\services\soma;

use App\libraries\Support\Collection;
use App\libraries\Support\Log;
use App\services\BaseService;
use App\services\Result;
use App\services\soma\order\OrderProvider;

/**
 * Class OrderService
 * @package App\services\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class OrderService extends BaseService
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
     * 下单
     * @param $params
     * @return Result
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function create($params)
    {
        $provider = new OrderProvider();
        $order = $provider->resolve($params);

        $interID = $this->getCI()->inter_id;
        $openid = $this->getCI()->openid;

        $productID = $params['product_id'];
        $settlement = isset($params['settlement']) ? $params['settlement'] : null;
        $u_type = isset($params['u_type']) ? $params['u_type'] : '';
        $qty = $params['qty'];
        $memberCardIDs = isset($params['mcid']) ? $params['mcid'] : [];
        $salerID = isset($params['saler']) ? $params['saler'] : null;
        $fanSalerID = isset($params['fans_saler']) ? $params['fans_saler'] : null;
        $phone = isset($params['phone']) ? $params['phone'] : null;
        $name = isset($params['name']) ? $params['name'] : null;
        $quoteType = isset($params['quote_type']) ? $params['quote_type'] : '';
        $quote = isset($params['quote']) ? $params['quote'] : '';
        $password = isset($params['password']) ? $params['password'] : '';
        $pspSettingArr = isset($params['psp_setting']) ? $params['psp_setting'] : [];
        $actID = isset($params['act_id']) ? $params['act_id'] : 0;
        $instanceID = isset($params['inid']) ? $params['inid'] : 0;
        $scopeProductLinkID = isset($params['scope_product_link_id']) ? $params['scope_product_link_id'] : 0;
        $groupId = 0;
        $grid = isset($params['grid']) ? $params['grid'] : 0;
        $type = isset($params['type']) ? $params['type'] : '';


        $settingID = isset($pspSettingArr[$productID]) ? $pspSettingArr[$productID] : 0;

        //第一步
        $beforeResult = $order->beforeCreate($productID, $interID, $qty, $settingID, $actID, $instanceID, $openid);
        if ($beforeResult->getStatus() === Result::STATUS_FAIL) {
            Log::error("OrderService beforeCreate error, before msg is " . $beforeResult->getMessage(), $beforeResult->toArray());
            return $beforeResult;
        }
        $product = $beforeResult->getData();

        //第二步
        $buildResult = $order->buildData($product, $interID, $qty, $openid, $memberCardIDs, $salerID, $fanSalerID, $phone, $name, $quoteType, $quote, $password, $scopeProductLinkID);
        if ($buildResult->getStatus() === Result::STATUS_FAIL) {
            Log::error("OrderService beforeCreate error, build msg is " . $buildResult->getMessage(), $buildResult->toArray());
            return $buildResult;
        }
        /**
         * @var \Sales_order_model $salesOrderModel
         */
        $salesOrderModel = $buildResult->getData();
        $salesOrderModel->business = 'package';
        $salesOrderModel->settlement = $settlement;


        //第三步
        $createResult = $order->create($salesOrderModel);
        if ($createResult->getStatus() === Result::STATUS_FAIL) {
            Log::error("OrderService beforeCreate error, create msg is " . $createResult->getMessage(), $createResult->toArray());
            return $createResult;
        }
        $salesOrderModel = $buildResult->getData();

        //第四步
        $afterResult = $order->afterCreate($salesOrderModel, $product, $openid, $interID, $name, $phone, $u_type, $instanceID, $groupId);
        if ($afterResult->getStatus() === Result::STATUS_FAIL) {
            Log::error("OrderService beforeCreate error, after msg is " . $afterResult->getMessage(), $afterResult->toArray());
            return $afterResult;
        }

        return $afterResult;
    }


    /**
     * 订单列表
     * @author yhdsir
     * @param string $openid
     * @param string $type
     * @param array $options
     * @return Result
     */
    public function getOrderList($openid, $type = '', $options)
    {
        $this->getCI()->load->model('soma/sales_order_model', 'somaSalesOrderModel');
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');
        $callback_func = function ($data) {
            $result = new Result();
            $result->setStatus(Result::STATUS_OK);
            $result->setData($data);
            return $result;
        };
        /** @var \Sales_order_model $somaSalesOrderModel */
        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        /** @var \Sales_item_package_model $salesItemPackageModel */
        $Sales_item_package_model = $this->getCI()->salesItemPackageModel;
        $condition = [
            'and openid =' => $openid,
            'and status =' => \Sales_order_model::STATUS_PAYMENT, // 购买成功
            'and del_time =' => function () {
                return 0;
            }, // 未删除
        ];
        if ($type == 2) { // 未使用
            $condition['and consume_status !='] = 23; // 21 未消费 22 部分消费 23 全部消费
        }
        if ($type == 3) { // 已完成
            $condition['and consume_status ='] = 23; // 全部消费

            $table_name = $Sales_item_package_model->table_name();

            $table_name = $this->getCI()->soma_db_conn_read->dbprefix($table_name);

            $condition['and (select expiration_date from ' . $table_name . ' as p where order_id = p.order_id limit 1) <'] = date('Y-m-d H:i:s');
        }

        $paginate = $somaSalesOrderModel->paginate(array_keys($condition), array_values($condition), [
            'order_id',
            'create_time',
            'item_name',
            'real_grand_total',
            'row_qty',
            'status',
            'refund_status',
            'consume_status',
        ], $options);
        $order = isset($paginate['data']) ? $paginate['data'] : [];
        if (empty($order)) {
            return $callback_func($paginate);
        }
        $orderIDMap = array_column($order, 'order_id');
        $item_condition = [
            'order_id' => $orderIDMap
        ];
        $item_map = $Sales_item_package_model->get(array_keys($item_condition), array_values($item_condition), [
            'order_id',
            'face_img',
        ], ['limit' => null]);
        foreach ($order as $key => $value) {
            foreach ($item_map as $item_key => $item_val) {
                if ($item_val['order_id'] == $value['order_id']) {
                    $order[$key]['package'][] = $item_val;
                }
            }
        }
        $paginate['data'] = $order;
        return $callback_func($paginate);
    }

    /**
     * 我的礼物列表
     * @author yhdsir
     * @param string $openid
     * @param string $type
     * @param array $options
     * @return Result
     */
    public function getGiftList($openid, $type = '', $options)
    {
        $callback_func = function ($data = [], $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['data' => $data]);
            return $result;
        };
        $this->getCI()->load->model('soma/Gift_order_model', 'soma_gift_order');
        $this->getCI()->load->model('soma/Asset_item_package_model', 'asset_item_package_model');
        $this->getCI()->load->model('soma/Gift_item_package_model', 'gift_item_package_model');
        $this->getCI()->load->model('wx/Publics_model', 'Publics_model');
        /** @var \Gift_order_model $soma_gift_order */
        $soma_gift_order = $this->getCI()->soma_gift_order;
        /** @var \Asset_item_package_model $asset_item_package_model */
        $asset_item_package_model = $this->getCI()->asset_item_package_model;
        /** @var \Gift_item_package_model $gift_item_package_model */
        $gift_item_package_model = $this->getCI()->gift_item_package_model;
        $Publics_model = $this->getCI()->Publics_model;
        $condition = [
            'and is_p2p =' => 1,
            'and openid_received =' => $openid,
        ];
        $gift_table_name = $this->getCI()->soma_db_conn_read->dbprefix($soma_gift_order->table_name());
        $gift_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($gift_item_package_model->table_name());
        $asset_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($asset_item_package_model->table_name());
        $result = $soma_gift_order->paginate(array_keys($condition), array_values($condition), [
            'gift_id',
            'total_qty',
            'status',
            'openid_give',
            'create_time',
            sprintf('(select face_img from %s as s where s.item_id = (select asset_item_id from %s as ai where ai.gift_id = %s.gift_id)) as face_img', $asset_item_table_name, $gift_item_table_name, $gift_table_name),
            sprintf('(select name from %s as ai where ai.gift_id = %s.gift_id) as name', $gift_item_table_name, $gift_table_name)
        ], $options);
        if (!isset($result['data']) || empty($result['data'])) {
            return $callback_func();
        }
        $order = $result['data'];
        $openidMap = array_column($order, 'openid_give');
        $openidMap = array_unique($openidMap);
        $fans = $Publics_model->get_fans_info_byIds($openidMap); // 粉丝
        if ($fans) {
            $fans_keys = array_column($fans, 'openid');
            $fans_value = array_column($fans, 'nickname');
            $fans = array_combine($fans_keys, $fans_value);
        }
        foreach ($order as $key => $value) {
            $value['openid_nickname'] = '';
            if ($fans && isset($fans[$value['openid_give']])) {
                $value['openid_nickname'] = $fans[$value['openid_give']];
            }
            $order[$key] = $value;
        }
        return $callback_func($order);
    }


    /**
     * 订单明细
     * @param $oid
     * @return Result
     */
    public function getOrderDetail($oid)
    {
        $callback_func = function ($data, $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['data' => $data]);
            return $result;
        };
        $this->getCI()->load->model('soma/sales_order_model', 'somaSalesOrderModel');
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');
        $this->getCI()->load->model('soma/Consumer_code_model', 'consumer_code_model');
        $this->getCI()->load->model('soma/Consumer_shipping_model', 'consumer_shipping_model');
        $this->getCI()->load->model('soma/Gift_order_model', 'soma_gift_order');

        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        $salesItemPackageModel = $this->getCI()->salesItemPackageModel;
        /** @var \Consumer_code_model $consumer_code_model */
        $consumer_code_model = $this->getCI()->consumer_code_model;
        $consumer_shipping_model = $this->getCI()->consumer_shipping_model;
        /** @var \Gift_order_model $soma_gift_order */
        $soma_gift_order = $this->getCI()->soma_gift_order;

        // order 相关
        $condition = ['order_id' => $oid, 'del_time' => 0];
        $order = $somaSalesOrderModel->get(array_keys($condition), array_values($condition), [
            'order_id',
            'create_time',
            'item_name',
            'real_grand_total',
            'row_qty',
            'status',
            'refund_status',
            'consume_status',
        ]);
        if (empty($order)) {
            return $callback_func([], Result::STATUS_FAIL, 'order not in');
        }

        // 订单细单明细
        $result = [];
        $order = $order[0];
        $item_condition = ['order_id' => $oid];
        $item_map = $salesItemPackageModel->get(array_keys($item_condition), array_values($item_condition), [
            'order_id',
            'face_img',
            'name',
            'product_id',
            'price_market',
            'price_package',
            'price_killsec',
        ], ['limit' => null]);
        $order['package'] = $item_map;
        if (empty($item_map)) {
            return $callback_func([], Result::STATUS_FAIL, 'order package not in');
        }
        $code_condition = ['order_id' => $oid,];
        $shipping_table_name = $this->getCI()->soma_db_conn_read->dbprefix($consumer_shipping_model->table_name());
        $code_table_name = $this->getCI()->soma_db_conn_read->dbprefix($consumer_code_model->table_name());
        $gift_table_name = $this->getCI()->soma_db_conn_read->dbprefix($soma_gift_order->item_table_name('package'));
        $consumer_order = $consumer_code_model->get(array_keys($code_condition), array_values($code_condition), [
            '(select count(*) from ' . $shipping_table_name . ' as s where s.order_id = ' . $code_table_name . '.order_id and s.inter_id = ' . $code_table_name . '.inter_id) as is_shipping',
//            '(select gift_id from ' . $gift_table_name . ' as s1 where s1.asset_item_id = '. $code_table_name .'.asset_item_id) as gift_id',
            'order_id',
            'code',
            'status',
            'asset_item_id',
            'code_id',
        ], ['limit' => null]);

        // 卷码相关
        $this->getCI()->load->helper('encrypt');
        $encrypt_util = new \Encrypt();
        foreach ($consumer_order as &$_con_order) {
            $_con_order['qrcode_url'] = '';
            if ($_con_order['status'] == \Consumer_code_model::CAN_REFUND_STATUS_FAIL) {
                $content = $encrypt_util->encrypt($_con_order['code']);
                $length = $encrypt_util->encrypt(strlen($_con_order['code']));
                // 二维码地址
                $_con_order['qrcode_url'] = \Soma_const_url::inst()->get_url('soma/api/get_consume_qrcode', array('code' => base64_encode($content), 'valid' => base64_encode($length)));
            }
        }

        $result['code'] = $consumer_order;
        $result['product'] = $order;
        return $callback_func($result);
    }

    /**
     * 删除订单
     * @param $oid
     * @return Result
     */
    public function getDeleteOrder($oid)
    {
        $callback_func = function ($data = [], $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['data' => $data]);
            return $result;
        };
        $this->getCI()->load->model('soma/sales_order_model', 'somaSalesOrderModel');
        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        $table = $this->getCI()->soma_db_conn_read->dbprefix($somaSalesOrderModel->table_name());
        $result = $somaSalesOrderModel->_shard_db()->update($table, ['del_time' => time()], ['order_id' => $oid, 'del_time' => 0], 1);
        if ($somaSalesOrderModel->_shard_db()->affected_rows()) {
            return $callback_func();
        }
        return $callback_func([], Result::STATUS_FAIL, '删除失败');
    }
}