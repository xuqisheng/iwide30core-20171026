<?php 
class Card extends CI_Model
{
	const OPENID_LENGTH            = 28;
	const TABLE_CARD               = 'member_card_infomation';
	const TABLE_CARD_TYPE          = 'member_card_type';
	
	protected $card_type           = array(
		'GROUPON'            => "团购券",
		'CASH'               => "代金劵",
		'DISCOUNT'           => "折扣劵",
		'GIFT'               => "礼品劵",
	    'GENERAL_COUPON'     => "优惠卷"
	);
	
	protected $code_type            = array(
		'CODE_TYPE_TEXT'          =>  "文本",
		'CODE_TYPE_BARCODE'       =>  "一维码",
		'CODE_TYPE_QRCODE'        =>  "二维码",
		'CODE_TYPE_ONLY_QRCODE'   =>  "二维码无code显示",
		'CODE_TYPE_ONLY_BARCODE'  =>  "一维码无code显示"
	);
	
	protected $fields;
	
	public function getCardTypes()
	{
		return $this->card_type;
	}
	
	public function getCodeTypes()
	{
		return $this->code_type;
	}
	
	/**
	 * 添加新的卡劵类型
	 * @param unknown $name
	 * @param unknown $card_type
	 * @param unknown $is_vcard
	 * @param unknown $is_package
	 * @param string $inter_id
	 * @param string $must_appid
	 * @throws Exception
	 */
	public function addCardType($name, $card_type, $is_vcard, $is_package,$inter_id=null,$must_appid=true)
	{
	    $name = htmlspecialchars($name);
	    
	    if(!isset($this->card_type[$card_type])) {
	    	throw new Exception("卡劵类型错误!");
	    }
	    
	    $is_package = intval($is_package);
	    $is_vcard = intval($is_vcard);
	    $data = array('type_name'=>$name, 'card_type'=>$card_type, 'is_vcard'=>$is_vcard, 'is_package'=>$is_package);
	    
	    if($inter_id) {
			$data['inter_id'] = $inter_id;
		} elseif($must_appid && $appid=getAppid()) {
			$data['inter_id'] = $appid;
		}
	    
	    $writeAdapter = $this->load->database('member_write',true);
	    return $writeAdapter->insert(self::TABLE_CARD_TYPE, $data);
	}
	
	/**
	 * 根据条件获取卡券
	 * @param unknown $where
	 * @param string $select
	 * @param string $must_appid
	 */
	public function getCardsByWhere($where,$select=null,$must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);

		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
		
		foreach($where as $k=>$v) {
			if($k=='ci_id') {
				$readAdapter->where_in($k,$v);
			} else {
				$readAdapter->where($k,$v);
			}
		}

