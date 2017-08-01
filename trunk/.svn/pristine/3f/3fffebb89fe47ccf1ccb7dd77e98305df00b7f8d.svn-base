<?php
class Roomservice_Goods_Group_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_GROUP = 'roomservice_goods_group';

	public function get_resource_name()
	{
		return 'Roomservice_Goods_Group_Model';
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
		return self::TAB_GROUP;
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
        $sql = 'select count(*) as c from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_GROUP ) .' a ' . $where;
        $row = $this->_db('iwide_r1')->query($sql)->row_array();
        //返回
        return $row['c'];
    }

    /**
     * 获取指定条件的记录列表
     * @access 	public
     * @return 	int
     */
    public function get_list(array $filter = NULL, $page = 0, $page_size = 0, array $order_by = NULL,$where = NULL){
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->gen_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //排序
        $order_by = empty($order_by) ? (' order by group_id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        //$sql  = 'select * from ' . $this->db->dbprefix ( self::TAB_GROUP ) . $where . $order_by . $limit;
        $sql = "select a.*,b.name,c.shop_name from iwide_roomservice_goods_group a left join iwide_hotels b on b.inter_id = a.inter_id and b.hotel_id = a.hotel_id left join iwide_roomservice_shop c on c.inter_id = a.inter_id and c.hotel_id = a.hotel_id and c.shop_id = a.shop_id " . $where.$order_by.$limit;
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();
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
            $arr_where[] = "a.inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id']){
            if(is_array($filter['hotel_id'])){
                $arr_where[] = "a.hotel_id in (".implode(',',$filter['hotel_id']).")";
            }else{
                $arr_where[] = "a.hotel_id in ({$filter['hotel_id']})";
            }
        }
        if(isset($filter['sale_type'])){
            $arr_where[] = "a.sale_type IN({$filter['sale_type']})";
        }
        if(isset($filter['is_delete'])){
            $arr_where[] = "a.is_delete={$filter['is_delete']}";
        }
        if(isset($filter['wd']) && $filter['wd']){
            $arr_where[] = "a.group_name like '%{$filter['wd']}%'";
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
    /** 根据条件获取分组信息
     * 根据排序字段排序
         * */
    public function get_goods_group_info($filter = array()){
        $db = $this->_db('iwide_r1');
        $db->where($filter);
        $res = $db->order_by('sort_order','desc')->get('roomservice_goods_group')->result_array();
        return $res;
    }

    /*
     * 计算分组的商品数数量
     * */
    public function get_group_count($group_ids = '',$inter_id = '',$type = ''){
        $sql = "select group_id,count(*) as c from iwide_roomservice_goods where inter_id = '{$inter_id}'";
        if(is_array($group_ids)){
            $sql .= " and group_id in (".implode(',',$group_ids) . ")";
        }else{
            $sql .= " and group_id in({$group_ids})";
        }
        if (!empty($type) && $type == 4)
        {
            $sql .= " and sale_now IN(1,2)";
        }
        $sql .= " group by group_id ";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }


}
