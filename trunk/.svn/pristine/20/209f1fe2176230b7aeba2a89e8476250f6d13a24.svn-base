<?php

class Yuheng_hotel_model extends MY_Model{
	private $serv_api;
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
		$this->serv_api = new Yuhengapi();
	}
	
	public function get_rooms_change($rooms, $idents, $condit, $pms_set = []){
		statistic('A1');
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], [
			'web_price_code_set',
			'web_basic_code',
		], 1, 'w2l');
		
		$this->load->model('api/Vmember_model', 'vm');
		$member_level = $this->vm->getLvlPmsCode($condit['openid'], $idents['inter_id']);
		
		$web_price_code = '';
		
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
		} else{
			if(!empty ($web_reflect ['web_basic_code'])){
				foreach($web_reflect ['web_basic_code'] as $wpc){
					$web_price_code .= ',' . $wpc;
				}
			}
		}
		$web_price_code = explode(',', $web_price_code);
		$countday = get_room_night($condit ['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$web_rids = [];
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		
		statistic('A2');
		$params = [
			'countday'     => $countday,
			'web_rids'     => $web_rids,
			'condit'       => $condit,
			'web_reflect'  => $web_reflect,
			'member_level' => $member_level,
			'idents'       => $idents,
		];
		
		$pms_data = $this->get_web_roomtype($pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params);
		statistic('A3');
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
		statistic('A4');
		
		$a_b = statistic('A1', 'A2');//查询本地配置
		$b_c = statistic('A2', 'A3');//请求PMS实时房态
		$c_d = statistic('A3', 'A4');//与本地房型匹配
		$a_d = statistic('A1', 'A4');//获取房型
		$timer_arr = [
			'查询本地配置'    => $a_b . '秒',
			'请求PMS实时房态' => $b_c . '秒',
			'与本地房型匹配'   => $c_d . '秒',
			'获取房型房态总耗时' => $a_d . '秒',
			'执行时间'      => date('Y-m-d H:i:s'),
		];
		pms_logger(func_get_args(), $timer_arr, __METHOD__ . '->query_time', $pms_set['inter_id']);
		return $data;
	}
	
	public function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		$web_room_keys = array_keys($params['web_rids']);
		$web_room = null;
		if(count($web_room_keys) == 1){
			$web_room = $web_room_keys[0];
		}
		$result = $this->serv_api->getRoomRate($pms_set['hotel_web_id'], $startdate, $enddate, $web_room,['hotel_id' => $params['idents']['hotel_id']]);

//		echo '<pre>';print_r($result);exit;
		
		$web_room_rate = [];
		foreach($result as $v){
			$tmp_data = explode('T', $v['hoteldate']);
			$v['in_date'] = date('Ymd', strtotime($tmp_data[0]));
			$web_room_rate[$v['code']][$v['ratecode']][] = $v;
		}
		
		$pms_state = [];
		$valid_state = [];
		$exprice = [];
		if($web_room_rate){
			foreach($web_room_rate as $web_room => $v){
				if(!array_key_exists($web_room, $params['web_rids'])){
					continue;
				}
				foreach($v as $web_rate => $t){
					$row = $t[0];
					$pms_state[$web_room][$web_rate]['price_name'] = $row['des']?$row['des']:$web_rate;
					$pms_state[$web_room][$web_rate]['price_type'] = 'pms';
					$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
					$pms_state[$web_room][$web_rate]['extra_info'] = [
						'type'     => 'code',
						'pms_code' => $web_rate,
						'rate0'    => $row['rate0'],
						//								'channel_code' => $rd ['channelCode']
					];
					$pms_state[$web_room][$web_rate]['des'] = '';
					$pms_state[$web_room][$web_rate]['sort'] = 0;
					$pms_state[$web_room][$web_rate]['disp_type'] = 'buy';
					
					$web_set = [];
					if(isset ($params ['web_reflect'] ['web_price_code_set'] [$web_rate])){
						$web_set = json_decode($params ['web_reflect'] ['web_price_code_set'] [$web_rate], true);
					}
					
					$pms_state[$web_room][$web_rate]['condition'] = $web_set;
					
					if(isset($params['web_rids'][$web_room]) && isset($params['condit']['nums'][$params['web_rids'][$web_room]])){
						$nums = $params['condit']['nums'][$params['web_rids'][$web_room]];
					} else{
						$nums = 1;
					}
					
					$allprice = [];
					$amount = 0;
					
					/*if(!empty($row['is_point_rate'])){
						$least_arr = [1];
					} else{
						$least_arr = [3];
					}*/
					$least_arr = [3];
					
					$date_status = true;
					
					$amount0=0;
					
					foreach($t as $w){
						$pms_state[$web_room][$web_rate]['date_detail'][$w['in_date']] = [
							'price' => $w['rate'],
							'nums'  => $w['avail']
						];
						
						$allprice[$w['in_date']] = $w['rate'];
						$amount += $w['rate'];
						$amount0 += $w['rate0'];
						$least_arr[] = $w['avail'];
						
						$date_status = $date_status && ($w['avail'] > 0) && empty($w['isfull']);
						
					}
					
					//校验日期价格
					$all_exists=true;
					for($start = date('Ymd', strtotime($startdate)); $start < date('Ymd', strtotime($enddate));){
						if(empty($pms_state[$web_room][$web_rate]['date_detail'][$start])){
							$all_exists=false;
							break;
						}
						$start = date('Ymd', strtotime($start)+86400);
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
					$pms_state[$web_room][$web_rate]['avg_price0'] = number_format($amount0 / $params ['countday'], 2, '.', '');
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
	
	public function get_rooms_change_ratecode($pms_data, $rooms, $params){
		$local_rooms = $rooms ['rooms'];
		$condit = $params ['condit'];
		$this->load->model('hotel/Order_model');
		$data = $this->Order_model->get_rooms_change($local_rooms, $params ['idents'], $params ['condit']);
		$pms_state = $pms_data ['pms_state'];
		$valid_state = $pms_data ['valid_state'];
		
		$merge = [
			'least_num',
			'book_status',
			'extra_info',
			'date_detail',
			'avg_price0',
			//			'avg_price',
			//			'allprice',
			//			'total',
			//			'total_price',
		];
		
		foreach($data as $room_key => $lrm){
			$min_price = [];
			if(empty ($valid_state [$lrm ['room_info'] ['webser_id']])){
				unset ($data [$room_key]);
				continue;
			}
			
			$nums = isset ($condit ['nums'] [$lrm ['room_info'] ['room_id']]) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
			
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
									} else{
										$tmp[$w][$dk]['price'] = $td['price'];
									}
//									$tmp[$w][$dk]['nums'] = $tmp['least_num'];
									$allprice .= ',' . $tmp[$w][$dk]['price'];
									$amount += $tmp[$w][$dk]['price'];
								}
								
								$data[$room_key]['state_info'][$sik]['avg_price'] = number_format($amount / $params['countday'],2);
								$data[$room_key]['state_info'][$sik]['allprice'] = substr($allprice, 1);
								$data[$room_key]['state_info'][$sik]['total'] = intval($amount);
								$data[$room_key]['state_info'][$sik]['total_price'] = $data[$room_key]['state_info'][$sik]['total'] * $nums;
								
								if(!empty($tmp['extra_info']['is_pms_point'])){
									$data[$room_key]['state_info'][$sik]['total_point']=$this->price2Point($data[$room_key]['state_info'][$sik]['total_price'],$condit['startdate']);
								}
							}
							$data[$room_key]['state_info'][$sik][$w] = $tmp[$w];
						}
					}
					
					$min_price[] = str_replace(',','',$data[$room_key]['state_info'][$sik]['avg_price']);
//					}
				}
			}
			
			$data[$room_key]['lowest'] = empty($min_price) ? 0 : min($min_price);
			$data[$room_key]['highest'] = empty($min_price) ? 0 : max($min_price);
			
			//君亭，只需要一个可用价格代码
			if(!empty($data[$room_key]['state_info'])){
				$state_info=$data[$room_key]['state_info'];
				uasort($state_info,function($a,$b){
					//按价格最低
					$a_price = str_replace(',', '', $a['avg_price']);
					$b_price = str_replace(',', '', $b['avg_price']);
					return bcsub($a_price, $b_price, 2) > 0 ? 1 : -1;
				});
				
				$data[$room_key]['state_info']=[];
				
				foreach($state_info as $k => $v){
					$data[$room_key]['state_info'][$k] = $v;
					$avg_price = str_replace(',', '', $v['avg_price']);
					$avg_price0 = str_replace(',', '', $v['avg_price0']);
					$data[$room_key]['lowest'] = $avg_price;
					$data[$room_key]['highest'] = $avg_price;
					$data[$room_key]['favour_price'] = $avg_price0 - $avg_price;
					break;
				}
			}
			
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
								foreach($tmp [$w] as $dk => $td){
									if($si['related_cal_way'] && $si['related_cal_value']){
										$tmp[$w][$dk]['price'] = round($this->Order_model->cal_related_price($td['price'], $si['related_cal_way'], $si['related_cal_value'], 'price'));
									} else{
										$tmp[$w][$dk]['price'] = $td['price'];
									}
									$tmp [$w] [$dk] ['nums'] = $tmp['least_num'];
									$allprice .= ',' . $tmp [$w] [$dk] ['price'];
									$amount += $tmp [$w] [$dk] ['price'];
								}
								
								$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday'],2);
								$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr($allprice, 1);
								$data [$room_key] ['show_info'] [$sik] ['total'] = intval($amount);
								$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
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
//		echo '<pre>';print_r($data);exit;
		
		return $data;
	}
	
	public function order_to_web($inter_id, $orderid, $params = [], $pms_set = []){
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
				
				//更新子订单
				/*$child_list = $this->readDB()->where(array(
					                               'orderid'  => $order ['orderid'],
					                               'inter_id' => $order ['inter_id']
				                               ))->from('hotel_order_items')->select('id')->get()->result_array();
				$child=array_pop($child_list);
				if($child){
					$this->db->where(array('id' => (int)$child['id']))->update('hotel_order_items', array('webs_orderid' => $web_orderid));
				}*/
				
				if($order['status'] != 9){
					$this->change_order_status($inter_id, $orderid, 1);
					$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
				}
				
				if(!empty ($params['third_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
					$this->add_web_bill($web_orderid, $order, $pms_set, $params['third_no']);
				}
				return [ // 返回成功
				         's' => 1
				];
			} else{
				$this->change_order_status($inter_id, $orderid, 10);
				return [ // 返回失败
				         's'      => 0,
				         'errmsg' => '提交订单失败。' . $result ['errmsg']
				];
			}
		}
		return [
			's'      => 0,
			'errmsg' => '提交订单失败'
		];
	}
	
	private function change_order_status($inter_id, $orderid, $status){
		$this->db->where(array(
			'orderid'  => $orderid,
			'inter_id' => $inter_id
		));
		$this->db->update('hotel_orders', array( // 提交失败，把订单状态改为下单失败
		                                         'status' => (int)$status
		));
	}
	
	
	function order_reserve($order, $pms_set, $params = []){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		$starttime = strtotime($order['startdate']);
		$endtime = strtotime($order['enddate']);
		if(ceil(($endtime - time()) / 86400) > 30){
			return [
				'result' => 0,
				'errmsg' => '最多可预订未来30天的房',
			];
		}
		
		$countday = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
		$startdate = date('Y-m-d', $starttime);
		$enddate = date('Y-m-d', $endtime);
		
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
		$extra_info = $room_codes['code']['extra_info'];
		
		$member = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		
		$remark = '';
		
		$all = explode(',', $order['first_detail']['allprice']);
		$all_price=array_sum($all); //单个房型总价
		//每天的平均价
		$first_rate=round($all_price/$countday,2);
		
		$daily_remark = [];
		for($i=0;$i<$countday;$i++){
			$house_date = date('Y-m-d', $starttime + ($i * 86400));
			
			$daily_remark[] = $house_date.'【'.$all[$i].'】';
		}
		
		$remark .= ' 日房价 ' . implode('/', $daily_remark) . ' 总房价 ' . $order['price'];
		
		$coupon='';
		
		$_pyk='daofu_';
		
		if(!empty($params['third_no'])){
			$remark.=' 该订单已支付成功【'.$params['third_no'].'】';
			$_pyk=$order['paytype'];
		}
		
		if($order ['coupon_favour'] > 0){
			$remark .= '使用优惠券：' . $order['coupon_favour'] . '元。';
			$coupon_arr = [];
			$coupon_des = json_decode($order['coupon_des'], true);
			if(array_key_exists('cash_token', $coupon_des)){
				$coupon_count = count($coupon_des['cash_token']);
				for($i = 0; $i < $coupon_count; $i++){
					$t = $coupon_des['cash_token'][$i];
					$coupon_arr[] = $t['code'];
				}
			}
			$coupon = implode(',', $coupon_arr);
			$_pyk.='coupon';
		}
		
		if($order['point_favour']>0){
			$remark .= '积分扣减：' . $order['point_favour'] . '元。';
		}
		
		$order_params = [
			'strHotelId'     => $pms_set['hotel_web_id'],
			'strMobile'      => $order['tel'],
			'strName'        => $order['name'],
			'strRoomType'    => $room_codes['room']['webser_id'],
			'dcRate'         => $first_rate,
			//			'dcRate0'        => $extra_info['rate0'],
			//			'dcRate'         => $order['first_detail']['iprice'],
			'dcRate0'        => $extra_info['rate0'],
			'iRoomNumber'    => $order['roomnums'],
			'dtArrival'      => $startdate,
			'dtDeparture'    => $enddate,
			'strRemark'      => $remark,
			'strPayMentType' => isset($pms_auth['pay_type'][$_pyk]) ? $pms_auth['pay_type'][$_pyk] : 99,
			'strDiYongQuan'  => $coupon,
			'strDiYongJin'   => $order['coupon_favour']+$order['point_favour'],
			'strCrsAdvamount'=> 0
		];
		
		//测试号
//		$member['pms_user_id']='000000000003558';
		
		if(!empty($member['pms_user_id'])){
			$order_params['strCardNo']=$member['membership_number'];
			$result = $this->serv_api->submitOrder($order_params,['orderid'=>$order['orderid']]);
			$err_arr=$this->pms_enum('submit_err');
		}else{
			$result = $this->serv_api->submitOrder2($order_params,['orderid'=>$order['orderid']]);
			$err_arr=$this->pms_enum('submit_err2');
		}
		
		if(strpos($result, ':') !== false){
			list($msg, $web_orderid) = explode(':', $result, 2);
			return [
				'result'      => true,
				'web_orderid' => $web_orderid
			];
		}
		
		return [
			'result' => false,
			'errmsg' => (isset($err_arr[$result]) ? $err_arr[$result] : ''),
		];
	}
	
	public function cancel_order_web($inter_id, $order, $pms_set = []){
		if(empty ($order ['web_orderid'])){
			return [
				's'      => 0,
				'errmsg' => '取消失败'
			];
		}
		
		$this->apiInit($pms_set);
		
		$result = $this->serv_api->cancelOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		
		if(strpos($result, ':') !== false){
			list($r, $oid) = explode(':', $result, 2);
			return [
				//取消成功，直接这样return，接下来的程序会继续处理
				's'      => 1,
				'errmsg' => '取消成功'
			];
		}
		
		$err_arr=$this->pms_enum('cancel_err');
		return [
			'result' => false,
			'errmsg' => '取消失败。'.(isset($err_arr[$result]) ? $err_arr[$result] : ''),
		];
		
	}
	
	public function update_web_order($inter_id, $order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		$istatus = -1;
		if(!empty($web_order)){
			$status_arr = $this->pms_enum('status');
			
			$this->load->model('hotel/Order_model');
			
			$ensure_check = 0;
			
			//存在子订单，则操作子订单状态
			$local_exists = [];  //已更新至本地的子订单号
			$local_noexists = []; //还没有更新
			
			foreach($order ['order_details'] as $od){
				if(!empty($od['webs_orderid'])){
					$local_exists[$od['webs_orderid']] = $od;
				} else{
					$local_noexists[] = $od;
				}
			}
			$i = 0;
			is_array(current($web_order)) or $web_order=[$web_order];
			foreach($web_order as $v){
				if(!isset($v['status'])){
					break;
				}
				$updata = [];
				if(array_key_exists($v['orderno'], $local_exists)){
					//该子订单号已保存至本地
					$od = $local_exists[$v['orderno']];
				} else{
					//还没有更新至本地子订单号
					$od = $local_noexists[$i];
					
					$this->db->where(['id' => (int)$od['sub_id']])->update('hotel_order_items', ['webs_orderid' => $v['orderno']]);
					
					$i++;
				}
				
				$istatus = $status_arr[$v['status']];
				if($od ['istatus'] == 4 && $istatus == 5){
					$istatus = 4;
				}
				
				// 未确认单先确认
				if(!$ensure_check && $istatus != 0 && $order['status'] == 0){
					$this->change_order_status($inter_id, $order['orderid'], 1);
					$this->Order_model->handle_order($inter_id, $order ['orderid'], 1, '', [
						'no_tmpmsg' => 1
					]);
					$ensure_check = 1;
				}
				
				list($arr_date,$tmp)=explode('T',$v['arrivaldate'],2);
				list($dep_date,$tmp)=explode('T',$v['departuredate'],2);
				
				//PMS上的入住，离店时间
				$web_start = date('Ymd', strtotime($arr_date));
				$web_end = date('Ymd', strtotime($dep_date));
				$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
				
				//判断实际入住时间，订单记录的入住时间
				$ori_day_diff = get_room_night($od['startdate'],$od['enddate'],'ceil',$od);//至少有一个间夜
				$web_day_diff = get_room_night($web_start,$web_end,'ceil');//至少有一个间夜
				$day_diff = $web_day_diff - $ori_day_diff;
				
				$updata['startdate'] = $web_start;
				$updata['enddate'] = $web_end;
				if($day_diff != 0 || $web_start != $od['startdate'] || $web_end != $od['enddate']){
					$updata['no_check_date'] = 1;
				}
				
				//离店后更新订单金额
				if($istatus==3){
					$all_price = explode(',', $od['allprice']);
					$real_price = explode(',', $od['real_allprice']);
					$dis_price = array_sum($all_price) - array_sum($real_price);
					$new_price = $v['actualrate'] - $dis_price;
					if($new_price >= 0 && $od['iprice'] != $new_price){
						$updata['new_price'] = $new_price;
						$updata['no_check_date'] = 1;
					}
				}
				
				if($istatus != $od ['istatus']){
					$updata ['istatus'] = $istatus;
				}
				
				if(!empty ($updata)){
					$this->Order_model->update_order_item($inter_id, $order ['orderid'], $od ['sub_id'], $updata);
				}
			}
		}
		
		return $istatus;
	}
	
	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no){
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		$web_paid = 2;
		//空订单号
		if(empty($web_orderid)){
			$this->db->where([
				'orderid'  => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			]);
			$this->db->update('hotel_order_additions', [ //更新web_paid 状态，2为失败，1为成功
			                                             'web_paid' => $web_paid
			]);
			return false;
		}
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		
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
		
		$_pyk=$order['paytype'].'_';
		if($order ['coupon_favour'] > 0){
			$_pyk.='coupon';
		}
		//支付方式
		$pay_type=isset($pms_auth['pay_type'][$_pyk]) ? $pms_auth['pay_type'][$_pyk] : 5;
		
		$all = explode(',', $order['first_detail']['allprice']);
		$countday=get_room_night($order['startdate'],$order['enddate'],'ceil',$order);
		$all_price=array_sum($all); //单个房型总价
		//每天的平均价
		$first_rate=round($all_price/$countday,2);
		
		$result=$this->serv_api->addPayment($web_orderid,$pay_type,$first_rate,$order['price'],['orderid'=>$order['orderid']]);
		if($result==5){
			$web_paid = 1;
		}
		
		$this->db->where([
			'orderid'  => $order ['orderid'],
			'inter_id' => $order ['inter_id']
		]);
		$this->db->update('hotel_order_additions', [
			'web_paid' => $web_paid
		]);
		return $web_paid==1;
	}
	
	//判断订单是否能支
	function check_order_canpay($order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		$check=true;
		if($web_order){
			is_array(current($web_order)) or $web_order=[$web_order];
			$status_arr = $this->pms_enum('status');
			foreach($web_order as $v){
				$status = $status_arr[$v['status']];
				if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
					$check=true;
				}else{
					$check=false;
					continue;
				}
			}
		}
		return $check;
	}
	
	
	private function pms_enum($type = ''){
		switch($type){
			case 'status':
				/*R：预订
C：取消
O:结账退房
N：预订未到NoShow
I：入住未退房
S:退房未结账*/
				//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
				return [
					'R' => 1,
					'C' => 5,
					'O' => 3,
					'N' => 8,
					'I' => 2,
					'S' => 3,
				];
				break;
			case 'submit_err':
				return [
					'0' => '错误',
					'1' => '渠道名称或者密码错误',
					'2' => '卡号不能为空',
					'3' => '会员卡客史不存在',
					'4' => '卡号不存在',
					'5' => '传入的离开日期小于到达日期',
					'6' => '预订日期不能小于当前日期',
					'7' => '预订人姓名不能为空',
					'8' => '酒店房量未设置',
					'9' => '当前房量不够，请重新选择其他房型预订',
				];
				break;
			case 'submit_err2':
				return [
					'0' => '错误',
					'1' => '渠道名称或者密码错误',
					'3' => '传入的离开日期小于到达日期',
					'4' => '预订日期不能小于当前日期',
					'5' => '预订人姓名不能为空',
					'6' => '酒店房量未设置',
					'7' => '当前房量不够，请重新选择其他房型预订',
				];
				break;
			case 'cancel_err':
				return [
					'0' => '未知错误',
					'1' => '渠道名称或者密码错误',
					'2' => '订单号不能为空',
					'3' => '订单号不存在',
				];
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
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
	
}

class Yuhengapi{
	private $inter_id;
	private $soap;
	private $user;
	private $pwd;
	private $CI;
	private $url;
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->helper('common');
		$this->CI->load->model('common/Webservice_model');
	}
	
	public function setPMSAuth($config){
		$this->inter_id = $config['inter_id'];
		
		$soap_opt = array(
			'soap_version' => SOAP_1_1,
			'encoding'     => 'UTF-8',
			//			'cache_wsdl'   => WSDL_CACHE_NONE,
			//			'trace'        => true,
		);
		$time=time();
		$this->user = $config['user'];
		$this->pwd = $config['pwd'];
		
		$this->url = $config['url'];
		try{
			$this->soap = new SoapClient($config['url'], $soap_opt);
		}catch(SoapFault $e){
			$this->checkWebResult('', [], $e, $time, microtime(),[],['run_alarm'=>1]);
		}catch(Exception $e){
			$this->checkWebResult('', [], $e, $time, microtime(),[],['run_alarm'=>1]);
		};
	}
	
	/**
	 * 查询房价
	 * @param $hotel_web_id
	 * @param $startdate
	 * @param $enddate
	 * @param null $webser_id
	 * @param array $func_data
	 * @return array
	 */
	public function getRoomRate($hotel_web_id, $startdate, $enddate, $webser_id = null,$func_data=[]){
		$startdate = date('Y-m-d', strtotime($startdate));
		$enddate = date('Y-m-d', strtotime($enddate));
		$parameters = [
			'strHotelId'  => $hotel_web_id,
			'dtArrival'   => $startdate,
			'dtDeparutre' => $enddate,
		];
		if($webser_id){
			$parameters['strRoomType']=$webser_id;
		}
		
		$result = $this->postService('GetRateDetail', $parameters,$func_data);
		if(!empty($result->any)){
			$result=xml2array($result->any);
			if(!empty($result['NewDataSet']['RoomRateDetail'])){
				$res = $result['NewDataSet']['RoomRateDetail'];
				is_array(current($res)) or $res = [$res];
				return $res;
			}
		}
		
		return [];
	}
	
	/**
	 * @param array $param
	 * strHotelId    String    酒店编号
	 * strCardNo    String    会员卡号
	 * strMobile    String    手机号
	 * strName    String    入住者姓名
	 * strRoomType    String    预订房间姓名
	 * dcRate    Decimal    成交价
	 * dcRate0    Decimal    门市价
	 * iRoomNumber    Int    预订房间数（请控制不能超过3间）
	 * dtArrival    Datetime    预订到达日期
	 * dtDeparture    Datetime    预订离开日期
	 * strRemark    String    备注（可以传点提醒信息）
	 * @param array $func_data
	 * @return null
	 */
	public function submitOrder($param = [],$func_data=[]){
		$result = $this->postService('MakeReservation',$param,$func_data);
		return $result;
	}
	
	/**
	 * @param array $param
	 * strHotelId    String    酒店编号
	 * strMobile    String    手机号
	 * strName    String    入住者姓名
	 * strRoomType    String    预订房间姓名
	 * dcRate    Decimal    成交价
	 * dcRate0    Decimal    门市价
	 * iRoomNumber    Int    预订房间数（请控制不能超过3间）
	 * dtArrival    Datetime    预订到达日期
	 * dtDeparture    Datetime    预订离开日期
	 * strRemark    String    备注（可以传点提醒信息）
	 * @param array $func_data
	 * @return null
	 */
	public function submitOrder2($param = [],$func_data=[]){
		$result = $this->postService('MakeReservation2',$param,$func_data);
		return $result;
	}
	
	public function cancelOrder($web_orderid,$func_data=[]){
		$parameter = [
			'strOrderNo' => $web_orderid,
		];
		$result = $this->postService('CancelReservation', $parameter,$func_data);
		return $result;
	}
	
	public function queryOrder($web_orderid,$func_data=[]){
		$parameter = [
			'strOrderNo' => $web_orderid,
		];
		$result = $this->postService('GetReservationDetailbyOrderNo', $parameter,$func_data);
		if(!empty($result->any)){
			$result = xml2array($result->any);
			if(!empty($result['NewDataSet']['Table1'])){
				$res = $result['NewDataSet']['Table1'];
				return $res;
			}
		}
		return [];
	}
	
	public function addPayment($web_orderid, $pay_type, $price, $pay_money,$func_data=[]){
		$parameter=[
			'strOrderNo'=>$web_orderid,
			'strNewPayMentType'=>$pay_type,
			'strNewdcRate'=>$price,
			'strNewCrsAdvamount'=>$pay_money,
		];
		$result=$this->postService('GetPayMentTypeUpdate',$parameter,$func_data);
		return $result;
	}
	
	private function postService($func, $parameters,$func_data=[]){
		$time=time();
		$auth_param = [
			'strCannelId'       => $this->user,
			'strCannelPassWord' => $this->pwd,
		];
		$parameters = array_merge($auth_param, $parameters);
		$result = null;
		$s = null;
		$run_alarm = 0;
		if($this->soap){
			try{
				$obj = $this->soap->__soapCall($func, ['parameters' => $parameters]);
				
				$this->CI->Webservice_model->add_webservice_record($this->inter_id, 'yuheng', $this->url, [
					$func,
					$parameters
				], $obj, 'query_post', $time, microtime(), $this->CI->session->userdata($this->inter_id . 'openid'));
				
				if(!empty($obj->{$func . 'Result'})){
					$result = $obj->{$func . 'Result'};
				}
				$s = $result;
			}catch(SoapFault $e){
				$s = $e;
				$run_alarm = 1;
			}catch(Exception $e){
				$s = $e;
				$run_alarm = 1;
			}
			$this->checkWebResult($func, $parameters, $s, $time, microtime(),$func_data, ['run_alarm' => $run_alarm]);
		}
		return $result;
		
	}
	
	protected function checkWebResult($func_name, $send, $receive, $now, $micro_time,$func_data=[], $params = []){
		$func_name_des = $this->pms_enum('func_name', $func_name);
		isset ($func_name_des) or $func_name_des = $func_name; // 方法名描述\
		$err_msg = ''; // 错误提示信息
		$err_lv = NULL; // 错误级别，1报警，2警告
		$alarm_wait_time = 5; // 默认超时时间
		if(!empty($params['run_alarm'])){ // 程序运行报错，直接报警
			$err_msg = '程序报错,' . json_encode($receive, JSON_UNESCAPED_UNICODE);
			$err_lv = 1;
		}else{
			switch($func_name){ // 针对不同方法判断是否出错
				case 'GetRateDetail':
					if(empty($receive->any)){
						$err_msg = '返回空数据';
						$err_lv=2;
					}else{
						$result=xml2array($receive->any);
						if(empty($result['NewDataSet']['RoomRateDetail'])){
							$err_msg = '返回空数据';
							$err_lv=2;
						}
					}
					break;
				case 'MakeReservation':
					if(strpos($receive, ':') === false){
						$err_msg=$this->pms_enum('submit_err',$receive);
						$err_lv=1;
					}
					break;
				case 'MakeReservation2':
					if(strpos($receive, ':') === false){
						$err_msg=$this->pms_enum('submit_err2',$receive);
						$err_lv=1;
					}
					break;
				case 'GetReservationDetailbyOrderNo':
					if(empty($receive->any)){
						$err_msg = '返回空数据';
						$err_lv=2;
					}else{
						$result=xml2array($receive->any);
						if(empty($result['NewDataSet']['Table1'])){
							$err_msg = '返回空数据';
							$err_lv=2;
						}
					}
					break;
				case 'CancelReservation':
					if(strpos($receive, ':') === false){
						$err_msg=$this->pms_enum('cancel_err',$receive);
						$err_lv=1;
					}
					break;
				case 'GetPayMentTypeUpdate':
					if($receive!=5){
						$err_msg=$this->pms_enum('payment_err',$receive);
						$err_lv=1;
					}
					break;
			}
		}
		
		$this->CI->Webservice_model->webservice_error_log( $this->inter_id, 'yuheng', $err_lv, $err_msg, array (
			'web_path' => $this->url,
			'send' => $send,
			'receive' => $receive,
			'send_time' => $now,
			'receive_time' => $micro_time,
			'fun_name' => $func_name_des,
			'alarm_wait_time' => $alarm_wait_time
		), $func_data );
	}
	
	private function pms_enum($type = '', $key = ''){
		$arr = [];
		switch($type){
			case 'func_name':
				$arr = [
					'GetRateDetail' => '获取房态',
					'MakeReservation'=>'会员提交订单',
					'MakeReservation2'=>'非会员提交订单',
					'GetReservationDetailbyOrderNo'=>'查询订单',
					'CancelReservation'=>'取消订单',
					'GetPayMentTypeUpdate'=>'网络入账',
				];
				break;
			case 'submit_err':
				$arr= [
					'0' => '错误',
					'1' => '渠道名称或者密码错误',
					'2' => '卡号不能为空',
					'3' => '会员卡客史不存在',
					'4' => '卡号不存在',
					'5' => '传入的离开日期小于到达日期',
					'6' => '预订日期不能小于当前日期',
					'7' => '预订人姓名不能为空',
					'8' => '酒店房量未设置',
					'9' => '当前房量不够，请重新选择其他房型预订',
				];
				break;
			case 'submit_err2':
				$arr= [
					'0' => '错误',
					'1' => '渠道名称或者密码错误',
					'3' => '传入的离开日期小于到达日期',
					'4' => '预订日期不能小于当前日期',
					'5' => '预订人姓名不能为空',
					'6' => '酒店房量未设置',
					'7' => '当前房量不够，请重新选择其他房型预订',
				];
				break;
			case 'cancel_err':
				$arr= [
					'0' => '未知错误',
					'1' => '渠道名称或者密码错误',
					'2' => '订单号不能为空',
					'3' => '订单号不存在',
				];
			case 'payment_err':
				$arr= [
					'0' => '未知错误',
					'1' => '流水号或中央预定号不能为空',
					'2' => '渠道名称或者密码错误',
					'3' => '该订单不存在',
					'4' => '预付金额不能大于成交价',
				];
				break;
		}
		if($key === ''){
			return $arr;
		}
		return isset($arr[$key]) ? $arr[$key] : null;
	}
}