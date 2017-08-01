<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Admin_Roomservice {

    protected $label_module= '打赏';
    protected $label_controller= '订单列表';
    protected $label_action= '订单列表';
    function __construct(){
        parent::__construct();
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $this->username=$admin_profile['username'];
    }

    public function index(){
        $filterH = array('inter_id'=>$this->inter_id);
        $filterH['status'] = 1;
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }

        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $view_params = array (
            'hotels'=>$hotels,
            'inter_id' => $this->inter_id,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
    }

    public function get_order_index_data(){
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        //get请求接收参数
        $filter['hotel_id'] = $this->input->get('hotel_id')?$this->input->get('hotel_id'):'';//酒店id
        $filter['start_time'] = $this->input->get('start_time')?$this->input->get('start_time'):'';//开始时间
        $filter['end_time'] = $this->input->get('end_time')?$this->input->get('end_time'):'';//结束时间
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';//关键词
        $filter['send_status'] = $this->input->get('send_status')?$this->input->get('send_status'):'';//发放状态
        $filter['per_page'] = $this->input->get('per_page')?$this->input->get('per_page'):30;//每页多少条
        $filter['cur_page'] = $this->input->get('cur_page')?$this->input->get('cur_page'):1;//当前第几页

        /*$per_page = 15;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));*/
        $filterH = array('inter_id'=>$this->inter_id);
        $filterH['status'] = 1;
        if(!empty($this->session->get_admin_hotels())){
            $filterH['in_hotel_id'] = explode(',',$this->session->get_admin_hotels());
            $filter['in_hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }

        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        if(empty($filter['start_time'])){
            $filter['start_time'] = date('Y-m-d',strtotime('-7 days'));
        }
        //获取订单信息
        $this->load->model('tips/tips_orders_model');
        $res = $this->tips_orders_model->get_page($filter,$filter['cur_page'],$filter['per_page']);
        $data = isset($res[1])?$res[1]:array();
        if(!empty($data)){
            foreach($data as $k=>$v){
                $data[$k]['hotel_name'] = '';
                if(empty($v['pay_time'])){
                    $data[$k]['pay_time'] = '';
                }
                if(isset($hotels[$v['hotel_id']])){
                    $data[$k]['hotel_name'] = $hotels[$v['hotel_id']];
                }
                if(empty($v['send_status'])){
                    $data[$k]['send_status'] = '';
                }elseif($v['send_status'] == 1){
                    $data[$k]['send_status'] = '未发放';
                }elseif($v['send_status'] == 2){
                    $data[$k]['send_status'] = '已发放';
                }
            }
        }
        $total_count = isset($res[0])?$res[0]:0;
        //总页数
        $total_page = ceil($total_count/$filter['per_page']);
        $return = array('errcode'=>0,'msg'=>'成功','data'=>array('total_count'=>$total_count,'total_page'=>$total_page,'cur_page'=>$filter['cur_page'],'result_data'=>$data));

        echo json_encode($return,JSON_UNESCAPED_UNICODE);
        die;
    }

    public function extdata($res = array()){
        $filter['inter_id'] = $this->inter_id;
        //get请求接收参数
        $filter['hotel_id'] = $this->input->get('hotel_id')?$this->input->get('hotel_id'):'';//酒店id
        $filter['start_time'] = $this->input->get('start_time')?$this->input->get('start_time'):'';//开始时间
        $filter['end_time'] = $this->input->get('end_time')?$this->input->get('end_time'):'';//结束时间
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';//关键词
        $filter['send_status'] = $this->input->get('send_status')?$this->input->get('send_status'):'';//发放状态
        if(empty($filter['start_time'])){
            $filter['start_time'] = '2017-04-01';
        }
        $this->load->model('tips/tips_orders_model');
        $res = $this->tips_orders_model->get_list($filter);
        if(empty($res)){
            return false;
        }
        //获取公众号下的酒店
        $filterH = array('inter_id'=>$this->inter_id);
        $filterH['status'] = 1;
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        if(!empty($res)){
            foreach($res as $k=>$v){
                $res[$k]['hotel_name'] = '';
                if(empty($v['pay_time'])){
                    $res[$k]['pay_time'] = '';
                }
                if(isset($hotels[$v['hotel_id']])){
                    $res[$k]['hotel_name'] = $hotels[$v['hotel_id']];
                }
                if(empty($v['send_status'])){
                    $res[$k]['send_status'] = '';
                }elseif($v['send_status'] == 1){
                    $res[$k]['send_status'] = '未发放';
                }elseif($v['send_status'] == 2){
                    $res[$k]['send_status'] = '已发放';
                }
            }
        }
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, 'id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '打赏用户' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '打赏时间' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '员工姓名' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '分销号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '所属门店' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '打赏金额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '用户评分' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '发放状态' );

        // Fetching the table data
        $row = 2;
        foreach ( $res as $k=>$item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['order_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['pay_name']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['pay_time'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['saler_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['saler'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, isset($hotels[$item['hotel_id']])?$hotels[$item['hotel_id']]:'--' );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['pay_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['score'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['status']==2?'已发放':'未发放' );
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
