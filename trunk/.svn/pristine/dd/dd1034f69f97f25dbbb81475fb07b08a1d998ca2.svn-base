<?php

class MY_Admin extends MY_Controller {

	protected $sub_template= 'AdminLTE';   //当前所用皮肤
	
	//面包屑导航定义，统一管理
	protected $breadcrumb_html= '';
	protected $breadcrumb_array= '';
	protected $label_module= '';		//统一在 constants.php 定义
	protected $label_controller= '';	//在文件定义
	protected $label_action= '';		//在方法中定义
    public $is_ajax = false;

	public function __construct()
	{
		/** 写在此部分的所有方法，需充分考虑函数的稳定性以及执行效率  **/

		//访问入口判断
		!defined('WEB_AREA') OR WEB_AREA=='admin' OR exit('This script access dis-allowed');
		parent::__construct();

        //判断是否为ajax提交
        $this->is_ajax = ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false;

		if( !$this->_acl_filter() ){  //权限判断
		    $url= EA_const_url::inst()->get_deny_admin();
		    $this->_redirect($url);
		}
		if( !$this->_token_filter() ){  //后台token判断，返回错误调到后台dashborad默认页面
		    $url= EA_const_url::inst()->get_default_admin();
		    $this->_redirect($url);
		}
	
		//$this->_init_database();
		$this->_decide_template();  //根据客户端类型判断手机版/PC版皮肤
		return $this;
	}
	
	protected function _log($model, $profile=NULL)
	{
		$this->load->library('EA_behavior_log');
		if( !$profile) $profile= $this->session->get_admin_profile();
		EA_behavior_log::inst()->record($profile, $model);
	}
	
	protected function _init_database()
	{
		if(ENVIRONMENT=='production') return '';
		
		$this->load->dbutil();
		if( !$this->dbutil->database_exists('iwide30dev') ){
			$this->dbforge->create_database('iwide30dev');
		}
		if( !$this->dbutil->database_exists('iwide_core_node')
			|| !$this->dbutil->database_exists('iwide_core_admin')
			|| !$this->dbutil->database_exists('iwide_core_admin_role')
		){
			$this->_update_db();    	//更新最新的数据结构
		}
	}
	
	/**
	 * 权限过滤
	 * @return boolean
	 */
	protected function _acl_filter()
	{
	    $module= $this->module;
	    $controller= $this->controller;
	    $action= $this->action;

	    $acl_array= $this->session->allow_actions;
        $acl_array= $acl_array[ADMINHTML];

	    //add chenjunyu 2016-10-21 如果当前用户没有新订单首页权限，则跳到会员列表
	    if($this->action=='index_one' && $this->controller=='orders'&&$acl_array!=FULL_ACCESS&&!in_array('index_one',$acl_array['hotel']['orders'])){
	    	$this->_redirect(site_url('member/memberlist/grid'));
	    }

	    //部分操作不受权限控制，如login
		$ignore_methods= config_item('acl_disable_method');
		$acl_filter= config_item('acl_filter');
		if( $acl_filter==FALSE || in_array($action, $ignore_methods) ) {
			//对于完全开放的操作，如logo，logout
		    return TRUE;
		}
		$open_methods= config_item('acl_open_method');
		if( $acl_array && in_array($action, $open_methods) ) {
			//对于半开放的操作，如dashboard
		    return TRUE;
		}

	    if( empty($acl_array) ) {
	        //会话超时
	        if( isset($_SERVER['REQUEST_URI']) ){
	            $redirect= urlencode( base_url($_SERVER['REQUEST_URI']) );
	        }
		    $this->_redirect(EA_const_url::inst()->get_login_admin(). '?redirect='. $redirect);
	    }
		if ( $acl_array== FULL_ACCESS ) {
			//此处需要放在会话超时之后
	        return true;
	    }
		if( isset($acl_array[$module][$controller]) && in_array($action, $acl_array[$module][$controller])){
	        return true;
	    }
	    if( $this->action=='index' && $this->controller=='auth' ){
	        return true;
	    }
//临时放行
if( $this->action=='grid' && $this->controller=='memberlist' ){
    return true;
}

        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
            die( json_encode(array('status'=>2, 'message'=>'您的账号权限不足。')) );
        else
		    $this->_redirect(EA_const_url::inst()->get_deny_admin());
	}

