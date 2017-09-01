<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gridbonus extends MY_Model
{
	const TABLE_MEMBER             = 'member';
	const TABLE_MEMBER_INFO        = 'member_additional';
	const TABLE_CONSUMPTION        = 'member_consumption_record';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('member/consume');
		return $this;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'iwide_member_consumption_record';
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
			'type'=>'种类',
			'every_bonus'=>'积分',
			'sum_bonus'=>'剩余积分',
			'note'=>'备注',
			'create_time'=>'日期'
		);
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		return array('cr_id', 'name', 'level', 'type', 'every_bonus', 'sum_bonus', 'note', 'create_time');
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
			'type' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=>self::toArray()
			),
			'every_bonus' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'sum_bonus' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'note' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'create_time' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
		);
	}
	
	public static function default_sort_field()
	{
		return array('field'=>'mem_id', 'sort'=>'desc');
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

		$select = str_replace('every_bonus', self::TABLE_CONSUMPTION.'.bonus as every_bonus', $select);
		$select = str_replace('sum_bonus', self::TABLE_MEMBER.'.bonus as sum_bonus', $select);
		$select = str_replace('create_time', self::TABLE_CONSUMPTION.'.create_time', $select);

		//echo $select;die;
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->from($table)->where('('.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CHARGE.' OR '.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CONSUME.')')->where($where)->get()->num_rows();

		$result= $this->_db()->select(" {$select} ")
			->join(self::TABLE_MEMBER_INFO, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
		    ->join(self::TABLE_MEMBER, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER.'.mem_id', 'inner')
			->order_by($sort)
			->where('('.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CHARGE.' OR '.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CONSUME.')')
			->limit($page_size, $offset)
			->from($table)
			->where($where)
			->get()
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

		$select = str_replace('every_bonus', self::TABLE_CONSUMPTION.'.bonus as every_bonus', $select);
		$select = str_replace('sum_bonus', self::TABLE_MEMBER.'.bonus as sum_bonus', $select);
		$select = str_replace('create_time', self::TABLE_CONSUMPTION.'.create_time', $select);

		//echo $select;die;
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->from($table)->where('('.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CHARGE.' OR '.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CONSUME.')')->where($where)->get()->num_rows();

		$result= $this->_db()->select(" {$select} ")
			->join(self::TABLE_MEMBER_INFO, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
		    ->join(self::TABLE_MEMBER, self::TABLE_CONSUMPTION.'.mem_id='.self::TABLE_MEMBER.'.mem_id', 'inner')
			->order_by($sort)
			->where('('.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CHARGE.' OR '.self::TABLE_CONSUMPTION.'.type='.Consume::TYPE_INTEGRAL_CONSUME.')')
			->limit($page_size, $offset)
			->from($table)
			->where($where)
			->get()
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
/*		 
		return array(
			'total'=>$total,
			'data'=>$result,
			'page_size'=>$page_size,
			'page_num'=>$current_page,
		);*/
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
		$result = $this->mconfig->getConfig('level',true);
	
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
	
	static public function toArray()
	{
		return array(
			'3' => "积分增加",
			'4' => "积分消费"
		);
	}
}