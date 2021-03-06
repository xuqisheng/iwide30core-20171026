<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memgetcardlist extends MY_Admin 
{			
	protected function main_model_name()
	{
		return 'member/admin/grid/gridgetcard';
	}
	
	public function grid()
	{
		$this->load->model('member/member');
		$inter_id= $this->session->get_admin_inter_id();
	
		if($inter_id == FULL_ACCESS) {
			$filter= array();
		} else if($inter_id) {
			$filter= array(Member::TABLE_MEMBER_INFO.'.inter_id'=>$inter_id );
		} else {
			$filter= array(Member::TABLE_MEMBER_INFO.'.inter_id'=>'deny' );
		}
			
		$this->_grid($filter);
	}
}