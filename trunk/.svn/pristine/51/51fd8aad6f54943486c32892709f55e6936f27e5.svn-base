<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpayfinish extends CI_Controller 
{	
	public function chargepayfinish()
	{
		$postStr = file_get_contents ( 'php://input' );
// 		$this->load->model('member/iconfig');
		$inter_id = $this->uri->segment(4);
		if (isset($postStr) && !empty($postStr)){			
			$this->load->model ('pay/Pay_model' );
			$pay_paras=$this->Pay_model->get_pay_paras($inter_id);
			
			$this->load->model('member/sign');
			$sign = $this->sign->fromXml($postStr)->makeSign($pay_paras['key']);

			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			
			if((string)$postObj->sign != $sign) {
				exit;
			}
			
			if(((string)$postObj->return_code != 'SUCCESS') || ((string)$postObj->result_code != 'SUCCESS')) {
				exit;
			}

			if(isset($postObj->sub_openid)) {
				$openid = (string)$postObj->sub_openid;
			} else {
			    $openid = (string)$postObj->openid;
			}
			
			$order_number = (string)$postObj->out_trade_no;
			$transaction_id = (string)$postObj->transaction_id;

			$this->load->model('member/imember');
			$this->load->model('member/ichargeorder');
		
			
			$pay_log = array ();
			$pay_log ['inter_id'] = $inter_id;
			$pay_log ['openid'] = $openid;
			$pay_log ['out_trade_no'] = $order_number;
			$pay_log ['transaction_id'] = $transaction_id;
			$pay_log ['pay_time'] = time ();
			$pay_log ['rtn_content'] = $postStr;
			$pay_log ['type'] = 'weixin';
			$this->db->insert ( 'pay_log', $pay_log );
			
			
			$sql = 'SELECT m.openid FROM `iwide_member_charge_order` co,iwide_member m WHERE m.mem_id=co.mem_id AND co.order_number=? LIMIT 1';
			$res = $this->db->query($sql,array($order_number))->row();
			if(!empty($res->openid)){
				$openid = $res->openid;
			}
				
			$member = $this->imember->getMemberByOpenId($openid,$inter_id,0);
			$order = $this->ichargeorder->getChargeOrderByOrderNumber($order_number);
// 			$this->iconfig->addConfig('test',$postStr,true,$inter_id);
			if($member && isset($member->mem_id) && $order && ($order->paid==0)) {
				if(($member->mem_id == $order->mem_id) && ($order->order_number==$order_number)) {
					$data = array('cgo_id'=>$order->cgo_id, 'paid'=>1, 'transaction_id'=>$transaction_id);
					$this->ichargeorder->updateChargeOrder($data);
					
					$this->checkMemberLevel($openid,$order->amount,$inter_id);

					$add = $this->checkAdditionMoney($order->amount,$inter_id);
					if($add) {
						$amount = $order->amount+$add;
						$note = '充值'.$order->amount.'元,赠送'.$add.'元';
					} else {
						$amount = $order->amount;
						$note = '充值'.$order->amount.'元';
					}
					$this->load->model('member/imember');
					$this->imember->addBalance($openid,$amount,$note,$order->order_number,$inter_id,0);
					$member_info = $this->imember->getMemberInfoById($openid);
					//
					$this->load->model('plugins/Template_msg_model');
					$this->Template_msg_model->send_charge_msg(array('inter_id'=>$inter_id,'openid'=>$openid,'orderid'=>$order->order_number,'membership_number'=>$member_info->membership_number,'amount'=>$order->amount),'member_charge_completed');
				}
			}
		}
	}
	
	public function memberpayfinish()
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		$inter_id = $this->uri->segment(4);
		if (isset($postStr) && !empty($postStr)) {
			$this->load->model ('pay/Pay_model' );
			$pay_paras=$this->Pay_model->get_pay_paras($inter_id);

			$this->load->model('member/sign');
			$sign = $this->sign->fromXml($postStr)->makeSign($pay_paras['key']);

			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

			if((string)$postObj->sign != $sign) {
				redirect('member/corder/fail');
				exit;
			}

			if(((string)$postObj->return_code != 'SUCCESS') || ((string)$postObj->result_code != 'SUCCESS')) {
				redirect('member/corder/fail');
				exit;
			}

			if(isset($postObj->sub_openid)) {
				$openid = (string)$postObj->sub_openid;
			} else {
				$openid = (string)$postObj->openid;
			}
			$order_number = (string)$postObj->out_trade_no;
			$transaction_id = (string)$postObj->transaction_id;

			$this->load->model('member/imember');
			$this->load->model('member/icardorder');

			$member = $this->imember->getMemberByOpenId($openid,$inter_id,0);
			$order = $this->icardorder->getCardOrderByOrderNumber($order_number);
	
			if($member && isset($member->mem_id) && $order && ($order->paid==0)) {
				if(($member->mem_id == $order->mem_id) && ($order->order_number==$order_number)) {
					$this->icardorder->updateCardOrder(array('co_id'=>$order->co_id, 'paid'=>1, 'transaction_id'=>$transaction_id));
					
					$abresult = $this->afterVcardBuy($openid,$order->ci_id,$inter_id,$order_number);
					
					if($abresult['is_balance']) {
						if($abresult['is_balance_card']) {
							//生成卡券
							$this->load->model('member/igetcard');
							$result = $this->igetcard->userGetCard($openid, $inter_id, array($order->ci_id=>1));
							if($result && isset($result[0])) {
								$gc_id=$result[0];
							} else {
								$gc_id=0;
							}
							
							if(isset($abresult['balance']) && $abresult['balance']) {
								$amount = $order->amount+$abresult['balance'];
							} else {
								$amount = $order->amount;
							}
							
							$this->load->model('member/ivcard');
							$vcmodel = $this->ivcard->getInfoById($order->co_id, 'co_id');
							if($vcmodel && isset($vcmodel->vc_id)) {
								$this->ivcard->updateInfoById($vcmodel->vc_id, array('balance'=>$amount,'gc_id'=>$gc_id));
							} else {
								$this->ivcard->createInfo(array(
									'mem_id'    => $member->mem_id,
									'inter_id'  => $inter_id,
									'balance'   => $amount,
									'co_id'     => $order->co_id,
									'gc_id'     => $gc_id
								));
							}
						} else {
							if(isset($abresult['balance']) && $abresult['balance']) {
								$amount = $order->amount+$abresult['balance'];
							} else {
								$amount = $order->amount;
							}
							$this->imember->addBalance($openid,$amount,'购卡获得金额',$order_number, $inter_id,0);
						}
					} else {
						if(isset($abresult['balance']) && $abresult['balance']) {
							$this->imember->addBalance($openid,$abresult['balance'],'购卡获得金额',$order_number, $inter_id,0);
						}
					}
				}
			}
		}
	}
	
	protected function checkAdditionMoney($amount,$inter_id)
	{
		$this->load->model('member/iconfig');
		$charge_amount = $this->iconfig->getConfig('charge_amount',true,$inter_id);
		if($charge_amount) {
			foreach($charge_amount->value as $money=>$add) {
				if($money==$amount) {
					return $add;
				}
			}
		}
		return false;
	}
	
	protected function checkMemberLevel($openid,$amount,$inter_id)
	{
		$this->load->model('member/iconfig');
		$upgrade_balance = $this->iconfig->getConfig('upgrade_balance',true,$inter_id);
		 
		if($upgrade_balance) {
			if(!$upgrade_balance->value) return false;
		}
		 
		 
		 
		$level_balance = $this->iconfig->getConfig('level_balance',true,$inter_id);
		if($level_balance) {
			$data = $level_balance->value;
			arsort($data);
				
			foreach($data as $level=>$money) {
				if($amount>=$money) {
					$this->load->model('member/imember');
					$this->imember->updateLevel($openid,$level,$inter_id,0);
					break;
				}
			}
		}
	
		return false;
	}
	
	protected function afterVcardBuy($openid,$ci_id,$inter_id,$co_id)
	{
		$this->load->model('member/iconfig');
		$datas = $this->iconfig->getConfig('vcardset',true,$inter_id);

//        $this->db->insert('weixin_text',array('content'=>'mempay----1'.json_encode($datas),'edit_date'=>date('Y-m-d H:i:s')));
//
//        $this->db->insert('weixin_text',array('content'=>'mempay----2'.json_encode($ci_id),'edit_date'=>date('Y-m-d H:i:s')));
	
		if($datas) {
			$datas = $datas->value;
		} else {
			$datas = false;
		}
	
		$balance = $is_balance = $is_balance_card = 0;
		if($datas) {
			$data = array();
			foreach($datas as $id=>$val) {
				if($id==$ci_id) {
					$data = $val;
					break;
				}
			}

//            $this->db->insert('weixin_text',array('content'=>'mempay----3'.json_encode($data),'edit_date'=>date('Y-m-d H:i:s')));

			if(!empty($data)) {

//                $this->db->insert('weixin_text',array('content'=>'mempay----4'.json_encode($data),'edit_date'=>date('Y-m-d H:i:s')));

				if($data['mem_id'] != '-1') {
					$this->load->model('member/imember');
					$this->imember->updateLevel($openid,$data['mem_id'],$inter_id,0);
//                    $this->db->insert('weixin_text',array('content'=>'mempay----5','edit_date'=>date('Y-m-d H:i:s')));
				}
				if($data['type']=='bonus') {
					$this->imember->addBonus($openid, $data['value'], '购卡获得积分', $co_id, $inter_id,0);
//                    $this->db->insert('weixin_text',array('content'=>'mempay----6','edit_date'=>date('Y-m-d H:i:s')));
				} else {
					$balance = $data['value'];
				}
				$is_balance      = $data['is_balance'];
				$is_balance_card = $data['is_balance_card'];
			}
		}
		return array('balance'=>$balance,'is_balance'=>$is_balance,'is_balance_card'=>$is_balance_card);
	}
}