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
        $arrow_ip = array('10.25.168.86','10.25.3.85','10.46.74.165','10.25.1.106');//只允许服务器自动访问，不能手动
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


    //处理订单
    public function update_overtime_orders(){
        $this->check_arrow();

        set_time_limit ( 0 );
        @ini_set('memory_limit','2048M');

        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();

        //检查是否只有一个实例
        $ok = $redis->setNX('roomservice_updating','on');//var_dump($redis->del('roomservice_updating'));die;
        if(!$ok){
            $last_time = $redis->get('roomservice_last_time');//var_dump($last_time);die;
            if(!empty($last_time) && $last_time+600 < time()){//超过十分钟
                MYLOG::w('自动任务进行中，已经超过了十分钟，重置|','roomservice');
                $redis->del('roomservice_updating');
                $redis->set('roomservice_last_time','');
                echo '超时，重置';
                die;
            }
            echo '有其他任务正在运行，请稍候！';exit;
        }
        //记录开始时间
        $redis->set('roomservice_last_time',time());
        //取 线上支付，15分钟内未付款的
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $orders = $orderModel->get_cancel_order_list();
        $success = $fail = 0;//var_dump($orders);die;
        if(!empty($orders)){
            foreach($orders as $k=>$v){
                if($v['pay_status'] == $orderModel::IS_PAYMENT_NOT && $v['pay_way'] != 3 && $v['order_status'] == $orderModel::OS_UNCONFIRMED){
                    //记录日志
                    MYLOG::w('inter_id:'.$v['inter_id'].'| 需要取消的订单信息：'.json_encode($v),'roomservice');
                    //取消订单
                    $res = $orderModel->cancel_order($v,$orderModel::OS_SYS_CANCEL);
                    if($res){

                        //发送模板消息
                        $date = date('Y-m-d',time());
                        if ($date > '2017-05-11')
                        {
                            $orderModel->handle_order($v['inter_id'],$v['order_id'],$v['openid'],27);
                        }
                        $success++;
                    }else{
                        $fail++;
                    }
                }
            }

        }
        //遍历结束，解锁
        $redis->del('roomservice_updating');
        //结果信息
        echo "执行完成:总记录:".count($orders)."，发放成功:".$success."，发放失败:".$fail;
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