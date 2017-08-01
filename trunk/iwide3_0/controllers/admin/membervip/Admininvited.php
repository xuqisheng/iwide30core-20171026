<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 邀请好友
 * @author liwensong
 * @version www.iwide.cn
 *          @
 *         
 */
class Admininvited extends MY_Admin
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '邀请好友';
    protected $label_action = '显示设置';

    public function __construct(){
        parent::__construct();
        $this->load->model('membervip/admin/Public_model','pm');
    }

    // 显示配置
    public function viewconf(){
        // 获取酒店集团的信息
        $admin_profile = $this->session->userdata('admin_profile');
        $inter_id = $admin_profile['inter_id'];
        //获取配置信息
        $info = $this->pm->get_info(array('inter_id'=>$inter_id),'invite_show_conf');
        if(!empty($info)){
            foreach ($info as &$v){ //转换为数组
                json_decode($v);
                if(json_last_error() == JSON_ERROR_NONE){
                    $v = json_decode($v,true);
                }
            }
        }

        $this->_init_breadcrumb($this->label_action);
        $data = !empty($info)?$info:array();
        $this->_render_content($this->_load_view_file('viewconf'), $data, false);
    }

    //保存配置信息
    public function save_viewconf()
    {
        $post_data = $this->input->post();
        if(empty($post_data['home']['title'])) $this->_ajaxReturn('请输入邀请好友主页面标题!',null,0);
        if(empty($post_data['invited_share']['title'])) $this->_ajaxReturn('请输入分享标题!',null,0);
        if(empty($post_data['reg_login']['reg_title'])) $this->_ajaxReturn('请输入注册页面标题!',null,0);
        if(empty($post_data['upgrade_success']['title'])) $this->_ajaxReturn('请输入升级成功页面标题!',null,0);

        foreach ($post_data as &$v){ //转换为JSON格式
            $v = json_encode($v);
        }

        $admin_profile = $this->session->userdata('admin_profile');
        $inter_id = $admin_profile['inter_id'];

        // 组装数据
        $post_data['createtime'] = time();
        $post_data['operator'] = $admin_profile['admin_id'].'|'.$admin_profile['username'];
        //验证字段信息
        $post_data = $this->pm->check_list_fields($post_data,'invite_show_conf');
        $post_data['inter_id'] = $inter_id;
        $info = $this->pm->get_info(array('inter_id'=>$inter_id),'invite_show_conf');

        //操作记录信息
        $this->load->model('membervip/common/Public_log_model','logm');
        $logs = array(
            'title'=>'邀请好友显示配置',
            'is_json'=>true,
            'filter'=>array('createtime','operator','lastupdatetime'),
            'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
        );

        if (!empty($info)) {
            $params['inter_id'] = $inter_id;
            $params['pk'] = 'id';
            $params['id'] = $info['id'];
            $update_result = $this->pm->update_save($params,$post_data,'invite_show_conf');

            $this->logm->save_log_init($info,$post_data,$info['id'],$update_result,'invite_viewconf',$this->pm,$logs); //添加操作记录

            if(is_ajax_request()){
                $retrun['result'] = $update_result;
                $retrun['isadd'] = false;
                if($update_result===false){
                    $this->_ajaxReturn('保存失败!',null,0);
                }elseif($update_result===0){
                    $this->_ajaxReturn('没有修改任何数据!',null,0);
                }
                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
            redirect('membervip/admininvited/viewconf');
        } else {
            $add_result = $this->pm->add_data($post_data,'invite_show_conf');

            $this->logm->save_log_init($info,$post_data,$add_result,$add_result,'invite_viewconf',$this->pm,$logs); //添加操作记录

            if(is_ajax_request()){
                $retrun['result'] = $add_result;
                $retrun['isadd'] = true;
                if($add_result){
                    $this->_ajaxReturn('添加成功!',$retrun,1);
                }
                $this->_ajaxReturn('添加失败!',$retrun,0);
            }
            redirect('membervip/admininvited/viewconf');
        }
    }

    //会员邀请配置设置
    public function index(){
        // 获取酒店集团的信息
        $admin_profile = $this->session->userdata('admin_profile');
        $inter_id = $admin_profile['inter_id'];
        //获取配置信息
        $info = $this->pm->get_info(array('inter_id'=>$inter_id,'is_active'=>'t'),'invite_settings');
        $view_params = !empty($info)?$info:array();
        $member_lvl = $this->pm->get_admin_member_lvl($inter_id,'member_lvl_id,lvl_name,is_default');
        //去除默认等级
        foreach ($member_lvl as $key => &$data){
            if($data['is_default']=='t') unset($member_lvl[$key]);
        }
        sort($member_lvl); //重置键值

        $view_params['member_lvl'] = $member_lvl;
        $act_id = !empty($info['id'])?$info['id']:0;
        $level_equity = $this->pm->get_info(array('inter_id'=>$inter_id,'act_id'=>$act_id),'invite_level_equity');
        if(!empty($level_equity)){
            $level_equity['hold_lvl_group'] = json_decode($level_equity['hold_lvl_group'],true);
        }
        $view_params['hold_lvl_group'] = !empty($level_equity['hold_lvl_group'])?$level_equity['hold_lvl_group']:array();
        $this->label_action = '邀请设置';
        $this->_init_breadcrumb($this->label_action);
        $this->_render_content($this->_load_view_file('index'), $view_params, false);
    }

    //保存配置信息
    public function save_setting()
    {
        $post_data = $this->input->post();
        unset($post_data['id']);
        if(empty($post_data['effective_time'])) $this->_ajaxReturn('请输入会员邀请好友权限的有效期!',null,0);
        if(empty($post_data['activate_value']) && $post_data['to_activate']=='t') $this->_ajaxReturn('请输入激活会员邀请权益所需间夜数!',null,0);

        if(!empty($post_data['main_lvl']) && count($post_data['main_lvl']) != count(array_unique($post_data['main_lvl']))){
            $this->_ajaxReturn('邀请权益不能配置重复等级!',null,0);
        }

        if(!empty($post_data['group_lvl'])){
            foreach ($post_data['group_lvl'] as $g_lvl){
                if(count($g_lvl) != count(array_unique($g_lvl))){
                    $this->_ajaxReturn('同一组邀请权益下不能配置重复等级!',null,0);
                }
            }
        }

        //重组邀请权益配置
        $hold_lvl_group = array();
        if(!empty($post_data['main_lvl']) && !empty($post_data['lvl_cout']) && !empty($post_data['group_lvl'])){
            foreach ($post_data['main_lvl'] as $key => $vo){
                $_lvl_group = array();
                foreach ($post_data['group_lvl'][$key] as $k=>$lvl){
                    if(empty($post_data['lvl_cout'][$key][$k])) $this->_ajaxReturn('可邀请等级的数量必须大于0!',null,0);
                    $_lvl_group[$lvl] = $post_data['lvl_cout'][$key][$k];
                }
                $hold_lvl_group[$vo] = $_lvl_group;
            }
        }
        $post_data['hold_lvl_group'] = json_encode($hold_lvl_group);

        $admin_profile = $this->session->userdata('admin_profile');

        $inter_id = $admin_profile['inter_id'];

        //验证字段信息
        $save_data = $this->pm->check_list_fields($post_data,'invite_settings');

        $save_data['inter_id'] = $inter_id;
        $save_data['createtime'] = time();
        $effective_time = intval($post_data['effective_time']);
        $expiretime = strtotime(date('Y-12-31 23:59:59'));
        if($effective_time > 1){
            $c = $effective_time - 1;
            $expire = strtotime('+'.$c.' years');
            $expiretime = strtotime(date('Y-12-31 23:59:59',$expire));
        }
        $save_data['expiretime'] = $expiretime;
        $save_data['operator'] = $admin_profile['admin_id'].'|'.$admin_profile['username'];

        $info = $this->pm->get_info(array('inter_id'=>$inter_id,'is_active'=>'t'),'invite_settings');

        //操作记录信息
        $this->load->model('membervip/common/Public_log_model','logm');
        $logs = array(
            'title'=>'邀请好友活动配置',
            'filter'=>array('createtime','operator','lastupdatetime'),
            'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action
        );

        $this->pm->_shard_db()->trans_begin(); //开启事务
        $params['inter_id'] = $inter_id;
        if (!empty($info) && (($info['is_open']==$save_data['is_open'] && $info['is_open']=='f') || ($save_data['is_open']=='f' && $info['is_open']=='t'))) {
            $params['pk'] = 'id';
            $params['id'] = $info['id'];
            $update_result = $this->pm->update_save($params,$save_data,'invite_settings');
            $this->logm->save_log_init($info,$save_data,$info['id'],$update_result,'invite_settings',$this->pm,$logs); //添加操作记录
            if(is_ajax_request()){
                $retrun['result'] = $update_result;
                $retrun['isadd'] = false;
                if($update_result===false){
                    $this->_ajaxReturn('保存失败!',null,0);
                }elseif($update_result===0){
                    $this->_ajaxReturn('没有修改任何数据!',null,0);
                }
                try {
                    $_save = $this->pm->check_list_fields($post_data,'invite_level_equity');
                    $_save['inter_id'] = $inter_id;
                    $_save['createtime'] = time();
                    $_save['operator'] = $admin_profile['admin_id'].'|'.$admin_profile['username'];
                    $_save['act_id'] = $info['id'];
                    $level_equity = $this->pm->get_info(array('inter_id'=>$inter_id,'act_id'=>$info['id']),'invite_level_equity');

                    $logs = array(
                        'title'=>'邀请好友权益配置',
                        'filter'=>array('createtime','operator','lastupdatetime'),
                        'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action
                    );

                    if(!empty($level_equity)){
                        $params['id'] = $level_equity['id'];
                        $level_update = $this->pm->update_save($params,$_save,'invite_level_equity');

                        $this->logm->save_log_init($level_equity,$_save,$level_update['id'],$level_update,'invite_level_equity',$this->pm,$logs); //添加操作记录

                        if($level_update===false){ //保存邀请权益信息失败
                            throw new Exception();
                        }
                    }else{
                        $level_add = $this->pm->add_data($_save,'invite_level_equity');
                        $this->logm->save_log_init($level_equity,$_save,$level_add,$level_add,'invite_level_equity',$this->pm,$logs); //添加操作记录

                        if(!$level_add){ //添加邀请权益信息失败
                            throw new Exception();
                        }
                    }
                }catch (Exception $e){
                    $this->pm->_shard_db()->trans_rollback(); //回滚事务
                    $this->_ajaxReturn('保存失败!',null,0);
                }
                $this->pm->_shard_db()->trans_commit();// 事务提交
                
                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
            redirect('membervip/admininvited');
        } else {
            if(!empty($info)){
                $_where = array('inter_id'=>$inter_id,'is_active'=>'t');
                $settings_update = $this->pm->update_save($_where,array('is_active'=>'f'),'invite_settings');
                if(!$settings_update) $this->_ajaxReturn('保存失败!',null,0);
            }

            $add_result = $this->pm->add_data($save_data,'invite_settings');

            $this->logm->save_log_init($info,$save_data,$add_result,$add_result,'invite_settings',$this->pm,$logs); //添加操作记录

            if(is_ajax_request()){
                $retrun['result'] = $add_result;
                $retrun['isadd'] = true;
                if($add_result){
                    try {
                        $_save = $this->pm->check_list_fields($post_data,'invite_level_equity');
                        $_save['inter_id'] = $inter_id;
                        $_save['createtime'] = time();
                        $_save['operator'] = $admin_profile['admin_id'].'|'.$admin_profile['username'];
                        $_save['act_id'] = $add_result;
                        $logs = array(
                            'title'=>'邀请好友权益配置',
                            'filter'=>array('createtime','operator','lastupdatetime'),
                            'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action
                        );

                        $level_add = $this->pm->add_data($_save,'invite_level_equity');
                        $this->logm->save_log_init(array(),$_save,$level_add,$level_add,'invite_level_equity',$this->pm,$logs); //添加操作记录

                        if(!$level_add){ //添加邀请权益信息失败
                            throw new Exception();
                        }
                    }catch (Exception $e){
                        $this->pm->_shard_db()->trans_rollback(); //回滚事务
                        $this->_ajaxReturn('保存失败!',null,0);
                    }
                    $this->pm->_shard_db()->trans_commit();// 事务提交
                    $this->_ajaxReturn('保存成功!',$retrun,1);
                }
                $this->_ajaxReturn('保存失败!',$retrun,0);
            }
            redirect('membervip/admininvited');
        }
    }

    public function lvlconf(){
        $inter_id= $this->session->get_admin_inter_id();
        //获取等级配置
        $member_lvl = $this->pm->get_admin_member_lvl($inter_id,'member_lvl_id,lvl_name,lvl_icon,is_default');
        //去除默认等级
        foreach ($member_lvl as $key => &$data){
            if($data['is_default']=='t') unset($member_lvl[$key]);
        }
        sort($member_lvl); //重置键值

        $this->load->model('membervip/admin/config/attribute_model','ui_model');
        $icon_conf = $this->ui_model->get_lvl_uiicon();

        $view_params= array(
            'module'=> $this,
            'member_lvl'=> $member_lvl,
            'icon_conf'=>$icon_conf
        );

        $this->_render_content($this->_load_view_file('lvlconf'), $view_params, false);
    }
    
    public function save_lvlconf()
    {
        // 获取酒店集团的信息
        $inter_id = $this->session->get_admin_inter_id();
        $post_data = $this->input->post();
        //获取等级配置
        $member_lvl = $this->pm->get_admin_member_lvl($inter_id,'member_lvl_id,lvl_name,lvl_icon,is_default');
        if(empty($member_lvl)) $this->_ajaxReturn('没有配置平台等级!',null,0);

        $params['inter_id'] = $inter_id;

        $this->pm->_shard_db()->trans_begin(); //开启事务
        $retrun=array();
        try{
            foreach ($member_lvl as $key => &$data){
                if($data['is_default']!='t' && !empty($post_data['icon'][$data['member_lvl_id']])) {
                    $save_data['lvl_icon'] = $post_data['icon'][$data['member_lvl_id']];
                    $params['pk'] = 'member_lvl_id';
                    $params['member_lvl_id'] = $data['member_lvl_id'];
                    $lvl_update = $this->pm->update_save($params,$save_data,'member_lvl');
                    $retrun['result'] = $lvl_update;
                    $retrun['isadd'] = false;
                    $retrun['url'] = site_url('membervip/admininvited/lvlconf');
                    if(!$lvl_update) throw new Exception();
                }
            }
            $this->pm->_shard_db()->trans_commit();// 事务提交
            $this->_ajaxReturn('保存成功!',$retrun,1);
        }catch (Exception $e){
            $this->pm->_shard_db()->trans_rollback(); //回滚事务
            $this->_ajaxReturn('保存失败!',null,0);
        }
    }

    //邀请好友统计
    public function statistics(){
        $admin_profile = $this->session->userdata('admin_profile');
        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request()){
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
            $_get_filter= $this->input->get();
            if(!empty($_get_filter) && is_array($_get_filter)) $get_filter = $get_filter + $_get_filter;
        }else
            $get_filter= $this->input->get();

        if( !$get_filter) $get_filter = $this->input->get('filter');

        $params['ir.inter_id'] = $admin_profile['inter_id'];

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        if(!empty($params['st'])){ //邀请时间(起始)
            $params['extendedWhere']['ir.invited_time'][] = array('>=',strtotime($get_filter['st']));
        }

        if(!empty($params['et'])){ //邀请时间(结束)
            $params['extendedWhere']['ir.invited_time'][] = array('<=',strtotime(date('Y-m-d 23:59:59',strtotime($get_filter['et']))));
        }

        if(!empty($get_filter['lvl'])){ //邀请等级
            $params['ir.member_lvl_id'] = $get_filter['lvl'];
        }

        if(!empty($get_filter['k'])){ //关键字
            $params['f_like']['value'] = $get_filter['k'];
        }

        $inter_id = $this->session->get_admin_inter_id();
        $this->load->model('membervip/admin/Public_model','pum');

        $params['table_name'] = 'invited_record';
        $params['alias'] = "ir";
        $params['join'] = array(
            array('table'=>'member_info as mi','on'=>"mi.member_info_id = ir.accept_mid",'type'=>'left'),
            array('table'=>'member_lvl as ml','on'=>"ml.member_lvl_id = ir.member_lvl_id",'type'=>'left'),
        );

        $params['ir.act_id'] = array('>',0);
        $params['ir.invited_lvl_id'] = array('>',0);
        $params['mi.member_info_id'] = array('>',0);

        $select = array('ir.*','mi.name','mi.nickname','mi.membership_number','mi.createtime as mi_createtime','ml.lvl_name');
        $params['sort_field'] = 'ir.createtime';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('ir_membership_number','ir_name','ir.createtime','mi_createtime','mi.membership_number','mi.name','ml.lvl_name');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 4;
        $params['ui_type'] = 5;
        $params['f_type'] = 5;

        //邀请者数量
        $invite_count = $this->pum->_shard_db()->from('invited_record r')
                                  ->join('member_info as m','r.accept_mid = m.member_info_id')
                                  ->where(array('r.inter_id'=>$admin_profile['inter_id'],'r.act_id >'=>0,'r.invited_lvl_id >'=>0,'m.member_info_id >'=>0))
                                  ->group_by('to_mid')->get()->num_rows();

        $member_lvl = $this->pum->get_field_by_level_config($admin_profile['inter_id'],'member_lvl_id,lvl_name,lvl_up_sort');
        if(empty($member_lvl)){
            $this->session->put_error_msg('找不到等级配置');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/*'));
        }
        $lvlids = array_keys($member_lvl); //获取等级ID

        //邀请等级分组统计
        $this->pum->_shard_db()->where(array('r.inter_id'=>$admin_profile['inter_id'],'r.act_id >'=>0,'r.invited_lvl_id >'=>0,'m.member_info_id >'=>0));
        $this->pum->_shard_db()->where_in('r.member_lvl_id',$lvlids);
        $lvlst = $this->pum->_shard_db()->from('invited_record r')->select('COUNT(r.member_lvl_id) as mlvlc,r.member_lvl_id')
                          ->join('member_info as m','r.accept_mid = m.member_info_id')
                          ->group_by('r.member_lvl_id')->get()->result_array();
        $group_lvls = array();
        if(!empty($lvlst)){
            foreach ($lvlst as &$arr){
                $arr['lvl_name'] = !empty($member_lvl[$arr['member_lvl_id']])?$member_lvl[$arr['member_lvl_id']]:' --- ';
                $group_lvls[] = $arr['lvl_name'].$arr['mlvlc'].'张';
            }
        }
        $group_lvls = implode('，',$group_lvls);

        $counts = $this->pum->_shard_db()->query("SELECT COUNT(id) as count FROM iwide_invited_record WHERE inter_id = '$inter_id' AND act_id > 0 AND invited_lvl_id > 0")->row_array();
        $result['data'] = array();
        $result['total'] = $counts['count'];
        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select);
            echo json_encode($result);
        }else{
            //HTML输出
            $this->label_action= '邀请好友统计';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $num = (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
            if($result['total'] < $num) $result = $this->pum->get_admin_filter($params,$select);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $_moedel = $this->ui_model;
            $fields_config = $_moedel->get_field_config('grid',5);
            $default_sort= array('field'=>'createtime', 'sort'=>$params['sort_direct']);
            $member_lvl = $this->pum->get_admin_member_lvl($inter_id,'member_lvl_id,lvl_name,is_default');
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter,
                'member_lvl'=>$member_lvl,
                'invite_count'=>$invite_count,
                'group_lvls'=>$group_lvls
            );
            $html = $this->_render_content($this->_load_view_file('statistics'), $view_params, TRUE);
            echo $html;
        }
    }
}
?>