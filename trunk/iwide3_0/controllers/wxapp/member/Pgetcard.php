<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pgetcard extends MY_Front 
{

	
	protected function getOpenId()
	{
		//$res = $this->session->userdata($this->session->userdata('inter_id').'openid');
		return $this->openid;
	}

	public function index()
	{
		$openid = $this->getOpenId();
		$data['rule_id'] = $this->input->get('rid');
		if(!$data['rule_id']) $data['rule_id'] = $this->session->userdata('wxcard_id');
		if(!$data['rule_id']) $data['message'] = '券的信息有误,请亲退出重新进入！';
	    $this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
		if(empty($openid) || $openid == '' || isset($openid)==FALSE)
		{
			redirect("member/pgetcard?id=".$this->inter_id."&rid=".$data['rule_id']);
		}
		
		if(!$member || !isset($member->mem_id)) {
			$member = $this->imember->initMember($openid,array(),$this->inter_id);
		}
 		
		$this->load->model('member/irule');
		$rules = $this->irule->getRewardByRules('member', 'member_focuks_after', $this->inter_id, array('focus'=>1));
		$cards = array();
		if(isset($rules[$data['rule_id']])){
			if(isset($rules[$data['rule_id']]['reward']['card'])){
				$cards = $rules[$data['rule_id']]['reward']['card'];
			}
		}
	
		if(count($cards)){
			$this->load->model('member/icard');
			$dcard = $this->icard->getCardsByWhere(array('ci_id'=>array_keys($cards)));
			foreach($dcard as $card) {
				$data['card'][$card->ci_id] = $card;
			}
			$data['rule_card'] = $cards;
		} else {
			$data['card'] = $data['rule_card'] = array();
		}
		
		// 		if(empty($data['rule_card']) || $data['rule_card'] == '' || isset($data['rule_card']) == FALSE)
		// 		{
		// 			redirect("member/pgetcard?id=".$this->inter_id."&rid=".$data['rule_id']);exit;
		// 		}
		
		$this->load->model('member/getcardrecord');
		$rule = $this->irule->getRule($data['rule_id']);

		foreach($data['card'] as $card) {
			$record = $this->getcardrecord->getRecordByMemRecord($member->mem_id,$card->ci_id,'rule_id_'.$data['rule_id'],$this->inter_id);
			if($record && isset($record->rrg_id)) {
				if($rule && isset($rule->rule_id)) {
					if(isset($rule->condition['exec_num']) && !empty($rule->condition['exec_num'])) {
						if($record->num>=$rule->condition['exec_num']){
							$data['yet'] = true;
							break;
						}
					}
				}
			}
		}

		$this->load->model('wx/access_token_model');
		$this->load->model('wx/Publics_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$this->display('member/pgetcard', $data);

	}

	public function addCard()
	{
		//$rule_id  = $this->input->post('rid');
		$rule_id  = $this->input->get('rid');
		if(!$rule_id) $rule_id = $this->session->userdata('wxcard_id');
		if(empty($rule_id)) {
			echo json_encode(array('code'=>1,'errmsg'=>'系统错误!'));
		} else {
			$this->load->model('member/irule');
			$rules = $this->irule->getRewardByRules('member', 'member_focuks_after', $this->inter_id, array('focus'=>'1'));

			$cards = array();
			if(isset($rules[$rule_id])) {
				if(isset($rules[$rule_id]['reward']['card'])) {
					$cards = $rules[$rule_id]['reward']['card'];
				}
			}
			$writeAdapter = $this->load->database('member_write',true);
			
			try {
				$writeAdapter->trans_begin();
				$this->load->model('member/igetcard');
				foreach($cards as $ci_id=>$cinfo) {
					$result = $this->igetcard->userGetCard($this->getOpenId(),$this->inter_id,array($ci_id=>$cinfo['quantity']),null,array('rule_id'=>$rule_id));
					if(isset($result['code']) && $result['code']==1) {
						throw new Exception($result['errmsg']);
					}
				}
				if ($writeAdapter->trans_status() === FALSE) {
					$writeAdapter->trans_rollback();
					echo json_encode(array('code'=>0,'errmsg'=>'添加卡券失败!'));
				} else {
					$writeAdapter->trans_commit();
					echo json_encode(array('code'=>0,'errmsg'=>'添加卡券成功!'));
				}
			} catch(Exception $e) {
				$writeAdapter->trans_rollback();
				echo json_encode(array('code'=>1,'errmsg'=>$e->getMessage()));
			}
		}
	}
}