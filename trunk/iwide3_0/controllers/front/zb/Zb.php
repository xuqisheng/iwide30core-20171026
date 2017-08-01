<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class zb extends MY_Front_Livebc {

    
	function __construct() {
	    $_GET['scope'] = "snsapi_userinfo";
	    $_GET['id'] = "a469428180";
		parent::__construct ();
		
		
	}
	
	
	public function index(){
	    
	    include_once APPPATH."config/zb_config.php";

	    $data = array();
	    
	    $channel_id = intval($_GET['channel_id']);
	    
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Channel_model' );	    
	    
	    $channel_data = $this->Zb_model->getChannelByChannelId($channel_id);
	    
	    if($channel_data['status'] == -1 && $channel_data['annouce_img'] != ""){
	        header("location:/index.php/zb/zb/announce?channel_id={$channel_id}");
	        exit;
	    }

	   // $redis->incr('key1');
	    
	    //$channel_id = 1;
	    
	    //限制人数及防盗链
	    $this->load->library ( 'Cache/Redis_proxy', array (
	        'not_init' => FALSE,
	        'module' => 'common',
	        'refresh' => FALSE,
	        'environment' => ENVIRONMENT
	    ), 'redis_proxy' );
	    $online_num = $this->redis_proxy->incr("zb_channel_".$channel_id);
	    
	    $user_time = $this->redis_proxy->hGet('zb_user_active_time:' . $channel_id, $this->iwideid);
	    
	    if($online_num > ZB_LIMIT_NUMBER && $user_time == false){
	        
	        $this->redis_proxy->decr("zb_channel_".$channel_id);
	        
	        echo "<script>alert('房间已满！');</script>";
	        
	    }elseif($online_num < ZB_LIMIT_NUMBER || ($user_time + 120) > time()){
	        
	        //已进入房间不超120秒并且又回到房间的
	        if($user_time != false && ($user_time + 120) > time()){
	            $this->redis_proxy->decr("zb_channel_".$channel_id);	             
	        }
	        
	        $this->redis_proxy->hSet ( 'zb_user_active_time:' . $channel_id, $this->iwideid, time () );
	        
	        if(isset($_GET['auth_key']) && $_GET['auth_key'] != ""){
	            
	            
	            $this->load->model ( 'livebc/Common_model' );
	            
	             
	            $stream = $this->Channel_model->get_current_stream($channel_id);
	             
	            $this->Channel_model->addFansPlayRecord($this->iwideid,$stream['stream_id']);
	            
	            if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx')
	                $url =  'http://' . $_SERVER ['HTTP_HOST'] . "/index.php/zb/zb?channel_id=".$channel_id ;
	            else
	                $url =  'http://' . $_SERVER ['SERVER_NAME'] . "/index.php/zb/zb?channel_id=".$channel_id ;
	            
	            $channel_data['url'] = $url;
	            
	            $channel_data['head_img'] = $stream['head_img'];
	             
	            $data['channel'] = $channel_data;
	             
	            $this->load->model('wx/access_token_model');
	            $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
	             
	            $this->display ( 'zb/index', $data );
	            
	        }else{
	            
	            $url = $this->getFDUrl();
	            ob_clean();
	            header("Location:{$url}");
	            exit;
	            
	        }
	        
	    }else{
	       
	        //$this->redis_proxy->decr("zb_channel_".$channel_id);
	         
	        echo "<script>alert('房间已满！001');</script>";
	    }
	   
	   
	    
	    
	}
	
	public function announce(){
	     
	    include_once APPPATH."config/zb_config.php";
	
	    $data = array();
	    
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $this->load->model ( 'livebc/Channel_model' );
	     
	    $channel_id = intval($_GET['channel_id']);
	
	    $channel_data = $this->Zb_model->getChannelByChannelId($channel_id);
	
	    $data['channel'] = $channel_data;
	  
	    $this->display ( 'zb/announce', $data );
	
	
	     
	     
	}
	
	public function replay(){
	     
	     
	    $data = array();
	     
	    $sid_id = intval($_GET['sid']);
	     
	    //$channel_id = 1;
	     
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $this->load->model ( 'livebc/Channel_model' );
	    
	    $stream = $this->Channel_model->get_stream_id($sid_id);
	     
	    
	     
	    $data['channel'] = $stream;
	     
	   // $this->load->model('wx/access_token_model');
	   // $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
	     
	    $this->display ( 'zb/replay', $data );
	     
	     
	}
	
	public function mingxi(){
	     
	    $data = array();
	    
	    $this->load->model ( 'livebc/Zb_fans_model' );
	    $this->load->model ( 'livebc/Record_model' );
	    
	    $userinfo = $this->Zb_fans_model->getFansInfoByOpenid($this->openid,$this->inter_id);
	    $data['mibi'] = $userinfo['mibi'];
	    $record = $this->Record_model->get_fans_mibi_record($userinfo['iwideid']);
	    
	    foreach($record as $key => $r_data){
	        if($r_data['record_type'] == "buy"){
	            
	            $record[$key]['record_type_name'] = '购买商品赠给';
	            $record[$key]['mibi_change_num_name'] = "+".$record[$key]['mibi_change_num'];
	            
	        }else if($r_data['record_type'] == "give"){
	            
	            
	            $record[$key]['record_type_name'] = $record[$key]['remark'];;
	            $record[$key]['mibi_change_num_name'] = $record[$key]['mibi_change_num'];
	            
	        }else if($r_data['record_type'] == "system"){
	            $record[$key]['record_type_name'] = '系统赠给';
	            $record[$key]['mibi_change_num_name'] = "+".$record[$key]['mibi_change_num'];
	        }
	    }
	    
	    
	  
	    $data['record'] = $record;
	     
	    $this->display ( 'zb/mingxi', $data );
	     
	     
	}
	
	public function user_info(){
	     
	    $this->load->model ( 'livebc/Zb_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    $this->load->model ( 'livebc/Zb_fans_model' );
	    $this->load->model ( 'livebc/Record_model' );
	    
	    $channel_id = intval($_GET['cid']);
	    
	    $intro_goods_array = $this->Zb_model->getIntroGoodsByChannelId($channel_id);
	    $data_view = array();
	    $data_view['goods'] = array();
	    $zburl = $this->getOrderFinishUrl();
	    
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
	        

	        $temp_arr['buy_url'] = $this->getUrlByPidInterId($data['product_id'], $data['inter_id'])."&zbcode={$this->openid}&channelid=1&zburl={$zburl}";
	         
	    
	        $data_view['goods'][] = $temp_arr;
	         
	    
	    }
	    
	     
	
	    
	    
	    
	    $userinfo = $this->Zb_fans_model->getFansInfoByOpenid($this->openid,$this->inter_id);
	    $data_view['userinfo'] = $userinfo;
	    
	    $data_view['order_num'] = $this->Record_model->get_fans_buy_record_num($userinfo['iwideid']);
	    
	    
	    $stream_arr = $this->Zb_fans_model->getFansViewChannel($this->iwideid);
	    $temp_arr = array();
	    foreach($stream_arr as $key => $sdata){
	        if( $sdata['status'] == 1){
	            $stream_arr[$key]['url'] = "http://zb.jinfangka.cn/index.php/zb/zb/?channel_id={$sdata['channel_id']}";
	            $temp_arr[] = $stream_arr[$key];
	        }else{
	            
	            $stream_arr[$key]['url'] = "http://zb.jinfangka.cn/index.php/zb/zb/replay?sid={$sdata['stream_id']}";
	            
	            if($sdata['review_url'] != ""){
	                $temp_arr[] = $stream_arr[$key];
	            }
	        }
	      //  $stream_arr[$key]['url'] = "http://zb.jinfangka.cn/index.php/zb/zb/replay?sid={$sdata['stream_id']}";
	    }
	    $stream_arr = $temp_arr;
	    
	    
	   // $data_view['channel'] = $this->Zb_fans_model->getFansViewChannel($this->iwideid);
	    $data_view['channel'] = $stream_arr;
	    
	    $this->load->model('wx/Publics_model');
	    $data_view['info'] =$this->Publics_model->get_fans_info($this->openid,$this->inter_id);

	    $this->display ( 'zb/user-info', $data_view );
	     
	     
	}
	
	public function success_buy(){
	    
	   $this->load->model ( 'livebc/Record_model' );
	   $data = $this->Record_model->get_user_current_record_and_goods($this->iwideid);
	   
	   
	   $this->load->model('wx/publics_model');
	   $public_info= $this->publics_model->get_public_by_id( $data['inter_id'] );
	   
	   if(isset($public_info['name'])){
	     $data['name'] = $public_info['name'];
	   }
	   
	   $data['order_url'] = $this->getOrderListUrl($data['inter_id']);
	    
	   $this->display ( 'zb/dingdan', $data );
	    
	}
	
	public function showQrcode(){
	    header("content-type: image/png");
	    $this->load->model ( 'wx/access_token_model' );
	    $this->load->helper ( 'common' );
	    $inter_id = $_GET['id'];
	    //$share_member_id = !empty($this->input->get('mid'))?$this->input->get('mid'):$this->vip_user['member_info_id'];
	    $access_token = $this->access_token_model->get_access_token ( $inter_id );
	    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
	    // 临时码
	    $qrcode = '{"expire_seconds": 86400,"action_name": "QR_SCENE","action_info": {"scene": {"scene_id": 99999}}}';
	    $output = json_decode(doCurlPostRequest ( $url, $qrcode ),true);
	    if(isset($output['url'])){
	        $this->load->helper('phpqrcode');
	        
	        QRcode::png($output['url'],false,'L',6,0,true);
	    }
	}
	
	public function end(){
	    	    
	    $channel_id = intval($_GET['channel_id']);
	     
	    
	    $this->load->model ( 'livebc/Channel_model' );
	    $this->load->model ( 'livebc/Record_model' );
	    $this->load->model ( 'livebc/Common_model' );
	    
	    $stream = $this->Channel_model->get_current_stream($channel_id);
	    
	    $stream['mibi_record_arr'] = $this->Record_model->get_channel_mibi_record($channel_id,$stream['stream_id']);
	    
	    foreach($stream['mibi_record_arr'] as $record_data){
	        if($record_data['gift_id'] == 1){
	            $stream['xiami'] = $record_data['total'];
	        }
	        
	        if($record_data['gift_id'] == 2){
	            $stream['daxia'] =$record_data['total'];
	        }
	        
	    }
	    
	    $stream['xiami'] = isset($stream['xiami'])?$stream['xiami']:0;
	    $stream['daxia'] = isset($stream['daxia'])?$stream['daxia']:0;
	    
	    $stream['replay_url'] = "/index.php/zb/zb/replay?sid={$stream['stream_id']}";
	     
	   
	    $this->display ( 'zb/end', $stream );
	}
	
	
	private function getUrlByPidInterId($pid,$inter_id){
	     
	    $this->load->model('wx/publics_model');
	    $public_info= $this->publics_model->get_public_by_id( $inter_id );
	     
	    $domain = $public_info['domain'];
	     
	    $url = "http://{$domain}/index.php/soma/package/package_pay?pid={$pid}&id={$inter_id}";
	     
	    return $url;
	     
	     
	}
	
	private function getOrderFinishUrl(){
	     
	    $this->load->model('wx/publics_model');
	     
	    $public_info= $this->publics_model->get_public_by_id( $this->inter_id );
	
	    $domain = $public_info['domain'];
	     
	    $url = "http://{$domain}/index.php/zb/zb/success_buy";
	    return $url;
	     
	}
	
	private function getOrderListUrl($inter_id){
	
	    $this->load->model('wx/publics_model');
	
	    $public_info= $this->publics_model->get_public_by_id( $inter_id );
	
	    $domain = $public_info['domain'];
	
	    $url = "http://{$domain}/index.php/soma/order/my_order_list?id={$inter_id}";
	    
	    return $url;
	
	}
	
	//取防盗链url
	private function getFDUrl(){
	    	
	    $timestamp=time()+86400;	//失效时间，整形正数，固定长度10，1970年1月1日以来的秒数。用来控制失效时间，10位整数，有效时间1800s
        $rand = 0; //随机数，一般设成0
        $uid = 0;	//暂未使用（设置成0即可)
        $md5hash = ""; //通过md5算法计算出的验证串，数字和小写英文字母混合0-9a-z，固定长度32
        
        $secrect = ZB_APP_SECRECT;
	    
	    if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx')
	        $url =  'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] ;
	    else
	        $url =  'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ;
	    
	    //sstring = "URI-Timestamp-rand-uid-PrivateKey"
	    $no_params_url = '/index.php/zb/zb';
	    
	    $sstring = "{$no_params_url}-{$timestamp}-{$rand}-{$uid}-{$secrect}";
	    
	    $md5hash = md5($sstring);
	    
	    return $url."&auth_key={$timestamp}-{$rand}-{$uid}-{$md5hash}";
	    
	}
	
	
	
	
	
	





}
