<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Orders extends MY_Admin_Iapi {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '交易订单';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/order_model';
	}
	public function index_one(){
		$data = $this->common_data;
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$entity_id = $this->session->get_admin_hotels ();
		$data['t_order_num'] = $model->get_today_order_num($this->inter_id,$entity_id);
		$data['t_checkin_num'] = $model->get_today_checkin_num($this->inter_id,$entity_id);
		$data['order_confirm_two'] = $this->getNumRoom(2);	
		$data['order_comfirm_num'] = $model->get_order_confirm_num($this->inter_id,$entity_id);
		$this->_render_content ( $this->_load_view_file ( 'index_one' ), $data, false );
	}
	//获取指定数量的未确认订单数据
	public function getNumRoom($num=1){
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
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
		$entity_id = $this->session->get_admin_hotels ();
		$data = $model->get_order_confirm_two($this->inter_id,$num,$entity_id);
		foreach ($data as $kt => $vt) {
			$data[$kt]['paytype'] = $pay_ways [$vt['paytype']]->pay_name;
			$data[$kt]['startdate'] = date('Y-m-d',strtotime($vt['startdate']));
			$data[$kt]['enddate'] = date('Y-m-d',strtotime($vt['enddate']));
			$data[$kt]['days'] = get_room_night($vt['startdate'],$vt['enddate'],'round',$vt);
			$data[$kt]['order_time'] = date('Y.m.d H:i:s',$vt['order_time']);
		}
		if($num==1){
			$data['order_comfirm_num'] = $model->get_order_confirm_num($this->inter_id,$entity_id);
			echo json_encode($data);
		}else{
			return $data;
		}
	}
	public function search_conf() {
		$data = array();
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );

		$data['allhotels'] = $model->get_all_hotels($this->inter_id,$entity_id);
		$data['time_type'] = array(
			1 => '下单时间',
			2 => '入住时间',
			3 => '离店时间',
			);
		$data['pay_status'] = array(
			"0" => '未支付',
			"1" => '已支付', 
			);
		$data['order_status'] = array(
			0 => '待确认',
			1 => '待入住',
			2 => '待离店',
			3 => '已离店',
			4 => '用户取消',
			5 => '酒店取消',
			8 => '未到',
			11 => '系统取消',
			);
		$this->load->model ( 'pay/Pay_model' );
		$pay_ways = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'key' => 'value' 
		) );
		$data['pay_ways'] = $pay_ways;
		
		$this->out_put_msg(1,'',$data,'hotel/orders/search_conf');
	}

	public function get_lists() {
		$data = $this->common_data;
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );

		$data ['show_status'] = array (
				'w' => array (
						'code' => '0,1,2',
						'des' => '待处理' ,
						'count' => 0,
						'rmk' => '包含待确认、已确认、待入住、已入住的订单',
				),
				'c' => array (
						'code' => '0,1,2,3,4,5,11',
						'des' => '今日入住' ,
						'count' => 0,
						'rmk' => '入住日期为今天、入离日期里包含了今天的订单',
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
		$entity_id = $this->session->get_admin_hotels ();
		foreach ($data ['show_status'] as $k => $v) {
			$acount_condits = array(
				'ept_status' => '9,10',
				'istatus' => $v['code'],
				'hotel_id' => $entity_id,
				'td' => $k=='t'?1:null,
				'tdstart' => $k=='c'?1:null,
				);
			$acount = $model->get_order_list ( $this->inter_id, $acount_condits, true );
			$data ['show_status'][$k]['count'] = $acount;
		}

		$this->load->model ( 'pay/Pay_model' );
		$pay_ways = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => $this->module,
				'key' => 'value' 
		) );

		//form条件处理
		$search_conditions = array();
		$search_conditions['hotel'] = $this->input->get('hotel')!==null?addslashes($this->input->get('hotel')):-1;
		$search_conditions['hotel_name'] = $this->input->get('hotel_name')?addslashes($this->input->get('hotel_name')):'';
		$search_conditions['timetype'] = $this->input->get('timetype')!==null?addslashes($this->input->get('timetype')):1;
		$search_conditions['start_t'] = $this->input->get('start_t')!==null?addslashes(str_replace('-','',$this->input->get('start_t'))):'';
		$search_conditions['end_t'] = $this->input->get('end_t')!==null?addslashes(str_replace('-','',$this->input->get('end_t'))):'';
		$search_conditions['number'] = $this->input->get('number')!==null?addslashes($this->input->get('number')):'';
		
		$search_conditions['paytype'] = $this->input->get('paytype')!==null?addslashes($this->input->get('paytype')):-1;
		
		$search_conditions['paystatus'] = $this->input->get('paystatus')!==null?addslashes($this->input->get('paystatus')):-1;
	
		$search_conditions['orderstatus'] = $this->input->get('orderstatus')!==null?addslashes($this->input->get('orderstatus')):-1;
		
		$per_page = $this->input->get ( 'size' )>0 ? intval($this->input->get ( 'size' )) : 20;
		$page = $this->input->get ( 'page' )>0 ? intval($this->input->get ( 'page' )) : 1;
		$istatus = $this->input->get ( 's', true );
		if (!is_null($istatus)) {
			$istatus_codes = $data ['show_status'] [$istatus] ['code'];
		}else{
			$istatus_codes = null;
			$istatus = 'a';
		}
		
		$search_url_conditions = '';
		foreach ($search_conditions as $ks => $vs) {
			if($ks=='start_t'||$ks=='end_t'){
				$vs = $data[$ks];
			}
			$search_url_conditions .= $vs=='' ? '' : '&'.$ks.'=' . $vs;
		}
		$offset = intval ( $page ) <= 0 ? 0 : (intval ( $page ) - 1) * $per_page;
		
		$count_condits = array (
				'ept_status' => '9,10',
				'istatus' => $istatus_codes,
				'hotel_id' => $entity_id,
				'td' => $istatus=='t'?1:null, 
				'tdstart' => $istatus=='c'?1:null,
		);
		$count_condits = array_merge($count_condits,$search_conditions);
		// 优化，分页总数在没有额外搜索条件下直接使用类型总数
		$gets = $this->input->get();
		if(!empty($gets['s'])){
			unset($gets['s']);
		}
		if(empty($gets)||(($search_conditions['hotel']==''||$search_conditions['hotel']==-1)&&$search_conditions['hotel_name']==''&&$search_conditions['timetype']==1&&$search_conditions['start_t']==''&&$search_conditions['end_t']==''&&$search_conditions['number']==''&&$search_conditions['paytype']==-1&&$search_conditions['paystatus']==-1&&$search_conditions['orderstatus']==-1)){
			$ext ['count'] = $data ['show_status'][$istatus]['count'];
		}else{
			$ext ['count'] = $model->get_order_list ( $this->inter_id, $count_condits, true );
		}
		
		$condits = array (
				'idetail' => array (
						'r' 
				),
				'nums' => $per_page,
				'istatus' => $istatus_codes,
				'hotel_id' => $entity_id,
				'td' => $istatus=='t'?1:null,
				'tdstart' => $istatus=='c'?1:null, 
		);
		$condits ['offset'] = $offset;
		$condits ['ept_status'] = '9,10';
		$condits = array_merge($condits,$search_conditions);
		$list = $model->get_order_list ( $this->inter_id, $condits );
		
		if (! empty ( $list )) {
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( array (
					'HOTEL_ORDER_STATUS',
					'HOTEL_ORDER_PAY_STATUS' 
			) );
			// @author lGh 2016-4-7 11:42:48 积分支付
			$pay_ways ['bonus'] = new stdClass ();
			$pay_ways ['bonus']->pay_name = '积分支付';
			
			$end_status = array (
					3,
					4,
					5,
					8,
					11
			);
			$this->load->model ( 'club/Club_list_model' );
			foreach ( $list as $k => $d ) {
				$list [$k] ['status'] = $status_des ['HOTEL_ORDER_STATUS'] [$d ['status']];
				$list [$k] ['hname_rname'] = empty ( $d ['first_detail'] ) ? $d ['hname'] : $d ['hname'] . '-' . $d ['first_detail'] ['roomname'];
				$list [$k] ['show_orderid'] = empty ( $d ['web_orderid'] ) ? $d ['orderid'] : $d ['orderid'] . ' / ' . $d ['web_orderid'];
                if(isset($pay_ways [$d ['paytype']]->pay_name)){
                    $list [$k] ['paytype'] = $pay_ways [$d ['paytype']]->pay_name;
                }else{
                    $list [$k] ['paytype']='';
                }
				$list [$k] ['is_paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$d ['paid']];
				if(($d ['paytype'] == 'weixin' || $d ['paytype'] == 'weifutong' ||$d ['paytype'] == 'lakala'||$d ['paytype'] == 'lakala_y' ||$d ['paytype'] == 'unionpay') && $d ['paid'] == 0){
					$list [$k] ['is_paid'] = '未支付';
				}
				if ($d ['status'] == 0) {
					$list [$k] ['no_item_status'] = 1;
				}
				$list [$k] ['ori_price'] = 0;
				$list [$k] ['real_price'] = 0;
				$grade_ids = array();
				$club_ids = array();
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
						}else{
							$item_order_after = $model->item_order_status($od['istatus']);
							if(!empty($item_order_after['status'])){
								foreach ($item_order_after['status'] as $ki => $vi) {
									if(in_array($ki,array(1,2,3))){
										$list [$k] ['order_details'] [$ok]['item_opt_status'][$ki]['bg_color'] = 'ff9900';
									}else{
										$list [$k] ['order_details'] [$ok]['item_opt_status'][$ki]['bg_color'] = 'fe6464';
									}
									$list [$k] ['order_details'] [$ok]['item_opt_status'][$ki]['text'] = $vi;
								}
							}
						}
						$grade_ids[] = $od['id'];
						$club_ids[] = $od['club_id'];
					}
				}
				// 积分抵用/积分数
				$list [$k]['point_favour'] = $list [$k] ['paytype']=='积分支付'?'--/'.floatval($d['point_used_amount']):$d['point_favour'].'/'.floatval($d['point_used_amount']);
				
				if(!empty($list [$k]['staff_info'])){
					$list [$k]['staff_info'] = trim($list [$k]['staff_info'],',');
				}
				
				if (in_array ( $d ['status'], $end_status )) {
					$list [$k] ['no_status'] = 1;
				}else{
					$order_after = $model->order_status($d ['status']);
					if(!empty($order_after['after'])){
						foreach ($order_after['after'] as $kf => $vf) {
							if(in_array($kf,array(1,2,3))){
								$list[$k] ['opt_status'][$kf]['bg_color'] = 'ff9900';
							}else{
								$list[$k] ['opt_status'][$kf]['bg_color'] = 'fe6464';
							}
							$list[$k] ['opt_status'][$kf]['text'] = $vf;
						}
					}
				}
				//去除无用信息
				unset($list[$k]['first_detail']);
			}
			$data ['lists'] = $list;
		}

		$ext['page'] = $page;
		$ext['size'] = $per_page;

		$this->out_put_msg(1,'',$data,'hotel/orders/get_lists',200,$ext);
	}

	//获取当前显示设置
	public function get_cofigs() {
		$this->load->model ( 'distribute/report_model' );
		$fconf = $this->report_model->get_dist_field_conf ();
		if(empty($fconf)){
			//初始化订单显示设置
			$model_name = $this->main_model_name ();
			$model = $this->_load_model ( $model_name );
			$fconfs = $model->list_fields ();
			$fconf = $this->report_model->init_orders_look($fconfs);
		}
		echo json_encode ( $fconf );
	}
	//保存当前显示设置
	public function save_cofigs() {
		$this->load->model ( 'distribute/report_model' );
		if ($this->report_model->save_dist_field_conf ()) {
			echo 'success';
		} else {
			echo '保存失败';
		}
	}

	public function __grid() {
		$inter_id = $this->session->get_admin_inter_id ();
		if ($inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($inter_id)
			$filter = array (
					'inter_id' => $inter_id 
			);
		else
			$filter = array (
					'inter_id' => 'deny' 
			);
		
		$this->_grid ( $filter );
	}
	public function edit() {
		$this->label_action = '订单详情';
		$this->_init_breadcrumb ( $this->label_action );
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
					'HOTEL_INVOICE_TYPE',
					'HOTEL_ORDER_PAY_STATUS' 
			) );
			$this->load->model ( 'pay/Pay_model' );
			$pay_ways = $this->Pay_model->get_pay_way ( array (
					'inter_id' => $this->inter_id,
					'module' => $this->module,
					'key' => 'value' 
			) );
			// @author lGh 2016-4-7 11:42:48 积分支付
			$pay_ways ['bonus'] = new stdClass ();
			$pay_ways ['bonus']->pay_name = '积分支付';
			$order = $order [0];
			$order ['show_orderid'] = empty ( $order ['web_orderid'] ) ? $order ['orderid'] : $order ['orderid'].' / '.$order ['web_orderid'];
			$order ['f_order_time'] = date ( 'Y-m-d H:i:s', $order ['order_time'] );
			$order ['f_startdate'] = date ( 'Y-m-d', strtotime ( $order ['startdate'] ) );
			$order ['f_enddate'] = date ( 'Y-m-d', strtotime ( $order ['enddate'] ) );
			$order ['order_room'] = $order ['first_detail'] ['roomname'];
			$order ['real_price'] = 0;
			$order ['ori_price'] = 0;
			$order ['order_price_code'] = $order ['first_detail'] ['price_code_name'];
			$order ['status_des'] = $status_des ['HOTEL_ORDER_STATUS'] [$order ['status']];
			$order ['pay_name'] = $pay_ways [$order ['paytype']]->pay_name;
			//销售员
			if(!empty($order ['first_detail'] ['id'])){
				$saler_info = $model->get_saler_info($this->inter_id,$order ['first_detail'] ['id']);
				$order['staff_info'] = $saler_info;
			}
            //社群客订单
            if(!empty($order ['first_detail'] ['club_id'])){
                $this->load->model ( 'club/Club_model' );
                $order['club_info'] = $this->Club_model->getClubInfoByClubId($order ['first_detail'] ['club_id'],$this->inter_id,2);
            }
			//渠道
			require_once APPPATH . DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."Hotel".DIRECTORY_SEPARATOR."Hotel_const.php";
			$order['channel'] = Hotel_const::enums('order_channel',$order['channel']);
			$order ['is_paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$order ['paid']];
			if(($order ['paytype'] == 'weixin' || $order ['paytype'] == 'weifutong' ||$order ['paytype'] == 'lakala'||$order ['paytype'] == 'lakala_y' ||$order ['paytype'] == 'unionpay') && $order ['paid'] == 0){
				$order ['is_paid'] = '未支付';
			}
			
			//关联发票信息
			if($order['is_invoice']>1){
				$this->load->model ( 'invoice/invoice_model' );
				$order ['invoice_detail'] = $this->invoice_model->get_invoice_detail($order ['orderid']);
				$order ['invoice_detail']['typename'] = $status_des ['HOTEL_INVOICE_TYPE'] [$order ['invoice_detail'] ['type']];
			}
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
	
	/**
	 * 废弃
	 */
	function edit_dec() {
		$this->label_action = '订单管理';
		$this->_init_breadcrumb ( $this->label_action );
		
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		
		$id = intval ( $this->input->get ( 'ids' ) );
		if ($id) {
			// for edit page.
			$model = $model->load ( $id );
			$fields_config = $model->get_field_config ( 'form' );
			// $sql= "select a.* from {$this->db->dbprefix}shp_goods_attr as a left join {$this->db->dbprefix}shp_attrbutes as b on a.attr_id=b.attr_id where a.gs_id=". $id;
			// $detail_field= $this->db->query($sql)->result_array();
			$detail_field = array ();
			if (count ( $detail_field ) > 0) {
				$detail_field = $detail_field [0] ['attr_value'];
			} else {
				$detail_field = '';
			}
		} else {
			// for add page.
			$model = $model->load ( $id );
			if (! $model)
				$model = $this->_load_model ();
			$fields_config = $model->get_field_config ( 'form' );
			$detail_field = '';
		}
		$view_params = array (
				'model' => $model,
				'fields_config' => $fields_config,
				'check_data' => FALSE,
				'detail_field' => $detail_field 
		);
		// 'gallery'=> $gallery,
		
		$html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		echo $html;
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
			redirect ( site_url ( 'hotel/orders/index' ) );
		} else {
			redirect ( site_url ( 'privilege/auth/deny' ) );
		}
	}
	function edit_post_dec() {
		$this->label_action = '信息维护';
		$this->_init_breadcrumb ( $this->label_action );
		
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$pk = $model->table_primary_key ();
		
		$this->load->library ( 'form_validation' );
		$post = $this->input->post ();
		
		$labels = $model->attribute_labels ();
		$base_rules = array (
				'id' => array (
						'field' => 'id',
						'label' => $labels ['id'],
						'rules' => 'trim' 
				),
				'openid' => array (
						'field' => 'openid',
						'label' => $labels ['openid'],
						'rules' => 'trim' 
				),
				'price' => array (
						'field' => 'price',
						'label' => $labels ['price'],
						'rules' => 'trim' 
				),
				'roomnums' => array (
						'field' => 'roomnums',
						'label' => $labels ['roomnums'],
						'rules' => 'trim' 
				),
				'name' => array (
						'field' => 'name',
						'label' => $labels ['name'],
						'rules' => 'trim' 
				),
				'tel' => array (
						'field' => 'tel',
						'label' => $labels ['tel'],
						'rules' => 'trim' 
				),
				'order_time' => array (
						'field' => 'order_time',
						'label' => $labels ['order_time'],
						'rules' => 'trim' 
				),
				'startdate' => array (
						'field' => 'startdate',
						'label' => $labels ['startdate'],
						'rules' => 'trim' 
				),
				'enddate' => array (
						'field' => 'enddate',
						'label' => $labels ['enddate'],
						'rules' => 'trim' 
				),
				'paid' => array (
						'field' => 'paid',
						'label' => $labels ['paid'],
						'rules' => 'trim' 
				),
				'orderid' => array (
						'field' => 'orderid',
						'label' => $labels ['orderid'],
						'rules' => 'trim' 
				),
			/* 	'status' => array (
						'field' => 'status',
						'label' => $labels ['status'],
						'rules' => 'trim|required' 
				), */
				'holdtime' => array (
						'field' => 'holdtime',
						'label' => $labels ['holdtime'],
						'rules' => 'trim' 
				),
				'paytype' => array (
						'field' => 'paytype',
						'label' => $labels ['paytype'],
						'rules' => 'trim' 
				),
				'isdel' => array (
						'field' => 'isdel',
						'label' => $labels ['isdel'],
						'rules' => 'trim' 
				),
				'operate_reason' => array (
						'field' => 'operate_reason',
						'label' => $labels ['operate_reason'],
						'rules' => 'trim' 
				),
				'remark' => array (
						'field' => 'remark',
						'label' => $labels ['remark'],
						'rules' => 'trim' 
				),
				'member_no' => array (
						'field' => 'member_no',
						'label' => $labels ['member_no'],
						'rules' => 'trim' 
				),
				'hotel_id' => array (
						'field' => 'hotel_id',
						'label' => $labels ['hotel_id'],
						'rules' => 'trim' 
				),
				'inter_id' => array (
						'field' => 'inter_id',
						'label' => $labels ['inter_id'],
						'rules' => 'trim' 
				) 
		);
		
		// 检测并上传文件。
		$post = $this->_do_upload ( $post, 'gs_logo' );
		
		$adminid = $this->session->get_admin_id ();
		
		if (empty ( $post [$pk] )) {
			// add data.
			$this->form_validation->set_rules ( $base_rules );
			
			if ($this->form_validation->run () != FALSE) {
				$post ['add_date'] = date ( 'Y-m-d H:i:s' );
				$post ['add_user'] = $adminid;
				
				// $result= $model->m_sets($post)->m_save($post);
				$message = ($result) ? $this->session->put_success_msg ( '已新增数据！' ) : $this->session->put_notice_msg ( '此次数据保存失败！' );
				// $this->_log($model);
				$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/grid' ) );
			} else
				$model = $this->_load_model ();
		} else {
			$this->form_validation->set_rules ( $base_rules );
			if ($this->form_validation->run () != FALSE) {
				$post ['last_update_time'] = date ( 'Y-m-d H:i:s' );
				$post ['last_update_user'] = $adminid;
				
				$status = intval ( $post ['status'] );
				$this->load->model ( 'hotel/Order_model' );
				$this->inter_id = $this->session->get_admin_inter_id ();
				// $check = $this->Order_model->update_order_status ( $this->inter_id, $post ['orderid'], $status );
				// if ($check) {
				// unset ( $post ['status'] );
				$result = $model->load ( $post [$pk] )->m_sets ( $post )->m_save ( $post );
				$message = ($result) ? $this->session->put_success_msg ( '已保存数据！' ) : $this->session->put_notice_msg ( '此次数据修改失败！' );
				// } else {
				// $this->session->put_notice_msg ( '此次数据修改失败！' );
				// }
				$this->_log ( $model );
				$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/grid' ) );
			} else
				$model = $model->load ( $post [$pk] );
		}
		
		// 验证失败的情况
		$validat_obj = _get_validation_object ();
		$message = $validat_obj->error_html ();
		// 页面没有发生跳转时用寄存器存储消息
		$this->session->put_error_msg ( $message, 'register' );
		
		$fields_config = $model->get_field_config ( 'form' );
		$view_params = array (
				'model' => $model,
				'fields_config' => $fields_config,
				'check_data' => TRUE 
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		echo $html;
	}
	public function order_status() {
		$model = $this->_load_model ( $this->main_model_name () );
		$orderid = $this->input->get ( 'oid' );
		$hotel_id = $this->input->get ( 'h' );
		$order = $model->get_order_list ( $this->inter_id, array (
				'orderid' => $orderid,
				'hotel_id' => $hotel_id,
				'idetail' => array (
						'i' 
				) 
		) );
		$data = array ();
		if (! empty ( $order )) {
			$order = $order [0];
			$after = $model->order_status_sequence ( $order ['status'] );
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
			$data ['order'] = array (
					'orderid' => $orderid,
					'status_des' => $status_des [$order ['status']] 
			);
			foreach ( $order ['order_details'] as $od ) {
				if ($od ['istatus'] != $order ['status']) {
					$data ['order'] ['status_des'] = '-';
					$after = array ();
					break;
				}
			}
			$data ['after'] = array ();
			foreach ( $after as $a ) {
				$data ['after'] [$a] = $status_des [$a];
			}
		}
		echo json_encode ( $data );
	}
	public function item_edit() {
		$model = $this->_load_model ( $this->main_model_name () );
		$orderid = $this->input->get ( 'oid' );
		$hotel_id = $this->input->get ( 'h' );
		$id = $this->input->get ( 'item' );
		$item = $model->get_order_item ( $this->inter_id, $orderid, $id );
		$data = array ();
		if (! empty ( $item ) && $item ['istatus'] != 0) {
			$after = $model->order_status_sequence ( $item ['istatus'] );
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
			$item ['status_des'] = $status_des [$item ['istatus']];
			$data ['list'] = $item;
			$data ['status'] = array ();
			foreach ( $after as $a ) {
				if ($a != 4)
					$data ['status'] [$a] = $status_des [$a];
			}
			/*
			 * if ($item ['istatus'] == 2 || $item ['istatus'] == 1) {
			 * $this->load->helper ( 'date' );
			 * $startdate = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $item ['startdate'] ) ) );
			 * $day_range = get_day_range ( $startdate, $item ['enddate'], 'array' );
			 * if (count ( $day_range ) > 1) {
			 * rsort ( $day_range );
			 * $data ['day_range'] = $day_range;
			 * }
			 * }
			 */
		} else {
			redirect ( site_url ( 'hotel/orders/index' ) );
		}
		$this->_render_content ( $this->_load_view_file ( 'item_edit' ), $data, false );
	}
	public function item_edite() {
		$model = $this->_load_model ( $this->main_model_name () );
		$orderid = $this->input->get ( 'oid' );
		$hotel_id = $this->input->get ( 'h' );
		$id = $this->input->get ( 'item' );
		$item = $model->get_order_item ( $this->inter_id, $orderid, $id );
		$data = array ();
		if (! empty ( $item ) && $item ['istatus'] != 0) {
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_STATUS' );
			$item ['status_des'] = $status_des [$item ['istatus']];
			$data ['list'] = $item;
			if ($item ['istatus'] == 2 || $item ['istatus'] == 1) {
				$this->load->helper ( 'date' );
				$data ['begin_range'] = get_day_range ( date ( 'Ymd', strtotime ( '- 10 day', strtotime ( $item ['startdate'] ) ) ), date ( 'Ymd', strtotime ( '+ 10 day', strtotime ( $item ['startdate'] ) ) ), 'array' );
				$data ['end_range'] = get_day_range ( date ( 'Ymd', strtotime ( '- 10 day', strtotime ( $item ['enddate'] ) ) ), date ( 'Ymd', strtotime ( '+ 10 day', strtotime ( $item ['enddate'] ) ) ), 'array' );
				$data ['can_edit'] = 1;
			}
		} else {
			redirect ( site_url ( 'hotel/orders/index' ) );
		}
		$this->_render_content ( $this->_load_view_file ( 'item_edit_date' ), $data, false );
	}
	public function item_edit_new() {
		$this->label_action = '子订单详情';
		$this->_init_breadcrumb ( $this->label_action );

		$model = $this->_load_model ( $this->main_model_name () );
		$orderid = $this->input->get ( 'oid' );
		$hotel_id = $this->input->get ( 'h' );
		$id = $this->input->get ( 'item' );
		$item = $model->get_order_item ( $this->inter_id, $orderid, $id ,'detail');
		$data = array ();
		if (! empty ( $item ) && $item ['istatus'] != 0) {
			$this->load->model ( 'pay/Pay_model' );
			$pay_ways = $this->Pay_model->get_pay_way ( array (
					'inter_id' => $this->inter_id,
					'module' => 'hotel',
					'key' => 'value' 
			) );
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( array (
					'HOTEL_ORDER_STATUS',
					'HOTEL_ORDER_PAY_STATUS' 
			) );
			$pay_ways ['bonus'] = new stdClass ();
			$pay_ways ['bonus']->pay_name = '积分支付';
			$item ['show_orderid'] = empty ( $item ['web_orderid'] ) ? $item ['orderid'] : $item ['web_orderid'];
			$item ['f_order_time'] = date ( 'Y-m-d H:i:s', $item ['order_time'] );
			$item ['f_startdate'] = date ( 'Y-m-d', strtotime ( $item ['startdate'] ) );
			$item ['f_enddate'] = date ( 'Y-m-d', strtotime ( $item ['enddate'] ) );
			$item ['pay_name'] = $pay_ways [$item ['paytype']]->pay_name;

			$after = $model->order_status_sequence ( $item ['istatus'] ,'adminafter');
			require_once APPPATH . DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."Hotel".DIRECTORY_SEPARATOR."Hotel_const.php";
			foreach ( $after as $a ) {
				if ($a != 4)
					$data ['status'] [$a] = Hotel_const::enums('order_status_oprate',$a);
			}
			$item ['status_des'] = $status_des ['HOTEL_ORDER_STATUS'] [$item ['istatus']];
			

			//查询操作日志
			$this->load->model('hotel/Hotel_log_model');
			$params=array(
				'ident'=>'Order/items#'.$id
			);
			
			$admin_logs = $this->Hotel_log_model->get_admin_log($this->inter_id,$params);
			foreach ($admin_logs as $value) {
				$item[$value['log_type']] = $value['record_time'];
			}
			$data ['list'] = $item;
			if ($item ['istatus'] == 2 || $item ['istatus'] == 1) {
				$this->load->helper ( 'date' );
				$data ['begin_min'] = date ( 'Y-m-d', strtotime ( '- 10 day', strtotime ( $item ['startdate'] ) ) );
				$data ['begin_max'] = date ( 'Y-m-d', strtotime ( '+ 10 day', strtotime ( $item ['startdate'] ) ) );
				$data ['end_min'] = date ( 'Y-m-d', strtotime ( '- 10 day', strtotime ( $item ['enddate'] ) ) );
				$data ['end_max'] = date ( 'Y-m-d', strtotime ( '+ 10 day', strtotime ( $item ['enddate'] ) ) );
				$data ['can_edit'] = 1;
			}
		} else {
			redirect ( site_url ( 'hotel/orders/index' ) );
		}

		$this->_render_content ( $this->_load_view_file ( 'item_edit_new' ), $data, false );
	}
	public function item_edit_post() {
		$orderid = $this->input->post ( 'orderid' );
		$item_id = $this->input->post ( 'item_id' );
		$data ['startdate'] = $this->input->post ( 'startdate' );
		$data ['startdate'] = date('Ymd',strtotime($data ['startdate']));
		$data ['enddate'] = $this->input->post ( 'enddate' );
		$data ['enddate'] = date('Ymd',strtotime($data ['enddate']));
		if (isset($_POST['new_price'])) {
			$data ['new_price'] = floatval ( $this->input->post ( 'new_price' ) );
		}
		$data ['istatus'] = intval ( $this->input->post ( 'istatus' ) );
        $data ['mt_room_id'] = $this->input->post ( 'mt_room_id' );
		$model = $this->_load_model ( $this->main_model_name () );
		if (! empty ( $item_id ) && ! empty ( $orderid )) {
			$re = $model->update_order_item ( $this->inter_id, $orderid, $item_id, $data );
		}
		$ajax = $this->input->post ( 'ajax' );
		if($ajax){
			if ($re)
				echo 'success';
			else
				echo 'false';
			exit;
		}
		redirect ( site_url ( 'hotel/orders/index' ) );
	}
	public function update_order_status() {
		$model = $this->_load_model ( $this->main_model_name () );
		$orderid = $this->input->get ( 'oid' );
		$status = intval ( $this->input->get ( 'status' ) );
		if ($model->update_order_status ( $this->inter_id, $orderid, $status )) {
			echo 1;
		} else
			echo 0;
	}
	public function edit_focus() {
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$pk = $model->table_primary_key ();
		$post = $this->input->post ();
		
		if ($post ['del_gallery']) {
			$model->delete_gallery ( $post ['del_gallery'], $post [$pk] );
		}
		// 检测并上传新的文件。
		$post = $this->_do_upload ( $post, 'gallery' );
		if (isset ( $post ['gallery'] )) {
			$data = array (
					'gry_url' => $post ['gallery'],
					'gry_desc' => $post ['gry_desc'],
					'gs_id' => $post ['gs_id'] 
			);
			$model->plus_gallery ( $data );
		}
		$this->session->put_success_msg ( '成功保存产品相册，请继续编辑产品信息' );
		$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/edit', array (
				'ids' => $post [$pk] 
		) ) );
	}
	/**
	 * 订单导出
	 */
	public function hotel_order_xls() {
		if (empty ( $_GET ['t'] )){
			error_reporting ( 0 );
			$this->output->enable_profiler ( true );
		}
		$this->load->model ( 'plugins/Excel_model' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$inter_id = $this->inter_id;
		$condition = " inter_id = '$inter_id' ";
		$order_condition = " $condition and status not in (9,10)";
		$book_begin = $this->input->get ( 'bb' );
		$order_condition .= empty ( $book_begin ) ? ' and order_time >= ' . strtotime ( date ( 'Y-m-d 00:00:00', strtotime ( '- 1 day', time () ) ) ) : ' and order_time >= ' . strtotime ( $book_begin );
		$book_end = $this->input->get ( 'be' );
		$order_condition .= empty ( $book_end ) ? ' and order_time <= ' . strtotime ( date ( 'Y-m-d 23:59:59', strtotime ( '- 1 day', time () ) ) ) : ' and order_time <= ' . strtotime ( $book_end );
		$my_hotels = $this->session->get_admin_hotels ();
		$order_condition .= empty ( $my_hotels ) ? '' : ' and hotel_id in (' . $my_hotels . ')';
		$selects = ' oa.coupon_favour,oa.wxpay_favour,oa.point_favour,oa.web_orderid,o.*,i.room_id,i.iprice,i.startdate istartdate,i.enddate ienddate,
				i.istatus,i.allprice,i.roomname,i.price_code_name,i.webs_orderid ';
		$sql = "select $selects from (SELECT * FROM " . $this->db->dbprefix ( 'hotel_orders' ) . " where $order_condition) o 
				join (select * from " . $this->db->dbprefix ( 'hotel_order_items' ) . " where $condition) i 
				 join (select * from " . $this->db->dbprefix ( 'hotel_order_additions' ) . " where $condition) oa 
				 on oa.orderid=o.orderid and oa.inter_id=o.inter_id and o.orderid=i.orderid and o.inter_id=i.inter_id";
		$result = $this->db->query ( $sql )->result ();
		$head = array (
				'订单类别',
				'订单ID',
				'pms单号',
				'酒店',
				'房型',
				'姓名',
				'电话',
				'下单时间',
				'入住时间',
				'离店时间',
				'房间数',
				'间夜数',
				'原价',
				'优惠信息',
				'总价',
				'价格代码',
				'支付状态',
				'订单状态' 
		);
		$public = $this->db->get_where ( 'publics', array (
				'inter_id' => $inter_id 
		) )->row_array ();
		if (! empty ( $result )) {
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
			$end_status = array (
					3,
					4,
					5,
					8,
					11
			);
			$hotels = $this->db->get_where ( 'hotels', array (
					'inter_id' => $inter_id 
			) )->result_array ();
			$h = array ();
			foreach ( $hotels as $ht ) {
				$h [$ht ['hotel_id']] = $ht ['name'];
			}
			
			$exl_data = array ();
			$heads = array ();
			$sheet_names = array ();
			$order_data = $this->mainorder_only ( $result, $h, $status_des );
			$exl_data [] = $order_data ['data'];
			$heads [] = $order_data ['head'];
			$sheet_names [] = '仅主单';
			
			$order_data = $this->suborder_data ( $result, $h, $status_des );
			$exl_data [] = $order_data ['data'];
			$heads [] = $order_data ['head'];
			$filename = $public ['name'] . '_' . date ( 'YmdHis' );
			$sheet_names [] = '含子单';
			$this->Excel_model->exp_exl_multisheet ( $heads, $exl_data, $filename, $sheet_names );
		} else {
			$filename = $public ['name'] . '_' . date ( 'YmdHis' );
			$this->Excel_model->exp_exl ( $head, $data, $filename );
		}
	}
	public function hotel_order_exp() {
		
		if(!$this->input->post()){
			echo $this->_render_content($this->_load_view_file('hotel_order_exp'), array(), TRUE);
		}
		
		if (empty ( $_GET ['t'] )){
			error_reporting ( 0 );
			$this->output->enable_profiler ( true );
		}
		$this->load->model ( 'plugins/Excel_model' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$inter_id = $this->inter_id;
		$condition = " o.inter_id = '$inter_id' ";
		
		$order_condition = " $condition and o.status not in (9,10)";
		
		
		$book_begin = $this->input->get_post ( 'bb' );
		
		$order_condition .= empty ( $book_begin ) ? ' and o.order_time >= ' . strtotime ( date ( 'Y-m-d 00:00:00', strtotime ( '- 1 day', time () ) ) ) : ' and o.order_time >= ' . strtotime ( $book_begin );
		
		$book_end = $this->input->get_post ( 'be' );
		
		$order_condition .= empty ( $book_end ) ? ' and o.order_time <= ' . strtotime ( date ( 'Y-m-d 23:59:59', strtotime ( '- 1 day', time () ) ) ) : ' and o.order_time <= ' . strtotime ( $book_end );
		
		$my_hotels = $this->session->get_admin_hotels ();
		
		$order_condition .= empty ( $my_hotels ) ? '' : ' and o.hotel_id in (' . $my_hotels . ')';
		
		$selects = ' oa.coupon_favour,oa.wxpay_favour,oa.point_favour,oa.web_orderid,o.*,i.room_id,i.iprice,i.startdate istartdate,i.enddate ienddate,
				i.istatus,i.allprice,i.roomname,i.price_code_name,i.webs_orderid ';
		
		
		$sql = "SELECT $selects FROM " . $this->db->dbprefix ( 'hotel_orders' ) . " o 
				LEFT JOIN " . $this->db->dbprefix ( 'hotel_order_items' ) . " i ON o.inter_id=i.inter_id AND o.orderid=i.orderid
				 LEFT JOIN " . $this->db->dbprefix ( 'hotel_order_additions' ) . " oa 
				 ON oa.orderid=o.orderid AND oa.inter_id=o.inter_id WHERE ".$order_condition;
		
		
		$result = $this->db->query ( $sql )->result ();
		$head = array (
				'订单类别',
				'订单ID',
				'pms单号',
				'酒店',
				'房型',
				'姓名',
				'电话',
				'下单时间',
				'入住时间',
				'离店时间',
				'房间数',
				'间夜数',
				'原价',
				'优惠信息',
				'总价',
				'价格代码',
				'支付状态',
				'订单状态' 
		);
		$public = $this->db->get_where ( 'publics', array (
				'inter_id' => $inter_id 
		) )->row_array ();
		if (! empty ( $result )) {
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
			$end_status = array (
					3,
					4,
					5,
					8,
					11
			);
			$hotels = $this->db->get_where ( 'hotels', array (
					'inter_id' => $inter_id 
			) )->result_array ();
			$h = array ();
			foreach ( $hotels as $ht ) {
				$h [$ht ['hotel_id']] = $ht ['name'];
			}
			
			$exl_data = array ();
			$heads = array ();
			$sheet_names = array ();
			$order_data = $this->mainorder_only ( $result, $h, $status_des );
			$exl_data [] = $order_data ['data'];
			$heads [] = $order_data ['head'];
			$sheet_names [] = '仅主单';
			
			$order_data = $this->suborder_data ( $result, $h, $status_des );
			$exl_data [] = $order_data ['data'];
			$heads [] = $order_data ['head'];
			$filename = $public ['name'] . '_' . date ( 'YmdHis' );
			$sheet_names [] = '含子单';
			$this->Excel_model->exp_exl_multisheet ( $heads, $exl_data, $filename, $sheet_names );
		} else {
			$filename = $public ['name'] . '_' . date ( 'YmdHis' );
			$this->Excel_model->exp_exl ( $head, $data, $filename );
		}
	}
	function suborder_data($result, $h, $status_des) {
		$count = count ( $result );
		$data = array ();
		for($i = 0; $i < $count;) {
			for($j = 0; $j <= $result [$i]->roomnums; $j ++) {
				$tmp = array ();
				if ($j == 0) {
					// $main_order = $result [$i];
					$tmp ['type'] = '主订单';
					$tmp ['orderid'] = $result [$i]->orderid;
					$tmp ['web_orderid'] = $result [$i]->web_orderid;
					$tmp ['hotel'] = $h [$result [$i]->hotel_id];
					$tmp ['room'] = $result [$i]->roomname;
					if (strlen ( $result [$i]->name ) > 3)
						$tmp ['custom'] = mb_strcut ( $result [$i]->name, 0, intval ( strlen ( $result [$i]->name ) * 0.7 ), 'UTF-8' ) . '*';
					else
						$tmp ['custom'] = $result [$i]->name;
					$tmp ['tel'] = substr_replace ( $result [$i]->tel, '****', 4, 4 );
					// $tmp['tel']=$result[$i]->tel;
					$tmp ['book_date'] = date ( 'Y-m-d', $result [$i]->order_time );
					$tmp ['book_time'] = date ( 'H:i:s', $result [$i]->order_time );
					$tmp ['startdate'] = date ( "Y-m-d", strtotime ( $result [$i]->startdate ) );
					$tmp ['enddate'] = date ( "Y-m-d", strtotime ( $result [$i]->enddate ) );
					$tmp ['roomnums'] = $result [$i]->roomnums;
					$countday = get_room_night($result [$i]->startdate , $result [$i]->enddate ,'round' , $result [$i]);//至少有1个间夜
					$tmp ['room_nights'] = $result [$i]->roomnums * $countday;
					$tmp ['ori_price'] = 0;
					$tmp ['coupon_favor'] = intval ( $result [$i]->coupon_favour ) + floatval ( $result [$i]->point_favour );
					$tmp ['price'] = 0;
					$tmp ['price_name'] = '';
					$tmp ['paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$result [$i]->paid];
					if ($result [$i]->roomnums > 1)
						$tmp ['status'] = '多订单';
					else
						$tmp ['status'] = $status_des ['HOTEL_ORDER_STATUS'] [$result [$i]->status];
					$tmp ['strange'] = "";
					$tmp ['reason'] = "";
					$data [$result [$i]->orderid] = $tmp;
				} else {
					$reason = '';
					$flag = 0;
					$tmp ['type'] = '';
					$tmp ['orderid'] = '订单' . $j;
					$tmp ['web_orderid'] = $result [$i + $j - 1]->webs_orderid;
					$tmp ['hotel'] = '';
					$tmp ['room'] = $result [$i + $j - 1]->roomname;
					$tmp ['custom'] = '';
					$tmp ['tel'] = '';
					$tmp ['book_date'] = '';
					$tmp ['book_time'] = '';
					$tmp ['startdate'] = date ( "Y-m-d", strtotime ( $result [$i + $j - 1]->istartdate ) );
					$tmp ['enddate'] = date ( "Y-m-d", strtotime ( $result [$i + $j - 1]->ienddate ) );
					$tmp ['roomnums'] = 1;
					$tmp ['room_nights'] = get_room_night($result [$i + $j - 1]->istartdate , $result [$i + $j - 1]->ienddate ,'round' , $result [$i + $j - 1]);//至少有1个间夜
					$tmp ['ori_price'] = array_sum ( explode ( ',', $result [$i + $j - 1]->allprice ) );
					$tmp ['coupon_favor'] = '';
					$tmp ['price'] = $result [$i + $j - 1]->iprice;
					$tmp ['price_name'] = $result [$i + $j - 1]->price_code_name;
					$data [$result [$i]->orderid] ['price'] += $tmp ['price'];
					$data [$result [$i]->orderid] ['ori_price'] += $tmp ['ori_price'];
					$tmp ['paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$result [$i]->paid];
					$tmp ['status'] = $status_des ['HOTEL_ORDER_STATUS'] [$result [$i + $j - 1]->istatus];
					$tmp ['strange'] = "";
					$tmp ['reason'] = "";
					$data [$result [$i]->orderid . $j] = $tmp;
				}
			}
			$i += $result [$i]->roomnums;
		}
		$head = array (
				'订单类别',
				'订单ID',
				'pms单号',
				'酒店',
				'房型',
				'姓名',
				'电话',
				'下单日期',
				'下单时间',
				'入住时间',
				'离店时间',
				'房间数',
				'间夜数',
				'原价',
				'优惠信息',
				'总价',
				'价格代码',
				'支付状态',
				'订单状态' 
		);
		return array (
				'data' => $data,
				'head' => $head 
		);
	}
	function mainorder_only($result, $h, $status_des) {
		$count = count ( $result );
		$data = array ();
		$valid_status = array (
				0,
				1,
				2,
				3 
		);
		$invalid_status = array (
				4,
				5,
				8,
				9,
				10,
				11
		);
		for($i = 0; $i < $count;) {
			$flag = 0;
			for($j = 0; $j <= $result [$i]->roomnums; $j ++) {
				$tmp = array ();
				if ($j == 0) {
					// $main_order = $re sult [$i];
					// $tmp ['type'] = '主订单';
					$tmp ['orderid'] = $result [$i]->orderid;
					$tmp ['web_orderid'] = $result [$i]->web_orderid;
					$tmp ['hotel'] = $h [$result [$i]->hotel_id];
					$tmp ['room'] = $result [$i]->roomname;
					if (strlen ( $result [$i]->name ) > 3)
						$tmp ['custom'] = mb_strcut ( $result [$i]->name, 0, intval ( strlen ( $result [$i]->name ) * 0.7 ), 'UTF-8' ) . '*';
					else
						$tmp ['custom'] = $result [$i]->name;
					$tmp ['tel'] = substr_replace ( $result [$i]->tel, '****', 4, 4 );
					// $tmp['tel']=$result[$i]->tel;
					$tmp ['book_date'] = date ( 'Y-m-d', $result [$i]->order_time );
					$tmp ['book_time'] = date ( 'H:i:s', $result [$i]->order_time );
					$tmp ['startdate'] = date ( "Y-m-d", strtotime ( $result [$i]->startdate ) );
					$tmp ['enddate'] = date ( "Y-m-d", strtotime ( $result [$i]->enddate ) );
					$tmp ['roomnums'] = $result [$i]->roomnums;
					$tmp ['real_roomnums'] = 0;
					// $tmp ['room_nights'] = $result [$i]->roomnums * (round ( (strtotime ( $result [$i]->enddate ) - strtotime ( $result [$i]->startdate )) / 86400 ));
					$tmp ['total_room_nights'] = 0;
					$tmp ['real_room_nights'] = 0;
					$tmp ['ori_price'] = 0;
					$tmp ['coupon_favor'] = intval ( $result [$i]->coupon_favour ) + floatval ( $result [$i]->point_favour );
					$tmp ['price'] = 0;
					$tmp ['real_price'] = 0;
					$tmp ['price_name'] = $result [$i]->price_code_name;
					$tmp ['paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$result [$i]->paid];
					// if ($result [$i]->roomnums > 1)
					// $tmp ['status'] = '多订单';
					// else
					$tmp ['status'] = $status_des ['HOTEL_ORDER_STATUS'] [$result [$i]->istatus];
					$tmp ['all_status'] = '';
					$tmp ['strange'] = "";
					$tmp ['reason'] = "";
					$data [$result [$i]->orderid] = $tmp;
				} else {
					$tmp = array ();
					$tmp ['startdate'] = date ( "Y-m-d", strtotime ( $result [$i + $j - 1]->istartdate ) );
					$tmp ['enddate'] = date ( "Y-m-d", strtotime ( $result [$i + $j - 1]->ienddate ) );
					// $tmp ['roomnums'] = 1;
					$tmpcountday = get_room_night($result [$i + $j - 1]->istartdate, $result [$i + $j - 1]->ienddate,'round',$result [$i + $j - 1]);//至少有一个间夜
					$data [$result [$i]->orderid] ['total_room_nights'] += $tmpcountday;
					if (in_array ( $result [$i + $j - 1]->istatus, $valid_status )) {
						$data [$result [$i]->orderid] ['real_room_nights'] += $tmpcountday;
						$data [$result [$i]->orderid] ['real_price'] += $result [$i + $j - 1]->iprice;
						$data [$result [$i]->orderid] ['real_roomnums'] += 1;
					}
					// $tmp ['room_nights'] = round ( (strtotime ( $result [$i + $j - 1]->ienddate ) - strtotime ( $result [$i + $j - 1]->istartdate )) / 86400 );
					// $tmp ['ori_price'] = array_sum ( explode ( ',', $result [$i + $j - 1]->allprice ) );
					$tmp ['coupon_favor'] = '';
					// $tmp ['price'] = $result [$i + $j - 1]->iprice;
					$tmp ['price_name'] = $result [$i + $j - 1]->price_code_name;
					$data [$result [$i]->orderid] ['price'] += $result [$i + $j - 1]->iprice;
					$data [$result [$i]->orderid] ['ori_price'] += array_sum ( explode ( ',', $result [$i + $j - 1]->allprice ) );
					$tmp ['paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$result [$i]->paid];
					$tmp ['status'] = $status_des ['HOTEL_ORDER_STATUS'] [$result [$i + $j - 1]->istatus];
					if ($data [$result [$i]->orderid] ['status'] != $tmp ['status']) {
						$data [$result [$i]->orderid] ['status'] = '多订单';
						$flag = 1;
					}
					if (! empty ( $data [$result [$i]->orderid] ['all_status'] )) {
						$data [$result [$i]->orderid] ['all_status'] .= ',';
					}
					$data [$result [$i]->orderid] ['all_status'] .= '订单' . $j . ':' . $tmp ['status'];
					$tmp ['strange'] = "";
					$tmp ['reason'] = "";
				}
			}
			if ($flag == 0) {
				$data [$result [$i]->orderid] ['all_status'] = '';
			}
			$i += $result [$i]->roomnums;
			// var_dump($data);exit;
		}
		$head = array (
				'订单ID',
				'pms单号',
				'酒店',
				'房型',
				'姓名',
				'电话',
				'下单日期',
				'下单时间',
				'入住时间',
				'离店时间',
				'房间数',
				'有效房间数',
				'原总间夜数',
				'有效总间夜数',
				'原价',
				'优惠信息',
				'总价',
				'有效总价',
				'价格代码',
				'支付状态',
				'订单状态',
				'所有订单状态' 
		);
		// '是否异常',
		// '异常原因'
		
		return array (
				'data' => $data,
				'head' => $head 
		);
	}
	public function ajax_query_order() {
		//检查声音提醒设置
		$this->load->model('hotel/hotel_notify_model');
		$notifys = $this->hotel_notify_model->get_admin_notify_config($this->inter_id);
		if($notifys['is_voice']==1){
			$check_time = $this->input->get ( 't' );
			$check_time = empty ( $check_time ) ? time () : intval ( $check_time );
			$this->load->model ( 'hotel/Order_check_model', 'checkm' );
			$nums = $this->checkm->check_time_range_order ( $this->inter_id, 'gt_order_time', array (
					'hotel_ids' => explode(',', $this->session->get_admin_hotels ()),
					'check_time' => $check_time,
					'exp_status' => array(9,10), 
			), 'nums' );
			echo json_encode ( array (
					'status' => 1,
					'total' => $nums,
					'message' => '' 
			) );
		}else{
			echo json_encode ( array (
					'status' => 1,
					'total' => 0,
					'message' => '' 
			) );
		}
	}
}
