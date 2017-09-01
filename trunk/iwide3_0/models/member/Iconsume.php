<?php 
class Iconsume
{
	protected $CI;
	
	protected $_consumeModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->getConsumeModel();
	}
	
	public function getAllRecordsByMemId($memid, $where=array())
	{		
		$recordObjectList = $this->getConsumeModel()->getAllRecordsByMemId($memid, $where);
		return $recordObjectList;
	}
	
	public function createRecord($data)
	{	
		try {
			$result = $this->getConsumeModel()->createRecord($data);
			return $result;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 积分消费记录
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $where
	 */
	public function getBonusConsumeRecord($limit=null, $offset=null, $where=array())
	{
		$where['type'] = Consume::TYPE_INTEGRAL_CONSUME;
		
		$recordObjectList = $this->getConsumeModel()->getRecordBywhere($limit, $offset, $where);
		return $recordObjectList;
	}
	
	public function getBonusByMember($memid,$type='all')
	{
		if($type=='all') {
			$where['type'][] = Consume::TYPE_INTEGRAL_CHARGE;
			$where['type'][] = Consume::TYPE_INTEGRAL_CONSUME;
		} elseif($type=='charge') {
			$where['type'] = Consume::TYPE_INTEGRAL_CHARGE;
		} else {
			$where['type'] = Consume::TYPE_INTEGRAL_CONSUME;
		}
		
	
		$recordObjectList = $this->getConsumeModel()->getRecordsByMemId($memid, $where);
		return $recordObjectList;
	}
	
	public function getBalancesByMember($memid,$type='all')
	{
		if($type=='all') {
			$where['type'][] = Consume::TYPE_BALANCE_CHARGE;
			$where['type'][] = Consume::TYPE_BALANCE_CONSUME;
		} elseif($type=='charge') {
			$where['type'] = Consume::TYPE_BALANCE_CHARGE;
		} else {
			$where['type'] = Consume::TYPE_BALANCE_CONSUME;
		}
	
	
		$recordObjectList = $this->getConsumeModel()->getRecordsByMemId($memid, $where);
		return $recordObjectList;
	}
	
	/**
	 * 积分消费记录总数
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $where
	 * @return number
	 */
	public function getBonusConsumeRecordCount($limit=null, $offset=null, $where=array())
	{
		$where['type'] = Consume::TYPE_INTEGRAL_CONSUME;
	
		$recordObjectList = $this->getConsumeModel()->getRecordBywhere($limit, $offset, $where, array(array('mem_id'),array('mem_id')));
		return count($recordObjectList);
	}
	
	public function getBalanceChargeRecord($limit=null, $offset=null, $where=array())
	{
		$where['type'] = Consume::TYPE_BALANCE_CHARGE;
	
		$recordObjectList = $this->getConsumeModel()->getRecordBywhere($limit, $offset, $where);
		return $recordObjectList;
	}
	
	public function getBalanceChargeRecordCount($limit=null, $offset=null, $where=array())
	{
		$where['type'] = Consume::TYPE_BALANCE_CHARGE;
	
		$recordObjectList = $this->getConsumeModel()->getRecordBywhere($limit, $offset, $where, array(array('mem_id'),array('mem_id')));
		return count($recordObjectList);
	}
	
	public function getRecordBywhereNumber($limit=null, $offset=null, $where=null)
	{
		$recordObjectList = $this->getConsumeModel()->getRecordBywhere($limit, $offset, $where, array(array('mem_id'),array('mem_id')));
		return count($recordObjectList);
	}
	
	/**
	 * 根据Openid获取记录
	 * @param unknown $openid
	 * @param string $limit
	 * @param string $offset
	 * @return unknown|boolean
	 */
	public function getRecordsByOpenId($openid, $limit=null, $offset=null)
	{
		try {
			$records = $this->getConsumeModel()->getRecordsByOpenId($openid, $limit, $offset);
			return $records;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 充值金额
	 * @param string $openid   OpenId
	 * @param number $balance  金额
	 * @param string $note     备注
	 * @param number $on_offline  1->线上|0->线下
	 * @return unknown|boolean
	 */
	public function charge($openid, $balance,$note='',$order_id='', $inter_id='',$on_offline=1)
	{
		$data = array(
			'openid'      => $openid,
			'balance'     => $balance,
			'note'        => $note,
			'on_offline'  => $on_offline,
			'order_id'    => $order_id,
			'inter_id'    => $inter_id,
			'type'        => Consume::TYPE_BALANCE_CHARGE
		);
		
		if($inter_id) $data['inter_id'] = $inter_id;

		try {
			$result = $this->getConsumeModel()->createRecord($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 消费金额
	 * @param unknown $openid     Openid
	 * @param unknown $balance    金额
	 * @param string $note        备注
	 * @param number $on_offline  1->线上|0->线下
	 * @return unknown|boolean
	 */
	public function consume($openid, $balance, $note='', $order_id='', $inter_id='', $on_offline=1)
	{
		$data = array(
			'openid'      => $openid,
			'balance'     => $balance,
			'note'        => $note,
			'on_offline'  => $on_offline,
			'order_id'    => $order_id,
			'inter_id'    => $inter_id,
			'type'        => Consume::TYPE_BALANCE_CONSUME
		);
		
		if($inter_id) $data['inter_id'] = $inter_id;
		
		try {
			$result = $this->getConsumeModel()->createRecord($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 增加积分
	 * @param string $openid
	 * @param number $bonus
	 * @param string $note
	 * @param number $on_offline
	 * @return unknown|boolean
	 */
	public function addBonus($openid, $bonus, $note='', $order_id='', $inter_id='', $on_offline=1)
	{
		$data = array(
			'openid'      => $openid,
			'bonus'       => $bonus,
			'note'        => $note,
			'on_offline'  => $on_offline,
			'order_id'    => $order_id,
			'inter_id'    => $inter_id,
			'type'        => Consume::TYPE_INTEGRAL_CHARGE
		);
		
		try {
			$result = $this->getConsumeModel()->createRecord($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 扣除积分
	 * @param string $openid
	 * @param number $bonus
	 * @param string $note
	 * @param number $on_offline
	 * @return unknown|boolean
	 */
	public function reduceBonus($openid, $bonus, $note='', $order_id='', $inter_id='', $on_offline=1)
	{
		$data = array(
			'openid'      => $openid,
			'bonus'       => $bonus,
			'note'        => $note,
			'on_offline'  => $on_offline,
			'order_id'    => $order_id,
			'inter_id'    => $inter_id,
			'type'        => Consume::TYPE_INTEGRAL_CONSUME
		);
		
		try {
			$result = $this->getConsumeModel()->createRecord($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	protected function getConsumeModel()
	{
		if(!isset($this->_consumeModel)) {
			$this->CI->load->model('member/consume');
			$this->_consumeModel = $this->CI->consume;
		}
	
		return $this->_consumeModel;
	}
}