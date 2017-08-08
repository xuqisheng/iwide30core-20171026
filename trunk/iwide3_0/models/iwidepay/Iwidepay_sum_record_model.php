<?php
class Iwidepay_sum_record_model extends MY_Model{
    const TAB_IWIDEPAY_ORDER = 'iwide_iwidepay_order';
    const TAB_IWIDEPAY_RULE = 'iwide_iwidepay_rule';
    const TAB_IWIDEPAY_BANK = 'iwide_iwidepay_merchant_info';
    const TAB_IWIDEPAY_TRANSFER = 'iwide_iwidepay_transfer';
    const TAB_IWIDEPAY_SPLIT = 'iwide_iwidepay_split';
    const TAB_IWIDEPAY_SUM = 'iwide_iwidepay_sum_record';
    const TAB_IWIDE_AREAS = 'iwide_iwidepay_areas';
    const TAB_IWIDEPAY_BANKCODE = 'iwide_iwidepay_bankcode';
    const TAB_IWIDEPAY_SETTLE = 'iwide_iwidepay_settlement';
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
     *结算记录数量
     * @param $filter
     */
    public function count_sum_record($filter)
    {
        $sql = "SELECT count(sr.id) as num
                FROM ".self::TAB_IWIDEPAY_SUM." AS sr";
        $sql .= " LEFT JOIN ".self::TAB_IWIDEPAY_BANK." as mi ON sr.m_id = mi.id";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = mi.inter_id AND H.hotel_id = mi.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = mi.inter_id";
        $where = "sr.amount > 0";
        if (empty($filter['status']))
        {
            $where .= " and sr.status >= 0";
        }
        else
        {
            $where .= " and sr.status = {$filter['status']}";
        }
        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }
        if (!empty($filter['hotel_id']))
        {
            $where .= " and mi.hotel_id IN ({$filter['hotel_id']})";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and sr.add_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and sr.add_time <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " WHERE {$where}";

        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : 0;
    }

    /**
     * 结算记录
     * @param $filter
     */
    public function get_sum_record($select = 'mi.*',$filter,$cur_page,$page_size)
    {
        $sql = "SELECT {$select},H.name as hotel_name,P.name,mi.type,mi.inter_id,mi.hotel_id
                FROM ".self::TAB_IWIDEPAY_SUM." AS sr";
        $sql .= " LEFT JOIN ".self::TAB_IWIDEPAY_BANK." as mi ON sr.m_id = mi.id";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = mi.inter_id AND H.hotel_id = mi.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = mi.inter_id";
        $where = "sr.amount > 0";
        if (empty($filter['status']))
        {
            $where .= " and sr.status >= 0";
        }
        else
        {
            $where .= " and sr.status = {$filter['status']}";
        }

        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }

        if (!empty($filter['hotel_id']))
        {
            $where .= " and mi.hotel_id IN ({$filter['hotel_id']})";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and sr.add_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and sr.add_time <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " WHERE {$where}";
        $sql .= " ORDER BY sr.id desc";
        $sql .= $this->gen_limit($cur_page,$page_size);

        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }


    /**
     * 新结算记录数量
     * @param $filter
     * @return int
     */
    public function count_settlement($filter)
    {
        $sql = "SELECT count(sr.id) as num
                FROM ".self::TAB_IWIDEPAY_SETTLE." AS sr";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = sr.inter_id AND H.hotel_id = sr.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = sr.inter_id";
        $where = "sr.amount > 0";
        if (empty($filter['status']))
        {
            $where .= " and sr.status >= 0";
        }
        else
        {
            $where .= " and sr.status = {$filter['status']}";
        }
        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  sr.inter_id = '{$filter['inter_id']}'";
        }
        if (!empty($filter['hotel_id']))
        {
            $where .= " and sr.hotel_id IN ({$filter['hotel_id']})";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and sr.add_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and sr.add_time <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " WHERE {$where}";

        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : 0;
    }

    /**
     * 新结算记录
     * @param string $select
     * @param $filter
     * @param $cur_page
     * @param $page_size
     * @return
     */
    public function get_settlement($select = 'mi.*',$filter,$cur_page,$page_size)
    {
        $sql = "SELECT {$select},H.name as hotel_name,P.name
                FROM ".self::TAB_IWIDEPAY_SETTLE." AS sr";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = sr.inter_id AND H.hotel_id = sr.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = sr.inter_id";
        $where = "sr.amount > 0";
        if (empty($filter['status']))
        {
            $where .= " and sr.status >= 0";
        }
        else
        {
            $where .= " and sr.status = {$filter['status']}";
        }

        if(!empty($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  sr.inter_id = '{$filter['inter_id']}'";
        }

        if (!empty($filter['hotel_id']))
        {
            $where .= " and sr.hotel_id IN ({$filter['hotel_id']})";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and sr.add_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and sr.add_time <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " WHERE {$where}";
        $sql .= " ORDER BY sr.id desc";
        $sql .= $this->gen_limit($cur_page,$page_size);

        $data = $this->db_read()->query($sql)->result_array();
        return $data;
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
