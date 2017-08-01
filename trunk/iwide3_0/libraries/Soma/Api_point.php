<?php
/**
 * 获取积分数据接口
 * Class Api_point
 * @author luguihong  <luguihong@mofly.cn>
 *
 */
class Api_point extends Soma_base
{
    //公众号ID
    const ID_BIGUIYUAN      = 'a421641095';//碧桂园
    const ID_XINXIYIZHAN    = 'a452233816';//信息驿站
    const ID_FANGXINZHU     = 'a450089706';//放心住

    //公众号域名
    const DOMAIN_BIGUIYUAN      = 'biguiyuan30.iwide.cn';//碧桂园
    const DOMAIN_XINXIYIZHAN    = 'assist.iwide.cn';//信息驿站
    const DOMAIN_FANGXINZHU     = 'credit.iwide.cn';//放心住

    //ip
    const IP_BIGUIYUAN      = '';//碧桂园ip
    const IP_XINXIYIZHAN    = '';//信息驿站ip
    const IP_FANGXINZHU     = '';//放心住ip

    //请求状态
    const RETURN_CODE_FAIL      = 'FAIL';
    const RETURN_CODE_SUCCESS   = 'SUCCESS';

    //返回结果状态
    const RESULT_CODE_FAIL      = 'FAIL';
    const RESULT_CODE_SUCCESS   = 'SUCCESS';

    //开启ip检查
    const OPEN_IP_CHECK         = FALSE;

    //开启域名检查
    const OPEN_DOMAIN_CHECK     = TRUE;

    //开启域名对应公众号检查
    const OPEN_IP_MAPPING_DOMAIN_CHECK     = TRUE;

    //购买方式
    const BUY_TYPE              = 'buy_type';

    //消费状态
    const CONSUMER_STATUS       = 'consumer_status';

    //退款状态
    const REFUND_STATUS         = 'refund_status';

    //产品类型
    const PRODUCT_TYPE         = 'p_type';

    //错误码
    const ERR_CODE_IP               = 10001;
    const ERR_CODE_DOMAIN           = 10002;
    const ERR_CODE_PARAM            = 10003;
    const ERR_CODE_INTER_NOT_MATCH  = 20001;
    const ERR_CODE_INTER_EMPTY      = 20002;
    const ERR_CODE_SIGNATURE        = 20003;
    const ERR_CODE_DATA_EMPTY       = 20004;

    public $_somaApiWxpayModel = NULL;
    public $_param = array();

    public function __construct()
    {
        $CI =& get_instance();

        $CI->load->model('pay/wxpay_model','somaApiWxpayModel');
        $this->_somaApiWxpayModel = $CI->somaApiWxpayModel;

        //错误码列表
        $errCodeList = $this->_get_err_code_list();

        //返回信息
        $return = array(
            'return_code'=>self::RETURN_CODE_FAIL,
            'result_code'=>self::RESULT_CODE_FAIL,
        );

        $xml = file_get_contents("php://input");
        $param = (array) simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA );
        if( $param )
        {
            $this->_param = $param;
        } else {
            $return['err_code'] = self::ERR_CODE_PARAM;
            $return['err_msg'] = $errCodeList[self::ERR_CODE_PARAM];
            $this->_log('response msg', 'msg', $return);
            echo json_encode( $return );
            die;
        }

        //IP判断
        if( self::OPEN_IP_CHECK )
        {
            $ip = $CI->input->ip_address();
            $idMappingIps = $this->_get_id_mapping_ip();
            $ips = array_values( $idMappingIps );
            if (
                ! in_array($ip, $ips)
                || empty( $idMappingIps[$this->_param['itd']] )
                || ( !empty( $idMappingIps[$this->_param['itd']] ) && $idMappingIps[$this->_param['itd']] != $ip )
            )
            {
                $return['err_code'] = self::ERR_CODE_IP;
                $return['err_msg'] = $errCodeList[self::ERR_CODE_IP];
                $this->_log('response msg', 'msg', $return);
                echo json_encode( $return );
                die;
            }
        }

        //Domain判断
        $domain = $_SERVER['HTTP_HOST'];
        if( self::OPEN_DOMAIN_CHECK )
        {
            $idMappingDomains = $this->_get_ip_mapping_domain();
            $domains = array_values( $idMappingDomains );
            if ( ! in_array($domain, $domains))
            {
                $return['err_code'] = self::ERR_CODE_DOMAIN;
                $return['err_msg'] = $errCodeList[self::ERR_CODE_DOMAIN];
                $this->_log('response msg', 'msg', $return);
                echo json_encode( $return );
                die;
            }
        }

