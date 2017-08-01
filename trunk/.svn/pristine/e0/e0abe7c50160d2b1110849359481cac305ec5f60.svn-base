<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistic extends MY_Admin_Roomservice {

    protected $label_module= '数据统计';
    protected $label_controller= '数据统计';
    protected $label_action= '数据统计';
    function __construct(){
        parent::__construct();
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $this->username=$admin_profile['username'];
    }

    public function index(){
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        //get请求接收参数
        $filter['type'] = $this->input->get('type')?$this->input->get('type'):0;
        $filter['start_time'] = $this->input->get('start_time')?$this->input->get('start_time'):'';//下单时间
        $filter['end_time'] = $this->input->get('end_time')?$this->input->get('end_time'):'';
        if(empty($filter['start_time'])){
            $filter['start_time'] = date('Y-m-01');
        }
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }

        //酒店信息
        //获取公众号下的酒店
        $this->load->model ( 'wx/publics_model' );
        $publics = $this->publics_model->get_public_hash ();
        $publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
      //  var_dump($publics);die;
        //获取信息
        $this->load->model('roomservice/roomservice_statistic_model');
        $res = $this->roomservice_statistic_model->get_sum_statistic($filter);
        //是否要导出
        $ex =$this->input->get('export');
        if($ex && $ex==1){
            $this->extdata($res,$publics);
        }
        $view_params = array (
            'filter'=>$filter,
            'publics'=>$publics,
            'res' =>$res,
            'inter_id' => $this->inter_id,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
    }



    public function extdata($res = array(),$publics = array()){

        if(empty($res)){
            return false;
        }

        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, 'inter_id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '公众号名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '房间数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '交易人数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '交易订单数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '成功人数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '成功订单数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '实际收入' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '交易成功率' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '复购率' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '待接单订单' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, 1, '待配送订单' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, 1, '已配送订单' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, 1, '已完成订单' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, 1, '已取消订单' );

        // Fetching the table data
        $row = 2;
        foreach ( $res as $k=>$item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['inter_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, isset($publics[$item['inter_id']])?$publics[$item['inter_id']]:'--');
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['room_num'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['all_mem_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['all_orders_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['success_mem_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['success_order_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['income_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['success_order_rate'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['fu_success_order_rate'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['wait_accept'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 11, $row, $item['wait_send'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 12, $row, $item['sending'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 13, $row, $item['finish'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 14, $row, $item['cancel'] );

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
