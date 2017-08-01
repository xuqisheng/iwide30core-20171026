<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cardstore extends MY_Front 
{	
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function index()
	{	
		$data['saler']  = $this->input->get('saler');
		
		$this->load->model('member/icard');
		$data['cards'] = $this->icard->getCardList(true,true);

		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->display('member/cardstore', $data);
	}
	
	public function detail()
	{		
		$ci_id = $this->input->get('ci_id');
		$data['saler']  = $this->input->get('saler');
		
		$this->load->model('member/icard');
		$data['card'] = $this->icard->getCardById($ci_id);
		
		$this->load->model('member/iconfig');
		$vcardata = $this->iconfig->getConfig('vcardset',true, $this->inter_id);
		if($vcardata) {
			foreach($vcardata->value as $id=>$d) {
				if($id==$ci_id) {
					$data['maxnum'] = $d['maxnum'];
					break;
				}
			}
			
			if(!isset($data['maxnum'])) $data['maxnum'] = 0;
		} else {
			$data['maxnum'] = 0;
		}

		$this->display('member/carddetail', $data);
	}
	
	public function myvcard()
	{
		$openid = $this->getOpenId();
		
		$this->load->model('member/igetcard');
		$this->igetcard->updateGetCardListByWxPackage($this->inter_id,$this->getOpenId());
		
		$this->load->model('member/igetcard');
		$data['cards'] = $this->igetcard->getMyVcards($openid);

		$this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->display('member/myvcard', $data);
	}
}