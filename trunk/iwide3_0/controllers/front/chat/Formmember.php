<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formmember extends MY_Front {

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
		
		if ( empty ( $this->openid ) ) {
			$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		}
		
		
		
		/**
		 * 验证权限接口
		 * 
		 */
		$this->load->library('session');
		$this->name = "admin";
		$this->password = "iwide168";
	}
	
	public function index()
	{
		echo '默认页';
	}
	
	public function login() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$this->display("chat/form.member_login",$data );
	}
	
	public function logincheck() {

		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$name = $this->input->post("name",true);
		$password = $this->input->post("password",true);
		$submit = $this->input->post("submit",true);
		
		
		
		if (isset($submit)) {
			if ($name == $this->name && $password == $this->password) {
				$newdata = array(
						'formusername'  => $this->name
				);
				
				$this->session->set_userdata($newdata);
				
				echo '<script type="text/javascript">alert("登录成功！");location.href = "/index.php/chat/formadmin";</script>';
				
			}
			else {
				echo '<script type="text/javascript">alert("用户名或密码不正确！");location.href = "/index.php/chat/formmember/login";</script>';
			}
		}
		
		
	}
	
	public function logout() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$newdata = array(
				'formusername'  => ''
		);
		
		$this->session->set_userdata($newdata);
		
		echo '<script type="text/javascript">alert("退出成功！");location.href = "/index.php/chat/formmember/login";</script>';
	}
}
