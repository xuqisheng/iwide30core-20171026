<?php
class Okpay_wxpay_model extends MY_Model{
	
	protected $real_path			= "";
	protected $api_client_cert_pem	= "apiclient_cert_"; //公众号证书 key
	protected $api_client_key_pem 	= "apiclient_key_"; //公众号证书密钥
	protected $api_rootca_pem		= "rootca_"; //ca证书
	
	protected $api_app_id			= ""; //公众号id
	protected $api_app_secret		= ""; //公众号密钥
	
	protected $api_mch_id			= ""; //商户id	
	protected $api_mch_key			= ""; //商户密钥
	
	function __construct() {
		parent::__construct();
	}
	
	
	function init_wxpay($inter_id){
		$this->real_path = realpath("../")."/certs/";
		
		//读取公众号资料
		$this->load->model ('wx/Publics_model' );
		$wx = $this->Publics_model->get_public_by_id($inter_id);
		if(!empty($wx)){
			
			$this->api_app_id 		= $wx['app_id'];
			$this->api_app_secret	= $wx['app_secret'];
		}
		
		//读取商户资料
		$this->load->model ('pay/Pay_model');
		$mch = $this->Pay_model->get_pay_paras($inter_id);
		if(!empty($mch)){
			$this->api_mch_id			= $mch['mch_id'];
			$this->api_mch_key			= $mch['key'];
				
			$this->api_client_key_pem	= $this->real_path.$this->api_client_key_pem.$this->api_mch_id.".pem";
			$this->api_client_cert_pem	= $this->real_path.$this->api_client_cert_pem.$this->api_mch_id.".pem";
			$this->api_rootca_pem		= $this->real_path.$this->api_rootca_pem.$this->api_mch_id.".pem";
		}
	}
	
	/**
	 * 
	 * @param  $transaction_id 交易号id
	 * @param  $out_refund_no  商户订单号
	 * @param  $total_fee  总金额
	 * @param  $refund_fee 退款金额
	 * @param  $op_user_id 操作员 ，默认商户号
	 * @param  $our_refund_no 退款单号
	 */
	function refund($inter_id,$transaction_id,$total_fee,$refund_fee,$our_refund_no){
		$this->init_wxpay($inter_id);
		
		//global $application_folder;
		//require_once $application_folder . '/libraries/WxPaySDK/lib/WxPay.Api.php';
		$this->load->library("WxPaySDK/WxPayApi");
		
		//log_message("info", "");
		//NATIVE
		
		$refundOrder = new WxPayRefund();
		
		$refundOrder->SetTransaction_id($transaction_id);
		$refundOrder->SetTotal_fee($total_fee);
		$refundOrder->SetRefund_fee($refund_fee);
		$refundOrder->SetOut_refund_no($our_refund_no);
		
		$refundOrder->SetOp_user_id($this->api_mch_id);
		
		$refundOrder->SetAppid($this->api_app_id);
		$refundOrder->SetMch_id($this->api_mch_id);
		
		//执行请求
		$refundResult = WxPayApi::refund($refundOrder,$this->api_client_key_pem,$this->api_client_cert_pem,$this->api_mch_key);

		log_message("error","___1___return info::".json_encode($refundResult));
		//商户根据实际情况设置相应的处理流程
		if ($refundResult["return_code"] == "FAIL")
		{
			//商户自行增加处理流程
			//log_message("info","___1___return info::".json_encode($refundResult));
            return $refundResult;//错误
		}
		elseif($refundResult["result_code"] == "FAIL")
		{
			//log_message("info","___2___return info:".json_encode($refundResult));
            return $refundResult;//错误return_msg
		}
		elseif($refundResult["return_code"] == "SUCCESS")
		{
			//log_message("info","___3___return info:".json_encode($refundResult));
			return $refundResult;
			
		}else{
			//log_message("info","___4___return info:".json_encode($refundResult));
			return false;
		}
		
	}
    //查询订单
    function query_order($inter_id,$transaction_id){
        $this->init_wxpay($inter_id);

        //global $application_folder;
        //require_once $application_folder . '/libraries/WxPaySDK/lib/WxPay.Api.php';
        $this->load->library("WxPaySDK/WxPayApi");

        //log_message("info", "");
        //NATIVE

        $queryOrder = new WxPayOrderQuery();

        $queryOrder->SetTransaction_id($transaction_id);

        $queryOrder->SetOp_user_id($this->api_mch_id);

        $queryOrder->SetAppid($this->api_app_id);
        $queryOrder->SetMch_id($this->api_mch_id);

        //执行请求
        $result = WxPayApi::orderQuery($queryOrder);
        if($result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            //支付成功
            if($result["trade_state"] == "SUCCESS"){
                $succCode = 1;
                return $result;
            }
            //用户支付中
            else if($result["trade_state"] == "USERPAYING"){
                $succCode = 2;
                return false;
            }
        }

        //如果返回错误码为“此交易订单号不存在”则直接认定失败
        if($result["err_code"] == "ORDERNOTEXIST")
        {
            $succCode = 0;
        } else{
            //如果是系统错误，则后续继续
            $succCode = 2;
        }
        return false;
    }

