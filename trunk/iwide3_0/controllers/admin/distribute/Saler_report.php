<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saler_report extends MY_Admin {

	protected $label_module= '分销数据分析';
	protected $label_controller= '分销数据分析';
	protected $label_action= '列表';
	private $inter_id;
	private $hotel_id;

	protected $user_profiler;
	function __construct(){
		parent::__construct();
		$this->user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $this->user_profiler['inter_id'];
		$this->hotel_id = $this->user_profiler['entity_id'];
	}
	
	public function index(){

		/*$admin_profile = $this->session->userdata ( 'admin_profile' );
		$filter = array();
		$inter_id = $admin_profile['inter_id'];
		$inter_id = 'a429262687';
		$filter['inter_id'] = $inter_id;*/
		//if($inter_id== FULL_ACCESS) $filter['inter_id']= '';
		//$this->load->model('distribute/follower_report_model');
		//根据inter_id 获取信息
		//$info = $this->follower_report_model->get_distribure_analys_info($filter);die;

		$avgs ['inter_id'] = $this->input->post ( 'inter_id' );//inter_id
		$avgs ['start_time'] = $this->input->post ( 'start_time' );//开始
		$avgs ['end_time'] = $this->input->post ( 'end_time' );//结束
		$avgs ['saler_id'] = $this->input->post ( 'saler_id' );//结束
		$avgs ['saler_name'] = $this->input->post ( 'saler_name' );//结束
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS){
			//$filter= array();
			if(empty($avgs['inter_id'])){
				$avgs['inter_id'] = FULL_ACCESS;
			}
		}else{
			$avgs ['inter_id'] = $inter_id;
		}
		//$avgs ['inter_id']= 'a429262687';
		$keys = $this->uri->segment ( 4 );
		$keys = explode ( '_', $keys );
		if (! empty ( $keys [0] )) {
			$avgs ['start_time'] = $keys [0];
		}
		if (! empty ( $keys [1] )) {
			$avgs ['end_time'] = $keys [1];
		}
		if (! empty ( $keys [2] )) {
			$avgs ['inter_id'] = $keys [2];
		}
		if (! empty ( $keys [3] )) {
			$avgs ['saler_id'] = $keys [3];
		}
		if (! empty ( $keys [4] )) {
			$avgs ['saler_name'] = $keys [4];
		}

		if(empty($avgs['start_time'])){
			$avgs['start_time'] = date('Y-m-d',strtotime('-1 month'));//一个月前
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date('Y-m-d',strtotime('-1 days'));//昨天
		}
		$this->load->model('distribute/follower_report_model');

		$this->load->library ( 'pagination' );
		$config ['per_page'] = 30;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];

		//是否为导出的
		$ext = $this->input->post('export');
		if($ext && $ext==1){
			$res = $this->follower_report_model->get_distribure_analys_info($avgs);//var_dump($res);die;
			$this->extdata($res,$avgs['inter_id']);
			die;
		}

		//获取展示的inter_id 并且处理数据
		$res = $this->follower_report_model->get_distribure_analys_info($avgs,$config['per_page'],$page);//var_dump
		//var_dump($res);die;
		//获取所有的公众号
		//$public = $this->follower_report_model->get_all_wx_public($inter_id);

		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		// $config['suffix'] = $sub_fix;
		$config ['numbers_link_vars'] = array (
			'class' => 'number'
		);
		$config ['cur_tag_open']    = '<a class="number current" href="#">';
		$config ['cur_tag_close']   = '</a>';
		$config ['base_url']        = site_url ( "distribute/saler_report/index/" . $avgs ['start_time'] . '_' . $avgs
			['end_time'] .'_'.$avgs ['inter_id'].'_'.$avgs ['saler_id'].'_'.$avgs ['saler_name']);
		$avgs['count'] = 1;//计算数量
		$config ['total_rows']      = $this->follower_report_model->get_distribure_analys_count($avgs);
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
		$this->load->model('wx/publics_model');
		$view_params = array (
			'pagination' => $this->pagination->create_links (),
			'res'        => $res,
			'posts'			=>$avgs,
			'inter_id'	=> $this->publics_model->get_public_by_id($avgs['inter_id']),
			'total'      => $config ['total_rows']
		);
		echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );


	}

	//计算发展粉丝人数、粉丝交易人数
	public function fans_data(){//var_dump($_POST);die;
		$avgs ['inter_id'] = $this->input->post ( 'inter_id' );//inter_id
		$avgs ['start_time'] = $this->input->post ( 'start_time' );//开始
		$avgs ['end_time'] = $this->input->post ( 'end_time' );//结束
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS){
			//$filter= array();
			if(empty($avgs['inter_id'])){
				$avgs['inter_id'] = FULL_ACCESS;
			}
		}else{
			$avgs ['inter_id'] = $inter_id;
		}
		//$avgs ['inter_id']= 'a421641095';
		$keys = $this->uri->segment ( 4 );
		$keys = explode ( '_', $keys );
		if (! empty ( $keys [0] )) {
			$avgs ['start_time'] = $keys [0];
		}
		if (! empty ( $keys [1] )) {
			$avgs ['end_time'] = $keys [1];
		}
		if(empty($avgs['start_time'])){
			$avgs['start_time'] = date('Y-m-d',strtotime('-1 days'));//一天前
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date('Y-m-d',strtotime('-1 days'));//昨天
		}

		//获取展示的inter_id 并且处理数据
		$this->load->model('distribute/follower_report_model');
		$res = $this->follower_report_model->get_saler_fans_data($avgs);//var_dump($res);die;
		//$res = $this->my_sort($res,'new_sub_count',SORT_DESC);
