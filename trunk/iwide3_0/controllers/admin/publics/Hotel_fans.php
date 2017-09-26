<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_fans extends MY_Admin {
    protected $label_controller = '粉丝列表';
    protected $label_action = '';
    protected $common_data = array ();
    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->get_admin_inter_id ();
        $this->module = 'publics';
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
        $this->common_data ['inter_id'] = $this->inter_id;
        // $this->output->enable_profiler ( true );
    }

	public function main_model_name()
	{
		return 'hotel/Hotel_fans';
	}

    public function userlist(){

        $data = $this->common_data;
        $offset = array(
            'nums'=>20,
            'page'=>1
        );
        $keyword='';

        $this->load->model ( 'wx/Fans_model' );
        $this->load->model ( 'hotel/Hotel_model' );

        $post_data = $this->input->get();

        if(!empty($post_data['p'])){
            $offset['page']=$post_data['p'];
            $data['page'] = $post_data['p'];
        }else{
            $data['page'] = 1;
        }


        if(!empty($post_data['nums']))$offset['nums']=$post_data['nums'];
        if(!empty($post_data['key'])){
            $keyword=$post_data['key'];
            $data['key'] = $keyword;
        }else{
            $data['key'] = '';
        }

        $res = $this->Fans_model->get_all_fans($this->inter_id,$offset,$keyword);

        $data['fans'] = $res['data'];
        $data['count'] = $res['count']['total'];
        $data['page_nums'] = ceil($data['count']/$offset['nums']);
        $data['per_nums']=count($res['data']);

        $html= $this->_render_content($this->_load_view_file('userlist'), $data, false);

        echo $html;

    }



    public function fans_admin(){

        $data = $this->common_data;

        $data['new_fans'] = 0;
        $data['cancel_fans'] = 0;
        $data['total'] = 0;
        $data['self_fans'] = 0;
        $data['saler_fans'] = 0;
        $data['scene_total']=0;

        $this->load->model ( 'wx/Fans_model' );
        $this->load->model ( 'hotel/Hotel_model' );

        $inter_id = $this->inter_id;

        //关注总数
        $hotel_fans = $this->Fans_model->count_hotel_fans($inter_id);
        $data['count_all_fans'] = $hotel_fans['total'];
//        if(!empty($hotel_fans)){
//            foreach($hotel_fans as $thf){
//                if($thf['hotel_id']>0){
//                    $data['hotel_fans'][$thf['hotel_id']] = $thf['total'];
//                    $data['scene_total'] = $data['scene_total'] + $thf['total'];
//                }
//            }
//        }

        //各个酒店昨日取消数
        $today =  date('Y-m-d 00:00:00');
        $last_day = date('Y-m-d 00:00:00',strtotime('- 1 day'));
        $temp_hotel_last_cancel = $this->Fans_model->lastday_cancel($inter_id,$today,$last_day);
        if(!empty($temp_hotel_last_cancel)){
            $hotel_last_cancel = array();
            foreach($temp_hotel_last_cancel as $hlc){
                if($hlc['hotel_id']>0)$hotel_last_cancel[$hlc['hotel_id']] = $hlc['total'];
            }
        }

        //各个酒店的分销员粉丝数
        $temp_distribute_fans = $this->Fans_model->distributed_fans($inter_id);
        if(!empty($temp_distribute_fans)){
            $distribute_fans = array();
            foreach($temp_distribute_fans as $df){
                if($df['hotel_id']>0)$distribute_fans[$df['hotel_id']] = $df['total'];
            }
        }

        //各个酒店的场景粉丝数
        $temp_scene_fans = $this->Fans_model->distributed_fans($inter_id,0);
        if(!empty($temp_scene_fans)){
            $scene_fans = array();
            foreach($temp_scene_fans as $sf){
                if($sf['hotel_id']>0)$scene_fans[$sf['hotel_id']] = $sf['total'];
            }
        }

        $data['hotels'] =  $this->Hotel_model->get_all_hotels($this->inter_id);

        if(!empty($data['hotels'])){
            foreach($data['hotels'] as $key=>$hotel){

                if(isset($hotel_last_cancel[$hotel['hotel_id']])){
                    $data['hotels'][$key]['last_cancel'] = $hotel_last_cancel[$hotel['hotel_id']];
                }else{
                    $data['hotels'][$key]['last_cancel'] = 0;
                }

                if(isset($distribute_fans[$hotel['hotel_id']])){
                    $data['hotels'][$key]['distribute_fans'] = $distribute_fans[$hotel['hotel_id']];
                }else{
                    $data['hotels'][$key]['distribute_fans'] = 0;
                }


                if(isset($scene_fans[$hotel['hotel_id']])){
                    $data['hotels'][$key]['scene_fans'] = $scene_fans[$hotel['hotel_id']];
                }else{
                    $data['hotels'][$key]['scene_fans'] = 0;
                }

                $data['hotels'][$key]['total_fans'] = $data['hotels'][$key]['distribute_fans'] + $data['hotels'][$key]['scene_fans'];
                $data['scene_total'] = $data['scene_total'] + $data['hotels'][$key]['total_fans'];

            }
        }

        $this->load->helper ( 'common' );
        $this->load->model ( 'wx/Access_token_model' );
        $access_token = $this->Access_token_model->get_access_token ( $this->inter_id );
        $get_fans = doCurlGetRequest('https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token);
        $get_fans = json_decode($get_fans);


        $post_data = array(
            'begin_date'=>date("Y-m-d",time()-86400),
            'end_date'=>date("Y-m-d",time()-86400)
        );

        $url = 'https://api.weixin.qq.com/datacube/getusersummary?access_token='.$access_token;
        $usersummary = json_decode(doCurlPostRequest($url,json_encode($post_data)));


        if(!empty($usersummary->list)){
            $temp_new_fans = 0;
            $temp_cancel_fans = 0;
            foreach($usersummary->list as $arr_list){
                $temp_new_fans = $temp_new_fans + $arr_list->new_user;
                $temp_cancel_fans = $temp_cancel_fans + $arr_list->cancel_user;
            }
            $data['new_fans'] = $temp_new_fans;
            $data['cancel_fans'] = $temp_cancel_fans;
        }

        $hotel_count = array();

        $data['total'] = isset($get_fans->total)?$get_fans->total:0;

        $data['hotel_count'] = $hotel_count;


        $html= $this->_render_content($this->_load_view_file('fans_admin'), $data, false);

        echo $html;

    }


    public function ext_date_data(){

        $data = $this->common_data;
        $this->load->model("wx/Fans_model");
        $this->load->model ( 'plugins/Excel_model');
        $params = array();
        $data['new_total'] = 0;
        $data['self_total'] = 0;
        $data['scan_total'] = 0;
        $data['dis_total'] = 0;
        $data['cancel_total'] = 0;
        $data['date_data'] = array();
        $data['hotel_data'] = array();

//        $post = json_decode($this->input->raw_input_stream,true);
        $post = $this->input->get();
        if(isset($post['hotel_id']) && !empty($post['hotel_id']))$params['hotel_id'] = $post['hotel_id'];


        if(!isset($post['startdate']) || !isset($post['enddate'])){
            $post['startdate'] = date('Y-m-d' , strtotime("-2 day"));;
            $post['enddate'] = date('Y-m-d' , time());;
        }

        $params['startdate'] = $post['startdate'];
        $params['enddate'] = $post['enddate'];


        $dt_start = strtotime($params['startdate']);
        $dt_end = strtotime($params['enddate']);
        while ($dt_start<=$dt_end){
            $each_day[date('Y-m-d',$dt_start)] = array('date'=>date('Y-m-d',$dt_start));
            $dt_start = strtotime('+1 day',$dt_start);
        }

        $params['cur_status'] = 1;

        $date_new_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'date');  //按日期统计新增
        $date_self_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'date',-1);  //按日期统计自主关注
        $dis_date_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'date');   //按日期统计分销
        $scan_date_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'date',2);   //按日期统计扫码关注

        $params['cur_status'] = 2;
        $date_cancel_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'date');  //按日期统计取消

        if(!empty($each_day)){
            foreach($each_day as $key => $e_day){
                $each_day[$key]['new'] = isset($date_new_fans[$key])? $date_new_fans[$key]['total'] : 0;
                $each_day[$key]['self'] = isset($date_self_fans[$key])? $date_self_fans[$key]['total'] : 0;
                $each_day[$key]['dis'] = isset($dis_date_fans[$key])? $dis_date_fans[$key]['total'] : 0;
                $each_day[$key]['scan'] = isset($scan_date_fans[$key])? $scan_date_fans[$key]['total'] : 0;
                $each_day[$key]['cancel'] = isset($date_cancel_fans[$key])? $date_cancel_fans[$key]['total'] : 0;
            }
        }


        $head = array ('时间','新增粉丝','自主关注','扫码关注','分销关注','取消关注');

        $ext_data = array();

        if(!empty($each_day)){
            foreach($each_day as $key=>$item){
                $temp[0]=$key;
                $temp[1]=$item['new'];
                $temp[2]=$item['self'];
                $temp[3]=$item['dis'];
                $temp[4]=$item['scan'];
                $temp[5]=$item['cancel'];
                $ext_data[]=$temp;
            }

        }

        $ext_date = date('Y-m-d',time());

        $filename='';

        $filename = $filename.'每日粉丝明细_'.$ext_date;

        $this->Excel_model->exp_exl($head,$ext_data,$filename);

    }


    public function ext_hotel_data(){

        $data = $this->common_data;
        $this->load->model("wx/Fans_model");
        $this->load->model("hotel/Hotel_model");
        $this->load->model ( 'plugins/Excel_model');
        $hotels = $this->Hotel_model->get_all_hotels($this->inter_id,null,'key');
        $params = array();
        $data['new_total'] = 0;
        $data['self_total'] = 0;
        $data['scan_total'] = 0;
        $data['dis_total'] = 0;
        $data['cancel_total'] = 0;
        $data['hotel_data'] = array();

//        $post = json_decode($this->input->raw_input_stream,true);
        $post = $this->input->get();
        if(isset($post['hotel_id']) && !empty($post['hotel_id']))$params['hotel_id'] = $post['hotel_id'];

        if(!isset($post['startdate']) || !isset($post['enddate'])){
            $post['startdate'] = date('Y-m-d' , strtotime("-2 day"));;
            $post['enddate'] = date('Y-m-d' , time());;
        }

        $params['startdate'] = $post['startdate'];
        $params['enddate'] = $post['enddate'];

        $params['cur_status'] = 1;
        $hotel_new_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'hotel');//按酒店统计新增
        $hotel_self_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'hotel',-1);  //按酒店统计自主关注
        $dis_hotel_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'hotel');   //按酒店统计分销
        $scan_hotel_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'hotel',2);   //按酒店统计扫码关注
        $params['cur_status'] = 2;
        $hotel_cancel_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'hotel');//按酒店统计取消


        if(!empty($hotels)){
            $hotel_data = array(
                -1 => array(
                    'hotel_id' => -1,
                    'hotel_name' => '无'
                )
            );
            $hotel_data[-1]['new'] = isset($hotel_new_fans[-1])? $hotel_new_fans[-1]['total'] : 0;
            $hotel_data[-1]['self'] = isset($hotel_self_fans[-1])? $hotel_self_fans[-1]['total'] : 0;
            $hotel_data[-1]['dis'] = isset($dis_hotel_fans[-1])? $dis_hotel_fans[-1]['total'] : 0;
            $hotel_data[-1]['scan'] = isset($scan_hotel_fans[-1])? $scan_hotel_fans[-1]['total'] : 0;
            $hotel_data[-1]['cancel'] = isset($hotel_cancel_fans[-1])? $hotel_cancel_fans[-1]['total'] : 0;
            foreach($hotels as $key => $hotel){
                $hotel_data[$key]['hotel_id'] = $key;
                $hotel_data[$key]['hotel_name'] = $hotel['name'];
                $hotel_data[$key]['new'] = isset($hotel_new_fans[$key])? $hotel_new_fans[$key]['total'] : 0;
                $hotel_data[$key]['self'] = isset($hotel_self_fans[$key])? $hotel_self_fans[$key]['total'] : 0;
                $hotel_data[$key]['dis'] = isset($dis_hotel_fans[$key])? $dis_hotel_fans[$key]['total'] : 0;
                $hotel_data[$key]['scan'] = isset($scan_hotel_fans[$key])? $scan_hotel_fans[$key]['total'] : 0;
                $hotel_data[$key]['cancel'] = isset($hotel_cancel_fans[$key])? $hotel_cancel_fans[$key]['total'] : 0;
            }
        }


        $head = array ('所属酒店','新增粉丝','自主关注','扫码关注','分销关注','取消关注');

        $ext_data = array();

        if(!empty($hotel_data)){
            foreach($hotel_data as $key=>$item){
                $temp[0]=$item['hotel_name'];
                $temp[1]=$item['new'];
                $temp[2]=$item['self'];
                $temp[3]=$item['dis'];
                $temp[4]=$item['scan'];
                $temp[5]=$item['cancel'];
                $ext_data[]=$temp;
            }

        }

        $ext_date = date('Y-m-d',time());

        $filename='';

        $filename = $filename.'酒店发展粉丝统计_'.$ext_date;

        $this->Excel_model->exp_exl($head,$ext_data,$filename);


    }


    public function ext_hotel_detail(){

        $data = $this->common_data;
        $this->load->model("wx/Fans_model");
        $this->load->model("hotel/Hotel_model");
        $this->load->model ( 'plugins/Excel_model');
        $inter_id = $this->inter_id;

//        $post = json_decode($this->input->raw_input_stream,true);
        $post = $this->input->get();
        $params = array();

        if(isset($post['hotel_id']) && !empty($post['hotel_id']))$params['hotel_id'] = $post['hotel_id'];
        if(isset($post['startdate']))$params['startdate'] = $post['startdate'];
        if(isset($post['enddate']))$params['enddate'] = $post['enddate'];

//        $params['startdate'] = '2015-08-01';
//        $params['enddate'] = '2017-08-12';

        $new = $this->Fans_model->dept_fans($inter_id,$params);
        $cancel = $this->Fans_model->dept_fans($inter_id,$params,2);
        $data['data'] = array();

        if(!empty($new)){
            foreach($new as $temp_new){
                if(!isset($data['data'][$temp_new['date']]['合计']['new']))$data['data'][$temp_new['date']]['合计']['new']=0;
                if(!isset($data['data'][$temp_new['date']]['合计']['cancel']))$data['data'][$temp_new['date']]['合计']['cancel']=0;
                if(!isset($data['data'][$temp_new['date']]['合计']['dept']))$data['data'][$temp_new['date']]['合计']['dept']='合计';
                $data['data'][$temp_new['date']][$temp_new['master_dept']] = array(
                    'new' => $temp_new['total'],
                    'dept' => $temp_new['master_dept'],
                    'cancel'=>0
                );
                $data['data'][$temp_new['date']]['合计']['new'] += $temp_new['total'];
            }
        }

        if(!empty($cancel)){
            foreach($cancel as $temp_cancel){
                if(!isset($data['data'][$temp_cancel['date']]['合计']['new']))$data['data'][$temp_cancel['date']]['合计']['new']=0;
                if(!isset($data['data'][$temp_cancel['date']]['合计']['cancel']))$data['data'][$temp_cancel['date']]['合计']['cancel']=0;
                if(!isset($data['data'][$temp_cancel['date']]['合计']['dept']))$data['data'][$temp_cancel['date']]['合计']['dept']='合计';
                $data['data'][$temp_cancel['date']][$temp_cancel['master_dept']] = array(
                    'cancel' => $temp_cancel['total'],
                    'dept' => $temp_cancel['master_dept']
                );
                if(!isset( $data['data'][$temp_cancel['date']][$temp_cancel['master_dept']]['new']))$data['data'][$temp_cancel['date']][$temp_cancel['master_dept']]['new'] =0;

                $data['data'][$temp_new['date']]['合计']['cancel'] += $temp_cancel['total'];
            }
        }



        $head = array ('日期','部门','分销关注','取消关注');

        $ext_data = array();

        if(!empty($data['data'])){
            foreach($data['data'] as $key=>$item){
                $temp[0]=$key;
                $temp[1]=$item['dept'];
                $temp[2]=$item['new'];
                $temp[3]=$item['cancel'];
                $ext_data[]=$temp;
            }

        }

        $ext_date = date('Y-m-d',time());

        $filename='';

        $filename = $filename.'各部门每日发展明细_'.$ext_date;

        $this->Excel_model->exp_exl($head,$ext_data,$filename);


    }



}
