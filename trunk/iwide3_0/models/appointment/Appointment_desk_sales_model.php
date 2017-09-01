<?php
/**
 * @author Shacaisheng
 * @since 2017/02/22
 * @desc 餐厅桌型排队数表
 *
 */
class Appointment_desk_sales_model extends MY_Model
{
    const TABLE_ADS      = 'appointment_desk_sales';
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
		return 'appointment_desk_sales';
	}

    /**
     * 查询桌型排队数
     * @author Shacaisheng
     * @param int $dining_room_id 餐厅自增ID
     * @return array $data
     * @date 2017-3-3
     */
    public function get_count($dining_room_id)
    {
        $sql = 'select SUM(book_number) as num from ' . $this->db->dbprefix (self::TABLE_ADS) . ' WHERE dining_room_id = ?';
        $data =  $this->read_db->query($sql,array($dining_room_id))->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 根据条件查询数量
     * @param $where
     * @return int
     */
    public function get_num_where($where)
    {
        $sql = "SELECT book_number AS num
                FROM " . $this->db->dbprefix (self::TABLE_ADS) . "
                WHERE dining_room_id = ? AND desk_type_id = ?";

        $data =  $this->read_db->query($sql,$where)->row_array();
        return $data['num'] ? $data['num'] : 0;
    }

    /**
     * 更新排队数
     *
     */
    public function update_num($data)
    {
        $insert = implode(',',$data);
        $sql = "INSERT INTO iwide_appointment_desk_sales (dining_room_id,desk_type_id,book_number) VALUES ({$insert})
                ON DUPLICATE KEY UPDATE `book_number` = `book_number` + 1";

        return  $this->write_db->query($sql);
    }


    /**
     * 更改桌型预订数量
     * @param $where
     * @return mixed
     */
    public function update($where)
    {
        $this->db->where($where);
        $this->db->set('book_number','book_number - 1',FALSE);
        return $this->db->update($this->db->dbprefix(self::TABLE_ADS));
    }
}
