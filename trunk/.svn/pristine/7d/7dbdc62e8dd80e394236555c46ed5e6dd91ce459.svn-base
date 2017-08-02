<?php
class Iwidepay_Deliver_Model extends MY_Model{
    const TAB_IWIDEPAY_ORDER = 'iwide_iwidepay_order';
    const TAB_IWIDEPAY_RULE = 'iwide_iwidepay_rule';
    const TAB_IWIDEPAY_BANK = 'iwide_iwidepay_merchant_info';
    const TAB_IWIDEPAY_TRANSFER = 'iwide_iwidepay_transfer';
    const TAB_IWIDEPAY_SPLIT = 'iwide_iwidepay_split';
    const TAB_IWIDEPAY_SUM = 'iwide_iwidepay_sum_record';
	function __construct() {
		parent::__construct ();
	}

    protected function db_read(){
        
        return $this->db;
        
    }
    
    protected function db_write(){
        
        return $this->db;
    }
    
    //update 数据
    public function update_data($where = array() , $update = array()){
        if(empty($where)){
            return false;
        }
        $this->db->where($where);
        return $this->db->update('iwidepay_sum_record',$update);
    }

    //获取当天汇总待发放的数据
    public function get_deliver_data(){
        $date = date('Ymd');
        $sql = "SELECT id,m_id,amount,bank_card_no,bank_user_name,handle_date,bank_city,bank_code,is_company,clearBankNo,accBankNo FROM " . self::TAB_IWIDEPAY_SUM . " WHERE handle_date = '{$date}' and status = 0 ";
        $res = $this->db_read()->query($sql)->result_array();
        return $res;
    }

    //转账 查询单条
    public function single_send($id){
        $date = date('Ymd');
        $this->db->where(array('id'=>$id,'status'=>0,'handle_date'=>$date));
        $res = $this->db->get('iwidepay_sum_record')->row_array();
        return $this->handle_transfer($res);
    }

    //处理转账 对公对私
    public function handle_transfer($data){
        $res = $this->transfer_pay($data);
        if($res['errmsg'] == 'ok'){//成功的 更新transfer表状态
            $up_param['send_status'] = 1;//转账成功
            $up_param['partner_trade_no'] = $res['partner_trade_no'];
            $up_param['send_time'] = date('Y-m-d H:i:s');
            $this->db->where(array('record_id'=>$data['id'],'status'=>2));
            $update_st = $this->db->update('iwidepay_transfer',$up_param);
            if(!$update_st){
                MYLOG::w('转账后续update失败' . json_encode($data),'iwidepay/send');
                die;
            }
        }elseif($res['errmsg'] == 'duplicate'){//重复插入 报错

        }elseif($res['errmsg'] == 'exception'){//异常
            MYLOG::w('转账异常' . json_encode($data),'iwidepay/send');
            $this->db->where(array('record_id'=>$data['id'],'status'=>2));
            $up_param['send_status'] = 3;//转账异常
            $up_param['partner_trade_no'] = isset($res['partner_trade_no'])?$res['partner_trade_no']:'';
            $update_st = $this->db->update('iwidepay_transfer',$up_param);
            if(!$update_st){
                MYLOG::w('转账后续update失败' . json_encode($data),'iwidepay/send');
                die;
            }
        }else{//失败的 记录日志
            MYLOG::w('转账失败' . json_encode($data),'iwidepay/send');
            $this->db->where(array('record_id'=>$data['id'],'status'=>2));
            $up_param['send_status'] = 2;//转账失败
            $up_param['partner_trade_no'] = isset($res['partner_trade_no'])?$res['partner_trade_no']:'';
            $update_st = $this->db->update('iwidepay_transfer',$up_param);
            if(!$update_st){
                MYLOG::w('转账后续update失败' . json_encode($data),'iwidepay/send');
                die;
            }
        }
        return true;
    }

