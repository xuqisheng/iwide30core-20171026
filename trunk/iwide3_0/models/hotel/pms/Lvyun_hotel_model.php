<?php
class Lvyun_hotel_model extends CI_Model {
	const TAB_HO = 'hotel_orders';
	const WEB_TYPE = 'lvyun';
	function __construct() {
		parent::__construct ();
		$this->load->helper('common');
	}

	public function get_rooms_change($rooms, $idents = array(), $condit = array(), $pms_set = array()) {
		$days = get_room_night($condit ['startdate'],$condit ['enddate'],'round',$condit);//至少有一个间夜
		$web_rids = array ();
		foreach ( $rooms as $r ) {
			$web_rids [$r ['webser_id']] = $r ['room_id'];
		}
		$this->load->model ( 'common/Webservice_model' );
		$level_reflect = $this->Webservice_model->get_web_reflect ( $idents ['inter_id'], $idents ['hotel_id'], $pms_set ['pms_type'], array (
			'member_level',
			'member_price_code',
			'web_price_code',
			'price_code_set',
			'members_price_code',
			'basic_price_code'
		), 1, 'w2l' );
		
		$pms_auth=json_encode($pms_set['pms_auth'],true);

		switch($pms_set['inter_id']){
			case 'a468224499':
			case 'a487647571': //万信测试
			case 'a487576098': //万信正式
				$this->load->model('api/Vmember_model','vm');
				$member_level=$this->vm->getLvlPmsCode($condit['openid'],$idents['inter_id']);
				break;
			default:
				if(isset($level_reflect['member_level'])){
					$mem_levels = array_flip($level_reflect ['member_level']);
					$member_level = isset ($mem_levels [$condit ['member_level']]) ? $mem_levels [$condit ['member_level']] : '';
				}else{
					$member_level='';
				}
				break;
		}
		if(!empty($pms_auth['new_level'])){
			$this->load->model('api/Vmember_model','vm');
			$member_level=$this->vm->getLvlPmsCode($condit['openid'],$idents['inter_id']);
		}
		$level_price_code = '';
		if ($pms_set ['pms_room_state_way'] != 3) {
    		$level_price_code = isset ( $level_reflect ['member_price_code'] [$member_level] ) ? $level_reflect ['member_price_code'] [$member_level] : NULL;
    		if (empty ( $level_price_code ) && ! empty ( $level_reflect ['member_price_code'] ['default_member_price_code'] )) {
    			$level_price_code = $level_reflect ['member_price_code'] ['default_member_price_code'];
    		}
    		if (! empty ( $level_reflect ['web_price_code'] )) {
    			foreach ( $level_reflect ['web_price_code'] as $wpc ) {
    				$level_price_code .= ',' . $wpc;
    			}
    		}
		}
		$code_config = array ();
		if (! empty ( $level_reflect ['price_code_set'] )) {
			foreach ( $level_reflect ['price_code_set'] as $mk => $mc ) {
				$code_config [$mk] = json_decode ( $mc, TRUE );
			}
		}
		$params = array (
			'idents' => $idents,
			'condit' => $condit,
			'web_rids' => $web_rids,
			'web_code' => $level_price_code,
			'member_level' => $member_level,
			'code_config' => $code_config,
		    'enddate' => $condit ['enddate']
		);
		$pms_data = $this->get_web_roomtype ( $pms_set, $condit ['startdate'], $days, $params );
		$data=[];
		if (! empty ( $pms_data )) {
			$allprice = array ();
			$this->load->model ( 'hotel/Member_model' );
			$levels = $this->Member_model->get_member_levels ( $idents ['inter_id'] );
			switch ($pms_set ['pms_room_state_way']) {
				case 1 :
				case 2 :
					$data= $this->get_rooms_change_allpms ( $pms_data, array (
						'rooms' => $rooms
					), array (
						                                        'member_level' => $member_level,
						                                        'levels' => $levels,
						                                        'days' => $days,
						                                        'idents' => $idents,
						                                        'condit' => $condit
					                                        ) );
					break;
				case 3 :
					$extra_price_code=array();
					$pms_auth=json_decode($pms_set['pms_auth'],TRUE);
					if (isset($pms_auth['member_mulprice'])&&$pms_auth['member_mulprice']==1){
						$this->load->model('hotel/Member_new_model');
						$web_member=$this->Member_new_model->get_pms_member($idents ['inter_id'] ,$condit['openid']);
						if (!empty($web_member)&&!empty($web_member['rateCode'])){
							$extra_price_code=explode(',', $web_member['rateCode']);
						}
					}
					$data= $this->get_rooms_change_lmem ( $pms_data, array (
						'rooms' => $rooms
					), array (
						                                      'member_level' => $member_level,
						                                      'levels' => $levels,
						                                      'days' => $days,
						                                      'idents' => $idents,
						                                      'condit' => $condit,
						                                      'field_config' => $level_reflect,
						                                      'inter_id'=>$pms_set['inter_id'],
															  'extra_price_code'=>$extra_price_code,
					                                          'pms_auth'=>$pms_auth
					                                      ) );
					break;
			}
		}
		return $data;
	}
	function get_rooms_change_allpms($pms_state, $rooms, $params) {
		$data = array ();
		foreach ( $rooms ['rooms'] as $rm ) {
			if (! empty ( $pms_state ['pms_state'] [$rm ['webser_id']] )) {
				$data [$rm ['room_id']] ['room_info'] = $rm;
				// $data [$rm ['room_id']] ['state_info'] = empty ( $pms_state ['valid_state'] [$rm ['webser_id']] ) ? array () : $pms_state ['valid_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['state_info'] = $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['show_info'] = array ();
				$data [$rm ['room_id']] ['lowest'] = min ( $pms_state ['exprice'] [$rm ['webser_id']] );
				$data [$rm ['room_id']] ['highest'] = max ( $pms_state ['exprice'] [$rm ['webser_id']] );
			}
		}
		return $data;
	}
	function get_rooms_change_lmem($pms_data, $rooms, $params) {
		$data = array ();
		$local_rooms = $rooms ['rooms'];
		$member_level = $params ['member_level'];
		$levels = $params ['levels'];
		if (!empty($params['condit']['is_comprice'])&&!empty($params['pms_auth']['com_memberprice'])){
		    $this->db->where(array('status'=>1,'inter_id'=>$params['idents']['inter_id']));
		    $this->db->like('use_condition','member_level');
		    $member_related_code=$this->db->get('hotel_price_info')->result_array();
		    $condit_codes=empty($member_related_code)?'':implode(',', array_column($member_related_code, 'price_code'));
		    $params['condit']['extra_price_code']=empty($params['condit']['extra_price_code'])?$condit_codes:$params['condit']['extra_price_code'].$condit_codes;
		    $params['condit']['no_check_memberlv']=1;
		}
		$condit = $params ['condit'];
		$this->load->model ( 'hotel/Order_model' );
		$data = $this->Order_model->get_rooms_change ( $local_rooms, $params ['idents'], $params ['condit'] );
		$pms_state = $pms_data ['pms_state'];
		$this->load->model ( 'hotel/Order_model' );
		$members_price_code = empty ( $params ['field_config'] ['members_price_code'] ['all'] ) ? array () : explode ( ',', $params ['field_config'] ['members_price_code'] ['all'] );
		$basic_price_code=!empty($params['field_config']['basic_price_code'])?$params['field_config']['basic_price_code']:[];
		foreach ( $data as $room_key => $lrm ) {
			$web_info = array ();
			$webps = array ();
			$min_price = array ();
			$room_price_codes=array();
			if (! empty ( $lrm ['state_info'] )) {
				foreach ( $lrm ['state_info'] as $sik => $si ) {
					//检查member_price_code是否存在该等级PMS代码配对值
					if($si ['external_code'] !== '' && !empty($basic_price_code[$si['external_code']])){
						$si['external_code'] = $basic_price_code[$si['external_code']];
					}

					if ($si ['external_code'] !== '' && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$si ['external_code']] )) {
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						//云盟房型库存处理
						if($params['inter_id']=='a445223616'){
							if ($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member') {
								$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
								$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
							} else {
								$data [$room_key] ['state_info'] [$sik] ['least_num'] = $data [$room_key] ['state_info'] [$sik] ['least_num'] <= $tmp ['least_num'] ? $data [$room_key] ['state_info'] [$sik] ['least_num'] : $tmp ['least_num'];
								$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
								if ($data [$room_key] ['state_info'] [$sik] ['least_num'] <= 0) {
									$data [$room_key] ['state_info'] [$sik] ['book_status'] = 'full';
								}
							}
						}else{
							$data [$room_key] ['state_info'] [$sik] ['least_num'] = $tmp ['least_num'];
							$data [$room_key] ['state_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						}
						$allprice = '';
						$amount = 0;
						foreach ( $tmp ['date_detail'] as $dk => $td ) {
							//云盟
							if($params['inter_id'] == 'a445223616'){
								if($data [$room_key] ['state_info'] [$sik] ['price_type'] == 'member' || (!empty($si ['related_code']))){
									$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'));
								} else{
									$tmp ['date_detail'] [$dk] ['price'] = round($data [$room_key] ['state_info'] [$sik] ['date_detail'] [$dk] ['price']);
								}
								$tmp ['date_detail'] [$dk] ['nums'] = $data [$room_key] ['state_info'] [$sik] ['least_num'];
							} else{
							    //智能定价优先
							    if (!empty($data [$room_key] ['state_info'] [$sik] ['date_detail'][$dk]['type'])&&$data [$room_key] ['state_info'] [$sik] ['date_detail'][$dk]['type']=='parity'){
							        $tmp ['date_detail'] [$dk] ['price']=$data [$room_key] ['state_info'] [$sik] ['date_detail'][$dk]['price'];
							    	$tmp ['extra_info']['parity'][$dk]=$tmp ['date_detail'] [$dk] ['price'];
							    }else if(!empty($si ['related_cal_way']) && !empty($si ['related_cal_value'])){
									$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'),2);
								} else{
									$tmp ['date_detail'] [$dk] ['price'] = $td['price'];
								}
								$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
							}
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['state_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
						$data [$room_key] ['state_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];
						empty ( $tmp ['des'] ) ?  : $data [$room_key] ['state_info'] [$sik] ['des'] = $tmp ['des'];
						// empty($tmp ['price_name'])?:$data [$room_key] ['state_info'] [$sik] ['price_name'] = $tmp ['price_name'];

						$data [$room_key] ['state_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 2,'.','' );
						$data [$room_key] ['state_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['state_info'] [$sik] ['total'] =  $amount * 1;
						$data [$room_key] ['state_info'] [$sik] ['total_price'] = $data [$room_key] ['state_info'] [$sik] ['total'] * $nums;
						$min_price [] = $data [$room_key] ['state_info'] [$sik] ['avg_price'];
						$room_price_codes[]=$si['external_code'];
					} else {
						unset ( $data [$room_key] ['state_info'] [$sik] );
					}
				}
			}
			if(!empty ($pms_state [$lrm ['room_info'] ['webser_id']])){
				foreach($pms_state [$lrm ['room_info'] ['webser_id']] as $web_code => $ps){
					if(isset ($params ['member_level']) ){
						if (!empty($members_price_code) && !in_array($web_code, $members_price_code)){
							$data [$room_key] ['state_info'] [$web_code] = $ps;
							$min_price [] = $ps ['avg_price'];
						}else if (!empty($params['extra_price_code'])&& in_array($web_code, $params['extra_price_code'])&&!in_array($web_code, $room_price_codes)){
							$data [$room_key] ['state_info'] [$web_code] = $ps;
							$min_price [] = $ps ['avg_price'];
						}
					}
				}
			}
			$data [$room_key] ['lowest'] = empty ( $min_price ) ? 0 : min ( $min_price );
			$data [$room_key] ['highest'] = empty ( $min_price ) ? 0 : max ( $min_price );

			if (! empty ( $lrm ['show_info'] )) {
				foreach ( $lrm ['show_info'] as $sik => $si ) {

					//检查member_price_code是否存在该等级PMS代码配对值
					if($si ['external_code'] !== '' && !empty($basic_price_code[$si['external_code']])){
						$si['external_code'] = $basic_price_code[$si['external_code']];
					}
					// if (isset ( $member_level ) && ! empty ( $condit ['member_privilege'] ) && isset ( $si ['condition'] ['member_level'] ) && array_key_exists ( $si ['condition'] ['member_level'], $condit ['member_privilege'] )) {
					if ($si ['external_code'] !== '' && ! empty ( $pms_state [$lrm ['room_info'] ['webser_id']] [$si ['external_code']] )) {
						$tmp = $pms_state [$lrm ['room_info'] ['webser_id']] [$si ['external_code']];
						$nums = isset ( $condit ['nums'] [$lrm ['room_info'] ['room_id']] ) ? $condit ['nums'] [$lrm ['room_info'] ['room_id']] : 1;
						$data [$room_key] ['show_info'] [$sik] ['least_num'] = $tmp ['least_num'];
						$data [$room_key] ['show_info'] [$sik] ['book_status'] = $tmp ['book_status'];
						$allprice = '';
						$amount = 0;
						foreach ( $tmp ['date_detail'] as $dk => $td ) {
							if(!empty($si ['related_cal_way'])&&!empty($si ['related_cal_value'])){
								$tmp ['date_detail'] [$dk] ['price'] = round($this->Order_model->cal_related_price($td ['price'], $si ['related_cal_way'], $si ['related_cal_value'], 'price'));
							}else{
								$tmp ['date_detail'] [$dk] ['price']=$td ['price'];
							}
							$tmp ['date_detail'] [$dk] ['nums'] = $tmp ['least_num'];
							$allprice .= ',' . $tmp ['date_detail'] [$dk] ['price'];
							$amount += $tmp ['date_detail'] [$dk] ['price'];
						}
						$data [$room_key] ['show_info'] [$sik] ['date_detail'] = $tmp ['date_detail'];
						$data [$room_key] ['show_info'] [$sik] ['extra_info'] = $tmp ['extra_info'];
						empty ( $tmp ['des'] ) ?  : $data [$room_key] ['show_info'] [$sik] ['des'] = $tmp ['des'];

						$data [$room_key] ['show_info'] [$sik] ['avg_price'] = number_format ( $amount / $params ['days'], 2,'.','' );
						$data [$room_key] ['show_info'] [$sik] ['allprice'] = substr ( $allprice, 1 );
						$data [$room_key] ['show_info'] [$sik] ['total'] = $amount * 1;
						$data [$room_key] ['show_info'] [$sik] ['total_price'] = $data [$room_key] ['show_info'] [$sik] ['total'] * $nums;
					}
					// }
				}
			}
		}
		return $data;
	}
	function get_web_roomtype($pms_set, $startdate, $days, $params) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . '/queryHotelList';
		$rate_codes = isset ( $params ['web_code'] ) ? $params ['web_code'] : '';
		$data = array (
			'date' => date ( 'Y-m-d', strtotime ( $startdate ) ),
			'dayCount' => $days,
			'cityCode' => '',
			'brandCode' => '',
			'order' => '1',
			'firstResult' => 1,
			'pageSize' => 10,
			'rateCodes' => $rate_codes,
			'salesChannel' => isset($pms_auth['priceSalesChannel'])?$pms_auth['priceSalesChannel']:$pms_auth['salesChannel'],
			// 'hotelIds' => 13,
			'hotelIds' => $pms_set ['hotel_web_id'],
			/* 13,14,15,16,17,18,32,33,34,30,19,20,21,22,23,24,12,11,25,31,10,9,28,29,35,36 */
			'hotelGroupId' => $pms_auth ['hotelGroupId']
		);

		$func_data=['hotel_id'=>$params['idents']['hotel_id']];
		$res = $this->get_to ( $url, $data, $pms_set ['inter_id'],$func_data,$pms_auth );
		$code_des = array ();
		$pms_state = array ();
		$exprice = array ();
		$room_web_rums = array();
		if (! empty ( $res ['hrList'] )) {
			$states = current ( $res ['hrList'] );
			if (! empty ( $states ['roomList'] )) {
				foreach ( $states ['rateCodes'] as $rc ) {
					$code_des [$rc ['code']] = $rc ['descript'];
				}
				if (!empty($pms_auth['get_num_way'])){
				    $rm_cds = implode(',', array_keys($params ['web_rids']));
				    $room_web_rums = $this->get_web_nums($pms_set,$pms_auth, $startdate, $params['enddate'], $rm_cds,$pms_auth['get_num_way']);
				}
				$day_diff = round ( (strtotime ( $startdate ) - strtotime ( date ( 'Ymd' ) )) / 86400 );
				foreach ( $states ['roomList'] as $rl ) {
					if (! empty ( $code_des [$rl ['ratecode']] )) {
						if ((! empty ( $rl ['advMin'] ) && $rl ['advMin'] > $day_diff) || (! empty ( $rl ['stayMin'] ) && $rl ['stayMin'] > $days)) {
							continue;
						}

						// 判断价格代码设置
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['condition'] = array ();
						if (! empty ( $params ['code_config'] [$rl ['ratecode']] )) {
							if (!empty($params ['code_config'] [$rl ['ratecode']]['condition'])){
								$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['condition'] = $params ['code_config'] [$rl ['ratecode']]['condition'];
							}
							if (!empty($params ['code_config'] [$rl ['ratecode']]['limit_level'])&&isset($params ['condit']['member_level'])&&!in_array($params ['condit']['member_level'], $params ['code_config'] [$rl ['ratecode']]['limit_level'])){
								unset($pms_state [$rl ['rmtype']] [$rl ['ratecode']]);
								continue;
							}
						}

						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['price_name'] = $code_des [$rl ['ratecode']];
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['price_type'] = 'pms';
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['extra_info'] = array (
							'type' => 'code',
							'pms_code' => $rl ['ratecode'],
							'market' => $rl ['market'],
							'src' => $rl ['src'],
							'member_level' => $params ['member_level']
						);
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['price_code'] = $rl ['ratecode'];
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['des'] = '';
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['sort'] = 0;
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['disp_type'] = 'buy';
						$allprice = '';
						$amount = '';
						$mins=array();
						for($i = 0; $i < $days; $i ++) {
						    $tmp_date=date ( 'Ymd', strtotime ( '+ ' . $i . ' day ', strtotime ( $startdate ) ) );
						    $room_nums=isset($room_web_rums[$rl['rmtype']][$tmp_date]) ? $room_web_rums[$rl['rmtype']][$tmp_date] : $rl ['avail'];
							$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['date_detail'] [$tmp_date] = array (
								'price' => $rl ['avgRate1'],
								'nums' => $room_nums
							);
							$allprice .= ',' . $rl ['avgRate1'];
							$amount += $rl ['avgRate1'];
							$mins[]=$room_nums;
						}
						if (isset ( $params ['web_rids'] [$rl ['rmtype']] )) {
							$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['rmtype']]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$rl ['rmtype']]];
						} else {
							$nums = 1;
						}
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['allprice'] = substr ( $allprice, 1 );
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['total'] = $rl ['crate1'];
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['related_des'] = '';
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['total_price'] = $rl ['crate1'] * $nums;
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['avg_price'] = $rl ['avgRate1'];
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['price_resource'] = 'webservice';
						
						$least_num = min($mins);
						if ($least_num>1&&empty($pms_auth['multi_rooms'])){
						    $least_num = 1;
						}
						
						if(!empty($pms_auth['max_rooms'])){
							$max_count=$pms_auth['max_rooms'];
						}else{
							$max_count=2;
						}
						
						$least_num=min($least_num,$max_count);
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['least_num'] =$least_num;
						$book_status = 'full';
						if ($least_num >= $nums)
							$book_status = 'available';
						$pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['book_status'] = $book_status;

						$exprice [$rl ['rmtype']] [] = $pms_state [$rl ['rmtype']] [$rl ['ratecode']] ['avg_price'];
						// if ($room_detail ['canBook'] == 1) {
						// $valid_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']] = $pms_state [$type_state ['roomTypeCode']] [$room_detail ['ratePlanCode']];
						// }
					}
				}
			}
		}
		return array (
			'pms_state' => $pms_state,
			'exprice' => $exprice
		);
	}
	function cancel_order_web($inter_id, $order, $pms_set = array()) {
		$web_orderid = isset ( $order ['web_orderid'] ) ? $order ['web_orderid'] : "";
		if (! $web_orderid) {
			return array (
				's' => 0,
				'errmsg' => '取消失败'
			);
		}

		//判断订单时间
		$intime = strtotime($order['startdate']);

		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order['hotel_id'], 'ORDER_PAID_CANCEL_TIME');

		if(!empty($config_data['ORDER_PAID_CANCEL_TIME'])&&$order['paytype']=='weixin'){
			$out_time = json_decode($config_data['ORDER_PAID_CANCEL_TIME'],true);
			if(isset($out_time[$order ['first_detail']['price_code']])){
				$timelimit = $out_time[$order ['first_detail']['price_code']];
			}elseif(isset($out_time['all'])){
				$timelimit = $out_time['all'];
			}

			if(mktime($timelimit, 0, 0, date('m', $intime), date('d', $intime), date('Y', $intime)) < time() && $this->uri->segment(3)!= 'deal_order_queues' && $order['status']!=9){
				//时间超过入住时间当天的限制时间不能取消
				return array(
					's'      => 0,
					'errmsg' => '只能在入住当天'.$timelimit.'点前取消订单！',
				);
			}
		}

		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . '/cancelbook';

		$data = array (
			"cardNo" => '',
			"crsNo" => $web_orderid,
			"hotelGroupId" => $pms_auth ['hotelGroupId']
		);
		$func_data=['orderid'=>$order['orderid']];
		$res = $this->post_to ( $url, $data, $inter_id ,$func_data,$pms_auth);
		if (isset ( $res ['resultCode'] ) && $res ['resultCode'] == 0)
			return array (
				's' => 1,
				'errmsg' => '取消成功'
			);
		return array (
			's' => 0,
			'errmsg' => '取消失败'
		);
	}
	public function update_web_order($inter_id, $list, $pms_set) {
		$pms_auth = json_decode($pms_set['pms_auth'], TRUE);
		if(!empty($pms_auth['new_upd'])){
			return $this->update_web_order_multi($inter_id, $list, $pms_set);
		}else{
		    return $this->update_web_order_sub($inter_id, $list, $pms_set);
// 			switch($inter_id){
// 				case 'a449675133' :
// 				case 'a438686762' :
// 				case 'a457946152' :
// 				case 'a445223616' :
// 				case 'a487647571' : //万信测试
// 				case 'a487576098' : //万信正式
// 					return $this->update_web_order_sub($inter_id, $list, $pms_set);
// 					break;
// 				default :
// 					return $this->update_web_order_main($inter_id, $list, $pms_set);
// 					break;
// 			}
		}
		return FALSE;
	}
	
