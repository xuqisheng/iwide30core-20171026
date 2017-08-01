<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Priv_admin_role extends MY_Model {

	const ROLE_TYPE_ALL		= 1;
	const ROLE_TYPE_DEFINE	= 2;
	const ROLE_TYPE_MODULE	= 3;
	const PRESERVE_ID    = 10;    //小于10的权限为绝对保留

	public function get_resource_name()
	{
		return '管理员权限';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'core_admin_role';
	}

	public function table_primary_key()
	{
	    return 'role_id';
	}

	const IS_OPEN_T = 1;
	const IS_OPEN_F= 2;
	
	public function is_open_label()
	{
	    return array(
	        self::IS_OPEN_T => '开放选择',
	        self::IS_OPEN_F => '不开放',
	    );
	}
	
	public function attribute_labels()
	{
		return array(
			'role_id' => 'ID',
			'module' => '所属模块',
			'role_name' => '唯一标识',
			'role_label' => '角色别称',
			'acl_desc' => '权限分配',
			'parent' => '父级',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
			'status' => '状态',
			'is_open' => '对商户开放',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array('role_id', 'is_open', 'module', 'role_name', 'role_label', 'create_time', 'update_time', 'status');
	}

	//定义 m_save 保存时不做转义字段
	public function unaddslashes_field()
	{
	    return array( 'acl_desc' );
	}
	
	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义 
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在<input “disabled” /> 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 *   select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui()
	{
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();
	    return array(
	        'role_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '5%',
	            'form_ui'=> '',
	            'type'=>'text',
	            //'form_type'=> 'hidden',
	        ),
	        'module' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'form_tips'=> '用来区分不同的后台面板权限',
	            'type'=>'combobox',
	            'select'=> $modules,
	        ),
	        'role_name' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'parent' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> ' disabled ',
                'form_hide'=> TRUE,
	            'form_default'=> '0',
	            'form_tips'=> '暂未启用此选项',
	            'type'=>'text',
	        ),
	        'role_label' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '20%',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'acl_desc' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '18%',
	            'form_ui'=> ' rows="5" ',
	            'form_hide'=> TRUE,
	            'type'=>'textarea',
	        ),
	        'create_time' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> ' disabled ',
	            'type'=>'datetime',
	        ),
	        'update_time' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> ' disabled ',
	            'type'=>'datetime',
	        ),
	        'status' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '6%',
	            'form_ui'=> '',
	            // label,text,textarea,checkbox,numberbox,validatebox,datebox,combobox,combotree
	            'type'=>'combobox',
	            'select'=> $base_util::get_status_options(),
	        ),
	        'is_open' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '12%',
	            'form_ui'=> '',
	            // label,text,textarea,checkbox,numberbox,validatebox,datebox,combobox,combotree
	            'type'=>'combobox',
	            'select'=> self::is_open_label(),
	        ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'role_id', 'sort'=>'desc');
	}
	
	/**
	 * grid表格中的过滤器匹配方式数组
	 */
	// 	public function filter_option()
	// 	{
	// 	    /* contains,equal,notequal,beginwith,endwith,less,lessorequal,greater,greaterorequal. */
	// 	    return array( 'equal','notequal','less','greater' );
	// 	}
	
	
	/***** 以下上为必填函数信息  *****/
	

	/**
	 * @param String $role_id
	 * @param Array $filter 
	 * @return Array|String acl_desc;
	 */
	public function get_one_acl_array($role_id=null, $filter= array() )
	{
		if ($role_id==null) {
			throw new Exception('RoleId can not be empty!');
			
		} else {
			$filter= array_merge($filter, array('role_id'=> $role_id));
			$result= $this->find($filter);
			if( $result ) {
				if($result['acl_desc'] === FULL_ACCESS ) {
					return array( ADMINHTML => FULL_ACCESS );
					
				} else {
					return @unserialize( $result['acl_desc'] );
				}
			} else {
				return false;
			}
		}
	}

	/**
	 * 需过滤的 模块module，控制器controller，以及方法action名称
	 * 范例：  1，模块名；2，模块名/控制器；3，模块名/控制器名/方法名
	 * @return multitype:string
	 */
	public function disable_acl_node()
	{
	    //Base directory "controllers/admin";
	    $list= array(
	        'disable_module',  //module disable
	        //'privilege'. DS. 'auth',  //controller disable
	        'privilege'. DS. 'login',
	        'privilege'. DS. 'auth'. DS. 'index',
	        'mall'. DS. 'mall',
            'basic'. DS. 'JSON',
	        'plugins'. DS. 'Atools',
	        'login.php',
	    );
	    $return= array();
	    foreach ($list as $v) $return[]= strtolower($v);
	    return $return;
	}

	/**
	 * 已弃用。 需要统一过滤的方法（不分模块和控制器的）
	 * @return multitype:string
	 * 
	 * @deprecated This array move to config.php file already
	 * @see $config['acl_disable_method'].
	 */
	public function disable_acl_method()
	{
	    return array(
// 	        'get_instance', 
// 	        'login', 'logout', 'deny', 'dashborad',
// 	        'index',
	    );
	}
	
	/**
	 * 读取目录中的可控制controller文件
	 * @return Array   return flat controller array.
	 * Array (
        [privilege] => Array (
            [0] => .....demo\controllers\admin\privilege\Adminrole.php
            [1] => .....demo\controllers\admin\privilege\Adminlog.php
	 */
	public function get_controller_options()
	{
		//echo Yii::app()->basePath; => ../protected
		$dir = APPPATH. 'controllers'. DS. 'admin'. DS;
		$options=$this->format_controller_options($dir);
		$dir = APPPATH. 'controllers'. DS. 'iapi'. DS.'admin'.DS;
		$options=array_merge($options,$this->format_controller_options($dir,'iapi-'));
		return $options;
	}
	function format_controller_options($dir,$prefix=''){
	    $array= array();
	    $dirHandle= @opendir($dir);
	    $diabled= $this->disable_acl_node();
	    if($dirHandle) {
	        while( ($file= readdir($dirHandle))!==false ) {
	            $ext= explode('.', $file);
	            if($file==='.' || $file==='..' ) {
	                continue;
	                
	            } else if(end($ext)=='php'){
	                if( in_array($file, $diabled) ) continue;
	                //else 
	                //此目录下原则上不存放文件。
	                
	            } else if( count($ext)>1 ){
	                continue;  //不读取其他文件，如 目录下rar，doc等文件
	                
	            } else {
	                //module directory.
	                if( in_array($file, $diabled) ) continue;
	                else {
	                    $dirHandle2= @opendir($dir. $file);
	                    while( ($file2= readdir($dirHandle2))!==false ) {
	                        $ext2= explode('.', $file2);
	                        if($file2==='.' || $file2==='..' ) {
	                            continue;
	                             
	                        } else if(count($ext2)>1 && end($ext2)!='php'){
	                            continue;  //不读取其他文件，如目录下 rar，doc等文件
	                            
	                        } else {
	                            //echo strtolower($file. DS. $ext2[0]);die;
	                            if( in_array( strtolower($file. DS. $ext2[0]), $diabled) ) continue;
	                            else $array[$prefix.$file][]= $dir. $file. DS. $file2;
	                        }
	                    }
	                    closedir($dirHandle2);  
	                }
	            }
	        }
	        closedir($dirHandle);  
	    }
	    return $array;
	}
	
	/**
	 * Array (
        [privilege] => Array (
            [Adminlog] => Array (
                [0] => save
                [1] => show
                [2] => index
                [3] => grid
                [4] => add
                [5] => edit
                [6] => edit_post
                [7] => delete
                [8] => batch_delete
	 * 
	 * Use in adminhtml -> sysAdminRole -> edit role
	 * @return Array return controller, action array.
	 */
	public function get_role_tree_array($level=0 )
	{
		$tree = array();
		$controllers= $this->get_controller_options();
		$ignore_methods= config_item('acl_disable_method');

		//获取要剔除的方法
		$ignore_methods= config_item('acl_disable_method');
		
		$base_mathods= array();
		$core_path= APPPATH. DS. 'core'. DS;
		foreach ( array($core_path. 'MY_Controller.php', $core_path. 'MY_Admin.php') as $v){
		    $content= php_strip_whitespace($v);
		    $content = preg_match_all('/public\s+function\s+[^_][a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(/xi', $content, $lines);
		    foreach ($lines[0] as $sv){
		        $strs= explode(' ', $sv);
		        $method= trim(substr(end($strs), 0, -1));
		        if( !in_array($method, $ignore_methods) )$base_mathods[]= $method;
		    }
		}
		//获取父类的基础方法
		$base_mathods= array_unique($base_mathods);
		
		foreach ($controllers as $k=> $v) {
		    foreach ($v as $sv) {
    			if( file_exists($sv) ) {
        			//多次截取后从路径中取得类名称：如 Adminlog
//         			$ctl_name= substr(strstr(strstr($sv, $k), DS), 1, -4);
        			$path=explode(DS, $sv);
        			$ctl_name= substr(array_pop($path),0,-4);
        			$content= php_strip_whitespace($sv);
        		    $content = preg_match_all('/public\s+function\s+[^_][a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(/xi', $content, $lines);
        		    foreach ($lines[0] as $sv){
        		        $strs= explode(' ', $sv);
        		        $method= trim(substr(end($strs), 0, -1));

					    if( !in_array($method, $ignore_methods) ){
					        $ctl_name= strtolower($ctl_name);
					        $tree[$k][$ctl_name][]= $method;
					    } 
        		    }
        		    if( isset($tree[$k][$ctl_name]) && count($tree[$k][$ctl_name])>0){
        		        //子控制器无任何执行方法
        		        $tree[$k][$ctl_name]= array_merge($base_mathods, $tree[$k][$ctl_name]);
        		        $tree[$k][$ctl_name]= array_unique( $tree[$k][$ctl_name] );
        		    } else {
					    $ctl_name= strtolower($ctl_name);
        		        $tree[$k][$ctl_name]= $base_mathods;
        		    }
        		    
    			} else {
    			    continue;
    			}
		    }
		}
		return $tree;
	}
	/**
	 * @deprecated 已弃用。此方法在CI框架下无法分别实例化各个controller
	 */
	public function get_role_tree_array_bak($level=0 )
	{
		$tree = array();
		$controllers= $this->get_controller_options();
		$ignore_methods= config_item('acl_disable_method');
		
		foreach ($controllers as $k=> $v) {
		    foreach ($v as $sv) {
    			if( file_exists($sv) ) {
    			    require_once($sv);
    			} else {
    			    continue;
    			}
    			//多次截取后从路径中取得类名称：如 Adminlog
    			$ctl_name= substr(strstr(strstr($sv, $k), DS), 1, -4);
    			
    			if( class_exists($ctl_name) ){
    				$ctl_obj= $ctl_name::get_instance();
    				
    				$actions = get_class_methods($ctl_obj);
    				foreach ($actions as $ssv) {
    					if ( substr($ssv,0,1)== '_' || in_array($ssv, $ignore_methods)){
    					    continue; 
    					    
    					} else {
    					    $ctl_name= strtolower($ctl_name);
    						$tree[$k][$ctl_name][]= $ssv;
    					}
    				}
    			}
		    }
		}
		return $tree;
	}
	
	public function get_role_array()
	{
	    $filter= array('status'=> EA_base::STATUS_TRUE);
	    $array= $this->get_data_filter($filter);
	    return $array;
	    //return array('0'=> '--')+ $array;
	}

	/**
	 * 根据英文名称获取其中文名，第三个参数用于名称冲突时使用，用法如下：
	 *  $name= $model->get_role_lang('privilege');						// “权限模块”
	 *  $name= $model->get_role_lang('auth', 'c');				// “认证管理”
	 *  $name= $model->get_role_lang('edit', 'a');					// “编辑数据”
	 *  $name= $model->get_role_lang('export', 'a', 'adminlog');  // “导出操作日志”
	 */
	public function get_role_lang($code, $type='m', $controller=NULL)
	{
		$this->lang->load('adminrole');
		switch ($type) {
			case 'm':
        		$lang= $this->lang->line('adminrole_module');
        		if(isset($lang[$code])) return $lang[$code];
				break;
			case 'c':
        		$lang= $this->lang->line('adminrole_controller');
        		if(isset($lang[$code])) return $lang[$code];
				break;
			case 'a':
        		$lang= $this->lang->line('adminrole_action');
        		if(isset($lang[$code])) return $lang[$code];
				break;
		}
		$lang= $this->lang->line('adminrole_conflict');
		if( isset($lang[$code]) ){
			if( $controller && isset($lang[$code][$controller]) ){
				return $lang[$code][$controller];
			}
		}
		return "[{$code}]";
	}
	
	/**
	 * @param array $form_array
	 * @return string
	 */
	public function form_array_to_acl_desc($form_array=array() )
	{
		$return = array();
		foreach ($form_array as $k=> $v) {
			list($controller, $action) = explode('__', $k);
			$return[$controller][] = $action;
		}
		return serialize($return);
	}

	public function get_staff_role_arr($label_field='role_label', $key_field='role_id' )
	{
	    $table= $this->table_name();
	    $result= $this->_db()->where('is_open', EA_base::STATUS_TRUE)
	       ->where('status', EA_base::STATUS_TRUE)
	       ->get($table)->result_array();
	    $option= array();
	    foreach($result as $v){
	        $option[$v[$key_field]]= $v[$label_field];
	    }
	    return $option;
	}
}
