<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Club extends MY_Front {
    private static $theme;
	public $common_data = array();
	public $openid;


    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
        MYLOG::hotel_tracker($this->openid,  $this->inter_id);
        $this->load->model ( 'wx/Publics_model' );
        $this->load->model ( 'wx/Access_token_model' );
        $this->public = $this->Publics_model->get_public_by_id ( $this->inter_id );
        $this->common_data ['signPackage'] = $this->Access_token_model->getSignPackage ( $this->inter_id );
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
    }


    public function index()           //社群客首页
    {

        $this->_get_wx_userinfo();
        $staff_info=$this->check_club_staff($this->inter_id);    //验证社群客权限

        $model=$this->load->model('club/Clubs_model');
        $data = $this->common_data;
        $openid=$this->openid;
        $inter_id=$this->inter_id;
        $data['inter_id'] = $inter_id;

        $this->load->model('wx/Publics_model');
        $fans_info=$this->Publics_model->get_fans_info_one($inter_id,$openid);

//        $headImg=$fans_info['headimgurl'];    //用户头像地址
        $data['imgs']='';
        if(isset($fans_info['headimgurl']) && !empty($fans_info['headimgurl'])){
            $data['imgs']=$fans_info['headimgurl'];
        }

       if($staff_info){
           $data['name']=$staff_info['name'];
           $data['hotel_name']=$staff_info['hotel_name'];
           $data['qrcode_id']=$staff_info['qrcode_id'];

           if(isset($staff_info['source']) && $staff_info['source']!='normal'){
               $club_config = $this->Clubs_model->get_club_config($inter_id,$staff_info['source']);    //获取社群客配置
           }


       }else{
           $data['name']='';
           $data['hotel_name']='';
           $data['qrcode_id']='';
       }

        $res=$this->Clubs_model->check_club($openid);


        if($res&&isset($res['amount'])){
            $data['limited_amount']=$res['limited_amount'];
            $data['amount']=$res['amount'];
            $data['left']=$res['limited_amount']-$res['amount'];

            if(isset($club_config)){
                $club_config = json_decode($club_config['value']);
                if(isset($club_config->index)){
                    $this->display($club_config->index, $data);
                }else{
                    $this->display('club/index', $data);
                }
            }else{

                $this->display('club/index', $data);

            }

        }else{

            $is_multy=$this->Clubs_model->interIdMulty($inter_id);
            $data['is_multy']=$is_multy;

            $data['code']=1;
            $data['msg']='你的社群客已经失效，请与管理人员联系';

            $this->display( 'club/bind_status', $data );
        }

    }

    public function add_club(){             //新增社群客

        $this->_get_wx_userinfo();
        $hotel_staff_info=$this->check_club_staff($this->inter_id);

        $data = $this->common_data;

        $this->load->model('club/Clubs_model');

        $club_staff=$this->Clubs_model->check_club($this->openid);

        if(isset($club_staff)&&!empty($club_staff['club_price_code'])){
            $price_code_id=explode(',',$club_staff['club_price_code']);
        }

        if(isset($club_staff)&&!empty($club_staff['soma_code'])){
            $soma_code_id=explode(',',$club_staff['soma_code']);
        }

        $data['hotel_id']=$hotel_staff_info['hotel_id'];
        $data['hotel_name']=$hotel_staff_info['hotel_name'];

        if($hotel_staff_info){

            $hotel_price_code=$this->Clubs_model->get_hotel_protrol_price_codes($this->inter_id,$hotel_staff_info['hotel_id']);   //自己酒店的价格代码

            if($hotel_price_code){
                $hotel_code=array();
                foreach($hotel_price_code as $arr){
                    if(!empty($price_code_id)){
                        if(in_array($arr['price_code'],$price_code_id)){
                            $hotel_code[$arr['price_code']]=$arr['price_name'];
                        }
                    }
                }
                $data['price_code']=$hotel_code;
            }


            $all_price_code=$this->Clubs_model->get_all_price_codes($this->inter_id);  //集团的可用价格代码

            $inter_code=array();
            foreach($all_price_code as $all){
                if(!empty($price_code_id)){
                    if(in_array($all['price_code'],$price_code_id)){
                        $inter_code[$all['price_code']]=$all['price_name'];
                    }
                }
            }
            $data['all_price_code']=$inter_code;


            $data['soma_code']=[];
            $all_soma_code=$this->Clubs_model->get_soma_code($this->inter_id);  //可用的商城价格
            foreach($all_soma_code as $soma){
                if(!empty($soma_code_id)){
                    if(in_array($soma->id,$soma_code_id)){
                        $data['soma_code'][$soma->id]=$soma->name;
                    }
                }
            }



        }


        $club_config = $this->Clubs_model->get_club_config ( $this->inter_id,$club_staff['club_type']);

        if(isset($club_config)){
            $club_config = json_decode($club_config['value']);
            if(isset($club_config->add_club)){
                $add_club = $club_config->add_club;
            }else{
                $add_club = 'club/add_new';
            }
        }else{
            $add_club = 'club/add_new';
        }


        if($club_staff['club_type']=='normal'){

            $this->display('club/add_new', $data);

        }elseif($club_staff['club_type']=='friendship'){

//            $data['price_code'] = $price_code_id;

            $this->load->model('hotel/Hotel_config_model');
            $config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
                'NEW_CLUB_STAFF'
            ) );

            if(!empty($config_data['NEW_CLUB_STAFF'])){

                $config = json_decode($config_data['NEW_CLUB_STAFF']);

                if(isset($config->code)){
                    $data['price_code'] = explode(',',$config->code);
                }

                if(isset($config->starttime)){
                    $data['starttime'] = $config->starttime;
                }else{
                    $data['starttime'] = '2016-11-08';
                }

                if(isset($config->endtime)){
                    $data['endtime'] = $config->endtime;
                }else{
                    $data['endtime'] = '2017-11-07';
                }

                $data['starttime'] = str_replace('-','.',$data['starttime']);
                $data['endtime'] = str_replace('-','.',$data['endtime']);

            }

            $this->display($add_club, $data);

        }else{


            $this->display($add_club, $data);

        }


    }


    public function add_post($params=array()){    //提交添加社群客

        $this->_get_wx_userinfo();
        $return_info=array();
        $staff_info=$this->check_club_staff($this->inter_id);

        if($staff_info){

            $this->load->model('club/Clubs_model');

            $check=$this->Clubs_model->check_staff_validated($this->inter_id,$staff_info['qrcode_id']);

            if($check){

                $data = $this->common_data;

                if(!empty($params)){

                    $post_data = $params;

                    $post_data['qrcode_id']=$staff_info['qrcode_id'];
                    $post_data['openid']=$staff_info['openid'];
                    $status = $params['status'];


                }else{

                    $post_data = $this->input->post();

                    if(!empty($post_data['price_code'])){
                        $price_code_array =  json_decode($post_data['price_code']);
                        $post_data['price_code']= implode(',',$price_code_array);
                    }

                    if(empty($post_data['soma_code'])){
                        unset($post_data['soma_code']);
                    }


                    $post_data['qrcode_id']=$staff_info['qrcode_id'];
                    $post_data['openid']=$staff_info['openid'];


                    if(!isset($post_data['hotel_id']))$post_data['hotel_id']=0;

                    //银座的时间需要转换
                    if(isset($post_data['b_time'])){
                        $post_data['b_time']=str_replace('.','-',$post_data['b_time']);
                    }

                    if(isset($post_data['e_time'])){
                        $post_data['e_time']=str_replace('.','-',$post_data['e_time']);
                    }

                    $club_staff=$this->Clubs_model->check_club($this->openid);
                    $status = 0;

                    if(!empty($club_staff['auth_price_code']) && !empty($price_code_array)){
                        $status = 1;
                        $auth_price_code = explode(',',$club_staff['auth_price_code']);
                        foreach($price_code_array as $arr){
                            if(!in_array($arr,$auth_price_code)){
                                $status = 0;
                                continue;
                            }
                        }
                    }

                }

                $result=$this->Clubs_model->new_club_list($post_data,$this->inter_id,$post_data['hotel_id'],$status);

                if($result){

                    $return_info['left']=$result['limited_amount']-$result['amount'];
                    $return_info['limited_amount']=$result['limited_amount'];
                    $return_info['status']=$status;

                    if($status==1){    //免审核通过的发送模板消息
                        $this->load->model ( 'plugins/Template_msg_model' );
                        $params=array(
                            'openid'=>$this->openid,
                            'keyword2'=>date('Y-m-d H:i:s',time())
                        );
                        $this->Template_msg_model->hotel_club_templates ($this->inter_id,$params ,'hotel_club_auth' );
                    }

                    $return_info['info']="添加成功";
                    $return_info['code']=1;
                }

                echo json_encode($return_info);

            }else{

                $return_info["info"]="不能再添加，如有疑问请与管理人员联系";
                $return_info["code"]= 0;

                echo json_encode($return_info);

            }

        }else{
            $return_info["info"]="不能再添加，如有疑问请与管理人员联系";
            $return_info["code"]=0;

            echo json_encode($return_info);

        }
    }



    public function club_list(){   //社群客列表

        $this->_get_wx_userinfo();
        $staff_info=$this->check_club_staff($this->inter_id);

        $data = $this->common_data;
        $openid=$this->openid;
        $inter_id=$this->inter_id;

        $this->load->model('club/Clubs_model');

        $hotel_info=$this->Clubs_model->getHotelInfo($inter_id);
        $data['hotels']=$hotel_info['hotel_name'];
        $data['price_code']=$hotel_info['price_code'];

        $array_price_code = array_keys($data['price_code']);


        //商城可用代码
        $soma_code = $this->Clubs_model->get_soma_code($inter_id);
        if(!empty($soma_code)){
            foreach($soma_code as $temp_soma_code){
                $data['soma_code'][$temp_soma_code->id] = $temp_soma_code;
            }
            $array_soma_code = array_keys($data['soma_code']);
        }else{
            $data['soma_code'] = array();
            $array_soma_code = array();
        }


        $list=$this->Clubs_model->staff_club($openid);

        $clubs_id=array();

        if($list){
            foreach($list as $tmp_list){
                $clubs_id[]=$tmp_list['club_id'];
            }
            $club_valid=$this->Clubs_model->check_group_club_validated($inter_id,$clubs_id);    //验证社群客的有效性

            foreach($list as $key=>$arr){
                $arr_hotel_id=explode(',',$arr['hotel_id']);
                if($arr['hotel_id']==0 || empty($arr['hotel_id'])){
                    $list[$key]['mulity']=1;
                    $list[$key]['type']='all';
                }elseif(count($arr_hotel_id)<=1){
                    $list[$key]['mulity']=0;
                    $list[$key]['type']='one';
                }else{
                    $list[$key]['mulity']=1;
                    $list[$key]['type']='part';
                }

                $valid=isset($club_valid[$arr['club_id']]['valid'])?$club_valid[$arr['club_id']]['valid']:0;    //验证社群客的有效性
                $arr_price_codes = array();
                $arr_soma_codes = array();


                if(!empty($arr['price_code'])){
                    $arr_price_codes = explode(',',$arr['price_code']);
                    foreach($arr_price_codes as $arr_price_code){
                        if(!in_array($arr_price_code,$array_price_code)){
                            $price_code_status = 1;
                            continue;
                        }
                    }
                }

                if(!empty($arr['soma_code'])){
                    $arr_soma_codes = explode(',',$arr['soma_code']);
                    foreach($arr_soma_codes as $arr_soma_code){
                        if(!in_array($arr_soma_code,$array_soma_code)){
                            $soma_code_status = 1;
                            continue;
                        }
                    }
                }

                if(isset($price_code_status) || isset($soma_code_status))$valid=6;


                $list[$key]['valid']=$valid;
                $list[$key]['createtime']=date("Ymd",strtotime($list[$key]['create_time']));

                $list[$key]['arr_price_codes']=$arr_price_codes;
                $list[$key]['arr_soma_codes']=$arr_soma_codes;

            }

        //统计产生的间夜数量
            $clubs_csv=implode(',',$clubs_id);
            $club_orders=$this->Clubs_model->getClubOrders($inter_id,$clubs_csv,'all');
            $club_order = array();
            if(!empty($club_orders)){
                foreach($club_orders as $arr){
                    $time = strtotime($arr['enddate'])-strtotime($arr['startdate']);
                    $night = ceil($time/86400);
                    if(!isset($club_order[$arr['club_id']])){
                        $club_order[$arr['club_id']]=$night;
                    }else{
                        $club_order[$arr['club_id']]=$club_order[$arr['club_id']] + $night;
                    }
                }
            }

        //统计产生的间夜数量结束

            $data['count_order']=$club_order;
            $data['list']=$list;
        }else{
            $data['list']='';
        }

        $data['inter_id']=$inter_id;

        $group_list = 'club/group_list';

        if($staff_info['source']!='normal'){

            $club_config = $this->Clubs_model->get_club_config($this->inter_id,$staff_info['source']);    //获取社群客配置

            if(isset($club_config)){
                $club_config = json_decode($club_config['value']);
                if(isset($club_config->club_list)){
                    $group_list = $club_config->club_list;
                }
            }


            $p_member = $this->Clubs_model->get_all_club($this->inter_id);
            $count_p_mem = $this->Clubs_model->count_p_mem($this->inter_id);
            $count_all_customer = $this->Clubs_model->count_all_club_customer($this->inter_id);
            $count_all_club = $this->Clubs_model->count_club_list($this->inter_id);


            $data['p_mem'] = $p_member;
            $data['count_p_mem'] = $count_p_mem['total'];
            $data['count_all'] = $count_all_customer['total'];
            $data['count_all_list'] = $count_all_club['total'];

        }



        $this->display($group_list, $data);



    }


    public function scan_qrcode(){    //扫描二维码

        $this->_get_wx_userinfo();

        $data = $this->common_data;
        $openid=$this->openid;
        $inter_id=$this->inter_id;
        $data['inter_id']=$inter_id;

        $this->load->model('club/Clubs_model');

        if(isset($_GET['qid'])&&!empty($_GET['qid'])){     //扫描微信端二维码
            $qrcode_id=$_GET['qid'];
            $club_info=$this->Clubs_model->getClubByQrcode($inter_id,$qrcode_id);
            $club_id=$club_info['club_id'];
        }elseif(isset($_GET['cid'])&&!empty($_GET['cid'])){   //扫描后台二维码
            $club_id=$_GET['cid'];
            $club_info=$this->Clubs_model->club_info($inter_id,$club_id);
        }


        $bind_status = 'club/bind_status';

        //读取配置是否跳链接
        if(!empty($club_info)){
            if(!empty($club_info['link']) && $club_info['register']==2){
                $this->add_new_customer($club_info);
            }
        }


        //银座
        if(isset($club_info)){
            $club_staff= $club_staff=$this->Clubs_model->check_club($club_info['openid']);
            $club_config = $this->Clubs_model->get_club_config ( $this->inter_id,$club_staff['club_type']);

            if(isset($club_config)){
                $club_config = json_decode($club_config['value']);
                if(isset($club_config->bind_status)){
                    $bind_status = $club_config->bind_status;
                }
            }
        }


        $is_multy=$this->Clubs_model->interIdMulty($inter_id);

        $data['is_multy']=$is_multy;

        if(isset($club_id)&&!empty($club_id)){

            $club_customer=$this->Clubs_model->check_customer($openid,$club_id);
            if(!empty($club_customer)){
                if($club_customer['status']==1){

                   $data[ 'club_info']=$club_info;

                    $data['code']=0;
                    $data['msg']='你已经加入了该社群客';

                    $this->display( $bind_status, $data );

                }elseif($club_customer['status']==2){
                    $data['code']=1;
                    $data['msg']='你的社群客已经失效，请与管理人员联系';

                    $this->display( $bind_status, $data );
                }
                return $data;
            }else{

                $check=$this->Clubs_model->check_club_validated($club_id);

                if($check==0){

                    $data['code']=2;
                    $data['msg']='不存在该社群组织';

                    $this->display( $bind_status,$data);

                }elseif($check==1){

                    $data['code']=2;
                    $data['msg']='超过有效期';

                    $this->display( $bind_status, $data );

                }elseif($check==2){

                    $data['code']=2;
                    $data['msg']='超过人数';

                    $this->display($bind_status, $data );

                }elseif($check==3){  //可以加入

                    $data['code']=3;
                    $data['inter_id']=$inter_id;
                    $data['club_id']=$club_id;
                    $data['club_name']=$club_info['club_name'];
                    $data['valid_time'] = $club_info['valid_time'];
                    $data['price_name'] = '';
                    $data['soma_name']='';


                    if(!empty($club_info['price_code'])){
                        $price_codes = explode(',',$club_info['price_code']);
                        $price_name='';
                        foreach($price_codes as $price_code){
                            if(empty($price_name)){
                                $price_name = $this->Clubs_model->getPriceName($inter_id,$price_code);
                            }else{
                                $temp =$this->Clubs_model->getPriceName($inter_id,$price_code);
                                $price_name = $price_name.','.$temp;
                            }
                        }
                        $data['price_name']=$price_name;
                    }

                    if(!empty($club_info['soma_code'])){
                        $all_soma_codes = $this->Clubs_model->get_soma_code($inter_id);
                        if(!empty($all_soma_codes)){
                            foreach($all_soma_codes as $temp_soma_code){
                                $all_soma_code[$temp_soma_code->id] = $temp_soma_code;
                            }
                            $soma_codes = explode(',',$club_info['soma_code']);
                            $soma_name='';
                            foreach($soma_codes as $soma_code){
                                if(empty($soma_name)){
                                    $soma_name = $all_soma_code[$soma_code]->name;
                                }else{
                                    $temp =$all_soma_code[$soma_code]->name;
                                    $soma_name = $soma_name.','.$temp;
                                }
                            }
                            $data['soma_name']=$soma_name;
                        }
                    }


                    if(isset($club_config->bind)){
                        $this->display( $club_config->bind, $data );
                    }else{
                        $this->display( 'club/bind', $data );
                    }



                }elseif($check==4){

                    $data['code']=2;
                    $data['msg']='社群客有效期出错';

                    $this->display( 'club/bind_status', $data );

                }elseif($check==5){

                    $data['code']=2;
                    $data['msg']='未到有效期';

                    $this->display($bind_status, $data );

                }elseif($check==6){

                    $data['code']=2;
                    $data['msg']='价格代码失效';

                    $this->display($bind_status, $data );

                }

            }

        }else{

            $data['code']=2;
            $data['msg']='社群客出错';

            $this->display( 'club/bind_status', $data );

        }

    }


    public function show_qrcode_bg(){     //获取或者生成二维码

        $this->_get_wx_userinfo();
        $staff_info=$this->check_club_staff($this->inter_id);

        $data = $this->common_data;

        $this->load->model('club/Clubs_model');

        $get_data=$this->input->get();

        $club_id=$get_data['cid'];

        $img_url=$this->Clubs_model->getUrlByOpenid($this->openid); //获取所有自己开通的社群客

        $headimgurl=$this->Clubs_model->getHeadImg($this->openid);  //头像地址

        $club_info=$this->Clubs_model->club_info($this->inter_id,$club_id);  //社群客信息

//        $this->display('club/code', $data);

        if($club_info){

            if(!empty($club_info['img_url'])&&!empty($club_info['club_code'])){

                if(isset($img_url[$club_info['club_code']])&&!empty($img_url[$club_info['club_code']])){
                    $data['background_url']=$img_url[$club_info['club_code']];
                }else{
                    $data['code']=0;
                    $data['info']='获取出错，转发失效';
                }

            }else{


                $res=$this->new_qrcode($club_id,$headimgurl,$staff_info,$club_info);

                if($res){

                    $this->Clubs_model->update_qrcode_info($this->inter_id,$club_id,$res);

                    $data['background_url']=$res['img_url'];

                }else{
                    $data['code']=0;
                    $data['info']='生成新的二维码出错';
                }

            }

        }else{
            $data['code']=0;
            $data['info']='社群客出错';

        }

        $this->display('club/code_old', $data);

    }


    function add_club_result(){   //新增社群客结果

        $this->_get_wx_userinfo();

        $data= $this->input->get();

        if($data['code']==0){

            $this->display('club/fail', $data);

        }elseif($data['code']==1){

            $this->load->model('club/Clubs_model');

            $club_staff=$this->Clubs_model->check_club($this->openid);

            $link = 'club/submit_status';

           if($club_staff['club_type']=='friendship'){

               $club_config = $this->Clubs_model->get_club_config($this->inter_id,$club_staff['club_type']);    //获取社群客配置

               if(isset($club_config)){

                   $club_config = json_decode($club_config['value']);

                   if(isset($club_config->submit)){

                       $link = $club_config->submit;
                   }
               }

           }

            if(!isset($data['left'])) $data['left'] = 0;
            if(!isset($data['limited_amount'])) $data['limited_amount'] = 1;
            if(!isset($data['st']))$data['st']=1;


            $this->display($link, $data);

        }


    }


    function add_new_customer($params=array()){   //社群客增加新的成员

        $this->load->model('club/Clubs_model');
        $openid = $this->openid;
        $post=array();

        if(!empty($params)){
            $this->load->model('hotel/Member_model');
            $member_info = $this->Member_model->check_openid_member($params['inter_id'],$openid);

            if(!empty($member_info)){
                $post['name'] = $member_info->nickname;
                if(!empty($member_info->telephone)){
                    $post['tel'] = $member_info->telephone;
                }elseif(!empty($member_info->cellphone)){
                    $post['tel'] = $member_info->cellphone;
                }else{
                    $post['tel'] = ' ';
                }
                $post['cid'] = $params['club_id'];
                $club_customer=$this->Clubs_model->check_customer($openid,$post['cid']);  //再一次验证是否已经加入了该社群客
                $check=$this->Clubs_model->check_club_validated($post['cid']);
                if(!$club_customer && isset($check) && $check==3){
                    $this->Clubs_model->join_club($post,$this->inter_id,$this->openid);
                }

                $set = stripos($params['link'],'?');
                if($set){
                    redirect($params['link'].'&club_id='.$params['club_id']);
                }else{
                    redirect($params['link'].'?club_id='.$params['club_id']);
                }
            }
            return;
        }

        $post= $this->input->post();
//$this->openid = 'oz1AKv5xDYeeTBfwImfWZHP8QfyU';

        $club_id=$post['cid'];

        $club_customer=$this->Clubs_model->check_customer($openid,$club_id);  //再一次验证是否已经加入了该社群客

        if($club_customer){

            echo json_encode(2);exit();
        }

        $check=$this->Clubs_model->check_club_validated($club_id);

        if(isset($check)&&$check==3){

            $res=$this->Clubs_model->join_club($post,$this->inter_id,$this->openid);

            if($res){

                echo json_encode(1);

            }else{

                echo json_encode(2);
            }

        }else{

            echo json_encode(2);
        }

    }


    public function new_qrcode($club_id,$headimgurl,$staff_info,$club_info)
    {
        $inter_id= $this->inter_id;
        $this->load->model('club/Clubs_model','Clubs_model');

        $qrcode_info=$this->Clubs_model->get_wx_new_qrcode($inter_id,$club_info['club_name'],'社群客扫码',$staff_info['name'],$id=NULL);

        $url= $this->create_qrcode($qrcode_info['url'], false,$headimgurl,$club_id,$staff_info,$club_info);

        if($url){
            $url['qrcode_id']=$qrcode_info['qrcode_id'];
           return $url;
        }else{
            return false;
        }
    }


    function create_qrcode($url,$file_name = '',$headimgurl,$club_id,$staff_info,$club_info){   //旧的创建二维码海报

        set_time_limit(60);

        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $code = "";
        for ( $i = 0; $i < 20; $i++ )
        {
            $code .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        $random_nums=time().random(0,9999);

        $this->load->helper ( 'phpqrcode' );
        if(empty($file_name)){
            $this->load->helper('guid');
            $file_name = Guid::toString();
        }


        $head_img=$this->GrabImage($headimgurl.'.jpg','club_'.$this->inter_id.'_'.$club_id);   //下载头像

        sleep(5);

        QRcode::png($url,$random_nums.'.jpg','Q',15,1,true);   //生成二维码

        $this->add_headImg($head_img,"haibao.jpg");   //海报添加头像

        sleep(5);

        $this->add_qrcode($head_img,$random_nums.'.jpg');   //海报添加二维码

        $staff_name=$staff_info['name'];
        $text1='HI,我是'.$staff_name.'!';
        $this->add_text_logo($head_img,$text1);


        $club_name=$club_info['club_name'];
        $text2='邀请您加入'.$club_name.'社群客，享受更低的订房折扣价';
        $this->add_text_logo($head_img,$text2,30);

        $this->ftp= $this->_ftp_server('prod');
        $base_path= 'media/club/';

        $to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path;

        if(empty($to_file)){
            $this->ftp->mkdir($this->ftp->floder. FD_PUBLIC. '/'. $base_path);
        }

        $up_path = realpath('./').'/'.$head_img;
        $this->ftp->upload($up_path, $to_file.$file_name.'.jpg', 'binary', 0775);
        $this->ftp->close();

        unlink($head_img);
        unlink($random_nums.'.jpg');

        $res=array(
            'club_code'=>$code,
            'img_url'=>$this->ftp->weburl . '/' . FD_PUBLIC . '/' . $base_path . $file_name.'.jpg'
        );

        return $res;
    }




    function add_headImg($img,$logo){   //加头像水印

        if($logo){
            $this->load->helper ( 'phpqrcode' );
            $QR = imagecreatefromstring(file_get_contents($logo));
            $logo = imagecreatefromstring(file_get_contents($img));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            $from_height = $logo_height / 25;
            imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        imagepng($QR,$img);
    }


    function add_qrcode($img,$logo){   //加二维码
        if($logo){
            $this->load->helper ( 'phpqrcode' );
            $QR = imagecreatefromstring(file_get_contents($img));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 2;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $QR_height / 3;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            $from_height = $QR_height / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        imagepng($QR,$img);
    }


    public function add_text_logo($img,$text,$extra_height=0){   //文字水印
        if($img){

            $this->load->library('image_lib');

            $QR = imagecreatefromstring(file_get_contents($img));
            $QR_height = imagesy($QR);
            $height = ($QR_height / 4)-50;

            $config['image_library'] = 'gd2';//(必须)设置图像库
            $config['source_image'] = $img;//(必须)设置原图像的名字和路径. 路径必须是相对或绝对路径，但不能是URL.
            $config['dynamic_output'] = FALSE;//TRUE 动态的存在(直接向浏览器中以输出图像),FALSE 写入硬盘
            $config['quality'] = '90%';//设置图像的品质。品质越高，图像文件越大
            $config['new_image'] = $img;//设置图像的目标名/路径。

            $config['wm_type'] = 'text';//(必须)设置想要使用的水印处理类型(text, overlay)
            $config['wm_padding'] = '5';//图像相对位置(单位像素)
            $config['wm_vrt_alignment'] = 'top';//竖轴位置 top, middle, bottom
            $config['wm_hor_alignment'] = 'center';//横轴位置 left, center, right
            $config['wm_vrt_offset'] = $height+$extra_height;//指定一个垂直偏移量（以像素为单位）
            $config['wm_hor_offset'] = '0';//指定一个横向偏移量（以像素为单位）
            /* 文字水印参数设置 */
            $config['wm_text'] = $text;//(必须)水印的文字内容
            $config['wm_font_path'] = $_SERVER['DOCUMENT_ROOT'].'\public\club\fonts\msyh.ttc';//字体名字和路径
            $config['wm_font_size'] = '16';//(必须)文字大小
            $config['wm_font_color'] = '000000';//(必须)文字颜色，十六进制数
            $config['wm_shadow_distance'] = '3';//字体和投影距离（单位像素）。
            /* 图像水印参数设置 */

            $this->image_lib->initialize($config);

            $this->image_lib->watermark();

        }

    }


    public function GrabImage($url, $filename = "") {    //下载头像

        if ($url == "")
            return false;

        $ext = strrchr ( $url, "." );
        if ($filename == "") {
            $filename = date ( 'YmdHis' ) . rand ( 10, 99 ) . $ext;
        }
        $filename .= $ext;

//        ob_start ();
//        readfile ( $url );
//        $img = ob_get_contents ();
//        ob_end_clean ();

        $img=$this->curl_file_get_contents($url);
        $size = strlen ( $img );

        $fp2 = @fopen ( $filename, "a" );
        fwrite ( $fp2, $img );
        fclose ( $fp2 );

        return $filename;
    }


    public function show_hotels(){   //酒店明细

        $ids=$this->input->post();

        $ids=$ids['id'];

        $inter_id=$this->inter_id;

        $this->load->model('club/Clubs_model');

        $result=$this->Clubs_model->getHotesByIds($ids,$inter_id);

        $info=array();

        if($result){
            $info['code']=1;
            $info['info']=$result;
        }else{
            $info['code']=0;
        }

        echo json_encode($info);

    }




    public function _get_wx_userinfo()    //验证是否关注微信公众号
    {
            $this->load->model('wx/publics_model');

            $userinfo= $this->publics_model->get_wxuser_info($this->inter_id, $this->openid );

            if( isset($userinfo['subscribe']) && $userinfo['subscribe']==0 ){
                //微信返回的信息显示没有关注，则进行高级授权验证
                if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx' )
                    $refer =  'http://'. $_SERVER ['HTTP_HOST']. $_SERVER ['REQUEST_URI'] ;
                else
                    $refer =  'http://'. $_SERVER ['SERVER_NAME']. $_SERVER ['REQUEST_URI'] ;
                $inter_id= $this->inter_id;
                $refer= urlencode($refer);

                $this->load->model('club/Clubs_model');
                $page_url=$this->Clubs_model->follow_page($inter_id);

                if($page_url){
                    redirect($page_url);
                }

            } else {

                $this->publics_model->update_wxuser_info($this->inter_id, $this->openid );
                return $userinfo;
            }


    }


    public function check_club_staff($inter_id,$openid=''){    //验证社群客权限

        $this->load->model('club/Clubs_model');

        if(empty($openid)){
            $openid=$this->openid;
        }

        $staff_info=$this->Clubs_model->getHotelStaff($inter_id,$openid);

        if(empty($staff_info)||  $staff_info['is_club']==0){
            redirect(site_url('club/Club/processing').'?id='.$this->inter_id);
        }else{
            return $staff_info;
        }


    }

    function curl_file_get_contents($durl){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, '');
        curl_setopt($ch, CURLOPT_REFERER,'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }


    function processing(){    //审核中

        $data = $this->common_data;

        $data['inter_id']=$this->inter_id;

        $this->display('club/processing', $data);

    }


    public function show_qrcode(){     //获取或者生成二维码

        $this->_get_wx_userinfo();
        $staff_info=$this->check_club_staff($this->inter_id);

        $data = $this->common_data;

        $this->load->model('club/Clubs_model');

        $get_data=$this->input->get();

        $club_id=$get_data['cid'];

        $club_info=$this->Clubs_model->club_info($this->inter_id,$club_id);  //社群客信息

        if(!empty($club_info)){

            if(!empty($club_info['qrcode_id'])||$club_info['qrcode_id']!=0||$club_info['qrcode_id']!=NULL){
                $url=$this->Clubs_model->getQrcodeTicket($this->inter_id,$club_info['qrcode_id']);
            }else{
                $this->load->helper ( 'phpqrcode' );
//                $random_nums=time().random(0,9999);
                $qrcode_info=$this->Clubs_model->get_wx_new_qrcode($this->inter_id,$club_info['club_name'],'社群客扫码',$staff_info['name'],$id=NULL);
                $url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode_info['ticket'];

                if($url){
                    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
                    $code = "";
                    for ( $i = 0; $i < 20; $i++ )
                    {
                        $code.= $chars[ mt_rand(0, strlen($chars) - 1) ];
                    }
                    $update_data=array('qrcode_id'=>$qrcode_info['qrcode_id'],'club_code'=>$code);
                    $res=$this->Clubs_model->update_qrcode_info($this->inter_id,$club_id,$update_data);
                }
            }

//          QRcode::png($qrcode_info['url'],$random_nums.'.jpg','Q',15,1,true);   //生成二维码

            $data['qrcode_url']=base64_encode($this->curl_file_get_contents($url));

            if(!empty($club_info['club_code'])||$club_info['club_code']!=0||$club_info['club_code']!=NULL){
               $code=base64_encode($club_info['club_code']);
            }elseif(!empty($code)){
               $code=base64_encode($code);
            }


            redirect(site_url("club/club/my_qrcode?id=".$this->inter_id."&cc=".$code));

//            $this->display('club/code', $data);

        }else{

            $data['code']=0;
            $data['info']='社群客信息出错';

            $this->display('club/code_old', $data);
        }


    }


    function my_qrcode(){

//        $this->_get_wx_userinfo();

        $data = $this->common_data;

        $cc = $this->input->get('cc');
        $c_code = $this->input->get('code');
        $n_code = $this->input->get('ncode');

        if(!empty($cc)){
            $club_code=base64_decode($cc);
        }elseif(!empty($c_code)){
            $club_code=base64_decode($c_code);
        }elseif(!empty($n_code)){
            $club_code=base64_decode($n_code);
        }


        $this->load->model('club/Clubs_model');
        $this->load->model('wx/publics_model');

        $fans_info=$this->publics_model->get_fans_info($this->openid);
        if(!empty($fans_info) && isset($fans_info['nickname'])){
            $data['nickname']=$fans_info['nickname'];
        }


        $club_info=$this->Clubs_model->getClubByCode($this->inter_id,$club_code);


        if(!empty($club_info)){

            $data['club_name']=$club_info['club_name'];
            $data['valid_time']=$club_info['valid_time'];
            $data['public_name']=$this->Clubs_model->getPublicName($this->inter_id);

            $staff_info=$this->check_club_staff($this->inter_id,$club_info['openid']);

            $data['price_code']=$this->Clubs_model->get_all_price_codes($this->inter_id);
            $soma_code=$this->Clubs_model->get_soma_code($this->inter_id);
            if(!empty($soma_code)){
                foreach($soma_code as $arr){
                    $data['soma_code'][$arr->id] = $arr;
                }
            }else{
                $data['soma_code'] = [];
            }
//            if(isset($price_code[$club_info['price_code']])){
//                $data['price_code']=$price_code[$club_info['price_code']]['price_name'];
//            }

            if(!empty($club_info['qrcode_id'])||$club_info['qrcode_id']!=0||$club_info['qrcode_id']==NULL){
                $url=$this->Clubs_model->getQrcodeTicket($this->inter_id,$club_info['qrcode_id']);
            }else{
                $this->load->helper ( 'phpqrcode' );
                $random_nums=time().random(0,9999);
                $qrcode_info=$this->Clubs_model->get_wx_new_qrcode($this->inter_id,$club_info['club_name'],'社群客扫码',$staff_info['name'],$id=NULL);
                $url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode_info['ticket'];

                if($url){
                    $update_data=array('qrcode_id'=>$qrcode_info['qrcode_id']);
                    $res=$this->Clubs_model->update_qrcode_info($this->inter_id,$club_info['club_id'],$update_data);
                }
            }

            $data['qrcode_url']=base64_encode($this->curl_file_get_contents($url));

            $data['arr_price_code'] = [];
            $data['arr_soma_code'] = [];


            if(!empty($club_info['price_code']))$data['arr_price_code'] = explode(',',$club_info['price_code']);
            if(!empty($club_info['soma_code']))$data['arr_soma_code'] = explode(',',$club_info['soma_code']);


            $link = 'club/code';

            if($staff_info['source']!='normal'){

                $club_config = $this->Clubs_model->get_club_config($this->inter_id,$staff_info['source']);    //获取社群客配置
                if(isset($club_config)){
                    $club_config = json_decode($club_config['value']);
                    if(isset($club_config->my_qrcode)){
                        $link = $club_config->my_qrcode;
                    }
                }

            }

            $this->display($link, $data);



        }else{

            $data['code']=0;
            $data['info']='社群客信息出错';

            $this->display('club/code_old', $data);
        }

    }


    function club_customer_bg(){    //社群客成员列表

        $this->_get_wx_userinfo();

        $data = $this->common_data;

        $club_staff_info = $this->check_staff_club();

        $club_id=$this->input->get('club_id');
        $inter_id = $this->inter_id;
        $openid = $this->openid;

        $this->load->model('club/Clubs_model','Clubs_model');
        $club_info = $this->Clubs_model->club_info($inter_id,$club_id);

        if(isset($club_info['openid']) && $club_info['openid'] == $openid){

            $customers = $this->Clubs_model->getClubCustomers($inter_id,$club_id);
            if($customers){
                foreach($customers as $key=>$arr){
                    $customers[$key]['join_time']=data('Y.m.d',strtotime($arr['update_time']));
                }
            }

            $data['customers'] = $customers;
            $data['club_info'] = $club_info;

            $this->display('club/list', $data);

        }else{

            redirect(site_url('club/Club/club_list'));

        }

    }


    function club_customer(){    //社群客成员列表


        $data = $this->common_data;

        $info=array();

        $club_id=$this->input->post('club_id');
        $inter_id = $this->inter_id;

        $this->load->model('club/Clubs_model');
        $club_info = $this->Clubs_model->club_info($inter_id,$club_id);

        $customers = $this->Clubs_model->getClubCustomers($inter_id,$club_id);

        $info["info"]=$customers;
        $info["code"]=1;

        echo json_encode($info);

    }


    function club_order(){    //单个社群客已产生的间夜数

        $data = $this->common_data;

        $inter_id = $this->inter_id;
        $club_id = $this->input->get('cid');

//        $club_staff_info = $this->check_staff_club();

        if(!$club_id){
            redirect(site_url('club/Club/club_list').'?id='.$inter_id);
        }

        $this->load->model('club/Clubs_model');
        $this->load->model('hotel/Hotel_model');

        $type = $this->input->get('type');


        if(empty($type)|| $type==''){
            $type = 'D';
        }

        $data['type']=$type;
        $data['club_info']=$this->Clubs_model->club_info($inter_id,$club_id);

        $data['hotels'] = $this->Hotel_model->get_all_hotels($inter_id,NULL,'key');
        $data['orders']=$this->Clubs_model->getClubOrdersById($inter_id,$club_id,$type);

        if($data['orders']){
            foreach($data['orders'] as $key=>$orders){
                $data['orders'][$key]['night'] = $this->Clubs_model->count_night(array($orders));
            }
        }

        $data['count']=$this->Clubs_model->count_club_orders($inter_id,$club_id,'part');



        $this->display('club/club_income', $data);

    }


    function club_orders(){    //收益记录

        $data = $this->common_data;

        $inter_id = $this->inter_id;
        $openid = $this->openid;

//        $club_staff_info = $this->check_staff_club();

//$openid='oz1AKv5xDYeeTBfwImfWZHP8QfyU';

        $this->load->model('club/Clubs_model');
        $this->load->model('hotel/Hotel_model');

        $type = $this->input->get('type');

        if(empty($type)||$type==''){
            $type = 'D';
        }

        $data['type']=$type;

        $data['hotels'] = $this->Hotel_model->get_all_hotels($inter_id,NULL,'key');

        $club_list=$this->Clubs_model->staff_club($openid);

        if($club_list){
            $clubs_id=array();

            $time = $this->input->get('t');

            if(!$time){
                $time ='';
            }else{
                $data['time'] = date('Y年m月',($time+86400));
            }

            foreach($club_list as $arr){
                $clubs_id[]=$arr['club_id'];
            }
            $clubs_csv=implode(',',$clubs_id);
            $data['orders']=$this->Clubs_model->getClubOrders($inter_id,$clubs_csv,$type,$time);
            if($data['orders']){
                foreach($data['orders'] as $key=>$orders){
                    $data['orders'][$key]['night'] = $this->Clubs_model->count_night(array($orders));
                }
            }
            $data['count']=$this->Clubs_model->count_club_orders($inter_id,$clubs_csv,'all');
        }

        $this->display('club/income_history', $data);

    }


    function check_staff_club(){   //验证社群客的归属与权限

        $club_id = $this->input->get('club_id');
        $inter_id = $this->inter_id;
        if($club_id){
            $this->load->model('club/Clubs_model');
            $club_info = $this->Clubs_model->club_info($inter_id,$club_id);
            $openid = $this->openid;
            if(isset($club_info)&&$club_info['openid']==$openid){
                return $club_info;
            }else{
                redirect(site_url('club/Club/index').'?id='.$inter_id);
            }
        }else{
            redirect(site_url('club/Club/index').'?id='.$inter_id);
        }

    }


    function qa_help(){  //Q&A

        $data = $this->common_data;
        $this->display('club/help', $data);

    }


    function ranking(){     //社群客琅琊榜

        $data = $this->common_data;
        $inter_id = $this->inter_id;
        $openid = $this->openid;

        $type=$this->input->get('type');

        if(empty($type)){
            $type="day";
        }

        $this->load->model('club/Clubs_model');

        $order_info=$this->Clubs_model->get_club_dist_orders($inter_id,$type); //公众号下所有订单

        $staff = $this->Clubs_model->getAllClubStaff($inter_id);  //公众号下所有社群客销售员

        $allClub = $this->Clubs_model->getAllClub($inter_id);   //公众号下所有社群客

        $saler_club_grade = $this->Clubs_model->get_club_grade_ext($inter_id,$type);


        foreach($staff as $key=>$staff_arr){

            if(isset($allClub[$staff_arr['qrcode_id']])){

                $room_nights[$staff_arr['qrcode_id']]=0;

                foreach($allClub[$staff_arr['qrcode_id']] as $club_id){

                    if(isset($order_info['count'][$club_id])){

                        $room_nights[$staff_arr['qrcode_id']]=$room_nights[$staff_arr['qrcode_id']]+$order_info['count'][$club_id];

                    }
                }
                $staff[$key]['total']=$room_nights[$staff_arr['qrcode_id']];

            }else{

                $staff[$key]['total']=0;

            }


            if(isset($saler_club_grade[$staff_arr['qrcode_id']])){
                $staff[$key]['grade']=$saler_club_grade[$staff_arr['qrcode_id']]['grade_total'];
            }else{
                $staff[$key]['grade']=0;
            }
        }

        $rank = $this->Clubs_model->my_sort($staff,'grade',SORT_DESC);

        if($rank){
            $data['rank']=$rank;
            $data['type']=$type;
            foreach($rank as $num=>$my_rank){
                if($my_rank['openid']==$openid){
                    $my_info = $my_rank;
                    $my_info['rank'] = $num;
                }
            }
            $data['my_rank']=$my_info;
        }

        $this->display('club/rank', $data);


    }



    function income_list(){    //社群客收益记录

        $data = $this->common_data;
        $openid = $this->openid;
        $inter_id = $this->inter_id;

        $this->load->model('club/Clubs_model');

        $res=$this->Clubs_model->getNearYear(12);

        $list=$this->Clubs_model->staff_club($openid);

        if($list){
            foreach($list as $key=>$arr){
                $clubs_id[]=$list[$key]['club_id'];
            }

            $clubs_csv=implode(',',$clubs_id);
            $orders=$this->Clubs_model->getClubOrders($inter_id,$clubs_csv,'all');

            if($orders){
                foreach($res as $key=>$month){
                    foreach($orders as $order){
                        if($key==0){
                            if(strtotime($order['startdate'])>=$month['info']){
                                    $total = $this->Clubs_model->count_night(array($order));
                                    $res[$key]['count']=$res[$key]['count']+$total;
                            }
                        }elseif($key>0){
                            if(strtotime($order['startdate'])>=$month['info'] && strtotime($order['startdate']) < $res[$key-1]['info']){
                                $total = $this->Clubs_model->count_night(array($order));
                                    $res[$key]['count']=$res[$key]['count']+$total;
                            }
                        }
                    }
                }
            }

            $club_staff_info = $this->Clubs_model->check_club($openid);

            $data['join_time'] = strtotime($club_staff_info['create_time']);
            $data['list']=$res;

        }

        $this->display('club/income_list', $data);

    }



    function reg(){   //申请分销员

        $this->load->model('hotel/Hotel_config_model');
        $config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
            'NEW_CLUB_STAFF'
        ) );

        if(!empty($config_data['NEW_CLUB_STAFF'])){

            $config = json_decode($config_data['NEW_CLUB_STAFF']);

            if(isset($config->status) && $config->status==1){

                $this->load->model('distribute/staff_model');
                $saler_info = $this->staff_model->saler_info($this->openid,$this->inter_id);

                $this->load->model('hotel/Hotel_model');
//                $this->datas ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id );
                $this->datas ['inter_id'] = $this->inter_id;

                if($saler_info && $saler_info['status'] == 2){
                    redirect(site_url('club/club/index').'?id='.$this->inter_id);
                }elseif($saler_info && $saler_info['status'] != 2){
                    redirect(site_url('distribute/distribute/processing').'?id='.$this->inter_id);
                }else{

                    $key = $this->input->get('m');
                    $this->load->model('club/Clubs_model');
                    $key = addslashes($key);

                    if(isset($key) && $key =='friendship'){

                        $amount = $this->Clubs_model->count_clubs_by_type($this->inter_id,$key);
                        if($amount >= $config->friendship ){   //开通超过限制
                            redirect ( site_url ( 'hotel/hotel/search' ) . '?id=' . $this->inter_id );
                        }

                        $this->datas ['status'] = 2;
                        $this->datas ['source'] = 'friendship';
                        $this->datas ['hidden'] = 1;
                        $this->datas ['is_club'] = 1;


                    }else{

                        $this->datas ['status'] = 1;
                        $this->datas ['source'] = 'share';
                        $this->datas ['hidden'] = 0;
                        $this->datas ['is_club'] = 0;

                    }


                    $p_member = $this->Clubs_model->get_all_club($this->inter_id);
                    $count_p_mem = $this->Clubs_model->count_p_mem($this->inter_id);
                    $count_all_customer = $this->Clubs_model->count_all_club_customer($this->inter_id);
                    $count_all_club = $this->Clubs_model->count_club_list($this->inter_id);


                    $this->datas['p_mem'] = $p_member;
                    $this->datas['count_p_mem'] = $count_p_mem['total'];
                    $this->datas['count_all'] = $count_all_customer['total'];
                    $this->datas['count_all_list'] = $count_all_club['total'];


                    $this->load->model('distribute/distribute_model');
                    $this->load->view('club/default/header');
                    $this->load->view('club/default/new_saler',$this->datas);

                }


            }else{   //后台数据库没有配置

                redirect ( site_url ( 'hotel/hotel/search' ) . '?id=' . $this->inter_id );

            }

        }else{  //后台数据库没有配置

            redirect ( site_url ( 'hotel/hotel/search' ) . '?id=' . $this->inter_id );

        }
    }


    function do_reg(){
        $this->save_register();
//        if($this->save_register()){
//            echo json_encode(array('errmsg'=>'ok','message'=>'信息提交成功'));
//        }else{
//            echo json_encode(array('errmsg'=>'faild','message'=>'信息提交失败'));
//        }
    }



    function save_register(){

        $this->load->model('club/Clubs_model');
        $club_config = $this->Clubs_model->get_club_config($this->inter_id,'normal',1,'default_staff');

        if(!empty($club_config)){    //雅斯特根据会员等级进行新增社群客

            $this->load->model ( 'hotel/Member_model' );
            $member_info = $this->Member_model->check_openid_member($this->inter_id,$this->openid);


            if(empty($member_info->nickname)){
                $name = $member_info->name;
            }else{
                $name = $member_info->nickname;
            }

            $params = $this->input->post();

            if(!empty($params['hotel_id'])){
                $data['hotel_id'] =  $params['hotel_id'];
            }


            $data['name']        = $name;
            $data['cellphone']   = $member_info->telephone;
            $data['openid']      = $this->openid;
            $data['inter_id']    = $this->inter_id;
            $data['status_time'] = date('Y-m-d H:i:s');
            $data['status']      = 2;//默认申请状态
            $data['source']      = 'normal';
            $data['is_club']      = 1;
            $data['is_distributed']      = 1;
            $data['distribute_hidden']    = 1;

            $club_price_code = $this->input->post('price_code');
            $is_grade = 1;

        }else{     //银座读取规定的社群客配置进行新增

            $this->load->model('hotel/Hotel_config_model');
            $config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
                'NEW_CLUB_STAFF'
            ) );

            if(!empty($config_data['NEW_CLUB_STAFF'])){

                $config = json_decode($config_data['NEW_CLUB_STAFF']);

                if(isset($config->code)){
                    $club_price_code = $config->code;
                }
            }

            $data['name']        = trim($this->input->post('name',true));
            $data['cellphone']   = trim($this->input->post('cellphone',true));
            $data['openid']      = $this->openid;
            $data['inter_id']    = $this->session->userdata('inter_id');
            $data['status_time'] = date('Y-m-d H:i:s');
            $data['status']      = trim($this->input->post('status',true));//默认申请状态
            $data['source']      = trim($this->input->post('source',true));
            $data['is_club']      = trim($this->input->post('is_club',true));
            $data['distribute_hidden']      = trim($this->input->post('hidden',true));

            $is_grade = 0;

        }

            //Prevent double insertion
        $this->load->model ( 'distribute/Staff_model' );
        $this->load->model ( 'club/Club_model' );
        $this->db->where(array('inter_id'=>$this->inter_id,'openid'=>$data['openid']));
        $this->db->limit(1);
        $query = $this->db->get('hotel_staff')->num_rows();
        if($query < 1){
            if($this->db->insert('hotel_staff',$data) > 0){
                $id = $this->db->insert_id();
                $qrcode_id = $this->Staff_model->get_qr_code($data['inter_id'],$data['name'],'','');
                $this->db->where(array('id'=>$id));
                $this->db->update('hotel_staff',array('qrcode_id'=>$qrcode_id));
                $this->Staff_model->save_staff_to_saler($data['inter_id'],$qrcode_id);

                $post_str=array(
                    'inter_id'=>$data['inter_id'],
                    'qrcode_id'=>$qrcode_id,
                    'openid'=>$data['openid'],
                    'name'=>$data['name'],
                    'is_grade'=>$is_grade,
                    'club_price_code'=>$club_price_code,
                    'limited_amount'=>1,
                    'club_type'=>$data['source']
                );

                $this->Club_model->add_club($post_str,1);

                if(!empty($club_config)){

                    $params['hotel_id'] = 0;
                    $params['status'] = 1;
                    $params['price_code'] = $club_price_code;

                    $new_club = $this->add_post($params);

                    return $new_club;

                }else{
                    return $this->db->insert_id();
                }

            }else{
                return false;
            }
        }else{
            return false;
        }

    }


    public  function reg_club(){

        $data = $this->common_data;

        $config_id = $this->input->get('cid');
        $inter_id = $this->inter_id;
        $openid = $this->openid;

        $this->load->model ( 'club/Clubs_model' );

        $check_hotel_staff = $this->Clubs_model->getHotelStaff($inter_id,$openid);
        $check_club_staff = $this->Clubs_model->check_club($openid);
        $check_club = $this->Clubs_model->staff_club($openid);


        if($check_hotel_staff || $check_club_staff || $check_club){    //验证时候已经是分销员或者社群客销售员或者已经开通社群客
            redirect(site_url('membervip/center/member_center').'?id='.$inter_id);
        }

        $this->load->model ( 'hotel/Member_model' );
        $member_info = $this->Member_model->check_openid_member($inter_id,$this->openid);

//$member_info->lvl_pms_code = 1;

        if(empty($config_id) || empty($member_info)){     //验证是否有社群客配置或者是否为会员
            redirect(site_url('membervip/center/member_center').'?id='.$inter_id);
        }

        $this->load->model ( 'club/Clubs_model' );

        $config = $this->Clubs_model->getClubConfigById($inter_id,$config_id);

        if(empty($config)){
            redirect(site_url('membervip/center/member_center').'?id='.$inter_id);
//            redirect(site_url('membervip/center').'?id='.$inter_id);
        }


//$member_info->lvl_pms_code = 1;

        $config = json_decode($config['value']);
        $res = $this->Clubs_model->match_club_config($config,$member_info->member_lvl_id);

        if(empty($res)){         //会员等级没有对应的社群客配置
            redirect(site_url('membervip/center/member_center').'?id='.$inter_id);
//            redirect(site_url('membervip/center').'?id='.$inter_id);
        }


        $data['all_price_code']=$this->Clubs_model->get_all_price_codes($this->inter_id);  //集团的可用价格代码

        $data['start'] = date("Y-m-d",time());
        $data['end'] = date("Y-m-d",time()+ 86400 * $res->days);
        $data['amount'] =  $res->amount;
        $data['price_code'] =  $res->price_code;
        $data['hotel_id'] =  $res->hotel_id;


        $this->display('club/club_reg', $data);

    }


    public function  progress($member_code=''){

//        $member_code ='';

        $this->load->model ( 'hotel/user/User_notify_model' );
        $this->User_notify_model->add_queue ( 'a234234234', '1', 'club_member_levelup', array (
            'ex_data' => array (
                'to_lv' => 111, //升到的级别
                'ori_lv' => 110 //原有的级别
            ),
            'sub_ident' => '1'
        ), FALSE );

        exit;



        $this->load->model ( 'club/Clubs_model' );
        $inter_id = $this->inter_id;
        $openid = $this->openid;

        $this->load->model ( 'hotel/Member_model' );
        $member_info = $this->Member_model->check_openid_member($inter_id,$this->openid);


        $res = $this->Clubs_model->upgrade_club_queue('a429262687','oGClOuOOfVn8zrlpahOAGUyKjOqM','',1,$member_info->member_info_id);

        var_dump($res);

//        $res = $this->Clubs_model->upgrade_club_queue($inter_id,$openid,$member_code);


    }


}







