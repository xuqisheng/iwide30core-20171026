<?php
/*
 * 分账
 * date 2017-05-16
 * author chenjunyu
 */

class Split extends MY_Controller {
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

    /**
     * [redis_lock redis上/解锁]
     * @param [type] [操作类型，set/delete]
     * @param [key] [键]
     * @param [value] [type为set时，value是值]
     * @return [boolean] [操作结果]
     */
    protected function redis_lock($type='set' ,$key='split_lock' ,$value='lock'){
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

    /**
     * [redis_del 清除锁]
     */
    public function redis_del(){
        $key = $this->input->get('key');
        $this->redis_lock('delete',$key);
    }

    //处理订单
    public function check(){
        $this->check_arrow();
        // 上锁
        $ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__METHOD__ . ' lock fail!', 'iwidepay_split');
            exit('FAILURE!');
        }
        MYLOG::w('开始分账的脚本', 'iwidepay_split');
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        $this->load->model('iwidepay/Iwidepay_split_model');
        //找出未分账订单T-1日期的订单新订单和以往所有待定账单
        $enddate = date('Y-m-d 00:00:00');
        $nosplitorders = $this->Iwidepay_split_model->get_no_split_order('',$enddate);
        $inter_ids = array();
        foreach ( $nosplitorders as $k=>$order){
            $inter_ids[] = $order['inter_id'];
        }
        //inter_id去重
        $inter_ids = array_unique($inter_ids);
        if(empty($inter_ids)){
            //释放锁
            $this->redis_lock('delete');
            exit('分账执行完毕');
        }
        //取全部分账规则 根据inter_id拿
        $splitrules = $this->Iwidepay_split_model->get_split_rule($inter_ids);

        //获取全部银行卡信息
        $bank_infos = $this->Iwidepay_split_model->get_bank_info($inter_ids);
        MYLOG::w('获取全部银行卡信息：'.json_encode($bank_infos),'iwidepay_split');

        //根据订单匹配适应规则
        $rulesplitorders = $this->order_match_rule($nosplitorders,$splitrules,$bank_infos);
        MYLOG::w('分账订单匹配规则结果：'.json_encode($rulesplitorders),'iwidepay_split');  

        //计算分账记录
        $splitresult = $this->count_split($rulesplitorders,$bank_infos);
        MYLOG::w('分账记录生成结果：'.json_encode($splitresult),'iwidepay_split');  

