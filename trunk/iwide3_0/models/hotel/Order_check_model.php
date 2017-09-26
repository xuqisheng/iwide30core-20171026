<?php
class Order_check_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_HO = 'hotel_orders';
	/**
	 * 根据特定时间条件返回订单
	 * @param string $inter_id 公众号内部ID
	 * @param string $check_type 查询类型 order_time,time_between,startdate,enddate,date_between
	 * @param array $condit 根据$check_type 不同，传入不同参数值，具体参见代码
	 */
	function check_time_range_order($inter_id, $check_type = 'gt_order_time', $condit = array(),$return_type='nums') {
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where ( 'inter_id', $inter_id );
		if(!empty($condit['hotel_ids'])){
			$db_read->where_in ( 'hotel_id', $condit['hotel_ids'] );
		}
		if(!empty($condit['exp_status'])){
			$db_read->where_not_in ( 'status', $condit['exp_status'] );
		}
		// $check_type = explode ( ',', $check_type );
		// foreach ( $check_type as $ct ) {
		switch ($check_type) {
			case 'gt_order_time' :
				$db_read->where ('order_time >=',$condit['check_time']);
				break;
			default :
				break;
		}
		$result=$db_read->get(self::TAB_HO);
		if($return_type=='nums')
			return $result->num_rows();
		else
			return $result->result();
		// }
	}
	function check_order_state($order, $status_des = array()) {
		$cancel_status = array (
			0,
			1,
			9
		);
		$complete_status = array (
			2,
			3
		);
		$comment_status = array (
			2,
			3
		);
		$can_checkout_status = array(
			2,3
		);
		$status_tips_arr=array(
		      '0'=>'订单已提交，前台会尽快确认您的订单，请耐心等待',
			'1'=>'酒店已确认订单',
			'2'=>'祝您入住愉快',
			'3'=>'您已离店，欢迎再次光临',
			'4'=>'您已取消订单',
			'5'=>'很遗憾，酒店取消了订单',
			'11'=>'您的订单超时未支付，系统自动关闭'
		);
		$status_tips='';
		$can_comment = 0;
		$can_cancel = 0;
		$not_same = 0;
		$des = '';
		$show_item=0;
		$self_checkout=0;
		$self_checkout_des='';
		$item_state=array();
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $order ['inter_id'], 'HOTEL', $order ['hotel_id'], array (
			'HOTEL_PAID_ORDER_CANCEL',
			'COMMENT_ONLY_MEMBER',
			'CAN_COMMENT_STATUS',
			'CHECK_ORDER_PMS_STATE',
			'HOTEL_CANCEL_TIME',
			'PAY_CANCEL_POLICY',
			'SELF_CONTINUE',
			'NONE_COMMENT'//取消评论
		) );
		if (!empty($config_data['CAN_COMMENT_STATUS'])){
			$comment_status=explode(',', $config_data['CAN_COMMENT_STATUS']);
		}

		//增加检查pms的state判断
		$web_cancel = NULL;
		$web_re_pay = NULL;
		$web_check = NULL;
		$web_des = NULL;
		$web_comment = NULL;
		if (!empty($config_data['CHECK_ORDER_PMS_STATE'])&&$config_data['CHECK_ORDER_PMS_STATE']==1){
			$this->load->model('hotel/Hotel_check_model');
			$adapter=$this->Hotel_check_model->get_hotel_adapter($order['inter_id'],$order['hotel_id'],TRUE);
			$pms_state=$adapter->get_order_state($order,$status_des);
			if (!empty($pms_state)){
				$web_cancel=$pms_state['can_cancel'];
				$web_re_pay=$pms_state['re_pay'];
				$web_check=$pms_state['web_check'];
				$web_des=$pms_state['web_des'];
				$web_comment=$pms_state['web_comment'];
			}
		}

		$paid_cancel = 0;
		if (! empty ( $config_data ['HOTEL_PAID_ORDER_CANCEL'] ) && $config_data ['HOTEL_PAID_ORDER_CANCEL'] == 1) {
			$paid_cancel = 1;
		}

