<?php
class Okpay_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_OKPAY_ORDERS = 'okpay_orders';

	public function get_resource_name()
	{
		return 'Okpay_model';
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
		return self::TAB_OKPAY_ORDERS;
	}

	public function table_primary_key()
	{
		return 'id';
	}

	public function attribute_labels()
	{
		return array(
			'id'=> '编号',
			'inter_id'=>'公众号id',
			'hotel_id'=>'酒店id',
			'openid'=>'openid',
			'nickname'=>'昵称',
			'hotel_name'=>'酒店',
			'money'=>'消费金额',
			'pay_money'=>'实付金额',
			'discount_money'=>'折扣',
			'no_sale_money'=>'不优惠金额',
			'pay_type_desc'=>'交易场景',
			'out_trade_no'=> '订单号',
			'sale'=> '分销员',
			'pay_time'=> '支付时间',
			'create_time'=> '下单时间',
			'update_time'=> '修改时间',
			'pay_status'=> '支付状态',
			'status'=>'状态',
			'pay_type'=>'支付类型',
			'trade_no'=>'交易号',
			'pay_way'=>'支付方式',
			'activity_id'=>'优惠id',
			'remark'=>'备注',
			'refund_money'=>'退款金额',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		//主键字段一定要放在第一位置，否则 grid位置会发生偏移
		return array(
			//'id',
			'hotel_name',
			'pay_type_desc',
			'nickname',
			'money',
			'no_sale_money',
			'discount_money',
			//'sale',
			'create_time',
			'update_time',
			'pay_time',
			'out_trade_no',
			'pay_money',
			'refund_money',
			'pay_status',
			//'pay_way',
			'remark',
		);
	}

	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 *   select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui()
	{
		$base_util = EA_base::inst();
		$modules   = config_item('admin_panels')? config_item('admin_panels'): array();
		/** 获取本管理员的酒店权限  */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;

		if ($this->_admin_inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($this->_admin_inter_id)
			$filter = array (
				'inter_id' => $this->_admin_inter_id
			);
		if (is_array ( $filter )) {
			$this->load->model ( 'wx/publics_model' );
			$publics = $this->publics_model->get_public_hash ( $filter );
			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
		}

		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = array ();
		else if ($this->_admin_hotels)
			$filterH = array (
				'hotel_id' => $this->_admin_hotels
			);
		else
			$filterH = array ();

		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$hotels = $hotels + array (
					'0' => '-不限定-'
				);
		}
		return array(
			'id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'inter_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'hotel_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'nickname' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> 'readonly',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',
			),
			'openid' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> 'readonly',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',
			),
			'hotel_name' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'money' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'price',	//textarea|text|combobox|number|email|url|price
			),
			'pay_money' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'price',	//textarea|text|combobox|number|email|url|price
			),
			'discount_money' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'price',	//textarea|text|combobox|number|email|url|price
			),
			'no_sale_money' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'price',	//textarea|text|combobox|number|email|url|price
			),
			'pay_type_desc' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'out_trade_no' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'sale' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'pay_time' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> ' readonly ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				'function'=> 'unix_to_human|true|cn2',
				'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
			),
			'create_time' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				'function'=> 'unix_to_human|true|cn2',
				'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
			),
			'update_time' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				'function'=> 'unix_to_human|true|cn2',
				'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
			),
			'pay_status' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> 'readonly',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				'function'=> 'string_format_pay_status',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
				//'select' => array(0 => '不可用', 1 => '待支付',3=>'支付成功',4=>'已退款')
			),'pay_type' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),'trade_no' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),'status' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),'pay_way' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				'function'=> 'string_format_pay_ways',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
			'activity_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
				'type'=>'text',	//textarea|text|combobox|number|email|url|price
			),
		);
	}



	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
		return array('field'=>'id', 'sort'=>'desc');
	}


	public function create_new_okpay_order($arr){
		$arr['create_time'] = time();
		$arr['update_time'] = time();
		$arr['status']		= 1;
		$arr['pay_status']	= 1;

		$this->db->insert(self::TAB_OKPAY_ORDERS,$arr);
		$insert_id = $this->db->insert_id();

		if($insert_id){
			return $insert_id;
		}else{
			return false;
		}
	}

	public function set_okpay_model($trade_no,$our_trade_no,$status,$refund_money){
		$this->db->where ( array (
			'out_trade_no' => $our_trade_no,
			'trade_no' => $trade_no,
			'pay_status !=' => $status,
		) );
		$this->db->update (self::TAB_OKPAY_ORDERS, array (
			'pay_status' => $status,
			'refund_money' => $refund_money,
			'update_time'=>time()
		) );

        return $this->db->affected_rows();
	}

	public function get_okpay_order_detail($order_id,$openid){
		$this->_db('iwide_r1')->select( '*' );
		$this->_db('iwide_r1')->where ( array (
			'out_trade_no' => $order_id,
			'openid'   => $openid
		) );
		return $this->_db('iwide_r1')->get( self::TAB_OKPAY_ORDERS )->row_array();
	}

	public function get_hotel_okpay_recode($hotel_id,$inter_id,$opendid,$sort='create_time desc',$nums=null,$offset=null){
		$this->_db('iwide_r1')->select( 'id,pay_type_desc,pay_money,out_trade_no,update_time,sale,create_time,pay_status' );
		$this->_db('iwide_r1')->order_by($sort );
		if (!is_null( $nums )){
			$this->_db('iwide_r1')->limit ($nums, $offset );
		}
		$this->_db('iwide_r1')->where ( array (
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'openid'   => $opendid
		) );
		return $this->_db('iwide_r1')->get ( self::TAB_OKPAY_ORDERS )->result_array();
	}

	public function get_hotel_pay_count($hotel_id,$inter_id,$opendid,$pay_status){
		$this->_db('iwide_r1')->select ( 'sum(pay_money) paycount' );
		$this->_db('iwide_r1')->where ( array (
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'pay_status'=>$pay_status,
			'openid'   => $opendid
		) );
		return $this->_db('iwide_r1')->get(self::TAB_OKPAY_ORDERS )->row_array();
	}

	/**
	 * 获取分销员的分销记录
	 */
	public function get_saler_okpay_recode($inter_id,$saler_qrcode_id,$sort='create_time desc',$begin_time="",$end_time="",$nums=null,$offset=null){
		if(empty($begin_time)){
			$begin_time = 1;
		}
		if(empty($end_time)){
			$end_time = 9999999999;
		}

		$this->_db('iwide_r1')->select( 'id,pay_type_desc,pay_money,create_time,pay_status' );
		$this->_db('iwide_r1')->order_by($sort );
		if (!is_null( $nums )){
			$this->_db('iwide_r1')->limit ($nums, $offset );
		}
		$this->_db('iwide_r1')->where ( array (
			'inter_id' => $inter_id,
			'sale' => $saler_qrcode_id,
			'create_time >= ' =>$begin_time,
			'create_time < ' => $end_time
		) );
		return $this->_db('iwide_r1')->get( self::TAB_OKPAY_ORDERS )->result_array();
	}

	/**
	 * 统计分销员的业绩
	 * apy_status 支付状态。支持统计不同类型
	 */
	public function get_saler_okpay_count($inter_id,$saler_qrcode_id,$pay_status,$begin_time="",$end_time=""){
		if(empty($begin_time)){
			$begin_time = 1;
		}
		if(empty($end_time)){
			$end_time = 9999999999;
		}

		$this->_db('iwide_r1')->select( 'sum(pay_money) as paymoney' );
		$this->_db('iwide_r1')->where ( array (
			'inter_id' => $inter_id,
			'sale' => $saler_qrcode_id,
			'pay_status'=>$pay_status,
			'create_time >= ' =>$begin_time,
			'create_time < ' => $end_time
		) );
		return $this->_db('iwide_r1')->get( self::TAB_OKPAY_ORDERS )->row_array();
	}

	/**
	 * @param $inter_id
	 * @param $saler_qrcode_id
	 * @param $pay_status
	 * @param string $begin_time
	 * @param string $end_time
	 * @return mixed
	 * 交易次数
	 */
	public function get_saler_okpay_times($inter_id,$saler_qrcode_id,$begin_time="",$end_time=""){
		if(empty($begin_time)){
			$begin_time = 1;
		}
		if(empty($end_time)){
			$end_time = 9999999999;
		}

		$this->_db('iwide_r1')->select( 'count(id) as paytimes' );
		$this->_db('iwide_r1')->where ( array (
			'inter_id' => $inter_id,
			'sale' => $saler_qrcode_id,
			'create_time >= ' =>$begin_time,
			'create_time < ' => $end_time
		) );
		return $this->_db('iwide_r1')->get( self::TAB_OKPAY_ORDERS )->row_array();
	}

	/**
	 * 获取一周中，每天使用快乐付支付的次数
	 */
	public function get_okpay_used_count_by_week(){
		$date = date("y-m-d",time());
		$today_begin	= intval(strtotime($date));
		$today_end		= $today_begin+86400;

		$sql = "";
		for($i=0; $i<7; $i++){
			$sql .= "select count(id) as cnt,{$today_begin} as dt_time from iwide_okpay_orders as o where o.create_time>={$today_begin} and o.create_time<{$today_end} union ";
			$today_end = $today_begin;
			$today_begin = $today_begin - 86400;
		}
		if(!empty($sql)){
			$sql = substr($sql, 0,(strlen($sql)-6));
		}
		return $this->_db('iwide_r1')->query($sql)->result();
	}

	/**
	 * 获取一周中，每天使用快乐付的用户数
	 */
	public function get_okpay_used_user_count_by_week(){
		$date = date("y-m-d",time());
		$today_begin	= intval(strtotime($date));
		$today_end		= $today_begin+86400;

		$sql = "";
		for($i=0; $i<7; $i++){
			$sql .= "select count(tp.oid) as cnt,coalesce(tp.dt,{$today_begin}) as dt_time from (select count(openid) as oid,{$today_begin} as dt from iwide_okpay_orders as o where o.create_time>={$today_begin} and o.create_time<{$today_end} group by openid)  as tp union ";
			$today_end = $today_begin;
			$today_begin = $today_begin - 86400;
		}
		if(!empty($sql)){
			$sql = substr($sql, 0,(strlen($sql)-6));
		}
		return $this->_db('iwide_r1')->query($sql)->result();
	}

	/**
	 * @param $inter_id
	 * @param $sale
	 * @param $oid
	 * @return 分销员根据自己的资料 读取对应订单详情
	 */
	public function get_saler_okpay_order_detail($inter_id,$sale,$oid){
		$this->_db('iwide_r1')->select( '*' );
		$this->_db('iwide_r1')->where ( array (
			'out_trade_no' => $oid,
			'sale'   => $sale,
			'inter_id'=>$inter_id

		) );
		return $this->_db('iwide_r1')->get( self::TAB_OKPAY_ORDERS )->row_array();
	}

	public function get_fans_info($openid){
		$this->_db('iwide_r1')->select( 'nickname' );
		$this->_db('iwide_r1')->where ( array (
			'openid' => $openid

		) );
		return $this->_db('iwide_r1')->get("fans")->row_array();
	}

	public function get_order_info($inter_id,$id){
		$this->_db('iwide_rw')->select( '*' );
		$this->_db('iwide_rw')->where ( array (
			'id' => $id,
			'inter_id'=>$inter_id
		) );
		return $this->_db('iwide_rw')->get(self::TAB_OKPAY_ORDERS)->row_array();
	}

	/**
	 * 读取快乐付订单列表
	 */
	public function get_okpay_orders_list($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select * from iwide_".self::TAB_OKPAY_ORDERS." as ord where ord.inter_id ='{$inter_id}' ";
		if(!empty($params['entiti_hotel_id'])){//array()
			$sql .= " and hotel_id in ('".implode("','",$params['entiti_hotel_id'])."')";
		}
		//酒店名称
		if(!empty($params['hotel_name'])){
			$sql = $sql." and ord.hotel_name like '%".$params['hotel_name']."%' ";
		}
		//支付开始时间
		if(!empty($params['pay_begin_time'])){
			$sql = $sql." and ord.create_time >='".strtotime($params['pay_begin_time'])."' ";
		}
		//支付结束时间
		if(!empty($params['pay_end_time'])){
			//结束到23:59:59
			$params['pay_end_time'] .= " 23:59:59";
			$sql = $sql." and ord.create_time <'".strtotime($params['pay_end_time'])."' ";
		}
		//订单号
		if(!empty($params['out_trade_no'])){
			$sql = $sql." and ord.out_trade_no ='".$params['out_trade_no']."' ";
		}
		//交易场景
		if(!empty($params['pay_type'])){
			$sql = $sql." and ord.pay_type ='".$params['pay_type']."' ";
		}
		if(!empty($params['pay_status'])){
			$sql = $sql." and ord.pay_status ='".$params['pay_status']."' ";
		}
		$sql = $sql." order by ord.id desc  ";
//echo $sql;
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = intval($limit);
		}
		//$query = $this->_db('iwide_r1')->query($sql,$argvs);
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		return $query;
	}

	/**
	 * 读取符合条件的快乐付订单 总数
	 */
	public function get_okpay_orders_list_count($params){
		$inter_id = $params['inter_id'];
		$sql = "select count(ord.id) as nums from iwide_".self::TAB_OKPAY_ORDERS." as ord where ord.inter_id ='{$inter_id}' ";

		if(!empty($params['entiti_hotel_id'])){//array()
			$sql .= " and hotel_id in ('".implode("','",$params['entiti_hotel_id'])."')";
		}
		//酒店名称
		if(!empty($params['hotel_name'])){
			$sql = $sql." and ord.hotel_name like '%".$params['hotel_name']."%' ";
		}
		//支付开始时间
		if(!empty($params['pay_begin_time'])){
			$sql = $sql." and ord.create_time >='".strtotime($params['pay_begin_time'])."' ";
		}
		//支付结束时间
		if(!empty($params['pay_end_time'])){
			//结束到23:59:59
			$params['pay_end_time'] .= " 23:59:59";
			$sql = $sql." and ord.create_time <'".strtotime($params['pay_end_time'])."' ";
		}
		//订单号
		if(!empty($params['out_trade_no'])){
			$sql = $sql." and ord.out_trade_no ='".$params['out_trade_no']."' ";
		}
		//交易场景
		if(!empty($params['pay_type'])){
			$sql = $sql." and ord.pay_type ='".$params['pay_type']."' ";
		}
		if(!empty($params['pay_status'])){
			$sql = $sql." and ord.pay_status ='".$params['pay_status']."' ";
		}
		$sql = $sql." order by ord.id desc  ";

		//$query = $this->_db('iwide_r1')->query($sql)->row();
		$query = $this->_db('iwide_r1')->query($sql)->row();
		return $query->nums;
	}

	/**
	 * 获取 指定公众号 酒店的 场景列表
	 * @param string $inter_id
	 * @param string $hotel_id
	 * @param Int $status
	 */
	function get_hotel_okpay_type_list($inter_id,$hotel_id = array(),$status = 1){
		$sql = "select id,name from iwide_okpay_type where inter_id = '{$inter_id}' and status = {$status} ";
		/*if(!empty($hotel_id)){
			$sql .= " and hotel_id in ('".implode("','",$hotel_id)."') ";
		}*/
		$sql .= " order by id desc";
		$type = $this->_db('iwide_r1')->query($sql)->result_array();
		//$this->db->last_query();
		return empty($type)?array():$type;
	}

	//余额快乐付 直接生成订单并扣除用户余额
	public function create_okpay_by_banlance($arr){
		$arr['create_time'] = time();
		$arr['update_time'] = time();
		$arr['pay_time'] = time();
		$arr['status']		= 1;
		$arr['pay_status']	= 3;//支付成功
		$arr['pay_way']	= 2;//余额支付
		try {
			// $this->db->trans_begin();//开启事务
			//扣除用户余额
			$this->load->model('hotel/Member_model');
			if($this->Member_model->reduce_balance($arr['inter_id'], $arr['openid'], $arr['pay_money'], $arr['out_trade_no'], '快乐付订单余额支付',array('module'=>'okpay'))){
				//插入订单
				$this->db->insert(self::TAB_OKPAY_ORDERS,$arr);
				$insert_id = $this->db->insert_id();

				if($insert_id){
					//添加模板消息 stgc 20161107
					if(3 == $arr['pay_status']){
						$this->load->model ( 'plugins/Template_msg_model' );
						//发送给用户
						$res = $this->Template_msg_model->send_okpay_success_msg ( $arr, 'okpay_order_success' );
						//发送给管理员 先查一次授权的管理员
						$this->load->model('okpay/okpay_type_model');
						$admins = $this->okpay_type_model->get_type_saler_info($arr['inter_id'],$arr['pay_type']);
						if(!empty($admins)){
							foreach($admins as $k=>$v){
								$arr['openid'] = $v['openid'];
								$res  = $this->Template_msg_model->send_okpay_success_msg ( $arr, 'okpay_order_notice' );
							}
						}
                        //添加打印订单操作 situguanchen 2017-03-20
                        $this->load->model ( 'plugins/Print_model' );
                        $res  = $this->Print_model->print_okpay_order ($arr,'okpay_pay_success');
					}
					return true;
				}else{
					return false;
				}
			}
			// $this->db->trans_commit();//提交
			return false;
		} catch (Exception $e) {
			// $this->db->trans_rollback();//回滚
			return false;
		}

	}


	//快乐付余额退款处理
	function balance_refund($inter_id = '',$openid = '',$refund_money = 0,$order_id = 0){
		try {
			// $this->db->trans_begin();//开启事务
			//扣除用户余额
			$this->load->model ( 'hotel/Member_new_model' );
			$res =  $this->Member_new_model->addBalance($inter_id,$openid,$order_id,$refund_money,'快乐付余额退款',array('module'=>'okpay'));//余额退款处理
			return $res;
			// $this->db->trans_commit();//提交
			//return false;
		} catch (Exception $e) {
			// $this->db->trans_rollback();//回滚
			return false;
		}
	}

	//获取快乐付数据分析
	public function get_analysis_data($day = 0,$inter_id = ''){
		$return = array();
		if($day == 1){//前天
			$start = strtotime(date('Y-m-d',strtotime('-2 days')));
			$end = strtotime(date('Y-m-d 23:59:59',strtotime('-2 days')));
		}else{
			//昨天
			$start = strtotime(date('Y-m-d',strtotime('-1 days')));
			$end = strtotime(date('Y-m-d 23:59:59',strtotime('-1 days')));
		}
		$sql = "select count(distinct(openid)) all_mem,count(id) all_order,sum(pay_money) trade_money,sum(if(money != pay_money,1,0))discount_order,sum(if(money != pay_money,discount_money,0)) discount_money,sum(if(pay_status = 3,1,0)) success_order,sum(if(pay_status = 3,pay_money,0)) success_money,sum(if(pay_status = 4,1,0)) cancel_order,sum(if(pay_status = 4,pay_money,0)) cancel_money from iwide_okpay_orders where  create_time >= {$start} and create_time < {$end}";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$res = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return = isset($res[0])?$res[0]:array();
		//获取平均完成时间
		$sql = "select create_time,pay_time from iwide_okpay_orders where pay_status = 3 and create_time >= {$start} and create_time < {$end}";
		if(!empty($inter_id)){
			$sql .=  " and inter_id = '{$inter_id}'";
		}
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$time = $i = $sum = 0;
		if(!empty($query)){
			foreach($query as $k=>$v){
				if(isset($v['pay_time']) && isset($v['create_time'])){
					$sum += $v['pay_time'] - $v['create_time'];
					$i++;
				}
			}
		}
		$time = $i==0?0:$sum/$i;
		$return['avg_time'] = $time;
		//获取参与酒店总数
		$sql = "select count(*) num from (select count(id) id from iwide_okpay_type where create_time < {$end} group by inter_id) as a";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$return['all_public'] = isset($query->num)?$query->num:0;
		//获取产生交易酒店总数
		$sql = "select count(*) c from (select count(*) ddc from iwide_okpay_orders where pay_status = 3 and create_time < {$end} ";
		/* if(!empty($inter_id)){
             $sql .= " and inter_id = '{$inter_id}' ";
         }*/
		$sql .= " group by inter_id) a";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$return['all_pay_public'] = isset($query->c)?$query->c:0;
		//交易场景占比
		$sql = "select pay_type_desc,count(*) c from iwide_okpay_orders where pay_status = 3 and create_time >= {$start} and create_time < {$end} ";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$sql .= " group by pay_type_desc having count(*) > 5 order by count(*) desc limit 15";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return['type_count'] = $query;
		//交易金额占比
		$sql = "select pay_type_desc,sum(pay_money) sum_money from iwide_okpay_orders where pay_status = 3 and create_time >= {$start} and create_time < {$end}";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$sql .= " group by pay_type_desc order by sum_money desc limit 15";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return['type_money'] = $query;
		return $return;
	}

	//获取线性数据
	public function get_ajax_data($inter_id = '',$date = 0){
		ini_set('memory_limit', '512M');
		if($date == 7 ){
			$start = strtotime(date('Y-m-d',strtotime('-7 days')));
		}elseif($date == 30){
			$start = strtotime(date('Y-m-d',strtotime('-30 days')));
		}
		$sql = "select id,pay_money,create_time from iwide_okpay_orders where pay_status = 3 and create_time >= {$start}";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$res = array('amount'=>array(),'count'=>array(),'date'=>array());

		for($date;$date > 0;$date--){
			$res['date'][] = date('Y-m-d', strtotime("-{$date} days"));;
			$stime = strtotime(date('Y-m-d',strtotime("-{$date} days")));
			$etime = strtotime(date('Y-m-d 23:59:59',strtotime("-{$date} days")));
			$amount = $count = 0;
			if(!empty($query)){
				foreach($query as $k=>$v){
					if($v['create_time'] >= $stime && $v['create_time'] < $etime){
						$amount += $v['pay_money'];
						$count++;
					}
				}
			}
			$res['amount'][] = $amount;
			$res['count'][] = $count;
		}
		return $res;
	}

	//获取线性数据
	public function get_ajax_data_in_day($inter_id = '',$date = 0){
		ini_set('memory_limit', '512M');
		if($date == 0 ){//今天
			$start = strtotime(date('Y-m-d 23:59:59'));
			$end = strtotime(date('Y-m-d 00:00:00'));
		}elseif($date == 1){//昨天
			$start = strtotime(date('Y-m-d',strtotime('-1 days')));
			$end = strtotime(date('Y-m-d 23:59:59',strtotime('-1 days')));
		}
		$sql = "select id,pay_money,create_time from iwide_okpay_orders where pay_status = 3 and create_time >= {$start} and create_time < {$end}  and inter_id = '{$inter_id}'";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$res = array('amount'=>array(),'count'=>array(),'date'=>array());

		for($i = 0;$i < 24;$i++){
			$i = str_pad($i,2,'0',STR_PAD_LEFT);
			$res['date'][] = $i.":00";
			if($date == 0){//今天
				$stime = strtotime(date("Y-m-d {$i}:00:00"));
				$etime = strtotime(date("Y-m-d {$i}:59:59"));
			}else{//昨天
				$stime = strtotime(date("Y-m-d {$i}:00:00",strtotime('-1 days')));
				$etime = strtotime(date("Y-m-d {$i}:59:59",strtotime('-1 days')));
			}
			$amount = $count = 0;
			if(!empty($query)){
				foreach($query as $k=>$v){
					if($v['create_time'] >= $stime && $v['create_time'] < $etime){
						$amount += $v['pay_money'];
						$count++;
					}
				}
			}
			$res['amount'][] = $amount;
			$res['count'][] = $count;
		}
		return $res;
	}

	//获取快乐付数据分析
	public function get_saler_data($day = 0,$inter_id = ''){
		$return = array();
		if($day == 1){//前天
			$start = strtotime(date('Y-m-d',strtotime('-2 days')));
			$end = strtotime(date('Y-m-d 23:59:59',strtotime('-2 days')));
		}else{
			//昨天
			$start = strtotime(date('Y-m-d'));
			$end = strtotime(date('Y-m-d 23:59:59'));
		}
		$sql = "select sum(if(money != pay_money,1,0))discount_order,sum(if(money != pay_money,discount_money,0)) discount_money,sum(if(pay_status = 3,1,0)) success_order,sum(if(pay_status = 3,pay_money,0)) success_money,sum(if(pay_status = 1,1,0)) paying_order,sum(if(pay_status = 1,pay_money,0)) paying_money from iwide_okpay_orders where  create_time >= {$start} and create_time < {$end}";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$res = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return = isset($res[0])?$res[0]:array();

		//获取参与门店总数
		$sql = "select count(*) num from (select count(id) id from iwide_okpay_type where inter_id = '{$inter_id}' group by hotel_id) as a";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$return['hotel_count'] = isset($query->num)?$query->num:0;
		//获取产生交易门店总数
		$sql = "select count(*) c from (select count(*) ddc from iwide_okpay_orders where pay_status = 3 and inter_id = '{$inter_id}'  ";
		/* if(!empty($inter_id)){
             $sql .= " and inter_id = '{$inter_id}' ";
         }*/
		$sql .= " group by hotel_id) a";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$return['pay_hotel_count'] = isset($query->c)?$query->c:0;
		//交易场景占比
		$sql = "select pay_type_desc,count(*) c from iwide_okpay_orders where pay_status = 3 and create_time >= {$start} and create_time < {$end} ";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$sql .= " group by pay_type_desc having count(*) > 1 order by count(*) desc limit 15";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return['type_count'] = $query;
		//交易金额占比
		$sql = "select pay_type_desc,sum(pay_money) sum_money from iwide_okpay_orders where pay_status = 3 and create_time >= {$start} and create_time < {$end}";
		if(!empty($inter_id)){
			$sql .= " and inter_id = '{$inter_id}'";
		}
		$sql .= " group by pay_type_desc order by sum_money desc limit 15";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return['type_money'] = $query;
		return $return;
	}

	//获取数据分析详情
	public function get_data_by_filter($filter = array()){
		//默认昨天
		$start = strtotime(date('Y-m-d',strtotime('-1 days')));
		$end = strtotime(date('Y-m-d 23:59:59',strtotime('-1 days')));

		if(!empty($filter['start_time'])){
			$start = strtotime($filter['start_time']." 00:00:00");
		}
		if(!empty($filter['end_time'])){
			$end = strtotime($filter['end_time']." 23:59:59");
		}
		$sql = "select inter_id,count(distinct(openid)) all_mem,count(id) all_order,sum(pay_money) trade_money,sum(if(money != pay_money,1,0))discount_order,sum(if(money != pay_money,discount_money,0)) discount_money,sum(if(pay_status = 3,1,0)) success_order,sum(if(pay_status = 3,pay_money,0)) success_money,sum(if(pay_status = 4,1,0)) cancel_order,sum(if(pay_status = 4,pay_money,0)) cancel_money from iwide_okpay_orders where  create_time >= {$start} and create_time < {$end}";
		$type_sql = "select count(*) c,inter_id from iwide_okpay_type where create_time >= {$start} and create_time < {$end} and status = 1";
		if(!empty($filter['inter_id'])){
			$sql .= " and inter_id = '{$filter['inter_id']}'";
			$type_sql .= " and inter_id = '{$filter['inter_id']}'";
		}
		if(!empty($filter['hotel_public'])){
			$sql .= " and inter_id in ('" . implode("','",$filter['hotel_public']) . "')";
			$type_sql .= " and inter_id in ('" . implode("','",$filter['hotel_public']) . "')";
		}
		$sql .= " group by inter_id";
		$type_sql .= " group by inter_id";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$type = $this->_db ( 'iwide_r1' )->query($type_sql)->result_array();
		$arr = array();
		if(!empty($type)){
			foreach($type as $k=>$v){
				$arr[$v['inter_id']] = $v['c'];
			}
		}
		if(!empty($query)){
			foreach($query as $kk=>$vv){
				$query[$kk]['type'] = isset($arr[$vv['inter_id']])?$arr[$vv['inter_id']] : 0;
			}
		}
		return $query;
	}
}
