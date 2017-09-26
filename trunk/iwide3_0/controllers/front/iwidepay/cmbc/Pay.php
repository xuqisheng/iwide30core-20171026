<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Pay extends MY_Controller {
	public function __construct() {
		parent::__construct ();
		// 开发模式下开启性能分析
		$this->output->enable_profiler ( false );
		$this->load->library('IwidePay/IwidePayService',null,'IwidePayApi');
	}

    //民生服务器升级过程中间友情提示页面
    public function temp_pay_show(){
        // 2017-09-21 0点-1点服务器升级，展示友情提示页面
        if(time()>=strtotime('2017-09-21 00:00:00')&&time()<=strtotime('2017-09-21 01:00:00')){
            $data = array();
            $this->load->model('iwidepay/IwidePay_configs_model');
            $conf = $this->IwidePay_configs_model->get_configs_by_interid('jinfangka',1,'cmbcpay_shutdown','iwidepay');
            if(!empty($conf)&&$conf['value']==1){
                $this->load->view('iwidepay/default/temp_pay_show',$data);
                return true;
            }
        }
        return false;
    }

	public function hotel_pay(){
    		$arr = array(
            'commodityName' => '测试产品',
            // 'merNo' => '850440053991050',
            // 'notifyUrl' => $notifyUrl,
            'openid' => $this->input->get('openid'),//$this->session->userdata($this->inter_id.'openid'),
            'orderDate' => date('Ymd'),
            'orderNo' => time(),
            'productId' => '0105',
            'requestNo' => time(),
            'transAmt' => $this->input->get('money'),
            'transId' => '10',
            'subMerNo'=>'21434543535',
            'subMerName'=>'酒店名称',
            // 'version' => 'V2.0',
            'returnUrl' => 'http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/pay/success',//测试
            // 'returnUrl' => 'http://cmbcpay.jinfangka.com/index.php/iwidepay/cmbc/pay/success',//生产
        );
        $this->load->library('MYLOG');
        MYLOG::w(json_encode($arr),'iwidepaypay');
        // var_dump($arr);exit;
        $info = $this->IwidePayApi->unifyPayRequest($arr);
        MYLOG::w($info,'iwidepaypay');
        // var_dump($info);exit;
        // $url = IwidePayConfig::REQUEST_URL;
        // if(!empty($arr['requestUrl'])){
        // 	$url = $arr['requestUrl'];
        // }
        // $res = parse_url($url . "?" . $info); 
        // $arr_query = convertUrlQuery($res['query']);
        // $payinfo = explode("payInfo=", $arr_query[6]);
        $data = array();
        // $data ['jsApiParameters'] = json_decode($payinfo[1],1);
        parse_str($info,$result_arr);
        $data ['jsApiParameters'] = json_decode($result_arr['payInfo'],1);
        $data ['returnUrl'] = $arr['returnUrl'];
        $this->load->view ( 'iwidepay/default/pay', $data );
        // var_dump(json_decode($payinfo[1]),true);exit;
	}

    //订房支付
    public function hotel_order(){
        if($this->temp_pay_show()){
            return;
        }
        //统计探针
        $this->load->library('MYLOG');
        $inter_id = $this->input->get ( 'id', true );

        $parameters = '';
        $data = array ();
        $orderid = $this->input->get ( 'orderid', true );
        if ($orderid) {
            // $this->load->model ( 'pay/Wxpay_model' );
            // $this->load->model ( 'pay/Pay_model' );
            $this->load->model ( 'hotel/Order_model' );
            $this->load->model ( 'wx/Publics_model' );
            $this->load->model('iwidepay/Iwidepay_model');
            // 公众号
            $public = $this->Publics_model->get_public_by_id ( $inter_id );
            if (! empty ( $public ['app_id'] )) {
                $order_details = $this->Order_model->get_main_order ( $inter_id, array (
                    'orderid' => $orderid,
                    'idetail' => array (
                        'i'
                    )
                ) );
                if ($order_details) {
                    $order_details = $order_details [0];
                    $openid = $order_details['openid'];
                    MYLOG::hotel_tracker($openid,   $inter_id);
                    //若订单已取消，跳转再次预定页面
                    $this->load->model ( 'hotel/Order_check_model' );
                    $re = $this->Order_check_model->check_order_state($order_details);
                    if($re['re_pay']!=1){
                        redirect ( 'http://'.$public['domain']. '/index.php/hotel/hotel/index?id=' . $inter_id .'&h=' .$order_details['hotel_id'] );
                    }

                    //配置采用pms单号还是本地单号进行支付
                    $pay_orderid=$order_details ['orderid'];
                    $this->load->model ( 'hotel/Hotel_config_model' );
                    $config_data = $this->Hotel_config_model->get_hotel_config ( $order_details ['inter_id'], 'HOTEL', 0, array (
                        'ORDER_PAY_ORDERID'
                    ) );
                    if(!empty($config_data['ORDER_PAY_ORDERID'])&&$config_data['ORDER_PAY_ORDERID']=='web'){
                        $pay_orderid=$order_details['web_orderid'];
                    }

                    //生成分账订单
                    $split_order = array(
                        'inter_id' => $inter_id,
                        'hotel_id' => $order_details['hotel_id'],
                        'openid' => $openid,
                        'order_no' => $pay_orderid,
                        'pay_no'=> $pay_orderid,
                        'order_status' => 0,
                        'orig_amount' => $order_details ['price'] * 100,
                        'trans_amt' => $order_details ['price'] * 100,
                        'order_date' => date('Ymd'),
                        'module' => 'hotel',
                        'pay_type' => 'weixin',
                        'transfer_status' => 0,
                        'add_time' => date('Y-m-d H:i:s'),
                        'update_time' => date('Y-m-d H:i:s'),
                        'is_dist' => 0,
                        );
                    MYLOG::w('分账订单入库数据：'.json_encode($split_order),'iwidepaypay');
                    $res = $this->Iwidepay_model->save_iwidepay_order($split_order);
                    if(!$res){
                        MYLOG::w('分账订单入库失败：'.json_encode($res),'iwidepaypay');
                        exit('程序错误，微信支付失败');
                    }
                    $data ['fail_url'] = 'http://'.$public['domain']. '/index.php/hotel/hotel/myorder?id=' . $inter_id;
                    $data ['success_url'] = $data ['fail_url'];
                    $data ['fail_url'] .= '&fro='.$orderid . '&type=' . $order_details ['price_type'].'&lsaler='.$order_details['link_saler'];
                    $data ['success_url'] = 'http://'.$public['domain']. '/index.php/hotel/hotel/orderdetail?id=' . $inter_id . '&oid=' . $order_details ['id'].'&lsaler='.$order_details['link_saler'];
                    $cmbc_chnl_id = $this->Iwidepay_model->get_cmbc_chnl_id($inter_id);
                    $commodityName = $order_details ['hname'] . ' - ' .$order_details ['first_detail'] ['roomname'];
                    $subMerName = !empty($order_details ['hname'])?$order_details ['hname']:$public['name'];
                    $jsApiArr = array(
                        'subChnlMerNo' => $cmbc_chnl_id,
                        'commodityName' => $this->do_string($commodityName),
                        'openid' => $openid,
                        'orderDate' => date('Ymd'),
                        'orderNo' => $pay_orderid,
                        'productId' => '0105',
                        'requestNo' => time().rand(10000,99999),
                        'transAmt' => $order_details ['price'] * 100,
                        'transId' => '10',
                        'subMerNo'=> IwidePayConfig::MERNO,
                        'subMerName'=> $this->do_string($subMerName,20),
                        // 'version' => 'V2.0',
                        'returnUrl' => site_url ( 'hotel/hotel/orderdetail' ) . '?id=' . $inter_id ,
                        );
                    MYLOG::w('支付下单参数：'.json_encode($jsApiArr),'iwidepaypay');
                    $result = $this->IwidePayApi->unifyPayRequest($jsApiArr);
                    MYLOG::w('支付参数：'.$result,'iwidepaypay');
                    // $url = IwidePayConfig::REQUEST_URL;
                    // if(!empty($jsApiArr['requestUrl'])){
                    //     $url = $jsApiArr['requestUrl'];
                    // }
                    // $res = parse_url($url . "?" . $result); 
                    // $arr_query = convertUrlQuery($res['query']);
                    // $payinfo = explode("payInfo=", $arr_query[6]);
                    // $parameters = $payinfo[1];
                    parse_str($result,$result_arr);
                    $parameters = $result_arr['payInfo'];
                }
            }
        }
        $data ['jsApiParameters'] = $parameters;
        $this->load->view ( 'pay/default/wxpay', $data );
    }

    //商城支付
    public function soma_pay(){
        if($this->temp_pay_show()){
            return;
        }
        $this->load->somaDatabase($this->db_soma);
        $this->load->somaDatabaseRead($this->db_soma_read);

        $inter_id = $this->input->get('id');
        $order_id= $this->input->get('orderid');
        //初始化数据库分片配置
        if( $inter_id ){
            $this->load->model('soma/shard_config_model', 'model_shard_config');
            $this->current_inter_id= $inter_id;
            $this->db_shard_config= $this->model_shard_config->build_shard_config($inter_id);
            //print_r($this->db_shard_config);
        }

        $this->load->model('soma/Sales_order_model');
        $order_detail= $this->Sales_order_model->get_order_simple($order_id);
        $openid= $order_detail['openid'];
        MYLOG::soma_tracker($openid,   $inter_id);

        if( $order_id && $inter_id && $openid && $order_detail ){
            $this->load->model('pay/wxpay_model');
            $this->load->model('pay/pay_model' );
            $this->load->model('wx/publics_model');
            $this->load->model('iwidepay/Iwidepay_model');
            $public = $this->publics_model->get_public_by_id($inter_id);

            if(!empty($public['app_id'])){

                $this->load->model('soma/Sales_order_model');
                $business_type= $this->Sales_order_model->get_business_type();  //各种业务类型中文标识：套票|

                if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' )
                    $business_type= '月饼';

                $settle_type= $this->Sales_order_model->get_settle_label();  //各种结算方式中文标识：普通购买|拼团购买
                
                // $order_desc= $public['name']. '_';
                // $order_desc.= array_key_exists($order_detail['business'], $business_type)? $business_type[$order_detail['business']]: '';
                // $order_desc.= array_key_exists($order_detail['settlement'], $settle_type)? $settle_type[$order_detail['settlement']]: '';
                // $order_desc.= '#'. $order_id;
                // 获取商品名称
                $this->load->model('soma/Sales_item_package_model');
                $orderItems = $this->Sales_item_package_model->get_order_items_byIds($order_id, $order_detail['business'], $inter_id);
                $order_desc = !empty($orderItems) ? $orderItems[0]['name'] : '';
                
                // if( $order_detail['settlement']== Sales_order_model::SETTLE_KILLSEC ){
                //     //对于秒杀限定其支付有效期
                //     $this->wxpay_model->setParameter("time_expire", date('YmdHis')+ 300);
                // }

                $wx_order_id= $this->Sales_order_model->wx_out_trade_no_encode($order_id, $order_detail['settlement'], $order_detail['business']);
            }

            //生成分账订单
            $split_order = array(
                'inter_id' => $inter_id,
                'hotel_id' => $order_detail['hotel_id'],
                'openid' => $openid,
                'order_no' => $order_id,
                'pay_no'=> $wx_order_id,
                'order_status' => $order_detail['status'],
                'orig_amount' => $order_detail ['grand_total'] * 100,
                'trans_amt' => $order_detail['grand_total']* 100,
                'order_date' => date('Ymd'),
                'module' => 'soma',
                'pay_type' => 'weixin',
                'transfer_status' => 0,
                'add_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
                'is_dist' => 0,
                );
            MYLOG::w('分账订单入库数据：'.json_encode($split_order),'iwidepaypay');
            $res = $this->Iwidepay_model->save_iwidepay_order($split_order);
            if(!$res){
                MYLOG::w('分账订单入库失败：'.json_encode($res),'iwidepaypay');
                exit('程序错误，微信支付失败');
            }

            $urlParams = array(
                'id'=> $inter_id,
                'order_id'=> $order_id,
                'settlement'=>$order_detail['settlement']
            );

            $successUrl = Soma_const_url::inst()->get_payment_success($order_detail['business'],$urlParams);
            //回跳链接域名替换
            $purl = parse_url($successUrl);
            $successUrl = 'http://'.$public['domain'].$purl['path'];
            $successUrl .= !empty($purl['query'])?'?'.$purl['query']:'';

            $buyType= $this->input->get('bType');
            if(empty($buyType)){
                $data['success_url'] = $successUrl;
            }else{
                $data['success_url'] = $this->Sales_order_model->success_payment_path($inter_id,$buyType,$order_detail,$successUrl);
                //回跳链接域名替换
                $surl = parse_url($data['success_url']);
                $data['success_url'] = 'http://'.$public['domain'].$surl['path'];
                $data['success_url'] .= !empty($surl['query'])?'?'.$surl['query']:'';
            }

            if($this->input->get('wxpay_order_type') == 2) {
                // 邮费订单，成功跳转链接为邮寄详情
                // $spid = $this->session->userdata('spid');
                if( false && $spid ){   
                    $data['success_url'] = 'http://'.$public['domain']. '/index.php/soma/consumer/shipping_detail?id='.$inter_id.'&spid='.$spid;
                }else{
                    $data['success_url'] = 'http://'.$public['domain']. 'index.php/soma/consumer/my_shipping_list?id='.$inter_id;
                }
            }
            $data['fail_url'] = Soma_const_url::inst()->get_payment_fail($order_detail['business'], $urlParams);
            //回跳链接域名替换
            $furl = parse_url($data['fail_url']);
            $data['fail_url'] = 'http://'.$public['domain'].$furl['path'];
            $data['fail_url'] .= !empty($furl['query'])?'?'.$furl['query']:'';
            $cmbc_chnl_id = $this->Iwidepay_model->get_cmbc_chnl_id($inter_id);
            $hotel_info = $this->Iwidepay_model->get_hotel_info($order_detail['hotel_id']);
            $commodityName = $this->do_string($order_desc);
            $subMerName = !empty($hotel_info['name'])?$hotel_info['name']:$public['name'];
            $jsApiArr = array(
                'subChnlMerNo' => $cmbc_chnl_id,
                'commodityName' => $commodityName,
                'openid' => $openid,
                'orderDate' => date('Ymd'),
                'orderNo' => $wx_order_id,
                'productId' => '0105',
                'requestNo' => time().rand(10000,99999),
                'transAmt' => $order_detail['grand_total']* 100,
                'transId' => '10',
                'subMerNo'=> IwidePayConfig::MERNO,
                'subMerName'=> $this->do_string($subMerName,20),
                // 'version' => 'V2.0',
                'returnUrl' => Soma_const_url::inst()->get_url('soma/consumer/my_shipping_list', array('id'=> $inter_id ) ) ,
                );
            MYLOG::w('支付下单参数：'.json_encode($jsApiArr),'iwidepaypay');
            $result = $this->IwidePayApi->unifyPayRequest($jsApiArr);
            MYLOG::w('支付参数：'.$result,'iwidepaypay');
            // $url = IwidePayConfig::REQUEST_URL;
            // if(!empty($jsApiArr['requestUrl'])){
            //     $url = $jsApiArr['requestUrl'];
            // }
            // $res = parse_url($url . "?" . $result);
            // $arr_query = convertUrlQuery($res['query']);
            // $payinfo = explode("payInfo=", $arr_query[6]);
            // $parameters = $payinfo[1];
            parse_str($result,$result_arr);
            $parameters = $result_arr['payInfo'];
            $data['jsApiParameters'] = $parameters;
            $this->load->view ( 'pay/default/wxpay', $data );

        } else
            exit('参数错误，微信支付失败');
    }

    //快乐付分账拉起支付
    public function okpay_pay(){
        if($this->temp_pay_show()){
            return;
        }
        //统计探针
        $this->load->library('MYLOG');
        $inter_id = $this->input->get ( 'id', true );
        $out_trade_no = $this->input->get('oid');
        $this->db->where(array(
            'out_trade_no'=>$out_trade_no,
            'inter_id'=>$inter_id,
            'hotel_id'=>$this->input->get('hid')
        ));
        $this->db->limit(1);
        $order_details = $this->db->get('okpay_orders')->row_array();
        $parameters = '';
        $data = array ();
        if(!empty($order_details) && $order_details['pay_status'] == 3){//已经支付成功
            echo '订单已完成';
            die;
        }else if($order_details){
            $this->load->model('iwidepay/Iwidepay_model');
            $openid= $order_details['openid'];
            $this->load->model('pay/wxpay_model');
            $this->load->model('pay/Pay_model' );
            $this->load->model('wx/publics_model');
            $public = $this->publics_model->get_public_by_id($inter_id);
            if(!empty($public['app_id'])){
                //生成分账订单
                $split_order = array(
                    'inter_id' => $inter_id,
                    'hotel_id' => $order_details['hotel_id'],
                    'openid' => $openid,
                    'order_no' => $out_trade_no,
                    'pay_no' => $out_trade_no,
                    'order_status' => 1,
                    'orig_amount' => $order_details ['pay_money'] * 100,
                    'trans_amt' => $order_details ['pay_money'] * 100,
                    'order_date' => date('Ymd'),
                    'module' => 'okpay',
                    'pay_type' => 'weixin',
                    'transfer_status' => 0,
                    'add_time' => date('Y-m-d H:i:s'),
                    'update_time' => date('Y-m-d H:i:s'),
                    'is_dist' => 0,
                );
                MYLOG::w('分账订单入库数据：'.json_encode($split_order),'iwidepaypay');
                $res = $this->Iwidepay_model->save_iwidepay_order($split_order);
                if(!$res){
                    MYLOG::w('分账订单入库失败：'.json_encode($res),'iwidepaypay');
                    exit('程序错误，微信支付失败');
                }
                $identity = time();
                $data ['fail_url'] = 'http://'.$public['domain']. '/index.php/okpay/okpay/pay_error?id=' . $inter_id.'&t='.$identity.'&oid='. $this->input->get('oid');
                $data ['success_url'] = 'http://'.$public['domain']. '/index.php/okpay/okpay/pay_success?id=' . $inter_id . '&t='. $identity.'&oid='. $this->input->get('oid');
                $cmbc_chnl_id = $this->Iwidepay_model->get_cmbc_chnl_id($inter_id);
                $commodityName = '快乐付';
                $subMerName = !empty($order_details['hotel_name'])?$order_details['hotel_name']:$public['name'];
                $jsApiArr = array(
                    'subChnlMerNo' => $cmbc_chnl_id,
                    'commodityName' => $commodityName,
                    'openid' => $openid,
                    'orderDate' => date('Ymd'),
                    'orderNo' => $out_trade_no,
                    'productId' => '0105',
                    'requestNo' => time().rand(10000,99999),
                    'transAmt' => $order_details ['pay_money'] * 100,
                    'transId' => '10',
                    'subMerNo'=> IwidePayConfig::MERNO,
                    'subMerName'=> $this->do_string($subMerName,20),
                    // 'version' => 'V2.0',
                    'returnUrl' => site_url('okpay/okpay/pay_show'),
                    );
                MYLOG::w('支付下单参数：'.json_encode($jsApiArr),'iwidepaypay');
                $result = $this->IwidePayApi->unifyPayRequest($jsApiArr);
                MYLOG::w('支付参数：'.$result,'iwidepaypay');
                parse_str($result,$result_arr);
                $parameters = $result_arr['payInfo'];
            }
            $data ['jsApiParameters'] = $parameters;
            $this->load->view ( 'pay/default/wxpay', $data );
        }else{
            exit('参数错误');
        }
    }

    //新版會員支付方法
    public function vip_pay(){
        if($this->temp_pay_show()){
            return;
        }
        $orderId = $this->input->get('orderId')?(int)$this->input->get('orderId'):0;
        $token = $this->get_Member_Token();
        //获取验证的
        //获取订单的详细信息
        $post_order_info = INTER_PATH_URL.'depositorder/get_order_by_orderid';
        $post_order_data = array(
            // 'inter_id'=>$inter_id,
            // 'openid'=>$openid,
            'orderId'=>$orderId,
            'token'=>$token,
        );
        $order_info = $this->doCurlPostRequest( $post_order_info , $post_order_data );
        if(isset($order_info['err'])){
            echo 'empty order info';exit;
        }else{
            $order_info = $order_info['data'];
        }
        $inter_id = $order_info['inter_id'];
        $openid = $order_info['open_id'];
        //获取支付订单的详细信息
        if($orderId){
            $this->load->model('pay/wxpay_model');
            $this->load->model ('pay/Pay_model' );
            $this->load->model('wx/publics_model');
            $this->load->model('iwidepay/Iwidepay_model');
            $public = $this->publics_model->get_public_by_id($inter_id);

            //生成分账订单
            $split_order = array(
                'inter_id' => $inter_id,
                'hotel_id' => 0,
                'openid' => $openid,
                'order_no' => $orderId,
                'pay_no' => $order_info['order_num'],
                'order_status' => $order_info['pay_status'],
                'orig_amount' => $order_info ['pay_money'] * 100,
                'trans_amt' => $order_info['pay_money']* 100,
                'order_date' => date('Ymd'),
                'module' => 'vip',
                'pay_type' => 'weixin',
                'transfer_status' => 0,
                'add_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
                'is_dist' => 0,
                );
            MYLOG::w('分账订单入库数据：'.json_encode($split_order),'iwidepaypay');
            $res = $this->Iwidepay_model->save_iwidepay_order($split_order);
            if(!$res){
                MYLOG::w('分账订单入库失败：'.json_encode($res),'iwidepaypay');
                exit('程序错误，微信支付失败');
            }
            $cmbc_chnl_id = $this->Iwidepay_model->get_cmbc_chnl_id($inter_id);
            $commodityName = '订单号码：'.$order_info['order_num'].'会员充值';
            $subMerName = $public['name'];
            //组装请求支付参数
            $jsApiArr = array(
                'subChnlMerNo' => $cmbc_chnl_id,
                'commodityName' => $this->do_string($commodityName),
                'openid' => $openid,
                'orderDate' => date('Ymd'),
                'orderNo' => $order_info['order_num'],
                'productId' => '0105',
                'requestNo' => time().rand(10000,99999),
                'transAmt' => $order_info ['pay_money'] * 100,
                'transId' => '10',
                'subMerNo'=> IwidePayConfig::MERNO,
                'subMerName'=> $this->do_string($subMerName,20),
                // 'version' => 'V2.0',
                'returnUrl' => site_url('membervip/depositcard/nopay'),
                );
            MYLOG::w('支付下单参数：'.json_encode($jsApiArr),'iwidepaypay');
            $result = $this->IwidePayApi->unifyPayRequest($jsApiArr);
            MYLOG::w('支付参数：'.$result,'iwidepaypay');
            parse_str($result,$result_arr);
            $parameters = $result_arr['payInfo'];

            $pay_data = array(
                'orderId'=>$orderId,
                'interId'=>$inter_id,
                'orderNum'=>$order_info['order_num'],
                'orderMoney'=>$order_info['pay_money'],
                'salesId'=>$order_info['distribution_num'],
		'payfor'=>$this->input->get('payfor')?$this->input->get('payfor'):''
            );
            $pay_data = http_build_query($pay_data);
            $data ['fail_url'] = 'http://'.$public['domain'].'/index.php/membervip/depositcard/nopay?'.$pay_data;
            $data ['success_url'] = 'http://'.$public['domain'].'/index.php/membervip/depositcard/okpay?'.$pay_data;
            $data ['jsApiParameters'] = $parameters;
            $this->load->view ('pay/default/wxpay', $data );
        }else{
            echo json_encode(array('err'=>40003,'msg'=>'参数错误'));
        }
    }


      //快乐送，门票拉起分账拉起支付
    public function dc_pay(){
        if($this->temp_pay_show()){
            return;
        }
        //统计探针
        $this->load->library('MYLOG');
        $inter_id = $this->input->get ( 'id', true );
        $order_id = $this->input->get('order_id');
        if(empty($inter_id) || empty($order_id)){
            echo 'data empty';
            die;
        }
        $where = array('inter_id'=>$inter_id,'order_id'=>$order_id,'pay_status'=>2,'pay_way'=>1);//未支付是2
        $this->load->model('roomservice/roomservice_shop_model');
        $this->load->model('roomservice/roomservice_orders_model');
        $order_details = $this->roomservice_orders_model->get_one($where);
        $parameters = '';
        $data = array ();
        if(empty($order_details)){
            exit('参数有误');
            die;
        }
        $this->load->model('iwidepay/Iwidepay_model');
        $openid= $order_details['openid'];
        $this->load->model('pay/wxpay_model');
        $this->load->model('pay/Pay_model' );
        $this->load->model('wx/publics_model');
        $public = $this->publics_model->get_public_by_id($inter_id);
        if(!empty($public['app_id'])){
            //生成分账订单
            $split_order = array(
                'inter_id' => $inter_id,
                'hotel_id' => $order_details['hotel_id'],
                'openid' => $openid,
                'order_no' => $order_details['order_sn'],
                'pay_no' => $order_details['order_sn'],
                'order_status' => 2,//支付状态
                'orig_amount' => $order_details ['sub_total'] * 100,
                'trans_amt' => $order_details ['sub_total'] * 100,
                'order_date' => date('Ymd'),
                'module' => 'dc',
                'pay_type' => 'weixin',
                'transfer_status' => 0,
                'add_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
                'is_dist' => 0,
            );
            MYLOG::w('分账订单入库数据：'.json_encode($split_order),'iwidepaypay');
            $res = $this->Iwidepay_model->save_iwidepay_order($split_order);
            if(!$res){
                MYLOG::w('分账订单入库失败：'.json_encode($res),'iwidepaypay');
                exit('程序错误，微信支付失败');
            }
            $identity = time();
            if($order_details['type'] == 4){//门票
                $data ['fail_url'] = 'http://'.$public['domain']. '/index.php/ticket/ticket/order_detail?id=' . $inter_id.'&t='.$identity.'&oid='. $order_id;
                $data['success_url'] = $data['fail_url'];
            }else{
                $data ['fail_url'] = 'http://'.$public['domain']. '/index.php/roomservice/roomservice/order_detail?id=' . $inter_id.'&t='.$identity.'&oid='. $order_id;
                $data['success_url'] = $data['fail_url'];
            }
            //获取店铺名称
            $shop_where = array('shop_id'=>$order_details['shop_id'],'inter_id'=>$inter_id);
            $shop = $this->roomservice_shop_model->get($shop_where);
            $order_desc= $shop['shop_name'];
            $cmbc_chnl_id = $this->Iwidepay_model->get_cmbc_chnl_id($inter_id);
            $hotel_info = $this->Iwidepay_model->get_hotel_info($order_details['hotel_id']);
            $commodityName = $this->do_string($order_desc);
            $subMerName = !empty($hotel_info['name'])?$hotel_info['name']:$public['name'];
            $jsApiArr = array(
                'subChnlMerNo' => $cmbc_chnl_id,
                'commodityName' => $commodityName,
                'openid' => $openid,
                'orderDate' => date('Ymd'),
                'orderNo' => $order_details['order_sn'],
                'productId' => '0105',
                'requestNo' => time().rand(10000,99999),
                'transAmt' => $order_details ['sub_total'] * 100,
                'transId' => '10',
                'subMerNo'=> IwidePayConfig::MERNO,
                'subMerName'=> $this->do_string($subMerName,20),
                // 'version' => 'V2.0',
                'returnUrl' => site_url('roomservice/roomservice/order_detail'),
                );
            MYLOG::w('支付下单参数：'.json_encode($jsApiArr),'iwidepaypay');
            $result = $this->IwidePayApi->unifyPayRequest($jsApiArr);
            MYLOG::w('支付参数：'.$result,'iwidepaypay');
            parse_str($result,$result_arr);
            $parameters = $result_arr['payInfo'];
        }
        $data ['jsApiParameters'] = $parameters;
        $this->load->view ( 'pay/default/wxpay', $data );
    
    }

    //预约核销拉起分账拉起支付 BY 沙沙 Date:2017-08-16
    public function ticket_pay()
    {
        if($this->temp_pay_show()){
            return;
        }
        //统计探针
        $this->load->library('MYLOG');
        $inter_id = $this->input->get ( 'id', true );
        $order_id = $this->input->get('order_id');
        if(empty($inter_id) || empty($order_id)){
            echo 'data empty';
            die;
        }

        $this->load->model('roomservice/roomservice_shop_model');
        $this->load->model('roomservice/roomservice_orders_model');

        $this->load->model('ticket/ticket_orders_merge_model');
        $order_detail= $this->ticket_orders_merge_model->order_info(array('inter_id'=>$inter_id,'merge_orderId'=>$order_id,'pay_way' => 1));

        if (!empty($order_detail) && $order_detail['pay_status'] > 0)
        {
            die('已成功支付，请不要重复支付订单');
        }

        $parameters = '';
        $data = array ();
        if(empty($order_detail)){
            exit('参数有误');
            die;
        }
        $this->load->model('iwidepay/Iwidepay_model');
        $openid= $order_detail['openid'];
        $this->load->model('pay/wxpay_model');
        $this->load->model('pay/Pay_model' );
        $this->load->model('wx/publics_model');
        $public = $this->publics_model->get_public_by_id($inter_id);
        if(!empty($public['app_id']))
        {
            //查询核销子单订单遍历生成
            $filter_arr = array(
                'merge_order_no' => $order_detail['order_no'],
            );
            $orders = $this->roomservice_orders_model->get_orders($filter_arr,'','*','w');
            if (!empty($orders))
            {
                $this->db->trans_begin(); //开启事务
                $num = count($orders);
                $countNum = 0;
                foreach ($orders as $value)
                {
                    //生成分账订单
                    $split_order = array(
                        'inter_id' => $inter_id,
                        'hotel_id' => $value['hotel_id'],
                        'openid' => $openid,
                        'order_no' => $value['order_sn'],
                        'pay_no' => $order_detail['order_no'],
                        'order_no_main' => $order_detail['order_no'],
                        'order_status' => 2,//支付状态
                        'orig_amount' => $value ['sub_total'] * 100,
                        'trans_amt' => $value ['sub_total'] * 100,
                        'order_date' => date('Ymd'),
                        'module' => 'ticket',//预约核销
                        'pay_type' => 'weixin',
                        'transfer_status' => 0,
                        'add_time' => date('Y-m-d H:i:s'),
                        'update_time' => date('Y-m-d H:i:s'),
                        'is_dist' => 0,
                    );
                    MYLOG::w('分账订单入库数据：'.json_encode($split_order),'iwidepaypay');
                    $res = $this->Iwidepay_model->save_iwidepay_order($split_order);
                    if ($res > 0)
                    {
                        $countNum++;
                    }
                }

                if($num != $countNum)
                {
                    //回滚
                    $this->db->trans_rollback();

                    MYLOG::w('分账订单入库失败：'.json_encode($orders),'iwidepaypay');
                    exit('程序错误，微信支付失败');
                }
            }
            //回滚
            else
            {
                die('参数有误');
            }

            //提交事务
            $this->db->trans_complete();

            $identity = time();

            $data ['fail_url'] = 'http://'.$public['domain']. '/index.php/ticket/book/order_detail?id=' .$inter_id.'&hotel_id='.$order_detail['hotel_id'].'&shop_id='.$order_detail['shop_id'].'&t='.$identity.'&order_id='. $order_id;
            $data['success_url'] = $data['fail_url'];

            //获取店铺名称
            $shop_where = array('shop_id'=>$order_detail['shop_id'],'inter_id'=>$inter_id);
            $shop = $this->roomservice_shop_model->get($shop_where);
            $order_desc= $shop['shop_name'];
            $cmbc_chnl_id = $this->Iwidepay_model->get_cmbc_chnl_id($inter_id);
            $hotel_info = $this->Iwidepay_model->get_hotel_info($order_detail['hotel_id']);
            $commodityName = $this->do_string($order_desc);
            $subMerName = !empty($hotel_info['name'])?$hotel_info['name']:$public['name'];
            $jsApiArr = array(
                'subChnlMerNo' => $cmbc_chnl_id,
                'commodityName' => $commodityName,
                'openid' => $openid,
                'orderDate' => date('Ymd'),
                'orderNo' => $order_detail['order_no'],
                'productId' => '0105',
                'requestNo' => time().rand(10000,99999),
                'transAmt' => $order_detail ['pay_fee'] * 100,
                'transId' => '10',
                'subMerNo'=> IwidePayConfig::MERNO,
                'subMerName'=> $this->do_string($subMerName,20),
                // 'version' => 'V2.0',
                'returnUrl' => 'http://'.$public['domain']. '/index.php/wxpayreturn/roomservice_rtn/'.$inter_id,
            );
            MYLOG::w('支付下单参数：'.json_encode($jsApiArr),'iwidepaypay');
            $result = $this->IwidePayApi->unifyPayRequest($jsApiArr);
            MYLOG::w('支付参数：'.$result,'iwidepaypay');
            parse_str($result,$result_arr);
            $parameters = $result_arr['payInfo'];
        }
        $data ['jsApiParameters'] = $parameters;
        $this->load->view ( 'pay/default/wxpay', $data );
    }

    /**
     * [sub_string 字符串特殊字符替换，长度截取函数]
     * @param  [type] $str [待处理的字符串]
     * @param  [type] $length [截取的长度]
     * @return [type]      [description]
     */
    protected function do_string($str,$length=32){
        $str_arr = array('%','&','+');
        foreach ($str_arr as $key => $value) {
            $str = str_replace($value,'',$str);
        }
        return mb_substr($str, 0,$length,'utf-8');
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 5) {
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
        $this->api_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function api_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'membervip'. DS;
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

    //获取授权token
    protected function get_Member_Token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        return isset($token_info['data'])?$token_info['data']:"";
    }

    public function success(){
        echo 'pay successfully';
    }
}