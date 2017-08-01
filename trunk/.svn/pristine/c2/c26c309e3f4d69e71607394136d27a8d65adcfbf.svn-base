<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Distribute_group extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '分销分组';
	protected $label_controller= '分销分组';
	protected $label_action= '';

	function __construct(){
		parent::__construct();
	}

	protected function main_model_name()
	{
		return 'distribute/distribute_group_model';
	}
	public function index(){
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		$params= $this->input->get();
		if(is_array($filter) && count($filter)>0 )
			$filter= array_merge($params, $filter);
		$limit = 30;
		$offset = 0;
		$this->load->model('distribute/distribute_group_model');
		$confs = array(array('name'=>'分组id'),array('name'=>'分组名称'),array('name'=>'有效期'),array('name'=>'组内成员'),array('name'=>'操作'));
		$filter['type'] = 1;//手动
		$res_sd = $this->distribute_group_model->get_distribute_group_info($filter,$limit,$offset);
		$filter['type'] = 2;//自动
		$res_zd = $this->distribute_group_model->get_distribute_group_info($filter,$limit,$offset);
		$view_params= array(
			'nav_confs'  => $confs,
			'res_sd'        => $res_sd->result_array(),
			'res_zd'        => $res_zd->result_array(),
			//'total'      => $config['total_rows'],
		);

		$html= $this->_render_content($this->_load_view_file('group_index'), $view_params, TRUE);
		echo $html;
	}
	public function group_detail()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		//get请求接收参数
		$params= $this->input->get();
		if(is_array($params) && count($params)>0 )
			$filter= array_merge($params, $filter);
		//post请求接收参数
		$post = $this->input->post();
		if(is_array($post)){
			$filter = array_merge($post,$filter);
		}//var_dump($filter);die;
		$keys = $this->uri->segment(4);
		if($keys){
			$filter['type']=$keys;
		}
		//export
		if(isset($filter['export']) && $filter['export']){
			$this->ext_data($filter);
			die;
		}
		//$this->grid();
		$this->load->library('pagination');
		$config['per_page']          = 50;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$this->load->model('distribute/distribute_group_model');
		$confs = array(array('name'=>'分组id'),
					   array('name'=>'分组名称'),
					   array('name'=>'创建时间'),
					   array('name'=>'有效期始'),
					   array('name'=>'有效期止'),
						array('name'=>'分组类型'),
						array('name'=>'核定来源'),
						array('name'=>'组内成员'),
						array('name'=>'分组状态'),
			array('name'=>'操作'),
		);
		if(!isset($filter['type'])){
			$filter['type'] = 1;//默认是手动
		}
		$res = $this->distribute_group_model->get_distribute_group_info($filter,$config['per_page'],$config['cur_page']);
		$config['uri_segment']       = 5;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url('distribute/distribute_group/group_detail/'.$filter['type']);
		$config['total_rows']        = $this->distribute_group_model->get_distribute_group_count($filter,$config['per_page'],$config['cur_page']);
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
			'type'  	 => $filter['type'],
			'posts'		 => $filter,
			'nav_confs'  => $confs,
			'res'        => $res->result_array(),
			'total'      => $config['total_rows'],
		);

		$html= $this->_render_content($this->_load_view_file('group_detail'), $view_params, TRUE);
		echo $html;
	}

	//add /
	public function group_add(){
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		$params= $this->input->get();
		if(is_array($params) && count($params)>0 )
			$filter= array_merge($params, $filter);
		//post请求接收参数
		$post = $this->input->post();
		if(is_array($post)){
			$filter = array_merge($post,$filter);
		}//var_dump($filter);die;
		if(empty($filter['type'])){
			$this->session->put_notice_msg('数据有误！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		}
		$submit = addslashes($this->input->post('submit'));
		$action = 'add';
		$id = $this->input->post('ids');
		if($id){
			$action = 'update';
		}
		$this->load->model('distribute/distribute_group_model');
		if($submit){//add
			/*if(empty($filter['group_name'])||empty($filter['begin_time'])||empty($filter['end_time'])||$filter['source']<0||$filter['check_type']<0||empty($filter['check_count'])){
				echo '输入的数据有误，请检查后从新输入！';die;
			}*/
            if($filter['type']==2 && (empty($filter['check_count']) || $filter['check_count'] < 0)){
                echo '核定数量数据有误，请检查后从新输入！';die;
                $this->_redirect(EA_const_url::inst()->get_url('*/*/group_detail?type='.$filter['type']));
                die;
            }
			$data['inter_id'] = $filter['inter_id'];
			$data['group_name'] = addslashes($filter['group_name']);
			$data['type'] = $filter['type'];
			$data['status'] = 1;
			$data['start_time'] = strtotime($filter['begin_time']);
			$data['end_time']   = strtotime($filter['end_time']);
			$data['check_date'] = isset($filter['check_date'])?$filter['check_date']:0;
			$data['source'] = isset($filter['source'])?$filter['source']:0;
			$data['check_type'] = isset($filter['check_type'])?$filter['check_type']:0;
			$data['check_count'] = isset($filter['check_count'])?$filter['check_count']:1;
			$hotel_arr = isset($filter['hotel'])?$filter['hotel']:array();
			if(!empty($hotel_arr)){
				$data['hotel_ids'] = implode(',',$hotel_arr);
			}else{//全不选
				$data['hotel_ids'] = '';
			}
			$department_arr = isset($filter['department'])?$filter['department']:array();
			if(!empty($department_arr)){
				$data['department_ids'] = implode(',',$department_arr);
			}else{//全不选
				$data['department_ids'] = '';
			}
			$sd_dept_staff = isset($filter['dept_staff'])?$filter['dept_staff']:array();
			if($filter['type']==1 && !empty($sd_dept_staff)){
				$data['member_count'] = count($sd_dept_staff);
				$data['sd_member_ids'] = implode(',',$sd_dept_staff);
			}elseif($filter['type']==1 && empty($sd_dept_staff)){
				$data['sd_member_ids'] = '';
			}
			if($action == 'update'){//update
				$res = $this->db->update('distribute_group',$data,array('group_id'=>$id));
				$message= ($res)?
					$this->session->put_success_msg('已修改数据！'):
					$this->session->put_notice_msg('此次数据修改失败！');
				//$log ='group_update_success'. date('Y-m-d H:i:s').' : '.microtime(TRUE).$filter['type']==1?'手动':'自动'.json_encode($data);
				//$this->distribute_group_model->write_log($log);
				//记录日志
				$this->load->model('distribute/welfare_model');
				$desc = ($filter['type']==1?'手动':'自动').'分组，更新数据'.($res?'成功':'失败');
				$remark = '更新数据为：'.json_encode($data);
				$log_type = 2;
				$this->welfare_model->_log_operation($desc,$remark,$log_type);
                //更新关联的奖励规则
                $this->db->update('distribute_group_reward',array('reward_check'=>$data['check_type'],'start_time'=>$data['start_time'],'end_time'=>$data['end_time'],'source'=>$data['source']),array('group_id'=>$id));
				$this->_redirect(EA_const_url::inst()->get_url('*/*/group_detail?type='.$filter['type']));
			}else{//add
                $data['create_time'] = time();
				//查询目前最大的group_id
				$max_group_id = $this->distribute_group_model->get_max_group_id($filter);
				$max_group_id = substr($max_group_id[0]['max_group_id'],2);
				$group_id = $max_group_id+1;
				$num = str_pad($group_id,6,'0',STR_PAD_LEFT);
				$group_id = ($filter['type']==1?'SD':'ZD').$num;
				$data['group_id'] = $group_id;
				$res = $this->db->insert('distribute_group',$data);
				$message= ($res)?
					$this->session->put_success_msg('已新增数据！'):
					$this->session->put_notice_msg('此次数据新增失败！');
				//记录日志
				$this->load->model('distribute/welfare_model');
				$desc = ($filter['type']==1?'手动':'自动').'分组，插入数据'.($res?'成功':'失败');
				$remark = '插入数据为：'.json_encode($data);
				$log_type = 2;
				$this->welfare_model->_log_operation($desc,$remark,$log_type);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/group_detail?type='.$filter['type']));
			}


		}
		//获取inter_id对应的酒店
		$hotel_res = $this->distribute_group_model->get_hotel_info_by_inter_id($inter_id);
		//获取酒店对应的部门
		$department = $this->distribute_group_model->get_department_by_inter_id($inter_id);

		$view_params= array(
			'type'  	 => $filter['type'],
			'posts'		 => $filter,
			'hotel'      => !empty($hotel_res->result_array())?$hotel_res->result_array():array(),
			'department' => !empty($department->result_array())?$department->result_array():array(),
		);
		if($filter['type']==1){
			//如果是手动的 需要查找对应部门的人员
			$dept_staff = $this->distribute_group_model->get_salers_info_list($inter_id);
			$view_params['dept_staff'] = empty($dept_staff)?array():$dept_staff;

		}
		$html= $this->_render_content($this->_load_view_file('group_add'), $view_params, TRUE);
		echo $html;
	}

	//edit page (save in add)
	public function group_edit(){
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		$group_id = $this->input->get('ids');
		$this->load->model('distribute/distribute_group_model');
		if($group_id){
			$group= $this->distribute_group_model->get($group_id,$inter_id);
			if(isset($group[0]) && !empty($group[0])){
				//获取inter_id对应的酒店
				$hotel_res = $this->distribute_group_model->get_hotel_info_by_inter_id($inter_id);
				//获取酒店对应的部门
				$department = $this->distribute_group_model->get_department_by_inter_id($inter_id);
				$group_hotel = $group[0]['hotel_ids'];
				if(!empty($group_hotel)){
					$group_hotel = explode(',',$group_hotel);
				}
				$group_department = $group[0]['department_ids'];
				if(!empty($group_department)){
					$group_department = explode(',',$group_department);
				}
				$view_params= array(
					'group_id'  	 => $group_id,
					'posts'		 => $group[0],
					'type'		 => $group[0]['type'],
					'hotel'      => !empty($hotel_res->result_array())?$hotel_res->result_array():array(),
					'department' => !empty($department->result_array())?$department->result_array():array(),
					'group_hotel'=>!empty($group_hotel)?$group_hotel:array(),
					'group_department'=>!empty($group_department)?$group_department:array(),
				);//var_dump($group_hotel);var_dump($group_department);die;
				if($group[0]['type']==1){
					//如果是手动的 需要查找对应部门的人员
					$dept_staff = $this->distribute_group_model->get_salers_info_list($inter_id);
					$view_params['dept_staff'] = empty($dept_staff)?array():$dept_staff;
					$sd_member_ids = $group[0]['sd_member_ids'];
					if(!empty($sd_member_ids)){
						$sd_member_ids = explode(',',$sd_member_ids);
					}
					$view_params['sd_member_ids'] = !empty($sd_member_ids)?$sd_member_ids:array();
				}
				$html= $this->_render_content($this->_load_view_file('group_add'), $view_params, TRUE);
				echo $html;
			}else{
					$this->session->put_notice_msg('数据有误！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));

			}
		}
	}

	//查看组内会员列表信息
	public function group_check(){
		$group_id = $this->input->get('ids');
		$inter_id= $this->session->get_admin_inter_id();
		$filter = array('group_id'=>$group_id);
		$keys = $this->uri->segment(4);
		if($keys){
			$filter['group_id']=$keys;
		}
		$this->load->model('distribute/distribute_group_model');
		$group_info =  $this->distribute_group_model->get($filter['group_id'],$inter_id);
		if(isset($group_info[0]) && !empty($group_info[0])){
			$group_info = $group_info[0];
		}else{
			$this->session->put_notice_msg('读取有误！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		}
		//查询消息
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$confs = array(array('name'=>'周期数'),
			array('name'=>'分销员'),
			array('name'=>'分销号'),
			array('name'=>'周期核定数量'),
			array('name'=>'达到条件时间'),
			array('name'=>'达到条件次数'),
			array('name'=>'周期收益总额'),
		);


		$res = $this->distribute_group_model->get_group_member_list($filter,$config['per_page'],$config['cur_page']);
		$config['uri_segment']       = 5;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url('distribute/distribute_group/group_check/'.$filter['group_id']);
		$config['total_rows']        = $this->distribute_group_model->get_group_member_count($filter,$config['per_page'],$config['cur_page']);
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

		//计算当前周期
		$week_num = $this->distribute_group_model->get_week_num_by_date($group_info['start_time'],$group_info['check_date']);
		/*if($group_info['check_date'] == 1){//周 计算出目前是第几周，并且算出周一和周日时间戳
			//算出start_time 那个周一时间戳
			$start_week_day = date('w',$group_info['start_time'])?date('w',$group_info['start_time']):7;
			$start_moday = $group_info['start_time'] - ($start_week_day-1)*86400;
			$week_time = time()-$start_moday;
			$week_num = ceil($week_time/(3600*24*7));//第几个周期
		}elseif($group_info['check_date'] == 2){//按月计算 算出当前是第几个月 并且算出1号和30号日期
			$start_mon = date('m',$group_info['start_time']);
			$week_time = time()-strtotime(date('Y-'.$start_mon.'-01 00:00:00',$group_info['start_time']));
			$days = date("t");
			$week_num = ceil($week_time/(3600*24*$days));//第几个周期
		}*/
		$view_params= array(
			'pagination' => $this->pagination->create_links(),
			'group_info' => $group_info,
			'group_id'	 => $filter['group_id'],
			'nav_confs'  => $confs,
			'week_num'	 => $week_num,
			'res'        => $res->result_array(),
			'total'      => $config['total_rows'],
		);
		$html= $this->_render_content($this->_load_view_file('group_member'), $view_params, TRUE);
		echo $html;
	}
	//导出excel
	public function ext_data($filter = array()){
		if(empty($filter)){
			echo 'data error!';
			die;
		}
		$per_page          = 50;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $per_page;
		$this->load->model('distribute/distribute_group_model');
		$res = $this->distribute_group_model->get_distribute_group_info($filter,$per_page,$page);
		$this->load->library ( 'PHPExcel' );
		$this->load->library ( 'PHPExcel/IOFactory' );
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
		$col = 0;
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分组id' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '分组名称' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '创建时间' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '有效期始' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '有效期止' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '分组类型' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '核定来源' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '组内成员' );
		$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '分组状态' );

		// Fetching the table data
		$row = 2;
		foreach ( $res->result_array() as $item ) {
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['group_id'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['group_name'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, date('Y-m-d',$item['create_time']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, date('Y-m-d',$item['start_time']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, date('Y-m-d',$item['end_time']));
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['type']==1?'手动':'自动' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['source']==1?'订房':'商城' );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['member_count'] );
			$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['status']==1?'有效':'无效' );
			$row ++;
		}

		$objPHPExcel->setActiveSheetIndex ( 0 );
		$objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );die;
	}
	//ajax delete
	public function ajax_delete(){//软删除

		$ids = $this->input->get('ids');
		if(empty($ids)){
			echo '数据有误！';
			die;
		}
		$this->load->model('distribute/distribute_group_model');
		$res = $this->db->update('distribute_group',array('is_delete'=>1),array('group_id'=>$ids));
		if($res){
			echo 'success';
		}else{
			echo 'update失败';
		}
	}

    /*分销分组奖励规则
     *
     * */
    public function reward(){
        $avgs ['reward_id'] = $this->input->post ( 'reward_id' );//酒店id
        $avgs ['source'] = $this->input->post ( 'source' );//
        $avgs ['status'] = $this->input->post ( 'status' );//分组状态
        $avgs ['start_time'] = $this->input->post ( 'start_time' );//开始
        $avgs ['end_time'] = $this->input->post ( 'end_time' );//结束
        $inter_id= $this->session->get_admin_inter_id();
        $avgs['inter_id'] = $inter_id;
       /* if($inter_id== FULL_ACCESS){
            //$filter= array();
        }else{
            $avgs ['inter_id'] = $inter_id;
        }*/
        $keys = $this->uri->segment ( 4 );
        $keys = explode ( '_', $keys );
        if (! empty ( $keys [0] )) {
            $avgs ['start_time'] = $keys [0];
        }
        if (! empty ( $keys [1] )) {
            $avgs ['end_time'] = $keys [1];
        }
        if (! empty ( $keys [2] )) {
            $avgs ['reward_id'] = $keys [2];
        }
        if (! empty ( $keys [3] )) {
            $avgs ['source'] = $keys [3];
        }
        if (! empty ( $keys [4] )) {
            $avgs ['status'] = $keys [4];
        }

        $this->load->library ( 'pagination' );
        $config ['per_page'] = 30;
        $page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];

        //获取展示的inter_id 并且处理数据
        $this->load->model('distribute/distribute_group_model');
        $res = $this->distribute_group_model->get_group_reward_list($avgs,$config['per_page'],$page);//var_dump($res);die;
        //$res = $this->my_sort($res,'new_sub_count',SORT_DESC);
        //是否为导出的
        /*$ext = $this->input->post('export');
        if($ext && $ext==1){
            $res = $this->distribute_group_model->get_group_reward_list($avgs);//var_dump($res);die;
            $this->extdata($res);
            die;
        }*/

        $config ['use_page_numbers'] = TRUE;
        $config ['cur_page'] = $page;
        $config ['uri_segment'] = 5;
        // $config['suffix'] = $sub_fix;
        $config ['numbers_link_vars'] = array (
            'class' => 'number'
        );
        $config ['cur_tag_open']    = '<a class="number current" href="#">';
        $config ['cur_tag_close']   = '</a>';
        $config ['base_url']        = site_url ( "distribute/distribute_group_model/reward/" . $avgs ['start_time'] . '_' . $avgs ['end_time'].'_'.$avgs ['reward_id'].'_'.$avgs ['source'].'_'.$avgs ['status'] );
        $avgs['count'] = 1;//计算数量
        $config ['total_rows']      = $this->distribute_group_model->get_group_reward_list_count ($avgs);
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
            'total'      => $config ['total_rows']
        );
        echo $this->_render_content ( $this->_load_view_file ( 'reward' ), $view_params, TRUE );
    }

    /*分组奖励规则增加
     * */
    public function reward_add(){
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS) exit('inter_id='.FULL_ACCESS.'不能添加');
        //post请求接收参数
        $post = $this->input->post();
        $submit = addslashes($this->input->post('submit'));
        $action = 'add';
        $id = $this->input->post('ids');
        if($id){
            $action = 'update';
        }
        $this->load->model('distribute/distribute_group_model');
        //获取分组
        $group = $this->distribute_group_model->get_distribute_group_info(array('inter_id'=>$inter_id,'status'=>1,'type'=>2));
        $tmp = $group->result_array();
        $group_list = array();
        if(!empty($tmp)){
            foreach($tmp as $key=>$values){
                $group_list[$values['group_id']] = $values;
            }
        }
        if($submit){
            if(empty($post['group_id'])){
                exit('分组信息有误！');
            }
            $data['reward_name'] = addslashes($post['reward_name']);
            $data['reward'] = $post['reward'];//奖励规则 ：元/人
            $data['limit_count'] = $post['limit_count'];//名额上限
           // $data['start_time'] = $post['start_time'];
          //  $data['end_time'] = $post['end_time'];
            $data['group_id'] = $post['group_id'];
            $data['status'] = $post['status'];
            $data['inter_id'] = $inter_id;
            $data['add_time'] = date('Y-m-d H:i');
            $data['reward_type'] = isset($post['reward_type'])?$post['reward_type']:1;
            //获取分组的信息
            $group_info = $this->distribute_group_model->get($post['group_id'],$inter_id);
            $data['reward_check'] = isset($group_info[0]['check_type'])?$group_info[0]['check_type']:0;
            $data['source'] = isset($group_info[0]['source'])?$group_info[0]['source']:0;
            $data['group_name'] = isset($group_info[0]['group_name'])?$group_info[0]['group_name']:'--';
             $data['start_time'] = isset($group_info[0]['start_time'])?date('Y-m-d',$group_info[0]['start_time']):'';
              $data['end_time'] = isset($group_info[0]['end_time'])?date('Y-m-d',$group_info[0]['end_time']):'';
            if($action == 'update'){
                $res = $this->db->update('distribute_group_reward',$data,array('reward_id'=>$id));
                $message= ($res)?
                    $this->session->put_success_msg('已修改数据！'):
                    $this->session->put_notice_msg('此次数据修改失败！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/reward'));
            }else{//add
                    //查询目前最大的id
                $max_reward_id = $this->distribute_group_model->get_max_reward_id();
                $max_reward_id = substr($max_reward_id,3);
                $reward_id = $max_reward_id+1;
                $reward_id = str_pad($reward_id,8,'0',STR_PAD_LEFT);
                $data['reward_id'] = 'JL1'.$reward_id;
                $res = $this->db->insert('distribute_group_reward',$data);
                $message= ($res)?
                    $this->session->put_success_msg('已新增数据！'):
                    $this->session->put_notice_msg('此次数据新增失败！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/reward'));
            }
        }else{
            $view_params= array(
                'group'		 => $group_list,
            );
            $html= $this->_render_content($this->_load_view_file('reward_edit'), $view_params, TRUE);
            echo $html;
        }
    }

    /*
     * 分组奖励编辑
     * */
    public function reward_edit(){
            $inter_id= $this->session->get_admin_inter_id();
            if($inter_id== FULL_ACCESS) $filter= array();
            else if($inter_id) $filter= array('inter_id'=>$inter_id );
            else $filter= array('inter_id'=>'deny' );
            $reward_id = $this->input->get('ids');
            $this->load->model('distribute/distribute_group_model');
            $this->db->where(array('reward_id'=>$reward_id,'inter_id'=>$inter_id));
            $reward = $this->db->get('distribute_group_reward')->result_array();
            $reward = isset($reward[0])&&!empty($reward[0])?$reward[0]:'';//var_dump($reward);die;
            if($reward){
                //获取分组
                $group = $this->distribute_group_model->get_distribute_group_info(array('inter_id'=>$inter_id,'status'=>1,'type'=>2));
                $tmp = $group->result_array();
                $group_list = array();
                if(!empty($tmp)){
                    foreach($tmp as $key=>$values){
                        $group_list[$values['group_id']] = $values;
                    }
                }
                    $view_params= array(
                        'posts'		 => $reward,
                        'group'     =>$group_list,
                        'reward_id' =>$reward_id
                    );//var_dump($group_hotel);var_dump($group_department);die;
                    $html= $this->_render_content($this->_load_view_file('reward_edit'), $view_params, TRUE);
                    echo $html;
                }else{
                    $this->session->put_notice_msg('数据有误！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/*/reward'));
                }
        }

    //查看组内会员列表信息
    public function reward_check(){
        $reward_id = $this->input->get('ids');
        $inter_id= $this->session->get_admin_inter_id();
        $filter = array('reward_id'=>$reward_id,'inter_id'=>$inter_id);
        $keys = $this->uri->segment(4);
        if($keys){
            $filter['reward_id']=$keys;
        }
        $this->load->model('distribute/distribute_group_model');
        $this->db->where(array('reward_id'=>$reward_id,'inter_id'=>$inter_id));
        $reward = $this->db->get('distribute_group_reward')->result_array();
        if(isset($reward[0]) && !empty($reward[0])){
            $reward = $reward[0];
        }else{
            $this->session->put_notice_msg('读取有误！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/reward'));
        }
        //查询消息
        $this->load->library('pagination');
        $config['per_page']          = 2;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;

        $res = $this->distribute_group_model->get_reward_member_list($filter,$config['per_page'],$config['cur_page']);
        $config['uri_segment']       = 5;
        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url('distribute/distribute_group/reward_check/'.$filter['reward_id']);
        $config['total_rows']        = $this->distribute_group_model->get_reward_member_count($filter,$config['per_page'],$config['cur_page']);
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

        //查询分组信息
        $this->db->where(array('inter_id'=>$inter_id,'group_id'=>$reward['group_id']));
        $group_info = $this->db->get('distribute_group')->result_array();
        $group_info = isset($group_info[0])?$group_info[0]:array();
        $view_params= array(
            'pagination' => $this->pagination->create_links(),
            'group_info' => $group_info,
            'reward_info'=>$reward,
            'reward_id'	 => $filter['reward_id'],
            'res'        => $res->result_array(),
            'total'      => $config['total_rows'],
        );
        $html= $this->_render_content($this->_load_view_file('reward_member'), $view_params, TRUE);
        echo $html;
    }

}
