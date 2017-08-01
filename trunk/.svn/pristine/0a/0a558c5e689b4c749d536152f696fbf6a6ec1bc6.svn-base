<?php
class Roomservice_ticket_dateprice_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_TICKET_DATAPRICE = 'roomservice_ticket_dateprice';
	const TAB_GOODS = 'roomservice_goods';

	public function get_resource_name()
	{
		return 'Roomservice_ticket_dateprice_Model';
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
		return self::TAB_TICKET_DATAPRICE;
	}

	public function table_primary_key()
	{
		return 'date_id';
	}

    /**
        * 获取规格价格日历信息
        * @param array $filter 查询条件
        * @param int $type 查询方式  1-查询最近商品价格，2-查询规则最低价
        * @return array
    */
    public function get_goods_dateprice_info($filter = array(),$type = 1)
    {
        $where = array();

        if (isset($filter['spu_id']))
        {
            $where['spu_id'] = $filter['spu_id'];
        }

        if(!empty($filter['goods_id']) && is_array($filter['goods_id']))
        {
            $this->_db('iwide_r1')->where_in('goods_id',$filter['goods_id']);
        }
        else
        {
            $where['goods_id'] = intval($filter['goods_id']);
        }
        //准确获取某日期价格库存
        if (isset($filter['date']) && !empty($filter['date']))
        {
            if ($type == 1)
            {
                $where['date >='] = $filter['date'];
            }
            else
            {
                $this->_db('iwide_r1')->where($filter['date']);
            }

        }

        if (isset($filter['goods_price']))
        {
            $where['goods_price >'] = $filter['goods_price'];
        }

        $this->_db('iwide_r1')->where($where);
        if ($type == 1)
        {
            $this->_db('iwide_r1')->group_by('spu_id');
            $this->_db('iwide_r1')->order_by('date','ASC');
        }
        else if($type == 2)
        {
            //$this->_db('iwide_r1')->group_by('date');
            $this->_db('iwide_r1')->order_by('goods_price','ASC');
        }

        $res = $this->_db('iwide_r1')->get('iwide_roomservice_ticket_dateprice')->result_array();

        if($res)
        {
            $return = array();
            foreach($res as $k => $v)
            {
                if ($type == 1)
                {
                    $return[$v['goods_id']][$v['spu_id']] = $v;
                }
                else
                {
                    $return[date('Y-n-j',strtotime($v['date']))][] = $v;
                }

            }
            return $return;
        }
        else
        {
            return false;
        }
    }


    /**
     * 更新
     * @param $data 更改的数据
     * @param $where 条件
     * @return mixed
     */
    public function update_data($data,$where)
    {
        $res = array(
            'data' => 0,
            'rows' => 0,
        );
        if (!empty($data))
        {
            $this->db->where($where);
            $res['data'] = $this->db->update(self::TAB_TICKET_DATAPRICE,$data);
            $res['rows'] = $this->db->affected_rows();
        }
        return $res;
    }

    /**
     * 删除spu_id 价格日历
     */
    public function delete_dateprice($where)
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
            $res['data'] = $this->db->where($where)->delete(self::TAB_TICKET_DATAPRICE);
            $res['rows'] = $this->db->affected_rows();
        }
        return $res;
    }

    /**
     * 查询某ID信息
     * @param array $filter
     * @return mixed
     */
    public function get_one_info($filter = array())
    {
        $db = $this->_db('iwide_r1');
        $db->where($filter);
        $res = $db->limit(1)->get(self::TAB_TICKET_DATAPRICE)->row_array();
        return $res;
    }

    /**
     * 添加/编辑商品价格日历
     */
    public function ticket_dateprice($setting,$inter_id,$goods_id,$spu_ids)
    {
        if (!empty($setting))
        {
            $start_time = strtotime($setting['price_start_time']);//开始时间
            $end_time = strtotime($setting['price_end_time']);//结束时间

            //长度不对则不更改
            if (count($setting['spu_data']) != count($spu_ids))
            {
                return false;
            }

            $min_arr_add = array();
            //循环按照日期生成记录
            while($start_time <= $end_time)
            {
                //判断某天是周几
                $this_week = date('w', $end_time);

                $this_week = $this_week == 0 ? 7 : $this_week;//周日

                if (!empty($setting['week']) && in_array($this_week, $setting['week']))
                {
                    //判断添加或者编辑
                    $spu_data = $setting['spu_data'];
                    if (!empty($spu_data))
                    {
                        foreach ($spu_data as $key => $value)
                        {
                            $value['spu_data_stock'] = trim($value['spu_data_stock']);
                            $value['spu_data_price'] = trim($value['spu_data_price']);
                            //编辑
                            if (!empty($value['spu_id']) && in_array($value['spu_id'],$spu_ids,true))
                            {
                                //执行条件
                                $where = array(
                                    'goods_id'  => $goods_id,
                                    'spu_id'    => $value['spu_id'],
                                    'inter_id'  => $inter_id,
                                    'date'      => date('Y-m-d',$end_time),
                                );

                                $row = $this->get_one_info($where);
                                if (!empty($row))
                                {
                                    //更新数据

                                    $min_arr_edit = array();
                                    if ($value['spu_data_price'] > 0)
                                    {
                                        $min_arr_edit['goods_price'] = $value['spu_data_price'];
                                    }

                                    if ($value['spu_data_stock'] >= 0)
                                    {
                                        $min_arr_edit['goods_stock'] = $value['spu_data_stock'];
                                    }

                                    //存在更新数据
                                    if (!empty($min_arr_edit))
                                    {
                                        $this->update_data($min_arr_edit,array('date_id'=>$row['date_id']));
                                    }
                                }
                                else
                                {
                                    //添加
                                    $min_arr_add[] = array(
                                        'goods_price' => $value['spu_data_price'],
                                        'goods_stock' => $value['spu_data_stock'],
                                        'spu_id'    => $value['spu_id'],
                                        'goods_id'  => $goods_id,
                                        'inter_id'  => $inter_id,
                                        'date'      => date('Y-m-d',$end_time),
                                    );
                                }
                            }
                            else
                            {
                                $min_arr_add[] = array(
                                    'spu_id' => $spu_ids[$key],
                                    'goods_price' => $value['spu_data_price'],
                                    'goods_stock' => $value['spu_data_stock'],
                                    'date' => date('Y-m-d',$end_time),
                                    'inter_id' => $inter_id,
                                    'goods_id' => $goods_id,
                                );
                            }
                        }
                    }
                }

                $end_time = $end_time - 86400;
            }

            //批量插入数据
            if (!empty($min_arr_add))
            {
                $this->db->insert_batch(self::TAB_TICKET_DATAPRICE, $min_arr_add);
            }
        }
    }


    /**
     * 添加/编辑商品价格日历 某一天
     * @param $setting
     * @param $inter_id
     * @param $goods_id
     * @param $spu_ids
     * @return  void
     */
    public function ticket_dateprice_one($setting,$inter_id,$goods_id,$spu_info)
    {
        if (!empty($setting))
        {
            $min_arr_add = array();
            foreach ($setting as $k => $item)
            {
                $start_time = strtotime($k);//开始时间

                //判断添加或者编辑
                $spu_data = $item;
                //长度不对则不更改
                $spu_ids = $spu_info['spu_ids'];
                $spu_name = $spu_info['spu_name'];

                if (!empty($spu_data) && !empty($start_time))
                {
                    ///print_r($spu_data);
                    foreach ($spu_data as $key => $value)
                    {
                        if (!empty($spu_name[$key]) && $value['spu_name'] == $spu_name[$key])
                        {
                            $value['spu_data_stock'] = trim($value['spu_data_stock']);
                            $value['spu_data_price'] = trim($value['spu_data_price']);

                            //执行条件
                            $where = array(
                                'goods_id' => $goods_id,
                                'spu_id' =>  $spu_ids[$key],
                                'inter_id' => $inter_id,
                                'date' => date('Y-m-d', $start_time),
                            );

                            $row = $this->get_one_info($where);
                            if (!empty($row))
                            {
                                //更新数据
                                $min_arr_edit = array();
                                if ($value['spu_data_price'] > 0)
                                {
                                    $min_arr_edit['goods_price'] = $value['spu_data_price'];
                                }

                                if ($value['spu_data_stock'] >= 0)
                                {
                                    $min_arr_edit['goods_stock'] = $value['spu_data_stock'];
                                }

                                //存在更新数据
                                if (!empty($min_arr_edit))
                                {
                                    $this->update_data($min_arr_edit, array('date_id' => $row['date_id']));
                                }
                            }
                            else if(!empty($value['spu_name']) && in_array($value['spu_name'], $spu_name, true))
                            {
                                if (!empty($spu_name[$key]) && $value['spu_name'] == $spu_name[$key])
                                {
                                    $min_arr_add[] = array(
                                        'spu_id' => $spu_ids[$key],
                                        'goods_price' => $value['spu_data_price'],
                                        'goods_stock' => $value['spu_data_stock'],
                                        'date' => date('Y-m-d', $start_time),
                                        'inter_id' => $inter_id,
                                        'goods_id' => $goods_id,
                                    );
                                }
                            }
                        }
                    }
                }
            }

            //print_r($min_arr_add);exit;
            //批量插入数据
            if (!empty($min_arr_add))
            {
                $this->db->insert_batch(self::TAB_TICKET_DATAPRICE, $min_arr_add);
            }
        }
    }

    /**
     * 添加/编辑商品价格日历 某一天
     * @param $setting
     * @param $inter_id
     * @param $goods_id
     * @param $spu_ids
     * @return  void
     */
    public function ticket_dateprice_onebar($setting,$inter_id,$goods_id,$spu_ids)
    {
        if (!empty($setting))
        {
            $start_time = !empty($setting['setting_date']) ? strtotime($setting['setting_date']) : '';//开始时间
            $min_arr_add = array();

            //判断添加或者编辑
            $spu_data = $setting['spu_data'];
            //长度不对则不更改
            if (count($spu_data) != count($spu_ids))
            {
                return false;
            }

            if (!empty($spu_data) && !empty($start_time))
            {
                foreach ($spu_data as $key => $value)
                {
                    $value['spu_data_stock'] = trim($value['spu_data_stock']);
                    $value['spu_data_price'] = trim($value['spu_data_price']);
                    //编辑
                    if (!empty($value['spu_id']) && in_array($value['spu_id'],$spu_ids,true))
                    {
                        //执行条件
                        $where = array(
                            'goods_id'  => $goods_id,
                            'spu_id'    => $value['spu_id'],
                            'inter_id'  => $inter_id,
                            'date'      => date('Y-m-d',$start_time),
                        );

                        $row = $this->get_one_info($where);
                        if (!empty($row))
                        {
                            //更新数据
                            $min_arr_edit = array();
                            if ($value['spu_data_price'] > 0)
                            {
                                $min_arr_edit['goods_price'] = $value['spu_data_price'];
                            }

                            if ($value['spu_data_stock'] >= 0)
                            {
                                $min_arr_edit['goods_stock'] = $value['spu_data_stock'];
                            }

                            //存在更新数据
                            if (!empty($min_arr_edit))
                            {
                                $this->update_data($min_arr_edit,array('date_id'=>$row['date_id']));
                            }
                        }
                        else
                        {
                            //添加
                            $min_arr_add[] = array(
                                'goods_price' => $value['spu_data_price'],
                                'goods_stock' => $value['spu_data_stock'],
                                'spu_id'    => $value['spu_id'],
                                'goods_id'  => $goods_id,
                                'inter_id'  => $inter_id,
                                'date'      => date('Y-m-d',$start_time),
                            );
                        }
                    }
                    else
                    {
                        $min_arr_add[] = array(
                            'spu_id' => $spu_ids[$key],
                            'goods_price' => $value['spu_data_price'],
                            'goods_stock' => $value['spu_data_stock'],
                            'date' => date('Y-m-d',$start_time),
                            'inter_id' => $inter_id,
                            'goods_id' => $goods_id,
                        );
                    }
                }
            }

            //批量插入数据
            if (!empty($min_arr_add))
            {
                $this->db->insert_batch(self::TAB_TICKET_DATAPRICE, $min_arr_add);
            }
        }
    }
    /**
     * 获取某天信息
     */
    public function get_date_info($filter)
    {
        $db = $this->_db('iwide_r1');
        $db->where($filter);
        $res = $db->get(self::TAB_TICKET_DATAPRICE)->result_array();
        return $res;
    }


    /**
     * 扣减库存
     */
    public function reduce_item_stock($goods_info = array(),$date = '')
    {
        if(empty($goods_info))
        {
            return false;
        }

        $db = $this->db;

        foreach($goods_info as $k=>$v)
        {
            $date = !empty($v['book_day']) ? date('Y-m-d',strtotime($v['book_day'])) : $date;
            if($v['num'] > 0)
            {
                $v['spu_id'] = !empty($v['spu_id']) ? $v['spu_id'] : $v['spec_id'];//兼容旧版
                //扣减库存
                $sql = "update " . $db->dbprefix(self::TAB_TICKET_DATAPRICE) . " set `goods_stock` = `goods_stock` - {$v['num']}
                            where `goods_id`={$v['goods_id']} AND spu_id = {$v['spu_id']} AND date = '{$date}' AND goods_stock >= {$v['num']} LIMIT 1";
                $res = $db->query($sql);

                if($res != 1)
                {
                    return false;//更新失败
                }

                //更新销量‘
                $sql = "update " . $db->dbprefix(self::TAB_GOODS) . " set `sale_num`=`sale_num`+{$v['num']} where `goods_id`={$v['goods_id']}";
                $res = $db->query($sql);

                if($res != 1)
                {
                    return false;//更新失败
                }
            }
        }
        return true;
    }

    /**
     * 添加/编辑商品价格日历
     */
    public function ticket_dateprice_ajax($setting,$inter_id,$goods_id)
    {
        if (!empty($setting))
        {
            $start_time = strtotime($setting['price_start_time']);//开始时间
            $end_time = strtotime($setting['price_end_time']);//结束时间

            $min_arr_add = array();
            //循环按照日期生成记录
            while($start_time <= $end_time)
            {
                //判断某天是周几
                $this_week = date('w', $end_time);

                $this_week = $this_week == 0 ? 7 : $this_week;//周日

                if (!empty($setting['week']) && in_array($this_week, $setting['week']))
                {
                    //判断添加或者编辑
                    $spu_data = $setting['spu_data'];
                    if (!empty($spu_data))
                    {
                        foreach ($spu_data as $key => $value)
                        {
                            $value['spu_data_stock'] = trim($value['spu_data_stock']);
                            $value['spu_data_price'] = trim($value['spu_data_price']);
                            //编辑

                            //执行条件
                            $where = array(
                                'goods_id'  => $goods_id,
                                'spu_id'    => $value['spu_id'],
                                'inter_id'  => $inter_id,
                                'date'      => date('Y-m-d',$end_time),
                            );

                            $row = $this->get_one_info($where);
                            if (!empty($row))
                            {
                                //更新数据

                                $min_arr_edit = array();
                                if ($value['spu_data_price'] > 0)
                                {
                                    $min_arr_edit['goods_price'] = $value['spu_data_price'];
                                }

                                if ($value['spu_data_stock'] >= 0)
                                {
                                    $min_arr_edit['goods_stock'] = $value['spu_data_stock'];
                                }

                                //存在更新数据
                                if (!empty($min_arr_edit))
                                {
                                    $this->update_data($min_arr_edit,array('date_id'=>$row['date_id']));
                                }
                            }
                            else
                            {
                                //添加
                                $min_arr_add[] = array(
                                    'goods_price' => $value['spu_data_price'],
                                    'goods_stock' => $value['spu_data_stock'],
                                    'spu_id'    => $value['spu_id'],
                                    'goods_id'  => $goods_id,
                                    'inter_id'  => $inter_id,
                                    'date'      => date('Y-m-d',$end_time),
                                );
                            }
                        }
                    }
                }

                $end_time = $end_time - 86400;
            }

            //批量插入数据
            if (!empty($min_arr_add))
            {
                $this->db->insert_batch(self::TAB_TICKET_DATAPRICE, $min_arr_add);
            }
        }
    }

    /**
     * 即时添加商品价格日历 某一天
     * @param $setting
     * @param $inter_id
     * @param $goods_id
     * @return  void
     */
    public function one_ticket_dateprice_ajax($setting,$inter_id,$goods_id)
    {
        if (!empty($setting))
        {
            $start_time = !empty($setting['setting_date']) ? strtotime($setting['setting_date']) : '';//开始时间
            $min_arr_add = array();

            //判断添加或者编辑
            $spu_data = $setting['spu_data'];

            if (!empty($spu_data) && !empty($start_time))
            {
                foreach ($spu_data as $key => $value)
                {
                    $value['spu_data_stock'] = trim($value['spu_data_stock']);
                    $value['spu_data_price'] = trim($value['spu_data_price']);

                    //执行条件
                    $where = array(
                        'goods_id'  => $goods_id,
                        'spu_id'    => $value['spu_id'],
                        'inter_id'  => $inter_id,
                        'date'      => date('Y-m-d',$start_time),
                    );

                    $row = $this->get_one_info($where);
                    if (!empty($row))
                    {
                        //更新数据
                        $min_arr_edit = array();
                        if ($value['spu_data_price'] > 0)
                        {
                            $min_arr_edit['goods_price'] = $value['spu_data_price'];
                        }

                        if ($value['spu_data_stock'] >= 0)
                        {
                            $min_arr_edit['goods_stock'] = $value['spu_data_stock'];
                        }

                        //存在更新数据
                        if (!empty($min_arr_edit))
                        {
                            $this->update_data($min_arr_edit,array('date_id'=>$row['date_id']));
                        }
                    }
                    else
                    {
                        //添加
                        $min_arr_add[] = array(
                            'goods_price' => $value['spu_data_price'],
                            'goods_stock' => $value['spu_data_stock'],
                            'spu_id'    => $value['spu_id'],
                            'goods_id'  => $goods_id,
                            'inter_id'  => $inter_id,
                            'date'      => date('Y-m-d',$start_time),
                        );
                    }
                }
            }

            //批量插入数据
            if (!empty($min_arr_add))
            {
                $this->db->insert_batch(self::TAB_TICKET_DATAPRICE, $min_arr_add);
            }
        }
    }

    /**
     * 扣减库存
     */
    public function roback_goods_stock($goods_info = array(),$date = '')
    {
        if(empty($goods_info))
        {
            return false;
        }

        $db = $this->db;

        if(!empty($goods_info))
        {
            foreach($goods_info as $k=>$v)
            {
                $date = !empty($v['book_day']) ? date('Y-m-d',strtotime($v['book_day'])) : $date;
                if($v['goods_num'] > 0)
                {
                    //扣减库存
                    $sql = "update " . $db->dbprefix (self::TAB_TICKET_DATAPRICE ) . " set `goods_stock` = `goods_stock` + {$v['goods_num']}
                            where `goods_id`={$v['goods_id']} AND spu_id = {$v['spec_id']} AND date = '{$date}' LIMIT 1";
                    $res = $db->query ($sql);
                    $this->write_log($res,'余额退款失败',$sql);

                    if($res != 1)
                    {
                        return false;//更新失败
                    }

                    //更新销量‘
                    $sql = "update " . $db->dbprefix (self::TAB_GOODS ) . " set `sale_num`=`sale_num` - {$v['goods_num']} where `goods_id`={$v['goods_id']}";
                    $res = $db->query ($sql);
                    $this->write_log($res,'余额退款失败',$sql);
                    if($res != 1)
                    {
                        return false;//更新失败
                    }
                }
            }
            return true;
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
     * 获取商品N天内最低价格
     */
    public function get_low_price($filter,$date = '')
    {
        $start = !empty($date) ? $date : date('Y-m-d');
        $db = $this->_db('iwide_r1');
        $db->select('goods_price');
        $db->where($filter);
        if (!empty($start))
        {
            $end_time = date('Y-m-d',strtotime($start) + 6 * 86400);
            $date['date'] = " date between '{$start}' and '{$end_time}'";
            $this->_db('iwide_r1')->where($date['date']);
        }
        $this->_db('iwide_r1')->order_by('goods_price','ASC');
        $res = $db->limit(1)->get(self::TAB_TICKET_DATAPRICE)->row_array();
        return $res;
    }

    /**
     * 获取商品价格
     */
    public function get_goods_price($filter,$goods_ids = array())
    {
        $db = $this->_db('iwide_r1');
        $date['date'] = $filter['date'];
        unset($filter['date']);
        $db->where($filter);
        if (!empty($date['date']))
        {
            $db->where($date['date']);
        }

        if (!empty($goods_ids))
        {
            $db->where_in('goods_id',$goods_ids);
        }

        $db->group_by('goods_id,goods_price');
        $db->order_by('goods_price','asc');
        $res = $db->get(self::TAB_TICKET_DATAPRICE)->result_array();
        return $res;
    }

    /**
     * 获取商品价格日历
     * @param $filter
     * @return mixed
     */
    public function goods_date_price($filter)
    {
        $end = $filter['date'];
        unset($filter['date']);
        $start = date('Y-m-d');
        $db = $this->_db('iwide_r1');
        $db->where($filter);
        if (!empty($end))
        {
            $date['date'] = " date between '{$start}' and '{$end}'";
            $db->where($date['date']);
        }
        //$this->_db('iwide_r1')->order_by('goods_price','ASC');
        $res = $db->get(self::TAB_TICKET_DATAPRICE)->result_array();
        return $res;
    }

    /**
     * 获取日历商品信息
     * @param $filter
     * @return mixed
     */
    public function check_goods_price($filter)
    {
        $db = $this->_db('iwide_r1');
        $db->where($filter);
        $res = $db->limit(1)->get(self::TAB_TICKET_DATAPRICE)->row_array();
        return $res;
    }
}
