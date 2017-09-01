<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Front {

    public $openid  ='';
    public $inter_id='';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 带智能检测用户关注情况，视情况进行高级授权跳转
	 */
	public function _get_wx_userinfo()
	{
	    $this->load->model('wx/publics_model');
	    $fans= $this->publics_model->get_fans_info( $this->openid );
	
	    if( !$fans || empty($fans['nickname']) || empty($fans['headimgurl']) ){
	        $userinfo= $this->publics_model->get_wxuser_info($this->inter_id, $this->openid );
	
	        if( isset($userinfo['subscribe']) && $userinfo['subscribe']==0 ){
	            //微信返回的信息显示没有关注，则进行高级授权验证
	            if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx' )
	                $refer =  'http://'. $_SERVER ['HTTP_HOST']. $_SERVER ['REQUEST_URI'] ;
	            else
	                $refer =  'http://'. $_SERVER ['SERVER_NAME']. $_SERVER ['REQUEST_URI'] ;
	            $inter_id= $this->inter_id;
	            $refer= urlencode($refer);
	            redirect(  site_url("Public_oauth/index?scope=snsapi_userinfo&id={$inter_id}&refer={$refer}")  );
	
	        } else {
	            $this->publics_model->update_wxuser_info($this->inter_id, $this->openid );
	            return $userinfo;
	        }
	
	    } else {
	        return $fans;
	    }
	}
	
	/**
	 * 接受管理员扫码授权请求
	 */
	public function admin_authid()
	{
	    $code= $this->input->get( 'code' );
	    $key= $this->input->get( 'key' );
/** 解决首次打开，经微信授权跳转后丢失 get参数的问题  */
	    if( !$code ) $code= $this->session->userdata('authid_code');
	    if( !$key ) $key= $this->session->userdata('authid_key');
/** 解决首次打开，经微信授权跳转后丢失 get参数的问题  */
	    
	    if($code){
	        $this->_get_wx_userinfo();
	        
	        $this->load->helper('encrypt');
	        $encrypt_util= new Encrypt();
	        $json= $encrypt_util->decrypt( base64_decode( $key) );
	        $data= (array) json_decode($json);
	        	
	        if( isset($data['key']) && isset($data['inter_id']) && isset($data['id']) && $data['key']=='fixed' ){
	            $this->load->model('core/priv_admin_authid', 'admin_authid');
	            $model= $this->admin_authid;
	            $r= $model->find_record($this->openid);
	            if( $r ){
	                $id= $r['auth_id'];
	                if($r['status']!= $model::STATUS_CHECK){
	                    //对于非审核状态的记录做处理
    	                $model->save_record($this->openid, $data['id'], $data['inter_id']);
	                    die('<h1 style="color:green;">已成功请求，请耐心等候审核。</h1>');
    	                
	                } else {
	                    die('<h1 style="color:green;">该账号已经通过授权，无需重复操作。</h1>');
	                }
	                
	            } else {
	                $model->save_record($this->openid, $data['id'], $data['inter_id']);
	                die('<h1 style="color:green;">成功新增授权请求。</h1>');
	            }
	            
	        } else {
	            die('<h1 style="color:red;">参数解密失败，请联系系统管理员。</h1>');
	        }
	        	
	    } else {
	        die('<h1 style="color:red;">缺少code参数，微信授权请求失败。</h1>');
	    }
	
	}
	
}
