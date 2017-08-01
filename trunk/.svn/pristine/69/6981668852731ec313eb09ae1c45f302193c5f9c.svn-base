<?php

class Jinjiang_webservice implements IPMS{
	protected $CI;
	protected $_memberModel;
	protected $_usr;
	protected $_url;
	protected $_pwd;
	protected $pms_set;

	function __construct($params){
		$this->CI = &get_instance();
		$this->pms_set = $params ['pms_set'];
		$pms_param = json_decode($this->pms_set['pms_auth'], TRUE);
		$this->_usr = $pms_param['user'];
		$this->_url = $pms_param['url'];
		$this->_pwd = $pms_param['pwd'];
	}

	public function get_orders($inter_id, $status, $offset, $limit){
		// TODO: Implement get_orders() method.
	}

	public function get_hotels($inter_id, $status, $offset, $limit){
		// TODO: Implement get_hotels() method.
	}

	public function get_rooms_change($rooms, $idents = array(), $condit = array()){
		// TODO: Implement get_rooms_change() method.
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ($condit ['member_level']) ? $condit ['member_level'] : NULL;
		return $this->CI->pms->get_rooms_change($rooms, $idents, $condit, $this->pms_set);
	}

	public function get_new_hotel($param = array()){
		// TODO: Implement get_new_hotel() method.
		echo '';
	}

	public function order_submit($inter_id, $orderid, $params){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		return $this->CI->pms->order_to_web($inter_id, $orderid, $params, $this->pms_set);
	}

	public function add_web_bill($order, $params = array()){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		$trans_no = empty($params['trans_no']) ? '' : $params['trans_no'];
		return $this->CI->pms->add_web_bill($order['web_orderid'], $order, $this->pms_set, $trans_no);
	}

	function cancel_order($inter_id, $order){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		return $this->CI->pms->cancel_order_web($inter_id, $order, $this->pms_set);
	}

	function update_web_order($inter_id, $order){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		return $this->CI->pms->update_web_order($inter_id, $order, $this->pms_set);
	}

	function check_order_canpay($order){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		return $this->CI->pms->check_order_canpay($order, $this->pms_set);
	}

	public function check_openid_member($inter_id, $openid, $paras){
		$this->CI->load->model('hotel/Member_model');
		return $this->CI->Member_model->check_openid_member($inter_id, $openid, $paras);
	}

	public function get_order_state($params){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		$status_des = empty($params[1]) ? array() : $params[1];
		return $this->CI->pms->get_order_state($params[0], $this->pms_set, $status_des);
	}
	/*	public function search_hotel_front($params){
			$this->CI->load->model('hotel/pms/Jinjiang_hotel_model');
			return $this->CI->Jinjiang_hotel_ext_model->search_hotel_front($params[0],$params[1],$this->pms_set);
		}*/

	/**
	 * 取酒店附加信息
	 * @param unknown $parms
	 */
	public function get_hotel_extra_info($params){
		$hotel_id = $this->pms_set['hotel_id'];
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		return $this->CI->pms->get_hotel_extra_info($hotel_id, $this->pms_set);
	}

	//卡券使用规则
	public function getRulesByParams($params){

		$openid = $params[0];
		$inter_id = $params[2];
		$params = $params[3];

		return $this->myCoupons($openid, $params);
	}


	function myCoupons($openid, $params){
		$this->CI->load->model('hotel/pms/Jinjiang_hotel_model', 'pms');
		return $this->CI->pms->get_useable_coupon($openid, $this->pms_set, $params);

		$this->CI->load->model('hotel/Coupon_new_model', 'Coupon_new_model');
		$data = $this->CI->Coupon_new_model->myCoupons($openid, $inter_id, $params, 1);

		$coupons = array();

		if(!empty($data) && !empty($data['data'])){

			foreach($data['data'] as $key => $res){

				$start = str_replace('.', '', $res['pms_card_info']['useStartDate']);
				$end = str_replace('.', '', $res['pms_card_info']['useEndDate']);
				$leftNum = intval($res['pms_card_info']['count']) - intval($res['pms_card_info']['used']);

				if($params['startdate'] >= $start && $params['startdate'] <= $end && $leftNum > 0 && $res['pms_card_info']['amount'] == 10){

					$result['code'] = $res['pms_card_info']['couponId'];
					$result['title'] = $res['pms_card_info']['ruleName'];
					$result['brand_name'] = $res['brand_name'];
					$result['ci_id'] = $res['pms_card_info']['couponId'];
					$result['card_id'] = $res['pms_card_info']['couponId'];
					$result['is_wxcard'] = 1;
					$result['restriction']['room_nights'] = 1;
					$result['restriction'] ['order'] = 1;
					$result['extra'] = '';
					$result['coupon_type'] = 'voucher';
					$result['pms_coupon_type'] = $res['pms_card_info']['ruleType'];
					$result['reduce_cost'] = $res['pms_card_info']['amount'];
					$result['date_info_end_timestamp'] = $res['expire_time'];
					if($res['pms_card_info']['leftNum'] != 0){
						$result['status'] = 1;
					} else{
						$result['status'] = 2;
					}
					$arr = (Object)$result;
					$coupons[$key] = $arr;
				}
			}
		}
		return $coupons;
	}

	public function updateGcardStatus($params){

		return TRUE;

	}


}