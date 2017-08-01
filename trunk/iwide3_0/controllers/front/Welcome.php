<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
	    if( defined('WEB_AREA') &&  WEB_AREA=='admin')
		    redirect('privilege/auth/index');
	    else 
	        echo 'Welcome to iwide.cn. 2';
	}
	function test(){
		if($this->input->get('code')){
			$code = $this->input->get ( 'code' );
			$redirect_uri = urldecode($this->input->get ( 'redirect' ));
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx992e5c06624b1a6e&secret=901647c10c10a2b8a0487324d8794706&code=$code&grant_type=authorization_code";
				
			$this->load->helper('common');
			$result = doCurlGetRequest($url);
			var_dump($result);
			die("I'm here...");
		}else{
			redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx992e5c06624b1a6e&redirect_uri=".urlencode('http://mycard.kargocard.com/aaa?code=XXXXXXX')."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect");
		}
	}
	
	/**
	 * 
	 * 
	 * 测试数据：
	 */
	public function testm(){
		
		
		if($_GET['t'] == 1){
			echo "aaaa";
		}else{
			//$this->aaa();
			exit;
		}
		
	}
	
	function aaa(){
		
		//$this->bbb();
		ECHO "CCCC";
		$this->BBB();
		
		
	}
	
	function ttt(){
		
		$this->count_money5();
		//测试积分扣减
// 		$this->load->model ( 'hotel/Member_model' );
// 		$this->Member_model->consum_point ( 'a449675133', 'ORDERID', 'OPENID', 'POINTUSEDAMOUNT');
	}
	

	function showUser(){
		
		$key = "STEEHesdfxcherAWSDASDyhdfv";
		
		if($_GET['key'] != $key){
			
			return ;
			
		}
		
		
		
		$inter_id = $_GET['id'];
		
		$openid = $_GET['openid'];
		
		if( !$inter_id || !$openid){
				
				echo "输入有误";
				exit;
		}
		
		$sql = "
				SELECT * FROM `iwide_fans` as F ,iwide_member as M
				WHERE 
				    F.openid = M.openid
				    AND F.openid = '{$openid}'
				";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		print_r($data);
		
		$sql = "
		SELECT 
				C.*,CI.* 
		FROM 
			`iwide_member_get_card_list` as C,
			iwide_member_card_infomation as CI 
		WHERE 
			C.ci_id = CI.ci_id AND C.openid = '{$openid}'
			AND CI.inter_id = '{$inter_id}'
		";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		echo "\r\n\r\n卡券：\r\n";
		print_r($data);
		
		
		/* $sql = "
		SELECT
		C.*,CI.*
		FROM
		`iwide_user_get_card` as C,
		iwide_member_card_infomation as CI
		WHERE
		C.ci_id = CI.ci_id AND C.openid = '{$openid}'
		AND CI.inter_id = '{$inter_id}'
		";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		print_r($data); */
		
	}
	
	
	
	function check_pk(){
		
		$time = $_GET['time'];
		
		$sql = "
			SELECT * 
			FROM 
				`iwide_weixin_text` 
			WHERE `content` LIKE ':redpack->receive->%发放成功%' 
				AND `edit_date` > '{$time}'			
				
				";
		
		echo $sql;
		
		$data = $this->db->query ( $sql )->result_array ();
		
		print_r($data);
		exit;
		
		$real_send_info = array();
		foreach($data as $d){
		
			$str = str_replace(":redpack->receive->", "", $d['content']);
		
		
			$oj = (array)simplexml_load_string($str,'SimpleXMLElement', LIBXML_NOCDATA);
		
			$real_send_info[] = $oj;
		
		
		}
		
		
		$uninx_time = strtotime($time);
		$date = date("Y-m-d",$uninx_time);
		
		$sql = "
			SELECT 
					o.openid,oi.* 
			FROM 
				iwide_hotel_order_items as oi ,
				iwide_hotel_orders as o 
			WHERE 
				oi.orderid = o.orderid 
				AND oi.enddate > '{$date}'
				AND istatus = 3
		
		";
		
		$order_list = array();
		
		unset($data);
		$data = $this->db->query ( $sql )->result_array ();
		
		foreach($data as $d){
				
			$order_list[] = $d;
				
		}
		
		
		//先核对发送队列和真实发送数
		$real_send_count_byopenid_array = array();
		foreach($real_send_info as $temp_real_send){
			
			if( isset( $real_send_count_byopenid_array[ $temp_real_send['re_openid'] ] ) ){
				
				$real_send_count_byopenid_array[ $temp_real_send['re_openid'] ]++;
				
			}else{
				
				$real_send_count_byopenid_array[ $temp_real_send['re_openid'] ] = 1;
				
			}
			
			
		}
		
		
		$order_count_list = array();
		foreach($order_list as $temp_order_info){
				
			if( isset( $order_count_list[ $temp_order_info['openid'] ] ) ){
		
				$order_count_list[ $temp_order_info['openid'] ]++;
		
			}else{
		
				$order_count_list[ $temp_order_info['openid'] ] = 1;
		
			}
				
				
		}
		
		
		
		$url = "http://act.a6tuan.com/web/Red_packets/getQueue?time={$time}";
		
		$queue_list = (array)json_decode( file_get_contents($url) );
		
		echo "openid,真实发送量,红包队列数,订单间夜数\r\n";
		
		foreach($real_send_count_byopenid_array as $openid => $num){
			
			$queue_num = isset($queue_list[$openid])?$queue_list[$openid]:0;
			$order_num = isset($order_count_list[$openid])?$order_count_list[$openid]:0;
			
			echo "{$openid},{$num},{$queue_num},{$order_num}";
			
			if($num > $queue_num || $num > $order_num){
				
				echo ",出问题!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\r\n";
				
			}else{
				
				echo "\r\n";
				
			}
			
			
			
			
		}
		
		
		
		
		
	}
	
	function count_money5(){
	
	
		set_time_limit(100);
		
		ini_set('memory_limit', '10000M');

	
		$file = "count/refuse6.csv";
	
		$fp = fopen($file,"r");
	
		$refuse_list = array();
	
		$count = 0;
	
		while($line = fgets($fp, 30000)){
	
	
	
			$count++;
	
	
	
			$temp_arr = explode(',', $line);
	
			if(isset($temp_arr[2]) && $temp_arr[2] != ''){
				//$refuse_list[ $temp_arr[2] ][] = $temp_arr;
				if( isset( $refuse_list[ $temp_arr[2] ]) ){
	
					$refuse_list[ $temp_arr[2] ] += $temp_arr[5];
	
				}else{
	
					$refuse_list[ $temp_arr[2] ] = $temp_arr[5];
	
				}
	
			}else{
	
				echo $line;
	
			}
	
		}
	
		$sql = "
				SELECT O.openid,OA.orderid,OA.web_orderid
				FROM
					iwide_hotel_orders AS O,
					iwide_hotel_order_additions as OA
				WHERE
					O.orderid = OA.orderid
					AND OA.web_orderid != ''
				";
	
		$order_add_array = $this->db->query ( $sql )->result_array ();
	
		$order_add_list = array();
	
		foreach($order_add_array as $order_info){
	
			$order_add_list[ $order_info['openid']  ][]  = $order_info['web_orderid'];
	
		}
	
	
	
		$sql = "
				SELECT O.openid,O.name,O.tel,COUNT(O.openid) as num2,SUM( datediff(OI.enddate,OI.startdate) ) AS num
				FROM
					iwide_hotel_orders AS O,
					iwide_hotel_order_items as OI
				WHERE
					O.inter_id = 'a455510007'
					AND OI.orderid = O.orderid
					AND OI.istatus = 3
					AND OI.enddate < '20160813'
				GROUP BY openid
				";
		$order_array = $this->db->query ( $sql )->result_array ();
	
		$order_list = array();
	
		foreach($order_array as $order_info){
	
			$order_info['send_pk_num'] = 0;
			$order_info['pk_money'] = 0;
			$order_info['refund_num'] = 0;
			$order_info['refund_amount'] = 0;
			$order_info['more_pay'] = 0;
			$order_info['mch_id_csv'] = '没有点击领取';
	
	
	
	
			if(isset( $order_add_list[$order_info['openid']] ) ){
					
				$order_info['web_order_id_csv'] = "|".implode('|', $order_add_list[$order_info['openid']]);
	
			}else{
					
				$order_info['web_order_id_csv'] = "无";
	
			}
	
			$order_list[ $order_info['openid']  ]  = $order_info;
	
		}
	
	
	
	
		$sql = "
				SELECT *
				FROM
					iwide_weixin_text
	
				WHERE
					edit_date > '2016-07-12 00:00:00'
					AND edit_date < '2016-08-11 00:00:00'
					AND content LIKE ':redpack->receive->%发放成功%'
				";
	
		$data = $this->db->query ( $sql )->result_array ();
	
	
		$num = 0;
		$count = 0;
	
		$refuse_num = 0;
		$refuse_count = 0;
	
		$send_package_arr = array();
	
	
	
		foreach($data as $d){
	
			$str = str_replace(":redpack->receive->", "", $d['content']);
	
	
			$oj = (array)simplexml_load_string($str,'SimpleXMLElement', LIBXML_NOCDATA);
	
			$re_oj = (array)json_decode( $d['re_content'] );
	
	
	
	
			if($oj['mch_id'] == '1217945801'){
	
				$num += $oj['total_amount'];
	
				$count++;
	
				$send_package_arr[$oj['re_openid']]['mch_order_id'][] = $oj['mch_billno'];
	
				if(isset( $send_package_arr[$oj['re_openid']]['num'] ) ){
	
					$send_package_arr[$oj['re_openid']]['num']++;
					$send_package_arr[$oj['re_openid']]['pk'] += $oj['total_amount'];
	
				}else{
	
					$send_package_arr[$oj['re_openid']]['num'] = 1;
					$send_package_arr[$oj['re_openid']]['pk'] = $oj['total_amount'];
	
				}
	
				if( isset( $refuse_list[$oj['mch_billno']] ) ){
	
	
					if( isset( $send_package_arr[$oj['re_openid']]['refund_num'] )){
							
						$send_package_arr[$oj['re_openid']]['refund_num']++;
						$send_package_arr[$oj['re_openid']]['refund_amount'] +=  $refuse_list[$oj['mch_billno']];
							
					}else{
							
						$send_package_arr[$oj['re_openid']]['refund_num'] = 1;
						$send_package_arr[$oj['re_openid']]['refund_amount'] =  $refuse_list[$oj['mch_billno']];
							
					}
	
	
				}else{
	
					$send_package_arr[$oj['re_openid']]['member_get_pk_list'][] = $oj;
	
				}
	
	
				/* if(isset($re_oj['refund_time'] ) && $re_oj['refund_time'] != ''){
	
	
				if( isset( $send_package_arr[$oj['re_openid']]['refund_num'] )){
	
				$send_package_arr[$oj['re_openid']]['refund_num']++;
				$send_package_arr[$oj['re_openid']]['refund_amount'] += $re_oj['refund_amount'];
	
				}else{
	
				$send_package_arr[$oj['re_openid']]['refund_num'] = 1;
				$send_package_arr[$oj['re_openid']]['refund_amount'] = $re_oj['refund_amount'];
	
				}
				$refuse_num++;
				$refuse_count += $re_oj['refund_amount'];
	
				} */
	
	
			}
	
		}
	
	
	
		foreach($send_package_arr as $openid => $temp_send_pk){
	
			if( isset( $order_list[$openid] ) ){
	
				//加入订单csv
				$order_list[$openid]['mch_id_csv'] = "|".implode("|", $send_package_arr[$openid]['mch_order_id']);
	
				$order_list[$openid]['send_pk_num'] =  $temp_send_pk['num'];
				$order_list[$openid]['pk_money'] =  $temp_send_pk['pk'];
	
				if(isset( $temp_send_pk['refund_num'] )){
					$order_list[$openid]['refund_num'] =  $temp_send_pk['refund_num'];
				}else{
	
					$order_list[$openid]['refund_num'] = 0;
				}
	
				if(isset( $temp_send_pk['refund_amount'] )){
					$order_list[$openid]['refund_amount'] =  $temp_send_pk['refund_amount'];
				}else{
	
					$order_list[$openid]['refund_amount'] = 0;
				}
	
				if( $order_list[$openid]['send_pk_num'] >  $order_list[$openid]['num'] && isset( $temp_send_pk['member_get_pk_list'] ) && is_array($temp_send_pk['member_get_pk_list']) ){
	
					$len = count($temp_send_pk['member_get_pk_list']);
	
					$more_money = 0;
					for($i = $order_list[$openid]['num'] - 1 ; $i < $order_list[$openid]['send_pk_num'] ; $i++){
	
						if( isset( $temp_send_pk['member_get_pk_list'][$i]) ){
							$more_money += $temp_send_pk['member_get_pk_list'][$i]['total_amount'];
						}
	
					}
	
	
					$order_list[$openid]['more_pay'] = $more_money;
	
	
				}else{
	
					$order_list[$openid]['more_pay'] = 0;
	
				}
	
	
	
			}
	
		}
	
	
	
		$num = $num / 100;
		$refuse_count = $refuse_count / 100;
	
	
		/* 	[openid] => o4F21juo80eSuZxxVb08mv8ymGes
		 [name] => 张素雯
		[tel] => 13861230311
		[num] => 5
		[send_pk_num] => */
	
		$total_num = 0;
		$total_pk_num = 0;
		$total_pk_moeny = 0;
		$total_real_send = 0;
		$total_more_pay = 0;
		$total_refund_num = 0;
		$total_refund_amount = 0;
	
		echo "openid,名称,电话,离店间夜数,发送总数,发送总金额,多发数量,多发总金额,没领红包数,没领红包金额,pms订单号,红包订单号\r\n";
		foreach($order_list as $info){
	
	
			if($info['send_pk_num'] > $info['num']){
					
				$real_send = ($info['send_pk_num'] - $info['num']);
					
			}else{
	
				$real_send = 0;
	
			}
	
	
	
	
			echo $info['openid'].",".$info['name'].",".$info['tel'].",".$info['num'].",".$info['send_pk_num'].",".($info['pk_money']/100).",".$real_send.",".($info['more_pay']/100).",".$info['refund_num'].",".$info['refund_amount'].",".$info['web_order_id_csv'].",".$info['mch_id_csv']."\n";
	
			$total_num += $info['num'];
			$total_pk_num += $info['send_pk_num'];
			$total_pk_moeny += ($info['pk_money']/100);
			$total_real_send += $real_send;
			$total_more_pay += ($info['more_pay']/100);
			$total_refund_num += $info['refund_num'];
			$total_refund_amount += $info['refund_amount'];
	
	
		}
	
		echo ",".",".",".$total_num.",".$total_pk_num.",".$total_pk_moeny.",".$total_real_send.",".$total_more_pay.",".$total_refund_num.",".$total_refund_amount.",".","."\n";
	
	
	}
	
	function log_mysql_lock(){
		
		$sql = "SELECT
					*
				FROM
					information_schema.INNODB_TRX
				
				
				";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		$time = date("Y-m-d+H:i:s");
		
		$date = date("Y-m-d");
		
		$text_trx = $time."\t".json_encode($data)."\n";
		
		$sql = "SELECT
					*
				FROM
					information_schema.INNODB_LOCK_WAITS
		
		
				";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		$text_lock_waits = $time."\t".json_encode($data)."\n";
		
		$sql = "SELECT
					*
				FROM
					information_schema.INNODB_LOCKS
		
		
				";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		$text_lock = $time."\t".json_encode($data)."\n";
		
		$fp = fopen('mysql_lock_log/'.$date."_lock_.log", "a");

		fwrite($fp, $text_trx);
		fwrite($fp, $text_lock);
		fwrite($fp, $text_lock_waits);
		
		fclose($fp);
		//print_r($data);
		exit;
		
		//information_schema
		
	}
	
	
	function record_api(){
		
		$fp = fopen('api_log/last_id.', "w");
		
		fwrite($fp, $content);
		
		fclose($fp);
		//print_r($data);
		exit;
		
		
		$sql = "SELECT
					*
				FROM
					iwide_webservice_record
				
		
				";
		
		$data = $this->db->query ( $sql )->result_array ();
		
		
		
		$content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['REMOTE_ADDR']." | ".session_id()." | ".$_SERVER['REQUEST_URI'].' | POST='.json_encode($_POST).' | '.$content."\n";
		
		
		$fp = fopen('api_log/'.$date."_lock_.log", "a");
		
		
		fwrite($fp, $content);
		
		fclose($fp);
		//print_r($data);
		exit;
		
	}
	
	
	public function getSu8Hotel(){
		
		$hotel_id = $this->input->get("hotel_id");
		
		$this->load->library ( 'Baseapi/Subaapi_webservice',array(
		
				'_testModel'=>false
		) );
		
		$suba = new Subaapi_webservice(true);
		
		$data = $suba->GetHotelDetail($hotel_id);
		
		$data2 = $suba->GetHotelImgs($hotel_id);
		
		$data3 = $suba->GetHotelRooms($hotel_id, "2016-10-10", "2016-10-11", 1);
		
		print_r($data);
		
		print_r($data2);
		
		print_r($data3);
		
	}
	
	/**
	 * 日志写入
	 * 格式：
	 * Y-m-d H:i:s | 进程id | 客户端IP | session id | 访问地址 | POST=post参数(json格式) | 内容
	 * @param unknown $content 内容
	 * @param unknown $dir 目录名称
	 * @param string $file_key	文件名标记
	 */
	private function w( $content,$dir = 'default',$file_key = '' )
	{
	
		$file= date('Y-m-d').$file_key. '.log';
		//echo $tmpfile;die;
		$path= APPPATH.'logs'.DS. $dir. DS;
	
		if( !file_exists($path) ) {
			@mkdir($path, 0777, TRUE);
		}
	
		$fp = fopen( $path. $file, "a");
	
		$sql = str_replace("\r"," ",$content);
		$sql = str_replace("\n"," ",$content);
	
		//echo __FILE__
		$content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['REMOTE_ADDR']." | ".session_id()." | ".$_SERVER['REQUEST_URI'].' | POST='.json_encode($_POST).' | '.$content."\n";
	
		fwrite($fp, $content);
		fclose($fp);
	
	}
	
	
	
	
	/**
	 * pms接口调用日志记录
	 * 格式：
	 * self::w()格式 | inter_id | 等待时间 | api类型 | api地址 | 发送记录 | 接收记录 | 备注
	 * @param unknown $inter_id
	 * @param unknown $pms_wait_time 接口调用耗时
	 * @param unknown $api_type api类型，用于不同酒店的api有多个接口，用于识别不同的接口
	 * @param unknown $api_url api接口链接
	 * @param unknown $send_content 发送内容
	 * @param unknown $record_content 接收到的内容
	 * @param unknown $remark 备注
	 */
	private function pms_access_record($inter_id,$pms_wait_time,$api_type,$api_url,$send_content,$record_content,$remark){
	
		//时间 酒店inter_id 调用耗时(ms） openid 接口类别 发送记录 接收记录
		//self::w($content, $dir)
		$arr[] = $inter_id;
		$arr[] = $pms_wait_time;
		$arr[] = $api_type;
		$arr[] = $api_url;
		$arr[] = $send_content;
		$arr[] = $record_content;
		$arr[] = $remark;
		$content = implode(" | ",$arr);
	
		//$this->w($content,self::_PMS_ACCESS_LOG_DIR,"_access");
		
	
	}
	
	
	public function test_refresh(){
		
		//测试499问题
		
		sleep(20);
		
		echo "yes";
		
	}
	
	public function clear_session(){
	    $this->session->sess_destroy();
		$this->session->set_userdata ( array ( $_GET['id'] . 'openid' => "" ) );
		echo "ok";
		exit;
	
	
	}
	
	public function testPi(){
		
		//$this->load->model("statistics/Statictics_model");
		$this->load->model("statistics/Statistics_model");
		
		$this->Statistics_model->outputJs('a457508847','asdfsdfsdf');
	//	echo $this->Statistics_model->addStatisticsWebsiteByInterid('a457508847');
		
	}
	
	public function synPulics(){
	
		//$this->load->model("statistics/Statictics_model");
		$this->load->model("statistics/Statistics_model");
	
		$publics = $this->Statistics_model->getAllPublics();
		//print_r($publics);
		
		foreach($publics as $d){
			
			$inter_id = $d['inter_id'];
			
			echo $inter_id." : ".$this->Statistics_model->addStatisticsWebsiteByInterid($inter_id)."<br>";
			
		}
		
		
		//$this->Statistics_model->outputJs('a457508847','asdfsdfsdf');
		//	echo $this->Statistics_model->addStatisticsWebsiteByInterid('a457508847');
	
	}
	
	public function testInsert(){
		
		$this->load->model("member/Weixin_text");
		
		$this->Weixin_text->add_weixin_text("test");
		
	}
	
	function refresh_accesstoken(){
		
		$inter_id = 'a421641095';
		//reflash_access_token
		$this->load->model ( 'wx/Access_token_model' );
		$reflash_access_token = $this->Access_token_model->reflash_access_token ( $inter_id );
		
		print_r($reflash_access_token);
	}
	
	function update_unionid(){
	
		set_time_limit(0);
		
		ini_set('memory_limit', '10000M');
		
		
		if( !isset($_REQUEST['inter_id'])){
		
			echo "inter_id is null";
			exit;
		
		}
		
		if( !isset($_REQUEST['mod_num']) || $_REQUEST['mod_num']  < 1 ){
		
			echo "mod_num is null";
			exit;
		
		}
		
		if( !isset($_REQUEST['th']) ){
				
			echo "th is null";
			exit;
				
		}
		$inter_id = $_REQUEST['inter_id'];
		
		$mod_num = $_REQUEST['mod_num'];
		
		if($_REQUEST['th'] >= $mod_num){
				
			echo "th must less than {$mod_num}";
			exit;
				
		}
		
		
		
		
		$th_num = $_REQUEST['th'];
		
		$lock_file_path = APPPATH.'logs/temp_update_unionid/'.$inter_id."_lock_{$th_num}";
		
		if( file_exists($lock_file_path) ){
			
			echo "lock";
			exit;
			
		}else{
			

			$fp = fopen( $lock_file_path, "a");

			//echo __FILE__
			$content = "lock";
			
			fwrite($fp, $content);
			fclose($fp);
			
			
		}

		//$page = 1;
		//$every_times_rows = 1000;
		
		$this->load->model ( 'wxapp/User_model' );
		
		$sql = "
				SELECT id,openid
				FROM
					iwide_fans
				WHERE
					inter_id = '$inter_id'
				ORDER BY 
					openid ASC
				";
		
		$data = $this->db->query ( $sql )->result_array();
		
		$sql = "SELECT openid
				FROM `iwide_fans_ext`
				WHERE
					inter_id = '{$inter_id}'
				";
		
		
		$data_ext = $this->db->query ( $sql )->result_array();
		
		$temp_data_ext = array();
		foreach($data_ext as $d_ext){
			
			$temp_data_ext[$d_ext['openid']] = 1;
			
		}
		unset($data_ext);
		
		$this->load->model ( 'wx/Publics_model' );
		
		$this->load->model ( 'wx/Access_token_model' );
		$access_token = $this->Access_token_model->get_access_token ( $inter_id );
		
		foreach($data as $d){
			
			if( ( $d['id'] % $mod_num ) !=  $th_num){
				
				continue;
				
			}
			
			$openid = $d['openid'];
			
			if(isset($temp_data_ext[$openid])){
				
				continue;
				
			}

			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
			
			$con = curl_init ( $url );
			curl_setopt ( $con, CURLOPT_HEADER, false );
			curl_setopt ( $con, CURLOPT_RETURNTRANSFER, true );
			curl_setopt ( $con, CURLOPT_SSL_VERIFYPEER, false );
			$result = curl_exec ( $con );
			
			MYLOG::w($url." | ".$result,"temp_update_unionid","wxapi_result");
			
			$result = json_decode($result,true);
			
			if(isset($result['errcode']) && ($result['errcode'] == 40001 || $result['errcode'] == 42001) ){
					
				print_r($result);
				
				exit;
					
			}
			
			if(isset($result['errcode']) && $result['errcode'] == 40003){
				
				MYLOG::w("openid : {$openid} | 40003","temp_update_unionid","_th_{$th_num}");
				continue;
				
			}
			
			$iwideid = $this->User_model->create_iwideid_for_import_fans ( $inter_id ,$openid);
			
			
		 	$sql = "
		 			INSERT INTO
		 				iwide_fans_ext(inter_id,iwideid,openid,unionid)
		 				VALUES
		 				('{$inter_id}','{$iwideid}','{$openid}','{$result['unionid']}');
		 			
		 			";
			
		 	$this->db->query($sql);
			
			
			MYLOG::w("openid : {$openid} | OK ","temp_update_unionid","_th_{$inter_id}_{$th_num}");
			
			
		}
		
	
	}
	
	function deleteSameOpenid(){
		
		set_time_limit(0);
		
		ini_set('memory_limit', '10000M');
		
		$sql = "
				SELECT openid,count(*) as num 
				FROM iwide_fans_ext 
				GROUP BY openid
				HAVING num > 1
				
				";
		
		$data = $this->db->query ( $sql )->result_array();
		
		foreach($data as $d){
			
			$del_num = $d['num'] - 1;
			$sql = "
					DELETE
					FROM iwide_fans_ext
					WHERE
						openid = '{$d['openid']}'
					LIMIT {$del_num}
					
					";
			$this->db->query ( $sql );
			
			
		}
		
		
	}
	
	function testfun(){
		
		$this->load->model ( 'wxapp/User_model' );
		
		$inter_id = 'a421641095';
		$openid = "aaaa";
		$unionid = "bbbb";
		$this->User_model->addUnionidToUser($inter_id,$openid,$unionid);
		
	}
	
	
	function testredis(){
		
		$redis = new redis();
		$result= $redis->connect('200.200.200.200', 6382);
		
		
	}
	
	function bindCheckShell(){
		
		$path = "/var/www/iwide30core/trunk/iwide3_0/controllers";
		
		$path = 'E:\xampp\htdocs\iwide_super8\trunk\iwide3_0\controllers\front\\';
		
		$file_key = 3;
		
		//print_r($this->getDir($path));
		$files = $this->getDir($path);
	
		$method_arr = array();
		$url_arr = array();
		$api_url = "http://dingfang.liyewl.com/index.php";
		$params = "?id=a469428180&openid=oX3WojhfNUD4JzmlwTzuKba1Myse&debug={$file_key}";
		
		foreach($files as $file){
			
			$fp = fopen($file,"r");
			
			if(strpos($file,".php") < 0){
				continue;
			}
			
			
			$temp_url_key = str_replace($path,"",$file);
			$temp_url_key = str_replace(".php","",$temp_url_key);
			
			while($line = fgets($fp)){
				//echo $line;	
				
				//echo strpos($line,"public ");
				if(strpos($line,"function ") > 0 ){
					
					if(strpos($line,"__construct") > 0){
						continue;
					}
					
					if(strpos($line,"private") <= 0){
							
						//$method_arr
						$temp = explode("function ",$line);
						$method = str_replace(" ","",$temp[1]);
						$method = str_replace("\n","",$method);
						$method = str_replace("\r","",$method);
						$method = str_replace("(","",$method);
						$method = str_replace(")","",$method);
						$method = str_replace("{","",$method);
						$url_arr[]  = $api_url.$temp_url_key."/".$method.$params;
						
					}
					//echo $line;
					//exit;
					
				}
				
				
					
			}
			
			fclose($fp);
			
			//break;
			
		}
		
		$fp = fopen("check_php.sh","w");
		fwrite($fp,"#/bin/sh\n\n");
		foreach($url_arr as $url){
			
			$line = "wget -O - '{$url}' >> out.txt 2&>1 &\n";
			fwrite($fp,$line);
			
		}
		
		fclose($fp);
		
	}
	
	function searchDir($path,&$data){
		
		if(is_dir($path)){
			$dp=dir($path);
			
			while($file=$dp->read()){
				if($file!='.'&& $file!='..'){
					$this->searchDir($path.'/'.$file,$data);
				}
			}
			$dp->close();
		}
		if(is_file($path)){
			$data[]=$path;
		}
	}
	
	function getDir($dir){
		$data=array();
		$this->searchDir($dir,$data);
		return   $data;
	}
	
	
	public function callback_create(){
	    //dingfang.liyewl.com/index.php/welcome/callback_create?channel_id=10001&stream_alias=10006&hls_url[0]=hls_url&pic_url[0]=pic_url
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
		$stream_id = $_REQUEST['stream_alias'];
		
		$sql = "
				UPDATE
		              iwide_zb_channel
		        SET
		              play_url = '{$_REQUEST['hls_url'][0]}',
		              pic_url = '{$_REQUEST['pic_url'][0]}',
		              status = 1
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
		
		if($this->db->affected_rows()){
		    
		    
		    $this->load->model ( 'livebc/Channel_model' );
		    
		    $stream = $this->Channel_model->get_stream_id($_REQUEST['stream_alias']);
		    	
		    $stream_id = $stream['stream_id'];
		    
		    $res = $this->Channel_model->setStreamStatus($stream_id,0,2);
		    
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
	
	public function ckauto(){
		$this->load->model('distribute/Idistribute_model');
		$this->load->model('wx/publics_model');
		$inter_id = $this->session->userdata('inter_id');
		$openid   = $this->session->userdata($inter_id.'openid');
		$fansInfo = $this->Idistribute_model->fans_is_saler_simple($inter_id,$openid);
		if(!$fansInfo){
			$deliver_infos = $this->publics_model->get_public_by_id ( $this->openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT') );
			$url = '';
			if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx')
				$url =  'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] ;
			else
				$url =  'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ;
			$site_url = prep_url($deliver_infos['domain']).'/distribute/dis_ext/auto_back/'.'?id='.$this->openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT').'&f='.base64_encode($this->inter_id.'***'.$this->openid.'***'.$url);
			redirect($site_url);
		}
	}
	
}
