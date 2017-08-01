<?php
class Member_model extends CI_Model {
	public $vid=NULL;
	const POINT_NAME='积分';
	function __construct() {
		parent::__construct ();
        $this->_server=INTER_PATH_URL2;
	}
	function get_member_levels($inter_id) {
        $this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            $levels=$this->Member_new_model->getAllMemberLevels($inter_id);
        }else{
            $this->load->model ( 'member/Imember' );
            $levels = $this->Imember->getAllMemberLevels ( $inter_id, 0 );
        }
        return $levels;
	}
	function check_openid_member($inter_id, $openid, $paras = array()) {
		$this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            $check=$this->Member_new_model->getMemberByOpenId($inter_id,$openid);

            if (isset ( $check->member_id )) {
                return $check;
            } else {
                $check=$this->Member_new_model->newMember($inter_id,$openid);
                return $check;
            }
        }else{
            $this->load->model ( 'member/Imember' );
            $check = $this->Imember->getMemberByOpenId ( $openid,$inter_id,0 );

            if (isset ( $check->mem_id )) {
                return $check;
            } else {
                if (! isset ( $paras ['create'] ) || $paras ['create'] == true) {
                    return $this->Imember->initMember ( $openid, array (), $inter_id );
                }
                return false;
            }
        }
	}
	function level_privilege($inter_id, $scene = 'room') {
		$vid=$this->get_vid($inter_id);
		if (!empty($vid)&&$vid==1){
			$data= $this->level_privilege_v($inter_id);
		}else{
			$data= $this->level_privilege_m($inter_id, $scene);
		}
		return $data;
	}
	function level_privilege_v($inter_id) {
		$this->load->model ( 'hotel/Member_new_model' );
		$data = $this->Member_new_model->getAllMemberLevels ( $inter_id,TRUE );
		$privilege = array ();
		if (! empty ( $data )) {
			foreach ( $data as $level => $d ) {
				if (! empty ( $d ['base_discount'] ) && $d ['base_discount'] > 0) {
					$privilege [$level] = $d;
					$privilege [$level] ['related_cal_way'] = 'multi';
					$privilege [$level] ['related_cal_value'] = $d ['base_discount']*0.1;
				} 
			}
		}
		return $privilege;
	}
	function level_privilege_m($inter_id, $scene = 'room') {
		switch ($scene) {
			case 'room' :
				$scene = '001';
				break;
			default :
				break;
		}
		$this->load->model ( 'member/Iconfig' );
		$data = $this->Iconfig->getPrivilegeByModule ( 'room', $inter_id );
		$privilege = array ();
		if (! empty ( $data )) {
			foreach ( $data as $k => $d ) {
				if ($k == 'room') {
					foreach ( $d as $ddk => $ddd ) {
						if (! empty ( $ddd [$scene] )) {
							if (! empty ( $ddd [$scene] ['discount'] )) {
								$privilege [$ddk] ['related_cal_way'] = 'multi';
								$privilege [$ddk] ['related_cal_value'] = $ddd [$scene] ['discount'];
							} else {
								$privilege [$ddk] ['related_cal_way'] = 'reduce';
								$privilege [$ddk] ['related_cal_value'] = $ddd [$scene] ['reduce'];
							}
						}
					}
				} else if ($k == 'all') {
					foreach ( $d as $ddk => $ddd ) {
						if (! empty ( $ddd [$scene] )) {
							if (! empty ( $ddd [$scene] ['discount'] )) {
								$privilege [$ddk] ['related_cal_way'] = 'multi';
								$privilege [$ddk] ['related_cal_value'] = $ddd [$scene] ['discount'];
							} else {
								$privilege [$ddk] ['related_cal_way'] = 'reduce';
								$privilege [$ddk] ['related_cal_value'] = $ddd [$scene] ['reduce'];
							}
						}
					}
				}
			}
		}
		return $privilege;
	}
	function get_point_consum_rate($inter_id, $level, $scene = 'room',$level_data=array(),$params=array()) {
		$vid=$this->get_vid($inter_id);
		if (!empty($vid)&&$vid==1){
			$data= $this->get_point_consum_rate_v($inter_id, $level,$params);
		}else{
			$data= $this->get_point_consum_rate_m($inter_id, $level, $scene);
		}
		return $data;
	}
	function get_point_consum_rate_v($inter_id, $level,$params=array()) {
		$point_name=self::POINT_NAME;
		if (isset($params['point_name'])){
			$point_name=$params['point_name'];
		}else if (!empty($params['check_point_name'])){
			$this->load->model ( 'hotel/Hotel_config_model' );
			$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $params['hotel_id'], array (
					'POINT_NAME'
			) );
			if (!empty($config_data['POINT_NAME'])){
				$point_name=$config_data['POINT_NAME'];
			}
		}
		$check=$this->check_part_point($inter_id,$params);
		if ($check['s']==0){
			$check['consum_rate']=0;
		}else if (!isset($check['consum_rate'])){
			$this->load->model ( 'hotel/Member_new_model' );
			$data = $this->Member_new_model->getAllMemberLevels ( $inter_id,TRUE );
			$point_consum_rate = 0;
			if (! empty ( $data[$level] ) && !empty($data[$level]['consume_bonus_size'])&&$data[$level]['consume_bonus_size']>0) {
				$point_consum_rate=number_format(1/$data[$level]['consume_bonus_size'],2,'.','');
			}
			$check['consum_rate']=$point_consum_rate;
		}
		if ($point_name!=self::POINT_NAME){
			$check['errmsg']=str_replace(self::POINT_NAME, $point_name, $check['errmsg']);
		}
		return $check;
	}
	function get_point_consum_rate_m($inter_id, $level, $scene = 'room') {
		switch ($scene) {
			case 'room' :
				$scene = '001';
				break;
			default :
				break;
		}
		$this->load->model ( 'member/Iconfig' );
		$data = $this->Iconfig->getBtoMByModule ( 'room', $inter_id );
		$point_consum_rate = 0;
		if (! empty ( $data )) {
			foreach ( $data as $d ) {
				if ($d ['module'] == 'room' && $d ['category'] == $scene) {
					if ($d ['member'] == $level) {
						$point_consum_rate = current ( $d ['bonustomoney'] ) / key ( $d ['bonustomoney'] );
						break;
					} else if ($d ['member'] == - 1) {
						$point_consum_rate = current ( $d ['bonustomoney'] ) / key ( $d ['bonustomoney'] );
					}
				} else if ($d ['module'] == 'all' && $d ['category'] == $scene) {
					if ($d ['member'] == $level) {
						$point_consum_rate = current ( $d ['bonustomoney'] ) / key ( $d ['bonustomoney'] );
						break;
					} else if ($d ['member'] == - 1) {
						$point_consum_rate = current ( $d ['bonustomoney'] ) / key ( $d ['bonustomoney'] );
					}
				}
			}
		}
		return array('s'=>1,'consum_rate'=>$point_consum_rate,'part_set'=>array());
	}
	
	function get_point_reward($inter_id, $order, $scene) {
		$vid=$this->get_vid($inter_id);
		if (!empty($vid)&&$vid==1){
			$data= $this->get_point_reward_v($inter_id, $order);
		}else{
			$data= $this->get_point_reward_m($inter_id, $order, $scene);
		}
		return $data;
	}
	
	function get_point_reward_v($inter_id, $order, $scene='room') {
		$member=$this->check_openid_member($inter_id, $order['openid']);
		$level=-1;
		if (!empty($member)){
			$level=$member->level;
			$this->load->model ( 'hotel/Member_new_model' );
			$data = $this->Member_new_model->getAllMemberLevels ( $inter_id,TRUE );
			if (! empty ( $data[$level] ) && !empty($data[$level]['bonus_size']) && $data[$level]['bonus_size']>0) {
				$give_amount=$data[$level]['bonus_size']*$order['price'];
				return array (
						'type' => 'BALANCE',
						'give_amount' => ceil ( $give_amount ),
						'give_rate' => $data[$level]['bonus_size']
				);
			}
		}
		return '';
	}
	function get_point_reward_m($inter_id, $order, $scene='room') {
		switch ($scene) {
			case 'room' :
				$scene = '001';
				break;
			default :
				break;
		}
		$this->load->library ( 'PMS_Adapter', array (
				'inter_id' => $inter_id,
				'hotel_id' => 0 
		), 'pub_pmsa' );
		$member = $this->pub_pmsa->check_openid_member ( $inter_id, $order ['openid'] );
		if ($member) {
			$give_info = $this->get_point_give_rate ( $inter_id, $member->level, $scene );
			$give_amount = 0;
			if (! empty ( $give_info ['give_rate'] )) {
				if ($give_info ['type'] == 'ORDER') {
					$give_amount = $give_info ['give_rate'];
				} else if ($give_info ['type'] == 'BALANCE') {
					$give_amount = $give_info ['give_rate'] * $order ['price'];
				}
			}
			return array (
					'type' => $give_info ['type'],
					'give_amount' => ceil ( $give_amount ),
					'give_rate' => $give_info ['give_rate'] 
			);
		}
		return 0;
	}
	function get_point_give_rate($inter_id, $level, $scene = 'room') {
		switch ($scene) {
			case 'room' :
				$scene = '001';
				break;
			default :
				break;
		}
		$this->load->model ( 'member/Iconfig' );
		$data = $this->Iconfig->getBonusruleMByModule ( 'room', $inter_id );
		$all_data = $this->Iconfig->getBonusruleMByModule ( 'all', $inter_id );
		$data = empty($data) ? $all_data: $data;
		$point_give_rate = 0;
		$give_type = '';
		if (! empty ( $data )) {
			foreach ( $data as $d ) {
				if (($d ['module'] == 'room' || $d ['module'] == 'all') && $d ['category'] == $scene) {
					if ($d ['member'] == $level) {
						if (! empty ( $d ['BALANCE'] )) {
							$point_give_rate = current ( $d ['BALANCE'] ) / key ( $d ['BALANCE'] );
							$give_type = 'BALANCE';
						} else if (! empty ( $d ['ORDER'] )) {
							$point_give_rate = current ( $d ['ORDER'] ) / key ( $d ['ORDER'] );
							$give_type = 'ORDER';
						}
						break;
					} else if ($d ['member'] == - 1) {
						if (! empty ( $d ['BALANCE'] )) {
							$point_give_rate = current ( $d ['BALANCE'] ) / key ( $d ['BALANCE'] );
							$give_type = 'BALANCE';
						} else if (! empty ( $d ['ORDER'] )) {
							$point_give_rate = current ( $d ['ORDER'] ) / key ( $d ['ORDER'] );
							$give_type = 'ORDER';
						}
					}
				}
			}
		}
		return array (
				'type' => $give_type,
				'give_rate' => $point_give_rate 
		);
	}
	function give_point($inter_id, $orderid, $openid, $amount, $note = '') {
        $this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            return $this->Member_new_model->addBonus($openid, $amount, $note, $orderid, $inter_id);
        }else{
            $this->load->model ( 'member/Imember' );
            return $this->Imember->addBonus ( $openid, $amount, $note, $orderid, $inter_id,0 );
        }
	}
	function point_back($inter_id, $openid, $orderid) {
        $this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            return $this->Member_new_model->refund($inter_id,$orderid);
        }else{
            $this->load->model ( 'member/Imember' );
            return $this->Imember->refund ( $openid, $orderid, '订房订单取消，积分返还', 'bonus', $inter_id,0 );
        }
	}
	function consum_point($inter_id, $orderid, $openid, $amount,$params=array()) {
        $this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            return $this->Member_new_model->reduceBonus($inter_id, $orderid, $openid, $amount,$params);
        }else{
            $this->load->model ( 'member/Imember' );
            return $this->Imember->reduceBonus ( $openid, $amount, '订房订单扣减积分', $orderid, $inter_id,0 );
        }
	}
    function exchange_bonus($inter_id, $openid, $amount, $orderid = '', $remark = '',$params=array()) {
        $this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            return $this->Member_new_model->exchangeBonus($inter_id, $orderid, $openid, $amount,$params);
        }
    }
	function reduce_balance($inter_id, $openid, $amount, $orderid = '', $remark = '',$params=array(),$order=array()) {
        $this->vid=$this->get_vid($inter_id);
        if(isset($this->vid) && $this->vid==1){
            $this->load->model ( 'hotel/Member_new_model' );
            $params['note'] = $remark;
            return $this->Member_new_model->reduceBalance($inter_id,$openid,$orderid,$amount,$params,$order);
        }else{
            $this->load->model ( 'member/Imember' );
            return $this->Imember->reduceBalance ( $openid, $amount, $remark, $orderid, $inter_id,0 );
        }
	}
	function room_point_exchange($inter_id, $member,$params=array()) {
		$point_config=json_decode($params['config'],TRUE);
		$result=array();
		if(!empty($point_config)){
			if($point_config['rule']=='fixed'){
				if($params['price']<=$point_config['limit']){
					$result['point_need']=$point_config['point']*$params['countday']*$params['roomnums'];
					$result['can_exchange']=$member->bonus>=$result['point_need']?1:0;
				}
			}
		}
		return $result;
	}
	function point_pay_check($inter_id,$params=array()){
//		$params['is_pms_reduce']=$this->session->userdata($inter_id.'_point_is_pms_reduce');
		if (!isset($params['is_pms_reduce'])) {
			$params['is_pms_reduce']=false;
			$this->load->model ( 'hotel/Hotel_config_model' );
			$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $params['hotel_id'], array (
					'POINT_EXCHANGE_ROOM',
					'POINT_NAME'
			) );
			if(!empty($config_data['POINT_EXCHANGE_ROOM'])){
				$code_point_set = json_decode($config_data['POINT_EXCHANGE_ROOM'], true);
				if(!empty($code_point_set['is_pms'])){
					$params['is_pms_reduce'] = true;
					$params['only_show']=true;
				}
			}
		}
		$point_name=self::POINT_NAME;
		if (isset($params['point_name'])){
			$point_name=$params['point_name'];
		}else if (!empty($params['check_point_name'])){
			if (!isset($config_data)){
				$this->load->model ( 'hotel/Hotel_config_model' );
				$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $params['hotel_id'], array (
						'POINT_NAME'
				) );
			}
			if (!empty($config_data['POINT_NAME'])){
				$point_name=$config_data['POINT_NAME'];
			}
		}
