<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EA_const_url extends EA_base
{
	public function __construct()
	{
	     return parent::__construct();
	}

	public static function inst($className=__CLASS__)
	{
		return parent::inst($className);
	}
	
	/**
	 * 
	 * echo EA_const_url::inst()->get_url('* / * /post', array('id'=>18, ), TRUE))
	 * @param unknown $route
	 * @param unknown $param
	 * @param string $token
	 * @return string
	 */
	public function get_url($route, $param=array(), $token=FALSE )
	{
	    $URI =& load_class('URI', 'core', NULL);
	    $segments= $URI->segments;
	    $module= $segments[1];
	    $controller= isset($segments[2])? $segments[2]: 'index';
	    $action= isset($segments[3])? $segments[3]: 'index';
	    
	    $routeArr = explode('/', $route);
	    if(isset($routeArr[0]) && $routeArr[0]=='*') $routeArr[0]= $module;
	    if(isset($routeArr[1]) && $routeArr[1]=='*') $routeArr[1]= $controller;
	    if(isset($routeArr[2]) && $routeArr[2]=='*') $routeArr[2]= $action;
	    $path= implode('/', $routeArr);
	    
	    $pam= '?';
	    if( is_array($param) ) {
	        foreach ($param as $k=>$v){
	            $pam.= $k. '='. $v. '&';
	        }
	        if($token){
	            $token_name= config_item('token_filter_name');
	            $pam.= $token_name. '='. do_hash($action);
	            
	        } else {
	            $pam= substr($pam, 0, -1);
	        }
	    } else {
	        $pam.= $param;
	    }
	    return site_url(). '/'. $path. $pam;    //http://domian.com/index.php/pppp
	    //return base_url(). $path. $pam;    //http://domian.com/pppp
	}

	public function get_front_url($inter_id, $route, $param=array(), $domain=NULL )
	{
	    $pam= '?';
	    if( is_array($param) ) {
	        foreach ($param as $k=>$v){
	            $pam.= $k. '='. $v. '&';
	        }
	        $pam= substr($pam, 0, -1);
	    } else {
	        $pam.= $param;
	    }

	    if($domain=='file'){
	    	$domain= file_site_url();

	    } elseif($domain=='front'){
	    	$domain= front_site_url($inter_id);

	    } else {
	    	$domain= front_site_url($inter_id);
	    }
		
		return $domain. '/index.php/'. $route. $pam;
	}
	
	public function get_base_url($p=NULL)
	{
	    /* const defind in @see MY_Model */
	    if($p=='image') return URL_IMG;
	    else if($p=='css') return URL_CSS;
	    else if($p=='js') return URL_JS;
		else 
		    return site_url();    //http://domian.com/index.php/pppp
		    //return base_url();    //http://domian.com/pppp
	}
	
	public function get_login_admin()
	{
		return $this->get_url('privilege/auth/login');
	}
	
	public function get_logout_admin()
	{
		return $this->get_url('privilege/auth/logout');
	}
	
	public function get_deny_admin()
	{
		return $this->get_url('privilege/auth/deny');
	}

	public function get_default_tab()
	{
	    // return '<i class="fa fa-dashboard"></i> <span> 概 览</span>';
	    // return '<i class="fa fa-list-alt"></i> <span> 会员数据 </span>';
	    return '<i class="fa fa-list-alt"></i> <span> 今日概览 </span>';
	}
	public function get_default_admin()
	{
		// return $this->get_url('privilege/auth/dashboard');
		// return $this->get_url('member/memberlist/grid');
		return $this->get_url('hotel/orders/index_one');
	}
	
	public function get_profile_admin()
	{
		return $this->get_url('privilege/adminuser/profile');
	}
	
	/**
	 * 微信公众号扫码第三方授权URL
	 * @return string
	 */
	public function get_account_auth(){
		return $this->get_url('publics/auth/index');
	}
	
}
