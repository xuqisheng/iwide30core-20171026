<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_config_use_model extends MY_Model_Soma {

	public function get_resource_name()
	{
		return 'Theme_config_use';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	// public function table_name()
	// {
	// 	return 'soma_theme_use';
	// }
	public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_theme_use', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'theme_use_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'theme_use_id'=> 'ID',
            'inter_id'=> '公众号',
            'theme_id'=> '皮肤ID',
            'theme_name'=> '皮肤名称',
            'theme_path'=> '皮肤路径',
            'theme_title'=> '首页标题',
            'is_show_product_attr'=> '首页商品属性',
            'index_bg'=> '首页背景图',
            'cat_bg'=> '分类页背景图',
            'buy_btn'=> '送自己按钮',
            'to_friend_btn'=> '送朋友按钮',
            'to_group_btn'=> '送群友按钮',
            'more_btn'=> '更多月饼',
            'main_color'=> '主色调',
            'sub_color'=> '副色调',
            'idx_color'=> '首页文字色',
            'phone'=> '首页联系电话',
            'receive_main_color'=> '转赠文字颜色',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'theme_use_id',
            'inter_id',
            'theme_name',
            'theme_path',
            'theme_title',
            'is_show_product_attr',
            'index_bg',
            'cat_bg',
            'buy_btn',
            'to_friend_btn',
            'to_group_btn',
            'more_btn',
            'main_color',
            'sub_color',
            'phone',
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
	    $Somabase_util= Soma_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();

        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

	    return array(
            'theme_use_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'theme_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'theme_path' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'theme_title' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'is_show_product_attr' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $Somabase_util::get_status_options(),
            ),
            'index_bg' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'cat_bg' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'buy_btn' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'to_friend_btn' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'to_group_btn' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'more_btn' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'main_color' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'color', //textarea|text|combobox|number|email|url|price
            ),
            'sub_color' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'color', //textarea|text|combobox|number|email|url|price
            ),
            'idx_color' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'color', //textarea|text|combobox|number|email|url|price
            ),
            'phone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'receive_main_color' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'color', //textarea|text|combobox|number|email|url|price
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'theme_use_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function get_use_theme( $inter_id=NULL )
    {
        $filter = array();
        $filter['inter_id'] = $inter_id;
        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')->where( $filter )
                    ->get( $table_name )
                    ->row_array();
    }
	
    public function m_replace($data) {
        $table= $this->table_name();
        $result= $this->_shard_db()->replace($table, $data);
        return $result;
    }

}
