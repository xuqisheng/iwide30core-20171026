<?php
class User_notify_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_USER_NOTIFY_QUEUE = 'hotel_user_notify_queues';
    protected function _load_db($type = 'main') {
        switch ($type) {
            case 'read' :
                if (! isset ( $this->_read_db )) {
                    $this->_read_db = $this->load->database ( 'iwide_r1', true );
                }
                return $this->_read_db;
                break;
            default :
                return $this->db;
                break;
        }
    }
    /**增加用户通知队列
     * @param unknown $inter_id
     * @param unknown $ident 队列主标识
     * @param unknown $type 队列类型
     * @param array $datas 格式如：
     * array(
     *      'sub_ident'=>'', //队列辅助标识
     *      'ex_data'=>'', //辅助处理队列的数据，会以json形式存入表
     *      'start_time'=>'', //可以进行处理的最早时间
     *      'end_time'=>'', //可以进行处理的最晚时间，过此时间后不再处理
     * )
     * @param string $check_duplicate 检查是否已有数据（$inter_id,$ident,$type,$sub_ident均相同）
     * @return boolean|unknown
     */
    function add_queue($inter_id, $ident, $type, $datas = array(), $check_duplicate = TRUE) {
        $map = array (
                'inter_id' => $inter_id,
                'ident' => $ident,
                'type' => $type 
        );
        isset ( $datas ['sub_ident'] ) and $map ['sub_ident'] = $datas ['sub_ident'];
        if ($check_duplicate) {
            $row = $this->_load_db ( 'read' )->where ( $map )->get ( self::TAB_USER_NOTIFY_QUEUE )->row_array ();
            if ($row) {
                return true;
            }
        }
        $ex_data = empty ( $datas ['ex_data'] ) ? '' : json_encode ( $datas ['ex_data'] );
        empty ( $datas ['start_time'] ) or $map ['start_time'] = $datas ['start_time'];
        empty ( $datas ['end_time'] ) or $map ['end_time'] = $datas ['end_time'];
        $map ['create_time'] = date ( 'Y-m-d H:i:s' );
        $map ['status'] = 1;
        $map ['ex_data'] = $ex_data;
        return $this->_load_db ()->insert ( self::TAB_USER_NOTIFY_QUEUE, $map );
    }
    /**查找队列
     * @param unknown $type 队列类型
     * @param array $params 格式如：
     * array(
     *      'status'=>'', //队列状态，1待处理，2处理成功，3处理失败，4已过期
     *      'locked'=>'', //是否锁定
     *      'max_oper_times'=>'', //最大处理时间，已被处理的次数大于此值的不会被查找处理
     *      'inter_id'=>'', //
     *      'ident'=>'', //
     *      'sub_ident'=>'', //
     *      'deal_time'=>'', //处理时的时间戳，处理的队列类型有时间限制时才传
     *      'nums'=>'', //查找数量
     *      'offset'=>'', //查找页数
     * )
     * @return unknown
     */
    function get_queues($type, $params = array()) {
        $db_read = $this->_load_db ( 'read' );
        $map = array (
                'type' => $type 
        );
        $map ['status'] = empty ( $params ['status'] ) ? 1 : $params ['status'];
        $map ['locked'] = isset ( $params ['locked'] ) ? $params ['locked'] : 0;
        isset ( $params ['max_oper_times'] ) and $map ['oper_times <'] = $params ['max_oper_times'];
        empty ( $params ['inter_id'] ) or $map ['inter_id'] = $params ['inter_id'];
        if (! empty ( $params ['ident'] )) {
            $map ['ident'] = $params ['ident'];
            isset ( $params ['sub_ident'] ) and $map ['sub_ident'] = $params ['sub_ident'];
        }
        $db_read->where ( $map );
        if (! empty ( $params ['deal_time'] )) {
            $db_read->where ( 'start_time <=', $params ['deal_time'] );
            $db_read->where ( ' ( end_time >=' . $params ['deal_time'] . ' or end_time=0)' );
        }
        if (isset ( $params ['nums'] ) && isset ( $params ['offset'] ))
            $db_read->limit ( $params ['nums'], $params ['offset'] );
        return $db_read->get ( self::TAB_USER_NOTIFY_QUEUE )->result_array ();
    }
    /**处理队列
     * @param unknown $inter_id
     * @param unknown $ident
     * @param unknown $type
     * @param unknown $status
     * @param unknown $sub_ident
     * @param array $datas  格式如：
     * array(
     *      'remark'=>'', //备注
     *      'deal_result'=>'', //业务处理结果，由业务自定义，用于各业务统计队列处理结果
     *      'locked'=>'', //是否锁定
     * )
     */
    function deal_queue($inter_id, $ident, $type, $status = NULL, $sub_ident = NULL, $datas = array()) {
        $map = array (
                'inter_id' => $inter_id,
                'ident' => $ident,
                'type' => $type 
        );
        isset ( $sub_ident ) and $map ['sub_ident'] = $sub_ident;
        $this->db->where ( $map );
        $this->db->set ( 'oper_times', 'oper_times+1', false );
        $data = array (
                'update_time' => date ( 'Y-m-d H:i:s' ) 
        );
        isset ( $status ) and $data ['status'] = $status;
        isset ( $datas ['remark'] ) and $data ['remark'] = $datas ['remark'];
        isset ( $datas ['deal_result'] ) and $data ['deal_result'] = $datas ['deal_result'];
        isset ( $datas ['locked'] ) and $data ['locked'] = $datas ['locked'];
        $result = $data ? $this->db->update ( self::TAB_USER_NOTIFY_QUEUE, $data ) : $this->db->update ( self::TAB_USER_NOTIFY_QUEUE );
    }
}