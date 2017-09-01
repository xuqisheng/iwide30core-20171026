<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groupon extends MY_Front_Soma {

    public  $themeConfig;
    public  $theme = 'default';

    public function __construct()
    {
        parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];

        $ticketId = '';
        if( $this->session->userdata('tkid') )
        {
            $ticketId = $this->session->userdata('tkid');
        }

        //门票皮肤没有详情页，先默认为v1皮肤的。ticket有自己的header头
        $themeArr = array(
            'ticket',
            'zongzi',
        );
        if( in_array( $this->theme, $themeArr ) || $ticketId )
        {
            $this->theme = 'v1';
        }
    }

    public function index(){

    }


    //**具体团单**//
    public function groupon_detail(){

        $groupId = intval($this->input->get('grid'));

        if(empty($groupId))
            return false;


        $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
        $activityGrouponModel = $this->activityGrouponModel;

        $gouponDetial = $activityGrouponModel->groupon_group_detail($groupId); //团单信息

        //验证是否有此团
        if(empty($gouponDetial)){
            $errMsg =  $activityGrouponModel
                        ->get_validate_msg();
            echo $errMsg[$activityGrouponModel::GROUP_NO_TEXIST];
            redirect(Soma_const_url::inst()->get_pacakge_home_page());
            die;
        }
        $users = $activityGrouponModel->groupon_group_users($groupId,$activityGrouponModel::GROUP_ADD_STATUS_SUCCESS);      //团单用户
        $activityInfo = $activityGrouponModel->groupon_detail($gouponDetial['act_id']);


        $inGroup = false; //判断自己是否在此团
        $validateCount = count($users);
        foreach($users as $v){
            if($v['openid'] == $this->openid){
                $inGroup = true;
            }
        }


        $this->load->helper('soma/time_calculate');
        $timeLeft = time_left($gouponDetial['deadline']);

        $msgArray = $activityGrouponModel->get_group_status_msg();
        $restNum = $activityInfo['group_count'] - $validateCount;

        if($gouponDetial['status'] == $activityGrouponModel::GROUP_STATUS_FINISHED ){ //拼团成功

            $groupStatusMsg = $msgArray[$activityGrouponModel::GROUP_STATUS_FINISHED];

        }elseif($gouponDetial['status'] == $activityGrouponModel::GROUP_STATUS_ING){ //拼团进行中

            $groupStatusMsg =  $msgArray[$activityGrouponModel::GROUP_STATUS_ING];
            if($inGroup){
                $msgArray = $activityGrouponModel->get_group_status_msg($restNum);
                $groupStatusMsg =  $msgArray[$activityGrouponModel::GROUP_STATUS_ING];
            }

        }else{

            $groupStatusMsg = $msgArray[$activityGrouponModel::GROUP_STATUS_FAILED];
        }

        //$groupStatusMsg =
        $this->load->model('soma/Product_package_model','productPackageModel');
        $packageDetail = $this->productPackageModel->get_product_package_detail_by_product_id($activityInfo['product_id'], $this->inter_id);
//        print_r($packageDetail);
//        print_r($activityInfo);
//        print_r($gouponDetial);
//        print_r($users);
//        exit;

        if($timeLeft <= 0 && $gouponDetial['status'] != $activityGrouponModel::GROUP_STATUS_FINISHED)
            $gouponDetial['status'] = $activityGrouponModel::GROUP_STATUS_FAILED;

        $this->datas['inGroup'] = $inGroup;
        $this->datas['timeLeft'] = $timeLeft;
        $this->datas['activityInfo'] = $activityInfo;
        $this->datas['packageDetail'] = $packageDetail;
        $this->datas['gouponDetial'] = $gouponDetial;
        $this->datas['users'] = $users;
        $this->datas['statusMsg'] = $groupStatusMsg;
        $this->datas['restNum'] = $restNum;


        //分享
//        $this->share['title'] = $this->topic['share_title'];
//        $this->share['link']  = $this->topic['share_link'];
//        $this->share['imgUrl']= $this->_get_domain(). $this->topic['share_img'];
//        $this->share['desc']  = $this->topic['share_desc'];

        $this->_view("header",array('title'=>'组团详情'));

        switch($gouponDetial['status']){
            case $activityGrouponModel::GROUP_STATUS_FINISHED:  //组团完成
                $this->_view("groupon_detail_finish",$this->datas);
                break;
            case $activityGrouponModel::GROUP_STATUS_ING:  //组团进行中


                //点击分享之后开启这些按钮
                $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
                //分享参数配置
                $params= array(
                    'grid' => $gouponDetial['group_id'],
                    'id'=> $this->inter_id
                );

                //取出分享配置
                $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
                $ShareConfigModel = $this->ShareConfigModel;
                $position = $ShareConfigModel::POSITION_GROUPON;//分享类型
                $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
                // $this->load->helper('soma/package');
                // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
                $share_config = array(
                    'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : '我发起了一个拼团，组团更优惠，快来看看',
                    'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : '拼团更实惠',
                    'link'=> Soma_const_url::inst()->get_share_url($this->openid,'*/*/groupon_detail', $params ),
                    'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : base_url('public/soma/images/box.png'),
                );
                // $share_config = array(
                //     'title'=> '我发起了一个拼团，组团更优惠，快来看看',
                //     'desc'=> '拼团出游更实惠',
                //     'link'=> Soma_const_url::inst()->get_share_url($this->openid,'*/*/groupon_detail', $params ),
                //     'imgUrl'=> base_url('public/soma/images/box.png'),
                // );

                $this->datas['js_menu_show'] = $js_menu_show;
                $this->datas['js_share_config'] = $share_config;

                $this->_view("groupon_detail_ongoing",$this->datas);
                break;
            case $activityGrouponModel::GROUP_STATUS_FAILED://组团失败
                $this->_view("groupon_detail_failed",$this->datas);
                break;
            default:
                redirect(Soma_const_url::inst()->get_pacakge_home_page());
                break;
        }

//        $this->_view($this->theme. '/public_share',$this->share);

    }


    //展示为以后的皮肤做扩展
    //$pathArr = array('package','default')
//    protected function _view($file, $datas=array(),$pathArr = NULL )
    protected function _view($file, $datas=array() )
    {
//        parent::_view('package'. DS. $file, $datas,$theme);
        parent::_view( 'package'. DS. $file, $datas);
    }
}
