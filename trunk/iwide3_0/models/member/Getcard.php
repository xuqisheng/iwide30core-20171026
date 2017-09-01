<?php 
class Getcard extends CI_Model
{
	const OPENID_LENGTH  = 28;
	const TABLE_GETCARD  = 'member_get_card_list';
	const TABLE_CARD     = 'member_card_infomation';
	const TABLE_VCARD    = 'member_vcard';
	
	protected $table_fields = array('gc_id','inter_id','ci_id','mem_id','openid','code','is_give_by_friend','friend_usr_name','old_user_card_code','outer_id','consume_source','status','module','scene','scene_id');
	
	const STATUS_DID_NOT_RECEIVE     = 0;  //未领取       微信卡券状态
	const STATUS_HAVE_RECEIVE        = 1;  //已经领取   微信卡券状态
	const STATUS_DONATE_COMPLETION   = 2;  //转赠完毕   微信卡券状态
	const STATUS_CANCEL_VERIFICATION = 3;  //核销           微信卡券状态
	const STATUS_DELETE              = 4;  //用户删除   微信卡券状态
	const STATUS_FREEZE              = 5;  //冻结           自定义状态
	const STATUS_GRANT               = 6;  //未发放       自定义状态
// 	const STATUS_WEIXIN_PACKAGE      = 7;  //微信卡包   自定义状态
	const STATUS_WEIXIN_PACKAGE      = -7;//不读取微信的卡券
	
	protected $_cardModel;
	
	/**
	 * 根据场景获取用户卡券领取记录
	 * @param unknown $openid
	 * @param unknown $module
	 * @param unknown $scene
	 * @param unknown $scene_id
	 */
	public function getCardsBySecne($openid,$module,$scene,$scene_id)
	{
		$readAdapter = $this->load->database('member_read',true);
		
		$query = $readAdapter->from(self::TABLE_GETCARD)
		    ->select("*")
		    ->where('openid', $openid)
		    ->where('module',$module)
		    ->where('scene',$scene)
		    ->where('scene_id',$scene_id)
		    ->get();
		
		return $query->result();
	}
	
