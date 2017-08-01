<?php

use App\models\soma\Activity_killsec_user;
use App\services\Result;
use App\services\soma\express\ExpressProvider;
use App\services\soma\KillsecService;
use App\services\soma\order\OrderProvider;
use App\services\soma\OrderService;
use App\services\soma\WxService;
use App\services\soma\CronService;
use App\services\soma\ScopeDiscountService;

/**
 * Class Service
 * @author renshuai  <renshuai@mofly.cn>
 *
 *
 * for test
 */
class Service extends MY_Front_Soma
{

    public function test()
    {
        var_dump(\App\libraries\Support\Tool::getUserIP());
    }


    /**
     *  ==========================
     *  ScopeDiscountService tests
     *  ==========================
     */


    public function check_stock()
    {
        $scope_product_link_id  = $this->input->get('splid');
        $num = $this->input->get('num');
        
        $result = ScopeDiscountService::getInstance()->checkStock($this->inter_id, $this->openid, $scope_product_link_id, $num);
        $this->json($result);
    }

    public function reduce_stock()
    {
        $scope_product_link_id = 1;
        $num = 2;
        $result = ScopeDiscountService::getInstance()->updateStock($this->inter_id, $this->openid, $scope_product_link_id, $num, '-');
        $this->json($result);
    }


    public function user_discount()
    {
        $result = ScopeDiscountService::getInstance()->getUserScopeDiscount($this->inter_id, $this->openid);
        $this->json($result);
    }

