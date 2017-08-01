<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_gift_log extends MY_Model_Mall {

	public function get_resource_name()
	{
		return '赠送记录';
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
		return 'shp_gift_log';
	}

	public function table_primary_key()
	{
	    return 'gt_id';
	}

	const STATUS_GIFTING = '0';
	const STATUS_GETTED  = '1';
	const STATUS_TIMEOUT = '2';
	const STATUS_CANCLE  = '3';

    public function order_gift_status()
    {
        return array(
            self::STATUS_GIFTING => '赠送中',
            self::STATUS_GETTED => '已领取',
            self::STATUS_TIMEOUT => '超时退回',
            self::STATUS_CANCLE => '撤销赠送',
        );
    }

	public function attribute_labels()
	{
		return array(
			'gt_id'=> 'ID',
			'ge_openid'=> '赠送者',
			'gt_openid'=> '接赠人',
			'ge_time'=> '赠送时间',
			'gt_time'=> '接赠时间',
			'ge_code'=> '赠送码',
			'order_id'=> '订单ID',
			'order_items'=> '商品ID',
			'status'=> '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			'gt_id',
			'ge_openid',
			'gt_openid',
			'ge_time',
			'gt_time',
			'ge_code',
			'status',
			'order_id',
			'order_items',
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

	    return array(
            'gt_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'ge_openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'grid_function'=> 'hide_string_prefix(6)',
                'type'=>'text',	//textarea|text|combobox
            ),
            'gt_openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'hide_string_prefix(6)',
                'type'=>'text',	//textarea|text|combobox
            ),
            'ge_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gt_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'ge_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox
                'select'=> $this->order_gift_status(),
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'order_items' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'gt_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function get_filter_log($filter, $in_status=array() )
	{
	    $table= $this->table_name();
	    $this->_db()->where($filter);
	    //print_r($filter);die;
	    if( count($in_status )>0 )
	        $this->_db()->order_by('gt_id asc')->where_in('status', $in_status);
	
	    return $this->_db()->get($table)->result_array();
	}

	public function get_last_filter_log($filter, $in_status=array() )
	{
	    $table= $this->table_name();
	    $this->_db()->where($filter);
	    return $this->_db()->order_by('gt_id desc')
	               ->limit(1)->get($table)->result_array();
	}
	
}