	/**
	 * 根据OPENID获得用户卡券领取记录
	 * @param unknown $openid
	 * @param string $limit
	 * @param string $offset
	 * @param string $is_vcard   是否储值卡
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getCardsByOpenid($openid, $limit=null, $offset=null, $is_vcard=false, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('iwide_r1',true);

		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_GETCARD.".*")
			    ->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','brand_name','notice','description','is_active','logo_url','reduce_cost','date_info_end_timestamp')));
		}
	
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}

		if($must_appid && $appid=getAppid()) {
			$readAdapter->where(self::TABLE_GETCARD.'.inter_id', $appid);
		}
		
		$this->load->model('member/icard');
		$cardtypes = $this->icard->getCardTypeList();
		
		if($cardtypes) {
			foreach($cardtypes as $type) {
				if(!$is_vcard && $type->is_vcard) {
					$readAdapter->where(self::TABLE_CARD .'.ct_id !=', $type->ct_id);
				}
				if($is_vcard && $type->is_vcard) {
					$readAdapter->where(self::TABLE_CARD .'.ct_id =', $type->ct_id);
				}
			}
		}

		$query = $readAdapter->from(self::TABLE_GETCARD)
			->join(self::TABLE_CARD, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD.'.ci_id', 'inner')
			->where(self::TABLE_GETCARD.'.openid',$openid)
			->where('('.self::TABLE_GETCARD.'.status='.Getcard::STATUS_DID_NOT_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.self::STATUS_HAVE_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.self::STATUS_WEIXIN_PACKAGE.')')
			->order_by(self::TABLE_GETCARD.'.create_time','DESC')
			->get();

		return $query->result();
	}
	
	/**
	 * 获得用户所以储值卡
	 * @param unknown $openid
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getMyVcards($openid, $limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_GETCARD.".*")
			->select(self::getColumns(self::TABLE_VCARD, array('balance')))
			    ->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','brand_name','notice','description','is_active','logo_url')));
		}
	
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
	
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where(self::TABLE_GETCARD.'.inter_id', $appid);
		}
	
		$query = $readAdapter->from(self::TABLE_GETCARD)
			->join(self::TABLE_CARD, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD.'.ci_id', 'inner')
			->join(self::TABLE_VCARD, self::TABLE_GETCARD.'.gc_id='.self::TABLE_VCARD.'.gc_id', 'inner')
			->where(self::TABLE_GETCARD.'.openid',$openid)
			->where('('.self::TABLE_GETCARD.'.status='.Getcard::STATUS_DID_NOT_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.self::STATUS_HAVE_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.self::STATUS_WEIXIN_PACKAGE.')')
			->order_by(self::TABLE_GETCARD.'.create_time','DESC')
			->get();
	
		return $query->result();
	}
	
	/**
	 * 创建卡劵领取
	 * @param array $data
	 * @throws Exception
	 * @return boolean
	 */
	public function createGetcard($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid() && empty($data['inter_id']))  $data['inter_id'] = $appid;
			if($this->checkData($data,true)) {
				$cardObject = $this->getCardModel()->getCard($data['ci_id']);
				if($cardObject && $cardObject->is_active) {
					$result = $this->checkIsExistCode($data['openid'], $data['ci_id'], $data['code']);
					if($result) {
						throw new Exception("已经存在该卡劵编码!");
					}
					$writeAdapter = $this->load->database('member_write',true);
					$writeAdapter->insert(self::TABLE_GETCARD,$data);
					return $writeAdapter->insert_id();
				} else {
					throw new Exception("卡劵不存在！");
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
	 * 根据ID获得卡券
	 * @param unknown $gc_id
	 * @param unknown $select
	 */
	public function getCardById($gc_id, $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		}
	
		$query = $readAdapter->from(self::TABLE_GETCARD)->where('gc_id',$gc_id)->get();
		return $query->row();
	}
	
	/**
	 * 更新领取的卡劵
	 * @param string $openid
	 * @param string $code
	 * @param string $data
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateGetCard($openid,$code,$data,$where=NULL)
	{
		try {
			if(!$this->checkOpenid($openid)) {
				throw new Exception("OpenID非法!");
			}
			
			if(empty($code)) {
				throw new Exception("卡劵code值不能为空!");
			}
			
			if($this->checkData($data)) {
				$writeAdapter = $this->load->database('member_write',true);
				
				if(isset($data['openid'])) unset($data['openid']);
				if(isset($data['code']))   unset($data['code']);
				
				if($where) {
				    $where['openid'] = $openid;
				    $where['code'] = $code;
				} else {
					$where = array('openid'=>$openid,'code'=>$code);
				}

				$result = $writeAdapter->update(self::TABLE_GETCARD, $data, $where);
				return $result;
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据条件获得卡券列表
	 * @param unknown $where
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getCardListByWhere($where, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);

		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		}
		
		foreach($where as $k=>$v) {
			if($k=='code' || $k=='ci_id') {
				$readAdapter->where_in($k,$v);
			} else {
				$readAdapter->where($k,$v);
			}
		}
	
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}

		$query = $readAdapter->from(self::TABLE_GETCARD)->get();
		return $query->result();
	}
	
	/**
	 * 获得用户微信卡包有效的卡券
	 * @param unknown $openid
	 * @param unknown $inter_id
	 * @param unknown $ci_ids
	 * @return Ambigous <multitype:, NULL>
	 */
	public function getWxEfficeCardList($openid,$inter_id,$ci_ids=array())
	{
		$this->load->model('member/wxcard');
		$effect_codelist = $this->wxcard->getWxCardlist($inter_id,$openid);

		$readAdapter = $this->load->database('member_read',true);

		if(!empty($ci_ids)) {
			$readAdapter->where_in('ci_id',$ci_ids);
		}
		
		$query = $readAdapter->from(self::TABLE_CARD)->get();
		$allcards = $query->result();

		$lastcodelist = array();
		if($allcards) {
			$tmpcodelist = array();
			foreach($allcards as $card) {
				$tmp = $this->wxcard->getWxCardlist($inter_id,$openid,$card->card_id);
				$intersect = array_intersect($effect_codelist, $tmp);
				
				if(!empty($intersect)) {
				    $tmpcodelist[] = array('ci_id'=>$card->ci_id,'card_id'=>$card->card_id,'codes'=>$intersect);
				}
			}

			foreach($tmpcodelist as $codearr) {
				foreach($codearr['codes'] as $code) {
					if(isset($lastcodelist['codes']) && in_array($code,$lastcodelist['codes'])) continue;
				    $result = $this->wxcard->getWxCardStatus($inter_id,$codearr['card_id'],$code);
				    if($result) {
				    	$lastcodelist['ci_id_code'][$codearr['ci_id']][] = $code;
				    	$lastcodelist['codes'][] = $code;
				    }
				}
			}
		}

		return $lastcodelist;
	}

	/**
	 * 根据微信卡券更新本地卡券的状态
	 * @param unknown $inter_id
	 * @param unknown $openid
	 * @param unknown $ci_ids
	 */
	public function updateGetCardListByWxPackage($inter_id,$openid,$ci_ids=array())
	{
		$effect_codelist = $this->getWxEfficeCardList($openid,$inter_id,$ci_ids);

		$getcardObjectList = array();
		if($effect_codelist && isset($effect_codelist['codes'])){
			$params = array('openid'=>$openid,'code'=>array_unique($effect_codelist['codes']));
			if(!empty($ci_ids)) $params['ci_id'] = $ci_ids;
			$getcardObjectList = $this->getCardListByWhere($params);
		}

		$objectarr = array();
		if($getcardObjectList) {
			foreach($getcardObjectList as $object) {
				$objectarr[$object->ci_id.$object->code] = $object->status;
			}
		}

		$new = $update = $delete = array();
		//卡包已经不存在的卡券
		$params = array('openid'=>$openid,'status'=>self::STATUS_WEIXIN_PACKAGE);
		if(!empty($ci_ids)) $params['ci_id'] = $ci_ids;
		$getcardPackageList = $this->getCardListByWhere($params);
		if($getcardPackageList) {
			foreach($getcardPackageList as $object) {
				if(!isset($effect_codelist['ci_id_code'][$object->ci_id]) || !in_array($object->code,$effect_codelist['ci_id_code'][$object->ci_id])) {
					$delete[$object->ci_id][]=$object->code;
				}
			}
		}
			
		if(isset($effect_codelist['ci_id_code'])) {
			foreach($effect_codelist['ci_id_code'] as $ci_id=>$codes) {
				foreach($codes as $code) {
					if(isset($objectarr[$ci_id.$code])) {
						if($objectarr[$ci_id.$code] != self::STATUS_WEIXIN_PACKAGE) {
							$update[$ci_id][]=$code;
						}
					} else {
						$new[$ci_id][]=$code;
					}
				}
			}
		}

		if($new) {
			$this->load->model('member/imember');
			$meminfo = $this->imember->getMemberByOpenId($openid);
			foreach($new as $ci_id=>$codes) {
				foreach($codes as $code) {
					$data = array('inter_id'=>$inter_id,'ci_id'=>$ci_id,'openid'=>$openid,'mem_id'=>$meminfo->mem_id,'code'=>$code,'status'=>self::STATUS_WEIXIN_PACKAGE);
					$this->createGetcard($data);					
				}
			}
		}

		if($update) {
			foreach($update as $ci_id=>$codes) {
				foreach($codes as $code) {
					$this->updateGetCard($openid,$code,array('status'=>self::STATUS_WEIXIN_PACKAGE),array('ci_id'=>$ci_id));
				}	
			}
		}
			
		if($delete) {
			foreach($delete as $ci_id=>$codes) {
				foreach($codes as $code) {
					$this->updateGetCard($openid,$code,array('status'=>self::STATUS_CANCEL_VERIFICATION),array('ci_id'=>$ci_id));
				}
			}
		}
	}
	
	/**
	 * 获取领取卡劵列表
	 * @param int $limit
	 * @param int $offset
	 * @param array|string $select
	 * @return object
	 */
	public function getCardList($limit=null, $offset=null, $select=array(), $must_appid=true)
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
		
		$query = $readAdapter->from(self::TABLE_GETCARD)->get();
		return $query->result();
	}
	
