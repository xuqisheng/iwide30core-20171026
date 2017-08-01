<?php 
class Consume extends MY_Model
{
	const TABLE_CONSUMPTION     = 'member_consumption_record';
	const TABLE_MEMBER_INFO     = 'member_additional';
	const TABLE_MEMBER          = 'member';
	
	const TYPE_BALANCE_CHARGE   = 1;
	const TYPE_BALANCE_CONSUME  = 2;
	const TYPE_INTEGRAL_CHARGE  = 3;
	const TYPE_INTEGRAL_CONSUME = 4;
	
	const TYPE_REFUND_BALANCE_CHANGE    = 5;
	const TYPE_REFUND_BALANCE_CONSUME   = 6;
	const TYPE_REFUND_INTEGRAL_CHARGE   = 7;
	const TYPE_REFUND_INTEGRAL_CONSUME  = 8;
	
	const INTEGRAL_GETBY_BALANCE = 'BALANCE';
	const INTEGRAL_GETBY_ORDER   = 'ORDER';
	
	protected $table_field    = array('type', 'inter_id', 'order_id', 'mem_id','balance','bonus','on_offline','note');
	
	protected $memberModel;
	
	/**
	 * 根据会员主键获取消费记录
	 * @param unknown $memid
	 * @param unknown $where
	 */
	public function getAllRecordsByMemId($memid, $where=array())
	{
		$readAdapter = $this->load->database('member_read',true);
		$readAdapter->from(self::TABLE_CONSUMPTION);
	
		
		$readAdapter->where('mem_id', $memid);

		if(isset($where['inter_id'])) {
			$readAdapter->where('inter_id', $where['inter_id']);
		}
		
		if(isset($where['order_id'])) {
			$readAdapter->where('order_id', $where['order_id']);
		}
		
		if(isset($where['type'])) {
			if($where['type']=='balance') {
				$readAdapter->where(' (type='.self::TYPE_BALANCE_CHARGE.' or type='.self::TYPE_BALANCE_CONSUME.')');
			} elseif($where['type']=='bonus') {
				$readAdapter->where(' (type='.self::TYPE_INTEGRAL_CHARGE.' or type='.self::TYPE_INTEGRAL_CONSUME.')');
			} else {
				$readAdapter->where('(type='.self::TYPE_BALANCE_CHARGE.' or type='.self::TYPE_BALANCE_CONSUME.' or type='.self::TYPE_INTEGRAL_CHARGE.' or type='.self::TYPE_INTEGRAL_CONSUME.')');
			}
		}
		
		$query = $readAdapter->get();
		return $query->result();
	}
	
