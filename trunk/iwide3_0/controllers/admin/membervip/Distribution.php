<?php

/**
 * 签到
 * Created by PhpStorm.
 * User: Smart Chan
 * Date: 2016/11/14
 * Time: 16:49
 */
class Distribution extends MY_Admin_Api
{
    protected $inter_id;// 公众号id

    public function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/admin/Distribution_model');
        $this->inter_id = $this->session->get_admin_inter_id();
    }

    public function setting(){
        // 设置面包屑
        $this->label_action = '会员分销绩效';
        $this->_init_breadcrumb($this->label_action);
        $rule_info = $this->Distribution_model->get_distribution_rule($this->inter_id);
        $this->_render_content($this->_load_view_file('setting'), array('ruleInfo' => $rule_info), false);

    }

    public function save_setting(){

        $inter_id = $this->inter_id;
        $saveData = $_POST;
        if(isset($_POST['rule_id'])){
            $rule_id = $_POST['rule_id'];
            $rule_info = $this->Distribution_model->get_rule_by_id($this->inter_id,$rule_id);
            if($rule_info && !empty($rule_info)){
                $result = $this->Distribution_model->save_rule($inter_id,$saveData);
                if($result){
                    $return['result'] = $result;
                    $return['isadd'] = false;
                    $return['url'] = site_url('membervip/distribution/setting');
                    $this->_ajaxReturn('保存成功!',$return,1);
                    exit;
                }
            }
        }
        $result = $this->Distribution_model->add_rule($inter_id,$saveData);
        $return['result'] = $result;
        $return['isadd'] = true;
        $return['url'] = site_url('membervip/distribution/setting');
        if($result){
            $this->_ajaxReturn('保存成功!',$return,1);
        }else{
            $this->_ajaxReturn('添加失败!',null,0);
        }
    }



}