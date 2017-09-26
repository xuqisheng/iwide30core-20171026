<?php

/**
 * 礼包派送接口 Controller
 * Created by Wuqd on.
 * Created Time 2017-09-03 15:00
 */
use \App\libraries\Iapi\BaseConst;

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
     *     @SWG\Parameter(
     *         description="分页",
     *         in="query",
     *         name="page",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              property="pageCount",
     *              description="分页数",
     *              type = "integer"
     *         ),
     *        @SWG\Schema(
     *              property="count",
     *              description="总记录数",
     *              type = "integer"
     *         ),
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
     *                  property="end_time",
     *                  description="礼包有效日期",
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

        $params['page'] = $this->input->get('page');
        $params['page'] = empty($params['page']) ? 0 : intval($params['page'] - 1);

        if(empty($params['inter_id']) || empty($params['saler_id'])){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'无法获取参数!','');
        }

        //验证分销员saler_id 是否属于inter_id
        $countRes = $this->db->select(['exts'])->where(['inter_id'=>$params['inter_id'],'qrcode_id'=>$params['saler_id']])
            ->get('hotel_staff')->row_array();

        if(empty($countRes)){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'分销员信息有误!','');
        }

        //验证是否有派送礼包权限
        $extsArr = unserialize($countRes['exts']);
        if(empty($extsArr) || $extsArr['join_gift'] == 1){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'无派送礼包权限,请向管理员申请!','');
        }

        //获取inter_id 当前是否有可用礼包
        /**
         * @var \Gift_detail_model $Gift_detail_model
         */
        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;
        $time = time();
        $countRes = $Gift_detail_model->_shard_db_r($this->inter_id)->where(['inter_id'=>$params['inter_id'],'start_time <'=>$time,'end_time >'=>$time])->count_all_results('soma_gift_effective_time');
