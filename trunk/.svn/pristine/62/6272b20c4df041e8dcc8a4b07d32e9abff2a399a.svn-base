<?php
// +----------------------------------------------------------------------
// | 前端模版数据处理模块
// +----------------------------------------------------------------------
// | Author: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// | Vapi.php 2017-06-16
// +----------------------------------------------------------------------
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Vapi extends MY_Admin_Iapi{
    private   $admin_profile = array();
    public function __construct(){
        parent::__construct();
        $this->load->model('membervip/common/Public_model','common_model');
        $this->load->model('membervip/common/Public_log_model','common_logm');
        $this->load->model('membervip/admin/Vapi_logic','vapi_logic');
        $this->admin_profile = $this->session->userdata['admin_profile'];
    }

    public function coupon_task(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $tag_data = $this->vapi_logic->coupon_task_list($inter_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    public function task_item(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $task_id = $this->input->get('id');
            if(empty($task_id)){
                $task_id = $this->input->post('id');
            }
            if(empty($task_id)){
                $msg['err'] = 9000;
                $msg['msg'] = '参数错误';
                $this->_ajaxReturn($msg);
            }
            $tag_data = $this->vapi_logic->task_item($inter_id,$task_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    //优惠批量发放配置
    public function create_coupon_task(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $task_id = $this->input->get('id');
            if(empty($task_id)){
                $task_id = $this->input->post('id');
            }
            $tag_data = $this->vapi_logic->coupon_task_tag($inter_id,$task_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    /**
     * Ajax方式返回数据到客户端
     * @param array $data 要返回的数据
     * @param string $type AJAX返回数据格式
     * @param int $json_option JSON 常量
     */
    protected function _ajaxReturn($data = array(), $type = '',$json_option=0) {

        $data['referer'] = !empty($data['url']) ? $data['url'] : "";
        $data['state']= (!empty($data['status']) && $data['status'] == '1000') ? "success" : "fail";
        if(empty($type)) $type  =   'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit($this->common_model->xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            case 'AJAX_UPLOAD':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data,$json_option));
            default :
                // 中断程序
                exit(0);
        }
    }
}