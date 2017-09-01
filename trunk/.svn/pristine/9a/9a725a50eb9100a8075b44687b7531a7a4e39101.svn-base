<?php
/*
 * 商品订单模型类
 * author chenjunyu
 * date 2016-11-29
 */
class Order_ticket_model extends MY_Model{
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
	private $price_type = 'ticket';
	function __construct(){
		parent::__construct();
	}

	/**
	 * 获取订单列表信息
	 * @param1 $inter_id String @param2 Array $idents 搜索条件 @param3 Boolean $just_count 是否求总数 默认false
	 */
	function get_order_list($inter_id, $idents = array(), $just_count = false) {
		$s = '';
		$o = '';
		$oa = '';
		$c = '';
		if (isset ( $idents ['status'] ) && ! is_null ( $idents ['status'] )) {
			$s .= ' status in (' . $idents ['status'] . ' ) and ';
		}
		if (isset ( $idents ['ept_status'] ) && ! is_null ( $idents ['ept_status'] )) {
			$s .= ' status not in (' . $idents ['ept_status'] . ' ) and ';
		}
		if (! empty ( $idents ['hotel_id'] )) {
			$s .= ' hotel_id in ( ' . $idents ['hotel_id'] . ' ) and ';
		}
		if (! empty ( $idents ['orderid'] )) {
			$o .= " ( orderid ='" . $idents ['orderid'] . "' or tel ='" . $idents ['orderid'] . "' ) and ";
//			$oa .= " ( orderid ='" . $idents ['orderid'] . "' ) and ";
            $oa="";
		} else if (! empty ( $idents ['oid'] )) {
			$o .= " id =" . $idents ['oid'] . " and ";
		}
		if(!empty($idents['td'])){
			$day_start = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$day_end = mktime(23,59,59,date("m"),date("d"),date("Y"));
			$s .= " order_time between $day_start and $day_end and ";
		}
		if(!empty($idents['tdstart'])){
			$s .= " startdate<=".date('Ymd')." and enddate>".date('Ymd')." and ";
		}
		if(isset ( $idents ['hotel'] ) &&is_numeric($idents['hotel'])&&$idents['hotel']>=0){
			$s .= " hotel_id=".$idents['hotel']." and ";
		}
		if(!empty($idents['hotel_name'])){
			$c .= " name like '%".$idents['hotel_name']."%' and ";
		}
		if(isset ( $idents ['timetype'] ) &&is_numeric($idents['timetype'])&&$idents['timetype']>0){
			switch ($idents['timetype']) {
				//下单时间
				case 1:
					$timetype = "order_time";
					break;
				//入住时间
				case 2:
					$timetype = "startdate";
					break;
				//离店时间
				case 3:
					$timetype = "enddate";
					break;
				default:
					break;
			}
			if(!empty($idents['start_t'])&&!empty($idents['end_t'])){
				$s_date = $idents['start_t'];
				$e_date = $idents['end_t'];
				if($idents['timetype']==1){
					$s_date .= '000000';
					$e_date .= '235959';
					$s_date = strtotime($s_date);
					$e_date = strtotime($e_date);
				}
				$s .= " $timetype>='$s_date' and $timetype<='$e_date' and ";
			}elseif(!empty($idents['start_t'])||!empty($idents['end_t'])){
				$date = $idents['start_t']?$idents['start_t']:$idents['end_t'];
				$s_date = $e_date = $date;
				if($idents['timetype']==1){
					$s_date = strtotime($date);
					$e_date = $s_date+60*60*24-1; 
				}
				$s .= " $timetype>='$s_date' and $timetype<='$e_date' and ";
			}
		}
		if(!empty($idents['number'])){
			$s_number = $idents['number'];
			$s .= " (name='$s_number' or tel='$s_number' or orderid='$s_number' or remark='$s_number') and ";
		}
		if(isset ( $idents ['paytype'] ) &&is_numeric($idents['paytype'])&&$idents['paytype']>0){
			switch ($idents['paytype']) {
				//微信支付
				case 1:
					$s .= " paytype='weixin' and ";					
					break;
				//到店支付
				case 2:
					$s .= " paytype='daofu' and ";					
					break;
				//积分支付or积分换房
				case 3:
					$s .= " (paytype='point' or paytype='bonus') and ";					
					break;
				//储值支付
				case 4:
					$s .= " paytype='balance' and ";					
					break;				
				default:
					break;
			}
		}
		if(isset ( $idents ['paystatus'] ) &&is_numeric($idents['paystatus'])&&$idents['paystatus']>=0){
			$s .= " paid= '".$idents['paystatus']."' and "; 
		}
		if(isset ( $idents ['orderstatus'] ) &&is_numeric($idents['orderstatus'])){
			if($idents['orderstatus']>=0){
				$s .= " status='".$idents['orderstatus']."' and ";
			}
		}

		$is = '';
		if (isset ( $idents ['istatus'] )) {
			$mm_sql = "select * from " . $this->db->dbprefix ( self::TAB_HO ) . " where $s inter_id='$inter_id' and isdel=0 and price_type='ticket'";
			$ii_sql = "select * from ". $this->db->dbprefix ( self::TAB_H ) . " where $c inter_id='$inter_id'";
			$m_sql = "select o.* from (".$mm_sql.") o join(".$ii_sql.") h on o.hotel_id=h.hotel_id";
			$i_sql = "select * from " . $this->db->dbprefix ( self::TAB_HOI ) . " where inter_id='$inter_id' and istatus in (" . $idents ['istatus'] . " ) group by orderid";
			$is = "select m_o.* from ($m_sql) m_o join ($i_sql) i_o on m_o.orderid=i_o.orderid and m_o.inter_id=i_o.inter_id";
			if ($just_count == true) {
				return $this->db->query ( $is )->num_rows ();
			}
		}
		$m_sql = "select * from " . $this->db->dbprefix ( self::TAB_HO ) . " where $s $o inter_id='$inter_id' and isdel=0 and price_type='ticket'";
		$i_sql = "select * from ". $this->db->dbprefix ( self::TAB_H ) . " where $c inter_id='$inter_id'";
		$order_sql = "select o.* from (" .$m_sql.') o join ('.$i_sql.') h on o.hotel_id=h.hotel_id';
		if ($just_count == true) {
			return $this->db->query ( $order_sql )->num_rows ();
		}
		$addition_sql = "select * from " . $this->db->dbprefix ( self::TAB_HOA ) . " where $oa inter_id='$inter_id'";
		$sql = "select oa.*,o.*,h.name hname,h.intro_img himg,h.address haddress,h.longitude,h.latitude,h.tel htel 
				 from ($order_sql) o
				  join (select * from " . $this->db->dbprefix ( self::TAB_H ) . " where $c inter_id='$inter_id') h on o.hotel_id=h.hotel_id
			       left join ($addition_sql) oa on o.orderid=oa.orderid ";
		$sql .= empty ( $is ) ? ' ' : " join ($is) i_s on i_s.id=o.id and i_s.inter_id=o.inter_id ";
		$sql .= empty ( $idents ['order_by'] ) ? ' order by o.order_time desc' : " order by " . $idents ['order_by'];
		$sql .= empty ( $idents ['nums'] ) ? '' : ' limit ' . $idents ['offset'] . ',' . $idents ['nums'];

		$result = $this->db->query ( $sql )->result_array ();
		if (! empty ( $idents ['idetail'] )) {
			foreach ( $result as $k => $r ) {
				$idents ['id'] = $r ['orderid'];
				$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
				$result [$k] ['order_details'] = $this->get_order_items ( $inter_id, $r ['orderid'], $idents ['idetail'] );
				$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
			}
		}
		return $result;
	}

