<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corder extends MY_Front 
{
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function addinfo()
	{
		$isinfo = true;
		
		$num = $this->input->post("num");
		$ci_id = $this->input->post("ci_id");
		$saler = $this->input->post("saler");
		
		$this->load->model('member/iconfig');
		$result = $this->iconfig->getConfig('vcardset',true,$this->inter_id);

		
		if($result) {
			foreach($result->value as $id=>$val) {
				if($id==$ci_id) {
					//if(!$val['is_balance']) $isinfo = false;
					break;
				}
			}
		}

		if($isinfo) {
			$data['num']   = $this->input->post("num");
			$data['ci_id'] = $this->input->post("ci_id");
			$data['saler'] = $this->input->post("saler");
			
			$this->load->model('wx/access_token_model');
			$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
			
			$this->display('member/vcardinfo', $data);
		} else {
			redirect("member/corder/addorder?num=".$num."&ci_id=".$ci_id."&saler=".$saler);
		}
	}
	
	public function saveinfo()
	{
		$openid = $this->getOpenId();
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
		
		if($member && isset($member->mem_id)) { 
			$num   = $this->input->post("num");
			$ci_id = $this->input->post("ci_id");
			$saler = $this->input->post("saler");
			
			$name          = $this->input->post("name");
			$telephone     = $this->input->post("telephone");
			$identity_card = $this->input->post("identity_card");
			
			$distribution_no = $this->input->post("distribution_no");
			
			$this->load->model('member/ivcard');
			$vc_id = $this->ivcard->createInfo(array(
				'mem_id'        =>$member->mem_id,
				'name'          =>$name,
				'telephone'     =>$telephone,
				'identity_card' =>$identity_card,
				'inter_id'      =>$this->inter_id,
				'distribution_no'      =>$distribution_no
			));
			
			if($vc_id) {
				redirect("member/corder/addorder?num=".$num."&ci_id=".$ci_id."&saler=".$saler."&vc_id=".$vc_id);
			} else {
				redirect('member/center');
			}
		} else {
			redirect('member/center');
		}
	}
	
	public function addorder()
	{
		$openid = $this->getOpenId();
		$num   = $this->input->get("num");
		$ci_id = $this->input->get("ci_id");
		$saler = $this->input->get("saler");
		$vc_id = $this->input->get("vc_id");
		
		if(empty($saler)) $saler=0;
		$num = intval($num);
		
		$this->load->model('member/icard');
		$this->load->model('member/imember');
		$card = $this->icard->getCardById($ci_id);
		$member = $this->imember->getMemberByOpenId($openid);
		
		if(!$card || !$member || !isset($member->mem_id)) {
			redirect('member/center');
		}
		
		$data = array(
			'mem_id'     => $member->mem_id,
			'ci_id'      => $card->ci_id,
			'unit_price' => $card->reduce_cost,
			'num'        => $num,
			'amount'     => $card->reduce_cost*$num,
			'saler'      => $saler
		);

		$this->load->model('member/icardorder');
		$cid = $this->icardorder->addCardOrder($data);
		
		if($vc_id) {
			$this->load->model('member/ivcard');
			$this->ivcard->updateInfoById($vc_id, array('co_id'=>$cid));
		}
		
		redirect('member/corder/pay?co_id='.$cid);
	}
	
	public function orderlist()
	{
		$openid = $this->getOpenId();
		
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
		
		$this->load->model('member/icardorder');
		$data['orders'] = $this->icardorder->getOrderDetailListByMemId($member->mem_id);

		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->display('member/orderlist', $data);
	}
	
	public function pay()
	{
		$openid = $this->getOpenId();
		
		$co_id = $this->input->get('co_id');
		
		$this->load->model('member/imember');
		$member = $this->imember->getMemberByOpenId($openid);
		
		$this->load->model('member/icard');
		$this->load->model('member/icardorder');
		
		$data['token_name'] = $this->security->get_csrf_token_name();
		$data['token_value'] = $this->security->get_csrf_hash();

	
		$data['order'] = $this->icardorder->getCardOrderById($co_id);
		$data['product'] = $this->icard->getCardById($data['order']->ci_id);
		$data['iwide_openid'] = $this->getOpenId();
		$data['request_url'] = site_url('wxpay/member_pay')."?id=".$this->inter_id;
		$data['notify_url'] = site_url("member/cpayfinish/memberpayfinish/".$this->inter_id);
		$data['success_url'] = base_url("index.php/member/corder/orderlist");
		$data['fail_url'] = base_url("index.php/member/corder/orderlist");

		$this->display('member/pay', $data);
	}
}