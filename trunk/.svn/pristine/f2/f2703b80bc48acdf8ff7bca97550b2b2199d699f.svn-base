<?php

/**
 * User: luguihong <luguihong@mofly.cn>
 * @property asset_customer_model $somaAssetCustomerModel
 * @property Sales_order_model $somaSalesOrderModel
 * @property Sales_item_package_model $somaSalesItemPackageModel
 * @property Product_package_model $somaProductPackageModel
 * @property Sales_refund_model $somaSalesRefundModel
 * @property Consumer_order_model $somaConsumerOrderModel
 * @property Consumer_code_model $somaConsumerCodeModel
 * Date: 2017/3/1
 * Time: 11:15
 */
class ZhiYouBao_Service extends MY_Service
{
    const SUCCESS   = 1;//成功
    const FAIL      = 2;//失败

    protected $_business = 'package';

    /**
     * ZhiYouBao_Service constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * 资产model
         */
        $path   = $this->modelName(asset_customer_model::class);
        $alias  = $this->modelAlias(asset_customer_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 订单model
         */
        $path   = $this->modelName(Sales_order_model::class);
        $alias  = $this->modelAlias(Sales_order_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 订单细单model
         */
        $path   = $this->modelName(Sales_item_package_model::class);
        $alias  = $this->modelAlias(Sales_item_package_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 商品model
         */
        $path   = $this->modelName(Product_package_model::class);
        $alias  = $this->modelAlias(Product_package_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 退款model
         */
        $path   = $this->modelName(Sales_refund_model::class);
        $alias  = $this->modelAlias(Sales_refund_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 核销model
         */
        $path   = $this->modelName(Consumer_order_model::class);
        $alias  = $this->modelAlias(Consumer_order_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 核销码model
         */
        $path   = $this->modelName(Consumer_code_model::class);
        $alias  = $this->modelAlias(Consumer_code_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        /**
         * 把分片配置信息放到model
         */
        $this->somaSalesOrderModel->db_shard_config         = $this->CI->db_shard_config;
        $this->somaSalesItemPackageModel->db_shard_config   = $this->CI->db_shard_config;
        $this->somaSalesRefundModel->db_shard_config        = $this->CI->db_shard_config;
        $this->somaConsumerOrderModel->db_shard_config      = $this->CI->db_shard_config;
        $this->somaConsumerCodeModel->db_shard_config       = $this->CI->db_shard_config;

        /**
         * model里面有些方法需要用到business这个参数
         */
        $this->somaSalesOrderModel->business        = $this->_business;
        $this->somaSalesItemPackageModel->business  = $this->_business;
        $this->somaSalesRefundModel->business       = $this->_business;
        $this->somaConsumerOrderModel->business     = $this->_business;
        $this->somaConsumerCodeModel->business      = $this->_business;
    }

    /**
     * 过滤一些html标签
     * @param $content
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    protected function filterHtml( $content )
    {
        $replace = array(
            "<br>", "<Br>", "<br/>", "<Br/>", "<br />", "<Br />", "<b>", "</b>",
            "&lt;br&gt;", "&lt;Br&gt;", "&lt;br/&gt;", "&lt;Br/&gt;", "&lt;br /&gt;", "&lt;Br /&gt;", "&lt;b&gt;", "&lt;/b&gt;",
            "&#60;br&#62;", "&#60;Br&#62;", "&#60;br/&#62;", "&#60;Br/&#62;", "&#60;br /&#62;", "&#60;Br /&#62;", "&#60;b&#62;", "&#60;b/&#62;",
        );
        return str_replace( $replace, '', htmlspecialchars( $content ) );
    }

    /**
     * 根据订单编号获取订单信息
     * @param $orderId
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getOrderDetailByOrderId( $orderId )
    {
        return $this->somaSalesOrderModel->load( $orderId )->m_data();
    }

    /**
     * 根据订单编号获取订单细单信息
     * @param $orderId
     * @param $interId
     * @return mixed
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getOrderItemByOrderId( $orderId, $interId )
    {
        return $this->somaSalesItemPackageModel->get_order_items_byIds( array($orderId), $this->_business, $interId );
    }

    /**
     * 主要的使用场景是，智游宝那边回调url，没有inter_id。
     * @param $orderId
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getOrderDetailToIdxByOrderId( $orderId )
    {
        return $this->somaSalesOrderModel->get_order_simple( $orderId );
    }

    /**
     * 同步订单成功，修改订单同步连接状态
     * @param $orderId
     * @param $interId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function successOrderConnDivecesStatus( $orderId, $interId )
    {
        $somaSalesOrderModel = $this->somaSalesOrderModel;
        $connDivecesStatus = $somaSalesOrderModel::CONN_DEVICES_ORDER;
        return $this->updateOrderConnDivecesStatus( $orderId, $interId, $connDivecesStatus );
    }

    /**
     * 取消订单，修改订单同步连接状态
     * @param $orderId
     * @param $interId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function cancelOrderConnDivecesStatus( $orderId, $interId )
    {
        $somaSalesOrderModel = $this->somaSalesOrderModel;
        $connDivecesStatus = $somaSalesOrderModel::CONN_DEVICES_CANCEL;
        return $this->updateOrderConnDivecesStatus( $orderId, $interId, $connDivecesStatus );
    }

    /**
     * 更新订单连接状态
     * @param $orderId
     * @param $interId
     * @param $connDivecesStatus
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function updateOrderConnDivecesStatus( $orderId, $interId, $connDivecesStatus )
    {
        $somaSalesOrderModel = $this->somaSalesOrderModel;
        $db = $somaSalesOrderModel->_shard_db( $interId );
        $table = $somaSalesOrderModel->table_name( $interId );

        $data = array(
            'conn_devices_status' => $connDivecesStatus,
        );

        $db->where( 'inter_id', $interId )
            ->where( 'order_id', $orderId )
            ->limit( 1 )
            ->update( $table, $data );

        if( $db->affected_rows() > 0 )
        {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /**
     * 获取退款主单信息
     * @param $orderId
     * @param $interId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getRefundDetailByOrderId( $orderId, $interId )
    {
        return $this->somaSalesRefundModel->get_refund_order_detail_byOrderId( $orderId, $interId );
    }

    /**
     * 根据退款编号，查询退款细单信息
     * @param $refundId
     * @param $interId
     * @return bool|mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getRefundItemByRefundId( $refundId, $interId )
    {
        /**
         * @var Sales_refund_model $somaSalesRefundModel
         */
        $somaSalesRefundModel = $this->somaSalesRefundModel->load( $refundId );
        if( $somaSalesRefundModel )
        {
            return $somaSalesRefundModel->get_order_items( $this->_business, $interId );
        } else {
            return FALSE;
        }
    }

    /**
     * 智游宝退款审核成功，修改退款主单状态为已审核，但不退款。
     * @param $interId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function successRefundOrder( $interId )
    {
        $somaSalesRefundModel = $this->somaSalesRefundModel;
        $somaSalesOrderModel = $this->somaSalesOrderModel;
        $somaAssetCustomerModel = $this->somaAssetCustomerModel;

        $orderId    = $somaSalesOrderModel->m_get('order_id');
        $business   = $somaSalesOrderModel->m_get('business');
        $refundType = $somaSalesRefundModel->m_get('refund_type');

        //退款详情
        $refundDetail = $somaSalesRefundModel->get_order_detail( $business, $interId );

        //订单操作
        $refund_item = array( 'is_refund'=>$somaSalesOrderModel::STATUS_ITEM_REFUNDED );//细单退款状态已退款
        $somaSalesOrderModel->refund_item   = $refund_item;
        $somaSalesRefundModel->order        = $somaSalesOrderModel;

        //资产操作
        $assetItemPk                            = $somaAssetCustomerModel->item_table_primary_key();
        $assetItemsDetail                       = $somaSalesOrderModel->get_order_asset( $business, $interId );
        $somaAssetCustomerModel->{$assetItemPk} = $assetItemsDetail['items'][0][$assetItemPk];
        $somaAssetCustomerModel->qty            = $refundDetail['items'][0]['qty'];
        $somaSalesRefundModel->asset            = $somaAssetCustomerModel;

        //取消操作
        $return_err_msg = TRUE;
        if( $refundType == $somaSalesRefundModel::REFUND_TYPE_WX ){
            $res = $somaSalesRefundModel->wx_refund_send( $orderId, $business, $interId, $return_err_msg );
        }elseif( $refundType == $somaSalesRefundModel::REFUND_TYPE_CZ ){
            $res = $somaSalesRefundModel->cz_refund_send( $orderId, $business, $interId, $return_err_msg );
        }elseif( $refundType == $somaSalesRefundModel::REFUND_TYPE_JF ){
            $res = $somaSalesRefundModel->jf_refund_send( $orderId, $business, $interId, $return_err_msg );
        }

        if( isset( $res['status'] ) && $res['status'] == 1 ){
            return $somaSalesRefundModel->order_payment( $business, $interId );
        }

        return FALSE;

    }

    /**
     * 智游宝退款审核失败，修改退款主单状态为取消，取消锁定订单状态。
     * @param $interId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function failureRefundOrder( $interId )
    {
        $somaSalesRefundModel = $this->somaSalesRefundModel;
        $somaSalesOrderModel = $this->somaSalesOrderModel;

        $refund = array('refund_status'=>$somaSalesOrderModel::REFUND_PENDING); //主单退款状态无退款
        $somaSalesOrderModel->refund = $refund;

        $refundItem = array( 'is_refund'=>$somaSalesOrderModel::STATUS_ITEM_UNREFUND );//细单退款状态无申请
        $somaSalesOrderModel->refund_item = $refundItem;

        $somaSalesRefundModel->order = $somaSalesOrderModel;

        //取消操作
        return $somaSalesRefundModel->order_cancel( $this->_business, $interId );
    }

    public function handleOrderCallback( $responseOrderIdx )
    {
        $somaConsumerOrderModel = $this->somaConsumerOrderModel;
        $interId = $responseOrderIdx['inter_id'];
        $orderId = $responseOrderIdx['order_id'];
        $callbackType = $somaConsumerOrderModel::CALLBACK_TYPE_ORDER;
        $checkNum = 0;//因为这里是订单完结成功，所以检票数量等于资产数量，在api_consumer处理检票数量
        return $somaConsumerOrderModel->api_consumer( $orderId, $checkNum, $interId, $this->_business, $callbackType );
    }

    /**
     * @param $responseOrderIdx 订单索引表数据
     * @param $checkNum         智游宝请求检票数量
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function handleConsumerCallback( $responseOrderIdx, $checkNum )
    {
        $somaConsumerOrderModel = $this->somaConsumerOrderModel;
        $interId = $responseOrderIdx['inter_id'];
        $orderId = $responseOrderIdx['order_id'];
        $callbackType = $somaConsumerOrderModel::CALLBACK_TYPE_CONSUMER;
        return $somaConsumerOrderModel->api_consumer( $orderId, $checkNum, $interId, $this->_business, $callbackType );
    }

    /**
     * 检测订单编号
     * @param $orderId
     * @param $interId
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function checkSendOrderOrderId( $orderId, $interId )
    {
        $result = array(
            'message'       => array(
                                'inter_id'  => $interId,
                                'order_id'  => $orderId,
            ),
            'status'        => self::FAIL,
            'data'          => '',
        );

        $somaSalesOrderModel = $this->somaSalesOrderModel;
        $somaProductPackageModel = $this->somaProductPackageModel;

        //订单详情
        $orderDetail = $this->getOrderDetailByOrderId( $orderId );
        if( !$orderDetail )
        {
            $result['message']['error'] = 'Order_detail is null!';
            return $result;
        }

        //是否已经支付
        if( $orderDetail['status'] != $somaSalesOrderModel::STATUS_PAYMENT )
        {
            $result['message']['error'] = 'Order_status is not pay success!';
            return $result;
        }

        //是否需要同步
        if( $orderDetail['conn_devices_status'] != $somaSalesOrderModel::CONN_DEVICES_DEFAULT )
        {
            $result['message']['error'] = 'Order_conn_devices_status is not equal ' . $somaSalesOrderModel::CONN_DEVICES_DEFAULT;
            return $result;
        }

        $orderItems = $this->getOrderItemByOrderId( $orderId, $interId );
        if( !$orderItems )
        {
            $result['message']['error'] = 'Order_items is null!';
            return $result;
        }

        //细单详情
        $orderItem = current( $orderItems );

        //是否对接智游宝
        if( $orderItem['conn_devices'] != $somaProductPackageModel::DEVICE_ZHIYOUBAO )
        {
            $result['message']['error'] = 'Conn_devices is not zhiyoubao!';
            return $result;
        }

        $result['status'] = self::SUCCESS;
        $result['message'] = 'SUCCESS';
        $result['data'] = array(
            'order_detail'  => $orderDetail,
            'order_item'    => $orderItem,
        );
        return $result;

    }

    /**
     * 返回结果简单验证验证
     * @param $response
     * @param $orderId
     * @param $interId
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function checkResponseOrderId( $response, $orderId, $interId )
    {

        $result = array(
            'message'       => array(
                'inter_id'  => $interId,
                'order_id'  => $orderId,
            ),
            'status'        => self::FAIL,
            'data'          => '',
        );

        //检查返回状态
        $responseOrderId = isset($response['orderCode'])?$response['orderCode']:'';

        if( $responseOrderId != $orderId )
        {
            //订单号不匹配
            $result['message']['error'] = "{$orderId} != {$responseOrderId}";
            return $result;
        }

        $orderDetail = $this->getOrderDetailByOrderId( $responseOrderId );
        if( !$orderDetail )
        {
            //获取不到订单信息
            $result['message']['error'] = 'Use response_order_id to get order_detail is null!';
            return $result;
        }

        if( $interId != $orderDetail['inter_id'] )
        {
            //公众号不匹配
            $result['message']['error'] = "{$orderDetail['inter_id']} != {$interId}";
            return $result;
        }

        $orderItems = $this->getOrderItemByOrderId( $responseOrderId, $interId );
        if( !$orderItems )
        {
            $result['message']['error'] = 'Order_items is null!';
            return $result;
        }

        //细单详情
        $orderItem = current( $orderItems );

        $result['status'] = self::SUCCESS;
        $result['message'] = 'SUCCESS';
        $result['data'] = array(
            'order_detail'  => $orderDetail,
            'order_item'    => $orderItem,
        );
        return $result;

    }

    /**
     * 检查回调参数，如果都检测通过，那么就返回订单索引表信息
     * @param $orderId
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function callbackCheck( $orderId )
    {
        $result = array(
            'message'       => array(
                'order_id'  => $orderId,
            ),
            'status'        => self::FAIL,
            'data'          => '',
        );

        if( !$orderId )
        {
            //没有在订单索引表里获取到信息
            $result['message']['error'] = 'Request_order_id can not be empty!';
            return $result;
        }

        //因为这个是智游宝回调的，所以没有公众号ID，只能到订单索引表里获取信息
        $orderIdxInfo = $this->getOrderDetailToIdxByOrderId( $orderId );
        if( !$orderIdxInfo )
        {
            //没有在订单索引表里获取到信息
            $result['message']['error'] = 'Use request_order_id to get order_idx_info is null!';
            return $result;
        }

        $result['status'] = self::SUCCESS;
        $result['message'] = 'SUCCESS';
        $result['data'] = array(
            'order_idx'  => $orderIdxInfo,
        );
        return $result;
    }

    /**
     * 获取同步订单的xml
     * @param $companyCode
     * @param $userName
     * @param $orderId
     * @param $orderDetail
     * @param $orderItem
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getSendOrderXml( $companyCode, $userName, $orderId, $orderDetail, $orderItem )
    {
        //组装参数
        $sendCodeReq    = 'SEND_CODE_REQ';//固定值，方法名
        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间
        $certificateNo  = '';//身份证号
        $linkName       = $orderDetail['contact'];//联系人
        $linkMobile     = $orderDetail['mobile'];//联系电话
        $orderPrice     = $orderDetail['grand_total'];//订单总价格
        $groupNo        = '';//团号
        $payMethod      = 'vm';//支付方式值spot现场支付vm备佣金，zyb智游宝支付
        $name           = '';//真实姓名
        $id             = '';//实名制商品需要传多个身份证（暂时没有使用到实名制）
        $price          = $orderItem['price_package'];//票价，必填，线下要统计的
        $quantity       = $orderItem['qty'];//必填票数量
        $totalPrice     = $orderPrice;//必填子订单总价
        $occDate        = $orderItem['expiration_date'];//必填日期（游玩日期）
        $goodsCode      = $orderItem['sku'];//必填 商品编码，同票型编码（可能为商家自己填的sku）//'PST20160918013085';//
        $goodsName      = $this->filterHtml( $orderItem['name'] );//商品名称
        $remark         = $goodsName;//备注

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <order>
            <certificateNo>{$certificateNo}</certificateNo>
            <linkName>{$linkName}</linkName>
            <linkMobile>{$linkMobile}</linkMobile>
            <orderCode>{$orderId}</orderCode>
            <orderPrice>{$orderPrice}</orderPrice>
            <groupNo>{$groupNo}</groupNo>
            <payMethod>{$payMethod}</payMethod>
            <ticketOrders>
                <ticketOrder>
                    <orderCode>{$orderId}</orderCode>
                    <price>{$price}</price>
                    <quantity>{$quantity}</quantity>
                    <totalPrice>{$totalPrice}</totalPrice>
                    <occDate>{$occDate}</occDate>
                    <goodsCode>{$goodsCode}</goodsCode>
                    <goodsName>{$goodsName}</goodsName>
                    <remark>{$remark}</remark>
                </ticketOrder>
            </ticketOrders>
        </order>
    </orderRequest>
</PWBRequest>
EOF;

        return $xml;
    }

    /**
     * 获取修改游玩日期的xml内容
     * @param $companyCode
     * @param $userName
     * @param $orderId
     * @param $newOccDate
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getModifyOrderXml( $companyCode, $userName, $orderId, $newOccDate )
    {
        //组装参数
        $sendCodeReq    = 'ORDER_ENDORSE_REQ';//固定值，方法名
        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <endorse>
            <subOrderCode>{$orderId}</subOrderCode>
            <newOccDate>{$newOccDate}</newOccDate>
        </endorse>
    </orderRequest>
</PWBRequest>
EOF;
        return $xml;

    }

    /**
     * 获取二维码xml内容
     * @param $companyCode
     * @param $userName
     * @param $orderId
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getQrcodeXml( $companyCode, $userName, $orderId )
    {
        //组装参数
        $sendCodeReq    = 'QUERY_IMG_URL_REQ';//固定值，方法名
        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <order>
            <orderCode>{$orderId}</orderCode>
        </order>
    </orderRequest>
</PWBRequest>
EOF;
        return $xml;

    }

    /**
     * 获取退款接口的xml内容
     * @param $companyCode
     * @param $userName
     * @param $orderId
     * @param $refundNum
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getRefundOrderXml( $companyCode, $userName, $orderId, $refundNum )
    {
        //组装参数
        $sendCodeReq    = 'RETURN_TICKET_NUM_NEW_REQ';//固定值，方法名
        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <returnTicket>
            <orderCode>{$orderId}</orderCode>
            <returnNum>{$refundNum}</returnNum>
            <thirdReturnCode>{$orderId}</thirdReturnCode>
        </returnTicket>
    </orderRequest>
</PWBRequest>
EOF;
        return $xml;
    }

    /**
     * 获取退款接口的xml内容
     * @param $companyCode
     * @param $userName
     * @param $retreatBatchNo
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getRefundOrderStatusXml( $companyCode, $userName, $retreatBatchNo )
    {
        //组装参数
        $sendCodeReq    = 'QUERY_RETREAT_STATUS_REQ';//固定值，方法名
        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <order>
            <retreatBatchNo>{$retreatBatchNo}</retreatBatchNo>
        </order>
    </orderRequest>
</PWBRequest>
EOF;
        return $xml;
    }

    /**
     * 获取组装参数相同的xml
     * @param $orderId
     * @param $sendCodeReq
     * @return string
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getOrderXml( $companyCode, $userName, $orderId, $sendCodeReq )
    {
        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <order>
            <orderCode>{$orderId}</orderCode>
        </order>
    </orderRequest>
</PWBRequest>
EOF;
        return $xml;
    }

    /**
     * 返回给用户发信息的xml
     * @param $companyCode
     * @param $userName
     * @param $orderId
     * @param $sendCodeReq
     * @param string $tplCode
     * @return string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getSendMessageXml( $companyCode, $userName, $orderId, $sendCodeReq, $tplCode='' )
    {
        /**
         * $tplCode不填写时智游宝后台使用默认模版，如需设置自己企业的短信模版可与客服联系设置
         */

        $sendCode       = 'SendCode';//固定值
        $date           = date('Y-m-d');//请求时间

        //生成xml
        $xml = <<<EOF
<PWBRequest>
    <transactionName>{$sendCodeReq}</transactionName>
    <header>
        <application>{$sendCode}</application>
        <requestTime>{$date}</requestTime>
    </header>
    <identityInfo>
        <corpCode>{$companyCode}</corpCode>
        <userName>{$userName}</userName>
    </identityInfo>
    <orderRequest>
        <order>
            <orderCode>{$orderId}</orderCode>
        </order>
    </orderRequest>
</PWBRequest>
EOF;
        return $xml;
    }

}