<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Model
{
	const TABLE_CONFIG       = 'iwide_member_config_data';
	
	/**
	 * 添加新配置
	 * @param array $data
	 * @return bool
	 */
	public function addConfig($code, $value, $serialize=null, $inter_id=null, $must_appid=true)
	{
		$code = strval($code);
		
		if($this->getConfig($code,true,$inter_id)) {
			return $this->updateConfig($code,$value,$serialize,$inter_id);
		} else {
			if($serialize) {
				$value = serialize($value);
			}
			$data = array('code'=>$code,'value'=>$value);
			
			if($inter_id) {
				$data['inter_id'] = $inter_id;
			} elseif($must_appid && $appid=getAppid()) {
				$data['inter_id'] = $appid;
			}
			
			$writeAdapter = $this->load->database('member_write',true);
			return $writeAdapter->insert(self::TABLE_CONFIG, $data);
		}
		
	}
	
	public function getConfig($code, $serialize=null,$inter_id=null, $must_appid=true)
	{
		$code = strval($code);
		$readAdapter = $this->load->database('member_read',true);
		
		$where = array('code' => $code);
	    if($inter_id) {
			$where['inter_id'] = $inter_id;
		} elseif($must_appid && $appid=getAppid()) {
			$where['inter_id'] = $appid;
		}

		$query = $readAdapter->from(self::TABLE_CONFIG)->where($where)->get();
		$configObject = $query->row();

		if($configObject && $serialize) {
			$configObject->value = unserialize($configObject->value);
		}
			
		return $configObject;
	}
	
	public function updateConfig($code,$value,$serialize=null,$inter_id=null,$must_appid=true)
	{
		try {
			$configObject = $this->getConfig($code);
	
			if($configObject) {
				if($serialize) $value = serialize($value);
				
				$where = array('code' => $code);
				if($inter_id) {
					$where['inter_id'] = $inter_id;
				} elseif($must_appid && $appid=getAppid()) {
					$where['inter_id'] = $appid;
				}
				
				$writeAdapter = $this->load->database('member_write',true);
				$result = $writeAdapter->update(self::TABLE_CONFIG, array('value'=>$value), $where);
	
				return $result;
			} else {
				throw new Exception("code为".$code."的配置不存在!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	public function deleteConfig($code, $inter_id=null, $must_appid=true)
	{
		$where = array('code' => $code);
		
		if($inter_id) {
			$where['inter_id'] = $inter_id;
		} elseif($must_appid && $appid=getAppid()) {
			$where['inter_id'] = $appid;
		}
		
		$writeAdapter = $this->load->database('member_write',true);
		$writeAdapter->delete(self::TABLE_CONFIG, $where);

		return $writeAdapter->affected_rows();
	}
}