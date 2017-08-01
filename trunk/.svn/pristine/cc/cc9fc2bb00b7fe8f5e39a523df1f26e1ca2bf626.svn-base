<?php
class Debts_model extends MY_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_DEBT = 'hotel_debts';
    function create_debt($inter_id, $debt_type, $source_id, $debt_amount, $source = array()) {
        $data = array (
                'inter_id' => $inter_id,
                'debt_type' => $debt_type,
                'debt_amount' => $debt_amount,
                'source_id' => $source_id,
                'create_time' => time () 
        );
        isset ( $source ['sub_ident'] ) and $data ['sub_ident'] = $source ['sub_ident'];
        isset ( $source ['remark'] ) and $data ['remark'] = $source ['remark'];
        isset ( $source ['ex_data'] ) and $data ['ex_data'] = json_encode ( $source ['ex_data'], JSON_UNESCAPED_UNICODE );
        $admin_profile = $this->session->admin_profile;
        if (! empty ( $admin_profile )) {
            $data ['creater_type'] = 2;
            $data ['creater'] = $admin_profile ['admin_id'] . '|' . $admin_profile ['username'];
        } else {
            $openid = $this->session->userdata ( $inter_id . 'openid' );
            if ($openid) {
                $data ['creater_type'] = 1;
                $data ['creater'] = $openid;
            }
        }
        if ($this->db->insert ( self::TAB_DEBT, $data )) {
            $id = $this->db->insert_id ();
            $debtid = '10' . str_pad ( $id, 7, 0, STR_PAD_LEFT ) . str_pad ( mt_rand ( 0, 999 ), 3, mt_rand ( 0, 9 ), STR_PAD_LEFT );
            $this->db->where ( 'debt_id', $id );
            $this->db->update ( self::TAB_DEBT, array (
                    'debtid' => $debtid 
            ) );
            return $debtid;
        }
        return FALSE;
    }
    function get_source_debt($inter_id, $debt_type, $source_id, $status = 'valid', $params = array()) {
        $map = array (
                'inter_id' => $inter_id,
                'debt_type' => $debt_type,
                'source_id' => $source_id 
        );
        switch ($status) {
            case 'valid' :
                $map ['status'] = 1;
                break;
            case 'cancel' :
                $map ['status'] = 2;
                break;
            case 'inprogress' :
                $map ['status'] = 1;
                $map ['debt_state'] = 0;
                break;
            default :
                break;
        }
        isset ( $params ['sub_ident'] ) and $map ['sub_ident'] = $params ['sub_ident'];
        $db = $this->load->database ( 'iwide_r1', true );
        $db->where ( $map );
        if (! empty ( $params ['latest'] )) {
            $db->order_by ( 'debt_id desc' );
            $db->limit ( 1 );
            return $db->get ( self::TAB_DEBT )->row_array ();
        }
        return $db->get ( self::TAB_DEBT )->result_array ();
    }
    function get_debt_by_id($inter_id, $id, $field = 'debtid') {
        $db = $this->load->database ( 'iwide_r1', true );
        return $db->get_where ( self::TAB_DEBT, array (
                $field => $id,
                'inter_id' => $inter_id 
        ) )->row_array ();
    }
    function update_debt($inter_id, $debtid, $datas) {
        $updata = array ();
        empty ( $datas ['paytype'] ) or $updata ['paytype'] = $datas ['paytype'];
        empty ( $datas ['status'] ) or $updata ['status'] = $datas ['status'];
        empty ( $datas ['debt_state'] ) or $updata ['debt_state'] = $datas ['debt_state'];
        empty ( $datas ['remark'] ) or $updata ['remark'] = $datas ['remark'];
        empty ( $datas ['ex_data'] ) or $updata ['ex_data'] = json_encode ( $datas ['ex_data'] );
        if (! $updata)
            return FALSE;
        $updata ['update_time'] = date ( 'Y-m-d H:i:s' );
        $this->db->where ( array (
                'inter_id' => $inter_id,
                'debtid' => $debtid 
        ) );
        return $this->db->update ( self::TAB_DEBT, $updata );
    }
    function pay_return($inter_id, $debtid, $debt_type, $params = array()) {
        switch ($debt_type) {
            case 'order_continue' :
                return $this->order_continue_payreturn ( $inter_id, $debtid, $params );
                break;
            default :
                break;
        }
    }
    function order_continue_payreturn($inter_id, $debtid, $params) {
        $debt = $this->get_debt_by_id ( $inter_id, $debtid );
        if ($debt) {
            $datas = array (
                    'paytype' => 'weixin',
                    'debt_state' => 1,
                    'remark' => $debt ['remark'] . '。已支付' 
            );
            $ex_data = json_decode ( $debt ['ex_data'], TRUE );
            $ex_data ['pay_third_no'] = $params ['third_no'];
            $datas ['ex_data'] = $ex_data;
            $this->update_debt ( $inter_id, $debtid, $datas );
            $this->load->model ( 'hotel/Order_model' );
            $order = $this->Order_model->get_main_order ( $inter_id, array (
                    'orderid' => $debt ['source_id'],
                    'idetail' => array (
                            'i' 
                    ) 
            ) );
            if ($order) {
                $order = $order [0];
                $order_items = array_column ( $order ['order_details'], NULL, 'sub_id' );
                $item = $order_items [$debt ['sub_ident']];
                $this->load->model ( 'hotel/Hotel_check_model' );
                $adapter = $this->Hotel_check_model->get_hotel_adapter ( $inter_id, $order['hotel_id'] );
                $continue_result = $adapter->continue_order_item ( $inter_id, $order, $item, $debt, $params );
                if ($continue_result ['s'] == 1) {
                    $this->load->model ( 'hotel/Room_status_model' );
                    $new_enddate = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $item ['enddate'] ) ) );
                    $data ['startdate'] = $item ['startdate'];
                    $data ['enddate'] = $new_enddate;
                    $data ['new_price'] = $item ['iprice'] + $debt ['debt_amount'];
                    if ($this->Order_model->update_order_item ( $inter_id, $debt ['source_id'], $debt ['sub_ident'], $data )) {
                        $this->Room_status_model->change_hotel_temp_stock ( array (
                                'inter_id' => $inter_id,
                                'hotel_id' => $order ['hotel_id'],
                                'room_id' => $item ['room_id'],
                                'price_code' => $item ['price_code'] 
                        ), $item ['enddate'], $data ['enddate'], 1 );
                        return TRUE;
                    }
                } else {
                    $refund_result = $this->debt_refund ( $inter_id, $debt, array (
                            'order' => $order 
                    ) );
                    $datas = array (
                            'paytype' => 'weixin',
                            'status' => 2 
                    );
                    $datas ['remark'] = $debt ['remark'] . '。已支付。' . '续住失败，';
                    if ($refund_result ['s'] == 1) {
                        $datas ['remark'] .= '已退款';
                        $datas ['debt_state'] = 2;
                    } else {
                        $datas ['remark'] .= '退款失败,' . $refund_result ['errmsg'];
                        $datas ['debt_state'] = 3;
                    }
                    $this->update_debt ( $inter_id, $debtid, $datas );
                    return FALSE;
                }
            }
        }
        return FALSE;
    }
    public function debt_refund($inter_id, $debt, $params = array()) {
        if (is_string ( $debt ))
            $debt = $this->get_debt_by_id ( $inter_id, $debt );
        if ($debt) {
            MYLOG::w ( json_encode ( $debt, JSON_UNESCAPED_UNICODE ), 'hotel' . DS . 'wxpay_refund' . DS . 'debt' );
            try {
                $return = array ();
                $this->load->model ( 'pay/Pay_model' );
                $out_trade_no = $debt ['debtid'];
                $pay_result = $this->Pay_model->get_pay_log ( $inter_id, $out_trade_no, 'weixin' );
                if ($pay_result) {
                    $order_pay_info = simplexml_load_string ( $pay_result ['rtn_content'], 'SimpleXMLElement', LIBXML_NOCDATA );
                    $transaction_id = $order_pay_info->transaction_id;
                    
                    // 获取商户号(mch_id)
                    $pay_paras = $this->Pay_model->get_pay_paras ( $inter_id );
                    $mch_id = isset ( $pay_paras ['mch_id'] ) ? $pay_paras ['mch_id'] : '';
                    if (! $mch_id) {
                        MYLOG::w ( "$inter_id|" . json_encode ( $debt, JSON_UNESCAPED_UNICODE ) . '|' . json_encode ( $pay_paras, JSON_UNESCAPED_UNICODE ) . '|' . '找不到商户号', 'hotel' . DS . 'wxpay_refund' . DS . 'debt', '_error' );
                        $return ['s'] = 0;
                        $return ['errmsg'] = '找不到商户号';
                        return $return;
                    }
                    
                    // 获取appid
                    $this->load->model ( 'wx/publics_model' );
                    $public = $this->publics_model->get_public_by_id ( $inter_id );
                    $appid = isset ( $public ['app_id'] ) ? $public ['app_id'] : '';
                    if (! $appid) {
                        MYLOG::w ( "$inter_id|" . json_encode ( $debt, JSON_UNESCAPED_UNICODE ) . '|' . json_encode ( $public, JSON_UNESCAPED_UNICODE ) . '|' . '找不到公众号appid', 'hotel' . DS . 'wxpay_refund' . DS . 'debt', '_error' );
                        $return ['s'] = 0;
                        $return ['errmsg'] = '找不到公众号appid';
                        return $return;
                    }
                    
                    // 生成随机字符串、签名
                    $this->load->model ( 'pay/wxpay_model' );
                    $this->wxpay_model->setParameter ( "body", "退款申请" ); // 设置参数使用此函数
                    $nonce_str = $this->wxpay_model->createNoncestr (); // 获取随机字符串
                    $refund_fee = $order_pay_info->total_fee;
                    $jsApiObj = array ();
                    $jsApiObj ['appid'] = isset ( $pay_paras ['app_id'] ) && ! empty ( $pay_paras ['app_id'] ) ? $pay_paras ['app_id'] : $appid; // 公众账号ID
                    $jsApiObj ['mch_id'] = $mch_id; // 商户号
                    $order = $params ['order'];
                    // 是否设置了子商户号
                    if (! empty ( $pay_paras ['sub_mch_id'] )) {
                        $sub_mch_id = $pay_paras ['sub_mch_id']; // 子商户号
                        
                        if (! empty ( $pay_paras ['sub_mch_id_h_' . $order ['hotel_id']] )) {
                            $sub_mch_id = $pay_paras ['sub_mch_id_h_' . $order ['hotel_id']];
                        }
                        
                        $jsApiObj ['sub_mch_id'] = $sub_mch_id; // 子商户号
                        
                        $jsApiObj ['transaction_id'] = $transaction_id;
                    }
                    
                    $jsApiObj ['nonce_str'] = $nonce_str; // 随机字符串
                    $jsApiObj ['out_trade_no'] = $out_trade_no; // 商户订单号
                    
                    $extras = array ();
                    //  证书路径
                    $extras = array ();
                    $extras ['CURLOPT_CAINFO'] = realpath ( '../certs' ) . DS . "rootca_" . $mch_id . '.pem';
                    $extras ['CURLOPT_SSLCERT'] = realpath ( '../certs' ) . DS . "apiclient_cert_" . $mch_id . '.pem';
                    $extras ['CURLOPT_SSLKEY'] = realpath ( '../certs' ) . DS . "apiclient_key_" . $mch_id . '.pem';
                    
                    // 判断证书是否存在
                    if (! file_exists ( $extras ['CURLOPT_SSLCERT'] ) || ! file_exists ( $extras ['CURLOPT_SSLKEY'] )) {
                        MYLOG::w ( "$inter_id|" . json_encode ( $debt, JSON_UNESCAPED_UNICODE ) . '|' . json_encode ( $extras, JSON_UNESCAPED_UNICODE ) . '|' . '没有找到证书', 'hotel' . DS . 'wxpay_refund' . DS . 'debt', '_error' );
                        $return ['s'] = 0;
                        $return ['errmsg'] = '没有找到证书';
                        return $return;
                    }
                    $jsApiObj ['op_user_id'] = $mch_id; // 操作员
                    $jsApiObj ['out_refund_no'] = $out_trade_no; // 商户退款单号
                    $jsApiObj ['refund_fee'] = $refund_fee; // 退款金额
                    $jsApiObj ['total_fee'] = $refund_fee; // 支付金额
                    
                    $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
                    $jsApiObj ['sign'] = $this->wxpay_model->getSign ( $jsApiObj, $pay_paras );
                    $xml = $this->wxpay_model->arrayToXml ( $jsApiObj );
                    $this->load->helper ( 'common_helper' );
                    $result = doCurlPostRequest ( $url, $xml, $extras );
                    
                    // 判断是否成功
                    $result = $this->wxpay_model->xmlToArray ( $result );
                    $return_code = isset ( $result ['return_code'] ) ? $result ['return_code'] : '';
                    $result_code = isset ( $result ['result_code'] ) ? $result ['result_code'] : '';
                    if ($return_code == 'SUCCESS' && $result_code == 'SUCCESS') {
                        $return ['s'] = 1;
                        $return ['errmsg'] = 'success';
                        return $return;
                    } else {
                        $tips = isset ( $result ['return_msg'] ) ? $result ['return_msg'] : 'FAIL';
                        isset ( $result ['err_code_des'] ) and $tips .= ',' . $result ['err_code_des'];
                        MYLOG::w ( "$inter_id|" . json_encode ( $debt, JSON_UNESCAPED_UNICODE ) . '|' . $xml . '|' . json_encode ( $extras ) . '|' . json_encode ( $result, JSON_UNESCAPED_UNICODE ) . '|' . '退款接口失败,' . $tips, 'hotel' . DS . 'wxpay_refund' . DS . 'debt', '_error' );
                        $return ['s'] = 0;
                        $return ['errmsg'] = $tips;
                        return $return;
                    }
                }
            } catch ( Exception $e ) {
                MYLOG::w ( "$inter_id|" . json_encode ( $debt, JSON_UNESCAPED_UNICODE ) . '|' . $e->getMessage (), 'hotel' . DS . 'wxpay_refund' . DS . 'debt', '_error' );
                $return ['s'] = 0;
                $return ['errmsg'] = $e->getMessage ();
                return $return;
            }
        } else {
            MYLOG::w ( "$inter_id|" . json_encode ( $debt, JSON_UNESCAPED_UNICODE ) . '|' . '找不到账单信息', 'hotel' . DS . 'wxpay_refund' . DS . 'debt', '_error' );
            $return ['s'] = 0;
            $return ['errmsg'] = '找不到帐单信息';
            return $return;
        }
    }
}