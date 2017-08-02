<?php
use App\libraries\Iapi\BaseConst;
use App\libraries\Iapi\FrontConst;
use App\libraries\Support\Log;
use App\services\Result;
use App\services\soma\ExpressService;
use App\services\soma\OrderService;
use App\services\soma\ScopeDiscountService;
use App\services\soma\PackageService;
use App\libraries\Support\Collection;



/**
 * Class Order
 * @author renshuai  <renshuai@mofly.cn>
 *
 *
 * @property Sales_order_model $salesOrderModel
 */
class Order extends MY_Front_Soma_Iapi
{

    /**
     * @var array
     */
    public $wft_pay_inter_ids = [
        'a479457264',//厦门海旅温德姆至尊酒店
        'a482210445',//厦门帝元维多利亚大酒店
        'a489326393',//都江堰紫坪铺滑翔伞飞行营地
        'a494820079',//成都群光君悦酒店
        'a496652649',//株洲万豪
        'a497580480',// 苏州吴宫泛太平洋酒店
        'a499046681',
        'a492763532',
        'a498545803',
        'a484533415',
        'a498095405',
    ];


    /**
     * @SWG\Get(
     *        path="/order/index",
     *        summary="订单列表",
     *        tags={"order"},
     *        @SWG\Parameter(
     *            description="第几页",
     *            in="query",
     *            name="page",
     *            required=true,
     *            type="integer"
     *        ),
     *        @SWG\Parameter(
     *            description="每页行数",
     *            in="query",
     *            name = "page_size",
     *            required=true,
     *            type="integer"
     *        ),
     *        @SWG\Parameter(
     *            description="菜单类型 1 全部 2 未使用 3 已完成",
     *            in="query",
     *            name = "type",
     *            required=true,
     *            type="integer"
     *        ),
     *         @SWG\Response(
     *              response=400,
     *              description="page or page_size not get",
     *        ),
     *         @SWG\Response(
     *              response=200,
     *              description="订单列表",
     *              @SWG\Schema(
     *                   type="object",
     *                   @SWG\Property(
     *                       property="products",
     *                       description="商品列表",
     *                       type = "array",
     *                       @SWG\Items(ref="#/definitions/SomaSalesOrder")
     *                   ),
     *                   @SWG\Property(
     *                        property="page_resource",
     *                        description="分页信息和页面链接",
     *                        type="object",
     *                        @SWG\Property(
     *                         property="page",
     *                         description="第几页",
     *                         type = "integer",
     *                        ),
     *                        @SWG\Property(
     *                         property="size",
     *                         description="每页多少行",
     *                         type = "integer",
     *                        ),
     *                        @SWG\Property(
     *                         property="count",
     *                         description="总条数",
     *                         type = "integer",
     *                        ),
     *                        @SWG\Property(
     *                             property="link",
     *                             description="页面链接",
     *                             type="object",
     *                             @SWG\Property(
     *                                  property="all",
     *                                  description="全部",
     *                                  type = "string",
     *                             ),
     *                             @SWG\Property(
     *                                  property="pending_use",
     *                                  description="待使用",
     *                                  type = "string",
     *                             ),
     *                             @SWG\Property(
     *                                  property="completed",
     *                                  description="已完成",
     *                                  type = "string",
     *                             ),
     *                             @SWG\Property(
     *                                  property="detail",
     *                                  description="订单详情，需要拼接order_id",
     *                                  type = "string",
     *                             ),
     *                              @SWG\Property(
     *                                  property="del_order",
     *                                  description="删除，需要拼接order_id",
     *                                  type = "string",
     *                             )
     *                        ),
     *                   ),
     *              ),
     *         ),
     *    )
     */

    public function get_index()
    {
        $openid = $this->openid;
        $input = $this->input;
        $pageSize = $input->get('page_size', null, 0);
        $page = $input->get('page', null, 0);
        $type = $input->get('type', null, 1);
        if (empty($openid) || empty($pageSize) || empty($page) || empty($type)) {
            show_error('page or page_size not get', 400);
        }
        $result = OrderService::getInstance()->getOrderList($openid, $type, [
            'limit' => $pageSize,
            'offset' => ($page - 1) * $pageSize,
            'page' => $page
        ]);
        $result_data = $result->getData();
        $data['products'] = isset($result_data['data']) ? $result_data['data'] : [];
        $data['page_resource'] = [
            'link' => [
                'all' => $this->link['order_link'],
                'pending_use' => $this->link['pending_use_order_link'],
                'completed' => $this->link['completed_order_link'],
                'gift' => $this->link['my_gift_link'],
                'detail' => $this->link['detail_link'],
                'del_order' => $this->link['delete_order_link'],
            ],
            'count' => isset($result_data['total']) ? $result_data['total'] : 0,
            'size' => $pageSize,
            'page' => $page,
        ];
        $this->json(FrontConst::OPER_STATUS_SUCCESS, 'msg', $data);
    }


