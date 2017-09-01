<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonusdetail extends MY_Admin 
{
	protected $label_action= '积分明细';
	
	protected function main_model_name()
	{
		return 'member/admin/grid/gridbonus';
	}
	
	public function grid()
	{
		$this->load->model('member/consume');
		$inter_id= $this->session->get_admin_inter_id();
		
		if($inter_id == FULL_ACCESS) {
			$filter= array();
		} else if($inter_id) {
			$filter= array('inter_id'=>$inter_id );
		} else {
			$filter= array('inter_id'=>'deny');
		}
		 
		$this->_grid($filter);
	}
}