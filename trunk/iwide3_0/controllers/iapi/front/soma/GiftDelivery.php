<?php

/**
 * 礼包派送接口 Controller
 * Created by Wuqd on.
 * Created Time 2017-09-03 15:00
 */

class GiftDelivery extends MY_Front_Soma_Iapi{

    /**
     * @SWG\Get(
     *     tags={"GiftDelivery"},
     *     path="/GiftDelivery/gift_list",
     *     summary="获取可用礼包列表",
     *     description="获取可用礼包列表",
     *     operationId="gift_list",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="公众号id",
     *         in="query",
     *         name="inter_id",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="分销员id",
     *         in="query",
     *         name="saler_id",
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
     *                  property="stock",
     *                  description="商品库存",
     *                  type = "integer"
     *              ),
     *              @SWG\Property(
     *                  property="name",
     *                  description="商品名称",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="gift_id",
     *                  description="礼包id",
     *                  type = "integer",
     *              ),
     *              @SWG\Property(
     *                  property="product_id",
     *                  description="商品id",
     *                  type = "integer",
     *              ),
     *              @SWG\Property(
     *                  property="goods_type",
     *                  description="商品类型",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="child_product_info",
     *                  description="子商品信息",
     *                  type = "array",
     *                  @SWG\Property(
     *                     property="name",
     *                     description="商品名称",
     *                     type = "integer"
     *                  ),
     *                 @SWG\Property(
     *                     property="num",
     *                     description="商品数量",
     *                     type = "string",
     *                 ),
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
    public function get_gift_list(){

        //接受参数
        $params = array();
        $params['inter_id'] = $this->input->get('inter_id');
        $params['saler_id'] = $this->input->get('saler_id');
        $params['inter_id'] = 'a450089706';
        $params['saler_id'] = '64888';
        $params['page'] = $this->input->get('page');
        $params['page'] = empty($params['page']) ? 0 : intval($params['page'] - 1);

        if(empty($params['inter_id']) || empty($params['saler_id'])){
            return $this->json(2,'无法获取参数!','');
        }

        //验证分销员saler_id 是否属于inter_id
        $countRes = $this->db->where(['id'=>$params['saler_id'],'inter_id'=>$params['inter_id'],'distribute_hidden'=>0])
            ->count_all_results('hotel_staff');
        if($countRes == 0){
            return $this->json(2,'分销员信息有误!','');
        }

        $this->db->db_select('iwide30soma');
        //获取inter_id 当前是否有可用礼包
        $time = time();
        $countRes = $this->db->where(['inter_id'=>$params['inter_id'],'start_time <'=>$time,'end_time >'=>$time])->count_all_results('soma_gift_effective_time');

        if($countRes == 0){
            return $this->json(2,'','');
        }

        $pageStart = $params['page'] * 20;
        //获取礼包
        $giftListRes = $this->db->select(['scp.stock','scp.name','sf.id as gift_id','sf.product_id','scp.goods_type'])->from('soma_gift sf')
            ->join('soma_catalog_product_package scp','scp.product_id = sf.product_id','left')
            ->where(['sf.inter_id'=>$params['inter_id'],'scp.stock >'=>0,'scp.status'=>1])
            ->limit($pageStart,20)->get()->result_array();
        //获取组合商品名称
        $productIdArr = arrayColumn($giftListRes,'product_id');
        $groupProductInfo = $this->db->select(['parent_pid','child_pid','num'])->where(['inter_id'=>$params['inter_id']])->where_in('parent_pid',$productIdArr)
            ->get('soma_product_package_link')->result_array();
        //获取子商品名称
        $childProductIdArr = arrayColumn($groupProductInfo,'child_pid');
        $childProductInfo = $this->db->select(['product_id','name'])->where(['inter_id'=>$params['inter_id']])->where_in('product_id',$childProductIdArr)
            ->get('soma_catalog_product_package')->result_array();
        $childProductField = arrayField($childProductInfo,'product_id');
        //子商品数组整合
        foreach($groupProductInfo as $key=>$val){
            $groupProductInfo[$key]['name'] = $childProductField[$val['child_pid']]['name'];
        }

        $childProductArr = array();
        foreach($groupProductInfo as $key=>$val){
            $parent_pid = $val['parent_pid'];
            unset($val['parent_pid']);
            unset($val['child_pid']);
            $childProductArr[$parent_pid][] = $val;
        }

        //分组商品整合
        foreach($giftListRes as $key=>$val){
            $giftListRes[$key]['child_product_info'] = $val['goods_type'] == 3 ? $childProductArr[$val['product_id']] : [['num'=>1,'name'=>$val['name']]];
        }

        //列表返回
        return $this->json(1,'',$giftListRes);

    }


    /**
     * @SWG\Get(
     *     tags={"GiftDelivery"},
     *     path="/GiftDelivery/generate_gift",
     *     summary="生成礼包",
     *     description="生成礼包",
     *     operationId="generate_gift",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="礼包id",
     *         in="query",
     *         name="gift_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="公众号id",
     *         in="query",
     *         name="inter_id",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="分销员id",
     *         in="query",
     *         name="saler_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="分销员名称",
     *         in="query",
     *         name="saler_name",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="登记信息",
     *         in="query",
     *         name="record_info",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="其他",
     *         in="query",
     *         name="orther_remark",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="gift_detail_id",
     *                  description="礼包详情id",
     *                  type = "integer"
     *              ),
     *              @SWG\Property(
     *                  property="request_token",
     *                  description="请求校验的token",
     *                  type = "string",
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
    public function get_generate_gift(){

        $params = array();
        $params['inter_id'] = $this->input->get('inter_id');
        $params['gift_id'] = intval($this->input->get('gift_id'));
        $params['gift_num'] = intval($this->input->get('gift_num'));
        $params['saler_id'] = intval($this->input->get('saler_id'));
        $params['saler_name'] = $this->input->get('saler_name');
        $params['orther_remark'] = $this->input->get('orther_remark');
        $params['record_info'] = $this->input->get('record_info');

//        $params['saler_name'] = 'Wuqd';
//        $params['orther_remark'] = '其他';
//        $params['record_info'] = '2031房间';
//        $params['inter_id'] = 'a450089706';
//        $params['saler_id'] = '64888';
//        $params['gift_id'] = 34;

        $fieldArr = ['inter_id','gift_id','saler_id','saler_name','record_info'];
        foreach($fieldArr as $key=>$val){
            if(empty($params[$val])){
                return $this->json(2,'缺少'.$val,'');
            }
        }
        $params['gift_num'] = empty($params['gift_num']) ? 1 : $params['gift_num'];

        //验证saler_id 与 gift_id是否同属inter_id
        $countStaffRes = $this->db->where(['id'=>$params['saler_id'],'inter_id'=>$params['inter_id'],'distribute_hidden'=>0])
            ->count_all_results('hotel_staff');

        $this->db->db_select('iwide30soma');
        $countGiftRes = $this->db->where(['id'=>$params['gift_id'],'inter_id'=>$params['inter_id']])
            ->count_all_results('soma_gift');
        if($countStaffRes <= 0 || $countGiftRes <= 0){
            return $this->json(2,'分销员与礼包不同属inter_id','');
        }

        //礼包库存校验
        $countProductRes = $this->db->select('sf.product_id')->from('soma_gift sf')
            ->join('soma_catalog_product_package scp','scp.product_id = sf.product_id','left')
            ->where(['sf.inter_id'=>$params['inter_id'],'sf.id'=>$params['gift_id'],'scp.stock >'=>0,'scp.status'=>1])
            ->get()->result_array();

        if($countProductRes <= 0){
            return $this->json(2,'库存不足!','');
        }

        //生成请求token
        $request_token = md5($params['inter_id'].mt_rand(1000,9999));
        $params['request_token'] = $request_token;

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $resultInfo = $this->gift_delivery_model->generateGiftData($params);
        if($resultInfo){
            return $this->json(1,'',['gift_detail_id'=>$resultInfo,'request_token'=>$request_token]);
        }else{
            return $this->json(2,'生成失败!','');
        }
    }


    /**
     * @SWG\Get(
     *     tags={"GiftDelivery"},
     *     path="/GiftDelivery/generate_gift_qrcode",
     *     summary="生成礼包二维码",
     *     description="生成礼包二维码",
     *     operationId="generate_gift_qrcode",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="礼包详情id",
     *         in="query",
     *         name="gift_detail_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="公众号id",
     *         in="query",
     *         name="inter_id",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="请求token",
     *         in="query",
     *         name="request_token",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
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
    public function get_generate_gift_qrcode(){

        $params = array();
        $params['gift_detail_id'] = $this->input->get('gift_detail_id'); //礼包详情id
        $params['inter_id'] = $this->input->get('inter_id'); //公众号id
        $params['request_token'] = $this->input->get('request_token'); //校验token
//        $params['gift_detail_id'] = 5;
//        $params['inter_id'] = 'a450089706';
//        $params['request_token'] = 'da3086a1badf3c19b0234cebdac741dc'; //校验token

        if(empty($params['gift_detail_id']) || empty($params['inter_id'])){
            return $this->json(2,'参数有误或为空!','');
        }

        $this->db->db_select('iwide30soma');
        $giftDetailRes = $this->db->select(['id','gift_id','saler_id','inter_id','request_token'])->where(['id'=>$params['gift_detail_id'],'inter_id'=>$params['inter_id'],'request_token'=>$params['request_token']])
            ->get('soma_gift_detail')->row_array();

        if(empty($giftDetailRes)){
            return $this->json(2,'未生成礼包!','');
        }

        //生成礼包二维码地址
        $baseUrl = base_url();
        $requestUrl = trim($_SERVER['REQUEST_URI'],'/');
        $qrCodeUrl = $baseUrl.$requestUrl;

        //礼包详情
        $qrCodeAction = 'qrcode_gift_detail';
        $paramsStr = '?';
        foreach($giftDetailRes as $key=>$val){
            $paramsStr.=$key.'='.$val.'&';
        }

        //参数拼接
        if($paramsStr != '?'){
            $paramsStr = trim($paramsStr,'&');
            $qrCodeAction.=$paramsStr;
        }

        //url function替换
        $currentFunName = $this->uri->segment(4);
        $qrCodeUrl = str_replace($currentFunName,$qrCodeAction,$qrCodeUrl);
        //生成二维码
        $this->load->helper('phpqrcode');
        $value = $qrCodeUrl; //二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片
        QRcode::png($value,false, $errorCorrectionLevel, $matrixPointSize, 2);

    }


    /**
     * @SWG\Get(
     *     tags={"GiftDelivery"},
     *     path="/GiftDelivery/gift_detail",
     *     summary="二维码礼包详情页",
     *     description="二维码礼包详情页",
     *     operationId="gift_detail",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="礼包详情id",
     *         in="query",
     *         name="gift_detail_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="公众号id",
     *         in="query",
     *         name="inter_id",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="请求token",
     *         in="query",
     *         name="request_token",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *            type="object",
     *            @SWG\Property(
     *               property="product_id",
     *               description="商品id",
     *               type = "integer"
     *            ),
     *            @SWG\Property(
     *               property="name",
     *               description="商品名称",
     *               type = "string",
     *            ),
     *            @SWG\Property(
     *               property="stock",
     *               description="商品库存",
     *               type = "integer",
     *            ),
     *            @SWG\Property(
     *               property="price_market",
     *               description="商品市场价格",
     *               type = "string",
     *            ),
     *            @SWG\Property(
     *               property="expiration_date",
     *               description="商品有效期时间",
     *               type = "string",
     *            ),
     *            @SWG\Property(
     *                  property="child_product_info",
     *                  description="子商品信息",
     *                  type = "array",
     *                  @SWG\Property(
     *                     property="name",
     *                     description="商品名称",
     *                     type = "integer"
     *                  ),
     *                 @SWG\Property(
     *                     property="num",
     *                     description="商品数量",
     *                     type = "string",
     *                 ),
     *            ),
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
    public function get_gift_detail(){

        $params = array();
        $params['gift_detail_id'] = intval($this->input->get('gift_detail_id')); //礼包详情id
        $params['inter_id'] = $this->input->get('inter_id'); //公众号id
        $params['request_token'] = $this->input->get('request_token'); //校验token
//        $params['gift_detail_id'] = 5;
//        $params['inter_id'] = 'a450089706';
//        $params['request_token'] = 'da3086a1badf3c19b0234cebdac741dc'; //校验token

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $this->db->db_select('iwide30soma');

        //获取礼包详情
        $resultInfo = $this->gift_delivery_model->getGiftDetailData($params);

        if($resultInfo['status']){
            return $this->json(1,'',$resultInfo['message']);
        }else{
            return $this->json(2,$resultInfo['message'],'');
        }
    }


    /**
     * @SWG\Get(
     *     tags={"GiftDelivery"},
     *     path="/GiftDelivery/qrcode_gift_detail",
     *     summary="确认领礼包取礼包详情页",
     *     description="确认领礼包取礼包详情页",
     *     operationId="qrcode_gift_detail",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="礼包详情id",
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="公众号id",
     *         in="query",
     *         name="inter_id",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="请求token",
     *         in="query",
     *         name="request_token",
     *         required=true,
     *         type="string",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="分销员id",
     *         in="query",
     *         name="saler_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Parameter(
     *         description="礼包id",
     *         in="query",
     *         name="gift_id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *            type="object",
     *            @SWG\Property(
     *               property="product_id",
     *               description="商品id",
     *               type = "integer"
     *            ),
     *            @SWG\Property(
     *               property="name",
     *               description="商品名称",
     *               type = "string",
     *            ),
     *            @SWG\Property(
     *               property="stock",
     *               description="商品库存",
     *               type = "integer",
     *            ),
     *            @SWG\Property(
     *               property="price_market",
     *               description="商品市场价格",
     *               type = "string",
     *            ),
     *            @SWG\Property(
     *               property="expiration_date",
     *               description="商品有效期时间",
     *               type = "string",
     *            ),
     *            @SWG\Property(
     *                  property="child_product_info",
     *                  description="子商品信息",
     *                  type = "array",
     *                  @SWG\Property(
     *                     property="name",
     *                     description="商品名称",
     *                     type = "integer"
     *                  ),
     *                 @SWG\Property(
     *                     property="num",
     *                     description="商品数量",
     *                     type = "string",
     *                 ),
     *            ),
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
    public function get_qrcode_gift_detail(){

        $params = array();
        $params['id'] = intval($this->input->get('id'));
        $params['gift_id'] = intval($this->input->get('gift_id'));
        $params['saler_id'] = intval($this->input->get('saler_id'));
        $params['inter_id'] = $this->input->get('inter_id');
        $params['request_token'] = $this->input->get('request_token');

        $paramsArr = ['id','gift_id','saler_id','inter_id','request_token'];
        foreach($paramsArr as $key=>$val){
            if(empty($params[$val])){
                return $this->json(2,'请求参数有误!','');
            }
        }

        $this->db->db_select('iwide30soma');
        //判断二维码是否失效
        $giftDetailInfo = $this->db->select(['add_time','is_receive'])->where($params)->get('soma_gift_detail')->row_array();

        if(empty($giftDetailInfo)){
            return $this->json(2,'礼包二维码有误,请重新生成!','');
        }

        $time = time();
        $gift_expiration_date = intval($giftDetailInfo['add_time']) + 600;
        if($gift_expiration_date < $time){
            return $this->json(2,'礼包二维码已失效,请重新生成!','');
        }

        //判断是否被领取
        if($giftDetailInfo['is_receive'] == 2){
            return $this->json(2,'礼包已被领取!','');
        }

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');

        $resultInfo = $this->gift_delivery_model->getQrcodeGiftDetailData($params);

        return $this->json(1,'',$resultInfo['message']);

    }


    /***
     * 确认领取礼包
     */
    public function get_generate_gift_order(){

        $params = array();
        $params['id'] = intval($this->input->get('id'));
        $params['inter_id'] = $this->input->get('inter_id');
        $params['request_token'] = $this->input->get('request_token');
        $params['id'] = 5;
        $params['inter_id'] = 'a450089706';
        $params['request_token'] = 'da3086a1badf3c19b0234cebdac741dc'; //校验token

        $openid = $this->openid;
        if(empty($openid)){
            return $this->json(2,'获取用户信息失败，请重试!','');
        }

        //获取礼包详情信息
        $this->db->db_select('iwide30soma');
        //判断二维码是否失效
        $giftDetailInfo = $this->db->select(['gift_id','add_time','is_receive'])->where($params)->get('soma_gift_detail')->row_array();

        if(empty($giftDetailInfo)){
            return $this->json(2,'礼包二维码有误,请重新生成!','');
        }

        $time = time();
        $gift_expiration_date = intval($giftDetailInfo['add_time']) + 600;
        if($gift_expiration_date < $time){
            return $this->json(2,'礼包二维码已失效,请重新生成!','');
        }

        //校验礼包商品库存是否充足与上架
        $productId = $this->db->select(['product_id'])->where(['id'=>$giftDetailInfo['gift_id']])->get('soma_gift')->row_array();
        $productRes = $this->db->select(['product_id','stock','status'])->where(['product_id'=>$productId['product_id']])
            ->get('soma_catalog_product_package')->row_array();

        if(empty($productRes)){
            return $this->json(2,'礼包商品不存在!','');
        }

        if($productRes['stock'] <= 0){
            return $this->json(2,'礼包商品库存不足!','');
        }

        if($productRes['status'] != 1){
            return $this->json(2,'礼包商品已下架!','');
        }

        //判断是否被领取
        if($giftDetailInfo['is_receive'] == 2){
            return $this->json(2,'礼包已被领取!','');
        }

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $params['openid'] = $openid;
        $this->gift_delivery_model->giftOrderCreate($params);
    }





}













