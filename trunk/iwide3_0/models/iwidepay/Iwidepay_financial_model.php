<?php
class Iwidepay_financial_model extends MY_Model
{
    const TAB_IWIDEPAY_ORDER = 'iwide_iwidepay_order';
    const TAB_IWIDEPAY_RULE = 'iwide_iwidepay_rule';
    const TAB_IWIDEPAY_BANK = 'iwide_iwidepay_merchant_info';
    const TAB_IWIDEPAY_TRANSFER = 'iwide_iwidepay_transfer';
    const TAB_IWIDEPAY_SPLIT = 'iwide_iwidepay_split';
    const TAB_IWIDEPAY_SUM = 'iwide_iwidepay_sum_record';
    const TAB_IWIDEPAY_BILL = 'iwide_iwidepay_bill_record';
    const TAB_IWIDEPAY_SETTLE = 'iwide_iwidepay_settlement';
    const TAB_IWIDEPAY_REFUND = 'iwide_iwidepay_refund';
    const TAB_IWIDEPAY_DEBT_RD = 'iwide_iwidepay_debt_record';
    const TAB_IWIDEPAY_FINANCIAL = 'iwide_iwidepay_financial';
	public function __construct()
    {
		parent::__construct();
	}

    protected function db_read()
    {
        $db_read = $this->load->database('iwide_r1',true);
        return $db_read;
        
    }
    
    protected function db_write(){
        
        return $this->db;
    }


    /**
     * 获取财务对账表
     * @param string $select
     * @param $where_arr
     * @return
     */
    public function get_financial($select = '*',$where_arr)
    {
        $sql = "SELECT {$select} FROM ".self::TAB_IWIDEPAY_FINANCIAL." AS fc";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = fc.inter_id AND H.hotel_id = fc.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = fc.inter_id";

        $sql .= " WHERE 1";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND fc.inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND fc.hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND fc.transfer_date >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND fc.transfer_date <= '{$where_arr['end_time']}'";
        }

        $data = $this->db_read()->query($sql)->result_array();
        return $data;
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
