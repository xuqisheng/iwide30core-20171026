<?php 
class Ivcardinfo
{
	protected $CI;
	
	protected $_vcardModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function createInfo($data)
	{
		try {
			$result = $this->getVcardModel()->createInfo($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoByMemId($memid, $select=array())
	{
		try {
			$infoObject = $this->getVcardModel()->getInfoByMemId($memid, $select);
			return $infoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoList($limit=null, $offset=null, $select=array())
	{
		$orderList = $this->getVcardModel()->getInfoList($limit,$offset);
		return $orderList;
	}
	
	public function getInfoListNumber($limit=null, $offset=null, $where=null)
	{
		$infoObjectList = $this->getVcardModel()->getInfoListNumber($limit, $offset, $where, array('gi_id'));
		return count($infoObjectList);
	}
	
	public function getInfoById($gi_id)
	{
		try {
			$infoObject = $this->getVcardModel()->getInfoById($gi_id);
			return $infoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function updateInfoById($gi_id, $data)
	{
		try {
			$result = $this->getVcardModel()->updateInfoById($gi_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	protected function getVcardModel()
	{
		if(!isset($this->_vcardModel)) {
			$this->CI->load->model('bgyhotel/vcardinfo');
			$this->_vcardModel = $this->CI->vcardinfo;
		}
	
		return $this->_vcardModel;
	}
}