<?php

class Xiruan_hotel_model extends MY_Model{
	public function __construct(){
		parent::__construct();
		$this->load->helper('common');
	}
	
	public function get_rooms_change($rooms, $idents, $condit, $pms_set = []){
		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], [
			'web_price_code_set',
			'rate_to_point',
			'point_rate_related',
			'point_rate_code',
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
			if(!empty ($web_reflect ['web_price_code'])){
				foreach($web_reflect ['web_price_code'] as $wpc){
					$web_price_code .= ',' . $wpc;
				}
			}
			$web_price_code .= isset ($web_reflect ['member_price_code'] [$member_level]) ? ',' . $web_reflect ['member_price_code'] [$member_level] : '';
			$web_price_code = substr($web_price_code, 1);
		}
		$web_price_code = explode(',', $web_price_code);
		$countday = get_room_night($condit ['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$web_rids = [];
		foreach($rooms as $r){
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		
		//是否积分房
		$point_rate_code = '';
		if(!empty($web_reflect['point_rate_code'])){
			foreach($web_reflect['point_rate_code'] as $v){
				$point_rate_code .= ',' . $v;
			}
			$point_rate_code = substr($point_rate_code, 1);
		}
		$point_rate_code = explode(',', $point_rate_code);
		
		$params = [
			'countday'        => $countday,
			'web_rids'        => $web_rids,
			'condit'          => $condit,
			'web_reflect'     => $web_reflect,
			'member_level'    => $member_level,
			'idents'          => $idents,
			'point_rate_code' => $point_rate_code,
		];
		//		$web_price_code = [1, 2, 3, 4];
		
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
								foreach($tmp[$w] as $dk => $td){
									if($si['related_cal_way'] && $si['related_cal_value']){
										$tmp[$w][$dk]['price'] = round($this->Order_model->cal_related_price($td['price'], $si['related_cal_way'], $si['related_cal_value'], 'price'));
									} else{
										$tmp[$w][$dk]['price'] = $td['price'];
									}
									$tmp[$w][$dk]['nums'] = $tmp['least_num'];
									$allprice .= ',' . $tmp[$w][$dk]['price'];
									$amount += $tmp[$w][$dk]['price'];
								}
								
								$data[$room_key]['state_info'][$sik]['avg_price'] = number_format($amount / $params['countday']);
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
//		echo '<pre>';print_r($data);exit;
		
		
		return $data;
	}
	
	public function update_web_order($inter_id, $order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		$istatus = -1;
		if(!empty ($web_order)){
			$status_arr = $this->pms_enum('status');
//			$main_order = $web_order['main'];
//			$sub_order = $web_order['sub'];
			
			$this->load->model('hotel/Order_model');
			
			$status = $status_arr [$web_order['sta']];
			
			// 未确认单先确认
			if($status != 0 && $order ['status'] == 0){
				$this->change_order_status($inter_id, $order['orderid'], 1);
				$this->Order_model->handle_order($inter_id, $order ['orderid'], 1, '', [
					'no_tmpmsg' => 1
				]);
			}
			
			$od=end($order['order_details']);
			
			$updata = [];
			$istatus = $status_arr[$web_order['sta']];
			if($od ['istatus'] == 4 && $istatus == 5){
				$istatus = 4;
			}
			
			//PMS上的入住，离店时间
			$web_start = date('Ymd', strtotime($web_order['arr']));
			$web_end = date('Ymd', strtotime($web_order['dep']));
			$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
			
			//判断实际入住时间，订单记录的入住时间
			$ori_day_diff = get_room_night($od['startdate'],$od['enddate'],'ceil',$od);//至少有一个间夜
			$web_day_diff = get_room_night($web_start,$web_end,'ceil');//至少有一个间夜
			$day_diff = $web_day_diff - $ori_day_diff;
			
			$updata ['startdate'] = $web_start;
			$updata ['enddate'] = $web_end;
			if($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']){
				$updata ['no_check_date'] = 1;
			}
			
			$new_price = $web_order['paymoney'];
			
			if($new_price >= 0 && $new_price != $od['iprice']){
				$updata['no_check_date'] = 1;
				$updata['new_price'] = $new_price;
			}
			
			if($istatus != $od ['istatus']){
				$updata ['istatus'] = $istatus;
			}
			
			if(!empty ($updata)){
				$this->Order_model->update_order_item($inter_id, $order ['orderid'], $od ['sub_id'], $updata);
			}
		}
		return $istatus;
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
	
	public function cancel_order_web($inter_id, $order, $pms_set = []){
		if(empty ($order ['web_orderid'])){
			return [
				's'      => 0,
				'errmsg' => '取消失败'
			];
		}
		
		//判断订单时间
		$intime = strtotime($order['startdate']);
		
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', 0, 'OUT_TIME');
		
		$out_time = array();
		if(!empty($config_data['OUT_TIME'])){
			$out_time = json_decode($config_data['OUT_TIME'],true);
		}
		
		if(isset($out_time[$order ['first_detail']['price_code']])){
			
			$timelimit = $out_time[$order ['first_detail']['price_code']];
			
		}elseif(isset($out_time['other'])){
			
			$timelimit = $out_time['other'];
			
		}else{
			
			$timelimit = 12;//默认超过入住时间当天的12点时不能取消
			
		}
		if(mktime($timelimit, 0, 0, date('m', $intime), date('d', $intime), date('Y', $intime)) < time() && $this->uri->segment(3)!= 'deal_order_queues' && $order['status']!=9){
			//时间超过入住时间当天的限制时间不能取消
			return array(
				's'      => 0,
				'errmsg' => '只能在入住当天'.$timelimit.'点前取消订单！',
			);
		}
		
		$this->apiInit($pms_set);
		
		$res = $this->serv_api->cancelOrder($pms_set['hotel_web_id'], $order['web_orderid'], '用户取消',null,['orderid'=>$order['orderid']]);
		
		if(!empty($res['success'])){
			//到店也能拉起微信支付，只要支付而且非积分支付则可退款
			if(($order['paytype'] == 'weixin' || $order['paytype'] == 'daofu' ) && $order['paid'] ==1 ){
				//微信已付款才可申请退款
				$this->load->model('hotel/Order_check_model');
				$this->Order_check_model->hotel_weixin_refund($order['orderid'], $inter_id,'send');
			}
			
			return [                        //取消成功，直接这样return，接下来的程序会继续处理
			                                's'      => 1,
			                                'errmsg' => '取消成功'
			];
		}
		
		return [
			's'      => 0,
			'errmsg' => '取消失败,' . isset($res['msg']) ? $res['msg'] : '',
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
		$this->apiInit($pms_set);
		$pms_auth=json_decode($pms_set['pms_auth'],true);
		
		$starttime = strtotime($order['startdate']);
		$endtime = strtotime($order['enddate']);
		
		if(ceil(($endtime - time()) / 86400) > 30){
			return [
				'result' => 0,
				'errmsg' => '最多可预订未来30天的房',
			];
		}
		
		//积分支付时，只能下一个订单
		$config_data = $this->Hotel_config_model->get_hotel_config($pms_set['inter_id'], 'HOTEL', $order ['hotel_id'], ['POINT_ORDER_LIMIT']);
		if($order['paytype']=='point'&&!empty($config_data['POINT_ORDER_LIMIT'])){
			//判断当前会员下的订单
			$map = array(
				'member_no'    => $order['member_no'],
				'inter_id'     => $pms_set['inter_id'],
				'order_time>=' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
				'order_time<=' => mktime(23, 59, 59, date('m'), date('d'), date('Y')),
//				'order_id!='   => $order['orderid'],
			);
			
			//订单状态,0预订，1确认，2入住，3离店，4用户取消，5酒店取消,6酒店删除，7异常，8未到，9待支付
			
			$order_count = $this->readDB()->from('hotel_orders')->select("count(*) as total,name")->where($map)->where_not_in('status', [4,5,10,11])->count_all_results();
			
			if($order_count>$config_data['POINT_ORDER_LIMIT']){
				return [
					'result'=>0,
				    'errmsg'=>'仅限每天'.$config_data['POINT_ORDER_LIMIT'].'张积分支付订单',
				];
			}
		}
		
		$startdate = date('Y-m-d', $starttime) . 'T12:00:00';
		$enddate = date('Y-m-d', $endtime) . 'T12:00:00';
		
		$room_codes = json_decode($order ['room_codes'], true);
		$room_codes = $room_codes [$order ['first_detail'] ['room_id']]; //$room_codes 结构：array('本地room_id'=>array('room'=>array('webser_id'=>房型代码),'code'=>array($extra_info(就是取房态时的 extra_info),'price_type'=>'价格类型')))
		$extra_info = $room_codes['code']['extra_info'];
		
		$member = $this->vm->getUserInfo($order['openid'], $order['inter_id']);
		
		$remark = '';
		$pms_coupons = '';
		$local_coupon_amount=0;//本地券金额
		
		if($order ['coupon_favour'] > 0){
			$remark .= '使用优惠券：' . $order ['coupon_favour'] . '元。';
			$coupon_arr = [];
			$coupon_des = json_decode($order['coupon_des'], true);
			if(array_key_exists('cash_token', $coupon_des)){
				$coupon_count = count($coupon_des['cash_token']);
				for($i = 0; $i < $coupon_count; $i++){
					$t = $coupon_des['cash_token'][$i];
					if(!empty($t['extra']['ascription'])&&$t['extra']['ascription']=='pms'){
						$coupon_arr[] = $t['code'];
					}else{
					    $local_coupon_amount+= $t['amount'];
					}
				}
			}
			$pms_coupons = implode(',', $coupon_arr);
		}
		
		$daily_price = explode(',', $order ['first_detail'] ['allprice']);
		
		$order_params = [
			'ratecode' => $extra_info['pms_code'],
			'rmtype'   => $room_codes['room']['webser_id'],
			'rmnum'    => $order['roomnums'],
			'rate'     => $daily_price[0],
			'hotelid'  => $pms_set['hotel_web_id'],
			'name'     => $order['name'],
			'mobile'   => $order['tel'],
			'arr'      => $startdate,
			'dep'      => $enddate,
			'gstno'    => 1,
			'cardno'   => $member ? $member['pms_user_id'] : null,
			'ref'      => $remark,
			'coupon'   => $pms_coupons,  //优惠券
			'crsno'    => $order['orderid'],
		];
		
		
		if('weixin' == $order['paytype']){
			$order_params['payment'] = '微信支付';
//			$order_params['restype'] = 5;
		}
		
		//属于本地券优惠金额
		if($local_coupon_amount>0){
			$this->load->model('common/Webservice_model');
			$web_reflect = $this->Webservice_model->get_web_reflect($order['inter_id'], $order['hotel_id'], $pms_set['pms_type'], ['coupon_promcode'], 1, 'w2l');
			if(!empty($web_reflect['coupon_promcode'][$local_coupon_amount])){
				$order_params['promcode']=$web_reflect['coupon_promcode'][$local_coupon_amount];
			}
		}
		
		//属于积分换房的
		if($extra_info['is_pms_point'] && 'point' == $order['paytype']){
			$order_params['restype'] = 8;
			$order_params['ref'].='积分兑换，整晚保留，取消订单积分不退';
			$order_params['promcode']=$pms_auth['point_procode'];
		}
		
		$result = $this->serv_api->saveOrder($order_params,['orderid'=>$order['orderid']]);
		
		
		if(!empty($result['success'])){
			return [
				'result'      => 1,
				'web_orderid' => $result['result']['rsvno'],
			];
		} else{
			return [
				'result' => 0,
				'errmsg' => isset($result['result']['msg']) ? $result['result']['msg'] : '',
			];
		}
	}
	
	public function add_web_bill($web_orderid, $order, $pms_set, $trans_no){
		$web_paid = 2;
		$pms_auth=json_decode($pms_set['pms_auth'],true);
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
		$payment = [
			'rsvno'     => $web_orderid,
			'pay_money' => $order['price'],
			'pay_code'  => isset($pms_auth['pay_code'][$order['paytype']])?$pms_auth['pay_code'][$order['paytype']]:$pms_auth['pay_code']['weixin'],
			'payno'     => $trans_no,
			'remark'    => '订单预付：' . $web_orderid,
			//			'hotelid'   => $web_order['hotelid']
		];
		$result = $this->serv_api->addPayment($payment,['orderid'=>$order['orderid']]);
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
		return $web_paid==1;
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
				$upstatus=null;
				$has_paid=null;
				$web_orderid = $result['web_orderid'];            //取得返回的pms订单id
				$this->db->where([
					'orderid'  => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				]);
				$this->db->update('hotel_order_additions', [        //更新pms单号到本地
				                                                    'web_orderid' => $web_orderid
				]);
				
				//更新子订单
				//每次只能下一张订单
				$child_list = $this->readDB()->where(array(
					'orderid'  => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				))->from('hotel_order_items')->select('id')->get()->result_array();
				$child=array_pop($child_list);
				if($child){
					$this->db->where(array('id' => (int)$child['id']))->update('hotel_order_items', array('webs_orderid' => $web_orderid));
				}
				
				if($order['status'] != 9){
					$upstatus=1;
//					$this->change_order_status($inter_id, $orderid, 1);
//					$this->Order_model->handle_order($inter_id, $orderid, 1); // 若pms的订单是即时确认的，执行确认操作，否则省略这一步
				}
				
				//积分房扣除积分
				if($order['paytype'] == 'point'){
					$this->load->model('hotel/Hotel_config_model');
					$config_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $order ['hotel_id'], array(
						'PMS_POINT_REDUCE_WAY'
					));
					if(!empty ($config_data ['PMS_POINT_REDUCE_WAY']) && $config_data ['PMS_POINT_REDUCE_WAY'] == 'after'){
						$point_param = [
							'extra' => [
								'pms_order_id' => $web_orderid,
								'pms_hotel_id' => $pms_set['hotel_web_id'],
							]
						];
						
						$this->load->model('hotel/Member_model');
						if(!$this->Member_model->consum_point($order ['inter_id'], $order ['orderid'], $order ['openid'], $order ['point_used_amount'], $point_param)){
							$this->Order_model->update_point_reduce($inter_id, $order['orderid'], 3);
							$info = $this->Order_model->cancel_order($inter_id, [
								'only_openid'   => $order ['openid'],
								'member_no'     => '',
								'orderid'       => $order ['orderid'],
								'cancel_status' => 5,
								'no_tmpmsg'     => 1,
								'delete'        => 2,
								'idetail'       => [
									'i'
								]
							]);
							return [
								's'      => 0,
								'errmsg' => '积分支付失败'
							];
						} else{
							$has_paid=1;
//							$this->Order_model->update_order_status($order['inter_id'], $order['orderid'], 1, $order['openid'], true, true);
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
			'errmsg' => '提交订单失败!'
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
	
	public function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params){
		set_time_limit(50);
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$this->apiInit($pms_set);
		
		if(count($params['web_rids']) == 1){
			$web_room_arr = array_keys($params['web_rids']);
			$web_room = $web_room_arr[0];
		} else{
			$web_room = null;
		}
		
		/*if(count($web_price_code) == 1){
			$web_rate = $web_price_code[0];

			/*if(!empty($params['point_rate_code']) && in_array($web_rate, $params['point_rate_code']) && !empty($params['web_reflect']['rate_to_point'])){
				$tmp=$params['web_reflect']['rate_to_point'];
				$web_rate.=','.current($tmp);
			}*
//			exit($web_rate);

		} else{
			$web_rate = null;
		}*/
		$web_rate = null;
		$func_data=['hotel_id'=>$params['idents']['hotel_id']];
		$qty_result = $this->serv_api->getRoomQty($pms_set['hotel_web_id'], $startdate, $enddate, $web_room, $web_rate,$func_data);
		$daily_result = $this->serv_api->getRoomDailyPrice($pms_set['hotel_web_id'], $startdate, $enddate, $web_room, $web_rate,$func_data);
		
		//房量
		$web_room_qty = [];
		foreach($qty_result as $v){
			$web_room_qty[$v['rmtype']][date('Ymd', strtotime($v['date']))] = $v['avail'];
		}
		
		$countday = get_room_night($startdate,$enddate,'ceil');//至少有一个间夜
		
		//每日房价
		$web_room_rate = [];
		foreach($daily_result as $v){
			if(!empty($params['point_rate_code']) && in_array($v['ratecode'], $params['point_rate_code']) && $countday > 1){
				continue;
			}
			$in_data = date('Ymd', strtotime($v['date']));
			$v['in_date'] = $in_data;
			$v['avail'] = isset($web_room_qty[$v['rmtype']][$in_data]) ? $web_room_qty[$v['rmtype']][$in_data] : 0;
			if(!empty($params['web_reflect']['rate_to_point'])&&!empty($params['point_rate_code']) && in_array($v['ratecode'], $params['point_rate_code'])){
				$v['is_point_rate'] = true;
				$rate_to_point=end($params['web_reflect']['rate_to_point']);
				foreach($daily_result as $t){
					if($t['rmtype']==$v['rmtype']&&$t['ratecode']==$rate_to_point&&$v['date']==$t['date']){
						$v['rate']=$t['rate'];
						break;
					}
				}
			}
			if(!$v['rate']){
				continue;
			}
			$web_room_rate[$v['rmtype']][$v['ratecode']][] = $v;
			//判断该价格是否用于计算积分坐价
			/*if($countday == 1 && !empty($params['web_reflect']['rate_to_point']) && in_array($v['ratecode'], $params['web_reflect']['rate_to_point'])){
				if(!empty($params['web_reflect']['point_rate_related'][$pms_auth['mode']])){
					$point_rate = $params['web_reflect']['point_rate_related'][$pms_auth['mode']];
					$v['ratecode'] = $point_rate;
					$v['is_point_rate'] = true;
					$web_room_rate[$v['rmtype']][$v['ratecode']][] = $v;
				}
			}*/
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
					
					$pms_state[$web_room][$web_rate]['price_name'] = $web_rate;
					$pms_state[$web_room][$web_rate]['price_type'] = 'pms';
					$pms_state[$web_room][$web_rate]['price_code'] = $web_rate;
					$pms_state[$web_room][$web_rate]['extra_info'] = [
						'type'         => 'code',
						'pms_code'     => $web_rate,
						'pkg'          => isset($row['pkg']) ? $row['pkg'] : '',
						'is_pms_point' => !empty($row['is_point_rate']),
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
					
					foreach($t as $w){
						if($w['rate']<=0){
							continue;
						}
						
						$pms_state[$web_room][$web_rate]['date_detail'][$w['in_date']] = [
							'price' => $w['rate'],
							'nums'  => $w['avail'],
						];
						
						$allprice[$w['in_date']] = $w['rate'];
						$amount += $w['rate'];
						$least_arr[] = $w['avail'];
						
						$date_status = $date_status && ($w['avail'] > 0)&&$w['rate']>0;
						
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
	
	//判断订单是否能支付
	function check_order_canpay($order, $pms_set){
		$this->apiInit($pms_set);
		$web_order = $this->serv_api->getOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
		if($web_order){
			$status_arr = $this->pms_enum('status');
			$status = $status_arr [$web_order['sta']];
		}
		if(isset($status) && ($status == 1 || $status == 0)){//订单预定或确认
			return true;
		} else{
			return false;
		}
	}
	
	function point_pay_check($inter_id, $params = [], $pms_set = []){
		
		$this->load->model('hotel/Hotel_config_model');
		$cfg_data = $this->Hotel_config_model->get_hotel_config($inter_id, 'HOTEL', $params['hotel_id'], array(
			'POINT_EXCHANGE_ROOM',
		));
		$result = ['can_exchange' => 0, 'pay_set' => [], 'point_need' => 0, 'des' => ''];
		if(empty($cfg_data['POINT_EXCHANGE_ROOM'])){
			$result['can_exchange'] = 0;
			$result['errmsg'] = '参数错误';
			return $result;
		}
		$cfg = json_decode($cfg_data['POINT_EXCHANGE_ROOM'], true);
		
		//判断入住天数
		if($params['countday'] > $cfg['orderday']){
			$result['can_exchange'] = 0;
			$result['errmsg'] = '只能预订' . $cfg['orderday'] . '天的积分房';
			return $result;
		}
		//判断预订房间
		if($params['roomnums'] > $cfg['roomnums']){
			$result['can_exchange'] = 0;
			$result['errmsg'] = '每次只能预订' . $cfg['roomnums'] . '间积分房';
			return $result;
		}
		
		//仅显示时，不需要验证价格代码
		if(empty($params['only_show'])){
			//判断价格代码
			//对接模式是本地价格代码时，读取对应的external_code值【PMS价格代码】
			if($pms_set['pms_room_state_way'] == 3 || $pms_set['pms_room_state_way'] == 4){
				$price_row = $this->readDB()->from('hotel_price_info')->select('external_code')->where(['inter_id' => $pms_set['inter_id']])->where(['price_code' => $params['price_code']])->get()->row_array();
				$params['price_code'] = $price_row['external_code'];
			}
			
			
			$this->load->model('common/Webservice_model');
			$web_reflect = $this->Webservice_model->get_web_reflect($inter_id, $params['hotel_id'], $pms_set['pms_type'], [
				'point_rate_related'
			], 1, 'w2l');
			
			$point_rate = '';
			if(!empty($web_reflect['point_rate_related'])){
				foreach($web_reflect['point_rate_related'] as $v){
					$point_rate .= ',' . $v;
				}
			}
			
			$point_rate_arr = explode(',', substr($point_rate, 1));
			if(!in_array($params['price_code'], $point_rate_arr)){
				$result['can_exchange'] = 0;
				$result['errmsg'] = '不用兑换的价格代码';
				return $result;
			}
		}
		//计算换房积分
		$price = $params['total_price'];
		$result['pay_set']['ex_way'] = 'rate';
		if(date('N',strtotime($params['startdate'])) == 1){
			//周一会员日
			//判断是否已有积分换房订单
			$map = array(
				'o.openid'    => $params['openid'],
				'o.inter_id'  => $inter_id,
				'o.startdate' => date('Ymd', strtotime($params['startdate'])),
			);
			$exists_list = $this->readDB()->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid')->select("oa.room_codes")->where($map)->where_not_in('o.status', array(
				4,
				5,
				10,
				11
			))->get()->result_array();
			foreach($exists_list as $v){
				$room_codes = json_decode($v['room_codes'], true);
				if(!empty($room_codes[$params['room_id']]['code']['extra_info'])){
					$extra_info = $room_codes[$params['room_id']]['code']['extra_info'];
					if(!empty($extra_info['is_pms_point'])){
						$result['can_exchange'] = 0;
						$result['errmsg'] = '会员日只能预订一次积分房';
						return $result;
						break;
					}
				}
				
			}
		}
		
		$point_need=$this->price2Point($price,$params['startdate']);
		
		$result['point_need'] = $point_need;
		
		$result['can_exchange'] = $params['bonus'] >= $point_need ? 1 : 0;
		$result['des'] = $result['point_need'] . '/' . $params['bonus'];
		
		if($params['bonus'] < $point_need){
			$result['errmsg'] = '积分不足以支付';
		}
		
		return $result;
		
	}
	
	protected function price2Point($price, $startdate){
		if(date('N',strtotime($startdate)) == 1){
			if($price <= 200){
				$point_need = 5000;
			} elseif($price <= 300){
				$point_need = 8000;
			} elseif($price <= 400){
				$point_need = 12000;
			} elseif($price <= 500){
				$point_need = 15000;
			} else{
				$point_need = 18000;
			}
		}else{
			if($price <= 200){
				$point_need = 8000;
			} elseif($price <= 300){
				$point_need = 12000;
			} elseif($price <= 400){
				$point_need = 15000;
			} elseif($price <= 500){
				$point_need = 18000;
			} else{
				$point_need = 30000;
			}
		}
		return $point_need;
	}
	
	private function apiInit($pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		
		$conf = array(
			'inter_id'     => $pms_set['inter_id'],
			'user'         => $pms_auth['user'],
			'pwd'          => $pms_auth['pwd'],
			'url'          => $pms_auth['url'],
			'cmmcode'      => $pms_auth['cmmcode'],
			'channel'      => $pms_auth['channel'],
			'sign_hotelid' => $pms_auth['sign_hotelid'],
		);
		$this->load->library('Baseapi/Xiruanapi', $conf, 'serv_api');
	}
	
	function get_order_state($order, $pms_set,$status_des=array()) {
		$state = array ();
		if ($order ['handled'] == 0 && ! empty ( $order ['web_orderid'] )) {
			$this->apiInit($pms_set);
			$web_order = $this->serv_api->queryOrder($order['web_orderid'],['orderid'=>$order['orderid']]);
			
			if (! empty ( $web_order )) {
				$status_arr = $this->pms_enum('status');
				$istatus = $status_arr[$web_order['sta']];
				if($order['paytype'] != 'point' && !$order['paid'] && in_array($istatus, array(0, 1))){
					$re_pay = 1;
				} else{
					$re_pay = null;
				}
//				$re_pay=in_array($istatus, array(0,1))?1:NULL;
				
				$state ['can_cancel'] = $this->check_cancel_time($order['inter_id'], $order)===TRUE?NULL:0;
				$state ['re_pay'] = $re_pay;
				$state ['web_check'] = NULL;
				$state ['web_des'] = NULL;
				$state ['web_comment'] = NULL;
			}
		}
		return $state;
	}
	
	function check_cancel_time($inter_id,$order){
		//判断订单时间
		$intime = strtotime($order['startdate']);
		
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', 0, 'OUT_TIME');
		
		$out_time = array();
		if(!empty($config_data['OUT_TIME'])){
			$out_time = json_decode($config_data['OUT_TIME'],true);
		}
		
		if(isset($out_time[$order ['first_detail']['price_code']])){
			
			$timelimit = $out_time[$order ['first_detail']['price_code']];
			
		}elseif(isset($out_time['other'])){
			
			$timelimit = $out_time['other'];
			
		}else{
			
			$timelimit = 12;//默认超过入住时间当天的12点时不能取消
			
		}
		if(mktime($timelimit, 0, 0, date('m', $intime), date('d', $intime), date('Y', $intime)) < time() && $this->uri->segment(3)!= 'deal_order_queues' && $order['status']!=9){
			//时间超过入住时间当天的限制时间不能取消
			return array(
				's'      => 0,
				'errmsg' => '只能在入住当天'.$timelimit.'点前取消订单！',
			);
		}
		return TRUE;
	}
	
	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
}