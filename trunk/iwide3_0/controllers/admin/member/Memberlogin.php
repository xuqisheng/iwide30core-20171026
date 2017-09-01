<?php
/**
*	
*	设置会员卡登陆配置文件
*	为了应对后期PMS的多种登陆形式，将对登陆需要的字段及参数做出配置来适应后期的各种	
*	@author Frandon <489291589@qq.com>
*	@version 3.1
*	@since 3.0
*	@copyright www.iwide.cn
*	@todo 从现有的3.0 更新为3.1,
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Memberlogin extends MY_Admin 
{	
	public function __construct()
	{
		parent::__construct();
	
		$this->initialize();
	}

	public function index()
	{
		$this->load->model('member/iconfig');
		$fields = $this->iconfig->getConfig('login_fields',true,$this->session->get_admin_inter_id());
		//echo $this->_load_view_file('edit');
		//var_dump($fields);
		if($fields) {
		    $data['fields'] = $this->iconfig->getConfig('login_fields',true,$this->session->get_admin_inter_id())->value;
		} else {
			$data['fields'] = array();
		}
		//var_dump($data);exit;
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

		echo $html;
	}
	
	public function edit_post()
	{		
		if(!$this->_checkInterId()) {
			$this->session->put_error_msg('公众号ID不对!');
				
			redirect('member/resetinfo');
			exit;
		}
		
		$data = $this->input->post();
		//var_dump($data);exit;
		$this->load->model('member/iconfig');
		$fields = $this->iconfig->getConfig('login_fields',true,$this->session->get_admin_inter_id());
		
		if($fields) {
		    $fields = $this->iconfig->getConfig('login_fields',true,$this->session->get_admin_inter_id())->value;
		} else {
			$fields = array();
		}
		//var_dump($data);
		foreach($fields as $field=>$value) {
			echo isset($data[$field.'_check']);

			if(isset($data[$field.'_check']) && $data[$field.'_check']==1 ){
				$fields[$field]['check']=1;
			}else{
				$fields[$field]['check']=0;
			}
			if(isset($data[$field]) && $data[$field]==1) {
				if(substr($field, 0, 6) == 'custom') {
					$fields[$field]['name']=$data[$field.'_name'];
				}
				$fields[$field]['must']=1;
			} else {
				$fields[$field]['must']=0;
			}
		}//exit;
		$res = $this->iconfig->addConfig('login_fields', $fields, true, $this->session->get_admin_inter_id());
		//var_dump($data,$res);exit;
		$this->session->put_success_msg('成功保存信息!');
		
		redirect("member/memberlogin");
	}
	
	protected function initialize()
	{
		$this->load->model('member/iconfig');
		$data = $this->iconfig->getConfig('login_fields',true,$this->session->get_admin_inter_id());
	
		if(empty($data)) {
			$info = array();
			$info['account'] = array(
				'name'=>'登录账号',
				'must'=>0,
				'check'=>0
			);
			$info['name'] = array(
				'name'=>'姓名',
				'must'=>0,
				'check'=>0
			);
			$info['telephone'] = array(
				'name'=>'手机号码',
				'must'=>0,
				'check'=>0
			);
			$info['email'] = array(
				'name'=>'Email',
				'must'=>0,
				'check'=>0
			);
			$info['password'] = array(
				'name'=>'登录密码',
				'must'=>0,
				'check'=>0
			);
			$info['picpwd'] = array(
				'name'=>'验证码',
				'must'=>0,
				'check'=>0
			);
			$info['identity_card'] = array(
				'name'=>'身份证号码',
				'must'=>0,
				'check'=>0
			);
			$info['custom1'] = array(
				'name'=>'自定义字段1',
				'must'=>0,
				'check'=>0
			);
			$info['custom2'] = array(
				'name'=>'自定义字段2',
				'must'=>0,
				'check'=>0
			);
			$info['custom3'] = array(
				'name'=>'自定义字段3',
				'must'=>0,
				'check'=>0
			);
			$info['custom4'] = array(
				'name'=>'自定义字段4',
				'must'=>0,
				'check'=>0
			);
			$info['custom5'] = array(
				'name'=>'自定义字段5',
				'must'=>0,
				'check'=>0
			);
				
			$this->iconfig->addConfig('login_fields',$info,true,$this->session->get_admin_inter_id());
		}
	}
	
	protected function _checkInterId()
	{
		if(preg_match("/a[0-9]{9}/i",$this->session->get_admin_inter_id())) {
			return true;
		} else {
			return false;
		}
	}
	
}