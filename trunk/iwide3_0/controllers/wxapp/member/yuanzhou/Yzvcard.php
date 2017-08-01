<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Yzvcard extends MY_Front 
{
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function bind()
	{
		$openid = $this->getOpenId();
	
		$this->load->model('member/imember');
		$member = $this->imember->getMemberDetailByOpenId($openid, $this->inter_id, 0);
		 
		if(!$member || !isset($member->mem_id)) {
			redirect('member/center');
		}
	
		if(!$member->is_active || !$member->is_login) {
			redirect('member/account/login');
		}
	
		if(!empty($member->custom2)) {
			redirect('member/crecord/balances');
		}
		
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
	
		$this->display('member/yzvcard', $data);
	}
	
	public function bindsave()
	{
		$openid = $this->getOpenId();
	
		$telephone = $this->input->post('telephone');
		$cardnumber = $this->input->post('cardnumber');
	
		$this->load->model('member/interface/yuanzhou');
	
		$data = array(
			'valuecardno' => $cardnumber,
			'phone'       => $telephone
		);
	
		$result = $this->yuanzhou->WxIsExistVipCardDoc($data);
	
		if($result) {
			$this->load->model('member/imember');
			$this->imember->updateMemberInfoCustom($openid,$cardnumber,2,$this->inter_id,0);
		}
		
		echo $result;
	}
	
	public function unbind()
	{
		$openid = $this->getOpenId();
	
		$this->load->model('member/imember');
		$memObject = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
	
		if($memObject && isset($memObject->mem_id)) {
			$result = $this->imember->updateMemberInfoCustom($openid,'',2,$this->inter_id,0);
			if($result) {
				echo json_encode(array('result'=>true,'message'=>"解除绑定储值卡成功！"));
				exit;
			}
		} else {
			echo json_encode(array('result'=>true,'message'=>"解除绑定储值卡成功！"));
			exit;
		}
	}
}