    //撤销订单
    function cancel_order($inter_id,$out_trade_no){
        $this->init_wxpay($inter_id);

        //global $application_folder;
        //require_once $application_folder . '/libraries/WxPaySDK/lib/WxPay.Api.php';
        $this->load->library("WxPaySDK/WxPayApi");

        //log_message("info", "");
        //NATIVE

        $refundOrder = new WxPayReverse();

        $refundOrder->SetOut_trade_no($out_trade_no);

        //$refundOrder->SetOp_user_id($this->api_mch_id);

        $refundOrder->SetAppid($this->api_app_id);
        $refundOrder->SetMch_id($this->api_mch_id);

        //执行请求
        $refundResult = WxPayApi::reverse($refundOrder,$this->api_client_key_pem,$this->api_client_cert_pem,$this->api_mch_key);
        //商户根据实际情况设置相应的处理流程
        if ($refundResult["return_code"] == "FAIL")
        {
            //商户自行增加处理流程
            log_message("error","___1___cancel::".json_encode($refundResult));
            return false;
        }
        elseif($refundResult["result_code"] == "FAIL")
        {
            log_message("error","___2___cancel:".json_encode($refundResult));
            return false;
        }
        elseif($refundResult["return_code"] == "SUCCESS")
        {
            log_message("error","___3___return info:".json_encode($refundResult));
            return $refundResult;

        }else{
            log_message("error","___4___cancel:".json_encode($refundResult));
            return false;
        }

    }

