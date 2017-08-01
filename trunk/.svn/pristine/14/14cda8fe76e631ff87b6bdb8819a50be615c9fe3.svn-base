<?php
class Iwidepay_merchant_model extends MY_Model{
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
     * 后台获取银行账户数量
     * @param $filter
     */
    public function count_band_accounts($filter)
    {
        $sql = "SELECT count(mi.id) as num
                FROM ".self::TAB_IWIDEPAY_BANK." AS mi";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = mi.inter_id AND H.hotel_id = mi.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = mi.inter_id";
        $where = " mi.status = 1";

        if(isset($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }
        //集团
        if (!empty($filter['hotel_id']))
        {
            $where .= " and mi.hotel_id IN (0,{$filter['hotel_id']})";
        }
        if (!empty($filter['is_company']))
        {
            $where .= " and mi.is_company = {$filter['is_company']}";
        }

        if (isset($filter['wd']))
        {
            $where .= " and mi.account_aliases LIKE '%{$filter['wd']}%' OR H.name LIKE '%{$filter['wd']}%' OR P.name LIKE '%{$filter['wd']}%'";
        }

        $sql .= " WHERE {$where}";

        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : $data['num'];
    }

    /**
     * 后台获取银行账户
     * @param $filter
     */
    public function get_band_accounts($select = 'mi.*',$filter,$cur_page,$page_size)
    {
        $sql = "SELECT {$select},H.name as hotel_name,P.name
                FROM ".self::TAB_IWIDEPAY_BANK." AS mi";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = mi.inter_id AND H.hotel_id = mi.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = mi.inter_id";
        $where = "mi.status = 1";
        if(isset($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and mi.inter_id = '{$filter['inter_id']}' ";
        }
        //集团
        if (!empty($filter['hotel_id']))
        {
            $where .= " and mi.hotel_id IN (0,{$filter['hotel_id']})";
        }
        if (!empty($filter['is_company']))
        {
            $where .= " and mi.is_company = {$filter['is_company']}";
        }

        if (!empty($filter['start_time']))
        {
            $where .= " and o.add_time >= '{$filter['start_time']}'";
        }

        if (!empty($filter['end_time']))
        {
            $where .= " and o.add_time <= '{$filter['end_time']} 23:59:59'";
        }

        if (isset($filter['wd']))
        {
            $where .= " and mi.account_aliases LIKE '%{$filter['wd']}%' OR H.name LIKE '%{$filter['wd']}%' OR P.name LIKE '%{$filter['wd']}%'";
        }


        $sql .= " WHERE {$where}";
        $sql .= " ORDER BY mi.id desc";
        $sql .= $this->gen_limit($cur_page,$page_size);

        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 获取账户信息
     * @author 沙沙
     * @param string $select
     * @param array $where 条件
     * @return array $data
     * @date 2017-6-28
     */
    public function get_one($select = '*',$where = array())
    {
        $res = $this->db_read()->select($select)->where($where)->get(self::TAB_IWIDEPAY_BANK)->row_array();
        return $res;
    }

    /**
     * 更改账户状态
     */
    public function update_account($where,$data)
    {
        $this->db_write()->where($where);
        $this->db_write()->update(self::TAB_IWIDEPAY_BANK,$data);
        return $this->db_write()->affected_rows();
    }

    /**
     * 添加账户
     * @author Shacaisheng
     * @param array $data 保存数据
     * @return int $res
     * @date 2017-6-28
     */
    public function insert_account($data)
    {
        $this->db_write()->insert(self::TAB_IWIDEPAY_BANK,$data);
        return $this->db_write()->insert_id();
    }


    /**
     * 获取城市区域
     */
    public function get_city($select = '*',$where)
    {
        $res = $this->db_read()->select($select)->where($where)->get(self::TAB_IWIDE_AREAS)->result_array();
        return $res;
    }

    /**
     * 获取银行
     */
    public function get_bank($select = '*',$where = array())
    {
        $res = $this->db_read()->select($select)->where($where)->get(self::TAB_IWIDEPAY_BANKCODE)->result_array();
        return $res;
    }


    /**
     * 获取公众号账户数量
     *
     */
    public function count_inter_bank($filter)
    {
        $sql = "select count(*) as num
                from (select count(mi.id) from  ".self::TAB_IWIDEPAY_BANK." AS mi";

        $where = " mi.status = 1 AND mi.inter_id != 'jinfangka'";
        if(isset($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }

        if (!empty($filter['hotel_id']))
        {
            $where .= " and mi.hotel_id IN ({$filter['hotel_id']})";
        }

        $sql .= " WHERE {$where}";
        $sql .= " group by mi.inter_id) u";

        $data = $this->db_read()->query($sql)->row_array();
        return $data ? $data['num'] : $data['num'];
    }


    /**
     * 获取公众号账户
     *
     */
    public function get_inter_bank($select = 'mi.*',$filter,$cur_page,$page_size)
    {
        $sql = "SELECT {$select},P.name,P.split_status
                FROM ".self::TAB_IWIDEPAY_BANK." AS mi";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = mi.inter_id";
        $where = " mi.status = 1 AND mi.inter_id != 'jinfangka'";
        if(isset($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES')
        {
            $where .= " and  mi.inter_id = '{$filter['inter_id']}'";
        }

        if (!empty($filter['hotel_id']))
        {
            $where .= " and mi.hotel_id IN ({$filter['hotel_id']})";
        }

        $sql .= " WHERE {$where}";
        $sql .= " GROUP BY mi.inter_id";
        $sql .= " ORDER BY mi.id desc";
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
