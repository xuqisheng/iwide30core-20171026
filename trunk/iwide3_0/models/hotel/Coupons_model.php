<?php
class Coupons_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_USE_RULES = 'iwide_hotel_coupon_urules';
	public $rule_types = array (
			'voucher' => array (
					'key' => 'voucher',
					'des' => '代金券',
					'type' => 1 
			),
			'discount' => array (
					'key' => 'discount',
					'des' => '折扣券',
					'type' => 2 
			),
			'exchange' => array (
					'key' => 'exchange',
					'des' => '兑换券',
					'type' => 3 
			),
			'balance' => array (
					'key' => 'balance',
					'des' => '储值卡',
					'type' => 4 
			) 
	);
	function _load_db() {
		return $this->db;
	}
	function get_userule_list($inter_id, $status = NULL, $nums = NULL, $offset = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->order_by ( 'rule_id desc' );
		$db->where ( 'inter_id', $inter_id );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $nums ) ?  : $db->limit ( intval ( $nums ), intval ( $offset ) );
		return $db->get ( self::TAB_USE_RULES )->result_array ();
	}
	function get_userules_by_couponids($inter_id,$coupons_ids, $format=TRUE) {
		$db = $this->load->database('iwide_r1',true);
		$sql='select * from '.self::TAB_USE_RULES." where inter_id = '$inter_id' and status = 1 and ( ";
		$i=0;
		foreach ($coupons_ids as $id){
			if ($i!=0){
				$sql.=' or ';
			}
			$sql.=' coupon_ids like "%|'.$id.'|%" ';
			$i++;
		}
		$sql.=' ) ';
		$result=$db->query($sql)->result_array ();
		$rules=array();
		if (! empty ( $result ) && $format) {
			foreach ($result as $rule){
				$rule ['coupon_ids']=substr($rule ['coupon_ids'], 1,strlen($rule ['coupon_ids'])-2);
				$rule ['coupon_ids'] = explode ( '|', $rule ['coupon_ids'] );
				$rule ['hotel_rooms'] = json_decode ( $rule ['hotel_rooms'], TRUE );
				$rule ['rule_dates'] = json_decode ( $rule ['rule_dates'], TRUE );
				$rule ['extra_rule'] = json_decode ( $rule ['extra_rule'], TRUE );
				if (! empty ( $rule ['rule_dates'] )) {
					if (!empty($rule ['rule_dates'] ['d'] ['d']))
						$rule ['rule_dates'] ['d'] ['d'] = explode ( ',', $rule ['rule_dates'] ['d'] ['d'] );
						if (! empty ( $rule ['rule_dates'] ['d'] ['r']['week'] ))
							$rule ['rule_dates'] ['d'] ['r'] ['week'] = explode ( ',', $rule ['rule_dates'] ['d'] ['r'] ['week'] );
				}
				foreach ($rule ['coupon_ids'] as $cid){
					$rules[$cid][]=$rule;
				}
			}
			return $rules;
		}
		return $result;
	}
	function get_userule($inter_id, $rule_id, $format = TRUE, $status = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( 'inter_id', $inter_id );
		$db->where ( 'rule_id', $rule_id );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		$rule = $db->get ( self::TAB_USE_RULES )->row_array ();
		if (! empty ( $rule ) && $format) {
			$rule ['coupon_ids']=substr($rule ['coupon_ids'], 1,strlen($rule ['coupon_ids'])-2);
			$rule ['coupon_ids'] = explode ( '|', $rule ['coupon_ids'] );
			$rule ['hotel_rooms'] = json_decode ( $rule ['hotel_rooms'], TRUE );
			$rule ['rule_dates'] = json_decode ( $rule ['rule_dates'], TRUE );
			$rule ['extra_rule'] = json_decode ( $rule ['extra_rule'], TRUE );
			if (! empty ( $rule ['rule_dates'] )) {
				if (!empty($rule ['rule_dates'] ['d'] ['d']))
					$rule ['rule_dates'] ['d'] ['d'] = explode ( ',', $rule ['rule_dates'] ['d'] ['d'] );
				if ( isset($rule ['rule_dates'] ['d'] ['r']['week'])&&$rule ['rule_dates'] ['d'] ['r']['week']!==''&&is_string($rule ['rule_dates'] ['d'] ['r']['week']) )
					$rule ['rule_dates'] ['d'] ['r'] ['week'] = explode ( ',', $rule ['rule_dates'] ['d'] ['r'] ['week'] );
			}
		}
		return $rule;
	}
	function get_hotel_coupons($inter_id, $type = '', $next_id = NULL, $nums = NULL) {
		// 1抵用2折扣3兑换4储值
		if (! empty ( $type )) {
			$type = empty ( $this->rule_types [$type] ['type'] ) ? '' : $this->rule_types [$type] ['type'];
		}
		$this->load->model ( 'hotel/Coupon_new_model', 'Coupon_new_model' );
		$result = $this->Coupon_new_model->allCouponsList ( $inter_id );
		$coupon_types = array ();
		if (! empty ( $result ['data'] )) {
			foreach ( $result ['data'] as $c ) {
				if ((! empty ( $type ) && $c ['card_type'] == $type) || empty ( $type )) {
					$coupon_types [$c ['card_id']] = $c;
				}
			}
			$next_id = $result ['next_id'];
		}
		return array (
				'coupon_types' => $coupon_types,
				'next_id' => $next_id 
		);
	}
	function update_userule($inter_id, $rule_id, $updata) {
		$db = $this->_load_db ();
		$check = $this->get_userule ( $inter_id, $rule_id, FALSE );
		if (empty ( $check )) {
			return FALSE;
		}
// 		$updata ['update_time'] = date ( 'Y-m-d H:i:s' );
		$db->where ( array (
				'inter_id' => $inter_id,
				'rule_id' => $rule_id 
		) );
		$result=$db->update ( self::TAB_USE_RULES, $updata );
		if ($result&&$db->affected_rows()>0){
			$db->where ( array (
					'inter_id' => $inter_id,
					'rule_id' => $rule_id
			) );
			$result=$db->update ( self::TAB_USE_RULES, array('update_time'=>date ( 'Y-m-d H:i:s' )) );
			
			$update_diff=array();
			foreach ($check as $k=>$c){
				if (isset($updata[$k])&&$check[$k]!=$updata[$k]){
					$update_diff[$k]=array('old'=>$c,'new'=>$updata[$k]);
				}
			}
			$this->load->model('hotel/Hotel_log_model');
			$this->Hotel_log_model->add_admin_log('hotel_coupon_urules#'.$rule_id,'save',$update_diff);
		}
		return $result;
	}
	function add_userule($inter_id, $data) {
		$db = $this->_load_db ();
		$data ['inter_id'] = $inter_id;
		$data ['create_time'] = date ( 'Y-m-d H:i:s' );
		$result=$db->insert ( self::TAB_USE_RULES, $data );
		if ($result){
			$rule_id=$db->insert_id();
			$this->load->model('hotel/Hotel_log_model');
			unset($data ['inter_id']);
			unset($data ['create_time']);
			$this->Hotel_log_model->add_admin_log('hotel_coupon_urules#'.$rule_id,'add',$data);
		}
		return $result;
	}
	
	function check_userule($inter_id,$cards,$params){
		$card_ids=array_column($cards, 'card_id');
		$card_ids=array_unique($card_ids);
		$rules=$this->get_userules_by_couponids($inter_id, $card_ids,1);
		$checked=array();
		$valid_cards=array();
		$effects=array('paytype_counts'=>0);
		$countday = get_room_night($params ['startdate'],$params ['enddate'],'round',$params);//至少有一个间夜
// 		var_dump($params);
		foreach ($cards as &$c){
			if (isset($checked[$c['card_id']])&&$checked[$c['card_id']]===0){
				continue;
			}					
			if (!empty($rules[$c['card_id']])){
				foreach ($rules[$c['card_id']] as $r){
					//适用门店
					if (!empty($r['hotel_rooms'])){
						if (empty($r['hotel_rooms'][$params['hotel']][$params['category']])||!in_array($params['price_code'], $r['hotel_rooms'][$params['hotel']][$params['category']])){
							$checked[$c['card_id']]=0;
							break;
						}
					}
					//日期规则
					if (!empty($r['rule_dates'])){
						$day_range=get_day_range ( $params ['startdate'], $params ['enddate'], 'array' );
						array_pop ( $day_range );
						if (!empty($r['rule_dates']['d']['d'])){
							$this->load->helper('date');
							$day_check=check_date_in ( $r['rule_dates']['d']['d'],$day_range);
							if ((empty($r['rule_dates']['r'])||$r['rule_dates']['r']==1)&&$day_check){
								$checked[$c['card_id']]=0;
								break;
							}
							if((!empty($r['rule_dates']['r'])&&$r['rule_dates']['r']==2)&&!$day_check){
								$checked[$c['card_id']]=0;
								break;
							}
						}
						$break_tag=0;
						if (!empty($r['rule_dates']['d']['r']['week'])){
							foreach ($day_range as $dr){
								$weekday=date('w',strtotime($dr));
								$week_check=in_array($weekday, $r['rule_dates']['d']['r']['week']);
								if ((empty($r['rule_dates']['r'])||$r['rule_dates']['r']==1)&&$week_check){
									$checked[$c['card_id']]=0;
									$break_tag=1;
									break;
								}
								if((!empty($r['rule_dates']['r'])&&$r['rule_dates']['r']==2)&&!$week_check){
									$checked[$c['card_id']]=0;
									$break_tag=1;
									break;
								}
							}
						}
						if ($break_tag==1)
							break;
					}
					//其他规则
					if (!empty($r['extra_rule'])){
						if (!empty($r['extra_rule']['level'])&&!in_array($params['level'], $r['extra_rule']['level'])){
							$checked[$c['card_id']]=0;
							break;
						}
						if (!empty($r['extra_rule']['paytype'])){
							$effects['paytype_counts']=1;
							if(!in_array($params['paytype'], $r['extra_rule']['paytype'])){
								$checked[$c['card_id']]=0;
								break;
							}
						}
						
						//@Editor lGh 2016-10-21 21:03:21 最低消费
						if (!empty($r['extra_rule']['min_money'])&&intval($r['extra_rule']['min_money'])>0){
							if($params['amount']<intval($r['extra_rule']['min_money'])){
								$checked[$c['card_id']]=0;
								break;
							}
						}
					}
					$c['effect_rule_id']=$r['rule_id'];
					$checked[$c['card_id']]=1;
				}
			}
		}
		foreach ($cards as &$c){
			if (!empty($checked[$c['card_id']]))
				$valid_cards[$c['member_card_id']]=$c;
		}
		return array(
				'valid_cards'=>$valid_cards,
				'effects'=>$effects
		);
	}
	
	function hotel_rooms_check($inter_id, $hotel_rooms_set = array(), $hotel_ids = NULL, $offset = NULL, $nums = NULL) {
		$hotel_rooms = $this->hotel_rooms_format ( $inter_id, $hotel_ids, $offset, $nums );
		if (! empty ( $hotel_rooms_set ) && ! empty ( $hotel_rooms ['hotel_rooms'] )) {
			foreach ( $hotel_rooms ['hotel_rooms'] as $hotel_id => $hr ) {
				foreach ( $hr ['rooms'] as $room_id => $rc ) {
					foreach ( $rc ['codes'] as $price_code => $rp ) {
						if (! empty ( $hotel_rooms_set [$hotel_id] [$room_id] ) && in_array ( $price_code, $hotel_rooms_set [$hotel_id] [$room_id] )) {
							$hotel_rooms ['hotel_rooms'] [$hotel_id] ['rooms'] [$room_id] ['check'] = 1;
							$hotel_rooms ['hotel_rooms'] [$hotel_id] ['check'] = 1;
							$hotel_rooms ['hotel_rooms'] [$hotel_id] ['rooms'] [$room_id] ['codes'] [$price_code] ['check'] = 1;
						}
					}
				}
			}
		}
		return $hotel_rooms;
	}
	function hotel_rooms_format($inter_id, $hotel_ids = NULL, $offset = NULL, $nums = NULL) {
		$this->load->model ( 'hotel/Price_code_model' );
		$this->load->model ( 'hotel/Rooms_model' );
		$this->load->model ( 'hotel/Hotel_model' );

		if (! empty ( $hotel_ids )) {
			$hotel_ids = explode ( ',', $hotel_ids );
			if (isset ( $offset ))
				$hotel_ids = array_slice ( $hotel_ids, $offset, $nums );
			$hotels = $this->Hotel_model->get_hotel_by_ids ( $inter_id, implode ( ',', $hotel_ids ), NULL, 'key' );
		} else {
			$hotels = $this->Hotel_model->get_all_hotels ( $inter_id, NULL, 'key' );
			if (isset ( $offset ))
				$hotels = array_slice ( $hotels, $offset, $nums );
		}
		$sets = $this->Price_code_model->get_hotels_price_set ( $inter_id, $hotel_ids, ' price_code,room_id,hotel_id ' );
		$codes = $this->Price_code_model->get_price_codes ( $inter_id );
		$rooms = $this->Rooms_model->get_hotels_rooms ( $inter_id, $hotel_ids, ' name,room_id,hotel_id ' );
		$data = array ();
		foreach ( $hotels as $h ) {
			// $data [$h ['hotel_id']] ['hotel_id'] = $h ['hotel_id'];
			$data [$h ['hotel_id']] ['name'] = $h ['name'];
			$data [$h ['hotel_id']] ['rooms'] = array ();
			if (! empty ( $sets [$h ['hotel_id']] )) {
				foreach ( $sets [$h ['hotel_id']] as $room_id => $room ) {
					foreach ( $room as $price_code => $prices ) {
						if (! empty ( $rooms [$h ['hotel_id']] [$room_id] ['name'] )) {
							$data [$h ['hotel_id']] ['rooms'] [$room_id] ['name'] = $rooms [$h ['hotel_id']] [$room_id] ['name'];
							$data [$h ['hotel_id']] ['rooms'] [$room_id] ['codes'] [$prices ['price_code']] ['code'] = $prices ['price_code'];
							$data [$h ['hotel_id']] ['rooms'] [$room_id] ['codes'] [$prices ['price_code']] ['name'] = $codes [$prices ['price_code']] ['price_name'];
							$data [$h ['hotel_id']] ['rooms'] [$room_id] ['codes'] [$prices ['price_code']] ['check'] = 0;
						}
					}
				}
			}
		}
		$price_codes = array ();
		foreach ( $codes as $c ) {
			$price_codes [$c ['price_code']] = $c ['price_name'];
		}
		return array (
				'hotel_rooms' => $data,
				'price_codes' => $price_codes 
		);
	}
	function userule_fields_config() {
		$user_operations = array (
				'ur_check' => array (
						'<a href="',
						'key' => site_url ( 'hotel/coupons/ur_check' ),
						'" class="btn btn-info btn-xs" title="查看"><i class="fa fa-file-o"></i>查看</a> ' 
				),
				'ur_edit' => array (
						'<a href="',
						'key' => site_url ( 'hotel/coupons/ur_edit' ),
						'" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
				) 
		);
		$acl_array = $this->session->allow_actions;
		$acl_array = $acl_array [ADMINHTML];
		foreach ( $user_operations as $oper => $link ) {
			if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel'] ['coupons'] ) || ! in_array ( $oper, $acl_array ['hotel'] ['coupons'] ))) {
				unset ( $user_operations [$oper] );
			}
		}
		return array (
				'rule_id' => array (
						'label' => '规则编号' 
				),
				'rule_name' => array (
						'label' => '规则名称' 
				),
				'rule_type' => array (
						'label' => '卡券类型',
						'select' => array_column ( $this->rule_types, 'des', 'key' ) 
				),
				'status' => array (
						'label' => '状态',
						'select' => array (
								'1' => '有效',
								'2' => '无效',
								'3' => '删除' 
						) 
				),
				'create_time' => array (
						'label' => '创建时间' 
				),
				'update_time' => array (
						'label' => '最后更新时间' 
				),
				'user_operations' => array (
						'label' => '操作',
						'user_operations' => $user_operations 
				) 
		);
	}
	function userule_table_fields() {
		return array (
				'rule_id' => '',
				'rule_name' => '',
				'rule_type' => '',
				'coupon_ids' => '',
				'hotel_rooms' => '',
				'rule_dates' => '',
				'extra_rule' => '',
				'status' => 1 
		);
	}


    function get_giverule($inter_id, $rule_id, $format = TRUE, $status = NULL) {
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'rule_id', $rule_id );
        is_null ( $status ) ? $db->where_in ( $status, array (
            1,
            2
        ) ) : $db->where ( 'status', $status );
        $rule = $db->get ( 'iwide_hotel_coupon_grules' )->row_array ();
        if (! empty ( $rule ) && $format) {
            $rule ['coupon_ids'] = explode ( ',', $rule ['coupon_ids'] );
            $rule ['hotel_rooms'] = json_decode ( $rule ['hotel_rooms'], TRUE );
            $rule ['rule_dates'] = json_decode ( $rule ['rule_dates'], TRUE );
            $rule ['extra_rule'] = json_decode ( $rule ['extra_rule'], TRUE );
            if (! empty ( $rule ['rule_dates'] )) {
                $rule ['rule_dates'] ['d'] ['d'] = explode ( ',', $rule ['rule_dates'] ['d'] ['d'] );
                if (! empty ( $rule ['rule_dates'] ['d'] ['r'] ))
                    $rule ['rule_dates'] ['d'] ['r'] ['week'] = explode ( ',', $rule ['rule_dates'] ['d'] ['r'] ['week'] );
            }
        }
        return $rule;
    }
    
    function check_related_userules($inter_id,$related_card_ids,$params=array()){
    	$coupon_ids=array();
    	$room_price=array();
    	foreach ($related_card_ids as $room_id=>$r){
    		foreach ($r as $price_code=>$card_id){
    			$coupon_ids[$card_id]=$card_id;
//     			$room_price[$card_id]
    		}
    	}
    	$userules=$this->get_userules_by_couponids($inter_id, $coupon_ids);
	    foreach ($userules as $card_id=>$r){
    		foreach ($r as $price_code=>$card_id){
    			$coupon_ids[$card_id]=$card_id;
    		}
    	}
    }
}