<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ccharge extends MY_Front 
{
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function gocharge()
	{
		$data['iwide_openid'] = $this->getOpenId();
		$data['request_url'] = site_url('wxpay/member_pay')."?id=".$this->session->userdata('inter_id');
		$data['notify_url'] = site_url("member/cpayfinish/chargepayfinish/".$this->session->userdata('inter_id'));
		$data['success_url'] = base_url("index.php/member/crecord/balances");
		$data['fail_url'] = base_url("index.php/member/crecord/balances");
		//$data['order_number'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
		
// 		$this->load->model('jssdk');
// 		$data['signpackage'] = $this->jssdk->getSignPackage();
        $this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$data['token_name'] = $this->security->get_csrf_token_name();
		$data['token_value'] = $this->security->get_csrf_hash();
		
		$this->load->model('member/iconfig');
		$charge_amount = $this->iconfig->getConfig('charge_amount',true,$this->inter_id);
		if($charge_amount) {
			$data['charge_amount'] = $charge_amount->value;
		} else {
			$data['charge_amount'] = array();
		}

		$this->display('member/gocharge', $data);
	}
	
	public function createorder()
	{
		$openid = $this->getOpenId();
		
		$amount = $this->input->post('amount');
		if(empty($amount)) {
			echo json_encode(array('result'=>0,'msg'=>'金额为空!'));
			exit;
		}
		
		$this->load->model('member/imember');
		$memberObject = $this->imember->getMemberByOpenId($openid);
		
		if($memberObject && isset($memberObject->mem_id)) {
			$data = array('mem_id'=>$memberObject->mem_id,'amount'=>$amount,'inter_id'=>$this->inter_id);
			$this->load->model('member/chargeorder');
			$id = $this->chargeorder->addChargeOrder($data);
			
			if($id) {
				$order = $this->chargeorder->getChargeOrderById($id);
				if($order) {
					echo json_encode(array('result'=>1,'order_number'=>$order->order_number));
					exit;
				}
			}
		}
		echo json_encode(array('result'=>0,'msg'=>'error!'));
		exit;
	}
	
	public function fail()
	{
		echo "充值失败!";
	}
}