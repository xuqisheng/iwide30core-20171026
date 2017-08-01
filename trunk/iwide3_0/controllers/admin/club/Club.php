<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Club extends MY_Admin_Cprice {
    protected $label_module = NAV_HOTEL;
    protected $label_controller = '社群客';
    protected $label_action = '';
    protected $common_data = array ();
    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->get_admin_inter_id ();
        $this->module = 'club';
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
        $this->common_data ['inter_id'] = $this->inter_id;
        // $this->output->enable_profiler ( true );
    }

	public function main_model_name()
	{
		return 'club/Club_model';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function  index(){
        $this->staff();
    }

    public function staff()
    {

        $data = $this->common_data;

        $this->load->model("club/Club_model");
        $this->load->model("club/Clubs_model");

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
        $condition='';

        if(!empty($entity_id)){
            $condition .="AND t2.hotel_id in ({$entity_id})";
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
                $condition=" AND t1.qrcode_id = ".intval($con).' ';
            }else{
                $get=addslashes($get);
                $condition=" AND  t1.name like '%{$get}%' ";
            }
        }

        $data['club_staffs'] = $this->Club_model->getAllClub($inter_id,$condition);

        $data['club_orders'] = $this->Clubs_model->get_all_club_orders($inter_id,'all');

        $data['club_grade'] = $this->Clubs_model->get_club_grade_ext($inter_id,'all');

        $data['price_code'] = $this->Clubs_model->get_all_price_codes($inter_id);

        $soma_club = $this->Clubs_model->get_soma_code($inter_id);


        if(!empty($soma_club)){
            foreach($soma_club as $arr_soma_club){
                $data['soma_code'][$arr_soma_club->id] = $arr_soma_club;
            }
        }


        $this->_render_content ( $this->_load_view_file ( 'grid' ), $data, false );
    }

    public function edit_post(){

        $this->load->model("club/Club_model");

        $info = array (
            'status' => 2,
            'message' => 'error'
        );

        $inter_id = $this->session->get_admin_inter_id ();
        $adminid = $this->session->get_admin_id ();

        $post = $this->input->get ();
        $data = json_decode($post['data']);
        $post_data = array();


        if(!empty($data->auth_price_code)){
            $post_data['auth_price_code'] = implode(',',$data->auth_price_code);
        }else{
            $post_data['auth_price_code'] = '';
        }

        if(!empty($data->club_price_code)){
            $post_data['club_price_code'] = implode(',',$data->club_price_code);
        }else{
            $post_data['club_price_code'] = '';
        }

        if(!empty($data->soma_codes)){
            $post_data['soma_code'] = implode(',',$data->soma_codes);
        }else{
            $post_data['soma_code'] = '';
        }

        $post_data['status'] = $data->status;
        $post_data['limited_amount'] = $data->limited_amount;
        $post_data['is_grade'] = $data->is_grade;

        $where['inter_id'] = $inter_id;
        $where['qrcode_id'] = $data->saler;


        $res = $this->Club_model->update_club($where,$post_data);

        if($res){
            $info['status'] = 1;
            $info['message'] = '保存成功';
        }

        echo json_encode($info);


    }

    public function qrcode_front()
    {
        if($id= $this->input->get('ids')){
            $inter_id = $this->session->get_admin_inter_id ();

            $this->load->model("club/Clubs_model");
            $club_info=$this->Clubs_model->club_info($inter_id,$id);
            $staff_info=$this->Clubs_model->getClubStaffById($club_info['id'],$inter_id);

            if(isset($club_info)&&isset($staff_info)){
                if($club_info['qrcode_id']==0||$club_info['qrcode_id']==NULL||empty($club_info['qrcode_id'])||empty($club_info['club_code'])||$club_info['club_code']==NULL){

                    $qrcode_info=$this->Clubs_model->get_wx_new_qrcode($this->inter_id,$club_info['club_name'],'社群客扫码',$staff_info['name'],$id=NULL);
                    if($qrcode_info){
                        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
                        $code = "";
                        for ( $i = 0; $i < 20; $i++ )
                        {
                            $code.= $chars[ mt_rand(0, strlen($chars) - 1) ];
                        }
                        $update_data=array('qrcode_id'=>$qrcode_info['qrcode_id'],'club_code'=>$code);
                        $res=$this->Clubs_model->update_qrcode_info($club_info['club_id'],$update_data);
                    }
                }
            }

            $url= EA_const_url::inst()->get_front_url($inter_id, 'club/Club/scan_qrcode',
                array('cid'=> $id,'id'=>$inter_id));
            $this->_get_qrcode_png($url);
        } else{
            echo '参数错误';
        }
    }


    public function edit() {

        $inter_id = $this->session->get_admin_inter_id ();

        $qrcode_id = $this->input->get('ids');

//        $data['hotels'] = $this->getHotels();

        $this->load->model("club/Clubs_model");

        $staff_info=$this->Clubs_model->getClubStaffById($qrcode_id,$inter_id);

        if(isset($staff_info['club_price_code'])&& !empty($staff_info['club_price_code'])){
            $price_codes=explode(',',$staff_info['club_price_code']);
        }else{
            $price_codes='';
        }


        if(isset($staff_info['auth_price_code'])&& !empty($staff_info['auth_price_code'])){
            $auth_price_code=explode(',',$staff_info['auth_price_code']);
        }else{
            $auth_price_code=array();
        }

        $all_price_code=$this->Clubs_model->get_all_price_codes($inter_id);


        $data=array(
            'all_price_code'=>$all_price_code,
            'staff_info'=>$staff_info,
            'chose_code'=>$price_codes,
            'auth_price_code'=>$auth_price_code
        );


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

        $html= $this->_render_content($this->_load_view_file('club_staff_edit'), $data, false);

        echo $html;
    }


    public function club_grade_turn(){    //社群客粉丝归属开关

        if(isset($_GET['grade'])&&$_GET['grade']==1){
            $is_grade=$_GET['grade'];
        }else{
            $is_grade=0;
        }

        $this->load->model('club/Club_model');
        $res = $this->Club_model->turn_grade($this->session->get_admin_inter_id(),$is_grade);
        echo json_encode($res);
    }


    public function turn_staff_grade(){

        $id= $this->input->get('ids');
        $operate= $this->input->get('operate');
        $inter_id = $this->session->get_admin_inter_id();

        if(!isset($id) || !isset($operate)){

            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/staff' ) );

        }else{

            $this->load->model('club/Club_model');
            $res = $this->Club_model->turn_grade($inter_id,$operate,$id);

            echo json_encode($res);

//            $message = ($res) ? $this->session->put_success_msg ( '修改成功！' ) : $this->session->put_notice_msg ( '修改失败！' );
//            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/staff' ) );

        }

    }

    public function  get_staff_info(){

        $inter_id = $this->session->get_admin_inter_id();

        $data = $this->common_data;

        $qrcode_id = $this->input->post('saler');

        $this->load->model('club/Club_model');
        $this->load->model('club/Clubs_model');


        $res['club'] = $this->Club_model->check_club($inter_id,'',$qrcode_id);

        $res['club']['price_code'] = $this->Clubs_model->get_all_price_codes($inter_id);

        $res['club']['all_soma_code'] = [];

        $all_soma_code= $this->Clubs_model->get_soma_code($inter_id);
        if(!empty($all_soma_code)){
            foreach($all_soma_code as $arr){
                $res['club']['all_soma_code'][$arr->id] = $arr;
            }
        }

        echo json_encode($res['club']);

    }


    public function club_reports(){    //社群客订单报表

        $data = $this->common_data;
        $inter_id = $this->inter_id;

        $this->load->model("club/Club_model");
        $this->load->model("club/Clubs_model");
        $this->load->model("club/Club_list_model");
        $this->load->model("club/Club_report_model");


        //所有社群客列表

        $club_list = $this->Club_list_model->getAllClubList($inter_id,'');
        $clubs = array();

        if(!empty($club_list)){
            foreach($club_list as $arr){
                $clubs[$arr['club_id']] = $arr;
            }
        }


        //所有社群客销售员
        $club_staff=$this->Clubs_model->getAllClubStaff($inter_id);
        $staff = array();

        if(!empty($club_staff)){
            foreach($club_staff as $arr){
                $staff[$arr['qrcode_id']] = $arr;
            }
        }

        //所有社群客绩效
        $club_grade = $this->Clubs_model->get_club_grade_ext($inter_id,'all','club_id');
        $grade = array();

        if(!empty($club_grade)){
            foreach($club_grade as $arr){
                if(!empty($arr['club_id']))$grade[$arr['club_id']] = $arr;
            }
        }


        $club_orders = $this->Club_report_model->get_club_report($inter_id);   //所有社群客订单


        $years=$this->Clubs_model->getNearYear(12);   //最近一年的月份
        $all_years = $years;

        $order_amount = 0;
        $total_price = 0;
        $total_night = 0;

        $all_order_amount = 0;
        $all_total_price = 0;
        $all_total_night = 0;

        if(!empty($club_orders)){
            $order_amount = count($club_orders);
            foreach($club_orders as $k => $orders){

                $time = strtotime($orders['enddate'])-strtotime($orders['startdate']);
                $night = ceil($time/86400);
                $club_orders[$k]['night'] = $night;
                $total_night = $total_night + $night;
                $total_price = $total_price + $orders['iprice'];

                if(isset($clubs[$orders['club_id']])){
                    if(isset($clubs[$orders['club_id']]['total_price'])){
                        $clubs[$orders['club_id']]['total_price'] = $clubs[$orders['club_id']]['total_price'] + $orders['iprice'];
                    }else{
                        $clubs[$orders['club_id']]['total_price'] = $orders['iprice'];
                    }

                    if(isset($clubs[$orders['club_id']]['night']))$clubs[$orders['club_id']]['night']=$clubs[$orders['club_id']]['night'] + $night;else $clubs[$orders['club_id']]['night']=$night;

                    if(isset($clubs[$orders['club_id']]['order_amount']))$clubs[$orders['club_id']]['order_amount']=$clubs[$orders['club_id']]['order_amount'] + 1;else $clubs[$orders['club_id']]['order_amount']=1;

                }


                foreach($years as $key => $year){
                    if(!isset($years[$key]['count'])) $years[$key]['count']=0;
                    if(!isset($years[$key]['night'])) $years[$key]['night']=0;
                    if(!isset($years[$key]['total_price']))$years[$key]['total_price']=0;
                    if($key==0){
                        if($orders['order_time']>=$year['info']){
                            $years[$key]['count'] = $years[$key]['count'] + 1;
                            $years[$key]['night'] = $years[$key]['night'] + $night;
                            $years[$key]['total_price'] = $years[$key]['total_price'] + $orders['iprice'];
                        }
                    }else{
                        if($orders['order_time']>=$year['info'] && $orders['order_time']<$years[$key-1]['info']){
                            $years[$key]['count'] = $years[$key]['count'] + 1;
                            $years[$key]['night'] = $years[$key]['night'] + $night;
                            $years[$key]['total_price'] = $years[$key]['total_price'] + $orders['iprice'];
                        }
                    }
                }
            }
        }

        $data['years'] = $years;
        $data['order_amount'] = $order_amount;
        $data['total_price'] = $total_price;
        $data['total_night'] = $total_night;


        $all_orders = $this->Club_report_model->near_year_orders($inter_id,$years[11]['info']);

        if(!empty($all_orders)){
            $all_order_amount = count($all_orders);
            foreach($all_orders as $orders){
                $time = strtotime($orders['enddate'])-strtotime($orders['startdate']);
                $night = ceil($time/86400);
                $all_total_night = $all_total_night + $night;
                $all_total_price = $all_total_price + $orders['iprice'];

                foreach($all_years as $key => $year){
                    if(!isset($all_years[$key]['night'])) $all_years[$key]['night']=0;
                    if(!isset($all_years[$key]['count'])) $all_years[$key]['count']=0;
                    if(!isset($all_years[$key]['total_price']))$all_years[$key]['total_price']=0;
                    if($key==0){
                        if($orders['order_time']>=$year['info']){
                            $all_years[$key]['count'] = $all_years[$key]['count'] + 1;
                            $all_years[$key]['night'] = $all_years[$key]['night'] + $night;
                            $all_years[$key]['total_price'] = $all_years[$key]['total_price'] + $orders['iprice'];
                        }
                    }else{
                        if($orders['order_time']>=$year['info'] && $orders['order_time']<$all_years[$key-1]['info']){
                            $all_years[$key]['count'] = $all_years[$key]['count'] + 1;
                            $all_years[$key]['night'] = $all_years[$key]['night'] + $night;
                            $all_years[$key]['total_price'] = $all_years[$key]['total_price'] + $orders['iprice'];
                        }
                    }
                }
            }
        }

        $data['all_years'] = $all_years;
        $data['all_order_amount'] = $all_order_amount;
        $data['all_total_price'] = $all_total_price;
        $data['all_total_night'] = $all_total_night;
        $data['club'] = $clubs;
        $data['staff'] = $staff;
        $data['grade'] = $grade;



        $this->_render_content ( $this->_load_view_file ( 'reports' ), $data, false );


    }


}
