<?php
class Gift_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_GIFT = 'zb_gift';
    public function get_gift_list($status = NULL, $nums = NULL, $offset = NULL) {
        $db = $this->load->database ( 'iwide_r1', true );
        isset ( $status ) ? $db->where ( 'status', $status ) : $db->where_in ( 'status', array (
                0,
                1 
        ) );
        isset ( $nums ) and $db->limit ( $nums, $offset );
        return $db->get ( self::TAB_GIFT )->result_array ();
    }
    public function get_gift($gift_id, $status = NULL) {
        $db = $this->load->database ( 'iwide_r1', true );
        isset ( $status ) ? $db->where ( 'status', $status ) : $db->where_in ( 'status', array (
                0,
                1 
        ) );
        $db->where ( 'gift_id', $gift_id );
        $db->limit ( 1 );
        return $db->get ( self::TAB_GIFT )->row_array ();
    }
    public function send_gift($channel_id, $gift_id, $give_num, $user_info = array()) {
        if ($give_num > 0) {
            $gift = $this->get_gift ( $gift_id, 1 );
            if ($gift) {
                $this->load->model ( 'livebc/User_model' );
                $user_info = $this->User_model->get_zb_fans_ext ( $user_info ['iwideid'] );
                if ($user_info) {
                    $cost = $gift ['mibi'] * $give_num;
                    if ($cost > 0 && $user_info ['mibi'] >= $cost) {
                        $reduce_result = $this->User_model->change_fans_mibi ( $user_info ['iwideid'], $cost * - 1 );
                        if ($reduce_result) {
                            $this->load->model ( 'livebc/Record_model' );
                            $this->load->model ( 'livebc/Channel_model' );
                            $stream = $this->Channel_model->get_current_stream($channel_id);
                            $stream_id = $stream['stream_id'];
                            
                            $this->Record_model->add_mibi_record ( $cost * - 1, 'give', '用户赠送礼品【' . $gift ['gift_name'] . '】', $user_info, array (
                                    'to_channel' => $channel_id,
                                    'stream_id'=>$stream_id
                            ), array (
                                    'give_gift_id' => $gift_id,
                                    'give_gift_num' => $give_num 
                            ) );
                            $this->load->model ( 'livebc/Chat_msg_model' );
                            $this->Chat_msg_model->add_system_msg ( $channel_id, 'send_gift', $user_info, array (
                                    'msg' => '赠送了' . $gift ['gift_name'] . $give_num . '份!' 
                            ) );
                            return array (
                                    's' => 1,
                                    'errmsg' => '赠送成功',
                                    'left_mibi' => $user_info ['mibi'] - $cost
                            );
                        } else {
                            return array (
                                    's' => 0,
                                    'errmsg' => '扣减失败' 
                            );
                        }
                    } else {
                        return array (
                                's' => 0,
                                'errmsg' => '您的米币不足哦' 
                        );
                    }
                } else {
                    return array (
                            's' => 0,
                            'errmsg' => '赠送失败' 
                    );
                }
            } else {
                return array (
                        's' => 0,
                        'errmsg' => '暂不能赠送' 
                );
            }
        }
        return array (
                's' => 0,
                'errmsg' => '参数错误' 
        );
    }
}
