<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Priv_admin_log extends MY_Model {

	const ACTION_TYPE_LOGIN = 1;
	const ACTION_TYPE_CREATE= 2;
	const ACTION_TYPE_EDIT  = 3;
	const ACTION_TYPE_UPDATE= 4;
	const ACTION_TYPE_DELETE= 5;
	const ACTION_TYPE_SYNC= 6;
	
	public $canLog= false;	// can not log into log table

	public function get_action_type()
	{
		$array= array(
			self::ACTION_TYPE_LOGIN  => '登录事件',
			self::ACTION_TYPE_CREATE => '创建数据',
			self::ACTION_TYPE_EDIT => 	'编辑数据',
			self::ACTION_TYPE_UPDATE => '更新数据',
			self::ACTION_TYPE_DELETE => '删除数据',
			self::ACTION_TYPE_SYNC => '同步数据',
		);
		return $array;
	}
	
	public function get_resource_name()
	{
		return '管理员日志';
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'core_admin_log';
	}

	public function table_primary_key()
	{
	    return 'log_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'log_id' => 'ID',
			'admin_id' => '操作管理员',
			'module' => '所属模块',
			'action_type' => '操作类别',
			'action_info' => '操作描述',
			'action_controller' => '控制器',
			'action_model' => '操作资源',
			'primary_key' => '操作主键',
			'action_time' => '操作时间',
			'remote_ip' => 'IP地址',
			'status' => '有效性',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array('log_id', 'admin_id', 'module', 'action_type', 'action_info', 'action_controller', 
	        'primary_key', 'action_model', 'action_time', 'remote_ip', 'status');
	}

	public function attribute_ui()
	{

	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //tp: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $this->load->model('core/priv_admin', 'priv_admin');
	    $array= $this->priv_admin->get_data_filter( array('status'=> EA_base::STATUS_TRUE) );
	    $admin_array= $this->array_to_hash($array, 'username', 'admin_id');
	    $action_type= $this->get_action_type();
	    return array(
	        'log_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '5% ',
	            'form_ui'=> '',
	            'type'=>'text',
	            //'form_type'=> 'hidden',
	        ),
	        'admin_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '7% ',
	            'form_ui'=> '',
	            'type'=>'combobox',
	            'select'=> $admin_array,
	        ),
	        'module' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8% ',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'action_type' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8% ',
	            'form_ui'=> '',
	            'type'=>'combobox',
	            'select'=> $action_type,
	        ),
	        'action_info' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'action_controller' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10% ',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'action_model' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10% ',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'primary_key' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '5% ',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'action_time' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8% ',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'remote_ip' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8% ',
	            'form_ui'=> 'disabled ',
	            'type'=>'text',
	            'function'=> 'long2ip',
	        ),
	        'status' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '5% ',
	            'form_ui'=> '',
	            // label,text,textarea,checkbox,numberbox,validatebox,datebox,combobox,combotree
	            'type'=>'combobox',
	            'select'=> $base_util::get_status_options(),
	        ),
	    );
	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
		return array('field'=>'log_id', 'sort'=>'desc');
	}
	
	
}
