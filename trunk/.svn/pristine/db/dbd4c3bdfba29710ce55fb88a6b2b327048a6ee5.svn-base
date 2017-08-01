<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_api extends CI_Controller {
    
    var $inter_id;
    var $sysconfig;
    
    function __construct(){
        parent::__construct ();
        include_once APPPATH."config/zb_config.php";
        $this->inter_id = ZB_INTER_ID;
        $this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
       
        
    }
    
    public function clearOffline(){
        
        $this->load->model ( 'livebc/Zb_model' );
        
        $channel_list = $this->Zb_model->getAllChannelId();
        
        foreach($channel_list as $channel_data){
            
            $channel_id = $channel_data['channel_id'];
            
          
            
            $user_time_list = $this->Zb_model->getChannelOnlineTime($channel_id);
            
            
            $now_time = time();
            
            //清除数量
            $remove_num = 0;
            
            $total_num = count($user_time_list);
            
            foreach ($user_time_list as $key => $user_time){
            
                if($user_time + 120 < $now_time){
            
                    $this->Zb_model->delOnlineMemberHashKeyByKey($channel_id,$key);
            
                    $remove_num++;
            
                }
            
            }
            
            $total_num -= $remove_num;
            
            $this->Zb_model->setOnlineNum($channel_id,$total_num);
             
            $user_time_list = $this->Zb_model->getChannelOnlineTime($channel_id);
            
        }
        
        echo "success";
        
    }
    
    
	
	
// 	public function getChannel(){
// 	    $login_token = $this->get_source ( 'token', '', FALSE );
// 	    $this->load->model ( 'livebc/Channel_model' );
// 	    $this->load->model ( 'livebc/Common_model' );
// 	    $channel = $this->Channel_model->get_channel ( $login_token , 'login_token');
// 	    if (! $channel) {
// 	        $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/getChannel', 1 );
// 	    }
// 	    $this->Common_model->out_put_msg ( 1, '', $channel, 'public/getChannel' );
// 	}
// 	public function refreshChannel(){
	    
// 	}
	
}
