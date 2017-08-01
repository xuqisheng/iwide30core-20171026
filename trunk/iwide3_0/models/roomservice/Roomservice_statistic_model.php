<?php
class Roomservice_Statistic_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_SHOP = 'roomservice_shop';


    public function get_resource_name()
    {
        return 'Roomservice_Statistic_Model';
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
		return self::TAB_SHOP;
	}

	public function table_primary_key()
	{
		return 'shop_id';
	}

    /**
     * 获取某一页的数据，同时返回记录总数
     * @access 	public
     * @return 	int
     */
    public function get_page(array $filter,  $page, $page_size, $order_by=array()){
        $where= $this->gen_where_sql($filter);
        $count = $this->get_count($filter, $where);
        $list = $this->get_list($filter, $page, $page_size,  $order_by,$where);
        return array($count, $list);
    }

    /**
     * 获取指定条件的记录总数
     * @access 	public
     * @return 	int
     */
    public function get_count($filter = NULL, $where = NULL) {
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->gen_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //查询
        $sql = 'select count(*) as c from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_SHOP ) . $where;
        $row = $this->_db('iwide_r1')->query($sql)->row_array();
        //返回
        return $row['c'];
    }

    /**
     * 获取指定条件的记录列表
     * @access 	public
     * @return 	int
     */
    public function get_list(array $filter = NULL, $page = 0, $page_size = 0, array $order_by = NULL,$where = NULL){//var_dump($filter);die;
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->gen_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //排序
        $order_by = empty($order_by) ? (' order by shop_id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        $sql  = 'select * from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_SHOP ) . $where . $order_by . $limit;
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();
        //返回
        return $arr;
    }
    /**
     * 创建查询条件sql语句
     * @access 	public
     * @param 	array	$filter 需要操作的数组
     * @return 	string
     */
    public function gen_where_sql($filter){
        $arr_where = array();
        if(isset($filter['inter_id']) && $filter['inter_id']){
            $arr_where[] = "inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id']){
           // $arr_where[] = "hotel_id={$filter['hotel_id']}";
            if(is_array($filter['hotel_id'])){
                $arr_where[] = "hotel_id in (".implode(',',$filter['hotel_id']).")";
            }else{
                $arr_where[] = "hotel_id in ({$filter['hotel_id']})";
            }
        }
        if(isset($filter['sale_type'])){
            $arr_where[] = "sale_type IN ({$filter['sale_type']})";
        }
        if(isset($filter['is_delete'])){
            $arr_where[] = "is_delete={$filter['is_delete']}";
        }
        if(isset($filter['status'])){
            $arr_where[] = "status={$filter['status']}";
        }
        if(isset($filter['wd']) && $filter['wd']){
            $arr_where[] = "shop_name like '%{$filter['wd']}%'";
        }
        return empty($arr_where) ? '' : implode(' and ', $arr_where);
    }

    /**
     * 创建排序sql语句
     * @access 	public
     * @param 	array	$data 需要操作的数组
     * @return 	string
     */
    public function gen_order_by_sql($data){
        $arr_order_by = '';
        foreach($data as $k=>$v){
            //需要在字段前加表别名的，在这里写代码判断
            $arr_order_by[] = $k . ' ' . $v;
        }
        return empty($arr_order_by) ? '' : implode(', ', $arr_order_by);
    }

    /**
     * 取得列表限定记录数
     * @access 	public
     * @param   string		$page 当前页数
     * @param   boolean		$page_size	偏移量
     * @return  string		拼装的sql语句
     */
    public function gen_limit($page, $page_size){
        $page = intval($page);
        $page_size = intval($page_size);
        return $page_size > 0 ? (' limit ' . max(0, ($page-1)*$page_size) . ', ' . max(1, $page_size)) : '';
    }

    /*
     * 统计报表
     * */
    public function get_sum_statistic($filter = array()){//sum(if(pay_status=1&&pay_way!=3 || pay_way=3&&order_status<=20,1,0)) as all_success_orders_count,
        $sql = "select inter_id,count(distinct(openid)) as all_mem_count,count(*) as all_orders_count,sum(if(order_status=0,1,0)) as wait_accept,sum(if(order_status=5,1,0)) as wait_send,sum(if(order_status=10,1,0)) as sending,sum(if(order_status=20,1,0)) as finish,sum(if(order_status=25 ||order_status=26||order_status=27 ,1,0)) as cancel from iwide_roomservice_orders where 1=1";
        //获取房间数
        $roomsql = "select a.inter_id,count(*) as c from iwide_roomservice_qrcodes a left join iwide_roomservice_shop b on a.inter_id = b.inter_id and a.shop_id=b.shop_id  where 1=1";
        //成功人数 成功订单数
        $succ_sql = "select inter_id,count(distinct(openid)) as success_mem_count,count(*) as success_order_count,sum(if(pay_way = 3,sub_total,pay_money)) as succ_income_money from iwide_roomservice_orders where (pay_way=3 && order_status <=20 || pay_way!=3 && order_status <=20 && pay_status = 1) ";
        //复购率 同一用户交易两次以上成功订单的人数
        $fu_sql = "select inter_id,count(*) as fu_succes_mem_count from (select inter_id,count(*) cc from iwide_roomservice_orders where (pay_way=3 && order_status <=20 || pay_way!=3 && order_status <=20 && pay_status = 1) ";
        if(isset($filter['inter_id']) && $filter['inter_id'] !='ALL_PRIVILEGES'){
            $sql .= " and inter_id = '{$filter['inter_id']}'";
            $roomsql .= " and a.inter_id = '{$filter['inter_id']}'";
            $succ_sql .= " and inter_id = '{$filter['inter_id']}'";
            $fu_sql .= " and inter_id = '{$filter['inter_id']}'";
        }else{

        }
        if(isset($filter['start_time']) && !empty($filter['start_time'])){
            $sql .= " and add_time >= '{$filter['start_time']}'";
           // $roomsql .= " and a.add_time = '{$filter['inter_id']}'";
            $succ_sql .= " and add_time >= '{$filter['start_time']}'";
            $fu_sql .= " and add_time >= '{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && !empty($filter['end_time'])){
            $sql .= " and add_time <= '{$filter['end_time']} 23:59:59'";
            // $roomsql .= " and a.add_time = '{$filter['inter_id']}'";
            $succ_sql .= " and add_time <= '{$filter['end_time']} 23:59:59'";
            $fu_sql .= " and add_time <= '{$filter['end_time']} 23:59:59'";
        }
        if(isset($filter['type']) && !empty($filter['type'])){
            $sql .= " and type = {$filter['type']}";
            $roomsql .= " and b.sale_type = {$filter['type']}";
            $succ_sql .= " and type = {$filter['type']}";
            $fu_sql .= " and type = {$filter['type']}";
        }
        $sql .= " group by inter_id";
        $roomsql .= " group by inter_id";
        $succ_sql .= " group by inter_id";
        $fu_sql .= " group by openid   having cc>1) a group by inter_id";
//var_dump($sql);var_dump($roomsql);var_dump($succ_sql);var_dump($fu_sql);die;
        $query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
        $roomnum = $this->_db ( 'iwide_r1' )->query($roomsql)->result_array();//获取二维码数量
        $succ_num = $this->_db ( 'iwide_r1' )->query($succ_sql)->result_array();//获取成功订单和成功订单人数
        $fu_num = $this->_db ( 'iwide_r1' )->query($fu_sql)->result_array();//同一用户交易两次以上成功订单的人数
        $room_arr = $succ_arr = $fu_arr = array();
        if(!empty($roomnum)){
            foreach($roomnum as $k=>$v){
                $room_arr[$v['inter_id']] = $v['c'];
            }
            unset($roomnum);
        }
        if(!empty($succ_num)){
            foreach($succ_num as $sk=>$sv){
                $succ_arr[$sv['inter_id']] = $sv;
            }
            unset($succ_num);
        }
        if(!empty($fu_num)){
            foreach($fu_num as $fk=>$fv){
                $fu_arr[$fv['inter_id']] = $fv['fu_succes_mem_count'];
            }
            unset($fu_num);
        }
        if(!empty($query)){
            foreach($query as $qk=>$qv){
                $query[$qk]['room_num'] = isset($room_arr[$qv['inter_id']])?$room_arr[$qv['inter_id']]:0;
                $query[$qk]['success_mem_count'] = isset($succ_arr[$qv['inter_id']]['success_mem_count'])?$succ_arr[$qv['inter_id']]['success_mem_count']:0;
                $query[$qk]['success_order_count'] = isset($succ_arr[$qv['inter_id']]['success_order_count'])?$succ_arr[$qv['inter_id']]['success_order_count']:0;
                $query[$qk]['income_money'] = isset($succ_arr[$qv['inter_id']]['succ_income_money'])?$succ_arr[$qv['inter_id']]['succ_income_money']:0;
                //计算交易成功率
                $query[$qk]['success_order_rate'] = empty($query[$qk]['all_orders_count'])?0: (number_format($query[$qk]['success_order_count']/$query[$qk]['all_orders_count'],4,'.','')*100 . '%');
                //计算交易成功复购率
                $query[$qk]['fu_success_order_rate'] = empty($query[$qk]['success_mem_count'])||!isset($fu_arr[$qv['inter_id']])?0: (number_format($fu_arr[$qv['inter_id']]/$query[$qk]['success_mem_count'],4,'.','')*100 . '%');
            }
        }
        return $query;
    }


}
