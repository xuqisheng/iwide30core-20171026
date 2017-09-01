<?php 

defined('BASEPATH') OR exit('No direct script access allowed'); 

class Wxfinanceexp_model extends MY_Model {

    public function get_resource_name() 
    { 
        return 'Wxfinanceexp_model';
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
        return 'hotel_orders'; 
    } 

    public function table_primary_key() 
    { 
     return 'id'; 
    } 
     
    public function attribute_labels() 
    { 
        return array( 
        'id'=> 'Id', 
        'hotel_id'=> 'Hotel_id', 
        'openid'=> 'Openid', 
        'inter_id'=> 'Inter_id', 
        'price'=> '金额', 
        'roomnums'=> '房间数', 
        'name'=> '入住人', 
        'tel'=> '手机', 
        'order_time'=> '下单时间', 
        'startdate'=> '入住时间', 
        'enddate'=> '离店时间', 
        'paid'=> 'Paid', 
        'orderid'=> '订单号', 
        'status'=> '订单状态', 
        'holdtime'=> 'Holdtime', 
        'paytype'=> '支付方式', 
        'isdel'=> 'Isdel', 
        'operate_reason'=> 'Operate_reason', 
        'remark'=> 'Remark', 
        'member_no'=> 'Member_no',
        'hotel_web_id'=>'酒店pmsID',
        'hotel_name'=>'酒店',
        'webs_orderid'=>'PMS订单号',
         'refund'=>'退款状态',
        ); 
    } 

    
    public function get_price_code_name($orderid) {
    	$result = $this->db->query("SELECT * FROM iwide_hotel_order_items WHERE orderid='".$orderid."' limit 1")->result_array();
    	return $result;
    }
    
