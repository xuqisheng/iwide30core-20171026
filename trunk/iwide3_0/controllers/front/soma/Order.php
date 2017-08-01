<?php
use App\libraries\Support\Log;
use App\services\Result;
use App\services\soma\KillsecService;
use App\services\soma\OrderService;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Order
 * @author renshuai  <renshuai@mofly.cn>
 *
 *
 * @property Sales_order_model $SalesOrderModel
 * @property Product_package_model $productPackageModel
 */
class Order extends MY_Front_Soma {

    /**
     *
     */
    const PACKAGE_ITEM = 'package';
    /**
     *
     */
    const GROUPON_ITEM = 'groupon';

    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->controllerLogHandler(__CLASS__);
    }
    /**
     * 订单列表
     */
    public function my_order_list(){

        $type = $this->input->get('t'); //分类
        $this->load->model('soma/Sales_order_model','SalesOrderModel');
        $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
        $SalesOrderModel = $this->SalesOrderModel;

        $filter = array(
            'openid' => $this->openid
        );
        $sort = 'order_id DESC';
        switch($type){
            case 1:
                $filter['is_payment'] = $SalesOrderModel::IS_PAYMENT_NOT; //等待支付
                $pageTitle = $this->lang->line('wait_payment');
                $orders = $SalesOrderModel->get_order_list(self::PACKAGE_ITEM,$this->inter_id,$filter,$sort);
                break;
            case 2:
                $filter['is_payment'] = $SalesOrderModel::IS_PAYMENT_YES; //已支付
                $filter['refund_status'] = $SalesOrderModel::REFUND_PENDING;
                $pageTitle = $this->lang->line('payment_done');
                $orders = $SalesOrderModel->get_order_list(self::PACKAGE_ITEM,$this->inter_id,$filter,$sort);
                break;
            case 3:
                $filter['refund_status'] = $SalesOrderModel::REFUND_ALL; //退款
                $pageTitle = $this->lang->line('refund_orders');
                $orders = $SalesOrderModel->get_order_list(self::PACKAGE_ITEM,$this->inter_id,$filter,$sort);
                break;
            default:
                // $filter = array('status'=>$SalesOrderModel->available_order(),'openid'=>$this->openid,'refund_status'=>$SalesOrderModel::REFUND_PENDING);
                $filter = array('status'=>$SalesOrderModel->available_order(),'openid'=>$this->openid);
                $pageTitle = $this->lang->line('all_orders');
                $orders = $SalesOrderModel->get_order_list_with_filter(self::PACKAGE_ITEM,$this->inter_id,$filter, $sort);
                break;
        }

        $_tmp = $orders;
        foreach ($_tmp as $id => $order) {
            if(isset($order['items'][0]['type'])
                && $order['items'][0]['type'] == Sales_order_model::PRODUCT_TYPE_SHIPPING) {
                unset($orders[$id]);
            }
        }

        if( defined('PROJECT_AREA') 
            && PROJECT_AREA=='mooncake' ){
            $pageTitle = '月饼说-' . $pageTitle;
        }

        $header = array(
            'title' => $pageTitle
        );

        $this->datas['activityGrouponModel'] = $this->activityGrouponModel;
        $this->datas['salesModel'] = $SalesOrderModel;
        $this->datas['orders'] = $orders;
        // $this->datas['order_status'] = $SalesOrderModel->get_status_label();
        $this->datas['order_status_key'] = $SalesOrderModel->get_status_label_lang_key();
        $this->datas['inter_id'] = $this->inter_id;
        $type = isset( $type ) ? $type : 1;
        $this->datas['type'] = $type;

        $js_menu_show = array( 'menuItem:copyUrl' );
        $this->datas['js_menu_show']= $js_menu_show;

        //购买商品
        $myOrdersUrl = Soma_const_url::inst()->get_soma_order_list(array('id'=>$this->inter_id));
        $this->datas['my_orders_url'] = $myOrdersUrl;
        //我的礼物
        $myGiftsUrl = Soma_const_url::inst()->get_my_gift_list(array('id'=>$this->inter_id));
        $this->datas['my_gifts_url'] = $myGiftsUrl;
        //邮寄商品
        $myMailsUrl = Soma_const_url::inst()->get_my_mail_list(array('id'=>$this->inter_id));
        $this->datas['my_mails_url'] = $myMailsUrl;

        // 双语翻译
        if($this->langDir == self::LANG_DIR_EN)
        {
            $en_orders = $this->datas['orders'];
            foreach($this->datas['orders'] as $ok => $cn_order)
            {
                $en_items = $cn_order['items'];
                foreach($cn_order['items'] as $ik => $cn_items)
                {
                    if(!empty($cn_items['name_en']))
                    {
                        $en_items[$ik]['name'] = $cn_items['name_en'];
                    }
                }
                $en_orders[$ok]['items'] = $en_items;
                if(!empty($cn_order['item_name_en']))
                {
                    $en_orders[$ok]['item_name'] = $cn_order['item_name_en'];
                }
            }
            $this->datas['orders'] = $en_orders;
        }
        
        $this->_view("header",$header);
        // $this->_view("order_list",$this->datas);
        $this->_view("my_order_list",$this->datas);
        // $this->_view("all_order",$this->datas);
//        $this->output->enable_profiler(true);

    }


    /**
     * 订单详情
     */
    public function order_detail()
    {
        $interId = $this->inter_id;
        $orderId = $this->input->get('oid');
        $business = $this->input->get('bsn'); //分类

        $this->load->model('soma/Sales_order_model','SalesOrderModel');
        $this->load->model('soma/product_package_model','ProductPackageModel');
        $this->load->model('soma/Consumer_order_model','ConsumerOrderModel');
        $this->load->model('soma/Consumer_item_package_model','ConsumerItemModel');
        $this->load->model('soma/Gift_order_model','GiftOrderModel');
        $this->load->model('soma/Gift_item_package_model','GiftItemModel');
        $this->load->helper('soma/package');

        /**
         * @var Sales_order_model $SalesOrderModel
         */
        $SalesOrderModel = $this->SalesOrderModel;
        $ConsumerOrderModel = $this->ConsumerOrderModel;
        $GiftOrderModel = $this->GiftOrderModel;
        $GiftItemModel = $this->GiftItemModel;

        $SalesOrderModel->business = $business;
        $salesOrder = $SalesOrderModel->load($orderId); //资产订单
        if( empty($salesOrder) ){
            redirect(Soma_const_url::inst()->get_url('soma/order/my_order_list', array('id' => $this->inter_id)));
        }
        $orderDetail = $SalesOrderModel->get_order_asset($business,$interId); //资产订单

        //筛选属于自己的资产订单
        $orderDetail['items'] = $SalesOrderModel->filter_items_by_openid( $orderDetail['items'], $this->openid );
        $openids = array();

        if($orderDetail['items']){
            //修改为送出的订单也一起展现，并展现收礼人昵称
            foreach($orderDetail['items'] as $k=> $v){
                if($v['openid']!= $this->openid || !empty($v['gift_id'])){
                    //unset($orderDetail['items'][$k]); //剔除赠送接收所得，避免自己送出去的又被送回来
                    $openids[]= $v['openid'];
                }
            }
            if( count($openids)>0 ){
                $openids = $this->Publics_model->get_fans_info_byIds($openids);
            }
            $openids = $SalesOrderModel->array_to_hash($openids, 'nickname', 'openid');
            //print_r($openids);die;    //array('Xgsehkages9g9se9g'=>'昵称', ...)

        } else {
            $orderDetail['items'] = array();
        }

        $this->load->model('soma/Consumer_shipping_model','ConsumerShippingModel');
        $ConsumerShippingModel = $this->ConsumerShippingModel;
        $can_mail_yes = $ConsumerShippingModel::CAN_MAIL_YES;
        $this->datas['can_mail_yes'] = $can_mail_yes;

        $consumerOrderDetail = array();
        $giftSendOrderDetail = array();
        foreach($orderDetail['items'] as $v){
            $consumerOrderDetail[] = $ConsumerOrderModel->consumer_order_item_list($v['item_id'],$business,$interId);

            $giftItems = $GiftItemModel->get_order_items_byAssetItemIds($v['item_id'],$business,$interId);
            $giftDetail = array();
            foreach ($giftItems as $sk => $sv) {
                $giftDetail = $GiftOrderModel->load($sv['gift_id'])->m_data();
                //luguihong 20170120 去掉超时退回的暂时不显示
                if( $giftDetail['status'] != $GiftOrderModel::STATUS_TIMEOUT ){
                    $giftDetail['items'][] = $sv;
                    $giftSendOrderDetail[] = $giftDetail;
                }
            }

        }
// var_dump( $giftSendOrderDetail );


        // 订单资产邮寄信息显示，by fengzhongcheng
        // var_dump($consumerOrderDetail);exit;
        $consumer_ids = array();
        foreach ($consumerOrderDetail as $k => $consumer_detail) {
            if(is_array($consumer_detail) && count($consumer_detail) >0 ) {
                foreach ($consumer_detail as $sk => $consumer) {
                    $consumer_ids[] = $consumer['consumer_id'];

                    $isBookingHotel = FALSE;
                    $consumerMethod = $ConsumerOrderModel->load( $consumer['consumer_id'] )->m_get('consumer_method');
                    if( $consumerMethod == $ConsumerOrderModel::CONSUME_HOTEL_SELF ){
                        $isBookingHotel = TRUE;
                    }
                    $consumerOrderDetail[$k][$sk]['is_booking_hotel'] = $isBookingHotel;
                }
            }
        }
        $shipping_detail = $ConsumerShippingModel->get_shipping_by_consumer_id($consumer_ids, $interId);
        $shipping_consumer_ids = array_keys($shipping_detail);
        $shipping_status_label = $ConsumerShippingModel->get_order_detail_status_label();
        $this->datas['shipping_detail'] = $shipping_detail;
        $this->datas['shipping_consumer_ids'] = $shipping_consumer_ids;
        $this->datas['shipping_status_label'] = $shipping_status_label;
        // var_dump($shipping_detail);exit;

        $gift_status = $GiftOrderModel->get_status_label();
        $this->datas['gift_status'] = $gift_status;
        $this->datas['giftSendOrderDetail'] = $giftSendOrderDetail;

//        $SalesOrderModel = $this->SalesOrderModel->load($orderId);
        //退款链接
        $can_refund = $salesOrder->can_refund_order();
        if($can_refund) {
            $can_refund = $salesOrder->m_get('can_refund');
        } else {
            $can_refund = $SalesOrderModel::CAN_REFUND_STATUS_FAIL;
        }

        //退款不能超过支付后7天
        $paymentTime = $salesOrder->m_get('payment_time');
        $isOverRefund = FALSE;//没有超过7天
        if( $paymentTime ){
            $overTime = strtotime( $paymentTime ) + 7*24*60*60;
            $nowTime = time();
            if( $overTime < $nowTime ){
                $isOverRefund = TRUE;//超过7天
            }
        }
        $this->datas['isOverRefund'] = $isOverRefund;

        //邮寄
        $can_mail = $salesOrder->can_mail_order();
        
        //是否是拼团
        $settlement = $salesOrder->m_get('settlement');
        if( $settlement == 'groupon' ){
            $isGroupOn = TRUE;
        }else{
            $isGroupOn = FALSE;
        }

        // 查询分销员信息
        $saler_id = $salesOrder->m_get('saler_id');
        $this->datas['saler_info_by_id'] = false;
        if (!empty($saler_id)) {
            $this->load->library('Soma/Api_idistribute');
            $this->datas['saler_info_by_id'] = $this->api_idistribute->getSalerInfoBySalerId($this->inter_id, $saler_id);
        }

        $param = array();
        $param['oid'] = $orderId;
        $param['bsn'] = $business;
        $refund_url = Soma_const_url::inst()->get_soma_refund_apply( $param );//退款
        $mail_url = Soma_const_url::inst()->get_soma_shipping( $param );//邮寄
        $product_record_url = Soma_const_url::inst()->get_url('*/*/photo_shot', $param);//交易快照

        $this->load->model('soma/Sales_order_product_record_model','RecordModel');
        $RecordModel = $this->RecordModel;
        $filter = array();
        $filter['openid'] = array( $this->openid );
        $filter['order_id'] = $orderId;
        $filter['status'] = Soma_base::STATUS_TRUE;
        $record_detail = $RecordModel->get_record_info( $filter, $interId );


        // 过滤开具发票按钮
        $invoice_enable = false;
        
        if($orderDetail['is_invoice'] == $SalesOrderModel::IS_INVOICE_NOT 
            && count($orderDetail['items']) > 0 ) {

            $payment_time = $orderDetail['payment_time'];
            $can_invoice_time = date('Y-m-d H:i:s', strtotime("+1 months", strtotime($payment_time)));
            $now_date_time = date('Y-m-d H:i:s');
            $item = $orderDetail['items'][0];

            if(isset( $item['can_invoice'] ) && $item['can_invoice'] == Soma_base::STATUS_TRUE
                && strtotime($now_date_time) < strtotime($can_invoice_time) ) {
                $invoice_enable = true;
            }

        }

        //获取推荐位
        $uri = 'soma_order_order_detail';
        $block = $this->get_page_block( $uri );
        $this->datas['block']= $block;

        // 需求不明，隐藏按钮，by FengZhongcheng 2016-07-25 18:16:53
        // $invoice_enable = false;

        //记录openid对应昵称一唯数组
        $this->datas['openids']= $openids;

        $this->datas['can_refund'] = $can_refund;
        $this->datas['can_mail'] = $can_mail;
        $this->datas['is_groupon'] = $isGroupOn;
        $this->datas['inter_id'] = $this->inter_id;
        $this->datas['business'] = $business;
        $this->datas['gift_model'] = $GiftOrderModel;

        $this->datas['consumer_status'] = $this->ConsumerItemModel->get_item_status_label();
        
        $ConsumerItemModel = $this->ConsumerItemModel;
        $this->datas['can_mail_status'] = $ConsumerItemModel::STATUS_ITEM_SHIPPING;
// var_dump( $this->datas['can_mail_status'] );
        $this->datas['orderDetail'] = $orderDetail;
        $this->datas['consumerDetail'] = $consumerOrderDetail;//消费单
        $this->datas['ProductPackageModel'] = $this->ProductPackageModel;
        $this->datas['SalesOrderModel'] = $this->SalesOrderModel;
        $this->datas['ConsumerShippingModel'] = $ConsumerShippingModel;
        $this->datas['refund_url'] = $refund_url;
        $this->datas['mail_url'] = $mail_url;
        $this->datas['product_record_url'] = $product_record_url;
        $this->datas['invoice_enable'] = $invoice_enable;
        $this->datas['record_detail'] = $record_detail;
//        print_r($orderDetail);exit;
//        return $orderDetail;
        
        // var_dump($this->datas['giftSendOrderDetail'], $this->datas['consumerDetail'],$this->datas['orderDetail']);exit;
        // 双语翻译
        if($this->langDir == self::LANG_DIR_EN)
        {
            $en_order_items = $this->datas['orderDetail']['items'];
            foreach($this->datas['orderDetail']['items'] as $key => $item)
            {
                if(!empty($item['name_en']))
                {
                    $en_order_items[$key]['name'] = $item['name_en'];
                }
            }
            $this->datas['orderDetail']['items'] = $en_order_items;

            foreach($this->datas['consumerDetail'] as $ak => $asset_items)
            {
                if(!empty($asset_items))
                {
                    foreach ($asset_items as $ik => $item)
                    {
                        if(!empty($item['name_en']))
                        {
                            $this->datas['consumerDetail'][$ak][$ik]['name'] = $item['name_en'];
                        }
                    }
                }
            }

            foreach($this->datas['giftSendOrderDetail'] as $ak => $asset_items)
            {
                $en_items = $asset_items['items'];
                foreach($asset_items['items'] as $ik => $item)
                {
                    if(!empty($item['name_en']))
                    {
                        $en_items[$ik]['name'] = $item['name_en'];
                    }
                }
                $this->datas['giftSendOrderDetail'][$ak]['items'] = $en_items;
            }
        }

        $header = array(
            'title'=> $this->lang->line('order_details')
        );
        $this->_view("header",$header);
        $this->_view("order_detail",$this->datas);

    }
    
    /**
     * 下单价格安全检测，带报警和警戒线拦截
     * @return boolean
     */
    protected function _order_price_safe_check($settlement, $productArr, $actDetail, $actModel )
    {
        $price_array= $name_array= array();
        foreach ($productArr as $v){
            $price_array[$v['product_id']]= $v['price_package'];
            $name_array[$v['product_id']]= $v['name'];
        }
        $public= $actDetail['inter_id'];//先用inter_id代替公众号
        $this->load->model('wx/Publics_model');
        $interInfo = $this->Publics_model->get_public_by_id( $public );
        $public = isset( $interInfo['name'] ) && !empty( $interInfo['name'] ) ? $interInfo['name'] : $public;
        
        foreach ($productArr as $k=>$v){

            switch ($settlement) {
                case 'killsec':
                    if( array_key_exists($v['product_id'], $price_array ) &&
                    $actDetail['killsec_price']< $price_array[$v['product_id']] * $actModel::PRICE_PERCENT_NOTICE ){
                        //低于该值发送模板消息给开发人员
                
                        $message= "秒杀价格异常：公众号【{$public}】，商品【{$name_array[$v['product_id']]}】"
                            . "原价 ¥{$price_array[$v['product_id']]}, 秒杀价 ¥{$actDetail['killsec_price']}, "
                            . "折扣【" 
                            . round( ($actDetail['killsec_price']/$price_array[$v['product_id']])*10, 5 ) 
                            . "】折, 购买openid【".$this->openid."】";
            
                        /***********************发送模版消息****************************/
                        //发送模版消息
                        $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
                        $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

                        $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;
                        $inter_id= 'a450089706';
                        $business= 'package';
                        $openid_arr = $MessageWxtempTemplateModel->get_notice_openids();
                        foreach ($openid_arr as $openid) {
                            $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, '', $openid, $inter_id, $business, $message, FALSE);
                        }
                        /***********************发送模版消息****************************/
                    }
                    if( array_key_exists($v['product_id'], $price_array ) &&
                        $actDetail['killsec_price']< $price_array[$v['product_id']] * $actModel::PRICE_PERCENT_LIMIT ){
            
                        $result['status'] = Soma_base::STATUS_FALSE;
                        $result['message'] = '活动价格设置错误，暂时不能购买，请见谅。';
                        $result['data'] =  array();
                        echo json_encode($result);die;
                    }
                    break;
            
                case 'groupon':
                    if( array_key_exists($v['product_id'], $price_array ) &&
                    $actDetail['group_price']< $price_array[$v['product_id']] * $actModel::PRICE_PERCENT_NOTICE ){
                        //低于该值发送模板消息给开发人员

                        $message= "拼团价格异常：公众号【{$public}】, 商品【{$name_array[$v['product_id']]}】"
                        . "原价 ¥{$price_array[$v['product_id']]}, 拼团价 ¥{$actDetail['group_price']}, "
                        . "折扣 【" 
                        . round( ($actDetail['group_price']/$price_array[$v['product_id']])*10, 5 ) 
                        . "】折, 购买openid【".$this->openid."】";
                        
                        /***********************发送模版消息****************************/
                        //发送模版消息
                        $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
                        $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

                        $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;
                        $inter_id= 'a450089706';
                        $business= 'package';
                        $openid_arr = $MessageWxtempTemplateModel->get_notice_openids();
                        foreach ($openid_arr as $openid) {
                            $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, '', $openid, $inter_id, $business, $message, FALSE);
                        }
                        /***********************发送模版消息****************************/
            
                    }

                    if( array_key_exists($v['product_id'], $price_array ) &&
                        $actDetail['group_price']< $price_array[$v['product_id']] * $actModel::PRICE_PERCENT_LIMIT ){
            
                        $result['status'] = Soma_base::STATUS_FALSE;
                        $result['message'] = '活动价格设置错误，暂时不能购买，请见谅。';
                        $result['data'] =  array();
                        echo json_encode($result);die;
                    }
                    break;
            
                case 'default':
                default:
                    break;
            }
        }
        
        return TRUE;
    }

    /**
     * 下单方法
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function make()
    {
        $result = array(
            'status' => Soma_base::STATUS_FALSE,
            'message' => '订单生成失败',
            'data'  => null,
            'step'  => 'wxpay'
        );

        $posts = $this->input->post();

//        if (ENVIRONMENT == 'dev') {
//            $posts = array(
//                'business' => 'package',
//                'settlement' => 'default',
//                'hotel_id' => 3,
//                'qty' => array(
//                    '11866' => 1
//                ),
//                'product_id' => 11866,
//                'name' => '123',
//                'phone' => 323,
//                'saler' => 0,
//                'fans_saler' => 0,
//                'inid' => 0,
//                'mcid' => 2657169,
//            );
//        }

        /**
         * -------------------
         * 参数验证
         * -------------------
         */
        $this->load->library('form_validation');
        $rules = array(
            'name' => array(
                'field' => 'name',
                'rules' => 'required'
            ),
            'phone' => array(
                'field' => 'phone',
                'rules' => 'required|is_natural'
            ),
            'business' => array(
                'field' => 'business',
                'rules' => 'trim|required|in_list[package]'
            ),
            'settlement' => array(
                'field' => 'settlement',
                'rules' => 'required|in_list[default,groupon,killsec]'
            ),
            'hotel_id' => array(
                'field' => 'hotel_id',
                'rules' => 'required'
            ),
            'product_id' => array(
                'field' => 'product_id',
                'rules' => 'required|is_natural_no_zero'
            ),
            'qty[]' => array(
                'field' => 'qty[]',
                'rules' => 'required|is_natural_no_zero'
            ),
            'psp_setting[]' => array(
                'field' => 'psp_setting[]',
                'rules' => 'numeric'
            ),
            'saler_id' => array(
                'field' => 'saler_id',
                'rules' => 'is_natural'
            ),
            'fans_saler' => array(
                'field' => 'fans_saler',
                'rules' => 'is_natural'
            ),
            'inid' => array(
                'field' => 'inid',
                'rules' => 'is_natural'
            ),
            'scope_product_link_id' => array(
                'field' => 'scope_product_link_id',  //使用了哪个专属价
                'rules' => 'is_natural'
            ),
        );

        $this->form_validation->set_data($posts);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === false) {
            $result['message'] = $this->form_validation->error_string();
            $this->json($result);
            return;
        }

        //下单
        $createResult = OrderService::getInstance()->create($posts);
        if ($createResult->getStatus() === Result::STATUS_FAIL) {
            $result['message'] = $createResult->getMessage();
            $this->json($result);
            return;
        }

        $data = $createResult->getData();
        $salesOrderModel = $data['salesOrderModel'];
        $payChannel = $data['payChannel'];

        $this->load->model('soma/sales_order_model', 'salesOrderModel');
        $order = $this->salesOrderModel->load($data['salesOrderModel']->order_id);

        $grand_total = $order->m_get('grand_total');
        if( $grand_total < 0.005){
            $pay_res['paid_type'] = empty($salesOrderModel->payment_extra)? Sales_payment_model::PAY_TYPE_HD : $salesOrderModel->payment_extra;
            $payResult = $this->_inner_payment($order, $pay_res, false);
            $this->json($payResult);
            return;
        }

        // 储值支付
        if($payChannel === 'balance_pay') {

            $bpay_passwd = $posts['bpay_passwd'];
            $pay_res = $this->balance_pay($salesOrderModel->inter_id, $salesOrderModel->openid, $bpay_passwd, $salesOrderModel->order_id);

            if($pay_res && $pay_res['status'] == Soma_base::STATUS_TRUE) {
                $pay_res['paid_type'] = Sales_payment_model::PAY_TYPE_CZ;
                $payResult = $this->_inner_payment($order, $pay_res);
                $this->json($payResult);
                return;
            } else {
                $result['message'] = $pay_res['message'];
                $this->json($result);
                return;
            }

        }elseif($payChannel === 'point_pay') {
            $pay_res = $this->point_pay($order);
            if($pay_res && $pay_res['status'] == Soma_base::STATUS_TRUE) {
                $pay_res['paid_type'] = Sales_payment_model::PAY_TYPE_JF;
                $payResult = $this->_inner_payment($order, $pay_res);
                $this->json($payResult);
                return;
            } else {
                $result['message'] = $pay_res['message'];
                $this->json($result);
                return;
            }
        }

        $result['status'] = Soma_base::STATUS_TRUE;
        $result['data'] = [
            'orderId' => $salesOrderModel->order_id
        ];
        $this->json($result);
    }

    //处理ajax请求，生成订单
    public function get_order_id_by_ajax()
    {
        if(!is_ajax_request()) return;

        if(in_array($this->inter_id, array('a491796658', 'a492669988')))
        {
            // 金陵下单11秒内重复下单时,暂定状态码为3，后续状态码跳过3
            $redis = $this->get_redis_instance();
            $lock_key = 'SOMA_ORDER:11_SEC_LOCK_' . $this->inter_id . '_' . $this->openid;
            $lock = $redis->setnx($lock_key, 'lock');
            if (!$lock) {
                $url = Soma_const_url::inst()->get_payment_package_success();
                $res = array('status' => 3, 'message' => '正在为您下单，请勿操作，耐心等待', 'success_url' => $url);
                echo json_encode($res);
                exit;
            }
            $redis->setex($lock_key, 11, 'jin_ling_order_lock');
        }

        $posts = $this->input->post();

        /**
         * -------------------
         * 参数验证
         * -------------------
         */
        $this->load->library('form_validation');
        $rules = array(
            'name' => array(
                'field' => 'name',
                'rules' => 'required'
            ),
            'phone' => array(
                'field' => 'phone',
                'rules' => 'required|is_natural'
            ),
            'business' => array(
                'field' => 'business',
                'rules' => 'trim|required|in_list[package]'
            ),
            'settlement' => array(
                'field' => 'settlement',
                'rules' => 'required|in_list[default,groupon,killsec]'
            ),
            'hotel_id' => array(
                'field' => 'hotel_id',
                'rules' => 'required'
            ),
            'product_id' => array(
                'field' => 'product_id',
                'rules' => 'required|is_natural_no_zero'
            ),
            'qty[]' => array(
                'field' => 'qty[]',
                'rules' => 'required|is_natural_no_zero'
            ),
            'psp_setting[]' => array(
                'field' => 'psp_setting[]',
                'rules' => 'numeric'
            ),
            'saler_id' => array(
                'field' => 'saler_id',
                'rules' => 'is_natural'
            ),
            'fans_saler' => array(
                'field' => 'fans_saler',
                'rules' => 'is_natural'
            ),
            'inid' => array(
                'field' => 'inid',
                'rules' => 'is_natural'
            ),
            'scope_product_link_id' => array(
                'field' => 'scope_product_link_id',  //使用了哪个专属价
                'rules' => 'is_natural'
            )
        );

        $this->form_validation->set_data($posts);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === false) {
            $result['message'] = '参数有误！' . $this->form_validation->error_string();
            echo json_encode($result);
            return;
        }

        // 查询分销员分组信息，查询分销员酒店信息
        $posts['saler_group'] = '';
        if(!empty($posts['saler']))
        {
            $this->load->library('Soma/Api_idistribute');
            // $api_idistribute = new Api_idistribute();
            $group_info = $this->api_idistribute->get_staff_group_info($this->inter_id, $posts['saler']);
            $saler_group = array();
            foreach($group_info as $group)
            {
                $saler_group[] = $group['group_id'];
            }
            $posts['saler_group'] = implode(',', $saler_group);

            $saler_info_by_id = $this->api_idistribute->getSalerInfoBySalerId($this->inter_id, $posts['saler']);
            $posts['saler_hotel'] = empty($saler_info_by_id['hotel_id']) ? '': $saler_info_by_id['hotel_id'];
        }
        // var_dump($posts);exit;

        $logTitle = "Order.php get_order_id_by_ajax :";

        $this->monoLog->info($logTitle . " post params Step 1", [
            'params' => $posts,
            'openid' => $this->openid,
            'inter_id' => $this->inter_id
        ]);

        $default_step = 'wxpay';
        $result = array(
            'status' => Soma_base::STATUS_FALSE,
            'message' => '订单生成失败',
            'data'  => NULL,
            'step'  => $default_step, //一共2种阶段: wxpay跳转到微信支付，success即支付成功
        );


        //记录使用方式到session
        $u_type = $this->input->post('u_type', true);

        $this->load->library('session');
        $this->session->set_userdata('order_use_type', $u_type);

        $this->load->model('soma/Sales_order_model','SalesOrderModel');
        $this->load->helper('soma/package');

        /**
         * @var Sales_order_model $SalesOrderModel
         */
        $SalesOrderModel = $this->SalesOrderModel;

        $business = $posts['business'];
        $settlement = $posts['settlement'];  //default| groupon | killsec
        $hotel_id = $posts['hotel_id'];

        //组装数组
        switch($business){
            case "package":
                $productIds = $posts['product_id'];
                $qtyArr = $posts['qty'];

                if(! is_array($productIds)){
                    $productIds = explode(',',$productIds);
                }
                $this->load->model('soma/Product_package_model','productPackageModel');
                $productArr = $this->productPackageModel->get_product_package_by_ids($productIds,$this->inter_id);
                $productEnInfo = $this->productPackageModel->getProductEnInfoList($productIds, $this->inter_id);

                $productModel = $this->productPackageModel;
                foreach($productArr as $k => $v){

                    //默认为不是时间规格的
                    $productArr[$k]['setting_date'] = Soma_base::STATUS_FALSE;

                    // 产品信息双语翻译
                    foreach($this->productPackageModel->en_fields() as $field)
                    {
                        $productArr[$k][$field . '_en'] = '';
                    }
                    if(isset($productEnInfo[$v['product_id']]))
                    {
                        foreach($this->productPackageModel->en_fields() as $field)
                        {
                            if(!empty($productEnInfo[$v['product_id']][$field]))
                            {
                                $productArr[$k][$field . '_en'] = $productEnInfo[$v['product_id']][$field];
                            }
                        }
                    }

                    if( $v['goods_type'] != $productModel::SPEC_TYPE_TICKET && $v['date_type'] == $productModel::DATE_TYPE_STATIC ){
                        $time = time();
                        $expireTime = isset( $v['expiration_date'] ) ? strtotime( $v['expiration_date'] ) : NULL;
                        if( $expireTime && $expireTime < $time ){
                            //如果已经过了有效期，停止本次循环，并在此列表删除该商品
                            unset( $productArr[$k] );
                            continue;
                        }
                    }
                    $productArr[$k]['qty'] = $qtyArr[$v['product_id']];

                    // 规格设定，参数传递方式参考qty
                    $setting_arr = isset($posts['psp_setting']) ? $posts['psp_setting'] : '';

                    // 库存调整，如果有规格传入，则使用规格价格库存
                    if($setting_arr) {
                        $this->load->model('soma/Product_specification_setting_model', 'psp_model');

                        if($settlement == 'default') {

                            $this->load->model('soma/Product_specification_model', 'ps_model');

                            // 普通购买走多规格库存
                            if($psp_setting = $this->psp_model->load($setting_arr[$v['product_id']])) {

                                $productArr[$k]['price_package'] = $psp_setting->m_get('spec_price');
                                $productArr[$k]['stock'] = $psp_setting->m_get('spec_stock');
                                $productArr[$k]['setting_id'] = $setting_arr[$v['product_id']];

                                // 组装多规格商品名
                                $specType = $psp_setting->m_get('type');

                                $spec_list = $this->ps_model->get_spec_list($this->inter_id, $v['product_id'], $specType);
                                $spec_list_info = json_decode($spec_list[$specType]['spec_compose'], true);

                                $compose = json_decode($psp_setting->m_get('setting_spec_compose'), true);
                                $setting_compose = current($compose);


                                if( $specType == $productModel::SPEC_TYPE_SCOPE ) {

                                    $spec_type_name = (isset($spec_list_info['spec_type']) && is_array($spec_list_info['spec_type']) ) ? $spec_list_info['spec_type'] : array();

                                    $product_spec_name = array();
                                    foreach ($spec_type_name as $key => $type_name) {
                                        $product_spec_name[] = $type_name . ':' . $setting_compose['spec_name'][$key];
                                    }

                                    $productArr[$k]['name'] .= "(" . implode(';', $product_spec_name) . ")";
                                } elseif( $specType == $productModel::SPEC_TYPE_TICKET ) {
                                    $productArr[$k]['setting_date']     = Soma_base::STATUS_TRUE;//这里是新加的字段，如果是时间规格的，那么过期时间就是规格时间
                                    $productArr[$k]['expiration_date']  = date('Y-m-d 23:59:59', strtotime( $setting_compose['date'] ) );
                                    $productArr[$k]['name'] .= "(" . $setting_compose['spec_name'][0]. ")";
                                }
                            }
                        } else {
                            // 秒杀拼团统一库存，随机发货，暂时无法回滚库存，秒杀拼团以活动价格为准
                            $psp_settings = $this->psp_model->get_specification_setting($this->inter_id, $v['product_id']);

                            $stocks = 0;
                            foreach ($psp_settings as $setting) {
                                $stocks += $setting['stock'];
                            }
                            $productArr[$k]['stock'] = $stocks;
                            $productArr[$k]['setting_id'] = 'all';
                        }

                    }

                    //如果是积分商品，去掉小数点，向上取整
                    if( $v['type'] == $productModel::PRODUCT_TYPE_POINT ){
                        $productArr[$k]['price_package'] = ceil( $productArr[$k]['price_package'] );
                        $productArr[$k]['price_market'] = ceil( $productArr[$k]['price_market'] );
                    }

                    // 如果是升级房券，判断是否存在分销员id以及分销员所属酒店
                    if ( $v['goods_type'] == $productModel::SPEC_TYPE_ROOM ) {
                        if (empty($posts['saler']) || empty($posts['saler_hotel'])) {
                            $result['status'] = Soma_base::STATUS_FALSE;
                            $result['message']  = '提示:请重新扫描员工二维码!';
                            $result['data'] = array(
                            );
                            echo json_encode($result);
                            return;
                        }
                         $productArr[$k]['hotel_id'] = $posts['saler_hotel'];
                    }

                }
            break;
        }


        write_log($logTitle." ProductInfo Step 1" . json_encode($productArr));

        //组装数组
        $userInfo = array();
        $createTime = date('Y-m-d H:i:s');
        $groupUserInsert = $killsecUserInsert = false;


        write_log($logTitle." ProductInfo Step 2 settlement:" . $settlement);
        
        switch($settlement){
            case "killsec":

                $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
                /**
                 * @var Activity_killsec_model $KillsecModel
                 */
                $KillsecModel =  $this->activityKillsecModel;

                $instance_id= $this->input->post('inid');
                $act_id= $this->input->post('act_id');

                $killResult = KillsecService::getInstance()->orderValid($instance_id, $this->inter_id, $this->openid);
                if ($killResult->getStatus() == Result::STATUS_FAIL) {
                    echo json_encode($killResult->toArray());die;
                }

                $actDetail = $KillsecModel->find( array('inter_id'=>$this->inter_id, 'act_id'=>$act_id) );

                foreach($productArr as $k => $v){
                    $productArr[$k]['price_package'] =  $actDetail['killsec_price'];
                    if( $productArr[$k]['qty']> $actDetail['killsec_permax'] ){
                        $productArr[$k]['qty'] = $actDetail['killsec_permax'];
                    }
                }

                $resultData = $killResult->toArray();
                if (!empty($killResult->getData())) {
                    $resultData['step'] = $default_step;
                    if( in_array( $this->inter_id, $this->wft_pay_inter_ids ) ){
                        $resultData['step'] = 'wft_pay';
                    }
                    Log::debug('make order killsec result :', $resultData);
                    echo json_encode($resultData);die;
                }

                $killsecUserInsert = true; //获取订单后插入数据库标记
            break;
                
            case "groupon":
                $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
                $GrouponModel =  $this->activityGrouponModel;

                $type = $this->input->post('type');
                if($type == 'add'){ //开团
                    $groupId = $GrouponModel->add_groupon_group($posts['act_id'],$this->openid,$this->inter_id);  /*groupon插入*/
                    
                }else{
                    $groupId = $this->input->post('grid');
                    /*同步检测人数*/
                    $this->load->model('soma/sales_refund_model');
                    $SalesRefundModel = $this->sales_refund_model;
                    $GrouponModel->set_unavailable_group_user($this->inter_id ,array('group_id'=> $groupId),$SalesRefundModel, $business);
                    $this->monoLog->info($logTitle . 'Groupon release and groupId ', [
                        'groupID' => $groupId,
                        'openid' => $this->openid,
                        'interID' => $this->inter_id
                    ]);
                    write_log($logTitle." Groupon release and groupId :" . $groupId);
                }

                /**参团用户信息*/
                $this->load->model('wx/Publics_model','public');
                $userFansInfo = $this->public->get_fans_info($this->openid);
                $userInfo['openid'] = $this->openid;
                $userInfo['nickname'] = $userFansInfo['nickname'];
                $userInfo['headimgurl'] = $userFansInfo['headimgurl'];
                $userInfo['join_time'] = $createTime;
                $userInfo['status'] = Soma_base::STATUS_FALSE;
                $groupUserInsert = true; //获取订单后插入数据库标记
                /**参团用户信息 end*/

                $grouponDetial = $GrouponModel->groupon_group_detail($groupId,$this->inter_id);
                $actDetail = null;
                if(!empty($grouponDetial)) {
                    $actDetail = $GrouponModel->groupon_detail($grouponDetial['act_id'],$this->inter_id);
                }

                if(empty($grouponDetial) || empty($actDetail)) {
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '系统异常，请稍后重新尝试。';
                    $result['data'] = Soma_const_url::inst()->get_url('soma/package/package_detail',
                        array('pid'=> $actDetail['product_id'] ) );
                    echo json_encode($result);die;
                }

                /*验证参团人数*/
                
                if( $actDetail['product_id'] != $posts['product_id'] ){
                    //防止手动输入product_id借助配额购买其他商品
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '参数错误，请重新参加活动购买。';
                    $result['data'] = Soma_const_url::inst()->get_url('soma/package/package_detail',
                        array('pid'=> $actDetail['product_id'] ) );
                    echo json_encode($result);die;
                
                }
                if($grouponDetial['status'] == $GrouponModel::GROUP_STATUS_FINISHED){ //支付过程中已经成团
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '真遗憾，你来晚啦~~别人已经完成这个拼团';
                    $result['data'] = array(
                    );
                    echo json_encode($result);
                    return;
                }
                if($grouponDetial['join_count'] >= $actDetail['group_count']){ //有用户已经占团
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '真遗憾，你来晚啦~~前面已经有用户正在支付了';
                    $result['data'] = array(
                    );
                    echo json_encode($result);
                    return;
                }

                //修正活动信息：拼团人数加1
                if($type != 'add'){ //开团
                    $GrouponModel->update_groupon_group_join($groupId,'join',$this->inter_id);
                }
                
                //检测价格问题，低于此比例报警拦截
                $this->_order_price_safe_check($settlement, $productArr, $actDetail, $GrouponModel );
                foreach($productArr as $k => $v){
                    $productArr[$k]['price_package'] =  $actDetail['group_price'];
                    $productArr[$k]['qty'] = 1; //拼团只可以买一件
                }

            break;
        }

        $can_mail = false;

        if(empty($productArr)) {
            $result['message'] = '产品参数有误！';
            echo json_encode($result);
            return;
            
        } else {

            // 针对$productArr中的商品属性，如果为积分商品，清空优惠规则
            foreach ($productArr as $product) {
                if($product['type'] == Product_package_model::PRODUCT_TYPE_POINT) {
                    unset($posts['mcid']);
                    unset($posts['quote_type']);
                }

                //判断是否能邮寄
                if($product['can_mail'] == Product_package_model::STATUS_TRUE)
                    $can_mail = true;
                else if($product['can_mail'] == Product_package_model::STATUS_FALSE){
                    $can_mail = false;
                }


            }

            $customer = new Sales_order_attr_customer($this->openid);

            // 2016-10-27 订单下单时保存用户信息到订单表 fengzhongcheng
            $customer->name = $posts['name'];
            $customer->mobile = $posts['phone'];
            $customer->openid = $this->openid;

            $SalesOrderModel->customer = $customer;
            $SalesOrderModel->business = $business;
            $SalesOrderModel->settlement = $settlement;
            $SalesOrderModel->customer= $customer;
            $SalesOrderModel->scope_product_link_id = isset($posts['scope_product_link_id']) ? $posts['scope_product_link_id'] : 0;

            /**
             * 20170503 luguihong 判断是否给予绩效
             */
            $giveDistribute = $this->session->userdata( 'giveDistribute'.$this->inter_id.$this->openid );
            if( $giveDistribute )
            {
                $SalesOrderModel->saler_id      = $posts['saler'];
                $SalesOrderModel->saler_group = $posts['saler_group'];
                $SalesOrderModel->fans_saler_id = $posts['fans_saler'];
            } else {
                $SalesOrderModel->saler_id      = 0;
                $SalesOrderModel->fans_saler_id = 0;
            }

            $SalesOrderModel->killsec_instance = isset($posts['inid']) ? $posts['inid'] : 0;
            $SalesOrderModel->shipping= 0;
            
            $discount_array= array();

            $this->load->library('Soma/Api_member');
            $this->load->model('soma/Sales_order_discount_model');
            $this->load->model('soma/Sales_rule_model');
            $this->load->model('soma/Sales_payment_model');

            $api= new Api_member($this->inter_id);
            $result= $api->get_token();
            $api->set_token($result['data']);

            // 查询会员信息
            $member_info = $api->get_member_info($this->openid);
            if($member_info)
            {
                // "member_mode":"1" 则是 本地会员， member_mode =2 & is_login = t, 则是对接而且登录的会员
                // 对接会员membership_number与jfk_member_info值不一样，非对接会员一样，下单取membership_number即可
                $SalesOrderModel->member_id = $member_info['data']->member_id;
                $SalesOrderModel->member_card_id = $member_info['data']->membership_number;
            }
            else
            {
                $result = array(
                    'status' => Soma_base::STATUS_FALSE,
                    'message' => '会员信息获取失败，请稍后再重新尝试下单',
                    'data'  => NULL,
                    'step'  => $default_step, //一共2种阶段: wxpay跳转到微信支付，success即支付成功
                );
                echo json_encode($result);
                exit;
            }

            /** 优惠券累计：***********************/
            //根据页面传过来的card_id通过会员接口读取扣减总额，组装discount数组 @see Sales_order_discount_model
            if( isset($posts['mcid']) && !empty($posts['mcid']) ){

                $payment_extra = Sales_payment_model::PAY_TYPE_CP;

                // $api= new Api_member($this->inter_id);
                $couponList = $api->conpon_sign_list( $this->openid );
                $couponList = (array) $couponList['data'];

                $d_type = Sales_order_discount_model::TYPE_COUPON;

                $info = array();
                $coupon_count = 0;
                $get_card_id = 0;
                //luguihong 20161107 优惠券批量使用，这里需要改成多个mcid 2
                $member_card_ids = isset($posts['mcid']) ? $posts['mcid'] : array();
                if( !is_array( $member_card_ids ) ){
                    $member_card_ids = explode(',', $member_card_ids);
                }

                foreach( $couponList as $k=>$v ){
                    $sv = (array)$v;
                    if( in_array( $sv['member_card_id'], $member_card_ids ) ){
                        $info[$sv['member_card_id']] = $sv + array('discount_type'=> $d_type );
                        $coupon_count++;

                        //判断是否是同一个card_id，否则返回空。只能同种券同一个card_id的券才可以叠加使用  todo 这个逻辑有问题
                        if( $get_card_id && $get_card_id != $sv['card_id'] ){
                            $info = array();
                            $coupon_count = 0;
                            break;
                        }else{
                            $get_card_id = $sv['card_id'];
                        }
                        
                        //判断是否是折扣券、代金券、折扣券。如果是折扣券，只能使用一张券。
                        if( $sv['card_type'] == Sales_order_discount_model::TYPE_COUPON_ZK ){
                            //如果是折扣券，那么先置空$info，等于当前的券内容，$coupon_count = 1。并停止循环
                            $info = array();
                            $coupon_count = 1;
                            $info[$sv['member_card_id']] = $sv + array('discount_type'=> $d_type );
                            break;
                        }
                    }
                }
                //检测购买数量和优惠券的数量  购买数量>=优惠券数量
                $buy_qty = isset( $posts['qty'] ) ? $posts['qty'] : 0;
                if( !is_array( $buy_qty ) ){
                    $buy_qty = explode(',', $buy_qty);
                }
                $buy_count = current( $buy_qty );
                if( $coupon_count > $buy_count ){
                    Soma_base::inst()->show_exception('选择优惠券出错');
                }

                $discount_array[$d_type] = $info+ array('discount_type'=> $d_type );

            }

/** 储值优惠:  ***********************/
            if( isset($posts['quote_type']) && isset($posts['quote']) && $posts['quote_type']== Sales_rule_model::RULE_TYPE_BALENCE ){
                $payment_extra = Sales_payment_model::PAY_TYPE_CZ;
                
                if( !isset($api) ){
                    $api= new Api_member($this->inter_id);
                    $result= $api->get_token();
                    $api->set_token($result['data']);
                }
                $info= $api->balence_info( $this->openid );
            
                if( $info['data']>= $posts['quote'] ){
                    $d_type= Sales_order_discount_model::TYPE_BALENCE;
                    if(!isset($posts['password'])) { $posts['password']=''; }
                    $discount_array[$d_type]= array( 'discount_type'=> $d_type, 'quote'=> $posts['quote'], 'passwd'=> $posts['password'], );
            
                } else {
                    Soma_base::inst()->show_exception('您的储值不够');
                }
            }
            
/** 积分优惠:  ***********************/
            else if( isset($posts['quote_type']) && isset($posts['quote']) 
                    && $posts['quote_type']== Sales_rule_model::RULE_TYPE_POINT ){
                $payment_extra= Sales_payment_model::PAY_TYPE_JF;
                
                if( !isset($api) ){
                    $api= new Api_member($this->inter_id);
                    $result= $api->get_token();
                    $api->set_token($result['data']);
                }
                $info= $api->point_info( $this->openid );
                
                if( $info['data']>= $posts['quote'] ){
                    $d_type= Sales_order_discount_model::TYPE_POINT;
                    $discount_array[$d_type]= array( 'discount_type'=> $d_type, 'quote'=> $posts['quote'], );
                    
                } else {
                    Soma_base::inst()->show_exception('您的积分不够');
                }
            }
            
/** 随机立减/满减优惠在后台自动计算:  ****/
            
            
            
/**  ********************************/
            $SalesOrderModel->product = $productArr;
            $SalesOrderModel->discount = $discount_array;//优惠券批量使用，这里需要改成多个mcid 3
            $SalesOrderModel->hotel_id = $hotel_id;

            $extras = array();
 /** 邮寄相关:  ***********************/
            if( $can_mail && isset($posts['address_id']) && $posts['address_id'] > 0){
                $extras['mail'] = array(
                    'address_id'   =>  $posts['address_id']
                );
            }

            $SalesOrderModel->extra = $extras;
            $SalesOrderModel = $SalesOrderModel->order_save($business, $this->inter_id);  //订单保存 <-失败

            $this->monoLog->info($logTitle . " order save Step 3", [
                'orderModel' => $SalesOrderModel,
                'openid' => $this->openid,
                'inter_id' => $this->inter_id
            ]);

            if($SalesOrderModel){
                $orderId = $SalesOrderModel->order_id;

                //拼团用户信息插入
                if($groupUserInsert){
                    $userInfo['order_id'] = $orderId;
                    $GrouponModel->groupon_user_add($groupId,$userInfo,$this->inter_id);
                }
                //秒杀用户信息插入
                if($killsecUserInsert){

                    $userInfo= array(
                        'order_id'=> $orderId,
                        'status'=> Activity_killsec_model::USER_STATUS_ORDER,
                        'order_time'=> date('Y-m-d H:i:s'),
                    );

                    $KillsecModel->update_user_by_filter($this->inter_id, array(
                        'openid'=>$this->openid, 'instance_id'=>$instance_id), $userInfo );
                }
                
                //电话信息插入
                $contact['inter_id']  = $this->inter_id;
                $contact['mobile']  = $this->input->post('phone');
                $contact['name']    = $this->input->post('name');
                $contact['openid']  = $this->openid;
                $contact['create_time'] = $createTime;
                $contact['order_id'] = $orderId;
                if( !empty($contact['mobile']) ){
                    $SalesOrderModel->save_customer_contact($contact, array('openid'=> $this->openid ) );
                }
                //电话信息插入end

                $result['status'] = Soma_base::STATUS_TRUE;
                $result['message']  = '订单保存成功';
                $result['data'] = array( 'orderId' => $orderId );
                $result['step'] = $default_step;

                if($productArr[0]['type'] == Product_package_model::PRODUCT_TYPE_BALANCE) {
                    $result['step'] = 'balance_pay';
                }

                if($productArr[0]['type'] == Product_package_model::PRODUCT_TYPE_POINT) {
                    $result['step'] = 'point_pay';
                }

                if( in_array( $this->inter_id, $this->wft_pay_inter_ids) ){
                    $result['step'] = 'wft_pay';
                }
            } else {
                Soma_base::inst()->show_exception('下单失败，请稍后重新尝试');
            }

            $this->monoLog->info($logTitle . " pay method Step 4", [
                'result' => $result,
                'openid' => $this->openid,
                'inter_id' => $this->inter_id
            ]);

            $zbcode = $this->input->get('zbcode', true);
            $channel_id = $this->input->get('channelid', true);
            if($zbcode && $channel_id)
            {
                $redis = $this->get_redis_instance();
                $redis_key = $SalesOrderModel->get_zb_order_redis_key();
                $redis_value = array('zbcode' => $zbcode, 'channelid' => $channel_id);
                $redis->setex($redis_key, 3600, json_encode($redis_value));
            }

            //订单金额为0直接处理为支付
            //订单金额小于0.01直接支付处理
            $grand_total= $SalesOrderModel->m_get('grand_total');
            //计算优惠券，积分的时候，会出现小数后几位
            if( $grand_total < 0.005){
                $pay_res['paid_type'] = isset($payment_extra)? $payment_extra: Sales_payment_model::PAY_TYPE_HD;
                $result = $this->_inner_payment($SalesOrderModel, $pay_res, false);
            }
            
            // 储值支付
            if($result['step'] == 'balance_pay') {
                
                $bpay_passwd = $this->input->post('bpay_passwd', true);
                $pay_res = $this->balance_pay($this->inter_id, $this->openid, $bpay_passwd, $orderId);

                if($pay_res && $pay_res['status'] == Soma_base::STATUS_TRUE) {
                    $pay_res['paid_type'] = Sales_payment_model::PAY_TYPE_CZ;
                    $result = $this->_inner_payment($SalesOrderModel, $pay_res);
                } else {
                    Soma_base::inst()->show_exception($pay_res['message']);
                }

            }

            // 积分支付
            if($result['step'] == 'point_pay') {
                $pay_res = $this->point_pay($SalesOrderModel);
                if($pay_res && $pay_res['status'] == Soma_base::STATUS_TRUE) {
                    $pay_res['paid_type'] = Sales_payment_model::PAY_TYPE_JF;
                    $result = $this->_inner_payment($SalesOrderModel, $pay_res);
                } else {
                    Soma_base::inst()->show_exception($pay_res['message']);
                }
            }

//            $this->output->enable_profiler(TRUE);
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
    }


    /**
     * 储值支付
     * @param $inter_id
     * @param $open_id
     * @param $passwd
     * @param $order_id
     * @return array
     *
     */
    protected function balance_pay($inter_id, $open_id, $passwd, $order_id) {

        try {
            $this->load->model('soma/sales_order_model');   

            if($order = $this->sales_order_model->load($order_id)) {
                $api= new Api_member($inter_id);    

                $result= $api->get_token();
                $api->set_token($result['data']);   

                $balance_info = null;
                $balance_info = $api->balence_info($open_id);
                $balance = isset($balance_info['data']) ? $balance_info['data']:0;
                if($balance < $order->m_get('grand_total')) {
                    // return json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '储值余额不足！'));
                    return array('status' => Soma_base::STATUS_FALSE, 'message' => '储值余额不足！');
                }   

                $scale= $api->balence_scale( $open_id );
                $pay_total = $api->balence_scale_convert($scale, $order->m_get('grand_total'), FALSE);
                $uu_code = rand(1000, 9999);    

                $use_result['err'] = 1; // 默认调用失败
                $yinju_inter_ids = array('a457946152', 'a471258436');
                if(in_array($inter_id, $yinju_inter_ids)) {
                    $use_result= (array) $api->yinju_balence_use($pay_total, $open_id, $passwd, $uu_code, $order_id);
                } else {
                    $use_result= (array) $api->balence_use($pay_total, $open_id, $passwd, $uu_code, $order_id);
                }
                if( $use_result['err'] == 0 ){
                    // return json_encode(array('status' => Soma_base::STATUS_TRUE, 'message' => ''));
                    return array('status' => Soma_base::STATUS_TRUE, 'message' => '');
                }
            }
        } catch (Exception $e) {
            // 日志
        }

        // return json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '订单信息错误！'));
        return array('status' => Soma_base::STATUS_FALSE, 'message' => '订单信息错误！');
    }

    protected function point_pay($order) {
        try {

            $inter_id = $order->m_get('inter_id');
            $open_id  = $order->m_get('openid');
            $order_id = $order->m_get('order_id');

            $api= new Api_member($inter_id);
            $result= $api->get_token();
            $api->set_token($result['data']);

            $point_info = null;
            $point_info = $api->point_info($open_id);
            $point = isset($point_info['data']) ? $point_info['data']:0;
            if($point < $order->m_get('grand_total')) {
                return array('status' => Soma_base::STATUS_FALSE, 'message' => '积分余额不足！');
            }

            $uu_code = rand(1000, 9999);
            // 积分支付必须是整数，上取整
            $pay_total = ceil($order->m_get('grand_total'));
            $pay_res = $api->point_use($pay_total, $open_id, $uu_code, $order_id, $order);

            if($pay_res['err'] == 0) {
                return array('status' => Soma_base::STATUS_TRUE, 'message' => '');
            }
        } catch (Exception $e) {

        }

        return array('status' => Soma_base::STATUS_FALSE, 'message' => '订单信息错误！');
    }

    protected function _inner_payment($order, $payment, $save_flag = true) {
        
        $result['status'] = Soma_base::STATUS_FALSE;
        $result['message']  = '订单支付失败';
        $result['step'] = 'fail';
        
        try {
            $log_data= array();
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

            $order->order_payment($log_data );
            $order->order_payment_post($log_data ); 

            if($save_flag) {
                $this->load->model('soma/Sales_payment_model', 'pay_model');
                $this->pay_model->save_payment($log_data);
            }   

            $result['status'] = Soma_base::STATUS_TRUE;
            $result['message']  = '订单支付成功';
            $result['data'] = array( 'orderId' => $order->m_get('order_id') );
            $result['step'] = 'success';

            $url_params = array(
                'id' => $order->m_get('inter_id'),
                'order_id' => $order->m_get('order_id')
            );

            $url = Soma_const_url::inst()->get_payment_package_success($url_params);
            $result['success_url'] = $url;

            $bType = $this->input->get('bType', true);
            $result['bType'] = $bType;
            // 月饼说分流
            if($bType) {
                $url = Soma_const_url::inst()->get_payment_package_success(array('id'=>$this->inter_id, 'order_id' => $order->m_get('inter_id')));
                $order_detail = $order->m_data();
                $result['success_url'] = $order->success_payment_path($this->inter_id, $bType, $order_detail, $url);
            }

        } catch (Exception $e) {

        }

        return $result;
    }

    /**
     * 关闭订单支付功能
     * 商户订单支付失败需要生成新单号重新发起支付，要对原订单号调用关单，避免重复支付；系统下单后，用户支付超时，系统退出不再受理，避免用户继续，请调用关单接口。
     */
    public function close_order_pay(){

    }

    // 订单购买后送自己，需要调用会员接口，仅特权券商品使用，其他类型直接跳转订单列表
    public function self_use() {

        $this->load->model('soma/Sales_order_model', 'o_model');
        $this->load->model('soma/Product_package_model', 'p_model');
        $p_model = $this->p_model;

        $order_id = $this->input->get('oid', true);
        $order = $this->o_model->load($order_id);
        $items = array();

        if($order) {
            $order->business = $order->m_get('business'); // 兼容写法
            $items = $order->get_order_items($order->m_get('business'), $this->inter_id);
        }
        
        if(count($items) <= 0 
            || $items[0]['type'] != $p_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER) {
            redirect(Soma_const_url::inst()->get_url('soma/order/my_order_list', array('id' => $this->inter_id)));
        }

        $this->load->model('soma/Consumer_order_model', 'co_model');
        $this->co_model->package_consumer($order_id, $this->openid, $this->inter_id, $order->m_get('business'));

        redirect(Soma_const_url::inst()->get_url('soma/order/order_detail', array('id' => $this->inter_id, 'oid' => $order_id, 'bsn' => $order->m_get('business'))));

    }

    /**
     * 特权券详情
     * 两个参数：order_id, used
     */
    public function voucher_detail() {

        $order_id = $this->input->get('oid', true);
        $gift_id  = $this->input->get('gid', true);
        $used     = $this->input->get('used', true);
        $this->load->model('soma/Asset_item_package_model');

        $detail = $this->_get_asset_from_order($order_id);
        if(!$detail) {
            $detail = $this->_get_asset_from_gift($gift_id);
        }

        if(!$detail || !is_array($detail['items']) || count($detail['items']) <= 0 
            || $detail['items'][0]['type'] != Asset_item_package_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER) {
            redirect(Soma_const_url::inst()->get_pacakge_home_page());
        }

        $qty = 0;
        foreach ($detail['items'] as $item) { $qty += $item['qty']; }
        if($qty <= 0) { $used = Soma_base::STATUS_TRUE; }

        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id, 'soma');
        // $voucher_detail = json_decode(json_encode($api->get_package_info($detail['items'][0]['card_id'])), true);
        $voucher_detail = $api->_package_card( $api->get_package_info( $detail['items'][0]['card_id'] ), $this->inter_id );
        // var_dump( $voucher_detail['data']['card'] );
        if(isset($voucher_detail['err']) 
            && $voucher_detail['err'] != '0') {
            redirect(Soma_const_url::inst()->get_pacakge_home_page());
        }

        $header = array( 'title'=> '礼包详情' );
        $this->datas['order_id']       = $order_id;
        $this->datas['gift_id']        = $gift_id;
        $this->datas['detail']         = $detail;
        $this->datas['item']           = $detail['items'][0];
        $this->datas['used']           = $used;
        $this->datas['voucher_detail'] = $voucher_detail['data'];

        $this->_view("header", $header);
        $this->_view("voucher_detail", $this->datas);
    }

    protected function _get_asset_from_order($order_id) {

        if(!$order_id) { return false; }

        $this->load->model('soma/Sales_order_model');
        $order = $this->Sales_order_model->load($order_id);

        if($order) {
            $order_detail = $order->get_order_asset($order->m_get('business'), $this->inter_id);
            $asset_detail = $order->filter_items_by_openid($order_detail['items'], $this->openid);

            $detail['items'] = $asset_detail;
            
            $order_status = $order->get_status_label();
            $detail['status'] = $order_status[ $order->m_get('status') ];
            return $detail;
        }

        return false;
    }

    protected function _get_asset_from_gift($gift_id) {
        
        if(!$gift_id) { return false; }

        $this->load->model('soma/Asset_item_package_model', 'ai_model');
        $asset_detail = $this->ai_model->get_order_items_byGiftids($gift_id, 'package', $this->inter_id);

        if($asset_detail) {
            $asset_detail = $this->ai_model->filter_items_by_openid($asset_detail, $this->openid);
            $detail['items']  = $asset_detail;
            $detail['status'] = '已支付';
            return $detail;
        }

        return false;
    }

    public function voucher_sign() {
        
        $order_id = $this->input->get('oid', true);
        $gift_id  = $this->input->get('gid', true);
        $asset_id = $this->input->get('aiid', true);
        $business = $this->input->get('bsn', true);

        $uuid = ($order_id == null) ? $gift_id : $order_id;

        $this->load->model('soma/Consumer_order_model', 'co_model');
        $this->co_model->package_consumer(
            $uuid, $this->openid, $this->inter_id, $business, $asset_id);

        $params = array(
            'id'   => $this->inter_id,
            'oid'  => $order_id,
            'gid'  => $gift_id,
            'used' => Soma_base::STATUS_TRUE,
        );

        redirect(Soma_const_url::inst()->get_url('*/*/voucher_detail', $params));

    }

    //展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() )
    {
//        parent::_view('order'. DS. $file, $datas ,$theme);
        parent::_view('order'. DS. $file, $datas);
    }

    //交易快照
    public function photo_shot()
    {
        $openid = $this->openid;
        $inter_id = $this->inter_id;
        $order_id = $this->input->get('oid');
        $business = $this->input->get('bsn');
        // var_dump( $openid, $inter_id, $order_id );die;
        if( !$order_id || !$openid || !$inter_id ){
            $params = array();
            $params['bsn'] = $business;
            $params['id'] = $inter_id;
            $params['oid'] = $order_id;
            redirect(Soma_const_url::inst()->get_url('*/*/order_detail', $params));
        }

        $this->load->model('soma/Sales_order_product_record_model','RecordModel');
        $RecordModel = $this->RecordModel;
        $filter = array();
        $filter['openid'] = array( $openid );
        $filter['order_id'] = $order_id;
        $filter['status'] = Soma_base::STATUS_TRUE;
        $record_detail = $RecordModel->get_record_info( $filter, $inter_id );
        if( !$record_detail ){
            $params = array();
            $params['bsn'] = $business;
            $params['id'] = $inter_id;
            $params['oid'] = $order_id;
            redirect(Soma_const_url::inst()->get_url('*/*/order_detail', $params));
        }

        // var_dump($record_detail);exit;
        // 双语化翻译
        if($this->langDir == self::LANG_DIR_EN)
        {
            $translate = array('name', 'compose', 'order_notice', 'img_detail');
            foreach ($translate as $field)
            {
                if(!empty($record_detail[$field . '_en']))
                {
                    $record_detail[$field] = $record_detail[$field . '_en'];
                }
            }
        }

        $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
        $somaSalesOrderModel = $this->somaSalesOrderModel->load( $order_id );
        if( $somaSalesOrderModel )
        {
            $orderDetail = $somaSalesOrderModel->get_order_detail($business, $inter_id);
            $record_detail['expiration_date'] = isset( $orderDetail['items'][0]['expiration_date'] )
                                                ? $orderDetail['items'][0]['expiration_date']
                                                : $record_detail['expiration_date'];
        }

        $this->datas = array(
                'record_detail'=>$record_detail,
            );
        $header = array(
            'title'=> $this->lang->line('deal_snapshot'),
        );
        $this->_view("header",$header);
        $this->_view("photo_shot",$this->datas);
    }


}
