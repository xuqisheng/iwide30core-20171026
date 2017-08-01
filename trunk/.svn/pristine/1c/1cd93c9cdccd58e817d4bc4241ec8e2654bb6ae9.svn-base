<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formadmin extends MY_Front {

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
	 * map to /index.php/welcome/method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$this->load->library('session');
		if ($this->session->userdata('formusername') != 'admin') {
			echo '<script type="text/javascript">top.location.href = "/index.php/chat/formmember/login";</script>';
		}

		
		$this->display('chat/form.index',array());
	}
	
	public function left() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$this->display('chat/form.lefter',array());
	}
	
	public function main() {
		echo '信息总览';
	}
	
	
}
