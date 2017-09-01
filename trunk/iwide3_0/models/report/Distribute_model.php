<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Distribute_model extends MY_Model { 

    public function get_resource_name() 
    { 
        return 'Distribute_model'; 
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
        return 'distribute_grade_all'; 
    } 

    public function table_primary_key() 
    { 
     return 'id'; 
    } 
      
    public function attribute_items()
    {   
    	return array(
    			'id'=> 'ID',
    			'saler'=> '分销号',
    			'hotel_name'=> '酒店名',
    			'staff_name'=> '分销员',
    			'cellphone'=> '电话',
    			'product'=> '产品',
    			'order_amount'=> '订单金额',
    			'grade_total'=> '绩效金额',
    			'grade_amount'=> '计算金额',
    			'status'=> '状态',
    			'order_id'=> '订单号',
    			'grade_time'=> '时间'
    	);
    }
    
    public function attribute_labels() 
    { 
        return array( 
        'id'=> 'Id', 
        'inter_id'=> 'Inter_id', 
        'hotel_id'=> 'Hotel_id', 
        'saler'=> 'Saler', 
        'grade_openid'=> 'Grade_openid', 
        'grade_table'=> 'Grade_table', 
        'grade_id'=> 'Grade_id', 
        'grade_id_name'=> 'Grade_id_name', 
        'order_amount'=> 'Order_amount', 
        'grade_total'=> 'Grade_total', 
        'grade_amount'=> 'Grade_amount', 
        'grade_time'=> 'Grade_time', 
        'status'=> 'Status', 
        'grade_amount_rate'=> 'Grade_amount_rate', 
        'grade_rate_type'=> 'Grade_rate_type', 
        'remark'=> 'Remark', 
        'deliver_batch'=> 'Deliver_batch', 
        'last_update_time'=> 'Last_update_time', 
        'partner_trade_no'=> 'Partner_trade_no', 
        'send_time'=> 'Send_time', 
        'dist_grop'=> 'Dist_grop', 
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
        'inter_id', 
        'hotel_id', 
        'saler', 
        'grade_openid', 
        'grade_table', 
        'grade_id', 
        'grade_id_name', 
        'order_amount', 
        //'grade_total', 
        //'grade_amount', 
        //'grade_time', 
        //'status', 
        //'grade_amount_rate', 
        //'grade_rate_type', 
        //'remark', 
        //'deliver_batch', 
        //'last_update_time', 
        //'partner_trade_no', 
        //'send_time', 
        //'dist_grop', 
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
    public function dis_count($filter) {
    	$sql = "SELECT count(*) as count FROM iwide_distribute_grade_all where ".$filter;    	
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function dis_sum($filter,$sum) {
    	$sql = "SELECT SUM(".$sum.") as sum FROM iwide_distribute_grade_all where ".$filter;
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function dis_distinct($filter,$dis) {
    	$sql = "select count(distinct ".$dis.") as count from iwide_v_report_distribute_all where ".$filter;
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function dis_hot_count($filter,$desc) {
    	$sql = "SELECT COUNT(*) AS count,product,grade_amount,SUM(grade_amount) as all_grade_amount,SUM(order_amount) as all_order_amount FROM ";
    	$sql .= "iwide_v_report_distribute_all where ".$filter." GROUP BY product ORDER BY ".$desc." desc limit 10";
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function dis_hot_count_1($filter,$desc) {
    	$sql = "SELECT COUNT(*) AS count,product,grade_amount,SUM(grade_total) as all_grade_total,SUM(order_amount) as all_order_amount FROM ";
    	$sql .= "iwide_v_report_distribute_all where ".$filter." GROUP BY product ORDER BY ".$desc." desc limit 10";
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function staff_count($filter) {
    	$sql = "SELECT count(*) as count FROM iwide_hotel_staff where ".$filter;    	
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function fans_log_count($filter) {
    	$sql = "SELECT count(*) as count FROM iwide_fans_sub_log where ".$filter;
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function staff_sex($sex) {
    	$sql = "SELECT count(*) as count FROM iwide_hotel_staff INNER JOIN iwide_fans ON iwide_hotel_staff.inter_id = iwide_fans.inter_id AND iwide_hotel_staff.openid = iwide_fans.openid where ".$sex;
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function staff_order_sex($sex) {
    	$sql = "SELECT count(*) as count FROM iwide_distribute_grade_all INNER JOIN iwide_fans ON ";
    	$sql .= "iwide_distribute_grade_all.inter_id = iwide_fans.inter_id AND iwide_distribute_grade_all.grade_openid = iwide_fans.openid where ".$sex;
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $count;
    }
    
    public function salers_data($condition='1',$page,$url) {
    	$sql = "SELECT count(*) as count FROM iwide_hotel_staff ";
    	$sql .= "WHERE ";
    	$sql .= $condition;
    	$count = $this->_db('iwide_r1')->query($sql)->result_array();
    	$qfpage = qfpage3($count[0]['count'], 20, $page,$url);
    	
    	$sql = "SELECT * FROM iwide_hotel_staff ";
		$sql .= "WHERE ";
		$sql .= $condition." order by id desc limit ".$qfpage['limit'];
    	$result = $this->_db('iwide_r1')->query($sql)->result_array();
    	
    	$data['qfpage'] = $qfpage;
    	$data['result'] = $result;
    	return $data;
    }
    
    
    
    
    
    
    
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
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'hotel_id' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'saler' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_openid' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_table' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_id' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_id_name' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'order_amount' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_total' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_amount' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_time' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'status' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_amount_rate' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'grade_rate_type' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'remark' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'deliver_batch' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'last_update_time' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'partner_trade_no' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'send_time' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'dist_grop' => array( 
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
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