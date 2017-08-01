<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cardrule extends CI_Model
{
	const TABLE_CARD_RULE               = 'iwide_member_card_rule';
	const TABLE_CARD_RULE_PRODUCT       = 'iwide_member_card_rule_product';
	const TABLE_GETCARD                 = 'member_get_card_list';
	protected $table_member_field       = array('cr_id', 'inter_id', 'module' ,'name','ci_id','condition','product_type','product','is_active');
	
	
	/**
	 * 根据以下条件获取可用卡券
	 * level    等级
     * category 商品类型
     * product  商品id
     * rooms    房间数
     * days     预订天数   
     * room_nights 房间数x预订天数=间夜数
     * product_num 商品数量
     * bonus    积分
     * amount 总金额
	 * @param unknown $openid
	 * @param unknown $module
	 * @param unknown $inter_id
	 * @param unknown $params
	 * @return boolean|unknown
	 */
	public function getCardUseRuleByCondition($openid, $module, $inter_id, $params)
	{
		$this->load->model('member/imember');
		$meminfo = $this->imember->getMemberByOpenId($openid,$inter_id,0);
		if(!$meminfo || !isset($meminfo->mem_id)) return false;
		$params['level'] = $meminfo->level;//会员等级
		
		//获取所有规则
		$rules = $this->getRuleList(true,array('inter_id'=>$inter_id));

		$activeRules = array();
		foreach($rules as $rule) {
			if((isset($rule->module[0]) && $rule->module[0]!='all') && !in_array($module,$rule->module)) continue;
// 			if(!in_array($module,$rule->module)) continue;

			//过滤选定商品
			if($rule->product_type==2 && (!isset($params['product']) || !in_array($params['product'],$rule->product))) continue;

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
				if(isset($params['hotel']) && isset($params['category'])) {
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
			
			//返回订单或者间夜数限制
			if(isset($rule->condition['restriction'])) {
				$rule->restriction = $rule->condition['restriction'];
				unset($rule->condition['restriction']);
			} else {
				$rule->restriction = array();
			}
			
			//unset($rule->condition['exec_num']);
			if(count($rule->condition)) continue;

			$activeRules[$rule->ci_id] = $rule->restriction;
		}

		if(count($activeRules)) {
			$this->load->model('member/igetcard');
			$this->load->model('member/wxcard');
			
			//同步用户卡包的卡券
			$this->igetcard->updateGetCardListByWxPackage($inter_id,$openid,array_keys($activeRules));
			
			//$wxcards = $this->getWxCards($openid,$inter_id,array_keys($activeRules));

			//获取用户可用的卡券
			$cards = $this->igetcard->getCardListByCardIds($openid, array('inter_id'=>$inter_id,'ci_id'=>array_keys($activeRules),'period'=>1));

			if($cards) {
				foreach($cards as $card) {
					$card->restriction = $activeRules[$card->ci_id];
				}
				return $cards;
				//return array('cards'=>$cards,'wxcards'=>$wxcards);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
// 	protected function getWxCards($openid,$inter_id,$ci_ids)
// 	{
// 		$this->load->model('member/getcard');
// 		$this->load->model('member/igetcard');
// 		$this->load->model('member/icard');

// 		$carddetails = $this->icard->getCardsByWhere(array('ci_id'=>$ci_ids));

// 		//获取openid下所有微信卡券的CODE
// 		$wxcardsArr = $cidtocardid = $wxcards = array();
// 		foreach($carddetails as $c) {
// 			$cidtocardid[$c->ci_id] = $c->card_id;
// 		    //注意
// 			if(!empty($c->card_id) && $c->status=='CARD_STATUS_VERIFY_OK') {
// 				$wxcodes = $this->wxcard->getWxCardlist($inter_id,$openid,$c->card_id);
// 				if($wxcodes) {
// 					$wxcardsArr[$c->ci_id] = $wxcodes;
// 				}
// 			}
// 		}

// 		if(count($wxcardsArr)) {
// 			$this->load->model('member/imember');
// 			$memberinfo = $this->imember->getMemberByOpenId($openid);
			
// 			if($memberinfo && isset($memberinfo->mem_id)) {
// 				$memid = $memberinfo->mem_id;
// 			} else {
// 				return $wxcards;
// 			}
			
// 		    $getcarddetails = array();
		
// 			foreach($wxcardsArr as $ci_id=>$wx_c) {
// 				$getcardObjectList = $this->igetcard->getCardListByWhere(array('ci_id'=>$ci_id,'code'=>$wx_c));
// 				if($getcardObjectList) $getcarddetails[$ci_id] = $getcardObjectList;
// 			}

// 			$self_Codes = $self_ignoreCodes = $others_ignoreCodes = $others_donate_Codes = array();
// 			foreach($getcarddetails as $ci_id => $getcard) {
// 				foreach($getcard as $get) {
// 					if($get->openid==$openid) {
// 						if($get->status != Getcard::STATUS_HAVE_RECEIVE) {
// 							$self_ignoreCodes[$ci_id][] = $get->code;
// 						} else {
// 							$self_Codes[$ci_id][] = $get->code;
// 						}
// 					} else {
// 						if($get->status==Getcard::STATUS_HAVE_RECEIVE) {
// 							$others_donate_Codes[$ci_id][] = $get->code;
// 							$donateCodesToObjet[$get->code] = $get;
// 						} elseif($get->status==Getcard::STATUS_DONATE_COMPLETION) {
// 						} else {
// 							$others_ignoreCodes[$ci_id][] = $get->code;
// 						}
// 					}
// 				}
// 			}

// 		    $lastnewCodes = $donateCodes = array();
// 			foreach($wxcardsArr as $ci_id=>$wx_c) {
// 				foreach($wx_c as $c) {
// 					if(isset($self_ignoreCodes[$ci_id]) && in_array($c,$self_ignoreCodes[$ci_id])) continue;
// 					if(isset($others_ignoreCodes[$ci_id]) && in_array($c,$others_ignoreCodes[$ci_id])) continue;
					
// 					if(isset($others_donate_Codes[$ci_id]) && in_array($c,$others_donate_Codes[$ci_id])) {
// 						$donateCodes[$ci_id][] = $c;//转赠
// 					}					
					
		
// 					$lastnewCodes[$ci_id][] = $c;
// 				}
// 			}

// 			foreach($donateCodes as $ci_id=>$codes) {
// 				foreach($codes as $code) {
// 					$this->igetcard->updateGcardStatus($donateCodesToObjet[$code]->openid, $code, Getcard::STATUS_DONATE_COMPLETION);
// 				}
// 			}

// 			foreach($lastnewCodes as $ci_id=>$arr) {
// 				if(isset($cidtocardid[$ci_id])) {
// 					foreach($arr as $c) {
// 						$result = $this->wxcard->getWxCardStatus($inter_id,$cidtocardid[$ci_id],$c);

// 						if($result) {
// 							$wxcards[$c] = $cidtocardid[$ci_id];
// 							if(isset($self_Codes[$ci_id]) && in_array($c,$self_Codes[$ci_id])) continue;
							
// 							$data = array(
// 								'inter_id'=>$inter_id,
// 								'ci_id'=>$ci_id,
// 								'openid'=>$openid,
// 								'mem_id'=>$memid,
// 								'code'=>$c,
// 								'status'=>Getcard::STATUS_HAVE_RECEIVE
// 							);

// 							$this->igetcard->addGetCard($data);
// 						}
// 					}
// 				}
// 			}
// 		}
		
// 		return $wxcards;
// 	}
	
	public function getRuleByOpenidProduct($openid,$productid)
	{
		$this->load->model('member/getcard');
		$readAdapter = $this->load->database('member_read', true);
		
		$query = $readAdapter->from(self::TABLE_GETCARD)->where('openid',$openid)
		    ->join(self::TABLE_CARD_RULE, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD_RULE.'.ci_id AND is_active=1', 'left')
		    ->where('status',Getcard::STATUS_HAVE_RECEIVE)
		    ->get();
		
		$ret = array();
		foreach($query->result_array() as $row) {
			if(empty($row['cr_id']) || $row['product_type']==1) {
				$ret[] = $row;
			} elseif(!empty($row['cr_id']) && $row['product_type']==2) {
				$productids = $this->getProductsByRuleId($row['cr_id']);
				
				if(!empty($productids) && in_array($productid,$productids)) {
					$row['condition'] = unserialize($row['condition']);
					$ret[] = $row;
				}
			}
		}
		
		return $ret;
	}
	
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
				$writeAdapter->insert(self::TABLE_CARD_RULE,$data);
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
	 * @param int $cr_id
	 */
	public function deleteRuleById($cr_id)
	{
	    $cr_id = intval($cr_id);
	    
		$writeAdapter = $this->load->database('member_write',true);
		$writeAdapter->delete(self::TABLE_CARD_RULE, array('cr_id' => $cr_id));
	
		return $writeAdapter->affected_rows();
	}
	
	/**
	 * 根据id更新规则
	 * @param string $cr_id
	 * @param array $data
	 * @return bool
	 */
	public function updateRuleById($cr_id,$data)
	{
		try {
			if($this->checkData($data)) {
	
				$ruleObject = $this->getRuleById($cr_id,'cr_id',array('cr_id'));
	
				if($ruleObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['cr_id']);
					$result = $writeAdapter->update(self::TABLE_CARD_RULE, $data, array('cr_id' => $ruleObject->cr_id));
	
					return $result;
				} else {
					throw new Exception("Id为".$cr_id."的规则不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 根据Id查询规则
	 * @param string $cr_id
	 * @return unknown
	 */
	public function getRuleById($id,$field='cr_id',$select=array(),$must_appid=true)
	{
		$id = intval($id);
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
		
		$query = $readAdapter->from(self::TABLE_CARD_RULE)->where(array($field => $id))->get();
		$ruleObject = $query->row();

		if(isset($ruleObject->condition)) {
			$ruleObject->condition = unserialize($ruleObject->condition);
		}
		if(isset($ruleObject->product)) {
			$ruleObject->product = unserialize($ruleObject->product);
		}
		if(isset($ruleObject->module)) {
			$ruleObject->module = unserialize($ruleObject->module);
		}

		return $ruleObject;
	}
	
	/**
	 * 获取规则列表
	 * @param string $is_active
	 * @param unknown $where
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 * @param string $must_appid
	 * @return multitype:unknown
	 */
	public function getRuleList($is_active=null, $where=array(), $limit=null, $offset=null, $select=array(), $must_appid=true)
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
		
		if($is_active !== null) {
			if($is_active) {
				$readAdapter->where('is_active',1);
			} else {
				$readAdapter->where('is_active',0);
			}
		}
		
		if(isset($where['inter_id'])) {
			$readAdapter->where('inter_id', $where['inter_id']);
		} elseif($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
		
		$readAdapter->order_by('cr_id', 'DESC');

		$query = $readAdapter->from(self::TABLE_CARD_RULE)->get();
		
		$result = array();
		foreach ($query->result() as $rule)
		{
			if(isset($rule->condition)) {
				$rule->condition = unserialize($rule->condition);
			}
			if(isset($rule->product)) {
				$rule->product = unserialize($rule->product);
			}
			if(isset($rule->module)) {
				$rule->module = unserialize($rule->module);
			}
			$result[] = $rule;
		}
		
		return $result;
	}
	
	/**
	 * 获取规则数量
	 * @param string $is_active
	 * @param unknown $select
	 * @param string $must_appid
	 */
	public function getRuleListCount($is_active=null, $select=array('cr_id'), $must_appid=true)
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
	
		$query = $readAdapter->from(self::TABLE_CARD_RULE)->get();
	
		return $query->result();
	}
	
	public function getProductsByRuleId($cr_id)
	{
		$readAdapter = $this->load->database('member_read',true);
		$readAdapter->select('product_id')->where('cr_id',$cr_id);
		$query = $readAdapter->from(self::TABLE_CARD_RULE_PRODUCT)->get();
	
		$ret = array();
		foreach($query->result_array() as $row) {
			$ret[] = $row['product_id'];
		}
	
		return $ret;
	}
	
	public function updateProductsByRuleId($cr_id,$product_ids)
	{
		$writeAdapter = $this->load->database('member_write',true);
	
		$old_product_ids = $this->getProductsByRuleId($cr_id);
	
		$insert_ids = array_diff($product_ids,$old_product_ids);
		$delete_ids = array_diff($old_product_ids,$product_ids);
	
		if(!empty($insert_ids)) {
			$insert_str = '';
			foreach($insert_ids as $id) {
				$insert_str.= "('".$cr_id."','".$id."'),";
			}
			$insert_str = substr($insert_str,0,strlen($insert_str)-1);
			$writeAdapter->query("INSERT INTO ".self::TABLE_CARD_RULE_PRODUCT." (`cr_id`,`product_id`) VALUES ".$insert_str);
		}
	
		if(!empty($delete_ids)) {
			$delete_ids = implode(",",$delete_ids);
			$writeAdapter->query("DELETE FROM ".self::TABLE_CARD_RULE_PRODUCT." WHERE `product_id` in (".$delete_ids.")");
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
	
		if(isset($data['product_type'])) {
			$data['product_type'] = intval($data['product_type']);
		}
		
		if(isset($data['condition'])) {
			$data['condition'] = serialize($data['condition']);
		}
		
		if(isset($data['product'])) {
			$data['product'] = serialize($data['product']);
		}
		
		if(isset($data['module'])) {
			$data['module'] = serialize($data['module']);
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