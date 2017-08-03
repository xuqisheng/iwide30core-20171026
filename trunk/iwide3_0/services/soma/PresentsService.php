<?php

namespace App\services\soma;

use App\services\BaseService;
use Soma_base;
use Soma_const_url;

/**
 * Class PresentsService
 * @package App\services\soma
 *
 */
class PresentsService extends BaseService
{

    /**
     *
     * @return PresentsService
     * @author renshuai  <renshuai@jperation.cn>
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }


    public static function save_order($business, $inter_id)
    {

    }


    /**
     * *获取赠送订单下领取用户列表
     * @param $inter_id
     * @param $gift_id
     * @param $give_openid
     * @param array $orders
     * @param string $business
     * @return array
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public static function get_gift_received_users( $inter_id , $gift_id , $give_openid ,$orders = array() , $business = 'package'){
        $filter = array();
        $receiveUsersArr = array();

        $filter['openid_give'] = $give_openid;
        self::getInstance()->getCI()->load->model('soma/Gift_order_model');
        $giftOrderModel = self::getInstance()->getCI()->Gift_order_model;
        $giftOrderModel = $giftOrderModel->load($gift_id);
        $receiveOrders = $giftOrderModel->get_receiver_list($inter_id, $gift_id, $filter);

        if (empty($orders)) {
            $orders = $giftOrderModel->get_order_detail($business, $inter_id);
        }

        if (empty($orders)) {
            return array();
        }

        //数据格式整理
        $openids = array();
        if ($orders['is_p2p'] == $giftOrderModel::GIFT_TYPE_P2P) {      //对私
            $openids = !empty($orders['openid_received']) ? array($orders['openid_received']) : array();
        } elseif ($orders['is_p2p'] == $giftOrderModel::GIFT_TYPE_GROUP) {  //群发
            foreach ($receiveOrders as $k => $v) {
                $openids[] = $v['openid'];
            }
        }

        self::getInstance()->getCI()->load->model('wx/Publics_model');
        $publicModel = self::getInstance()->getCI()->Publics_model;
        $openid_data = !empty($openids) ? $publicModel->get_fans_info_byIds($openids) : array();
        $openid_hash = $giftOrderModel->array_to_hash($openid_data, 'nickname', 'openid');
        $headimg_hash = $giftOrderModel->array_to_hash($openid_data, 'headimgurl', 'openid');

        $receiveUserFiled = ['receiver_id', 'gift_id', 'inter_id', 'openid_give', 'hotel_id', 'total_qty', 'source', 'remote_ip', 'status'];

        foreach ($receiveOrders as $k => $v) {
            //填充openid昵称
            if (array_key_exists($v['openid'], $openid_hash)) {
                $receiveOrders[$k]['openid_nickname'] = $openid_hash[$v['openid']];
            }
            if (array_key_exists($v['openid'], $headimg_hash)) {
                $receiveOrders[$k]['openid_headimg'] = $headimg_hash[$v['openid']];
            }

            $receiveOrders[$k]['get_time'] = date("m-d H:i", strtotime($receiveOrders[$k]['get_time']));
            $receiveUser = new \App\libraries\Support\Collection($receiveOrders[$k]);
            $receiveUser = $receiveUser->except($receiveUserFiled);
            $receiveUsersArr[] = $receiveUser->toArray();

        }

        return $receiveUsersArr;

    }


    /**
     * *赠送订单可领取数量验证，TRUE可继续领取
     * @param $inter_id
     * @param $gift_id
     * @param array $gift_data
     * @param array $receive_list
     * @param string $business
     * @return bool
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public static function validation_of_received_status( $inter_id , $gift_id, $gift_data = array() ,$receive_list = array() , $business ='package'  ){
        if(!empty($gift_data) || !is_array($gift_data)){
            self::getInstance()->getCI()->load->model('soma/Gift_order_model');
            $giftOrderModel = self::getInstance()->getCI()->Gift_order_model;
            $giftOrderModel  = $giftOrderModel->load($gift_id);
            $gift_data = $giftOrderModel->get_order_detail($business, $inter_id);
        }

        if( $gift_data['is_p2p']== Soma_base::STATUS_TRUE ){ //私发的情况下，只有接收者就不可以再领取
            if(!empty($gift_data['openid_received']))
                return FALSE;
            else
                return TRUE;
        }else{ //群发的时候

           //参数不全的时候
           if(empty($receive_list) || !is_array($receive_list)){
               $receive_list = self::get_gift_received_users( $inter_id , $gift_id ,$gift_data['openid_give'],$gift_data);
           }

            $receive_count = count( $receive_list );

            if( $gift_data['total_qty'] == $receive_count)
                return FALSE; //没有数量了
            else if( $gift_data['total_qty'] > $receive_count )
                return TRUE;  //还有数量
            else
                return FALSE; //其他情况

        }
    }


    public static function usage_btn_format($inter_id, $gift_id ,$item , $business ,$giftOrderModel){
        /*提前预约*/
        if($item['can_reserve'] == Soma_base::STATUS_TRUE){
            $url = Soma_const_url::inst()->get_url('*/consumer/package_booking', array('aiid'=>$item['item_id'], 'aiidi'=>0,'id'=>$inter_id,'bsn'=>$business ) );
            $returnData[] = array(
                'type'  => 'reserve',
                'url'   =>   $url,
                'label' =>  '提前预约'
            );
        }

