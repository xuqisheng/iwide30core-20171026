<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Distribution_protection_model extends MY_Model {
	public function get_resource_name(){
		return '分销保护信息';
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
		return 'distribution_protection';
	}

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels()
	{
		return array('id'=>'ID','inter_id'       => '公众号ID',
					 'slink'          => '来源链接',
					 'saler'          => '分销号',
					 'created_time'   => '开始保护时间',
					 'protect_to'     => '保护结束时间',
					 'openid'         => '保护粉丝',
					 'module'         => '模块');
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			'id',
			'inter_id',
			'slink',
			'saler',
			'openid',
			'module',
			'created_time',
			'protect_to',
		);
	}

	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义 
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
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
		//$parents= $this->get_cat_tree_option();

        /** 获取本管理员的酒店权限  */
	    $this->_init_admin_hotels();
	    $publics = $hotels= array();
	    $filter= $filterH= NULL;

	    if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
	    else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
	    if(is_array($filter)){
    	    $this->load->model('wx/publics_model');
    	    $publics= $this->publics_model->get_public_hash($filter);
    	    $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
    	    //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
	    }
	    
	    if( $this->_admin_hotels== FULL_ACCESS ) $filterH= array();
	    else if( $this->_admin_hotels ) $filterH= array('hotel_id'=> $this->_admin_hotels);
	    else $filterH= array();

	    if( $publics && is_array($filterH)){
    	    $this->load->model('hotel/hotel_model');
    	    $hotels= $this->hotel_model->get_hotel_hash($filterH);
    	    $hotels= $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
    	    $hotels= $hotels+ array('0'=>'-不限定-');
	    }
        /** 获取本管理员的酒店权限  */
	    
	    return array(
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'slink' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'saler' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'module' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'created_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox
            ),
            'protect_to' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'grid_function'=> 'unix_to_human|1',
                'type'=>'text',	//textarea|text|combobox
            )
	    );
	}
	
	protected function _parseDate($timestamp){
		return date('Y-m-d H:i:s',$timestamp);
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'id', 'sort'=>'desc');
	}
	
	
}