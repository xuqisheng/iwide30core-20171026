<?php

class Xiruan3_hotel_model extends MY_Model{
	private $serv_api;
	
	public function __construct(){
		parent::__construct();
		$this->serv_api = new Xiruanapi3();
	}
	
	public function get_rooms_change($rooms, $idents, $condit, $pms_set = []){
		statistic('A1');
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], [
			'web_price_code_set',
//		    'pkg_data'
		], 1, 'w2l');
		
		$web_price_code = '';
		
		$this->load->model('api/Vmember_model', 'vm');
		$member_level = $this->vm->getLvlPmsCode($condit['openid'], $idents['inter_id']);
		
		if(!empty ($condit ['price_codes'])){
			$web_price_code = $condit ['price_codes'];
			
			//对接模式是本地价格代码时，读取对应的external_code值【PMS价格代码】
			if($pms_set['pms_room_state_way'] == 3 || $pms_set['pms_room_state_way'] == 4){
				$web_code_arr = [];
				$price_code_list = $this->readDB()->from('hotel_price_info')->select('external_code')->where(['inter_id' => $pms_set['inter_id']])->where_in('price_code', explode(',', $condit['price_codes']))->get()->result_array();
				foreach($price_code_list as $v){
					$web_code_arr[] = $v['external_code'];
				}
				if($web_code_arr){
					$web_price_code = implode(',', $web_code_arr);
				}
			}
		}else{
			if(!empty ($web_reflect ['web_price_code'])){
				foreach($web_reflect ['web_price_code'] as $wpc){
					$web_price_code .= ',' . $wpc;
				}
			}
			$web_price_code .= isset ($web_reflect ['member_price_code'] [$member_level]) ? ',' . $web_reflect ['member_price_code'] [$member_level] : '';
			$web_price_code = substr($web_price_code, 1);
		}
		
		$web_price_code = explode(',', $web_price_code);
		$countday = get_room_night($condit['startdate'], $condit['enddate'], 'ceil');
		$web_rids = [];
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		
		statistic('A2');
		$params = [
			'countday'    => $countday,
			'web_rids'    => $web_rids,
			'condit'      => $condit,
			'web_reflect' => $web_reflect,
			'idents'      => $idents,
		];
		
		/*if($pms_set['pms_room_state_way'] == 3 || $pms_set['pms_room_state_way'] == 4){
			$this->load->model('hotel/Order_model');
			$local_data = $this->Order_model->get_rooms_change($rooms, $idents, $condit);
			$web_price_code = [];
			foreach($local_data as $k => $v){
				if(!empty($v['state_info'])){
					foreach($v['state_info'] as $t){
						$web_price_code[] = $t['external_code'];
					}
				}
			}
		}
		$web_price_code = array_unique($web_price_code);*/
		statistic('A3');
		
		$pms_data = $this->get_web_roomtype($pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params);
		statistic('A4');
		$data = [];
		if(!empty ($pms_data)){
			switch($pms_set ['pms_room_state_way']){
				case 1 :
				case 2 :
					$data = $this->get_rooms_change_allpms($pms_data, [
						'rooms' => $rooms
					], $params);
					break;
				case 4:
					$data = $this->get_rooms_change_ratecode($pms_data, [
						'rooms' => $rooms
					], $params);
					break;
			}
		}
		statistic('A5');
		
		$a_b = statistic('A1', 'A2');//查询本地配置
		$b_c = statistic('A2', 'A3');//请求本地房态
		$c_d = statistic('A3', 'A4');//请求PMS实时房态
		$d_e = statistic('A4', 'A5');//与本地房型匹配
		$a_e = statistic('A1', 'A5');//获取房型
		$timer_arr = [
			'查询本地配置'    => $a_b . '秒',
			'与本地房型匹配'   => $b_c . '秒',
			'请求PMS实时房态' => $c_d . '秒',
			'与本地房型匹配'   => $d_e . '秒',
			'获取房型房态总耗时' => $a_e . '秒',
			'执行时间'      => date('Y-m-d H:i:s'),
		];
		pms_logger(func_get_args(), $timer_arr, __METHOD__ . '->query_time', $pms_set['inter_id']);
		return $data;
	}
	
	public function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params){
		
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		$web_room_arr = array_keys($params['web_rids']);
		
		$countday = $params['countday'];
		//下单时，获取实时数据，或离店日期为一个月后的
		//每日房价
		$web_room_rate = [];
		$func_data = ['hotel_id' => $params['idents']['hotel_id']];
		
		/*if($this->uri->segment(3) == 'saveorder'/*||$this->uri->segment(3)=='bookroom'/){
			$web_room_req=$web_room_arr[0];
			$web_rate_req=$web_price_code[0];
			
			$result=$this->serv_api->getRoomPriceByRateCode($pms_set['hotel_web_id'],$startdate, $enddate, $web_room_req, $web_rate_req);
			if($result){
				$row=$result['daily'][0];
				$web_room_rate[$row['RmType']]['name']=$row['RmType'];
				$web_room_rate[$row['RmType']]['code']=$row['RmType'];
				$web_room_rate[$row['RmType']]['rates'][$row['WebRateCode']]=[
					'code'         => $row['WebRateCode'],
					'name'         => $row['WebRateCode'],
					'packages'     => isset($row['Packages'])?$row['Packages']:'',
					'cancel_rule'  => isset($row['CancelRule'])?$row['CancelRule']:'',
					'deposit_rule' => isset($row['DepositRule'])?$row['DepositRule']:'',
					'cancel_days'  => isset($row['CancelDays'])?$row['CancelDays']:'',
				];
				foreach($result['daily'] as $t){
					list($in_date, $in_time) = explode('T', $t['Date']);
					$web_room_rate[$row['RmType']]['rates'][$t['WebRateCode']]['daily'][] = [
						'in_date'  => date('Ymd', strtotime($in_date)),
						'price'    => round($t['Rate'], 2),
						'quantity' => $result['avail'],
					];
				}
				
			}
		}else*/if(strtotime($enddate) > time() + 86400 * 30 || !empty($this->input->get('realtime'))){
			/*$request_room_arr=[];
			if(count($web_room_arr)==1){
				$request_room_arr=$web_room_arr;
			}
			$request_price_arr=[];
			
			if(count($web_room_arr)==1&&count($web_price_code)==1){
				$web_room_req=$web_room_arr[0];
				$web_rate_req=$web_price_code[0];
				
				$result=$this->serv_api->getRoomPriceByRateCode($pms_set['hotel_web_id'],$startdate, $enddate, $web_room_req, $web_rate_req);
				if($result){
					$row=$result['daily'][0];
					$web_room_rate[$row['RmType']]['name']=$row['RmType'];
					$web_room_rate[$row['RmType']]['code']=$row['RmType'];
					$web_room_rate[$row['RmType']]['rates'][$row['WebRateCode']] = [
						'code'         => $row['WebRateCode'],
						'name'         => $row['WebRateCode'],
						'packages'     => isset($row['Packages']) ? $row['Packages'] : '',
						'pkg_des'      => isset($row['PkgDes'])?$row['PkgDes']:'',
						'cancel_rule'  => isset($row['CancelRule']) ? $row['CancelRule'] : '',
						'deposit_rule' => isset($row['DepositRule']) ? $row['DepositRule'] : '',
						'cancel_days'  => isset($row['CancelDays']) ? $row['CancelDays'] : '',
					];
					
					foreach($result['daily'] as $t){
						list($in_date, $in_time) = explode('T', $t['Date']);
						$web_room_rate[$row['RmType']]['rates'][$t['WebRateCode']]['daily'][] = [
							'in_date'  => date('Ymd', strtotime($in_date)),
							'price'    => round($t['Rate'], 2),
							'quantity' => $result['avail'],
						];
					}
					
					
				}
			}else{
				$daily_result = $this->serv_api->getRoomState($pms_set['hotel_web_id'], $startdate, $enddate, $request_room_arr, $request_price_arr, $func_data);
				if(!empty($daily_result)){
					is_array(current($daily_result)) or $daily_result = [$daily_result];
					
					foreach($daily_result as $v){
						$web_room_rate[$v['RoomType']]['name'] = $v['RoomDesc'];
						$web_room_rate[$v['RoomType']]['code'] = $v['RoomType'];
						$web_room_rate[$v['RoomType']]['rates'] = [];
						if(!empty($v['RateCodeList']['RateCodeInfo'])){
							is_array(current($v['RateCodeList']['RateCodeInfo'])) or $v['RateCodeList']['RateCodeInfo'] = [$v['RateCodeList']['RateCodeInfo']];
							foreach($v['RateCodeList']['RateCodeInfo'] as $t){
								$web_room_rate[$v['RoomType']]['rates'][$t['WebRateCode']] = [
									'code'         => $t['WebRateCode'],
									'name'         => $t['RateCodeDesc'],
									'packages'     => isset($t['Packages'])?$t['Packages']:'',
									'pkg_des'      => isset($t['PkgDes'])?$t['PkgDes']:'',
									'cancel_rule'  => isset($t['CancelRule'])?$t['CancelRule']:'',
									'deposit_rule' => isset($t['DepositRule'])?$t['DepositRule']:'',
									'cancel_days'  => isset($t['CancelDays'])?$t['CancelDays']:'',
								];
								
							}
							if(!empty($v['RateList']['RateInfo'])){
								is_array(current($v['RateList']['RateInfo'])) or $v['RateList']['RateInfo'] = [$v['RateList']['RateInfo']];
								foreach($v['RateList']['RateInfo'] as $t){
									list($in_date, $in_time) = explode('T', $t['Date']);
									
									$web_room_rate[$v['RoomType']]['rates'][$t['WebRateCode']]['daily'][] = [
										'in_date'  => date('Ymd', strtotime($in_date)),
										'price'    => round($t['Rate'], 2),
										'quantity' => $v['Avail'],
									];
								}
							}
						}
					}
				}
			}*/
		}else{
			//非下单请求时，优先读取redis
			$this->load->helper('common');
			$this->load->library('Cache/Redis_proxy', array(
				'not_init'    => FALSE,
				'module'      => 'common',
				'refresh'     => FALSE,
				'environment' => ENVIRONMENT
			), 'redis_proxy');
			$redis = $this->redis_proxy;
			
			//判断本地缓存中每日都有数据
			$all_exists = true;
			$rk_temp = $pms_set['inter_id'] . ':price_lite:' . $params['idents']['hotel_id'] . ':';
			$sdate = date('Ymd', strtotime($startdate));
			$edate = date('Ymd', strtotime($enddate));
			
			for($start = $sdate; $start < $edate;){
				$rk = $rk_temp . $start;
				if(!$redis->exists($rk)){
					$all_exists = false;
					break;
				}
				$start = date('Ymd', strtotime($start) + 86400);
			}
			
			if(!$all_exists || !empty($params['condit']['recache'])){
				for($start = $sdate; $start < $edate;){
					$rk = $rk_temp . $start;
					//删除缓存数据，防止接口没有数据返回时，本地仍有缓存
					$redis->del($rk);
					$start = date('Ymd', strtotime($start) + 86400);
				}
				//不是所有数据都有缓存时，重新请求缓存接口，
				$cache_result = $this->serv_api->getRoomStateByCache($pms_set['hotel_web_id'], $sdate, $edate, [], [], $func_data);
				is_array(current($cache_result)) or $cache_result = [$cache_result];
				$cache_data = [];
				$cache_rate_list = [];
				foreach($cache_result as $v){
					if(!empty($v['RateCodeList']['RateCodeInfo'])){
						is_array(current($v['RateCodeList']['RateCodeInfo'])) or $v['RateCodeList']['RateCodeInfo'] = [$v['RateCodeList']['RateCodeInfo']];
						foreach($v['RateCodeList']['RateCodeInfo'] as $t){
							$cache_rate_list[$v['RoomType']][$t['WebRateCode']] = [
								'code'         => $t['WebRateCode'],
								'name'         => $t['RateCodeDesc'],
								'packages'     => isset($t['Packages'])?$t['Packages']:'',
								'pkg_des'      => isset($t['PkgDes'])?$t['PkgDes']:'',
								'cancel_rule'  => isset($t['CancelRule'])?$t['CancelRule']:'',
								'deposit_rule' => isset($t['DepositRule'])?$t['DepositRule']:'',
								'cancel_days'  => isset($t['CancelDays'])?$t['CancelDays']:'',
							];
						}
						if(!empty($v['RateList']['RateInfo'])){
							is_array(current($v['RateList']['RateInfo'])) or $v['RateList']['RateInfo'] = [$v['RateList']['RateInfo']];
							foreach($v['RateList']['RateInfo'] as $t){
								list($in_date, $in_time) = explode('T', $t['Date']);
								//以日期作为KEY值，将每个房型的价格存放进去
								$daily_list = [
									'price'    => round($t['Rate'], 2),
									'quantity' => $v['Avail'],
								];
								$cache_data[date('Ymd', strtotime($in_date))][$v['RoomType']]['name'] = $v['RoomDesc'];
								$cache_data[date('Ymd', strtotime($in_date))][$v['RoomType']]['rates'][$t['WebRateCode']] = [
									'rate'  => $cache_rate_list[$v['RoomType']][$t['WebRateCode']],
									'daily' => $daily_list,
								];
							}
						}
					}
				}
				
				//设置本地缓存
				foreach($cache_data as $k => $v){
					$rk = $rk_temp . $k;
//					$redis->hSet($rk, $pms_set['hotel_web_id'], json_encode($v));
//					statistic('W1');
					/*foreach($v as $wr=>$t){
						$redis->hSet($rk, $wr, json_encode($t));
					}*/
//					statistic('W2');
//					echo statistic('W1','W2').'<br />';
					
					//保存到redis的数据,将数组的值转为JSON，避免多次循环
//					statistic('T1');
					$redis_data = array_map('json_encode', $v);
					$redis->hMset($rk, $redis_data);
//					statistic('T2');
//					exit(statistic('T1','T2'));
					
					//记录当前KEY获取缓存的数量
					pms_logger([
						$rk,
						$_SERVER['REQUEST_URI']
					], $v, __METHOD__ . '->set_redis', $pms_set['inter_id']);
					$redis->expireAt($rk, strtotime($k) + 86400);
					
					//组合
					foreach($v as $web_room => $_row){
						//每个房型的数据
						$web_room_rate[$web_room]['name'] = $_row['name'];
						$web_room_rate[$web_room]['code'] = $web_room;
						if(!empty($_row['rates'])){
							foreach($_row['rates'] as $web_rate => $t){
								if(empty($web_room_rate[$web_room]['rates'][$web_rate])){
									$web_room_rate[$web_room]['rates'][$web_rate] = $t['rate'];
									
								}
								//每日价格记录
								$daily_rec = $t['daily'];
								$daily_rec['in_date'] = $k;
								$web_room_rate[$web_room]['rates'][$web_rate]['daily'][] = $daily_rec;
							}
						}
					}
				}
			}else{
				
				//读取本地缓存数据--开始
//			statistic('A');
				for($start = $sdate; $start < $edate;){
					$rk = $rk_temp . $start;
//				statistic('AA');
					//读取当日的本地缓存数据
					/*$json = $redis->hGet($rk, $pms_set['hotel_web_id']);
	//				statistic('BB');
					//记录当前KEY获取缓存的数量
					pms_logger([
						$rk,
						$pms_set['hotel_web_id'],
						$_SERVER['REQUEST_URI']
					], $json, __METHOD__ . '->get_redis', $pms_set['inter_id']);
					$res = json_decode($json, true);
	//				echo (statistic('AA','BB'))."<br>";
					if(is_array($res) && !empty($res)){
						foreach($res as $web_room => $v){
							$web_room_rate[$web_room]['name'] = $v['name'];
							$web_room_rate[$web_room]['code'] = $web_room;
							
							//循环价格代码情况
							if(!empty($v['rates'])){
								foreach($v['rates'] as $web_rate => $t){
									if(empty($web_room_rate[$web_room]['rates'][$web_rate])){
										$web_room_rate[$web_room]['rates'][$web_rate] = $t['rate'];
									}
									//每日价格记录
									$daily_rec = $t['daily'];
									$daily_rec['in_date'] = $start;
									$web_room_rate[$web_room]['rates'][$web_rate]['daily'][] = $daily_rec;
								}
							}
						}
					}*/
					
					$redis_data = $redis->hGetAll($rk);
					pms_logger([
						$rk,
						$_SERVER['REQUEST_URI']
					], $redis_data, __METHOD__ . '->get_redis', $pms_set['inter_id']);
					if($redis_data){
						foreach($redis_data as $web_room => $v){
							//每个房型的数据
							$_row = json_decode($v, true);
							$web_room_rate[$web_room]['name'] = $_row['name'];
							$web_room_rate[$web_room]['code'] = $web_room;
							if(!empty($_row['rates'])){
								foreach($_row['rates'] as $web_rate => $t){
									if(empty($web_room_rate[$web_room]['rates'][$web_rate])){
										$web_room_rate[$web_room]['rates'][$web_rate] = $t['rate'];
										
									}
									//每日价格记录
									$daily_rec = $t['daily'];
									$daily_rec['in_date'] = $start;
									$web_room_rate[$web_room]['rates'][$web_rate]['daily'][] = $daily_rec;
								}
							}
						}
					}
					
					$start = date('Ymd', strtotime($start) + 86400);
				}
//			statistic('B');
//			echo statistic('A','B');
				
				//读取本地缓存数据--结束
			}
		}
		
		$pms_state = [];
		$valid_state = [];
		$exprice = [];
		
		/*$pkg_key='pkg_detail:'.$pms_set['inter_id'];
		$pkg_json=$redis->hGet($pkg_key,$pms_set['hotel_web_id']);
		$pkg_arr=json_decode($pkg_json,true);
		is_array($pkg_arr) or $pkg_arr=[];*/
		
//		$pkg_data = isset($params['web_reflect']['pkg_data'])?$params['web_reflect']['pkg_data']:[];
		
		if($web_room_rate){
			foreach($web_room_rate as $web_room => $v){
				if(!array_key_exists($web_room, $params['web_rids'])){
					continue;
				}
				$pms_state[$web_room] = [];
				foreach($v['rates'] as $web_rate => $t){
					
					$pms_state[$web_room][$web_rate]['price_name'] = $t['name'];
					$pms_state[$web_room][$web_rate]['price_type'] = 'pms';
					$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
					$pms_state[$web_room][$web_rate]['extra_info'] = [
						'type'         => 'code',
						'pms_code'     => $web_rate,
						'cancel_rule'  => $t['cancel_rule'],
						'deposit_rule' => $t['deposit_rule'],
						'cancel_days'  => $t['cancel_days'],
					];
					/*$pkg_list=explode(',',$t['packages']);
					$pkg_des=[];
					foreach($pkg_list as $w){
						if(isset($pkg_data[$w])){
							$pkg_des[]=$pkg_data[$w];
						}
					}*/
					
					$pms_state[$web_room][$web_rate]['des'] = $t['pkg_des'];//implode('。',$pkg_des);
					$pms_state[$web_room][$web_rate]['sort'] = 0;
					$pms_state[$web_room][$web_rate]['disp_type'] = 'buy';
					
					$web_set = [];
					if(isset ($params['web_reflect']['web_price_code_set'][$web_rate])){
						$web_set = json_decode($params['web_reflect']['web_price_code_set'][$web_rate], true);
					}
					
					$pms_state[$web_room][$web_rate]['condition'] = $web_set;
					
					if(isset($params['web_rids'][$web_room]) && isset($params['condit']['nums'][$params['web_rids'][$web_room]])){
						$nums = $params['condit']['nums'][$params['web_rids'][$web_room]];
					}else{
						$nums = 1;
					}
					
					$allprice = [];
					$amount = 0;
					
					$least_arr = [3];
					
					$date_status = true;
					
					foreach($t['daily'] as $w){
						if($w['in_date'] < date('Ymd', strtotime($enddate))){
							
							$pms_state[$web_room][$web_rate]['date_detail'][$w['in_date']] = [
								'price' => $w['price'],
								'nums'  => $w['price'] > 0 ? $w['quantity'] : 0,
							];
							
							$allprice[$w['in_date']] = $w['price'];
							$amount += $w['price'];
							$least_arr[] = $w['quantity'];
							
							$date_status = $date_status && $w['quantity'] > 0 && $w['price'] > 0;
						}
					}
					
					//校验日期价格
					$all_exists = true;
					for($start = date('Ymd', strtotime($startdate)); $start < date('Ymd', strtotime($enddate));){
						if(empty($pms_state[$web_room][$web_rate]['date_detail'][$start])){
							$all_exists = false;
							break;
						}
						$start = date('Ymd', strtotime($start) + 86400);
					}
					
					//是否所有日期都直接价格代码
					if(!$all_exists){
						unset($pms_state[$web_room][$web_rate]);
						continue;
					}
					
					ksort($allprice);
					$least_count = min($least_arr);
					$least_count > 0 or $least_count = 0;
					
					$pms_state[$web_room][$web_rate]['allprice'] = implode(',', $allprice);
					$pms_state[$web_room][$web_rate]['total'] = $amount;
					$pms_state[$web_room][$web_rate]['related_des'] = '';
					$pms_state[$web_room][$web_rate]['total_price'] = $amount * $nums;
					
					$pms_state[$web_room][$web_rate]['avg_price'] = number_format($amount / $params ['countday'], 2, '.', '');
					$pms_state[$web_room][$web_rate]['price_resource'] = 'webservice';
					
					
					$book_status = 'full';
					if($date_status){
						$book_status = 'available';
					}
					
					$pms_state[$web_room][$web_rate]['book_status'] = $book_status;
					$exprice [$web_room][] = $pms_state[$web_room][$web_rate]['avg_price'];
					
					$pms_state[$web_room][$web_rate]['least_num'] = $least_count;
					$valid_state[$web_room][$web_rate] = $pms_state[$web_room][$web_rate];
					
				}
			}
		}
		
		return [
			'pms_state'   => $pms_state,
			'valid_state' => $valid_state,
			'exprice'     => $exprice,
		];
	}
	
	
	public function get_rooms_change_ratecode($pms_data, $rooms, $params){
		$local_rooms = $rooms ['rooms'];
		$this->load->model('hotel/Order_model');
		$data = $this->Order_model->get_rooms_change($local_rooms, $params['idents'], $params['condit']);
		$condit = $params ['condit'];
		
		$pms_state = $pms_data ['pms_state'];
		$valid_state = $pms_data ['valid_state'];
		//		echo '<pre>';print_r($pms_state);exit;
		$merge = [
			'price_name',
			'des',
			'least_num',
			'book_status',
			'extra_info',
			'date_detail',
			//			'avg_price',
			//			'allprice',
			//			'total',
			//			'total_price',
		];
		
		foreach($data as $room_key => $lrm){
			$min_price = [];
			if(empty ($valid_state[$lrm['room_info']['webser_id']])){
				unset ($data[$room_key]);
				continue;
			}
			
			$nums = isset ($condit['nums'][$lrm['room_info']['room_id']]) ? $condit['nums'][$lrm['room_info']['room_id']] : 1;
			
			if(!empty($lrm['state_info'])){
				
				foreach($lrm['state_info'] as $sik => $si){
					
					//需要设置PMS价格代码值
					$web_rate = $si['external_code'];
					
					if($web_rate === '' || empty($pms_state[$lrm['room_info']['webser_id']][$web_rate])){//PMS上不存在该价格代码
						unset($data[$room_key]['state_info'][$sik]);
						continue;
					}
					
					//PMS上的房态数据
					$tmp = $pms_state[$lrm['room_info']['webser_id']][$web_rate];
					foreach($merge as $w){
						if(isset($tmp[$w])){
							
							if($w == 'date_detail'){
								$allprice = '';
								$amount = 0;
								foreach($tmp[$w] as $dk => $td){
									if($si['related_cal_way'] && $si['related_cal_value']){
										$tmp[$w][$dk]['price'] = round($this->Order_model->cal_related_price($td['price'], $si['related_cal_way'], $si['related_cal_value'], 'price'));
									}else{
										$tmp[$w][$dk]['price'] = $td['price'];
									}
//									$tmp[$w][$dk]['nums'] = $tmp['least_num'];
									$allprice .= ',' . $tmp[$w][$dk]['price'];
									$amount += $tmp[$w][$dk]['price'];
								}
								
								$data[$room_key]['state_info'][$sik]['avg_price'] = number_format($amount / $params['countday'], 0);
								$data[$room_key]['state_info'][$sik]['allprice'] = substr($allprice, 1);
								$data[$room_key]['state_info'][$sik]['total'] = intval($amount);
								$data[$room_key]['state_info'][$sik]['total_price'] = $data[$room_key]['state_info'][$sik]['total'] * $nums;
							}
							$data[$room_key]['state_info'][$sik][$w] = $tmp[$w];
						}
					}
					$avg_price = str_replace(',', '', $data[$room_key]['state_info'][$sik]['avg_price']);;
					if($avg_price > 0)
						$min_price[] = $avg_price;
					//					}
				}
			}
			$data[$room_key]['lowest'] = empty($min_price) ? 0 : min($min_price);
			$data[$room_key]['highest'] = empty($min_price) ? 0 : max($min_price);
			/*if(empty($lrm['show_info'])){
				$lrm['show_info'] = $lrm['state_info'];
				$data[$room_key]['show_info'] = $lrm['state_info'];
			}*/
			if(!empty($lrm['show_info'])){
				foreach($lrm['show_info'] as $sik => $si){
					//需要设置PMS价格代码值
					$web_rate = $si['external_code'];
					if($web_rate === '' || empty($pms_state[$lrm['room_info']['webser_id']][$web_rate])){//PMS上不存在该价格代码
						//					echo '<pre>';print_r($pms_state[$lrm['room_info']['webser_id']]);print_r($lrm);exit;
						unset($data[$room_key]['show_info'][$sik]);
						continue;
					}
					
					//PMS上的房态数据
					$tmp = $pms_state[$lrm['room_info']['webser_id']][$web_rate];
					foreach($merge as $w){
						if(isset($tmp[$w])){
							
							if($w == 'date_detail'){
								$allprice = '';
								$amount = 0;
								foreach($tmp[$w] as $dk => $td){
									if($si['related_cal_way'] && $si['related_cal_value']){
										$tmp[$w][$dk]['price'] = round($this->Order_model->cal_related_price($td['price'], $si['related_cal_way'], $si['related_cal_value'], 'price'));
									}else{
										$tmp[$w][$dk]['price'] = $td['price'];
									}
									$tmp[$w][$dk]['nums'] = $tmp['least_num'];
									$allprice .= ',' . $tmp[$w][$dk]['price'];
									$amount += $tmp[$w][$dk]['price'];
								}
								
								$data[$room_key]['show_info'][$sik]['avg_price'] = number_format($amount / $params['countday'], 0);
								$data[$room_key]['show_info'][$sik]['allprice'] = substr($allprice, 1);
								$data[$room_key]['show_info'][$sik]['total'] = intval($amount);
								$data[$room_key]['show_info'][$sik]['total_price'] = $data[$room_key]['show_info'][$sik]['total'] * $nums;
							}
						}
						
						$data[$room_key]['show_info'][$sik][$w] = $tmp[$w];
					}
				}
			}
			if(empty($data[$room_key]['state_info'])){
				unset($data[$room_key]);
			}
		}
		return $data;
	}
	
	private function get_rooms_change_allpms($pms_state, $rooms, $params){
		$data = [];
		foreach($rooms ['rooms'] as $rm){
			if(!empty ($pms_state ['pms_state'] [$rm ['webser_id']])){
				$data [$rm ['room_id']] ['room_info'] = $rm;
				$data [$rm ['room_id']] ['state_info'] = empty ($pms_state ['valid_state'] [$rm ['webser_id']]) ? [] : $pms_state ['valid_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['show_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['lowest'] = min($pms_state ['exprice'] [$rm ['webser_id']]);
				$data [$rm ['room_id']] ['highest'] = max($pms_state ['exprice'] [$rm ['webser_id']]);
			}
		}
		
		return $data;
	}
	
	public function order_to_web($inter_id, $orderid, $params = [], $pms_set = []){
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$this->load->model('hotel/Order_model');
		$order = $this->Order_model->get_main_order($inter_id, [
			'orderid' => $orderid,
			'idetail' => [
				'i'
			]
		]);
		if(!empty ($order)){
			$order = $order [0];   //获取本地已保存的订单信息
			$room_codes = json_decode($order ['room_codes'], true);
			$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
			//			$pms_set ['pms_auth'] = json_decode($pms_set ['pms_auth'], TRUE);
			//kdkjlksjijkflija diljijkjosjklj
			
			/*
				构造要提交的数据
			*/
			
			$result = $this->order_reserve($order, $pms_set, $params);//提交订单
			
			if($result['result']){
				$web_orderid = $result['web_orderid'];            //取得返回的pms订单id
				
				
				$this->db->where([
					'orderid'  => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				]);
				$this->db->update('hotel_order_additions', [        //更新pms单号到本地
				                                                    'web_orderid' => $web_orderid
				]);
				
				$upstatus=null;
				$has_paid=null;
				if($order['status'] != 9){
					$upstatus=1;
//					$this->Order_model->update_order_status($inter_id,$orderid,1,$order['openid']);
				}
				
				$sub_count = count($order['order_details']);
				for($i = 0; $i < $sub_count; $i++){
					if(!empty($result['accnt'][$i])){
						$sub = $order['order_details'][$i];
						$this->db->where(['id' => $sub['id']])->update('hotel_order_items', ['webs_orderid' => $result['accnt'][$i]]);
					}
				}
				
				$config_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $order ['hotel_id'], array(
					'PMS_BONUS_COMSUME_WAY',
					'PMS_POINT_REDUCE_WAY',
				    'POINT_PAY_WITH_BILL'
				));
				
				$after_reduce=false;
				$credit=0;
				if($order['point_used_amount']>0){
					if($order['paytype']=='point'&&!empty($config_data['PMS_POINT_REDUCE_WAY']) && $config_data['PMS_POINT_REDUCE_WAY'] == 'after'){
						$after_reduce=true;
						$credit=$order['price'];
					}elseif($order['paytype']!='point'&&!empty($config_data['PMS_BONUS_COMSUME_WAY']) && $config_data['PMS_BONUS_COMSUME_WAY'] == 'after'){
						$after_reduce=true;
						$credit=$order['point_favour'];
					}
				}
				
				if($after_reduce){
					$this->load->model('hotel/Member_model');
					$bonus_params = [
						'crsNo' => $web_orderid,
					    'timeout'=>99,
					];
					
					if(!empty($config_data['POINT_PAY_WITH_BILL'])){
						$bonus_params['extra']=[
							'Channel'=>$pms_auth['Channel'],
							'ResNo'       => $web_orderid,
							'Source'      => 'N',
							'PreAuthID'   => $order['orderid'],
							'PreAuthType' => 'F',
							'Credit'      => $credit,
							'Payment'     => 0,
							'Pccode'      => $pms_auth['pccode']['bonus']
						];
						$bonus_params['extra']['point_pay_with_bill']=1;
					}
					
					$res=$this->Member_model->consum_point($order['inter_id'], $order['orderid'], $order['openid'], $order['point_used_amount'],$bonus_params);
					if(!$res){
						$this->Order_model->update_point_reduce($inter_id, $order['orderid'], 3);
						$info = $this->Order_model->cancel_order($inter_id, array(
							'only_openid'   => $order['openid'],
							'member_no'     => '',
							'orderid'       => $order ['orderid'],
							'cancel_status' => 5,
							'no_tmpmsg'     => 1,
							'delete'        => 2,
							'idetail'       => array(
								'i'
							)
						));
						return array(
							's'      => 0,
							'errmsg' => '积分扣减失败'
						);
					}else{
						if($order['paytype']=='point'){
							$has_paid=1;
//							$this->Order_model->update_order_status($inter_id,$orderid,1,$order['openid'],true);
						}
					}
				}
				
				if(!empty ($params ['third_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
					$this->add_web_bill($web_orderid, $order, $pms_set, $params ['third_no']);
				}
				return [ // 返回成功
				         's' => 1,
				         'has_paid'=>$has_paid,
				         'upstatus'=>$upstatus
				];
			}else{
				$this->change_order_status($inter_id, $orderid, 10);
				return [ // 返回失败
				         's'      => 0,
				         'errmsg' => '提交订单失败' . ',' . $result ['errmsg']
				];
			}
		}
		return [
			's'      => 0,
			'errmsg' => '提交订单失败'
		];
	}
	private function change_order_status($inter_id, $orderid, $status){
		$this->db->where([
			'orderid'  => $orderid,
			'inter_id' => $inter_id
		]);
		$this->db->update('hotel_orders', [ // 提交失败，把订单状态改为下单失败
		                                    'status' => (int)$status
		]);
	}
	
	function order_reserve($order, $pms_set, $params = []){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		$starttime = strtotime($order['startdate']);
		$endtime = strtotime($order['enddate']);
		
		$startdate = date('Y-m-d', $starttime);// . 'T12:00:00';
		$enddate = date('Y-m-d', $endtime);// . 'T12:00:00';
		
		$dates = [];
		for($tmpdate = $order['startdate']; $tmpdate < $order['enddate'];){
			$dates[] = $tmpdate;
			$tmpdate = date('Ymd', strtotime($tmpdate) + 86400);
		}
		
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
		$extra_info = $room_codes['code']['extra_info'];
		
		//价格校验
		$pms_rate_check=true;
		$web_room_req=$room_codes['room']['webser_id'];
		$web_rate_req=$extra_info['pms_code'];
		
		$room_count = $order['roomnums'];
		
		$allprice=explode(',', $order['first_detail']['allprice']);
		
		$result=$this->serv_api->getRoomPriceByRateCode($pms_set['hotel_web_id'],$startdate, $enddate, $web_room_req, $web_rate_req);
		if($result){
			if($result['avail'] < $room_count){
				return [
					'result' => 0,
					'errmsg' => '酒店房量不足！',
				];
			}else{
				$row = $result['daily'][0];
				$rate_result = [];
				foreach($result['daily'] as $t){
					list($in_date, $in_time) = explode('T', $t['Date']);
					$in_date = date('Ymd', strtotime($in_date));
					$rate_result[$in_date] = [
						'in_date'  => $in_date,
						'price'    => round($t['Rate'], 2),
						'quantity' => $result['avail'],
					];
				}
				$tc = count($allprice);
				
				//判断日期价格数据是否一致
				if($tc != count($rate_result)){
					$pms_rate_check = false;
				}else{
					//校验每日价格是否一致
					for($i = 0; $i < $tc; $i++){
						$in_date = $dates[$i];
						if($allprice[$i] != $rate_result[$in_date]['price']){
							$pms_rate_check = false;
							break;
						}
					}
				}
			}
			
		}else{
			$pms_rate_check = false;
		}
		
		if(!$pms_rate_check){
			return [
				'result' => 0,
				'errmsg' => '酒店房型价格有变动，请稍后重新下单！',
			];
		}
		
		
		$this->load->model('api/Vmember_model', 'vm');
		
		$member = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		
		
		$total_favour = 0;
		
		$all_daily = explode(',', $order ['first_detail'] ['allprice']);
		$first_rate=$all_daily[0];
		$total_=array_sum($all_daily)*$room_count;
		$remark = '订单金额：'.$total_ . '元。';
		
		if($order['coupon_favour'] > 0){
			$remark .= '#使用优惠券：' . $order ['coupon_favour'] . '元。#';
			$total_favour += $order['coupon_favour'];
			
			$first_rate-=$order['coupon_favour'];
			
		}
		
		//每晚房价
		$rn=0;
		$tc=count($allprice);
		foreach($order['order_details'] as $kv => $v){
			$rn++;
			$daily_rem = [];
			for($i = 0; $i < $tc; $i++){
				$t = $allprice[$i];
				if($kv == 0 && $i == 0){
					$t = $first_rate;
				}
				$daily_rem[] = date('Y-m-d', strtotime($dates[$i])) . '：' . $t . '元';
//				$remark .= date('Y-m-d', strtotime($dates[$i])) . '：'.$t.'元|';
			}
			$remark .= '房间' . $rn . '【' . implode('|', $daily_rem) . '】';
		}
		
		if($order['point_favour'] > 0){
			$remark .= '使用【'.$order['point_used_amount'].'】积分扣减：' . $order['point_favour'] . '元。';
			$total_favour += $order['point_favour'];
		}
		
		$remark.='实付金额：'.$order['price'].'元。';
		
		if($order['paytype']=='point'){
			$remark.='使用【'.$order['point_used_amount'].'】积分抵扣全部房费';
		}
		
		$resv_type = $pms_auth['resv_type']['normal'];
		
		if($order['paytype'] == 'weixin'||$order['paytype']=='point'){
			$resv_type = $pms_auth['resv_type']['prepay'];
		}
		
		
		if($order['first_detail']['customer'] != ''){
			foreach($order['order_details'] as $v){
				$name_arr[] = $v['customer'];
			}
		}else{
			$name_arr[] = $order['name'];
		}
		
		$order_params = [
			'HotelID'  => $pms_set['hotel_web_id'],
			'ArrDate'  => $startdate,
			'DepDate'  => $enddate,
			'RmType'   => $room_codes['room']['webser_id'],
			'RateCode' => $extra_info['pms_code'],
			'Rate'     => $first_rate,
			'Name'     => implode(',',$name_arr),
			'Mobile'   => $order['tel'],
			'Remark'   => $remark,
			'ResType'  => $resv_type,
			'Amount'      => $order['roomnums'],
			
			'DepositRule' => $extra_info['deposit_rule'],
			'CancelRule'  => $extra_info['cancel_rule'],
			'BeforeHour'  => $extra_info['cancel_days'],
			
			'NegoID'=>$pms_auth['NegoID'],
		    'CrsNo'=>$order['openid'],
		];
		
		if(!empty($member['pms_user_id'])){
			$order_params['CardNo'] = $member['membership_number'];
		}
		
		$result = $this->serv_api->submitOrder($order_params, ['orderid' => $order['orderid']]);
		
		if(!empty($result['success'])){
			return [
				'result'=>1,
				'web_orderid'=>$result['web_orderid'],
				'accnt'=>explode('##',$result['accnt']),
			];
		}else{
			return [
				'result' => 0,
				'errmsg' => isset($result['errmsg']) ? $result['errmsg'] : '',
			];
		}
	}
	
	public function update_web_order($inter_id, $order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'], ['orderid' => $order['orderid']]);
		$istatus = -1;
		
		if($web_order){
			$this->load->model('hotel/Order_model');
			$status_arr=$this->pms_enum('status');
			foreach($order['order_details'] as $od){
				$updata=[];
				$sub_order=$web_order[$od['webs_orderid']];
				
				$istatus=$status_arr[$sub_order['Status']];
				
				if($od ['istatus'] == 4 && $istatus == 5){
					$istatus = 4;
				}
				
				//PMS上的入住，离店时间
				list($sdate,$stime)=explode('T',$sub_order['FromDate'],2);
				$web_start = date('Ymd', strtotime($sdate));
				list($edate,$etime)=explode('T',$sub_order['ToDate'],2);
				$web_end = date('Ymd', strtotime($edate));
				$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
				
				//判断实际入住时间，订单记录的入住时间
				$ori_day_diff = get_room_night($od['startdate'], $od['enddate'], 'ceil', $od);//至少有一个间夜
				$web_day_diff = get_room_night($web_start, $web_end, 'ceil');//至少有一个间夜
				$day_diff = $web_day_diff - $ori_day_diff;
				
				$updata['startdate'] = $web_start;
				$updata['enddate'] = $web_end;
				if($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']){
					$updata ['no_check_date'] = 1;
				}
				
				if($istatus != $od ['istatus']){
					$updata['istatus'] = $istatus;
				}
				
				if(!empty ($updata)){
					$this->Order_model->update_order_item($inter_id, $order['orderid'], $od['sub_id'], $updata);
				}
			}
		}
		
		return $istatus;
	}
	
	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$web_paid = 2;
		//空订单号
		if(empty($web_orderid)){
			$this->db->where([
				'orderid'  => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			]);
			//更新web_paid 状态，2为失败，1为成功
			$this->db->update('hotel_order_additions', [
				'web_paid' => $web_paid
			]);
			return false;
		}
		$this->apiInit($pms_set);
		//查询网络订单是否存在
		$web_order = $this->serv_api->queryOrder($order['web_orderid'], ['orderid' => $order['orderid']]);
		
		if(!$web_order){
			$this->db->where([
				'orderid'  => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			]);
			$this->db->update('hotel_order_additions', [
				'web_paid' => $web_paid
			]);
			return false;
		}
		
		$extra=[];
		$pccode=$pms_auth['pccode'][$order['paytype']];
		
		//PMS上的入账接口
		$result = $this->serv_api->addPayment($web_orderid, $trans_no, $order['price'], $pccode, $extra, ['orderid' => $order['orderid']]);
		
		if($result){
			$web_paid = 1;
		}
		
		$this->db->where([
			'orderid'  => $order ['orderid'],
			'inter_id' => $order ['inter_id']
		]);
		$this->db->update('hotel_order_additions', [
			'web_paid' => $web_paid
		]);
		return $web_paid == 1;
	}
	
	public function cancel_order_web($inter_id, $order, $pms_set = []){
		if(empty ($order ['web_orderid'])){
			return [
				's'      => 0,
				'errmsg' => '取消失败'
			];
		}
		
		$this->apiInit($pms_set);
		
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
		$extra_info = $room_codes['code']['extra_info'];
		
		$res = $this->serv_api->cancelOrder($order['web_orderid'], ['orderid' => $order['orderid']]);
		
		if($res['result']){
			
			return [
				//取消成功，直接这样return，接下来的程序会继续处理
				's'      => 1,
				'errmsg' => '取消成功'
			];
		}
		
		return [
			's'      => 0,
			'errmsg' => '取消失败,' . (isset($res['errmsg']) ? $res['errmsg'] : ''),
		];
	}
	
	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid']);
		if($web_order){
			$check=true;
			$status_arr = $this->pms_enum('status');
			foreach($web_order as $v){
				$status=$status_arr[$v['Status']];
				
				$check=$check&&($status == 1 || $status == 0);
			}
			$main_order = $web_order['main'];
			if($main_order){
				$status = $status_arr [$main_order['sta']];
			}
		}else{
			$check=false;
		}
		return $check;
	}
	
	function pms_enum($type){
		switch($type){
			case 'status' :
				return [
					//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
					
					'Q' => 0,
					'R' => 1,
					'I' => 2,
					'O' => 3,
					'D' => 3,
					//					'H' => 3,
					'X' => 5,
					'N' => 8,
				];
				break;
			default :
				return [];
				break;
		}
	}
	
	private function apiInit($pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$pms_auth['inter_id'] = $pms_set['inter_id'];
		$this->serv_api->setPMSAuth($pms_auth);
	}
	
	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1', true);
		}
		return $db_read;
	}
	
}

