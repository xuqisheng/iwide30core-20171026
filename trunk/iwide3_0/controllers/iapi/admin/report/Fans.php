<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Fans extends MY_Admin_Iapi {
    protected $label_module = NAV_HOTEL;
    protected $label_controller = '粉丝分析';
    protected $label_action = '';
    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->get_admin_inter_id ();
        $this->module = 'hotel';
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
        // $this->output->enable_profiler ( true );
    }
    protected function main_model_name() {
        return 'wx/Fans_model';
    }


    public function fans_report(){

        $data = $this->common_data;
        $this->load->model("wx/Fans_model");
        $this->load->model("hotel/Hotel_model");
        $hotels = $this->Hotel_model->get_all_hotels($this->inter_id,null,'key');
        $params = array();
        $data['new_total'] = 0;
        $data['self_total'] = 0;
        $data['scan_total'] = 0;
        $data['dis_total'] = 0;
        $data['cancel_total'] = 0;
        $data['date_data'] = array();
        $data['hotel_data'] = array();
        $data['percentage'] = 0;

        $get = $this->input->get();

        if(isset($get['hotel_id']) && !empty($get['hotel_id']))$params['hotel_id'] = $get['hotel_id'];

        $data['total'] = $this->Fans_model->total_fans($this->inter_id,$params); //累计粉丝

        if(!isset($get['startdate']) || !isset($get['enddate'])){
            $get['startdate'] = date('Y-m-d' , strtotime("-2 day"));;
            $get['enddate'] = date('Y-m-d' , time());;
        }

        $params['startdate'] = $get['startdate'];
        $params['enddate'] = $get['enddate'];


        $dt_start = strtotime($params['startdate']);
        $dt_end = strtotime($params['enddate']);
        while ($dt_start<=$dt_end){
            $each_day[date('Y-m-d',$dt_start)] = array('date'=>date('Y-m-d',$dt_start));
            $dt_start = strtotime('+1 day',$dt_start);
        }


        $params['cur_status'] = 1;
        $time_total = $this->Fans_model->total_fans($this->inter_id,$params);   //筛选条件后总粉丝


        $date_new_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'date');  //按日期统计新增
        $hotel_new_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'hotel');//按酒店统计新增

        $date_self_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'date',-1);  //按日期统计自主关注
        $hotel_self_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'hotel',-1);  //按酒店统计自主关注

        $dis_date_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'date');   //按日期统计分销
        $dis_hotel_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'hotel');   //按酒店统计分销

        $scan_date_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'date',2);   //按日期统计扫码关注
        $scan_hotel_fans = $this->Fans_model->dis_fans($this->inter_id,$params,'hotel',2);   //按酒店统计扫码关注

        $params['cur_status'] = 2;
        $date_cancel_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'date');  //按日期统计取消
        $hotel_cancel_fans = $this->Fans_model->count_con_fans($this->inter_id,$params,'hotel');//按酒店统计取消


        $days = round((strtotime($params['enddate'])-strtotime($params['startdate']))/86400);
        $o_startdate = date('Y-m-d',strtotime($params['startdate'])-($days+1)*86400);
        $o_enddate = date('Y-m-d',strtotime($params['enddate'])-($days+1)*86400);

        $params['cur_status'] = 1;
        $params['startdate'] = $o_startdate;
        $params['enddate'] = $o_enddate;

        $o_total = $this->Fans_model->total_fans($this->inter_id,$params);   //环比总粉丝


        if(!empty($each_day)){
            foreach($each_day as $key => $e_day){
                $each_day[$key]['new'] = isset($date_new_fans[$key])? $date_new_fans[$key]['total'] : 0;
                $each_day[$key]['self'] = isset($date_self_fans[$key])? $date_self_fans[$key]['total'] : 0;
                $each_day[$key]['dis'] = isset($dis_date_fans[$key])? $dis_date_fans[$key]['total'] : 0;
                $each_day[$key]['scan'] = isset($scan_date_fans[$key])? $scan_date_fans[$key]['total'] : 0;
                $each_day[$key]['cancel'] = isset($date_cancel_fans[$key])? $date_cancel_fans[$key]['total'] : 0;

                $data['new_total'] += $each_day[$key]['new'];
                $data['self_total'] += $each_day[$key]['self'];
                $data['scan_total'] += $each_day[$key]['scan'];
                $data['dis_total'] += $each_day[$key]['dis'];
                $data['cancel_total'] += $each_day[$key]['cancel'];
            }
        }

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

        $data['hotel_data'] = $hotel_data;
        $data['date_data'] = $each_day;

        if($o_total['total'] !=0 && $data['new_total'] !='0'){
            $data['percentage'] = ($data['new_total'] - $o_total['total'])/$o_total['total']*100;
        }

        $data['date_data_link'] = site_url('publics/hotel_fans/ext_date_data');
        $data['hotel_data_link'] = site_url('publics/hotel_fans/ext_hotel_data');
        $data['dept_data_link'] = site_url('publics/hotel_fans/ext_hotel_detail');


        $this->out_put_msg(1,'',$data,'report/fans/fans_report',200);

    }


    public function hotel_detail_data(){

        $data = $this->common_data;
        $this->load->model("wx/Fans_model");
        $inter_id = $this->inter_id;

        $get = $this->input->get();
        $params = array();

        if(isset($get['hotel_id']) && !empty($get['hotel_id']))$params['hotel_id'] = $get['hotel_id'];
        if(isset($get['startdate']))$params['startdate'] = $get['startdate'];
        if(isset($get['enddate']))$params['enddate'] = $get['enddate'];

        if(!isset($params['startdate']) || !isset($params['enddate'])){
            $params['startdate'] = date('Y-m-d' , strtotime("-2 day"));;
            $params['enddate'] = date('Y-m-d' , time());;
        }

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

        $this->out_put_msg(1,'',$data,'report/fans/hotel_detail_data',200);

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

        $post = json_decode($this->input->raw_input_stream,true);
        if(isset($post['hotel_id']) && !empty($get['hotel_id']))$params['hotel_id'] = $post['hotel_id'];


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

        $post = json_decode($this->input->raw_input_stream,true);

        if(isset($post['hotel_id']) && !empty($get['hotel_id']))$params['hotel_id'] = $post['hotel_id'];

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

        $post = json_decode($this->input->raw_input_stream,true);
        $params = array();

        if(isset($post['hotel_id']) && !empty($get['hotel_id']))$params['hotel_id'] = $post['hotel_id'];
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


    public function wx_article_total(){

        $data = $this->common_data;
        $this->load->model("wx/Fans_model");
        $this->load->helper ( 'common' );
        $this->load->model ( 'wx/Access_token_model' );
        $inter_id = $this->inter_id;
        $access_token = $this->Access_token_model->get_access_token ( $inter_id );
        $date = array();
        $return_info = array();

        $startdate = $this->input->get('startdate');
        $enddate = $this->input->get('enddate');

        if(!empty($startdate) && !empty($enddate)){
            $get_total = $this->Fans_model->getarticlesummary($inter_id);
            for($i=0;strtotime($startdate.'+'.$i.' days')<=strtotime($enddate);$i++){
                $time = strtotime($startdate.'+'.$i.' days');
                if($time<time()-86400)$date[] = date('Y-m-d',$time);
            }

            if(!empty($date)){
                foreach($date as $arr){
                    if(!isset($get_total[$arr])){
                        $post_data = array(
                            'begin_date'=>$arr,
                            'end_date'=>$arr
                        );
                        $url = 'https://api.weixin.qq.com/datacube/getarticletotal?access_token='.$access_token;
                        $usertotal = json_decode(doCurlPostRequest($url,json_encode($post_data)));
                        if(isset($usertotal->errcode) && ($usertotal->errcode == '40001' || $usertotal->errcode == '42001')){
                            $access_token = $this->Access_token_model->reflash_access_token ( $inter_id );
                            $usertotal = json_decode(doCurlPostRequest($url,json_encode($post_data)));
                        }
                        if(isset($usertotal->list) && !empty($usertotal->list)){
                            $get_total[$arr] = json_encode($usertotal->list);
                            $this->Fans_model->setarticlesummary($inter_id,$arr,$usertotal->list);
                        }else{
                            $get_total[$arr] = '';
                        }

                    }
                }
            }

            if(!empty($get_total)){
                foreach($get_total as $date_key => $count){
                    if(strtotime($date_key) >= strtotime($startdate) && strtotime($date_key) <= strtotime($enddate)){
                        if(!empty($count) && $count!='null'){
                            foreach(json_decode($count) as $count_detail){
                                $len = count($count_detail->details);
                                if(strtotime($enddate)>=strtotime($count_detail->details[$len-1]->stat_date)){
                                    $temp_data = array(
                                        'title' => $count_detail->title,
                                        'send_date' => $count_detail->ref_date,
                                        'target_user' => $count_detail->details[$len-1]->target_user,
                                        'int_page_read_user' => $count_detail->details[$len-1]->int_page_read_user,
                                        'ori_page_read_user' => $count_detail->details[$len-1]->ori_page_read_user,
                                        'share_user' => $count_detail->details[$len-1]->share_user,
                                        'int_page_from_feed_read_user' => $count_detail->details[$len-1]->int_page_from_feed_read_user,
                                        'int_page_from_friends_read_user' => $count_detail->details[$len-1]->int_page_from_friends_read_user
                                    );
                                    $return_info[] = $temp_data;
                                }else{
                                    foreach($count_detail->details as $temp_detail){
                                        if($temp_detail->stat_date == $enddate){
                                            $temp_data = array(
                                                'title' => $count_detail->title,
                                                'send_date' => $count_detail->ref_date,
                                                'target_user' => $temp_detail->target_user,
                                                'int_page_read_user' => $temp_detail->int_page_read_user,
                                                'ori_page_read_user' => $temp_detail->ori_page_read_user,
                                                'share_user' => $temp_detail->share_user,
                                                'int_page_from_feed_read_user' => $temp_detail->int_page_from_feed_read_user,
                                                'int_page_from_friends_read_user' => $temp_detail->int_page_from_friends_read_user
                                            );
                                            $return_info[] = $temp_data;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $data['return_info'] = $return_info;

        $this->out_put_msg(1,'',$data,'report/fans/wx_article_total',200);

    }

}
