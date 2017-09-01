<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Front_Soma_Wxapp {

    public  $themeConfig;
    public  $theme = 'default';//皮肤

    const PACKAGE_ITEM = 'package';
    const GROUPON_ITEM = 'groupon';

	public function __construct()
	{
		parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];
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
        $sort = 'create_time DESC';
        switch($type){
            case 1:
                $filter['is_payment'] = $SalesOrderModel::IS_PAYMENT_NOT; //等待支付
                $pageTitle = '等待支付';
                $orders = $SalesOrderModel->get_order_list(self::PACKAGE_ITEM,$this->inter_id,$filter,$sort);
                break;
            case 2:
                $filter['is_payment'] = $SalesOrderModel::IS_PAYMENT_YES; //已支付
                $filter['refund_status'] = $SalesOrderModel::REFUND_PENDING;
                $pageTitle = '已付款';
                $orders = $SalesOrderModel->get_order_list(self::PACKAGE_ITEM,$this->inter_id,$filter,$sort);
                break;
            case 3:
                $filter['refund_status'] = $SalesOrderModel::REFUND_ALL; //退款
                $pageTitle = '退款订单';
                $orders = $SalesOrderModel->get_order_list(self::PACKAGE_ITEM,$this->inter_id,$filter,$sort);
                break;
            default:
                // $filter = array('status'=>$SalesOrderModel->available_order(),'openid'=>$this->openid,'refund_status'=>$SalesOrderModel::REFUND_PENDING);
                $filter = array('status'=>$SalesOrderModel->available_order(),'openid'=>$this->openid);
                $pageTitle = '全部订单';
                $orders = $SalesOrderModel->get_order_list_with_filter(self::PACKAGE_ITEM,$this->inter_id,$filter);
                break;
        }

        if( defined('PROJECT_AREA') 
            && PROJECT_AREA=='mooncake' ){
            $pageTitle = '月饼说-' . $pageTitle;
        }

        $header = array(
            'title' => $pageTitle
        );

        $o_status = $SalesOrderModel->get_status_label();
        $fmt_orders = array();
        foreach ($orders as $key => $row) {
            $tmp = $row;
            $status_line = '';
            if($row['settlement'] && $row['refund_status'] == $SalesOrderModel::REFUND_ALL) {
                $status_line .= "拼团失败";
            } else if ($row['settlement'] && $row['refund_status'] == $SalesOrderModel::STATUS_PAYMENT) {
                $status_line .= "拼团成功";
            } else {
                $status_line .= $o_status[ $row['status'] ];
            }

            if($row['settlement'] == 'groupon') { $status_line .= ' | 拼团订单'; }
            if($row['refund_status'] == $SalesOrderModel::REFUND_ALL) { $status_line .= ' | 退款订单'; }
            $tmp['status_line'] = $status_line;
            $fmt_orders[] = $tmp;
        }

        // $this->datas['activityGrouponModel'] = $this->activityGrouponModel;
        // $this->datas['salesModel'] = $SalesOrderModel;
        $this->datas['orders'] = $fmt_orders;
        $this->datas['order_status'] = $SalesOrderModel->get_status_label();
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

        // $this->datas['openid'] = $this->openid;

        // $this->_view("header",$header);
        // $this->_view("order_list",$this->datas);
        $this->_view("my_order_list",$this->datas);
        // $this->_view("all_order",$this->datas);

    }


    /**
     * 订单详情
     */
    public function order_detail(){
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

        $SalesOrderModel = $this->SalesOrderModel;
        $ConsumerOrderModel = $this->ConsumerOrderModel;
        $GiftOrderModel = $this->GiftOrderModel;
        $GiftItemModel = $this->GiftItemModel;

        $SalesOrderModel->business = $business;
//        $orderDetail = $SalesOrderModel->load($orderId)->get_order_detail($business,$interId);
        $orderDetail = $SalesOrderModel->load($orderId)->get_order_asset($business,$interId); //资产订单

        //筛选属于自己的资产订单
        $orderDetail['items'] = $SalesOrderModel->filter_items_by_openid( $orderDetail['items'], $this->openid );
        $items = $openids = array();
        if($orderDetail['items']){
            //修改为送出的订单也一起展现，并展现收礼人昵称
            foreach($orderDetail['items'] as $k=> $v){
                if($v['openid']!= $this->openid || !empty($v['gift_id'])){
                    //unset($orderDetail['items'][$k]); //剔除赠送接收所得，避免自己送出去的又被送回来
                    $openids[]= $v['openid'];
                }
            }
            if( count($openids)>0 ){
                $this->load->model('wx/Publics_model','public');
                $openids = $this->public->get_fans_info_byIds($openids);
            }
            $openids= $SalesOrderModel->array_to_hash($openids, 'nickname', 'openid');
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
                if( $giftDetail['status'] != $GiftOrderModel::STATUS_TIMEOUT ){//超时退回的暂时不显示 
                    $giftDetail['items'][] = $sv;
                    $giftSendOrderDetail[] = $giftDetail;
                }
            }

        }
