<?php
class Default_service implements IPMS{
	protected $_memberModel;
	
	function __construct($params){
		$this->CI =& get_instance();
		$this->pms_set = $params ['pms_set'];
	}
	public function get_orders($inter_id,$status,$offset,$limit){
		return "I'm function get_orders in Test_service";
	}
	public function get_hotels($inter_id,$status,$offset,$limit){
		return "I'm function get_hotels in Test_service";
	}
	public function get_new_hotel($params=array()) {
		
	}
	public function get_rooms_change($rooms, $idents = array(), $condit = array()) {
		$this->CI->load->model('hotel/Order_model');
		return $this->CI->Order_model->get_rooms_change($rooms, $idents, $condit);
	}
	public function check_openid_member($inter_id,$openid,$paras) {
		$this->CI->load->model('hotel/Member_model');
		return  $this->CI->Member_model->check_openid_member($inter_id,$openid,$paras);
	}
	public function order_submit($inter_id,$orderid,$params){
		return array('s'=>1,'ensure'=>1);
	}
	public function add_web_bill($order,$params=array()){
		return array('s'=>1);
	}
	public function cancel_order($inter_id,$params){
		$this->CI->load->model('hotel/Order_model');
		return $this->CI->Order_model->cancel_order($inter_id,$params);
	}
	public function search_hotel_front($params){
		$this->CI->load->model('hotel/Hotel_check_model');
		return $this->CI->Hotel_check_model->search_hotel_front($params[0],$params[1],$this->pms_set);
	}
	public function get_hotel_citys($params){
		$this->CI->load->model('hotel/Hotel_check_model');
		$params[1]['pms_set']=$this->pms_set;
		return $this->CI->Hotel_check_model->get_hotel_citys($params[0],$params[1]);
	}
	public function get_hotel_comment_count($params){
		$this->CI->load->model('hotel/Comment_model');
		return $this->CI->Comment_model->get_hotel_comment_count_local($params[0],$params[1],$params[2]);
	}
	public function get_hotel_comments($params){
		$this->CI->load->model('hotel/Comment_model');
		return $this->CI->Comment_model->get_hotel_comments_local($params[0],$params[1],$params[2],$params[3],$params[4],$params[5],$params[6]);
	}
	public function add_comment($params){
		return array('s'=>1,'errmsg'=>'提交成功');
	}
	public function get_city_filter($params){
		$this->CI->load->model('hotel/Hotel_check_model');
		return $this->CI->Hotel_check_model->get_city_filter_local($params[0],$params[1],$params[2]);
	}
	public function get_order_state($params){
		return array();
	}
	private function _load_model($model){
		$name='tmodel';
		$this->CI->load->model($model,$name);
		return $this->CI->$name;
	}
	
	public function checklogin($params)
	{
		return true;
	}
	
	//验证短信是否合法
	public function checkSendSms($params) {
		$getsms = $params[1];
		if(!$_SESSION['sms']) {
			return  0;
		} elseif($_SESSION['sms']==$getsms) {
			return 1;
		}
		return 0;
	}
	
	public function updatePassWordin($params) {
		$openid        = $params[0];
		$telephone     = $params[1]['telephone'];
		//$identity_card = $params[1]['identity_card'];
	
		return array('code'=>0,'errmsg'=>"新密码已经发送到您手机号，请用新密码登录!");
	}
	
