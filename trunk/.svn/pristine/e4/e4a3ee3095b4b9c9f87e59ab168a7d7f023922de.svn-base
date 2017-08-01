<?php
use Monolog\Handler\StreamHandler;
use Tatocaster\Monolog\Formatter\JsonPrettyUnicodePrintFormatter;

/**
 * 对接智游宝接口
 * Class Api_zhiyoubao
 * @author luguihong  <luguihong@mofly.cn>
 * @property  wxpay_model  $somaWxpayModel
 * @property  ZhiYouBao_Service  $somaZhiyoubaoService
 *
 */
class Api_zhiyoubao extends Soma_base
{
    protected $somaWxpayModel       = NULL;//使用函数doCurlPostRequest用到
    protected $somaZhiyoubaoService = NULL;//处理智游宝的service层
    protected $_debug               = FALSE;//是否调试
    protected $_url                 = NULL;//请求智游宝链接
    protected $_key                 = NULL;//私钥
    protected $_username            = NULL;//账号
    protected $_company_code        = NULL;//企业码
    protected $_inter_id            = NULL;//公众号ID
    protected $_sign_key            = 'xmlMsg';//签名使用的key值
    protected $_CI                  = NULL;

    const RESULT_CODE_ORDER_EXISTS  = 6;//智游宝返回的状态码，代表订单已经存在

    /**
     * 获取公众号对接智游宝账号配置
     * @param $interId
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    protected function _get_config( $interId=NULL )
    {
        $config = array(
            'a450089706' => array(//放心住暂时使用佛山市三水金水湾投资有限公司-微官网测试
                'username'      => 'jswfx',
                'password'      => 'fvy9bgof',
                'company_code'  => 'sdzfxjswfx',
                'key'           => '21D5AB0DD9C2AC625D4C69F343297C06',
            ),
            'a487061037' => array(//佛山市三水金水湾投资有限公司-微官网 hotels.tianai123.com
                'username'      => 'jswfx',
                'password'      => 'fvy9bgof',
                'company_code'  => 'sdzfxjswfx',
                'key'           => '21D5AB0DD9C2AC625D4C69F343297C06',
            ),
            'a492755178' => array(//北京古北水镇旅游有限公司-分销2 http://hotels.tianai123.com/
                'username'      => 'gbszfx2',
                'password'      => 'AYcggCc0',
                'company_code'  => 'sdzfxgbszfx2',
                'key'           => 'E4EE94AE22A2645F889E0CD54DACDCF5',
            ),
        );

        return isset( $config[$interId] ) ? $config[$interId] : array();
    }
    
    public function __construct( $interId=NULL )
    {
        if( $interId && is_array( $interId ) )
        {
            $interId = isset( $interId['inter_id'] ) ? $interId['inter_id'] : '';
        }

        //读取配置
        $config = $this->_get_config( $interId );

        //智游宝接口地址设定
        $interArr = array(
            'a450089706',
        );
        if( in_array( $interId, $interArr ) || isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
        {
            $this->_url             = 'http://boss.zhiyoubao.com/boss/service/code.htm';
            $this->_key             = isset( $config['key'] ) ? $config['key'] : '';
            $this->_username        = isset( $config['username'] ) ? $config['username'] : '';
            $this->_company_code    = isset( $config['company_code'] ) ? $config['company_code'] : '';
        } else {
            $this->_url             = 'http://zyb-zff.sendinfo.com.cn/boss/service/code.htm';
            $this->_key             = 'TESTFX';
            $this->_username        = 'admin';
            $this->_company_code    = 'TESTFX';
        }

        $this->_inter_id = $interId;

        $CI = & get_instance();

        //初始化分片信息
        if( $interId )
        {
            //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
            $CI->load->model('soma/shard_config_model', 'model_shard_config');
            $CI->current_inter_id   = $interId;
            $CI->db_shard_config    = $CI->model_shard_config->build_shard_config( $interId );
        }

        //使用doCurlPostRequest用到
        $CI->load->helper('common_helper');

        //使用xmlToArray用到
        $CI->load->model('pay/wxpay_model','somaWxpayModel');

        //加载智游宝service
        $serviceName    = $CI->serviceName(ZhiYouBao_Service::class);
        $serviceAlias   = $CI->serviceAlias(ZhiYouBao_Service::class);
        $CI->load->service($serviceName, null, $serviceAlias);

        $this->somaWxpayModel       = $CI->somaWxpayModel;
        $this->somaZhiyoubaoService = $CI->soma_zhiyoubao_service;

        //日志
        $handler = new StreamHandler(APPPATH . 'logs/soma/api_zhiyoubao/soma_' . date('Y-m-d') . '.log', \Monolog\Logger::DEBUG);
        $handler->setFormatter(new JsonPrettyUnicodePrintFormatter());
        $CI->monoLog->setHandlers(array($handler));

        $this->_CI = $CI;

        return parent::__construct();
    }

    /**
     * 设置公众号ID
     * @param null $interId
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function set_interId( $interId=NULL )
    {
        $this->_inter_id = $interId;
    }

    /**
     * 把请求/返回记录记入文件
     * @param $description
     * @param $key
     * @param $content
     */
    protected function _log( $description, $key, $content )
    {
        $this->_CI->monoLog->info($description, array(
            $key => $content,
        ));
    }

