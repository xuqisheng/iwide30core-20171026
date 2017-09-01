<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Hotelordert_model extends MY_Model {

	public function get_resource_name()
	{
		return 'hotelordert';
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
		return 'hotels';
	}

	public function table_primary_key()
	{
	    return 'hotel_id';
	}
	
	public function attribute_labels()
	{
		return array(
		'hotel_id'=> 'ID',
		'inter_id'=>'酒店ID',
		'name'=> '酒店名称',
		'address'=> '酒店地址',
		'city'=> '城市',
		'count'=> '订单总数',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
	    'hotel_id',
	    'inter_id',
		'name',
		'address',
		'city',
	    'count'
		);
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
		$where= array();
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
		foreach ($params as $k=>$v){
			//过滤非数据库字段，以免产生sql报错
			if(in_array($k, $dbfields)) $where[$k]= $v;
		}
	
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
		
		$inter_id = $this->session->userdata['admin_profile']['inter_id'];
		if ($inter_id == 'ALL_PRIVILEGES') {
			
		}
		else {
		
		}
		
		
		/*////////////////////////////////
		
		这里要把数据库里没有的字段除掉，防出错。
		*/
		foreach ($select as $value) {
			if ($value == 'count') {
				unset($value);
			}
			else {
				$selectnew[] = $value;
			}
		}
		unset($select);
		$select = $selectnew;
		unset($selectnew);
		
		
		
		$select= count($select)==0? '*': implode(',', $select);
	
		//echo $select;die;
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->select(" {$select} ")->get_where($table, $where)->num_rows();
		//echo $total;
	
	
	
		$resulthotels= $this->_db()->select(" {$select} ")->order_by($sort)
		->limit($page_size, $offset)->get_where($table, $where)
		->result_array();
	
	
		foreach ($resulthotels as $key => $value) {
			$hotel_id = $value['hotel_id'];
			$countorder = $this->_db()->query("SELECT COUNT(0) as count FROM ".$this->db->dbprefix."hotel_orders WHERE hotel_id='".$hotel_id."'")->result_array();
			$count = $countorder[0]['count'];
			$value['count'] = $count;
			$result[] = $value;
		}
	
			
		if($format=='array'){
			$tmp= array();
			$field_config= $this->get_field_config('grid');
			foreach ($result as $k=> $v){
				//判断combobox类型需要对值进行转换
				foreach($field_config as $sk=>$sv){
					if($field_config[$sk]['type']=='combobox') {
						if( isset($field_config[$sk]['select'][$v[$sk]])){
							$v[$sk]= $field_config[$sk]['select'][$v[$sk]];
						}
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
				}//---
	
				$el= array_values($v);
				$el['DT_RowId']= $v[$this->table_primary_key()];
				$tmp[]= $el;
			}
			$result= $tmp;
		}
	
		return array(
				'total'=>$total,
				'data'=>$result,
				'page_size'=>$page_size,
				'page_num'=>$current_page,
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
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
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
		
		
		/*////////////////////////////////
		
		这里要把数据库里没有的字段除掉，防出错。
		*/
		foreach ($select as $value) {
			if ($value == 'count') {
				unset($value);
			}
			else {
				$selectnew[] = $value;
			}
		}
		unset($select);
		$select = $selectnew;
		unset($selectnew);
		
		
		$select= count($select)==0? '*': implode(',', $select);
	
		$total= $this->_db()->select(" {$select} ")->get_where($table, $where)->num_rows();
		//echo $total;
	
		$resulthotels= $this->_db()->select(" {$select} ")->order_by($sort)
		->limit($params['length'], $params['start'])->get_where($table, $where)
		->result_array();
		
		
		foreach ($resulthotels as $key => $value) {
			$hotel_id = $value['hotel_id'];
			$countorder = $this->_db()->query("SELECT COUNT(0) as count FROM ".$this->db->dbprefix."hotel_orders WHERE hotel_id='".$hotel_id."'")->result_array();
			$count = $countorder[0]['count'];
			$value['count'] = $count;
			$result[] = $value;
		}
		
		
		
	
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
                            'hotel_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '3%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	    		'inter_id' => array(
	    				'grid_ui'=> '',
	    				'grid_width'=> '3%',
	    				//'form_ui'=> ' disabled ',
	    				//'form_default'=> '0',
	    				//'form_tips'=> '注意事项',
	    				//'form_hide'=> TRUE,
	    				//'function'=> 'show_price_prefix|￥',
	    				'type'=>'text',	//textarea|text|combobox|number|email|url|price
	    		),
                                        'name' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'address' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'city' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '3%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    //'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
	    		'count' => array(
	    				'grid_ui'=> '',
	    				'grid_width'=> '3%',
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
	    return array('field'=>'count', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	
}
