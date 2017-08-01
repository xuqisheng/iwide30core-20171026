<?php
class Qrcode_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	function create($array){
		$array['create_date']=date("Y-m-d H:i:s");
		return $this->db->insert('qrcode',$array);
	}
	
	function create_batch($array){
		return $this->db->insert_batch('qrcode',$array);
	}

    function create_batch_staff($array){
        return $this->db->insert_batch('hotel_staff',$array);
    }
	
	function update($array){
		$array['edit_date']=date("Y-m-d H:i:s");
		$this->db->where(array('id'=>$array['id'],'inter_id'=>$array['inter_id']));
		return $this->db->update('qrcode',$array);
	}
	/**
	 * 编辑/新增二维码
	 * @return boolean
	 */
	function save_edit($inter_id=''){
		$this->output->enable_profiler(false);
		$key               = $this->input->post('key',TRUE);
		$param['intro']    = $this->input->post('intro',TRUE);
		$param['keyword']  = $this->input->post('keyword',TRUE);
		$hotel             = explode('###',$this->input->post('hotel',TRUE));
		$param['name']     = $hotel[1];
		if(empty($key)){
			$inter_id=empty($inter_id)?$this->session->get_admin_inter_id():$inter_id;
			$this->db->trans_begin ();
			$param['id']       = $this->get_max_id($inter_id);
			$param['id'] = $param['id'] + 1;
			$param['inter_id'] = $inter_id;
			$param['url']      = $this->get_qr_code($param['id'],$inter_id);
			$this->db->insert('qrcode',$param);
			$staff_param['name']           = $param['intro'];
			$staff_param['hotel_name']     = $param['name'];
			$staff_param['hotel_id']       = $hotel[0];
			$staff_param['inter_id']       = $inter_id;
			$staff_param['status']         = 2;
			$staff_param['qrcode_id']      = $param['id'];
			$staff_param['is_distributed'] = 0;
			$staff_param['status_time']    = date('Y-m-d H:i:s');
			$this->db->insert('hotel_staff',$staff_param);
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			}else{
				$this->db->trans_commit();
				return TRUE;
			}
		}else{
			$this->db->where(array('inter_id'=>$inter_id,'id'=>$key));
			$this->db->update('qrcode',$param);
			if ($this->db->trans_status () === FALSE) {
				$this->db->trans_rollback ();
				return FALSE;
			} else {
				$this->db->trans_commit ();
				return TRUE;
			}
		}
	}
	
	function get_qrcodes_list($inter_id,$nums = NULL,$offset = NULL){
		$db_read = $this->load->database('iwide_r1',true);
		if(!is_null($nums) && !is_null($offset)){
			$db_read->limit($nums,$offset);
		}
		if(!is_null($nums) && is_null($offset)){
			$db_read->limit($nums);
		}
		return $db_read->get_where('qrcode',array('inter_id'=>$inter_id));
	}
	
	function get_qrcode_in_list($inter_id,$list){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where_in('id',$list);
		$db_read->where('inter_id',$inter_id);
		return $db_read->get('qrcode');
	}
	
	function get_detail($id, $inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('id'=>$id,'inter_id'=>$inter_id));
		$db_read->limit(1);
		return $db_read->get('qrcode');
	}
	function get_max_id($inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$sql = "SELECT MAX(id) id FROM ".$db_read->dbprefix('qrcode')." WHERE inter_id='".$inter_id."'";
		$query = $db_read->query($sql)->row_array();
		return isset($query['id']) ? $query['id'] : 1;
		/* $this->db->where(array('inter_id'=>$inter_id,'id <'=>9999));
		$this->db->select_max('id');
		return  $this->db->get('qrcode'); */ 
	}
	
	function scan_log($qrcode_id,$inter_id,$openid = NULL,$nickname = NULL,$event_time = NULL){
			// 扫描分销员二维码推事件到会员中心
		//if($openid == 'oNjXVjhRWyMEvyQhCWOP37B4_TMA'){
			$db_read = $this->load->database('iwide_r1',true);
			$this->load->model ( 'distribute/qrcodes_model' );
			$saler_info = $this->qrcodes_model->get_qrcodes_base ( array ( 'inter_id' => $inter_id, 'qrcode_id' => $qrcode_id ), array ( 'inter_id', 'qrcode_id', 'openid' ) );
			if (isset ( $saler_info[0]['openid'] ) && ! empty ( $saler_info[0]['openid'] )) {
				$this->load->helper ( 'common' );
				$result = doCurlPostRequest ( INTER_PATH_URL . 'package/dis_give', http_build_query ( array ( 'inter_id' => $inter_id, 'openid' => $openid ) ) );
				MYLOG::w('SCAN _LOG_POST | ' . INTER_PATH_URL . 'package/dis_give' . ' | ' . json_encode ( array ( 'inter_id' => $inter_id, 'openid' => $openid ) ) . ' | ' . $result,"wxapi_member_log" );
			}
		//}
		//记录扫码记录前先检查有没有关注记录，没有关注记录先添加关注行为再记录扫码行为 -- edit by NFOU 2015-12-21
		$sql = 'SELECT COUNT(*) nums FROM ' . $db_read->dbprefix ( "fans_sub_log" ) . ' WHERE `event`=2 AND inter_id=? AND openid=?';
		$count_query = $db_read->query($sql,array($inter_id,$openid))->row_array();
		if($count_query['nums'] < 1){
			$param = array (
					'event'      => 2,
					'event_time' => is_null ( $event_time ) ? date ( 'Y-m-d H:i:s' ) : $event_time,
					'source'     => $qrcode_id,
					'inter_id'   => $inter_id,
					'openid'     => $openid 
			);
			$this->db->insert ( 'fans_sub_log', $param );
		}
		//--
		$sql = "INSERT INTO " . $this->db->dbprefix ( "qrcode_log" ) . " (qrcode_id,scan_time,keyword";
		if (! is_null ( $openid ))
			$sql .= ",openid";
		if (! is_null ( $nickname ))
			$sql .= ",nickname";
		if (is_numeric ( $qrcode_id )) {
			$sql .= ",inter_id) SELECT id,";
			if (is_null ( $event_time ))
				$sql .= "NOW()";
			else
				$sql .= "'$event_time',keyword";
			if (! is_null ( $openid ))
				$sql .= ",'" . $openid . "'";
			if (! is_null ( $nickname ))
				$sql .= ",'" . $nickname . "'";
			$sql .= ",inter_id FROM " . $this->db->dbprefix ( "qrcode" ) . " WHERE id=$qrcode_id AND inter_id='" . $inter_id . "'";
		}else{
			$sql.=",inter_id) values (-1,'$event_time','','$inter_id')";
		}
		return $this->db->query ( $sql );
	}
	
	function event_log($source_id, $inter_id, $event, $openid, $event_time = NULL, $nickname = NULL, $description = NULL) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where ( array('openid' => $openid,'inter_id' => $inter_id) );
		$query = $db_read->get ( 'fans' );
		if ($query->num_rows () < 1) {
			$this->load->model('wx/Fans_key_model');
			$fans_key = $this->Fans_key_model->get_fans_key();
			$this->db->trans_begin ();
			$subcribe_time = is_null ( $event_time ) ? date ( 'Y-m-d H:i:s' ) : $event_time;
			$this->db->insert ( 'fans', array (
					'openid'         => $openid,
					'subscribe_time' => $subcribe_time,
					'inter_id'       => $inter_id,
					'fans_key'       => $fans_key,
					'nickname'       => $nickname 
			) );
			$this->db->insert ( 'fans_sub_log', array (
					'source'      => empty ( $source_id ) ? - 1 : $source_id,
					'openid'      => $openid,
					'event'       => $event,
					'inter_id'    => $inter_id,
					'event_time'  => $subcribe_time,
					'description' => $description 
			) );
// 			$insert_id = $this->db->insert_id();
			
			$this->db->trans_complete ();
			if ($this->db->trans_status () === FALSE) {
				$this->db->trans_rollback ();
				return FALSE;
			} else {
				$this->db->trans_commit ();
				return $fans_key;
			}
		} else {
			$this->db->insert ( 'fans_sub_log', array (
					'source'      => $source_id,
					'openid'      => $openid,
					'event'       => $event,
					'inter_id'    => $inter_id,
					'event_time'  => date ( 'Y-m-d H:i:s' ),
					'description' => $description 
			) );
			return $this->db->insert_id();
		}
	}
	
	public function zhuawawa($data,$inter_id){
		$s="select * from iwide_toynums where openid='".$data['FromUserName']."' and DATE(time)=DATE('".date('Y-m-d H:i:s')."')";
		$count = $this->db->query($s)->num_rows();
				//return array($count,'text');
				$tips="";
				if($count < 2){
					$wtoken = 'z1UD8fT3';
					$wtimestamp = time();
					$wnonce = 'hellooo';
					$wdata = '
					<xml>
					<ToUserName><![CDATA['.time().']]></ToUserName>
					<FromUserName><![CDATA[fromUser]]></FromUserName>
					<CreateTime>' . time() . '</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[925558]]></Content>
					<MsgId>1234567890123456</MsgId>
					</xml>
					';
					$wtmpArr = array($wtoken, $wtimestamp, $wnonce);
					sort($wtmpArr, SORT_STRING);
					$wnew_signature = sha1(implode($wtmpArr));
					$url = 'http://api.leguanzhu.com/' . $wtoken . '?signature=' . $wnew_signature . '&timestamp=' . $wtimestamp . '&nonce=' . $wnonce;
					$woptions = array(
							'http' => array(
							'method' => 'POST',
							'header' => 'Content-type:text/xml',
							'content' => $wdata,
							'timeout' => 60 * 15,
						),
					);
					$context = stream_context_create($woptions);
					$result = json_decode(json_encode(simplexml_load_string(file_get_contents($url, false, $context),'SimpleXMLElement', LIBXML_NOCDATA)));
					$pattern = '/[\d]{6}/';
					preg_match_all($pattern, $result->Content, $matches);
					$datas['openid'] = $data['FromUserName'];
					$datas['codes'] = implode(';',$matches[0]);
					$datas['time']   = date('Y-m-d H:i:s');
					$datas['inter_id']  = $inter_id;
					$this->db->insert('toynums',$datas);
					// if($count==0)$tips="逸林一周年送礼啦，只需您动动手！您的5个劳动码为：".implode('；',$matches[0])."。开始努力吧！";
					// else if($count==1)
					$tips="勤劳的人有福气，为您加油！5个劳动码奉上：".implode('；',$matches[0])."。";
					// $tips="勤劳的人有福气，为您加油！3个劳动码奉上：".$matches[0][0].';'.$matches[0][1].';'.$matches[0][2]."。";
					return $tips;
				}else{
					return '拿礼物也要注意休息，今天娃娃机也累了，请您明天再来抓吧。';
				}
	}
	
	function open_lock(){
		$client=new SoapClient('http://server.hismartlife.com:8090/ws/kssLockService.wsdl', array('encoding'=>'GBK'));
		$something =  $client->openLock(array('kssOpenRequestDto'=>array('lockAddress'=>'043394D6','requestSystemId'=>'8e5aff4ae4138e0718e341e9b46cd490')));
		echo $something->kssOpenResponseDto->errorMessage;
		return '开锁指令已发出';
	}

	function generate_qrcode($id,$qrcode_name = NULL) {
		if (isset ( $id )) {
			// $this->load->helper ( 'qrcode' );
			// $a = new QR ( site_url ( 's/t/' . $id ) );
			// $a = new QR ( 'http://'.$_SERVER['HTTP_HOST']."/index.php/s/t/$id") ;
			// return file_put_contents ( 'media/qrcodes/'.$id.'.jpg', $a->image ( 20 ) );
			$this->load->helper ( 'phpqrcode' );
			// QRcode::png('http://'.$_SERVER['HTTP_HOST'].'/index.php/s/t/'.$id,'media/qrcodes/'.$id.'.jpg','Q',15,1,true);
			QRcode::png('http://s.iwide.cn/index.php/s/t/'.$id,'media/qrcodes/'.$id.'.jpg','Q',15,1,true);
			return 'media/qrcodes/'.$id.'.jpg';
		} else
			return FALSE;
	}
	
	function grnerate_qrcode_ftp($text,$file_name = ''){
		$this->load->helper ( 'phpqrcode' );
		if(empty($file_name)){
			$this->load->helper('guid');
			$file_name = Guid::toString();
		}
		$base_path = 'temp/';
		if (! file_exists ( $base_path )) {
			mkdir ( $base_path, 0777, true );
		}
		QRcode::png($text,$base_path.$file_name.'.jpg','Q',15,1,true);
		$this->load->library('ftp');
        $configftp['hostname'] = $this->config->config['ftphostname'];
        $configftp['username'] = $this->config->config['ftpusername'];
        $configftp['password'] = $this->config->config['ftppassword'];
        $configftp['port']     = $this->config->config['ftpport'];
        $configftp['passive']  = $this->config->config['ftppassive'];
        $configftp['debug']    = $this->config->config['ftpdebug'];
            	
        $this->ftp->connect($configftp);
		
		$upload_path = '/public_html/public/qrcode/';
		$this->ftp->upload($base_path . $file_name.'.jpg', $upload_path. $file_name.'.jpg', 'binary', 0775);
		$this->ftp->close();
		//ftp结束
		@unlink($base_path . $file_name.'.jpg');
		return 'http://file.iwide.cn/' . $upload_path . $file_name.'.jpg';
	}
	
	function add_qrlogo($img,$logo){
		if($logo){
			$this->load->helper ( 'phpqrcode' );
			$QR = imagecreatefromstring(file_get_contents($img));  
			$logo = imagecreatefromstring(file_get_contents($logo));  
			$QR_width = imagesx($QR);  
			$QR_height = imagesy($QR);  
			$logo_width = imagesx($logo);  
			$logo_height = imagesy($logo);  
			$logo_qr_width = $QR_width / 5;  
			$scale = $logo_width / $logo_qr_width;  
			$logo_qr_height = $logo_height / $scale;  
			$from_width = ($QR_width - $logo_qr_width) / 2;  
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);  
		}  
		imagepng($QR,$img);  
	}
	public function get_qr_code($id,$inter_id='') {
		$this->load->model ( 'wx/access_token_model' );
		$inter_id=empty($inter_id)?$this->session->get_admin_inter_id():$inter_id;
		$access_token = $this->access_token_model->get_access_token ( $inter_id );
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
		// 临时码
		// $qrcode = '{"expire_seconds": 1800,"action_name": "QR_SCENE","action_info": {"scene": {"scene_id": '.$ticket_num.'}}}';
		// 永久码
		$qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
		$this->load->helper ( 'common' );
		$output = doCurlPostRequest ( $url, $qrcode );
		$jsoninfo = json_decode ( $output, true );
		
		if(isset($jsoninfo['errcode']) && $jsoninfo['errcode'] == '40001'){
			$access_token = $this->access_token_model->reflash_access_token ( $inter_id );
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
			$qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
			$output = doCurlPostRequest ( $url, $qrcode );
			$jsoninfo = json_decode ( $output, true );
		}
		
		if (isset ( $jsoninfo ['url'] ))
			return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $jsoninfo ['ticket'];
		else
			return $jsoninfo;
	}


    public function getHotelbyIdName($data){
		$db_read = $this->load->database('iwide_r1',true);
        $result=$db_read->query("SELECT
                                        `hotel_id`,`inter_id`,`name`
                                    FROM
                                        `iwide_hotels`
                                    WHERE
                                        hotel_id = {$data['hotel_id']}
                                    AND
                                         inter_id = '{$data['inter_id']}'
                                    AND
                                        name = '{$data['name']}'")->row_array();
        return $result;

    }


}