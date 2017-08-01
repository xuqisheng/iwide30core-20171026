<?php

/**
 * 签到
 * Created by PhpStorm.
 * User: Smart Chan
 * Date: 2016/11/14
 * Time: 16:49
 */
class Sign extends MY_Admin_Api
{
    protected $inter_id;// 公众号id

    public function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/admin/Signin_model');
        $this->inter_id = $this->session->get_admin_inter_id();
    }

    /**
     * 签到统计
     */
    public function stat()
    {
        // 设置面包屑
        $this->label_action = '签到统计';
        $this->_init_breadcrumb($this->label_action);

        // 获取设置数据
        $confInfo = $this->Signin_model->get_conf_info($this->inter_id, 'is_active,active_at');
        // 获取统计数据
        $statData = $this->Signin_model->get_stat_data($this->inter_id);

        $this->_render_content($this->_load_view_file('stat'), array('confInfo' => $confInfo, 'statData' => $statData), false);
    }

    /**
     * 统计导出
     */
    public function export()
    {
        // 接收统计年月
        $year = intval($this->input->get('year'));
        $month = intval($this->input->get('month'));
        $type = intval($this->input->get('type'));
        if(strlen($month) == 1) $month = '0'.$month;

        // 获取导出数据
        if($type == 1){
            $filename = "签到活动统计{$year}年{$month}月.csv";
            $data = $this->Signin_model->get_export_data($this->inter_id, $year .$month);
        }else if($type == 2){
            $filename = "签到清单{$year}年{$month}月.csv";
            $data = $this->Signin_model->get_export_list($this->inter_id, $year .$month);
        }else{
            $this->session->put_error_msg('导出数据失败！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/stat'));
        }

        // 导出

        header('Content-Type: text/csv');
        header("Content-Type:text/html; charset=utf-8");
        header("Content-Disposition: attachment;filename={$filename}");
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        if (isset($data)) {
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
    }

    /**
     * 签到设置
     */
    public function conf()
    {
        // 设置面包屑
        $this->label_action = '签到设置';
        $this->_init_breadcrumb($this->label_action);

        // 获取签到配置
        $confInfo = $this->Signin_model->get_conf_info($this->inter_id);

        $this->_render_content($this->_load_view_file('conf'), array('confInfo' => $confInfo), false);
    }

    /**
     * 添加或修改签到设置
     */
    public function ajax_post()
    {
        // 接收设置数据
        $post = $this->input->post();

        // 验证设置数据
        if (!isset($post['is_active'])) {
            $msg = '请选择是否启动';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }
        if (empty($post['bonus_day'])) {
            $msg = '每日签到获得积分不能为空';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }
        if (!is_numeric($post['bonus_day']) || $post['bonus_day'] < 0) {
            $msg = '每日签到获得积分必须是一个正整数';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }
        if (empty($post['bonus_extra'])) {
            $msg = '额外获得积分不能为空';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }
        if (!is_numeric($post['bonus_extra']) || $post['bonus_extra'] < 0) {
            $msg = '额外获得积分必须是一个正整数';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }
        if (empty($post['serial_content'])) {
            $msg = '连续签到说明不能为空';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }
        if (empty($post['serial_reward_content'])) {
            $msg = '连续签到奖励说明不能为空';
            echo json_encode(array('err' => 1, 'msg' => $msg));
            exit;
        }

        // 新增或修改操作
        if (isset($post['id'])) {
            $res = $this->Signin_model->conf_edit($this->inter_id, $post);
            if (!$res) {
                $msg = '保存失败，请稍后重试';
                echo json_encode(array('err' => 1, 'msg' => $msg));
                exit;
            }
        } else {
            $res = $this->Signin_model->conf_add($this->inter_id, $post);
            if (!$res) {
                $msg = '保存失败，请稍后重试';
                echo json_encode(array('err' => 1, 'msg' => $msg));
                exit;
            }
        }

        echo json_encode(array('err' => 0));
    }

}