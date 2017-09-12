<?php
class Order_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_H = 'hotels';
	const TAB_HO = 'hotel_orders';
	const TAB_HOI = 'hotel_order_items';
	const TAB_HR = 'hotel_rooms';
	const TAB_HPS = 'hotel_price_set';
	const TAB_HPI = 'hotel_price_info';
	const TAB_HRS = 'hotel_room_state';
	const TAB_HOA = 'hotel_order_additions';
	const TAB_HNQ = 'hotels_notify_queue';
	const TAB_HNC = 'hotels_notify_config';
	const TAB_HNR = 'hotels_notify_reg';
	const TAB_DGA = 'distribute_grade_all';
	const TAB_STF = 'hotel_staff';
	public $third_prepay_ways=array(
	        'weixin','weifutong','lakala','lakala_y','unionpay'
	);//第三方预付方式
	function get_last_order($inter_id, $openid, $nums, $effect = true, $group_by = '') {
		$db_read = $this->load->database('iwide_r1',true);
		$sql = 'SELECT o.*,h.name hname,h.city hcity from (SELECT * FROM ' . $db_read->dbprefix ( self::TAB_HO ) . " WHERE `inter_id` = '$inter_id' AND `openid` = '$openid'";
		if ($effect == true)
			$sql .= " AND status IN (1,2,3) ";
		$sql .= " ORDER BY order_time desc limit 0,$nums) o JOIN (SELECT * FROM " . $db_read->dbprefix ( self::TAB_H ) . " WHERE inter_id='$inter_id' AND status=1 ) h 
				ON h.hotel_id=o.hotel_id AND h.inter_id=o.inter_id ";
		if (! empty ( $group_by ))
			$sql .= " group by $group_by";
		return $db_read->query ( $sql )->result_array ();
	}

	function date_validate($startdate, $enddate,$inter_id='',$hotel_id=0,$config_data=array()) {
        if (! $config_data) {
            $this->load->model ( 'hotel/Hotel_config_model' );
            $config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $hotel_id, array (
                    'BOOK_DATE_VALIDATE',
                    'MIN_START_DATE' 
            ) );
        }
        $start_val = 0;
        $start_disp = 0;
        $startdate = date ( "Ymd", strtotime ( $startdate ) );
        $enddate = date ( "Ymd", strtotime ( $enddate ) );
        if (! empty ( $config_data ['BOOK_DATE_VALIDATE'] )) {
            $condition = json_decode ( $config_data ['BOOK_DATE_VALIDATE'], true );
            if (! empty ( $condition ['startdate'] )) {
                foreach ( $condition ['startdate'] as $v ) {
                    $hour = $v ['hour'];
                    switch ($v ['compare']) {
                        case 'less' : // 当前时间少于值
                            if (date ( 'H' ) < $hour) {
                                $start_val = intval ( $v ['val'] );
                            }
                            break;
                        case 'more' :
                            if (date ( 'H' ) > $hour) {
                                $start_val = intval ( $v ['val'] );
                            }
                            break;
                    }
                    // 循环，出现多次条件匹配，以最后为准
                }
            }
        }
        if (! empty ( $config_data ['MIN_START_DATE'] )) {
            $min_date_diff = ceil ( (strtotime ( $config_data ['MIN_START_DATE'] ) - strtotime ( date ( 'Ymd' ) )) / 86400 );
            if ($min_date_diff > 0) {
                $start_disp = $min_date_diff;
                $start_val = $start_disp + $start_val;
            }
        }
        $compare = array (
                date ( 'Ymd', time () + (intval ( $start_disp ) * 86400) ),
                date ( 'Ymd', time () + (intval ( $start_val ) * 86400) ) 
        );
        if (! strtotime ( $startdate ) || date ( "Ymd", strtotime ( $startdate ) ) < min ( $compare )) {
            $startdate = max ( $compare );
        }
        if (! strtotime ( $enddate ) || date ( "Ymd", strtotime ( $enddate ) ) <= $startdate) {
            $enddate = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $startdate ) ) );
        }
        return array (
                $startdate,
                $enddate,
                $start_val 
        );
    }
	function get_rooms_change_calendar($rooms, $idents = array(), $condit = array()) {
		$db_read = $this->load->database('iwide_r1',true);
		if (! empty ( $idents ['query_site'] ) && $idents ['query_site'] == 'admin') {
			$enddate = $condit ['enddate'];
		} else {
			$enddate = date ( 'Ymd', strtotime ( '- 1 day', strtotime ( $condit ['enddate'] ) ) );
		}
		$countday = get_room_night($condit ['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$room_ids = '';
		foreach ( $rooms as $rm ) {
			$room_ids .= ',' . $rm ['room_id'];
		}
		$room_ids = substr ( $room_ids, 1 );
		if (empty ( $room_ids ))
			return $rooms;
		$channel_code = empty ( $idents ['channel_code'] ) ? 'Weixin' : $idents ['channel_code'];
		$price_codes = empty ( $condit ['price_codes'] ) ? '' : $condit ['price_codes'];
		$extra_price_code = empty ( $condit ['extra_price_code'] ) ? '' : $condit ['extra_price_code'];
		$common_type = "'common','member','ticket','athour'";
		$extra_type = $common_type;

		// @author lGh 协议价 2016-3-22 17:54:01
		$price_names = array ();
		$company_prices = array ();
		if (! empty ( $condit ['openid'] )) {
			$this->load->model ( 'hotel/Company_model' );
			$company_price = $this->Company_model->get_cprice_by_openid ( $idents ['inter_id'], $idents ['hotel_id'], $condit ['openid'] );
			if (! empty ( $company_price )) {
				$extra_price_code .= empty ( $extra_price_code ) ? $company_price->price_code : ',' . $company_price->price_code;
				$common_type .= ",'protrol'";
				$price_names [$company_price->price_code] = array('name'=>$company_price->company_name,'info'=>$company_price->cp_code);
			}else{
				$company_price = $this->Company_model->get_club_by_openid ( $idents ['inter_id'], $idents ['hotel_id'], $condit ['openid'] );
				if (!empty($company_price)){
					foreach ($company_price as $c){
						$company_prices[$c->price_code][$c->club_id]=array('name'=>$c->company_name);
						$extra_price_code .= empty ( $extra_price_code ) ? $c->price_code : ',' . $c->price_code;
					}
					$common_type .= ",'protrol'";
				}
			}
		}

		if ($countday == 1) {
			$common_type .= ",'athour'";
			$extra_type .= ",'athour'";
		}
		$type = empty ( $condit ['price_type'] ) ? " in ($common_type)" : " in ($common_type,'" . implode ( "','", $condit ['price_type'] ) . "')";
		$datas = array ();
		$price_info_sql = "SELECT i.*,s.add_service_set sadd_service_set,s.must_date smust_date,s.status sstatus,s.external_code sexternal_code,s.external_way sexternal_way,s.related_cal_way srelated_cal_way,s.related_cal_value srelated_cal_value,s.room_id,s.hotel_id,s.price sprice,s.nums snums,s.use_condition suse_condition,s.coupon_condition scoupon_condition,s.bonus_condition sbonus_condition
						    FROM `" . $db_read->dbprefix ( self::TAB_HPI ) . "` i join 
							(SELECT * from `" . $db_read->dbprefix ( self::TAB_HPS ) . "` where room_id in ($room_ids) and inter_id = '" . $idents ['inter_id'] . "' and status in (1,2)) s
							 on s.inter_id=i.inter_id and s.price_code=i.price_code 
							  where i.status = 1 and i.channel_code='" . $channel_code . "' and i.inter_id='" . $idents ['inter_id'] . "' and i.type $type";
		if (! empty ( $price_codes )) {
			$price_codes = "'" . implode ( "','", explode ( ',', $price_codes ) ) . "'";
			$price_info_sql .= " and i.price_code in ( " . $price_codes . ')';
		} else if (! empty ( $extra_price_code )) {
			$extra_price_code = "'" . implode ( "','", explode ( ',', $extra_price_code ) ) . "'";
			$price_info_sql .= " and (i.price_code in ( " . $extra_price_code . ") or type in ($extra_type))";
		}
		$price_info_sql .= " order by i.sort desc";
		$price_info_set = $db_read->query ( $price_info_sql )->result_array ();
		$set = array ();
		$related_codes = array ();
		$my_coupons=NULL;
		$price_code_in = '';
		foreach ( $price_info_set as $pcs ) {
			$price_code_in .= ',' . $pcs ['price_code'];
			$pcs ['use_condition'] = empty ( $pcs ['suse_condition'] ) ? json_decode ( $pcs ['use_condition'], TRUE ) : json_decode ( $pcs ['suse_condition'], TRUE );
			$pcs ['coupon_condition'] = empty ( $pcs ['scoupon_condition'] ) ? json_decode ( $pcs ['coupon_condition'], TRUE ) : json_decode ( $pcs ['scoupon_condition'], TRUE );
			$pcs ['bonus_condition'] = empty ( $pcs ['sbonus_condition'] ) ? json_decode ( $pcs ['bonus_condition'], TRUE ) : json_decode ( $pcs ['sbonus_condition'], TRUE );
			$pcs ['add_service_set'] = empty ( $pcs ['sadd_service_set'] ) ? json_decode ( $pcs ['add_service_set'], TRUE ) : json_decode ( $pcs ['sadd_service_set'], TRUE );
			$pcs ['time_condition'] = json_decode ( $pcs ['time_condition'], TRUE );
			if (! empty ( $pcs ['srelated_cal_way'] )) {
				$pcs ['related_cal_way'] = $pcs ['srelated_cal_way'];
				$pcs ['related_cal_value'] = $pcs ['srelated_cal_value'];
			}
			$pcs ['must_date'] = empty ( $pcs ['smust_date'] ) ? $pcs ['must_date'] : $pcs ['smust_date'];
			$pcs ['external_code'] = $pcs ['sexternal_code'] === '' ? $pcs ['external_code'] : $pcs ['sexternal_code'];
			$pcs ['external_way'] = empty ( $pcs ['sexternal_way'] ) ? $pcs ['external_way'] : $pcs ['sexternal_way'];
			$pcs ['disp_type'] = 'buy';
			if (! is_null ( $pcs ['related_code'] )) {
				$related_codes [] = $pcs ['related_code'];
			}
			if (isset ( $pcs ['use_condition'] ['member_level'] )) {
				if ($pcs ['type'] != 'member' && $pcs ['use_condition'] ['member_level'] != $condit ['member_level']) {
					continue;
				} else if ($pcs ['type'] == 'member' && isset ( $condit ['member_level'] ) && ! empty ( $condit ['member_privilege'] ) && array_key_exists ( $pcs ['use_condition'] ['member_level'], $condit ['member_privilege'] )) {
					$pcs ['related_cal_way'] = $condit ['member_privilege'] [$pcs ['use_condition'] ['member_level']] ['related_cal_way'];
					$pcs ['related_cal_value'] = $condit ['member_privilege'] [$pcs ['use_condition'] ['member_level']] ['related_cal_value'];
					if ($condit ['member_level'] != $pcs ['use_condition'] ['member_level']) {
						$pcs ['disp_type'] = 'only_show';
					} else {
						$pcs ['disp_type'] = 'buy_show';
					}
				}
			}
			// if (isset ( $pcs ['use_condition'] ['book_time'] )) {
			// 	$now_time = date ( 'Hi' );
			// 	if (($pcs ['use_condition'] ['book_time'] ['s'] != '' && $now_time < intval ( $pcs ['use_condition'] ['book_time'] ['s'] )) || ($pcs ['use_condition'] ['book_time'] ['e'] != '' && $now_time > intval ( $pcs ['use_condition'] ['book_time'] ['e'] ))) {
			// 		continue;
			// 	}
			// }
			/* 价格日历专用
			if (isset ( $pcs ['use_condition'] ['pre_d'] )) {
				$pre_day=ceil ( (strtotime ( $condit ['startdate'] ) - strtotime ( date ( 'Ymd' ) )) / 86400 );
				if (($pre_day < $pcs ['use_condition'] ['pre_d'])||($pcs ['use_condition'] ['pre_d']==0&&$pre_day!=0)) {
					continue;
				}
			}*/

			//@Editor lGh 2016-5-31 21:22:07 增加开始与结束日期配置
			// if (!empty( $pcs ['use_condition'] ['s_date_s'] )&&strtotime($pcs ['use_condition'] ['s_date_s'])&&$condit ['startdate']<$pcs ['use_condition'] ['s_date_s']) {
			// 	continue;
			// }
			// if (!empty( $pcs ['use_condition'] ['s_date_e'] )&&strtotime($pcs ['use_condition'] ['s_date_e'])&&$condit ['startdate']>$pcs ['use_condition'] ['s_date_e']) {
			// 	continue;
			// }
			// if (!empty( $pcs ['use_condition'] ['e_date_s'] )&&strtotime($pcs ['use_condition'] ['e_date_s'])&&$condit ['enddate']<$pcs ['use_condition'] ['e_date_s']) {
			// 	continue;
			// }
			// if (!empty( $pcs ['use_condition'] ['e_date_e'] )&&strtotime($pcs ['use_condition'] ['e_date_e'])&&$condit ['enddate']>$pcs ['use_condition'] ['e_date_e']) {
			// 	continue;
			// }
			
			//@Editor lGh 2016-7-6 16:03:25 最大天数限制
			// if (!empty($pcs['use_condition']['mxd'])&&$pcs['use_condition']['mxd']<$countday){
   //              continue;
   //          }

   //          if (!empty($pcs['use_condition']['min_day'])&&$pcs['use_condition']['min_day']>$countday){
   //              continue;
   //          }
			
			//@Editor lGh 2016-7-6 21:10:47 券关联
			if (!empty($pcs['coupon_condition']['couprel'])&&!empty($condit['openid'])){
				if (!isset($my_coupons)){
					$this->load->model('hotel/Coupon_new_model');
					$my_coupons=$this->Coupon_new_model->myCouponsTypes($condit['openid'],$idents['inter_id']);
				}
				if (!isset($my_coupons[$pcs['coupon_condition']['couprel']])){
					continue;
				}else{
					//关联房券信息，放到数据，
					$pcs['coupon_condition']['couprel_info']=$my_coupons[$pcs['coupon_condition']['couprel']];
				}
			}


			$set [$pcs ['room_id']] [$pcs ['price_code']] = $pcs;
		}
		$price_code_in = substr ( $price_code_in, 1 );
		if (empty ( $price_code_in )) {
			if (! empty ( $condit ['only_room_info'] )) { // 当同时使用pms价格代码与本地价格代码时，增加判断
				foreach ( $rooms as $room_id => $rm ) {
					$datas [$room_id] ['room_info'] = $rm;
				}
				return $datas;
			} else {
// 				return $rooms;
				foreach ( $rooms as $room_id => $rm ) {
					$datas [$room_id] ['room_info'] = $rm;
				}
				return $datas;
			}
		} else {
			$price_code_in .= ',-1';
			$price_code_in = "'" . implode ( "','", array_unique ( explode ( ',', $price_code_in ) ) ) . "'";
		}
		$state_condition = " room_id in ($room_ids) and inter_id='" . $idents ['inter_id'] . "' and price_code in ($price_code_in) and hotel_id=" . $idents ['hotel_id'];
		$pset_sql = "SELECT * from `" . $db_read->dbprefix ( self::TAB_HPS ) . "` where $state_condition ";
		$state_sql = "SELECT state.*,state.price_code scode,pset.price_code,pset.room_id FROM ( $pset_sql ) pset
					   left join (SELECT * from `" . $db_read->dbprefix ( self::TAB_HRS ) . "` where $state_condition and date >= " . $condit ['startdate'] . " and date <= $enddate) state
					    on pset.room_id=state.room_id and (pset.price_code=state.price_code or state.price_code=-1)";
		$room_state = $db_read->query ( $state_sql )->result_array ();
		$state = array ();
		foreach ( $room_state as $rs ) {
			empty ( $rs ['date'] ) ?  : $state [$rs ['room_id']] [$rs ['scode']] [$rs ['date']] = $rs;
		}
		if (! empty ( $related_codes )) {
			$related_state = array ();
			$default_related_state = array ();
			$db_read->where ( array (
					'hotel_id' => $idents ['hotel_id'],
					'inter_id' => $idents ['inter_id']
			) );
			$db_read->where_in ( 'price_code', $related_codes );
			$db_read->where ( "room_id in ($room_ids)", null, false );
			$db_read->where ( "date >= " . $condit ['startdate'] . " and date <=" . $enddate, null, false );
			$related_states = $db_read->get ( self::TAB_HRS )->result_array ();
			foreach ( $related_states as $rs ) {
				$related_state [$rs ['room_id']] [$rs ['price_code']] [$rs ['date']] = $rs;
			}

			$related_state_sql = "SELECT i.*,s.must_date smust_date,s.related_cal_way srelated_cal_way,s.related_cal_value srelated_cal_value,s.room_id,s.hotel_id,s.price sprice,s.nums snums,s.use_condition suse_condition
								FROM `" . $db_read->dbprefix ( self::TAB_HPI ) . "` i join
								(SELECT * from `" . $db_read->dbprefix ( self::TAB_HPS ) . "` where room_id in ($room_ids) and inter_id = '" . $idents ['inter_id'] . "' and status in (1,2)) s
								 on s.inter_id=i.inter_id and s.price_code=i.price_code
								  where i.status = 1 and i.channel_code='" . $channel_code . "' and i.inter_id='" . $idents ['inter_id'] . "' and i.price_code in ( " . implode ( ',', $related_codes ) . ')';
			$related_code_set = $db_read->query ( $related_state_sql )->result_array ();
			foreach ( $related_code_set as $rcs ) {
				$rcs ['must_date'] = empty ( $rcs ['smust_date'] ) ? $rcs ['must_date'] : $rcs ['smust_date'];
				$default_related_state [$rcs ['room_id']] [$rcs ['price_code']] = $rcs;
			}
		}

		$i = 0;
		foreach ( $rooms as $rm ) {
			$room_id = empty ( $condit ['is_ajax'] ) ? $rm ['room_id'] : $i;
			if (! empty ( $set [$rm ['room_id']] ) || ! empty ( $condit ['only_room_info'] )) {
				$datas [$room_id] ['room_info'] = $rm;
				$datas [$room_id] ['state_info'] = array ();
				$j = 0;
				if (empty ( $set [$rm ['room_id']] ))
					continue;
				foreach ( $set [$rm ['room_id']] as $sk => $st ) {
					if ($st ['sstatus'] == 1) {
						$extra_info='';
						$price_id = empty ( $condit ['is_ajax'] ) ? $st ['price_code'] : $j;
						$datas [$room_id] ['state_info'] [$price_id] ['price_name'] = empty ( $price_names [$st ['price_code']] ) ? $st ['price_name'] : $price_names [$st ['price_code']]['name'];
						$datas [$room_id] ['state_info'] [$price_id] ['price_resource'] = 'jinfangka';
						$datas [$room_id] ['state_info'] [$price_id] ['external_code'] = $st ['external_code'];
						$datas [$room_id] ['state_info'] [$price_id] ['external_way'] = $st ['external_way'];
						$datas [$room_id] ['state_info'] [$price_id] ['price_type'] = $st ['type'];
						//@author lGh 2016-3-22 21:46:50 协议价
						if($st ['type']=='protrol'){
							$extra_info=array('unlock_code'=>$st ['unlock_code']);
							if(!empty ( $price_names [$st ['price_code']] )){
								$extra_info['protrol_code']=$price_names [$st ['price_code']]['info'];
							}
							$datas [$room_id] ['state_info'] [$price_id] ['extra_info'] = $extra_info;
						}
						$datas [$room_id] ['state_info'] [$price_id] ['price_code'] = $st ['price_code'];
						$datas [$room_id] ['state_info'] [$price_id] ['des'] = $st ['des'];
						$datas [$room_id] ['state_info'] [$price_id] ['sort'] = $st ['sort'];
						$datas [$room_id] ['state_info'] [$price_id] ['disp_type'] = $st ['disp_type'];
						$datas [$room_id] ['state_info'] [$price_id] ['related_code'] = $st ['related_code'];
						$datas [$room_id] ['state_info'] [$price_id] ['related_des'] = empty ( $st ['related_code'] ) ? '' : $this->cal_related_price ( 0, $st ['related_cal_way'], $st ['related_cal_value'], 'des' );
						$datas [$room_id] ['state_info'] [$price_id] ['related_cal_way'] = $st ['related_cal_way'];
						$datas [$room_id] ['state_info'] [$price_id] ['related_cal_value'] = $st ['related_cal_value'];
						$datas [$room_id] ['state_info'] [$price_id] ['condition'] = $st ['use_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['coupon_condition'] = $st ['coupon_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['bonus_condition'] = $st ['bonus_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['add_service_set'] = $st ['add_service_set'];
						$datas [$room_id] ['state_info'] [$price_id] ['time_condition'] = $st ['time_condition'];
						switch ($st ['must_date']) {
							case 1 :
								{ // 每天都需有数据
									if (! empty ( $state [$rm ['room_id']] [$st ['price_code']] )) {
										for($n = 0; $n < $countday; $n ++) {
											$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
											$tmp = array ();
											if(empty($state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'])){
												$state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'] = 0;
											}
											if(empty($state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'])){
												$state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] = 0;
											}
											$tmp ['price'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'];
											$tmp ['oprice'] = $rm ['oprice'];
											if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
												$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'quick_close';
											} else if (is_null ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] )) {
												if (! is_null ( $st ['snums'] )) {
													$tmp ['total_nums'] = $st ['snums'];
													$tmp ['num_source'] = 'code';
												} else {
													$tmp ['total_nums'] = $rm ['nums'];
													$tmp ['num_source'] = 'room';
												}
											} else {
												$tmp ['total_nums'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'date';
											}
											$tmp ['price_source'] = 'date';
											$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
										}
									} else {
										unset ( $datas [$room_id] ['state_info'] [$price_id] );
										continue;
									}
									break;
								}
							case 2 :
								{ // 需部分有数据
									if (empty ( $state [$rm ['room_id']] [$st ['price_code']] ) || count ( $state [$rm ['room_id']] [$st ['price_code']] ) == 0) {
										unset ( $datas [$room_id] ['state_info'] [$price_id] );
										continue;
									} else {
										for($n = 0; $n < $countday; $n ++) {
											$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
											$tmp = array ();
											if (empty ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'] )) {
												if (! empty ( $st ['related_code'] )) {
													$related_price = 0;
													if (! empty ( $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'] )) {
														$related_price = $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'];
													} else {
														$related_price = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
													}

													$tmp ['price'] = $this->cal_related_price ( $related_price, $st ['related_cal_way'], $st ['related_cal_value'] );
													$tmp ['oprice'] = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
													$tmp ['price_source'] = 'related';
												} else {
													$tmp ['price'] = $st ['sprice'];
													$tmp ['oprice'] = $st ['sprice'];
													$tmp ['price_source'] = 'code';
												}
												if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
													$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
													$tmp ['num_source'] = 'quick_close';
												} else if (! is_null ( $st ['snums'] )) {
													$tmp ['total_nums'] = $st ['snums'];
													$tmp ['num_source'] = 'code';
												} else {
													$tmp ['total_nums'] = $rm ['nums'];
													$tmp ['num_source'] = 'room';
												}
											} else {
												$tmp ['price'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'];
												$tmp ['oprice'] = $st ['sprice'];
												$tmp ['price_source'] = 'date';
												if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
													$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
													$tmp ['num_source'] = 'quick_close';
												} else if (is_null ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] )) {
													if (! is_null ( $st ['snums'] )) {
														$tmp ['total_nums'] = $st ['snums'];
														$tmp ['num_source'] = 'code';
													} else {
														$tmp ['total_nums'] = $rm ['nums'];
														$tmp ['num_source'] = 'room';
													}
												} else {
													$tmp ['total_nums'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'];
													$tmp ['num_source'] = 'date';
												}
											}
											$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
										}
									}
									break;
								}
							case 3 :
								{
									for($n = 0; $n < $countday; $n ++) {
										$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
										$tmp = array ();
										if (empty ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'] )) {
 											isset($default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice']) or $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice']=0;
											if (! empty ( $st ['related_code'] )) {
												$related_price = 0;
												if (! empty ( $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'] )) {
													$related_price = $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'];
												} else {
													$related_price = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
												}
												$tmp ['price'] = $this->cal_related_price ( $related_price, $st ['related_cal_way'], $st ['related_cal_value'] );
												$tmp ['oprice'] = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
												$tmp ['price_source'] = 'related';
											} else {
												$tmp ['price'] = $st ['sprice'];
												$tmp ['oprice'] = $st ['sprice'];
												$tmp ['price_source'] = 'code';
											}
											if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
												$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'quick_close';
											} else if (! is_null ( $st ['snums'] )) {
												$tmp ['total_nums'] = $st ['snums'];
												$tmp ['num_source'] = 'code';
											} else {
												$tmp ['total_nums'] = $rm ['nums'];
												$tmp ['num_source'] = 'room';
											}
										} else {
											$tmp ['price'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'];
											$tmp ['oprice'] = $st ['sprice'];
											$tmp ['price_source'] = 'date';
											if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
												$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'quick_close';
											} else if (is_null ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] )) {
												if (! is_null ( $st ['snums'] )) {
													$tmp ['total_nums'] = $st ['snums'];
													$tmp ['num_source'] = 'code';
												} else {
													$tmp ['total_nums'] = $rm ['nums'];
													$tmp ['num_source'] = 'room';
												}
											} else {
												$tmp ['total_nums'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'date';
											}
										}
										$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
									}
									break;
								}
							default :
								unset ( $datas [$room_id] ['state_info'] [$price_id] );
								continue;
								break;
						}
						$j ++;
					}
				}
			}
			$i ++;
		}
		$this->load->model ( 'hotel/Room_status_model' );
		$stock = $this->Room_status_model->get_hotel_type_stock ( $idents ['inter_id'], $idents ['hotel_id'], $room_ids, $condit ['startdate'], $enddate );
		$price_types = array (
				'protrol' => array (
						'sort' => 1,
						'name' => '协'
				),
				'athour' => array (
						'sort' => 2,
						'name' => '钟'
				)
		);
		foreach ( $datas as $k => $d ) {
			$min = array ();
			$max = array ();
			$all_full = 1;
			$nums = isset ( $condit ['nums'] [$d ['room_info'] ['room_id']] ) ? $condit ['nums'] [$d ['room_info'] ['room_id']] : 1;
			$volume = array ();
			$shows = array ();
			$top_price = array ();
			foreach ( $d ['state_info'] as $dk => $dd ) {
				$least_num = 999999;
				$cflag = 0;
				$state = 'full';
				$temp_total = 0;
				$temp_ototal = 0;
				$temp_allprice = '';
				foreach ( $dd ['date_detail'] as $ddk => $ddt ) {
					$temp_total += $ddt ['price'];
					$temp_ototal += $ddt ['oprice'];
					$temp_allprice .= ',' . $ddt ['price'];

					switch ($ddt ['num_source']) {
						case 'date' :
						case 'code' :
							if (empty ( $stock ['part'] [$d ['room_info'] ['room_id']] [$d ['state_info'] [$dk] ['price_code']] [$ddk] [$ddt ['num_source']] )) {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'];
							} else {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'] - $stock ['part'] [$d ['room_info'] ['room_id']] [$d ['state_info'] [$dk] ['price_code']] [$ddk] [$ddt ['num_source']];
							}
							break;
						case 'room' :
							if (empty ( $stock ['all'] [$d ['room_info'] ['room_id']] [$ddk] [$ddt ['num_source']] )) {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'];
							} else {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'] - $stock ['all'] [$d ['room_info'] ['room_id']] [$ddk] [$ddt ['num_source']];
							}
							break;
						default :
							$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = 0;
							break;
					}

					if ($d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] < $least_num) {
						$least_num = $d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'];
						$d ['state_info'] [$dk] ['least_num'] = $least_num;
						if ($d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] >= $nums) {
							$state = 'available';
						} else {
							$cflag = 1;
						}
					}
				}

				//@Editor lGh 2016-7-6 16:21:14 最大预订数量
				if (!empty($d ['state_info'] [$dk]['condition']['mxn'])&&$d ['state_info'] [$dk]['condition']['mxn']>0&&$d ['state_info'] [$dk]['condition']['mxn']<$d ['state_info'] [$dk] ['least_num']){
					$d ['state_info'] [$dk] ['least_num']=$d ['state_info'] [$dk]['condition']['mxn'];
				}

				if ($cflag == 1 && $state == 'full')
					$d ['state_info'] [$dk] ['book_status'] = 'full';
				else if ($cflag == 1 && $state == 'available')
					$d ['state_info'] [$dk] ['book_status'] = 'lack';
				else if ($cflag == 0) {
					$d ['state_info'] [$dk] ['book_status'] = 'available';
					if ($dd ['disp_type'] != 'only_show') {
						$all_full = 0;
					}
				}
				$d ['state_info'] [$dk] ['total_price'] = $temp_total * $nums;
				$d ['state_info'] [$dk] ['total'] = $temp_total;
				$d ['state_info'] [$dk] ['ototal'] = $temp_ototal;
				$d ['state_info'] [$dk] ['allprice'] = substr ( $temp_allprice, 1 );
				$d ['state_info'] [$dk] ['total_oprice'] = $temp_ototal * $nums;
				$d ['state_info'] [$dk] ['avg_oprice'] = number_format ( $temp_ototal / $countday, 2, '.', '' );
				$tmp_p = number_format ( $temp_total / $countday, 2, '.', '' );
				$d ['state_info'] [$dk] ['avg_price'] = $tmp_p;
				// if ($tmp_p < $lowest) {
				if ($dd ['disp_type'] != 'only_show') {
					$min [] = $tmp_p;
				}
				if (! empty ( $price_types [$d ['state_info'] [$dk] ['price_type']] )) {
					$top_price [$price_types [$d ['state_info'] [$dk] ['price_type']] ['sort']] = $price_types [$d ['state_info'] [$dk] ['price_type']] ['name'];
				}
				if ($dd ['disp_type'] == 'only_show' || $dd ['disp_type'] == 'buy_show') {
					$shows [$dk] = $d ['state_info'] [$dk];
					if ($dd ['disp_type'] == 'only_show')
						unset ( $d ['state_info'] [$dk] );
				}

				//2016-7-4 21:02:42 社群客2.0
				if (!empty($d ['state_info'] [$dk])&&!empty($company_prices[$d ['state_info'] [$dk]['price_code']])){
					foreach ($company_prices[$d ['state_info'] [$dk]['price_code']] as $cp_code=>$cp){
						$tmp_code=$d ['state_info'] [$dk]['price_code'].'_iwidep_clbu_'.$cp_code;
						$d ['state_info'] [$tmp_code]=$d ['state_info'] [$dk];
						$d ['state_info'] [$tmp_code]['price_code']=$tmp_code;
						empty ( $cp['name'] ) ?:$d ['state_info'] [$tmp_code]['price_name']= $cp['name'] ;
// 						$d ['state_info'] [$tmp_code]['extra_info']['protrol_code']=$cp['info'];
					}
					unset($d ['state_info'] [$dk]);
				}

			}
			$datas [$k] ['state_info'] = $d ['state_info'];
			$datas [$k] ['show_info'] = $shows;
			$datas [$k] ['lowest'] = empty($min)?'':number_format ( min ( $min ), 2, '.', '' );
			$datas [$k] ['highest'] = empty($min)?'':number_format ( max ( $min ), 2, '.', '' );
			$datas [$k] ['all_full'] = $all_full;
			$datas [$k] ['top_price'] =$top_price;
		}
		return $datas;
	}
	function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$db_read = $this->load->database('iwide_r1',true);
		if (! empty ( $idents ['query_site'] ) && $idents ['query_site'] == 'admin') {
			$enddate = $condit ['enddate'];
		} else {
			$enddate = date ( 'Ymd', strtotime ( '- 1 day', strtotime ( $condit ['enddate'] ) ) );
		}
		$countday = get_room_night($condit ['startdate'],$condit ['enddate'],'ceil',$condit);//至少有一个间夜
		$room_ids = '';
		foreach ( $rooms as $rm ) {
			$room_ids .= ',' . $rm ['room_id'];
		}
		$room_ids = substr ( $room_ids, 1 );
		if (empty ( $room_ids ))
			return $rooms;
		$channel_code = empty ( $idents ['channel_code'] ) ? 'Weixin' : $idents ['channel_code'];
		$price_codes = empty ( $condit ['price_codes'] ) ? '' : $condit ['price_codes'];
		$extra_price_code = empty ( $condit ['extra_price_code'] ) ? '' : $condit ['extra_price_code'];
		$common_type = "'common','member'";
		$extra_type = $common_type;

		// @author lGh 协议价 2016-3-22 17:54:01
		$price_names = array ();
		$company_prices = array ();
		if (! empty ( $condit ['openid'] )) {
			$this->load->model ( 'hotel/Company_model' );
			$company_price = $this->Company_model->get_cprice_by_openid ( $idents ['inter_id'], $idents ['hotel_id'], $condit ['openid'] );
			if (! empty ( $company_price )) {
				$extra_price_code .= empty ( $extra_price_code ) ? $company_price->price_code : ',' . $company_price->price_code;
				$common_type .= ",'protrol'";
				$price_names [$company_price->price_code] = array('name'=>$company_price->company_name,'info'=>$company_price->cp_code);
			}else{
				$company_price = $this->Company_model->get_club_by_openid ( $idents ['inter_id'], $idents ['hotel_id'], $condit ['openid'] );
				if (!empty($company_price)){
					foreach ($company_price as $c){
						$company_prices[$c->price_code][$c->club_id]=array('name'=>$c->company_name);
						$extra_price_code .= empty ( $extra_price_code ) ? $c->price_code : ',' . $c->price_code;
					}
					$common_type .= ",'protrol'";
				}
			}
		}

		// if ($countday == 1) {
		// 	$common_type .= ",'athour'";
		// 	$extra_type .= ",'athour'";
		// }
		$type = empty ( $condit ['price_type'] ) ? " in ($common_type)" : " in ($common_type,'" . implode ( "','", $condit ['price_type'] ) . "')";
		//add by ping
		if(isset($condit ['only_type']) && $condit ['only_type'] == 'athour'){
			$type = " = 'athour'";
			$extra_type .= ",'athour'";
		}
		if(isset($condit ['only_type']) && $condit ['only_type'] == 'ticket'){
			$type = " = 'ticket'";
			$extra_type .= ",'ticket'";
		}

		$datas = array ();
		$price_info_sql = "SELECT i.*,s.add_service_set sadd_service_set,s.must_date smust_date,s.status sstatus,s.external_code sexternal_code,s.external_way sexternal_way,s.related_cal_way srelated_cal_way,s.related_cal_value srelated_cal_value,s.room_id,s.hotel_id,s.price sprice,s.nums snums,s.use_condition suse_condition,s.coupon_condition scoupon_condition,s.bonus_condition sbonus_condition,s.bookpolicy_condition sbookpolicy_condition
						    FROM `" . $db_read->dbprefix ( self::TAB_HPI ) . "` i join
							(SELECT * from `" . $db_read->dbprefix ( self::TAB_HPS ) . "` where room_id in ($room_ids) and inter_id = '" . $idents ['inter_id'] . "' and status in (1,2)) s
							 on s.inter_id=i.inter_id and s.price_code=i.price_code
							  where i.status = 1 and i.channel_code='" . $channel_code . "' and i.inter_id='" . $idents ['inter_id'] . "' and i.type $type";
		if (! empty ( $price_codes )) {
			$price_codes = "'" . implode ( "','", explode ( ',', $price_codes ) ) . "'";
			$price_info_sql .= " and i.price_code in ( " . $price_codes . ')';
		} else if (! empty ( $extra_price_code )) {
			$extra_price_code = "'" . implode ( "','", explode ( ',', $extra_price_code ) ) . "'";
			$price_info_sql .= " and (i.price_code in ( " . $extra_price_code . ") or type in ($extra_type))";
		}
		$price_info_sql .= " order by i.sort desc";
		$price_info_set = $db_read->query ( $price_info_sql )->result_array ();
		$set = array ();
		$related_codes = array ();
		$my_coupons=NULL;
		$price_code_in = '';
		foreach ( $price_info_set as $pcs ) {
			$price_code_in .= ',' . $pcs ['price_code'];
			$pcs ['use_condition'] = empty ( $pcs ['suse_condition'] ) ? json_decode ( $pcs ['use_condition'], TRUE ) : json_decode ( $pcs ['suse_condition'], TRUE );
			$pcs ['coupon_condition'] = empty ( $pcs ['scoupon_condition'] ) ? json_decode ( $pcs ['coupon_condition'], TRUE ) : json_decode ( $pcs ['scoupon_condition'], TRUE );
			$pcs ['bonus_condition'] = empty ( $pcs ['sbonus_condition'] ) ? json_decode ( $pcs ['bonus_condition'], TRUE ) : json_decode ( $pcs ['sbonus_condition'], TRUE );
			$pcs ['add_service_set'] = empty ( $pcs ['sadd_service_set'] ) ? json_decode ( $pcs ['add_service_set'], TRUE ) : json_decode ( $pcs ['sadd_service_set'], TRUE );
			// 预订政策
			$pcs ['bookpolicy_condition'] = empty ( $pcs ['sbookpolicy_condition'] ) ? json_decode ( $pcs ['bookpolicy_condition'], TRUE ) : json_decode ( $pcs ['sbookpolicy_condition'], TRUE );
			$pcs ['time_condition'] = json_decode ( $pcs ['time_condition'], TRUE );
			$pcs ['customer_condition'] = json_decode ( $pcs ['customer_condition'], TRUE );
			$pcs ['goods_info'] = json_decode ( $pcs ['goods_info'], TRUE );
			if (! empty ( $pcs ['srelated_cal_way'] )) {
				$pcs ['related_cal_way'] = $pcs ['srelated_cal_way'];
				$pcs ['related_cal_value'] = $pcs ['srelated_cal_value'];
			}
			$pcs ['must_date'] = empty ( $pcs ['smust_date'] ) ? $pcs ['must_date'] : $pcs ['smust_date'];
			$pcs ['external_code'] = $pcs ['sexternal_code'] === '' ? $pcs ['external_code'] : $pcs ['sexternal_code'];
			$pcs ['external_way'] = empty ( $pcs ['sexternal_way'] ) ? $pcs ['external_way'] : $pcs ['sexternal_way'];
			$pcs ['disp_type'] = 'buy';
			if (! is_null ( $pcs ['related_code'] )) {
				$related_codes [] = $pcs ['related_code'];
			}
			if (isset ( $pcs ['use_condition'] ['member_level'] )) {
				if ($pcs ['type'] != 'member' && $pcs ['use_condition'] ['member_level'] != $condit ['member_level']) {
				    if (empty($condit['no_check_memberlv'])){
				        continue;
				    }
				} else if ($pcs ['type'] == 'member' && isset ( $condit ['member_level'] ) && ! empty ( $condit ['member_privilege'] ) && array_key_exists ( $pcs ['use_condition'] ['member_level'], $condit ['member_privilege'] )) {
					$pcs ['related_cal_way'] = $condit ['member_privilege'] [$pcs ['use_condition'] ['member_level']] ['related_cal_way'];
					$pcs ['related_cal_value'] = $condit ['member_privilege'] [$pcs ['use_condition'] ['member_level']] ['related_cal_value'];
					if ($condit ['member_level'] != $pcs ['use_condition'] ['member_level']) {
						$pcs ['disp_type'] = 'only_show';
					} else {
						$pcs ['disp_type'] = 'buy_show';
					}
				}
			}
			if (isset ( $pcs ['use_condition'] ['book_time'] )) {
				$now_time = date ( 'Hi' );
				if (($pcs ['use_condition'] ['book_time'] ['s'] != '' && $now_time < intval ( $pcs ['use_condition'] ['book_time'] ['s'] )) || ($pcs ['use_condition'] ['book_time'] ['e'] != '' && $now_time > intval ( $pcs ['use_condition'] ['book_time'] ['e'] ))) {
					continue;
				}
			}
			if (isset ( $pcs ['use_condition'] ['pre_d'] )) {
				$pre_day=ceil ( (strtotime ( $condit ['startdate'] ) - strtotime ( date ( 'Ymd' ) )) / 86400 );
				if (($pre_day < $pcs ['use_condition'] ['pre_d'])||($pcs ['use_condition'] ['pre_d']==0&&$pre_day!=0)) {
					continue;
				}
			}

			//@Editor lGh 2016-5-31 21:22:07 增加开始与结束日期配置
// 			if (!empty( $pcs ['use_condition'] ['s_date_s'] )&&strtotime($pcs ['use_condition'] ['s_date_s'])&&$condit ['startdate']<$pcs ['use_condition'] ['s_date_s']) {
// 				continue;
// 			}
// 			if (!empty( $pcs ['use_condition'] ['s_date_e'] )&&strtotime($pcs ['use_condition'] ['s_date_e'])&&$condit ['startdate']>$pcs ['use_condition'] ['s_date_e']) {
// 				continue;
// 			}
// 			if (!empty( $pcs ['use_condition'] ['e_date_s'] )&&strtotime($pcs ['use_condition'] ['e_date_s'])&&$condit ['enddate']<$pcs ['use_condition'] ['e_date_s']) {
// 				continue;
// 			}
// 			if (!empty( $pcs ['use_condition'] ['e_date_e'] )&&strtotime($pcs ['use_condition'] ['e_date_e'])&&$condit ['enddate']>$pcs ['use_condition'] ['e_date_e']) {
// 				continue;
// 			}
			$check_sdate_s = 1;
			$check_sdate_e = 1;
			if (!empty( $pcs ['use_condition'] ['s_date_s'] )&&strtotime($pcs ['use_condition'] ['s_date_s'])&&$condit ['startdate']<$pcs ['use_condition'] ['s_date_s']) {
			    $check_sdate_s = 0;
			}
			if (!empty( $pcs ['use_condition'] ['s_date_e'] )&&strtotime($pcs ['use_condition'] ['s_date_e'])&&$condit ['startdate']>$pcs ['use_condition'] ['s_date_e']) {
			    $check_sdate_e = 0;
			}
			if(!empty( $pcs ['use_condition'] ['s_date_m'] ) && $pcs ['use_condition'] ['s_date_m'] == 2){
			    if (!($check_sdate_s | $check_sdate_e)){
			        continue;
			    }
			}else{
			    if (!($check_sdate_s & $check_sdate_e)){
			        continue;
			    }
			}
			$check_edate_s = 1;
			$check_edate_e = 1;
			if (!empty( $pcs ['use_condition'] ['e_date_s'] )&&strtotime($pcs ['use_condition'] ['e_date_s'])&&$condit ['enddate']<$pcs ['use_condition'] ['e_date_s']) {
			    $check_edate_s = 0;
			}
			if (!empty( $pcs ['use_condition'] ['e_date_e'] )&&strtotime($pcs ['use_condition'] ['e_date_e'])&&$condit ['enddate']>$pcs ['use_condition'] ['e_date_e']) {
			    $check_edate_e = 0;
			}
			if(!empty( $pcs ['use_condition'] ['e_date_m'] ) && $pcs ['use_condition'] ['e_date_m'] == 2){
			    if (!($check_edate_s | $check_edate_e)){
			        continue;
			    }
			}else{
			    if (!($check_edate_s & $check_edate_e)){
			        continue;
			    }
			}

			//@Editor lGh 2016-7-6 16:03:25 最大天数限制
			if (!empty($pcs['use_condition']['mxd'])&&$pcs['use_condition']['mxd']<$countday){
                continue;
            }

            if (!empty($pcs['use_condition']['min_day'])&&$pcs['use_condition']['min_day']>$countday){
                continue;
            }
			//星期判断
			$startdate_weeks = date('w',strtotime($condit ['startdate']));
			if (!empty($pcs['time_condition']['limit_weeks'])&&is_array($pcs['time_condition']['limit_weeks'])&& !in_array($startdate_weeks,$pcs['time_condition']['limit_weeks'])){
                continue;
            }
			//@Editor lGh 2016-7-6 21:10:47 券关联
			if (!empty($pcs['coupon_condition']['couprel'])&&!empty($condit['openid'])){
				if (!isset($my_coupons)){
					$this->load->model('hotel/Coupon_new_model');
					$my_coupons=$this->Coupon_new_model->myCouponsTypes($condit['openid'],$idents['inter_id']);
				}
				if (!isset($my_coupons[$pcs['coupon_condition']['couprel']])){
					continue;
				}else{
					//关联房券信息，放到数据，
					$pcs['coupon_condition']['couprel_info']=$my_coupons[$pcs['coupon_condition']['couprel']];
				}
			}

			//非套票预订且价格仅能用于套票
			if (empty($condit['is_package'])&&!empty($pcs['use_condition']['package_only'])){
				continue;
			}

			$set [$pcs ['room_id']] [$pcs ['price_code']] = $pcs;
		}
		$price_code_in = substr ( $price_code_in, 1 );
		if (empty ( $price_code_in )) {
			if (! empty ( $condit ['only_room_info'] )) { // 当同时使用pms价格代码与本地价格代码时，增加判断
				foreach ( $rooms as $room_id => $rm ) {
					$datas [$room_id] ['room_info'] = $rm;
				}
				return $datas;
			} else {
// 				return $rooms;
				foreach ( $rooms as $room_id => $rm ) {
					$datas [$room_id] ['room_info'] = $rm;
				}
				return $datas;
			}
		} else {
			$price_code_in .= ',-1';
			$price_code_in = "'" . implode ( "','", array_unique ( explode ( ',', $price_code_in ) ) ) . "'";
		}
		$state_condition = " room_id in ($room_ids) and inter_id='" . $idents ['inter_id'] . "' and price_code in ($price_code_in) and hotel_id=" . $idents ['hotel_id'];
		$pset_sql = "SELECT * from `" . $db_read->dbprefix ( self::TAB_HPS ) . "` where $state_condition ";
		$state_sql = "SELECT state.*,state.price_code scode,pset.price_code,pset.room_id FROM ( $pset_sql ) pset
					   left join (SELECT * from `" . $db_read->dbprefix ( self::TAB_HRS ) . "` where $state_condition and date >= " . $condit ['startdate'] . " and date <= $enddate) state
					    on pset.room_id=state.room_id and (pset.price_code=state.price_code or state.price_code=-1)";
		$room_state = $db_read->query ( $state_sql )->result_array ();
		$state = array ();
		foreach ( $room_state as $rs ) {
			empty ( $rs ['date'] ) ?  : $state [$rs ['room_id']] [$rs ['scode']] [$rs ['date']] = $rs;
		}
		if (! empty ( $related_codes )) {
			$related_state = array ();
			$default_related_state = array ();
			$db_read->where ( array (
					'hotel_id' => $idents ['hotel_id'],
					'inter_id' => $idents ['inter_id']
			) );
			$db_read->where_in ( 'price_code', $related_codes );
			$db_read->where ( "room_id in ($room_ids)", null, false );
			$db_read->where ( "date >= " . $condit ['startdate'] . " and date <=" . $enddate, null, false );
			$related_states = $db_read->get ( self::TAB_HRS )->result_array ();
			foreach ( $related_states as $rs ) {
				$related_state [$rs ['room_id']] [$rs ['price_code']] [$rs ['date']] = $rs;
			}

			$related_state_sql = "SELECT i.*,s.must_date smust_date,s.related_cal_way srelated_cal_way,s.related_cal_value srelated_cal_value,s.room_id,s.hotel_id,s.price sprice,s.nums snums,s.use_condition suse_condition
								FROM `" . $db_read->dbprefix ( self::TAB_HPI ) . "` i join
								(SELECT * from `" . $db_read->dbprefix ( self::TAB_HPS ) . "` where room_id in ($room_ids) and inter_id = '" . $idents ['inter_id'] . "' and status in (1,2)) s
								 on s.inter_id=i.inter_id and s.price_code=i.price_code
								  where i.status = 1 and i.channel_code='" . $channel_code . "' and i.inter_id='" . $idents ['inter_id'] . "' and i.price_code in ( " . implode ( ',', $related_codes ) . ')';
			$related_code_set = $db_read->query ( $related_state_sql )->result_array ();
			foreach ( $related_code_set as $rcs ) {
				$rcs ['must_date'] = empty ( $rcs ['smust_date'] ) ? $rcs ['must_date'] : $rcs ['smust_date'];
				$default_related_state [$rcs ['room_id']] [$rcs ['price_code']] = $rcs;
			}
		}

		$i = 0;
		foreach ( $rooms as $rm ) {
			$room_id = empty ( $condit ['is_ajax'] ) ? $rm ['room_id'] : $i;
			if (! empty ( $set [$rm ['room_id']] ) || ! empty ( $condit ['only_room_info'] )) {
				$datas [$room_id] ['room_info'] = $rm;
				$datas [$room_id] ['state_info'] = array ();
				$j = 0;
				if (empty ( $set [$rm ['room_id']] ))
					continue;
				foreach ( $set [$rm ['room_id']] as $sk => $st ) {
					if ($st ['sstatus'] == 1) {
						$extra_info='';
						$price_id = empty ( $condit ['is_ajax'] ) ? $st ['price_code'] : $j;
						$datas [$room_id] ['state_info'] [$price_id] ['price_name'] = empty ( $price_names [$st ['price_code']] ) ? $st ['price_name'] : $price_names [$st ['price_code']]['name'];
						$datas [$room_id] ['state_info'] [$price_id] ['price_resource'] = 'jinfangka';
						$datas [$room_id] ['state_info'] [$price_id] ['external_code'] = $st ['external_code'];
						$datas [$room_id] ['state_info'] [$price_id] ['external_way'] = $st ['external_way'];
						$datas [$room_id] ['state_info'] [$price_id] ['price_type'] = $st ['type'];
						//@author lGh 2016-3-22 21:46:50 协议价
						if($st ['type']=='protrol'){
							$extra_info=array('unlock_code'=>$st ['unlock_code']);
							if(!empty ( $price_names [$st ['price_code']] )){
								$extra_info['protrol_code']=$price_names [$st ['price_code']]['info'];
							}
							$datas [$room_id] ['state_info'] [$price_id] ['extra_info'] = $extra_info;
						}
						$datas [$room_id] ['state_info'] [$price_id] ['price_code'] = $st ['price_code'];
						$datas [$room_id] ['state_info'] [$price_id] ['des'] = $st ['des'];
						$datas [$room_id] ['state_info'] [$price_id] ['detail'] = nl2br($st ['detail']);
						$datas [$room_id] ['state_info'] [$price_id] ['sort'] = $st ['sort'];
						$datas [$room_id] ['state_info'] [$price_id] ['disp_type'] = $st ['disp_type'];
						$datas [$room_id] ['state_info'] [$price_id] ['related_code'] = $st ['related_code'];
						$datas [$room_id] ['state_info'] [$price_id] ['related_des'] = empty ( $st ['related_code'] ) ? '' : $this->cal_related_price ( 0, $st ['related_cal_way'], $st ['related_cal_value'], 'des' );
						$datas [$room_id] ['state_info'] [$price_id] ['related_cal_way'] = $st ['related_cal_way'];
						$datas [$room_id] ['state_info'] [$price_id] ['related_cal_value'] = $st ['related_cal_value'];
						$datas [$room_id] ['state_info'] [$price_id] ['condition'] = $st ['use_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['coupon_condition'] = $st ['coupon_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['bonus_condition'] = $st ['bonus_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['add_service_set'] = $st ['add_service_set'];
						$datas [$room_id] ['state_info'] [$price_id] ['time_condition'] = $st ['time_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['customer_condition'] = $st ['customer_condition'];
						$datas [$room_id] ['state_info'] [$price_id] ['goods_info'] = $st ['goods_info'];
						$datas [$room_id] ['state_info'] [$price_id] ['is_packages'] = $st ['is_packages'];
						// 增加价格代码早餐和预定政策
						$this->load->model ( 'hotel/Price_code_model' );
						$bf_fields = $this->Price_code_model->grid_fields();
						$bf_fields = $bf_fields['bfnums']['select'];
						if(isset($st ['bookpolicy_condition']['breakfast_nums']))
							$st ['bookpolicy_condition']['breakfast_nums'] = $st ['bookpolicy_condition']['breakfast_nums']==-1?'':$bf_fields[$st ['bookpolicy_condition']['breakfast_nums']];
						else
							$st ['bookpolicy_condition']['breakfast_nums'] = '';
						$datas [$room_id] ['state_info'] [$price_id] ['bookpolicy_condition'] = $st ['bookpolicy_condition'];
						//时租房增加时间段判断
						if($st ['type']=='athour' && isset($st ['time_condition']['book_time']['e']) && $st ['time_condition']['book_time']['e'] < date('Hi')){
							unset ( $datas [$room_id] ['state_info'] [$price_id] );
							continue;
						}
						switch ($st ['must_date']) {
							case 1 :
								{ // 每天都需有数据
									if (! empty ( $state [$rm ['room_id']] [$st ['price_code']] ) && count ( $state [$rm ['room_id']] [$st ['price_code']] ) == $countday) {
										for($n = 0; $n < $countday; $n ++) {
											$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
											$tmp = array ();
											$tmp ['price'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'];
											$tmp ['oprice'] = $rm ['oprice'];
											if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
												$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'quick_close';
											} else if (is_null ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] )) {
												if (! is_null ( $st ['snums'] )) {
													$tmp ['total_nums'] = $st ['snums'];
													$tmp ['num_source'] = 'code';
												} else {
													$tmp ['total_nums'] = $rm ['nums'];
													$tmp ['num_source'] = 'room';
												}
											} else {
												$tmp ['total_nums'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'date';
											}
											$tmp ['price_source'] = 'date';
											$tmp ['type'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['type'];
											$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
										}
									} else {
										unset ( $datas [$room_id] ['state_info'] [$price_id] );
										continue;
									}
									break;
								}
							case 2 :
								{ // 需部分有数据
									if (empty ( $state [$rm ['room_id']] [$st ['price_code']] ) || count ( $state [$rm ['room_id']] [$st ['price_code']] ) == 0) {
										unset ( $datas [$room_id] ['state_info'] [$price_id] );
										continue;
									} else {
										for($n = 0; $n < $countday; $n ++) {
											$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
											$tmp = array ();
											if (empty ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'] )) {
												if (! empty ( $st ['related_code'] )) {
													$related_price = 0;
													if (! empty ( $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'] )) {
														$related_price = $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'];
													} else {
														$related_price = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
													}

													$tmp ['price'] = $this->cal_related_price ( $related_price, $st ['related_cal_way'], $st ['related_cal_value'] );
													$tmp ['oprice'] = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
													$tmp ['price_source'] = 'related';
												} else {
													$tmp ['price'] = $st ['sprice'];
													$tmp ['oprice'] = $st ['sprice'];
													$tmp ['price_source'] = 'code';
												}
												if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
													$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
													$tmp ['num_source'] = 'quick_close';
												} else if (! is_null ( $st ['snums'] )) {
													$tmp ['total_nums'] = $st ['snums'];
													$tmp ['num_source'] = 'code';
												} else {
													$tmp ['total_nums'] = $rm ['nums'];
													$tmp ['num_source'] = 'room';
												}
											} else {
												$tmp ['price'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'];
												$tmp ['oprice'] = $st ['sprice'];
												$tmp ['price_source'] = 'date';
												if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
													$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
													$tmp ['num_source'] = 'quick_close';
												} else if (is_null ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] )) {
													if (! is_null ( $st ['snums'] )) {
														$tmp ['total_nums'] = $st ['snums'];
														$tmp ['num_source'] = 'code';
													} else {
														$tmp ['total_nums'] = $rm ['nums'];
														$tmp ['num_source'] = 'room';
													}
												} else {
													$tmp ['total_nums'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'];
													$tmp ['num_source'] = 'date';
												}
											}
											$tmp ['type'] = $tmp ['price_source'] == 'date' ?$state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['type']:'common';
											$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
										}
									}
									break;
								}
							case 3 :
								{
									for($n = 0; $n < $countday; $n ++) {
										$tmpdate = date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $condit ['startdate'] ) ) );
										$tmp = array ();
										if (empty ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'] )) {
 											isset($default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice']) or $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice']=0;
											if (! empty ( $st ['related_code'] )) {
												$related_price = 0;
												if (! empty ( $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'] )) {
													$related_price = $related_state [$rm ['room_id']] [$st ['related_code']] [$tmpdate] ['price'];
												} else {
													$related_price = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
												}
												$tmp ['price'] = $this->cal_related_price ( $related_price, $st ['related_cal_way'], $st ['related_cal_value'] );
												$tmp ['oprice'] = $default_related_state [$rm ['room_id']] [$st ['related_code']] ['sprice'];
												$tmp ['price_source'] = 'related';
											} else {
												$tmp ['price'] = $st ['sprice'];
												$tmp ['oprice'] = $st ['sprice'];
												$tmp ['price_source'] = 'code';
											}
											if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
												$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'quick_close';
											} else if (! is_null ( $st ['snums'] )) {
												$tmp ['total_nums'] = $st ['snums'];
												$tmp ['num_source'] = 'code';
											} else {
												$tmp ['total_nums'] = $rm ['nums'];
												$tmp ['num_source'] = 'room';
											}
										} else {
											$tmp ['price'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['price'];
											$tmp ['oprice'] = $st ['sprice'];
											$tmp ['price_source'] = 'date';
											if (! empty ( $state [$rm ['room_id']] ['-1'] [$tmpdate] )) {
												$tmp ['total_nums'] = $state [$rm ['room_id']] ['-1'] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'quick_close';
											} else if (is_null ( $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'] )) {
												if (! is_null ( $st ['snums'] )) {
													$tmp ['total_nums'] = $st ['snums'];
													$tmp ['num_source'] = 'code';
												} else {
													$tmp ['total_nums'] = $rm ['nums'];
													$tmp ['num_source'] = 'room';
												}
											} else {
												$tmp ['total_nums'] = $state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['nums'];
												$tmp ['num_source'] = 'date';
											}
										}
										$tmp ['type'] = $tmp ['price_source'] == 'date' ?$state [$rm ['room_id']] [$st ['price_code']] [$tmpdate] ['type']:'common';
										$datas [$room_id] ['state_info'] [$price_id] ['date_detail'] [$tmpdate] = $tmp;
									}
									break;
								}
							default :
								unset ( $datas [$room_id] ['state_info'] [$price_id] );
								continue;
								break;
						}
						$j ++;
					}
				}
			}
			$i ++;
		}
		$this->load->model ( 'hotel/Room_status_model' );
		$stock = $this->Room_status_model->get_hotel_type_stock ( $idents ['inter_id'], $idents ['hotel_id'], $room_ids, $condit ['startdate'], $enddate );
		$price_types = array (
				'protrol' => array (
						'sort' => 1,
						'name' => '协'
				),
				'athour' => array (
						'sort' => 2,
						'name' => '钟'
				)
		);
		foreach ( $datas as $k => $d ) {
			$min = array ();
			$max = array ();
			$all_full = 1;
			$nums = isset ( $condit ['nums'] [$d ['room_info'] ['room_id']] ) ? $condit ['nums'] [$d ['room_info'] ['room_id']] : 1;
			$volume = array ();
			$shows = array ();
			$top_price = array ();
			foreach ( $d ['state_info'] as $dk => $dd ) {
				$least_num = 999999;
				$cflag = 0;
				$state = 'full';
				$temp_total = 0;
				$temp_ototal = 0;
				$temp_allprice = '';
				foreach ( $dd ['date_detail'] as $ddk => $ddt ) {
					$temp_total += $ddt ['price'];
					$temp_ototal += $ddt ['oprice'];
					$temp_allprice .= ',' . $ddt ['price'];

					switch ($ddt ['num_source']) {
						case 'date' :
						case 'code' :
							if (empty ( $stock ['part'] [$d ['room_info'] ['room_id']] [$d ['state_info'] [$dk] ['price_code']] [$ddk] [$ddt ['num_source']] )) {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'];
							} else {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'] - $stock ['part'] [$d ['room_info'] ['room_id']] [$d ['state_info'] [$dk] ['price_code']] [$ddk] [$ddt ['num_source']];
							}
							break;
						case 'room' :
							if (empty ( $stock ['all'] [$d ['room_info'] ['room_id']] [$ddk] [$ddt ['num_source']] )) {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'];
							} else {
								$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = $ddt ['total_nums'] - $stock ['all'] [$d ['room_info'] ['room_id']] [$ddk] [$ddt ['num_source']];
							}
							break;
						default :
							$d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] = 0;
							break;
					}

					if ($d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] < $least_num) {
						$least_num = $d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'];
						$d ['state_info'] [$dk] ['least_num'] = $least_num;
						if ($d ['state_info'] [$dk] ['date_detail'] [$ddk] ['nums'] >= $nums) {
							$state = 'available';
						} else {
							$cflag = 1;
						}
					}
				}

				//@Editor lGh 2016-7-6 16:21:14 最大预订数量
				if (!empty($d ['state_info'] [$dk]['condition']['mxn'])&&$d ['state_info'] [$dk]['condition']['mxn']>0&&$d ['state_info'] [$dk]['condition']['mxn']<$d ['state_info'] [$dk] ['least_num']){
					$d ['state_info'] [$dk] ['least_num']=$d ['state_info'] [$dk]['condition']['mxn'];
				}

				if ($cflag == 1 && $state == 'full')
					$d ['state_info'] [$dk] ['book_status'] = 'full';
				else if ($cflag == 1 && $state == 'available')
					$d ['state_info'] [$dk] ['book_status'] = 'lack';
				else if ($cflag == 0) {
					$d ['state_info'] [$dk] ['book_status'] = 'available';
					if ($dd ['disp_type'] != 'only_show') {
						$all_full = 0;
					}
				}
				$d ['state_info'] [$dk] ['total_price'] = $temp_total * $nums;
				$d ['state_info'] [$dk] ['total'] = $temp_total;
				$d ['state_info'] [$dk] ['ototal'] = $temp_ototal;
				$d ['state_info'] [$dk] ['allprice'] = substr ( $temp_allprice, 1 );
				$d ['state_info'] [$dk] ['total_oprice'] = $temp_ototal * $nums;
				$d ['state_info'] [$dk] ['avg_oprice'] = number_format ( $temp_ototal / $countday, 2, '.', '' );
				$tmp_p = number_format ( $temp_total / $countday, 2, '.', '' );
				$d ['state_info'] [$dk] ['avg_price'] = $tmp_p;
				// if ($tmp_p < $lowest) {
				if ($dd ['disp_type'] != 'only_show') {
					$min [] = $tmp_p;
				}
				if (! empty ( $price_types [$d ['state_info'] [$dk] ['price_type']] )) {
					$top_price [$price_types [$d ['state_info'] [$dk] ['price_type']] ['sort']] = $price_types [$d ['state_info'] [$dk] ['price_type']] ['name'];
				}
				if ($dd ['disp_type'] == 'only_show' || $dd ['disp_type'] == 'buy_show') {
					$shows [$dk] = $d ['state_info'] [$dk];
					if ($dd ['disp_type'] == 'only_show')
						unset ( $d ['state_info'] [$dk] );
				}

				//2016-7-4 21:02:42 社群客2.0
				if (!empty($d ['state_info'] [$dk])&&!empty($company_prices[$d ['state_info'] [$dk]['price_code']])){
					foreach ($company_prices[$d ['state_info'] [$dk]['price_code']] as $cp_code=>$cp){
						$tmp_code=$d ['state_info'] [$dk]['price_code'].'_iwidep_clbu_'.$cp_code;
						$d ['state_info'] [$tmp_code]=$d ['state_info'] [$dk];
						$d ['state_info'] [$tmp_code]['price_code']=$tmp_code;
						empty ( $cp['name'] ) ?:$d ['state_info'] [$tmp_code]['price_name']= $cp['name'] ;
// 						$d ['state_info'] [$tmp_code]['extra_info']['protrol_code']=$cp['info'];
					}
					unset($d ['state_info'] [$dk]);
				}

			}
			$datas [$k] ['state_info'] = $d ['state_info'];
			$datas [$k] ['show_info'] = $shows;
			$datas [$k] ['lowest'] = empty($min)?'':number_format ( min ( $min ), 2, '.', '' );
			$datas [$k] ['highest'] = empty($min)?'':number_format ( max ( $min ), 2, '.', '' );
			$datas [$k] ['all_full'] = $all_full;
			$datas [$k] ['top_price'] =$top_price;
		}
		return $datas;
	}
	function cal_related_price($related_price, $cal_way, $cal_value, $return_type = 'price') {
		$des = '';
		switch ($cal_way) {
			case 'plus' :
				$related_price = $related_price + $cal_value;
				if ($cal_value > 0)
					$des = "(+$cal_value)";
				break;
			case 'reduce' :
				$related_price = $related_price - $cal_value;
				if ($cal_value > 0)
					$des = "(-$cal_value)";
				break;
			case 'multi' :
				$related_price = $related_price * $cal_value;
				if ($cal_value < 1)
					$des = '(' . ($cal_value * 10) . '折)';
				break;
			case 'divide' :
				$related_price = $cal_value == 0 ? $related_price : $related_price / $cal_value;
				if ($cal_value > 0)
					$des = $cal_value == 0 ? '' : '(' . (10 / $cal_value) . '折)';
				break;
			default :
				break;
		}
		if ($return_type == 'price')
			return $related_price;
		return $des;
	}
	function get_protrol_price_code($inter_id, $hotel_id, $protrol_code) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select ( 'i.*' );
		$db_read->from ( self::TAB_HPI . ' i' );
		$db_read->join ( self::TAB_HPS . ' s', 'i.inter_id=s.inter_id ' );
		$db_read->where ( array (
				's.hotel_id' => $hotel_id,
				'i.type' => 'protrol',
				'i.unlock_code' => $protrol_code
		) );
		$check = $db_read->get ()->row_array ();
		if ($check)
			return $check ['price_code'];
		else
			return false;
	}
	function create_order($inter_id, $orderdata, $datas, $subs, $room_nos = array()) {
		$this->load->library('MYLOG');
		$data = $orderdata ['main_order'];
		$order_addition = $orderdata ['order_additions'];
		$package_data=isset($orderdata['package_data'])?$orderdata['package_data']:array();
		$package_data and $data['is_package'] = 1;
		$data ['order_time'] = time ();
		$data ['orderid'] = str_replace ( '-', 'o', substr ( $data ['openid'], - 2, 2 ) . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT ) );
		$order_addition ['orderid'] = $data ['orderid'];
		$order_addition ['inter_id'] = $data ['inter_id'];
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $data['hotel_id'], array (
				'PMS_BANCLANCE_REDUCE_WAY',
				'PMS_POINT_REDUCE_WAY',
                'PMS_BONUS_COMSUME_WAY',
		        'TWO_ROOMS_ALLOCT'
		) );
		MYLOG::w('bonus_exchange+'.'order_model_config_data：'.json_encode($config_data),"bonus_exchange");
		$has_paid=0;
		$this->db->trans_begin ();
		if ($this->db->insert ( self::TAB_HO, $data )) {
			$oid = $this->db->insert_id ();
			$this->db->insert ( self::TAB_HOA, $order_addition );

			if (!empty($orderdata ['coupon_rel'])){
				foreach ($orderdata ['coupon_rel'] as &$cr){
					$cr['orderid']=$data ['orderid'];
				}
				$this->db->insert_batch ( 'hotel_order_coupons', $orderdata ['coupon_rel'] );
			}

			require_once APPPATH . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . "Hotel" . DIRECTORY_SEPARATOR . "Hotel_base.php";
			require_once APPPATH . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . "Hotel" . DIRECTORY_SEPARATOR . "Hotel_const.php";
			
			$link = Hotel_base::inst()->get_url('ORDERDETAIL',array('oid'=>$oid));
			if ($data ['paytype'] == 'weixin' || $data ['paytype'] == 'weifutong' || $data ['paytype'] == 'lakala'  || $data ['paytype'] == 'lakala_y' || $data ['paytype'] == 'unionpay') {
				$this->load->model ( 'pay/Pay_model' );
				$link = $this->Pay_model->get_pay_link ( $data ['paytype'] );
				$link .= '/hotel_order?id=' . $inter_id . '&orderid=' . $data ['orderid'];
			}
			$tmp = array (
					'orderid' => $data ['orderid'],
					'startdate' => $data ['startdate'],
					'enddate' => $data ['enddate'],
					'inter_id' => $inter_id
			);
			$sub_orders = array ();

			if (! empty ( $room_nos )) {
				$room_numbers = array ();
				foreach ( $room_nos as $k => $v ) {
					if (! empty ( $v )) {
						foreach ( $v as $kk => $vv ) {
							$room_numbers [$k] [] = array (
									'id' => $kk,
									'no' => $vv
							);
						}
					}
				}
			}
			$total_favour = 0;
			$total_favour += empty ( $order_addition ['point_favour'] ) ? 0 : $order_addition ['point_favour'];
			$total_favour += empty ( $order_addition ['coupon_favour'] ) ? 0 : $order_addition ['coupon_favour'];
			$total_favour += empty ( $order_addition ['wxpay_favour'] ) ? 0 : $order_addition ['wxpay_favour'];
			$avg_favour = ($data ['roomnums']==2 && isset($config_data['TWO_ROOMS_ALLOCT']) && $config_data['TWO_ROOMS_ALLOCT']==1) ?$total_favour / $data ['roomnums']:intval ( $total_favour / $data ['roomnums'] );
			$extra_favour = $total_favour - ($avg_favour * $data ['roomnums']);
			$this->load->model('hotel/Price_code_model');
			foreach ( $subs as $rid => $s ) {
				for($i = 0; $i < $datas [$rid]; $i ++) {
					$tmp ['iprice'] = $subs [$rid] ['iprice'] - $avg_favour;
					$tmp ['allprice'] = $subs [$rid] ['allprice'];
					$real_allprice=explode(',', $tmp ['allprice']);
					$tmp_favour=$avg_favour;
					foreach ($real_allprice as $k=>$a){
						if ($tmp_favour<=$a){
							$real_allprice[$k]-=$tmp_favour;
							break;
						}else{
							$real_allprice[$k]=0;
							$tmp_favour-=$a;
						}
					}
					//记录真实的每日价格
					$tmp ['real_allprice'] = implode(',', $real_allprice);

					$tmp ['roomname'] = $subs [$rid] ['roomname'];
					$tmp ['price_code_name'] = $subs [$rid] ['price_code_name'];
					//记录早餐数
					$tmp ['breakfast_nums'] = !empty($subs [$rid] ['breakfast_nums'])?$subs [$rid] ['breakfast_nums']:'';
					$tmp ['room_no'] = '';
					$tmp ['room_no_id'] = 0;
					if (! empty ( $room_numbers [$rid] )) {
						$curr_no = array_shift ( $room_numbers [$rid] );
						$tmp ['room_no'] = $curr_no ['no'];
						$tmp ['room_no_id'] = $curr_no ['id'];
					}
					$tmp ['room_id'] = $rid;

					//2016-7-5 20:00:55 社群客
					$code_check=$this->Price_code_model->check_special_code($subs [$rid] ['price_code'],$inter_id);
					if (!empty($code_check)){
						$tmp ['price_code'] = $code_check['true_code'];
						$tmp ['club_id'] = $code_check['special_code'];
					}else
						$tmp ['price_code'] = $subs [$rid] ['price_code'];
                    $tmp ['item_hotel_id'] = $data['hotel_id'];
                    
					//多个入住人
                    if(!empty($s['multi_inners'][$i])){
                        $tmp['inners']=json_encode($s['multi_inners'][$i]);
                    }else if(!empty($s['customer'][$i])){
                        $tmp['customer']=$s['customer'][$i];
                    }
					
					if(!empty($s['multi_inners'][$i])){
						$tmp['inners']=json_encode($s['multi_inners'][$i]);
					}
					
					$sub_orders [] = $tmp;
				}
			}
			if ($extra_favour>0){
				foreach ($sub_orders as $key=>$so){
					if($sub_orders [$key] ['iprice'] >= $extra_favour){
						$sub_orders [$key] ['iprice'] -= $extra_favour;
						$real_allprice=explode(',', $sub_orders [$key] ['real_allprice']);
						foreach ($real_allprice as $k=>$a){
							if ($extra_favour<=$a){
								$real_allprice[$k]-=$extra_favour;
								$real_allprice[$k]=number_format($real_allprice[$k],2,'.','');
								$real_allprice[$k]==intval($real_allprice[$k]) and $real_allprice[$k]=intval($real_allprice[$k]);
								break;
							}else{
								$real_allprice[$k]=0;
								$extra_favour-=$a;
							}
						}
						$sub_orders [$key] ['real_allprice'] = implode(',', $real_allprice);
						break;
					}else{
						$extra_favour-=$sub_orders [$key] ['iprice'];
						$sub_orders [$key] ['iprice'] = 0;
						$real_allprice=explode(',', $sub_orders [$key] ['real_allprice']);
						foreach ($real_allprice as $k=>$a){
							$real_allprice[$k]=0;
						}
						$sub_orders [$key] ['real_allprice'] = implode(',', $real_allprice);
					}
				}
			}
			$this->db->insert_batch ( self::TAB_HOI, $sub_orders );
			// if (! empty ( $order_addition ['coupon_des'] )) {
			// $this->load->model ( 'hotel/Coupon_model' );
			// $order_wxcards = $this->Coupon_model->change_order_coupon ( $data ['orderid'], $data ['inter_id'], $data ['openid'], $order_addition ['coupon_des'], 'hang_on' );
			// if (! $order_wxcards) {
			// $this->db->trans_rollback ();
			// return array (
			// 's' => 0,
			// 'errmsg' => '用券失败。'
			// );
			// } else if (! empty ( $order_wxcards ['wxcards'] )) {
			// $this->Coupon_model->wx_card_consume ( $data ['inter_id'], $order_wxcards ['wxcards'] );
			// }
			// }
			if (! empty ( $order_addition ['point_used_amount'] ) && $data ['paytype'] != 'point') {
                if(empty($config_data['PMS_BONUS_COMSUME_WAY'])||$config_data['PMS_BONUS_COMSUME_WAY']=='before'){

	                $room_codes = json_decode($order_addition ['room_codes'], true);
	                $room_codes = $room_codes[$sub_orders[0]['room_id']];

	                $extra_info = $room_codes['code']['extra_info'];
	                $bonus_extra = [];
	                $bonus_condit = [];
	                if(!empty($extra_info) && !empty($extra_info['hotel_web_id'])){
		                $bonus_extra['hotel_web_id'] = $extra_info['hotel_web_id'];
		                $bonus_extra['webser_id'] = $room_codes['room']['webser_id'];
		                $bonus_condit['extra'] = $bonus_extra;
	                }

                    $this->load->model ( 'hotel/Member_model' );
                    if (! $this->Member_model->consum_point ( $data ['inter_id'], $data ['orderid'], $data ['openid'], $order_addition ['point_used_amount'],$bonus_condit)) {
                        $tips=$this->session->userdata('text_msg');
                        $tips=empty($tips)?'':$tips;
//                         $this->db->trans_rollback ();
                        return array (
                                's' => 0,
                                'errmsg' => '扣减积分失败。'.$tips
                        );
                    }
                }
			}

			if ($this->db->trans_status () === FALSE) {
// 				$this->db->trans_rollback ();
				return array (
						's' => 0,
						'errmsg' => '下单失败'
				);
			} else {
				if ($data ['paytype'] == 'balance') {
					//@Editor lGh 2016-4-14 15:47:58 先下单再扣储值
					if(empty($config_data['PMS_BANCLANCE_REDUCE_WAY'])||$config_data['PMS_BANCLANCE_REDUCE_WAY']=='before'){
						$this->load->model ( 'hotel/Member_model' );
						if ($this->Member_model->reduce_balance ( $data ['inter_id'], $data ['openid'], $data ['price'], $data ['orderid'], '订房订单余额支付',array(),$data)) {
//                             $this->handle_order ( $data ['inter_id'], $data ['orderid'], 'ss' );
// 							$this->update_order_status ( $data ['inter_id'], $data ['orderid'], 1, $data ['openid'], true );
							$has_paid=1;
						} else {
// 							$this->db->trans_rollback ();
							return array (
									's' => 0,
									'errmsg' => '支付失败'
							);
						}
					}
				}else if ($data ['paytype'] == 'point') {
					if(empty($config_data['PMS_POINT_REDUCE_WAY'])||$config_data['PMS_POINT_REDUCE_WAY']=='before'){
						$room_codes = json_decode($order_addition ['room_codes'], true);
						$room_codes = $room_codes[$sub_orders[0]['room_id']];

						$extra_info = $room_codes['code']['extra_info'];
						$bonus_extra = [];
						$bonus_condit = [];
						if(!empty($extra_info) && !empty($extra_info['hotel_web_id'])){
							$bonus_extra['hotel_web_id'] = $extra_info['hotel_web_id'];
							$bonus_extra['webser_id'] = $room_codes['room']['webser_id'];
							$bonus_condit['extra'] = $bonus_extra;
						}

						$this->load->model ( 'hotel/Member_model' );
						if (! $this->Member_model->consum_point ( $data ['inter_id'], $data ['orderid'], $data ['openid'], $order_addition ['point_used_amount'],$bonus_condit)) {
							$tips=$this->session->userdata('text_msg');
							$tips=empty($tips)?'':$tips;
// 							$this->db->trans_rollback ();
							return array (
									's' => 0,
									'errmsg' => '积分支付失败。'.$tips
							);
						}else {
//                             $this->handle_order ( $data ['inter_id'], $data ['orderid'], 'ss' );
// 							$this->update_order_status ( $data ['inter_id'], $data ['orderid'], 1, $data ['openid'], true );
							$has_paid=1;
						}
					}
				}
				else if ($data ['paytype'] == 'bonus') {
// 					$this->update_order_status ( $data ['inter_id'], $data ['orderid'], 1, $data ['openid'], true );
					$has_paid=1;
				}
				if ($package_data){
				    $this->load->model('hotel/goods/Goods_order_model');
				    $package_order_result=$this->Goods_order_model->create_goods_order($data['inter_id'],$data ['orderid'],$data['openid'],$package_data);
				    if ($package_order_result['s']==1){
				
				    }else {
				        return array (
				                's' => 0,
				                'errmsg' => isset($package_order_result['errmsg'])?$package_order_result['errmsg']:'套餐下单失败'
				        );
				    }
				}
				if($this->db->trans_commit ()){
					return array (
							's' => 1,
							'oid' => $oid,
							'orderid' => $data ['orderid'],
							'link' => $link,
							'has_paid'=>$has_paid
					);
				}else{
					return array (
							's' => 0,
							'errmsg' => '订单下单失败。请重试'
					);
				}
			}
		}
		return array (
				's' => 0,
				'errmsg' => '下单失败，请重试'
		);
	}
	function get_order_details($inter_id, $idents = array(), $field = 'id') {
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		if (! empty ( $idents ['member_no'] ))
			$s = " ( member_no='" . $idents ['member_no'] . "' or ( openid='" . $idents ['openid'] . "' and (member_no ='' or member_no is null) )) and ";
		else if (! empty ( $idents ['openid'] ))
			$s = " openid='" . $idents ['openid'] . "' and (member_no ='' or member_no is null) and ";
		$sql = "select h.name hname,h.book_policy,h.intro_img,b.*,a.* from
		 (SELECT * FROM " . $db_read->dbprefix ( self::TAB_HO ) . " WHERE $s `$field` = '" . $idents ['id'] . "') a
			 join " . $db_read->dbprefix ( self::TAB_H ) . " h on a.inter_id=h.inter_id and a.hotel_id=h.hotel_id
		   join " . $db_read->dbprefix ( self::TAB_HOI ) . " b on a.orderid=b.orderid and a.inter_id=b.inter_id";
		return $db_read->query ( $sql )->result_array ();
	}
	function get_order_items($inter_id, $orderid, $infos = array(), $format = FALSE,$params=array()) {
	    if (! empty ( $params ['main_db'] ) || stripos ( $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], '/saveorder' ) !== FALSE) {
	        $db = $this->db;
	    } else {
	        $db = $this->load->database ( 'iwide_r1', true );
	    }
		$selects = 'i.*,i.id sub_id';
		$db->from ( self::TAB_HOI . ' i' );
		if (! empty ( $infos )) {
			foreach ( $infos as $i ) {
				switch ($i) {
					case 'r' :
						$selects .= ',r.room_img r_room_img,r.name r_name';
						$db->join ( self::TAB_HR . ' r', " r.room_id = i.room_id and r.inter_id= i.inter_id and r.inter_id= '$inter_id'", 'left' );
						break;
					case 'rno' :
						$selects .= ',rno.num_id rno_num_id,rno.room_no rno_room_no,rno.net_lock rno_net_lock,rno.lock_id rno_lock_id,rno.status rno_status,rno.des rno_des';
						$db->join ( self::TAB_HRN . ' rno', " rno.room_id = i.room_id and rno.num_id = i.room_no_id and rno.inter_id=i.inter_id and rno.inter_id= '$inter_id' ", 'left' );
						break;
					default :
						break;
				}
			}
		}
		$db->select ( $selects );
		$db->where ( 'i.inter_id', $inter_id );
		empty ( $infos ['item_id'] ) or $db->where ( 'i.id', $infos ['item_id'] );
		is_array ( $orderid ) ? $db->where_in ( 'i.orderid', $orderid ) : $db->where ( 'i.orderid', $orderid );
		$result = $db->get ()->result_array ();
		if ($format) {
			$data = array ();
			foreach ( $result as $r ) {
				$data [$r ['orderid']] [] = $r;
			}
			return $data;
		}
		return $result;
	}
	function get_order($inter_id, $idents = array()) {
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		$o = '';
		if (! empty ( $idents ['member_no'] ))
			$s = "( member_no='" . $idents ['member_no'] . "' or ( openid='" . $idents ['openid'] . "' and (member_no ='' or member_no is null) )) and";
		else if (! empty ( $idents ['openid'] ))
			$s = " openid='" . $idents ['openid'] . "' and (member_no ='' or member_no is null) and ";
		else if (! empty ( $idents ['only_openid'] ))
			$s = " openid='" . $idents ['only_openid'] . "' and ";
		if (isset ( $idents ['status'] ) && ! is_null ( $idents ['status'] )) {
			$s .= ' status in (' . $idents ['status'] . ' ) and ';
		}
		if (! empty ( $idents ['orderid'] )) {
			$o .= " orderid ='" . $idents ['orderid'] . "' and ";
		} else if (! empty ( $idents ['oid'] )) {
			$o .= " id =" . $idents ['oid'] . " and ";
		}
		$order_sql = "select oa.*,o.* from (
						select * from " . $db_read->dbprefix ( self::TAB_HO ) . " where $s $o inter_id='$inter_id' and isdel=0) o
		 				 join " . $db_read->dbprefix ( self::TAB_HOA ) . " oa on o.orderid=oa.orderid and o.inter_id = oa.inter_id";
		return $db_read->query ( $order_sql )->result_array ();
	}
	function get_main_order($inter_id, $idents = array()) {
	    $main_db=0;
		if (! empty ( $idents ['main_db'] ) || stripos ( $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], '/saveorder' ) !== FALSE) {
			$db = $this->db;
			$main_db=1;
		} else {
			$db = $this->load->database ( 'iwide_r1', true );
		}
		$s = '';
		$union_s1='';
		$union_s2='';
		$o = '';
		$a = '';
		$selects=' oa.*,o.*,h.name hname,h.intro_img himg,h.address haddress,h.longitude,h.latitude,h.tel htel ';
		if (! empty ( $idents ['member_no'] )){
			$s = "( o.member_no='" . $idents ['member_no'] . "' or ( o.openid='" . $idents ['openid'] . "' and o.member_no ='' )) and";
			$union_s1=" o.member_no='" . $idents ['member_no']. "' and ";
			$union_s2=" o.openid='" . $idents ['openid'] . "' and o.member_no ='' and ";
			$selects .=',o.id as orderbyid';
		}else if (! empty ( $idents ['openid'] ))
			$s = " o.openid='" . $idents ['openid'] . "' and o.member_no ='' and ";
		else if (! empty ( $idents ['only_openid'] ))
			$s = " o.openid='" . $idents ['only_openid'] . "' and ";
		$s_condition = '';
		if (isset ( $idents ['status'] ) && ! is_null ( $idents ['status'] )) {
		    $s_condition .= ' o.status in (' . $idents ['status'] . ' ) and ';
		}
		if (isset ( $idents ['handled'] ) && ! is_null ( $idents ['handled'] )) {
		    $s_condition .= ' o.handled =  ' . $idents ['handled'] . ' and ';
		}
		if ($s_condition){
    		$s .= $s_condition;
    		if ($union_s1){
    		    $union_s1.=$s_condition;
    		    $union_s2.=$s_condition;
    		}
		}
		if (! empty ( $idents ['orderid'] )) {
			$o .= " o.orderid ='" . $idents ['orderid'] . "' and ";
			$a .= " oa.orderid ='" . $idents ['orderid'] . "' and ";
		} else if (! empty ( $idents ['oid'] )) {
			$o .= " o.id =" . $idents ['oid'] . " and ";
		}
		$o .= " o.inter_id='$inter_id' and o.isdel ";
		$o .= empty ( $idents ['isdel'] ) ? ' = 0 and ' : ' in (0,' . $idents ['isdel'] . ') and ';
		$o .= empty ( $a ) ? '' : $a;
		$sql = "select $selects 
				 from " . $db->dbprefix ( self::TAB_HO ) . " o
		          join " . $db->dbprefix ( self::TAB_H ) . " h
		           join " . $db->dbprefix ( self::TAB_HOA ) . " oa
		           	on o.orderid=oa.orderid and o.inter_id=oa.inter_id and o.hotel_id=h.hotel_id and o.inter_id=h.inter_id
		           	 where ";
		if (!$union_s1){
		    $sql .= " $s $o h.inter_id = '$inter_id' ";
    		$sql .= empty ( $idents ['order_by'] ) ? ' order by o.id desc' : " order by " . $idents ['order_by'];
		}else{
		    $union_sql1 = $sql." $union_s1 $o h.inter_id = '$inter_id' ";
		    $union_sql2 = $sql." $union_s2 $o h.inter_id = '$inter_id' ";
		    $sql = $union_sql1.' UNION '.$union_sql2;
		    $sql .= ' order by orderbyid desc' ;
		}
		$sql .= empty ( $idents ['nums'] ) ? '' : ' limit ' . $idents ['offset'] . ',' . $idents ['nums'];
		$result = $db->query ( $sql )->result_array ();
		if (! empty ( $idents ['idetail'] )) {
			if (count ( $result ) > 1) {
			    $package_orderids=array();
			    $orderids=array();
				foreach ( $result as $k => $r ) {
    				$orderids[] = $r ['orderid'];
				    $r['is_package']==1 and $package_orderids[] = $r ['orderid'];
				}
				if ($package_orderids && empty($idents['no_goods_order'])){
				    $this->load->model('hotel/goods/Goods_order_model');
				    $package_order_condition = isset($idents['package_condition']) ? $idents['package_condition'] + array('main_db'=>$main_db) : array('main_db'=>$main_db);
				    $package_orders=$this->Goods_order_model->get_order_goods($inter_id, $package_orderids,'ORDER_ITEM', TRUE ,$package_order_condition);
				}
				$order_items = $this->get_order_items ( $inter_id, $orderids, $idents ['idetail'], TRUE ,array('main_db'=>$main_db));
				foreach ( $result as $k => $r ) {
					$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
					$result [$k] ['order_details'] = isset ( $order_items [$r ['orderid']] ) ? $order_items [$r ['orderid']] : array ();
					$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
					$result [$k] ['goods_details'] = isset ( $package_orders [$r ['orderid']] ) ? $package_orders [$r ['orderid']] : array ();
				}
			} else {
				foreach ( $result as $k => $r ) {
					$idents ['id'] = $r ['orderid'];
					$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
					$result [$k] ['order_details'] = $this->get_order_items ( $inter_id, $r ['orderid'], $idents ['idetail'], FALSE ,array('main_db'=>$main_db) );
					$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
					if ($r['is_package']==1 && empty($idents['no_goods_order'])){
					    $this->load->model('hotel/goods/Goods_order_model');
					    $package_order_condition = isset($idents['package_condition']) ? $idents['package_condition'] + array('main_db'=>$main_db) : array('main_db'=>$main_db);
					    $result [$k] ['goods_details']=$this->Goods_order_model->get_order_goods($inter_id, $r ['orderid'],'ORDER_ITEM', FALSE ,$package_order_condition);
					}
				}
			}
		}
		return $result;
	}
	function get_order_list($inter_id, $idents = array(), $just_count = false) {
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		$order_condition = " o.inter_id='$inter_id' and o.isdel= 0 and ";
		$o = '';
		$order_special_condition = '';
		$oa = '';
		$addition_condition = '';
		$c = '';
		$hotel_condition = '';
		$item_condition = '';
		$table_hotel_order = $db_read->dbprefix ( self::TAB_HO );
		$table_hotel_order_item = $db_read->dbprefix ( self::TAB_HOI );
		$table_hotel_order_addition = $db_read->dbprefix ( self::TAB_HOA );
		$table_hotels = $db_read->dbprefix ( self::TAB_H );
		if (isset ( $idents ['status'] ) && ! is_null ( $idents ['status'] )) {
			$order_condition .= ' o.status in (' . $idents ['status'] . ' ) and ';
		}
		if (isset ( $idents ['ept_status'] ) && ! is_null ( $idents ['ept_status'] )) {
			$order_condition .= ' o.status not in (' . $idents ['ept_status'] . ' ) and ';
		}
		if (! empty ( $idents ['hotel_id'] )) {
			$order_condition .= ' o.hotel_id in ( ' . $idents ['hotel_id'] . ' ) and ';
		}
		if (! empty ( $idents ['orderid'] )) {
			$order_special_condition .= " ( o.orderid ='" . $idents ['orderid'] . "' or o.tel ='" . $idents ['orderid'] . "' ) and ";
		} else if (! empty ( $idents ['oid'] )) {
			$order_special_condition .= " o.id =" . $idents ['oid'] . " and ";
		}
		if (! empty ( $idents ['td'] )) {
			$day_start = mktime ( 0, 0, 0, date ( "m" ), date ( "d" ), date ( "Y" ) );
			$day_end = mktime ( 23, 59, 59, date ( "m" ), date ( "d" ), date ( "Y" ) );
			$order_condition .= " o.order_time between $day_start and $day_end and ";
		}
		if (! empty ( $idents ['tdstart'] )) {
			$item_condition .= " i.startdate<=" . date ( 'Ymd' ) . " and i.enddate>" . date ( 'Ymd' ) . " and ";
// 			$order_condition .= " o.startdate<=" . date ( 'Ymd' ) . " and o.enddate>" . date ( 'Ymd' ) . " and ";
		}
		if (isset ( $idents ['hotel'] ) && is_numeric ( $idents ['hotel'] ) && $idents ['hotel'] >= 0) {
			$order_condition .= " o.hotel_id=" . $idents ['hotel'] . " and ";
		}
		if (! empty ( $idents ['hotel_name'] )) {
			$hotel_condition .= " h.name like '%" . $idents ['hotel_name'] . "%' and ";
		}
		if (isset ( $idents ['timetype'] ) && is_numeric ( $idents ['timetype'] ) && $idents ['timetype'] > 0) {
			switch ($idents ['timetype']) {
				// 下单时间
				case 1 :
					$timetype = "o.order_time";
					break;
					// 入住时间
				case 2 :
					$timetype = "i.startdate";
					break;
					// 离店时间
				case 3 :
					$timetype = "i.enddate";
					break;
				default :
					break;
			}
			if ($idents ['start_t']&&$start_time=strtotime($idents ['start_t'])){
				if ($idents ['timetype'] == 1) {
					$order_condition .= " $timetype>=$start_time and ";
				}else{
					$item_condition .= " $timetype>='".date('Ymd',$start_time)."' and ";
// 					$order_condition .= " $timetype>='".date('Ymd',$start_time)."' and ";
				}
			}
			if ($idents ['end_t']&&$end_time=strtotime($idents ['end_t'])){
				if ($idents ['timetype'] == 1) {
					$end_time+=86399;
					$order_condition .= " $timetype<=$end_time and ";
				}else{
					$item_condition .= " $timetype<='".date('Ymd',$end_time)."' and ";
// 					$order_condition .= " $timetype<='".date('Ymd',$end_time)."' and ";
				}
			}
		}
		if (! empty ( $idents ['number'] )) {
			$s_number = $idents ['number'];
			$order_condition .= " (o.name='$s_number' or o.tel='$s_number' or o.orderid='$s_number' or o.remark='$s_number') and ";
		}
		if (isset ( $idents ['paytype'] ) ) {
			switch ($idents ['paytype']) {
				//积分支付or积分换房
				case 'point':
					$order_condition .= " (o.paytype='point' or o.paytype='bonus') and ";
					break;
				//全部
				case -1:
					break;
				//其他
				default:
					$order_condition .= " o.paytype='$idents[paytype]' and ";
					break;
			}
		}
		if (isset ( $idents ['paystatus'] ) && is_numeric ( $idents ['paystatus'] ) && $idents ['paystatus'] >= 0) {
			$order_condition .= " o.paid= '" . $idents ['paystatus'] . "' and ";
		}
		if (isset ( $idents ['orderstatus'] ) && is_numeric ( $idents ['orderstatus'] )) {
			if ($idents ['orderstatus'] >= 0) {
				$item_condition .= " i.istatus='" . $idents ['orderstatus'] . "' and ";
			}
		}
		if (isset ( $idents ['istatus'] )) {
			$item_condition .= ' i.istatus in (' . $idents ['istatus'] . ' ) and ';
		}
		
		$on_condition = '';
		$sql = "
		FROM $table_hotel_order o ";
		if ($hotel_condition || $just_count != true) {
			$sql .= " JOIN $table_hotels h ";
			$on_condition .= ' o.hotel_id=h.hotel_id and ';
			$hotel_condition .= " h.inter_id= '$inter_id' and ";
		}
		if ($item_condition || $just_count != true) {
			$sql .= " JOIN $table_hotel_order_item i ";
			$on_condition .= ' o.orderid=i.orderid and ';
			$item_condition .= " i.inter_id='$inter_id' and ";
		}
		if ($addition_condition || $just_count != true) {
			$sql .= " LEFT JOIN $table_hotel_order_addition oa ";
			$on_condition .= ' o.orderid=oa.orderid and ';
			$addition_condition .= " oa.inter_id='$inter_id' and ";
		}
		if ($on_condition) {
			$sql .= " ON  $on_condition 1 ";
		}
		$sql .= "
		WHERE $addition_condition $order_condition $order_special_condition $hotel_condition $item_condition 1 ";
		$sql .= " group by o.orderid ";
		if ($just_count == TRUE) {
			$sql = 'select count(*) total ' . $sql;
			if ($this->input->get('bbug')){
				echo $sql.';<br />';
			}
			return $db_read->query ( $sql )->num_rows ();
		} else {
			$result=array();
			$sql .= empty ( $idents ['order_by'] ) ? ' order by o.id desc' : " order by " . $idents ['order_by'];
			$sql .= empty ( $idents ['nums'] ) ? '' : ' limit ' . $idents ['offset'] . ',' . $idents ['nums'];
			$selects = ' oa.*,o.*,h.name hname,h.intro_img himg,h.address haddress,h.longitude,h.latitude,h.tel htel ';
			$sql = 'SELECT ' . $selects . $sql;
			// 			if (!empty( $idents ['nums'] )){//分页时，用in查询子订单
			// 				$result = $db_read->query ( $sql )->result_array ();
			// 				if (!empty($result)){
			// 					$orderids=array_column($result,'orderid');
			// 					$db_read->where('inter_id',$inter_id);
			// 					$db_read->where_in('orderid',$orderids);
			// 					$order_items=$db_read->get(self::TAB_HOI)->result_array();
			// 					$items=array();
			// 					foreach ($order_items as $i){
			// 						$items[$i['orderid']][]=$i;
			// 					}
			// 					foreach ($result as $k=>$r){
			// 						$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
			// 						$result [$k] ['order_details'] =empty($items[$r['orderid']])?array():$items[$r['orderid']];
			// 						$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
			// 					}
			// 				}
			// 			}else{
		
			// 			}
			if ($this->input->get('bbug'))
				echo $sql.';<br />';
			$result = $db_read->query ( $sql )->result_array ();
			if (! empty ( $idents ['idetail'] )) {
				if (count ( $result ) > 1) {
				    $package_orderids=array();
				    $orderids=array();
				    foreach ( $result as $k => $r ) {
				        $orderids[] = $r ['orderid'];
				        $r['is_package']==1 and $package_orderids[] = $r ['orderid'];
				    }
				    if ($package_orderids){
				        $this->load->model('hotel/goods/Goods_order_model');
				        $package_order_condition = isset($idents['package_condition']) ? $idents['package_condition'] : array();
				        $package_orders=$this->Goods_order_model->get_order_goods($inter_id, $package_orderids,'ORDER_ITEM', TRUE ,$package_order_condition );
				    }
					$order_items = $this->get_order_items ( $inter_id, $orderids, $idents ['idetail'], TRUE );
					foreach ( $result as $k => $r ) {
						$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
						$result [$k] ['order_details'] = isset ( $order_items [$r ['orderid']] ) ? $order_items [$r ['orderid']] : array ();
						$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
						$result [$k] ['goods_details'] = isset ( $package_orders [$r ['orderid']] ) ? $package_orders [$r ['orderid']] : array ();
					}
				} else {
					foreach ( $result as $k => $r ) {
						$idents ['id'] = $r ['orderid'];
						$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
						$result [$k] ['order_details'] = $this->get_order_items ( $inter_id, $r ['orderid'], $idents ['idetail'] );
						$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
						if ($r['is_package']==1){
						    $this->load->model('hotel/goods/Goods_order_model');
						    $package_order_condition = isset($idents['package_condition']) ? $idents['package_condition'] : array();
						    $result [$k] ['goods_details']=$this->Goods_order_model->get_order_goods($inter_id, $r ['orderid'],'ORDER_ITEM', FALSE ,$package_order_condition );
						}
					}
				}
			}
			return $result;
		}
	}
	// 获取总订单下一步可操作状态
	function order_status($status) {
		$data = array ();
		if (is_numeric($status)&&$status>=0) {
			if($status == 1){
				$after = $this->order_status_sequence ( $status ,'adminafter');
			}else{
				$after = $this->order_status_sequence ( $status );
			}
			require_once APPPATH . DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."Hotel".DIRECTORY_SEPARATOR."Hotel_const.php";
			$status_des = Hotel_const::$order_status_oprate;
			$data ['after'] = array ();
			foreach ( $after as $a ) {
				if($a != 4){
					$data ['after'] [$a] = $status_des [$a];
				}
			}
		}
		return $data;
	}
	//获取子订单下一步可操作状态
	function item_order_status($istatus) {
		$data = array ();
		if (is_numeric($istatus)&&$istatus>=0) {
			if($istatus == 1){
				$after = $this->order_status_sequence ( $istatus ,'adminafter');
			}else{
				$after = $this->order_status_sequence ( $istatus );
			}
			require_once APPPATH . DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."Hotel".DIRECTORY_SEPARATOR."Hotel_const.php";
			$status_des = Hotel_const::$order_status_oprate;
			$data ['status'] = array ();
			foreach ( $after as $a ) {
				if ($a != 4){
					$data ['status'] [$a] = $status_des [$a];
				}
			}
		}
		return $data;
	}

	//获取当前公众号下的所有出现过的酒店信息
	function get_all_hotels($inter_id,$hotel_ids=''){
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		if (! empty ( $hotel_ids )) {
			$s .= ' AND hotel_id in ( ' . $hotel_ids . ' )';
		}
		$sql = "SELECT hotel_id FROM iwide_hotel_orders WHERE inter_id='$inter_id' $s GROUP BY hotel_id";
		$ids = $db_read->query($sql)->result_array();
		if(!empty($ids)){
			$temp = '';
			foreach ($ids as  $value) {
				$temp .= $value['hotel_id'].',';
			}
			$temp = trim($temp,',');
			if($temp){
				$sql = "SELECT hotel_id,inter_id,`name` FROM ".$db_read->dbprefix ( self::TAB_H )." WHERE hotel_id IN($temp) AND inter_id='$inter_id'";
				return $db_read->query($sql)->result_array();
			}else{
				return array();
			}
		}else{
			return array();
		}
	}

	//获取今日订单数量
	function get_today_order_num($inter_id,$entity_id=''){
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		$t_time = strtotime(date('Y-m-d'));
		if (! empty ( $entity_id )) {
			$s .= ' hotel_id in ( ' . $entity_id . ' ) and ';
		}
		$sql = "select count(*) as count from ".$db_read->dbprefix ( self::TAB_HO )." where inter_id='$inter_id' and order_time>='$t_time' and isdel=0 and $s status in(0,1,2,3,4,5,11)";
		$count = $db_read->query($sql)->result_array();
		return $count[0]['count'];
	}

	//获取今日入住数量
	function get_today_checkin_num($inter_id,$entity_id=''){
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		$t_date = date('Ymd');
		if (! empty ( $entity_id )) {
			$s .= ' hotel_id in ( ' . $entity_id . ' ) and ';
		}
		$sql = "select count(*) as count from ".$db_read->dbprefix ( self::TAB_HO )." where inter_id='$inter_id' and startdate<='$t_date' and enddate>'$t_date' and isdel=0 and $s status in(0,1,2,3,4,5,11)";
		$count = $db_read->query($sql)->result_array();
		return $count[0]['count'];
	}

	//获取最新两个待确认订单信息
	function get_order_confirm_two($inter_id,$num,$entity_id='',$order_fields=''){
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		if($num==1){
			$num = '1,1';
		}
		if (! empty ( $entity_id )) {
			$s .= ' o.hotel_id in ( ' . $entity_id . ' ) and ';
		}
		empty($order_fields) and $order_fields='o.*';
		$sql = "select $order_fields,h.name hname,i.roomname,i.price_code_name price_name from ".$this->db->dbprefix ( self::TAB_HO )." o 
		        join ".$this->db->dbprefix ( self::TAB_H )." h on o.inter_id=h.inter_id and o.hotel_id=h.hotel_id
		         join ".$this->db->dbprefix ( self::TAB_HOI )." i on o.orderid=i.orderid
		          where o.inter_id='$inter_id' and $s o.status=0 and o.isdel=0 group by o.id order by o.id desc limit $num";
		$res = $db_read->query($sql)->result_array();
		foreach ($res as $kr => $vr) {
// 			$sqlc = "select count(*) count from ".$db_read->dbprefix ( self::TAB_HOI )." where inter_id='$inter_id' and orderid='".$vr['orderid']."' ";
// 			$count = $db_read->query($sqlc)->result_array();
			$res[$kr]['count'] = $vr['roomnums'];
		}
		return $res;
	}
	
	//获取未确认订单总数
	function get_order_confirm_num($inter_id,$entity_id=''){
		$db_read = $this->load->database('iwide_r1',true);
		$s = '';
		if (! empty ( $entity_id )) {
			$s .= ' hotel_id in ( ' . $entity_id . ' ) and ';
		}
		$sql = "select count(*) count from ".$db_read->dbprefix ( self::TAB_HO )." where inter_id='$inter_id' and $s status=0 and isdel=0";
		$count = $db_read->query($sql)->result_array();
		return $count[0]['count'];
	}


	function cancel_order($inter_id, $idents) {
		$check = $this->get_main_order ( $inter_id, $idents );
		$error_msg='取消失败';
		if (! empty ( $check )) {
			$check = $check [0];
			$this->load->model ( 'hotel/Order_check_model' );
			$state = $this->Order_check_model->check_order_state ( $check );
			if ($state ['can_cancel'] == 1) {
				$status = empty ( $idents ['cancel_status'] ) ? 4 : $idents ['cancel_status'];
				$no_tmpmsg = isset ( $idents ['no_tmpmsg'] ) ? 1 : 0;
				$updata = array (
						'status' => $status
				);
				$params = array (
						'no_tmpmsg' => $no_tmpmsg
				);
				if (! empty ( $idents ['delete'] )) {
					$updata ['isdel'] = $idents ['delete'];
					$params ['isdel'] = $idents ['delete'];
				}
				$idents ['openid']=empty($idents ['openid'])?$idents ['only_openid']:$idents ['openid'];
				if (! empty ( $check ['web_orderid'] )) {
                    $this->load->model ( 'hotel/Hotel_check_model' );
                    $adapter = $this->Hotel_check_model->get_hotel_adapter ( $inter_id, $check['hotel_id'], TRUE );
					$result = $adapter->cancel_order ( $inter_id, $check );
					if ($result ['s'] == 1) {
						$this->db->where ( array (
								'orderid' => $idents ['orderid'],
								'inter_id' => $inter_id,
								'openid' => $idents ['openid']
						) );
						$this->db->update ( self::TAB_HO, $updata );
						$this->handle_order ( $inter_id, $idents ['orderid'], $status, $idents ['openid'], $params );
						return array (
								's' => 1,
								'errmsg' => '取消成功'
						);
					}
					$error_msg=empty($result['errmsg'])?$error_msg:$result['errmsg'];
				} else {
					$this->db->where ( array (
							'orderid' => $idents ['orderid'],
							'inter_id' => $inter_id,
							'openid' => $idents ['openid']
					) );
					$this->db->update ( self::TAB_HO, $updata );
					$this->handle_order ( $inter_id, $idents ['orderid'], $status, $idents ['openid'], $params );
					return array (
							's' => 1,
							'errmsg' => '取消成功'
					);
				}
			}
		}
		return array (
				's' => 0,
				'errmsg' => $error_msg
		);
	}
	function check_status($ori_status, $next_status) {
		$after = $this->order_status_sequence ( $ori_status, 'after' );
		if (in_array ( $next_status, $after )) {
			return true;
		}
		return false;
	}
	function update_order_status($inter_id, $orderid, $status, $openid = '', $paid = false,$ss=false) {
		$db_read = $this->load->database('iwide_r1',true);
		if ($ss){
			$this->handle_order ( $inter_id, $orderid, 'ss' );
		}
		$where = array (
				'inter_id' => $inter_id,
				'orderid' => $orderid
		);
		if ($openid)
			$where ['openid'] = $openid;
		$order = $db_read->get_where ( self::TAB_HO, $where )->row_array ();
		if ($this->check_status ( $order ['status'], $status)) {
			$this->db->where ( $where );
			$updata = array (
					'status' => $status
			);
			if ($paid)
				$updata ['paid'] = 1;
			$this->db->update ( self::TAB_HO, $updata );
			return $this->handle_order ( $inter_id, $orderid, $status, $openid ,array('main_db'=>1));
		}
		return false;
	}
	function handle_order($inter_id, $orderid, $status, $openid = '', $params = array()) {

      	$this->load->model('hotel/Member_model');
		$vid=$this->Member_model->get_vid($inter_id);//统一取

		$isdel = empty ( $params ['isdel'] ) ? 0 : $params ['isdel'];
		$main_db = empty ( $params ['main_db'] ) ? 0 : $params ['main_db'];
		$order = $this->get_main_order ( $inter_id, array (
				'orderid' => $orderid,
				'only_openid' => $openid,
				'isdel' => $isdel,
				'idetail' => array (
						'i'
				),
				'main_db'=>$main_db
		) );
		if ($order) {
			$order = $order [0];

            $this->load->model ( 'hotel/Hotel_config_model' );
            $config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
                'CANCEL_ORDER_BACK_BONUS',    //取消订单积分返还配置
                'PAID_ORDER_NOT_AUTO_ENSURE'
            ) );

			$days = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
			$this->load->model ( 'hotel/Member_model' );
			$this->load->model ( 'hotel/Coupon_model' );
			$same_count = 0;
			$thiscase = true; //防止4 11状态重复执行模板消息发送
			switch ($status) {
			    case '0':
			        if (!empty($config_data['PAID_ORDER_NOT_AUTO_ENSURE'])){
			            $this->set_order_wxmsg($order, 'hotel_order_notice',1);
			            $this->load->model ( 'plugins/Print_model' );
		                $this->Print_model->print_hotel_order ( $order, 'new_order' );
			        }
			        break;
				case 1 :
					{
						if (empty ( $params ['no_item'] )) {
							foreach ( $order ['order_details'] as $od ) {
								if ($od ['istatus'] == 0) {
									$this->db->where ( array (
// 											'inter_id' => $inter_id,
// 											'orderid' => $orderid,
// 											'istatus' => 0,
											'id' => $od ['id']
									) );
									$this->db->update ( self::TAB_HOI, array (
											'istatus' => 1
									) );


									$same_count ++;
									//记录订房操作日志
									$this->load->model('hotel/Hotel_log_model');
									$this->Hotel_log_model->add_admin_log('Order/items#'.$od ['id'],'save_1',array('istatus' => 1));
									//确认订单绩效状态变更 start
									$this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
						            $this->load->model('distribute/Idistribution_model');
						            $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
									if($check_new_on>0){
										$update_dist = array(
											'inter_id'=>$inter_id,
											'grade_table'=>'iwide_hotels_order',
											'grade_id'=>$od['id'],
											'order_status'=>$status,
											"status" => 4,//未核定－尚未离店
											'grade_typ'=>1//粉丝归属
										);
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
										$update_dist['grade_typ'] = 2;//社群客归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
										$update_dist['grade_typ'] = 3;//链接分销员归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									}
									//确认订单绩效状态变更 end
								}
							}
						}
						// 生成订单完成时发放的优惠券
                        if($vid!=1){
                            if ($order ['complete_reward_given'] == 0) {
                                $market_reward = $this->Coupon_model->create_market_reward ( $inter_id, $order, 'order_complete', array (
                                    'hotel' => $order ['hotel_id'],
                                    'rooms' => $order ['roomnums'],
                                    'days' => $days,
                                    'product_num' => $order ['roomnums'],
                                    'price_code' => $order ['first_detail'] ['price_code'],
                                    'category' => $order ['first_detail'] ['room_id'],
                                    'amount' => $order ['price']
                                ) );
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward ['s'] == 1) {
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => json_encode ( $market_reward ['coupons'] ),
                                        'complete_reward_given' => 2
                                    ) );
                                } else {
                                    $this->db->update ( self::TAB_HOA, array (
                                        'complete_reward_given' => 1
                                    ) );
                                }
                            }
                        }


                        if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //新版会员确认后发放优惠券
                            $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                            if (isset($give_info ['status']['ensure']) && $give_info ['status']['ensure'] == 0) {
                                $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['ensure'], 'order_complete','ensure');
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward) {
                                    $give_info ['status']['ensure']=1;  //发放成功
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                    $this->load->model ( 'plugins/Template_msg_model' );
                                    $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                                } else {
                                    $give_info ['status']['ensure']=2;  //发放失败
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                }
                            }
                        }

						// 生成订单完成时发放的积分
						if ($order ['complete_point_given'] == 0) {
							$point_reward = $this->Member_model->get_point_reward ( $inter_id, $order, 'room' );
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							if ($point_reward) {
								$this->db->update ( self::TAB_HOA, array (
										'complete_point_info' => json_encode ( $point_reward ),
										'complete_point_given' => 2
								) );
							} else {
								$this->db->update ( self::TAB_HOA, array (
										'complete_point_given' => 1
								) );
							}
						}
						// 发放模板消息
						$notice=0;
						if ($same_count == count ( $order ['order_details'] ) && (empty ( $params ['no_tmpmsg'] ))) {
							$this->load->model ( 'plugins/Template_msg_model' );
							$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_ensure' );
							$notice=1;
						}
						$this->load->model ( 'plugins/Print_model' );
						//微信支付确认才进行优惠券核销
						if ($order ['status'] != 9 && in_array($order['paytype'],$this->third_prepay_ways)) {
						    if (empty($config_data['PAID_ORDER_NOT_AUTO_ENSURE'])){
							     $this->Print_model->print_hotel_order ( $order, 'new_order' );
						    }
							if ($order['holdtime']==='18:00'){
								$holdtime = date ( 'Y-m-d 18:00', strtotime ( $order ['startdate'] ) );
								if ($order ['paid'] == 1) {
									$holdtime = date ( 'Y-m-d 12:00', strtotime ( $order ['enddate'] ) );
								}
								$this->db->where ( array (
										'orderid' => $order ['orderid'],
										'inter_id' => $inter_id
								) );
								$this->db->update ( self::TAB_HO, array (
										'holdtime' => $holdtime
								) );
							}
						}else if ($notice==1) {
							$this->Print_model->print_hotel_order ( $order, 'ensure_order' );
						}
						// 新订单模板插入队列
						$this->set_order_wxmsg($order, 'hotel_order_notice',1);
						break;
					}
				case 2 : // 入住
					{
						if (empty ( $params ['no_item'] )) {
							foreach ( $order ['order_details'] as $od ) {
								$same_count ++;
								if ($od ['istatus'] == 1) {
									$this->db->where ( array (
// 											'inter_id' => $inter_id,
// 											'orderid' => $orderid,
// 											'istatus' => 1,
											'id' => $od ['id']
									) );
									$this->db->update ( self::TAB_HOI, array (
											'istatus' => 2
									) );
									//记录订房操作日志
									$this->load->model('hotel/Hotel_log_model');
									$this->Hotel_log_model->add_admin_log('Order/items#'.$od ['id'],'save_2',array('istatus' => 2));
									//入住订单绩效状态变更 start
									$this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
						            $this->load->model('distribute/Idistribution_model');
						            $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
									if($check_new_on>0){
										$update_dist = array(
											'inter_id'=>$inter_id,
											'grade_table'=>'iwide_hotels_order',
											'grade_id'=>$od['id'],
											'order_status'=>$status,
											"status" => 4,//未核定－尚未离店
											'grade_typ'=>1//粉丝归属
										);
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
										$update_dist['grade_typ'] = 2;//社群客归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
										$update_dist['grade_typ'] = 3;//链接分销员归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									}
									//入住订单绩效状态变更 end
								}
							}
						}
						if ($same_count == count ( $order ['order_details'] )) {
							$this->load->model ( 'plugins/Template_msg_model' );
							$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_checkin' );
						}

                        if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //新版会员入住后发放优惠券
                            $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                            if (isset($give_info ['status']['in']) && $give_info ['status']['in'] == 0) {
                                $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['in'], 'order_complete','in');
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward) {
                                    $give_info ['status']['in']=1;  //发放成功
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                    $this->load->model ( 'plugins/Template_msg_model' );
                                    $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                                } else {
                                    $give_info ['status']['in']=2;  //发放失败
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                }
                            }
                        }

						break;
					}
				case 3 : // 订单为离店状态
					{
						$this->load->model ( 'hotel/Room_status_model' );
						$this->load->model ( 'plugins/Template_msg_model' );
						$this->load->model ( 'distribute/Idistribution_model' );

						if (empty ( $params ['no_item'] )) {
							$orderdays = 0;
							$real_price = 0;
							foreach ( $order ['order_details'] as $od ) {
		                    	$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
								if ($od ['istatus'] == 1 || $od ['istatus'] == 2) {
									$same_count ++;
									$this->db->where ( array (
// 											'inter_id' => $inter_id,
// 											'orderid' => $orderid,
											'id' => $od ['id']
									) );
									$this->db->update ( self::TAB_HOI, array (
											'istatus' => 3,
											'handled' => 1,
											'leavetime' => date('Y-m-d H:i:s',time())//离店时间
									) );
									//记录订房操作日志
									$this->load->model('hotel/Hotel_log_model');
									$this->Hotel_log_model->add_admin_log('Order/items#'.$od ['id'],'save_3',array('istatus' => 3));
									// 处理库存
									// add yu 2016-11-23 时租房/门票类不减库存
									if($order ['price_type']!='athour' && $order ['price_type']!='ticket'){
										$this->Room_status_model->change_hotel_temp_stock ( array (
												'inter_id' => $inter_id,
												'hotel_id' => $order ['hotel_id'],
												'room_id' => $od ['room_id'],
												'price_code' => $od ['price_code']
										), $od ['startdate'], $od ['enddate'], - 1 );
									}
				                    //离店绩效状态变更 start
			                    	$orderdays += $oddays;
			                    	$real_price =bcadd($od['iprice'],$real_price,2);
				                    $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				                    if($check_new_on>0){
				                    	$this->Idistribution_model->leave_recount($inter_id,$od['id'],$od['iprice'],$oddays,$status,$days);
				                    	$this->write_log($od,$oddays,'离店');//调试
				                    }

									//离店绩效状态变更 end
									
				                    //会员间夜升级
			                    	$this->load->model ( 'hotel/member/Level_model' ,'member_level_model');
			                    	$this->member_level_model->create_roomnight_queue($inter_id,$order,$od);
								} else if ($od ['handled'] == 1) {
									$same_count ++;
								}
								if($od ['istatus'] == 3){
									$orderdays += $oddays;
			                    	$real_price =bcadd($od['iprice'],$real_price,2);
								}
							}
						}

                        if (! empty ( $order ['coupon_des'] )&&$vid==1) {
							$this->load->model ( 'hotel/Coupon_model' );
							$order_wxcards = $this->Coupon_model->change_order_coupon ( $order ['orderid'], $order ['inter_id'], $order ['openid'], $order ['coupon_des'], 'use',$vid,$order );
						}

						// 订单完成时的优惠券发放
                        if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //新版会员离店后发放优惠券
                            $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                            if (isset($give_info ['status']['left']) && $give_info ['status']['left'] == 0) {
                                $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['left'], 'order_complete','left');
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward) {
                                    $give_info ['status']['left']=1;  //发放成功
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                    $this->load->model ( 'plugins/Template_msg_model' );
                                    $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                                } else {
                                    $give_info ['status']['left']=2;  //发放失败
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                }
                            }
                        }elseif($order ['complete_reward_given'] == 2){
                                $give_info = json_decode ( $order ['coupon_give_info'], TRUE );
                                $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['check_out'], 'order_complete' );
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward) {
                                    $this->db->update ( self::TAB_HOA, array (
                                        'complete_reward_given' => 3
                                    ) );
                                    $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                                } else {
                                    $this->db->update ( self::TAB_HOA, array (
                                        'complete_reward_given' => 4
                                    ) );
                                }
						}
						// 订单完成时发放积分
						if ($order ['complete_point_given'] == 2) {
							$point_info = json_decode ( $order ['complete_point_info'], TRUE );
							if (! empty ( $point_info ['give_amount'] )) {
								$point_reward = $this->Member_model->give_point ( $inter_id, $order ['orderid'], $order ['openid'], $point_info ['give_amount'], '订单离店，赠送积分' );
								$this->db->where ( array (
										'orderid' => $order ['orderid'],
										'inter_id' => $inter_id
								) );
								if ($point_reward) {
									$this->db->update ( self::TAB_HOA, array (
											'complete_point_given' => 3
									) );
								} else {
									$this->db->update ( self::TAB_HOA, array (
											'complete_point_given' => 4
									) );
								}
							}
						}
						// 订单完成时发放储值返现
						if ($order ['complete_balance_given'] == 2) {
							$balance_info = json_decode ( $order ['complete_balance_info'], TRUE );
							if (! empty ( $balance_info ['give_amount'] )) {
							    $this->load->model ( 'hotel/Member_new_model' );
							    $membership_number = empty( $order['member_no'] )? $order['jfk_member_no'] : $order['member_no'];
								$balance_reward = $this->Member_new_model->addBalanceByCard($inter_id,$order ['openid'],$membership_number,$order ['orderid'],$balance_info ['give_amount'],'离店送','离店返现',array('crsNo'=>$order['web_orderid']));//余额退款处理
								$this->db->where ( array (
										'orderid' => $order ['orderid'],
										'inter_id' => $inter_id
								) );
								if ($balance_reward) {
									$this->db->update ( self::TAB_HOA, array (
											'complete_balance_given' => 3
									) );
								} else {
									$this->db->update ( self::TAB_HOA, array (
											'complete_balance_given' => 4
									) );
								}
							}
						}
						if ($same_count == count ( $order ['order_details'] )) {
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							$this->db->update ( self::TAB_HO, array (
									'handled' => 1
							) );
							// 发放模板消息
							$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete' );
							//主单完结
							$this->Idistribution_model->leave_recount_by_orders($inter_id,$order['orderid'],$real_price,$orderdays,$status,$days*$order['roomnums'],array('hotel_id'=>$order['hotel_id']));
							$this->write_log($order,$orderdays,'主单完结');//调试
						}
						break;
					}

				case 4 :
                    {
                        $this->load->model ( 'hotel/Hotel_config_model' );
                    	$this->load->model('hotel/Order_check_model');
                    	//发送微信模板消息通知酒店人员 start
                		$this->set_order_wxmsg($order, 'hotel_order_cancel_notice',4);
                    	$thiscase = false;
                    	//发送微信模板消息通知酒店人员 end
                        if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //客户取消后发放优惠券
                            $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                            if (isset($give_info ['status']['custom_cancel']) && $give_info ['status']['custom_cancel'] == 0) {
                                $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['custom_cancel'], 'order_complete','custom_cancel');
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward) {
                                    $give_info ['status']['custom_cancel']=1;  //发放成功
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                    $this->load->model ( 'plugins/Template_msg_model' );
                                    $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                                } else {
                                    $give_info ['status']['custom_cancel']=2;  //发放失败
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                }
                            }
                        }

                        if($order['paid']==1 && ($order['paytype']=='weixin' || $order['paytype']=='daofu')){      //微信支付退款

                            $config_data = $this->Hotel_config_model->get_hotel_config ( $order ['inter_id'], 'HOTEL', 0, array (
                                'CHECK_WEIXIN_REFUND'
                            ) );

                            if(isset($config_data['CHECK_WEIXIN_REFUND']) && $config_data['CHECK_WEIXIN_REFUND']==1){
                                $state = $this->Order_check_model->check_order_state ( $order );
                                $params=array();
                                if (isset($state['cancel_punish_rate']))
                                    $params['punish_rate']=$state['cancel_punish_rate'];
                                $refund_result =  $this->Order_check_model->hotel_weixin_refund($order ['orderid'],$inter_id,'send',$params);

                                if(!empty($refund_result['status']) && $refund_result['status']==1){

                                    $this->update_refund_status($inter_id,$order ['orderid'],1);

                                }else{

                                    $this->update_refund_status($inter_id,$order ['orderid'],2);
$this->db->insert('weixin_text',array('content'=>'order_weixin_refund+订单号：'.$order ['orderid'].'退款失败','edit_date'=>date('Y-m-d H:i:s')));
                                }

                            }
                        }

                        if($order['paytype'] == 'unionpay' && $order['paid'] ==1 ){
                        	//银联已付款才可申请退款
                        	$config_data = $this->Hotel_config_model->get_hotel_config ( $order ['inter_id'], 'HOTEL', 0, array (
                        	    'CHECK_UNIONPAY_REFUND'
                        	) );

                        	if(isset($config_data['CHECK_UNIONPAY_REFUND']) && $config_data['CHECK_UNIONPAY_REFUND']==1){
	                        	
	                        	$refund_result = $this->Order_check_model->hotel_unionpay_refund($order['orderid'], $inter_id);
                                if(!empty($refund_result['status']) && $refund_result['status']==1){

                                    $this->update_refund_status($inter_id,$order ['orderid'],1);

                                }else{

                                    $this->update_refund_status($inter_id,$order ['orderid'],2);
                                }
	                        }
                        }

                    }
                case 11://系统取消
                		//发送微信模板消息通知酒店人员 start
                		if($thiscase){
                    		$this->set_order_wxmsg($order, 'hotel_order_cancel_notice',11);
                    	}

                    	//发送微信模板消息通知酒店人员 end
				case 5 :
					{
						$this->load->model ( 'hotel/Room_status_model' );
						$this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
			            $this->load->model('distribute/Idistribution_model');
			            $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启

						if (empty ( $params ['no_item'] )) {
							$orderdays = 0;
							$real_price = 0;
							foreach ( $order ['order_details'] as $od ) {
								$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
								if ($od ['istatus'] == 0 || $od ['istatus'] == 1) {
									$same_count ++;
									$this->db->where ( array (
											//'inter_id' => $inter_id,
											//'orderid' => $orderid,
											'id' => $od ['id']
									) );
									$this->db->update ( self::TAB_HOI, array (
											'istatus' => $status,
											'handled' => 1
									) );
									//记录订房操作日志
									$this->load->model('hotel/Hotel_log_model');
									$this->Hotel_log_model->add_admin_log('Order/items#'.$od ['id'],'save_'.$status,array('istatus' => $status));
									// 处理库存
									$this->Room_status_model->change_hotel_temp_stock ( array (
											'inter_id' => $inter_id,
											'hotel_id' => $order ['hotel_id'],
											'room_id' => $od ['room_id'],
											'price_code' => $od ['price_code']
									), $od ['startdate'], $od ['enddate'], - 1 );
			                        //取消订单绩效状态变更 start
			                        if($check_new_on>0){
			                        	$update_dist = array(
			                        		'inter_id'=>$inter_id,
			                        		'grade_table'=>'iwide_hotels_order',
			                        		'grade_id'=>$od['id'],
			                        		'order_status'=>$status,
			                        		'status'=>5,//取消
			                        		'grade_typ'=>1//粉丝归属
			                        	);
			                        	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
			                        	$update_dist['grade_typ'] = 2;//社群客归属
			                        	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
			                        	$this->write_log($od,$update_dist,'取消');//调试
			                        	$update_dist['grade_typ'] = 3;//链接分销员归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
			                        }
			                        //取消订单绩效状态变更 end
								} else if ($od ['handled'] == 1) {
									$same_count ++;
								}
								if($od ['istatus'] == 3){
									$orderdays += $oddays;
			                    	$real_price =bcadd($od['iprice'],$real_price,2);

								}
							}
						}


                    if($vid==1){
                        if(isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //酒店取消后发放优惠券
                            $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                            if (isset($give_info ['status']['hotel_cancel']) && $give_info ['status']['hotel_cancel'] == 0) {
                                $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['hotel_cancel'], 'order_complete','hotel_cancel');
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($market_reward) {
                                    $give_info ['status']['hotel_cancel']=1;  //发放成功
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                    $this->load->model ( 'plugins/Template_msg_model' );
                                    $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                                } else {
                                    $give_info ['status']['hotel_cancel']=2;  //发放失败
                                    $update_coupon_info=json_encode($give_info);
                                    $this->db->update ( self::TAB_HOA, array (
                                        'coupon_give_info' => $update_coupon_info
                                    ) );
                                }
                            }
                        }
                        if (! empty ( $order ['coupon_des'] )) {//优惠券退回
                            $this->load->model ( 'hotel/Coupon_model' );
                            $order_wxcards = $this->Coupon_model->change_order_coupon ( $order ['orderid'], $order ['inter_id'], $order ['openid'], $order ['coupon_des'], 'back' ,$vid,$order);
                        }
                    }
						// 积分返还
						$this->load->model ( 'hotel/Hotel_config_model' );
						$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order ['hotel_id'], array (
						    'CANCEL_ORDER_BACK_BONUS'    //取消订单积分返还配置
						) );
                        if(isset($config_data['CANCEL_ORDER_BACK_BONUS']) && $config_data['CANCEL_ORDER_BACK_BONUS']==1){
                            if ($order ['point_used'] == 1) {
                                $point_back = $this->Member_model->point_back ( $inter_id, $order ['openid'], $order ['orderid'], $order );
                                $this->db->where ( array (
                                    'orderid' => $order ['orderid'],
                                    'inter_id' => $inter_id
                                ) );
                                if ($point_back) {
                                    $this->db->update ( self::TAB_HOA, array (
                                        'point_used' => 2
                                    ) );
                                } else {
                                    $this->db->update ( self::TAB_HOA, array (
                                        'point_used' => 4
                                    ) );
                                }
                            }
                        }
                        //积分返还结束
                        
						if ($same_count == count ( $order ['order_details'] )) {
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							$this->db->update ( self::TAB_HO, array (
									'handled' => 1
							) );
							// 发放模板消息
							if (empty ( $params ['no_tmpmsg'] )) {
								$this->load->model ( 'plugins/Template_msg_model' );
								$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_cancel' );
								$this->load->model ( 'plugins/Print_model' );
								$this->Print_model->print_hotel_order ( $order, 'cancel_order_'.$status );
							}
							//主单完结
							$this->Idistribution_model->leave_recount_by_orders($inter_id,$order['orderid'],$real_price,$orderdays,$status,$days*$order['roomnums'],array('hotel_id'=>$order['hotel_id']));
							$this->write_log($order,$orderdays,'主单完结');//调试
						}

						break;
					}
				case 'paid' :
					{

						break;
					}
				case 'repay' :
					{

						break;
					}
				case 8 :
					{
						$this->load->model ( 'hotel/Room_status_model' );
						$this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
						$this->load->model('distribute/Idistribution_model');
						$check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
						if (empty ( $params ['no_item'] )) {
							$orderdays = 0;
							$real_price = 0;
							foreach ( $order ['order_details'] as $od ) {
								$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
								if ($od ['istatus'] == 0 || $od ['istatus'] == 1) {
									$same_count ++;
									$this->db->where ( array (
// 											'inter_id' => $inter_id,
// 											'orderid' => $orderid,
											'id' => $od ['id']
									) );
									$this->db->update ( self::TAB_HOI, array (
											'istatus' => $status,
											'handled' => 1
									) );
									//记录订房操作日志
									$this->load->model('hotel/Hotel_log_model');
									$this->Hotel_log_model->add_admin_log('Order/items#'.$od ['id'],'save_'.$status,array('istatus' => $status));
									// 处理库存
									$this->Room_status_model->change_hotel_temp_stock ( array (
											'inter_id' => $inter_id,
											'hotel_id' => $order ['hotel_id'],
											'room_id' => $od ['room_id'],
											'price_code' => $od ['price_code']
									), $od ['startdate'], $od ['enddate'], - 1 );
									//取消订单绩效状态变更 start
									if($check_new_on>0){
										$update_dist = array(
											'inter_id'=>$inter_id,
											'grade_table'=>'iwide_hotels_order',
											'grade_id'=>$od['id'],
											'order_status'=>$status,
											'status'=>5,//取消
											'grade_typ'=>1//粉丝归属
										);
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
										$update_dist['grade_typ'] = 2;//社群客归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
										$this->write_log($od,$update_dist,'取消');//调试
										$update_dist['grade_typ'] = 3;//链接分销员归属
										$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									}
			                        //取消订单绩效状态变更 end
								} else if ($od ['handled'] == 1) {
									$same_count ++;
								}
								if($od ['istatus'] == 3){
									$orderdays += $oddays;
									$real_price =bcadd($od['iprice'],$real_price,2);
								}
							}
						}
						if ($same_count == count ( $order ['order_details'] )) {
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							$this->db->update ( self::TAB_HO, array (
									'handled' => 1
							) );
							//主单完结
							$this->Idistribution_model->leave_recount_by_orders($inter_id,$order['orderid'],$real_price,$orderdays,$status,$days*$order['roomnums'],array('hotel_id'=>$order['hotel_id']));
							$this->write_log($order,$orderdays,'主单完结');//调试
						}

						break;
					}
				case 9 :
					{
						break;
					}
				case 10 :
					{
						$this->load->model ( 'hotel/Room_status_model' );
						if (empty ( $params ['no_item'] )) {
							$orderdays = 0;
							$real_price = 0;
							foreach ( $order ['order_details'] as $od ) {
								$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
								$same_count ++;
								$this->db->where ( array (
// 										'inter_id' => $inter_id,
// 										'orderid' => $orderid,
										'id' => $od ['id']
								) );
								$this->db->update ( self::TAB_HOI, array (
										'istatus' => $status,
										'handled' => 1
								) );
								if($od ['istatus'] == 3){
									$orderdays += $oddays;
									$real_price =bcadd($od['iprice'],$real_price,2);
								}
							}
						}
						// 积分返还
						if ($order ['point_used'] == 1) {
							$point_back = $this->Member_model->point_back ( $inter_id, $order ['openid'], $order ['orderid'], $order );
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							if ($point_back) {
								$this->db->update ( self::TAB_HOA, array (
										'point_used' => 2
								) );
							} else {
								$this->db->update ( self::TAB_HOA, array (
										'point_used' => 4
								) );
							}
						}
						if ($same_count == count ( $order ['order_details'] )) {
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							$this->db->update ( self::TAB_HO, array (
									'handled' => 1
							) );
							//主单完结
							$this->load->model ( 'distribute/Idistribution_model' );
							$this->Idistribution_model->leave_recount_by_orders($inter_id,$order['orderid'],$real_price,$orderdays,$status,$days*$order['roomnums'],array('hotel_id'=>$order['hotel_id']));
							$this->write_log($order,$orderdays,'主单完结');//调试
						}
						break;
					}
				case 'ss' : // 下单成功
					$this->load->model ( 'hotel/temp_msg_auth_model' );
					$this->load->model ( 'plugins/Template_msg_model' );
					$this->load->model ( 'hotel/Room_status_model' );
					//发送微信模板消息通知酒店人员 start
					// $auths = $this->temp_msg_auth_model->get_openids ( $inter_id );
					$ori_openid=$order ['openid'];
					// if (! empty ( $auths )) {
					// 	foreach ( $auths as $ah ) {
					// 		$order ['openid'] = $ah->openid;
					// 		$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_notice' );
					// 	}
					// }
					if($order['paytype']=='daofu'){
						$this->set_order_wxmsg($order, 'hotel_order_notice',1);
					}
					//发送微信模板消息通知酒店人员 end
					$order ['openid']=$ori_openid;
					if (! empty ( $order ['coupon_des'] )) {
						$this->load->model ( 'hotel/Coupon_model' );
						$order_wxcards = $this->Coupon_model->change_order_coupon ( $order ['orderid'], $order ['inter_id'], $order ['openid'], $order ['coupon_des'], 'hang_on',NULL,$order );
						if (! empty ( $order_wxcards ['wxcards'] )) {
							$this->Coupon_model->wx_card_consume ( $order ['inter_id'], $order_wxcards ['wxcards'] );
						}
					}
					if ($order ['status'] != 9 && $order['paytype'] !='weixin' && $order['paytype'] !='weifutong' && $order['paytype'] !='lakala' && $order['paytype'] !='lakala_y' && $order['paytype'] !='unionpay') {
						
						if ($order['holdtime']==='18:00'){
							$holdtime = date ( 'Y-m-d 18:00', strtotime ( $order ['startdate'] ) );
							if ($order ['paid'] == 1) {
								$holdtime = date ( 'Y-m-d 12:00', strtotime ( $order ['enddate'] ) );
							}
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id
							) );
							$this->db->update ( self::TAB_HO, array (
									'holdtime' => $holdtime
							) );
						}
						$this->load->model ( 'plugins/Print_model' );
						$this->Print_model->print_hotel_order ( $order, 'new_order' );
					}

                    //新版会员在订单生成就生成优惠券信息
                    if($vid==1){
                        if ($order ['complete_reward_given'] == 0) {
                            $market_reward = $this->Coupon_model->create_market_reward ( $inter_id, $order, 'order_complete', array (
                                'hotel' => $order ['hotel_id'],
                                'rooms' => $order ['roomnums'],
                                'days' => $days,
                                'product_num' => $order ['roomnums'],
                                'price_code' => $order ['first_detail'] ['price_code'],
                                'category' => $order ['first_detail'] ['room_id'],
                                'amount' => $order ['price']
                            ) );
                            $this->db->where ( array (
                                'orderid' => $order ['orderid'],
                                'inter_id' => $inter_id
                            ) );
                            if ($market_reward ['s'] == 1) {
                                $this->db->update ( self::TAB_HOA, array (
                                    'coupon_give_info' => json_encode ( $market_reward ['coupons'] ),
                                    'complete_reward_given' => 2
                                ) );
                            } else {
                                $this->db->update ( self::TAB_HOA, array (
                                    'complete_reward_given' => 1
                                ) );
                            }
                        }


                        if ($order ['complete_point_given'] == 0) {       //新版会员在订单生成就生成积分赠送信息
                            $point_given=$this->Member_model->check_point_giverules( $inter_id, $order, 'create_order', array (
                                'hotel' => $order ['hotel_id'],
                                'rooms' => $order ['roomnums'],
                                'product_num' => $order ['roomnums'],
                                'price_code' => $order ['first_detail'] ['price_code'],
                            ) );

                            $this->db->where ( array (
                                'orderid' => $order ['orderid'],
                                'inter_id' => $inter_id
                            ) );
                            if ($point_given ['code'] == 1) {
                                $this->db->update ( self::TAB_HOA, array (
                                    'complete_point_info' => json_encode ( $point_given ['result'] ),
                                    'complete_point_given' => 2
                                ) );
                            } else {
                                $this->db->update ( self::TAB_HOA, array (
                                    'complete_point_given' => 1
                                ) );
                            }
                        }

                    }
                    //储值返现
                    if ($order ['complete_balance_given'] == 0) {
                    	$this->load->model ( 'hotel/Hotel_config_model' );
                    	$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $order['hotel_id'], array (
                    			'BALANCE_BACK_RATE'
                    	) );
                        $balance_given=$this->Member_model->check_balance_giverules( $inter_id, $order, $config_data);

                        $this->db->where ( array (
                            'orderid' => $order ['orderid'],
                            'inter_id' => $inter_id
                        ) );
                        if ($balance_given ['code'] == 1) {
                            $this->db->update ( self::TAB_HOA, array (
                                'complete_balance_info' => json_encode ( $balance_given ['result'] ),
                                'complete_balance_given' => 2
                            ) );
                        } else {
                            $this->db->update ( self::TAB_HOA, array (
                                'complete_balance_given' => 1
                            ) );
                        }
                    }

                    //生成分销信息 start
					$this->load->model ( 'distribute/Idistribution_model' );
					$this->load->model ( 'club/Club_list_model' );
					if($order['first_detail']['club_id']){
						$saler = $this->Club_list_model->get_club_by_id($inter_id,$order['first_detail']['club_id']);//获取分销id
					}
					$this->load->model ( 'distribute/Fans_model' );
					$fans = $this->Fans_model->get_fans_beloning($inter_id,$order ['openid']);

					$orderdays = 0;
					foreach ( $order ['order_details'] as $od ) {
						// 获得相差天数
						$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
						$orderdays += $oddays;
						$I_params = array(
							'inter_id' => $inter_id, //公众号id
							'hotel_id' => $order ['hotel_id'], //酒店id
							'grade_openid' => $order ['openid'], //用户openid
							'now_time' => date('Y-m-d',$order ['order_time']), //下单时间
							'price_typeid' => $od['price_code'], //价格代码
							'pay_wayid' => $order['paytype'],//支付方式
							'order_amount' => $od['iprice'],//订单总金额
							'grade_amount' => $od['iprice'],//订单计算绩效部分的金额
							'days' => $oddays,//天数
							'order_id' => $order['web_orderid']? $order['web_orderid']:$od['orderid'],//订单号(如果有PMS订单号传PMS订单号)
							'grade_id' => $od['id'],//记录产生绩效的表的主键值
							'product' => $od['roomname'],//产品名称
							'istatus' => $od['istatus'],//订单状态
							'fans_hotel' => isset($fans->hotel_id)?$fans->hotel_id:$order ['hotel_id'],//粉丝所属酒店
							'fans_source' => isset($fans->source)?$fans->source:0,//粉丝归属
							'saler' => isset($saler['id'])? $saler['id']:0,//分销员id
							'link_saler' => $order['link_saler'],//链接分销员id
							'own_saler' => $order['own_saler']//下单分销员id
						);
						$this->Idistribution_model->get_best($I_params);
						$this->write_log($od,$I_params,'下单');//调试


						// 处理库存
						$this->Room_status_model->change_hotel_temp_stock ( array (
								'inter_id' => $inter_id,
								'hotel_id' => $order ['hotel_id'],
								'room_id' => $od ['room_id'],
								'price_code' => $od ['price_code']
						), $od ['startdate'], $od ['enddate'], 1 );
					}

					$O_params = array(
						'inter_id' => $inter_id, //公众号id
						'hotel_id' => $order ['hotel_id'], //酒店id
						'grade_openid' => $order ['openid'], //用户openid
						'now_time' => date('Y-m-d',$order ['order_time']), //下单日期
						'price_typeid' => $od['price_code'], //价格代码
						'pay_wayid' => $order['paytype'],//支付方式
						'order_amount' => $order['price'],//订单总金额
						'grade_amount' => $order['price'],//订单计算绩效部分的金额
						'days' => $orderdays,//天数
						'order_id' => $order['web_orderid']? $order['web_orderid']:$od['orderid'],//订单号(如果有PMS订单号传PMS订单号)
						'grade_id' => $order['orderid'],//记录产生绩效的表的orderid
						'product' => $od['roomname'],//产品名称
						'status' => $order['status'],//订单状态
						'fans_hotel' => isset($fans->hotel_id)?$fans->hotel_id:$order ['hotel_id'],//粉丝所属酒店
						'fans_source' => isset($fans->source)?$fans->source:0,//粉丝归属
						'saler' => isset($saler['id'])? $saler['id']:0,//分销员id
						'link_saler' => $order['link_saler'],//链接分销员id
						'own_saler' => $order['own_saler'],//下单分销员id
						'link_f_saler' => $order['link_f_saler'],//链接泛分销员id
						'own_f_saler' => $order['own_f_saler'],//下单泛分销员id
					);
					$this->Idistribution_model->get_best_by_order($O_params);
					$this->write_log($order,$O_params,'主单下单');//调试

					//生成分销信息 end
					//插入订单数据到数据库队列，用于系统取消
					if($order['paytype'] == 'weixin' || $order['paytype'] == 'weifutong' || $order['paytype'] =='lakala' || $order['paytype'] =='lakala_y' || $order['paytype'] =='unionpay'){
						$this->load->model ( 'hotel/Order_queues_model' );
						$this->load->model ( 'pay/Pay_model' );
						$pay_paras = $this->Pay_model->get_pay_paras ( $inter_id, 'weixin' );
						$this->Order_queues_model->create_queue($inter_id,$order ['hotel_id'],$order ['orderid'],$pay_paras);
					}
					break;
				default :
					break;
			}
			return true;
		}
		return false;
	}
	public function update_order_item($inter_id, $orderid, $id, $data) {
		$item = $this->get_order_item ( $inter_id, $orderid, $id );
       	$this->load->model('hotel/Member_model');
		$vid=$this->Member_model->get_vid($inter_id);//统一取
		if (! empty ( $item )) {
			$this->db->trans_begin ();
			if ($item ['istatus'] == 2 || $item ['istatus'] == 1) {
				$this->load->helper ( 'date' );
				$updata = array ();
				$order_update = array ();
				$main_order=$this->get_order($inter_id,array('orderid'=>$orderid));
				$main_order=$main_order[0];
				if(isset($data['new_price'])&&$data['new_price']>=0&&$data['new_price']!=$item['iprice']){
				    $updata ['iprice'] = $data ['new_price'];
				
				    if ($main_order ['complete_point_given'] == 2) {
				        $point_info = json_decode ( $main_order ['complete_point_info'], TRUE );
				        if (! empty ( $point_info ['give_amount'] )) {
				            $price_diff=$item['iprice']-$updata ['iprice'];
				            if ($point_info ['type'] == 'BALANCE') {
				                $point_info ['give_amount'] -= $price_diff * $point_info ['give_rate'];
				            } else if ($point_info ['type'] == 'ORDER') {
				                // 								if ($is_last == 1) {
				                // 									$point_info ['give_amount'] = 0;
				                // 								}
				            }
				        }
				        $order_update ['complete_point_info'] = empty($point_info) ? '' : json_encode ( $point_info );
				    }
				    //更新离店储值返现
				    if ($main_order ['complete_balance_given'] == 2) {
				        $balance_info = json_decode ( $main_order ['complete_balance_info'], TRUE );
				        if (! empty ( $balance_info ['give_amount'] )) {
				            $price_diff=$item['iprice']-$updata ['iprice'];
				            $balance_info ['give_amount'] -= $price_diff * $balance_info ['give_rate'];
				        }
				        $order_update ['complete_balance_info'] = json_encode ( $balance_info );
				    }
				}
				if (! empty ( $data ['startdate'] ) || ! empty ( $data ['enddate'] ) || isset($updata ['iprice'])) {
    				$min_begin_date =  date ( 'Ymd', strtotime ( '- 30 day', strtotime ( $item ['startdate'] ) )) ;
    				$max_begin_date =  date ( 'Ymd', strtotime ( '+ 90 day', strtotime ( $item ['startdate'] ) )) ;
    				$min_end_date =  date ( 'Ymd', strtotime ( '- 30 day', strtotime ( $item ['enddate'] ) )) ;
    				$max_end_date =  date ( 'Ymd', strtotime ( '+ 90 day', strtotime ( $item ['enddate'] ) )) ;
				    $data ['startdate'] = empty ( $data ['startdate'] ) ? $item ['startdate'] : date ( 'Ymd', strtotime ( $data ['startdate'] ) );
				    $data ['enddate'] = empty ( $data ['enddate'] ) ? $item ['enddate'] : date ( 'Ymd', strtotime ( $data ['enddate'] ) );
				    if ($data ['startdate'] < $min_begin_date || $data ['startdate'] > $max_begin_date){
				        unset($data ['startdate']);
				    }
				    if ($data ['enddate'] < $min_end_date || $data ['enddate'] > $max_end_date){
				        unset($data ['enddate']);
				    }
				    if ((!empty($data ['startdate']) && !empty($data ['enddate']) && $data ['enddate'] >= $data ['startdate']) && ($data ['startdate']!=$item['startdate'] || $data ['enddate']!=$item['enddate']  || (isset($updata ['iprice']) && $updata ['iprice']!=$item['iprice']))) {
                        $updata ['startdate'] = $data ['startdate'];
                        $updata ['enddate'] = $data ['enddate'];
    
                        $room_night = get_room_night($updata ['startdate'],$updata ['enddate'],'ceil',$updata);//至少有一个间夜
                        $ori_room_night = get_room_night($item ['startdate'],$item ['enddate'],'ceil',$item);//至少有一个间夜
                        $room_night_diff=$ori_room_night-$room_night;
    
                        if($vid==1){
                            $order = $this->get_main_order ( $inter_id, array (
                                'orderid' => $main_order['orderid'],
                                'only_openid' => $main_order['openid'],
                                'isdel' => $main_order['isdel'],
                                'idetail' => array (
                                    'i'
                                )
                            ) );
                            $order=$order[0];
                            $total_price=0;
                            $total_night=0;
                            if(!empty($order['order_details'])){
                                foreach($order['order_details'] as $arr){
                                    $total_price=$total_price+$arr['iprice'];
                                    $update_date = get_room_night($arr ['startdate'],$arr ['enddate'],'ceil',$arr);//至少有1个间夜
                                    $total_night=$total_night+$update_date;
                                }
                            }
    						$days = get_room_night($order ['startdate'],$order ['enddate'],'ceil',$order);//至少有1个间夜
    	                    $new_night=$total_night - $ori_room_night + $room_night;
    //	                    if(!empty($data['new_price']) && $data['new_price'] > 0){
    	                    if(isset($data['new_price'])&&$data['new_price']>=0){
    		                    $new_price = $total_price - $item['iprice'] + $data['new_price'];
    	                    } else{
    		                    $new_price = $total_price;
    	                    }
    
    
                            $this->load->model ( 'hotel/Coupon_model' );
                            $new_market_reward = $this->Coupon_model->create_market_reward ( $inter_id, $order, 'order_complete', array (
                                'hotel' => $order ['hotel_id'],
                                'rooms' => $order ['roomnums'],
                                'days' => $days,
                                'product_num' => $order ['roomnums'],
                                'price_code' => $order ['first_detail'] ['price_code'],
                                'category' => $order ['first_detail'] ['room_id'],
                                'amount' => $new_price,
                                'extra_nums'=>$new_night
                            ) );
                            if(!empty($new_market_reward)){
                                $new_market_reward=$new_market_reward['coupons'];
                            }
                            $coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );
    
                            if(empty($coupon_info)&&!empty($new_market_reward)){
                                $coupon_info=$new_market_reward;
                            }elseif(!empty($new_market_reward)){
                                foreach($new_market_reward['status'] as $key=>$arr){
                                    if(empty($coupon_info['status'][$key]) || $coupon_info['status'][$key]==0){
                                        $coupon_info[$key]=$new_market_reward[$key];
                                        $coupon_info['status'][$key]=0;
                                    }
                                }
                            }
    
                            $order_update ['coupon_give_info'] = empty($coupon_info) ? '' : json_encode ( $coupon_info );
    
                        }elseif ($vid!=1 && $main_order ['complete_reward_given'] == 2) {
                            $coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );
                            foreach ( $coupon_info ['check_out'] as $out_k => $out ) {
                                if (! empty ( $out ['cards'] )) {
                                    foreach ( $out ['cards'] as $ck => $oc ) {
                                        if ($oc ['give_rule'] == 'room_nights') {
                                            $coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] -= $room_night_diff * $oc ['give_num'];
                                        } else if ($oc ['give_rule'] == 'order') {
    // 										if ($is_last == 1) {
    // 											$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] = 0;
    // 										}
                                        }
                                    }
                                }
                            }
                            $order_update ['coupon_give_info'] = empty($coupon_info) ? '' : json_encode ( $coupon_info );
                        }
				    }
                }
                if (! empty ( $data ['mt_room_id'] ) && $data ['mt_room_id'] !=$item ['mt_room_id']) {
                    $updata ['mt_room_id'] = $data ['mt_room_id'];
                }
                
                empty($data['webs_orderid']) or	$updata['webs_orderid']=$data['webs_orderid'];
                
				// 更新优惠信息
				if(!empty($order_update)){
					$this->db->where ( array (
							'inter_id' => $inter_id,
							'orderid' => $orderid
					) );
					$this->db->update ( self::TAB_HOA, $order_update );
				}

				if (! empty ( $updata )) {
					$this->db->where ( array (
// 							'orderid' => $orderid,
							'id' => $id,
// 							'inter_id' => $inter_id
					) );
					$this->db->update ( self::TAB_HOI, $updata );
				}
				if ($this->db->trans_status () === FALSE) {
					$this->db->trans_rollback ();
					return false;
				}
				$this->db->trans_commit ();

				//记录订房操作日志
				if(!empty($updata)){
					$this->load->model('hotel/Hotel_log_model');
					$this->Hotel_log_model->add_admin_log('Order/items#'.$id,'save',$updata);
				}

			}
			if (! empty ( $data ['istatus'] ) && $data ['istatus'] != $item ['istatus']) {
				$this->db->trans_begin ();
				if ($this->check_status ( $item ['istatus'], $data ['istatus'] )) {
					// if ($this->check_status ( $item ['istatus'], $data ['istatus'] ) && $item ['handled'] == 0) {
					if (! $this->update_order_item_status ( $inter_id, $orderid, $id, $data ['istatus'] )) {
						$this->db->trans_rollback ();
						return false;
					}
				} else {
					$this->db->trans_rollback ();
					return false;
				}
				if ($this->db->trans_status () === FALSE) {
					$this->db->trans_rollback ();
					return false;
				}
				$this->db->trans_commit ();
			}
			return true;
		}
		return false;
	}
	public function update_order_item_status($inter_id, $orderid, $item_id, $status) {
		$this->load->model ( 'hotel/Member_model' );
		$this->load->model ( 'hotel/Coupon_model' );
		$main_order = $this->get_order_list ( $inter_id, array (
				'orderid' => $orderid,
				'idetail' => array (
						'i'
				)
		) );
		if (! empty ( $main_order )) {
			$main_order = $main_order [0];
			if (count ( $main_order ['order_details'] ) == 1) {
				return $this->update_order_status ( $inter_id, $orderid, $status );
			}
			$items = array ();
			$cancle_count = 0;
			$end_count = 0;
			$is_last = 0;
			$cancle_status = array (
					4,
					5,
					8,
					11
			);
			$end_status = array (
					3,
					4,
					5,
					8,
					11
			);
			$haven_in = 0;
			$orderdays = 0;
			$real_price = 0;
			foreach ( $main_order ['order_details'] as $od ) {
				$items [$od ['sub_id']] = $od;
				if (in_array ( $od ['istatus'], $cancle_status )) {
					$cancle_count ++;
				}
				if (in_array ( $od ['istatus'], $end_status )) {
					$end_count ++;
				}
				if ($od ['istatus'] == 3) {
					$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
					$orderdays += $oddays;
					$real_price =bcadd($od['iprice'],$real_price,2);
					$haven_in ++;
				}
			}
			$this_item = $items [$item_id];
			if (count ( $main_order ['order_details'] ) == ($cancle_count + 1)) {
				$is_last = 1;
			}
			$is_end = 0;
			if (count ( $main_order ['order_details'] ) == ($end_count + 1)) {
				$is_end = 1;
			}
			$updata = array (
					'istatus' => $status
			);
			$room_night = get_room_night($this_item ['startdate'],$this_item ['enddate'],'ceil',$this_item);//至少有一个间夜
			$ori_room_night = get_room_night($main_order ['startdate'],$main_order ['enddate'],'ceil',$main_order);//至少有一个间夜
			$room_night_diff=$ori_room_night-$room_night;
			$this->db->trans_begin ();
			switch ($status) {
				case 1 :
					$this->handle_order ( $inter_id, $orderid, 1 ,'',array('main_db'=>1));
					break;
				case 2 :
					$this->load->model ( 'plugins/Template_msg_model' );
					$this->Template_msg_model->send_hotel_order_msg ( $main_order, 'hotel_order_checkin' );
					break;
				case 3 :

// 					$order_update = array ();
// 					if ($main_order ['complete_reward_given'] == 2) {
// 						$coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );
// 						foreach ( $coupon_info ['check_out'] as $out_k => $out ) {
// 							if (! empty ( $out ['cards'] )) {
// 								foreach ( $out ['cards'] as $ck => $oc ) {
// 									if ($oc ['give_rule'] == 'room_nights') {
// 										$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] -= $room_night_diff * $oc ['give_num'];
// 									} else if ($oc ['give_rule'] == 'order') {
// 										if ($is_last == 1) {
// 											$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] = 0;
// 										}
// 									}
// 								}
// 							}
// 						}
// 						$order_update ['coupon_give_info'] = json_encode ( $coupon_info );
// 					}

// 					$updata ['handled'] = 1;

					// 更新优惠信息
// 					if(!empty($order_update)){
// 						$this->db->where ( array (
// 								'inter_id' => $inter_id,
// 								'orderid' => $orderid
// 						) );
// 						$this->db->update ( self::TAB_HOA, $order_update );
// 					}

					if ($is_end == 1) {
						$this->db->where ( array (
								'orderid' => $orderid,
								'inter_id' => $inter_id
						) );
						$this->db->update ( self::TAB_HO, array (
								'handled' => 1
						) );
						$this->handle_order ( $inter_id, $orderid, $status, '', array (
								'no_item' => true,
                                'main_db' => 1
						) );
					}
					// 处理库存
					$this->load->model ( 'hotel/Room_status_model' );
					$this->Room_status_model->change_hotel_temp_stock ( array (
							'inter_id' => $inter_id,
							'hotel_id' => $main_order ['hotel_id'],
							'room_id' => $this_item ['room_id'],
							'price_code' => $this_item ['price_code']
					), $this_item ['startdate'], $this_item ['enddate'], - 1 );
					$this->load->model ( 'plugins/Template_msg_model' );
					$this->Template_msg_model->send_hotel_order_msg ( $main_order, 'hotel_order_complete' );
					$updata ['handled'] = 1;
					$updata ['leavetime'] = date('Y-m-d H:i:s',time());//离店时间
					$oddays = get_room_night($this_item ['startdate'],$this_item ['enddate'],'ceil',$this_item);//至少有一个间夜
					$orderdays += $oddays;
					$real_price =bcadd($this_item['iprice'],$real_price,2);

					break;
				case 4 :
				case 5 :
				case 8 :
				case 11:
					$order_update = array ();
					if ($main_order ['complete_reward_given'] == 2) {
						$coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );
						if (!empty($coupon_info)){
							foreach ( $coupon_info ['check_out'] as $out_k => $out ) {
								if (! empty ( $out ['cards'] )) {
									foreach ( $out ['cards'] as $ck => $oc ) {
										if ($oc ['give_rule'] == 'room_nights') {
											$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] -= $room_night * $oc ['give_num'];
										} else if ($oc ['give_rule'] == 'order') {
											if ($is_last == 1) {
												$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] = 0;
											}
										}
									}
								}
							}
							$order_update ['coupon_give_info'] = json_encode ( $coupon_info );
						}
					}
					if ($main_order ['complete_point_given'] == 2) {
						$point_info = json_decode ( $main_order ['complete_point_info'], TRUE );
						if (! empty ( $point_info ['give_amount'] )) {
							if ($point_info ['type'] == 'BALANCE') {
								$point_info ['give_amount'] -= $this_item ['iprice'] * $point_info ['give_rate'];
							} else if ($point_info ['type'] == 'ORDER') {
								if ($is_last == 1) {
									$point_info ['give_amount'] = 0;
								}
							}
							$order_update ['complete_point_info'] = json_encode ( $point_info );
						}
					}
					//更新离店储值返现
					if ($main_order ['complete_balance_given'] == 2) {
						$balance_info = json_decode ( $main_order ['complete_balance_info'], TRUE );
						if (! empty ( $balance_info ['give_amount'] )) {
							$balance_info ['give_amount'] -= $this_item ['iprice'] * $balance_info ['give_rate'];
						}
						$order_update ['complete_balance_info'] = json_encode ( $balance_info );
					}
					$updata ['handled'] = 1;

					// 更新优惠信息
					if(!empty($order_update)){
						$this->db->where ( array (
								'inter_id' => $inter_id,
								'orderid' => $orderid
						) );
						$this->db->update ( self::TAB_HOA, $order_update );
					}
					// 处理库存
					if ($this_item['istatus']!=0){//状态不为待确认的才改库存
						$this->load->model ( 'hotel/Room_status_model' );
						$this->Room_status_model->change_hotel_temp_stock ( array (
								'inter_id' => $inter_id,
								'hotel_id' => $main_order ['hotel_id'],
								'room_id' => $this_item ['room_id'],
								'price_code' => $this_item ['price_code']
						), $this_item ['startdate'], $this_item ['enddate'], - 1 );
					}
					$this->load->model ( 'plugins/Template_msg_model' );
					$this->Template_msg_model->send_hotel_order_msg ( $main_order, 'hotel_order_cancel' );
					// 若全部完结且有订单是离店状态
					if ($is_end == 1) {
						$this->db->where ( array (
								'orderid' => $orderid,
								'inter_id' => $inter_id
						) );
						$this->db->update ( self::TAB_HO, array (
								'handled' => 1
						) );
						if($haven_in > 0){
							$this->handle_order ( $inter_id, $orderid, 3, '', array (
									'no_item' => true,
                                    'main_db' => 1
							) );
						}
					}
					break;
				default :
					break;
			}
			// 更新子单状态
			$this->db->where ( array (
// 					'inter_id' => $inter_id,
// 					'orderid' => $orderid,
					'id' => $this_item ['id']
			) );
			$this->db->update ( self::TAB_HOI, $updata );
			if ($this->db->trans_status () === FALSE) {
				$this->trans_rollback ();
				return false;
			}
			$this->db->trans_commit ();
			//记录订房操作日志
			$this->load->model('hotel/Hotel_log_model');
			$this->Hotel_log_model->add_admin_log('Order/items#'.$this_item ['id'],'save_'.$status,$updata);

			if($updata['istatus'] == 2){
				//入住订单绩效状态变更 start
				$this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
	            $this->load->model('distribute/Idistribution_model');
	            $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				if($check_new_on>0){
					$update_dist = array(
						'inter_id'=>$inter_id,
						'grade_table'=>'iwide_hotels_order',
						'grade_id'=>$this_item['id'],
						'order_status'=>$status,
						"status" => 4,//未核定－尚未离店
						'grade_typ'=>1//粉丝归属
					);
					$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
					$update_dist['grade_typ'] = 2;//社群客归属
					$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
					$update_dist['grade_typ'] = 3;//链接分销员归属
					$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
				}
				//入住订单绩效状态变更 end
			}elseif($updata['istatus'] == 3){
				//离店绩效状态变更 start
				$this->load->model ( 'distribute/Idistribution_model' );
				$check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				if($check_new_on>0){
					$this->Idistribution_model->leave_recount($inter_id,$this_item['id'],$this_item['iprice'],$room_night,$status,$ori_room_night);
					$this->write_log($this_item,$room_night,'离店');//调试
				}
				//离店绩效状态变更 end
				
				//会员间夜升级
				$this->load->model ( 'hotel/member/Level_model' ,'member_level_model');
				$this->member_level_model->create_roomnight_queue($inter_id,$main_order,$this_item);
			}elseif($updata['istatus'] == 4 || $updata['istatus'] == 5 || $updata['istatus'] == 8 || $updata['istatus'] == 11){
                //取消订单绩效状态变更 start
				$this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
				$this->load->model('distribute/Idistribution_model');
				$check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				if($check_new_on>0){
					$update_dist = array(
						'inter_id'=>$inter_id,
						'grade_table'=>'iwide_hotels_order',
						'grade_id'=>$this_item['id'],
						'order_status'=>$status,
						'status'=>5,//取消
						'grade_typ'=>1//粉丝归属
					);
					$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
					$update_dist['grade_typ'] = 2;//社群客归属
					$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
					$this->write_log($this_item,$update_dist,'取消');//调试
					$update_dist['grade_typ'] = 3;//链接分销员归属
					$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
				}
                //取消订单绩效状态变更 end
			}

			if ($is_end == 1 && in_array ( $updata ['istatus'], $end_status )) {
				$this->load->model('distribute/Idistribution_model');
				//主单完结
				$days = get_room_night($main_order ['startdate'],$main_order ['enddate'],'ceil',$main_order);//至少有一个间夜
				$this->Idistribution_model->leave_recount_by_orders($inter_id,$orderid,$real_price,$orderdays,$updata['istatus'],$days*$main_order['roomnums'],array('hotel_id'=>$main_order['hotel_id']));
				$this->write_log($main_order,$orderdays,'主单完结');//调试
			}
			return true;
		}
		return false;
	}
	public function get_order_item($inter_id, $orderid, $item_id = null, $detail = null) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( array (
				'i.inter_id' => $inter_id,
				'i.orderid' => $orderid
		) );
		$db->from ( self::TAB_HOI . ' i' );
		if($detail == 'detail'){
			$db->select ( 'h.name hotelname,i.*,o.name,o.tel,o.order_time,o.paytype' );
			$db->join ( self::TAB_HO . ' o', 'o.inter_id=i.inter_id and o.orderid=i.orderid' );
			$db->join ( self::TAB_H . ' h', 'h.inter_id=i.inter_id and h.hotel_id=o.hotel_id' );
		}
		if (! empty ( $item_id )) {
			$db->where ( 'i.id', $item_id );
			return $db->get ()->row_array ();
		}
		return $db->get ()->result_array ();
	}
	function order_stock($inter_id, $hotel_id, $room_id, $condits) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'room_id' => $room_id,
				'hotel_id' => $hotel_id
		) );
		$db_read->where_in ( 'date', $condits ['day_range'] );
		$db_read->where_in ( 'price_code', array (
				'-1',
				$condits ['price_code']
		) );
		$price_codes = $db_read->get ( self::TAB_HRS )->result_array ();
		$date_num = array ();
		foreach ( $price_codes as $pc ) {
			if ($pc ['price_code'] != - 1 && ! is_null ( $pc ['nums'] )) {
				$date_num [$pc ['date']] = $pc ['price_code'];
			} else {
				$date_num [$pc ['date']] = - 1;
			}
		}
		foreach ( $date_num as $dk => $dn ) {
			$this->db->where ( array (
					'inter_id' => $inter_id,
					'room_id' => $room_id,
					'hotel_id' => $hotel_id,
					'date' => $dk,
					'price_code' => $dn
			) );
			if ($condits ['type'] == 'reduce')
				$this->db->set ( 'nums', 'nums-1', FALSE );
			else
				$this->db->set ( 'nums', 'nums+1', FALSE );
			$this->db->update ( self::TAB_HRS );
		}
	}
	function pay_return($orderid, $openid = '') {
// 		$db_read = $this->load->database('iwide_r1',true);
		$map= array (
				'orderid' => $orderid
		) ;
		if ($openid)
			$map['openid']= $openid ;
// 		$order = $db_read->get_where( self::TAB_HO ,$map)->row_array ();
// 		if (empty($order)){
			$order = $this->db->get_where( self::TAB_HO ,$map)->row_array ();
MYLOG::w('pay_return-check_order_log:warning!!!time:'.microtime().',orderid:'.$orderid.':openid:'.$openid.'db_main_result:'.json_encode($order),'hotel_order'.DS.'pay_return','_read');
// MYLOG::w('pay_return-check_order_log:warning!!!time:'.microtime().',orderid:'.$orderid.':openid:'.$openid.'----db_read_result:'.json_encode($order_read).',db_main_result:'.json_encode($order),'hotel_order'.DS.'pay_return','_read');
// 		}
		
		if ($order) {
			if ($order ['paid'] == 1) {
// 				$payresult = $db_read->get_where ( 'pay_log', array (
// 						'out_trade_no' => $orderid
// 				) )->row_array ();
// 				if (empty($payresult)){
					$payresult = $this->db->get_where ( 'pay_log', array (
							'out_trade_no' => $orderid
					) )->row_array ();
MYLOG::w('pay_return-pay_result_log:time:'.microtime().',orderid:'.$orderid.':openid:'.$openid.'----db_main_result:'.json_encode($payresult),'hotel_order'.DS.'pay_result','_read');
// MYLOG::w('pay_return-pay_result_log:warning!!!time:'.microtime().',orderid:'.$orderid.':openid:'.$openid.'----db_read_result:'.json_encode($payresult_read).',db_main_result:'.json_encode($payresult),'hotel_order'.DS.'pay_result','_read');
// 				}
				if (! $payresult) {
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id']
					) );
					$this->db->update ( self::TAB_HO, array (
							'operate_reason' => '没有支付结果'
					) );
				} else {
				    $this->load->model ( 'hotel/Hotel_config_model' );
				    $config_data = $this->Hotel_config_model->get_hotel_config ( $order ['inter_id'], 'HOTEL', $order ['hotel_id'], array (
				            // 'HOTEL_IS_PMS',
				            'PMS_AFT_SUBMIT',
				            'WEB_BILL_RESUB',
				            'PAID_ORDER_NOT_AUTO_ENSURE'
				    ) );
				    $status = empty($config_data['PAID_ORDER_NOT_AUTO_ENSURE']) ? 1 :0 ;
					$this->db->where ( array (
							'orderid' => $order ['orderid'],
							'inter_id' => $order ['inter_id']
					) );
					$this->db->update ( self::TAB_HO, array (
							'status' => $status,
							'operate_reason' => '支付完成'
					) );

					$order = $this->get_main_order ( $order ['inter_id'], array (
							'orderid' => $order ['orderid'],
							'idetail' => array (
									'i'
							),
							'main_db'=> 1
					) );
					if (! empty ( $order )) {
						$order = $order [0];
						// if ((! empty ( $config_data ['HOTEL_IS_PMS'] ) && $config_data ['HOTEL_IS_PMS'] == 1) && (empty ( $config_data ['PMS_PRE_SUBMIT'] ))) {
						$this->load->library ( 'PMS_Adapter', array (
								'inter_id' => $order ['inter_id'],
								'hotel_id' => $order ['hotel_id']
						), 'pmsa' );
						if (! empty ( $config_data ['PMS_AFT_SUBMIT'] ) && $config_data ['PMS_AFT_SUBMIT'] == 1) {
							$result = $this->pmsa->order_submit ( $order ['inter_id'], $order ['orderid'], array (
									'trans_no' => $payresult ['out_trade_no'],
									'third_no' => $payresult['transaction_id']
							) );
							if ($result ['s'] == 0) {
								$this->handle_order ( $order ['inter_id'], $order ['orderid'], 10, $order ['openid'] ,array('main_db'=>1)); // pms下单失败，退回
							}
						} else {
							$result = $this->pmsa->add_web_bill ( $order, array (
									'trans_no' => $payresult ['out_trade_no'],
									'third_no' => $payresult['transaction_id']
							) );
							//PMS失败写入队列 add by ping 2017-02-24
							
							if (!$result && ! empty ( $config_data ['WEB_BILL_RESUB'] ) && $config_data ['WEB_BILL_RESUB'] == 1) {
		    					$this->load->model ( 'hotel/Order_queues_model' );
		    					$this->Order_queues_model->create_pms_queue($order ['inter_id'],$order ['hotel_id'],$order ['orderid'], array('third_no' => $payresult['transaction_id']));
							}

							// if ($result && ! empty ( $result ['ensure'] )) {
							// $this->handle_order ( $order ['inter_id'], $orderid, 'ss' );
							if (!empty($order['goods_details'])){
							    $this->load->model ( 'hotel/goods/Goods_order_model' );
							    $this->Goods_order_model->order_pay($order);
							}
							if ($status==1){
							    $this->handle_order ( $order ['inter_id'], $orderid, 1, $order ['openid'] ,array('main_db'=>1));
							}else if ($status==0){
							    $this->handle_order ( $order ['inter_id'], $orderid, 0, $order ['openid'] );
							}
							// }
						}
					}
					// $this->handle_order ( $orderid, 'paid' );
				}
				//修改订单队列状态为已处理
				$this->load->model ( 'hotel/Order_queues_model' );
				$this->Order_queues_model->cancel_queue($order ['inter_id'],$order ['hotel_id'],$order ['orderid']);
			}
		}
		// 发送模板消息
		// $this->handle_order($orderid,'paid');
		// if ($order ['status'] == 1 && $order ['paid'] == 1) {
		// if ($order ['paytype'] == 0) // 原支付方式为门店支付
		// $this->handle_order ( $orderid, 'repay' );
		// else
		// $this->handle_order ( $orderid, 1 );
		// }
	}
	function get_lowest_price_date($inter_id, $condits) {
		$db_read = $this->load->database('iwide_r1',true);
		$this->load->helper ( 'date' );
		$h = empty ( $condits ['hotel_ids'] ) ? '' : ' and hotel_id in (' . $condits ['hotel_ids'] . ') ';
		$valid_sql = 'select price_code from ' . $db_read->dbprefix ( self::TAB_HPS ) . " where inter_id = '$inter_id' and status=1 $h";
		$countday = get_room_night($condits ['startdate'],$condits ['enddate'],'round',$condits);//至少有一个间夜
		$enddate = date ( "Ymd", strtotime ( '- 1 day', strtotime ( $condits ['enddate'] ) ) );
		$day_range = get_day_range ( $condits ['startdate'], $enddate );
		$sql = "select a.hotel_id,min(format(sprice/dnum,2)) lowest from
				(SELECT sum(price) sprice,hotel_id,price_code,count(date) dnum ,room_id FROM `" . $db_read->dbprefix ( self::TAB_HRS ) . "`
				 where date in ($day_range) and inter_id='$inter_id' and price_code in ($valid_sql) $h group by hotel_id,price_code,room_id) a where a.dnum=$countday group by a.hotel_id";
		$result = $db_read->query ( $sql )->result_array ();
		$data = array ();
		if (! empty ( $result ))
			foreach ( $result as $r ) {
				$data [$r ['hotel_id']] = $r ['lowest'];
			}
		return $data;
	}

	function get_lowest_price($inter_id, $condits) {
		$db_read = $this->load->database('iwide_r1',true);
		$this->load->helper ( 'date' );
		$this->load->helper ( 'common' );
		if (empty($condits ['hotel_ids'])){
			return array();
		}
		$data = array ();
		$hotel = explode ( ',', $condits ['hotel_ids'] );
		if($hotel){
			//读取Redis数据
			$_hotels=[];//没有缓存数据的酒店ID
			foreach($hotel as $v){
				$_lowest=lowest_from_redis($inter_id,$v,$condits['member_level'],$condits['startdate'],$condits['enddate'],$condits['price_codes']);
				if($_lowest!==null){
					$data[$v]=$_lowest;
				}else{
					$_hotels[]=$v;

				}
			}
		}else{
			$_hotels=null;
		}
		//_hotels为NULL或_hotels是非空数组
		if($_hotels === null || (is_array($_hotels) && $_hotels)){
			$h = empty ($_hotels) ? '' : ' and hotel_id in (' . implode(',', $_hotels) . ') ';
			$h .= empty ($condits['price_codes']) ? '' : ' and price_code in (' . implode(',', $condits['price_codes']) . ') ';
			$valid_sql = 'select price_code from ' . $db_read->dbprefix(self::TAB_HPS) . " where inter_id = '$inter_id' and status=1 $h";
			$countday = get_room_night($condits ['startdate'],$condits ['enddate'],'round',$condits);//至少有一个间夜
			$enddate = date("Ymd", strtotime('- 1 day', strtotime($condits ['enddate'])));
			$day_range = get_day_range($condits ['startdate'], $enddate);
			$sql = "select a.hotel_id,min(sprice/dnum) lowest from
				(SELECT sum(price) sprice,hotel_id,price_code,count(date) dnum ,room_id FROM `" . $db_read->dbprefix(self::TAB_HRS) . "`
				 where date in ($day_range) and inter_id='$inter_id' and price_code in ($valid_sql) $h group by hotel_id,price_code,room_id) a group by a.hotel_id";
			$result = $db_read->query($sql)->result_array();
			if(!empty ($result)){
				foreach($result as $r){
					$data [$r ['hotel_id']] = $r ['lowest'];
				}
			}

			/*$mins = $this->get_lowest_code_price ( $inter_id );
			$tmp_mins = $this->get_tmp_lowest_price ( $inter_id );*/

			//没有缓存数据，查询数据库
			$mins = $this->get_lowest_code_price($inter_id, $_hotels ,$condits['price_codes']);
			$tmp_mins = $this->get_tmp_lowest_price($inter_id, $_hotels);

		}
		foreach ( $hotel as $ho ) {
			if (! empty ( $mins [$ho] )) {
				if (empty ( $data [$ho] )) {
					$data [$ho] = number_format ( $mins [$ho], '2', '.', '' );
				} else {
					$data [$ho] = $data [$ho] > $mins [$ho] ? number_format ( $mins [$ho], '2', '.', '' ) : number_format ( $data [$ho], '2', '.', '' );
				}
			}
			if (! empty ( $tmp_mins [$ho] )) {
				if (empty ( $data [$ho] )) {
					$data [$ho] = number_format ( $tmp_mins [$ho], '2', '.', '' );
				} else {
					$data [$ho] = $data [$ho] > $tmp_mins [$ho] ? number_format ( $tmp_mins [$ho], '2', '.', '' ) : number_format ( $data [$ho], '2', '.', '' );
				}
			}
		}
		return $data;
	}

	function get_lowest_code_price($inter_id,$hotel_ids=[],$price_codes=null) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select ( 'min(s.price) m_price,s.hotel_id' );
		$db_read->from ( self::TAB_HPS . ' s' );
		$db_read->join ( self::TAB_HPI . ' i', 's.inter_id=i.inter_id and s.price_code=i.price_code' );
		$db_read->join ( self::TAB_HR . ' r', 'r.inter_id=i.inter_id and r.room_id=s.room_id and r.hotel_id=s.hotel_id' );
		$db_read->where ( array (
				's.inter_id' => $inter_id,
				's.price >' => 0,
				's.status' => 1,
				'i.status' => 1,
				'r.status' => 1
		) );
		if(!empty($price_codes)){
			$db_read->where_in( 's.price_code',$price_codes );
		}
		//只查询传入的酒店
		if($hotel_ids){
			$db_read->where_in('s.hotel_id',$hotel_ids);
		}
		$db_read->group_by ( 's.hotel_id' );
		$min = $db_read->get ()->result_array ();
		$mins = array ();
		foreach ( $min as $m ) {
			$mins [$m ['hotel_id']] = number_format ( $m ['m_price'], '2', '.', '' );
		}
		return $mins;
	}

	public function get_tmp_lowest_price($inter_id,$hotel_ids=[]) {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->from('hotel_lowest_price')->where(array (
			                                             'inter_id' => $inter_id
		                                             ));
		//只查询传入的酒店
		if($hotel_ids){
			$db_read->where_in('hotel_id',$hotel_ids);
		}
		$lowest=$db_read->get()->result_array();

		/*$lowest = $db_read->get_where ( 'hotel_lowest_price', array (
				'inter_id' => $inter_id
		) )->result_array ();*/
		$tmp_mins = array ();
		foreach ( $lowest as $l ) {
			$tmp_mins [$l ['hotel_id']] = number_format ( $l ['lowest_price'], '2', '.', '' );
		}
		return $tmp_mins;
	}
	function order_status_sequence($status, $type = 'after') {
		$seq = array (
				'0' => array (
						'after' => array (
								1,
								4,
								5,
								8
						),
						'adminafter' => array (
								1,
								4,
								5,
								8
						)
				),
				'1' => array (
						'after' => array (
								2,
								3,
								4,
								5,
								8
						),
						'adminafter' => array (
								2,
								5,
								8
						)
				),
				'2' => array (
						'after' => array (
								3
						),
						'adminafter' => array (
								3
						)
				),
				'3' => array (
						'after' => array (),
						'adminafter' => array ()
				),
				'4' => array (
						'after' => array (),
						'adminafter' => array ()
				),

				'5' => array (
						'after' => array (),
						'adminafter' => array ()
				),
				'9' => array (
						'after' => array (
								0,
								1,
								4,
								5
						),
						'adminafter' => array (
								0,
								1,
								4,
								5
						)
				)
		);
		if (isset ( $seq [$status] ))
			return $seq [$status] [$type];
	}
	function get_order_sequence($status) {
		$this->load->model ( 'common/Enum_model' );
		$seq = array (
				'0' => array (
						'after' => array (
								2,
								3
						)
				),
				'1' => array (
						'before' => array (),
						'after' => array (
								2,
								3
						)
				),
				'2' => array (
						'before' => array (
								1
						),
						'after' => array (
								3
						)
				),
				'3' => array (
						'before' => array (
								1,
								2
						)
				),
				'4' => array (),

				'5' => array (),
				'11' => array ()
		);
		$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
		$sequence = array (
				'cur' => $status_des [$status]
		);
		if (! empty ( $seq [$status] ['before'] )) {
			foreach ( $seq [$status] ['before'] as $sb ) {
				$sequence ['before'] [] = $status_des [$sb];
			}
		}
		if (! empty ( $seq [$status] ['after'] )) {
			foreach ( $seq [$status] ['after'] as $sa ) {
				$sequence ['after'] [] = $status_des [$sa];
			}
		}
		return $sequence;
	}
	public function get_resource_name() {
		return 'hotel_orders';
	}
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}

	/**
	 *
	 * @return string the associated database table name
	 */
	public function table_name() {
		return 'hotel_orders';
	}
	public function table_primary_key() {
		return 'id';
	}
	public function attribute_labels() {
		return array (
				'id' => '订单ID',
				'hotel_id' => '酒店ID',
				'orderid' => '订单号',
				'openid' => 'Openid',
				'inter_id' => '公众号',
				'price' => '价格',
				'roomnums' => '房间数',
				'name' => '姓名',
				'tel' => '电话',
				'order_time' => '下单时间',
				'startdate' => '入住时间',
				'enddate' => '离店时间',
				'paid' => '支付状态',
				'status' => '状态',
				'holdtime' => '保留时间',
				'paytype' => '支付类型',
				'isdel' => 'Isdel',
				'operate_reason' => 'Operate_reason',
				'remark' => '备注',
				'member_no' => '会员号',
                'handled' => '完结状态',
                'mt_pms_orderid' => 'PMS订单号',
                'mt_room_id' => '房号'
        );
	}
	public function list_fields() {
		return array (
				'member_no' => array(
						'label' => '会员号'
				),
				'staff_info' => array(
						'label' => '分销员/分销号'
				),
				'show_orderid' => array (
						'label' => '订单ID'
				),
				'hname_rname' => array (
						'label' => '酒店名/房型'
				),
				'name' => array (
						'label' => '姓名'
				),
				'tel' => array (
						'label' => '电话'
				),
				'startdate' => array (
						'label' => '入住时间'
				),
				'enddate' => array (
						'label' => '离店时间'
				),
				'roomnums' => array (
						'label' => '房间数'
				),
				'order_datetime' => array (
						'label' => '下单时间'
				),
				'ori_price' => array (
						'label' => '原价'
				),
				'coupon_favour' => array (
						'label' => '使用优惠券'
				),
				'point_favour' => array (
						'label' => '积分抵用/积分数'
				),
				'real_price' => array (
						'label' => '总价'
				),
				'paytype' => array (
						'label' => '支付类型'
				),
				'is_paid' => array (
						'label' => '支付状态'
				),
				'status' => array (
						'label' => '状态'
				),
				'channel' => array (
						'label' => '渠道'
				),
				'remark' => array (
						'label' => '备注'
				),
				'mt_room_id' => array (
                    'label' => '房号'
                ),
                'mt_pms_orderid' => array (
                    'label' => 'PMS订单号'
                ),
		);
	}
	public function item_list_fields() {
		return array (
				'sid' => array (
						'label' => '订单ID',
						'span' => 1
				),
				'roomname_pricecode' => array (
						'label' => '房型',
						'span' => 1
				),
                'mt_room_id' => array (
                    'label' => '房号',
                    'span' => 1
                ),
				'room_no' => array (
						'label' => '房间号',
						'span' => 2
				),
				'startdate' => array (
						'label' => '入住日期',
						'span' => 1
				),
				'enddate' => array (
						'label' => '离店日期',
						'span' => 1
				),
				'roomnums' => array (
						'label' => '房间数',
						'span' => 2
				),
				'ori_price' => array (
						'label' => '原价',
						'span' => 3
				),
				'iprice' => array (
						'label' => '价格',
						'span' => 3
				),
				'istatus' => array (
						'label' => '订单状态',
						'span' => 1
				)
		);
	}
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields() {
		return array (
				'id',
				'orderid',
				'name',
				'from_unixtime(order_time) order_time',
				'tel',
				'roomnums',
				'price',
				'paid',
				'inter_id',
				'hotel_id',
				'price',
                'status',
                'mt_room_id'
		);
	}

	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 * type: grid中的表头类型定义
	 * form_type: form中的元素类型定义
	 * form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 * form_tips: form中的label信息提示
	 * form_hide: form中自动化输出中剔除
	 * form_default: form中的默认值，请用字符类型，不要用数字
	 * select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui() {
		/* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
		// type: numberbox数字框|combobox下拉框|text不写时默认|datebox
		$base_util = EA_base::inst ();
		$modules = config_item ( 'admin_panels' ) ? config_item ( 'admin_panels' ) : array ();

		/**
		 * 获取本管理员的酒店权限
		 */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;

		if ($this->_admin_inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($this->_admin_inter_id)
			$filter = array (
					'inter_id' => $this->_admin_inter_id
			);
		if (is_array ( $filter )) {
			$this->load->model ( 'wx/publics_model' );
			$publics = $this->publics_model->get_public_hash ( $filter );
			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
			// $publics= array_merge($hotels, array('ALL_PRIVILEGE'=>'-所有公众号-'));
		}

		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = array ();
		else if ($this->_admin_hotels)
			$filterH = array (
					'hotel_id' => $this->_admin_hotels
			);
		else
			$filterH = array ();

		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$hotels = $hotels + array (
					'0' => '-不限定-'
			);
		}
		/**
		 * 获取本管理员的酒店权限
		 */
		$paytypes = array (
				'weixin' => '微信支付',
				'daofu' => '到店支付'
		);
		return array (
				'id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'hotel_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select' => $hotels
				), // textarea|text|combobox|number|email|url|price
				'orderid' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled '
				),
				// 'form_default'=> '0',
				// 'form_tips'=> '注意事项',
				// 'form_hide' => TRUE,
				// 'function'=> 'show_price_prefix|￥',
				// 'type' => 'hidden'
				// textarea|text|combobox|number|email|url|price
				'openid' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'inter_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select' => $publics
				), // textarea|text|combobox|number|email|url|price
				'price' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'roomnums' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'name' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'tel' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'order_time' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'startdate' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'enddate' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'paid' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'status' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select' => array (
								'1' => '已确认',
								'2' => '已入住',
								'3' => '已离店',
								'4' => '用户取消',
								'5' => '酒店取消',
								'6' => '酒店删除',
								'7' => '异常',
								'8' => '未到',
								'9' => '未支付',
								'11' => '系统取消'
						)
				),
				'holdtime' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'paytype' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select' => $paytypes
				), // textarea|text|combobox|number|email|url|price
				'isdel' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'operate_reason' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'remark' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				), // textarea|text|combobox|number|email|url|price
				'member_no' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				),
				'handled' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui' => ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text'
				)
		); // textarea|text|combobox|number|email|url|price
	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field() {
		return array (
				'field' => 'order_time',
				'sort' => 'desc'
		);
	}
    private function write_log( $order,$result,$re)
	{
		$file= date('Y-m-d'). '.txt';
		//echo $tmpfile;die;
		$path= APPPATH.'logs'.DS. 'distribute'. DS;

		if( !file_exists($path) ) {
			@mkdir($path, 0777, TRUE);
		}

		//echo $tmpfile;die;
		// $path= APPPATH.'logs'.DS. 'mysql_log'. DS;
		$order=json_encode($order);
		if(is_array($result)){
			$result=json_encode($result);
		}
		$fp = fopen($path.$file, "a");
		//echo __FILE__
		$content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['PHP_SELF']." | ".session_id()." | ".$order." | ".$re." | ".$result."\n";

		fwrite($fp, $content);
		fclose($fp);
	}


    function update_refund_status($inter_id,$order_id,$refund=0){

        $this->db->where ( array (
            'inter_id' => $inter_id,
            'orderid' => $order_id
        ) );

        return $this->db->update ( self::TAB_HOA, array (
            'refund' => $refund,
        ) );

    }


    function update_point_reduce($inter_id,$order_id,$status=1){

        $this->db->where ( array (
            'inter_id' => $inter_id,
            'orderid' => $order_id
        ) );

        return $this->db->update ( self::TAB_HOA, array (
            'point_used' => $status,
        ) );

    }

    /*
     * 记录需要发微信模板消息的订单动作到队列表
     */
    function set_order_wxmsg($order,$tmp_type,$type){
    	if($type==11){
    		//系统取消不通知
    		return false;
    	}
    	if($type==4&&$order['paid']==0&&$order['paytype']!='daofu'){
    		//未支付且不是到付的用户取消不通知
    		return false;
    	}
    	$db_read = $this->load->database('iwide_r1',true);
    	$db_read->where(array(
    		'inter_id'=>$order['inter_id'],
    		'hotel_id' => $order['hotel_id'],
    		'orderid' => $order['orderid'],
    		'wx_type' => $type,
    		));
    	$isready = $db_read->get(self::TAB_HNQ)->result_array();
    	if(!empty($isready[0])){ //不重复插入队列
    		return false;
    	}
		// $db_read->where(array(
		// 	'inter_id'=>$order['inter_id'],
		// 	));
		// $wxconf = $db_read->get(self::TAB_HNC)->result_array();
		// if(empty($wxconf[0])){
		// 	$this->load->model('hotel/hotel_notify_model');
		// 	$wxconf[0] = $this->hotel_notify_model->notify_default_config();
		// }
		$db_read->where(array(
			'inter_id'=>$order['inter_id'],
			'status'=>1,
			));
		$db_read->where_in('hotel_id',array(0,$order['hotel_id']));
		$regs = $db_read->get(self::TAB_HNR)->result_array();
		// if($wxconf[0]['is_weixin']==1&&!empty($regs)&&(in_array($type,explode(',',$wxconf[0]['wx_notify']))||$wxconf[0]['wx_notify']=='all')){//配置微信提醒开启
	    if(!empty($regs)){
	    	$this->load->model('hotel/hotel_notify_model');
	    	$can = false;
	    	foreach ($regs as $reg) {
    			if($this->hotel_notify_model->check_reg($reg,$type)){
    				$can = true;
    			}
	    	}
	    	if($can){
		    	$data = array(
		    		'inter_id' => $order['inter_id'],
		    		'hotel_id' => $order['hotel_id'],
		    		'orderid' => $order['orderid'],
		    		'create_time' => time(),
		    		'locked' => 2,//1.锁定，2.开放
		    		'flag' => 2,//1.已处理，2.未处理
		    		'wx_type' => $type,
		    		'update_time' => time(),
		    		'oper_times' => 0,
		    		'out_time' => 0,
					'tmp_type' => $tmp_type,
		    		'order_data' => json_encode($order),
		    		'type' =>1,//微信提醒
		    		);
		    	$this->db->insert(self::TAB_HNQ,$data);
	    	}
	    }
	    // }
    }

    // 获取分销员信息
    function get_saler_info($inter_id,$grade_id,$orderid){
    	$db_read = $this->load->database('iwide_r1',true);
    	if(empty($inter_id)||empty($grade_id)){
    		return '';
    	}
    	$db_read->where(array(
    		'grade_id'=>$grade_id,
    		'grade_table'=>'iwide_hotels_order',
    		));
    	$dists1 = $db_read->get(self::TAB_DGA)->result_array();

    	$db_read->where(array(
    		'grade_id'=>$orderid,
    		'grade_table'=>'iwide_hotels_order',
    		));
    	$dists2 = $db_read->get(self::TAB_DGA)->result_array();
    	$dists = array_merge($dists1,$dists2);
    	$salers = array();
    	$return = array();
    	foreach ($dists as $dist) {
    		if(in_array($dist['saler'],$salers)){
    			continue;
    		}
	    	if(!empty($dist['saler'])){
	    		$salers[] = $dist['saler'];
	    		$db_read->where(array(
	    			'inter_id' => $inter_id,
	    			'qrcode_id'=>$dist['saler'],
	    			));
		    	$saler_info = $db_read->get(self::TAB_STF)->row_array();

		    	if(!empty($saler_info)){
		    		$return[] = $saler_info['name'].'-'.$saler_info['qrcode_id'];
		    	}
	    	}
    	}
    	return $return;
    }

}
