<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );

class Iwidepayreturn extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
	}

	//支付回调
	public function hotel_return(){
		$body = file_get_contents ( 'php://input' );

		$this->load->library('MYLOG');
        MYLOG::w('回调数据：'.$body,'iwidepayreturn');
        if(empty($body)){
        	exit('SUCCESS');
        }

        // 验签，通过返回数组
        $result = $this->IwidePayApi->payCallBack($body);
        MYLOG::w('验签结果：'.json_encode($result),'iwidepayreturn');
        // 验签失败
        if(!$result){
            exit('SUCCESS');
        }

        $this->load->model('iwidepay/Iwidepay_model');
        //转数组
        $parse_arr = parseQString($body,true);
        //根据交易类型，走退款回调和转账结果回调逻辑,默认为支付回调
        if($parse_arr['transId']==='07'){
            $this->transfer_callback($parse_arr);//转账回调
            exit();
        }elseif ($parse_arr['transId']==='02') {
            exit();
        }
        if($parse_arr['respCode']!=='0000'){
            exit('SUCCESS');
        }

        //根据orderid查出订单信息
        $idata = $this->Iwidepay_model->get_iwidepay_order($parse_arr['orderNo']);

        MYLOG::w('订单：'.json_encode($idata),'iwidepayreturn');
        //处理存在主单号的模块
        if (count($idata) != count($idata, 1))
        {
            MYLOG::w('预约核销：'.json_encode($idata),'iwidepayreturn');
            $this->multi_order($idata,$parse_arr);
            exit;
        }

        if(!$idata){
            //检查是否soma订单，订单号需转义
            $this->load->somaDatabase($this->db_soma);
            $this->load->somaDatabaseRead($this->db_soma_read);
            $this->load->model('soma/Sales_order_model');
            $parse_arr['orderNo'] = $this->Sales_order_model->wx_out_trade_no_decode($parse_arr['orderNo']);
            $idata = $this->Iwidepay_model->get_iwidepay_order($parse_arr['orderNo']);
        }elseif($idata['module']=='soma'){
            $this->load->somaDatabase($this->db_soma);
            $this->load->somaDatabaseRead($this->db_soma_read);
        }
        if(!$idata){
            MYLOG::w('分账订单查询为空：'.$parse_arr['orderNo'].','.json_encode($idata),'iwidepayreturn');
        	exit('SUCCESS');
        }
        //重复回调判断
        if($idata['transfer_status']>0){
            exit('SUCCESS');
        }

        //判断总金额是否相符
        if($idata['trans_amt']!=$parse_arr['transAmt']){
            MYLOG::w($parse_arr['orderNo'].'|回调金额'.$parse_arr['transAmt'].'与订单金额'.$idata['trans_amt'].'不一致'.'iwidepayreturn');
            exit('SUCCESS');
        }
        //各模块回调逻辑
        switch ($idata['module']) {
            case 'hotel':
                $pay_log_type = 'ip_hotel';
                break;
            case 'soma':
                $pay_log_type = 'ip_soma';
                break;
            case 'vip':
                $pay_log_type = 'ip_vip';
                break;
            case 'okpay':
                $pay_log_type = 'ip_okpay';
                break;
            default:
                $pay_log_type = 'ip';
                break;
        }

        //写入支付日志
        $in_arr = array(
            'inter_id' => $idata['inter_id'],
            'openid' => $idata['openid'],
            'out_trade_no' => $parse_arr['orderNo'],
            'transaction_id' => $parse_arr['payId'],
            'pay_time' => time(),
            'rtn_content' => $body,
            'type' => $pay_log_type,
            );
        $this->db->insert ( 'pay_log', $in_arr );

        //各模块回调逻辑
        switch ($idata['module']) {
            case 'hotel':
                $this->hotel_handle($idata,$parse_arr);
                $order_status = 1;
                $transfer_status = 1;
                break;
            case 'soma':
                $this->soma_handle($idata,$parse_arr);
                $order_status = 12;
                $transfer_status = 1;
                break;
            case 'vip':
                $this->vip_handle($idata,$parse_arr);
                $order_status = 't';
                $transfer_status = 2;
                break;
            case 'okpay':
                $this->okpay_handle($idata,$parse_arr);
                $order_status = 3;
                $transfer_status = 2;
                break;
            case 'dc':
                $this->dc_handle($idata,$parse_arr);
                $order_status = 1;//支付状态
                $transfer_status = 1;
                break;
            default:
                $order_status = 1;
                $transfer_status = 1;
                break;
        }

		// 更新分账订单信息
		parse_str($body, $params);
		$split_order = array(
			'productid' => $params['productId'],
			'transid' => $params['transId'],
			'merno' => $params['merNo'],
			'order_status' => $order_status,
			'pay_time' => $params['payTime'],
			'pay_id' => $params['payId'],
			'transfer_status' => $transfer_status,
			'update_time' => date('Y-m-d H:i:s'),
			);
		MYLOG::w('分账订单更新数据：'.json_encode($split_order),'iwidepayreturn');
		$res = $this->Iwidepay_model->update_iwidepay_order($idata['order_no'],$idata['module'],$split_order);
		if(!$res){
			MYLOG::w('分账订单更新失败：'.json_encode($res),'iwidepayreturn');
			exit('SUCCESS');
		}
		// 成功响应
		exit('SUCCESS');
	}

    /**
     * transfer_callback
     * 转账回调处理
     * @param  [array] $parse_arr [回调信息主体]
     */
    private function transfer_callback($parse_arr){
        //先查询iwidepay_identify 表 查询转账来源
        MYLOG::w('分账转账回调数据：'.json_encode($parse_arr),'iwidepayreturn/send_callback');
        $this->load->model ( 'iwidepay/Iwidepay_deliver_model' );
        $array = array();
        $array['partner_trade_no']=$parse_arr ['orderNo'];
        $array['status'] = 0;//初始状态
        $order = $this->Iwidepay_deliver_model->get_iwidepay_identify_info($array,'*',false);
        if(empty($order)){
            return false;
        }
        if(isset($parse_arr['respCode'])){
            $update = array();
            $update['receive_content'] = json_encode($parse_arr);
            $update['receive_time'] = date('Y-m-d H:i:s');
            $update['paytime'] = isset($parse_arr['payTime'])?$parse_arr['payTime']:'';
            $update['payid'] = isset($parse_arr['payId'])?$parse_arr['payId']:'';
            if($parse_arr['respCode'] === '0000'){//只处理成功 貌似民生只有成功的回调
                //先update更新identify表
                $update['status'] = 1;//成功
                if(!$this->Iwidepay_deliver_model->update_identify_data(array('id'=>$order['id']),$update)){
                    return false;
                }
                if($order['type'] == 1){//查询sum_record表 查询异常
                    $this->load->model ( 'iwidepay/Iwidepay_sum_record_model' );
                    $this->load->model ( 'iwidepay/iwidepay_transfer_model' );
                    $sum_order = $this->Iwidepay_sum_record_model->get_one('*',array('partner_trade_no'=>$parse_arr['orderNo'],'status'=>3));
                    if(empty($sum_order)){
                        MYLOG::w('分账转账回调更新失败：sum_record表没有记录：'.json_encode($sum_order),'iwidepayreturn/send_callback');
                        return false;
                    }
                    //更新sum_record表 这里用事务保持一致性
                    $this->db->trans_begin ();
                    $upres = $this->Iwidepay_deliver_model->update_data(array('id'=>$sum_order['id'],'status'=>3),array('status'=>1,'remark'=>$sum_order['remark'].'|回调：成功'));
                    if(!$upres){
                        MYLOG::w('分账转账回调更新失败：sum_record表：'.json_encode($sum_order),'iwidepayreturn/send_callback');
                        $this->db->trans_rollback ();
                        return false;
                    }
                    //start更新settlement表
                    $this->db->where(array('bank_card_no'=>$sum_order['bank_card_no'],'handle_date'=>$sum_order['handle_date'],'status'=>3));
                    $settle_res = $this->db->update('iwidepay_settlement',array('status'=>1));
                    if(!$this->db->affected_rows()){
                        MYLOG::w('分账转账回调更新失败：iwidepay_settlement表：'.json_encode($sum_order),'iwidepayreturn/send_callback');
                        $this->db->trans_rollback ();
                        return false;
                    }
                    //end
                    $up_param['send_status'] = 1;//转账成功
                    $up_param['send_time'] = date('Y-m-d H:i:s');
                    $tran_where = array('record_id'=>$sum_order['id'],'status'=>2,'send_status'=>3);
                    //更新transfer表
                    $update_st = $this->iwidepay_transfer_model->update_data($tran_where,$up_param);
                    if(!$update_st){
                        $this->db->trans_rollback ();
                        return false;
                    }else{
                        $this->db->trans_commit ();
                    }//end 事务
                }elseif($order['type'] == 2){//查询check_accout表
                    $this->load->model ( 'iwidepay/Iwidepay_merchant_model' );
                    $check_order = $this->Iwidepay_merchant_model->get_check_account('*',array('partner_trade_no'=>$parse_arr['orderNo'],'status'=>3));
                    if(empty($check_order)){
                        return false;
                    }
                    //更新为成功
                    $this->Iwidepay_merchant_model->update_check_account(array('partner_trade_no'=>$parse_arr['orderNo'],'status'=>3),array('status'=>1,'remark'=>$check_order['remark'].'|回调：成功'));
                }
                exit('SUCCESS');
            } 
        }else{
            return false;
        }
    }

    /**
     * [hotel_handle 订房模块回调逻辑]
     * @param  [array] $data      [分账订单信息]
     * @param  [array] $parse_arr [回调信息主体]
     * @return [type]            [description]
     */
    private function hotel_handle($data,$parse_arr){
        //配置采用pms单号还是本地单号进行支付
        $this->load->model ( 'hotel/Hotel_config_model' );
        $config_data = $this->Hotel_config_model->get_hotel_config ( $data ['inter_id'], 'HOTEL', 0, array (
            'ORDER_PAY_ORDERID'
        ) );
        if(!empty($config_data['ORDER_PAY_ORDERID'])&&$config_data['ORDER_PAY_ORDERID']=='web'){
            $this->load->model ( 'hotel/Order_check_model' );
            $order = $this->Order_check_model->get_order_by_weborderid ( $data ['inter_id'], $data ['order_no'] );
            $parse_arr ['orderNo']=$order['orderid'];
        }
        // 修改模块订单状态
        if($parse_arr['respCode']==='0000'){
            $this->load->helper ( 'common_helper' );
            
            $this->db->where ( array (
                    'orderid' => $parse_arr['orderNo']
                    // 'openid' => $openid 
            ) );
            $order = $this->db->get ( 'hotel_orders' )->row_array ();
            if ($order && $order ['paid'] == 0) {
                $this->db->where ( array (
                        'orderid' => $parse_arr['orderNo']
                        // 'openid' => $openid 
                ) );
                $this->db->update ( 'hotel_orders', array (
                        'paid' => 1,
                        'isdel' => 0                        
                ) );
                $this->load->model ( 'hotel/Order_model' );
                $this->Order_model->pay_return ( $parse_arr['orderNo'] );
            }
        }else{
            $this->db->where ( array (
                    'orderid' => $parse_arr['orderNo'],
                    // 'openid' => $openid 
            ) );
            $this->db->update ( 'hotel_orders', array (
                    'operate_reason' => '支付失败订单' 
            ) );
            exit('SUCCESS');
        }
    }

    /**
     * [soma_handle 商城模块回调逻辑]
     * @param  [array] $data      [分账订单信息]
     * @param  [array] $parse_arr [回调信息主体]
     * @return [type]            [description]
     */
    private function soma_handle($data,$parse_arr){
        $this->load->model('soma/Sales_order_model');
        $order_id = $this->Sales_order_model->wx_out_trade_no_decode($parse_arr['orderNo']);

        $order_simple= $this->Sales_order_model->get_order_simple($order_id);

        //初始化数据库分片配置
        if( $order_simple['inter_id'] ){
            $this->load->model('soma/shard_config_model', 'model_shard_config');
            $this->current_inter_id= $order_simple['inter_id'];
            $this->db_shard_config= $this->model_shard_config->build_shard_config($order_simple['inter_id']);
            //print_r($this->db_shard_config);
        } else {
            Soma_base::inst()->show_exception($order_id. '微信支付回调数据分片配置失败！');
        }

        //处理结果成功与否
        $this->load->helper('soma/package');
        if ($parse_arr['respCode'] === '0000') {
            $debug = true;
            if ($debug) write_log('soma payment iwidepay_return invoked');

            //公共保存部分
            $this->load->model('soma/sales_payment_model');
            $payment_model= $this->sales_payment_model;
            //取得商户/子商户的openid,
            //$openid = empty ( $result['sub_openid'] ) ? $result['openid'] : $result['sub_openid'];
            
            $log_data= array();
            $log_data['paid_ip']= $this->input->ip_address();
            $log_data['paid_type']= $payment_model::PAY_TYPE_WX;
            $log_data['order_id']= $order_id;
            $log_data['openid']= $order_simple['openid'];
            $log_data['business']= $order_simple['business'];
            $log_data['settlement']= $order_simple['settlement'];
            $log_data['inter_id']= $order_simple['inter_id'];
            $log_data['hotel_id']= $order_simple['hotel_id'];
            $log_data['grand_total']= $order_simple['grand_total'];
            $log_data['transaction_id']= $parse_arr['payId'];
            /**
             * @var Sales_order_model $order
             */
            $order = $this->Sales_order_model->load($order_id);
            if( empty($order) ){
                Soma_base::inst()->show_exception($order_id. '微信支付回调 Sales Model初始化失败！');
            }

            if( !in_array( $order->m_get('status'), $order->can_payment_status()) ) {
                Soma_base::inst()->show_exception('订单号[' .$log_data['order_id'] .']不能重复支付，或目前处于不能支付的状态。');
            }

            $order->order_payment( $log_data );
            $order->order_payment_post();
            
            $this->sales_payment_model->save_payment($log_data, NULL);  //校验签名时已经记录
            //$this->sales_payment_model->save_payment($log_data, $xml);  //插入支付记录，并文件记录xml内容

            if ($debug) write_log('soma payment wxpay_return invoked end');
            
        } else {
            write_log($xml, 'iwidepay_return_code_fail.txt' );
            exit('SUCCESS');
        }
    }

    /**
     * [vip_handle 会员模块回调逻辑]
     * @param  [type] $data      [分账订单信息]
     * @param  [type] $parse_arr [回调信息主体]
     * @return [type]            [description]
     */
    private function vip_handle($data,$parse_arr){
        //查询订单信息
        $okxml = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        $failxml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        $_token = $this->_get_Token();
        $post_order_url = INTER_PATH_URL.'depositorder/get_order';
        $post_order_data = array(
            'token'=>$_token,
            'inter_id'=>$data['inter_id'],
            'openid'=>$data['openid'],
            'orderId'=>$data['order_no'],
        );
        $order_info = $this->doCurlPostRequest( $post_order_url , $post_order_data );
        $this->_api_write_log(json_encode($order_info),'vipokpay_order_info');
        if(isset($order_info['err']) || !isset($order_info['data']) || empty($order_info['data'])){
            $this->_api_write_log('empty order info','vipokpay_status');
            echo $failxml;exit;
        }
        if($order_info['data']['order_num'] != $parse_arr['orderNo'] ){
            $this->_api_write_log('order number is error','vipokpay_status');
            echo $failxml;exit;
        }

        //========判断订单的order_id, openid, 总金额是否相符
        $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
        $total_dif= float_precision_match($parse_arr['transAmt'], $order_info['data']['pay_money']*100);
        if( ! $total_dif ){
            $file= 'vip_wxpay_return_exception';
            $this->write_log($xml, $file);      //记录下签名失败的记录
            Soma_base::inst()->show_exception($parse_arr['orderNo']. '微信支付回调返回total_fee['
                . $parse_arr['transAmt'] .']与订单金额[' . $order_info['data']['pay_money']*100 .']不一致！');
            echo $failxml;exit;
        }

        if($order_info['data']['pay_status']=='t'){
            $this->_api_write_log('order pay is success','vipokpay_status');
            echo $okxml;exit;
        }

        //查询购卡信息
        $post_cardinfo_url = PMS_PATH_URL."depositcard/getinfo";
        $post_cardinfo_data = array(
            'inter_id'=>$data['inter_id'],
            'deposit_card_id'=>$order_info['data']['deposit_card_id'],
        );
        $card_info= $this->doCurlPostRequest( $post_cardinfo_url , $post_cardinfo_data );
        $this->_api_write_log(json_encode($card_info),'vipokpay_card_info');
        if(!isset($card_info['data']) || !$card_info['data']  ){
            $card_info['data']['is_package'] = 'f';
            $deposit_data['is_balance'] = 't';
            $deposit_data['distribution_money'] = 0;
        }else{
            $deposit_data = $card_info['data'];
        }
        //修改订单信息，以及增加储值的金额
        $post_upOrder_url = INTER_PATH_URL.'depositorder/update_order';
        $post_upOrder_data = array(
            'token'=>$_token,
            'inter_id'=>$data['inter_id'],
            'openid'=>$data['openid'],
            'deposit_card_pay_id'=>$data['order_no'],
        );
        $update_result = $this->doCurlPostRequest( $post_upOrder_url , $post_upOrder_data );
        $this->_api_write_log(json_encode($update_result),'vipokpay_update_result');
        if($update_result['err']>0){
            $this->_api_write_log('order info error','vipokpay_status');
            echo $failxml;exit;
        }else{
            //判断重置是否计入余额
            if(isset($deposit_data['is_balance']) && $deposit_data['is_balance']=='t'){
                //增加储值
                $add_deposit_url = INTER_PATH_URL.'deposit/add';
                $add_deposit_data = array(
                    'token'=>$_token,
                    'inter_id'=>$data['inter_id'],
                    'openid'=>$data['openid'],
                    'count'=>($parse_arr['transAmt']/100),
                    'uu_code'=>uniqid(),
                    'module'=>'vip',
                    'scene'=>'vip',
                    'note'=>'会员充值储值',
                    'deposit_type'=>isset($deposit_data['deposit_type'])?$deposit_data['deposit_type']:'c',
                    'order_id'  => $parse_arr['transaction_id'], //微信的流水单号
                    'local_id'  => $data['order_no']
                );
                $add_deposit = $this->doCurlPostRequest( $add_deposit_url , $add_deposit_data );
                $this->_api_write_log(json_encode($add_deposit),'vipokpay_add_deposit');
                if($add_deposit['err']>0){
                    $this->_api_write_log('balance is error','vipokpay_status');
                }
            }
            //检查是否属于泛分销
            MYLOG::w("Wechat pay | Type Order Info | ".@json_encode($order_info),'membervip/debug-log');
            if( isset($order_info['data']['distribution_num']) && $order_info['data']['distribution_num'] > 0 &&  $order_info['data']['distribution_type'] == 'FANS' ){
                $where = array(
                    'id'=>$order_info['data']['distribution_num'],
                    'inter_id'=>$data['inter_id'],
                );
                $this->load->model('membervip/common/Public_model','p_model');
                $pan_sales = $this->p_model->get_info($where,'distribution_member');
                MYLOG::w("Wechat pay | Type Get pan sales info | ".@json_encode(array('result'=>$pan_sales,'where'=>$where)),'membervip/debug-log');
                if(!empty($pan_sales)){
                    $this->load->model('distribute/Idistribute_model','idistribute');
                    $fansInfo = $this->idistribute->fans_is_saler($data['inter_id'],$pan_sales['open_id']);
                    MYLOG::w("Wechat pay | Type Get dis fansInfo | ".@json_encode(array('result'=>$fansInfo,'params'=>$pan_sales)),'membervip/debug-log');
                    $salesInfo = json_decode($fansInfo,true);
                    if($salesInfo && $salesInfo['typ'] == 'FANS'){
                        //TODO
                        $this->load->model('pay/Company_pay_model','pay_model');
                        $reward = 0;
                        switch ($order_info['data']['deposit_card_id']){
                            case 138:$reward = 100;break;
                            case 139:$reward = 60;break;
                            case 173:$reward = 10;break;
                        }
//                        $reward = 0.01;
                        //插入发放记录
                        $insert_data = array(
                            'inter_id'=>$data['inter_id'],
                            'open_id'=>$data['openid'],
                            'type'=>'dis_pan',
                            'record_title'=>'购卡泛分销',
                            'sales_id'  => $order_info['data']['distribution_num'],
                            'reward'=>$reward,
                            'sn'=>"card{$order_info['data']['order_num']}",
                            'createtime'=>date('Y-m-d H:i:s'),
                            'status'=>'f'
                        );
                        $add_sales = $this->p_model->add_data($insert_data,'distribution_record');
                        MYLOG::w("Wechat pay | Type Insert record 插入发放记录| ".@json_encode(array('result'=>$add_sales,'data'=>$insert_data)),'membervip/debug-log');
                        $distribute_arr = array(
                            'inter_id'=>$data['inter_id'],
                            'hotel_id'=>0,
                            'saler'=>$salesInfo['info']['saler'],
                            'grade_openid'=>$data['openid'],
                            'grade_table'=>'iwide_member4_fans',
                            'grade_id'=>$order_info['data']['deposit_card_pay_id'],
                            'order_amount'=>$order_info['data']['pay_money'],
                            'grade_total'=>$reward,
                            'remark'=>$deposit_data['title'],
                            'grade_amount'=>$order_info['data']['pay_money'],
                            'order_time'    => date("Y-m-d H:i:s",time()),
                            'status'=>1,
                            'grade_typ' => 2,
                            'product'=>$deposit_data['title'],
                            'order_status'=>'已完成'
                        );
                        $distribute_result = $this->idistribute->create_ext_grade( $distribute_arr );
                        MYLOG::w("Wechat pay | Type create_dist 绩效发放记录| ".@json_encode(array('result'=>$distribute_result,'param'=>$distribute_arr)),'membervip/debug-log');
                        if($distribute_result){
                            $save_data = array(
                                'status'=>'t'
                            );
                            $params = array(
                                'inter_id'=>$data['inter_id'],
                                'open_id'=>$data['openid'],
                                'sn'=>"card{$order_info['data']['order_num']}",
                                'type'=>'dis_pan',
                                'status'=>'f'
                            );
                            $update_sales = $this->p_model->update_save($params,$save_data,'distribution_record');
                            MYLOG::w("Wechat pay | Type update 绩效发放记录| ".@json_encode(array('result'=>$update_sales,'data'=>$save_data)),'membervip/debug-log');
                        }
                    }
                }
            }
            //加入分销数据
            else if( isset( $deposit_data['distribution_money']) && $deposit_data['distribution_money']>0 && isset($order_info['data']['distribution_num']) && $order_info['data']['distribution_num'] > 0){
                $this->load->model('distribute/Idistribute_model','idistribute');
                $distribute_arr = array(
                    'inter_id'=>$data['inter_id'],
                    'hotel_id'=>0,
                    'saler'=>$order_info['data']['distribution_num'],
                    'grade_openid'=>$data['openid'],
                    'grade_table'=>'iwide_member4_order',
                    'grade_id'=>$order_info['data']['deposit_card_pay_id'],
                    'grade_id_name'=>'充值订单ID',
                    'order_amount'=>$order_info['data']['pay_money'],
                    'grade_total'=>$deposit_data['distribution_money'],
                    'grade_amount'=>$order_info['data']['pay_money'],
                    'grade_amount_rate'=>$deposit_data['distribution_money'],
                    'grade_rate_type'=>0,
                    'status'=>1,
                    'remark'=>$deposit_data['title'],
                    'product'=>$deposit_data['title'],
                    'order_status'=>'已完成',
                    'order_id'=>$order_info['data']['order_num'],
                );
                $distribute_result = $this->idistribute->create_dist( $distribute_arr );
                if(!$distribute_result){
                    //不成功写入日志
                    $log_data = array(
                        'msg'=>'分销记录失败',
                        'info'=>$distribute_arr,
                    );
                    $this->vip_order_write_log(serialize($log_data) );
                }
            }

            //赠送套餐
            if($card_info['data']['is_package']=='t'){
                $packge_url = INTER_PATH_URL.'package/give';
                $package_data = array(
                    'token'=>$_token,
                    'inter_id'=>$data['inter_id'],
                    'openid'=>$data['openid'],
                    'uu_code'=>uniqid(),
                    'package_id'=>$card_info['data']['package_id'],
                );
                $package_deposit = $this->doCurlPostRequest( $packge_url , $package_data );
                $this->_api_write_log(json_encode($package_deposit),'vipokpay_package');
                if($package_deposit['err']>0){
                    $this->_api_write_log('package is error','vipokpay_status');
                }
            }

            //入账
            //if($order_info['data']['is_bill']=='f' && $order_info['data']['inter_id']=='a474597291'){
            if($order_info['data']['is_bill']=='f' && $order_info['data']['inter_id']=='a464919542'){
                $into_bill_url = INTER_PATH_URL."depositorder/intobill";
                $req_data = array(
                    'inter_id'=>$data['inter_id'],
                    'openid'=>$data['openid'],
                    'deposit_card_pay_id'=>isset($order_info['data']['deposit_card_pay_id'])?$order_info['data']['deposit_card_pay_id']:0,
                    'transaction_id'=>$return_data['transaction_id'],
                    'amount'=>($parse_arr['transAmt']/100),
                    'deposit_type'=>isset($deposit_data['deposit_type'])?$deposit_data['deposit_type']:'c'
                );
                $res = $this->doCurlPostRequest($into_bill_url,$req_data);
                $this->_api_write_log(json_encode($res),'vipokpay_intobill');
            }

        }
    }

     //获取授权token
    protected function _get_Token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        return isset($token_info['data'])?$token_info['data']:"";
    }

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function _api_write_log( $content, $type='request' ){
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'membervip'. DS.'returnpay'.DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->_api_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * [hotel_handle 快乐付模块回调逻辑]
     * @param  [array] $data      [分账订单信息]
     * @param  [array] $parse_arr [回调信息主体]
     * @return [type]            [description]
     */
    private function okpay_handle($data,$parse_arr){
        // 修改模块订单状态
        if($parse_arr['respCode']==='0000'){//成功
            $this->db->where ( array (
                    'out_trade_no' => $parse_arr['orderNo'],
                     'inter_id' => $data['inter_id'],
            ) );
            $order = $this->db->get ( 'okpay_orders' )->row_array ();
            if ($order && $order['pay_status'] == 1) {//未改状态
                    $this->db->where ( array (
                        'out_trade_no' => $parse_arr['orderNo'],
                        'inter_id' => $data['inter_id'],
                    ) );
                    $this->db->update ('okpay_orders', array (
                            'pay_status' => 3,//支付成功
                            'trade_no'=>$parse_arr['payId'],
                            'pay_time'=>time(),
                            'update_time'=>time(),
                    ) );

                     //发送模板消息 stgc 20161107
                    $order['pay_time'] = time();
                    $this->load->model ( 'plugins/Template_msg_model' );
                    //发送给用户
                    $res = $this->Template_msg_model->send_okpay_success_msg ( $order, 'okpay_order_success' );
                    //发送给管理员 先查一次授权的管理员
                    $this->load->model('okpay/okpay_type_model');
                    $admins = $this->okpay_type_model->get_type_saler_info($order['inter_id'],$order['pay_type']);
                    if(!empty($admins)){
                        foreach($admins as $k=>$v){
                            $order['openid'] = $v['openid'];
                            $res = $this->Template_msg_model->send_okpay_success_msg ( $order, 'okpay_order_notice' );
                        }
                    }
                    //添加打印订单操作 situguanchen 2017-03-20
                    $this->load->model ( 'plugins/Print_model' );
                    $res  = $this->Print_model->print_okpay_order ($order,'okpay_pay_success');
                
                //end 模板消息
            }
        }else{
            MYLOG::w('okpay分账回调有误：'.json_encode($data),'iwidepayreturn');
            exit('SUCCESS');
        }
       
    }

    /**
     * [dc_handle 快乐送，门票模块回调逻辑]
     * @param  [array] $data      [分账订单信息]
     * @param  [array] $parse_arr [回调信息主体]
     * @return [type]            [description]
     */
    private function dc_handle($data,$parse_arr){
        // 修改模块订单状态
        if($parse_arr['respCode']==='0000'){//成功
            $this->load->model ( 'roomservice/roomservice_orders_model' );
            $orderModel = $this->roomservice_orders_model;
            $this->db->where ( array (
                    'order_sn' => $parse_arr['orderNo'],
                     'inter_id' => $data['inter_id'],
            ) );
            $order = $this->db->get ( 'roomservice_orders' )->row_array ();
             if ($order && $order ['pay_status'] == $orderModel::IS_PAYMENT_NOT) {
                    $this->db->where ( array (
                        'order_sn' => $parse_arr['orderNo'],
                        'inter_id' => $data['inter_id'],
                    ) );
                    $this->db->update ('roomservice_orders', array (
                            'pay_status' => $orderModel::IS_PAYMENT_YES,
                            'pay_time' => date('Y-m-d H:i:s'),
                            'pay_money'=> $parse_arr['transAmt'] / 100,
                            'trade_no' => $parse_arr['payId'],
                    ) );

                    if($order['type'] == 4){//门票
                        //判断店铺 即时确认 自动接单
                        $arr_shop['shop_id'] = $order['shop_id'];
                        $this->load->model ( 'roomservice/roomservice_shop_model' );
                        $shop_info = $this->roomservice_shop_model->get($arr_shop);
                        MYLOG::w(json_encode($shop_info).'自动接单后！', 'roomservice');
                        if (!empty($shop_info) && $shop_info['instant_confirm'] == 1)
                        {
                            $this->roomservice_orders_model->auto_update_status($order['inter_id'],$order);
                            MYLOG::w($data['out_trade_no'].'自动接单！', 'roomservice');
                        }
                    }
                   $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
                
            }
        }else{
            $this->db->where ( array (
                 'order_sn' => $parse_arr['orderNo'],
                'inter_id' => $data['inter_id'],
                // 'openid' => $openid
            ) );
            $this->db->update ( 'roomservice_orders', array (
                'operate_reason' => '支付失败订单'
            ) );
            MYLOG::w('dc分账回调有误：'.json_encode($data),'iwidepayreturn');
            exit('SUCCESS');
        }
       
    }


    /**
     * 预约核销回调处理
     * @param $data
     * @param $parse_arr
     */
    private function ticket_handle($data,$parse_arr)
    {
        // 修改模块订单状态
        if($parse_arr['respCode']==='0000')
        {
            $this->load->helper ( 'common_helper' );

            //查询总订单信息
            $this->load->model('ticket/ticket_orders_merge_model');
            $where_arr = array(
                'inter_id' => $data['inter_id'],
                'order_no' => $parse_arr['orderNo'],
                'pay_status' => 0
            );
            $order_merge = $this->ticket_orders_merge_model->order_info($where_arr);
            if (!empty($order_merge))
            {
                //========判断订单的order_id, openid, 总金额是否相符
                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                $total_dif = float_precision_match($parse_arr['transAmt'], $order_merge['pay_fee'] *100);
                if(! $total_dif)
                {
                    MYLOG::w($parse_arr ['orderNo']. '微信支付回调返回total_fee['
                        . $parse_arr['transAmt'] .']与订单金额[' . $order_merge['pay_fee']*100 .']不一致！','ticket');
                    die;
                }

                $this->load->model ( 'roomservice/roomservice_orders_model' );
                $orderModel = $this->roomservice_orders_model;
                $this->db->where (array(
                    'inter_id' => $data ['inter_id'],
                    'merge_order_no' => $parse_arr ['orderNo'],
                ));
                $orders = $this->db->get('roomservice_orders')->result_array();
                MYLOG::w(json_encode($orders), 'ticket');

                $this->db->trans_begin(); //开启事务
                $auto_confirm = 0;
                $auto_res = 1;
                if (!empty($orders))
                {
                    $order_res = 1;
                    foreach ($orders as $key=> $order)
                    {
                        if ($order['pay_status'] == $orderModel::IS_PAYMENT_NOT)
                        {
                            $this->db->where ( array (
                                'order_id' => $order ['order_id'],
                                'inter_id' => $data ['inter_id'],
                            ) );
                            $this->db->update ( 'roomservice_orders', array (
                                'pay_status' => $orderModel::IS_PAYMENT_YES,
                                'pay_time' => date('Y-m-d H:i:s'),
                                'pay_money'=> $order['sub_total'],
                                'trade_no' => $parse_arr['payId'],
                            ) );

                            $o_res = $this->db->affected_rows();
                            if ($o_res == 0)
                            {
                                $order_res = 0;
                            }

                            //判断店铺 即时确认 自动接单
                            if ($key == 0)
                            {
                                $arr_shop['shop_id'] = $order['shop_id'];
                                $this->load->model ( 'roomservice/roomservice_shop_model' );
                                $shop_info = $this->roomservice_shop_model->get($arr_shop);
                            }

                            MYLOG::w(json_encode($shop_info).'自动接单后！', 'ticket');
                            if (!empty($shop_info) && $shop_info['instant_confirm'] == 1)
                            {
                                $auto_confirm = 1;
                                $auto_res =  $this->roomservice_orders_model->auto_update_status($order['inter_id'],$order);
                                MYLOG::w($data['orderNo'].'自动接单！', 'ticket');
                            }

                            $this->roomservice_orders_model->pay_return ($order['order_id'],$order['inter_id'],$order['openid']);
                        }
                        else
                        {
                            $this->db->trans_rollback();
                            MYLOG::w($data['orderNo'].'订单信息已支付！', 'ticket');
                            die;
                        }
                    }
                }
                else
                {
                    $this->db->trans_rollback();
                    MYLOG::w($data['orderNo'].'订单信息有误！', 'ticket');
                    die;
                }

                //更改总单状态
                $update = array(
                    'update_time' => date('Y-m-d H:i:s'),
                    'pay_time' => date('Y-m-d H:i:s'),
                    'order_status' => $auto_confirm == 1 ? 1 : 0,
                    'pay_status' => 2,
                    'out_order_no' => $parse_arr['payId'],
                );
                MYLOG::w(json_encode($update).'更改的数据！', 'ticket');
                $where = array(
                    'merge_orderId' => $order_merge['merge_orderId'],
                );
                $res = $this->ticket_orders_merge_model->update_order($update,$where);
                MYLOG::w($auto_res.$res.$order_res.'状态！', 'ticket');
                if ($auto_res > 0 && $res > 0 && $order_res > 0)
                {
                    //提交事务
                    $this->db->trans_complete();
                }
                else
                {
                    $this->db->trans_rollback();
                    MYLOG::w($data['orderNo'].'更改订单失败！', 'ticket');
                    die();
                }
            }
        }
        else
        {
            $this->db->where ( array (
                'inter_id' => $data ['inter_id'],
                'order_no' => $parse_arr ['orderNo']
            ) );
            $this->db->update ( 'ticket_orders_merge', array (
                'operate_reason' => '支付失败订单'
            ) );

            MYLOG::w('dc分账回调有误：'.json_encode($data),'iwidepayreturn');
            exit('SUCCESS');
        }
    }


    /**
     * 处理存在主单号模块的逻辑
     * @param $data 订单数组
     * @param $parse_arr 回调数据
     */
    protected function multi_order($data,$parse_arr)
    {
        if (empty($data))
        {
            MYLOG::w('分账订单查询为空：'.$parse_arr['orderNo'].','.json_encode($data),'iwidepayreturn');
            exit('SUCCESS');
        }

        //总单金额
        $pay_fee = 0;
        foreach ($data as $value)
        {
            //重复回调判断
            if($value['transfer_status'] > 0)
            {
                exit('SUCCESS');
            }

            $pay_fee += $value['trans_amt'];
        }

        //判断总金额是否相符
        if($pay_fee != $parse_arr['transAmt'])
        {
            MYLOG::w($parse_arr['orderNo'].'|回调金额'.$parse_arr['transAmt'].'与订单金额'.$pay_fee.'不一致'.'iwidepayreturn');
            exit('SUCCESS');
        }

        //模块
        $idata = $data[0];

        //写入支付日志
        $in_arr = array(
            'inter_id' => $idata['inter_id'],
            'openid' => $idata['openid'],
            'out_trade_no' => $parse_arr['orderNo'],
            'transaction_id' => $parse_arr['payId'],
            'pay_time' => time(),
            'rtn_content' => json_encode($parse_arr),
            'type' => 'ip_'.$idata['module'],
        );
        $this->db->insert('pay_log', $in_arr);

        //各模块回调逻辑
        switch ($idata['module'])
        {
            case 'ticket':
                $this->ticket_handle($idata,$parse_arr);
                $order_status = 1;//支付状态
                $transfer_status = 1;
                break;
            default:
                $order_status = 1;
                $transfer_status = 1;
                break;
        }

        // 更新分账订单信息
        //parse_str($body, $params);
        foreach ($data as $value)
        {
            $split_order = array(
                'productid' => $parse_arr['productId'],
                'transid' => $parse_arr['transId'],
                'merno' => $parse_arr['merNo'],
                'order_status' => $order_status,
                'pay_time' => $parse_arr['payTime'],
                'pay_id' => $parse_arr['payId'],
                'transfer_status' => $transfer_status,
                'update_time' => date('Y-m-d H:i:s'),
            );
            MYLOG::w('分账订单更新数据：'.json_encode($split_order),'iwidepayreturn');
            $res = $this->Iwidepay_model->update_iwidepay_order($value['order_no'],$value['module'],$split_order);

            if(!$res)
            {
                MYLOG::w('分账订单更新失败：'.json_encode($res),'iwidepayreturn');
                //exit('SUCCESS');
            }
        }
        // 成功响应
        exit('SUCCESS');
    }
}