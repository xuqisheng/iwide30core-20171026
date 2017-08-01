<?php
class Roomservice_verify_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

        const TAB_ROOMSERVICE_VERIFY = 'roomservice_verify';

	public function get_resource_name()
	{
		return 'Roomservice_verify_Model';
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
		return self::TAB_ROOMSERVICE_VERIFY;
	}


    public function get_num_where()
    {

    }

    /**
     * 更新库存
     * @param $data 更改的数据
     * @param $where 条件
     * @return mixed
     */
    public function update_data($data,$where)
    {
        if (!empty($data))
        {
            $this->db->where($where);
            $this->db->update(self::TAB_ROOMSERVICE_VERIFY,$data);
            return $this->db->affected_rows();
        }
    }

    /**
     * 插入数据
     * @param $data
     * @return mixed
     */
    public function insert_data($data)
    {
        return $this->db->insert(self::TAB_ROOMSERVICE_VERIFY,$data);
    }

    /**
     * 获取核对信息
     */
    public function get_one($filter,$order_by = false)
    {
        $this->_db('iwide_r1')->where($filter);
        if ($order_by == true)
        {
            $this->_db('iwide_r1')->order_by('verify_id','DESC');
        }

        return $this->_db('iwide_r1')->get(self::TAB_ROOMSERVICE_VERIFY)->row_array();
    }
}
