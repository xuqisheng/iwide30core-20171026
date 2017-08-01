<?php
/**
 * @author Shacaisheng
 * @since 2017/02/22
 * @desc 客户预约（取号）表
 *
 */
class Appointment_order_model extends MY_Model
{
    const TABLE_AO  = 'appointment_order';
    const PRIMARY_KEY  = 'order_id';
    private $write_db;
    private $read_db;
    public function __construct()
    {
        parent::__construct ();
        $this->write_db = $this->_db('iwide_rw');
        $this->read_db  = $this->_db('iwide_r1');
    }

	public function get_resource_name()
	{
		return '预约分组';
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
		return 'appointment_order';
	}

	public function table_primary_key()
	{
		return 'book_id';
	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
		return array('field'=>'order_id', 'sort'=>'desc');
	}


    /**
     * 获取预订信息
     * @author Shacaisheng
     * @param int $id 自增ID
     * @return array $data
     * @date 2017-3-7
     */
    public function get_one($id)
    {
        $sql = 'select * from ' . $this->db->dbprefix (self::TABLE_AO) . ' WHERE '.self::PRIMARY_KEY.' = ?';
        $data = $this->read_db->query($sql,array($id))->row_array();
        return $data;
    }

    /**
     * 检查用户当前时段是否存在记录
     * @param $where array 条件
     * @return mixed
     */
    public function get_user_one($where)
    {
        $sql = "SELECT order_id
                FROM {$this->db->dbprefix (self::TABLE_AO)}
                WHERE openid = ? AND book_type = 1
                AND book_op_status = 0 AND dining_room_id = ?
                AND (book_datetime BETWEEN ? AND ?)";
        $data = $this->read_db->query($sql,$where)->row_array();
        return $data;
    }


    /**
     * 检查用户当前时段是否存在记录
     * @param $where array 条件
     * @return mixed
     */
    public function get_phone_one($where)
    {
        $sql = "SELECT order_id
                FROM {$this->db->dbprefix (self::TABLE_AO)}
                WHERE dining_room_id = ? AND book_phone = ?
                AND book_op_status = 0 AND book_type = 1
                AND (book_datetime BETWEEN ? AND ?)";
        $data = $this->read_db->query($sql,$where)->row_array();
        return $data;
    }

    /**
     * 检查用户当前桌型是否存在记录
     * @param $where array 条件
     * @return mixed
     */
    public function get_desk_one($where)
    {
        $sql = "SELECT order_id
                FROM {$this->db->dbprefix (self::TABLE_AO)}
                WHERE openid = ? AND dining_room_id = ?
                 AND book_op_status = 0 AND book_type = 2";
        $data = $this->read_db->query($sql,$where)->row_array();
        return $data;
    }

    /**
     * 检查用户当前桌型是否存在记录
     * @param $where array 条件
     * @return mixed
     */
    public function get_desk_by_phone($where)
    {
        $sql = "SELECT order_id
                FROM {$this->db->dbprefix (self::TABLE_AO)}
                WHERE dining_room_id = ? AND book_phone = ?
                 AND book_op_status = 0 AND book_type = 2";
        $data = $this->read_db->query($sql,$where)->row_array();
        return $data;
    }

    /**
     * 添加时段记录
     * @author Shacaisheng
     * @param array $data 保存数据
     * @return int mixed
     * @date 2017-3-7
     */
    public function insert($data)
    {
        $this->db->insert(self::TABLE_AO,$data);
        return $this->db->insert_id();
    }

