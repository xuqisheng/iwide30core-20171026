<?php
use App\libraries\Iapi\FrontConst;

/**
 * Class Presents
 *
 *
 * 商品赠送相关接口
 *
 * @property Product_package_model $productPackageModel
 */
class Presents extends MY_Front_Soma_Iapi
{


    protected $itemFiledFilter = ['parent_id', 'parent_id', 'hotel_id', 'openid_origin', 'type', 'sku', 'conn_devices', 'name_en', 'card_id', 'compose', 'transparent_img', 'compose_en', 'use_cnt', 'can_split_use', 'can_wx_booking', 'wx_booking_config', 'can_refund', 'can_mail', 'can_gift', 'can_pickup', 'can_sms_notify', 'can_invoice', 'can_reserve', 'is_hide_reserve_date', 'room_id', 'add_time', 'send_wxtemp_status'];
    //http://local.iwide.com/iapi/soma/presents/package?aiid=6132&group=1&id=a450089706&bsn=package&send_from=1&send_order_id=1000011754&saler=29&openid=o9Vbtw1W0ke-eb0g6kE4SD1eh6qU

    /**
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/package",
     *     summary="赠送礼物",
     *     description="return item info",
     *     operationId="get_package",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="资产ID",
     *         in="query",
     *         name="aiid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="公众号ID",
     *         in="query",
     *         name = "id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="业务类型，如package",
     *         in="query",
     *         name = "bsn",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              ref="#/definitions/SomaGiftResult"
     *         )
     *     )
     * )
     */
    public function get_package()
    {

        $is_group = $this->input->get('group');
        $business = $this->input->get('bsn');

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $this->load->model('soma/Asset_item_package_model', 'assetItemModel');
        $asset_item_id = $this->input->get('aiid');
        $inter_id = $this->inter_id;
        $items = $this->assetItemModel->get_order_items_byItemids(array($asset_item_id), $business, $inter_id);

        $filter_array = $this->itemFiledFilter;
        foreach ($items as $key => $item) {
            $item = new \App\libraries\Support\Collection($item);  //collect collect($item)
            $item->toArray();
            $item = $item->except($filter_array);
            $items[$key] = $item->toArray();
        }

        //检查能否赠送
        $this->load->model("soma/Sales_order_model", 'SalesOrderModel');
        $order_id = isset($items[0]['order_id']) ? $items[0]['order_id'] : '';
        $SalesOrderModel = $this->SalesOrderModel->load($order_id);
        if ($SalesOrderModel) {
            if (!$SalesOrderModel->can_gift_order()) {
                $this->json(FrontConst::OPER_STATUS_FAIL_ALERT,$this->lang->line('can_not_gift_tip'));
                return;
            }
        } else {
            $this->json(FrontConst::OPER_STATUS_FAIL_ALERT,'检查能否赠送失败，加载sales_order_model失败！！');
            return;
        }

        if (count($items) == 0) {
            $this->json(FrontConst::OPER_STATUS_FAIL_ALERT,'参数错误！');
            return;

        } elseif ($items[0]['openid'] != $this->openid) {
            //并非自己的资产不能处理
            $this->json(FrontConst::OPER_STATUS_FAIL_ALERT,'无可赠送礼物！');
            return;
        }
        //纠正发送方式
        if ($items[0]['qty'] < 2) {
            $is_group = Soma_base::STATUS_FALSE;
        }
        if (!$is_group) {
            $is_group = Soma_base::STATUS_FALSE;//防止没有传group参数进来，而且剩余数量大于2
        }

        $time = time();
        $expireTime = isset($items[0]['expiration_date']) ? strtotime($items[0]['expiration_date']) : null;
        $is_expire = Soma_base::STATUS_FALSE;
        if ($expireTime && $expireTime < $time) {
            $this->json(FrontConst::OPER_STATUS_FAIL_ALERT,'已经过期不能进行赠送！');
            return;
        }

        $giftTheme = array(//这些数据暂时是不可修改的，后台有上传功能，直接从数据库取
            //theme字段是暂时定义的，到时候在后台上传赠送主题，则不需要，直接使用背景图链接即可

            array('theme_id' => 1, 'theme' => base_url().'public/soma/images/gift_send_theme/selected.jpg', 'theme_name' => '精选'),
            array('theme_id' => 2, 'theme' => base_url().'public/soma/images/gift_send_theme/relatives.jpg', 'theme_name' => '亲友'),
        );

        //todo  把不用的代码直接去掉把
        $this->load->helper('soma/package');
        $returnData = array(
//             'item_ids'  => array_values($item_ids), //资产ID数组
//            'item_model'        => $this->assetItemModel,
            'is_expire' => $is_expire, //是否已经过期
//          'is_group'  => $is_group, //是否群发
            'gift_theme' => $giftTheme, //主题
            'order_list_url'    => Soma_const_url::inst()->get_soma_order_list(array('id'=>$this->inter_id))
//           'check_follow_ajax' => $check_follow_ajax_url         //是否关注的ajax请求
        );

        foreach($items[0] as $key => $v){
            $returnData[$key] = $v;
        }

        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $returnData);
    }


    /**
     * @SWG\Post(
     *     tags={"present"},
     *     path="/presents/send_out",
     *     summary="礼物打包请求提交",
     *     description="return item info",
     *     operationId="post_send_out",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="公众号ID",
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="礼物资产ID与对应数量",
     *         in="query",
     *         name="aiids",
     *         required=true,
     *         type="array",
     *         @SWG\Items(
     *              type="array",
     *              @SWG\Property(
     *                  property="aiid",
     *                  type="string" ,
     *                  description="单品id"
     *              ),
     *              @SWG\Property(
     *                  property="qty",
     *                  type="string" ,
     *                  description="赠送数量"
     *              )
     *        )
     *     ),
     *     @SWG\Parameter(
     *         description="祝福语",
     *         in="query",
     *         name="msg",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="主题ID",
     *         in="query",
     *         name="tid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="收礼人数",
     *         in="query",
     *         name="count_give",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="发出礼盒数，每人多少盒",
     *         in="query",
     *         name="per_give",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="是否是群发,1私人对私人 2群发礼物",
     *         in="query",
     *         name="is_group",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="发出礼盒数，每人多少盒",
     *         in="query",
     *         name="per_give",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="1表示来自礼物",
     *         in="query",
     *         name = "send_from",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="礼物源的订单号",
     *         in="query",
     *         name = "send_order_id",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="gift",
     *                  description="礼物id",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="sign",
     *                  description="签名加密字符串",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="dec",
     *                  description="祝福语",
     *                  type = "string",
     *              )
     *         )
     *     )
     * )
     */
    public function post_send_out()
    {

        $request_param = $this->input->input_json();
        $request_array = $request_param->toArray();

        $inter_id = $this->inter_id;
        $business = 'package';
        $aiidsArr = $request_array['aiids']; //asset_item的需求量json数组,required
        $message = $request_array['msg']; //祝福语
        $theme_id = $request_array['tid']; //礼物主题ID ,required
        $count_give = (int)$request_array['count_give']; //收礼人数   ,required
//        $is_group = $request_array['is_group']; //是否是群发
        $is_group =  $count_give > 1 ? Soma_base::STATUS_TRUE : Soma_base::STATUS_FALSE;   //是否是群发,人数大于1就是
        $per_give = (int)$request_array['per_give']; //发出礼盒数    ,required

        $total_qty = $per_give * $count_give;

        $send_from = isset($request_array['send_from']) ? $request_array['send_from'] : '';
        $send_order_id = isset($request_array['send_order_id']) ? $request_array['send_order_id'] : '';

        if (!empty($aiidsArr)) {
            foreach ($aiidsArr as $v) {
                $aiids[$v['aiid']] = $v['qty'];
            }
        }
        $item_ids = array_keys($aiids);
        $this->load->model('soma/Asset_item_package_model', 'assetItemModel');
        $items = $this->assetItemModel->get_order_items_byItemids($item_ids, $business, $inter_id);

        if (!$items || $items[0]['qty'] < $total_qty) {
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '您的礼品数量不足！', array());

            return;
        }
        foreach ($items as $k => $v) {
            if ($v['can_gift'] == Asset_item_package_model::STATUS_CAN_NO) {
                $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, $v['name'] . '不允许赠送', array());

                return;
            } else {
                if (in_array($v['item_id'], $item_ids)) {
                    $items[$k]['qty_require'] = $aiids[$v['item_id']];
                }
            }
        }

        $this->load->model('soma/Gift_order', 'giftOrderModel');
        $this->giftOrderModel->is_p2p = ($is_group == Soma_base::STATUS_TRUE) ? Soma_base::STATUS_FALSE : Soma_base::STATUS_TRUE;
        $this->giftOrderModel->sender = new Gift_order_attr_customer($this->openid);
        $this->giftOrderModel->rule = new Gift_order_attr_rule($per_give, $count_give);
        $this->giftOrderModel->theme = new Gift_order_attr_theme($theme_id, $message);
        $this->giftOrderModel->item = $items;

        $this->giftOrderModel->send_from = $send_from;
        $this->giftOrderModel->send_order_id = $send_order_id;

        $result = $this->giftOrderModel->order_save($business, $this->inter_id);

        if ($result['status'] == Soma_base::STATUS_TRUE && isset($result['gift_id'])) {
            $gift_id = $result['gift_id'];
            if ($gift_id) {
                if ($is_group == Soma_base::STATUS_TRUE) {
                    $sendResult = $this->giftOrderModel->set_redis_list($inter_id, $gift_id);
                } else {
                    $sendResult = true;
                }
                $sign = Soma_base::inst()->str_encrypt($gift_id, true);
                $returnData['gift'] = $gift_id;
                $returnData['sign'] = $sign;
                $returnData['desc'] = $message;

                /*分享所需参数打包*/
                $redirect= urlencode( Soma_const_url::inst()->get_soma_gift_list()."?id=".$this->inter_id);
                $redirect_url = Soma_const_url::inst()->get_soma_gift_send( array('id'=> $this->inter_id, 'redirect'=> $redirect ,'gid'=> $gift_id ,'sign' => $sign) );
                $params= array(
                    'id'=> $this->inter_id,
                    'bsn'=> $business,
                );
                $fans= $this->Publics_model->get_wxuser_info($this->inter_id, $this->openid);

                $nickname= empty($fans['nickname'])? '': $fans['nickname'];
                $send_link= Soma_const_url::inst()->get_url( '*/*/package_received', $params );
                $this->load->helper('soma/package');
                $share_img = base_url('public/soma/images/gift_box.png');
                $share_config = array(
                    'title'=> "亲，{$nickname}送您一份小礼物",//"亲，{$nickname}送您一份小礼物",
                    'desc'=>  $this->lang->line('pay_success_tip'),
                    'link'=> $send_link,
                    'img_url'=>  $share_img,
                    'redirect_url'  => $redirect_url
                );

                $returnData['wx_config'] =  $share_config;
                $message = $sendResult ? '赠礼打包成功' : '您群发的礼物暂时无法领取，' . Gift_order_model::EXPIRED_HOURS . '小时候将自动退回';
                $this->out_put_msg(FrontConst::OPER_STATUS_SUCCESS, $message, $returnData);

                return;
            } else {
                $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '赠送失败，请重试');
            }

        } else {

            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '赠送失败，请重试');
        }

    }

    /**
     * 我的礼物
     *
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/mine_list",
     *     summary="我收到的礼物列表",
     *     description="return  my received gifts list",
     *     operationId="get_mine_list",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="inter_id",
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="gift_info",
     *                  description="订单信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGiftOrder"),
     *              )
     *         )
     *     )
     * )
     */
    public function get_mine_list(){
        $business= 'package';
        $inter_id= $this->inter_id;
        $openid= $this->openid;

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');

        //私人对私人赠送单
        $gift_list = $this->giftOrderModel->get_order_list($business, $inter_id, array('is_p2p' => Soma_base::STATUS_TRUE, 'openid_received' => $openid), 'gift_id desc');


        $rec_list = $this->giftOrderModel->get_receiver_list_byOpenId($inter_id, $openid);
        $gift_ids = $this->giftOrderModel->array_to_hash($rec_list, 'gift_id');
        $rec_gift_list = array();
        if (count($gift_ids) > 0) {
            //叠加群发接收的订单，得到最终的接受列表

            //资产细单model luguihong 20160907 添加这个目的，准确查找到对应的资产
            $this->load->model('soma/Asset_item_' . $business . '_model', 'AssetItemModel');
            $AssetItemModel = $this->AssetItemModel;
            $assetItems = $AssetItemModel->get_order_items_byGiftids($gift_ids, $business, $inter_id);
            $assetItems = $this->giftOrderModel->filter_items_by_openid($assetItems, $openid);

            $rec_gift_list = $this->giftOrderModel->get_order_list_byIds('package', $inter_id, $gift_ids, array('is_p2p' => Soma_base::STATUS_FALSE), 'gift_id desc');//这里查找到的资产不准确
            foreach ($assetItems as $k => $v) {
                if (in_array($v['gift_id'], $gift_ids)) {
                    $rec_gift_list[$v['gift_id']]['items'][0] = $v;
                }
            }
            $gift_list += $rec_gift_list;
            //统计各个群发订单的接受情况
            //$all_ids= $this->giftOrderModel->array_to_hash($rec_gift_list, 'gift_id');
        }

        //键值做排序，由高到低
        krsort($gift_list);

        //所有群发接受清单
        //$all_receiver= $this->giftOrderModel->get_receiver_list($inter_id, NULL, array('gift_id'=> $all_ids) );

        //进行群发数组组装
        foreach ($rec_gift_list as $k => $v) {
            if (array_key_exists($v['gift_id'], $gift_list)) {
                $gift_list[$v['gift_id']]['receivers'][] = $v;
            }
        }

        if (count($gift_list) > 0) {
            //消费信息
            $this->load->model('soma/Asset_item_package_model');
            $asset_item = $this->Asset_item_package_model->get_order_items_by_filter($inter_id, array('openid' => $openid, 'gift_id' => array_keys($gift_list),));
            $consum_hash = $this->Asset_item_package_model->array_to_hash($asset_item, 'qty', 'gift_id');

            //循环提取必要信息
            $openids = array();
            foreach ($gift_list as $k => $v) {
                $openids[] = $v['openid_give'];
            }
            $openid_data = $this->Publics_model->get_fans_info_byIds($openids);
            $openid_hash = $this->giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');

            $status = $this->giftOrderModel->get_status_label();
            //填充必要字段信息
            foreach ($gift_list as $k => $v) {
                if (array_key_exists($v['status'], $status)) {
                    $gift_list[$k]['status_label'] = $status[$v['status']];
                }

                //填充openid昵称
                if (array_key_exists($v['openid_give'], $openid_hash)) {
                    $gift_list[$k]['openid_nickname'] = $openid_hash[$v['openid_give']];
                }

                //填充消费情况
                if (array_key_exists($v['gift_id'], $consum_hash)) {
                    $gift_list[$k]['consum_qty'] = $consum_hash[$v['gift_id']];
                } else {
                    $gift_list[$k]['consum_qty'] = 0;
                }
            }
        }


        $returnData = array();
        if (!empty($gift_list)) {
            foreach ($gift_list as $giftKey => $singleGift) {
                if (!empty($singleGift['items'])) {
                    $singleItem =  $singleGift['items'][0];
                    $singleItem = new \App\libraries\Support\Collection($singleItem);
                    $singleItem = $singleItem->except($this->itemFiledFilter);
                    $gift_list[$giftKey] = array_merge($gift_list[$giftKey],$singleItem->toArray()) ;
                    unset($gift_list[$giftKey]['items']);
                }
                $returnData['gift_info'][] = $gift_list[$giftKey];
            }
        }


        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $returnData);
    }

    /**
     * 我送出的礼物
     *
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/sent_list",
     *     summary="我送出的礼物列表",
     *     description="我送出的礼物列表",
     *     operationId="get_sent_list",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="公众号ID",
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="gift_info",
     *                  description="订单信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGiftOrder"),
     *              )
     *         )
     *     )
     * )
     */
    public function get_sent_list(){
        $business= 'package';
        $inter_id= $this->inter_id;
        $openid= $this->openid;


        $this->load->model('soma/Gift_order_model', 'giftOrderModel');

        //我送出的订单
        $gift_list= $this->giftOrderModel->get_order_list($business, $inter_id, array( 'openid_give'=>$openid ), 'gift_id desc' );

        $gift_ids= $this->giftOrderModel->array_to_hash($gift_list, 'gift_id');
        if( count($gift_ids)>0 ){
            //所有群发接受清单
            $all_receiver= $this->giftOrderModel->get_receiver_list($inter_id, NULL, array('gift_id'=> $gift_ids) );
        } else {
            $all_receiver= array();
        }

        //领取信息组装，注意有些群发订单是没有接收人的
        foreach ($all_receiver as $k=>$v){
            if( array_key_exists($v['gift_id'], $gift_list) ){
                $gift_list[$v['gift_id']]['receivers'][]= $v;
            }
        }

        $status= $this->giftOrderModel->get_status_label();
        //填充必要字段信息
        foreach ($gift_list as $k=>$v){
            if( array_key_exists($v['status'], $status) )
                $gift_list[$k]['status_label']= $status[$v['status']];
        }

//        $returnData['recevie_status'] = $this->giftOrderModel->can_recevie_status();

        if (!empty($gift_list)) {
            foreach ($gift_list as $giftKey => $singleGift) {
                if (!empty($singleGift['items'])) {
                    $singleItem =  $singleGift['items'][0];
                    $singleItem = new \App\libraries\Support\Collection($singleItem);
                    $singleItem = $singleItem->except($this->itemFiledFilter);
                    $gift_list[$giftKey] = array_merge($gift_list[$giftKey],$singleItem->toArray()) ;
                    unset($gift_list[$giftKey]['items']);
                }
                $returnData['gift_info'][] = $gift_list[$giftKey];
            }
        }


        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $returnData);
    }

    /**
     * 接受赠送
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function get_package_received(){

    }


    /**
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/received_list",
     *     summary="领取详情",
     *     description="指定礼物订单下的领取详情",
     *     operationId="get_received_list",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="公众号ID",
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *      @SWG\Parameter(
     *         description="对应赠送礼物id",
     *         in="query",
     *         name="gid",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="gift_info",
     *                  description="订单信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGift"),
     *              ),
     *              @SWG\Property(
     *                  property="users",
     *                  description="收礼人列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGiftUser")
     *              ),
     *              @SWG\Property(
     *                  property="gift_order_id",
     *                  description="赠送单ID",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="gift_status",
     *                  description="赠送状态,1礼物打包中，2可以赠送，3已被领取，4领取超时，5开始有人领取第一份礼物",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="theme_id",
     *                  description="对应主题ID",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="theme_keyword",
     *                  description="对应主题键值",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="total",
     *                  description="总份数",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="get_count",
     *                  description="已领取人数",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="can_repeat",
     *                  description="是否可以继续赠送，1可以，2不可以",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  description="祝福语",
     *                  type = "string",
     *              )
     *         )
     *     )
     * )
     */
    public function get_received_list(){

        $business = 'package';
        $gift_id = $this->input->get('gid');
        $inter_id = $this->inter_id;

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $giftOrderModel = $this->giftOrderModel->load( $gift_id );
        //能否再次赠送
        $can_receive_repeat = FALSE;
        if( in_array( $giftOrderModel->m_get('status'), $giftOrderModel->can_recevie_status() ) ){
            $can_receive_repeat = TRUE;
        }

        $filter = array();
        $filter['openid_give'] = $this->openid;
        $orders = $giftOrderModel->get_order_detail($business, $inter_id);

        $receiveOrders= $giftOrderModel->get_receiver_list($inter_id, $gift_id, $filter );
        // 已经分完了，不能再重复发送了
        if(count($receiveOrders) == $giftOrderModel->m_get('count_give')) {
            $can_receive_repeat = FALSE;
        }

//        $openids = array();
//        //数据格式整理
//        if($orders['is_p2p'] == $giftOrderModel::GIFT_TYPE_P2P){      //对私
//            $openids= !empty($orders['openid_received']) ? array( $orders['openid_received'] ) : array();
//        }elseif($orders['is_p2p'] == $giftOrderModel::GIFT_TYPE_GROUP){  //群发
//            foreach ($receiveOrders as $k=>$v){
//                $openids[]= $v['openid'];
//            }
//        }
//        $openid_data= !empty($openids)?$this->Publics_model->get_fans_info_byIds($openids):array();
//        $openid_hash= $this->giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
//        $headimg_hash= $this->giftOrderModel->array_to_hash($openid_data, 'headimgurl', 'openid');
//
//
//        $receiveUsersArr = array();
//        /**接收人**/
//        //群发
//        foreach ($receiveOrders as $k=>$v){
//            //填充openid昵称
//            if( array_key_exists($v['openid'], $openid_hash ))
//                $receiveOrders[$k]['openid_nickname']= $openid_hash[$v['openid']];
//            if( array_key_exists($v['openid'], $headimg_hash ))
//                $receiveOrders[$k]['openid_headimg']= $headimg_hash[$v['openid']];
//
//            $receiveOrders[$k]['get_time'] = date("m-d H:i",strtotime($receiveOrders[$k]['get_time']));
//            $receiveUser = new \App\libraries\Support\Collection($receiveOrders[$k]);
//            $receiveUser  = $receiveUser->except($receiveUserFiled);
//            $receiveUsersArr[] = $receiveUser->toArray();
//
//        }
//        //私发
//        if( array_key_exists($orders['openid_received'], $openid_hash ) ){
//            $receiveUser['open_id'] =  $orders['openid_received'];
//            $receiveUser['get_qty'] =  $orders['total_qty'];
//            $receiveUser['openid_nickname']= $openid_hash[$orders['openid_received']];
//            $receiveUser['openid_headimg']= $headimg_hash[$orders['openid_received']];
//            $receiveUser['get_time'] =  date("m-d H:i",strtotime($orders['update_time']));;
//            $receiveUsersArr[] = $receiveUser;
//        }
//        /**接收人end**/

        $receiveUsersArr = \App\services\soma\PresentsService::get_gift_received_users($this->inter_id,$gift_id,$orders['openid_give'],$orders);

        $status= $this->giftOrderModel->get_status_label();
        if( array_key_exists($orders['status'], $status ) )
            $orders['status_label']= $status[$orders['status']];

        $returnData = array();


        $themeArr = $this->get_theme();
        foreach($themeArr as $singleTheme){
            if($singleTheme['theme_id'] == $orders['theme_id']){
                $returnData['theme_keyword'] = $singleTheme['keyword']; //主题
                break;
            }
        }

        $item = new \App\libraries\Support\Collection($orders['items'][0]) ;
        $returnData['item'] = $item->except($this->itemFiledFilter)->toArray(); //礼物详情
        $returnData['users'] =   $receiveUsersArr;         //领取人详情
        $returnData['gift_order_id'] = $gift_id;         //赠送订单ID
        $returnData['gift_status'] = $orders['status'];  //订单状态
        $returnData['theme_id'] = $orders['theme_id']; //主题
        $returnData['total'] = $orders['count_give']; //赠送总人数
        $returnData['get_count'] = count($receiveUsersArr) ; //已经领取人数
        $returnData['message'] =  $orders['message'] ; //祝福语
        $returnData['can_repeat'] = ($can_receive_repeat) ? Soma_base::STATUS_TRUE : Soma_base::STATUS_FALSE; //是否可以继续赠送
        $this->json(FrontConst::OPER_STATUS_SUCCESS, '', $returnData);



    }



    /**
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/gift_return_jump",
     *     summary="获取回退礼物详情的超链接",
     *     description="获取回退礼物详情的超链接",
     *     operationId="post_gift_return_jump",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="赠送礼物的id",
     *         in="query",
     *         name="gid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="redirect_url",
     *                  description="跳转超链接",
     *                  type = "string",
     *              )
     *         )
     *     )
     * )
     */
    public function post_gift_return_jump(){
        $request_param = $this->input->input_json();
        $request_array = $request_param->toArray();
        $gid = $request_array['gid'];

        $this->load->model('soma/Gift_order_model', 'g_model');
        $this->g_model->load($gid);
        if(!$this->g_model) {
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '没有找到礼品信息');
            return ;
        }

        $send_from = $this->g_model->m_get('send_from');
        $send_order_id = $this->g_model->m_get('send_order_id');
        $business = $this->g_model->m_get('business');

        // 默认跳转到订单中心
        $redirect_url = Soma_const_url::inst()->get_url('*/order/order_detail', array('id' => $this->inter_id, 'oid'=> $send_order_id, 'bsn' => $business));
        if($send_from == Gift_order_model::SEND_FROM_GIFT) {
            $redirect_url = Soma_const_url::inst()->get_url('*/gift/package_received', array('id' => $this->inter_id, 'gid'=> $send_order_id, 'sign' => ''));
        }

        $this->out_put_msg(FrontConst::OPER_STATUS_SUCCESS, '',array( 'redirect_url' => $redirect_url));
        return ;

    }


    public function get_theme(){


        $gift_theme = array(//这些数据暂时是不可修改的，后台有上传功能，直接从数据库取
            //theme字段是暂时定义的，到时候在后台上传赠送主题，则不需要，直接使用背景图链接即可

            array('theme_id' => 1, 'theme' => base_url().'public/soma/images/gift_send_theme/selected.jpg', 'theme_name' => '精选','keyword'=>'selected'),
            array('theme_id' => 2, 'theme' => base_url().'public/soma/images/gift_send_theme/relatives.jpg', 'theme_name' => '亲友','keyword'=>'relatives'),
        );

        return $gift_theme;
    }


    /**
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/gift_send_success",
     *     summary="转赠成功后的页面数据",
     *     description="转赠成功后的页面数据",
     *     operationId="get_gift_send_success",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="赠送礼物的id",
     *         in="query",
     *         name="gid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="product_detail_url",
     *                  description="再次购买的URL地址",
     *                  type = "string"
     *              ),
     *              @SWG\Property(
     *                  property="order_list_url",
     *                  description="订单中心URL地址",
     *                  type = "string",
     *              )
     *         )
     *     )
     * )
     */
    public function get_gift_send_success(){
        $gid = $this->input->get('gid');
        $business = 'package';

        $returnData = array();

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $giftOrderModel = $this->giftOrderModel->load( $gid );
        if(empty($giftOrderModel)){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '没有找到礼品信息');
            return ;
        }

        $orders = $giftOrderModel->get_order_detail($business, $this->inter_id);
        if(empty($orders)){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '没有找到礼品信息');
            return ;
        }else{
            $item = $orders['items'][0];
            $product_id = $item['product_id'];
            $returnData['product_detail_url'] = Soma_const_url::inst ()->get_package_detail(array('id'=>$this->inter_id,'pid'=>$product_id));
            $returnData['order_list_url'] = Soma_const_url::inst ()->get_soma_order_list(array('id'=>$this->inter_id));
            $this->out_put_msg(FrontConst::OPER_STATUS_SUCCESS, '',$returnData);
            return ;
        }

    }


    /**
     * @author zhangyi  <zhangyi@mofly.cn>
     * 失效赠送订单详情
     */
    /**
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/invalidate_gift_order",
     *     summary="home products",
     *     description="获取一个已经失效的订单",
     *     operationId="get_invalidate_gift_order",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="礼物订单ID",
     *         in="query",
     *         name="gid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="item",
     *                  description="礼物信息",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGift"),
     *              ),
     *              @SWG\Property(
     *                  property="users",
     *                  description="收礼人列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGiftUser")
     *              ),
     *              @SWG\Property(
     *                  property="gift_order_id",
     *                  description="赠送单ID",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="gift_status",
     *                  description="赠送状态,1礼物打包中，2可以赠送，3已被领取，4领取超时，5开始有人领取第一份礼物",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="theme_id",
     *                  description="对应主题ID",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="theme_keyword",
     *                  description="对应主题键值",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="total",
     *                  description="总份数",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="get_count",
     *                  description="已领取人数",
     *                  type = "string",
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  description="祝福语",
     *                  type = "string",
     *              )
     *         )
     *     )
     * )
     */
    public function get_invalidate_gift_order(){
        $gift_id = $this->input->get('gid');
        $business = 'package';
        $returnData = array();

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $gift_array= $this->giftOrderModel->get_data_filter( array('gift_id'=>$gift_id ) );
        $gift_received_status = $this->giftOrderModel->can_recevie_status();//可以接受礼物的状态

        if(empty($gift_array)){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '没有找到礼品信息',$returnData);
            return ;
        }

        $orders = $gift_array[0];

        if( in_array( $orders['status'], $gift_received_status ) ){  //礼物还可以接收赠送，地址有误
            $redirect_url = Soma_const_url::inst()->get_url('*/gift/package_received', array('id' => $this->inter_id, 'gid'=> $orders['order_id'], 'sign' => ''));
            $returnData['redirect'] = $redirect_url;
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_TOAST, '',$returnData);
            return ;
        }

        $items = $this->giftOrderModel->load( $gift_id )->get_order_items($business, $this->inter_id);
        $item = $items[0];
        $receiveUsersArr = \App\services\soma\PresentsService::get_gift_received_users($this->inter_id,$gift_id,$orders['openid_give'],$orders);

        $item = new \App\libraries\Support\Collection($item) ;
        $returnData['item'] = $item->except($this->itemFiledFilter)->toArray(); //礼物详情
        $returnData['users'] =   $receiveUsersArr;         //领取人详情
        $returnData['gift_order_id'] = $gift_id;         //赠送订单ID
        $returnData['gift_status'] = $orders['status'];  //订单状态
        $returnData['theme_id'] = $orders['theme_id']; //主题
        $returnData['total'] = $orders['count_give']; //赠送总人数
        $returnData['get_count'] = count($receiveUsersArr) ; //已经领取人数
        $returnData['message'] =  $orders['message'] ; //祝福语

        $themeArr = $this->get_theme();
        foreach($themeArr as $singleTheme){
            if($singleTheme['theme_id'] == $orders['theme_id']){
                $returnData['theme_keyword'] = $singleTheme['keyword']; //主题
                break;
            }
        }

        $this->out_put_msg(FrontConst::OPER_STATUS_SUCCESS, '',$returnData);
        return ;





    }


    /**
     * @author zhangyi  <zhangyi@mofly.cn>
     * *有效赠送订单详情,可以领取
     */

    /**
     * @SWG\Get(
     *     tags={"present"},
     *     path="/presents/validate_gift_order",
     *     summary="获取可以领取的订单详情",
     *     description="获取可以领取的订单详情",
     *     operationId="get_validate_gift_order",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="资产ID",
     *         in="query",
     *         name="aiid",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="items",
     *                  description="资产列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGift")
     *              ),
     *              @SWG\Property(
     *                  property="giftTheme",
     *                  description="主题列表",
     *                  type = "array",
     *                  @SWG\Items(ref="#/definitions/SomaGiftTheme")
     *              ),
     *         )
     *     )
     * )
     */
    public function get_validate_gift_order(){
        //gid
        //grd
        $business = 'package';
        $gift_id = $this->input->get('gid');
        $returnData = array();

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $giftOrderModel = $this->giftOrderModel;
        $gift_array= $giftOrderModel->get_data_filter( array('gift_id'=>$gift_id ) );
        $gift_received_status = $giftOrderModel->can_recevie_status();//可以接受礼物的状态

        if(empty($gift_array)){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '没有找到礼品信息',$returnData);
            return ;
        }
        $orders = $gift_array[0];
        if( !in_array( $orders['status'], $gift_received_status ) ){  //礼物还不可以接收赠送
            switch($orders['status']){
                case 4:
                    $message = '领取超时，礼物已被回收';
                    break;
                case 3:
                    $message = '礼物被领取';
                    break;
                default:
                    $message = '领取失败';
                    break;
            }
            $url= Soma_const_url::inst()->get_url('*/*/received_gift_empty', array('id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business ) );
            $returnData['redirect_url'] = $url;
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, $message,$returnData);
            return ;
        }

        $business= $orders['business'];
        $sign= Soma_base::inst()->str_decrypt($this->input->get('sign'), TRUE);
        if( $gift_id != $sign ){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, $this->lang->line('share_link_error_tip'),$returnData);
            return;
        }
        $items = $giftOrderModel->load( $gift_id )->get_order_items($business, $this->inter_id);
        $item = $items[0];
        $itemFiledFilter = ['parent_id', 'parent_id', 'hotel_id', 'openid_origin', 'type', 'sku', 'conn_devices', 'name_en', 'card_id', 'compose', 'transparent_img', 'compose_en', 'use_cnt', 'can_split_use', 'can_wx_booking', 'wx_booking_config', 'can_refund', 'can_mail', 'can_gift', 'can_pickup', 'can_sms_notify', 'can_invoice', 'can_reserve', 'is_hide_reserve_date', 'room_id', 'add_time', 'send_wxtemp_status'];
        $item = new \App\libraries\Support\Collection($item);  //collect collect($item)
        $item->toArray();
        $item = $item->except($itemFiledFilter)->toArray();
        $returnData['item'] = $item ;
        $returnData['fans'] = $this->Publics_model->get_fans_info( $orders['openid_give'] );
        $this->out_put_msg(FrontConst::OPER_STATUS_SUCCESS, '',$returnData);
        return;
    }


    //接收礼物处理
    public function post_receive_process(){
        //gid  required
        //bsn
        //sign
        $request_param = $this->input->input_json();
        $request_array = $request_param->toArray();

        $business = (!isset( $request_array['bsn']) || empty( $request_array['bsn'])) ? 'package':  $request_array['bsn'];
        $gift_id = $request_array['gid'];
        $sign = $request_array['sign'];

        $returnData = array();

        $this->load->model('soma/Gift_order_model', 'giftOrderModel');
        $giftOrderModel = $this->giftOrderModel;
        $gift_array= $giftOrderModel->get_data_filter( array('gift_id'=>$gift_id ) );
        $gift_received_status = $giftOrderModel->can_recevie_status();//可以接受礼物的状态

        if(empty($gift_array)){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '没有找到礼品信息',$returnData);
            return ;
        }
        $orders = $gift_array[0];
        if( !in_array( $orders['status'], $gift_received_status ) ){  //礼物还不可以接收赠送
            switch($orders['status']){
                case 5:
                    $message = '领取超时，礼物已被回收';
                    break;
                case 4:
                    $message = '领取超时';
                    break;
                default:
                    $message = '领取失败';
                    break;
            }
            $url= Soma_const_url::inst()->get_url('*/*/received_gift_empty', array('id'=> $this->inter_id,'gid'=>$gift_id,'bsn'=>$business ) );
            $returnData['redirect_url'] = $url;
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, $message,$returnData);
            return ;
        }

        $business= $orders['business'];
        $sign= Soma_base::inst()->str_decrypt($sign, TRUE);
        if( $gift_id != $sign ){
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '接受分享链接签名错误',$returnData);
            return;
        }

        //接受礼物处理
        $validation_of_order = \App\services\soma\PresentsService::validation_of_received_status( $this->inter_id , $gift_id, $orders);
        if(!$validation_of_order){  //领取人数已超
            $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_ALERT, '对不起，你手慢了，礼物已经被领完了',$returnData);
            return;
        }else{
            $giftOrderModel = $giftOrderModel->load($gift_id);
            $gift_requirement= $giftOrderModel->get_requirement($business, $this->inter_id);
            $this->load->model('soma/Asset_item_package_model', 'somaAssetItemModel');
            $somaAssetItemModel = $this->somaAssetItemModel;
            $items= $somaAssetItemModel->get_order_items_byItemids( array_keys($gift_requirement), $business, $this->inter_id);
            $orderIds = array();
            foreach ($items as $k=>$v){
                if( array_key_exists($v['item_id'], $gift_requirement) ) {
                    $items[$k]['qty_require']= $gift_requirement[$v['item_id']];
                    $orderIds[] = $v['order_id'];
                }
            }
            $giftOrderModel->received_item= $items;

            if( $orders['is_p2p']== Soma_base::STATUS_FALSE ) {
                //$per_give= $token['qty'];  //群发中的数量
                $per_give= $orders['per_give'];  //群发中的数量
                $count_give= $orders['count_give'];  //群发中的数量
                $giftOrderModel->rule= new Gift_order_attr_rule($per_give, $count_give);
            }
            $giftOrderModel->sender= new Gift_order_attr_customer( $orders['openid_give'] );
            $giftOrderModel->received= new Gift_order_attr_customer( $this->openid );
            $received_result = $giftOrderModel->order_received($business, $this->inter_id);

            /***********************发送模版消息****************************/
            //发送模版消息
            $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

            $type = $MessageWxtempTemplateModel::TEMPLATE_GIFT_RECEIVED;// 礼物被领取
            $openid = $giftOrderModel->m_get('openid_give');
            $fans_received= $this->Publics_model->get_fans_info( $this->openid );
            $giftOrderModel->nickname = isset( $fans_received['nickname'] ) ? $fans_received['nickname'] : $this->lang->line('your_friends'); // 领取人昵称
            $inter_id = $this->inter_id;//公众号
            $business = 'package';

            $MessageWxtempTemplateModel->send_template_by_gift_success( $type, $giftOrderModel, $openid, $inter_id, $business);
            /***********************发送模版消息****************************/

            //判断赠送人是否为购买人，是购买人那么赠送人肯定不是二次赠送的
            if( isset( $items[0] ) && empty( $items[0]['gift_id'] ) ){
                /**
                 * 接收成功赠送会员礼包，只限购买人。要判断赠送人是不是购买人，只能通过接收人资产里面的parent_id是不是等于购买人的资产ID
                 * @author      luguihong    2017/02/23
                 * @param       array        items              赠送人的资产明细
                 */
                $this->load->model('soma/Config_member_package_model','somaConfigMemberModel');
                $somaConfigMemberModel = $this->somaConfigMemberModel;
                $memberRecordData[] = array(
                    'inter_id'      => $inter_id,
                    'openid'        => $items[0]['openid'],
                    'send_id'       => $gift_id,
                    'product_id'    => $items[0]['product_id'],
                    'num'           => $orders['per_give'],
                    'type'          => $somaConfigMemberModel::TYPE_RECEIVED_SUCCESS,
                    'create_time'   => date('Y-m-d H:i:s'),
                    'status'        => $somaConfigMemberModel::RECORD_STATUS_PENDING,
                );
                $somaConfigMemberModel->insert_record( $giftOrderModel, $inter_id, $memberRecordData );

            }

            if( $received_result ){
                //这里为0的意思是，已经接受了，数量减少在前，判断在后，所以为0
                $giftOrderModel->change_order_consumer_status( $giftOrderModel, $inter_id, $business, $orderIds, 0 );
                //处理完成把必要数据返回到前端
                $item = $items[0];
                //收礼成功后根据礼物的属性返回对应操作按钮地址
                //$returnData['btn'] = \App\services\soma\PresentsService::usage_btn_format($inter_id,$gift_id,$item,$business,$giftOrderModel);
                $returnData['btn'] = '';
                $item  = new \App\libraries\Support\Collection($item);
                $returnData['item'] = $item->except($this->itemFiledFilter)->toArray() ;      //过滤敏感字段


                /*关注验证*/
                $this->load->model('wx/Fans_model', 'fansModel');
                $subscribeStatus = $this->fansModel->subscribeStatus($this->inter_id, $this->openid);

                if (!$subscribeStatus) {
                    $returnData['subscribe']= Soma_base::STATUS_FALSE;
                    $returnData['qrc'] = WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE);
                }else{
                    $returnData['subscribe']= Soma_base::STATUS_TRUE;
                    $returnData['qrc'] = '';
                }


                $this->out_put_msg(FrontConst::OPER_STATUS_SUCCESS, '',$returnData);
                return;

            }else{
                $this->out_put_msg(FrontConst::OPER_STATUS_FAIL_TOAST, '领取失败');
                return;
            }


        }


    }
}