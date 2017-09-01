<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Model
{	
	const TABLE_ADDRESS          = 'member_address';
	
	protected $table_field       = array('address_id', 'inter_id', 'mem_id','consignee','telephone','address');
	
	/**
	 * 添加会员地址
	 * @param unknown $data
	 * @param string $must_appid
	 * @throws Exception
	 * @return boolean
	 */
	public function createAddress($data, $must_appid=true)
	{
		try {
			if($must_appid && $appid=getAppid())  $data['inter_id'] = $appid;
			
			if($this->checkData($data)) {
				$writeAdapter = $this->load->database('member_write',true);
				return $writeAdapter->insert(self::TABLE_ADDRESS,$data);
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 根据主键获取地址
	 * @param unknown $address_id
	 */
	public function getAddress($address_id)
	{
		$address_id = intval($address_id);
		
		$readAdapter = $this->load->database('member_read',true);
		$query = $readAdapter->from(self::TABLE_ADDRESS)->where(array('address_id' => $address_id))->get();
		return $query->row();
	}
	
	/**
	 * 根据会员主键获取该会员所以地址
	 * @param unknown $memid
	 * @param string $limit
	 * @param string $offset
	 */
	public function getAddressList($memid, $limit=null, $offset=null)
	{
		$readAdapter = $this->load->database('member_read',true);
		
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		$query = $readAdapter->from(self::TABLE_ADDRESS)->where(self::TABLE_ADDRESS.'.mem_id',$memid)->get();
		return $query->result();
	}
	
	/**
	 * 根据主键更新地址
	 * @param unknown $address_id
	 * @param unknown $data
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateAddress($address_id, $data)
	{
		try {
			if($this->checkData($data)) {
	
				$addressObject = $this->getAddress($address_id);

				if($addressObject) {
					$writeAdapter = $this->load->database('member_write',true);
					$result = $writeAdapter->update(self::TABLE_ADDRESS, $data, array('address_id' => $addressObject->address_id));
	
					return $result;
				} else {
					throw new Exception("Address ID为".$address_id."的地址不存在!");
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 删除地址
	 * @param unknown $address_id
	 */
	public function deleteAddress($address_id)
	{
		$address_id = intval($address_id);
		
		$writeAdapter = $this->load->database('member_write',true);
		$writeAdapter->delete(self::TABLE_ADDRESS, array('address_id' => $address_id));

		return $writeAdapter->affected_rows();
	}

	/**
	 * 检测数据
	 * @param unknown $data
	 * @throws Exception
	 * @return boolean
	 */
	protected function checkData(&$data)
	{	
		$this->_filterData($data);
		
		$this->load->helper('security');
		
		if(isset($data['mem_id'])) {
			$data['mem_id'] = intval($data['mem_id']);
		}
		
		if(isset($data['telephone']) && !preg_match("/^[0-9\-]*$/i",$data['telephone'])) {
			throw new Exception("手机号码非法!");
		}
		
		if(isset($data['consignee'])) {
			$data['consignee'] = $this->security->xss_clean($data['consignee']);
		}
		
		if(isset($data['address'])) {
			$data['address'] = $this->security->xss_clean($data['address']);
		}
		
		return true;
	}

	protected function _filterData(&$data)
	{	
		$toDelKeys = array_diff(array_keys($data),$this->table_field);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}
}