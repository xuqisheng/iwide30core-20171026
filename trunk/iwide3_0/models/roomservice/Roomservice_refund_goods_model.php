<?php
class Roomservice_refund_goods_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

    const TAB_REFUND_GOODS = 'roomservice_refund_goods';

	public function get_resource_name()
	{
		return 'Rroomservice_refund_goods_Model';
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
		return self::TAB_REFUND_GOODS;
	}

	public function table_primary_key()
	{
		return 'refund_goods_id';
	}


    /**
     * 添加记录
     * @author Shacaisheng
     * @param array $data 保存数据
     * @return int mixed
     * @date 2017-3-7
     */
    public function insert($data)
    {
        $this->db->insert(self::TAB_REFUND_GOODS,$data);
        return $this->db->insert_id();
    }


    /**
     * 更改数据
     * @param $data 更改的数据
     * @param $where 条件
     * @return mixed
     */
    public function update_data($data,$where)
    {
        if (!empty($data))
        {
            foreach ($data as $key => $value)
            {
                $this->db->set($key, $value, $key == 'refund_num' ? false : true);
            }

            $this->db->where($where);
            $this->db->update(self::TAB_REFUND_GOODS);
            return $this->db->affected_rows();
        }
    }

}