	/**
	 * 根据卡券code获取卡券
	 * @param unknown $code
	 */
	public function getGcardByCode($code)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		$query = $readAdapter->from(self::TABLE_GETCARD)->where(array('code'=>$code))->get();
	
		return $query->result();
	}
	
	/**
	 * 检测特定code的卡券是否存在
	 * @param unknown $openid
	 * @param unknown $ci_id
	 * @param unknown $code
	 * @return boolean
	 */
	public function checkIsExistCode($openid, $ci_id, $code)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		$query = $readAdapter->from(self::TABLE_GETCARD)->where(array('openid'=>$openid,'ci_id'=>$ci_id,'code'=>$code))->get();
		
		$result = $query->result();
		
		if($result) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 根据Openid和code查询领取卡劵
	 * @param unknown $openid
	 * @param unknown $code
	 * @param unknown $select
	 * @param string $return_one
	 */
	public function getGcardListByOpenidCode($openid, $code, $select=array(), $get_one=false)
	{
		$readAdapter = $this->load->database('member_read',true);
		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		}
		
		$query = $readAdapter->from(self::TABLE_GETCARD)->where(array('openid'=>$openid,'code'=>$code))->get();
		
		if($get_one) {
			return $query->row();
		} else {
		    return $query->result();
		}
	}
	
	/**
	 * 有条件地获取领取卡劵列表
	 * @param unknown $val
	 * @param string $field
	 * @param int $limit
	 * @param int $offset
	 * @param array|string $select
	 * @return object
	 */
	public function getCardListByCondition($val, $field='openid', $where=array(), $limit=null, $offset=null, $select=array(),$must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
		
		if(isset($where['ci_id']) && isset($where['period']) && $where['period']) {
			$this->load->model('member/icard');
			$cards = $this->icard->getCardsByWhere(array('ci_id'=>$where['ci_id']));
			
			$ci_id = array();
			foreach($cards as $card) {
				if($card->date_info_type=='DATE_TYPE_FIX_TIME_RANGE') {
					if(time()>$card->date_info_begin_timestamp && time()<$card->date_info_end_timestamp) {
						$ci_id[] = $card->ci_id;
					}
				}
			}
			
			$where['ci_id'] = $ci_id;
		}
		
					
		if($select) {
			if(!is_array($select)) $select = array($select);
			$readAdapter->select(implode(',',$select));
		} else {
			$readAdapter->select(self::TABLE_GETCARD.".*")
			    ->select(self::getColumns(self::TABLE_CARD, array('ct_id','card_id','card_type','logo_url','code_type','brand_name')))
			    ->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','color','notice','description','sku_quantity','sku_total_quantity')))
			    ->select(self::getColumns(self::TABLE_CARD, array('date_info_type','date_info_begin_timestamp','date_info_end_timestamp','date_info_fixed_term','date_info_fixed_begin_term')))
			    ->select(self::getColumns(self::TABLE_CARD, array('use_custom_code','bind_openid','service_phone','location_id_list','source')))
			    ->select(self::getColumns(self::TABLE_CARD, array('custom_url','custom_url_name','custom_url_sub_title','promotion_url','promotion_url_name','promotion_url_sub_title')))
			    ->select(self::getColumns(self::TABLE_CARD, array('get_limit','can_share','can_give_friend','deal_detail','least_cost','reduce_cost','discount','gift')))
			    ->select(self::getColumns(self::TABLE_CARD, array('default_detail','note','status as card_status','is_active')));
		}
		
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		$readAdapter->from(self::TABLE_GETCARD)
		    ->join(self::TABLE_CARD, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD.'.ci_id', 'inner')
		    ->where($field,$val)
			->where('('.self::TABLE_GETCARD.'.status='.self::STATUS_HAVE_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.self::STATUS_WEIXIN_PACKAGE.')');
		
		if(isset($where['inter_id'])) {
			$readAdapter->where(self::TABLE_GETCARD.'.inter_id', $where['inter_id']);
		} elseif($must_appid && $appid=getAppid()) {
			$readAdapter->where(self::TABLE_GETCARD.'.inter_id', $appid);
		}

		if(isset($where['ci_id']) && !empty($where['ci_id'])) $readAdapter->where_in(self::TABLE_GETCARD.'.ci_id', $where['ci_id']);

		$query = $readAdapter->get();
		return $query->result();
	}

	/**
	 * 检测状态正确与否
	 * @param int $old_status
	 * @param int $new_status
	 * @return boolean
	 */
	public function checkStatus($old_status, $new_status)
	{		
// 		if($old_status==self::STATUS_DONATE_COMPLETION)   return false;
// 		if($old_status==self::STATUS_CANCEL_VERIFICATION) return false;
// 		if($old_status==self::STATUS_DELETE)              return false;
		
		if($old_status==self::STATUS_GRANT) {
			if($new_status==self::STATUS_HAVE_RECEIVE || $new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_DID_NOT_RECEIVE) {
			if($new_status==self::STATUS_HAVE_RECEIVE || $new_status==self::STATUS_CANCEL_VERIFICATION || $new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_WEIXIN_PACKAGE) {
			if($new_status==self::STATUS_DONATE_COMPLETION || $new_status==self::STATUS_CANCEL_VERIFICATION || $new_status==self::STATUS_DELETE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_HAVE_RECEIVE) {
			if($new_status==self::STATUS_DONATE_COMPLETION || $new_status==self::STATUS_CANCEL_VERIFICATION || $new_status==self::STATUS_DELETE || $new_status==self::STATUS_FREEZE || $new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_FREEZE) {
			if($new_status==self::STATUS_CANCEL_VERIFICATION || self::STATUS_HAVE_RECEIVE || $new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_DELETE) {
			if($new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_CANCEL_VERIFICATION) {
			if($new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		if($old_status==self::STATUS_DONATE_COMPLETION) {
			if($new_status==self::STATUS_WEIXIN_PACKAGE) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 卡券code生成算法
	 * @return Ambigous <string, number>
	 */
	public function getRandCode()
	{
		$code = '';
		for($i=0;$i<12;$i++) {
			$code .= mt_rand(0, 9);
		}
		return $code;
	}
	
	protected function getCardModel()
	{
		if(!isset($this->_cardModel)) {
			$this->load->model('member/card');
			$this->_cardModel = $this->card;
		}
		
		return $this->_cardModel;
	}
	
	/**
	 * 检测openid是否合法
	 * @param unknown $openid
	 * @return boolean
	 */
	protected function checkOpenid($openid) {
		if(empty($openid)) {
			return false;
		}
	
		if(strlen($openid) != self::OPENID_LENGTH) {
			return false;
		}
	
		return true;
	}
	
	/**
	 * 检测输入数据
	 * @param unknown $data
	 * @param string $new
	 * @throws Exception
	 * @return boolean
	 */
	protected function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
		
		if(isset($data['openid']) && !$this->checkOpenid($data['openid'])) {
			throw new Exception("OpenID非法!");
		}
	
// 		if(isset($data['code']) && !preg_match("/^[a-zA-Z0-9]*$/i",$data['code'])) {
// 			throw new Exception("CODE字段含有非法字符!");
// 		}
		
		if(isset($data['ci_id']))    $data['ci_id']    = intval($data['ci_id']);
		if(isset($data['mem_id']))   $data['mem_id']   = intval($data['mem_id']);
		if(isset($data['outer_id'])) $data['outer_id'] = intval($data['outer_id']);
		
		
		if(isset($data['status']))   {
			$data['status']   = intval($data['status']);
			if(!in_array($data['status'],array(self::STATUS_DID_NOT_RECEIVE,self::STATUS_HAVE_RECEIVE,self::STATUS_DONATE_COMPLETION,self::STATUS_CANCEL_VERIFICATION,self::STATUS_DELETE,self::STATUS_FREEZE,self::STATUS_GRANT,self::STATUS_WEIXIN_PACKAGE))) {
				throw new Exception("status状态不对!");
			}
		}
		
		if(isset($data['is_give_by_friend'])) {
			if($data['is_give_by_friend'] != 1) {
				$data['is_give_by_friend'] = 0;
			}
		}
	
		if($new) {
			$data['create_time'] = date('Y-m-d H:i:s',time());
		}
	
		return true;
	}
	
	/**
	 * 数据字段前加表名
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
	 * 过滤不是数据库字段的数据
	 * @param unknown $data
	 */
	protected function _filterData(&$data)
	{
		$toDelKeys = array_diff(array_keys($data), $this->table_fields);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
}