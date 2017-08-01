<?php
class Ticket_goods_log_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_TICKET_GOODS_LOG = 'ticket_goods_log';

	public function get_resource_name()
	{
		return 'Ticket_goods_log_model';
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
		return self::TAB_TICKET_GOODS_LOG;
	}

	public function table_primary_key()
	{
		return 'id';
	}


    /**
     * 添加日志
     */
    public function add_log($goods,$user,$data,$type = 1)
    {
        $insert = array(
            'goods_id' => $goods['goods_id'],
            'inter_id' => $goods['inter_id'],
            'hotel_id' => $goods['hotel_id'],
            'shop_id'   => $goods['shop_id'],
            'admin_id'  => $user['admin_id'],
            'admin_name' => $user['username'],
            'op_type'   => $type,
            'op_time'   => date('Y-m-d H:i:s'),
            'log_data'  => $data,
        );

        $this->db->insert(self::TAB_TICKET_GOODS_LOG,$insert);
    }


}
