<?php
class Tips_Orders_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_ORDERS = 'tips_orders';

    //支付状态 默认为0
    const IS_PAYMENT_YES = 2;   //已支付
    const IS_PAYMENT_NOT = 1;   //未支付


    public $ps_array = array(
        1   =>  '未支付',
        2   =>  '已支付',
    );
    public $pay_way_array = array(//支付方式
        1   =>  '微信',
        2   =>  '储值',
        3   =>  '线下',
        4   =>  '威富通',
    );

	public function get_resource_name()
	{
		return 'Tips_Orders_Model';
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
		return self::TAB_ORDERS;
	}

	public function table_primary_key()
	{
		return 'order_id';
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
        $sql = 'select count(*) as c from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_ORDERS ) ." a left join  (select status,inter_id,grade_id from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and grade_table = 'iwide_tips_orders' and saler >0) c on c.inter_id = a.inter_id and c.grade_id = a.order_id " . $where;
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
        $sql = "select a.*,c.status as send_status from iwide_tips_orders a left join (select status,inter_id,grade_id from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and grade_table = 'iwide_tips_orders' and saler >0) c on c.inter_id = a.inter_id and c.grade_id = a.order_id  "  . $where.$order_by.$limit;;
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
    //统计单个分销员的打赏记录
    public function get_saler_tips_record($inter_id = '' , $saler = 0){
        $sql = "select count(*) as c,sum(score) sum_score from iwide_tips_orders where inter_id = '{$inter_id}' and saler = {$saler} and pay_status = 2";
        $res = $this->_db('iwide_r1')->query($sql)->row_array();
        return $res;
    }

    //查询订单号是否存在
    public function check_order_sn($order_sn){
        $sql = "select count(1) as c from iwide_tips_orders where order_sn = '{$order_sn}'";
        $query = $this->_db('iwide_r1')->query($sql)->row_array();
        return $query['c']>0?0:true;
    }


    //支付回调操作
    public function pay_return($data){
        $this->_db('iwide_r1')->where ( array (
            'order_id' => $data['order_id'],
            'inter_id'=>$data['inter_id'],
        ) );
        $order = $this->_db('iwide_r1')->get ( 'tips_orders' )->row_array ();
        if($order){
            if($order['pay_status'] == 2){//支付成功
                $payresult = $this->_db('iwide_r1')->get_where ( 'pay_log', array (
                    'out_trade_no' => $order['order_sn']
                ) )->row_array ();
                if (! $payresult) {
                    $this->db->where ( array (
                        'order_id' => $order ['order_id'],
                        'inter_id' => $order ['inter_id']
                    ) );
                    $this->db->update ( 'tips_orders', array (
                        'operate_reason' => '没有支付结果'
                    ) );
                }else{
                    $is_notify = 0;//通知第三方，目前速8 用
                    //生成绩效
                    //记录order
                    MYLOG::w('打赏推送绩效数组：'.json_encode($order), 'tips');
                    $this->create_tips_grade($order);
                    //查询分销员信息，发送模板消息
                    $this->load->model('distribute/staff_model');
                    $saler = $this->staff_model->get_my_base_info_saler($order['inter_id'],$order['saler']);

                    //如果是速8的，推送su8
                    if($order['inter_id'] == 'a455510007'||$order['inter_id']=='a429262687'){
                        $notify =array();
                        $notify['OpenID'] = $order['pay_openid'];
                        $notify['HotelID'] = $order['hotel_id'];
                        $notify['RewardName'] = $order['saler_name'];//分销员名字
                        $notify['RewardPosition'] = $saler['master_dept'];
                        $notify['RewardAmount'] = $order['pay_money'];
                        $notify['DistributorID'] = $order['saler'];
                        $notify['Score'] = $order['score'];
                        $notify_res = $this->notify_suba($notify);
                        if(!empty($notify_res) && isset($notify_res['SetRewardInfoResult']) && $notify_res['SetRewardInfoResult']['Content']){
                            //成功 更新字段
                            $is_notify = 1;//成功
                        }else{
                            $is_notify = 2;//失败
                        }
                    }
                    $this->db->where ( array (
                        'order_id' => $order ['order_id'],
                        'inter_id' => $order ['inter_id']
                    ) );
                    $this->db->update ('tips_orders', array (
                        'operate_reason' => '支付完成',
                        'is_notify' => $is_notify,
                    ) );
                    if(!empty($saler) && !empty($saler['openid'])){
                        $this->load->model ( 'plugins/Template_msg_model' );
                        $order['openid'] = $saler['openid'];
                        $res = $this->Template_msg_model->send_tips_success_msg ( $order, 'tips_orders_pay_success' );
                    }
                }
            }
        }
    }

    //生成绩效
    private function create_tips_grade($arr){
        $this->load->model('distribute/Grades_model');
        $data['inter_id'] = $arr['inter_id'];
        $data['grade_openid'] = $arr['pay_openid'];
        $data['grade_id_name'] = 'order_id';
        $data['grade_table'] = 'iwide_tips_orders';//类型
        $data['grade_id'] = $arr['order_id'];//tips_orders表的id
        $data['saler'] = $arr['saler'];
        $data['order_amount'] = $arr['pay_money'];//订单金额
        $data['remark'] = $data['product'] = '用户打赏';
        $data['order_id'] = $arr['order_id'];
        $data['grade_time'] = $data['order_time'] =  date('Y-m-d H:i:s');
        $data['grade_total'] = $arr['pay_money'] * 0.99;//0.99绩效
        $data['grade_typ'] = 2;//粉丝归属为1 按次为2
        $data['status'] = 1;//先设定为这个状态
        $data['hotel_id'] = $arr['hotel_id'];
        return $this->Grades_model->_create_grade($data);
    }

    //打赏通知速8
    public function notify_suba($data = array()){
        $this->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
        $suba = new Subaapi_webservice(false);
        $time1 = microtime(true);
        $res = $suba->SetRewardInfo($data);
        $time2 = microtime(true);
        MYLOG::w('打赏完成通知su8：s_time:'.$time1.'|e_time:'.$time2.'--request_param:'.json_encode($data).'--res:'.json_encode($res), 'tips');
        return $res;
    }

    //获取奖励记录中没有发放的（说明还没有绑定）
    public function get_unblind_list($filed='*',$inter_id='a455510007'){//每次取100条，按照handle_time升序来取 每次处理都update一次handle_time
        $sql = "select {$filed} from iwide_tips_reward_record where inter_id = '{$inter_id}' and is_send = 0  order by handle_times asc limit 100";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }



    //储值支付
    public function pay_order_in_banlance($inter_id,$order_id){
        //查询
        $this->db->where ( array (
            'inter_id' => $inter_id,
            'order_id' => $order_id,
            'pay_status'=> self::IS_PAYMENT_NOT
            // 'openid' => $openid
        ) );
        $order = $this->db->get ( 'roomservice_orders' )->row_array ();
        if($order){
            try {
                // $this->db->trans_begin();//开启事务
                //扣除用户余额
                $this->load->model('hotel/Member_model');

                if($this->Member_model->reduce_balance($order['inter_id'], $order['openid'], $order['sub_total'], $order['order_sn'], '点餐支付')){
                    //支付成功 更新状态
                    $this->db->where ( array (
                        'order_sn' => $order['order_sn'],
                        'inter_id' => $inter_id,
                    ) );
                    $this->db->update ( 'roomservice_orders', array (
                        'pay_status' => self::IS_PAYMENT_YES,
                        'pay_time' => date('Y-m-d H:i:s'),
                        'pay_money' => $order['sub_total'],
                    ) );
                    //发送模板消息
                    $this->handle_order($order['inter_id'],$order_id,$order['openid'],1);
                    //打印
                    $this->load->model ( 'plugins/Print_model' );
                    $this->Print_model->print_roomservice_order ( $order, 'new_order' );
                    return true;
                }else{
                    // $this->db->trans_commit();//提交
                    return false;
                }

            } catch (Exception $e) {
                // $this->db->trans_rollback();//回滚
                return false;
            }
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

}
