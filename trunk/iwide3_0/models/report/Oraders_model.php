<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Oraders_model extends MY_Model { 

    public function get_resource_name() 
    { 
        return 'Oraders_model'; 
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
    
    public function order_count($params) {
    	$count = $this->_db('iwide_r1')->query("SELECT COUNT(*) AS count FROM iwide_hotel_orders WHERE ".$params." limit 1")->result_array();
    	
    	//print_r($params);print_r("SELECT COUNT(*) AS count FROM iwide_hotel_orders WHERE ".$params." limit 1");die();
    	
    	return $count;
    }
    
    public function order_sum($totle,$params) {
    	$sum = $this->_db('iwide_r1')->query("SELECT SUM(".$totle.") as sum FROM iwide_hotel_orders WHERE ".$params." limit 1")->result_array();
    	return $sum;
    }
    
    public function get_hotels($entity_id = '',$inter_id) {
    	 
    	$entity_id_sql = '';
    	if ($entity_id) {
    		$entity_id_sql = " and hotel_id in (".$entity_id.")";
    	}
    	 
    	$hotels = $this->_db('iwide_r1')->query("SELECT * FROM iwide_hotels WHERE inter_id='".$inter_id."' ".$entity_id_sql)->result_array();
    	return $hotels;
    }
    
    /*
    public function get_hotels($entity_id = 0,$inter_id) {
    	
    	$hotels = $this->_db('iwide_r1')->query("SELECT * FROM iwide_hotels WHERE inter_id='".$inter_id."' and hotel_id in (".$entity_id.") ")->result_array();
    	return $hotels;
    }*/
    
    
    public function get_rooms($hotel_id,$inter_id) {
    	 
    	$hotel_id_sql = '';
    	if ($hotel_id) {
    		$hotel_id_sql = " hotel_id='".$hotel_id."' and ";
    	}
    	
    	$rooms = $this->_db('iwide_r1')->query("SELECT * FROM iwide_hotel_rooms WHERE ".$hotel_id_sql." inter_id='".$inter_id."' ")->result_array();
    	return $rooms;
    }
    
    public function get_rooms_order_count($roomsid,$condition) {
    	$sql = "SELECT count(*) as count FROM iwide_hotel_order_items LEFT JOIN iwide_hotel_orders ON iwide_hotel_orders.inter_id = iwide_hotel_order_items.inter_id AND iwide_hotel_orders.orderid = iwide_hotel_order_items.orderid WHERE iwide_hotel_order_items.room_id='".$roomsid."' ".$condition;
    	$rooms_count = $this->_db('iwide_r1')->query($sql)->result_array();
    	return $rooms_count;
    }
    
    public function user_sex($inter_id_filter='') {//0没有1男2女
    	$sql = 'SELECT count(*) as count FROM iwide_member
INNER JOIN iwide_hotel_orders ON iwide_hotel_orders.openid = iwide_member.openid AND iwide_member.inter_id = iwide_hotel_orders.inter_id
INNER JOIN iwide_member_additional ON iwide_member.mem_id = iwide_member_additional.mem_id where sex=1 '.$inter_id_filter.';';
    	
    	$user_sex1 = $this->_db('iwide_r1')->query($sql)->result_array();
    	
    	$sql = 'SELECT count(*) as count FROM iwide_member
    	INNER JOIN iwide_hotel_orders ON iwide_hotel_orders.openid = iwide_member.openid AND iwide_member.inter_id = iwide_hotel_orders.inter_id
    	INNER JOIN iwide_member_additional ON iwide_member.mem_id = iwide_member_additional.mem_id where sex=2 '.$inter_id_filter.';';
    	 
    	$user_sex2 = $this->_db('iwide_r1')->query($sql)->result_array();
    	
    	$sql = 'SELECT count(*) as count FROM iwide_member
    	INNER JOIN iwide_hotel_orders ON iwide_hotel_orders.openid = iwide_member.openid AND iwide_member.inter_id = iwide_hotel_orders.inter_id
    	INNER JOIN iwide_member_additional ON iwide_member.mem_id = iwide_member_additional.mem_id where sex=0 '.$inter_id_filter.';';
    	
    	$user_sex0 = $this->_db('iwide_r1')->query($sql)->result_array();
    	
    	
    	return array('sex1'=>$user_sex1,'sex2'=>$user_sex2,'sex0'=>$user_sex0);
    }
    
    public function user_old($inter_id_filter='') {
    	$sql = 'SELECT openid,COUNT(*) FROM iwide_hotel_orders '.$inter_id_filter.' GROUP BY 1 HAVING COUNT(*)=1;';
    	$user_old1 = $this->_db('iwide_r1')->query($sql)->result_array();
    	$user_old1 = count($user_old1);
    	
    	$sql = 'SELECT COUNT(*) AS count FROM iwide_hotel_orders'.$inter_id_filter;
    	$user_old2_arr = $this->_db('iwide_r1')->query($sql)->result_array();
    	
    	$user_old2 = $user_old2_arr[0]['count'] - $user_old1;
    	return array('old1'=>$user_old1,'old2'=>$user_old2);
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