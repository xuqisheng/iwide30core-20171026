<?php
use App\services\Result;
use App\libraries\Iapi\BaseConst;
use App\services\soma\ExpressService;

/**
 * Class Express
 * @author renshuai  <renshuai@mofly.cn>
 *
 * @property Customer_address_model $customerAddressModel
 */
class Express extends MY_Front_Soma_Iapi
{


    /**
     * @SWG\Get(
     *     tags={"express"},
     *     path="/express/tree",
     *     summary="获取区域列表",
     *     description="获取区域列表",
     *     operationId="get_tree",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="data",
     *                  description="区域列表",
     *                  type="object",
     *                  @SWG\Items(ref="#/definitions/SomaCustomerAddress")
     *              )
     *         )
     *     )
     * )
     */
    public function get_tree()
    {
        $result = ExpressService::getInstance()->regionTree();
        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $result->getData());
    }


    /**
     * @SWG\Post(
     *     tags={"express"},
     *     path="/express/save",
     *     summary="保存（增加、修改）地址数据",
     *     description="保存（增加、修改）地址数据",
     *     operationId="get_save",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="地址id。值为空：增加；值不为空且找到该条记录：修改",
     *         in="query",
     *         name="address_id",
     *         required=false,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="省id",
     *         in="query",
     *         name="province_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="市id",
     *         in="query",
     *         name="city_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="区id",
     *         in="query",
     *         name="region_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="详细地址",
     *         in="query",
     *         name="address",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="联系人",
     *         in="query",
     *         name="contact",
     *         required=true,
     *         type="string",
     *     ),
     *    @SWG\Parameter(
     *         description="phone",
     *         in="query",
     *         name="contact",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *                  @SWG\Property(
     *                      property="address_id",
     *                      description="保存地址id",
     *                      type="integer",
     *                  )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="缺少必要参数或保存失败"
     *     )
     * )
     */
    public function post_save(){

        $params = $this->input->input_json();

        if(!$params->has('province')) {
           show_error('Invalid province supplied', 400);
        }
        if(!$params->has('city')) {
            show_error('Invalid city supplied', 400);
        }
        if(!$params->has('address')) {
            show_error('Invalid address supplied', 400);
        }
        if(!$params->has('phone')) {
            show_error('Invalid phone supplied', 400);
        }
        if(!$params->has('contact')) {
            show_error('Invalid contact supplied', 400);
        }

        $item = [
            'openid' => $this->openid,
            'inter_id' => $this->inter_id,
            'address_id' => $params['address_id'],
            'province' => $params['province'],
            'city' => $params['city'],
            'region' => $params['region'],
            'address' => $params['address'],
            'phone' => $params['phone'],
            'contact' => $params['contact'],
        ];

        $returnData = ExpressService::getInstance()->saveRegion($item);
        if($returnData){
            $this->json(BaseConst::OPER_STATUS_SUCCESS, '保存成功', ['address_id' => $returnData]);
            return;
        }
        //todo
    }

    /**
     * @SWG\Get(
     *     tags={"express"},
     *     path="/express/index",
     *     summary="邮寄页面",
     *     description="邮寄页面",
     *     operationId="get_index",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="订单id",
     *         in="query",
     *         name="oid",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="礼物id",
     *         in="query",
     *         name = "gid",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="product",
     *                  description="商品详情",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="count",
     *                  description="邮寄数量",
     *                  type = "integer",
     *               ),
     *              @SWG\Property(
     *                  property="address",
     *                  description="邮寄地址",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="sum_fee",
     *                  description="邮寄总费用",
     *                  type = "string",
     *              ),
     *             @SWG\Property(
     *                  property="per_fee",
     *                  description="每件邮寄",
     *                  type = "string",
     *              ),
     *         )
     *     )
     * )
     */
    public function get_index()
    {
        //分两种情况，一：从订单进来的，参数是order_id。二：从赠送订单进来的，参数是gift_id
        $orderId = $this->input->get('oid');
        $giftId = $this->input->get('gid');
        $business = 'package';
        $interId = $this->inter_id;
        $openid = $this->openid;

        if (!$orderId && !$giftId) {
            $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '商品id或礼品id不能为空');
            return;
        }

        if ($orderId) {
            //自己的订单
            $this->load->model('soma/Sales_order_model', 'SalesOrderModel');
            $SalesOrderModel = $this->SalesOrderModel;

            $SalesOrderModel->business = $business;
            $SalesOrderModel = $SalesOrderModel->load($orderId);
            if (!$SalesOrderModel) {
                $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '订单不存在');
                return;
            }

            //检查能否邮寄
            if (!$SalesOrderModel->can_mail_order()) {
                $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '不能邮寄');
                return;
            }
            $detail = $SalesOrderModel->get_order_asset($business, $interId); //资产订单

            //筛选属于自己的资产订单
            $detail['items'] = $SalesOrderModel->filter_items_by_openid($detail['items'], $this->openid);
            if (isset($detail['openid']) && $detail['openid'] != $this->openid) {
                //不是自己的单
                $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '不是本人订单');
                return;
            }
        } elseif ($giftId) {
            //接受到赠送的礼物
            $this->load->model('soma/Gift_order_model', 'GiftOrderModel');
            $GiftOrderModel = $this->GiftOrderModel;

            $this->load->model('soma/Asset_customer_model', 'assetCustomerModel');

            $filter = array('gift_id' => $giftId);
            $items = $this->assetCustomerModel->get_gift_recevied_item($filter, $business, $interId);

            //筛选属于自己的资产订单
            $items = $this->assetCustomerModel->filter_items_by_openid($items, $this->openid);
            $model = $GiftOrderModel->load($giftId);

            //取出群发收到的列表
            $receive_list = $model->get_receiver_list_byOpenId($interId, $this->openid);
            $giftIds = $model->array_to_hash($receive_list, 'gift_id');

            if (!$model || ($model->m_get('openid_received') != $this->openid && $model->m_get('is_p2p') == Soma_base::STATUS_TRUE)
                || ($model->m_get('is_p2p') == Soma_base::STATUS_FALSE && !in_array($giftId, $giftIds))
            ) {
                $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '礼物不存在');
                return;
            }

            $detail = $model->m_data();
            $detail['items'] = $items;
        }

        //筛选掉数量为空的数据
        $filter_data = array();
        foreach ($detail['items'] as $k => $v) {
            if ($v['qty'] > 0) {
                $filter_data[] = $v;
            }
        }
        $detail['items'] = $filter_data;
        if (isset($detail['items'][0]['can_mail']) && $detail['items'][0]['can_mail'] == Soma_base::STATUS_FALSE) {
            $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '不可邮寄');
            return;
        }

        $page_data = array();
        $items = $detail['items'];
        $count = 0;
        foreach ($items as $k => $v) {
            $count += $v['qty'];
        }

        if ($count == 0) {
            //没有可邮寄的商品
            $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '没有可邮寄的商品');
            return;
        }

        //获取消费地址
        $this->load->model('soma/Customer_address_model', 'CustomerAddressModel');
        $CustomerAddressModel = $this->CustomerAddressModel;

        $filter = array();
        $filter['openid'] = $openid;
        $filter['inter_id'] = $interId;

        $limit = 1;//取出一条地址信息
        $address = $CustomerAddressModel->get_addresses($openid, $filter, $limit);
        $address_detail = '';
        if (!empty($address)) {
            $region_list = ExpressService::getInstance()->getRegion($this->openid, $this->inter_id, $address[0]['address_id']);
            if (!empty($region_list)) {
                $address_detail = $region_list[0].$region_list[1].$region_list[2].$address[0]['address'];
            }
        }

        // 取出商品信息
        $this->load->model('soma/product_package_model', 'p_model');
        $select = 'product_id,face_img,name,shipping_fee_unit,price_package';
        $page_data['product'] = $this->p_model->get_product_package_by_ids($items[0]['product_id'], $this->inter_id, $select)[0];
        if (isset($page_data['product']['shipping_product_id'])
            && $spi = $page_data['product']['shipping_product_id']
        ) {
            $page_data['shipping_product'] = $this->p_model->get_product_package_by_ids($spi, $this->inter_id, $select)[0];
        }
        $res = array(
            'product' => $page_data['product'],
            'count' => $count,
            'address' => $address_detail,

        );
        if (isset($page_data['shipping_product'])) {
            $res['sum_fee'] = ceil($count/$res['product']['shipping_fee_unit'])*$page_data['shipping_product']['price_package'];
            $res['pre_fee'] = $page_data['shipping_product']['price_package'];
        } else {
            $res['sum_fee'] = '0';
            $res['pre_fee'] = '0';
        }

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $res);
    }

    /**
     * @SWG\Get(
     *     tags={"express"},
     *     path="/express/detail",
     *     summary="邮寄详情",
     *     description="邮寄详情",
     *     operationId="get_detail",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="邮寄id",
     *         in="query",
     *         name="spid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="product",
     *                  description="商品详情",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage")
     *              ),
     *              @SWG\Property(
     *                  property="contact",
     *                  description="联系人信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaUserAddress")
     *               ),
     *              @SWG\Property(
     *                  property="shipping_track",
     *                  description="物流信息",
     *                  type = "array",
     *                  @SWG\Items(
     *                      @SWG\Property(
     *                          property="datetime",
     *                          description="时间",
     *                          type = "string"
     *                      ),
     *                      @SWG\Property(
     *                          property="remark",
     *                          description="物流信息",
     *                          type = "string"
     *                      ),
     *                      @SWG\Property(
     *                          property="zone",
     *                          description="",
     *                          type = "string"
     *                      )
     *                  )
     *              ),
     *             @SWG\Property(
     *                  property="status",
     *                  description="邮寄状态 1:邮寄申请 2：邮寄发货 4：异常挂起 5：已签收 6：待付运费 7：下单失败",
     *                  type = "string",
     *              ),
     *         )
     *     )
     * )
     */
    public function get_detail()
    {
        $page_data = array();

        $business = 'package';
        $business = $business ? $business : 'package';
        $shipping_id = $this->input->get('spid');

        if( !$shipping_id ){
            show_error('Invalid spid supplied', 400);
        }

        $filter = array(
            'openid' => $this->openid,
            'inter_id' => $this->inter_id,
            'shipping_id' => $shipping_id,
        );
        $sort = 'create_time DESC';

        $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
        $this->load->library('Soma/Api_express');
        $Api_express = $this->api_express;

        $ConsumerShippingModel = $this->ConsumerShippingModel;
        $orders = $ConsumerShippingModel->get_shipping_list($business, $this->inter_id,$filter,$sort);
        if (empty($orders)) {
            show_404();
        }

        $orders = $orders[0];
        $this->load->model('soma/Consumer_item_'.$business.'_model','ConsumerItemModel');
        $ConsumerItemModel = $this->ConsumerItemModel;
        $ids = $orders['consumer_id'];
        $items = $ConsumerItemModel->get_order_items_byIds( $ids, $business, $this->inter_id );
        $orders['items'] = $items;

        $distributor = isset( $orders['distributor'] ) ? $orders['distributor'] :'';
        $expressComCode = $distributor;

        if( isset($orders['status']) && $orders['status'] ==  $ConsumerShippingModel::STATUS_SHIPPED || ($orders['status'] ==  $ConsumerShippingModel::STATUS_FINISHED) ){
            //邮寄状态，从redis读取快递信息
            $expressInfo = $Api_express->get_express_from_redis($expressComCode ,$orders['tracking_no'],$this->inter_id);
            if(isset($expressInfo['list']) && is_array($expressInfo['list']) && !empty($expressInfo['list']) ){
                krsort($expressInfo['list']);
                $page_data['shippingTrack'] = $expressInfo['list'];
            }

            //根据快递100的状态去更新数据库状态
            if($expressInfo['status'] == 1 && $orders['status'] ==  $ConsumerShippingModel::STATUS_SHIPPED){
                $ConsumerShippingModel->edit_shipping_info('',$this->inter_id,array('shipping_id'=>$orders['shipping_id']),array('status'=>$ConsumerShippingModel::STATUS_FINISHED));
            }
        }

        $page_data['orders'] = $orders;

        $res = [
            'product' => [
                'name' => $page_data['orders']['name'],
                'qty' => $page_data['orders']['qty'],
                'price_package' => $items[0]['price_package'],
            ],
            'contact' => [
                'contact' => $page_data['orders']['contacts'],
                'phone' => $page_data['orders']['phone'],
                'address' => $page_data['orders']['address']
            ],
            'shipping_track' => $page_data['shippingTrack'],
            'status' => $page_data['orders']['status']
        ];

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $res);

    }

}