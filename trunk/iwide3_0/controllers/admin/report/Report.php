<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Admin {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function price() {

		$condition = '';
		
		$inter_id = $this->session->userdata['admin_profile']['inter_id'];
		if ($inter_id == 'ALL_PRIVILEGES') {
				
		}
		else {
			$condition = $condition." and inter_id='".$inter_id."'";
		}
			
		$common = array('100'=>'100元以下房','200'=>'100-200元房','300'=>'200-300元房','400'=>'300-400元房','500'=>'400-500元房','600'=>'500元以上房');
		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."hotel_orders where price<100".$condition);
		$ret = $query->result_array();
		$price['100'] = array('count'=>$ret['0']['count']);
		
		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."hotel_orders where price<200 and price>=100".$condition);
		$ret = $query->result_array();
		$price['200'] = array('count'=>$ret['0']['count']);

		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."hotel_orders where price<300 and price>=200".$condition);
		$ret = $query->result_array();
		$price['300'] = array('count'=>$ret['0']['count']);
		
		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."hotel_orders where price<400 and price>=300".$condition);
		$ret = $query->result_array();
		$price['400'] = array('count'=>$ret['0']['count']);
		
		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."hotel_orders where price<500 and price>=400".$condition);
		$ret = $query->result_array();
		$price['500'] = array('count'=>$ret['0']['count']);
		
		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."hotel_orders where price>=500".$condition);
		$ret = $query->result_array();
		$price['600'] = array('count'=>$ret['0']['count']);
		
		foreach ($price as $k => $v) {
			$dataitem[] = array('value'=>$v['count'],'name'=>$common[$k]);
			$showcommon[] = $common[$k];
		}

		$data = array('data'=>json_encode($dataitem),'showcommon'=>json_encode($showcommon));
		
		
		//print_r($data);
		
		
		echo $this->_render_content('report/report/price',$data);
	}
	
}