//		$this->session->set_userdata($inter_id.'_point_is_pms_reduce',$params['is_pms_reduce']);
		$room_night = get_room_night($params ['startdate'],$params ['enddate'],'round',$params);//至少有1个间夜
		isset($params['countday']) or $params['countday']=$room_night;
		if ($params['is_pms_reduce']){
			$this->load->model('hotel/Hotel_check_model');
			$adapter=$this->Hotel_check_model->get_hotel_adapter($inter_id,$params['hotel_id']);
			$servObj = $adapter->getWebServ();
			return $servObj->point_pay_check($inter_id, $params);
		}
		
		$this->load->model('hotel/Bonus_rules_model');
		$rule=$this->Bonus_rules_model->check_pay_rule($inter_id,$params);
		$params ['room_night'] = $room_night * $params ['roomnums'];
		$result=array('can_exchange'=>0,'pay_set'=>array(),'point_need'=>'','errmsg'=>'');
// 		var_dump($rule);exit;
		if (!empty($rule['rule'])){
			$result['pay_set']=$rule['rule']['extra_condition'];
			$result['pay_set']['ex_value']=$rule['rule']['ex_value'];
			if ($rule['rule']['ex_way']==2){
				$result['point_need']=$params['room_night']*$rule['rule']['ex_value'];
				$result['pay_set']['ex_way']='roomnight';
			}else {
				$result['point_need']=$rule['rule']['ex_value']*$params['total_price'];
				$result['pay_set']['ex_way']='rate';
			}
			if ($params['bonus']>=$result['point_need']){
				$result['can_exchange']=1;
			}else{
				$result['can_exchange']=0;
				$result['errmsg']='积分不足';
			}
		}else if(!empty($rule['errmsg'])){
			$result['can_exchange']=0;
			$result['errmsg']=$rule['errmsg'];
		}
		else{
			$this->load->model('pay/Pay_model');
			$para=$this->Pay_model->get_pay_paras($inter_id,'point');
			
			if (!empty($para['hotel_rate'])&&$para['hotel_rate']>0){
				$result['pay_set']=array('ex_way'=>'rate','ex_value'=>$para['hotel_rate']);
				$result['point_need']=$params['total_price']*$para['hotel_rate'];
				$result['can_exchange']=$params['bonus']>=$result['point_need']?1:0;
			}
		}
		empty($result['point_need']) or $result['point_need']=round($result['point_need']);
		if (!empty($result['can_exchange'])){
			if ($inter_id=='a429262687'){//定制，碧桂园需要拥有的积分大于所需的200才可兑换
				if ($params['bonus']-$result['point_need']<200){
					$result ['can_exchange'] = 0;
					$result ['errmsg'] = '您的积分需比订单积分多200积分才可预订！';
				}
			}
		}
		$result['des']=$result['point_need'].'/'.$params['bonus'];
		if ($point_name!=self::POINT_NAME){
			$result['des']=str_replace(self::POINT_NAME, $point_name, $result['des']);
			$result['errmsg']=str_replace(self::POINT_NAME, $point_name, $result['errmsg']);
		}
		return $result;
	}
	function get_openid_member($inter_id, $openid, $paras = array()) {
		$this->load->model ( 'hotel/Member_new_model' );
		return $this->Member_new_model->get_pms_member($inter_id, $openid,$paras);
		$this->load->model('hotel/Hotel_check_model');
		$adapter=$this->Hotel_check_model->get_hotel_adapter($inter_id,0);
		$member = $adapter->check_openid_member ( $inter_id, $openid, array (
				'update' => TRUE 
		) );
		return $member;
	}
    function get_vid($inter_id){
		if (isset($this->vid))
			return $this->vid;
		$id_create_time=str_replace('a','1',$inter_id);
		if ($id_create_time>=1468828800){
			return 1;
		}
        $this->load->model ( 'hotel/Hotel_config_model' );
        $config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', 0, 'NEW_VIP');
		if (isset($config_data['NEW_VIP'])){
			$this->vid=$config_data['NEW_VIP'];
        	return $config_data['NEW_VIP'];
		}
		$this->vid=0;
		return 0;
    }
    function set_vid($inter_id){
    	$this->load->model ( 'hotel/Hotel_config_model' );
    	$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', 0, 'NEW_VIP');
    	if (empty($config_data)){
    		if($this->db->insert('hotel_config',array('inter_id'=>$inter_id,'module'=>'HOTEL','param_name'=>'NEW_VIP','param_value'=>'1','hotel_id'=>0)))
    			return 1;
    	}else {
    		$this->db->where(array('inter_id'=>$inter_id,'module'=>'HOTEL','param_name'=>'NEW_VIP','hotel_id'=>0));
    		if($this->db->update('hotel_config',array('param_value'=>1)))
    			return 2;
    	}
    	return 0;
    }
    function price_code_tran($inter_id){
    	$sql="SELECT * FROM `iwide_hotel_price_info` 
    	       WHERE inter_id = '$inter_id' and (use_condition like '%member_level%' or use_condition like '%no_coupon%')";
    	$infos=$this->db->query($sql)->result_array();
    	$this->load->model('hotel/Member_new_model');
    	$member_level=$this->Member_new_model->getAllMemberLevels($inter_id,TRUE);
    	if (!empty($infos)){
    		foreach ($infos as $i){
    			$tmp=json_decode($i['use_condition'],TRUE);
    			$updata=array();
    			if (isset($tmp['no_coupon'])){
    				$updata['coupon_condition']=json_decode($i['coupon_condition'],TRUE);
    				$updata['coupon_condition']['no_coupon']=$tmp['no_coupon'];
    				$updata['coupon_condition']=json_encode($updata['coupon_condition']);
    			}
    			if (isset($tmp['member_level'])){
    				
    			}
    		}
    	}
    }
    function check_part_point($inter_id,$params=array()){
    	$this->load->model('hotel/Bonus_rules_model');
    	$params['no_status']=1;
    	$rule=$this->Bonus_rules_model->check_userule($inter_id,$params);
    	if ($rule===FALSE){
    	    return array('s'=>1,'part_set'=>array());
    	}
    	$set=array('s'=>0,'part_set'=>array(),'errmsg'=>'不可使用积分');
    	if (!empty($rule['rule'])){
    		$set['s']=1;
    		$set['errmsg']='';
    		$set['part_set']=$rule['rule']['extra_condition'];
    		$set['consum_rate']=$rule['rule']['ex_value'];
    	}else {
    		$this->load->model ( 'hotel/Hotel_config_model' );
    		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $params['hotel_id'], array (
    				'PART_POINT_SET'
    		) );
    		if (!empty($config_data['PART_POINT_SET'])){
	    		$part_set=json_decode($config_data['PART_POINT_SET'],TRUE);
	    		$set=array('s'=>0,'part_set'=>$part_set);
	    		if (!empty($part_set['min_haven'])&&(empty($params['bonus'])||$params['bonus']<$part_set['min_haven'])){
	    			$set['errmsg']='您需要有至少'.$part_set['min_haven'].'积分才可以使用积分兑换';
	    		}
	    		if (!empty($part_set['exp_room'])&&(!empty($params['room_id'])&&in_array($params['room_id'], $part_set['exp_room']))){
	    			$set['errmsg']='此房型不可以使用积分';
	    		}
	    		if (!empty($part_set['exp_price_code'])&&(!empty($params['price_code'])&&in_array($params['price_code'], $part_set['exp_price_code']))){
	    			$set['errmsg']='此价格不可以使用积分';
	    		}
	    		if (!empty($part_set['max_use'])&&(!empty($params['used'])&&$params['used']>$part_set['max_use'])){
	    			$set['errmsg']='您最多可使用'.$part_set['max_use'].'积分';
	    		}
	    		$set['s']=2;
	    		if (isset($part_set['consum_rate'])){
	    			$set['consum_rate']=$part_set['consum_rate'];
	    		}
    		}
    	}
    	return $set;
    }



    function check_point_giverules($inter_id,$params=array(),$type='create_order',$detail){    //订单赠送积分规则

        $this->load->model ( 'hotel/Bonus_rules_model' );
        $rules_list = $this->Bonus_rules_model->get_all_giverule($inter_id);

        $info['code']=0;

        if(!empty($rules_list)){
            foreach($rules_list as $rule){
                if($rule['status']==1){
                    if(!empty($rule['paytype'])){    //过滤支付方式
                        $paytype=json_decode($rule['paytype']);
                        if(!in_array($params['paytype'],$paytype)){
                            continue;
                        }
                    }

                    if(isset($rule['valid_time']) && $rule['valid_time']!='') {   //过滤时间
                        $valid_time=explode('-',$rule['valid_time']);

                        if(isset($valid_time[1]) && !empty($valid_time[1])){
                            if($valid_time[0]>$params['startdate'] || $params['startdate']>$valid_time[1]){
                                continue;
                            }
                        }
                    }

                    if(isset($rule['hotels_id']) && !empty($rule['hotels_id'])) {    //过滤选定的酒店、房型与价格代码

                        $hotel_id=$detail['hotel'];
                        $room_id=$params['first_detail']['room_id'];
                        $hotel_rooms=json_decode($rule['hotels_id']);

                        if(isset($hotel_rooms->{$hotel_id}->{$room_id}) && in_array($detail['price_code'],$hotel_rooms->{$hotel_id}->{$room_id})){
    //                        unset($rule->hotel_rooms);
                        } else {
                            continue;
                        }
                    }


                    switch($type){

                        case 'create_order':      //订房订单消费

                            if(isset($rule['give_rule']) && !empty($rule['give_rule'])){     //计算积分比例

                                $give_rule=json_decode($rule['give_rule']);

                                $this->load->model ( 'hotel/Member_new_model' );
                                $memberInfo=$this->Member_new_model->getMemberByOpenId($inter_id,$params['openid']);
                                if(isset($memberInfo->member_lvl_id) && !empty($memberInfo->member_lvl_id)){
                                    $level=$memberInfo->member_lvl_id;
                                }else{
                                    $level=0;
                                }

                                if(isset($give_rule->consume->all)){
                                    $give_rate=$give_rule->consume->all->amount/$give_rule->consume->all->cost;
                                    if(isset($give_rule->consume->all->give_type)){
                                        $give_type=$give_rule->consume->all->give_type;
                                    }else{
                                        $give_type='BALANCE';
                                    }
                                    $info['result']=array(
                                        'type'=>$give_type,
                                        'paytype'=>$params['paytype'],
                                        'give_amount'=>floor($give_rate*(intval($params['price']))),
                                        'give_rate'=>$give_rate,
                                        'cost'=>$give_rule->consume->all->cost,
                                        'amount'=>$give_rule->consume->all->amount,
                                        'rule_id'=>$rule['bonus_grules_id']
                                    );
                                    $info['code']=1;
                                    return $info;
                                }elseif(isset($give_rule->consume->$level)&&isset($give_rule->consume->$level->cost)&&isset($give_rule->consume->$level->amount)){
                                    $give_rate=$give_rule->consume->$level->amount/$give_rule->consume->$level->cost;
                                    if(isset($give_rule->consume->$level->give_type)){
                                        $give_type=$give_rule->consume->$level->give_type;
                                    }else{
                                        $give_type='BALANCE';
                                    }
                                    $info['result']=array(
                                        'type'=>$give_type,
                                        'paytype'=>$params['paytype'],
                                        'give_amount'=>floor($give_rate*(intval($params['price']))),
                                        'give_rate'=>$give_rate,
                                        'cost'=>$give_rule->consume->$level->cost,
                                        'amount'=>$give_rule->consume->$level->amount,
                                        'rule_id'=>$rule['bonus_grules_id']
                                    );
                                    $info['code']=1;
                                    return $info;
                                }else{
                                    continue;
                                }
                            }
                        break;

                        case 'comment_complete':    //订房点评成功送积分

$this->db->insert('weixin_text',array('content'=>'give_rule+'.json_encode($rule),'edit_date'=>date('Y-m-d H:i:s')));

                            if(isset($rule['give_rule']) && !empty($rule['give_rule'])){

                                $give_rule=json_decode($rule['give_rule']);

                                $this->load->model ( 'hotel/Member_new_model' );
                                $memberInfo=$this->Member_new_model->getMemberByOpenId($inter_id,$params['openid']);
                                if(isset($memberInfo->member_lvl_id) && !empty($memberInfo->member_lvl_id)){
                                    $level=$memberInfo->member_lvl_id;
                                }else{
                                    $level=0;
                                }

                                if(isset($give_rule->comment->all)){
                                    $info['result']=array(
                                        'paytype'=>$params['paytype'],
                                        'give_amount'=>$give_rule->comment->all->amount,
                                        'rule_id'=>$rule['bonus_grules_id']
                                    );
                                    $info['code']=1;
                                    return $info;
                                }elseif(isset($give_rule->comment->$level)&&isset($give_rule->comment->$level->amount)){
                                    $info['result']=array(
                                        'paytype'=>$params['paytype'],
                                        'give_amount'=>$give_rule->comment->$level->amount,
                                        'rule_id'=>$rule['bonus_grules_id']
                                    );
                                    $info['code']=1;
                                    return $info;
                                }else{
                                    continue;
                                }
                            }
                        break;
                    }

                }
            }
        }else{

            $data = $this->get_point_reward($inter_id,$params,'room');

            if(!empty($data)){

                $info['code']=1;
                $info['result'] = $data;

                return $info;
            }

        }

        return $info;


    }

    function check_balance_giverules($inter_id,$params=array(),$config_data){    //订单返现规则

    	if(empty($config_data)){
    		$this->load->model ( 'hotel/Hotel_config_model' );
    		$config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', $params['hotel_id'], array (
    				'BALANCE_BACK_RATE'
    		) );
    	}
    	$info['code']=0;
    	if(!empty($config_data['BALANCE_BACK_RATE'])){
    		$config = json_decode($config_data['BALANCE_BACK_RATE'],true);
    		if(isset($config['status']) && isset($config['rate']) && $config['status']==1 && $config['rate']>0 ){
	    		$give_amount = bcmul($config['rate'],(intval($params['price'])),2);
	    		$info['result']=array(
	                'give_amount'=>$give_amount,
	                'give_rate'=>$config['rate']
	            );
	            $info['code']=1;
    		}
    	}
        return $info;

    }
}