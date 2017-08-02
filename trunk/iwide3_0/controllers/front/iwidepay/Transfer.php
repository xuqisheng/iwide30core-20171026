<?php
/*
 * 转账
 */
class Transfer extends MY_Controller {
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
        $arrow_ip = array('118.178.228.168','118.178.133.170','114.55.234.45');//只允许服务器自动访问，不能手动
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

    /**
     * [redis_lock redis上/解锁]
     * @param [type] [操作类型，set/delete]
     * @param [key] [键]
     * @param [value] [type为set时，value是值]
     * @return [boolean] [操作结果]
     */
    protected function redis_lock($type='set' ,$key='transfer_lock' ,$value='lock'){
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
    public function check(){
        $this->check_arrow();
        //上锁
        $ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_transfer');
            die('FAILURE!');
        }
        MYLOG::w('开始分账的脚本', 'iwidepay/transfer');
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        //找出未分账订单
        $this->load->model ( 'iwidepay/iwidepay_transfer_model' );
        $orders = $this->iwidepay_transfer_model->get_unsplit_orders();//var_dump($orders);die;
        if(empty($orders)){
            MYLOG::w('无可分账的数据', 'iwidepay/transfer');
            $this->redis_lock('delete');
            return false;
        }
        //所有规则一起拿
        $all_rules = $this->iwidepay_transfer_model->get_all_rules();
        if(empty($all_rules)){
            MYLOG::w('无可分账的规则', 'iwidepay/transfer');
            $this->redis_lock('delete');
            return false;
        }
        $rules = array();
        foreach($all_rules as $ak=>$av){
            $rules[$av['inter_id']][] = $av;
        }
        $configedata = array('saler_inter_id'=>'jinfangka','iwide'=>'jinfangka','iwide_tips'=>'jinfangka');

        $bank_info_arr = $this->iwidepay_transfer_model->get_all_banks();
            foreach($bank_info_arr as $bk=>$bv){
                $bank_arr[$bv['inter_id'].'_'.$bv['hotel_id'] .'_' . $bv['type']] = $bv;
            }
        unset($bank_info_arr);
        //取分账规则 根据inter_id拿
        foreach($orders as $k=>$v){
            //查询对应的inter_id规则
            if(empty($rules[$v['inter_id']])){
                 MYLOG::w('inter_id:'.$v['inter_id'].'-无可分账的规则', 'iwidepay/transfer');
                 continue;
            }
            if($v['module']=='soma' && $v['refund_status'] != 2 && $v['refund_status'] != 8){
                continue;
            }
            if($v['module']!='soma' && $v['transfer_status'] == 1){
                continue;
            }
            $rules_data = $this->handle_rules($rules[$v['inter_id']],$v['inter_id'],$v['module'],$v['hotel_id']);
            $for_last = '';//是否剩下
            //拿出cost的费用 只有 1待定 2待分 才要算这步，5未分完的不用了
            /*if($v['transfer_status'] == 1 || $v['transfer_status'] == 2){
                if($v['transfer_status'] == 1){//商城通票
                    $for_last = 'regular_hotel';//剩下的都给酒店
                }
            }*/
            
            $money_arr = array();
            if($v['module'] == 'soma' && $v['transfer_status'] ==5){//再次只分通票
                $regular = array();
            }else{
                $regular = array('regular_jfk_cost','regular_group','regular_hotel','regular_jfk');
                if($v['is_dist'] && $v['dist_amt'] > 0){//是分销的单 做处理
                    $money_arr['regular_dist'] = $v['dist_amt'];//分销员的
                }
            }
            foreach($rules_data as $_key=>$_value){
                if(in_array($_key,$regular)){
                    if(empty($_value)){
                        //为空的不处理
                    }elseif($_value == '-1'){//剩下的全部给 
                        $for_last = $_key;
                        $money_arr[$_key] = 'forlast';//先给0 后面补
                    }elseif(is_numeric($_value)){
                        $money_arr[$_key] = $_value;
                    }else{
                        $persent = str_replace('%', '', $_value);
                        $money_arr[$_key] = round($v['trans_amt'] * $persent / 100);
                    }
                }
            }
            //商城判断 多次分
            $soma_flag = 0;//商城的单  是否已经分完
            $per_soma_bill = $v['per_hotel_amt'];//每张通票应分金额
            $single_inter = 0;//是否是单体 
            $soma_bill_arr = array();
            $ohter_amt = $hotel_amt = 0;//酒店分的 和 非酒店分的 soma用
            if($v['module'] == 'soma' ){
                $soma_bill = $this->iwidepay_transfer_model->get_soma_bill($v['inter_id'],$v['order_no']);
                if(!empty($soma_bill)){//不为空
                    foreach($soma_bill as $smk=>$smv){
                        if($smv['handle_status'] == 2){//已经完成
                            continue;
                        }
                        if($smv['bill_qty'] > 1){
                            $single_inter = 1;
                        }
                        if($single_inter!=1 && $smv['order_qty'] == count($soma_bill) || $single_inter==1 && count($soma_bill)==1){//用完 结束分
                            $soma_flag = 1;
                        }
                        $soma_bill_arr[] = $smv['bill_hotel'];
                    }
                }else{// 1 5 会有
                    if($v['transfer_status'] == 5){//没有已核销的通票
                        continue;
                    }
                }
                if(empty($soma_bill_arr) && $v['transfer_status'] == 5){
                    continue;
                }
                if($v['transfer_status'] == 1){//首次
                    if($for_last == 'regular_hotel'){//不是剩下全部给，可以由比例算出实际的
                        $soma_hotel_bill = -1;
                    }else{
                        $soma_hotel_bill = $money_arr['regular_hotel'];
                        unset($money_arr['regular_hotel']);
                    }
                }elseif($v['transfer_status'] == 5 && empty($per_soma_bill)){//重新计算除了酒店以外的
                    //$trans_sum_amt = $this->iwidepay_transfer_model->gather_transfer_amt($v['inter_id'],$v['order_no'],$v['module']);
                    $handle_order_amt = $this->iwidepay_transfer_model->get_handle_transfer_amt($v['order_no']);
                    $ohter_amt = isset($handle_order_amt['o_amt'])?$handle_order_amt['o_amt']:0;//除了酒店之外的
                    $hotel_amt = isset($handle_order_amt['hotel_amt'])?$handle_order_amt['hotel_amt']:0;//酒店已经分的
                    $soma_hotel_bill = $v['trans_amt'] - $ohter_amt;
                }
            }
            
            $inser_data = array();
            $transfer = $bank_info = array();
            $transfer['inter_id'] = $v['inter_id'];
            $transfer['hotel_id'] = $v['hotel_id'];//后面核销的要用核销酒店的ID
            $transfer['order_no'] = $v['order_no'];
            $transfer['pay_id'] = $v['pay_id'];//民生订单号
            $transfer['module'] = $v['module'];
            $transfer['pay_type'] = $v['pay_type'];
            $transfer['rule_id'] = $rules_data['rule_id'];
            $transfer['status'] = 1;//初始状态 待转
            $transfer['add_time'] = date('Y-m-d H:i:s');
            $money = 0;
            $last_arr = array();//存剩下的全部给的数据
            foreach($money_arr as $mk=>$mv){
                //处理银行卡信息
                if($mk == 'regular_group'){
                    $transfer['type'] = 'group';
                    $bank_info = isset($bank_arr[$v['inter_id'].'_0_group'])?$bank_arr[$v['inter_id'].'_0_group']:'';
                }elseif($mk == 'regular_hotel' && $v['hotel_id'] != 9999999){//soma通票是9999999 坑
                    $transfer['type'] = 'hotel';
                    $bank_info = isset($bank_arr[$v['inter_id'].'_'.$v['hotel_id'] .'_hotel'])?$bank_arr[$v['inter_id'].'_'.$v['hotel_id'] .'_hotel']:'';
                }elseif($mk == 'regular_dist'){
                    $transfer['type'] = 'dist';
                    $bank_info = isset($bank_arr[$configedata['iwide'].'_0_jfk'])?$bank_arr[$configedata['iwide'].'_0_jfk']:'';
                }elseif($mk == 'regular_jfk'){
                    $transfer['type'] = 'jfk';
                    $bank_info = isset($bank_arr[$configedata['iwide'].'_0_jfk'])?$bank_arr[$configedata['iwide'].'_0_jfk']:'';
                }elseif($mk == 'regular_jfk_cost'){
                    $transfer['type'] = 'cost';
                    $bank_info = isset($bank_arr[$configedata['iwide'].'_0_jfk'])?$bank_arr[$configedata['iwide'].'_0_jfk']:'';
                }
                if(empty($bank_info)){
                    MYLOG::w('inter_id:'.$v['inter_id'].'--order_id:'.$v['order_no'].'-无银行卡信息', 'iwidepay/transfer');
                }
                $transfer['m_id'] = !empty($bank_info['id'])?$bank_info['id']:0;
                $transfer['bank'] = !empty($bank_info['bank'])?$bank_info['bank']:'';
                $transfer['bank_card_no'] = !empty($bank_info['bank_card_no'])?$bank_info['bank_card_no']:'';
                $transfer['bank_user_name'] = !empty($bank_info['bank_user_name'])?$bank_info['bank_user_name']:'';
                $transfer['is_company'] = !empty($bank_info['is_company'])?$bank_info['is_company']:'';
                $transfer['amount'] = $mv;
                if($mv === 'forlast'){
                    $last_arr = $transfer;
                }else{
                    $inser_data[] = $transfer;
                    $money += $mv;//累加
                }
            }
            //单独处理剩下的
            if($for_last && !empty($last_arr)){
                $last_arr['amount'] = $v['trans_amt'] - $money;
                if($v['module']=='soma'&&$for_last=='regular_hotel'){//soma 并且剩下的是给酒店的 这个就要单独处理了
                    $soma_hotel_bill = $last_arr['amount'];//剩下的给酒店
                }else{
                    $inser_data[] = $last_arr;
                }
                unset($last_arr);
            }
            //这里处理通票
            if($v['module']=='soma' && !empty($soma_bill_arr)){
                if(empty($per_soma_bill)){
                    $per_soma_bill = $single_inter?$soma_hotel_bill:round($soma_hotel_bill / $v['bill_num']);
                }
                //改成每次去查
                $last_hotel_amt = 0;
                foreach($soma_bill_arr as $soma_k=>$soma_v){
                    if($soma_k == count($soma_bill_arr) - 1 && !$single_inter && $soma_flag){//最后一条
                        $cut_amt = $ohter_amt==0?($v['trans_amt']-$soma_hotel_bill):$ohter_amt;
                        $last_hotel_amt = $per_soma_bill + ($v['trans_amt']- $cut_amt-$per_soma_bill * $v['bill_num']);
                    }
                    $inser_data[] = $this->handle_soma_bill($v,$rules_data['rule_id'],$soma_v,($last_hotel_amt>0?$last_hotel_amt:$per_soma_bill),$bank_arr);
                }
            }//var_dump($inser_data);die;
            //汇总，分账金额和订单金额不一致
            $sum_amount = 0;
            if($v['module'] != 'soma'){
                foreach($inser_data as $ins_key =>$ins_val){
                $sum_amount += $ins_val['amount'];
            }
                if($sum_amount != $v['trans_amt']){
                    MYLOG::w('inter_id:'.$v['inter_id'].'--order_no:'.$v['order_no'].'-分账金额与订单金额总和不一致', 'iwidepay/transfer');
                    continue;
                }
            }
            //insert
            $res = $this->db->insert_batch('iwidepay_transfer',$inser_data);
            if($res){//插入成功
               // $data[$v['order_no']][] = $transfer;
            }else{
                MYLOG::w('inter_id:'.$v['inter_id'].'--order_no:'.$v['order_no'].'-入库失败，该条order_no订单停止', 'iwidepay/transfer');
                $this->redis_lock('delete');
                return false;
            }

            $is_success = $this->split_to_transfer($v['inter_id'],$v['order_no'],$v['module']);
            if(!$is_success){
                //报警
                //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
                MYLOG::w('err:'.__FUNCTION__ . ' 两个表匹配不一致,订单号：' . $v['order_no'], 'iwidepay_transfer');
            }
            if($v['module'] == 'soma'){
                if($soma_flag){
                    $up_status = 0;
                    $this->db->where(array('inter_id'=>$v['inter_id'],'order_no'=>$v['order_no']));
                    if($is_success){//匹配成功
                        $up_status = $this->db->update('iwidepay_order',array('transfer_status'=>3,'regular_jfk_cost'=>$rules_data['regular_jfk_cost'],'per_hotel_amt'=>$per_soma_bill));
                    }else{
                        $up_status = $this->db->update('iwidepay_order',array('transfer_status'=>4));
                    }
                }else{
                    $this->db->where(array('inter_id'=>$v['inter_id'],'order_no'=>$v['order_no']));
                    if($is_success){
                        $up_status = $this->db->update('iwidepay_order',array('transfer_status'=>5,'regular_jfk_cost'=>$rules_data['regular_jfk_cost'],'per_hotel_amt'=>$per_soma_bill));
                    }else{
                        $up_status = $this->db->update('iwidepay_order',array('transfer_status'=>4));
                    }
                }
                if(!$up_status){//更新不成功
                    MYLOG::w('inter_id:'.$v['inter_id'].'--order_no:'.$v['order_no'].'-update状态更新出错，该条order_no订单停止', 'iwidepay/transfer');
                    return false;
                }
                if($is_success && $up_status && !empty($soma_bill)){
                    foreach($soma_bill as $sbk=>$sbv){
                        $this->db->where(array('id'=>$sbv['id'],'handle_status'=>1));
                        $this->db->update('iwidepay_bill_record',array('handle_status'=>2));
                    }
                }
            }else{
                $this->db->where(array('inter_id'=>$v['inter_id'],'order_no'=>$v['order_no']));
                if($is_success){
                    $up_status = $this->db->update('iwidepay_order',array('transfer_status'=>3,'regular_jfk_cost'=>$rules_data['regular_jfk_cost']));//3是已分
                }else{
                    $up_status = $this->db->update('iwidepay_order',array('transfer_status'=>4));
                }
                if(!$up_status){//更新不成功
                    MYLOG::w('inter_id:'.$v['inter_id'].'--order_no:'.$v['order_no'].'-update状态更新出错，该条order_no订单停止', 'iwidepay/transfer');
                    return false;
                }

            }
            
        }  
        echo 'done';
        //遍历结束，解锁
        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('结束分账的脚本', 'iwidepay/transfer');
    } 

