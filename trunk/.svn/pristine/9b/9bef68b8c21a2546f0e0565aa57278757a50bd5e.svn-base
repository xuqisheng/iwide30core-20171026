<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memberlist extends MY_Admin_Cprice
{
    protected $label_module = '会员中心';
    protected $label_controller = '会员资料';
	protected $label_action= '会员列表';
	
	protected function main_model_name()
	{
		return 'member/admin/grid/gridmember';
	}
	
	public function grid()
	{ 
		$this->load->model('member/member');
		$inter_id= $this->session->get_admin_inter_id();

		if($inter_id == FULL_ACCESS) {
			$filter= array();
		} else if($inter_id) {
			$filter= array(Member::TABLE_MEMBER.'.inter_id'=>$inter_id );
		} else {
			$filter= array(Member::TABLE_MEMBER.'.inter_id'=>'deny' );
		}
		/* 兼容grid变为ajax加载加这一段 */
		if(is_ajax_request())
		    //处理ajax请求，参数规格不一样
		    $get_filter= $this->input->post();
		else
		    $get_filter= $this->input->get('filter');
		
		if( !$get_filter) $get_filter= $this->input->get('filter');
		
		if(is_array($get_filter)) $filter= $get_filter+ $filter;

        if(!empty($_GET['searchAll'])){

            $con=$_GET['searchAll'];

                $get=addslashes($con);

                $condition=" AND (
                                t2.name like '%{$get}%'
                            OR  t1.mem_card_no like '%{$get}%'
                            OR  t2.membership_number  like '%{$get}%'
                          )
            ";




            $filter['sql']="SELECT
                                t1.mem_id,t1.inter_id,t2.name,t1.mem_card_no,t1.level,t2.membership_number,t1.bonus,t1.balance
                            FROM
                                `iwide_member` as t1,
                                `iwide_member_additional` as t2
                           WHERE
                                 t1.inter_id='{$inter_id}'
                           AND
                                 t1.mem_id = t2.mem_id".$condition;


            $this->m_grid ( $filter );

        }else{

            /* 兼容grid变为ajax加载加这一段 */
            $this->_grid($filter);
        }
	}
	
	public function edit()
	{
		$memid = $this->input->get('ids');
		
		if($memid) {
			$this->load->model('member/imember');
			$data['levels'] = $this->imember->getAllMemberLevels();
			$data['meminfo'] = $this->imember->getMemberDetailByMemId($memid);
			
			$html= $this->_render_content($this->_load_view_file('edit'), $data, false);
		
		    echo $html;
		} else {
			exit;
		}
	}
	
	public function owners(){
		$this->load->model('member/member');
		$inter_id= $this->session->get_admin_inter_id();
		
		$this->load->library('pagination');
		$config['per_page']          = 20;
		// PHP5.3 下报错
		//$page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4) - 1) * $config['per_page'];
		$segment4= $this->uri->segment(4);
		$page = empty($segment4) ? 0 : ($segment4 - 1) * $config['per_page'];
		
		$key = $this->input->get_post('key');
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$config['uri_segment']       = 4;
		// 		$config['suffix']            = $sub_fix;
		if($key){
			$config['suffix']            = '?key='.$key;
		}
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("member/memberlist/owners");
		$config['total_rows']        = $this->member->getUnAuthMembersCount($inter_id,$key);
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
				'res'        => $this->member->getUnAuthMembers($inter_id,$config['per_page'],$config['cur_page'],$key),
		);
		echo $this->_render_content($this->_load_view_file('owners'), $view_params, false);
	}
	public function auth_member(){
		$this->load->model('member/member');
		$this->load->model('member/imember');
		$rec_ids = $this->input->post('ids');
		$err_count = 0;
		$suc_count = 0;
		if(is_array($rec_ids)){
			foreach ($rec_ids as $item){
				$local_member = $this->member->getMemberById($item,'mem_id');
				$local_member_info = $this->member->getMemberInfoById($item,'mem_id');
				$res = $this->imember->getPmsMemberCard($local_member_info->telephone,'',1,$local_member_info->inter_id,0);
				if($this->__auth($res,$local_member,$local_member_info)){
					$suc_count += 1;
				}else{
					$err_count += 1;
				}
			}
		}else{
			$local_member = $this->member->getMemberById($rec_ids,'mem_id');
			$local_member_info = $this->member->getMemberInfoById($rec_ids,'mem_id');
			$res = $this->imember->getPmsMemberCard($local_member_info->telephone,'',1,$local_member_info->inter_id,0);
			if($this->__auth($res,$local_member,$local_member_info)){
				$suc_count += 1;
			}else{
				$err_count += 1;
			}
		}
		echo "成功通过:".$suc_count."人，失败:".$err_count."人"; 
		exit;
		
	
	}
	private function __auth($res,$local_member,$local_member_info){
		if ($res) {
			$res = $this->imember->upgradeLevel ( $res->Ic_num, $local_member_info->inter_id, 0 );
			
			if ($res->UpdateIcTypResult === true) {
				$this->db->where ( array ( 'mem_id' => $local_member->mem_id, 'is_login' => 1 ) );
				$this->db->limit ( 1 );
				$this->db->update ( 'member', array ( 'level' => 2 ) );
				$this->db->where ( array ( 'ma_id' => $local_member_info->ma_id ) );
				$this->db->limit ( 1 );
				$this->db->update ( 'member_additional', array ( 'audit' => 2 ) );
				return true;
			} else {
				return false;
			}
		} else {
			$id = $this->input->get ( 'id' );
			$data = array (
					'name'      => $local_member_info->name,
					'telephone' => $local_member_info->telephone,
					'password'  => $local_member_info->password,
					'level'     => 'R',
					'crtf_typ'  => $local_member_info->member_type,
					'crtf_num'  => $local_member_info->owner_no 
			);
			$result = $this->imember->registerMember ( $local_member->openid, $data, $local_member_info->inter_id, 0 );
			if ($result && $result ['code'] == 1) {
				$res = $this->imember->getPmsMemberCard ( $local_member_info->telephone, '', 1, $local_member_info->inter_id, 0 );
				$this->db->where ( array ( 'mem_id' => $local_member->mem_id, 'is_login' => 1 ) );
				$this->db->limit ( 1 );
				$this->db->update ( 'member', array ( 'level' => 2 ) );
				$this->db->where ( array ( 'ma_id' => $local_member_info->ma_id ) );
				$this->db->limit ( 1 );
				$this->db->update ( 'member_additional', array ( 'audit' => 2, 'membership_number' => $res->Ic_num ) );
				return true;
			} else {
				return false;
			}
		}
	}
	public function unbinding(){
		$memid = $this->input->get('ids');
		$inter_id= $this->session->get_admin_inter_id();
		if($memid) {
            $memid_array=explode(",",$memid);

            foreach($memid_array as $memid){

                $this->load->model('member/imember');
                $data['levels'] = $this->imember->getAllMemberLevels($inter_id,0);
                $data['meminfo'] = $this->imember->getMemberDetailByMemId($memid,$inter_id,0);

                $this->load->model('member/member');

                if($data['meminfo']->membership_number){

                    $updateParams = array(
                        'membership_number' => ''
                    );

                    $this->member->updateMemberInfoById($data['meminfo']->ma_id,$updateParams);

                }

                $updateParams = array(
                    'openid'           => $data['meminfo']->openid,
                    'level'            => 0,
                    'is_login'         => 0,
                    'is_active'        => 0,
                    'last_login_time'  => time()
                );
                $this->member->updateMemberByOpenId($updateParams);

            }
            redirect('member/memberlist/grid');
			exit;
		} else {
			exit;
		}
	}



    public function applyOwners()
    {
        $this->load->model('member/member');
        $inter_id= $this->session->get_admin_inter_id();

        if($inter_id == FULL_ACCESS) {
            $filter= array();
        } else if($inter_id) {
            $filter= array(Member::TABLE_MEMBER_INFO.'.inter_id'=>$inter_id );
        } else {
            $filter= array(Member::TABLE_MEMBER_INFO.'.inter_id'=>'deny' );
        }
        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
        $get_filter= $this->input->post();
        else
            $get_filter= $this->input->get('filter');

        if( !$get_filter) $get_filter= $this->input->get('filter');

        if(is_array($get_filter)) $filter= $get_filter+ $filter;

        if(!empty($_GET['searchAll'])){

            $con=$_GET['searchAll'];

            $get=addslashes($con);

            $condition=" AND (
                                t2.name like '%{$get}%'
                            OR  t1.mem_card_no like '%{$get}%'
                            OR  t2.membership_number  like '%{$get}%'
                          )
            ";




            $filter['sql']="SELECT
                                membership_number,name,telephone,member_type,owner_name,identity_card
                            FROM
                                `iwide_member_additional`
                           WHERE
                                 inter_id='{$inter_id}'
                           AND
                                 member_type !=0";


            $this->m_grid ( $filter );

        }else{

            /* 兼容grid变为ajax加载加这一段 */
            $this->m_grid($filter);
        }
    }

    /**
     * 速8定制(绑定统计)
     */
    public function bindstic(){
//        echo date('Y-m-t',strtotime('2016-11-09')); exit;
        $this->load->model('member/member');
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        $inter_id= $this->session->get_admin_inter_id();

        if($inter_id == FULL_ACCESS) {
            $filter= array();
        } else if($inter_id) {
            $filter= array('a.inter_id'=>$inter_id );
        } else {
            $filter= array('a.inter_id'=>'deny' );
        }
        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
        else
            $get_filter= $this->input->get('filter');

        if( !$get_filter) $get_filter= $this->input->get('filter');

        if(is_array($get_filter)) $filter= $get_filter+ $filter;
        $params= $this->input->get();
        if(is_array($filter) && count($filter)>0 ) $params= array_merge($params, $filter);
        $params['a.send_time']=array('>',0);
        $params['a.send_time']=array('<>','');
        $params['a.is_send']=2;
        $params['a.send_count']=array('>',0);
        if(is_ajax_request()){
            //处理ajax请求
            $result = $model->filter_bind_member($params );
            echo json_encode($result);
        }else{
            //HTML输出
            $this->label_action='会员绑定统计';
            $this->_init_breadcrumb($this->label_action);
            //base grid data..
            $result= $model->filter_bind_member($params);
            $fields_config= $model->_get_field_config('grid');
            $default_sort= array('field'=>'reg_time', 'sort'=>'desc');
            //获取一个月时间段的秒数
            $st = strtotime(date('Y-m-01 00:00:00'));
            $et = strtotime(date('Y-m-t 23:59:59'));
            $onemouth = $et-$st;
            $view_params= array(
                'module'=> $this,
                'model'=> $model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'onemouth'=>$onemouth,
                'reg_count'=>count($model->get_bind_member(array('inter_id'=>$inter_id))),
                'endtime'=>date('Y年m月d日 H:i')
        );
            $html = $this->_render_content($this->_load_view_file('bindstic'), $view_params, TRUE);
            echo $html;
        }
    }

    public function bind_export(){
        $get = $this->input->get();
        $_st = floatval($get['st']);
        $_et = floatval($get['et']);
        $om = $_et-$_st;
        //获取一个月时间段的秒数
        $st = strtotime(date('Y-m-01 00:00:00',$_st));
        $et = strtotime(date('Y-m-t 23:59:59',$_st));
        $onemouth = $et-$st;
        //--
        if($om > $onemouth){
            $this->session->put_error_msg('选择时间段不能大于一个月');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/bindstic'));exit;
        }

        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        $inter_id= $this->session->get_admin_inter_id();
        $param['inter_id']=$inter_id;
        $startime=date('Y-m-d H:i:s',$_st);
        $endtime=date('Y-m-d H:i:s',$_et);
        $list = $model->get_bind_member($param,$startime,$endtime);
        if(!empty($list)){
            $this->load->library ( 'PHPExcel' );
            $this->load->library ( 'PHPExcel/IOFactory' );
            $objPHPExcel = new PHPExcel ();
            $objPHPExcel->getProperties()->setTitle ( "export" )->setDescription ( "none" );
            $col = 1;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 0, $col, '时间' );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 1, $col, '会员卡号' );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 2, $col, '姓名' );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 3, $col, '手机号' );


            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            // Fetching the table data
            $row = 2;
            foreach ( $list as $item ) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 0, $row, isset($item['create_time']) ? date('Y-m-d',strtotime($item['create_time'])): '------' );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 1, $row, isset($item['membership_number']) ? $item['membership_number'] : '------');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 2, $row, isset($item['name']) ? $item['name'] : '------');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 3, $row, !empty($item['telephone'])?$item['telephone']:'------');
                $row ++;
            }
            $objPHPExcel->setActiveSheetIndex ( 0 );
            $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
            // 发送标题强制用户下载文件
            header ( 'Content-Type: application/vnd.ms-excel' );
            header ( 'Content-Disposition: attachment;filename="会员绑定统计清单' . date ( 'YmdHis' ) . '.xls"' );
            header ( 'Cache-Control: max-age=0' );
            $objWriter->save ( 'php://output' );
        }
    }
}