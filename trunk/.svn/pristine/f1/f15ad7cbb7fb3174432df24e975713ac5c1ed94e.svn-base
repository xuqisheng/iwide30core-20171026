<?php
class Tips_Statistics_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_STATISTICS = 'tips_statistics';

	public function get_resource_name()
	{
		return 'Tips_Statistics_Model';
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
		return self::TAB_STATISTICS;
	}

	public function table_primary_key()
	{
		return 'id';
	}

    /**
     * 获取某一页的数据，同时返回记录总数
     * @access 	public
     * @return 	int
     */
    public function get_page(array $filter,  $page, $page_size, $order_by=array()){
        $where= $this->gen_where_sql($filter);
        $count = $this->get_count($filter, $where);
        $list = $this->get_list($filter, $page, $page_size,  $order_by,$where);
        return array($count, $list);
    }

    /**
     * 获取指定条件的记录总数
     * @access 	public
     * @return 	int
     */
    public function get_count($filter = NULL, $where = NULL) {
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->gen_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //查询
        $sql = 'select count(*) as c from (select count(*) as cc from iwide_tips_orders '. $where ." group by saler) as ss";
        $row = $this->_db('iwide_r1')->query($sql)->row_array();
        //返回
        return $row['c'];
    }

    /**
     * 获取指定条件的记录列表
     * @access 	public
     * @return 	int
     */
    public function get_list(array $filter = NULL, $page = 0, $page_size = 0, array $order_by = NULL,$where = NULL){//var_dump($filter);die;
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->gen_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //排序
        $order_by = empty($order_by) ? (' order by shop_id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
       // $sql  = 'select * from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_SHOP ) . $where . $order_by . $limit;
        $sql = "SELECT a.*,@rank:=@rank+1 rank FROM (SELECT @rank:=0,tos.saler,tos.saler_name,sum(tos.score) sum_score,count(tos.order_id) as sum_count,tos.hotel_id,
				SUM(IFNULL(g.grade_total,0)) 'GRADE_TOTAL',
				SUM(IF(g.`status`!=1,g.grade_total,0)) 'DELIVER',
				SUM(IF(g.`status`=1,g.grade_total,0)) 'UNDELIVER' FROM iwide_tips_orders tos LEFT JOIN iwide_distribute_grade_all g
				ON tos.inter_id=g.inter_id AND tos.saler=g.saler AND tos.order_id = g.grade_id
				WHERE tos.inter_id=? AND g.grade_table = 'iwide_tips_orders'  AND (g.status=1 OR g.status=2 OR g.status=9)";
        $params [] = $filter['inter_id'];
        if(!empty($filter['start_time'])){
            $sql .= " AND tos.pay_time>=? ";
            $params[] = $filter['start_time'];
        }
        if(!empty($filter['end_time'])){
            $sql .= " AND tos.pay_time>=? ";
            $params[] = ($filter['end_time'] . ' 23:59:59');
        }
        if(!empty($filter['hotel_id'])){
            $sql .= " AND tos.hotel_id=? ";
            $params[] = $filter['hotel_id'];
        }
        if(!empty($filter['wd'])){
            $sql .= " AND (tos.saler = ? or tos.saler_name like ?)";
            $params[] =  $filter['wd'];
            $params[] = '%' . $filter['wd'] . '%';
        }
        $sql .= "  GROUP BY tos.saler ORDER BY GRADE_TOTAL DESC) a  ORDER BY rank";
        if(!empty($page)){
            $sql .= $limit;
        }

        $arr = $this->_db('iwide_r1')->query($sql,$params)->result_array();//echo $this->_db('iwide_r1')->last_query();die;
        //返回
        return $arr;
    }
    /**
     * 创建查询条件sql语句
     * @access 	public
     * @param 	array	$filter 需要操作的数组
     * @return 	string
     */
    public function gen_where_sql($filter){
        $arr_where = array();
        if(isset($filter['inter_id']) && $filter['inter_id']){
            $arr_where[] = "inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id']){
           // $arr_where[] = "hotel_id={$filter['hotel_id']}";
            if(is_array($filter['hotel_id'])){
                $arr_where[] = "hotel_id in (".implode(',',$filter['hotel_id']).")";
            }else{
                $arr_where[] = "hotel_id in ({$filter['hotel_id']})";
            }
        }
        if(isset($filter['start_time']) && $filter['start_time']){
            $arr_where[] = "pay_time >='{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && $filter['end_time']){
            $arr_where[] = "pay_time <='{$filter['end_time']} 23:59:59' ";
        }
        if(isset($filter['wd'])){
            $arr_where[] = " (saler = '{$filter['wd']}' or saler_name like '%{$filter['wd']}%')";
        }
        return empty($arr_where) ? '' : implode(' and ', $arr_where);
    }

    /**
     * 创建排序sql语句
     * @access 	public
     * @param 	array	$data 需要操作的数组
     * @return 	string
     */
    public function gen_order_by_sql($data){
        $arr_order_by = '';
        foreach($data as $k=>$v){
            //需要在字段前加表别名的，在这里写代码判断
            $arr_order_by[] = $k . ' ' . $v;
        }
        return empty($arr_order_by) ? '' : implode(', ', $arr_order_by);
    }

    /**
     * 取得列表限定记录数
     * @access 	public
     * @param   string		$page 当前页数
     * @param   boolean		$page_size	偏移量
     * @return  string		拼装的sql语句
     */
    public function gen_limit($page, $page_size){
        $page = intval($page);
        $page_size = intval($page_size);
        return $page_size > 0 ? (' limit ' . max(0, ($page-1)*$page_size) . ', ' . max(1, $page_size)) : '';
    }
}
