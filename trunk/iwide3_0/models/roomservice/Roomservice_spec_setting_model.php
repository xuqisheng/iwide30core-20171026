<?php
class Roomservice_Spec_setting_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_SPEC_SETTING = 'roomservice_spec_setting';

	public function get_resource_name()
	{
		return 'Roomservice_Spec_setting_Model';
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
		return self::TAB_SPEC_SETTING;
	}

	public function table_primary_key()
	{
		return 'setting_id';
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


    /*获取规格信息
    *param array $filter 查询条件
     * param goods_ids 商品id数组
     * 默认 按价格排序 升序
    */
    public function get_goods_sepc_info($filter = array() ,$goods_ids = array()){
        $where = array();
        $where['inter_id'] = $filter['inter_id'];
        if(isset($filter['goods_id']) && $filter['goods_id'] > 0){
            $where['goods_id'] = intval($filter['goods_id']);
        }
        $this->_db('iwide_r1')->where($where);
        if(!empty($goods_ids)){
            $this->_db('iwide_r1')->where_in('goods_id',$goods_ids);
        }
        //默认按价格升序
        $this->_db('iwide_r1')->order_by('spec_price','asc');
        $res = $this->_db('iwide_r1')->get('iwide_roomservice_spec_setting')->result_array();
        $return = array();
        if($res){
            foreach($res as $k=>$v){
                $return[$v['goods_id']][] = $v;
            }
            return $return;
        }else{
            return false;
        }
    }

    //获取规格下一个自增id
    public function get_spec_auto_increment_id()
    {//$this->db
        //$sql = "SELECT Auto_increment FROM information_schema.`TABLES` WHERE Table_Schema='iwide30soma' AND table_name = 'iwide_roomservice_spec_setting'";
        $sql = "select max(setting_id) as id from iwide_roomservice_spec_setting";
        $result = $this->_db('iwide_r1')->query($sql)->row_array();
        // var_dump( $result );die;
        if( $result ){
            return $result['id']+1;
        }else{
            return 0;
        }
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
            foreach ($data as $key => $value)
            {
                $this->db->set($key, $value, $key == 'spec_stock' ? false : true);
            }

            $this->db->where($where);
            $this->db->update(self::TAB_SPEC_SETTING);
            return $this->db->affected_rows();
        }
    }
}
