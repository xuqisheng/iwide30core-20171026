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
     * 订单创建之前的操作
     *
     * @param      array $params 下单参数
     *
     * @return     Result  处理结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function beforeCreate($params)
    {
        // 金陵下单限制
        $jinLinResult = $this->jinLinOrderLimit($params);
        if ($jinLinResult->getStatus() != Result::STATUS_OK) {
            return $jinLinResult;
        }

        // 补充下单时所需要的额外参数，如分销员参数信息
        $paramsResult = $this->fillExtraParams($params);
        if ($paramsResult->getStatus() != Result::STATUS_OK) {
            return $paramsResult;
        }
        $paramsData = $paramsResult->getData();
        $params = $paramsData['params'];

        // 获取下单产品信息
        $productResult = $this->getOrderProductInfo($params);
        if ($productResult->getStatus() != Result::STATUS_OK) {
            return $productResult;
        }
        $productData = $productResult->getData();
        $productArr = $productData['productArr'];

        // 处理活动信息
        $activityResult = $this->handleOrderActivity($productArr, $params);
        if ($activityResult->getStatus() != Result::STATUS_OK) {
            return $activityResult;
        }
        $activityData = $activityResult->getData();
        $productArr = $activityData['productArr'];
        $activityInfo = $activityData['activityInfo'];

        // 处理折扣优惠信息
        $discountResult = $this->handleOrderDiscount($productArr, $params);
        if ($discountResult->getStatus() != Result::STATUS_OK) {
            return $discountResult;
        }
        $discountData = $discountResult->getData();

        Log::debug('beforeCreate discount data is ', $discountData);

        $discountInfo = $discountData['discountInfo'];

        // 查询会员信息
        $memberResult = $this->getUserMemberInfo($params);
        if ($memberResult->getStatus() != Result::STATUS_OK) {
            return $memberResult;
        }
        $memberData = $memberResult->getData();
        $memberInfo = $memberData['memberInfo'];

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'params' => $params,
                'productArr' => $productArr,
                'activityInfo' => $activityInfo,
                'discountInfo' => $discountInfo,
                'memberInfo' => $memberInfo,
            ]
        );
        return $result;
    }

    /**
     * 金陵公众号限制下单操作
     *
     * @param      array $params 下单参数
     *
     * @return     Result  金陵公众号限制下单信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function jinLinOrderLimit($params)
    {
        $interId = $params['inter_id'];
        $openid = $params['openid'];
        $interIdArr = ['a491796658', 'a492669988'];

        if (in_array($interId, $interIdArr)) {
            $redis = $this->getCI()->get_redis_instance();
            $lock_key = 'SOMA_ORDER:11_SEC_LOCK_' . $interId . '_' . $openid;
            $lock = $redis->setnx($lock_key, 'lock');
            if (!$lock) {
                $msg = '正在为您下单，请勿操作，耐心等待';
                return new Result(Result::STATUS_FAIL, $msg);
            }
            $redis->setex($lock_key, 11, 'jin_ling_order_lock');
        }

        return new Result(Result::STATUS_OK);
    }

    /**
     * 补充下单时需要的额外参数
     *
     * @param      array $params 下单参数
     *
     * @return     Result  补充参数结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function fillExtraParams($params)
    {
        if (!empty($params['saler'])) {
            $this->getCI()->load->library('Soma/Api_idistribute');
            $api = $this->getCI()->api_idistribute;
            $group_info = $api->get_staff_group_info($params['inter_id'], $params['saler']);
            $saler_group = array();
            foreach ($group_info as $group) {
                $saler_group[] = $group['group_id'];
            }
            $params['saler_group'] = implode(',', $saler_group);

            $saler_info = $api->getSalerInfoBySalerId($params['inter_id'], $params['saler']);
            $params['saler_hotel'] = empty($saler_info['hotel_id']) ? '' : $saler_info['hotel_id'];
        }

        return new Result(Result::STATUS_OK, '', ['params' => $params]);
    }

    /**
     * 获取下单产品信息
     *
     * @param      array $params 下单参数
     *
     * @return     Result  下单产品信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function getOrderProductInfo($params)
    {
        // 获取原始的产品信息
        $productResult = $this->getOriginProductInfo($params);
        if ($productResult->getStatus() != Result::STATUS_OK) {
            return $productResult;
        }
        $productData = $productResult->getData();
        $productArr = $productData['productArr'];

        // 根据产品自身属性更新下单产品信息
        $selfAttrResult = $this->modifyOrderProductBySelfAttr($productArr, $params);
        if ($selfAttrResult->getStatus() != Result::STATUS_OK) {
            return $selfAttrResult;
        }
        $selfAttrData = $selfAttrResult->getData();
        $productArr = $selfAttrData['productArr'];

        // 根据产品多规格信息更新下单产品信息
        $specResult = $this->modifyOrderProductBySpecInfo($productArr, $params);
        if ($specResult->getStatus() != Result::STATUS_OK) {
            return $specResult;
        }
        $specData = $specResult->getData();
        $productArr = $specData['productArr'];

        $result = new Result();
        if (empty($productArr)) {
            $result->setMessage('无可用产品信息!');
        } else {
            $result->setData(['productArr' => $productArr]);
            $result->setStatus(Result::STATUS_OK);
        }

        return $result;
    }

    /**
     * 获取从数据库中产品信息（预留）
     *
     * @param      array $params 下单参数
     *
     * @return     Result  产品信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function getOriginProductInfo($params)
    {
        $qtyArr = $params['qty'];
        $productIds = $params['product_id'];
        //预留
//        if (!is_array($productIds)) {
//            $productIds = explode(',', $params['product_id']);
//        }

        if (!isset($this->getCI()->Product_package_model)) {
            $this->getCI()->load->model('soma/Product_package_model');
        }
        $productModel = $this->getCI()->Product_package_model;
        //预留
        //$productArr = $productModel->get_product_package_by_ids($productIds, $params['inter_id']);
        //$productEnInfo = $productModel->getProductEnInfoList($productIds, $params['inter_id']);
        $productArr = $productModel->get_product_package_by_ids([$productIds], $params['inter_id']);
        $productEnInfo = $productModel->getProductEnInfoList([$productIds], $params['inter_id']);

        foreach ($productArr as $k => $v) {
            //预留
            //$productArr[$k]['qty'] = $qtyArr[$v['product_id']];
            $productArr[$k]['qty'] = $qtyArr;

            foreach ($productModel->en_fields() as $field) {
                $productArr[$k][$field . '_en'] = '';
            }
            if (isset($productEnInfo[$v['product_id']])) {
                foreach ($productModel->en_fields() as $field) {
                    if (!empty($productEnInfo[$v['product_id']][$field])) {
                        $productArr[$k][$field . '_en'] = $productEnInfo[$v['product_id']][$field];
                    }
                }
            }
        }

        return new Result(Result::STATUS_OK, '', ['productArr' => $productArr]);
    }


    /**
     * 根据产品自身属性更改某些值
     *
     * @param      array $productArr 产品信息
     * @param      array $params 下单参数
     *
     * @return     Result  更改结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function modifyOrderProductBySelfAttr($productArr, $params)
    {

        foreach ($productArr as $k => $v) {
            //如果是积分商品，去掉小数点，向上取整
            if ($v['type'] == \Product_package_model::PRODUCT_TYPE_POINT) {
                $productArr[$k]['price_market'] = ceil($productArr[$k]['price_market']);
                $productArr[$k]['price_package'] = ceil($productArr[$k]['price_package']);
            }

            // 如果是升级房券，判断是否存在分销员id以及分销员所属酒店
            if ($v['goods_type'] == \Product_package_model::SPEC_TYPE_ROOM) {
                if (empty($params['saler']) || empty($params['saler_hotel'])) {
                    $msg = '提示：请重新扫描员工二维码！';
                    return new Result(Result::STATUS_FAIL, $msg);
                }
                $productArr[$k]['hotel_id'] = $params['saler_hotel'];
            }

            //库存
            if(!$params['psp_setting']){
                if($params['qty'] > $productArr[$k]['stock']){
                    return new Result(Result::STATUS_FAIL, '库存不足');
                }
            }
        }

        return new Result(Result::STATUS_OK, '', ['productArr' => $productArr]);
    }

    /**
     * 根据产品多规格信息更改某些值
     *
     * @param      array $productArr 产品信息
     * @param      array $params 下单参数
     *
     * @return     Result  更改结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function modifyOrderProductBySpecInfo($productArr, $params)
    {
        $result = new Result();

        // 获取多规格库存信息，注意：同一产品在同一次下单总只能下一个规格
        $specSettingInfo = $productSpecInfo = [];
        //预留
        //$specIdArr = empty($params['psp_setting']) ? [] : $params['psp_setting'];
        $specIdArr = [$productArr[0]['product_id'] => $params['psp_setting']];
        if (!empty($specIdArr)) {
            $this->getCI()->load->model('soma/Product_specification_model');
            $this->getCI()->load->model('soma/Product_specification_setting_model');
            $psModel = $this->getCI()->Product_specification_model;
            $pspModel = $this->getCI()->Product_specification_setting_model;

            $tmpInfo = $pspModel->get_inter_product_spec_setting($params['inter_id'], array_keys($specIdArr));
            foreach ($tmpInfo as $row) {
                $productSpecInfo[$row['product_id']][] = $row;
                //预留
//                if ($row['setting_id'] == $specIdArr[$row['product_id']]) {
//                    $specSettingInfo[$row['product_id']] = $row;
//                }
                if ($row['setting_id'] == $params['psp_setting']) {
                    $specSettingInfo[$row['product_id']] = $row;
                }
            }
        }

        foreach ($productArr as $k => $v) {
            // 默认不是时间规格的
            $productArr[$k]['setting_date'] = \Soma_base::STATUS_FALSE;

            // 不是多规格商品跳过
            // 因为多规格与普通商品切换时，规格信息不会删除，所以未指定规格时走默认下单
            if (empty($productSpecInfo[$v['product_id']])
                || empty($specSettingInfo[$v['product_id']])
            ) {
                continue;
            }

            // 除了普通购买外，其他购买均走多规格总库存
            if ($params['settlement'] != 'default') {
                $stocks = 0;
                foreach ($productSpecInfo[$v['product_id']] as $setting) {
                    $stocks += $setting['stock'];
                }
                $productArr[$k]['stock'] = $stocks;
                $productArr[$k]['setting_id'] = 'all';
                continue;
            }


            // 替换价格库存
            $orderSpec = $specSettingInfo[$v['product_id']];
            $productArr[$k]['price_package'] = $orderSpec['spec_price'];
            $productArr[$k]['stock'] = $orderSpec['spec_stock'];
            $productArr[$k]['setting_id'] = $orderSpec['setting_id'];

            //库存
            if($params['qty'] > (int)$orderSpec['spec_stock']){
                return new Result(Result::STATUS_FAIL, '库存不足');
            }

            // 产品名追加规格信息 后续该优化一下，不应该在循环里面读数据库
            $totalSpec = $psModel->get_spec_list(
                $params['inter_id'],
                $v['product_id'],
                $orderSpec['type']
            );

            $totalCompose = json_decode($totalSpec[$orderSpec['type']]['spec_compose'], true);
            $orderCompose = current(json_decode($orderSpec['setting_spec_compose'], true));

            // 普通多规格
            if ($orderSpec['type'] == \Product_package_model::SPEC_TYPE_SCOPE) {
                $totalTypeName = $productSpecNameArr = [];
                if (isset($totalCompose['spec_type'])
                    && is_array($totalCompose['spec_type'])
                ) {
                    $totalTypeName = $totalCompose['spec_type'];
                }

                foreach ($totalTypeName as $key => $typeName) {
                    $productSpecNameArr[] = $typeName . ':' . $orderCompose['spec_name'][$key];
                }
                $productArr[$k]['name'] .= "(" . implode(';', $productSpecNameArr) . ")";

            } else {
                // 不是普通多规格就是时间多规格
                // 这里是新加的字段，如果是时间规格的，那么过期时间就是规格时间
                $productArr[$k]['setting_date'] = \Soma_base::STATUS_TRUE;
                $productArr[$k]['expiration_date'] = date('Y-m-d 23:59:59', strtotime($orderCompose['date']));
                $productArr[$k]['name'] .= "(" . $orderCompose['spec_name'][0] . ")";
            }
        }

        return new Result(Result::STATUS_OK, '', ['productArr' => $productArr]);
    }

    /**
     * 处理活动信息
     *
     * @param      array $productArr 产品信息
     * @param      array $params 下单参数
     *
     * @return     Result  处理结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function handleOrderActivity($productArr, $params)
    {
        switch ($params['settlement']) {
            case 'killsec':
                return $this->handleOrderKillsecActivity($productArr, $params);
            case 'groupon':
                return $this->handleOrderGrouponActivity($productArr, $params);
            default:
                break;
        }

        return new Result(Result::STATUS_OK, '', ['productArr' => $productArr, 'activityInfo' => []]);
    }

    /**
     * 处理秒杀活动信息
     *
     * @param      array $productArr 产品信息
     * @param      array $params 下单参数
     *
     * @return     Result  处理结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function handleOrderKillsecActivity($productArr, $params)
    {
        $this->getCI()->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
        /**
         * @var \Activity_killsec_model $KillsecModel
         */
        $KillsecModel = $this->getCI()->activityKillsecModel;
        $interId = $params['inter_id'];
        $openid = $params['openid'];
        $instance_id = $params['inid'];
        $act_id = $params['act_id'];

        $killResult = KillsecService::getInstance()->orderValid($instance_id, $interId, $openid);
        if ($killResult->getStatus() == Result::STATUS_FAIL) {
            return $killResult;
        }

        $actDetail = $KillsecModel->find(['inter_id' => $interId, 'act_id' => $act_id]);
        foreach ($productArr as $k => $v) {
            $productArr[$k]['price_package'] = $actDetail['killsec_price'];
            if ($productArr[$k]['qty'] > $actDetail['killsec_permax']) {
                $productArr[$k]['qty'] = $actDetail['killsec_permax'];
            }
        }

        $resultData = $killResult->toArray();
        if (!empty($killResult->getData())) {
            //$msg = '新版秒杀流程尚不支持重复拉起支付';
            //return new Result(Result::STATUS_FAIL, $msg);
            // $resultData['step'] = $default_step;
            // if( in_array( $this->getCI()->inter_id, $this->wft_pay_inter_ids ) ){
            //     $resultData['step'] = 'wft_pay';
            // }
            // Log::debug('make order killsec result :', $resultData);
            // echo json_encode($resultData);die;
        }

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'productArr' => $productArr,
                'activityInfo' => [
                    'actDetail' => $actDetail,
                    'type' => 'killsec',
                    'killsecUserInsert' => true,
                    'model' => $KillsecModel,
                ],
            ]
        );

        return $result;
    }

    /**
     * 处理拼团活动信息
     *
     * @param      array $productArr 产品信息
     * @param      array $params 下单参数
     *
     * @return     Result  处理结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function handleOrderGrouponActivity($productArr, $params)
    {
        $actId = $params['act_id'];
        $interId = $params['inter_id'];
        $openid = $params['openid'];
        $type = $params['type'];
        $business = $params['business'];

        $this->getCI()->load->model('soma/Activity_groupon_model');
        $grouponModel = $this->getCI()->Activity_groupon_model;

        if ($type == 'add') {
            $groupId = $grouponModel->add_groupon_group($actId, $openid, $interId);
        } else {
            $groupId = $params['grid'];
            /*同步检测人数*/
            $this->getCI()->load->model('soma/sales_refund_model');
            $salesRefundModel = $this->getCI()->sales_refund_model;
            $grouponModel->set_unavailable_group_user(
                $interId,
                ['group_id' => $groupId],
                $salesRefundModel,
                $business
            );

            Log::info(
                'Groupon release and groupId ',
                [
                    'groupID' => $groupId,
                    'openid' => $this->getCI()->openid,
                    'interID' => $this->getCI()->inter_id
                ]
            );
        }

        /**参团用户信息*/
        $this->getCI()->load->model('wx/Publics_model', 'public');
        $userFansInfo = $this->getCI()->public->get_fans_info($openid);
        $userInfo['openid'] = $openid;
        $userInfo['nickname'] = $userFansInfo['nickname'];
        $userInfo['headimgurl'] = $userFansInfo['headimgurl'];
        $userInfo['join_time'] = date('Y-m-d H:i:s');
        $userInfo['status'] = \Soma_base::STATUS_FALSE;
        $groupUserInsert = true; //获取订单后插入数据库标记
        /**参团用户信息 end*/

        $grouponDetial = $grouponModel->groupon_group_detail($groupId, $interId);
        $actDetail = null;
        if (!empty($grouponDetial)) {
            $actDetail = $grouponModel->groupon_detail($grouponDetial['act_id'], $interId);
        }

        if (empty($grouponDetial) || empty($actDetail)) {
            $msg = '系统异常，请稍后重新尝试。';
            return new Result(Result::STATUS_FAIL, $msg);
        }

        /*验证参团人数*/
        if ($actDetail['product_id'] != $params['product_id']) {
            //防止手动输入product_id借助配额购买其他商品
            $msg = '参数错误，请重新参加活动购买。';
            return new Result(Result::STATUS_FAIL, $msg);
        }
        if ($grouponDetial['status'] == \Activity_groupon_model::GROUP_STATUS_FINISHED) {
            $msg = '真遗憾，你来晚啦~~别人已经完成这个拼团';
            return new Result(Result::STATUS_FAIL, $msg);
        }
        if ($grouponDetial['join_count'] >= $actDetail['group_count']) {
            $msg = '真遗憾，你来晚啦~~前面已经有用户正在支付了';
            return new Result(Result::STATUS_FAIL, $msg);
        }

        //修正活动信息：拼团人数加1
        if ($type != 'add') {
            $grouponModel->update_groupon_group_join($groupId, 'join', $interId);
        }

        foreach ($productArr as $k => $v) {
            $productArr[$k]['price_package'] = $actDetail['group_price'];
            $productArr[$k]['qty'] = 1; //拼团只可以买一件
        }

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'productArr' => $productArr,
                'activityInfo' => [
                    'type' => 'groupon',
                    'actDetail' => $actDetail,
                    'grouponDetial' => $grouponDetial,
                    'groupUserInsert' => $groupUserInsert,
                    'userInfo' => $userInfo,
                    'model' => $grouponModel,
                ],
            ]
        );
        return $result;
    }

    /**
     * 处理订单折扣信息
     *
     * @param      array $productArr 产品信息
     * @param      array $params 下单参数
     *
     * @return     Result  处理结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function handleOrderDiscount($productArr, $params)
    {
        // 积分商品去除所有优惠规则
        foreach ($productArr as $product) {
            if ($product['type'] == \Product_package_model::PRODUCT_TYPE_POINT) {
                unset($params['mcid']);
                unset($params['quote_type']);
            }
        }

        // 优惠券优惠
        $couponResult = $this->handleOrderCouponDiscount($productArr, $params);
        if ($couponResult->getStatus() != Result::STATUS_OK) {
            return $couponResult;
        }
        $couponData = $couponResult->getData();
        $couponDiscount = $couponData['discount'];
        Log::debug('handleOrderDiscount coupon discount is ', $couponData);

        // 储值优惠
        $balanceResult = $this->handleOrderBalanceDiscount($productArr, $params);
        if ($balanceResult->getStatus() != Result::STATUS_OK) {
            return $balanceResult;
        }
        $balanceData = $balanceResult->getData();
        $balanceDiscount = $balanceData['discount'];

        // 积分优惠
        $pointResult = $this->handleOrderPointDiscount($productArr, $params);
        if ($pointResult->getStatus() != Result::STATUS_OK) {
            return $pointResult;
        }
        $pointData = $pointResult->getData();
        $pointDiscount = $pointData['discount'];

        // 满减优惠在订单保存时自动计算

        // 合并优惠
        $discountInfo = $couponDiscount + $balanceDiscount + $pointDiscount;

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'productArr' => $productArr,
                'discountInfo' => $discountInfo,
            ]
        );
        return $result;
    }

    protected function handleOrderCouponDiscount($productArr, $params)
    {
        $discountInfo = [];

        if (!empty($params['mcid'])) {

            $this->getCI()->load->model('soma/Sales_order_discount_model');
            $this->getCI()->load->library('Soma/Api_member');

            $api = new \Api_member($params['inter_id']);

            $couponList = $api->conpon_sign_list($params['openid']);
            $couponList = (array)$couponList['data'];

            $discountType = \Sales_order_discount_model::TYPE_COUPON;
            $info = array();
            $coupon_count = $get_card_id = 0;

            $member_card_ids = [$params['mcid']];

            foreach ($couponList as $k => $v) {
                $sv = (array)$v;
                if (in_array($sv['member_card_id'], $member_card_ids)) {
                    $info[$sv['member_card_id']] = $sv + ['discount_type' => $discountType];
                    $coupon_count++;

                    //判断是否是同一个card_id，否则返回空。只能同种券同一个card_id的券才可以叠加使用  todo 这个逻辑有问题
                    if ($get_card_id && $get_card_id != $sv['card_id']) {
                        $info = array();
                        $coupon_count = 0;
                        break;
                    } else {
                        $get_card_id = $sv['card_id'];
                    }

                    //判断是否是折扣券、代金券、折扣券。如果是折扣券，只能使用一张券。
                    if ($sv['card_type'] == \Sales_order_discount_model::TYPE_COUPON_ZK) {
                        //如果是折扣券，那么先置空$info，等于当前的券内容，$coupon_count = 1。并停止循环
                        $info = array();
                        $coupon_count = 1;
                        $info[$sv['member_card_id']] = $sv + ['discount_type' => $discountType];
                        break;
                    }
                }
            }

            //检测购买数量和优惠券的数量  购买数量>=优惠券数量
            $buy_count = isset($params['qty']) ? $params['qty'] : 0;
            if ($coupon_count > $buy_count) {
                return new Result(Result::STATUS_FAIL, '选择优惠券出错');
            }

            $discountInfo[$discountType] = $info + ['discount_type' => $discountType];
        }
        return new Result(Result::STATUS_OK, '', ['discount' => $discountInfo]);
    }

    protected function handleOrderBalanceDiscount($productArr, $params)
    {
        $discountInfo = [];

        if (!class_exists('\Sales_rule_model')) {
            $this->getCI()->load->model('soma/Sales_rule_model');
        }

        if (isset($params['quote_type'])
            && isset($params['quote'])
            && $params['quote_type'] == \Sales_rule_model::RULE_TYPE_BALENCE
        ) {

            $this->getCI()->load->library('Soma/Api_member');

            $this->getCI()->load->model('soma/Sales_order_discount_model');

            $api = new \Api_member($params['inter_id']);
            $result = $api->get_token();
            $api->set_token($result['data']);
            $info = $api->balence_info($this->getCI()->openid);

            if ($info['data'] >= $params['quote']) {
                $discountType = \Sales_order_discount_model::TYPE_BALENCE;
                $params['password'] = isset($params['password']) ? $params['password'] : '';

                $discountInfo[$discountType] = [
                    'discount_type' => $discountType,
                    'quote' => $params['quote'],
                    'passwd' => $params['password']
                ];

            } else {
                return new Result(Result::STATUS_FAIL, '您的储值不够');
            }
        }

        return new Result(Result::STATUS_OK, '', ['discount' => $discountInfo]);
    }

    protected function handleOrderPointDiscount($productArr, $params)
    {
        $discountInfo = [];

        if (!class_exists('\Sales_rule_model')) {
            $this->getCI()->load->model('soma/Sales_rule_model');
        }

        if (isset($params['quote_type'])
            && isset($params['quote'])
            && $params['quote_type'] == \Sales_rule_model::RULE_TYPE_POINT
        ) {

            $this->getCI()->load->library('Soma/Api_member');

            $this->getCI()->load->model('soma/Sales_order_discount_model');

            $api = new \Api_member($params['inter_id']);
            $result = $api->get_token();
            $api->set_token($result['data']);
            $info = $api->point_info($params['openid']);

            if ($info['data'] >= $params['quote']) {
                $discountType = \Sales_order_discount_model::TYPE_POINT;
                $discountInfo[$discountType] = ['discount_type' => $discountType, 'quote' => $params['quote']];
            } else {
                return new Result(Result::STATUS_FAIL, '您的积分不够');
            }
        }
        return new Result(Result::STATUS_OK, '', ['discount' => $discountInfo]);
    }

    /**
     * 获取用户会员信息
     *
     * @param      array $params 下单参数
     *
     * @return     Result  会员信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function getUserMemberInfo($params)
    {
        $this->getCI()->load->library('Soma/Api_member');

        $api = new \Api_member($params['inter_id']);
        $memberInfo = $api->get_member_info($params['openid']);
        if (empty($memberInfo)) {
            return new Result(Result::STATUS_FAIL, '会员信息获取失败，请稍后再重新尝试下单');
        }

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'memberInfo' => [
                    'member_id' => $memberInfo['data']->member_id,
                    'member_card_id' => $memberInfo['data']->membership_number,
                ],
            ]
        );
        return $result;
    }

    /**
     * 创建订单
     *
     * @param      array $params 下单参数
     *
     * @return     Result  下单结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function create($params)
    {
        //$this->getCI()->soma_db_conn->trans_start();

        //
        $beforeResult = $this->beforeCreate($params);
        if ($beforeResult->getStatus() != Result::STATUS_OK) {
            return $beforeResult;
        }

        //
        $orderResult = $this->makeOrder($beforeResult);
        if ($orderResult->getStatus() != Result::STATUS_OK) {
            return $orderResult;
        }
        $orderData = $orderResult->getData();

        $orderModel = $orderData['orderModel'];

        //
        $afterResult = $this->afterCreate($beforeResult, $orderResult);
        if ($afterResult->getStatus() != Result::STATUS_OK) {
            return $afterResult;
        }
        $afterData = $afterResult->getData();
        $paymentInfo = $afterData['paymentInfo'];

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'orderInfo' => ['order_id' => $orderModel->m_get('order_id')],
                'payChannel' => $paymentInfo['payment_method'],
            ]
        );

        //$this->getCI()->soma_db_conn->trans_complete();
        return $result;
    }

    /**
     * 创建订单
     *
     * @param      Result $beforeResult 创建订单之前的处理
     *
     * @return     Result  订单创建结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function makeOrder($beforeResult)
    {
        $beforeData = $beforeResult->getData();
        $params = $beforeData['params'];
        $productArr = $beforeData['productArr'];
        $activityInfo = $beforeData['activityInfo'];
        $discountInfo = $beforeData['discountInfo'];
        $memberInfo = $beforeData['memberInfo'];

        /**
         * @var \Sales_order_model $orderModel
         */
        $this->getCI()->load->model('soma/Sales_order_model');
        $orderModel = $this->getCI()->Sales_order_model;

        $customer = new \Sales_order_attr_customer($params['openid']);
        $customer->name = $params['name'];
        $customer->mobile = $params['phone'];
        $customer->openid = $params['openid'];

        $orderModel->customer = $customer;
        $orderModel->business = $params['business'];
        $orderModel->settlement = $params['settlement'];
        $orderModel->scope_product_link_id = $params['scope_product_link_id'];

        $orderModel->member_id = $memberInfo['member_id'];
        $orderModel->member_card_id = $memberInfo['member_card_id'];


        $giveDistributeSessionKey = 'giveDistribute' . $params['inter_id'] . $params['openid'];
        $giveDistribute = $this->getCI()->session->userdata($giveDistributeSessionKey);
        if ($giveDistribute) {
            $orderModel->saler_id = $params['saler'];
            $orderModel->saler_group = $params['saler_group'];
            $orderModel->fans_saler_id = $params['fans_saler'];
        } else {
            $orderModel->saler_id = 0;
            $orderModel->saler_group = 0;
            $orderModel->fans_saler_id = 0;
        }

        $orderModel->shipping= 0;
        $orderModel->product = $productArr;
        $orderModel->discount = $discountInfo;
        $orderModel->shipping = 0;
        $orderModel->killsec_instance = $params['inid'];

        //预留
        //$orderModel->hotel_id = $params['hotel_id'];
        $orderModel->hotel_id = $productArr[0]['hotel_id'];

        // 邮寄前置
        if (!empty($params['address_id'])
            && $productArr[0]['can_mail'] == \Product_package_model::STATUS_TRUE
        ) {
            $orderModel->extra = ['mail' => ['address_id' => $params['address_id']]];
        }


        // todo order_save要重新改造
        $orderModel->order_save($params['business'], $params['inter_id']);
        if (!$orderModel) {
            return new Result(Result::STATUS_FAIL, '下单失败，请稍后重新尝试');
        }

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'params' => $params,
                'orderModel' => $orderModel,
            ]
        );
        return $result;
    }

    /**
     * 订单创建完毕后操作
     *
     * @param      <type>  $beforeResult  订单创建前处理结果
     * @param      <type>  $orderResult   订单创建时处理结果
     *
     * @return     Result  订单创建完毕后的操作结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function afterCreate($beforeResult, $orderResult)
    {
        // 订单创建完毕后将直播信息写入session，支付回调后有用
        $this->writeZBSessionInfo($orderResult);

        // 下单成功，插入用户信息、秒杀、拼团、联系电话等信息
        $userResult = $this->handleOrderUserInfo($beforeResult, $orderResult);
        if ($userResult->getStatus() != Result::STATUS_OK) {
            return $userResult;
        }

        // 处理订单支付
        $paymentResult = $this->handleOrderPaymentInfo($beforeResult, $orderResult);
        if ($paymentResult->getStatus() != Result::STATUS_OK) {
            return $paymentResult;
        }
        $paymentData = $paymentResult->getData();

        Log::debug('after create payment data is ', $paymentData);

        $paymentInfo = $paymentData['paymentInfo'];

        $result = new Result(
            Result::STATUS_OK,
            '',
            [
                'paymentInfo' => $paymentInfo,
            ]
        );
        return $result;
    }

    /**
     * @param $orderResult
     */
    protected function writeZBSessionInfo($orderResult)
    {
        $orderData = $orderResult->getData();
        $orderModel = $orderData['orderModel'];
        $zbcode = $this->getCI()->session->tempdata('zbcode');
        $channel_id = $this->getCI()->session->tempdata('channelid');

        if ($zbcode && $channel_id) {
            $redis = $this->getCI()->get_redis_instance();
            $redis_key = $orderModel->get_zb_order_redis_key();
            $redis_value = ['zbcode' => $zbcode, 'channelid' => $channel_id];
            $redis->setex($redis_key, 3600, json_encode($redis_value));
        }
    }

    /**
     * 处理订单用户信息
     *
     * @param      Result $beforeResult 订单创建前处理结果
     * @param      Result $orderResult 订单创建时处理结果
     *
     * @return     Result  用户信息处理结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function handleOrderUserInfo($beforeResult, $orderResult)
    {
        $orderData = $orderResult->getData();
        $params = $orderData['params'];
        $orderModel = $orderData['orderModel'];
        $beforeData = $beforeResult->getData();

        // 拼团购买，插入拼团用户信息
        if ($params['settlement'] == 'groupon') {
            if (!empty($beforeData['activityInfo']['groupUserInsert'])
                && $beforeData['activityInfo']['groupUserInsert'] == true
            ) {
                $grouponDetial = $beforeData['activityInfo']['grouponDetial'];
                $groupModel = $beforeData['activityInfo']['model'];
                $userInfo = $beforeData['activityInfo']['userInfo'];
                $userInfo['order_id'] = $orderModel->m_get('order_id');
                $groupModel->groupon_user_add($grouponDetial['group_id'], $userInfo, $params['inter_id']);
            }
        }

        // 更新秒杀用户信息
        if ($params['settlement'] == 'killsec') {
            if (!empty($beforeData['activityInfo']['killsecUserInsert'])
                && $beforeData['activityInfo']['killsecUserInsert'] == true
            ) {
                //$killsecModel = $beforeData['activityInfo']['killsecModel'];
                $killsecModel = $beforeData['activityInfo']['model'];
                $killsecModel->update_user_by_filter(
                    $params['inter_id'],
                    [
                        'openid' => $params['openid'],
                        'instance_id' => $params['inid'],
                    ],
                    [
                        'order_id' => $orderModel->m_get('order_id'),
                        'status' => \Activity_killsec_model::USER_STATUS_ORDER,
                        'order_time' => date('Y-m-d H:i:s'),
                    ]
                );
            }
        }

        // 用户电话信息
        $contact['inter_id'] = $params['inter_id'];
        $contact['mobile'] = $params['phone'];
        $contact['name'] = $params['name'];
        $contact['openid'] = $params['openid'];
        $contact['create_time'] = date('Y-m-d H:i:s');
        $contact['order_id'] = $orderModel->m_get('order_id');
        if (!empty($contact['mobile'])) {
            $orderModel->save_customer_contact($contact, ['openid' => $params['openid']]);
        }

        return new Result(Result::STATUS_OK);
    }

    protected function handleOrderPaymentInfo($beforeResult, $orderResult)
    {
        $beforeData = $beforeResult->getData();
        $orderData = $orderResult->getData();
        $productArr = $beforeData['productArr'];
        $orderModel = $orderData['orderModel'];
        $params = $orderData['params'];

        $paymentInfo['payment_method'] = 'wx_pay';
        if ($productArr[0]['type'] == \Product_package_model::PRODUCT_TYPE_BALANCE) {
            $paymentInfo['payment_method'] = 'balance_pay';
        }
        if ($productArr[0]['type'] == \Product_package_model::PRODUCT_TYPE_POINT) {
            $paymentInfo['payment_method'] = 'point_pay';
        }
        // 威富通支付？？ wft_pay
        if (!empty($params['wft_inter_ids'])
            && in_array($params['inter_id'], $params['wft_inter_ids'])
        ) {
            $paymentInfo['payment_method'] = 'wft_pay';
        }

        // 直接支付
        $this->getCI()->load->model('soma/Sales_payment_model');
        if ($orderModel->m_get('grand_total') < 0.005) {
            $paymentInfo['payment_method'] = 'already_pay';
            $pay_res['paid_type'] = \Sales_payment_model::PAY_TYPE_HD;
            $paymentResult = $this->_inner_payment($orderModel, $pay_res, false);
            if ($paymentResult->getStatus() != Result::STATUS_OK) {
                return $paymentResult;
            }
        }

        // 储值支付
        if ($paymentInfo['payment_method'] == 'balance_pay') {
            $balanceResult = $this->balance_pay($orderModel, $params);
            if ($balanceResult->getStatus() != Result::STATUS_OK) {
                return $balanceResult;
            }
            $pay_res['paid_type'] = \Sales_payment_model::PAY_TYPE_CZ;
            $paymentResult = $this->_inner_payment($orderModel, $pay_res);
            if ($paymentResult->getStatus() != Result::STATUS_OK) {
                return $paymentResult;
            }
        }

        // 积分支付
        if ($paymentInfo['payment_method'] == 'point_pay') {
            $pointResult = $this->point_pay($orderModel, $params);
            if ($pointResult->getStatus() != Result::STATUS_OK) {
                return $pointResult;
            }
            $pay_res['paid_type'] = \Sales_payment_model::PAY_TYPE_JF;
            $paymentResult = $this->_inner_payment($orderModel, $pay_res);
            if ($paymentResult->getStatus() != Result::STATUS_OK) {
                return $paymentResult;
            }
        }

        return new Result(Result::STATUS_OK, '', ['paymentInfo' => $paymentInfo]);
    }

    /**
     * 储值支付
     *
     * @param      Sales_order_model $order 订单实例
     * @param      array $params 下单参数
     *
     * @return     Result             支付结果
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function balance_pay($order, $params)
    {

        try {
            $inter_id = $order->m_get('inter_id');
            $open_id = $order->m_get('openid');
            $passwd = $params['bpay_passwd'];
            $order_id = $order->m_get('order_id');

            $this->getCI()->load->library('Soma/Api_member');

            $api = new \Api_member($inter_id);
            $result = $api->get_token();
            $api->set_token($result['data']);

            $balance_info = null;
            $balance_info = $api->balence_info($open_id);
            $balance = isset($balance_info['data']) ? $balance_info['data'] : 0;
            if ($balance < $order->m_get('grand_total')) {
                return new Result(Result::STATUS_FAIL, '储值余额不足！');
            }

            $scale = $api->balence_scale($open_id);
            $pay_total = $api->balence_scale_convert($scale, $order->m_get('grand_total'), FALSE);
            $uu_code = rand(1000, 9999);

            $use_result['err'] = 1; // 默认调用失败
            $yinju_inter_ids = ['a457946152', 'a471258436'];
            if (in_array($inter_id, $yinju_inter_ids)) {
                $use_result = (array)$api->yinju_balence_use($pay_total, $open_id, $passwd, $uu_code, $order_id);
            } else {
                $use_result = (array)$api->balence_use($pay_total, $open_id, $passwd, $uu_code, $order_id);
            }

            if (!isset($use_result['err']) || $use_result['err'] != 0) {
                return new Result(Result::STATUS_FAIL, '支付失败，请稍后重新尝试下单操作');
            }
            return new Result(Result::STATUS_OK);
        } catch (Exception $e) {
            // 日志
        }

        return new Result(Result::STATUS_FAIL, '订单信息错误！');
    }

    protected function point_pay($order, $params)
    {
        try {

            $inter_id = $order->m_get('inter_id');
            $open_id = $order->m_get('openid');
            $order_id = $order->m_get('order_id');

            $this->getCI()->load->library('Soma/Api_member');

            $api = new \Api_member($inter_id);
            $result = $api->get_token();
            $api->set_token($result['data']);

            $point_info = null;
            $point_info = $api->point_info($open_id);
            $point = isset($point_info['data']) ? $point_info['data'] : 0;
            if ($point < $order->m_get('grand_total')) {
                return new Result(Result::STATUS_FAIL, '积分余额不足！');
            }

            $uu_code = rand(1000, 9999);
            // 积分支付必须是整数，上取整
            $pay_total = ceil($order->m_get('grand_total'));
            $pay_res = $api->point_use($pay_total, $open_id, $uu_code, $order_id, $order);

            if (!isset($pay_res['err']) || $pay_res['err'] != 0) {
                return new Result(Result::STATUS_FAIL, '支付失败，请稍后重新尝试下单操作');
            }
            return new Result(Result::STATUS_OK);
        } catch (\Exception $e) {
            // 日志
        }

        return new Result(Result::STATUS_FAIL, '订单信息错误！');
    }

    /**
     * @param $order
     * @param $payment
     * @param bool $save_flag
     * @return Result
     */
    protected function _inner_payment($order, $payment, $save_flag = true)
    {
        try {
            $log_data['paid_ip'] = Tool::getUserIP();
            $log_data['paid_type'] = $payment['paid_type'];
            $log_data['order_id'] = $order->m_get('order_id');
            $log_data['openid'] = $order->m_get('openid');
            $log_data['business'] = $order->m_get('business');
            $log_data['settlement'] = $order->m_get('settlement');
            $log_data['inter_id'] = $order->m_get('inter_id');
            $log_data['hotel_id'] = $order->m_get('hotel_id');
            $log_data['grand_total'] = $order->m_get('grand_total');
            $log_data['transaction_id'] = isset($payment['trans_id']) ? $payment['trans_id'] : '';

            $order->order_payment($log_data);
            $order->order_payment_post($log_data);

            if ($save_flag) {
                $this->getCI()->load->model('soma/Sales_payment_model', 'pay_model');
                $this->getCI()->pay_model->save_payment($log_data);
            }
            return new Result(Result::STATUS_OK);
        } catch (\Exception $e) {
            // 日志
        }

        return new Result(Result::STATUS_FAIL, '支付失败，请稍后重新尝试下单操作');
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
        $callback_func = function ($data) {
            $result = new Result();
            $result->setStatus(Result::STATUS_OK);
            $result->setData($data);
            return $result;
        };
        $this->getCI()->load->model('soma/sales_order_model', 'somaSalesOrderModel');
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');
        $this->getCI()->load->model('soma/Consumer_code_model', 'consumer_code_model');

        /** @var \Sales_order_model $somaSalesOrderModel */
        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        /** @var \Sales_item_package_model $Sales_item_package_model */
        $Sales_item_package_model = $this->getCI()->salesItemPackageModel;


        /** @var \Consumer_code_model $consumer_code_model */
        $consumer_code_model = $this->getCI()->consumer_code_model;

        $condition = [
            'and openid =' => $openid,
            'and status =' => \Sales_order_model::STATUS_PAYMENT, // 购买成功
            'and del_time =' => function () {
                return 0;
            }, // 未删除
        ];

        $table_name = $Sales_item_package_model->table_name();
        $table_name = $this->getCI()->soma_db_conn_read->dbprefix($table_name);
        $soma_sales_order_table_name = $this->getCI()->soma_db_conn_read->dbprefix($somaSalesOrderModel->table_name());

        if ($type == 2) { // 未使用
            $condition['and consume_status !='] = 23; // 21 未消费 22 部分消费 23 全部消费

            $condition['and (select expiration_date from ' . $table_name . ' as p where ' . $soma_sales_order_table_name . '.order_id = p.order_id limit 1) >'] = date('Y-m-d H:i:s'); // 过期时间：未过期

            $condition['and refund_status ='] = 31; // 31 无退款 32 部分退款 33 全部退款
        }
        if ($type == 3) { // 已完成

            $condition['and refund_status = '] = 31; // 31 无退款 32 部分退款 33 全部退款

            $condition[''] = function () use ($table_name, $soma_sales_order_table_name) {
                return implode('', [
                    'and ',
                    '(',
                    'consume_status = 23', // 21 未消费 22 部分消费 23 全部消费
                    ' or ',
                    '((select expiration_date from ' . $table_name . '  as p where ' . $soma_sales_order_table_name . '.order_id = p.order_id limit 1) < "' . date('Y-m-d H:i:s') . '")',  // 过期时间：已过期
                    ')',
                ]);
            };

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

        // 订单细单
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


        //退款进度
        /** @var \Sales_refund_model  $sales_refund_model */
        $this->getCI()->load->model('soma/Sales_refund_model', 'Sales_refund_model');
        $sales_refund_model = $this->getCI()->Sales_refund_model;
        $sales_refund_list = $sales_refund_model->get(['order_id', 'inter_id'], [$orderIDMap, $this->getCI()->inter_id]);

        // 资产
        $asset_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($Sales_item_package_model->asset_item_table_name('package'));
        $code_condition = [
            'order_id' => $orderIDMap,
            'status' => [2, 3, 4],
            function () use ($asset_item_table_name, $openid) {
                return 'asset_item_id in (select item_id from ' . $asset_item_table_name . ' where openid =  "' . $openid . '" )';
            },
        ];
        $code_map = $consumer_code_model->get(array_keys($code_condition), array_values($code_condition), [
            'order_id',
            'count(if(status=2, 1, 0)) as use_num',
            'count(status) as total_num',
        ], [
            'limit' => null,
            'groupBy' => 'order_id'
        ]);


        foreach ($order as $key => $value) {
            foreach ($item_map as $item_key => $item_val) {
                if ($item_val['order_id'] == $value['order_id']) {
                    $order[$key]['package'][] = $item_val;
                }
            }
            foreach ($code_map as $code_key => $code_val) {
                if ($code_val['order_id'] == $value['order_id']) {
                    $order[$key]['code'] = $code_val;
                }
            }
            $order[$key]['refund_info_status'] = 0;
            if(!empty($sales_refund_list)){
                foreach ($sales_refund_list as $vale){
                   if($vale['order_id'] == $value['order_id']){
                       $order[$key]['refund_info_status'] = $vale['status'];
                       break;
                   }
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
        $this->getCI()->load->model('soma/Sales_order_model', 'somaSalesOrderModel');
        $this->getCI()->load->model('soma/sales_item_package_model', 'salesItemPackageModel');
        $this->getCI()->load->model('soma/Consumer_code_model', 'consumer_code_model');
        $this->getCI()->load->model('soma/Consumer_shipping_model', 'consumer_shipping_model');
        $this->getCI()->load->model('soma/Consumer_order_model', 'consumer_order_model');
        $this->getCI()->load->model('soma/Gift_order_model', 'soma_gift_order');
        $this->getCI()->load->model('soma/Gift_item_package_model','Gift_item_package_model');
        $this->getCI()->load->model('soma/Sales_order_product_record_model', 'Sales_order_product_record_model');
        $this->getCI()->load->model('soma/Product_package_model', 'Product_package_model');
        $this->getCI()->load->model('soma/Sales_refund_model', 'Sales_refund_model');
        $this->getCI()->load->model('soma/Asset_item_package_model', 'Asset_item_package_model');

        /** @var \Sales_order_model $somaSalesOrderModel */
        $somaSalesOrderModel = $this->getCI()->somaSalesOrderModel;
        $salesItemPackageModel = $this->getCI()->salesItemPackageModel;
        /** @var \Consumer_code_model $consumer_code_model */
        $consumer_code_model = $this->getCI()->consumer_code_model;
        $consumer_shipping_model = $this->getCI()->consumer_shipping_model;
        /** @var \Gift_order_model $soma_gift_order */
        $soma_gift_order = $this->getCI()->soma_gift_order;
        /** @var \Gift_item_package_model $gift_item_package_model */
        $gift_item_package_model = $this->getCI()->Gift_item_package_model;
        /** @var \Product_package_model $product_package_model */
        $product_package_model = $this->getCI()->Product_package_model;
        /** @var \Sales_refund_model  $sales_refund_model */
        $sales_refund_model = $this->getCI()->Sales_refund_model;
        /** @var \Asset_item_package_model $asset_item_package_model */
        $asset_item_package_model = $this->getCI()->Asset_item_package_model;
        /** @var \Consumer_order_model $consumer_order_model */
        $consumer_order_model = $this->getCI()->consumer_order_model;


        $Sales_order_product_record_model = $this->getCI()->Sales_order_product_record_model;

        // order 相关
        $condition = ['order_id' => $oid, 'del_time' => 0, 'openid' => $this->getCI()->openid];
        $order = $somaSalesOrderModel->get(array_keys($condition), array_values($condition), [
            'order_id',
            'create_time',
            'item_name',
            'real_grand_total',
            'grand_total',
            'row_qty',
            'status',
            'refund_status',
            'consume_status',
            'settlement'
        ]);

        if (empty($order)) {
            return $callback_func([], Result::STATUS_FAIL, '订单不存在');
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
            'goods_type',
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
            '(select (img_detail) from ' . $order_product_record_table_name . ' as r where r.order_id = ' . $item_package_table_name . '.order_id  limit 1) as img_detail',
            '(select (compose) from ' . $order_product_record_table_name . ' as r where r.order_id = ' . $item_package_table_name . '.order_id  limit 1) as compose',
        ], ['limit' => null]);


        if (empty($item_map)) {
            return $callback_func([], Result::STATUS_FAIL, 'order package not in');
        }

        foreach ($item_map as $key => $val) {
            $item_map[$key]['compose'] = isset($val['compose']) && !empty($val['compose']) ? unserialize($val['compose']) : '';
            $item_map[$key]['name'] = isset($val['name']) && !empty($val['name']) ? strip_tags($val['name']) : '';
//            $item_map[$key]['img_detail'] = isset($val['img_detail']) && !empty($val['img_detail']) ? htmlspecialchars($val['img_detail']) : '';
//            $item_map[$key]['order_notice'] = isset($val['order_notice']) && !empty($val['order_notice']) ? htmlspecialchars($val['order_notice']) : '';
            $product = $product_package_model->get(['product_id'], [$val['product_id']]);
            if(!empty($product)){
                //积分、储值商品不显示退款
               if(in_array($product[0]['type'], [$product_package_model::PRODUCT_TYPE_BALANCE, $product_package_model::PRODUCT_TYPE_POINT])){
                   $item_map[$key]['can_refund'] = (string)$product_package_model::CAN_F;
               }
               $item_map[$key]['type'] = $product[0]['type'];
               //特权券商品，不能订房
                if(in_array($product[0]['type'], [$product_package_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER])){
                    $item_map[$key]['can_wx_booking'] = (string)$product_package_model::CAN_F;
                }
            }
        }

        $order['package'] = $item_map;

        $shipping_table_name = $this->getCI()->soma_db_conn_read->dbprefix($consumer_shipping_model->table_name());
        $code_table_name = $this->getCI()->soma_db_conn_read->dbprefix($consumer_code_model->table_name());
        $gift_table_name = $this->getCI()->soma_db_conn_read->dbprefix($soma_gift_order->table_name('package'));
        $gift_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($soma_gift_order->item_table_name('package'));
        $asset_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->asset_item_table_name('package'));

//        $code_condition = [
//            'order_id' => $oid,
//            'status' => [2, 3, 4],
//            function () use ($asset_item_table_name, $openid) {
//                return 'asset_item_id in (select item_id from ' . $asset_item_table_name . ' where openid =  "' . $openid . '" )';
//            },
//        ];

        $name = $order['item_name'];

//        $consumer_order = $consumer_code_model->get(array_keys($code_condition), array_values($code_condition), [
//            str_replace(
//                ['%shipping%', '%code%'],
//                [$shipping_table_name, $code_table_name],
//                'if(status=3,IFNULL((select shipping_id from %shipping% as s where s.order_id =  %code%.order_id and s.inter_id = %code%.inter_id and s.consumer_id = %code%.consumer_id) , 0), 0) as shipping_id'
//            ),
//            'if(status=4,(select gift_id from ' . $gift_item_table_name . ' as s1 where s1.asset_item_id = ' . $code_table_name . '.asset_item_id and name = "' . $name . '" and qty > 0 limit 1),0) as gid',
//            'if(status=4,(select send_from from ' . $gift_table_name . ' as s2 where s2.gift_id =  gid),0) as send_from',
//            'order_id',
//            'code',
//            'status',
//            'asset_item_id',
//            'code_id',
//            'consumer_item_id',
//            'code_id',
//        ], ['limit' => null, 'orderBy' => 'status asc']);



        //券码
        $consumer_order = [];
        $asset_item_id = [0];
        $asset_item_package_list = $asset_item_package_model->get(['openid', 'order_id'], [$openid, $oid], ['item_id'], ['limit' => null]);
        if(!empty($asset_item_package_list)){
            $asset_item_id = array_column($asset_item_package_list, 'item_id');
        }
        $consumer_code_list = $consumer_code_model->get(
            ['order_id', 'status', 'asset_item_id'],
            [$oid, [2, 3, 4], $asset_item_id],
            ['order_id', 'code', 'status', 'asset_item_id', 'code_id', 'consumer_item_id', 'consumer_id'],
            ['limit' => null, 'orderBy' => 'status asc']
        );

        if(!empty($consumer_code_list)){
            foreach ($consumer_code_list as $key => $val){
                $consumer_order[$key]['shipping_id'] = 0;
                $consumer_order[$key]['gid'] = 0;
                $consumer_order[$key]['send_from'] = 0;
                $consumer_order[$key]['order_id'] = $val['order_id'];
                $consumer_order[$key]['code'] = $val['code'];
                $consumer_order[$key]['status'] = $val['status'];
                $consumer_order[$key]['asset_item_id'] = $val['asset_item_id'];
                $consumer_order[$key]['code_id'] = $val['code_id'];
                $consumer_order[$key]['consumer_item_id'] = $val['consumer_item_id'];
                $consumer_order[$key]['is_booking_hotel'] = false;
                //$consumer_order[$key]['consumer_id'] = $val['consumer_id'];
                if($val['status'] == 3){
                    //shipping_id
                    $consumer_shipping_list = $consumer_shipping_model->get(
                        ['order_id', 'inter_id', 'consumer_id'],
                        [$oid, $this->getCI()->inter_id, $val['consumer_id']],
                        ['shipping_id']
                    );
                    if(!empty($consumer_shipping_list)){
                        $consumer_order[$key]['shipping_id'] = $consumer_shipping_list[0]['shipping_id'];
                    }
                }
                if($val['status'] == 4){
                    //gid
                    $gift_item_package_list = $gift_item_package_model->get(
                        ['asset_item_id', 'name', 'qty > '],
                        [$val['asset_item_id'], $name, 0],
                        '*',
                        ['limit' => null]
                    );
                    if(!empty($gift_item_package_list)){
                        $gift_id = isset($consumer_order[$key - 1]['gid']) ? $consumer_order[$key - 1]['gid'] : 0;
                        foreach ($gift_item_package_list as $items => $values){
                            if($values['gift_id'] != $gift_id){
                                $gift_id = $values['gift_id'];
                                break;
                            }
                        }
                        $consumer_order[$key]['gid'] = $gift_id;
                    }
                    //send_from
                    $gift_order_list = $soma_gift_order->get(
                        ['gift_id'],
                        [$consumer_order[$key]['gid']]
                    );
                    if(!empty($gift_order_list)){
                        $consumer_order[$key]['send_from'] = $gift_order_list[0]['send_from'];
                    }
                }
                $consumer_order_info = $consumer_order_model->get(['consumer_id'], [$val['consumer_id']]);
                if(!empty($consumer_order_info)){
                    if( $consumer_order_info[0]['consumer_method'] == $consumer_order_model::CONSUME_HOTEL_SELF ){
                        $consumer_order[$key]['is_booking_hotel'] = true;
                    }
                }
            }
        }


        // 卷码相关
        $this->getCI()->load->helper('encrypt');
        $encrypt_util = new \Encrypt();
        foreach ($consumer_order as $key => &$_con_order) {
            $_con_order['qrcode_url'] = '';
            if ($_con_order['status'] == \Consumer_code_model::CAN_REFUND_STATUS_FAIL) {

                // 全部退款 && 卷码有效的
                if ($order['refund_status'] == 33) {
                    unset($consumer_order[$key]);
                    continue;
                }

                $content = $encrypt_util->encrypt($_con_order['code']);
                $length = $encrypt_util->encrypt(strlen($_con_order['code']));
                // 二维码地址
                $_con_order['qrcode_url'] = site_url('soma/api/get_consume_qrcode') . '?' . http_build_query(array('code' => base64_encode($content), 'valid' => base64_encode($length)));
            }
        }

        // 公众号名称
        $public_info = $this->getCI()->public_info;
        $result['public']['name'] = $public_info['name'];

        //退款表
        $refund_info_status = 0;
        if($order['settlement'] != $somaSalesOrderModel::SETTLE_HOTEL_GIFT){
            $sales_refund_info = $sales_refund_model->get(['order_id', 'inter_id'], [$oid, $inter_id]);
            if(!empty($sales_refund_info)){
                $refund_info_status = $sales_refund_info[0]['status'];
            }
        }
        $order['refund_info_status'] = (string)$refund_info_status;

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
            '(select expiration_date from ' . $item_package_table_name . ' as i where i.order_id = ' . $oid . '  limit 1) as expiration_date',
        ]);

        foreach ($order as $item) {
            if ($item['consume_status'] != $somaSalesOrderModel::CONSUME_ALL) {
                return $callback_func([], Result::STATUS_FAIL, '未消费完毕，删除失败');
            }
            if (strtotime($item['expiration_date']) < time()) {
                return $callback_func([], Result::STATUS_FAIL, '订单未过期，删除失败');
            }
            if ($item['refund_status'] != $somaSalesOrderModel::REFUND_PENDING) {
                return $callback_func([], Result::STATUS_FAIL, '退款订单，删除失败');
            }
        }

        $result = $somaSalesOrderModel->_shard_db()->update($somaSalesOrderModel->table_name(), ['del_time' => time()], ['order_id' => $oid, 'del_time' => 0], 1);
        if ($somaSalesOrderModel->_shard_db()->affected_rows()) {
            return $callback_func();
        }
        return $callback_func([], Result::STATUS_FAIL, '删除失败，请稍后重试');
    }

    /**
     * 预约 || 验卷
     * @param $aiid
     * @param $openid
     * @param $inter_id
     * @param $code_id
     * @return Result
     */
    public function getPackageInfo($aiid, $openid, $inter_id, $code_id)
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

        // 解析
        if (isset($result['product']['compose']) && $result['product']['compose'][strlen($result['product']['compose']) - 1] === '}') {
            $result['product']['compose'] = unserialize($result['product']['compose']);
        } else {
            $result['product']['compose'] = [];
        }

        // 卷码
        /** @var \Consumer_code_model $consumer_code_model */
        $consumer_code_model = $this->getCI()->consumer_code_model;


        $asset_item_table_name = $this->getCI()->soma_db_conn_read->dbprefix($salesItemPackageModel->asset_item_table_name('package'));

        $code_condition = [
            'asset_item_id' => $aiid,
            'status' => [2, 3], // 默认已使用的卷码也会调用，需要增加状态：3 已使用
            'inter_id' => $inter_id,
            'code_id' => $code_id,
            function () use ($asset_item_table_name, $openid) {
                return 'asset_item_id in (select item_id from ' . $asset_item_table_name . ' where openid =  "' . $openid . '" )';
            },
        ];

        // 默认有效的卷码
        $codeModel = $consumer_code_model->get(array_keys($code_condition), array_values($code_condition), [
            'code',
            'status',
        ], ['orderBy' => 'status asc']);

        if (empty($codeModel)) {
            return $callback_func([], Result::STATUS_FAIL, 'code not in');
        }

        // 卷码相关
        $this->getCI()->load->helper('encrypt');
        $encrypt_util = new \Encrypt();
        foreach ($codeModel as &$_con_order) {
            $_con_order['qrcode_url'] = '';
             // if ($_con_order['status'] == \Consumer_code_model::CAN_REFUND_STATUS_FAIL) { 未使用
                $content = $encrypt_util->encrypt($_con_order['code']);
                $length = $encrypt_util->encrypt(strlen($_con_order['code']));
                // 二维码地址
                $_con_order['qrcode_url'] = site_url('soma/api/get_consume_qrcode') . '?' . http_build_query(array('code' => base64_encode($content), 'valid' => base64_encode($length)));
            // }
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
            $result->setData($data);
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


        $result['data'] = $order;

        // 公众号名称
        $public_info = $this->getCI()->public_info;
        $result['public']['name'] = $public_info['name'];

        return $callback_func($result);
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
            $result->setData(['room_list' => $data]);
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

        $packageService = PackageService::getInstance()->getParams();

        //搜索
        $hotelInfoList = [];
        foreach ($wx_booking_config as $key => $item) {
            $temp = $item;
            unset($temp['room_ids']);
            if(!empty($item['room_ids'])){
                foreach ($item['room_ids'] as $room_key => $roomMap) {
                    if(isset($roomMap['name'])){
                        $has = true;
                        $room_map = $roomMap;
                        unset($room_map['price_codes']);
                        $price_codes = [];
                        if(isset($roomMap['price_codes']) && !empty($roomMap['price_codes'])){
                            foreach ($roomMap['price_codes'] as $vale){
                                $price_codes[] = $vale;
                            }
                        }
                        if($search){
                            if(!isset($roomMap['name']) || !strstr($roomMap['name'], $search)){
                                $room_map = [];
                                $price_codes = [];
                                $has = false;
                            }
                        }
                        if($has){
                            $room_map['price_codes'] = $price_codes;
                            $temp['room_ids'][] = $room_map;
                        }
                    }

                }
            }

            $hotelInfoList[] = $temp;
        }

        $result = [];
        if(!empty($hotelInfoList)){
            foreach ($hotelInfoList as $val){
                if(!empty($val['room_ids'])){
                    foreach ($val['room_ids'] as $vale){
                        if(!empty($vale['price_codes'])){
                            foreach ($vale['price_codes'] as $value){
                                $result[] = [
                                    'id' => $val['inter_id'],
                                    'name' => $val['name'],
                                    'address' => $val['address'],
                                    'latitude' => $val['latitude'],
                                    'longitude' => $val['longitude'],
                                    'room_name' => $vale['name'],
                                    'room_cover' => $vale['room_img'],
                                    'room_price_code' => $value['price_code'],
                                    'room_price_name' => $value['price_name'],
                                    'link' => site_url('soma/booking/select_hotel_time').'?id='.$val['inter_id'].'&bsn=package&hid='.$val['hotel_id'].'&aiid='.$aiid.'&oid='.$oid.'&rmid='.$vale['room_id'].'&cdid='.$value['price_code'].'&tkid='.$packageService['tkid'].'&brandname='.$packageService['brandname'].'&layout='.$packageService['layout'].'&code_id='
                                ];
                            }
                        }

                    }
                }
            }
        }

        return $callback_func($result);
    }

    /**
     * 微信订房 - 价格日历 - 信息
     * @param $assetItemId
     * @param $codeId
     * @param $interId
     * @param $openid
     * @param $hotelId
     * @param $roomId
     * @param $priceCode
     * @return Result
     */
    public function getSelectHotelInfo($assetItemId, $codeId, $hotelId, $roomId, $priceCode)
    {
        $callback_func = function ($data = [], $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData($data);
            return $result;
        };

        $interId = $this->getCI()->inter_id;
        $openid = $this->getCI()->openid;

        // 获取卷码
        $this->getCI()->load->model('soma/Consumer_code_model', 'CodeModel');
        /** @var \Consumer_code_model $CodeModel */
        $CodeModel = $this->getCI()->CodeModel;
        //$limit = isset($item['qty']) ? $item['qty'] : 1;
        //$filter = array();
        //$filter['status'] = $CodeModel::STATUS_SIGNED;//取出没有消费的
        //$codes = $CodeModel->get_code_by_assetItemIds(array($assetItemId), $interId, $filter, $limit);
        //$code = isset($codes[$aiidi]['code']) ? $codes[$aiidi]['code'] : ''; //

        // 获取联系人和电话
        $filter = array();
        $filter['openid'] = $openid;
        $return['customer'] = $CodeModel->get_customer_contact($filter);

        // 获取酒店名称
        $this->getCI()->load->model('hotel/hotel_model', 'somaHotelModel');
        /** @var \Hotel_model $somaHotelModel */
        $somaHotelModel = $this->getCI()->somaHotelModel;
        $hotelInfo = $somaHotelModel->get_hotel_detail($interId, $hotelId);

        $return['hotel'] = ['name' => null, 'hotel_id' => null];
        if ($hotelInfo) {
            $return['hotel']['name'] = $hotelInfo['name'];
            $return['hotel']['hotel_id'] = $hotelInfo['hotel_id'];
        }

        // 获取房型名称
        $this->getCI()->load->model('hotel/Rooms_model', 'Rooms_model');
        /** @var \Rooms_model $Rooms_model */
        $Rooms_model = $this->getCI()->Rooms_model;
        $room = $Rooms_model->find([
            'room_id' => $roomId,
            'inter_id' => $interId
        ], '*');
        if(empty($room)){
            return $callback_func($return, Result::STATUS_FAIL, '房间不存在');
        }
        $return['room'] = $room;

        // 获取价格代码名称
        $this->getCI()->load->model('hotel/Price_code_model', 'Price_code_model');
        /** @var \Price_code_model $Price_code_model */
        $Price_code_model = $this->getCI()->Price_code_model;
        $price_code = $Price_code_model->get_room_price_code($interId, $hotelId, $roomId, $priceCode, 1);
        $return['price_code'] = [];
        if (isset($price_code, $price_code[0])) {
            $return['price_code']['price_name'] = $price_code[0]['price_name'];
            $return['price_code']['price_code'] = $price_code[0]['price_code'];
        }

        $orderId = null;
        $begin_date = null;
        $end_date = null;
        $consumer_code = null;

        //获取订单信息
        $codeInfo = $CodeModel->get(['asset_item_id'], [$assetItemId]);
        if($codeInfo){
            $this->getCI()->load->model('soma/Sales_item_package_model', 'orderItemModel');
            $orderItemModel = $this->getCI()->orderItemModel;
            $orderId = $codeInfo[0]['order_id'];
            $orderItemInfo = $orderItemModel->get(['order_id'], $orderId);
            if($orderItemInfo){
                $begin_date = date('Y/m/d', strtotime($orderItemInfo[0]['validity_date']));
                $end_date = date('Y/m/d', strtotime($orderItemInfo[0]['expiration_date']));
            }
        }

        //房价码
        $this->getCI()->load->model('soma/Consumer_code_model', 'consumerCodeModel');
        $consumerCodeModel = $this->getCI()->consumerCodeModel;
        $consumerItem = $consumerCodeModel->get(['order_id'], [$orderId]);
        if(empty($consumerItem)){
            return $callback_func($return, Result::STATUS_FAIL, '券码不存在');
        }
        $consumer_code = $consumerItem[0]['code'];

        //附加参数
        $return['attach'] = [
            'current_date' => time(),
            'code_use_date' => [
                'begin_date' => $begin_date,
                'end_date' => $end_date,
            ],
            'booking_date' => [
                'begin_date' => date('Y/m/01'),
                'end_date' => date('Y/m/d', strtotime(date('Y/m/01')." +3 month -1 day")),
            ],
            'order_params' => [
                'post_hotel_id' => $hotelId,
                'post_room_id' => $roomId,
                'post_price_code' => $priceCode,
                'post_name' => null,
                'post_phone' => null,
                'post_start' => null,
                'post_end' => null,
                'post_room_name' => $room['name'],
                'post_code_name' => !empty($price_code) ? $price_code[0]['price_name'] : null,
                'post_num' => 1,
                'post_order_id' => $orderId,
                'aiid' => $assetItemId,
                'post_code' => $consumer_code
            ]
        ];

        $return['code']['code'] = $consumer_code;

        return $callback_func($return);
    }

    /**
     * 微信订房 - 价格日历 - 日历可预订时间
     * @param $orderId
     * @param $hotelId
     * @param $roomId
     * @param $priceCode
     * @param $year
     * @param $month
     * @param $interId
     * @param $openid
     * @return Result
     */
    public function getSelectHotelTime($orderId, $hotelId, $roomId, $priceCode, $year, $month, $interId, $openid)
    {
        $callback_func = function ($data, $status = Result::STATUS_OK, $msg = '') {
            $result = new Result();
            $result->setStatus($status);
            $result->setMessage($msg);
            $result->setData(['data' => $data]);
            return $result;
        };

        // 补全月份
        $month = sprintf('%02s', $month);
        $year_now = date('Y');
        $month_now = date('m');
        $start = $year . $month . '01';
        $end = date("Ym01", strtotime($start . " +1 month")); //结束时间都以下个月1号为结束

        $this->getCI()->load->library('Soma/Api_hotel');
        $ApiModel = new \Api_hotel($interId);

        // can_booking 1 可订 2 不可订 3 满房

        //过去的时间就不发起订房拉取时间了，全部不可选
        if ($year_now > $year || ($year_now == $year && $month_now > $month)) {
            $rooms_un_can_booking = $ApiModel->get_un_booking($start);
            $roomMap = collect($rooms_un_can_booking)->map(function ($item, $key) {
                $item['can_booking'] = \Api_hotel::CAN_BOOKING_FALSE; // 不可订
                return $item;
            })->all();
            return $callback_func($roomMap, Result::STATUS_OK, '过去的时间不可选！');
        }

        if ($year == $year_now && $month == $month_now) {
            $start = date('Ymd');
        }

        if (!$hotelId || !$roomId || !$priceCode || !$interId || !$year || !$month) {
            $rooms_un_can_booking = $ApiModel->get_un_booking($start);
            $roomMap = collect($rooms_un_can_booking)->map(function ($item, $key) {
                $item['can_booking'] = \Api_hotel::CAN_BOOKING_FALSE; // 不可订
                return $item;
            })->all();
            return $callback_func($roomMap, Result::STATUS_OK, '参数不全，把这个月都变成不可选');
        }

        //调取订房时间接口
        $ApiModel->_write_log('ajax获取订房时间开始。inter_id：' . $interId . ' order_id：' . $orderId, 'start：soma/booking/ajax_get_time');
        $result = $ApiModel->get_rooms($openid, $interId, $hotelId, $roomId, $priceCode, $start, $end);
        $ApiModel->_write_log('ajax获取订房时间结束。inter_id：' . $interId . ' order_id：' . $orderId, 'end：soma/booking/ajax_get_time');

        // 接口返回错误信息
        if (isset($result['message']) && !empty($result['message'])) {
            $rooms_un_can_booking = $ApiModel->get_un_booking($start);
            $roomMap = collect($rooms_un_can_booking)->map(function ($item, $key) {
                $item['can_booking'] = \Api_hotel::CAN_BOOKING_FALSE; // 不可订
                return $item;
            })->all();
            return $callback_func($roomMap, Result::STATUS_OK, '接口获取数据失败，把这个月都变成不可选');
        }

        //现在是不管返回状态，都有返回数据
        //如果没有返回$return['data']['rooms']信息，那么默认全部不可订
        if (isset($result['data'], $result['data']['rooms']) && !empty($result['data']['rooms'])) {
            $rooms_un_can_booking = isset($result['data']['rooms']['un_can_booking'])
                ? $result['data']['rooms']['un_can_booking']
                : array();

            $room_can_booking = isset($result['data']['rooms']['can_booking'])
                ? $result['data']['rooms']['can_booking']
                : array();

            $roomMap = array_merge($room_can_booking, $rooms_un_can_booking);

            return $callback_func($roomMap, Result::STATUS_OK, '');
        } else {
            $rooms_un_can_booking = $ApiModel->get_un_booking($start);
            $roomMap = collect($rooms_un_can_booking)->map(function ($item, $key) {
                $item['can_booking'] = \Api_hotel::CAN_BOOKING_FALSE; // 不可订
                return $item;
            })->all();
            return $callback_func($roomMap, Result::STATUS_OK, '接口获取数据失败，把这个月都变成不可选');

        }
    }

    /**
     * 微信订房 - 下单
     * @param array $params
     * @return Result
     */
    public function getBooking($params = [])
    {

        $interId = $params['interId'];
        $openid = $params['openid'];
        $business = $params['business'];
        $orderId = $params['orderId']; // 订单ID
        $hotelId = $params['hotelId']; // 酒店ID
        $roomId = $params['roomId']; // 房间ID
        $priceCode = $params['priceCode']; // 房价码
        $code = $params['code']; // 卷码
        $name = $params['name'];
        $phone = $params['phone'];
        $start = $params['start']; // 订房开始时间
        $end = $params['end']; // 订房结束时间
        $room_name = $params['room_name']; // 房型名称
        $code_name = $params['code_name']; // 价格代码名称
        $assetItemId = $params['assetItemId'];
        //$aiidi = $params['aiidi'];
        $num = $params['num']; // 选择多少间


        //参考旧商城及进行预订操作，此处不应做校验，否则将会永远预订不了
        // 资产 and 卷码校验
        // yhdsir
//        $this->getCI()->load->model('soma/Consumer_order_model', 'Consumer_order_model');
//        $Consumer_order_model = $this->getCI()->Consumer_order_model;
//        $Consumer_order_table_name = $this->getCI()->soma_db_conn_read->dbprefix($Consumer_order_model->item_table_name($business));
//        $consumer_order_condition = [
//            'inter_id' => $interId,
//            'consumer_code' => $code,
//            'asset_item_id' => $assetItemId,
//        ];
//
//        $consumer_code_map = $Consumer_order_model->get(array_keys($consumer_order_condition), array_values($consumer_order_condition), [
//            'status',
//            'name'
//        ], ['table_name' => $Consumer_order_table_name]);
//
//        if (empty($consumer_code_map)) {
//            return new Result(Result::STATUS_FAIL, '券码信息不存在');
//        }
//        $consumer_code_map = $consumer_code_map[0];
//        if ($consumer_code_map['status'] != 2) {
//            return new Result(Result::STATUS_FAIL, sprintf('券码[%s]已经消费', $code));
//        }



        //组装回跳选时间的链接
//        $select_time_params = array();
//        $select_time_params['aiid'] = $assetItemId;
//        $select_time_params['hid'] = $hotelId;
//        $select_time_params['rmid'] = $roomId;
//        $select_time_params['cdid'] = $priceCode;
//        $select_time_params['aiidi'] = $aiidi;
//        $select_time_params['bsn'] = $business;
//        $select_time_params['id'] = $interId;
//        $select_time_params['oid'] = $orderId;
//        $other_time_url = Soma_const_url::inst()->get_url( '*/booking/select_hotel_time', $select_time_params );
//
//        if( !$interId || !$orderId || !$hotelId || !$roomId || !$priceCode || !$code ){
//            redirect( $other_time_url );
//        }

        //获取资产信息
//        $jump = '*/booking/wx_select_hotel';
//        $item = $this->get_asset_item( $assetItemId, $openid, $orderId, $interId, $business, $jump );
//// var_dump( $item );die;


        //获取资产信息
        $this->getCI()->load->model('soma/Asset_item_package_model', 'ItemModel');
        /** @var \Asset_item_package_model $ItemModel */
        $ItemModel = $this->getCI()->ItemModel;
        $items = $ItemModel->get_order_items_byItemids(array($assetItemId), $business, $interId);

        if (empty($items)) {
            return new Result(Result::STATUS_FAIL, '资产信息不存在');
        }

        $item = $items[0];
        $nowTime = date('Y-m-d H:i:s');

        if (
            $item['inter_id'] != $interId
            || $item['openid'] != $openid
            || $item['order_id'] != $orderId
            || (int)$item['qty'] <= 0
            || strtotime($item['expiration_date']) < strtotime($nowTime)
        ) {
            return new Result(Result::STATUS_FAIL, '资产信息有误');
        }

        //组装回跳选酒店和订单详情页的参数
//        $params = array();
//        $params['bsn'] = $business;
//        $params['id'] = $interId;
//        $params['oid'] = $orderId;
//        $other_hotel_url = Soma_const_url::inst()->get_url('*/booking/wx_select_hotel', $params);
//        $order_detail_url = Soma_const_url::inst()->get_url('*/order/order_detail', $params);

        //获取订单详情
        $this->getCI()->load->model('soma/Sales_order_model', 'OrderModel');
        /** @var \Sales_order_model $OrderModel */
        $OrderModel = $this->getCI()->OrderModel;
        $OrderModel = $OrderModel->load($orderId);
        if (!$OrderModel) {
            return new Result(Result::STATUS_FAIL, '酒店不存在');
        }
        $OrderModel->business = $business;
        $orderDetail = $OrderModel->get_order_detail($business, $interId);
        if (!$orderDetail) {
            return new Result(Result::STATUS_FAIL, '订单详情不存在');
        }

        // 检查下单数量和剩余数量
        $qty = $item['qty'];
        if ($num > $qty) {
            //选择数量大于剩余数量，跳回选择入住时间
            return new Result(Result::STATUS_FAIL, '剩余数量不足');
        }

        //计算要发送给订单的金额
        $buy_qty = isset($orderDetail['items'][0]['qty']) ? $orderDetail['items'][0]['qty'] : 0;
        $real_grand_total = $orderDetail['real_grand_total'];//实付金额
        $real_grand_total_arr = explode('.', $real_grand_total);//如果有小数的，要把小数去掉先，加在最后使用的一个数量
        $remainder = $real_grand_total_arr[0] % $buy_qty;//余数
        if ($qty > 1) {
            $send_grand_total = ($real_grand_total_arr[0] - $remainder) / $buy_qty;
            //($a-$a%$b)/$b
        } elseif ($qty == 1) {
            //使用数量为1的时候，加上余数和小数
            $send_grand_total = (($real_grand_total_arr[0] - $remainder) / $buy_qty + $remainder) . '.' . $real_grand_total_arr[1];
            //($a-$a%$b)/$b + $a%$b;
        } else {
            //数量不足，跳回订单详情
            return new Result(Result::STATUS_FAIL, '剩余数量不足');
        }

        //给订房发送数据前，检测同一个核销码是否已经发送过，如果已经发送过，要拦截本次请求，防止多次下单
        $key = "SOMA_PACKAGE_TO_HOTEL:BOOKING_ROOM_{$interId}_{$orderId}_{$code}";
        // $cache= $this->_load_cache();
        // $redis= $cache->redis->redis_instance();

        $this->getCI()->load->library('Redis_selector');
        /** @var \Redis_selector $redis_selector */
        $redis_selector = $this->getCI()->redis_selector;
        $redis = $redis_selector->get_soma_redis('soma_redis');
//        if ($redis = $this->redis_selector->get_soma_redis('soma_redis')) {
//            return $redis;
//        }
//        $redis = $this->get_redis_instance();

        /*
        $now_time = time();
        $lock_time = $now_time + 10;
        if (!$redis->setnx($key, $lock_time)) {
            // 没有获取到锁的，判断lock是否已过期
            // $lock_expire = (int)$redis->get( $key );
            // $lock_expire_old = (int)$redis->getset( $key, $lock_time );
            // if( $now_time >= $lock_expire && $now_time > $lock_expire_old ){
            //     $redis->delete($key);

            // }
            return new Result(Result::STATUS_FAIL, '不能多次提交,请稍后再试！');
        }
        */
        // yhdsir 重改
        // redis 版本3
        $now_time = time();
        $lock_time = $now_time + 10;
        $lock = $redis->setnx($key, $lock_time);
        if ($lock or (($now_time > (float)$redis->get($key)) and $now_time > (float)$redis->getset($key, $lock_time))) {
        } else {
            return new Result(Result::STATUS_FAIL, '不能多次提交,请稍后再试！');
        }


        $redis->setex($key, 60, $lock_time);

        $remark = '套票预定，订单号：' . $orderId . '，核销券码：' . $code . '，商品名称：' . $item['name'];
        //发送订房的数据
        $post_params = array(
            'openid' => $openid,
            'startdate' => date('Ymd', strtotime($start)),
            'enddate' => date('Ymd', strtotime($end)),
            'hotel_id' => $hotelId,
            'room_id' => $roomId,
            'price_code' => $priceCode,
            'roomnums' => $num,
            'name' => $name,
            'tel' => $phone,
            'remark' => $remark,
            'rtype' => 'room',//默认room
            // 'allprice'=>$send_grand_total,
            // 默认价格为0
            'allprice' => 0,
//            'starttime' => '',
        );

        //给订房发送数据
        $this->getCI()->load->library('Soma/Api_hotel');
        $ApiModel = new \Api_hotel($interId);
        $ApiModel->_write_log('提交订房订单开始。inter_id：' . $interId . ' order_id：' . $orderId, 'start');
        $return = $ApiModel->post_booking_room($interId, $post_params);

        //订房返回成功, 核销该核销码
        $booking_status = FALSE;
        $consumer_status = FALSE;
        $recordId = 0;
        if (isset($return['status']) && $return['status'] == \Soma_base::STATUS_TRUE) {

            $booking_status = TRUE;

            $this->getCI()->load->model('soma/Consumer_order_model', 'ConsumerOrderModel');
            /** @var \Consumer_order_model $ConsumerOrderModel */
            $ConsumerOrderModel = $this->getCI()->ConsumerOrderModel;

            //订房返回成功，插入一条成功信息到consumer_order_booking_hotel表
            $post_params['business'] = $business;
            $post_params['inter_id'] = $interId;
            $post_params['order_id'] = $orderId;
            $post_params['code'] = $code;
            $post_params['show_orderid'] = $return['data']['show_orderid'];
            $post_params['orderid'] = $return['data']['orderid'];
            $post_params['hotel_name'] = $item['hotel_name'];
            $post_params['room_name'] = $room_name;
            $post_params['mobile'] = $phone;
            $post_params['create_time'] = date('Y-m-d H:i:s');
            $post_params['status'] = \Consumer_order_model::BOOKING_HOTEL_FALSE;//未处理，核销完之后，更新状态
            // var_dump( $post_params );die;

            $recordId = $ConsumerOrderModel->save_booking_hotel_record_info($interId, $post_params);

            // var_dump( $this->session->userdata('booking_hotel_consumer_id') );die;
            //根据券码去核销
            $consumer_method = $ConsumerOrderModel::CONSUME_HOTEL_SELF;//自助核销(套票转订房)
            // $result = $ConsumerOrderModel->direct_consumer( $code, $openid, $consumer_method, $interId, $business );
            // if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){

            $service = \App\services\soma\consumer\ConsumerService::getInstance();
            $result = $service->codeConsumer($interId, $code, $openid, $consumer_method, $business, $hotelId);

            if ($result->getStatus() == \App\services\Result::STATUS_OK) {
                $consumer_status = TRUE;

                //更新consumer_order_booking_hotel表状态，标记为已处理
                $filter = array();
                $filter['id'] = $recordId;
                $filter['inter_id'] = $interId;
                $filter['openid'] = $openid;
                $filter['order_id'] = $orderId;

                $consumerId = $this->getCI()->session->userdata('booking_hotel_consumer_id');
                $data = array();
                $data['consumer_id'] = $consumerId;
                $data['status'] = $ConsumerOrderModel::BOOKING_HOTEL_TRUE;
                // var_dump( $interId, $filter, $data );die;
                $ConsumerOrderModel->update_booking_hotel_record_status($interId, $filter, $data);
                $this->getCI()->session->set_userdata('booking_hotel_consumer_id', '');

                /**
                 * @var \Consumer_item_package_model $consumerItemModel
                 */
                ///修改消费细单备注
                $business = 'package';
                $consumerItemId = $this->getCI()->session->userdata('booking_hotel_consumer_item_id');
                $itemRemark = "订房订单号：{$return['data']['orderid']}，酒店：{$item['hotel_name']}，房型：{$room_name}，价格代码：{$code_name}";
                $this->getCI()->load->model("soma/Consumer_item_{$business}_model", 'consumerItemModel');
                /** @var \Consumer_item_package_model $consumerItemModel */
                $consumerItemModel = $this->getCI()->consumerItemModel;
                $consumerItemModel->remark_save($consumerItemId, $itemRemark, $interId, $business);
                $this->getCI()->session->set_userdata('booking_hotel_consumer_item_id', '');

                /***********************发送模版消息****************************/
                //发送模版消息
                /* 新版核销内已经发送核销模板消息
                $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
                $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

                $openid = $openid;
                $inter_id = $interId;

                $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;

                $this->load->model('soma/Asset_customer_model','AssetCustomerModel');
                $AssetCustomerModel = $this->AssetCustomerModel;
                $AssetCustomerModel->asset_item_id = $assetItemId;
                $AssetCustomerModel->code = $code;

                $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, $AssetCustomerModel, $openid, $inter_id, $business);
                */
                /***********************发送模版消息****************************/

            } else {
                //提示自助核销失败
                // $message = isset( $result['message'] ) ? $result['message'] : '自助核销失败，发生未知错误。';
                $message = $result->getMessage();
                $ApiModel->_write_log('提交订房订单成功，券码自助核销失败。inter_id：' . $interId . ' order_id：' . $orderId . '，失败信息：' . $message, 'consumer');
            }
        }

        // $redis->delete($key);//不能删除，如果同时并发很多，删除了后面跟着进来去订房下单
        $ApiModel->_write_log('提交订房订单结束。inter_id：' . $interId . ' order_id：' . $orderId, 'end');

        //后续处理 这里需要跳转，不能在这里显示成功或者失败页面，否则可能会重复发送数据给订房下单
        if ($booking_status == TRUE && $consumer_status == TRUE) {
            return new Result(Result::STATUS_OK, '下单成功', ['bid' => $recordId]);
        } else {
            return new Result(Result::STATUS_FAIL, '下单失败');
        }
    }
}