	/**
	 * 后台key过滤
	 * @return boolean
	 */
	public function _token_filter()
	{
		$ignore_methods= config_item('acl_disable_method');
		$token_filter= config_item('token_filter');
	    if(in_array($this->action, $ignore_methods)) {
	        return TRUE;
	        
	    } else if( $token_filter==TRUE ){
	        $token_name= config_item('token_filter_name');
	        $token= $this->input->get($token_name);
	        $token_match= do_hash($this->action);
	        $token== $token_match? TRUE: FALSE;
	         
	    } else {
	        return TRUE;
	    }
	}
	
	/**
	 * 加载缓存组件
	 * @see MY_Controller::_load_cache()
	 */
	protected function _load_cache( $name='Cache' )
    {
        $success = Soma_base::inst()->check_cache_redis();
        if( !$success){
            //redis故障关闭cache
            Soma_base::inst()->show_exception('当前访问用户过多，请稍后再试！', TRUE );
        }
        if(!$name || $name=='cache') //不能为小写cache
            $name='Cache';
        
        $this->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'adm_'),
            $name
        );
        return $this->$name;
	}

	/**
	 * 单输出content局部位置内容（不带头部）
	 * @param $file string 模板文件
	 * @param $data Array 传入模板参数
	 * @param $return boolean  输出方式 TRUE 缓存到变量; FALSE 直接输出
	 * @return String
	 */
	protected function _load_content($file, $data=NULL, $return = FALSE)
	{
		$data['tpl']= $this->sub_template;
		//echo $this->sub_template. '/'. $file;die;
		return $this->load->view($this->sub_template. '/'. $file, $data, $return);
	}

	/**
	 * 按照默认结构输出整页面内容，只定义 content中内容（带头部）
	 * @param $file 模板文件
	 * @param $data Array 传入模板参数
	 * @param $return boolean  输出方式 TRUE 缓存到变量; FALSE 直接输出
	 * @return String
	 */
	protected function _render_content($content_file, $params=array(), $return = TRUE)
	{
	    $admin_profile= $this->session->get_admin_profile();
		$data= array(
			'tpl'=> $this->sub_template,
		    'profile'=> $admin_profile,
		    'breadcrumb_html'=> $this->breadcrumb_html,
		    'breadcrumb_array'=> $this->breadcrumb_array,
		);
		
		$menu= $this->_load_menu();
		if($return){
    		$html= '';
    		$data['block_top'] = $this->_load_view($this->priv_dir. '/top', $data, $return);
    		$data['block_left'] = $this->_load_view($this->priv_dir. '/left', array('menu'=> $menu), $return);
    		$html.= $this->_load_view($this->priv_dir. '/header', $data, $return);
    		$html.= $this->_load_view($content_file, $params, $return);
		    return $html;
		    
		} else {
    		$data['block_top'] = $this->_load_view($this->priv_dir. '/top', $data, TRUE);
    		$data['block_left'] = $this->_load_view($this->priv_dir. '/left', array('menu'=> $menu), TRUE);
    		$this->_load_view($this->priv_dir. '/header', $data, $return);
    		$this->_load_view($content_file, $params, $return);
		}
	}
	
	/**
	 * 统一读取菜单数组
	 * @return String
	 */
	protected function _load_menu()
	{
	    $menu= $this->session->get_leftmenu();
	    if(!$menu){
	        $this->load->model('core/priv_node', 'node_model');
	        $nodes= $this->node_model->get_node_tree_array();  //获取全部菜单节点
	        //print_r($nodes);die;
	        
	        $this->load->library('EA_block_admin');
	        $block= EA_block_admin::inst();
	        $acl= $block->get_acl_array($this->session);  //获取当前会员的权限记录
	        //print_r($acl);die;
            $acl = array('adminhtml'=>'ALL_PRIVILEGES');//测试
	        
	        $menu= $block->build_menu($nodes, $acl, $this->session);
	        
            if( config_item('save_menu') ) 
                $this->session->set_leftmenu($nodes);
	    }
		return $menu;
	}

	/**
	 * 根据浏览器类型判断使用哪个模板
	 * @return boolean
	 */
	protected function _decide_template()
	{
		//Must set it after parent::__construct();
		$this->load->library('user_agent');
		
		if( $this->agent->is_mobile() ){
			//exit('<h1 style="color:red;">请用PC打开，手机端正在开发中...</h1>');
			//$this->_set_template('mobile');
		    if( !$this->sub_template ) $this->_set_template('default');
				
		} else {
		    if( !$this->sub_template ) $this->_set_template('default');
		}
		return TRUE;
	}
	
	/**
	 * 传入方法名后，生成最终的面包屑html和数组
	 * @param String 传入执行的方法名
	 * @param String icon 名称
	 * @param Array 下级内容（预留）
	 */
	protected function _init_breadcrumb($label_action, $icon='fa-home', $param=array() )
	{
		$html= '';
		$url_util= EA_const_url::inst();
		$icon= "<i class='fa {$icon}'></i>";
		if($this->label_module){
			$html.= "<a href='#'>$icon {$this->label_module}</a>";
		} else {
			$html.= "<a href='/index.html'> HOME </a>";
		}
		if($this->label_controller){
			$href= $url_util->get_url('*/*');
			$html.= "／<a href='{$href}'>{$this->label_controller}</a>";
		}
		if($this->label_action){
			$href= $url_util->get_url('*/*/*');
			$html.= "／{$this->label_action}";
		}
		$this->breadcrumb_html = $html;
		$this->breadcrumb_array = array(
			'module'=>$this->label_module,
			'controller'=>$this->label_controller,
			'action'=>$this->label_action,
		);
		return TRUE;
	}

	protected function _init_upload_config($config=array())
	{
	    /**
参数    默认值    选项    描述
upload_path	    None	None	文件上传的位置，必须是可写的，可以是相对路径或绝对路径
allowed_types	None	None	允许上的文件 MIME 类型，通常文件的后缀名可作为 MIME 类型 可以是数组，也可以是以管道符（|）分割的字符串
file_name       None	Desired file name	如果设置了，CodeIgniter 将会使用该参数重命名上传的文件 设置的文件名后缀必须也要是允许的文件类型 如果没有设置后缀，将使用原文件的后缀名
file_ext_tolower	FALSE	TRUE/FALSE (boolean)	如果设置为 TRUE ，文件后缀名将转换为小写
overwrite	        FALSE	TRUE/FALSE (boolean)	如果设置为 TRUE ，上传的文件如果和已有的文件同名，将会覆盖已存在文件 如果设置为 FALSE ，将会在文件名后加上一个数字
max_size	0	None	允许上传文件大小的最大值（单位 KB），设置为 0 表示无限制 注意：大多数 PHP 会有它们自己的限制值，定义在 php.ini 文件中 通常是默认的 2 MB （2048 KB）。
max_width	0	None	图片的最大宽度（单位为像素），设置为 0 表示无限制
max_height	0	None	图片的最大高度（单位为像素），设置为 0 表示无限制
min_width	0	None	图片的最小宽度（单位为像素），设置为 0 表示无限制
min_height	0	None	图片的最小高度（单位为像素），设置为 0 表示无限制
max_filename 0	None	文件名的最大长度，设置为 0 表示无限制
max_filename_increment	100	None	当 overwrite 参数设置为 FALSE 时，将会在同名文件的后面加上一个自增的数字 这个参数用于设置这个数字的最大值
encrypt_name	FALSE	TRUE/FALSE (boolean)	如果设置为 TRUE ，文件名将会转换为一个随机的字符串 如果你不希望上传文件的人知道保存后的文件名，这个参数会很有用
remove_spaces	TRUE	TRUE/FALSE (boolean)	如果设置为 TRUE ，文件名中的所有空格将转换为下划线，推荐这样做
detect_mime	    TRUE	TRUE/FALSE (boolean)	如果设置为 TRUE ，将会在服务端对文件类型进行检测，可以预防代码注入攻击 除非不得已，请不要禁用该选项，这将导致安全风险
mod_mime_fix	TRUE	TRUE/FALSE (boolean)	如果设置为 TRUE ，那么带有多个后缀名的文件将会添加一个下划线后缀 这样可以避免触发 Apache mod_mime 。 如果你的上传目录是公开的，请不要关闭该选项，这将导致安全风险
	     */ 
	    $default= array();
	    //调用效果：htdocs\www_front/public/模块名称/uploads/
	    $default['upload_path'] = FRONT_FD_. $this->module. DS. 'uploads'. DS;
	    $default['allowed_types'] = 'gif|jpg|png';
	    //$default['max_size'] = '2048';
	    $default['max_size'] = '1024';
	    //$default['max_width'] = '1024';
	    //$default['max_height'] = '768';
	    $config= array_merge($default, $config);
	    //print_r($config);die;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if( !file_exists($config['upload_path'])) {
        	@mkdir($config['upload_path'], 0777, TRUE);
        }
	    return ;
	}
	
	protected function _do_upload($post, $fieldname, $area='front', $path=NULL)
	{
	    if(isset($_FILES[$fieldname]['size']) && $_FILES[$fieldname]['size']>0 ) {
	        $upload_ext= substr(strrchr($_FILES[$fieldname]['name'], '.'), 1);
	        if( $path==NULL ){
	            $path= $post['inter_id']. '/'. $this->controller. '/'. $fieldname;
	        }
	        $base_path= 'media/'. $path. '/';
	        $upload_name= date('YmdHis'). '.'. $upload_ext;
	        
	        if($area== 'admin')
	             $upload_path= FD_. $base_path;
	        else 
	             $upload_path= FRONT_FD_. $base_path;
	        
	        if( $path==NULL ){
	            $upload_url= '/public/media/'. $post['inter_id']. '/'. $this->controller. '/'
	                . $fieldname. '/'. date('YmdHis'). '.'. $upload_ext;
	        } else {
	            $upload_url= '/public/media/'. $path. '/'. date('YmdHis'). '.'. $upload_ext;
	        }
	        
	        $this->_init_upload_config(array(
	            'upload_path'=> $upload_path,
	            'file_name'=> $upload_name,
	        ));
	        if ( ! $this->upload->do_upload($fieldname)) {
	            $error = $this->upload->display_errors();
	            $this->session->put_error_msg($error);
	            if($this->controller == 'sales_voucher_theme') {
	            	$this->_redirect(EA_const_url::inst()->get_url('*/*/edit'));
	            } else {
	            	$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	            }
	        } else {

//ftp开始，初始化测试服务器ftp
if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
    $this->ftp= $this->_ftp_server('prod');
} else {
    $this->ftp= $this->_ftp_server('test');
}
//$to_file = '/public_html'. $file_system_path;
$to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path;
//echo $to_file;die;  //public/media/a449493496/mall/goods/gs_detail/
$isdir = $this->ftp->list_files($to_file);
if (empty($isdir)) {
    $newpath = '/';
    $arrpath = explode('/', $to_file);
    foreach ($arrpath as $v) {
        if ($v!= '') {
            $newpath = $newpath. $v. '/';
            $isdirchild = $this->ftp->list_files($newpath);
            if (empty($isdirchild)) {
                $this->ftp->mkdir($newpath);
            }
        }
    }
}
$this->ftp->upload($upload_path. $upload_name, $to_file. $upload_name, 'binary', 0775);
$this->ftp->close();
//ftp结束

@unlink($upload_path. $upload_name);
$upload_url= $this->ftp->weburl. '/'. FD_PUBLIC. '/'. $base_path. $upload_name;
	            
	            $post[$fieldname]= $upload_url;
	        }
	    }
	    return $post;
	}
	
	/**
	 * JSON 形式反馈消息，分成功、失败、警告3种情况
	 * @param String $message
	 * @param String|Array $data
	 * @return string
	 */
	public function _return_json_notice($message, $data=array())
	{
		return $this->_return_json($data, $message, 3);
	}
	public function _return_json_error($message, $data=array())
	{
		return $this->_return_json($data, $message, 2);
	}
	public function _return_json_success($message, $data=array())
	{
		return $this->_return_json($data, $message, 1);
	}
	public function _return_json($message='', $data=array(), $status=0)
	{
		$result= new stdClass();
		$result->message= $message;
		$result->data= $data;
		$result->status= $status;
		return json_encode($result);
	}

    /**
     * Ajax方式返回数据到客户端
     * @param string $message
     * @param array $data
     * @param int $status
     */
    protected function _ajaxReturn($message='', $data=array(), $status=0){
        $result= new stdClass();
        $result->message= $message;
        $result->data= $data;
        $result->status= $status;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($result));
    }
	
	/**
	 * 加载controller默认模型对象
	 * @param $model string 传入模型名称
	 * @return MY_Model 
	 */
	public function _load_model($model=NULL)
	{
		$model= $model? $model: $this->main_model_name();
		$this->load->model($model, 'm_model');
		if( !$this->m_model ){
			throw new Exception('The requested page does not exist.');
			
		} else {
			return $this->m_model;
		}
	}
	
	public function index()
	{
		$this->grid();
	}

	public function add()
	{
	    $this->edit();
	}

	public function _load_view_file($filename)
	{
	    $base_path= VIEWPATH. $this->_get_template(). DS;
	    if( file_exists($base_path. $this->module. DS. $this->controller. DS. $filename. '.php') ) {
	        return $this->module. DS. $this->controller. DS. $filename;
	
	    } else if( file_exists($base_path. $this->module. DS. $filename. '.php') ) {
	        return $this->module. DS. $filename;
	
	    } else {
	        return 'privilege'. DS. $filename;
	    }
	     
	}
	
	public function grid()
	{
	    $this->_grid();
	}

	/**
	 * 分解datatable提交的搜索数据
	 * @param Array $post
	 * @param MY_Model $model
	 * @return Array
	 */
	public function _ajax_params_parse($post, $model)
	{
	    if( isset($post['columns']) ){
	        $field_map= $model->grid_fields();
	        //print_r($field_map);die;
	        $merge_arr= array( 'f_like'=>array(), 'f_match'=>array() );
	        foreach ($post['columns'] as $k=> $v){
	            //var_dump($v['search']);die;
	            if( key_exists($k, $field_map) && isset($v['search']['value']) && $v['search']['value']){
	                if( isset($v['search']['regex']) && $v['search']['regex']=='true' )
	                    $merge_arr['f_like'][$field_map[$k]]= $v['search']['value'];
	                else
	                    $merge_arr['f_match'][$field_map[$k]]= $v['search']['value'];
	            }
	        }
	        $post = $merge_arr+ $post;
	    }
	    //print_r($post);die;
	    return $post;
	}
	
	/**
	 * 默认表格展示函数，可共享
	 */
	public function _grid($filter= array(), $viewdata=array())
	{
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);

		//filter params: the same with table fields...
		//sort params: sort_direct, sort_field
		//page params: page_size, page_num
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 ) 
		    $params= array_merge($params, $filter);
		
		if(is_ajax_request()){
			//处理ajax请求
			$result= $model->filter_json($params );
			echo json_encode($result);
			
		} else {
			//HTML输出
			if( !$this->label_action ) $this->label_action= '信息列表';
			$this->_init_breadcrumb($this->label_action);

			//base grid data..
			$result= $model->filter($params);
			$fields_config= $model->get_field_config('grid');
			$default_sort= $model::default_sort_field();

			$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'result'=> $result,
				'fields_config'=> $fields_config,
				'default_sort'=> $default_sort,
			);
            $view_params= $view_params+ $viewdata;

			$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
			//echo $html;die;
			echo $html;
		}
	}

    /**
     * 判断越权查看数据
     */
    public function _can_edit($model)
    {
    	if( !$model || !$model->table_primary_key() ){
            return TRUE;
        }
        $pk= $model->table_primary_key();
        if( !$model->m_get($pk)  ){
            return TRUE;
        }
        $inter_id= $this->session->get_admin_inter_id();
        if( $inter_id== FULL_ACCESS ){
            return TRUE;
        }
        if( $inter_id!= $model->m_get('inter_id') ){
            return FALSE;
        }
        return TRUE;
    }

	/**
     * 处理新增和编辑方法
     */
	public function edit()
	{
		$this->label_action= '信息维护';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));
		if($id){
			$model= $model->load($id);
		}

        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	/**
	 * 此函数需要在controller实例化
	 */
	public function edit_post()
	{
	    $this->session->put_error_msg("edit_post方法还没定义", 'register');
	    $this->add();
	}

	public function delete()
	{
        $this->session->put_error_msg('该内容不能删除！');
	    $url= EA_const_url::inst()->get_url('*/*/grid');
	    $this->_redirect($url);
	}
	
	/**
	 * 删除和批量删除，
	 * @deprecated 数据安全因素，请在模块内父类书写
	 * @see MY_Admin_Priv
	 */
	public function _delete()
	{
	    try {
    		$model_name= $this->main_model_name();
    		$model= $this->_load_model($model_name);
    		
    		$ids= explode(',', $this->input->get('ids'));
    		$result= $model->delete_in($ids);
    		
    		if( $result ){
                $this->session->put_success_msg("删除成功");
    		    
    		} else {
                $this->session->put_error_msg('删除失败');
    		}
    		
	    } catch (Exception $e) {
	        $message= '删除失败过程中出现问题！';
	        //$message= $e->getMessage();
            $this->session->put_error_msg('删除失败');
	    }
	    $url= EA_const_url::inst()->get_url('*/*/grid');
	    $this->_redirect($url);
	}

	/**
	 * @author libinyan@mofly.cn
	 * @param  [type]  $content  [二维码内容]
	 * @param  boolean $filename [生成图片名，文件名空则直接显示图片，不保存文件]
	 * @param  integer $size     [图片大小]
	 * @param  integer $margin   [白边举例]
	 * @return [type]
	 */
	public function _get_qrcode_png($content, $filename=FALSE, $size=5, $margin=1, $base_path=FALSE )
	{
	    $this->load->helper ( 'phpqrcode' );
	    if( $filename===FALSE ){
	        QRcode::png($content, FALSE, 'Q', $size, $margin, TRUE );
	        return TRUE;
	
	    } else {
            if( $base_path==FALSE )
                $base_path= 'qrcode'. '/'. $this->module. '/'. $this->controller. '/'. $this->action;
	        $path= FCPATH. FD_PUBLIC. '/'. $base_path;
	        //echo $path;die;
	        if( !file_exists($path) ) @mkdir($path, 755, TRUE);
	        $file= $path. '/'. $filename. '.png';
	        //echo $file;die;
            QRcode::png($content, $file, 'Q', $size, $margin );

//ftp开始，初始化测试服务器ftp
if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
    $this->ftp= $this->_ftp_server('prod');
} else {
    $this->ftp= $this->_ftp_server('test');
}
//$to_file = '/public_html'. $file_system_path;
$to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path. '/';
//echo $to_file;die;  //   /public_html/public/qrcode/mall/test/qr/
$isdir = $this->ftp->list_files('./public_html/public/qrcode/mall');
if (empty($isdir)) {
    $newpath = '/';
    $arrpath = explode('/', $to_file);
    foreach ($arrpath as $v) {
        if ($v && $v!= $this->ftp->floder ) {
            $newpath .= $v. '/';
            $isdirchild = $this->ftp->list_files($newpath);
            if (empty($isdirchild)) {
                $this->ftp->mkdir($newpath);
            }
        }
    }
}
$upload_name= $filename. '.png';
$to_file= str_replace(array('\\','//'), array('/','/'), $to_file. '/'. $upload_name);
if( !file_exists($file) ) echo '原上传文件不存在！';
else $result= $this->ftp->upload($file, $to_file, 'binary', 0775);
$this->ftp->close();
//ftp结束

//@unlink($file);   //二维码留底，不删除
$upload_url= $this->ftp->weburl. '/'. FD_PUBLIC. '/'. $base_path. $upload_name;
	        
	        return $upload_url;
	    }
	}
	
	
}

//后台权限模块专用Controller by libinyan
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Priv.php";
//后台Mall模块专用Controller by libinyan
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Mall.php";
//后台Soma模块专用Controller by libinyan
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Soma.php";
//后台Company模块专用Controller by zhanghuai
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Cprice.php";
//后台Member Api模块专用 by Frandon
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Api.php";
//后台Member模块专用 by knight
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Member.php";
//后台Roomservcie模块专用 by situguanchen
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Roomservice.php";
//后台Ticket模块专用 by shacaisheng
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin_Ticket.php";