// var_dump( $giftSendOrderDetail );


        // 订单资产邮寄信息显示，by fengzhongcheng
        // var_dump($consumerOrderDetail);exit;
        $consumer_ids = array();
        foreach ($consumerOrderDetail as $consumer_detail) {
            if(is_array($consumer_detail) && count($consumer_detail) >0 ) {
                foreach ($consumer_detail as $consumer) {
                    $consumer_ids[] = $consumer['consumer_id'];
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

        $SalesOrderModel = $this->SalesOrderModel->load($orderId);
        //退款链接
        $can_refund = $SalesOrderModel->can_refund_order();

        //退款不能超过支付后7天
        $paymentTime = $SalesOrderModel->m_get('payment_time');
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
        $can_mail = $SalesOrderModel->can_mail_order();
        
        //是否是拼团
        $settlement = $this->SalesOrderModel->m_get('settlement');
        if( $settlement == 'groupon' ){
            $isGroupOn = TRUE;
        }else{
            $isGroupOn = FALSE;
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
        $header = array(
            'title'=> '订单明细'
        );
        // $this->_view("header",$header);
        // $this->_view("order_detail",$this->datas);
        
        // 小程序数据处理
        $app_data = array();
        
        // 产品类型没有跟随消费细单，由于订单只存在一种商品，因此可以从资产细单中获取
        $p_type = Product_package_model::PRODUCT_TYPE_DEFAULT;
        // 礼物赠送细单中没有这些字段，从资产提取
        $name = $face_img = $hotel_name = $price_package = '';

        $app_data['order_detail'] = $this->datas['orderDetail'];
        $app_data['order_detail']['is_groupon'] = $this->datas['is_groupon'];
        $app_data['order_detail']['show_photo_shot'] = false;
        $app_data['order_detail']['can_refund'] = false;
        $app_data['order_detail']['can_mail'] = false;
        $app_data['order_detail']['can_batch_gift'] = false;
        $app_data['order_detail']['can_invoice'] = $this->datas['invoice_enable'];

        if(isset($this->datas['record_detail'])) {
            $app_data['order_detail']['show_photo_shot'] = true;
        }
        if($this->datas['can_refund'] 
            && !$this->datas['isOverRefund']
            && $app_data['order_detail']['subtotal'] - $app_data['order_detail']['discount']) {
            $app_data['order_detail']['can_refund'] = true;
        }
        
        $app_data['asset_detail'] = $this->datas['orderDetail']['items'];
        foreach ($this->datas['orderDetail']['items'] as $index => $item) {

            $p_type = isset($item['type']) && $item['type'] != '' ? $item['type'] : Product_package_model::PRODUCT_TYPE_DEFAULT;
            $name = $item['name'];
            $hotel_name = $item['hotel_name'];
            $face_img = $item['face_img'];
            $price_package = $item['price_package'];

            $extra = $btns = $use_status = array();
            $app_data['asset_detail'][$index]['show_reserve'] = false;
            if($item['can_reserve'] != Product_package_model::CAN_T) { 
                $app_data['asset_detail'][$index]['show_reserve'] = true;
            }
            
            if(!empty($item['gift_id']) 
                || $item['openid'] != $this->openid) {
                $receiver = '好友';
                if(isset($this->datas['openids'][ $item['openid'] ])) {
                    $receiver = $this->datas['openids'][ $item['openid'] ];
                }
                $use_status[] = '被' . $receiver . '领取';
                $btns[] = array('action' => 'view_gift_detail', 'label' => '查看详情');
            } else {
                $expire_time = isset( $v['expiration_date'] ) ? strtotime( $v['expiration_date'] ) : NULL;
                if($expire_time && $expire_time > time()
                    && $this->datas['orderDetail']['refund_status'] == Sales_order_model::REFUND_PENDING) {
                    
                    if($item['can_mail'] == Product_package_model::CAN_T) {
                        $app_data['order_detail']['can_mail'] = true;
                    }
                    if($item['type'] == Product_package_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER) {
                        $btns[] = array('action' => 'voucher_detail', 'label' => '查看详情');
                        $btns[] = array('action' => 'voucher_sign', 'label' => '加入卡包');
                    }
                    if($item['can_reserve'] == Product_package_model::CAN_T) {
                        $btns[] = array('action' => 'package_booking', 'label' => '提前预约');
                    }
                    if($item['can_gift'] == Product_package_model::CAN_T) {
                        $btns[] = array('action' => 'package_send', 'label' => '赠送好友');
                        if($item['qty'] >= 2) {
                            $app_data['order_detail']['can_batch_gift'] = true;
                        }
                    }
                    if($item['can_pickup'] == Product_package_model::CAN_T) {
                        $btns[] = array('action' => 'package_usage', 'label' => '到店用券');
                    }

                    $use_status[] = '未使用';
                } else {
                    $use_status[] = '已过期';
                    $btns[] = array('action' => 'package_review', 'label' => '查看详情');
                }
            }

            $app_data['asset_detail'][$index]['view_use_status'] = $use_status;
            $app_data['asset_detail'][$index]['view_btns'] = $btns;
            $app_data['asset_detail'][$index]['view_extra'] = $extra;
        }

        $unset = array();
        $app_data['consumer_detail'] = $this->datas['consumerDetail'];
        foreach ($this->datas['consumerDetail'] as $index => $items) {

            if(empty($items)) { $unset[] = $index; continue; }

            foreach($items as $key => $item) {

                $extra = $btns = $use_status = array();

                $use_status[] = '已消费';
                if(in_array($item['consumer_id'], $this->datas['shipping_consumer_ids'])) {
                    $use_status[] = '已接单';
                    if(isset( $this->datas['shipping_detail'][ $v['consumer_id'] ]['status'])) {
                        $use_status[] = $this->datas['shipping_detail'][ $v['consumer_id'] ]['status'];
                    }
                } else {
                    if($p_type == Product_package_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER) {
                        $use_status[] = '已加入卡包';
                    }
                }

                if($item['openid'] == $this->openid) {
                    if($p_type == Product_package_model::PRODUCT_TYPE_PRIVILEGES_VOUCHER) {
                        $btns[] = array('action' => 'voucher_detail', 'label' => '查看详情');
                    } else {
                        if($item['status'] == $this->datas['can_mail_status'] 
                            && isset($this->datas['consumer_status'][$item['status']])) {
                            $btns[] = array('action' => 'shipping_detail', 'label' => '查看详情');
                        } else {
                            $btns[] = array('action' => 'package_review', 'label' => '查看详情');
                        }
                    }
                }

                $app_data['consumer_detail'][$index][$key]['view_use_status'] = $use_status;
                $app_data['consumer_detail'][$index][$key]['view_btns'] = $btns;
                $app_data['consumer_detail'][$index][$key]['view_extra'] = $extra;
            }
        }
        foreach ($unset as $index) { unset($app_data['consumer_detail'][$index]); }

        $app_data['gift_detail'] = array();
        foreach ($this->datas['giftSendOrderDetail'] as $index => $items) {
            
            if(empty($items)) { continue; }

            $extra = $btns = $use_status = array();
            $use_status[] = $this->datas['gift_status'][$items['status']];
            $btns[] = array('action' => 'gift_receive_list', 'label' => '查看详情');

            foreach ($items['items'] as $item) {
                if(empty($item) || $item == '') { continue; }
                $_tmp = $item;
                $_tmp['name'] = $name;
                $_tmp['hotel_name'] = $hotel_name;
                $_tmp['face_img'] = $face_img;
                $_tmp['price_package'] = $price_package;
                $_tmp['view_use_status'] = $use_status;
                $_tmp['view_btns'] = $btns;
                $_tmp['view_extra'] = $extra;
                $app_data['gift_detail'][] = $_tmp;
            }

        }

        $this->_view('order_detail', $app_data);

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

    //处理ajax请求，生成订单
    public function get_order_id_by_ajax()
    {
        //if(!is_ajax_request()) return;

        // 记录使用方式到session
        $u_type = $this->input->post('u_type', true);
        $this->load->library('session');
        $this->session->set_userdata('order_use_type', $u_type);

        $this->load->model('soma/Sales_order_model','SalesOrderModel');
        $this->load->helper('soma/package');

        $SalesOrderModel = $this->SalesOrderModel;

        $logTitle = "Order.php get_order_id_by_ajax :";

        $default_step= 'wxpay';
        $result = array(
            'status' => Soma_base::STATUS_FALSE ,
            'message' => '订单生成失败',
            'data'  => NULL,
            'step'  => $default_step, //一共2种阶段: wxpay跳转到微信支付，success即支付成功
        );

        $posts = $this->input->post();
        /**
         * 验证？
         */
        //validate($posts);?

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
                $productModel = $this->productPackageModel;
                foreach($productArr as $k => $v){
                    if( $v['date_type'] == $productModel::DATE_TYPE_STATIC ){
                        $time = time();
                        $expireTime = isset( $v['expiration_date'] ) ? strtotime( $v['expiration_date'] ) : NULL;
                        if( $expireTime && $expireTime < $time ){
                            //如果已经过了有效期，停止本次循环，并在此列表删除该商品
                            unset( $productArr[$k] );
                            continue;
                        }
                    }
                    $productArr[$k]['qty'] = $qtyArr[$v['product_id']];
                }
            break;
        }
        write_log($logTitle." ProductInfo Step 1" . json_encode($productArr));

        //组装数组
        $userInfo = array();
        $createTime = date("Y-m-d H:i:s",time());
        $groupUserInsert = $killsecUserInsert= false;
        write_log($logTitle." ProductInfo Step 2 settlement:" . $settlement);
        
        switch($settlement){
            case "killsec":
                //修正活动信息，user表填充，商品价格
                $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
                $KillsecModel =  $this->activityKillsecModel;
                
                $act_id= $this->input->post('act_id');
                $instance_id= $this->input->post('inid');
                $actDetail = $KillsecModel->find( array('inter_id'=>$this->inter_id, 'act_id'=>$act_id) );
                
                if( $actDetail['product_id'] != $posts['product_id'] ){
                    //防止手动输入product_id借助配额购买其他商品
                    $KillsecModel->clean_cache_after_payment($this->inter_id, $this->openid, $instance_id);
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '参数错误，请重新参加活动购买。';
                    $result['data'] = Soma_const_url::inst()->get_url('soma/package/package_detail',
                        array('pid'=> $actDetail['product_id'] ) );
                    $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                   // echo json_encode($result);die;
                
                } else {
                    //检测价格问题，低于此比例报警拦截
                    $this->_order_price_safe_check($settlement, $productArr, $actDetail, $KillsecModel );
                    foreach($productArr as $k => $v){
                        $productArr[$k]['price_package'] =  $actDetail['killsec_price'];
                        if( $productArr[$k]['qty']> $actDetail['killsec_permax'] ){
                            $productArr[$k]['qty'] = $actDetail['killsec_permax'];
                        }
                        //$productArr[$k]['qty'] = 1; //秒杀只可以买一件
                    }
                }
                
                //校验token
                /* $cache= $this->_load_cache();
                //$cache->redis->select_db(Activity_killsec_model::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
                $redis= $cache->redis->redis_instance();
                $cache_key= $this->activityKillsecModel->redis_token_key($instance_id, 'cache');
                $cache_hash= (array) json_decode($redis->hGet($cache_key, $this->openid));
                if( !isset( $cache_hash['token']) || $token != $cache_hash['token'] ){
                    //校验得到token是否伪造？
                } */
                
                //查user表，存在记录即可下单在5分钟以内，状态为 JOIN
                $instance_user= $KillsecModel->find_user_by_openid($this->inter_id, $this->openid, $instance_id );
                if( !$instance_user ){
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '提交订单已超时，订单已被释放';
                    $result['data'] = array();
                     $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                   // echo json_encode($result);die;
                    
                } else if( $instance_user['status']==Activity_killsec_model::USER_STATUS_PAYMENT ){
                    //重新进入支付
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message'] = '秒杀活动数量有限，请勿重复参加。';
                    $result['data'] = array();
                    $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                    //echo json_encode($result);die;
                    
                } else if( $instance_user['status']==Activity_killsec_model::USER_STATUS_ORDER ){
                    //重新进入支付
                    $result['status'] = Soma_base::STATUS_TRUE;
                    $result['message'] = '订单继续支付';
                    $result['data'] = array( 'orderId' => $instance_user['order_id'] );
                    $this->out_put_msg ( 1, '', $result,'order_get_orderid' );die;
                    //echo json_encode($result);die;
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
                $actDetail = $GrouponModel->groupon_detail($grouponDetial['act_id'],$this->inter_id);
                /*验证参团人数*/
                
                if( $actDetail['product_id'] != $posts['product_id'] ){
                    //防止手动输入product_id借助配额购买其他商品
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '参数错误，请重新参加活动购买。';
                    $result['data'] = Soma_const_url::inst()->get_url('soma/package/package_detail',
                        array('pid'=> $actDetail['product_id'] ) );
                    $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                   // echo json_encode($result);die;
                
                }
                if($grouponDetial['status'] == $GrouponModel::GROUP_STATUS_FINISHED){ //支付过程中已经成团
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '真遗憾，你来晚啦~~别人已经完成这个拼团';
                    $result['data'] = array(
                    );
                    $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                   // echo json_encode($result);
                    return;
                }
                if($grouponDetial['join_count'] >= $actDetail['group_count']){ //有用户已经占团
                    $result['status'] = Soma_base::STATUS_FALSE;
                    $result['message']  = '真遗憾，你来晚啦~~前面已经有用户正在支付了';
                    $result['data'] = array(
                    );
                    $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                   // echo json_encode($result);
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

        if(empty($productArr)){
            $result['message'] = '产品参数有误！';
            $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
           // echo json_encode($result);
            return;
            
        } else {
            //先获取orderID
//            array_push($productArr,$product);
            $SalesOrderModel->business = $business;
            $SalesOrderModel->settlement = $settlement;
            $customer = new Sales_order_attr_customer($this->openid);

            // 2016-10-27 订单下单时保存用户信息到订单表 fengzhongcheng
            $customer->name = $posts['name'];
            $customer->mobile = $posts['phone'];

            if($posts['name'] == '' || empty($posts['name'])
                || $posts['phone'] == '' || empty($posts['phone'])) {
                $result['message'] = '联系人信息不能为空';
                $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
            }
            
            foreach ($productArr as $product) {
                if($product['qty'] <= 0 || $product['qty'] > 200) {
                    $result['message'] = '购买数量不能超过200';
                    $this->out_put_msg ( 2, $result['message'], $result,'order_get_orderid' );die;
                }
            }

            $customer->openid = $this->openid;
            $SalesOrderModel->settlement = $settlement;
            $SalesOrderModel->customer= $customer;
            $SalesOrderModel->saler_id= $this->input->post('saler');
            $SalesOrderModel->fans_saler_id= $this->input->post('fans_saler');
            $SalesOrderModel->killsec_instance = $this->input->post('inid');
            $SalesOrderModel->shipping= 0;
            
            $discount_array= array();
            $this->load->library('Soma/Api_member');
            $this->load->model('soma/Sales_order_discount_model');
            $this->load->model('soma/Sales_rule_model');
            $this->load->model('soma/Sales_payment_model');
            
/** 优惠券累计：***********************/
            //根据页面传过来的card_id通过会员接口读取扣减总额，组装discount数组 @see Sales_order_discount_model 
            if( isset($posts['mcid']) && !empty($posts['mcid']) ){
                $payment_extra= Sales_payment_model::PAY_TYPE_CP;
                // var_dump( $posts['mcid'] );die;
                //luguihong 20161107 优惠券批量使用，这里需要改成多个mcid 2
                $member_card_ids = $posts['mcid'];
                if( !is_array( $member_card_ids ) ){
                    $member_card_ids = explode(',',$member_card_ids);
                }
                $api= new Api_member($this->inter_id);
                $result = $api->conpon_sign_list( $this->openid );
                $result= (array) $result['data'];
                $d_type= Sales_order_discount_model::TYPE_COUPON;

                $info = array();
                $coupon_count = 0;
                $get_card_id = 0;
                foreach( $result as $k=>$v ){
                    $sv = (array)$v;
                    if( in_array( $sv['member_card_id'], $member_card_ids ) ){
                        $info[$sv['member_card_id']] = $sv + array('discount_type'=> $d_type );
                        $coupon_count++;

                        //判断是否是同一个card_id，否则返回空。只能同种券同一个card_id的券才可以叠加使用
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
                // var_dump( $info );die;
                //检测购买数量和优惠券的数量  购买数量>=优惠券数量
                $bug_qty = isset( $posts['qty'] ) ? $posts['qty'] : 0;
                if( !is_array( $bug_qty ) ){
                    $bug_qty = explode(',',$bug_qty);
                }
                $bug_count = current( $bug_qty );
                if( $coupon_count > $bug_count ){
                    Soma_base::inst()->show_exception('选择优惠券出错');
                }

                $discount_array[$d_type] = $info+ array('discount_type'=> $d_type );
                // var_dump( $discount_array[$d_type] );die;
                /*
                    $member_card_id= isset($posts['mcid'])? $posts['mcid']: '';
                    $api= new Api_member($this->inter_id);
                    $result= $api->get_token();
                    $api->set_token($result['data']);
                    $info= $api->conpon_sign_info($member_card_id, $this->openid);
                    $info= (array) $info['data'];
                
                    //条件： openid归属匹配，进行以下处理
                    if( isset($info['open_id']) && $info['open_id']==$this->openid ){
                        $d_type= Sales_order_discount_model::TYPE_COUPON;
                        $discount_array[$d_type]= $info+ array('discount_type'=> $d_type );
                    }
                */
            }

/** 储值优惠:  ***********************/
            if( isset($posts['quote_type']) && isset($posts['quote']) 
                    && $posts['quote_type']== Sales_rule_model::RULE_TYPE_BALENCE ){
                $payment_extra= Sales_payment_model::PAY_TYPE_CZ;
                
                if( !isset($api) ){
                    $api= new Api_member($this->inter_id);
                    $result= $api->get_token();
                    $api->set_token($result['data']);
                }
                $info= $api->balence_info( $this->openid );
            
                if( $info['data']>= $posts['quote'] ){
                    $d_type= Sales_order_discount_model::TYPE_BALENCE;
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
            $SalesOrderModel->discount= $discount_array;//优惠券批量使用，这里需要改成多个mcid 3
            $SalesOrderModel->product= $productArr;
            $SalesOrderModel->hotel_id = $hotel_id;
            $SalesOrderModel = $SalesOrderModel->order_save($business, $this->inter_id);  //订单保存 <-失败
            $orderId= $SalesOrderModel->order_id;
            
            if($orderId){
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
                    // $where = array();
                    // $where['openid'] = $this->openid;
                    // $customer_info = $SalesOrderModel->get_customer_contact( $where );
                    // if( $customer_info ){
                    //     if( $customer_info['name'] == $contact['name'] && $customer_info['mobile'] == $contact['mobile'] ){
                    //         //姓名和电话都没改变
                    //     } else {
                    //         //更新
                    //         $where = array();
                    //         $where['openid'] = $this->openid;
                    //         $data = array();
                    //         $data['name'] = $contact['name'];
                    //         $data['mobile'] = $contact['mobile'];
                    //         $SalesOrderModel->update_customer_contact( $where, $data );
                    //     }
                    // } else { 
                            $SalesOrderModel->save_customer_contact($contact, array('openid'=> $this->openid ) );
                    // }
                }
                //电话信息插入end

                // 交易快照 luguihong 20161031
                $this->load->model('soma/Sales_order_product_record_model','ProductRecordModel');
                $ProductRecordModel = $this->ProductRecordModel;
                $ProductRecordModel->order = $SalesOrderModel;
                $ProductRecordModel->status = Soma_base::STATUS_FALSE;
                $ProductRecordModel->product_record_save( $this->inter_id );

                $result['status'] = Soma_base::STATUS_TRUE;
                $result['message']  = '订单保存成功';
                $result['data'] = array( 'orderId' => $orderId );
                $result['step'] = $default_step;

                if($productArr[0]['type'] == Product_package_model::PRODUCT_TYPE_BALANCE) {
                    $result['step'] = 'balance_pay';
                }
            };

            //订单金额为0直接处理为支付
            //订单金额小于0.01直接支付处理
            $grand_total= $SalesOrderModel->m_get('grand_total');
            //计算优惠券，积分的时候，会出现小数后几位
            if( $grand_total < 0.005 ){
                $CI = & get_instance();
                $log_data= array();
                $log_data['paid_ip']= $CI->input->ip_address();
                $log_data['paid_type']= isset($payment_extra)? $payment_extra: Sales_payment_model::PAY_TYPE_HD;
                $log_data['order_id']= $orderId;
                $log_data['openid']= $this->openid;
                $log_data['business']= $business;
                $log_data['settlement']= $settlement;
                $log_data['inter_id']= $this->inter_id;
                $log_data['hotel_id']= $hotel_id;
                $log_data['grand_total']= $grand_total;
                $log_data['transaction_id']= '';

                //暂时无法记录准确的支付方式所占的金额
                //$this->Sales_payment_model->save_payment($log_data, NULL);
            
                $SalesOrderModel->order_payment($log_data );
                $SalesOrderModel->order_payment_post($log_data );
                
                $result['status'] = Soma_base::STATUS_TRUE;
                $result['message']  = '订单支付成功';
                $result['data'] = array( 'orderId' => $orderId );
                $result['step'] = 'success';
                $url = Soma_const_url::inst()->get_payment_package_success(array('id'=>$this->inter_id, 'order_id' => $orderId));
                $result['success_url'] = $url;

                $bType = $this->input->get('bType', true);
                // 月饼说分流
                if($bType) {
                    $order_detail = $SalesOrderModel->m_data();
                    $result['success_url'] = $SalesOrderModel->success_payment_path($this->inter_id, $bType, $order_detail, $url);
                }

            }
            
            // 储值支付
            if($result['step'] == 'balance_pay') {
                
                $bpay_passwd = $this->input->post('bpay_passwd', true);
                if(!$bpay_passwd) { $balance_pay = ''; }
                $pay_res = $this->balance_pay($this->inter_id, $this->openid, $bpay_passwd, $orderId);
                $payment = json_decode($pay_res, true);

                if($payment['status'] == Soma_base::STATUS_TRUE) {

                    $CI = & get_instance();
                    $log_data= array();
                    $log_data['paid_ip']= $CI->input->ip_address();
                    $log_data['paid_type']= Sales_payment_model::PAY_TYPE_CZ;
                    $log_data['order_id']= $orderId;
                    $log_data['openid']= $this->openid;
                    $log_data['business']= $business;
                    $log_data['settlement']= $settlement;
                    $log_data['inter_id']= $this->inter_id;
                    $log_data['hotel_id']= $hotel_id;
                    $log_data['grand_total']= $grand_total;
                    $log_data['transaction_id']= '';
                
                    $SalesOrderModel->order_payment($log_data );
                    $SalesOrderModel->order_payment_post($log_data );
                    $this->Sales_payment_model->save_payment($log_data, NULL);  

                    $result['status'] = Soma_base::STATUS_TRUE;
                    $result['message']  = '订单支付成功';
                    $result['data'] = array( 'orderId' => $orderId );
                    $result['step'] = 'success';
                    $url = Soma_const_url::inst()->get_payment_package_success(array('id'=>$this->inter_id, 'order_id' => $orderId));
                    $result['success_url'] = $url;
                } else {
                    Soma_base::inst()->show_exception($payment['message']);
                }

            }
            $this->out_put_msg ( 1, $result['message'], $result,'order_get_orderid' );die;
            //echo json_encode($result);
            return;
        }
    }

    /**
     * 储值支付
     *
     * @param      <type>  $inter_id  The inter identifier
     * @param      <type>  $open_id   The open identifier
     * @param      <type>  $passwd    The passwd
     * @param      <type>  $order_id  The order identifier
     *
     * @return     <type>  ( description_of_the_return_value )
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
                    return json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '储值余额不足！'));
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
                    return json_encode(array('status' => Soma_base::STATUS_TRUE, 'message' => ''));
                }
            }
        } catch (Exception $e) {
            // 日志
        }

        return json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '订单信息错误！'));
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

        $this->datas = array(
                'record_detail'=>$record_detail,
            );
        $header = array(
            'title'=> '交易快照'
        );
        $this->_view("header",$header);
        $this->_view("photo_shot",$this->datas);
    }


}
