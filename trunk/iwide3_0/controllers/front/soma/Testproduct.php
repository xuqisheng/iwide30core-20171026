<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testproduct extends MY_Front_Soma
{

    /**
     * 商城首页入口
     */
    public function get_index()
    {
        $inter_id = $this->inter_id;
        $start_date = $this->input->get('start_date');
        $end_date = null;

        $this->load->model('soma/Sales_order_model');
        $result= $this->Sales_order_model->getOrderTotal($inter_id, $start_date, $end_date);
        var_dump($result);
    }
}
