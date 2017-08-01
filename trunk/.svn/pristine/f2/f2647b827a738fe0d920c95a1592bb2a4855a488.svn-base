<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Admin {
	private $inter_id = 'a111111111';
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->load->model ( 'wx/publics_model' );
		$user_ac_info = $this->publics_model->get_public_by_id($this->session->get_admin_inter_id());
		if(isset($user_ac_info['is_authed']) && $user_ac_info['is_authed'] == 2){
			$this->_redirect(EA_const_url::inst()->get_default_admin());
		}
		$account_info = $this->publics_model->get_public_by_id ( $this->inter_id );
		$this->load->model ( 'wx/access_token_model' );
		$pre_auth_res = $this->access_token_model->get_pre_auth_code ( $this->inter_id, $account_info ['app_id'] );
		$pre_auth_code = '';
		if(isset($pre_auth_res->pre_auth_code)){
			$pre_auth_code = $pre_auth_res->pre_auth_code;
		}
		$view_params = array ( 'app_id' => $account_info ['app_id'], 'pre_auth_code' => $pre_auth_code );
		echo $this->_render_content ( $this->_load_view_file ( 'guid' ), $view_params, TRUE );
	}
	public function call_back(){
		if($this->input->get('auth_code')){
// 			$this->load->helper('common');
			$this->load->model('wx/access_token_model');
			$this->load->model('wx/publics_model');
			$account_info = $this->publics_model->get_public_by_id($this->inter_id);
			$rs = $this->access_token_model->_get_authorizer_access_token($account_info['app_id'],$this->input->get('auth_code'),time() + intval($this->input->get('expires_in')), TRUE);
// 			$account_info = $this->get_public_by_id($this->inter_id);
// 			$component_access_token = $this->access_token_model->get_component_access_token($this->inter_id);
// 			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='.$component_access_token;
// 			$res = json_decode(doCurlPostRequest($url, json_encode(array('component_appid'=>$account_info['appid'],'authorization_code'=>$this->input->get('auth_code')))));
// 			$this->access_token_model->_get_authorizer_access_token($this->inter_id,$this->input->get('auth_code'));
// 			echo 'success';
			$user_info = $this->session->userdata('admin_profile');
			$user_info['inter_id'] = $rs['inter_id'];
			$this->session->set_userdata('admin_profile',$user_info);
			
			redirect(site_url('publics/publics/index').'?ids='.$rs['inter_id']);
		}else{
			$this->session->put_success_msg ( '授权失败，请重新授权!!!' );
			redirect(site_url('auth/index'));
		}
	}
	
// 	public function refresh(){
// 		$this->load->model('wx/access_token_model');
// 		var_dump($this->access_token_model->authorizer_access_token('a470628611'));
// 	}

	public function account_info(){
		$account_id = $this->input->get('a');
// 		$this->load->model('wx/pulics_model');
		$this->load->model('wx/access_token_model');
		$this->load->helper('common');
		$post_arr = array('component_appid'=>'wx26cd9be0e8b4e749','authorizer_appid'=>'wxd39cf32a6c4dd3b8');
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token='.$this->access_token_model->get_component_access_token();
		print_r(doCurlPostRequest($url, json_encode($post_arr)));
	}
	
	public function confirm_authorization(){
		$inter_id = $this->input->get('id');
		$this->load->model('wx/publics_model');
		$public_info = $this->publics_model->get_public_by_id($inter_id);
		$this->load->model('wx/access_token_model');
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_confirm_authorization?component_access_token='.$this->access_token_model->get_component_access_token();
		$this->load->helper ( 'common' );
		$post_str = json_encode ( array (
				'component_appid'       => 'wx26cd9be0e8b4e749',
				'authorizer_appid'      => $public_info['app_id'],
				'funcscope_category_id' => 8,
				'confirm_value'         => 1 
		) );
		print_r($post_str);
		print_r ( doCurlPostRequest ( $url, $post_str ) );
	}

	public function test(){
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=';
		$this->load->model('wx/access_token_model');
		$component_token = $this->access_token_model->get_component_access_token();
	}
}
?>