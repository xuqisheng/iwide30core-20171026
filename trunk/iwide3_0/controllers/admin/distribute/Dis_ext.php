<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dis_ext extends MY_Admin{
	
	public function salers(){
		$keys = $this->uri->segment(4);
		$fkey  = $this->input->post('key');
		$btime = $this->input->post('begin_time');
		$etime = $this->input->post('end_time');
		$keys     = explode('_', $keys);
		if(!empty($keys[0])){
			$fkey = trim($keys[0]);
		}
		if(!empty($keys[1])){
			$btime = $keys[1];
		}
		if(!empty($keys[2])){
			$etime = $keys[2];
		}
		$this->load->model('distribute/distribute_ext_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->distribute_ext_model->get_salers($admin_profile['inter_id'],$btime,$etime,$fkey,'',$config['per_page'],$config['cur_page'])->result_array();
		$grades = $this->distribute_ext_model->get_grades_summary($admin_profile['inter_id'],'',array_column($res, 'fans_key'))->result();
		$grades = $this->distribute_ext_model->get_hash_map($grades,'saler');
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/dis_ext/salers/".$fkey.'_'.$btime.'_'.$etime);
		$config['total_rows']        = $this->distribute_ext_model->get_salers_count($admin_profile['inter_id'],$btime,$etime,$fkey,'');
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
				'grades'     => $grades,
				'posts'      => $this->input->post(),
				'total'      => $config['total_rows']
		);
		echo $this->_render_content($this->_load_view_file('distribute/fans_salers'), $view_params, TRUE);
	}
	
	public function grades(){
		$keys = $this->uri->segment(4);
		$fkey  = $this->input->post('key');
		$sbtime = $this->input->post('sbtime');
		$setime = $this->input->post('setime');
		$obtime = $this->input->post('obtime');
		$oetime = $this->input->post('oetime');
		$status = $this->input->post('sstatus');
		$ext_grades = $this->input->post('ext_grades');
		$keys     = explode('_', $keys);
		if(!empty($keys[0])){
			$fkey = trim($keys[0]);
		}
		if(!empty($keys[1])){
			$btime = $keys[1];
		}
		if(!empty($keys[2])){
			$etime = $keys[2];
		}
		if(!empty($keys[3])){
			$obtime = $keys[3];
		}
		if(!empty($keys[4])){
			$oetime = $keys[4];
		}
		if(!empty($keys[5])){
			$status = $keys[5];
		}
		$this->load->model('distribute/distribute_ext_model');
		$this->load->model('distribute/grades_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;

        //导出报表
        if (!empty($ext_grades) && $ext_grades == 1)
        {
            $res = $this->distribute_ext_model->get_grades($admin_profile['inter_id'],$obtime,$oetime,$fkey,$sbtime,$setime,$status)->result_array();
            $this->ext_grades($res,$this->grades_model->grade_status);
            exit;
        }

        $res = $this->distribute_ext_model->get_grades($admin_profile['inter_id'],$obtime,$oetime,$fkey,$sbtime,$setime,$status,$config['per_page'],$config['cur_page'])->result_array();

        $config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/dis_ext/grades/".$fkey.'_'.$sbtime.'_'.$setime.'_'.$obtime.'_'.$oetime.'_'.$status);
		$config['total_rows']        = $this->distribute_ext_model->get_grades_count($admin_profile['inter_id'],$obtime,$oetime,$fkey,$sbtime,$setime,$status);
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
				'posts'      => $this->input->post(),
				'gstatus'    => $this->grades_model->grade_status,
				'sstatus'    => [2=>'已发放',9=>'发放异常',0=>'未发放',1=>'未发放',3=>'未发放',4=>'未发放',5=>'未发放',6=>'未发放',7=>'未发放',8=>'未发放'],
				'total'      => $config['total_rows']
		);


		echo $this->_render_content($this->_load_view_file('distribute/fans_grades'), $view_params, TRUE);
	}

    /**
     * 导出报表
     */
    protected function ext_grades($res,$sstatus)
    {
        //$res = $data['res'];
       // $gstatus = $data['gstatus'];
        //$sstatus = $data['sstatus'];

        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '订单号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '购买时间' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '商品名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '商品数量' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '实付金额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '粉丝编号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '绩效金额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '发放时间' );
//        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '绩效状态' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '发放状态' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '备注' );
        // Fetching the table data
        $row = 2;
        foreach ( $res as $item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['order_id']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['order_time'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['product'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['counts'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['actually_paid'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['saler'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['grade_total'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, empty($item['send_time']) ? '-' : $item['send_time'] );
//            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, empty($gstatus[$item['status']]) ? '-' : $gstatus[$item['status']] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, empty($sstatus[$item['status']]) ? '-' : $sstatus[$item['status']] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, empty($item['remark']) ? '-' : $item['remark'] );
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