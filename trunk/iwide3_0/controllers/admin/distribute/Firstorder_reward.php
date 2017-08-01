<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Firstorder_reward extends MY_Admin {

	protected $label_module= '首单奖励';
	protected $label_controller= '首单奖励';
	protected $label_action= '列表';
	private $inter_id;

	protected $user_profiler;
	function __construct(){
		parent::__construct();
		$this->user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $this->user_profiler['inter_id'];
	}

    //列表页
    public function index(){//var_dump($_POST);die;
        $avgs ['inter_id'] = $this->input->post ( 'inter_id' );//inter_id
        $avgs ['status'] = $this->input->post ( 'status' );//status
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS){
            //$filter= array();
        }else{
            $avgs ['inter_id'] = $inter_id;
        }

        $keys = $this->uri->segment ( 4 );
        $keys = explode ( '_', $keys );
        if (! empty ( $keys [0] )) {
            $avgs ['status'] = $keys [0];
        }

        $this->load->library ( 'pagination' );
        $config ['per_page'] = 30;
        $page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];

        //获取展示的inter_id 并且处理数据
        $this->load->model('distribute/firstorder_reward_model');
        $res = $this->firstorder_reward_model->get_rule_info($avgs,$config['per_page'],$page);//var_dump($res);die;
        $result = array();
        if(!empty($res)){
            foreach($res as $key => $value){
                $value['s_id'] = 'SD'.str_pad($value['id'],7,'0',STR_PAD_LEFT);
                $result[] = $value;
            }
        }
        $config ['use_page_numbers'] = TRUE;
        $config ['cur_page'] = $page;
        $config ['uri_segment'] = 5;
        // $config['suffix'] = $sub_fix;
        $config ['numbers_link_vars'] = array (
            'class' => 'number'
        );
        $config ['cur_tag_open']    = '<a class="number current" href="#">';
        $config ['cur_tag_close']   = '</a>';
        $config ['base_url']        = site_url ( "distribute/firstorder_reward/index/".$avgs['status'] );
        $avgs['count'] = 1;//计算数量
        $config ['total_rows']      = $this->firstorder_reward_model->get_rule_info_count ($avgs);
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
        //规则类型
        $type = array(
            '1'=>'订房首单奖励',
            '2'=>'商城首单奖励',
            '3'=>'订房+商城首单奖励'
        );
        //核算单位
        $reward_type = array(
            '1'=>'按订单固定奖励',
        //    '2'=>'按间夜固定奖励',
        //    '3'=>'按订单实付金额百分比奖励'
        );
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'res'        => $result,
            'posts'			=>$avgs,
            'type'      =>$type,
            'reward_type'=>$reward_type,
            'total'      => $config ['total_rows']
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );

    }

    //新增规则
    public function add(){
        $inter_id= $this->session->get_admin_inter_id();
        $filter= array('inter_id'=>$inter_id );
        $post = $this->input->post();
        if(is_array($post)){
            $filter = array_merge($post,$filter);
        }//var_dump($filter);die;
        //如果是add
        $submit = addslashes($this->input->post('submit'));
        if($submit){//add
            //var_dump($filter);die;
            if(empty($filter['reward'])){
                $this->session->put_notice_msg('核算值数据有误！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            //每种类型规则只能存在一个 入库前先去查一次
            //根据id获取单条记录
            $this->load->model('distribute/firstorder_reward_model');
            $count = $this->firstorder_reward_model->check_type_exist($filter['type'],$filter['inter_id'],'add');
            if(!empty($count)){
                $this->session->put_notice_msg('类型已经存在，或者类型有冲突！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
                die;
            }
            $data = array();
            $data['inter_id'] = $filter['inter_id'];
            $data['type'] = isset($filter['type'])?$filter['type']:'';
            $data['reward_type'] = isset($filter['reward_type'])?$filter['reward_type']:'';
            $data['reward'] = isset($filter['reward'])?$filter['reward']:'';
            $data['add_time'] = date('Y-m-d H:i:s');
            $data['status'] = $filter['status'];
            $res = $this->db->insert('firstorder_reward',$data);
            $message= ($res)?
                $this->session->put_success_msg('已新增数据！'):
                $this->session->put_notice_msg('此次数据新增失败！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //规则类型
        $type = array(
            '1'=>'订房首单奖励',
            '2'=>'商城首单奖励',
            '3'=>'订房+商城首单奖励'
        );
        //核算单位
        $reward_type = array(
            '1'=>'按订单固定奖励',
         //   '2'=>'按间夜固定奖励',
         //   '3'=>'按订单实付金额百分比奖励'
        );
        $view_params = array(
            'type'  => $type,
            'reward_type'=>$reward_type
        );
        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }
	//规则详情
	public function edit(){//var_dump($_POST);die;
        $inter_id= $this->session->get_admin_inter_id();
        $filter= array('inter_id'=>$inter_id );
        $post = $this->input->post();
        if(is_array($post)){
            $filter = array_merge($post,$filter);
        }//var_dump($filter);die;
        $id = $this->input->get('ids');
        if(!$id){
            echo 'data error!';
            die;
        }
        $this->load->model('distribute/firstorder_reward_model');
        //如果是update
        $submit = addslashes($this->input->post('submit'));
        if($submit && $id){//add
            if(empty($filter['reward'])){
                $this->session->put_notice_msg('核算值数据有误！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            }
            //对比下update穿过来的type
            if($filter['status'] == 1){//状态变为有效才去检查
                //根据id获取单条记录
                $res = $this->firstorder_reward_model->get(array('id'=>$id,'inter_id'=>$inter_id));
                    $count = $this->firstorder_reward_model->check_type_exist($res[0]['type'],$filter['inter_id'],'');
                    if(!empty($count)){
                        $this->session->put_notice_msg('类型已经存在，或者类型有冲突！');
                        $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
                        //die;
                    }
            }
            $data = array();
            $data['inter_id'] = $filter['inter_id'];
           // $data['type'] = isset($filter['type'])?$filter['type']:'';
           // $data['reward_type'] = isset($filter['reward_type'])?$filter['reward_type']:'';
            $data['reward'] = isset($filter['reward'])?$filter['reward']:'';
           // $data['add_time'] = date('Y-m-d H:i:s');
            $data['status'] = $filter['status'];
            $res = $this->db->update('firstorder_reward',$data,array('id'=>$id));
            $message= ($res)?
                $this->session->put_success_msg('已更新数据！'):
                $this->session->put_notice_msg('此次数据更新失败！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //根据id获取单条记录
        $res = $this->firstorder_reward_model->get(array('id'=>$id,'inter_id'=>$inter_id));
        if(isset($res[0]) && !empty($res[0])){
            $res = $res[0];
        }
//规则类型
        $type = array(
            '1'=>'订房首单奖励',
            '2'=>'商城首单奖励',
            '3'=>'订房+商城首单奖励'
        );
        //核算单位
        $reward_type = array(
            '1'=>'按订单固定奖励',
        //    '2'=>'按间夜固定奖励',
         //   '3'=>'按订单实付金额百分比奖励'
        );
        $view_params = array(
            'id'	=> $id,
            'posts'	=> $res,
            'type'  => $type,
            'reward_type'=>$reward_type
        );
        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
	}

    //绩效明细
    public function check(){
        $avgs ['saler'] = $this->input->post ( 'saler' );//inter_id
        $avgs ['hotel_id'] = $this->input->post ( 'hotel_id' );//status
        $avgs ['start_time'] = $this->input->post ( 'start_time' );
        $avgs ['end_time'] = $this->input->post ( 'end_time' );
        $inter_id= $this->session->get_admin_inter_id();
        $id = $this->input->get('ids');
        if(empty($id)){
            $this->session->put_notice_msg('data error！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        }
        $avgs['id'] = $id;
        if($inter_id== FULL_ACCESS){
            //$filter= array();
        }else{
            $avgs ['inter_id'] = $inter_id;
        }

        $keys = $this->uri->segment ( 4 );
        $keys = explode ( '_', $keys );
        if (! empty ( $keys [0] )) {
            $avgs ['hotel_id'] = $keys [0];
        }
        if (! empty ( $keys [1] )) {
            $avgs ['saler'] = $keys [1];
        }
        if (! empty ( $keys [2] )) {
            $avgs ['start_time'] = $keys [2];
        }
        if (! empty ( $keys [3] )) {
            $avgs ['end_time'] = $keys [3];
        }

        $this->load->library ( 'pagination' );
        $config ['per_page'] = 30;
        $page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];

        //获取展示的inter_id 并且处理数据
        $this->load->model('distribute/firstorder_reward_model');
        if(!empty($this->input->post ( 'export' ))){
            $res = $this->firstorder_reward_model->get_reward_detail_info($avgs);//var_dump($res);die;
            $this->extdata($res);
            die;
        }
        $res = $this->firstorder_reward_model->get_reward_detail_info($avgs,$config['per_page'],$page);//var_dump($res);die;
        $config ['use_page_numbers'] = TRUE;
        $config ['cur_page'] = $page;
        $config ['uri_segment'] = 5;
        $config['suffix'] = '?ids='.$id;
        $config['first_url'] = base_url('distribute/firstorder_reward/check/').'?ids='.$id;
        $config ['numbers_link_vars'] = array (
            'class' => 'number'
        );
        $config ['cur_tag_open']    = '<a class="number current" href="#">';
        $config ['cur_tag_close']   = '</a>';
        $config ['base_url']        = site_url ( "distribute/firstorder_reward/check/".$avgs['hotel_id'].'_'.$avgs['saler'].'_'.$avgs['start_time'].'_'.$avgs['end_time'] );
        $avgs['count'] = 1;//计算数量
        $config ['total_rows']      = $this->firstorder_reward_model->get_reward_detail_info_count ($avgs);
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
        $filterH = array('inter_id'=>$inter_id);
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ( $filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'res'        => $res,
            'posts'			=>$avgs,
            'hotels'    =>$hotels,
            'ids'       =>$id,
            'total'      => $config ['total_rows']
        );
        echo $this->_render_content ( $this->_load_view_file ( 'check_detail' ), $view_params, TRUE );

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
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '订单号' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '商品名称' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '酒店' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '实付金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '绩效金额' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '核定时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '分销员' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '分销号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '所属酒店' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '发放时间' );
        // Fetching the table data
		$row = 2;
		foreach ( $res as $k=>$item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['order_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['product'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['hotel_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['order_amount'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['grade_total'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['grade_time'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['staff_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['saler'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['hotel_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['send_time'] );
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