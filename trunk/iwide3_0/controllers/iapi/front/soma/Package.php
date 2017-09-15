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
     *                  property="fans_info",
     *                  description="用户信息",
     *                  type = "object"
     *              ),
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
        $productDetail = $this->productPackageModel->getByID($productId, $this->inter_id);
        if (empty($productDetail)) {
            show_404();
        }

        //商品内容
        $productDetail['compose'] = unserialize($productDetail['compose']);

        //商品相册
        $productDetail['gallery'] = $this->productPackageModel->get_gallery_front($productId, $this->inter_id);

        //追加商品的英文信息
        $this->productPackageModel->appendEnInfo($productDetail);

        //初始化
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
                //订阅
                $killsec['is_subscribe'] = false;
                $openAct = KillsecService::getInstance()->getOpenidSubscribActid([$killsec['act_id']], $this->inter_id, $this->openid);
                if(count($openAct)) {
                    $killsec['is_subscribe'] = true;
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
                $settingList = PackageService::getInstance()->getSettingInfoCompose($ps_detail);
                $ps_detail = $settingList['settingInfo'];
                $isTicket = $settingList['isTicket'];
                if($ps_detail) {
                    $specProduct = true;
                    foreach ($ps_detail as $pid => $setting){
                        $productDetail['price_package'] = PackageService::getInstance()->progressNumber($setting[0]['spec_price']);
                    }
                }
            }
        }

        $productDetail['spec_product'] = $specProduct;
        $productDetail['isTicket'] = $isTicket;
        $productDetail['ticketId'] = $this->session->userdata('tkid') ? $this->session->userdata('tkid') : null;

        //查询自身分销员信息 'a450941565', 'oJ3KZs3upmsdv8Q7WbAcDOsUDsLE'
        $saler_info = PackageService::getInstance()->getUserSalerOrFansaler($this->inter_id, $this->openid);

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

        $page_resource = [
            'link' => [
                'home' => $this->link['home'],
                'order' =>  $this->link['order_link'],
                'prepay' => $this->link['prepay_link'].'&pid='.$productId
            ]
        ];

        //公众号信息
        $publicInfo = ['name' => data_get($this->public, 'name')];

        //酒店信息
        $this->load->model('hotel/Hotel_model', 'hotelModel');
        $hotelInfo = $this->hotelModel->get_hotel_detail($productDetail['inter_id'], $productDetail['hotel_id']);
        $qrCode = null;
        try{
            $qrCode = WxService::getInstance()->getQrcode(WxService::QR_CODE_SOMA_PUBLIC)->getData();
        }
        catch (Exception $e){

        }
        $hotelInfo = [
            'name' => data_get($hotelInfo, 'name'),
            'address' => data_get($hotelInfo, 'address'),
            'latitude' => data_get($hotelInfo, 'latitude'),
            'longitude' => data_get($hotelInfo, 'longitude'),
            'qrcode' => $qrCode
        ];

        //用户信息
        $fansInfo = ['is_fans' => false];
        $this->load->model('wx/Fans_model', 'fansModel');
        $subscribeStatus = $this->fansModel->subscribeStatus($this->inter_id, $this->openid);
        //没有关注
        if($subscribeStatus) {
            $fansInfo = ['is_fans' => true];
        }

        $data['fans_info'] = $fansInfo;
        $data['public_info'] = $publicInfo;
        $data['hotel_info'] = $hotelInfo;
        $data['page_resource'] = $page_resource;
        $data['saler_banner'] = $saleBlock;
        $data['product_info'] = $productDetail;
        //$this->output->enable_profiler(TRUE);
        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/list",
     *     summary="商品首页列表",
     *     description="商品首页列表",
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
            $filter = [
                'inter_id' => $this->inter_id,
                'status' => MY_Model_Soma::STATUS_TRUE
            ];
            $option = [
                'limit' => null,
                'orderBy' => 'cat_sort desc',
            ];
            $page_data['categories'] = $this->categoryModel->get(array_keys($filter), array_values($filter), '*', $option);
        }


        //多店铺
        $ticketId = PackageService::getInstance()->getParams()['tkid'];
        $pIds = [];
        if ($ticketId) {

            //获取产品id列表
            $serviceName = $this->serviceName(Product_Service::class);
            $serviceAlias = $this->serviceAlias(Product_Service::class);
            $this->load->service($serviceName, null, $serviceAlias);
            $catId = $filter_cat;
            $info = $this->soma_product_service->getProductPackageTicketProductIds($ticketId);
            if($info){
                $pIds = array_column($info['products'], 'product_id');
                $ticketDetail = current($info['ticketList']);
            }
            //门店设置了皮肤
            if (isset($ticketDetail['theme_path']) && $ticketDetail['theme_path']) {
                $this->theme = $ticketDetail['theme_path'];
            }
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
        if(!empty($pIds)){
            $pIds = "'" . implode("','", $pIds) . "'";;
            $where['and product_id in ('] = $pIds.')';
        }
        if(!empty($filter_cat)) {
            $where['and cat_id = '] = $filter_cat;
        }

        $select = [
            'product_id', 'inter_id', 'cat_id', 'goods_type', 'face_img', 'name',
            'price_market','price_package', 'stock', 'is_hide', 'validity_date',
            'sort', 'status', 'type','date_type', 'expiration_date', 'sales_cnt', 'show_sales_cnt'
        ];
        $options = [
            'limit' => $page_size, 'offset' => ($page - 1) * $page_size,
            'orderBy' => 'sort DESC, product_id DESC', 'page' => $page
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

        $page_data['products'] = array_values($result);

        //取出秒杀id
        $act_id = array();
        $kill_sec_times = array();
        foreach ($page_data['products'] as $p) {
            if ($p['product_type'] == $productModel::PRODUCT_ACTIVITY_KILLSEC) {
                $act_id[] = $p['killsec']['act_id'];
                $kill_sec_times[] = $p['killsec']['killsec_time'];
            }
        }

        //获取用户订阅的act_id
        $actids = array();
        if (!empty($act_id)) {
            $openid_actid = KillsecService::getInstance()->getOpenidSubscribKilltime($act_id, $this->inter_id, $this->openid, $kill_sec_times);
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
        }
        unset($val);

        $page_data['products'] = PackageService::getInstance()->composePackage($page_data['products'], $this->inter_id, $this->openid);
        //分页数据

        $ext['count'] = $products['total'];
        $page_data['page_resource'] = $ext;
        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $page_data);
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

        $pid = $this->input->get('pid');
        $qty = $this->input->get('qty');
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

        $data = [];

        //积分商品不使用任何规则
        if($products[0]['type'] != Product_package_model::PRODUCT_TYPE_POINT) {

            $data = PackageService::getInstance()->getProductRules($products, $settlement, $psp_sid, $qty, $this->inter_id, $this->openid);
        }

        //购买数量
        $data['count'] = 1;
        $auto_rule = PackageService::getInstance()->getProductAutoRule($products[0], $this->inter_id);
        if($auto_rule){
            $data['count'] = PackageService::getInstance()->getProductDefaultCount($products[0], $auto_rule[0]['rule_id']);
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
     *         description="券类型。-1：所有，1：抵扣券（reduce_cost），2：折扣券（discount），3：兑换券",
     *         in="query",
     *         name="card_type",
     *         required=true,
     *         type="string",
     *         enum={-1, 1, 2, 3}
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
     *                  @SWG\Items(ref="#/definitions/SomaSalesCoupon"),
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

        $params = $this->input->get();
        $cardType = $params['card_type'] + 0;
        $count = $params['qty'];
        $pid = $params['pid'];

        if(empty($pid)){
            show_error('Invalid pid supplied', 400);
        }
        if(empty($cardType)){
            show_error('Invalid card_type supplied', 400);
        }
        if(empty($count)){
            show_error('Invalid qty supplied', 400);
        }

        if($cardType == -1){
            $data = array_merge(
                PackageService::getInstance()->getProductCoupons($this->inter_id, $this->openid, $pid, $count, 1),
                PackageService::getInstance()->getProductCoupons($this->inter_id, $this->openid, $pid, $count, 2),
                PackageService::getInstance()->getProductCoupons($this->inter_id, $this->openid, $pid, $count, 3)
                //PackageService::getInstance()->getProductCoupons($this->inter_id, $this->openid, $pid, $count, 4)
            );
            $canUse = array();
            $cannotUse = array();
            foreach ($data as $k => $v) {
                if(isset($v['usable']) && $v['usable'] == true) {
                    $canUse[] = $v;
                }
                if(isset($v['usable']) && $v['usable'] == false) {
                    $cannotUse[] = $v;
                }
            }
            $data = array_merge($canUse, $cannotUse);
        }
        else{
            $data = PackageService::getInstance()->getProductCoupons($this->inter_id, $this->openid, $pid, $count, $cardType);
        }

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/recommended",
     *     summary="推荐位商品",
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
        $result = PackageService::getInstance()->composePackage($result, $this->inter_id, $this->openid);

        //分页数据
        $ext['page'] = $page;
        $ext['size'] = $page_size;
        $ext['link']['detail'] = $this->link['product_link'];
        $ext['link']['home'] = $this->link['home'];

        $res = array(
            'products' => $result,
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
     *                  property="qr_code",
     *                  description="二维码",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="header",
     *                  description="头部信息",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="product_id",
     *                  description="商品id",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="hotel_name",
     *                  description="酒店名",
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

        $header = '购买成功';

        $productID = $items[0]['product_id'];

        $orderDetailLink = $this->link['detail_link'].$oid;
        $productDetailLink = $this->link['product_link'];

        $result = WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE);
        $this->load->model('wx/Fans_model', 'fansModel');
        $subscribeStatus = $this->fansModel->subscribeStatus($this->inter_id, $this->openid);

        //返回
        $ext['link']['order_detail'] = $orderDetailLink;
        $ext['link']['product_detail'] = $productDetailLink;
        $res['header'] = $header;
        $res['qr_code'] = $result->getData();
        $res['hotel_name'] = $this->public['name'];
        $res['product_id'] = $productID;
        $res['subscribe_status'] = ($subscribeStatus)? 1:0;
        $res['page_resource'] = $ext;

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $res);

    }

    /**
     * 获取绩效商品
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/distribute_products",
     *     summary="获取绩效商品",
     *     description="获取绩效商品",
     *     operationId="distribute_products",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="页码",
     *         in="query",
     *         name = "page",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="排序。1：按销量降序，2：按绩效商品降序",
     *         in="query",
     *         name = "sort",
     *         required=true,
     *         type="integer"
     *     ),
     *          *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
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
     *     )
     * )
     */
    public function get_distribute_products(){

        $page = $this->input->get('page', null, 1);
        $sort = $this->input->get('sort', null, 1);
        $product_info = PackageService::getInstance()->getDistributeProducts($page, 10, $sort);
        $page_resource = [
            'page' => $page,
            'count' => $product_info['total'],
            'size' => 10,
            'link' => [
                'detail' => $this->link['product_link']
            ]
        ];
        $result = [
            'product_info' => $product_info['products'],
            'theme' => $product_info['theme'],
            'attach' => $product_info['attach'],
            'page_resource' => $page_resource
        ];
        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $result);
    }


    /**
     * 获取绩效商品/商城首页 二维码
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/distribute_qrcode",
     *     summary="获取绩效商品/商城首页 二维码",
     *     description="获取绩效商品/商城首页 二维码",
     *     operationId="distribute_qrcode",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="二维码跳转链接",
     *         in="query",
     *         name = "url",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object"
     *         )
     *     )
     * )
     */
    public function get_distribute_qrcode(){

        PackageService::getInstance()->getQrcodeStream($this->input->get('url', null, $this->link['home']));
    }



    /**
     * 用户是否已经关注该公众号，并返回公众号二维码
     * @SWG\Get(
     *     tags={"package"},
     *     path="/package/is_subscribe",
     *     summary="用户是否已经关注该公众号，并返回公众号二维码",
     *     description="用户是否已经关注该公众号，并返回公众号二维码",
     *     operationId="is_subscribe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="二维码跳转链接",
     *         in="query",
     *         name = "url",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="subscribe_status",
     *                  description="关注状态，true：已关注，false：未关注",
     *                  type = "bool",
     *              ),
     *              @SWG\Property(
     *                  property="qr_code_url",
     *                  description="公众号二维码",
     *                  type = "string",
     *              ),
     *         )
     *     )
     * )
     */
    public function get_is_subscribe(){

        /**
         * @var \Fans_model $fansModel
         */
        $this->load->model('wx/Fans_model', 'fansModel');
        $fansModel = $this->fansModel;
        $subscribeStatus = $fansModel->subscribeStatus($this->inter_id, $this->openid);
        $qrCodeUrl = WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE)->getData();

        $res = [
            'subscribe_status' => $subscribeStatus,
            'qr_code_url' => $qrCodeUrl
        ];

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $res);
    }

}