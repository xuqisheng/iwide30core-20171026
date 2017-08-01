<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_package_ticket_model extends MY_Model_Soma {

	public function get_resource_name()
	{
		return 'Product_package_ticket_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
    public function table_name($inter_id = NULL)
    {
        return $this->_shard_table('soma_catalog_product_package_ticket', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels()
	{
		return array(
            'id'=> '编号',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店名称',
            'theme_id'=> '皮肤ID',
            'name'=> '店铺名称',
            'type'=> '标题类型',
            'scope'=> '选择商品',
            'product_ids'=> '商品ID列表',
            'link'=> '门店链接',
            'block_arr'=> '展位内容',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
            'op_user'=> '上一个操作人',
            'status'=> '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'id',
            'name',
            'theme_id',
            'link',
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
            'id' => array(
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
            'theme_id' => array(
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
            'name' => array(
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
            'type' => array(
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
            'scope' => array(
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
            'product_ids' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'sort' => array(
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
            'status' => array(
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
	    return array('field'=>'id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    //定义 m_save 保存时不做转义字段
    public function unaddslashes_field()
    {
        return array(
            'product_ids',
            'block_arr',
            'link',
        );
    }

    /**
     * 验证表单提交是否正确
     *
     * @param      <type>  $data   表单提交数据
     *
     * @return     <type>  验证结果true|false
     */
    public function form_validation($data)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('inter_id', '公众号', 'required');
        $this->form_validation->set_rules('name', '规则名', 'required');
        return $this->form_validation->run();
    }

    public function get_product_package_ticket_byIds( $ids, $interId = NULL, $select = '*' )
    {
        if (!$ids) {
            return false;
        }
        if ($interId) {
            $inter_id = $interId;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $db = $this->_shard_db_r('iwide_soma_r');
        if ($inter_id) {
            $db->where(array('inter_id' => $inter_id));
        }

        $table_name = $this->table_name($inter_id);
        return $db->where_in('id', $ids)
                    ->select($select)
                    ->get($table_name)
                    ->result_array();
    }
	
}
