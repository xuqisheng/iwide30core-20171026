<?php
use App\libraries\Iapi\FrontConst;
use App\services\soma\ScopeDiscountService;
use App\libraries\Iapi\BaseConst;
use App\services\soma\KillsecService;
use App\services\soma\WxService;
use App\services\soma\PackageService;
use App\services\Result;
use App\services\soma\ExpressService;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Package
 *
 *
 * 商品相关接口
 *
 * @property Product_package_model $productPackageModel
 */
class Package extends MY_Front_Soma_Iapi
{

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/info",
     *     summary="商品详情",
     *     description="商品详情",
     *     operationId="get_info",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="pid",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="public_info",
     *                  description="公众号信息",
     *                  type = "object",
     *                  @SWG\Items(ref="#/definitions/IwidePublics")
     *              ),
     *              @SWG\Property(
     *                  property="hotel_info",
     *                  description="酒店信息",
     *                  type = "object",
     *                  @SWG\Items(ref="#/definitions/IwideHotel")
     *              ),
     *              @SWG\Property(
     *                  property="saler_banner",
     *                  description="分销通知",
     *                  type = "array",
     *              ),
     *              @SWG\Property(
     *                  property="product_info",
     *                  description="商品信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="page_resource",
     *                  description="页面链接",
     *                  type = "object",
     *              ),
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid pid supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Package not found"
     *     ),
     * )
     */
    public function get_info()
    {
        $data = [];

        $productId = $this->input->get('pid');
        if(empty($productId)) {
            show_error('Invalid pid supplied', 400);
        }

        $this->load->model('soma/product_package_model', 'productPackageModel');
        $productModel = $this->productPackageModel;
        $productDetail = $this->productPackageModel->getByID($productId, $this->inter_id);
        if (empty($productDetail)) {
            show_404();
        }

        //商品内容
        $productDetail['compose'] = unserialize($productDetail['compose']);

        //是否下架
        $productDetail['is_off'] = $this->productPackageModel->isOff($productDetail);

        //相册
        $productDetail['gallery'] = $this->productPackageModel->get_gallery_front($productId, $this->inter_id);

        //追加商品的英文信息
        $this->productPackageModel->appendEnInfo($productDetail);

        //商品类型
        $productDetail['tag'] = 0;
        if ($productDetail['goods_type'] == $productModel::SPEC_TYPE_COMBINE) {
            //组合标签
            $productDetail['tag'] = $productModel::PRODUCT_TAG_COMBINED;
        } else {
            if ($productDetail['type'] == $productModel::PRODUCT_TYPE_BALANCE) {
                //储值标签
                $productDetail['tag'] = $productModel::PRODUCT_TAG_BALANCE;
            }
            if ($productDetail['type'] == $productModel::PRODUCT_TYPE_POINT) {
                //积分标签
                $productDetail['tag'] = $productModel::PRODUCT_TAG_POINT;
            }
        }

        //积分商品设置规则为空
        $auto_rule = [];
        if($productDetail['type'] != Product_package_model::PRODUCT_TYPE_POINT && empty($productDetail['scopes'])) {
            //促销规则加载
            $this->load->model('soma/Sales_rule_model');
            $auto_rule = $this->Sales_rule_model->get_product_rule(array($productId), $this->inter_id, 'auto_rule');
            $auto_rule_new = array();
            if ($auto_rule && count($auto_rule) > 0) {
                foreach ($auto_rule as $v) {
                    $auto_rule_new[] = $v;
                }
            }
            $auto_rule = $auto_rule_new;
        }
        $productDetail['auto_rule'] = $auto_rule;

        $productDetail = PackageService::getInstance()->composePackage([$productDetail], $this->inter_id, $this->openid)[0];

        //秒杀
        $killsec = [];
        if (!$productDetail['is_expire']) {
            $killsec = KillsecService::getInstance()->getInfo($productId);
            if ($killsec) {
                //刷新频率
                $killsec['stock_reflesh_rate'] = 10000;
                if (ENVIRONMENT === 'production') {
                    $killsec['stock_reflesh_rate'] = 60000;
                }
            }
        }
        $productDetail['killsec'] = $killsec;

        //加载多规格信息,页面显示价格为最低规格价
        $isTicket = false;
        $specProduct = false;
        if ($productDetail) {
            $this->load->model('soma/Product_specification_setting_model', 'ps_detail_model');
            $ps_detail = $this->ps_detail_model->get_inter_product_spec_setting($this->inter_id, array($productId));
            if (!empty($ps_detail)) {
                $settingList = $this->_get_setting_info($ps_detail);
                $ps_detail = $settingList['settingInfo'];
                $isTicket = $settingList['isTicket'];
                if($ps_detail) {
                    $specProduct = true;
                }
            }
        }

        $productDetail['spec_product'] = $specProduct;
        $productDetail['isTicket'] = $isTicket;
        $productDetail['ticketId'] = $this->session->userdata('tkid') ? $this->session->userdata('tkid') : null;

        //查询自身分销员信息
        $saler_info = PackageService::getInstance()->getUserSalerOrFansaler($this->inter_id, $this->openid);
        //$saler_info = PackageService::getInstance()->getUserSalerOrFansaler('a450941565', 'oJ3KZs3upmsdv8Q7WbAcDOsUDsLE');

        //查询适用分销规则信息
        $effectiveRule = array();
        $this->load->model('soma/Reward_rule_model', 'rewardRuleModel');
        $rules = $this->rewardRuleModel->getRewardRules($this->inter_id);
        if(!empty($rules) && !empty($saler_info)) {
            $effective_rule = false;
            //同等优先级下，选择秒杀规则优先
            foreach ($rules as $rule) {
                //粉丝不显示 拼团不显示 规则设置不显示的不显示
                if($rule['reward_source'] == Reward_rule_model::REWARD_SOURCE_FIXED || $rule['rule_type'] == Reward_rule_model::SETTLE_GROUPON || $rule['can_show_hip'] == Reward_rule_model::STATUS_CAN_NO) {
                    continue;
                }

                //检查产品是否符合分销规则，不符合不显示
                if(!empty($rule['product_ids']) && strpos($rule['product_ids'], $productId . '') === false) {
                    continue;
                }

                //身份为泛分销员，规则不为泛分销规则不显示
                if($saler_info['saler_type'] == 'FANS' && $rule['reward_source'] != Reward_rule_model::REWARD_SOURCE_FANS_SALER) {
                    continue;
                }

                //身份为分销员，规则不为分销规则不显示
                $saler_rule_source = array(Reward_rule_model::REWARD_SOURCE_FIXED, Reward_rule_model::REWARD_SOURCE_SALER);
                if ($saler_info['saler_type'] == 'STAFF' && !in_array($rule['reward_source'], $saler_rule_source)) {
                    continue;
                }

                // 不存在秒杀时不显示秒杀规则
                if(!$killsec && $rule['rule_type'] == Reward_rule_model::SETTLE_KILLSEC) {
                    continue;
                }

                //第一条规则
                if($effective_rule == false) {
                    $effective_rule = $rule;
                }

                //秒杀优先
                if($killsec && $rule['sort'] == $effective_rule['sort'] && $rule['rule_type'] == Reward_rule_model::SETTLE_KILLSEC) {
                    $effective_rule = $rule;
                    break;
                }
            }
            if($effective_rule) {
                if($effective_rule['reward_type'] == Reward_rule_model::REWARD_TYPE_FIXED) {
                    //固定金额保留两位小数
                    $effective_rule['reward_rate'] = round($effective_rule['reward_rate'], 2);
                } else {
                    //界面显示为百分比
                    $effective_rule['reward_rate'] = $effective_rule['reward_rate'] * 100;
                }
                $effectiveRule = $effective_rule;
            }
        }

        //分销通知面板
        $saleBlock = [];
        if(!empty($saler_info) && !empty($effectiveRule)){
            $saleTitle = null;
            if($saler_info['saler_type'] == 'FANS'){
                $saleTitle = '粉丝福利：';
            }
            else{
                $saleTitle = '员工福利：';
            }
            $saleTitle .= '分享本产品，您的好友购买成功后，您将获得';
            if($effectiveRule['reward_type'] == Reward_rule_model::REWARD_TYPE_PERCENT){
                $saleTitle .= '订单';
            }
            $saleBlock[] = $saleTitle;
            $saleTitle = $effectiveRule['reward_rate'];
            if($effectiveRule['reward_type'] == Reward_rule_model::REWARD_TYPE_PERCENT){
                $saleTitle .= '%';
            }
            else{
                $saleTitle .= '元';
            }
            $saleBlock[] = $saleTitle;
            $saleTitle = '红包奖励，';
            if($saler_info['saler_type'] == 'FANS'){
                $saleTitle .= '隔天发至您的微信钱包';
            }
            else{
                $saleTitle .= '由酒店发放';
            }
            $saleBlock[] = $saleTitle;
        }

        //返回链接
        $salerId = $fansSalerId = $ruleId = null;
        if(!empty($saler_info)){
            if($saler_info['saler_type'] == 'STAFF'){
                $salerId = $saler_info['saler_id'];
            }
            if($saler_info['saler_type'] == 'FANS'){
                $fansSalerId = $saler_info['saler_id'];
            }
        }
        if(!empty($auto_rule)){
            $ruleId = $ruleId[0]['rule_id'];
        }
        $page_resource = [
            'link' => [
                'home' => $this->link['home'],
                'order' =>  $this->link['order_link'],
                'pay' => $this->link['prepay_link']
            ]
        ];

        //公众号信息
        $publicInfo = ['name' => data_get($this->public, 'name')];

        //酒店信息
        $this->load->model('hotel/Hotel_model', 'hotelModel');
        $hotelInfo = $this->hotelModel->get_hotel_detail($this->inter_id, $productDetail['hotel_id']);
        $hotelInfo = [
            'name' => data_get($hotelInfo, 'name'),
            'address' => data_get($hotelInfo, 'address'),
            'latitude' => data_get($hotelInfo, 'latitude'),
            'longitude' => data_get($hotelInfo, 'longitude'),
            'qrcode' => WxService::getInstance()->getQrcode(WxService::QR_CODE_SOMA_PUBLIC)->getData()
        ];

        $data['public_info'] = $publicInfo;
        $data['hotel_info'] = $hotelInfo;
        $data['page_resource'] = $page_resource;
        $data['saler_banner'] = $saleBlock;
        $data['product_info'] = $productDetail;

        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/list",
     *     summary="home products",
     *     description="return products",
     *     operationId="get_list",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="第几页",
     *         in="query",
     *         name="page",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="每页行数",
     *         in="query",
     *         name = "page_size",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="传1显示广告和分类 传2不显示",
     *         in="query",
     *         name="show_ads_cat",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="分类id",
     *         in="query",
     *         name="fcid",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="products",
     *                  description="商品列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="ads",
     *                  description="广告 show_ads_cat=1时才显示",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/SomaAdv")
     *               ),
     *              @SWG\Property(
     *                  property="categories",
     *                  description="分类 show_ads_cat=1时才显示",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/SomaCate")
     *              ),
     *              @SWG\Property(
     *                  property="page_resource",
     *                  description="分页信息和页面链接",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="page",
     *                      description="第几页",
     *                      type = "integer",
     *                  ),
     *                  @SWG\Property(
     *                      property="size",
     *                      description="每页多少行",
     *                      type = "integer",
     *                  ),
     *                  @SWG\Property(
     *                      property="count",
     *                      description="总条数",
     *                      type = "integer",
     *                  ),
     *                  @SWG\Property(
     *                      property="link",
     *                      description="页面链接",
     *                      type="object",
     *                      @SWG\Property(
     *                          property="detail",
     *                          description="商品详情链接",
     *                          type = "string",
     *                      ),
     *                     @SWG\Property(
     *                          property="home",
     *                          description="首页",
     *                          type = "string",
     *                      ),
     *                     @SWG\Property(
     *                          property="order",
     *                          description="订单",
     *                          type = "string",
     *                      ),
     *                     @SWG\Property(
     *                          property="center",
     *                          description="我的",
     *                          type = "string",
     *                      )
     *                  ),
     *             )
     *         )
     *    )
     *)
     */
    public function get_list()
    {
        $page_data = [];

        $filter_cat   = $this->input->get('fcid');
        $show_ads_cat = $this->input->get('show_ads_cat');
        $page_size    = $this->input->get('page_size', null, 20);
        $page         = $this->input->get('page', null, 1);

        if ($show_ads_cat == MY_Model_Soma::STATUS_TRUE) {
            //首页广告图
            $this->load->model('soma/Adv_model', 'ads_model');
            $page_data['advs'] = $this->ads_model->get_ads_by_category($this->inter_id);

            //分类
            $this->load->model('soma/Category_package_model', 'categoryModel');
            $page_data['categories'] = $this->categoryModel->get_package_category_list($this->inter_id, null, 5, $filter_cat);
        }

        //商品
        $this->load->model('soma/Product_package_model', 'productPackageModel');
        $productModel = $this->productPackageModel;

        $nowTime = date('Y-m-d H:i:s');
        $where   = [
            'and inter_id = '         => $this->inter_id,
            'and is_hide = '          => $productModel::STATUS_CAN_YES,
            'and status = '           => $productModel::STATUS_ACTIVE,
            'and validity_date < '    => $nowTime,
            'and un_validity_date > ' => $nowTime,
            'and (date_type = '       => $productModel::DATE_TYPE_FLOAT,
            'or (date_type = '        => $productModel::DATE_TYPE_STATIC,
            'and expiration_date > '  => "'" . $nowTime . "'))",
        ];
        if(!empty($filter_cat)) {
            $where['and cat_id = '] = $filter_cat;
        }

        $select = [
            'product_id', 'inter_id', 'cat_id', 'goods_type', 'face_img', 'name',
            'price_market','price_package', 'stock', 'is_hide', 'validity_date',
            'sort', 'status', 'type','date_type', 'expiration_date', 'sales_cnt',
        ];
        $options = [
            'limit' => $page_size, 'offset' => ($page - 1) * $page_size, 
            'orderBy' => 'sort DESC', 'page' => $page
        ];

        $products = $productModel->paginate(array_keys($where), array_values($where), $select, $options);

        //相关链接
        $ext['page']           = (int)$page;
        $ext['size']           = (int)$page_size;
        $ext['link']['detail'] = $this->link['product_link'];
        $ext['link']['home']   = $this->link['home'];
        $ext['link']['order']  = $this->link['order_link'];
        $ext['link']['center'] = $this->link['center_link'];

        if (empty($products['data'])) {
            $page_data['products']      = [];
            $ext['count']               = 0;
            $page_data['page_resource'] = $ext;
            $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $page_data);
            return;
        }

        $productIds = array_column($products['data'], 'product_id');
        $result     = $pointProductIds = array();

        //拿到积分商品的id
        foreach ($products['data'] as $k => $p) {
            //做过期处理过滤
            if ($p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC) {
                if (!($productModel->isAvaliable($p))) {
                    unset($products[$k]);
                    continue;
                }
            }

            if ($p['type'] == $productModel::PRODUCT_TYPE_POINT) {
                $pointProductIds[] = $p['product_id'];
            }
            $result[$p['product_id']] = $p;

            //商品默认参加活动是非秒杀
            $result[$p['product_id']]['product_type'] = $productModel::PRODUCT_ACTIVITY_DEFAULT;
        }

        //满减活动
        $this->load->model('soma/Sales_rule_model', 'salesRuleModel');
        $rules = $this->salesRuleModel->get_product_rule($productIds, $this->inter_id);

        if ($rules) {
            foreach ($rules as $rule) {
                if ($rule['scope'] == Soma_base::STATUS_TRUE) {
                    //全部适用
                    // 非满减规则过滤
                    $not_auto_rule_arr = array(Sales_rule_model::RULE_TYPE_POINT, Sales_rule_model::RULE_TYPE_BALENCE);
                    if (!in_array($rule['rule_type'], $not_auto_rule_arr)) {
                        foreach ($productIds as $v) {
                            if (!isset($result[$v]['auto_rule'])) {
                                $result[$v]['auto_rule'] = $rule;
                            }
                        }
                    }
                } else {
                    foreach ($rule['product_id'] as $rule_pid) {
                        $result[$rule_pid]['auto_rule'] = $rule;
                    }
                }
            }
        }

        //秒杀列表
        $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
        $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);

        foreach ($killsecs as $killsec) {
            if (in_array($killsec['product_id'], $pointProductIds)) {
                $killsec['killsec_price'] = ceil($killsec['killsec_price']);
            }
            /** 对秒杀开始时间进行处理 */
            $result[$killsec['product_id']]['killsec'] = $killsec;
            $result[$killsec['product_id']]['product_type'] = $productModel::PRODUCT_ACTIVITY_KILLSEC;
        }

        // 商品多规格,多规格商品显示最低的规格价格
        $this->load->model('soma/Product_specification_setting_model', 'psp_model');

        if ($productIds) {
            $psp_setting = $this->psp_model->get_inter_product_spec_setting($this->inter_id, $productIds);
            if (!empty($psp_setting)) {
                $settingList = $this->_get_setting_info($psp_setting);
                $psp_setting = $settingList['settingInfo'];
                foreach ($psp_setting as $pid => $setting) {
                    $result[$pid]['price_package'] = $setting[0]['spec_price'];
                }
            }
        }

        $page_data['products'] = array_values($result);

        //取出秒杀id
        $act_id = array();
        foreach ($page_data['products'] as $p) {
            if ($p['product_type'] == $productModel::PRODUCT_ACTIVITY_KILLSEC) {
                $act_id[] = $p['killsec']['act_id'];
            }
        }

        //获取用户订阅的act_id
        $actids = array();
        if (!empty($act_id)) {
            $openid_actid = KillsecService::getInstance()->getOpenidSubscribActid($act_id, $this->inter_id, $this->openid);
        }
        if (!empty($openid_actid)) {
            $actids = array_column($openid_actid, 'act_id');
        }

        //给商品追加价格配置的东西
        foreach ($page_data['products'] as &$val) {
            if ($val['product_type'] == $productModel::PRODUCT_ACTIVITY_KILLSEC) {
                //秒杀时间设成秒数
                $val['killsec']['killsec_time'] = strtotime($val['killsec']['killsec_time']);
                $val['killsec']['end_time'] = strtotime($val['killsec']['end_time']);

                //更改订阅状态 1表示已设置提醒 2表示未设置提醒
                $val['killsec']['subscribe_status'] = 2;
                if (!empty($actids)) {
                    if (in_array($val['killsec']['act_id'], $actids)) {
                        $val['killsec']['subscribe_status'] = 1;
                    }
                }
            }

            //价格.00去掉
            if (stripos($val['price_market'], '.00') !== false) {
                $val['price_market'] = number_format($val['price_market']);
            }
            if (stripos($val['price_package'], '.00') !== false) {
                $val['price_package'] = number_format($val['price_package']);
            }
            if (isset($val['killsec'])) {
                if (stripos($val['killsec']['killsec_price'], '.00') !== false) {
                    $val['killsec']['killsec_price'] = number_format($val['killsec']['killsec_price']);
                }
            }
        }
        unset($val);

        $page_data['products'] = PackageService::getInstance()->composePackage($page_data['products'], $this->inter_id, $this->openid);

        //分页数据
        $ext['count'] = $products['total'];
        $page_data['page_resource'] = $ext;

        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $page_data);
    }

    /**
     * 处理规格信息
     * @param $psp_setting
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    protected function _get_setting_info($psp_setting)
    {
        $dataTicket = $data = array();
        //判断是否有门票类的
        $isTicket = false;
        foreach ($psp_setting as $row) {
            if ($row['type'] == Soma_base::STATUS_TRUE) {
                $data[$row['product_id']][] = $row;
            } elseif ($row['type'] == Soma_base::STATUS_FALSE) {
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
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/spec",
     *     summary="获取商品规格",
     *     description="获取商品规格信息，包括款式、价格、库存等",
     *     operationId="get_spec",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="pid",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  description="返回数据" ,
     *                  @SWG\Items(
     *                        type="object",
     *                        @SWG\Property(
     *                            property="spec_type",
     *                            type="array" ,
     *                            description="款式标题" ,
     *                        ),
     *                        @SWG\Property(
     *                            property="spec_name",
     *                            type="array" ,
     *                            description="款式选项" ,
     *                        ),
     *                        @SWG\Property(
     *                            property="spec_name_id",
     *                            type="array" ,
     *                            description="该项作为款式选项的隐藏值" ,
     *                        ),
     *                        @SWG\Property(
     *                            property="spec_id",
     *                            type="array" ,
     *                            description="所选款式的组合隐藏值" ,
     *                        ),
     *                        @SWG\Property(
     *                            property="setting_id",
     *                            type="array" ,
     *                            description="款式id，该项作为用户选完款式后的key，作为同级的data的key,可以知道所选款式的单价、库存" ,
     *                        ),
     *                        @SWG\Property(
     *                            property="data",
     *                            type="array" ,
     *                            description="商品所有可能组合的款式列表，里面有组合商品所有数值" ,
     *                        ),
     *                  ),
     *              ),
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid pid supplied"
     *     )
     * )
     */
    public function get_spec()
    {
        $pid = $this->input->get('pid');
        if(empty($pid)){
            show_error('Invalid pid supplied', 400);
        }
        $this->load->model('soma/Product_specification_setting_model', 'psp_model');
        $data = $this->psp_model->get_full_specification_compose($this->inter_id, $this->input->get('pid', true), Soma_base::STATUS_TRUE);
        if(empty($data)){
            //$data = ['' => ''];
        }
        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/rule",
     *     summary="用于下单时，返回当前商品使用的优惠规则，包括种类、总价、是否能使用优惠券等",
     *     description="返回当前商品使用的优惠规则，包括种类、总价、是否能使用优惠券等",
     *     operationId="get_rule",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="pid",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="购买数量",
     *         in="query",
     *         name="qty",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="结算类型",
     *         in="query",
     *         name="stl",
     *         required=true,
     *         type="string",
     *         default="default"
     *     ),
     *     @SWG\Parameter(
     *         description="多规格商品组合id",
     *         in="query",
     *         name="psp_sid",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="activity",
     *                  type="object",
     *                  description="立减活动" ,
     *                  @SWG\Items(
     *                        type="object",
     *                        @SWG\Property(
     *                            property="status",
     *                            type="integer" ,
     *                            enum={1, 2},
     *                            description="是否为立减活动（1：是，2：否）" ,
     *                        ),
     *                        @SWG\Property(
     *                            property="auto_rule",
     *                            type="array" ,
     *                            description="立减活动规则" ,
     *                            @SWG\Items(
     *                                 @SWG\Property(
     *                                    property="rule_type",
     *                                    type="integer" ,
     *                                    description="类型" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="name",
     *                                    type="string" ,
     *                                    description="名称" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="reduce_cost",
     *                                    type="float" ,
     *                                    description="抵扣金额" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="least_cost",
     *                                    type="float" ,
     *                                    description="使用下限" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="can_use_coupon",
     *                                    type="integer" ,
     *                                    enum={1, 2},
     *                                    description="限制使用优惠券（1：可用 2：不可用）" ,
     *                                 ),
     *                            )
     *                        )
     *                      ),
     *                  ),
     *                  @SWG\Property(
     *                      property="asset",
     *                      type="object",
     *                      description="积分储值" ,
     *                      @SWG\Items(
     *                          type="object",
     *                          @SWG\Property(
     *                              property="status",
     *                              type="integer" ,
     *                              enum={1, 2},
     *                              description="积分储值（1：是，2：否）" ,
     *
     *                          ),
     *                          @SWG\Property(
     *                              property="cal_rule",
     *                              type="array" ,
     *                              description="积分储值规则" ,
     *                              @SWG\Items(
     *                                 @SWG\Property(
     *                                    property="rule_type",
     *                                    type="integer" ,
     *                                    description="类型" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="quote",
     *                                    type="string" ,
     *                                    description="使用额度" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="reduce_cost",
     *                                    type="float" ,
     *                                    description="抵扣金额" ,
     *                                 ),
     *                                 @SWG\Property(
     *                                    property="can_use_coupon",
     *                                    type="float" ,
     *                                    description="使用下限" ,
     *                                 ),
     *                            )
     *                        )
     *                      ),
     *                  ),
     *              ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid pid supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Package not found"
     *     )
     * )
     */
    public function get_rule()
    {

        $data = [];

        $pid = $this->input->get('pid');
        $pqty = $this->input->get('qty');
        $settlement = $this->input->get('stl');
        $psp_sid = $this->input->get('psp_sid', true);
        if(empty($pid)){
            show_error('Invalid pid supplied', 400);
        }

        $this->load->model('soma/Product_package_model');
        $products = $this->Product_package_model->get_product_package_by_ids(array($pid), $this->inter_id);
        if(empty($products)){
            show_404();
        }

        //积分商品不使用任何规则
        if($products[0]['type'] != Product_package_model::PRODUCT_TYPE_POINT) {

            if(isset($products[0]['scopes'])) {
                $data = ['asset' => ['status' => Soma_base::STATUS_FALSE, 'cal_rule' => ['can_use_coupon' => Soma_base::STATUS_FALSE]]];
            }
            else{

                $subtotal = 0;

                ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->inter_id, $this->openid);

                //多规格，替换原来产品中的库存与价格
                $this->load->model('soma/Product_specification_setting_model', 'psp_model');
                if(!empty($products) && $psp_setting = $this->psp_model->load($psp_sid)) {
                    $products[0]['price_package'] = $psp_setting->m_get('spec_price');
                }

                foreach($products as $k => $v) {
                    $p_type = $v['type'];
                    $products[$k]['qty'] = $pqty;
                    $subtotal += $v['price_package'] * $pqty;
                }

                $this->load->model('soma/Sales_rule_model');
                $rules = $this->Sales_rule_model->get_discount_rule($this->inter_id, $this->openid, $products, $subtotal, $settlement);

                if(isset($rules['auto_rule'])) {
                    $activity = array('status' => Soma_base::STATUS_TRUE, 'auto_rule' => $rules['auto_rule'],);
                }
                else{
                    $activity = array('status' => Soma_base::STATUS_FALSE, 'auto_rule' => array(),);
                }

                if(isset($rules['cal_rule'])) {
                    $asset = array('status' => Soma_base::STATUS_TRUE, 'cal_rule' => $rules['cal_rule'],);
                }
                else{
                    $asset = array('status' => Soma_base::STATUS_FALSE, 'cal_rule' => array(),);
                }

                $data = array('activity' => $activity, 'asset' => $asset, 'base_info' => $rules['base_info']);
            }
        }

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/ticket_time",
     *     summary="时间多规格的选择",
     *     description="获取门票时间",
     *     operationId="get_ticket_time",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="pid",
     *         required=false,
     *         type="integer",
     *         format="int32",
     *     ),
     *    @SWG\Parameter(
     *         description="业务类型",
     *         in="query",
     *         name="bsn",
     *         required=true,
     *         type="string",
     *         default="package"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="product",
     *                  description="商品信息",
     *                  type = "object",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="setting_list",
     *                  description="商品日期规格设置信息",
     *                  type = "object",
     *                  @SWG\Items(ref="#/definitions/SomaProductSpecification")
     *               ),
     *              @SWG\Property(
     *                  property="setting_data",
     *                  description="商品日期规格设置信息，该项与setting_list数据大致一样，格式类型会有区别",
     *                  type = "object",
     *                  @SWG\Items(ref="#/definitions/SomaProductSpecification")
     *              ),
     *              @SWG\Property(
     *                  property="bsn",
     *                  description="商品业务类型",
     *                  type = "string",
     *                  default="package"
     *              ),
     *              @SWG\Property(
     *                  property="settlement",
     *                  description="商品结算类型",
     *                  type = "string",
     *                  default="default"
     *              ),
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid pid supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Package not found"
     *     ),
     * )
     */
    public function get_ticket_time()
    {

        $productId = $this->input->get('pid');
        if(!$productId) {
            show_error('Invalid pid supplied', 400);
        }

        $this->load->model('soma/Product_package_model','somaProductPackageModel');
        $productDetail = $this->somaProductPackageModel->get_product_package_detail_by_product_id($productId, $this->inter_id);
        if(!$productDetail)
        {
          show_404();
        }

        //给商品追加价格配置的东西
        $productDetail = PackageService::getInstance()->composePackage([$productDetail], $this->inter_id, $this->openid)[0];

        //获取门店规格信息
        $settingList = PackageService::getInstance()->getSettingInfoByProductId($this->inter_id, $productId, Soma_base::STATUS_FALSE);

        //处理规格信息
        $settingData = array();
        if (isset($settingList['data']) && $settingList['data'])
        {
            //使用用户的 价格配置 修改价格
            if (isset($productDetail['scopes'])) {
                foreach($settingList['data'] as &$setting) {
                    foreach ($productDetail['scopes'] as $scope) {
                        if ($setting['setting_id'] == $scope['setting_id']) {
                            $setting['specprice'] = $scope['price'];
                            $setting['spec_price'] = $scope['price'];
                        }
                    }

                }
            }
            foreach ($settingList['data'] as $k => $v)
            {
                $da                                             = array();
                $yearMonth                                      = date('Y/n',$k);
                $da['time']                                     = date('Y-n-j',$k);
                $da['money']                                    = $v['specprice'];
                $da['stock']                                    = $v['spec_stock'];
                $da['psp_sid']                                  = $v['setting_id'];
                $settingData[$yearMonth]['data']                = $yearMonth;
                $settingData[$yearMonth]['month'][]             = $da;
            }
        }

        $data = array(
            'product' => $productDetail,
            'setting_list' => $settingList,
            'setting_data' => $settingData,
            'bsn' => $this->input->get('bsn'),
            'settlement' => 'default'
        );

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }


    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/coupons",
     *     summary="用于下单时，获取使用券",
     *     description="使用券目前分三种，抵扣券、兑换券、折扣券",
     *     operationId="get_coupons",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="pid",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="购买数量",
     *         in="query",
     *         name="qty",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="券类型。抵扣券：1，兑换券：3，折扣券：2",
     *         in="query",
     *         name="card_type",
     *         required=true,
     *         type="string",
     *         enum={1, 2, 3}
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  description="优惠券列表" ,
     *                  @SWG\Items(ref="#/definitions/SomaSalesCouponProduct"),
     *              ),
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid pid supplied"
     *     )
     * )
     */
    public function get_coupons()
    {

        $data = [];

        $params = $this->input->get();
        $cardType = $params['card_type'] + 0;
        $count = $params['qty'];
        $pid = $params['pid'];

        if(empty($pid)){
            show_error('Invalid pid supplied', 400);
        }

        $this->load->model('soma/Product_package_model');
        $products = $this->Product_package_model->get_product_package_by_ids([$pid], $this->inter_id);
        $subtotal = 0;
        if (!empty($products)) {
            foreach ($products as $k => $v) {
                $subtotal += $v['price_package'] * $count;  //累计订单总额
            }
        }
        else{
            $this->json(BaseConst::OPER_STATUS_SUCCESS, $this->lang->line('no_coupon_available'), $data);
            return ;
        }

        //读取购买人的可用券
        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $result = $api->conpon_sign_list($this->openid);

        $card_ids = array();
        if (isset($result['data']) && count($result['data']) > 0) {
            $coupons = array();
            foreach ($result['data'] as $v) {
                if (!in_array($v->card_id, $card_ids)) {
                    $card_ids[] = $v->card_id;
                }
            }
            $this->load->model('soma/Sales_order_discount_model');
            $discountModel = $this->Sales_order_discount_model;
            $this->load->model('soma/Sales_coupon_model');
            $link_all = $this->Sales_coupon_model->get_coupon_product_list($card_ids, $this->inter_id);

            //取出适用所有商品的优惠券，格式：array('card_id'=>'券1',)
            $wide_scope_coupon = $this->Sales_coupon_model->get_wide_scope_coupon($this->inter_id, true);

            foreach ($result['data'] as $k => $v) {
                //逐张优惠券判断是否满足购物条件
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
                    $tmp['scopeType'] = $this->lang->line('all_goods_apply');

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
                                    $tmp['scopeType'] = $this->lang->line('some_goods_apple');

                                } else {
                                    $tmp['usable'] = false;  //该卡不符合使用条件
                                    $tmp['scopeType'] = $this->lang->line('no_goods_can_apply');
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
                    $tmp['expire_time'] = str_replace('[0]', ceil($minusTime / 86400), $this->lang->line('expire_after_some_days'));
                } else {
                    $tmp['expire_time'] = $this->lang->line('expire_at_') . '：' . date("Y-m-d", $tmp['expire_time']);
                }

                $coupons[] = $tmp;
            }

            //将不可用的券排到最后面
            $can_use_arr = array();
            foreach ($coupons as $k => $v) {
                if (isset($v['usable']) && $v['usable'] == true) {
                    $can_use_arr[] = $v;
                    unset($coupons[$k]);
                }
            }
            foreach ($can_use_arr as $k => $v) {
                array_unshift($coupons, $v);
            }

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

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/recommended",
     *     summary="recommended products",
     *     description="推荐位商品",
     *     operationId="get_recommended",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="推荐位第几页",
     *         in="query",
     *         name="page",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="推荐位每页行数",
     *         in="query",
     *         name = "page_size",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="products",
     *                  description="推荐位商品列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="page_resource",
     *                  description="分页信息和页面链接",
     *                  type = "object",
     *                  @SWG\Property(
     *                      property="page",
     *                      description="第几页",
     *                      type = "integer",
     *                  ),
     *                  @SWG\Property(
     *                      property="size",
     *                      description="每页多少行",
     *                      type = "integer",
     *                  ),
     *                  @SWG\Property(
     *                      property="count",
     *                      description="总条数",
     *                      type = "integer",
     *                  ),
     *                  @SWG\Property(
     *                      property="link",
     *                      description="页面链接",
     *                      type = "object",
     *                      @SWG\Property(
     *                          property="detail",
     *                          description="商品详情链接",
     *                          type = "string",
     *                      ),
     *                      @SWG\Property(
     *                          property="home",
     *                          description="首页链接",
     *                          type = "string",
     *                      )
     *                  )
     *              )
     *         )
     *     )
     * )
     */
    public function get_recommended()
    {
        $page_size = $this->input->get('page_size', null, 20);
        $page = $this->input->get('page', null, 1);
        $result = PackageService::getInstance()->getRecommend($page, $page_size, 'soma_package_package_detail', $this->inter_id);

        //分页数据
        $ext['page'] = $page;
        $ext['size'] = $page_size;
        $ext['count'] = $result['count'];
        $ext['link']['detail'] = $this->link['product_link'];
        $ext['link']['home'] = $this->link['home'];

        $res = array(
            'products' => $result['products'],
            'page_resource' => $ext
        );

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $res);

    }

    /**
     * 购买成功
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/success_pay",
     *     summary="购买成功",
     *     description="购买成功",
     *     operationId="get_success_pay",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="订单id",
     *         in="query",
     *         name = "oid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="products",
     *                  description="推荐位商品列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="qr_code",
     *                  description="二维码",
     *                  type = "string",
     *              ),
     *             @SWG\Property(
     *                  property="subscribe_status",
     *                  description="订阅状态 1：已关注 0：未关注",
     *                  type = "integer",
     *              ),
     *              @SWG\Property(
     *                  property="page_resource",
     *                  description="分页信息和页面链接",
     *                  type = "object",
     *                  @SWG\Property(
     *                      property="link",
     *                      description="页面链接",
     *                      type = "object",
     *                      @SWG\Property(
     *                          property="product_detail",
     *                          description="商品详情链接",
     *                          type = "string",
     *                      ),
     *                  @SWG\Property(
     *                          property="order_detail",
     *                          description="订单详情链接",
     *                          type = "string",
     *                      )
     *                  )
     *              )
     *         )
     *     )
     * )
     */
    public function get_success_pay()
    {
        $res = [];
        
        $oid = $this->input->get('oid');

        if(empty($oid)) {
            show_404();
        }

        $this->load->model('soma/Sales_order_model', 'o_model');
        $order = $this->o_model->getByID($oid);

        if (empty($order)) {
            show_404();
        }

        $this->load->model('soma/Sales_item_package_model');
        $items = $this->Sales_item_package_model->get(
            [
                'order_id'
            ],
            [
                $oid
            ],
            'order_id, product_id'
        );
        if (empty($items) || empty($items[0])) {
            show_404();
        }

        $header = array(
            'title' => '购买成功'
        );
        if ($this->inter_id == 'a490782373') {
            $header['title'] = '申请成功';
        }

        $productID = $items[0]['product_id'];

        $orderDetailLink = $this->link['order_link'];
        //todo 这个怎么没有改
        $productDetailLink = Soma_const_url::inst()->get_url('soma/package/package_detail/', ['pid' => $productID, 'id' => $this->inter_id]);

        $result = WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE);
        $this->load->model('wx/Fans_model', 'fansModel');
        $subscribeStatus = $this->fansModel->subscribeStatus($this->inter_id, $this->openid);

        //返回
        $ext['link']['order_detail'] = $orderDetailLink;
        $ext['link']['product_detail'] = $productDetailLink;
        $res['header'] = $header;
        $res['qr_code'] = $result->getData();
        $res['subscribe_status'] = ($subscribeStatus)? 1:0;
        $res['page_resource'] = $ext;

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $res);

    }
    


    //test
    public function get_qr(){

        $result = WxService::getInstance()->getQrcode(WxService::QR_CODE_SOMA_PUBLIC);

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $result->getData());
    }

}