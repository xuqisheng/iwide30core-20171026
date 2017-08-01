<?php
class Tips_Reward_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_REWRAD = 'tips_reward';


	public function get_resource_name()
	{
		return 'Tips_Reward_Model';
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
		return self::TAB_REWRAD;
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
        $sql = "select count(*) as c from iwide_tips_orders a left join  (select status,inter_id,grade_id from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and grade_table = 'iwide_tips_orders' and saler >0) c on c.inter_id = a.inter_id and c.grade_id = a.order_id left join iwide_tips_reward_record d on d.inter_id = a.inter_id and d.order_id = a.order_id " . $where;
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
        $order_by = empty($order_by) ? (' order by order_id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        //$sql  = 'select * from ' . $this->db->dbprefix ( self::TAB_ORDERS ) . $where . $order_by . $limit;
        $sql = "select a.*,c.status as send_status,d.reward_id,d.is_send,d.member_card_no from iwide_tips_orders a left join (select status,inter_id,grade_id from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and grade_table = 'iwide_tips_orders' and saler >0) c on c.inter_id = a.inter_id and c.grade_id = a.order_id left join iwide_tips_reward_record d on d.inter_id = a.inter_id and d.order_id = a.order_id "  . $where.$order_by.$limit;;
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
            $arr_where[] = "a.inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id']>0){
            $arr_where[] = "a.hotel_id={$filter['hotel_id']}";
        }
        if(isset($filter['start_time']) && $filter['start_time']){
            $arr_where[] = "a.pay_time >='{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && $filter['end_time']){
            $arr_where[] = "a.pay_time <'{$filter['end_time']} 23:59:59'";
        }
        if(isset($filter['send_status']) && $filter['send_status'] > 0){
            $arr_where[] = "c.status = {$filter['send_status']}";
        }
        if(isset($filter['is_send']) && $filter['is_send'] >= 0){
            $arr_where[] = "d.is_send = {$filter['is_send']}";
        }
        if(isset($filter['wd']) && !empty($filter['wd'])){
            $arr_where[] = "(a.saler_name like '%{$filter['wd']}%' or a.saler = '{$filter['wd']}')";
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
    //查询单条信息
    public function get_one($filter = array()){
        $res = $this->_db('iwide_r1')->get_where('roomservice_orders',$filter)->row_array();
        return $res ;
    }

    //获取奖励记录中没有发放的（说明还没有绑定）
    public function get_unblind_list($filed='*',$inter_id=''){//每次取100条，按照handle_time升序来取 每次处理都update一次handle_time
        $sql = "select {$filed} from iwide_tips_reward_record where  is_send = 0 and is_print = 1  order by handle_times asc limit 100";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }

    //获取奖品列表 根据时间
    public function get_prize_info($field = '*',$date){
        $sql = "select {$field} from iwide_tips_reward a join iwide_tips_reward_stock b on a.reward_id = b.reward_id where date = '{$date}' order by a.reward_id";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }

    //更新奖品库存
    public function update_prize_stock($reward_id , $date){
        $sql = "update iwide_tips_reward_stock set date_stock = date_stock - 1,use_stock = use_stock + 1 where reward_id = {$reward_id} and date = '{$date}' and date_stock >0";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    //根据奖品id查询中奖记录
    public function get_reward_record_by_reward_id($reward_id,$reward_type,$data= ''){
        $sql = "select count(*) as c from iwide_tips_reward_record where reward_id = {$reward_id} and reward_type = {$reward_type}";
        if(!empty($date) && $date='old'){
            $sql .= " and add_time >= '" . date('Y-m-01') . "'";
        }
        $res = $this->_db('iwide_r1')->query($sql)->row_array();
        return $res['c'];
    }

    //获取奖品的数据
    public function get_single_prize_info($reward_id = ''){
        $sql = "select * from iwide_tips_reward ";
        if(!empty($reward_id)){
            $sql .= " where reward_id = {$reward_id} limit 1";
            $res = $this->_db('iwide_r1')->query($sql)->row_array();
        }else{
            $res = $this->_db('iwide_r1')->query($sql)->result_array();
        }
        return $res;
    }

    //查询用户的中奖记录
    public function get_user_prize_record($inter_id,$openid,$date = ''){
        $sql = "select * from iwide_tips_reward_record where inter_id = '{$inter_id}' and openid = '{$openid}'";
        if(!empty($date) && $date='old'){
            $sql .= " and add_time >= '" . date('Y-m-01') . "'";
        }
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }

    //获取所有分销员
    public function get_all_saler($inter_id = ''){
        $sql = "select name,inter_id,qrcode_id,master_dept from iwide_hotel_staff where inter_id = '{$inter_id}' and status = 2 and openid != ''";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
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

}
