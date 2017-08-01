<?php
include_once APPPATH."models/livebc/MY_ZB_model.php";
class Channel_model extends MY_ZB_model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_CHANNEL = 'zb_channel';
    const TAB_CHANNEL_FANS = 'zb_channel_fans';
    const TAB_STREAM = 'iwide_zb_stream';
    const TAB_FANS_PLAY_RECORD = 'iwide_zb_fans_play_record';
    
    const STATE_STOP = 2;
    
    public function get_channel($ident, $field = 'channel_id', $status = NULL) {
        $db = $this->load->database ( 'iwide_r1', true );
        if (in_array ( $field, array (
                'channel_id',
                'login_token',
                'login_code' 
        ) )) {
            $db->where ( $field, $ident );
            isset ( $status ) ? $db->where ( 'status', $status ) : $db->where_in ( 'status', array (
                    -1,
                    0,
                    1 
            ) );
            $db->limit ( 1 );
            return $db->get ( self::TAB_CHANNEL )->row_array ();
        }
        return array ();
    }
    public function update_channel_info($channel_id, $data) {
        $updata = array ();
        empty ( $data ['nickname'] ) or $updata ['nickname'] = $data ['nickname'];
        empty ( $data ['head_img'] ) or $updata ['head_img'] = $data ['head_img'];
        empty ( $data ['title'] ) or $updata ['channel_title'] = $data ['title'];
        if ($updata) {
            $db = $this->db;
            $db->where ( 'channel_id', $channel_id );
            return $db->update ( self::TAB_CHANNEL, $updata );
        }
        return FALSE;
    }
    
    public function update_stream_info($channel_id, $data) {
        $updata = array ();
        empty ( $data ['nickname'] ) or $updata ['nickname'] = $data ['nickname'];
        empty ( $data ['head_img'] ) or $updata ['head_img'] = $data ['head_img'];
        empty ( $data ['title'] ) or $updata ['channel_title'] = $data ['title'];
        if ($updata) {
            $db = $this->db;
            $db->where ( 'channel_id', $channel_id );
            return $db->update ( self::TAB_CHANNEL, $updata );
        }
        return FALSE;
    }
    
    /**
     * 取当前channel id 的stream
     * @param int $channel_id
     * @return unknown
     */
    public function get_current_stream($channel_id) {
       
       $sql = "
            SELECT *
            FROM
                ".self::TAB_STREAM."
            WHERE                
                channel_id = '{$channel_id}'
            ORDER BY stream_id DESC
            LIMIT 1
            ";
        
        $db_read = $this->db_read();
        
        $data = $db_read->query ( $sql )->result_array ();
        
        return $data[0];
        
    }
    
    
    /**
     * 取stream
     * @param int $channel_id
     * @return unknown
     */
    public function get_stream_id($stream_id) {
         
        $sql = "
            SELECT *
            FROM
                ".self::TAB_STREAM."
                    WHERE
                    stream_id = '{$stream_id}'
                    ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data[0];
    
    }
    
    /**
     * 生成新的stream
     * @param unknown $channel_id
     * @return int stream id
     */
    public function build_new_stream($channel_id,$nickname,$head_img,$title) {
    
       $db = $this->db_write();
       $rooms = array(
						'channel_id'    => $channel_id,
						'nickname'    => $nickname,
						'head_img'        => $head_img,
						'channel_title' => $title,
						'create_time'     => date("Y-m-d H:i:s")
				
					);
		$db->insert('zb_stream', $rooms);
		$room_id = $db->insert_id();
    
        return $room_id;
    
    }
    
    
    public function insertChannelFansInfo($iwideid,$openid,$channel_id){
    
        $sql = "
				SELECT COUNT(*) AS num
				FROM
					iwide_".self::TAB_CHANNEL_FANS."
    					WHERE
    					iwideid = '{$iwideid}'
    					AND channel_id = {$channel_id}
    					AND openid = '{$openid}'
    					";
        $db = $this->db_write();
        $data = $db->query ( $sql )->result_array();
        if( $data[0]['num'] < 1){
    
            $pams = array (
                'iwideid' => $iwideid,
                'openid' => $openid,
                'view_time' => date("Y-m-d H:i:s"),
                'create_time'  => date("Y-m-d H:i:s"),
                'channel_id' => $channel_id,
                'online' =>1
            );
        
            $db->insert ( self::TAB_CHANNEL_FANS, $pams );
             
             
        }else{
            
            $pams = array (
                'view_time' => date("Y-m-d H:i:s"),
                'online' =>1
            );
            
            $db->where ( 'iwideid', $iwideid )->where ( 'openid', $openid )->where ( 'channel_id', $channel_id );
            $db->update ( self::TAB_CHANNEL_FANS, $pams );
            
        }
    
    }
    
    public function addStreamPlayNumByChannelId($channel_id,$num = 1){
    
    
        $stream = $this->get_current_stream($channel_id);
        
        $this->addStreamPlayNum($stream['stream_id'],$num);
    
    }
    
    public function addStreamPlayNum($stream_id,$num = 1){
        
        
        $sql = "
            UPDATE
                ".self::TAB_STREAM."
            SET
                play_num = play_num + {$num}
            WHERE
                stream_id = {$stream_id}
            ";
        
       $db = $this->db_write();
       $db->query($sql);
        
    }
    
    public function addFansPlayRecord($iwideid,$stream_id){
    
    
        $sql = "
            REPLACE INTO
            ".self::TAB_FANS_PLAY_RECORD."
            (iwideid,stream_id,create_time)
            VALUE
            ('{$iwideid}','{$stream_id}',NOW())
        ";
    
        $db = $this->db_write();
        $db->query($sql);
    
    }
    
    
    public function closeStream($stream_id){
    
    
        $sql = "
        UPDATE
                ".self::TAB_STREAM."
                    SET
                    status = ".self::STATE_STOP."
        WHERE
        stream_id = {$stream_id}
        ";
    
        $db = $this->db_write();
        $db->query($sql);
        
        return $db->affected_rows();
    
    }
    
    public function setStreamStatus($stream_id,$status,$status_not_is,$callback_id){
    
    
        $sql = "
        UPDATE
                ".self::TAB_STREAM."
                    SET
                    status = $status,
                    callback_id = {$callback_id}
                        WHERE
                        stream_id = {$stream_id}
                        AND status != {$status_not_is}
                        AND callback_id <= {$callback_id}
        ";
    
        $db = $this->db_write();
        $db->query($sql);
    
        return $db->affected_rows();
    
    }
    
    
}
