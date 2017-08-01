<?php
//error_reporting(E_ALL^E_NOTICE);
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Club_list extends MY_Admin_Cprice {
    protected $label_module = '社群客列表';
    protected $label_controller = '社群客列表';
    protected $label_action = '';
    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->get_admin_inter_id ();
        $this->module = 'club';
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
        $this->common_data ['inter_id'] = $this->inter_id;
    }

    protected function main_model_name()
    {
        return 'club/club_list_model';
    }

    public function index()
    {
        $inter_id = $this->session->get_admin_inter_id ();

        $entity_id = $this->session->get_admin_hotels ();

        $data = $this->common_data;

        $this->load->model("club/Club_list_model");
        $this->load->model("club/Clubs_model");

        $condition='';

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
                            club_id = ".intval($con)."
                            OR id = ".intval($con)."
                          )
            ";
            }else{
                $get=addslashes($get);
                $condition=" AND  club_name like '%{$get}%' ";
            }
            if(!empty($entity_id)){
                $condition .="AND hotel_id in ({$entity_id})";
            }

        }

        $data['list'] = array();
        $data['a_list'] = array();
        $data['amount']['total']=0;
        $data['amount']['apply']=0;

        $res = $this->Club_list_model->getAllClubList($inter_id,$condition);

        if(!empty($res)){
            foreach($res as $arr){
                if($arr['status']!=0){
                    $data['list'][]=$arr;
                }else{
                    $data['a_list'][]=$arr;
                }
            }
            $data['amount']['total']=count($data['list']);
            $data['amount']['apply']=count($data['a_list']);
        }


        $data['c_nights'] = $this->Clubs_model->get_all_club_orders($inter_id,'all');
        $data['price_code'] = $this->Clubs_model->get_all_price_codes($inter_id);
		$temp_all_soma_code = $this->Clubs_model->get_soma_code($inter_id);

        if(!empty($temp_all_soma_code)){
            foreach($temp_all_soma_code as $temp_arr){
                $data['all_soma_code'][$temp_arr->id] = $temp_arr;
            }
        }else{
            $data['all_soma_code'] = '';
        }


        $data['staff'] = $this->Club_list_model->get_all_hotel_staff($inter_id);

        $data['status'] = array(
            '0'=>'审核中',
            '1'=>'有效',
            '2'=>'失效',
            '3'=>'失效'
        );


        $this->_render_content ( $this->_load_view_file ( 'club_list_index' ), $data, false );


    }


    public function apply()
    {
        $inter_id = $this->session->get_admin_inter_id ();
        if ($inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($inter_id)
            $filter = array (
                'inter_id' => $inter_id
            );
        else
            $filter = array (
                'inter_id' => 'deny'
            );
        $entity_id = $this->session->get_admin_hotels ();
        if (! empty ( $entity_id )) {
            // $filter['hotel_id']
        }
        // print_r($filter);die;

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request ())
            // 处理ajax请求，参数规格不一样
        $get_filter = $this->input->post ();
        else
            $get_filter = $this->input->get ( 'filter' );

        if (! $get_filter)
            $get_filter = $this->input->get ( 'filter' );

        if (is_array ( $get_filter ))
            $filter = $get_filter + $filter;
        /* 兼容grid变为ajax加载加这一段 */

            $filter['status']=0;


        $this->c_grid ( $filter,'','club_list_apply' );

    }


    public function edit() {
        $this->label_action = '社群客';
        $this->_init_breadcrumb ( $this->label_action );

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $id = intval ( $this->input->get ( 'ids' ) );
        $this->load->model ( 'club/club_list_model' );
        if ($id) {
            // for edit page.
            // $model= $this->hotel_ext_model->load($id);
            $model = $model->load ( $id );
            if(!$model){
                redirect ( EA_const_url::inst ()->get_url ( '*/*/apply' ) );
            }
            $fields_config = $model->get_field_config ( 'form' );
            $detail_field = array ();
            if (count ( $detail_field ) > 0) {
                $detail_field = $detail_field [0] ['attr_value'];
            } else {
                $detail_field = '';
            }
        } else {
            // for add page.
            // $model= $this->hotel_ext_model->load($id);
            $model = $model->load ( $id );

            if (! $model)
                $model = $this->_load_model ();
            $fields_config = $model->get_field_config ( 'form' );


            $detail_field = '';
        }

        $view_params = array (
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => FALSE,
            'detail_field' => $detail_field,
            'services' => '',
            'hotel_ser' => ''
        )

            // 'gallery'=> $gallery,
        ;
        $html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );

        echo $html;
    }


    public function edit_post() {

        $this->label_action = '信息维护';
        $this->_init_breadcrumb ( $this->label_action );

        $inter_id = $this->session->get_admin_inter_id ();

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $pk = $model->table_primary_key ();



        $this->load->library ( 'form_validation' );
        $post = $this->input->post ();


        $adminid = $this->session->get_admin_id ();

        $labels = $model->attribute_labels ();


        $base_rules = array (
            'status' => array (
                'field' => 'status',
                'label' => $labels ['status'],
                'rules' => 'trim|required'
            ),
        );

        // 检测并上传文件。
        $post = $this->_do_upload ( $post, 'intro_img' );

        $adminid = $this->session->get_admin_id ();


        if (empty ( $post [$pk] )) {

            // add data.
            $this->form_validation->set_rules ( $base_rules );

            if ($this->form_validation->run () != FALSE) {
                $post ['update_time'] = date ( 'Y-m-d H:i:s' );
                $post ['inter_id'] = $inter_id;

                $result = $model->m_sets ( $post )->m_save ( $post );

                $message = ($result) ? $this->session->put_success_msg ( '已新增数据！' ) : $this->session->put_notice_msg ( '此次数据保存失败！' );
                 $this->_log($model);

                $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/apply' ) );
            } else
                $model = $this->_load_model ();
        } else {

            $this->form_validation->set_rules ( $base_rules );
            if ($this->form_validation->run () != FALSE) {
                $post ['update_time'] = date ( 'Y-m-d H:i:s' );
                $post ['inter_id'] = $inter_id;

                $result = $model->load ( $post [$pk] )->m_sets ( $post )->m_save ( $post );
//                $model->save_services ( $result );
                $message = ($result) ? $this->session->put_success_msg ( '已保存数据！' ) : $this->session->put_notice_msg ( '此次数据修改失败！' );

                if($result){
                    if($post['status']==1){
                        $this->load->model ( 'club/Clubs_model' );
                        $club_info = $this->Clubs_model->get_club_by_id($post [$pk]);
                        if(isset($club_info['openid'])){
                            $this->load->model ( 'plugins/Template_msg_model' );
                            $params=array(
                                'openid'=>$club_info['openid'],
                                'keyword2'=>date('Y-m-d H:i:s',time())
                            );
                            $this->Template_msg_model->hotel_club_templates ($inter_id,$params ,'hotel_club_auth' );
                        }
                    }
                }

                $this->_log ( $model );
                $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/apply' ) );
            } else
                $model = $model->load ( $post [$pk] );
        }

        // 验证失败的情况
        $validat_obj = _get_validation_object ();
        $message = $validat_obj->error_html ();
        // 页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg ( $message, 'register' );

        $fields_config = $model->get_field_config ( 'form' );
        $view_params = array (
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => TRUE
        );
        $html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
        echo $html;
    }


    public function club_batch_auth(){
        $this->load->model('club/Club_list_model');
        $inter_id = $this->session->get_admin_inter_id();

        $condition = " AND status = 0";
        $clublist = $this->Club_list_model->getAllClubList($inter_id,$condition);

        $info = array(
            'status' => 1,
            'message'=>'审核成功'
        );

        if(!empty($clublist)){
            foreach($clublist as $club){
                $res = $this->Club_list_model->ensure_club($inter_id,$club['club_id']);
                if(!$res){
                    $info['status']=2;
                    $info['message']='审核失败';
                }
            }
        }

        echo json_encode($info);
    }

    public function club_edit(){
    	$data = $this->common_data;
    	$model = $this->_load_model ( $this->main_model_name () );
    	$club_id = $this->input->get ( 'ids' );
    	$this->load->model('club/Clubs_model');
        $this->load->model('club/Club_list_model');
        $this->load->model('hotel/Hotel_model');

    	if (! empty ( $club_id )) {
    		$data ['list'] = $model->get_club_by_id ( $this->inter_id, $club_id );

    		if (empty ( $data ['list'] )) {
    			redirect ( site_url ( 'club/club/staff' ) );
    		}

            $club_staff=$this->Clubs_model->check_club($data ['list']['openid']);

            $usable_price_code=[];

            if(isset($club_staff)&&!empty($club_staff['club_price_code'])){
                $staff_price_code=explode(',',$club_staff['club_price_code']);

                $all_price_code=$this->Clubs_model->get_all_price_codes($this->inter_id,1);
                foreach($all_price_code as $arr){
                    if(isset($staff_price_code) && in_array($arr['price_code'],$staff_price_code)){
                        $usable_price_code[$arr['price_code']]=$arr;
                    }
                }
            }

            $data['price_codes']=$usable_price_code;

            $usable_soma_code=[];

            if(isset($club_staff)&&!empty($club_staff['soma_code'])){
                $staff_soma_code=explode(',',$club_staff['soma_code']);

                $all_soma_code=$this->Clubs_model->get_soma_code($this->inter_id);
                foreach($all_soma_code as $arr){
                    if(isset($staff_soma_code) && in_array($arr->id,$staff_soma_code)){
                        $usable_soma_code[$arr->id]=$arr;
                    }
                }
            }


            $data['usable_soma_codes']=$usable_soma_code;

    		$data['club_id']=$club_id;
    		$data['statuses']=array('0'=>'审核中','2'=>'审核不通过','1'=>'有效','3'=>'无效');
    		if($data['list']['status']==1||$data['list']['status']==3){
    			$data['sta_edit']=1;
    			$data['statuses']=array('1'=>'有效','3'=>'无效');
    		}else {
    			$data['sta_edit']=0;
    			$data['statuses']=$data['statuses'][$data['list']['status']];
    		}

            if(!empty($data['list']['price_code'])){
                $data['list']['price_code'] = explode(',',$data['list']['price_code']);
            }else{
                $data['list']['price_code'] = [];
            }

            if(!empty($data['list']['soma_code'])){
                $data['list']['soma_code'] = explode(',',$data['list']['soma_code']);
            }else{
                $data['list']['soma_code'] = [];
            }

    	} else {
            redirect ( site_url ( 'club/club/staff' ) );
    	}

        $data['salers'] = $this->Club_list_model->get_salers($this->inter_id,2,1);

        $data['hotels'] = $this->Hotel_model->get_all_hotels($this->inter_id,1);

        $data['array_hotel_ids'] = array();
        if(!empty($data ['list']['hotel_id']))$data['array_hotel_ids'] = explode(',',$data ['list']['hotel_id']);

        $data['json_hotel_id'] = json_encode($data['array_hotel_ids']);


    	$this->_render_content ( $this->_load_view_file ( 'club_list_edit' ), $data, false );
    }
    
    public function ajax_hotels() {
    	$club_id = $this->input->get ( 'ids' );
    	$model = $this->_load_model ( $this->main_model_name () );
    	$hotels = '';
    	if (! empty ( $club_id )) {
    		$check = $model->get_club_by_id ( $this->inter_id, $club_id, FALSE);
    		$hotels = empty ( $check ['hotel_id'] ) ? '' : explode(',', $check ['hotel_id']);
    	}
    	$hotels = $model->hotels_check ( $this->inter_id, $hotels, $this->session->admin_profile ['entity_id'] );
    	if (! empty ( $hotels )) {
    		echo json_encode ( array (
    				'status' => 1,
    				'data' => $hotels
    		), JSON_UNESCAPED_UNICODE );
    	} else {
    		echo json_encode ( array (
    				'status' => 2,
    				'message' => '无数据'
    		), JSON_UNESCAPED_UNICODE );
    	}
    }
    public function ajax_search_salers(){
    	$keyword=$this->input->get('keyword');
    	$this->load->model('club/Club_list_model');
    	$saler=$this->Club_list_model->search_staff($this->inter_id,$keyword);
    	$salers=array();
    	foreach ($saler as $s){
    		$salers[]=array('name'=>$s['name'],'saler_id'=>$s['qrcode_id']);
    	}
    	$data=array('status' => 1,'count'=>count($saler),'content'=>$salers);
    	echo json_encode($data);
    }
    public function save_club(){
    	$datas = $this->input->post ();
        $data = array();
        if(!empty($datas)){
            $post_data = json_decode($datas['datas']);
            foreach($post_data as $arr){
                $data[$arr->name] = $arr->value;
            }
        }


    	$club = array ();
    	$model = $this->_load_model ( $this->main_model_name () );
    	$info = array (
    			'status' => 2,
    			'message' => 'error'
    	);
    	if (! empty ( $data ['club_id'] )) {
    		$check = $model->get_club_by_id ( $this->inter_id, $data ['club_id'] );
    		if (empty($check)){
    			$info ['message'] = '数据错误';
    			exit ( json_encode ( $info ) );
    		}
//    		if($check['status']==1||$check['status']==3){
//    			$club ['status'] = intval ( $data ['status'] ) == 1 ? 1 : 3;
                $club ['status'] = intval ( $data ['status'] );
//    		}
    	}else {
    		$club ['status'] = 0;
    		if (empty($data['saler_id'])){
    			$info ['message'] = '请选择分销员';
    			exit ( json_encode ( $info ) );
    		}
    	}

        $data['saler_id']=intval($data['saler_id']);
        $this->load->model('distribute/Staff_model');
        $saler=$this->Staff_model->get_my_base_info_saler($this->inter_id,$data['saler_id']);
        if (empty($saler)){
            $info ['message'] = '分销员错误';
            exit ( json_encode ( $info ) );
        }
        $club['openid']=$saler['openid'];
        $club['id']=$saler['qrcode_id'];

    	if (empty ( $data['club_name'] )) {
    		$info ['message'] = '请填写名称';
    		exit ( json_encode ( $info ) );
    	}
    	if (empty (intval($data['limited_amount']))) {
    		$info ['message'] = '请填写正确人数';
    		exit ( json_encode ( $info ) );
    	}
    	if (empty ( $data['start_range'] )||empty ( $data['end_range'] )||!strtotime($data['start_range'])||!strtotime($data['end_range'])||strtotime($data['end_range'])<strtotime($data['start_range'])) {
    		$info ['message'] = '请填写正确时间';
    		exit ( json_encode ( $info ) );
    	}
    	$this->load->model('club/Clubs_model');
    	$price_codes=$this->Clubs_model->get_all_price_codes($this->inter_id,NULL);
//    	if (empty($data['price_code'])||!array_key_exists($data['price_code'], $price_codes)){
        if (empty($data['price_code']) && empty($data['soma_code'])){
    		$info ['message'] = '请选择价格代码';
    		exit ( json_encode ( $info ) );
    	}
//         else{
//            $club['price_code']=implode(',',$data['price_code']);
//        }

        if((isset($check['id']) && $check['id']!=$data['saler_id']) || empty ( $data ['club_id'])){    //检查是否已经超过开通上限
            if(isset($data['saler_id'])&&!empty($data['saler_id'])){
                $this->load->model("club/Clubs_model");
                $limited_amount=$this->Clubs_model->check_staff_validated($this->inter_id,$data['saler_id']);
                if(!$limited_amount){
                    $info ['message'] = '已经超过社群客可开通数量或销售员社群客失效';
                    exit ( json_encode ( $info ) );
                }
            }
        }


        $club['id']=$data['saler_id'];
        $club['price_code']=$data['price_code'];
        $club['soma_code']=$data['soma_code'];
    	$club['club_name']=$data['club_name'];
    	$club['limited_amount']=$data['limited_amount'];
    	$club['valid_time']=date('Ymd',strtotime($data['start_range'])).'-'.date('Ymd',strtotime($data['end_range']));
        $club['remark']=$data['remark'];
    
    	// 门店规则
    	if (! empty ( $data ['hotel_ids'] )) {
    		$club ['hotel_id'] = array ();
    		if ($data ['hotel_ids'] != 'all') {
//    			foreach ( $data ['hotel_ids'] as $hotel_id ) {
//    				$club ['hotel_id'] [] = $hotel_id;
//    			}
                $club ['hotel_id'] = $data ['hotel_ids'];
    		}else{
                $club ['hotel_id'] = 0;
            }
//    		$club ['hotel_id'] = implode(',', $club ['hotel_id'] );
    	}


    	if (! empty ( $data ['club_id'] )) {
    		if ($model->save_club ( $this->inter_id, $data ['club_id'], $club )) {

                if(isset($check['id']) && $check['id']!=$club['id']){
                    $model->update_club_staff_amount($this->inter_id,$club['id']);
                    $model->update_club_staff_amount($this->inter_id,$check['id'],'change');
                }
    			$info ['status'] = 1;
    			$info ['message'] = '保存成功';
    		} else {
    			$info ['message'] = '保存失败';
    		}
    	} else {
    		if ($model->add_club ( $this->inter_id, $club )) {
                if($model->update_club_staff_amount($this->inter_id,$club['id'])){
                    $info ['status'] = 10;
                    $info ['message'] = '添加成功';
                }
    		} else {
    			$info ['message'] = '添加失败';
    		}
    	}
    	echo json_encode ( $info );
    	exit ();
    }

    public function change_saler(){

        $inter_id=$this->session->get_admin_inter_id ();
        $entity_id = $this->session->get_admin_hotels ();
        $hotel_ids = explode ( ',', $entity_id );
        $club_id = $this->input->get ( 'ids' );

        $this->load->model ( 'club/Club_list_model' );
        $this->load->model ( 'hotel/Bonus_rules_model' );

        $club_info = $this->Club_list_model->get_club_by_id($inter_id,$club_id);
        $logs=$this->Bonus_rules_model->get_rule_logs($this->inter_id,'change_saler#'.$club_id,0,20);


        if($logs){
            foreach($logs as $arr){
                $edit_admin=json_decode($arr['admin']);
                $arr['admin']=$edit_admin;
                $arr['updata'] = explode(',',$arr['key_data']);
                $data['logs'][]=$arr;
            }
        }


        if(empty($club_info)){
            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ) );
        }

        $data['salers'] = $this->Club_list_model->get_salers($inter_id,2,1);
        $data['club_id'] = $club_id;


        $this->_render_content ( $this->_load_view_file ( 'change_saler' ), $data, false );

    }


    public function assign(){

        $this->load->model ( 'club/Club_list_model' );
        $inter_id=$this->session->get_admin_inter_id ();
        $entity_id = $this->session->get_admin_hotels ();
        $hotel_ids = explode ( ',', $entity_id );

        $club_id = $this->input->get ( 'ids' );
        $saler_id = $this->input->get ( 'sid' );

        $hotel_staff = $this->Club_list_model->get_salers($inter_id,2,1,$saler_id);

        if(empty($club_id) || empty($saler_id) || empty($hotel_staff['openid'])){
            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ) );
        }

        $this->load->model ( 'club/Club_list_model' );

        $club_info = $this->Club_list_model->get_club_by_id($inter_id,$club_id);

        if(empty($club_info)){
            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ));
        }

        if($club_info['id']==$saler_id){
            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ));
        }

        $club_staff = $this->Club_list_model->getStaffPriceCode($inter_id,$hotel_staff['qrcode_id']);


        if(!empty($club_staff) && $club_staff['amount']>=$club_staff['limited_amount']){
            $this->_redirect ( EA_const_url::inst ()->get_url ( 'club/club_list/change_saler?ids='.$club_id ));
        }

        if(!empty($club_staff)){
            $saler_price_codes = explode(',',$club_staff['club_price_code']);
            $club_price_codes = explode(',',$club_info['price_code']);
        }else{
            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ));
        }


        foreach($club_price_codes as $club_price_code){
            if(!in_array($club_price_code,$saler_price_codes)){
                $res = 'false';
                continue;
            }
        }

        if(isset($res) && $res =='false'){
            $this->_redirect ( EA_const_url::inst ()->get_url ( 'club/club_list/change_saler?ids='.$club_id ));
        }else{
            $assign = $this->Club_list_model->update_saler($inter_id,$club_id,$saler_id,$club_info['id'],$hotel_staff['openid']);
            if($assign==1){
                $this ->Club_list_model->addClubStaffAmount($inter_id,$saler_id);
                $this ->Club_list_model->reduceClubStaffAmount($inter_id,$club_info['id']);
                $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ) );
            }

        }

    }


    public function check(){

        $data = $this->common_data;
        $club_id = $this->input->get ( 'ids' );
        $this->load->model('club/Clubs_model');
        $this->load->model('club/Club_list_model');
        $this->load->model('club/Club_customer_model');
        $this->load->model('hotel/Hotel_model');

        if (! empty ( $club_id )) {
            $data ['list'] = $this->Club_list_model->get_club_by_id ( $this->inter_id, $club_id );

            if (empty ( $data ['list'] )) {
                redirect ( site_url ( 'club/club/staff' ) );
            }

            $club_staff=$this->Clubs_model->check_club($data ['list']['openid']);
            if(isset($club_staff)&&!empty($club_staff['club_price_code'])){
                $staff_price_code=explode(',',$club_staff['club_price_code']);
            }

            $usable_price_code='';

            $all_price_code=$this->Clubs_model->get_all_price_codes($this->inter_id,1);
            foreach($all_price_code as $arr){
                if(isset($staff_price_code) && in_array($arr['price_code'],$staff_price_code)){
                    $usable_price_code[$arr['price_code']]=$arr;
                }
            }
            $data['price_codes']=$usable_price_code;

            $data['club_id']=$club_id;
            $data['statuses']=array('0'=>'审核中','2'=>'审核不通过','1'=>'有效','3'=>'无效');
            if($data['list']['status']==1||$data['list']['status']==3){
                $data['sta_edit']=1;
                $data['statuses']=array('1'=>'有效','3'=>'无效');
            }else {
                $data['sta_edit']=0;
                $data['statuses']=$data['statuses'][$data['list']['status']];
            }

            if(!empty($data['list']['price_code'])){
                $data['list']['price_code'] = explode(',',$data['list']['price_code']);
            }
        } else {
            redirect ( site_url ( 'club/club/staff' ) );
        }

        $club_orders = $this->Clubs_model->get_all_club_orders($this->inter_id,'all');

        $data['roomnight']=0;

        if(isset($club_orders['count'][$club_id]))$data['roomnight']=$club_orders['count'][$club_id];

        $data['salers'] = $this->Club_list_model->get_salers($this->inter_id,2,1);

        $hotels = $this->Hotel_model->get_all_hotels($this->inter_id);

        if(!empty($hotels)){
            foreach($hotels as $hotel){
                $data['hotels'][$hotel['hotel_id']] = $hotel;
            }
        }

        $data['customer'] = $this->Club_customer_model->getClubCustomer($this->inter_id,$club_id);

        $data['qrcode_url'] = base_url("club/club/qrcode_front?ids=").$club_id;

        $this->_render_content ( $this->_load_view_file ( 'club_list_check' ), $data, false );

    }


    public function ext_club_customer(){

        $this->load->model('club/Club_customer_model');
        $this->load->model('club/Club_list_model');

        ini_set('memory_limit','265M');
        set_time_limit(120);
        $inter_id= $this->session->get_admin_inter_id();

        $club_id = $this->input->get('ids');

        $club_info = $this->Club_list_model->get_club_by_id($inter_id,$club_id);

        $res  = $this->Club_customer_model->getClubCustomer($this->inter_id,$club_id);

        $status_dec = array(
            '1'=>'有效',
            '2'=>'失效'
        );

        $this->load->model ( 'plugins/Excel_model');
        $this->load->model ( 'wx/Publics_model');

        $head = array ('姓名','电话','状态');

        $data = array();

        if(!empty($res)){
            foreach($res as $key=>$item){
                $temp[0]=$item['name'];
                $temp[1]=$item['tel'];
                $temp[2]=$status_dec[$item['status']];
                $data[]=$temp;
            }

        }

        $ext_date = date('Y-m-d',time());

        $filename='';

        if(isset($club_info['club_name']))$filename=$club_info['club_name'];

        $filename = $filename.'成员导出_'.$ext_date;

        $this->Excel_model->exp_exl($head,$data,$filename);

    }


    public function club_batch_post(){  //审核

        $info = array(
            'status'=>1,
            'message'=>'审核失败'
        );

        $this->load->model('club/Club_list_model');

        $club_id = $this->input->get('club_id');
        $inter_id= $this->session->get_admin_inter_id();

        $res = $this->Club_list_model->ensure_club($inter_id,$club_id);

        if($res){
            $info['status']=0;
            $info['message']='审核成功';
        }

         echo json_encode($info);

    }


    public function change_club_customer(){

        $this->load->model('club/Club_customer_model');
        $inter_id= $this->session->get_admin_inter_id();

        $post_data['inter_id'] = $inter_id;
        $post_data['club_id'] = $this->input->get('club_id');
        $post_data['customer_id'] = $this->input->get('customer_id');

        $update_data['status'] = $this->input->get('status');

        $res = $this->Club_customer_model->changeCustomerStatus($post_data,$update_data);

        echo 1;

    }

}