//var_dump(json_encode($res));die;

		$view_params = array (
			'res'        => json_encode($res),
			'posts'			=>$avgs,
		);
		echo $this->_render_content ( $this->_load_view_file ( 'fans_data' ), $view_params, TRUE );

	}

	//转化情况
	public  function transform(){
		$avgs ['inter_id'] = $this->input->post ( 'inter_id' );//inter_id
		$avgs ['start_time'] = $this->input->post ( 'start_time' );//开始
		$avgs ['end_time'] = $this->input->post ( 'end_time' );//结束
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS){
			//$filter= array();
			if(empty($avgs['inter_id'])){
				$avgs['inter_id'] = FULL_ACCESS;
			}
		}else{
			$avgs ['inter_id'] = $inter_id;
		}
		if(empty($avgs['start_time'])){
			$avgs['start_time'] = date('Y-m-d',strtotime('-1 month'));//一月前
		}
		if(empty($avgs['end_time'])){
			$avgs['end_time'] = date('Y-m-d',strtotime('-1 days'));//昨天
		}
//$avgs ['inter_id']= 'a421641095';
		//获取展示的inter_id 并且处理数据
		$this->load->model('distribute/follower_report_model');
		$res = $this->follower_report_model->get_transform_data($avgs);//echo json_encode($res);
/*$res = array('dev_fans_count'=>22980,'sale_fans_count'=>3596,'fans_from_saler'=>18883,'sale_fans_from_saler'=>5240,'sale_fans_from_sence'=>359,'fans_from_sence'=>4074,'time'=>array('one'=>8,'two'=>2,'three'=>162,'four'=>736,'five'=>234,'six'=>648,'seven'=>178,'sum'=>1968));*/
		$view_params = array (
			'res'        => json_encode($res),
            'dev_fans_count' => $res['dev_fans_count'],
            'sale_fans_count' => $res['sale_fans_count'],
            'fans_from_saler'=> $res['fans_from_saler'],
            'sale_fans_from_saler' => $res['sale_fans_from_saler'],
            'fans_from_sence' => $res['fans_from_sence'],
            'sale_fans_from_sence' => $res['sale_fans_from_sence'],
			'posts'			=>$avgs,
		);
		echo $this->_render_content ( $this->_load_view_file ( 'transform' ), $view_params, TRUE );
	}

    //画像
    public function saler_picture(){
        $avgs ['inter_id'] = $this->input->post ( 'inter_id' );//inter_id
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS){
            //$filter= array();
            if(empty($avgs['inter_id'])){
                $avgs['inter_id'] = FULL_ACCESS;
            }
        }else{
            $avgs ['inter_id'] = $inter_id;
        }
//$avgs ['inter_id']= 'a449664652';
        //获取展示的inter_id 并且处理数据
        $this->load->model('distribute/follower_report_model');
        $res = $this->follower_report_model->get_saler_picture($avgs);//echo json_encode($res);
        //性别比例
       $sex = array(
           'man'=>isset($res['sex']->man)?$res['sex']->man:0,
           'women'=>isset($res['sex']->women)?$res['sex']->women:0,
           'unknow'=>isset($res['sex']->unknow)?$res['sex']->unknow:0,
       );
        //新分销员占比
        $new_saler = array(
            'all_saler'=>$res['new_saler'][0]['all_saler'],
            'new_saler'=>empty($res['new_saler'][0]['new_saler'])?0:$res['new_saler'][0]['new_saler']
        );
        //性别占比
        $sex_rate = array(
            'man'=>isset($res['sex_rate']->man)?$res['sex_rate']->man:0,
            'women'=>isset($res['sex_rate']->women)?$res['sex_rate']->women:0,
        );
        //年龄分担
        $age_data = $res['age_data'];

        $view_params = array (
            'res'        => json_encode($res),
           'sex' => $sex,
            'new_saler'=>$new_saler,
            'sex_rate'=>$sex_rate,
            'age_data'=>$age_data,
            'posts'			=>$avgs,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'saler_picture' ), $view_params, TRUE );
    }



	public function extdata($res = array(),$inter_id = ''){
		if(empty($res)){
			return false;
		}
		$this->load->model('wx/publics_model');
		$inter_id = $this->publics_model->get_public_by_id($inter_id);
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, 'inter_id' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '酒店公众号名称' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '分销员' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '分销号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '所属酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '粉丝数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '间夜数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '间夜绩效' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '商品数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '商品绩效' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '会员数' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '会员绩效' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '粉丝转化率' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, 1, '平均转化时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, 1, '绩效总额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, 1, '绩效排名' );

		// Fetching the table data
		$row = 2;
		foreach ( $res as $k=>$item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $inter_id['inter_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $inter_id['name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['qrcode_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['hotel_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, isset($item['fans_count'])
				?$item['fans_count']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, isset($item['room_night'])
				?$item['room_night']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, isset($item['room_grade'])
				?$item['room_grade']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, isset($item['product_count'])
				?$item['product_count']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, isset($item['product_grade'])
				?$item['product_grade']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, isset($item['mem_count'])
				?$item['mem_count']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, isset($item['GRADE_MEM'])
				?$item['GRADE_MEM']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, ((isset($item['success_fans']) &&
				isset($item['fans_count']) && !empty($item['fans_count']))
				?number_format($item['success_fans'] / $item['fans_count'],2,'.','') *100:0 ).'%');
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, $row, ((isset($item['sum_time']) && isset
				($item['success_fans']) && !empty($item['success_fans']))
				?round($item['sum_time'] / $item['success_fans']):0).'min' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, $row, isset($item['GRADE_TOTAL'])
				?$item['GRADE_TOTAL']:0 );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 15, $row, isset($item['rank'])?$item['rank']:0 );
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