//        $cancel_time_config = $this->Hotel_config_model->get_hotel_config ( $order ['inter_id'], 'HOTEL', 0,'HOTEL_CANCEL_TIME');
		if (! empty ( $config_data ['HOTEL_CANCEL_TIME'] )) {   //规定时间前可以取消订单
			$cancel_time_config=$config_data ['HOTEL_CANCEL_TIME'];
			$cancel_hours = date('H',time());
			$cancel_time = date('Ymd',time());
			if($order['startdate']==$cancel_time && $cancel_hours>=$cancel_time_config){
				$paid_cancel = 0;
			}
		}

		$cancel_punish_rate=NULL;
		if (! empty ( $config_data ['PAY_CANCEL_POLICY'] )) {
			$pay_cancel_policy=json_decode($config_data ['PAY_CANCEL_POLICY'],TRUE);
			if (isset($pay_cancel_policy[$order['paytype']])){
				$pay_cancel_policy=$pay_cancel_policy[$order['paytype']];
			}else{
				$pay_cancel_policy=isset($pay_cancel_policy['all'])?$pay_cancel_policy['all']:array();
			}
			if($pay_cancel_policy){
				if (!empty($pay_cancel_policy['time'])){
					$range_check=0;
					$now=time();
					$startdate_time=strtotime($order['startdate']);
					if (!empty($pay_cancel_policy['time']['range'])){
						$sp=(isset($pay_cancel_policy['time']['sp']) && $pay_cancel_policy['time']['sp']>0) ? $pay_cancel_policy['time']['sp']:0;
						if (empty($pay_cancel_policy['time']['ty'])||$pay_cancel_policy['time']['ty']=='s'){
							$diff_time=$startdate_time+$sp-$now;
						}else if ($pay_cancel_policy['time']['ty']=='d'){
							$diff_time=ceil(($startdate_time-strtotime(date('Ymd',$now)))/86400);
						}
						foreach ($pay_cancel_policy['time']['range'] as $range){
							if (($range['s']==='-'||$diff_time>=$range['s'])&&($range['e']==='-'||$diff_time<=$range['e'])){
								if ($range['c']==1){
									$paid_cancel=1;
									$cancel_punish_rate=$range['f'];
								}else{
									$paid_cancel=0;
								}
								$range_check=1;
								break;
							}
						}
					}
					if ($range_check==0&&!empty($pay_cancel_policy['time']['def'])){
						if ($pay_cancel_policy['time']['def']['c']==1){
							$paid_cancel=1;
							$cancel_punish_rate=$pay_cancel_policy['time']['def']['f'];
						}else{
							$paid_cancel=0;
						}
					}
				}
				if (!empty($pay_cancel_policy['day'])){
					if (!empty($pay_cancel_policy['day']['week']['w'])&&in_array(date('w',$startdate_time), $pay_cancel_policy['day']['week']['w'])){
						if ($pay_cancel_policy['day']['week']['c']==1){
							$paid_cancel=1;
							$cancel_punish_rate=$pay_cancel_policy['day']['week']['f'];
						}else{
							$paid_cancel=0;
							$cancel_punish_rate=NULL;
						}
					}
					if (!empty($pay_cancel_policy['day']['date']['d'])&&in_array($order['startdate'], $pay_cancel_policy['day']['date']['d'])){
						if ($pay_cancel_policy['day']['date']['c']==1){
							$paid_cancel=1;
							$cancel_punish_rate=$pay_cancel_policy['day']['date']['f'];
						}else{
							$paid_cancel=0;
							$cancel_punish_rate=NULL;
						}
					}
				}
			}
		}

		$self_continue_config = empty ( $config_data ['SELF_CONTINUE'] ) ? array() : json_decode($config_data ['SELF_CONTINUE'],TRUE);
		if ($order['is_invoice']==2 || $order['is_invoice']==3){
			$self_checkout=2;
			$self_checkout_des='已预约退房';
		}
		if (count ( $order ['order_details'] ) == 1) {
			$des = empty ( $status_des ) ? '' : $status_des [$order ['status']];
			if (in_array ( $order ['status'], $cancel_status )) {
				$can_cancel = 1;
				if ($order ['paid'] == 1 && $paid_cancel == 0) {
					$can_cancel = 0;
				}
			}
			if (in_array ( $order ['status'], $comment_status )) {
				$can_comment = 1;
			}
			if (!$self_checkout && in_array ( $order ['status'], $can_checkout_status )) {
				$self_checkout = 1;
				$self_checkout_des='预约退房';
			}
			if ($self_continue_config && in_array($order ['order_details'][0]['istatus'], $self_continue_config['statuses']) && $order ['order_details'][0]['enddate'] >= date('Ymd')){
				$item_state[$order ['order_details'][0]['sub_id']]['can_continue']=1;
				$show_item=1;
			}else {
				$item_state[$order ['order_details'][0]['sub_id']]['can_continue']=0;
			}
			isset($status_tips_arr[$order['status']]) and $status_tips=$status_tips_arr[$order['status']];
		} else {
			$has_complete = 0;
			$cancel_flag = 0;
			foreach ( $order ['order_details'] as $od ) {
				if ($od ['istatus'] != $order ['status'] && $order['status'] !=9 ) {
					$not_same = 1;
				}
				if ((in_array ( $od ['istatus'], $cancel_status )) && $cancel_flag == 0) {
					$can_cancel = 1;
					if ($order ['paid'] == 1 && $paid_cancel == 0) {
						$can_cancel = 0;
						$cancel_flag = 1;
					}
				} else {
					$can_cancel = 0;
					$cancel_flag = 1;
				}
				if (in_array ( $od ['istatus'], $comment_status )) {
					$has_complete = 1;
				}
				if (!$self_checkout && in_array ( $od ['istatus'], $can_checkout_status )) {
					$self_checkout = 1;
					$self_checkout_des='退房';
				}
				if ($self_continue_config && in_array($od['istatus'], $self_continue_config['statuses']) && $od['enddate'] >= date('Ymd')){
					$item_state[$od['sub_id']]['can_continue']=1;
					$show_item=1;
				}else{
					$item_state[$od['sub_id']]['can_continue']=0;
				}
			}
			if ($order ['handled'] == 1 && $has_complete == 1) {
				$can_comment = 1;
			}
			if ($not_same == 1) {
				$des = '多订单';
			} else {
				$des = empty ( $status_des ) ? '' : $status_des [$order ['status']];
				isset($status_tips_arr[$order['status']]) and $status_tips=$status_tips_arr[$order['status']];
			}
		}
		$pms_check = 0;
		// if ($order ['handled'] == 0 && ! empty ( $order ['web_orderid'] )) {
		if ($order ['handled'] == 0 && $order ['status'] != 9 && ! empty ( $order ['web_orderid'] )) {
			$pms_check = 1;
		}
		$re_pay = 0;
		//获取每个公众号的配置超时时间
		$this->load->model('pay/Pay_model' );
		$pay_paras = $this->Pay_model->get_pay_paras( $order['inter_id'] );
		if(isset($pay_paras['outtime']) && $pay_paras['outtime']>5 && $pay_paras['outtime']<=30){
			$out_time = $pay_paras['outtime'] * 60;
		}else{
			$out_time = 900;//默认15分钟超时
		}
		$last_repay_time=date('Y/m/d H:i:s',$order ['order_time'] + $out_time );
		if ($order ['status'] == 9 && (time () - $order ['order_time']) < $out_time) {
			$re_pay = 1;
		}

		//是否只有会员可评论
		if ((!empty($config_data['COMMENT_ONLY_MEMBER'])&&$config_data['COMMENT_ONLY_MEMBER']==1)&&empty($order['member_no'])){
			$can_comment=0;
		}

		if(!empty($config_data['NONE_COMMENT'])){
			$can_comment=0;
		}

		//增加pms的state的判断
		$can_cancel=is_null($web_cancel)?$can_cancel:$web_cancel;
		$re_pay=is_null($web_re_pay)?$re_pay:$web_re_pay;
		$pms_check=is_null($web_check)?$pms_check:$web_check;
		$can_comment=is_null($web_comment)?$can_comment:$web_comment;
		$des=is_null($web_des)?$des:$web_des;

		$repay_url=$re_pay==1?$this->Pay_model->get_pay_link($order['paytype']).'/hotel_order?id=' . $order['inter_id'] . '&orderid=' . $order ['orderid']:'';

		$goods_state=array();
		if (!empty($order['goods_details'])){
			$order_goods_usable_status = array(
				1,2
			);
			foreach ($order['goods_details'] as $k=>$go){
				$goods_state[$k]['usable']=0;
				if (in_array($go['gstatus'],$order_goods_usable_status)){
					$goods_state[$k]['usable']=1;
					if ($go['external_channel']=='soma'){
						$goods_state[$k]['use_link']=site_url('soma/order/order_detail').'?id='.$order['inter_id'].'&oid='.$go['external_orderid'].'&bsn=package';
					}
				}
			}
		}

		return array (
			'can_comment' => $can_comment,
			'can_cancel' => $can_cancel,
			're_pay' => $re_pay,
			'not_same' => $not_same,
			'des' => $des,
			'pms_check' => $pms_check,
			'cancel_punish_rate' => $cancel_punish_rate,
			'show_item'=>$show_item,
			'item_state'=>$item_state,
			'repay_url'=>$repay_url,
			'self_checkout'=>$self_checkout,
			'self_checkout_des'=>$self_checkout_des,
			'last_repay_time' => $last_repay_time,
			'status_tips' => $status_tips,
			'goods_state' => $goods_state
		);
	}

	function get_order_status_count($inter_id,$hotel_id,$status_type='') {
		if (empty($hotel_id))
			return array();
		$db = $this->load->database('iwide_r1',true);
		switch ($status_type){
			case "normal":
				$db->where_not_in('status',array(9,10));
				break;
			default:
				break;
		}
		$db->select ( ' count(hotel_id) as order_count,hotel_id ' );
		$db->where('inter_id',$inter_id);
		if (is_array ( $hotel_id )) {
			$db->where_in ( 'hotel_id', $hotel_id );
			$db->group_by ( ' hotel_id ' );
		} else {
			$db->where ( 'hotel_id', $hotel_id );
			$result = $db->get (self::TAB_HO)->row_array ();
			return isset($result['order_count'])?$result['order_count']:0;
		}
		$result = $db->get (self::TAB_HO)->result_array ();
		if (! empty ( $result )) {
			$result = array_column ( $result, 'order_count', 'hotel_id' );
		}
		return $result;
	}

	function get_order_by_weborderid($inter_id,$web_orderid){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('inter_id'=>$inter_id,'web_orderid'=>$web_orderid));
		$order_addition=$db_read->get('hotel_order_additions')->row_array();
		if (!empty($order_addition)){
			$this->load->model('hotel/Order_model');
			$order=$this->Order_model->get_main_order( $inter_id, array (
				'orderid' => $order_addition['orderid'],
				'idetail' => array (
					'i'
				)
			) );
			if (!empty($order)){
				return $order[0];
			}
			return $order;
		}
		return $order_addition;
	}

	public function hotel_weixin_refund($order_id, $inter_id,$action='',$params=array()){
		//分账退款 先写这里吧 替换原有的微信退款 situguanchen 20170707
		$this->load->model('iwidepay/iwidepay_model');
        $split_order = $this->iwidepay_model->get_iwidepay_order($order_id);
        if($split_order){
        	//这里要返回
        	return $this->hotel_iwidepay_refund($order_id, $inter_id,$action='',$params=array());
        }
		$this->load->library('MYLOG');

		MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. '+'.$action,"order_refund");

		try{
			$return = array();

			$action= strtolower($action);
			$db_read = $this->load->database('iwide_r1',true);
			$order_pay_info = $db_read->get_where ( 'iwide_pay_log', array (
				'out_trade_no' => $order_id
			) )->row_array ();

			$this->load->model('hotel/Order_model' );
			$order_detail = $this->Order_model->get_order($inter_id,array('orderid'=>$order_id));

			if($order_detail){
				$order_detail = $order_detail[0];
			}

			if($order_pay_info){

				$order_pay_info = simplexml_load_string( $order_pay_info['rtn_content'], 'SimpleXMLElement', LIBXML_NOCDATA);
				$order_detail['transaction_id'] = $order_pay_info->transaction_id;
				$out_trade_no = $order_pay_info->out_trade_no;

			}else{

				$out_trade_no = $order_id;
			}


			if( !$order_detail ){
				// Soma_base::inst()->show_exception($order_id. '找不到订单信息');
//                 $this->db->insert('weixin_text',array('content'=>'order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ',找不到订单信息','edit_date'=>date('Y-m-d H:i:s')));
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ',找不到订单信息',"order_refund");
				// return FALSE;
				$return['status'] = 2;
				$return['message'] = '找不到订单信息';
				return $return;
			}

			if( isset( $order_detail['price'] ) && $order_detail['price'] <= 0 ){
//                 $this->db->insert('weixin_text',array('content'=>'order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 订单实付金额为0, 不进行微信退款, 储值全额支付。' ,'edit_date'=>date('Y-m-d H:i:s')));
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 订单实付金额为0, 不进行微信退款, 储值全额支付。',"order_refund");
				// return FALSE;
				$return['status'] = 2;
				$return['message'] = '订单实付金额为0';
				return $return;
			}

			//获取商户号(mch_id)
			$this->load->model('pay/Pay_model' );
			$pay_paras = $this->Pay_model->get_pay_paras( $inter_id );
			$mch_id = isset( $pay_paras['mch_id'] ) ? $pay_paras['mch_id'] : '';
			if( !$mch_id ){
				// Soma_base::inst()->show_exception('订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到商户号');
//                 $this->db->insert('weixin_text',array('content'=>'order_refund+'. '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到商户号' ,'edit_date'=>date('Y-m-d H:i:s')));
				MYLOG::w('order_refund+'. '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到商户号',"order_refund");
				// return FALSE;
				$return['status'] = 2;
				$return['message'] = '找不到商户号';
				return $return;
			}

			//获取appid
			$this->load->model('wx/publics_model');
			$public = $this->publics_model->get_public_by_id( $inter_id );
			$appid = isset( $public['app_id'] ) ? $public['app_id'] : '';
			if( !$appid ){
				// Soma_base::inst()->show_exception($order_id. '找不到公众账号ID');
//                 $this->db->insert('weixin_text',array('content'=>'order_refund+'.  '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到公众账号ID'  ,'edit_date'=>date('Y-m-d H:i:s')));
				MYLOG::w('order_refund+'.  '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到公众账号ID' ,"order_refund");
				// return FALSE;
				$return['status'] = 2;
				$return['message'] = '找不到公众账号ID';
				return $return;
			}

			//生成随机字符串、签名
			$this->load->model('pay/wxpay_model');
			$this->wxpay_model->setParameter("body", "退款申请");//设置参数使用此函数

			$nonce_str = $this->wxpay_model->createNoncestr();//获取随机字符串

