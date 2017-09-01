<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shake extends MY_Admin {

	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '酒店订单量报表';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'report/Shake_model';
	}
	
	public function grid()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		//print_r($filter);die;
		 
		$this->_grid($filter);
	}
	
	public function edit_post()
	{
		$infoid = $this->input->post('id');
		$data['baner'] = $this->input->post('baner');
		$data['inter_id'] = $this->input->post('inter_id');
		$data['title'] = $this->input->post('title');
		$data['dotimes'] = $this->input->post('dotimes');
		$data['sendmsg'] = $this->input->post('sendmsg');
		
		$infoid = intval($infoid);

		if ($infoid) {
			$this->db->update('custom_shake',$data,array('id'=>$infoid));
	
		} else {
			$this->db->insert('custom_shake',$data);
		}
	
		$this->session->put_success_msg('操作成功！');
		$this->_redirect(EA_const_url::inst()->get_url('*/*/index', array('ids'=> $cid ) ));
	
	}
	
}
