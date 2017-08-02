<?php
/*
 * 转账
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
        $arrow_ip = array('118.178.228.168','118.178.133.170','114.55.234.45');//只允许服务器自动访问，不能手动
        if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
            exit('非法访问！');
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
    public function sendrun(){
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
            $res = $this->iwidepay_transfer_model->get_transfer_data();
            if(empty($res)){
                    echo '无记录可导出';
                    die;
            }
            $merchant_info = $this->merchant_info();
            
            foreach($res as $rk=>$rv){
                $tmp = $ids = array();
                $ids = $rv['ids'];
                $tmp['amount'] = $rv['sum_trans_amt'];
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
                $result = $this->db->insert('iwidepay_sum_record',$tmp);
                $insert_id = $this->db->insert_id();
                if(!$result){
                    echo 'insert error';
                    MYLOG::w('处理汇总脚本insert出错', 'iwidepay/transfer_auto');
                    die;
                }
                $data[] = $tmp;
                //批量更新
                if(!empty($ids)){
                    $ids = explode(',',$ids);
                    $this->db->where_in('id',$ids);
                    $all_update = $this->db->update('iwidepay_transfer',array('record_id'=>$insert_id));
                    if(!$all_update){
                        echo 'update error';
                        MYLOG::w('处理汇总脚本update transfer表出错', 'iwidepay/transfer_auto');
                        die;
                    }
                }
            }
        }
        $this->redis_lock('delete','_CMBC_SUM_RECORD');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束处理汇总脚本...<br/>';
        MYLOG::w('结束处理汇总脚本', 'iwidepay/transfer_auto');

    }

    //查询各个模块的退款订单 更新订单状态
    public function auto_refund_handle(){
        MYLOG::w('-开始处理民生退款处理脚本', 'iwidepay/refund_handle');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始处理民生退款处理脚本...<br/>';
        $this->check_arrow();
        //上锁
        $ok = $this->redis_lock('set','_CMBC_REFUDN_HANDLE');
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_transfer/refund_handle');
            die('FAILURE!');
        }

        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        //先查询所有发起退款的退款失败，退款异常的订单 
        $this->load->model ( 'iwidepay/iwidepay_model' );
        $orders = $this->iwidepay_model->get_refund_orders();
        if(empty($orders)){
            echo '退款脚本无数据';
            die;
        }
        foreach($orders as $k=>$v){
            if($v['refund_status'] == 10){//异常的单 查询状态 更改iwidepay表的状态，因为民生返回异常，业务模式是返回成功回应的
                //查询refund表 拿出退款单号去民生那边查
                $refund_order = $this->iwidepay_model->get_refund_no($v['order_no'],3);//异常的单
                if($refund_order){
                    $res = $this->iwidepay_model->order_query($refund_order['refund_order_no'],$refund_order['refund_order_date'],2);
                    if($res['origRespCode'] === '0000' ){//成功了 更新表
                        $this->db->where(array('id'=>$refund_order['id']));
                        $refund_update = $this->db->update('iwidepay_refund',array('refund_status'=>1));
                        //更新iwidepayorder表
                        $this->iwidepay_model->update_iwidepay_order($v['order_no'],$v['module'],array('refund_status'=>8));//成功
                    }
                }
            }elseif($v['refund_status'] == 9){//失败的单 是余额不够，民生是返回 余额冻结失败 退款单号不能重入，会报系统错误
                //在发起一次退款 先查询最后一次的退款记录
                $refund_order = $this->iwidepay_model->get_refund_no($v['order_no'],2);//失败的单
                //分账退款
                $iwidepay_refund = array(
                    'orderDate' => date('Ymd'),
                    'orderNo' => $v['order_no'] . time() . rand(10000,99999),
                    'requestNo' => md5($v['order_no'] . time() . rand(10000,99999)),
                    'transAmt' => $refund_order['refund_amt'],//单位：分
                    'returnUrl'=>'http://cmbcpay.jinfangka.com/index.php',
                    'refundReson' => '脚本发起退款',
                );
                $hand_res = $this->iwidepay_model->handle_refund_request($v,$iwidepay_refund,$refund_order['type']);
                $arr_query = $hand_res['arr_query'];
                $refund_id = $hand_res['refund_id'];
                if(isset($arr_query['respCode'])){
                    if($arr_query['respCode'] === '0000'){
                        $up_data['refund_status']=8;
                        //先更新iwidepay_orders的订单状态 ，再改变refund表的退款状态
                        $order_update = $this->update_iwidepay_order($v['order_no'],$v['module'],$up_data);
                        $this->db->where(array('id'=>$refund_id));
                        $refund_update = $this->db->update('iwidepay_refund',array('refund_status'=>1));
                    }elseif($arr_query['respCode'] == 'P000' || $arr_query['respCode'] == '9999'|| $arr_query['respCode'] == '9997'|| $arr_query['respCode'] == '0028'){
                        $up_data['refund_status']=10;
                        $order_update = $this->update_iwidepay_order($v['order_no'],$v['module'],$up_data);//异常
                        $this->db->where(array('id'=>$refund_id));
                        $update = $this->db->update('iwidepay_refund',array('refund_status'=>3));//异常
                    }else{
                        $up_data['refund_status']= ($arr_query['respCode'] == '0042' || $arr_query['respCode'] == '0066')?9:11;
                        $order_update = $this->update_iwidepay_order($v['order_no'],$v['module'],$up_data);
                        $this->db->where(array('id'=>$refund_id));
                        $update = $this->db->update('iwidepay_refund',array('refund_status'=>2));//失败
                    }
                }
            }
        }
        $this->redis_lock('delete','_CMBC_REFUDN_HANDLE');
        echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 结束民生退款处理脚本...<br/>';
        MYLOG::w('结束处理民生退款处理脚本', 'iwidepay/refund_handle');
    }
}