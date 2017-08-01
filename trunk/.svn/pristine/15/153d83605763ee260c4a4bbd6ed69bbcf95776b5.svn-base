<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台用户中心配置
*	@author Frandon 
*	@time 三月三十一号
*	@version www.iwide.cn
*	@
*/
class Membertheme extends MY_Admin_Api
{
	//配置列表
	public function index(){
        //获取酒店集团的信息

        if($this->session->get_admin_inter_id()) {
            $this->load->model('wx/Publics_model');
            $inter_public= $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());
        }
        $post_tem_url = PMS_PATH_URL."member/member_template";
        $post_tem_data = array(
            'inter_id'=>$this->session->get_admin_inter_id(),
            'openid'=>'admin',
        );
        $result = $this->doCurlPostRequest( $post_tem_url , $post_tem_data );
        $template = $result['data'];

        $theme_mapping = array(
            'version4'  => '默认',
            'phase2'    => '第二版'
        );

        $data['template'] = $template;
        $data['themes'] = $theme_mapping;


        $this->_render_content($this->_load_view_file('edit'),$data,false);
	}



	//增加或修改配置
	public function edit_post(){
        $post_data = $this->input->post();

        if(empty($post_data) || !isset($post_data['theme']) || empty($post_data['theme']))  $this->_ajaxReturn('保存失败，没有选择皮肤');

        $inter_id = $this->session->get_admin_inter_id();

        $this->load->model('membervip/admin/Public_model','pum');

        $info = $this->pum->get_info(array('inter_id'=>$inter_id,'type_code'=>'membertemplate'),'iwide_inter_member_config');

        if(!empty($info)){
            $params['inter_id'] = $inter_id;
            $params['member_inter_config_id'] = $info['member_inter_config_id'];
            $params['pk'] = 'member_inter_config_id';
            $sdata = array(
                'inter_id' => $inter_id,
                'type_code'=>'membertemplate',
                'value'     => $post_data['theme']
            );
            $result = $this->pum->update_save($params,$sdata,'iwide_inter_member_config');
        }else{
            $sdata = array(
                'inter_id' => $inter_id,
                'type_code'=>'membertemplate',
                'value'     => $post_data['theme']
            );
            $result = $this->pum->add_data($sdata,'iwide_inter_member_config');
        }

        $return['result'] = $result;
        $return['url'] = site_url('membervip/membertheme/index');

        if($result){
            $this->_ajaxReturn('保存成功!',$return,1);
        }
        $this->_ajaxReturn('保存失败!',$return,0);

        exit;


    }

}
?>