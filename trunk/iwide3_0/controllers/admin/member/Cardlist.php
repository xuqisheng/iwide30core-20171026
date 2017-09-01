<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cardlist extends MY_Admin 
{	
	protected $label_action= '卡劵列表';
	
	protected function main_model_name()
	{
		return 'member/admin/grid/gridcard';
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

		$data['cardtypes'] = $this->getCardTypes(array('inter_id'=>$this->session->get_admin_inter_id()));
		$data['codetypes'] = $this->getCodeTypes();
		
		$this->load->model('member/icard');
		$data['card'] = $this->icard->getCardById($id);
		$data['id'] = $id;

		$html= $this->_render_content($this->_load_view_file('edit'), $data, false);
		
		echo $html;
	}
	
	public function edit_post()
	{
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
				
			redirect('member/cardlist/grid');
			exit;
		}
		
        $data = $this->input->post();
		$this->load->model('member/icard');
		
		if(isset($data['ci_id']) && !empty($data['ci_id'])) {
		    $card = $this->icard->getCardById($data['ci_id']);
		} else {
			$card = false;
		}
		
		$data['inter_id']=$this->session->get_admin_inter_id();

		if($card && isset($card->ci_id)) {
			$data= $this->_do_upload($data, 'logo');
				
			if(isset($data['logo'])) {
				if($card->logo_url) {
					//@unlink(APPPATH.$card->logo_url);
				}
				$data['logo_url'] = $data['logo'];
			}

			$this->icard->updateCard($card->ci_id,$data);
		} else {			
			$data= $this->_do_upload($data, 'logo');
			
			if(isset($data['logo'])) {
				$data['logo_url'] = $data['logo'];
			}
			
			$this->load->model('member/icard');
			$this->icard->createCard($data);
		}
		
		redirect('member/cardlist');
	}
	
	public function createWxCard()
	{
		$inter_id= $this->session->get_admin_inter_id();
		$ci_ids = $this->input->get('ci_id');
		
		$this->load->model('member/wxcard');
		$result = $this->wxcard->createCard($ci_ids[0],$inter_id);
		echo json_encode($result);
	}
	
	public function checkWxCardStatus()
	{
		$inter_id= $this->session->get_admin_inter_id();
		$ci_ids = $this->input->get('ci_id');
	
		$this->load->model('member/wxcard');
		$result = $this->wxcard->getCardDetail($ci_ids[0],$inter_id);
		
		if($result['error']) {
			echo $result['errmsg'];
		} else {
			if($result['errmsg']=='CARD_STATUS_NOT_VERIFY') {
				echo '待审核';
			} elseif($result['errmsg']=='CARD_STATUS_VERIFY_FAIL') {
				echo '审核失败';
			} elseif($result['errmsg']=='CARD_STATUS_VERIFY_OK') {
				echo '通过审核';
			} elseif($result['errmsg']=='CARD_STATUS_USER_DELETE') {
				echo '卡券被商户删除';
			} elseif($result['errmsg']=='CARD_STATUS_DISPATCH') {
				echo '在公众平台投放过的卡券';
			}
		}
		
		exit;
	}
	
// 	protected function upload($name)
// 	{
// 		$config['upload_path']      = UPLOAD_PATH.'card';
// 		$config['allowed_types']    = 'gif|jpg|png|jpeg';
// 		$config['encrypt_name']     = true;
// 		$config['max_size']         = 2048;
// 		$config['max_width']        = 1024;
// 		$config['max_height']       = 1024;
	
// 		$this->load->library('upload', $config);
	
// 		if ( ! $this->upload->do_upload($name))
// 		{
// 			$data = array('error' => $this->upload->display_errors());
// 		}
// 		else
// 		{
// 			$data = array('upload_data' => $this->upload->data());
// 		}
	
// 		return $data;
// 	}
	
	protected function getCardTypes($where=null)
	{
		$this->load->model('member/icard');
	
		return $this->icard->getCardTypeList(null,null,$where);
	}
	
	protected function getCodeTypes()
	{
		$this->load->model('member/icard');
	
		return $this->icard->getCodeTypes();
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