<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffs extends MY_Admin_Cprice {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '分销人员';
	protected $label_controller= '分销人员';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'distribute/staff_model';
	}
	function index(){
// 		$this->grid();
//@author lGh 增加酒店筛选 2016-3-30 10:35:39
		$_POST ['inter_id'] = $this->session->get_admin_inter_id ();
        $inter_id=$this->session->get_admin_inter_id ();
		$entity_id = $this->session->get_admin_hotels ();
		$hotel_ids = explode ( ',', $entity_id );
		$hotel_id = $this->input->get ( 'h' );
		
		$this->load->model ( 'hotel/hotel_model' );
		$data = array (
				'hotel_id' => $hotel_id
		);
		if (! empty ( $entity_id )) {
			$data ['hotels'] = $this->hotel_model->get_hotel_by_ids ( $_POST ['inter_id'], $entity_id );
			if (! empty ( $hotel_id ) && in_array ( $hotel_id, $hotel_ids )) {
				$_POST ['hotel_id'] = $hotel_id;
			} else {
				$_POST ['hotel_id'] = $hotel_ids;
			}
		} else {
			$data ['hotels'] = $this->hotel_model->get_all_hotels ( $_POST ['inter_id'] );
			if (! empty ( $hotel_id )) {
				$_POST ['hotel_id'] = $hotel_id;
			}
		}

        if(!empty($_GET['searchAll'])){

            $con=$_GET['searchAll'];

            $get=$con;

            $int_con=floatval($con);

            if(strlen($con)==strlen($int_con)){

                $type='int';

            }else{

                $type='string';
            }

            if($type=='int'){

                $condition=" AND (
                            employee_id = ".intval($con)."
                            OR qrcode_id = ".intval($con)."
                            OR cellphone = '{$get}'
                          )
            ";

            }else{

                $get=addslashes($get);

                $condition=" AND (
                            name = '{$get}'
                            OR position like '%{$get}%'
                            OR business like '%{$get}%'
                            OR hotel_name like '%{$get}%'
                          )
            ";


            }


            if(!empty($entity_id)){

                $condition .="AND hotel_id in ({$entity_id})";

            }

            $filter['sql']="SELECT
                            id,name,sex,position,business,employee_id,master_dept,cellphone,hotel_name,inter_id,status,qrcode_id,`lock`,is_distributed,is_club
                        FROM
                            `iwide_hotel_staff`
                        WHERE
                            inter_id = '{$inter_id}'".$condition;


            $this->m_grid ( $filter );

        }else{

            $this->m_grid ( $_POST, $data );

        }

	}
	
	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
		if(is_ajax_request())
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
	    $this->_grid($filter);
	}

    public function ext_staffs(){
        $inter_id= $this->session->get_admin_inter_id();
        $this->load->model('distribute/staff_model');
        $res  = $this->staff_model->find_all(array('inter_id'=>$inter_id));
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '编号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, '姓名' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '生日' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '部门' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '员工号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '电话' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '酒店' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '分销号' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '身份证' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, 1, '申请时间' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, 1, '审核时间' );
        // Fetching the table data
        $row = 2;
        foreach ( $res as $item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['birthday']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['master_dept']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['employee_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, $item['cellphone'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['hotel_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['qrcode_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['id_card'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 9, $row, $item['status_time'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 10, $row, $item['audit_time'] );
            $row ++;
        }

        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }


	public function edit()
	{
		$this->label_action= '员工管理';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$id= intval($this->input->get('ids'));
		if($id){
			//for edit page.
			$model= $model->load($id);
			$fields_config= $model->get_field_config('form');
			$detail_field = array();
			if( count($detail_field)>0 ){
				$detail_field= $detail_field[0]['attr_value'];
			} else {
				$detail_field= '';
			}
			
		} else {
			//for add page.
			$model= $model->load($id);
			if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');
			$detail_field= '';
		}
	
	
		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> FALSE,
				'detail_field'=> $detail_field,
// 				'gallery'=> $gallery,
		);
	
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	public function edit_post()
	{
		$this->label_action= '信息维护';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
	
		$this->load->library('form_validation');
		$post= $this->input->post();
	
		$labels= $model->attribute_labels();
		$base_rules= array(
				'name'=> array(
						'field' => 'name',
						'label' => $labels['name'],
						'rules' => 'trim',
				),
				'sex'=> array(
						'field' => 'sex',
						'label' => $labels['sex'],
						'rules' => 'trim',
				),
				'birthday'=> array(
						'field' => 'birthday',
						'label' => $labels['birthday'],
						'rules' => 'trim',
				),
				'education'=> array(
						'field' => 'education',
						'label' => $labels['education'],
						'rules' => 'trim',
				),
				'graduation'=> array(
						'field' => 'graduation',
						'label' => $labels['graduation'],
						'rules' => 'trim',
				),
				'position'=> array(
						'field' => 'position',
						'label' => $labels['position'],
						'rules' => 'trim',
				),
				'business'=> array(
						'field' => 'business',
						'label' => $labels['business'],
						'rules' => 'trim',
				),
				'in_date'=> array(
						'field' => 'in_date',
						'label' => $labels['in_date'],
						'rules' => 'trim',
				),
				'changes'=> array(
						'field' => 'changes',
						'label' => $labels['changes'],
						'rules' => 'trim',
				),
				'previous_job'=> array(
						'field' => 'previous_job',
						'label' => $labels['previous_job'],
						'rules' => 'trim',
				),
				'description'=> array(
						'field' => 'description',
						'label' => $labels['description'],
						'rules' => 'trim',
				),
				'master_dept'=> array(
						'field' => 'master_dept',
						'label' => $labels['master_dept'],
						'rules' => 'trim',
				),
//				'second_dept'=> array(
//						'field' => 'second_dept',
//						'label' => $labels['second_dept'],
//						'rules' => 'trim',
//				),
				'employee_id'=> array(
						'field' => 'employee_id',
						'label' => $labels['employee_id'],
						'rules' => 'trim',
				),
				'in_group_date'=> array(
						'field' => 'in_group_date',
						'label' => $labels['in_group_date'],
						'rules' => 'trim',
				),
				'cellphone'=> array(
						'field' => 'cellphone',
						'label' => $labels['cellphone'],
						'rules' => 'trim',
				),
				'hotel_name'=> array(
						'field' => 'hotel_name',
						'label' => $labels['hotel_name'],
						'rules' => 'trim',
				),
				'view_count'=> array(
						'field' => 'view_count',
						'label' => $labels['view_count'],
						'rules' => 'trim',
				),
				'hotel_id'=> array(
						'field' => 'hotel_id',
						'label' => $labels['hotel_id'],
						'rules' => 'trim',
				),
				'inter_id'=> array(
						'field' => 'inter_id',
						'label' => $labels['inter_id'],
						'rules' => 'trim',
				),
				'is_club'=> array(
                    'field' => 'is_club',
                    'label' => $labels['is_club'],
                    'rules' => 'trim',
                )
		);
	
		$adminid= $this->session->get_admin_id();
		 
		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
				$post['add_date']= date('Y-m-d H:i:s');
				$post['add_user']= $adminid;
				 
				$this->load->model ( 'hotel/hotel_model' );
				$hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$this->session->get_admin_inter_id()) );
				$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
				$post['hotel_name'] = $hotels[$post['hotel_id']];
				
				$result= $model->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				//$this->_log($model);
