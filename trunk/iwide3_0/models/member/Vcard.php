<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vcard extends CI_Model
{
	const TABLE_VCARD        = 'member_vcard';
	const TABLE_CARD         = 'member_card_infomation';
	const TABLE_GETCARD      = 'member_get_card_list';
	
	protected $table_field       = array('vc_id','inter_id','mem_id','gc_id','co_id','name','telephone','identity_card','password','balance','distribution_no');
	
	public function getCardDetaiInfoList($openid, $select=array(), $must_appid=true)
	{
		$this->load->model('member/getcard');
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_GETCARD.".*")
			    ->select(self::getColumns(self::TABLE_CARD, array('title','sub_title','brand_name','notice','description','is_active','logo_url')))
			    ->select(self::getColumns(self::TABLE_VCARD, array('vc_id','balance','name','telephone','identity_card','password')));
		}
		
		if($must_appid && $appid=getAppid())  $readAdapter->where(self::TABLE_GETCARD.'.inter_id',$appid);
		
		$query = $readAdapter->from(self::TABLE_GETCARD)
		    ->join(self::TABLE_CARD, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
		    ->join(self::TABLE_VCARD, self::TABLE_GETCARD.'.gc_id='.self::TABLE_VCARD.'.gc_id', 'left')
		    ->where(self::TABLE_GETCARD.'.openid',$openid)
		    ->where('('.self::TABLE_GETCARD.'.status='.Getcard::STATUS_DID_NOT_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.Getcard::STATUS_HAVE_RECEIVE.')')
		    ->order_by(self::TABLE_GETCARD.'.create_time','DESC')
		    ->get();

		return $query->result();
	}
	
	public function getCardList($openid, $select=array(), $must_appid=true)
	{
		$this->load->model('member/getcard');
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		} else {
			$readAdapter->select(self::TABLE_GETCARD.".*")->select(self::getColumns(self::TABLE_VCARD, array('vc_id','balance','name','telephone','identity_card','password')));
		}
		
		if($must_appid && $appid=getAppid())  $readAdapter->where(self::TABLE_GETCARD.'.inter_id',$appid);
	
		$query = $readAdapter->from(self::TABLE_GETCARD)
			->join(self::TABLE_VCARD, self::TABLE_GETCARD.'.gc_id='.self::TABLE_VCARD.'.gc_id', 'inner')
			->where(self::TABLE_GETCARD.'.openid',$openid)
			->where('('.self::TABLE_GETCARD.'.status='.Getcard::STATUS_DID_NOT_RECEIVE.' OR '.self::TABLE_GETCARD.'.status='.Getcard::STATUS_HAVE_RECEIVE.')')
			->order_by(self::TABLE_GETCARD.'.create_time','DESC')
			->get();
	
		return $query->result();
	}
	
	public function getVcardById($id, $field='vc_id', $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		}
	
		$query = $readAdapter->from(self::TABLE_VCARD)
		    ->join(self::TABLE_GETCARD, self::TABLE_GETCARD.'.gc_id='.self::TABLE_VCARD.'.gc_id', 'inner')
		    ->where($field, $id)->get();
		return $query->row();
	}
	
	public function createInfo($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			if($this->checkData($data,true)) {

				if(isset($data['gc_id'])) {
				    $infoObject = $this->getInfoById($data['gc_id'], 'gc_id', array('vc_id'));
				    if($infoObject) {
					    throw new Exception("该储值卡的资料已经存在！");
				    }
				}

				$writeAdapter = $this->load->database('member_write',true);
				$writeAdapter->insert(self::TABLE_VCARD,$data);

				return $writeAdapter->insert_id();
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoById($id, $field='vc_id', $select=array())
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',', $select));
		}
	
		$query = $readAdapter->from(self::TABLE_VCARD)->where($field, $id)->get();
		return $query->row();
	}
	
	public function updateBalance($data, $add, $note='')
	{
		try {
			if($this->checkData($data)) {
	
				$this->load->model('member/imember');
				$memberObject = $this->imember->getMemberById($data['openid'],'openid',array('mem_id'));
	
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
	                $vcard = $this->getInfoById($data['gc_id']);
	                
	                $result  = false;
	                if($vcard && $vcard->mem_id==$memberObject->mem_id) {
						if($add) {
							$value = "balance+".$data['balance'];
						} else {
							if($data['balance']>$vcard->balance) {
								throw new Exception("金额不能为负数!");
							}
							$value = "balance-".$data['balance'];
						}
		
						$result = $writeAdapter->set('balance', $value, FALSE)
							->where(array('mem_id' => $memberObject->mem_id))
							->update(self::TABLE_MEMBER);
							
						if($result) {
							$this->load->model('member/iconsume');
							if($add) {
								$this->iconsume->charge($data['openid'], $data['balance'],$note);
							} else {
								$this->iconsume->consume($data['openid'], $data['balance'], $note);
							}
						}
	                }
	
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
	
	public function updateInfoById($id, $data, $field='vc_id')
	{
		try {
			if($this->checkData($data)) {
				$infoObject = $this->getInfoById($id, $field, array('vc_id'));

				if($infoObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['vc_id']);
					$result = $writeAdapter->update(self::TABLE_VCARD, $data, array('vc_id' => $infoObject->vc_id));
	
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
	
		if(isset($data['gc_id'])) {
			$data['gc_id'] = intval($data['gc_id']);
		}
		
		if(isset($data['vc_id'])) {
			$data['vc_id'] = intval($data['vc_id']);
		}
		
		if(isset($data['balance'])) {
			$data['balance'] = floatval($data['balance']);
		}
	
		if(isset($data['name'])) {
			$data['name'] = $this->security->xss_clean($data['name']);
		}

        if(isset($data['distribution_no'])) {
            $data['distribution_no'] = $this->security->xss_clean($data['distribution_no']);
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