    /**
     * 查询排队等待数
     * @author Shacaisheng
     * @param array $where 条件
     * @return array $data
     * @date 2017-3-8
     */
    public function count_wait_num($where)
    {
        $sql = "SELECT COUNT(order_id) AS num
                FROM {$this->db->dbprefix (self::TABLE_AO)}
                WHERE dining_room_id = ? AND desk_type_id = ? AND book_type = 2 AND book_op_status = 0
                  AND ((book_add_time < ? AND offer_type = 1) OR offer_type = 2)
                ";
        $data = $this->read_db->query($sql, $where)->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 查询排队等待数
     * @author Shacaisheng
     * @param array $where 条件
     * @return array $data
     * @date 2017-3-8
     */
    public function count_wait_num_vip($where)
    {
        $sql = "SELECT COUNT(order_id) AS num
                FROM {$this->db->dbprefix (self::TABLE_AO)}
                WHERE dining_room_id = ? AND desk_type_id = ? AND book_type = 2 AND book_op_status = 0
                  AND book_add_time < ? AND offer_type = 2
                ";
        $data = $this->read_db->query($sql, $where)->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 查询用户预约订单
     * @author Shacaisheng
     * @param array $bind 参数绑定条件
     * @param bool $all 是否查询全部
     * @return array
     * @date 2017-3-8
     */
    public function count_inter_orders($bind,$all = false)
    {
        $sql = "SELECT COUNT(order_id) AS num FROM {$this->db->dbprefix (self::TABLE_AO)}";
        $sql .= " WHERE book_add_type = 2 AND inter_id = ? AND openid = ? ";
        $sql .= $all === false ? " AND book_op_status = ? " : '';
        $data = $this->read_db->query($sql,$bind)->row_array();
        return $data['num'];
    }

    /**
     * 查询用户预约订单
     * @author Shacaisheng
     * @param array $bind 参数绑定条件
     * @param bool $all 是否查询全部
     * @return array
     * @date 2017-3-8
     */
    public function get_inter_orders($bind,$all = false,$cur_page,$page_size)
    {
        $sql = "SELECT * FROM {$this->db->dbprefix (self::TABLE_AO)}";
        $sql .= " WHERE book_add_type = 2 AND inter_id = ? AND openid = ?" ;
        $sql .= $all === false ? " AND book_op_status = ? " : '';
        $sql .= " ORDER BY order_id DESC";
        $sql .= $this->set_limit($cur_page,$page_size);
        $data = $this->read_db->query($sql,$bind)->result_array();
        return $data;
    }


    /**
     * 查询后台订单数
     * @author Shacaisheng
     * @param array $bind 绑定参数
     * @param array $filter 搜索条件
     * @return int $data
     * @date 2017-3-3
     */
    public function get_count($bind,$filter = array())
    {
        $sql = "SELECT count(*) as num FROM {$this->db->dbprefix(Self::TABLE_AO)}";

        $where = ' WHERE inter_id = ? AND book_type = 1 ';
        if (!empty($filter['dining_room_id']))
        {
            $where .= " AND dining_room_id = {$filter['dining_room_id']}";
        }

        if ($filter['type'] != 99)
        {
            $where .= " AND book_op_status = {$filter['type']}";
        }

        if (!empty($filter['wd']))
        {
            $where .= " AND (book_phone LIKE '%{$filter['wd']}%' OR book_name LIKE '%{$filter['wd']}%' OR book_info LIKE '%{$filter['wd']}%')";
        }

        if(!empty($filter['start_time']))
        {
            $where .= " AND book_datetime >= '{$filter['start_time']}'";
        }
        if(!empty($filter['end_time']))
        {
            $where .= " AND book_datetime <= '{$filter['end_time']}'";
        }

        $sql .= " {$where}";

        $data = $this->read_db->query($sql,$bind)->row_array();
        return $data['num'];
    }


    /**
     * 查询预约订单
     * @author Shacaisheng
     * @param array $bind 绑定参数
     * @param array $filter 搜索条件
     * @return int $data
     * @date 2017-3-3
     */
    public function get_list($bind,$filter = array(),$cur_page = 1,$page_size = 15)
    {
        $sql = "SELECT * FROM {$this->db->dbprefix(Self::TABLE_AO)}";

        $where = ' WHERE inter_id = ? AND book_type = 1 ';
        if (!empty($filter['dining_room_id']))
        {
            $where .= " AND dining_room_id = {$filter['dining_room_id']}";
        }

        if ($filter['type'] != 99)
        {
            $where .= " AND book_op_status = {$filter['type']}";
        }

        if (!empty($filter['wd']))
        {
            $where .= " AND (book_phone LIKE '%{$filter['wd']}%' OR book_name LIKE '%{$filter['wd']}%' OR book_info LIKE '%{$filter['wd']}%')";
        }

        if(!empty($filter['start_time']))
        {
            $where .= " AND book_datetime >= '{$filter['start_time']} 00:00:00'";
        }
        if(!empty($filter['end_time']))
        {
            $where .= " AND book_datetime <= '{$filter['end_time']} 23:59:59'";
        }

        $sql .= " {$where}";
        $sql .= $this->set_order_by(array('is_op asc,book_datetime desc'));
        $sql .= $this->set_limit($cur_page,$page_size);

        $data = $this->read_db->query($sql,$bind)->result_array();
        return $data;
    }

    /**
     * 查询后台取号列表
     * @param array $bind 绑定参数
     * @param string $field 字段
     * @param int $type 1=> 取号历史,0=>正常取号
     * @return mixed
     */
    public function get_offer_list($bind,$type = 0,$field = '*')
    {
        $sql = "SELECT {$field} FROM {$this->db->dbprefix (self::TABLE_AO)}";
        //历史
        if ($type == 0)
        {
            $sql .= " WHERE inter_id = ? AND dining_room_id = ? AND book_type = 2 AND book_op_status > 0 " ;
            unset($bind[2]);

        }
        else
        {
            $sql .= " WHERE  inter_id = ? AND dining_room_id = ? AND desk_type_id = ? AND book_type = 2 AND book_op_status = 0 " ;

        }
        $sql .= " ORDER BY offer_type DESC,order_id ASC";
        if ($type == 0)
        {
            $sql .= " LIMIT 250";
        }

        $data = $this->read_db->query($sql,$bind)->result_array();
        return $data;
    }

    /**
     * 查询后台历史排队号
     * @param array $bind 绑定参数
     * @return mixed
     */
    public function count_offer_num($bind)
    {
        $sql = "SELECT COUNT(order_id) AS num FROM {$this->db->dbprefix (self::TABLE_AO)}";
        $sql .= " WHERE dining_room_id = ? AND book_type = 2 AND book_op_status > 0 " ;
        $data = $this->read_db->query($sql,$bind)->row_array();
        return $data['num'];
    }

    /**
     * 查询 临近用餐时间前30min
     * @param string $field
     * @return mixed
     */
    public function notice_status_order($field = '*')
    {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT {$field} FROM {$this->db->dbprefix(self::TABLE_AO)}";
        $sql .= " WHERE book_type = 1 AND book_op_status = 0 AND book_add_type = 2 AND notice_status = 0 ";
        $sql .= " AND book_datetime BETWEEN '{$now}' AND date_add(now(),interval +30 minute)";
        $data = $this->read_db->query($sql)->result_array();
        return $data;
    }

    //设置排序
    private function set_order_by($data = array())
    {
        $arr_order_by = array();
        foreach($data as $k=>$v)
        {
            //需要在字段前加表别名的，在这里写代码判断
            $arr_order_by[] = $k . ' ' . $v;
        }
        return ' ORDER BY ' . (empty($arr_order_by) ? self::PRIMARY_KEY .' DESC '  : implode(' , ', $data) );
    }

    //设置分段
    private function set_limit($page,$page_size)
    {
        return $page_size > 0 ? (' LIMIT ' . max(0, ($page-1)*$page_size) . ', ' . max(1, $page_size)) : '';
    }
}
