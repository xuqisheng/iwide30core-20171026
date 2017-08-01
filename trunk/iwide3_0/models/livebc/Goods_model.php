<?php
include_once APPPATH."models/livebc/MY_ZB_model.php";
class Goods_model extends MY_ZB_model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_INTRO_GOODS = 'iwide_zb_intro_goods';
    public function get_intro_goods($channel_id, $status = NULL, $nums = NULL, $offset = NULL) {
        $db = $this->load->database ( 'iwide_r1', true );
        $db->where ( 'channel_id', $channel_id );
        isset ( $status ) ? $db->where ( 'status', $status ) : $db->where_in ( 'status', array (
                0,
                1 
        ) );
        isset ( $nums ) and $db->limit ( $nums, $offset );
        return $db->get ( self::TAB_INTRO_GOODS )->result_array ();
    }
    
    /**
     * 取频道的介绍商品
     * @param int $channel_id
     * @param int $goods_id
     */
    public function getIntroGoodsByChannelIdAndGoodsId($channel_id,$goods_id){
    
        $sql = "
            SELECT goods_id,give_mibi
            FROM
                ".self::TAB_INTRO_GOODS."
                    WHERE
                    channel_id = {$channel_id}
                    AND goods_id = {$goods_id}
            LIMIT 1
        ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data[0];
    
    
        //product_id
    
    }
}
