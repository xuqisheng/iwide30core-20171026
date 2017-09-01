<?php
/**
 * @author Shacaisheng
 * @since 2017/02/22
 * @desc 预约时段数量表
 *
 */
class Appointment_time_sales_model extends MY_Model
{
    const TABLE_ATS      = 'appointment_time_sales';
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
		return 'appointment_time_sales';
	}


    /**
     * 查询时段订座数
     * @author Shacaisheng
     * @param int $dining_room_id 餐厅自增ID
     * @return array $data
     * @date 2017-3-3
     */
    public function get_count($dining_room_id)
    {
        $sql = 'select SUM(book_number) AS num from ' . $this->db->dbprefix (self::TABLE_ATS) . ' WHERE dining_room_id = ?';
        $data = $this->read_db->query($sql,array($dining_room_id))->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 获取时段预订数
     * @param $where
     * @return int
     */
    public function get_num_where($where)
    {
        $sql = "SELECT book_number AS num
                FROM " . $this->db->dbprefix (self::TABLE_ATS) . "
                WHERE dining_room_id = ? AND opentime_id = ? AND add_date = ?";

        $data = $this->read_db->query($sql,$where)->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 获取今日预订数
     * @param $where
     * @return int
     */
    public function get_num_today($where)
    {
        $sql = "SELECT SUM(book_number) AS num
                FROM " . $this->db->dbprefix (self::TABLE_ATS) . "
                WHERE dining_room_id = ? AND add_date = ?";
        $data = $this->read_db->query($sql,$where)->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 添加时段记录
     * @author Shacaisheng
     * @param array $data 保存数据
     * @return int $res
     * @date 2017-3-2
     */
    public function insert($data)
    {
       if (!empty($data))
       {
           $field = implode(',',array_keys($data));
           $value = implode(',',array_values($data));
       }
        $sql = "REPLACE into " . $this->db->dbprefix (self::TABLE_ATS) . " ({$field}) VALUES ({$value})";
        return $this->write_db->query($sql);
    }


    public function update_book_num($where)
    {
        $sql = "UPDATE iwide_appointment_time_sales
                SET `book_number` = `book_number` + 1
                WHERE dining_room_id = {$where['dining_room_id']} AND opentime_id = {$where['opentime_id']}
                 AND add_date = '".$where['date']."' AND book_number < {$where['stock']}";

        return $this->write_db->query($sql);
    }

    /**
     * 更改时段预订数量
     * @param $where
     * @return mixed
     */
    public function update($where)
    {
        $this->db->where($where);
        $this->db->set('book_number','book_number - 1',FALSE);
        return $this->db->update($this->db->dbprefix(self::TABLE_ATS));
    }
}
