<?php
/*
 * 定时取消15分钟内未付款订单
 * author situguanchen  2017-02-06
 */
class Autorun extends MY_Controller {
	function __construct() {
		parent::__construct ();
		$this->debug = $this->input->get ( 'debug' );
		error_reporting ( 0 );
		if (! empty ( $this->debug )) {
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 1 );
        }
		$this->load->library('MYLOG');
	}
    private function check_arrow(){//访问限制
        //var_dump($_SERVER['REMOTE_ADDR']);die;
        return true;
        $arrow_ip = array('127.0.0.1','10.25.168.86','10.25.3.85','10.46.74.165');//只允许服务器自动访问，不能手动
        if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
            exit('非法访问！');
        }
    }

    protected function _load_cache( $name='Cache' ){
        if(!$name || $name=='cache')
            $name='Cache';
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
        return $this->$name;
    }

    //处理核销旧版订单
    public function update_overtime_orders()
    {
        $this->check_arrow();

        set_time_limit ( 0 );
        @ini_set('memory_limit','2048M');

        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();

        //检查是否只有一个实例
        $ok = $redis->setNX('ticket_updating','on');//var_dump($ok);die;
        if(!$ok){
            $last_time = $redis->get('ticket_last_time');//var_dump($last_time);die;
            if(!empty($last_time) && $last_time+600 < time()){//超过十分钟
                MYLOG::w('自动任务进行中，已经超过了十分钟，重置|','ticket');
                $redis->del('ticket_updating');
                $redis->set('ticket_last_time','');
                echo '超时，重置';
                die;
            }
            echo '有其他任务正在运行，请稍候！';exit;
        }
        //记录开始时间
        $redis->set('ticket_last_time',time());
        //取 线上支付，15分钟内未付款的
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $orders = $orderModel->get_cancel_order_list_ticket();
        $success = $fail = 0;
        if(!empty($orders)){
            foreach($orders as $k=>$v){
                if($v['pay_status'] == $orderModel::IS_PAYMENT_NOT && $v['pay_way'] != 3 && $v['order_status'] == $orderModel::OS_UNCONFIRMED){
                    //记录日志
                    MYLOG::w('inter_id:'.$v['inter_id'].'| 需要取消的订单信息：'.json_encode($v),'ticket');
                    //取消订单
                    $res = $orderModel->cancel_order($v,$orderModel::OS_SYS_CANCEL);
                    if($res){
                        $success++;
                    }else{
                        $fail++;
                    }
                }
            }

        }
        //遍历结束，解锁
        $redis->del('ticket_updating');
        //结果信息
        echo "执行完成:总记录:".count($orders)."，发放成功:".$success."，发放失败:".$fail;
    }

    /**
     * 每分钟执行 处理过期不支付订单
     */
    public function update_overtime_orders_ticket()
    {
        $this->check_arrow();

        set_time_limit ( 0 );
        @ini_set('memory_limit','2048M');

        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();

        //检查是否只有一个实例
        $ok = $redis->setNX('ticket_updating_overtime','on');//var_dump($ok);die;
        if(!$ok){
            $last_time = $redis->get('ticket_last_time_overtime');//var_dump($last_time);die;
            if(!empty($last_time) && $last_time+600 < time()){//超过十分钟
                MYLOG::w('自动任务进行中，已经超过了十分钟，重置|','roomservice');
                $redis->del('ticket_updating_overtime');
                $redis->set('ticket_last_time_overtime','');
                echo '超时，重置';
                die;
            }
            echo '有其他任务正在运行，请稍候！';exit;
        }

        //记录开始时间
        $redis->set('ticket_last_time_overtime',time());
        //取 线上支付，15分钟内未付款的

        $this->load->model('ticket/ticket_orders_merge_model');
        $this->load->model('roomservice/roomservice_orders_model');

        $orders_merge_model = $this->ticket_orders_merge_model;
        $orderModel = $this->roomservice_orders_model;
        $merge_orders = $orders_merge_model->get_cancel_order_list();
        $success = $fail = $orderNUm = 0;
        if(!empty($merge_orders))
        {
            foreach($merge_orders as $k=>$merge_order)
            {
                //更改总单状态
                $update = array(
                    'update_time' => date('Y-m-d H:i:s'),
                    'order_status' => 3,
                );
                $where = array(
                    'merge_orderId' => $merge_order['merge_orderId'],
                    'order_status' => 0,
                    'pay_status' => 0,
                );
                $up_res = $orders_merge_model->update_order($update,$where);

                $orders = array();
                if ($up_res > 0)
                {
                    //查询子订单
                    $where_arr = array(
                        'inter_id' => $merge_order['inter_id'],
                        'merge_order_no' => $merge_order['order_no'],
                    );
                    $orders = $this->roomservice_orders_model->get_orders($where_arr,'','*');
                }

                if (!empty($orders))
                {
                    foreach ($orders as $v)
                    {
                        if($v['pay_status'] == $orderModel::IS_PAYMENT_NOT && $v['pay_way'] != 3 && $v['order_status'] == $orderModel::OS_UNCONFIRMED)
                        {
                            //记录日志
                            MYLOG::w('inter_id:'.$v['inter_id'].'| 需要取消的订单信息：'.json_encode($v),'ticket');
                            //取消订单
                            $res = $orderModel->cancel_order($v,$orderModel::OS_SYS_CANCEL);
                            if($res)
                            {
                                //发送模板消息
                                $date = date('Y-m-d',time());
                                if ($date > '2017-05-11')
                                {
                                    $orderModel->handle_order($v['inter_id'],$v['order_id'],$v['openid'],27);
                                }

                                //订单个跟踪
                                $array = array(
                                    'inter_id'=> $v['inter_id'],
                                    'openid' =>'',
                                    'order_id' => $v['order_id'],
                                    'type' => 2,//跟踪
                                    'content'=>'订单已取消',
                                    'order_status'=>$orderModel::OS_SYS_CANCEL,
                                    'add_time' => date('Y-m-d H:i:s')
                                );

                                //记录订单操作日志
                                $order_log = array(
                                    'inter_id' => $v['inter_id'],
                                    'order_id' => $v['order_id'],
                                    'hotel_id' => $v['hotel_id'],
                                    'shop_id'  => $v['shop_id'],
                                    'operation' => '',
                                    'order_status'=>$orderModel::OS_SYS_CANCEL,
                                    'add_time'=> date('Y-m-d H:i:s'),
                                    'action_note'=>'系统自动取消',
                                    'types' => 3,//
                                );

                                $this->db->insert('roomservice_action',$array);
                                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录

                                $success++;
                            }
                            else
                            {
                                $fail++;
                            }
                        }
                    }
                }

                $orderNUm += count($orders);
            }
        }
        //遍历结束，解锁
        $redis->del('ticket_updating_overtime');
        //结果信息
        echo "执行完成:总记录:".$orderNUm."，发放成功:".$success."，发放失败:".$fail;
    }


    /**
     *  每天0点自动核销到期&&待消费订单
     */
    public function update_order_status()
    {
        $this->check_arrow();
        set_time_limit ( 0 );
        @ini_set('memory_limit','2048M');

        //查询到期&&待消费的订单
        $this->load->model('roomservice/roomservice_orders_model');
        $list = $this->roomservice_orders_model->get_finish_order();
        $count = $success = $fail = 0;
        if (!empty($list))
        {
            $count = count($list);
            $merge_orderNO = array();
            foreach ($list as $item)
            {
                //订单跟踪数组
                $array = array(
                    'inter_id'  => $item['inter_id'],
                    'openid'    => '',
                    'order_id'  => $item['order_id'],
                    'type'      => 2,//跟踪
                    'order_status' => 20,//更新后的订单状态
                    'add_time'  => date('Y-m-d H:i:s')
                );

                //记录订单操作日志
                $order_log = array(
                    'inter_id' => $item['inter_id'],
                    'order_id' => $item['order_id'],
                    'hotel_id' => $item['hotel_id'],
                    'shop_id'  => $item['shop_id'],
                    'operation' => '系统',
                    'order_status' => 20,
                    'add_time' => date('Y-m-d H:i:s'),
                    'types' => 2,//后台
                );

                $note = '更新订单状态：从 ' . $this->roomservice_orders_model->os_array[$item['order_status']]
                    . ' 更新到 '.$this->roomservice_orders_model->os_array[20] . ',订单信息：'.json_encode($item);
                //更新订单状态
                $this->db->update('roomservice_orders',array('order_status'=>20),array('inter_id'=>$item['inter_id'],'order_id'=>$item['order_id'],'order_status'=>5));
                $res = $this->db->affected_rows();
                if($res > 0)
                {
                    //发送模板消息
                    $this->roomservice_orders_model->handle_order($item['inter_id'],$item['order_id'],$item['openid'],20);
                    $array['content'] = '订单已核销';
                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表

                    $order_log['action_note'] = $note;
                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录

                    $merge_orderNO[] = $item['merge_order_no'];
                    $success++;
                }
                else
                {
                    $fail++;
                }
            }

            unset($list);
            //更改总订单状态
            $this->load->helper('appointment');
            update_merge_order_status($merge_orderNO);
        }
        echo "执行完成:总记录:".$count."，发放成功:".$success."，发放失败:".$fail;
    }

    /**
     * 更改总订单状态
     * @param $merge_order
     */
    protected function update_merge_order($merge_order)
    {
        if (!empty($merge_order))
        {
            $merge_order = array_unique($merge_order);//去掉重复值
            $this->load->model('ticket/ticket_orders_merge_model');
            foreach ($merge_order as $item)
            {
                $filter = array(
                    'merge_order_no' => $item,
                    'type' => 4,
                );
                $orders = $this->ticket_orders_merge_model->get_orders($filter,'','','w');//防止主从延时
                if (!empty($orders))
                {
                    $count = count($orders);
                    $num = 0;
                    foreach ($orders as $order)
                    {
                        if ($order['order_status'] == 20 || $order['order_status'] == 26)
                        {
                            $num++;
                        }
                    }

                    //全部核销
                    if ($count == $num)
                    {
                        //更改总单状态
                        $update = array(
                            'update_time' => date('Y-m-d H:i:s'),
                            'order_status' => 2,
                        );
                        $where = array(
                            'order_no' => $item,
                        );
                       $this->ticket_orders_merge_model->update_order($update,$where);
                    }
                }
            }
        }
    }


    /**
     * 封装curl的调用接口，get的请求方式
     * @param string 请求URL
     * @param array  请求参数值array(key=>value,...)
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    private function doCurlGetRequest($url, $data = array(), $timeout = 10){
        if($url == "" || $timeout <= 0){
            return false;
        }
        if($data != array()){
            $url = $url . '?' . http_build_query($data);
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);

        $res = curl_exec($con);
        curl_close($con);
        return $res;

    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array  扩展字段值
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    private function doCurlPostRequest($url, $requestString, $extra = array(), $timeout = 10){
        if($url == "" || $requestString == "" || $timeout <= 0){
            return false;
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);

        if(!empty ($extra) && is_array($extra)){
            $headers = array();
            foreach($extra as $opt => $value){
                if(strexists($opt, 'CURLOPT_')){
                    curl_setopt($con, constant($opt), $value);
                } elseif(is_numeric($opt)){
                    curl_setopt($con, $opt, $value);
                } else{
                    $headers [] = "{$opt}: {$value}";
                }
            }
            if(!empty ($headers)){
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
        curl_close($con);
        return $res;
    }


}