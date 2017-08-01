<?php 
class Ivcard
{
	protected $CI;
	
	protected $_vcardModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getCardDetaiInfoList($openid)
	{
		try {
			$cardlistObject = $this->getVcardModel()->getCardDetaiInfoList($openid);
			return $cardlistObject;
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
	
	public function addBalance($openid, $gc_id, $balance,$note)
	{
		try {
			$data = array(
				'openid'      => $openid,
				'gc_id'       => $gc_id,
				'balance'     => $balance
			);
			$result = $this->getVcardModel()->updateBalance($data, true, $note);
			return $result;
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
	
	public function reduceBalance($openid,$gc_id,$balance,$note)
	{
		try {
			$data = array(
				'openid'      => $openid,
				'gc_id'       => $gc_id,
				'balance'     => $balance
			);
			$result = $this->getVcardModel()->updateBalance($data, false, $note);
			return $result;
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
	
	public function getCardList($openid)
	{
		try {
			$cardlistObject = $this->getVcardModel()->getCardList($openid);
			return $cardlistObject;
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
	
	public function getVcardById($id, $field='vc_id')
	{
		try {
			$infoObject = $this->getVcardModel()->getVcardById($id, $field);
			return $infoObject;
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
	
	public function updateInfoById($id, $data, $field='vc_id')
	{
		try {
			$result = $this->getVcardModel()->updateInfoById($id, $data, $field);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getInfoById($id, $field='vc_id')
	{
		try {
			$result = $this->getVcardModel()->getInfoById($id, $field);
			return $result;
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
	
	protected function getVcardModel()
	{
		if(!isset($this->_vcardModel)) {
			$this->CI->load->model('member/vcard');
			$this->_vcardModel = $this->CI->vcard;
		}
	
		return $this->_vcardModel;
	}
}