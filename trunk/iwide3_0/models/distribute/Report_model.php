<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends MY_Model {
	public function get_resource_name()
	{
		return '分销报表';
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
		return 'hotel_orders';
	}
	
	public function table_primary_key()
	{
		return 'id';
	}
	
	public function date_difference($date_1 , $date_2 , $difference_format = '%a' ){
		$datetime1 = date_create ( $date_1 );
		$datetime2 = date_create ( $date_2 );
		
		$interval = date_diff ( $datetime1, $datetime2 );
		
		return $interval->format ( $difference_format );
	}
	
	public function get_orders($limit = NULL,$offset = 0,$type = NULL,$begin_time = '',$end_time = ''){
		/**
		 * 获取本管理员的酒店权限
		 */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;
		$inter_id = '';
		if ($this->_admin_inter_id != FULL_ACCESS)
			$inter_id = $this->_admin_inter_id;
// 		else if ($this->_admin_inter_id)
// 			$filter = array ('inter_id' => $this->_admin_inter_id );
// 		if (is_array ( $filter )) {
// 			$this->load->model ( 'wx/publics_model' );
// 			$publics = $this->publics_model->get_public_hash ( $filter );
// 			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
// 			// $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
// 		}
		
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = '';
		else if ($this->_admin_hotels)
			$filterH = array ('hotel_id' => $this->_admin_hotels);
		else
			$filterH = '';
		$this->load->model ( 'hotel/hotel_model' );
		$filterH['inter_id'] = $inter_id;
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$hotels = $hotels + array ('0' => '-不限定-' );

		$order_query = "SELECT oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,
				oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid
				FROM iwide_hotel_orders o ";
		$order_count_query = "SELECT COUNT(*) nums FROM iwide_hotel_orders o ";
		//		if(!empty($begin_time)){
		//			$order_query .= " AND order_time >= ".strtotime($begin_time)." ";
		//			$order_count_query .= " AND order_time >= ".strtotime($begin_time)." ";
		//		}
		//		if(!empty($end_time)){
		//			$order_query .= " AND order_time <= ".strtotime($end_time)." ";
		//			$order_count_query .= " AND order_time <= ".strtotime($end_time)." ";
		//		}
		
		
		$order_query .= " INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id LEFT JOIN iwide_hotel_order_items oi
			ON o.orderid=oi.orderid WHERE oi.istatus=3";
		$order_count_query .= " INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id LEFT JOIN iwide_hotel_order_items oi
			ON o.orderid=oi.orderid WHERE oi.istatus=3";
		
		if(!empty($inter_id)){
			if(is_array($inter_id)){
				$order_query .= " AND o.inter_id IN ('".implode("','", $inter_id)."') ";
				$order_count_query .= " AND o.inter_id IN ('".implode("','", $inter_id)."') ";
			}else{
				$order_query .= " AND o.inter_id ='$inter_id' ";
				$order_count_query .= " AND o.inter_id ='$inter_id' ";
			}
		}
		if(!empty($filterH)){
			if(is_array($filterH)){
				$order_query .= " AND o.hotel_id IN (".implode(",", $filterH['hotel_id']).") ";
				$order_count_query .= " AND o.hotel_id IN (".implode(",", $filterH['hotel_id']).") ";
			}else{
				$order_query .= " AND o.hotel_id =$filterH ";
				$order_count_query .= " AND o.hotel_id =$filterH ";
			}
		}
		if(!empty($begin_time)){
			$beginTime=str_replace('-','',$begin_time);
			$beginTime=intval($beginTime);
			$order_query .= " AND o.enddate >= ".($beginTime)." ";
			$order_count_query .= " AND o.enddate >= ".($beginTime)." ";
		}
		if(!empty($end_time)){
			$endDate=str_replace('-','',$end_time);
			$endDate=intval($endDate);
			$order_query .= " AND o.enddate <= ".($endDate)." ";
			$order_count_query .= " AND o.enddate <= ".($endDate)." ";
		}
		$order_query .= " ORDER BY order_time DESC";
		if(!is_null($limit)){
			$order_query .= " limit $offset,$limit";
		}
		$orders = $this->_db('iwide_r1')->query($order_query)->result_array();
		
		
		
		//子订单的ID集合
		$grade_ids = array_column($orders, 'sid');
		$exts = $staffs = array();
		$orders_count_query = array('nums'=>0);
		if(count($grade_ids) > 0){
			$orders_count_query = $this->_db('iwide_r1')->query($order_count_query)->row_array();
			$order_ext_query = "SELECT ga.*,ge.hotel_name,ge.staff_name,ge.product 
					FROM (SELECT id,inter_id,saler,hotel_id,grade_openid,grade_id,grade_total,grade_time,`status`,deliver_batch,partner_trade_no,send_time 
					FROM `iwide_distribute_grade_all` WHERE ";
			if($type == 'hotels'){
				$order_ext_query .= " saler=-3 ";
			}else{
				$order_ext_query .= " saler>0 ";
			}
			$order_ext_query .= " AND grade_table='iwide_hotels_order' AND grade_id IN (".str_replace(',,',',',implode(',', $grade_ids)).")";
			$order_ext_query .= ") ga INNER JOIN iwide_distribute_grade_ext ge ON ge.grade_id=ga.id ORDER BY `ga`.`grade_id` DESC";
			
			$exts = $this->_db('iwide_r1')->query($order_ext_query)->result_array();
			$saler_ids = array_column($exts, 'saler');
			if(count($saler_ids) > 0){
				$this->_db('iwide_r1')->where(array('inter_id' => $inter_id));
				$this->_db('iwide_r1')->where_in('qrcode_id',$saler_ids);
				$staff_query = $this->_db('iwide_r1')->get('hotel_staff')->result_array();
				foreach ($staff_query as $item){
					$staffs[$item['qrcode_id']] = array('name'=>$item['name'],'dept'=>$item['master_dept'],'hotel'=>$item['hotel_name']);
				}
			}
		}
		$ext_array = array();
		foreach ($exts as $item){
			$ext_array[$item['grade_id']] = $item;
		}
		return array('hotels'=>$hotels,'orders'=>$orders,'count'=>$orders_count_query['nums'],'exts' => $ext_array,'staffs'=>$staffs);
	}
	
	public function get_orders_v1($limit = NULL,$offset = 0,$type = NULL,$begin_time = '',$end_time = ''){
		/**
		 * 获取本管理员的酒店权限
		 */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;
		$inter_id = '';
		if ($this->_admin_inter_id != FULL_ACCESS)
			$inter_id = $this->_admin_inter_id;
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = '';
			else if ($this->_admin_hotels)
				$filterH = array ('hotel_id' => $this->_admin_hotels);
			else
				$filterH = '';
		$this->load->model ( 'hotel/hotel_model' );
		$filterH['inter_id'] = $inter_id;
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$hotels = $hotels;
		
		$order_query = "SELECT oi.id oiid,o.id,o.hotel_id ohotel_id,o.openid,o.price,o.roomnums,o.name,o.order_time,o.orderid,o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,ga.*,ge.hotel_name,ge.staff_name,ge.product"; 
// 		if($type != 'hotels'){
			$order_query .= ",hs.`name` saler_name,hs.master_dept,hs.hotel_id saler_hotel_id,hs.hotel_name saler_hotel,hs.is_distributed ";
// 		}
	$order_query .= " FROM iwide_distribute_grade_all ga
	LEFT JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id AND ga.grade_table='iwide_hotels_order'
	LEFT JOIN iwide_hotel_order_items oi ON ga.inter_id=oi.inter_id AND ga.grade_id=oi.id
	LEFT JOIN iwide_hotel_orders o ON oi.inter_id=o.inter_id AND oi.orderid=o.orderid
	LEFT JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id";
// 		if($type != 'hotels'){
			$order_query .= " LEFT JOIN iwide_hotel_staff hs ON ga.inter_id=hs.inter_id AND ga.saler=hs.qrcode_id ";
// 		}
		$order_query .=" WHERE (ga.`status`=1 OR ga.`status`=2 OR ga.`status`=9) AND oi.istatus=3 ";
		$count_query = "SELECT COUNT(ga.id) nums FROM iwide_distribute_grade_all ga
	LEFT JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id AND ga.grade_table='iwide_hotels_order'
	LEFT JOIN iwide_hotel_order_items oi ON ga.inter_id=oi.inter_id AND ga.grade_id=oi.id
	LEFT JOIN iwide_hotel_orders o ON oi.inter_id=o.inter_id AND oi.orderid=o.orderid
	WHERE (ga.`status`=1 OR ga.`status`=2 OR ga.`status`=9) AND oi.istatus=3 ";
		$where = '';
		if(!empty($inter_id)){
			if(is_array($inter_id)){
				$where .= " AND ga.inter_id IN ('".implode("','", $inter_id)."') ";
			}else{
				$where .= " AND ga.inter_id ='$inter_id' ";
			}
		}
// 		if(!empty($filterH)){
// 			if(is_array($filterH)){
// 				$where .= " AND o.hotel_id IN (".implode(",", $filterH['hotel_id']).") ";
// 			}else{
// 				$where .= " AND o.hotel_id =$filterH ";
// 			}
// 		}
		if($type == 'hotels'){
			$where .= " AND ga.saler=-3 ";
		}else{
			$where .= "  AND ga.saler>0 ";
		}
		$params = array();
		if(!empty($begin_time)){
			$where .= " AND ga.grade_time >=? ";
			$params[] = $begin_time;
		}
		if(!empty($end_time)){
			$where .= " AND ga.grade_time <=? ";
			$params[] = $end_time.' 23:59:59';
		}
		$count = $this->_db('iwide_r1')->query($count_query.$where,$params)->row();
		$where .= " ORDER BY o.order_time DESC";
		if(!is_null($limit)){
			$where .= " limit $offset,$limit";
		}
		$query = $this->_db('iwide_r1')->query($order_query.$where,$params)->result_array();
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return array('hotels'=>$hotels,'orders'=>$query,'count'=>empty($count->nums) ? 0 : $count->nums);
	}
	
	function get_dis_room_order($limit = NULL, $offset = 0, $hotel_id = '', $order_id = '', $check_out = '', $saler_name = '', $saler_no = '') {
		/**
		 * 获取本管理员的酒店权限
		 */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;
		$inter_id = '';
		if ($this->_admin_inter_id != FULL_ACCESS)
			$inter_id = $this->_admin_inter_id;
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = '';
		else if ($this->_admin_hotels)
			$filterH = array ( 'hotel_id' => $this->_admin_hotels );
		else
			$filterH = '';
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $inter_id;
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$hotels = $hotels + array ( '0' => '-不限定-'  );
		
		$order_query = "SELECT oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,
				oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,m.mem_card_no,ma.membership_number
				FROM iwide_hotel_orders o ";
		$order_count_query = "SELECT COUNT(*) nums FROM iwide_hotel_orders o ";
		$order_query .= " INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id LEFT JOIN iwide_hotel_order_items oi
			ON o.orderid=oi.orderid LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid INNER JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id WHERE oi.istatus=3";
		$order_count_query .= " INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id LEFT JOIN iwide_hotel_order_items oi
			ON o.orderid=oi.orderid WHERE oi.istatus=3";
		
		if (! empty ( $inter_id )) {
			if (is_array ( $inter_id )) {
				$order_query .= " AND o.inter_id IN ('" . implode ( "','", $inter_id ) . "') ";
				$order_count_query .= " AND o.inter_id IN ('" . implode ( "','", $inter_id ) . "') ";
			} else {
				$order_query .= " AND o.inter_id ='$inter_id' ";
				$order_count_query .= " AND o.inter_id ='$inter_id' ";
			}
		}
		if(empty($hotel_id)){
			if (! empty ( $filterH )) {
				if (is_array ( $filterH )) {
					$order_query .= " AND o.hotel_id IN (" . implode ( ",", $filterH ['hotel_id'] ) . ") ";
					$order_count_query .= " AND o.hotel_id IN (" . implode ( ",", $filterH ['hotel_id'] ) . ") ";
				} else {
					$order_query .= " AND o.hotel_id =$filterH ";
					$order_count_query .= " AND o.hotel_id =$filterH ";
				}
			}
		}else{
			$order_query .= " AND o.hotel_id =$hotel_id ";
			$order_count_query .= " AND o.hotel_id =$hotel_id ";
		}
		if (! empty ( $order_id )) {
			$order_query .= " AND o.orderid LIKE %" . $order_id . "% ";
			$order_count_query .= " AND o.orderid LIKE %" . $order_id . "% ";
		}
		if (! empty ( $check_out )) {
			$endDate = str_replace ( '-', '', $end_time );
			$endDate = intval ( $endDate );
			$order_query .= " AND o.enddate = " . $check_out . " ";
			$order_count_query .= " AND o.enddate = " . $check_out . " ";
		}
		$order_query .= " GROUP BY oi.id ORDER BY order_time DESC";
		if (! is_null ( $limit )) {
			$order_query .= " limit $offset,$limit";
		}
		$orders = $this->_db('iwide_r1')->query ( $order_query )->result_array ();
		// 子订单的ID集合
		$grade_ids = array_column ( $orders, 'sid' );
		$exts = $staffs = array ();
		$orders_count_query = array (
				'nums' => 0 
		);
		if (count ( $grade_ids ) > 0) {
			$orders_count_query = $this->_db('iwide_r1')->query ( $order_count_query )->row_array ();
			$order_ext_query = "SELECT ga.*,ge.hotel_name,ge.staff_name,ge.product
					FROM (SELECT id,inter_id,saler,hotel_id,grade_openid,grade_id,grade_total,grade_time,`status`,deliver_batch,partner_trade_no,send_time
					FROM `iwide_distribute_grade_all` WHERE ";
			if ($type == 'hotels') {
				$order_ext_query .= " saler=-3 ";
			} else {
				$order_ext_query .= " saler>0 ";
			}
			$order_ext_query .= " AND grade_table='iwide_hotels_order' AND grade_id IN (" . str_replace ( ',,', ',', implode ( ',', $grade_ids ) ) . ")";
			$order_ext_query .= ") ga INNER JOIN iwide_distribute_grade_ext ge ON ge.grade_id=ga.id ORDER BY `ga`.`grade_id` DESC";
			
			$exts = $this->_db('iwide_r1')->query ( $order_ext_query )->result_array ();
			$saler_ids = array_column ( $exts, 'saler' );
			if (count ( $saler_ids ) > 0) {
				$this->_db('iwide_r1')->where ( array (
						'inter_id' => $inter_id 
				) );
				$this->_db('iwide_r1')->where_in ( 'qrcode_id', $saler_ids );
				$staff_query = $this->_db('iwide_r1')->get ( 'hotel_staff' )->result_array ();
				foreach ( $staff_query as $item ) {
					$staffs [$item ['qrcode_id']] = array (
							'name' => $item ['name'],
							'dept' => $item ['master_dept'],
							'hotel' => $item ['hotel_name'] 
					);
				}
			}
		}
		$ext_array = array ();
		foreach ( $exts as $item ) {
			$ext_array [$item ['grade_id']] = $item;
		}
		return array (
				'hotels' => $hotels,
				'orders' => $orders,
				'count' => $orders_count_query ['nums'],
				'exts' => $ext_array,
				'staffs' => $staffs 
		);
	}
	
	function get_dist_field_conf($inter_id='',$mode='',$admin_id=''){
		$admin_profile = $this->session->userdata('admin_profile');
		if(empty($inter_id)){
			$inter_id = $admin_profile['inter_id'];
		}
		if(empty($mode)){
			$mode = strtoupper($this->input->get('ctyp'));
		}
		if(empty($admin_id)){
			$admin_id = $admin_profile['admin_id'];
		}
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'conf_mode'=>$mode,'admin_id'=>$admin_id));
		$this->_db('iwide_r1')->limit(1);
		$rs = $this->_db('iwide_r1')->get('distribute_report_config');
		if($rs->num_rows() < 1){
			$this->_db('iwide_r1')->where(array('inter_id'=>'DEFAULT','conf_mode'=>$mode));
			$this->_db('iwide_r1')->limit(1);
			$rs = $this->_db('iwide_r1')->get('distribute_report_config');
		}
		if($rs->num_rows() < 1){
			return false;
		}
		$rs = $rs->row();
		return unserialize($rs->conf_val);
	}
	function save_dist_field_conf($save=false){
		$admin_profile = $this->session->userdata('admin_profile');
		$this->_db('iwide_r1')->where(array('inter_id'=>$admin_profile['inter_id'],'conf_mode'=>strtoupper($this->input->get('ctyp')),'admin_id'=>$admin_profile['admin_id']));
		$this->_db('iwide_r1')->limit(1);
		$rs = $this->_db('iwide_r1')->get('distribute_report_config');
		$is_update = true;
		if($rs->num_rows() < 1){
			$is_update = false;
			$this->_db('iwide_r1')->where(array('inter_id'=>'DEFAULT','conf_mode'=>strtoupper($this->input->get('ctyp'))));
			$this->_db('iwide_r1')->limit(1);
			$rs = $this->_db('iwide_r1')->get('distribute_report_config');
		}
		$rs = $rs->row();
		$rs = unserialize($rs->conf_val);
		// 订单管理使用，增加显示字段时兼容用
		if($save==true){
			$this->load->model('hotel/order_model');
			$field_lists = $this->order_model->list_fields ();
			$postf = array();
			foreach ($this->input->post() as $k => $val) {
				if(in_array($k, array_keys($field_lists))){
					$postf[] = $k;
				}
			}
			$keys = array_diff($postf,array_keys($rs));
			if(!empty($keys)){
				$confs = $this->report_model->init_orders_look(array(),true);
				foreach ($keys as $kk => $vk) {			
					if(in_array($vk, $confs['choose'])){
						$rs [$vk]['choose'] = 1;
					}else{
						$rs [$vk]['choose'] = 2;
					}
					if(in_array($vk, $confs['must'])){
						$rs [$vk]['must'] = 1;
					}else{
						$rs [$vk]['must'] = 2;
					}
					$rs [$vk]['name'] = $field_lists[$vk]['label'];
				}
			}
		}
		foreach ($rs as $item_key=>$item_val){
			if(array_key_exists($item_key, $this->input->post()) && $item_val['must'] == 2){
				$rs[$item_key]['choose'] = 1;
			}else if($item_val['must'] == 2){
				$rs[$item_key]['choose'] = 2;
			}
		}
		if($is_update){
			$this->_db('iwide_rw')->where(array('conf_mode'=>strtoupper($this->input->get('ctyp')),'admin_id'=>$admin_profile['admin_id'],'inter_id'=>$admin_profile['inter_id']));
			return $this->_db('iwide_rw')->update('distribute_report_config',array('conf_val'=>serialize($rs)));
		}else{
			$this->load->helper('guid');
			return $this->_db('iwide_rw')->insert('distribute_report_config',array('conf_mode'=>strtoupper($this->input->get('ctyp')),'admin_id'=>$admin_profile['admin_id'],'inter_id'=>$admin_profile['inter_id'],'id'=>Guid::toString(),'conf_val'=>serialize($rs)));
		}
	}
	
	//初始化订单管理显示设置
	function init_orders_look($fconfs,$conf=false,$save=false){
		$feild_configs = array();
		$choose = array(
			'member_no',
			'staff_info',
			'show_orderid',
			'hname_rname',
			'name',
			'tel',
			'startdate',
			'enddate',
			'roomnums',
			'order_datetime',
			'real_price',
			'paytype',
			'is_paid',
			'status',
			);
		$must = array(
			'show_orderid',
			'hname_rname',
			'name',
			'tel',
			'real_price',
			'paytype',
			'is_paid',
			'status',
			);
		if($conf==true){
			return array('choose'=>$choose,'must'=>$must);
		}
		foreach ($fconfs as $kf => $vf) {
			if(in_array($kf,$must)){
				$feild_configs[$kf]['must'] = 1;
			}else{
				$feild_configs[$kf]['must'] = 2;
			}
			if(in_array($kf,$choose)){
				$feild_configs[$kf]['choose'] = 1;				
			}else{
				$feild_configs[$kf]['choose'] = 2;
			}
			$feild_configs[$kf]['name'] = $vf['label'];
		}
		$this->load->helper('guid');
		if($save==true){
			$this->_db('iwide_rw')->where(array('conf_mode'=>strtoupper($this->input->get('ctyp')),'inter_id'=>'DEFAULT'));
			$ins = $this->_db('iwide_rw')->update('distribute_report_config',array('conf_val'=>serialize($feild_configs)));
		}else{
			$ins = $this->_db('iwide_rw')->insert('distribute_report_config',array('conf_mode'=>strtoupper($this->input->get('ctyp')),'inter_id'=>'DEFAULT','id'=>Guid::toString(),'conf_val'=>serialize($feild_configs)));
		}
		return $ins?$feild_configs:false;
	}

	function get_send_logs($params,$limit=NULL,$offset=0){
	if($this->input->get('debug') == 1){
		echo $this->_db('iwide_r1')->last_query();echo '<br />';
		echo '<p>'.microtime(TRUE).'</p>';
	}
		$sql = "SELECT oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id,o.price,o.roomnums,o.name,o.order_time,o.orderid, o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used, oi.id sid,
				oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,ga.inter_id,ga.saler,ga.grade_openid,ga.grade_table,ga.grade_id,ga.grade_id_name,ga.order_amount,ga.grade_total,ga.hotel_id saler_hotel,
				 ga.grade_amount,ga.grade_time,ga.status,ga.grade_amount_rate,ga.grade_rate_type,ga.remark,ga.deliver_batch, ga.last_update_time,ga.partner_trade_no,ga.send_time,ga.deliver_fail,ga.order_hotel,ga.order_status,ga.fans_hotel, 
				 ga.hotel_rate,ga.group_rate,ga.jfk_rate,ga.hotel_grades,ga.group_grades,ga.jfk_grades,hs.master_dept,m.mem_card_no,ma.membership_number 
				FROM `iwide_distribute_grade_all` ga FORCE INDEX(grade_time) 
				LEFT JOIN iwide_hotel_order_items oi ON ga.grade_id=oi.id
				LEFT JOIN iwide_hotel_staff hs ON ga.inter_id=hs.inter_id AND ga.saler=hs.qrcode_id 
				LEFT JOIN iwide_hotel_orders o ON o.inter_id=ga.inter_id AND o.orderid=oi.orderid
				LEFT JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id 
				LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid 
				LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id WHERE ga.grade_id=CONVERT(oi.id,CHARACTER) AND ga.grade_table='iwide_hotels_order' AND ga.inter_id=?";
				
		$argvs [] = $params['inter_id'];
		//添加酒店权限筛选
		if(!empty($params['entity_hotel_id'])){
			$sql .= " AND o.hotel_id in ('" .implode("','",$params['entity_hotel_id']) . "') ";
		}
		if(!empty($params['hotel_id'])){
			$sql .= " AND o.hotel_id=?";
			$argvs [] = $params['hotel_id'];
		}
		if(!empty($params['cout_date_begin'])){
			$sql .= " AND oi.enddate>=?";
			$argvs [] = $params['cout_date_begin'];
		}
		if(!empty($params['cout_date_end'])){
			$sql .= " AND oi.enddate<=?";
			$argvs [] = $params['cout_date_end'];
		}
		if(!empty($params['order_id'])){
			$sql .= " AND o.orderid LIKE ?";
			$argvs [] = '%'.$params['order_id'].'%';
		}
		$is_distributed = 1;
		if(isset($params['distribute'])){
			$is_distributed = $params['distribute'];
		}
		$sql .= " AND hs.is_distributed=?";
		$argvs [] = $is_distributed;
		if(!empty($params['saler_name'])){
			$sql .= " AND hs.`name` LIKE ?";
			$argvs [] = '%'.$params['saler_name'].'%';
		}
		//一级部门
		if(!empty($params['department']))
		{
			$sql .= " AND hs.master_dept = ? ";
			$argvs [] = $params['department'];
		}
		
		if(!empty($params['saler_no'])){
			$sql .= " AND ga.saler=?";
			$argvs [] = $params['saler_no'];
		}
		if(!empty($params['grade_date_begin'])){
			$sql .= " AND ga.grade_time>=?";
			$argvs [] = $params['grade_date_begin'];
		}
		if(!empty($params['grade_date_end'])){
			$sql .= " AND ga.grade_time<=?";
			$argvs [] = $params['grade_date_end'].' 23:59:59';
		}
		if(!empty($params['send_date_begin'])){
			$sql .= " AND ga.send_time>=?";
			$argvs [] = $params['send_date_begin'];
		}
		if(!empty($params['send_date_end'])){
			$sql .= " AND ga.send_time<=?";
			$argvs [] = $params['send_date_end'].' 23:59:59';
		}
		$sql .= " AND oi.istatus=3 AND  ga.saler>0 AND ga.status != 99 ORDER BY grade_time DESC";
        
//         $sql = "SELECT * FROM (SELECT oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,
//                   o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,
//                   oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,m.mem_card_no,ma.membership_number
//                 FROM iwide_hotel_orders o
//                 INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id
//                 LEFT JOIN iwide_hotel_order_items oi ON o.orderid=oi.orderid
//                 LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid
//                 LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id

//                 WHERE oi.istatus=3 AND o.inter_id=?";
// 		$argvs [] = $params['inter_id'];
// 		//添加酒店权限筛选
// 		if(!empty($params['entity_hotel_id'])){
// 			$sql .= " AND o.hotel_id in ('" .implode("','",$params['entity_hotel_id']) . "') ";
// 		}
// 		if(!empty($params['hotel_id'])){
// 			$sql .= " AND o.hotel_id=?";
// 			$argvs [] = $params['hotel_id'];
// 		}
// 		if(!empty($params['cout_date_begin'])){
// 			$sql .= " AND oi.enddate>=?";
// 			$argvs [] = $params['cout_date_begin'];
// 		}
// 		if(!empty($params['cout_date_end'])){
// 			$sql .= " AND oi.enddate<=?";
// 			$argvs [] = $params['cout_date_end'];
// 		}
// 		if(!empty($params['order_id'])){
// 			$sql .= " AND o.orderid LIKE ?";
// 			$argvs [] = '%'.$params['order_id'].'%';
// 		}
// 		$is_distributed = 1;
// 		if(isset($params['distribute'])){
// 			$is_distributed = $params['distribute'];
// 		}
		
// 		$sql .= " GROUP BY oi.id) orders INNER JOIN
// (SELECT ga.inter_id,ga.saler,ga.grade_openid,ga.grade_table,ga.grade_id,ga.grade_id_name,ga.order_amount,ga.grade_total,ga.hotel_id saler_hotel,
// 				ga.grade_amount,ga.grade_time,ga.status,ga.grade_amount_rate,ga.grade_rate_type,ga.remark,ga.deliver_batch,
// 				ga.last_update_time,ga.partner_trade_no,ga.send_time,ga.deliver_fail,ga.order_hotel,ga.order_status,ga.fans_hotel,
// 				ga.hotel_rate,ga.group_rate,ga.jfk_rate,ga.hotel_grades,ga.group_grades,ga.jfk_grades,ge.hotel_name,ge.staff_name,
// 				ge.product,hs.master_dept
// 				FROM iwide_distribute_grade_all ga
// 				LEFT JOIN iwide_hotel_staff hs ON hs.qrcode_id=ga.saler and hs.inter_id = ?
// INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.grade_table='iwide_hotels_order' AND ga.id=ge.grade_id
// WHERE ge.distribute=? AND ga.inter_id=? AND ga.saler>0 AND ga.status != 99";//期限外的绩效不统计 situguanchen 20170411
//         $argvs [] = $params['inter_id'];
// 		$argvs [] = $is_distributed;
// 		$argvs [] = $params['inter_id'];
// 		if(!empty($params['saler_name'])){
// 			$sql .= " AND ge.staff_name LIKE ?";
// 			$argvs [] = '%'.$params['saler_name'].'%';
// 		}
//         //一级部门
//         if(!empty($params['department']))
//         {
//             $sql .= " AND hs.master_dept = ?";
//             $argvs [] = $params['department'];
//         }

// 		if(!empty($params['saler_no'])){
// 			$sql .= " AND ga.saler=?";
// 			$argvs [] = $params['saler_no'];
// 		}
// 		if(!empty($params['grade_date_begin'])){
// 			$sql .= " AND ga.grade_time>=?";
// 			$argvs [] = $params['grade_date_begin'];
// 		}
// 		if(!empty($params['grade_date_end'])){
// 			$sql .= " AND ga.grade_time<=?";
// 			$argvs [] = $params['grade_date_end'].' 23:59:59';
// 		}
// 		if(!empty($params['send_date_begin'])){
// 			$sql .= " AND ga.send_time>=?";
// 			$argvs [] = $params['send_date_begin'];
// 		}
// 		if(!empty($params['send_date_end'])){
// 			$sql .= " AND ga.send_time<=?";
// 			$argvs [] = $params['send_date_end'].' 23:59:59';
// 		}
// 		$sql .=") grades ON orders.inter_id=grades.inter_id AND orders.oiid=grades.grade_id ORDER BY grades.grade_time DESC";
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
	echo '<p>'.microtime(TRUE).'</p>';
		}
		return $query;
	}
	
	
	function get_send_logs_count($params){
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
			echo '<p>'.microtime(TRUE).'</p>';
		}
		$sql = "SELECT COUNT(oi.id) nums FROM `iwide_distribute_grade_all` ga LEFT JOIN iwide_hotel_order_items oi ON ga.grade_id=oi.id LEFT JOIN iwide_hotel_staff hs ON ga.inter_id=hs.inter_id AND ga.saler=hs.qrcode_id LEFT JOIN iwide_hotel_orders o ON o.inter_id=ga.inter_id AND o.orderid=oi.orderid LEFT JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id WHERE ga.grade_id=CONVERT(oi.id,CHARACTER) AND ga.grade_table='iwide_hotels_order' AND ga.inter_id=? ";
		$argvs [] = $params['inter_id'];
		//添加酒店权限筛选
		if(!empty($params['entity_hotel_id'])){
			$sql .= " AND o.hotel_id in ('" .implode("','",$params['entity_hotel_id']) . "') ";
		}
		if(!empty($params['hotel_id'])){
			$sql .= " AND o.hotel_id=?";
			$argvs [] = $params['hotel_id'];
		}
		if(!empty($params['cout_date_begin'])){
			$sql .= " AND oi.enddate>=?";
			$argvs [] = $params['cout_date_begin'];
		}
		if(!empty($params['cout_date_end'])){
			$sql .= " AND oi.enddate<=?";
			$argvs [] = $params['cout_date_end'];
		}
		if(!empty($params['order_id'])){
			$sql .= " AND o.orderid LIKE ?";
			$argvs [] = '%'.$params['order_id'].'%';
		}
		
		$is_distributed = 1;
		if(isset($params['distribute'])){
			$is_distributed = $params['distribute'];
		}
		$sql .= " AND hs.is_distributed=? ";
		$argvs [] = $is_distributed;
		if(!empty($params['saler_name'])){
			$sql .= " AND hs.`name` LIKE ?";
			$argvs [] = '%'.$params['saler_name'].'%';
		}
		//一级部门
		if(!empty($params['department']))
		{
			$sql .= " AND hs.master_dept = ?";
			$argvs [] = $params['department'];
		}
		if(!empty($params['saler_no'])){
			$sql .= " AND ga.saler=?";
			$argvs [] = $params['saler_no'];
		}
		if(!empty($params['grade_date_begin'])){
			$sql .= " AND ga.grade_time>=?";
			$argvs [] = $params['grade_date_begin'];
		}
		if(!empty($params['grade_date_end'])){
			$sql .= " AND ga.grade_time<=?";
			$argvs [] = $params['grade_date_end'].' 23:59:59';
		}
		if(!empty($params['send_date_begin'])){
			$sql .= " AND ga.send_time>=?";
			$argvs [] = $params['send_date_begin'];
		}
		if(!empty($params['send_date_end'])){
			$sql .= " AND ga.send_time<=?";
			$argvs [] = $params['send_date_end'].' 23:59:59';
		}
		$sql .= " AND oi.istatus=3 AND ga.saler>0 AND ga.status != 99";
		
