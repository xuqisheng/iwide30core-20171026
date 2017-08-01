<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 2016/9/20
 * Time: 11:32
 */
class Yasiteiw_webservice implements IPMS{
	protected $CI;
	protected $_memberModel;

	function __construct($params){
		$this->CI = &get_instance();
		$this->pms_set = $params ['pms_set'];
	}


	public function get_orders($inter_id, $status, $offset, $limit){
		// TODO: Implement get_orders() method.
	}

	public function get_hotels($inter_id, $status, $offset, $limit){
		// TODO: Implement get_hotels() method.
	}

	public function get_rooms_change($rooms, $idents = array(), $condit = array()){
		// TODO: Implement get_rooms_change() method.
		$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
		$idents ['hotel_web_id'] = $this->pms_set ['hotel_web_id'];
		$condit ['member_level'] = isset ($condit ['member_level']) ? $condit ['member_level'] : null;
		return $this->CI->pms->get_rooms_change($rooms, $idents, $condit, $this->pms_set);
	}

	public function get_new_hotel($param = array()){
		// TODO: Implement get_new_hotel() method.
		echo '';
	}

	public function order_submit($inter_id, $orderid, $params){
		$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
		return $this->CI->pms->order_to_web($inter_id, $orderid, $params, $this->pms_set);
	}

	public function add_web_bill($order, $params = array()){
		$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
		$trans_no = empty($params['third_no']) ? '' : $params['third_no'];

		return $this->CI->pms->add_web_bill($order['web_orderid'], $order, $this->pms_set, $trans_no);
	}

	function cancel_order($inter_id, $order){
		$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
		return $this->CI->pms->cancel_order_web($inter_id, $order, $this->pms_set);
	}

	function update_web_order($inter_id, $order){
		$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
		return $this->CI->pms->update_web_order($inter_id, $order, $this->pms_set);
	}

	function check_order_canpay($order){
		$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
		return $this->CI->pms->check_order_canpay($order, $this->pms_set);
	}

	public function check_openid_member($inter_id, $openid, $paras){
		$this->CI->load->model('hotel/Member_model');
		return $this->CI->Member_model->check_openid_member($inter_id, $openid, $paras);
	}
	/*	public function search_hotel_front($params){
			$this->CI->load->model('hotel/pms/Yasiteiw_hotel_model');
			return $this->CI->BeyondH_hotel_ext_model->search_hotel_front($params[0],$params[1],$this->pms_set);
		}*/
	public function continue_order_item($params){
	    $this->CI->load->model('hotel/pms/Yasiteiw_hotel_model', 'pms');
	    return $this->CI->pms->continue_stay($params[1], $this->pms_set,array(
	            'sub_id'=>$params[2]['sub_id'],
	            'webs_orderid'=>$params[2]['webs_orderid'],
	            'money'=>$params[3]['debt_amount'],
	            'third_no'=>$params[4]['third_no']
	    )
	            );
	}
}