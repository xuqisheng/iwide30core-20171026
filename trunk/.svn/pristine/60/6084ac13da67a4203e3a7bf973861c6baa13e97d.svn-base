<?php
class Ticket_orders_merge_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_TICKET_ORDERS_MERGE = 'ticket_orders_merge';

	public function get_resource_name()
	{
		return 'ticket_orders_merge_model';
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
		return self::TAB_TICKET_ORDERS_MERGE;
	}

	public function table_primary_key()
	{
		return 'merge_orderId';
	}


    /**
     * 添加订单
     */
    public function add_order($insert)
    {
        $this->db->insert(self::TAB_TICKET_ORDERS_MERGE,$insert);
        return $this->db->insert_id();
    }

    /**
     * 更改订单信息
     */
    public function update_order($update,$where)
    {
        $this->db->where($where);
        $this->db->update(self::TAB_TICKET_ORDERS_MERGE,$update);
        return $this->db->affected_rows();
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
        $sql = 'select count(*) as c from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_TICKET_ORDERS_MERGE ) .' a ' . $where;
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
        $order_by = empty($order_by) ? (' order by merge_orderId desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        //$sql  = 'select * from ' . $this->db->dbprefix ( self::TAB_ORDERS ) . $where . $order_by . $limit;
        $sql = "select * from iwide_ticket_orders_merge" . $where.$order_by.$limit;
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();
        //返回
        return $arr;
    }


    //获取订单信息(只查订单表)
    public function get_order_info($data = array())
    {
        $where['merge_orderId'] = $data['order_id'];
        $where['inter_id'] = $data['inter_id'];
        $where['openid'] = $data['openid'];

        $res = $this->_db('iwide_r1')->where($where)->get(self::TAB_TICKET_ORDERS_MERGE)->row_array();
        return $res;
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

    /**
     * 创建查询条件sql语句
     * @access 	public
     * @param 	array	$filter 需要操作的数组
     * @return 	string
     */
    public function gen_where_sql($filter)
    {
        $arr_where = array();
        if(isset($filter['inter_id']) && $filter['inter_id']){
            $arr_where[] = "inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['shop_id']) && $filter['shop_id']>0){
            $arr_where[] = "shop_id={$filter['shop_id']}";
        }
        if(isset($filter['start_time']) && $filter['start_time']){
            $arr_where[] = "add_time >='{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && $filter['end_time']){
            $arr_where[] = "add_time <'{$filter['end_time']} 23:59:59'";
        }

        if(isset($filter['openid']) && $filter['openid']){
            $arr_where[] = "openid='{$filter['openid']}'";
        }
        if(isset($filter['type']) && $filter['type']>0){
            $arr_where[] = "type = {$filter['type']})";
        }
        if(isset($filter['wd']) && !empty($filter['wd'])){
            $arr_where[] = "(consignee like '%{$filter['wd']}%' or order_sn = '{$filter['wd']}')";
        }
        if(isset($filter['order_status']) && $filter['order_status'])
        {
            if($filter['order_status'] == 1){
                //0-未处理，1-待消费，2-已消费，3-已取消
                $arr_where[] = "pay_status = 2 and order_status = 1";
            }elseif($filter['order_status'] == 2){
                $arr_where[] = "pay_status = 2 and order_status = 2";
             }elseif($filter['order_status'] == 3){
                $arr_where[] = "order_status = 1";
            }
        }
        return empty($arr_where) ? '' : implode(' and ', $arr_where);
    }


    //取线上支付 15分钟未支付订单
    public function get_cancel_order_list()
    {
        $time = date('Y-m-d H:i:s',time() - 900);
        $sql = "select * from iwide_ticket_orders_merge where pay_status = 0 and order_status = 0 and add_time < '{$time}' limit 300";
        $query = $this->_db('iwide_r1')->query($sql);
        return $query->result_array();
    }


    //获取订单信息
    public function order_info($where = array())
    {
        if (empty($where))
        {
            return array();
        }
        $res = $this->_db('iwide_r1')->where($where)->get(self::TAB_TICKET_ORDERS_MERGE)->row_array();
        return $res;
    }
}
