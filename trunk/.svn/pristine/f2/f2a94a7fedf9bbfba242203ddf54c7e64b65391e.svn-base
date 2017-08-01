<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Order_interface extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
		ini_set ( 'display_errors', 0 );
		if (version_compare ( PHP_VERSION, '5.3', '>=' )) {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_USER_NOTICE & ~ E_USER_DEPRECATED );
		} else {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_STRICT & ~ E_USER_NOTICE );
		}
	}
	public function get_roomstate() {
		try {
			$now = time ();
			$this->load->model ( 'interface/Icommon_model' );
			$this->load->model ( 'interface/Isigniture_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			if (empty ( $source ['hotel_id'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'wrong hotel id' );
			}
			$this->load->model ( 'hotel/Hotel_model' );
			$inter_id = $source ['itd'];
			$hotel_id = $source ['hotel_id'];
			$rooms = $this->Hotel_model->get_hotel_rooms ( $inter_id, $hotel_id, 1 );
			$this->load->model ( 'hotel/Member_model' );
			$member_privilege = $this->Member_model->level_privilege ( $inter_id );
			$levels = $this->Member_model->get_member_levels ( $inter_id );
			$condit = array (
					'startdate' => $source ['startdate'],
					'enddate' => $source ['enddate'],
                    'is_comprice' => 1
			);
			if (! empty ( $member_privilege )) {
				$condit ['member_privilege'] = $member_privilege;
			}
			if (! empty ( $levels )) {
				$condit ['member_level'] = current ( array_keys ( $levels ) );
			}
			$this->load->library ( 'PMS_Adapter', array (
					'inter_id' => $inter_id,
					'hotel_id' => $hotel_id 
			), 'pmsa' );
			$rooms = $this->pmsa->get_rooms_change ( $rooms, array (
					'inter_id' => $inter_id,
					'hotel_id' => $hotel_id 
			), $condit, true );
			$rooms = $this->_room_state_format ( $rooms );
			
			$this->load->helper('common');
			$this->load->model('common/Webservice_model');
			$this->Webservice_model->add_webservice_record($inter_id, 'local', $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], $source, $rooms,'rec_post', $now, microtime (), getIp());
			
			$this->Icommon_model->out_put_msg ( TRUE, '查询成功', $rooms );
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	private function _room_state_format($state) {
		$filter = array (
				'room' => array (
						'room_id',
						'name' 
				),
				'state' => array (
						'price_name',
						'price_code',
				        'des',
						'total_price',
						'avg_price',
						'book_status',
                        'bookpolicy_condition'
				),
				'others' => array (
						'show_info',
						'all_full',
						'top_price' 
				) 
		);
		foreach ( $state as $room_id => &$sta ) {
			$sta ['state_info'] = $sta ['state_info'] + $sta ['show_info'];
			foreach ( $sta ['room_info'] as $rk => $ri ) {
				if (! in_array ( $rk, $filter ['room'] )) {
					unset ( $sta ['room_info'] [$rk] );
				}
			}
			foreach ( $sta ['state_info'] as $price_code => $price_info ) {
				foreach ( $price_info as $pk => $pi ) {
					if (! in_array ( $pk, $filter ['state'] )) {
						unset ( $sta ['state_info'] [$price_code] [$pk] );
					}
				}
			}
			foreach ( $sta as $sk => $st ) {
				if (in_array ( $sk, $filter ['others'] )) {
					unset ( $sta [$sk] );
				}
			}
		}
		return $state;
	}
	
	public function point_orders(){
		try {
			$now = time ();
			$this->load->model ( 'interface/Icommon_model' );
			$this->load->model ( 'interface/Isigniture_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$this->load->model ( 'hotel/Hotel_model' );
			$inter_id = $source ['itd'];
			// $hotel_id = $source ['hotel_id'];
			if ($inter_id != 'a421641095') {
				$this->Icommon_model->out_put_msg ( FALSE, 'invalid token' );
			}
			$this->load->model ( 'hotel/Order_info_model' );
			$params=array();
			$date_params=array(
				'min_startdate',	
				'max_startdate',	
				'min_enddate',	
				'max_enddate'	
			);
			foreach ($date_params as $p){
				if (!empty($source[$p])){
					if (!strtotime($source[$p])){
						$this->Icommon_model->out_put_msg ( FALSE, 'invalid parameter '.$p );
					}else {
						$params[$p]=date('Ymd',strtotime($source[$p]));
					}
				}
			}
			if (!empty($source['min_booktime'])){
				if (!strtotime($source['min_booktime'])){
					$this->Icommon_model->out_put_msg ( FALSE, 'invalid parameter min_booktime' );
				}else {
					$params['min_booktime']=strtotime($source['min_booktime']);
				}
			}
			if (!empty($source['max_booktime'])){
				if (!strtotime($source['max_booktime'])){
					$this->Icommon_model->out_put_msg ( FALSE, 'invalid parameter max_booktime' );
				}else {
					$params['max_booktime']=strtotime($source['max_booktime']);
				}
			}
			$point_orders = $this->Order_info_model->get_point_order ( $inter_id, $params );
			$datas = array ();
			$row = array (
					'member_no' => '',
					'customer' => '',
					'phone' => '',
					'roomnums' => '',
					'ori_price' => '',
					'price' => '',
					'pms_orderid' => '',
					'pms_order_item_id' => '',
					'paytype' => '',
					'paid' => '',
					'hotel_name' => '',
					'room_name' => '',
					'startdate' => '',
					'enddate' => '',
					'status' => '',
					'order_time' => '',
					'point_favour' => '',
					'point_amount' => '' 
			);
			$point_count = array ();
			$point_rate = 10;
			$this->load->model ( 'hotel/Hotel_model' );
			$hotels = $this->Hotel_model->get_all_hotels ( $inter_id );
			$hotels = array_column ( $hotels, 'name', 'hotel_id' );
			$paytypes = array (
					'weixin' => '微信支付',
					'daofu' => '到店支付',
					'point' => '积分支付',
					'balance' => '储值支付' 
			);
			$paid_status = array (
					'0' => '未支付',
					'1' => '已支付' 
			);
			$status = array (
					'0' => '待确认',
					'1' => '已确认',
					'2' => '已入住',
					'3' => '已离店',
					'4' => '用户取消',
					'5' => '酒店取消',
					'6' => '酒店删除',
					'7' => '异常',
					'8' => '未到',
					'9' => '未支付',
					'10' => '下单失败',
					'11' => '系统取消' 
			);
			foreach ( $point_orders as $p ) {
				$tmp = $row;
				$tmp ['member_no'] = $p ['member_no'];
				$tmp ['customer'] = $p ['name'];
				$tmp ['phone'] = $p ['tel'];
				$tmp ['roomnums'] = 1;
				$tmp ['ori_price'] = array_sum ( explode ( ',', $p ['allprice'] ) );
				$tmp ['price'] = array_sum ( explode ( ',', $p ['real_allprice'] ) );
				empty ( $tmp ['price'] ) and $tmp ['price'] = $p ['iprice'];
				$tmp ['orderid'] = $p ['orderid'];
				$tmp ['pms_orderid'] = $p ['web_orderid'];
				$tmp ['pms_order_item_id'] = $p ['webs_orderid'];
				$tmp ['paytype'] = empty ( $paytypes [$p ['paytype']] ) ? $p ['paytype'] : $paytypes [$p ['paytype']];
				$tmp ['paid'] = $paid_status [$p ['paid']];
				$tmp ['hotel_name'] = $hotels [$p ['hotel_id']];
				$tmp ['room_name'] = $p ['roomname'];
				$tmp ['startdate'] = date ( 'Y-m-d', strtotime ( $p ['istart'] ) );
				$tmp ['enddate'] = date ( 'Y-m-d', strtotime ( $p ['iend'] ) );
				if ($p ['status'] == 9 && $p ['istatus'] == 0) {
					$p ['istatus'] = 9;
				}
				$tmp ['status'] = $status [$p ['istatus']];
				$tmp ['order_time'] = date ( 'Y-m-d H:i:s', $p ['order_time'] );
				if (empty ( $point_count [$p ['orderid']] )) {
					$point_count [$p ['orderid']] ['avg_point'] = intval ( $p ['point_used_amount'] / $p ['roomnums'] );
					$point_count [$p ['orderid']] ['extra_point'] = $p ['point_used_amount'] - ($point_count [$p ['orderid']] ['avg_point'] * $p ['roomnums']);
					$point_count [$p ['orderid']] ['extra'] = ($point_count [$p ['orderid']] ['avg_point'] - intval ( $point_count [$p ['orderid']] ['avg_point'] / $point_rate ) * $point_rate) * $p ['roomnums'];
					$tmp ['point_amount'] += $point_count [$p ['orderid']] ['extra'] + $point_count [$p ['orderid']] ['extra_point'];
				}
				$tmp ['point_amount'] += intval ( $point_count [$p ['orderid']] ['avg_point'] / $point_rate ) * $point_rate;
				$tmp ['point_favour'] = $tmp ['point_amount'] / $point_rate;
				$datas [$p ['web_orderid'] . '_' . $p ['sub_id']] = $tmp;
			}
			
			$this->load->helper ( 'common' );
			$this->load->model ( 'common/Webservice_model' );
			$this->Webservice_model->add_webservice_record ( $inter_id, 'localorder', $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], $source, $rooms, 'rec_post', $now, microtime (), getIp () );
			
			$this->Icommon_model->out_put_msg ( TRUE, '查询成功', array_values($datas) );
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
}