<?php
/*
 * 转账
 */
defined('BASEPATH') OR exit('No direct script access allowed');
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
        //return true;
        if(ENVIRONMENT === 'production'){
            $arrow_ip = array('118.178.228.168','118.178.133.170','114.55.234.45');//只允许服务器自动访问，不能手动
            if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
                exit('非法访问！');
            }     
        }else{
            return true;
        }
        
    }

    /**
     * [redis_lock redis上/解锁]
     * @param [type] [操作类型，set/delete]
     * @param [key] [键]
     * @param [value] [type为set时，value是值]
     * @return [boolean] [操作结果]
     */
    protected function redis_lock($type='set' ,$key='_TRANSFER_SEND_LOCK' ,$value='lock'){
        $this->load->library ( 'Cache/Redis_proxy', array (
                'not_init' => FALSE,
                'module' => 'common',
                'refresh' => FALSE,
                'environment' => ENVIRONMENT
        ), 'redis_proxy' );
        $ok = false;
        if($type == 'set'){
            $ok = $this->redis_proxy->setNX ( $key, $value );
        }elseif($type == 'delete' ){
            $ok = $this->redis_proxy->del ( $key );
        }
        return $ok;
    }

    //处理订单
    public function sendrun(){return true;
        log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始处理分账发放脚本...');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始处理分账发放脚本...<br/>';
        $this->check_arrow();
        //上锁
        $ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_transfer/send');
            die('FAILURE!');
        }

        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        //获取当天汇总出来的数据
        $this->load->model ( 'iwidepay/iwidepay_deliver_model');
        $info = $this->iwidepay_deliver_model->get_deliver_data();//获取今天需要发放的数据
        
        echo '待发放数量'.count($info).'<br/>';
        if(empty($info)){
            echo 'empty data!';
            die;
        }
        foreach($info as $k=>$v){
            $this->iwidepay_deliver_model->handle_transfer($v);
        }
        echo 'done';
        $this->redis_lock('delete');
        log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束分账发放脚本...');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束分账发放脚本...<br/>';
        //遍历结束，解锁
        //释放锁
    } 

     //商户编号，商户名称
    private function merchant_info(){
        $data = array('account'=>'','name'=>'');
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $data['account'] = "850440053991272";
            $data['name'] = '广州金房卡信息科技有限公司';
        }else{
            $data['account'] =  "850440053991272";
            $data['name'] = '广州金房卡信息科技有限公司';
        }
        return $data;
    }

    //汇总数据
    public function sum_info(){
        $this->check_arrow();
        //上锁
        $ok = $this->redis_lock('set','_CMBC_SUM_RECORD');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_transfer/sum_info');
            die('FAILURE!');
        }
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        MYLOG::w('-开始处理汇总脚本', 'iwidepay/transfer_auto');
        $this->load->model ( 'iwidepay/iwidepay_transfer_model' );
        $data = array();
        //先查下有咩有指定日期的数据
        $data = $this->iwidepay_transfer_model->get_sum_record();
        if(empty($data)){
            //作废：手动转账，先将前一天还没处理转账的数据update为10：放弃转账
            /*$this->db->where(array('status'=>0,'add_time <'=>date('Y-m-d 00:00:00')));
            $this->db->update('iwidepay_sum_record',array('status'=>10));*/
            $res = $this->iwidepay_transfer_model->get_transfer_data();
            if(empty($res)){
                    echo '空数据';
                    $this->redis_lock('delete','_CMBC_SUM_RECORD');
                    die;
            }
            //start这里先做一次按银行账号汇总 账号作为键值
            $tmp_res = array();
            foreach($res as $k=>$v){
                $tmp_res[$v['bank_card_no']]['ids'][] = $v['id']; 
                $tmp_res[$v['bank_card_no']]['sum_trans_amt'] = isset($tmp_res[$v['bank_card_no']]['sum_trans_amt'])?$tmp_res[$v['bank_card_no']]['sum_trans_amt']+$v['amount']:$v['amount'];
                $tmp_res[$v['bank_card_no']]['m_id'] = $v['m_id'];
                $tmp_res[$v['bank_card_no']]['bank'] = $v['bank'];
                $tmp_res[$v['bank_card_no']]['bank_card_no'] = $v['bank_card_no'];
                $tmp_res[$v['bank_card_no']]['bank_user_name'] = $v['bank_user_name'];
                $tmp_res[$v['bank_card_no']]['bank_code'] = $v['bank_code'];
                $tmp_res[$v['bank_card_no']]['bank_city'] = $v['bank_city'];
                $tmp_res[$v['bank_card_no']]['accBankNo'] = $v['accBankNo'];
                $tmp_res[$v['bank_card_no']]['clearBankNo'] = $v['clearBankNo'];
                $tmp_res[$v['bank_card_no']]['is_company'] = $v['is_company'];
            }
            unset($res);
            //end
            $settle_res = $this->iwidepay_transfer_model->get_settlement_info();
            if(empty($settle_res)){
                echo '查询分账结算表无数据！';
                $this->redis_lock('delete','_CMBC_SUM_RECORD');
                die;
            }
            
            $settle_arr =  array();
            foreach($settle_res as $sk=>$sv){
                $settle_arr[$sv['bank_card_no']]['sum_amount'] = isset($settle_arr[$sv['bank_card_no']]['sum_amount'])?$settle_arr[$sv['bank_card_no']]['sum_amount']+$sv['orig_amount']:$sv['orig_amount'];//统计用的
                $settle_arr[$sv['bank_card_no']]['trans_amount'] = isset($settle_arr[$sv['bank_card_no']]['trans_amount'])?$settle_arr[$sv['bank_card_no']]['trans_amount']+$sv['amount']:$sv['amount'];//结算用的
                $settle_arr[$sv['bank_card_no']]['set_id'][] = $sv['id'];
            }
            $merchant_info = $this->merchant_info();
            
            foreach($tmp_res as $rk=>$rv){
                $tmp = $ids = $set_ids =  array();
                $ids = $rv['ids'];//transfer表的id 
                $set_ids = isset($settle_arr[$rv['bank_card_no']]['set_id'])?$settle_arr[$rv['bank_card_no']]['set_id']:array();
                $tmp['amount'] = isset($settle_arr[$rv['bank_card_no']]['trans_amount'])?$settle_arr[$rv['bank_card_no']]['trans_amount']:0;
                $tmp['sum_amount'] = $rv['sum_trans_amt'];
                $tmp['m_id'] = $rv['m_id'];
                $tmp['bank'] = $rv['bank'];
                $tmp['bank_card_no'] = $rv['bank_card_no'];
                $tmp['bank_user_name'] = $rv['bank_user_name'];
                $tmp['handle_date'] = date('Ymd');
                $tmp['bank_code'] = $rv['bank_code'];
                $tmp['bank_city'] = $rv['bank_city'];
                $tmp['add_time'] = date('Y-m-d H:i:s');
                $tmp['merchant_name'] =  $merchant_info['name'];
                $tmp['merchant_no'] = $merchant_info['account'];
                $tmp['accBankNo'] = $rv['accBankNo'];
                $tmp['clearBankNo'] = $rv['clearBankNo'];
                $tmp['is_company'] = $rv['is_company'];
                if(isset($settle_arr[$rv['bank_card_no']]['sum_amount']) && $settle_arr[$rv['bank_card_no']]['sum_amount'] && $settle_arr[$rv['bank_card_no']]['sum_amount']==$rv['sum_trans_amt']){
                    $tmp['status'] = 0;
                }else{
                    $tmp['status'] = 9;//匹配异常
                }
                $result = $this->db->insert('iwidepay_sum_record',$tmp);
                $insert_id = $this->db->insert_id();
                if(!$result){
                    echo 'insert error';
                    MYLOG::w('处理汇总脚本insert出错', 'iwidepay/transfer_auto');
                    die;
                }
                //批量更新 transfer 表
                if(!empty($ids)){
                    //$ids = explode(',',$ids);
                    $this->db->where_in('id',$ids);
                    $all_update = $this->db->update('iwidepay_transfer',array('record_id'=>$insert_id,'send_status'=>1,'send_time'=>date('Y-m-d H:i:s')));
                    if(!$this->db->affected_rows()){
                        echo 'update error';
                        MYLOG::w('处理汇总脚本update transfer表出错', 'iwidepay/transfer_auto');
                        die;
                    }
                } 
                //批量更新settlement表  
                if(!empty($set_ids)){
                    $this->db->where_in('id',$set_ids);
                    $settle_res = $this->db->update('iwidepay_settlement',array('record_id'=>$insert_id));
                    if(!$settle_res){
                        echo 'update error';
                        MYLOG::w('处理汇总脚本update settlement表出错', 'iwidepay/transfer_auto');
                        die;
                    }
                }
            }
        }
        $this->redis_lock('delete','_CMBC_SUM_RECORD');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束处理汇总脚本...<br/>';
        MYLOG::w('结束处理汇总脚本', 'iwidepay/transfer_auto');

    }

    //结算汇总数据
    public function settlement_info()
    {
        $this->check_arrow();
        //上锁

        $ok = $this->redis_lock('set','_CMBC_SETTLEMENT_RECORD');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_transfer/settlement_info');
            die('FAILURE!');
        }


        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        MYLOG::w('-开始处理汇总脚本', 'iwidepay/settlement_info');
        $this->load->model ( 'iwidepay/iwidepay_transfer_model' );

        //查询 昨天未转账订单
        $start_time = date('Y-m-d',strtotime('-1 days'));
        $end_time = date('Y-m-d 00:00:00');

        $last_day_settlement = $this->iwidepay_transfer_model->last_day_settlement($start_time,$end_time);
        if (!empty($last_day_settlement))
        {
            //生成新的记录
            MYLOG::w(json_encode($last_day_settlement), 'iwidepay/transfer_auto_settlement');
            foreach ($last_day_settlement as $value)
            {
                unset($value['id']);
                $item = $value;
                $item['handle_date'] = date('Ymd');
                $item['add_time'] = date('Y-m-d H:i:s');
                $this->db->insert('iwidepay_settlement',$item);
                //$this->db->insert_id();
            }
        }

        //先update 今天之前未转账的记录

        $this->db->where('status',0);
        $this->db->where('add_time <',date('Y-m-d 00:00:00'));
        $this->db->update('iwidepay_settlement',array('status' => 10));

        $res = $this->iwidepay_transfer_model->get_settlement_transfer();
        if(empty($res))
        {
            echo '无记录可导出';
            die;
        }
        $merchant_info = $this->merchant_info();

        $temp = array();
        //处理数据按照金房卡，集团，门店 生成新的数组
        foreach ($res as $k => $val)
        {
            //金房卡记录
            if (in_array($val['type'],array('cost','jfk','dist'),true))
            {
                $key = 'jinfangka';

                isset($temp[$key]['sum_trans_amt']) ? ($temp[$key]['sum_trans_amt'] += $val['amount']) : ($temp[$key]['sum_trans_amt'] = $val['amount']);
                $val['sum_trans_amt'] = $temp[$key]['sum_trans_amt'];
                $val['inter_id'] = $key;
                $val['hotel_id'] = 0;
                $val['type'] = 'jfk';

                $temp[$key] = $val;

            }
            //集团
            else if ($val['type'] == 'group')
            {
                $key = $val['inter_id'].'_group';
                $val['hotel_id'] = 0;
                isset($temp[$key]['sum_trans_amt']) ? ($temp[$key]['sum_trans_amt'] += $val['amount']) : ($temp[$key]['sum_trans_amt'] = $val['amount']);

                $val['sum_trans_amt'] = $temp[$key]['sum_trans_amt'];

                $temp[$key] = $val;
            }
            //门店
            else if($val['type'] == 'hotel')
            {
                $key = $val['inter_id'].'_'.$val['hotel_id'].'_hotel';

                isset($temp[$key]['sum_trans_amt']) ? ($temp[$key]['sum_trans_amt'] += $val['amount']) : ($temp[$key]['sum_trans_amt'] = $val['amount']);
                $val['sum_trans_amt'] = $temp[$key]['sum_trans_amt'];

                $temp[$key] = $val;
            }
        }


        if (!empty($temp))
        {
            foreach($temp as $rk=>$rv)
            {
                $tmp = array();
                $tmp['inter_id'] = $rv['inter_id'];
                $tmp['hotel_id'] = $rv['hotel_id'];
                $tmp['type'] = $rv['type'];
                $tmp['amount'] = $rv['sum_trans_amt'];
                $tmp['orig_amount'] = $rv['sum_trans_amt'];
                $tmp['bank'] = $rv['bank'];
                $tmp['bank_card_no'] = $rv['bank_card_no'];
                $tmp['bank_user_name'] = $rv['bank_user_name'];
                $tmp['handle_date'] = date('Ymd');
                $tmp['bank_code'] = $rv['bank_code'];
                $tmp['bank_city'] = $rv['bank_city'];
                $tmp['add_time'] = date('Y-m-d H:i:s');
                $tmp['merchant_name'] =  $merchant_info['name'];
                $tmp['merchant_no'] = $merchant_info['account'];
                $tmp['accBankNo'] = $rv['accBankNo'];
                $tmp['clearBankNo'] = $rv['clearBankNo'];
                $tmp['is_company'] = $rv['is_company'];

                //存在则更改，否则新增
                $where_arr = array(
                    'inter_id' => $tmp['inter_id'],
                    'hotel_id' => $tmp['hotel_id'],
                    'type' => $tmp['type'],
                    'handle_date' => $tmp['handle_date'],
                );
                $set_id = $this->iwidepay_transfer_model->get_inter_settlement('id',$where_arr);
                if (!empty($set_id))
                {
                    $up_date = array(
                        'amount' => '`amount` + ' .$tmp['amount'],
                    );
                    $res = $this->iwidepay_transfer_model->update_settlement($up_date,array('id' => $set_id['id']));
                    if (!$res)
                    {
                        echo 'update error';
                        MYLOG::w('处理汇总脚本update出错', 'iwidepay/transfer_auto_settlement');
                    }
                }
                else
                {
                    $this->db->insert('iwidepay_settlement',$tmp);
                    $insert_id = $this->db->insert_id();
                    if(!$insert_id)
                    {
                        echo 'insert error';
                        MYLOG::w('处理汇总脚本insert出错', 'iwidepay/transfer_auto_settlement');
                    }
                }
            }
        }

        $this->redis_lock('delete','_CMBC_SETTLEMENT_RECORD');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束处理汇总结算脚本...<br/>';
        MYLOG::w('结束处理汇总结算脚本', 'iwidepay/transfer_auto_settlement');

    }

    //查询各个模块的退款订单 更新订单状态
    public function auto_refund_handle(){
        
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始处理民生退款处理脚本...<br/>';
        $this->check_arrow();
        //上锁
        $ok = $this->redis_lock('set','_CMBC_REFUDN_HANDLE');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_transfer/refund_script');
            die('FAILURE!');
        }
        MYLOG::w('-开始处理民生退款处理脚本', 'iwidepay/refund_script');
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        //先查询所有发起退款的退款失败，退款异常的订单 
        $this->load->model ( 'iwidepay/iwidepay_model' );
        $this->load->model ( 'iwidepay/iwidepay_refund_model' );
        $orders = $this->iwidepay_model->get_refund_orders();//$orders = array($orders[0]);//var_dump($orders);die;
        if(empty($orders)){
            echo '退款脚本无数据';
            $ok = $this->redis_lock('delete','_CMBC_REFUDN_HANDLE');
            die;
        }
        foreach($orders as $k=>$v){
            if($v['refund_status'] == 10){//异常的单 查询状态 更改iwidepay表的状态，因为民生返回异常，业务模式是返回成功回应的
                //查询refund表 拿出退款单号去民生那边查
                $refund_order = $this->iwidepay_model->get_refund_no($v['order_no'],3);//异常的单
                if($refund_order){
                    if(!empty($refund_order['script_refund_order_no'])){//脚本退过没成功走这里
                        $res = $this->iwidepay_model->order_query($refund_order['script_refund_order_no'],$refund_order['script_refund_order_date'],2);
                    }else{
                        $res = $this->iwidepay_model->order_query($refund_order['refund_order_no'],$refund_order['refund_order_date'],2);
                    }
                    if($res['origRespCode'] === '0000' ){//成功了 更新表
                        //$this->db->where(array('id'=>$refund_order['id']));
                        $refund_update = $this->iwidepay_refund_model->update_data(array('id'=>$refund_order['id']),array('refund_status'=>1));
                        //更新iwidepayorder表
                        $this->iwidepay_model->update_iwidepay_order($v['order_no'],$v['module'],array('refund_status'=>8));//成功
                    }
                }else{
                    MYLOG::w('民生退款处理脚本:退款表无异常记录,order_no:' .$v['order_no'] , 'iwidepay/refund_script');
                }
            }elseif($v['refund_status'] == 9){//失败的单 是余额不够，民生是返回 余额冻结失败 退款单号不能重入，会报系统错误
                //在发起一次退款 先查询最后一次的退款记录
                $refund_order = $this->iwidepay_model->get_refund_no($v['order_no'],2);//失败的单
                if(empty($refund_order)){
                    MYLOG::w('民生退款处理脚本:退款表无失败记录,order_no:' .$v['order_no'] , 'iwidepay/refund_script');
                    continue;
                }
                //分账退款
                $iwidepay_refund = array(
                    'orderDate' => date('Ymd'),
                    'orderNo' => $v['order_no'] . time() . rand(1000,9999),
                    'requestNo' => md5($v['order_no'] . time() . rand(1000,9999)),
                    'transAmt' => $refund_order['refund_amt'],//单位：分
                    'returnUrl'=>'http://cmbcpay.jinfangka.com/index.php',
                    'refundReson' => '脚本退款',
                    'ori_refund_id' => $refund_order['id'],
                );
                MYLOG::w('民生退款脚本:原退款状态,order_no:' .$v['order_no'] .'|refund_status:'.$refund_order['refund_status'] .'|脚本退款单号:' . $iwidepay_refund['orderNo'] . '|日期：'.$iwidepay_refund['orderDate'].'|金额：'.$iwidepay_refund['transAmt'], 'iwidepay/refund_script');
                $hand_res = $this->iwidepay_model->handle_refund_request($v,$iwidepay_refund,$refund_order['type'],true);
                $arr_query = $hand_res['arr_query'];
                //$refund_id = $hand_res['refund_id'];
                if(isset($arr_query['respCode'])){
                    if($arr_query['respCode'] === '0000'){
                        $up_data['refund_status']=8;
                        //先更新iwidepay_orders的订单状态 ，再改变refund表的退款状态
                        $order_update = $this->iwidepay_model->update_iwidepay_order($v['order_no'],$v['module'],$up_data);
                        //$this->db->where(array('id'=>$refund_id));
                        $refund_update = $this->iwidepay_refund_model->update_data(array('id'=>$refund_order['id']),array('refund_status'=>1));
                    }elseif($arr_query['respCode'] == 'P000' || $arr_query['respCode'] == '9999'|| $arr_query['respCode'] == '9997'|| $arr_query['respCode'] == '0028'){
                        $up_data['refund_status']=10;
                        $order_update = $this->iwidepay_model->update_iwidepay_order($v['order_no'],$v['module'],$up_data);//异常
                        //$this->db->where(array('id'=>$refund_id));
                        $refund_update = $this->iwidepay_refund_model->update_data(array('id'=>$refund_order['id']),array('refund_status'=>3));//异常
                    }else{
                        $up_data['refund_status']= ($arr_query['respCode'] == '0042' || $arr_query['respCode'] == '0066')?9:11;
                        $order_update = $this->iwidepay_model->update_iwidepay_order($v['order_no'],$v['module'],$up_data);
                        //$this->db->where(array('id'=>$refund_id));
                        $refund_update = $this->iwidepay_refund_model->update_data(array('id'=>$refund_order['id']),array('refund_status'=>2));//失败
                    }
                }
            }
        }
        $this->redis_lock('delete','_CMBC_REFUDN_HANDLE');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束民生退款处理脚本...<br/>';
        MYLOG::w('结束处理民生退款处理脚本', 'iwidepay/refund_script');
    }


    /**
     * 计划任务生成 财务对账单 => 每天退款订单
     */
    public function run_refund_financial()
    {
        $this->check_arrow();

        //上锁
        $ok = $this->redis_lock('set','_CMBC_REFUND_FINANCIAL_');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_financial/refund');
            die('FAILURE!');
        }

        set_time_limit(0);
        @ini_set('memory_limit','1624M');

        $this->load->model('iwidepay/Iwidepay_financial_model');

        //退款记录
        $stat_time = date('Y-m-d',strtotime('-1 days'));
        $end_time = date('Y-m-d 23:59:60',strtotime('-1 days'));
        $list_refund = $this->Iwidepay_financial_model->refund_order($stat_time,$end_time,'1,3');
        if (!empty($list_refund))
        {
            //插入对账单表
            foreach ($list_refund as $value)
            {
                $item = array(
                    'module'    => $value['module'],
                    'order_no'  => $value['orig_order_no'],
                    'pay_no'    => $value['ori_pay_no'],
                    'trade_type' => in_array($value['type'],array(1,3)) ? 3 : 2, //2-垫付退款,3-原款退款
                    'transfer_status' => 3, //3-已结清
                    'transfer_date' => date('Y-m-d',strtotime($value['add_time'])),
                    'amount' => $value['refund_amt'],
                    'inter_id' => $value['inter_id'],
                    'hotel_id' => $value['hotel_id'],
                    'trade_time' => $value['add_time'],
                    'add_time' => date('Y-m-d H:i:s'),
                );
                $this->Iwidepay_financial_model->insert_order($item);
            }
        }

        $this->redis_lock('delete','_CMBC_REFUND_FINANCIAL_');
    }


    /**
     * 计划任务生成 财务对账单 => 每天欠款订单
     */
    public function run_debt_financial()
    {
        $this->check_arrow();

        //上锁
        $ok = $this->redis_lock('set','_CMBC_DEBT_FINANCIAL_');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_financial/debt');
            die('FAILURE!');
        }

        set_time_limit(0);
        @ini_set('memory_limit','1624M');
        $this->load->model('iwidepay/Iwidepay_financial_model');

        //退款记录
        $stat_time = date('Y-m-d');
        $end_time = date('Y-m-d 23:59:60');
        $list_debt = $this->Iwidepay_financial_model->debt_order($stat_time,$end_time);
        if (!empty($list_debt))
        {
            //插入对账单表
            foreach ($list_debt as $value)
            {
                $item = array(
                    'module'    => $value['module'],
                    'order_no'  => $value['order_no'],
                    'pay_no'    => $value['ori_pay_no'],
                    'trade_type' => $this->get_financial_type($value['order_type']), //3-原款退款
                    'transfer_status' => 3, //3-已结清
                    'transfer_date' => date('Y-m-d',strtotime($value['add_time'])),
                    'inter_id' => $value['inter_id'],
                    'hotel_id' => $value['hotel_id'],
                    'trade_time' => $value['add_time'],
                    'add_time' => date('Y-m-d H:i:s'),
                    'amount' => $value['amount'],
                );

                //线下交易
                if ($item['trade_type'] == 7)
                {
                    $ext_info = json_decode($value['ext_info'],true);
                    $item['amount'] = !empty($ext_info['orig_amount']) ? $ext_info['orig_amount'] : 0;
                    $item['jfk_amount'] = !empty($ext_info['jfk_amount']) ? $ext_info['jfk_amount'] : 0;
                    $item['group_amount'] = !empty($ext_info['group_amount']) ? $ext_info['group_amount'] : 0;
                    $item['dist_amount'] = !empty($ext_info['dist_amount']) ? $ext_info['dist_amount'] : 0;
                }
                else if ($item['trade_type'] == 6)
                {
                    $item['module'] = 'base_pay';
                }
                else if (in_array($item['trade_type'],array(4,5)))
                {
                    $item['module'] = 'dist';
                }

                $this->Iwidepay_financial_model->insert_order($item);
            }
        }

        $this->redis_lock('delete','_CMBC_DEBT_FINANCIAL_');
    }

    /**
     * 计划任务生成 财务对账单 => 每天分账订单
     */
    public function run_transfer_financial()
    {
        $this->check_arrow();

        //上锁
        $ok = $this->redis_lock('set','_CMBC_TRANSFER_FINANCIAL_');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_financial/transfer');
            die('FAILURE!');
        }

        set_time_limit(0);
        @ini_set('memory_limit','1624M');
        $this->load->model('iwidepay/Iwidepay_financial_model');
        $this->load->model('iwidepay/Iwidepay_order_model');
        $this->load->model('iwidepay/Iwidepay_transfer_model');

        //退款记录
        $stat_time = date('Y-m-d');//,strtotime('-1 days')
        $end_time = date('Y-m-d 23:59:60');//,strtotime('-1 days')
        $list_transfer = $this->Iwidepay_financial_model->transfer_order($stat_time,$end_time);

        if (!empty($list_transfer))
        {
            $temp = $order_types = array();
            //插入对账单表
            foreach ($list_transfer as $value)
            {
                $add_key = $value['module'] .'_'.$value['order_no'];
                $temp[$add_key]['module'] = $value['module'];
                $temp[$add_key]['amount'] = $value['orig_amount'];
                $temp[$add_key]['order_no_main'] = $value['order_no_main'];
                $temp[$add_key]['pay_no'] = $value['pay_no'];
                $temp[$add_key]['order_no'] = $value['order_no'];
                $temp[$add_key]['transfer_date'] = $value['transfer_date'];
                $temp[$add_key]['inter_id'] = $value['inter_id'];
                $temp[$add_key]['hotel_id'] = $value['hotel_id'];
                $temp[$add_key]['trade_time'] = $value['add_time'];
                $temp[$add_key]['write_off_hotel_id'] = $value['write_off_hotel_id'];
                $temp[$add_key]['add_time'] = date('Y-m-d H:i:s');

                //核销订单
                $status_key = $value['order_no'].'_'.$value['write_off_hotel_id'];
                $order_types[$status_key] = array(
                    'off_hotel_id' => $value['write_off_hotel_id'],
                    'order_no' => $value['order_no'],
                    'module' => $value['module'],
                );

                //分成金额
                $amount_key = $status_key;
                $tmp[$amount_key][$value['type']] = $value['amount'];
            }

            unset($list_transfer);
            $list_transfer = null;

            if (!empty($order_types))
            {
                foreach ($order_types as $key => $value)
                {
                    $add_key = $value['module'] .'_'.$value['order_no'];
                    $item = $temp[$add_key];
                    $item['transfer_status'] = 2;
                    //部分分账
                    if ($value['off_hotel_id'] == '9999999')
                    {
                        $item['transfer_status'] = 1;
                    }

                    $item['write_off_hotel_id'] = $value['off_hotel_id'];
                    $item['trade_type'] = 1;

                    $status_key = $value['order_no'].'_'.$value['off_hotel_id'];
                    $item['cost_amount'] = !empty($tmp[$status_key]['cost']) ? $tmp[$status_key]['cost'] : 0;
                    $item['jfk_amount'] = !empty($tmp[$status_key]['jfk']) ? $tmp[$status_key]['jfk'] : 0;
                    $item['group_amount'] = !empty($tmp[$status_key]['group']) ? $tmp[$status_key]['group'] : 0;
                    $item['hotel_amount'] = !empty($tmp[$status_key]['hotel']) ? $tmp[$status_key]['hotel'] : 0;
                    $item['dist_amount'] = !empty($tmp[$status_key]['dist']) ? $tmp[$status_key]['dist'] : 0;

                    $this->Iwidepay_financial_model->insert_order($item);
                }
            }
        }

        $this->redis_lock('delete','_CMBC_TRANSFER_FINANCIAL_');
    }

    /**
     * 或者对账单类型
     * @param $order_type
     * @return int
     */
    protected function get_financial_type($order_type)
    {
        switch($order_type)
        {
            case 'order':
                $trade_type = 7;
                break;
            case 'base_pay':
                $trade_type = 6;
                break;
            case 'refund':
                $trade_type = 2;
                break;
            case 'orderReward':
                $trade_type = 4;
                break;
            case 'extraReward':
                $trade_type = 5;
                break;
            default :
                $trade_type = 0;
                break;
        }

        return $trade_type;
    }
}