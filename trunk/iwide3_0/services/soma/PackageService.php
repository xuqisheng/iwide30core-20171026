<?php

namespace App\services\soma;

use App\models\soma\Activity_killsec;
use App\services\BaseService;

/**
 * Class PackageService
 * @package App\services\soma
 *
 */
class PackageService extends BaseService
{

    /**
     *
     * @return PackageService
     * @author renshuai  <renshuai@jperation.cn>
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }


    /**
     * 获取套票列表
     * @param array $params
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function index($params = []){

        //查看权限
        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }
        $hotel_ids = $this->getCI()->session->get_admin_hotels();
        if($hotel_ids){
            $params['hotel_id'] = $hotel_ids;
        }
        $hotel_ids= $this->getCI()->session->get_admin_hotels();
        $params['hotel_id'] = $hotel_ids ? explode(',', $hotel_ids) : array();

        $this->getCI()->load->model('soma/product_package_model', 'productPackageModel');
        return $this->getCI()->productPackageModel->getProductsList($params);
    }


    /**
     * 获取套票分类
     * @param $inter_id
     * @return mixed
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getCatalog($inter_id){

        $this->getCI()->load->model('soma/category_package_model', 'CategoryPackageModel');
        $categoryPackageModel = $this->getCI()->CategoryPackageModel;
        return $categoryPackageModel->getCatalog($inter_id);
    }


    /**
     * 判断当前用户是否为分销员或者是泛分销
     * @param $inter_id
     * @param $openid
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getUserSalerOrFansaler($inter_id, $openid)
    {
        $this->getCI()->load->library('Soma/Api_idistribute');
        $staff = $this->getCI()->api_idistribute->get_saler_info($inter_id, $openid);
        if($staff)
        {
            //判断是分销员还是泛分销 $staff['typ'] ＝ 'STAFF'(分销员), 'FANS'(泛分销)
            $saler_type     = isset($staff['typ']) && ! empty($staff['typ']) ? $staff['typ'] : '';
            $saler_id       = isset($staff['info']['saler']) && ! empty($staff['info']['saler']) ? $staff['info']['saler'] : 0;
            if ($saler_id && $saler_type)
            {
                //分销员名称
                $saler_name     = isset($staff['info']['name']) && ! empty($staff['info']['name']) ? $staff['info']['name'] : '';

                //返回分销员信息
                $salesArr = array(
                    'saler_id'      => $saler_id,
                    'saler_type'    => $saler_type,
                    'saler_name'    => $saler_name,
                );

                return $salesArr;
            }
        }

        return array();
    }


    /**
     * 集中不同类型商品相关信息
     * @param array $products
     * @param $interId
     * @param $openId
     * @param array $attach 附加参数，可做一些特别处理
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function composePackage($products = [], $interId, $openId, $attach = []){

        if(is_array($products) && !empty($products)){

            //给商品追加用户对应的专属价格
            ScopeDiscountService::getInstance()->appendScopeDiscount($products, $interId, $openId, false);

            //实例化
            $this->getCI()->load->model('soma/product_package_model', 'productPackageModel');
            $this->getCI()->load->model('soma/Product_specification_setting_model', 'pspModel');
            $productModel = $this->getCI()->productPackageModel;
            $pspModel = $this->getCI()->pspModel;

            //多规格价格
            $productIds = array_column($products, 'product_id');
            $pspSetting = $pspModel->get_inter_product_spec_setting($interId, $productIds);
            $settingPrice = [];
            if (!empty($pspSetting)) {
                $settingPrice = $this->getSettingInfo($pspSetting);
            }

            foreach($products as $key => &$val){

                //去掉名称的标签
                $val['name'] = strip_tags($val['name']);

                //秒杀
                $killsec = KillsecService::getInstance()->getInfo($val['product_id']);
                if(!empty($killsec)){
                    if(!isset($attach['common']) || $attach['common'] != 1){
                        $val['price_market'] = $val['price_package'];
                        $val['price_package'] = $killsec['killsec_price'];
                    }
                }
                //如果是积分商品，去掉小数点，向上取整
                if($val['type'] == $productModel::PRODUCT_TYPE_POINT) {
                    $val['price_package'] = ceil($val['price_package']);
                    $val['price_market'] = ceil($val['price_market']);
                    if($killsec) {
                        if(!isset($attach['common']) || $attach['common'] != 1){
                            $val['price_package'] = ceil($killsec['killsec_price']);
                        }
                    }
                }
                //专属价
                if(!empty($val['scopes'])){
                    $val['price_package'] = $val['scopes'][0]['price'];
                }

                //多规格价
                if (isset($settingPrice[$val['product_id']])) {
                    $val['price_package'] = $settingPrice[$val['product_id']][0]['spec_price'];
                }

                //整型去掉小数点
                $val['price_market'] = $this->progressNumber($val['price_market']);
                $val['price_package'] = $this->progressNumber($val['price_package']);

                //商品类型 标签 返回值 1：专属 2：秒杀 3：拼团 4：满减 5：组合 6：储值 7：积分
                $val['tag'] = 0;
                if($val['goods_type'] == $productModel::SPEC_TYPE_COMBINE) {
                    //组合标签
                    $val['tag'] = $productModel::PRODUCT_TAG_COMBINED;
                } else {
                    if($val['type'] == $productModel::PRODUCT_TYPE_BALANCE) {
                        //储值标签
                        $val['tag'] = $productModel::PRODUCT_TAG_BALANCE;
                        $val['can_refund'] = (string)$productModel::CAN_F;
                    }
                    if($val['type'] == $productModel::PRODUCT_TYPE_POINT) {
                        //积分标签
                        $val['tag'] = $productModel::PRODUCT_TAG_POINT;
                        $val['can_refund'] = (string)$productModel::CAN_F;
                    }
                }
                if(!empty($val['auto_rule'])){
                    //满减
                    $val['tag'] = $productModel::PRODUCT_TAG_REDUCED;
                }
                if(!empty($killsec)){
                    //秒杀标签
                    if(!isset($attach['common']) || $attach['common'] != 1){
                        $val['tag'] = $productModel::PRODUCT_TAG_KILLSEC;
                    }
                }
                if(!empty($val['scopes'])){
                    //专属标签
                    $val['tag'] = $productModel::PRODUCT_TAG_EXCLUSIVE;
                }
                //todo 拼团

                //商品有效期
                $val['is_expire'] = false;
                if($val['goods_type'] != $productModel::SPEC_TYPE_TICKET && $val['date_type'] == $productModel::DATE_TYPE_STATIC) {
                    $time = time();
                    $expireTime = isset($val['expiration_date']) ? strtotime($val['expiration_date']) : null;
                    if($expireTime && $expireTime < $time) {
                        $val['is_expire'] = true;
                    }
                }
            }
        }

        return $products;
    }



    /**
     * 根据产品id和规格类型获取规格信息列表
     * @param $interId
     * @param $productId
     * @param $type
     * @return mixed
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getSettingInfoByProductId($interId, $productId, $type){
        $this->getCI()->load->model('soma/Product_specification_setting_model', 'productSpecificationSettingModel');
        return $this->getCI()->productSpecificationSettingModel->get_full_specification_compose($interId, $productId, $type);
    }

    /**
     * 处理规格信息
     * @param $pspSetting
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    protected function getSettingInfo($pspSetting)
    {
        $data = array();
        foreach ($pspSetting as $row) {
            $data[$row['product_id']][] = $row;
        }
        return $data;
    }


    /**
     * 处理规格信息
     * @param $psp_setting
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getSettingInfoCompose($psp_setting)
    {
        $dataTicket = $data = array();
        //判断是否有门票类的
        $isTicket = false;
        foreach ($psp_setting as $row) {
            if ($row['type'] == 1) {
                $data[$row['product_id']][] = $row;
            } elseif ($row['type'] == 2) {
                $isTicket = true;
                $dataTicket[$row['product_id']][] = $row;
            }
        }

        if ($isTicket && $dataTicket) {
            $psp_setting = $dataTicket;
        } else {
            $psp_setting = $data;
        }

        return array('isTicket' => $isTicket, 'settingInfo' => $psp_setting,);
    }


    /**
     * 获取产品规格信息
     * @param $interId
     * @param $productId
     * @param $psp_sid
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getSettingInfoByProductIdAndPspSid($interId, $productId, $psp_sid)
    {
        $setting = [];
        $this->getCI()->load->model('soma/Product_specification_setting_model', 'pspModel');
        $psp_setting = $this->getCI()->pspModel->get_specification_compose($interId, $productId, $psp_sid);
        if (!empty($psp_setting)){
            $setting = array_values($psp_setting);
        }
        return $setting;
    }



    /**
     * 获取推荐位商品
     * @param $page
     * @param $page_size
     * @param $uri
     * @param $inter_id
     * @author daikanwu <daikanwu@jperation.com>
     * @return array
     */
    public function getRecommend($page, $page_size, $uri, $inter_id)
    {
        $filter = array('inter_id' => $inter_id);
        $this->getCI()->load->model('soma/Cms_block_model');
        $this->getCI()->load->model('soma/Product_package_model');
        $pids = $this->getCI()->Cms_block_model->show_in_page($uri, $filter,$page_size, $page);

        $products = array();
        if ($pids) {
            $select = 'product_id,face_img,name,price_package,price_market,hotel_id,type,goods_type,date_type,status,un_validity_date,validity_date,expiration_date';
            $products = $this->getCI()->Product_package_model->get_product_package_by_ids($pids, $inter_id,$select);
        }

        // 如果没有配置推荐位产品，抽取产品销量最高的2条显示
        if (empty($products)) {
            $products = $this->getCI()->Product_package_model->getRecommendedProducts($inter_id);
            $tmp = array();
            foreach ($products as $v) {
                $tmp[] = array(
                    'product_id' => $v['product_id'],
                    'face_img' => $v['face_img'],
                    'name' => $v['name'],
                    'price_package' => $v['price_package'],
                    'price_market' => $v['price_market'],
                    'hotel_id' => $v['hotel_id'],
                    'type' => $v['type'],
                    'goods_type' => $v['goods_type'],
                    'date_type' => $v['date_type']
                );
            }
            $products = $tmp;
        } else {
            $tmp_products = [];
            //去掉下架的
            foreach ($products as $k => $v){
                if (!$this->getCI()->Product_package_model->isOff($v)) {
                    $tmp_products[] = $v;
                }
            }
            $products = $tmp_products;
        }

        return $products;

    }


