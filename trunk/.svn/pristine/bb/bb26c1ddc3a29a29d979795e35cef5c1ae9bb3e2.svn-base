<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type:text/html;charset=utf-8");
class Dis_v12 extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	function index(){
		
		echo "test";
		
		
	}
	
	function file2base64(){
		try {
//            echo base64_encode(file_get_contents( $this->input->get('url')));
            echo base64_encode($this->curl_file_get_contents( $this->input->get('url')));
		}catch (Exception $e){
			echo 'error';
		}
	}
	
	function curl_file_get_contents($durl){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $durl);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_USERAGENT, '');
		curl_setopt($ch, CURLOPT_REFERER,'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}

}