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
            'product_id',
            'face_img',
            'expiration_date',
            'if(expiration_date < now(), 1, 2) as expiration_status',
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
            sprintf('(select name from %s as ai where ai.gift_id = gid) as name', $gift_item_table_name, $gift_table_name)
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
     * @param $openid
     * @param $inter_id
     * @return Result
     */
    public function getOrderDetail($oid, $openid, $inter_id)
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
        $this->getCI()->load->model('soma/Sales_order_product_record_model', 'Sales_order_product_record_model');

        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        $salesItemPackageModel = $this->getCI()->salesItemPackageModel;
        /** @var \Consumer_code_model $consumer_code_model */
        $consumer_code_model = $this->getCI()->consumer_code_model;
        $consumer_shipping_model = $this->getCI()->consumer_shipping_model;
        /** @var \Gift_order_model $soma_gift_order */
        $soma_gift_order = $this->getCI()->soma_gift_order;


        $Sales_order_product_record_model = $this->getCI()->Sales_order_product_record_model;


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

        $order_product_record_table_name = $this->getCI()->soma_db_conn_read->dbprefix($Sales_order_product_record_model->table_name());

        $item_package_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->table_name('package'));

        $result = [];
        $order = $order[0];
        $item_condition = [
            'order_id' => $oid
        ];
        $item_map = $salesItemPackageModel->get(array_keys($item_condition), array_values($item_condition), [
            'order_id',
            'face_img',
            'name',
            'product_id',
            'price_market',
            'price_package',
            'price_killsec',
            'hotel_name',
            'hotel_tel',
            'expiration_date',
            'if(expiration_date < now(), 1, 2) as expiration_status',
            'can_refund', // 可退
            'can_mail', // 邮寄
            'can_gift', // 赠送
            'can_pickup', // 验卷
            'can_invoice', // 发票
            'can_reserve', // 预约
            'can_wx_booking', // 订房
            '(select (order_notice) from ' . $order_product_record_table_name . ' as r where r.order_id = ' . $item_package_table_name . '.order_id  limit 1) as order_notice',
        ], ['limit' => null]);

        $order['package'] = $item_map;

        if (empty($item_map)) {
            return $callback_func([], Result::STATUS_FAIL, 'order package not in');
        }



        $shipping_table_name = $this->getCI()->soma_db_conn_read->dbprefix($consumer_shipping_model->table_name());
        $code_table_name = $this->getCI()->soma_db_conn_read->dbprefix($consumer_code_model->table_name());
        $gift_table_name = $this->getCI()->soma_db_conn_read->dbprefix($soma_gift_order->table_name('package'));
        $gift_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($soma_gift_order->item_table_name('package'));
        $asset_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->asset_item_table_name('package'));

        $code_condition = [
            'order_id' => $oid,
            'status' => [2, 3, 4],
            function() use($asset_item_table_name, $openid){
                return 'asset_item_id in (select item_id from ' .  $asset_item_table_name . ' where openid =  "' . $openid . '" )';
            },
        ];
        $consumer_order = $consumer_code_model->get(array_keys($code_condition), array_values($code_condition), [
            str_replace(
                ['%shipping%', '%code%'],
                [$shipping_table_name, $code_table_name],
                'if(status=3,IFNULL((select shipping_id from %shipping% as s where s.order_id =  %code%.order_id and s.inter_id = %code%.inter_id and s.consumer_id = %code%.consumer_id) , 0), 0) as shipping_id'
            ),
            'if(status=4,(select gift_id from ' . $gift_item_table_name . ' as s1 where s1.item_id = ' . $code_table_name . '.asset_item_id),0) as gid',
            'if(status=4,(select send_from from ' . $gift_table_name . ' as s2 where s2.gift_id =  gid),0) as send_from',
            'order_id',
            'code',
            'status',
            'asset_item_id',
            'code_id',
            'consumer_item_id',
        ], ['limit' => null, 'orderBy' => 'status asc']);

        // 卷码相关
        $this->getCI()->load->helper('encrypt');
        $encrypt_util = new \Encrypt();
        foreach ($consumer_order as $key => &$_con_order) {
            $_con_order['qrcode_url'] = '';
            if ($_con_order['status'] == \Consumer_code_model::CAN_REFUND_STATUS_FAIL) {

                // 全部退款 && 卷码有效的
                if($order['refund_status'] == 33){
                    unset($consumer_order[$key]);
                    continue;
                }

                $content = $encrypt_util->encrypt($_con_order['code']);
                $length = $encrypt_util->encrypt(strlen($_con_order['code']));
                // 二维码地址
                $_con_order['qrcode_url'] = site_url('soma/api/get_consume_qrcode') .'?'. http_build_query(array('code' => base64_encode($content), 'valid' => base64_encode($length)));
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
    public function getDelete($oid)
    {
        $callback_func = function ($data = [], $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['data' => $data]);
            return $result;
        };
        $this->getCI()->load->model('soma/sales_order_model', 'somaSalesOrderModel');
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');

        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        $salesItemPackageModel = $this->getCI()->salesItemPackageModel;


        $item_package_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->table_name('package'));

        $condition = [
            'order_id' => $oid,
            'STATUS' => 12,
        ];
        $order = $somaSalesOrderModel->get(array_keys($condition), array_values($condition), [
            'refund_status',
            'consume_status',
            '(select expiration_date from ' . $item_package_table_name . ' where order_id = ' . $oid . '  limit 1) as expiration_date',
        ]);

        foreach($order as $item){
            if($item['consume_status'] == 23){ // 消费完毕
                continue;
            }
            if(strtotime($item['expiration_date']) < time()){ // 过期时间
                continue;
            }
            if($item['refund_status'] == 33){ // 全部退款
                continue;
            }
            return $callback_func([], Result::STATUS_FAIL, '删除失败');
        }

        $result = $somaSalesOrderModel->_shard_db()->update($somaSalesOrderModel->table_name(), ['del_time' => time()], ['order_id' => $oid, 'del_time' => 0], 1);
        if ($somaSalesOrderModel->_shard_db()->affected_rows()) {
            return $callback_func();
        }
        return $callback_func([], Result::STATUS_FAIL, '删除失败');
    }

    /**
     * 预约 || 验卷
     * @param $aiid
     * @param $openid
     * @param $inter_id
     * @return Result
     */
    public function getPackageInfo($aiid, $openid, $inter_id)
    {
        $callback_func = function ($data = [], $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['data' => $data]);
            return $result;
        };
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');
        $this->getCI()->load->model('soma/Consumer_code_model', 'consumer_code_model');
        // 商品
        $salesItemPackageModel = $this->getCI()->salesItemPackageModel;
        $salesItemPackageModel_condition = [
            'item_id' => $aiid,
            'inter_id' => $inter_id,
        ];
        $salesItemPackageModel_table_name = $salesItemPackageModel->asset_item_table_name('package');
        $itemPackage = $salesItemPackageModel->get(array_keys($salesItemPackageModel_condition), array_values($salesItemPackageModel_condition), [
            'product_id',
            'name',
            'hotel_name',
            'hotel_tel',
            'price_package',
            'price_market',
            'qty',
            'qty_origin',
            'expiration_date',
            'face_img',
            'status',
            'compose',
        ], ['table_name' => $salesItemPackageModel_table_name]);
        if (empty($itemPackage)) {
            return $callback_func([], Result::STATUS_FAIL, 'order not in');
        }
        $result['product'] = $itemPackage[0];

        $result['product']['compose'] = isset($result['product']['compose']) ? unserialize($result['product']['compose']) : [];

        // 卷码
        /** @var \Consumer_code_model $consumer_code_model */
        $consumer_code_model = $this->getCI()->consumer_code_model;


        $asset_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->asset_item_table_name('package'));

        $code_condition = [
            'asset_item_id' => $aiid,
            'status' => 2,
            'inter_id' => $inter_id,
            function() use($asset_item_table_name, $openid){
                return 'asset_item_id in (select item_id from ' .  $asset_item_table_name . ' where openid =  "' . $openid . '" )';
            },
        ];
        $codeModel = $consumer_code_model->get(array_keys($code_condition), array_values($code_condition), [
            'code',
            'status',
        ]);
        if (empty($codeModel)) {
            return $callback_func([], Result::STATUS_FAIL, 'code not in');
        }

        // 卷码相关
        $this->getCI()->load->helper('encrypt');
        $encrypt_util = new \Encrypt();
        foreach ($codeModel as &$_con_order) {
            $_con_order['qrcode_url'] = '';
            if ($_con_order['status'] == \Consumer_code_model::CAN_REFUND_STATUS_FAIL) {
                $content = $encrypt_util->encrypt($_con_order['code']);
                $length = $encrypt_util->encrypt(strlen($_con_order['code']));
                // 二维码地址
                $_con_order['qrcode_url'] = site_url('soma/api/get_consume_qrcode') .'?'. http_build_query(array('code' => base64_encode($content), 'valid' => base64_encode($length)));
            }
        }

        $result['code'] = $codeModel[0];
        return $callback_func($result);
    }

    /**
     * 交易快照
     * @param $oid
     * @param $inter_id
     * @param $openid
     * @return Result
     */
    public function getOrderRecord($oid, $inter_id, $openid)
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
        $this->getCI()->load->model('soma/Sales_order_product_record_model', 'Sales_order_product_record_model');

        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        $salesItemPackageModel = $this->getCI()->salesItemPackageModel;
        $Sales_order_product_record_model = $this->getCI()->Sales_order_product_record_model;

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


        $order_product_record_table_name = $this->getCI()->soma_db_conn_read->dbprefix($Sales_order_product_record_model->table_name());

        $item_package_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->table_name('package'));

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
            'hotel_name',
            'hotel_tel',
            'expiration_date',
            'can_refund', // 可退
            'can_mail', // 邮寄
            'can_gift', // 赠送
            'can_pickup', // 验卷
            'can_invoice', // 发票
            'can_reserve', // 预约
            'can_wx_booking', // 订房
            '(select (order_notice) from ' . $order_product_record_table_name . ' as r where r.order_id = ' . $item_package_table_name . '.order_id  limit 1) as order_notice',
        ], ['limit' => null]);

        $order['package'] = $item_map;

        if (empty($item_map)) {
            return $callback_func([], Result::STATUS_FAIL, 'order package not in');
        }

        return $callback_func($order);
    }


    /**
     * 微信订房
     * @param $oid
     * @param $aiid
     * @param $inter_id
     * @return Result
     */
    public function getWxSelectHotel($oid, $aiid, $inter_id, $search)
    {
        $callback_func = function ($data, $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['products' => $data]);
            return $result;
        };


        $this->getCI()->load->model('soma/Asset_item_package_model', 'asset_item_package_model');
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');

        $asset_item_package_model = $this->getCI()->asset_item_package_model;
        /** @var \Sales_item_package_model $salesItemPackageModel */
        $Sales_item_package_model = $this->getCI()->salesItemPackageModel;

        // 订单细单明细
        $result = [];
        $order = [];
        $item_condition = [
            'order_id' => $oid,
            'item_id' => $aiid
        ];
        $item_map = $asset_item_package_model->get(array_keys($item_condition), array_values($item_condition), [
            'product_id',
        ], ['limit' => 1]);

        $order['package'] = $item_map;


        if (empty($item_map)) {
            return $callback_func([], Result::STATUS_FAIL, 'order package not in');
        }

        $product_id = $item_map[0]['product_id'];


        $item_condition = [
            'inter_id' => $inter_id,
            'validity_date < ' => date('Y-m-d H:i:s'),
            'product_id' => $product_id,
            'status' => 1,
        ];

        $item_map = $Sales_item_package_model->get(array_keys($item_condition), array_values($item_condition), [
            'product_id',
            'wx_booking_config'
        ], ['limit' => 1, 'table' => $Sales_item_package_model->product_table_name('package')]);

        if (empty($item_map)) {
            return $callback_func([], Result::STATUS_FAIL, 'order package not in');
        }

        $item = $item_map[0];
        $wx_booking_config = json_decode($item['wx_booking_config'], true);


        foreach ($wx_booking_config as $key => $item) {
            if ($key == 'select_ids') {
                unset($wx_booking_config['select_ids']);
                continue;
            }
        }

        // 搜索
        if ($search) {
            foreach ($wx_booking_config as $key => $item) {
                foreach ($item['room_ids'] as $room_key => $roomMap) {
                    if (!isset($roomMap['name']) || strpos($roomMap['name'], $search) === false) {
                        unset($wx_booking_config[$key]['room_ids'][$room_key]);
                    }
                }
            }
        }
        return $callback_func(['wx_booking_config' => $wx_booking_config]);
    }


    /**
     * 微信订房
     * @param $orderId
     * @param $hotelId
     * @param $roomId
     * @param $priceCode
     * @return Result
     */
    public function getSelectHotelTime($orderId, $hotelId, $roomId, $priceCode){
        $callback_func = function ($data, $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['products' => $data]);
            return $result;
        };

