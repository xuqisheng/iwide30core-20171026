<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carduserule extends MY_Admin 
{	
	protected $label_action= '规则列表';
	
	protected function main_model_name()
	{
		return 'member/admin/grid/gridcardrule';
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
	
	public function edit()
	{	
		$cr_id = $this->input->get('ids');
		
		if($cr_id) {
			$data['cr_id'] = $cr_id;
			$this->load->model('member/icardrule');
// 			$data['oldproducts'] = $this->icardrule->getProductsByRuleId($cr_id);
			$data['oldrule'] = $this->icardrule->getRule($cr_id);
			
			if(isset($data['oldrule']->condition['hotel']) && !empty($data['oldrule']->condition['hotel'])) {
				$data['oldrule']->condition['hotel'] = implode(',',$data['oldrule']->condition['hotel']);
			}
		}
		
		$this->load->model('member/actions');
		$data['modules'] = $this->actions->getModules();
		
		$this->load->model('member/iconfig');
		$this->load->model('member/icard');
		
		$data['cards'] = $this->icard->getCardList();
		
		if($this->iconfig->getConfig('level',true,$this->session->get_admin_inter_id())) {
		    $data['members'] = $this->iconfig->getConfig('level',true,$this->session->get_admin_inter_id())->value;
		} else {
			$data['members'] = array();
		}
		
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
	
	public function edit_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/membercat');
			exit;
		}
		
		$data = $this->input->post();
		//奖励条件
		$condition = array();
		
		if(isset($data['consume_balance_up']) && ($data['consume_balance_up']==1) && !empty($data['balance_up']))       $condition['consume_balance_up'] = $data['balance_up'];
		if(isset($data['consume_bonus_up']) && ($data['consume_bonus_up']==1) && !empty($data['bonus_up']))             $condition['consume_bonus_up']   = $data['bonus_up'];
		if(isset($data['consume_product_up']) && ($data['consume_product_up']==1) && !empty($data['product_up']))       $condition['consume_product_up']   = $data['product_up'];
		
		if(isset($data['member'])) $condition['member'] = $data['member'];
		if(isset($data['category'])) $condition['pro_category'] = $data['category'];
		if(isset($data['hotel']) && !empty($data['hotel'])) {
			$condition['hotel'] = explode(',',$data['hotel']);
		}
		if(isset($data['price_code'])) $condition['price_code'] = $data['price_code'];
		if(isset($data['restriction'])) $condition['restriction'] = array($data['restriction']=>$data['res_num']);

		$udata = array(
			'ci_id'=>intval($data['ci_id']),
			'inter_id'=>$this->session->get_admin_inter_id(),
			'condition'=>$condition,
			'product_type'=>$data['product_type'],
			'module'=>$data['module']
		);
		
		if(isset($data['product_id'])) $udata['product'] = $data['product_id'];

		$this->load->model('member/icard');
		$card = $this->icard->getCardById($data['ci_id']);
		$udata['name'] = $card->title;
		$udata['is_active'] = intval($data['is_active']);
		
		$this->load->model('member/icardrule');
		
	    if(isset($data['cr_id']) && !empty($data['cr_id'])) {
			$result = $this->icardrule->updateRule($data['cr_id'], $udata);
		} else {
		    $result = $this->icardrule->createRule($udata);
		}
		
// 		if($result && $data['product_type']==2) {
// 			if(!isset($data['product_id'])) $data['product_id'] = array();
// 			if(!empty($data['cr_id'])) {
// 				$this->icardrule->updateProductsByRuleId($data['cr_id'],$data['product_id']);
// 			} else {
// 				$this->icardrule->updateProductsByRuleId($result,$data['product_id']);
// 			}
// 		}
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/carduserule');
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