	public function update_web_order_multi($inter_id, $order, $pms_set){
		$web_order=$this->get_web_order_items($inter_id,$order,$pms_set);
		$istatus=-1;
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$point_prices = array();
		if ($order['paytype'] == 'point' && !empty($pms_auth['point_order_up'])){
    		$res=$this->get_web_order($order['web_orderid'],$inter_id,$pms_set);
    		if (isset ( $res ['resultCode'] ) && $res ['resultCode'] == 0) {
        		$web_price = floatval($res ['guest'] ['rateSum']);
        		$avg_web_price=intval($web_price/$order['roomnums']);
        		$left_price=$web_price-$avg_web_price*$order['roomnums'];
        		for($i=0;$i<$order['roomnums'];$i++){
                    $point_prices [] = $i == 0 ?  $avg_web_price + $left_price : $avg_web_price;
                }
    		}
		}
		if($web_order){
			//有数据返回
			$this->load->model('hotel/Order_model');
			$status_arr=$this->pms_enum('status');
			$status= $status_arr[$web_order['sta']];
			
			// 未确认单先确认
			if($status != 0 && $order['status'] == 0){
				$this->change_order_status($inter_id, $order['orderid'], 1);
				$this->Order_model->handle_order($inter_id, $order['orderid'], 1, '', array(
					'no_tmpmsg' => 1
				));
			}
			
			//判断是否有子订单返回
			if(!empty($web_order['items'])){
				$local_items = array();
				$local_noitem = array();
				foreach($order['order_details'] as $od){
					if(!empty($od['webs_orderid'])){
						$local_items[$od['webs_orderid']] = $od;
					}else{
						$local_noitem[] = $od;
					}
				}
				
				$i = 0;
				foreach($web_order['items'] as $v){
					$updata = array();
					if(array_key_exists($v['id'], $local_items)){
						$od = $local_items[$v['id']];
					}else{
						//不存在本地订单中
						$od = $local_noitem[$i];
						
						$this->db->where(array('id' => (int)$od['sub_id']))->update('hotel_order_items', array('webs_orderid' => $v['id']));
						
						$i++;
					}
					
					$wstatus=$v['sta'];
					$istatus=$status_arr[$wstatus];
					
					if($od['istatus'] == 4 && $istatus == 5){
						$istatus = 4;
					}
					
					$web_start=date('Ymd',intval($v['arr']*0.001));
					$web_end=date('Ymd',intval($v['dep']*0.001));
					
					$web_end = $web_end == $web_start ? date('Ymd', strtotime('+ 1 day', strtotime($web_start))) : $web_end;
					
					//判断实际入住时间，订单记录的入住时间
					$ori_day_diff = get_room_night($od['startdate'], $od['enddate'], 'ceil', $od);//至少有一个间夜
					$web_day_diff = get_room_night($web_start, $web_end, 'ceil');//至少有一个间夜
					$day_diff = $web_day_diff - $ori_day_diff;
					
					$updata['startdate'] = $web_start;
					$updata['enddate'] = $web_end;
					
					if($day_diff != 0 || $web_start != $od['startdate'] || $web_end != $od['enddate']){
						$updata['no_check_date'] = 1;
					}
					
					//计算房费
					if ($point_prices){
					    $new_price = array_shift($point_prices);
					    if($new_price > 0 && $new_price != $od['iprice']){
					        $updata['no_check_date'] = 1;
					        $updata['new_price'] = $new_price;
					    }
					}else if($istatus==3){
					    $acc_info=$this->get_web_acc($inter_id, $order, $v['id'], $pms_set);
					    if(!$acc_info&&count($order['order_details'])==1){
					    	//如果不存在财务明细，而且只有一间房时，尝试查询预订单的财务明细
						    $acc_info=$this->get_web_acc($inter_id, $order, $web_order['id'], $pms_set);
					    }
					    if($acc_info){
					    	//存在记录时才统计
						    $fang_fee=$pms_auth['fang_fee'];
						    $new_price=0;
						    foreach($acc_info as $v){
						        if(in_array($v['taCode'], $fang_fee)){
						        	$new_price+=$v['charge'];
						        }
						    }
						    
						    if($new_price > 0 && $new_price != $od['iprice']){
							    $updata['no_check_date'] = 1;
							    $updata['new_price'] = $new_price;
						    }
					    }
					}
					
					if($istatus != $od ['istatus']){
						$updata['istatus'] = $istatus;
					}
					
					if($od['room_no']!=$v['rmno']){
						$updata['room_no']=$v['rmno'];
					}
					
					if(!empty ($updata)){
						$this->Order_model->update_order_item($inter_id, $order ['orderid'], $od ['sub_id'], $updata);
					}
				}
			}else{
				
				if ($order['status'] == 4 && $status == 5) {
					$status = 4;
				}
				$istatus = $status;
				
				if($status != $order['status']){
					$this->Order_model->update_order_status($inter_id, $order['orderid'], $status, $order['openid']);
				}
			}
		}
		
		return $istatus;
		
	}
	
