<?php
class Roomservice_Action_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_ACTION = 'roomservice_action';

	public function get_resource_name()
	{
		return 'Roomservice_Action_Model';
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
		return self::TAB_ACTION;
	}

	public function table_primary_key()
	{
		return 'id';
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
        if(isset($filter['is_delete'])){
            $arr_where[] = "is_delete={$filter['is_delete']}";
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

    //获取最新的催单信息
    public function get_new_remind_action($inter_id, $condit = array(),$return_type='nums'){

        $this->_db('iwide_r1')->select('ra.*');
        $this->_db('iwide_r1')->from('roomservice_action as ra');

        if (isset($condit['type']))
        {
            $this->_db('iwide_r1')->join('roomservice_shop as rs', 'rs.shop_id = ra.shop_id', 'left');
        }

        $this->_db('iwide_r1')->where ( 'ra.inter_id', $inter_id );
        $this->_db('iwide_r1')->where ( 'ra.type', 1 );
        if(!empty($condit['hotel_ids'])){
            $this->_db('iwide_r1')->where_in ( 'ra.hotel_id', $condit['hotel_ids'] );
        }
        if(!empty($condit['shop_ids'])){
            $this->_db('iwide_r1')->where_in ( 'ra.shop_id', $condit['shop_ids'] );
        }
        //订单时间
        if(!empty($condit['check_time'])){
            $this->_db('iwide_r1')->where('ra.add_time >=',date('Y-m-d H:i:s',$condit['check_time']));
        }

        if (isset($condit['type']))
        {
            $this->_db('iwide_r1')->where_in ( 'rs.sale_type', $condit['type']);
        }
        $result=$this->_db('iwide_r1')->get();
        //print_r($result->num_rows());exit;
        if($return_type=='nums'){
            return $result->num_rows();
        }else{
            return $result->result_array();

        }
    }

    //获取订单的催单信息
    public function get_order_remind($inter_id = '',$filter = array()){
        $sql = "select * from iwide_roomservice_action where inter_id = '{$inter_id}'";
        if(isset($filter['order_ids'])){
            $sql .= " and order_id in (" . implode(',',$filter['order_ids']) . ") ";
        }
        if(isset($filter['type'])){
            $sql .= " and type = " . $filter['type'];
        }
        $sql .= " order by add_time desc";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;

    }


}
