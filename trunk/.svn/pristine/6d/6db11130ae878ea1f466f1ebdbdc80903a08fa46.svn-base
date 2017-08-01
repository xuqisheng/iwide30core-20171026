<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superform extends MY_Front {

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
		$this->load->library('session');
		
		if ($this->session->userdata('formusername') != 'admin') {
			echo '<script type="text/javascript">top.location.href = "/index.php/chat/formmember/login";</script>';
		}
		/**
		 * 无权限则将不能进行下列所有操作
		 */
		/*权限接口文件*/
		
	}
	
	public function index()
	{
		echo '默认页';	    
	}
	
	public function show() {
		;
	}
	
	public function suform() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		$action = $this->input->get("action");
		$id = intval($this->input->get("iad"));
		if ($action == 'del' && $id) {
			$this->db->delete('custom',array('id'=>$id));
		}
		
		$query = $this->db->query("SELECT count(*) as count FROM ".$this->db->dbprefix."custom limit 1");
		$ret = $query->result_array();
		
		/****************************************/
		$this->load->helper('qfpages');
		$pageurl = '/index.php/chat/superform/suform?page={p}';
		$totals = intval($ret['0']['count']);
		$perpage = 20;
		$page = intval($this->input->get("page"));
		$nowpage = $page>1?$page:1;
		

		$pages = qfpages($totals,$perpage,$nowpage,$pageurl);
		$limit = $pages['limit'];
		
		
		$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom order by id desc limit ".$limit);
		$ret = $query->result_array();
		$data['data'] = $ret;
		$data['pages'] = $pages;
		$this->display('chat/form.show_form',$data);
	}
	
	public function addform() {
		$data = array();
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$submit = addslashes($this->input->post('submit'));
		$action = $this->input->get('action');
		$id = intval($this->input->get('iad'));
		if ($submit) {
			
			$data['title'] = $this->input->post("title");
			$data['keyword'] = $this->input->post('keyword');
			$data['intro'] = htmlspecialchars($this->input->post('intro'));
			$data['toppic'] = $this->input->post('toppic');
			
			$data['islimittime'] = $this->input->post('putlimittime');
			$data['isstarttime'] = $this->input->post('putstarttime');
			$data['isdaynum'] = $this->input->post('putdaynum');
			$data['istotalnum'] = $this->input->post('puttotalnum');
			$data['ischeck'] = intval($this->input->post('ischeck'));
			
			if ($data['islimittime'] == 1) {
				$data['limittime'] = $this->input->post('dlimittime');
			}
			else {
				$data['islimittime'] = 0;
			}
			
			if ($data['isstarttime'] == 1) {
				$data['starttime'] = $this->input->post('dstarttime');
			}
			else {
				$data['isstarttime'] = 0;
			}
			
			if ($data['isdaynum'] == 1) {
				$data['daynum'] = $this->input->post('ddaynum');
			}
			else {
				$data['isdaynum'] = 0;
			}
			
			if ($data['istotalnum'] == 1) {
				$data['totalnum'] = $this->input->post('dtotalnum');
			}
			else {
				$data['istotalnum'] = 0;
			}
			
			$data['template'] = $this->input->post('template');
			
			$data['discount'] = $this->input->post('ddiscount');
			
			$data['discount']=$this->array_remove_empty($data['discount']);
			$data['discount'] = serialize($data['discount']);
			
			$data['successtip'] = $this->input->post('successtip');
			$data['errtip'] = $this->input->post('errtip');
			$data['content'] = $this->input->post('content');
			
			$data['price'] = $this->input->post('dprice');
			
			$data['addtime'] = time();
			if (strlen($data['title'])<2) {
				echo 'err:1';die();
			}
			if (strlen($data['keyword'])<1) {
				echo 'err:2';die();
			}
			if (strlen($data['toppic'])<15) {
				echo 'err:3';die();
			}
			
			if ($action == 'upd' && $id) {
				$this->db->update('custom',$data,array('id'=>$id));
			}
			else {
				$this->db->insert('custom',$data);
			}
			
			header("location:/index.php/chat/superform/suform");
			die();
		}
		
		/**
		 * 默认值初始化，防止服务器版本不同而报warning.
		 */
		$data['data']['title'] = '';
		$data['data']['keyword'] = '';
		$data['data']['intro'] = '';
		$data['data']['toppic'] = '';
		$data['data']['successtip'] = '';
		$data['data']['errtip'] = '';
		$data['data']['content'] = '';
		
		$data['data']['islimittime'] = '';
		$data['data']['isdaynum'] = '';
		$data['data']['istotalnum'] = '';
		$data['data']['isstarttime'] = '';
		
		$data['data']['limittime'] = '';
		$data['data']['daynum'] = '';
		$data['data']['totalnum'] = '';
		$data['data']['starttime'] = '';
		$data['data']['price'] = '';
		
		$data['data']['discount'] = '';		
		$data['data']['template'] = '';
		$data['data']['ischeck'] = '';
		
		if ($id) {
		    $query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom where id=".$id);
		    $ret = $query->result_array();
		    if ($ret) {
		    	$data['data'] = $ret['0'];
		    }
		}
		
		$this->display('chat/form.show_addform',$data);
	}
	
	public function forminput() {
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$action = $this->input->get('action');
		$id = intval($this->input->get('iad'));
		

		$iid = intval($this->input->get("iid"));
		if ($action == 'del' && $iid) {
			$this->db->delete('custom_input',array('id'=>$iid));
			$action = 'show';
		}
		
		
		$ret = array();
		if ($id && $action == 'show') {
			$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_input where cid=".$id." order by listorder desc");
			$ret = $query->result_array();
		}
		$data['data'] = $ret;
		$this->display('chat/form.show_forminput',$data);
	}
	
	public function addinput() {
		$data = array();
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$submit = addslashes($this->input->post('submit'));
		$action = $this->input->get('action');
		$id = intval($this->input->get('iad'));
		$iid = intval($this->input->get('iid'));
		if ($submit) {
			
			$data['iname'] = $this->input->post("iname");
			$data['itype'] = $this->input->post('itype');
			$data['fieldmatch'] = htmlspecialchars($this->input->post('fieldmatch'));
			$data['filedoption'] = htmlspecialchars($this->input->post('filedoption'));
			$data['isempty'] = intval($this->input->post('isempty'));
			$data['isshow'] = intval($this->input->post('isshow'));
			$data['listorder'] = intval($this->input->post('listorder'));
			$data['errinfo'] = htmlspecialchars($this->input->post('errinfo'));
			$data['addtime'] = time();
			
			if (strlen($data['iname'])<1) {
				echo 'err:1';die();
			}
			if (strlen($data['itype'])<1) {
				echo 'err:2';die();
			}
			if ($data['itype'] == 'text' || $data['itype'] == 'textarea') {
				if (strlen($data['fieldmatch'])<3) {
					echo 'err:3';die();
				}
			}

			if ($action == 'upd' && $iid) {
				$this->db->update('custom_input',$data,array('id'=>$iid));
				$id = intval($this->input->get('cid'));
			}
			else {
				$data['cid'] = $id;
				$this->db->insert('custom_input',$data);
			}
				
			header("location:/index.php/chat/superform/forminput?action=show&iad=".$id);
			die();
		}
		
		
		
		$data['data']['iname'] = '';
		$data['data']['filedoption'] = '';
		$data['data']['itype'] = '';
		$data['data']['fieldmatch'] = '';
		$data['data']['listorder'] = '0';
		$data['data']['isshow'] = '';
		$data['data']['isempty'] = '';
		$data['data']['errinfo'] = '';
		if ($iid) {
			$query = $this->db->query("SELECT * FROM ".$this->db->dbprefix."custom_input where id=".$iid);
			$ret = $query->result_array();
			if ($ret) {
				$data['data'] = $ret['0'];
			}
		}
		
		$this->display('chat/form.show_addinput',$data);
	}
	
	public function showinfo() {
		
		$id = intval($this->input->get('iad'));
		
		$action = $this->input->get("action");
		$iid = intval($this->input->get("iid"));
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
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
		
		$this->display('chat/form.show_info',$data);
		
	}

	public function test() {
		
		$data = array();
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$query = "select * from ".$this->db->dbprefix."custom_info";
		
		
		$this->display('chat/form.show_info',$data);
		exit();
	}
	
	public function formcount() {
		$data = array();
		$data['csrf'] = array('name'=>$this->security->get_csrf_token_name(),'hash' =>$this->security->get_csrf_hash());
		
		$timedown = addslashes($this->input->get("timedown",true));
		$timeup = addslashes($this->input->get("timeup",true));
		$cid = intval($this->input->get("iad",true));
		$condition = '';
		if ($timedown && $timeup) {
			$timestart = $timedown." 00:00:00";
			$timestart = date("Ymd",strtotime($timestart));
			$timeend = $timeup." 23:59:59";
			$timeend = date("Ymd",strtotime($timeend)+100);
			$condition = $condition." where adddate<'".$timeend."' and adddate>='".$timestart."'";
		}
		else {
			$timestart = date("Ym01",time());
			$timeend = date('Ym01',strtotime("+1 month",strtotime($timestart)));
			$condition = $condition." where adddate<'".$timeend."' and adddate>='".$timestart."'";
		}
		if ($cid) {
			$condition = $condition." and cid='".$cid."'";
		}

		$query = $this->db->query("SELECT COUNT(*) AS count, adddate FROM ".$this->db->dbprefix."custom_info".$condition." GROUP BY adddate limit 1000");
		$ret = $query->result_array();
		
		$dt_start = strtotime($timestart);
		$dt_end = strtotime($timeend);
		while ($dt_start<$dt_end){
			$dateall[date('Ymd',$dt_start)] = 0;
			$dt_start = strtotime('+1 day',$dt_start);
		}
		
		foreach ($ret as $k => $v) {
			$reta[$v['adddate']] = $v['count'];
		}
		
		foreach ($dateall as $k => $v) {
			if(isset($reta[$k])){
				$dateall[$k]=$reta[$k];
			}
		}
		foreach ($dateall as $k => $v) {
			$datadate[] = date('md',strtotime($k));
			$datacount[] = $v;
		}
		
		$query = $this->db->query("SELECT COUNT(*) AS count FROM ".$this->db->dbprefix."custom_info".$condition." limit 1");
		$ret = $query->result_array();
		$data['countall'] = $ret['0']['count'];
		
		
		$today = " where cid='".$cid."' and adddate='".date("Ymd")."'";
		$query = $this->db->query("SELECT COUNT(*) AS count FROM ".$this->db->dbprefix."custom_info".$today." limit 1");
		$ret = $query->result_array();
		$data['counttoday'] = $ret['0']['count'];
		
		$yesterday = " where cid='".$cid."' and adddate='".date("Ymd",time()-24*60*60)."'";
		$query = $this->db->query("SELECT COUNT(*) AS count FROM ".$this->db->dbprefix."custom_info".$yesterday." limit 1");
		$ret = $query->result_array();
		$data['countyesterday'] = $ret['0']['count'];
		
		$data['count'] = json_encode($datacount);
		$data['adddate'] = json_encode($datadate);	
		
		$this->display('chat/form.show_count',$data);
	}
	
	private function array_remove_empty($arr, $trim = true) {

		$narr = array ();
		
		foreach ( $arr as $key => $value ) {
			if (is_array ( $value )) {
				$val = $this->array_remove_empty ( $value );
				if (count ( $val ) != 0) {
					$narr [$key] = $val;
				}
			} else {
				$value = trim ( $value );
				if ($value != '') {
					$narr [$key] = $value;
				}
			}
		}
		
		unset ( $arr );
		
		return $narr;
	}
}
