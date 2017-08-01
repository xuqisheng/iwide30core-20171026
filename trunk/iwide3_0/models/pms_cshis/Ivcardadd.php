<?php 
class Ivcardadd
{
	protected $CI;
	
	protected $_vaddModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getCardDetaiInfoList($openid)
	{
		try {
			$cardlistObject = $this->getVaddModel()->getCardDetaiInfoList($openid);
			return $cardlistObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function createInfo($data)
	{
		try {
			$result = $this->getVaddModel()->createInfo($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoByGcId($gc_id)
	{
		try {
			$infoObject = $this->getVaddModel()->getInfoByGcId($gc_id);
			return $infoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoById($id, $field='ga_id')
	{
		try {
			$infoObject = $this->getVaddModel()->getInfoById($id, $field);
			return $infoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateInfoById($id, $data, $field='ga_id')
	{
		try {
			$result = $this->getVaddModel()->updateInfoById($id, $data, $field);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	protected function getVaddModel()
	{
		if(!isset($this->_vaddModel)) {
			$this->CI->load->model('pms_cshis/vcardadd');
			$this->_vaddModel = $this->CI->vcardadd;
		}
	
		return $this->_vaddModel;
	}
}