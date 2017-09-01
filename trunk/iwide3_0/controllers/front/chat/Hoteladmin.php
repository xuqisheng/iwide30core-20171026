<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hoteladmin extends CI_Controller {

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
	
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		
		
		
		$method = $this->router->method;
		if (in_array($method, array('login','logincheck','logout','_nosession'))) {
				
		}
		else {
			if ( !$this->session->userdata('userinter_id') ) {
				$this->_nosession('userinter_id');
				echo '<script type="text/javascript">top.location.href = "/index.php/chat/hoteladmin/login";</script>';
			}
		}
		
		$this->inter_id = $this->session->userdata('userinter_id');
		
	}
	
	public function index()
	{
		
	}
	
	public function showforminfo() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$id = intval($this->input->get('iad'));
		
		$action = $this->input->get("action");
		$iid = intval($this->input->get("iid"));
		
		if ($action == 'check' && $id) {
			$iid = intval($this->input->post("id"));
			$status = intval($this->input->post("s"));
			$reason = $this->input->post("reason");
			$reason = htmlspecialchars($reason);
			$reason = addslashes($reason);
			if ($iid) {
				if ($status == 3 || $status == 0) {
					$datacheck['status'] = $status;
					$datacheck['checkresult'] = $reason;
					$this->db->update('custom_info',$datacheck,array('id'=>$iid));
					echo 1;
				}
			}
				
			die();
		}
		
		if ($action == 'del' && $iid) {
				
			$this->db->delete('custom_info',array('id'=>$iid));
			$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."custom_info where cid=".$id);
			$ret = $query->result_array();
			$count = 0;
			if ($ret) {
				$count = $ret['0']['count'];
			}
			$datacount['addnum'] = $count;
			$this->db->update('custom',$datacount,array('id'=>$id));
		
		}
		
		
		$data['data'] = array();
		$data['datainput'] = array();
		if ($id) {
			$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_input where cid=".$id);
			$ret = $query->result_array();
			if ($ret) {
				$data['datainput'] = $ret;
			}
		
			$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_info where cid=".$id." order by id desc");
			$retd = $query->result_array();
			if ($retd) {
				$data['data'] = $retd;
			}
		}
		
		$this->load->view('chat/default/hoteladmin/show_info'.$id,$data);
		
	}	
	
	
	public function login() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$data['inter_id'] = $this->input->get('inter_id');
		$data['iad'] = $this->input->get('iad');
		
		$this->load->view('chat/default/hoteladmin/show_login',$data );
	
	}

	public function logout() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
	
		$newdata = array(	
				'userinter_id'  => ''	
		);
	
		$this->session->set_userdata($newdata);
		echo '<script type="text/javascript">alert("退出成功！");location.href = "/index.php/chat/hoteladmin/login";</script>';
	}
	
	public function logincheck() {
		
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());

		$name = $this->input->post("username",true);
		$password = md5($this->input->post("password",true));
		$submit = $this->input->post("submit",true);
		$to = $this->input->post("to",true);	

		//print_r($name);
		
		if ( !$name ) {
			echo '<script type="text/javascript">alert("用户名错误或用户名不能用当前地址登录！");history.go(-1);</script>';die();
		}
	
	
		if (isset($submit)) {
				
			if ( '32bc2e4b4e6ff598da5ace80381bbade' != $password ) {//iwidecn888
				echo '<script type="text/javascript">alert("密码有误！");location.href = "/index.php/chat/hoteladmin/login";</script>';die();
			}
			
			$newdata = array(
					'userinter_id'  => $name
			);

			$this->session->set_userdata($newdata);

			$this->input->set_cookie("userinter_id", $name, 60*60*24*30);	
			
			
			echo '<script type="text/javascript">location.href = "'.$to.'";</script>';
		}
	
	
	}

	private function _nosession($item){
		if (!$item) {
			return false;
		}
		else {
			$newdata = array(
				$item  => ''
			);
			$this->session->set_userdata($newdata);
		}
		
	}
}
