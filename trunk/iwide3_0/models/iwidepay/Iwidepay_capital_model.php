<?php
class Iwidepay_capital_model extends MY_Model
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
     * 获取 监管账户余额 订单表 => 分账状态:1待定、2待分,5部分分账,8部分正常退款 不限制筛选时间
     * @param $where_arr
     * @return int
     */
    public function total_amount($where_arr)
    {
        $sql = "SELECT SUM(trans_amt) AS amount FROM ".self::TAB_IWIDEPAY_ORDER." WHERE transfer_status IN(1,2,4,5,8)";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        $data = $this->db_read()->query($sql)->row_array();

        return !empty($data['amount']) ? $data['amount'] : 0;
    }


    /**
     * 获取 监管账户余额 订单表 => 5部分分账减去已分账的金额
     * @param $where_arr
     * @return int
     */
    public function total_amount_send($where_arr)
    {
        $sql = "SELECT SUM(it.amount) AS amount FROM iwide_iwidepay_transfer AS it
                LEFT JOIN iwide_iwidepay_order io ON io.order_no = it.order_no AND io.module = it.module
                WHERE io.transfer_status = 5 AND it.`status` = 2 AND send_status = 1";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND it.inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND it.hotel_id IN ({$where_arr['hotel_id']})";
        }

        $data = $this->db_read()->query($sql)->row_array();

        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 用户支付金额 订单表 => 分账状态: 非 0状态 限制筛选时间
     * @param $where_arr
     * @return int
     */
    public function pay_amount($where_arr)
    {
        $sql = "SELECT SUM(orig_amount) AS amount FROM ".self::TAB_IWIDEPAY_ORDER." WHERE transfer_status > 0";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 退款金额 退款表 => 退款状态:1成功，2失败(余额不足),3异常
     * @param $where_arr
     * @return int
     */
    public function refund_amount($where_arr)
    {
        $sql = "SELECT SUM(refund_amt) AS amount FROM ".self::TAB_IWIDEPAY_REFUND." WHERE refund_status IN(1,2,3)";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 提现金额 分账结算记录表 => 状态 1成功 ，3异常
     * @param $where_arr
     * @return int
     */
    public function withdraw_amount($where_arr)
    {
        $sql = "SELECT SUM(amount) AS amount FROM ".self::TAB_IWIDEPAY_SETTLE." WHERE status IN(1,3)";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 金房卡佣金(分账给金房卡且已转账成功的总金额)   分账转账表 => type => 'jfk', send_status = 1成功
     * @param $where_arr
     * @return int
     */
    public function commission($where_arr)
    {
        $sql = "SELECT SUM(amount) AS amount FROM ".self::TAB_IWIDEPAY_TRANSFER." WHERE `type` = 'jfk' AND send_status = 1 AND record_id > 0";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 分销佣金(分账给分销员且已转账成功的总金额)  分账结算记录表 => 状态 1成功 ，3异常
     * @param $where_arr
     * @return int
     */
    public function distribution($where_arr)
    {
        $sql = "SELECT SUM(amount) AS amount FROM ".self::TAB_IWIDEPAY_TRANSFER." WHERE `type` = 'dist' AND send_status = 1 AND record_id > 0";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 手续费(分账给分销员且已转账成功的总金额)  分账结算记录表 => 状态 1成功 ，3异常
     * @param $where_arr
     * @return int
     */
    public function cost_fee($where_arr)
    {
        $sql = "SELECT SUM(amount) AS amount FROM ".self::TAB_IWIDEPAY_TRANSFER." WHERE `type` = 'cost' AND send_status = 1 AND record_id > 0";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
    }

    /**
     * 获取 欠款金额 线下订单佣金&奖励、垫付、月费，实时不受筛选时间限制 欠款单记录表 => 结算状态: 0未结清
     * @param $where_arr
     * @return int
     */
    public function arrears_amount($where_arr)
    {
        $sql = "SELECT SUM(amount) AS amount FROM ".self::TAB_IWIDEPAY_DEBT_RD."
                WHERE `order_type` IN('order','base_pay','refund','orderReward','extra_dist') AND status = 0";
        if (!empty($where_arr['inter_id']) && $where_arr['inter_id'] != 'ALL_PRIVILEGES')
        {
            $sql .= " AND  inter_id = '{$where_arr['inter_id']}'";
        }
        if (!empty($where_arr['hotel_id']))
        {
            $sql .= " AND hotel_id IN ({$where_arr['hotel_id']})";
        }

        if (!empty($where_arr['start_time']))
        {
            $sql .= " AND add_time >= '{$where_arr['start_time']}'";
        }

        if (!empty($where_arr['end_time']))
        {
            $sql .= " AND add_time <= '{$where_arr['end_time']} 23:59:60'";
        }

        $data = $this->db_read()->query($sql)->row_array();
        return !empty($data['amount']) ? $data['amount'] : 0;
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
