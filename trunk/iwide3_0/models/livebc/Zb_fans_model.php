<?php
class Zb_fans_model extends CI_Model {
    
    //针对直播的用户资料表
    const TAB_ZB_FANS = 'iwide_zb_fans_ext';
    
    const TAB_FANS = 'iwide_fans';
    const TAB_FANS_EXT = 'iwide_fans_ext';
    
    const TAB_CHANNEL_ZB_FANS = 'iwide_zb_channel_fans';
    
    const TAB_FANS_PLAY_RECORD = 'iwide_zb_fans_play_record';
    
    const TAB_STREAM = 'iwide_zb_stream';
    
    function __construct() {
        parent::__construct ();
    }
    
    
    public function getFansViewChannel($iwideid) {
    
        
        $sql = "
                SELECT S.*
                FROM
                    ".self::TAB_STREAM." AS S ,
                    ".self::TAB_FANS_PLAY_RECORD." AS R
                    
                WHERE
                    S.stream_id = R.stream_id
                    AND R.iwideid = '{$iwideid}'
                ORDER BY 
                   R.create_time DESC
                LIMIT 5
                            ";
        $db_read = $this->db_read();
        
        $data = $db_read->query ( $sql )->result_array ();
             
        return $data;
       
    
    
    }
    
    public function getFansInfoByOpenid($openid,$inter_id) {
        
        $sql = "
            SELECT ZBF.*,F.*
            FROM
                ".self::TAB_FANS." AS F,
                ".self::TAB_ZB_FANS." AS ZBF,
                ".self::TAB_FANS_EXT." AS FEXT
            WHERE
                 FEXT.iwideid = ZBF.iwideid
                 AND 
                 FEXT.openid = F.openid
                 AND
                 F.openid = '{$openid}'
                 AND 
                 F.inter_id = '{$inter_id}'
            LIMIT 1
            ";
        
        $db_read = $this->db_read();
        
        $data = $db_read->query ( $sql )->result_array ();
        
        return $data[0];
        
        
    }
    
    
    public function getFansExtInfoByOpenid($openid) {
    
        $sql = "
            SELECT FEXT.*
            FROM
                ".self::TAB_FANS_EXT." AS FEXT
                    WHERE
               
                    FEXT.openid = '{$openid}'
                    LIMIT 1
                    ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data[0];
    
    
    }
    
    
    public function getFansInfoByIwideid($iwideid,$inter_id) {
        
        $sql = "
            SELECT ZBF.*,F.*
            FROM
                ".self::TAB_FANS." AS F,
                ".self::TAB_ZB_FANS." AS ZBF,
                ".self::TAB_FANS_EXT." AS FEXT
            WHERE
                 FEXT.iwideid = ZBF.iwideid
                 AND 
                 FEXT.openid = F.openid
                 AND
                 FEXT.iwideid = '{$iwideid}'
                 AND 
                 F.inter_id = '{$inter_id}'
            LIMIT 1
            ";
        
        $db_read = $this->db_read();
        
        $data = $db_read->query ( $sql )->result_array ();
        
        return $data[0];
        
    }
    
    /**
     * 
     * @param string $iwideid
     */
    public function insertFansInfo($iwideid){
        
        $sql = "
				SELECT COUNT(*) AS num
				FROM
					".self::TAB_ZB_FANS."
        		WHERE
        			iwideid = '{$iwideid}'
        					";
        $db = $this->db_write();
        $data = $db->query ( $sql )->result_array();
        if( $data[0]['num'] < 1){

            $pams = array (
                'mibi' => 0,
                'iwideid' => $iwideid
                	
            );
        
            $db->insert ( self::TAB_ZB_FANS, $pams );
            	
            	
        }
        
    }
    
    
    
    
    
    private function db_read(){
    
        $db_read = $this->load->database('iwide_r1',true);
        return $db_read;
    
    }
    
    private function db_write(){
    
        return $this->db;
    }
    
    private function db_soma_read(){
    
        $db_soma_read = $this->load->database('iwide_soma_r',true);
        return $db_soma_read;
    
    
    }
    
    
}
