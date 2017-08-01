<?php
use App\services\Result;
use App\libraries\Support\Log;

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/5/15
 * Time: 16:57
 */
class Third_api extends MY_Controller
{
    /**
     * Inner_api constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->somaDatabase($this->db_soma);
        $this->load->somaDatabaseRead($this->db_soma_read);

        //没有到这个model，不过index的方法的model在命名空间里，无法加载父类model所以只能随便找个父类load出来了。
        $this->load->model('soma/adv_model');
    }


    public function test()
    {
        $handle = @fopen(APPPATH . 'config/soma_openid.log', 'rw');
        if ($handle) {
            $this->load->model('soma/sales_order_model');
            while (($buffer = fgets($handle, 200)) !== false) {
                $row = $this->sales_order_model->get('openid', $buffer);
                if ($row) {
                    echo $buffer. '<br/>';
                }
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
    }

    /**
     * 顺丰返回订单通知的地址
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function callback_sf()
    {

        $result = new Result();

        //身份校验 todo
        $orderId = filter_var($_POST['orderId'], FILTER_SANITIZE_STRING);
        $mailNo = filter_var($_POST['mailNo'], FILTER_SANITIZE_STRING);
        $this->load->model('soma/Consumer_shipping_model', 'shipping_model');
        $model = $this->shipping_model;
        $filter = array('order_id' => $orderId);
        $inter_id = $this->session->get_admin_inter_id ();
        $model= $model->get_shipping_info($filter, $inter_id);
        if ($model->m_get('tracking_no') !== $mailNo) {
            $result->setMessage('运单号与查询的不一样');
        }
        return $result;
    }

    /**
     *
     * 一次性订阅消息, 用户同意或取消授权后会返回相关信息
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1500374289_66bvB
     * @author renshuai  <renshuai@jperation.cn>
     *
     */
    public function callback_temp_msg()
    {
        $params = $this->input->get();

        Log::debug('callback temp msg params is ', $params);
        //todo 拿不到session
        Log::debug('callback temp msg temp session is ', $this->session->tempdata());
//        if (!isset($params['reserved']) || $params['reserved'] !== $this->session->tempdata(WxTempMsgService::RESERVED_KEY)) {
//            show_error('reserved error');
//        }

        //用户点击动作，”confirm”代表用户确认授权，”cancel”代表用户取消授权
        if (isset($params['action'])  && $params['action'] === 'confirm') {

            $interID = 'a450089706';

            $result = App\services\common\WxTempMsgService::getInstance()->sendMsg($interID, $params['openid'], $params['template_id'], $params['scene']);

            redirect(site_url('soma/package/index') . "?id={$interID}");

        } else {
            show_error('you cancel');
        }


    }
}