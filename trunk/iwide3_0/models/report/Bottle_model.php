<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Bottle_model extends MY_Model { 

    public function get_resource_name() 
    { 
        return 'Bottle_model'; 
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
        return 'chat_config'; 
    } 

    public function table_primary_key() 
    { 
     return 'id'; 
    } 
    
    public function get_main_data($ids) {
	  	if ($ids) {
	  		return $this->db->select("*")->get_where('chat_config', array('id'=>$ids))->result_array();
	  	}
    }
     
    public function attribute_labels() 
    { 
        return array( 
        'id'=> 'Id', 
        'title'=> '标题', 
        'inter_id'=> '公众号', 
        'addtime'=> '添加时间', 
        'timeoffline'=> 'Timeoffline', 
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
        'title', 
        'inter_id', 
       // 'addtime', 
        //'timeoffline', 
        ); 
    } 

    /** 
     * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等 
     * type: grid中的表头类型定义 
     * form_type: form中的元素类型定义 
     * form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用 
     * form_tips: form中的label信息提示 
     * form_hide: form中自动化输出中剔除 
     * form_default: form中的默认值，请用字符类型，不要用数字 
     * select: form中的类型为 combobox时，定义其下来列表 
     */ 
    public function attribute_ui() 
    { 
     /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */ 
     //type: numberbox数字框|combobox下拉框|text不写时默认|datebox 
     $base_util= EA_base::inst(); 
     $modules= config_item('admin_panels')? config_item('admin_panels'): array(); 
     
     $this->load->model('wx/publics_model');
     $publics= $this->publics_model->get_public_hash();
     $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
     $inter_id= $this->session->get_admin_inter_id();
     if($inter_id=='ALL_PRIVILEGES'){
     	$inter_ids = $publics;
     } else {
     	$inter_ids = array($inter_id=>$publics[$inter_id]);
     }

     return array( 
'id' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'title' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'inter_id' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'combobox',    //textarea|text|combobox|number|email|url|price
'select'=> $inter_ids
), 
'addtime' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
'form_ui'=> ' disabled ', 
'form_default'=> date("Y-m-d H:i:s"), 
//'form_tips'=> '注意事项', 
'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'timeoffline' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
'form_ui'=> ' disabled ', 
'form_default'=> date("Y-m-d H:i:s"), 
//'form_tips'=> '注意事项', 
'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
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

     
} 