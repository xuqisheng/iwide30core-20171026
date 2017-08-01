<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_wishes extends MY_Model {

	public function get_resource_name()
	{
		return '赠礼寄语';
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
		return 'shp_wishes';
	}

	public function table_primary_key()
	{
	    return 'wish_id';
	}
	
	public function attribute_labels()
	{
		return array(
    		'wish_id'=> 'ID',
    		'inter_id'=> '公众号',
    		'order_id'=> '订单ID',
    		'code'=> '分配码',
    		'openid'=> 'Openid',
    		'headimgurl'=> '微信头像',
    		'nickname'=> '微信昵称',
    		'message'=> '祝福语',
    		'bg_url'=> '背景图片',
    		'serverId'=> '语音serverId',
    		'voice_url'=> '语音URL',
    		'create_time'=> '创建时间',
    		'lastview_time'=> '最后查看时间',
    		'view_count'=> '查看次数',
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
    		'wish_id',
    		'inter_id',
    		//'code',
    		'headimgurl',
    		'nickname',
    		'message',
    		'bg_url',
    		'lastview_time',
    		'view_count',
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
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    /** 获取本管理员的酒店权限  */
	    $this->_init_admin_hotels();
	    $publics = $hotels= $topics= array();
	    $filter= $filterH= NULL;
	     
	    if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
	    else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
	    if(is_array($filter)){
	        $this->load->model('wx/publics_model');
	        $publics= $this->publics_model->get_public_hash($filter);
	        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
	        //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
	         
	        $this->load->model('mall/shp_topic');
	        $topics= $this->shp_topic->get_data_filter($filter);
	        $topics= $this->shp_topic->array_to_hash_multi($topics, 'identity|page_title', 'topic_id');
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
	        'wish_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_ui'=> ' disabled ',
                'type'=>'combobox',
                'select'=> $publics,
                ),
	        'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'headimgurl' => array(
                'grid_ui'=> '',
	            'grid_width'=> '8%',
	            'form_ui'=> '',
	            'grid_function'=> 'show_admin_head|80',
	            'type'=>'logo',
                ),
	        'nickname' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'message' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'bg_url' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|150|0',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'serverId' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'voice_url' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'lastview_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'view_count' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	        );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'wish_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	
}
