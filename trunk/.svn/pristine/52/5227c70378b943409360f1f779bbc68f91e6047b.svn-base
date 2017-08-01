<?php 
class Iaddress
{
	protected $CI;
	
	protected $_addressModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function createAddress($data)
	{	
		try {
			$result = $this->getAddressModel()->createAddress($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function getAddress($address_id)
	{
		try {
			$address = $this->getAddressModel()->getAddress($address_id);
			return $address;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getAddressList($memid)
	{		
		try {
			$addressObjectList = $this->getAddressModel()->getAddressList($memid);
		    return $addressObjectList;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function updateAddress($address_id, $data)
	{	
		try {
			$result = $this->getAddressModel()->updateAddress($address_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	protected function getAddressModel()
	{
		if(!isset($this->_addressModel)) {
			$this->CI->load->model('member/address');
			$this->_addressModel = $this->CI->address;
		}
	
		return $this->_addressModel;
	}
}