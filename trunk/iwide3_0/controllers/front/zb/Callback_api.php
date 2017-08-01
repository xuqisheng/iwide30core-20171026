<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback_api extends CI_Controller {

	public function index()
	{
	    if( defined('WEB_AREA') &&  WEB_AREA=='admin')
		    redirect('privilege/auth/index');
	    else 
	        echo 'Welcome to iwide.cn. 2';
	}
	
	
	
	
	public function callback_create(){
	    //dingfang.liyewl.com/index.php/welcome/callback_create?channel_id=10001&stream_alias=10006&hls_url[0]=hls_url&pic_url[0]=pic_url
		MYLOG::W("create | ".json_encode($_REQUEST),"xm_play");
	
		
		$channel_id = $_REQUEST['channel_id'];
		$stream_id = $_REQUEST['stream_alias'];
		$callback_id = intval($_REQUEST['id']);
		
		$sql = "
				UPDATE
		              iwide_zb_channel
		        SET
		              play_url = '{$_REQUEST['hls_url'][0]}',
		              pic_url = '{$_REQUEST['pic_url'][0]}',
		              status = 1,
		              callback_id = {$callback_id}
		        WHERE
		              channel_id = {$channel_id}
				";
		
		$this->db->query ( $sql );
		
		if($this->db->affected_rows() ){
		    
		    $sql = "
		    UPDATE
		    iwide_zb_stream
		    SET
		      play_url = '{$_REQUEST['hls_url'][0]}',
		      screen_pic = '{$_REQUEST['pic_url'][0]}',
		      callback_id = {$callback_id},
		      status = 1
		    WHERE
		      channel_id = {$channel_id}
		      AND stream_id = {$stream_id}
		      AND status != 2
		    ";
		    
		    $this->db->query ( $sql );

		    echo 1;		   
		   
		}else{
		    echo 0;
		}

	
	}
	
	public function close(){
	
		MYLOG::W("close | ".json_encode($_REQUEST),"xm_play");
			
		$channel_id = $_REQUEST['channel_id'];
		
		$this->load->model ( 'livebc/Channel_model' );
		
		$stream = $this->Channel_model->get_stream_id($_REQUEST['stream_alias']);
		 
		$stream_id = $stream['stream_id'];
		
		$callback_id = intval($_REQUEST['id']);
		
		$res = $this->Channel_model->setStreamStatus($stream_id,0,2,$callback_id);
		
		
		    $current_stream = $this->Channel_model->get_current_stream($channel_id);
		    
		    $current_stream_id = $current_stream['stream_id'];
		    
		    if($current_stream_id == $_REQUEST['stream_alias']){
		        $sql = "
		        UPDATE
		           iwide_zb_channel
		        SET
		          status = 0,
		          callback_id = {$callback_id}
		        WHERE
		          channel_id = {$channel_id}
		          AND callback_id <= {$callback_id}
		        ";
		        
		        $this->db->query ( $sql );
		        
		        echo 1;
		        /* if($this->db->affected_rows()){
		            
		            echo 1;
		        }else{
		            echo 0;
		        } */
		        
		    }else{
		        echo 1;
		    }
		   
	

		//echo 1;
	}
	
	public function review(){
	
		MYLOG::W("review | ".json_encode($_REQUEST),"xm_play");

		
		$channel_id = $_REQUEST['channel_id'];
		$stream_id = $_REQUEST['stream_alias'];
		
		
		$sql = "
		UPDATE
		  iwide_zb_stream
		SET
		  review_url = '{$_REQUEST['replay_url']}'
		WHERE
		  channel_id = {$channel_id}
		  AND stream_id = {$stream_id}
		";
		
		$this->db->query ( $sql );
		
		
		echo 1;
	
	}
	
	
}
