<?php
class Bonus_rules_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_USE_RULES = 'iwide_hotel_bonus_urules';
	const TAB_GIVE_RULES = 'iwide_hotel_bonus_grules';
	const TAB_HOTEL_ADMIN_LOG = 'iwide_hotel_admin_log';
	function _load_db() {
		return $this->db;
	}
	function get_userule_list($inter_id, $rule_type = NULL, $status = NULL, $nums = NULL, $offset = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->order_by ( 'rule_id desc' );
		$db->where ( 'inter_id', $inter_id );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $nums ) ?  : $db->limit ( intval ( $nums ), intval ( $offset ) );
		is_null ( $rule_type ) ?  : $db->where ( 'rule_type', $rule_type );
		return $db->get ( self::TAB_USE_RULES )->result_array ();
	}
	function get_userule($inter_id, $rule_id, $rule_type = NULL, $format = TRUE, $status = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( array (
				'inter_id' => $inter_id,
				'rule_id' => $rule_id 
		) );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $rule_type ) ?  : $db->where ( 'rule_type', $rule_type );
		$rule = $db->get ( self::TAB_USE_RULES )->row_array ();
		if (! empty ( $rule ) && $format) {
			$rule ['extra_condition'] = json_decode ( $rule ['extra_condition'], TRUE );
			$rule ['hotel_rooms'] = json_decode ( $rule ['hotel_rooms'], TRUE );
		}
		return $rule;
	}
	function update_userule($inter_id, $rule_id, $updata) {
		$db = $this->_load_db ();
		$check = $this->get_userule ( $inter_id, $rule_id, NULL, FALSE );
		if (empty ( $check )) {
			return FALSE;
		}
		$db->where ( array (
				'inter_id' => $inter_id,
				'rule_id' => $rule_id 
		) );
		$result = $db->update ( self::TAB_USE_RULES, $updata );
		if ($result && $db->affected_rows () > 0) {
			$db->where ( array (
					'inter_id' => $inter_id,
					'rule_id' => $rule_id 
			) );
			$result = $db->update ( self::TAB_USE_RULES, array (
					'update_time' => date ( 'Y-m-d H:i:s' ) 
			) );
			
			$update_diff = array ();
			foreach ( $check as $k => $c ) {
				if (isset ( $updata [$k] ) && $check [$k] != $updata [$k]) {
					$update_diff [$k] = array (
							'old' => $c,
							'new' => $updata [$k] 
					);
				}
			}
			$this->load->model ( 'hotel/Hotel_log_model' );
			$this->Hotel_log_model->add_admin_log ( 'hotel_bonus_urules#' . $rule_id, 'save', $update_diff );
		}
		return $result;
	}
	function add_userule($inter_id, $data) {
		$db = $this->_load_db ();
		$data ['inter_id'] = $inter_id;
		$data ['create_time'] = date ( 'Y-m-d H:i:s' );
		$result = $db->insert ( self::TAB_USE_RULES, $data );
		if ($result) {
			$rule_id = $db->insert_id ();
			$this->load->model ( 'hotel/Hotel_log_model' );
			unset ( $data ['inter_id'] );
			unset ( $data ['create_time'] );
			$this->Hotel_log_model->add_admin_log ( 'hotel_bonus_urules#' . $rule_id, 'add', $data );
		}
		return $result;
	}
	function check_userule($inter_id, $params) {
		$condits = array (
				'type' => 2,
				'time' => time () 
		);
		$condits['no_status']=empty($params['no_status'])?0:1;
		$rules = $this->get_userules_by_params ( $inter_id, $condits );
		
		if (!empty($params['no_status'])){
		    if (empty($rules)){
		        return FALSE;
		    }else{
		        foreach ($rules as $k=>$r){
		            if ($r['status']!=1){
		                unset($rules[$k]);
		            }
		        }
		    }
		}
		
		$checked = array ();
		$effects = array ();
		$rule = array ();
		$room_night = get_room_night($params ['startdate'],$params ['enddate'],'round',$params);//至少有1个间夜
		$params ['room_night'] = $room_night * $params ['roomnums'];
// 		var_dump($params);exit;
		$errmsg = '不可使用积分';
		foreach ( $rules as $r ) {
			// 适用门店
			if (! empty ( $r ['hotel_rooms'] )) {
				if (empty ( $r ['hotel_rooms'] [$params ['hotel_id']] [$params ['room_id']] ) || ! in_array ( $params ['price_code'], $r ['hotel_rooms'] [$params ['hotel_id']] [$params ['room_id']] )) {
					$errmsg = '此房型或价格不能使用积分';
					continue;
				}
			}
			// 其他规则
			if (! empty ( $r ['extra_condition'] )) {
				if (! empty ( $r ['extra_condition'] ['level'] ) && ! in_array ( $params ['member_level'], $r ['extra_condition'] ['level'] )) {
					$errmsg = '您不能在此订单使用积分';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['paytype'] ) && ! in_array ( $params ['paytype'], $r ['extra_condition'] ['paytype'] )) {
					$errmsg = '该支付方式不能使用积分';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['min_price'] ) && ! empty ( $params ['total_price'] ) && $params ['total_price'] < $r ['extra_condition'] ['min_price']) {
					$errmsg = '订单价格最低要' . $r ['extra_condition'] ['min_price'] . '才可使用积分';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['min_rn'] ) && ! empty ( $params ['room_night'] ) && $params ['room_night'] < $r ['extra_condition'] ['min_rn']) {
					$errmsg = '订单最低要' . $r ['extra_condition'] ['min_rn'] . '间夜才可使用积分';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['min_haven'] ) && ( empty ( $params ['bonus'] ) || $params ['bonus'] < $r ['extra_condition'] ['min_haven'])) {
					$errmsg = '您要有至少' . $r ['extra_condition'] ['min_haven'] . '积分才可使用';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['max_use'] ) && ! empty ( $params ['used'] ) && $params ['used'] > $r ['extra_condition'] ['max_use']) {
					$errmsg = '您最多可用' . $r ['extra_condition'] ['max_use'] . '积分';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['use_rate'] ) && ! empty ( $params ['used'] ) && $params ['used'] % $r ['extra_condition'] ['use_rate'] != 0) {
					$errmsg = '使用的积分数需为' . $r ['extra_condition'] ['use_rate'] . '的整数倍';
					continue;
				}
			}
			$checked [$r ['priority']] [] = $r ['rule_id'];
		}
		if (! empty ( $checked )) {
			$max_priority = max ( array_keys ( $checked ) );
			$rule_id = max ( $checked [$max_priority] );
			$rule = $rules [$rule_id];
		}
		return array (
				'rule' => $rule,
				'errmsg' => $errmsg 
		);
	}
	function check_pay_rule($inter_id, $params) {
		$condits = array (
				'type' => 1,
				'time' => time () 
		);
		$rules = $this->get_userules_by_params ( $inter_id, $condits );
		$checked = array ();
		$effects = array ();
		$rule = array ();
		$room_night = get_room_night($params ['startdate'],$params ['enddate'],'round',$params);//至少有1个间夜
		$params ['room_night'] = $room_night * $params ['roomnums'];
		// var_dump($params);
		$errmsg = '不可使用积分';
		foreach ( $rules as $r ) {
			if (empty($r['ex_value'])||$r['ex_value']<0){
			    continue;
			}
			// 适用门店
			if (! empty ( $r ['hotel_rooms'] )) {
				if (empty ( $r ['hotel_rooms'] [$params ['hotel_id']] [$params ['room_id']] ) || ! in_array ( $params ['price_code'], $r ['hotel_rooms'] [$params ['hotel_id']] [$params ['room_id']] )) {
					$errmsg = '此房型或价格不能使用积分';
					continue;
				}
			}
			// 其他规则
			if (! empty ( $r ['extra_condition'] )) {
				if (! empty ( $r ['extra_condition'] ['min_price'] ) && ! empty ( $params ['total_price'] ) && $params ['total_price'] < $r ['extra_condition'] ['min_price']) {
					$errmsg = '订单价格最低要' . $r ['extra_condition'] ['min_price'] . '才可使用积分';
					continue;
				}
				if (! empty ( $r ['extra_condition'] ['min_haven'] ) && ! empty ( $params ['bonus'] ) && $params ['bonus'] < $r ['extra_condition'] ['min_haven']) {
					$errmsg = '您要有至少' . $r ['extra_condition'] ['min_haven'] . '积分才可使用';
					continue;
				}
			}
			$checked [$r ['priority']] [] = $r ['rule_id'];
		}
		if (! empty ( $checked )) {
			$max_priority = max ( array_keys ( $checked ) );
			$rule_id = max ( $checked [$max_priority] );
			$rule = $rules [$rule_id];
			$errmsg='';
		}
		return array (
				'rule' => $rule,
				'errmsg' => $errmsg 
		);
	}
	function get_userules_by_params($inter_id, $condits, $format = TRUE) {
		$db = $this->load->database('iwide_r1',true);
		$sql = ' SELECT * FROM ' . self::TAB_USE_RULES . ' WHERE inter_id = ? and rule_type = ? ';
		$sql.=empty($condits['no_status'])?' and status = 1 ':' and status in (1,2) ';
		$sql .= ' and ( start_time <= ? or start_time = 0 ) and ( end_time >= ? or end_time = 0 ) ';
		$param = array (
				$inter_id,
				$condits ['type'],
				$condits ['time'],
				$condits ['time'] 
		);
		$result = $db->query ( $sql, $param )->result_array ();
		if (! empty ( $result ) && $format) {
			foreach ( $result as &$rule ) {
				$rule ['hotel_rooms'] = json_decode ( $rule ['hotel_rooms'], TRUE );
				$rule ['extra_condition'] = json_decode ( $rule ['extra_condition'], TRUE );
			}
		}
		$rules = array ();
		foreach ( $result as $r ) {
			$rules [$r ['rule_id']] = $r;
		}
		return $rules;
	}
	function userule_fields_config() {
		$user_operations = array (
				'ur_check' => array (
						'key' => array (
								'type' => 1,
								'link' => '<a href="' . site_url ( 'hotel/bonus/ur_check' ) 
						),
						'" class="btn btn-info btn-xs" title="查看"><i class="fa fa-file-o"></i>查看</a> ' 
				),
				'ur_edit' => array (
						'key' => array (
								'type' => 1,
								'link' => '<a href="' . site_url ( 'hotel/bonus/ur_edit' ) 
						),
						'" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
				),
				'pur_check' => array (
						'key' => array (
								'type' => 2,
								'link' => '<a href="' . site_url ( 'hotel/bonus/pur_check' ) 
						),
						'" class="btn btn-info btn-xs" title="查看"><i class="fa fa-file-o"></i>查看</a> ' 
				),
				'pur_edit' => array (
						'key' => array (
								'type' => 2,
								'link' => '<a href="' . site_url ( 'hotel/bonus/pur_edit' ) 
						),
						'" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
				) 
		);
		$acl_array = $this->session->allow_actions;
		$acl_array = $acl_array [ADMINHTML];
		foreach ( $user_operations as $oper => $link ) {
			if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel'] ['bonus'] ) || ! in_array ( $oper, $acl_array ['hotel'] ['bonus'] ))) {
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
						'label' => '规则类型',
						'select' => $this->enums ( 'urule_type' ) 
				),
				'priority' => array (
						'label' => '优先级' 
				),
				'status' => array (
						'label' => '状态',
						'select' => $this->enums ( 'status' ) 
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
				'rule_type' => 1,
				'ex_way' => 1,
				'ex_value' => '',
				'status' => 1,
				'priority' => '' 
		);
	}
	function enums($type) {
		switch ($type) {
			case 'status' :
				return array (
						'1' => '有效',
						'2' => '无效',
						'3' => '删除' 
				);
				break;
			case 'urule_type' :
				return array (
						'1' => '全额兑换',
						'2' => '部分兑换' 
				);
				break;
			case 'purule_para' :
				return array (
						'min_price', // 价格满多少可用
						'min_rn', // 满多少间夜可用
						'min_haven', // 有多少积分才可用
						'max_use', // 最多可用积分
						'use_rate' 
				) // 抵扣基数
;
				break;
			default :
				break;
		}
	}
	function get_rule_priorities($inter_id, $rule_type, $rule_id = NULL, $check = FALSE, $priority = NULL) {
		$rules = $this->get_userule_list ( $inter_id, $rule_type );
		$priorities = array ();
		if (! empty ( $rules )) {
			$priorities = array_column ( $rules, 'priority', 'rule_id' );
		}
		if (! empty ( $rule_id ) && isset ( $priorities [$rule_id] )) {
			unset ( $priorities [$rule_id] );
		}
		if ($check == TRUE) {
			if (isset ( $priority ) && in_array ( $priority, $priorities ))
				return FALSE;
			else
				return TRUE;
		}
		return $priorities;
	}
	function get_giverule_list($inter_id, $status = NULL, $nums = NULL, $offset = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->order_by ( 'bonus_grules_id desc' );
		$db->where ( 'inter_id', $inter_id );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $nums ) ?  : $db->limit ( intval ( $nums ), intval ( $offset ) );
		return $db->get ( self::TAB_GIVE_RULES )->result_array ();
	}
	function get_giverule($inter_id, $rule_id, $format = TRUE, $status = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( array (
				'inter_id' => $inter_id,
				'bonus_grules_id' => $rule_id 
		) );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		$rule = $db->get ( self::TAB_GIVE_RULES )->row_array ();
		if (! empty ( $rule ) && $format) {
			if (! empty ( $rule ['hotels_id'] )) {
				$hotels_id = json_decode ( $rule ['hotels_id'] );
				unset ( $rule ['hotels_id'] );
				foreach ( $hotels_id as $hotel_id => $room ) {
					foreach ( $room as $room_id => $price_code ) {
						$rule ['hotels_id'] [$hotel_id] [$room_id] = $price_code;
					}
				}
			}
		}
		return $rule;
	}
	function add_giverule($inter_id, $data) { // 增加赠送规则
		$db = $this->_load_db ();
		$data ['inter_id'] = $inter_id;
		$data ['create_time'] = date ( 'Y-m-d H:i:s' );
		$data ['update_time'] = date ( 'Y-m-d H:i:s' );
		
		$result = $db->insert ( 'iwide_hotel_bonus_grules', $data );
		
		if ($result) {
			$rule_id = $db->insert_id ();
			$this->load->model ( 'hotel/Hotel_log_model' );
			unset ( $data ['inter_id'] );
			unset ( $data ['create_time'] );
			$this->Hotel_log_model->add_admin_log ( 'hotel_bonus_grules#' . $rule_id, 'add', $data );
		}
		
		return $result;
	}
	function update_giverule($inter_id, $rule_id, $updata) { // 修改规则
		$db = $this->_load_db ();
		$this->load->model ( 'hotel/Bonus_rules_model' );
		$check = $this->Bonus_rules_model->get_giverule ( $inter_id, $rule_id, FALSE );
		if (empty ( $check )) {
			return FALSE;
		}
		$db->where ( array (
				'inter_id' => $inter_id,
				'bonus_grules_id' => $rule_id 
		) );
		$result = $db->update ( 'iwide_hotel_bonus_grules', $updata );
		if ($result && $db->affected_rows () > 0) {
			$db->where ( array (
					'inter_id' => $inter_id,
					'bonus_grules_id' => $rule_id 
			) );
			$result = $db->update ( 'iwide_hotel_bonus_grules', array (
					'update_time' => date ( 'Y-m-d H:i:s' ) 
			) );
			
			$update_diff = array ();
			foreach ( $check as $k => $c ) {
				if (isset ( $updata [$k] ) && $check [$k] != $updata [$k]) {
					$update_diff [$k] = array (
							'old' => $c,
							'new' => $updata [$k] 
					);
				}
			}
			$this->load->model ( 'hotel/Hotel_log_model' );
			$this->Hotel_log_model->add_admin_log ( 'hotel_bonus_grules#' . $rule_id, 'edit', $update_diff );
		}
		return $result;
	}
	function check_grules_priority($inter_id, $priority, $rule_id = NULL, $status = 1) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( array (
				'inter_id' => $inter_id,
				'priority' => $priority,
				'status' => $status,
				'bonus_grules_id <>' => $rule_id 
		) );
		return $db->get ( self::TAB_GIVE_RULES )->row_array ();
	}
	function get_all_giverule($inter_id, $status = NULL, $nums = NULL, $offset = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->order_by ( 'priority desc' );
		$db->where ( 'inter_id', $inter_id );
		is_null ( $status ) ? $db->where_in ( $status, array (
				1,
				2 
		) ) : $db->where ( 'status', $status );
		is_null ( $nums ) ?  : $db->limit ( intval ( $nums ), intval ( $offset ) );
		return $db->get ( self::TAB_GIVE_RULES )->result_array ();
	}
	function get_rule_logs($inter_id, $ident, $nums = NULL, $offset = NULL) {
		$db = $this->load->database('iwide_r1',true);
		$db->where ( array (
				'inter_id' => $inter_id,
				'ident' => $ident 
		) );
		is_null ( $nums ) ?  : $db->limit ( intval ( $nums ), intval ( $offset ) );
		return $db->get ( self::TAB_HOTEL_ADMIN_LOG )->result_array ();
	}
	function get_giverule_priorities($inter_id, $rule_type, $rule_id = NULL, $check = FALSE, $priority = NULL) {
		$rules = $this->get_all_giverule ( $inter_id, $rule_type );
		$priorities = array ();
		if (! empty ( $rules )) {
			$priorities = array_column ( $rules, 'priority', 'bonus_grules_id' );
		}
		if (! empty ( $rule_id ) && isset ( $priorities [$rule_id] )) {
			unset ( $priorities [$rule_id] );
		}
		if ($check == TRUE) {
			if (isset ( $priority ) && in_array ( $priority, $priorities ))
				return FALSE;
			else
				return TRUE;
		}
		return $priorities;
	}

    function allRules(){
        $db = $this->load->database('iwide_r1',true);
        return $db->get ( self::TAB_GIVE_RULES )->result_array ();
    }

    function update_rules($rule_id,$data){
        $db = $this->_load_db ();
        return $db->update_string( self::TAB_GIVE_RULES,array('give_rule'=>$data),array('bonus_grules_id'=>$rule_id));
    }

}