    /**
     * 获取商品促销规则
     * @param $product
     * @param $interId
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getProductAutoRule($product, $interId)
    {

        $auto_rule = [];

        if(empty($product)){

            return $auto_rule;
        }

        $this->getCI()->load->model('soma/product_package_model', 'productPackageModel');
        $productPackageModel = $this->getCI()->productPackageModel;

        if($product['type'] != $productPackageModel::PRODUCT_TYPE_POINT && empty($product['scopes'])) {
            //促销规则加载
            $this->getCI()->load->model('soma/Sales_rule_model', 'salesRuleModel');
            $salesRuleModel = $this->getCI()->salesRuleModel;
            $auto_rule = $salesRuleModel->get_product_rule(array($product['product_id']), $interId, 'auto_rule');
            $auto_rule_new = array();
            if ($auto_rule && count($auto_rule) > 0) {
                foreach ($auto_rule as $v) {
                    $auto_rule_new[] = $v;
                }
            }
            $auto_rule = $auto_rule_new;
        }

        return $auto_rule;
    }


    /**
     * 获取商品促销信息
     * @param $products
     * @param $settlement
     * @param $pspSid
     * @param $qty
     * @param $interId
     * @param $openId
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getProductRules($products, $settlement, $pspSid, $qty, $interId, $openId){

        if(isset($products[0]['scopes'])){
            $data = ['asset' => ['status' => 2, 'cal_rule' => ['can_use_coupon' => 2]]];
        }
        else{

            ScopeDiscountService::getInstance()->appendScopeDiscount($products, $interId, $openId);

            $subtotal = 0;

            //多规格，替换原来产品中的库存与价格
            $this->getCI()->load->model('soma/Product_specification_setting_model', 'psp_model');
            if(!empty($products) && $psp_setting = $this->getCI()->psp_model->load($pspSid)) {
                $products[0]['price_package'] = $psp_setting->m_get('spec_price');
            }

            foreach($products as $k => $v) {
                $p_type = $v['type'];
                $products[$k]['qty'] = $qty;
                $subtotal += $v['price_package'] * $qty;
            }

            $this->getCI()->load->model('soma/Sales_rule_model');
            $rules = $this->getCI()->Sales_rule_model->get_discount_rule($interId, $openId, $products, $subtotal, $settlement);

            if(isset($rules['auto_rule'])) {
                $rules['auto_rule']['least_cost'] = $this->progressNumber($rules['auto_rule']['least_cost']);
                $rules['auto_rule']['reduce_cost'] = $this->progressNumber($rules['auto_rule']['reduce_cost']);
                $activity = array('status' => 1, 'auto_rule' => $rules['auto_rule']);
            }
            else{
                $activity = array('status' => 2, 'auto_rule' => array());
            }
            if(isset($rules['cal_rule']) && isset($rules['cal_rule']['reduce_cost'])) {
                $rules['cal_rule']['reduce_cost'] = $this->progressNumber($rules['cal_rule']['reduce_cost']);
                $rules['cal_rule']['quote'] = $this->progressNumber($rules['cal_rule']['quote']);

                $asset = array('status' => 1, 'cal_rule' => $rules['cal_rule']);
            }
            else{
                $asset = array('status' => 2, 'cal_rule' => array());
            }

            $data = array('activity' => $activity, 'asset' => $asset, 'base_info' => $rules['base_info']);
        }

        return $data;
    }


    //处理数字
    public function progressNumber($number){

        $number = number_format($number, 2, '.', '');

//        //去掉.00
//        if (stripos($number, '.00') !== false) {
//            $number = number_format($number, 0, '.', '');
//        }

        //$number = preg_replace(' /^\d+(\.\d[0]+)?$/', '', $number);

        $split = explode('.', $number);
        $previous = $split[0];
        $after = $split[1];
        $dot = '';
        $after_ = '';
        if(!empty($after)){
            $arr = str_split($after);
            $arr_ = [];
            for($i = count($arr) - 1; $i >= 0; $i--){
                if($arr[$i] != '0'){
                    $arr_[] = $arr[$i];
                    $dot = '.';
                }
                else{
                    if(!empty($arr_)){
                        $arr_[] = $arr[$i];
                    }
                }
            }
            if(!empty($arr_)){
                $after_ = implode('', array_reverse($arr_));
            }
        }

        return $previous.$dot.$after_;
    }


    /**
     * 根据规则id参数确定应该默认买多少份
     * @param $product
     * @param $ruleId
     * @return float|int
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getProductDefaultCount($product, $ruleId)
    {

        $count = 1;

        if(!$product || !$ruleId){
            return $count;
        }

        $this->getCI()->load->model('soma/Sales_rule_model', 'salesRuleModel');
        $salesRuleModel = $this->getCI()->salesRuleModel;

        $fix_rule = $salesRuleModel->find(array('rule_id' => $ruleId));
        if ($fix_rule && $fix_rule['lease_cost'] && $product['price_package']) {
            $fix_qty = $fix_rule['lease_cost'] / $product['price_package'];
            if ($fix_qty < 1) {
                $fix_qty = 1;
            }
            else {
                if ($fix_qty > 1) {
                    $fix_qty = ceil($fix_qty);
                } else {
                    $fix_qty = intval($fix_qty);
                }
            }
            $count = $fix_qty > 200 ? 200 : $fix_qty;
        }

        return $count;
    }



    /**
     * 获取商品优惠券
     * @param $inter_id
     * @param $openid
     * @param $productId
     * @param $count
     * @param $type
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getProductCoupons($inter_id, $openid, $productId, $count, $type){

        $data = [];

        $cardType = $type;
        $pid = $productId;

        if(empty($pid)){
            show_error('Invalid pid supplied', 400);
        }

        $this->getCI()->load->model('soma/Product_package_model');
        $products = $this->getCI()->Product_package_model->get_product_package_by_ids([$pid], $inter_id);
        $subtotal = 0;
        if (!empty($products)) {
            foreach ($products as $k => $v) {
                $subtotal += $v['price_package'] * $count;  //累计订单总额
            }
        }
        else{
            return $data;
        }

        //读取购买人的可用券
        $this->getCI()->load->library('Soma/Api_member');
        $api = new \Api_member($inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $result = $api->conpon_sign_list($openid);

        $card_ids = array();
        if (isset($result['data']) && count($result['data']) > 0) {
            $coupons = array();
            foreach ($result['data'] as $k => $v) {
                if (!in_array($v->card_id, $card_ids)) {
                    $card_ids[] = $v->card_id;
                }
                $result['data'][$k]->discount = number_format($v->discount, 0, '.', ',');
            }

            $this->getCI()->load->model('soma/Sales_order_discount_model');
            $discountModel = $this->getCI()->Sales_order_discount_model;
            $this->getCI()->load->model('soma/Sales_coupon_model');
            $link_all = $this->getCI()->Sales_coupon_model->get_coupon_product_list($card_ids, $inter_id);

            //取出适用所有商品的优惠券，格式：array('card_id'=>'券1',)
            $wide_scope_coupon = $this->getCI()->Sales_coupon_model->get_wide_scope_coupon($inter_id, true);

            foreach ($result['data'] as $k => $v) {
                //逐张优惠券判断是否满足购物条件
                $v->reduce_cost = $this->progressNumber($v->reduce_cost);
                $tmp = (array)$v;

                if (array_key_exists($tmp['card_id'], $wide_scope_coupon)) {
                    if (isset($tmp['least_cost']) && $tmp['least_cost'] > $subtotal) {
                        $tmp['usable'] = false;

                    } else {
                        if (isset($tmp['over_limit']) && $tmp['over_limit'] > 0 && $tmp['over_limit'] < $subtotal) {
                            $tmp['usable'] = false;

                        } else {
                            $tmp['usable'] = true;  //该卡属于宽泛匹配卡id
                        }
                    }
                    $tmp['scopeType'] = '全部商品适用';

                } else {
                    foreach ($link_all as $sk => $sv) {
                        //匹配配置表中的各个配置商品，匹配到为止
                        if (isset($tmp['usable']) && $tmp['usable'] == true) {
                            continue;  //匹配到之后跳出不再循环匹配。
                        }

                        //已经配置了该卡券 && 配置的商品、数量 跟当前购物清单匹配
                        if (isset($tmp['least_cost']) && $tmp['least_cost'] > $subtotal) {
                            $tmp['usable'] = false;

                        } else {
                            if (isset($tmp['over_limit']) && $tmp['over_limit'] > 0 && $tmp['over_limit'] < $subtotal) {
                                $tmp['usable'] = false;

                            } else {
                                if ($sv['card_id'] == $tmp['card_id'] && in_array($sv['product_id'], [$pid]) && $count >= $sv['qty']) {
                                    $tmp['usable'] = true;  //该卡满足配置和数量条件
                                    $tmp['scopeType'] = '部分商品适用';

                                } else {
                                    $tmp['usable'] = false;  //该卡不符合使用条件
                                    $tmp['scopeType'] = '无适用商品';
                                }
                            }
                        }
                    }
                }

                //判断是否到了可用时间
                if (time() < $tmp['use_time_start']) {
                    $tmp['usable'] = false;  //该卡不符合使用条件,没有到使用时间
                }


                //跟会员组了解过, 券的过期时间设置是 2016-11-11 00:00:00，但是实际过期时间是2016-11-11 23:59:59
                $expire_date = date('Y-m-d', $tmp['expire_time']);
                $expire_time = strtotime($expire_date);
                if ($tmp['expire_time'] == $expire_time) {
                    $real_expire_date = $expire_date . ' 23:59:59';
                    $tmp['expire_time'] = strtotime($real_expire_date);
                }

                $minusTime = $tmp['expire_time'] - time();
                if ($minusTime <= 0) {
                    continue;
                } elseif (($minusTime / 86400) <= 10) {
                    $tmp['expire_time'] = str_replace('[0]', ceil($minusTime / 86400), "还有[0]天过期");
                } else {
                    $tmp['expire_time'] = '有效期至'.'：' . date("Y-m-d", $tmp['expire_time']);
                }

                $coupons[] = $tmp;
            }

            //将不可用的券排到最后面
            $can_use_arr = array();
            $can_use_not_arr = array();
            foreach ($coupons as $k => $v) {
                if(isset($v['usable']) && $v['usable'] == true) {
                    $can_use_arr[] = $v;
                    unset($coupons[$k]);
                }
                if(isset($v['usable']) && $v['usable'] == false) {
                    $can_use_not_arr[] = $v;
                    unset($coupons[$k]);
                }
            }
            $coupons = array_merge($can_use_arr, $can_use_not_arr);

            //把优惠券分成抵扣券、兑换券、折扣券
            $dj = array();
            $zk = array();
            $dh = array();
            $cz = array();
            foreach ($coupons as $k => $v) {
                if ($v['card_type'] == $discountModel::TYPE_COUPON_DJ) {
                    //代金券
                    $dj[] = $v;
                } elseif ($v['card_type'] == $discountModel::TYPE_COUPON_ZK) {
                    //折扣券
                    $zk[] = $v;
                } elseif ($v['card_type'] == $discountModel::TYPE_COUPON_DH) {
                    //兑换券
                    $dh[] = $v;
                } elseif ($v['card_type'] == $discountModel::TYPE_COUPON_CZ) {
                    //储值券
                    $cz[] = $v;
                }
            }

            if ($cardType == $discountModel::TYPE_COUPON_DJ) {
                //代金券
                $coupons = $dj;
            } elseif ($cardType == $discountModel::TYPE_COUPON_ZK) {
                //折扣券
                $coupons = $zk;
            } elseif ($cardType == $discountModel::TYPE_COUPON_DH) {
                //兑换券
                $coupons = $dh;
            } elseif ($cardType == $discountModel::TYPE_COUPON_CZ) {
                //储值券
                $coupons = $cz;
            }

            $data = $coupons;
        }

        return $data;
    }


    //获取设置绩效商品列表
    public function getDistributeProducts($page = 1, $pageSize = 10, $sort = 1){

        //缓存
        $redis = $this->getCI()->get_redis_instance();
        $themeConfig = $redis->get('theme_config_distribute');

        //分销员
        $saler = $fansler = 0;
        $salerInfo = $this->getSalerInfo();
        if($salerInfo){
            //soma_detail_商品id_分销id_泛分销id
            if($salerInfo['saler_type'] == 'STAFF'){
                $saler = $salerInfo['saler_id'];
            }
            if($salerInfo['saler_type'] == 'FANS'){
                $fansler = $salerInfo['saler_id'];
            }
        }

        //二维码
        $dir = str_replace('system', '', BASEPATH);
        $this->getCI()->load->helper('phpqrcode');
        $uploadUrl = $dir.'/www_front/public/soma/qrcode/';

        //首页二维码
        $indexUrl = site_url('soma/package/index?'. http_build_query([
                'id' => $this->getCI()->inter_id,
                'saler' => $saler,
                'fans_saler' => $fansler
            ]));

        $productIds = [];

        //绩效商品
        $all = false;
        $nowTime = date('Y-m-d H:i:s');
        /**
         * @var \Reward_rule_model $rewardRuleModel
         */
        $this->getCI()->load->model('soma/Reward_rule_model', 'rewardRuleModel');
        $rewardRuleModel = $this->getCI()->rewardRuleModel;
        $rewardRuleList = $rewardRuleModel->get(
            ['inter_id', 'status', 'start_time <= ', 'end_time >=', 'reward_source'],
            [$this->getCI()->inter_id, $rewardRuleModel::STATUS_ACTIVE, $nowTime, $nowTime, $rewardRuleModel::REWARD_SOURCE_SALER],
            ['product_ids', 'reward_rate', 'sort', 'reward_type'],
            ['limit' => null, 'orderBy' => 'sort desc, rule_id desc']
        );
        if(count($rewardRuleList)){
            foreach ($rewardRuleList as $key => $val){
                if(!$val['product_ids']){
                    $all = true;
                    break;
                }
            }
            if(!$all){
                foreach ($rewardRuleList as $key => $val){
                    $pid = explode(',', $val['product_ids']);
                    $productIds = array_merge($productIds, $pid);
                }
                $productIds = array_values(array_unique(array_values(array_filter($productIds))));
            }
        }



