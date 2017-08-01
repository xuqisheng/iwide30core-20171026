<?php
class Zhuzhe_hotel_model extends CI_Model {
	const TAB_HO = 'hotel_orders';
	const TOKEN = 'ejia365_82250_20151023_web:admin';
	const ZHUZHEAPIPATH = 'http://webapi.zhuzher.com';
	function __construct() {
		parent::__construct ();
	}
	function qfcurl($url, $data = '', $token = '', $inter_id = '') {
		$s = json_encode ( $data, JSON_UNESCAPED_UNICODE );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
		curl_setopt ( $ch, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
		curl_setopt ( $ch, CURLOPT_USERPWD, $token );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
		if (is_array ( $data )) {
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( $data ) );
		} elseif ($data) {
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		}
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 5 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
		$now = time ();
		$data = curl_exec ( $ch );
		if (curl_errno ( $ch )) {
			echo 'Errno' . curl_error ( $ch ); // 捕抓异常
		}
		curl_close ( $ch );
		
		$this->load->model ( 'common/Webservice_model' );
		$this->Webservice_model->add_webservice_record ( $inter_id, 'zhuzhe', $url, $s, $data, 'query_post', $now, microtime (), $this->session->userdata ( $inter_id . 'openid' ) );
		
		return $data;
	}
	function path($url) {
		return self::ZHUZHEAPIPATH . $url;
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array(), $pms_set = array()) {
		$web_rids = array ();
		foreach ( $rooms as $r ) {
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		$params = array (
				'condit' => $condit,
				'web_rids' => $web_rids 
		);
		$pms_data = $this->get_web_roomtype ( $pms_set, $condit ['startdate'], $condit ['enddate'], $params );
		if (! empty ( $pms_data )) {
			$days = get_room_night($condit['startdate'],$condit['enddate'],'round',$condit);//至少有一个间夜
			$allprice = array ();
			$this->load->model ( 'hotel/Member_model' );
			$levels = $this->Member_model->get_member_levels ( $idents ['inter_id'] );
			$member_level = $condit ['member_level'];
			// foreach ( $rooms as $nr ) {
			// $needrooms [] = $nr ['webser_id'];
			// $web_rooms [$nr ['webser_id']] = $nr;
			// }
			switch ($pms_set ['pms_room_state_way']) {
				case 1 :
			        $this->load->model ( 'common/Webservice_model' );
       		        $level_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
           			    'member_level'
           		    ), 1, 'l2w' );
       		        $new_level=$levels;
   		            if (!empty($level_reflect['member_level'])&&isset($level_reflect['member_level'][$member_level])){
   		                $member_level=$level_reflect['member_level'][$member_level];
   		                foreach ($levels as $lv=>$lvname){
   		                   if(isset($level_reflect['member_level'][$lv])){
   		                       $new_level[$level_reflect['member_level'][$lv]]=$lvname;
   		                   }
   		                }
   		            }
					return $this->get_rooms_change_allpms ( $pms_data, array (
							// 'needrooms' => $needrooms,
							// 'web_rooms' => $web_rooms,
							'rooms' => $rooms 
					), array (
							'member_level' => $member_level,
							'levels' => $new_level,
							'days' => $days,
							'idents' => $idents,
							'condit' => $condit 
					) );
					break;
				case 2 :
				    $this->load->model ( 'common/Webservice_model' );
       		        $level_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
           			    'member_level'
           		    ), 1, 'l2w' );
       		        $new_level=$levels;
   		            if (!empty($level_reflect['member_level'])&&isset($level_reflect['member_level'][$member_level])){
   		                $member_level=$level_reflect['member_level'][$member_level];
   		                foreach ($levels as $lv=>$lvname){
   		                   if(isset($level_reflect['member_level'][$lv])){
   		                       $new_level[$level_reflect['member_level'][$lv]]=$lvname;
   		                   }
   		                }
   		            }
					return $this->get_rooms_change_conexi ( $pms_data, array (
							// 'needrooms' => $needrooms,
							// 'web_rooms' => $web_rooms,
							'rooms' => $rooms 
					), array (
							'member_level' => $member_level,
							'levels' => $new_level,
							'days' => $days,
							'idents' => $idents,
							'condit' => $condit 
					) );
					break;
				case 3 :
					return $this->get_rooms_change_lmem ( $pms_data, array (
							// 'needrooms' => $needrooms,
							// 'web_rooms' => $web_rooms,
							'rooms' => $rooms 
					), array (
							'member_level' => $member_level,
							'levels' => $levels,
							'days' => $days,
							'idents' => $idents,
							'condit' => $condit 
					) );
					break;
				default :
					return array ();
					break;
			}
		}
	}
	function get_rooms_change_allpms($pms_data, $rooms, $params) {
		$data = array ();
		// $needrooms = $rooms ['needrooms'];
		// $web_rooms = $rooms ['web_rooms'];
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_level'];
		$levels = $params ['levels'];
		$condit = $params ['condit'];
		foreach ( $local_rooms as $lrm ) {
			$web_info = array ();
			$webps = array ();
			$min_price = array ();
			if (! empty ( $pms_data [$lrm ['webser_id']] )) {
				$web_state = $pms_data [$lrm ['webser_id']];
				foreach ( $web_state as $level => $state ) {
					if (isset ( $levels [$state ['price_code']] )) {
						$code = $state ['price_code'];
						$state ['price_name'] = empty ( $levels [$level] ) ? '' : $levels [$level];
						$state ['des'] = empty ( $levels [$level] ) ? '' : $levels [$level];
						$nums = isset ( $condit ['nums'] [$lrm ['room_id']] ) ? $condit ['nums'] [$lrm ['room_id']] : 1;
						$state ['total_price'] = $state ['total'] * $nums;
						$state ['related_des'] = '';
						if ($code == $member_level) {
							$min_price [] = $state ['avg_price'];
							$web_info [$code] = $state;
						}
						$webps [$code] = $state;
					}
				}
			}
			if (! empty ( $webps )) {
				$data [$lrm ['room_id']] ['room_info'] = $lrm;
				$data [$lrm ['room_id']] ['state_info'] = $web_info;
				$data [$lrm ['room_id']] ['show_info'] = $webps;
				$data [$lrm ['room_id']] ['lowest'] = min ( $min_price );
				$data [$lrm ['room_id']] ['highest'] = max ( $min_price );
			}
		}
		return $data;
	}
	function get_rooms_change_conexi_dec($pms_data, $rooms, $params) {
		$data = array ();
		// $needrooms = $rooms ['needrooms'];
		// $web_rooms = $rooms ['web_rooms'];
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_level'];
		$levels = $params ['levels'];
		$condit = $params ['condit'];
		$this->load->model ( 'hotel/Order_model' );
		$params ['condit'] ['only_room_info'] = TRUE;
		$local_data = $this->Order_model->get_rooms_change ( $local_rooms, $params ['idents'], $params ['condit'] );
		$external = array ();
		foreach ( $local_data as $room_id => $state ) {
			if (! empty ( $state ['state_info'] )) {
				foreach ( $state ['state_info'] as $si ) {
					if ($si ['external_code'] !== '') {
						$external [$state ['room_info'] ['room_id']] [$si ['external_code']] [] = $si;
						// } else {
						// $extra [$state ['room_info'] ['room_id']] [] = $si;
					}
				}
			}
		}
		foreach ( $local_data as $lrm ) {
			$web_info = array ();
			$webps = array ();
			$min_price = array ();
			if (! empty ( $pms_data [$lrm ['room_info'] ['webser_id']] )) {
				$web_state = $pms_data [$lrm ['room_info'] ['webser_id']];
				foreach ( $web_state as $level => $state ) {
					if (isset ( $levels [$state ['price_code']] )) {
						$code = $state ['price_code'];
						$state ['price_name'] = empty ( $levels [$level] ) ? '' : $levels [$level];
						$state ['des'] = empty ( $levels [$level] ) ? '' : $levels [$level];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						$state ['total_price'] = $state ['total'] * $nums;
						$state ['related_des'] = '';
						if ($code == $member_level) {
							$min_price [] = $state ['avg_price'];
							if (! empty ( $web_info [$code] )) {
								$web_info [$code . '_conflict'] = $state;
							} else {
								$web_info [$code] = $state;
							}
						}
						$webps [$code] = $state;
						if (! empty ( $external [$lrm ['room_info'] ['room_id']] [$level] )) {
							foreach ( $external [$lrm ['room_info'] ['room_id']] [$level] as $lsi ) {
								$lsi ['least_num'] = $lsi ['least_num'] <= $state ['least_num'] ? $lsi ['least_num'] : $state ['least_num'];
								$lsi ['book_status'] = $state ['book_status'];
								if ($lsi ['least_num'] <= 0) {
									$lsi ['book_status'] = 'full';
								}
	
								$web_info [$lsi ['price_code']] = $lsi;
								$min_price [] = $lsi ['avg_price'];
							}
						}
					}
				}
			}
			if (! empty ( $webps )) {
				$data [$lrm ['room_info'] ['room_id']] ['room_info'] = $lrm ['room_info'];
				$data [$lrm ['room_info'] ['room_id']] ['state_info'] = $web_info;
				$data [$lrm ['room_info'] ['room_id']] ['show_info'] = $webps;
				$data [$lrm ['room_info'] ['room_id']] ['lowest'] = min ( $min_price );
				$data [$lrm ['room_info'] ['room_id']] ['highest'] = max ( $min_price );
			}
		}
		return $data;
	}
	function get_rooms_change_conexi($pms_data, $rooms, $params) {
		$data = array ();
		// $needrooms = $rooms ['needrooms'];
		// $web_rooms = $rooms ['web_rooms'];
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_level'];
		$levels = $params ['levels'];
		$condit = $params ['condit'];
		$this->load->model ( 'hotel/Order_model' );
		$params ['condit'] ['only_room_info'] = TRUE;
		$local_data = $this->Order_model->get_rooms_change ( $local_rooms, $params ['idents'], $params ['condit'] );
// 		var_dump($local_data);
// 		var_dump($pms_data);
// 		exit;
		foreach ( $local_data as $lrm ) {
			$web_info = array ();
			$webps = array ();
			$min_price = array ();
			if (! empty ( $pms_data [$lrm ['room_info'] ['webser_id']] )) {
				$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
				if (!empty($lrm ['state_info'])){
					foreach ( $lrm ['state_info'] as $sik => $si ) {
						if ($si ['external_code'] !== '' && ! empty ( $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']] )) {
							$web_state=$pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
							$si ['least_num'] = $si ['least_num'] <= $web_state ['least_num'] ? $si ['least_num'] : $web_state ['least_num'];
// 							$si ['least_num'] = $web_state ['least_num'];
							if ($si['price_type']=='member'){
								$allprice = '';
								$amount = 0;
								foreach ( $web_state ['date_detail'] as $dk => $td ) {
	// 								$web_state ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
									$web_state ['date_detail'] [$dk] ['nums'] = $si ['least_num'];
									$allprice .= ',' . $web_state ['date_detail'] [$dk] ['price'];
									$amount += $web_state ['date_detail'] [$dk] ['price'];
								}
								$si ['date_detail'] = $web_state ['date_detail'];
								
								$si ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
								$si ['allprice'] = substr ( $allprice, 1 );
								$si ['total'] = intval ( $amount );
								$si ['total_price'] = $si ['total'] * $nums;
							}else{
								foreach ( $si ['date_detail'] as $dk => $td ) {
									$si ['date_detail'] [$dk] ['nums'] = $si ['least_num'];
								}
							}
							$si ['book_status'] = $web_state ['book_status'];
							if ($si ['least_num'] <= 0) {
								$si ['book_status'] = 'full';
							}
							
							$web_info [$si ['price_code']] = $si;
							$min_price [] = $si ['avg_price'];
						}
					}
				}
				if (!empty($lrm ['show_info'])){
					foreach ( $lrm ['show_info'] as $sik => $si ) {
						if ($si ['external_code'] !== '' && ! empty ( $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']] )) {
							$web_state=$pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
							$si ['least_num'] = $si ['least_num'] <= $web_state ['least_num'] ? $si ['least_num'] : $web_state ['least_num'];
// 							$si ['least_num'] = $web_state ['least_num'];
							if ($si['price_type']=='member'){
								$allprice = '';
								$amount = 0;
								foreach ( $web_state ['date_detail'] as $dk => $td ) {
	// 								$web_state ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
									$web_state ['date_detail'] [$dk] ['nums'] = $si ['least_num'];
									$allprice .= ',' . $web_state ['date_detail'] [$dk] ['price'];
									$amount += $web_state ['date_detail'] [$dk] ['price'];
								}
								$si ['date_detail'] = $web_state ['date_detail'];
								
								$si ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
								$si ['allprice'] = substr ( $allprice, 1 );
								$si ['total'] = intval ( $amount );
								$si ['total_price'] = $si ['total'] * $nums;
							}else{
								foreach ( $si ['date_detail'] as $dk => $td ) {
									$si ['date_detail'] [$dk] ['nums'] = $si ['least_num'];
								}
							}
							$si ['book_status'] = $web_state ['book_status'];
							if ($si ['least_num'] <= 0) {
								$si ['book_status'] = 'full';
							}
							
							$webps [$si ['price_code']] = $si;
						}
					}
				}
			}
			if (! empty ( $web_info )) {
				$data [$lrm ['room_info'] ['room_id']] ['room_info'] = $lrm ['room_info'];
				$data [$lrm ['room_info'] ['room_id']] ['state_info'] = $web_info;
				$data [$lrm ['room_info'] ['room_id']] ['show_info'] = $webps;
				$data [$lrm ['room_info'] ['room_id']] ['lowest'] = min ( $min_price );
				$data [$lrm ['room_info'] ['room_id']] ['highest'] = max ( $min_price );
			}
		}
		return $data;
	}
	function get_rooms_change_lmem($pms_data, $rooms, $params) {
		$data = array ();
		// $needrooms = $rooms ['needrooms'];
		// $web_rooms = $rooms ['web_rooms'];
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_level'];
		$levels = $params ['levels'];
		$condit = $params ['condit'];
		$this->load->model ( 'hotel/Order_model' );
		$params ['condit'] ['only_room_info'] = TRUE;
		$data = $this->Order_model->get_rooms_change ( $local_rooms, $params ['idents'], $params ['condit'] );
		$this->load->model ( 'hotel/Order_model' );
		foreach ( $data as $room_key => $lrm ) {
			$web_info = array ();
			$webps = array ();
			$min_price = array ();
			if(empty ( $pms_data [$lrm ['room_info'] ['webser_id']] )){
			    unset($data[$room_key]);
			    continue;
			}
			foreach ( $lrm ['state_info'] as $sik => $si ) {
				if ($si ['external_code'] !== '' && ! empty ( $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']] )) {
					if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
						$tmp = $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
						$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						$allprice = '';
						$amount = 0;
						foreach ( $tmp ['date_detail'] as $dk => $td ) {
							$tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
							$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
						
						$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
						$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['state_info'] [$sik] ['total'] = intval ( $amount );
						$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
						$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
					} else {
						$tmp = $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
						$data [$room_key] ['state_info'] [$sik] ['least_num'] = $data [$room_key] ['state_info'] [$sik] ['least_num'] <= $tmp ['least_num'] ? $data [$room_key] ['state_info'] [$sik] ['least_num'] : $tmp ['least_num'];
						$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						if ($data [$room_key] ['state_info'] [$sik] ['least_num'] <= 0) {
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = 'full';
						}
						if (!empty($si ['related_code'])){
							$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
							$allprice = '';
							$amount = 0;
							foreach ( $tmp ['date_detail'] as $dk => $td ) {
								$tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
								$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
								$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
								$amount += $tmp ['date_detail'] [$dk] ['price'];
							}
							$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
							
							$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
							$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
							$data [$room_key] ['state_info'] [$sik] ['total'] = intval ( $amount );
							$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
						}
						
						$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
					}
				} else
					$min_price [] = $si ['avg_price'];
			}
			$data [$room_key] ['lowest'] = min ( $min_price );
			$data [$room_key] ['highest'] = max ( $min_price );
			foreach ( $lrm ['show_info'] as $sik => $si ) {
				// if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
				if ($si ['external_code'] !== '' && ! empty ( $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']] )) {
					$tmp = $pms_data [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
					$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
					$data [$room_key] ['show_info'] [$sik] ['least_num'] = $tmp ['least_num'];
					$data [$room_key] ['show_info'] [$sik] ['book_status'] = $tmp ['book_status'];
					$allprice = '';
					$amount = 0;
					foreach ( $tmp ['date_detail'] as $dk => $td ) {
						$tmp ['date_detail'] [$dk] ['price'] = round ( $this->Order_model->cal_related_price ( $td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price' ) );
						$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
						$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
						$amount += $tmp ['date_detail'] [$dk] ['price'];
					}
					$data [$room_key] ['show_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
					
					$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 1 );
					$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
					$data [$room_key] ['show_info'] [$sik] ['total'] = intval ( $amount );
					$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
				}
				// }
			}
		}
		// var_dump($data);
		return $data;
	}
	function get_web_roomtype($pms_set, $startdate, $enddate, $params) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$hotelretdata = $this->qfcurl ( $pms_auth ['apipath'] . '/hotel/' . $pms_set ['hotel_web_id'] . '/' . date ( 'Y-m-d', strtotime ( $startdate ) ) . '/' . date ( 'Y-m-d', strtotime ( $enddate ) ), '', $pms_auth ['token'], $pms_set ['inter_id'] );
		preg_match_all ( "/\<houseTypeList\>(.*?)\<\/houseTypeList\>/", $hotelretdata, $hotelretdataarr );
		$data = array ();
		$days = get_room_night($startdate,$enddate,'round');//至少有一个间夜
		if (isset ( $hotelretdataarr [0] )) {
			$hotelretxml = $hotelretdataarr [0] [0];
			$pms_data = json_decode ( json_encode ( simplexml_load_string ( $hotelretxml ) ), TRUE );
			foreach ( $pms_data ['houseType'] as $key => $value ) {
				$houseTypeId = intval ( $value ['houseTypeId'] );
				$least_num = $value ['maxBookedNum'];
				if (isset ( $params ['web_rids'] [$houseTypeId] )) {
					$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$houseTypeId]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$houseTypeId]];
				} else {
					$nums = 1;
				}
				$book_status = 'full';
				if ($least_num >= $nums) {
					$book_status = 'available';
				}
				
				$everyDayPrice = $value ['everyDayPrice']; // 336.0#2016-01-06#3#0@336.0#2016-01-07#4#0 , 316.0#2016-01-06#3#1@316.0#2016-01-07#4#1 , ...
				$mins = array ();
				if (! empty ( $everyDayPrice )) {
					$tmp = array ();
					$dayallprice = explode ( ',', $everyDayPrice );
					// array('336.0#2016-01-06#3#0@336.0#2016-01-07#4#0','316.0#2016-01-06#3#1@316.0#2016-01-07#4#1') 各等级每日房价： 价格#日期#星期#会员等级
					foreach ( $dayallprice as $vpr ) { // 循环各等级房价
						$vprarr = explode ( '@', $vpr ); // array('336.0#2016-01-06#3#0','336.0#2016-01-07#4#0')
						$date_detail = array ();
						$allprice = '';
						$amount = '';
						
						foreach ( $vprarr as $state ) {
							$date_state = explode ( '#', $state );
							$state_level = $date_state [3];
							$date_detail [date ( 'Ymd', strtotime ( $date_state [1] ) )] = array (
									'price' => $date_state [0],
									'nums' => $least_num 
							);
							$allprice .= ',' . $date_state [0];
							$amount += $date_state [0];
						}
						$tmp [$state_level] ['least_num'] = $least_num;
						$tmp [$state_level] ['book_status'] = $book_status;
						$tmp [$state_level] ['allprice'] = substr ( $allprice, 1 );
						$tmp [$state_level] ['date_detail'] = $date_detail;
						$tmp [$state_level] ['total'] = $amount;
						$tmp [$state_level] ['avg_price'] = number_format ( $amount / $days, 1 );
						$tmp [$state_level] ['price_code'] = $state_level;
						$tmp [$state_level] ['price_type'] = 'pms';
						$tmp [$state_level] ['price_resource'] = 'webservice';
					}
					$data [$houseTypeId] = $tmp;
				}
			}
		}
		return $data;
	}
	function get_hotel_rooms($idents, $webser_id) {
		$inter_id = isset ( $idents ['inter_id'] ) ? $idents ['inter_id'] : "";
		$hotel_id = isset ( $idents ['hotel_id'] ) ? $idents ['hotel_id'] : "";
		
		if (! $webser_id) {
			return array ();
		}
		
		$sql = 'SELECT * FROM ' . $this->readDB()->dbprefix ( 'hotel_rooms' ) . ' WHERE webser_id=? and inter_id=? and hotel_id=?';
		$query = $this->readDB()->query ( $sql, array (
				$webser_id,
				$inter_id,
				$hotel_id 
		) )->result_array ();
		
		return $query;
	}
	function in_hotel_rooms($data) {
		$this->db->insert ( $this->db->dbprefix ( 'hotel_rooms' ), $data );
	}
	function status_order_web($inter_id, $order, $pms_set = array()) {
	}
	function zhuzhe_enum($type) {
		switch ($type) {
			case 'status' :
				return array (
						'0' => 1,
						'9' => 1,
						'1' => 2,
						'2' => 3,
						'6' => 5,
						'8' => 8 
				);
				break;
			case 'sub_status' :
				return array (
						'0' => 1,
						'1' => 2,
						'2' => 3,
						'6' => 5,
						'8' => 8 
				);
				break;
			default :
				break;
		}
	}
	function cancel_order_web($inter_id, $order, $pms_set = array()) {
		/*
		 * $sql = 'SELECT * FROM ' . $this->db->dbprefix ( 'hotel_order_additions' ) . ' WHERE orderid=? and inter_id=?';
		 * $order_data = $this->db->query ( $sql, array (
		 * $order ['orderid'],
		 * $inter_id
		 * ) )->result_array ();
		 *
		 * if (! $order_data) {
		 * return array (
		 * 'Result' => 0
		 * );
		 * }
		 */
		$web_orderid = isset ( $order ['web_orderid'] ) ? $order ['web_orderid'] : "";
		if (! $web_orderid) {
			return array (
					'Result' => 0 
			);
		}
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$remove_data = $this->qfcurl ( $pms_auth ['apipath'] . '/order/remove/' . $web_orderid . '/' . $order ['roomnums'], '', $pms_auth ['token'], $inter_id );
		$result = simplexml_load_string ( $remove_data );
		$result = json_decode ( json_encode ( $result ), TRUE );
		if (isset ( $result ['code'] ) && $result ['code'] == 0)
			return array (
					'Result' => 1 
			);
		return array (
				'Result' => 0 
		);
	}
	public function get_web_hotels($token = '') {
		$url = "http://webapi.zhuzher.com/data/hotels";
		$result = $this->qfcurl ( $url, '', $token );
		$result = simplexml_load_string ( $result );
		foreach ( $result->hotel as $v ) {
			$hotels [] = $v;
		}
		
		return $hotels; // 温馨提示，返回的是 “SimpleXMLElement Object” 格式
	}
	public function update_web_order($inter_id, $list, $pms_set) {
		switch ($inter_id) {
			case 'a445223616' :
				return $this->update_web_order_suball ( $inter_id, $list, $pms_set );
				break;
			default :
				return $this->update_web_order_substatus ( $inter_id, $list, $pms_set );
				break;
		}
	}
	
	// <childOrderInfoExtend>
	// 30594#8406#陶良蛟#1#2016-03-01 09:29:48.0#2016-03-03 00:00:00.0#500.0@30595#8402#陶良蛟#1#2016-03-01
	// 09:29:48.0#2016-03-03 00:00:00.0#500.0
	// </childOrderInfoExtend>
	public function update_web_order_suball($inter_id, $list, $pms_set) {
		$web_orderid = isset ( $list ['web_orderid'] ) ? $list ['web_orderid'] : "";
		$new_status = null;
		if (! $web_orderid) {
			return null;
		}
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$result = $this->get_web_order ( $web_orderid, $pms_auth, $inter_id );
		if (isset ( $result ['code'] ) && $result ['code'] == 0) {
			$status_arr = $this->zhuzhe_enum ( 'sub_status' );
			if (! empty ( $result ['order'] ['childOrderInfoExtend'] )) {
				$sub_orders = explode ( '@', $result ['order'] ['childOrderInfoExtend'] );
				$sub_status = array ();
				$sub_orderid = array ();
				$first_status = NULL;
				$status_flag = 1;
				$status_count = 0;
				$end_status = array (
						3,
						4,
						5,
						8 
				);
				$handle_count = 0;
				foreach ( $sub_orders as $so ) {
					$sub_order = explode ( '#', $so );
					$sub_order [3] = $status_arr [$sub_order [3]];
					$sub_order [4] = date ( 'Ymd', strtotime ( $sub_order [4] ) );
					$sub_order [5] = date ( 'Ymd', strtotime ( $sub_order [5] ) );
					$sub_status [$sub_order [0]] = $sub_order;
					$sub_orderid [] = $sub_order [0];
					if (in_array ( $sub_status [$sub_order [0]] [3], $end_status )) {
						$handle_count ++;
					}
					if (is_null ( $first_status )) {
						$first_status = $sub_status [$sub_order [0]] [3];
					} else {
						if ($sub_status [$sub_order [0]] [3] != $first_status || date ( 'Ymd', strtotime ( $sub_status [$sub_order [0]] [5] ) ) != $list ['enddate'])
							$status_flag = 0;
						else
							$status_count ++;
					}
				}
				$this->load->model ( 'hotel/Order_model' );
				$i = 0;
				foreach ( $list ['order_details'] as $od ) {
					if (empty ( $od ['webs_orderid'] )) {
						$webs_orderid = $sub_orderid [$i];
						$i ++;
						$this->db->where ( array (
								'id' => $od ['sub_id'],
								'inter_id' => $inter_id,
								'orderid' => $list ['orderid'] 
						) );
						$this->db->update ( 'hotel_order_items', array (
								'webs_orderid' => $webs_orderid 
						) );
					} else {
						$webs_orderid = $od ['webs_orderid'];
					}
					// if ($status_flag == 0) {
					if ($od ['istatus'] == 4 && $sub_status [$webs_orderid] [3] == 5) {
						$sub_status [$webs_orderid] [3] = 4;
					}
					
					$web_start = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [4] ) );
					$web_end = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [5] ) );
					$web_end = $web_end == $web_start ? date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $web_start ) ) ) : $web_end;
					$ori_day_diff = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
					$web_day_diff = get_room_night($web_start,$web_end,'ceil');//至少有一个间夜
					$day_diff = $web_day_diff - $ori_day_diff;
					
					$updata = array ();
					if ($sub_status [$webs_orderid] [3] != $od ['istatus']) {
						$updata ['istatus'] = $sub_status [$webs_orderid] [3];
						$new_status = $sub_status [$webs_orderid] [3];
					}
					if ($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']) {
						// $updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [4] ) );
						// $updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [5] ) );
						// $updata ['new_price'] = floatval($sub_status [$webs_orderid] [6]);
						// @author lGh 2016-3-25 19:02:08 云盟返佣机制更改
						if ($day_diff < 0 && $list ['paid'] == 0) {
							$first_price = floatval ( $od ['allprice'] ); // 取首日价格
							$allprice = explode ( ',', $od ['allprice'] );
							$day_diff = abs ( $day_diff );
							$updata ['no_check_date'] = 1;
							$updata ['startdate'] = $web_start;
							$updata ['enddate'] = $web_end;
							$reduce_amount = 0;
							for($j = 0; $j < $day_diff; $j ++) {
								$tmp = array_pop ( $allprice );
								$reduce_amount += empty ( $tmp ) ? $first_price : $tmp;
							}
							$updata ['new_price'] = $od ['iprice'] - $reduce_amount;
						}
					}
					if (! empty ( $updata )) {
						$this->Order_model->update_order_item ( $inter_id, $list ['orderid'], $od ['sub_id'], $updata );
					}
					// }
				}
				
				// if ($status_flag == 1 && ! is_null ( $first_status ) && $status_count == count ( $list ['roomnums'] )) {
				// if ($list ['status'] == 4 && $first_status == 5) {
				// $first_status = 4;
				// }
				// if ($first_status != $list ['status']) {
				// $this->load->model ( 'hotel/Order_model' );
				// $this->db->where ( array (
				// 'inter_id' => $list ['inter_id'],
				// 'orderid' => $list ['orderid']
				// ) );
				// $this->db->update ( 'hotel_orders', array (
				// 'status' => $first_status
				// ) );
				// $this->Order_model->handle_order ( $inter_id, $list ['orderid'], $first_status, $list ['openid'] );
				// }
				// return $first_status;
				// }
			}
			
			if ($handle_count == $list ['roomnums']) {
				$this->db->where ( array (
						'inter_id' => $inter_id,
						'orderid' => $list ['orderid'] 
				) );
				$this->db->update ( self::TAB_HO, array (
						'handled' => 1 
				) );
			}
			
			/*
			 * $new_status = $status_arr [$result ['order'] ['status']];
			 * if ($list ['status'] == 4 && $new_status == 5) {
			 * $new_status = 4;
			 * }
			 * if ($new_status != $list ['status']) {
			 * $this->load->model ( 'hotel/Order_model' );
			 * $this->db->where ( array (
			 * 'inter_id' => $list ['inter_id'],
			 * 'orderid' => $list ['orderid']
			 * ) );
			 * $this->db->update ( 'hotel_orders', array (
			 * 'status' => $new_status
			 * ) );
			 * $this->Order_model->handle_order ( $inter_id, $list ['orderid'], $new_status, $list ['openid'] );
			 * }
			 */
		}
		return $new_status;
	}
	public function update_web_order_substatus($inter_id, $list, $pms_set) {
		$web_orderid = isset ( $list ['web_orderid'] ) ? $list ['web_orderid'] : "";
		$new_status = null;
		if (! $web_orderid) {
			return null;
		}
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$result = $this->get_web_order ( $web_orderid, $pms_auth, $inter_id );
		if (isset ( $result ['code'] ) && $result ['code'] == 0) {
			$status_arr = $this->zhuzhe_enum ( 'sub_status' );
			if (! empty ( $result ['order'] ['childOrderInfo'] )) {
				$sub_orders = explode ( '@', $result ['order'] ['childOrderInfo'] );
				$sub_status = array ();
				$sub_orderid = array ();
				$first_status = NULL;
				$status_flag = 1;
				$end_status = array (
						3,
						4,
						5,
						8 
				);
				$handle_count = 0;
				foreach ( $sub_orders as $so ) {
					$sub_order = explode ( '#', $so );
					$sub_status [$sub_order [0]] = $status_arr [$sub_order [3]];
					$sub_orderid [] = $sub_order [0];
					if (in_array ( $sub_status [$sub_order [0]], $end_status )) {
						$handle_count ++;
					}
					if (is_null ( $first_status )) {
						$first_status = $sub_status [$sub_order [0]];
					} else if ($sub_status [$sub_order [0]] != $first_status) {
						$status_flag = 0;
					}
				}
				$this->load->model ( 'hotel/Order_model' );
				$i = 0;
				foreach ( $list ['order_details'] as $od ) {
					if (empty ( $od ['webs_orderid'] )) {
						$webs_orderid = $sub_orderid [$i];
						$i ++;
						$this->db->where ( array (
								'id' => $od ['sub_id'],
								'inter_id' => $inter_id,
								'orderid' => $list ['orderid'] 
						) );
						$this->db->update ( 'hotel_order_items', array (
								'webs_orderid' => $webs_orderid 
						) );
					} else {
						$webs_orderid = $od ['webs_orderid'];
					}
					if ($status_flag == 0) {
						if ($od ['istatus'] == 4 && $sub_status [$webs_orderid] == 5) {
							$new_status = 4;
						}
						if ($sub_status [$webs_orderid] != $od ['istatus']) {
							$new_status = $sub_status [$webs_orderid];
							$this->Order_model->update_order_item ( $inter_id, $list ['orderid'], $od ['sub_id'], array (
									'enddate' => $od ['enddate'],
									'istatus' => $sub_status [$webs_orderid] 
							) );
						}
					}
				}
				
				if ($status_flag == 1 && ! is_null ( $first_status )) {
					if ($list ['status'] == 4 && $first_status == 5) {
						$first_status = 4;
					}
					if ($first_status != $list ['status']) {
						$this->load->model ( 'hotel/Order_model' );
						$this->db->where ( array (
								'inter_id' => $list ['inter_id'],
								'orderid' => $list ['orderid'] 
						) );
						$this->db->update ( 'hotel_orders', array (
								'status' => $first_status 
						) );
						$this->Order_model->handle_order ( $inter_id, $list ['orderid'], $first_status, $list ['openid'] );
					}
					return $first_status;
				}
			}
			
			if ($handle_count == $list ['roomnums']) {
				$this->db->where ( array (
						'inter_id' => $inter_id,
						'orderid' => $list ['orderid'] 
				) );
				$this->db->update ( self::TAB_HO, array (
						'handled' => 1 
				) );
			}
			
			/*
			 * $new_status = $status_arr [$result ['order'] ['status']];
			 * if ($list ['status'] == 4 && $new_status == 5) {
			 * $new_status = 4;
			 * }
			 * if ($new_status != $list ['status']) {
			 * $this->load->model ( 'hotel/Order_model' );
			 * $this->db->where ( array (
			 * 'inter_id' => $list ['inter_id'],
			 * 'orderid' => $list ['orderid']
			 * ) );
			 * $this->db->update ( 'hotel_orders', array (
			 * 'status' => $new_status
			 * ) );
			 * $this->Order_model->handle_order ( $inter_id, $list ['orderid'], $new_status, $list ['openid'] );
			 * }
			 */
		}
		return $new_status;
	}
	function get_web_order($web_orderid, $pms_auth, $inter_id) {
		$url = $pms_auth ['apipath'] . '/v2/order/info/' . $web_orderid . '/111/admin';
		$return_data = $this->qfcurl ( $url, '', $pms_auth ['token'], $inter_id );
		
		$result = simplexml_load_string ( $return_data );
		return json_decode ( json_encode ( $result ), TRUE );
	}
	function order_to_web($inter_id, $orderid, $paras = array(), $pms_set = array()) {
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $inter_id, array ( // 取订单信息，包含订单主单信息和子单信息
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $order )) {
			$order = $order [0]; // 订单信息
			$hotel_no = $pms_set ['hotel_web_id'];
			$room_codes = json_decode ( $order ['room_codes'], TRUE );
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']];
			/*
			 * 这里的 $room_codes 结构:
			 * Array
			 * (
			 * [code] => Array
			 * (
			 * [price_type] => pms
			 * [extra_info] => Array //取房态时填入的extra_info,根据pms的需要填入不同的字段
			 * (
			 * [type] => code
			 * [pms_code] => 网络注册会员价
			 * )
			 *
			 * )
			 *
			 * [room] => Array
			 * (
			 * [webser_id] => 8017 //pms上房型的id
			 * )
			 *
			 * )
			 */
			// 开始提交订单到pms
			// 提交代码
			// ////////////////////////////////////////////////////////订单保存成功后向API服务器提交信息
			$days = get_room_night($order ['startdate'],$order ['enddate'],'round',$order);//至少有一个间夜
			$price = number_format ( $order ['price'] / $days, 2,'.','' );
			$ensure_flag = 0;
			if (! empty ( $paras ['trans_no'] )) {
				$ensure_flag = 1;
			}
			$room_nos = '';
			foreach ( $order ['order_details'] as $od ) {
				$room_nos .= ',' . $od ['room_no'];
			}
			$room_nos = substr ( $room_nos, 1 );
			$enddate = date ( "Y-m-d 12:00", strtotime ( $order ['enddate'] ) );
			if ($order ['inter_id'] == 'a445223616') {
				$enddate = date ( "Y-m-d 13:00", strtotime ( $order ['enddate'] ) );
				$price = number_format ( $order ['price'] / ($days * $order ['roomnums']), 2 ,'.','');
			}
			$note = '微信订单。';
			$note .= $order ['coupon_favour'] > 0 ? '使用优惠券抵扣：' . $order ['coupon_favour'] . '。' : '';
			$in = array (
					'sdate' => date ( "Y-m-d H:i", strtotime ( $order ['startdate'] ) ),
					'edate' => $enddate,
					'hotelId' => $hotel_no,
					'hotelnm' => $order ['hname'],
					'houseTypeId' => $room_codes ['room'] ['webser_id'],
					'houseTypeName' => $order ['first_detail'] ['roomname'],
					'bookNum' => $order ['roomnums'],
					'rooms' => $room_nos,
					'price' => $price,
					'checkInPerson' => $order ['name'],
					'bookPerson' => $order ['name'],
					'bookTel' => $order ['tel'],
					'ensureFlag' => $ensure_flag,
					'guarantorType' => '',
					'email' => '',
					'note' => $note,
					'keepTime' => '18:00',
					'oid' => $orderid 
			);
			$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
			//夜宵房
			if (!empty($pms_auth['weehour'])&&$order['startdate']==date('Ymd',strtotime('-1 day'))){
				$in['weeHourRoom']=1;
				$in['sdate']=date('Y-m-d 00:00');
				$note.='凌晨订单。';
			}
// 			if ($order ['inter_id'] == 'a441624001' && !empty($order['member_no']) && strpos($order['member_no'], 'JFK')===FALSE) {
// 				$in['uid'] = $order['member_no'];
// 			}
			
			$zzdata = $this->qfcurl ( $pms_auth ['apipath'] . '/order/reserve', $in, $pms_auth ['token'], $inter_id );
			
			preg_match_all ( "/\<msg\>(.*?)\<\/msg\>/", $zzdata, $zzmsgarr );
			preg_match_all ( "/\<code\>(.*?)\<\/code\>/", $zzdata, $zzcodearr );
			preg_match_all ( "/\<oid\>(.*?)\<\/oid\>/", $zzdata, $zzoidarr );
			
			$zzmsg = empty ( $zzmsgarr [1] ) ? '下单失败，请联系酒店客服':$zzmsgarr [1] [0] ;
			$zzcode = empty ( $zzcodearr [1] ) ? '':$zzcodearr [1] [0] ;
			$zzoid = empty ( $zzoidarr [1] ) ? '':$zzoidarr [1] [0];
			
			// ////////////////////////////////////////////////////////向API服务器提交信息成功后往下继续进行
			
			// 提交结束
			if ($zzcode !=='' && $zzcode == 0) { // 提交成功，把订单数据保存到本地
				$web_orderid = $zzoid;
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_order_additions', array ( // 将pms的单号更新到相应订单
						'web_orderid' => $web_orderid 
				) );
				/*
				 * $this->db->where ( array ( //若pms有分单，则将分单号存入对应的子单
				 * 'orderid' => $order ['orderid'],
				 * 'inter_id' => $order ['inter_id'],
				 * 'id' => $order ['first_detail'] ['id']
				 * ) );
				 * $this->db->update ( 'hotel_order_items', array (
				 * 'webs_orderid' => $web_orderid
				 * ) );
				 */
				// $this->Order_model->update_order_status ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
				if ($order ['status'] != 9) {
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id'] 
					) );
					$this->db->update ( 'hotel_orders', array (
							'status' => 1 
					) );
					$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
				}else if ($order ['paytype'] == 'balance') {
					$this->load->model ( 'hotel/Hotel_config_model' );
					$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
							'PMS_BANCLANCE_REDUCE_WAY'
					) );
					if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
						$this->load->model ( 'hotel/Member_model' );
						$balance_param=array('crsNo'=>$web_orderid);
						if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'], '订房订单余额支付',$balance_param,$order)) {
							$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true ,true);
							$this->add_web_bill ( $web_orderid, $order, $pms_auth, $order['orderid'] );
						} else {
							$info = $this->Order_model->cancel_order ( $inter_id, array (
									'only_openid' => $order ['openid'],
									'member_no' => '',
									'orderid' => $order ['orderid'],
									'cancel_status' => 5,
									'no_tmpmsg' => 1,
									'delete' => 2,
									'idetail' => array (
											'i'
									)
							) );
							return array (
									's' => 0,
									'errmsg' => '储值支付失败'
							);
						}
					}
				}
				if (! empty ( $paras ['trans_no'] )) {
					$this->add_web_bill ( $web_orderid, $order, $pms_auth, $paras ['trans_no'] );
				}
				$web_order = $this->get_web_order ( $web_orderid, $pms_auth, $inter_id );
				if (! empty ( $web_order ['order'] ['childOrderInfo'] )) {
					$sub_orders = explode ( '@', $web_order ['order'] ['childOrderInfo'] );
					for($i = 0; $i < $order ['roomnums']; $i ++) {
						$sub_order = explode ( '#', $sub_orders [$i] );
						$this->db->where ( array (
								'id' => $order ['order_details'] [$i] ['sub_id'],
								'inter_id' => $inter_id,
								'orderid' => $order ['orderid'] 
						) );
						$this->db->update ( 'hotel_order_items', array (
								'webs_orderid' => $sub_order [0] 
						) );
					}
				}
				return array ( // 返回成功
						's' => 1 
				);
			} else {
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_orders', array ( // 提交失败，把订单状态改为下单失败
						'status' => 10 
				) );
				return array ( // 返回失败
						's' => 0,
						'errmsg' => $zzmsg 
				);
			}
		}
		return array (
				's' => 0,
				'errmsg' => '提交订单失败' 
		);
	}
	function add_web_bill($web_orderid, $order, $pms_auth, $trans_no = '') {
		$in = array (
				'oid' => $web_orderid,
				'tradeid' => $trans_no,
				'agent' => 'Wxpay',
				'paid' => $order ['price'] 
		);
		$web_paid = 2;
		$s = FALSE;
		$data = $this->qfcurl ( $pms_auth ['apipath'] . '/order/payment', $in, $pms_auth ['token'], $order ['inter_id'] );
		$result = simplexml_load_string ( $data );
		$result = json_decode ( json_encode ( $result ), TRUE );
		if (isset ( $result ['code'] ) && $result ['code'] == 0) {
			$web_paid = 1;
			$s = TRUE;
		}
		$this->db->where ( array (
				'orderid' => $order ['orderid'],
				'inter_id' => $order ['inter_id'] 
		) );
		$this->db->update ( 'hotel_order_additions', array ( // 将pms的单号更新到相应订单
				'web_paid' => $web_paid 
		) );
		return $s;
	}
	function get_room_realtime($inter_id, $hotel_id, $room_id, $date, $params = array()) {
		$hotel = $this->readDB()->get_where ( 'hotel_additions', array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id 
		) )->row_array ();
		$hotel_no = $hotel ['hotel_web_id'];
		$room = $this->readDB()->get_where ( 'hotel_rooms', array (
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'room_id' => $room_id 
		) )->row_array ();
		$room_no = $room ['webser_id'];
		$url = "http://webapi.zhuzher.com/kzf/$hotel_no/$room_no";
		$result = $this->qfcurl ( $url, '', 'jieting_83383_20160106_web:admin', $inter_id );
		$result = json_decode ( $result, true );
		$data = array ();
		$i = 1;
		if (isset ( $result ['code'] ) && $result ['code'] == 0) {
			$tmp = array ();
			foreach ( $result ['roomList'] as $r ) {
				$tmp ['room_no'] = $r ['roomNum'];
				$tmp ['des'] = $r ['desc'];
				$tmp ['num_id'] = $i;
				$data [] = $tmp;
				$i ++;
			}
		}
		return $data;
	}

	public function update_web_order_suball_fix($inter_id, $list, $pms_set) {
		$web_orderid = isset ( $list ['web_orderid'] ) ? $list ['web_orderid'] : "";
		$new_status = null;
		if (! $web_orderid) {
			return 4;
		}
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$result = $this->get_web_order ( $web_orderid, $pms_auth, $inter_id );
		if (isset ( $result ['code'] ) && $result ['code'] == 0) {
			$status_arr = $this->zhuzhe_enum ( 'sub_status' );
			if (! empty ( $result ['order'] ['childOrderInfoExtend'] )) {
				$sub_orders = explode ( '@', $result ['order'] ['childOrderInfoExtend'] );
				$sub_status = array ();
				$sub_orderid = array ();
				$first_status = NULL;
				$status_flag = 1;
				$status_count = 0;
				$end_status = array (
						3,
						4,
						5,
						8 
				);
				$handle_count = 0;
				foreach ( $sub_orders as $so ) {
					$sub_order = explode ( '#', $so );
					$sub_order [3] = $status_arr [$sub_order [3]];
					$sub_order [4] = date ( 'Ymd', strtotime ( $sub_order [4] ) );
					$sub_order [5] = date ( 'Ymd', strtotime ( $sub_order [5] ) );
					$sub_status [$sub_order [0]] = $sub_order;
					$sub_orderid [] = $sub_order [0];
					if (in_array ( $sub_status [$sub_order [0]] [3], $end_status )) {
						$handle_count ++;
					}
					if (is_null ( $first_status )) {
						$first_status = $sub_status [$sub_order [0]] [3];
					} else {
						if ($sub_status [$sub_order [0]] [3] != $first_status || date ( 'Ymd', strtotime ( $sub_status [$sub_order [3]] [6] ) ) != $list ['enddate'])
							$status_flag = 0;
						else
							$status_count ++;
					}
				}
				$this->load->model ( 'hotel/Order_model' );
				$i = 0;
				foreach ( $list ['order_details'] as $od ) {
					if (empty ( $od ['webs_orderid'] )) {
						$webs_orderid = $sub_orderid [$i];
						$i ++;
						$this->db->where ( array (
								'id' => $od ['sub_id'],
								'inter_id' => $inter_id,
								'orderid' => $list ['orderid'] 
						) );
						$this->db->update ( 'hotel_order_items', array (
								'webs_orderid' => $webs_orderid 
						) );
					} else {
						$webs_orderid = $od ['webs_orderid'];
					}
					// if ($status_flag == 0) {
					if ($od ['istatus'] == 4 && $sub_status [$webs_orderid] [3] == 5) {
						$sub_status [$webs_orderid] [3] = 4;
					}
					if ($sub_status [$webs_orderid] [3] != $od ['istatus'] || $sub_status [$webs_orderid] [5] != $list ['enddate']) { // 离店日期有更改
						$new_status = $sub_status [$webs_orderid] [3];
						$countday = ceil ( (strtotime ( date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [5] ) ) ) - strtotime ( $list ['enddate'] )) / 86400 );
						$updata = array ();
						
						if ($sub_status [$webs_orderid] [3] != $od ['istatus']) {
							$updata ['istatus'] = $sub_status [$webs_orderid] [3];
							// $updata ['new_price'] = floatval($sub_status [$webs_orderid] [6]);
						}
						if ($countday != 0) {
							$updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [4] ) );
							$updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [5] ) );
							
							$avg_price = floatval ( $od ['allprice'] ); // 取首日价格
							$ori_price = explode ( ',', $od ['allprice'] );
							$ori_price = array_sum ( $ori_price );
							$updata ['new_price'] = $ori_price + $countday * $avg_price; // 按微信价格重新计算新价格
								                                                             
							// $updata ['new_price'] = floatval($sub_status [$webs_orderid] [6]);//使用住哲返回的价格
								                                                             
							// @author lGh 2016-3-25 19:02:08 云盟返佣机制更改
								                                                             // if ($countday < 0 && $list ['paid'] == 0) {
								                                                             // $first_price = floatval ( $od ['allprice'] ); // 取首日价格
								                                                             // $allprice = explode ( ',', $od ['allprice'] );
								                                                             // $countday = abs ( $countday );
								                                                             // $updata ['no_check_date'] = 1;
								                                                             // $updata ['startdate'] = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [4] ) );
								                                                             // $updata ['enddate'] = date ( 'Ymd', strtotime ( $sub_status [$webs_orderid] [5] ) );
								                                                             // $reduce_amount = 0;
								                                                             // for($j = 0; $j < $countday; $j ++) {
								                                                             // $tmp = array_pop ( $allprice );
								                                                             // $reduce_amount += empty ( $tmp ) ? $first_price : $tmp;
								                                                             // }
								                                                             // $updata ['new_price'] = $od ['iprice'] - $reduce_amount;
								                                                             // }
						}
						if (! empty ( $updata )) {
							$this->Order_model->update_order_item ( $inter_id, $list ['orderid'], $od ['sub_id'], $updata );
							return 1;
						}
					}
					// }
				}
				return 2;
			}
		}
		return 3;
	}
	//判断订单是否能支付
	function check_order_canpay($list, $pms_set) {
		$web_orderid = isset ( $list ['web_orderid'] ) ? $list ['web_orderid'] : "";
		if (! $web_orderid) {
			return false;
		}
		if($list['inter_id'] == 'a445223616'){
			$index = 'childOrderInfoExtend';
		}else{
			$index = 'childOrderInfo';
		}
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$result = $this->get_web_order ( $web_orderid, $pms_auth, $list['inter_id'] );
		if (isset ( $result ['code'] ) && $result ['code'] == 0) {
			$status_arr = $this->zhuzhe_enum ( 'sub_status' );
			if (! empty ( $result ['order'] [$index] )) {
				$sub_orders = explode ( '@', $result ['order'] [$index] );
				foreach ( $sub_orders as $so ) {
					$sub_order = explode ( '#', $so );
					$istatus = $status_arr [$sub_order [3]];
					if($istatus != 1 && $istatus != 0){
						return false;
					}
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
		return true;
	}

	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
}