//        $hotelId = $this->input->post('hid');
//        $roomId = $this->input->post('rmid');
//        $priceCode = $this->input->post('cdid');
//        $orderId = $this->input->post('oid');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $interId = $this->inter_id;
        $openid = $this->openid;

        //因为前端传过来的没有带0
        if( $month < 10 ){
            $month = '0'.$month;
        }

        // $start = $year.$month.'01';
        $year_now = date('Y');
        $month_now = date('m');

        $start = $year.$month.'01';
        //结束时间都以下个月1号为结束
        $end = date( "Ym01", strtotime( "{$start} +1 month" ) );

        $return = array( 'status'=> \Soma_base::STATUS_TRUE, 'data'=>array(), 'message'=>'' );
        $this->getCI()->load->library('Soma/Api_hotel');
        $ApiModel = new \Api_hotel( $interId );

        //过去的时间就不发起订房拉取时间了，全部不可选
        if( $year_now > $year || ( $year_now == $year && $month_now > $month ) ){
            $rooms_un_can_booking = $ApiModel->get_un_booking( $start );
            $return['data']['data'] = $rooms_un_can_booking;
            $return['message'] = '过去的时间不可选！';
        }else{

            if( $year == $year_now && $month == $month_now ){
                $start = date('Ymd');
            }

            // var_dump( $start, $year, $month, $year_now, $month_now, $year != $year_now );die;
            // $end = $year.$month.'31';

            if( !$hotelId || !$roomId || !$priceCode || !$interId || !$year || !$month ){
                // die('参数不全，请返回再试一次！');
                $return['message'] = '参数不全，把这个月都变成不可选';
                $return['data']['data'] = $ApiModel->get_un_booking( $start );

            }else{

                //调取订房时间接口
                $ApiModel->_write_log( 'ajax获取订房时间开始。inter_id：'.$interId.' order_id：'.$orderId, 'start：soma/booking/ajax_get_time' );
                $result = $ApiModel->get_rooms( $openid, $interId, $hotelId, $roomId, $priceCode, $start, $end );
                $ApiModel->_write_log( 'ajax获取订房时间结束。inter_id：'.$interId.' order_id：'.$orderId, 'end：soma/booking/ajax_get_time' );

                $rooms_un_can_booking = $rooms_can_booking = array();
                if( isset( $result['status'] ) && $result['status'] == \Soma_base::STATUS_TRUE ){
                    //现在是不管返回状态，都有返回数据
                    //如果没有返回$return['data']['rooms']信息，那么默认全部不可订
                    if( isset( $result['data']['rooms'] ) && !empty( $result['data']['rooms'] ) ){
                        $rooms_can_booking = isset( $result['data']['rooms']['can_booking'] )
                            ? $result['data']['rooms']['can_booking']
                            : array();
                        $rooms_un_can_booking = isset( $result['data']['rooms']['un_can_booking'] )
                            ? $result['data']['rooms']['un_can_booking']
                            : array();
                    }else{
                        $rooms_un_can_booking = $ApiModel->get_un_booking( $start );
                    }
                }else{
                    //如果获取数据失败，那么这个月都变成不可选
                    $return['message'] = isset( $result['message'] )
                        ? $result['message']
                        : '接口获取数据失败，把这个月都变成不可选';
                    $rooms_un_can_booking = $ApiModel->get_un_booking( $start );
                }

                $return['data']['data'] = $rooms_un_can_booking;

            }
        }

        slog($return);

            return $callback_func();
//        echo json_encode( $return );die;
    }

}