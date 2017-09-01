<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Center_share_config_model extends MY_Model_Soma {

    const POSITION_DEFAULT = 1;
    const POSITION_GIFT = 2;
    const POSITION_GROUPON = 3;
    const POSITION_ACTIVITY = 4;

    const STATUS_ACTIVE  = 1;
    const STATUS_DISABLE = 2;

    public function get_status_label()
    {
        return array(
            self::STATUS_ACTIVE => '激活',
            self::STATUS_DISABLE=> '禁用',
        );
    }

    public function get_position_label()
    {
        return array(
                // self::POSITION_DEFAULT=>'默认',
                // self::POSITION_GIFT=>'转赠',
                // self::POSITION_GROUPON=>'拼团',
                self::POSITION_ACTIVITY=>'同步活动页',
            );
    }

	public function get_resource_name()
	{
		return 'Center_share_config_model';
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
	// 	return 'soma_cms_share_config';
	// }

    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_center_cms_share_config', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'config_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'config_id'=> '分享ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'position'=> '类型',
            'share_title'=> '分享标题',
            'share_link'=> '分享链接',
            'share_img'=> '分享图标',
            'share_desc'=> '分享描述',
            'timeline_title'=> '分享朋友圈标题',
            'timeline_link'=> '分享朋友圈链接',
            'timeline_img'=> '分享朋友圈图标',
            'start_time'=> '开始时间',
            'end_time'=> '结束时间',
            'create_time'=> '创建时间',
            'sort'=> '排序',
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
            'config_id',
            'inter_id',
            // 'hotel_id',
            'position',
            'share_title',
            'share_link',
            'share_img',
            'share_desc',
            // 'timeline_title',
            // 'timeline_link',
            // 'timeline_img',
            // 'start_time',
            // 'end_time',
            // 'create_time',
            'sort',
            'status',
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
	    $base_util= Soma_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

	    return array(
            'config_id' => array(
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
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'position' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type'=>'combobox',
                'select'=> $this->get_position_label(),
            ),
            'share_title' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'share_link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'share_img' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_admin_head|80',
                'type'=>'logo',	//textarea|text|combobox|number|email|url|price
            ),
            'share_desc' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'timeline_title' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'timeline_link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'timeline_img' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'grid_function'=> 'show_admin_head|80',
                'type'=>'logo',	//textarea|text|combobox|number|email|url|price
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                'form_tips'=> '不填写代表不限定结束时间',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                // 'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|combobox|number|email|url|price
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' min="0" ',
                'form_default'=> '1',
                'form_tips'=> '越大越优先',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',   //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_label()
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'config_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    //取出分享配置内容
    public function get_share_config_list( $position=NULL, $inter_id=NULL )
    {
        if( !$position || !$inter_id ){
            return FALSE;
        }

        $time = date('Y-m-d H:i:s');
        $table_name = $this->table_name( $inter_id );
        $filter = array();
        $filter['position'] = isset( $position ) && !empty( $position ) ? $position : self::POSITION_DEFAULT;//没有传值过来，就默认
        $filter['inter_id'] = $inter_id;
        $filter['status'] = self::STATUS_ACTIVE;
        $filter['start_time < '] = $time;
        $filter['end_time > '] = $time;

        return $this->_shard_db( $inter_id )
                    ->where( $filter )
                    ->order_by( 'sort DESC' )
                    ->get( $table_name )
                    ->row_array();
    }

	
}