//$out_trade_no = $order_id;

			$refund_fee = $order_pay_info->total_fee;

			$jsApiObj = array();
			$jsApiObj['appid']          = isset($pay_paras['app_id']) && !empty($pay_paras['app_id']) ? $pay_paras['app_id'] : $appid;//公众账号ID
			$jsApiObj['mch_id']         = $mch_id;//商户号

			//是否设置了子商户号
			if( isset( $pay_paras['sub_mch_id'] ) && !empty( $pay_paras['sub_mch_id'] ) ){
				$sub_mch_id = $pay_paras['sub_mch_id'];//子商户号

				//自商户分账-----------
				if( !empty($pay_paras['sub_mch_id_h_'. $order_detail['hotel_id']]) ){
					$sub_mch_id = $pay_paras['sub_mch_id_h_'. $order_detail['hotel_id']];
				}

				$jsApiObj['sub_mch_id'] = $sub_mch_id;//子商户号

				$transaction_id = isset( $order_detail['transaction_id'] ) ? $order_detail['transaction_id'] : NULL;
				if( $action == 'send' ){
					$jsApiObj['transaction_id'] = $transaction_id;
				}
			}

			$jsApiObj['nonce_str']      = $nonce_str;//随机字符串
			$jsApiObj['out_trade_no']   = $out_trade_no;//商户订单号

			$extras = array();
			//是发送退款请求
			if( $action == 'send' ){

				$select_mch_id = $mch_id;//isset($sub_mch_id) && !empty($sub_mch_id) ? $sub_mch_id : $mch_id;//选择商户号

				// 证书路径
				$extras = array();
				$extras['CURLOPT_CAINFO'] = realpath('../certs').DS."rootca_" . $select_mch_id . '.pem';
				$extras['CURLOPT_SSLCERT'] = realpath('../certs').DS."apiclient_cert_" . $select_mch_id . '.pem';
				$extras['CURLOPT_SSLKEY'] = realpath('../certs').DS."apiclient_key_" . $select_mch_id . '.pem';

				//判断证书是否存在
				if( !file_exists( $extras['CURLOPT_SSLCERT'] ) || !file_exists( $extras['CURLOPT_SSLKEY'] ) ){
//                     $this->db->insert('weixin_text',array('content'=>'order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 没有找到证书'.$select_mch_id,'edit_date'=>date('Y-m-d H:i:s')));
					MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 没有找到证书'.$select_mch_id ,"order_refund");
					// return FALSE;
					$return['status'] = 2;
					$return['message'] = '没有找到证书';
					return $return;
				}

				$jsApiObj['op_user_id']     = $select_mch_id;//操作员
				$jsApiObj['out_refund_no']  = $out_trade_no;//商户退款单号
				$jsApiObj['total_fee']      = $refund_fee;//支付金额
				if (isset($params['punish_rate'])&&$params['punish_rate']>0&&$params['punish_rate']<1){
					$refund_fee=number_format($refund_fee*(1-$params['punish_rate']),2,'.','')*1;
					$refund_fee<0.01 and $refund_fee=0.01;
				}
				$jsApiObj['refund_fee']     = $refund_fee;//退款金额

				$url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
			}elseif( $action == 'check' ){
				//发送查询退款请求
				$url = 'https://api.mch.weixin.qq.com/pay/refundquery';
			}elseif( $action == 'close' ){
				//微信关闭订单
				$url = 'https://api.mch.weixin.qq.com/pay/closeorder';
			}elseif( $action == 'query' ){
				//微信关闭订单
				$url = 'https://api.mch.weixin.qq.com/pay/orderquery';
			}else{
//                $this->save_refund('default', '没有执行命令action' );
				// return FALSE;
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 没有执行命令action' ,"order_refund");
				$return['status'] = 2;
				$return['message'] = '没有执行命令action';
				return $return;
			}

			//获取签名
			$jsApiObj['sign'] = $this->wxpay_model->getSign( $jsApiObj, $pay_paras );

			//array to xml
			$xml = $this->wxpay_model->arrayToXml( $jsApiObj );

			//发送数据
			$this->load->helper('common_helper');

			$result = doCurlPostRequest( $url, $xml, $extras );

			//判断是否成功
			$result = $this->wxpay_model->xmlToArray( $result );
			$return_code = isset( $result['return_code'] ) ? $result['return_code'] : '';
			$result_code = isset( $result['result_code'] ) ? $result['result_code'] : '';
			if( $return_code == 'SUCCESS' && $result_code == 'SUCCESS' ){
				if( $action == 'check' ){
					//如果是查询订单，返回退款状态和退款入账方
					$return['data'] = $result;
					// return $result;
				}

				// return TRUE;
				$return['status'] = 1;
				$return['message'] = 'SUCCESS';
				return $return;
			}else{

				//记录上一次发送前的信息
				MYLOG::w('order_refund+'.$xml,"order_refund");
//              $this->db->insert('weixin_text',array('content'=>'order_refund+'.$xml,'edit_date'=>date('Y-m-d H:i:s')));
				//记录上一次微信返回的信息
				MYLOG::w('order_refund+'.json_encode( $result ) ,"order_refund");
//             	$this->db->insert('weixin_text',array('content'=>'order_refund+'.json_encode( $result ),'edit_date'=>date('Y-m-d H:i:s')));

				//以下代码修改是为了解决使用wx_out_trade_no_encode，微信返回TRANSACTION_ID_INVALID(订单号非法)
				if( isset( $result['err_code'] )
					&& ( $result['err_code'] == 'TRANSACTION_ID_INVALID' //订单退款
						|| $result['err_code'] == 'REFUNDNOTEXIST' //订单查询
						|| $result['err_code'] == 'ERROR' ) ){//订单关闭
					$return['status'] = 2;
					$return['err_code'] = $result['err_code'];
					return $return;
				}

				// return FALSE;
				$return['status'] = 2;
				$return['message'] = isset( $result['err_code'] ) ? $result['err_code'] : 'FAIL';
				return $return;
			}
		}catch( Exception $e ){

			// return FALSE;
			MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id.'+'.$e->getMessage() ,"order_refund");
			$return['status'] = 2;
			$return['message'] = $e->getMessage();
			return $return;
		}

	}

	//分账退款 替换原来的微信退款
	//situguanchen 20170706
    public function hotel_iwidepay_refund($order_id, $inter_id,$action='',$params=array()){

        $this->load->library('MYLOG');

        MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id,"iwidepay/refund");
		MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id ,"order_refund");
        try{
            $return = array();
            $db_read = $this->load->database('iwide_r1',true);
            $order_pay_info = $db_read->get_where ( 'iwide_pay_log', array (
                'out_trade_no' => $order_id
            ) )->row_array ();

            $this->load->model('hotel/Order_model' );
            $order_detail = $this->Order_model->get_order($inter_id,array('orderid'=>$order_id));

            if($order_detail){
                $order_detail = $order_detail[0];
            }
            if($order_pay_info){
            	require_once APPPATH.'libraries/IwidePay/IwidePayData.php';
            	//转成数组
                $order_pay_info = parseQString( $order_pay_info['rtn_content']);
                $order_detail['transaction_id'] = $order_pay_info['payId'];
                $out_trade_no = $order_pay_info['orderNo'];

            }else{
                $out_trade_no = $order_id;
            }

            if( !$order_detail ){
                MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ',找不到订单信息',"iwidepay/refund");
				MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id . ',找不到订单信息' ,"order_refund");
                $return['status'] = 2;
                $return['message'] = '找不到订单信息';
                return $return;
            }

            if( isset( $order_detail['price'] ) && $order_detail['price'] <= 0 ){
                MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 订单实付金额为0, 不进行分账退款。',"iwidepay/refund");
				MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id . ',订单实付金额为0, 不进行分账退款。' ,"order_refund");
                $return['status'] = 2;
                $return['message'] = '订单实付金额为0';
                return $return;
            }
            $refund_orderid = $out_trade_no . time() . rand(1000,9999);
             //分账退款
             $refund_fee = $order_pay_info['transAmt'];
            if (isset($params['punish_rate'])&&$params['punish_rate']>0&&$params['punish_rate']<1){
				    $refund_fee=number_format($refund_fee*(1-$params['punish_rate']),2,'.','')*1;
				    $refund_fee<0.01 and $refund_fee=0.01;
			}//这里好像没有上线的，到时看情况是否屏蔽掉 20170706 situguanchen
            $iwidepay_refund = array(
                'orderDate' => date('Ymd'),
                'orderNo' => $refund_orderid,
                'requestNo' => md5(time()),
                'transAmt' => $refund_fee,//单位：分
                'returnUrl'=>'http://cmbcpay.jinfangka.com/index.php',
                'refundReson' => '订房退款',
            );
            $res = $this->iwidepay_model->refund($iwidepay_refund,$order_id);
            if(isset($res['respCode']) && $res['respCode'] === '0000'){
                $return['status'] = 1;
                $return['message'] = 'SUCCESS';
                return $return;
            }elseif(isset($res['respCode']) && ($res['respCode'] == 'P000'||$res['respCode'] == '9999'||$res['respCode'] == '9997'||$res['respCode'] == '0028')){//中间状态 记录日志
            	MYLOG::w('订房分账退款返回中间状态:订单号：'.$order_id.', 公众号：'.$inter_id , 'iwidepay/refund');
				MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id . ',订房分账退款返回中间状态,当成功处理。' ,"order_refund");
                $return['status'] = 1;//中间状态当做成功处理
                $return['message'] = 'SUCCESS';
                return $return;               
            }elseif(isset($res['respCode']) && ($res['respCode'] == '0042' || $res['respCode'] == '0066')){
				MYLOG::w('订房分账退款（微信里没钱状态）:订单号：'.$order_id.', 公众号：'.$inter_id , 'iwidepay/refund');
				MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id . ',微信里没钱状态,当成功处理。' ,"order_refund");
				$return['status'] = 1;//微信里没钱状态成功处理
				$return['message'] = 'SUCCESS';
				return $return;
			}else{
            	$return['status'] = 2;
                $return['message'] = isset($res['respDesc'])?$res['respDesc']:'error';
                return $return; 
            }
        }catch( Exception $e ){
            MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id.'+'.$e->getMessage() ,"iwidepay/refund");
			MYLOG::w('订房分账退款order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id .'+ '.$e->getMessage() ,"order_refund");
            $return['status'] = 2;
            $return['message'] = $e->getMessage();
            return $return;
        }

    }

	//银联退款
	public function hotel_unionpay_refund($order_id, $inter_id){

		$this->load->library('MYLOG');

		MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id,"order_refund_unionpay");

		try{
			$return = array();
			$db_read = $this->load->database('iwide_r1',true);
			$order_pay_info = $db_read->get_where ( 'iwide_pay_log', array (
				'out_trade_no' => $order_id
			) )->row_array ();

			$this->load->model('hotel/Order_model' );
			$order_detail = $this->Order_model->get_order($inter_id,array('orderid'=>$order_id));

			if($order_detail){
				$order_detail = $order_detail[0];
			}

			if($order_pay_info){

				$order_pay_info = json_decode( $order_pay_info['rtn_content'],true);
				$order_detail['transaction_id'] = $order_pay_info['queryId'];
				$out_trade_no = $order_pay_info['orderId'];

			}else{

				$out_trade_no = $order_id;
			}


			if( !$order_detail ){
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ',找不到订单信息',"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = '找不到订单信息';
				return $return;
			}

			if( isset( $order_detail['price'] ) && $order_detail['price'] <= 0 ){
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 订单实付金额为0, 不进行银联退款。',"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = '订单实付金额为0';
				return $return;
			}

			//获取商户号(mch_id)
			$this->load->model('pay/Pay_model' );
			$pay_paras = $this->Pay_model->get_pay_paras( $inter_id ,'unionpay');
			$mch_id = isset( $pay_paras['mch_id'] ) ? $pay_paras['mch_id'] : '';
			if( !$mch_id ){
				MYLOG::w('order_refund+'. '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到商户号',"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = '找不到商户号';
				return $return;
			}

			//加载银联
			require_once APPPATH.'/libraries/UnionPay/acp_service.php';
			$refund_orderid = str_replace('_','yinlian',str_replace('-','union',$out_trade_no)).'refund';
			$params = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.0.0',		      //版本号
				'encoding' => 'utf-8',		      //编码方式
				'signMethod' => '01',		      //签名方法
				'txnType' => '04',		          //交易类型
				'txnSubType' => '00',		      //交易子类
				'bizType' => '000201',		      //业务类型
				'accessType' => '0',		      //接入类型
				'channelType' => '07',		      //渠道类型
				'backUrl' => site_url ( 'unionpayreturn/hotel_refund_return/'.$inter_id ),	  //后台通知地址

				//TODO 以下信息需要填写
				'orderId' => $refund_orderid,	    //商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
				'merId' => $mch_id,	        //商户代码，请改成自己的测试商户号，此处默认取demo演示页面传递的参数
				'origQryId' => $order_detail['transaction_id'], //原消费的queryId，可以从查询接口或者通知接口中获取，此处默认取demo演示页面传递的参数
				'txnTime' => date('YmdHis'),	    //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
				'txnAmt' => $order_pay_info['txnAmt'],       //交易金额，退货总金额需要小于等于原消费
				//'reqReserved' =>'透传信息',            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
			);
			com\unionpay\acp\sdk\UnionPayConfig::setSignCertPath ( '../certs/acp_prod_sign_'.$pay_paras ['mch_id'].'.pfx' );
			com\unionpay\acp\sdk\UnionPayConfig::setSignCertPwd ( $pay_paras ['pwd'] );
			com\unionpay\acp\sdk\UnionPayConfig::setVerifyCertDir ( '../certs/');
			//判断证书是否存在
			if( !file_exists( '../certs/acp_prod_sign_'.$pay_paras ['mch_id'].'.pfx' ) ){
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 没有找到证书'.$mch_id ,"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = '没有找到证书';
				return $return;
			}

			com\unionpay\acp\sdk\AcpService::sign ( $params ); // 签名
			$url = 'https://gateway.95516.com/gateway/api/backTransReq.do';

			$result_arr = com\unionpay\acp\sdk\AcpService::post ( $params, $url);
			if(count($result_arr)<=0) { //没收到200应答的情况
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 没收到200应答的情况'.$mch_id ,"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = '没收到200应答的情况';
				return $return;
			}


			if (!com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){
				MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id. ', 应答报文验签失败'.$mch_id ,"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = '应答报文验签失败';
				return $return;
			}

			//应答报文验签成功;
			if ($result_arr["respCode"] == "00"){
				//交易已受理，等待接收后台通知更新订单状态，如果通知长时间未收到也可发起交易状态查询
				//TODO
				//受理成功。
				//写入队列
				$data = array(
					'inter_id' => $inter_id,
					'out_trade_no' => $out_trade_no,
					'c_time' => time(),
					'param_content' => json_encode( $params ),
					'rtn_content' => json_encode( $result_arr ),
					'total_fee' => $order_pay_info['txnAmt'],
					'transaction_id' => $order_detail['transaction_id'],
					'paytype' => 'unionpay'
				);
				$this->db->insert ( 'pay_refund', $data );
				$return['status'] = 1;
				$return['message'] = 'SUCCESS';
				return $return;
			} else if ($result_arr["respCode"] == "03"
				|| $result_arr["respCode"] == "04"
				|| $result_arr["respCode"] == "05" ){
				//后续需发起交易状态查询交易确定交易状态
				//TODO
				//处理超时
				MYLOG::w('order_refund+params+'.json_encode( $params ),"order_refund_unionpay");
				MYLOG::w('order_refund+result_arr+'.json_encode( $result_arr ) ,"order_refund_unionpay");
				$return['status'] = 2;
				$return['message'] = isset( $result_arr["respMsg"] ) ? $result_arr["respMsg"] : '处理超时';
				return $return;
			} else {
				//其他应答码做以失败处理
				//TODO
				MYLOG::w('order_refund+params+'.json_encode( $params ),"order_refund_unionpay");

				MYLOG::w('order_refund+result_arr+'.json_encode( $result_arr ) ,"order_refund_unionpay");

				$return['status'] = 2;
				$return['message'] = isset( $result_arr["respMsg"] ) ? $result_arr["respMsg"] : 'FAIL';
				return $return;
			}

		}catch( Exception $e ){

			MYLOG::w('order_refund+'.'订单号：'.$order_id.', 公众号：'.$inter_id.'+'.$e->getMessage() ,"order_refund_unionpay");
			$return['status'] = 2;
			$return['message'] = $e->getMessage();
			return $return;
		}

	}

	public function check_self_continue($inter_id, $orderid, $openid, $item_id, $params = array()) {
		$info = array (
			's' => 0,
			'errmsg' => '错误'
		);
		$this->load->model ( 'hotel/Order_model' );
		$order = $this->Order_model->get_main_order ( $inter_id, array (
			'orderid' => $orderid,
			'idetail' => array (
				'i'
			)
		) );
		if ($order) {
			$order = $order [0];
			$state = $this->check_order_state ( $order );
			if ($item_id && ! empty ( $state ['item_state'] [$item_id] ['can_continue'] )) {
				$order_items = array_column ( $order ['order_details'], NULL, 'sub_id' );
				$item = $order_items [$item_id];
				$this->load->model ( 'hotel/Hotel_model' );
				$hotel_id = $order ['hotel_id'];
				$room_id = $item ['room_id'];
				$startdate = $item ['enddate'];
				$enddate = date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $startdate ) ) );
				if ($startdate >= date ( 'Ymd' )) {
					$data_arr = array (
						$item ['room_id'] => 1
					);
					$room_list = $this->Hotel_model->get_rooms_detail ( $inter_id, $hotel_id, array_keys ( $data_arr ), array (
						'data' => 'key'
					) );
					$price_codes = array (
						$item ['room_id'] => $item ['price_code']
					);
					$member_lv = '';
					if (isset ( $params ['member_level'] )) {
						$member_lv = $params ['member_level'];
					} else {
						$this->load->model ( 'hotel/Hotel_check_model' );
						$pub_pmsa = $this->Hotel_check_model->get_hotel_adapter ( $inter_id, 0, TRUE );
						$member = $pub_pmsa->check_openid_member ( $inter_id, $openid );
						if (! empty ( $member ) && isset ( $member->mem_id )) {
							$member_lv = $member->level;
						}
					}
					$condit = array (
						'startdate' => $startdate,
						'enddate' => $enddate,
						'price_codes' => implode ( ',', $price_codes ),
						'nums' => $data_arr,
						'openid' => $openid,
						'member_level' => $member_lv
					);
					$this->load->model ( 'hotel/Member_model' );
					$member_privilege = $this->Member_model->level_privilege ( $inter_id );
					if (! empty ( $member_privilege )) {
						$condit ['member_privilege'] = $member_privilege;
					}
					$condit ['price_type'] = empty ( $order ['price_type'] ) ? array (
						'common'
					) : array (
						$order ['price_type']
					);
					$this->load->library ( 'PMS_Adapter', array (
						'inter_id' => $inter_id,
						'hotel_id' => $hotel_id
					), 'pmsa' );
					$rooms = $this->pmsa->get_rooms_change ( $room_list, array (
						'inter_id' => $inter_id,
						'hotel_id' => $hotel_id
					), $condit, true );
					if (isset ( $rooms [$room_id] ['state_info'] )) {
						$this->load->model('hotel/Price_code_model');
						foreach ($rooms [$room_id] ['state_info'] as $p=>$s){
							$code_check=$this->Price_code_model->check_special_code($s ['price_code'],$inter_id);
							if (!empty($code_check)){
								$rooms [$room_id] ['state_info'][$code_check['true_code']]=$s;
							}
						}
					}
					if (isset ( $rooms [$room_id] ['state_info'] [$item ['price_code']] )) {
						$room_state = $rooms [$room_id] ['state_info'] [$item ['price_code']];
						if ($room_state ['book_status'] == 'available') {
							$this->load->model ( 'hotel/Debts_model' );
							$check = $this->Debts_model->get_source_debt ( $inter_id, 'order_continue', $order ['orderid'], 'inprogress', array (
								'sub_ident' => $item ['sub_id'],
								'latest' => 1
							) );
							$ex_data = $check ? json_decode ( $check ['ex_data'], TRUE ) : array ();
							if (! $check || $check ['debt_amount'] != $room_state ['total_price'] || $ex_data ['e'] != $enddate) {
								$debt_id = $this->Debts_model->create_debt ( $inter_id, 'order_continue', $order ['orderid'], $room_state ['total_price'], array (
									'sub_ident' => $item ['sub_id'],
									'remark' => '从' . date ( 'Y-m-d', strtotime ( $order ['enddate'] ) ) . '续住到' . date ( 'Y-m-d', strtotime ( $enddate ) ),
									'ex_data' => array (
										's' => $startdate,
										'e' => $enddate
									)
								) );
							} else {
								$debt_id = $check ['debtid'];
							}
							if ($debt_id) {
								$info ['s'] = 1;
								$info ['errmsg'] = '可续住';
								$info ['debtid'] = $debt_id;
								$info ['order'] = $order;
								$info ['pay_link'] = site_url ( 'wxpay/hotel_continue' ) . '?id=' . $inter_id . '&debtid=' . $debt_id;
							} else {
								$info ['errmsg'] = '续住失败';
							}
						} else {
							$info ['errmsg'] = '可用房数不足';
						}
					} else {
						$info ['errmsg'] = '已无可用房';
					}
				} else {
					$info ['errmsg'] = '离店日期在今天前，不可再续住';
				}
			} else {
				$info ['errmsg'] = '该房状态不可续住';
			}
		}
		return $info;
	}
}