<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Distri_report extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '分销统计';
	protected $label_controller= '分销统计';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
		if($this->input->get('debug')){
			$this->output->enable_profiler(true);
		}
	}

	protected function main_model_name()
	{
		return 'distribute/distribute_model';
	}
	public function index(){
		// 		$this->grid();
		echo 'index';
	}
	public function orders(){
		$keys = $this->uri->segment(4);
		$begin_time = $this->input->post('begin_time');
		$end_time   = $this->input->post('end_time');
		$keys = explode('_', $keys);
		if(!isset($keys[0]) || empty($keys[0])){
			$type = 'orders';
		}else{
			$type = $keys[0];
		}
		if(!empty($keys[1])){
			$begin_time = $keys[1];
		}
		if(!empty($keys[2])){
			$end_time = $keys[2];
		}
		if(empty($begin_time) && empty($end_time)){
			$begin_time = date('Y-m-01',strtotime('-1 month'));
			$end_time   = date('Y-m-t',strtotime('-1 month'));
		}
		$this->load->model('distribute/report_model');
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_orders_v1($config['per_page'],$config['cur_page'],$type,$begin_time,$end_time);
		$config['uri_segment']       = 5;
// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/orders/".$type.'_'.$begin_time.'_'.$end_time);
		$config['total_rows']        = $res['count'];
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res['orders'],
				'hotels'     => $res['hotels'],
				'type'       => $type,
				'btime'      => $begin_time,
				'etime'      => $end_time,
				'total'      => $config['total_rows'],
				'source'     => array(0=>'场景',1=>'员工'),
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		if($this->input->get('debug')){
			var_dump($res);
		}
		if($type == 'orders')
			$html= $this->_render_content($this->_load_view_file('orders'), $view_params, TRUE);
		else if($type == 'hotels')
			$html= $this->_render_content($this->_load_view_file('hotels'), $view_params, TRUE);
		else
			$html= $this->_render_content($this->_load_view_file('staffs'), $view_params, TRUE);
		echo $html;
	}
	public function room_orders(){
		$keys = $this->uri->segment(4);
		$order_id   = $this->input->post('order_id');
		$hotel_id   = $this->input->post('hotel_id');
		$check_out  = $this->input->post('check_out');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$order_id = $keys[0];
		}
		if(!empty($keys[1])){
			$hotel_id = $keys[1];
		}
		if(!empty($keys[2])){
			$check_out = $keys[2];
		}
		if(!empty($keys[3])){
			$saler_name = $keys[3];
		}
		if(!empty($keys[4])){
			$saler_no = $keys[4];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_ROOMS_ORDER',$admin_profile['admin_id']);
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_orders($config['per_page'],$config['cur_page'],$hotel_id,$order_id,$check_out,$saler_name,$saler_no);
		$config['uri_segment']       = 5;
// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/orders/".$order_id.'_'.$hotel_id.'_'.$check_out.'_'.$saler_name.'_'.$saler_no);
		$config['total_rows']        = $res['count'];
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model('hotel/hotel_model');
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res['orders'],
				'exts'       => $res['exts'],
				'confs'      => $confs,
				'hotels'     => $res['hotels'],
				'order_id'   => $order_id,
				'hotel_id'   => $hotel_id,
				'check_out'  => $check_out,
				'saler_name' => $saler_name,
				'saler_no'   => $saler_no,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		$html= $this->_render_content($this->_load_view_file('rooms_order'), $view_params, TRUE);
		echo $html;
	}
	
	public function exp_orders(){
		ini_set('memory_limit','512M');
		$keys = $this->uri->segment(4);
		$begin_time = $this->input->post('begin_time');
		$end_time   = $this->input->post('end_time');
		$keys = explode('_', $keys);
		if(!isset($keys[0]) || empty($keys[0])){
			$type = 'orders';
		}else{
			$type = $keys[0];
		}
		if(!empty($keys[1])){
			$begin_time = $keys[1];
		}
		if(!empty($keys[2])){
			$end_time = $keys[2];
		}
		
		if(empty($begin_time) && empty($end_time)){
			$begin_time = date("Y-m-01");
			$end_time   = date("Y-m-d");
		}
		$this->load->model('distribute/report_model');
		
// 		$res = $this->report_model->get_orders(null,null,$type,$begin_time,$end_time);
		$res = $this->report_model->get_orders_v1(null,null,$type,$begin_time,$end_time);
		$exts   = isset($res['exts']) ? $res['exts'] : array();
		$hotels = isset($res['hotels']) ? $res['hotels'] : array();
		$res    = $res['orders'];
		$paid    = array(0=>'未支付',1=>'微信已支付',2=>'门店付款');
		$status  = array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败');
		$paytype = array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分');
		$source  = array(0=>'场景',1=>'员工');
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		if($type == 'orders'){
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '系统订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, 'PMS订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, 'PMS子订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '订单状态' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '姓名' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '预订酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '预订时间' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '房型' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '实际金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '入住日期' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '离店日期' );
// 			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '间夜' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '下单金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '支付方式' );
// 			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '支付状态' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, 1, '粉丝所属酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, 1, '粉丝所属员工' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, 1, '订单佣金' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 16, 1, '渠道' );
			
			
			// Fetching the table data
			$row = 2;
			foreach ( $res as $item ) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['web_orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['webs_orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, isset($status[$item['istatus']])?$status[$item['istatus']]:'--' );
	// 			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['istatus'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['name'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $hotels[$item['ohotel_id']] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['order_time']  );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['roomname'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['iprice'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['startdate'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['enddate'] );
// 				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $this->report_model->date_difference($item['enddate'],$item['startdate']) );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $item['price'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, isset($paytype[$item['paytype']]) ? $paytype[$item['paytype']] : '到付' );
// 				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, $paid[$item['paid']] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, $row, isset($hotels[$item['fans_hotel']]) ? $hotels[$item['fans_hotel']] : isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, $row, isset($item['saler_name']) ? $item['saler_name'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, $row, isset($item['grade_total']) ? $item['grade_total'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 16, $row, isset($source[$item['grade_total']]) ? $source[$item['grade_total']] : '公共' );
				$row ++;
			}
		}
		if($type == 'staffs'){
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分销员姓名' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '分销号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '发放时间' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '发放金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '系统订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, 'PMS订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '预订酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '预订时间' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '房型' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '房价' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '入住日期' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '离店日期' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, 1, '间夜' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, 1, '订单金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, 1, '员工返佣' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 16, 1, '所属酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 17, 1, '子单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 19, 1, '渠道' );
			
			// Fetching the table data
			$row = 2;
			foreach ( $res as $item ) {
				//分销员姓名	分销号	发放时间	发放金额(单位：分)	酒店	订单号	预订酒店	预订时间	房型	房价	入住日期	离店日期	间夜	订单金额	员工返佣	所属酒店
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, isset($item['saler_name']) ? $item['saler_name'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, isset($item['saler']) ? $item['saler'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, isset($item['send_time']) ? $item['send_time'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, isset($item['grade_total']) ? $item['grade_total'] : '-' );
	// 			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['istatus'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, isset($hotels[$item['saler_hotel_id']]) ? $hotels[$item['saler_hotel_id']] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['web_orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, isset($hotels[$item['ohotel_id']]) ? $hotels[$item['ohotel_id']] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['order_time'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['roomname'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['iprice'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $item['startdate'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, $item['enddate'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, $row, $this->report_model->date_difference($item['enddate'],$item['startdate']) );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, $row, $item['price'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, $row, isset($item['grade_total']) ? $item['grade_total'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 16, $row, isset($hotels[$item['saler_hotel_id']]) ? $hotels[$item['saler_hotel_id']] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 17, $row, $item['webs_orderid']);
				$apri = explode(',',$item['allprice']);
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 18, $row, $apri[0]);
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 19, $row, isset($source[$item['grade_total']]) ? $source[$item['grade_total']] : '公共' );
				$row ++;
			}
		}
		if($type == 'hotels'){
			//返佣酒店	发放时间	发放金额(单位：分)	订单号	预订酒店	预订时间	房型	房价	入住日期	离店日期	间夜	订单金额	粉丝所属酒店	分销员姓名	酒店返佣
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '返佣酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '发放时间' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '发放金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '系统订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, 'PMS订单号' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '预订酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '预订时间' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '房型' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '房价' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '入住日期' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '离店日期' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '间夜' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '订单金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, 1, '粉丝所属酒店' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, 1, '分销员' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, 1, '酒店返佣' );
			
			
			// Fetching the table data
			$row = 2;
			foreach ( $res as $item ) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, isset($item['send_time']) ? $item['send_time'] : '-'  );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, isset($item['grade_total']) ? $item['grade_total'] : '-' );
	// 			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['istatus'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['web_orderid'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $hotels[$item['ohotel_id']] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['order_time'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['roomname'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['iprice']);
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['startdate'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['enddate'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $this->report_model->date_difference($item['enddate'],$item['startdate']) );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, $item['price'] );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, $row, isset($hotels[$item['fans_hotel']]) ? $hotels[$item['fans_hotel']] : isset($hotels[$item['hotel_id']]) ? $hotels[$item['hotel_id']] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, $row, isset($item['saler_name']) ? $item['saler_name'] : '-' );
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, $row, isset($item['grade_total']) ? $item['grade_total'] : '-' );
				$row ++;
			}
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	public function ext_all(){
		ini_set('memory_limit','512M');
		set_time_limit(120);
		$keys = $this->uri->segment(4);
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['cout_date_begin']  = $this->input->post('cout_date_begin');
		$avgs['cout_date_end']    = $this->input->post('cout_date_end');
		$avgs['order_id']         = $this->input->post('order_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
		$avgs['grade_date_end']   = $this->input->post('grade_date_end');
		$avgs['send_date_begin']  = $this->input->post('send_date_begin');
		$avgs['send_date_end']    = $this->input->post('send_date_end');
		$avgs['department']    = $this->input->post('department');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['cout_date_begin'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['cout_date_end'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['order_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = $keys[4];
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		if(!empty($keys[6])){
			$avgs['grade_date_begin'] = $keys[6];
		}
		if(!empty($keys[7])){
			$avgs['grade_date_end'] = $keys[7];
		}
		if(!empty($keys[8])){
			$avgs['send_date_begin'] = $keys[8];
		}
		if(!empty($keys[9])){
			$avgs['send_date_end'] = $keys[9];
		}
        if(!empty($keys[10])){
            $avgs['department'] = urldecode($keys[10]);
        }
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_ROOMS_ORDER',$admin_profile['admin_id']);
		$config['per_page'] = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$avgs['inter_id'] = $admin_profile['inter_id'];
		 if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20161104
            $avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
		$res  = $this->report_model->get_send_logs($avgs)->result_array();
		$paid    = array(0=>'未支付',1=>'微信已支付',2=>'门店付款');
		$status  = array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败');
		$paytype = array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分');
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		//主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店	
		//酒店分组	房型	价格代码	入住日期	离店日期	间夜	
		//下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店	
		//酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
		$index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
				$index ++;
				$fields[] = $key;
			}
		}
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$index = 0;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $index, $row, $item ['orderid'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1 + $index, $row, $item ['oiid'] );
			$index = 2;
			if (in_array ( 'webs_orderid', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['webs_orderid'] );
				$index ++;
			}
			if (in_array ( 'web_orderid', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['web_orderid'] );
				$index ++;
			}
			if (in_array ( 'mem_card_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['mem_card_no'] ) ? $item ['mem_card_no'] : '--' );
				$index ++;
			}
			if (in_array ( 'membership_number', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['membership_number'] ) ? $item ['membership_number'] : '--' );
				$index ++;
			}
			if (in_array ( 'name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['name'] );
				$index ++;
			}
			if (in_array ( 'in_hotel_id', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['order_hotel']]) ? $hotels [$item ['order_hotel']] : '--' );
				$index ++;
			}
			if (in_array ( 'roomname', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['roomname'] );
				$index ++;
			}
			if (in_array ( 'startdate', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['startdate'] );
				$index ++;
			}
			if (in_array ( 'enddate', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['enddate'] );
				$index ++;
			}
			if (in_array ( 'grade_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['grade_time'] );
				$index ++;
			}
			if (in_array ( 'price', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['price'] );
				$index ++;
			}
			if (in_array ( 'coupon_favour', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['coupon_favour'] ) ? $item ['coupon_favour'] : '--' );
				$index ++;
			}
			if (in_array ( 'point_used', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['point_used'] ) ? $item ['point_used'] : '--' );
				$index ++;
			}
			if (in_array ( 'paytype', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $paytype [$item ['paytype']] ) ? $paytype [$item ['paytype']] : '到付' );
				$index ++;
			}
			if (in_array ( 'iprice', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['iprice'] );
				$index ++;
			}
			if (in_array ( 'staff_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['staff_name'] ) ? $item ['staff_name'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['saler'] ) ? $item ['saler'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler_hotel_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['saler_hotel']]) ? $hotels [$item ['saler_hotel']] : '--' );
				$index ++;
			}
			if (in_array ( 'master_dept', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['master_dept'] ) ? $item ['master_dept'] : '-' );
                $index ++;
            }
			if (in_array ( 'grade_total', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['grade_total'] ) ? $item ['grade_total'] : '-' );
				$index ++;
			}
			if (in_array ( 'send_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['send_time'] ) ?$item ['send_time'] : '-' );
				$index ++;
			}
			if (in_array ( 'fans_hotel_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['fans_hotel']]) ? $hotels [$item ['fans_hotel']] : '--' );
				$index ++;
			}
			if (in_array ( 'partner_trade_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['partner_trade_no'] ) ?$item ['partner_trade_no'] : '-' );
				$index ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	public function get_cofigs(){
		$this->load->model('distribute/report_model');
		echo json_encode($this->report_model->get_dist_field_conf());
	}
	public function save_cofigs(){
		$this->load->model('distribute/report_model');
		if($this->report_model->save_dist_field_conf()){
			echo 'success';
		}else{
			echo '保存失败';
		}
	}
	
	/**
	 * 订房订单员工返佣明细
	 */
	public function exnew(){
		set_time_limit ( 0 );
        @ini_set('memory_limit','1024M');
		$keys = $this->uri->segment(4);
// 		var_dump($this->input->post());exit;
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['cout_date_begin']  = $this->input->post('cout_date_begin');
		$avgs['cout_date_end']    = $this->input->post('cout_date_end');
		$avgs['order_id']         = $this->input->post('order_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
		$avgs['grade_date_end']   = $this->input->post('grade_date_end');
		$avgs['send_date_begin']  = $this->input->post('send_date_begin');
		$avgs['send_date_end']    = $this->input->post('send_date_end');
		$avgs['department']    = $this->input->post('department');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['cout_date_begin'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['cout_date_end'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['order_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		if(!empty($keys[6])){
			$avgs['grade_date_begin'] = $keys[6];
		}
		if(!empty($keys[7])){
			$avgs['grade_date_end'] = $keys[7];
		}
		if(!empty($keys[8])){
			$avgs['send_date_begin'] = $keys[8];
		}
		if(!empty($keys[9])){
			$avgs['send_date_end'] = $keys[9];
		}
        if(!empty($keys[10])){
            $avgs['department'] = urldecode($keys[10]);
        }
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_ROOMS_ORDER',$admin_profile['admin_id']);
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$filterH ['hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

        //部门
        $this->load->model('distribute/qrcodes_model');
        $depts = $this->qrcodes_model->get_staff_depts($admin_profile['inter_id']);

		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$res = $this->report_model->get_send_logs($avgs,$config['per_page'],$config['cur_page']);
// 		$res = $this->report_model->get_orders($config['per_page'],$config['cur_page'],$hotel_id,$order_id,$check_out,$saler_name,$saler_no);
		$config['uri_segment']       = 5;
// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/exnew/".$avgs['hotel_id'].'_'.$avgs['cout_date_begin'].'_'.$avgs['cout_date_end'].'_'.$avgs['order_id'].'_'.$avgs['saler_name'].'_'.$avgs['saler_no'].'_'.$avgs['grade_date_begin'].'_'.$avgs['grade_date_end'].'_'.$avgs['send_date_begin'].'_'.$avgs['send_date_end'].'_'.$avgs['department']);
		$config['total_rows']        = $this->report_model->get_send_logs_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model('hotel/hotel_model');
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				'hotels'     => $hotels,
				'depts'      => $depts,
				'deptment'      => $avgs['department'],
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);

        //print_r($view_params['res']);exit;
		$html= $this->_render_content($this->_load_view_file('rooms_order'), $view_params, TRUE);
		echo $html;
	}
	/**
	 * 订房订单员工返佣明细
	 */
	public function exqrcodes(){
		$keys = $this->uri->segment(4);
// 		var_dump($this->input->post());exit;
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['cout_date_begin']  = $this->input->post('cout_date_begin');
		$avgs['cout_date_end']    = $this->input->post('cout_date_end');
		$avgs['order_id']         = $this->input->post('order_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
		$avgs['grade_date_end']   = $this->input->post('grade_date_end');
		$avgs['send_date_begin']  = $this->input->post('send_date_begin');
		$avgs['send_date_end']    = $this->input->post('send_date_end');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['cout_date_begin'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['cout_date_end'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['order_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		if(!empty($keys[6])){
			$avgs['grade_date_begin'] = $keys[6];
		}
		if(!empty($keys[7])){
			$avgs['grade_date_end'] = $keys[7];
		}
		if(!empty($keys[8])){
			$avgs['send_date_begin'] = $keys[8];
		}
		if(!empty($keys[9])){
			$avgs['send_date_end'] = $keys[9];
		}
		$avgs['distribute'] = 0;
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_ROOMS_ORDER',$admin_profile['admin_id']);
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$filterH ['hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$res = $this->report_model->get_send_logs($avgs,$config['per_page'],$config['cur_page']);
// 		$res = $this->report_model->get_orders($config['per_page'],$config['cur_page'],$hotel_id,$order_id,$check_out,$saler_name,$saler_no);
		$config['uri_segment']       = 5;
// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/exqrcodes/".$avgs['hotel_id'].'_'.$avgs['cout_date_begin'].'_'.$avgs['cout_date_end'].'_'.$avgs['order_id'].'_'.$avgs['saler_name'].'_'.$avgs['saler_no'].'_'.$avgs['grade_date_begin'].'_'.$avgs['grade_date_end'].'_'.$avgs['send_date_begin'].'_'.$avgs['send_date_end']);
		$config['total_rows']        = $this->report_model->get_send_logs_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model('hotel/hotel_model');
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		$html= $this->_render_content($this->_load_view_file('rooms_order'), $view_params, TRUE);
		echo $html;
	}
	public function ext_qrs(){
		ini_set('memory_limit','265M');
		set_time_limit(120);
		$keys = $this->uri->segment(4);
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['cout_date_begin']  = $this->input->post('cout_date_begin');
		$avgs['cout_date_end']    = $this->input->post('cout_date_end');
		$avgs['order_id']         = $this->input->post('order_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
		$avgs['grade_date_end']   = $this->input->post('grade_date_end');
		$avgs['send_date_begin']  = $this->input->post('send_date_begin');
		$avgs['send_date_end']    = $this->input->post('send_date_end');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['cout_date_begin'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['cout_date_end'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['order_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = $keys[4];
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		if(!empty($keys[6])){
			$avgs['grade_date_begin'] = $keys[6];
		}
		if(!empty($keys[7])){
			$avgs['grade_date_end'] = $keys[7];
		}
		if(!empty($keys[8])){
			$avgs['send_date_begin'] = $keys[8];
		}
		if(!empty($keys[9])){
			$avgs['send_date_end'] = $keys[9];
		}
		$avgs['distribute'] = 0;
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_ROOMS_ORDER',$admin_profile['admin_id']);
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$avgs['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20161104
            $avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
		$res  = $this->report_model->get_send_logs($avgs)->result_array();
		$paid    = array(0=>'未支付',1=>'微信已支付',2=>'门店付款');
		$status  = array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败');
		$paytype = array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分');
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		//主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店	
		//酒店分组	房型	价格代码	入住日期	离店日期	间夜	
		//下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店	
		//酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
		$index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
				$index ++;
				$fields[] = $key;
			}
		}
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$index = 0;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $index, $row, $item ['orderid'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1 + $index, $row, $item ['oiid'] );
			$index = 2;
			if (in_array ( 'webs_orderid', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['webs_orderid'] );
				$index ++;
			}
			if (in_array ( 'web_orderid', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['web_orderid'] );
				$index ++;
			}
			if (in_array ( 'mem_card_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['mem_card_no'] ) ? $item ['mem_card_no'] : '--' );
				$index ++;
			}
			if (in_array ( 'membership_number', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['membership_number'] ) ? $item ['membership_number'] : '--' );
				$index ++;
			}
			if (in_array ( 'name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['name'] );
				$index ++;
			}
			if (in_array ( 'in_hotel_id', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['order_hotel']]) ? $hotels [$item ['order_hotel']] : '--' );
				$index ++;
			}
			if (in_array ( 'roomname', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['roomname'] );
				$index ++;
			}
			if (in_array ( 'startdate', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['startdate'] );
				$index ++;
			}
			if (in_array ( 'enddate', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['enddate'] );
				$index ++;
			}
			if (in_array ( 'grade_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['grade_time'] );
				$index ++;
			}
			if (in_array ( 'price', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['price'] );
				$index ++;
			}
			if (in_array ( 'coupon_favour', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['coupon_favour'] ) ? $item ['coupon_favour'] : '--' );
				$index ++;
			}
			if (in_array ( 'point_used', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['point_used'] ) ? $item ['point_used'] : '--' );
				$index ++;
			}
			if (in_array ( 'paytype', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $paytype [$item ['paytype']] ) ? $paytype [$item ['paytype']] : '到付' );
				$index ++;
			}
			if (in_array ( 'iprice', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['iprice'] );
				$index ++;
			}
			if (in_array ( 'staff_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['staff_name'] ) ? $item ['staff_name'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['saler'] ) ? $item ['saler'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler_hotel_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['saler_hotel']]) ? $hotels [$item ['saler_hotel']] : '--' );
				$index ++;
			}
			if (in_array ( 'grade_total', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['grade_total'] ) ? $item ['grade_total'] : '-' );
				$index ++;
			}
			if (in_array ( 'send_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['send_time'] ) ?$item ['send_time'] : '-' );
				$index ++;
			}
			if (in_array ( 'fans_hotel_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['fans_hotel']]) ? $hotels [$item ['fans_hotel']] : '--' );
				$index ++;
			}
			if (in_array ( 'partner_trade_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['partner_trade_no'] ) ?$item ['partner_trade_no'] : '-' );
				$index ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	/**
	 * 粉丝分销记录 
	 */
	public function exnew_fas(){
		$keys = $this->uri->segment(4);
		
		$avgs = array();
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['begin_time']  	  = $this->input->post('begin_time');
		$avgs['end_time']		  = $this->input->post('end_time');
		$avgs['fans_id']          = $this->input->post('fans_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$avgs['dept']         = $this->input->post('dept');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['fans_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		if(!empty($keys[6])){
			$avgs['dept'] = $keys[6];
		}
		//默认加载近7天数据
		if(empty($avgs['begin_time'])){
			$avgs['begin_time'] = date('Y-m-d',strtotime('-7 days'));
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date('Y-m-d');
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_FANS_SALE',$admin_profile['admin_id']);
		
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$filterH ['hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$res = $this->report_model->get_hotel_fas_report_by_order($avgs,$config['per_page'],$config['cur_page']);

		$config['uri_segment']       = 5;

		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/exnew_fas/".$avgs['hotel_id'].'_'.$avgs['begin_time'].'_'.$avgs['end_time'].'_'.$avgs['fans_id'].'_'.$avgs['saler_name'].'_'.$avgs['saler_no'].'_'.$avgs['dept']);
		$config['total_rows']        = $this->report_model->get_hotel_fas_report_by_order_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model('hotel/hotel_model');
		$this->load->model('distribute/qrcodes_model');
		$depts = $this->qrcodes_model->get_staff_depts($admin_profile['inter_id']);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'depts'		 => $depts,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		
		$html= $this->_render_content($this->_load_view_file('fans_room_order'), $view_params, TRUE);
		echo $html;
	}
	
	public function ext_fans_room_order(){
		ini_set('memory_limit','256M');
		$keys = $this->uri->segment(4);
		$avgs = array();
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['begin_time']  	  = $this->input->post('begin_time');
		$avgs['end_time']		  = $this->input->post('end_time');
		$avgs['fans_id']          = $this->input->post('fans_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['fans_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_FANS_SALE',$admin_profile['admin_id']);
	
		
		/*
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$avgs['inter_id'] = $admin_profile['inter_id'];
		*/
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res  = $this->report_model->get_hotel_fas_report_by_order($avgs)->result_array();
		$paid    = array(0=>'未支付',1=>'微信已支付',2=>'门店付款');
		$status  = array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败');
		$paytype = array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分');
		
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		//主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店
		//酒店分组	房型	价格代码	入住日期	离店日期	间夜
		//下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店
		//酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
		$index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
				$index ++;
				$fields[] = $key;
			}
		}
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$index = 0;
			if (in_array ('orderid', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['fas_id'] );
				$index ++;
			}
			if (in_array ( 'nickname', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($item ['nickname']) ? str_replace(array("\r\n", "\n", "\r",","),"",str_replace("\"","\"\"",$item['nickname'])) :'--' );
				$index ++;
			}
			if (in_array ( 'mem_card_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['mem_card_no'] ) ? $item ['mem_card_no'] : '--' );
				$index ++;
			}
			if (in_array ( 'subscribe_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['event_time'] ) ? $item ['event_time'] : '--' );
				$index ++;
			}
			if (in_array ( 'staff_name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['name'] );
				$index ++;
			}
			if (in_array ( 'saler', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($item ['saler']) ? $item ['saler'] : '--' );
				$index ++;
			}
			if (in_array ( 'hotel_id', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['hotel_name'] );
				$index ++;
			}
			if (in_array ( 'grade_rate_type', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['grade_rate_type'] );
				$index ++;
			}
			if (in_array ( 'grade_total', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['grade_amount_rate'] );
				$index ++;
			}
			if (in_array ( 'send_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($item ['send_time']) ? $item ['send_time'] : '--' );
				$index ++;
			}
			if (in_array ( 'status', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['status'] );
				$index ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	public function ext_fans_room_order2(){
		ini_set('memory_limit','256M');
		$keys = $this->uri->segment(4);
		$avgs = array();
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['begin_time']  	  = $this->input->post('begin_time');
		$avgs['end_time']		  = $this->input->post('end_time');
		$avgs['fans_id']          = $this->input->post('fans_id');
		$avgs['saler_name']       = $this->input->post('saler_name');
		$avgs['saler_no']         = $this->input->post('saler_no');
		$avgs['dept']         = $this->input->post('dept');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['fans_id'] = $keys[3];
		}
		if(!empty($keys[4])){
			$avgs['saler_name'] = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$avgs['saler_no'] = $keys[5];
		}
		if(!empty($keys[6])){
			$avgs['dept'] = urldecode($keys[6]);
		}
		//默认加载近7天数据
		if(empty($avgs['begin_time'])){
			$avgs['begin_time'] = date('Y-m-d',strtotime('-7 days'));
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date('Y-m-d');
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_FANS_SALE',$admin_profile['admin_id']);
	
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20161104
            $avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
            $filterH ['hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$res  = $this->report_model->get_hotel_fas_report_by_order($avgs)->result_array();
		//主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店
		//酒店分组	房型	价格代码	入住日期	离店日期	间夜
		//下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店
		//酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
		$data = "";
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			}
		}
		$data = $data."\n";
		foreach ($res as $item ) {
				$data = $data.iconv('utf-8','gb2312',$item['fas_id']).",";
				$data = $data.iconv('utf-8','gb2312',str_replace(array("\r\n", "\n", "\r",","),"",str_replace("\"","\"\"",$item['nickname']))).",";
				$data = $data.iconv('utf-8','gb2312',$item['mem_card_no']).",";
				$data = $data.$item['event_time']." ,";
				$data = $data.(isset($hotels[$item['hotel_id']])?mb_convert_encoding($hotels[$item['hotel_id']],'GB18030','utf-8'):'--').",";
				$data = $data.mb_convert_encoding($item['name'],'GB18030','utf-8').",";
				$data = $data.$item['saler'].",";
 				$data = $data.mb_convert_encoding($item['master_dept'],'GB18030','utf-8').",";
				$data = $data.mb_convert_encoding($item['hotel_name'],'GB18030','utf-8').",";
				$data = $data.$item['grade_rate_type'].",";
				$data = $data.$item['grade_amount_rate'].",";
				$data = $data.$item['send_time']." ,";
				$data = $data.$item['status'].",";
				$data = $data."\n";
		}
		
		// 发送标题强制用户下载文件
		header ('Content-Type: text/csv' );
		header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ('Cache-Control:must-revalidate,post-check=0,pre-check=0'); 
		header('Expires:0');
		header('Pragma:public');
		echo $data;
	}
	
	/**
	 * 粉丝分销排行 按酒店
	 */
	public function exnew_fas_hotel(){
		$keys = $this->uri->segment(4);
		$avgs['begin_time'] = $this->input->post('date_begin');
		$avgs['end_time']   = $this->input->post('date_end');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		if(empty($avgs['begin_time'])){
			$avgs['begin_time'] = date("Y-m-d",(time()-2592000));
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date("Y-m-d",time());
		}
		
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_FANS_HOTEL',$admin_profile['admin_id']);
	
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
	
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		
		$res = $this->report_model->get_hotel_fas_report($avgs,$config['per_page'],$config['cur_page']);

		$config['uri_segment']       = 5;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/exnew_fas_hotel/".$avgs['begin_time'].'_'.$avgs['end_time']);
		$config['total_rows']        = $this->report_model->get_hotel_fas_report_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		$html= $this->_render_content($this->_load_view_file('fans_hotel'), $view_params, TRUE);
		echo $html;
	}
	
	public function ext_fans_hotel(){
		$keys = $this->uri->segment(4);
		$avgs['begin_time'] = $this->input->post('date_begin');
		$avgs['end_time']   = $this->input->post('date_end');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		if(empty($avgs['begin_time'])){
			$avgs['begin_time'] = date("Y-m-d",(time()-2592000));
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date("Y-m-d",time());
		}
		
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_FANS_HOTEL',$admin_profile['admin_id']);
		
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_fas_report($avgs);
		$res = $res->result_array();
		if(empty($res)){
			echo '无数据可以导出！';
			die;
		}
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		//主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店
		//酒店分组	房型	价格代码	入住日期	离店日期	间夜
		//下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店
		//酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
		$index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
				$index ++;
				$fields[] = $key;
			}
		}
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$index = 0;
			if (in_array ('name', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['name'] );
				$index ++;
			}
			if (in_array ( 'cnt', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($item ['cnt']) ? $item ['cnt'] :'0' );
				$index ++;
			}
			if (in_array ( 'gz', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['gz'] ) ? $item ['gz'] : '0' );
				$index ++;
			}
			if (in_array ( 'pm', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['pm'] ) ? $item ['pm'] : '--' );
				$index ++;
			}
			if (in_array ( 'zjx', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($item ['zjx']) ? $item ['zjx'] : '--' );
				$index ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
		
	}
	
	public function hotel_order_summation(){
		$keys = $this->uri->segment(4);
// 		var_dump($this->input->post());exit;
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
		$avgs['grade_date_end']   = $this->input->post('grade_date_end');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['grade_date_begin'] = $keys[1];
		}else if(empty($avgs['grade_date_begin'])){
			$avgs['grade_date_begin'] = date('Ymd',strtotime('- 7 days',time()));
		}
		if(!empty($keys[2])){
			$avgs['grade_date_end'] = $keys[2];
		}else if(empty($avgs['grade_date_end'])) {
			$avgs['grade_date_end'] = date('Ymd',time());
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
// 		var_dump($keys);
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'HOTEL_ORDER_SUMMATION',$admin_profile['admin_id']);
		$this->load->library('pagination');
		$config['per_page']          = 0;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
	
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
// 		$filterH ['hotel_id'] = $avgs['hotel_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
	
	
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_order_summation( $avgs,$config['per_page'],$config['cur_page']);
		$config['uri_segment']       = 5;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/hotel_order_summation/".$avgs['hotel_id'].'_'.$avgs['grade_date_begin'].'_'.$avgs['grade_date_end']);
		$config['total_rows']        = count($res);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
		);
		$html= $this->_render_content($this->_load_view_file('hotel_order_summation'), $view_params, TRUE);
		echo $html;
	}
	public function ext_hotel_order_sum(){
		$keys = $this->uri->segment(4);
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
		$avgs['grade_date_end']   = $this->input->post('grade_date_end');
		$keys = explode('_', $keys);
		$admin_profile = $this->session->userdata('admin_profile');
		$public = $this->db->get_where ( 'publics', array (
				'inter_id' => $admin_profile['inter_id']
		) )->row_array ();
		$filename = $public ['name'] ;
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['grade_date_begin'] = $keys[1];
			$filename.='-'.$avgs['grade_date_begin'];
		}
		if(!empty($keys[2])){
			$avgs['grade_date_end'] = $keys[2];
			$filename.='-'.$avgs['grade_date_end'];
		}
		$this->load->model('distribute/report_model');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'HOTEL_ORDER_SUMMATION',$admin_profile['admin_id']);
	
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_order_summation( $avgs);
		$this->load->model ( 'plugins/Excel_model' );
		
		$head=array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$head[]=$item['name'];
			}
		}
		$data=array();
		foreach ($res as $k=>$item){
			$tmp=array();
			foreach ($item as $data_type=>$i){
				if (isset($confs[$data_type])&&($confs[$data_type]['must'] == 1||$confs[$data_type]['choose'] == 1)){
					$tmp[$data_type]=$i;
				}
			}
			$data[]=$tmp;
		}
		$this->Excel_model->exp_exl ( $head, $data, $filename );
	}
	
	/**
	 * 酒店分销子订单数据 
	 */
	public function hotel_distri_order(){
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['order_time_start'] = $this->input->post('order_time_start');
		$avgs['order_time_end']   = $this->input->post('order_time_end');
		$avgs['start_date_start']   = $this->input->post('start_date_start');
		$avgs['start_date_end']   = $this->input->post('start_date_end');
		$avgs['end_date_start']   = $this->input->post('end_date_start');
		$avgs['end_date_end']   = $this->input->post('end_date_end');
		$avgs['order_status']   = $this->input->post('order_status');
		$avgs['orderid']   = $this->input->post('orderid');
		$avgs['web_orderid']   = $this->input->post('web_orderid');
	
		$keys = $this->input->get();
		if(!empty($keys['hotel_id'])){
			$avgs['hotel_id'] = $keys['hotel_id'];
		}
		if(!empty($keys['order_time_start'])){
			$avgs['order_time_start'] = $keys['order_time_start'];
		}
		if(!empty($keys['order_time_end'])){
			$avgs['order_time_end'] = $keys['order_time_end'];
		}
		if(!empty($keys['start_date_start'])){
			$avgs['start_date_start'] = $keys['start_date_start'];
		}
		if(!empty($keys['start_date_end'])){
			$avgs['start_date_end'] = $keys['start_date_end'];
		}
		if(!empty($keys['end_date_start'])){
			$avgs['end_date_start'] = $keys['end_date_start'];
		}
		if(!empty($keys['end_date_end'])){
			$avgs['end_date_end'] = $keys['end_date_end'];
		}
		if(!empty($keys['order_status'])){
			$avgs['order_status'] = $keys['order_status'];
		}
		if(!empty($keys['orderid'])){
			$avgs['orderid'] = $keys['orderid'];
		}
		if(!empty($keys['web_orderid'])){
			$avgs['web_orderid'] = $keys['web_orderid'];
		}
	
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'HOTEL_DISTRI_ORDER',$admin_profile['admin_id']);
		$this->load->library('pagination');
		$config['per_page']  = 20;
		$page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4) - 1) * $config['per_page'];
	
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if (!empty($admin_profile['entity_id'])){
			$hotel_ids=explode(',', $admin_profile['entity_id']);
			$filterH ['hotel_id'] = $hotel_ids;
			if (!empty($avgs['hotel_id'])&&!in_array($avgs['hotel_id'], $hotel_ids)){
				unset($avgs['hotel_id']);
			}
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
	
	
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$config ['suffix'] = "?" .http_build_query($avgs);
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_distri_order( $avgs,$config['per_page'],$config['cur_page']);
		$config['uri_segment']       = 4;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/hotel_distri_order");
		$config['total_rows']        = $this->report_model->get_hotel_distri_order($avgs,NULL,0,FALSE,TRUE);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['first_url'] = site_url("distribute/distri_report/hotel_distri_order")."?" .http_build_query($avgs);
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
		);
		$html= $this->_render_content($this->_load_view_file('hotel_distri_order'), $view_params, TRUE);
		echo $html;
	}
	public function ext_hotel_distri_order(){
		$keys = $this->input->get();
		if(!empty($keys['hotel_id'])){
			$avgs['hotel_id'] = $keys['hotel_id'];
		}
		if(!empty($keys['order_time_start'])){
			$avgs['order_time_start'] = $keys['order_time_start'];
		}
		if(!empty($keys['order_time_end'])){
			$avgs['order_time_end'] = $keys['order_time_end'];
		}
		if(!empty($keys['start_date_start'])){
			$avgs['start_date_start'] = $keys['start_date_start'];
		}
		if(!empty($keys['start_date_end'])){
			$avgs['start_date_end'] = $keys['start_date_end'];
		}
		if(!empty($keys['end_date_start'])){
			$avgs['end_date_start'] = $keys['end_date_start'];
		}
		if(!empty($keys['end_date_end'])){
			$avgs['end_date_end'] = $keys['end_date_end'];
		}
		if(!empty($keys['order_status'])){
			$avgs['order_status'] = $keys['order_status'];
		}
		if(!empty($keys['orderid'])){
			$avgs['orderid'] = $keys['orderid'];
		}
		if(!empty($keys['web_orderid'])){
			$avgs['web_orderid'] = $keys['web_orderid'];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'HOTEL_DISTRI_ORDER',$admin_profile['admin_id']);
	
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if (!empty($admin_profile['entity_id'])){
			$hotel_ids=explode(',', $admin_profile['entity_id']);
			$filterH ['hotel_id'] = $hotel_ids;
			if (!empty($avgs['hotel_id'])&&!in_array($avgs['hotel_id'], $hotel_ids)){
				unset($avgs['hotel_id']);
			}
		}
	
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_distri_order( $avgs,NULL,NULL,TRUE);
	
		$public = $this->db->get_where ( 'publics', array (
				'inter_id' => $admin_profile['inter_id']
		) )->row_array ();
		$filename = $public ['name'].'_'.date('YmdHis') ;
		$this->load->model ( 'plugins/Excel_model' );
		$head=array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$head[]=$item['name'];
			}
		}
		$data=array();
		foreach ($res as $k=>$item){
			$tmp=array();
			foreach ($confs as $data_type=>$i){
				if (isset($item[$data_type])&&($i['must'] == 1||$i['choose'] == 1)){
					$tmp[]=$item[$data_type];
				}
			}
			$data[]=$tmp;
		}
		ob_clean();
		$this->Excel_model->exp_exl ( $head, $data, $filename );
	}
	
	/**
	 * 新增粉丝明细
	 */
	public function exnew_add_fans(){
		$keys = $this->uri->segment(4);
	
		$avgs = array();
		$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		$avgs['source']		  	= $this->input->post('source');
		
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['source'] = $keys[3];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_NEW_FANS',$admin_profile['admin_id']);
	
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
	
	
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
	
	
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_new_add_fans($avgs,$config['per_page'],$config['cur_page']);
	
		$config['uri_segment']       = 5;
	
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/exnew_add_fans/".$avgs['hotel_id'].'_'.$avgs['begin_time'].'_'.$avgs['end_time'].'_'.$avgs['source']);
		$config['total_rows']        = $this->report_model->get_hotel_new_add_fans_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model('hotel/hotel_model');
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
	
		$html= $this->_render_content($this->_load_view_file('new_fans'), $view_params, TRUE);
		echo $html;
	}
	
	/**
	 * 导出  新增粉丝明细
	 */
	public function ext_exnew_add_fans(){
		$keys = $this->uri->segment(4);
	
		$avgs = array();
		$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		$avgs['source']		  	= $this->input->post('source');
	
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['source'] = $keys[3];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_NEW_FANS',$admin_profile['admin_id']);
	
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_hotel_new_add_fans($avgs)->result_array();
	
		$data = "";
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			}
		}
		$data = $data."\n";
		foreach ($res as $item ) {
			$data = $data.$item['openid']." ,";
			if(intval($item['saler']) > 2){
				$data = $data.iconv('utf-8','gb2312',"分销员").",";
			}else{
				$data = $data.iconv('utf-8','gb2312',"公众号").",";
			}
			$data = $data.$item['saler']." ,";
			$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			$data = $data.iconv('utf-8','gb2312',$item['hotel_name']).",";
			$data = $data.iconv('utf-8','gb2312',$item['event_time']).",";
			$data = $data.$item['mem_card_no']." ,";
			$data = $data.$item['bind_time']." ,";
			$data = $data."\n";
		}
		// 发送标题强制用户下载文件
		header ('Content-Type: text/csv' );
		header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;
	}
	
	/**
	 * 分销员发展粉丝明细
	 */
	public function exsale_get_fans(){
		$keys = $this->uri->segment(4);
	
		$avgs = array();
		$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		$avgs['source']		  	= $this->input->post('source');
		
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['source'] = $keys[3];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_SALE_GET_FANS',$admin_profile['admin_id']);
	
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
	
		//加载会员等级
		$this->load->model('member/member');
		$levels = $this->member->getAllMemberLevels($admin_profile['inter_id']);
	
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$filterH ['hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
	
	
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
			$avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
		}
		$res = $this->report_model->get_hotel_saler_get_fans($avgs,$config['per_page'],$config['cur_page']);
	
		$config['uri_segment']       = 5;
	
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/exsale_get_fans/".$avgs['hotel_id'].'_'.$avgs['begin_time'].'_'.$avgs['end_time'].'_'.$avgs['source']);
		$config['total_rows']        = $this->report_model->get_hotel_saler_get_fans_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'levels'	 => $levels,
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
	
		$html= $this->_render_content($this->_load_view_file('sale_get_fans'), $view_params, TRUE);
		echo $html;
	}
	
	/**
	 * 导出  分销员发展粉丝明细
	 */
	public function ext_exsale_get_fans(){
		ini_set('memory_limit','256M');
		$keys = $this->uri->segment(4);
	
		$avgs = array();
		$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		$avgs['source']		  	= $this->input->post('source');
		
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(!empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(!empty($keys[3])){
			$avgs['source'] = $keys[3];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_SALE_GET_FANS',$admin_profile['admin_id']);
	
		
		$avgs['inter_id'] = $admin_profile['inter_id'];
		if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20161104
            $avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
		$res  = $this->report_model->get_hotel_saler_get_fans($avgs)->result_array();
		//主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店
		//酒店分组	房型	价格代码	入住日期	离店日期	间夜
		//下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店
		//酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
		$data = "";
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			}
		}
		$data = $data."\n";
		foreach ($res as $item ) {
			$data = $data.iconv('utf-8','gb2312',$item['event_time']).",";
			$data = $data.iconv('utf-8','gb2312',$item['source']).",";
			$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			$data = $data.iconv('utf-8','gb2312',$item['hotel_name']).",";
			$data = $data.$item['openid']." ,";
			$data = $data.iconv('utf-8','gb2312',str_replace(array("\r\n", "\n", "\r",","),"",str_replace("\"","\"\"",$item['nickname']))).",";
			if(empty($item['membership_number'])){
				$data = $data.iconv('utf-8','gb2312',"未绑定").",";
			}else{
				$data = $data.iconv('utf-8','gb2312',"已绑定").",";
			}
			$data = $data.$item['membership_number'].",";
			$data = $data.$item['level'].",";
			
			if(intval($item['event']) == 2){
				$data = $data.iconv('utf-8','gb2312',"关注中").",";
			}else{
				$data = $data.iconv('utf-8','gb2312',"取消关注").",";
			}
			$data = $data."\n";
		}
		// 发送标题强制用户下载文件
		header ('Content-Type: text/csv' );
		header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;
	}
	
	
	/**
	 * 读取消关注的粉丝
	 */
	public function unfollow_fans(){
		$keys = $this->uri->segment(4);
		
		$avgs = array();
		//$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		//$avgs['source']		  	= $this->input->post('source');
		
		$keys = explode('_', $keys);
		/* if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		} */
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		/* if(!empty($keys[3])){
			$avgs['source'] = $keys[3];
		} */
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'FANS_UNFOLLOW_LIST',$admin_profile['admin_id']);
		
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		//$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		//$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_unfollow_fans_list($avgs,$config['per_page'],$config['cur_page']);
		
		$config['uri_segment']       = 5;
		
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/unfollow_fans/".$avgs['begin_time'].'_'.$avgs['end_time']);
		$config['total_rows']        = $this->report_model->get_unfollow_fans_list_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				//'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		
		$html= $this->_render_content($this->_load_view_file('unfollow_fans'), $view_params, TRUE);
		echo $html;
	}
	
	/**
	 * 导出 取消关注的粉丝
	 */
	public function ext_unfollow_fans(){
		$keys = $this->uri->segment(4);
	
		$avgs = array();
		//$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		//$avgs['source']		  	= $this->input->post('source');
	
		$keys = explode('_', $keys);
		/* if(!empty($keys[0])){
		 $avgs['hotel_id'] = $keys[0];
			} */
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		/* if(!empty($keys[3])){
		 $avgs['source'] = $keys[3];
			} */
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'FANS_UNFOLLOW_LIST',$admin_profile['admin_id']);
	
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_unfollow_fans_list($avgs);
	
		$data = "";
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			}
		}
		$data = $data."\n";
		foreach ($res as $item ) {
			$data = $data.$item['openid']." ,";
			$data = $data.$item['event_time']." ,";
			$data = $data."\n";
		}
		// 发送标题强制用户下载文件
		header ('Content-Type: text/csv' );
		header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;
	}
	
	/**
	 * 新增绑定会员明细表
	 */
	public function new_bind_member_fans(){
		$keys = $this->uri->segment(4);
		
		$avgs = array();
		//$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		//$avgs['source']		  	= $this->input->post('source');
		
		$keys = explode('_', $keys);
		/* if(!empty($keys[0])){
			$avgs['hotel_id'] = $keys[0];
		} */
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		/* if(!empty($keys[3])){
		 $avgs['source'] = $keys[3];
		 } */
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'MEM_NEW_BIND',$admin_profile['admin_id']);
		
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		//$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		//$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_bind_member_fans($avgs,$config['per_page'],$config['cur_page']);
		
		$config['uri_segment']       = 5;
		
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/new_bind_member_fans/".$avgs['begin_time'].'_'.$avgs['end_time']);
		$config['total_rows']        = $this->report_model->get_bind_member_fans_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				//'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		
		$html= $this->_render_content($this->_load_view_file('new_bind_member_fans'), $view_params, TRUE);
		echo $html;
	}
	
	/**
	 * 导出  新增绑定会员明细表
	 */
	public function ext_new_bind_member_fans(){
		$keys = $this->uri->segment(4);
	
		$avgs = array();
		//$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		//$avgs['source']		  	= $this->input->post('source');
	
		$keys = explode('_', $keys);
		/* if(!empty($keys[0])){
		 $avgs['hotel_id'] = $keys[0];
			} */
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		/* if(!empty($keys[3])){
		 $avgs['source'] = $keys[3];
		 } */
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'MEM_NEW_BIND',$admin_profile['admin_id']);
	
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_bind_member_fans($avgs)->result_array();
	
		$data = "";
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$data = $data.iconv('utf-8','gb2312',$item['name']).",";
			}
		}
		
		$data = $data."\n";
		foreach ($res as $item ){
			$data = $data.$item['membership_number']." ,";
			$data = $data.$item['openid']." ,";
			$data = $data.$item['create_time']." ,";
			$data = $data."\n";
		}
		// 发送标题强制用户下载文件
		header ('Content-Type: text/csv' );
		header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;
	}
	
	/**
	 * 员工分销按时段绩效报表 
	 */
	public function saler_grades(){
		$keys = $this->uri->segment(4);
		$hotel_name  = $this->input->post('hotel_name');
		$saler_name  = $this->input->post('saler_name');
		$saler_no    = $this->input->post('saler_no');
		$btime       = $this->input->post('btime');
		$etime       = $this->input->post('etime');
		$dept_name       = $this->input->post('dept');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_name = urldecode($keys[0]);
		}
		if(!empty($keys[1])){
			$saler_name = urldecode($keys[1]);
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$btime = $keys[3];
		}
		if(!empty($keys[4])){
			$etime = $keys[4];
		}
        if(!empty($keys[5])){
            $dept_name = urldecode($keys[5]);
        }
		$this->load->model('distribute/report_model');
        $this->load->model('distribute/qrcodes_model');
		$admin_profile = $this->session->userdata('admin_profile');
	
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_staff_grade_rank($admin_profile['inter_id'],$saler_no,$saler_name,$hotel_name,$btime,date('Y-m-d',strtotime("+1 day $etime")),$config['per_page'],$config['cur_page'],$dept_name)->result();
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/saler_grades/".$hotel_name.'_'.$saler_name.'_'.$saler_no.'_'.$btime.'_'.$etime.'_'.$dept_name);
		$config['total_rows']        = $this->report_model->get_staff_grade_rank_count($admin_profile['inter_id'],$saler_no,$saler_name,$hotel_name,$btime,date('Y-m-d',strtotime("+1 day $etime")),$dept_name);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'hotel_name' => $hotel_name,
				'saler_name' => $saler_name,
				'saler_no'   => $saler_no,
				'btime'      => $btime,
				'etime'      => $etime,
				'dept_name'      => $dept_name,
				'total'      => $config['total_rows'],
                'depts'      => $this->qrcodes_model->get_staff_depts($admin_profile['inter_id']),
		);
		$html= $this->_render_content($this->_load_view_file('saler_grades'), $view_params, TRUE);
		echo $html;
	}
	/**
	 * 导出员工分销按时段绩效报表 
	 */
	public function ext_saler_grades(){
		$keys = $this->uri->segment(4);
		$hotel_name  = $this->input->post('hotel_name');
		$saler_name  = $this->input->post('saler_name');
		$saler_no    = $this->input->post('saler_no');
		$btime       = $this->input->post('btime');
		$etime       = $this->input->post('etime');
        $dept_name   = $this->input->post('dept');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_name = urldecode($keys[0]);
		}
		if(!empty($keys[1])){
			$saler_name = urldecode($keys[1]);
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$btime = $keys[3];
		}
		if(!empty($keys[4])){
			$etime = $keys[4];
		}

        if(!empty($keys[5])){
            $dept_name = urldecode($keys[5]);
        }

		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
	
		$res = $this->report_model->get_staff_grade_rank($admin_profile['inter_id'],$saler_no,$saler_name,$hotel_name,$btime,date('Y-m-d',strtotime("+1 day $etime")),'','',$dept_name)->result();
	
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分销员姓名' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '分销员所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '分销员所属部门' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '绩效总金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '粉丝绩效金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '订房绩效金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '商城绩效金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '会员卡绩效金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '额外绩效金额(首单)	' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '未发绩效' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '已发绩效' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '绩效金额排行榜' );
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item->name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item->qrcode_id );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item->hotel_name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item->master_dept );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item->GRADE_TOTAL );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item->GRADE_FANS );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item->GRADE_ROOMS );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item->GRADE_MALL_ALL );
			
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item->GRADE_MEMBER );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item->GRADE_EXTRA );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item->UNDELIVER );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow (11, $row, $item->GRADE_TOTAL-$item->UNDELIVER );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, $item->rank );
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		ob_clean();
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	
	}
	
	public function hotel_fans_unfollow(){
		$keys = $this->uri->segment(4);
		
		$avgs = array();
		//$avgs['hotel_id']		= $this->input->post('hotel_id');
		$avgs['begin_time']		= $this->input->post('begin_time');
		$avgs['end_time']		= $this->input->post('end_time');
		//$avgs['source']		  	= $this->input->post('source');
		
		$keys = explode('_', $keys);
		/* if(!empty($keys[0])){
		 $avgs['hotel_id'] = $keys[0];
		 } */
		if(!empty($keys[0])){
			$avgs['begin_time'] = $keys[0];
		}
		if(!empty($keys[1])){
			$avgs['end_time'] = $keys[1];
		}
		/* if(!empty($keys[3])){
		 $avgs['source'] = $keys[3];
		 } */
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'FANS_UNFOLLOW_LIST',$admin_profile['admin_id']);
		
		$this->load->library('pagination');
		$config['per_page']          = 30;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$avgs['inter_id'] = $admin_profile['inter_id'];
		$res = $this->report_model->get_unfollow_fans_list($avgs,$config['per_page'],$config['cur_page']);
		
		$config['uri_segment']       = 5;
		
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/unfollow_fans/".$avgs['begin_time'].'_'.$avgs['end_time']);
		$config['total_rows']        = $this->report_model->get_unfollow_fans_list_count($avgs);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res->result_array(),
				'confs'      => $confs,
				'hotels'     => $hotels,
				'posts'      => $avgs,
				'total'      => $config['total_rows'],
				'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
				'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
				'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
		);
		
		$html= $this->_render_content($this->_load_view_file('hotel_fans_unfollow'), $view_params, TRUE);
		echo $html;
	}
	
	
	public function ext_hotel_fans_unfollow(){
		
	}
	
	/**
	 * 酒店粉丝取消概况
	 */
	public function hotel_unsubc_fans(){
		$keys = $this->uri->segment(4);
		$hotel_id    = $this->input->post('hotel_id');
		$saler_name  = $this->input->post('saler_name');
		$saler_no    = $this->input->post('saler_no');
		$btime       = $this->input->post('btime');
		$etime       = $this->input->post('etime');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_id = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_name = urldecode($keys[1]);
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$btime = $keys[3];
		}
		if(!empty($keys[4])){
			$etime = $keys[4];
		}
		if($hotel_id == 'ALL') $hotel_id = NULL;
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');

		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_hotel_unsubcribe_fans($admin_profile['inter_id'],$hotel_id,$btime,$etime,$saler_name,$saler_no,$config['per_page'],$config['cur_page'])->result();
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/hotel_unsubc_fans/".$hotel_id.'_'.$saler_no.'_'.$saler_name.'_'.$btime.'_'.$etime);
		$config['total_rows']        = $this->report_model->get_hotel_unsubcribe_fans_count($admin_profile['inter_id'],$hotel_id,$btime,$etime,$saler_name,$saler_no);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'hotels'     => $hotels,
				'hotel_id'   => $hotel_id,
				'saler_name' => $saler_name,
				'saler_no'   => $saler_no,
				'btime'      => $btime,
				'etime'      => $etime,
				'total'      => $config['total_rows']
		);
		$html= $this->_render_content($this->_load_view_file('fans_unsubc'), $view_params, TRUE);
		echo $html;
	}
	/**
	 * 导出酒店粉丝取消概况
	 */
	public function ext_hotel_unsubc_fans(){
		$keys = $this->uri->segment(4);
		$hotel_id    = $this->input->post('hotel_id');
		$saler_name  = $this->input->post('saler_name');
		$saler_no    = $this->input->post('saler_no');
		$btime       = $this->input->post('btime');
		$etime       = $this->input->post('etime');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_id = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_name = urldecode($keys[1]);
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$btime = $keys[3];
		}
		if(!empty($keys[4])){
			$etime = $keys[4];
		}
		if($hotel_id == 'ALL') $hotel_id = NULL;
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');

		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		
		$res = $this->report_model->get_hotel_unsubcribe_fans($admin_profile['inter_id'],$hotel_id,$btime,$etime,$saler_name,$saler_no)->result();
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '微信粉丝名' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '微信会员号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '粉丝取消时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '所属分销员姓名' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '所属分销员分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '粉丝绩效规则' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '分销员绩效' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '绩效发放时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '发放状态' );
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, isset($hotels[$item->hotel_id]) ? $hotels[$item->hotel_id] : '--' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item->nickname );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item->unsubcribe_time );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item->staff_name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item->saler );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, '粉丝关注奖励固定金额' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item->grade_total );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item->send_time );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item->status == 2 ? '已发放' : '未发放' );
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	/**
	 * 员工粉丝绩效概况
	 */
	public function saler_fans_summ(){
		$keys = $this->uri->segment(4);
		$hotel_name    = $this->input->post('hotel_name');
		$saler_name  = $this->input->post('saler_name');
		$saler_no    = $this->input->post('saler_no');
		$btime       = $this->input->post('btime');
		$etime       = $this->input->post('etime');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_name = urldecode(trim($keys[0]));
		}
		if(!empty($keys[1])){
			$saler_name = urldecode(trim($keys[1]));
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$btime = $keys[3];
		}
		if(!empty($keys[4])){
			$etime = $keys[4];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_staff_fans_grades($admin_profile['inter_id'],$hotel_name,$btime,$etime,$saler_name,$saler_no,$config['per_page'],$config['cur_page'])->result();
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/saler_fans_summ/".$hotel_name.'_'.$saler_name.'_'.$saler_no.'_'.$btime.'_'.$etime);
		$config['total_rows']        = $this->report_model->get_staff_fans_grades_count($admin_profile['inter_id'],$hotel_name,$btime,$etime,$saler_name,$saler_no);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'hotel_name' => $hotel_name,
				'saler_name' => $saler_name,
				'saler_no'   => $saler_no,
				'btime'      => $btime,
				'etime'      => $etime,
				'total'      => $config['total_rows']
		);
		$html= $this->_render_content($this->_load_view_file('saler_fans_summary'), $view_params, TRUE);
		echo $html;
	}
	/**
	 * 员工粉丝绩效概况导出
	 */
	public function ext_saler_fans_summ(){
	$keys = $this->uri->segment(4);
		$hotel_name  = $this->input->post('hotel_name');
		$saler_name  = $this->input->post('saler_name');
		$saler_no    = $this->input->post('saler_no');
		$btime       = $this->input->post('btime');
		$etime       = $this->input->post('etime');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_name = urldecode(trim($keys[0]));
		}
		if(!empty($keys[1])){
			$saler_name = urldecode(trim($keys[1]));
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$btime = $keys[3];
		}
		if(!empty($keys[4])){
			$etime = $keys[4];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		
		$res = $this->report_model->get_staff_fans_grades($admin_profile['inter_id'],$hotel_name,$btime,$etime,$saler_name,$saler_no)->result();
		
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分销员' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '分销员所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '新增粉丝数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '粉丝交易量' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '产生交易粉丝数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '粉丝数排行榜' );
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item->name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item->saler );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item->hotel_name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item->fans_counts );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item->trans_counts );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item->act_fans );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item->rank );
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	/**
	 * 商城分销绩效报表（按酒店）
	 */
	public function mall_hotels(){
		$keys = $this->uri->segment(4);
		$hotel_id = $this->input->post('hotel_name');
		$btime    = $this->input->post('btime');
		$etime    = $this->input->post('etime');
		$keys     = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_id = urldecode(trim($keys[0]));
		}
		if(!empty($keys[1])){
			$btime = $keys[1];
		}
		if(!empty($keys[2])){
			$etime = $keys[2];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_MALL_HOTELS',$admin_profile['admin_id']);
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_mall_grades_hotel($admin_profile['inter_id'],$hotel_id,$btime,$etime,$config['per_page'],$config['cur_page'])->result_array();
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/mall_hotels/".$hotel_id.'_'.$btime.'_'.$etime);
		$config['total_rows']        = $this->report_model->get_mall_grades_hotel_count($admin_profile['inter_id'],$hotel_id,$btime,$etime);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$hotel_salers = $this->report_model->get_grades_hotel_saler($admin_profile['inter_id'],$hotel_id,$btime,$etime)->result();
		$hs_array = array();
		foreach ($hotel_salers as $hsaler){
			if(is_null($hsaler->hotel_id)){
				$hs_array['NULL'] = $hsaler->salers;
			}else{
				$hs_array[$hsaler->hotel_id] = $hsaler->salers;
			}
		}
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'hotel_id'   => $hotel_id,
				'btime'      => $btime,
				'etime'      => $etime,
				'confs'      => $confs,
				'hotels'     => $hotels,
				'h_salers'   => $hs_array,
				'total'      => $config['total_rows']
		);
		echo $this->_render_content($this->_load_view_file('mall_grades_hotel'), $view_params, TRUE);
	}
	public function ext_mall_hotels(){
		$keys = $this->uri->segment(4);
		$hotel_id = $this->input->post('hotel_name');
		$btime    = $this->input->post('btime');
		$etime    = $this->input->post('etime');
		$keys     = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_id = urldecode(trim($keys[0]));
		}
		if(!empty($keys[1])){
			$btime = $keys[1];
		}
		if(!empty($keys[2])){
			$etime = $keys[2];
		}
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_MALL_HOTELS',$admin_profile['admin_id']);
		
		$res = $this->report_model->get_mall_grades_hotel($admin_profile['inter_id'],$hotel_id,$btime,$etime)->result_array();

		$hotel_salers = $this->report_model->get_grades_hotel_saler($admin_profile['inter_id'],$hotel_id,$btime,$etime)->result();
		$hs_array = array();
		foreach ($hotel_salers as $hsaler){
			if(is_null($hsaler->hotel_id)){
				$hs_array['NULL'] = $hsaler->salers;
			}else{
				$hs_array[$hsaler->hotel_id] = $hsaler->salers;
			}
		}
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );

		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
				$index ++;
				$fields[] = $key;
			}
		}
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$index = 0;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $index, $row, isset($hotels [$item ['hotel_id']])?$hotels [$item ['hotel_id']]:'--' );
			$index = 1;
			if (in_array ( 'hotel_group', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['hotel_group'] );
				$index ++;
			}
			if (in_array ( 'ORDER_COUNTS', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['ORDER_COUNTS'] );
				$index ++;
			}
			if (in_array ( 'BALANCE_PAY_COUNTS', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['BALANCE_PAY_COUNTS'] ) ? $item ['BALANCE_PAY_COUNTS'] : '--' );
				$index ++;
			}
			if (in_array ( 'WEIXIN_PAY_COUNTS', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['WEIXIN_PAY_COUNTS'] ) ? $item ['WEIXIN_PAY_COUNTS'] : '--' );
				$index ++;
			}
			if (in_array ( 'POINT_PAY_COUNTS', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['POINT_PAY_COUNTS'] );
				$index ++;
			}
			if (in_array ( 'TICKET_PAY_COUNTS', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['TICKET_PAY_COUNTS'] );
				$index ++;
			}
			if (in_array ( 'GRADES_PRODUCTS_COUNTS', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['GRADES_PRODUCTS_COUNTS'] );
				$index ++;
			}
			if (in_array ( 'TOTAL_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['TOTAL_AMOUNT'] );
				$index ++;
			}
			if (in_array ( 'BALANCE_PAY_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['BALANCE_PAY_AMOUNT'] );
				$index ++;
			}
			if (in_array ( 'WEIXIN_PAY_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['WEIXIN_PAY_AMOUNT'] );
				$index ++;
			}
			if (in_array ( 'SHOP_PAY_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['SHOP_PAY_AMOUNT'] ) ? $item ['SHOP_PAY_AMOUNT'] : '--' );
				$index ++;
			}
			if (in_array ( 'TICKET_PAY_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['TICKET_PAY_AMOUNT'] ) ? $item ['TICKET_PAY_AMOUNT'] : '--' );
				$index ++;
			}
			if (in_array ( 'POINT_PAY_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['POINT_PAY_AMOUNT'] );
				$index ++;
			}
			if (in_array ( 'GRADES_AMOUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['GRADES_AMOUNT'] ) ? $item ['GRADES_AMOUNT'] : '-' );
				$index ++;
			}
			if (in_array ( 'GRADES_COUNT', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['GRADES_COUNT'] ) ? $item ['GRADES_COUNT'] : '-' );
				$index ++;
			}
			if (in_array ( 'SALER_COUNT', $fields )) {
				$saler_count = '--';
				if(is_null($item['hotel_id'])){
					$saler_count = $hs_array['NULL'];
				}else{
					$saler_count = $hs_array[$item['hotel_id']];
				}
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $saler_count );
				$index ++;
			}
			if (in_array ( 'RANKING', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['RANKING'] ) ? $item ['RANKING'] : '-' );
				$index ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	public function mall_orders(){
		$keys = $this->uri->segment(4);
		$params['order_id'] = $this->input->post('order_id');
		$params['botime']   = $this->input->post('botime');
		$params['eotime']   = $this->input->post('eotime');
		$params['bgtime']   = $this->input->post('bgtime');
		$params['egtime']   = $this->input->post('egtime');
		$params['customer'] = $this->input->post('customer');
		$params['ticket']   = $this->input->post('ticket');
		$params['pay_typ']  = $this->input->post('pay_typ');
		$params['saler']    = $this->input->post('saler');
		$params['saler_no'] = $this->input->post('saler_no');
		$params['department'] = $this->input->post('department');
		$keys     = explode('_', $keys);
		if(!empty($keys[0])){
			$params['order_id'] = trim($keys[0]);
		}
		if(!empty($keys[1])){
			$params['botime'] = $keys[1];
		}
		if(!empty($keys[2])){
			$params['eotime'] = $keys[2];
		}
		if(!empty($keys[3])){
			$params['bgtime'] = $keys[3];
		}
		if(!empty($keys[4])){
			$params['egtime'] = $keys[4];
		}
		if(!empty($keys[5])){
			$params['customer'] = $keys[5];
		}
		if(!empty($keys[6])){
			$params['ticket'] = $keys[6];
		}
		if(!empty($keys[7])){
			$params['pay_typ'] = urldecode($keys[7]);
		}
		if(!empty($keys[8])){
			$params['saler'] = $keys[8];
		}
		if(!empty($keys[9])){
			$params['saler_no'] = $keys[9];
		}
        if(!empty($keys[10])){
            $params['department'] = urldecode($keys[10]);
        }
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_MALL_ORDERS',$admin_profile['admin_id']);
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->report_model->get_mall_grades_order($admin_profile['inter_id'],$params,$config['per_page'],$config['cur_page'])->result_array();
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/distri_report/mall_orders/".$params['order_id'].'_'.$params['botime'].'_'.$params['eotime'].'_'.$params['bgtime'].'_'.$params['egtime'].'_'.$params['customer'].'_'.$params['ticket'].'_'.$params['pay_typ'].'_'.$params['saler'].'_'.$params['saler_no'].'_'.$params['department']);
		$config['total_rows']        = $this->report_model->get_mall_grades_order_count($admin_profile['inter_id'],$params);
		$config['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="paginate_button">';
		$config['num_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="paginate_button first">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="paginate_button last">';
		$config['last_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="paginate_button previous">';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="paginate_button next">';
		$config['next_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

        //部门
        $this->load->model('distribute/qrcodes_model');
        $depts = $this->qrcodes_model->get_staff_depts($admin_profile['inter_id']);

		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res,
				'posts'      => $params,
				'confs'      => $confs,
				'hotels'     => $hotels,
				'depts'      => $depts,
                'deptment'   => $params['department'],
				'ostatus'    => array(12=>'购买成功',14=>'订单取消',15=>'拼团中',16=>'拼团成功'),
				'total'      => $config['total_rows']
		);
		echo $this->_render_content($this->_load_view_file('mall_grades_order'), $view_params, TRUE);
	}
	public function ext_mall_orders(){
		$keys = $this->uri->segment(4);
		$params['order_id'] = $this->input->post('order_id');
		$params['botime']   = $this->input->post('botime');
		$params['eotime']   = $this->input->post('eotime');
		$params['bgtime']   = $this->input->post('bgtime');
		$params['egtime']   = $this->input->post('egtime');
		$params['customer'] = $this->input->post('customer');
		$params['ticket']   = $this->input->post('ticket');
		$params['pay_typ']  = $this->input->post('pay_typ');
		$params['saler']    = $this->input->post('saler');
		$params['saler_no'] = $this->input->post('saler_no');
		$params['department'] = $this->input->post('department');
		$keys     = explode('_', $keys);
		if(!empty($keys[0])){
			$params['order_id'] = trim($keys[0]);
		}
		if(!empty($keys[1])){
			$params['botime'] = $keys[1];
		}
		if(!empty($keys[2])){
			$params['eotime'] = $keys[2];
		}
		if(!empty($keys[3])){
			$params['bgtime'] = $keys[3];
		}
		if(!empty($keys[4])){
			$params['egtime'] = $keys[4];
		}
		if(!empty($keys[5])){
			$params['customer'] = $keys[5];
		}
		if(!empty($keys[6])){
			$params['ticket'] = $keys[6];
		}
		if(!empty($keys[7])){
			$params['pay_typ'] = $keys[7];
		}
		if(!empty($keys[8])){
			$params['saler'] = $keys[8];
		}
		if(!empty($keys[9])){
			$params['saler_no'] = $keys[9];
		}
        if(!empty($keys[10])){
            $params['department'] = urldecode($keys[10]);
        }
		$this->load->model ( 'hotel/hotel_model' );
		$admin_profile = $this->session->userdata('admin_profile');
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$this->load->model('distribute/report_model');
		
		$confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_MALL_ORDERS',$admin_profile['admin_id']);
		
		$res = $this->report_model->get_mall_grades_order($admin_profile['inter_id'],$params)->result_array();
		$order_status = array(12=>'购买成功',14=>'订单取消',15=>'拼团中',16=>'拼团成功');
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$index = 0;
		$fields = array();
		foreach ($confs as $key=>$item){
			if($item['must'] == 1 || $item['choose'] == 1){
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
				$index ++;
				$fields[] = $key;
			}
		}
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$index = 0;
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $index, $row, $item ['order_id'] );
			$index = 1;
			if (in_array ( 'sub_order_id', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['sub_order_id'] );
				$index ++;
			}
			if (in_array ( 'pms_order_id', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['pms_order_id'] );
				$index ++;
			}
			if (in_array ( 'member_card_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['member_card_no'] ) ? $item ['member_card_no'] : '--' );
				$index ++;
			}
			if (in_array ( 'membership_number', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['membership_number'] ) ? $item ['membership_number'] : '--' );
				$index ++;
			}
			if (in_array ( 'customer', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['customer'] );
				$index ++;
			}
			if (in_array ( 'cellphone', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['cellphone'] );
				$index ++;
			}
			if (in_array ( 'product', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['product'] );
				$index ++;
			}
			if (in_array ( 'product_group', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['product_group'] );
				$index ++;
			}
			if (in_array ( 'order_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['order_time'] );
				$index ++;
			}
			if (in_array ( 'counts', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['counts'] );
				$index ++;
			}
			if (in_array ( 'order_status', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $order_status[$item ['order_status']] ) ? $order_status[$item ['order_status']] : $item ['order_status'] );
				$index ++;
			}
			if (in_array ( 'price', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['price'] ) ? $item ['price'] : '--' );
				$index ++;
			}
			if (in_array ( 'shopping_mode', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['shopping_mode'] );
				$index ++;
			}
			if (in_array ( 'ticket', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['ticket'] ) ? $item ['ticket'] : '-' );
				$index ++;
			}
			if (in_array ( 'point', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['point'] ) ? $item ['point'] : '-' );
				$index ++;
			}
			if (in_array ( 'balance', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($item ['balance']) ? $item ['balance'] : '--' );
				$index ++;
			}
			if (in_array ( 'pay_typ', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['pay_typ'] ) ? $item ['pay_typ'] : '-' );
				$index ++;
			}
			if (in_array ( 'actually_paid', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['actually_paid'] ) ? $item ['actually_paid'] : '-' );
				$index ++;
			}
			if (in_array ( 'grade_typ', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['grade_typ'] == 2 ? '按次' : '粉丝归属' );
				$index ++;
			}
			if (in_array ( 'grade_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['grade_time'] ) ? $item ['grade_time'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['name'] ) ? $item ['name'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler_no', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['saler'] ) ? $item ['saler'] : '-' );
				$index ++;
			}
			if (in_array ( 'saler_hotel', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $hotels[$item ['hotel_id']] ) ? $hotels[$item ['hotel_id']] : '-' );
				$index ++;
			}
            if (in_array ( 'master_dept', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['master_dept'] ) ? $item ['master_dept'] : '-' );
                $index ++;
            }
			if (in_array ( 'hotel_group', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['hotel_group'] ) ? $item ['hotel_group'] : '-' );
				$index ++;
			}
			if (in_array ( 'grade_rates', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['grade_amount_rate'] ) ? $item ['grade_amount_rate'] : '-' );
				$index ++;
			}
			if (in_array ( 'grade_total', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['grade_total'] ) ? $item ['grade_total'] : '-' );
				$index ++;
			}
			if (in_array ( 'send_time', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['send_time'] ) ? $item ['send_time'] : '-' );
				$index ++;
			}
			if (in_array ( 'send_status', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['status'] == 2 ? '已发放' : '未发放' );
				$index ++;
			}
			if (in_array ( 'fans_hotel', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $hotels[$item ['fans_hotel']] ) ? $hotels[$item ['fans_hotel']] : '-' );
				$index ++;
			}
			if (in_array ( 'hotel_group1', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['hotel_group1'] ) ? $item ['hotel_group1'] : '-' );
				$index ++;
			}
			if (in_array ( 'fans_hotel_grades', $fields )) {
				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['fans_hotel_grades'] ) ? $item ['fans_hotel_grades'] : '-' );
				$index ++;
			}
			$row ++;
		}
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		// 发送标题强制用户下载文件
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
	}
	
	public function dist_summ(){
		$admin_profile = $this->session->get_admin_profile ();
		$this->load->model ( 'distribute/report_model' );
		$this->load->model ( 'distribute/qrcodes_model' );
		$this->load->model ( 'distribute/grades_model' );
		$this->load->helper('calculate');
		$base_summ = $this->report_model->get_dist_summ_by_date ( $admin_profile ['inter_id'], date ( 'Ymd', strtotime ( '-1 days' ) ) );
		$prevent_summ = $this->report_model->get_dist_summ_by_date ( $admin_profile ['inter_id'], date ( 'Ymd', strtotime ( '-2 days' ) ) );
// 		$inter_id_counts = $this->qrcodes_model->get_inter_id_counts_with_pris ( array ( $admin_profile ['inter_id'] ) );
		$base_grades = $this->grades_model->get_saler_grades_all ( $admin_profile ['inter_id'], date ( 'Ymd', strtotime ( '-1 days' ) ), date ( 'Ymd', strtotime ( '-2 days' ) ) );
		$prevent_grades = $this->grades_model->get_saler_grades_all ( $admin_profile ['inter_id'], date ( 'Ymd', strtotime ( '-2 days' ) ), date ( 'Ymd', strtotime ( '-3 days' ) ) );
		$view_params = array (
				'base_summ' => $base_summ,
				'prevent_summ' => $prevent_summ,
				'base_grades' => $base_grades,
				'prevent_grades' => $prevent_grades 
		);
		echo $this->_render_content ( $this->_load_view_file ( 'dist_summ' ), $view_params, TRUE );
	}
	public function dist_base(){
		$admin_profile = $this->session->get_admin_profile ();	
		$this->load->model ( 'distribute/report_model' );
		$this->load->model ( 'wx/publics_model' );
		$this->load->model ( 'distribute/grades_model' );
		$send_typ   = $this->input->post('send_typ');
		$begin_time = '';
		$end_time   = '';
		$inter_ids  = $admin_profile['inter_id'];
		if($this->input->post('date_begin')){
			$begin_time = $this->input->post('date_begin');
		}
		if($this->input->post('date_end')){
			$et = new DateTime($this->input->post('date_end'));
			date_add($et,date_interval_create_from_date_string('1 days'));
			$end_time = $et->format('Y-m-d');
		}
		if($this->input->post('inter_ids')){
			$inter_ids = explode(',', $this->input->post('inter_ids'));
		}
		$base_summ  = $this->report_model->get_dist_summ_group_by_date ( $inter_ids,$send_typ,$begin_time,$end_time );
		$grade_summ = $this->grades_model->get_saler_grades_group_by_inter_id($inter_ids,$begin_time,$end_time);
		$grade_arr  = array();
		foreach ($grade_summ as $grade){
			$grade_arr[$grade->inter_id] = $grade;
		}
		$where_params = array();
		if($admin_profile ['inter_id'] != 'ALL_PRIVILEGES'){
			$where_params[] = $admin_profile['inter_id'];
		}
		$paccounts = $this->publics_model->get_public_hash($where_params,array('inter_id','name'));
		$hcounts = $this->grades_model->get_hotel_counts_hash($admin_profile['inter_id']);
		$view_params = array (
				'grade_arr' => $grade_arr,
				'base_summ' => $base_summ->result(),
				'paccounts' => $this->publics_model->array_to_hash($paccounts,'name','inter_id'),
				'hcounts'   => $hcounts,
				'posts'     =>$this->input->post()
		);
		echo $this->_render_content ( $this->_load_view_file ( 'dist_base' ), $view_params, TRUE );
	}
	public function exp_dist_base(){
		ini_set('memory_limit','1024M');
		$admin_profile = $this->session->get_admin_profile ();	
		$this->load->model ( 'distribute/report_model' );
		$this->load->model ( 'wx/publics_model' );
		$this->load->model ( 'distribute/grades_model' );
		$send_typ   = $this->input->post('send_typ');
		$begin_time = '';
		$end_time   = '';
		$inter_ids  = $admin_profile['inter_id'];
		if($this->input->post('date_begin')){
			$begin_time = $this->input->post('date_begin');
		}
		if($this->input->post('date_end')){
			$et = new DateTime($this->input->post('date_end'));
			date_add($et,date_interval_create_from_date_string('1 days'));
			$end_time = $et->format('Y-m-d');
		}
		if($this->input->post('inter_ids')){
			$inter_ids = explode(',', $this->input->post('inter_ids'));
		}
		$base_summ  = $this->report_model->get_dist_summ_group_by_date ( $inter_ids,$send_typ,$begin_time,$end_time )->result();
		$grade_summ = $this->grades_model->get_saler_grades_group_by_inter_id($inter_ids,$begin_time,$end_time);
		$grade_arr  = array();
		foreach ($grade_summ as $grade){
			$grade_arr[$grade->inter_id] = $grade;
		}
		$where_params = array();
		if($admin_profile ['inter_id'] != 'ALL_PRIVILEGES'){
			$where_params[] = $admin_profile['inter_id'];
		}
		$paccounts = $this->publics_model->get_public_hash($where_params,array('inter_id','name'));
		$hcounts = $this->grades_model->get_hotel_counts_hash($admin_profile['inter_id']);
// 		$view_params = array (
// 				'grade_arr' => $grade_arr,
// 				'base_summ' => $base_summ->result(),
// 				'paccounts' => $this->publics_model->array_to_hash($paccounts,'name','inter_id'),
// 				'hcounts'   => $hcounts,
// 				'posts'     =>$this->input->post()
// 		);
		$paccounts = $this->publics_model->array_to_hash($paccounts,'name','inter_id');
		$data = "";
		$data .= 'interID';
		$data .= ',酒店名称';
		$data .= ',分销间夜数';
		$data .= ',分销商品数';
		$data .= ',新增分销员';
		$data .= ',新增粉丝数';
		$data .= ',门店总数';
		$data .= ',产生交易酒店数';
		$data .= ',交易总额';
		$data .= ',发放方式';
		$data .= ',员工佣金总额';
		$data .= ',分销间夜数排名';
		$data .= "\n";
		foreach ($base_summ as $bs ) {
			$data .= $bs->inter_id.',';
			$data .= (isset($paccounts[$bs->inter_id]) ? $paccounts[$bs->inter_id] : '-' ).',';
			$data .= (empty($bs->room_counts) ? '0,' : $bs->room_counts.",");
			$data .= (empty($bs->product_counts) ? '0,' : $bs->product_counts.",");
			$data .= (empty($bs->new_saler_counts) ? '0,' : $bs->new_saler_counts.",");
			$data .= (empty($bs->new_fans_counts) ? '0,' : $bs->new_fans_counts.",");
			$data .= (empty($hcounts[$bs->inter_id]) ? '0,' : $hcounts[$bs->inter_id].",");
			$data .= (empty($grade_arr[$bs->inter_id]->hotel_count) ? '0,' : $grade_arr[$bs->inter_id]->hotel_count.",");
// 			$data .= (empty($grade_arr[$bs->inter_id]->grade_amount) ? '0,' : $grade_arr[$bs->inter_id]->grade_amount.",");
			$data .= (empty($bs->mall_trans + $bs->room_trans) ? '0,' : ($bs->mall_trans + $bs->room_trans).",");
			$data .= ($bs->send_typ == 1 ? '线下发放,' : '自动发放,');
			$data .= (empty($grade_arr[$bs->inter_id]->grade_total) ? '0,' : $grade_arr[$bs->inter_id]->grade_total.",");
			$data .= (empty($bs->room_counts) ? '0,' : $bs->room_counts.",");
			$data .= "\n";
		}
		
		// 发送标题强制用户下载文件
		header ('Content-Type: text/csv;charset=utf-8' );
		header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;
		
	}
	
	function get_dis_summ_chart(){
		$date_span = $this->input->get('ds') ? $this->input->get('ds') : 7;
		$this->load->model('distribute/report_model');
		$admin_profile = $this->session->get_admin_profile();
		$res   = $this->report_model->get_dist_room_pro_fas_by_date($admin_profile['inter_id'],date('Ymd',strtotime("-{$date_span} days")),date('Ymd'))->result_array();
		$index = array_column($res, 'date');
		$rc    = array_column($res, 'rc');
		$pc    = array_column($res, 'pc');
		$nfc   = array_column($res, 'nfc');
		echo json_encode(array('index'=>$index,'rc'=>$rc,'pc'=>$pc,'nfc'=>$nfc),JSON_NUMERIC_CHECK);
		exit;
	}

    public function club_orders(){
        $keys = $this->uri->segment(4);
// 		var_dump($this->input->post());exit;
        $avgs['hotel_id']         = $this->input->post('hotel_id');
        $avgs['cout_date_begin']  = $this->input->post('cout_date_begin');
        $avgs['cout_date_end']    = $this->input->post('cout_date_end');
        $avgs['order_id']         = $this->input->post('order_id');
        $avgs['saler_name']       = $this->input->post('saler_name');
        $avgs['saler_no']         = $this->input->post('saler_no');
        $avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
        $avgs['grade_date_end']   = $this->input->post('grade_date_end');
        $avgs['send_date_begin']  = $this->input->post('send_date_begin');
        $avgs['send_date_end']    = $this->input->post('send_date_end');
        $keys = explode('_', $keys);
        if(!empty($keys[0])){
            $avgs['hotel_id'] = $keys[0];
        }
        if(!empty($keys[1])){
            $avgs['cout_date_begin'] = $keys[1];
        }
        if(!empty($keys[2])){
            $avgs['cout_date_end'] = $keys[2];
        }
        if(!empty($keys[3])){
            $avgs['order_id'] = $keys[3];
        }
        if(!empty($keys[4])){
            $avgs['saler_name'] = urldecode($keys[4]);
        }
        if(!empty($keys[5])){
            $avgs['saler_no'] = $keys[5];
        }
        if(!empty($keys[6])){
            $avgs['grade_date_begin'] = $keys[6];
        }
        if(!empty($keys[7])){
            $avgs['grade_date_end'] = $keys[7];
        }
        if(!empty($keys[8])){
            $avgs['send_date_begin'] = $keys[8];
        }
        if(!empty($keys[9])){
            $avgs['send_date_end'] = $keys[9];
        }
        $this->load->model('distribute/report_model');
        $admin_profile = $this->session->userdata('admin_profile');
        $confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_CLUB_ORDER',$admin_profile['admin_id']);
        $this->load->library('pagination');
        $config['per_page']          = 20;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];


        $this->load->model ( 'hotel/hotel_model' );
        $filterH ['inter_id'] = $admin_profile['inter_id'];
        if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
            $filterH ['hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
        $hotels = $this->hotel_model->get_hotel_hash ( $filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );


        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $avgs['inter_id'] = $admin_profile['inter_id'];
        if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20160902
            $avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
        $res = $this->report_model->get_all_club_orders($avgs,$config['per_page'],$config['cur_page']);
// 		$res = $this->report_model->get_orders($config['per_page'],$config['cur_page'],$hotel_id,$order_id,$check_out,$saler_name,$saler_no);
        $config['uri_segment']       = 5;
// 		$config['suffix']            = $sub_fix;
        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url("distribute/distri_report/club_orders/".$avgs['hotel_id'].'_'.$avgs['cout_date_begin'].'_'.$avgs['cout_date_end'].'_'.$avgs['order_id'].'_'.$avgs['saler_name'].'_'.$avgs['saler_no'].'_'.$avgs['grade_date_begin'].'_'.$avgs['grade_date_end'].'_'.$avgs['send_date_begin'].'_'.$avgs['send_date_end']);
        $config['total_rows']        = $this->report_model->get_club_orders_count($avgs);
        $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="paginate_button">';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="paginate_button first">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="paginate_button last">';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="paginate_button previous">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="paginate_button next">';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $this->load->model('hotel/hotel_model');
        $res = $res->result_array();
        if(!empty($res)){
            foreach($res as $key=>$arr){
                if(!empty($arr['coupon_give'])){
                    $arr_coupon_give = json_decode($arr['coupon_give']);
                    if(isset($arr_coupon_give->title)){
                        $res[$key]['coupon_give'] = implode(',',$arr_coupon_give->title);
                    }else{
                        $res[$key]['coupon_give']='';
                    }
                }

                if(!empty($arr['point_give'])){
                    $arr_point_give = json_decode($arr['point_give']);
                    if(isset($arr_point_give->give_amount)){
                        $res[$key]['point_give'] = $arr_point_give->give_amount;
                    }else{
                        $res[$key]['point_give']='';
                    }
                }

                if($arr['dis_status']==1 || $arr['dis_status']==2 || $arr['dis_status']==5 ){
                    $res[$key]['dis_status']='已核定';
                }else{
                    $res[$key]['dis_status']='未核定';
                }

            }
        }
        $view_params= array(
            'pagination' => $this->pagination->create_links(),
            'res'        => $res,
            'confs'      => $confs,
            'hotels'     => $hotels,
            'posts'      => $avgs,
            'total'      => $config['total_rows'],
            'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
            'status'     => array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败'),
            'paytype'    => array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分')
        );
        $html= $this->_render_content($this->_load_view_file('club_order'), $view_params, TRUE);
        echo $html;
    }



    public function ext_club_orders_all(){
        ini_set('memory_limit','265M');
        set_time_limit(120);
        $keys = $this->uri->segment(4);
        $avgs['hotel_id']         = $this->input->post('hotel_id');
        $avgs['cout_date_begin']  = $this->input->post('cout_date_begin');
        $avgs['cout_date_end']    = $this->input->post('cout_date_end');
        $avgs['order_id']         = $this->input->post('order_id');
        $avgs['saler_name']       = $this->input->post('saler_name');
        $avgs['saler_no']         = $this->input->post('saler_no');
        $avgs['grade_date_begin'] = $this->input->post('grade_date_begin');
        $avgs['grade_date_end']   = $this->input->post('grade_date_end');
        $avgs['send_date_begin']  = $this->input->post('send_date_begin');
        $avgs['send_date_end']    = $this->input->post('send_date_end');
        $keys = explode('_', $keys);
        if(!empty($keys[0])){
            $avgs['hotel_id'] = $keys[0];
        }
        if(!empty($keys[1])){
            $avgs['cout_date_begin'] = $keys[1];
        }
        if(!empty($keys[2])){
            $avgs['cout_date_end'] = $keys[2];
        }
        if(!empty($keys[3])){
            $avgs['order_id'] = $keys[3];
        }
        if(!empty($keys[4])){
            $avgs['saler_name'] = $keys[4];
        }
        if(!empty($keys[5])){
            $avgs['saler_no'] = $keys[5];
        }
        if(!empty($keys[6])){
            $avgs['grade_date_begin'] = $keys[6];
        }
        if(!empty($keys[7])){
            $avgs['grade_date_end'] = $keys[7];
        }
        if(!empty($keys[8])){
            $avgs['send_date_begin'] = $keys[8];
        }
        if(!empty($keys[9])){
            $avgs['send_date_end'] = $keys[9];
        }
        $this->load->model('distribute/report_model');
        $admin_profile = $this->session->userdata('admin_profile');
        $confs = $this->report_model->get_dist_field_conf($admin_profile['inter_id'],'DIST_CLUB_ORDER',$admin_profile['admin_id']);
        $config['per_page'] = 20;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];


        $this->load->model ( 'hotel/hotel_model' );
        $filterH ['inter_id'] = $admin_profile['inter_id'];
        $hotels = $this->hotel_model->get_hotel_hash ( $filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $avgs['inter_id'] = $admin_profile['inter_id'];
        if(!empty($admin_profile['entity_id'])){//是否有具体的酒店权限控制 添加筛选  @author：stgc 20161104
            $avgs ['entity_hotel_id'] = explode(',',$admin_profile['entity_id']);
        }
        $res  = $this->report_model->get_all_club_orders($avgs)->result_array();

        if(!empty($res)){
            foreach($res as $key=>$arr){
                if(!empty($arr['coupon_give'])){
                    $arr_coupon_give = json_decode($arr['coupon_give']);
                    if(isset($arr_coupon_give->title)){
                        $res[$key]['coupon_give'] = implode(',',$arr_coupon_give->title);
                    }else{
                        $res[$key]['coupon_give']='';
                    }
                }

                if(!empty($arr['point_give'])){
                    $arr_point_give = json_decode($arr['point_give']);
                    if(isset($arr_point_give->give_amount)){
                        $res[$key]['point_give'] = $arr_point_give->give_amount;
                    }else{
                        $res[$key]['point_give']='';
                    }
                }


                if($arr['dis_status']==1 || $arr['dis_status']==2 || $arr['dis_status']==5 ){
                    $res[$key]['dis_status']='已核定';
                }else{
                    $res[$key]['dis_status']='未核定';
                }

            }
        }

        $paid    = array(0=>'未支付',1=>'微信已支付',2=>'门店付款');
        $status  = array(0=>'待确认',1=>'已确认',2=>'已入住',3=>'已离店',4=>'用户取消',5=>'酒店取消',6=>'酒店删除',7=>'异常',8=>'未到',9=>'未支付',10=>'下单失败');
        $paytype = array('weixin'=>'微信支付','daofu'=>'到付','balance'=>'储值','point'=>'积分');
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        //主订单号	子单号	PMS订单号	微信会员号	pms会员号	订房人	入住酒店
        //酒店分组	房型	价格代码	入住日期	离店日期	间夜
        //下单价格	券的形式	用券金额	积分使用量	储值支付金额	支付方式	实际价格	绩效核定时间	分销员姓名	分销号	分销员所属酒店
        //酒店分组	绩效比例或金额	分销员绩效	绩效发放时间	发放成功与否	粉丝所属酒店	酒店分组	粉丝所属酒店佣金
        $index = 0;
        $fields = array();
        foreach ($confs as $key=>$item){
            if($item['must'] == 1 || $item['choose'] == 1){
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, 1, $item['name'] );
                $index ++;
                $fields[] = $key;
            }
        }
        // Fetching the table data
        $row = 2;
        foreach ( $res as $item ) {
            $index = 0;
            if (in_array ( 'csname', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['csname'] );
                $index ++;
            }
            if (in_array ( 'club_name', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['club_name'] );
                $index ++;
            }
            if (in_array ( 'coupon_give', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['coupon_give'] ) ? $item ['coupon_give'] : '--' );
                $index ++;
            }
            if (in_array ( 'point_give', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['point_give'] ) ? $item ['point_give'] : '--' );
                $index ++;
            }
            if (in_array ( 'dis_status', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['dis_status'] );
                $index ++;
            }
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0 + $index, $row, $item ['orderid'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1 + $index, $row, $item ['oiid'] );
            $index = 7;
            if (in_array ( 'web_orderid', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['web_orderid'] );
                $index ++;
            }
            if (in_array ( 'mem_card_no', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['mem_card_no'] ) ? $item ['mem_card_no'] : '--' );
                $index ++;
            }
            if (in_array ( 'membership_number', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['membership_number'] ) ? $item ['membership_number'] : '--' );
                $index ++;
            }
            if (in_array ( 'name', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['name'] );
                $index ++;
            }
            if (in_array ( 'in_hotel_id', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['order_hotel']]) ? $hotels [$item ['order_hotel']] : '--' );
                $index ++;
            }
            if (in_array ( 'roomname', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['roomname'] );
                $index ++;
            }
            if (in_array ( 'startdate', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['startdate'] );
                $index ++;
            }
            if (in_array ( 'enddate', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['enddate'] );
                $index ++;
            }
            if (in_array ( 'grade_time', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['grade_time'] );
                $index ++;
            }
            if (in_array ( 'price', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['price'] );
                $index ++;
            }
            if (in_array ( 'coupon_favour', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['coupon_favour'] ) ? $item ['coupon_favour'] : '--' );
                $index ++;
            }
            if (in_array ( 'point_used', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['point_used'] ) ? $item ['point_used'] : '--' );
                $index ++;
            }
            if (in_array ( 'paytype', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $paytype [$item ['paytype']] ) ? $paytype [$item ['paytype']] : '到付' );
                $index ++;
            }
            if (in_array ( 'iprice', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, $item ['iprice'] );
                $index ++;
            }
            if (in_array ( 'staff_name', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['staff_name'] ) ? $item ['staff_name'] : '-' );
                $index ++;
            }
            if (in_array ( 'saler', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['saler'] ) ? $item ['saler'] : '-' );
                $index ++;
            }
            if (in_array ( 'saler_hotel_name', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['hotel_id']]) ? $hotels [$item ['hotel_id']] : '--' );
                $index ++;
            }
            if (in_array ( 'grade_total', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['grade_total'] ) ? $item ['grade_total'] : '-' );
                $index ++;
            }
            if (in_array ( 'send_time', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['send_time'] ) ?$item ['send_time'] : '-' );
                $index ++;
            }
            if (in_array ( 'fans_hotel_name', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset($hotels [$item ['fans_hotel']]) ? $hotels [$item ['fans_hotel']] : '--' );
                $index ++;
            }
            if (in_array ( 'partner_trade_no', $fields )) {
                $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( $index, $row, isset ( $item ['partner_trade_no'] ) ?$item ['partner_trade_no'] : '-' );
                $index ++;
            }
            $row ++;
        }
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }


    /**
     * 更新 报表列配置
     */
    public function update_report_config()
    {
        $this->load->model('distribute/report_model');
        $where = array(
            'DIST_ROOMS_ORDER',
            'DIST_MALL_ORDERS',
        );
        $data = $this->report_model->report_config_model($where);

        if (!empty($data))
        {
            foreach ($data as $key => $item)
            {
                $conf_val = unserialize($item['conf_val']);

                $keys = array_keys($conf_val);
                $new_arr = array();
                $master_dept = array(
                    'must' => 2,
                    'choose' => 1,
                    'name' => '酒店部门',
                );
                foreach ($keys as $val)
                {
                    if ($val == 'saler_hotel_name' || $val == 'saler_hotel')
                    {
                        $new_arr[$val] = $conf_val[$val];
                        $new_arr['master_dept'] = $master_dept;
                    }
                    else
                    {
                        $new_arr[$val] = $conf_val[$val];
                    }
                }

                $update['conf_val'] = serialize($new_arr);
                //更改
                $this->db->update('distribute_report_config',$update,array('id'=>$item['id']));
            }
        }
    }

}
