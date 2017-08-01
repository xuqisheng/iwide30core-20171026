<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_shipping_order_model extends MY_Model_Soma {

	public function get_resource_name()
	{
		return 'Sales_shipping_order_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function unaddslashes_field() {
        return array('shipping_data');
    }
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'soma_sales_shipping_order';
	}

	public function table_primary_key()
	{
	    return 'spo_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'spo_id'=> 'Spo_id',
            'inter_id'=> 'Inter_id',
            'hotel_id'=> 'Hotel_id',
            'openid'=> 'Openid',
            'pay_order_id'=> 'Pay_order_id',
            'shipping_data'=> 'Shipping_data',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'spo_id',
            'inter_id',
            'hotel_id',
            'openid',
            'pay_order_id',
            'shipping_data',
	    );
	}

	/**
	 * 后台UI输出定义函数
	 *   type: grid中的表头类型定义 
	 *   function: 数值转换函数 
	 *   select: form中的类型为 combobox时，定义其下来列表
	 grid专用属性名
	 *   grid_function: grid生效的数值转换，如'grid_function'=> 'show_price_prefix|￥',
	 *   grid_width: grid的宽度
	 *   grid_ui:  grid中的属性追加
	 form专用属性名
	 *   js_config: 用于 datetime, date 等js初始化中追加此参数
	 *   input_unit: input框中的单位提示
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 */
	public function attribute_ui()
	{
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    return array(
            'spo_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'pay_order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'shipping_data' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'spo_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	
}
