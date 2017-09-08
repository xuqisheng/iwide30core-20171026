<?php
/**
 * User: daikanwu
 * Date: 2017-7-28
 * Time: 10:17
 */
use App\libraries\Iapi\BaseConst;

/**
 * Class Refund
 *
 *
 */
class Refund extends MY_Front_Soma_Iapi
{
    /**
     * @SWG\Get(
     *     tags={"refund"},
     *     path="/refund/index",
     *     summary="退款页面",
     *     description="退款",
     *     operationId="get_index",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="订单id",
     *         in="query",
     *         name="oid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="order_detail",
     *                  description="订单信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaSalesOrder")
     *              ),
     *              @SWG\Property(
     *                  property="tip",
     *                  description="小提示",
     *                  type="string",
     *              ),
     *              @SWG\Property(
     *                  property="refund_cause",
     *                  description="退款原因",
     *                  type="array",
     *              ),
     *              @SWG\Property(
     *                  property="rtype",
     *                  description="退款类型 1:微信 2储值 3积分",
     *                  type="string",
     *              ),
     *         )
     *    ),
     *    @SWG\Response(
     *         response="400",
     *         description="参数错误"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="订单不存在"
     *     )
     *)
     */
    public function get_index()
    {
        //获取订单号
        $order_id = $this->input->get('oid');
        if (!$order_id) {
            show_error('', 400);
        }
        $business = 'package'; //分类
        $this->load->model('soma/Sales_order_model', 'sales_order_model');
        $sales_order_model = $this->sales_order_model;

        $sales_order_model->business = $business;
        $order_detail = $sales_order_model->load($order_id)->get_order_detail($business, $this->inter_id);

        //订单不存在
        if (!$order_detail) {
            show_404();
        }

        //不是自己的
        if ($this->openid !== $order_detail['openid']) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '非自己订单');
            return;
        }

        //判断是否可以退款
        $can_refund = $sales_order_model->can_refund_order();
        if (!$can_refund) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '不可退款');
            return;
        }

        if ($order_detail['can_refund'] == $sales_order_model::CAN_REFUND_STATUS_SEVEN) {
            //退款不能超过支付后7天
            $paymentTime = $order_detail['payment_time'];
            if ($paymentTime) {
                $overTime = strtotime($paymentTime) + 7 * 24 * 60 * 60;
                $nowTime = time();
                if ($overTime < $nowTime) {
                    $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '退款不能超过支付后7天');
                    return;
                }
            }
        }

        //是否过期
        $time = time();
        $expireTime = isset($order_detail['items'][0]['expiration_date']) ? strtotime($order_detail['items'][0]['expiration_date']) : NULL;
        if ($expireTime && $expireTime < $time) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '已过期不能退款');
            return;
        }

        $data = array();
        $data['order_detail'] = [
            'order_id' => $order_detail['order_id'],
            'create_time' => date('Y/m/d H:i:s', strtotime($order_detail['create_time'])),
            'real_grand_total' => $order_detail['real_grand_total'],
            'item_name' => $order_detail['item_name'],
            'hotel_name' => $order_detail['items'][0]['hotel_name']
        ];
        $data['tip'] = '退款状态下,您的订单将被暂时锁定';

        //退款原因
        $cause = array(
            '预约不上',
            '网上／朋友评价不好',
            '买多了',
            '计划有变，没时间消费',
            '联系不上商家',
            '找到了便宜的渠道',
        );
        $data['refund_cause'] = $cause;

        //获取支付类型
        $this->load->model('soma/Sales_payment_model', 'paymentModel');
        $paymentModel = $this->paymentModel;
        $paidTypes = $paymentModel->get_paid_type_byOrderIds(array($order_id), $this->inter_id, 'paid_type');
        if (!$paidTypes) {
            //查找不到信息，在payment查找不到信息，就是说可能不是微信支付或者储值支付
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '支付信息不存在');
            return;
        }
        $pay_type = isset($paidTypes[0]['paid_type']) ? $paidTypes[0]['paid_type'] : '';

        $this->load->model('soma/Sales_refund_model', 'refundModel');
        $refundModel = $this->refundModel;
        $param = [];
        if ($pay_type == $paymentModel::PAY_TYPE_WX) {
            $param['rtype'] = $refundModel::REFUND_TYPE_WX;
        } elseif ($pay_type == $paymentModel::PAY_TYPE_CZ) {
            $param['rtype'] = $refundModel::REFUND_TYPE_CZ;
        } elseif ($pay_type == $paymentModel::PAY_TYPE_JF) {
            $param['rtype'] = $refundModel::REFUND_TYPE_JF;
        }

        //退款类型
        $data['rtype'] = isset($param['rtype'])?$param['rtype']:'';

        $page_resource = [
            'link' => [
                'refund_detail' => $this->link['refund_detail'].'&oid='.$order_id,
            ]
        ];

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * 提交处理退款
     * @SWG\POST(
     *     tags={"refund"},
     *     path="/refund/apply",
     *     description="处理退款",
     *     operationId="post_apply",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="订单id",
     *         in="formData",
     *         name="oid",
     *         required=true,
     *         type="integer"
     *     ),
     *    @SWG\Parameter(
     *         description="退款原因, 用分号隔开 比如【预约不上;买多了】",
     *         in="formData",
     *         name="cause",
     *         required=true,
     *         type="string"
     *     ),
     *    @SWG\Parameter(
     *         description="退款类型，1:微信 2储值 3积分",
     *         in="formData",
     *         name="rtype",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="data",
     *                  description="",
     *                  type = "string",
     *              )
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="参数错误"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="订单不存在"
     *      )
     * )
     */
    public function post_apply()
    {
        $request_param = $this->input->input_json();
        if (empty($request_param) || !$request_param->has('oid')) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '订单号不能为空');
            return;
        }
        if (empty($request_param) || !$request_param->has('cause')) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '退款原因不能为空');
            return;
        }

        $order_id = $request_param->get('oid');
        $refund_type = $request_param->get('rtype');
        $cause = $request_param->get('cause');
        $business = 'package';

        //加载订单model
        $this->load->model('soma/Sales_order_model', 'sales_order_model');
        $sales_order_model = $this->sales_order_model;
        $sales_order_model->business = $business;

        //获取详情
        $sales_order_model = $sales_order_model->load($order_id);

        $order_detail = $sales_order_model->get_order_detail($business, $this->inter_id);
        if (!$order_detail) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '没有找到该订单');
        }

        //不是自己的订单，返回到订单列表
        if ($this->openid !== $order_detail['openid']) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '非自己订单');
            return;
        }

        //判断是否已经提交过退款申请或已经退款
        $can_refund = $sales_order_model->can_refund_order();
        if (!$can_refund) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '订单不可退款或已经退款');
            return;
        }

        $save = array();
        $save['cause'] = $cause;
        $save['refund_type'] = $refund_type;

        //加载退款model
        $this->load->model('soma/Sales_refund_model');
        $sales_refund_model = $this->Sales_refund_model;

        //设置退款参数
        $refund = array('refund_status' => $sales_order_model::REFUND_ALL); //主单退款状态全部退
        $sales_order_model->refund = $refund;

        $refund_item = array('is_refund' => $sales_order_model::STATUS_ITEM_REFUNDING);//细单退款状态已申请
        $sales_order_model->refund_item = $refund_item;
        $sales_refund_model->order = $sales_order_model;

        $sales_refund_model->product = $order_detail['items'];
        $sales_refund_model->business = $business;
        $sales_refund_model->save = $save;

        //如果是对接核销设备的，需要在生成退款单前检查
        $orderItems = isset($order_detail['items']) ? $order_detail['items'] : '';
        if (!$orderItems) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '订单商品不存在');
            return;
        }

        $orderItem = current($orderItems);
        $this->load->model('soma/Product_package_model', 'somaProductPackageModel');
        $somaProductPackageModel = $this->somaProductPackageModel;

        if (isset($orderItem['conn_devices']) && $orderItem['conn_devices'] != $somaProductPackageModel::DEVICE_NO_CONN) {
            //对接了设备
            switch ($orderItem['conn_devices']) {
                case $somaProductPackageModel::DEVICE_ZHIYOUBAO:
                    //对接智游宝，放到定时任务里面
                    $this->load->library('Soma/Api_zhiyoubao');
                    $api = new Api_zhiyoubao($orderItem['inter_id']);
                    $refundOrderResult = $api->refund_order($orderItem['order_id'], $orderItem['qty']);
                    if ($refundOrderResult) {
                        $save['retreat_batch_no'] = $refundOrderResult;
                        $sales_refund_model->save = $save;
                    } else {
                        $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '提交失败');
                        return;
                    }
                    break;
                default:
                    break;
            }
        }

        // 退款处理，把分账记录取消
        $this->soma_db_conn->trans_start();
        $result = $sales_refund_model->order_save($business, $this->inter_id);
        $billing_service = \App\services\soma\SeparateBillingService::getInstance();
        $billing_result = $billing_service->updateOrderSeparateBillingInfo($order_id);

        $page_resource = [
            'link' => [
                'refund_detail' => $this->link['refund_detail'].'&oid='.$order_id
            ]
        ];

        if ($result && $billing_result) {
            $this->soma_db_conn->trans_complete();
            //显示申请退款成功后的页面
            $this->json(BaseConst::OPER_STATUS_SUCCESS, '提交成功', ['order_id' => $order_id, 'page_resource' => $page_resource]);
        } else {
            $this->soma_db_conn->trans_rollback();
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '提交失败');
        }
    }


    /**
     * @SWG\Get(
     *     tags={"refund"},
     *     path="/refund/detail",
     *     summary="退款详情",
     *     description="退款详情",
     *     operationId="get_detail",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="订单id",
     *         in="query",
     *         name="oid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="order",
     *                  type = "object",
     *                  @SWG\Property(
     *                      property="order_id",
     *                      description="订单id",
     *                      type = "string",
     *                  ),
     *                  @SWG\Property(
     *                      property="real_total",
     *                      description="订单实付",
     *                      type = "string",
     *                  ),
     *                  @SWG\Property(
     *                      property="status",
     *                      description="状态",
     *                      type="integer 1:已申请 2：已审核 3：已退款 4：取消 5：挂起 6：微信退款中",
     *                  ),
     *                  @SWG\Property(
     *                      property="refund_type",
     *                      description="退款类型",
     *                      type="string 1:微信支付退款 2：储值支付退款 3：积分支付退款",
     *                  )
     *              )
     *         )
     *    ),
     *    @SWG\Response(
     *         response="400",
     *         description="参数错误"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="退款不存在"
     *     )
     *)
     */
    public function get_detail()
    {
        $order_id = $this->input->get('oid');
        $business = 'package';

        //获取订单号
        if (!$order_id) {
            show_error('oid不能为空', 400);
        }

        $this->load->model('soma/sales_refund_model');
        $sales_refund_model = $this->sales_refund_model;

        //查找退款主单信息
        $refund_info = $sales_refund_model->get_refund_order_detail_byOrderId($order_id, $this->inter_id);
        if (empty($refund_info)) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '没有退款主单');
        }

        //校验是不是自己单
        if ($refund_info['openid'] != $this->openid) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '非自己订单');
            return;
        }

        //订单信息
        $this->load->model('soma/Sales_order_model', 'sales_order_model');
        $order = $this->sales_order_model->load($order_id);
        if (!$order) {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, '订单不存在');
            return;
        }

        $data = array();
        $data['order_id'] = $refund_info['order_id'];
        $data['total'] = $order->m_get('real_grand_total');
        $data['status'] = $refund_info['status'];
        $data['refund_type'] = $refund_info['refund_type'];

        //审核状态
        $pending = $sales_refund_model::STATUS_PENDING;//已审核
        $success = $sales_refund_model::STATUS_REFUND;//已退款
        $processing = $sales_refund_model::STATUS_PROCESSING;//微信退款中

        if ($refund_info['refund_type'] == $sales_refund_model::REFUND_TYPE_WX) {

            if ($refund_info['status'] == $pending && $refund_info['status'] != $success) {
                //已审核但未成功的状态需到微信那边获取退款状态
                $order_id = $refund_info['order_id'];

                //微信退款
                $result = $sales_refund_model->wx_refund_check($order_id, $business, $this->inter_id);
                $result_status = $result['refund_status_0'];//退款状态

                if ($result_status == 'PROCESSING') {
                    //退款中
                    $data['status'] = $processing;
                } elseif ($result_status == 'SUCCESS') {
                    //退款成功
                    $data['status'] = $success;
                }
            }
        }

        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data);
    }
}