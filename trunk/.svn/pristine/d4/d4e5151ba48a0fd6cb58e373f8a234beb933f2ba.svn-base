<?php 
class Gridbonusrecord extends MY_Model
{
	const TABLE_CONSUMPTION     = 'member_consumption_record';
	const TABLE_MEMBER_INFO     = 'member_additional';
	const TABLE_MEMBER          = 'member';
	
	public function table_name()
	{
		return 'member_consumption_record';
	}
	
	public function table_primary_key()
	{
		return 'cr_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'cr_id'=>'序号',
			'name'=>'会员名',
			'level'=>'会员等级',
			'balance'=>'充值金额',
			'amount'=>'充值后金额',
			'create_time'=>'充值日期'
		);
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		return array('cr_id', 'name', 'level', 'balance', 'amount','create_time');
	}
	
	public function attribute_ui()
	{
		return array(
			'cr_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'name' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'level' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=>$this->getLevelHash(),
			),
			'balance' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'amount' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'create_time' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> '',
				'type'=>'text',
			),
		);
	}
	
	public static function default_sort_field()
	{
		return array('field'=>'cr_id', 'sort'=>'desc');
	}

	public function filter( $params=array(), $select= array(), $format='array' )
	{
	    $table= $this->table_name();
	    $where= array();
	    $dbfields= array_values($fields= $this->_db()->list_fields($table));
	    foreach ($params as $k=>$v){
	        //过滤非数据库字段，以免产生sql报错
	        if($k=='inter_id'){
	        	if(in_array($k, $dbfields)) $where[self::TABLE_CONSUMPTION.'.'.$k]= $v;
	        }else{
	        	if(in_array($k, $dbfields)) $where[$k]= $v;
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
	
	    $select = str_replace('mem_id', self::TABLE_MEMBER.'.mem_id', $select);
	    $select = str_replace('inter_id', self::TABLE_MEMBER.'.inter_id', $select);
	    $select = str_replace('level', self::TABLE_MEMBER.'.level', $select);
	    $select = str_replace('balance', self::TABLE_CONSUMPTION.'.balance', $select);
	    $select = str_replace('amount', self::TABLE_MEMBER.'.balance as amount', $select);
	    $select = str_replace('create_time', self::TABLE_CONSUMPTION.'.create_time', $select);
	
	    $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
	    $total= $this->_db()->get_where($table, $where)->num_rows();
	
	    $result= $this->_db()->select(" {$select} ")
	    ->join(self::TABLE_MEMBER, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER.'.mem_id', 'inner')
	    ->join(self::TABLE_MEMBER_INFO, self::TABLE_MEMBER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
	    ->order_by($sort)
	    ->limit($page_size, $offset)->get_where($table, $where)
	    ->result_array();
	    	
	    if($format=='array'){
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
	            }//---
	
	            $el= array_values($v);
	            $el['DT_RowId']= $v[$this->table_primary_key()];
	            $tmp[]= $el;
	        }
	        $result= $tmp;
	    }
	    //echo $this->_db()->last_query();exit;
	    return array(
	        'total'=>$total,
	        'data'=>$result,
	        'page_size'=>$page_size,
	        'page_num'=>$current_page,
	    );
	}
	public function filter_json( $params=array(), $select= array(), $format='array' )
	{
		$table= $this->table_name();
		$where= array();
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
		foreach ($params as $k=>$v){
			//过滤非数据库字段，以免产生sql报错
	        if($k=='inter_id'){
	        	if(in_array($k, $dbfields)) $where[self::TABLE_CONSUMPTION.'.'.$k]= $v;
	        }else{
	        	if(in_array($k, $dbfields)) $where[$k]= $v;
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
	
		$select = str_replace('mem_id', self::TABLE_MEMBER.'.mem_id', $select);
		$select = str_replace('inter_id', self::TABLE_MEMBER.'.inter_id', $select);
		$select = str_replace('level', self::TABLE_MEMBER.'.level', $select);
		$select = str_replace('balance', self::TABLE_CONSUMPTION.'.balance', $select);
		$select = str_replace('amount', self::TABLE_MEMBER.'.balance as amount', $select);
		$select = str_replace('create_time', self::TABLE_CONSUMPTION.'.create_time', $select);
	
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->get_where($table, $where)->num_rows();
	
		$result= $this->_db()->select(" {$select} ")
		->join(self::TABLE_MEMBER, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER.'.mem_id', 'inner')
		->join(self::TABLE_MEMBER_INFO, self::TABLE_MEMBER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
		->order_by($sort)
		->limit($page_size, $offset)->get_where($table, $where)
		->result_array();
			
		if($format=='array'){
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
				}//---
	
				$el= array_values($v);
				$el['DT_RowId']= $v[$this->table_primary_key()];
				$tmp[]= $el;
			}
			$result= $tmp;
		}
			
		return array(
			'draw'=> isset($params['draw'])? $params['draw']: 1,
			'data'=> $result, 
			'recordsTotal'=> $total,
			'recordsFiltered'=> $total,
		);
	}
	
	protected function getLevelHash()
	{
		$this->load->model('member/config','mconfig');
		$result = $this->mconfig->getConfig('level',true,$this->_admin_inter_id);
	
		if($result) {
			$ret = array();
			foreach($result->value as $k=>$v) {
				$ret[$k] = $v;
			}
			return $ret;
		} else {
			return array();
		}
	}
}