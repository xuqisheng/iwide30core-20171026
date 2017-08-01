<?php
class Iwidepay_rule_model extends MY_Model{
    const TAB_IWIDEPAY_ORDER = 'iwide_iwidepay_order';
    const TAB_IWIDEPAY_RULE = 'iwide_iwidepay_rule';
    const TAB_IWIDEPAY_BANK = 'iwide_iwidepay_merchant_info';
    const TAB_IWIDEPAY_TRANSFER = 'iwide_iwidepay_transfer';
    const TAB_IWIDEPAY_SPLIT = 'iwide_iwidepay_split';
    const TAB_IWIDEPAY_SUM = 'iwide_iwidepay_sum_record';
    const TAB_IWIDE_AREAS = 'iwide_iwidepay_areas';
    const TAB_IWIDEPAY_BANKCODE = 'iwide_iwidepay_bankcode';
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
     * 统计某公众号规则数
     * @param $filter
     */
    public function count_inter_num($filter)
    {
        $sql = "SELECT count(b.rule_id) AS num FROM  ".self::TAB_IWIDEPAY_RULE." AS b ";

        $where = " b.status = 1";
        if(isset($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  b.inter_id = '{$filter['inter_id']}'";
        }

        $sql .= " WHERE {$where}";

        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : $data['num'];

    }

    /**
     * 后台获取 公众号下规则数量
     * @param $filter
     * @return int
     */
    public function count_hotel_rule($filter)
    {
        $sql = "SELECT count(mi.rule_id) as num
                FROM ".self::TAB_IWIDEPAY_RULE." AS mi";
        $where = "mi.status = 1";
        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }

        if(!empty($filter['hotel_id']))
        {
            $where .= " and  mi.hotel_id IN ({$filter['hotel_id']})";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and mi.edit_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and mi.edit_time <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " WHERE {$where}";
        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : 0;
    }

    /**
     * 后台获取 公众号下规则列表
     * @param $filter
     */
    public function get_hotel_rule($select = 'mi.*',$filter,$cur_page,$page_size)
    {
        $sql = "SELECT {$select},H.name as hotel_name
                FROM ".self::TAB_IWIDEPAY_RULE." AS mi";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = mi.inter_id AND H.hotel_id = mi.hotel_id";
        $where = "mi.status = 1";
        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }

        if(!empty($filter['hotel_id']))
        {
            $where .= " and  mi.hotel_id IN ({$filter['hotel_id']})";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and mi.edit_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and mi.edit_time <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " WHERE {$where}";
        $sql .= " ORDER BY mi.rule_id desc";
        $sql .= $this->gen_limit($cur_page,$page_size);

        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 获取规则信息
     * @author 沙沙
     * @param string $select
     * @param array $where 条件
     * @return array $data
     * @date 2017-6-28
     */
    public function get_one($select = '*',$where = array())
    {
        $res = $this->db_read()->select($select)->where($where)->get(self::TAB_IWIDEPAY_RULE)->row_array();
        return $res;
    }

    /**
     * 获取规则
     * @param string $select
     * @param array $where
     * @return
     */
    public function get_rule($select = '*',$where = array(),$order_by = '')
    {
        $this->db->select($select);
        $this->db->from(self::TAB_IWIDEPAY_RULE);
        $this->db->where($where);
        if (!empty($order_by))
        {
            $order_by = explode(' ',$order_by);
            $this->db->order_by($order_by[0],$order_by[1]);
        }
        $query = $this->db->get()->row_array();

        return $query;
    }

    /**
     * 获取规则
     * @param string $select
     * @param array $where
     * @return
     */
    public function get_rules($select = '*',$where = array(),$order_by = '')
    {
        $this->db->select($select);
        $this->db->from(self::TAB_IWIDEPAY_RULE);
        $this->db->where($where);
        if (!empty($order_by))
        {
            $order_by = explode(' ',$order_by);
            $this->db->order_by($order_by[0],$order_by[1]);
        }
        $query = $this->db->get()->result_array();

        return $query;
    }

    /**
     * 添加规则
     *
     * @param $array 公众号信息
     *        	return 受影响行数
     */
    public function add_rule($array)
    {
        $this->db_write()->insert ('iwidepay_rule', $array);
        return $this->db_write()->insert_id();
    }

    /**
     * 更新规则
     *
     * @param $array 公众号信息
     *        	return 受影响行数
     */
    public function save_rule($fliter,$array)
    {
        $this->db_write()->where ($fliter);
        $this->db_write()->update ('iwidepay_rule', $array);
        return $this->db_write()->affected_rows();
    }


    /**
     * 更新公众号
     *
     * @param $array 公众号信息
     *        	return 受影响行数
     */
    public function update_public($fliter,$array)
    {
        $this->db_write()->where ($fliter);
        $this->db_write()->update ('publics', $array);
        return $this->db_write()->affected_rows();
    }

    /**
     * 创建排序sql语句
     * @access 	public
     * @param 	array	$data 需要操作的数组
     * @return 	string
     */
    protected function gen_order_by_sql($data)
    {
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
    protected function gen_limit($page, $page_size)
    {
        $page = intval($page);
        $page_size = intval($page_size);
        return $page_size > 0 ? (' limit ' . max(0, ($page-1)*$page_size) . ', ' . max(1, $page_size)) : '';
    }
}
