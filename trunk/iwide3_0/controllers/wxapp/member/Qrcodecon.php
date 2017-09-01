<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Qrcodecon extends CI_Controller 
{
	public function index()
	{
		$this->load->helper ('phpqrcode');
		$url = urldecode($_GET["data"]);
        QRcode::png($url,false,'Q',30,10,true);
	}
}