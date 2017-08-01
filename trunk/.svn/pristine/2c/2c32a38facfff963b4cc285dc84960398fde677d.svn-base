<?php
/**
 * @author Shacaisheng
 * @since 2017/02/22
 * @desc 预约营业时间表
 *
 */
class Appointment_dining_room_Model extends MY_Model
{
	const TABLE_ADR = 'appointment_dining_room';
	const TABLE_ASI = 'appointment_shop_info';
	const PRIMARY_KEY = 'dining_room_id';
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
		return 'appointment_dining_room';
	}

    /**
     * 查询后台餐厅记录数
     * @author Shacaisheng
     * @param array $filter 搜索条件
     * @return array $data
     * @date 2017-3-3
     */
    public function get_count($filter = array())
    {
        $sql = "SELECT count(*) as num FROM {$this->db->dbprefix(Self::TABLE_ADR)}";

        $where = " inter_id = '{$filter['inter_id']}'";
        if (isset($filter['wd']))
        {
            $where .= " and shop_name LIKE '%{$filter['wd']}%'";
        }

        $sql .= " WHERE {$where}";
        $data = $this->read_db->query($sql)->row_array();
        return $data['num'];
    }

	/**
	 * 查询后台餐厅
	 * @author Shacaisheng
	 * @param array $filter 搜索条件
	 * @return array $data
	 * @date 2017-3-3
	 */
	public function get_list($filter = array(),$order_by = array(),$cur_page = 1,$page_size = 15)
	{
		$sql = "SELECT * FROM {$this->db->dbprefix(Self::TABLE_ADR)} AS adm";

		$where = " inter_id = '{$filter['inter_id']}'";
		if (isset($filter['wd']))
		{
			$where .= " and shop_name LIKE '%{$filter['wd']}%'";
		}

		$sql .= " WHERE {$where}";
		$sql .= $this->set_order_by($order_by);
		$sql .= $this->set_limit($cur_page,$page_size);
		$data = $this->read_db->query($sql)->result_array();
		return $data;
	}


	/**
	 * 获取餐厅信息
	 * @author Shacaisheng
	 * @param int $id 自增ID
	 * @param string $field 字段
	 * @return array $data
	 * @date 2017-3-3
	 */
	public function get_one($id,$field = '*')
	{
		$sql = 'select '.$field.' from ' . $this->db->dbprefix (self::TABLE_ADR) . ' WHERE '.self::PRIMARY_KEY.' = ?';
		$data = $this->read_db->query($sql,array($id))->row_array();
		return $data;
	}

    /**
     * 前台餐厅列表
     * @author Shacaisheng
     * @param string $field 字段
     * @param int $inter_id 公众号ID
     * @param int $cur_page 页码
     * @param int $page_size 显示数目
     * @param array $order_by 排序
     * @return array $data
     * @date 2017-3-6
     */
    public function dining_room_list($field = '*',$inter_id,$cur_page,$page_size,$order_by = array('add_time'))
    {
        $sql = "SELECT {$field} FROM " . $this->db->dbprefix (self::TABLE_ADR) . " WHERE inter_id = ? AND book_style > 0";
        $sql .= $this->set_order_by($order_by);
        $sql .= $this->set_limit($cur_page,$page_size);
        $data = $this->read_db->query($sql,array($inter_id))->result_array();
        return $data;
    }


    /**
     * @param array $filter 条件
     * @return mixed
     */
    public function dining_room_count($filter = array())
    {
        $where = ' WHERE book_style > 0';
        if (!empty($filter))
        {
            foreach ($filter AS $key => $value)
            {
                $where .= " AND {$key} = '{$value}'";
            }
        }
        $sql = 'select COUNT(1) AS num from ' . $this->db->dbprefix (self::TABLE_ADR) .$where;
        $data = $this->read_db->query($sql)->row_array();
        return $data['num'];
    }

    /**
     * 获取酒店店铺
     * @author Shacaisheng
     * @param int $inter_id 自增ID
     * @param string $field 字段
     * @return array $data
     * @date 2017-3-9
     */
    public function get_inter_shop($inter_id,$field = '*')
    {
        $sql = 'select '.$field.' from ' . $this->db->dbprefix(self::TABLE_ADR) . ' WHERE inter_id = ?';
        $data = $this->read_db->query($sql,array($inter_id))->result_array();
        return $data;
    }


	/**
	 * 添加预约店铺
	 * @author Shacaisheng
	 * @param array $data 保存数据
	 * @return int $res
	 * @date 2017-3-2
	 */
	public function insert($data)
	{
	 	$this->db->insert(self::TABLE_ADR,$data);
		return $this->db->insert_id();
	}

    /**
     * 校验店铺是否重复添加
     * @param $check_where
     * @return mixed
     */
    public function check_shop($check_where)
    {
        if (isset($check_where['shop_id']))
        {
            $where = " WHERE shop_id = ? ";
            $bind = array_values($check_where);
        }
        else
        {
            $where = " WHERE hotel_id = ? AND shop_name = ?";
            $bind = array_values($check_where);
        }

        $sql = 'select dining_room_id from ' . $this->db->dbprefix (self::TABLE_ADR) .$where;
        $data = $this->read_db->query($sql,$bind)->row_array();
        return $data;
    }

    //查对应分销员的openid
    public function get_shop_saler_info($inter_id = '',$dining_room_id = 0)
    {
        //先查出对应shop的分销员数据
        $sql = "select msgsaler from iwide_appointment_dining_room where inter_id = '{$inter_id}' and dining_room_id = {$dining_room_id}  limit 1";
        $saler = $this->_db('iwide_r1')->query($sql)->row_array();
        if(!empty($saler) && !empty($saler['msgsaler']))
        {
            $saler = $saler['msgsaler'];
            $sql = "select qrcode_id,openid from iwide_hotel_staff where inter_id = '{$inter_id}' and qrcode_id in ({$saler})";
            $res = $this->_db('iwide_r1')->query($sql)->result_array();
            return $res;
        }else{
            return '';
        }
    }

    //设置分段
	private function set_limit($page,$page_size)
	{
		return $page_size > 0 ? (' LIMIT ' . max(0, ($page-1)*$page_size) . ', ' . max(1, $page_size)) : '';
	}

    //设置排序
	private function set_order_by($data = array())
	{
		$arr_order_by = array();
        if (!empty($data))
        {
            foreach($data as $k=>$v)
            {
                //需要在字段前加表别名的，在这里写代码判断
                $arr_order_by[] = $k . ' ' . $v;
            }

        }

		return ' ORDER BY ' . (empty($arr_order_by) ? self::PRIMARY_KEY  : implode(' , ', $data) ).' DESC ';
	}

}