	function get_order_items($inter_id, $orderid, $infos = array()) {
		$selects = 'i.*';
		$sql = '';
		$joins = '';
		if (! empty ( $infos )) {
			foreach ( $infos as $i ) {
				switch ($i) {
					case 'r' :
						$selects .= ',r.room_img r_room_img,r.name r_name';
						$joins .= ' left join (select * from ' . $this->db->dbprefix ( self::TAB_HR ) . " where inter_id='$inter_id') r on r.room_id = i.room_id";
						break;
					case 'rno' :
						$selects .= ',rno.num_id rno_num_id,rno.room_no rno_room_no,rno.net_lock rno_net_lock,rno.lock_id rno_lock_id,rno.status rno_status,rno.des rno_des';
						$joins .= ' left join (select * from ' . $this->db->dbprefix ( self::TAB_HRN ) . " where inter_id='$inter_id') rno on rno.room_id = i.room_id and rno.num_id = i.room_no_id";
						break;
					default :
						break;
				}
			}
		}
		$item_sql = "select *,id sub_id from " . $this->db->dbprefix ( self::TAB_HOI ) . " where inter_id='$inter_id' and orderid='$orderid'";
		$item_sql .= empty ( $infos ['item_id'] ) ? '' : ' and id= ' . $infos ['item_id'];
		if ($joins)
			$sql = "select $selects from ($item_sql) i $joins";
		else
			$sql = $item_sql;
		return $this->db->query ( $sql )->result_array ();
	}

	//获取当前公众号下的所有出现过的酒店信息
	function get_all_hotels($inter_id,$hotel_ids=null){
		if(!empty($hotel_ids)){
			$sql = "SELECT hotel_id,inter_id,`name` FROM ".$this->db->dbprefix ( self::TAB_H )." WHERE hotel_id IN(".$hotel_ids.") AND inter_id='$inter_id' AND `status`=1";
				return $this->db->query($sql)->result_array();
		}
		$sql = "SELECT hotel_id FROM iwide_hotel_orders WHERE inter_id='$inter_id' GROUP BY hotel_id";
		$ids = $this->db->query($sql)->result_array();
		if(!empty($ids)){
			$temp = '';
			foreach ($ids as  $value) {
				$temp .= $value['hotel_id'].',';
			}
			$temp = trim($temp,',');
			if($temp){
				$sql = "SELECT hotel_id,inter_id,`name` FROM ".$this->db->dbprefix ( self::TAB_H )." WHERE hotel_id IN($temp) AND inter_id='$inter_id' AND `status`=1";
				return $this->db->query($sql)->result_array();
			}else{
				return array();
			}
		}else{
			return array();
		}
	}

	//检查这个状态的改变是否可行
	function check_status($ori_status, $next_status, $inter_id='') {
		$after = $this->order_status_sequence ( $ori_status, 'after' );
		if (in_array ( $next_status, $after )) {
			return true;
		}else if ($inter_id=='a455510007'&&$ori_status==3&&$next_status==3){//速8定制
			return true;
		}
		return false;
	}

	//修改订单状态
	function update_order_status($inter_id, $orderid, $status, $openid = '', $paid = false,$ss=false) {
		if ($ss){
			$this->order_model->handle_order ( $inter_id, $orderid, 'ss' );
		}
		$where = array (
				'inter_id' => $inter_id,
				'orderid' => $orderid 
		);
		if ($openid)
			$where ['openid'] = $openid;
		$order = $this->db->get_where ( self::TAB_HO, $where )->row_array ();
		if ($this->check_status ( $order ['status'], $status,$inter_id)) {
			$this->db->where ( $where );
			$updata = array (
					'status' => $status 
			);
			if ($paid)
				$updata ['paid'] = 1;
			$this->db->update ( self::TAB_HO, $updata );
			return $this->handle_order ( $inter_id, $orderid, $status, $openid );
		}
		return false;
	}

