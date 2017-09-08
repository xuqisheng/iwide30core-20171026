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
        $this->db->update('iwidepay_sum_record',$update);
        return $this->db->affected_rows();//返回受影响行数
    }

    //查询iwidepay_identify表中数据
    public function get_iwidepay_identify_info($where = array(),$select = '*',$muti = true){
        $this->db->select($select);
        if(!empty($where)){
            $this->db->where($where);
        }
        $res = $this->db->get('iwidepay_identify');
        if($muti){
            return $res->result_array();
        }else{
            return $res->row_array();
        }
    }

    //update identify表的数据
    public function update_identify_data($where = array() , $update = array()){
        if(empty($where)){
            return false;
        }
        $this->db->where($where);
        $this->db->update('iwidepay_identify',$update);
        return $this->db->affected_rows();//返回受影响行数
    }

    //获取当天汇总待发放的数据
    public function get_deliver_data(){
        $date = date('Ymd',strtotime('-1 days'));
        $sql = "SELECT id,m_id,amount,bank_card_no,bank_user_name,handle_date,bank_city,bank_code,is_company,clearBankNo,accBankNo FROM " . self::TAB_IWIDEPAY_SUM . " WHERE handle_date = '{$date}' and status = 0 ";
        $res = $this->db_read()->query($sql)->result_array();
        return $res;
    }
    //给后台做账号验证
    //param: array $data:账号信息数组
    //return:array eg:成功返回：array('errmsg'=>'ok','partner_trade_no'=>'645121561'); partner_trade_no成功必传，失败不一定传
    public function check_account($data = array()){
        if(!isset($data['isCompay'])){
            return false;
        }
        if(!$this->check_bank_redis($data)){
            $return['errmsg'] = 'fail';
            $return ['remark'] = '今天已经转过一次了（redis中有数据）';
            return $return;
        }
        $partner_trade_no = 'cabp'.time();
        if($data['isCompay'] == 2){//对私
            $arr = array(
                'version' => 'V2.0',
                'orderDate' => date('Ymd'),
                'orderNo' => $partner_trade_no,
                'requestNo' => md5($partner_trade_no),
                'transAmt' => isset($data['amount'])?$data['amount']:1,//单位：分
                'productId'=> '0201',//产品类型
                'transId' => '07',//余额代付
                'isCompay' => 0,//对私
                'customerName'=>isset($data['bank_user_name'])?$data['bank_user_name']:'',//账号名：如：广州金房卡有限公司
                'acctNo' => isset($data['bank_card_no'])?$data['bank_card_no']:'',//银行账号：如：88888888888888888888
                'note'  => '金房卡check账户',
            );
        }elseif($data['isCompay'] == 1){//对公
            $arr = array(
                'version' => 'V2.0',
                'orderDate' => date('Ymd'),
                'orderNo' => $partner_trade_no,
                'requestNo' => md5($partner_trade_no),
                'transAmt' => isset($data['amount'])?$data['amount']:1,//单位：分
                'productId'=> '0211',//产品类型
                'transId' => '07',//余额代付
                'isCompay' => 1,//对私0 对公1
                'customerName'=>isset($data['bank_user_name'])?$data['bank_user_name']:'',//账号名：如：广州金房卡有限公司
                'accBankNo' => isset($data['accBankNo'])?$data['accBankNo']:'',//开户行号
                 'accBankName' => isset($data['accBankName'])?$data['accBankName']:'',//银行名字：如中国银行
                'acctNo' => isset($data['bank_card_no'])?$data['bank_card_no']:'',//银行账号：如：88888888888888888888
                'note'  => '金房卡check账户',
                'settBankNo'=>isset($data['clearBankNo'])?$data['clearBankNo']:'',//清算行号
                'busiType'=>'00506',
            );
        }
        if($arr['transAmt'] > 50){
            $return['errmsg'] = 'fail';
            $return ['remark'] = '超过5毛钱了';
            return $return;
        }
        //先插表 成功了再请求 否则报失败
        $identifier = md5($data['bank_card_no'].$data['handle_date']);
        $record = array();
        $record['bank_card_no'] = $data['bank_card_no'];
        $record['handle_date'] = $data['handle_date'];
        $record['amount'] = $data['amount'];
        $record['partner_trade_no'] = $partner_trade_no;
        $record['type'] = 2;//检测账户转账
        $record['status'] = 0;//0 初始状态（未回调） 1 成功  2失败  3异常
        $record['iscompay'] = $data['isCompay'];//对公对私
        $record['identify'] = $identifier;
        $record['add_time'] = date('Y-m-d H:i:s');
        $ins_res = $this->db->insert('iwidepay_identify',$record);
        $insert_id = $this->db->insert_id ();
        if(!$ins_res || empty($insert_id)||!is_numeric($insert_id)){
            MYLOG::w('检测账户转账插入重复数据:'.json_encode($arr), 'iwidepay/check_send');
            $return['errmsg'] = 'duplicate';
            return $return;
        }
        $this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
        $this->load->helper ( 'common' );
        $chart = IwidePayConfig::TRANSFER_PAY_SECRET;//改配置文件
        $arr['sign'] = md5($chart.$arr['orderNo'].$arr['orderDate'].$arr['transAmt'].$arr['transId'].$arr['customerName'].$arr['acctNo'].$chart);
        $return_data = doCurlPostRequest ( $this->get_refund_url(), http_build_query($arr),array(),30);
        MYLOG::w('转账返回数据：'.$return_data,'iwidepay/check_send');
        $return_data = $this->handle_encrypt($return_data,$chart,false);//这里记日志了
        if(!$return_data){       
            $return['errmsg'] = 'exception';
            $return ['remark'] = '签名回来无数据返回，异常' ;
            $return['partner_trade_no'] = $partner_trade_no;
            return $return;
        }
        $result = parseQString($return_data,true);//专属组
        $return = array('errmsg'=>'','data'=>'');
        if($result['respCode'] === '0000'){//成功
            $return['errmsg'] = 'ok';
            $return['partner_trade_no'] = $partner_trade_no;
            $return ['remark'] = $result['respDesc'];
            return $return;
        }elseif($result['respCode'] == 'P000' ||$result['respCode']=='9999'||$result['respCode']=='9997'||$result['respCode']=='0028'){//中间状态
            $return['errmsg'] = 'exception';
            $return['partner_trade_no'] = $partner_trade_no;
            $return ['remark'] = $result['respDesc'];
            return $return;
        }else{//明确失败的状态
            $return['errmsg'] = 'fail';
            $return ['remark'] = $result['respDesc'];
            $return['partner_trade_no'] = $partner_trade_no;
            return $return;
        }
         
    }

    //转账 查询单条
    public function single_send($id){
        //$date = date('Ymd');
        $ok = $this->redis_lock('setnx','_MP_TRANSFER_SEND_LOCK');
        if(!$ok){
            MYLOG::w('后台转账发起锁住redis，id：' . $id,'iwidepay/send');
        }   
        MYLOG::w('后台转账发起，id：' . $id,'iwidepay/send');
        $this->db->where(array('id'=>$id,'status'=>0));
        $res = $this->db->get('iwidepay_sum_record')->row_array();
        if(empty($res) || empty($res['amount'])){
            $this->redis_lock('delete','_MP_TRANSFER_SEND_LOCK');
            return false;
        }
        $res = $this->handle_transfer($res);
        $this->redis_lock('delete','_MP_TRANSFER_SEND_LOCK');
        return $res;
    }

    //转账账号按日期存进redis 
    public function check_bank_redis($data){
        $handle_data = $bank_arr = array();
        if($this->redis_lock('get','_TRANSFER_BANK_NO')){
            $handle_data = json_decode($this->redis_lock('get','_TRANSFER_BANK_NO'),true);
            if($handle_data['date'] == date('Ymd')){
                if(in_array($data['bank_card_no'],$handle_data['bank_arr'])){//当天已经存在了
                    return false;
                }
                $bank_arr = $handle_data['bank_arr'];
                $bank_arr[] = $data['bank_card_no'];//第一次，新增进去
                $handle_data['bank_arr'] = $bank_arr;
                $this->redis_lock('set','_TRANSFER_BANK_NO',json_encode($handle_data));
            }else{
                $bank_arr[] = $data['bank_card_no'];//第一次，新增进去
                $handle_data['bank_arr'] = $bank_arr;
                $handle_data['date']   = date('Ymd');
                $this->redis_lock('set','_TRANSFER_BANK_NO',json_encode($handle_data));
            }
        }else{
            $bank_arr[] = $data['bank_card_no'];//第一次，新增进去
            $handle_data['bank_arr'] = $bank_arr;
            $handle_data['date']   = date('Ymd');
            $this->redis_lock('set','_TRANSFER_BANK_NO',json_encode($handle_data));
        }
        return true;
    }

    //处理转账 对公对私
    public function handle_transfer($data){
        if($this->redis_lock('get')){
            MYLOG::w('后台转账发起：get锁住了'.$data['bank_card_no'],'iwidepay/send');
            return false;
        }
        if(!$this->check_bank_redis($data)){
            MYLOG::w('后台转账发起：redis不通过'.$data['bank_card_no'],'iwidepay/send');
            return false;
        }
        MYLOG::w('后台转账发起，到达handle_transfer：卡号：' .$data['bank_card_no'],'iwidepay/send');
        $res = $this->transfer_pay($data);
        if($res['errmsg'] == 'ok'){//成功的 更新transfer表状态
            $up_param['send_status'] = 1;//转账成功
            $up_param['partner_trade_no'] = $res['partner_trade_no'];
            $up_param['send_time'] = date('Y-m-d H:i:s');
            $this->db->where(array('record_id'=>$data['id'],'status'=>2));
            $update_st = $this->db->update('iwidepay_transfer',$up_param);
            if(!$this->db->affected_rows()){
                MYLOG::w('转账后续update失败:transfer|' . json_encode($data),'iwidepay/send');
                $this->redis_lock('set');
                return false;
            }
            $this->db->where(array('bank_card_no'=>$data['bank_card_no'],'handle_date'=>date('Ymd')));
            $this->db->update('iwidepay_settlement',array('status'=>1));
            if(!$this->db->affected_rows()){
                MYLOG::w('转账后续update失败：settlement|' . json_encode($data),'iwidepay/send');
                $this->redis_lock('set');
                return false;
            }
        }elseif($res['errmsg'] == 'duplicate'){//重复插入 报错

        }elseif($res['errmsg'] == 'exception'){//异常
            MYLOG::w('转账异常' . json_encode($data),'iwidepay/send');
            $this->db->where(array('record_id'=>$data['id'],'status'=>2));
            $up_param['send_status'] = 3;//转账异常
            $up_param['partner_trade_no'] = isset($res['partner_trade_no'])?$res['partner_trade_no']:'';
            $update_st = $this->db->update('iwidepay_transfer',$up_param);
            if(!$this->db->affected_rows()){
                MYLOG::w('转账后续update失败：transfer|' . json_encode($data),'iwidepay/send');
                $this->redis_lock('set');
                return false;
            }
            $this->db->where(array('bank_card_no'=>$data['bank_card_no'],'handle_date'=>date('Ymd')));
            $this->db->update('iwidepay_settlement',array('status'=>3));
            if(!$this->db->affected_rows()){
                MYLOG::w('转账后续update失败：settlement|' . json_encode($data),'iwidepay/send');
                $this->redis_lock('set');
                return false;
            }
        }else{//失败的 记录日志
            MYLOG::w('转账失败' . json_encode($data),'iwidepay/send');
            /*$this->db->where(array('record_id'=>$data['id'],'status'=>2));
            $up_param['send_status'] = 2;//转账失败
            $up_param['partner_trade_no'] = isset($res['partner_trade_no'])?$res['partner_trade_no']:'';
            $update_st = $this->db->update('iwidepay_transfer',$up_param);
            if(!$update_st){
                MYLOG::w('转账后续update失败' . json_encode($data),'iwidepay/send');
                $this->redis_lock('set');
                return false;
            }*/
            $this->db->where(array('bank_card_no'=>$data['bank_card_no'],'handle_date'=>date('Ymd')));
            $this->db->update('iwidepay_settlement',array('status'=>2));
            if(!$this->db->affected_rows()){
                MYLOG::w('转账后续update失败:settlement|' . json_encode($data),'iwidepay/send');
                $this->redis_lock('set');
                return false;
            }
        }
        return true;
    }

    //转账方法
    public function transfer_pay($data){
        $return = array('errmsg'=>'','data'=>'');
        //这里先将sum_record中的状态改为 转账中 99
        if(!$this->update_data(array('id'=>$data['id']),array('status'=>99))){
            //没返回
            MYLOG::w('错误，update转账中时返回受影响行数为0:'.json_encode($data), 'iwidepay/send');
            $return['errmsg'] = 'exception';
            return $return;
        }
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
                'busiType'=>'99906',
            );
        }else{
            MYLOG::w('错误，没指定对公或者对私:'.json_encode($data), 'iwidepay/send');
            $return['errmsg'] = 'fail';
            return $return;
        }
        //先插表 成功了再请求 否则报失败
        $identifier = md5($data['bank_card_no'].$data['handle_date']);
        $record = array();
        $record['bank_card_no'] = $data['bank_card_no'];
        $record['handle_date'] = $data['handle_date'];
        $record['amount'] = $data['amount'];
        $record['partner_trade_no'] = $partner_trade_no;
        $record['type'] = 1;//日常转账
        $record['status'] = 0;//0 初始状态（未回调） 1 成功  2失败  3异常
        $record['iscompay'] = $data['is_company'];//1对公  2对私
        $record['identify'] = $identifier;
        $record['add_time'] = date('Y-m-d H:i:s');
        $ins_res = $this->db->insert('iwidepay_identify',$record);
        $insert_id = $this->db->insert_id ();
        if(!$ins_res || empty($insert_id)||!is_numeric($insert_id)){
            MYLOG::w('转账插入重复数据:'.json_encode($arr), 'iwidepay/send');
            $return['errmsg'] = 'duplicate';
            return $return;
        }
        MYLOG::w('转账请求数据：'.json_encode($arr),'iwidepay/send');
        $this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
        //$result = $this->IwidePayApi->balancePayRequest($arr);//如果这里没走下去，有可能验签没通过
        //转成统一域名
        $this->load->helper ( 'common' );
        $chart = IwidePayConfig::TRANSFER_PAY_SECRET;//改配置文件
        $arr['sign'] = $this->handle_encrypt($arr,$chart);
        $return_data = doCurlPostRequest ( $this->get_refund_url(), http_build_query($arr),array(),30);
        MYLOG::w('转账返回数据：'.json_encode($return_data),'iwidepay/send');
        $return_data = $this->handle_encrypt($return_data,$chart,false);//这里记日志了
        if(!$return_data){       
            $return['errmsg'] = 'exception';
            $update['status'] = 3;//异常的
            $update ['remark'] = '签名回来无数据返回，异常' ;
            $update['partner_trade_no'] = $partner_trade_no;
            $this->update_data(array('id'=>$data['id']),$update);
            return $return;
        }
        //end 统一域名
        $result = parseQString($return_data,true);//专属组
        //MYLOG::w('转账返回数据：'.json_encode($result),'iwidepay/send');
        $where = array('id'=>$data['id']);
        if(!is_array($result)){
            $update['status'] = 3;//异常的
            $update ['remark'] = '无数据返回，异常' ;
            $update['partner_trade_no'] = $partner_trade_no;
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
            $update['partner_trade_no'] = $partner_trade_no;
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

    private function get_refund_url(){
        if(ENVIRONMENT === 'production'){
            return 'http://pull.jinfangka.com/index.php/iwidepay/cmbc/handle/pay';
        }else{
            return 'http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/handle/pay';
        }
    }

    //加个处理的方法
    private function handle_encrypt($data,$secret,$encode = true){
        if($encode){//加密
            return md5($secret.$data['orderNo'].$data['orderDate'].$data['transAmt'].$data['transId'].$data['customerName'].$data['acctNo'].$secret);
        }else{//解密
            if(empty($data)){
                MYLOG::w('处理http返回数据为空','iwidepay/send');
                return false;
            }
            $data = json_decode($data,true);
            if($data['errcode'] == 0){
                $sign = md5($secret. $data['data']['return_data'] .$secret);
                if(isset($data['data']['sign'])&& $data['data']['sign']== $sign){
                    return $data['data']['return_data'];
                }else{
                    MYLOG::w('处理http返回数据签名不对|' . json_encode($data),'iwidepay/send');
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    /**
     * [redis_lock redis上/解锁]
     * @param [type] [操作类型，set/delete]
     * @param [key] [键]
     * @param [value] [type为set时，value是值]
     * @return [boolean] [操作结果]
     */
    protected function redis_lock($type='set' ,$key='_TRANSFER_SENDING_LOCK' ,$value='lock'){
        $this->load->library ( 'Cache/Redis_proxy', array (
                'not_init' => FALSE,
                'module' => 'common',
                'refresh' => FALSE,
                'environment' => ENVIRONMENT
        ), 'redis_proxy' );
        $ok = false;
        if($type == 'setnx'){
            $ok = $this->redis_proxy->setNX ( $key, $value );
        }elseif($type == 'delete' ){
            $ok = $this->redis_proxy->del ( $key );
        }elseif($type == 'set'){
            $ok = $this->redis_proxy->set ( $key ,$value);
        }elseif($type == 'get'){
            $ok = $this->redis_proxy->get ( $key );
        }
        return $ok;
    }
}
