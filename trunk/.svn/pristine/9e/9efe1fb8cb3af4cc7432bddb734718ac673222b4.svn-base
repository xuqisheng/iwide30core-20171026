<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	protected $db_soma = 'iwide_soma';
	protected $db_soma_read = 'iwide_soma_r';

	protected $can_log= true;
	protected $db_write= 'iwide_rw';
	protected $db_read= 'iwide_r1';
	protected $db_resource= array();
	protected $db_dbforge= array();
	protected $db_dbutil= array();

	protected $_admin_inter_id = array();
	protected $_admin_hotels = array();
	
	protected $_pk;
	protected $_attribute= array();
	protected $_data= array();
	protected $_data_org= array();
	public $data_array= array();//关联数组

	public function get_resource_name(){}

	public function table_name(){}			//定义此数据模型跟哪个数据库表关联
	public function table_primary_key(){}	//此数据表中主键名称是什么

	public function attribute_labels(){}	//此数据表的对应字段的中文标识
	public function attribute_ui(){}		//在grid/edit视图中的显示特征（是否显示、宽度、注释、显示类型等等）
	public function grid_fields(){}			//在grid视图中要显示那几个字段
	//public function default_sort_field(){}	//在grid视图中默认以哪个字段作为排序。

	//定义 m_save 保存时不做转义字段
	public function unaddslashes_field()
	{
	    return array();
	}
	
	public function _shard_db($inter_id=NULL)
	{
	    return $this->_db();
	}
	
	public function _shard_table($basename, $inter_id=NULL )
	{
	    return $basename;
	}
	
	/**
	 * Class constructor
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
        //$this->load->database();	// Use by $this->_db(), $this->_dbforge();
        //$db2 = $this->load->database('other_dbcon', TRUE);
        
        //$this->load->dbutil();	// after $this->load->database(), Use by $this->_dbutil();
        //$this->db->db_select('other_db');		// Use by $this->_db();
        
		return $this;
	}
	
	/**
	 * 初始化 $this->_admin_hotels ，根据会话值，装入 本管理员能管理的酒店id，
	 * @return string
	 */
	public function _init_admin_hotels()
	{
	    $temp_id= $this->session->get_temp_inter_id();
	    if($temp_id){
	        $inter_id= $temp_id;
	    } else{
	        $inter_id= $this->session->get_admin_inter_id();
	    }
	    if( $inter_id==FULL_ACCESS ){
	        //全局权限
	        $this->_admin_hotels= FULL_ACCESS;
	        $this->_admin_inter_id= FULL_ACCESS;
	        return '';
	        
	    } else {
	        $this->_admin_inter_id= $inter_id;
	        //其他情况：
	        //1，限定inter_id，不限定 hotel
	        //2，限定inter_id，限定hotel，忽略 inter_id
	        //3，不限定 inter_id，不限定hotel
	        $hotels= $this->session->get_admin_hotels();
	        //var_dump($hotels);var_dump($inter_id);die;
	        if( $hotels ){
                $hotel_array= explode(',', $hotels);
	            $this->_admin_hotels= $hotel_array;
	            return '';
	            
	        } else if( $inter_id && !$hotels ){
	            //var_dump($hotels);var_dump($inter_id);die;
	            $this->_admin_hotels= array();
	            $this->load->model('hotel/hotel_model');
	            $hotel_data= $this->hotel_model->get_hotel_hash(array('inter_id'=> $inter_id));
	            //print_r($hotel_data);die;
	            foreach ($hotel_data as $v){
	                $this->_admin_hotels[]= $v['hotel_id'];
	            }
	            //print_r($this->_admin_hotels);die;
	            return '';
	             
	        } else  {
	            $this->_admin_hotels= array();
	            $this->_admin_inter_id= FALSE;
	            return '';
	        }
	    }
	}

	protected function _db($select=NULL)
	{
		$select= $select? $select: $this->db_write;
		if( !isset($this->db_resource[$select]) ) {
			$this->db_resource[$select]= $this->load->database($select, TRUE);
		}
		return $this->db_resource[$select];
	}
	
	protected function _dbforge($select=NULL)
	{
		$select= $select? $select: $this->db_write;
		if( !isset($this->db_dbforge[$select]) ) {
			$this->db_dbforge[$select]= $this->load->dbforge($select, TRUE);
		}
		return $this->db_dbforge[$select];
	}
	
	protected function _dbutil($select=NULL)
	{
		$select= $select? $select: $this->db_write;
		if( !isset($this->db_dbutil[$select]) ) {
			$this->db_dbutil[$select]= $this->load->dbutil($select, TRUE);
		}
		return $this->db_dbutil[$select];
	}
	
	/**
	 * @param String $type   grid|form
	 * 统一生成字段配置数组，赋予模板
	 */
	public function get_field_config($type='grid')
	{
	    $data= array();
	    if($type=='grid'){
	        $show= $this->grid_fields();
	        //grid多选状态必须有主键
	        array_unshift( $show, $this->table_primary_key() );
	        
	    } else {
	        //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
	        $show= $this->_shard_db()->list_fields($this->table_name());
	    }
	    $fields= $this->attribute_labels();
	    $fields_ui= $this->attribute_ui();
	    foreach ($show as $v){
	        $data[$v]['label']= $fields[$v];
	        
	        if($type=='grid'){
	            //grid所需配置信息
	            if( array_key_exists($v, $fields_ui) ){
	                $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui'])?$fields_ui[$v]['grid_ui']: '';
	                $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width'])?$fields_ui[$v]['grid_width']: "";
	            	$data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function'])? $fields_ui[$v]['grid_function']: FALSE;
	            	$data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
	                $data[$v]['type'] = isset($fields_ui[$v]['type'])?$fields_ui[$v]['type']: 'text';
	                if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
	            }
	            
	        } else if($type=='form') {
	            //form所需配置信息
                $data[$v]['js_config'] = isset($fields_ui[$v]['js_config'])? $fields_ui[$v]['js_config']: '';
                $data[$v]['input_unit'] = isset($fields_ui[$v]['input_unit'])? "<div class='input-group-addon'>{$fields_ui[$v]['input_unit']}</div>" : '';
                $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui'])? $fields_ui[$v]['form_ui']: '';
	            $data[$v]['form_tips'] = !empty($fields_ui[$v]['form_tips'])? $fields_ui[$v]['form_tips']: NULL;
	            $data[$v]['form_default'] = isset($fields_ui[$v]['form_default'])? $fields_ui[$v]['form_default']: NULL;
	            $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide'])? $fields_ui[$v]['form_hide']: FALSE;
	            $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
	            $data[$v]['type'] = isset($fields_ui[$v]['type'])? $fields_ui[$v]['type']: 'text';
	            if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
	            if( isset($fields_ui[$v]['form_type'])) $data[$v]['type'] = $fields_ui[$v]['form_type'];
	        }
	    }
	    /**
	     * Array => (
    	      [status] => Array (
        	     [label] => 状态
        	     [grid_ui] => 'width:80px;'
        	     [grid_width] => '20%'
        	     [type] => combobox
        	     [select] => Array (
            	     [1] => 正常
            	     [2] => 禁用
        	     )
    	      )
	     * )
	     *
	     */
	    //print_r($data);die;
	    return $data;
	}

	/**
	 * 根据 @see get_field_config() 方法生成的字段配置数组生成 datatable 的多层次对象获取数据[未完成]
"columns": [
	{ "data": "name" },
	{ "data": "hr.position" },
	{ "data": "contact.0" },
	{ "data": "contact.1" },
	{ "data": "hr.start_date" },
	{ "data": "hr.salary" }
],
	 * @param Array $fields_config
	 */
	public function get_column_config($fields_config)
	{
		$columns= array();
		foreach ($fields_config as $k=> $v) {
// 			if( isset($v['type']) && $v['type']=='combobox'){
// 				$columns[]= array('dataSet'=> $v['select'] );
// 			} else 
				$columns[]= array('dataSet'=> $k);
		}
		return $columns;
	}

	/**
	 * 普通模型数据读取，与find_all 类型。
	 * @param Array $params 过滤条件
	 * @param Array $select 选择哪些字段
	 * @param string $format array|object
	 * @return unknown
	 */
	public function get_data_filter( $params=array(), $select= array(), $format='array' )
	{
	    $table= $this->table_name();
	    $select= count($select)==0? '*': implode(',', $select);
	    $this->_shard_db()->select(" {$select} ");
	    
	    $where= array();
	    $dbfields= array_values($fields= $this->_shard_db()->list_fields($table));
	    foreach ($params as $k=>$v){
	        //过滤非数据库字段，以免产生sql报错
	        if(in_array($k, $dbfields) && is_array($v)){
	            $this->_shard_db()->where_in($k, $v);
	        } else if(in_array($k, $dbfields)) {
	            $this->_shard_db()->where($k, $v);
	        }
	    }
	    $result= $this->_shard_db()->get($table);
	    if($format=='object') return $result->result();
    	else return $result->result_array();
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
	    $where= $where_in= array();
	    $dbfields= array_values($fields= $this->_shard_db()->list_fields($table));
	    foreach ($params as $k=>$v){
	        //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
	        if(in_array($k, $dbfields) ){
	            if( is_array($v) ){
	                $where_in[$k]= $v;
	            } else {
	                $where[$k]= $v;
	            }
	        }
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
	    $select= count($select)==0? '*': implode(',', $select);
	    
	    //echo $select;die;
	    $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
	    if( count($where_in)>0 ){
	        foreach ($where_in as $k => $v ){
	            if( count($v) ) $this->_shard_db()->where_in($k, $v);
	        }
	    }
	    $total= $this->_shard_db()->select(" {$select} ")->get_where($table, $where)->num_rows();
	    //echo $total;

	    if( count($where_in)>0 ){
	        foreach ($where_in as $k => $v ){
	            if( count($v) ) $this->_shard_db()->where_in($k, $v);
	        }
	    }
	    $result= $this->_shard_db()->select(" {$select} ")->order_by($sort)
		    ->limit($page_size, $offset)->get_where($table, $where)
		    ->result_array();
	    //print_r($result);
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
	    		$this->data_array[] = $v;
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
		$where= $where_in= array();
		$dbfields= array_values($fields= $this->_shard_db()->list_fields($table));
	    foreach ($params as $k=>$v){
	        //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
	        if(in_array($k, $dbfields) ){
	            if( is_array($v) ){
	                $where_in[$k]= $v;
	            } else {
	                $where[$k]= $v;
	            }
	        }
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
		
		
	    /** 总条数计算  **/
		$search= $this->_shard_db()->select(" {$select} ");
	    if( count($where_in)>0 ){
	        foreach ($where_in as $k => $v ){
	            if( count($v) ) $this->_shard_db()->where_in($k, $v);
	        }
	    }
	    if( isset($params['f_like']) && count($params['f_like'])>0 ){
		    //模糊匹配参数
		    foreach ($params['f_like'] as $sk=> $sv) $search= $search->like($sk, $sv);
		}
		if( isset($params['f_match']) && count($params['f_match'])>0 ){
		    //准确匹配参数
		    foreach ($params['f_match'] as $sk=> $sv) $search= $search->where($sk, $sv);
		}
		$total= $search->get_where($table, $where)->num_rows();
		
		/** 数据查询 **/
		$search= $this->_shard_db()->select(" {$select} ");
	    if( count($where_in)>0 ){
	        foreach ($where_in as $k => $v ){
	            if( count($v) ) $this->_shard_db()->where_in($k, $v);
	        }
	    }
		if( isset($params['f_like']) && count($params['f_like'])>0 ){
		    //模糊匹配参数
		    foreach ($params['f_like'] as $sk=> $sv) $search= $search->like($sk, $sv);
		}
		if( isset($params['f_match']) && count($params['f_match'])>0 ){
		    //准确匹配参数
		    foreach ($params['f_match'] as $sk=> $sv) $search= $search->where($sk, $sv);
		}
		$result= $search->order_by($sort)
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
	 * 获取默认排序字段在grid罗列字段中的索引序号（grid模板datatable.js中使用）
	 * @param unknown $field
	 * @return Ambigous <number, unknown>
	 */
	public function field_index_in_grid($field)
	{
	    $index= 0;
	    $fields= $this->grid_fields();
	    foreach($fields as $k=>$v){
	        if($v==$field) $index= $k;
	    }
	    return $index;
	}
	public function field_name_in_grid($index)
	{
	    $name= '';
	    $fields= $this->grid_fields();
	    foreach($fields as $k=>$v){
	        if($k==$index) $name= $v;
	    }
	    return $name;
	}
	
	/**
	 * 将多维数组转换为hash形式数组，当 $value_key为 NULL时，返回数组中key将会默认取数组的一级key
	 * @param Array $array array( 'k1'=> array('key11'=>'val11', 'key12'=>'val12'), 'k2'=> array('key21'=>'val21', 'key22'=>'val22')
	 * @return Array $array array('key1'=>'val1', 'key2'=>'val2', ...)
	 */
	public function array_to_hash($array, $label_key, $value_key=NULL)
	{
	    $data= array();
	    foreach ($array as $k=>$v) {
	    	//过滤额外增加的数据 如 key=0的不完整数据
	    	if( isset($v[$label_key]) ){
	    		if( $value_key==NULL ) {
	    			$key= $k;
	    		} else {
	    			$key= $v[$value_key];
	    		}
	    		$data[$key]= $v[$label_key];
	    	}
	    }
	    return $data;
	}
	/**
	 * @example:  
     *       $topics= $this->shp_topic->get_data_filter($filter);
     *       $topics= $this->shp_topic->array_to_hash_multi($topics, 'identity|page_title', 'topic_id');
	 * @param  [type] $array      [description]
	 * @param  [type] $label_keys [description]
	 * @param  [type] $value_key  [description]
	 * @return [type]             [description]
	 */
	public function array_to_hash_multi($array, $label_keys, $value_key=NULL)
	{
	    $data= array();
	    foreach ($array as $k=>$v) {
    		if( $value_key==NULL ) {
    			$key= $k;
    		} else {
    			$key= $v[$value_key];
    		}
			$labels= '';
			foreach( explode('|', $label_keys) as $sv){
				$labels.=  $v[$sv]. ' |';
			}
    		$data[$key]= substr($labels, 0, -1);
	    }
	    return $data;
	}
	/**
	 * 将哈希数组变为 value, text的数组形式
	 * @param Array $array array('key1'=>'val1', 'key2'=>'val2', ...)
	 * @return Array $array array( array('value'=>'key1', 'text'=>'val1'), array('value'=>'key2', 'text'=>'val2')
	 */
	public function hash_to_option($array)
	{
	    //[{value:'',text:'All'},{value:'P',text:'P'},{value:'N',text:'N'}],
	    $data= array();
	    $array[]= '全部';
	    foreach ($array as $k=>$v) {
	        $data[]=  array('value'=>$k, 'text'=>$v);
	    }
	    return $data;
	}
	/**
	 * 将哈希数组变为 value, text的html输出形式
	 * @param Array $array array('key1'=>'val1', 'key2'=>'val2', ...)
	 * @param String $selected 选中项目的值
	 * @return String <option value="key1" selected="selected">value1</option>....
	 */
	public function hash_to_optionhtml($array, $selected=NULL)
	{
	    $html= '';
	    foreach ($array as $k=>$v) {
	        if($selected!==NULL&& $selected== $k) $html.=  "<option value='{$k}' selected='selected'>{$v}</option>";
	        elseif( is_array($selected) && in_array($k, $selected) ) $html.=  "<option value='{$k}' selected='selected'>{$v}</option>";
	        else $html.=  "<option value='{$k}'>{$v}</option>";
	    }
	    return $html;
	}
	
	/**
	 * 返回单条记录
	 * @param array $where
	 * @param string $select
	 * @return array|false
	 */
	public function find( $where= array(), $select='*' )
	{
	    $table= $this->table_name();
	    $result= $this->_shard_db()->select(" {$select} ")
    	    ->limit('1')
    	    ->get_where($table, $where)
    	    ->result_array();
	    return current($result);
	}
	
	/**
	 * 返回多条记录
	 * @param Array $where
	 * @param string $sort
	 * @param string $limit
	 * @param string $select
	 * @return Array
	 */
	public function find_all( $where= array(), $sort=NULL, $limit=NULL, $select='*' )
	{
	    $table= $this->table_name();
	    $result= $this->_shard_db()->select(" {$select} ")
    	    ->order_by($sort)
    	    ->limit($limit)
    	    ->get_where($table, $where)
    	    ->result_array();
	    return $result;
	}

	/**
	 * @see CI_Model::__get()
	 */
	public function __get($name)
	{
        //这里好危险。
        if (parent::__get($name)) {
            return parent::__get($name);
        }

        if (isset($this->_data[$name]) ) {
            return $this->_data[$name];
        }

        return null;
    }
	
	/**
	 * 用主键定位模型
	 * @param Sting $id
	 * @return MY_Model|NULL
	 */
	public function load($id)
	{
	    $pk= $this->table_primary_key();
	    $values= $this->find(array($pk=> $id));
	    
	    if($values){
    	    $table= $this->table_name();
    	    $fields= $this->_shard_db()->list_fields($table);
    	    $this->_attribute= array_values($fields);
    	    
    	    foreach ($fields as $v) {
    	        $this->_data[$v]= $values[$v];
    	    }
    	    //确保 $this->_data_org 的值是完整的
    	    $this->_data_org = $this->_data;
    	    return $this;
    	    
	    } else {
	        return NULL;
	    }
	}

	/**
	 * 模型数据是否已经实例化
	 * @return boolean
	 */
	public function has_data()
	{
	    return count($this->_data)>0? TRUE: FALSE;
	}
	
	/**
	 * 获取模型定位后的数据，之前需要执行 load方法
	 * @return Array
	 */
	public function m_data()
	{
	    return $this->_data;
	}

	/**
	 * 获取单个字段属性值
	 * @param $name
	 * @return null|mixed
	 * @author renshuai  <renshuai@jperation.cn>
	 */
	public function m_get($name)
	{
	     $data= $this->m_data();
	     if( array_key_exists($name, $data) ){
	         return $data[$name];
	         
	     } else {
	         return NULL;
	     }
	}

	/**
	 * 设置单个字段
	 * @param String $name
	 * @param String $value
	 * @return MY_Model
	 */
	public function m_set($name, $value)
	{
	    $table= $this->table_name();
	    $fields= $this->_shard_db()->list_fields($table);
	    $this->_attribute= array_values($fields);
	    $unaddslashes_field= $this->unaddslashes_field();
	    if( in_array($name, $this->_attribute) ){
	        //$this->_data[$name]= $value;
	        if( !in_array($name, $unaddslashes_field) ) 
	            $this->_data[$name]= addslashes($value);
	        else $this->_data[$name] = $value;
	    }
	    return $this;
	}

	/**
	 * 设置多个字段
	 * @param Array $data
	 * @return MY_Model
	 */
	public function m_sets($data)
	{
	    $table= $this->table_name();
	    $fields= $this->_shard_db()->list_fields($table);
	    $this->_attribute= array_values($fields);
	    $unaddslashes_field= $this->unaddslashes_field();
	    foreach ($data as $k=>$v){
	        if( in_array($k, $this->_attribute) ){
	            //$this->_data[$k]= $v;
    	        if( !in_array($k, $unaddslashes_field) ) 
    	            $this->_data[$k]= addslashes($v);
    	        else $this->_data[$k] = $v;
	        }
	    }
	    return $this;
	}

	public function insert_id()
	{
	    return $this->_shard_db()->insert_id();
	}
	/**
	 * 获取数据变动情况，返回变动字段的变动前后值，没有变动返回空数组
	 * Array(
	 * 	'field1'=> array(
	 * 		'org'=> '原来的值',
	 * 		'cur'=> '现在的值',
	 * 	 ),
	 * 	'field2'=> array(
	 * 		'org'=> '原来的值',
	 * 		'cur'=> '现在的值',
	 * 	 ),
	 *   ...
	 * );
	 * @return Array $changed  空数组即没有任何变动
	 */
	public function m_change()
	{
	     if( empty($this->_data_org) || empty($this->_data)){
	     	return array();
	     }
	     $changed= array();
	     foreach ($this->_data_org as $k=> $v ) {
	     	//只考虑 有新值的情况，新增的model，没有运行load方法，_data_org 为空数据
	     	if( isset($this->_data[$k]) ){
	     		if( $this->_data[$k] !== $this->_data_org[$k] ){
	     			$changed[$k]= array(
	     				'org'=> $this->_data_org[$k],
	     				'cur'=> $this->_data[$k],
	     			);
	     		}
	     	}
	     }
	     //print_r($changed);die;
	     return $changed;
	}
	
	/**
	 * 保存模型数据，3种用法：
	 *     1，实例化、load方法之后，m_set多个变量之后 m_save保存[不传入参数]
	 *     2，实例化、load方法之后，直接m_save保存[需传入参数]
	 *     3，实例化后直接 m_save保存[需传入参数]，用于插入新数据
	 * @param boolean $update 新增且主键字段为手工生成时，update=FALSE -- 2015-12-07 ounianfeng --
	 * @return boolean
	 */
	public function m_save($data=NULL,$update = TRUE)
	{
	    $pk= $this->table_primary_key();
	    $table= $this->table_name();
	    $fields= $this->_shard_db()->list_fields($table);
	    $unaddslashes_field= $this->unaddslashes_field();
	    
	     //手工生成主键字段，update=FALSE -- 2015-12-07 ounianfeng
// 	    if( isset($this->_data[$pk]) && $this->_data[$pk]>0 ) {
	    if( isset($this->_data[$pk]) && !empty($this->_data[$pk]) && $update ) {
	        if($data){
	            foreach ($data as $k=>$v){
	                if( in_array($k,$fields) ){
	                	if( !in_array($k, $unaddslashes_field) ){
	                    	//$this->_data[$k]= $v;
	                    	$this->_data[$k]= addslashes($v);
	                    } else { $this->_data[$k] = $v; }
	                }
	            }
	        }
	        $where= array( $pk=> $this->_data[$pk] );
	        $this->_shard_db()->where($where);
	        $result= $this->_shard_db()->update($table, $this->_data);
	        return $result;
	         
	    } else {
	        if($data){
	            foreach ($data as $k=>$v){
	                if( in_array($k,$fields) ){
	                	if( !in_array($k, $unaddslashes_field) ){
	                    	//$this->_data[$k]= $v;
	                    	$this->_data[$k]= addslashes($v);
	                    } else { $this->_data[$k] = $v; }
	                }
	            }
	        }
	        //手工生成主键字段时，不释放主键的变量 -- 2015-12-07 ounianfeng --
	        if($update)unset($this->_data[$pk]);
	        $result= $this->_shard_db()->insert($table, $this->_data);
	        //成功插入后返回last insert id
	        if($result==TRUE){
	            return $this->_shard_db()->insert_id();
	        } else {
	            return $result;
	        }
	    }
	}
	
	/**
	 * 删除一条数据
	 * @return boolean|unknown
	 */
	public function m_delete()
	{
	    $pk= $this->table_primary_key();
	    $attribute= $this->_attribute;
	    if( !array_key_exists($pk, $this->_data ) || !$this->_data[$pk] ) {
	        return FALSE;
	        
	    } else {
	        $table= $this->table_name();
	        $where= array( $pk=> $this->_data[$pk] );
	        $result= $this->_shard_db()->delete($table, $where);
	        return $result;
	    }
	}
	
	/**
	 * in条件删除多条数据
	 */
	public function delete_in($array)
	{
	    //注意数据的安全性校验
		$ids = (array) $array;
	    $table= $this->table_name();
	    $pk= $this->table_primary_key();
		$this->_shard_db()->where_in($pk, $ids);
	    $result= $this->_shard_db()->delete($table);
	    return $result;
	}
}

//根据自身模块定义若干模块内model共享的函数
//created by libinyan
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Model_Mall.php";
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Model_Soma.php";

//自定义会员模块model,created by knight
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Model_Member.php";
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Model_Hotel.php";
