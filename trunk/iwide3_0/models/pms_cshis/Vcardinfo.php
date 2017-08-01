<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vcardinfo extends CI_Model
{
	const TABLE_VCARD            = 'member_bgy_vcard_customer_info';
	
	protected $table_field       = array('gi_id','inter_id','mem_id','ci_id','name','telephone','identity_card','hotel','personal_picture_image_url','id_card_front_image_url','id_card_reverse_image_url','check');
	
	public function createInfo($data, $must_appid=true)
	{
		try {
			if($this->checkData($data,true)) {
				$infoObject = $this->getInfoByMemId($data['mem_id'], array('mem_id'));
				
				$writeAdapter = $this->load->database('member_write',true);
				if($infoObject) {
					return $writeAdapter->update(self::TABLE_VCARD, $data, array('mem_id' => $infoObject->mem_id));
				} else {
					if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
					return $writeAdapter->insert(self::TABLE_VCARD,$data);
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	public function updateMemberByOpenId($data)
	{
		try {
			if($this->checkData($data)) {
	
				$memberObject = $this->getMemberById($data['openid'],'openid',array('mem_id'));
	
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['openid']);
					$result = $writeAdapter->update(self::TABLE_MEMBER, $data, array('mem_id' => $memberObject->mem_id));
	
					return $result;
				} else {
					throw new Exception("OpenId为".$data['openid']."的会员不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	public function getInfoListNumber($limit=null, $offset=null, $where=array(), $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
	
		$readAdapter->from(self::TABLE_VCARD);
	
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
	
		$query = $readAdapter->get();
		return $query->result();
	}
	
	public function getInfoList($limit=null, $offset=null, $select=array(), $must_appid=true)
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
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		$query = $readAdapter->from(self::TABLE_VCARD)->get();
		return $query->result();
	}
	
	public function getInfoById($gi_id, $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		}
	
		$query = $readAdapter->from(self::TABLE_VCARD)->where('gi_id', $gi_id)->get();
		return $query->row();
	}
	
	public function updateInfoById($gi_id, $data)
	{
		try {
			if($this->checkData($data)) {
				$infoObject = $this->getInfoById($gi_id, array('gi_id'));
	
				if($infoObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['gi_id']);
					$result = $writeAdapter->update(self::TABLE_VCARD, $data, array('gi_id' => $infoObject->gi_id));
	
					return $result;
				} else {
					throw new Exception("ID为".$gi_id."的资料不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	public function getInfoByMemId($memid, $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		
		$readAdapter->where(array('mem_id' => $memid));
	
		$query = $readAdapter->from(self::TABLE_VCARD)->get();
		return $query->row();
	}
	
	protected function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
		
		$this->load->helper('security');
	
		if(isset($data['mem_id'])) {
			$data['mem_id'] = intval($data['mem_id']);
		}
	
		if(isset($data['ci_id'])) {
			$data['ci_id'] = intval($data['ci_id']);
		}
	
		if(isset($data['check'])) {
			$data['check'] = intval($data['check']);
		}
	
		if(isset($data['name'])) {
			$data['name'] = $this->security->xss_clean($data['name']);
		}
		
	    if(isset($data['telephone']) && !preg_match("/^[0-9\-]*$/i",$data['telephone'])) {
			throw new Exception("手机号码非法!");
		}
		
		if(isset($data['identity_card']) && !is_numeric($data['identity_card'])) {
			throw new Exception("身份证非法!");
		}
	
		if($new) {
			$data['create_time'] = date('Y-m-d H:i:s',time());
		}
	
		return true;
	}
	
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