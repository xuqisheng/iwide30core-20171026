<?php
class Tips_Qrcodes_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_QRCODE = 'tips_qrcodes';

	public function get_resource_name()
	{
		return 'Tips_Qrcodes_Model';
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
		return self::TAB_QRCODE;
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
        $sql = 'select count(*) as c from iwide_tips_qrcodes a left join iwide_hotel_staff b on a.inter_id = b.inter_id and a.saler = b.qrcode_id ' . $where;
        $row = $this->_db('iwide_r1')->query($sql)->row_array();
        //返回
        return $row['c'];
    }

    /**
     * 获取指定条件的记录列表
     * @access 	public
     * @return 	int
     */
    public function get_list(array $filter = NULL, $page = 0, $page_size = 0, array $order_by = NULL,$where = NULL){
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->gen_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //排序
        $order_by = empty($order_by) ? (' order by id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        //$sql  = 'select * from ' . $this->db->dbprefix ( self::TAB_QRCODE ) . $where . $order_by . $limit;
        $sql = "select * from iwide_tips_qrcodes " . $where . $order_by . $limit;
        $sql = "select a.*,b.master_dept from iwide_tips_qrcodes a left join iwide_hotel_staff b on a.inter_id = b.inter_id and a.saler = b.qrcode_id " .$where.$order_by.$limit;
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();//echo $sql;die;
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
            $arr_where[] = "a.inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id']){
            $arr_where[] = "a.hotel_id = {$filter['hotel_id']}";
        }
        if(isset($filter['in_hotel_id']) && $filter['in_hotel_id']){
            if(is_array($filter['in_hotel_id'])){
                $arr_where[] = "a.hotel_id in (".implode(',',$filter['in_hotel_id']).")";
            }else{
                $arr_where[] = "a.hotel_id in ({$filter['in_hotel_id']})";
            }
        }
        if(isset($filter['wd']) && !empty($filter['wd'])){
            $arr_where[] = " (a.saler = '{$filter['wd']}' or a.saler_name like '%{$filter['wd']}%')";
        }
        if(isset($filter['dept']) && !empty($filter['dept'])){
            $arr_where[] = " b.master_dept = '{$filter['dept']}'";
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

    //获取指定的二维码信息
    public function get_qrcodes($filter = array()){
        $sql = "select * from iwide_tips_qrcodes where inter_id = '{$filter['inter_id']}'";
        if(isset($filter['in_id']) && !empty($filter['in_id'])){
            $sql .= " and id in (".implode(',',$filter['in_id']).")";
        }
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();
        //返回
        return $arr;
    }

    //获取所有生成的记录 用于批量生成检查
    public function get_all_record($inter_id){
        $sql = "select saler from iwide_tips_qrcodes where inter_id = '{$inter_id}'";
        $arr = $this->db->query($sql)->result_array();//这里用主库  防止数据不及时
        //返回
        return $arr;
    }

    //根据条件获取分销员
    public function get_salers($filter = array()){
        $sql = "select id,name,hotel_name,qrcode_id,inter_id,hotel_id from iwide_hotel_staff where inter_id = '{$filter['inter_id']}' and is_distributed=1 AND `status` =2 AND openid<>'' ";
        if(isset($filter['exist_salers']) && !empty($filter['exist_salers'])){
            $sql .= " and qrcode_id not in (" . implode(',',$filter['exist_salers']) . ") ";
        }
        $sql .= " order by qrcode_id limit 200";
        $arr = $this->_db('iwide_r1')->query($sql)->result_array();
        //返回
        return $arr;
    }

}
