<?php
class Roomservice_orders_item_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

    const TAB_ORDERS_ITEM = 'roomservice_orders_item';

	public function get_resource_name()
	{
		return 'Roomservice_Orders_item_Model';
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
		return self::TAB_ORDERS_ITEM;
	}

	public function table_primary_key()
	{
		return 'item_id';
	}


    //查询单条信息
    public function get_one($filter = array())
    {
        $res = array();
        if (!empty($filter))
        {
            $res = $this->_db('iwide_r1')->get_where(self::TAB_ORDERS_ITEM,$filter)->row_array();
        }
        return $res ;
    }


    /**
     * 更新退款数量
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
            $this->db->update(self::TAB_ORDERS_ITEM);
            return $this->db->affected_rows();
        }
    }


    private function write_log( $data,$re = '',$result = '',$file=NULL, $path=NULL )
    {
        if(!$file) $file= date('Y-m-d'). '.txt';
        if(!$path) $path= APPPATH. 'logs'. DS. 'roomservice'. DS;

        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }

        if(is_array($data)){
            $data=json_encode($data);
        }
        if(is_array($result)){
            $result=json_encode($result);
        }
        $fp = fopen($path.$file, "a");
        $content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['PHP_SELF']." | ".session_id()." | ".$data." | ".$re." | ".$result."\n";

        fwrite($fp, $content);
        fclose($fp);
    }


    /**
     * 批量插入订单商品
     * @param $order_goods
     * @return mixed
     */
    public function insert_batch_item($order_goods)
    {
        //插入orders_item 表
        $result = $this->db->insert_batch($this->db->dbprefix(self::TAB_ORDERS_ITEM),$order_goods);
        return $result;
    }

    /**
     * 获取订单
     */
    public function get_order_item($filter,$order_id = '',$select = '*')
    {
        $db = $this->_db('iwide_r1');

        $db->select($select);
        $db->where($filter);
        if (!empty($order_id))
        {
            $db->where_in('order_id',$order_id);
        }

        $res = $db->get(self::TAB_ORDERS_ITEM)->result_array();
        return $res;
    }

}