    //处理通票的那种单
    protected function handle_soma_bill($orders,$rules_id,$soma_hotel,$hotel_bill,$bank){
         //先拿出已经分掉的金额
        //$trans_sum_amt = $this->iwidepay_transfer_model->gather_transfer_amt($orders['inter_id'],$orders['order_no'],$orders['module']);
        $transfer = array();
        $transfer['inter_id'] = $orders['inter_id'];
        $transfer['hotel_id'] = $soma_hotel;//已经核销的
        $transfer['order_no'] = $orders['order_no'];
        $transfer['pay_id'] = $orders['pay_id'];//民生订单号
        $transfer['module'] = $orders['module'];
        $transfer['pay_type'] = $orders['pay_type'];
        $transfer['rule_id'] = $rules_id;
        $transfer['status'] = 1;//初始状态 待转
        $transfer['add_time'] = date('Y-m-d H:i:s');
        $transfer['type'] = 'hotel';
        $bank_info = isset($bank[$orders['inter_id'].'_'.$soma_hotel .'_hotel'])?$bank[$orders['inter_id'].'_'.$soma_hotel .'_hotel']:'';
        $transfer['m_id'] = !empty($bank_info['id'])?$bank_info['id']:0;
        $transfer['bank'] = !empty($bank_info['bank'])?$bank_info['bank']:'';
        $transfer['bank_card_no'] = !empty($bank_info['bank_card_no'])?$bank_info['bank_card_no']:'';
        $transfer['bank_user_name'] = !empty($bank_info['bank_user_name'])?$bank_info['bank_user_name']:'';
        $transfer['is_company'] = !empty($bank_info['is_company'])?$bank_info['is_company']:'';
        $transfer['amount'] = $hotel_bill;
        return $transfer;
    }

