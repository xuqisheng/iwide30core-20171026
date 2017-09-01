<?php
class Upimg extends MY_Front {
	
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array ('form','url') );
	}
	
	function index() {
	
	}
	
	function do_upload() {
		
		$config['upload_path']      = './public/chat/uploads/';
		$config['allowed_types']    = 'gif|jpg|png';
		$config['max_size']     = 100000;
		$config['max_width']        = 1024;
		$config['max_height']       = 768;
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());	
			echo json_encode($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			echo json_encode($data);
		}
	}
	
}
?>