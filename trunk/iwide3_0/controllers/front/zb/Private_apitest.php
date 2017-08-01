<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Private_apitest extends MY_Front_Livebc {

	function __construct() {
	    
		parent::__construct ();
		
	}
	
	public function getChannel(){
	   
	   
	   $channel_id = intval($this->get_source ( 'channel_id', 'int' ));
	   
	   $channel_id = 10001;
	   
	   $this->load->model ( 'livebc/Zb_model' );
	   $this->load->model ( 'livebc/Common_model' );
	   $this->load->model ( 'livebc/Channel_model' );
	   
	   $this->Channel_model->insertChannelFansInfo($this->iwideid,$this->openid,$channel_id);
	   $this->Channel_model->addStreamPlayNumByChannelId($channel_id);
	   
	   $channel_data = $this->Zb_model->getChannelByChannelId($channel_id);
	   
	   unset($channel_data['login_token']);
	   unset($channel_data['login_code']);
	   
	   $stream = $this->Channel_model->get_current_stream($channel_id);
	   
	   $channel_data['live_time'] = strtotime($stream['create_time']);
	   
	   
	   //当前观看者的资料
	   $this->load->model ( 'livebc/Zb_fans_model' );
	   $userinfo = $this->Zb_fans_model->getFansInfoByOpenid($this->openid,$this->inter_id);
	   $channel_data['user_info'] = array(
	       'mibi'=>$userinfo['mibi'],
	       'daxia'=>0,
	       
	   );
	   unset($channel_data['create_time']);
	   unset($channel_data['mibi']);
	   unset($channel_data['daxia']);
	   
	  
	   //观看者资料
	   $online_fans = $this->Zb_model->getChannelOnlineFans($channel_id,$this->inter_id);
	   $players_head_img = array();
	   foreach($online_fans as $fans){
	       $players_head_img[] = $fans['headimgurl'];
	   }
	   $players_num  = count($online_fans);
	   $channel_data['audience'] = $this->Zb_model->getChannelOnlineFansNumber($channel_id,$this->inter_id);
	   $channel_data['audience_photo'] = $players_head_img;
	   
	   
	   //礼品资料
	   $channel_data['gift1_price'] = 10;
	   $channel_data['gift2_price'] = 100;
	  
	   $channel_data['qrcode_url'] = "http://7n.cdn.iwide.cn/app/qrcode.png?t=123";
	   
	   /* $return_data = array();
	   
	   $return_data['play_url'] =  */
	   
	   $intro_goods_array = $this->Zb_model->getIntroGoodsByChannelId($channel_id);
	   
	   
	   $channel_data['goods'] = array();	   
	   $channel_data['goods_quantity'] = count($intro_goods_array);
	   foreach($intro_goods_array as $data){
	       
	       //商品状态为1（可售）时才推荐
	       if($data['status'] == 1){
	           $temp_arr = array();
	           $temp_arr['number'] = 10;
	           $temp_arr['name'] = $data['name'];
	           $temp_arr['info'] = $data['name'];
	           $temp_arr['price'] = $data['price_package'];
	           $temp_arr['gift'] = $data['give_mibi'];
	           $temp_arr['img'] = $data['face_img'];
	           $temp_arr['inter_id'] = $data['inter_id'];
	           $temp_arr['pid'] = $data['product_id'];
	         
	           
	       }
	       
	       $channel_data['goods'][] = $temp_arr;
	          
	       
	   }
	   
	   $this->load->model ( 'livebc/Record_model' );
	   $this->Record_model->refresh_user_active_time($channel_id,$this->iwideid);
	   
	   $this->Common_model->out_put_msg(1,'',$channel_data);
	   
	   
	}
	
	
	public function refreshChannel(){
	
	
	   $channel_id = intval($this->get_source ( 'channel_id', 'int' ));
	   
	   //$channel_id = 1;
	   
	  $this->load->model ( 'livebc/Zb_model' );
	   $this->load->model ( 'livebc/Common_model' );
	   $this->load->model ( 'livebc/Channel_model' );
	   
	   $channel_data = $this->Zb_model->getChannelByChannelId($channel_id);
	   
	   unset($channel_data['login_token']);
	   unset($channel_data['login_code']);
	   
	   $stream = $this->Channel_model->get_current_stream($channel_id);
	   
	   $channel_data['live_time'] = strtotime($stream['create_time']);
	   
	   
	   //当前观看者的资料
	   $this->load->model ( 'livebc/Zb_fans_model' );
	   $userinfo = $this->Zb_fans_model->getFansInfoByOpenid($this->openid,$this->inter_id);
	   $channel_data['user_info'] = array(
	       'mibi'=>$userinfo['mibi'],
	       'daxia'=>0,
	       
	   );
	   unset($channel_data['create_time']);
	   unset($channel_data['mibi']);
	   unset($channel_data['daxia']);
	   
	  
	   //观看者资料
	   $online_fans = $this->Zb_model->getChannelOnlineFans($channel_id,$this->inter_id);
	   $players_head_img = array();
	   foreach($online_fans as $fans){
	       $players_head_img[] = $fans['headimgurl'];
	   }
	   $players_num  = count($online_fans);
	   $channel_data['audience'] = $this->Zb_model->getChannelOnlineFansNumber($channel_id,$this->inter_id);
	   $channel_data['audience_photo'] = $players_head_img;
	   
	   
	   //礼品资料
	   $channel_data['gift1_price'] = 10;
	   $channel_data['gift2_price'] = 100;
	  
	   $channel_data['qrcode_url'] = "http://file.iwide.cn/app/qrcode.png";
	  
	   
	   
	   /* $return_data = array();
	   
	   $return_data['play_url'] =  */
	
	   $this->load->model ( 'livebc/Record_model' );
	   $this->Record_model->refresh_user_active_time($channel_id,$this->iwideid);
	   
	    $this->Common_model->out_put_msg(1,'',$channel_data);
	
	
	}
	
	public function getMsg() {
        $channel_id = $this->get_source ( 'channel_id', 'int' );
        $last_msg_id = $this->get_source ( 'last_msg_id', 'int' );
        $nums = $this->get_source ( 'nums', 'int' );
        empty ( $nums ) and $nums = 10;
        $this->load->model ( 'livebc/Chat_msg_model' );
        $this->load->model ( 'livebc/Common_model' );
        $msgs = $this->Chat_msg_model->get_msg_list ( $channel_id, 1, $last_msg_id, '', $nums );
        $this->Common_model->out_put_msg ( 1, '', $msgs, 'private/getMsg' );
    }
    public function sendMsg() {
        $channel_id = $this->get_source ( 'channel_id', 'int' );
        $content = $this->get_source ( 'msg' );
        $this->load->model ( 'livebc/Chat_msg_model' );
        $this->load->model ( 'livebc/Common_model' );
        $result = $this->Chat_msg_model->add_chat_msg ( $channel_id, $content,'user', array (
                'openid' => $this->openid,
                'iwideid' => $this->iwideid 
        ) );
        $result ? $this->Common_model->out_put_msg ( 1, '', $result, 'private/sendMsg' ) : $this->Common_model->out_put_msg ( 2, '提交失败', $result, 'private/sendMsg', 1 );
    }
	public function sendGift(){
	    $channel_id = $this->get_source ( 'channel_id', 'int' );
	    $gift_id = $this->get_source ( 'gift_id', 'int' );
	    $gift_num = $this->get_source ( 'send_num', 'int' );
	    $this->load->model ( 'livebc/Gift_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $result = $this->Gift_model->send_gift ( $channel_id, $gift_id, $gift_num,array (
                'openid' => $this->openid,
                'iwideid' => $this->iwideid 
        ));
	    $result['s']==1 ? $this->Common_model->out_put_msg ( 1, '', $result, 'private/sendGift' ) : $this->Common_model->out_put_msg ( 2, $result['errmsg'], $result, 'private/sendGift', 1 );
	}
	
	public function test(){
	    
	    $this->load->model ( 'livebc/Record_model' );
	    
	    $this->Record_model->buy_goods_add_mibi(12393,'ohQSEuB9Y5RHLoImm_owz7CbqJyg',1);
	    //$this->Record_model->buy_goods_add_mibi($pid,$zbcode,$channel_id);
	}
	
	
	
}
