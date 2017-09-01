<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vcardadd extends CI_Model
{
	const TABLE_ADD        = 'iwide_member_bgy_vcard_additional_info';
	const TABLE_CARD       = 'member_card_infomation';
	const TABLE_GETCARD    = 'member_get_card_list';
	const TABLE_CARD_TYPE  = 'member_card_type';
	
	protected $table_field       = array('ga_id','inter_id','mem_id','gc_id','name','telephone','identity_card','password','balance');
	
	public function getCardDetaiInfoList($openid, $select=array())
	{
		$this->load->model('member/getcard');
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_GETCARD.".*")
			    ->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','brand_name','notice','description','is_active','logo_url')))
			    ->select(self::getColumns(self::TABLE_ADD, array('ga_id','balance','name','telephone','identity_card','password')));
		}
		
		$query = $readAdapter->from(self::TABLE_GETCARD)
		    ->join(self::TABLE_CARD, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
		    ->join(self::TABLE_ADD, self::TABLE_GETCARD.'.gc_id='.self::TABLE_ADD.'.gc_id', 'left')
		    ->join(self::TABLE_CARD_TYPE, self::TABLE_CARD.'.ct_id='.self::TABLE_CARD_TYPE.'.ct_id', 'inner')
		    ->where(self::TABLE_CARD_TYPE.'.is_vcard',1)
		    ->where(self::TABLE_GETCARD.'.openid',$openid)
		    ->where('('.self::TABLE_GETCARD.'.status='.Getcard::STATUS_DID_NOT_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.Getcard::STATUS_HAVE_RECEIVE.')')
		    ->order_by(self::TABLE_GETCARD.'.create_time','DESC')
		    ->get();

		return $query->result();
	}
	
	public function createInfo($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			if($this->checkData($data,true)) {
				$infoObject = $this->getInfoByGcId($data['gc_id'], array('ga_id'));
				if($infoObject) {
					throw new Exception("该储值卡的资料已经存在！");
				} else {
					$writeAdapter = $this->load->database('member_write',true);
					$writeAdapter->insert(self::TABLE_ADD,$data);
					return $writeAdapter->insert_id();
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoByGcId($gc_id, $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		}
	
		$query = $readAdapter->from(self::TABLE_ADD)->where('gc_id', $gc_id)->get();
		return $query->row();
	}
	
	public function getInfoById($ga_id, $field='ga_id', $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		}
	
		$query = $readAdapter->from(self::TABLE_ADD)->where($field, $ga_id)->get();
		return $query->row();
	}
	
	public function updateInfoById($id, $data, $field='ga_id')
	{
		try {
			if($this->checkData($data)) {
				$infoObject = $this->getInfoById($id, $field, array('ga_id'));

				if($infoObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['ga_id']);
					$result = $writeAdapter->update(self::TABLE_ADD, $data, array('ga_id' => $infoObject->ga_id));
	
					return $result;
				} else {
					throw new Exception("ID为".$id."的资料不存在!");
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
	
		$query = $readAdapter->from(self::TABLE_ADD)->get();
		return $query->row();
	}
	
	protected function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
		
		$this->load->helper('security');
	
		if(isset($data['mem_id'])) {
			$data['mem_id'] = intval($data['mem_id']);
		}
	
		if(isset($data['gc_id'])) {
			$data['gc_id'] = intval($data['gc_id']);
		}
		
		if(isset($data['balance'])) {
			$data['balance'] = floatval($data['balance']);
		}
	
		if(isset($data['name'])) {
			$data['name'] = $this->security->xss_clean($data['name']);
		}
		
	    if(isset($data['telephone']) && !preg_match("/^[0-9\-]*$/i",$data['telephone'])) {
			throw new Exception("手机号码非法!");
		}
		
		if(isset($data['identity_card']) && !is_numeric($data['identity_card'])) {
			$data['identity_card'] = 0;
		}
	
		if($new) {
			$data['create_time'] = date('Y-m-d H:i:s',time());
		}
	
		return true;
	}
	
	static protected function getColumns($table,$columns)
	{
		$select = array();
		foreach($columns as $column) {
			$select[] = $table.".".$column;
		}
		return implode(",",$select);
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