<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class EleCallback extends MY_Controller {

    //蜂鸟订单状态异步通知地址
    public function notify_url()
    {
		$this->load->library('MYLOG');
        //$content = file_get_contents ( 'php://input' );

        $content = $_POST;

       // $arr = json_decode ( $content, true );
        $arr = $content['data'];
        //$arr = json_decode($arr['data'],true);

        MYLOG::w('return_data|'.$arr,'take-away/ele');
        $arr = json_decode($arr,true);
        if(empty($arr))
        {
            die ( '这是请求的接口地址，直接在浏览器里无效' );
        }

        $partner_order_code = addslashes($arr['partner_order_code']);
        $inter_id = $this->input->get('id',TRUE);
        $order_status = intval($arr['order_status']);

        //记录蜂鸟回调日志
        $data = array();
        $data['inter_id'] = $inter_id;
        $data['order_sn'] = $partner_order_code;
        $data['ele_order_status'] = $order_status;
        $data['ele_update_time'] = time();
        $data['rec_content'] = json_encode($arr);
        $data['add_time'] = time();
        $data['ele_dm_name'] = !empty($arr['carrier_driver_name']) ? addslashes($arr['carrier_driver_name']) : '';
        $data['ele_dm_mobile'] = !empty($arr['carrier_driver_phone']) ? addslashes($arr['carrier_driver_phone']) : '';

        $this->db->insert('ele_log', $data);

        //先查询该订单是否存在
        $this->db->where ( array (
            'inter_id' => $inter_id,
            'order_sn' => $partner_order_code,//订单号 传给达达的也是订单号
        ) );
        $order = $this->db->get ('roomservice_orders' )->row_array ();
        if($order && $order['type'] == 3 && $order['shipping_type'] == 3)
        {
            //更新相应的达达状态
            $where = array('order_sn'=> $partner_order_code ,'inter_id'=>$inter_id);
            $params = array(
                'ele_status' => $order_status,
            );

            if($order_status == 2)
            {
                //配送中，需要更新订单状态为已配送
                $params['order_status'] = 10;//订单状态：10=》已配送
            }
            else if($order_status == 3)
            {
                //完成，需要封信订单状态为已完成
                $params['order_status'] = 20;//订单状态：20=》已完成
            }

            $res = $this->db->update('roomservice_orders',$params,$where);
            if ($res)
            {
                if(isset($params['order_status']) && ($params['order_status'] == 10 || $params['order_status'] == 20))
                {
                    //改状态
                    //订单跟踪数组
                    $array = array(
                        'inter_id'=>$inter_id,
                        'openid' =>'',
                        'order_id' => $order['order_id'],
                        'type' => 2,//跟踪
                        'order_status'=>$params['order_status'],//更新后的订单状态
                        'add_time' => date('Y-m-d H:i:s'),
                        'content' => $params['order_status'] == 10 ? '订单配送中' :'订单已完成',
                    );
                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表
                }

                //记录订单操作日志
                $order_log = array(
                    'inter_id'=>$inter_id,
                    'order_id' => $order['order_id'],
                    'hotel_id' => $order['hotel_id'],
                    'shop_id'  => $order['shop_id'],
                    'operation'=> '蜂鸟回调',
                    'order_status'=>isset($params['order_status'])?$params['order_status']:$order['order_status'],
                    'add_time'=> date('Y-m-d H:i:s'),
                    'types' =>2,//后台
                );
                $order_log['action_note'] = '蜂鸟触发更新状态:蜂鸟状态变更为：'.$params['ele_status'] .(isset($params['order_status'])?'|订单状态变为：'.$params['order_status']:'');
                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录

                echo 'success';
                die;
            }
            else
            {
                MYLOG::w($partner_order_code .'订单信息更新失败！' . '| 更新信息为：'.json_encode($params), 'take-away/ele');
            }
        }
        else
        {
            MYLOG::w($partner_order_code . '订单信息有误！', 'take-away/ele');
            die;
        }

        echo 'success';
        die;
    }
}
