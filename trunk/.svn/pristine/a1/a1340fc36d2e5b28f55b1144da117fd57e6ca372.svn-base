<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Admin {

	protected $label_module= '预约';
	protected $label_controller= '预约列表';
	protected $label_action= '预约';
	
	function __construct(){
		parent::__construct();
	}
	

	public function index(){//var_dump($_SESSION);die;
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
        if($inter_id== FULL_ACCESS) $inter_id= '';
        $filter['inter_id'] = $inter_id;
        $filter['start_time'] = $this->input->post ( 'start_time' );//开始
        $filter['end_time'] = $this->input->post ( 'end_time' );//开始
        $filter['shop_id'] = $this->input->post ( 'shop_id' );//开始
        $filter['status'] = $this->input->post ( 'status' );//开始
        $filter['wd'] = $this->input->post ( 'wd' );//开始

        $keys = $this->uri->segment(4);
        $keys = explode('_', $keys);

        if(!empty($keys[0])){
            $filter['shop_id'] = urldecode($keys[0]);
        }
        if(!empty($keys[1])){
            $filter['start_time'] = $keys[1];
        }
        if(!empty($keys[2])){
            $filter['end_time'] = $keys[2];
        }
        if(!empty($keys[3])){
            $filter['status'] = $keys[3];
        }
        if(!empty($keys[4])){
            $filter['wd'] = $keys[4];
        }
        $this->load->library('pagination');
        $this->load->model('booking/booking_model');
        $config['per_page'] =15;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
        $res = $this->booking_model->get_booking_item($filter,$config['per_page'],$page);//var_dump($res);die;
        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $config['uri_segment']       = 5;

        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url("booking/booking/index/".$filter['shop_id'].'_'.$filter['start_time'].'_'.$filter['end_time'].'_'.$filter['status'].'_'.$filter['wd']);
        $config['total_rows']        = $this->booking_model->get_booking_item_count($filter);//var_dump($res);die;
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
        $status_arr = array('1'=>'预约中','2'=>'已用餐','3'=>'用户取消','4'=>'酒店取消');
        $shop_arr = array('1'=>'凤轩中餐厅','2'=>'天堂岛池畔餐厅');
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'posts'=>$filter,
            'res' =>$res,
            'inter_id' => $inter_id,
            'status_arr'=>$status_arr,
            'shop_arr' =>$shop_arr,
            'total'=>$config['total_rows'] ,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
	}
//ajax  改变状态 array('1'=>'预约中','2'=>'已用餐','3'=>'用户取消','4'=>'酒店取消');
    public function change_status(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $id = $this->input->post('id',true)?$this->input->post('id',true):'';
        $status = $this->input->post('status',true)?$this->input->post('status',true):'';
        if(!$id){
            echo 'data error';
            die;
        }
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
        $where['inter_id'] = $inter_id;

        $res = $this->db->update('booking_item',array('status'=>$status,'cancel_time'=>date('Y-m-d H:i:s')),array('inter_id'=>$inter_id,'status'=>1,'id'=>$id));

        if($res){
            $return['errcode'] = 0;
            $return['msg'] = 'ok';
            echo json_encode($return);
            die;
        }else{
            $return['msg'] = '更新失败';
            echo json_encode($return);
            die;
        }
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
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分组id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, 'inter_id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '酒店公众号名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '所属门店' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '场景名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '添加时间' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '成交订单数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '成交总额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '成交分组占比' );

        // Fetching the table data
        $row = 2;
        foreach ( $res as $k=>$item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['group_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['inter_id']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['inter_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['hotel_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['type_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, date('Y-m-d',$item['create_time']) );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['order_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['trade_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['rate'] );
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
