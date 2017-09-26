<?php
use App\libraries\Iapi\CommonLib;

/**
 * Class MY_Front_Soma_Iapi
 * @author renshuai  <renshuai@mofly.cn>
 *
 *
 * @property Shard_config_model $shardConfigModel
 */
class MY_Front_Soma_Iapi extends MY_Front_Iapi
{
    /**
     * 常用页面链接
     * @var array
     */
    public $link;

    public $public_info;

    /**
     * MY_Front_Soma_Iapi constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->public_info = $this->public;

        $this->current_inter_id = $this->inter_id;

        $this->_initLink();
    }

    /**
     * @author renshuai  <renshuai@jperation.cn>
     */
    private function _initLink()
    {

//        $redis = $this->get_redis_instance();
//        $layout = $redis->get($this->inter_id.'_is_layout');
//        $tkId = $redis->get('tkid');
//        $brandName = $redis->get('brandname');


        $packageService = \App\services\soma\PackageService::getInstance();

        $layout = $packageService->getParams()['layout'];
        $tkId = $packageService->getParams()['tkid'];
        $brandName = $packageService->getParams()['brandname'];

        $this->link = array(
            'home' => site_url('soma/package/index') . "?id=" . $this->inter_id."&tkid=".$tkId.'&brandname='.$brandName.'&layout='.$layout,
            'product_link' => site_url('soma/package/package_detail') . "?id=" . $this->inter_id .'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName. '&pid=',
            'order_link' => site_url('soma/order/my_order_list') . "?id=" . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            'center_link' => site_url("membervip/center") . "?id=" . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            'prepay_link' => site_url("soma/package/package_pay").'?btype=package'.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            // 订单中心
            'my_order_list' => site_url('iapi/soma/order/index') . "?" . http_build_query(['id' => $this->inter_id, 'type' => '', 'tkid' => $tkId, 'brandname' => $brandName,
                    'layout' => $layout]), // 我的订单(全部、待使用、已完成)
            'my_gift_list' => site_url('iapi/soma/order/gift_list') . "?id=" . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName, // 我的礼物
            'detail_link' => site_url('soma/order/order_detail') . "?id=" . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName .'&bsn=package'. '&oid=', //  订单详情
            'delete_order_link' => site_url('iapi/soma/order/index') . "?id=" . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName, // 删除 需要参数 oid=
            // 我的礼物
            'package_received' => site_url('soma/gift/package_received') . '?id=' . $this->inter_id .'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName. '&gid=', // 礼物详情
            'package_list_send' => site_url('soma/gift/package_list_send') . '?id=' . $this->inter_id . '&fans_saler='.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName, // 送出礼物
            'package_list_received' => site_url('soma/gift/package_list_received') . '?id=' . $this->inter_id .'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName. '&fans_saler=', // 收到礼物
            // 订单明细
            'package_booking' => site_url('soma/consumer/package_booking') . '?' . http_build_query([
                    'id' => $this->inter_id,
                    'aiid' => '%s',
                    'aiidi' => 0,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout
                ]), // 预约
            'package_usage' => site_url('soma/consumer/package_usage') . '?' . http_build_query([
                    'id' => $this->inter_id,
                    'aiid' => '%s',
                    'aiidi' => 0,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout
                ]), // 验卷
            'package_send' => site_url('soma/gift/package_send') . '?' . http_build_query([
                    'id' => $this->inter_id,
                    'aiid' => '%s',
                    'aiidi' => 0,
                    'group' => '2',
                    'send_from' => '1',
                    'send_order_id' => '',
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout
                ]), // 转赠
            'package_detail' => site_url('soma/package/package_detail') . '?' . http_build_query([
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'id' => $this->inter_id,
                    'pid' => '',
                ]), // 订单详情
            'show_shipping_info' => site_url('soma/consumer/show_shipping_info') . '?' . http_build_query([
                    'bsn' => 'package',
                    'id' => $this->inter_id,
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'oid' => '',
                    'gid' => '',
                ]), // 邮寄
            // 卷码相关
            'get_received_list' => site_url('soma/gift/get_received_list') . '?' . http_build_query([
                    //'gid' => '', // iwide_soma_gift_order_1001.gift_id  || iwide_soma_gift_order_receiver_1001.gift_id
                    'id' => $this->inter_id,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout
                ]), // 卷码 - 已赠送（赠送）
            'shipping_detail' => site_url('soma/consumer/shipping_detail') . '?' . http_build_query([
                    //'spid' => '', // iwide_soma_consumer_shipping.shipping_id
                    'id' => $this->inter_id,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout
                ]), // 卷码 - 已邮寄（邮寄）
            'package_review' => site_url('soma/consumer/package_review') . '?' . http_build_query([
                    //'ciid' => '',
                    'id' => $this->inter_id,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout
                ]), // 卷码 - 已使用（消费）
            'refund_index_link' => site_url('soma/refund/apply') . "?id=" . $this->inter_id .'&bsn=package'.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName. '&oid=',
            //微信支付
            'wx_pay' => site_url('wxpay/soma_pay').'?id=' . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            //威富通支付
            'wft_pay' => site_url('Wftpay/soma_pay').'?id=' . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            //直接支付
            'already_pay' => site_url('soma/package/success').'?id=' . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,

            // 微信订房 - 选酒店房型 [页面]
            'wx_select_hotel_link' => site_url('soma/booking/wx_select_hotel') . '?' . http_build_query([
                    'id' => $this->inter_id,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'aiid' => '',
                    'aiidi' => '',
                    'oid' => '',
                ]),
            'wx_select_hotel' => site_url('soma/booking/wx_select_hotel') . '?' . http_build_query([
                    'id'=>$this->inter_id,
                    'aiidi'=>0,
                    'aiid'=>'%s',
                    'oid'=>'%s',
                    'bsn'=>'%s'
                ]),
            // 微信订房 - 选酒店房型 [api]
            'wx_select_hotel_api_link' => site_url('iapi/soma/order/wx_select_hotel') . '?' . http_build_query([
                    'id' => $this->inter_id,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'aiid' => '',
                    'aiidi' => '',
                    'oid' => ''
                ]),

            // 微信订房 - 价格日历
            'select_hotel_time' => site_url('soma/booking/select_hotel_time') . '?' . http_build_query([
                    'id' => $this->inter_id,
                    'bsn' => 'package',
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'aiid' => '',
                    'aiidi' => '',
                    'oid' => '',
                    'hid' => '',
                    'rmid' => '',
                    'cdid' => '',
                ]),

            // 下单成功跳转的页面
            'pay_success_stay_link' => site_url('soma/package/pay_success_stay') .'?'. http_build_query(['id' => $this->inter_id,'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,'oid' => '']),

            // 提交微信订房
            'booking_link' => site_url('soma/booking/post_booking') . '?' . http_build_query([
                    'bsn' => 'package',
                    'id' => $this->inter_id,
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'fans_saler' => '',
                ]),
            //提交微信订房，下单成功跳转的页面
            'booking_success' => site_url('soma/booking/success').'?'.http_build_query(
                [
                    'id' => $this->inter_id,
                    'bsn' => '',
                    'bid' => ''
                ]
                ),
            //退款首页
            'refund_index' => site_url('soma/refund/apply') . "?id=" . $this->inter_id.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName .'&bsn=package'.'&oid=',
            //退款详情 //http://credit.iwide.cn/index.php/soma/refund/detail?&oid=1000013037&saler=35
            'refund_detail' => site_url('soma/refund/detail') . "?id=" . $this->inter_id .'&bsn=package'.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            //二维码礼包详情页
            'qrcode_and_gift_detail'=>site_url('soma/GiftDelivery/redirect_gift_detail') . "?id=" . $this->inter_id .'&bsn=package'.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            //礼包礼包
            'gift_list'=>site_url('soma/GiftDelivery/gift_list') . "?id=" . $this->inter_id .'&bsn=package'.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            //确认领取礼包详情页
            'receive_gift_detail'=>site_url('soma/GiftDelivery/receive_gift_detail') . "?id=" . $this->inter_id .'&bsn=package'.'&layout='.$layout."&tkid=".$tkId.'&brandname='.$brandName,
            'received_gift_empty' => site_url('soma/gift/received_gift_empty').'?'. http_build_query([
                    'id' => $this->inter_id,
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                    'bsn' => '%s',
                    'gid' => '%s'

                ]),
            //订房订单详情
            'hotel_order_info' => site_url('hotel/hotel/myorder').'?'.http_build_query([
                    'id' => $this->inter_id,
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                ]),
            //订房订单列表
            'hotel_order_list' => site_url('hotel/hotel/myorder').'?'.http_build_query([
                    'id' => $this->inter_id,
                    'tkid' => $tkId,
                    'brandname' => $brandName,
                    'layout' => $layout,
                ]),
        );
    }

    /**
     * @param $method
     * @param array $params
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     *
     */
    public function _remap($method, $params = array())
    {
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $method = "{$requestMethod}_{$method}";

//        if (ENVIRONMENT === 'production' && !$this->input->is_ajax_request()) {
//            show_error('please use ajax', 400);
//        }

        if (method_exists($this, $method))
        {
            //数据库链接
            $this->load->somaDatabase($this->db_soma);
            $this->load->somaDatabaseRead($this->db_soma_read);
            //初始化数据库分片配置
            $this->load->model('soma/shard_config_model', 'shardConfigModel');
            $this->db_shard_config = $this->shardConfigModel->build_shard_config($this->inter_id);

            return call_user_func_array(array($this, $method), $params);
        } else {
            show_404('api not found');
        }
    }

    /**
     * @param $result
     * @param $msg
     * @param $data
     * @param $fun
     * @param $extra
     * @param $msg_lv
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function json($result, $msg = '', $data = array(), $fun = '', $extra = array(), $msg_lv = 0)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(CommonLib::soma_output('jwx', $result, $msg, $data, $fun, $extra, $msg_lv));
    }

    /**
     * Gets the redis instance.
     *
     * @param      string $select The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }

        return null;
    }

    public function isNewTheme()
    {
        return true;
    }


}