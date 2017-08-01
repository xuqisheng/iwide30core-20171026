<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台用户中心配置
*	@author Frandon 
*	@time 三月三十一号
*	@version www.iwide.cn
*	@
*/
class Membercenter extends MY_Admin_Api
{
	//配置列表
	public function index(){
        //获取酒店集团的信息
        if($this->session->get_admin_inter_id()) {
            $this->load->model('wx/Publics_model');
            $inter_public= $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }
        $post_data = array(
            'inter_id'=>$this->session->get_admin_inter_id(),
        );
        //请求会员中心配置信息URL
        $post_center_url = PMS_PATH_URL."adminmember/get_center_info";
        $center_info = $this->doCurlPostRequest( $post_center_url , $post_data );
        $data = array(
            'centerinfo' =>isset($center_info['data']['value'])?$center_info['data']['value']:array(),
            'public' =>$inter_public,
        );
        $this->_render_content($this->_load_view_file('edit'),$data,false);
	}


    public function membernav(){
        //获取酒店集团的信息
        if($this->session->get_admin_inter_id()) {
            $this->load->model('wx/Publics_model');
            $inter_public= $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }

        $this->load->model('membervip/admin/config/attribute_model','ui_model');
        $icon_conf = $this->ui_model->get_uiicon();

        $inter_id = $this->session->get_admin_inter_id();
        $this->load->model('membervip/admin/Public_model','pum');
        $nav_info = $this->pum->get_info(array('inter_id'=>$inter_id),'member_nav');
        $nav_conf = array();
        if(isset($nav_info['nav_conf']) && !empty($nav_info['nav_conf'])) $nav_conf = json_decode($nav_info['nav_conf'],true);

        $data = array(
            'group_tab'=>array(1=>'分组一',2=>'分组二',3=>'分组三',4=>'分组四',5=>'分组五'),
            'nav_conf' =>$nav_conf,
            'public' =>$inter_public,
            'icon_conf'=>$icon_conf
        );
        $this->_render_content($this->_load_view_file('index'),$data,false);
    }

	//增加或修改配置
	public function edit_post(){
        $center_post_data = $this->input->post();
        $inter_id = $this->session->get_admin_inter_id();
        $post_data = array(
            'inter_id'=>$this->session->get_admin_inter_id(),
        );
        //获取已增加的会员中心配置信息
        $post_center_url = PMS_PATH_URL."adminmember/get_center_info";
        $center_info = $this->doCurlPostRequest( $post_center_url , $post_data );
        $center_confing = isset($center_info['data']['value'])?$center_info['data']['value']:array();
        //对修改的数据进行处理
        foreach ($center_confing as $key => $value) {
            if($center_post_data['group_'.$key]){
                $center_confing[$key]['group'] = $center_post_data['group_'.$key];
            }
            if($center_post_data['modelname_'.$key]){
                $center_confing[$key]['modelname'] =  $center_post_data['modelname_'.$key];
            }
            if($center_post_data['ico_'.$key]){
                $center_confing[$key]['ico'] =  $center_post_data['ico_'.$key];
            }
            if($center_post_data['link_'.$key]){
                $center_confing[$key]['link'] =  $center_post_data['link_'.$key];
            }
            if($center_post_data['modeltype_'.$key]){
                $center_confing[$key]['modeltype'] =  $center_post_data['modeltype_'.$key];
                $center_confing[$key]['link'] =  $center_post_data['modeltype_'.$key];
            }
            //如果修改中删掉了模块名称/则默认为用户删除该数据
            if(!$center_post_data['modelname_'.$key]){
                unset($center_confing[$key]);
            }
        }

        if(!empty($center_confing)) sort($center_confing); //兼容删除数据，重新排序，避免重复覆盖

        //对新增的数据进行处理
        if($this->input->post('group') && $this->input->post('modelname') ){
            $count = count($center_confing);
            $center_confing[$count]['group'] = $this->input->post('group');
            $center_confing[$count]['modelname'] = $this->input->post('modelname');
            $center_confing[$count]['ico'] = $this->input->post('ico');
            if($this->input->post('modeltype')){
                $center_confing[$count]['link'] = $this->input->post('modeltype');
            }else{
                $center_confing[$count]['link'] = $this->input->post('link');
            }
        }
        //如果存在则新增，否则修改
        if( $center_info['data'] ){
            $update_center_url = PMS_PATH_URL."adminmember/update_center_config";
            $update_center_data = array(
                'member_inter_config_id'=>$center_info['data']['member_inter_config_id'],
                'inter_id'=>$inter_id,
                'value'=>serialize($center_confing),
                'type_code'=>'membercenter',
            );

            $this->doCurlPostRequest( $update_center_url , $update_center_data );
        }else{
            $add_center_url = PMS_PATH_URL."adminmember/add_center_config";
            $add_center_data = array(
                'inter_id'=>$inter_id,
                'type_code'=>'membercenter',
                'value'=>serialize($center_confing),
                'createtime'=>time(),
            );
            $this->doCurlPostRequest( $add_center_url , $add_center_data );
        }
        redirect('membervip/membercenter');
        exit;
    }

//增加或修改配置
    public function edit_membernav(){
        $post_data = $this->input->post();
        if(!isset($post_data['group'])) $this->_ajaxReturn('没有分组');
        $save_data = array();
        $this->load->helper('common_helper');
        uasort($post_data['group'],"my_sort"); //对分组排序，由小到大根据键值排序
//        uksort($post_data['group'],"my_sort"); //对分组排序，由小到大根据键名排序
        foreach ($post_data['group'] as $key => $value){
            $save_data[$value][] = array(
                'icon'=>$post_data['icon'][$key],
                'modelname'=>$post_data['modelname'][$key],
                'link'=>$post_data['link'][$key],
                'is_login'=>isset($post_data['is_login'][$key])?$post_data['is_login'][$key]:2,
                'listorder'=>$post_data['listorder'][$key],
            );
        }

        if(empty($save_data)) $this->_ajaxReturn('至少添加一个导航标签');

        foreach ($save_data as $key => &$value){
            uasort($value,"my_sort"); //对分组排序，由小到大根据键值排序
        }

        $inter_id = $this->session->get_admin_inter_id();
        $this->load->model('membervip/admin/Public_model','pum');
        $info = $this->pum->get_info(array('inter_id'=>$inter_id),'member_nav');
        $sdata['nav_conf'] = json_encode($save_data);
        $sdata['inter_id'] = $inter_id;
        $sdata = $this->pum->check_list_fields($sdata,'member_nav');
        $params['inter_id'] = $inter_id;
        if(!empty($info)){
            $params['nav_id'] = $info['nav_id'];
            $params['pk'] = 'nav_id';
            $result = $this->pum->update_save($params,$sdata,'member_nav');
            $retrun['result'] = $result;
            $retrun['isadd'] = false;
            $retrun['url'] = site_url('membervip/membercenter/membernav');
            if($result){
                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
            $this->_ajaxReturn('保存失败!',$retrun,0);
        }else{
            $result = $this->pum->add_data($sdata,'member_nav');
            $retrun['result'] = $result;
            $retrun['isadd'] = true;
            $retrun['url'] = site_url('membervip/membercenter/membernav');
            if($result){
                $this->_ajaxReturn('添加失败!',null,0);
            }
            $this->_ajaxReturn('添加成功!',$retrun,1);
        }
        redirect('membervip/membercenter/membernav');
    }
}
?>