// 				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));//@author lGh 修改跳转 2016-3-30 10:39:54
//                $this->_redirect(EA_const_url::inst()->get_url('*/*/index').'?h='.$post['hotel_id']);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
				$post['last_update_time']= date('Y-m-d H:i:s');
				$post['last_update_user']= $adminid;

				$this->load->model ( 'hotel/hotel_model' );
				$hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$this->session->get_admin_inter_id()) );
				$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
				$post['hotel_name'] = $hotels[$post['hotel_id']];
				if(empty($post['qrcode_id']) && $post['status'] == 2){
					$this->load->model('distribute/staff_model');
					$post['qrcode_id'] = $this->staff_model->get_qr_code($post['inter_id'],$post['name'],'','');
					$this->staff_model->save_staff_to_saler($post['inter_id'],$post['qrcode_id']);
				}

				$result= $model->load($post[$pk])->m_sets($post)->m_save($post);

                if($result){     //更新分销员社群客权限
                    if(isset($post['is_club'])){
//                        $this->db->insert('weixin_text',array('content'=>'update_club_staff_1+'.$post['qrcode_id'],'edit_date'=>date('Y-m-d H:i:s')));
                        if(isset($post['qrcode_id'])){
                            if(empty($post['qrcode_id'])||$post['qrcode_id']==0){
                                $post['qrcode_id']=1;
                            }
                            $this->load->model ( 'club/Club_model','Club_model' );
                            $staff_info=$this->Club_model->getOpenid($post['inter_id'],$post['qrcode_id']);
//                            $this->db->insert('weixin_text',array('content'=>'update_club_staff_2+'.json_encode($staff_info),'edit_date'=>date('Y-m-d H:i:s')));
                            if($staff_info){
                                $res=$this->Club_model->check_club($post['inter_id'], $staff_info['openid'],$post['qrcode_id']);
                                $post_str=array(
                                    'inter_id'=>$post['inter_id'],
                                    'qrcode_id'=>$staff_info['qrcode_id'],
                                    'openid'=>$staff_info['openid'],
                                    'name'=>$staff_info['name']
                                );
                                $status=array(
                                    '0'=>2,
                                    '1'=>1
                                );
                                if($res){
                                        $this->Club_model->update_club($post_str,array('status'=>$status[$post['is_club']]));
//                                        $this->db->insert('weixin_text',array('content'=>'update_club_staff_3+'.json_encode($post_str),'edit_date'=>date('Y-m-d H:i:s')));
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
//                                        $this->db->insert('weixin_text',array('content'=>'add_club_staff_3+'.json_encode($post_str),'edit_date'=>date('Y-m-d H:i:s')));
                                    }
                                }
                            }
                        }
                    }else{
                        if(isset($post['qrcode_id'])&&!empty($post['qrcode_id'])&&$post['qrcode_id']!=0){
                            $this->load->model ( 'club/Club_model','Club_model' );
                            $staff_info=$this->Club_model->getOpenid($post['inter_id'],$post['qrcode_id']);
//                            $this->db->insert('weixin_text',array('content'=>'update_club_staff_5+'.json_encode($staff_info),'edit_date'=>date('Y-m-d H:i:s')));
                            if($staff_info){
                                $res=$this->Club_model->check_club($post['inter_id'], $staff_info['openid'],$post['qrcode_id']);
                                $post_str=array(
                                    'inter_id'=>$post['inter_id'],
                                    'qrcode_id'=>$staff_info['qrcode_id'],
                                    'openid'=>$staff_info['openid'],
                                    'name'=>$staff_info['name']
                                );
                                if($res){
                                    $this->Club_model->update_club($post_str,array('status'=>2));
//                                    $this->db->insert('weixin_text',array('content'=>'update_club_staff_4+'.json_encode($post_str),'edit_date'=>date('Y-m-d H:i:s')));
                                }
                            }
                        }
                    }

                }

				$message= ($result)?
				$this->session->put_success_msg('已保存数据！'):
				$this->session->put_notice_msg('此次数据修改失败！');
				
				
				
				$this->_log($model);
// 				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));//@author lGh 修改跳转 2016-3-30 10:39:54
//				$this->_redirect(EA_const_url::inst()->get_url('*/*/index').'?h='.$post['hotel_id']);
                $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
	
			} else
				$model= $model->load($post[$pk]);
		}
	
		//验证失败的情况
		$validat_obj= _get_validation_object();
		$message= $validat_obj->error_html();
		//页面没有发生跳转时用寄存器存储消息
		$this->session->put_error_msg($message, 'register');
	
		$fields_config= $model->get_field_config('form');
		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> TRUE,
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}
	/**
	 * 批量审核
	 */
	public function batch_auth(){
		$this->load->model('distribute/staff_model');
		$res = $this->staff_model->batch_auth($this->session->get_admin_inter_id());
		echo json_encode($res);
	}
}
