<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wxgetcard extends MY_Front 
{
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function addpackage()
	{
		$card_id = $this->input->get('card_id');
		$code = $this->input->get('code');
		
		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$data['cardpackage'] = $this->access_token_model->getCardPackage($card_id,$this->inter_id,$code);

		$this->display('member/wxgetcard',$data);
	}
	
// 	public function addpackage2()
// 	{
// 		$this->load->model('wx/access_token_model');
// 		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
	
// 		$data['cardpackage'] = $this->access_token_model->getCardPackage('pX3WojmllzHC1CKoVr0YI7vi3b6U',$this->inter_id,'1234567890aa');
	
// 		$this->display('member/wxgetcard',$data);
// 	}
}