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
    public function get_financial($select = 'fc.*',$where_arr)
    {
        $sql = "SELECT {$select},H.name AS hotel_name,P.name FROM ".self::TAB_IWIDEPAY_FINANCIAL." AS fc";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = fc.inter_id AND H.hotel_id = fc.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = fc.inter_id";

        $sql .= " WHERE 1";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND fc.inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND (fc.hotel_id IN ({$where_arr['hotel_id']}) OR fc.write_off_hotel_id IN ({$where_arr['hotel_id']}))";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND fc.transfer_date >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND fc.transfer_date <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 退款订单
     * @param $start_time
     * @param $end_time
     * @param string $type
     * @return
     */
    public function refund_order($start_time,$end_time,$type = '1,3')
    {
        $sql = "SELECT * FROM ".self::TAB_IWIDEPAY_REFUND." WHERE refund_amt > 0 AND refund_status IN(1,2,3) AND `type` IN(".$type.")";
        $sql .= " AND (add_time >= '{$start_time}' AND add_time <= '{$end_time}')";
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 欠款订单
     * @param $start_time
     * @param $end_time
     * @return
     */
    public function debt_order($start_time,$end_time)
    {
        $sql = "SELECT * FROM ".self::TAB_IWIDEPAY_DEBT_RD." WHERE status = 1 AND order_type IN('order','base_pay','refund','orderReward','extra_dist')";
        $sql .= " AND (up_time >= '{$start_time}' AND up_time <= '{$end_time}')";
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }


    /**
     * 分账订单
     * @param $start_time
     * @param $end_time
     */
    public function transfer_order($start_time,$end_time)
    {
        $sql = "SELECT t.type,t.module,t.amount,t.hotel_id as write_off_hotel_id,t.add_time AS transfer_date,o.order_no,
                o.pay_no,o.order_no_main,o.inter_id,o.hotel_id,o.orig_amount,o.add_time
                FROM iwide_iwidepay_transfer as t
                LEFT JOIN iwide_iwidepay_order as o ON o.order_no = t.order_no AND o.module = t.module
                WHERE t.status = 2 AND t.amount > 0
                ";
        $sql .= " AND (t.add_time >= '{$start_time}' AND t.add_time <= '{$end_time}')";
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 转账对账单 分账订单
     * @param $record_id
     * @return mixed
     */
    public function transfer_accounts_order($record_id)
    {
        $sql = "SELECT t.type,t.module,t.amount,t.hotel_id as write_off_hotel_id,t.add_time AS transfer_date,o.order_no,
                o.pay_no,o.order_no_main,o.inter_id,o.hotel_id,o.orig_amount,o.add_time,H.name as hotel_name,P.name
                FROM iwide_iwidepay_order as o
                LEFT JOIN iwide_hotels as H ON H.inter_id = o.inter_id AND H.hotel_id = o.hotel_id
                LEFT JOIN iwide_publics as P ON P.inter_id = o.inter_id
                LEFT JOIN iwide_iwidepay_transfer as t ON o.order_no = t.order_no AND o.module = t.module
                WHERE amount > 0
                AND t.order_no IN (SELECT order_no FROM iwide_iwidepay_transfer WHERE record_id = ?);
                ";
        $data = $this->db_read()->query($sql,$record_id)->result_array();
        return $data;
    }

    /**
     * 欠款订单
     * 2017-08-29
     * @param $where 条件
     * @return
     */
    public function transfer_accounts_debt_order($where)
    {
        $sql = "SELECT DR.*,H.name as hotel_name,P.name FROM ".self::TAB_IWIDEPAY_DEBT_RD." AS DR
                LEFT JOIN iwide_hotels as H ON H.inter_id = DR.inter_id AND H.hotel_id = DR.hotel_id
                LEFT JOIN iwide_publics as P ON P.inter_id = DR.inter_id
                WHERE DR.status = 1 AND DR.order_type IN('order','base_pay','refund','orderReward','extra_dist')";
        $sql .= " AND {$where}";
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }


    /**
     * 查询 汇总记录
     * @param $record_id
     */
    public function sum_settlement($record_id)
    {
        $sql = "SELECT id,type,inter_id,hotel_id FROM ".self::TAB_IWIDEPAY_SETTLE." WHERE record_id = ? ";
        $data = $this->db_read()->query($sql,$record_id)->result_array();
        return $data;
    }

    /**
     * 获取门店信息
     * @param $inter_id
     * @param $hotel_id
     * @return
     */
    public function get_hotel_info($inter_id,$hotel_id)
    {
        $sql = "SELECT `name` FROM iwide_hotels  WHERE inter_id = '{$inter_id}' AND hotel_id = '{$hotel_id}'";
        $data = $this->db_read()->query($sql)->row_array();
        return $data;
    }

    /**
     * 获取门店结余记录
     * @param $inter_id
     * @param $hotel_id
     * @param $set_id
     * @return
     */
    public function balance_order($inter_id,$hotel_id,$set_id)
    {
        $sql = "SELECT DR.*,H.name as hotel_name,P.name FROM `iwide_iwidepay_debt_record` as DR
                LEFT JOIN iwide_hotels as H ON H.inter_id = DR.inter_id AND H.hotel_id = DR.hotel_id
                LEFT JOIN iwide_publics as P ON P.inter_id = DR.inter_id
                WHERE DR.`inter_id` = '{$inter_id}' AND DR.hotel_id = '{$hotel_id}' AND DR.set_id = '{$set_id}'
                AND DR.module = 'balance' AND DR.status = 0 AND DR.amount > 0 ORDER BY DR.id DESC ";
        $data = $this->db_read()->query($sql)->row_array();
        return $data;
    }

    /**
     * 获取 当天结余记录
     * @param $start_time
     * @param $end_time
     * @return
     */
    public function balance_record($start_time,$end_time)
    {
        $sql = "SELECT * FROM iwide_iwidepay_debt_record WHERE `module` = 'balance' AND  status = 0 AND amount > 0 ";
        $sql .= " AND (add_time >= '{$start_time}' AND add_time <= '{$end_time}')";
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 财务对账单结余记录
     * @param $select
     * @param $where_arr
     * @return
     */
    public function financial_balance_order($select,$where_arr)
    {
        $sql = "SELECT {$select},H.name AS hotel_name,P.name FROM `iwide_iwidepay_debt_record` AS DR";
        $sql .= " LEFT JOIN iwide_hotels as H ON H.inter_id = DR.inter_id AND H.hotel_id = DR.hotel_id";
        $sql .= " LEFT JOIN iwide_publics as P ON P.inter_id = DR.inter_id";

        $sql .= " WHERE DR.module = 'balance' AND DR.status = 0 AND DR.amount > 0";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND DR.inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND DR.hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND DR.add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND DR.add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $sql .= " GROUP BY DR.inter_id,DR.hotel_id";
        $sql .= " ORDER BY DR.id DESC";
        $data = $this->db_read()->query($sql)->result_array();
        return $data;
    }

    /**
     * 插入订单
     * @param $data
     */
    public function insert_order($data)
    {
        $this->db_write()->insert ('iwidepay_financial', $data);
        return $this->db_write()->insert_id();
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
