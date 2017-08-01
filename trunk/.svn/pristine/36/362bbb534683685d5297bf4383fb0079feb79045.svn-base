<?php

/**
 * @author OuNianfeng
 * @since 2016-01-26 23:04
 * @todo 公众号企业付款模型类
 */
class Company_pay_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	 
	/**
	 * @todo 取公众号支付参数
	 * @param string 公众号唯一识别码
	 * @param string 酒店ID号
	 * @return Query result 支付参数集
	 */
	function get_account_confg($inter_id,$hotel_id = NULL){
		$where = array('inter_id'=>$inter_id);
		if(!empty($hotel_id)){
			$where['hotel_id'] = $hotel_id;
		}
		$this->db->where($where);
		$query  = $this->db->get('pay_params')->result();
		$result = array ();
		foreach ( $query as $item ) {
			$result [$item->param_name] = $item->param_value;
		}
		return $result;
	}
	
	/**
	 * 分销业绩企业支付发放
	 * @param string openid
	 * @param int 发放金额（单位：分）
	 * @param string 公众号识别码
	 * @param string 绩效ID
	 * @param int 分销号
	 * @param string 发放描述
	 * @return string[]|unknown[]|Query[]|string[]
	 */
	function company_pay($openid,$amount,$inter_id,$grade_id=null,$saler=null,$desc=null,$batch_no = '',$pay_id = '',$source=1){
		$mch_info = array ();
		$rel_openid = $openid;
		if (empty ( $pay_id ))
			$mch_info = $this->get_mch_trans_params ( $inter_id );
		else {
			$mch_info = $this->get_mch_trans_params ( $pay_id );
			$this->load->model ( 'distribute/openid_rel_model' );
			$rel_obj = $this->openid_rel_model->get_openid_relationship ( $inter_id, $openid, $pay_id );
			if (isset ( $rel_obj->m_openid ) && ! empty ( $rel_obj->m_openid )) {
				$rel_openid = $rel_obj->m_openid;
			}else 
				return array ( 'errmsg' => 'faild','return_msg'=>'MISSING_RELATIONSHIP_OPENID' );
		}
		if ($mch_info) {
			if (! isset ( $mch_info ['app_id'] ) && ! isset ( $mch_info ['pay_app_id'] )) {
				$this->load->model ( 'wx/publics_model' );
				$pid = empty ( $pay_id ) ? $inter_id : $pay_id;
				$publics_info = $this->publics_model->get_public_by_id ( $pid, 'inter_id' );
				if (isset ( $publics_info ['app_id'] )){
					$mch_info ['app_id'] = $publics_info ['app_id'];
					$mch_info ['mch_appid'] = $publics_info ['app_id'];
				}
			}
			$this->load->helper ( 'common' );
			//唯一单号 situguanchen 20170206
			$tmp_data = $this->session->userdata('trade_time');
			$tmp_trade_no = substr ( time (), 3 ) . mt_rand ( 100, 999 );
			if(!empty($tmp_data) && $tmp_data == $tmp_trade_no){//相同则sleep 1秒
				sleep(1);
				$tmp_trade_no = substr ( time (), 3 ) . mt_rand ( 100, 999 );
			}
			//生成的数据放进会话中
			$this->session->set_userdata(array('trade_time'=>$tmp_trade_no));
			$partner_trade_no = '202' . date ( 'Ymd' ) . $tmp_trade_no ;
			$this->load->model ( 'pay/wxpay_model' );
			if(isset($mch_info ['pay_mch_id']))
				$mch_info ['mch_id'] = $mch_info ['pay_mch_id'];
			if(isset($mch_info ['pay_key']))
				$mch_info ['key'] = $mch_info ['pay_key'];
			$arr = array (
					'mch_appid'        => $mch_info ['app_id'],
					'mchid'            => $mch_info ['mch_id'],
					'nonce_str'        => createNoncestr (),
					'partner_trade_no' => $partner_trade_no,
					'openid'           => $rel_openid,
					'check_name'       => 'NO_CHECK',
					'amount'           => $amount,
					'spbill_create_ip' => $_SERVER ["REMOTE_ADDR"],
					'desc'             => empty ( $desc ) ? '企业付款' : $desc 
			);
			if (isset ( $mch_info ['pay_app_id'] ) && $mch_info ['pay_app_id'] != $mch_info ['app_id']) {
				$arr ['mch_appid'] = $mch_info ['pay_app_id'];
				$arr ['mch_id']    = $mch_info ['pay_mch_id'];
				unset ( $arr ['openid'] );
				$arr ['sub_openid'] = $rel_openid;
				$mch_info ['app_id']= $arr ['mch_appid'];
			}
			$arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ( 'key'    => $mch_info ['key'], 'app_id' => $arr ['mch_appid'] ) );
			$extras = array ();
			$extras ['CURLOPT_CAINFO']  = realpath ( '../certs' ) . DS . "rootca_" . $mch_info ['mch_id'] . ".pem";
			$extras ['CURLOPT_SSLCERT'] = realpath ( '../certs' ) . DS . "apiclient_cert_" . $mch_info ['mch_id'] . '.pem';
			$extras ['CURLOPT_SSLKEY']  = realpath ( '../certs' ) . DS . "apiclient_key_" . $mch_info ['mch_id'] . '.pem';
			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
			$send_content = $this->wxpay_model->arrayToXml ( $arr );
			
			$record = array ();
			$record ['send_amount']      = $amount;
			$record ['saler']            = $saler;
			$record ['grade_id']         = $grade_id;
			$record ['openid']           = $openid;
			$record ['inter_id']         = $inter_id;
			$record ['send_time']        = date ( 'Y-m-d H:i:s' );
			$record ['send_content']     = $send_content . '&extras=' . $this->wxpay_model->arrayToXml ( $extras );
			$record ['partner_trade_no'] = $arr ['partner_trade_no'];
			
			if(!empty($pay_id)){
				//第三方企业付款时记录第三方支付账号信息
				$record['send_by']       = 2;
				$record['send_mch_id']   = $mch_info ['mch_id'];
				$record['send_inter_id'] = $pay_id;
				$record['rel_openid']    = $rel_openid;
			}
			
			if (! empty ( $batch_no )) {
				$record ['batch_no'] = $batch_no;
			}
			$record ['source'] = $source;
			//添加唯一索引字段标志 situguanchen 2017-03-10
			$identifier = md5($record['batch_no'].$record['inter_id'].$record['saler'].$record['grade_id']);
			$record['identifier'] = $identifier;
			$this->db->insert ( 'distribute_send_record', $record );
			$rid    = $this->db->insert_id ();
			if(empty($rid)){//插入失败
				$this->load->library('MYLOG');
				MYLOG::w('插入重复数据:'.json_encode($record), 'auto_distribute');
				return array ( 'errmsg' => 'duplicate', 'return_msg' => 'duplicate');
			}
			$result = doCurlPostRequest ( $url, $send_content, $extras );
			$update = array ();
			$update ['receive_content'] = $result;
			
			$data = json_decode ( json_encode ( simplexml_load_string ( $result, NULL, LIBXML_NOCDATA ) ), true );
			if ($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'SUCCESS') {
				$update ['status'] = 1;
				$update ['remark'] = $rid . '_' . json_encode($data ['return_msg']);
				$this->db->where ( array ( 'id' => $rid, 'inter_id' => $inter_id  ) );
				$this->db->update ( 'distribute_send_record', $update );
				return array ( 'errmsg' => 'ok', 'partner_trade_no' => $arr ['partner_trade_no'], 'rid' => $rid );
			} else {
				//添加余额不足判断 situguanchen 2017-03-17
				if($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'FAIL' && isset($data['err_code']) && $data['err_code']=='NOTENOUGH'){
					$this->db->where ( array ( 'id' => $rid, 'inter_id' => $inter_id ) );
					$update['status'] = 2;//失败
					$update['remark'] = isset($data['return_msg'])?$data['return_msg'] . ':余额不足':'余额不足';
					$this->db->update ( 'distribute_send_record', $update );
					return array('errmsg'=>'notenough','return_msg' => 'notenough', 'rid' => $rid);
				}
				$arr ['rid']                = $rid;
				
				$this->db->where ( array ( 'id' => $rid, 'inter_id' => $inter_id ) );
				$this->db->update ( 'distribute_send_record', $update );
				$check_res = $this->check_pay_result ( $inter_id, $data, $arr, $mch_info );
				if($check_res['errmsg'] == 'ok'){
					return array ( 'errmsg' => 'ok', 'partner_trade_no' => $arr ['partner_trade_no'], 'rid' => $rid );
				}else if($check_res['errmsg'] == 'faild'){
					return array ( 'errmsg' => 'faild', 'return_msg' => '发放校验结果：'.$check_res['ck_res'], 'rid' => $rid );
				}else{//异常发放记录
					return array ( 'errmsg' => 'error', 'return_msg' => '发放校验结果：'.$check_res['ck_res'], 'rid' => $rid );
				}
			}
			$ret_msg = '发放异常';
			if (isset ( $data ['return_msg'] )) {
				$ret_msg = $data ['return_msg'];
			}
			return array ( 'errmsg' => 'faild', 'return_msg' => $ret_msg, 'rid' => $rid );
		} else {
			return array ( 'errmsg' => 'faild', 'return_msg' => 'PARAM_ERROR' );
		}
	}

    /**
     * 分销业绩企业支付发放(会员调用，没有发放记录，需要自己写)
     * @param string openid
     * @param int 发放金额（单位：分）
     * @param string 公众号识别码
     * @param string 绩效ID
     * @param int 分销号
     * @param string 发放描述
     * @return string[]|unknown[]|Query[]|string[]
     */
    function member_company_pay($openid,$amount,$inter_id,$grade_id=null,$saler=null,$desc=null,$batch_no = '',$pay_id = '',$source=1){
        $mch_info = array ();
        $rel_openid = $openid;
        if (empty ( $pay_id ))
            $mch_info = $this->get_mch_trans_params ( $inter_id );
        else {
            $mch_info = $this->get_mch_trans_params ( $pay_id );
            $this->load->model ( 'distribute/openid_rel_model' );
            $rel_obj = $this->openid_rel_model->get_openid_relationship ( $inter_id, $openid, $pay_id );
            if (isset ( $rel_obj->m_openid ) && ! empty ( $rel_obj->m_openid )) {
                $rel_openid = $rel_obj->m_openid;
            }else
                return array ( 'errmsg' => 'faild','return_msg'=>'MISSING_RELATIONSHIP_OPENID' );
        }
        if ($mch_info) {
            if (! isset ( $mch_info ['app_id'] ) && ! isset ( $mch_info ['pay_app_id'] )) {
                $this->load->model ( 'wx/publics_model' );
                $pid = empty ( $pay_id ) ? $inter_id : $pay_id;
                $publics_info = $this->publics_model->get_public_by_id ( $pid, 'inter_id' );
                if (isset ( $publics_info ['app_id'] )){
                    $mch_info ['app_id'] = $publics_info ['app_id'];
                    $mch_info ['mch_appid'] = $publics_info ['app_id'];
                }
            }
            $this->load->helper ( 'common' );
            //唯一单号 situguanchen 20170206
            $tmp_data = $this->session->userdata('trade_time');
            $tmp_trade_no = substr ( time (), 3 ) . mt_rand ( 100, 999 );
            if(!empty($tmp_data) && $tmp_data == $tmp_trade_no){//相同则sleep 1秒
                sleep(1);
                $tmp_trade_no = substr ( time (), 3 ) . mt_rand ( 100, 999 );
            }
            //生成的数据放进会话中
            $this->session->set_userdata(array('trade_time'=>$tmp_trade_no));
            $partner_trade_no = '202' . date ( 'Ymd' ) . $tmp_trade_no ;
            $this->load->model ( 'pay/wxpay_model' );
            if(isset($mch_info ['pay_mch_id']))
                $mch_info ['mch_id'] = $mch_info ['pay_mch_id'];
            if(isset($mch_info ['pay_key']))
                $mch_info ['key'] = $mch_info ['pay_key'];
            $arr = array (
                'mch_appid'        => $mch_info ['app_id'],
                'mchid'            => $mch_info ['mch_id'],
                'nonce_str'        => createNoncestr (),
                'partner_trade_no' => $partner_trade_no,
                'openid'           => $rel_openid,
                'check_name'       => 'NO_CHECK',
                'amount'           => $amount,
                'spbill_create_ip' => $_SERVER ["REMOTE_ADDR"],
                'desc'             => empty ( $desc ) ? '企业付款' : $desc
            );
            if (isset ( $mch_info ['pay_app_id'] ) && $mch_info ['pay_app_id'] != $mch_info ['app_id']) {
                $arr ['mch_appid'] = $mch_info ['pay_app_id'];
                $arr ['mch_id']    = $mch_info ['pay_mch_id'];
                unset ( $arr ['openid'] );
                $arr ['sub_openid'] = $rel_openid;
                $mch_info ['app_id']= $arr ['mch_appid'];
            }
            $arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ( 'key'    => $mch_info ['key'], 'app_id' => $arr ['mch_appid'] ) );
            $extras = array ();
            $extras ['CURLOPT_CAINFO']  = realpath ( '../certs' ) . DS . "rootca_" . $mch_info ['mch_id'] . ".pem";
            $extras ['CURLOPT_SSLCERT'] = realpath ( '../certs' ) . DS . "apiclient_cert_" . $mch_info ['mch_id'] . '.pem';
            $extras ['CURLOPT_SSLKEY']  = realpath ( '../certs' ) . DS . "apiclient_key_" . $mch_info ['mch_id'] . '.pem';
            $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
            $send_content = $this->wxpay_model->arrayToXml ( $arr );

            MYLOG::w(@json_encode(array('type'=>"Send to Wxpay",'url'=>$url,'send_content'=>$send_content,'extra'=>$extras)),'membervip/debug-log','wxpay');
            $result = doCurlPostRequest ( $url, $send_content, $extras );
            $update = array ();
            $update ['receive_content'] = $result;

            $data = json_decode ( json_encode ( simplexml_load_string ( $result, NULL, LIBXML_NOCDATA ) ), true );
            if ($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'SUCCESS') {
                return array ( 'errmsg' => 'ok',
//                    'partner_trade_no' => $arr ['partner_trade_no'], 'rid' => $rid
                );
            } else {
                //添加余额不足判断 situguanchen 2017-03-17
                if($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'FAIL' && isset($data['err_code']) && $data['err_code']=='NOTENOUGH'){
                    return array('errmsg'=>'notenough','return_msg' => 'notenough');
                }

                $check_res = $this->check_pay_result ( $inter_id, $data, $arr, $mch_info );
                if($check_res['errmsg'] == 'ok'){
                    return array ( 'errmsg' => 'ok', 'partner_trade_no' => $arr ['partner_trade_no'] );
                }else if($check_res['errmsg'] == 'faild'){
                    return array ( 'errmsg' => 'faild', 'return_msg' => '发放校验结果：'.$check_res['ck_res']);
                }else{//异常发放记录
                    return array ( 'errmsg' => 'error', 'return_msg' => '发放校验结果：'.$check_res['ck_res']);
                }
            }
            $ret_msg = '发放异常';
            if (isset ( $data ['return_msg'] )) {
                $ret_msg = $data ['return_msg'];
            }
            return array ( 'errmsg' => 'faild', 'return_msg' => $ret_msg );
        } else {
            return array ( 'errmsg' => 'faild', 'return_msg' => 'PARAM_ERROR' );
        }
    }
	/**
	 * @todo 支付校验记录
	 * @param unknown $inter_id
	 * @param unknown $result
	 * @param unknown $rid
	 * @return string[]|unknown[]|string[]
	 */
	function check_pay_result($inter_id,$return,$arr,$paras){
		$remark = '';
		if (empty ( $return )) {
			$remark .= '微信无返回结果。';
			//微信没返回，不二次查询了，直接状态改为异常 by situguanchen 2017-04-24
			$update ['status'] = 3;
			$update ['remark'] = $remark;
			$this->db->where ( array ('id' => $arr['rid'],'inter_id' => $inter_id) );
			$this->db->update ( 'distribute_send_record', $update );
			return array ('errmsg' => 'error','ck_res'=>$remark);
		} else
			$remark .= $return ['return_msg'];
		$check = $this->check_company_pay ( $arr ['partner_trade_no'], $paras );
		$update ['check_again'] = 1;
		$update ['check_content'] = $check ['result'];
		$checkinfo = json_decode ( json_encode ( simplexml_load_string ( $check ['result'], NULL, LIBXML_NOCDATA ) ), true );
		$remark .= '再次验证结果：' . $checkinfo ['return_msg'];
		$update ['remark'] = $remark;
		//SUCCESS
		if ($check ['errmsg'] == 'ok') {
			$update ['status'] = 1;
			$this->db->where ( array ('id' => $arr['rid'],'inter_id' => $inter_id) );
			$this->db->update ( 'distribute_send_record', $update );
			return array ('errmsg' => 'ok','partner_trade_no' => $arr ['partner_trade_no']);
		} else if($check ['errmsg'] == 'fail') {//SEND FAILD
			$update ['status'] = 2;
			$this->db->where ( array ('id' => $arr['rid'],'inter_id' => $inter_id) );
			$this->db->update ( 'distribute_send_record', $update );
			return array ('errmsg' => 'faild','ck_res'=>$checkinfo ['return_msg']);
		} else {//ERROR
			$update ['status'] = 3;
			$this->db->where ( array ('id' => $arr['rid'],'inter_id' => $inter_id) );
			$this->db->update ( 'distribute_send_record', $update );
			return array ('errmsg' => 'error','ck_res'=>$checkinfo ['return_msg']);
		}
			
	}
	/**
	 * @todo 支付结果主动查询
	 * @param string 商户单号
	 * @param array 商户支付参数
	 * @return string[]|请求成功返回成功结构，否则返回FALSE[]
	 */
	function check_company_pay($trade_no,$paras){
		$this->load->helper ( 'common' );
		$arr ['appid'] = $paras ['app_id'];
		$arr ['mch_id'] = $paras ['mch_id'];
		$arr ['nonce_str'] = createNoncestr ();
		$arr ['partner_trade_no'] = $trade_no;
		
		$this->load->model ( 'pay/wxpay_model' );
		$arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ( 'key' => $paras ['key'], 'app_id' => $arr ['appid'] ) );
		$extras = array ();
		$extras ['CURLOPT_CAINFO'] = realpath ( '../' ) . DS . "certs" . DS . "rootca_" . $paras ['mch_id'] . ".pem";
		$extras ['CURLOPT_SSLCERT'] = realpath ( '../' ) . DS . "certs" . DS . "apiclient_cert_" . $paras ['mch_id'] . ".pem";
		$extras ['CURLOPT_SSLKEY'] = realpath ( '../' ) . DS . "certs" . DS . "apiclient_key_" . $paras ['mch_id'] . ".pem";
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';
		$result = doCurlPostRequest ( $url, $this->wxpay_model->arrayToXml ( $arr ), $extras );

		log_message('error', 'DELIVER CHECK | '.$url.' | '.json_encode($arr).' | '.json_encode($extras));
		var_dump($result);
		$data = json_decode ( json_encode ( simplexml_load_string ( $result, NULL, LIBXML_NOCDATA ) ), true );
		if ($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'SUCCESS' && $data ['status'] == 'SUCCESS') {
			return array ( 'errmsg' => 'ok', 'result' => $result );
		} else if (($data ['result_code'] == 'SUCCESS' && $data ['status'] == 'FAILED') || $data ['result_code'] == 'FAIL') {
			return array ( 'errmsg' => 'fail', 'result' => $result );
		} else {
			return array ( 'errmsg' => 'wrong', 'result' => $result );
		}
	}
	
	public function get_mch_trans_params($inter_id){
		$account_info = $this->get_account_confg ( $inter_id );
		if ($account_info) {
			$this->load->model ( 'wx/publics_model' );
			$public_info = $this->publics_model->get_public_by_id ( $inter_id );
			$account_info ['mch_name'] = $public_info ['name'];
			$account_info ['app_id']   = $public_info ['app_id'];
			// 收款账号与支付账号分开
			if (isset ( $account_info ['pay_key'] )) {
				$account_info ['key'] = $account_info ['pay_key'];
			}
			if (isset ( $account_info ['pay_mch_id'] )) {
				$account_info ['mch_id'] = $account_info ['pay_mch_id'];
			}
			return $account_info;
		} else
			return FALSE;
	}
}