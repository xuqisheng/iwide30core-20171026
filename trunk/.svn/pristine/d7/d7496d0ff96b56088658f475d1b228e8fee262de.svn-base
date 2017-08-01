<?php
class Roomservice_ticket_spu_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_TICKET_SPU = 'roomservice_ticket_spu';

	public function get_resource_name()
	{
		return 'Roomservice_ticket_spu_Model';
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
		return self::TAB_TICKET_SPU;
	}

	public function table_primary_key()
	{
		return 'spu_id';
	}

    /**
        * 获取规格信息
        * param array $filter 查询条件
        * param goods_ids 商品id数组
        * 默认 按价格排序 升序
    */
    public function get_goods_spu_info($filter = array() ,$goods_ids = array())
    {
        $where = array();
        $where['inter_id'] = $filter['inter_id'];
        if(isset($filter['goods_id']) && $filter['goods_id'] > 0)
        {
            $where['goods_id'] = intval($filter['goods_id']);
        }

        $this->_db('iwide_r1')->where($where);
        if(!empty($goods_ids))
        {
            $this->_db('iwide_r1')->where_in('goods_id',$goods_ids);
        }
        $res = $this->_db('iwide_r1')->get('iwide_roomservice_ticket_spu')->result_array();
        $return = array();
        if($res)
        {
            foreach($res as $k => $v)
            {
                $return[$v['goods_id']][] = $v;
            }
            return $return;
        }
        else
        {
            return false;
        }
    }


    /**
     * 获取规格信息
     * param array $filter 查询条件
     * param goods_ids 商品id数组
     * 默认 按价格排序 升序
     */
    public function goods_spu($filter = array())
    {
        $where = array();
        $where['inter_id'] = $filter['inter_id'];
        if(isset($filter['goods_id']) && $filter['goods_id'] > 0)
        {
            $where['goods_id'] = intval($filter['goods_id']);
        }

        $this->_db('iwide_r1')->where($where);

        $res = $this->_db('iwide_r1')->get('iwide_roomservice_ticket_spu')->result_array();

        return $res;
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
            $this->db->update(self::TAB_TICKET_SPU,$data);
            return $this->db->affected_rows();
        }
    }

    /**
     * 处理新旧规格
     */
    public function update_spu_data($post,$goods)
    {
        $data = $post['spu'];
        $prime_price = $post['prime_price'];
        $spu_ids = array();
        if (!empty($data))
        {
            foreach ($data as $key => $value)
            {
                $spu_id = explode('_',$key);
                $price = !empty($prime_price[$key]) ? $prime_price[$key] : 0;
                //更改
                if (!empty($spu_id[1]))
                {
                    $spu_ids[] = $spu_id[1];
                    $spu_name[] = trim($value);

                    $this->update_data(array('spu_name'=>trim($value),'prime_price'=>trim($price)),array('spu_id'=>$spu_id[1]));
                }
                //新增
                else
                {
                    $check_goods_spu = array(
                        'goods_id' => $goods['goods_id'],
                        'inter_id' => $goods['inter_id'],
                        'spu_name' => trim($value),
                    );

                    $spu_info = $this->check_goods_spu($check_goods_spu);
                    if (empty($spu_info))
                    {
                        if (!empty($value))
                        {
                            $spu_name[] = trim($value);
                            $goods['spu_name'] = trim($value);
                            $goods['prime_price'] = trim($price);
                            $this->db->insert(self::TAB_TICKET_SPU,$goods);
                            $spu_ids[] = $this->db->insert_id();
                        }
                    }
                }
            }
        }

        $res['spu_ids'] = $spu_ids;
        $res['spu_name'] = $spu_name;
        return $res;
    }

    /**
     * 添加新旧规格
     */
    public function add_spu_data($data,$goods)
    {
        $spu_data = $data['spu_data'];

        if (!empty($spu_data))
        {
            foreach ($spu_data as $key => $value)
            {
                $value['type'] = 'edit';
                //新增spu
                if (empty($value['spu_id']) && !empty($value['spu_name']))
                {
                    $goods['spu_name'] = !empty($value['spu_name']) ? trim($value['spu_name']) : '';
                    $goods['prime_price'] = !empty($value['prime_price']) ? trim($value['prime_price']) : 0;
                    $filter = array(
                        'inter_id' => $goods['inter_id'],
                        'goods_id' => $goods['goods_id'],
                        'spu_name' => $goods['spu_name'],
                    );

                    //是否已经添加
                    $spu_info = $this->check_goods_spu($filter);
                    if (!empty($spu_info))
                    {

                        $value['spu_id'] = $spu_info['spu_id'];
                    }
                    else
                    {
                        $value['type'] = 'add';
                        $this->db->insert(self::TAB_TICKET_SPU,$goods);
                        $value['spu_id'] = $this->db->insert_id();
                    }
                }

                $spu_data[$key] = $value;
            }
        }

        $data['spu_data'] = $spu_data;
        return $data;
    }

    /**
     * 检查spu是否已经添加
     */
    public function check_goods_spu($filter)
    {
        $this->_db('iwide_r1')->where($filter);
        return $this->_db('iwide_r1')->get(self::TAB_TICKET_SPU)->row_array();
    }

    /**
     * 删除spu
     */
    public function delete_spu($where)
    {
        $res = array(
            'data' => 0,
            'rows' => 0,
        );
        if (!empty($where))
        {
            if (!empty($where['spu_id']) && is_array($where['spu_id']))
            {
                $this->db->where_in('spu_id',$where['spu_id']);
                unset($where['spu_id']);
            }
            $res['data'] = $this->db->where($where)->delete(self::TAB_TICKET_SPU);
            $res['rows'] = $this->db->affected_rows();
        }
        return $res;
    }
}