    //规则处理
    protected function handle_rules($rules,$inter_id,$module,$hotel_id){
            $rules_data = array();
            $single = 0;//没有单独设置
            $rules_list = $sin_hotel = $def_hotel = array();
            foreach($rules as $ruk=>$ruv){
                if($ruv['module'] == $module){
                    if($ruv['hotel_id'] == $hotel_id){//集团或者酒店
                        $sin_hotel = $ruv;
                        $single = 1;//存在单独设的
                        break;
                    }elseif($ruv['hotel_id'] == -1){//默认设置
                        $def_hotel = $ruv;
                    }
                }
            }
            if($single){
                $rules_data = $sin_hotel;
            }else{
                $rules_data = $def_hotel;
            }
            return $rules_data;
    }

    //分账和转账对比
    protected function split_to_transfer($inter_id,$order_no,$module){
        //先查询两个表记录条数是不是一致
            $split_count = $this->iwidepay_transfer_model->get_split_count($inter_id,$order_no,$module);
            $transfer_count = $this->iwidepay_transfer_model->get_transfer_count($inter_id,$order_no,$module);
            if($split_count != $transfer_count){
                MYLOG::w('inter_id:'.$inter_id.'--order_no:'.$order_no.'-两个表记录条数不一致', 'iwidepay/transfer');
                return false;
            }
            //根据订单号和inter_id连表查询分账记录和转账记录 
            $record = $this->iwidepay_transfer_model->get_split_transfer_record($inter_id,$order_no,$module);
            if(empty($record)){
                MYLOG::w('inter_id:'.$inter_id.'--order_no:'.$order_no.'-分账转账无记录', 'iwidepay/transfer');
                return false;
            }
            $is_split = 1;//是否成功分账
            foreach($record as $key=>$values){
                if($values['type']==$values['s_type'] && $values['hotel_id'] == $values['s_hotel_id'] && $values['order_no'] == $values['s_order_no']){//确定为同一条记录
                    if($values['amount'] == $values['s_amount'] && $values['m_id'] == $values['s_m_id']){//相同的
                        //相同，更新状态，或者转账
                        $update = array('status'=>2,'check_time'=>date('Y-m-d H:i:s'));
                        $this->db->where(array('id'=>$values['id']));
                        $this->db->update('iwidepay_transfer',$update);
                    }else{//不相同
                        MYLOG::w('inter_id:'.$inter_id.'--order_no:'.$order_no.'-匹配异常', 'iwidepay/transfer');
                        $update = array('status'=>3,'check_time'=>date('Y-m-d H:i:s'));//匹配异常
                        $this->db->where(array('id'=>$values['id']));
                        $this->db->update('iwidepay_transfer',$update);
                        $is_split = 0;//一次失败，就是失败
                    }
                }
            }
            return $is_split;
    }

    
}