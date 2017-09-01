<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follower_report extends MY_Admin {

	protected $label_module= '平台粉丝';
	protected $label_controller= '平台粉丝';
	protected $label_action= '列表';
	private $inter_id;

	protected $user_profiler;
	function __construct(){
		parent::__construct();
		$this->user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $this->user_profiler['inter_id'];
	}
	
	public function index(){

		$admin_profile = $this->session->userdata ( 'admin_profile' );
		$inter_id = $admin_profile['inter_id'];
		if($inter_id== FULL_ACCESS) $inter_id= '';

		//$entity_id = $admin_profile['entity_id'];
		//查询
		$this->load->model('distribute/follower_report_model');
		//昨天
		$date = 1;
		$res1 = $res2 = array();
		$res1 = $this->follower_report_model->get_follower_data($inter_id,$date);
		//前一天
		$res2= $this->follower_report_model->get_follower_data($inter_id,$date+1);
		//var_dump($res1);var_dump($res2);die;die;
		$rate = array();
		if(!empty($res2) && !empty($res1)){
			if(isset($res2['all_fans_count']) && $res2['all_fans_count']!=NULL){
				$rate['all_fans_count'] = $res2['all_fans_count']==0?1:($res1['all_fans_count'] - $res2['all_fans_count'])/$res2['all_fans_count'];
				$rate['new_sub_count'] = $res2['new_sub_count']==0?1:($res1['new_sub_count'] - $res2['new_sub_count'])/$res2['new_sub_count'];
				$rate['new_unsub_count'] = $res2['new_unsub_count']==0?1:($res1['new_unsub_count'] - $res2['new_unsub_count'])/$res2['new_unsub_count'];
				$rate['new_add_count'] = $res2['new_add_count']==0?1:($res1['new_add_count'] - $res2['new_add_count'])/$res2['new_add_count'];
				$rate['saler_nums'] = $res2['saler_nums']==0?1:($res1['saler_nums'] - $res2['saler_nums'])/$res2['saler_nums'];
				$rate['saler_rate'] = $res2['saler_rate']==0?1:($res1['saler_rate'] - $res2['saler_rate'])/$res2['saler_rate'];
				$rate['all_sub_count'] = $res2['all_sub_count']==0?1:($res1['all_sub_count'] - $res2['all_sub_count'])/$res2['all_sub_count'];
				$rate['all_publics'] = $res2['all_publics']==0?1:($res1['all_publics'] - $res2['all_publics'])/$res2['all_publics'];
			}

		}
		if(!empty($rate)){
			foreach($rate as $k=>$v){
				$rate[$k] = (number_format($v,4,'.','')*100).'%';
			}
		}
		$view_params = array (
			//'pagination' => $this->pagination->create_links (),
			'res1' =>$res1,
			'rate' => $rate,
		);
		echo $this->_render_content ( $this->_load_view_file ( 'report' ), $view_params, TRUE );
	}

	//ajax取数据
	public function ajax_get_data(){
		$date = $this->input->get('check_date');
		//$date = 30;
		$admin_profile = $this->session->userdata ( 'admin_profile' );
		$inter_id = $admin_profile['inter_id'];
		if($inter_id== FULL_ACCESS) $inter_id= '';
		//查询
		$this->load->model('distribute/follower_report_model');
		$res = $this->follower_report_model->get_ajax_data($inter_id,$date);
		echo json_encode($res);die;

	}
	//粉丝平台详情
	public function detail(){//var_dump($_POST);die;
		$avgs ['hotel_public'] = $this->input->post ( 'hotel_public' );//酒店id
		$avgs ['inter_id'] = $this->input->post ( 'inter_id' );//inter_id
		$avgs ['start_time'] = $this->input->post ( 'start_time' );//开始
		$avgs ['end_time'] = $this->input->post ( 'end_time' );//结束
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS){
			//$filter= array();
		}else{
			$avgs ['inter_id'] = $inter_id;
		}

		$keys = $this->uri->segment ( 4 );
		$keys = explode ( '_', $keys );
		if (! empty ( $keys [0] )) {
			$avgs ['start_time'] = $keys [0];
		}
		if (! empty ( $keys [1] )) {
			$avgs ['end_time'] = $keys [1];
		}


		$this->load->library ( 'pagination' );
		$config ['per_page'] = 30;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];

		//获取展示的inter_id 并且处理数据
		$this->load->model('distribute/follower_report_model');
		$res = $this->follower_report_model->get_data_by_filter($avgs,$config['per_page'],$page);//var_dump($res);die;
		//$res = $this->my_sort($res,'new_sub_count',SORT_DESC);

		//是否为导出的
		$ext = $this->input->post('export');
		if($ext && $ext==1){
			$res = $this->follower_report_model->get_data_by_filter($avgs);//var_dump($res);die;
			$this->extdata($res);
			die;
		}
		//var_dump($res);die;
		//获取所有的公众号
		$public = $this->follower_report_model->get_all_wx_public($inter_id);

		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		// $config['suffix'] = $sub_fix;
		$config ['numbers_link_vars'] = array (
			'class' => 'number'
		);
		$config ['cur_tag_open']    = '<a class="number current" href="#">';
		$config ['cur_tag_close']   = '</a>';
		$config ['base_url']        = site_url ( "distribute/follower_report/detail/" . $avgs ['start_time'] . '_' . $avgs ['end_time'] );
		$avgs['count'] = 1;//计算数量
		$config ['total_rows']      = $this->follower_report_model->get_data_by_filter ($avgs);
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
		$view_params = array (
			'pagination' => $this->pagination->create_links (),
			'res'        => $res,
			'posts'			=>$avgs,
			'public'	=>$public,
			'select_hotel'=>isset($avgs ['hotel_public'])?$avgs ['hotel_public']:array(),
			'total'      => $config ['total_rows']
		);
		echo $this->_render_content ( $this->_load_view_file ( 'detail' ), $view_params, TRUE );

	}



	public function extdata($res = array()){
		if(empty($res)){
			return false;
		}
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, 'inter_id' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '酒店公众号名称' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '新增关注数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '取消关注数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '净增关注数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '分销关注数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '累计关注数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '新增关注数排名' );
		// Fetching the table data
		$row = 2;
		foreach ( $res as $k=>$item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['inter_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['public_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['new_sub_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['new_unsub_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['new_add_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['saler_nums'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['all_sub_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['new_sub_sort'] );
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
	

}