    public function order_additions($orderid) {
    	$result = $this->db->query("SELECT * FROM iwide_hotel_order_additions WHERE orderid='".$orderid."' limit 1")->result_array();
    	return $result;
    }
    
    
    /**
     * @param Array GET 参数（过滤，排序，分页）
     * @param String $format 有2种数据规格：
     * 		'array':返回datatable组件所需要的数组形式
     * 		'':返回普通的对象数组
     * grid过滤，排序，分页时，过滤参数
     * 如需定制，请重写此函数
     */
    public function filter( $params=array(), $select= array(), $format='array' )
    {
    	$table= $this->table_name();
    	
    	/////////////////////////这里放筛选
    	$where = '1=1';/*存放1=1防止无条件时语句不完整*/
    	$dbfields= array_values($fields= $this->db->list_fields($table));
    	
    	//foreach ($params as $k=>$v){
    		//过滤非数据库字段，以免产生sql报错
    		//if(in_array($k, $dbfields)) $where[$k]= $v;
    	//}
    	//print_r($params);
    	
    	if (isset($params['inter_id'])) {
    		if ($params['inter_id']) {
    			$where = $where." and inter_id='".$params['inter_id']."'";
    		}
    	}
    	
    	if (isset($params['orderid'])) {
    		if ($params['orderid']) {
    			$where = $where." and orderid='".$params['orderid']."'";
    		}
    	}
    	if (isset($params['paytype'])) {
    		if ($params['paytype']) {
    			$where = $where." and paytype='".$params['paytype']."'";
    		}
    	}
    	if (isset($params['orderstatus'])) {
    		if ($params['orderstatus']) {
    			$where = $where." and status in (".implode($params['orderstatus'], ",").")";
    		}
    	}
    	if (isset($params['typeaddtime'])) {
    		if ($params['typeaddtime']==1 && $params['timedown'] && $params['timeup']) {
    			
    			$timestart = $params['timedown']." 00:00:00";
    			$timeend = $params['timeup']." 00:00:00";
    	
    			$where = $where." and order_time<'".strtotime($timeend)."' and order_time>='".strtotime($timestart)."'";
    		}
    		else if($params['typeaddtime']==0 && $params['addtime']) {
    			$addtime = $params['addtime'];
    			$timestart = $addtime."-01 00:00:00";
				$timeend = date('Y-m-d H:i:s',strtotime("+1 month",strtotime($timestart)));
				$where = $where." and order_time<'".strtotime($timeend)."' and order_time>='".strtotime($timestart)."'";
    		}
    	}
 
    	
    	/////////////////////////
    	//print_r($where);
    	/////////////////////////筛选结束
    
    	if( isset($params['sort_field']) && isset($params['sort_direct']) ){
    		$sort= $params['sort_field']. ' '. $params['sort_direct'];
    	} else
    		$pk= $this->table_primary_key();
    	$sort= "{$pk} DESC";  //默认排序
    
    	$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
    	$page_size= isset($params['page_size'])? $params['page_size']: $num;
    	$current_page= isset($params['page_num'])? $params['page_num']: 1;
    
    	if(count($select)==0) {
    		$select= $this->grid_fields();
    	}
    	$select= count($select)==0? '*': implode(',', $select);
    
    	//echo $select;die;
    	$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
//    	$total= $this->db->select(" {$select} ")->get_where($table, $where)->num_rows();
    	//echo $total;
    
//    	$result= $this->db->select(" {$select} ")->order_by($sort)
//    	->limit($page_size, $offset)->get_where($table, $where)
//    	->result_array();
//    	//print_r($result);
//
//    	if($format=='array'){
//    		$tmp= array();
//    		$field_config= $this->get_field_config('grid');
//    		foreach ($result as $k=> $v){
//    			//判断combobox类型需要对值进行转换
//    			foreach($field_config as $sk=>$sv){
//    				if($field_config[$sk]['type']=='combobox') {
//    					if( isset($field_config[$sk]['select'][$v[$sk]])){
//    						$v[$sk]= $field_config[$sk]['select'][$v[$sk]];
//    					}
//    					else $v[$sk]= '--';
//    				}
//    				if( $field_config[$sk]['grid_function'] ) {
//    					$funp= explode('|', $field_config[$sk]['grid_function']);
//    					$fun= $funp[0];
//    					$funp[0]= $v[$sk];
//    					$v[$sk]= call_user_func_array ($fun, $funp);
//    				} else if( $field_config[$sk]['function'] ) {
//    					$funp= explode('|', $field_config[$sk]['function']);
//    					$fun= $funp[0];
//    					$funp[0]= $v[$sk];
//    					$v[$sk]= call_user_func_array ($fun, $funp);
//    				}
//    			}//---
//
//    			$el= array_values($v);
//    			$el['DT_RowId']= $v[$this->table_primary_key()];
//    			$tmp[]= $el;
//    		}
//    		$result= $tmp;
//    	}
//
//    	$roomnums = 0;
//    	$roomnumsobj = $this->db->query("SELECT SUM(roomnums) as roomnums FROM ".$this->db->dbprefix."hotel_orders where ".$where)->result_array();
//    	if ($roomnumsobj) {
//    		$roomnums = $roomnumsobj[0]['roomnums'];
//    	}
//
//    	$roomnums = 0;
//    	$priceobj = $this->db->query("SELECT SUM(price) as price FROM ".$this->db->dbprefix."hotel_orders where ".$where)->result_array();
//    	if ($priceobj) {
//    		$price = intval(doubleval($priceobj[0]['price'])*100)/100;
//    	}
    	
    	return array(
//    			'total'=>$total,
//    			'data'=>$result,
    			'page_size'=>$page_size,
    			'page_num'=>$current_page,
//    			'roomnums'=>$roomnums,
//    			'price'=>$price,
    	);
    }
    