        //Domain是否对应公众号判断
        if( self::OPEN_IP_MAPPING_DOMAIN_CHECK )
        {
            $idMappingDomains = $this->_get_ip_mapping_domain();
            if (
                empty( $idMappingDomains[$this->_param['itd']] )
                || ( !empty( $idMappingDomains[$this->_param['itd']] ) && $idMappingDomains[$this->_param['itd']] != $domain )
            )
            {
                $return['err_code'] = self::ERR_CODE_INTER_NOT_MATCH;
                $return['err_msg'] = $errCodeList[self::ERR_CODE_INTER_NOT_MATCH];
                $this->_log('response msg', 'msg', $return);
                echo json_encode( $return );
                die;
            }
        }

    }

    //获取key
    protected function _get_key()
    {
        return array(
            self::ID_BIGUIYUAN      => '70jd9ey3f5ckigtvh03wjsbBZ0um9lyi',
            self::ID_XINXIYIZHAN    => '70jd9ey3f5ckigtvh03wjsbBZ0um9lyi',
            self::ID_FANGXINZHU     => '70jd9ey3f5ckigtvh03wjsbBZ0um9lyi',
        );
    }

    //返回错误码数组
    protected function _get_err_code_list()
    {
        return array(
            self::ERR_CODE_IP               =>  '不允许访问的IP地址',
            self::ERR_CODE_DOMAIN           =>  '不允许访问的域名',
            self::ERR_CODE_PARAM            =>  '参数错误',
            self::ERR_CODE_INTER_NOT_MATCH  =>  'ITD错误',
            self::ERR_CODE_INTER_EMPTY      =>  'ITD不能为空',
            self::ERR_CODE_SIGNATURE        =>  '签名错误',
            self::ERR_CODE_DATA_EMPTY       =>  '数据为空',
        );
    }

    //id mapping domain
    protected function _get_ip_mapping_domain()
    {
        return array(
            self::ID_BIGUIYUAN      => self::DOMAIN_BIGUIYUAN,     //碧桂园
            self::ID_XINXIYIZHAN    => self::DOMAIN_XINXIYIZHAN,   //信息驿站
            self::ID_FANGXINZHU     => self::DOMAIN_FANGXINZHU,    //放心住
        );
    }

    //ip
    protected function _get_id_mapping_ip()
    {
        return array(
            self::ID_BIGUIYUAN      => self::IP_BIGUIYUAN,     //碧桂园
            self::ID_XINXIYIZHAN    => self::IP_XINXIYIZHAN,   //信息驿站
            self::ID_FANGXINZHU     => self::IP_FANGXINZHU,    //放心住
        );
    }

    //要获取的字段
    protected function _get_order_fields()
    {
        return array(
            'order_id'              =>'oid',//订单编号
            'contact'               =>'customer',//购买人
            'mobile'                =>'phone',//购买电话
            'create_time'           =>'s_date',//下单时间
            'payment_time'          =>'pay_time',//支付时间
            'settlement'            =>self::BUY_TYPE,//购买方式
            'consume_status'        =>self::CONSUMER_STATUS,//消费状态
            'refund_status'         =>self::REFUND_STATUS,//退款状态
            'point_total'           =>'point_num',//实扣积分
            'real_grand_total'      =>'real_money',//实付金额
            'subtotal'              =>'order_money',//订单总额 = 应付金额＋优惠金额
            'row_qty'               =>'num',//购买件数
        );
    }

    //要获取的字段
    protected function _get_order_item_fields()
    {
        return array(
            'type'              =>self::PRODUCT_TYPE,//产品类型
            'hotel_name'        =>'hotel_name',//酒店名称
            'name'              =>'goods_name',//商品名称
            'sku'               =>'sku',//sku
            'price_package'     =>'price',//商品单价
            'qty'               =>'get_num',//获得数量
        );
    }

    /**
     * 请求数据链接
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function get_point_list()
    {
        $CI =& get_instance();

        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        $this->_log( 'request ip', 'ip', $CI->input->ip_address() );
        $this->_log( 'request url', 'url', $url );
        $this->_log( 'request param', 'param', json_encode($this->_param, JSON_UNESCAPED_UNICODE) );


        //返回信息
        $return = array(
            'return_code'=>self::RETURN_CODE_FAIL,
            'result_code'=>self::RESULT_CODE_FAIL,
        );

        //检查
        $this->_param_check();

        $return['return_code'] = self::RETURN_CODE_SUCCESS;

        //必填，公众号
        $interId = htmlspecialchars( $this->_param['itd'] );
        $filter = array(
            'inter_id'=> $interId
        );

        //选填，订单号
        $orderId = isset( $this->_param['oid'] ) ? $this->_param['oid'] + 0 : '';
        if( $orderId )
        {
            $filter['order_id'] = $orderId;
        }

        //选填，开始时间（指下单的时间）
        $startTime = isset( $this->_param['s_date'] ) ? htmlspecialchars( $this->_param['s_date'] ) : '';
        if( $startTime )
        {
            //时间格式化
            $startTime = date( 'Y-m-d', strtotime( $startTime ) );
            if( strlen($startTime)<=10 )
            {
                $filter['create_time >= '] = $startTime . ' 00:00:00';
            } else {
                $filter['create_time >= '] = $startTime;
            }
        }

        //选填，结束时间（指下单的时间）
        $endTime = isset( $this->_param['e_date'] ) ? htmlspecialchars( $this->_param['e_date'] ) : '';
        if( $endTime )
        {
            $endTime = date( 'Y-m-d', strtotime( $endTime ) );
            if( strlen($endTime)<=10 )
            {
                $filter['create_time <= '] = $endTime . ' 23:59:59';
            } else {
                $filter['create_time <= '] = $endTime;
            }
        }
        
        //没有设置时间，返回一个月内
        if(!$startTime && !$endTime)
        {
            $filter['create_time >= '] = date('Y-m-d H:i:s', strtotime('-1 month') );
        }

        //错误码列表
        $errCodeList = $this->_get_err_code_list();

        $business = 'package';

        //需要初始化分片信息
        $CI->load->model('soma/shard_config_model', 'somaShardConfigModel');
        if( $interId )
        {
            $CI->current_inter_id = $interId;
            $CI->db_shard_config= $CI->somaShardConfigModel->build_shard_config( $interId );
        }

        $CI->load->model('soma/Sales_order_model','somaSalesOrderModel');
        $somaSalesOrderModel = $CI->somaSalesOrderModel;

        $filter['status'] = $somaSalesOrderModel::STATUS_PAYMENT;//查询购买成功的订单
        $orderList = $somaSalesOrderModel->get_order_collection( array( 'where'=>$filter ) );
//        var_dump( $orderList );die;
        if( $orderList )
        {
            //购买类型
            $buyType = $somaSalesOrderModel->get_settle_label();

            //产品类型
            $productType = $somaSalesOrderModel->get_product_type_label();

            //消费状态
            $consumerStatus = $somaSalesOrderModel->get_consume_label();

            //退款状态
            $refundStatus = $somaSalesOrderModel->get_refund_label();

            $CI->load->library('Soma/Api_member');
            $api = new Api_member( $interId );
            $result= $api->get_token();
            $api->set_token( $result['data'] );

            $orderIds = array();
            $orderFields = $this->_get_order_fields();
            $orderItemFields = $this->_get_order_item_fields();
            foreach( $orderList as $k=>$v )
            {
                $data = array();
                //提取主单表信息
                foreach( $orderFields as $sk=>$sv )
                {
                    $data[$sv] = $orderList[$k][$sk];

                    if( $sv == self::BUY_TYPE && $data[$sv] )
                    {
                        $data[$sv] = $buyType[$data[$sv]];
                    }

                    if( $sv == self::CONSUMER_STATUS && $data[$sv] )
                    {
                        $data[$sv] = $consumerStatus[$data[$sv]];
                    }

                    if( $sv == self::REFUND_STATUS && $data[$sv] )
                    {
                        $data[$sv] = $refundStatus[$data[$sv]];
                    }

                }

                //提取细单表信息
                foreach( $orderItemFields as $ssk=>$ssv )
                {
                    $data[$ssv] = $orderList[$k]['items'][0][$ssk];//暂时不考虑多种商品情况

                    if( $ssv == self::PRODUCT_TYPE && $data[$ssv] )
                    {
                        $data[$ssv] = $productType[$data[$ssv]];
                    }

                }

                //获取支付类型model
                $CI->load->model('soma/Sales_payment_model','somaPaymentModel');
                $somaPaymentModel = $CI->somaPaymentModel;
                //支付类型
                $payType = $somaPaymentModel->get_payment_label();
                $paidTypes = $somaPaymentModel->get_paid_type_byOrderIds( array($v['order_id']), $interId, 'paid_type' );
                $data['pay_type'] = isset( $paidTypes[0]['paid_type'] ) ? $payType[$paidTypes[0]['paid_type']] : '';

                //获取会员信息
                $memberInfo = $api->get_member_info( $v['openid'] );
                if( $memberInfo )
                {
                    $memberInfo = (array)$memberInfo['data'];
                    $data['member_no'] = $memberInfo['membership_number'];//会员卡号
                    $data['member_phone'] = $memberInfo['cellphone'];//会员手机号码
                }

                $orderIds[$v['order_id']] = $data;

            }

            //到邮寄表，查找邮寄数量
            $CI->load->model('soma/Consumer_shipping_model','somaConsumerShippingModel');
            $somaConsumerShippingModel = $CI->somaConsumerShippingModel;
            $select = 'shipping_id,order_id,qty';
            $shippingList = $somaConsumerShippingModel->get_shipping_info( array( 'order_id'=>array_keys($orderIds) ), $interId, $select );
            if( $shippingList )
            {
                foreach( $shippingList as $k=>$v )
                {
                    $orderIds[$v['order_id']]['mail_id'] .= $v['shipping_id'].',';
                }
            }
            $return['result_code'] = self::RESULT_CODE_SUCCESS;
            $return['err_msg'] = '查询成功';
            $return['total'] = count( $orderIds );
            $return['data'] = $orderIds;
            echo json_encode( $return );
            die;

        } else {
            $return['result_code'] = self::RESULT_CODE_SUCCESS;
            $return['err_msg'] = $errCodeList[self::ERR_CODE_DATA_EMPTY];
            $this->_log( 'response msg', 'msg', $return );
            echo json_encode( $return );
            die;
        }

    }

    /**
     * @param $params   请求参数
     * @return string   返回签名
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function get_sign( $params, $key )
    {
        $fields= array( 'sign' );
        foreach ($params as $k => $v) {
            if( in_array($k, $fields) )
            {
                //去掉签名参数sign
                unset($params[$k]);
            } elseif ( !$v ) {
                //参数为空不参与签名
                unset($params[$k]);
            }

        }
        //签名步骤一：按字典序排序参数
        ksort($params);
        $string = http_build_query( $params, false ). "&key=". $key;
        return strtoupper(md5($string));
    }

    /**
     * 对传入对表单数组检测
     * @author  luguihong  <luguihong@mofly.cn>
     */
    protected function _param_check()
    {
        //错误码列表
        $errCodeList = $this->_get_err_code_list();

        //返回信息
        $return = array(
            'return_code'=>self::RETURN_CODE_FAIL,
            'result_code'=>self::RESULT_CODE_FAIL,
        );

        //公众号为空
        if( !isset( $this->_param['itd'] ) || empty( $this->_param['itd'] ) )
        {
            $return['err_code'] = self::ERR_CODE_INTER_EMPTY;
            $return['err_msg'] = $errCodeList[self::ERR_CODE_INTER_EMPTY];
            $this->_log( 'response msg', 'msg', $return );
            echo json_encode( $return );
            die;
        }

        //公众号判断
        $idMappingDomains = $this->_get_ip_mapping_domain();
        $interIds = array_keys( $idMappingDomains );
        if( !in_array( $this->_param['itd'], $interIds ) )
        {
            $return['err_code'] = self::ERR_CODE_INTER_NOT_MATCH;
            $return['err_msg'] = $errCodeList[self::ERR_CODE_INTER_NOT_MATCH];
            $this->_log( 'response msg', 'msg', $return );
            echo json_encode( $return );
            die;
        }

        //检验签名
        $keyIds = $this->_get_key();
        $sign = $this->get_sign( $this->_param, $keyIds[$this->_param['itd']] );
        if( !isset( $this->_param['sign'] ) || empty( $this->_param['sign'] ) || $sign != $this->_param['sign'] )
        {
            $return['err_code'] = self::ERR_CODE_SIGNATURE;
            $return['err_msg'] = $errCodeList[self::ERR_CODE_SIGNATURE];
            $this->_log( 'response msg', 'msg', $return );
            echo json_encode( $return );
            die;
        }

    }

    /**
     * 把请求/返回记录记入文件
     * @param String $content
     */
    protected function _log( $description, $key, $content )
    {
        $CI =& get_instance();
        $CI->load->library('Soma_Logger', array(
            'options' => array(
                'prefix' => 'soma_'
            ),
            'logDirectory' => APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'soma' . DIRECTORY_SEPARATOR . 'api_point',
        ));

        $CI->soma_logger->info($description, array(
            $key => $content,
        ));

    }

}