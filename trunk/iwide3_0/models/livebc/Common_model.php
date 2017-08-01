<?php
class Common_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    static $enums = array (
            // 1000=>成功
            // 1001：失败，前端用toast显示错误提示（不需要用户操作，自动消失）
            // 1002：失败，前端用alert显示错误提示（要点击确认）
            // 1003：未登状态。
            'status' => array (
                    1 => 1000,
                    2 => 1001,
                    3 => 1000,
                    4 => 1003 
            ),
            'errcode' => array (),
            'msg_lv' => array (
                    0 => '',
                    1 => 'toast',
                    2 => 'alert' 
            ) 
    );
    static $dehydrate_samples = array ();
    public function get_dehydrate_samples($sample) {
        if (isset ( self::$dehydrate_samples [$sample] )) {
            return self::$dehydrate_samples [$sample];
        }
        return array ();
    }
    public function get_enums($type) {
        if (isset ( self::$enums [$type] )) {
            return self::$enums [$type];
        }
        return array ();
    }
    /**
     * @param int $result 运行结果 
     * @param string $msg 显示给用户的信息
     * @param array $data 数据集
     * @param string $fun 调用的方法的标识 如hotel/search
     * @param number $msg_lv 消息级别  
     * @param string $exit 输出数据后是否退出整个程序
     */
    public function out_put_msg($result, $msg = '', $data = array(), $fun = '', $msg_lv = 0, $exit = TRUE) {
        // require_once dirname ( dirname ( __FILE__ ) ) . "/libraries/App/Wxapp_conf.php";
        $info = array ();
        $status_arr = $this->get_enums ( 'status' );
        $msg_lvs = $this->get_enums ( 'msg_lv' );
        $result = isset ( $status_arr [$result] ) ? $status_arr [$result] : 1004;
        $info ['status'] = $result;
        $info ['msg'] = $msg;
        $info ['msg_type'] = $msg_lvs [$msg_lv];
        if (isset ( $data )) {
            $data = json_decode ( json_encode ( $data ), TRUE );
            $info ['web_data'] = $this->data_dehydrate ( $data, $this->get_dehydrate_samples ( $fun ) );
        }
        ob_clean ();
        echo json_encode ( $info, JSON_UNESCAPED_UNICODE );
        if ($exit) {
            exit ();
        }
    }
    /**
     * @param unknown $data 传入数据，只能为数组
     * @param unknown $mode 数据筛选模板 Wxapp_conf中$dehydrate_samples定义
     * @return unknown|NULL[]|unknown
     */
    public function data_dehydrate($data, $mode) {
        if (empty ( $mode ))
            return $data;
        $tmp = array ();
        if (! empty ( $mode ['ks'] )) {
            $mode ['ks'] = array_flip ( $mode ['ks'] );
            $tmp = array_intersect_key ( $data, $mode ['ks'] );
        }
        if (! empty ( $mode ['kas'] )) {
            foreach ( $mode ['kas'] as $mk => $mod ) {
                $tmp [$mk] = isset ( $data [$mk] ) ? $this->data_dehydrate ( $data [$mk], $mod ) : NULL;
            }
        }
        if (! empty ( $mode ['fks'] )) {
            foreach ( $mode ['fks'] as $mk => $mod ) {
                if (isset ( $data [$mk] )) {
                    foreach ( $data [$mk] as $fk => $fm ) {
                        $tmp [$mk] [$fk] = $this->data_dehydrate ( $fm, $mod );
                    }
                } else {
                    $tmp [$mk] = NULL;
                }
            }
        }
        return $tmp;
    }
}
