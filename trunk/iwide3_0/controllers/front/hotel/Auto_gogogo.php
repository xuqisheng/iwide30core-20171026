<?php
// if (! defined ( 'BASEPATH' ))
// exit ( 'No direct script access allowed' );
class Auto_gogogo extends MY_Controller {
	function __construct() {
		parent::__construct ();
		$this->debug = $this->input->get ( 'debug' );
		error_reporting ( 0 );
		if (! empty ( $this->debug )) {
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 1 );
		}
		$this->output->enable_profiler ( false );
	}
	public function auto_task() {
		// $this->db->where(array('id'=>6));
		// $task=$this->db->get('auto_task_config')->row_array();
		// redirect($task['run_task']);
		// var_dump($task);
		// exit;
		$db_read=$this->load->database('iwide_r1',true);
		set_time_limit ( 0 );
		$type = $this->input->get ( 'ttype', true );
		if (! empty ( $type )) {
			$time = time ();
			$sql = 'select * from iwide_auto_task_config where status=1 and run_check=1 and task_type=? and last_run_time+run_interval <= ?';
			$tasks = $db_read->query ( $sql, array (
				$type,
				$time
			) )->result_array ();
			if (! empty ( $tasks )) {
				foreach ( $tasks as $tk ) {
					switch ($tk ['run_type']) {
						case 'curl_get' :
							$this->load->helper ( 'common' );
							for($i = 0; $i < $tk ['run_times']; $i ++) {
								$s = doCurlGetRequest ( $tk ['run_task'], array (), 2 );
								$this->db->insert ( 'auto_task_log', array (
									'task_id' => $tk ['id'],
									'task_des' => $tk ['run_task'],
									'task_extra' => '',
									'task_title' => $tk ['task_name'],
									'task_result' => json_encode ( array (
										                               'result' => $s
									                               ) ),
									'task_ident' => $type . '_' . $time,
									'log_time' => time ()
								) );
								if (! empty ( $this->debug )) {
									echo $tk ['task_name'] . ',' . $s . '<br />';
								}
							}
							$this->db->where ( array (
								                   'id' => $tk ['id']
							                   ) );
							$this->db->update ( 'auto_task_config', array (
								'last_run_time' => $time
							) );
							break;
						case 'curl_post' :
							$this->load->helper ( 'common' );
							$data = json_decode ( $tk ['task_data'], TRUE );
							for($i = 0; $i < $tk ['run_times']; $i ++) {
								$s = doCurlPostRequest ( $tk ['run_task'], $data, 1 );
								$this->db->insert ( 'auto_task_log', array (
									'task_id' => $tk ['id'],
									'task_des' => $tk ['run_task'],
									'task_extra' => $tk ['task_data'],
									'task_title' => $tk ['task_name'],
									'task_result' => json_encode ( array (
										                               'result' => $s
									                               ) ),
									'task_ident' => $type . '_' . $time,
									'log_time' => time ()
								) );
							}
							$this->db->where ( array (
								                   'id' => $tk ['id']
							                   ) );
							$this->db->update ( 'auto_task_config', array (
								'last_run_time' => $time
							) );
							break;
						case 'model_function' :
							break;
						case 'redirect' :
							$this->db->insert ( 'auto_task_log', array (
								'task_id' => $tk ['id'],
								'task_des' => $tk ['run_task'],
								'task_extra' => $tk ['task_data'],
								'task_title' => $tk ['task_name'],
								'task_result' => json_encode ( array (
									                               'result' => 'url_redirect'
								                               ) ),
								'task_ident' => $type . '_' . $time,
								'log_time' => time ()
							) );
							$this->db->where ( array (
								                   'id' => $tk ['id']
							                   ) );
							$this->db->update ( 'auto_task_config', array (
								'last_run_time' => $time,
								'run_check' => 2
							) );
							$in = array ();
							for($i = 0; $i < count ( $tasks ); $i ++) {
								if ($tasks [$i] ['id'] != $tk ['id'] && $tasks [$i] ['run_type'] == 'redirect')
									$in [] = $tasks [$i] ['id'];
							}
							if (empty ( $in )) {
								$this->db->where ( array (
									                   'status' => 1,
									                   'run_check' => 2,
									                   'task_type' => $type
								                   ) );
								$this->db->update ( 'auto_task_config', array (
									'run_check' => 1
								) );
							}
							redirect ( $tk ['run_task'] );
							exit ();
							break;
						default :
							break;
					}
				}
			}
		}
	}
	public function check_task_log() {
		$db_read=$this->load->database('iwide_r1',true);
		$sql = 'SELECT * FROM `iwide_auto_task_config`';
		$data = $db_read->query ( $sql )->result ();
		echo '配置状态:' . '<br />';
		foreach ( $data as $d ) {
			echo $d->task_name . ',' . date ( 'Y-m-d H:i:s', $d->last_run_time ) . ',' . $d->run_check . '<br />';
		}
		$limit = $this->input->get ( 'l' );
		$limit = empty ( $limit ) ? 50 : $limit;
		$sql = "SELECT * FROM `iwide_webservice_record` WHERE `record_type` LIKE 'order_batch_update' ORDER BY `iwide_webservice_record`.`id` DESC LIMIT 0,$limit";
		$data = $db_read->query ( $sql )->result ();
		echo '<br />运行记录:' . '<br />';
		foreach ( $data as $d ) {
			echo $d->id . ':' . date ( 'Y-m-d H:i:s', $d->record_time ) . ',' . $d->service_type . '<br />' . $d->receive_content . '<br />';
		}
	}
	public function update_web_orders_yibo() {
		set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
		$this->load->helper('common');
		$time=time();
		statistic($time.'A1');
		$inter_id = 'a441098524';
		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		statistic($time.'A2');
		/*$sql = "select oa.*,o.* from
				(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
					) o join
				(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
					on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();*/

		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		statistic($time.'A3');
		/*foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}*/
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		statistic($time.'A4');
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		statistic($time.'A5');
		$handle_num = 0;
		$handle_orders = '(逸柏)oo-';
		$this->load->model ( 'hotel/pms/Yibo_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {ia
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		statistic($time.'A6');
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'yibo',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		echo '<br />';
		echo '获取本地配置：'.statistic($time.'A1',$time.'A2').'<br />';
		echo '查询主订单：'.statistic($time.'A2',$time.'A3').'<br />';
		echo '查询子订单：'.statistic($time.'A3',$time.'A4').'<br />';
		echo '更新本地配置：'.statistic($time.'A4',$time.'A5').'<br />';
		echo '同步PMS订单：'.statistic($time.'A5',$time.'A6').'<br />';
		echo '总耗时：'.statistic($time.'A1',$time.'A6');
		// redirect ( 'http://yzhotels.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yuanzhou?oc='.$one_count );
	}
	public function update_web_orders_yuanzhou() {
		set_time_limit ( 0 );
		$inter_id = 'a440577876';
		$this->load->model ( 'hotel/Hotel_config_model' );
		$db_read=$this->load->database('iwide_r1',true);

		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$startdate=date('Ymd',strtotime('- 30 day',time()));
		$sql = "select oa.*,o.* from
				(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4,7) and handled=0 and startdate >= $startdate ORDER BY `id` asc
					) o join
				(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
					on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(远洲)oo-';
		$this->load->model ( 'hotel/pms/Yuanzhou_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'yuanzhou',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '(远洲)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://chatinn.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_jieding?oc='.$one_count);
	}
	public function update_web_orders_jieding() {
		set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a441624001';
		$this->load->model ( 'hotel/Hotel_config_model' );

		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'pms_type' => 'zhuzhe'
		                   ) );
		$pms_set = $db_read->get_where ( 'hotel_additions' )->row_array ();
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(街町)oo-';
		$this->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt, $pms_set ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'jieding',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '(街町)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yumeng?oc='.$one_count );
	}
	public function update_web_orders_yumeng() {
		set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a445223616';
		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'hotel_id >' => 0
		                   ) );
		$hotel_pms_set = $db_read->get_where ( 'hotel_additions' )->result_array ();
		$pms_sets = array ();
		foreach ( $hotel_pms_set as $hps ) {
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $this->db->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(365云盟)oo-';
//		$this->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'zhuzhe_pms' );
//		$this->load->model ( 'hotel/pms/Huayi_hotel_model', 'huayi_pms' );
		$this->load->model ( 'hotel/pms/Lvyun_hotel_model', 'lvyun_pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			switch ($pms_sets [$lt ['hotel_id']] ['pms_type']) {
				/*case 'zhuzhe' :
					$new_status = $this->zhuzhe_pms->update_web_order ( $inter_id, $lt, $pms_sets [$lt ['hotel_id']] ); // 更新订单状态,返回新的状态
					break;*/
				/*case 'huayi' :
					$room_codes = json_decode ( $lt ['room_codes'], TRUE );
					$lt ['first_detail'] ['room_id'] = key ( $room_codes );
					$new_status = $this->huayi_pms->update_web_order ( $inter_id, $lt, array (
						'pms_set' => $pms_sets [$lt ['hotel_id']]
					) ); // 更新订单状态,返回新的状态
					break;*/
				case 'lvyun' :
					$room_codes = json_decode ( $lt ['room_codes'], TRUE );
					$lt ['first_detail'] ['room_id'] = key ( $room_codes );
					$new_status = $this->lvyun_pms->update_web_order ( $inter_id, $lt, $pms_sets [$lt ['hotel_id']]); // 更新订单状态,返回新的状态
					break;
				default :
					break;
			}
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'yunmeng',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '(365云盟)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://morninginn.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_molin?oc='.$one_count );
	}
	public function update_web_orders_molin() {
		set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a452223043';
		$this->load->model ( 'hotel/Hotel_config_model' );

		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'pms_type' => 'luopan'
		                   ) );
		$pms_set = $db_read->get_where ( 'hotel_additions' )->row_array ();
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(莫林风尚)oo-';
		$this->load->model ( 'hotel/pms/Luopan_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt, array (
				'pms_set' => $pms_set
			) ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'molin',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '(莫林风尚)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_shuxiang?oc='.$one_count );
	}
	public function update_web_orders_shuxiang() {
		set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a449675133';
		$this->load->model ( 'hotel/Hotel_config_model' );

		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'pms_type' => 'lvyun'
		                   ) );
		$pms_set = $db_read->get_where ( 'hotel_additions' )->row_array ();
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(书香)oo-';
		$this->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt, $pms_set ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'shuxiang',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '(书香)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yumeng' );
	}
	public function update_web_orders_yinju() {
		set_time_limit ( 0 );
		$inter_id = 'a457946152';
		$db_read=$this->load->database('iwide_r1',true);
		$this->load->model ( 'hotel/Hotel_config_model' );
		
		$db_read->where ( array (
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module' => 'HOTEL',
				'hotel_id' => 0,
				'inter_id' => $inter_id
		) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
				'inter_id' => $inter_id,
				'pms_type' => 'lvyun'
		) );
		$pms_set = $db_read->get_where ( 'hotel_additions' )->row_array ();
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";

		$orderlist = $db_read->query ( $sql )->result_array ();
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
					'inter_id' => $inter_id,
					'orderid' => $order ['orderid']
			) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
					'param_name' => 'WEB_ORDER_UPDATE_COUNT',
					'module' => 'HOTEL',
					'hotel_id' => 0,
					'inter_id' => $inter_id
			) );
			$this->db->update ( 'hotel_config', array (
					'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
					'param_name' => 'WEB_ORDER_UPDATE_COUNT',
					'module' => 'HOTEL',
					'hotel_id' => 0,
					'inter_id' => $inter_id
			) );
			$this->db->update ( 'hotel_config', array (
					'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(隐居)oo-';
		$this->load->model ( 'hotel/pms/Lvyun_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt, $pms_set ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$this->db->insert ( 'weixin_text', array (
				'content' => $handle_orders,
				'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'lvyun', $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], '', $handle_orders,'order_batch_update', $now, microtime (), 'gang');
		
		echo '(隐居)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yumeng' );
	}
	public function update_web_orders_bgy() {
		set_time_limit ( 0 );
		$inter_id = 'a421641095';
		$db_read=$this->load->database('iwide_r1',true);
		$this->load->model ( 'hotel/Hotel_config_model' );

		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'hotel_id >' => 0
		                   ) );
		$hotel_pms_set = $db_read->get_where ( 'hotel_additions' )->result_array ();
		$pms_sets = array ();
		foreach ( $hotel_pms_set as $hps ) {
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$time_s=time()-2592000;
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 and order_time >".$time_s." ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(碧桂园)oo-';
		$this->load->model ( 'hotel/pms/Zhongruan_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt, $pms_sets[$lt['hotel_id']] ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'biguiyuan',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		echo '(碧桂园)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yumeng' );
	}

	public function bgy_point(){
		$m=$this->input->get('m');
		if ($m==1){
			$this->db->where('id',527);
			$this->db->update('pay_config',array('status'=>2));
			echo $this->db->last_query();
			$this->db->where('id',44);
			$this->db->update('webservice_field_config',array('local_value'=>'08,09,10,11'));
			echo $this->db->last_query();
		}else{
			$this->db->where('id',527);
			$this->db->update('pay_config',array('status'=>1));
			echo $this->db->last_query();
			$this->db->where('id',44);
			$this->db->update('webservice_field_config',array('local_value'=>'08,09,10,11,01'));
			echo $this->db->last_query();
		}
	}

	public function update_web_orders_s8() {
		set_time_limit ( 0 );
		$inter_id = 'a455510007';
		$db_read=$this->load->database('iwide_r1',true);
		$this->load->model ( 'hotel/Hotel_config_model' );

		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'pms_type' => 'suba',
			                   'hotel_id'=>0
		                   ) );
		$pms_set = $db_read->get ( 'hotel_additions' )->row_array ();
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0 ORDER BY `id` asc
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,3,4,9) and handled=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id LIMIT " . $update_count ['param_value'] . ",$one_count";
		$orderlist = $db_read->query ( $sql )->result_array ();
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
			echo json_encode($orderlist);
		}
		if (count ( $orderlist ) < $one_count) {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => 0
			) );
		} else {
			$this->db->where ( array (
				                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                   'module' => 'HOTEL',
				                   'hotel_id' => 0,
				                   'inter_id' => $inter_id
			                   ) );
			$this->db->update ( 'hotel_config', array (
				'param_value' => $update_count ['param_value'] + $one_count
			) );
		}
		$handle_num = 0;
		$handle_orders = '(速八)oo-';
		$this->load->model ( 'hotel/pms/Suba_hotel_model', 'pms' );
		$now = time ();
		foreach ( $orderlist as $lt ) {
			$new_status = $this->pms->update_web_order ( $inter_id, $lt, $pms_set ); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
				 */
			if ($new_status == 3) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			} else if ($new_status == 4 || $new_status == 5 || $new_status == 8) {
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num ++;
			}
		}
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		
		$this->db->insert ( 'weixin_text', array (
			'content' => $handle_orders,
			'edit_date' => date ( 'Y-m-d H:i:s' )
		) );
		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => $now,
			'inter_id' => $inter_id,
			'service_type' => 'suba',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_update',
			'openid'=>'gang',
			'wait_time'=>$wait_time
		) );
		
		$this->load->model('common/Webservice_model');
		$this->Webservice_model->add_webservice_record($inter_id, 'suba', $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], '', $handle_orders,'order_batch_update', $now, microtime (), 'gang');
		
		echo '(速八)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if ($handle_orders)
			echo '订单号：' . $handle_orders;
		if (! empty ( $debug )) {
			exit ();
		}
	}

	function update_lowest_yibo() {
		// $this->db->where ( 'inter_id', 'a441098524' );
		// $lowest = $this->db->get ( 'hotel_lowest_price' )->result_array ();
		// $has_h = array ();
		// foreach ( $lowest as $l ) {
		// $has_h [] = $l ['hotel_id'];
		// }
		$db_read=$this->load->database('iwide_r1',true);
		$db_read->where ( 'inter_id', 'a441098524' );
		$db_read->where ( 'hotel_id >', 0 );
		$result = $db_read->get ( 'hotel_additions' )->result_array ();
		$this->load->model ( 'hotel/pms/Yibo_hotel_model', 'yibo' );
		$startdate = date ( 'Ymd' );
		$enddate = date ( 'Ymd', strtotime ( '+ 5 day', time () ) );
		$tmp = array (
			'inter_id' => 'a441098524',
			'update_time' => date ( 'Y-m-d H:i:s' )
		);
		foreach ( $result as $r ) {
			// if (! in_array ( $r ['hotel_id'], $has_h )) {
			echo $r ['hotel_web_id'] . ',';
			$low = $this->yibo->get_web_room_status ( $r ['hotel_web_id'], $startdate, $enddate, '' );
			if (! empty ( $low ['Data'] ['LowestRoomRate'] )) {
				$tmp ['hotel_id'] = $r ['hotel_id'];
				$tmp ['lowest_price'] = $low ['Data'] ['LowestRoomRate'];
				$this->db->replace ( 'hotel_lowest_price', $tmp );
			}
			// }
		}
	}
	function cancel_nopay_order(){
		$db_read=$this->load->database('iwide_r1',true);
		$this->load->model ( 'common/Enum_model' );
		$update_count = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_DEAL_NUM', 1, $inter_id );
		$update_count = empty($update_count['nopay_order_cancel'])?30:$update_count['nopay_order_cancel'];
		$limit_time=time()-900;
		$db_read->limit(1);
		$db_read->where(array('status'=>9,'isdel'=>0,'order_time <='=>$limit_time,'order_time >'));
		$orders=$db_read->get('hotel_orders')->result_array();
		var_dump($orders);exit;
		foreach ($orders as $o){
			$info = $this->Order_model->cancel_order ( $o['inter_id'], array (
				'orderid' => $o['orderid'],
				'cancel_status' => 5,
				'no_tmpmsg' => 1,
				'delete' => 2,
				'idetail' => array (
					'i'
				)
			) );
			if (isset($this->pmsa))
				unset($this->pmsa);
		}
	}
	function price() {
		exit ();
		$db_read=$this->load->database('iwide_r1',true);
		set_time_limit ( 0 );
		$sql = 'select orderid,allprice,id from iwide_hotel_order_items where id <=77808';
		$orders = $db_read->query ( $sql )->result_array ();
		$sql = 'SELECT orderid,coupon_favour FROM `iwide_hotel_order_additions` WHERE `coupon_favour` > 0';
		$additions = $db_read->query ( $sql )->result_array ();
		$add_favour = array ();
		foreach ( $additions as $a ) {
			$add_favour [$a ['orderid']] = $a;
		}
		$items = array ();
		foreach ( $orders as $o ) {
			$items [$o ['orderid']] [] = $o;
		}
		$updata = array ();
		foreach ( $items as $k => $o ) {
			$total_favour = empty ( $add_favour [$k] ) ? 0 : $add_favour [$k] ['coupon_favour'];
			$avg_favour = intval ( $total_favour / count ( $o ) );
			$extra_favour = $total_favour - ($avg_favour * count ( $o ));
			$i = 0;
			foreach ( $o as $oo ) {
				if (strpos ( $oo ['allprice'], ',' ) !== false || $total_favour) {
					$iprice = array_sum ( explode ( ',', $oo ['allprice'] ) ) - $avg_favour;
					if ($i == 0) {
						$iprice -= $extra_favour;
					}
					$i ++;
					// echo $oo['id'];echo '<br >';
					// echo $oo['allprice'];echo '<br >';
					// echo $iprice;echo '<br >';break;
					// $tmp=array('id'=>$oo['id'],'iprice'=>$iprice);
					// $updata[]=$tmp;
					$this->db->where ( array (
						                   'id' => $oo ['id']
					                   ) );
					$this->db->update ( 'hotel_order_items', array (
						'iprice' => $iprice
					) );
				}
			}
		}
		// $this->db->update_batch('hotel_order_items', $updata, 'id');
		// var_dump($updata);
		echo 'ok';
	}
	function tmp365() {
		$db_read=$this->load->database('iwide_r1',true);
		$sql = 'SELECT * FROM `365temp` WHERE `status` = 1';
		$tmps = $db_read->query ( $sql )->result_array ();
		foreach ( $tmps as $t ) {
			$this->db->where ( array (
				                   'id' => $t ['local_item_id'],
				                   'inter_id' => 'a445223616'
			                   ) );
			$this->db->update ( 'hotel_order_items', array (
				'startdate' => $t ['startdate'],
				'enddate' => $t ['enddate'],
				'iprice' => $t ['iprice']
			) );
		}
	}

	public function update_web_orders_yumeng_fix() {exit;
		set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a445223616';
		$db_read->where ( array (
			                   'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'hotel_id >' => 0
		                   ) );
		$hotel_pms_set = $db_read->get_where ( 'hotel_additions' )->result_array ();
		$pms_sets = array ();
		foreach ( $hotel_pms_set as $hps ) {
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id join 365temp t on t.orderid=oa.web_orderid where t.status = 0 LIMIT 0,100";
		$orderlist = $db_read->query ( $sql )->result_array ();
		if(empty($orderlist))
			exit('已全部查询');
		$debug = $this->input->get ( 'debug' );
		if (! empty ( $debug )) {
			var_dump ( $orderlist );
		}
		foreach ( $orderlist as $k => $order ) {
			$db_read->select ( '*,id sub_id' );
			$db_read->where ( array (
				                   'inter_id' => $inter_id,
				                   'orderid' => $order ['orderid']
			                   ) );
			$orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
			$orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
		}

		$handle_num = 0;
		$handle_orders = '(365云盟)oo-';
		$this->load->model ( 'hotel/pms/Zhuzhe_hotel_model', 'zhuzhe_pms' );
		$this->load->model ( 'hotel/pms/Huayi_hotel_model', 'huayi_pms' );
		$fail_count=0;
		$change_count=0;
		$stay_count=0;
		foreach ( $orderlist as $lt ) {
			switch ($pms_sets [$lt ['hotel_id']] ['pms_type']) {
				case 'zhuzhe' :
					$new_status = $this->zhuzhe_pms->update_web_order_suball_fix ( $inter_id, $lt, $pms_sets [$lt ['hotel_id']] ); // 更新订单状态,返回新的状态
					break;
				case 'huayi' :
					$room_codes = json_decode ( $lt ['room_codes'], TRUE );
					$lt ['first_detail'] ['room_id'] = key ( $room_codes );
					$new_status = $this->huayi_pms->update_web_order_sub_fix ( $inter_id, $lt, array (
						'pms_set' => $pms_sets [$lt ['hotel_id']]
					) ); // 更新订单状态,返回新的状态
					break;
				default :
					break;
			}
			$this->db->query("update 365temp set status = $new_status where orderid = '".$lt['web_orderid']."'");
			switch($new_status){
				case 1:$change_count++;break;
				case 2:$stay_count++;break;
				case 3:$fail_count++;break;
			}
		}

		$this->db->insert ( 'webservice_record', array (
			'send_content' => '',
			'receive_content' => $handle_orders,
			'record_time' => time (),
			'inter_id' => $inter_id,
			'service_type' => 'yunmeng',
			'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type' => 'order_batch_fix'
		) );
		echo '(365云盟)本次处理订单<br />';
		echo '已更改数据订单：'.$change_count.'条<br />';
		echo '不需更改数据订单：'.$change_count.'条<br />';
		echo '获取信息失败订单：'.$change_count.'条<br />';
	}
	function bugging(){
		$this->load->model('member/Member_card_model');
		$model=$this->Member_card_model;
		var_dump($model);
// 		var_dump($model->_shard_db()->list_fields('member_card_order'));
		var_dump(get_class_methods($model));
	}

	//锦江订单自动更新
	public function update_web_orders_jinjiang() {

		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a464177542';
		$service_type = 'jinjiang';
		$hotel_name = '锦江';
		$model_f = 'hotel/pms/Jinjiang_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}

		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yumeng' );
	}

	public function update_web_orders_beyondh(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_beyondh' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a464919542';
		$service_type = 'beyondh';
		$hotel_name = '清沐';
		$model_f = 'hotel/pms/Beyondh_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	public function update_web_orders_yage(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yage' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a465195239';
		$service_type = 'argyle';
		$hotel_name = '雅阁';
		$model_f = 'hotel/pms/Argyle_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	//定时生成清沐价格代码缓存
	public function update_redis_qm_pricecode(){
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a464919542';
		$service_type = 'beyondh';
		$hotel_name = '清沐';

		$db_read->where ( array (
           'param_name' => 'WEB_REDIS_UPDATE_COUNT',
           'module' => 'HOTEL',
           'hotel_id' => 0,
           'inter_id' => $inter_id
       ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 5 : intval ( $one_count );

		$db_read->select('hotel_web_id');
		$db_read->where ( array (
           'inter_id' => $inter_id,
           'pms_type' => $service_type,
           'hotel_id >' =>0
    	));
		$list = $db_read->from ( 'hotel_additions' )->limit($one_count, $update_count['param_value'])->get()->result_array ();

		if(count($list) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_REDIS_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_REDIS_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}

		$this->load->model('hotel/pms/Beyondh_hotel_model', 'pms');
		$startdate = date ( 'Y-m-d' );
		$enddate = date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) );

		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($inter_id, 0, $service_type, array(
			'web_member_list',
		), 1, 'w2l');

		$web_member_list = array();
		if(!empty($web_reflect['web_member_list'])){
			$web_member_str = '';
			foreach($web_reflect['web_member_list'] as $v){
				$web_member_str .= ',' . $v;
			}
			$web_member_str = substr($web_member_str, 1);
			$web_member_list = explode(',', $web_member_str);
		}
		array_push($web_member_list, '');
		$search_params=array(
			'member_levels'=>$web_member_list,
			'only_physical'=>false,
			'recache'=>true,
		);
		foreach($list as $value){
			//普通房价
			$this->pms->searchHotelById($value['hotel_web_id'], $startdate, $enddate, $search_params, $inter_id);
		}

		echo '(' . $hotel_name . ')本次已更新价格代码缓存 ' . count($list) . ' 条。<br />';
	}

	//定时生成清沐价格代码缓存
	public function update_redis_qm_clockrate(){
		set_time_limit(0);
		$inter_id = 'a464919542';
		$service_type = 'beyondh';
		$hotel_name = '清沐';
		$db_read=$this->load->database('iwide_r1',true);

		$db_read->where ( array (
			                   'param_name' => 'WEB_REDIS_CLOCK_UPDATE_COUNT',
			                   'module' => 'HOTEL',
			                   'hotel_id' => 0,
			                   'inter_id' => $inter_id
		                   ) );
		$update_count = $db_read->get ( 'hotel_config' )->row_array ();
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 5 : intval ( $one_count );

		$db_read->select('hotel_web_id');
		$db_read->where ( array (
			                   'inter_id' => $inter_id,
			                   'pms_type' => $service_type,
			                   'hotel_id >' =>0
		                   ));
		$list = $db_read->from ( 'hotel_additions' )->limit($one_count, $update_count['param_value'])->get()->result_array ();

		if(count($list) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_REDIS_CLOCK_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_REDIS_CLOCK_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}

		$this->load->model('hotel/pms/Beyondh_hotel_model', 'pms');
		$startdate = date ( 'Y-m-d' );
		$enddate = date ( 'Y-m-d', strtotime ( '+ 1 day', time () ) );

		$this->load->model('common/Webservice_model');
		$web_reflect = $this->Webservice_model->get_web_reflect($inter_id, 0, $service_type, array(
			'web_member_list',
		), 1, 'w2l');

		$web_member_list = array();
		if(!empty($web_reflect['web_member_list'])){
			$web_member_str = '';
			foreach($web_reflect['web_member_list'] as $v){
				$web_member_str .= ',' . $v;
			}
			$web_member_str = substr($web_member_str, 1);
			$web_member_list = explode(',', $web_member_str);
		}
		array_push($web_member_list, '');
		foreach($list as $value){
			//时租房价
			$this->pms->getHourPrice($value['hotel_web_id'], $startdate, $web_member_list, $inter_id, true);
		}

		echo '(' . $hotel_name . ')本次已更新价格代码缓存 ' . count($list) . ' 条。<br />';
	}

	//批量取消微信超时未支付订单
	function deal_order_queues(){
		set_time_limit ( 0 );
		$this->load->model ( 'hotel/Order_queues_model' );
		$lists = $this->Order_queues_model->get_queues();//微信超时未支付订单列表
		$this->load->model ( 'hotel/Order_model' );
		$this->load->library('MYLOG');
		$faildcout = 0;
		$succcout = 0;
		$deal_list =array();
        $this->load->library ( 'Cache/Redis_proxy', array (
				'not_init' => FALSE,
				'module' => 'common',
				'refresh' => FALSE,
				'environment' => ENVIRONMENT
		), 'redis_proxy' );
        // $ok = $this->redis_proxy->setNX ( 'hotel_unpaid_orderdeal_lock', 'on' );
        $rand=mt_rand();
        $ok = $this->redis_proxy->set('hotel_unpaid_orderdeal_lock', $rand, array('nx', 'ex' => 120));
		
		if (! $ok) {
			$last_time = $this->redis_proxy->get ( 'hotel_unpaid_orderdeal_lasttime' );
			if ($last_time + 60 < time ()) { // 锁住了，并且上次请求超过1分钟
				echo '有其他任务正在运行，已经超过1分钟，报警ing！';
				MYLOG::w ( '有其他任务正在运行，已经超过1分钟，报警ing！', 'order_queues' );
				exit ();
			}
			echo '有其他任务正在运行，请稍候！';
			MYLOG::w ( '有其他任务正在运行，请稍候！', 'order_queues' );
			exit ();
		}
		// 记录请求时间
		$this->redis_proxy->set ( 'hotel_unpaid_orderdeal_lasttime', time () );
		foreach ($lists as $key => $value) {
			$isdeal = true;
			$order = $this->Order_model->get_order($value['inter_id'],array('orderid' => $value['orderid']));
			$order = $order[0];
			if($order['paid'] == 0 && ($order['paytype']=='weixin' || $order['paytype']=='weifutong' || $order['paytype']=='lakala' || $order['paytype']=='lakala_y' || $order['paytype']=='unionpay') && $order['status'] == 9){//微信未支付
				$info = $this->Order_model->cancel_order ( $value['inter_id'], array (
					'only_openid' => $order['openid'],
					'orderid' => $value['orderid'],
					'cancel_status' => 11,
					'idetail' => array (
						'i'
					)
				) );
				if($info['s']==0){//取消失败
					$isdeal = false;
					$faildcout++;
					$value['errmsg'] = $info['errmsg'];
					MYLOG::w(json_encode($value),'order_queues','false');
				}else{
					//分账支付，主动关闭订单 add by yu 2017-06-05
					if($order['paytype']=='weixin'){
						$this->load->model('iwidepay/Iwidepay_model');
						$this->Iwidepay_model->close_order($value['orderid']);
					}

					$succcout++;
				}
				unset($this->pmsa);
			}
			if($isdeal){//标记已处理
				$this->Order_queues_model->cancel_queue($value['inter_id'],$value['hotel_id'],$value['orderid']);
			}
			//记录操作次数
			$this->Order_queues_model->set_deal_time($value['inter_id'],$value['hotel_id'],$value['orderid']);
			$deal_list[] = $value['orderid'];
		}
        if($this->redis_proxy->get('hotel_unpaid_orderdeal_lock')==$rand)
            $this->redis_proxy->del ( 'hotel_unpaid_orderdeal_lock' );
		MYLOG::w(json_encode($deal_list),'order_queues','list');
		echo '本次处理订单共 ' . count($lists) . ' 条，取消成功的有 '.$succcout.' 条，取消失败的有 '. $faildcout.' 条<br />';
	}


	public function update_web_orders_yasiteiw(){
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yasiteiw' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a472731996';
		$service_type = 'yasiteiw';
		$hotel_name = '雅斯特';
		$model_f = 'hotel/pms/Yasiteiw_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	public function update_web_orders_daisi(){
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_daisi' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a468209719';
		$service_type = 'shiji';
		$hotel_name = '戴斯';
		$model_f = 'hotel/pms/Shiji_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}


	public function update_web_orders_yinzuo(){
		// redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yinzuo' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a476756979';
		$service_type = 'xiruan';
		$hotel_name = '银座';
		$model_f = 'hotel/pms/Xiruan_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	public function update_lowest_yinzuo(){
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);

		$this->load->helper('common');
		$inter_id = 'a476756979';
		$service_type = 'xiruan';
		$hotel_name = '银座';

		$db_read->where(array(
			                 'param_name' => 'LOWEST_NEXT_RUNTIME',
			                 'inter_id'   => $inter_id,
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
		                 ));
		$next_run = $db_read->get('hotel_config')->row_array();
		if(!$next_run){
			$next_run['param_value'] = time();
		}

		if($next_run['param_value']>time()){
			exit('不在执行时间内');
		}
		$this->load->model('hotel/Hotel_model');

		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'LOWEST_UPDATE_COUNT',
			                 'inter_id'   => $inter_id,
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty($one_count) ? 20 : intval($one_count);

		$db_read->where(array(
			                 'inter_id' => $inter_id,
		                     'status'=>1,
			                 //			                 'hotel_id >' => 0
		                 ));
		$hotel_result = $db_read->from('hotels')->order_by('hotel_id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		$hotels = array();
		foreach($hotel_result as $v){
			$hotels [$v['hotel_id']] = $v;
		}
		if(count($hotels) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'LOWEST_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));

			$this->db->where(array(
				                 'param_name' => 'LOWEST_NEXT_RUNTIME',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => mktime(1,1,0,date('m'),date('d')+1,date('Y')),
			));
			if(empty($hotels)){
				exit();
			}
		} else{
			$this->db->where(array(
				                 'param_name' => 'LOWEST_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$hotel_ids = array_keys($hotels);


		$_map = [
			'inter_id' => $inter_id,
			'type'     => 'room',
			'status'=>1,
		];
		$rooms = $db_read->from('hotel_rooms')->where($_map)->where_in('hotel_id', $hotel_ids)->get()->result_array();
		if($rooms){
			foreach($rooms as $v){
				$hotels[$v['hotel_id']]['rooms'][] = $v;
			}
		}
		$condit = array(
			'startdate' => date('Ymd'),
			'enddate'   => date('Ymd', time() + 86400),
		    'openid'=>'',
			//			'member_level' => $this->member_lv
		);

		$this->load->model('hotel/Member_model');
		$level_list = $this->Member_model->get_member_levels($inter_id);

		$debug = $this->input->get('debug');
		if(!empty($debug)){
			var_dump($hotels);
			var_dump($level_list);
		}

		if(empty($level_list)){
			exit();
		}

		$level_ids = array_keys($level_list);
		$lvl_count = count($level_ids);
		$handle = [];

		statistic('R1');

		foreach($hotels as $k => $v){
			if(!empty($v['rooms'])){
				$this->load->library('PMS_Adapter', array(
					'inter_id' => $inter_id,
					'hotel_id' => $k
				), 'pmsa');
				$lowest=[];

				for($i = 0; $i < $lvl_count; $i++){
					$condit['member_level'] = $level_ids[$i];
					$result = $this->pmsa->get_rooms_change($v['rooms'], array(
						'inter_id' => $inter_id,
						'hotel_id' => $k
					), $condit);
					if($result){
						$lowest[] = '【'.$level_list[$level_ids[$i]].'：'.lowest_from_redis($inter_id,$k,$level_ids[$i],$condit['startdate'],$condit['enddate']).'】';
					}
				}
				$handle[]='【'.$k.'】'.$v['name'].'｛'.implode('',$lowest).'｝';
				unset($this->pmsa);
			}
		}
		statistic('R2');

		$wait_time = statistic('R1','R2');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => count($handle),
			'record_time'     => time(),
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'lowest_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));

		if($handle){
			echo '已处理｛' . $hotel_name . '｝共'.count($handle).'家酒店最低价：<br />' . implode('<br>', $handle);
		}
		if(!empty ($debug)){
			exit ();
		}

	}

	//碧桂园房态缓存
	public function update_ftai_biguiyuan(){
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);

		$this->load->helper('common');
		$inter_id = 'a421641095';
		$service_type = 'zhongruan';
		$hotel_name = '碧桂园';
		$day = $this->input->get('day');
		$day = empty($day) ? 0 : intval($day);
		$offset=$this->input->get('offset');
		$offset=intval($offset);
		$offset>1 or $offset=1;
        $hotel_id=intval($this->input->get('hotel'));

        if (!$hotel_id){
		$db_read->where(array(
			                 'param_name' => 'FTAI_UPDATE_COUNT',
			                 'inter_id'   => $inter_id,
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'priority'   => $day
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty($one_count) ? 5 : intval($one_count);

		$db_read->where(array(
			                 'inter_id' => $inter_id,
			                 'status' => 1
		                 ));
		$hotel_result = $db_read->from('hotels')->order_by('hotel_id', 'desc')->limit($one_count, $update_count['param_value'])->get()->result_array();
        }else{
            $this->db->where(array(
		            'inter_id' => $inter_id,
		            'status' => 1,
		            'hotel_id'=>$hotel_id
		    ));
		    $hotel_result = $this->db->from('hotels')->get()->result_array();
        }
		$hotels = array();
		foreach($hotel_result as $v){
			$hotels [$v['hotel_id']] = $v;
		}
        if (!$hotel_id){
            if(count($hotels) < $one_count){
                $this->db->where(array(
                                     'param_name' => 'FTAI_UPDATE_COUNT',
                                     'module'     => 'HOTEL',
                                     'hotel_id'   => 0,
                                     'inter_id'   => $inter_id,
                                     'priority'   => $day
                                 ));
                $this->db->update('hotel_config', array(
                    'param_value' => 0
                ));
                if(empty($hotels)){
                	exit();
                }
            } else{
                $this->db->where(array(
                                     'param_name' => 'FTAI_UPDATE_COUNT',
                                     'module'     => 'HOTEL',
                                     'hotel_id'   => 0,
                                     'inter_id'   => $inter_id,
                                     'priority'   => $day
                                 ));
                $this->db->update('hotel_config', array(
                    'param_value' => $update_count ['param_value'] + $one_count
                ));
            }
        }
		$hotel_ids = array_keys($hotels);
		$_map = [
			'inter_id' => $inter_id,
			'type'     => 'room',
			'status'     => 1
		];
		$rooms = $db_read->from('hotel_rooms')->where($_map)->where_in('hotel_id', $hotel_ids)->get()->result_array();
		if($rooms){
			foreach($rooms as $v){
				$hotels[$v['hotel_id']]['rooms'][] = $v;
			}
		}

		$this->load->model('hotel/Member_model');

		$startdate = date ( 'Ymd' ,time()+86400*$day);
		$starttime = strtotime($startdate);
		$enddate = date ( 'Ymd', $starttime+(86400*$offset));

		$condit = array(
			'startdate' => $startdate,
			'enddate'   => $enddate,
			'member_privilege' => $this->Member_model->level_privilege($inter_id),
			'recache' => true
		);
		
		$condit['openid'] = 'oGaHQjpWjjxQa9jLh0QeShyJZewE';
		
		$level_list = $this->Member_model->get_member_levels($inter_id);

		$debug = $this->input->get('debug');
		if(!empty($debug)){
			var_dump($hotels);
			var_dump($level_list);
		}

		if(empty($level_list)){
			exit();
		}

		$level_ids = array_keys($level_list);
		$lvl_count = count($level_ids);
		$handle = [];

		statistic('R1');
		foreach($hotels as $k => $v){
			if(!empty($v['rooms'])){
				$this->load->library('PMS_Adapter', array(
					'inter_id' => $inter_id,
					'hotel_id' => $k
				), 'pmsa');
				$lowest=[];
				
				for($i = 0; $i < $lvl_count; $i++){
					unset($condit['recache']);
					if($i==0){
						$condit['recache']=1;
					}
					$condit['member_level'] = $level_ids[$i];
					$result = $this->pmsa->get_rooms_change($v['rooms'], array(
						'inter_id' => $inter_id,
						'hotel_id' => $k
					), $condit);
					if($result){
						$lowest[] = '【'.$level_list[$level_ids[$i]].'：'.lowest_from_redis($inter_id,$k,$level_ids[$i],$condit['startdate'],$condit['enddate']).'】';
					}
				}
				$handle[]='【'.$k.'】'.$v['name'].'｛'.implode('',$lowest).'｝';
				unset($this->pmsa);
			}
		}
		statistic('R2');

		$wait_time = statistic('R1','R2');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => count($handle),
			'record_time'     => time(),
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'biguiyuan_ftai_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));

		if($handle){
			echo '已处理｛' . $hotel_name . '｝共'.count($handle).'家酒店最低价：<br />' . implode('<br>', $handle);
		}

		if(!empty ($debug)){
			exit ();
		}

	}

	public function update_web_orders_hfjt(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_hfjt' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a479866040';
		$service_type = 'qianlima';
		$hotel_name = '弘峰集团';
		$model_f = 'hotel/pms/Qianlima_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'inter_id'   => $inter_id
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			                 'inter_id'   => $inter_id,
			                 'hotel_id >' => 0
		                 ));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}

		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				                 'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

    //优程订单自动更新
    public function update_web_orders_youcheng() {
        set_time_limit ( 0 );
		$db_read=$this->load->database('iwide_r1',true);
        $inter_id = 'a480304439';
        $this->load->model ( 'hotel/Hotel_config_model' );

        $db_read->where ( array (
            'param_name' => 'WEB_ORDER_UPDATE_COUNT',
            'module' => 'HOTEL',
            'hotel_id' => 0,
            'inter_id' => $inter_id
        ) );
        $update_count = $db_read->get ( 'hotel_config' )->row_array ();
        $one_count = $this->input->get ( 'oc' );
        $one_count = empty ( $one_count ) ? 30 : intval ( $one_count );
        $db_read->where ( array (
            'inter_id' => $inter_id,
            'hotel_id >' => 0
        ) );
        $hotel_pms_set = $db_read->get_where ( 'hotel_additions' )->result_array ();
        $pms_sets = array ();
        foreach ( $hotel_pms_set as $hps ) {
            $pms_sets [$hps ['hotel_id']] = $hps;
        }
        $sql = "select oa.*,o.* from
		(SELECT * FROM `iwide_hotel_orders` WHERE inter_id = '$inter_id' and isdel=0 and status in (0,1,2,4) and handled=0  ORDER BY `id` asc
		) o join
		(select * from iwide_hotel_order_additions where web_orderid is not null and web_orderid !='' and inter_id='$inter_id') oa
		on o.orderid=oa.orderid and o.inter_id=oa.inter_id ";
        echo $sql;
        $orderlist = $db_read->query ( $sql )->result_array ();

        foreach ( $orderlist as $k => $order ) {
            $db_read->select ( '*,id sub_id' );
            $db_read->where ( array (
                'inter_id' => $inter_id,
                'orderid' => $order ['orderid']
            ) );
            $orderlist [$k] ['order_details'] = $db_read->get ( 'hotel_order_items' )->result_array ();
            $orderlist [$k] ['first_detail'] = empty ( $orderlist [$k] ['order_details'] ) ? array () : $orderlist [$k] ['order_details'] [0];
        }
        $debug = $this->input->get ( 'debug' );
        if (! empty ( $debug )) {
            var_dump ( $orderlist );
        }
        if (count ( $orderlist ) < $one_count) {
            $this->db->where ( array (
                'param_name' => 'WEB_ORDER_UPDATE_COUNT',
                'module' => 'HOTEL',
                'hotel_id' => 0,
                'inter_id' => $inter_id
            ) );
            $this->db->update ( 'hotel_config', array (
                'param_value' => 0
            ) );
        } else {
            $this->db->where ( array (
                'param_name' => 'WEB_ORDER_UPDATE_COUNT',
                'module' => 'HOTEL',
                'hotel_id' => 0,
                'inter_id' => $inter_id
            ) );
            $this->db->update ( 'hotel_config', array (
                'param_value' => $update_count ['param_value'] + $one_count
            ) );
        }
        $handle_num = 0;
        $handle_orders = '(优程)oo-';
        $this->load->model ( 'hotel/pms/youcheng_hotel_model', 'pms' );
        $now = time ();
        foreach ( $orderlist as $lt ) {
            $new_status = $this->pms->update_web_order ( $inter_id, $lt, $pms_sets[$lt['hotel_id']] ); // 更新订单状态,返回新的状态
            /*
             * if ($new_status == 1 || $new_status == 2) {
             * } else
             */
            if ($new_status == 2 || $new_status == 3 || $new_status == 5) {
                $handle_orders .= ',' . $lt ['orderid'];
                $handle_num ++;
            }
        }
        $mirco_time = microtime ();
        $mirco_time = explode ( ' ', $mirco_time );
        $wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
        $this->db->insert ( 'webservice_record', array (
            'send_content' => '',
            'receive_content' => $handle_orders,
            'record_time' => $now,
            'inter_id' => $inter_id,
            'service_type' => 'youcheng',
            'web_path' => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
            'record_type' => 'order_batch_update',
            'openid'=>'gang',
            'wait_time'=>$wait_time
        ) );
        echo '(优程)本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
        if ($handle_orders)
            echo '订单号：' . $handle_orders;
        if (! empty ( $debug )) {
            exit ();
        }
        // redirect ( 'http://iaka.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_yumeng' );
    }

	public function update_web_orders_hengda(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_hengda' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a468919145';
		$service_type = 'xiruaniw';
		$hotel_name = '恒大集团';
		$model_f = 'hotel/pms/Xiruaniw_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}

		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	public function update_web_orders_junting(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_junting' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a483407432';
		$service_type = 'yuheng';
		$hotel_name = '君亭';
		$model_f = 'hotel/pms/Yuheng_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}

		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_baideng(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_baideng' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a484191907';
		$service_type = 'zhongruaniw';
		$hotel_name = '拜登';
		$model_f = 'hotel/pms/Zhongruaniw_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	//拜登房态缓存
	public function update_ftai_baideng(){
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);

		$this->load->helper('common');
		$inter_id = 'a484191907';
		$service_type = 'baideng';
		$hotel_name = '拜登集团';
		$day = $this->input->get('day');
		$day = empty($day) ? 0 : intval($day);

		$db_read->where(array(
			                 'param_name' => 'FTAI_UPDATE_COUNT',
			                 'inter_id'   => $inter_id,
			                 'module'     => 'HOTEL',
			                 'hotel_id'   => 0,
			                 'priority'   => $day
		                 ));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty($one_count) ? 5 : intval($one_count);

		$db_read->where(array(
			                 'inter_id' => $inter_id,
			                 'status' => 1
		                 ));
		$hotel_result = $db_read->from('hotels')->order_by('hotel_id', 'desc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		$hotels = array();
		foreach($hotel_result as $v){
			$hotels [$v['hotel_id']] = $v;
		}
		if(count($hotels) < $one_count){
			$this->db->where(array(
				                 'param_name' => 'FTAI_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id,
			                 	 'priority'   => $day
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
			if(empty($hotels)){
				exit();
			}
		} else{
			$this->db->where(array(
				                 'param_name' => 'FTAI_UPDATE_COUNT',
				                 'module'     => 'HOTEL',
				                 'hotel_id'   => 0,
				                 'inter_id'   => $inter_id,
			                 	 'priority'   => $day
			                 ));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$hotel_ids = array_keys($hotels);
		$_map = [
			'inter_id' => $inter_id,
			'type'     => 'room',
			'status'     => 1
		];
		$rooms = $db_read->from('hotel_rooms')->where($_map)->where_in('hotel_id', $hotel_ids)->get()->result_array();
		if($rooms){
			foreach($rooms as $v){
				$hotels[$v['hotel_id']]['rooms'][] = $v;
			}
		}

		$this->load->model('hotel/Member_model');

		$startdate = date ( 'Ymd' ,strtotime ( "+ $day day", time() ) );
		$starttime = strtotime($startdate);
		$enddate = date ( 'Ymd', strtotime ( "+ 7 day", $starttime ) );

		$condit = array(
			'startdate' => $startdate,
			'enddate'   => $enddate,
			'member_privilege' => $this->Member_model->level_privilege($inter_id),
			'recache' => true
		);

		$condit['openid'] = 'o5TKUt3AC6gtDiJEEhC22VkZNX_o';
		
		$level_list = $this->Member_model->get_member_levels($inter_id);

		$debug = $this->input->get('debug');
		if(!empty($debug)){
			var_dump($hotels);
			var_dump($level_list);
		}

		if(empty($level_list)){
			exit();
		}

		$level_ids = array_keys($level_list);
		$lvl_count = count($level_ids);
		$handle = [];

		statistic('R1');
		foreach($hotels as $k => $v){
			if(!empty($v['rooms'])){
				$this->load->library('PMS_Adapter', array(
					'inter_id' => $inter_id,
					'hotel_id' => $k
				), 'pmsa');
				$lowest=[];
				
				for($i = 0; $i < $lvl_count; $i++){
					unset($condit['recache']);
					if($i==0){
						$condit['recache']=1;
					}
					$condit['member_level'] = $level_ids[$i];
					$result = $this->pmsa->get_rooms_change($v['rooms'], array(
						'inter_id' => $inter_id,
						'hotel_id' => $k
					), $condit);
					if($result){
						$lowest[] = '【'.$level_list[$level_ids[$i]].'：'.lowest_from_redis($inter_id,$k,$level_ids[$i],$condit['startdate'],$condit['enddate']).'】';
					}
				}
				$handle[]='【'.$k.'】'.$v['name'].'｛'.implode('',$lowest).'｝';
				unset($this->pmsa);
			}
		}
		statistic('R2');

		$wait_time = statistic('R1','R2');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => count($handle),
			'record_time'     => time(),
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'baideng_ftai_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));

		if($handle){
			echo '已处理｛' . $hotel_name . '｝共'.count($handle).'家酒店最低价：<br />' . implode('<br>', $handle);
		}

		if(!empty ($debug)){
			exit ();
		}

	}

	//PMS请求失败定时重发
    public function add_web_bill_again(){
    	set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
    	$this->load->library('Cache/Redis_proxy',array(
    	    			'not_init'=>FALSE,
    	    			'module'=>'common',
    	    			'refresh'=>FALSE,
    	    			'environment'=>ENVIRONMENT
    	    	),'redis_proxy');
    	$ok = $this->redis_proxy->setNX('add_web_bill_again_lock','on');

    	if(!$ok){
			$last_time  = $this->redis_proxy->get('add_web_bill_again_lasttime');
			if($last_time+600 < time()){//锁住了，并且上次请求超过10分钟
				echo '有其他任务正在运行，已经超过10分钟，报警ing！';exit;
			}
			echo '有其他任务正在运行，请稍候！';exit;
		}
		//记录请求时间
		$this->redis_proxy->set('add_web_bill_again_lasttime',time());
    	$db_read->where(array(
    						 'type' => 2,//pms订单入账待处理
    		                 'flag' => 2,
    		                 'oper_times<'   => 3
    	                 ));
    	$res = $db_read->from('hotel_order_queues')->order_by('id', 'asc')->limit(10,0)->get()->result_array();
    	$this->load->model ( 'hotel/Order_model' );
    	foreach($res as $k => $v){
    		$order = $this->Order_model->get_main_order ( $v ['inter_id'], array (
					'orderid' => $v ['orderid'],
					'idetail' => array (
							'i'
					)
			) );
			if (! empty ( $order )) {
				$order = $order [0];
				if($order['web_paid']!=1){
					$this->load->library('PMS_Adapter', array(
						'inter_id' => $v['inter_id'],
						'hotel_id' => $v['hotel_id']
					), 'pmsa');
					$ex_data = json_decode($v['ex_data'],true);
					$result = $this->pmsa->add_web_bill ( $order, array (
							'trans_no' => $v ['orderid'],
							'third_no' => $ex_data['third_no']
					) );
					$this->db->where ( array (
						'type' => 2,//pms订单入账待处理
					    'orderid' => $v['orderid'],
					    'hotel_id' => $v['hotel_id'],
					    'inter_id' => $v['inter_id']
					) );
					if($result){
						$this->db->update ( 'hotel_order_queues', array (
						    'oper_times' => $v['oper_times']+1,
						    'flag' => 1,
						    'update_time' => time()
						) );
					}else{
						$this->db->update ( 'hotel_order_queues', array (
						    'oper_times' => $v['oper_times']+1,
						    'update_time' => time()
						) );
					}
					unset($this->pmsa);
				}else{
					$this->db->where ( array (
						'type' => 2,//pms订单入账待处理
					    'orderid' => $v['orderid'],
					    'hotel_id' => $v['hotel_id'],
					    'inter_id' => $v['inter_id']
					) );
					$this->db->update ( 'hotel_order_queues', array (
					    'oper_times' => $v['oper_times']+1,
					    'flag' => 1,
					    'update_time' => time()
					) );
				}
			}else{
				$this->db->where ( array (
					'type' => 2,//pms订单入账待处理
				    'orderid' => $v['orderid'],
				    'hotel_id' => $v['hotel_id'],
				    'inter_id' => $v['inter_id']
				) );
				$this->db->update ( 'hotel_order_queues', array (
				    'oper_times' => $v['oper_times']+1,
				    'flag' => 1,
				    'update_time' => time()
				) );
			}
			
    	}
    	//遍历结束，解锁
		$this->redis_proxy->del('add_web_bill_again_lock');
		echo '完成，已处理共'.count($res).'条队列';

    }
    
    public function send_roomnight_levelup(){
		set_time_limit ( 0 );
		$db_read = $this->load->database ( 'iwide_r1', true );
		$type = 'roomnight_levelup';
		$this->load->library ( 'Cache/Redis_proxy', array (
				'not_init' => FALSE,
				'module' => 'common',
				'refresh' => FALSE,
				'environment' => ENVIRONMENT
		), 'redis_proxy' );
		$ok = $this->redis_proxy->setNX ( 'hotel_rn_lvup_lock', 'on' );
		
		if (! $ok) {
			$last_time = $this->redis_proxy->get ( 'hotel_rn_lvup_lasttime' );
			if ($last_time + 600 < time ()) { // 锁住了，并且上次请求超过10分钟
				echo '有其他任务正在运行，已经超过10分钟，报警ing！';
				MYLOG::w ( '有其他任务正在运行，已经超过10分钟，报警ing！', 'hotel/roomnight_queue', '_deal_log' );
				exit ();
			}
			echo '有其他任务正在运行，请稍候！';
			MYLOG::w ( '有其他任务正在运行，请稍候！', 'hotel/roomnight_queue', '_deal_log' );
			exit ();
		}
		// 记录请求时间
		$this->redis_proxy->set ( 'hotel_rn_lvup_lasttime', time () );
		
		$this->load->model ( 'hotel/member/Level_model' );
		$this->load->model ( 'hotel/Order_queues_model' );
		$max_oper_times = 3;
		$one_count = $this->input->get ( 'oc' );
		$one_count = empty ( $one_count ) ? 20 : intval ( $one_count );
		$res = $this->Order_queues_model->get_order_queues ( $type, 2, $max_oper_times, $one_count, 0 );
		$orderids = '';
		foreach ( $res as $k => $v ) {
			$tip = '';
			$order = json_decode ( $v ['ex_data'], TRUE );
			$order ['inter_id'] = $v ['inter_id'];
			$order ['hotel_id'] = $v ['hotel_id'];
			$room_night = isset ( $order ['rn'] ) ? $order ['rn'] : $this->Level_model->check_roomnight_rule ( $v ['inter_id'], $order );
			$dealed = NULL;
			$result = FALSE;
			if ($room_night !== FALSE) {
				$result = $this->Level_model->send_roomnight ( $v ['inter_id'], $v ['orderid'], $order, $v ['ident'], $room_night );
				if ($result) {
					$dealed = 1;
					$tip .= '成功';
				} else if ($v ['oper_times'] + 1 >= $max_oper_times) {
					$dealed = 3;
					$tip .= '失败，三次后不再处理';
				} else {
					$tip .= '失败，待再处理';
				}
			} else {
				$dealed = 1;
				$result = TRUE;
				$tip .= '完成，不符条件';
			}
			$this->Order_queues_model->deal_queue ( $v ['inter_id'], $v ['hotel_id'], $v ['orderid'], $type, $dealed, $v ['ident'],$tip );
			MYLOG::w ( $v ['orderid'] . '|' . $v ['ident'] . '|' . $tip . '|' . $dealed . '|' . json_encode ( $result ) . '|' . $room_night, 'hotel/roomnight_queue', '_deal_log' );
			$orderids .= '|' . $v ['orderid'] . '|' . $v ['ident'] . ':' . $tip;
		}
		// 遍历结束，解锁
		$this->redis_proxy->del ( 'hotel_rn_lvup_lock' );
		$s = '已处理共' . count ( $res ) . '条，订单号:' . substr ( $orderids, 1 );
		echo $s;
		MYLOG::w ( $s, 'hotel/roomnight_queue', '_deal_log' );
	}

	public function update_web_orders_wanxin(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_baideng' );
		$db_read=$this->load->database('iwide_r1',true);
		set_time_limit(0);
		$inter_id = 'a487576098';
		$service_type = 'lvyun';
		$hotel_name = '万信';
		$model_f = 'hotel/pms/Lvyun_hotel_model';
		$this->load->model('hotel/Hotel_config_model');

		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();

		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}

		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}

	function deal_distribution(){
	    	$orderids = array('Wg149673545077266'=>'a491374875');
	    	set_time_limit ( 0 );
	    	$result = array();
	    	$this->load->model('hotel/Order_model');
	    	foreach ($orderids as $orderid => $inter_id) {

		    	$order = $this->Order_model->get_main_order ( $inter_id, array (
						'orderid' => $orderid,
						'idetail' => array (
								'i'
						)
				) );
				$order = $order [0];


				$this->load->model ( 'distribute/Idistribution_model' );
				$this->load->model ( 'club/Club_list_model' );
				if($order['first_detail']['club_id']){
					$saler = $this->Club_list_model->get_club_by_id($inter_id,$order['first_detail']['club_id']);//获取分销id
				}
				$this->load->model ( 'distribute/Fans_model' );
				$fans = $this->Fans_model->get_fans_beloning($inter_id,$order ['openid']);
				$countod = 0;
				foreach ( $order ['order_details'] as $od ) {
					$countod++;
					// 获得相差天数
					$oddays = get_room_night($od ['startdate'],$od ['enddate'],'ceil',$od);//至少有一个间夜
					$I_params = array(
						'inter_id' => $inter_id, //公众号id
						'hotel_id' => $order ['hotel_id'], //酒店id
						'grade_openid' => $order ['openid'], //用户openid
						'now_time' => date('Y-m-d',$order ['order_time']), //下单时间
						'price_typeid' => $od['price_code'], //价格代码
						'pay_wayid' => $order['paytype'],//支付方式
						'order_amount' => $od['iprice'],//订单总金额
						'grade_amount' => $od['iprice'],//订单计算绩效部分的金额
						'days' => $oddays,//天数
						'order_id' => $order['web_orderid']? $order['web_orderid']:$od['orderid'],//订单号(如果有PMS订单号传PMS订单号)
						'grade_id' => $od['id'],//记录产生绩效的表的主键值
						'product' => $od['roomname'],//产品名称
						'istatus' => $od['istatus'],//订单状态
						'fans_hotel' => isset($fans->hotel_id)?$fans->hotel_id:$order ['hotel_id'],//粉丝所属酒店
						'fans_source' => isset($fans->source)?$fans->source:0,//粉丝归属
						'saler' => isset($saler['id'])? $saler['id']:0//分销员id
					);
					$this->Idistribution_model->get_best($I_params);
				}
				$result[$orderid] = '共'.$countod.'个子订单处理成功';
	    	}
	    	echo '处理完成<br>';
	    	var_dump($result);
	    }



    //社群客定时升级
    public function upgrade_club(){
        set_time_limit(0);
        $this->load->model ( 'hotel/user/User_notify_model' );
        $this->load->model ( 'club/Clubs_model' );
        $this->load->library('Cache/Redis_proxy',array(
            'not_init'=>FALSE,
            'module'=>'common',
            'refresh'=>FALSE,
            'environment'=>ENVIRONMENT
        ),'redis_proxy');
        $ok = $this->redis_proxy->setNX('upgrade_club_lock','on');

//        if(!$ok){
//            $last_time  = $this->redis_proxy->get('upgrade_club_lasttime');
//            if($last_time+600 < time()){//锁住了，并且上次请求超过10分钟
//                echo '有其他任务正在运行，已经超过10分钟，报警ing！';exit;
//            }
//            echo '有其他任务正在运行，请稍候！';exit;
//        }
//        记录请求时间
        $this->redis_proxy->set('upgrade_club_lasttime',time());
        $params = array(
            'status' => 1,
            'max_oper_times' => 4
        );
        $res = $this->User_notify_model->get_queues('club_member_levelup',$params);

        foreach($res as $k => $v){
            $ex_data = json_decode($v['ex_data']);
            $deal_res = $this->Clubs_model->upgrade_club($v['inter_id'],$v['ident'],$ex_data->to_lv);

            if($deal_res['code']==2){
                $this->User_notify_model->deal_queue($v['inter_id'],$v['ident'],'club_member_levelup',2,$v['sub_ident'],array('remark'=>'处理成功'));
            }elseif($deal_res['code']==1){
                if($v['oper_times']==2){
                    $this->User_notify_model->deal_queue($v['inter_id'],$v['ident'],'club_member_levelup',3,$v['sub_ident'],array('remark'=>$deal_res['message']));
                }else{
                    $this->User_notify_model->deal_queue($v['inter_id'],$v['ident'],'club_member_levelup',1,$v['sub_ident'],array('remark'=>$deal_res['message']));
                }

            }

        }
        //遍历结束，解锁
        $this->redis_proxy->del('upgrade_club_lock');
        echo '完成，已处理共'.count($res).'条队列';

    }
	
	public function update_ftai_jinling(){
    	
    	//http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_ftai_jinling?offset=2&oc=25
		//http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_ftai_jinling?day=3&offset=28&oc=20
  
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		
		$this->load->helper('common');
		$inter_id = 'a492669988';
		$service_type = 'xiruan3';
		$hotel_name = '金陵集团';
		$day = $this->input->get('day');
		$day = empty($day) ? 0 : intval($day);
		$offset=$this->input->get('offset');
		$offset=intval($offset);
		
		$db_read->where(array(
			'param_name' => 'FTAI_UPDATE_COUNT',
			'inter_id'   => $inter_id,
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'priority'   => $day,
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty($one_count) ? 5 : intval($one_count);
		
		$db_read->where(array(
			'inter_id' => $inter_id,
			'status' => 1
		));
		$hotel_result = $db_read->from('hotels')->order_by('hotel_id', 'desc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		$hotels = array();
		foreach($hotel_result as $v){
			$hotels [$v['hotel_id']] = $v;
		}
		if(count($hotels) < $one_count){
			$this->db->where(array(
				'param_name' => 'FTAI_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id,
				'priority'   => $day
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
			if(empty($hotels)){
				exit();
			}
		} else{
			$this->db->where(array(
				'param_name' => 'FTAI_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id,
				'priority'   => $day
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$hotel_ids = array_keys($hotels);
		$_map = [
			'inter_id' => $inter_id,
			'type'     => 'room',
			'status'     => 1
		];
		$rooms = $db_read->from('hotel_rooms')->where($_map)->where_in('hotel_id', $hotel_ids)->get()->result_array();
		if($rooms){
			foreach($rooms as $v){
				$hotels[$v['hotel_id']]['rooms'][] = $v;
			}
		}
		
		$this->load->model('hotel/Member_model');
		
		$startdate = date ( 'Ymd' ,time()+86400*$day);
		$starttime = strtotime($startdate);
		$enddate = date ( 'Ymd', $starttime+(86400*$offset));
		
		$condit = array(
			'startdate' => $startdate,
			'enddate'   => $enddate,
			'member_privilege' => $this->Member_model->level_privilege($inter_id),
			'recache' => true
		);
		
		$condit['openid'] = 'o8wK4uInOeQcUJb7RPX_bys-PkeY';
		
		$level_list = $this->Member_model->get_member_levels($inter_id);
		
		$debug = $this->input->get('debug');
		if(!empty($debug)){
			var_dump($hotels);
			var_dump($level_list);
		}
		
		$level_list[0]='未注册';
		
		if(empty($level_list)){
			exit();
		}
		
		$level_ids = array_keys($level_list);
		$lvl_count = count($level_ids);
		$handle = [];
		
		statistic('R1');
		foreach($hotels as $k => $v){
			if(!empty($v['rooms'])){
				$this->load->library('PMS_Adapter', array(
					'inter_id' => $inter_id,
					'hotel_id' => $k
				), 'pmsa');
				$lowest=[];
				
				for($i = 0; $i < $lvl_count; $i++){
					unset($condit['recache']);
					if($i==0){
						$condit['recache']=1;
					}
					$condit['member_level'] = $level_ids[$i];
					$result = $this->pmsa->get_rooms_change($v['rooms'], array(
						'inter_id' => $inter_id,
						'hotel_id' => $k
					), $condit);
					if($result){
						$lowest[] = '【'.$level_list[$level_ids[$i]].'：'.lowest_from_redis($inter_id,$k,$level_ids[$i],$condit['startdate'],$condit['enddate']).'】';
					}
				}
				$handle[]='【'.$k.'】'.$v['name'].'｛'.implode('',$lowest).'｝';
				unset($this->pmsa);
			}
		}
		statistic('R2');
		
		$wait_time = statistic('R1','R2');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => count($handle),
			'record_time'     => time(),
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'baideng_ftai_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		
		if($handle){
			echo '已处理｛' . $hotel_name . '｝共'.count($handle).'家酒店最低价：<br />' . implode('<br>', $handle);
		}
		
		if(!empty ($debug)){
			exit ();
		}
		
	}
	
	public function update_web_orders_jinling(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_hengda' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a492669988';
		$service_type = 'xiruan3';
		$hotel_name = '金陵集团';
		$model_f = 'hotel/pms/Xiruan3_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_huaxiaming1(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_huaxiaming1' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a495442905';
		$service_type = 'luopan';
		$hotel_name = '华夏铭酒店';
		$model_f = 'hotel/pms/Luopan_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, ['pms_set'=>$pms_sets[$lt['hotel_id']]]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_huaxiaming2(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_huaxiaming2' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a495470684';
		$service_type = 'luopan';
		$hotel_name = '华夏铭酒店';
		$model_f = 'hotel/pms/Luopan_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, ['pms_set'=>$pms_sets[$lt['hotel_id']]]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_huaxiaming3(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_huaxiaming3' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a495437302';
		$service_type = 'luopan';
		$hotel_name = '华夏铭酒店';
		$model_f = 'hotel/pms/Luopan_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, ['pms_set'=>$pms_sets[$lt['hotel_id']]]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	function check_fill_orderid(){
	    MYLOG::w ( 'VISIT', 'orderid_pool' );
	    $business = $this->input->get ( 'business' );
	    if (! in_array ( $business, array (
	            'hotel_order',
	            'hotel_goods',
	            'default_code'
	    ), TRUE )) {
	        exit ( 'no this business' );
	    }
	    $this->load->model ( 'common/Enum_model' );
	    $configs = $this->Enum_model->get_enum_des ( array (
	            'HOTEL_ORDERID_CREATE_CONFIG',
	            'HOTEL_ORDERID_CREATE_QUEUE'
	    ) );
	    if (empty ( $configs ['HOTEL_ORDERID_CREATE_CONFIG'] [$business] )) {
	        MYLOG::w ( 'ERROR:no config :'.$business, 'orderid_pool' );
	        exit ( 'no config' );
	    }
	    if (empty ( $configs ['HOTEL_ORDERID_CREATE_QUEUE'] [$business] )) {
	        MYLOG::w ( 'ERROR:no queue :'.$business, 'orderid_pool' );
	        exit ( 'no queue' );
	    }
	    $this->load->library('Hotel/Orderid_pump');
	
	    $rel = Orderid_pump::get_enums('business_code');
	    if (empty ( $rel [$business] )) {
	        MYLOG::w ( 'ERROR:no code :'.$business, 'orderid_pool' );
	        exit ( 'no code' );
	    }
	    $this->load->library ( 'Cache/Redis_proxy', array (
	            'not_init' => FALSE,
	            'module' => 'common',
	            'refresh' => FALSE,
	            'environment' => ENVIRONMENT
	    ), 'redis_proxy' );
	    $lock_key = 'hotel_orderid_create_lock_' . $business;
	    $rand = mt_rand ();
	    $lock_time_key = 'hotel_orderid_create_lasttime_' . $business;
	    $ok = $this->redis_proxy->set ( $lock_key, $rand, array (
	            'nx',
	            'ex' => 120
	    ) );
	    if (! $ok) {
	        $last_time = $this->redis_proxy->get ( $lock_time_key );
	        if ($last_time + 120 < time ()) {
	            echo 'running long';
	            MYLOG::w ( 'ERROR:有其他任务正在运行，已经超过2分钟！', 'orderid_pool' );
	            exit ();
	        }
	        echo 'running now';
	        MYLOG::w ( 'ERROR:有其他任务正在运行！', 'orderid_pool' );
	        exit ();
	    }
	    set_time_limit ( 0 );
	    ini_set ( 'memory_limit', '256M' );
	    $this->redis_proxy->set ( $lock_time_key, time () );
	    $config = json_decode ( $configs ['HOTEL_ORDERID_CREATE_CONFIG'] [$business], TRUE );
	    $queue = explode ( ',', $configs ['HOTEL_ORDERID_CREATE_QUEUE'] [$business] );
	    $code = $rel [$business];
	    $up_config = $config;
	    $orderids = array ();
	    $per_num = 5;
	    $per_count = 10000;
	    $step = empty ( $config ['step'] ) ? 1 : $config ['step'];
	    $queue_count = count ( $queue );
	    if ($config ['qindex'] + $step >= $queue_count - 1) { // 走到了尽头
	        $step = $queue_count - $config ['qindex'] - 1;
	        foreach ( $configs ['HOTEL_ORDERID_CREATE_CONFIG'] as $c ) {
	            $tc = json_decode ( $c, TRUE );
	            if ($up_config ['code'] <= $tc ['code']) {
	                $up_config ['code'] = $tc ['code'] + 1;
	            }
	        }
	        $up_config ['qindex'] = 0;
	    } else {
	        $up_config ['qindex'] += $step;
	    }
	    for($i = 0; $i < $step; $i ++) {
	        $min = $queue [$config ['qindex'] + $i] * $per_count;
	        $max = ($queue [$config ['qindex'] + $i] + $per_num) * $per_count - 1;
	        for($j = $min; $j <= $max; $j ++) {
	            $orderids [] = array (
	                    'orderid' => $config ['code'] . str_pad ( $j, 8, 0, STR_PAD_LEFT ),
	                    'business' => $code
	            );
	        }
	    }
	    $this->db->where ( array (
	            'type' => 'HOTEL_ORDERID_CREATE_CONFIG',
	            'code' => $business
	    ) );
	    $this->db->update ( 'enum_desc', array (
	            'des' => json_encode ( $up_config )
	    ) );
	    shuffle ( $orderids );
	    $count = count ( $orderids );
	    $per_insert_num = 10000;
	    if ($count <= $per_insert_num) {
	        $this->db->insert_batch ( 'orderid_pool', $orderids );
	    } else {
	        $insert_times = round ( $count / $per_insert_num );
	        for($i = 0; $i < $insert_times; $i ++) {
	            if ($i == $insert_times - 1) {
	                $tmp = array_slice ( $orderids, $i * $per_insert_num );
	            } else {
	                $tmp = array_slice ( $orderids, $i * $per_insert_num, $per_insert_num );
	            }
	            $this->db->insert_batch ( 'orderid_pool', $tmp );
	        }
	    }
	    if ($this->redis_proxy->get ( $lock_key ) == $rand)
	        $this->redis_proxy->del ( $lock_key );
	        MYLOG::w ( 'COMPLETE', 'orderid_pool' );
	        echo 'ok';
	}
	
	public function update_web_orders_gbsz(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_gbsz' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a492755178';
		$service_type = 'gbsz';
		$hotel_name = '古北水镇';
		$model_f = 'hotel/pms/Gbsz_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_dielai(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_dielai' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a498464307';
		$service_type = 'lvyun';
		$hotel_name = '蝶来酒店';
		$model_f = 'hotel/pms/Lvyun_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_huali(){
		// redirect ( 'http://jfk.iwide.cn/index.php/hotel/auto_gogogo/update_web_orders_huali' );
		set_time_limit(0);
		$db_read=$this->load->database('iwide_r1',true);
		$inter_id = 'a500000398';
		$service_type = 'xiruaniw';
		$hotel_name = '中恒华里';
		$model_f = 'hotel/pms/Xiruaniw_hotel_model';
		$this->load->model('hotel/Hotel_config_model');
		
		$db_read->where(array(
			'param_name' => 'WEB_ORDER_UPDATE_COUNT',
			'module'     => 'HOTEL',
			'hotel_id'   => 0,
			'inter_id'   => $inter_id
		));
		$update_count = $db_read->get('hotel_config')->row_array();
		if(!$update_count){
			$update_count['param_value'] = 0;
		}
		$one_count = $this->input->get('oc');
		$one_count = empty ($one_count) ? 20 : intval($one_count);
		$db_read->where(array(
			'inter_id'   => $inter_id,
			'hotel_id >' => 0
		));
		$hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
		$pms_sets = array();
		foreach($hotel_pms_set as $hps){
			$pms_sets [$hps ['hotel_id']] = $hps;
		}
		$where = array(
			'o.inter_id'            => $inter_id,
			'o.isdel'               => 0,
			'o.handled'             => 0,
			'oa.web_orderid is not' => null,
		);
		$orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
			0,
			1,
			2,
			4
		))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
		
		//查询子订单优化
		$sub_res=[];
		$oid_list=[];
		foreach($orderlist as $v){
			$oid_list[]=$v['orderid'];
		}
		//一次查询相关的子订单
		if($oid_list){
			$sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
		}
		$sub_list=[];
		foreach($sub_res as $v){
			$sub_list[$v['orderid']][]=$v;
		}
		//匹配子订单
		foreach($orderlist as $k=>$v){
			$orderlist[$k]['order_details']=[];
			if(isset($sub_list[$v['orderid']])){
				$orderlist[$k]['order_details']=$sub_list[$v['orderid']];
			}
			$orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
		}
		
		$debug = $this->input->get('debug');
		if(!empty ($debug)){
			var_dump($orderlist);
		}
		if(count($orderlist) < $one_count){
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => 0
			));
		} else{
			$this->db->where(array(
				'param_name' => 'WEB_ORDER_UPDATE_COUNT',
				'module'     => 'HOTEL',
				'hotel_id'   => 0,
				'inter_id'   => $inter_id
			));
			$this->db->update('hotel_config', array(
				'param_value' => $update_count ['param_value'] + $one_count
			));
		}
		$handle_num = 0;
		$handle_orders = '(' . $hotel_name . ')oo-';
		$this->load->model($model_f, 'pms');
		$now = time();
		foreach($orderlist as $lt){
			$new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
			/*
			 * if ($new_status == 1 || $new_status == 2) {
			 * } else
			 */
			if($new_status == 3){
				$handle_orders .= ',' . $lt ['orderid'];
				$handle_num++;
			} else{
				if($new_status == 4 || $new_status == 5 || $new_status == 8){
					$handle_orders .= ',' . $lt ['orderid'];
					$handle_num++;
				}
			}
		}
		$mirco_time = microtime();
		$mirco_time = explode(' ', $mirco_time);
		$wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
		$this->db->insert('webservice_record', array(
			'send_content'    => '',
			'receive_content' => $handle_orders,
			'record_time'     => $now,
			'inter_id'        => $inter_id,
			'service_type'    => $service_type,
			'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
			'record_type'     => 'order_batch_update',
			'openid'          => 'gang',
			'wait_time'       => $wait_time
		));
		echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
		if($handle_orders){
			echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
		}
		if(!empty ($debug)){
			exit ();
		}
	}
	
	public function update_web_orders_lilang(){
	    set_time_limit(0);
	    $db_read=$this->load->database('iwide_r1',true);
	    $inter_id = 'a496200601';
	    $service_type = 'shiji';
	    $hotel_name = '丽朗';
	    $model_f = 'hotel/pms/Shiji_hotel_model';
	    $this->load->model('hotel/Hotel_config_model');
	
	    $db_read->where(array(
	            'param_name' => 'WEB_ORDER_UPDATE_COUNT',
	            'module'     => 'HOTEL',
	            'hotel_id'   => 0,
	            'inter_id'   => $inter_id
	    ));
	    $update_count = $db_read->get('hotel_config')->row_array();
	    if(!$update_count){
	        $update_count['param_value'] = 0;
	    }
	    $one_count = $this->input->get('oc');
	    $one_count = empty ($one_count) ? 20 : intval($one_count);
	    $db_read->where(array(
	            'inter_id'   => $inter_id,
	            'hotel_id >' => 0
	    ));
	    $hotel_pms_set = $db_read->get_where('hotel_additions')->result_array();
	    $pms_sets = array();
	    foreach($hotel_pms_set as $hps){
	        $pms_sets [$hps ['hotel_id']] = $hps;
	    }
	    $where = array(
	            'o.inter_id'            => $inter_id,
	            'o.isdel'               => 0,
	            'o.handled'             => 0,
	            'oa.web_orderid is not' => null,
	    );
	    $orderlist = $db_read->from('hotel_orders o')->join('hotel_order_additions oa', 'oa.orderid=o.orderid', 'inner')->where($where)->where_in('o.status', array(
	            0,
	            1,
	            2,
	            4
	    ))->order_by('o.id', 'asc')->limit($one_count, $update_count['param_value'])->get()->result_array();
	
	    //查询子订单优化
	    $sub_res=[];
	    $oid_list=[];
	    foreach($orderlist as $v){
	        $oid_list[]=$v['orderid'];
	    }
	    //一次查询相关的子订单
	    if($oid_list){
	        $sub_res=$db_read->from('hotel_order_items')->select('*,id as sub_id')->where(['inter_id'=>$inter_id])->where_in('orderid',$oid_list)->get()->result_array();
	    }
	    $sub_list=[];
	    foreach($sub_res as $v){
	        $sub_list[$v['orderid']][]=$v;
	    }
	    //匹配子订单
	    foreach($orderlist as $k=>$v){
	        $orderlist[$k]['order_details']=[];
	        if(isset($sub_list[$v['orderid']])){
	            $orderlist[$k]['order_details']=$sub_list[$v['orderid']];
	        }
	        $orderlist[$k]['first_detail'] = empty($orderlist[$k]['order_details']) ? [] : $orderlist[$k]['order_details'][0];
	    }		$debug = $this->input->get('debug');
	    if(!empty ($debug)){
	        var_dump($orderlist);
	    }
	    if(count($orderlist) < $one_count){
	        $this->db->where(array(
	                'param_name' => 'WEB_ORDER_UPDATE_COUNT',
	                'module'     => 'HOTEL',
	                'hotel_id'   => 0,
	                'inter_id'   => $inter_id
	        ));
	        $this->db->update('hotel_config', array(
	                'param_value' => 0
	        ));
	    } else{
	        $this->db->where(array(
	                'param_name' => 'WEB_ORDER_UPDATE_COUNT',
	                'module'     => 'HOTEL',
	                'hotel_id'   => 0,
	                'inter_id'   => $inter_id
	        ));
	        $this->db->update('hotel_config', array(
	                'param_value' => $update_count ['param_value'] + $one_count
	        ));
	    }
	    $handle_num = 0;
	    $handle_orders = '(' . $hotel_name . ')oo-';
	    $this->load->model($model_f, 'pms');
	    $now = time();
	    foreach($orderlist as $lt){
	        $new_status = $this->pms->update_web_order($inter_id, $lt, $pms_sets[$lt['hotel_id']]); // 更新订单状态,返回新的状态
	        /*
	         * if ($new_status == 1 || $new_status == 2) {
	         * } else
	             */
	         if($new_status == 3){
	             $handle_orders .= ',' . $lt ['orderid'];
	             $handle_num++;
	         } else{
	             if($new_status == 4 || $new_status == 5 || $new_status == 8){
	                 $handle_orders .= ',' . $lt ['orderid'];
	                 $handle_num++;
	             }
	         }
	    }
	    $mirco_time = microtime();
	    $mirco_time = explode(' ', $mirco_time);
	    $wait_time = $mirco_time [1] - $now + number_format($mirco_time [0], 2, '.', '');
	    $this->db->insert('webservice_record', array(
	            'send_content'    => '',
	            'receive_content' => $handle_orders,
	            'record_time'     => $now,
	            'inter_id'        => $inter_id,
	            'service_type'    => $service_type,
	            'web_path'        => $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'],
	            'record_type'     => 'order_batch_update',
	            'openid'          => 'gang',
	            'wait_time'       => $wait_time
	    ));
	    echo '(' . $hotel_name . ')本次已处理订单 ' . $handle_num . ' 条。（订单状态被更改为离店、取消、未到、删除、异常才算处理完成，确认和入住不算。）<br />';
	    if($handle_orders){
	        echo '订单号：' . str_replace(',', '<br/>', $handle_orders);
	    }
	    if(!empty ($debug)){
	        exit ();
	    }
	}
	
}