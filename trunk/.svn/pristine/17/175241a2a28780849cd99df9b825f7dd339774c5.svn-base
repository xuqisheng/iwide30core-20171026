<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iconfig
{
	protected $CI;
	
	protected $_configModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getPrivilegeByModule($module,$inter_id)
	{
		$result = $this->getConfig('level_privilege',true,$inter_id);
		
		if($result) {
			$privilege = $result->value;
		} else {
			$privilege = array();
		}
		
		return $privilege;
	}
	//给出增加的额外的金钱规则
	public function getBtoMByModule($module,$inter_id)
	{
		$result = $this->getConfig('bonustomoney_rule',true,$inter_id);
	
		if($result) {
			$ret = $result->value;
		} else {
			$ret = array();
		}
	
		return $ret;
	}
	//给出奖金规则
	public function getBonusruleMByModule($module,$inter_id)
	{
		$result = $this->getConfig('bonus_rule',true,$inter_id);
	
		$ret = array();
		if($result) {
			foreach($result->value as $rule) {
				if($rule['module']==$module) $ret[] = $rule;
			}
		}
	
		return $ret;
	}
	
	/**
	 * 添加新配置
	 * @param array $data
	 * @return bool
	 */
	public function addConfig($code, $value, $serialize=null,$inter_id=null)
	{
		try {
		    $result = $this->getConfigModel()->addConfig($code, $value, $serialize, $inter_id);
		    return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	//给出后台会员配置信息
	public function getConfig($code, $serialize=null, $inter_id=null)
	{
		try {
			$configObject = $this->getConfigModel()->getConfig($code, $serialize, $inter_id);
			return $configObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function updateConfig($code,$value,$serialize=null,$inter_id=null)
	{
		try {
			$result = $this->getConfigModel()->updateConfig($code,$value,$serialize,$inter_id);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function deleteConfig($code,$inter_id=null)
	{
		try {
			$result = $this->getConfigModel()->deleteConfig($code,$inter_id);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	protected function getConfigModel()
	{
		if(!isset($this->_configModel)) {
			$this->CI->load->model('member/config','mconfig');
			$this->_configModel = $this->CI->mconfig;
		}
		return $this->_configModel;
	}
}