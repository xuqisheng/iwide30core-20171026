<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msgauth extends MY_Admin {

	protected $label_module= '信息授权页面';
	protected $label_controller= '列表';
	protected $label_action= '';
	private $inter_id;
	protected $status_arr = array ('1' => '申请中','2' => '正常','3' => '未通过','4' => '停止绩效');
	protected $user_profiler;
	function __construct(){
		parent::__construct();
		$this->user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $this->user_profiler['inter_id'];
	}


	public function ext_send_logs(){
		$this->_init_breadcrumb ( $this->label_action );
		$keys        = $this->uri->segment ( 4 );
		$keys = explode ( '_', $keys );
		$params = array();
		if (! empty ( $keys [0] )) {
			$params['btime'] = $keys [0];
		}
		if (! empty ( $keys [1] )) {
			$params['etime']  = $keys [1];
		}
		if (! empty ( $keys [2] )) {
			$params['saler_name'] = $keys [2];
		}
		if (! empty ( $keys [3] )) {
			$params['saler_no'] = $keys [3];
		}
		if (! empty ( $keys [4] )) {
			$params['status'] = $keys [4];
		}
		if (! empty ( $keys [5] )) {
			$params['hotel'] = $keys [5];
		}
		if (! empty ( $keys [6] )) {
			$params['dept'] = $keys [6];
		}
		$this->load->model ( 'distribute/welfare_model' );
		$admin_profile = $this->session->userdata ( 'admin_profile' );
		$this->load->library ( 'pagination' );
		$saler_stat = array ('1' => '申请中','2' => '正常','3' => '未通过','4' => '停止绩效');
		$send_stat  = array ('1' => '成功','2' => '失败','3' => '异常');
		$res = $this->welfare_model->get_send_logs ( $params)->result ();
		$data = "";
		$data .= '分销员';
		$data .= ',分销号';
		$data .= ',所属酒店';
		$data .= ',所属部门';
		$data .= ',分销状态';
		$data .= ',发放时间';
		$data .= ',福利标题';
		$data .= ',发放金额';
		$data .= ',发放状态';
		$data .= ',发放商户号';
		$data .= "\n";
		foreach ($res as $item ) {
			$data .= mb_convert_encoding($item->name.',', 'GB18030');
			$data .= mb_convert_encoding($item->saler.",", 'GB18030');
			$data .= mb_convert_encoding($item->hotel_name.",", 'GB18030');
			$data .= mb_convert_encoding($item->master_dept.",", 'GB18030');
			$data .= mb_convert_encoding((isset($saler_stat[$item->saler_status]) ? $saler_stat[$item->saler_status]."," : "--,"), 'GB18030');
			$data .= mb_convert_encoding($item->send_time.",", 'GB18030');
			$data .= mb_convert_encoding($item->title.",", 'GB18030');
			$data .= $item->amount.",";
			$data .= mb_convert_encoding((isset($send_stat[$item->status]) ? $send_stat[$item->status].',' : '--,'), 'GB18030');
			$data .= $item->out_trade_no.",";
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
	public function ext_qrcodes(){
		$keys = $this->uri->segment(4);
		$hotel_id    = $this->input->post ( 'hotel_id' );
		$saler_name  = $this->input->post ( 'saler_name' );
		$saler_no    = $this->input->post ( 'saler_no' );
		$department  = $this->input->post ( 'department' );
		$gtime_begin = $this->input->post ( 'grade_time_begin' );
		$gtime_end   = $this->input->post ( 'grade_time_end' );
		$keys = explode ( '_', $keys );
		if (! empty ( $keys [0] )) {
			$hotel_id = $keys [0];
		}
		if (! empty ( $keys [1] )) {
			$saler_name = $keys [1];
		}
		if (! empty ( $keys [2] )) {
			$saler_no = $keys [2];
		}
		if (! empty ( $keys [3] )) {
			$department = $keys [3];
		}
		if (! empty ( $keys [4] )) {
			$gtime_begin = $keys [4];
		}
		if (! empty ( $keys [5] )) {
			$gtime_end = $keys [5];
		}
		$this->load->model('distribute/qrcodes_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$res = $this->qrcodes_model->get_salers ( $this->inter_id, 1, $saler_name, $saler_no, NULL, $hotel_id, $department, NULL, NULL, NULL, $gtime_begin, $gtime_end )->result ();
// 		$res = $this->qrcodes_model->get_salers($this->inter_id,1,$saler_name,$saler_no,$cellphone,$hotel_id,$department,$status)->result();
		// die;
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '姓名' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '手机号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '所属部门' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '分销状态' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '总收益' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '未发收益' );
		// Fetching the table data
		$row = 2;
		foreach ( $res as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item->name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item->qrcode_id );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item->cellphone );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item->hotel_name );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item->master_dept );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, isset($this->status_arr[$item->status]) ? $this->status_arr[$item->status] : '异常' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, empty($item->grade_total) ? 0 : $item->grade_total );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item->undeliver );
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


	
	public function admins(){
		$this->load->model('okpay/okpay_msgauth_model');
		$this->_init_breadcrumb ( $this->label_action );
		$this->load->model ( 'distribute/qrcodes_model' );
		$admin_profile = $this->session->userdata ( 'admin_profile' );
		$this->load->library ( 'pagination' );
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 4 ) ) ? 0 : ($this->uri->segment ( 4 ) - 1) * $config ['per_page'];
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile ['inter_id'];
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$res = $this->okpay_msgauth_model->get_admins ( $this->inter_id,$config ['per_page'], $config ['cur_page'] )->result ();
		$config ['uri_segment'] = 5;
		// $config['suffix'] = $sub_fix;
		$config ['numbers_link_vars'] = array (
				'class' => 'number'
		);
		$config ['cur_tag_open']    = '<a class="number current" href="#">';
		$config ['cur_tag_close']   = '</a>';
		$config ['base_url']        = site_url ( "okpay/msgauth/admins/" );
		$config ['total_rows']      = $this->okpay_msgauth_model->get_admins_count ( $this->inter_id );
		$config ['cur_tag_open']    = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close']   = '</a></li>';
		$config ['num_tag_open']    = '<li class="paginate_button">';
		$config ['num_tag_close']   = '</li>';
		$config ['first_tag_open']  = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open']   = '<li class="paginate_button last">';
		$config ['last_tag_close']  = '</li>';
		$config ['prev_tag_open']   = '<li class="paginate_button previous">';
		$config ['prev_tag_close']  = '</li>';
		$config ['next_tag_open']   = '<li class="paginate_button next">';
		$config ['next_tag_close']  = '</li>';
		$this->pagination->initialize ( $config );
		$depts = $this->qrcodes_model->get_staff_depts ( $this->inter_id );
		$view_params = array (
				'pagination'  => $this->pagination->create_links (),
				'res'         => $res,
				'total'       => $config ['total_rows'],
				'status_arr'  => $this->status_arr
		);
		echo $this->_render_content ( $this->_load_view_file ( 'admin_grid' ), $view_params, TRUE );
	}

	public function cksaler(){
		$saler_no   = $this->input->post('saler_no');
		$saler_name = $this->input->post('saler_name');
		$this->load->model('distribute/qrcodes_model');
		$query = $this->qrcodes_model->get_salers($this->inter_id,1,$saler_name,$saler_no,NULL,NULL,NULL,NULL,10);
		
		echo json_encode(array('count'=>$query->num_rows(),'res'=>$query->result()));
		exit;
	}
	public function new_auth(){
		$this->load->model('okpay/okpay_msgauth_model');
		echo json_encode($this->okpay_msgauth_model->create_admin());
		exit;
	}
	public function schange(){
    	$admin_profiler = $this->session->userdata('admin_profile');
        $this->load->model('okpay/okpay_msgauth_model');
		echo $this->okpay_msgauth_model->_update_admin_status($admin_profiler['inter_id'],$this->input->post('admin'),$this->input->post('status'),$this->input->post('typ')) ?
		json_encode(array('errcode'=>'ok','errmsg'=>'授权状态修改成功')) : json_encode(array('errcode'=>'failed','errmsg'=>'授权状态修改失败'));
	}
}