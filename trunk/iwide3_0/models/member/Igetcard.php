<?php 
class Igetcard
{
	protected $CI;
	
	protected $_getcardModel;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
// 	public function createCodeByNum($num)
// 	{
// 		$retarr = array();
		
// 		if($num>0) {
// 			while(count($retarr)<$num) {
// 				for($i=0;$i<$num-count($retarr);$i++) {
// 					$retarr[] = $this->getGcardModel()->getRandCode();
// 				}
// 				$retarr = array_unique($retarr);
// 			}	
// 		}
		
// 		return $retarr;
// 	}
	
	public function getCardsBySecne($openid,$module,$scene,$scene_id)
	{
		try {
			$cardslist = $this->getGcardModel()->getCardsBySecne($openid,$module,$scene,$scene_id);
			return $cardslist;
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
	
	public function getCardsByOpenid($openid, $limit=null, $offset=null, $is_vcard=false)
	{
		try {
			$cardslist = $this->getGcardModel()->getCardsByOpenid($openid, $limit, $offset, $is_vcard);
			return $cardslist;
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
	
	public function getMyVcards($openid, $limit=null, $offset=null)
	{
		try {
			$cardslist = $this->getGcardModel()->getMyVcards($openid, $limit, $offset);
			return $cardslist;
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
	 * 核销卡劵
	 * @param string $openid
	 * @param string $code
	 * @param string $source
	 * @throws Exception
	 * @return boolean
	 */
	public function consumeCard($openid, $code, $source)
	{
		try {	
			$getcardObject = $this->getGcardModel()->getGcardListByOpenidCode($openid, $code, null, true);
			$result = $this->getGcardModel()->checkStatus($getcardObject->status,Getcard::STATUS_CANCEL_VERIFICATION);
			if($result) {
				$data = array(
					'status'         => Getcard::STATUS_CANCEL_VERIFICATION,
					'consume_source' => $source
				);
				
				return $this->getGcardModel()->updateGetCard($openid,$code,$data);
			} else {
				throw new Exception("CODE".$code."状态转换到".Getcard::STATUS_CANCEL_VERIFICATION."错误!");
			}
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
	
	public function updateGetCardListByWxPackage($inter_id,$openid,$ci_ids=array())
	{
		try {
			$this->getGcardModel()->updateGetCardListByWxPackage($inter_id,$openid,$ci_ids);
		} catch (Exception $e) {
			$error = new stdClass();
			$error->error = true;
			$error->message = $e->getMessage();
			$error->code = $e->getCode();
			$error->file = $e->getFile();
			$error->line = $e->getLine();
				
			return $error;
		}
	}
	
	public function getCardById($gc_id)
	{
		try {
			$getcardObject = $this->getGcardModel()->getCardById($gc_id);
			return $getcardObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更改卡劵使用状态
	 * @param string $openid
	 * @param string $code
	 * @param int $status
	 * @param int $outer_id
	 * @param boolean $is_give_by_friend
	 * @param string $friend_user_name
	 * @param string $old_user_card_code
	 * @throws Exception
	 * @return boolean
	 */
	public function updateGcardStatus($openid, $code, $status, $outer_id=null, $is_give_by_friend=null, $friend_user_name=null, $old_user_card_code=null)
	{
		try {
			$getcardObject = $this->getGcardModel()->getGcardListByOpenidCode($openid, $code, null, true);
			
			$result = $this->getGcardModel()->checkStatus($getcardObject->status,$status);
			
			if($result) {
				$data['status'] = $status;
				if($outer_id)           $data['outer_id']           = $outer_id;
				if($is_give_by_friend)  $data['is_give_by_friend']  = $is_give_by_friend;
				if($friend_user_name)   $data['friend_user_name']   = $friend_user_name;
				if($old_user_card_code) $data['old_user_card_code'] = $old_user_card_code;
				
				return $this->getGcardModel()->updateGetCard($openid,$code,$data);
			} else {
				throw new Exception("CODE".$code."状态转换到".$status."错误!");
			}
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 用户删除卡劵
	 * @param string $openid
	 * @param string $code
	 * @throws Exception
	 * @return boolean
	 */
	public function delCard($openid, $code)
	{
		try {
			$getcardObject = $this->getGcardModel()->getGcardListByOpenidCode($openid, $code, null, true);
			$result = $this->getGcardModel()->checkStatus($getcardObject->status,Getcard::STATUS_DELETE);
				
			if($result) {
				$data = array(
					'status'         => Getcard::STATUS_DELETE
				);
		
				return $this->getGcardModel()->updateGetCard($openid,$code,$data);
			} else {
				throw new Exception("CODE".$code."状态转换到".Getcard::STATUS_DELETE."错误!");
			}
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
	
	public function getCardListByWhere($where)
	{
		try {
			$getcardObjectList = $this->getGcardModel()->getCardListByWhere($where);
			return $getcardObjectList;
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
	 * 根据openid获取领卡劵记录
	 * @param strint $openid
	 * @return object|boolean
	 */
	public function getGcardListByOpenId($openid, $where=array(), $limit=null, $offset=null)
	{
		try {
			$getcardObject = $this->getGcardModel()->getCardListByCondition($openid, 'openid', $where, $limit, $offset);
			return $getcardObject;
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
	
	public function getCardListByCardIds($openid, $where=array(), $limit=null, $offset=null)
	{
		try {
			$getcardObject = $this->getGcardModel()->getCardListByCondition($openid, 'openid', $where, $limit, $offset);
			return $getcardObject;
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
	
	public function getGcardByCode($code)
	{
		try {
			$getcardObject = $this->getGcardModel()->getGcardByCode($code);
			return $getcardObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 用户领取卡券
	 * @param unknown $openid
	 * @param unknown $inter_id
	 * @param unknown $ci_id
	 * @param number $num
	 * @param string $status
	 * @param unknown $add_data
	 * @return boolean|multitype:Ambigous <unknown, boolean, stdClass, unknown>
	 */
	public function userGetCard($openid,$inter_id,$cards=array(),$status=null,$add_data=array())
	{
		$this->getGcardModel();
		$this->CI->load->model('member/imember');
		$this->CI->load->model('member/icard');
		$meminfo = $this->CI->imember->getMemberByOpenId($openid);
	
		if(!$meminfo || !isset($meminfo->mem_id)) return false;
	
		$data = array(
			'inter_id'=>$inter_id,
			'openid'=>$openid,
			'mem_id'=>$meminfo->mem_id
		);
	
		if(is_null($status)) {
			$data['status'] = Getcard::STATUS_HAVE_RECEIVE;
		} else {
			$data['status'] = $status;
		}
	
		if(isset($add_data['module'])) {
			$data['module'] = $add_data['module'];
		}
		if(isset($add_data['scene'])) {
			$data['scene'] = $add_data['scene'];
		}
		if(isset($add_data['scene_id'])) {
			$data['scene_id'] = $add_data['scene_id'];
		}
	
		$ret = array();
		foreach($cards as $ci_id=>$num) {
			
			if(isset($add_data['rule_id'])) {
				$this->CI->load->model('member/irule');
				$rule = $this->CI->irule->getRule($add_data['rule_id']);
				
				if($rule && isset($rule->rule_id)) {
					if(isset($rule->condition['exec_num']) && !empty($rule->condition['exec_num'])) {
						$this->CI->load->model('member/getcardrecord');
						$record = $this->CI->getcardrecord->getRecordByMemRecord($meminfo->mem_id,$ci_id,'rule_id_'.$add_data['rule_id'],$inter_id);
						if($record) {
							if((int)$record->num < (int)$rule->condition['exec_num'] ){
								$this->CI->getcardrecord->updateRecord($meminfo->mem_id, $ci_id, 'rule_id_'.$add_data['rule_id'], intval($record->num)+1, $inter_id);
							} else {
								return array('code'=>1,'errmsg'=>'你已经领取过该卡券!');
							}
						} else {
							$this->CI->getcardrecord->updateRecord($meminfo->mem_id, $ci_id, 'rule_id_'.$add_data['rule_id'], 1, $inter_id);
						}
					}
				} else {
					return array('code'=>1,'errmsg'=>'系统错误!');
				}
			}
			
			$data['ci_id'] = $ci_id;
			for($i=0;$i<$num;$i++) {
				$data['code'] = $this->createCardCode($openid,$ci_id);
				$result = $this->addGetCard($data);
					
				if($result && !isset($result->error)) {
					$this->CI->icard->reduceInventory($ci_id,1);
					$ret[]=$result;
				}
			}
		}
	
		return $ret;
	}
	
	/**
	 * 增加领取卡劵信息
	 * @param string $openid
	 * @param string $card_id
	 * @param string $code
	 * @param int $status
	 * @param int $outer_id
	 * @param string $is_give_by_friend
	 * @param string $friend_user_name
	 * @param string $old_user_card_code
	 * @return unknown|boolean
	 */
	public function addGetCard($data)
	{	
		try {			
			$result = $this->getGcardModel()->createGetcard($data);
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
	
	protected function createCardCode($openid, $ci_id) {
		$code = $this->getGcardModel()->getRandCode();
		$result = $this->getGcardModel()->checkIsExistCode($openid, $ci_id, $code);
		
		if(!$result) return $code;
		
		$i=0;
		do {
			$i++;$code += 1;
			$result = $this->getGcardModel()->checkIsExistCode($openid, $ci_id, $code);
		} while($result && $i<100);
		return $code;
	}
	
	protected function getGcardModel()
	{
		if(!isset($this->_getcardModel)) {
			$this->CI->load->model('member/getcard');
			$this->_getcardModel = $this->CI->getcard;
		}
	
		return $this->_getcardModel;
	}
}