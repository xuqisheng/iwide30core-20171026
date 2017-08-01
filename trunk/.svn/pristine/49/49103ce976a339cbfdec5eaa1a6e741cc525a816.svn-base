<?php 
class Gridchargeorder extends MY_Model
{
	const TABLE_CHARGE_ORDER     = 'member_charge_order';
	const TABLE_MEMBER_INFO     = 'member_additional';
	
	public function table_name()
	{
		return 'iwide_member_charge_order';
	}
	
	public function table_primary_key()
	{
		return 'cgo_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'cgo_id'         => '序号',
			'name'           => '会员名',
			'mem_id'         => '会员ID',
			'order_number'   => '订单号',
			'transaction_id' => '微信流水号',
			'amount'         => '充值金额',
			'paid'           => '是否付款',
			'note'           => '备注',
			'create_time'    => '充值日期'
		);
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		return array('cgo_id', 'mem_id', 'name', 'order_number', 'transaction_id', 'amount','paid','note','create_time');
	}
	
	public function attribute_ui()
	{
		return array(
			'cgo_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'mem_id' => array(
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
			'order_number' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'transaction_id' => array(
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
			'paid' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=>self::toArray()
			),
			'note' => array(
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
		return array('field'=>'cgo_id', 'sort'=>'desc');
	}
	
	public function filter( $params=array(), $select= array(), $format='array' )
	{
		$table= $this->table_name();
		$where= array();
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
		foreach ($params as $k=>$v){
			//过滤非数据库字段，以免产生sql报错
			if(in_array($k, $dbfields) || ($k==self::TABLE_MEMBER.'.inter_id')) $where[$k]= $v;
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
	
		$select = str_replace('mem_id', self::TABLE_CHARGE_ORDER.'.mem_id', $select);
		$select = str_replace('inter_id', self::TABLE_CHARGE_ORDER.'.inter_id', $select);
		$select = str_replace('create_time', self::TABLE_CHARGE_ORDER.'.create_time', $select);
	
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->get_where($table, $where)->num_rows();
	
		$result= $this->_db()->select(" {$select} ")
		->join(self::TABLE_MEMBER_INFO, self::TABLE_CHARGE_ORDER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
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
			'total'=>$total,
			'data'=>$result,
			'page_size'=>$page_size,
			'page_num'=>$current_page,
		);
	}
	
	static public function toArray()
	{
		return array(
			'0' => "否",
			'1' => "是"
		);
	}
}