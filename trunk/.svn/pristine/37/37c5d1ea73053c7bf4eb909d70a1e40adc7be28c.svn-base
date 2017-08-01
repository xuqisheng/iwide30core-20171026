<?php 
class Icardorder
{
	protected $CI;
	
	protected $_cardorderModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function addCardOrder($data)
	{
		try {
			$result = $this->getCardorderModel()->addCardOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getOrderDetailListByMemId($mem_id)
	{
		try {
			$result = $this->getCardorderModel()->getOrderDetailListByMemId($mem_id);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getOrderDetailList($limit=null, $offset=null)
	{
		$orderList = $this->getCardorderModel()->getOrderDetailList($limit,$offset);
		return $orderList;
	}
	
	
	public function getOrderList($limit=null, $offset=null, $select=array())
	{
		$orderList = $this->getCardorderModel()->getOrderList($limit,$offset);
		return $orderList;
	}
	
	public function getOrderListNumber($limit=null, $offset=null, $where=null)
	{
		$memberObjectList = $this->getCardorderModel()->getOrderListNumber($limit, $offset, $where, array('co_id'));
		return count($memberObjectList);
	}
	
	public function updatePayStatus($id, $paid)
	{
		try {
			$data = array(
				'co_id' => $id,
				'paid'  => $paid
			);
			$result = $this->getCardorderModel()->updateCardOrder($data);
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
				'co_id'       => $id,
				'note'        => $code,
			);
			$result = $this->getCardorderModel()->updateCardOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateCardOrder($data)
	{
		try {
			$result = $this->getCardorderModel()->updateCardOrder($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getCardOrderById($id)
	{
		try {
			$orderObject = $this->getCardorderModel()->getCardOrderById($id, 'co_id');
			return $orderObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function getCardOrderByOrderNumber($number)
	{
		try {
			$orderObject = $this->getCardorderModel()->getCardOrderById($number, 'order_number');
			return $orderObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	protected function getCardorderModel()
	{
		if(!isset($this->_cardorderModel)) {
			$this->CI->load->model('member/cardorder');
			$this->_cardorderModel = $this->CI->cardorder;
		}

		return $this->_cardorderModel;
	}
}