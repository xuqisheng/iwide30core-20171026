<?php 
class Cardorder extends CI_Model
{
	const TABLE_CARD_ORDER     = 'member_card_order';
	const TABLE_CARD           = 'member_card_infomation';
	const TABLE_ADD            = 'iwide_member_bgy_vcard_additional_info';
	
	protected $fields          = array('co_id', 'inter_id', 'mem_id', 'ci_id', 'unit_price', 'num', 'amount', 'paid', 'transaction_id', 'saler', 'note');
	
	/**
	 * 添加卡券订单
	 * @param unknown $data
	 * @param string $must_appid
	 * @throws Exception
	 * @return boolean
	 */
	public function addCardOrder($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			
			if($this->checkData($data,true)) {
				$writeAdapter = $this->load->database('member_write',true);
				$writeAdapter->insert(self::TABLE_CARD_ORDER,$data);
				return $writeAdapter->insert_id();
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据会员主键获取订单详情
	 * @param unknown $mem_id
	 * @param unknown $select
	 */
	public function getOrderDetailListByMemId($mem_id, $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_CARD_ORDER.".*")
				->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','notice','description')));
		}
		
		$query = $readAdapter->from(self::TABLE_CARD_ORDER)
			->join(self::TABLE_CARD, self::TABLE_CARD_ORDER.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
			->where('mem_id', $mem_id)
			->order_by(self::TABLE_CARD_ORDER.".create_time", 'DESC')
			->get();
		
		return $query->result();
	}
	
	/**
	 * 获取订单数量
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $where
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getOrderListNumber($limit=null, $offset=null, $where=array(), $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		$readAdapter->from(self::TABLE_CARD_ORDER);
	
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
	
	    if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		$query = $readAdapter->get();
		return $query->result();
	}
	
	/**
	 * 获取订单列表
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getOrderList($limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		$query = $readAdapter->from(self::TABLE_CARD_ORDER)->get();
		return $query->result();
	}
	
	/**
	 * 获取订单详情列表
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getOrderDetailList($limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_CARD_ORDER.".*")
			->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','notice','description')))
			->select(self::getColumns(self::TABLE_ADD, array('name','telephone','identity_card')));
		}
		
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where(self::TABLE_CARD_ORDER.".inter_id", $appid);
		}
	
		$query = $readAdapter->from(self::TABLE_CARD_ORDER)
			->join(self::TABLE_CARD, self::TABLE_CARD_ORDER.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
			->join(self::TABLE_ADD, self::TABLE_CARD_ORDER.'.mem_id='.self::TABLE_ADD.'.mem_id', 'inner')
			->order_by(self::TABLE_CARD_ORDER.".create_time", 'DESC')
			->get();

		return $query->result();
	}
	
	/**
	 * 更新订单数据
	 * @param unknown $data
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateCardOrder($data)
	{
		try {
			if($this->checkData($data)) {
		
				$orderObject = $this->getCardOrderById($data['co_id'], 'co_id', array('co_id'));
		
				if($orderObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['co_id']);
					$result = $writeAdapter->update(self::TABLE_CARD_ORDER, $data, array('co_id' => $orderObject->co_id));
		
					return $result;
				} else {
					throw new Exception("Id为".$data['co_id']."的订单不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据ID获取订单
	 * @param unknown $id
	 * @param string $field
	 * @param unknown $select
	 */
	public function getCardOrderById($id, $field='co_id', $select=array())
	{		
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		$query = $readAdapter->from(self::TABLE_CARD_ORDER)->where(array($field => $id))->get();
		return $query->row();
	}
	
	/**
	 * 检测数据
	 * @param unknown $data
	 * @param string $new
	 * @return boolean
	 */
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
		
		if(isset($data['unit_price'])) {
			$data['unit_price'] = floatval($data['unit_price']);
		}
		
		if(isset($data['num'])) {
			$data['num'] = intval($data['num']);
		}
		
		if(isset($data['paid'])) {
			$data['paid'] = intval($data['paid']);
		}
		
	    if(isset($data['ci_id'])) {
			$data['ci_id'] = intval($data['ci_id']);
		}
		
		if(isset($data['mem_id'])) {
			$data['mem_id'] = intval($data['mem_id']);
		}
		
		if(isset($data['saler']) && empty($data['saler'])) {
			$data['saler'] = 0;
		}
	
		return true;
	}
	
	/**
	 * 过滤数据
	 * @param unknown $data
	 */
	protected function _filterData(&$data)
	{			
		$toDelKeys = array_diff(array_keys($data),$this->fields);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
	
	/**
	 * 为选择列添加表前缀
	 * @param unknown $table
	 * @param unknown $columns
	 * @return string
	 */
	static protected function getColumns($table,$columns)
	{
		$select = array();
		foreach($columns as $column) {
			$select[] = $table.".".$column;
		}
		return implode(",",$select);
	}
}