        //组合商品
        if(!empty($productIds)){
            $this->getCI()->load->model('soma/Product_package_link_model', 'productPackageLinkModel');
            $productPackageLinkModel = $this->getCI()->productPackageLinkModel;
            $productPackageLinkList = $productPackageLinkModel->get(['parent_pid'], [$productIds], ['parent_pid', 'child_pid'], ['limit' => null]);

            if(!empty($productPackageLinkList)){
                foreach ($productPackageLinkList as $val){
                    //主、子都有，取主商品
                    if(in_array($val['parent_pid'], $productIds) && in_array($val['child_pid'], $productIds)){
                        foreach ($productIds as $item => $vale){
                            if($vale == $val['child_pid']){
                                unset($productIds[$item]);
                                break;
                            }
                        }
                    }
                }

            }
            $productIds = array_values($productIds);
        }


        //商品
        $this->getCI()->load->model('soma/Product_package_model', 'productPackageModel');
        $productPackageModel = $this->getCI()->productPackageModel;
        $where = [
            'inter_id'         => $this->getCI()->inter_id,
            'status'           => $productPackageModel::STATUS_ACTIVE,
            'validity_date <= '    => $nowTime,
            'un_validity_date >= ' => $nowTime,
            'expiration_date >= '  => $nowTime
        ];
        if(!$all && !empty($productIds)){
            $where['product_id'] = $productIds;
        }
        $select = [
            'product_id', 'inter_id', 'goods_type', 'face_img', 'name', 'hotel_id',
            'price_market','price_package', 'stock', 'validity_date',
            'status', 'type', 'expiration_date', 'sales_cnt', 'date_type'
        ];
        $options = [
            'limit' => null,
            'orderBy' => 'sales_cnt desc'
        ];
        $products = $productPackageModel->get(array_keys($where), array_values($where), $select, $options);