	/**
	 * 获取所有会员详细信息列表
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 */
	public function getRecordBywhere($limit=null, $offset=null, $where=array(), $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		$readAdapter->from(self::TABLE_CONSUMPTION)
		    ->join(self::TABLE_MEMBER_INFO, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
		    ->join(self::TABLE_MEMBER, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER.'.mem_id', 'inner');
	
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
	
		if($select) {
			if(isset($select[0])) {
				$readAdapter->select(self::getColumns(self::TABLE_CONSUMPTION, $select[0]));
			}
			if(isset($select[1])) {
				$readAdapter->select(self::getColumns(self::TABLE_MEMBER_INFO, $select[1]));
			}
		} else {
			$readAdapter->select(self::TABLE_CONSUMPTION.".*")
				->select(self::getColumns(self::TABLE_MEMBER_INFO, array('ma_id','membership_number','name','sex','dob','telephone','qq','email','identity_card','address')))
				->select(self::getColumns(self::TABLE_MEMBER_INFO, array('custom1','custom2','custom3')))
			    ->select(self::getColumns(self::TABLE_MEMBER, array('level','balance as remain_balance','bonus as remain_bonus','is_active')));
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where(self::TABLE_CONSUMPTION.'.inter_id', $appid);
		}
	
		if($where) {
			if(isset($where['name'])) {
				$readAdapter->like(self::TABLE_MEMBER_INFO.'.name', $where['name']);
			}
				
			if(isset($where['level'])) {
				$readAdapter->where(self::TABLE_MEMBER.'.level',$where['level']);
			}
			
			if(isset($where['type'])) {
				$readAdapter->where(self::TABLE_CONSUMPTION.'.type',$where['type']);
			}
		}

		$query = $readAdapter->get();
		return $query->result();
	}
	
	/**
	 * 获取用户消费记录
	 * @param unknown $memid
	 * @param unknown $where
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getRecordsByMemId($memid, $where=array(), $limit=null, $offset=null, $select=array(),$must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		$readAdapter->from(self::TABLE_CONSUMPTION);
	
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
		
		$readAdapter->where('mem_id', $memid);
		
		if($where && isset($where['type'])) {				
			if(is_array($where['type'])) {
			    $readAdapter->where("(type=".$where['type'][0]." OR type=".$where['type'][1].")");
			} else {
				$readAdapter->where('type',$where['type']);
			}
		}

		$query = $readAdapter->get();
		return $query->result();
	}
	
	/**
	 * 添加新记录
	 * @param array $data
	 * @return bool
	 */
	public function createRecord($data, $must_appid=true)
	{
		try {
			$openid = $data['openid'];
			
			if(!isset($data['inter_id']) && $must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			
			if($this->checkData($data)) {
				$memberObject = $this->getMemberModel()->getMemberByOpenId($openid);

				if($memberObject && isset($memberObject->mem_id)) {
					$data['mem_id'] = $memberObject->mem_id;
					$writeAdapter = $this->load->database('member_write',true);
					return $writeAdapter->insert(self::TABLE_CONSUMPTION,$data);
				} else {
					throw new Exception("OpenID为".$memberObject->openid."的会员不存在！");
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更加OpenId查询消费记录
	 * @param unknown $openid
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @throws Exception
	 */
	public function getRecordsByOpenId($openid, $limit=null, $offset=null, $select=array())
	{
		try {
			$memberObject = $this->getMemberModel()->getMemberByOpenId($openid);
			
			if($memberObject && isset($memberObject->mem_id)) {
				$readAdapter = $this->load->database('member_read',true);
				
				if($select) {
					$readAdapter->select(implode(',',$select));
				}
				if($limit !== null && $offset !== null) {
					$readAdapter->limit($limit,$offset);
				} elseif($limit !== null) {
					$readAdapter->limit($limit);
				}
				
				$readAdapter->where('mem_id',$memberObject->mem_id);
			} else {
				throw new Exception("OpenID为".$openid."的会员不存在！");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
			
		$query = $readAdapter->from(self::TABLE_CONSUMPTION)->get();
		return $query->result();
	}
	
	static public function toArray()
	{
		return array(
			self::TYPE_BALANCE_CHARGE         => "金额充值",
			self::TYPE_BALANCE_CONSUME        => "金额消费",
			self::TYPE_INTEGRAL_CHARGE        => "积分充值",
			self::TYPE_INTEGRAL_CONSUME       => "积分消费",
			self::TYPE_REFUND_BALANCE_CHANGE  => '退回金额',
			self::TYPE_REFUND_BALANCE_CONSUME => '扣除金额',
			self::TYPE_REFUND_INTEGRAL_CHARGE => '退回积分',
			self::TYPE_REFUND_INTEGRAL_CONSUME=> '扣除积分',
		);
	}
	
	protected function getMemberModel()
	{
		if(!isset($this->memberModel)) {
		    $this->load->model('member/imember');
		    $this->memberModel = $this->imember;
		}
		
		return $this->memberModel;
	}
	
	/**
	 * 检测数据是否合法
	 * @param array $data
	 * @param bool $new 是否新建数据
	 * @param unknown $date
	 *
	 * @return bool
	 */
	protected function checkData(&$data)
	{
		$this->_filterData($data);
	
		if(!isset($data['type'])) {
			throw new Exception("记录类型TYPE不存在!");
		} else {
			$types = self::toArray();
			if(!isset($types[$data['type']])) {
				throw new Exception("记录类型TYPE错误!");
			}
		}
	
		if(isset($data['balance'])) {
			$data['balance'] = floatval($data['balance']);
		}
	
		if(isset($data['bonus'])) {
			$data['bonus'] = intval($data['bonus']);
		}
	
		if(isset($data['on_offline'])) {
			if($data['on_offline'] != 1) {
				$data['on_offline'] = 0;
			}
		}
		
		if(isset($data['note'])) {
			$data['note'] = htmlspecialchars($data['note']);
		}
	
		return true;
	}
	
	/**
	 * 给字段添加表前缀
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
	
	/**
	 * 过滤不需要的字段
	 * @param array
	 */
	protected function _filterData(&$data)
	{
		$toDelKeys = array_diff(array_keys($data), $this->table_field);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
}