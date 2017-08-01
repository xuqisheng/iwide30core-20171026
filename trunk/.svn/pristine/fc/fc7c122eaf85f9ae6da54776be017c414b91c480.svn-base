<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basicinfo extends MY_Admin 
{
	public function index()
	{
		$this->load->model('member/iconfig');
		$info = $this->iconfig->getConfig('basicinfo',true,$this->session->get_admin_inter_id());
		
		if($this->session->get_admin_inter_id()) {
			$this->load->model('wx/Publics_model');
			$data['public'] = $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
		}

		if($info) {
			$data['basicinfo'] = $info->value;
		} else {
			$data['basicinfo'] = array();
		}
		
		$membermodel = $this->iconfig->getConfig('membermodel',false,$this->session->get_admin_inter_id());
		if($membermodel) {
			$data['membermodel'] = $membermodel->value;
		}
		
		$data['icos'] = $this->getIcocss();

		$data['module'] = $this->getModule();
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
	public function edit_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/basicinfo');
			exit;
		}
		
		$data = $this->input->post();
		
		$info = array();
		foreach($data['group'] as $k=>$v) {
			if(empty($data['name'][$k])) continue;
			if($data['module'][$k] == 'link' && empty($data['link'][$k])) continue;
			
			$info[$v][] = array('module'=>$data['module'][$k],'name'=>$data['name'][$k],'link'=>$data['link'][$k],'icocss'=>$data['icocss'][$k]);
		}
		ksort($info);
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('basicinfo',$info,true,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		
		redirect('member/basicinfo');
	}
	
	public function edit_memodel_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/basicinfo');
			exit;
		}
		
		$data = $this->input->post();
		
		$this->load->model('member/iconfig');
		$this->iconfig->addConfig('membermodel',$data['membermodel'],false,$this->session->get_admin_inter_id());
		
		$this->session->put_success_msg('成功保存信息!');
		redirect('member/basicinfo');
	}
	
	protected function getModule()
	{
		return array(
			'member'      =>'会员登录资料',
			'membercharge'=>'会员登录充值',
			'qrcode'      =>'我的二维码',
			'address'     =>'我的地址',
			'cardstore'   =>'卡券商城',
			'link'        =>'超链接',
			'vcard'       =>'我的储值卡',
			'mycard'      =>'我的卡券',
			'mycharge'    =>'充值消费记录',
			'mybonus'     =>'积分消费记录',
			'yzvcard'     =>'远洲储值卡',
		);
	}
	
	protected function getIcocss()
	{
		return array(
			'ui_ico1'  => '会员卡',
			'ui_ico2'  => '礼品商城',
			'ui_ico3'  => '积分商城',
			'ui_ico4'  => '储值卡',
			'ui_ico5'  => '二维码',
			'ui_ico6'  => '我的地址',
			'ui_ico8'  => '记录',
			'ui_ico9'  => '会员权益',
			'ui_ico10' => '会员卡2',
			'ui_ico11' => '积分',
			'ui_ico12' => '说明',
			'ui_ico13' => '消费记录',
			'ui_ico14' => '优惠券',
            'ui_ico15' => '我的订单',
            'ui_ico16' => '我的收藏',
            'ui_ico17' => '我的权益',
            'ui_ico18' => '我的消息',
            'ui_ico19' => '排行榜',
            'ui_ico20' => '分销中心',
            'ui_ico21' => '会员卡绑定',
            'ui_ico22' => '退出登录',
            'ui_ico23' => '预约订房',
            'ui_ico24' => '我的套票',
		);
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