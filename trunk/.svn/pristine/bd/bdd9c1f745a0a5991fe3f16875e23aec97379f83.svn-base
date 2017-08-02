<?php
use App\libraries\Iapi\BaseConst;
use App\services\soma\ScopeDiscountService;
use App\services\soma\PaginateService;
use App\libraries\Support\Log;
use App\libraries\Iapi\FrontConst;
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
    public function post_sign_update_code(){
        $requestParam = $this->input->input_json();
        $url = $requestParam->get('url');
        if ( empty($url)) {
            $this->json(FrontConst::OPER_STATUS_FAIL_TOAST, '参数为空', array());
            return;
        }
        $returnData['status'] = Soma_base::STATUS_TRUE;
        $returnData = \App\services\soma\WxService::getInstance()->getSignUpdateCode($this->inter_id, $url);
        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $returnData);
        return ;
    }


    /**
     * 新版皮肤
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function get_index()
    {

    }

    /**
     * @SWG\Get(
     *     path="/index/test",
     *     summary="test",
     *     tags={"package"},
     *     @SWG\Response(
     *         response=200,
     *         description="package",
     *         @SWG\Schema(
     *             ref="#/definitions/SomaPackage",
     *             additionalProperties={
     *                  "name":"test",
     *                  "type":"integer",
     *                  "format":"int32"
     *              }
     *         ),
     *     )
     * )
     */
    public function get_test()
    {
        echo 1;
    }

    /**
     * @SWG\Get(
     *     path="/index/test2",
     *     summary="test",
     *     tags={"package"},
     *     @SWG\Response(
     *         response=200,
     *         description="package",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="products",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/SomaPackage"),
     *              ),
     *              @SWG\Property(
     *                  property="killsecs",
     *                  type="object",
     *                  ref="#/definitions/SomaKillsec",
     *              )
     *         ),
     *     )
     * )
     */
    public function get_test2()
    {
        echo 2;
    }

    /**
     * @SWG\Get(
     *     path="/index/test3",
     *     summary="test",
     *     tags={"package"},
     *     @SWG\Response(
     *         response=200,
     *         description="package",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="products",
     *                  ref="#/definitions/SomaPackage",
     *              ),
     *              @SWG\Property(
     *                  property="test",
     *                  type="string",
     *                  example="testName"
     *              )
     *         ),
     *     )
     * )
     */
    public function get_test3()
    {
        echo 3;
    }

    /**
     * @SWG\Get(
     *     path="/index/test4",
     *     summary="test",
     *     tags={"package"},
     *     @SWG\Response(
     *         response=200,
     *         description="package",
     *         @SWG\Schema(
     *                  @SWG\Property(
     *                      property="test",
     *                      type="string",
     *                      example="testName"
     *                 )
     *         ),
     *     )
     * )
     */
    public function get_test4()
    {
        echo 4;
    }

    public function put_test()
    {
        $params = $this->input->input_json();
        $this->json(1, '', $params->only(['status']));
    }



}