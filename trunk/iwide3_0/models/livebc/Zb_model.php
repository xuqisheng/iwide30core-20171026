<?php
class Zb_model extends CI_Model {
    
    const TAB_CHANNEL = "iwide_zb_channel";
    const TAB_INTRO_GOODS = "iwide_zb_intro_goods";
    const TAB_SOMA_GOODS = 'iwide_soma_catalog_product_package';
    
    const TAB_FANS_EXT = 'iwide_fans_ext';
    const TAB_FANS = 'iwide_fans';
    
   // var $redis_proxy;
    
    
    function __construct() {
        parent::__construct ();
        
        $this->load->library ( 'Cache/Redis_proxy', array (
            'not_init' => FALSE,
            'module' => 'common',
            'refresh' => FALSE,
            'environment' => ENVIRONMENT
        ), 'redis_proxy' );
        
        
        
    }
    
    
    /**
     * 取频道内容
     * @param int $channel_id
     */
    public function getChannelByChannelId($channel_id){
        
        $sql = "
            SELECT *
            FROM
                ".self::TAB_CHANNEL."
            WHERE
                 channel_id = {$channel_id}
            LIMIT 1
            ";
        
        $db_read = $this->db_read();
        
        $data = $db_read->query ( $sql )->result_array ();
        
        return $data[0];
        
    }
    
    /**
     * 取所有频道
     * @param int $channel_id
     */
    public function getAllChannelId(){
    
        $sql = "
            SELECT channel_id
            FROM
                ".self::TAB_CHANNEL."
      
        ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data;
    
    }
    
    
    /**
     * 取频道内容
     * @param string $login_code
     */
    public function getChannelByLoginCode($login_code){
    
        $sql = "
            SELECT *
            FROM
                ".self::TAB_CHANNEL."
                    WHERE
                    login_code = '{$login_code}'
            LIMIT 1
        ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data[0];
    
    }
    
    /**
     * 取频道粉丝
     * @param string $login_code
     */
    public function getChannelOnlineFans($channel_id,$inter_id = ""){
    
        $this->load->model ( 'livebc/Record_model' );
        
        $iwide_id_arr = $this->Record_model->get_latest_active_time($channel_id);
        
        $limit_time = time() - 120;

        $online_iwide_id_arr = array();
        foreach($iwide_id_arr as $key => $data){
            
            if($data > $limit_time ){
                
                $online_iwide_id_arr[] = "'{$key}'";
                
            }
            
        }
        
        if(count($online_iwide_id_arr) < 1){
            
            return array();
            
        }
        
        $online_iwide_id_csv = implode(",", $online_iwide_id_arr);
        
        $addsql = "";
        if($inter_id != ""){
            $addsql = " AND F.inter_id = '{$inter_id}' ";
        }
        
        $sql = "
            SELECT F.*
            FROM
                ".self::TAB_FANS." AS F,
               ".self::TAB_FANS_EXT." AS FX   
                    WHERE
                    F.openid = FX.openid
                    AND
                    FX.iwideid in ({$online_iwide_id_csv})
                    {$addsql}
                    ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data;
    
    }
    
    /**
     * 取频道粉丝 总数
     * @param string $login_code
     */
    public function getChannelOnlineFansNumberHaveRobet($channel_id,$inter_id = "",$addNum = 0){
    
        $this->load->library ( 'Cache/Redis_proxy', array (
            'not_init' => FALSE,
            'module' => 'common',
            'refresh' => FALSE,
            'environment' => ENVIRONMENT
        ), 'redis_proxy' );
        
        $num = $this->redis_proxy->incr("zb_channel_robet_".$channel_id);
        if($num > 10 ){
            
            $addNum = $addNum?$addNum:intval(rand(0,3));
            $num = $this->redis_proxy->incrBy("zb_channel_robet_".$channel_id,$addNum);
                        
        }
                
        return $num;
        
    }
    
    /**
     * 取频道粉丝 总数
     * @param string $login_code
     */
    public function getChannelOnlineFansNumberHaveRobetNoAdd($channel_id,$inter_id = ""){
    
        $this->load->library ( 'Cache/Redis_proxy', array (
            'not_init' => FALSE,
            'module' => 'common',
            'refresh' => FALSE,
            'environment' => ENVIRONMENT
        ), 'redis_proxy' );
    
        return $this->redis_proxy->get("zb_channel_robet_".$channel_id);
        
    
    }
    
    
    /**
     * 取频道粉丝 总数
     * @param string $login_code
     */
    public function getChannelOnlineFansNumber($channel_id,$inter_id = ""){
        
        
        $this->load->library ( 'Cache/Redis_proxy', array (
            'not_init' => FALSE,
            'module' => 'common',
            'refresh' => FALSE,
            'environment' => ENVIRONMENT
        ), 'redis_proxy' );
        
        return $this->redis_proxy->get("zb_channel_".$channel_id);
        
        $this->load->model ( 'livebc/Record_model' );
        
        $iwide_id_arr = $this->Record_model->get_latest_active_time($channel_id);
        
        $limit_time = time() - 120;

        $online_iwide_id_arr = array();
        foreach($iwide_id_arr as $key => $data){
            
            if($data > $limit_time ){
                
                $online_iwide_id_arr[] = "'{$key}'";
                
            }
            
        }
        
        if(count($online_iwide_id_arr) < 1){
            
            return array();
            
        }
        
        
       // return count($online_iwide_id_arr);
        
        $online_iwide_id_csv = implode(",", $online_iwide_id_arr);
        
        $addsql = "";
        if($inter_id != ""){
            $addsql = " AND F.inter_id = '{$inter_id}' ";
        }
        
        $sql = "
            SELECT count(*) as num
            FROM
                ".self::TAB_FANS." AS F,
               ".self::TAB_FANS_EXT." AS FX   
                    WHERE
                    F.openid = FX.openid
                    AND
                    FX.iwideid in ({$online_iwide_id_csv})
                    {$addsql}
                    ";
    
        $db_read = $this->db_read();
    
        $data = $db_read->query ( $sql )->result_array ();
    
        return $data[0]['num'];
    
        
    }
    
    
   