//
        if($countRes == 0){
            return $this->json(BaseConst::OPER_STATUS_SUCCESS,'当前时间段暂无可用礼包!',[]);
        }

        $pageStart = $params['page'] * 20;
        $pageLength = 20;
        //获取礼包
        $giftListRes = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['scp.stock','scp.name','sf.id as gift_id','sf.product_id','scp.goods_type'])->from('soma_gift sf')
            ->join('soma_catalog_product_package scp','scp.product_id = sf.product_id','left')
            ->where(['sf.inter_id'=>$params['inter_id'],'scp.stock >'=>0,'scp.status'=>1])
            ->limit($pageLength,$pageStart)->get()->result_array();

        //获取礼包有效期
        $giftEndTime = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['end_time'])->where(['inter_id'=>$params['inter_id']])->get('iwide_soma_gift_effective_time')->row_array();
        $giftEndTime = date('Y年m月d日',$giftEndTime['end_time']);

        //获取组合商品名称
        $productIdArr = arrayColumn($giftListRes,'product_id');
        $groupProductInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['parent_pid','child_pid','num'])->where(['inter_id'=>$params['inter_id']])->where_in('parent_pid',$productIdArr)
            ->get('soma_product_package_link')->result_array();
        //获取子商品名称
        $childProductIdArr = arrayColumn($groupProductInfo,'child_pid');
        $childProductInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['product_id','name'])->where(['inter_id'=>$params['inter_id']])->where_in('product_id',$childProductIdArr)
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
            $giftListRes[$key]['end_time'] = $giftEndTime;
        }

        //列表返回
        return $this->json(\App\libraries\Iapi\BaseConst::OPER_STATUS_SUCCESS,'',$giftListRes);
    }


    /**
     * @SWG\Post(
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
     *         description="礼包数量",
     *         in="query",
     *         name="gift_num",
     *         required=true,
     *         type="integer",
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
    public function post_generate_gift(){

        $paramsJson = $this->input->input_json();
        $params['gift_id'] = intval($paramsJson['gift_id']);
        $params['gift_num'] = intval($paramsJson['gift_num']);
        $params['saler_id'] = intval($paramsJson['saler_id']);
        $params['saler_name'] = $paramsJson['saler_name'];
        $params['record_info'] = $paramsJson['record_info'];
        $params['orther_remark'] = $paramsJson['orther_remark'];
        $params['inter_id'] = $paramsJson['inter_id'];

        $fieldArr = ['inter_id','gift_id','saler_id','saler_name','record_info'];
        foreach($fieldArr as $key=>$val){
            if(empty($params[$val])){
                return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'缺少'.$val,'');
            }
        }
        $params['gift_num'] = empty($params['gift_num']) ? 1 : $params['gift_num'];

        //验证saler_id 与 gift_id是否同属inter_id
        $countStaffRes = $this->db->select(['exts'])->where(['inter_id'=>$params['inter_id'],'qrcode_id'=>$params['saler_id']])
            ->get('hotel_staff')->row_array();

        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;
        $this->db = $Gift_detail_model->_shard_db_r($this->inter_id);

        $countGiftRes = $this->db->where(['id'=>$params['gift_id'],'inter_id'=>$params['inter_id']])
            ->count_all_results('soma_gift');
        if(empty($countStaffRes) || $countGiftRes <= 0){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'分销员与礼包不同属inter_id','');
        }

        //验证是否有派送礼包权限
        $extsArr = unserialize($countStaffRes['exts']);
        if(empty($extsArr) || $extsArr['join_gift'] == 1){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'无派送礼包权限,请向管理员申请!','');
        }

        $startTime = strtotime(date('Y-m-d 00:00:00',time()));
        $endTime = strtotime(date('Y-m-d 23:59:59',time()));
        //获取已成功派送的礼包数量
        $giftCount = $this->db->select(['sum(gift_num) as gift_num_count'])->where(['inter_id'=>$params['inter_id'],'saler_id'=>$params['saler_id'],'is_receive'=>2,'add_time >'=>$startTime,'add_time <'=>$endTime])
            ->get('soma_gift_detail')->row_array();
        $generate_gift_num = intval($giftCount['gift_num_count'] + $params['gift_num']);
        if(!empty($extsArr['gift_limit']) && $generate_gift_num > intval($extsArr['gift_limit'])){
            $surplusNum = intval($extsArr['gift_limit'] - $giftCount['gift_num_count']);
            $surplusNum = $surplusNum < 0 ? 0 : $surplusNum;
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'发放数量超过上限,剩余数量'.$surplusNum,'');
        }
        //礼包库存校验
        $countProductRes = $this->db->select(['sf.product_id','scp.stock'])->from('soma_gift sf')
            ->join('soma_catalog_product_package scp','scp.product_id = sf.product_id','left')
            ->where(['sf.inter_id'=>$params['inter_id'],'sf.id'=>$params['gift_id'],'scp.stock >'=>0,'scp.status'=>1])
            ->get()->row_array();

        if($countProductRes['stock'] < $params['gift_num']){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'库存不足!','');
        }

        //生成请求token
        $request_token = md5($params['inter_id'].mt_rand(1000,9999));
        $params['request_token'] = $request_token;

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $resultInfo = $this->gift_delivery_model->generateGiftData($params);
        $link = $this->link['qrcode_and_gift_detail'];
        $link.='&gift_detail_id='.$resultInfo.'&inter_id='.$params['inter_id'].'&request_token='.$request_token;
        $page_resource = [
            'link' => [
                'gift_detail' => $link
            ]
        ];
        if($resultInfo){
            return $this->json(BaseConst::OPER_STATUS_SUCCESS,'',['gift_detail_id'=>$resultInfo,'request_token'=>$request_token,'page_resource'=>$page_resource]);
        }else{
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'生成失败!','');
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

        if(empty($params['gift_detail_id']) || empty($params['inter_id'])){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'参数有误或为空!','');
        }

        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;
        $this->db = $Gift_detail_model->_shard_db_r($this->inter_id);

        $giftDetailRes = $this->db->select(['id as gift_detail_id','gift_id','saler_id','inter_id','request_token'])->where(['id'=>$params['gift_detail_id'],'inter_id'=>$params['inter_id'],'request_token'=>$params['request_token']])
            ->get('soma_gift_detail')->row_array();

        if(empty($giftDetailRes)){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'未生成礼包!','');
        }

        //生成礼包二维码地址
        $baseUrl = base_url();
        $requestUrl = trim($_SERVER['PHP_SELF'],'/');
        $requestUrl = explode('?',$requestUrl)[0];
        $requestUrl = str_replace('/iapi','',$requestUrl);
        $qrCodeUrl = $baseUrl.$requestUrl;

        //礼包详情
        $qrCodeAction = 'receive_gift_detail';
        $paramsStr = '?';
        $giftDetailRes['id'] = $giftDetailRes['inter_id'];
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
        $value = str_replace('index.php/index.php','index.php',$value);

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

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;
        $this->db = $Gift_detail_model->_shard_db_r($this->inter_id);
        //获取礼包详情
        $resultInfo = $this->gift_delivery_model->getGiftDetailData($params);

        if($resultInfo['status']){
            return $this->json(BaseConst::OPER_STATUS_SUCCESS,'',$resultInfo['message']);
        }else{
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,$resultInfo['message'],'');
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
        $params['id'] = intval($this->input->get('gift_detail_id'));
        $params['gift_id'] = intval($this->input->get('gift_id'));
        $params['saler_id'] = intval($this->input->get('saler_id'));
        $params['inter_id'] = $this->input->get('inter_id');
        $params['request_token'] = $this->input->get('request_token');

        $paramsArr = ['id','gift_id','saler_id','inter_id','request_token'];
        foreach($paramsArr as $key=>$val){
            if(empty($params[$val])){
                return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'请求参数有误!','');
            }
        }

        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;
        $this->db = $Gift_detail_model->_shard_db_r($this->inter_id);
        //判断二维码是否失效
        $giftDetailInfo = $this->db->select(['add_time','is_receive'])->where($params)->get('soma_gift_detail')->row_array();

        if(empty($giftDetailInfo)){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包二维码有误,请重新生成!','');
        }


        $page_resource = [
            'link' => [
                'gift_detail' => $this->link['home']
            ],
            'gift_status'=>2
        ];

        $data['page_resource'] = $page_resource;

        $time = time();
        $gift_expiration_date = intval($giftDetailInfo['add_time']) + 600;
        if($gift_expiration_date < $time){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包二维码已失效,请重新生成!',$data);
        }

        //判断是否被领取
        if($giftDetailInfo['is_receive'] == 2){
            //159用于前段特殊判断 跳转至商城首页
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包已被领取!',$data);
        }

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');

        $resultInfo = $this->gift_delivery_model->getQrcodeGiftDetailData($params);

        if($resultInfo['status'] == false){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,$resultInfo['message'],[]);
        }

        return $this->json(BaseConst::OPER_STATUS_SUCCESS,'',$resultInfo['message']);

    }


    /**
     * @SWG\Post(
     *     tags={"GiftDelivery"},
     *     path="/GiftDelivery/generate_gift_order",
     *     summary="确认领取礼包",
     *     description="确认领取礼包",
     *     operationId="generate_gift_order",
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
     *         description="请求校验token",
     *         in="query",
     *         name="request_token",
     *         required=true,
     *         type="integer",
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
    public function post_generate_gift_order(){

        $paramsJson = $this->input->input_json();
        $params['id'] = intval($paramsJson['gift_detail_id']);
        $params['inter_id'] = $paramsJson['inter_id'];
        $params['request_token'] = $paramsJson['request_token'];
        $gift_detail_id = $params['id'];

        $openid = $this->openid;
            if(empty($openid)){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'获取用户信息失败，请重试!','');
        }

        //获取礼包详情信息
        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;

        $giftDetailInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['gift_id','add_time','is_receive','gift_num','saler_id'])->where($params)->get('soma_gift_detail')->row_array();
        $saler_id = $giftDetailInfo['saler_id'];

        if(empty($giftDetailInfo)){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包二维码有误,请重新生成!','');
        }

        $time = time();
        $gift_expiration_date = intval($giftDetailInfo['add_time']) + 600;
        if($gift_expiration_date < $time){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包二维码已失效,请重新生成!','');
        }

        //校验礼包商品库存是否充足与上架
        $productId = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['product_id'])->where(['id'=>$giftDetailInfo['gift_id']])->get('soma_gift')->row_array();
        $productRes = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['product_id','stock','status'])->where(['product_id'=>$productId['product_id']])
            ->get('soma_catalog_product_package')->row_array();

        if(empty($productRes)){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包商品不存在!','');
        }

        //验证saler_id 与 gift_id是否同属inter_id
        $countStaffRes = $this->db->select(['exts'])->where(['inter_id'=>$params['inter_id'],'qrcode_id'=>$giftDetailInfo['saler_id']])
            ->get('hotel_staff')->row_array();
        //验证是否有派送礼包权限
        $extsArr = unserialize($countStaffRes['exts']);
        if(empty($extsArr) || $extsArr['join_gift'] == 1){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'无派送礼包权限,请向管理员申请!','');
        }
        $startTime = strtotime(date('Y-m-d 00:00:00',time()));
        $endTime = strtotime(date('Y-m-d 23:59:59',time()));
        //获取已成功派送的礼包数量
        $giftCount = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['sum(gift_num) as gift_num_count'])->where(['inter_id'=>$params['inter_id'],'saler_id'=>$giftDetailInfo['saler_id'],'is_receive'=>2,'add_time >'=>$startTime,'add_time <'=>$endTime])
            ->get('soma_gift_detail')->row_array();
        $generate_gift_num = intval($giftCount['gift_num_count'] + $giftDetailInfo['gift_num']);
        if(!empty($extsArr['gift_limit']) && $generate_gift_num > intval($extsArr['gift_limit'])){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'发放数量超过上限,暂不能领取!','');
        }

        if($productRes['stock'] < $giftDetailInfo['gift_num']){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包商品库存不足!','');
        }

        if($productRes['status'] != 1){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包商品已下架!','');
        }

        //判断是否被领取
        if($giftDetailInfo['is_receive'] == 2){
            return $this->json(BaseConst::OPER_STATUS_FAIL_TOAST,'礼包已被领取!','');
        }

        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $params['openid'] = $openid;
        $resultInfo = $this->gift_delivery_model->giftOrderCreate($params);
        $result = $resultInfo['result'];
        $params = $resultInfo['params'];
        //返回
        if ($result->getStatus() == \App\services\Result::STATUS_OK) {

            $link = null;
            $result = $result->getData();
            //直接支付
            if (in_array($result['payChannel'], ['already_pay', 'balance_pay', 'point_pay'])) {
//                $link = $this->link['already_pay'] . '&bType=' . '&saler=' . $this->session->tempdata('saler') . '&order_id=' . $result['orderInfo']['order_id'] . '&settlement=' . $params['settlement'] . '&zburl=' . $this->session->tempdata('zburl');
            } //微信支付
            elseif ($result['payChannel'] == 'wx_pay') {
                $this->gift_delivery_model->gift_receive_callback($result['orderInfo']['order_id']);
//                $link = base_url().'index.php/soma/package/pay_success_stay?id='.$params['inter_id'].'&oid='.$result['orderInfo']['order_id']. '&settlement=' . $params['settlement'];

            } //威付通支付
            elseif ($result['payChannel'] == 'wft_pay') {
                $this->gift_delivery_model->gift_receive_callback($result['orderInfo']['order_id']);
//                $link = base_url().'index.php/soma/package/pay_success_stay?id='.$params['inter_id'].'&oid='.$result['orderInfo']['order_id']. '&settlement=' . $params['settlement'];
            }

            //商城订单列表
            $link = base_url().'index.php/soma/order/my_order_list?id='.$params['inter_id'].'&layout=&tkid=&brandname=';
            $page_resource = [
                'link' => [
                    'gift_detail' => $link
                ]
            ];
            $data['page_resource'] = $page_resource;

            $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
            $Gift_detail_model = $this->Gift_detail_model;

            //更改礼包状态
            $Gift_detail_model->_shard_db_r($this->inter_id)->where(['inter_id'=>$params['inter_id'],'id'=>$gift_detail_id])->update('soma_gift_detail',['openid'=>$params['openid'],'is_receive'=>2,'order_id'=>$result['orderInfo']['order_id'],'updated_time'=>date('Y-m-d H:i:s',time())]);
            //订单记录分销员id
            $Gift_detail_model->_shard_db_r($this->inter_id)->where(['order_id'=>$result['orderInfo']['order_id']])->update('soma_sales_order_1001',['saler_id'=>$saler_id]);
            return $this->json(BaseConst::OPER_STATUS_SUCCESS,'支付成功!',$data);

        } else {
            $this->json(BaseConst::OPER_STATUS_FAIL_TOAST, $result->getMessage());
        }

    }


}














