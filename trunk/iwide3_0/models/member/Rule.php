<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule extends CI_Model
{
	const TABLE_RULE               = 'iwide_member_promote_rule';
	const TABLE_RULE_PRODUCT       = 'iwide_member_promote_rule_product';
	protected $table_member_field  = array('rule_id','inter_id','rule_name','module','handle','reward','condition', 'is_active','activity_time_type','activity_time_begin','activity_time_end','activity_product_type','product');
	
	/**
	 * 添加新的规则
	 * @param array $data
	 * @return bool
	 */
	public function createRule($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			if($this->checkData($data,true)) {
				$writeAdapter = $this->load->database('member_write',true);
				$writeAdapter->insert(self::TABLE_RULE,$data);
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
	 * 删除规则
	 * @param int $rule_id
	 */
	public function deleteRuleById($rule_id)
	{
	    $rule_id = intval($rule_id);
	    
		$writeAdapter = $this->load->database('member_write',true);
		$writeAdapter->delete(self::TABLE_RULE, array('rule_id' => $rule_id));
	
		return $writeAdapter->affected_rows();
	}
	
	/**
	 * 根据id更新规则
	 * @param string $rule_id
	 * @param array $data
	 * @return bool
	 */
	public function updateRuleById($rule_id,$data)
	{
		try {
			if($this->checkData($data)) {
	
				$ruleObject = $this->getRuleById($rule_id,array('rule_id'));
	
				if($ruleObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['rule_id']);
					$result = $writeAdapter->update(self::TABLE_RULE, $data, array('rule_id' => $ruleObject->rule_id));
	
					return $result;
				} else {
					throw new Exception("Id为".$rule_id."的规则不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 根据Id查询规则
	 * @param string $rule_id
	 * @return unknown
	 */
	public function getRuleById($rule_id,$select=array())
	{
		$rule_id = intval($rule_id);
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		$query = $readAdapter->from(self::TABLE_RULE)->where(array('rule_id' => $rule_id))->get();
		$ruleObject = $query->row();
		
		if(isset($ruleObject->module)) {
			$ruleObject->module = unserialize($ruleObject->module);
		}
		if(isset($ruleObject->handle)) {
			$ruleObject->handle = unserialize($ruleObject->handle);
		}
	    if(isset($ruleObject->reward)) {
			$ruleObject->reward = unserialize($ruleObject->reward);
		}
		if(isset($ruleObject->condition)) {
			$ruleObject->condition = unserialize($ruleObject->condition);
		}
		if(isset($ruleObject->product)) {
			$ruleObject->product = unserialize($ruleObject->product);
		}
			
		return $ruleObject;
	}
	
	/**
	 * 符合奖励条件将获得奖品
	 * @param unknown $module
	 * @param unknown $handle
	 * @param unknown $inter_id
	 * @param unknown $params
	 * @return multitype:multitype:NULL
	 */
	public function getRewardByRules($module, $handle, $inter_id, $params)
	{
		$rules = $this->getRuleList(true,array('inter_id'=>$inter_id));
		$activeRules = array();
		foreach($rules as $rule) {
			if((isset($rule->module[0]) && $rule->module[0]!='all') && !in_array($module,$rule->module)) continue;
			if(!is_null($handle) && !empty($rule->handle) && !in_array($handle,$rule->handle)) continue;
			//过滤规则生效时间
			if($rule->activity_time_type) {
				if(strtotime($rule->activity_time_begin)>time() || strtotime($rule->activity_time_end)<time()) continue;
			}
			//过滤选定商品
			if($rule->activity_product_type==2 && (!isset($params['product']) || (!empty($rule->product) && !in_array($params['product'],$rule->product)))) continue;
			
			//过滤会员
			if(isset($rule->condition['member'])) {
				if($rule->condition['member'][0] == '-1' || (isset($params['level']) && in_array($params['level'],$rule->condition['member']))) {
					unset($rule->condition['member']);
				} else {
					continue;
				}
			}
			
			//过滤消费金额满
			if(isset($rule->condition['consume_balance_up'])) {
				if(isset($params['amount']) && $params['amount']>=$rule->condition['consume_balance_up']) {
					unset($rule->condition['consume_balance_up']);
				} else {
					continue;
				}
			}
				
			//过滤积分满
			if(isset($rule->condition['consume_bonus_up'])) {
				if(isset($params['bonus']) && $params['bonus']>=$rule->condition['consume_bonus_up']) {
					unset($rule->condition['consume_bonus_up']);
				} else {
					continue;
				}
			}
			
			//过滤订单满足几个商品
			if(isset($rule->condition['consume_product_up'])) {
				if(isset($params['product_num']) && $params['product_num']>=$rule->condition['consume_product_up']) {
					unset($rule->condition['consume_product_up']);
				} else {
					continue;
				}
			}
			
			//满足条件离店领取
			if(isset($rule->condition['hotel_checkout'])) {
				if(isset($params['hotel_checkout']) && $params['hotel_checkout']==1) {
					unset($rule->condition['hotel_checkout']);
				} else {
					continue;
				}
			}

			//满足条件注册领取
			if(isset($rule->condition['hotel_register'])) {
				if(isset($params['hotel_register']) && $params['hotel_register']==1) {
					unset($rule->condition['hotel_register']);
					unset($rule->condition['hotel']);
					unset($rule->condition['pro_category']);
				} else {
					continue;
				}
			}
			
			//满足条件线上支付
			if(isset($rule->condition['pay_online'])) {
				if(isset($params['pay_online']) && $params['pay_online']==1) {
					unset($rule->condition['pay_online']);
				} else {
					continue;
				}
			}
			
			//满足条件线下支付
			if(isset($rule->condition['pay_offline'])) {
				if(isset($params['pay_offline']) && $params['pay_offline']==1) {
					unset($rule->condition['hotel_register']);
					unset($rule->condition['hotel']);
					unset($rule->condition['pro_category']);
					unset($rule->condition['pay_offline']);
				} else {
					continue;
				}
			}
			
			//满足条件关注
			if(isset($rule->condition['focus'])) {
				if(isset($params['focus']) && $params['focus']==1) {
					unset($rule->condition['focus']);
					unset($rule->condition['hotel_register']);
					unset($rule->condition['hotel']);
					unset($rule->condition['pro_category']);
					unset($rule->condition['pay_offline']);
				} else {
					continue;
				}
			}
			
			//过滤选定的酒店
			if(isset($rule->condition['hotel'])) {
				if(isset($params['hotel']) && in_array($params['hotel'],$rule->condition['hotel'])) {
					unset($rule->condition['hotel']);
				} else {
					continue;
				}
			}
			
			//过滤房型
			if(isset($rule->condition['pro_category'])) {
				if(isset($params['category'])) {
					if(isset($rule->condition['pro_category'][$params['hotel']]) && !empty($rule->condition['pro_category'][$params['hotel']])) {
						$tmp = explode(',', $rule->condition['pro_category'][$params['hotel']]);
						if(in_array($params['category'],$tmp)) {
							unset($rule->condition['pro_category']);
						} else {
							continue;
						}
					} else {
						continue;
					}
				} else {
					continue;
				}
			}

			//过滤价格代码
			if(isset($rule->condition['price_code'])) {
				if($rule->condition['price_code'][0] == '-1' || (isset($params['price_code']) && in_array($params['price_code'],$rule->condition['price_code']))) {
					unset($rule->condition['price_code']);
				} else {
					continue;
				}
			}
			
			//满足条件消费完成
			if(isset($rule->condition['consume_completed'])) {
				if(isset($params['consume_completed']) && $params['consume_completed']==1) {
					unset($rule->condition['consume_completed']);
				} else {
					continue;
				}
			}
            
			//同一用户执行规则的数量
			unset($rule->condition['exec_num']);
			if(count($rule->condition)) continue;
			
			//返回奖励详情
			$activeRules[$rule->rule_id] = array('rule_name'=>$rule->rule_name,'reward'=>$rule->reward);
		}

		return $activeRules;
	}
	
	/**
	 * 根据模块获取规则
	 * @param unknown $module
	 * @param string $handle
	 * @param string $is_active
	 * @param unknown $select
	 * @param string $must_appid
	 * @return multitype:unknown
	 */
	public function getRuleListByModule($module, $handle=null, $is_active=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		} else {
			$readAdapter->select('rule_id,inter_id,module,handle,rule_name,reward,condition,product,is_active,sort_order');
			$readAdapter->select('activity_time_type,activity_time_begin,activity_time_end,activity_product_type');
		}
	
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		if($is_active !== null) {
			if($is_active) {
				$readAdapter->where('is_active',1);
			} else {
				$readAdapter->where('is_active',0);
			}
		}
	
		$readAdapter->order_by('sort_order', 'DESC');
		$readAdapter->order_by('rule_id', 'DESC');
	
		$query = $readAdapter->from(self::TABLE_RULE)->get();
	
		$result = array();
		foreach ($query->result() as $ruleObject)
		{
			if(isset($ruleObject->module)) {
				$ruleObject->module = unserialize($ruleObject->module);
			}
			
			if(isset($ruleObject->handle)) {
				$ruleObject->handle = unserialize($ruleObject->handle);
			}
			if(isset($ruleObject->reward)) {
				$ruleObject->reward = unserialize($ruleObject->reward);
			}
			if(isset($ruleObject->condition)) {
				$ruleObject->condition = unserialize($ruleObject->condition);
			}
			if(isset($ruleObject->product)) {
				$ruleObject->product = unserialize($ruleObject->product);
			}
			
			if(in_array($module,$ruleObject->module)) {
				if(is_null($handle) || in_array($handle,$ruleObject->handle))
			    $result[] = $ruleObject;
			}
		}
	
		return $result;
	}
	
	/**
	 * 获取规则列表
	 * @param int $is_active
	 * @param string $limit
	 * @param string $offset
	 * @param array $select
	 * @param object
	 */
	public function getRuleList($is_active=null, $where=null, $limit=null, $offset=null, $select=array(), $must_appid=true)
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
		
		if(isset($where['inter_id'])) {
			$readAdapter->where('inter_id', $where['inter_id']);
		} elseif($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
		
		if($is_active !== null) {
			if($is_active) {
				$readAdapter->where('is_active',1);
			} else {
				$readAdapter->where('is_active',0);
			}
		}

		$readAdapter->order_by('sort_order', 'DESC');
		$readAdapter->order_by('rule_id', 'DESC');

		$query = $readAdapter->from(self::TABLE_RULE)->get();
		
		$result = array();
		foreach ($query->result() as $rule)
		{
			if(isset($rule->module)) {
				$rule->module = unserialize($rule->module);
			}
			if(isset($rule->handle) && !empty($rule->handle)) {
				$rule->handle = unserialize($rule->handle);
			}
			if(isset($rule->reward)) {
				$rule->reward = unserialize($rule->reward);
			}
			if(isset($rule->condition)) {
				$rule->condition = unserialize($rule->condition);
			}
			if(isset($rule->product)) {
				$rule->product = unserialize($rule->product);
			}
			$result[] = $rule;
		}

		return $result;
	}
	
	/**
	 * 规则数量
	 * @param string $is_active
	 * @param unknown $where
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getRuleListCount($is_active=null, $where=array(), $select=array('rule_id'), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		if($is_active !== null) {
			if($is_active) {
				$readAdapter->where('is_active',1);
			} else {
				$readAdapter->where('is_active',0);
			}
		}
	
		$query = $readAdapter->from(self::TABLE_RULE)->get();
	
		return $query->result();
	}
	
	public function getProductsByRuleId($rule_id)
	{
		$readAdapter = $this->load->database('member_read',true);
		$readAdapter->select('product_id')->where('rule_id',$rule_id);
		$query = $readAdapter->from(self::TABLE_RULE_PRODUCT)->get();
	
		$ret = array();
		foreach($query->result_array() as $row) {
			$ret[] = $row['product_id'];
		}
		
		return $ret;
	}
	
	public function updateProductsByRuleId($rule_id,$product_ids)
	{
		$writeAdapter = $this->load->database('member_write',true);
		
		$old_product_ids = $this->getProductsByRuleId($rule_id);
		
		$insert_ids = array_diff($product_ids,$old_product_ids);
		$delete_ids = array_diff($old_product_ids,$product_ids);

		if(!empty($insert_ids)) {
			$insert_str = '';
			foreach($insert_ids as $id) {
				$insert_str.= "('".$rule_id."','".$id."'),";
			}
			$insert_str = substr($insert_str,0,strlen($insert_str)-1);
		    $writeAdapter->query("INSERT INTO ".self::TABLE_RULE_PRODUCT." (`rule_id`,`product_id`) VALUES ".$insert_str);
		}

		if(!empty($delete_ids)) {
			$delete_ids = implode(",",$delete_ids);
			$writeAdapter->query("DELETE FROM ".self::TABLE_RULE_PRODUCT." WHERE `product_id` in (".$delete_ids.")");
		}
		
		return true;
	}
	
	/**
	 * 检测数据是否合法
	 * @param array $data
	 * @param bool $new 是否新建数据
	 * @param unknown $date
	 *
	 * @return bool
	 */
	protected function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
	
		if(isset($data['activity_time_type'])) {
			$data['activity_time_type'] = intval($data['activity_time_type']);
		}
	
		if(isset($data['activity_product_type'])) {
			$data['activity_product_type'] = intval($data['activity_product_type']);
		}
		
		if(isset($data['reward'])) {
			$data['reward'] = serialize($data['reward']);
		}
		
		if(isset($data['module'])) {
			$data['module'] = serialize($data['module']);
		}
		
		if(isset($data['handle'])) {
			$data['handle'] = serialize($data['handle']);
		}
		
		if(isset($data['condition'])) {
			$data['condition'] = serialize($data['condition']);
		}
		
		if(isset($data['product'])) {
			$data['product'] = serialize($data['product']);
		}
		
		if(isset($data['sort_order'])) {
			$data['sort_order'] = intval($data['sort_order']);
		}
	
		if(isset($data['is_active'])) {
			if($data['is_active'] != 1) {
				$data['is_active'] = 0;
			}
		}

		if($new) {
			$data['create_time'] = date('Y-m-d H:i:s',time());
		}
	
		return true;
	}
	
	/**
	 * 过滤不需要的字段
	 * @param array
	 */
	protected function _filterData(&$data)
	{
		$toDelKeys = array_diff(array_keys($data), $this->table_member_field);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
}