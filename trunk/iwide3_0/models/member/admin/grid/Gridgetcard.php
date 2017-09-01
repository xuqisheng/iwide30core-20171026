<?php
class Gridgetcard extends MY_Model
{
	const TABLE_GETCARD       = 'member_get_card_list';
	const TABLE_MEMBER_INFO   = 'member_additional';
	const TABLE_CARD          = 'member_card_infomation';

	public function table_name()
	{
		return 'iwide_member_get_card_list';
	}

	public function table_primary_key()
	{
		return 'gc_id';
	}

	public function attribute_labels()
	{
		return array(
			'gc_id'            => '序号',
			'name'             => '会员名',
			'title'            => '卡名',
			'code'             => '卡券编码',
			'is_give_by_friend'=> '是否转赠',
			'status'           => '状态',
			'create_time'      => '日期'
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		return array('gc_id', 'name', 'title','code','is_give_by_friend','status','create_time');
	}

	public function attribute_ui()
	{
		return array(
			'gc_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '2%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'name' => array(
				'grid_ui'=> '',
				'grid_width'=> '3%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'title' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'code' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'is_give_by_friend' => array(
				'grid_ui'=> '',
				'grid_width'=> '3%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=>self::toArray()
			),
			'status' => array(
				'grid_ui'=> '',
				'grid_width'=> '3%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=>$this->getcardstatus()
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
		return array('field'=>'co_id', 'sort'=>'desc');
	}

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
		$select= count($select)==0? '*': implode(',', $select);

		$select = str_replace('inter_id', self::TABLE_GETCARD.'.inter_id', $select);
		$select = str_replace('create_time', self::TABLE_GETCARD.'.create_time', $select);
		$select = str_replace('status', self::TABLE_GETCARD.'.status', $select);

		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->get_where($table, $where)->num_rows();

		$result= $this->_db()->select(" {$select} ")
			->join(self::TABLE_MEMBER_INFO, self::TABLE_GETCARD.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
			->join(self::TABLE_CARD, self::TABLE_GETCARD.'.ci_id='.self::TABLE_CARD.'.ci_id', 'left')
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
	
	public function getcardstatus()
	{
		$this->load->model('member/getcard');
		
		return array(
			Getcard::STATUS_DID_NOT_RECEIVE     => '未领取',
			Getcard::STATUS_HAVE_RECEIVE        => '已经领取',
			Getcard::STATUS_DONATE_COMPLETION   => '转赠完毕',
			Getcard::STATUS_CANCEL_VERIFICATION => '核销',
			Getcard::STATUS_DELETE              => '用户删除',
			Getcard::STATUS_FREEZE              => '冻结',
			Getcard::STATUS_GRANT               => '未发放',
			Getcard::STATUS_WEIXIN_PACKAGE      => '微信卡包',
		);
	}
}