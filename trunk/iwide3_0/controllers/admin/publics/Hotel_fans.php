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






}
