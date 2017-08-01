<?php
class Chat_msg_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_CHAT = 'zb_chat';
    const DEFAULT_USER_NAME = '匿名用户';
    public function get_msg_list($channel_id, $status = NULL, $last_msg_id = 0, $msg_type = '', $nums = NULL, $get_user_info = TRUE) {
        $db = $this->load->database ( 'iwide_r1', true );
        $db->where ( 'channel_id', $channel_id );
        empty ( $last_msg_id ) or $db->where ( 'chat_id >', $last_msg_id );
        empty ( $msg_type ) or $db->where ( 'msg_type', $msg_type );
        isset ( $status ) ? $db->where ( 'status', $status ) : $db->where_in ( 'status', array (
                0,
                1 
        ) );
        isset ( $nums ) and $db->limit ( $nums );
        if (! $last_msg_id) {
            $db->order_by ( 'chat_id desc' );
        }
        $msgs = $db->get ( self::TAB_CHAT )->result_array ();
        if ($msgs) {
            if (! $last_msg_id) {
                $msgs = array_reverse ( $msgs );
            }
            $user_ids = array ();
            foreach ( $msgs as &$m ) {
                $m ['msg_id'] = $m ['chat_id'];
                if ($m ['msg_type'] == 'user') {
                    $m ['type'] = 1;
                    $user_ids [] = $m ['iwideid'];
                } else {
                    $m ['type'] = 2;
                }
            }
            $fans_info = array ();
            if ($user_ids) {
                $this->load->model ( 'livebc/User_model' );
                $fans_info = $this->User_model->get_fans_info ( $user_ids );
                $fans_info = array_column ( $fans_info, NULL, 'iwideid' );
            }
            foreach ( $msgs as &$m ) {
                if ($m ['iwideid'] && isset ( $fans_info [$m ['iwideid']] )) {
                    $m ['name'] = $fans_info [$m ['iwideid']] ['nickname'];
                } else {
                    $m ['name'] = self::DEFAULT_USER_NAME;
                }
            }
        }
        return $msgs;
    }
    public function add_system_msg($channel_id, $type, $user_info = array(), $params = array()) {
        $content = '';
        switch ($type) {
            case 'send_gift' :
                $this->load->model ( 'livebc/User_model' );
                $fans_info = $this->User_model->get_fans_info ( $user_info ['iwideid'] );
                if ($fans_info) {
                    $user_name = $fans_info [0] ['nickname'];
                } else {
                    $user_name = self::DEFAULT_USER_NAME;
                }
                $content = $user_name . ' ' . $params ['msg'];
                break;
            default :
                break;
        }
        if ($content)
            return $this->add_chat_msg ( $channel_id, $content, 'system', $user_info );
        return FALSE;
    }
    public function add_chat_msg($channel_id, $content = '', $msg_type = '', $user_info = array()) {
        $db = $this->db;
        $data = array (
                'channel_id' => $channel_id,
                'msg' => strip_tags($content),
                'msg_type' => $msg_type,
                'create_time' => date ( 'Y-m-d H:i:s' ) 
        );
        empty ( $user_info ['openid'] ) or $data ['openid'] = $user_info ['openid'];
        empty ( $user_info ['iwideid'] ) or $data ['iwideid'] = $user_info ['iwideid'];
        return $db->insert ( self::TAB_CHAT, $data );
    }
}
