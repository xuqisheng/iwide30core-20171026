<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_api extends CI_Controller {
    
    var $inter_id;
    var $sysconfig;
    
    function __construct(){
        parent::__construct ();
        include_once APPPATH."config/zb_config.php";
        $this->inter_id = ZB_INTER_ID;
        $this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
        
        // 指定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:POST');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
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

	public function index()
	{
	    if( defined('WEB_AREA') &&  WEB_AREA=='admin')
		    redirect('privilege/auth/index');
	    else 
	        echo 'Welcome to iwide.cn. 2';
	}
	
	
	
	
	
	public function callback_create(){
	
		MYLOG::W("create | ".json_encode($_REQUEST),"xm_play");
		$file_key = 'create';
		$dir = "xm_play";
		$path= APPPATH.'logs'.DS. $dir. DS;
		$file= date('Y-m-d').$file_key. '.log';
		$content = json_encode($_REQUEST);
		$fp = fopen( $path. $file, "w");
		fwrite($fp, $content);
		fclose($fp);
		
		$channel_id = $_REQUEST['channel_id'];
		
		$sql = "
				UPDATE
		              iwide_zb_channel
		        SET
		              play_url = '{$_REQUEST['hls_url'][0]}',
		              pic_url = = '{$_REQUEST['pic_url'][0]}',
		              Live_time = '{$_REQUEST['create_time']}',
		              status = 1
		        WHERE
		              channel_id = {$channel_id}
				";
		
		$this->db->query ( $sql );
		
		if($this->db->affected_rows() ){
		    echo 1;
		}else{
		    echo 0;
		}

	
	}
	
	public function callback_close(){
	
		MYLOG::W("close | ".json_encode($_REQUEST),"xm_play");
		$file_key = 'close';
		$dir = "xm_play";
		$path= APPPATH.'logs'.DS. $dir. DS;
		$file= date('Y-m-d').$file_key. '.log';
		$content = json_encode($_REQUEST);
		$fp = fopen( $path. $file, "w");
		fwrite($fp, $content);
		fclose($fp);
		
		
		$channel_id = $_REQUEST['channel_id'];
		
		$sql = "
		UPDATE
		  iwide_zb_channel
		SET		 
		  status = 0
		WHERE
		  channel_id = {$channel_id}
		";
		
		$this->db->query ( $sql );
		
		if($this->db->affected_rows() ){
		    echo 1;
		}else{
		    echo 0;
		}
		
		//echo 1;
	}
	
	public function callback_review(){
	
		MYLOG::W("review | ".json_encode($_REQUEST),"xm_play");
		$file_key = 'review';
		$dir = "xm_play";
		$path= APPPATH.'logs'.DS. $dir. DS;
		$file= date('Y-m-d').$file_key. '.log';
		$content = json_encode($_REQUEST);
		$fp = fopen( $path. $file, "w");
		fwrite($fp, $content);
		fclose($fp);
		echo 1;
	
	}
	
	public function play(){
		
		$file_key = 'create';
		$dir = "xm_play";
		$path= APPPATH.'logs'.DS. $dir. DS;
		$file= date('Y-m-d').$file_key. '.log';
		$fp = fopen( $path. $file, "r");
		$line = fgets($fp);
		fclose($fp);
		
		$data = json_decode($line,true);
		
		
		$this->load->view ( 'play.php', $data);
		
		//$this->display ( 'play', $data );
		
	}
	public function perfectChannel(){
        $head_img = $this->get_source ( 'head_img' );
        $channel_title = $this->get_source ( 'channel_title' );
        $nickname = $this->get_source ( 'nickname' );
        $login_token = $this->get_source ( 'token', '', FALSE );
        $this->load->model ( 'livebc/Common_model' );
        $this->load->model ( 'livebc/Channel_model' );
        $channel = $this->Channel_model->get_channel ( $login_token , 'login_token');
        if (! $channel) {
            $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/perfectChannel', 1 );
        }
        $result = $this->Channel_model->update_channel_info ( $channel ['channel_id'], array (
                'nickname' => $nickname,
                'head_img' => $head_img,
                'title' => $channel_title
        ) );
        
        $stream_id = $this->Channel_model->build_new_stream($channel ['channel_id'],$nickname,$head_img,$channel_title);
        
        if($stream_id > 0){
            $return_data = array("stream_id"=>$stream_id);
            $this->Common_model->out_put_msg ( 1, '', $return_data, 'public/perfectChannel' );
        }else{
            $this->Common_model->out_put_msg ( 0, '开播超时', $result, 'public/perfectChannel' );
        }
        
       // $result ? $this->Common_model->out_put_msg ( 1, '', $result, 'public/perfectChannel' ) : $this->Common_model->out_put_msg ( 2, '修改失败', $result, 'public/perfectChannel', 1 );
    
	
	}
	public function getMsg(){
	    $login_token = $this->get_source ( 'token', '', FALSE );
	    $last_msg_id = $this->get_source ( 'last_msg_id', 'int' );
	    $nums = $this->get_source ( 'nums', 'int' );
	    empty ( $nums ) and $nums = 10;
	    $this->load->model ( 'livebc/Chat_msg_model' );
	    $this->load->model ( 'livebc/Channel_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $channel = $this->Channel_model->get_channel ($login_token , 'login_token');
	    if (! $channel) {
	        $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/getMsg', 1 );
	    }
	    $msgs = $this->Chat_msg_model->get_msg_list (  $channel ['channel_id'], 1, $last_msg_id, '', $nums );
	    $this->Common_model->out_put_msg ( 1, '', $msgs, 'public/getMsg' );
	}
	
	public function getChannel(){
	
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $this->load->model ( 'livebc/Channel_model' );
	    $login_token = $this->get_source ( 'token', '', FALSE );
	   
	    $channel = $this->Channel_model->get_channel ( $login_token , 'login_token');
	    if (! $channel) {
	        $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/perfectChannel', 1 );
	    }
	    $channel_id = $channel ['channel_id'];
	
	
	   
	   $channel_data = $this->Zb_model->getChannelByChannelId($channel_id);
	   
	   unset($channel_data['login_token']);
	   unset($channel_data['login_code']);
	   
	   $stream = $this->Channel_model->get_current_stream($channel_data['channel_id']);
	   
	   $channel_data['live_time'] = strtotime($stream['create_time']);
	   
	   $channel_data['user_info'] = array(
	       'mibi'=>$channel_data['mibi'],
	       'daxia'=>$channel_data['daxia'],
	       
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
	
	
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $this->load->model ( 'livebc/Channel_model' );
	    $login_token = $this->get_source ( 'token', '', FALSE );
	   
	    $channel = $this->Channel_model->get_channel ( $login_token , 'login_token');
	    if (! $channel) {
	        $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/perfectChannel', 1 );
	    }
	    $channel_id = $channel ['channel_id'];

	
	     $channel_data = $this->Zb_model->getChannelByChannelId($channel_id);
	   
	   unset($channel_data['login_token']);
	   unset($channel_data['login_code']);
	   
	   $stream = $this->Channel_model->get_current_stream($channel_data['channel_id']);
	   
	   $channel_data['live_time'] = strtotime($stream['create_time']);
	   
	   $channel_data['user_info'] = array(
	       'mibi'=>$channel_data['mibi'],
	       'daxia'=>$channel_data['daxia'],
	       
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

	    /* $return_data = array();
	
	    $return_data['play_url'] =  */
	
	    $this->load->model ( 'livebc/Record_model' );
	    $this->Record_model->refresh_user_active_time($channel_id,$this->iwideid);
	
	    $this->Common_model->out_put_msg(1,'',$channel_data);
	
	
	}
	
	public function getCurrentStream(){
	    
	    $head_img = $this->get_source ( 'head_img' );
	    $channel_title = $this->get_source ( 'channel_title' );
	    $nickname = $this->get_source ( 'nickname' );
	    $channel = $this->checkTokenAndGetChannel();
	    
	    $this->load->model ( 'livebc/Channel_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    
	    $stream = $this->Channel_model->get_current_stream($channel['channel_id']);
	   
	    $this->Common_model->out_put_msg(1,'',$stream);
	
	
	}
	
	public function getCurrentStreamResult(){
	     
	    $head_img = $this->get_source ( 'head_img' );
	    $channel_title = $this->get_source ( 'channel_title' );
	    $nickname = $this->get_source ( 'nickname' );
	    $channel = $this->checkTokenAndGetChannel();
	     
	    $this->load->model ( 'livebc/Channel_model' );
	    $this->load->model ( 'livebc/Record_model' );
	    $this->load->model ( 'livebc/Common_model' );
	     
	    $stream = $this->Channel_model->get_current_stream($channel['channel_id']);
	    
	    $stream['mibi_record_arr'] = $this->Record_model->get_channel_mibi_record($channel['channel_id'],$stream['stream_id']);
	
	    if(intval($channel['channel_id']) == 888 ){
	        $stream['max_play_num'] = $stream['play_num'];
	    }else{
	        $stream['max_play_num'] = 250;
	    }
	    
	    $this->Common_model->out_put_msg(1,'',$stream);
	
	
	}
	
	public function stop(){
	
	   
	    $channel = $this->checkTokenAndGetChannel();
	
	    $this->load->model ( 'livebc/Channel_model' );
	    $this->load->model ( 'livebc/Record_model' );
	    $this->load->model ( 'livebc/Common_model' );
	
	    $stream = $this->Channel_model->get_current_stream($channel['channel_id']);
	    
	    $stream_id = $stream['stream_id'];
	     
	    $res = $this->Channel_model->closeStream($stream_id);
	
	    if($res){
	        $this->Common_model->out_put_msg(1,'',array());
	    }else{
	        $this->Common_model->out_put_msg(1,'关闭超时',array());
	    }
	   
	
	
	}
	
	
	public function introGoods(){
	
	
	    $channel = $this->checkTokenAndGetChannel();
	
	    $channel_id = $channel['channel_id'];
	    $goods_id = $this->get_source ( 'goods_id' );
        $content = $goods_id;
        $this->load->model ( 'livebc/Chat_msg_model' );
        $this->load->model ( 'livebc/Common_model' );
        $result = $this->Chat_msg_model->add_chat_msg ( $channel_id, $content,'gift', array (
                'openid' => $this->openid,
                'iwideid' => $this->iwideid 
        ) );
        $result ? $this->Common_model->out_put_msg ( 1, '', $result, 'Public_api/introGoods' ) : $this->Common_model->out_put_msg ( 2, '提交失败', $result, 'private/sendMsg', 1 );
	
	}
	
	/* protected function _prep_filename($filename)
	{
	    if ($this->mod_mime_fix === FALSE OR $this->allowed_types === '*' OR ($ext_pos = strrpos($filename, '.')) === FALSE)
	    {
	        return $filename;
	    }
	
	    $ext = substr($filename, $ext_pos);
	    $filename = substr($filename, 0, $ext_pos);
	    return str_replace('.', '_', $filename).$ext;
	} */
	
	public function do_upload() {
	
	    //$this->checkToken();
	     
	    $this->sysconfig = get_config();
	     
	    $this->load->model ( 'livebc/Common_model' );
	     
	    if (isset($_FILES['file'])) {
	        if ($_FILES['file']['type']=="application/octet-stream") {
	            $file_ext = $this->get_ext($_FILES['file']['name']);
	            $type = $this->get_mines($file_ext);
	            $_FILES['file']['type'] = $type;
	        }
	    }
	
	    $file_system_path = '/public/uploads/' .date("Ym"). '/';
	
	    $config['upload_path']      = './public/base/tmp/';
	    $config['allowed_types']    = 'gif|jpg|jpeg|png|bmp|swf|flv|swf|flv|mp3|wav|wma|wmv|mid|avi|mpg|asf|rm|rmvb|doc|docx|xls|xlsx|ppt|htm|html|txt|zip|rar|gz|bz2';
	    $config['max_size']     = 20480;
	    $config['max_width']        = 10240;
	    $config['max_height']       = 7680;
	
	    $config['file_name'] = 'qf'.date("dHis").rand(1000, 9999);
	
	    $this->load->library('upload', $config);
	     
	    $fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG'); // File extensions
	    
	    $fileoj = $_FILES['file'];
	    $fileParts = pathinfo($fileoj['name']);
	    
	    
	    
	    $file_name = $config['file_name'].".".$fileParts['extension'];
	    $filepath = $config['upload_path'].$file_name;
	    $tumbimagepath =  $config['upload_path'].$config['file_name']."_thumb.".$fileParts['extension'];
	     
	    if (in_array($fileParts['extension'],$fileTypes)) {
	        if ( ! @move_uploaded_file($fileoj['tmp_name'], $filepath) )
			{
			    $this->Common_model->out_put_msg (0,"上传文件超时");
			    exit;
			}
	    } else {
	         $this->Common_model->out_put_msg (0,"上传类型有误");
	         exit;
	    } 
	    
	    
	
	   // if ( !$this->upload->do_upload('file') )
	    if ( 0 )
	    {
	        //echo json_encode(array('error' => 1, 'message' => strip_tags($this->upload->display_errors())));
	        $this->Common_model->out_put_msg (0,"上传文件超时");
	    }
	    else
	    {
	        $config['image_library'] = 'gd2';
	        $config['source_image'] = $filepath;
	        $config['create_thumb'] = TRUE;
	        $config['maintain_ratio'] = false;
	        $config['width']     = 309;
	        $config['height']   = 185;
	        // $config['x_axis']     = 309;
	        // $config['y_axis']   = 185;
	        
	        $this->load->library('image_lib', $config);
	        
	       // $this->image_lib->resize();
	        if ( ! $this->image_lib->resize())
	        {
	            echo $this->image_lib->display_errors();
	        }
	        
	        $filepath = $tumbimagepath;
	        
	        
	        $data = array('upload_data' => $this->upload->data());
	        ini_set('memory_limit', '1280M');
	
	        $recof['image_library'] = 'gd2';
	        $recof['source_image'] = $data['upload_data']['full_path'];
	        //$recof['create_thumb'] = TRUE;
	        $recof['maintain_ratio'] = TRUE;
	
	        $image_width1 = $data['upload_data']['image_width'];
	        $image_height1 = $data['upload_data']['image_height'];
	        if ($image_width1>=$image_height1) {
	            if ($image_width1>1024) {
	                $recof['width']  = 1024;
	            }
	        }
	        /*
	         else {
	         if ($image_height1>768) {
	         $recof['height']  = 768;
	         }
	         }*/
	        $this->load->library('image_lib', $recof);
	        $this->image_lib->resize();
	
	        //ftp开始
	        $this->load->library('ftp');
	        $configftp['hostname'] = $this->sysconfig['ftphostname'];
	        $configftp['username'] = $this->sysconfig['ftpusername'];
	        $configftp['password'] = $this->sysconfig['ftppassword'];
	        $configftp['port']     = $this->sysconfig['ftpport'];
	        $configftp['passive']  = $this->sysconfig['ftppassive'];
	        $configftp['debug']    = $this->sysconfig['ftpdebug'];
	
	        $this->ftp->connect($configftp);
	
	        $toftppath = '/public_html'.$file_system_path;
	        $isdir = $this->ftp->list_files($toftppath);
	
	        if (empty($isdir)) {
	            $newpath = '/';$arrpath = explode('/', $toftppath);
	            foreach ($arrpath as $v) {
	                if ($v!='') {
	                    $newpath = $newpath.$v.'/';
	                    $isdirchild = $this->ftp->list_files($newpath);
	                    if (empty($isdirchild)) {
	                        $this->ftp->mkdir($newpath);
	                    }
	                }
	            }
	        }
	
	        $this->ftp->upload($filepath, $toftppath.$file_name, 'binary', 0775);
	        $this->ftp->close();
	        //ftp结束
	
	        $username = "app_upload-channelid-";
	        $inter_id = "zb";
	
	        $in['username'] = $username;
	        $in['inter_id'] = $inter_id;
	        $in['dir'] = date("Ym");
	        $in['addtime'] = time();
	        
	        
	
	        $in['filesize'] = $fileoj['size'];
	        $in['filetype'] = $fileParts['extension'];
	        $in['filename'] = $file_name;
	
	        $file_domain=empty($this->sysconfig['ftp_cdn_url'])?$this->sysconfig['ftpurl']:$this->sysconfig['ftp_cdn_url'];
	        $in['src'] = $file_domain.$file_system_path.$file_name;
	
	        $this->db->insert('upload',$in);
	
	        $file_url = $in['src'];
	        //echo json_encode(array('error' => 0, 'url' => $file_url));
	        $this->Common_model->out_put_msg (1,"",$file_url);
	        //@unlink($config['upload_path'].$config['file_name'].$data['upload_data']['file_ext']);
	        exit;
	
	    }
	
	
	
	}
	
	
	public function do_upload2() {
	
	    //$this->checkToken();
	    
	    $this->sysconfig = get_config();
	    
	    $this->load->model ( 'livebc/Common_model' );
	    
	    if (isset($_FILES['file'])) {
	        if ($_FILES['file']['type']=="application/octet-stream") {
	            $file_ext = $this->get_ext($_FILES['file']['name']);
	            $type = $this->get_mines($file_ext);
	            $_FILES['file']['type'] = $type;
	        }
	    }
	
	    $file_system_path = '/public/uploads/' .date("Ym"). '/';
	
	    $config['upload_path']      = './public/base/tmp/';
	    $config['allowed_types']    = 'gif|jpg|jpeg|png|bmp|swf|flv|swf|flv|mp3|wav|wma|wmv|mid|avi|mpg|asf|rm|rmvb|doc|docx|xls|xlsx|ppt|htm|html|txt|zip|rar|gz|bz2';
	    $config['max_size']     = 20480;
	    $config['max_width']        = 10240;
	    $config['max_height']       = 7680;
	
	    $config['file_name'] = 'qf'.date("dHis").rand(1000, 9999);
	
	    $this->load->library('upload', $config);
	    
	    $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	    $fileParts = pathinfo($_FILES['Filedata']['name']);
	    
	    if (in_array($fileParts['extension'],$fileTypes)) {
	        move_uploaded_file($tempFile,$targetFile);
	        echo '1';
	    } else {
	        echo 'Invalid file type.';
	    }
	
	    if ( !$this->upload->do_upload('file') )
	    {
	        //echo json_encode(array('error' => 1, 'message' => strip_tags($this->upload->display_errors())));
	        $this->Common_model->out_put_msg (0,"上传文件超时");
	    }
	    else
	    {
	        $data = array('upload_data' => $this->upload->data());
	        ini_set('memory_limit', '1280M');
	
	        $recof['image_library'] = 'gd2';
	        $recof['source_image'] = $data['upload_data']['full_path'];
	        //$recof['create_thumb'] = TRUE;
	        $recof['maintain_ratio'] = TRUE;
	
	        $image_width1 = $data['upload_data']['image_width'];
	        $image_height1 = $data['upload_data']['image_height'];
	        if ($image_width1>=$image_height1) {
	            if ($image_width1>1024) {
	                $recof['width']  = 1024;
	            }
	        }
	        /*
	         else {
	         if ($image_height1>768) {
	         $recof['height']  = 768;
	         }
	         }*/
	        $this->load->library('image_lib', $recof);
	        $this->image_lib->resize();
	
	        //ftp开始
	        $this->load->library('ftp');
	        $configftp['hostname'] = $this->sysconfig['ftphostname'];
	        $configftp['username'] = $this->sysconfig['ftpusername'];
	        $configftp['password'] = $this->sysconfig['ftppassword'];
	        $configftp['port']     = $this->sysconfig['ftpport'];
	        $configftp['passive']  = $this->sysconfig['ftppassive'];
	        $configftp['debug']    = $this->sysconfig['ftpdebug'];
	         
	        $this->ftp->connect($configftp);
	         
	        $toftppath = '/public_html'.$file_system_path;
	        $isdir = $this->ftp->list_files($toftppath);
	         
	        if (empty($isdir)) {
	            $newpath = '/';$arrpath = explode('/', $toftppath);
	            foreach ($arrpath as $v) {
	                if ($v!='') {
	                    $newpath = $newpath.$v.'/';
	                    $isdirchild = $this->ftp->list_files($newpath);
	                    if (empty($isdirchild)) {
	                        $this->ftp->mkdir($newpath);
	                    }
	                }
	            }
	        }
	
	        $this->ftp->upload($data['upload_data']['full_path'], $toftppath.$data['upload_data']['file_name'], 'binary', 0775);
	        $this->ftp->close();
	        //ftp结束
	
	        $username = "app_upload-channelid-";
	        $inter_id = "zb";
	
	        $in['username'] = $username;
	        $in['inter_id'] = $inter_id;
	        $in['dir'] = date("Ym");
	        $in['addtime'] = time();
	
	        $in['filesize'] = $data['upload_data']['file_size'];
	        $in['filetype'] = $data['upload_data']['image_type'];
	        $in['filename'] = $data['upload_data']['client_name'];
	
	        $file_domain=empty($this->sysconfig['ftp_cdn_url'])?$this->sysconfig['ftpurl']:$this->sysconfig['ftp_cdn_url'];
	        $in['src'] = $file_domain.$file_system_path.$data['upload_data']['file_name'];
	
	        $this->db->insert('upload',$in);
	
	        $file_url = $in['src'];
	        //echo json_encode(array('error' => 0, 'url' => $file_url));
	        $this->Common_model->out_put_msg (1,"",$file_url);
	        @unlink($config['upload_path'].$config['file_name'].$data['upload_data']['file_ext']);
	        exit;
	
	    }
	
	
	
	}
	
	private function checkToken(){
	    
	    $login_token = $this->get_source ( 'token', '', FALSE );
	    $this->load->model ( 'livebc/Channel_model' );
	    $channel = $this->Channel_model->get_channel ( $login_token , 'login_token');
	    if (! $channel) {
	        $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/do_upload', 1 );
	        exit;
	    }
	    
	}
	
	private function checkTokenAndGetChannel(){
	     
	    $login_token = $this->get_source ( 'token', '', FALSE );
	    $this->load->model ( 'livebc/Channel_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $channel = $this->Channel_model->get_channel ( $login_token , 'login_token');
	    if (! $channel) {
	        $this->Common_model->out_put_msg ( 2, '频道信息不存在', '', 'public/do_upload', 1 );
	        exit;
	    }else{
	        
	        return $channel;
	        
	    }
	     
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
