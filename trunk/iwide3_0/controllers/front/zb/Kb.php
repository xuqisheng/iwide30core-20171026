<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Kb extends CI_Controller {

    
	function __construct() {
         // 指定允许其他域名访问  
        header('Access-Control-Allow-Origin:*');  
        // 响应类型  
        header('Access-Control-Allow-Methods:POST');  
        // 响应头设置  
        header('Access-Control-Allow-Headers:x-requested-with,content-type');  
	    //$_GET['scope'] = "snsapi_userinfo";
	    $_GET['id'] = "a469428180";
		parent::__construct ();
		
		$this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		
	}
	
	
	public function login(){
	    
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    
	    /* {
	        send_data: {
	            login_code:code//邀请码
	        }
	        token:null  //为空
	    } */
	    
	    $code = $this->source['send_data']['login_code'];
	     
	    
	   // $code = $_REQUEST["login_code"];
	    
	    $channel_data = $this->Zb_model->getChannelByLoginCode($code);
	    
	    if($channel_data != ""){
	        
	        $token = $this->Zb_model->kb_build_token();
	        
	        $state = $this->Zb_model->setTokenToChannel($token,$channel_data['channel_id']);
	        
	        $data = array();
	        
	        if($state > 0){
	            $data['token'] = $token;
	            $data['userinfo'] = array(
	                'channel_id'=>$channel_data['channel_id'],
	                'room_id'=>$channel_data['channel_id'],
	                'stream_id'=>"stream-".$channel_data['channel_id'],
	                'username'=>$channel_data['nickname'],
	                'title'=>$channel_data['title'],
	                'video_bitrate'=>$channel_data['video_bitrate'],
	                'video_width'=>$channel_data['video_width'],
	                'video_height'=>$channel_data['video_height'],
	            );
	            
	            $this->Common_model->out_put_msg(1,'',$data);
	            
	        }else{
	            
	            $this->Common_model->out_put_msg(0,'登录超时,code:001');
	            
	        }
	        
	       
	         
	        
	        
	        
	        
	    }else{
	        
	        $this->Common_model->out_put_msg(0,'登录失败');
	        
	    }
	    
	
	   
	   
	    
	}
	
	
	protected function get_source($index = '', $filter = '', $in = TRUE) {
	    if ($index === '')
	        return $this->source;
	    if ($in)
	        $data = isset ( $this->source ['send_data'] [$index] ) ? $this->source ['send_data'] [$index] : NULL;
	    else
	        $data = isset ( $this->source [$index] ) ? $this->source [$index] : NULL;
	    if (isset ( $data ) && ! empty ( $filter )) {
	        switch ($filter) {
	            case 'int' :
	                $data = intval ( $data );
	                break;
	            default :
	                break;
	        }
	    }
	    return $data;
	}
	





}
