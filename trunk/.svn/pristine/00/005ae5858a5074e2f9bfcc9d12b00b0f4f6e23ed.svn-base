<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Irule
{
	protected $CI;
	
	protected $_ruleModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getProductsByRuleId($rule_id)
	{
		$rule_id = intval($rule_id);
	
		try {
			$products = $this->getRuleModel()->getProductsByRuleId($rule_id);
			return $products;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateProductsByRuleId($rule_id,$product_ids)
	{
		$rule_id = intval($rule_id);
		
		try {
			$result = $this->getRuleModel()->updateProductsByRuleId($rule_id, $product_ids);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 添加新规则
	 * @param array $data
	 * @return bool
	 */
	public function createRule($data)
	{
		if(isset($data['is_active']))  {
			$data['is_active'] = intval($data['is_active']);
		} else {
			$data['is_active'] = 0;
		}
		
		try {
		    $result = $this->getRuleModel()->createRule($data);
		    return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 删除规则
	 * @param int $rule_id
	 */
	public function deleteRule($rule_id)
	{
		$rule_id = intval($rule_id);
		 
		try {
			$result = $this->getRuleModel()->deleteRuleById($rule_id);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据Id更新规则
	 * @param string $rule_id
	 * @param array $data
	 * @return bool
	 */
	public function updateRule($rule_id, $data)
	{
		if(isset($data['is_active']))  {
			$data['is_active'] = intval($data['is_active']);
		} else {
			$data['is_active'] = 0;
		}

		try {
			$result = $this->getRuleModel()->updateRuleById($rule_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更改激活状态
	 * @param unknown $rule_id
	 * @param unknown $is_active
	 * @return unknown|boolean
	 */
	public function setActive($rule_id, $is_active)
	{
		$is_active = intval($is_active);
		
		if($is_active==1) {
			$data['is_active'] = 1;
		} else {
			$data['is_active'] = 0;
		}
		
		try {
			$result = $this->getRuleModel()->updateRule($rule_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据Id查询规则
	 * @param string $rule_id
	 * @return unknown
	 */
	public function getRule($rule_id)
	{
		$rule_id = intval($rule_id);
		
		try {
			$ruleObject = $this->getRuleModel()->getRuleById($rule_id);
			return $ruleObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getRewardByRules($module, $handle, $inter_id, $params)
	{
		try {
			$ruleObjectList = $this->getRuleModel()->getRewardByRules($module, $handle, $inter_id, $params);
			return $ruleObjectList;
		} catch (Exception $e) {
			$error = new stdClass();
			$error->error = true;
			$error->message = $e->getMessage();
			$error->code = $e->getCode();
			$error->file = $e->getFile();
			$error->line = $e->getLine();
				
			return $error;
		}
	}
	
	public function getRuleListByModule($module, $handle=null, $is_active=null)
	{
		try {
			$ruleObjectList = $this->getRuleModel()->getRuleListByModule($module, $handle, $is_active);
		    return $ruleObjectList;
		} catch (Exception $e) {
			$error = new stdClass();
			$error->error = true;
			$error->message = $e->getMessage();
			$error->code = $e->getCode();
			$error->file = $e->getFile();
			$error->line = $e->getLine();
			
			return $error;
		}
	}
	
	/**
	 * 获取规则列表
	 * @param int $is_active
	 * @param string $limit
	 * @param string $offset
	 * @return object
	 */
	public function getRuleList($is_active=null, $where=null, $limit=null, $offset=null)
	{
		$ruleObjectList = $this->getRuleModel()->getRuleList($is_active, $where, $limit, $offset);
		return $ruleObjectList;
	}
	
	public function getRuleListCount()
	{
		$ruleObjectList = $this->getRuleModel()->getRuleListCount();
		return count($ruleObjectList);
	}
	
	protected function getRuleModel()
	{
		if(!isset($this->_ruleModel)) {
			$this->CI->load->model('member/rule');
			$this->_ruleModel = $this->CI->rule;
		}
	
		return $this->_ruleModel;
	}
}