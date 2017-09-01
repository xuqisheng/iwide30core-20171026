<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends MY_Admin {

	protected $label_module= '快乐付';
	protected $label_controller= '数据统计';
	protected $label_action= '数据统计';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'okpay/Okpay_model';
	}
	
	public function info() {
		$inter_id= $this->session->get_admin_inter_id();
	
		//HTML输出
		$this->label_action= '数据统计';
		$this->_init_breadcrumb($this->label_action);

		//-----------订单相关，用户使用情况
		$this->load->model('okpay/okpay_model');
		$paytimes_week_count	= $this->okpay_model->get_okpay_used_count_by_week(); //使用次数
		$users_week_count		= $this->okpay_model->get_okpay_used_user_count_by_week(); //使用人数
		
		
		//---------酒店快乐付使用的情况
		$this->load->model('okpay/okpay_type_model');
		$okpay_type_used_count = $this->okpay_type_model->get_okpay_used_hotel_count(); //累计使用快乐付场景的酒店数
		
		
		
		//-------酒店快乐付活动使用情况
		$this->load->model('okpay/Okpay_activities_model');
		$okpay_activities_used_count = $this->Okpay_activities_model->get_okpay_activities_used_hotel_count(); //累计使用折扣的酒店数

		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$view_params= array(
				'module'=> $this->module,
				'model'=> $model,
				'pay_times'=>$paytimes_week_count,
				'pay_users'=>$users_week_count,

				'pay_hotels'=>$okpay_type_used_count[0]->cnt,
				'pay_activities'=>$okpay_activities_used_count[0]->cnt,
		);
		$html= $this->_render_content($this->_load_view_file('info'), $view_params, TRUE);
		echo $html;
	}

    //快乐付数据分析
    public function analysis(){
        //$id = $this->input->post ( 'inter_id' );//inter_id
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
        if($inter_id== FULL_ACCESS ) $inter_id= '';

        $this->load->model('okpay/okpay_model');
        //昨天
        $data1 = $this->okpay_model->get_analysis_data(0,$inter_id);
        $data1['success_rate'] = empty($data1['all_order'])?0:$data1['success_order'] / $data1['all_order'];
        //var_dump($data1);die;
        //前天
        $data2 = $this->okpay_model->get_analysis_data(1,$inter_id);
        $rate = array();
        $rate['all_mem_rate'] = empty($data2['all_mem'])?0:($data1['all_mem'] - $data2['all_mem']) / $data2['all_mem'];
        $rate['all_order_rate'] = empty($data2['all_order'])?0:($data1['all_order'] - $data2['all_order']) / $data2['all_order'];
        $rate['trade_money_rate'] = empty($data2['trade_money'])?0:($data1['trade_money'] - $data2['trade_money']) / $data2['trade_money'];
        $rate['discount_order_rate'] = empty($data2['discount_order'])?0:($data1['discount_order'] - $data2['discount_order']) / $data2['discount_order'];
        $rate['discount_money_rate'] = empty($data2['discount_money'])?0:($data1['discount_money'] - $data2['discount_money']) / $data2['discount_money'];
        $rate['success_order_rate'] = empty($data2['success_order'])?0:($data1['success_order'] - $data2['success_order']) / $data2['success_order'];
        $rate['success_money_rate'] = empty($data2['success_money_rate'])?0:($data1['success_money'] - $data2['success_money']) / $data2['success_money'];
        $rate['cancel_order_rate'] = empty($data2['cancel_order'])?0:($data1['cancel_order'] - $data2['cancel_order']) / $data2['cancel_order'];
        $rate['cancel_money_rate'] = empty($data2['cancel_money'])?0:($data1['cancel_money'] - $data2['cancel_money']) / $data2['cancel_money'];
        $rate['avg_time_rate'] = empty($data2['avg_time'])?0:($data1['avg_time'] - $data2['avg_time']) / $data2['avg_time'];
        $rate['all_public_rate'] = empty($data2['all_public'])?0:($data1['all_public'] - $data2['all_public']) / $data2['all_public'];
        $rate['all_pay_public_rate'] = empty($data2['all_pay_public'])?0:($data1['all_pay_public'] - $data2['all_pay_public']) / $data2['all_pay_public'];
        foreach($data1 as $dk=>$dv){
            $data1[$dk] = empty($dv)?0:$dv;
        }
        foreach($rate as $rk=>$rv){
            $rate[$rk] = (empty($rv)?0:round($rv,4)*100) . '%';
        }
        $da = $dc = '[';
        if(!empty($data1['type_money'])){
            foreach($data1['type_money'] as $k=>$v){
                 $da .= "['" . $v['pay_type_desc'] . "'," . $v['sum_money'] . '],';
            }
        }
        if($data1['type_count']){
            foreach($data1['type_count'] as $key=>$value){
                $dc .= "['" . $value['pay_type_desc'] . "'," . $value['c'] . '],';
            }
        }
        $da = trim($da,',') .  ']';
        $dc = trim($dc,',')  . ']';//var_dump($da);var_dump($dc);die;
        $view_params= array(
            'res' => $data1,
            'rate' => $rate,
            'da'=>$da,
            'dc'=>$dc
        );
        $html= $this->_render_content($this->_load_view_file('report'), $view_params, TRUE);
        echo $html;
    }

    //ajax取数据
    public function ajax_get_data(){
        $date = $this->input->get('check_date');
        //$date = 30;
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
        if($inter_id== FULL_ACCESS) $inter_id= '';
        //查询
        $this->load->model('okpay/okpay_model');
        $res = $this->okpay_model->get_ajax_data($inter_id,$date);
        echo json_encode($res);die;

    }
    //ajax取数据 天 按小时
    public function ajax_get_data_in_day(){
        $date = $this->input->get('check_date');
        //$date = 30;
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
       // if($inter_id== FULL_ACCESS) $inter_id= '';
        //查询
        $this->load->model('okpay/okpay_model');
        $res = $this->okpay_model->get_ajax_data_in_day($inter_id,$date);
        echo json_encode($res);die;

    }

    //详情
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

        //获取展示的inter_id 并且处理数据
        $this->load->model('okpay/okpay_model');
        $res = $this->okpay_model->get_data_by_filter($avgs);//var_dump($res);die;
        if(!empty($res)){
            foreach($res as $k=>$v){
                $res[$k]['success_rate'] = empty($v['all_order'])?0:$v['success_order'] / $v['all_order'];
            }
        }
        //var_dump($res);die;
        //获取所有的公众号
        $this->load->model('distribute/follower_report_model');
        $public = $this->follower_report_model->get_all_wx_public();
        $pubs = array();
        if(!empty($public)){
            foreach($public as $k=>$v){
                $pubs[$v['inter_id']] = $v;
            }
        }
        if($inter_id != FULL_ACCESS){//不是超级管理员时 只拿对应的inter_id
            $public = array($pubs[$inter_id]);
        }

        //是否为导出的
        $ext = $this->input->post('export');
        if($ext && $ext==1){
            //$res = $this->okpay_model->get_data_by_filter($avgs);//var_dump($res);die;
            $this->extdata($res,$pubs);
            die;
        }



        $view_params = array (
            'res'        => $res,
            'pubs'       =>$pubs,
            'posts'			=>$avgs,
            'public'	=>$public,
            'select_hotel'=>isset($avgs ['hotel_public'])?$avgs ['hotel_public']:array(),
        );
        echo $this->_render_content ( $this->_load_view_file ( 'detail' ), $view_params, TRUE );

    }

    //展示商家显示数据 stgc 20161101
    public function saler_data(){
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
        $this->load->model('okpay/okpay_model');
        $data1 = $this->okpay_model->get_saler_data(0,$inter_id);
        $da = $dc = '[';
        if(!empty($data1['type_money'])){
            foreach($data1['type_money'] as $k=>$v){
                $da .= "['" . $v['pay_type_desc'] . "'," . $v['sum_money'] . '],';
            }
        }
        if($data1['type_count']){
            foreach($data1['type_count'] as $key=>$value){
                $dc .= "['" . $value['pay_type_desc'] . "'," . $value['c'] . '],';
            }
        }
        $da = trim($da,',') .  ']';
        $dc = trim($dc,',')  . ']';//var_dump($da);var_dump($dc);die;
        $view_params= array(
            'res' => $data1,
            'da'=>$da,
            'dc'=>$dc
        );
        $html= $this->_render_content($this->_load_view_file('salerreport'), $view_params, TRUE);
        echo $html;
    }



    public function extdata($res = array(),$inter_ids = array()){
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
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '场景数量' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '交易人数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '交易笔数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '交易金额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '优惠笔数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '优惠金额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '成功笔数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '实际收入' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '退款笔数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '退款金额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '交易成功率' );
        // Fetching the table data
        $row = 2;
        foreach ( $res as $k=>$item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['inter_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, isset($inter_ids[$item['inter_id']]['name'])?$inter_ids[$item['inter_id']]['name']:'--' );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['type'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['all_mem'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['all_order'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['trade_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['discount_order'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['discount_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['success_order'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['success_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['cancel_order'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $item['cancel_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, empty($item['all_order'])?0:number_format($item['success_order'] / $item['all_order'],4,'.','')*100 .'%' );
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