class Xiruanapi3{
	private $inter_id;
	private $CI;
	
	private $url;
	private $Channel;
	private $InterID;
	private $InterPwd;
	
	private $soap;
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->model('common/Webservice_model');
		$this->CI->load->helper('common');
	}
	
	public function setPmsAuth($config = []){
		$time = time();
		
		$this->inter_id = $config['inter_id'];
		$this->url = $config['url'];
		$this->Channel = $config['Channel'];
		$this->InterID = $config['InterID'];
		$this->InterPwd = $config['InterPwd'];
		
		
		$soap_opt = array(
			'soap_version' => SOAP_1_1,
			'encoding'     => 'UTF-8',
						'cache_wsdl'   => WSDL_CACHE_NONE,
			//			'trace'        => true,
		);
		
		try{
			$this->soap = new SoapClient($this->url, $soap_opt);
		}catch(SoapFault $e){
			$this->checkWebResult('', [], $e, $time, microtime(), [], ['run_alarm' => 1]);
		}catch(Exception $e){
			$this->checkWebResult('', [], $e, $time, microtime(), [], ['run_alarm' => 1]);
		};
	}
	
	/**
	 * @param $hotel_web_id
	 * @param string $sdate
	 * @param string $edate
	 * @param array $web_room
	 * @param array $web_rate
	 * @return array
	 */
	public function getRoomState($hotel_web_id, $sdate, $edate, $web_room = [], $web_rate = [], $func_data = []){
		$sdate = date('Y-m-d', strtotime($sdate));
		$edate = date('Y-m-d', strtotime($edate));
		$data = [
			'Channel'    => $this->Channel,
			'HotelID'    => $hotel_web_id,
			'ArrDate'    => $sdate,
			'DepDate'    => $edate,
			'Amount'     => 1,
			'Adult'      => 1,
			'Child'      => 0,
			'CardNo'     => '',
			'NegoID'     => '',
			'IsBestRate' => '',
			'RmType'     => implode(',', $web_room),
			'RateCode'   => implode(',', $web_rate),
		];
		$params = ['RmInf' => $data];
		
		$res = $this->postService('GetRmInf_MED', $params, $func_data);
		if(isset($res['RetCode']) && $res['RetCode'] == '0'){
			return $res['RoomInfoList']['RoomInfo'];
		}
		return [];
	}
	
	public function getRoomPriceByRateCode($hotel_web_id, $sdate, $edate, $web_room, $web_rate, $func_data = []){
		$sdate = date('Y-m-d', strtotime($sdate));
		$edate = date('Y-m-d', strtotime($edate));
		$data = [
			'Channel'    => $this->Channel,
			'HotelID'    => $hotel_web_id,
			'ArrDate'    => $sdate,
			'DepDate'    => $edate,
			'Rmtype'     => $web_room,
			'RateCode'   => $web_rate,
		];
		$params = ['getrd' => $data];
		$res = $this->postService('GetRateDetailMED', $params, $func_data);
		if(isset($res['RetCode']) && $res['RetCode'] == '0'&&!empty($res['Items']['MEDRateResponseItem'])){
			$items=$res['Items']['MEDRateResponseItem'];
			is_array(current($items)) or $items=[$items];
			return [
				'avail'=>$res['Avail'],
				'daily'=>$items,
			];
		}
		return [];
	}
	
	public function getRoomStateByCache($hotel_web_id, $sdate, $edate, $web_room = [], $web_rate = [], $func_data = []){
		$sdate = date('Y-m-d', strtotime($sdate));
		$edate = date('Y-m-d', strtotime($edate));
		$data = [
			'Channel'    => $this->Channel,
			'HotelID'    => $hotel_web_id,
			'ArrDate'    => $sdate,
			'DepDate'    => $edate,
			'Amount'     => 1,
			'Adult'      => 1,
			'Child'      => 0,
			'CardNo'     => '',
			'NegoID'     => '',
			'IsBestRate' => '',
			'RmType'     => implode(',', $web_room),
			'RateCode'   => implode(',', $web_rate),
		];
		$params = ['RmInf' => $data];
		
		$res = $this->postService('GetRmRate_MED', $params, $func_data);
		if(isset($res['RetCode']) && $res['RetCode'] == '0'){
			return $res['RoomInfoList']['RoomInfo'];
		}
		return [];
	}
	
	public function submitOrder($data, $func_data = []){
		$merge = [
			'HotelID'     => '',
			'ArrDate'     => '',
			'DepDate'     => '',
			'RmType'      => '',
			'RateCode'    => '',
			'Rate'        => 0,
			'Amount'      => 0,
			'Name'        => '',
			'Phone'       => '',
			'Mobile'      => '',
			'Remark'      => '',
			'DepositRule' => '',
			'CancelRule'  => '',
			'BeforeHour'  => '',
			
			'CrsNo'   => '',
			'ResType' => '1',
			
			'Channel'       => $this->Channel,
			'Audlt'         => 1,
			'Child'         => 0,
			'Country'       => '',
			'Zip'           => '',
			'MobileReceive' => false,
			'Email'         => '',
			'City'          => '',
			'Address'       => '',
			'Title'         => '',
			'CardNo'        => '',
			'NegoID'        => '',
			'NegoType'      => '',
			'AgentOID'      => '',
			'AgentCardNo'   => '',
			'AgentMobile'   => '',
			'AgentType'     => '',
		];
		
		$data = array_merge($merge, $data);
		$params = [
			'neworder' => $data,
		];
		$result = $this->postService('NewOrderMED', $params, $func_data);
		if(isset($result['RetCode'])){
			if($result['RetCode'] == '0'){
				return [
					'success'      => true,
					'web_orderid' => $result['ResNo'],
					'accnt'       => $result['Accnt'],
				];
			}else{
				return [
					'success' => false,
					'errmsg' => $result['RetDesc'],
				];
			}
		}
		return [
			'success' => false,
			'errmsg' => '',
		];
	}
	
	public function cancelOrder($res_no, $func_data = []){
		$params = [
			'cancelorder' => [
				'ResNo'   => $res_no,
				'Accnt'   => '',
				'Channel' => $this->Channel,
			],
		];
		$result = $this->postService('CancelOrderMED', $params, $func_data);
		if(isset($result['RetCode'])){
			if($result['RetCode'] == '0'){
				return [
					'result' => true,
				];
			}else{
				return [
					'result' => false,
					'errmsg' => $result['RetDesc'],
				];
			}
		}
		return [
			'result' => false,
			'errmsg' => '',
		];
	}
	
	public function queryOrder($web_orderid, $func_data = []){
		$params = [
			'resNo' => $web_orderid,
			'crsNo' => '',
		];
		$res = [];
		$result = $this->postService('GetRes', $params, $func_data);
		if(isset($result['RetCode']) && $result['RetCode'] == '0'){
			$web_order = $result['OrderList']['OrderInfo'];
			is_array(current($web_order)) or $web_order = [$web_order];
			foreach($web_order as $v){
				$res[$v['Accnt']]=$v;
			}
			
		}
		return $res;
	}
	
	/**
	 * 入账
	 * @param string $res_no 预订号
	 * @param string $trans_no 流水号
	 * @param double $amount 金额
	 * @param string $pccode 付款码
	 * @param array $extra
	 * @param array $func_data
	 * @return bool
	 */
	public function addPayment($res_no, $trans_no, $amount, $pccode, $extra = [], $func_data = []){
		$needed = ['Source' => 'F', 'PreAuthType' => 'C', 'CardNo' => ''];
//		$extra = array_merge($needed, $extra);
		$data = [
			'ResNo'     => $res_no,
			'Channel'   => $this->Channel,
			'PreAuthID' => $trans_no,
			'Credit'    => $amount,
			'Pccode'    => $pccode,
			'Payment'   => $amount,
		];
		$data = array_merge($needed, $extra, $data);
		$params = [
			'preauthor' => $data
		];
		
		$result = $this->postService('PreAuthor', $params, $func_data);
		if(isset($result['RetCode']) && $result['RetCode'] == '0'){
			return true;
		}
		return false;
	}
	
	protected function postService($func, $params, $func_data = []){
		$time = time();
		$auth_param = [
			'InterID'  => $this->InterID,
			'InterPwd' => $this->InterPwd,
		];
		$parameters = array_merge($auth_param, $params);
		
		$result = [
			'RetCode'=>'9999',
			'RetDesc'=>'接口错误',
		];
		if($this->soap){
			$s = null;
			$run_alarm = 0;
			try{
				$obj = $this->soap->__soapCall($func, ['parameters' => $parameters]);
				
				/*$this->CI->Webservice_model->add_webservice_record($this->inter_id, 'xiruan3', $this->url, [
					$func,
					$parameters
				], $obj, 'query_post', $time, microtime(), $this->CI->session->userdata($this->inter_id . 'openid'));*/
				
				$micro_receive_time = explode(' ', microtime());
				$wait_time = $micro_receive_time [1] - $time + number_format($micro_receive_time [0], 2, '.', ''); // 计算等待时间
				$send_content = json_encode([$func, $parameters], JSON_UNESCAPED_UNICODE);
				$receive_content = json_encode($obj);
				MYLOG::pms_access_record($this->inter_id, $time, $wait_time, 'xiruan3', $this->url, $send_content, $receive_content, "openid=" . $this->CI->session->userdata($this->inter_id . 'openid'));
				
				if(!empty($obj->{$func . 'Result'})){
					$result = $obj->{$func . 'Result'};
				}
				$result = obj2array($result);
				$s = $result;
			}catch(SoapFault $e){
				$s = $e;
				$run_alarm = 1;
			}catch(Exception $e){
				$s = $e;
				$run_alarm = 1;
			}
			$this->checkWebResult($func, $parameters, $s, $time, microtime(), $func_data, ['run_alarm' => $run_alarm]);
		}
		return $result;
	}
	
	protected function checkWebResult($func_name, $send, $receive, $now, $micro_time, $func_data = [], $params = []){
		$func_name_des = $this->pms_enum('func_name', $func_name);
		isset ($func_name_des) or $func_name_des = $func_name; // 方法名描述\
		$err_msg = ''; // 错误提示信息
		$err_lv = NULL; // 错误级别，1报警，2警告
		$alarm_wait_time = null; // 默认超时时间
		if(!empty($params['run_alarm'])){ // 程序运行报错，直接报警
			$err_msg = '程序报错,' . json_encode($receive, JSON_UNESCAPED_UNICODE);
			$err_lv = 1;
		}else{
			if(!isset($receive['RetCode'])){
				$err_msg = '接口错误，' . json_encode($receive, JSON_UNESCAPED_UNICODE);
				$err_lv = 2;
				switch($func_name){
					case 'NewOrderMED':
					case 'CancelOrderMED':
					case 'PreAuthor':
						$err_lv=1;
						break;
				}
			}elseif($receive['RetCode'] != '0'){
				$err_msg = $receive['RetDesc'];
				$err_lv = 2;
				switch($func_name){
					case 'NewOrderMED':
					case 'CancelOrderMED':
					case 'PreAuthor':
						$err_lv=1;
						break;
				}
			}else{
				switch($func_name){
					case 'GetRmInf_MED':
					case 'GetRmRate_MED':
						if(empty($receive['RoomInfoList']['RoomInfo'])){
							$err_msg = '空房价数据';
							$err_lv = 2;
						}
						break;
					case 'GetRes':
						if(empty($receive['OrderList']['OrderInfo'])){
							$err_msg = '空订单数据';
							$err_lv=2;
						}
						break;
					case 'GetRateDetailMED':
						if(empty($receive['Items']['MEDRateResponseItem'])){
							$err_msg='空数据';
							$err_lv=2;
						}
						break;
				}
			}
			
		}
		
		$this->CI->Webservice_model->webservice_error_log($this->inter_id, 'xiruan3', $err_lv, $err_msg, array(
			'web_path'        => $this->url,
			'send'            => $send,
			'receive'         => $receive,
			'send_time'       => $now,
			'receive_time'    => $micro_time,
			'fun_name'        => $func_name_des,
			'alarm_wait_time' => $alarm_wait_time
		), $func_data);
	}
	
	private function pms_enum($type = '', $key = ''){
		$arr = [];
		switch($type){
			case 'func_name':
				$arr = [
					'GetRmInf_MED'  => '实时房价读取',
					'GetRmRate_MED' => '缓存房价读取',
					'GetRes'        => '查询订单',
					'NewOrderMED'=>'新建订单',
					'CancelOrderMED'=>'取消订单',
					'PreAuthor'=>'网络预付款',
					'GetRateDetailMED'=>'房价读取'
				];
				break;
		}
		if($key === ''){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}
}