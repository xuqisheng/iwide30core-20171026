<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crecord extends MY_Front 
{
	protected function getOpenId()
	{
		return $this->openid;
	}
	
	public function bonus()
	{
		$openid = $this->getOpenId();

        $this->load->model('wx/access_token_model');
		$data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		
		$this->load->model('member/imember');
		$data = $this->imember->getBonusRecords($openid, $this->inter_id, 0);

		$this->display('member/bonus/bonus', $data);
	}
	
	public function cards()
	{
		$openid = $this->getOpenId();
//2016-04-11 Edit by OuNianfeng 不更新卡包的卡券信息回到系统
// 		$this->load->model('member/igetcard');
// 		$this->igetcard->updateGetCardListByWxPackage($this->inter_id,$this->getOpenId());

//        $this->load->model('member/imember');
//        $data = $this->imember->couponCardList($openid,$this->inter_id);

        $this->load->library ( 'PMS_Adapter', array (
            'inter_id' => $this->inter_id,
            'hotel_id' => 0
        ), 'pmsa' );
        $data = $this->pmsa->couponCardList( array($openid,$this->inter_id));

        $data['inter_id'] = $this->inter_id;
        $this->display('member/cards/cardlist',$data);
	}
	
	public function carddetail()
	{
		$gc_id = $this->input->get('gc_id');
	    $this->load->model('member/getcard');
		$this->load->model('member/igetcard');
		$this->load->model('member/icard');
	
		$data['card'] = $this->igetcard->getCardById($gc_id);
		$data['detail']  = $this->icard->getCardById($data['card']->ci_id);
		
		$this->load->model('wx/Publics_model');
		$data['public'] = $this->Publics_model->get_public_by_id($this->inter_id);
		
		if(!empty($data['detail']->card_id) && $data['detail']->status=='CARD_STATUS_VERIFY_OK') {
			if($data['card']->status==Getcard::STATUS_WEIXIN_PACKAGE) {
				$data['card']->wxcard = true;
			} else {
			    $data['card']->addpackage = true;
			}
		}
	
		$this->display('member/carddt', $data);
	}
	
	public function balances()
	{
		$openid = $this->getOpenId();

		$this->load->model('member/imember');
		$data = $this->imember->getBalanceRecords($openid, $this->inter_id, 0);

        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->inter_id);

        $data['inter_id']   = $this->inter_id;
        
        if(!isset($data['data_title'])){
            $data['data_title'] = array();
        }
        if(!isset($data['data_record'])){
            $data['data_record'] = array();
        }

		$this->display('member/balances', $data);
	}
}