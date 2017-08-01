<?php
include_once APPPATH."models/livebc/MY_ZB_model.php";
class Record_model extends MY_ZB_model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_MIBI_RECORD = 'zb_mibi_record';
    const TAB_MIBI_RECORD_FULLNAME = 'iwide_zb_mibi_record';
    const TAB_ZB_FANS = 'iwide_zb_fans_ext';
    const TAB_SOMA_GOODS = 'iwide_soma_catalog_product_package';
    
    
    const RECORD_BUY = 'buy';
    const RECORD_GIVE = 'give';
    const RECORD_SYSTEM = 'system';
    
    public function add_mibi_record($amount, $record_type, $remark = '', $user_info = array(), $channel_info = array(), $item_info = array()) {
        $data = array (
                'mibi_change_num' => $amount,
                'record_type' => $record_type,
                'remark' => $remark 
        );
        $data ['iwideid'] = empty ( $user_info ['iwideid'] ) ? '' : $user_info ['iwideid'];
        $data ['channel_id'] = empty ( $channel_info ['to_channel'] ) ? 0 : $channel_info ['to_channel'];
        $data ['buy_goods_from_channel_id'] = empty ( $channel_info ['buy_goods_from_channel'] ) ? 0 : $channel_info ['buy_goods_from_channel'];
        $data ['gift_id'] = empty ( $item_info ['give_gift_id'] ) ? 0 : $item_info ['give_gift_id'];
        $data ['give_gift_num'] = empty ( $item_info ['give_gift_num'] ) ? 0 : $item_info ['give_gift_num'];
        $data ['goods_id'] = empty ( $item_info ['from_goods_id'] ) ? 0 : $item_info ['from_goods_id'];
        $data ['buy_num'] = empty ( $item_info ['buy_num'] ) ? 0 : $item_info ['buy_num'];
        $data ['create_time'] = date ( 'Y-m-d H:i:s' );
        $data['stream_id'] = empty ( $channel_info ['stream_id'] ) ? 0 : $channel_info ['stream_id'];
        $db = $this->db;
        return $db->insert ( self::TAB_MIBI_RECORD, $data );
    }
    public function refresh_user_active_time($channel_id, $iwideid) {
        $this->load->library ( 'Cache/Redis_proxy', array (
                'not_init' => FALSE,
                'module' => 'common',
                'refresh' => FALSE,
                'environment' => ENVIRONMENT 
        ), 'redis_proxy' );
        $this->redis_proxy->hSet ( 'zb_user_active_time:' . $channel_id, $iwideid, time () );
    }
    public function get_latest_active_time($channel_id, $iwideid = NULL) {
        $this->load->library ( 'Cache/Redis_proxy', array (
                'not_init' => FALSE,
                'module' => 'common',
                'refresh' => FALSE,
                'environment' => ENVIRONMENT 
        ), 'redis_proxy' );
        return isset ( $iwideid ) ? $this->redis_proxy->hGet ( 'zb_user_active_time:' . $channel_id, $iwideid ) : $this->redis_proxy->hGetAll ( 'zb_user_active_time:' . $channel_id );
    }
    
    /**
     * 购买商品后调用
     * @param unknown $goods_id
     * @param unknown $zbcode
     * @param unknown $channel_id
     */
    public function buy_goods_add_mibi($goods_id,$zbcode,$channel_id,$buy_num = 1){
        
        
        MYLOG::w("add mibi : {$goods_id} ,{$zbcode},{$channel_id},{$buy_num}","mibi_record");
        $this->load->model('livebc/Goods_model');
        
        $goodsinfo = $this->Goods_model->getIntroGoodsByChannelIdAndGoodsId($channel_id,$goods_id);
        $this->load->model ( 'livebc/Zb_fans_model' );
        $this->load->model ( 'livebc/Record_model' );
         
        $userinfo = $this->Zb_fans_model->getFansExtInfoByOpenid($zbcode);
        $channel_info = array();
        $channel_info['buy_goods_from_channel'] = $channel_id;    
        $goodsinfo ['from_goods_id'] = $goods_id;
        
        if($goodsinfo['give_mibi'] > 0){
            $goodsinfo['buy_num'] = $buy_num;
            $give_mibi = $goodsinfo['give_mibi']*$buy_num;
            
            $db_write = $this->db;
            $db_write->trans_begin();
            
            $insertid = $this->add_mibi_record($give_mibi,self::RECORD_BUY,"购买商品赠送米币",$userinfo,$channel_info,$goodsinfo);
            
            if($insertid){
                
                $addNum = $goodsinfo['give_mibi'] * $buy_num;
                $sql = "
                    UPDATE
                        ".self::TAB_ZB_FANS."
                    SET
                        mibi = mibi + {$addNum}
                    WHERE
                        iwideid = '{$userinfo['iwideid']}'
                    ";
                
                $db_write = $this->db;
                $db_write->query($sql);
                
                if($db_write->affected_rows()){
                    $db_write->trans_commit ();
                    return true;
                }else{
                    $db_write->trans_rollback ();
                    $content = "goods_id={$goods_id},zbcode={$zbcode},channel_id={$channel_id},buy_num={$buy_num} | userinfo=".json_encode($userinfo)." | channel_info=".json_encode($channel_info)." | goodsinfo=".json_encode($goodsinfo);
                    MYLOG::w("","zb_error","add_record_error");
                    return false;
                }
                                              
                
                
            }else{
                $db_write->trans_rollback ();
                $content = "goods_id={$goods_id},zbcode={$zbcode},channel_id={$channel_id},buy_num={$buy_num} | userinfo=".json_encode($userinfo)." | channel_info=".json_encode($channel_info)." | goodsinfo=".json_encode($goodsinfo);
                MYLOG::w("","zb_error","add_record_error");
                return false;
            }
            
        }
        
    }
    
    
    /**
     * 取用户的米币记录
     * @param string $iwideid
     * @param int $start
     * @param int $len
     */
    public function get_fans_mibi_record($iwideid,$start = 0,$len = 100){
        
        $sql = "
            SELECT 
                *
            FROM
                ".self::TAB_MIBI_RECORD_FULLNAME."
            WHERE
                iwideid = '{$iwideid}'
            ORDER BY
                mibi_record_id DESC
            LIMIT 
                {$start},{$len}
            ";
        
         $db_read = $this->db_read();
                
         $data = $db_read->query ( $sql )->result_array ();
                
        return $data;
        
    }
    
    
    public function get_fans_buy_record_num($iwideid,$start = 0,$len = 100){
    
        $sql = "
            SELECT
                count(*) as num
            FROM
                ".self::TAB_MIBI_RECORD_FULLNAME."
                    WHERE
                    iwideid = '{$iwideid}'
                    AND goods_id > 0
                    ORDER BY
                    mibi_record_id DESC
                    LIMIT
                    {$start},{$len}
                    ";
    
                    $db_read = $this->db_read();
    
                    $data = $db_read->query ( $sql )->result_array ();
    
                    return $data[0]['num'];
    
    }
    
    
    public function get_channel_mibi_record($channel_id,$stream_id){
    
        $sql = "
            SELECT
                gift_id,SUM(give_gift_num) as total
            FROM
                ".self::TAB_MIBI_RECORD_FULLNAME."
            WHERE
                    channel_id = '{$channel_id}'
                    AND stream_id = '{$stream_id}'
            GROUP BY gift_id
        
            ";
    
            $db_read = $this->db_read();
    
            $data = $db_read->query ( $sql )->result_array ();
    
           return $data;
    
    }
    
    public function get_user_current_record_and_goods($iwide_id){
    
        $sql = "
            SELECT
                R.*
            FROM
                ".self::TAB_MIBI_RECORD_FULLNAME." as R
                    WHERE
                    R.iwideid = '{$iwide_id}'
                    AND 
                    R.goods_id != 0
                    ORDER by R.mibi_record_id DESC
                    ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        if($data && isset($data[0])){
            
            $sql = "
            SELECT *
            FROM
                ".self::TAB_SOMA_GOODS."
                            WHERE
                            product_id = {$data[0]['goods_id']}
                            ";
            
            $db_soma_read = $this->db_soma_read();
            
            $data_soma = $db_soma_read->query ( $sql )->result_array ();
            
            $return_data = $data[0];
            
            if(isset($data_soma[0])){
                $return_data = array_merge($return_data,$data_soma[0]);
            }

            return $return_data;
        }else{
            return null;
        }
        
    
    }
    
    
    
    
    
}
