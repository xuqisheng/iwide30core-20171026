<?php
use App\libraries\Iapi\FrontConst;
use App\services\soma\WxService;

/**
 * Class Index
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Index extends MY_Front_Soma_Iapi
{

    /**
     * @SWG\Get(
     *     path="/index/sign_update_code",
     *     summary="获取微信配置信息",
     *     tags={"package"},
     *     @SWG\Parameter(
     *         description="当前地址url",
     *         in="query",
     *         name="url",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="package",
     *         @SWG\Schema(
     *             ref="#/definitions/SomaSignCode",
     *             additionalProperties={
     *                  "name":"data",
     *                  "type":"object",
     *              }
     *         ),
     *     )
     * )
     */
    public function post_sign_update_code()
    {
        $requestParam = $this->input->input_json();
        $url = $requestParam->get('url');
        if (empty($url)) {
            $this->json(FrontConst::OPER_STATUS_FAIL_TOAST, '参数为空', array());

            return;
        }
        $returnData['status'] = Soma_base::STATUS_TRUE;
        $returnData = WxService::getInstance()->getSignUpdateCode($this->inter_id, $url);
        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $returnData);
    }


}