//             $sql = "SELECT count(oiid) nums FROM (SELECT oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,
//               o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,
//               oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,m.mem_card_no,ma.membership_number
//             FROM iwide_hotel_orders o
//             INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id
//             LEFT JOIN iwide_hotel_order_items oi ON o.orderid=oi.orderid
//             LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid
//             LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id
//             WHERE oi.istatus=3 AND o.inter_id=?";
// 		$argvs [] = $params['inter_id'];
// 		//添加酒店权限筛选
// 		if(!empty($params['entity_hotel_id'])){
// 			$sql .= " AND o.hotel_id in ('" .implode("','",$params['entity_hotel_id']) . "') ";
// 		}
// 		if(!empty($params['hotel_id'])){
// 			$sql .= " AND o.hotel_id=?";
// 			$argvs [] = $params['hotel_id'];
// 		}
// 		if(!empty($params['cout_date_begin'])){
// 			$sql .= " AND oi.enddate>=?";
// 			$argvs [] = $params['cout_date_begin'];
// 		}
// 		if(!empty($params['cout_date_end'])){
// 			$sql .= " AND oi.enddate<=?";
// 			$argvs [] = $params['cout_date_end'];
// 		}
// 		if(!empty($params['order_id'])){
// 			$sql .= " AND o.orderid LIKE ?";
// 			$argvs [] = '%'.$params['order_id'].'%';
// 		}