		$query = $readAdapter->from(self::TABLE_CARD)->get();
		return $query->result();
	}
	
	/**
	 * 更新卡劵种类名称
	 * @param int $ct_id
	 * @param string $name
	 * @throws Exception
	 * @return unknown
	 */
	public function updateCardType($ct_id, $name, $card_type, $is_vcard, $is_package)
	{
		$name = htmlspecialchars($name);
		
		if(!isset($this->card_type[$card_type])) {
			throw new Exception("卡劵类型错误!");
		}
		 
		$is_package = intval($is_package);
		$is_vcard = intval($is_vcard);
		
		$cardtypeObject = $this->getCardTypeById($ct_id);
		
		if($cardtypeObject) {
			$writeAdapter = $this->load->database('member_write',true);
			$result = $writeAdapter->update(
				self::TABLE_CARD_TYPE,
				array('type_name'=>$name, 'card_type'=>$card_type, 'is_vcard'=>$is_vcard, 'is_package'=>$is_package),
				array('ct_id' => $cardtypeObject->ct_id)
			);
		
			return $result;
		} else {
			throw new Exception("ID为".$ct_id."的卡劵种类不存在!");
		}
	}
	
	/**
	 * 根据ID取得卡劵种类
	 * @param unknown $ct_id
	 */
	public function getCardTypeById($ct_id,$inter_id=null)
	{
		$ct_id = intval($ct_id);
		
		$where=array('ct_id'=>$ct_id);
		if($inter_id) {
			$where['inter_id'] = $inter_id;
		}
		
		$readAdapter = $this->load->database('member_read',true);
		return $readAdapter->get_where(self::TABLE_CARD_TYPE,$where)->row();
	}
	
	/**
	 * 获取卡劵类型列表
	 * @param string $limit
	 * @param string $offset
	 */
	public function getCardTypeList($limit=null, $offset=null, $where=null, $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
		
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}

		if($where) {
			foreach($where as $k=>$v) {
				$readAdapter->where($k,$v);
			}
		}

		$query = $readAdapter->from(self::TABLE_CARD_TYPE)->get();
		return $query->result();
	}
	
	/**
	 * 删除卡劵
	 * @param string $ci_id
	 * @return bool|number
	 */
	public function deleteCardType($ct_id)
	{
		$cardObject = $this->getCardTypeById($ct_id);
	
		if(!$cardObject) {
			throw new Exception("ID为".$ct_id."的卡劵类型不存在!");
		}
	
		$writeAdapter = $this->load->database('member_write',true);
		$writeAdapter->delete(self::TABLE_CARD_TYPE, array('ct_id' => $ct_id));
	
		return $writeAdapter->affected_rows();
	}
	
	/**
	 * 添加新的卡劵
	 * @param array $data
	 * @throws Exception
	 * @return boolean
	 */
	public function createCard($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			
			if($this->checkData($data,true)) {
				$writeAdapter = $this->load->database('member_write',true);
				return $writeAdapter->insert(self::TABLE_CARD,$data);
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 删除卡劵
	 * @param string $ci_id
	 * @return bool|number
	 */
	public function deleteCard($ci_id)
	{
		$cardObject = $this->getCard($ci_id);
		
		if($cardObject) {
			if($cardObject->card_id) {
				throw new Exception("该卡劵不能删除");
			}
		} else {
			throw new Exception("ID为".$ci_id."的卡劵不存在!");
		}
		
		$writeAdapter = $this->load->database('member_write',true);
		$writeAdapter->delete(self::TABLE_CARD, array('ci_id' => $ci_id));
	
		return $writeAdapter->affected_rows();
	}
	
	/**
	 * 根据主键更新卡券信息
	 * @param int $ci_id
	 * @param array $data
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateCard($ci_id,$data)
	{
		$ci_id = intval($ci_id);
		
		try {
			if($this->checkData($data)) {
				$cardObject = $this->getCard($ci_id);
		        
				if($cardObject) {
					$writeAdapter = $this->load->database('member_write',true);
					
					$result = $writeAdapter->update(self::TABLE_CARD, $data, array('ci_id' => $cardObject->ci_id));
		
					return $result;
				} else {
					throw new Exception("ID为".$ci_id."的Card不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据主键减少卡券库存
	 * @param unknown $ci_id
	 * @param unknown $num
	 * @throws Exception
	 * @return boolean
	 */
	public function reduceInventory($ci_id,$num)
	{
		$ci_id = intval($ci_id);
		$num   = intval($num);

		try {
			$cardObject = $this->getCard($ci_id);

			if($cardObject) {
				$writeAdapter = $this->load->database('member_write',true);
				$writeAdapter->query("UPDATE ".$writeAdapter->dbprefix(self::TABLE_CARD)." SET `sku_quantity`=`sku_quantity`-".$num." WHERE `ci_id`=".$ci_id);
				return $writeAdapter->affected_rows();
			} else {
				throw new Exception("ID为".$ci_id."的Card不存在!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 根据ID获取卡券
	 * @param unknown $val
	 * @param string $field
	 * @param unknown $select
	 */
	public function getCard($val, $field='ci_id', $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
				
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		$query = $readAdapter->from(self::TABLE_CARD)->where(array($field => $val))->get();
		return $query->row();
	}
	
	/**
	 * 根据分类获取卡券
	 * @param string $select
	 * @param string $inter_id
	 * @param string $must_appid
	 * @return Ambigous <multitype:, unknown>
	 */
	public function getCardGroupByType($select=null,$inter_id=null, $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		}
		
		if($inter_id) {
			$readAdapter->where('inter_id', $inter_id);
		} elseif($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		$query = $readAdapter->from(self::TABLE_CARD)->get();
		
		$ret = array();
		foreach($query->result() as $row) {
			$ret[$row->ct_id][] = $row;
		}
		
		return $ret;
	}
	
	/**
	 * 获取Card列表
	 * @param string $vcard
	 * @param string $is_active
	 * @param string $where
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getCardList($vcard=null, $is_active=null, $where=null, $limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
		
		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		}
		
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($where && isset($where['inter_id'])) {
			$readAdapter->where('inter_id',$where['inter_id']);
		} elseif($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
		
		if(!is_null($is_active)) {
			$readAdapter->where('is_active', $is_active);
		}
		
		if(!is_null($vcard)) {
			$cardtypes = $this->getCardTypeList();
			if($cardtypes) {
				foreach($cardtypes as $type) {
					if($vcard && $type->is_vcard) {
						$readAdapter->where(self::TABLE_CARD .'.ct_id =', $type->ct_id);
					}
					if(!$vcard && $type->is_vcard) {
						$readAdapter->where(self::TABLE_CARD .'.ct_id !=', $type->ct_id);
					}
				}
			}
		}

		$query = $readAdapter->from(self::TABLE_CARD)->get();
		return $query->result();
	}
	
	/**
	 * 获取卡券的数量
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getCardListCount($limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		if($select) {
			if(!is_array($select)) $select = array($select);
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
	
		$query = $readAdapter->from(self::TABLE_CARD)->get();
		return $query->result();
	}
	
	/**
	 * 检测数据是否合法
	 * @param array $data
	 * @param bool $new 是否新建数据
	 *
	 * @return bool
	 */
	protected function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
	
		if($new) {
			$data['create_time']  = date('Y-m-d H:i:s',time());
			//sku_total_quantity初始库存
			//sku_quantity现有库存
			$data['sku_quantity'] = intval($data['sku_total_quantity']);
		}
		
		if(isset($data['is_active'])) {
			if($data['is_active'] != 1) {
				$data['is_active'] = 0;
			}
		}
		
		if(isset($data['least_cost']) && empty($data['least_cost'])) {
			$data['least_cost'] = 0;
		}
		
		if(isset($data['reduce_cost']) && empty($data['reduce_cost'])) {
			$data['reduce_cost'] = 0;
		}
		
		if(isset($data['discount']) && empty($data['discount'])) {
			$data['discount'] = 0;
		}
		
		if(isset($data['get_limit']) && empty($data['get_limit'])) {
			$data['get_limit'] = 50;
		}
		
		if(isset($data['date_info_begin_timestamp'])) $data['date_info_begin_timestamp'] = strtotime($data['date_info_begin_timestamp']);
		if(isset($data['date_info_end_timestamp']))   $data['date_info_end_timestamp']   = strtotime($data['date_info_end_timestamp']);
	
		return true;
	}
	
    /**
	 * 过滤不需要的字段
	 * @param array
	 * @param string type member|info
	 */
	protected function _filterData(&$data)
	{			
		$toDelKeys = array_diff(array_keys($data),$this->getTableFields());
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
	
	/**
	 * 获取表字段
	 * @return Ambigous <multitype:, unknown>
	 */
	protected function getTableFields()
	{
		$ignoreFields = array('ci_id'=>1,'create_time'=>1,'update_time'=>1);
		
		if(!isset($this->fields)) {
			$readAdapter = $this->load->database('member_read',true);
			$fields = $readAdapter->list_fields(self::TABLE_CARD);
			
			$this->fields = array();
			foreach ($fields as $field)
			{
				if(isset($ignoreFields[$field])) continue;
				$this->fields[] = $field;
			}
		}

		return $this->fields;
	}
}