	//可允许的下一步状态改变
	function order_status_sequence($status, $type = 'after') {
		$seq = array (
				'0' => array (
						'after' => array (
								1,
								4,
								5
						) 
				),
				'1' => array (
						'after' => array (
								2,
								3,
								4,
								5,
								8
						) 
				),
				'2' => array (
						'after' => array (
								3 
						) 
				),
				'3' => array (
						'after' => array () 
				),
				'4' => array (
						'after' => array () 
				),
				
				'5' => array (
						'after' => array () 
				),
				'9' => array (
						'after' => array (
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

	function get_main_order($inter_id, $idents = array()) {
		$s = '';
		$o = '';
		$a = '';
		if (! empty ( $idents ['member_no'] ))
			$s = "( o.member_no='" . $idents ['member_no'] . "' or ( o.openid='" . $idents ['openid'] . "' and (o.member_no ='' or o.member_no is null) )) and";
		else if (! empty ( $idents ['openid'] ))
			$s = " o.openid='" . $idents ['openid'] . "' and (o.member_no ='' or o.member_no is null) and ";
		else if (! empty ( $idents ['only_openid'] ))
			$s = " o.openid='" . $idents ['only_openid'] . "' and ";
		if (isset ( $idents ['status'] ) && ! is_null ( $idents ['status'] )) {
			$s .= ' o.status in (' . $idents ['status'] . ' ) and ';
		}
		if (isset ( $idents ['handled'] ) && ! is_null ( $idents ['handled'] )) {
			$s .= ' o.handled =  ' . $idents ['handled'] . ' and ';
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
		$sql = "select oa.*,o.*,h.name hname,h.intro_img himg,h.address haddress,h.longitude,h.latitude,h.tel htel
				 from ".$this->db->dbprefix ( self::TAB_HO )." o
		          join " . $this->db->dbprefix ( self::TAB_H ) . " h on o.hotel_id=h.hotel_id and o.inter_id=h.inter_id
		           left join ".$this->db->dbprefix ( self::TAB_HOA )." oa on o.orderid=oa.orderid and o.inter_id=oa.inter_id 
		           	 where $s $o h.inter_id = '$inter_id' ";
		$sql .= empty ( $idents ['order_by'] ) ? ' order by o.id desc' : " order by " . $idents ['order_by'];
		$sql .= empty ( $idents ['nums'] ) ? '' : ' limit ' . $idents ['offset'] . ',' . $idents ['nums'];
		$result = $this->db->query ( $sql )->result_array ();
		if (! empty ( $idents ['idetail'] )) {
			foreach ( $result as $k => $r ) {
				$idents ['id'] = $r ['orderid'];
				$result [$k] ['order_datetime'] = date ( 'Y-m-d H:i:s', $result [$k] ['order_time'] );
				$result [$k] ['order_details'] = $this->get_order_items ( $inter_id, $r ['orderid'], $idents ['idetail'] );
				$result [$k] ['first_detail'] = empty ( $result [$k] ['order_details'] ) ? array () : $result [$k] ['order_details'] [0];
			}
		}
		return $this->orders_format($result);
	}
	
	function orders_format($orders){
		if (!empty($orders['orderid'])){
			$orders=array($orders);
		}
		foreach ($orders as $ok=>$o){
			$orders[$ok]['ori_price']=number_format($o['price']+$o['coupon_favour']+$o['balance_part']+$o['point_favour']+$o['wxpay_favour'],2,'.','');
		}
		if (!empty($orders['orderid'])){
			return $orders[0];
		}
		return $orders;
	}

	function handle_order($inter_id, $orderid, $status, $openid = '', $params = array()) {

      	$this->load->model('hotel/Member_model');
		$vid=$this->Member_model->get_vid($inter_id);//统一取

		$isdel = empty ( $params ['isdel'] ) ? 0 : $params ['isdel'];
		$order = $this->get_main_order ( $inter_id, array (
				'orderid' => $orderid,
				'only_openid' => $openid,
				'isdel' => $isdel,
				'idetail' => array (
						'i' 
				) 
		) );
		if ($order) {
			$order = $order [0];
			// $days = get_room_night($order['startdate'],$order['enddate'],'ceil',$order);//至少有一个间夜
			$this->load->model ( 'hotel/Member_model' );
			$this->load->model ( 'hotel/Coupon_model' );
			$same_count = 0;
			$thiscase = true; //防止4 11状态重复执行模板消息发送
			switch ($status) {
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
									// //确认订单绩效状态变更 start
									// $this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
						   //          $this->load->model('distribute/Idistribution_model');
						   //          $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
									// if($check_new_on>0){
									// 	$update_dist = array(
									// 		'inter_id'=>$inter_id,
									// 		'grade_table'=>'iwide_hotels_order',
									// 		'grade_id'=>$od['id'],
									// 		'order_status'=>$status,
									// 		"status" => 4,//未核定－尚未离店
									// 		'grade_typ'=>1//粉丝归属
									// 	);
									// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									// 	$update_dist['grade_typ'] = 2;//社群客归属
									// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									// }
									//确认订单绩效状态变更 end
								}
							}
						}
						// 生成订单完成时发放的优惠券
                        // if($vid!=1){
                        //     if ($order ['complete_reward_given'] == 0) {
                        //         $market_reward = $this->Coupon_model->create_market_reward ( $inter_id, $order, 'order_complete', array (
                        //             'hotel' => $order ['hotel_id'],
                        //             'rooms' => $order ['roomnums'],
                        //             'days' => $days,
                        //             'product_num' => $order ['roomnums'],
                        //             'price_code' => $order ['first_detail'] ['price_code'],
                        //             'category' => $order ['first_detail'] ['room_id'],
                        //             'amount' => $order ['price']
                        //         ) );
                        //         $this->db->where ( array (
                        //             'orderid' => $order ['orderid'],
                        //             'inter_id' => $inter_id
                        //         ) );
                        //         if ($market_reward ['s'] == 1) {
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => json_encode ( $market_reward ['coupons'] ),
                        //                 'complete_reward_given' => 2
                        //             ) );
                        //         } else {
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'complete_reward_given' => 1
                        //             ) );
                        //         }
                        //     }
                        // }


                        // if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //新版会员确认后发放优惠券
                        //     $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                        //     if (isset($give_info ['status']['ensure']) && $give_info ['status']['ensure'] == 0) {
                        //         $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['ensure'], 'order_complete','ensure');
                        //         $this->db->where ( array (
                        //             'orderid' => $order ['orderid'],
                        //             'inter_id' => $inter_id
                        //         ) );
                        //         if ($market_reward) {
                        //             $give_info ['status']['ensure']=1;  //发放成功
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //             $this->load->model ( 'plugins/Template_msg_model' );
                        //             $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                        //         } else {
                        //             $give_info ['status']['ensure']=2;  //发放失败
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //         }
                        //     }
                        // }

						// 生成订单完成时发放的积分
						// if ($order ['complete_point_given'] == 0) {
						// 	$point_reward = $this->Member_model->get_point_reward ( $inter_id, $order, 'room' );
						// 	$this->db->where ( array (
						// 			'orderid' => $order ['orderid'],
						// 			'inter_id' => $inter_id 
						// 	) );
						// 	if ($point_reward) {
						// 		$this->db->update ( self::TAB_HOA, array (
						// 				'complete_point_info' => json_encode ( $point_reward ),
						// 				'complete_point_given' => 2 
						// 		) );
						// 	} else {
						// 		$this->db->update ( self::TAB_HOA, array (
						// 				'complete_point_given' => 1 
						// 		) );
						// 	}
						// }
						// 发放模板消息
						$notice=0;
						if ($same_count == count ( $order ['order_details'] ) && (empty ( $params ['no_tmpmsg'] ))) {
							$this->load->model ( 'plugins/Template_msg_model' );
							$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_ensure' );
							$notice=1;
						}
						// $this->load->model ( 'plugins/Print_model' );
						// //微信支付确认才进行优惠券核销
						// if ($order ['status'] != 9 && ($order['paytype'] =='weixin' || $order['paytype'] =='weifutong' || $order['paytype'] =='lakala')) {
						// 	$this->Print_model->print_hotel_order ( $order, 'new_order' );
						// 	if ($order['holdtime']==='18:00'){
						// 		$holdtime = date ( 'Y-m-d 18:00', strtotime ( $order ['startdate'] ) );
						// 		if ($order ['paid'] == 1) {
						// 			$holdtime = date ( 'Y-m-d 12:00', strtotime ( $order ['enddate'] ) );
						// 		}
						// 		$this->db->where ( array (
						// 				'orderid' => $order ['orderid'],
						// 				'inter_id' => $inter_id 
						// 		) );
						// 		$this->db->update ( self::TAB_HO, array (
						// 				'holdtime' => $holdtime 
						// 		) );
						// 	}
						// }else if ($notice==1) {
						// 	$this->Print_model->print_hotel_order ( $order, 'ensure_order' );
						// }
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
									// $this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
						   //          $this->load->model('distribute/Idistribution_model');
						   //          $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
									// if($check_new_on>0){
									// 	$update_dist = array(
									// 		'inter_id'=>$inter_id,
									// 		'grade_table'=>'iwide_hotels_order',
									// 		'grade_id'=>$od['id'],
									// 		'order_status'=>$status,
									// 		"status" => 4,//未核定－尚未离店
									// 		'grade_typ'=>1//粉丝归属
									// 	);
									// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									// 	$update_dist['grade_typ'] = 2;//社群客归属
									// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									// }
									//入住订单绩效状态变更 end
								}
							}
						}
						if ($same_count == count ( $order ['order_details'] )) {
							$this->load->model ( 'plugins/Template_msg_model' );
							$this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_checkin' );
						}

                        // if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //新版会员入住后发放优惠券
                        //     $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                        //     if (isset($give_info ['status']['in']) && $give_info ['status']['in'] == 0) {
                        //         $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['in'], 'order_complete','in');
                        //         $this->db->where ( array (
                        //             'orderid' => $order ['orderid'],
                        //             'inter_id' => $inter_id
                        //         ) );
                        //         if ($market_reward) {
                        //             $give_info ['status']['in']=1;  //发放成功
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //             $this->load->model ( 'plugins/Template_msg_model' );
                        //             $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                        //         } else {
                        //             $give_info ['status']['in']=2;  //发放失败
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //         }
                        //     }
                        // }

						break;
					}
				case 3 : // 订单为离店状态 
					{
						break;//票券类无此状态
						$this->load->model ( 'hotel/Room_status_model' );
						$this->load->model ( 'plugins/Template_msg_model' );
						$this->load->model ( 'distribute/Idistribution_model' );

						if (empty ( $params ['no_item'] )) {
							foreach ( $order ['order_details'] as $od ) {
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
				                    $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				                    if($check_new_on>0){
				                    	$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
				                    	$this->Idistribution_model->leave_recount($inter_id,$od['id'],$od['iprice'],$oddays,$status,$days);
				                    	$this->write_log($od,$oddays,'离店');//调试
				                    }
									
									//离店绩效状态变更 end
								} else if ($od ['handled'] == 1) {
									$same_count ++;
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
						}
						break;
					}
				case 4 :
                    {
                    	//发送微信模板消息通知酒店人员 start
                    	$this->set_order_wxmsg($order, 'hotel_order_cancel_notice',4);
                    	$thiscase = false;
                    	//发送微信模板消息通知酒店人员 end
                        // if($vid==1 && isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //客户取消后发放优惠券
                        //     $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                        //     if (isset($give_info ['status']['custom_cancel']) && $give_info ['status']['custom_cancel'] == 0) {
                        //         $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['custom_cancel'], 'order_complete','custom_cancel');
                        //         $this->db->where ( array (
                        //             'orderid' => $order ['orderid'],
                        //             'inter_id' => $inter_id
                        //         ) );
                        //         if ($market_reward) {
                        //             $give_info ['status']['custom_cancel']=1;  //发放成功
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //             $this->load->model ( 'plugins/Template_msg_model' );
                        //             $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                        //         } else {
                        //             $give_info ['status']['custom_cancel']=2;  //发放失败
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //         }
                        //     }
                        // }

                        if($order['paid']==1 && $order['paytype']=='weixin'){      //微信支付退款

                            $this->load->model ( 'hotel/Hotel_config_model' );
                            $config_data = $this->Hotel_config_model->get_hotel_config ( $order ['inter_id'], 'HOTEL', 0, array (
                                'CHECK_WEIXIN_REFUND'
                            ) );

                            if(isset($config_data) && $config_data['CHECK_WEIXIN_REFUND']==1){

                                $refund_result =  $this->Order_check_model->hotel_weixin_refund($order ['orderid'],$inter_id,'send');

                                if(!empty($refund_result['status']) && $refund_result['status']==1){

                                    $this->update_refund_status($inter_id,$order ['orderid'],1);

                                }else{

                                    $this->update_refund_status($inter_id,$order ['orderid'],2);
//        $this->db->insert('weixin_text',array('content'=>'order_weixin_refund+订单号：'.$order ['orderid'].'退款失败','edit_date'=>date('Y-m-d H:i:s')));


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
						// $this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
			            // $this->load->model('distribute/Idistribution_model');
			            // $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启

						if (empty ( $params ['no_item'] )) {
							foreach ( $order ['order_details'] as $od ) {
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
			                        // if($check_new_on>0){
			                        // 	$update_dist = array(
			                        // 		'inter_id'=>$inter_id,
			                        // 		'grade_table'=>'iwide_hotels_order',
			                        // 		'grade_id'=>$od['id'],
			                        // 		'order_status'=>$status,
			                        // 		'status'=>5,//取消
			                        // 		'grade_typ'=>1//粉丝归属
			                        // 	);
			                        // 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
			                        // 	$update_dist['grade_typ'] = 2;//社群客归属
			                        // 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
			                        // 	$this->write_log($od,$update_dist,'取消');//调试
			                        // }
			                        //取消订单绩效状态变更 end
								} else if ($od ['handled'] == 1) {
									$same_count ++;
								}
							}
						}


                    // if($vid==1){
                        // if(isset($order['coupon_give_info']) && !empty($order['coupon_give_info'])){     //酒店取消后发放优惠券
                        //     $give_info = json_decode ( $order['coupon_give_info'], TRUE );
                        //     if (isset($give_info ['status']['hotel_cancel']) && $give_info ['status']['hotel_cancel'] == 0) {
                        //         $market_reward = $this->Coupon_model->give_market_reward ( $inter_id, $order, $give_info ['hotel_cancel'], 'order_complete','hotel_cancel');
                        //         $this->db->where ( array (
                        //             'orderid' => $order ['orderid'],
                        //             'inter_id' => $inter_id
                        //         ) );
                        //         if ($market_reward) {
                        //             $give_info ['status']['hotel_cancel']=1;  //发放成功
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //             $this->load->model ( 'plugins/Template_msg_model' );
                        //             $this->Template_msg_model->send_hotel_order_msg ( $order, 'hotel_order_complete_coupon_reward' );
                        //         } else {
                        //             $give_info ['status']['hotel_cancel']=2;  //发放失败
                        //             $update_coupon_info=json_encode($give_info);
                        //             $this->db->update ( self::TAB_HOA, array (
                        //                 'coupon_give_info' => $update_coupon_info
                        //             ) );
                        //         }
                        //     }
                        // }
                        // if (! empty ( $order ['coupon_des'] )) {//优惠券退回
                        //     $this->load->model ( 'hotel/Coupon_model' );
                        //     $order_wxcards = $this->Coupon_model->change_order_coupon ( $order ['orderid'], $order ['inter_id'], $order ['openid'], $order ['coupon_des'], 'back' ,$vid,$order);
                        // }
                    // }
						// 积分返还
						/*
						 * if ($order ['point_used'] == 1) {
						 * $point_back = $this->Member_model->point_back ( $inter_id, $order ['openid'], $order ['orderid'] );
						 * $this->db->where ( array (
						 * 'orderid' => $order ['orderid'],
						 * 'inter_id' => $inter_id
						 * ) );
						 * if ($point_back) {
						 * $this->db->update ( self::TAB_HOA, array (
						 * 'point_used' => 2
						 * ) );
						 * } else {
						 * $this->db->update ( self::TAB_HOA, array (
						 * 'point_used' => 4
						 * ) );
						 * }
						 * }
						 */
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
						// $this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
						// $this->load->model('distribute/Idistribution_model');
						// $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
						if (empty ( $params ['no_item'] )) {
							foreach ( $order ['order_details'] as $od ) {
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
									// if($check_new_on>0){
									// 	$update_dist = array(
									// 		'inter_id'=>$inter_id,
									// 		'grade_table'=>'iwide_hotels_order',
									// 		'grade_id'=>$od['id'],
									// 		'order_status'=>$status,
									// 		'status'=>5,//取消
									// 		'grade_typ'=>1//粉丝归属
									// 	);
									// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									// 	$update_dist['grade_typ'] = 2;//社群客归属
									// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
									// 	$this->write_log($od,$update_dist,'取消');//调试
									// }
			                        //取消订单绩效状态变更 end
								} else if ($od ['handled'] == 1) {
									$same_count ++;
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
							foreach ( $order ['order_details'] as $od ) {
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
							}
						}
						// 积分返还
						// if ($order ['point_used'] == 1) {
						// 	$point_back = $this->Member_model->point_back ( $inter_id, $order ['openid'], $order ['orderid'] );
						// 	$this->db->where ( array (
						// 			'orderid' => $order ['orderid'],
						// 			'inter_id' => $inter_id 
						// 	) );
						// 	if ($point_back) {
						// 		$this->db->update ( self::TAB_HOA, array (
						// 				'point_used' => 2 
						// 		) );
						// 	} else {
						// 		$this->db->update ( self::TAB_HOA, array (
						// 				'point_used' => 4 
						// 		) );
						// 	}
						// }
						if ($same_count == count ( $order ['order_details'] )) {
							$this->db->where ( array (
									'orderid' => $order ['orderid'],
									'inter_id' => $inter_id 
							) );
							$this->db->update ( self::TAB_HO, array (
									'handled' => 1 
							) );
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
					$this->set_order_wxmsg($order, 'hotel_order_notice',1);
					//发送微信模板消息通知酒店人员 end
					$order ['openid']=$ori_openid;
					// if (! empty ( $order ['coupon_des'] )) {
					// 	$this->load->model ( 'hotel/Coupon_model' );
					// 	$order_wxcards = $this->Coupon_model->change_order_coupon ( $order ['orderid'], $order ['inter_id'], $order ['openid'], $order ['coupon_des'], 'hang_on',NULL,$order );
					// 	if (! empty ( $order_wxcards ['wxcards'] )) {
					// 		$this->Coupon_model->wx_card_consume ( $order ['inter_id'], $order_wxcards ['wxcards'] );
					// 	}
					// }
					// if ($order ['status'] != 9 && $order['paytype'] !='weixin' && $order['paytype'] !='weifutong' && $order['paytype'] !='lakala') {
						
					// 	if ($order['holdtime']==='18:00'){
					// 		$holdtime = date ( 'Y-m-d 18:00', strtotime ( $order ['startdate'] ) );
					// 		if ($order ['paid'] == 1) {
					// 			$holdtime = date ( 'Y-m-d 12:00', strtotime ( $order ['enddate'] ) );
					// 		}
					// 		$this->db->where ( array (
					// 				'orderid' => $order ['orderid'],
					// 				'inter_id' => $inter_id 
					// 		) );
					// 		$this->db->update ( self::TAB_HO, array (
					// 				'holdtime' => $holdtime 
					// 		) );
					// 	}
					// 	$this->load->model ( 'plugins/Print_model' );
					// 	$this->Print_model->print_hotel_order ( $order, 'new_order' );
					// }

                    //新版会员在订单生成就生成优惠券信息
                    // if($vid==1){
                    //     if ($order ['complete_reward_given'] == 0) {
                    //         $market_reward = $this->Coupon_model->create_market_reward ( $inter_id, $order, 'order_complete', array (
                    //             'hotel' => $order ['hotel_id'],
                    //             'rooms' => $order ['roomnums'],
                    //             'days' => $days,
                    //             'product_num' => $order ['roomnums'],
                    //             'price_code' => $order ['first_detail'] ['price_code'],
                    //             'category' => $order ['first_detail'] ['room_id'],
                    //             'amount' => $order ['price']
                    //         ) );
                    //         $this->db->where ( array (
                    //             'orderid' => $order ['orderid'],
                    //             'inter_id' => $inter_id
                    //         ) );
                    //         if ($market_reward ['s'] == 1) {
                    //             $this->db->update ( self::TAB_HOA, array (
                    //                 'coupon_give_info' => json_encode ( $market_reward ['coupons'] ),
                    //                 'complete_reward_given' => 2
                    //             ) );
                    //         } else {
                    //             $this->db->update ( self::TAB_HOA, array (
                    //                 'complete_reward_given' => 1
                    //             ) );
                    //         }
                    //     }


                    //     if ($order ['complete_point_given'] == 0) {       //新版会员在订单生成就生成积分赠送信息
                    //         $point_given=$this->Member_model->check_point_giverules( $inter_id, $order, 'create_order', array (
                    //             'hotel' => $order ['hotel_id'],
                    //             'rooms' => $order ['roomnums'],
                    //             'product_num' => $order ['roomnums'],
                    //             'price_code' => $order ['first_detail'] ['price_code'],
                    //         ) );

                    //         $this->db->where ( array (
                    //             'orderid' => $order ['orderid'],
                    //             'inter_id' => $inter_id
                    //         ) );
                    //         if ($point_given ['code'] == 1) {
                    //             $this->db->update ( self::TAB_HOA, array (
                    //                 'complete_point_info' => json_encode ( $point_given ['result'] ),
                    //                 'complete_point_given' => 2
                    //             ) );
                    //         } else {
                    //             $this->db->update ( self::TAB_HOA, array (
                    //                 'complete_point_given' => 1
                    //             ) );
                    //         }
                    //     }

                    // }

                    //生成分销信息 start
					// $this->load->model ( 'distribute/Idistribution_model' );
					// $this->load->model ( 'club/Club_list_model' );
					// if($order['first_detail']['club_id']){
					// 	$saler = $this->Club_list_model->get_club_by_id($inter_id,$order['first_detail']['club_id']);//获取分销id
					// }
					// $this->load->model ( 'distribute/Fans_model' );
					// $fans = $this->Fans_model->get_fans_beloning($inter_id,$order ['openid']);
					// foreach ( $order ['order_details'] as $od ) {
					// 	// 获得相差天数
					// 	$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
					// 	$I_params = array(
					// 		'inter_id' => $inter_id, //公众号id
					// 		'hotel_id' => $order ['hotel_id'], //酒店id
					// 		'grade_openid' => $order ['openid'], //用户openid
					// 		'now_time' => date('Y-m-d',$order ['order_time']), //下单时间
					// 		'price_typeid' => $od['price_code'], //价格代码
					// 		'pay_wayid' => $order['paytype'],//支付方式
					// 		'order_amount' => $od['iprice'],//订单总金额
					// 		'grade_amount' => $od['iprice'],//订单计算绩效部分的金额
					// 		'days' => $oddays,//天数
					// 		'order_id' => $order['web_orderid']? $order['web_orderid']:$od['orderid'],//订单号(如果有PMS订单号传PMS订单号)
					// 		'grade_id' => $od['id'],//记录产生绩效的表的主键值
					// 		'product' => $od['roomname'],//产品名称
					// 		'istatus' => $od['istatus'],//订单状态
					// 		'fans_hotel' => isset($fans->hotel_id)?$fans->hotel_id:$order ['hotel_id'],//粉丝所属酒店
					// 		'fans_source' => isset($fans->source)?$fans->source:0,//粉丝归属
					// 		'saler' => isset($saler['id'])? $saler['id']:0//分销员id
					// 	);
					// 	$this->Idistribution_model->get_best($I_params);
					// 	$this->write_log($od,$I_params,'下单');//调试


						// 处理库存
						$this->Room_status_model->change_hotel_temp_stock ( array (
								'inter_id' => $inter_id,
								'hotel_id' => $order ['hotel_id'],
								'room_id' => $od ['room_id'],
								'price_code' => $od ['price_code']
						), $od ['startdate'], $od ['enddate'], 1 );
					// }
					//生成分销信息 end
					//插入订单数据到数据库队列，用于系统取消
					if($order['paytype'] == 'weixin' || $order['paytype'] == 'weifutong' || $order['paytype'] =='lakala' ){
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

	/*
     * 记录需要发微信模板消息的订单动作到队列表 
     */
    function set_order_wxmsg($order,$tmp_type,$type){
    	$this->db->where(array(
    		'inter_id'=>$order['inter_id'],
    		'hotel_id' => $order['hotel_id'],
    		'orderid' => $order['orderid'],
    		'wx_type' => $type,
    		));
    	$isready = $this->db->get(self::TAB_HNQ)->result_array();
    	if(!empty($isready[0])){ //不重复插入队列
    		return false;
    	}
		$this->db->where(array(
			'inter_id'=>$order['inter_id'],
			));
		$wxconf = $this->db->get(self::TAB_HNC)->result_array();
		if(empty($wxconf[0])){
			$this->load->model('hotel/hotel_notify_model');
			$wxconf[0] = $this->hotel_notify_model->notify_default_config();
		}
		$this->db->where(array(
			'inter_id'=>$order['inter_id'],
			'status'=>1,
			));
		$this->db->where_in('hotel_id',array(0,$order['hotel_id']));
		$regs = $this->db->get(self::TAB_HNR)->result_array();
		if($wxconf[0]['is_weixin']==1&&!empty($regs)&&(in_array($type,explode(',',$wxconf[0]['wx_notify']))||$wxconf[0]['wx_notify']=='all')){//配置微信提醒开启
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
	public function get_order_item($inter_id, $orderid, $item_id = null) {
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'orderid' => $orderid 
		) );
		if (! empty ( $item_id )) {
			$this->db->where ( 'id', $item_id );
			return $this->db->get ( self::TAB_HOI )->row_array ();
		}
		return $this->db->get ( self::TAB_HOI )->result_array ();
	}
    public function update_order_item($inter_id, $orderid, $id, $data) {
		$item = $this->get_order_item ( $inter_id, $orderid, $id );
       	$this->load->model('hotel/Member_model');
		$vid=$this->Member_model->get_vid($inter_id);//统一取
		if (! empty ( $item )) {
			// $this->db->trans_begin ();
// 			if (! empty ( $data ['startdate'] ) && ! empty ( $data ['enddate'] ) && ($data ['enddate'] > $data ['startdate'] || ! empty ( $data ['no_check_date'])) && ($item ['istatus'] == 2 || $item ['istatus'] == 1)) {
// 				$this->load->helper ( 'date' );
// 				$begin_range = get_day_range ( date ( 'Ymd', strtotime ( '- 10 day', strtotime ( $item ['startdate'] ) ) ), date ( 'Ymd', strtotime ( '+ 10 day', strtotime ( $item ['startdate'] ) ) ), 'array' );
// 				$end_range = get_day_range ( date ( 'Ymd', strtotime ( '- 10 day', strtotime ( $item ['enddate'] ) ) ), date ( 'Ymd', strtotime ( '+ 10 day', strtotime ( $item ['enddate'] ) ) ), 'array' );
// 				$updata = array ();
// 				$order_update = array ();
// 				$main_order=$this->get_order($inter_id,array('orderid'=>$orderid));
// 				$main_order=$main_order[0];
//                 if ((in_array ( $data ['startdate'], $begin_range ) && in_array ( $data ['enddate'], $end_range )) || ! empty ( $data ['no_check_date'] )) {
//                     $updata ['startdate'] = $data ['startdate'];
//                     $updata ['enddate'] = $data ['enddate'];

//                     $room_night = get_room_night($updata ['startdate'],$updata ['enddate'],'ceil',$updata);//至少有一个间夜
//                     $ori_room_night = get_room_night($item ['startdate'],$item ['enddate'],'ceil',$item);//至少有一个间夜
//                     $room_night_diff=$ori_room_night-$room_night;

//                     if($vid==1){
//                         $order = $this->get_main_order ( $inter_id, array (
//                             'orderid' => $main_order['orderid'],
//                             'only_openid' => $main_order['openid'],
//                             'isdel' => $main_order['isdel'],
//                             'idetail' => array (
//                                 'i'
//                             )
//                         ) );
//                         $order=$order[0];
//                         $total_price=0;
//                         $total_night=0;
//                         if(!empty($order['order_details'])){
//                             foreach($order['order_details'] as $arr){
//                                 $total_price=$total_price+$arr['iprice'];
//                                 $update_date = get_room_night($arr ['startdate'],$arr ['enddate'],'ceil',$arr);//至少有1个间夜
//                                 $total_night=$total_night+$update_date;
//                             }
//                         }
// 						$days = get_room_night($order ['startdate'],$order ['enddate'],'ceil',$order);//至少有1个间夜
// 	                    $new_night=$total_night - $ori_room_night + $room_night;
// 	                    if(!empty($data['new_price']) && $data['new_price'] > 0){
// 		                    $new_price = $total_price - $item['iprice'] + $data['new_price'];
// 	                    } else{
// 		                    $new_price = $total_price;
// 	                    }

//                         $this->load->model ( 'hotel/Coupon_model' );
//                         $new_market_reward = $this->Coupon_model->create_market_reward ( $inter_id, $order, 'order_complete', array (
//                             'hotel' => $order ['hotel_id'],
//                             'rooms' => $order ['roomnums'],
//                             'days' => $days,
//                             'product_num' => $order ['roomnums'],
//                             'price_code' => $order ['first_detail'] ['price_code'],
//                             'category' => $order ['first_detail'] ['room_id'],
//                             'amount' => $new_price,
//                             'extra_nums'=>$new_night
//                         ) );
//                         if(!empty($new_market_reward)){
//                             $new_market_reward=$new_market_reward['coupons'];
//                         }
//                         $coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );

//                         if(empty($coupon_info)&&!empty($new_market_reward)){
//                             $coupon_info=$new_market_reward;
//                         }elseif(!empty($new_market_reward)){
//                             foreach($new_market_reward['status'] as $key=>$arr){
//                                 if(empty($coupon_info['status'][$key]) || $coupon_info['status'][$key]==0){
//                                     $coupon_info[$key]=$new_market_reward[$key];
//                                     $coupon_info['status'][$key]=0;
//                                 }
//                             }
//                         }


//                         $order_update ['coupon_give_info'] = json_encode ( $coupon_info );

//                     }elseif ($vid!=1 && $main_order ['complete_reward_given'] == 2) {
//                         $coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );
//                         foreach ( $coupon_info ['check_out'] as $out_k => $out ) {
//                             if (! empty ( $out ['cards'] )) {
//                                 foreach ( $out ['cards'] as $ck => $oc ) {
//                                     if ($oc ['give_rule'] == 'room_nights') {
//                                         $coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] -= $room_night_diff * $oc ['give_num'];
//                                     } else if ($oc ['give_rule'] == 'order') {
// // 										if ($is_last == 1) {
// // 											$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] = 0;
// // 										}
//                                     }
//                                 }
//                             }
//                         }
//                         $order_update ['coupon_give_info'] = json_encode ( $coupon_info );
//                     }
//                 }
// 				if (! empty ( $data ['new_price'] ) && $data ['new_price'] > 0) {
// 					$updata ['iprice'] = $data ['new_price'];
					
// 					if ($main_order ['complete_point_given'] == 2) {
// 						$point_info = json_decode ( $main_order ['complete_point_info'], TRUE );
// 						if (! empty ( $point_info ['give_amount'] )) {
// 							$price_diff=$item['iprice']-$updata ['iprice'];
// 							if ($point_info ['type'] == 'BALANCE') {
// 								$point_info ['give_amount'] -= $price_diff * $point_info ['give_rate'];
// 							} else if ($point_info ['type'] == 'ORDER') {
// // 								if ($is_last == 1) {
// // 									$point_info ['give_amount'] = 0;
// // 								}
// 							}
// 						}
// 						$order_update ['complete_point_info'] = json_encode ( $point_info );
// 					}
// 				}
//                 if (! empty ( $data ['mt_room_id'] ) && $data ['mt_room_id'] !=$item ['mt_room_id']) {
//                     $updata ['mt_room_id'] = $data ['mt_room_id'];
//                 }
// 				// 更新优惠信息
// 				if(!empty($order_update)){
// 					$this->db->where ( array (
// 							'inter_id' => $inter_id,
// 							'orderid' => $orderid
// 					) );
// 					$this->db->update ( self::TAB_HOA, $order_update );
// 				}
				
// 				if (! empty ( $updata )) {
// 					$this->db->where ( array (
// // 							'orderid' => $orderid,
// 							'id' => $id,
// // 							'inter_id' => $inter_id 
// 					) );
// 					$this->db->update ( self::TAB_HOI, $updata );
// 				}
// 				if ($this->db->trans_status () === FALSE) {
// 					$this->db->trans_rollback ();
// 					return false;
// 				}
// 				$this->db->trans_commit ();
				
// 				//记录订房操作日志
// 				if(!empty($updata)){
// 					$this->load->model('hotel/Hotel_log_model');
// 					$this->Hotel_log_model->add_admin_log('Order/items#'.$id,'save',$updata);
// 				}
				
// 			}
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

	function update_order_item_status($inter_id, $orderid, $item_id, $status) {
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
			foreach ( $main_order ['order_details'] as $od ) {
				$items [$od ['sub_id']] = $od;
				if (in_array ( $od ['istatus'], $cancle_status )) {
					$cancle_count ++;
				}
				if (in_array ( $od ['istatus'], $end_status )) {
					$end_count ++;
				}
				if ($od ['istatus'] == 3) {
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
			// $room_night = get_room_night($this_item ['startdate'],$this_item ['enddate'],'ceil',$this_item);//至少有一个间夜
			// $ori_room_night = get_room_night($main_order ['startdate'],$main_order ['enddate'],'ceil',$main_order);//至少有一个间夜
			// $room_night_diff=$ori_room_night-$room_night;
			$this->db->trans_begin ();
			switch ($status) {
				case 1 :
					$this->handle_order ( $inter_id, $orderid, 1 );
					break;
				case 2 :
					$this->load->model ( 'plugins/Template_msg_model' );
					$this->Template_msg_model->send_hotel_order_msg ( $main_order, 'hotel_order_checkin' );
					break;
				case 3 :
					break;//票券类没有离店状态
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
								'no_item' => true 
						) );
					}
					// 处理库存
					// $this->load->model ( 'hotel/Room_status_model' );
					// $this->Room_status_model->change_hotel_temp_stock ( array (
					// 		'inter_id' => $inter_id,
					// 		'hotel_id' => $main_order ['hotel_id'],
					// 		'room_id' => $this_item ['room_id'],
					// 		'price_code' => $this_item ['price_code'] 
					// ), $this_item ['startdate'], $this_item ['enddate'], - 1 );
					$this->load->model ( 'plugins/Template_msg_model' );
					$this->Template_msg_model->send_hotel_order_msg ( $main_order, 'hotel_order_complete' );
					$updata ['handled'] = 1;
					$updata ['leavetime'] = date('Y-m-d H:i:s',time());//离店时间
					break;
				case 4 :
				case 5 :
				case 8 :
				case 11:
					$order_update = array ();
					// if ($main_order ['complete_reward_given'] == 2) {
					// 	$coupon_info = json_decode ( $main_order ['coupon_give_info'], TRUE );
					// 	foreach ( $coupon_info ['check_out'] as $out_k => $out ) {
					// 		if (! empty ( $out ['cards'] )) {
					// 			foreach ( $out ['cards'] as $ck => $oc ) {
					// 				if ($oc ['give_rule'] == 'room_nights') {
					// 					$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] -= $room_night * $oc ['give_num'];
					// 				} else if ($oc ['give_rule'] == 'order') {
					// 					if ($is_last == 1) {
					// 						$coupon_info ['check_out'] [$out_k] ['cards'] [$ck] ['card_nums'] = 0;
					// 					}
					// 				}
					// 			}
					// 		}
					// 	}
					// 	$order_update ['coupon_give_info'] = json_encode ( $coupon_info );
					// }
					// if ($main_order ['complete_point_given'] == 2) {
					// 	$point_info = json_decode ( $main_order ['complete_point_info'], TRUE );
					// 	if (! empty ( $point_info ['give_amount'] )) {
					// 		if ($point_info ['type'] == 'BALANCE') {
					// 			$point_info ['give_amount'] -= $this_item ['iprice'] * $point_info ['give_rate'];
					// 		} else if ($point_info ['type'] == 'ORDER') {
					// 			if ($is_last == 1) {
					// 				$point_info ['give_amount'] = 0;
					// 			}
					// 		}
					// 	}
					// 	$order_update ['complete_point_info'] = json_encode ( $point_info );
					// }
					$updata ['handled'] = 1;
					
					// 更新优惠信息
					// if(!empty($order_update)){
					// 	$this->db->where ( array (
					// 			'inter_id' => $inter_id,
					// 			'orderid' => $orderid 
					// 	) );
					// 	$this->db->update ( self::TAB_HOA, $order_update );
					// }
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
					// if ($is_end == 1) {
					// 	$this->db->where ( array (
					// 			'orderid' => $orderid,
					// 			'inter_id' => $inter_id 
					// 	) );
					// 	$this->db->update ( self::TAB_HO, array (
					// 			'handled' => 1 
					// 	) );
					// 	if($haven_in > 0){
					// 		$this->handle_order ( $inter_id, $orderid, 3, '', array (
					// 				'no_item' => true 
					// 		) );
					// 	}
					// }
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
				// $this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
	   //          $this->load->model('distribute/Idistribution_model');
	   //          $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				// if($check_new_on>0){
				// 	$update_dist = array(
				// 		'inter_id'=>$inter_id,
				// 		'grade_table'=>'iwide_hotels_order',
				// 		'grade_id'=>$this_item['id'],
				// 		'order_status'=>$status,
				// 		"status" => 4,//未核定－尚未离店
				// 		'grade_typ'=>1//粉丝归属
				// 	);
				// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
				// 	$update_dist['grade_typ'] = 2;//社群客归属
				// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
				// }
				//入住订单绩效状态变更 end
			}elseif($updata['istatus'] == 3){
				//离店绩效状态变更 start
				// $this->load->model ( 'distribute/Idistribution_model' );
				// $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				// if($check_new_on>0){
				// 	$this->Idistribution_model->leave_recount($inter_id,$this_item['id'],$this_item['iprice'],$room_night,$status,$ori_room_night);
				// 	$this->write_log($this_item,$room_night,'离店');//调试
				// }
				//离店绩效状态变更 end
			}elseif($updata['istatus'] == 4 || $updata['istatus'] == 5 || $updata['istatus'] == 8 || $updata['istatus'] == 11){
                //取消订单绩效状态变更 start
				// $this->load->model ( 'distribute/Idistribute_model' );//加载分销接口
				// $this->load->model('distribute/Idistribution_model');
				// $check_new_on = $this->Idistribution_model->check_new_on($inter_id);//查询新规则有没开启
				// if($check_new_on>0){
				// 	$update_dist = array(
				// 		'inter_id'=>$inter_id,
				// 		'grade_table'=>'iwide_hotels_order',
				// 		'grade_id'=>$this_item['id'],
				// 		'order_status'=>$status,
				// 		'status'=>5,//取消
				// 		'grade_typ'=>1//粉丝归属
				// 	);
				// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
				// 	$update_dist['grade_typ'] = 2;//社群客归属
				// 	$this->Idistribute_model->create_dist ( $update_dist );//更新分销信息
				// 	$this->write_log($this_item,$update_dist,'取消');//调试
				// }
                //取消订单绩效状态变更 end
			}
			
			return true;
		}
		return false;
	}

	/**
	 * 后台管理的表格中要用到的字段
	 */
	public function grid_fields() {
		return array (
			'orderid',
			'hotel_name',
			'inter_id',
			'hotel_id',
			'order_time',
			'price',
			'roomnums',
			'coupon_favour',
			'point_favour',
			'name',
			'tel',
			'startdate',
			'paytype',
			'paid',
			'status',
		);
	}

	/**
	 * 后台管理的表格中字段显示配置
	 */
	public function label_fields(){
		return array(
			'orderid' => array(
				'label' => '酒店&商品',
				),
			'hotel_name' => array(
				'label' => '酒店&商品',
				),
			'inter_id' => array(
				'label' => '酒店&商品',
				),
			'hotel_id' => array(
				'label' => '酒店&商品',
				),
			'order_time' => array(
				'label' => '酒店&商品',
				),
			'name' => array(
				'label' => '客户信息',
				),
			'tel' => array(
				'label' => '客户信息',
				),
			'startdate' => array(
				'label' => '使用时间',
				),
			'price' => array(
				'label' => '实付金额&数量',
				),
			'roomnums' => array(
				'label' => '实付金额&数量',
				),
			'coupon_favour' => array(
				'label' => '用券&积分',
				),
			'point_favour' => array(
				'label' => '用券&积分',
				),
			'paytype' => array(
				'label' => '支付状态',
				),
			'paid' => array(
				'label' => '支付状态',
				),
			'status' => array(
				'label' => '订单状态',
				),
		);
	}
}