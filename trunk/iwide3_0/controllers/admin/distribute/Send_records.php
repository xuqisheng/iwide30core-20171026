<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Send_records extends MY_Admin {
	
	public function __construct(){
		parent::__construct();
		if($this->input->get('debug') == 1){
			$this->output->enable_profiler(true);
		}
	}
	
	public function records() {
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );
		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number' 
		);
		$keys = $this->uri->segment(4);
		$from     = $this->input->post('from_date');
		$batch_no = $this->input->post('order_id');
// 		$msg_typ  = $this->input->post('msg_typ');
		$to       = $this->input->post('to_date');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$batch_no = $keys[0];
		}
		if(!empty($keys[1])){
			$from = $keys[1];
		}
		if(!empty($keys[2])){
			$to = $keys[2];
		}
// 		if(isset($keys[3])){
// 			//success : 2,fail : 1,all : null
// 			$msg_typ = $keys[3];
// 		}
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = base_url ( "index.php/distribute/send_records/records/".$batch_no.'_'.$from.'_'.$to ).'?s='.$source;
		$config ['total_rows'] = $this->send_records_model->get_parent_records ( $inter_id, $batch_no, $from, $to,NULL,0,$source)->num_rows();
		$config ['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="paginate_button">';
		$config ['num_tag_close'] = '</li>';
		$config ['first_tag_open'] = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="paginate_button last">';
		$config ['last_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li class="paginate_button previous">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li class="paginate_button next">';
		$config ['next_tag_close'] = '</li>';
		$this->pagination->initialize ( $config );
		$query = $this->send_records_model->get_parent_records ( $inter_id, $batch_no, $from, $to, $config ['per_page'],$page,$source )->result();
		$view_params = array (
				'pagination' => $this->pagination->create_links (),
				'res' => $query, 
				'order_no' => $batch_no, 
				'source' => $source,
				'from' => $from,
				'to' => $to 
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'records' ), $view_params, TRUE );
		echo $html;
	}
	public function sub_records(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );

		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$order_no   = NULL;
		$saler_no   = NULL;
		$saler_name = NULL;
		$msg_typ    = null;
		$batch_no   = '';
		$keys = $this->uri->segment(4);
		$order_no   = $this->input->post('order_id');
		$batch_no   = $this->input->get('bn');
		$msg_typ    = $this->input->post('msg_typ');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$order_no = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_no = $keys[1];
		}
		if(!empty($keys[2])){
			$saler_name = $keys[2];
		}
		if(!empty($keys[3])){
			//success : 2,fail : 1,all : null
			$msg_typ = $keys[3];
		}
		if(!empty($keys[4])){
			$batch_no = $keys[4];
		}
		
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number'
		);
		$config['suffix'] = '?bn='.$batch_no.'&s='.$source;
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = site_url("distribute/send_records/sub_records/".$order_no.'_'.$saler_no.'_'.$saler_name.'_'.$msg_typ.'_'.$batch_no);
		$config ['first_url'] = $config ['base_url'] . '?s='.$source;
		$config ['total_rows'] = $this->send_records_model->get_records ( $inter_id,$batch_no,$msg_typ,$order_no,$saler_no,$saler_name,null,0,$source)->num_rows();
		$config ['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="paginate_button">';
		$config ['num_tag_close'] = '</li>';
		$config ['first_tag_open'] = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="paginate_button last">';
		$config ['last_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li class="paginate_button previous">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li class="paginate_button next">';
		$config ['next_tag_close'] = '</li>';
		$this->pagination->initialize ( $config );
		$query = $this->send_records_model->get_records ( $inter_id,$batch_no,$msg_typ,$order_no,$saler_no,$saler_name, $config ['per_page'],$page,$source )->result();
		$this->load->model('hotel/hotel_model');
		
		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->get_admin_inter_id ()),array('hotel_id','name'));
		$hotel_arr = array();
		foreach ($hotels as $hotel){
			$hotel_arr [$hotel['hotel_id']] = $hotel['name'];
		}
		$view_params = array (
				'pagination' => $this->pagination->create_links (),
				'res'        => $query,
				'hotels'     => $hotel_arr,
				'order_no'   => $order_no,
				'batch_no'   => $batch_no,
				'saler_no'   => $saler_no,
				'saler_name' => $saler_name,
				'msg_typ'    => $msg_typ,
				'source'	 => $source,
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'sub_records' ), $view_params, TRUE );
		echo $html;
	}
	
	public function ext_records(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$keys = $this->uri->segment(4);
		$from     = $this->input->post('from_date');
		$batch_no = $this->input->post('order_id');
		// 		$msg_typ  = $this->input->post('msg_typ');
		$to       = $this->input->post('to_date');
		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$batch_no = $keys[0];
		}
		if(!empty($keys[1])){
			$from = $keys[1];
		}
		if(!empty($keys[2])){
			$to = $keys[2];
		}
		$query = $this->send_records_model->get_parent_records ( $inter_id, $batch_no, $from, $to,null,0,$source)->result_array();
		
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '发放编号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '发放时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '核定截止' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '发放人数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '发放笔数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '发放总额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '发放状态' );
			
		// Fetching the table data
		$row = 2;
		foreach ( $query as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['batch_no'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['send_time'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, date('Y-m-d 23:59:59',strtotime('-1 day',strtotime($item['send_time']))) );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['times'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['times'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['amount']/100 );
			$msg = '';
			if($item['sts'] == 1 && $item['status'] == 1){
				$msg =  '成功';
			}elseif($item['sts'] == 1 && $item['status'] == 2){
				$msg =  '全部失败';
			}else{
				$msg = '部分失败';
			}
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $msg );
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
	public function ext_sub_records(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );

		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$order_no   = NULL;
		$saler_no   = NULL;
		$saler_name = NULL;
		$msg_typ    = null;
		$keys = $this->uri->segment(4);
		$order_no   = $this->input->post('order_id');
		$batch_no   = $this->input->get('bn');
		$msg_typ    = $this->input->post('msg_typ');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$order_no = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_no = $keys[1];
		}
		if(!empty($keys[2])){
			$saler_name = $keys[2];
		}
		if(!empty($keys[3])){
			//success : 2,fail : 1,all : null
			$msg_typ = $keys[3];
		}
		if(!empty($keys[4])){
			$batch_no = $keys[4];
		}
		$query = $this->send_records_model->get_records ( $inter_id,$batch_no,$msg_typ,$order_no,$saler_no,$saler_name,NULL,0,$source )->result_array();
		$this->load->model('hotel/hotel_model');
		
		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->get_admin_inter_id ()),array('hotel_id','name'));
		$hotel_arr = array();
		foreach ($hotels as $hotel){
			$hotel_arr [$hotel['hotel_id']] = $hotel['name'];
		}
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '订单号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '商品名称' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '间夜' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '订单酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '实际金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '核定时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '发放状态' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '分销员' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '返佣金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '发放单号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '备注' );
			
		// Fetching the table data
		$row = 2;
		foreach ( $query as $item ) {

            if ($item['grade_table'] == 'iwide_hotels_order')
            {
                $get_room_night = get_room_night($item['startdate'], $item['enddate'], 'ceil');
            }
            else
            {
                $get_room_night = '--';
            }

			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['order_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['product'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $get_room_night );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, empty($hotel_arr[$item['hotel_id']]) ? '' : $hotel_arr[$item['hotel_id']]);
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['grade_amount'] == 0 ? '--' : $item['grade_amount'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['grade_time'] );
			$msg = '';
			if($item['status'] == 2){
				$msg = '发放失败';
			}elseif($item['status'] == 1){
				$msg = '已发放';
			}
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $msg );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['saler'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['staff_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['hotel_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['grade_total'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $item['partner_trade_no'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, $item['status'] == 1 ? '--' : $item['remark'] );
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
	
	public function batch_log() { 
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number'
		);
		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$keys = $this->uri->segment(4);
		$from     = $this->input->post('from_date');
		$batch_no = $this->input->post('order_id');
		$to       = $this->input->post('to_date');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$batch_no = $keys[0];
		}
		if(!empty($keys[1])){
			$from = $keys[1];
		}
		if(!empty($keys[2])){
			$to = $keys[2];
		}
		$config['suffix'] = '?s='.$source;
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = base_url ( "index.php/distribute/send_records/batch_log/".$batch_no.'_'.$from.'_'.$to );
		$config ['first_url'] = $config ['base_url'] . '?s='.$source;
		$config ['total_rows'] = $this->send_records_model->get_batch_logs_count ( $inter_id, $batch_no, $from, $to,null,0,$source);
		$config ['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="paginate_button">';
		$config ['num_tag_close'] = '</li>';
		$config ['first_tag_open'] = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="paginate_button last">';
		$config ['last_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li class="paginate_button previous">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li class="paginate_button next">';
		$config ['next_tag_close'] = '</li>';
		$this->pagination->initialize ( $config );
		$query = $this->send_records_model->get_batch_logs ( $inter_id, $batch_no, $from, $to, $config ['per_page'],$page,$source )->result();
		$view_params = array (
				'pagination' => $this->pagination->create_links (),
				'res'        => $query,
				'order_no'   => $batch_no,
				'batch_no'   => $batch_no,
				'source'     => $source,
				'from'       => $from,
				'to'         => $to
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'batch_logs' ), $view_params, TRUE );
		echo $html;
	}
	

	public function saler_logs(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );

		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$saler_no   = NULL;
		$saler_name = NULL;
		$msg_typ    = null;
		$batch_no   = '';
		$keys = $this->uri->segment(4);
		$batch_no   = $this->input->get('bn');
		$msg_typ    = $this->input->post('msg_typ');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$saler_no = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_name = $keys[1];
		}
		if(!empty($keys[2])){
			//success : 2,fail : 1,all : null
			$msg_typ = $keys[2];
		}
		if(!empty($keys[3])){
			$batch_no = $keys[3];
		}
	
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number'
		);
		$config['suffix'] = '?bn='.$batch_no.'&s='.$source;
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = site_url("distribute/send_records/saler_logs/".$saler_no.'_'.$saler_name.'_'.$msg_typ.'_'.$batch_no);
		$config ['total_rows'] = $this->send_records_model->get_salers_log ( $inter_id,$batch_no,$msg_typ,$saler_no,$saler_name,NULL,0,$source)->num_rows();
		$config ['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="paginate_button">';
		$config ['num_tag_close'] = '</li>';
		$config ['first_tag_open'] = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="paginate_button last">';
		$config ['last_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li class="paginate_button previous">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li class="paginate_button next">';
		$config ['next_tag_close'] = '</li>';
		$this->pagination->initialize ( $config );
		$query = $this->send_records_model->get_salers_log ( $inter_id,$batch_no,$msg_typ,$saler_no,$saler_name, $config ['per_page'],$page,$source )->result();
		$this->load->model('hotel/hotel_model');
	
		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->get_admin_inter_id ()),array('hotel_id','name'));
		$hotel_arr = array();
		foreach ($hotels as $hotel){
			$hotel_arr [$hotel['hotel_id']] = $hotel['name'];
		}
		$view_params = array (
				'pagination' => $this->pagination->create_links (),
				'res'        => $query,
				'hotels'     => $hotel_arr,
				'batch_no'   => $batch_no,
				'saler_no'   => $saler_no,
				'saler_name' => $saler_name,
				'source'     => $source,
				'msg_typ'    => $msg_typ
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'saler_logs' ), $view_params, TRUE );
		echo $html;
	}
	
	public function record_orders(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );
	
		$record_id   = NULL;
		$keys = $this->uri->segment(4);
		$record_id   = $this->input->get('bn');
		if(!empty($keys)){
			$record_id = $keys;
		}
	
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number'
		);
		$source = 1;
		$config['suffix'] = '?bn='.$record_id;
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = site_url("distribute/send_records/record_orders/".$record_id);
		$config ['total_rows'] = $this->send_records_model->get_partner_logs ( $inter_id,$record_id,NULL,0,$source)->num_rows();
		$config ['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="paginate_button">';
		$config ['num_tag_close'] = '</li>';
		$config ['first_tag_open'] = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="paginate_button last">';
		$config ['last_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li class="paginate_button previous">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li class="paginate_button next">';
		$config ['next_tag_close'] = '</li>';
		$this->pagination->initialize ( $config );
		$query = $this->send_records_model->get_partner_logs ( $inter_id,$record_id, $config ['per_page'],$page,$source )->result();
		$this->load->model('hotel/hotel_model');
	
		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->get_admin_inter_id ()),array('hotel_id','name'));
		$hotel_arr = array();
		foreach ($hotels as $hotel){
			$hotel_arr [$hotel['hotel_id']] = $hotel['name'];
		}
		$view_params = array (
				'pagination' => $this->pagination->create_links (),
				'res'        => $query,
				'hotels'  => $hotel_arr
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'record_orders' ), $view_params, TRUE );
		echo $html;
	}
	public function ext_batch_log() {
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number'
		);
		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$keys = $this->uri->segment(4);
		$from     = $this->input->post('from_date');
		$batch_no = $this->input->post('order_id');
		$to       = $this->input->post('to_date');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$batch_no = $keys[0];
		}
		if(!empty($keys[1])){
			$from = $keys[1];
		}
		if(!empty($keys[2])){
			$to = $keys[2];
		}
		$query = $this->send_records_model->get_batch_logs ( $inter_id, $batch_no, $from, $to,NULL,0,$source)->result_array();
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '发放编号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '发放时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '核定截止时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '应发放人数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '应发放笔数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '应发放金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '发放成功人数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '发放成功笔数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '发放成功金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '发放失败人数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '发放失败笔数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '发放失败金额' );
			
		// Fetching the table data
		$row = 2;
		foreach ( $query as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['batch_no'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['send_time'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, date('Y-m-d 23:59:59',strtotime('-1 day',strtotime($item['send_time']))));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['saler_count']);
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['total_times'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['total_amount']/100 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['success_saler'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['success_total_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['success_amount']/100 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['saler_count']-$item['success_saler'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['total_times']-$item['success_total_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, ($item['total_amount']-$item['success_amount'])/100 );
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
	
	public function ext_saler_logs(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );
	
		$saler_no   = NULL;
		$saler_name = NULL;
		$msg_typ    = null;
		$batch_no   = '';
		$keys = $this->uri->segment(4);
		$batch_no   = $this->input->get('bn');
		$msg_typ    = $this->input->post('msg_typ');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$source = 1;
		if($this->input->get('s'))
			$source = intval($this->input->get('s'));
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$saler_no = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_name = $keys[1];
		}
		if(!empty($keys[2])){
			//success : 2,fail : 1,all : null
			$msg_typ = $keys[2];
		}
		if(!empty($keys[3])){
			$batch_no = $keys[3];
		}

		$query = $this->send_records_model->get_salers_log ( $inter_id,$batch_no,$msg_typ,$saler_no,$saler_name,NULL,0,$source )->result_array();
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '编号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '分销员' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '商户单号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '核发状态' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '发放总额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '发放笔数' );
			
		// Fetching the table data
		$row = 2;
		foreach ( $query as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['saler']);
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['hotel_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['partner_trade_no'] );
			$msg = '';
			if($item['status'] == 2){
				$msg = '发放失败';
			}elseif($item['status'] == 1){
				$msg = '已发放';
			}
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $msg );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['send_amount']/100 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['times'] );
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
	
	public function ext_record_orders(){
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/send_records_model' );
		$this->load->library ( 'pagination' );
	
		$record_id   = NULL;
		$keys = $this->uri->segment(4);
		$record_id   = $this->input->get('bn');
		if(!empty($keys)){
			$record_id = $keys;
		}

		$query = $this->send_records_model->get_partner_logs ( $inter_id,$record_id)->result_array();
		$this->load->model('hotel/hotel_model');
		
		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->get_admin_inter_id ()),array('hotel_id','name'));
		$hotel_arr = array();
		foreach ($hotels as $hotel){
			$hotel_arr [$hotel['hotel_id']] = $hotel['name'];
		}
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '订单号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '商品名称' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '订单酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '实际金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '核定时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '发放状态' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '分销员' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '返佣金额' );
			
		// Fetching the table data
		$row = 2;
		foreach ( $query as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['order_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['product'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, empty($hotel_arr[$item['order_hotel']]) ? '' : $hotel_arr[$item['order_hotel']]);
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['grade_amount'] == 0 ? '--' : $item['grade_amount'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['grade_time'] );
			$msg = '';
			if($item['status'] == 1){
				$msg = '发放失败';
			}elseif($item['status'] == 2){
				$msg = '已发放';
			}
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $msg );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['saler'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['staff_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, empty($hotel_arr[$item['hotel_id']]) ? '' : $hotel_arr[$item['hotel_id']] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['grade_total'] > 0 ? $item['grade_total'] : '-' );
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
	 * 处理发放异常记录 
	 */
// 	public function sstatus(){
// 		if($this->session->admin_profile['role']['role_name'] != 'admin'){
// 			exit('没有权限');
// 		}else{
// 			if(empty($this->uri->segment(4)) || empty($this->uri->segment(5))){
// 				exit('参数错误');
// 			}
// 			$this->load->model('distribute/Send_records_model');
// 			$id = intval($this->uri->segment(5));
// 			if($id < 1)
// 				exit('绩效编号错误');
// 			if($this->uri->segment(4) == 'f'){
// 				echo $this->Send_records_model->set_status($id,Send_records_model::SEND_STATUS_FAILD) ? '重置成功' : '重置失败';
// 			}else if($this->uri->segment(4) == 's'){
// 				echo $this->Send_records_model->set_status($id,Send_records_model::SEND_STATUS_SUCCESS) ? '重置成功' : '重置失败';
// 			}
// 		}
// 	}
}