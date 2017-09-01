<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfectinfo extends MY_Front 
{
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function index()
	{
		$openid = $this->getOpenId();

		$this->load->model('member/imember');
		$member = $this->imember->getMemberInfoByOpenId($openid,$this->inter_id,0);

		if(isset($member)) {
			$data['member'] = $member;
		} else {
			$data = array();
		}
		
		$this->load->model('member/iconfig');
		$fields = $this->iconfig->getConfig('register_fields',true,$this->inter_id);
		if($fields) {
		    $data['fields'] = $this->iconfig->getConfig('register_fields',true,$this->inter_id)->value;
		} else {
			$data['fields'] = array();
		}

		//$this->load->model('jssdk');
		//$data['signpackage'] = $this->jssdk->getSignPackage();
        $this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);

        if($this->inter_id=='a421641095' || $this->inter_id=='a455510007'){ //a455510007为速8
            $data['inter_id']=$this->inter_id;
        }

		$this->display('member/perfectinfo', $data);
	}
	
	public function save()
	{
		$openid = $this->getOpenId();
		if(!isset($openid)) {
			redirect('member/center');
			return;
		}
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid,$this->inter_id,0);
		
		if(!$member || !isset($member->mem_id)) {
			redirect('member/center');
			return;
		}
		
		$data = $this->input->post();
		$data['inter_id'] = $this->inter_id;
		$data['mem_id'] = $member->mem_id;
		$this->imember->updateStatus($openid,1);

		$this->imember->addMemberInfo($openid, $data);
		//完善资料送券   START
			$this->load->model('member/igetcard','igetcard');
			$this->load->model('member/irule','irule');
			$rules = $this->irule->getRewardByRules('all', 'member_focuks_after', $data['inter_id'] , array('hotel_register'=>1));
		
			foreach ($rules as $key => $value) {
				foreach ($value['reward']['card'] as $ke => $val) {
					$this->igetcard->userGetCard($openid,$data['inter_id'],array($val['ci_id']=>$val['quantity']),null,array('rule_id'=>$key));
				}
			}
		//完善资料送券   END
		redirect('member/center/userinfo?mem_id='.$member->mem_id);
	}
	
// 	public function resetpwd()
// 	{
// 	    $openid = $this->getOpenId(base_url("index.php/member/perfectinfo"));
// 		//$openid = 'oo89wt2NIJ3N0Y_fxenOfFaoiStI';		
// 		if(!isset($openid)) {
// 			redirect('member/center');
// 			return;
// 		}

// 		$mem_id = $this->input->post('mem_id');
		
// 		$this->load->model('member/imember');
// 		$member = $this->imember->getMemberById($mem_id);
		
// 		if(!isset($member) || (isset($member) && !empty($member->custom3))) {
// 			redirect('member/center');
// 			return;
// 		}

// 		if($member && $member->openid==$openid) {
// 			$memInfo = $this->imember->getMemberInfoByMemId($mem_id);
// 			$data['info'] = $memInfo;
// 			$this->load->view('member/resetpwd', $data);
// 		} else {
// 	        redirect('member/center');
// 		}
// 	}
	
// 	public function savepwd()
// 	{	
// 		$openid = $this->getOpenId(base_url("index.php/member/perfectinfo"));
// 		//$openid = 'oo89wt2NIJ3N0Y_fxenOfFaoiStI';
// 		$ma_id = $this->input->post('ma_id');
// 		$passwd = $this->input->post('custom3');
// 		$passwd =  md5($passwd);
		
// 		$this->load->model('member/imember');
		
// 		$this->imember->updateMemberInfoById($ma_id,array('custom3'=>$passwd));
		
// 		redirect('member/center');
// 	}
}