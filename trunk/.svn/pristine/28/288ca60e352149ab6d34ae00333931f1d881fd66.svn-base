<?php
/*
 * 转账
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Fixbug extends MY_Controller {
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

   /* //汇总数据
    public function sum_info(){
        $this->load->model ( 'iwidepay/iwidepay_transfer_model' );
        $data = array();
        //先查下有咩有指定日期的数据
        if(empty($data)){
            //手动转账，先将前一天还没处理转账的数据update为10：放弃转账
            $end_date = date('Y-m-d 15:00:00');
            //先取出异常的订单号 然后拿记录的时候去掉那些订单号
            $not_in_sql = "SELECT order_no FROM iwide_iwidepay_transfer WHERE add_time <= '{$end_date}' and status != 2 group by order_no";
            $sql = "SELECT a.id,a.inter_id,a.hotel_id,a.bank,a.bank_user_name,a.bank_card_no,a.amount,a.m_id,b.bank_code,b.bank_city,b.clearBankNo,b.accBankNo,b.is_company FROM iwide_iwidepay_transfer a LEFT JOIN iwide_iwidepay_merchant_info b on a.m_id = b.id where  a.add_time <= '{$end_date}' and amount >0 and a.status = 2 and a.inter_id in('a502797520','a502441635') and a.send_status = 0 and a.order_no not in (".$not_in_sql.")";
            //echo $sql;die;
            $res = $this->db->query($sql)->result_array();
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
            $sql = "select id,amount,bank,bank_card_no,inter_id,hotel_id,type,handle_date from iwide_iwidepay_settlement where handle_date = '20170901' and status = 0 and inter_id in ('a502441635','a502797520')";
          $settle_res = $this->db->query($sql)->result_array();
            
            if(empty($settle_res)){
                echo '查询分账结算表无数据！';
                $this->redis_lock('delete','_CMBC_SUM_RECORD');
                die;
            }
            $settle_arr = array();
            foreach($settle_res as $sk=>$sv){
                $settle_arr[$sv['bank_card_no']] = $sv;
            }
            unset($settle_res);
            $merchant_info = $this->merchant_info();
            
            foreach($tmp_res as $rk=>$rv){
                if($rv['bank_card_no'] == '699790460'){
                    continue;
                }MYLOG::w('处理汇总脚本update transfer表出错' . json_encode($rv['ids']), 'iwidepay/transfer_auto');
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
                if(isset($settle_arr[$rv['bank_card_no']]) && $settle_arr[$rv['bank_card_no']]['sum_amount'] && $settle_arr[$rv['bank_card_no']]['sum_amount']==$rv['sum_trans_amt']){
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
                //批量更新
                if(!empty($ids)){
                    //$ids = explode(',',$ids);
                    $this->db->where_in('id',$ids);
                    $all_update = $this->db->update('iwidepay_transfer',array('record_id'=>$insert_id));
                    if(!$this->db->affected_rows()){
                        echo 'update error';
                        MYLOG::w('处理汇总脚本update transfer表出错', 'iwidepay/transfer_auto');
                        die;
                    }
                }
            }
        }
    }

    //结算汇总数据
    public function settlement_info()
    {
       
        $this->load->model ( 'iwidepay/iwidepay_transfer_model' );

        //先update 今天之前未转账的记录
        $end_date = date('Y-m-d 15:00:00');
        //先取出异常的订单号 然后拿记录的时候去掉那些订单号
        $sql = "select order_no from iwide_iwidepay_transfer where add_time <= '{$end_date}' and status != 2 group by order_no";
        $orders = $this->db->query($sql)->result_array();
        $sql = "select a.id,a.inter_id,a.hotel_id,a.bank,a.bank_user_name,a.type,
                a.bank_card_no,a.amount,a.add_time,a.m_id,b.bank_code,b.bank_city,b.clearBankNo,b.accBankNo,b.is_company
                from iwide_iwidepay_transfer a
                left join iwide_iwidepay_merchant_info b on a.m_id = b.id
                where  a.add_time <= '{$end_date}' and a.status = 2 and a.inter_id in('a502797520','a502441635') and a.send_status = 0";
        if(!empty($orders)){
            $oid = array_column($orders,'order_no');
            $sql .= " and a.order_no not in ('" . implode("','",$oid) . "' )";
        }

        unset($orders);
        //echo $sql;die;
        $res = $this->db->query($sql)->result_array();
        if(empty($res))
        {
            echo '无记录可导出';
            die;
        }
        $merchant_info = $this->merchant_info();

        $temp = array();
        //处理数据按照金房卡，集团，门店 生成新的数组
        foreach ($res as $k => $val)
        {   if($val['inter_id'] != 'a502797520' && $val['inter_id'] != 'a502441635'){
                continue;
            }
            //金房卡记录
            if (in_array($val['type'],array('cost','jfk','dist'),true))
            {
                $key = 'jinfangka';

                $temp[$key]['sum_trans_amt'] += $val['amount'];
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
                $temp[$key]['sum_trans_amt'] += $val['amount'];

                $val['sum_trans_amt'] = $temp[$key]['sum_trans_amt'];

                $temp[$key] = $val;
            }
            //门店
            else if($val['type'] == 'hotel')
            {
                $key = $val['inter_id'].'_'.$val['hotel_id'].'_hotel';

                $temp[$key]['sum_trans_amt'] += $val['amount'];
                $val['sum_trans_amt'] = $temp[$key]['sum_trans_amt'];

                $temp[$key] = $val;
            }
        }

        if (!empty($temp))
        {
            foreach($temp as $rk=>$rv)
            {   if($rv['inter_id'] != 'a502797520' && $rv['inter_id'] != 'a502441635'){
                    continue;
                }
                $tmp = array();
                $tmp['inter_id'] = $rv['inter_id'];
                $tmp['hotel_id'] = $rv['hotel_id'];
                $tmp['type'] = $rv['type'];
                $tmp['amount'] = $rv['sum_trans_amt'];
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

                $this->db->insert('iwidepay_settlement',$tmp);
                $insert_id = $this->db->insert_id();
                if(!$insert_id)
                {
                    echo 'insert error';
                    MYLOG::w('处理汇总脚本insert出错', 'iwidepay/transfer_auto_settlement');
                    die;
                }
            }
    }
}*/

   
}