<?php 
class Chargeorder extends CI_Model
{
	const TABLE_CHARGE_ORDER     = 'member_charge_order';
	
	protected $fields          = array('cgo_id', 'inter_id', 'mem_id', 'amount', 'paid', 'transaction_id', 'note');
	
	public function addChargeOrder($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			if($this->checkData($data,true)) {
				$writeAdapter = $this->load->database('member_write',true);
				$writeAdapter->insert(self::TABLE_CHARGE_ORDER,$data);
				return $writeAdapter->insert_id();
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
// 	public function getOrderDetailListByMemId($mem_id, $select=array())
// 	{
// 		$readAdapter = $this->load->database('member_read',true);
			
// 		if($select) {
// 			$readAdapter->select(implode(',', $select));
// 		} else {
// 			$readAdapter->select(self::TABLE_CARD_ORDER.".*")
// 				->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','notice','description')));
// 		}
		
// 		$query = $readAdapter->from(self::TABLE_CARD_ORDER)
// 			->join(self::TABLE_CARD, self::TABLE_CARD_ORDER.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
// 			->where('mem_id', $mem_id)
// 			->order_by(self::TABLE_CARD_ORDER.".create_time", 'DESC')
// 			->get();
		
// 		return $query->result();
// 	}
	
// 	public function getOrderListNumber($limit=null, $offset=null, $where=array(), $select=array())
// 	{
// 		$readAdapter = $this->load->database('member_read',true);
	
// 		$readAdapter->from(self::TABLE_CARD_ORDER);
	
// 		if($limit !== null && $offset !== null) {
// 			$readAdapter->limit($limit,$offset);
// 		} elseif($limit !== null) {
// 			$readAdapter->limit($limit);
// 		}
	
// 	    if($select) {
// 			$readAdapter->select(implode(',',$select));
// 		}
	
// 		$query = $readAdapter->get();
// 		return $query->result();
// 	}
	
// 	public function getOrderList($limit=null, $offset=null, $select=array())
// 	{
// 		$readAdapter = $this->load->database('member_read',true);
			
// 		if($select) {
// 			$readAdapter->select(implode(',',$select));
// 		}
// 		if($limit !== null && $offset !== null) {
// 			$readAdapter->limit($limit,$offset);
// 		} elseif($limit !== null) {
// 			$readAdapter->limit($limit);
// 		}
	
// 		$query = $readAdapter->from(self::TABLE_CARD_ORDER)->get();
// 		return $query->result();
// 	}
	
// 	public function getOrderDetailList($limit=null, $offset=null, $select=array())
// 	{
// 		$readAdapter = $this->load->database('member_read',true);
			
// 		if($select) {
// 			$readAdapter->select(implode(',', $select));
// 		} else {
// 			$readAdapter->select(self::TABLE_CARD_ORDER.".*")
// 			->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','notice','description')))
// 			->select(self::getColumns(self::TABLE_ADD, array('name','telephone','identity_card')));
// 		}
		
// 		if($limit !== null && $offset !== null) {
// 			$readAdapter->limit($limit,$offset);
// 		} elseif($limit !== null) {
// 			$readAdapter->limit($limit);
// 		}
	
// 		$query = $readAdapter->from(self::TABLE_CARD_ORDER)
// 			->join(self::TABLE_CARD, self::TABLE_CARD_ORDER.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
// 			->join(self::TABLE_ADD, self::TABLE_CARD_ORDER.'.mem_id='.self::TABLE_ADD.'.mem_id', 'inner')
// 			->order_by(self::TABLE_CARD_ORDER.".create_time", 'DESC')
// 			->get();

// 		return $query->result();
// 	}
	
	public function updateChargeOrder($data)
	{
		try {
			if($this->checkData($data)) {
		
				$orderObject = $this->getChargeOrderById($data['cgo_id'], 'cgo_id', array('cgo_id'));
		
				if($orderObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['cgo_id']);
					$result = $writeAdapter->update(self::TABLE_CHARGE_ORDER, $data, array('cgo_id' => $orderObject->cgo_id));
		
					return $result;
				} else {
					throw new Exception("Id为".$data['cgo_id']."的订单不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	public function getChargeOrderById($id, $field='cgo_id', $select=array())
	{		
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		$query = $readAdapter->from(self::TABLE_CHARGE_ORDER)->where(array($field => $id))->get();
		return $query->row();
	}
	
	protected function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
	
		if($new) {
			$data['create_time']  = date('Y-m-d H:i:s',time());
			$data['order_number'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
		}
		
		if(isset($data['amount'])) {
			$data['amount'] = floatval($data['amount']);
		}
		
		if(isset($data['paid'])) {
			$data['paid'] = intval($data['paid']);
		}
		
		if(isset($data['mem_id'])) {
			$data['mem_id'] = intval($data['mem_id']);
		}
	
		return true;
	}
	
	protected function _filterData(&$data)
	{			
		$toDelKeys = array_diff(array_keys($data),$this->fields);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
	
// 	static protected function getColumns($table,$columns)
// 	{
// 		$select = array();
// 		foreach($columns as $column) {
// 			$select[] = $table.".".$column;
// 		}
// 		return implode(",",$select);
// 	}
}