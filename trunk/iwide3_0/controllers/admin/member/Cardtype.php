<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cardtype extends MY_Admin
{
	protected function main_model_name()
	{
		return 'member/admin/grid/gridcardtype';
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
		$id = $this->input->get('ids');
		
		if($id) {
			$this->load->model('member/icard');
			$typeModel = $this->icard->getCardTypeById($id);
				
			if($typeModel) {
				$data['typemodel'] = $typeModel;
			}
		}
		
		$data['cardtypes'] = $this->getCardTypes();
		$data['id'] = $id;
		
		$html= $this->_render_content($this->_load_view_file('edit'), $data, false);
		
		echo $html;
	}
	
	/**
	 * 删除和批量删除
	 */
	public function delete()
	{
		try {
			$id = $this->input->get('ids');
			
			if(strpos($id,',') !== false) {
				$this->session->put_error_msg('不能同时删除多个!');
			} elseif($id) {
				$this->load->model('member/icard');
				$result = $this->icard->deleteCardType($id);
				
				if( $result ){
					$this->session->put_success_msg("删除成功");	
				}
			}
		} catch (Exception $e) {
			$message= '删除失败过程中出现问题！';
			//$message= $e->getMessage();
			$this->session->put_error_msg('删除失败');
		}
		$url= EA_const_url::inst()->get_url('*/*/grid');
		$this->_redirect($url);
	}
	
	public function edit_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
		
			redirect('member/membercat');
			exit;
		}
		
		$postData = $this->input->post();
		
		$this->load->model('member/icard');
		
		if(isset($postData['ct_id'])) {
			$this->icard->updateCardType($postData['ct_id'], $postData['type_name'], $postData['card_type'], $postData['is_vcard'], $postData['is_package']);
		} else {
			$this->icard->addCardType($postData['type_name'], $postData['card_type'], $postData['is_vcard'], $postData['is_package'],$this->session->get_admin_inter_id());
		}
		
		redirect('member/cardtype/grid');
	}
	
	protected function getCardTypes()
	{
		$this->load->model('member/icard');
	
		return $this->icard->getCardTypes();
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