    /**
     * 功能跟filter差不多，作用于datatable grid ajax查询，下列为 $params 中带有的参数
     order[0][column]:6 排序列索引
     order[0][dir]:desc 排序方向
     start:0	开始记录
     length:20 每页条数
     search[value]: 搜索字眼
     search[regex]:false
     */
    public function filter_json( $params=array(), $select= array() )
    {
    	$table= $this->table_name();
    	$where= array();
    	$dbfields= array_values($fields= $this->db->list_fields($table));
    	foreach ($params as $k=>$v){
    		//过滤非数据库字段，以免产生sql报错
    		if(in_array($k, $dbfields)) $where[$k]= $v;
    	}
    
    	if( isset($params['order'][0]['column']) && isset($params['order'][0]['dir']) ){
    		$field= $this->field_name_in_grid($params['order'][0]['column']);
    		$sort= $field. ' '. $params['order'][0]['dir'];
    			
    	} else {
    		$pk= $this->table_primary_key();
    		$sort= "{$pk} DESC";  //默认排序
    	}
    
    	if(count($select)==0) {
    		$select= $this->grid_fields();
    	}
    	$select= count($select)==0? '*': implode(',', $select);
    
    	$total= $this->db->select(" {$select} ")->get_where($table, $where)->num_rows();
    	//echo $total;
    
    	$result= $this->db->select(" {$select} ")->order_by($sort)
    	->limit($params['length'], $params['start'])->get_where($table, $where)
    	->result_array();
    
    	$tmp= array();
    
    	$field_config= $this->get_field_config('grid');
    	foreach ($result as $k=> $v){
    		//判断combobox类型需要对值进行转换
    		foreach($field_config as $sk=>$sv){
    			if($field_config[$sk]['type']=='combobox') {
    				if( isset($field_config[$sk]['select'][$v[$sk]]))
    					$v[$sk]= $field_config[$sk]['select'][$v[$sk]];
    				else $v[$sk]= '--';
    			}
    			if( $field_config[$sk]['grid_function'] ) {
    				$funp= explode('|', $field_config[$sk]['grid_function']);
    				$fun= $funp[0];
    				$funp[0]= $v[$sk];
    				$v[$sk]= call_user_func_array ($fun, $funp);
    			} else if( $field_config[$sk]['function'] ) {
    				$funp= explode('|', $field_config[$sk]['function']);
    				$fun= $funp[0];
    				$funp[0]= $v[$sk];
    				$v[$sk]= call_user_func_array ($fun, $funp);
    			}
    		}//-----
    
    		$el= array_values($v);
    		$el['DT_RowId']= $v[$this->table_primary_key()];
    		$tmp[]= $el;
    	}
    	$result= $tmp;
    
    	return array(
    			'draw'=> isset($params['draw'])? $params['draw']: 1,
    			'data'=> $result,
    			'recordsTotal'=>$total,
    			'recordsFiltered'=>$total,
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
        'hotel_web_id',
        'hotel_name',
        'webs_orderid',
         'orderid',
         'order_time',
         'startdate',
         'enddate',
         'name',
         'tel',
         'roomnums',
         'price',
         'paytype',
         'paid',
         'status',
         'refund',
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
     
     
     
     $this->load->model("common/Enum_model");
     $pay_way = $this->Enum_model->get_enum_des("PAY_WAY");
     
     $hotel_order_status = $this->Enum_model->get_enum_des("HOTEL_ORDER_STATUS");
     

     return array( 
'id' => array( 
'grid_ui'=> '', 
'grid_width'=> '3%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'hotel_web_id' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'hotel_name' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'webs_orderid' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'orderid' => array(
'grid_ui'=> '', 
'grid_width'=> '5%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'order_time' => array(
'grid_ui'=> '', 
'grid_width'=> '5%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'startdate' => array(
'grid_ui'=> '', 
'grid_width'=> '5%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'enddate' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'name' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
'function'=> 'qfdate', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'tel' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'roomnums' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'price' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'paytype' => array(
'grid_ui'=> '', 
'grid_width'=> '10%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
'type'=>'text',    //textarea|text|combobox|number|email|url|price 
), 
'paid' => array(
'grid_ui'=> '', 
'grid_width'=> '5%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
//'type'=>'text',    //textarea|text|combobox|number|email|url|price 
		'type'=>'combobox',
		'select'=> $hotel_order_status
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
'refund' => array(
'grid_ui'=> '', 
'grid_width'=> '5%', 
//'form_ui'=> ' disabled ', 
//'form_default'=> '0', 
//'form_tips'=> '注意事项', 
//'form_hide'=> TRUE, 
//'function'=> 'show_price_prefix|￥', 
//'type'=>'text',    //textarea|text|combobox|number|email|url|price 
		'type'=>'combobox',
		'select'=> $pay_way
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

    public function sumAllPrice($data){

        $eprice=explode(",",$data);

        $oprice=0;

        foreach($eprice as $arr){

            $oprice = $oprice+$arr;

        }

        return $oprice;


    }

     
} 