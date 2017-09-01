<?php
/**
 * @author Shacaisheng
 * @since 2017/02/22
 * @desc 餐厅营业时间表
 *
 */
class Appointment_opentime_model extends MY_Model
{
	const TABLE_AO      = 'appointment_opentime';
	const PRIMARY_KEY   = 'opentime_id';
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
		return 'appointment_opentime';
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
		$this->db->insert(self::TABLE_AO,$data);
		return $this->db->insert_id();
	}

    /**
     * 获取营业时段信息
     * @author Shacaisheng
     * @param int $id 自增ID
     * @return array $data
     * @date 2017-3-3
     */
    public function get_one($id)
    {
        $sql = 'select * from ' . $this->db->dbprefix (self::TABLE_AO) . ' WHERE '.self::PRIMARY_KEY.' = ?';
        $data = $this->read_db->query($sql,array($id))->row_array();
        return $data;
    }

    /**
     * 获取餐厅桌型信息
     * @author Shacaisheng
     * @param int $dining_room_id 餐厅自增ID
     * @return array $data
     * @date 2017-3-3
     */
    public function getby_dining_room_id($dining_room_id)
    {
        $sql = 'select * from ' . $this->db->dbprefix (self::TABLE_AO) . ' WHERE dining_room_id = ?';
        $data = $this->read_db->query($sql,array($dining_room_id))->result_array();
        return $data;
    }

    /**
     * 根据时间范围查询餐厅时段信息
     * @param $where array 条件
     * @return mixed
     */
    public function getby_time_range($where)
    {
        $sql = "SELECT * FROM " . $this->db->dbprefix (self::TABLE_AO) . " WHERE dining_room_id = ? AND ? BETWEEN start_time AND end_time";
        $data = $this->read_db->query($sql,$where)->row_array();
        return $data;
    }
}
