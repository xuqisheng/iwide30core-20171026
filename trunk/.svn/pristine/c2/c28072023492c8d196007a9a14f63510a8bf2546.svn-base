<?php

class Qianlima_hotel_model extends MY_Model{
	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
	}
	
	public function get_rooms_change($rooms, $idents, $condit, $pms_set = []){
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], [
			'web_price_code_set',
			'web_basic_code',
			'point_rate_set',
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
		
		$point_rate_arr=[];
		if(!empty($web_reflect['point_rate_set'])){
			$point_rate_str='';
			foreach($web_reflect['point_rate_set'] as $v){
				$point_rate_str.=','.$v;
			}
			if($point_rate_str){
				$point_rate_str=substr($point_rate_str,1);
				$point_rate_arr=explode(',',$point_rate_str);
			}
		}
		
		$web_price_code = explode(',', $web_price_code);
		$countday = get_room_night($condit ['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$web_rids = [];
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		
		$params = [
			'countday'     => $countday,
			'web_rids'     => $web_rids,
			'condit'       => $condit,
			'web_reflect'  => $web_reflect,
			'member_level' => $member_level,
			'idents'       => $idents,
			'point_rate_list'=>$point_rate_arr,
		];
		
		$pms_data = $this->get_web_roomtype($pms_set, $web_price_code, $condit ['startdate'], $condit ['enddate'], $params);
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
		return $data;
	}
	public function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		$_enddate=date('Ymd',strtotime($enddate)-86400);
		
		$result = $this->serv_api->getPmsRoomState($pms_set['hotel_web_id'], $web_price_code, $startdate, $_enddate,['hotel_id'=>$params['idents']['hotel_id']]);
		
		$web_room_rate = [];
		foreach($result as $v){
			$tmp_data = explode('+', $v['rateDate']);
			$v['in_date'] = date('Ymd', strtotime($tmp_data[0]));
			$web_room_rate[$v['rmType']][$v['rateCode']][] = $v;
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
					$is_point_rate=in_array($web_rate, $params['point_rate_list']);
					$pms_state[$web_room][$web_rate]['price_name'] = $row['rateCodeCName'];
					$pms_state[$web_room][$web_rate]['price_type'] = 'pms';
					$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
					$pms_state[$web_room][$web_rate]['extra_info'] = [
						'type'         => 'code',
						'pms_code'     => $web_rate,
						'point_rate'=>$is_point_rate,
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
					$least_arr = [1];
					
					$date_status = true;
					$err=false;
					
					foreach($t as $w){
						if($w['ratePrice']<0){
							$err=true;
							break;
						}
						if($is_point_rate&&$w['ratePrice']==0){
							$w['ratePrice']=1;
						}
						$pms_state[$web_room][$web_rate]['date_detail'][$w['in_date']] = [
							'price' => $w['ratePrice'],
							'nums'  => $w['ratePrice'] > 0 ? $w['vacRooms'] : 0,
						];
						
						$allprice[$w['in_date']] = $w['ratePrice'];
						$amount += $w['ratePrice'];
						$least_arr[] = $w['vacRooms'];
						$date_status = $date_status && ($w['vacRooms'] > 0)&&$w['ratePrice'] > 0;
						
					}
					if($err){
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
								
								$data[$room_key]['state_info'][$sik]['avg_price'] = number_format($amount / $params ['countday']);
								$data[$room_key]['state_info'][$sik]['allprice'] = substr($allprice, 1);
								$data[$room_key]['state_info'][$sik]['total'] = intval($amount);
								$data[$room_key]['state_info'][$sik]['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
							}
							$data[$room_key]['state_info'][$sik][$w] = $tmp[$w];
						}
					}
					
					$avg_price = str_replace(',', '', $data[$room_key]['state_info'][$sik]['avg_price']);;
					if($avg_price >= 0)
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
								
								$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format($amount / $params ['countday']);
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
				
				if($order['paytype'] == 'balance'){
					
					$this->load->model('hotel/Hotel_config_model');
					$config_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $order ['hotel_id'], array(
						'PMS_BANCLANCE_REDUCE_WAY'
					));
					if(!empty ($config_data ['PMS_BANCLANCE_REDUCE_WAY']) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after'){
						$this->load->model('hotel/Member_model');
						$balance_param = [
							'crsNo' => $web_orderid,
						];
						if($this->Member_model->reduce_balance($inter_id, $order['openid'], $order['price'], $order['orderid'], '订房订单余额支付', $balance_param,$order)){
							//调用入账接口
							$pay_res = $this->add_web_bill($web_orderid, $order, $pms_set, $order['orderid'], 'ZC');
							
							$this->Order_model->update_order_status($inter_id, $order ['orderid'], 1, $order ['openid'], true);
						} else{
							$info = $this->Order_model->cancel_order($inter_id, array(
								'only_openid'   => $order ['openid'],
								'member_no'     => '',
								'orderid'       => $order ['orderid'],
								'cancel_status' => 5,
								'no_tmpmsg'     => 1,
								'delete'        => 2,
								'idetail'       => array(
									'i'
								)
							));
							
							return [
								's'      => 0,
								'errmsg' => '储值支付失败！',
							];
						}
					}
				}
				
				if(!empty ($params ['third_no'])){ // 提交账务,如果传入了 trans_no,代表已经支付，调用pms的入账接口
					$this->add_web_bill($web_orderid, $order, $pms_set, $params ['third_no']);
				}
				return [ // 返回成功
				         's' => 1
				];
			} else{
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
		
		$countday = get_room_night($starttime,$endtime,'ceil',$order);//至少有一个间夜
		$startdate = date('Y-m-d', $starttime);
		$enddate = date('Y-m-d', $endtime);
		
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
		$extra_info = $room_codes['code']['extra_info'];
		
		$member = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		
		$remark = '';
		
		if($order['coupon_favour'] > 0){
			$remark .= '使用优惠券：' . $order['coupon_favour'] . '元。';
		}
		if($order['point_favour']>0){
			$remark .= '积分扣减：' . $order['point_favour'] . '元。';
		}
		
		$web_reflect = $this->Webservice_model->get_web_reflect($order['inter_id'], $order['hotel_id'], $pms_set['pms_type'], [
			'web_market_set',
		], 1, 'w2l');
		
		$web_market_set = [];
		if(!empty($web_reflect['web_market_set'])){
			$web_market_set = $web_reflect['web_market_set'];
		}
		
		$market = 'W';
		if($member['lvl_pms_code'] && isset($web_market_set[$member['lvl_pms_code']])){
			$market = $web_market_set[$member['lvl_pms_code']];
		}
		
		if(isset($pms_auth['paytype'][$order['paytype']])){
			$paytype=$pms_auth['paytype'][$order['paytype']];
		}else{
			$paytype=$pms_auth['paytype']['default'];
		}
		
		if('point'==$order['paytype']){
			$remark.='该订单已经为积分支付订单，积分：'.$order['point_used_amount'].'。';
		}
		
		$order_price=$order['price'];
		if(!empty($extra_info['point_rate'])&&$order['paytype']=='point'){
			$order_price=0;
		}
		
		$order_params = [
			// 'adults'   => 1,
			'arrDate'  => $startdate,
			'depDate'  => $enddate,
			'booker'   => $member && $member['pms_user_id'] ? $member['name'] : $order['name'],
			'bookTel'  => $member && $member['telephone'] ? $member['telephone'] : $order['tel'],
			'gstName'  => $order['name'],
			'gstTel'   => $order['tel'],
			'hotelId'  => $pms_set['hotel_web_id'],
			'rateCode' => $extra_info['pms_code'],
			
			'resType'   => $pms_auth['res_type']['no_pay'],
			//		    'status'=>$pms_auth['res_status'],
			
			//			'market'    => $member && $member['lvl_pms_code'] ? $member['lvl_pms_code'] : 'W',
			'market'    => $market,
			'rmQty'     => $order['roomnums'],
			'rmRate'    => $order_price,
			'rmType'    => $room_codes['room']['webser_id'],
			//		    'msgType'=>10,
			'cardNo'    => $member['pms_user_id'] ? $member['membership_number'] : '',
			'custId'    => $member['pms_user_id'],
			'nights'    => $countday,
			'remarks'   => $remark,
			'iscrmcust' => $member && $member['pms_user_id'] ? 'Y' : 'N',
			'paytype'   => $paytype
		];
		
		$result = $this->serv_api->submitOrder($order_params,['orderid'=>$order['orderid']]);
		
		if(0 == $result['result']){
			return [
				'result'      => true,
				'web_orderid' => $result['code'],
			];
		}
		return [
			'result' => false,
			'errmsg' => '提交订单失败，' . (isset($result['errorMsgZh']) ? $result['errorMsgZh'] : ''),
		];
	}
	
	public function update_web_order($inter_id, $order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		$istatus = -1;
		if(!empty($web_order)){
			$status_arr = $this->pms_enum('status');
			
			$this->load->model('hotel/Order_model');
			
			$status = $status_arr [$web_order['status']];
			
			$istatus = $status;
			
			if($order ['status'] == 4 && $status == 5){
				$status = 4;
			}
			
			// 未确认单先确认
			if($status != 0 && $order ['status'] == 0){
				$this->change_order_status($inter_id, $order['orderid'], 1);
				$this->Order_model->handle_order($inter_id, $order ['orderid'], 1, '', [
					'no_tmpmsg' => 1
				]);
			}
			
			$updata = [];
			
			$od = end($order['order_details']);
			
			if($od ['istatus'] == 4 && $istatus == 5){
				$istatus = 4;
			}
			
			//PMS上的入住，离店时间
			$web_start = date('Ymd', strtotime($web_order['arrDate']));
			$web_end = date('Ymd', strtotime($web_order['depDate']));
			$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
			
			//判断实际入住时间，订单记录的入住时间
			$ori_day_diff = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
			$web_day_diff = get_room_night($web_start,$web_end,'ceil',$od);//至少有一个间夜
			$day_diff = $web_day_diff - $ori_day_diff;
			
			$updata ['startdate'] = $web_start;
			$updata ['enddate'] = $web_end;
			
			if($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']){
				$updata ['no_check_date'] = 1;
				
				$allprice = explode(',', $od['allprice']);
				$night_price = end($allprice);
				$real_allprice = explode(',', $od['real_allprice']);
				$new_price = 0;
				for($i = 0; $i < $web_day_diff; $i++){
					$new_price += isset($real_allprice[$i]) ? $real_allprice[$i] : $night_price;
				}
				if($new_price >= 0 && $new_price != $od['iprice']){
					$updata['new_price'] = $new_price;
				}
			}
			
			/*	$new_price = $web_order['total'];
				if($new_price > 0){
					$updata ['no_check_date'] = 1;
					$updata['new_price'] = $new_price;
				}*/
			
			if($istatus != $od ['istatus']){
				$updata ['istatus'] = $istatus;
			}
			
			if(!empty ($updata)){
				$this->Order_model->update_order_item($inter_id, $order ['orderid'], $od ['sub_id'], $updata);
			}
			
		}
		
		return $istatus;
	}
	
	public function cancel_order_web($inter_id, $order, $pms_set = []){
		if(empty ($order ['web_orderid'])){
			return [
				's'      => 0,
				'errmsg' => '取消失败'
			];
		}
		
		$this->apiInit($pms_set);
		
		$res = $this->serv_api->cancelOrder($order['web_orderid'], '用户取消',['orderid'=>$order['orderid']]);
		
		if(0 == $res['result']){
			
			//退款
			if($order['paytype']=='weixin'&&$order['paid']==1){
				$this->load->model('hotel/Order_check_model');
				$this->Order_check_model->hotel_weixin_refund($order['orderid'], $inter_id, 'send');
			}
			return [                        //取消成功，直接这样return，接下来的程序会继续处理
			                                's'      => 1,
			                                'errmsg' => '取消成功'
			];
		}
		
		return [
			's'      => 0,
			'errmsg' => '取消失败,' . isset($res['errorMsgZh']) ? $res['errorMsgZh'] : '',
		];
		
	}
	
	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no,$paytype='WECHATPAY'){
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
		//查询网络订单是否存在
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
		
		//PMS上的入账接口
		$result = $this->serv_api->addPayment($web_orderid,$order['price'],$paytype,['orderid'=>$order['orderid']]);
		if(0 == $result['result']){
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
	
	
	
	private function change_order_status($inter_id, $orderid, $status){
		$this->db->where([
			'orderid'  => $orderid,
			'inter_id' => $inter_id
		]);
		$this->db->update('hotel_orders', [ // 提交失败，把订单状态改为下单失败
		                                    'status' => (int)$status
		]);
	}
	
	function pms_enum($type){
		switch($type){
			case 'status' :
				return [
					/*resstat  等候  W  Wait List
resstat  取消  X  Cancel
resstat  归档  H  History
resstat  在住  I  In House
resstat  离店  O  Checked Out
resstat  预订  R  Reservation
resstat  未到  N  No Show   */
					//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
					
					'X' => 5,
					'H' => 3,
					'I' => 2,
					'O' => 3,
					'R' => 1,
					'N' => 8,
					'W' => 0,
				];
				break;
			default :
				return [];
				break;
		}
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
	
	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		if($web_order){
			$status_arr = $this->pms_enum('status');
			$status = $status_arr [$web_order['status']];
		}
		if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
			return true;
		} else{
			return false;
		}
	}
	
	private function apiInit($pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$pms_auth['inter_id'] = $pms_set['inter_id'];
		
		$this->load->library('Baseapi/Qianlimaapi', $pms_auth, 'serv_api');
		
	}
	
	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
}