	public function get_web_order_items($inter_id, $order, $pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$url = $pms_auth['url'] . '/orderInfo';
		$data = [
			'crsNo'        => $order['web_orderid'],
			'hotelGroupId' => $pms_auth['hotelGroupId'],
		];
		$func_data = ['orderid' => $order['orderid']];
		$res = $this->get_to($url, $data, $inter_id, $func_data,$pms_auth);
		
		if($res['resultCode'] == 0 && !empty($res['result'])){
			return $res['result'];
		}
		return [];
	}
	
	public function get_web_acc($inter_id, $order, $accnt, $pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$url = $pms_auth['url'] . '/simpleAccount';
		$data = [
			'hotelGroupId' => $pms_auth['hotelGroupId'],
			'hotelId'      => $pms_set['hotel_web_id'],
			'accnt'        => $accnt,
		];
		$func_data = ['orderid' => $order['orderid'], 'web_orderid' => $order['web_orderid']];
		$res = $this->get_to($url, $data, $inter_id, $func_data,$pms_auth);
		
		if($res['resultCode'] == 0 && !empty($res['result'])){
			return $res['result'];
		}
		return [];
	}

	public function update_web_order_sub($inter_id, $list, $pms_set) {
		$res=$this->get_web_order($list['web_orderid'],$inter_id,$pms_set);
		$new_status = null;

		if (isset ( $res ['resultCode'] ) && $res ['resultCode'] == 0) {
			$status_arr = $this->pms_enum ( 'status' );
			$new_status = $status_arr [$res ['guest'] ['sta']];
			$this->load->model ( 'hotel/Order_model' );
			foreach ( $list ['order_details'] as $od ) {
				if ($od ['istatus'] == 4 && $new_status == 5) {
					$new_status = 4;
				}
				$web_start = date ( 'Ymd', strtotime ( $res ['guest'] ['arr'] ) );
				$web_end = date ( 'Ymd', strtotime ( $res ['guest'] ['dep'] ) );
				$web_end = $web_end == $web_start ? date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $web_start ) ) ) : $web_end;
				$ori_day_diff = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
				$web_day_diff = get_room_night($web_start,$web_end,'ceil');//至少有一个间夜
				$day_diff = $web_day_diff - $ori_day_diff;
				$updata = array ();
				if ($new_status != $od ['istatus']) {
					$updata ['istatus'] = $new_status;
				}

				// 云盟返佣机制更改
				// 到付：提前离店，按pms计算；
				// 微信支付：按下单时订单计算；
				if($inter_id == 'a445223616'){
					if ($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']) {
						if($day_diff < 0 && $list ['paid'] == 0){
							$first_price = floatval($od ['allprice']); // 取首日价格
							$allprice = explode(',', $od ['allprice']);
							$countday = abs($day_diff);
							if(date('Ymd', strtotime($res ['guest'] ['dep'])) == $web_start){
								$countday--;
							}
							$updata ['no_check_date'] = 1;
							$updata ['startdate'] = $web_start;
							$updata ['enddate'] = date('Ymd', strtotime($res ['guest'] ['dep']));
							$reduce_amount = 0;
							for($j = 0; $j < $countday; $j++){
								$tmp = array_pop($allprice);
								$reduce_amount += empty ($tmp) ? $first_price : $tmp;
							}
							$updata ['new_price'] = $od ['iprice'] - $reduce_amount;
						}
					}
				}else{
					$updata ['startdate'] = $web_start;
					$updata ['enddate'] = $web_end;

					if ($day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']) {
						$updata ['no_check_date'] = 1;
					}

					$web_price = floatval($res ['guest'] ['rateSum']);
// 					if(empty ($web_price)){
// 						if($web_day_diff == 1){
// 							$web_price = floatval($od ['allprice']);
// 						}
// 					}
					if($web_price>0&&$web_price != $od['iprice']){
						$updata['no_check_data'] = 1;
						$updata['new_price'] = $web_price;
					}
				}