	/**
	 * 根据openid获取会员
	 * @param strint $openid
	 * @return unknown|boolean
	 */
	public function getMemberByOpenId($params)
	{
		try {
			$openid = $params[0];
			$memberObject = $this->getMemberModel()->getMemberByOpenId($openid);
			return $memberObject;
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
	
	public function getMemberById($params)
	{
		$memid = $params[0];
		try {
			$memberObject = $this->getMemberModel()->getMemberById($memid,'mem_id');
			return $memberObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	public function sendSms($params)
	{
		$phone = $params[0]['telephone'];
		$code = $params[1];
		$this->CI->load->model('member/sms','sms');
		$this->CI->sms->setLog();
		$result = $this->CI->sms->Sendsms($phone,array($code),60225);
		if(!(int)$result['code']){
			return '发送成功';
		}else{
			return '发送失败';
		}
	}
	
	public function modifiedMember($params)
	{
		return $params[1];
	}
	
	//获取积分记录
	public function getBonusRecords($params)
	{
		$openid = $params[0];

		$this->CI->load->model('member/iconsume');
		$memberObject         = $this->getMemberByOpenId(array($openid));
		$data['bonus']        = $this->CI->iconsume->getBonusByMember($memberObject->mem_id, 'all');
		$data['add_bonus']    = $this->CI->iconsume->getBonusByMember($memberObject->mem_id, 'charge');
		$data['reduce_bonus'] = $this->CI->iconsume->getBonusByMember($memberObject->mem_id, 'reduce');
		
		if(!empty($data['bonus']))        rsort($data['bonus']);
		if(!empty($data['add_bonus']))    rsort($data['add_bonus']);
		if(!empty($data['reduce_bonus'])) rsort($data['reduce_bonus']);
		
		return $data;
	}
	
	public function getBalanceRecords($params)
	{
		$openid = $params[0];

		$this->CI->load->model('member/iconsume');
		$memberObject         = $this->getMemberByOpenId(array($openid));
		$data['balances']        = $this->CI->iconsume->getBalancesByMember($memberObject->mem_id, 'all');
		$data['add_balances']    = $this->CI->iconsume->getBalancesByMember($memberObject->mem_id, 'charge');
		$data['reduce_balances'] = $this->CI->iconsume->getBalancesByMember($memberObject->mem_id, 'reduce');
		
		if(!empty($data['balances']))        rsort($data['balances']);
		if(!empty($data['add_balances']))    rsort($data['add_balances']);
		if(!empty($data['reduce_balances'])) rsort($data['reduce_balances']);
		
		$r_data['data_title'] = array('全部记录','充值记录','消费记录');
		$r_data['data_record'] = array($data['balances'],$data['add_balances'],$data['reduce_balances']);
	
		return $r_data;
	}
	
	//注册会员
	public function registerMember($params)
	{
		$openid = $params[0];
		$data   = $params[1];
		
		$this->updateStatus($openid,1);
		$result = $this->addMemberInfo($openid, $data);
		
		return $result;
	}
	
	//修改密码
	public function modPassword($params)
	{
		$openid = $params[0];
		$data   = $params[1];
	
		return array('code'=>1,'errmsg'=>'修改密码失败!');
	}
	
	/**
	 * 获取会员列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberList($params)
	{
		$limit  = $params[0];
		$offset = $params[1];
		
		$memberObjectList = $this->getMemberModel()->getMemberList($limit,$offset);
		return $memberObjectList;
	}
	
	/**
	 * 根据openid删除会员
	 * @param string $openid
	 * @return boolean|number
	 */
	public function deleteMemberByOpenId($params)
	{
		$openid = $params[0];
		try {
			$result = $this->getMemberModel()->deleteMemberByOpenId($openid);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function initMember($params)
	{		
		$openid = $params[0];
		$data   = $params[1];
		$inter_id = $params[2];
		$data['is_active'] = 0;
		
// 		$result = $this->createMember($openid, $data, $inter_id);
		$result = $this->createMember(array($openid, $data, $inter_id));//传参错误，作修改 @author lGh
		
		if($result) {
// 			return $this->getMemberByOpenId($openid);
			return $this->getMemberByOpenId(array($openid));//传参错误，作修改 @author lGh
		} else {
			return false;
		}
	}
	
	/**
	 * 创建会员
	 * @param string $openid          微信OpenID
	 * @param string $code            卡券唯一编号
	 * @param int $growth             会员成长值
	 * @param int $balance            金额
	 * @param int $bonus              积分
	 * @param int $level              等级
	 * @param int $is_active          是否激活
	 * @param timestamp $begin_time   激活有效期
	 * @param timestamp $end_time     激活截止期
	 * 
	 * @return bool
	 */
	public function createMember($params)
	{
		$data = $params[1];
		$data['openid'] = $params[0];
		$data['inter_id'] = $params[2];
		 
		if(isset($data['is_active']))   $data['is_active'] = intval($data['is_active']);
		
		try {
		    $result = $this->getMemberModel()->createMember($data);
		    return $result;
		} catch (Exception $e) {
			return false;
		}
		
		return false;
	}
	
	public function updateMemberByOpenId($params)
	{
		try {
			$data = $params[0];
			if(!isset($data['openid'])) throw new Exception("openid不存在");
			
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更加卡券的唯一code码
	 * @param string $openid
	 * @param string $code
	 * 
	 * @return bool
	 */
	public function updateCode($params)
	{
		$data = array(
			'openid'   => $params[0],
			'code'     => $params[1]
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员成长值
	 * @param string $openid
	 * @param int $growth
	 * @return unknown|boolean
	 */
	public function updateGrowth($params)
	{
		$data = array(
			'openid'     => $params[0],
			'growth'     => $params[1]
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 增加成长值
	 * @param unknown $openid
	 * @param unknown $growth
	 * @return unknown|boolean
	 */
	public function addGrowth($params)
	{	
		try {
			$data = array(
				'openid'     => $params[0],
				'growth'     => $params[1]
		    );
			$result = $this->getMemberModel()->updateGrowth($data, true);
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
	
	/**
	 * 减少成长值
	 * @param unknown $openid
	 * @param unknown $growth
	 * @return unknown|boolean
	 */
	public function reduceGrowth($params)
	{
		try {
			$data = array(
				'openid'     => $params[0],
				'growth'     => $params[1]
		    );
			$result = $this->getMemberModel()->updateGrowth($data, false);
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
	
	/**
	 * 更新会员储值金额
	 * @param string $openid
	 * @param int $balance
	 * @return unknown|boolean
	 */
	public function updateBalance($params)
	{
		$data = array(
			'openid'     => $params[0],
			'balance'    => $params[1]
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function addBalance($params)
	{
		try {
			$data = array(
				'openid'     => $params[0],
				'balance'     => $params[1]
			);
			$note     = $params[2];
			$order_id = $params[3];
			$inter_id = $params[4];
			$result = $this->getMemberModel()->updateBalance($data, true, $note, $order_id, $inter_id);
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
	
	public function reduceBalance($params)
	{
		try {
			$data = array(
				'openid'      => $params[0],
				'balance'     => $params[1]
			);
			$note     = $params[2];
			$order_id = $params[3];
			$inter_id = $params[4];
			$result = $this->getMemberModel()->updateBalance($data, false, $note,$order_id,$inter_id);
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
	
	/**
	 * 更新积分
	 * @param string $openid
	 * @param int $bonus
	 * @return unknown|boolean
	 */
	public function updateBonus($params)
	{
		$data = array(
			'openid'     => $params[0],
			'bonus'      => $params[1]
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function refund($params)
	{
		$openid   = $params[0];
		$order_id = $params[1];
		$note     = $params[2];
		$type     = $params[3];
		$inter_id = $params[4];
		
		try {
			$result = $this->getMemberModel($inter_id)->refund($openid, $order_id, $note, $type, $inter_id);
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
	
	public function addBonusByRule($params)
	{
		$openid       = $params[0];
		$category     = $params[1];
		$num          = $params[2];
		$note         = $params[3];
		$order_id     = $params[4];
		$member_level = $params[5];
		$inter_id     = $params[6];
		$type         = $params[7];
		
		try {
			$result = $this->getMemberModel($inter_id)->addBonusByRule($openid, $category, $num, $note, $order_id, $member_level, $inter_id, $type);
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
	
	public function addBonus($params)
	{
		$openid       = $params[0];
		$bonus        = $params[1];
		$note         = $params[2];
		$order_id     = $params[3];
		$inter_id     = $params[4];
		
		try {
			$data = array(
				'openid'     => $openid,
				'bonus'      => $bonus
			);
			$result = $this->getMemberModel()->updateBonus($data, true, $note,$order_id,$inter_id);
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
	
	public function reduceBonus($params)
	{
		$openid       = $params[0];
		$bonus        = $params[1];
		$note         = $params[2];
		$order_id     = $params[3];
		$inter_id     = $params[4];
		
		$this->CI->load->model('member/Weixin_text','Weixin');
		$time = date('Y-m-d H:i:s',time());
		$data_str = "login".json_encode($params);
		$this->CI->Weixin->add_weixin_text($data_str,$time);
		
		try {
			$data = array(
				'openid'     => $openid,
				'bonus'      => $bonus
			);
			$result = $this->getMemberModel()->updateBonus($data, false, $note,$order_id,$inter_id);
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
	
	/**
	 * 更新等级
	 * @param string $openid
	 * @param int $level
	 * @return unknown|boolean
	 */
	public function updateLevel($params)
	{
		$openid       = $params[0];
		$level        = $params[1];
		
		$data = array(
			'openid'     => $openid,
			'level'      => $level
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
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
	
	/**
	 * 更新激活状态
	 * @param string $openid
	 * @param int $active
	 * @return unknown|boolean
	 */
	public function updateStatus($params)
	{
		$openid        = $params[0];
		$active        = $params[1];
		
		$data = array(
			'openid'         => $openid,
			'is_active'      => $active
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新有效时间
	 * @param unknown $openid
	 * @param unknown $begin
	 * @param unknown $end
	 * @return unknown|boolean
	 */
	public function updateValidity($params)
	{
		$openid        = $params[0];
		$begin         = $params[1];
		$end           = $params[2];
		
		$data = array(
			'openid'                 => $openid,
			'activate_begin_time'    => $begin,
			'activate_end_time'      => $end
		);
	
		try {
			$result = $this->getMemberModel()->updateMemberByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------------
	
// 	public function upgradeLevel($openid)
// 	{
// 		try {
// 			return $this->getMemberModel()->upgradeLevel($openid);
// 		} catch (Exception $e) {
// 			$error = new stdClass();
// 			$error->error = true;
// 			$error->message = $e->getMessage();
// 			$error->code = $e->getCode();
// 			$error->file = $e->getFile();
// 			$error->line = $e->getLine();
// 			return $error;
// 		}
	
// 		return false;
// 	}
	
	
	public function getAllMemberLevels($params)
	{
		$inter_id = $params[0];
		try {
			return $this->getMemberModel($inter_id)->getAllMemberLevels($inter_id);
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
	
	public function getMemberLevel($params)
	{
		$member = $params[0];
		try {
			$this->getMemberModel()->getMemberLevel($member);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return $this;
	}
	
	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberDetailByOpenId($params)
	{
		$openid = $params[0];
		try {
			$memberInfoObject = $this->getMemberModel()->getMemberDetailById($openid);
			return $memberInfoObject;
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
	
	public function getMemberDetailByMemId($params)
	{
		$memid = $params[0];
		try {
			$memberInfoObject = $this->getMemberModel()->getMemberDetailById($memid,array(),'mem_id');
			return $memberInfoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 获取所有会员详细信息列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberDetailList($params)
	{
		$limit  = $params[0];
		$offset = $params[1];
		$where  = $params[2];
		
		$memberObjectList = $this->getMemberModel()->getMemberDetailList($limit, $offset, $where);
		return $memberObjectList;
	}
	
	public function getMemberDetailListNumber($limit=null, $offset=null, $where=null, $inter_id='')
	{
		$memberObjectList = $this->getMemberModel($inter_id)->getMemberDetailList($limit, $offset, $where, array(array('mem_id')));
		return count($memberObjectList);
	}
	
	/**
	 * 根据OpenId获取会员详细资料
	 * @param unknown $openid
	 * @return unknown|boolean
	 */
	public function getMemberInfoByOpenId($params)
	{		
		$openid  = $params[0];
		try {
			$memberinfoObject = $this->getMemberModel()->getMemberInfoById($openid);
			return $memberinfoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getMemberInfoByMemId($params)
	{
		$memid  = $params[0];
		try {
			$memberinfoObject = $this->getMemberModel()->getMemberInfoById($memid, 'mem_id');
			return $memberinfoObject;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 获取会员附加信息列表
	 * @param string $limit
	 * @param string $offset
	 * @return unknown
	 */
	public function getMemberInfoList($params)
	{
		$limit  = $params[0];
		$offset = $params[1];
		$memberInfoObjectList = $this->getMemberModel()->getMemberInfoList($limit,$offset);
		return $memberInfoObjectList;
	}
	
	/**
	 * 根据OpenId删除会员附加资料
	 * @param string $openid
	 * @return unknown|boolean
	 */
	public function deleteMemberInfoByOpenId($params)
	{
		$openid  = $params[0];
		try {
			$result = $this->getMemberModel()->deleteMemberInfoByOpenId($openid);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 添加会员详细资料
	 * @param string $openid
	 * @param array $data
	 * @return unknown|boolean
	 */
	public function addMemberInfo($params)
	{
		$data  = $params[1];
		$data['openid'] = $params[0];
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员卡编号
	 * @param string $openid
	 * @param string $card_number
	 * @return unknown|boolean
	 */
	public function updateMemberInfoCardNumber($params)
	{
		$openid = $params[0];
		$card_number = $params[1];
		
		$data = array(
			'openid'                 => $openid,
			'membership_number'      => $card_number
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员名字
	 * @param string $openid
	 * @param string $name
	 * @return unknown|boolean
	 */
	public function updateMemberInfoName($params)
	{
		$openid = $params[0];
		$name = $params[1];
		
		$data = array(
			'openid'    => $openid,
			'name'      => $name
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员性别
	 * @param unknown $openid
	 * @param unknown $sex
	 * @return unknown|boolean
	 */
	public function updateMemberInfoSex($params)
	{
		$openid = $params[0];
		$sex    = $params[1];
		
		$data = array(
			'openid'    => $openid,
			'sex'       => $sex
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员出生日期
	 * @param unknown $openid
	 * @param unknown $dob
	 * @return unknown|boolean
	 */
	public function updateMemberInfoDob($params)
	{
		$openid = $params[0];
		$dob    = $params[1];
		
		$data = array(
			'openid'    => $openid,
			'dob'       => $dob
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员电话号码
	 * @param unknown $openid
	 * @param unknown $telephone
	 * @return unknown|boolean
	 */
	public function updateMemberInfoTelephone($params)
	{
		$openid       = $params[0];
		$telephone    = $params[1];
		
		$data = array(
			'openid'         => $openid,
			'telephone'      => $telephone
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员QQ
	 * @param unknown $openid
	 * @param unknown $qq
	 * @return unknown|boolean
	 */
	public function updateMemberInfoQQ($params)
	{
		$openid       = $params[0];
		$qq           = $params[1];
		
		$data = array(
			'openid'    => $openid,
			'qq'        => $qq
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员邮件
	 * @param unknown $openid
	 * @param unknown $email
	 * @return unknown|boolean
	 */
	public function updateMemberInfoEmail($params)
	{
		$openid       = $params[0];
		$email        = $params[1];
		
		$data = array(
			'openid'    => $openid,
			'email'     => $email
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function updateMemberInfoById($params)
	{	
		$ma_id       = $params[0];
		$data        = $params[1];
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoById($ma_id, $data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 更新会员身份证
	 * @param unknown $openid
	 * @param unknown $idcard
	 * @return unknown|boolean
	 */
	public function updateMemberInfoIdcard($params)
	{
		$openid        = $params[0];
		$idcard        = $params[1];
		
		$data = array(
			'openid'            => $openid,
			'identity_card'     => $idcard
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新会员地址
	 * @param string $openid
	 * @param string $address
	 * @return unknown|boolean
	 */
	public function updateMemberInfoAddress($params)
	{
		$openid        = $params[0];
		$address       = $params[1];
		
		$data = array(
			'openid'      => $openid,
			'address'     => $address
		);
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 更新自定义字段
	 * @param string $openid
	 * @param string $custom
	 * @param number $type
	 * @return unknown|boolean
	 */
	public function updateMemberInfoCustom($params)
	{
		$data['openid']            = $params[0];
		$data['custom'.$params[2]] = $params[1];
		
		try {
			$result = $this->getMemberModel()->updateMemberInfoByOpenId($data);
			return $result;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		
		return false;
	}
	
	public function getMemberModel()
	{
		if(!isset($this->_memberModel)) {
			$this->CI->load->model('member/member');
			$this->_memberModel = $this->CI->member;
		}
		return $this->_memberModel;
	}
	
	public function loginGetUserinfo($params) {
		var_dump($params);exit;
		$telephone = $params [1] ['telephone'];
		$openid = $params [0];
			$data = array (
					'openid' => $openid,
					'name' => $result->user->uname,
					'telephone' => $result->user->tel,
					'email' => $result->user->email,
					'identity_card' => '0',
					'membership_number' => $result->user->id,
					'dob' => $result->user->birthday,
					'password' => $password,
					'inter_id' => $inter_id
			);
			$updateParams = array (
					'openid' => $openid,
					'bonus' => $result->user->score,
					'is_login' => 1,
					'level' => $result->user->level,
					'last_login_time' => time ()
			);
			$this->getMemberModel ()->updateMemberByOpenId ( $updateParams );
			$this->getMemberModel ()->updateMemberInfoByOpenId ( $data );
			return true;
	}


    /**
     * @author Jake
     * @param $params
     * @return mixed
     */
    //获取卡卷列表--default
    public function getIgetcard($params){
        $openid = $params[0];
        $this->CI->load->model('member/igetcard');
        $cards = $this->CI->igetcard->getCardsByOpenid($openid);
        return $cards;
    }


    /**
     * @author Jake
     * @param $params
     * @return mixed
     */
    //会员中心-卡券列表-default
    public function couponCardList($params){
        $openid = $params[0];
        $inter_id = $params[1];
        $this->CI->load->model('member/igetcard');
        $data['cards'] = $this->CI->igetcard->getCardsByOpenid($openid);


        $this->CI->load->model('wx/access_token_model');
        $data['signpackage'] = $this->CI->access_token_model->getSignPackage($inter_id);

        $data['view'] = 'member/cardlist';
        return $data;
    }

    /**
     * /优惠券
     * @param $params
     */
    public function getRulesByParams($params){
        $openid = $params[0];
        $module = $params[1];
        $inter_id = $params[2];
        $param = $params[3];

        $this->CI->load->model ( 'member/Icardrule' );

        return $this->CI->Icardrule->getRulesByParams_local($openid, $module, $inter_id, $param);

    }
    
    /**
     * /更改代金券状态
     * @param $params
     */
    public function updateGcardStatus($params){
    	$openid = $params[0];
    	$module = $params[1];
    	$inter_id = $params[2];
    	$param = $params[3];
    	$openid = $params[0];
    	$code = $params[1];
    	$status = $params[2];
    	$outer_id=$params[3];
    	$is_give_by_friend=$params[4];
    	$friend_user_name=$params[5];
    	$old_user_card_code=$params[6];
    
    	$this->CI->load->model ( 'member/Igetcard' );
    
    	return $this->CI->Igetcard->updateGcardStatusForPms($openid, $code, $status, $outer_id , $is_give_by_friend , $friend_user_name , $old_user_card_code);
    
    }
    
    /**
     * //同步PMS会员信息
     * @param $params
     */
    public function sycMemberInfo($params){
        return true;
    }
    //解绑会员
    public function unBindMemberCard($params){
        return true;
    }


    /**
     * 取酒店附加信息，包括wifi,设施
     */
	public function get_hotel_extra_info($params){
		
		return array();
		
	}
	
	/**
	 * 取房附加信息，包括wifi,设施
	 */
	public function return_room_detail($params){
	
		//return array();
		$inter_id = $params[0];
		$hotel_id = $params[1];
		$room_id = $params[2];
		$this->CI->load->model('hotel/Hotel_model');
		$cards = $this->CI->Hotel_model->return_room_detail_local($inter_id,$hotel_id,$room_id);
		return $cards;
	
	}
	

    public function loginWithOpenid($params){
        return true;
    }

    //增加收藏
    public function add_fav_to_pms($params){
        return true;
    }
    //移除收藏
    public function remove_fav($params){
        return true;
    }

    //获取本地收藏
    public function get_user_front_marks($params){
        $params = $params[0];
        $idents = $params[0];
        $sort   = $params[1];
        $nums   = $params[2];
        $offset = $params[3];
        $this->CI->load->model ( 'hotel/Hotel_model' );
		return $this->CI->Hotel_model->get_front_marks_local ( $idents,$sort,$nums ,$offset);
    }

    //根据条件获取本地收藏
    public function get_type_mark($params){
        $params = $params[0];
        $this->CI->load->model ( 'hotel/Hotel_model' );
        $idents = $params[0];
        $staus = $params[1];
        return $this->CI->Hotel_model->get_type_mark_local($idents,$staus);

    }
	public function order_checkin_type($params)
	{
		return 'unknown';
	}
	public function continue_order_item($params)
	{
	    return array('s'=>1);
	}
}