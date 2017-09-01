<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promoterule extends MY_Admin 
{	
	protected $label_action= '规则列表';
	
	protected function main_model_name()
	{
		return 'member/admin/grid/gridpromoterule';
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();

		if($inter_id == FULL_ACCESS) {
			$filter= array();
		} else if($inter_id) {
			$filter= array('inter_id'=>$inter_id );
		} else {
			$filter= array('inter_id'=>'deny' );
		}
			
		$this->_grid($filter);
	}
	//修改和新增规则
	public function edit()
	{
        $ruleid = $this->input->get('ids');
        if($ruleid) {
        	$this->load->model('member/irule');
        	$data['oldrule'] = $this->irule->getRule($ruleid);
        	if($data['oldrule']->module) $data['oldrule']->module = array_flip($data['oldrule']->module);
        	if($data['oldrule']->handle) $data['oldrule']->handle = array_flip($data['oldrule']->handle);

        	if(isset($data['oldrule']->condition['hotel']) && !empty($data['oldrule']->condition['hotel'])) {
        		$data['oldrule']->condition['hotel'] = implode(',',$data['oldrule']->condition['hotel']);
        	}

        	$data['ruleid'] = $ruleid;
        	
        	$this->load->model('wx/Publics_model');
        	$data['public'] = $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }
        $this->load->model('member/icard');
		$this->load->model('member/iconfig');
		$this->load->model('member/actions');

		$data['cardtypearr'] = $this->icard->getCardTypeList(null,null,array('inter_id'=>$this->session->get_admin_inter_id()));
		$data['cardarr'] = $this->icard->getCardGroupByType(null,$this->session->get_admin_inter_id());
		
		if($this->iconfig->getConfig('level',true,$this->session->get_admin_inter_id())) {
			$data['members'] = $this->iconfig->getConfig('level',true,$this->session->get_admin_inter_id())->value;
		} else {
			$data['members'] = array();
		}
		
		$data['modules'] = $this->actions->getModules();
		$data['handles'] = $this->actions->getHandles();
		
		$data['products'] = $this->getProducts();
		$data['hotels'] = $this->getHotels();
		
		//modify from 20160218
		if( isset($data['oldrule']->condition['price_code']) && $data['oldrule']->condition['price_code'] ){
			$data['price_code1'] = implode(',',$data['oldrule']->condition['price_code']);
		}else{
			$data['price_code1'] = '';
		}
		
		if(isset($data['oldrule']) && isset($data['oldrule']->condition['hotel'])) {
			$data['pro_category'] = $this->getProCategory($data['oldrule']->condition['hotel'],2);
			$data['price_code'] = $this->getPriceCode($data['oldrule']->condition['hotel'],2);
		} else {
			$data['pro_category'] = $data['code_price'] = array();
		}
		$html= $this->_render_content($this->_load_view_file('edit'), $data, false);
		
		echo $html;
	}
	
	protected function getProducts()
	{
		return array(
		    '1'=>'商品1',
			'2'=>'商品2',
			'3'=>'商品3',
			'4'=>'商品4',
			'5'=>'商品5',
			'6'=>'商品6',
			'7'=>'商品7',
		);
	}
	
	public function getProCategory($hotel_id='',$type=1)
	{
		if($type==1) {
			$hotel_id = $this->input->get('hotel_id');
		}
	
		$this->load->model('hotel/Hotel_model');
		$rooms = $this->Hotel_model->get_hotel_rooms($this->session->get_admin_inter_id(),$hotel_id,1);
	
		$ret = array();
		foreach($rooms as $room) {
			$ret[$room['room_id']] = $room['name'];
		}
	
		if($type==1) {
			echo json_encode($ret);
		} else {
			return $ret;
		}
	}
	
	protected function getHotels()
	{
		$this->load->model('hotel/Hotel_model');
		$hotels = $this->Hotel_model->get_all_hotels($this->session->get_admin_inter_id(),1);
	
		$ret = array();
		foreach($hotels as $hotel) {
			$ret[$hotel['hotel_id']] = $hotel['name'];
		}
		return $ret;
	}
	
	public function getPriceCode($hotel_id='',$type=1)
	{
		if($type==1) {
			$hotel_id = $this->input->get('hotel_id');
		}
	
		$this->load->model ('hotel/Price_code_model');
		$resutl = $this->Price_code_model->get_price_codes($this->session->get_admin_inter_id());
	
		$ret = array();
		foreach($resutl as $r) {
			$ret[$r['price_code']] = $r['price_name'];
		}
	
		if($type==1) {
			echo json_encode($ret);
		} else {
			return $ret;
		}
	}
	
	public function edit_post()
	{		
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
			
			redirect('member/promoterule');
			exit;
		}
		
		$data = $this->input->post();

		$reward = array();
		if(isset($data['card_id']) && count($data['card_id'])) {
			foreach($data['card_id'] as $id) {
				$reward['card'][$id]=array('ci_id'=>$id,'quantity'=>$data['card_num'][$id],'restriction'=>$data['restriction'][$id]);
			}
		}
		
		if($data['bonus_add']) $reward['bonus']['add'] = $data['bonus_add'];
		if($data['bonus_mul']) $reward['bonus']['mul'] = $data['bonus_mul'];
		if($data['custom_balance']) $reward['balance']['equal'] = $data['custom_balance'];
		if($data['balance_min']) $reward['balance']['min'] = $data['balance_min'];
		if($data['balance_max']) $reward['balance']['max'] = $data['balance_max'];
		
		foreach($data['describe'] as $val) {
			if($val) {
				$reward['describe'][] =  $val;
			}
		}
		
		$condition = array();
		if(isset($data['hotel_checkout']) && $data['hotel_checkout']==1)          $condition['hotel_checkout'] = 1;
		if(isset($data['hotel_register']) && $data['hotel_register']==1)          $condition['hotel_register'] = 1;
		if(isset($data['pay_online']) && $data['pay_online']==1)                  $condition['pay_online'] = 1;
		if(isset($data['pay_offline']) && $data['pay_offline']==1)                $condition['pay_offline'] = 1;
		if(isset($data['consume_completed']) && $data['consume_completed']==1)    $condition['consume_completed'] = 1;
		if(isset($data['focus']) && $data['focus']==1)                            $condition['focus'] = 1;
		
		if(isset($data['consume_balance_up']) && ($data['consume_balance_up']==1) && !empty($data['balance_up'])) $condition['consume_balance_up']=$data['balance_up'];
		if(isset($data['consume_bonus_up']) && ($data['consume_bonus_up']==1) && !empty($data['bonus_up']))       $condition['consume_bonus_up']=$data['bonus_up'];
		if(isset($data['consume_goods_up']) && ($data['consume_goods_up']==1) && !empty($data['goods_up']))       $condition['consume_goods_up']=$data['goods_up'];
		
		if(isset($data['member'])) $condition['member'] = $data['member'];
		
		//关注
		if(!isset($condition['focus'])) {
			if(isset($data['price_code'])) $condition['price_code'] = $data['price_code'];
			
			if(isset($data['category'])) $condition['pro_category'] = $data['category'];
			if(isset($data['hotel']) && !empty($data['hotel'])) {
				$condition['hotel'] = explode(',',$data['hotel']);
			}
		}
		//价格代码
		if(isset($data['price_code'])) $condition['price_code'] = $data['price_code'];
		//分类代码
		if(isset($data['category'])) $condition['pro_category'] = $data['category'];
		//酒店代码
		if(isset($data['hotel']) && !empty($data['hotel'])) {
				$condition['hotel'] = explode(',',$data['hotel']);
			}
		//var_dump($data['price_code']);
		if(isset($data['activity_time_type']) && $data['activity_time_type']==1) {
			$activity_time_type=1;
			$activity_time_begin=$data['activity_time_begin'];
			$activity_time_end=$data['activity_time_end'];
		} else {
			$activity_time_type=0;
		}
		
		if(isset($data['restriction']) && !empty($data['restriction']) && !empty($data['res_num'])) {
			$condition['restriction'] = array($data['restriction']=>$data['res_num']);
		}
		
		if(isset($data['exec_num'])) $condition['exec_num'] = $data['exec_num'];
		
		$udata = array(
			'rule_name'=>$data['rule_name'],
			'inter_id'=>$this->session->get_admin_inter_id(),
			'module'=>$data['module'],
			'handle'=>$data['handle'],
			'reward'=>$reward,
			'condition'=>$condition,
			'activity_product_type'=>$data['activity_product_type'],
			'activity_time_type'=>$activity_time_type
		);

		if(isset($data['product_id'])) $udata['product'] = $data['product_id'];
		
		$udata['is_active'] = intval($data['is_active']);
		
		if(isset($activity_time_begin)) $udata['activity_time_begin'] = $activity_time_begin;
		if(isset($activity_time_end))   $udata['activity_time_end'] = $activity_time_end;
		
		$this->load->model('member/irule');

		if(!empty($data['rule_id'])) {
			$result = $this->irule->updateRule($data['rule_id'], $udata);
		} else {
			$result = $this->irule->createRule($udata);
		}

		// 		if($result && $data['activity_product_type']==2) {
		// 			if(!isset($data['product_id'])) $data['product_id'] = array();
		// 			if(!empty($data['rule_id'])) {
		// 				$this->irule->updateProductsByRuleId($data['rule_id'],$data['product_id']);
		// 			} else {
		// 				$this->irule->updateProductsByRuleId($result,$data['product_id']);
		// 			}
		// 		}

		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/promoterule');
	}
	
	protected function _checkInterId()
	{
		if(preg_match("/a[0-9]{9}/i",$this->session->get_admin_inter_id())) {
			return true;
		} else {
			return false;
		}
	}
}