// 		$is_distributed = 1;
// 		if(isset($params['distribute'])){
// 			$is_distributed = $params['distribute'];
// 		}
// 		$sql .= " GROUP BY oi.id) orders INNER JOIN
// (SELECT ga.inter_id,ga.saler,ga.grade_openid,ga.grade_table,ga.grade_id,ga.grade_id_name,ga.order_amount,ga.grade_total,
// 				ga.grade_amount,ga.grade_time,ga.status,ga.grade_amount_rate,ga.grade_rate_type,ga.remark,ga.deliver_batch,
// 				ga.last_update_time,ga.partner_trade_no,ga.send_time,ga.deliver_fail,ga.order_hotel,ga.order_status,ga.fans_hotel,
// 				ga.hotel_rate,ga.group_rate,ga.jfk_rate,ga.hotel_grades,ga.group_grades,ga.jfk_grades,ge.hotel_name,ge.staff_name,
// 				ge.product FROM iwide_distribute_grade_all ga
// 				LEFT JOIN iwide_hotel_staff hs ON hs.qrcode_id=ga.saler and hs.inter_id = ?
//                 INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id AND ga.grade_table='iwide_hotels_order'
//                 WHERE ge.distribute=? AND ga.inter_id=? AND ga.saler>0 AND ga.status != 99";//期限外的绩效不统计 situguanchen 20170411
//         $argvs [] = $params['inter_id'];
// 		$argvs [] = $is_distributed;
// 		$argvs [] = $params['inter_id'];
// 		if(!empty($params['saler_name'])){
// 			$sql .= " AND ge.staff_name LIKE ?";
// 			$argvs [] = '%'.$params['saler_name'].'%';
// 		}
//         //一级部门
//         if(!empty($params['department']))
//         {
//             $sql .= " AND hs.master_dept = ?";
//             $argvs [] = $params['department'];
//         }
// 		if(!empty($params['saler_no'])){
// 			$sql .= " AND ga.saler=?";
// 			$argvs [] = $params['saler_no'];
// 		}
// 		if(!empty($params['grade_date_begin'])){
// 			$sql .= " AND ga.grade_time>=?";
// 			$argvs [] = $params['grade_date_begin'];
// 		}
// 		if(!empty($params['grade_date_end'])){
// 			$sql .= " AND ga.grade_time<=?";
// 			$argvs [] = $params['grade_date_end'].' 23:59:59';
// 		}
// 		if(!empty($params['send_date_begin'])){
// 			$sql .= " AND ga.send_time>=?";
// 			$argvs [] = $params['send_date_begin'];
// 		}
// 		if(!empty($params['send_date_end'])){
// 			$sql .= " AND ga.send_time<=?";
// 			$argvs [] = $params['send_date_end'].' 23:59:59';
// 		}
// 		$sql .=") grades ON orders.inter_id=grades.inter_id AND orders.oiid=grades.grade_id";
		
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
			echo '<p>'.microtime(TRUE).'</p>';
		}
		return $query->nums;
	}
	
	function get_send_logs_fas($params,$limit=NULL,$offset=0){
	
		// 		$this->load->model ( 'hotel/hotel_model' );
		// 		$filterH ['inter_id'] = $params['inter_id'];
		// 		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		// 		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
	
		$sql = "SELECT * FROM (SELECT oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,
				o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,
				oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,m.mem_card_no,ma.membership_number
				FROM iwide_hotel_orders o
INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id
LEFT JOIN iwide_hotel_order_items oi ON o.orderid=oi.orderid LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id WHERE oi.istatus=3 AND o.inter_id=?";
		$argvs [] = $params['inter_id'];
		if(!empty($params['hotel_id'])){
			$sql .= " AND o.hotel_id=?";
			$argvs [] = $params['hotel_id'];
		}
		if(!empty($params['cout_date_begin'])){
			$sql .= " AND oi.startdate>=?";
			$argvs [] = $params['cout_date_begin'];
		}
		if(!empty($params['cout_date_end'])){
			$sql .= " AND oi.enddate<=?";
			$argvs [] = $params['cout_date_end'];
		}
		if(!empty($params['order_id'])){
			$sql .= " AND o.orderid LIKE ?";
			$argvs [] = '%'.$params['order_id'].'%';
		}
	
		$sql .= " GROUP BY oi.id) orders INNER JOIN
(SELECT ga.inter_id,ga.saler,ga.grade_openid,ga.grade_table,ga.grade_id,ga.grade_id_name,ga.order_amount,ga.grade_total,
				ga.grade_amount,ga.grade_time,ga.status,ga.grade_amount_rate,ga.grade_rate_type,ga.remark,ga.deliver_batch,
				ga.last_update_time,ga.partner_trade_no,ga.send_time,ga.deliver_fail,ga.order_hotel,ga.order_status,ga.fans_hotel,
				ga.hotel_rate,ga.group_rate,ga.jfk_rate,ga.hotel_grades,ga.group_grades,ga.jfk_grades,ge.hotel_name,ge.staff_name,
				ge.product,f.nickname,f.subscribe_time FROM iwide_distribute_grade_all ga
INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id LEFT JOIN iwide_fans f ON f.inter_id=ga.inter_id AND f.openid=ga.grade_openid WHERE ga.inter_id=? AND ga.saler>0 ";
		$argvs [] = $params['inter_id'];
		if(!empty($params['saler_name'])){
			$sql .= " AND ge.staff_name LIKE ?";
			$argvs [] = '%'.$params['saler_name'].'%';
		}
		if(!empty($params['saler_no'])){
			$sql .= " AND ga.saler=?";
			$argvs [] = $params['saler_no'];
		}
		if(!empty($params['grade_date_begin'])){
			$sql .= " AND ga.grade_time>=?";
			$argvs [] = date('Y-m-d H:i:s',strtotime($params['grade_date_begin']));
		}
		if(!empty($params['grade_date_end'])){
			$sql .= " AND ga.grade_time<=?";
			$argvs [] = date('Y-m-d H:i:s',strtotime($params['grade_date_end']));
		}
	
		$sql .=") grades ON orders.inter_id=grades.inter_id AND orders.oiid=grades.grade_id ORDER BY grades.grade_time DESC";
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	
	
	/**
	 * 粉丝分销排行 按酒店
	 * @param unknown $params
	 * @param unknown $limit
	 * @param number $offset
	 * @return unknown
	 */
	function get_hotel_fas_report($params,$limit=NULL,$offset=0){
		
		$inter_id = $params['inter_id'];
		$begin_time = date('Y-m-d',strtotime($params['begin_time']));
		$end_time = date('Y-m-d 23:59:59',strtotime($params['end_time']));
	
		/* $sql = "select *,(select idc.excitation_type from iwide_distribute_config as idc where idc.excitation_category=1 and idc.inter_id='{$inter_id}') as gz,
(select count(1)+1 from 
(
select 
(select count(ifs.id) as cnt from iwide_fans_subs as ifs 
where ifs.inter_id='{$inter_id}' and ifs.hotel_id=ih.hotel_id 
and ifs.event=2 and ifs.event_time > '{$begin_time}' and ifs.event_time < '{$end_time}') as cnt
from iwide_hotels as ih where ih.inter_id='{$inter_id}' 
) as tmp2
 where tmp2.cnt>tmp.cnt) as pm,(select sum(idga.grade_total) as jx from iwide_distribute_grade_all as idga where idga.inter_id='a455510007' and idga.hotel_id=tmp.hotel_id ) as zjx  
from (
select ih.inter_id,ih.hotel_id,ih.name,
(select count(ifs.id) as cnt from iwide_fans_subs as ifs 
where ifs.inter_id='{$inter_id}' and ifs.hotel_id=ih.hotel_id 
and ifs.event=2 and ifs.event_time > '{$begin_time}' and ifs.event_time < '{$end_time}') as cnt
from iwide_hotels as ih where ih.inter_id='{$inter_id}' 
) as tmp order by pm"; */
		
		/*$sql = "select t.name,t.gz,t.cnt,t.zjx,(@tmp:=@tmp+1) as pm  from (
	select h.name,(select idc.excitation_type from iwide_distribute_config as idc where idc.excitation_category=1 and idc.inter_id='{$inter_id}') as gz,
	count(g.id) as cnt,
	sum(g.grade_total) as zjx,@tmp:=0
	from iwide_distribute_grade_all as g,iwide_hotels as h where g.hotel_id=h.hotel_id and g.inter_id=h.inter_id and g.inter_id='{$inter_id}' 
				and g.grade_table='iwide_fans_sub_log' and g.grade_time >= '{$begin_time}' and g.grade_time <= '{$end_time}' 
GROUP BY 
	g.inter_id,
	g.hotel_id
) as t order by t.cnt desc";*/
		$sql = "select t.name,t.gz,t.cnt,t.zjx,(@tmp:=@tmp+1) as pm from ( select h.name,(select idc.excitation_type from iwide_distribute_config as idc where idc.excitation_category=1 and idc.inter_id='{$inter_id}') as gz, cnt,zjx,@tmp:=0 from iwide_hotels h left join (select g.inter_id,g.hotel_id,count(*) as cnt,sum(g.grade_table) as zjx from iwide_distribute_grade_all as g where g.inter_id='{$inter_id}' and g.grade_table='iwide_fans_sub_log' and g.grade_time >= '{$begin_time}' and g.grade_time <= '{$end_time}' and g.hotel_id >0 group by g.hotel_id) b on b.inter_id = h.inter_id and b.hotel_id = h.hotel_id where h.inter_id = '{$inter_id}') as t order by t.cnt desc";
		
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		//echo $this->_db('iwide_rw')->last_query();
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	/**
	 * 粉丝分销排行 按酒店 总数
	 * @param unknown $params
	 * @return unknown
	 */
	function get_hotel_fas_report_count($params){
		$inter_id = $params['inter_id'];
		$begin_time = date('Y-m-d',strtotime($params['begin_time']));
		$end_time = date('Y-m-d 23:59:59',strtotime($params['end_time']));
	
		 $sql = "select count(ih.hotel_id) as nums
from iwide_hotels as ih where ih.inter_id='{$inter_id}'"; 
		/*$sql = "select count(t.name) as nums  from (
	select h.name,(select idc.excitation_type from iwide_distribute_config as idc where idc.excitation_category=1 and idc.inter_id='{$inter_id}') as gz,
	count(g.id) as cnt,
	sum(g.grade_total) as zjx 
	from iwide_distribute_grade_all as g,iwide_hotels as h where g.hotel_id=h.hotel_id and g.inter_id=h.inter_id and g.inter_id='{$inter_id}' 
				and g.grade_table='iwide_fans_sub_log' and g.grade_time >= '{$begin_time}' and g.grade_time <= '{$end_time}'  
GROUP BY
	g.inter_id,
	g.hotel_id
) as t";*/
	
		$argvs = array();
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		return $query->nums;
	}
	
	function get_hotel_order_summation($params,$limit=NULL,$offset=0) {
		ini_set('memory_limit','512M');
		$debug=intval($this->input->get('debug'));
		$inter_id=$params['inter_id'];
// 		$inter_id='a452223043';
		$params['grade_status']=empty($params['grade_status'])?'valid':$params['grade_status'];
// 			1 => '已核定－未发放',//交易成功（不论消费方式一律按7天核定奖励）、拼团成功（成团立即核定奖励）
// 			2 => '已核定－已发放',
// 			4 => '未核定－尚未离店',
// 			5 => '已核定－无绩效',//发生退款（立即撤销奖励）
// 			6 => '未核定-付款成功' //付款成功、开团成功（只奖励开团人订单）
		$selects=' grade_id,hotel_id,grade_total,saler,status ';
		$status_sql='';
		switch ($params['grade_status']){
			case 'valid':
				$status_sql.=' and status in (1,2) ';
				break;
			case 'all':
			default:
				break;
		}
		$hsql='';
		if (!empty($params['hotel_id']))
			$hsql.=" and hotel_id = ".$params['hotel_id'];
		$sql="SELECT $selects FROM `iwide_distribute_grade_all` WHERE `inter_id` = '$inter_id' and grade_table = 'iwide_hotels_order' ";
// 		$sql.=" and hotel_id is not NULL and hotel_id > 0 ";
		if (!empty($params['grade_date_begin']))
			$sql.=" and grade_time >= '".date('Y-m-d 00:00:00',strtotime($params['grade_date_begin']))."' ";
		if (!empty($params['grade_date_end']))
			$sql.=" and grade_time <= '".date('Y-m-d 23:59:59',strtotime($params['grade_date_end']))."' ";
		$sql.= $status_sql.$hsql;
// 		$sql.=' and id > 388600 ';
// 		echo $sql;
		$grades=$this->_db('iwide_r1')->query($sql)->result_array();
		
// 		$grades=$this->dis_all();
		$item_ids=array_column($grades, 'grade_id');
		$grade_data_get=array();
		$grade_data_send=array();
		foreach ($grades as $g){
			if ($g['saler']>0){
				$grade_data_get[$g['grade_id']]=$g;
			}else{
				$grade_data_send[$g['grade_id']][$g['saler']]=$g;
			}
		}
		unset($grades);
		
		$selects=' items.*,items.id iid,orders.paytype o_paytype,orders.hotel_id o_hotel_id,additions.web_orderid a_web_orderid,additions.coupon_favour a_coupon_favour,additions.coupon_used a_coupon_used,additions.point_used_amount a_point_used_amount,additions.point_used a_point_used  ';
		$sql="SELECT $selects FROM 
               (SELECT * FROM `iwide_hotel_order_items` where inter_id = '$inter_id' and id in ('".implode("','", $item_ids)."') ) items
		        JOIN (SELECT * FROM `iwide_hotel_orders` where inter_id = '$inter_id' ) orders 
		         ON items.inter_id=orders.inter_id and items.orderid=orders.orderid 
		          JOIN (SELECT * FROM `iwide_hotel_order_additions` where inter_id = '$inter_id' ) additions 
				   ON orders.inter_id=additions.inter_id and orders.orderid=additions.orderid";
		$order_data=$this->_db('iwide_r1')->query($sql)->result_array();
// 		$order_data=$this->hotel_orders();

		if ($this->input->get('nobug')==1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		
		$order_item_ids=array_column($order_data, 'orderid','iid');
		$order_ids=array();
		$orders=array();
		foreach ($order_data as $o){
			$order_ids[$o['orderid']]=1;
			$orders[$o['orderid']]['order_data']=$o;
			$orders[$o['orderid']]['order_details'][$o['iid']]=$o;
		}
		unset($order_data);
		$this->load->model('hotel/Hotel_model');		
		$hotels=$this->Hotel_model->get_all_hotels($inter_id,NULL,'key');

		$data=array();
		$default=array(
				'hotel_name'=>0,
				'total_order_count'=>0,//订单总量
				
				'balance_pay_order_count'=>0,//储值支付订单总量
				'weixin_pay_order_count'=>0,//微信支付订单总量
				'daofu_pay_order_count'=>0,//到店支付订单总量
				'bonus_pay_order_count'=>0,//积分支付订单总量
				'point_pay_order_count'=>0,//积分支付订单总量
				
				'coupon_used_order_count'=>0,//使用优惠券订单总量
				'bonus_used_order_count'=>0,//使用积分抵扣订单总量
				'total_bonus_order_count'=>0,//使用积分订单总量
				'total_point_order_count'=>0,//使用积分订单总量
				'total_room_night'=>0,//间夜数量
				
				'balance_pay_room_night'=>0,//储值支付间夜总量
				'weixin_pay_room_night'=>0,//微信支付间夜总量
				'daofu_pay_room_night'=>0,//到店支付间夜总量
				'bonus_pay_room_night'=>0,//积分支付间夜总量
				'point_pay_room_night'=>0,//积分支付间夜总量
				
				'coupon_used_room_night'=>0,//使用优惠券间夜总量
				'bonus_used_room_night'=>0,//使用积分抵扣间夜总量
				'total_bonus_room_night'=>0,//使用积分间夜总量
				'total_point_room_night'=>0,//使用积分间夜总量
				'total_order_money'=>0,//销售总额
				
				'balance_pay_order_money'=>0,//储值支付总额
				'weixin_pay_order_money'=>0,//微信支付总额
				'daofu_pay_order_money'=>0,//到店支付总额
				'bonus_pay_order_money'=>0,//使用积分支付的订单总额
				'point_pay_order_money'=>0,//使用积分支付的订单总额
				
				'total_bonus_pay_amount'=>0,//使用积分支付总额
				'total_point_pay_amount'=>0,//使用积分支付总额
				'total_coupon_used_money'=>0,//使用优惠券总额
				'total_bonus_used_amount'=>0,//使用积分抵扣总额
				'total_bonus_amount'=>0,//使用积分总额
// 				'total_receiving_grade'=>0,
				'total_receive_grade'=>0,//应收绩效总额
				'receive_grade_count'=>0,//应收绩效笔数
				'total_send_grade'=>0,//应付绩效总额
				'total_send_count'=>0,//应付绩效笔数
				'total_staff_send_grade'=>0,//应付员工绩效
				'total_hotel_send_grade'=>0,//应付酒店总绩效
				'total_grade'=>0,//绩效总金额
				'total_grade_staff_count'=>array(),//产生绩效员工数
// 				'grade_sort'=>0,
				'room_night_sort'=>0//间夜数排名
		);
		
		foreach ($hotels as $hotel_id=>$h){
			$data[$hotel_id]=$default;
			$data[$hotel_id]['hotel_name']=$h['name'];
		}

		foreach ($grade_data_get as $item_id=>$grade){
			if (isset($order_ids[$order_item_ids[$grade['grade_id']]])){
				$orderid=$order_item_ids[$grade['grade_id']];
				$hotel_id=$orders[$orderid]['order_data']['o_hotel_id'];
				$saler_hotel_id=$grade['hotel_id'];
				$tmp_room_night = get_room_night($orders[$orderid]['order_details'][$item_id]['startdate'],$orders[$orderid]['order_details'][$item_id]['enddate'],'round',$orders[$orderid]['order_details'][$item_id]);
				if ($order_ids[$orderid]==1){//计算订单数（同一单多个子单只能记一个）
					$data[$hotel_id]['total_order_count']++;
					$data[$hotel_id][$orders[$orderid]['order_data']['o_paytype'].'_pay_order_count']++;
					if($orders[$orderid]['order_data']['a_coupon_used']==1){
						$data[$hotel_id]['coupon_used_order_count']++;
					}
					$order_ids[$orderid]=0;
					if($orders[$orderid]['order_data']['a_coupon_used']==1){
						$data[$hotel_id]['total_coupon_used_money']+=$orders[$orderid]['order_data']['a_coupon_favour'];
					}
					if($orders[$orderid]['order_data']['a_point_used']==1){
						if ($orders[$orderid]['order_data']['o_paytype']=='bonus'){
							$data[$hotel_id]['total_bonus_pay_amount']+=$orders[$orderid]['order_data']['a_point_used_amount'];
						}else{
							$data[$hotel_id]['total_bonus_used_amount']+=$orders[$orderid]['order_data']['a_point_used_amount'];
							$data[$hotel_id]['bonus_used_order_count']++;
						}
						$data[$hotel_id]['total_bonus_amount']+=$orders[$orderid]['order_data']['a_point_used_amount'];
						$data[$hotel_id]['total_bonus_order_count']++;
					}
				}
				$data[$hotel_id]['total_room_night']+=$tmp_room_night;
				$data[$hotel_id][$orders[$orderid]['order_data']['o_paytype'].'_pay_room_night']+=$tmp_room_night;
				$data[$hotel_id][$orders[$orderid]['order_data']['o_paytype'].'_pay_order_money']+=$orders[$orderid]['order_details'][$item_id]['iprice'];
				if($orders[$orderid]['order_data']['a_coupon_used']==1){
					$data[$hotel_id]['coupon_used_room_night']+=$tmp_room_night;
				}
				$data[$hotel_id]['total_order_money']+=$orders[$orderid]['order_details'][$item_id]['iprice'];
				if($orders[$orderid]['order_data']['a_point_used']==1){
					if ($orders[$orderid]['order_data']['o_paytype']!='bonus'){
						$data[$hotel_id]['bonus_used_room_night']+=$tmp_room_night;
					}
					$data[$hotel_id]['total_bonus_room_night']+=$tmp_room_night;
				}
				if (!empty($grade_data_send[$item_id])){
					foreach ($grade_data_send[$item_id] as $grade_type=>$grade_else){
						switch ($grade_type){
							case -1://jfk绩效
								break;
							case -2://集团绩效
								break;
							case -3://酒店绩效
// 								if ($hotel_id==$saler_hotel_id){
// 									$data[$hotel_id]['total_receive_grade']+=$grade_else['grade_total'];
// 									$data[$hotel_id]['receive_grade_count']++;
// 								}else
								if ($hotel_id!=$saler_hotel_id&&!empty($saler_hotel_id)){
									$data[$saler_hotel_id]['total_receive_grade']+=$grade_else['grade_total'];
									$data[$saler_hotel_id]['receive_grade_count']++;
									$data[$hotel_id]['total_send_grade']+=$grade_else['grade_total'];
									$data[$hotel_id]['total_hotel_send_grade']+=$grade_else['grade_total'];
									$data[$hotel_id]['total_send_count']++;
								}
								break;
							default:
								break;
						}
					}
				}
				$data[$hotel_id]['total_send_grade']+=$grade['grade_total'];
				$data[$hotel_id]['total_send_count']++;
				$data[$hotel_id]['total_staff_send_grade']+=$grade['grade_total'];
				$data[$hotel_id]['total_grade_staff_count'][$grade['saler']]=1;
			}
		}
		foreach ($data as $hotel_id=>$d){
			$data[$hotel_id]['total_grade']=$d['total_receive_grade']-$d['total_send_grade'];
			$data[$hotel_id]['total_grade_staff_count']=count($data[$hotel_id]['total_grade_staff_count']);
		}
		uasort ( $data, function ($a, $b) {
			return $a['total_room_night'] > $b['total_room_night'] ? -1 : 1;
		} );
		$i=1;
		$tmp=array();
		foreach ($data as $hotel_id=>$d){
			if (!empty($d['hotel_name'])){
				if (empty($tmp)){
					$tmp=$d;
				}
				if($tmp['total_room_night']!=$d['total_room_night']){
					$i++;
				}
				$data[$hotel_id]['room_night_sort']=$i;
				$tmp=$d;
			}
		}
		return $data;
	}
	
	function get_hotel_distri_order($params,$limit=NULL,$offset=0,$xls=FALSE,$just_count=FALSE) {
		ini_set('memory_limit','512M');
		$inter_id=$params['inter_id'];
// 		$inter_id='a455510007';
		$selects=' grade.hotel_id grade_hotel_id,grade.grade_openid fans_openid,grade.saler grade_saler,grade.grade_time,grade.inter_id,grade.saler
				 ,orders.roomnums,orders.startdate,orders.enddate,orders.order_time,orders.paytype o_paytype,orders.hotel_id o_hotel_id,orders.orderid o_orderid,orders.member_no membership_number,orders.status order_status
				 ,items.id item_id,items.roomname,items.iprice,items.startdate istart,items.enddate iend,items.istatus item_status
				 ,additions.web_orderid web_orderid
				 ,member.level member_level';
		$where='';
		$para=array();
		if (!empty($params['order_status'])){
			if($params['order_status']=='left'){
				$where .=' and items.istatus = 3 ';
			}else if($params['order_status']=='book'){
				$where .=' and items.istatus in (0,1) ';
			}else {
				$where .=' and items.istatus in (0,1,3) ';
			}
		}
		if (!empty($params['hotel_id'])){
			$where .= ' and orders.hotel_id = ? ';
			$para[]=$params['hotel_id'];
		}
		if (!empty($params['orderid'])){
			$where .= ' and orders.orderid = ? ';
			$para[]=$params['orderid'];
		}
		if (!empty($params['web_orderid'])){
			$where .= ' and additions.web_orderid = ? ';
			$para[]=$params['web_orderid'];
		}
		if (!empty($params['order_time_start'])){
			$where .= ' and orders.order_time >= ? ';
			$para[]=strtotime($params['order_time_start']);
		}
		if (!empty($params['order_time_end'])){
			$where .= ' and orders.order_time <= ? ';
			$para[]=strtotime($params['order_time_end'])+86399;
		}
		if (!empty($params['start_date_start'])){
			$where .= ' and items.startdate >= ? ';
			$para[]=date('Ymd',strtotime($params['start_date_start']));
		}
		if (!empty($params['start_date_end'])){
			$where .= ' and items.startdate <= ? ';
			$para[]=date('Ymd',strtotime($params['start_date_end']));
		}
		if (!empty($params['end_date_start'])){
			$where .= ' and items.enddate >= ? ';
			$para[]=date('Ymd',strtotime($params['end_date_start']));
		}
		if (!empty($params['end_date_end'])){
			$where .= ' and items.enddate <= ? ';
			$para[]=date('Ymd',strtotime($params['end_date_end']));
		}
		$limit_s=' order by orders.id desc ';
		if (!is_null($limit)){
			$limit_s .= ' limit ?,? ';
			$para[]=$offset;
			$para[]=$limit;
		}
		$sql="SELECT a.*,staff.name saler_name FROM (SELECT  $selects
			   FROM iwide_distribute_grade_all grade 
				join `iwide_hotel_order_items` items JOIN `iwide_hotel_orders` orders 
				 ON items.inter_id=orders.inter_id and items.orderid=orders.orderid 
				  JOIN `iwide_hotel_order_additions` additions 
				    ON orders.inter_id=additions.inter_id and orders.orderid=additions.orderid and grade.inter_id=orders.inter_id and grade.grade_openid=orders.openid
				     LEFT JOIN `iwide_member` member       
				      ON member.inter_id=orders.inter_id  and member.openid=orders.openid
				       JOIN iwide_distribute_grade_all grade_c 
                        ON grade_c.saler = grade.saler and grade_c.inter_id = grade.inter_id
				     WHERE grade.`inter_id` = '$inter_id' AND grade.`grade_table` = 'iwide_fans_sub_log' and unix_timestamp(grade.grade_time) < orders.order_time 
				      and grade_c.grade_table = 'iwide_hotels_order' and grade_c.saler > 0 and grade_c.grade_id = items.id $where $limit_s ) a 
				      JOIN (SELECT name,inter_id,qrcode_id FROM iwide_hotel_staff where inter_id='$inter_id') staff on staff.inter_id=a.inter_id and staff.qrcode_id=a.saler";
		$order_data=$this->_db('iwide_r1')->query($sql,$para)->result_array();
		if ($just_count)
			return count($order_data);
// echo $this->_db('iwide_rw')->last_query();exit;
		$this->load->model('hotel/Hotel_model');		
		$hotels=$this->Hotel_model->get_all_hotels($inter_id,NULL,'key');

		$data=array();
		$default=array(
				'fans_openid'=>'',
				'member_level'=>'',
				'membership_number'=>'',
				'saler_name'=>'',
				'web_orderid'=>'',
				'in_hotel_name'=>'',
				'order_time'=>'',
				'startdate'=>'',
				'enddate'=>'',
				'days'=>'',
				'nums'=>'',
				'roomname'=>'',
				'room_night'=>'',
				'item_status'=>''
		);
		$this->load->model ( 'hotel/Member_model' );
		$levels = $this->Member_model->get_member_levels ( $inter_id );
		$this->load->model ( 'common/Enum_model' );
		$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS');
		foreach ($order_data as &$o){
			$o['in_hotel_name']=$hotels[$o['o_hotel_id']]['name'];
			$o['order_time']=date('Y-m-d H:i:s',$o['order_time']);
			$o['istart']=date('Y-m-d',strtotime($o['istart']));
			$o['iend']=date('Y-m-d',strtotime($o['iend']));
			$o['days']=get_room_night($o['istart'],$o['iend'],'ceil',$o);//至少有1个间夜
			$o['nums']=1;
			$o['member_level']=isset($levels[$o['member_level']])?$levels[$o['member_level']]:$o['member_level'];
			$o['item_status']=$status_des[$o['item_status']];
			$o['room_night']=$o['nums']*$o['days'];
			if ($xls){
				$o['o_orderid']='`'.$o['o_orderid'];
				if (!empty($o['web_orderid']))
					$o['web_orderid']='`'.$o['web_orderid'];
			}
		}

		return $order_data;
	}
	
	/**
	 * 粉丝分销排行  按订单~
	 * @param unknown $params
	 * @param unknown $limit
	 * @param number $offset
	 */
	function get_hotel_fas_report_by_order($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
	
		/*
		$sql = "select t1.*,t2.id as fas_id,t2.event_time,t2.nickname,t2.mem_card_no,t3.name,t3.hotel_name from
		(
		select idga.id,idga.inter_id,idga.grade_openid,idga.send_time,idga.grade_rate_type,idga.grade_amount_rate,
		idga.grade_total,idga.status,idga.saler,hotel_id 
		from iwide_distribute_grade_all as idga  where idga.hotel_id !='' and idga.saler!='' and idga.grade_table='iwide_fans_sub_log' and idga.inter_id='{$inter_id}'
		) as t1,
	
		(select tmp.*,tmp2.mem_card_no from (
		select ifs.id,ifs.event_time,
		ifs.source,ifs.openid,
		ifs.hotel_id,iwf.inter_id,iwf.nickname from iwide_fans_subs as ifs left join iwide_fans as iwf
		on ifs.openid=iwf.openid and ifs.inter_id=iwf.inter_id
		where ifs.inter_id='{$inter_id}') as tmp
		left join
		(select iwm.mem_card_no,iwm.openid,iwm.inter_id from iwide_member as iwm where iwm.inter_id='{$inter_id}') as tmp2
		on tmp2.inter_id=tmp.inter_id and tmp2.openid = tmp.openid ) as t2,
	
		(
		select ihs.name,ihs.hotel_id,ihs.inter_id,ihs.hotel_name,ihs.qrcode_id
		from iwide_hotel_staff as ihs where ihs.hotel_id !='' and ihs.qrcode_id != '' and ihs.inter_id='{$inter_id}'
		) as t3 where t1.saler=t3.qrcode_id and t1.saler=t2.source and t1.grade_openid = t2.openid ";
	*/
		// $sql = "select * from iwide_report_follow_grade as t where t.inter_id='{$inter_id}' ";
		$sql = "select a.id, a.inter_id, a.openid grade_openid, d.send_time, d.grade_rate_type, d.grade_amount_rate, d.grade_total, d.status, a.source saler, a.hotel_id, a.id as fas_id, a.event_time, b.nickname, e.mem_card_no, d.grade_time, c.name,c.hotel_name,c.master_dept FROM iwide_fans_subs a LEFT JOIN iwide_fans b ON a.openid = b.openid AND a.inter_id = b.inter_id left JOIN iwide_hotel_staff c ON a.inter_id = c.inter_id AND a.source = c.qrcode_id LEFT JOIN iwide_distribute_grade_all d ON a.inter_id = d.inter_id AND a.source = d.saler AND a.openid = d.grade_openid AND d.grade_table = 'iwide_fans_sub_log' left JOIN iwide_member e ON a.inter_id = e.inter_id AND a.openid = e.openid where a.source > 0 AND a.inter_id='{$inter_id}'";
		// if(!empty($params['hotel_id'])){
		// 	$sql = $sql." and t.hotel_id = ".$params['hotel_id'];
		// }
		// if(!empty($params['begin_time'])){
		// 	$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
		// 	$sql = $sql." and t.event_time >='".$begin_time."' ";
		// }
		// if(!empty($params['end_time'])){
		// 	$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
		// 	$sql = $sql." and t.event_time <='".$end_time."' ";
		// }
		// //粉丝编号
		// if(!empty($params['fans_id'])){
		// 	$sql = $sql." and t.fas_id =".intval($params['fans_id']);
		// }
		// //分销与名字
		// if(!empty($params['saler_name'])){
		// 	$sql = $sql." and t.name ='".intval($params['saler_name'])."' ";
		// }
		// //分销号
		// if(!empty($params['saler_no'])){
		// 	$sql = $sql." and t.saler =".intval($params['saler_no']);
		// }
	
		// $sql = $sql."  order by t.id desc ";
		//添加酒店权限控制判断
		if(!empty($params['entity_hotel_id'])){
			$sql = $sql." and a.hotel_id in ('".implode("','",$params['entity_hotel_id']) . "') ";
		}
		if(!empty($params['hotel_id'])){
			$sql = $sql." and a.hotel_id = ".$params['hotel_id'];
		}
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and a.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d 23:59:59',strtotime($params['end_time']));
			$sql = $sql." and a.event_time <='".$end_time."' ";
		}
		//粉丝编号
		if(!empty($params['fans_id'])){
			$sql = $sql." and a.id =".intval($params['fans_id']);
		}
		//分销与名字
		if(!empty($params['saler_name'])){
			$sql = $sql." and c.name LIKE '%".$params['saler_name']."%' ";
		}
		//部门
		if(!empty($params['dept'])){
			$sql = $sql." and c.master_dept LIKE '%".$params['dept']."%' ";
		}
		//分销号
		if(!empty($params['saler_no'])){
			$sql = $sql." and a.source =".intval($params['saler_no']);
		}
	
		$sql = $sql." GROUP BY a.openid order by a.id desc ";
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	
	}
	
	/**
	 * 粉丝分销排行  按订单~ 总数
	 */
	public function get_hotel_fas_report_by_order_count($params){
		$inter_id = $params['inter_id'];
	
		// $sql = "select count(t.id) as nums from iwide_report_follow_grade as t where t.inter_id='{$inter_id}' ";
		$sql = "select count(DISTINCT a.id) nums FROM iwide_fans_subs a LEFT JOIN iwide_fans b ON a.openid = b.openid AND a.inter_id = b.inter_id left JOIN iwide_hotel_staff c ON a.inter_id = c.inter_id AND a.source = c.qrcode_id LEFT JOIN iwide_distribute_grade_all d ON a.inter_id = d.inter_id AND a.source = d.saler AND a.openid = d.grade_openid AND d.grade_table = 'iwide_fans_sub_log' left JOIN iwide_member e ON a.inter_id = e.inter_id AND a.openid = e.openid where a.source > 0 AND a.inter_id='{$inter_id}'";
		// if(!empty($params['hotel_id'])){
		// 	$sql = $sql." and t.hotel_id = ".$params['hotel_id'];
		// }
		// if(!empty($params['begin_time'])){
		// 	$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
		// 	$sql = $sql." and t.event_time >='".$begin_time."' ";
		// }
		// if(!empty($params['end_time'])){
		// 	$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
		// 	$sql = $sql." and t.event_time <='".$end_time."' ";
		// }
		// //粉丝编号
		// if(!empty($params['fans_id'])){
		// 	$sql = $sql." and t.fas_id =".intval($params['fans_id']);
		// }
		// //分销与名字
		// if(!empty($params['saler_name'])){
		// 	$sql = $sql." and t.name ='".intval($params['saler_name'])."' ";
		// }
		// //分销号
		// if(!empty($params['saler_no'])){
		// 	$sql = $sql." and t.saler =".intval($params['saler_no']);
		// }
	
		// $sql = $sql."  order by t.id desc ";
		//添加酒店权限控制判断
		if(!empty($params['entity_hotel_id'])){
			$sql = $sql." and a.hotel_id in ('".implode("','",$params['entity_hotel_id']) . "') ";
		}
		if(!empty($params['hotel_id'])){
			$sql = $sql." and a.hotel_id = ".$params['hotel_id'];
		}
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and a.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d 23:59:59',strtotime($params['end_time']));
			$sql = $sql." and a.event_time <='".$end_time."' ";
		}
		//粉丝编号
		if(!empty($params['fans_id'])){
			$sql = $sql." and a.id =".intval($params['fans_id']);
		}
		//分销与名字
		if(!empty($params['saler_name'])){
			$sql = $sql." and c.name LIKE '%".$params['saler_name']."%' ";
		}
		//部门
		if(!empty($params['dept'])){
			$sql = $sql." and c.master_dept LIKE '%".$params['dept']."%' ";
		}
		//分销号
		if(!empty($params['saler_no'])){
			$sql = $sql." and a.source =".intval($params['saler_no']);
		}
	
		$sql = $sql."  order by a.id desc ";
		$argvs = array();
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		return $query->nums;
	}
	
	/**
	 * 获取新增粉丝明细
	 */
	public function get_hotel_new_add_fans($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select  lg.id,im.mem_card_no,im.create_time as bind_time,lg.openid,lg.event_time,lg.event,lg.source as saler,ihs.hotel_name,ihs.name,ihs.hotel_id,f.nickname,f.fans_key  
from iwide_fans_sub_log as lg left join iwide_hotel_staff as ihs 
on lg.source=ihs.qrcode_id and lg.inter_id=ihs.inter_id left join iwide_member as im on lg.inter_id=im.inter_id and lg.openid=im.openid LEFT JOIN iwide_fans f ON lg.inter_id=f.inter_id AND lg.openid=f.openid
where lg.inter_id='{$inter_id}' and lg.event=2 ";
		
		if(!empty($params['hotel_id'])){
			$sql = $sql." and ihs.hotel_id = ".$params['hotel_id'];
		}
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
		//分销号
		if(!empty($params['source'])){
			$sql = $sql." and lg.source =".intval($params['source']);
		}
		
		$sql = $sql."  order by lg.id desc ";
		
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	public function get_hotel_new_add_fans_count($params){
		$inter_id = $params['inter_id'];
		$sql = "select  count(lg.id) as nums 
		from iwide_fans_sub_log as lg left join iwide_hotel_staff as ihs
		on lg.source=ihs.qrcode_id and lg.inter_id=ihs.inter_id left join iwide_member as im on lg.inter_id=im.inter_id and lg.openid=im.openid
		where lg.inter_id='{$inter_id}' and lg.event=2 ";
	
		if(!empty($params['hotel_id'])){
			$sql = $sql." and ihs.hotel_id = ".$params['hotel_id'];
		}
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
		//分销号
		if(!empty($params['source'])){
			$sql = $sql." and lg.source =".intval($params['source']);
		}
	
		$argvs = array();
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		return $query->nums;
	}
	
	/**
	 * 分销员发展粉丝明细
	 */
	public function get_hotel_saler_get_fans($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select tmp.*,tmp2.mem_id,tmp2.mem_card_no,tmp2.level,tmp2.membership_number from 
(
select  lg.id,lg.openid,lg.event_time,lg.event,lg.source,ihs.hotel_name,ihs.name,ihs.hotel_id,lg.inter_id     
from iwide_fans_subs as lg left join iwide_hotel_staff as ihs 
on lg.source=ihs.qrcode_id and lg.inter_id=ihs.inter_id where lg.inter_id='{$inter_id}'  and lg.source>0";
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
$sql .= " group by lg.openid order by lg.event_time desc
) as tmp left join 
(
select im.mem_id,im.mem_card_no,im.level,im.inter_id,im.openid,imd.membership_number from iwide_member as im inner join iwide_member_additional as imd on 
im.mem_id=imd.mem_id and im.inter_id='{$inter_id}'
) as tmp2 on tmp.openid=tmp2.openid and tmp.inter_id=tmp2.inter_id where 1=1 ";

		//添加酒店权限控制 stgc 20160902
		if(!empty($params['entity_hotel_id'])){
			$sql = $sql. " and tmp.hotel_id in ('" . implode("','",$params['entity_hotel_id']) . "') ";
		}
		if(!empty($params['hotel_id'])){
			$sql = $sql." and tmp.hotel_id = ".$params['hotel_id'];
		}
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and tmp.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and tmp.event_time <='".$end_time."' ";
		}
		//分销号
		if(!empty($params['source'])){
			$sql = $sql." and tmp.source =".intval($params['source']);
		}
	
		$sql = $sql."  order by tmp.id desc ";
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	/**
	 * 读取分销员发展粉丝总数
	 */
	public function get_hotel_saler_get_fans_count($params){
		$inter_id = $params['inter_id'];
		$sql = "select count(tmp.id) as nums from
		(
		select  lg.id,lg.openid,lg.event_time,lg.event,lg.source,ihs.hotel_name,ihs.name,ihs.hotel_id,lg.inter_id
		from iwide_fans_subs as lg left join iwide_hotel_staff as ihs
		on lg.source=ihs.qrcode_id and lg.inter_id=ihs.inter_id where lg.inter_id='{$inter_id}'  and lg.source>0";
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
		$sql .=" group by lg.openid order by lg.event_time desc
		) as tmp left join
		(
		select im.mem_id,im.mem_card_no,im.level,im.inter_id,im.openid,imd.membership_number from iwide_member as im inner join iwide_member_additional as imd on
		im.mem_id=imd.mem_id and im.inter_id='{$inter_id}'
		) as tmp2 on tmp.openid=tmp2.openid and tmp.inter_id=tmp2.inter_id where 1=1 ";

		//添加酒店权限控制 stgc 20160902
		if(!empty($params['entity_hotel_id'])){
			$sql = $sql. " and tmp.hotel_id in ('" . implode("','",$params['entity_hotel_id']) . "') ";
		}
		if(!empty($params['hotel_id'])){
			$sql = $sql." and tmp.hotel_id = ".$params['hotel_id'];
		}
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and tmp.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and tmp.event_time <='".$end_time."' ";
		}
		//分销号
		if(!empty($params['source'])){
			$sql = $sql." and tmp.source =".intval($params['source']);
		}
	
		$argvs = array();
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		return $query->nums;
	}
	
	/**
	 * 粉丝取消关注
	 */
	public function get_unfollow_fans_list($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select lg.openid,lg.event_time from iwide_fans_sub_log as lg where lg.event=1 and lg.inter_id='{$inter_id}' ";
		
		/* if(!empty($params['hotel_id'])){
			$sql = $sql." and tmp.hotel_id = ".$params['hotel_id'];
		} */
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
		//分销号
		/* if(!empty($params['source'])){
			$sql = $sql." and tmp.source =".intval($params['source']);
		} */
		
		$sql = $sql."  order by lg.id desc ";
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	/**
	 * 粉丝取消关注总数
	 */
	public function get_unfollow_fans_list_count($params){
		$inter_id = $params['inter_id'];
		$sql = "select count(lg.id) as nums from iwide_fans_sub_log as lg where lg.event=1 and lg.inter_id='{$inter_id}' ";
	
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
		
		$argvs = array();
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		return $query->nums;
	}
	
	/**
	 * 读取绑定会员明细表
	 */
	public function get_bind_member_fans($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select im.openid,imd.membership_number,im.create_time from  iwide_member as im left join iwide_member_additional as imd 
on im.mem_id=imd.mem_id where imd.membership_number!='' and im.inter_id='{$inter_id}' ";
		
		/* if(!empty($params['hotel_id'])){
		 $sql = $sql." and tmp.hotel_id = ".$params['hotel_id'];
		 } */
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and im.create_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and im.create_time <='".$end_time."' ";
		}
		//分销号
		/* if(!empty($params['source'])){
		 $sql = $sql." and tmp.source =".intval($params['source']);
		 } */
		
		$sql = $sql."  order by im.mem_id desc ";
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	/**
	 * 读取绑定会员明细表 总数
	 */
	public function get_bind_member_fans_count($params){
		$inter_id = $params['inter_id'];
		$sql = "select count(im.mem_id) as nums from  iwide_member as im left join iwide_member_additional as imd 
on im.mem_id=imd.mem_id where imd.membership_number!='' and im.inter_id='{$inter_id}' ";
	
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and im.create_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and im.create_time <='".$end_time."' ";
		}
	
		$argvs = array();
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
		return $query->nums;
	}
	
	/**
	 * 员工按时段分销绩效
	 * 
	 * @param unknown $inter_id        	
	 * @param string $saler_id        	
	 * @param string $saler_name        	
	 * @param string $hotel_name        	
	 * @param string $limit        	
	 * @param number $offset        	
	 */
	public function get_staff_grade_rank($inter_id, $saler_id = NULL, $saler_name = NULL, $hotel_name = NULL, $btime = NULL, $etime = NULL, $limit = NULL, $offset = 0,$dept_name = NULL) {
		$sql = "SELECT a.*,@rank:=@rank+1 rank FROM (SELECT @rank:=0,hs.`name`,hs.hotel_name,hs.qrcode_id,hs.master_dept,
				SUM(IFNULL(g.grade_total,0)) 'GRADE_TOTAL',
				SUM(IF(g.`grade_table`='iwide_fans_sub_log',g.grade_total,0)) 'GRADE_FANS',
				SUM(IF(g.`grade_table`='iwide_hotels_order',g.grade_total,0)) 'GRADE_ROOMS',
				SUM(IF(SUBSTR(g.`grade_table`,1,12)='iwide_member',g.grade_total,0)) 'GRADE_MEMBER',
				SUM(IF(g.`grade_table`='iwide_firstorder_reward',g.grade_total,0)) 'GRADE_EXTRA',
				SUM(IF(g.`grade_table`='iwide_shp_orders',g.grade_total,0)) 'GRADE_SHOPS',
				SUM(IF(g.`grade_table`='iwide_soma_sales_order:default',g.grade_total,0)) 'GRADE_MALL',
				SUM(IF(g.`grade_table`='iwide_soma_sales_order:groupon',g.grade_total,0)) 'GRADE_GROUPON',
				SUM(IF(g.`grade_table`='iwide_soma_sales_order:package',g.grade_total,0)) 'GRADE_PACKAGE',
				SUM(IF(g.`grade_table`='iwide_soma_mooncake_order:default',g.grade_total,0)) 'GRADE_CAKE',
				SUM(IF(SUBSTR(g.`grade_table`,1,11)='iwide_soma_' OR g.`grade_table`='iwide_shp_orders',g.grade_total,0)) 'GRADE_MALL_ALL',
				SUM(IF(g.`status`=1,g.grade_total,0)) 'UNDELIVER' FROM iwide_hotel_staff hs LEFT JOIN iwide_distribute_grade_all g
				ON hs.inter_id=g.inter_id AND hs.qrcode_id=g.saler 
				WHERE hs.inter_id=? AND hs.is_distributed=1 AND hs.qrcode_id>0 AND (g.status=1 OR g.status=2 OR g.status=9)";
		$params [] = $inter_id;
		if(!empty($btime)){
			$sql .= " AND g.grade_time>=? ";
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= " AND g.grade_time<=? ";
			$params[] = $etime;
		}
        //新增部门搜索
        if (! empty ( $dept_name )) {
            $sql .= " AND hs.master_dept = ? ";
            $params [] = $dept_name;
        }

		$sql .= " GROUP BY hs.qrcode_id ORDER BY GRADE_TOTAL DESC) a ";
		if(!empty($saler_id) || !empty($saler_name) || !empty($hotel_name)){
			$sql .= 'HAVING 1';
		}
		if (! empty ( $saler_id )) {
			$sql .= " AND qrcode_id=? ";
			$params [] = $saler_id;
		}
		if (! empty ( $saler_name )) {
			$sql .= " AND name LIKE ? ";
			$params [] = '%' . $saler_name . '%';
		}
		if (! empty ( $hotel_name )) {
			$sql .= " AND hotel_name LIKE ? ";
			$params [] = '%' . $hotel_name . '%';
		}
		$sql .= " ORDER BY rank";
		if (! empty ( $limit )) {
			$sql .= " limit ?,?";
			$params [] = $offset;
			$params [] = $limit;
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params );
		if($this->input->get('debug') == 1)echo $this->_db('iwide_r1')->last_query();
		return $query;
	}
	/**
	 * 员工按时段分销绩效
	 * 
	 * @param unknown $inter_id        	
	 * @param string $saler_id        	
	 * @param string $saler_name        	
	 * @param string $hotel_name        	
	 */
	public function get_staff_grade_rank_count($inter_id, $saler_id = NULL, $saler_name = NULL, $hotel_name = NULL, $btime = NULL, $etime = NULL , $dept_name = NULL) {
		$sql = "SELECT a.*,count(*) counts FROM (SELECT hs.`name`,hs.hotel_name,hs.qrcode_id FROM iwide_hotel_staff hs LEFT JOIN iwide_distribute_grade_all g
				ON hs.inter_id=g.inter_id AND hs.qrcode_id=g.saler 
				WHERE hs.inter_id=? AND hs.is_distributed=1 AND hs.qrcode_id>0 AND (g.status=1 OR g.status=2 OR g.status=9)";
		$params [] = $inter_id;
		if(!empty($btime)){
			$sql .= " AND g.grade_time>=? ";
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= " AND g.grade_time<=? ";
			$params[] = $etime;
		}

        //新增部门搜索
        if (! empty ( $dept_name )) {
            $sql .= " AND hs.master_dept = ? ";
            $params [] = $dept_name;
        }

		$sql.= " GROUP BY hs.qrcode_id) a ";
		if(!empty($saler_id) || !empty($saler_name) || !empty($hotel_name)){
			$sql .= 'WHERE 1';
		}
		if (! empty ( $saler_id )) {
			$sql .= " AND qrcode_id=? ";
			$params [] = $saler_id;
		}
		if (! empty ( $saler_name )) {
			$sql .= " AND name LIKE ? ";
			$params [] = '%' . $saler_name . '%';
		}
		if (! empty ( $hotel_name )) {
			$sql .= " AND hotel_name LIKE ? ";
			$params [] = '%' . $hotel_name . '%';
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params )->row ();
		return $query->counts;
	}
	
	/**
	 * 门店粉丝取消情况汇总
	 */
	public function get_hotel_unfollow_fans_list($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select lg.openid,lg.event_time from iwide_fans_sub_log as lg where lg.event=1 and lg.inter_id='{$inter_id}' ";
	
		/* if(!empty($params['hotel_id'])){
		 $sql = $sql." and tmp.hotel_id = ".$params['hotel_id'];
			} */
		if(!empty($params['begin_time'])){
			$begin_time = date('Y-m-d H:i:s',strtotime($params['begin_time']));
			$sql = $sql." and lg.event_time >='".$begin_time."' ";
		}
		if(!empty($params['end_time'])){
			$end_time = date('Y-m-d H:i:s',strtotime($params['end_time']));
			$sql = $sql." and lg.event_time <='".$end_time."' ";
		}
		//分销号
		/* if(!empty($params['source'])){
		 $sql = $sql." and tmp.source =".intval($params['source']);
			} */
	
		$sql = $sql."  order by lg.id desc ";
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
	
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	
	/**
	 * 门店粉丝取消情况
	 * @param unknown $inter_id
	 * @param string $hotel_id
	 * @param string $uns_time_begin 取消开始时间
	 * @param string $uns_time_end 取消结束时间
	 * @param string $saler_name
	 * @param string $saler_no
	 * @param string $limit
	 * @param number $offset
	 */
	public function get_hotel_unsubcribe_fans($inter_id, $hotel_id = NULL, $uns_time_begin = NULL, $uns_time_end = NULL, $saler_name = NULL, $saler_no = NULL, $limit = NULL, $offset = 0) {
		$sql = "SELECT fs.hotel_id,fs.event_time,fs.unsubcribe_time,ga.saler,ga.grade_amount_rate,ga.grade_total,ge.staff_name,ga.`status`,ga.send_time,f.nickname,m.mem_card_no
				FROM iwide_fans_subs fs LEFT JOIN iwide_distribute_grade_all ga ON fs.openid=ga.grade_openid AND fs.inter_id=ga.inter_id 
				LEFT JOIN iwide_distribute_grade_ext ge ON ga.id=ge.grade_id 
				LEFT JOIN iwide_member m ON m.openid=fs.openid AND m.inter_id=fs.inter_id 
				LEFT JOIN iwide_fans f ON f.openid=fs.openid AND f.inter_id=fs.inter_id WHERE ga.grade_table='iwide_fans_sub_log' AND fs.cur_status=2 AND fs.inter_id=? ";
		$params [] = $inter_id;
		if (! empty ( $hotel_id )) {
			$sql .= ' AND fs.hotel_id=?';
			$params [] = $hotel_id;
		}
		if (! empty ( $uns_time_begin )) {
			$sql .= ' AND fs.unsubcribe_time>=?';
			$params [] = $uns_time_begin;
		}
		if (! empty ( $uns_time_end )) {
			$sql .= ' AND fs.unsubcribe_time<=?';
			$params [] = $uns_time_end . ' 23:59:59';
		}
		if (! empty ( $saler_name )) {
			$sql .= ' AND ge.staff_name LIKE ?';
			$params [] = "%$saler_name%";
		}
		if (! empty ( $saler_no )) {
			$sql .= ' AND fs.source=?';
			$params [] = $saler_no;
		}
		if (! empty ( $limit )) {
			$sql .= ' limit ?,?';
			$params [] = $offset;
			$params [] = $limit;
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params );
		if($this->input->get('debug') == 1){
			echo $this->_db('iwide_r1')->last_query();echo '<br />';
		}
		return $query;
	}
	public function get_hotel_unsubcribe_fans_count($inter_id, $hotel_id = NULL, $uns_time_begin = NULL, $uns_time_end = NULL, $saler_name = NULL, $saler_no = NULL) {
		$sql = "SELECT COUNT(fs.id) counts FROM iwide_fans_subs fs LEFT JOIN iwide_distribute_grade_all ga ON fs.openid=ga.grade_openid AND fs.inter_id=ga.inter_id 
				LEFT JOIN iwide_distribute_grade_ext ge ON ga.id=ge.grade_id 
				LEFT JOIN iwide_member m ON m.openid=fs.openid AND m.inter_id=fs.inter_id WHERE ga.grade_table='iwide_fans_sub_log' AND fs.cur_status=2 AND fs.inter_id=? ";
		$params [] = $inter_id;
		if (! empty ( $hotel_id )) {
			$sql .= ' AND fs.hotel_id=?';
			$params [] = $hotel_id;
		}
		if (! empty ( $uns_time_begin )) {
			$sql .= ' AND fs.unsubcribe_time>=?';
			$params [] = $uns_time_begin;
		}
		if (! empty ( $uns_time_end )) {
			$sql .= ' AND fs.unsubcribe_time<=?';
			$params [] = $uns_time_end . ' 23:59:59';
		}
		if (! empty ( $saler_name )) {
			$sql .= ' AND ge.staff_name LIKE ?';
			$params [] = "%$saler_name%";
		}
		if (! empty ( $saler_no )) {
			$sql .= ' AND fs.source=?';
			$params [] = $saler_no;
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params )->row();
		return $query->counts;
	}
	/**
	 * 按时段统计员工分销业绩
	 * @param unknown $inter_id
	 * @param string $saler_no
	 * @param string $saler_name
	 * @param string $hotel_name
	 * @param string $limit
	 * @param number $offset
	 */
	public function get_staff_fans_grades($inter_id,$hotel_name = NULL,$btime = NULL,$etime = NULL,$saler_name = NULL,$saler_no = NULL,$limit = NULL,$offset = 0){
		$sql = "SELECT a.inter_id,a.saler,a.fans_counts,a.trans_counts,@rank:=@rank+1 rank,h.hotel_id,h.hotel_name,h.`name`,a.act_fans FROM (SELECT inter_id,saler,hotel_id,SUM(grade_table='iwide_fans_sub_log') fans_counts,COUNT(DISTINCT IF(grade_table<>'iwide_fans_subs',grade_id,null)) trans_counts,COUNT(DISTINCT (IF(grade_table<>'iwide_fans_sub_log',grade_openid,null)) ) act_fans,@rank:=0 FROM iwide_distribute_grade_all WHERE inter_id=? ";
		
		$params[] = $inter_id;
		if(!empty($saler_no)){
			$sql .= " AND saler=?";
			$params[] = $saler_no;
		}
		if(!empty($btime)){
			$sql .= " AND grade_time>=?";
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= " AND grade_time<=?";
			$params[] = $etime.' 23:59:59';
		}
		
		$sql .= "GROUP BY saler ORDER BY fans_counts DESC) a LEFT JOIN iwide_hotel_staff h ON h.inter_id=a.inter_id AND h.qrcode_id=a.saler WHERE 1";
		
		if(!empty($saler_name)){
			$sql .= " AND h.`name` LIKE ?";
			$params[] = "%$saler_name%";
		}
		if(!empty($hotel_name)){
			$sql .= " AND h.hotel_name LIKE ?";
			$params[] = "%$hotel_name%";
		}
		if(!empty($limit)){
			$sql .= " LIMIT ?,?";
			$params[] = $offset;
			$params[] = $limit;
		}
// 		$this->db->query($sql,$params);
// 		echo $this->db->last_query();echo '<br />';
		return $this->_db('iwide_r1')->query($sql,$params);
		
	}
	public function get_staff_fans_grades_count($inter_id,$hotel_name = NULL,$btime = NULL,$etime = NULL,$saler_name = NULL,$saler_no = NULL,$limit = NULL,$offset = 0){
		$sql = "SELECT COUNT(a.inter_id) counts FROM (SELECT inter_id,saler,hotel_id FROM iwide_distribute_grade_all WHERE inter_id=?";
		
		$params[] = $inter_id;
		if(!empty($saler_no)){
			$sql .= " AND saler=?";
			$params[] = $saler_no;
		}
		if(!empty($btime)){
			$sql .= " AND grade_time>=?";
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= " AND grade_time<=?";
			$params[] = $etime.' 23:59:59';
		}
		
		$sql .= " GROUP BY saler) a LEFT JOIN iwide_hotel_staff h ON h.inter_id=a.inter_id AND h.qrcode_id=a.saler WHERE 1";
		
		if(!empty($saler_name)){
			$sql .= " AND h.`name` LIKE ?";
			$params[] = "%$saler_name%";
		}
		if(!empty($hotel_name)){
			$sql .= " AND h.hotel_name LIKE ?";
			$params[] = "%$hotel_name%";
		}
		if(!empty($limit)){
			$sql .= " LIMIT ?,?";
			$params[] = $offset;
			$params[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$params)->row();
		return $query->counts;
	}
	/**
	 * 商城分销绩效报表（按酒店）
	 * @param string $inter_id
	 * @param string $hotel_id
	 * @param string $btime
	 * @param string $etime
	 * @param string $limit
	 * @param number $offset
	 */
	public function get_mall_grades_hotel($inter_id, $hotel_id = NULL, $btime = NULL, $etime = NULL, $limit = NULL, $offset = 0) {
// 		$sql = "SELECT *,@rank:=@rank+1 rank FROM (SELECT @rank:=0,s.hotel_id,COUNT(DISTINCT s.order_id) ORDER_COUNTS,SUM(s.balance>0) BALANCE_PAY_COUNTS,SUM(s.pay_typ='微信支付') WEIXIN_PAY_COUNTS,SUM(s.point>0) POINTS_PAY_COUNTS,SUM(s.ticket>0) TICKET_PAY_COUNTS,
// COUNT(DISTINCT a.grade_id) GRADES_PRODUCTS_COUNTS,SUM(s.actually_paid) TOTAL_AMOUNT,SUM(s.balance) BALANCE_PAY_AMOUNT,SUM(IF(s.pay_typ='微信支付',s.actually_paid,0)) WEIXIN_PAY_AMOUNT,SUM(IF(s.pay_typ='到店支付',s.price,0)) SHOP_PAY_AMOUNT,
// SUM(s.ticket) TICKET_PAY_AMOUNT,SUM(s.point) POINT_PAY_AMOUNT,SUM(a.grade_total) GRADES_AMOUNT,COUNT(a.id) GRADES_COUNT,COUNT(DISTINCT a.saler) SALER_COUNT
// FROM iwide_mall_order_summary s LEFT JOIN iwide_distribute_grade_all a ON s.order_id=a.grade_id AND s.inter_id=a.inter_id WHERE s.inter_id=?";
// 		$params [] = $inter_id;
// 		if (! empty ( $hotel_id )) {
// 			$sql .= " AND s.hotel_id=?";
// 			$params [] = $hotel_id;
// 		}
// 		if (! empty ( $btime )) {
// 			$sql .= " AND a.grade_time>=?";
// 			$params [] = $btime;
// 		}
// 		if (! empty ( $etime )) {
// 			$sql .= " AND a.grade_time<=?";
// 			$params [] = $etime . ' 23:59:59';
// 		}
// 		$sql .= " GROUP BY s.hotel_id ORDER BY TOTAL_AMOUNT DESC) m";
// 		if (empty ( $limit )) {
// 			$sql .= " LIMIT ?,?";
// 			$params [] = $offset;
// 			$params [] = $limit;
// 		}
// 		return $this->_db ( 'iwide_rw' )->query ( $sql, $params );
		$sql = "SELECT *,@rank :=@rank + 1 RANKING FROM ( SELECT sm.hotel_id, COUNT(sm.order_id) ORDER_COUNTS, SUM(sm.balance > 0) BALANCE_PAY_COUNTS, SUM(sm.pay_typ = '微信支付') WEIXIN_PAY_COUNTS, SUM(sm.point > 0) POINT_PAY_COUNTS, SUM(sm.ticket > 0) TICKET_PAY_COUNTS, SUM(sm.counts) GRADES_PRODUCTS_COUNTS, SUM(sm.actually_paid) TOTAL_AMOUNT, SUM(sm.balance) BALANCE_PAY_AMOUNT, SUM(sm.GRADES_COUNT) GRADES_COUNT, @rank := 0, SUM(sm.GRADES_AMOUNT) GRADES_AMOUNT, SUM( IF ( sm.pay_typ = '微信支付', sm.actually_paid, 0 )) WEIXIN_PAY_AMOUNT, SUM( IF ( sm.pay_typ = '到店支付', sm.price, 0 )) SHOP_PAY_AMOUNT, SUM(sm.ticket) TICKET_PAY_AMOUNT, SUM(sm.point) POINT_PAY_AMOUNT FROM ( SELECT s.order_id, s.hotel_id, s.balance, s.pay_typ, s.point, s.ticket, s.order_time, s.counts, s.price, s.shopping_mode, s.actually_paid, SUM(a.grade_total) GRADES_AMOUNT, COUNT(a.id) GRADES_COUNT FROM iwide_mall_order_summary s LEFT JOIN iwide_distribute_grade_all a ON s.order_id = a.grade_id AND s.inter_id = a.inter_id WHERE a.saler>0 AND s.inter_id = ?";
		$params [] = $inter_id;
		if (! empty ( $hotel_id )) {
			$sql .= " AND s.hotel_id=?";
			$params [] = $hotel_id;
		}
		if (! empty ( $btime )) {
			$sql .= " AND a.grade_time>=?";
			$params [] = $btime;
		}
		if (! empty ( $etime )) {
			$sql .= " AND a.grade_time<=?";
			$params [] = $etime . ' 23:59:59';
		}
		$sql .= " GROUP BY grade_table, grade_id ) sm GROUP BY sm.hotel_id ORDER BY TOTAL_AMOUNT DESC ) c";
// 		$sql .= " GROUP BY grade_table, grade_id ) sm GROUP BY sm.hotel_id";
		if (!empty ( $limit )) {
			$sql .= " LIMIT ?,?";
			$params [] = $offset;
			$params [] = $limit;
		}
		
		return $this->_db ( 'iwide_r1' )->query ( $sql, $params );
// 		$query = $this->_db ( 'iwide_rw' )->query ( $sql, $params );
// 		echo $this->_db ( 'iwide_rw' )->last_query();
// 		return $query;
	}
	public function get_mall_grades_hotel_count($inter_id, $hotel_id = NULL, $btime = NULL, $etime = NULL) {
		$sql = "SELECT COUNT(c.hotel_id) nums FROM ( SELECT sm.hotel_id FROM ( SELECT s.order_id, s.hotel_id FROM iwide_mall_order_summary s LEFT JOIN iwide_distribute_grade_all a ON s.order_id = a.grade_id AND s.inter_id = a.inter_id WHERE a.saler>0 AND s.inter_id = ?";
		$params [] = $inter_id;
		if (! empty ( $hotel_id )) {
			$sql .= " AND s.hotel_id=?";
			$params [] = $hotel_id;
		}
		if (! empty ( $btime )) {
			$sql .= " AND a.grade_time>=?";
			$params [] = $btime;
		}
		if (! empty ( $etime )) {
			$sql .= " AND a.grade_time<=?";
			$params [] = $etime . ' 23:59:59';
		}
		$sql .= " GROUP BY grade_table, grade_id ) sm GROUP BY sm.hotel_id) c";
		return $this->_db ( 'iwide_r1' )->query ( $sql, $params )->row ()->nums;
	}
	/**
	 * 酒店产生绩效的员工数
	 * @param unknown $inter_id
	 * @param string $hotel_id
	 * @param string $btime
	 * @param string $etime
	 */
	public function get_grades_hotel_saler($inter_id, $hotel_id = NULL, $btime = NULL, $etime = NULL) {
		$sql = "SELECT COUNT(DISTINCT saler) salers,hotel_id FROM iwide_distribute_grade_all WHERE saler>0 AND inter_id=? ";
		$params [] = $inter_id;
		if (! empty ( $hotel_id )) {
			$sql .= " AND hotel_id=?";
			$params [] = $hotel_id;
		}
		if (! empty ( $btime )) {
			$sql .= " AND grade_time>=?";
			$params [] = $btime;
		}
		if (! empty ( $etime )) {
			$sql .= " AND grade_time<=?";
			$params [] = $etime . ' 23:59:59';
		}
		$sql .= " GROUP BY hotel_id";
		return $this->_db ( 'iwide_r1' )->query ( $sql, $params );
	}
	/**
	 * 商城分销绩效报表
	 * @param string $inter_id        	
	 * @param string $hotel_id        	
	 * @param string $btime        	
	 * @param string $etime        	
	 * @param string $limit        	
	 * @param number $offset        	
	 */
	public function get_mall_grades_order($inter_id, $arvg = array(), $limit = NULL, $offset = 0) {
        $sql = "SELECT s.order_id,s.sub_order_id,s.pms_order_id,s.member_card_no,s.membership_number,s.customer,s.cellphone,s.product,s.product_group,s.order_time,
              s.counts,s.order_status,s.price,s.ticket,s.point,s.balance,s.pay_typ,s.actually_paid,s.hotel_id ohotel,s.shopping_mode,
              a.grade_typ,a.grade_time,a.saler,a.hotel_id,a.grade_amount_rate,a.grade_total,a.send_time,a.`status`,a.hotel_grades,a.fans_hotel,hs.`name`,hs.master_dept
            FROM iwide_mall_order_summary s
            LEFT JOIN iwide_distribute_grade_all a ON s.order_id=a.grade_id AND s.inter_id=a.inter_id
            LEFT JOIN iwide_hotel_staff hs ON a.inter_id=hs.inter_id AND a.saler=hs.qrcode_id
            WHERE s.inter_id=? AND NOT ISNULL(hs.qrcode_id) AND a.saler<>0 and a.status !=99";
		$params [] = $inter_id;
		if (! empty ( $arvg['order_id'] )) {
			$sql .= " AND s.order_id LIKE ?";
			$params [] = "%{$arvg['order_id']}%";
		}
		if (! empty ( $arvg['botime'] )) {
			$sql .= " AND s.order_time>=?";
			$params [] = $arvg['botime'];
		}
		if (! empty ( $arvg['eotime'] )) {
			$sql .= " AND s.order_time<=?";
			$params [] = $arvg['eotime'] . ' 23:59:59';
		}
		if (! empty ( $arvg['bgtime'] )) {
			$sql .= " AND a.grade_time>=?";
			$params [] = $arvg['bgtime'];
		}
		if (! empty ( $arvg['egtime'] )) {
			$sql .= " AND a.grade_time<=?";
			$params [] = $arvg['egtime'] . ' 23:59:59';
		}
		if (! empty ( $arvg['customer'] )) {
			$sql .= " AND s.customer LIKE ?";
			$params [] = "%{$arvg['customer']}%";
		}
		if (! empty ( $arvg['ticket'] )) {
			$sql .= " AND s.ticket";
			if($arvg['ticket'] == 1){
				$sql .= '>0';
			}else{
				$sql .= "=0";
			}
		}
		if (! empty ( $arvg['pay_typ'] )) {
			$sql .= " AND s.pay_typ=?";
			$params [] = $arvg['pay_typ'];
		}
		if (! empty ( $arvg['saler'] )) {
			$sql .= " AND hs.`name` LIKE ?";
			$params [] = "%{$arvg['saler']}%";
		}
		if (! empty ( $arvg['saler_no'] )) {
			$sql .= " AND a.saler=?";
			$params [] = $arvg['saler_no'];
		}
        //一级部门
        if(!empty($arvg['department']))
        {
            $sql .= " AND hs.master_dept = ?";
            $params [] = $arvg['department'];
        }
        if(!empty($arvg['hotel_id'])){
        	if(is_array($arvg['hotel_id'])){
	        	$sql .= " AND s.hotel_id IN ?";
        	}else{
	        	$sql .= " AND s.hotel_id = ?";
        	}
        	$params [] = $arvg['hotel_id'];
        }
		$sql .= " ORDER BY s.order_time DESC";
		if (!empty ( $limit )) {
			$sql .= " LIMIT ?,?";
			$params [] = $offset;
			$params [] = $limit;
		}
		// $query = $this->_db ( 'iwide_r1' )->query ( $sql, $params );
		// echo $this->_db('iwide_r1')->last_query();
		// return $query;
		return $this->_db ( 'iwide_r1' )->query ( $sql, $params );
	}
	public function get_mall_grades_order_count($inter_id, $arvg = array()) {
		$sql = "SELECT COUNT(s.order_id) nums FROM iwide_mall_order_summary s LEFT JOIN iwide_distribute_grade_all a ON s.order_id=a.grade_id AND s.inter_id=a.inter_id LEFT JOIN iwide_hotel_staff hs ON a.inter_id=hs.inter_id AND a.saler=hs.qrcode_id WHERE s.inter_id=? AND NOT ISNULL(hs.qrcode_id) and a.status !=99";
		$params [] = $inter_id;
		if (! empty ( $arvg['order_id'] )) {
			$sql .= " AND s.order_id LIKE ?";
			$params [] = "%{$arvg['order_id']}%";
		}
		if (! empty ( $arvg['botime'] )) {
			$sql .= " AND s.order_time>=?";
			$params [] = $arvg['botime'];
		}
		if (! empty ( $arvg['eotime'] )) {
			$sql .= " AND s.order_time<=?";
			$params [] = $arvg['eotime'] . ' 23:59:59';
		}
		if (! empty ( $arvg['bgtime'] )) {
			$sql .= " AND a.grade_time>=?";
			$params [] = $arvg['bgtime'];
		}
		if (! empty ( $arvg['egtime'] )) {
			$sql .= " AND a.grade_time<=?";
			$params [] = $arvg['egtime'] . ' 23:59:59';
		}
		if (! empty ( $arvg['customer'] )) {
			$sql .= " AND s.customer LIKE ?";
			$params [] = "%{$arvg['customer']}%";
		}
		if (! empty ( $arvg['ticket'] )) {
			$sql .= " AND s.ticket";
			if($arvg['ticket'] == 1){
				$sql .= '>0';
			}else{
				$sql .= "=0";
			}
		}
		if (! empty ( $arvg['pay_typ'] )) {
			$sql .= " AND s.pay_typ=?";
			$params [] = $arvg['pay_typ'];
		}
		if (! empty ( $arvg['saler'] )) {
			$sql .= " AND hs.`name` LIKE ?";
			$params [] = "%{$arvg['saler']}%";
		}
		if (! empty ( $arvg['saler_no'] )) {
			$sql .= " AND a.saler=?";
			$params [] = $arvg['saler_no'];
		}
        //一级部门
        if(!empty($arvg['department']))
        {
            $sql .= " AND hs.master_dept = ?";
            $params [] = $arvg['department'];
        }
        if(!empty($arvg['hotel_id'])){
        	if(is_array($arvg['hotel_id'])){
	        	$sql .= " AND s.hotel_id IN ?";
        	}else{
	        	$sql .= " AND s.hotel_id = ?";
        	}
        	$params [] = $arvg['hotel_id'];
        }
		return $this->_db ( 'iwide_r1' )->query ( $sql, $params )->row()->nums;
	}
	public function sysc_summ(){
		$this->load->model('distribute/grades_model');
		$this->load->model('distribute/fans_model');
		$this->load->model('distribute/qrcodes_model');
		$this->load->model('wx/publics_model');
		$hotels = $this->publics_model->get_public_hash(array('status'=>0),array('inter_id'));
		//产生交易的酒店数
		$time_begin = new DateTime();
		if($this->input->get('__btime')){
			$time_begin = new DateTime($this->input->get('__btime'));
		}
		$time_end   = clone($time_begin);
// 		$time_begin = '2015-01-01';
// 		$time_begin = date('Y-m-d 00:00:00',strtotime('-1 day'));
// 		$time_end   = date('Y-m-d');
		//发放配置
		$deliver_settings_res = $this->grades_model->get_deliver_setting();
		$deliver_settings = array();
		foreach ($deliver_settings_res as $item){
			$deliver_settings[$item->inter_id] = $item->mode;
		}
		$time_now = new DateTime();
		date_add($time_end, date_interval_create_from_date_string('1 days'));
// 		while ($time_end < $time_now){
			$this->_db('iwide_rw')->trans_begin();
// 			ob_start();
// 			echo '<br/>time_end : '.$time_end->format('Y-m-d H:i:s') . "\n";
// // 			ob_flush();
// // 			flush();
// 			date_add($time_end, date_interval_create_from_date_string('1 days'));
// 			date_add($time_begin, date_interval_create_from_date_string('1 days'));
			$hotels_trans_counts  = $this->grades_model->get_hotel_counts_with_records('',$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
			foreach ($hotels as $hotel){
				//间夜&绩效&交易额
				$room_nights_grades = $this->grades_model->get_distribute_room_nights_with_grades(array($hotel['inter_id']),array(),$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				//商品&绩效&交易额
				$products_grades    = $this->grades_model->get_distribute_products_counts_with_grades(array($hotel['inter_id']),array(),$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				//酒店数
				$hotels_count       = $this->grades_model->get_hotel_counts($hotel['inter_id']);
				//新粉的订房数及总金额
				$room_amount        = $this->fans_model->get_fans_room_summ(array($hotel['inter_id']),$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				//新粉购买商品数及总金额
				$mall_amount        = $this->fans_model->get_fans_mall_summ(array($hotel['inter_id']),$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				//公众号员工总绩效
				$total_grades       = $this->grades_model->get_ps_grades_amount($hotel['inter_id'],$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				//log insert
				$avgs['inter_id']           = $hotel['inter_id'];
				$avgs['room_counts']        = isset($room_nights_grades->ds) ? $room_nights_grades->ds : 0;
				$avgs['room_trans']         = isset($room_nights_grades->trans_amounts) ? $room_nights_grades->trans_amounts : 0;
				$avgs['grades_amount']      = $total_grades;
				$avgs['product_counts']     = isset($products_grades->ds) ? $products_grades->ds : 0;
				$avgs['mall_trans']         = isset($products_grades->trans_amounts) ? $products_grades->trans_amounts : 0;
				//新增粉丝数
				$avgs['new_fans_count']     = $this->fans_model->get_fans_count_by_time(array($hotel['inter_id']),$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				//新增分销员数
				$avgs['new_saler_count']    = $this->qrcodes_model->get_salers_counts_sample(array($hotel['inter_id']),array(),$time_begin->format('Y-m-d'),$time_end->format('Y-m-d'));
				$avgs['new_room_counts']    = isset($room_amount->counts) ? $room_amount->counts : 0;
				$avgs['new_room_trans']     = isset($room_amount->total_amount) ? $room_amount->total_amount : 0;
				$avgs['new_product_counts'] = isset($mall_amount->counts) ? $mall_amount->counts : 0;
				$avgs['new_mall_trans']     = isset($mall_amount->total_amount) ? $mall_amount->total_amount : 0;
				$avgs['hotel_counts']       = $hotels_count;
				$avgs['trans_hotel_count']  = isset($hotels_trans_counts[$hotel['inter_id']]) ? $hotels_trans_counts[$hotel['inter_id']] : 0;
				$avgs['send_typ']           = isset($deliver_settings[$hotel['inter_id']]) ? $deliver_settings[$hotel['inter_id']] : 1;
				$avgs['distri_hotels']      = $this->qrcodes_model->get_hotel_id_counts_with_pris ( $hotel['inter_id'] );
				$avgs['create_time']        = date('Y-m-d H:i:s');
// 				$avgs['create_time']        = $time_end->format('Y-m-d H:i:s');
				$avgs['summ_date']          = $time_end->format('Ymd');
				$this->_db('iwide_rw')->insert('distribute_summary',$avgs);
// 			}
			if($this->_db('iwide_rw')->trans_status() === FALSE){
				$this->_db('iwide_rw')->trans_rollback();
				echo 'failed';
			}else{
				$this->_db('iwide_rw')->trans_commit();
				echo 'ok';
			}
		}
	}
	/**
	 * 根据指定条件获取酒店分销统计概览
	 * @param Array $inter_id 公众号唯一编号数组
	 * @param string $date 统计概况日期
	 * @return Sql-Query
	 */
	public function get_dist_summ_by_date($inter_id = array(),$date = ''){
		if(empty($date)){
			$date = date('Ymd',strtotime('-1 days'));
		}
		$sql = "SELECT SUM(room_counts) room_counts,SUM(product_counts) product_counts,SUM(new_fans_count) new_fans_count,SUM(new_saler_count) new_saler_count,SUM(new_room_counts) new_room_counts,SUM(new_product_counts) new_product_counts,SUM(room_trans) room_trans,SUM(mall_trans) mall_trans,SUM(new_room_trans) new_room_trans,SUM(new_mall_trans) new_mall_trans,SUM(trans_hotel_count > 0) trans_hotel_count,SUM(grades_amount) grades_amount,SUM(send_typ=0) auto_deliver_counts,SUM(distri_hotels > 0) distri_hotels_count FROM iwide_distribute_summary WHERE summ_date=?";
		$params = array($date);
		if(!empty($inter_id) && $inter_id !='ALL_PRIVILEGES'){
    		if(is_array($inter_id)){
				$sql .= " AND inter_id IN ?";
				$params[] = $inter_id;
			}else{
				$sql .= " AND inter_id=?";
				$params[] = $inter_id;
			}
		}
		return $this->_db('iwide_r1')->query($sql,$params)->row();
	}
	/**
	 * 根据指定条件获取酒店分销基础信息概况
	 * @param Array $inter_id 公众号唯一编号数组
	 * @param string $send_typ 发放类型
	 * @param string $begin_time 起始时间
	 * @param string $end_time 结束时间
	 * @return Sql-Query
	 */
	public function get_dist_summ_group_by_date($inter_id = array(),$send_typ = '',$begin_time = '',$end_time = ''){
		$sql = "SELECT *,@rank:=@rank+1 rank FROM (SELECT inter_id,SUM(room_counts) room_counts,SUM(product_counts) product_counts,SUM(new_fans_count) new_fans_counts,SUM(new_saler_count) new_saler_counts,SUM(new_room_counts) new_room_counts,SUM(new_product_counts) new_product_counts,SUM(room_trans) room_trans,SUM(mall_trans) mall_trans,SUM(new_room_trans) new_room_trans,SUM(new_mall_trans) new_mall_trans,@rank:=0,send_typ FROM iwide_distribute_summary";
		$where = '';
		$params = array();
		if(!empty($inter_id) && $inter_id !='ALL_PRIVILEGES'){
    		if(is_array($inter_id)){
				$where .= " WHERE inter_id IN ?";
				$params[] = $inter_id;
			}else{
				$where .= " WHERE inter_id=?";
				$params[] = $inter_id;
			}
		}
		if($send_typ != ''){
			if(empty($where)){
				$where .= " WHERE send_typ=?";
				$params[] = $send_typ;
			}else{
				$where .= " AND send_typ=?";
				$params[] = $send_typ;
			}
		}
		if(!empty($begin_time)){
			if(empty($where)){
				$where .= " WHERE create_time>=?";
				$params[] = $begin_time;
			}else{
				$where .= " AND create_time>=?";
				$params[] = $begin_time;
			}
		}
		if(!empty($end_time)){
			if(empty($where)){
				$where .= " WHERE create_time<?";
				$params[] = $end_time;
			}else{
				$where .= " AND create_time<?";
				$params[] = $end_time;
			}
		}
		$sql .= $where;
		$sql .= " GROUP BY inter_id) a ORDER BY room_counts DESC";
		return $this->_db('iwide_r1')->query($sql,$params);
// 		$query = $this->_db('iwide_r1')->query($sql,$params);
// 		echo $this->_db('iwide_r1')->last_query();
// 		echo '<br />';
// 		return $query;
	}
	/**
	 * 分销统计，计算指定时间分销间夜数、分销商品数和新增粉丝数
	 * @param unknown $inter_id
	 * @param string $begin_time
	 * @param string $end_time
	 */
	public function get_dist_room_pro_fas_by_date($inter_id = array(),$begin_time = '',$end_time = ''){
		$sql = "SELECT SUM(room_counts) rc,SUM(product_counts) pc,SUM(new_fans_count) nfc,summ_date date FROM iwide_distribute_summary";
		$params = array();
		$where = '';
		if(!empty($inter_id) && $inter_id != 'ALL_PRIVILEGES'){
			if(is_array($inter_id))
				$where .= ' WHERE inter_id IN ?';
			else
				$where .= ' WHERE inter_id=?';
			$params[] = $inter_id;
		}
		if(!empty($begin_time)){
			if(empty($where)){
				$where .= " WHERE summ_date>=?";
				$params[] = $begin_time;
			}else{
				$where .= " AND summ_date>=?";
				$params[] = $begin_time;
			}
		}
		if(!empty($end_time)){
			if(empty($where)){
				$where .= " WHERE summ_date<?";
				$params[] = $end_time;
			}else{
				$where .= " AND summ_date<?";
				$params[] = $end_time;
			}
		}
		$sql .= $where;
		$sql .= " GROUP BY summ_date";
		return $this->_db('iwide_r1')->query($sql,$params);
	}

    function get_all_club_orders($params,$limit=NULL,$offset=0){

        $sql = "SELECT * FROM (SELECT oi.club_id oicid,oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id ointer_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,
				o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,oa.coupon_des,oa.coupon_give_info coupon_give,oa.complete_point_info point_give,
				oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,m.mem_card_no,ma.membership_number
				FROM iwide_hotel_orders o
INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id
LEFT JOIN iwide_hotel_order_items oi ON o.orderid=oi.orderid LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id WHERE oi.club_id !='' AND o.inter_id=?";
        $argvs [] = $params['inter_id'];
        //添加酒店权限筛选
        if(!empty($params['entity_hotel_id'])){
            $sql .= " AND o.hotel_id in ('" .implode("','",$params['entity_hotel_id']) . "') ";
        }
        if(!empty($params['hotel_id'])){
            $sql .= " AND o.hotel_id=?";
            $argvs [] = $params['hotel_id'];
        }
        if(!empty($params['cout_date_begin'])){
            $sql .= " AND oi.enddate>=?";
            $argvs [] = $params['cout_date_begin'];
        }
        if(!empty($params['cout_date_end'])){
            $sql .= " AND oi.enddate<=?";
            $argvs [] = $params['cout_date_end'];
        }
        if(!empty($params['order_id'])){
            $sql .= " AND o.orderid LIKE ?";
            $argvs [] = '%'.$params['order_id'].'%';
        }
        $is_distributed = 1;
        if(isset($params['distribute'])){
            $is_distributed = $params['distribute'];
        }
        $sql .= " GROUP BY oi.id) orders INNER JOIN
(SELECT ga.inter_id,ga.saler,ga.grade_openid,ga.grade_table,ga.grade_id,ga.grade_id_name,ga.order_amount,ga.grade_total,
				ga.grade_amount,ga.grade_time,ga.status dis_status,ga.grade_amount_rate,ga.grade_rate_type,ga.remark,ga.deliver_batch,
				ga.last_update_time,ga.partner_trade_no,ga.send_time,ga.deliver_fail,ga.order_hotel,ga.order_status,ga.fans_hotel,
				ga.hotel_rate,ga.group_rate,ga.jfk_rate,ga.hotel_grades,ga.group_grades,ga.jfk_grades,ge.hotel_name,ge.staff_name,
				ge.product FROM iwide_distribute_grade_all ga
INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.grade_table='iwide_hotels_order' AND ga.id=ge.grade_id WHERE ge.distribute=? AND ga.inter_id=? AND ga.saler>0  AND ga.grade_typ=2";
        $argvs [] = $is_distributed;
        $argvs [] = $params['inter_id'];
        if(!empty($params['saler_name'])){
            $sql .= " AND ge.staff_name LIKE ?";
            $argvs [] = '%'.$params['saler_name'].'%';
        }
        if(!empty($params['saler_no'])){
            $sql .= " AND ga.saler=?";
            $argvs [] = $params['saler_no'];
        }
        if(!empty($params['grade_date_begin'])){
            $sql .= " AND ga.grade_time>=?";
            $argvs [] = $params['grade_date_begin'];
        }
        if(!empty($params['grade_date_end'])){
            $sql .= " AND ga.grade_time<=?";
            $argvs [] = $params['grade_date_end'].' 23:59:59';
        }
        if(!empty($params['send_date_begin'])){
            $sql .= " AND ga.send_time>=?";
            $argvs [] = $params['send_date_begin'];
        }
        if(!empty($params['send_date_end'])){
            $sql .= " AND ga.send_time<=?";
            $argvs [] = $params['send_date_end'].' 23:59:59';
        }
        $sql .=") grades ON orders.ointer_id=grades.inter_id AND orders.oiid=grades.grade_id ORDER BY grades.grade_time DESC";
        if(!empty($limit)){
            $sql .= ' LIMIT ?,?';
            $argvs[] = $offset;
            $argvs[] = $limit;
        }

        $sql = "SELECT t1.*,t2.club_name,t3.name csname FROM(".$sql.") t1,iwide_club_list t2,iwide_club_staff t3 WHERE t1.oicid = t2.club_id AND t1.inter_id = t2.inter_id AND t1.inter_id = t3.inter_id AND t2.id = t3.qrcode_id";
        $query = $this->_db('iwide_r1')->query($sql,$argvs);
        if($this->input->get('debug') == 1){
            echo $this->_db('iwide_r1')->last_query();
        }
        return $query;
    }


    function get_club_orders_count($params){
        $sql = "SELECT * FROM (SELECT oi.club_id oicid,oi.id oiid,o.id,o.hotel_id,o.openid,o.inter_id ointer_id,o.price,o.roomnums,o.name,o.order_time,o.orderid,
				o.paid,o.paytype,oa.web_orderid,oa.coupon_favour,oa.wxpay_favour,oa.point_used,oa.point_used_amount,oa.coupon_used,oa.coupon_des,oa.coupon_give_info coupon_give,oa.complete_point_info point_give,
				oi.id sid,oi.room_id,oi.iprice,oi.startdate,oi.enddate,oi.istatus,oi.allprice,oi.roomname,oi.webs_orderid,m.mem_card_no,ma.membership_number
				FROM iwide_hotel_orders o
INNER JOIN iwide_hotel_order_additions oa ON o.orderid=oa.orderid AND oa.inter_id=o.inter_id
LEFT JOIN iwide_hotel_order_items oi ON o.orderid=oi.orderid LEFT JOIN iwide_member m ON m.inter_id=o.inter_id AND m.openid=o.openid LEFT JOIN iwide_member_additional ma ON m.mem_id=ma.mem_id WHERE  o.inter_id=?";
        $argvs [] = $params['inter_id'];
        //添加酒店权限筛选
        if(!empty($params['entity_hotel_id'])){
            $sql .= " AND o.hotel_id in ('" .implode("','",$params['entity_hotel_id']) . "') ";
        }
        if(!empty($params['hotel_id'])){
            $sql .= " AND o.hotel_id=?";
            $argvs [] = $params['hotel_id'];
        }
        if(!empty($params['cout_date_begin'])){
            $sql .= " AND oi.enddate>=?";
            $argvs [] = $params['cout_date_begin'];
        }
        if(!empty($params['cout_date_end'])){
            $sql .= " AND oi.enddate<=?";
            $argvs [] = $params['cout_date_end'];
        }
        if(!empty($params['order_id'])){
            $sql .= " AND o.orderid LIKE ?";
            $argvs [] = '%'.$params['order_id'].'%';
        }

        $is_distributed = 1;
        if(isset($params['distribute'])){
            $is_distributed = $params['distribute'];
        }
        $sql .= " GROUP BY oi.id) orders INNER JOIN
(SELECT ga.inter_id,ga.saler,ga.grade_openid,ga.grade_table,ga.grade_id,ga.grade_id_name,ga.order_amount,ga.grade_total,
				ga.grade_amount,ga.grade_time,ga.status dis_status,ga.grade_amount_rate,ga.grade_rate_type,ga.remark,ga.deliver_batch,
				ga.last_update_time,ga.partner_trade_no,ga.send_time,ga.deliver_fail,ga.order_hotel,ga.order_status,ga.fans_hotel,
				ga.hotel_rate,ga.group_rate,ga.jfk_rate,ga.hotel_grades,ga.group_grades,ga.jfk_grades,ge.hotel_name,ge.staff_name,
				ge.product FROM iwide_distribute_grade_all ga
INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id AND ga.grade_table='iwide_hotels_order' WHERE ge.distribute=? AND ga.inter_id=? AND ga.saler>0  AND ga.grade_typ=2";
        $argvs [] = $is_distributed;
        $argvs [] = $params['inter_id'];
        if(!empty($params['saler_name'])){
            $sql .= " AND ge.staff_name LIKE ?";
            $argvs [] = '%'.$params['saler_name'].'%';
        }
        if(!empty($params['saler_no'])){
            $sql .= " AND ga.saler=?";
            $argvs [] = $params['saler_no'];
        }
        if(!empty($params['grade_date_begin'])){
            $sql .= " AND ga.grade_time>=?";
            $argvs [] = $params['grade_date_begin'];
        }
        if(!empty($params['grade_date_end'])){
            $sql .= " AND ga.grade_time<=?";
            $argvs [] = $params['grade_date_end'].' 23:59:59';
        }
        if(!empty($params['send_date_begin'])){
            $sql .= " AND ga.send_time>=?";
            $argvs [] = $params['send_date_begin'];
        }
        if(!empty($params['send_date_end'])){
            $sql .= " AND ga.send_time<=?";
            $argvs [] = $params['send_date_end'].' 23:59:59';
        }
        $sql .=") grades ON orders.ointer_id=grades.inter_id AND orders.oiid=grades.grade_id ORDER BY grades.grade_time DESC";
        $sql = "SELECT count(*) nums FROM(".$sql.") t1,iwide_club_list t2,iwide_club_staff t3 WHERE t1.oicid = t2.club_id AND t1.inter_id = t2.inter_id AND t1.inter_id = t3.inter_id AND t2.id = t3.qrcode_id";

        $query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
        return $query->nums;
    }


    /**
     * 获取
     * @param $where
     * @return mixed
     */
    public function report_config_model($where)
    {
        $this->_db('iwide_r1')->where_in('conf_mode',$where);
        $query = $this->_db('iwide_r1')->get('distribute_report_config');
        $data =  $query->result_array();
        return $data;
    }

}
