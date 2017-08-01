<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Dadareturn extends MY_Controller {

    //达达订单状态异步通知地址
    public function dada_rtn()
    {
		$this->load->library('MYLOG');
        $content = file_get_contents ( 'php://input' );
        if(empty($content)){
            die ( '这是请求的接口地址，直接在浏览器里无效' );
        }
        MYLOG::w('return_res|'.$content,'roomservice/dada');
        $arr = json_decode ( $content, true );
        $inter_id = $this->uri->segment(3);
        $data = array();
        $data['inter_id'] = $inter_id;
        $data['order_sn'] = $arr['order_id'];
        $data['dada_order_status'] = $arr['order_status'];
        $data['dada_update_time'] = $arr['update_time'];
        $data['rec_content'] = $content;
        $data['add_time'] = time();
        $data['cancel_reason'] = $arr['cancel_reason'];
        $data['cancel_from'] = $arr['cancel_from'];
        $data['dada_dm_id'] = isset($arr['dm_id'])?$arr['dm_id']:0;
        $data['dada_dm_name'] = isset($arr['dm_name'])?$arr['dm_name']:'';
        $data['dada_dm_mobile'] = isset($arr['dm_mobile'])?$arr['dm_mobile']:'';
        $this->db->insert ( 'dada_log', $data );

        //先查询该订单是否存在
        $this->db->where ( array (
            'inter_id' => $inter_id,
            'order_sn' => $arr['order_id'],//订单号 传给达达的也是订单号
        ) );
        $order = $this->db->get ( 'roomservice_orders' )->row_array ();
        if($order && $order['type'] == 3 && $order['shipping_type']==2)
        {
            //获取达达配置信息
            $this->load->model('roomservice/roomservice_dada_model');
            $dadaInfo = $this->roomservice_dada_model->get(array('inter_id'=>$inter_id,'hotel_id'=>$order['hotel_id'],'status'=>1,'type'=>1));
            //验证签名
            $sign_data = array('client_id'=>$arr['client_id'],'order_id'=>$arr['order_id'],'update_time'=>$arr['update_time']);
            $sign = $this->get_sign($dadaInfo,$sign_data);
            if($sign != $arr['signature']){
                MYLOG::w($data['order_id'].'签名错误！', 'roomservice/dada');
                die;
            }
            //更新相应的达达状态
            $where = array('order_sn'=>$arr['order_id'],'inter_id'=>$inter_id);
            $params = array(
                'dada_status' => $arr['order_status'],
            );
            if($arr['order_status'] == 3){//配送中，需要更新订单状态为已配送
                $params['order_status'] = 10;//订单状态：10=》已配送
            }elseif($arr['order_status'] == 4){//完成，需要封信订单状态为已完成
                $params['order_status'] = 20;//订单状态：20=》已完成
            }
            $res = $this->db->update('roomservice_orders',$params,$where);
            if($res){
                if(isset($params['order_status']) && ($params['order_status'] == 10 || $params['order_status'] == 20)){//改状态
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
                    'operation'=> '达达回调',
                    'order_status'=>isset($params['order_status'])?$params['order_status']:$order['order_status'],
                    'add_time'=> date('Y-m-d H:i:s'),
                    'types' =>2,//后台
                );
                $order_log['action_note'] = '达达触发更新状态:达达状态变更为：'.$params['dada_status'] .(isset($params['order_status'])?'|订单状态变为：'.$params['order_status']:'');
                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                echo 'success';
                die;
            }else{
                MYLOG::w($data['order_id'].'订单信息更新失败！' . '| 更新信息为：'.json_encode($params), 'roomservice/dada');
            }
        }else{
            MYLOG::w($data['order_id'].'订单信息有误！', 'roomservice/dada');
            die;
        }
        echo 'success';
        die;
    }

    //签名验证
    private function get_sign($dadaInfo,$data){

        $args = array();
        foreach ($data as $key => $value) {
            $args[]=$value;
        }
        sort($args,SORT_STRING);
        $args = implode('',$args);
        $sign = md5($args);

        return $sign;
    }

    /**
     * POST 请求
     *
     * @param string $url
     * @param array $param
     * @return string content
     */
    private function http_post($url, $param) {
        $oCurl = curl_init ();
        if (stripos ( $url, "https://" ) !== FALSE) {
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
        }
        if (is_string ( $param )) {
            $strPOST = $param;
        } else {
            $aPOST = array ();
            foreach ( $param as $key => $val ) {
                $aPOST [] = $key . "=" . urlencode ( $val );
            }
            $strPOST = join ( "&", $aPOST );
        }
        curl_setopt ( $oCurl, CURLOPT_URL, $url );
        curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $oCurl, CURLOPT_POST, true );
        curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
        $sContent = curl_exec ( $oCurl );
        $aStatus = curl_getinfo ( $oCurl );
        curl_close ( $oCurl );
        if (intval ( $aStatus ["http_code"] ) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

}
