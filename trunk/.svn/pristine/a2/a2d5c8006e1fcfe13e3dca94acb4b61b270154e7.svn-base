<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Room_status extends MY_Admin {
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
	}
	protected $label_module = NAV_HOTEL_ROOM_STATUS;
	protected $label_controller = '房态维护';
	protected $label_action = '';
	
	// private static $inter_id='a429262687';
	function index() {
		$this->label_action = '房态维护';
		$this->_init_breadcrumb ( $this->label_action );
		// $params ['hotel_id'] = $this->input->get_post ( 'hotel_id' );
		$params ['hotel_id'] = $this->input->get_post ( 'hotel' );
		$params ['room_id'] = $this->input->get_post ( 'room_id' );
		$params ['price_code'] = $this->input->get_post ( 'price_code' );
		$calendar = '';
		// if ($_POST) {
		$begin_date=$this->input->get_post ( 'begin' );
		$end_date=$this->input->get_post ( 'end' );
		if (empty($begin_date)||!strtotime($begin_date)){
			$begin_date=date('Y-m-d');
		}
		if (empty($end_date)||!strtotime($end_date)){
			$end_date=date('Y-m-d',strtotime('+ 1 day',strtotime($begin_date)));
		}
		$params ['begin'] = $begin_date;
		$params ['end'] = $end_date;
		// $begin_date = '2015-11-23';
		// $end_date = '2015-12-31';
		// }
		
		$inter_id = $this->session->get_admin_inter_id ();
		$hotels = array ();
		if ($inter_id == FULL_ACCESS) {
			$this->db->where ( array (
					'status' => 1 
			) );
			$this->db->select ( array (
					'hotel_id',
					'name' 
			) );
			$hotels = $this->db->get ( 'hotels' )->result_array ();
		} else {
			$this->load->model ( 'hotel/hotel_model' );
			$user_profiler = $this->session->userdata ( 'admin_profile' );
			$entity_id = $this->session->get_admin_hotels ();
			if (! empty ( $entity_id ))
				$hotels = $this->hotel_model->get_hotel_by_ids ( $user_profiler ['inter_id'], $entity_id );
			else
				$hotels = $this->hotel_model->get_all_hotels ( $user_profiler ['inter_id'] );
		}
		$room_infos = array ();
		$this->load->model ( 'hotel/hotel_price_info_model' );
		if (! isset ( $params ['hotel_id'] )) {
			$params ['hotel_id'] = empty ( $hotels [0] ) ? 0 : $hotels [0] ['hotel_id'];
		}
		$room_infos = $this->hotel_price_info_model->get_hotel_room_to_codes ( $this->inter_id, $params ['hotel_id'], 1, 1);
		if (isset ( $params ['room_id'] ) && isset ( $params ['price_code'] ) && isset ( $begin_date ) && isset ( $end_date )) {
			$this->load->model ( 'hotel/rooms_model' );
			$res = $this->rooms_model->get_day_room_price ( $this->inter_id, $params ['hotel_id'], $params ['room_id'], $params ['price_code'], $begin_date, $end_date )->result ();
			
			$date_arr = array ();
			foreach ( $res as $item ) {
				$date_arr [$item->date] = array (
						'price' => $item->price,
						'nums' => $item->nums 
				);
			}
			
			$calendar = $this->generate_calendar ( $begin_date, $end_date, $date_arr );
		}
		$data = array (
				'calendar' => $calendar,
				'hotels' => $hotels,
				'param' => $params,
				'ris' => $room_infos 
		);
		
		$html = $this->_render_content ( $this->_load_view_file ( 'index' ), $data, true );
		// $this->load->view($this->_get_template().'/index');
		echo $html;
	}
	public function room_types() {
		$hotel_id = $this->input->get ( 'hid', true );
		if ($hotel_id) {
			$this->load->model ( 'hotel/hotel_price_info_model' );
			$room_infos = $this->hotel_price_info_model->get_hotel_room_to_codes ( $this->inter_id, $hotel_id, 1, 1);
			echo json_encode ( $room_infos );
		} else {
			echo json_encode ( array (
					'errmsg' => 'faild' 
			) );
		}
	}
	public function price_codes() {
		$hotel_id = $this->input->get ( 'hid', true );
		$room_id = $this->input->get ( 'rid', true );
		if ($hotel_id) {
			$this->load->model ( 'hotel/hotel_price_info_model' );
			$user_profiler = $this->session->userdata ( 'admin_profile' );
			$codes = $this->hotel_price_info_model->get_price_codes ( $this->inter_id, $hotel_id )->result ();
			echo json_encode ( $codes );
		} else {
			echo json_encode ( array (
					'errmsg' => 'faild' 
			) );
		}
	}
	public function save_day_price() {
		$days = $this->input->post ( 'daybox' );
		$hotel_id = $this->input->post ( 'hotel_id', true );
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! in_array ( $hotel_id, $hotel_ids )) {
				echo '无权限';
				exit ();
			}
		}
		$room_id = $this->input->post ( 'room_id', true );
		$price_code = $this->input->post ( 'price_code', true );
		$price = $this->input->post ( 'price', true );
		$room_num = $this->input->post ( 'room_num', true );
		if (empty ( $days )) {
			echo '请选择修改日期';
			exit ();
		}
		if (empty ( $price )) {
			echo '请输入价格';
			exit ();
		}
		if ($room_num == '')
			$room_num = null;
		$this->load->model ( 'hotel/room_status_model' );
		// ($inter_id,$hotel_id,$room_id,$price_code,$price,$nums,$day_arr)
		if ($this->room_status_model->save_room_price ( $this->inter_id, $hotel_id, $room_id, $price_code, $price, $room_num, $days )) {
			redirect ( 'hotel/room_status/index' . '?hotel=' . $hotel_id . '&room_id=' . $room_id );
			// echo json_encode ( array (
			// 'msg' => 'true',
			// 'error' => ''
			// ) );
		} else {
			echo '操作失败';
			exit ();
			// echo json_encode ( array (
			// 'msg' => 'faild',
			// 'error' => '操作失败'
			// ) );
		}
	}
	private function generate_calendar($begin_date, $end_date, $hdaysets = null) {
		$begin_date = strtotime ( $begin_date );
		$end_date = strtotime ( $end_date );
		$table = '';
		$current_date = $begin_date;
		$day_of_week = '';
		$week = array (
				'sunday',
				'monday',
				'tuesday',
				'wednesday',
				'thursday',
				'friday',
				'saturday' 
		);
		
		$week_title = '<tr><td><input type="checkbox" week="monday" rel="weektitle" />星期一</td>';
		$week_title .= '<td><input type="checkbox" week="tuesday" rel="weektitle" />星期二</td>';
		$week_title .= '<td><input type="checkbox" week="wednesday" rel="weektitle" />星期三</td>';
		$week_title .= '<td><input type="checkbox" week="thursday" rel="weektitle" />星期四</td>';
		$week_title .= '<td><input type="checkbox" week="friday" rel="weektitle" />星期五</td>';
		$week_title .= '<td style=\'color:red\'><input type="checkbox" week="saturday" rel="weektitle" />星期六</td>';
		$week_title .= '<td style=\'color:red\'><input type="checkbox" week="sunday" rel="weektitle" />星期日</td></tr><tr>';
		
		$table = "<table class=\"table table-striped\">";
		$day_of_week = date ( 'w', $begin_date ); // day of week
		$day = date ( 'j', $begin_date ); // day of month
		$month_count = (date ( "Y", $end_date ) - date ( "Y", $begin_date )) * 12 + (date ( "m", $end_date ) - date ( "m", $begin_date )); // 月份差
		$table .= '<tr>';
		
		$datediff = date_diff ( new DateTime ( date ( 'Y-m-d', $begin_date ) ), new DateTime ( date ( 'Y-m-d', $end_date ) ) );
		$monthspan = date('m',$end_date)-date('m',$begin_date)+1;
		$datespan = $datediff->format ( '%a' );
		$count = 1;
		for($i = 0; $i < $monthspan; $i ++) {
			$table .= "<tr align='center'><td colspan='7' style='text-align:center'>" . date ( 'Y年m月', $current_date ) . "</td></tr>";
			$table .= $week_title;
			$blank_num = $day_of_week;
			if ($day_of_week == 0)
				$blank_num = 7;
			for($ii = 1; $ii < $blank_num; $ii ++) { // 之前的空白日期
				$table .= '<td>&nbsp;</td>';
			}
			$last_day_of_month = date ( 't', $current_date );
			$week_index = $day_of_week;
			for(; $day <= $last_day_of_month && $count <= $datespan; $day ++) {
				$daystr = $day >= 10 ? $day : '0' . $day;
				$date_str = date ( 'Ym', $current_date ) . $daystr;
				$cur_str = '';
				$num_str = '-';
				if (isset ( $hdaysets [$date_str] ['nums'] ))
					$num_str = $hdaysets [$date_str] ['nums'];
				if (isset ( $hdaysets [$date_str] ))
					$cur_str = '(<span>' . $hdaysets [$date_str] ['price'] . '/<i>' . $num_str . '</i></span>)';
				if ($day_of_week % 7 == 0) { // 星期换行
					$week_index = 0;
					$table .= '<td><input type="checkbox" week="' . $week [$week_index] . '" t="daybox" name="daybox[]" value="' . $date_str . '" />' . $day . $cur_str . '</td></tr><tr>';
				} else {
					$table .= '<td><input type="checkbox" week="' . $week [$week_index] . '" t="daybox" name="daybox[]" value="' . $date_str . '" />' . $day . $cur_str . '</td>';
				}
				$day_of_week ++;
				$count ++;
				$week_index ++;
			}
			$day = 1;
            
			$current_date = strtotime ( '+1 month', $current_date );
			$day_of_week = date ( 'w', strtotime ( date ( 'Y-m', $current_date ) . '-01' ) );
		}
		return $table .= '</tr></table>';
	}
	
	public function price_calendar() {
		$this->label_action = '房态维护';
		$this->_init_breadcrumb ( $this->label_action );
		$inter_id = $this->session->get_admin_inter_id ();
		$hotels = array ();
		if ($inter_id == FULL_ACCESS) {
			$this->db->where ( array (
					'status' => 1 
			) );
			$this->db->select ( array (
					'hotel_id',
					'name' 
			) );
			$hotels = $this->db->get ( 'hotels' )->result_array ();
		} else {
			$this->load->model ( 'hotel/hotel_model' );
			$user_profiler = $this->session->userdata ( 'admin_profile' );
			$entity_id = $this->session->get_admin_hotels ();
			if (! empty ( $entity_id ))
				$hotels = $this->hotel_model->get_hotel_by_ids ( $user_profiler ['inter_id'], $entity_id );
			else
				$hotels = $this->hotel_model->get_all_hotels ( $user_profiler ['inter_id'],1 );
		}
		$yestoday =date('Y-m-d' , time()-24*60*60);
		$data = array (
				'hotels' => $hotels,
				'yestoday' =>$yestoday
		);
		echo $this->_render_content ( $this->_load_view_file ( 'price_calendar' ), $data, true );
	}
	public function get_price_codes(){

		$hotel_id = $this->input->get ( 'hotel' );
		if (empty ( $hotel_id )) {
			echo json_encode(array('code'=>1,'msg'=>'请选择酒店'));exit;
		}
		$calendar = '';
		$begin_date = $this->input->get ( 'begindate' );
		if (empty ( $begin_date )) {
			echo json_encode(array('code'=>1,'msg'=>'请选择日期'));exit;
		}
		$begin_date =  date('Ymd',strtotime($begin_date));
		$end_date =  date('Ymd',strtotime($begin_date)+24*60*60*10);
		$inter_id = $this->session->get_admin_inter_id ();
		$room_infos = array ();
		$this->load->model ( 'hotel/hotel_price_info_model' );
		
		$room_infos = $this->hotel_price_info_model->get_hotel_room_to_codes ( $this->inter_id, $hotel_id, 1 , 1);
		$this->load->model ( 'hotel/rooms_model' );

		$date =array();
		$pri_date =array(); //初始化价格库存房态
		$weekarray=array("周日","周一","周二","周三","周四","周五","周六");
		$firsttime = strtotime($begin_date);
		for ($i = 0; $i < 10 ; $i++) {
			$date_detail = array();
			$date_detail['date'] = date('Ymd',$firsttime+24*60*60*$i);
			$date_detail['week'] = $weekarray[date("w",$firsttime+24*60*60*$i)];
			$date[] = $date_detail;
			$pri_date[$date_detail['date']] = array('price'=>'','nums'=>'','ftai'=>'-2');
		}
		$room_infos['date'] = $date;
		//查询房型默认库存
		$roomlist = $this->rooms_model->get_hotels_rooms($this->inter_id,array($hotel_id),'hotel_id,room_id,nums',1,1);
		
		$this->load->model ( 'hotel/Price_code_model' );
		$this->load->model ( 'hotel/Hotel_model' );
		$this->load->model ( 'hotel/Order_model' );
		$this->load->model ( 'hotel/Member_model' );
		$member_privilege = $this->Member_model->level_privilege ( $this->inter_id );
		$levels = $this->Member_model->get_member_levels ( $this->inter_id );

		$condit = array (
				'startdate' => $begin_date,
				'enddate' => $end_date,
				'price_type' => array (
						'protrol'
				)
		);
		
		if (! empty ( $member_privilege )) {
			$condit ['member_privilege'] = $member_privilege;
		}

		foreach ($room_infos['codes'] as $room_id => $price_codes) {
			//查询房态
			$res = $this->rooms_model->get_day_room_state ( $this->inter_id, $hotel_id, $room_id, $begin_date, $end_date )->result ();
			$ftai_arr = $pri_date;
			foreach ($ftai_arr as  $ftai_date => $ftai) {
				$ftai_arr[$ftai_date]['nums'] = $roomlist[$hotel_id][$room_id]['nums'];
			}
			foreach ( $res as $item ) {
				if(is_null($item->nums)){
					$item->nums = '';
				}
				$ftai_arr [$item->date] = array (
						'price' => $item->price,
						'nums' => $item->nums,
						'ftai' => $item->price_code
				);
			}
			$room_infos['codes'][$room_id]['date_arr'] = $ftai_arr;
			$room = $this->Hotel_model->get_rooms_detail ( $this->inter_id, $hotel_id, array (
					$room_id
			), array () );

			foreach ($price_codes['codes'] as $price_code => $value) {
				$list = $this->Price_code_model->get_room_price_set ( $this->inter_id, $hotel_id, $room_id, $price_code );
				if (empty ( $list )) {
					unset($room_infos['codes'][$room_id]['codes'][$price_code]);
					continue;
				}
				$list = $list [0];
				$list ['use_condition'] = empty ( $list ['suse_condition'] ) ? json_decode ( $list ['use_condition'], TRUE ) : json_decode ( $list ['suse_condition'], TRUE );
				
				$condit['price_codes'] = $price_code;
				
				if (isset ( $list ['use_condition'] ['member_level'] )) {
					$condit ['member_level'] = $list ['use_condition'] ['member_level'];
				}else {
					if (! empty ( $levels )) {
						$condit ['member_level'] = current ( array_keys ( $levels ) );
					}
				}

				$states = $this->Order_model->get_rooms_change_calendar ( $room, array (
						'inter_id' => $this->inter_id,
						'hotel_id' => $hotel_id,
						'query_site' => 'admin'
				), $condit, TRUE );
				$room_state = empty ( current ( $states ) ) ? array () : current ( $states );
				$res = empty ( $room_state ['state_info'] [ $price_code ] ['date_detail'] ) ? array () : $room_state ['state_info'] [ $price_code ] ['date_detail'];

				$date_arr = $pri_date;
				foreach ( $res as $dd => $item ) {
					$date_arr [$dd]['price'] = $item['price'];
					$date_arr [$dd]['nums'] = $item['nums'];
				}
				$room_infos['codes'][$room_id]['codes'][$price_code] = array('name'=>$value,'date_arr'=>$date_arr);
			}
		}
		
		echo json_encode($room_infos);
	}

	//保存价格日历数据修改
	public function save_calendar_price() {
		$days = $this->input->get ( 'daybox' );
		if(empty($days)){
			$startdate = $this->input->get ( 'startdate' );
			$enddate = $this->input->get ( 'enddate' );
			$weekarray = $this->input->get ( 'weekarray' );
			if(empty($startdate) || empty($enddate) || empty($weekarray) ){
				echo json_encode(array('code'=>1,'msg'=>'缺少参数'));exit;
			}
			$startdate = strtotime($startdate);
			$enddate = strtotime($enddate);
			$days =array();
			while ( $startdate <= $enddate) {
				if(in_array(date("w",$startdate),$weekarray)){
					$days[] = date('Ymd',$startdate);
				}
				$startdate += 24*60*60;
			}
		}
		$hotel_id = $this->input->get ( 'hotel_id', true );
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! in_array ( $hotel_id, $hotel_ids )) {
				echo json_encode(array('code'=>1,'msg'=>'没有权限'));exit;
			}
		}
		$room_id = $this->input->get ( 'room_id', true );
		$price_code = $this->input->get ( 'price_code', true );
		$price = $this->input->get ( 'price', true );
		$room_num = $this->input->get ( 'room_num', true );
		if (empty ( $days )) {
			echo json_encode(array('code'=>1,'msg'=>'请选择修改日期'));exit;
		}
		if (empty ( $price )) {
			echo json_encode(array('code'=>1,'msg'=>'请输入价格'));exit;
		}
		$this->load->model ( 'hotel/room_status_model' );
		if ($this->room_status_model->save_room_price_new ( $this->inter_id, $hotel_id, $room_id, $price_code, $price, $room_num, $days )) {
			echo json_encode(array('code'=>0,'msg'=>'操作成功'));
		} else {
			echo json_encode(array('code'=>1,'msg'=>'操作失败'));
		}
	}

	//保存房态信息
	public function save_calendar_ftai(){
		$hotel_id = $this->input->get ( 'hotel_id' , true);
		$room_id = $this->input->get ( 'room_id' , true);
		$date = $this->input->get ( 'date' , true);
		$type = $this->input->get ( 'type' , true);
		$room_num = $this->input->get ( 'room_num', true );

		if (empty ( $room_id ) || empty ( $hotel_id ) || empty ( $date ) || empty ( $type )) {
			echo json_encode(array('code'=>1,'msg'=>'缺少参数'));exit;
		}
		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! in_array ( $hotel_id, $hotel_ids )) {
				echo json_encode(array('code'=>1,'msg'=>'没有权限'));exit;
			}
		}
		if ($type == 1) {
			$data = array (
					'inter_id' => $this->inter_id,
					'room_id' => $room_id,
					'hotel_id' => $hotel_id,
					'date' => $date,
					'price_code' => - 2,
					'channel_code' => 'Weixin'
			);
			$this->db->where ( $data );
			$check=$this->db->get ( 'hotel_room_state' )->row_array ();
			if (! $check) {
				$data ['price_code'] = - 1;
				$data ['oprice'] = 0;
				$data ['nums'] = $room_num;
				$data ['channel_code'] = 'Weixin';
				$data ['edittime'] = time ();
				$this->db->replace ( 'hotel_room_state', $data );
				$this->load->model('hotel/Hotel_log_model');
				unset($data ['inter_id']);
				unset($data ['edittime']);
				$this->Hotel_log_model->add_admin_log('room_state'.'#'.$this->inter_id.'_'.$hotel_id.'_'.$room_id.'_'.'-1','add',$data);
				
			} else {
				$this->db->where ( $data );
				$updata=array (
						'price_code' => - 1,
						'nums' => $room_num,
						'edittime' => time ()
				);
				$this->db->update ( 'hotel_room_state', $updata );
				$this->load->model('hotel/Hotel_log_model');
				$update_diff=array();
				foreach ($check as $k=>$c){
					if ((isset($updata[$k])&&$check[$k]!=$updata[$k])||$k=='date'){
						$update_diff[$k]=array('old'=>$c,'new'=>isset($updata[$k])?$updata[$k]:'');
					}
				}
				unset($update_diff ['edittime']);
				$this->Hotel_log_model->add_admin_log('room_state'.'#'.$this->inter_id.'_'.$hotel_id.'_'.$room_id.'_'.'-2','save',$update_diff);
			}

		} elseif ($type == 2) {

			$data = array (
					'inter_id' => $this->inter_id,
					'room_id' => $room_id,
					'hotel_id' => $hotel_id,
					'date' => $date,
					'price_code' => - 1,
					'channel_code' => 'Weixin'
			);
			$this->db->where ( $data );
			$check=$this->db->get ( 'hotel_room_state' )->row_array();
			if ($check) {
				$this->db->where ( $data );
				$updata=array (
						'price_code' => - 2,
						'nums' => $room_num,
						'edittime' => time ()
				);
				$this->db->update ( 'hotel_room_state', $updata );
				$this->load->model('hotel/Hotel_log_model');
				$update_diff=array();
				foreach ($check as $k=>$c){
					if ((isset($updata[$k])&&$check[$k]!=$updata[$k])||$k=='date'){
						$update_diff[$k]=array('old'=>$c,'new'=>isset($updata[$k])?$updata[$k]:'');
					}
				}
				unset($update_diff ['edittime']);
				$this->Hotel_log_model->add_admin_log('room_state'.'#'.$this->inter_id.'_'.$hotel_id.'_'.$room_id.'_'.'-2','save',$update_diff);
					
			}

		} else {
			echo json_encode(array('code'=>1,'msg'=>'参数有误'));exit;
		}
		echo json_encode(array('code'=>0,'msg'=>'操作成功'));

	}
}
