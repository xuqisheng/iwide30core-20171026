<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Session extends CI_Session
{
	static private $_registry = array();  //寄存器容器
	public $area= 'adminhtml';

	/**
	 * Register a new variable
	 * @param string $key
	 * @param mixed $value
	 * @param bool $graceful
	 */
	public static function register($key, $value, $graceful = false)
	{
		if (isset(self::$_registry[$key])) {
			if ($graceful) {
				return;
			}
			self::throwException('Registry key "'.$key.'" already exists');
		}
		self::$_registry[$key] = $value;
	}
	
	/**
	 * Unregister a variable from register by key
	 * @param string $key
	 */
	public static function unregister($key)
	{
		if (isset(self::$_registry[$key])) {
			if (is_object(self::$_registry[$key]) && (method_exists(self::$_registry[$key], '__destruct'))) {
				self::$_registry[$key]->__destruct();
			}
			unset(self::$_registry[$key]);
		}
	}
	
	/**
	 * Retrieve a value from registry by a key
	 * @param string $key
	 * @return mixed
	 */
	public static function registry($key)
	{
		if (isset(self::$_registry[$key])) {
			return self::$_registry[$key];
		}
		return null;
	}
	
	/**
	 * 登陆成功后 赋值会话
	 * @param Priv_admin $admin
	 * @return boolean
	 */
	public function admin_login(Priv_admin $admin, $acl)
	{
	    if( !$admin->has_data()){
	        return FALSE;
	        
	    } else {
    	    if( !$img=$admin->m_get('head_pic') )
    	        $img= $admin->get_default_head();

	        $this->allow_actions= $acl;
	        $this->admin_profile= array(
    	        'admin_id'=> $admin->m_get('admin_id'),
    	        'inter_id'=> $admin->m_get('inter_id'),
    	        'entity_id'=> $admin->m_get('entity_id'),
    	        'username'=> $admin->m_get('username'),
    	        'nickname'=> $admin->m_get('nickname'),
    	        'head_pic'=> $img,
    	        'update_time'=> $admin->m_get('update_time'),
    	        'role'=> $admin->role,
	        );
	        //print_r($this->admin_profile);die;
	        return TRUE;
	    }
	}
	public function reflash_profile($admin)
	{
	    if( !$img=$admin->m_get('head_pic') )
    	    $img= URL_MEDIA. '/admin_head_pic/default.png';
	    
	    $this->admin_profile= array(
	        'admin_id'=> $admin->m_get('admin_id'),
    	    'inter_id'=> $admin->m_get('inter_id'),
	        'entity_id'=> $admin->m_get('entity_id'),
	        'username'=> $admin->m_get('username'),
	        'nickname'=> $admin->m_get('nickname'),
    	    'head_pic'=> $img,
	        'update_time'=> $admin->m_get('update_time'),
        );
	    return TRUE;
	}
	
	/**
	 * 判断是否已经登陆
	 * @return boolean
	 */
	public function admin_is_login()
	{
	    return $this->allow_actions && $this->admin_profile ? TRUE: FALSE;
	}
	/**
	 * 注销登陆会话
	 * @return boolean
	 */
	public function admin_logout()
	{
        $this->unset_userdata('allow_actions');
        $this->unset_userdata('admin_profile');
        $this->unset_userdata('menu_array');
        return TRUE;
	}

	public function get_leftmenu()
	{
        return $this->menu_array;
	}
	
	public function set_leftmenu($menu)
	{
        $this->set_userdata('menu_array', $menu);
        return TRUE;
	}
	
	public function get_admin_actions($key=NULL)
	{
		return $this->allow_actions;
	}

	public function get_admin_profile($key=NULL)
	{
	    $profile= $this->admin_profile;
	    if(!$key){
	        return $profile;
	        
	    } else if($profile){
	        if(array_key_exists($key, $profile)){
	            return $profile[$key];
	        }
	    } else {
	        return FALSE;
	    }
	}
	public function set_admin_profile($profile)
	{
	    try {
            $this->set_userdata('admin_profile', $profile);
	        return TRUE;
	        
	    } catch (Exception $e) {
	        return FALSE;
	    }
	}
	public function get_admin_username()
	{
	    return $this->get_admin_profile('username');
	}
	public function get_admin_id()
	{
	    return $this->get_admin_profile('admin_id');
	}
	public function get_admin_head()
	{
	    return $this->get_admin_profile('head_pic');
	}
	public function get_admin_inter_id()
	{
	     $profile= $this->admin_profile;
	     if( $profile && isset($profile['inter_id']) ){
	         return $profile['inter_id'];
	     } else {
	         return FALSE;
	     }
	}
	public function get_temp_inter_id()
	{
	     $profile= $this->admin_profile;
	     if( $profile && isset($profile['temp_inter_id']) ){
	         return $profile['temp_inter_id'];
	     } else {
	         return FALSE;
	     }
	}
	/**
	 * 返回管理员对应的酒店ID，如：32,235,12 ，逗号分隔，FALSE则要根据 inter_id 来读取对应酒店
	 * @return Ambigous <NULL, Ambigous <Ambigous, NULL>, NULL>|boolean
	 */
	public function get_admin_hotels()
	{
	    $profile= $this->admin_profile;
	    if( $profile && isset($profile['entity_id']) ){
	        return $profile['entity_id'];
	    } else {
	        return FALSE;
	    }
	}
	
	/**
	 * 后台登陆验证码校验
	 * @param String $captcha
	 * @return boolean
	 */
	public function validate_captcha($captcha)
	{
	    return TRUE;
	}
	
    /**
     * @param string $string
     * @param string $type
     * @return MY_Session
     */
	public function push_message($string=null, $type='success_msg')
	{
	    if ($string!=null ) {
	        if( $this->flashdata($type) )
	            $old = $this->flashdata($type);
	        else
	            $old = array();
	        array_push($old, $string);
	        $this->set_flashdata($type, $old);
	    }
	    return $this;
	}
	public function register_message($string=null, $type='success_msg')
	{
	    if ($string!=null ) {
	        if( $this->registry($type) ){
	            $old = $this->registry($type);
	            
	        } else {
	            $old = array();
	        }
	        array_push($old, $string);
	        $this->register($type, $old);
	    }
	    return $this;
	}
	/**
	 * 将提示信息按找类型放入 session/ 寄存器中，场合有所不同：
	 *     放入会话中，适用于 页面产生跳转，内容会在下一次输出后被清除，缺点会对会话存储增加压力
	 *     放入寄存器，适用于本次页面输出，执行完毕后内容将会被清除。
	 * @param string $string   提示内容信息
	 * @param string $type     flash|register  
	 */
	public function put_success_msg($string=null, $type='flash')
	{
	    if($type=='flash') $this->push_message($string, 'success_msg');
	    else $this->register_message($string, 'success_msg');
	}
	public function put_notice_msg($string=null, $type='flash')
	{
	    if($type=='flash') $this->push_message($string, 'notice_msg');
	    else $this->register_message($string, 'notice_msg');
	}
	public function put_error_msg($string=null, $type='flash')
	{
	    if($type=='flash') $this->push_message($string, 'error_msg');
	    else $this->register_message($string, 'error_msg');
	}

	public function show_put_msg()
	{
	    $show ='';
	    $successName= 'success_msg';
	    $noticeName= 'notice_msg';
	    $errorName= 'error_msg';
	    /*
<div class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-check"></i> 操作成功!</h4>
  message...
</div>
<div class="alert alert-danger alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-ban"></i> 操作失败!</h4>
  message...
</div>
<div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-warning"></i> 温馨提醒：</h4>
  message...
</div>
	     */
	    if($this->flashdata($successName) || $this->registry($successName)) {
	        $show.= '<div class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-check"></i> 操作成功!</h4>';
	        $msg= (array) $this->flashdata($successName);
	        if (count($msg)>1) {
	            $show.= mb_substr(implode(', ', $msg), 0,-1, 'utf-8');
	        } else {
	            $show.= current($msg);
	        }
	        $msg= (array) $this->registry($successName);
	        if (count($msg)>1) {
	            $show.= mb_substr(implode(', ', $msg), 0,-1, 'utf-8');
	        } else {
	            $show.= current($msg);
	        }
	        $show.= '</div>';
	    }
	    if($this->flashdata($noticeName) || $this->registry($noticeName)) {
	        $show.= '<div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-warning"></i> 温馨提醒：</h4>';
	        $msg= (array) $this->flashdata($noticeName);
	        if (count($msg)>1) {
	            $show.= mb_substr(implode(', ', $msg), 0,-1, 'utf-8');
	        } else {
	            $show.= current($msg);
	        }
	        $msg= (array) $this->registry($noticeName);
	        if (count($msg)>1) {
	            $show.= mb_substr(implode(', ', $msg), 0,-1, 'utf-8');
	        } else {
	            $show.= current($msg);
	        }
	        $show.= '</div>';
	    }
	    if($this->flashdata($errorName) || $this->registry($errorName)) {
	        $show.= '<div class="alert alert-danger alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-ban"></i> 操作失败!</h4>';
	        $msg= (array) $this->flashdata($errorName);
	        if (count($msg)>1) {
	            $show.= mb_substr(implode(', ', $msg), 0,-1, 'utf-8');
	        } else {
	            $show.= current($msg);
	        }
	        $msg= (array) $this->registry($errorName);
	        if (count($msg)>1) {
	            $show.= mb_substr(implode(', ', $msg), 0,-1, 'utf-8');
	        } else {
	            $show.= current($msg);
	        }
	        $show.= '</div>';
	    }
	    return $show;
	}
	
}
