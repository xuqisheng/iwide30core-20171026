<?php
class Webservice_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_REFLECT = 'webservice_field_config';
    function get_web_reflect($inter_id, $hotel_id, $web_name, $type, $status = NULL, $sort = 'w2l') {
        $db_read = $this->load->database ( 'iwide_r1', true );
        $arr = array ();
        $db_read->order_by ( 'hotel_id asc' );
        $db_read->where_in ( 'hotel_id', array (
                0,
                $hotel_id 
        ) );
        $db_read->where ( array (
                'inter_id' => $inter_id,
                'webservice_name' => $web_name 
        ) );
        if ($status == 1 || $status == 2) {
            $db_read->where ( 'status', $status );
        }
        if (is_array ( $type )) {
            $db_read->where_in ( 'value_type', $type );
            $data = $db_read->get ( self::TAB_REFLECT )->result_array ();
            if ($sort == 'w2l') {
                foreach ( $data as $d ) {
                    $arr [$d ['value_type']] [$d ['web_value']] = $d ['local_value'];
                }
            } else {
                foreach ( $data as $d ) {
                    $arr [$d ['value_type']] [$d ['local_value']] = $d ['web_value'];
                }
            }
        } else {
            $db_read->where ( 'value_type', $type );
            $data = $db_read->get ( self::TAB_REFLECT )->result_array ();
            if ($sort == 'w2l') {
                foreach ( $data as $d ) {
                    $arr [$d ['web_value']] = $d ['local_value'];
                }
            } else {
                foreach ( $data as $d ) {
                    $arr [$d ['local_value']] = $d ['web_value'];
                }
            }
        }
        return $arr;
    }
    
    /**获取一条配置
     * @param unknown $inter_id
     * @param unknown $value_type
     * @param unknown $webservice_name
     * @param unknown $value
     * @param number $hotel_id
     * @param number $status
     */
    function get_field_config($inter_id, $value_type, $webservice_name, $value = array(), $hotel_id = 0, $status = 1) {
        $this->db->where ( array (
                'inter_id' => $inter_id,
                'value_type' => $value_type,
                'webservice_name' => $webservice_name,
                'hotel_id' => $hotel_id,
                'status' => $status 
        ) );
        if (isset ( $value ['web_value'] ))
            $this->db->where ( 'web_value', $value ['web_value'] );
        if (isset ( $value ['local_value'] ))
            $this->db->where ( 'local_value', $value ['local_value'] );
        return $this->db->get ( self::TAB_REFLECT )->row_array ();
    }
    
    /**
     * @todo 记录PMS通讯日志
     * @param string $send_content 发送的数据
     * @param string $receive_content PMS返回的数据
     * @param string $inter_id 
     * @param string $service_type PMS类型
     * @param string $web_path 请求路径
     * @param string $record_type 请求类型 query_get|query_post
     * @return boolean 
     */
    function log_service_record($send_content, $receive_content, $inter_id, $service_type, $web_path, $record_type) {
        return $this->db->insert ( 'webservice_record', array (
                'send_content' => $send_content,
                'receive_content' => $receive_content,
                'inter_id' => $inter_id,
                'service_type' => $service_type,
                'web_path' => $web_path,
                'record_type' => $record_type,
                'record_time' => time () 
        ) );
    }
    
    /**
     * @param string $inter_id
     * @param string $service_type webservice类型，如weixin_tmpmsg,suba
     * @param string $web_path webservice url 
     * @param string $send_content 发送的数据
     * @param string $receive_content 收到的数据
     * @param string $record_type 记录的类型，如query_get|query_post|hotel_order_checkin
     * @param string $send_time 发出请求的时间
     * @param string $micro_receive_time 接收到数据的时间，到毫秒，为microtime ()方法取到的值
     * @param string $openid 请求的openid
     */
    function add_webservice_record($inter_id, $service_type, $web_path, $send_content, $receive_content, $record_type, $send_time, $micro_receive_time, $openid = '') {
        is_string ( $send_content ) ?: $send_content = json_encode ( $send_content, JSON_UNESCAPED_UNICODE );
        is_string ( $receive_content ) ?: $receive_content = json_encode ( $receive_content, JSON_UNESCAPED_UNICODE ); // 格式化数据
        $openid = empty ( $openid ) ? '' : $openid;
        $micro_receive_time = explode ( ' ', $micro_receive_time );
        $wait_time = $micro_receive_time [1] - $send_time + number_format ( $micro_receive_time [0], 2, '.', '' ); // 计算等待时间
        
        MYLOG::pms_access_record ( $inter_id, $send_time, $wait_time, $service_type, $web_path, $send_content, $receive_content, "openid={$openid}&record_type={$record_type}" );
        
//         return array('wait_time'=>$wait_time,'openid'=>$openid);
        return $this->db->insert ( 'webservice_record', array (
                'send_content' => $send_content,
                'receive_content' => $receive_content,
                'record_time' => $send_time,
                'inter_id' => $inter_id,
                'service_type' => $service_type,
                'web_path' => $web_path,
                'record_type' => $record_type,
                'openid' => $openid,
                'wait_time' => $wait_time 
        ) );
    }
    /**
     * @param unknown $inter_id
     * @param unknown $service_type pms类型
     * @param unknown $err_lv 报警级别，NULL时不记录
     * @param unknown $err_msg 报错信息
     * @param array $datas 参数数组，含alarm_wait_time:方法超时报警时间,fun_name:方法名描述,send:发送数据,receive:接收数据,send_time:调用时间戳,web_path:调用地址,receive_time:调用结束时mirco_time()
     * @param array $func_data 方法所用数据，用于快速定位问题，如下单方法可传单号，array('orderid'=>$orderid);
     */
    function webservice_error_log($inter_id, $service_type, $err_lv, $err_msg, $datas = array(), $func_data = array()) {
        $micro_receive_time = explode ( ' ', $datas ['receive_time'] );
        $wait_time = $micro_receive_time [1] - $datas ['send_time'] + number_format ( $micro_receive_time [0], 2, '.', '' ); // 计算等待时间
        $ext_err_msg = '';
        //超过设置的超时时间，直接修改报警级别
        if ((isset ( $datas ['alarm_wait_time'] ) && $wait_time > $datas ['alarm_wait_time']) || (! isset ( $datas ['alarm_wait_time'] ) && $wait_time > 15)) {
            $err_lv = 1;
            $ext_err_msg = '。接口超时:' . $wait_time . 's';
        }
        if (isset ( $err_lv )) {
            is_string ( $err_msg ) or $err_msg = json_encode ( $err_msg, JSON_UNESCAPED_UNICODE ); // 格式化数据
            $err_msg = $datas ['fun_name'] . '。接口错误信息：' . $err_msg . $ext_err_msg;
            is_string ( $datas ['send'] ) or $datas ['send'] = json_encode ( $datas ['send'], JSON_UNESCAPED_UNICODE );
            is_string ( $datas ['receive'] ) or $datas ['receive'] = json_encode ( $datas ['receive'], JSON_UNESCAPED_UNICODE );
            $func_data = empty ( $func_data ) ? '' : json_encode ( $func_data, JSON_UNESCAPED_UNICODE );
            MYLOG::pms_error_record ( $inter_id, date ( 'Y-m-d H:i:s', $datas ['send_time'] ), $wait_time, $service_type, $datas ['web_path'], $datas ['send'], $datas ['receive'], $err_lv, $err_msg, $func_data );
        }
    }
}