        //商品处理
        $productsList = [];
        if(is_array($products) && !empty($products)){

            //给商品追加用户对应的专属价格
            ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->getCI()->inter_id, $this->getCI()->openid, false);

            //实例化
            $this->getCI()->load->model('soma/product_package_model', 'productPackageModel');
            $this->getCI()->load->model('soma/Product_specification_setting_model', 'pspModel');
            $productModel = $this->getCI()->productPackageModel;
            $pspModel = $this->getCI()->pspModel;

            //多规格价格
            $productIds = array_column($products, 'product_id');
            $pspSetting = $pspModel->get_inter_product_spec_setting($this->getCI()->inter_id, $productIds);
            $settingPrice = [];
            if (!empty($pspSetting)) {
                $settingPrice = $this->getSettingInfo($pspSetting);
            }

            //秒杀商品
            $killsecModel = new Activity_killsec();
            $killsecList = $killsecModel->getKillsecListByPids($this->getCI()->inter_id, $productIds);

            foreach($products as $key => &$val){

                //去掉名称的标签
                $val['name'] = strip_tags($val['name']);

                //秒杀
                $killsec = [];
                foreach ($killsecList as $vale){
                    if($vale['product_id'] == $val['product_id']){
                        $killsec = $vale;
                        break;
                    }
                }
                if(!empty($killsec)){
                    if(!isset($attach['common']) || $attach['common'] != 1){
                        $val['price_market'] = $val['price_package'];
                        $val['price_package'] = $killsec['killsec_price'];
                    }
                }
                //如果是积分商品，去掉小数点，向上取整
                if($val['type'] == $productModel::PRODUCT_TYPE_POINT) {
                    $val['price_package'] = ceil($val['price_package']);
                    $val['price_market'] = ceil($val['price_market']);
                    if($killsec) {
                        if(!isset($attach['common']) || $attach['common'] != 1){
                            $val['price_package'] = ceil($killsec['killsec_price']);
                        }
                    }
                }
                //专属价
                if(!empty($val['scopes'])){
                    $val['price_package'] = $val['scopes'][0]['price'];
                }

                //多规格价
                if (isset($settingPrice[$val['product_id']])) {
                    $val['price_package'] = $settingPrice[$val['product_id']][0]['spec_price'];
                }

                //整型去掉小数点
                $val['price_market'] = $this->progressNumber($val['price_market']);
                $val['price_package'] = $this->progressNumber($val['price_package']);

                //商品类型 标签 返回值 1：专属 2：秒杀 3：拼团 4：满减 5：组合 6：储值 7：积分
                $val['tag'] = 0;
                if($val['goods_type'] == $productModel::SPEC_TYPE_COMBINE) {
                    //组合标签
                    $val['tag'] = $productModel::PRODUCT_TAG_COMBINED;
                } else {
                    if($val['type'] == $productModel::PRODUCT_TYPE_BALANCE) {
                        //储值标签
                        $val['tag'] = $productModel::PRODUCT_TAG_BALANCE;
                    }
                    if($val['type'] == $productModel::PRODUCT_TYPE_POINT) {
                        //积分标签
                        $val['tag'] = $productModel::PRODUCT_TAG_POINT;
                    }
                }
                if(!empty($val['auto_rule'])){
                    //满减
                    $val['tag'] = $productModel::PRODUCT_TAG_REDUCED;
                }
                if(!empty($killsec)){
                    //秒杀标签
                    if(!isset($attach['common']) || $attach['common'] != 1){
                        $val['tag'] = $productModel::PRODUCT_TAG_KILLSEC;
                    }
                }
                if(!empty($val['scopes'])){
                    //专属标签
                    $val['tag'] = $productModel::PRODUCT_TAG_EXCLUSIVE;
                }

                //商品有效期
                $val['is_expire'] = false;
                if($val['goods_type'] != $productModel::SPEC_TYPE_TICKET && $val['date_type'] == $productModel::DATE_TYPE_STATIC) {
                    $time = time();
                    $expireTime = isset($val['expiration_date']) ? strtotime($val['expiration_date']) : null;
                    if($expireTime && $expireTime < $time) {
                        $val['is_expire'] = true;
                    }
                }

                $productsList[$val['product_id']] = $val;
            }

        }


        //列表
        $result = [];
        if(count($productsList) && count($rewardRuleList)){
            foreach ($productsList as $key => $val){
                //生成二维码图片
//                $qrCodeImg = WxService::QR_CODE_PRODUCT_DETAIL.$val['product_id'].'_'.$saler.'_'.$fansler.'.png';
//                $qrCodeUrl = site_url('soma/package/package_detail?'. http_build_query([
//                        'id' => $this->getCI()->inter_id,
//                        'pid' => $val['product_id'],
//                        'saler' => $saler,
//                        'fans_saler' => $fansler
//                    ]));
//                $qrcode_detail = $this->getQrcode($uploadUrl, $qrCodeImg, $qrCodeUrl);
//                $productsList[$key]['qrcode_detail'] = $qrcode_detail;
                $productsList[$key]['sales_cnt'] = (int)$val['sales_cnt'];
                $productsList[$key]['detail'] = site_url('soma/package/package_detail').'?id='.$this->getCI()->inter_id.'&pid='.$val['product_id'].'&saler='.$saler.'&fans_saler='.$fansler;

                $reward_sort = 0;
                foreach ($rewardRuleList as $item => $vale){
                    $pids = explode(',', $vale['product_ids']);
                    //全部
                    if($all){
                        $pids = $productIds;
                    }
                    if($pids){
                        foreach ($pids as $items => $value){
                            if($value == $val['product_id']){
                                if($reward_sort < (int)$vale['sort']){
                                    $reward_money = (double)$vale['reward_rate'];
                                    $reward_sort = (int)$vale['sort'];
                                    if($vale['reward_type'] == $rewardRuleModel::REWARD_TYPE_PERCENT){
                                        $reward_money *= (double)$productsList[$key]['price_package'];
                                    }
                                    $productsList[$key]['reward_type'] = $vale['reward_type'];
                                    $productsList[$key]['reward_percent'] = $vale['reward_rate'];
                                    $productsList[$key]['reward_money'] = number_format($reward_money, 2, '.', ',');
                                    $productsList[$key]['reward_sort'] = $reward_sort;
                                }
                            }
                        }
                    }
                }
            }
            $result = array_values($productsList);
        }

        //销量
        $this->getCI()->load->helpers('soma/math');
        if($sort == 1){
            $result = array_acco_sort($result, 'sales_cnt', 'desc');
        }
        //绩效金额
        if($sort == 2){
            $result = array_acco_sort($result, 'reward_money', 'desc');
        }

        $offset = ($page - 1 <= 0 ? 0 : $page - 1) * $pageSize;
        if(count($result)){
            $temp = [];
            foreach ($result as $key => $val){
                if($key >= $offset && $key < ($offset + $pageSize)){
                    $temp[] = $val;
                }
            }

            $hotelId = implode(',', array_column($temp, 'hotel_id'));
            $this->getCI()->load->model('hotel/Hotel_model', 'hotelModel');
            $hotelList = $this->getCI()->hotelModel->get_hotel_by_ids($this->getCI()->inter_id, $hotelId);
            if(!empty($hotelList)){
                foreach ($temp as &$val){
                    foreach ($hotelList as $vale){
                        if($val['hotel_id'] == $vale['hotel_id']){
                            $val['hotel_name'] = $vale['name'];
                            break;
                        }
                    }
                }
            }

            $result = $temp;
        }

        $result =  [
            'products' => $result,
            'total' => count($productsList),
            'theme' => json_decode($themeConfig, true),
            'attach' => [
                'index' => $indexUrl
            ]
        ];

        return $result;
    }


    /**
     * 获取分销员信息
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getSalerInfo(){

        $saler_id = $saler_name = $saler_type = null;

        /* add by chencong 20170826 分销保护期 start */
        if(empty($posts['saler']) && empty($posts['fans_saler'])){
            $this->getCI()->load->model('distribute/Idistribute_model');
            $trueSaler = $this->getCI()->Idistribute_model->get_protection_saler($this->getCI()->openid, $this->getCI()->openid);
            if($trueSaler){
                if($trueSaler >= 10000000){// 泛分销10000000起的
                    $fansSalerId = $trueSaler;
                }else{
                    $salerId = $trueSaler;
                }
            }
        }
        /* add by chencong 20170826 分销保护期 end */

        $this->getCI()->load->library('Soma/Api_idistribute');
        $staff = $this->getCI()->api_idistribute->get_saler_info($this->getCI()->inter_id, $this->getCI()->openid);
        if($staff){
            $saler_type = isset($staff['typ']) && ! empty($staff['typ']) ? $staff['typ'] : '';
            $saler_id = isset($staff['info']['saler']) && ! empty($staff['info']['saler']) ? $staff['info']['saler'] : 0;
            $saler_name = isset($staff['info']['name']) && ! empty($staff['info']['name']) ? $staff['info']['name'] : '';
        }

        return [
            'saler_id' => $saler_id,
            'saler_name' => $saler_name,
            'saler_type' => $saler_type
        ];

    }

    /**
     * 生成自定义链接二维码，文件流
     * @param $codeUrl 二维码链接
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getQrcodeStream($codeUrl){

        //生成二维码
        $this->getCI()->load->helper('phpqrcode');
        //生成二维码图片
        \QRcode::png($codeUrl,false, 'L', 6, 2);

    }

    /**
     * 生成自定义链接二维码，cdn
     * @param $filePath 上传文件目录
     * @param $fileName 上传文件名
     * @param $codeUrl 二维码跳转链接
     * @return bool|string
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function getQrcode($filePath, $fileName, $codeUrl){

        $redis = $this->getCI()->get_redis_instance();
        if (empty($redis)) {
            return false;
        }
        $url = $redis->get($fileName);
        if (!empty($url)){
            return $url;
        }

        $this->getCI()->load->helper('phpqrcode');

        //生成二维码图片
        if(!is_dir($filePath)){
            @mkdir($filePath, 0755);
        }
        if(!file_exists($filePath.$fileName)){
            $qrCode = new \QRcode();
            $qrCode::png($codeUrl, $filePath.$fileName, 'L', 6, 2);
        }

        $imgUrl = $this->uploadFileToCdn($filePath.$fileName);
        @unlink($filePath.$fileName);

        $redis->set($fileName, $imgUrl);
        $redis->expire($fileName, 3600 * 24 * 365);

        return $imgUrl;
    }


    /**
     * cdn上传
     * @param $furl 上传文件路径（包括文件名）
     * @return string
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function uploadFileToCdn($furl){

        //cdn
        $file_system_path = '/public/uploads/' .date("Ym"). '/';
        //ftp开始
        $this->getCI()->load->library('ftp');
        $configftp['hostname'] = config_item('ftphostname');
        $configftp['username'] = config_item('ftpusername');
        $configftp['password'] = config_item('ftppassword');
        $configftp['port']     = config_item('ftpport');
        $configftp['passive']  = config_item('ftppassive');
        $configftp['debug']    = config_item('ftpdebug');

        $this->getCI()->ftp->connect($configftp);

        $toftppath = '/public_html'.$file_system_path;
        $isdir = $this->getCI()->ftp->list_files($toftppath);
        if (empty($isdir)) {
            $newpath = '/';$arrpath = explode('/', $toftppath);
            foreach ($arrpath as $v) {
                if ($v!='') {
                    $newpath = $newpath.$v.'/';
                    $isdirchild = $this->getCI()->ftp->list_files($newpath);
                    if (empty($isdirchild)) {
                        $this->getCI()->ftp->mkdir($newpath);
                    }
                }
            }
        }

        $uploadName = time().mt_rand(0, 9999).'.png';
        $this->getCI()->ftp->upload($furl, $toftppath.$uploadName, 'binary', 0775);
        $this->getCI()->ftp->close();
        //ftp结束

        $file_domain = empty(config_item('ftp_cdn_url'))? config_item('ftpurl') : config_item('ftp_cdn_url');

        return $file_domain.$file_system_path.$uploadName;
    }


}
