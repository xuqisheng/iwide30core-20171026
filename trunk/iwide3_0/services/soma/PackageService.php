<?php

namespace App\services\soma;

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
     * @return array
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function composePackage($products = [], $interId, $openId){

        if(is_array($products) && !empty($products)){

            //给商品追加用户对应的专属价格
            ScopeDiscountService::getInstance()->appendScopeDiscount($products, $interId, $openId, false);

            //实例化
            $this->getCI()->load->model('soma/product_package_model', 'productPackageModel');
            $productModel = $this->getCI()->productPackageModel;

            foreach($products as $key => &$val){

                //去掉名称的标签
                $val['name'] = strip_tags($val['name']);

                //秒杀
                $killsec = KillsecService::getInstance()->getInfo($val['product_id']);
                if(!empty($killsec)){
                    $val['price_package'] = $killsec['killsec_price'];
                    $val['price_market'] = $val['price_package'];
                }
                //如果是积分商品，去掉小数点，向上取整
                if($val['type'] == $productModel::PRODUCT_TYPE_POINT) {
                    $val['price_package'] = ceil($val['price_package']);
                    $val['price_market'] = ceil($val['price_market']);
                    if($killsec) {
                        $val['price_package'] = ceil($killsec['killsec_price']);
                    }
                }
                //专属价
                if(!empty($val['scopes'])){
                    $val['price_package'] = $val['scopes'][0]['price'];
                }

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
                    $val['tag'] = $productModel::PRODUCT_TAG_KILLSEC;
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
     * @param $prodcutId
     * @param $type
     * @return mixed
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getSettingInfoByProductId($interId, $prodcutId, $type){
        $this->getCI()->load->model('soma/Product_specification_setting_model', 'productSpecificationSettingModel');
        return $this->getCI()->productSpecificationSettingModel->get_full_specification_compose($interId, $prodcutId, $type);
    }


    /**
     * 处理分销号和泛分销号
     * @author luguihong  <luguihong@jperation.com>
     */
    /*public function handleDistribute()
    {
        $salerId        = $this->input->get('saler');
        $fansSalerId    = $this->input->get('fans_saler');
        if( $salerId ) {
            //如果链接存在分销号，就不刷新链接的分销号，转发刷新，购买计算绩效
            $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, $salerId );
        } else {
            //需要跳转
            $url =  \App\libraries\Support\Url::current();

            //如果链接不存在分销号，判断是否为分销员
            $staff = $this->getUserSalerOrFansaler();
            if ($staff)
            {
                $saler_type = $staff['saler_type'];
                $saler_id   = $staff['saler_id'];
                if ($saler_id && $saler_type)
                {
                    if ($saler_type == 'STAFF')
                    {
                        $this->session->set_userdata( 'isSaler'.$this->inter_id.$this->openid, json_encode($staff) );

                        //是分销员，跳转链接，带上自己的分销号，购买计算绩效
                        $url .= "&saler={$saler_id}";
                        header("Location: $url");
                        die;
                    } elseif ($saler_type == 'FANS') {
                        //是泛分销员，判断页面是否带有泛分销号
                        if( $fansSalerId )
                        {
                            //带泛分销号，是否为自己的泛分销号
                            if( $fansSalerId == $saler_id )
                            {
                                //是自己的泛分销号，购买不赠送绩效
                                $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, '' );
                            } else {
                                //不是自己的泛分销号，购买后计算绩效给泛分销号员
                                $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, $fansSalerId );
                            }
                        } else {
                            //不带泛分销号，静默授权泛分销号，跳转链接带上泛分销号，购买不赠送绩效
                            //静默授权已经在父类进行，无需在这里操作
                            $url .= "&fans_saler={$saler_id}";
                            header("Location: $url");
                            die;
                        }
                    }
                }
            }
        }
    }*/

}