    public function appendScopeDiscount()
    {
        $this->load->model('soma/Product_package_model', 'productPackageModel');

        $productId = $this->input->get('pid');

        $productDetail = $this->productPackageModel->get_product_package_detail_by_product_id($productId, $this->inter_id);
        $products = array($productDetail);

        ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->current_inter_id, $this->openid, false);
        $productDetail = $products[0];
        $this->json($productDetail);
    }

    public function useScopeDiscount()
    {
        $this->load->model('soma/Product_package_model', 'productPackageModel');

        $productId = $this->input->get('pid');
        $psp_sid = $this->input->get('psp_sid');

        $productDetail = $this->productPackageModel->get_product_package_detail_by_product_id($productId, $this->inter_id);

        $scope_product_link = ScopeDiscountService::getInstance()->useScopeDiscount($this->inter_id, $this->openid, $productDetail, $psp_sid);

        $this->json($scope_product_link);
    }

    public function updateStock()
    {
        $scope_product_link_id = $this->input->get('spid');
        $qty  = $this->input->get('qty');
        $return_result = ScopeDiscountService::getInstance()->updateStock($this->inter_id, $this->openid, $scope_product_link_id, $qty, '-');

        $this->json($return_result);
    }




    public function request()
    {
        $inter_id = 'a450089706';
        $openid = 'o9VbtwwUedrHzhXFSfegtSFMIKtU';
        $uri = $_SERVER['SERVER_NAME'] . "/api/ClubApi/getSomaClub?inter_id=$inter_id&openid=$openid";
        $result = ScopeDiscountService::getInstance()->request($uri);
        $this->json($result);

    }


    /**
     *  ==========================
     *  OrderService tests
     *  ==========================
     */
    public function order_create()
    {
        $posts = array(
            'business' => 'package',
            'settlement' => OrderProvider::NORMAL_SETTLEMENT,
            'hotel_id' => 180,
            'qty' => array(
                '12029' => 1
            ),
            'psp_setting' => [
                '12029' => -1
            ],
            'product_id' => 12029,
            'name' => '123',
            'phone' => 18620462480,
            'saler' => 0,
            'fans_saler' => 0,
            'inid' => 0,
            'mcid' => '',
            'u_type' => 1
        );

        $result = OrderService::getInstance()->create($posts);

        if ($result->getStatus() === Result::STATUS_FAIL) {
            echo 'fail';
        } else {
            $data = $result->getData();
            $salesOrderModel = $data['salesOrderModel'];
            $payChannel = $data['payChannel'];

            // 积分支付
            if($payChannel === 'point_pay') {
                echo $payChannel;
            } elseif ($payChannel === 'balance_pay') {
                echo $payChannel;
            }
        }

    }


    /**
     *  ==========================
     *  CronService tests
     *  ==========================
     */

    public function killsec_notice_sending()
    {
        $time = microtime(true);
        CronService::getInstance()->sendKillsecBeginNotice();
        $etime = microtime(true);

        echo $etime - $time;
        $this->output->enable_profiler(true);
    }

    public function send()
    {
        $result = CronService::getInstance()->send();

        $this->json($result);

    }

    /**
     *  ==========================
     *  WxService tests
     *  ==========================
     */
    public function qrcode()
    {
        echo WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE)->toJson();
    }

    public function getApp()
    {
        $app = WxService::getInstance()->getApp();
        echo $app->access_token->getToken();

    }

    public function subscribemsg()
    {
        $interID = $this->inter_id;
        $scene = 123;
        $url = site_url('/soma/third_api/callback_temp_msg');
        $reserved = random_string();
        $result = \App\services\common\WxTempMsgService::getInstance()->getGuideUrl($interID, $scene, $url, $reserved);
        echo $this->json([$result]);

    }
    /**
     *  ==========================
     *  express tests
     *  ==========================
     */
    public function shunfeng_token()
    {
        $provider = (new ExpressProvider())->resolve(ExpressProvider::TYPE_SF);

        $result = $provider->accessToken->getToken();

        echo $result;
    }
    public function shunfeng_make()
    {
        $post = array(
            'order_id' => 'JFKWEBELIEVE1000582690',
            'shipping_order' => '0',
            'shipping_fee' => '0.02',
            'address' => '广东省佛山三水区测试',
            'contacts' => '小米',
            'phone' => '13553540712',
            'name' => 'iphon54',
//            'shipping_id' => '562',
            'remark' => '备注629',
            'address_id' => 10249,
            'inter_id' => $this->inter_id,
            'openid' => $this->openid
        );

        $provider = (new ExpressProvider())->resolve(ExpressProvider::TYPE_SF);

        $result = $provider->createShippingOrder($post);
        $this->json($result->toArray());
    }

    /**
     *  ==========================
     *  KillsecService tests
     *  ==========================
     */

    public function getRedisKeys()
    {
        $interID = $this->inter_id;
        $openid = $this->openid;
        $instanceID = 3;

        $result = KillsecService::getInstance()->getRedisKeys($interID, $instanceID, $openid);

        $this->json($result);
    }

    public function initData()
    {
        $actID = 1;

        $result = KillsecService::getInstance()->initData($actID);

        $this->json($result);
    }

    public function updateScheduleCycleTime()
    {
        KillsecService::getInstance()->updateScheduleCycleTime();
        $this->json(1);
    }

    public function getInfo()
    {
        $productID = $this->input->get('pid');
        $result = KillsecService::getInstance()->getInfo($productID);
        $this->json($result);
    }


    public function getOpporunity()
    {
        $instanceID = 2491;
        $interID = $this->inter_id;

        $result = KillsecService::getInstance()->getOpporunity($interID, $instanceID, $this->openid);

        $this->json($result->toArray());
    }

    public function payed()
    {
        $orderID = '1000010736';
        $openid = 'o9VbtwwUedrHzhXFSfegtSFMIKtU';

        $result = KillsecService::getInstance()->payed($orderID, $openid, 1);

        $this->json($result->toArray());
    }

    public function disable()
    {
        $actID = 1;
        $actID = 127;

        $result = KillsecService::getInstance()->disable($actID);
        $this->json($result->toArray());
    }


    public function used_count()
    {
        $instanceID = 2798;
        $userModel = new Activity_killsec_user();
        $count = $userModel->getUsedCount($this->inter_id, $this->openid, $instanceID);
        $this->json($count);
    }
    public function order_valid()
    {
       \App\libraries\Support\Log::error('233');
        echo 1;
//        $redis = KillsecService::getInstance()->getRedis();
//        echo $redis->get('a');die;
//        $instanceID = 2798;
//        $result = KillsecService::getInstance()->orderValid($instanceID, $this->inter_id, $this->openid);
//
//        $this->json($result->toArray());
//        echo json_encode($result->toArray());die;
    }
}