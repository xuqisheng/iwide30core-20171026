<?php
class Iwidepay_order_model extends MY_Model{

	const TAB_IIP_O = 'iwide_iwidepay_order';
	function __construct() {
		parent::__construct ();
	}

    protected function db_read(){

        $db_read = $this->load->database('iwide_r1',true);
        return $db_read;

    }

    protected function db_write(){

        return $this->db;
    }

    protected function db_soma_read(){

        $db_soma_read = $this->load->database('iwide_soma_r',true);
        return $db_soma_read;


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
        $sql = 'select count(*) as c from ' . self::TAB_IIP_O. $where;
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
        $order_by = empty($order_by) ? (' order by id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        $sql  = 'select * from ' . self::TAB_IIP_O . $where . $order_by . $limit;
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();
        //返回
        return $arr;
    }

    /**
     * 获取 交易流水数量
     */
    public function count_orders($filter)
    {
        $sql = "SELECT count(o.id) as num
                FROM ".self::TAB_IIP_O." AS o";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = o.inter_id AND H.hotel_id = o.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = o.inter_id";

        $where = $this->set_where_sql($filter);

        $sql .= " WHERE {$where}";

        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : 0;
    }

    /**
     * 获取 交易流水记录
     */
    public function get_orders($select = 'mi.*',$filter,$cur_page,$page_size)
    {
        $sql = "SELECT {$select},H.name as hotel_name,P.name
                FROM ".self::TAB_IIP_O." AS o";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = o.inter_id AND H.hotel_id = o.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = o.inter_id";

        $where = $this->set_where_sql($filter);

        $sql .= " WHERE {$where}";
        $sql .= " ORDER BY o.id desc";
        $sql .= $this->gen_limit($cur_page,$page_size);
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }


    /**
     * 创建查询条件sql语句
     * @access 	public
     * @param 	array	$filter 需要操作的数组
     * @return 	string
     */
    public function set_where_sql($filter)
    {
        $where = ' o.transfer_status > 0';
        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  o.inter_id = '{$filter['inter_id']}'";
        }
        if (!empty($filter['hotel_id']))
        {
            $where .= " and o.hotel_id IN ({$filter['hotel_id']})";
        }
        //模块
        if (!empty($filter['module']))
        {
            $where .= " and o.module = '{$filter['module']}'";
        }
        //订单状态
        if (!empty($filter['order_status']))
        {
            //所有
            if ($filter['order_status'] == 1)
            {
                //$where .= " and o.transfer_status > 0";
            }
            //全额退款
            else if ($filter['order_status'] == 2)
            {
                $where .= " and o.transfer_status IN (6,7)";
            }
            //部分退款
            else if ($filter['order_status'] == 3)
            {
                $where .= " and o.transfer_status IN (8,9)";
            }
        }
        //分账状态
        if (!empty($filter['transfer_status']))
        {
            $where .= " and o.transfer_status = '{$filter['transfer_status']}'";
        }
        //单号
        if (!empty($filter['order_no']))
        {
            $where .= " and o.order_no LIKE '%{$filter['order_no']}%'";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and o.add_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and o.add_time <= '{$filter['end_time']} 23:59:59'";
        }

        if (!empty($filter['pay_start_time']))
        {
            $where .= " and o.pay_time >= '{$filter['pay_start_time']}'";
        }

        if (!empty($filter['pay_end_time']))
        {
            $where .= " and o.pay_time <= '{$filter['pay_end_time']} 23:59:59'";
        }
        return $where;
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
