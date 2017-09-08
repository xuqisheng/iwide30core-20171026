<?php
class Roomservice_Orders_Model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_ORDERS = 'roomservice_orders';
    const TAB_ORDERS_ITEM = 'roomservice_orders_item';

    //订单状态
    const OS_UNCONFIRMED = 0;//未确认
    const OS_CONFIRMED = 5;//已确认/准备中
    const OS_SHPPING = 10;//已确认/配送中
    const OS_FINISH = 20;//已完成
    const OS_PER_CANCEL = 25;//个人取消
    const OS_HOL_CANCEL = 26;//酒店取消
    const OS_SYS_CANCEL = 27;//系统取消

    //支付状态 默认为0
    const IS_PAYMENT_YES = 1;   //已支付
    const IS_PAYMENT_NOT = 2;   //未支付
    const IS_PAYMENT_RE = 3;   //已退款

    const STOCK_LIMIT = 50;//限制购买量

    //订单状态
    public  $os_array = array(
        0 => '未确认',
        5 => '已确认',
        10=> '配送中',
        20=> '已完成',
        25=> '个人取消',
        26=>  '酒店取消',
        27=>  '系统取消'
    );
    public $ps_array = array(
        1   =>  '已支付',
        2   =>  '未支付',
        3   =>  '已退款',
    );
    public $pay_way_array = array(//支付方式
        1   =>  '微信',
        2   =>  '储值',
        3   =>  '线下',
        4   =>  '威富通',
    );

	public function get_resource_name()
	{
		return 'Roomservice_Orders_Model';
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
		return 'id';
	}

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        return array(
            'shop_id'   => '订单店铺',
            'order_sn'  => '订单编号',
            'add_time'  => '下单时间',
            'dissipate' => '预约时间',
            'consignee' => '用户信息',
            'phone'     => '联系方式',
            'note'      => '用户备注',
            'shop_note' => '后台备注',
            'order_status' => '订单状态',
            'dada_status' => '达达状态',
            'pay_way' => '支付方式',
            'refund_money' => '退款金额',
            'discount_money' => '优惠金额',
            'pay_money' => '实付金额',
        );
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
        $sql = 'select count(*) as c from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_ORDERS ) .' a ' . $where;
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
        $sql = "select * from iwide_roomservice_orders" . $where.$order_by.$limit;
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
        if(isset($filter['shop_id']) && $filter['shop_id']>0){
            $arr_where[] = "shop_id={$filter['shop_id']}";
        }
        if(isset($filter['start_time']) && $filter['start_time']){
            $arr_where[] = "add_time >='{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && $filter['end_time']){
            $arr_where[] = "add_time <'{$filter['end_time']} 23:59:59'";
        }

        if(isset($filter['book_start_time']) && $filter['book_start_time']){
            $arr_where[] = "dissipate >='{$filter['book_start_time']}'";
        }
        if(isset($filter['book_end_time']) && $filter['book_end_time']){
            $arr_where[] = "dissipate <'{$filter['book_end_time']} 23:59:59'";
        }

        if(isset($filter['merge_order_no'])){
            $arr_where[] = "merge_order_no = '' ";
        }

        if(isset($filter['openid']) && $filter['openid']){
            $arr_where[] = "openid='{$filter['openid']}'";
        }
        if(isset($filter['type']) && $filter['type']>0){
            $arr_where[] = "type IN ({$filter['type']})";
        }
        if(isset($filter['wd']) && !empty($filter['wd'])){
            $arr_where[] = "(consignee like '%{$filter['wd']}%' OR order_sn = '{$filter['wd']}')";
        }
        if(isset($filter['order_status']) && $filter['order_status']){
            if($filter['order_status'] == 1){//待付款 查询支付状态
                $arr_where[] = "pay_status= " . self::IS_PAYMENT_NOT . " and order_status = " . self::OS_UNCONFIRMED;
            }elseif($filter['order_status'] == 2){//待确认 查询订单状态
                $arr_where[] = " (order_status= " . self::OS_UNCONFIRMED . " and pay_way=3 or order_status=".self::OS_UNCONFIRMED." and pay_way <>3 and pay_status=".self::IS_PAYMENT_YES.")";
            }elseif($filter['order_status'] == 3){//已确认、准备中
                $arr_where[] = " order_status = " . self::OS_CONFIRMED;
            }elseif($filter['order_status'] == 4){//配送中
                $arr_where[] = " order_status = " . self::OS_SHPPING;
            }elseif($filter['order_status'] == 5){//已完成
                $arr_where[] = " order_status = " . self::OS_FINISH;
            }elseif($filter['order_status'] == 6){
                $arr_where[] = " order_status in( " . self::OS_HOL_CANCEL.',' .self::OS_SYS_CANCEL .',' .self::OS_PER_CANCEL. ")";
            }
        }
        return empty($arr_where) ? '' : implode(' and ', $arr_where);
    }


    /**
     * 获取某一页的数据，同时返回记录总数
     * @access 	public
     * @return 	int
     */
    public function get_page_ticket(array $filter,  $page, $page_size, $order_by=array()){
        $where= $this->ticket_where_sql($filter);
        $count = $this->get_count_ticket($filter, $where);
        $list = $this->get_list_ticket($filter, $page, $page_size,  $order_by,$where);
        return array($count, $list);
    }

    /**
     * 获取指定条件的记录总数
     * @access 	public
     * @return 	int
     */
    public function get_count_ticket($filter = NULL, $where = NULL) {
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->ticket_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //查询
        $sql = 'select count(*) as c from ' . $this->_db('iwide_r1')->dbprefix ( self::TAB_ORDERS ) .' a ' . $where;
        $row = $this->_db('iwide_r1')->query($sql)->row_array();
        //返回
        return $row['c'];
    }

    /**
     * 获取指定条件的记录列表
     * @access 	public
     * @return 	int
     */
    public function get_list_ticket(array $filter = NULL, $page = 0, $page_size = 0, array $order_by = NULL,$where = NULL){
        //条件
        if($where === NULL) $where = empty($filter) ? '' : $this->ticket_where_sql($filter);
        if( ! empty($where)) $where = ' where ' . $where;
        //排序
        $order_by = empty($order_by) ? (' order by order_id desc') : (' order by ' . $this->gen_order_by_sql($order_by));
        //分页
        $limit = $this->gen_limit($page, $page_size);
        //查询
        //$sql  = 'select * from ' . $this->db->dbprefix ( self::TAB_ORDERS ) . $where . $order_by . $limit;
        $sql = "select * from iwide_roomservice_orders" . $where.$order_by.$limit;
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
    public function ticket_where_sql($filter){
        $arr_where = array();
        $arr_where[] = 'merge_order_no > 0';
        if(isset($filter['inter_id']) && $filter['inter_id']){
            $arr_where[] = "inter_id='{$filter['inter_id']}'";
        }
        if(isset($filter['shop_id']) && $filter['shop_id']>0){
            $arr_where[] = "shop_id={$filter['shop_id']}";
        }
        if(isset($filter['start_time']) && $filter['start_time']){
            $arr_where[] = "add_time >='{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && $filter['end_time']){
            $arr_where[] = "add_time <'{$filter['end_time']} 23:59:59'";
        }

        if(isset($filter['book_start_time']) && $filter['book_start_time']){
            $arr_where[] = "dissipate >='{$filter['book_start_time']}'";
        }
        if(isset($filter['book_end_time']) && $filter['book_end_time']){
            $arr_where[] = "dissipate <'{$filter['book_end_time']} 23:59:59'";
        }

        if(isset($filter['openid']) && $filter['openid']){
            $arr_where[] = "openid='{$filter['openid']}'";
        }
        if(isset($filter['type']) && $filter['type']>0){
            $arr_where[] = "type IN ({$filter['type']})";
        }
        if(isset($filter['wd']) && !empty($filter['wd'])){
            $arr_where[] = "(consignee like '%{$filter['wd']}%' OR phone like '%{$filter['wd']}%' OR merge_order_no like '%{$filter['wd']}%' OR order_sn like '%{$filter['wd']}%')";
        }
        if(isset($filter['order_status']) && $filter['order_status']){
            if($filter['order_status'] == 1){//待付款 查询支付状态
                $arr_where[] = "pay_status= " . self::IS_PAYMENT_NOT . " and order_status = " . self::OS_UNCONFIRMED;
            }elseif($filter['order_status'] == 2){//待确认 查询订单状态
                $arr_where[] = " (order_status= " . self::OS_UNCONFIRMED . " and pay_way=3 or order_status=".self::OS_UNCONFIRMED." and pay_way <>3 and pay_status=".self::IS_PAYMENT_YES.")";
            }elseif($filter['order_status'] == 3){//已确认、准备中
                $arr_where[] = " order_status = " . self::OS_CONFIRMED;
            }elseif($filter['order_status'] == 4){//配送中
                $arr_where[] = " order_status = " . self::OS_SHPPING;
            }elseif($filter['order_status'] == 5){//已完成
                $arr_where[] = " order_status = " . self::OS_FINISH;
            }elseif($filter['order_status'] == 6){
                $arr_where[] = " order_status in( " . self::OS_HOL_CANCEL.',' .self::OS_SYS_CANCEL .',' .self::OS_PER_CANCEL. ")";
            }
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

    //获取订单信息(只查订单表)
    public function get_order_simple($data = array()){
        $where['inter_id'] = $data['inter_id'];
        $where['order_id'] = $data['order_id'];
        $where['openid'] = $data['openid'];
        $where['is_delete'] = 0;
        if(isset($data['pay_status']) && $data['pay_status']=='unpay'){
            $where['pay_status'] = self::IS_PAYMENT_NOT;//未付款
        }

        $res = $this->_db('iwide_r1')->where(
            $where
        )->get('roomservice_orders')->row_array();
        return $res;
    }
    //获取订单商品表(只查订单商品表)
    public function get_order_item_detail($data = array()){
        $sql = "select * from iwide_roomservice_orders_item where inter_id = '{$data['inter_id']}' and order_id = {$data['order_id']} and openid = '{$data['openid']}'";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }

    //商品表和订单商品表关联
    public function get_order_goods_info($data = array()){
        $sql = "select a.*,b.goods_img,b.shop_price from iwide_roomservice_orders_item a left join iwide_roomservice_goods b on a.inter_id = b.inter_id and a.goods_id = b.goods_id where a.inter_id = '{$data['inter_id']}'";
        if(!empty($data['order_id'])){
            $sql .= "  and a.order_id = {$data['order_id']} ";
        }
        if(!empty($data['order_ids'])){
            $sql .= " and a.order_id in ( " . implode(',',$data['order_ids']) . ")";
        }
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }

    //获取订单详情 （连表查询）
    public function get_order_detail($data = array()){
        $sql = "select a.*,b.goods_id,b.spec_id,b.goods_name,b.goods_num,goods_price from iwide_roomservice_orders a left join iwide_roomservice_orders_item b on a.inter_id = b.inter_id  and a.shop_id = b.shop_id and a.order_id = b.order_id where a.inter_id = '{$data['inter_id']}'  and a.openid = '{$data['openid']}'";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
    }


    //生成订单
    public function checkout($data = array(),$shop_info = array()){
        $this->load->helper('appointment');
        $return = array();
        $return['errcode'] = 1;
        $return['msg'] = '订单失败';

        try{
            $db = $this->db;

            //库存检测
            $goods_info = $data['goods'];
            foreach($goods_info as $k=>$v){
                if(empty($v['num']) || intval($v['num'] != $v['num'])){
                    $return['msg'] = '数量必须为整数';
                    return $return;
                }
                if($v['num'] > $v['stock'] || $v['num'] > self::STOCK_LIMIT){
                    $return['msg'] = '库存不足';
                    return $return;
                }
            }
            $pay_type = $data['pay_type'];//支付方式
            //计算订单总额 优惠计算//计算优惠 $total['row_total'] //商品金额  $total['sub_total'] 订单金额
            $ticket_discount_fee = !empty($data['ticket_discount_fee']) ? $data['ticket_discount_fee'] : 0;
            $total= $this->calculate_total($goods_info,$shop_info,$ticket_discount_fee);
            //如果订单总额是0的话 改成线下支付方式
            if($total['row_total'] == '0'){
                $pay_type = '3';//线下支付
            }

            //扣减库存（先减库存，再生成订单）
            $db->trans_begin(); //开启事务
            $this->load->model('roomservice/roomservice_goods_model');
            if(!$this->roomservice_goods_model->reduce_item_stock($goods_info)){
                $this->db->trans_rollback();//回滚
                $return['msg'] = '库存不足,扣减失败';
                return $return;
            }
            //组装收货信息
            $user_info = array('contact'=>'', 'phone'=>'','address'=>'');
            $order_sn = '';
            if($data['type']== 3 || $data['type']== 4){
                $order_sn = 'WM';
                //查询地址信息 根据inter_id opeind shopid hotel_id
                $address = $this->_db('iwide_r1')->where(
                    array('inter_id'=>$this->inter_id,
                        'hotel_id'=>$data['hotel_id'],
                        //'shop_id' =>$data['shop_id'],
                        'openid'=>$data['openid'],
                        'address_id' => $data['address_id'],
                        'status'=>1))
                    ->get('iwide_roomservice_address')
                    ->row_array();
                if(!empty($address)){
                    $user_info['contact'] = $address['contact'];
                    $user_info['phone'] = $address['phone'];
                    $user_info['address'] = $address['select_addr'] . ' ' . $address['address'];
                }else{
                    $this->db->trans_rollback();//回滚
                    $return['msg'] = '地址有误';
                    return $return;
                }
            }else{
                $user_info['contact'] = $data['type_id'];
                if($data['type']==1){
                    $order_sn = 'FJ';
                    $user_info['address'] = ($shop_info['identify_type'] ==2 && !empty($data['room_name'])) ? $data['room_name'] : $data['type_id'];
                }elseif($data['type']==2){
                    $order_sn = 'TS';
                    $user_info['address'] = $data['type_id'];
                }
            }
            //生成订单号  1房间 ：FJ 2堂食:TS 3外卖：WM  放到下面生成
           // $order_sn_num = $order_sn . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT );
            //TODO 检查订单号唯一性
            $CI = & get_instance();
            $remote_ip= $CI->input->ip_address();
            //服务费  = 商品总额 * 店铺服务费比率
            $cover_charge = !empty($data['cover_charge']) ? round($data['cover_charge'] * $total['row_total'],2) : 0;

            //组装订单数据
            $order = array(
                'inter_id'  => $data['inter_id'],
                'hotel_id'  => $data['hotel_id'],
                'shop_id'   => $data['shop_id'],
                'openid'    => $data['openid'],
              //  'order_sn'  => $order_sn_num,
                'order_status' =>   self::OS_UNCONFIRMED,//订单状态 未确认
                'pay_status' => self::IS_PAYMENT_NOT,//付款状态 未付款
                'pay_way'   => $pay_type,//付款方式 1微信支付 2储值  3线下支付
                'row_total' => formatMoney($total['row_total']),//商品总额
                'sub_total' => formatMoney($total['sub_total'] + $data['shipping_cost'] + $cover_charge),//订单应付金额=商品总额-各种优惠-各种活动+配送费+服务费
                'discount_fee' => $total['sub_total'],//订单优惠后金额=商品总额-各种优惠-各种活动
                'discount_id' => 0,
                'cover_charge' => $cover_charge,//服务费
                'shipping_type' => $data['shipping_type'],//外卖配送方式
                'shipping_cost' => $data['shipping_cost'],//配送费
                'discount_money' => formatMoney($total['discount_total']),
                'consignee' => $user_info['contact'],//收货人姓名 或者房间 桌号
                'address'   => $user_info['address'],//收货地址 或者 是房间号 桌号
                'phone'     => $user_info['phone'],
                'type'      => $data['type'],//类型 1房间 2堂食 3 外卖
                'is_lock'   => 0,//是否锁定订单
                'add_time'  => date('Y-m-d H:i:s'),
                'note'      => !empty($data['note'])?htmlspecialchars($data['note']):'',
                'verify_name'      => !empty($data['verify_name'])?htmlspecialchars($data['verify_name']):'',
                'verify_phone'      => !empty($data['verify_phone'])?htmlspecialchars($data['verify_phone']):'',
                'from_ip'   =>  $remote_ip,
                'longitude'   =>  !empty($address['longitude']) ? $address['longitude'] : 1,//经度
                'latitude'   =>  !empty($address['latitude']) ? $address['latitude'] : 1, //纬度
                'dissipate'   => !empty($data['dissipate']) ? $data['dissipate'] : '',
                'ticket_discount_fee'   => !empty($data['ticket_discount_fee']) ? $data['ticket_discount_fee'] : 0,
            );
            //插入前先查一次，是否有重复重新生成
           do{
                //生成订单号  1房间 ：FJ 2堂食:TS 3外卖：WM
               $order_sn_num = $order_sn . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT );
                $order_res = $this->check_order_sn($order_sn_num);
            }
           while($order_res == 0);//订单号重复重新生成
            $order['order_sn'] = $order_sn_num;
            $res = $this->db->insert($this->db->dbprefix ( self::TAB_ORDERS ),$order);
            if(!$res){
                $this->db->trans_rollback();//回滚
                $return['msg'] = '生成订单失败';
                return $return;
            }
            //获取插入的order_id
            $order_id = $this->db->insert_id ();
            //订单商品表 ORDERS_ITEM
            $order_goods = array();
            foreach($goods_info as $gk=>$gv){
                $order_goods[$gk]['order_id'] = $order_id;
                $order_goods[$gk]['inter_id'] = $data['inter_id'];
                $order_goods[$gk]['shop_id']  = $data['shop_id'];
                $order_goods[$gk]['openid']   = $data['openid'];
                $order_goods[$gk]['goods_id'] = $gv['goods_id'];
                $order_goods[$gk]['setting_id']  = $gv['setting_id'];
                $order_goods[$gk]['spec_id']  = $gv['spec_id'];
                $order_goods[$gk]['goods_name'] = $gv['goods_name'];
                $order_goods[$gk]['spec_name'] = is_array($gv['spec_name'])?implode(',',$gv['spec_name']):$gv['spec_name'];
                $order_goods[$gk]['goods_num'] = $gv['num'];
                $order_goods[$gk]['goods_price'] = $gv['shop_price'];
                $order_goods[$gk]['ticket_discount_fee'] = !empty($gv['discount']) ? $gv['discount'] : 0;
            }
            //插入orders_item 表
            $result = $this->db->insert_batch($this->db->dbprefix ( self::TAB_ORDERS_ITEM ),$order_goods);
            if(!$result){
                $this->db->trans_rollback();//回滚
                $return['msg'] = '插入订单详情失败';
                return $return;
            }
            if ($this->db->trans_status () === FALSE) {
                $this->db->trans_rollback ();

                return $return['msg'] = '订单失败';
            }else{
                $this->db->trans_complete();
            }

            $payinfo = array(
                'order_id' => $order_id,
                'order_sn' => $order_sn_num,
                'pay_type' => $pay_type
            );
            $return['errcode'] = 0;
            $return['msg'] = '订餐成功';
            $return['data'] = $payinfo;
            return $return;

        }catch (Exception $e) {
            return FALSE;
        }

    }

    //查询订单号是否存在
    public function check_order_sn($order_sn){
        $sql = "select count(1) as c from iwide_roomservice_orders where order_sn = '{$order_sn}'";
        $query = $this->_db('iwide_r1')->query($sql)->row_array();
        return $query['c']>0?0:true;
    }
    //取消订单
    //$data:订单数组
    public function cancel_order($data = array(),$status=''){

        if(empty($status)){
            $status = self::OS_PER_CANCEL;
            if(!isset($data['openid'])){//如果是前台个人取消，一定需要openID
                return false;
            }
        }
        $db = $this->db;
        $db->trans_begin ();
        if($data['pay_status'] == self::IS_PAYMENT_YES){//如果是付款了，改为已退款（前面处理了退款步骤来这里）
            $sql = "update iwide_roomservice_orders set order_status = ".$status." , pay_status = " . self::IS_PAYMENT_RE . "  where inter_id = '{$data['inter_id']}'  and order_id = {$data['order_id']} and order_status != " . self::OS_FINISH ;
        }else{
            $sql = "update iwide_roomservice_orders set order_status = ".$status."  where inter_id = '{$data['inter_id']}'  and order_id = {$data['order_id']} and order_status != " . self::OS_FINISH ;
        }

        if(isset($data['openid']) && !empty($data['openid'])){//后台取消不用openid  前台取消需要openid
            $sql .= " and openid = '{$data['openid']}'";
        }
        $db->query($sql);
        if($db->affected_rows() != 1){
            $this->write_log($data,'更新订单表状态失败',$sql);
            $db->trans_rollback();
            return false;
        }
        //查询订单商品
        $order_goods = $this->get_order_item_detail($data);
        //还原库存  预约核销
        if ($data['type'] == 4)
        {
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            $res = $this->roomservice_ticket_dateprice_model->roback_goods_stock($order_goods,$data['dissipate']);
        }
        else
        {
            $this->load->model('roomservice/roomservice_goods_model');
            $res = $this->roomservice_goods_model->roback_goods_stock($order_goods);
        }
        $this->write_log($data,'更新订单表状态失败',$sql);
        if(!$res){//失败
            $db->trans_rollback();
            return false;
        }
        if ($db->trans_status () === FALSE) {
            $db->trans_rollback ();
            return false;
        }
        $db->trans_commit ();
        return true;
    }

    //退款一系列操作
    public function update_refund_data($order,$refund_sn,$trade_no = '',$data,$status = ''){
        $this->db->trans_begin ();
        $sql = "update iwide_roomservice_refund set refund_status = 1 , refund_id = '{$data['refund_id']}' , refund_fee = {$data['refund_fee']} where refund_sn = '{$refund_sn}' and inter_id = '{$order['inter_id']}' and id = {$data['id']} ";
        if(!empty($trade_no)){
            $sql .= "and trade_no = '".$trade_no . "'";
        }
        $this->db->query($sql);
        if($this->db->affected_rows() != 1){
            $this->write_log($order,'更新退款表失败',$refund_sn);
            $this->db->trans_rollback();
            return false;
        }
        //更新订单表状态
        if($this->cancel_order($order,$status)){
            if ($this->db->trans_status () === FALSE) {
                $this->db->trans_rollback ();
                return false;
            }
            $this->db->trans_commit ();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }
    }

    //计算订单金额
    public function calculate_total($goods_info = array(),$shop_info = array(),$ticket_fee = 0){
        if(empty($goods_info)){
            return false;
        }
        //print_r($goods_info);exit;
        $total= array('row_total'=>0, 'sub_total'=>0,'discount_total'=>0,'en_count_total'=>0,'un_count_total'=>0);//count_total 参与优惠商品金额，discount_total优惠金额
        foreach($goods_info as $k=>$v){
            $num = ($v['num']<1)?1:intval($v['num']);
            //qty 为计算数量后附加的属性
            $total['row_total'] += $v['shop_price'] * $num; //商品总金额
            //qty 为计算数量后附加的属性
            //参与优惠
            if (!empty($v['is_discount']) && $v['is_discount'] == 2)
            {
                $total['un_count_total'] += $v['shop_price']* $num;//后面用做 计算优惠
            }
            else
            {
                $total['en_count_total'] += $v['shop_price']* $num;//不参与优惠
                $total['sub_total'] += $v['shop_price']* $num;//后面用做 计算优惠
            }
        }

        //门票提前预约优惠金额
        //$sub_total = $total['sub_total'];
        $total['sub_total'] = $total['sub_total'] - $ticket_fee;

        //先查询设置的优惠 满减 折扣 随机减
        if(!empty($shop_info['discount_type']) && !empty($shop_info['discount_config'])){
            //查询时间
            if($shop_info['discount_start_time'] <= date('Y-m-d H:i:s') && $shop_info['discount_end_time']> date('Y-m-d H:i:s')){
                if($shop_info['discount_type'] == 1){//单满减
                    if($total['sub_total'] >= $shop_info['discount_config'][0]){
                        $total['sub_total'] = $total['sub_total'] - $shop_info['discount_config'][1];
                        $total['discount_total'] = $shop_info['discount_config'][1];
                    }
                }elseif($shop_info['discount_type'] == 2){//每满减
                    if($total['sub_total'] >= $shop_info['discount_config'][0]){
                        $total['discount_total'] = floor($total['sub_total'] / $shop_info['discount_config'][0]) * $shop_info['discount_config'][1];
                        $total['sub_total'] = $total['sub_total'] - $total['discount_total'];
                    }
                }elseif($shop_info['discount_type'] == 3){//折扣
                    $total['discount_total'] = $total['sub_total'] - $total['sub_total'] * $shop_info['discount_config'][0]*0.1;
                    $total['sub_total'] = $total['sub_total'] - $total['discount_total'];
                }elseif($shop_info['discount_type'] == 4){//随机减
                    if($total['sub_total'] >= $shop_info['discount_config'][0]){
                        $percent = mt_rand($shop_info['discount_config'][1],$shop_info['discount_config'][2]);
                        $total['discount_total'] = $total['sub_total'] * ($percent/100);
                        $total['sub_total'] = $total['sub_total'] - $total['discount_total'];
                    }
                }
            }
        }
        $total['discount_total'] = $total['discount_total'] + $ticket_fee;
        $total['sub_total']  = $total['sub_total'] + $total['un_count_total'];
        return $total;
    }
    //查对应分销员的openid
    public function get_shop_saler_info($inter_id = '',$shop_id = 0){
    //$sql = "select a.qrcode_id,a.openid from iwide_hotel_staff a left join iwide_roomservice_shop b on a.inter_id = b.inter_id and a.qrcode_id = b.msgsaler where a.inter_id = '{$inter_id}' and b.shop_id = {$shop_id}";
        //先查出对应shop的分销员数据
        $sql = "select msgsaler from iwide_roomservice_shop where inter_id = '{$inter_id}' and shop_id = {$shop_id} and status = 1 and is_delete=0 limit 1";
        $saler = $this->_db('iwide_r1')->query($sql)->row_array();
        if(!empty($saler) && !empty($saler['msgsaler'])){
            $saler = $saler['msgsaler'];
            $sql = "select qrcode_id,openid from iwide_hotel_staff where inter_id = '{$inter_id}' and qrcode_id in ({$saler})";
            $res = $this->_db('iwide_r1')->query($sql)->result_array();
            return $res;
        }else{
            return '';
        }
}

    //余额退款处理
    public function balance_refund($inter_id = '',$openid = '',$refund_money = 0,$order_id = 0){
        try {
            // $this->db->trans_begin();//开启事务
            $this->load->model ( 'hotel/Member_new_model' );
            $res =  $this->Member_new_model->addBalance($inter_id,$openid,$order_id,$refund_money,'快乐送余额储值退款',array('module'=>'dc'));//余额退款处理
            if($res){
                return $res;
            }else{
                $this->write_log($inter_id.'| order_id:'.$order_id,'余额退款失败',$res);
                return false;
            }
            // $this->db->trans_commit();//提交
            //return false;
        } catch (Exception $e) {
            // $this->db->trans_rollback();//回滚
            return false;
        }
    }

    //发送模板消息
    public function handle_order($inter_id,$order_id,$openid = '',$status){
        $this->load->model ( 'plugins/Template_msg_model' );
        $order = $this->_db('iwide_r1')->get_where ( 'roomservice_orders',array('inter_id'=>$inter_id,'order_id'=>$order_id,'is_delete'=>0) )->row_array ();
        if($order){
            //查询订单商品
            $order_goods = $this->_db('iwide_r1')->get_where('iwide_roomservice_orders_item',array('inter_id'=>$inter_id,'order_id'=>$order_id))->result_array();
            $show_name = '';
            if($order_goods){
                /*foreach($order_goods as $k=>$v){
                    $show_name .= $v['goods_name'] . $v['spec_name'] . '|';
                }*/
                $show_name .= $order_goods[0]['goods_name'] .'('. $order_goods[0]['spec_name'] .')' . (count($order_goods)>0?'等':'');
            }
            $order['show_name'] = $show_name;
            $order['order_show_status'] = '';//催单显示状态使用
            switch($status){
                case 1://支付完成发送模板消息

                    if ($order['type'] != 4)
                    {
                        if($order['pay_way']!=3){//不是线下支付
                            $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_pay_success' );
                        }
                        //发送待接单给管理员
                        $saler = $this->get_shop_saler_info($inter_id,$order['shop_id']);
                        if($saler){
                            foreach($saler as $k=>$v){
                                $order['openid'] = $v['openid'];//给管理员
                                $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_order_wait' );
                            }
                        }
                    }
                    else //核销消息
                    {
                        //发送待接单给管理员
                        $saler = $this->get_shop_saler_info($inter_id,$order['shop_id']);
                        if($saler){
                            foreach($saler as $k=>$v){
                                $order['openid'] = $v['openid'];//给管理员
                                $this->Template_msg_model->send_ticket_success_msg ( $order, 'ticket_order_wait' );
                            }
                        }
                    }


                    break;
                case 2://催单发送模板消息 给管理员 给用户
                    //给用户
                    //添加订单状态
                    $order['order_show_status'] = $this->os_array[$order['order_status']];
                    $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_customer_remind' );
                    $saler = $this->get_shop_saler_info($inter_id,$order['shop_id']);
                    if($saler){

                        foreach($saler as $k=>$v){
                            $order['openid'] = $v['openid'];//给管理员
                            $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_admin_remind' );
                        }
                    }
                    break;
                case 5://订单确认
                    if ($order['type'] != 4)
                    {
                        $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_order_confirm' );
                    }
                    else
                    {
                        $res = $this->Template_msg_model->send_ticket_success_msg ( $order, 'ticket_order_confirm' );
                    }

                    break;
                case 10://订单配送
                    $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_order_shipping' );
                    break;
                case 20://订单完成
                    if ($order['type'] != 4)
                    {
                        $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_order_finish' );
                    }
                    else
                    {
                        $res = $this->Template_msg_model->send_ticket_success_msg ( $order, 'ticket_order_finish' );
                    }

                    break;
                case 25://个人取消
                    $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_person_cancel_customer' );
                    $saler = $this->get_shop_saler_info($inter_id,$order['shop_id']);
                    if($saler){
                        foreach($saler as $k=>$v){
                            $order['openid'] = $v['openid'];//给管理员
                            $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_person_cancel_admin' );
                        }
                    }
                    break;
                case 26://酒店取消
                    if ($order['type'] != 4)
                    {
                        $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_hotel_cancel_customer' );
                    }
                    else
                    {
                        $res = $this->Template_msg_model->send_ticket_success_msg ( $order, 'ticket_hotel_cancel_customer' );
                    }

                    break;
                case 27://系统取消
                    if ($order['type'] != 4)
                    {
                        $res = $this->Template_msg_model->send_roomservice_success_msg ( $order, 'roomservice_sys_cancel_customer' );
                    }
                    else
                    {
                        $res = $this->Template_msg_model->send_ticket_success_msg ( $order, 'ticket_sys_cancel_customer' );
                    }

                    break;
                default :
                    break;
            }
        }

    }
    //支付回调操作
    public function pay_return($order_id,$inter_id = '',$openid = ''){
        if ($openid){
            $this->_db('iwide_r1')->where ( 'openid', $openid );
        }
        $this->_db('iwide_r1')->where ( array (
            'order_id' => $order_id
        ) );
        $order = $this->_db('iwide_r1')->get ( 'roomservice_orders' )->row_array ();
        if($order){
            if($order['pay_status'] == self::IS_PAYMENT_YES){
                $payresult = $this->_db('iwide_r1')->get_where ( 'pay_log', array (
                    'out_trade_no' => $order['order_sn']
                ) )->row_array ();
                if (! $payresult) {
                    $this->db->where ( array (
                        'order_id' => $order ['order_id'],
                        'inter_id' => $order ['inter_id']
                    ) );
                    $this->db->update ( 'roomservice_orders', array (
                        'operate_reason' => '没有支付结果'
                    ) );
                }else{
                    $this->db->where ( array (
                        'order_id' => $order ['order_id'],
                        'inter_id' => $order ['inter_id']
                    ) );
                    $this->db->update ('roomservice_orders', array (
                        'operate_reason' => '支付完成'
                    ) );
                    //发送模板消息
                    $this->handle_order($order['inter_id'],$order_id,$order['openid'],1);

                    if ($order['type'] != 4)
                    {
                        //打印机
                        $order['order_detail'] = $this->db->get_where('roomservice_orders_item',array('order_id'=>$order['order_id']))->result_array();
                        $this->load->model ( 'plugins/Print_model' );
                        //$this->Print_model->print_roomservice_order ( $order, 'new_order' );
                        $this->Print_model->print_roomservice_order ( $order, 'ensure_order' );
                    }
                }
            }
        }
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

                if($this->Member_model->reduce_balance($order['inter_id'], $order['openid'], $order['sub_total'], $order['order_sn'], '点餐支付',array('module'=>'dc'),array('hotel_id'=>$order['hotel_id']))){
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
                    if ($order['type'] != 4)
                    {
                        //打印机
                        $order['order_detail'] = $this->db->get_where('roomservice_orders_item',array('order_id'=>$order['order_id']))->result_array();
                        $this->load->model ( 'plugins/Print_model' );
                        //$this->Print_model->print_roomservice_order ( $order, 'new_order' );
                        $order['pay_status'] = self::IS_PAYMENT_YES;
                        $this->Print_model->print_roomservice_order ( $order, 'ensure_order' );
                    }

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

    //取线上支付 15分钟未支付订单
    public function get_cancel_order_list(){
        $time = date('Y-m-d H:i:s',time() - 900);
        $sql = "select * from iwide_roomservice_orders where `type` < 4 AND pay_way != 3 and pay_status = " . self::IS_PAYMENT_NOT . " and order_status = " . self::OS_UNCONFIRMED . " and is_delete = 0 and add_time < '{$time}' limit 100";
        $query = $this->_db('iwide_r1')->query($sql);
        return $query->result_array();
    }

    //取线上支付 15分钟未支付订单
    public function get_cancel_order_list_ticket()
    {
        $time = date('Y-m-d H:i:s',time() - 900);
        $sql = "select * from iwide_roomservice_orders where `type` = 4 AND merge_order_no = '' AND pay_way != 3 and pay_status = " . self::IS_PAYMENT_NOT . " and order_status = " . self::OS_UNCONFIRMED . " and is_delete = 0 and add_time < '{$time}' limit 100";
        $query = $this->_db('iwide_r1')->query($sql);
        return $query->result_array();
    }

    /**
     * 根据特定时间条件返回订单
     * @param string $inter_id 公众号内部ID
     * @param array $condit 根据$check_type 不同，传入不同参数值，具体参见代码
     * @param string:返回数据类型
     */
    public function get_new_time_order($inter_id, $condit = array(),$return_type='nums'){
        $this->_db('iwide_r1')->where ( 'inter_id', $inter_id );
        $this->_db('iwide_r1')->where_in ( 'type', isset($condit['type']) && !empty($condit['type']) ? $condit['type'] : array(1,2,3) );
        //$sql = "SELECT count(*) AS num,`type` FROM iwide_roomservice_orders WHERE inter_id = '{$inter_id}'";
        if(!empty($condit['hotel_ids'])){
            $this->_db('iwide_r1')->where_in ( 'hotel_id', $condit['hotel_ids'] );
            $condit['hotel_ids'] = implode(',',$condit['hotel_ids']);
            //$sql .= " AND hotel_id in({$condit['hotel_ids']})";
        }
        if(!empty($condit['shop_ids'])){
            $this->_db('iwide_r1')->where_in ( 'shop_id', $condit['shop_ids'] );
            $condit['shop_ids'] = implode(',',$condit['shop_ids']);
            //$sql .= " AND shop_id in({$condit['shop_ids']})";
        }
        //订单时间
        if(!empty($condit['check_time'])){
            $this->_db('iwide_r1')->where('add_time >=',date('Y-m-d H:i:s',$condit['check_time']));
            //$sql .= " AND add_time >= '".date('Y-m-d H:i:s',$condit['check_time']) ."'";
        }

       // $sql .= " GROUP BY `type`";

        //$result = $this->_db('iwide_r1')->query($sql);
        $result=$this->_db('iwide_r1')->get('roomservice_orders');
        if($return_type=='nums'){
            return $result->num_rows();
        }else{
            return $result->result_array();

        }
    }


    /**
     * 更新退款金额
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
                $this->db->set($key, $value, $key == 'refund_money' || $key == 'pay_money' ? false : true);
            }

            $this->db->where($where);
            $this->db->update(self::TAB_ORDERS);
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
     * 查询待核销订单[待消费，消费时间已过期]
     * @param string $field 字段
     * @return
     */
    public function get_finish_order($field = 'RO.*')
    {
        // $now = date('Y-m-d H:i:s',time());
        $now = date('Y-m-d 00:00:00',time());
        $sql = "SELECT {$field} FROM " . $this->db->dbprefix (self::TAB_ORDERS) . " AS RO
                LEFT JOIN " . $this->db->dbprefix('roomservice_shop') . " AS RS  ON RO.shop_id = RS.shop_id
                WHERE `type` = 4 AND order_status = 5 AND outdate_order = 2 AND dissipate < '{$now}'
                ";
        $data = $this->_db('iwide_r1')->query($sql)->result_array();
        return $data;
    }


    //即时确认自动接单
    public function auto_update_status($inter_id,$order)
    {
        $this->load->model('roomservice/roomservice_orders_model');
        //更新订单状态 为已接单
        $res = $this->db->update('roomservice_orders',array('order_status'=>5),array('inter_id'=>$inter_id,'order_id'=>$order['order_id']));
        $rows = $this->db->affected_rows();
        if($res)
        {
            //订单跟踪数组
            $array_action = array(
                'inter_id'=>$inter_id,
                'openid' => '',
                'content' => '订单已接单',
                'order_id' => $order['order_id'],
                'type' => 2,//跟踪
                'order_status'=>5,//更新后的订单状态
                'add_time' => date('Y-m-d H:i:s')
            );
            //记录订单操作日志
            $order_log = array(
                'inter_id'=> $inter_id,
                'order_id' => $order['order_id'],
                'hotel_id' => $order['hotel_id'],
                'shop_id'  => $order['shop_id'],
                'operation'=> '系统',
                'order_status'=>5,
                'add_time'=> date('Y-m-d H:i:s'),
                'types' =>2,//后台
            );

            //发送模板消息
            $this->roomservice_orders_model->handle_order($inter_id,$order['order_id'],$order['openid'],5);

            $this->db->insert('roomservice_action',$array_action);//插入订单跟踪表
            $order['order_id'] = $order['order_id'];

            $note = '更新订单状态：从 ' . $this->roomservice_orders_model->os_array[0]
                . ' 更新到 '.$this->roomservice_orders_model->os_array[5] . ',订单信息：'.json_encode($order);
            $order_log['action_note'] = $note;
            $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
        }

        return $rows;
    }



    //生成预约核销订单
    public function checkout_ticket($data = array(),$shop_info = array())
    {
        $this->load->helper('appointment');
        $return = array();
        $return['errcode'] = 1;
        $return['msg'] = '订单失败';

        try{
            $db = $this->db;

            //库存检测
            $goods_info = $data['goods'];
            foreach($goods_info as $k=>$v)
            {
                if(empty($v['count']) || intval($v['count'] != $v['count']))
                {
                    $return['msg'] = '数量必须为整数';
                    return $return;
                }
                if($v['count'] > $v['stock'] || $v['count'] > self::STOCK_LIMIT){
                    $return['msg'] = '库存不足';
                    return $return;
                }
            }
            $pay_type = $data['pay_type'];//支付方式
            //计算订单总额 优惠计算//计算优惠 $total['row_total'] //商品金额  $total['sub_total'] 订单金额
            $ticket_discount_fee = !empty($data['ticket_discount_fee']) ? $data['ticket_discount_fee'] : 0;
            $total= $this->calculate_total($goods_info,$shop_info,$ticket_discount_fee);
            //如果订单总额是0的话 改成线下支付方式
            if($total['row_total'] == '0')
            {
                $pay_type = '3';//线下支付
            }

            //扣减库存（先减库存，再生成订单）
            $db->trans_begin(); //开启事务
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            if(!$this->roomservice_ticket_dateprice_model->reduce_item_stock($goods_info,$data['dissipate']))
            {
                $this->db->trans_rollback();//回滚
                $return['msg'] = '库存不足,扣减失败';
                return $return;
            }
            //组装收货信息
            $user_info = array('contact'=>'', 'phone'=>'','address'=>'');
            $order_sn = '';
            if($data['type']== 3 || $data['type']== 4)
            {
                $order_sn = 'WM';
                //查询地址信息 根据inter_id opeind shopid hotel_id
                $address = $this->_db('iwide_r1')->where(
                    array('inter_id'=>$this->inter_id,
                        'hotel_id'=>$data['hotel_id'],
                        //'shop_id' =>$data['shop_id'],
                        'openid'=>$data['openid'],
                        'address_id' => $data['address_id'],
                        'status'=>1))
                    ->get('iwide_roomservice_address')
                    ->row_array();
                if(!empty($address)){
                    $user_info['contact'] = $address['contact'];
                    $user_info['phone'] = $address['phone'];
                    $user_info['address'] = $address['select_addr'] . ' ' . $address['address'];
                }else{
                    $this->db->trans_rollback();//回滚
                    $return['msg'] = '地址有误';
                    return $return;
                }
            }else{
                $user_info['contact'] = $data['type_id'];
                if($data['type']==1){
                    $order_sn = 'FJ';
                    $user_info['address'] = ($shop_info['identify_type'] ==2 && !empty($data['room_name'])) ? $data['room_name'] : $data['type_id'];
                }elseif($data['type']==2){
                    $order_sn = 'TS';
                    $user_info['address'] = $data['type_id'];
                }
            }
            //生成订单号  1房间 ：FJ 2堂食:TS 3外卖：WM  放到下面生成
            // $order_sn_num = $order_sn . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT );
            //TODO 检查订单号唯一性
            $CI = & get_instance();
            $remote_ip= $CI->input->ip_address();
            //服务费  = 商品总额 * 店铺服务费比率
            $cover_charge = !empty($data['cover_charge']) ? round($data['cover_charge'] * $total['row_total'],2) : 0;

            //组装订单数据
            $order = array(
                'inter_id'  => $data['inter_id'],
                'hotel_id'  => $data['hotel_id'],
                'shop_id'   => $data['shop_id'],
                'openid'    => $data['openid'],
                //  'order_sn'  => $order_sn_num,
                'order_status' =>   self::OS_UNCONFIRMED,//订单状态 未确认
                'pay_status' => self::IS_PAYMENT_NOT,//付款状态 未付款
                'pay_way'   => $pay_type,//付款方式 1微信支付 2储值  3线下支付
                'row_total' => formatMoney($total['row_total']),//商品总额
                'sub_total' => formatMoney($total['sub_total'] + $data['shipping_cost'] + $cover_charge),//订单应付金额=商品总额-各种优惠-各种活动+配送费+服务费
                'discount_fee' => $total['sub_total'],//订单优惠后金额=商品总额-各种优惠-各种活动
                'discount_id' => 0,
                'cover_charge' => $cover_charge,//服务费
                'shipping_type' => $data['shipping_type'],//外卖配送方式
                'shipping_cost' => $data['shipping_cost'],//配送费
                'discount_money' => formatMoney($total['discount_total']),
                'consignee' => $user_info['contact'],//收货人姓名 或者房间 桌号
                'address'   => $user_info['address'],//收货地址 或者 是房间号 桌号
                'phone'     => $user_info['phone'],
                'type'      => $data['type'],//类型 1房间 2堂食 3 外卖
                'is_lock'   => 0,//是否锁定订单
                'add_time'  => date('Y-m-d H:i:s'),
                'note'      => !empty($data['note'])?htmlspecialchars($data['note']):'',
                'from_ip'   =>  $remote_ip,
                'longitude'   =>  !empty($address['longitude']) ? $address['longitude'] : 1,//经度
                'latitude'   =>  !empty($address['latitude']) ? $address['latitude'] : 1, //纬度
                'dissipate'   => !empty($data['dissipate']) ? $data['dissipate'] : '',
                'ticket_discount_fee'   => !empty($data['ticket_discount_fee']) ? $data['ticket_discount_fee'] : 0,
            );
            //插入前先查一次，是否有重复重新生成
            do{
                //生成订单号  1房间 ：FJ 2堂食:TS 3外卖：WM
                $order_sn_num = $order_sn . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT );
                $order_res = $this->check_order_sn($order_sn_num);
            }
            while($order_res == 0);//订单号重复重新生成
            $order['order_sn'] = $order_sn_num;
            $res = $this->db->insert($this->db->dbprefix ( self::TAB_ORDERS ),$order);
            if(!$res){
                $this->db->trans_rollback();//回滚
                $return['msg'] = '生成订单失败';
                return $return;
            }
            //获取插入的order_id
            $order_id = $this->db->insert_id ();
            //订单商品表 ORDERS_ITEM
            $order_goods = array();
            foreach($goods_info as $gk=>$gv){
                $order_goods[$gk]['order_id'] = $order_id;
                $order_goods[$gk]['inter_id'] = $data['inter_id'];
                $order_goods[$gk]['shop_id']  = $data['shop_id'];
                $order_goods[$gk]['openid']   = $data['openid'];
                $order_goods[$gk]['goods_id'] = $gv['goods_id'];
                $order_goods[$gk]['setting_id']  = $gv['setting_id'];
                $order_goods[$gk]['spec_id']  = $gv['spec_id'];
                $order_goods[$gk]['goods_name'] = $gv['goods_name'];
                $order_goods[$gk]['spec_name'] = is_array($gv['spec_name'])?implode(',',$gv['spec_name']):$gv['spec_name'];
                $order_goods[$gk]['goods_num'] = $gv['count'];
                $order_goods[$gk]['goods_price'] = $gv['shop_price'];
                $order_goods[$gk]['ticket_discount_fee'] = !empty($gv['discount']) ? $gv['discount'] : 0;
            }
            //插入orders_item 表
            $result = $this->db->insert_batch($this->db->dbprefix ( self::TAB_ORDERS_ITEM ),$order_goods);
            if(!$result){
                $this->db->trans_rollback();//回滚
                $return['msg'] = '插入订单详情失败';
                return $return;
            }
            if ($this->db->trans_status () === FALSE) {
                $this->db->trans_rollback ();

                return $return['msg'] = '订单失败';
            }else{
                $this->db->trans_complete();
            }

            $payinfo = array(
                'order_id' => $order_id,
                'order_sn' => $order_sn_num,
                'pay_type' => $pay_type
            );
            $return['errcode'] = 0;
            $return['msg'] = '订餐成功';
            $return['data'] = $payinfo;
            return $return;

        }catch (Exception $e) {
            return FALSE;
        }

    }

    /**
     * 插入订单
     * @param $order
     * @return mixed
     */
    public function create_order($order)
    {
        $this->db->insert($this->db->dbprefix(self::TAB_ORDERS),$order);
        return $this->db->insert_id();
    }

    /**
     * 获取订单
     */
    public function get_orders($filter,$merge_order = '',$select = '*',$db = 'r')
    {
        $db = ($db == 'r') ? $this->_db('iwide_r1') : $this->db;

        $db->select($select);
        $db->where($filter);
        if (!empty($merge_order))
        {
            $db->where_in('merge_order_no',$merge_order);
        }

        $res = $db->get(self::TAB_ORDERS)->result_array();
        return $res;
    }
}