    /**
     * 取频道的介绍商品
     * @param int $channel_id
     */
    public function getIntroGoodsByChannelId($channel_id){
        
        $sql = "
            SELECT goods_id,give_mibi
            FROM
                ".self::TAB_INTRO_GOODS."
                        WHERE
                        channel_id = {$channel_id}
        ";
        
        $db_read = $this->db_read();
        
        $data = $db_read->query ( $sql )->result_array ();
        
        if(count($data) < 1){
            return array();
        }
        
        //将goods id 组成数组，并转成csv
        $goods_id_arr = array();
        $intro_goods_arr = array();
        foreach($data as $intro_goods){
            $goods_id_arr[] = $intro_goods['goods_id'];
            
            //将 goods_id 为作数组 key ，方便下面关联两张表
            $intro_goods_arr[$intro_goods['goods_id']] = $intro_goods;
        }
        
        $goods_id_csv = implode(",", $goods_id_arr);
        
        
        //取商城数据库中的商品表，通过goods_id
        $sql = "
            SELECT *
            FROM
                ".self::TAB_SOMA_GOODS."
            WHERE
                 product_id in ({$goods_id_csv})
            ";
        
        $db_soma_read = $this->db_soma_read();
        
        $data = $db_soma_read->query ( $sql )->result_array ();
        
        if(count($data) < 1){
            return array();
        }
        
        
        foreach($data as $key => $d){
            
            //商品对应给多少米币，从$intro_goods_arr找出介绍商品配置里的米币数
            $data[$key]['give_mibi'] = $intro_goods_arr[$d['product_id']]['give_mibi'];
            
        }
        
        return $data;
        
        
        //product_id
        
    }
    
    public function kb_build_token(){
        
        return md5(rand(1000000000,9000000000));
        
    }
    
    /**
     * 设置频道login token
     * @param String $token
     * @param int $channel_id
     */
    public function setTokenToChannel($token,$channel_id){
        
        $sql = "UPDATE
                    ".self::TAB_CHANNEL."
                SET
                    login_token = '{$token}'
                WHERE
                    channel_id = {$channel_id}
                    ";
        
        $db_write = $this->db_write();
        
        $db_write->query ( $sql );
        
        return $db_write->affected_rows();
        
    }
    
    public function getGoodsUrlByPidInterId($pid,$inter_id,$openid,$channel_id,$zburl){
         
        $this->load->model('wx/publics_model');
        $public_info= $this->publics_model->get_public_by_id( $inter_id );
         
        $domain = $public_info['domain'];
         
        $url = "http://{$domain}/index.php/soma/package/package_detail?pid={$pid}&id={$inter_id}&zbcode={$this->openid}&channelid={$channel_id}&zburl=".urlencode($zburl);
         
        return $url;
         
         
    }
    
    public function getOrderFinishUrl($inter_id){
         
        $this->load->model('wx/publics_model');
         
        $public_info= $this->publics_model->get_public_by_id( $inter_id );
    
        $domain = $public_info['domain'];
         
        $url = "http://{$domain}/index.php/zb/zb/success_buy";
        return $url;
         
    }
    
    public function getChannelOnlineTime($channel_id){
        
       
       // $online_num = $this->redis_proxy->incr("zb_channel_".$channel_id);
        
        $user_time_arr = $this->redis_proxy->hGetAll('zb_user_active_time:' . $channel_id);
        
        return $user_time_arr;
        
    }
    
    public function delOnlineMemberHashKeyByKey($channel_id,$key){
    

        $this->redis_proxy->hDel('zb_user_active_time:' . $channel_id,$key);

    }
    
    public function setOnlineNum($channel_id,$online_num){
       
        $this->redis_proxy->set("zb_channel_".$channel_id,$online_num);
    
    }
    
    public function getOnlineNum($channel_id){
         
        return $this->redis_proxy->get("zb_channel_".$channel_id);
    
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