        /*可邮寄*/
        if($item['can_mail']  == Soma_base::STATUS_TRUE){
            $param = array();
            $param['id'] = $inter_id;
            $param['gid'] = $gift_id;
            $param['bsn'] = $business;
            $mail_url = Soma_const_url::inst()->get_soma_shipping( $param );//邮寄
            $returnData[] = array(
                'type'  => 'mail',
                'url'   =>   $mail_url,
                'label' =>   '邮寄到家'
            );
        }

        /*赠送好友*/
        $send_friend_url = Soma_const_url::inst()->get_url( '*/*/package_send',
            array(
                'id'=>$inter_id,
                'group'=>Soma_base::STATUS_TRUE,
                'aiid'=>$item['item_id'],
                'bsn'=>$business,
                'send_from' => $giftOrderModel::SEND_FROM_GIFT,
                'send_order_id' => $gift_id,
            )
        );
        $returnData[] = array(
            'type'  => 'gift',
            'url'   =>   $send_friend_url,
            'label' => '送给朋友'
        );



        /*到店用券*/
        if($item['can_pickup'] == Soma_base::STATUS_TRUE){
            $url = Soma_const_url::inst()->get_url('*/*/package_detail',
                array(
                    'gid'=>$gift_id,
                    'id'=>$inter_id,
                    'bsn'=>$business,
                    'send_from' => $giftOrderModel::SEND_FROM_GIFT,
                    'send_order_id' => $gift_id,
                )
            );
            $returnData[] = array(
                'type'  => 'pickup',
                'url'   =>   $url,
                'label' =>  '到店用券'
            );
        }

        /*卡包*/
        if($item['type'] == $giftOrderModel::PRODUCT_TYPE_PRIVILEGES_VOUCHER){
            $url = Soma_const_url::inst()->get_url('*/*/package_detail',
                array(
                    'gid'=>$gift_id,
                    'id'=>$inter_id,
                    'bsn'=>$business,
                    'send_from' => $giftOrderModel::SEND_FROM_GIFT,
                    'send_order_id' => $gift_id,
                )
            );
            $returnData[] = array(
                'type'  => 'cardpack',
                'url'   =>   $url,
                'label' =>  '加入卡包'
            );
        }

        /*现在订房*/
        self::getInstance()->getCI()->load->model('soma/product_package_model');
        $productionPackageModel = self::getInstance()->getCI()->product_package_model;
        if($item['can_wx_booking'] == $productionPackageModel::CAN_T ){
            $url = Soma_const_url::inst()->get_url('*/booking/wx_select_hotel',
                array(
                    'aiid'=>$item['item_id'],
                    'oid'=>$item['order_id'],
                    'aiidi'=>0,  //TODO 不确定这个值是什么意思
                    'id'=>$inter_id,
                    'bsn'=>$business
                )
            );
            $returnData[] = array(
                'type'  => 'wx_booking',
                'url'   =>   $url,
                'label' =>  '现在订房'
            );
        }


        return $returnData;
    }


}