    /**
     * 订单详情
     */
    public function get_detail()
    {
        $input = $this->input;
        $oid = $input->get('oid', null, null);
        if (empty($oid)) {
            show_error('not get order_id', 400);
        }

        $this->json(FrontConst::OPER_STATUS_SUCCESS, 'I am detail', []);
    }

    /**
     * 删除订单
     */
    public function get_delete_order()
    {
        $this->json(FrontConst::OPER_STATUS_SUCCESS, 'I am delete', []);
    }


    /**
     * @SWG\Post(
     *     path="/order/index",
     *     summary="下单",
     *     tags={"order"},
     *     @SWG\Parameter(
     *         in="formData",
     *         name="business",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="settlement",
     *         required=true,
     *         type="string",
     *         default="default"
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="hotel_id",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="qty",
     *         required=true,
     *         type="string",
     *         description="数量",
     *     ),
     *      @SWG\Parameter(
     *         in="formData",
     *         name="product_id",
     *         required=true,
     *         type="string",
     *         description="商品id"
     *     ),
     *      @SWG\Parameter(
     *         in="formData",
     *         name="name",
     *         required=true,
     *         type="string",
     *         description="联系人"
     *     ),
     *      @SWG\Parameter(
     *         in="formData",
     *         name="phone",
     *         required=true,
     *         type="string",
     *         description="电话"
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="inid",
     *         required=true,
     *         type="string",
     *         description=""
     *     ),
     *      @SWG\Parameter(
     *         in="formData",
     *         name="mcid",
     *         required=true,
     *         type="string",
     *         description=""
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="order_id",
     *                  description="订单id",
     *                  type = "string",
     *              )
     *         )
     *     )
     * )
     */
    public function post_index()
    {
        //todo  没有做参数验证
        $posts = $this->input->input_json();
        $posts = $posts->toArray();
        Log::debug('post', $posts);
//        if (ENVIRONMENT == 'dev') {
//            $posts = array(
//                'business' => 'package',
//                'settlement' => 'default',
//                'hotel_id' => 3,
//                'qty' => 1,
//                'product_id' => 11866,
//                'name' => '123',
//                'phone' => 323,
//                'inid' => 0,
//                'mcid' => 0,
//            );
//        }

        $post['saler'] = $this->session->tempdata('saler');
        $post['fans_saler'] = $this->session->tempdata('fans_saler');

        $createResult = OrderService::getInstance()->create($posts);
        if ($createResult->getStatus() === Result::STATUS_FAIL) {
            $message = $createResult->getMessage();
            $this->json(FrontConst::OPER_STATUS_FAIL_ALERT, $message);
            return;
        }

        $data = $createResult->getData();
        $salesOrderModel = $data['salesOrderModel'];
        $payChannel = $data['payChannel'];

        $this->load->model('soma/sales_order_model', 'salesOrderModel');
        $order = $this->salesOrderModel->load($data['salesOrderModel']->order_id);

        $grand_total = $order->m_get('grand_total');
        if ($grand_total < 0.005) {
            $pay_res['paid_type'] = empty($salesOrderModel->payment_extra) ? Sales_payment_model::PAY_TYPE_HD : $salesOrderModel->payment_extra;
            $payResult = $this->_inner_payment($order, $pay_res, false);
            $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $payResult);
            return;
        }