    //威富通退款
    public function weifutong_refund($inter_id,$transaction_id,$total_fee,$refund_fee,$out_refund_no,$hotel_id){
        $this->load->library('WftPay/Utils');
        $this->load->library('WftPay/RequestHandler',null,'RequestHandler');
        $this->load->library('WftPay/PayHttpClient',null,'PayHttpClient');
        $this->load->library('WftPay/ClientResponseHandler',null,'ClientResponseHandler');

        //获取配置
        $this->load->model ('pay/Pay_model');
        $pay_paras = $this->Pay_model->get_pay_paras ( $inter_id, 'weifutong' );
        $this->RequestHandler->setParameter('service','trade.single.refund');
        //必填项，商户号，由威富通分配
        $mch_id = '';
        $inter_ids = array(
            'a479457264',//厦门海旅温德姆至尊酒店
            'a482210445',//厦门帝元维多利亚大酒店
            'a489326393',//都江堰紫坪铺滑翔伞飞行营地
            'a494820079',//成都群光君悦酒店
            'a496652649',//株洲万豪
            'a497580480',// 苏州吴宫泛太平洋酒店
        );
        $news = 1;//是否是新号
        if(in_array($inter_id,$inter_ids)){
            $news = 0;//不是新号
        }
        if($news && isset($pay_paras['sub_ok_mch_id_h_'.$hotel_id]) && !empty($pay_paras['sub_ok_mch_id_h_'.$hotel_id])){
            $this->RequestHandler->setParameter("mch_id",$pay_paras['sub_ok_mch_id_h_'.$hotel_id]);
            $mch_id = $pay_paras['sub_ok_mch_id_h_'.$hotel_id];
        } elseif(isset($pay_paras['sub_mch_id_h_'.$hotel_id]) && !empty($pay_paras['sub_mch_id_h_'.$hotel_id])){
            $this->RequestHandler->setParameter("mch_id",$pay_paras['sub_mch_id_h_'.$hotel_id]);
            $mch_id = $pay_paras['sub_mch_id_h_'.$hotel_id];
        }else{
            $this->RequestHandler->setParameter ( "mch_id", $pay_paras ['mch_id'] );
            $mch_id =$pay_paras ['mch_id'];
        }

        if($news && isset($pay_paras['sub_ok_key_h_'.$hotel_id]) && !empty($pay_paras['sub_ok_key_h_'.$hotel_id]))
            $this->RequestHandler->setKey($pay_paras ['sub_ok_key_h_'.$hotel_id]);
        elseif(isset($pay_paras['sub_key_h_'.$hotel_id]) && !empty($pay_paras['sub_key_h_'.$hotel_id])){
            $this->RequestHandler->setKey($pay_paras ['sub_key_h_'.$hotel_id]);
        }else{
            $this->RequestHandler->setKey($pay_paras ['key']);
        }

       // $this->RequestHandler->setParameter('version','2.0');
        $this->RequestHandler->setParameter('transaction_id',$transaction_id);
        $this->RequestHandler->setParameter('out_refund_no',$out_refund_no);
        $this->RequestHandler->setParameter('total_fee',$total_fee);
        $this->RequestHandler->setParameter('refund_fee',$refund_fee);
        $this->RequestHandler->setParameter('op_user_id',$mch_id);
        $this->RequestHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位

        $this->RequestHandler->createSign();//创建签名
        $data = Utils::toXml($this->RequestHandler->getAllParameters());
        log_message("error","weifutong_0___return info::|".json_encode($this->RequestHandler->getAllParameters()));
        $this->PayHttpClient->setReqContent($this->RequestHandler->getGateURL(),$data);
        //$this->load->library('MYLOG');
        //MYLOG::w(json_encode($this->RequestHandler->getAllParameters()),'wftpay_q');

        if($this->PayHttpClient->call()){
            $this->ClientResponseHandler->setContent($this->PayHttpClient->getResContent());
            $this->ClientResponseHandler->setKey($this->RequestHandler->getKey());
            if($this->ClientResponseHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码，其它结果请查看接口文档
                if($this->ClientResponseHandler->getParameter('status') == 0 && $this->ClientResponseHandler->getParameter('result_code') == 0){
                    $res = array('transaction_id'=>$this->ClientResponseHandler->getParameter('transaction_id'),
                        'out_trade_no'=>$this->ClientResponseHandler->getParameter('out_trade_no'),
                        'out_refund_no'=>$this->ClientResponseHandler->getParameter('out_refund_no'),
                        'refund_id'=>$this->ClientResponseHandler->getParameter('refund_id'),
                        'refund_channel'=>$this->ClientResponseHandler->getParameter('refund_channel'),
                        'refund_fee'=>$this->ClientResponseHandler->getParameter('refund_fee'),
                        'coupon_refund_fee'=>$this->ClientResponseHandler->getParameter('coupon_refund_fee'));
                    log_message("error","weifutong_succ__info::|".json_encode($this->ClientResponseHandler->getAllParameters()));
                    return $res;
                }else{
                    // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('err_code').' Error Message:'.$this->ClientResponseHandler->getParameter('err_msg')));
                    //echo $this->ClientResponseHandler->getParameter('err_msg');
                   // exit();
                    log_message("error","weifutong_1___return info::|".$this->ClientResponseHandler->getParameter('err_msg'));
                    return false;
                }
            }
            // echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->ClientResponseHandler->getParameter('status').' Error Message:'.$this->ClientResponseHandler->getParameter('message')));
            //echo $this->ClientResponseHandler->getParameter('message');
            log_message("error","weifutong__2__return info::|".json_encode($this->RequestHandler->getAllParameters()));
            return false;
        }else{
            // echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
            //echo $this->PayHttpClient->getErrInfo();
            log_message("error","weifutong__3__return info::|". $this->PayHttpClient->getErrInfo());
            return false;
        }

    }
	
	
}