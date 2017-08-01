<?php
class Ticket_cart_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_TICKET_CART = 'ticket_cart';

	public function get_resource_name()
	{
		return 'Ticket_cart_model';
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
		return self::TAB_TICKET_CART;
	}

	public function table_primary_key()
	{
		return 'cart_id';
	}


    /**
     * 添加商品
     */
    public function add_cart($goods)
    {
        $insert = array(
            'goods_id' => $goods['goods_id'],
            'inter_id' => $goods['inter_id'],
            'hotel_id' => $goods['hotel_id'],
            'shop_id'   => $goods['shop_id'],
            'spu_id'    => $goods['spu_id'],
            'openid'    => $goods['openid'],
            'buy_type'  => $goods['buy_type'],
            'goods_price'  => $goods['goods_price'],
            'goods_num'  => $goods['goods_num'],
            'spu_name'  => $goods['spu_name'],
            'book_day'  => $goods['book_day'],
            'add_time'  => date('Y-m-d H:i:s'),
            'selected'  => 1,
        );

        $this->db->insert(self::TAB_TICKET_CART,$insert);
        return $this->db->insert_id();
    }

    /**
     * 更改购物车
     */
    public function update_cart($update,$where)
    {
        $this->db->where($where);
        return $this->db->update(self::TAB_TICKET_CART,$update);
    }

    /**
     * 删除购物车
     * @param $where
     */
    public function del_cart($where)
    {
        $res['data'] = $this->db->where($where)->delete(self::TAB_TICKET_CART);
        $res['rows'] = $this->db->affected_rows();

        return $res;
    }


    /**
     * 批量删除购物车
     * @param $where
     */
    public function batch_del_cart($where)
    {
        $res['data'] = $this->db->where_in('cart_id', $where['cart_id'])->delete(self::TAB_TICKET_CART);
        $res['rows'] = $this->db->affected_rows();

        return $res;
    }


    /**
     * 查询商品是否加入购物车
     */
    public function get_cart_info($filter,$select = 'cart_id')
    {
        $db = $this->_db('iwide_r1');
        $db->select($select);
        $db->where($filter);

        $res = $db->limit(1)->get(self::TAB_TICKET_CART)->row_array();
        return $res;
    }

    public function user_cart_info($filter,$select = '*')
    {
        $db = $this->_db('iwide_r1');
        $db->select($select);
        if (!empty($filter['cart_id']))
        {
            $this->_db('iwide_r1')->where_in('cart_id',$filter['cart_id']);
            unset($filter['cart_id']);
        }

        $db->where($filter);

        $res = $db->get(self::TAB_TICKET_CART)->result_array();
        return $res;
    }


}