    /**
     * 获取签名
     * @param $key
     * @param $value
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    protected function _get_sign( $key, $value )
    {
        if( !$this->_key )
        {
            $this->_log( "error：{$this->_inter_id}", 'msg', 'Key can not be empty!' );
            die;
        }

        $string = "{$key}={$value}{$this->_key}";
        return strtolower(MD5($string));
    }

    /**
     * 请求数据
     * @param $url
     * @param $params
     * @param array $extra
     * @param int $timeout
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    protected function _post_request($url, $params, $extra= array(), $timeout= 10)
    {
        if( empty( $this->_inter_id ) )
        {
            $this->_log( "error：{$this->_inter_id}", 'msg', 'Inter_id can not be empty!' );
            die;
        }

        if( $this->_debug && isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']!='production' ){
            echo "Debug Request:";
            var_dump( $params );
            die;
        }

        $this->_log( "request：{$this->_inter_id} {$url}", 'params', $params );

        $requestString = http_build_query( $params, false );
        return doCurlPostRequest( $url, $requestString, $extra );

    }

    /**
     * 处理返回数据
     * @param String $result
     * @return array
     */
    protected function _handle_result($result)
    {
        $this->_log( "response：{$this->_inter_id}", 'msg', $result );

        $result = $this->somaWxpayModel->xmlToArray( $result );

        if( $this->_debug && isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']!='production' )
        {
            echo "Debug Response:<br />";
            print_r( $result );
            die;
        }

        if( isset($result['code']) && ( $result['code']==0 || $result['code']==6 ) )
        {
            //返回码为0即是成功
            //返回码为6，订单已经存在，如果是同步订单的时候，则需要发起订单查询
            return $result;
        } else {
            return FALSE;
        }
    }

/****************************以下为订单处理接口****************************/
    /**
     * 同步订单信息到智游宝
     * @param $orderId
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function send_order( $orderId )
    {
        if( !$orderId )
        {
            $this->_log( "error：send_order {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //检测订单号
        $checkSendOrderIdResult = $somaZhiyoubaoService->checkSendOrderOrderId( $orderId, $this->_inter_id );
        if( !isset( $checkSendOrderIdResult['status'] ) || $checkSendOrderIdResult['status'] != $somaZhiyoubaoService::SUCCESS )
        {
            $this->_log( "error：send_order {$this->_inter_id} {$orderId}", 'msg', $checkSendOrderIdResult );
            return FALSE;
        }

        //获取xml信息
        $orderDetail    = $checkSendOrderIdResult['data']['order_detail'];
        $orderItem      = $checkSendOrderIdResult['data']['order_item'];
        $xml            = $somaZhiyoubaoService->getSendOrderXml( $this->_company_code, $this->_username, $orderId, $orderDetail, $orderItem );

        //获取签名
        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //如果返回状态码为6，需要发起订单查询
        if( isset( $result['code'] ) && $result['code'] == self::RESULT_CODE_ORDER_EXISTS )
        {
            $checkRes = $this->check_order( $orderId );
            if( !$checkRes )
            {
                return FALSE;
            }

        } else {

            //这里的scenicThirdCode才是jfk系统的订单号
            if (isset($result['orderResponse']['order']['ticketOrders']['ticketOrder']['orderCode']))
            {
                $result['orderCode'] = $result['orderResponse']['order']['ticketOrders']['ticketOrder']['orderCode'];
            }

            //检查返回状态
            $checkResponseOrderIdResult = $somaZhiyoubaoService->checkResponseOrderId( $result, $orderId, $this->_inter_id );
            if( !isset( $checkResponseOrderIdResult['status'] ) || $checkResponseOrderIdResult['status'] != $somaZhiyoubaoService::SUCCESS )
            {
                $this->_log( "error：send_order {$this->_inter_id} {$orderId}", 'msg', $checkResponseOrderIdResult );
                return FALSE;
            }

            $responseOrderDetail = $checkResponseOrderIdResult['data']['order_detail'];

        }

        //更新订单主单的字段conn_decives_status
        $res = $somaZhiyoubaoService->successOrderConnDivecesStatus( $orderId, $this->_inter_id );
        if( $res )
        {
            //处理成功，给用户发信息
            $this->send_message( $orderId );
        }

        return $res;

    }

    /**
     * 到智游宝查询订单
     * @param $orderId
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function check_order( $orderId )
    {
        if( !$orderId )
        {
            $this->_log( "error：check_order {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //组装参数
        $sendCodeReq    = 'QUERY_ORDER_REQ';//固定值，方法名
        $xml = $somaZhiyoubaoService->getOrderXml( $this->_company_code, $this->_username, $orderId, $sendCodeReq );

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //这里的scenicThirdCode才是jfk系统的订单号
        if( isset( $result['order']['scenicOrders']['scenicOrder']['scenicThirdCode'] ) )
        {
            $result['responseOrderCode'] = $result['orderCode'];
            $result['orderCode'] = $result['order']['scenicOrders']['scenicOrder']['scenicThirdCode'];
        }

        //检查返回状态
        $checkResponseOrderIdResult = $somaZhiyoubaoService->checkResponseOrderId( $result, $orderId, $this->_inter_id );
        if( !isset( $checkResponseOrderIdResult['status'] ) || $checkResponseOrderIdResult['status'] != $somaZhiyoubaoService::SUCCESS )
        {
            $this->_log( "error：send_order {$this->_inter_id} {$orderId}", 'msg', $checkResponseOrderIdResult );
            return FALSE;
        }

        $responseOrderDetail = $checkResponseOrderIdResult['data']['order_detail'];
        $responseOrderItem = $checkResponseOrderIdResult['data']['order_item'];

        //返回结果的商品信息
        if( isset( $result['order']['scenicOrders']['scenicOrder'] ) )
        {
            $goodsDetail = $result['order']['scenicOrders']['scenicOrder'];
        } else {
            return FALSE;
        }

        //判断商品连接码是否一致
        if(
            isset( $goodsDetail['goodsCode'] ) && !empty( $goodsDetail['goodsCode'] )
            && isset( $responseOrderItem['sku'] ) && !empty( $responseOrderItem['sku'] )
            && ( $responseOrderItem['sku'] == $goodsDetail['goodsCode'] )
        )
        {
            return $result;
        } else {
            $this->_log( "error：check_order {$this->_inter_id} {$orderId}", 'msg', "{$responseOrderItem['sku']} != {$goodsDetail['goodsCode']}" );
            return FALSE;
        }

    }

    /**
     * Tips：这个接口暂时没有使用到，对应订单完结回调接口使用。没有测试过
     * 取消订单
     * @param $orderId
     * @return bool
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function cancel_order( $orderId )
    {
        if( !$orderId )
        {
            $this->_log( "error：cancel_order {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //组装参数
        $sendCodeReq    = 'SEND_CODE_CANCEL_NEW_REQ';//固定值，方法名
        $xml = $somaZhiyoubaoService->getOrderXml( $this->_company_code, $this->_username, $orderId, $sendCodeReq );

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //检查返回状态
        if( $result )
        {

            //处理业务逻辑

            //更新订单主单的字段conn_decives_status
            return $somaZhiyoubaoService->cancelOrderConnDivecesStatus( $orderId, $this->_inter_id );

        } else {
            return FALSE;
        }

    }

    /**
     * 给用户发信息，这个是智游宝那边处理的，我们只能发这条信息就好
     * @param $orderId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function send_message( $orderId )
    {
        if( !$orderId )
        {
            $this->_log( "error：send_message {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //组装参数
        $sendCodeReq    = 'SEND_SM_REQ';//固定值，方法名
        $xml = $somaZhiyoubaoService->getSendMessageXml( $this->_company_code, $this->_username, $orderId, $sendCodeReq );

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //检查返回状态
        if( $result )
        {
            //处理业务逻辑
            return TRUE;

        } else {
            return FALSE;
        }
    }

    /**
     * Tips：这个接口暂时没有使用到，没有测试过
     * 到智游宝查询订单检票情况
     * @param $orderId
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function check_order_use_status( $orderId )
    {
        if( !$orderId )
        {
            $this->_log( "error：check_order_use_status {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //组装参数
        $sendCodeReq    = 'CHECK_STATUS_QUERY_REQ';//固定值，方法名
        $xml = $somaZhiyoubaoService->getOrderXml( $this->_company_code, $this->_username, $orderId, $sendCodeReq );

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        if( isset( $result['subOrders']['subOrder']['orderCode'] ) )
        {
            $result['orderCode'] = $result['subOrders']['subOrder']['orderCode'];
        }

        //检查返回状态
        $checkResponseOrderIdResult = $somaZhiyoubaoService->checkResponseOrderId( $result, $orderId, $this->_inter_id );
        if( !isset( $checkResponseOrderIdResult['status'] ) || $checkResponseOrderIdResult['status'] != $somaZhiyoubaoService::SUCCESS )
        {
            $this->_log( "error：send_order {$this->_inter_id} {$orderId}", 'msg', $checkResponseOrderIdResult );
            return FALSE;
        }

        $responseOrderDetail = $checkResponseOrderIdResult['data']['order_detail'];

        //返回结果的商品信息
        if( isset( $result['subOrders']['subOrder'] ) )
        {
            $detail = $result['subOrders']['subOrder'];
        } else {
            return FALSE;
        }

        $needCheckNum       = $detail['needCheckNum'];//需检票数量
        $alreadyCheckNum    = $detail['alreadyCheckNum'];//已检票数量
        $returnNum          = $detail['returnNum'];//退票数

        //处理业务逻辑
        switch ( $detail['checkStatus'] )
        {
            case 'un_check':
                //未检票
                break;
            case 'checking':
                //检票中（有还没有消费的票）
                break;
            case 'checked':
                //检票完成
                break;
            default:
                break;
        }

    }

    /**
     * Tips：这个接口暂时没有使用到，没有测试过
     * 订单游玩时间改签
     * @param $orderId
     * @return bool
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function modify_order( $orderId, $newOccDate )
    {
        if( !$orderId )
        {
            $this->_log( "error：modify_order {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        if( !$newOccDate )
        {
            $this->_log( "error：modify_order {$this->_inter_id} {$orderId}", 'msg', 'Modify_date can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //获取xml
        $xml = $somaZhiyoubaoService->getModifyOrderXml( $this->_company_code, $this->_username, $orderId, $newOccDate);

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //检查返回状态
        if( $result )
        {

            //处理业务逻辑

            //修改资产的过期时间

            //修改订单的过期时间

        } else {
            return FALSE;
        }

    }

    /**
     * 获取订单核销二维码
     * @param $orderId
     * @return bool|mixed|string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function get_qrcode($orderId)
    {
        if( !$orderId )
        {
            $this->_log( "error：get_qrcode {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //获取xml
        $xml = $somaZhiyoubaoService->getQrcodeXml( $this->_company_code, $this->_username, $orderId );

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //检查返回状态
        if( $result )
        {
            //处理业务逻辑
            $qrcodeUrl = isset( $result['img'] ) ? $result['img'] : '';
            return $qrcodeUrl;

        } else {
            return FALSE;
        }
    }

/****************************以上为订单处理接口****************************/

/****************************以下为退票接口****************************/

    /**
     * 部分退票。这个接口对接退款环节，申请退款的时候，先发起这个接口，成功才生成退款申请纪录
     * Tips：景区设置的退票规则一般是需要景区审核，所以此接口返回的code 0 不能表示退票成功，只是说明线上系统判断退票请求没有问题。
     *      接下来是等待景区审核，审核成功才是退票完成，审核失败，说明退票失败。
     * @param $orderId
     * @param $refundNum
     * @return bool|mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function refund_order( $orderId, $refundNum )
    {
        if( !$orderId )
        {
            $this->_log( "error：refund_order {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        if( !$refundNum )
        {
            $this->_log( "error：refund_order {$this->_inter_id} {$orderId}", 'msg', 'Refund_num can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //获取xml
        $xml = $somaZhiyoubaoService->getRefundOrderXml( $this->_company_code, $this->_username, $orderId, $refundNum);

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //检查返回状态
        if( $result )
        {
            //处理业务逻辑

            //这个字段需要用来查询景区是否通过什么的标示，发起部分退票成功后，需要把这个字段存起来，再拿来发送退票查询。
            if( !isset( $result['retreatBatchNo'] ) )
            {
                $this->_log( "error：refund_order {$this->_inter_id} {$orderId}", 'msg', 'RetreatBatchNo can not be empty!' );
                return FALSE;
            }

            //把retreatBatchNo存在对应的表里
            $retreatBatchNo = $result['retreatBatchNo'];
            return $retreatBatchNo;

        } else {
            return FALSE;
        }

    }

    /**
     * Tips：这个接口暂时没有使用到，没有测试过
     * 查询退票情况
     * @param $orderId
     * @param $retreatBatchNo
     * @return bool
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function refund_order_status( $orderId, $retreatBatchNo )
    {
        if( !$orderId )
        {
            $this->_log( "error：refund_order_status {$this->_inter_id} {$orderId}", 'msg', 'Order_id can not be empty!' );
            return FALSE;
        }

        if( !$retreatBatchNo )
        {
            $this->_log( "error：refund_order_status {$this->_inter_id} {$orderId}", 'msg', 'RetreatBatchNo can not be empty!' );
            return FALSE;
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //获取xml
        $xml = $somaZhiyoubaoService->getRefundOrderStatusXml( $this->_company_code, $this->_username, $retreatBatchNo);

        $sign = $this->_get_sign( $this->_sign_key, $xml );
        $params = array(
            'xmlMsg'    => $xml,
            'sign'      => $sign,
        );

        $result = $this->_post_request( $this->_url, $params );
        $result = $this->_handle_result( $result );

        //检查返回状态
        if( $result )
        {
            //处理业务逻辑

            //Code：0为审核通过，6为未审核完成  Description:描述中 还有 “审核未通过”、“等待审核中”

            //智游宝那里审核已经通过了，处理系统退款单状态为已审核

        } else {
            return FALSE;
        }

    }
/****************************以上为退票接口****************************/

/****************************以下为智游宝回调接口****************************/
    /**
     * 智游宝那边订单完结后，会调用这个接口通知我们这边系统。
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function order_callback()
    {
        $orderId = $this->_CI->input->get('order_code',true);
        $sign = $this->_CI->input->get('sign',true);

        $this->_log( "params：order_callback request {$orderId}", 'msg', $this->_CI->input->get() );

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //检查返回状态
        $callbackCheckResullt = $somaZhiyoubaoService->callbackCheck( $orderId );
        if( !isset( $callbackCheckResullt['status'] ) || $callbackCheckResullt['status'] != $somaZhiyoubaoService::SUCCESS )
        {
            $this->_log( "error：order_callback request {$orderId}", 'msg', $callbackCheckResullt );
            die('failure');
        }

        $responseOrderIdx = $callbackCheckResullt['data']['order_idx'];

        //校验签名
        $signKey = 'order_code';
        $this->_check_sign( $responseOrderIdx['inter_id'], $orderId, $sign, $signKey );

        $status     = $this->_CI->input->get('status',true);
        $total      = $this->_CI->input->get('total',true);//总数量
        $checkNum   = $this->_CI->input->get('checkNum',true);//检票数量
        $returnNum  = $this->_CI->input->get('returnNum',true);//退票数量

        if( !$status )
        {
            $this->_log( "error：order_callback request {$orderId}", 'msg', 'status can not be empty!' );
            die('failure');
        }

        //以下为处理业务逻辑
        switch ( $status )
        {
            case 'cancel':
                //订单取消
                die('智游宝订单取消回调还没有对接！');
                break;
            case 'success':
                //检票完成
                $handleCallbackResullt = $somaZhiyoubaoService->handleOrderCallback( $responseOrderIdx );
                $this->_log( "error：order_callback request {$orderId}", 'msg', $handleCallbackResullt );
                if( isset( $handleCallbackResullt['status'] ) && $handleCallbackResullt['status'] == $somaZhiyoubaoService::SUCCESS )
                {
                    echo 'success';
                } else {
                    echo 'failure';
                }
                break;
            default:
                echo 'failure';
                break;
        }

    }

    /**
     * 核销通知，检票完成时，智游宝回调通知。
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function consumer_callback()
    {
        $orderId    = $this->_CI->input->get('order_no',true);
        $sign       = $this->_CI->input->get('sign',true);

        $this->_log( "params：consumer_callback request {$orderId}", 'msg', $this->_CI->input->get() );

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //检查返回状态
        $callbackCheckResullt = $somaZhiyoubaoService->callbackCheck( $orderId );
        if( !isset( $callbackCheckResullt['status'] ) || $callbackCheckResullt['status'] != $somaZhiyoubaoService::SUCCESS )
        {
            $this->_log( "error：consumer_callback request {$orderId}", 'msg', $callbackCheckResullt );
            die('failure');
        }

        $responseOrderIdx = $callbackCheckResullt['data']['order_idx'];

        //校验签名
        $signKey = 'order_no';
        $this->_check_sign( $responseOrderIdx['inter_id'], $orderId, $sign, $signKey );

        $status     = $this->_CI->input->get('status',true);
        $total      = $this->_CI->input->get('total',true);//总数量
        $checkNum   = $this->_CI->input->get('checkNum', true);
        $returnNum  = $this->_CI->input->get('returnNum', true);

        if( !$status )
        {
            $this->_log( "error：consumer_callback request {$orderId}", 'msg', 'status can not be empty!' );
            die('failure');
        }

        //以下为处理业务逻辑
        $status = strtolower( trim( $status ) );
        switch ( $status )
        {
            case 'check':
                //检票

                //查询出属于这个订单资产，判断资产数量是否足够

                //特权券不能在这里核销

                //查询出核销码，检查是否有checkNum这个数量的核销码

                //核销相应数量的核销码

                $handleCallbackResullt = $somaZhiyoubaoService->handleConsumerCallback( $responseOrderIdx, $checkNum );
                $this->_log( "consumer_record：consumer_callback request {$orderId}", 'msg', $handleCallbackResullt );
                if( isset( $handleCallbackResullt['status'] ) && $handleCallbackResullt['status'] == $somaZhiyoubaoService::SUCCESS )
                {
                    echo 'success';
                } else {
                    echo 'failure';
                }
                break;
            default:
                echo 'failure';
                break;
        }

    }

    /**
     * 智游宝回调校验签名
     * @param $interId
     * @param $orderId
     * @param $sign
     * @param $signKey
     * @author luguihong  <luguihong@jperation.com>
     */
    protected function _check_sign( $interId, $orderId, $sign, $signKey )
    {
        //检查签名要放后，先获取到公众号ID，再获取对应的秘钥，才能验证签名
        $config = $this->_get_config( $interId );
        $this->_key = isset($config['key'])?$config['key']:'';

        if( !$sign )
        {
            $this->_log( "error：callback check_sign request {$interId} {$orderId}", 'msg', 'Sign can not be empty!' );
            die('failure');
        }

        //签名校验
        $localSign = $this->_get_sign( $signKey, $orderId );
        if( $localSign != $sign )
        {
            $this->_log( "error：callback check_sign request {$interId} {$orderId}", 'msg', "Sign error, {$localSign} != {$sign}" );
            die('failure');
        }
    }

    /**
     * 退票通知，这个是智游宝回调使用的
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function refund_callback()
    {
        $retreatBatchNo     = $this->_CI->input->get('retreatBatchNo',true);//用于查询退票情况 退票成功或失败
        $orderId            = $this->_CI->input->get('orderCode',true);//订单号
        $sign               = $this->_CI->input->get('sign',true);
        $subOrderCode       = $this->_CI->input->get('subOrderCode',true);//子订单号
        $auditStatus        = $this->_CI->input->get('auditStatus',true);//审核状态
        $returnNum          = $this->_CI->input->get('returnNum',true);//退票数量

        $this->_log( "params：refund_callback request {$orderId}", 'msg', $this->_CI->input->get() );

        if( !$retreatBatchNo )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', 'retreatBatchNo can not be empty!' );
            die('failure');
        }

        if( !$auditStatus )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', 'auditStatus can not be empty!' );
            die('failure');
        }

        $somaZhiyoubaoService = $this->somaZhiyoubaoService;

        //检查返回状态
        $callbackCheckResullt = $somaZhiyoubaoService->callbackCheck( $orderId );
        if( !isset( $callbackCheckResullt['status'] ) || $callbackCheckResullt['status'] != $somaZhiyoubaoService::SUCCESS )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', $callbackCheckResullt );
            die('failure');
        }

        $responseOrderIdx = $callbackCheckResullt['data']['order_idx'];
        $interID = $responseOrderIdx['inter_id'];

        if( !$sign )
        {
            $this->_log( "error：callback check_sign request {$orderId}", 'msg', 'Sign can not be empty!' );
            die('failure');
        }

        //检查签名要放后，先获取到公众号ID，再获取对应的秘钥，才能验证签名
        $config = $this->_get_config( $interID );
        $key = isset($config['key'])?$config['key']:'';
        $string = "{$orderId}{$key}";
        $localSign = strtolower(MD5($string));

        //签名校验
        if( $localSign != $sign )
        {
            $this->_log( "error：refund_callback check_sign request {$orderId}", 'msg', "Sign error, {$localSign} != {$sign}" );
            die('failure');
        }

        //以下为处理业务逻辑

        //查出退款主单
        $refundDetail = $somaZhiyoubaoService->getRefundDetailByOrderId( $orderId, $responseOrderIdx['inter_id'] );
        if( !$refundDetail )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', 'Use callback_order_id to get refund_detail is null' );
            die('failure');
        }

        //检查有没有保存发起退款是智游宝返回的retreat_batch_no字段值
        if( empty( $refundDetail['retreat_batch_no'] ) || $retreatBatchNo != $refundDetail['retreat_batch_no'] )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', "retreat_batch_no {$refundDetail['retreat_batch_no']} != {$retreatBatchNo}" );
            die('failure');
        }

        //获取退款细单
        $refundItems = $somaZhiyoubaoService->getRefundItemByRefundId( $refundDetail['refund_id'], $refundDetail['inter_id'] );
        if( !$refundItems )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', 'Use refund_id to get refund_item is null' );
            die('failure');
        }

        $refundItem = current( $refundItems );

        if( $refundItem['qty'] != $returnNum )
        {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', "qty({$refundItem['qty']}) != returnNum({$returnNum})" );
            die('failure');
        }

        //判断退款主单的状态
        $somaSalesRefundModel = $somaZhiyoubaoService->somaSalesRefundModel;

        //关于这里的判断，智游宝那边通过审核后，我们这边系统处理相当于后台的通过审核，但不退款
        $statusLabel = $somaSalesRefundModel->get_status_label();
        if( $refundDetail['status'] == $somaSalesRefundModel::STATUS_WAITING )
        {

            $somaSalesOrderModel = $somaZhiyoubaoService->somaSalesOrderModel->load( $orderId );
            if( !$somaSalesOrderModel )
            {
                $this->_log( "error：refund_callback request {$orderId}", 'msg', 'Sales_order_model load order_id fail!' );
                die('failure');
            }
            $somaZhiyoubaoService->somaSalesOrderModel = $somaSalesOrderModel;

            //以下为处理业务逻辑
            $auditStatus = strtolower( trim( $auditStatus ) );
            switch ( $auditStatus )
            {
                case 'success':
                    //成功，处理为已审核
                    $res = $somaZhiyoubaoService->successRefundOrder( $refundDetail['inter_id'] );
                    $this->_log( "refund_success_result：refund_callback request {$orderId}", 'msg', $res );
                    if( $res )
                    {
                        echo 'success';
                    }
                    break;
                case 'failure':
                    //失败，处理为拒绝退款
                    $res = $somaZhiyoubaoService->failureRefundOrder( $refundDetail['inter_id'] );
                    $this->_log( "refund_failure_result：refund_callback request {$orderId}", 'msg', $res );
                    if( $res )
                    {
                        echo 'success';
                    }
                    break;
                default:
                    echo 'failure';
                    break;
            }

        } elseif(
            $refundDetail['status'] == $somaSalesRefundModel::STATUS_PENDING
            || $refundDetail['status'] == $somaSalesRefundModel::STATUS_REFUND
        ){
            //如果已经是已退款状态了，就直接返回success
            $this->_log( "success：refund_callback request {$orderId}", 'msg', ' Success beacause refund_order status = '.$statusLabel[$refundDetail['status']] );
            echo 'success';

        } else {
            $this->_log( "error：refund_callback request {$orderId}", 'msg', 'The refund_status = '.$statusLabel[$refundDetail['status']] );
            echo 'failure';
        }

    }

/****************************以上为智游宝回调接口****************************/

}