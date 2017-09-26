<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/***
 * 礼包派送 Controller
 * Created by Wuqd on.
 * Created Time 2017-09-03
 */


class Gift_delivery extends MY_Admin_Soma{


    /***
     * 后台礼包首页路由
     */
    public function gift_index(){

        $this->_render_content($this->_load_view_file('gift_index'), [], false);
    }


    /***
     * 领取礼包的详情
     */
    public function gift_detail_index(){

        $this->_render_content($this->_load_view_file('gift_detail_index'), [], false);
    }



    /***
     * 导出礼包详情
     */
    public function exportGiftExcel(){
        //接受筛选参数
        $params = array();
        $params['start_time'] = $this->input->get('start_time');
        $params['end_time'] = $this->input->get('end_time');
        $params['order_id'] = intval($this->input->get('order_id'));
        $params['saler_name'] = $this->input->get('saler_name');
        $params['record_info'] = $this->input->get('record_info');
        $params['page'] = intval($this->input->get('page'));
        $params['page'] = empty($params['page']) ? 0 : $params['page'] - 1;

        $params['inter_id'] = $this->session->get_admin_inter_id();
        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;
        //获取礼包领取详情
        $resultInfo = $this->gift_delivery_model->exportGiftOrderData($params,$Gift_detail_model);

        $this->_do_export($resultInfo['data'], $resultInfo['title'], 'csv', TRUE);
    }




}













