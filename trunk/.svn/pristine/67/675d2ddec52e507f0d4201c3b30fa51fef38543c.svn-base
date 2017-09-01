<?php 
class Icard
{
	protected $CI;
	
	protected $_cardModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function getCardTypes()
	{
		return $this->getCardModel()->getCardTypes();
	}
	
	public function getCodeTypes()
	{
		return $this->getCardModel()->getCodeTypes();
	}
	
	/**
	 * 添加新的卡劵
	 * @param array $data
	 * @throws Exception
	 * @return boolean
	 */
	public function createCard($data)
	{	
		try {
		    $result = $this->getCardModel()->createCard($data);
		    return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 根据ID更新卡劵信息
	 * @param int $ci_id
	 * @param array $data
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateCard($ci_id,$data)
	{		
		try {
			$result = $this->getCardModel()->updateCard($ci_id,$data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function reduceInventory($ci_id,$num)
	{
		try {
			$result = $this->getCardModel()->reduceInventory($ci_id,$num);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function getCardGroupByType($select=null,$inter_id=null)
	{
		$cardObjectList = $this->getCardModel()->getCardGroupByType($select,$inter_id);
		return $cardObjectList;
	}
	
	public function getCardsByWhere($where)
	{
		try {
			$cardObjectList = $this->getCardModel()->getCardsByWhere($where);
			return $cardObjectList;
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
	
	/**
	 * 根据id获取卡劵信息
	 * @param int $ci_id
	 * @return object
	 */
	public function getCardById($ci_id)
	{
		$ci_id = intval($ci_id);
		try {
			$cardObject = $this->getCardModel()->getCard($ci_id);
			return $cardObject;
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
	
	/**
	 * 获取卡劵列表
	 * @param int $limit
	 * @param int $offset
	 * @return object
	 */
	public function getCardList($vcard=null, $is_active=null, $where=null, $limit=null, $offset=null)
	{
		$cardObjectList = $this->getCardModel()->getCardList($vcard, $is_active, $where, $limit, $offset);
		return $cardObjectList;
	}
	
	/**
	 * 添加新的卡劵类型
	 * @param unknown $name
	 */
	public function addCardType($name, $card_type, $is_vcard, $is_package,$inter_id=null)
	{
		try {
		    $result = $this->getCardModel()->addCardType($name, $card_type, $is_vcard, $is_package, $inter_id);
		    return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getCardListCount()
	{
		$cardObjectList = $this->getCardModel()->getCardListCount(null, null, 'ci_id');
		return count($cardObjectList);
	}
	
	/**
	 * 更新卡劵种类名称
	 * @param int $ct_id
	 * @param string $name
	 * @throws Exception
	 * @return unknown
	 */
	public function updateCardType($ct_id, $name, $card_type, $is_vcard, $is_package)
	{
		try {
		    $result = $this->getCardModel()->updateCardType($ct_id, $name, $card_type, $is_vcard, $is_package);
		    return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function deleteCardType($ct_id)
	{
		return $this->getCardModel()->deleteCardType($ct_id);
	}
	
	/**
	 * 根据ID取得卡劵种类
	 * @param unknown $ct_id
	 */
	public function getCardTypeById($ct_id,$inter_id=null)
	{
		return $this->getCardModel()->getCardTypeById($ct_id,$inter_id);
	}
	
	/**
	 * 获取卡劵类型列表
	 * @param string $limit
	 * @param string $offset
	 */
	public function getCardTypeList($limit=null, $offset=null, $where=null)
	{
		$ctObjectList = $this->getCardModel()->getCardTypeList($limit,$offset,$where);
		return $ctObjectList;
	}
	
	protected function getCardModel()
	{
		if(!isset($this->_cardModel)) {
			$this->CI->load->model('member/card');
			$this->_cardModel = $this->CI->card;
		}
	
		return $this->_cardModel;
	}
}