    //转账方法
    public function transfer_pay($data){
        $return = array('errmsg'=>'','data'=>'');
        $tmp_data = $this->session->userdata('trade_time');
        $tmp_trade_no = substr ( time (), 3 ) . mt_rand ( 10000, 99999 );
        if(!empty($tmp_data) && $tmp_data == $tmp_trade_no){//相同则sleep 1秒
            sleep(1);
            $tmp_trade_no = substr ( time (), 3 ) . mt_rand ( 10000, 99999 );
        }
        //生成的数据放进会话中
        $this->session->set_userdata(array('trade_time'=>$tmp_trade_no));
        $partner_trade_no = date ( 'Ymd' ) . $tmp_trade_no ;
        if($data['is_company'] == 2){//对私
            $arr = array(
                'version' => 'V2.0',
                'orderDate' => date('Ymd'),
                'orderNo' => $partner_trade_no,
                'requestNo' => MD5($partner_trade_no),
                'transAmt' => $data['amount'],//单位：分
                'productId'=> '0201',//产品类型
                'transId' => '07',//余额代付
                'isCompay' => 0,//对私
                'customerName'=>$data['bank_user_name'],
                //'bankType' =>$data['bank_code'],//行别
                'addrName' => $data['bank_city'],//地区
                // 'accBankName' => '广州招商银行科技园支行',
                'acctNo' => $data['bank_card_no'],
                'note'  => '分成',
            );
        }elseif($data['is_company'] == 1){//对公
            $arr = array(
                'version' => 'V2.0',
                'orderDate' => date('Ymd'),
                'orderNo' => $partner_trade_no,
                'requestNo' => MD5($partner_trade_no),
                'transAmt' => $data['amount'],//单位：分
                'productId'=> '0211',//产品类型
                'transId' => '07',//余额代付
                'isCompay' => 1,//对公
                'customerName'=>$data['bank_user_name'],
                'accBankNo' => $data['accBankNo'],
                //'bankType' =>$data['bank_code'],//行别
                //'addrName' => $data['bank_city'],//地区
                // 'accBankName' => '广州招商银行科技园支行',
                'acctNo' => $data['bank_card_no'],
                'note'  => '分成',
                'settBankNo'=>$data['clearBankNo'],//清算行号
                'busiType'=>'00506',
            );
        }else{
            MYLOG::w('错误，没指定对公或者对私:'.json_encode($data), 'iwidepay/send');
            $return['errmsg'] = 'fail';
            return $return;
        }
        //先插表 成功了再请求 否则报失败
        $identifier = md5($data['bank_card_no'].$data['handle_date']);
        $this->db->insert('iwidepay_identify',array('identify'=>$identifier,'type'=>1));
        $insert_id = $this->db->insert_id ();
        if(empty($insert_id)){
            MYLOG::w('对私转账插入重复数据:'.json_encode($arr), 'iwidepay/send');
            $return['errmsg'] = 'duplicate';
            return $return;
        }
        MYLOG::w('转账请求数据：'.json_encode($arr),'iwidepay/send');
        $this->load->library('IwidePay/IwidePayApi',null,'IwidePayApi');
        $result = $this->IwidePayApi->balancePayRequest($arr);//如果这里没走下去，有可能验签没通过
        $result = parseQString($result,true);//专属组
        MYLOG::w('转账返回数据：'.json_encode($result),'iwidepay/send');
        $where = array('id'=>$data['id']);
        if(!is_array($result)){
            $update['status'] = 3;//异常的
            $update ['remark'] = '无数据返回，异常' ;
            $this->update_data($where,$update);
            $return['errmsg'] = 'exception';
            return $return;
        }
        $update = array();
        //记录多一个ip信息
        $arr['remote_ip'] = isset($_SERVER ["REMOTE_ADDR"])?$_SERVER ["REMOTE_ADDR"]:'';
        $update['send_content'] = json_encode($arr);
        $update['receive_content'] = json_encode($result);
        $update['send_time'] = date('Y-m-d H:i:s');
        if(!isset($result['respCode'])){
            $update['status'] = 3;//异常的
            $update ['remark'] = '无数据返回，异常' ;
            $this->update_data($where,$update);
            $return['errmsg'] = 'exception';
            return $return;
        }
        if($result['respCode'] === '0000'){//成功
            $update['status'] = 1;//成功
            $update ['remark'] = $result['respDesc'];
            $update['partner_trade_no'] = $partner_trade_no;
            $this->update_data($where,$update);
            $return['errmsg'] = 'ok';
            $return['partner_trade_no'] = $partner_trade_no;
            return $return;
        }elseif($result['respCode'] == 'P000' ||$result['respCode']=='9999'||$result['respCode']=='9997'||$result['respCode']=='0028'){//中间状态
            $update['status'] = 3;//异常的
            $update ['remark'] = '返回异常状态' . $result['respCode'] . '|说明：' .$result['respDesc'] ;
            $update['partner_trade_no'] = $partner_trade_no;
            $this->update_data($where,$update);
            $return['errmsg'] = 'exception';
             $return['partner_trade_no'] = $partner_trade_no;
            return $return;
        }else{//明确失败的状态
            MYLOG::w('请求转账失败' . json_encode($result),'iwidepay/send');
            $update['status'] = 2;//明确失败的
            $update ['remark'] = $result['respDesc'];
            $update['partner_trade_no'] = $partner_trade_no;
            $this->update_data($where,$update);
            $return['errmsg'] = 'fail';
           // $return['partner_trade_no'] = $partner_trade_no;
            return $return;
        }
    }
}
