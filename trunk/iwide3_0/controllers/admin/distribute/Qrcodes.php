<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrcodes extends MY_Admin {

	protected $label_module= '二维码信息';
	protected $label_controller= '二维码列表';
	protected $label_action= '';
	private $inter_id;
	protected $status_arr = array ('1' => '申请中','2' => '正常','3' => '未通过','4' => '停止绩效');

	function __construct(){
		parent::__construct();
		$user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $user_profiler['inter_id'];
	}
	
	/**
	 * 分销员/区域二维码 
	 */
	public function index(){
		$this->_init_breadcrumb($this->label_action);
		$keys = $this->uri->segment(4);
		$hotel_id   = $this->input->post('hotel_id');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$cellphone  = $this->input->post('cellphone');
		$department = $this->input->post('department');
		$status     = $this->input->post('status');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_id = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_name = $keys[1];
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$cellphone = $keys[3];
		}
		if(!empty($keys[4])){
			$department = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$status = $keys[5];
		}
		$this->load->model('distribute/qrcodes_model');
		$admin_profile = $this->session->userdata('admin_profile');
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $admin_profile['inter_id'];
		$hids = array();
		if(!empty($admin_profile['entity_id'])){
			$hids = $filterH['hotel_id'] = explode(',', $admin_profile['entity_id']);
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		if(!empty($hotel_id) && (in_array($hotel_id, $hids) || empty($hids))){
			$hids = $hotel_id;
		}
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		
		$res = $this->qrcodes_model->get_salers_lite($this->inter_id,1,$saler_name,$saler_no,$cellphone,$hids,$department,$status,$config['per_page'],$config['cur_page']);
		$config['uri_segment']       = 5;
		// 		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("distribute/qrcodes/index/".$hotel_id.'_'.$saler_name.'_'.$saler_no.'_'.$cellphone.'_'.$department.'_'.$status);
		$config['total_rows']        = $this->qrcodes_model->get_salers_count($this->inter_id,1,$saler_name,$saler_no,$cellphone,$hids,$department,$status);
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
		$depts = $this->qrcodes_model->get_staff_depts($this->inter_id);
        $club_status = $this->qrcodes_model->club_status();
        $distribute_hidden = $this->qrcodes_model->distribute_hidden();
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'res'        => $res[0],
				'cls'        => $res[1],
				'hotel_id'   => $hotel_id,
				'saler_name' => $saler_name,
				'saler_no'   => $saler_no,
				'deptment'   => $department,
				'cellphone'  => $cellphone,
				'status'     => $status,
				'hotels'     => $hotels,
				'depts'      => $depts,
				'total'      => $config['total_rows'],
				'status_arr' => $this->status_arr,
                'club_status'=>$club_status,
                'distribute_hidden'=>$distribute_hidden
		);
		echo $this->_render_content($this->_load_view_file('qrcode_grid'), $view_params, TRUE);
	}
	public function edit(){
		$saler = $this->input->get('ids');
		if(empty($saler)){
			redirect('distribute/qrcodes/index');
		}else{
			$this->load->model('distribute/qrcodes_model');
			$saler = $this->qrcodes_model->_get_saler($this->inter_id,$saler);
			$this->load->model ( 'hotel/hotel_model' );
			$filterH ['inter_id'] = $this->inter_id;
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$view_params= array(
					'hotels' => $hotels,
					'saler'  => $saler,
					'status' => array ('1' => '申请中','2' => '正常','3' => '未通过','4' => '停止绩效')
			);
			echo $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		}
	}
	public function edit_post(){
		$this->load->library('form_validation');
		$post= $this->input->post();
		$base_rules= array(
				'name'=> array(
						'field' => 'name',
						'label' => '姓名',
						'rules' => 'trim',
				),
				'sex'=> array(
						'field' => 'sex',
						'label' => '性别',
						'rules' => 'trim',
				),
				'birthday'=> array(
						'field' => 'birthday',
						'label' => '生日',
						'rules' => 'trim',
				),
				'master_dept'=> array(
						'field' => 'master_dept',
						'label' => '部门',
						'rules' => 'trim',
				),
				'cellphone'=> array(
						'field' => 'cellphone',
						'label' => '手机',
						'rules' => 'trim',
				),
				'hotel_id'=> array(
						'field' => 'hotel_id',
						'label' => '酒店',
						'rules' => 'trim',
				)
		);
		$this->form_validation->set_rules($base_rules);
		$adminid= $this->session->get_admin_id();
		if ($this->form_validation->run() != FALSE) {
			$post['last_update_time']= date('Y-m-d H:i:s');
			$post['last_update_user']= $adminid;
		
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$this->session->get_admin_inter_id()) );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$post['hotel_name'] = $hotels[$post['hotel_id']];
			if(empty($post['qrcode_id'])){//防止qrcode_id 由null变为0
                unset($post['qrcode_id']);
            }
			$model= $this->_load_model('distribute/Staff_model');
			$result= $model->load($post['id'])->m_sets($post)->m_save($post);


            if($result==1){     //更新分销员社群客权限
                $inter_id = $this->session->get_admin_inter_id();
                if(isset($post['is_club'])){

                    if(isset($post['qrcode_id'])){
                        if(empty($post['qrcode_id'])||$post['qrcode_id']==0){
                            $post['qrcode_id']=1;
                        }
                        $this->load->model ( 'club/Club_model','Club_model' );
                        $staff_info=$this->Club_model->getOpenid($inter_id,$post['qrcode_id']);

                        if($staff_info){
                            $res=$this->Club_model->check_club($inter_id, $staff_info['openid'],$post['qrcode_id']);
                            $post_str=array(
                                'inter_id'=>$inter_id,
                                'qrcode_id'=>$staff_info['qrcode_id'],
                                'openid'=>$staff_info['openid'],
                                'name'=>$staff_info['name'],
                                'club_type'=>$staff_info['source']
                            );
                            $status=array(
                                '0'=>2,
                                '1'=>1
                            );
                            if($res){
                                $this->Club_model->update_club($post_str,array('status'=>$status[$post['is_club']]));

                            }else{
                                if($post['is_club']==1){

//                                        $is_grade=$this->Club_model->checkGradeStatus($post['inter_id']);    //根据现已开通的社群客判断该公众号的发展粉丝归属
//                                        if(!empty($is_grade)){
//                                            $post_str['is_grade']=$is_grade;
//                                        }else{
//                                            $post_str['is_grade']=0;
//                                        }
                                    $post_str['is_grade']=1;     //暂时
                                    $this->Club_model->add_club($post_str,$status[$post['is_club']]);

                                }
                            }
                        }
                    }
                }else{
                    if(isset($post['qrcode_id'])&&!empty($post['qrcode_id'])&&$post['qrcode_id']!=0){
                        $this->load->model ( 'club/Club_model','Club_model' );
                        $staff_info=$this->Club_model->getOpenid($inter_id,$post['qrcode_id']);

                        if($staff_info){
                            $res=$this->Club_model->check_club($inter_id, $staff_info['openid'],$post['qrcode_id']);
                            $post_str=array(
                                'inter_id'=>$inter_id,
                                'qrcode_id'=>$staff_info['qrcode_id'],
                                'openid'=>$staff_info['openid'],
                                'name'=>$staff_info['name']
                            );
                            if($res){
                                $this->Club_model->update_club($post_str,array('status'=>2));

                            }
                        }
                    }
                }

            }

			$message= ($result)?
			$this->session->put_success_msg('已保存数据！'):
			$this->session->put_notice_msg('此次数据修改失败！');
		
		
		
			$this->_log($model);
			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		
		} else{
			$this->load->model('distribute/qrcodes_model');
			$saler = $this->qrcodes_model->_get_saler($this->inter_id,$saler);
			$this->load->model ( 'hotel/hotel_model' );
			$filterH ['inter_id'] = $this->inter_id;
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$validat_obj= _get_validation_object();
			$message= $validat_obj->error_html();
			//页面没有发生跳转时用寄存器存储消息
			$this->session->put_error_msg($message, 'register');
			$view_params= array(
					'hotels' => $hotels,
					'saler'  => $saler,
					'status' => array ('1' => '申请中','2' => '正常','3' => '未通过','4' => '停止绩效')
			);
			echo $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		}
	}
	public function auth(){
		$this->load->model('distribute/qrcodes_model');
		$salers = $this->input->post('saler');
		$status = $this->input->post('status');
		$res = $this->qrcodes_model->auth_saler($this->inter_id,$salers,$status);
		echo json_encode(array('errmsg'=>$res));
	}
	
	public function batch_auth(){
		$this->load->model('distribute/qrcodes_model');
		$salers = $this->input->post('sid[]');
		$status = intval($this->input->post('status'));
		$success_count = 0;
		$fail_count = 0;
		foreach ($salers as $saler) {
			if($this->qrcodes_model->auth_saler($this->inter_id,$saler,$status)){
				$success_count++;
			}else{
				$fail_count++;
			}
		}
		echo json_encode(array('success_count'=>$success_count,'fail_count'=>$fail_count));
	}
	public function ext_qrcodes(){
		$keys = $this->uri->segment(4);
		$hotel_id   = $this->input->post('hotel_id');
		$saler_name = $this->input->post('saler_name');
		$saler_no   = $this->input->post('saler_no');
		$cellphone  = $this->input->post('cellphone');
		$department = $this->input->post('department');
		$status     = $this->input->post('status');
		$keys = explode('_', $keys);
		if(!empty($keys[0])){
			$hotel_id = $keys[0];
		}
		if(!empty($keys[1])){
			$saler_name = urldecode($keys[1]);
		}
		if(!empty($keys[2])){
			$saler_no = $keys[2];
		}
		if(!empty($keys[3])){
			$cellphone = $keys[3];
		}
		if(!empty($keys[4])){
			$department = urldecode($keys[4]);
		}
		if(!empty($keys[5])){
			$status = $keys[5];
		}
		$this->load->model('distribute/qrcodes_model');
		$admin_profile = $this->session->userdata('admin_profile');
		
		$res = $this->qrcodes_model->get_salers($this->inter_id,1,$saler_name,$saler_no,$cellphone,$hotel_id,$department,$status)->result();
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
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '申请时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '通过时间' );
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
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, isset($item->status_time) && $item->status_time != '0000-00-00 00:00:00' ? $item->status_time :'-' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, isset($item->audit_time) && $item->audit_time != '0000-00-00 00:00:00' ? $item->audit_time : '-' );
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
	public function saler_grades(){
		$this->_init_breadcrumb('员工信息');
		if(empty($this->input->get('sid')) && empty($this->uri->segment(4))){
			echo '<script type="text/javascript">alert("参数错误");window.location.href="'.site_url('distribute/qrcodes/index').'";</script>';
			die;
		}
		$saler = $this->input->get('sid');
		if(!empty($this->uri->segment(4))){
			$saler = $this->uri->segment(4);
		}
		$this->load->model('distribute/qrcodes_model');
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		
		$this->load->model ( 'hotel/hotel_model' );
		$filterH ['inter_id'] = $this->inter_id;
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$res = $this->qrcodes_model->get_saler_grades($this->inter_id,$saler,$config['per_page'],$config['cur_page'])->result();
		$config['uri_segment']       = 5;
		// $config['suffix']            = '?sid='.$saler;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = '#';
// 		$config['base_url']          = site_url("distribute/qrcodes/saler_grades/".$saler);
		$config['total_rows']        = $this->qrcodes_model->get_saler_grades_count($this->inter_id,$saler);
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
		$depts = $this->qrcodes_model->get_staff_depts($this->inter_id);
		$this->load->model('distribute/grades_model');
		$grade_types  = $this->grades_model->grade_types;
		$grade_status = $this->grades_model->grade_status;
		$order_status = $this->grades_model->order_status;
		$saler_infos  = $this->qrcodes_model->_get_saler($this->inter_id,$saler,'qrcode_id');
		$view_params  = array(
				'pagination'   => $this->pagination->create_links(),
				'res'          => $res,
				'depts'        => $depts,
				'valid_stat'   => $this->qrcodes_model->get_valid_status($saler_infos->status),
				'grade_types'  => $this->grades_model->grade_types,
				'grade_status' => $this->grades_model->grade_status,
				'order_status' => $this->grades_model->order_status,
				'saler_infos'  => $saler_infos,
				'grades_summ'  => $this->qrcodes_model->get_saler_grades_amounts($this->inter_id,$saler),
				'grades_status'=> $this->status_arr,
				'hotels'       => $hotels,
				'total'        => $config['total_rows']
		);
		echo $this->_render_content($this->_load_view_file('saler_grid'), $view_params, TRUE);
	}
	
	public function saler_grade_summ(){
		$this->load->model('distribute/qrcodes_model');
		$this->load->model('distribute/grades_model');
		$grade_types  = $this->grades_model->grade_types;
		$grade_status = $this->grades_model->grade_status;
		$order_status = $this->grades_model->order_status;
		$offset = 0;
		$length = 20;
		$gtable = '';
		$typ    = $this->input->post('sts');
		switch ($typ){
			case 'ALL':
				$typ = '';
				break;
			case 'DELIVERED':
				$typ = 2;
				break;
			case 'UNDELIVER':
				$typ = 1;
				break;
			case 'UNCONFIRM':
				$typ = array(4,6);
				break;
			case 'ROOMS':
				$typ    = '';
				$gtable = 'iwide_hotels_order';
				break;
			case 'MALL':
				$typ    = '';
				$gtable = array('iwide_soma_sales_order:default','iwide_soma_sales_order:killsec','iwide_soma_mooncake_order:default','iwide_shp_orders');
				break;
			case 'PACKAGE':
				$typ    = '';
				$gtable = 'iwide_soma_sales_order:groupon';
				break;
			case 'INVALID':
				$typ = 5;
				break;
		}
		if($this->input->post('length'))
			$length = intval($this->input->post('length'));
		if($this->input->post('start'))
			$offset = intval($this->input->post('start'));
		$datas = $this->qrcodes_model->get_saler_grades($this->inter_id,$this->input->get('sid'),$length,$offset,$typ,$gtable)->result();
		foreach ($datas as $item){
			$item->status       = isset($grade_status[$item->status]) ? $grade_status[$item->status] : '-';
			$item->order_status = isset($order_status[$item->grade_table][$item->order_status]) ? $order_status[$item->grade_table][$item->order_status] : '--';
			$item->grade_table  = isset($grade_types[$item->grade_table]) ? $grade_types[$item->grade_table] : '-';
			$item->grade_time   = empty($item->grade_time) ? '-' : $item->grade_time;
		}
		$rec_count = $this->qrcodes_model->get_saler_grades_count($this->inter_id,$this->input->get('sid'),$typ,$gtable);
		echo json_encode(array('data'=>$datas,'iTotalRecords'=>$rec_count,'iTotalDisplayRecords'=>$rec_count));
	}
	public function qjsave() {
		$this->load->model ( 'distribute/qrcodes_model' );
		echo $this->qrcodes_model->qrsave ( $this->input->post () ) ? 'success' : 'fail' ;
	}
	public function ex_gsalers(){
		ini_set ( 'memory_limit', '256M' );
		$this->load->model ( 'distribute/qrcodes_model' );
		$this->load->model ( 'distribute/grades_model' );
		$grade_types = $this->grades_model->grade_types;
		$grade_status = $this->grades_model->grade_status;
		$order_status = $this->grades_model->order_status;
		$gtable = '';
		$typ = $this->input->get ( 'sts' );
		switch ($typ) {
			case 'ALL' :
				$typ = '';
				break;
			case 'DELIVERED' :
				$typ = 2;
				break;
			case 'UNDELIVER' :
				$typ = 1;
				break;
			case 'UNCONFIRM' :
				$typ = array ( 4, 6 );
				break;
			case 'ROOMS' :
				$typ = '';
				$gtable = 'iwide_hotels_order';
				break;
			case 'MALL' :
				$typ = '';
				$gtable = array ('iwide_soma_sales_order:default','iwide_soma_sales_order:killsec','iwide_soma_mooncake_order:default','iwide_shp_orders');
				break;
			case 'PACKAGE' :
				$typ = '';
				$gtable = 'iwide_soma_sales_order:groupon';
				break;
			case 'INVALID' :
				$typ = 5;
				break;
		}
		$datas = $this->qrcodes_model->get_saler_grades ( $this->inter_id, $this->input->get ( 'sid' ), NULL, 0, $typ, $gtable )->result ();
		$data = mb_convert_encoding ("订单号,商品名,订单类型,交易粉丝,交易状态,交易金额,绩效金额,绩效状态,核定时间", 'GB18030', 'utf-8' ) . ",";
		$data .= "\n";
		foreach ( $datas as $item ) {
			$data .= is_numeric($item->order_id) ? "'" . $item->order_id . "," : $item->order_id . " ,";
			$data .= mb_convert_encoding ( $item->product, 'GB18030', 'utf-8' ) . ",";
			$data .= mb_convert_encoding ( isset ( $grade_types [$item->grade_table] ) ? $grade_types [$item->grade_table] : '-', 'GB18030', 'utf-8' ) . ",";
			$data .= mb_convert_encoding ( str_replace ( array ( "\r\n", "\n", "\r", "," ), "", str_replace ( "\"", "\"\"", $item->nickname ) ), 'GB18030', 'utf-8' ) . ",";
			$data .= mb_convert_encoding ( isset ( $order_status [$item->grade_table] [$item->order_status] ) ? $order_status [$item->grade_table] [$item->order_status] : '--', 'GB18030', 'utf-8' ) . ",";
			$data .= $item->order_amount . " ,";
			$data .= $item->grade_total . " ,";
			$data .= mb_convert_encoding (isset ( $grade_status [$item->status] ) ? $grade_status [$item->status] : '-', 'GB18030', 'utf-8' ) . ",";
			$data .= empty ( $item->grade_time ) ? '-' : $item->grade_time . " ,";
			$data .= "\n";
		}
		
		// 发送标题强制用户下载文件
		header ( 'Content-Type: text/csv' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
		header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0' );
		header ( 'Expires:0' );
		header ( 'Pragma:public' );
		echo $data;
	}
}