<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Orders_ticket extends MY_Admin {
	function __construct(){
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->action_name = '订单管理';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
	}

	protected function main_model_name() {
		return 'hotel/order_ticket_model';
	}

	public function grid(){
		$check_w = $this->uri->segment ( 4 );
		if ($check_w == 'w') {
			redirect ( site_url ( 'hotel/Orders_ticket/index' ) . '?s=w' );
		}
		$data = $this->common_data;
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$entity_id = $this->session->get_admin_hotels ();
		$data = array();
		$data['action_name'] = $this->action_name;
		$data ['istatus'] = $this->input->get ( 's', true );
		$data ['show_status'] = array (
			'w' => array (
				'code' => '0,1',
				'des' => '待处理' ,
				'count' => 0,
				'rmk' => '包含待确认、已确认、未使用的订单',
			),
			'c' => array (
				'code' => '0,1,2,4,5,11',
				'des' => '今日使用' ,
				'count' => 0,
				'rmk' => '使用日期为今天的订单',
			),
			't' => array (
				'code' => NULL,
				'des' => '今日订单' ,
				'count' => 0,
				'rmk' => '今天产生的新订单',
			),
			'a' => array (
				'code' => NULL,
				'des' => '所有订单' ,
				'count' => 0,
				'rmk' => '',
			) 
		);
		//form条件处理
		$search_conditions = array();
		$data['allhotels'] = $model->get_all_hotels($this->inter_id,$entity_id);
		if($this->input->get('hotel')!==null){
			$search_conditions['hotel'] = $data['hotel'] = addslashes($this->input->get('hotel'));
		}else{
			$data['hotel'] = -1;
		}
		// $search_condits['hotel_name'] = $data['hotel_name'] = $this->input->get('hotel_name')?addslashes($this->input->get('hotel_name')):'';
		$data['time_type'] = array(
			1 => '下单时间',
			2 => '使用时间',
			);
		$data['start_t'] = $this->input->get('start_t');
		if($this->input->get('start_t')!==null){
			$search_conditions['start_t'] = addslashes(str_replace('-','',$this->input->get('start_t')));
			$data['start_t'] = $this->input->get('start_t');
		}else{
			$data['start_t'] = '';
		}	
		$data['end_t'] = $this->input->get('end_t');
		if($this->input->get('end_t')!==null){
			$search_conditions['end_t'] = addslashes(str_replace('-','',$this->input->get('end_t')));
			$data['end_t'] = $this->input->get('end_t');
		}else{
			$data['end_t'] = '';
		}
		if(!empty($data['start_t'])||!empty($data['end_t'])){
			if($this->input->get('timetype')!==null){
				$search_conditions['timetype'] = $data['timetype'] = addslashes($this->input->get('timetype'));
			}else{
				$data['timetype'] = 1;
			}
		}else{
			$data['timetype'] = 1;
		}
		if($data['number'] = $this->input->get('number')!==null){
			$search_conditions['number'] = $data['number'] = addslashes($this->input->get('number'));
		}else{
			$data['number'] = '';
		}
		$data['pay_type'] = array(
			1 => '微信支付',
			2 => '到店支付',
			3 => '积分支付',//包括积分支付、换房支付
			4 => '储值支付',
			);
		if($this->input->get('paytype')!==null){
			$search_conditions['paytype'] = $data['paytype'] = addslashes($this->input->get('paytype'));
		}else{
			$data['paytype'] = -1;
		}
		$data['pay_status'] = array(
			0 => '未支付',
			1 => '已支付', 
			);
		if($this->input->get('paystatus')!==null){
			$search_conditions['paystatus'] = $data['paystatus'] = addslashes($this->input->get('paystatus'));
		}else{
			$data['paystatus'] = -1;
		}
		$data['order_status'] = array(
			0 => '待确认',
			1 => '已确认',
			2 => '已使用',
			4 => '用户取消',
			5 => '酒店取消',
			11 => '系统取消',
			);
		if($this->input->get('orderstatus')!==null){
			$search_conditions['orderstatus'] = $data['orderstatus'] = addslashes($this->input->get('orderstatus'));
		}else{
			$data['orderstatus'] = -1;
		}
		if (is_null($data ['istatus'])) {
			$istatus = $data ['show_status'] ['w']['code'];
			$data ['istatus'] = 'w';
		} else {
			$istatus = $data ['show_status'] [$data ['istatus']] ['code'];
		}
		$opt_status = array(
			0=>array(
				1=>'ff9900',
				5=>'fe6464',
				),
			1=>array(
				2=>'ff9900',
				5=>'fe6464',
				),
			);
		$data['grid_fields'] = $model->grid_fields();
		$label_fields = $model->label_fields();
		$list_fields = array();
		foreach ($data['grid_fields'] as $key => $value) {
			$list_fields[] = $label_fields[$value]['label'];
		}
		$data['list_fields'] = array_unique($list_fields);
		$search_url_conditions = '';
		foreach ($search_conditions as $ks => $vs) {
			if($ks=='start_t'||$ks=='end_t'){
				$vs = $data[$ks];
			}
			$search_url_conditions .= $vs=='' ? '' : '&'.$ks.'=' . $vs;
		}
		// echo $search_url_conditions;
		$per_page = 5;
		$data ['search_url'] = site_url ( 'hotel/Orders_ticket/index'  . "?s=" . $data ['istatus']);
		$this->load->library ( 'pagination' );
		$config ['per_page'] = $per_page;
		$config ['use_page_numbers'] = TRUE;
		$page = intval ( $this->uri->segment ( 4 ) ) <= 0 ? 0 : (intval ( $this->uri->segment ( 4 ) ) - 1) * $config ['per_page'];
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 4;
		$config ['numbers_link_vars'] = array (
				'class' => 'number' 
		);
		$config ['suffix'] = "?s=" . $data ['istatus'];
		$config ['suffix'] .= $search_url_conditions;
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = base_url ( "index.php/hotel/Orders_ticket/index" );
		$config ['first_url'] = base_url ( "index.php/hotel/Orders_ticket/index" ) . "?s=" . $data ['istatus'];
		$config ['first_url'] .= $search_url_conditions;
		$count_condits = array (
			'ept_status' => '9,10',
			'istatus' => $istatus,
			'hotel_id' => $entity_id,
			'td' => $data['istatus']=='t'?1:null, 
			'tdstart' => $data['istatus']=='c'?1:null,
		);
		$count_condits = array_merge($count_condits,$search_conditions);
		$config ['total_rows'] = $model->get_order_list ( $this->inter_id, $count_condits, true );
		$this->pagination->initialize ( $config );
		$data ['pagination'] = $this->pagination->create_links ();
		
		$condits = array (
				'idetail' => array (
						'r' 
				),
				'nums' => $per_page,
				'istatus' => $istatus,
				'hotel_id' => $entity_id,
				'td' => $data ['istatus']=='t'?1:null,
				'tdstart' => $data['istatus']=='c'?1:null, 
		);
		$condits ['offset'] = $page;
		$condits ['ept_status'] = '9,10';
		$condits = array_merge($condits,$search_conditions);
		$list = $model->get_order_list($this->inter_id,$condits,false);
		if (! empty ( $list )) {
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( array (
					'HOTEL_ORDER_STATUS',
					'HOTEL_ORDER_PAY_STATUS' 
			) );
			$status_des['HOTEL_ORDER_STATUS'][2] = '已使用';
			unset($status_des['HOTEL_ORDER_STATUS'][3]);
			$this->load->model ( 'pay/Pay_model' );
			$pay_ways = $this->Pay_model->get_pay_way ( array (
					'inter_id' => $this->inter_id,
					'module' => $this->module,
					'key' => 'value' 
			) );

			$pay_ways ['bonus'] = new stdClass ();
			$pay_ways ['bonus']->pay_name = '积分支付';
			
			$end_status = array (
					3,
					4,
					5,
					8,
					11
			);
			
			foreach ( $list as $k => $d ) {
				$list [$k] ['status'] = $status_des ['HOTEL_ORDER_STATUS'] [$d ['status']];
				$list [$k] ['hname_rname'] = empty ( $d ['first_detail'] ) ? $d ['hname'] : $d ['hname'] . '-' . $d ['first_detail'] ['roomname'];
				$list [$k] ['show_orderid'] = empty ( $d ['web_orderid'] ) ? $d ['orderid'] : $d ['orderid'] . '/<br />' . $d ['web_orderid'];
                if(isset($pay_ways [$d ['paytype']]->pay_name)){
                    $list [$k] ['paytype'] = $pay_ways [$d ['paytype']]->pay_name;
                }else{
                    $list [$k] ['paytype']='';
                }
				$list [$k] ['is_paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$d ['paid']];
				if($d ['paytype'] == 'weixin' && $d ['paid'] == 0){
					$list [$k] ['is_paid'] = '未支付';
				}
				if ($d ['status'] == 0) {
					$list [$k] ['no_item_status'] = 1;
				}
				$list [$k] ['ori_price'] = 0;
				$list [$k] ['real_price'] = 0;
				if (! empty ( $d ['order_details'] )) {
					$not_same = 0;
					foreach ( $d ['order_details'] as $ok => $od ) {
						if ($od ['istatus'] != $d ['status'] && $not_same == 0) {
							$list [$k] ['no_status'] = 1;
							$list [$k] ['status'] = '-';
							$not_same = 1;
						}
						$list [$k] ['order_details'] [$ok] ['istatus'] = $status_des ['HOTEL_ORDER_STATUS'] [$od ['istatus']];
						$list [$k] ['order_details'] [$ok] ['roomname_pricecode'] = $od ['roomname'] . '-' . $od ['price_code_name'];
						$list [$k] ['order_details'] [$ok] ['ori_price'] = array_sum ( explode ( ',', $od ['allprice'] ) );
						$list [$k] ['order_details'] [$ok] ['sid'] = '订单' . ($ok + 1);
						$list [$k] ['order_details'] [$ok] ['roomnums'] = 1;
						$list [$k] ['ori_price'] += $list [$k] ['order_details'] [$ok] ['ori_price'];
						$list [$k] ['real_price'] += $list [$k] ['order_details'] [$ok] ['iprice'];
						if (in_array ( $od ['istatus'], $end_status )) {
							$list [$k] ['order_details'] [$ok] ['no_item_status'] = 1;
						}
						if (isset ( $opt_status[$od ['istatus']] )) {
							foreach ($opt_status[$od ['istatus']] as $ko => $vo) {
								$list [$k] ['order_details'] [$ok] ['item_opt_status'][$ko]['text'] = $ko==1?'确认':($ko==5?'拒绝':$status_des ['HOTEL_ORDER_STATUS'][$ko]);
								$list [$k] ['order_details'] [$ok] ['item_opt_status'][$ko]['bg_color'] = $vo;
								if($od['istatus']==1&& $d ['paid'] == 1){
									$list [$k] ['order_details'] [$ok] ['istatus'] = '等待使用';
									$list [$k] ['order_details'] [$ok] ['item_opt_status'][$ko]['bg_color'] = 'ff9900';
									$list [$k] ['order_details'] [$ok] ['item_opt_status'][$ko]['text'] = '已使用';
									break;
								}
							}
						}
					}
				}
				if (in_array ( $d ['status'], $end_status )) {
					$list [$k] ['no_status'] = 1;
				}
				if (isset ( $opt_status[$d ['status']] )) {
					foreach ($opt_status[$d ['status']] as $ko => $vo) {
						$list [$k] ['opt_status'][$ko]['text'] = $ko==1?'确认':($ko==5?'拒绝':$status_des ['HOTEL_ORDER_STATUS'][$ko]);
						$list [$k] ['opt_status'][$ko]['bg_color'] = $vo;
						if($d['status']==1&& $d ['paid'] == 1){
							$list [$k] ['status'] = '等待使用';
							$list [$k] ['opt_status'][$ko]['bg_color'] = 'ff9900';
							$list [$k] ['opt_status'][$ko]['text'] = '已使用';
							break;
						}
					}
				}
			}
			$data ['lists'] = $list;
		}
		// var_dump($list);exit;
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}

	public function edit() {
		$oid = intval ( $this->input->get ( 'ids' ) );
		$hotel_id = $this->input->get ( 'h' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! in_array ( $hotel_id, $hotel_ids )) {
				redirect ( site_url ( 'privilege/auth/deny' ) );
				exit ();
			}
		}
		$order = $model->get_order_list ( $this->inter_id, array (
				'oid' => $oid,
				'hotel_id' => $hotel_id,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $order )) {
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( array (
					'HOTEL_ORDER_STATUS',
					'HOTEL_ORDER_PAY_STATUS' 
			) );
			$this->load->model ( 'pay/Pay_model' );
			$pay_ways = $this->Pay_model->get_pay_way ( array (
					'inter_id' => $this->inter_id,
					'module' => $this->module,
					'key' => 'value' 
			) );

			$pay_ways ['bonus'] = new stdClass ();
			$pay_ways ['bonus']->pay_name = '积分支付';
			$order = $order [0];
			$order ['show_orderid'] = empty ( $order ['web_orderid'] ) ? $order ['orderid'] : $order ['web_orderid'];
			$order ['f_order_time'] = date ( 'Y-m-d H:i:s', $order ['order_time'] );
			$order ['f_startdate'] = date ( 'Y-m-d', strtotime ( $order ['startdate'] ) );
			$order ['f_enddate'] = date ( 'Y-m-d', strtotime ( $order ['enddate'] ) );
			$order ['order_room'] = $order ['first_detail'] ['roomname'];
			$order ['real_price'] = 0;
			$order ['ori_price'] = 0;
			$order ['order_price_code'] = $order ['first_detail'] ['price_code_name'];
			$order ['status_des'] = $status_des ['HOTEL_ORDER_STATUS'] [$order ['status']];
			$order ['pay_name'] = $pay_ways [$order ['paytype']]->pay_name;
			foreach ( $order ['order_details'] as $ok => $od ) {
				$order ['order_details'] [$ok] ['status_des'] = $status_des ['HOTEL_ORDER_STATUS'] [$od ['istatus']];
				$order ['order_details'] [$ok] ['roomname_pricecode'] = $od ['roomname'] . '-' . $od ['price_code_name'];
				$order ['order_details'] [$ok] ['ori_price'] = array_sum ( explode ( ',', $od ['allprice'] ) );
				$order ['order_details'] [$ok] ['sid'] = '订单' . ($ok + 1);
				$order ['real_price'] += $od ['iprice'];
				$order ['ori_price'] += $order ['order_details'] [$ok] ['ori_price'];
			}
			$this->load->helper ( 'date' );
			//价格详情
			foreach ($order['order_details'] as $k => $item) {
				$arr = array();
				$countday = get_room_night($item ['startdate'] , $item ['enddate'] ,'ceil' , $item);//至少有1个间夜
				$allprice = explode(',',$item['allprice']);
				if($item['startdate'] == $order['startdate'] && $item['enddate'] == $order['enddate'] && count($allprice)==$countday){
					$temp = 0;
					$same = 1;
					foreach ($allprice as $key => $value) {
						if($key > 0){
							if($value != $allprice[($key-1)]){
								if($key - $temp >1){
									$mykey = date ( 'Ymd', strtotime ( "+ $temp day", strtotime ( $item['startdate'] ) ) )."-". date ( 'Ymd', strtotime ( "+ ".($key)." day", strtotime ( $item['startdate'] ) ) );
									$arr[$mykey] = $allprice[($key-1)];
								}else{
									$mykey = date ( 'Ymd', strtotime ( "+ ".($key-1)." day", strtotime ( $item['startdate'] ) ) )."-".date ( 'Ymd', strtotime ( "+ ".($key)." day", strtotime ( $item['startdate'] ) ) );
									$arr[$mykey] = $allprice[($key-1)];
								}
								$temp = $key;
								$same = 1;
							}else{
								$same++;
							}
						}
					}
					if($same>1){
						$mykey = date ( 'Ymd', strtotime ( "- $same day", strtotime ( $item['enddate'] ) ) )."-". $item['enddate'];
						$arr[$mykey] = $allprice[$temp];
					}else{
						$mykey = date ( 'Ymd', strtotime ( "- 1 day", strtotime ( $item['enddate'] ) ) )."-". $item['enddate'];
						$arr[$mykey] = $allprice[$temp];
					}
				}else{
					$mykey = $item['startdate'].'-'.$item['enddate'];
					$arr[$mykey] = '一共：'.$item['iprice'];
				}
				$order['order_details'][$k]['price_detail'] = $arr;
			}
			$data ['list'] = $order;
			//是否打印
			$print = $this->input->get ( 'print' );
			$view = empty($print)? 'edit' : 'view';
			$this->_render_content ( $this->_load_view_file ( $view ), $data, false );
		} else {
			redirect ( site_url ( 'privilege/auth/deny' ) );
		}
	}

	public function edit_post() {
		$hotel_id = intval ( $this->input->post ( 'hotel_id' ) );
		$orderid = $this->input->post ( 'orderid' );
		$hold_time = $this->input->post ( 'holdtime' );
		$remark = $this->input->post ( 'remark' );
        $mt_pms_orderid = $this->input->post ( 'mt_pms_orderid' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! in_array ( $hotel_id, $hotel_ids )) {
				redirect ( site_url ( 'privilege/auth/deny' ) );
				exit ();
			}
		}
		$order = $model->get_order_list ( $this->inter_id, array (
				'orderid' => $orderid,
				'hotel_id' => $hotel_id,
				'idetail' => array (
						'i' 
				) 
		) );
		if (! empty ( $order )) {
			$order = $order [0];
			$this->db->where ( array (
					'orderid' => $orderid,
					'inter_id' => $this->inter_id,
					'hotel_id' => $hotel_id 
			) );
			$this->db->update ( 'hotel_orders', array (
					'holdtime' => $hold_time,
					'remark' => $remark,
                    'mt_pms_orderid' => $mt_pms_orderid
            ) );
			redirect ( site_url ( 'hotel/orders_ticket/index' ) );
		} else {
			redirect ( site_url ( 'privilege/auth/deny' ) );
		}
	}

	public function update_order_status() {
		$model = $this->_load_model ( $this->main_model_name () );
		$orderid = $this->input->get ( 'oid' );
		$status = intval ( $this->input->get ( 'status' ) );
		if($item_id = $this->input->get('item_id')){
			$data['istatus'] = $status;
			if($model->update_order_item ( $this->inter_id, $orderid, $item_id, $data )){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			if ($model->update_order_status ( $this->inter_id, $orderid, $status )) {
				echo 1;
			} else
				echo 0;
		}
	}

}