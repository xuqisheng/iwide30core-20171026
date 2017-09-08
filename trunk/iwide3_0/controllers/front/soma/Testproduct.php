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
        $order_id= $this->input->get('order_id');

        $this->load->model('soma/Sales_order_model');
        $order= $this->Sales_order_model->load($order_id);

        $this->load->model('soma/Reward_benefit_model');
        $result= $this->Reward_benefit_model->bgySpecialHotelReward($inter_id, $order);
        var_dump($result);
    }
}
