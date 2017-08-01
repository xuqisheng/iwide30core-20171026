<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Icardrule
{
	protected $CI;
	
	protected $_ruleModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getRulesByParams($openid, $module, $inter_id, $params)
	{
        $this->CI->load->library ( 'PMS_Adapter', array (
            'inter_id' => $inter_id,
            'hotel_id' => 0
        ), 'pmsa' );
        $cards = $this->CI->pmsa->getRulesByParams ($openid, $module, $inter_id, $params );
        return $cards;
	}
    public function getRulesByParams_local($openid, $module, $inter_id, $params)
    {
        try {
            $cards = $this->getRuleModel()->getCardUseRuleByCondition($openid, $module, $inter_id, $params);
            return $cards;
        } catch (Exception $e) {
            $error = new stdClass();
            $error->error = true;
            $error->message = $e->getMessage();
            $error->code = $e->getCode();
            $error->file = $e->getFile();
            $error->line = $e->getLine();
            return $error;
        }

        return false;
    }
	public function getRuleByOpenidProduct($openid,$productid)
	{
		try {
			$cards = $this->getRuleModel()->getRuleByOpenidProduct($openid,$productid);
			return $cards;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	public function getProductsByRuleId($cr_id)
	{
		$cr_id = intval($cr_id);
	
		try {
			$products = $this->getRuleModel()->getProductsByRuleId($cr_id);
			return $products;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateProductsByRuleId($cr_id,$product_ids)
	{
		$cr_id = intval($cr_id);
	
		try {
			$result = $this->getRuleModel()->updateProductsByRuleId($cr_id, $product_ids);
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
	 * @param int $cr_id
	 */
	public function deleteRule($cr_id)
	{
		$cr_id = intval($cr_id);
		 
		try {
			$result = $this->getRuleModel()->deleteRuleById($cr_id);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据Id更新规则
	 * @param string $cr_id
	 * @param array $data
	 * @return bool
	 */
	public function updateRule($cr_id, $data)
	{
		if(isset($data['is_active']))  {
			$data['is_active'] = intval($data['is_active']);
		} else {
			$data['is_active'] = 0;
		}
		
		try {
			$result = $this->getRuleModel()->updateRuleById($cr_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更改激活状态
	 * @param unknown $cr_id
	 * @param unknown $is_active
	 * @return unknown|boolean
	 */
	public function setActive($cr_id, $is_active)
	{
		$is_active = intval($is_active);
		
		if($is_active==1) {
			$data['is_active'] = 1;
		} else {
			$data['is_active'] = 0;
		}
		
		try {
			$result = $this->getRuleModel()->updateRule($cr_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据Id查询规则
	 * @param string $cr_id
	 * @return unknown
	 */
	public function getRule($cr_id)
	{		
		try {
			$ruleObject = $this->getRuleModel()->getRuleById($cr_id);
			return $ruleObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getRuleByCardId($ci_id)
	{
		try {
			$ruleObject = $this->getRuleModel()->getRuleById($ci_id, 'ci_id');
			return $ruleObject;
		} catch (Exception $e) {
			$error = new stdClass();
			$error->error = true;
			$error->message = $e->getMessage();
			$error->code = $e->getCode();
			$error->file = $e->getFile();
			$error->line = $e->getLine();
			return $error;
		}
	
		return false;
	}
	
	/**
	 * 获取规则列表
	 * @param int $is_active
	 * @param string $limit
	 * @param string $offset
	 * @return object
	 */
	public function getRuleList($is_active=null, $where=array(), $limit=null, $offset=null)
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
			$this->CI->load->model('member/cardrule');
			$this->_ruleModel = $this->CI->cardrule;
		}
	
		return $this->_ruleModel;
	}
}