        //分账记录入库
        $res = $this->Iwidepay_split_model->save_split_record($splitresult);
        MYLOG::w('分账记录入库结果：'.json_encode($res),'iwidepay_split');
        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('结束分账的脚本', 'iwidepay_split');
        if($res){
            exit('分账执行完毕');
        }
        exit('分账执行失败');    
    }

    /**
     * 根据订单匹配适应规则
     */
    private function order_match_rule($nosplitorders,$splitrules){
        //规则匹配
        foreach ($nosplitorders as $k => $order) {
            if(!empty($order)){
                $rule1 = array();
                $rule2 = array();
                foreach ($splitrules as $ks => $rule) {
                    if($order['inter_id']==$rule['inter_id']&&$rule['hotel_id']==$order['hotel_id']&&$order['module']==$rule['module']){
                        $rule1 = $rule;
                    }
                    if($order['inter_id']==$rule['inter_id']&&$rule['hotel_id']==-1&&$order['module']==$rule['module']){
                        $rule2 = $rule;
                    } 
                    if(!empty($rule1)&&!empty($rule2)){
                        break;
                    }     
                }

                if(!empty($rule1)){
                    $nosplitorders[$k]['rule'] = $rule1;
                }

                if(empty($rule1)){
                    $nosplitorders[$k]['rule'] = $rule2;
                }
            }
        }
        return $nosplitorders;
    }

    /**
     * 计算分账记录
     */
    private function count_split($nosplitorders,$bank_infos){
        $iresult = array();
        foreach ($nosplitorders as $k => $order) {
            //不可退款或退款成功的商城订单跳过分账
            if($order['module']=='soma'&&$order['refund_status']!=2&&$order['refund_status']!=8){
                continue;
            }
            //不可退款的待定商城订单才可分账
            if($order['transfer_status']==1&&$order['module']!='soma'){
                continue;
            }
            $result = array();
            if(!empty($order['rule'])){
                //判断待定订单分账逻辑
                if($order['transfer_status']==1&&$order['module']=='soma'){//待定
                    $rules = $this->hanle_rules($order['rule'],'before');
                }elseif ($order['transfer_status']==5&&$order['module']=='soma') {//未分完
                    //查出订单剩余金额
                    $remain_amt = $this->Iwidepay_split_model->get_remain_amt($order);
                    //重置订单
                    if($order['trans_amt']>$remain_amt){
                        $order['trans_amt'] = $order['trans_amt']-$remain_amt;
                        $rules = $this->hanle_rules($order['rule'],'after');
                    }else{
                        MYLOG::w('未分完订单余额不足再分：'.$order['order_no'].'，订单金额：'.$order['trans_amt'].'，已分金额：'.$remain_amt);
                        continue;
                    }
                }else{
                    $rules = $this->hanle_rules($order['rule']);
                }
                MYLOG::w('规则处理handle_rules_'.$order['order_no'].'：'.json_encode($rules),'iwidepay_split');
                // $soma_hotel_amt = 0;
                $dist_exist = 1;
                foreach ($rules as $kr => $rule) {
                    $type = '';
                    //分销员、平台、平台支付成本
                    switch ($kr) {
                        case 'regular_jfk_cost':
                            $type = 'cost';
                            break;
                        case 'regular_jfk':
                            $type = 'jfk';
                            break;
                        case 'regular_group':
                            $type = 'group';
                            break;
                        case 'regular_hotel':
                            $type = 'hotel';
                            break;
                        default:
                            # code...
                            break;
                    }
                    if($rule['val']==-1){
                        $last_rule = array('k'=>$type,'v'=>$rule);
                        continue;
                    }
                    $trans_amt = 0;
                    if($rule['type']=='percent'){
                        //百分比
                        $trans_amt = round($order['trans_amt']*$rule['val']);
                    }elseif($rule['type']=='number'){
                        //数值
                        $trans_amt = max($order['trans_amt'],$rule['val']);
                    }
                    //商城订单门店分成先全部入集团账
                    // if($order['module']=='soma'&&$kr=='regular_hotel'){
                    //     $soma_hotel_amt = $trans_amt;
                    // }

                    if($type=='group'){
                        $bank_info = $bank_infos[$order['inter_id'].'_0_group'];
                    }elseif($type=='jfk'||$type=='cost'){
                        $bank_info = $bank_infos[Iwidepay_split_model::JFK_ITD.'_0_jfk'];
                    }elseif($type=='hotel'&&$order['hotel_id']!=9999999){
                        $bank_info = $bank_infos[$order['inter_id'].'_'.$order['hotel_id'].'_hotel'];
                    }
                    if($order['module']=='soma' && $type=='hotel'){
                        //查出全部已确定的核销门店
                        $bill_hotels = $this->Iwidepay_split_model->get_soma_bill_record($order['order_no']);
                        $soma_bank_infos = array();
                        if(empty($bill_hotels)){
                            //还没有核销记录跳过门店分成
                            continue;
                        }
                        foreach ($bill_hotels as $kb => $vb) {
                            $soma_bank_infos[$vb['bill_hotel']] = $bank_infos[$order['inter_id'].'_'.$vb['bill_hotel'].'_hotel'];
                        }
                        if($bill_hotels[0]['order_qty']==$bill_hotels[0]['bill_qty']){
                            //单店或者通票只有一张的情况金额不需拆分
                            $trans_amt_p = $trans_amt;
                        }else{
                            //门店分成金额平均分
                            $trans_amt_p = round($trans_amt/$order['bill_num']);
                            //如果是未分完状态，保证每张票分成按原规则计算的值不变
                            if($order['transfer_status']==5){
                                $trans_amt_p = empty($order['per_hotel_amt'])?$trans_amt_p:$order['per_hotel_amt'];
                            }
                            //查询当前订单分成情况
                            $split_record = $this->Iwidepay_split_model->get_split_record($order['inter_id'],$order['order_no'],'soma');
                            $last_amt = 0;
                            if(!empty($split_record)){
                                $hotel_count = 0;
                                foreach ($split_record as $ks => $vs) {
                                    if($vs['type']=='hotel'){
                                        $hotel_count++;
                                    }
                                }
                                //当前是最后一个门店分成或全部门店分成都在此计算
                                if(($order['bill_num']-$hotel_count)==1){
                                    $last_amt = $order['trans_amt'];
                                }elseif($order['bill_num']==count($soma_bank_infos)){
                                    $last_amt = $order['trans_amt']-(count($soma_bank_infos)-1)*$trans_amt_p;
                                }
                            }
                        }
                    }
                    if(/*$trans_amt>0&&*/$type!=''){
                        // if($type == 'hotel'|| ($order['transfer_status']==1&&$order['is_dist']==2)){
                            if($order['is_dist']==2&&$order['dist_amt']>0){
                                // if($type == 'hotel'&&$order['transfer_status']<4){
                                //     $trans_amt = $trans_amt-$order['dist_amt'];
                                // }
                                // $bank_info_dist = $bank_infos[$order['inter_id'].'_'.$order['hotel_id'].'_dist'];
                                //判断如果门店没有分销银行卡信息，入金房卡分成银行卡
                                // if(!$bank_info_dist){
                                    $bank_info_dist = $bank_infos['jinfangka_0_jfk'];
                                // } 
                                if($dist_exist==1){  
                                    $result[] = array(
                                        'inter_id' => $order['inter_id'],
                                        'hotel_id' => $order['hotel_id'],
                                        'type' => 'dist',
                                        'rule_id' => $order['rule']['rule_id'],
                                        'order_no' => $order['order_no'],
                                        'module' => $order['module'],
                                        'pay_type' => $order['rule']['pay_type'],
                                        'm_id' => $bank_info_dist['id'],
                                        'bank' => $bank_info_dist['bank'],
                                        'bank_card_no' => $bank_info_dist['bank_card_no'],
                                        'bank_user_name' => $bank_info_dist['bank_user_name'],
                                        'amount' => $order['dist_amt'],
                                        'create_time' => date('Y-m-d H:i:s'),
                                        'check_time' => date('Y-m-d H:i:s'),
                                        );
                                    $dist_exist = 0;
                                }
                            }
                        // }
                        if($order['module']=='soma' && $type=='hotel'){
                            if(!empty($soma_bank_infos)){
                                $nc = 1;
                                foreach ($soma_bank_infos as $kb => $vb) {
                                    $result[] = array(
                                        'inter_id' => $order['inter_id'],
                                        'hotel_id' => $kb,
                                        'type' => $type,
                                        'rule_id' => $order['rule']['rule_id'],
                                        'order_no' => $order['order_no'],
                                        'module' => $order['module'],
                                        'pay_type' => $order['rule']['pay_type'],
                                        'm_id' => $vb['id'],
                                        'bank' => $vb['bank'],
                                        'bank_card_no' => $vb['bank_card_no'],
                                        'bank_user_name' => $vb['bank_user_name'],
                                        'amount' => $nc==count($soma_bank_infos)?($last_amt>0?$last_amt:$trans_amt_p):$trans_amt_p,
                                        'create_time' => date('Y-m-d H:i:s'),
                                        'check_time' => date('Y-m-d H:i:s'),
                                        );
                                    $nc++;
                                }
                            }
                        }else{
                            $result[] = array(
                                'inter_id' => $order['inter_id'],
                                'hotel_id' => $order['hotel_id'],
                                'type' => $type,
                                'rule_id' => $order['rule']['rule_id'],
                                'order_no' => $order['order_no'],
                                'module' => $order['module'],
                                'pay_type' => $order['rule']['pay_type'],
                                'm_id' => $bank_info['id'],
                                'bank' => $bank_info['bank'],
                                'bank_card_no' => $bank_info['bank_card_no'],
                                'bank_user_name' => $bank_info['bank_user_name'],
                                'amount' => $trans_amt,
                                'create_time' => date('Y-m-d H:i:s'),
                                'check_time' => date('Y-m-d H:i:s'),
                                );
                        }
                    }
                }
                //如果门店分成空，则将剩下的金额作为门店分成
                // if(empty($rules['regular_hotel'])&&$order['transfer_status']==2){
                //规则为-1的部分视为获取剩下的全部金额
                if(isset($last_rule)&&$last_rule['v']['val']==-1){
                    $allprice = 0;
                    $type = $last_rule['k'];
                    foreach ($result as $ks => $vals) {
                        if($vals['inter_id']==$order['inter_id']&&$vals['hotel_id']==$order['hotel_id']&&$vals['order_no']==$order['order_no']){
                            $allprice += $vals['amount'];
                        }
                    }
                    $trans_amt = $order['trans_amt']-$allprice;
                    //商城订单门店分成先全部入集团账
                    // if($order['module']=='soma'&&$type=='hotel'){
                    //     $soma_hotel_amt = $trans_amt;
                    // }
                    if($type=='group'){
                        $bank_info = $bank_infos[$order['inter_id'].'_0_group'];
                    }elseif($type=='jfk'||$type=='cost'){
                        $bank_info = $bank_infos[Iwidepay_split_model::JFK_ITD.'_0_jfk'];
                    }elseif($type=='hotel'&&$order['hotel_id']!=9999999){
                        $bank_info = $bank_infos[$order['inter_id'].'_'.$order['hotel_id'].'_hotel'];
                    }
                    if($order['module']=='soma' && $type=='hotel'){
                        //查出全部已确定的核销门店
                        $bill_hotels = $this->Iwidepay_split_model->get_soma_bill_record($order['order_no']);
                        $soma_bank_infos = array();
                        if(!empty($bill_hotels)){
                            //有核销记录才生成门店分成
                            foreach ($bill_hotels as $kb => $vb) {
                                $soma_bank_infos[$vb['bill_hotel']] = $bank_infos[$order['inter_id'].'_'.$vb['bill_hotel'].'_hotel'];
                            }
                            if($bill_hotels[0]['order_qty']==$bill_hotels[0]['bill_qty']){
                                //单店或者通票只有一张的情况金额不需拆分
                                $trans_amt_p = $trans_amt;
                            }else{
                                //门店分成金额平均分
                                $trans_amt_p = round($trans_amt/$order['bill_num']);
                                //如果是未分完状态，保证每张票分成按原规则计算的值不变
                                if($order['transfer_status']==5){
                                    $trans_amt_p = $order['per_hotel_amt'];
                                }
                                //查询当前订单分成情况
                                $split_record = $this->Iwidepay_split_model->get_split_record($order['inter_id'],$order['order_no'],'soma');
                                $last_amt = 0;
                                if(!empty($split_record)){
                                    $hotel_count = 0;
                                    foreach ($split_record as $ks => $vs) {
                                        if($vs['type']=='hotel'){
                                            $hotel_count++;
                                        }
                                    }
                                    //当前是最后一个门店分成或全部门店分成都在此计算
                                    if(($order['bill_num']-$hotel_count)==1){
                                        $last_amt = $order['trans_amt']-$all_amt;
                                    }elseif($order['bill_num']==count($soma_bank_infos)){
                                        $last_amt = ($order['trans_amt']-$all_amt)-(count($soma_bank_infos)-1)*$trans_amt_p;
                                    }
                                }
                            }
                        }
                    }
                    if($order['module']=='soma' && $type=='hotel'){
                        if(!empty($soma_bank_infos)){
                            $nc = 1;
                            foreach ($soma_bank_infos as $kb => $vb) {
                                $result[] = array(
                                    'inter_id' => $order['inter_id'],
                                    'hotel_id' => $kb,
                                    'type' => $type,
                                    'rule_id' => $order['rule']['rule_id'],
                                    'order_no' => $order['order_no'],
                                    'module' => $order['module'],
                                    'pay_type' => $order['rule']['pay_type'],
                                    'm_id' => $vb['id'],
                                    'bank' => $vb['bank'],
                                    'bank_card_no' => $vb['bank_card_no'],
                                    'bank_user_name' => $vb['bank_user_name'],
                                    'amount' => $nc==count($soma_bank_infos)?($last_amt>0?$last_amt:$trans_amt_p):$trans_amt_p,
                                    'create_time' => date('Y-m-d H:i:s'),
                                    'check_time' => date('Y-m-d H:i:s'),
                                    );
                                $nc++;
                            }
                        }
                    }else{
                        $result[] = array(
                                'inter_id' => $order['inter_id'],
                                'hotel_id' => $order['hotel_id'],
                                'type' => $type,
                                'rule_id' => $order['rule']['rule_id'],
                                'order_no' => $order['order_no'],
                                'module' => $order['module'],
                                'pay_type' => $order['rule']['pay_type'],
                                'm_id' => $bank_info['id'],
                                'bank' => $bank_info['bank'],
                                'bank_card_no' => $bank_info['bank_card_no'],
                                'bank_user_name' => $bank_info['bank_user_name'],
                                'amount' => $trans_amt,
                                'create_time' => date('Y-m-d H:i:s'),
                                'check_time' => date('Y-m-d H:i:s'),
                                );
                    }
                }
                //商城订单门店分成先全部入集团账
                // if($order['module']=='soma'&&$soma_hotel_amt>0){
                //     foreach($result as $rk=>$rv){
                //         if($rv['type']=='group'&&$rv['module']=='soma'){
                //             $result[$rk]['amount'] += $soma_hotel_amt;
                //         }
                //         if($rv['type']=='hotel'&&$rv['module']=='soma'){
                //             unset($result[$rk]);
                //         }    
                //     }
                // }
                //检验分成总金额是否等于订单总金额
                if($order['module']!='soma'){
                    $split_amt = 0;
                    foreach($result as $kt=>$vt){
                        $split_amt += $vt['amount'];
                    }
                    if($order['trans_amt']!=$split_amt){
                        echo '分成总金额：'.$split_amt.'，与订单-'.$order['order_no'].'总金额：'.$order['trans_amt'].'不一致';
                        MYLOG::w('分成总金额：'.$split_amt.'，与订单-'.$order['order_no'].'总金额：'.$order['trans_amt'].'不一致','iwidepay_split');
                        continue;
                    }
                }
            }
            $iresult = array_merge($iresult,$result);
        }
        return $iresult;
    }

    /**
     * 分账规则处理
     */
    private function hanle_rules($rules,$wait=null){
        $re_rules = array();
        $keys = array('regular_group','regular_hotel','regular_jfk','regular_jfk_cost',);
        if($wait=='before'){
            $keys = array('regular_group','regular_hotel','regular_jfk','regular_jfk_cost',);
        }
        if($wait=='after'){
            $keys = array('regular_hotel',);
            $re_rules['regular_hotel'] = $this->doloop('100%');
            return $re_rules;
        }
        foreach ($keys as $v) {
            if(!empty($rules[$v])){
                $re_rule = $this->doloop($rules[$v]);
                $re_rules[$v] = $re_rule;
            }
        }
        return $re_rules;
    }

    private function doloop($field){
        $re_field = array();
        if(!empty($field)){
            if(is_numeric($field)){
                $re_field['type'] = 'number';
                $re_field['val'] = $field;
            }else{
                $re_field['type'] = 'percent';
                $re_field['val'] = rtrim($field,'%')/100;
            }
        }
        return $re_field;
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