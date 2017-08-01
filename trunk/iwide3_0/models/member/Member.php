<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Model
{
	const OPENID_LENGTH            = 28;
	const TABLE_MEMBER             = 'member';
	const TABLE_MEMBER_INFO        = 'member_additional';
	
	protected $table_member_field       = array('mem_id','inter_id','openid','code','growth','balance','bonus','level','is_active','is_login','last_login_time','activate_begin_time','activate_end_time','update_time');
	protected $table_member_info_field  = array('ma_id','inter_id','mem_id','membership_number','name','sex','dob','telephone','qq','email','identity_card','address','password','custom1','custom2','custom3','custom4','custom5','owner_no','owner_name','member_type');
	
	/**
	 * 退货
	 * @param unknown $openid
	 * @param unknown $order_id
	 * @param unknown $note
	 * @param string $type
	 * @param string $inter_id
	 * @throws Exception
	 * @return boolean
	 */
	public function refund($openid, $order_id, $note, $type='all', $inter_id='')
	{
		$writeAdapter = $this->load->database('member_write',true);
		
		$meminfo = $this->getMemberById($openid);
		
		if(!$meminfo) {
			return false;
		}
		
		if($type=='balance' || $type=='bonus') {
			$where['type'] = $type;
		} else {
			$where['type'] = 'all';
		}
		
		if(!empty($inter_id)) {
			$where['inter_id'] = $inter_id;
		}
		
		if(empty($order_id)) {
			throw new Exception("Order ID 不能为空!");
		} else {
			$where['order_id'] = $order_id;
		}

		$this->load->model('member/iconsume');
		$records = $this->iconsume->getAllRecordsByMemId($meminfo->mem_id,$where);
		
		$newrecord = array();
		foreach($records as $record) {
			if( $record->type==CONSUME::TYPE_BALANCE_CHARGE && $meminfo->balance<$record->balance) {
				throw new Exception("余额不足扣除!");
			}
			
			if($record->type==CONSUME::TYPE_INTEGRAL_CHARGE && $meminfo->bonus<$record->bonus) {
				throw new Exception("剩余积分不足以扣除!");
			}
			
			$record = (array)$record;
			unset($record['cr_id']);
			unset($record['create_time']);
			unset($record['note']);
			
			$newrecord[] = $record;
		}

		foreach($newrecord as $record) {
			if($record['type']==Consume::TYPE_BALANCE_CHARGE) {
				$record['type'] = Consume::TYPE_REFUND_BALANCE_CONSUME;
				$value = "balance-".$record['balance'];
			} elseif($record['type']==Consume::TYPE_BALANCE_CONSUME) {
				$record['type'] = Consume::TYPE_REFUND_BALANCE_CHANGE;
				$value = "balance+".$record['balance'];
			} elseif($record['type']==Consume::TYPE_INTEGRAL_CHARGE) {
				$record['type'] = Consume::TYPE_REFUND_INTEGRAL_CONSUME;
				$value = "bonus-".$record['bonus'];
			} elseif($record['type']==Consume::TYPE_INTEGRAL_CONSUME) {
				$record['type'] = Consume::TYPE_REFUND_INTEGRAL_CHARGE;
				$value = "bonus+".$record['bonus'];
			}
			
			$record['note'] = $note;
			$record['openid'] = $openid;

			$result = $this->iconsume->createRecord($record);
			if($result) {
				if(substr($value,0,5)=='bonus') {
					$key = 'bonus';
				} else {
					$key = 'balance';
				}
				$result = $writeAdapter->set($key, $value, FALSE)
				    ->where(array('mem_id' => $meminfo->mem_id))
				    ->update(self::TABLE_MEMBER);
			}
		}
		
		return true;
	}
	
	/**
	 * 根据"积分获取规则"增加积分
	 * //index.php/member/bonusrule
	 * @param unknown $openid
	 * @param unknown $category
	 * @param unknown $num
	 * @param string $note
	 * @param string $order_id
	 * @param string $member_level
	 * @param string $inter_id
	 * @param string $type
	 * @return Ambigous <unknown, boolean, unknown>
	 */
	public function addBonusByRule($openid, $category, $num, $note='', $order_id='',$member_level='-1', $inter_id='',$type='BALANCE')
	{
		//根据条件获取相应的规则
		$this->load->model('member/config', 'mconfig');
		$configs = $this->mconfig->getConfig('bonus_rule', true)->value;
	
		$rule = array();
		foreach($configs as $config) {
			if(($config['member']=='-1' || $config['member']==$member_level) && $config['category']==$category && isset($config[$type])) {
				$rule = $config[$type];
				break;
			}
		}
	
		//根据规则获取应该增加的积分
		$result = false;
		if(!empty($rule)) {
			$key = key($rule);
			$addbonus = ($num/$key)*$rule[$key];
			
			$data = array(
				'openid'   => $openid,
				'bonus'    => intval($addbonus)
			);
		} else {
			$data = array(
				'openid'   => $openid,
				'bonus'    => intval($num)
			);
		}
		
		$result = $this->updateBonus($data, true, $note, $order_id, $inter_id);
	
		return $result;
	}
	
	/**
	 * 获取所有会员等级
	 * @param unknown $inter_id
	 * @return multitype:
	 */
	public function getAllMemberLevels($inter_id)
	{
		$this->load->model('member/config', 'mconfig');
		$data = $this->mconfig->getConfig('level', true,$inter_id);
		
		if($data) {
	 	    return $data->value;
		} else {
			return array();
		}
	}
	
	/**
	 * 获取会员等级
	 * @param unknown $member
	 * @return Member
	 */
	public function getMemberLevel($member)
	{
		if(is_object($member)) {
			$this->load->model('member/config', 'mconfig');
			$data = $this->mconfig->getConfig('level', true);
			if(isset($data->value[$member->level])) {
				$member->levelinfo = $data->value[$member->level];
			}
		}
		
		return $this;
	}
	
	/**
	 * 添加新会员
	 * @param array $data
	 * @return bool
	 */
	public function createMember($data, $must_appid=true)
	{
		try {
			if(!isset($data['inter_id']) && $must_appid && $appid=getAppid())  $data['inter_id'] = $appid;

			if($this->checkData($data,true)) {
				$memberObject = $this->getMemberById($data['openid'],'openid',array('mem_id'));
				if($memberObject) {
					throw new Exception("OpenID为".$data['openid']."的会员已经存在！");
				} else {
					$writeAdapter = $this->load->database('member_write',true);
					$data['mem_card_no']=$this->create_member_no();
					return $writeAdapter->insert(self::TABLE_MEMBER,$data);
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		return false;
	}
	
	/**
	 * 自有会员号生成算法
	 * @return Ambigous <string, number>|boolean
	 */
	function create_member_no() {
		$tmp=str_pad(substr(time(),4,6).mt_rand(0,99),8,mt_rand(0,9));
		for($i=0;;$i++){
			$tmp+=$i;
			if(!$this->db->get_where('member',array('inter_id'=>getAppid(),'mem_card_no'=>$tmp))->row_array())
				return $tmp;
		}
		return false;
	}
	
	/**
	 * 根据OpenId更新会员资料
	 * @param string $openid
	 * @param array $data
	 * @return bool
	 */
	public function updateMemberByOpenId($data)
	{
		try {
			if($this->checkData($data)) {
				$memberObject = $this->getMemberById($data['openid'],'openid',array('mem_id'));

				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
					unset($data['openid']);
					$result = $writeAdapter->update(self::TABLE_MEMBER, $data, array('mem_id' => $memberObject->mem_id));
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
	
	/**
	 * 添加成长值
	 * @param unknown $data
	 * @param unknown $add
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateGrowth($data, $add)
	{
		try {
			if($this->checkData($data)) {
	
				$memberObject = $this->getMemberById($data['openid'],'openid',array('mem_id','growth'));
	
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
					
					if($add) {
						$value = "growth+".$data['growth'];
					} else {
						if($data['growth']>$memberObject->growth) {
							throw new Exception("成长值不能为负数!");
						}
						$value = "growth-".$data['growth'];
					}

					$result = $writeAdapter->set('growth', $value, FALSE)
					    ->where(array('mem_id' => $memberObject->mem_id))
					    ->update(self::TABLE_MEMBER);

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
	
	/**
	 * 增加或者扣减会员金额
	 * @param unknown $data
	 * @param unknown $add
	 * @param string $note
	 * @param string $order_id
	 * @param string $inter_id
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateBalance($data, $add, $note='',$order_id='',$inter_id='')
	{
		try {
			//检测数据是否合法
			if($this->checkData($data)) {
	
				$memberObject = $this->getMemberById($data['openid'],'openid',array('mem_id','balance'));
	
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
						
					if($add) {
						$value = "balance+".$data['balance'];
					} else {
						if($data['balance']>$memberObject->balance) {
							throw new Exception("金额不能为负数!");
						}
						$value = "balance-".$data['balance'];
					}
	
					$result = $writeAdapter->set('balance', $value, FALSE)
						->where(array('mem_id' => $memberObject->mem_id))
						->update(self::TABLE_MEMBER);
					
					if($result) {
						//添加消费记录
						$this->load->model('member/iconsume');
						if($add) {
							$this->iconsume->charge($data['openid'], $data['balance'],$note, $order_id, $inter_id);
						} else {
						    $this->iconsume->consume($data['openid'], $data['balance'], $note, $order_id, $inter_id);
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
	
	/**
	 * 增加或者扣减会员积分
	 * @param unknown $data
	 * @param unknown $add
	 * @param string $note
	 * @param string $order_id
	 * @param string $inter_id
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateBonus($data, $add, $note='',$order_id='',$inter_id='')
	{
		try {
			if($this->checkData($data)) {
	
				$memberObject = $this->getMemberById($data['openid'],'openid',array('mem_id','bonus'));
	
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
	
					if($add) {
						$value = "bonus+".$data['bonus'];
					} else {
						if($data['bonus']>$memberObject->bonus) {
							throw new Exception("积分不能为负数!");
						}
						$value = "bonus-".$data['bonus'];
					}
	
					$result = $writeAdapter->set('bonus', $value, FALSE)
						->where(array('mem_id' => $memberObject->mem_id))
						->update(self::TABLE_MEMBER);
						
					if($result) {
						//增加消费记录
						$this->load->model('member/iconsume');
						if($add) {
							$this->updateGrowth(array('openid'=>$data['openid'],'growth'=>$data['bonus']),true);
							$this->iconsume->addBonus($data['openid'], $data['bonus'], $note, $order_id, $inter_id);
						} else {
							$this->iconsume->reduceBonus($data['openid'], $data['bonus'], $note, $order_id, $inter_id);
						}
					}
					
					//检测会员等级
					if($add) $this->checkBonusMemberLevel($data['openid']);
	
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
	
	/**
	 * 会员积分升级
	 * 根据会员成长值检测会员等级变化
	 * @param unknown $openid
	 * @return Member
	 */
	public function checkBonusMemberLevel($openid)
	{
		$meminfo = $this->getMemberById($openid);
		$this->load->model('member/iconfig');
		$level_bonus = $this->iconfig->getConfig('level_bonus',true,$this->session->get_admin_inter_id());
		if($level_bonus) {
			$level_bonus = $level_bonus->value;
		} else {
			$level_bonus = array();
		}
		
		$upgrade_bonus = $this->iconfig->getConfig('upgrade_bonus',true,$this->session->get_admin_inter_id());
		if($upgrade_bonus) {
			$upgrade_bonus = $upgrade_bonus->value;
		} else {
			$upgrade_bonus = false;
		}
		
		if($upgrade_bonus && $level_bonus) {
			arsort($level_bonus);
			
			foreach($level_bonus as $level=>$bonus) {
				if($meminfo->growth>=$bonus) {
					if($meminfo->level != $level) {
					    $this->updateMemberByOpenId(array('openid'=>$openid,'level'=>$level));
					}
					break;
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * 会员充值升级
	 * 根据充值金额检测会员等级
	 * @param unknown $openid
	 * @param unknown $balance
	 * @return Member
	 */
	public function checkBalanceMemberLevel($openid,$balance)
	{
		$meminfo = $this->getMemberById($openid);
		$level_balance = $this->iconfig->getConfig('level_balance',true,$this->session->get_admin_inter_id());
		if($level_balance) {
			$level_balance = $level_balance->value;
		} else {
			$level_balance = array();
		}
	
		$upgrade_balance = $this->iconfig->getConfig('upgrade_balance',true,$this->session->get_admin_inter_id());
		if($upgrade_balance) {
			$upgrade_balance = $upgrade_balance->value;
		} else {
			$upgrade_balance = false;
		}
	
		if($upgrade_balance && $level_balance) {
			arsort($level_balance);
				
			foreach($level_balance as $level=>$bal) {
				if($balance>=$bal) {
					if($meminfo->level != $level) {
					    $this->updateMemberByOpenId(array('openid'=>$openid,'level'=>$level));
					}
					break;
				}
			}
		}
	
		return $this;
	}
	
	/**
	 * 根据OpenId查询会员
	 * @param string $openid
	 * @return unknown
	 */
	public function getMemberByOpenId($openid,$select=array())
	{	
		if(!$this->checkOpenid($openid)) {
			throw new Exception("OpenId格式错误!");
		} else {
			$readAdapter = $this->load->database('member_read',true);
			
			if($select) {
				$readAdapter->select(implode(',',$select));
			}
			$query = $readAdapter->from(self::TABLE_MEMBER)->where(array('openid' => $openid))->get();
			return $query->row();
		}
		
		return false;
	}
	
	/**
	 * 根据ID获取会员
	 * @param unknown $id
	 * @param string $field
	 * @param unknown $select
	 * @throws Exception
	 * @return boolean
	 */
	public function getMemberById($id,$field='openid',$select=array())
	{
		if($field=='openid') {
			if(!$this->checkOpenid($id)) throw new Exception("OpenId格式错误!");
		} elseif($field=='mem_id') {
			$id = intval($id);
		}

		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}

		$query = $readAdapter->from(self::TABLE_MEMBER)->where(array($field => $id))->get();
		return $query->row();
	
		return false;
	}
	
	/**
	 * 获取会员列表
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 */
	public function getMemberList($limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}

		$query = $readAdapter->from(self::TABLE_MEMBER)->get();
		return $query->result();
	}
	
	/**
	 * 根据OpenId删除会员
	 * @param string $openid
	 * @return bool|number
	 */
	public function deleteMemberByOpenId($openid)
	{
		if(!$this->checkOpenid($openid)) {
			throw new Exception("OpenId格式错误!");
		} else {
			$writeAdapter = $this->load->database('member_write',true);
			$writeAdapter->delete(self::TABLE_MEMBER, array('openid' => $openid));
				
			return $writeAdapter->affected_rows();
		}
		
		return false;
	}
	
	/**
	 * 根据ma_id更新会员资料
	 * @param unknown $ma_id
	 * @param unknown $data
	 * @throws Exception
	 * @return unknown|boolean
	 */
	public function updateMemberInfoById($ma_id, $data)
	{
		try {
			$ma_id = intval($ma_id);
				
			if($this->checkInfoData($data)) {
				$memberObject = $this->getMemberInfoById($ma_id, 'ma_id');
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
					if($memberObject->ma_id) {
						$result = $writeAdapter->update(self::TABLE_MEMBER_INFO, $data, array('ma_id' => $memberObject->ma_id));
						return $result;
					} else {
						throw new Exception("会员资料不存在!");
					}
				} else {
					throw new Exception("会员不存在!");
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 根据OpenId添加更新会员资料
	 * @param array $data
	 * @return bool
	 */
	public function updateMemberInfoByOpenId($data)
	{
		try {
			$openid = strval($data['openid']);

			if($this->checkInfoData($data)) {
				$memberObject = $this->getMemberDetailById($openid);
				if($memberObject) {
					$writeAdapter = $this->load->database('member_write',true);
					if(isset($memberObject->ma_id) && !empty($memberObject->ma_id)) {
						$result = $writeAdapter->update(self::TABLE_MEMBER_INFO, $data, array('ma_id' => $memberObject->ma_id));
						return $result;
					} else {
						$data['mem_id'] = $memberObject->mem_id;
						return $writeAdapter->insert(self::TABLE_MEMBER_INFO,$data);
					}
				} else {
					throw new Exception("会员不存在!");
				}
			} else {
				throw new Exception("输入数据非法!");
			}
		} catch (Exception $e) {
			echo $e->getMessage();exit;
			throw new Exception($e->getMessage());
		}
	
		return false;
	}
	
	/**
	 * 根据OpenId获取会员基本资料
	 * @param string $openid
	 * @return bool
	 */
	public function getMemberInfoById($id, $field="openid")
	{
		if($field=='openid') {
			$memberObject = $this->getMemberById($id,'openid',array('mem_id'));
			if($memberObject) {
				$readAdapter = $this->load->database('member_read',true);
				$query = $readAdapter->get_where(self::TABLE_MEMBER_INFO, array('mem_id' => $memberObject->mem_id));
				return $query->row();
			}
		} elseif($field=='mem_id' || $field=='ma_id') {
			$readAdapter = $this->load->database('member_read',true);
			$query = $readAdapter->get_where(self::TABLE_MEMBER_INFO, array($field => $id));
			return $query->row();
		}
		
		return false;
	}
	
	public function getMemberInfoByTelephone($telephone,$inter_id)
	{
		$readAdapter = $this->load->database('member_read',true);
		$query = $readAdapter->get_where(self::TABLE_MEMBER_INFO, array('telephone' => $telephone,'inter_id'=>$inter_id));
		return $query->row();
	}
	
	/**
	 * 根据OpenId删除会员附加资料
	 * @param string $openid
	 * @return bool|number
	 */
	public function deleteMemberInfoByOpenId($openid)
	{
		$memberObject = $this->getMemberById($openid,'openid',array('mem_id'));
		
		if($memberObject) {
			$writeAdapter = $this->load->database('member_write',true);
			$writeAdapter->delete(self::TABLE_MEMBER_INFO, array('mem_id' => $memberObject->mem_id));
			
			return $writeAdapter->affected_rows();
		} else {
			throw new Exception("OpenId为".$openid."的会员不存在!");
		}
	
		return false;
	}
	
	/**
	 * 获取会员附加信息列表
	 * @param string $limit
	 * @param string $offset
	 * @param unknown $select
	 */
	public function getMemberInfoList($limit=null, $offset=null, $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
			
		if($select) {
			$readAdapter->select(implode(',',$select));
		}
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($must_appid && $appid=getAppid()) {
			$readAdapter->where('inter_id', $appid);
		}
	
		$query = $readAdapter->from(self::TABLE_MEMBER_INFO)->get();
		return $query->result();
	}
	
	/**
	 * 根据OpenId获取会员详细资料
	 * @param string $openid
	 * @param array $select
	 * @return bool
	 */
	public function getMemberDetailById($id,$select=array(),$field='openid')
	{
		if(($field=='openid') && !$this->checkOpenid($id)) {
			throw new Exception("OpenId格式错误!");
		} else {
			$readAdapter = $this->load->database('member_read',true);
			$readAdapter->from(self::TABLE_MEMBER)
			    ->join(self::TABLE_MEMBER_INFO, self::TABLE_MEMBER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
			    ->where(self::TABLE_MEMBER.'.'.$field,$id);
			if($select) {
				if(isset($select[0])) {
					$readAdapter->select(self::getColumns(self::TABLE_MEMBER, $select[0]));
				}
				if(isset($select[1])) {
				    $readAdapter->select(self::getColumns(self::TABLE_MEMBER_INFO, $select[1]));
				}
			} else {
				$readAdapter->select(self::TABLE_MEMBER.".*")
				    ->select(self::getColumns(self::TABLE_MEMBER_INFO, array('ma_id','membership_number','name','sex','dob','telephone','qq','email','identity_card','address')))
				    ->select(self::getColumns(self::TABLE_MEMBER_INFO, array('password','custom1','custom2','custom3','custom4','custom5')));
			}
  
			$query = $readAdapter->get();
			$UserInfo = $query->row();
			//下面的增加为了优化系统自身没有本系统的会员卡号，做的补提
			if(!empty($UserInfo)&&!$UserInfo->mem_card_no){
				$mem_card_no = $this->create_member_no();
				$writeAdapter = $this->load->database('member_write',true);
				$writeAdapter->update(self::TABLE_MEMBER, array('mem_card_no'=>$mem_card_no), array('mem_id' => $UserInfo->mem_id));
				$UserInfo->mem_card_no = $mem_card_no;
			}
			return $UserInfo;
		}
		return false;
	}
	
	/**
	 * 获取所有会员详细信息列表
	 * @param string $limit
	 * @param string $offsetssssss
	 * @param unknown $select
	 */
	public function getMemberDetailList($limit=null, $offset=null, $where=array(), $select=array(), $must_appid=true)
	{
		$readAdapter = $this->load->database('member_read',true);
		
		$readAdapter->from(self::TABLE_MEMBER)->join(self::TABLE_MEMBER_INFO, self::TABLE_MEMBER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left');
		
		if($limit !== null && $offset !== null) {
			$readAdapter->limit($limit,$offset);
		} elseif($limit !== null) {
			$readAdapter->limit($limit);
		}
		
		if($select) {
			if(isset($select[0])) {
				$readAdapter->select(self::getColumns(self::TABLE_MEMBER, $select[0]));
			}
			if(isset($select[1])) {
				$readAdapter->select(self::getColumns(self::TABLE_MEMBER_INFO, $select[1]));
			}
		} else {
			$readAdapter->select(self::TABLE_MEMBER.".*")
			    ->select(self::getColumns(self::TABLE_MEMBER_INFO, array('ma_id','membership_number','name','sex','dob','telephone','qq','email','identity_card','address')))
			    ->select(self::getColumns(self::TABLE_MEMBER_INFO, array('custom1','custom2','custom3')));
		}
		
		if($where) {
			if(isset($where['card_number'])) {
				$readAdapter->like(self::TABLE_MEMBER_INFO.'.membership_number', $where['card_number']);
			}
			
			if(isset($where['level'])) {
				$readAdapter->where(self::TABLE_MEMBER.'.level',$where['level']);
			}
		}
		
		if($must_appid) {
			if($appid=getAppid()) {
				$readAdapter->where(self::TABLE_MEMBER.'.inter_id', $appid);
			}
		}
		
		$readAdapter->order_by(self::TABLE_MEMBER.'.mem_id', 'DESC');

		$query = $readAdapter->get();
		return $query->result();
	}
	
	/**
	 * 查询的字段加上表前缀
	 * @param unknown $table
	 * @param unknown $columns
	 * @return string
	 */
	static protected function getColumns($table,$columns)
	{
		$select = array();
		foreach($columns as $column) {
			$select[] = $table.".".$column;
		}
		return implode(",",$select);
	}
	
	/**
	 * 根据会员ID查询会员资料
	 * @param string $mem_id
	 * @return unknown
	 */
	protected function getMemberInfoByMemId($mem_id)
	{
		if($mem_id) {
			$readAdapter = $this->load->database('member_read',true);
			$query = $readAdapter->get_where(self::TABLE_MEMBER_INFO, array('mem_id' => $mem_id));
			return $query->row();
		} else {
			return false;
		}
	}
	
	/**
	 * 检查OPENID是否正确格式
	 * @param unknown $openid
	 * @return boolean
	 */
	protected function checkOpenid($openid) {
		if(empty($openid)) {
			return false;
		}
		
		if(strlen($openid) != self::OPENID_LENGTH) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 检测数据是否合法
	 * @param array $data
	 * @param bool $new 是否新建数据
	 * @param unknown $date
	 * 
	 * @return bool
	 */
	public  function checkData(&$data, $new = false)
	{
		$this->_filterData($data);
		
// 		if(isset($data['code']) && !preg_match("/^[a-zA-Z0-9]*$/i",$data['code'])) {
// 			throw new Exception("CODE字段含有非法字符!");
// 		}
		
		if(isset($data['growth'])) {
			$data['growth'] = intval($data['growth']);
		}
		
		if(isset($data['balance'])) {
			$data['balance'] = floatval($data['balance']);
		}
		
		if(isset($data['bonus'])) {
			$data['bonus'] = intval($data['bonus']);
		}
		
		if(isset($data['level'])) {
			$data['level'] = intval($data['level']);
		}
		
// 		if(isset($data['activate_begin_time']) && !preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/i",$data['activate_begin_time'])) {
// 			throw new Exception("日期格式错误!");
// 		}
		
// 		if(isset($data['activate_end_time']) && !preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/i",$data['activate_end_time'])) {
// 			throw new Exception("日期格式错误!");
// 		}
		
		if(isset($data['is_active'])) {
			if($data['is_active'] != 1) {
				$data['is_active'] = 0;
			}
		}

		if($new) {
			$data['create_time'] = date('Y-m-d H:i:s',time());
		}
		
		return true;
	}
	
	/**
	 * 检测会员数据是否合法
	 * @param array $data
	 *
	 * @return bool
	 */
	public function checkInfoData(&$data)
	{
		$this->_filterData($data,'info');
		
		$this->load->helper('security');
	
		if(isset($data['membership_number']) && !empty($data['membership_number']) && !preg_match("/^[a-zA-Z0-9]*$/i",$data['membership_number'])) {
			throw new Exception("会员卡编号membership_number含有非法字符!");
		}
		
		if(!isset($data['sex'])) {
				$data['sex'] = 0;
		}
		
		if(isset($data['dob'])) {
			$data['dob'] = intval($data['dob']);
		}
		
		if(isset($data['telephone']) && !preg_match("/^[0-9\-]*$/i",$data['telephone'])) {
			throw new Exception("手机号码非法!");
		}
	
		if(isset($data['qq'])) {
			$data['qq'] = intval($data['qq']);
		}
		
// 		if(isset($data['email']) && !preg_match("/^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i",$data['email'])) {
// 			throw new Exception("邮件地址非法!");
// 		}
		
// 		if(isset($data['identity_card']) && !is_numeric($data['identity_card'])) {
// 			throw new Exception("身份证非法!");
// 		}
		
		if(isset($data['name'])) {
			$data['name'] = $this->security->xss_clean($data['name']);
		}
		
		if(isset($data['address'])) {
			$data['address'] = $this->security->xss_clean($data['address']);
		}
		
		if(isset($data['custom1'])) {
			$data['custom1'] = $this->security->xss_clean($data['custom1']);
		}
		
		if(isset($data['custom2'])) {
			$data['custom2'] = $this->security->xss_clean($data['custom2']);
		}
		
		if(isset($data['custom3'])) {
			$data['custom3'] = $this->security->xss_clean($data['custom3']);
		}
		
		return true;
	}
	
	/**
	 * 过滤不需要的字段
	 * @param array
	 * @param string type member|info
	 */
	protected function _filterData(&$data,$type='member')
	{
		$type=='member' ? $fields = $this->table_member_field : $fields = $this->table_member_info_field;
	
		$toDelKeys = array_diff(array_keys($data),$fields);
	
		if($toDelKeys) {
			foreach($toDelKeys as $key) {
				unset($data[$key]);
			}
		}
	}

	/**
	*	检测用户的信息是否在当下微信公众号下面是否有重复的记录
	*	@param 	$inter_id 微信公众号的ID
	*	@param $card_no 会员卡号
	*/
	public function checkUserIsOn( $inter_id , $card_no ){
		$readAdapter = $this->load->database('member_write',true);
		$queryCardInfo = $readAdapter->from(self::TABLE_MEMBER_INFO)->where(array('membership_number' => $card_no,'inter_id'=>$inter_id))->get()->row();
		if(isset($queryCardInfo->mem_id) && $queryCardInfo->mem_id ){
			//$queryUserInfo = $readAdapter->from(self::TABLE_MEMBER)->where(array('mem_id'=>$queryCardInfo->mem_id,'inter_id'=>$inter_id))->get()->row();
			$queryUserInfo = $readAdapter->from(self::TABLE_MEMBER)->where(array('mem_id'=>$queryCardInfo->mem_id))->get()->row();
			if(isset($queryUserInfo->is_login) && $queryUserInfo->is_login ){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
		return true;
	}
	
	/**
	 * 取员工/业主会员信息(碧桂园业主注册审核)
	 * @author OuNianfeng
	 * @since 2016-03-27
	 */
	public function getUnAuthMembers($inter_id,$limit=100, $offset=null,$key=''){
		$readAdapter = $this->load->database('member_read',true);
		$readAdapter->cache_off();
// 		if($must_appid && $appid=getAppid()) {
// 			$readAdapter->where('inter_id', $appid);
// 		}
		$param = array();
		$sql = 'SELECT ma.ma_id,ma.mem_id,ma.membership_number,ma.`name`,ma.telephone,ma.member_type,ma.audit,ma.owner_name,ma.owner_no,m.create_time FROM (SELECT * FROM iwide_member_additional WHERE inter_id=? AND member_type>0';
		$param[] = $inter_id;
		if(!empty($key)){
			$sql .=' AND (membership_number LIKE ? OR name LIKE ? OR telephone LIKE ?)';
			$param[] = "%$key%";
			$param[] = "%$key%";
			$param[] = "%$key%";
		}
		$sql .=') ma INNER JOIN iwide_member m ON m.mem_id=ma.mem_id WHERE m.inter_id=?';
		
		$param[] = $inter_id;
		$sql .= ' ORDER BY m.update_time DESC';
		if($limit !== null && $offset !== null) {
			$sql .= ' limit ?,?';
			$param[] = $offset;
			$param[] = $limit;
		} elseif($limit !== null) {
			$sql .= ' limit ?';
			$param[] = $limit;
		}
		$query = $readAdapter->query($sql,$param);
		if($this->input->get('debug'))
			echo $readAdapter->last_query();
		return $query->result();
	}
	function getUnAuthMembersCount($inter_id,$key=''){
		$readAdapter = $this->load->database('member_read',true);
		$param = array();
		$sql = 'SELECT COUNT(*) nums FROM (SELECT * FROM iwide_member_additional WHERE inter_id=? AND member_type>0';
		$param[] = $inter_id;
		if(!empty($key)){
			$sql .=' AND (membership_number LIKE ? OR name LIKE ? OR telephone LIKE ?)';
			$param[] = "%$key%";
			$param[] = "%$key%";
			$param[] = "%$key%";
		}
		$sql .=') ma INNER JOIN iwide_member m ON m.mem_id=ma.mem_id WHERE m.inter_id=?';
		$param[] = $inter_id;
		$query = $readAdapter->query($sql,$param)->row_array();
		if($this->input->get('debug'))
			echo $readAdapter->last_query();
		// $query = $readAdapter->from(self::TABLE_MEMBER)->get();
		return $query['nums'];
	}
	
	/**
	*	增加一个会员的基础信息
	*	@param inter_id 微信账号的ID信息
	*	@param openid 微信用户的账号信息
	*	@return false/true
	*/
	public function addUserInfo( $inter_id , $OpenId ){
		if(!$inter_id) return false;
		if(!$OpenId) return false;
		$readAdapter = $this->load->database('member_read',true);
		$writeAdapter = $this->load->database('member_write',true);
		$queryUserInfo = $readAdapter->from(self::TABLE_MEMBER)->where(array('openid' => $OpenId,'inter_id'=>$inter_id))->get()->row();
		if( !isset($queryUserInfo->mem_id) ){
			$addInfoId = $writeAdapter->insert(self::TABLE_MEMBER,array('inter_id'=>$inter_id,'openid'=>$OpenId,'mem_card_no'=>$this->create_member_no()) );
			$addInfoId = $writeAdapter->insert_id();
			$selectListInfo = $readAdapter->from(self::TABLE_MEMBER_INFO)->where(array('mem_id' => $addInfoId,'inter_id'=>$inter_id))->get()->row();
			if(!isset($selectListInfo->ma_id)){
				$addInfoId = $writeAdapter->insert(self::TABLE_MEMBER_INFO,array('inter_id'=>$inter_id,'mem_id'=>$addInfoId) );
			}else{
				return false;
			}
		}else{
			$selectListInfo = $readAdapter->from(self::TABLE_MEMBER_INFO)->where(array('mem_id' => $queryUserInfo->mem_id,'inter_id'=>$inter_id))->get()->row();
			if(!isset($selectListInfo->ma_id)){
				$addInfoId = $writeAdapter->insert(self::TABLE_MEMBER_INFO,array('inter_id'=>$inter_id,'mem_id'=>$queryUserInfo->mem_id) );
			}else{
				return false;
			}
		}
		return true;
	}

	//查询一个微信卡券领取的信息（临时）
	public function selectWxcardInfo($openid,$inter_id,$cardId){
		$readAdapter = $this->load->database('member_read',true);
		$select_wxcard_info = $readAdapter->from('member_wxcard_info')->where(array('openid' => $openid,'inter_id'=>$inter_id,'wxcard_id'=>$cardId))->get();
		return $select_wxcard_info->num_rows();
	}

	//增加一个微信卡券领取的信息（临时）
	public function addWxcardInfo($openid,$inter_id,$cardId ){
		$writeAdapter = $this->load->database('member_write',true);
		$addInfoId = $writeAdapter->insert('member_wxcard_info',array('openid' => $openid,'inter_id'=>$inter_id,'wxcard_id'=>$cardId) );
		return $addInfoId;
	}

	//查询用户的关注信息并获取是否有分销信息
	public function getUserWxStaff( $openid , $inter_id ){
		$readAdapter = $this->load->database('member_read',true);
		$staff_info = $readAdapter->from('iwide_fans_sub_log')->where(array('openid' => $openid,'inter_id'=>$inter_id,'event'=>2))->get();
		$staff_info_result = $staff_info->result();
		$data['source'] = isset($staff_info_result[0]->source)?$staff_info_result[0]->source:"";
		$staff = $readAdapter->from('iwide_hotel_staff')->where(array('qrcode_id' => $data['source'],'inter_id'=>$inter_id))->get();
		$staff_result = $staff->result();
		$data['name'] = isset($staff_result[0]->name)?$staff_result[0]->name:"";
		$data['hotel_name'] = isset($staff_result[0]->hotel_name)?$staff_result[0]->hotel_name:"";
		return $data;
	}



    public function getMemberByOpenIdInterId($openid,$inter_id,$select=array())
    {
        if(!$this->checkOpenid($openid)) {
            throw new Exception("OpenId格式错误!");
        } else {
            $readAdapter = $this->load->database('member_read',true);

            if($select) {
                $readAdapter->select(implode(',',$select));
            }
            $query = $readAdapter->from(self::TABLE_MEMBER)->where(array('openid' => $openid,'inter_id'=>$inter_id))->get();
            return $query->row();
        }

        return false;
    }

    public function updateMemberByOpenIdInterid($data)
    {
        $mem_id = $data['memid'];

        try {
            if($this->checkData($data)) {


                    $writeAdapter = $this->load->database('member_write',true);
                    unset($data['openid']);
                    $result = $writeAdapter->update(self::TABLE_MEMBER, $data, array('mem_id' => $mem_id));
                    return $result;

            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return false;
    }


    public function updateMemberInfoByOpenIdInterid($data,$arr)
    {

        try {

            $getMemberInfo=$this->getMemberInfoByMemIdInterId($arr['mem_id'],$arr['inter_id']);

            if($getMemberInfo){


                    $writeAdapter = $this->load->database('member_write',true);

                    $result = $writeAdapter->update(self::TABLE_MEMBER_INFO, $data, array('mem_id' => $arr['mem_id'],'inter_id' => $arr['inter_id']));

                    return $result;

            }else{


                     $writeAdapter = $this->load->database('member_write',true);

                     $data['mem_id'] = $arr['mem_id'];
                     $data['inter_id']=$arr['inter_id'];
                     $data['identity_card']=$arr['identity_card'];

                     return $writeAdapter->insert(self::TABLE_MEMBER_INFO,$data);


            }


        } catch (Exception $e) {
            echo $e->getMessage();exit;
            throw new Exception($e->getMessage());
        }

        return false;
    }


    protected function getMemberInfoByMemIdInterId($mem_id,$inter_id)
    {
        if($mem_id) {
            $readAdapter = $this->load->database('member_read',true);
            $query = $readAdapter->get_where(self::TABLE_MEMBER_INFO, array('mem_id' => $mem_id,'inter_id'=>$inter_id));
            return $query->row();
        } else {
            return false;
        }
    }


    public  function getMemberInfoByCardNum($VipCard,$inter_id)    //江门柏丽
    {
        if($VipCard) {
            $readAdapter = $this->load->database('member_read',true);
            $query = $readAdapter->get_where(self::TABLE_MEMBER_INFO, array('membership_number' => $VipCard,'inter_id'=>$inter_id));
            return $query->row();
        } else {
            return false;
        }
    }

}