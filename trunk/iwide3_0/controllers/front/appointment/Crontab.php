<?php
/*
 * 定时取消5分钟内未付款订单
 * author situguanchen  2017-02-06
 */
class Crontab extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->debug = $this->input->get('debug');
        error_reporting(0);
        if (!empty ($this->debug)) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
        $this->load->library('MYLOG');
    }


    /**
     * 计划任务推送消息 [临近用餐时间前30min]
     */
    public function run_notice_status()
    {
        set_time_limit(0);
        @ini_set('memory_limit','2048M');

        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $order = $this->appointment_order_model->notice_status_order('order_id,openid,inter_id,desk_name,book_datetime,dining_room_id');

        if (!empty($order))
        {
            $this->load->model('plugins/Template_msg_model');
            foreach ($order as $value)
            {
                if (!empty($value['openid']))
                {
                    $where = array(
                        'order_id'  => $value['order_id'],
                        'notice_status' => 0,
                    );
                    $this->db->update('appointment_order',array('notice_status'=>1),$where);
                    $res = $this->db->affected_rows();
                    //推送消息
                    if ($res > 0)
                    {
                        $dining_room = $this->appointment_dining_room_model->get_one($value['dining_room_id']);
                        //发送模板消息
                        $value['shop_name'] = $dining_room['shop_name'];
                        $value['item'] = $value['desk_name'].$order['desk_man'];
                        $value['time'] = $value['book_datetime'];
                        $this->Template_msg_model->send_appointment_msg($value, 'appointment_offer_wait');
                    }
                }
                echo "{$order['order_id']} <BR/>";
            }
        }
    }


}