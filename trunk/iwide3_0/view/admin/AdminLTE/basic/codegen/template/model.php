
defined('BASEPATH') OR exit('No direct script access allowed');

class <?php echo ucfirst($class) ?> extends <?php echo $parent ?> {

	public function get_resource_name()
	{
		return '<?php echo $class ?>';
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
		return '<?php echo $table ?>';
	}

	public function table_primary_key()
	{
	    return '<?php echo $pk ?>';
	}
	
	public function attribute_labels()
	{
		return array(
<?php foreach($fields as $v): ?>
            '<?php echo $v->name; ?>'=> '<?php echo ucfirst($v->name); ?>',
<?php endforeach; ?>
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
<?php foreach($attributes as $v): ?>
            '<?php echo $v; ?>',
<?php endforeach; ?>
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
<?php foreach($fields as $v): ?><?php if($v=='sort'): ?>
            '<?php echo $v->name; ?>' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'number',
            ),
<?php elseif($v=='status'): ?>
            '<?php echo $v->name; ?>' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_default'=> '0',
                'type'=>'combobox',
	            'select'=> $base_util::get_status_options_(),
            ),
<?php else: ?>
            '<?php echo $v->name; ?>' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
<?php endif; ?>
<?php endforeach; ?>
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'<?php echo $pk ?>', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	
}