        // 储值支付
        if ($payChannel === 'balance_pay') {

            $bpay_passwd = $posts['bpay_passwd'];
            $pay_res = $this->balance_pay($bpay_passwd, $salesOrderModel->order_id);

            if ($pay_res && $pay_res['status'] == Soma_base::STATUS_TRUE) {
                $pay_res['paid_type'] = Sales_payment_model::PAY_TYPE_CZ;
                $payResult = $this->_inner_payment($order, $pay_res);
                $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $payResult);
                return;
            } else {
                $message = $pay_res['message'];
                $this->json(FrontConst::OPER_STATUS_FAIL_ALERT, $message);
                return;
            }

        } elseif ($payChannel === 'point_pay') {
            $pay_res = $this->point_pay($order);
            if ($pay_res && $pay_res['status'] == Soma_base::STATUS_TRUE) {
                $pay_res['paid_type'] = Sales_payment_model::PAY_TYPE_JF;
                $payResult = $this->_inner_payment($order, $pay_res);
                $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $payResult);
                return;
            } else {
                $message = $pay_res['message'];
                $this->json(FrontConst::OPER_STATUS_FAIL_ALERT, $message);
                return;
            }
        }

//        $result['status'] = Soma_base::STATUS_TRUE;
        $result = [
            'order_id' => $salesOrderModel->order_id
        ];
        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $result);
        return;
    }


    /**
     * 储值支付
     * @param $passwd
     * @param $order
     * @return array
     *
     */
    protected function balance_pay($passwd, $order)
    {
        $openid = $order->m_get('openid');
        $inter_id = $order->m_get('inter_id');
        $order_id = $order->m_get('order_id');

        try {
            $api = new Api_member($order->m_get('inter_id'));

            $result = $api->get_token();
            $api->set_token($result['data']);

            $balance_info = $api->balence_info($openid);
            $balance = isset($balance_info['data']) ? $balance_info['data'] : 0;
            if ($balance < $order->m_get('grand_total')) {
                // return json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '储值余额不足！'));
                return array('status' => Soma_base::STATUS_FALSE, 'message' => '储值余额不足！');
            }

            $scale = $api->balence_scale($openid);
            $pay_total = $api->balence_scale_convert($scale, $order->m_get('grand_total'), false);
            $uu_code = rand(1000, 9999);

            $use_result['err'] = 1; // 默认调用失败

            $yinju_inter_ids = array('a457946152', 'a471258436');
            if (in_array($inter_id, $yinju_inter_ids)) {
                $use_result = (array)$api->yinju_balence_use($pay_total, $openid, $passwd, $uu_code, $order_id);
            } else {
                $use_result = (array)$api->balence_use($pay_total, $openid, $passwd, $uu_code, $order_id);
            }

            if ($use_result['err'] == 0) {
                return array('status' => Soma_base::STATUS_TRUE, 'message' => '');
            }

        } catch (Exception $e) {
            Log::error('balance_pay error' . $e->getMessage(), []);
        }

        return array('status' => Soma_base::STATUS_FALSE, 'message' => '订单信息错误！');
    }

    /**
     * @param $order
     * @return array
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function point_pay($order)
    {
        try {
            $inter_id = $order->m_get('inter_id');
            $open_id = $order->m_get('openid');
            $order_id = $order->m_get('order_id');

            $api = new Api_member($inter_id);
            $result = $api->get_token();
            $api->set_token($result['data']);

            $point_info = null;
            $point_info = $api->point_info($open_id);
            $point = isset($point_info['data']) ? $point_info['data'] : 0;
            if ($point < $order->m_get('grand_total')) {
                return array('status' => Soma_base::STATUS_FALSE, 'message' => '积分余额不足！');
            }

            $uu_code = rand(1000, 9999);
            // 积分支付必须是整数，上取整
            $pay_total = ceil($order->m_get('grand_total'));
            $pay_res = $api->point_use($pay_total, $open_id, $uu_code, $order_id, $order);

            if ($pay_res['err'] == 0) {
                return array('status' => Soma_base::STATUS_TRUE, 'message' => '');
            }
        } catch (Exception $e) {
            Log::error('point_pay error' . $e->getMessage(), []);
        }

        return array('status' => Soma_base::STATUS_FALSE, 'message' => '订单信息错误！');
    }

    /**
     * @param Sales_order_model $order
     * @param $payment
     * @param bool $save_flag
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function _inner_payment($order, $payment, $save_flag = true)
    {

        $result['status'] = Soma_base::STATUS_FALSE;
        $result['message'] = '订单支付失败';
        $result['step'] = 'fail';

        $log_data = array();
        $log_data['paid_ip'] = $this->input->ip_address();
        $log_data['paid_type'] = $payment['paid_type'];
        $log_data['order_id'] = $order->m_get('order_id');
        $log_data['openid'] = $order->m_get('openid');
        $log_data['business'] = $order->m_get('business');
        $log_data['settlement'] = $order->m_get('settlement');
        $log_data['inter_id'] = $order->m_get('inter_id');
        $log_data['hotel_id'] = $order->m_get('hotel_id');
        $log_data['grand_total'] = $order->m_get('grand_total');
        $log_data['transaction_id'] = isset($payment['trans_id']) ? $payment['trans_id'] : '';

        if ($order->order_payment($log_data)) {

            $order->order_payment_post($log_data);
            if ($save_flag) {
                $this->load->model('soma/Sales_payment_model', 'pay_model');
                $this->pay_model->save_payment($log_data);
            }
            $url_params = array(
                'id' => $order->m_get('inter_id'),
                'order_id' => $order->m_get('order_id')
            );
            $url = Soma_const_url::inst()->get_payment_package_success($url_params);
            $result['success_url'] = $url;
            $result['status'] = Soma_base::STATUS_TRUE;
            $result['message'] = '订单支付成功';
            $result['data'] = array('orderId' => $order->m_get('order_id'));
            $result['step'] = 'success';

        } else {

            Log::error('inner_payment order_payment return false', $log_data);

            $result['status'] = Soma_base::STATUS_FALSE;
            $result['message'] = '订单支付失败';
            $result['step'] = 'fail';
        }

        return $result;
    }


    /**
     * @SWG\Get(
     *     tags={"order"},
     *     path="/order/pay",
     *     summary="准备下单数据",
     *     description="跳至下单页面，所要获取相关数据",
     *     operationId="get_pay",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="pid",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="业务类型",
     *         in="query",
     *         name="btype",
     *         required=true,
     *         type="string",
     *         default="package"
     *     ),
     *    @SWG\Parameter(
     *         description="规则id，该参数确定应该默认买多少份",
     *         in="query",
     *         name="rule_id",
     *         required=false,
     *         type="integer",
     *     ),
     *    @SWG\Parameter(
     *         description="商品多规格id",
     *         in="query",
     *         name="psp_id",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="token，秒杀活动要传此参数",
     *         in="query",
     *         name="token",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="customer_info",
     *                  description="联系人信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaCustomerContact")
     *              ),
     *              @SWG\Property(
     *                  property="coupons",
     *                  type="integer" ,
     *                  description="可用优惠券数量" ,
     *              ),
     *              @SWG\Property(
     *                  property="point",
     *                  type="integer" ,
     *                  description="可用积分" ,
     *              ),
     *              @SWG\Property(
     *                  property="count",
     *                  type="integer" ,
     *                  description="购买数量" ,
     *              ),
     *              @SWG\Property(
     *                  property="balance",
     *                  type="object" ,
     *                  description="用户信息信息。money：储值金额；url：储值充值中心链接；password：某些公众号储值类型商品必须输入密码，0：不需要，1：需要" ,
     *              ),
     *              @SWG\Property(
     *                  property="psp_setting",
     *                  type="array" ,
     *                  description="多规格商品款式设置" ,
     *                  @SWG\Items(ref="#/definitions/SomaProductSpecificationSetting")
     *              ),
     *              @SWG\Property(
     *                  property="scope_product_link",
     *                  type="array" ,
     *                  description="判断是否使用价格配置的价格，如果使用的话就不能使用优惠券了" ,
     *              ),
     *              @SWG\Property(
     *                  property="product",
     *                  description="购买商品信息",
     *                  type="array" ,
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="address",
     *                  description="邮寄地址。default: 默认，list：列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaCustomerAddress")
     *              ),
     *              @SWG\Property(
     *                  property="saler",
     *                  description="分销员信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/IwideHotelStaff")
     *              )
     *
     *         )
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
    public function get_pay()
    {

        $data = [];

        $productId = $this->input->get('pid');

        //todo 其他参数怎么不验证呢？ 备注：其他参数可传可不传
        if(empty($productId)) {
            show_error('Invalid pid supplied', 400);
        }

        $this->load->model('soma/Product_package_model', 'productPackageModel');
        $productDetail = $this->productPackageModel->get_product_package_detail_by_product_id($productId, $this->inter_id);

        //商品不存在
        if (!$productDetail) {
            show_404();
        }

        $productDetail = PackageService::getInstance()->composePackage([$productDetail], $this->inter_id, $this->openid)[0];

        //取出联系人和电话
        $data['customer_info'] = $this->productPackageModel->get_customer_contact(['openid' => $this->openid]);

        //读取购买人的可用券
        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id);

        //储值类型商品读取购买人的储值信息
        $data['balance'] = ['money' => 0, 'url' => null, 'password' => false];
        if ($productDetail['type'] == Product_package_model::PRODUCT_TYPE_BALANCE) {
            $result = $api->get_token();
            $result['data'] = isset($result['data']) ? $result['data'] : array();
            $api->set_token($result['data']);
            $balance = $api->balence_info($this->openid);
            $balance['data'] = isset($balance['data']) ? $balance['data'] : 0;
            $data['balance']['money'] = $balance['data'];
            $data['balance']['url'] = $api->balence_deposit_url($this->inter_id);
        }
        if(in_array($this->inter_id, array('a457946152', 'a471258436', 'a450089706'))) {
            $data['balance']['password'] = Soma_base::STATUS_TRUE;
        }

        //积分商品拉取用户积分信息
        $data['point'] = 0;
        if ($productDetail['type'] == Product_package_model::PRODUCT_TYPE_POINT) {
            $result = $api->get_token();
            $result['data'] = isset($result['data']) ? $result['data'] : array();
            $api->set_token($result['data']);
            $point = $api->point_info($this->openid);
            $data['point'] = isset($point['data']) ? (int)$point['data'] : 0;
        }

        $this->load->helper('soma/time_calculate');
        $this->load->model('soma/Sales_rule_model');
        $this->load->model('soma/Sales_order_discount_model');
        $this->load->model('soma/Sales_order_model');

        $salesRuleModel = $this->Sales_rule_model;

        //todo get_rule 中返回
        //根据rule_id规则ID参数确定应该默认买多少份
        $data['count'] = 1;
        $fix_rule = $salesRuleModel->find(array('rule_id' => $this->input->get('rule_id')));
        if ($fix_rule && $fix_rule['lease_cost'] && $productDetail['price_package']) {
            $fix_qty = $fix_rule['lease_cost'] / $productDetail['price_package'];
            if ($fix_qty < 1) {
                $fix_qty = 1;
            } else {
                if ($fix_qty > 1) {
                    $fix_qty = ceil($fix_qty);
                } else {
                    $fix_qty = intval($fix_qty);
                }
            }
            $data['count'] = $fix_qty > 200 ? 200 : $fix_qty;
        }

        //可用优惠券数量
        $result = $api->get_token();
        $result['data'] = isset($result['data']) ? $result['data'] : array();
        $api->set_token($result['data']);
        $result = $api->conpon_sign_list($this->openid);
        $result['data'] = isset($result['data']) ? $result['data'] : array();
        $data['coupons'] = count($result['data']);

        //加载产品规格信息
        $data['psp_setting'] = [];
        $psp_sid = $this->input->get('psp_sid', true);
        if ($psp_sid) {
            $data['psp_setting'] = PackageService::getInstance()->getSettingInfoByProductIdAndPspSid($this->inter_id, $productId, $psp_sid);
            if (!empty($data['psp_setting'])) {
                $productDetail['price_package'] = $data['psp_setting'][0]['specprice'];
            }
        }

        //判断是否使用价格配置的价格，如果使用的话就不能使用优惠券了
        $scope_product_link = ScopeDiscountService::getInstance()->useScopeDiscount($this->inter_id, $this->openid, $productDetail, $psp_sid);
        $data['scope_product_link'] = $scope_product_link;
        if (!empty($scope_product_link)) {
            $productDetail['price_package'] = $scope_product_link['price'];
        }

        //邮寄
        $defaultAddress = array();
        $userAddressList = array();
        if (isset($productDetail['can_mail']) && $productDetail['can_mail'] == Product_package_model::CAN_T) {
            $userAddressList = ExpressService::getInstance()->getUserAddressList($this->openid, $this->inter_id);
            if (!empty($userAddressList)) {
                $defaultAddress = $userAddressList[0];
            }
        }

        $data['product'] = $productDetail;
        $data['address'] = ['default' => $defaultAddress, 'list' => $userAddressList];

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }


    /**
     *
     * @SWG\Post(
     *     tags={"order"},
     *     path="/order/create",
     *     summary="下单",
     *     description="用户下单",
     *     operationId="get_create",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="商品id",
     *         in="query",
     *         name="product_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="结算类型",
     *         in="query",
     *         name="settlement",
     *         required=true,
     *         type="string",
     *         default="package"
     *     ),
     *    @SWG\Parameter(
     *         description="商品（特权券）使用方式。1：送自己，2：送朋友，-1： 无使用方式",
     *         in="query",
     *         name="u_type",
     *         required=true,
     *         type="integer",
     *         default="-1"
     *     ),
     *    @SWG\Parameter(
     *         description="购买商品数量。数组形式，例如：qty[11472]=2，即表示：qty[商品id]=数量",
     *         in="query",
     *         name="qty",
     *         required=true,
     *         type="string",
     *     ),
     *    @SWG\Parameter(
     *         description="优惠券批量使用。数组形式，例如：mcid[0]=2658915，即表示：qty[下标]=优惠券member_card_id",
     *         in="query",
     *         name="mcid",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="收货人电话",
     *         in="query",
     *         name="phone",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="收货人姓名",
     *         in="query",
     *         name="name",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="?????????",
     *         in="query",
     *         name="quote_type",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="?????????",
     *         in="query",
     *         name="quote",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="支付密码。由于已被bpay_passwd替代，暂未明确有何用处，可传空值过来",
     *         in="query",
     *         name="password",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="多规格商品设置信息。数组形式，例如：psp_setting[11472]=2，即表示：psp_setting[商品id]=多规格id（即：psp_sid）",
     *         in="query",
     *         name="psp_setting",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="活动id，秒杀活动必传",
     *         in="query",
     *         name="act_id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="活动实例化id，秒杀活动必传",
     *         in="query",
     *         name="inid",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="专属价id。该项判断是否使用价格配置的价格，如果使用的话就不能使用优惠券了",
     *         in="query",
     *         name="scope_product_link_id",
     *         required=true,
     *         type="string",
     *         default="0",
     *     ),
     *     @SWG\Parameter(
     *         description="拼团活动id，拼团活动必传",
     *         in="query",
     *         name="grid",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="??",
     *         in="query",
     *         name="type",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="储值密码，储值商品必传",
     *         in="query",
     *         name="bpay_passwd",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="salesOrderModel",
     *                  description="下单返回信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaSalesOrder")
     *              ),
     *              @SWG\Property(
     *                  property="payChannel",
     *                  type="string" ,
     *                  description="商品支付类型" ,
     *              )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid params supplied"
     *     )
     * )
     */
    public function post_create()
    {

        $params = $this->input->input_json();
        //$params = $this->input->get();

        if (empty($params)) {
            show_error('Invalid params supplied', 400);
        } else {
            if (!$params->has('product_id')) {
                show_error('Invalid product_id supplied', 400);
            }
            if (!$params->has('settlement')) {
                show_error('Invalid settlement supplied', 400);
            }
            if (!$params->has('u_type')) {
                show_error('Invalid u_type supplied', 400);
            }
            if (!$params->has('qty')) {
                show_error('Invalid qty supplied', 400);
            }
            if (!$params->has('mcid')) {
                show_error('Invalid mcid supplied', 400);
            }
            if (!$params->has('phone')) {
                show_error('Invalid phone supplied', 400);
            }
            if (!$params->has('name')) {
                show_error('Invalid name supplied', 400);
            }
            if (!$params->has('quote_type')) {
                show_error('Invalid quote_type supplied', 400);
            }
            if (!$params->has('quote')) {
                show_error('Invalid quote supplied', 400);
            }
            if (!$params->has('password')) {
                show_error('Invalid password supplied', 400);
            }
            if (!$params->has('psp_setting')) {
                show_error('Invalid psp_setting supplied', 400);
            }
            if (!$params->has('inid')) {
                show_error('Invalid inid supplied', 400);
            }
            if (!$params->has('scope_product_link_id')) {
                show_error('Invalid scope_product_link_id supplied', 400);
            }
            if (!$params->has('act_id')) {
                show_error('Invalid act_id supplied', 400);
            }
            if (!$params->has('grid')) {
                show_error('Invalid grid supplied', 400);
            }
            if (!$params->has('type')) {
                show_error('Invalid type supplied', 400);
            }
        }

        $params['saler'] = $this->session->tempdata('saler');
        $params['fans_saler'] = $this->session->tempdata('fans_saler');

        $result = OrderService::getInstance()->create($params);

        $this->json($result->getStatus(), $result->getMessage(), $result->getData());
    }
}