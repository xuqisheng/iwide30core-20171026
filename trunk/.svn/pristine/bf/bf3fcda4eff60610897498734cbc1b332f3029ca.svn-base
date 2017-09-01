<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 后台邀请金中心配置
 *
 * @author Frandon
 *         @time 三月三十一号
 * @version www.iwide.cn
 *          @
 *         
 */
class Kiminvited extends MY_Admin_Api
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '邀金令';
    protected $label_action = '显示设置';

    public function __construct(){
        parent::__construct();
        $this->load->model('membervip/admin/Kiminvited_model');
    }

    // 配置列表
    public function disetting(){
        $admin_profile = $this->session->userdata('admin_profile');
        // 获取酒店集团的信息
        $inter_public = array();
        $inter_id = $admin_profile['inter_id'];
        $params['inter_id'] = $inter_id;
        $info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_display_config');
        if ($inter_id) {
            $this->load->model('wx/Publics_model');
            $inter_public = $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }
        // 反序列化
        $data = array(
            'info' => array(),
            'public' => $inter_public
        );

        if (!empty($info)) {
            $info['face_invite_config'] = unserialize($info['face_invite_config']);
            $info['share_config'] = unserialize($info['share_config']);
            $info['center_config'] = unserialize($info['center_config']);
            $info['title_config'] = unserialize($info['title_config']);
            $info['rank_config'] = unserialize($info['rank_config']);
            $info['canon_config'] = unserialize($info['canon_config']);
            $info['regnotice_config'] = unserialize($info['regnotice_config']);
            $info['act_config'] = unserialize($info['act_config']);
        }
        $display_param['info'] = $info;

        //页面标题
        $display_param['action_title'] = isset($info['title_config']['action_title'])?$info['title_config']['action_title']:'default';
        $display_param['custom_title'] = isset($info['title_config']['custom_title'])?$info['title_config']['custom_title']:'';

        //活动正文
        $display_param['steps'] = isset($info['act_config']['steps'])?$info['act_config']['steps']:'';

        //个人战绩
        $display_param['action_center'] = isset($info['center_config']['action_center'])?$info['center_config']['action_center']:'default';
        $display_param['custom_center'] = isset($info['center_config']['custom_center'])?$info['center_config']['custom_center']:'';

        //当面邀请
        $display_param['action_toface'] = isset($info['face_invite_config']['action_toface'])?$info['face_invite_config']['action_toface']:'default';
        $display_param['custom_toface'] = isset($info['face_invite_config']['custom_toface'])?$info['face_invite_config']['custom_toface']:'';
        //会员注册邀请语
        $display_param['invitation_toface'] = isset($info['face_invite_config']['invitation_toface'])?$info['face_invite_config']['invitation_toface']:'';

        //千里传音
        $display_param['action_share'] = isset($info['share_config']['action_share'])?$info['share_config']['action_share']:'default';
        $display_param['custom_share'] = isset($info['share_config']['custom_share'])?$info['share_config']['custom_share']:'';
        $display_param['title_share'] = isset($info['share_config']['title_share'])?$info['share_config']['title_share']:'';
        $display_param['title2_share'] = isset($info['share_config']['title2_share'])?$info['share_config']['title2_share']:'';

        //获得优惠提示
        $display_param['reg_value1'] = isset($info['regnotice_config']['value1'])?$info['regnotice_config']['value1']:'';
        $display_param['reg_value2'] = isset($info['regnotice_config']['value2'])?$info['regnotice_config']['value2']:'';
        $display_param['reg_value3'] = isset($info['regnotice_config']['value3'])?$info['regnotice_config']['value3']:'';

        //活动积分
        $display_param['action_point'] = isset($info['act_config']['action_point'])?$info['act_config']['action_point']:'default';
        $display_param['point'] = isset($info['act_config']['point'])?$info['act_config']['point']:'';
        $display_param['point_detail'] = isset($info['act_config']['point_detail'])?$info['act_config']['point_detail']:'';

        //我的奖励
        $display_param['action_reward'] = isset($info['act_config']['action_reward'])?$info['act_config']['action_reward']:'default';
        $display_param['reward'] = isset($info['act_config']['reward'])?$info['act_config']['reward']:'';
        $display_param['reward_detail'] = isset($info['act_config']['reward_detail'])?$info['act_config']['reward_detail']:'';

        //排行榜
        $display_param['action_rank'] = isset($info['rank_config']['action_rank'])?$info['rank_config']['action_rank']:'default';
        $display_param['custom_rank'] = isset($info['rank_config']['custom_rank'])?$info['rank_config']['custom_rank']:'';
        $display_param['title_rank'] = isset($info['rank_config']['title_rank'])?$info['rank_config']['title_rank']:'';
        $display_param['title2_rank'] = isset($info['rank_config']['title2_rank'])?$info['rank_config']['title2_rank']:'';

        //邀金宝典
        $display_param['action_canon'] = isset($info['canon_config']['action_canon'])?$info['canon_config']['action_canon']:'default';
        $display_param['custom_canon'] = isset($info['canon_config']['custom_canon'])?$info['canon_config']['custom_canon']:'';
        $display_param['canon_title'] = isset($info['canon_config']['canon_title'])?$info['canon_config']['canon_title']:'';
        $display_param['canon_title2'] = isset($info['canon_config']['canon_title2'])?$info['canon_config']['canon_title2']:'';

        //活动宝典、说明
        $display_param['canon'] = isset($info['act_config']['canon'])?$info['act_config']['canon']:'';
        $display_param['description'] = isset($info['act_config']['description'])?$info['act_config']['description']:'';

        $this->_init_breadcrumb($this->label_action);
        $this->_render_content($this->_load_view_file('disetting'), $display_param, false);
    }

    public function disetting_edit()
    {
        $data = $this->input->post();
        $inter_id = $this->session->get_admin_inter_id();
        $id = intval($data['id']);
        // 组装数据
        $post_data = array();
        $post_data['inter_id'] = $inter_id;
        $post_data['face_invite_config'] = serialize(array(
            'action_toface' => isset($data['action_toface']) ? $data['action_toface'] : '',
            'custom_toface' => isset($data['custom_toface']) ? $data['custom_toface'] : '',
            'invitation_toface' => isset($data['invitation_toface']) ? $data['invitation_toface'] : '',
            'remarks_toface' => isset($data['remarks_toface']) ? $data['remarks_toface'] : ''
        ));
        $post_data['share_config'] = serialize(array(
            'action_share' => isset($data['action_share']) ? $data['action_share'] : '',
            'custom_share' => isset($data['custom_share']) ? $data['custom_share'] : '',
            'title_share' => isset($data['title_share']) ? $data['title_share'] : '',
            'title2_share' => isset($data['title2_share']) ? $data['title2_share'] : ''
        ));
        $post_data['center_config'] = serialize(array(
            'action_center' => isset($data['action_center']) ? $data['action_center'] : '',
            'custom_center' => isset($data['custom_center']) ? $data['custom_center'] : ''
        ));
        $post_data['title_config'] = serialize(array(
            'action_title' => isset($data['action_title']) ? $data['action_title'] : '',
            'custom_title' => isset($data['custom_title']) ? $data['custom_title'] : ''
        ));
        $post_data['rank_config'] = serialize(array(
            'action_rank' => isset($data['action_rank']) ? $data['action_rank'] : '',
            'custom_rank' => isset($data['custom_rank']) ? $data['custom_rank'] : '',
            'title_rank' => isset($data['title_rank']) ? $data['title_rank'] : '',
            'title2_rank' => isset($data['title2_rank']) ? $data['title2_rank'] : ''
        ));
        $post_data['canon_config'] = serialize(array(
            'action_canon' => isset($data['action_canon']) ? $data['action_canon'] : '',
            'custom_canon' => isset($data['custom_canon']) ? $data['custom_canon'] : '',
            'canon_title' => isset($data['canon_title']) ? $data['canon_title'] : '',
            'canon_title2' => isset($data['canon_title2']) ? $data['canon_title2'] : ''
        ));

        $post_data['regnotice_config'] = serialize(array(
            'value1' => isset($data['value1']) ? $data['value1'] : '',
            'value2' => isset($data['value2']) ? $data['value2'] : '',
            'value3' => isset($data['value3']) ? $data['value3'] : '',
        ));

        $post_data['act_config'] = serialize(array(
            'steps' => isset($data['steps']) ? $data['steps'] : '',
            'canon' => isset($data['canon']) ? $data['canon'] : '',
            'description' => isset($data['description']) ? $data['description'] : '',
        ));

        $post_data['banner'] = $data['banner'];
        $post_data['background'] = $data['background'];
        $post_data['thumb_act'] = $data['thumb_act'];
        $post_data['notice_banner'] = $data['notice_banner'];
        $post_data['share_banner'] = $data['share_banner'];

        $post_data['last_update_time'] = date('Y-m-d H:i:s');
        //验证字段信息
        $post_data = $this->Kiminvited_model->check_list_fields($post_data,'kiminvited_display_config');
        if ($id) {
            $params['inter_id'] = $inter_id;
            $params['id'] = $id;
            $update_result = $this->Kiminvited_model->update_save($params,$post_data,'kiminvited_display_config');
            if(is_ajax_request()){
                $retrun['result'] = $update_result;
                $retrun['isadd'] = false;
                if($update_result!==true){
                    $this->_ajaxReturn('保存失败!',null,0);
                }
                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
            redirect('membervip/Kiminvited/disetting');
        } else {
            $add_result = $this->Kiminvited_model->add_data($post_data,'kiminvited_display_config');
            if(is_ajax_request()){
                $retrun['result'] = $add_result;
                $retrun['isadd'] = true;
                if($add_result){
                    $this->_ajaxReturn('添加成功!',$retrun,1);
                }
                $this->_ajaxReturn('添加失败!',$retrun,0);
            }
            redirect('membervip/Kiminvited/disetting');
        }
    }

    public function actsetlist(){
        $keys = $this->uri->segment(4);
        $avgs = array();
        $avgs['begin_time']		= $this->input->get('begin_time');
        $avgs['end_time']		= $this->input->get('end_time');

        $keys = explode('_', $keys);

        if(!empty($keys[0])){
            $avgs['begin_time'] = $keys[0];
        }
        if(!empty($keys[1])){
            $avgs['end_time'] = $keys[1];
        }
        $table = 'kiminvited_activited_conf';
        $this->load->library('pagination');
        $config['per_page']          = 30;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];

        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $avgs['inter_id'] = $this->session->get_admin_inter_id();
        $where = array('is_del'=>'n');
        if(!empty($avgs['begin_time']) && !empty($avgs['end_time']))
            $where = array($table.'.start_time >='=>strtotime($avgs['begin_time']),$table.'.end_time <='=>strtotime($avgs['end_time']),'is_del'=>'n');
        elseif (!empty($avgs['begin_time']))
            $where = array($table.'.start_time >='=>strtotime($avgs['begin_time']),'is_del'=>'n');
        elseif (!empty($avgs['end_time']))
            $where = array($table.'.end_time <='=>strtotime($avgs['end_time']),'is_del'=>'n');
        $res = $this->Kiminvited_model->get_kiminvited_list($avgs,$config['cur_page'],$config['per_page'],$table,$where);
        $config['uri_segment']       = 5;
        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url("membervip/kiminvited/act_list/".$avgs['begin_time'].'_'.$avgs['end_time']);
        $config['total_rows']        = $this->Kiminvited_model->get_kiminvited_total($avgs,$table,$where);
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
            'list'        => $res,
            'posts'      => $avgs,
            'total'      => $config['total_rows'],
        );
        $this->_render_content($this->_load_view_file('list'), $view_params, false);
    }

    public function rmranklist(){
        $act_id = $this->input->get('id');
        $inter_id= $this->session->get_admin_inter_id();
        $params['inter_id'] = $inter_id;
        $params['activited_id'] = $act_id;
        $_type = 4;
        $params['type'] = $_type;
        if(is_ajax_request()){
            //处理ajax请求
            $result = $this->Kiminvited_model->filter_rank($params );
            echo json_encode($result);
        } else {
            //HTML输出
            if( !$this->label_action ) $this->label_action= '活动统计';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $_params['inter_id'] = $params['inter_id'];

            $result= $this->Kiminvited_model->filter_rank($params);
            $fields_config= $this->Kiminvited_model->get_field_config('grid',$_type);
            $default_sort= array('field'=>'reg_time', 'sort'=>'desc');

            $view_params= array(
                'module'=> $this,
                'model'=> $this->Kiminvited_model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
            );
            $view_params = $view_params;
            $html = $this->_render_content($this->_load_view_file('rmranklist'), $view_params, TRUE);
            echo $html;
        }
    }
    
    public function actset()
    {
        // 获取酒店集团的信息
        $inter_public = array();
        $id = intval($this->input->get('id'));
        $inter_id = $this->session->get_admin_inter_id();
        //奖励配置信息
        $params['inter_id'] = $inter_id;
        $params['activited_id'] = $id;
        $rewardinfo = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        if(empty($id) || !$id) $rewardinfo = array();
        if ($inter_id) {
            $this->load->model('wx/Publics_model');
            $inter_public = $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }
        $data = array(
            'public' => $inter_public
        );

        // 获取卡券
        $post_data = array(
            'inter_id' => $inter_id
        );
        $card_info = $this->doCurlPostRequest(PMS_PATH_URL . "adminmember/get_inter_card", $post_data);
        $cardlist = isset($card_info['data'])?$card_info['data']:array();

        $exchange_card = array();
        if(!empty($cardlist)){
            foreach ($cardlist as $key => $item){
                if($item['card_type']=='3'){
                    $exchange_card[]=$item;
                }
            }
        }

        $data['exchange_card'] = $exchange_card;
        $data['cardlist'] = $cardlist;

        // 获取礼包
        $post_data = array(
            'inter_id' => $inter_id,
            'token' => $this->_token,
            'num' => 100
        );
        $package_list = $this->doCurlPostRequest(INTER_PATH_URL . "package/getlist", $post_data);
        $package = isset($package_list['data'])?$package_list['data']:array();
        $data['package_list'] = $package;

        $where['id'] = $id;
        $where['inter_id'] = $inter_id;
        $where['is_del'] = 'n';
        $info = $this->Kiminvited_model->get_kiminvited_info($where,'kiminvited_activited_conf');
        if(!empty($info['start_time'])) $info['start_time'] = date('Y-m-d',$info['start_time']);
        if(!empty($info['end_time'])) $info['end_time'] = date('Y-m-d',$info['end_time']);
        $data['set'] = array_merge($info,$rewardinfo);
        $this->_init_breadcrumb('活动设置');
        $this->_render_content($this->_load_view_file('actset'), $data, false);
       
    }

    public function actset_edit()
    {
        $inter_id = $this->session->get_admin_inter_id();
        $data = $this->input->post();
        $id = intval($data['id']);
        $reward_id = intval($data['reward_id']);
        // 组装数据
        $post_data = $this->Kiminvited_model->check_list_fields($data,'kiminvited_activited_conf');
        $post_data['inter_id'] = $inter_id;
        if(!empty($post_data['start_time'])){
            $post_data['start_time'] = strtotime($post_data['start_time']);
        }
        if(!empty($post_data['end_time'])){
            $post_data['end_time'] = strtotime($post_data['end_time']);
        }
        $post_data['last_update_time'] = date('Y-m-d H:i:s', time());
        unset($post_data['id']);
        if ($id) {
            //检测該活动是否已開始
            $map = "(start_time <= ".time()." and end_time > start_time)";
            $where = "$map and inter_id='".$inter_id."' and status=1 and isopen=1 and id=$id and is_del='n'";
            $act_count = $this->Kiminvited_model->get_kiminvited_count($where,'kiminvited_activited_conf');
            if($act_count && !empty($id)) $this->_ajaxReturn('当前活动正在进行，不能修改!',null,0);

            $params['id'] = $id;
            $params['inter_id'] = $inter_id;
            $params['is_del'] = 'n';
            $result = $this->Kiminvited_model->update_save($params,$post_data,'kiminvited_activited_conf');
            if(is_ajax_request()){
                $retrun['result'] = $result;
                $retrun['isadd'] = false;
                $retrun['url'] = site_url('membervip/kiminvited/actsetlist');
                if($result!==true){
                    $this->_ajaxReturn('保存失败!',null,0);
                }

                if($reward_id){
                    //修改奖励配置
                    $post_data = $this->Kiminvited_model->check_list_fields($data,'kiminvited_reward');
                    $post_data['inter_id'] = $inter_id;
                    unset($post_data['reward_id']);
                    unset($params['id']);
                    $params['activited_id'] = $id;
                    $params['reward_id'] = $reward_id;
                    $this->Kiminvited_model->update_save($params,$post_data,'kiminvited_reward');
                }else{
                    //添加奖励配置
                    $post_data = $this->Kiminvited_model->check_list_fields($data,'kiminvited_reward');
                    $post_data['inter_id'] = $inter_id;
                    unset($post_data['reward_id']);
                    $post_data['activited_id'] = $id;
                    $this->Kiminvited_model->add_data($post_data,'kiminvited_reward');
                }

                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
        } else {
            $post_data['createtime'] = time();
            $result = $this->Kiminvited_model->add_data($post_data,'kiminvited_activited_conf');
            if(is_ajax_request()){
                $retrun['result'] = $result;
                $retrun['isadd'] = true;
                $retrun['url'] = site_url('membervip/kiminvited/actsetlist');
                if($result){
                    //添加奖励配置
                    $post_data = $this->Kiminvited_model->check_list_fields($data,'kiminvited_reward');
                    $post_data['inter_id'] = $inter_id;
                    unset($post_data['reward_id']);
                    $post_data['activited_id'] = $result;
                    $this->Kiminvited_model->add_data($post_data,'kiminvited_reward');
                    $this->_ajaxReturn('添加成功!',$retrun,1);
                }
                $this->_ajaxReturn('添加失败!',$retrun,0);
            }
        }
        redirect('membervip/Kiminvited/actsetlist');
    }

    public function rewardset()
    {
        // 获取酒店集团的信息
        $inter_public = array();
        $inter_id = $this->session->get_admin_inter_id();
        if ($inter_id) {
            $this->load->model('wx/Publics_model');
            $inter_public = $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }
        // 获取卡券
        $post_data = array(
            'inter_id' => $inter_id
        );
        $card_info = $this->doCurlPostRequest(PMS_PATH_URL . "adminmember/get_inter_card", $post_data);
        $cardlist = isset($card_info['data'])?$card_info['data']:array();

        $exchange_card = array();
        if(!empty($cardlist)){
            foreach ($cardlist as $key => $item){
                if($item['card_type']=='3'){
                    $exchange_card[]=$item;
                }
            }
        }
        
        // 获取礼包
        $post_data = array(
            'inter_id' => $inter_id,
            'token' => $this->_token,
            'num' => 100
        );
        $package_list = $this->doCurlPostRequest(INTER_PATH_URL . "package/getlist", $post_data);
        $package = isset($package_list['data'])?$package_list['data']:array();

        $params['inter_id'] = $inter_id;
        $info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_reward');
        
        $data = array(
            'public' => $inter_public,
            'cardlist' => $cardlist,
            'package_list' => $package,
            'info'=>$info,
            'exchange_card'=>$exchange_card
        );
        $this->_init_breadcrumb('奖励设置');
        $this->_render_content($this->_load_view_file('rewardset'), $data, false);
    }

    public function rewards_edit()
    {
        /*
         * 奖励类型typ 0（无奖励） 1送积分 2送储值 3送优惠券 4送大礼包
         * 满XX住XX晚 reward_exchange 1积分兑换 2排名前XX名兑换
         */

        $post = $this->input->post();
        $id = intval($post['id']);
        $inter_id = $this->session->get_admin_inter_id();

        //检测已开启活动是否已存在
        $map = "(start_time <= ".time()." and end_time > start_time)";
        $where = "$map and inter_id='".$inter_id."' and status=1 and isopen=1 and is_del='n'";
        $act_count = $this->Kiminvited_model->get_kiminvited_count($where,'kiminvited_activited_conf');
        if($act_count && !empty($id)) $this->_ajaxReturn('当前已有活动正在进行，不能修改!',null,0);

        // 检测数据
        $post_data = $this->Kiminvited_model->check_list_fields($post,'kiminvited_reward');
        $post_data['inter_id'] = $inter_id;
        unset($post_data['id']);
        if(!empty($id)){
            $params['id'] = $id;
            $params['inter_id'] = $inter_id;
            $post_data['uptatetime'] = date('Y-m-d H:i:s');
            $result = $this->Kiminvited_model->update_save($params,$post_data,'kiminvited_reward');
            if(is_ajax_request()){
                $retrun['result'] = $result;
                $retrun['isadd'] = false;
                $retrun['url'] = site_url('membervip/kiminvited/rewardset');
                if($result!==true){
                    $this->_ajaxReturn('保存失败!',null,0);
                }
                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
        }else{
            $post_data['createtime'] = time();
            $post_data['uptatetime'] = date('Y-m-d H:i:s');
            $result = $this->Kiminvited_model->add_data($post_data,'kiminvited_reward');
            if(is_ajax_request()){
                $retrun['result'] = $result;
                $retrun['isadd'] = true;
                $retrun['url'] = site_url('membervip/kiminvited/rewardset');
                if($result){
                    $this->_ajaxReturn('添加成功!',$retrun,1);
                }
                $this->_ajaxReturn('添加失败!',$retrun,0);
            }
        }
         redirect('membervip/Kiminvited/rewardset');
    }

    public function statistics(){
        $this->label_action= '活动统计';
        $inter_id= $this->session->get_admin_inter_id();
        $id = $this->input->get('id');
        if($inter_id== FULL_ACCESS) $filter= array();
        else if($inter_id) $filter= array('inter_id'=>$inter_id );
        else $filter= array('inter_id'=>'deny' );

        $ent_ids= $this->session->get_admin_hotels();
        $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
        if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

        $filter['activited_id']=$id;
        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
        else
            $get_filter= $this->input->get('filter');

        if( !$get_filter) $get_filter = $this->input->get('filter');

        if(is_array($get_filter)) {
            $filter = $get_filter + $filter;
        }
        /* 兼容grid变为ajax加载加这一段 */
        $this->_grid_statistics($filter);
    }

    public function detailes(){
        $uid = $this->input->get("uid");
        $aid = $this->input->get("aid");
        $type = $this->input->get("type");
        $inter_id= $this->session->get_admin_inter_id();
        $params['inter_id'] = $inter_id;
        $params['activited_id'] = $aid;
        $params['fromuser_id'] = $uid;
        $params['type'] = $type;
        $_type = 2;
        if($type=='2') $_type=3;
        if(is_ajax_request()){
            //处理ajax请求
            $result = $this->Kiminvited_model->filter_detailes($params );
            echo json_encode($result);

        } else {
            //HTML输出
            if( !$this->label_action ) $this->label_action= '活动统计';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $_params['inter_id'] = $params['inter_id'];

            $result= $this->Kiminvited_model->filter_detailes($params);
            $fields_config= $this->Kiminvited_model->get_field_config('grid',$_type);
            $default_sort= array('field'=>'reg_time', 'sort'=>'desc');

            $view_params= array(
                'module'=> $this,
                'model'=> $this->Kiminvited_model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
            );
            $view_params = $view_params;
            $html = $this->_render_content($this->_load_view_file('detailes'), $view_params, TRUE);
            echo $html;
        }
    }

    /**
     * 默认表格展示函数，可共享
     */
    public function _grid_statistics($filter= array(), $viewdata=array()){
        //filter params: the same with table fields...
        //sort params: sort_direct, sort_field
        //page params: page_size, page_num
        $params= $this->input->get();
        if(is_array($filter) && count($filter)>0 ) $params= array_merge($params, $filter);

        $_params['inter_id'] = $filter['inter_id'];
        $rwinfo = $this->Kiminvited_model->get_kiminvited_info($_params,'kiminvited_reward');
        $params['reward'] = $rwinfo;
        if(is_ajax_request()){
            //处理ajax请求
            $result = $this->Kiminvited_model->filter_statistics($params );
            echo json_encode($result);
        } else {
            //HTML输出
            if( !$this->label_action ) $this->label_action= '活动统计';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $result= $this->Kiminvited_model->filter_statistics($params);
            $fields_config= $this->Kiminvited_model->get_field_config('grid');
            $default_sort= array('field'=>'reg_time', 'sort'=>'desc');

            $view_params= array(
                'module'=> $this,
                'model'=> $this->Kiminvited_model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
            );
            $view_params = $view_params + $viewdata;
            $html = $this->_render_content($this->_load_view_file('statistics'), $view_params, TRUE);
            echo $html;
        }
    }

    public function delact(){
        $return['code'] = 0;
        $return['data'] = ' -- ';
        if(is_ajax_request()){
            $id = $this->input->get('id');
            $inter_id = $this->session->get_admin_inter_id();
            $params['id'] = intval($id);
            $params['inter_id'] = $inter_id;
            $params['is_del'] = 'n';
            $act_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_activited_conf');
            if(empty($act_info)) $this->_ajaxReturn('该活动不存在',$act_info,0);
            unset($params['is_del']);
            $post_data['is_del'] = 'y';
            $result = $this->Kiminvited_model->update_save($params,$post_data,'kiminvited_activited_conf');
            if($result) {
                $return['code'] = 1;
                $return['data'] = $result;
                $this->_ajaxReturn('刪除成功',$return,1);
            }
            $this->_ajaxReturn('删除失败!',$return,0);
        }
        $this->_ajaxReturn('请求失败!',$return,0);
    }

    public function edit_act(){
        $get = $this->input->get();
        $inter_id = $this->session->get_admin_inter_id();
        $isopen = $get['isopen'];
        $params['id'] = intval($get['id']);
        $params['inter_id'] = $inter_id;
        $params['is_del'] = 'n';
        $act_info = $this->Kiminvited_model->get_kiminvited_info($params,'kiminvited_activited_conf');
        if(empty($act_info)) $this->_ajaxReturn('该活动不存在',$act_info,0);
        $end_time = strtotime(date('Y-m-d',$act_info['end_time']).' 23:59:59');
        if(floatval($end_time) < time() && $isopen=='1') $this->_ajaxReturn('该活动已过期',$act_info,0);
        if($act_info['status']=='2' && $isopen=='1') $this->_ajaxReturn('该活动未激活',$act_info,0);

        //检测已开启活动是否已存在
        $where = array('inter_id'=>$inter_id,'isopen'=>1,'is_del'=>'n');
        $act_count = $this->Kiminvited_model->get_kiminvited_count($where,'kiminvited_activited_conf');
        if($act_count && $isopen=='1') $this->_ajaxReturn('只能开启一个活动',$act_count,0);

        $post_data['isopen'] = $isopen;
        $return['code'] = 0;
        $return['state'] = ' -- ';
        $result = $this->Kiminvited_model->update_save($params,$post_data,'kiminvited_activited_conf');
        if($act_info['status']=='2'){
            $return['code'] = 0;
            $return['state'] = '<strong><font color="#f39c12">未激活</font></strong>';
        }elseif($act_info['status']=='1'){
            if($isopen=='2'){
                $return['code'] = 0;
                $return['state'] = '<strong><font color="#dd4b39">停用</font></strong>';
            }elseif($isopen=='1'){
                $start_time = strtotime(date('Y-m-d',$act_info['start_time']).' 00:00:00');
                if($start_time<=time() && $end_time>=time()){
                    $return['code'] = 1;
                    $return['state'] = '<strong><font color="#00a65a">正在进行...</font></strong>';
                }elseif ($start_time>time()){
                    $return['code'] = 0;
                    $return['state'] = '<strong><font color="#f39c12">未开始</font></strong>';
                }elseif ($end_time<time()){
                    $return['code'] = 0;
                    $return['state'] = '<strong><font color="#000">已结束</font></strong>';
                }
            }
        }
        if($result) $this->_ajaxReturn('ok',$return,1);
        $this->_ajaxReturn('操作失败',$return,0);
    }

    public function send_reward(){
        $get = $this->input->get();
        $inter_id = $this->session->get_admin_inter_id();
        $_where['inter_id'] = $inter_id;
        $_where['member_info_id'] = $get['memid'];
        $_where['activited_id'] = $get['actid'];
        $_where['reward_type'] = '2';
        $count = $this->Kiminvited_model->get_count($_where,'kiminvited_exchange_reward');
        if($count) $this->_ajaxReturn('奖励已发放！',null,0);
        $request=array(
            'token'=>$this->_token,
            'inter_id'=>$inter_id,
            'openid'=>$get['openid'],
            'member_info_id'=>$get['memid'],
            'card_id'=>floatval($get['value']),
            'module'=>'vip',
            'scene'=>'邀金令-积分兑换',
            'uu_code'=>time().uniqid('',true),
            'order_id'=>0
        );
        $tourl = INTER_PATH_URL.'intercard/receive';
        $result = $this->doCurlPostRequest($tourl,$request); //兑换优惠券
        $this->Kiminvited_model->_write_log($result,'exchange_reward_res');
        $msg = (isset($result['msg']) && !empty($result['msg']))?$result['msg']:'';
        if(isset($result['err']) && floatval($result['err'])>0){
            $this->_ajaxReturn('发放奖励失败！'.$msg,$result,0);
        }
        if(isset($result['data'])) {
            $reward_data = array(
                'inter_id'=>$inter_id,
                'member_info_id'=>$get['memid'],
                'openid'=>$get['openid'],
                'activited_id'=>$get['actid'],
                'reward_type'=>'2',
                'use_credit'=>0,
                'reward_cardid'=>floatval($get['value']),
                'createtime'=>time()
            );
            $add_data = $this->Kiminvited_model->add_data($reward_data,'kiminvited_exchange_reward');
            $this->Kiminvited_model->_write_log($add_data,'exchange_reward_res');
            $card_id = floatval($get['value']);
            $where = array('card_id'=>$card_id);
            $card_name = $this->Kiminvited_model->get_info($where,'title','card');
            $this->_ajaxReturn('成功发放了一张'.$card_name.'优惠券',$result,1);
        }
    }

    /**
     * 导出  活动统计表
     */
    public function ext_statistics(){
        $inter_id= $this->session->get_admin_inter_id();
        $id = $this->input->get('id');
        if($inter_id== FULL_ACCESS) $filter= array();
        else if($inter_id) $filter= array('inter_id'=>$inter_id );
        else $filter= array('inter_id'=>'deny' );

        $ent_ids= $this->session->get_admin_hotels();
        $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
        if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

        $filter['activited_id']=$id;
        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
        else
            $get_filter= $this->input->get('filter');

        if( !$get_filter) $get_filter = $this->input->get('filter');

        if(is_array($get_filter)) {
            $filter = $get_filter + $filter;
        }

        $params= $this->input->get();
        if(is_array($filter) && count($filter)>0 ) $params= array_merge($params, $filter);

        /* 兼容grid变为ajax加载加这一段 */
        $_params['inter_id'] = $filter['inter_id'];
        $rwinfo = $this->Kiminvited_model->get_kiminvited_info($_params,'kiminvited_reward');
        $params['reward'] = $rwinfo;
        $result= $this->Kiminvited_model->get_statistics_info($params);
        $confs = array('名次','会员名称','昵称','会员卡号','推荐数','获得积分');

        $data = "";
        foreach ($confs as $key=>$item){
            $data = $data.iconv('utf-8','gb2312',$item).",";
        }

        $data = $data."\n";
        foreach ($result as $item ){
            $data = $data.$item['rank_lv']." ,";
            $data = $data.iconv('utf-8','gb2312',$item['name'])." ,";
            $data = $data.iconv('utf-8','gb2312',$item['nickname'])." ,";
            $data = $data.$item['membership_number']." ,";
            $data = $data.$item['total_user']." ,";
            $data = $data.$item['total_value']." ,";
            $data = $data."\n";
        }
        // 发送标题强制用户下载文件
        header ('Content-Type: text/csv' );
        header ('Content-Disposition: attachment;filename="活动统计 - ' . date ( 'YmdHi' ) . '.csv"' );
        header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }

    /**
     * URL重定向
     * @param string $url 重定向的URL地址
     * @param integer $time 重定向的等待时间（秒）
     * @param string $msg 重定向前的提示信息
     * @return void
     */
    protected function _redirect($url, $time=0, $msg='') {
        //多行URL地址支持
        $url        = str_replace(array("\n", "\r"), '', $url);
        if (empty($msg))
            $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$time};url={$url}");
                echo($msg);
            }
            exit();
        } else {
            $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0)
                $str .= $msg;
            exit($str);
        }
    }
}
?>