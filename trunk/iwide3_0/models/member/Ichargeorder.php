<?php 
class Ichargeorder
{
	protected $CI;
	
	protected $_orderModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getChargeOrderByOrderNumber($number)
	{
		try {
			$orderObject = $this->getOrderModel()->getChargeOrderById($number, 'order_number');
			return $orderObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function addChargeOrder($data)
	{
		try {
			$result = $this->getOrderModel()->addChargeOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function updateChargeOrder($data)
	{
		try {
			$result = $this->getOrderModel()->updateChargeOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updatePayStatus($id, $paid)
	{
		try {
			$data = array(
				'cgo_id' => $id,
				'paid'  => $paid
			);
			$result = $this->getOrderModel()->updateChargeOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateNote($id, $code)
	{
		try {
			$data = array(
				'cgo_id'       => $id,
				'note'        => $code,
			);
			$result = $this->getOrderModel()->updateChargeOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function getChargeOrderById($id)
	{
		try {
			$orderObject = $this->getOrderModel()->getChargeOrderById($id, 'cgo_id');
			return $orderObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	protected function getOrderModel()
	{
		if(!isset($this->_orderModel)) {
			$this->CI->load->model('member/chargeorder');
			$this->_orderModel = $this->CI->chargeorder;
		}

		return $this->_orderModel;
	}
}