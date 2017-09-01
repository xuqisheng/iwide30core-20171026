<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
	    
	     echo 'Welcome to iwide.cn.';
	}
	
	public function json(){
		echo json_encode(array("aaa"=>111));
	}
	
	function ac(){
		$this->load->model('app/App_config_model','ac');
		var_dump($this->ac->get_hotel_config($_GET['id'], json_decode($_GET['types'],TRUE), $_GET['channel'], $_GET['module'],0));
	}
}
