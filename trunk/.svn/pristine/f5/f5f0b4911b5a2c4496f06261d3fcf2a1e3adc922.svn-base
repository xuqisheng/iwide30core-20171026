<?php
use App\libraries\Iapi\FrontConst;
use App\services\soma\ScopeDiscountService;
use App\libraries\Iapi\BaseConst;
use App\services\soma\KillsecService;
use App\services\soma\WxService;
use App\services\soma\PackageService;
use App\services\Result;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 秒杀相关接口
 * Class Killsec
 * @author daikanwu <daikanwu@jperation.com>
 *
 */
class Killsec extends MY_Front_Soma_Iapi
{

    /**
     * @SWG\POST(
     *     tags={"killsec"},
     *     path="/killsec/notice",
     *     description="秒杀设置提醒",
     *     operationId="post_notice",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="秒杀id",
     *         in="formData",
     *         name="act_id",
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
     *                  description="返回数据 非空的话表示公众号二维码地址",
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
     *         description="秒杀不存在"
     *      )
     * )
     */
    public function post_notice()
    {
        //TODO 堪武  最好逻辑写到一个service里？
        $request_param = $this->input->input_json();
        if (empty($request_param) || !$request_param->has('act_id')) {
            show_error('', 400);
        }

        $actId = $request_param->get('act_id');
        $return = array('data' => array(), 'message' => '');

        $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
        $this->load->model('soma/Activity_killsec_notice_model', 'activityKillsecNoticeModel');
        $activity = $this->activityKillsecModel->get_aviliable_activity(array('act_id' => $actId));
        if (empty($activity)) {
            show_404();
        }

        $activity = $activity[0];
        //校验订阅时间
        if ($activity['killsec_time'] < date('Y-m-d H:i:s', strtotime('+10 minute'))) {
            $this->json(FrontConst::OPER_STATUS_FAIL_TOAST, '已超过订阅时间');
            return;
        }

        $data = array('act_id' => $actId, 'openid' => $this->openid, 'inter_id' => $activity['inter_id'], 'product_id' => $activity['product_id'], 'product_name' => $activity['product_name'], 'killsec_price' => $activity['killsec_price'], 'killsec_time' => $activity['killsec_time'],);

        $result = $this->activityKillsecNoticeModel->add_notice($activity['inter_id'], $data);
        if (!$result) {
            $this->json(FrontConst::OPER_STATUS_FAIL_TOAST, '已订阅，请耐心等待活动开始', $return['data']);
            return;
        }

        $this->load->model('wx/Fans_model', 'fansModel');
        $subscribeStatus = $this->fansModel->subscribeStatus($this->inter_id, $this->openid);

        //没有关注的话返回二维码 关注了公众号的弹窗提示【设置成功】
        if (!$subscribeStatus) {
            $return['message'] = '您已订阅成功，我们将在活动开始前10分钟内通知您，为保证能收到提醒信息，请扫码关注公众号';
            $qrcodeResult = WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE);
            if ($qrcodeResult->getStatus() === Result::STATUS_OK && $activity['is_subscribe'] == Activity_killsec_model::STATUS_TRUE) {
                $return['data'] = $qrcodeResult->getData();
            }
        } else {
            $return['data'] = '';
            $return['message'] = '订阅成功';
        }

        $res = array('data' => $return['data']);

        $this->json(FrontConst::OPER_STATUS_SUCCESS, $return['message'], $res);
    }


    /**
     * @SWG\Get(
     *     tags={"killsec"},
     *     path="/killsec/stock",
     *     summary="获取秒杀商品库存",
     *     description="获取秒杀商品库存",
     *     operationId="get_stock",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="秒杀活动id",
     *         in="query",
     *         name="act_id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                 property="percent",
     *                 type="integer" ,
     *                 description="stock/total，表示已抢数量比例" ,
     *              ),
     *              @SWG\Property(
     *                 property="status",
     *                 type="integer" ,
     *                 description="状态值。数据查询正常：1，数据查询异常：2" ,
     *              ),
     *              @SWG\Property(
     *                 property="stock",
     *                 type="string" ,
     *                 description="已抢数量" ,
     *              ),
     *              @SWG\Property(
     *                 property="total",
     *                 type="string" ,
     *                 description="总库存" ,
     *              ),
     *         )
     *     ),
     *    @SWG\Response(
     *         response="400",
     *         description="Invalid act_id supplied"
     *    )
     * )
     */
    public function get_stock()
    {
        $actId = $this->input->get('act_id');
        if(empty($actId)){
            show_error('Invalid act_id supplied', 400);
        }

        $data = KillsecService::getInstance()->getStock($actId);
        $this->json(BaseConst::OPER_STATUS_SUCCESS, '', $data->getData());
    }


    /**
     * @SWG\Get(
     *     tags={"killsec"},
     *     path="/killsec/rob",
     *     summary="获取秒杀资格",
     *     description="开始秒杀后，用户获取秒杀资格，根据返回参数判断是否秒杀成功，若成功，则跳至下单页面",
     *     operationId="get_rob",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="秒杀活动id",
     *         in="query",
     *         name="act_id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="秒杀实例id。商品详情接口对应killsec.instance.id",
     *         in="query",
     *         name="inid",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="web_data",
     *                  type="object",
     *                  description="数据",
     *                  @SWG\Property(
     *                        property="instance_id",
     *                        type="string",
     *                        description="秒杀实例id" ,
     *                  ),
     *                  @SWG\Property(
     *                        property="token",
     *                        type="integer" ,
     *                        description="跳至下单页面的时候要带上此参数" ,
     *                  ),
     *                 @SWG\Property(
     *                        property="status",
     *                        type="integer" ,
     *                        description="状态。1：成功，2：失败" ,
     *                  )
     *              )
     *         )
     *     ),
     *    @SWG\Response(
     *         response="400",
     *         description="Invalid inid supplied"
     *    )
     * )
     */
    public function get_rob()
    {

        $instanceID = $this->input->get('inid');
        if(empty($instanceID)){
            show_error('Invalid inid supplied', 400);
        }

        $result = KillsecService::getInstance()->getOpporunity($this->inter_id, $instanceID, $this->openid);

        $this->json(BaseConst::OPER_STATUS_SUCCESS, $result->getMessage(), $result->getData());
    }
}