				if (! empty ( $updata )) {
					$this->Order_model->update_order_item ( $inter_id, $list ['orderid'], $od ['sub_id'], $updata );
				}
			}
		}
		return $new_status;
	}
	public function update_web_order_main($inter_id, $list, $pms_set) {
		$res=$this->get_web_order($list['web_orderid'],$inter_id,$pms_set);
		$new_status = null;
		if (isset ( $res ['resultCode'] ) && $res ['resultCode'] == 0) {
			$status_arr = $this->pms_enum ( 'status' );
			$new_status = $status_arr [$res ['guest'] ['sta']];
			if ($list ['status'] == 4 && $new_status == 5) {
				$new_status = 4;
			}
			if ($new_status != $list ['status']) {
				$this->load->model ( 'hotel/Order_model' );
				$this->db->where ( array (
					                   'inter_id' => $list ['inter_id'],
					                   'orderid' => $list ['orderid']
				                   ) );
				$this->db->update ( 'hotel_orders', array (
					'status' => $new_status
				) );
				$this->Order_model->handle_order ( $inter_id, $list ['orderid'], $new_status, $list ['openid'] );
			}
		}
		return $new_status;
	}

	public function get_web_order($web_orderid, $inter_id, $pms_set){
		$pms_auth = json_decode($pms_set['pms_auth'], true);
		$url = $pms_auth ['url'] . '/findResrvGuest';
		$data = array(
			"cardNo"       => "",
			"crsNo"        => $web_orderid,
			"hotelGroupId" => $pms_auth['hotelGroupId']
		);
		$res = $this->post_to($url, $data, $inter_id,[],$pms_auth);
		if(isset($res['resultCode']) && $res['resultCode'] == 0){
			return $res;
		}else{
			$url = $pms_auth ['url'] . '/findResrvGuestHistory';
			$res = $this->post_to($url, $data, $inter_id,[],$pms_auth);
		}
		return $res;
	}
	// R=预订，X=取消，I=在住，S=挂账，O=离店，N=Noshow
	function pms_enum($type, $key = NULL, $value = NULL) {
		$data = array ();
		switch ($type) {
			case 'status' :
				$data = array(
					'R' => 1,
					'X' => 5,
					'I' => 2,
					'O' => 3,
					'N' => 8,
					'S' => 3,
					'D'=>5,
				);
				break;
			case 'func_name' :
				$data = array(
					'cancelbook'=>'取消订单',
					'findResrvGuest'=>'查询订单',
					'findResrvGuestHistory'=>'查询历史订单',
					'book'=>'新增订单',
					'bookWithCoupon'=>'新增用券订单',
					'saveWebPay'=>'入账',
					'queryHotelList'=>'查询房态'
				);
				break;
			default :
				break;
		}
		if (is_array ( $data )) {
			if (isset ( $key )) {
				return isset ( $data [$key] ) ? $data [$key] : NULL;
			}
			if (isset ( $value )) {
				return in_array ( $value, $data );
			}
		}
		return $data;
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
			$coupon_info=json_decode($order['coupon_des'],TRUE);
//			'ascription'
			$coupon_row=$coupon_info['cash_token'][0];
			
			if((!empty($coupon_row['extra']['source'])&&$coupon_row['extra']['source']=='pms')||(!empty($coupon_row['extra']['ascription'])&&$coupon_row['extra']['ascription']=='pms'))
				return $this->order_to_web_coupon($inter_id, $orderid,$paras,$pms_set, $order);
			return $this->order_to_web_normal($inter_id, $orderid,$paras,$pms_set, $order);
		}
		return array (
			's' => 0,
			'errmsg' => '提交订单失败'
		);
	}

	function order_to_web_normal($inter_id, $orderid, $paras = array(), $pms_set = array(),$order){
		$this->load->library('MYLOG');
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$hotel_no = $pms_set ['hotel_web_id'];
		$order_room_codes = json_decode ( $order ['room_codes'], TRUE );
		$room_codes = $order_room_codes [$order ['first_detail'] ['room_id']];
		$url = $pms_auth ['url'] . '/book';
		$card_type = '';
		$card_no = '';
		$member_name = '';

		//有绿云会员号才传
		$this->load->model('hotel/Member_model');
		$member=$this->Member_model->check_openid_member($inter_id,$order['openid']);

		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
			'PMS_BANCLANCE_REDUCE_WAY',
			'BONUS_EXCHANGE_BALANCE',
			'PMS_BONUS_COMSUME_WAY',
			'PMS_POINT_REDUCE_WAY',
		    'POINT_PAY_WITH_BILL'
		) );

		// 		if(!isset($member->mode)){
		// 			$member->mode=$member->member_mode;
		// 		}

		if (! empty ( $order ['member_no'] ) && (!empty($member)&&$member->member_mode==2&&$member->is_login=='t')) {
			$card_type = $room_codes ['code'] ['extra_info'] ['member_level'];
			$card_no = $order ['member_no'];
			if (!empty($pms_auth['self_in'])){
    			$member_name = $member->name;
			}
		}
		$favor = empty ( $order ['coupon_favour'] ) ? 0 : $order ['coupon_favour'];

		$custom_price = array ();
		// $custom_price = '';
		$allprice = explode ( ',', $order ['first_detail'] ['allprice'] );
		$total_favour = $order ['coupon_favour'] + $order ['point_favour']+ $order ['wxpay_favour'];
		$favour_info = empty ( $order ['coupon_favour'] ) ? '' : '使用优惠券：' . $order ['coupon_favour'] . '。';
		if($inter_id=='a457946152'){
			$favour_info .= empty ( $order ['point_favour'] ) ? '' : '悦银抵扣：' . $order ['point_favour'] . '。';
		}else{
			$favour_info .= empty ( $order ['point_favour'] ) ? '' : '积分抵扣：' . $order ['point_favour'] . '。';
		}
		$favour_info .= $order ['wxpay_favour'] <= 0 ? '' : '微信支付立减：' . $order ['wxpay_favour'] . '。';
		if (!empty($room_codes ['code'] ['extra_info']['parity'])){
		    $parity_info=json_decode($room_codes ['code'] ['extra_info']['parity'],TRUE);
			$favour_info.='已智能调价,';
			foreach ($parity_info as $date=>$price){
			    $favour_info.=date('Y-m-d',strtotime($date)).':'.$price.',';
			}
			$favour_info = substr($favour_info, 0,strlen($favour_info)-1);
			$favour_info.='。';
		}
		$favour = number_format ( $total_favour / $order ['roomnums'], 2, '.', '' );
		if ($favour > 0) {
			for($i = 0; $i < count ( $allprice ); $i ++) {
				if ($allprice [$i] >= $favour) {
					$allprice [$i] -= $favour;
					$favour = 0;
					break;
				} else {
					$favour -= $allprice [$i];
					$allprice [$i] = 0;
				}
				if ($favour == 0)
					break;
			}
		}
		$i = 0;
		foreach ( $allprice as $ap ) {
			if($order ['paytype'] == 'point' && $inter_id=='a457946152' && isset($config_data['BONUS_EXCHANGE_BALANCE']) && !empty($config_data['BONUS_EXCHANGE_BALANCE'])){
				$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
				$ap = round($ap*$bonus_config->percentage);
			}
			$custom_price [] = array (
				'date' => date ( 'Y-m-d', strtotime ( '+ ' . $i . 'day', strtotime ( $order ['first_detail'] ['startdate'] ) ) ),
				'realRate' => $ap
			);
			// $custom_price .= ',{date:' . date ( 'Y-m-d', strtotime ( '+ ' . $i . 'day', strtotime ( $order ['first_detail'] ['startdate'] ) ) ) . ',realRate:' . $ap . '}';
			$i ++;
		}
		// $custom_price = substr ( $custom_price, 1 );
		// $custom_price = '[' . $custom_price . ']';
		$src = 'NET';
		if (! empty ( $pms_auth ['src'] )) {
			$src = $pms_auth ['src'];
		}
		if($order ['paytype'] == 'point'&&!empty($pms_auth['point_market'])){
		    $market=$pms_auth['point_market'];
		}else{
		    $market=$room_codes ['code'] ['extra_info'] ['market'];
		}
		if (empty($market)&&!empty($pms_auth['def_market'])){
			$market=$pms_auth['def_market'];
		}
		$data = array (
			"arr" => date ( 'Y-m-d 12:00:00', strtotime ( $order ['startdate'] ) ),
			"dep" => date ( 'Y-m-d 12:00:00', strtotime ( $order ['enddate'] ) ),
			"rmtype" => $room_codes ['room'] ['webser_id'], // 房型代码
			"rateCode" => $room_codes ['code'] ['extra_info'] ['pms_code'], // MEM,RACK,ENT,MAN 房价码
			"src" => $src,
			"rmNum" => $order ['roomnums'],
			"rsvMan" => $order ['name'],
			"sex" => "0",
			"mobile" => $order ['tel'],
			"email" => '',
			"idType" => "01",
			"idNo" => "",
			"cardType" => $card_type,
			"cardNo" => $card_no,
			"adult" => "1",
			"remark" => "微信订单。" . $favour_info,
			"disAmount" => '',
			"market" => $market,
			"saleChannel" => $pms_auth ['salesChannel'],
			"salesChannel" => $pms_auth ['salesChannel'],
			"hotelId" => $hotel_no,
			"hotelGroupId" => $pms_auth ['hotelGroupId'],
			"everyDayRate" => json_encode ( $custom_price )
		);
		//         if($inter_id=='a457946152'){
		// 			$data['market']='NET';
		// 			if(strpos($data['rateCode'],'VIP')!==FALSE){
		// 				$data['market']='VIM';
		// 			}
		// 		}
		if (! empty ( $pms_auth ['weixin'] )) {
			$data ['weixin'] = $pms_auth ['weixin'];
		}
		if (! empty ( $pms_auth ['order_channel'] )) {
			$data ['channel'] = $pms_auth ['order_channel'];
		}
        //特殊的备注
        if (isset($pms_auth['special_remark']) && $pms_auth['special_remark'] == 1) {
            $this->load->model ( 'common/Enum_model' );
            $pay_types = $this->Enum_model->get_enum_des ( 'PAY_WAY',1,$order['inter_id']);
            $data['remark'] .= $order['first_detail']['price_code_name']
                .','.$order['price'].'元,'.$pay_types[$order['paytype']];
        }

		$res = $this->post_to ( $url, $data, $inter_id,array('orderid'=>$orderid),$pms_auth );
		/*
		 * 返回的信息如下：
		 * {"crsNo":"W1512300002","paySta":"0","deposit":0,"resultCode":0}
		 */
		if (! empty ( $res ['crsNo'] )) {
			$web_orderid = $res ['crsNo'];
		    $order_updata=array ( // 将pms的单号更新到相应订单
				'web_orderid' => $web_orderid
			);
		    if ($member_name){
		        $order_room_codes[$order ['first_detail'] ['room_id']] ['code'] ['extra_info'] ['memname']=$member_name;
		        $order_updata['room_codes']=json_encode($order_room_codes);
		    }
			$this->db->where ( array (
				'orderid' => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			) );
			$this->db->update ( 'hotel_order_additions',$order_updata);
			// $this->Order_model->update_order_status ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作

			//修改积分储值扣减

			//积分部分扣减
			if ($order ['paytype'] != 'point'&&$order ['point_used_amount']>0 &&! empty ( $config_data ['PMS_BONUS_COMSUME_WAY'] ) && $config_data ['PMS_BONUS_COMSUME_WAY'] == 'after') {
				$this->load->model ( 'hotel/Member_model' );
				if(!empty($config_data['BONUS_EXCHANGE_BALANCE'] )){
					$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
					$params['rate'] = $bonus_config->rate;
					$params['percentage'] = $bonus_config->percentage;
					$params['count'] = $order['point_favour'];
					$params['crsNo'] = $web_orderid;
					$params['hotelId'] = $hotel_no;
					if(!empty($room_codes['room']['bonus_consume_code'])){
						$params['password']=$room_codes ['room'] ['bonus_consume_code'];
					}else{
						$params['password']='';
					}
					MYLOG::w('bonus_exchange_params+'.json_encode($params),"bonus_exchange");
					$exchange_bonus = $this->Member_model->exchange_bonus($inter_id,$order ['openid'],  $order ['point_used_amount'],$order ['orderid'], '隐居积分换算扣减',$params);
					if($exchange_bonus['msg']!='ok'){
						$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],3);
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
							'errmsg' => $exchange_bonus['msg']
						);
					}
				}else {
					$params=array();
					$params['crsNo'] = $web_orderid;
					if(!empty($room_codes['room']['bonus_consume_code'])){
						$params['password']=$room_codes ['room'] ['bonus_consume_code'];
					}else{
						$params['password']='';
					}
					if (! $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'], $order ['openid'], $order ['point_used_amount'],$params )) {
						$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],3);
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
						if($inter_id=='a457946152'){
							return array (
								's' => 0,
								'errmsg' => '悦银扣减失败'
							);
						}else{
							return array (
								's' => 0,
								'errmsg' => '积分扣减失败'
							);
						}
					}
				}
				$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],1);
			}
			if ($order ['status'] != 9) {
				$this->db->where ( array (
					'orderid' => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				) );
				$this->db->update ( 'hotel_orders', array (
					'status' => 1
				) );
				$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
			}else if ($order ['paytype'] == 'balance'){
				if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
					$this->load->model ( 'hotel/Member_model' );
					$balance_param=array(
						'crsNo'=>$web_orderid,
						'hotelId' => $hotel_no
						//						'password'=>$room_codes ['room'] ['consume_code']
					);
					if(!empty($room_codes['room']['consume_code'])){
						$balance_param['password']=$room_codes ['room'] ['consume_code'];
					}
					if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'] . ',' . $web_orderid . ',' . $room_codes ['room'] ['consume_code'], '订房订单余额支付',$balance_param,$order )) {
						//云盟储值支付需要入账
						if($inter_id=='a445223616'){
							$this->add_web_bill($web_orderid,$order,$pms_auth,$orderid,true);
						}
						$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true );
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
			}else if ($order ['paytype'] == 'point'){
				if(empty($config_data['PMS_POINT_REDUCE_WAY'])||$config_data['PMS_POINT_REDUCE_WAY']=='after'){
					MYLOG::w('BONUS_EXCHANGE_BALANCE+'.$config_data['BONUS_EXCHANGE_BALANCE'],"bonus_exchange");
					if(!empty($config_data['BONUS_EXCHANGE_BALANCE'] )){
						$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
						$params=array();

                        $exchange_count = 0;    //每日房价四舍五入相加
                        foreach($order['order_details'] as $order_detail){
                            if(!empty($order_detail['real_allprice'])){
                                $real_prices = explode(',',$order_detail['real_allprice']);
                                foreach($real_prices as $real_price){
                                    $real_price = $real_price * $bonus_config->percentage;
                                    $exchange_count = $exchange_count + round($real_price);
                                }
                            }
                        }
                        if($exchange_count==0){
                            $params['count'] = round($order['price'] * $bonus_config->percentage);
                        }else{
                            $params['count'] = $exchange_count;
                        }

						$params['rate'] = $bonus_config->rate;
						$params['percentage'] = $bonus_config->percentage;
						$params['crsNo'] = $web_orderid;
						$params['hotelId'] = $hotel_no;
						MYLOG::w('bonus_consume_code+'.$room_codes['room']['point_pay_code'],"bonus_exchange");
						if(!empty($room_codes['room']['point_pay_code'])){
							$params['password']=$room_codes ['room'] ['point_pay_code'];
						}else{
							$params['password']='';
						}
						MYLOG::w('params+'.json_encode($params),"bonus_exchange");
						$this->load->model ( 'hotel/Member_model' );
						$exchange_bonus = $this->Member_model->exchange_bonus($inter_id,$order ['openid'],  $order ['point_used_amount'],$order ['orderid'], '隐居积分换算扣减',$params);
						if($exchange_bonus['msg']!='ok'){
							MYLOG::w('result+'.'fail',"bonus_exchange");
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
								'errmsg' => $exchange_bonus['msg']
							);
						}else {
							$this->Order_model->update_order_status ( $order ['inter_id'], $order ['orderid'], 1, $order ['openid'], true,true );
						}
					}else{
						$params=array();
						$params['crsNo'] = $web_orderid;
						if(!empty($room_codes['room']['point_pay_code'])){
							$params['password']=$room_codes ['room'] ['point_pay_code'];
						}else{
							$params['password']='';
						}
						
						if(!empty($config_data['POINT_PAY_WITH_BILL'])){
							$params['extra']=[
									'taCode'=>$pms_auth['point_taCode'],
									'taRemark'=>'积分支付订单',
									'money'=>$order['price'],
									'taNo'=>$web_orderid,
									'point_pay_with_bill'=>1,
							];
						}
						
						$this->load->model ( 'hotel/Member_model' );
						if (! $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'], $order ['openid'], $order ['point_used_amount'],$params )) {
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
							if($inter_id=='a457946152'){
								return array (
									's' => 0,
									'errmsg' => '悦银扣减失败'
								);
							}else{
								return array (
									's' => 0,
									'errmsg' => '积分扣减失败'
								);
							}
						}else {
							$this->Order_model->update_order_status ( $order ['inter_id'], $order ['orderid'], 1, $order ['openid'], true,true );
						}
					}
				}
			}
			//修改积分储值扣减
			// 			if ($order ['status'] != 9) {
			// 				$this->db->where ( array (
			// 					                   'orderid' => $order ['orderid'],
			// 					                   'inter_id' => $order ['inter_id']
			// 				                   ) );
			// 				$this->db->update ( 'hotel_orders', array (
			// 					'status' => 1
			// 				) );
			// 				$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
			// 			} else if ($order ['paytype'] == 'balance') {
			// 				$this->load->model ( 'hotel/Hotel_config_model' );
			// 				$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
			// 					'PMS_BANCLANCE_REDUCE_WAY',
			// 					'BONUS_EXCHANGE_BALANCE',
			// 					'PMS_BONUS_COMSUME_WAY'
			// 				) );
			// 				if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
			// 					$this->load->model ( 'hotel/Member_model' );
			// 					$balance_param=array(
			// 						'crsNo'=>$web_orderid,
			// 						//						'password'=>$room_codes ['room'] ['consume_code']
			// 					);
			// 					if(!empty($room_codes['room']['consume_code'])){
			// 						$balance_param['password']=$room_codes ['room'] ['consume_code'];
			// 					}
			// 					if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'] . ',' . $web_orderid . ',' . $room_codes ['room'] ['consume_code'], '订房订单余额支付',$balance_param )) {
			// 						//云盟储值支付需要入账
			// 						if($inter_id=='a445223616'){
			// 							$this->add_web_bill($web_orderid,$order,$pms_auth,$orderid,true);
			// 						}
			// 						$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true );
			// 					} else {
			//                         $info = $this->Order_model->cancel_order ( $inter_id, array (
			//                                 'only_openid' => $order ['openid'],
			//                                 'member_no' => '',
			//                                 'orderid' => $order ['orderid'],
			//                                 'cancel_status' => 5,
			//                                 'no_tmpmsg' => 1,
			//                                 'delete' => 2,
			//                                 'idetail' => array (
			//                                     'i'
			//                                 )
			//                             ) );
			//                             return array (
			//                                 's' => 0,
			//                                 'errmsg' => '储值支付失败'
			//                             );
			// 					}
			// 				}
			//                 MYLOG::w('bonus_exchange+'.'lvyun_hotel_model_config_data：'.json_encode($config_data).'+user_amount:'.$order ['point_used_amount'].'+point:'.$order ['point_favour'],"bonus_exchange");
			// 				if($config_data['BONUS_EXCHANGE_BALANCE'] &&  $order ['point_used_amount']>0 && !empty($order ['point_favour'])){
			// 					if (! empty ( $config_data ['PMS_BONUS_COMSUME_WAY'] ) && $config_data ['PMS_BONUS_COMSUME_WAY'] == 'after') {
			// 						$this->load->model ( 'hotel/Member_model' );
			// 						$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
			// 						$params['rate'] = $bonus_config->rate;
			// 						$params['percentage'] = $bonus_config->percentage;
			// 						$params['count'] = $order['point_favour'];
			// 						$params['crsNo'] = $web_orderid;
			// 						if(!empty($room_codes['room']['bonus_consume_code'])){
			// 							$params['password']=$room_codes ['room'] ['bonus_consume_code'];
			// 						}else{
			// 							$params['password']='';
			// 						}
			//                         MYLOG::w('bonus_exchange_params+'.json_encode($params),"bonus_exchange");
			// 						if(!$this->Member_model->exchange_bonus($inter_id,$order ['openid'],  $order ['point_used_amount'],$order ['orderid'], '隐居悦银换算扣减',$params)){
			// 							$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],3);
			// 							$info = $this->Order_model->cancel_order ( $inter_id, array (
			// 								'only_openid' => $order ['openid'],
			// 								'member_no' => '',
			// 								'orderid' => $order ['orderid'],
			// 								'cancel_status' => 5,
			// 								'no_tmpmsg' => 1,
			// 								'delete' => 2,
			// 								'idetail' => array (
			// 									'i'
			// 								)
			// 							) );
			// 							return array (
			// 								's' => 0,
			// 								'errmsg' => '积分扣减失败'
			// 							);
			// 						}
			// 					}
			// 					$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],1);
			// 				}
			// 			}
			if (! empty ( $paras ['trans_no'] )) {
				$this->add_web_bill ( $web_orderid, $order, $pms_auth, $paras ['trans_no'] );
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
				'errmsg' => $res ['resultMsg']
			);
		}
	}

	function order_to_web_coupon($inter_id, $orderid, $paras = array(), $pms_set = array(),$order){
		$this->load->library('MYLOG');
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$hotel_no = $pms_set ['hotel_web_id'];
		$order_room_codes = json_decode ( $order ['room_codes'], TRUE );
		$room_codes = $order_room_codes [$order ['first_detail'] ['room_id']];
		$url = $pms_auth ['url'] . '/bookWithCoupon';
		$card_type = '';
		$card_no = '';
		$member_name = '';
		//有绿云会员号才传
		$this->load->model('hotel/Member_model');
		$member=$this->Member_model->check_openid_member($inter_id,$order['openid']);

		// 		if(!isset($member->mode)){
		// 			$member->mode=$member->member_mode;
		// 		}

		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
			'PMS_BANCLANCE_REDUCE_WAY',
			'BONUS_EXCHANGE_BALANCE',
			'PMS_BONUS_COMSUME_WAY',
			'PMS_POINT_REDUCE_WAY',
		    'POINT_PAY_WITH_BILL'
		) );

		if (! empty ( $order ['member_no'] ) && (!empty($member)&&$member->member_mode==2&&$member->is_login=='t')) {
			$card_type = $room_codes ['code'] ['extra_info'] ['member_level'];
			$card_no = $order ['member_no'];
			if (!empty($pms_auth['self_in'])){
			    $member_name = $member->name;
			}
		}

		$custom_price = array ();
		// $custom_price = '';
		$allprice = explode ( ',', $order ['first_detail'] ['allprice'] );
		$total_favour = $order ['coupon_favour'] + $order ['point_favour']+ $order ['wxpay_favour'];
		$favour_info = empty ( $order ['coupon_favour'] ) ? '' : '使用优惠券抵扣：' . $order ['coupon_favour'] . '。';
		if($inter_id=='a457946152'){
			$favour_info .= empty ( $order ['point_favour'] ) ? '' : '悦银抵扣：' . $order ['point_favour'] . '。';
		}else{
			$favour_info .= empty ( $order ['point_favour'] ) ? '' : '积分抵扣：' . $order ['point_favour'] . '。';
		}
		$favour_info .= empty ( $order ['wxpay_favour'] ) ? '' : '微信支付立减：' . $order ['wxpay_favour'] . '。';
		if (!empty($room_codes ['code'] ['extra_info']['parity'])){
		    $parity_info=json_decode($room_codes ['code'] ['extra_info']['parity'],TRUE);
		    $favour_info.='已智能调价,';
		    foreach ($parity_info as $date=>$price){
		        $favour_info.=date('Y-m-d',strtotime($date)).':'.$price.',';
		    }
		    $favour_info = substr($favour_info, 0,strlen($favour_info)-1);
		    $favour_info.='。';
		}
		$favour = number_format ( $total_favour / $order ['roomnums'], 2, '.', '' );
		if ($favour > 0) {
			for($i = 0; $i < count ( $allprice ); $i ++) {
				if ($allprice [$i] >= $favour) {
					$allprice [$i] -= $favour;
					$favour = 0;
					break;
				} else {
					$favour -= $allprice [$i];
					$allprice [$i] = 0;
				}
				if ($favour == 0)
					break;
			}
		}
		$i = 0;
		foreach ( $allprice as $ap ) {
			if($order ['paytype'] == 'point' && $inter_id=='a457946152' && isset($config_data['BONUS_EXCHANGE_BALANCE']) && !empty($config_data['BONUS_EXCHANGE_BALANCE'])){
				$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
				$ap = round($ap*$bonus_config->percentage);
			}
			$custom_price [] = array (
				'date' => date ( 'Y-m-d', strtotime ( '+ ' . $i . 'day', strtotime ( $order ['first_detail'] ['startdate'] ) ) ),
				'realRate' => $ap
			);
			$i ++;
		}
		$src = 'NET';
		if (! empty ( $pms_auth ['src'] )) {
			$src = $pms_auth ['src'];
		}
		if($order ['paytype'] == 'point'&&!empty($pms_auth['point_market'])){
		    $market=$pms_auth['point_market'];
		}else{
		    $market=$room_codes ['code'] ['extra_info'] ['market'];
		}
		if (empty($market)&&!empty($pms_auth['def_market'])){
			$market=$pms_auth['def_market'];
		}
		$coupon_info=json_decode($order['coupon_des'],TRUE);
		$coupon_info=$coupon_info['cash_token'][0];
		$couponTypeArr = array(
			'discount' => 'RF', //折扣
			'voucher'  => 'DF' //代金
		);
		$data = array (
			"arr" => date ( 'Y-m-d 12:00:00', strtotime ( $order ['startdate'] ) ),
			"dep" => date ( 'Y-m-d 12:00:00', strtotime ( $order ['enddate'] ) ),
			"rmtype" => $room_codes ['room'] ['webser_id'], // 房型代码
			"rateCode" => $room_codes ['code'] ['extra_info'] ['pms_code'], // MEM,RACK,ENT,MAN 房价码
			"src" => $src,
			"rmNum" => $order ['roomnums'],
			"rsvMan" => $order ['name'],
			"sex" => "0",
			"mobile" => $order ['tel'],
			"email" => '',
			"idType" => "01",
			"idNo" => "",
			"cardType" => $card_type,
			"cardNo" => $card_no,
			"adult" => "1",
			"remark" => "微信订单。" . $favour_info,
			"disAmount" => '0',
			"market" => $market,
			"saleChannel" => $pms_auth ['salesChannel'],
			"salesChannel" => $pms_auth ['salesChannel'],
			"hotelId" => $hotel_no,
			"hotelGroupId" => $pms_auth ['hotelGroupId'],
			"couponDetailCode" => $coupon_info['code'],
			// 				"couponDetailCode" => 'FH807040000704877',
			"couponCode" => $coupon_info['extra']['coupon_id'],
			// 				"couponCode" => 'FH80',
			"costValue" => '-'.floatval($order['coupon_favour']),
			// 				"costValue" => -120,
			"cosType" => $couponTypeArr[$coupon_info['extra']['type']],
			'startDate'=>date ( 'Y-m-d 12:00:00', strtotime ( $order ['startdate'] ) ),
			'endDate'=>date ( 'Y-m-d 12:00:00', strtotime ( $order ['enddate'] ) )
		);
		if (! empty ( $pms_auth ['weixin'] )) {
			$data ['weixin'] = $pms_auth ['weixin'];
		}
		if (! empty ( $pms_auth ['order_channel'] )) {
			$data ['channel'] = $pms_auth ['order_channel'];
		}
        //特殊的备注
        if (isset($pms_auth['special_remark']) && $pms_auth['special_remark'] == 1) {
            $this->load->model ( 'common/Enum_model' );
            $pay_types = $this->Enum_model->get_enum_des ( 'PAY_WAY',1,$order['inter_id']);
            $data['remark'] .= $order['first_detail']['price_code_name']
                .','.$order['price'].'元,'.$pay_types[$order['paytype']];
        }

		$res = $this->post_to ( $url, $data, $inter_id,array('orderid'=>$orderid),$pms_auth );

		/*
		 * 返回的信息如下：
		 * {"crsNo":"W1512300002","paySta":"0","deposit":0,"resultCode":0}
		 */
		if (! empty ( $res ['crsNo'] )) {
			$web_orderid = $res ['crsNo'];
		    $order_updata=array ( // 将pms的单号更新到相应订单
		            'web_orderid' => $web_orderid
		    );
		    if ($member_name){
		        $order_room_codes[$order ['first_detail'] ['room_id']] ['code'] ['extra_info'] ['memname']=$member_name;
		        $order_updata['room_codes']=json_encode($order_room_codes);
		    }
			$this->db->where ( array (
				'orderid' => $order ['orderid'],
				'inter_id' => $order ['inter_id']
			) );
			$this->db->update ( 'hotel_order_additions', $order_updata);
			// $this->Order_model->update_order_status ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作

			//修改积分储值扣减

			//积分部分扣减
			if ($order ['paytype'] != 'point'&&$order ['point_used_amount']>0 &&! empty ( $config_data ['PMS_BONUS_COMSUME_WAY'] ) && $config_data ['PMS_BONUS_COMSUME_WAY'] == 'after') {
				$this->load->model ( 'hotel/Member_model' );
				if(!empty($config_data['BONUS_EXCHANGE_BALANCE'] )){
					$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
					$params['rate'] = $bonus_config->rate;
					$params['percentage'] = $bonus_config->percentage;
					$params['count'] = round($order['point_favour'] * $bonus_config->percentage);
					$params['crsNo'] = $web_orderid;
					$params['hotelId'] = $hotel_no;
					if(!empty($room_codes['room']['bonus_consume_code'])){
						$params['password']=$room_codes ['room'] ['bonus_consume_code'];
					}else{
						$params['password']='';
					}
					MYLOG::w('bonus_exchange_params+'.json_encode($params),"bonus_exchange");
					$exchange_bonus = $this->Member_model->exchange_bonus($inter_id,$order ['openid'],  $order ['point_used_amount'],$order ['orderid'], '隐居积分换算扣减',$params);
					if($exchange_bonus['msg']!='ok'){
						$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],3);
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
							'errmsg' => $exchange_bonus['msg']
						);
					}
				}else {
					$params=array();
					$params['crsNo'] = $web_orderid;
					if(!empty($room_codes['room']['bonus_consume_code'])){
						$params['password']=$room_codes ['room'] ['bonus_consume_code'];
					}else{
						$params['password']='';
					}
					if (! $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'], $order ['openid'], $order ['point_used_amount'],$params )) {
						$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],3);
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
						if($inter_id=='a457946152'){
							return array (
								's' => 0,
								'errmsg' => '悦银扣减失败'
							);
						}else{
							return array (
								's' => 0,
								'errmsg' => '积分扣减失败'
							);
						}
					}
				}
				$this->Order_model->update_point_reduce($inter_id,$order ['orderid'],1);
			}
			if ($order ['status'] != 9) {
				$this->db->where ( array (
					'orderid' => $order ['orderid'],
					'inter_id' => $order ['inter_id']
				) );
				$this->db->update ( 'hotel_orders', array (
					'status' => 1
				) );
				$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
			}else if ($order ['paytype'] == 'balance'){
				if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
					$this->load->model ( 'hotel/Member_model' );
					$balance_param=array(
						'crsNo'=>$web_orderid,
						'hotelId' => $hotel_no
						//						'password'=>$room_codes ['room'] ['consume_code']
					);
					if(!empty($room_codes['room']['consume_code'])){
						$balance_param['password']=$room_codes ['room'] ['consume_code'];
					}
					if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'] . ',' . $web_orderid . ',' . $room_codes ['room'] ['consume_code'], '订房订单余额支付',$balance_param,$order)) {
						//云盟储值支付需要入账
						if($inter_id=='a445223616'){
							$this->add_web_bill($web_orderid,$order,$pms_auth,$orderid,true);
						}
						$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true );
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
			}else if ($order ['paytype'] == 'point'){
				if(empty($config_data['PMS_POINT_REDUCE_WAY'])||$config_data['PMS_POINT_REDUCE_WAY']=='after'){
					if(!empty($config_data['BONUS_EXCHANGE_BALANCE'] )){
						$bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);

                        $exchange_count = 0;    //每日房价四舍五入相加
                        foreach($order['order_details'] as $order_detail){
                            if(!empty($order_detail['real_allprice'])){
                                $real_prices = explode(',',$order_detail['real_allprice']);
                                foreach($real_prices as $real_price){
                                    $real_price = $real_price * $bonus_config->percentage;
                                    $exchange_count = $exchange_count + round($real_price);
                                }
                            }
                        }
                        if($exchange_count==0){
                            $params['count'] = $order['price'];
                        }else{
                            $params['count'] = $exchange_count;
                        }

						$params['rate'] = $bonus_config->rate;
						$params['percentage'] = $bonus_config->percentage;
						$params['count'] = round($order['price']*$bonus_config->percentage);
						$params['crsNo'] = $web_orderid;
						$params['hotelId'] = $hotel_no;
						if(!empty($room_codes['room']['point_pay_code'])){
							$params['password']=$room_codes ['room'] ['point_pay_code'];
						}else{
							$params['password']='';
						}
						$this->load->model ( 'hotel/Member_model' );
						if(!$this->Member_model->exchange_bonus($inter_id,$order ['openid'],  $order ['point_used_amount'],$order ['orderid'], '隐居悦银换算扣减',$params)){
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
							if($inter_id=='a457946152'){
								return array (
									's' => 0,
									'errmsg' => '悦银扣减失败'
								);
							}else{
								return array (
									's' => 0,
									'errmsg' => '积分扣减失败'
								);
							}
						}else {
							$this->Order_model->update_order_status ( $order ['inter_id'], $order ['orderid'], 1, $order ['openid'], true,true );
						}
					}else{
						$params=array();
						$params['crsNo'] = $web_orderid;
						if(!empty($room_codes['room']['bonus_consume_code'])){
							$params['password']=$room_codes ['room'] ['bonus_consume_code'];
						}else{
							$params['password']='';
						}
						
						if(!empty($config_data['POINT_PAY_WITH_BILL'])){
							$params['extra']=[
								'taCode'=>$pms_auth['point_taCode'],
								'taRemark'=>'积分支付订单',
								'money'=>$order['price'],
								'taNo'=>$web_orderid,
							    'point_pay_with_bill'=>1,
							];
						}
						
						$this->load->model ( 'hotel/Member_model' );
						if (! $this->Member_model->consum_point ( $order ['inter_id'], $order ['orderid'], $order ['openid'], $order ['point_used_amount'],$params )) {
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
							if($inter_id=='a457946152'){
								return array (
									's' => 0,
									'errmsg' => '悦银扣减失败'
								);
							}else{
								return array (
									's' => 0,
									'errmsg' => '积分扣减失败'
								);
							}
						}else {
							$this->Order_model->update_order_status ( $order ['inter_id'], $order ['orderid'], 1, $order ['openid'], true,true );
						}
					}
				}
			}
			//修改积分储值扣减

			// 			if ($order ['status'] != 9) {
			// 				$this->db->where ( array (
			// 					                   'orderid' => $order ['orderid'],
			// 					                   'inter_id' => $order ['inter_id']
			// 				                   ) );
			// 				$this->db->update ( 'hotel_orders', array (
			// 					'status' => 1
			// 				) );
			// 				$this->Order_model->handle_order ( $inter_id, $orderid, 1 ); // 若pms的订单是即时确认的，执行确认操作
			// 			} else if ($order ['paytype'] == 'balance') {
			// 				$this->load->model ( 'hotel/Hotel_config_model' );
			// 				$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
			// 					'PMS_BANCLANCE_REDUCE_WAY',
			// 					'PMS_BONUS_COMSUME_WAY',
			// 					'BONUS_EXCHANGE_BALANCE'
			// 				) );
			// 				if (! empty ( $config_data ['PMS_BANCLANCE_REDUCE_WAY'] ) && $config_data ['PMS_BANCLANCE_REDUCE_WAY'] == 'after') {
			// 					$this->load->model ( 'hotel/Member_model' );
			// 					$balance_param=array(
			// 						'crsNo'=>$web_orderid,
			// 						'password'=>$room_codes ['room'] ['consume_code']
			// 					);
			// 					if ($this->Member_model->reduce_balance ( $inter_id, $order ['openid'], $order ['price'], $order ['orderid'] . ',' . $web_orderid . ',' . $room_codes ['room'] ['consume_code'], '订房订单余额支付' ,$balance_param)) {
			// 						//云盟储值支付需要入账
			// 						if($inter_id=='a445223616'){
			// 							$this->add_web_bill($web_orderid,$order,$pms_auth,$orderid,true);
			// 						}
			// 						$this->Order_model->update_order_status ( $inter_id, $order ['orderid'], 1, $order ['openid'], true );
			// 					} else {
			// 						$info = $this->Order_model->cancel_order ( $inter_id, array (
			// 							'only_openid' => $order ['openid'],
			// 							'member_no' => '',
			// 							'orderid' => $order ['orderid'],
			// 							'cancel_status' => 5,
			// 							'no_tmpmsg' => 1,
			// 							'delete' => 2,
			// 							'idetail' => array (
			// 								'i'
			// 							)
			// 						) );
			// 						return array (
			// 							's' => 0,
			// 							'errmsg' => '储值支付失败'
			// 						);
			// 					}
			// 				}
			//                 MYLOG::w('bonus_exchange+'.'lvyun_hotel_model_config_data：'.json_encode($config_data).'+user_amount:'.$order ['point_used_amount'].'+point:'.$order ['point_favour'],"bonus_exchange");
			//                 if($config_data['BONUS_EXCHANGE_BALANCE'] && $order ['point_used_amount']>0 && !empty($order ['point_favour'])){
			//                     if (! empty ( $config_data ['PMS_BONUS_COMSUME_WAY'] ) && $config_data ['PMS_BONUS_COMSUME_WAY'] == 'after') {
			//                         $this->load->model ( 'hotel/Member_model' );
			//                         $bonus_config = json_decode($config_data['BONUS_EXCHANGE_BALANCE']);
			//                         $params['rate'] = $bonus_config->rate;
			//                         $params['percentage'] = $bonus_config->percentage;
			//                         $params['count'] = $order['point_favour'];
			//                         $params['crsNo'] = $web_orderid;
			//                         if(!empty($room_codes['room']['bonus_consume_code'])){
			//                             $params['password']=$room_codes ['room'] ['bonus_consume_code'];
			//                         }else{
			//                             $params['password']='';
			//                         }
			//                         MYLOG::w('bonus_exchange_params+'.json_encode($params),"bonus_exchange");
			//                         if(!$this->Member_model->exchange_bonus($inter_id,$order ['openid'],  $order ['point_used_amount'],$order ['orderid'], '隐居悦银换算扣减',$params)){
			//                             $this->Order_model->update_point_reduce($inter_id,$order ['orderid'],3);
			//                             $info = $this->Order_model->cancel_order ( $inter_id, array (
			//                                 'only_openid' => $order ['openid'],
			//                                 'member_no' => '',
			//                                 'orderid' => $order ['orderid'],
			//                                 'cancel_status' => 5,
			//                                 'no_tmpmsg' => 1,
			//                                 'delete' => 2,
			//                                 'idetail' => array (
			//                                     'i'
			//                                 )
			//                             ) );
			//                             return array (
			//                                 's' => 0,
			//                                 'errmsg' => '积分扣减失败'
			//                             );
			//                         }
			//                     }
			//                     $this->Order_model->update_point_reduce($inter_id,$order ['orderid'],1);
			//                 }

			// 			}
			if (! empty ( $paras ['trans_no'] )) {
				$this->add_web_bill ( $web_orderid, $order, $pms_auth, $paras ['trans_no'] );
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
				'errmsg' => $res ['resultMsg']
			);
		}

	}

	function add_web_bill($web_orderid, $order, $pms_auth, $trans_no = '',$is_balance=false) {
		$url = $pms_auth ['url'] . '/saveWebPay';
// 		$taCode = empty ( $pms_auth ['taCode'] ) ? '' : $pms_auth ['taCode'];
		$data = array (
			'crsNo' => $web_orderid,
			'money' => $order ['price'],
			'taNo' => $trans_no,
			// 				'taCode' => $taCode,
			'taRemark' => '微信支付，支付流水号：' . $trans_no,
			'hotelGroupId' => $pms_auth ['hotelGroupId']
		);

		if('a487576098'==$order['inter_id']&&!empty($order['_third_no'])){
			//万信入账时，使用流水号，并非商户单号
			$data['taNo']=$order['_third_no'];
			$data['taRemark']='微信支付，支付流水号：'.$order['_third_no'];
		}

		if($is_balance){
			$data['taRemark']='储值支付，支付流水号：'.$trans_no;
		}

		//书香要传taCode，隐居不用,
		//云盟使用taCode，万信使用taCode
		$inter_arr = [
			'a449675133',
			'a445223616',
			'a487647571',
			'a487576098'
		];
		if(in_array($order['inter_id'],$inter_arr)||!empty($pms_auth['bill_ta'])){
			$data['taCode'] = $pms_auth ['taCode'];
		}
		$res = $this->post_to ( $url, $data, $order ['inter_id'],array('orderid'=>$order ['orderid']),$pms_auth );

		$web_paid = 2;
		$s = FALSE;
		if (isset ( $res ['resultCode'] ) && $res ['resultCode'] == 0) {
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
	function get_to($url, $data = array(), $inter_id = '', $func_data = array(),$pms_auth=[]) {
		$this->load->helper ( 'common' );
		
		if(!empty($pms_auth['need_sign'])){
			$data['hotelGroupCode']=$pms_auth['hotelGroupCode'];
			$data['appKey']=$pms_auth['key'];
			
			$this->load->library ( 'Cache/Redis_proxy', array (
			        'not_init' => FALSE,
			        'module' => 'hotel',
			        'refresh' => FALSE,
			        'environment' => ENVIRONMENT
			), 'redis_proxy' );
			
// 			$session_id= $this->session->userdata('sess:'.$inter_id);
			$session_id= $this->redis_proxy->get('lvyunsess:'.$inter_id);
			if(empty($session_id)){
				$session_id=$this->api_login($pms_auth,$inter_id);
			}
			$data['sessionId']=$session_id;
			
			$sign_arr = $this->sign($data,$pms_auth['secret']);
			$data['sign']=$sign_arr['sign'];
		}
		
		$r_url = $url;
		$url_param = '';
		if (! empty ( $data )) {
			foreach ( $data as $k => $d ) {
				$url_param .= '&' . $k . '=' . $d;
			}
		}
		$url .= empty ( $url_param ) ? '' : '?' . substr ( $url_param, 1 );
		$now = time ();
		
		
		
		$s = doCurlGetRequest ( $url );
		$mirco_receive_time=microtime ();
		$openid=$this->session->userdata ( $inter_id . 'openid' );
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'lvyun', $r_url, $data, $s,'query_get', $now, $mirco_receive_time,$openid );
		$run_alarm = empty($s) ? 1:0;
		
		$s = json_decode ( $s, true );
		
		$func_data ['openid'] = $openid;
		$this->check_web_result ( $inter_id, $r_url, $data, $s, $now, $mirco_receive_time, $func_data, array (
				'run_alarm' => $run_alarm
		) );
		
		return $s;
	}
	
	private function api_login($pms_auth, $inter_id){
		$this->load->helper('common');
		$r_url=$pms_auth['sign_url'];
		$now=time();
		$data = [
			'v'              => $pms_auth['v'],
			'hotelGroupCode' => $pms_auth['hotelGroupCode'],
			'usercode'       => $pms_auth['user'],
			'password'       => $pms_auth['pwd'],
			'method'         => 'user.login',
			'local'          => 'zh_CN',
			'format'         => 'json',
			'appKey'         => $pms_auth['key'],
		];
		
		$sign_arr = $this->sign($data, $pms_auth['secret']);
		$data['sign']= $sign_arr['sign'];
		
		$rs = doCurlGetRequest ( $pms_auth['sign_url'],$data );
		$mirco_receive_time=microtime ();
		$openid=$this->session->userdata ( $inter_id . 'openid' );
		
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'lvyun', $r_url, $data, $rs,'query_get', $now, $mirco_receive_time,$openid );
		$run_alarm = empty($rs) ? 1:0;
		
		$rs = json_decode ( $rs, true );
		
		$func_data=[
			'sign_str'=>$sign_arr['string'],
			'openid'=>$openid
		];
		$this->check_web_result ( $inter_id, $r_url, $data, $rs, $now, $mirco_receive_time, $func_data, array (
			'run_alarm' => $run_alarm
		));
		
		$session_id='';
		if(isset($rs['resultCode'])&&$rs['resultCode']==0){
			$session_id=$rs['resultInfo'];
			$this->load->library ( 'Cache/Redis_proxy', array (
			        'not_init' => FALSE,
			        'module' => 'hotel',
			        'refresh' => FALSE,
			        'environment' => ENVIRONMENT
			), 'redis_proxy' );
				
// 			$this->session->set_userdata('sess:'.$inter_id,$session_id);
			$this->redis_proxy->set('lvyunsess:'.$inter_id,$session_id,300);
		}
		return $session_id;
	}

	function post_to($url, $data = array(), $inter_id = '', $func_data = array(),$pms_auth=[]) {
		$this->load->helper ( 'common' );
		
		if(!empty($pms_auth['need_sign'])){
			$data['hotelGroupCode']=$pms_auth['hotelGroupCode'];
			$data['appKey']=$pms_auth['key'];
			
			$this->load->library ( 'Cache/Redis_proxy', array (
			        'not_init' => FALSE,
			        'module' => 'hotel',
			        'refresh' => FALSE,
			        'environment' => ENVIRONMENT
			), 'redis_proxy' );
				
			// 			$session_id= $this->session->userdata('sess:'.$inter_id);
			$session_id= $this->redis_proxy->get('lvyunsess:'.$inter_id);
			
			if(empty($session_id)){
				$session_id=$this->api_login($pms_auth,$inter_id);
			}
			$data['sessionId']=$session_id;
			
			$sign_arr = $this->sign($data,$pms_auth['secret']);
			$data['sign']=$sign_arr['sign'];
		}
		
		$send_content = http_build_query ( $data );
		$now = time ();
		
		$s = doCurlPostRequest ( $url, $send_content );
		$mirco_receive_time=microtime ();
		$openid=$this->session->userdata ( $inter_id . 'openid' );
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'lvyun', $url, $data, $s,'query_post', $now,$mirco_receive_time, $openid);
		$run_alarm = empty($s) ? 1:0;
		$s = json_decode ( $s, true );
		$func_data ['openid'] = $openid;
		$this->check_web_result ( $inter_id, $url, $data, $s, $now, $mirco_receive_time, $func_data, array (
				'run_alarm' => $run_alarm
		) );
		return $s;
	}
	
	public function sign($data,$secret){
		ksort($data, SORT_STRING);
		$key_str = $secret;
		foreach($data as $k => $v){
			$key_str .= $k . $v;
		}
		$key_str .= $secret;
		return [
			'string' => $key_str,
			'sign'   => strtoupper(sha1($key_str)),
		];
	}
	
	//判断订单是否能支付
	function check_order_canpay($list, $pms_set) {
		$pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
		$url = $pms_auth ['url'] . '/findResrvGuest';
		$data = array (
			"cardNo" => "",
			"crsNo" => $list ['web_orderid'],
			"hotelGroupId" => $pms_auth ['hotelGroupId']
		);
		$res = $this->post_to ( $url, $data, $list['inter_id'] ,['orderid'=>$list['orderid']],$pms_auth);
		if (isset ( $res ['resultCode'] ) && $res ['resultCode'] == 0) {
			$status_arr = $this->pms_enum ( 'status' );
			$new_status = $status_arr [$res ['guest'] ['sta']];
		}
		if(isset($new_status) && ($new_status == 1 || $new_status == 0)){//订单预定或确认
			return true;
		}else{
			return false;
		}
	}
	function check_web_result($inter_id, $web_path, $send, $receive, $now, $micro_receive_time, $func_data = array(), $params = array()) {
		$func_name = substr ( $web_path, strrpos ( $web_path, '/' ) + 1 );
		$func_name_des = $this->pms_enum ( 'func_name', $func_name );
		isset ( $func_name_des ) or $func_name_des = $func_name;
		$err_msg = '';
		$err_lv = NULL;
		$alarm_wait_time = 5;
		if (! empty ( $params ['run_alarm'] )) {
			$err_msg = '程序报错,' . json_encode ( $receive, JSON_UNESCAPED_UNICODE );
			$err_lv = 1;
		} else {
			switch ($func_name) {
				case 'cancelbook' :
				case 'findResrvGuest' :
				case 'findResrvGuestHistory' :
				case 'queryHotelList' :
					if (empty ( $receive ) || $receive ['resultCode'] != 0) {
						$err_msg = isset ( $receive['resultMsg'] ) ? $receive['resultMsg'] : '无';
						isset($receive ['resultCode']) and $err_msg .=',resultcode:'.$receive ['resultCode'];
						$err_lv = 2;
					}
					break;
				case 'book' :
				case 'bookWithCoupon' :
				case 'saveWebPay' :
					if (empty ( $receive ) || $receive ['resultCode'] != 0) {
						$err_msg = isset ( $receive['resultMsg'] ) ? $receive['resultMsg'] : '无';
						isset($receive ['resultCode']) and $err_msg .=',resultcode:'.$receive ['resultCode'];
						$err_lv = 1;
					}
					break;
				default :
					break;
			}
		}
		$this->load->model ( 'common/Webservice_model' );
		$this->Webservice_model->webservice_error_log ( $inter_id, self::WEB_TYPE, $err_lv, $err_msg, array (
				'web_path' => $web_path,
				'send' => $send,
				'receive' => $receive,
				'send_time' => $now,
				'receive_time' => $micro_receive_time,
				'fun_name' => $func_name_des,
				'alarm_wait_time' => $alarm_wait_time
		), $func_data );
	}
	function order_checkin_type($inter_id,$order,$order_item,$pms_set) {
        if (empty ( $order ['member_no'] ) || empty ( $order ['web_orderid'] )) {
            return 'unknown';
        }
        $room_codes = json_decode ( $order ['room_codes'], TRUE );
        $room_codes = $room_codes [$order ['first_detail'] ['room_id']];
        if (empty ( $room_codes ['code'] ['extra_info'] ['memname'] )) {
            return 'unknown';
        }
        $member_name = $room_codes ['code'] ['extra_info'] ['memname'];
        $pms_auth = json_decode ( $pms_set ['pms_auth'], TRUE );
        $url = $pms_auth ['url'] . '/listMasterInHotel';
        $data = array (
                'hotelGroupId' => $pms_auth ['hotelGroupId'],
                'name' => $member_name,
                'hotelId' => '' 
        );
        $res = $this->post_to ( $url, $data, $pms_set ['inter_id'], '', $pms_auth );
        if (! empty ( $res ['resultCode'] ) && $res ['resultCode'] == 1 && ! empty ( $res ['result'] )) {
            $other = 0;
            foreach ( $res ['result'] as $r ) {
                if ($r ['crsNo'] == $order ['web_orderid']) {
                    if ($r ['name'] == $member_name) {
                        return 'self';
                    } else {
                        $other = 1;
                    }
                }
            }
            if ($other == 1) {
                return 'other';
            }
        }
        return 'unknown';
    }
    function get_web_nums($pms_set,$pms_auth, $startdate, $enddate, $rm_cds,$num_type=1) {
        $data = array (
                'arr' => date ( 'Y-m-d', strtotime ( $startdate ) ),
                'dep' => date ( 'Y-m-d', strtotime ( $enddate ) ),
                'salesChannel' => isset ( $pms_auth ['priceSalesChannel'] ) ? $pms_auth ['priceSalesChannel'] : $pms_auth ['salesChannel'],
                'hotelId' => $pms_set ['hotel_web_id'],
                'hotelGroupId' => $pms_auth ['hotelGroupId'],
                'rmtype' => $rm_cds 
        );
        $func_data = [ 
                'hotel_id' => $pms_set ['hotel_id'] 
        ];
        $url = $pms_auth ['url'] . '/listRoomAvail';
        $web_nums = $this->get_to ( $url, $data, $pms_set ['inter_id'], $func_data, $pms_auth );
        $room_web_rums = array ();
        if (! empty ( $web_nums ['resultCode'] ) && $web_nums ['resultCode'] == 1 && ! empty ( $web_nums ['result'] )) {
            foreach ( $web_nums ['result'] as $wn ) {
                $room_web_rums [$wn ['rmtype']] [date ( 'Ymd', strtotime ( $wn ['occDate'] ) )] = $wn ['availWithLimit'];
            }
        }
        return $room_web_rums;
    }
}