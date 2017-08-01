<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	include 'PrintHttpClient.class.php';
	include 'HttpClient.class.php';
	// define('DEVICE_NO', '814070365');//测试
	// define('KEY', '3oGH6wh0');
	// IP和端口不需要更改
	define ( 'FEIE_HOST', '115.28.225.82' );
	define ( 'FEIE_PORT', 80 );
	define ( 'FEIE_HOST_V2', 'api163.feieyun.com' );
	define ( 'FEIE_PORT_V2', 80 );
	class Print_model extends CI_Model {
		const TAB_PRINT_DEVICE = 'print_device_info';
		function __construct() {
			parent::__construct ();
		}
		public function print_hotel_order($order, $print_type) {
			$dev_info = $this->get_deviceinfo ( $order ['inter_id'], $order ['hotel_id'], $print_type );
			$results=array();
			if (! empty ( $dev_info )) {
				$this->load->model ( 'plugins/Template_msg_model' );
				$fr = $this->Template_msg_model->order_find_replace ( $order );
				foreach ( $dev_info as $di ) {
					$data = array ();
					$data ['print_text'] = $this->get_print_text ( $di ['type'], array (
							'print_data' => $order,
							'fr' => $fr
					) );
					$results[$di['id']]=$this->print_adapter ( $di ['dev_type'], $di, $data );
				}
			}
			return $results;
		}

        //预约订座--后台取号 2017-4-19 沙
        public function print_appointment_offer($offer, $print_type)
        {
            //$dev_info = $this->get_deviceinfo ( $order ['inter_id'], $order ['hotel_id'], $print_type );
            //这里查另外的表
            $db = $this->load->database('iwide_r1',true);
            $dev_info = $db->get_where('roomservice_printer_info',array('inter_id'=>$offer['inter_id'],'hotel_id'=>$offer['hotel_id'],'shop_id'=>$offer['shop_id'],'status'=>1,'source'=>3))->result_array();
            if (! empty ( $dev_info ))
            {
                foreach($dev_info as $di)
                {
                    if(json_decode($di['type'],true) && !in_array($print_type,json_decode($di['type'],true)))
                    {
                        continue;
                    }
                $di['dev_auth'] = json_encode(array('device_no'=>$di['printer_no'],'apitype'=>$di['apitype'],'key'=>$di['printer_key'],'version'=>2));
                    $data = array ();

                    $data ['print_text'] = $this->appointment_offer_text($offer);

                    $di['type'] = $print_type;//兼容之前的日志，存具体的值
                    $this->print_adapter($di['dev_type'], $di, $data);
                }
            }
        }

		public function print_roomservice_order($order, $print_type) {
			//$dev_info = $this->get_deviceinfo ( $order ['inter_id'], $order ['hotel_id'], $print_type );
			//这里查另外的表
			$db = $this->load->database('iwide_r1',true);
		$dev_info = $db->get_where('roomservice_printer_info',array('inter_id'=>$order['inter_id'],'hotel_id'=>$order['hotel_id'],'shop_id'=>$order['shop_id'],'status'=>1,'source'=>1))->result_array();
			if (! empty ( $dev_info )) {
				foreach ( $dev_info as $di ) {
					if(json_decode($di['type'],true) && !in_array($print_type,json_decode($di['type'],true))){
						continue;
					}
					$di['dev_auth'] = json_encode(array('device_no'=>$di['printer_no'],'apitype'=>$di['apitype'],'key'=>$di['printer_key'],'version'=>2));
					$data = array ();
					$data ['print_text'] = $this->get_roomservice_print_text ( $print_type, array (
							'print_data' => $order,
							//'fr' => $fr
					) );
					$di['type'] = $print_type;//兼容之前的日志，存具体的值
					$this->print_adapter ( $di ['dev_type'], $di, $data );
				}
			}
		}

		public function print_okpay_order($order, $print_type) {
			//$dev_info = $this->get_deviceinfo ( $order ['inter_id'], $order ['hotel_id'], $print_type );
			//这里查另外的表
			$db_read = $this->load->database("iwide_r1", TRUE);
        $dev_info = $db_read->get_where('roomservice_printer_info',array('inter_id'=>$order['inter_id'],'shop_id'=>$order['pay_type'],'status'=>1,'source'=>2))->result_array();
			//$dev_info = array(array('inter_id'=>'a429262687','printer_no'=>'814070875','printer_key'=>'U4c3M0Kh','apitype'=>'php','dev_type'=>'feie','type'=>'["new_order"]','print_times'=>1));
			if (! empty ( $dev_info )) {
				foreach ( $dev_info as $di ) {
					if(json_decode($di['type'],true) && !in_array($print_type,json_decode($di['type'],true))){
						continue;
					}
					$di['dev_auth'] = json_encode(array('device_no'=>$di['printer_no'],'apitype'=>$di['apitype'],'key'=>$di['printer_key'],'version'=>2));
					$data = array ();
					$data ['print_text'] = $this->get_okpay_print_text ( $print_type, array (
							'print_data' => $order,
							//'fr' => $fr
					) );
					$di['type'] = $print_type;//兼容之前的日志，存具体的值
					$this->print_adapter ( $di ['dev_type'], $di, $data );
				}
			}
		}
		public function check_printer_status($printer_sn,$key){
			$msgInfo = array(
					'sn'=>$printer_sn,
					'key'=>$key,
			);

			$client = new feie_print_v2\HttpClient(FEIE_HOST_V2, FEIE_PORT);
			if(!$client->post('/FeieServer/queryPrinterStatusAction',$msgInfo)){
				echo 'error';
			}
			else{
				$result = $client->getContent();
				return $result;
			}
		}
		public function get_roomservice_print_text($print_type, $datas) {
			$print_text = '';
			switch ($print_type) {
				case 'new_order' :
					$print_text = $this->roomservice_new_order ( $datas ['print_data'] );
					break;
				case 'ensure_order' :
					$print_text = $this->roomservice_ensure_order ( $datas ['print_data'] );
					break;
				case 'remind_order' :
					$print_text = $this->roomservice_remind_order ( $datas ['print_data'] );
					break;
			}
			return $print_text;
		}
		public function get_okpay_print_text($print_type, $datas) {
			$print_text = '';
			switch ($print_type) {
				case 'okpay_pay_success' :
					$print_text = $this->okpay_new_order ( $datas ['print_data'] );
					break;
			}
			return $print_text;
		}
		public function get_print_text($print_type, $datas) {
			$print_text = '';
			switch ($print_type) {
				case 'new_order' :
					$print_text = $this->new_order_text ( $datas ['print_data'] );
					break;
				case 'ensure_order' :
					$print_text = $this->ensure_order_text ( $datas ['print_data'] );
					break;
				case 'cancel_order_4' :
					$print_text = $this->cancel_order_text ( $datas ['print_data'] );
					break;
				case 'cancel_order_5' :
					$print_text = $this->cancel_order_text ( $datas ['print_data'] );
					break;
				default :
					break;
			}
			return $print_text;
		}
		public function get_deviceinfo($inter_id, $hotel_id, $type, $status = 1) {
			$db = $this->load->database('iwide_r1',true);
			$db->where ( array (
					'inter_id' => $inter_id,
					'hotel_id' => $hotel_id,
					'type' => $type,
					// 'dev_type' => $dev_type,
					'status' => $status
			) );
			return $db->get ( self::TAB_PRINT_DEVICE )->result_array ();
		}
		public function print_adapter($dev_type, $dev_info, $data) {
			$dev_info ['dev_auth'] = json_decode ( $dev_info ['dev_auth'], TRUE );
			switch ($dev_type) {
				case 'feie' :
					if (isset($dev_info ['dev_auth']['version'])&&$dev_info ['dev_auth']['version']==2){
						return $this->feie_print_v2 ( $dev_info, $data );
					}else {
						return $this->feie_print ( $dev_info, $data );
					}
				default :
					break;
			}
		}
		public function feie_print($dev_info, $data) {
			$selfMessage = array (
					'clientCode' => $dev_info ['dev_auth'] ['device_no'],
					'printInfo' => $data ['print_text'],
					'apitype' => $dev_info ['dev_auth'] ['apitype'],
					'key' => $dev_info ['dev_auth'] ['key'],
					'printTimes' => $dev_info ['print_times']
			);
			$now = time ();
			$s = sendSelfFormatMessage ( $selfMessage );

			$this->load->model('common/Webservice_model');
			$this->Webservice_model->add_webservice_record($dev_info ['inter_id'], 'feie', '', $selfMessage, $s,$dev_info ['type'] . '_print', $now, microtime (), 'print_r');

			// "{"reslutCode":0,"msg":"success"}"
			$s=json_decode($s,TRUE);
			if (!empty($s)){
				if ($s['reslutCode']==0){
					return array('s'=>1,'errmsg'=>$s['msg']);
				}else {
					return array('s'=>0,'errmsg'=>$s['msg'].',error code:'.$s['reslutCode']);
				}
			}
			return array('s'=>0,'errmsg'=>'no response');
		}
		public function feie_print_v2($dev_info, $data) {
			$selfMessage = array (
					'sn' => $dev_info ['dev_auth'] ['device_no'],
					'printContent' => $data ['print_text'],
					'apitype' => $dev_info ['dev_auth'] ['apitype'],
					'key' => $dev_info ['dev_auth'] ['key'],
					'times' => $dev_info ['print_times']
			);
			$now = time ();
			$s = wp_print ( $selfMessage );

			$this->load->model('common/Webservice_model');
			$this->Webservice_model->add_webservice_record($dev_info ['inter_id'], 'feie_v2', '', $selfMessage, $s,$dev_info ['type'] . '_print', $now, microtime (), 'print_r');

			// {"responseCode":0,"msg":"服务器接收订单成功","orderindex":"814070875_20170329192156_632291955"}
			$s=json_decode($s,TRUE);
			if (!empty($s)){
				if ($s['responseCode']==0){
					return array('s'=>1,'errmsg'=>$s['msg'],'orderindex'=>$s['orderindex']);
				}else {
					return array('s'=>0,'errmsg'=>$s['msg'].',error code:'.$s['responseCode']);
				}
			}
			return array('s'=>0,'errmsg'=>'no response');
		}
		function new_order_text($order) {
			$status = array (
					'0' => '待确认',
					'1' => '已确认',
					'2' => '入住',
					'3' => '离店',
					'4' => '用户取消',
					'5' => '酒店取消',
					'6' => '酒店删除',
					'7' => '异常',
					'8' => '未到',
					'9' => '未支付'
			);
			$orderInfo = '<CB>' . $order ['hname'] . '</CB><BR><BR>'; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo .= "订单号：" . $order ['orderid'] . "\r\n";
			$orderInfo .= "下单时间：" . date ( 'Y-m-d H:i:s', $order ['order_time'] ) . "\r\n";
			$orderInfo .= "------------------------\r\n";
			$orderInfo .= "入住人：" . $order ['name'] . "\r\n";
			$orderInfo .= "联系电话：" . $order ['tel'] . "\r\n";
			$orderInfo .= "房型：" . $order ['first_detail'] ['roomname'] . "\r\n";
			$orderInfo .= "价格代码：" . $order ['first_detail'] ['price_code_name'] . "\r\n";
			$orderInfo .= "房间数：" . $order ['roomnums'] . "\r\n";
			$orderInfo .= "入住时间：" . date ( "Y-m-d", strtotime ( $order ['startdate'] ) ) . "\r\n";
			$orderInfo .= "离店时间：" . date ( "Y-m-d", strtotime ( $order ['enddate'] ) ) . "\r\n";
			$price = $order ['price'];
			if ($order ['paid'] == 0) {
				$orderInfo .= "支付状态：到店支付\r\n";
				$orderInfo .= "预留至：" . date ( "Y-m-d", strtotime ( $order ['startdate'] ) ) . " 18:00\r\n";
			} else if ($order ['paid'] == 1) {
				$orderInfo .= "支付状态：已付款\r\n";
				$orderInfo .= "预留至：" . date ( "Y-m-d", strtotime ( $order ['enddate'] ) ) . " 12:00\r\n";
				$price -= floatval ( $order ['wxpay_favour'] );
			}
			$orderInfo .= "------------------------\r\n";
			$orderInfo .= "订单总价：" . $price . "元\r\n";
			$orderInfo .= "订单状态：" . $status [$order ['status']] . "\r\n";
			$orderInfo .= "备注：\r\n";
			$orderInfo .= $order ['remark'];
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}
		function ensure_order_text($order) {
			$status = array (
					'0' => '待确认',
					'1' => '已确认',
					'2' => '入住',
					'3' => '离店',
					'4' => '用户取消',
					'5' => '酒店取消',
					'6' => '酒店删除',
					'7' => '异常',
					'8' => '未到',
					'9' => '未支付'
			);
			$orderInfo = '<CB>' . $order ['hname'] . '</CB><BR><BR>'; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo .= "订单号：" . $order ['orderid'] . "\r\n";
			$orderInfo .= "下单时间：" . date ( 'Y-m-d H:i:s', $order ['order_time'] ) . "\r\n";
			$orderInfo .= "------------------------\r\n";
			$orderInfo .= "入住人：" . $order ['name'] . "\r\n";
			$orderInfo .= "联系电话：" . $order ['tel'] . "\r\n";
			$orderInfo .= "房型：" . $order ['first_detail'] ['roomname'] . "\r\n";
			$orderInfo .= "价格代码：" . $order ['first_detail'] ['price_code_name'] . "\r\n";
			$orderInfo .= "房间数：" . $order ['roomnums'] . "\r\n";
			$orderInfo .= "入住时间：" . date ( "Y-m-d", strtotime ( $order ['startdate'] ) ) . "\r\n";
			$orderInfo .= "离店时间：" . date ( "Y-m-d", strtotime ( $order ['enddate'] ) ) . "\r\n";
			$price = $order ['price'];
			if ($order ['paid'] == 0) {
				$orderInfo .= "支付状态：到店支付\r\n";
				$orderInfo .= "预留至：" . date ( "Y-m-d", strtotime ( $order ['startdate'] ) ) . " 18:00\r\n";
			} else if ($order ['paid'] == 1) {
				$orderInfo .= "支付状态：微信已付款\r\n";
				$orderInfo .= "预留至：" . date ( "Y-m-d", strtotime ( $order ['enddate'] ) ) . " 12:00\r\n";
				$price -= floatval ( $order ['wxpay_favour'] );
			}
			$orderInfo .= "------------------------\r\n";
			$orderInfo .= "订单总价：" . $price . "元\r\n";
			$orderInfo .= "订单状态：" . $status [$order ['status']] . "\r\n";
			$orderInfo .= "备注：\r\n";
			$orderInfo .= "<CB>确认订单</CB>\r\n";
			$orderInfo .= $order ['remark'];
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}
		function cancel_order_text($order) {
			$status = array (
					'0' => '待确认',
					'1' => '已确认',
					'2' => '入住',
					'3' => '离店',
					'4' => '用户取消',
					'5' => '酒店取消',
					'6' => '酒店删除',
					'7' => '异常',
					'8' => '未到',
					'9' => '未支付',
					'11'=>'系统取消'
			);
			$orderInfo = '<CB>' . $order ['hname'] . '</CB><BR><BR>'; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo .= "订单号：" . $order ['orderid'] . "\r\n";
			$orderInfo .= "下单时间：" . date ( 'Y-m-d H:i:s', $order ['order_time'] ) . "\r\n";
			$orderInfo .= "------------------------\r\n";
			$orderInfo .= "入住人：" . $order ['name'] . "\r\n";
			$orderInfo .= "联系电话：" . $order ['tel'] . "\r\n";
			$orderInfo .= "房型：" . $order ['first_detail'] ['roomname'] . "\r\n";
			$orderInfo .= "价格代码：" . $order ['first_detail'] ['price_code_name'] . "\r\n";
			$orderInfo .= "房间数：" . $order ['roomnums'] . "\r\n";
			$orderInfo .= "入住时间：" . date ( "Y-m-d", strtotime ( $order ['startdate'] ) ) . "\r\n";
			$orderInfo .= "离店时间：" . date ( "Y-m-d", strtotime ( $order ['enddate'] ) ) . "\r\n";
			$price = $order ['price'];
			if ($order ['paid'] == 0) {
				$orderInfo .= "支付状态：到店支付\r\n";
			} else if ($order ['paid'] == 1) {
				$orderInfo .= "支付状态：微信已付款\r\n";
				$price -= floatval ( $order ['wxpay_favour'] );
			}
			$orderInfo .= "------------------------\r\n";
			$orderInfo .= "订单总价：" . $price . "元\r\n";
			$orderInfo .= "订单状态：" . $status [$order ['status']] . "\r\n";
			$orderInfo .= "备注：\r\n";
			$orderInfo .= "<CB>取消订单</CB>\r\n";
			$orderInfo .= $order ['remark'];
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}

		//房间微服务 接单
		function roomservice_ensure_order($order) {

			$addr = '';
			if($order['type'] == 1){//房间号
				$addr = '房间号：';
			}elseif($order['type'] == 2){//桌号
				$addr = '桌号：';
			}elseif($order['type'] == 3){
				$addr = '联系方式：' . $order['consignee'] . ' ' .$order['phone']. "\r\n";
				$addr .= '地址：';
			}
			$pay_name = '';
			$this->load->model('roomservice/roomservice_orders_model');
			$ordermodel = $this->roomservice_orders_model;
			if($order['pay_way']==3){
				$pay_name = '线下支付-'.$ordermodel->os_array[$order['order_status']];
			}else{
				$pay_name = $ordermodel->pay_way_array[$order['pay_way']].'支付-'.$ordermodel->ps_array[$order['pay_status']];
			}
			/*if($order['pay_way']==1){
			 $pay_name = '微信支付 - ' . (($order['pay_status'] == 1)?'已支付':($order['pay_status'] == 2)?'未支付':'已退款');//如果是这个 表示已经支付
			 }elseif($order['pay_way']==2){
			 $pay_name = '储值支付 - '. (($order['pay_status'] == 1)?'已支付':'');
			 }elseif($order['pay_way']==3){
			 $pay_name = '线下支付 - ' .'已确认';
			 }*/
			//$orderInfo = '<CB>发觉发掘的就' . '</CB>'. "\r\n"; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo = '顾客消费小票' . "\r\n";
			$orderInfo .= $addr . $order ['address'] . "\r\n";
			$orderInfo .= $pay_name . "\r\n";
			$orderInfo .= "订单号：" . $order['order_sn'] . "\r\n";
			$orderInfo .= date('Y-m-d H:i:s') . "\r\n";
			$orderInfo .= "-------------------------------\r\n";
			$orderInfo .= "商品名称	     数量  单价   金额 "."\r\n";
			if(!empty($order['order_detail'])){
				foreach($order['order_detail'] as $k=>$v){
					//	$length = mb_strlen($v['goods_name'],'utf-8');
					$goods_name = $v['goods_name'];//$length>20?mb_substr($v['goods_name'],0,20,'utf-8').'…':$v['goods_name'];
					$spec_name = isset(explode(':',$v['spec_name'])[1])?explode(':',$v['spec_name'])[1]:'';
					$orderInfo .= $goods_name .' ('.$spec_name.')' ."\r\n";
					//$kong = str_repeat(' ',(14 - $this->cal_count($spec_name)));
					$sec_kong = str_repeat(' ',(8-$this->cal_count($v['goods_price'])));
					$orderInfo .= '	      ' . $v['goods_num'].$sec_kong.$v['goods_price'] .$sec_kong.number_format($v['goods_price']*$v['goods_num'],2,'.','')."\r\n";
					//	$orderInfo .= $v['spec_name'] ."\r\n";
				}
			}
			$orderInfo .= "-------------------------------\r\n";
			$orderInfo .= "备注：" . $order['note'] . "\r\n";
			$orderInfo .= "总金额：" . $order['row_total'] . "元\r\n";
			$orderInfo .= "优惠：" . $order['discount_money'] . "元\r\n";
			$orderInfo .= "实付：" . $order['sub_total'] . "元\r\n";
			$orderInfo .= "待付：" . ($order['sub_total'] - $order['pay_money']) . "元\r\n";
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}
		//房间微服务 新订单
		function roomservice_new_order($order) {
			$addr = '';
			if($order['type'] == 1){//房间号
				$addr = '房间号：';
			}elseif($order['type'] == 2){//桌号
				$addr = '桌号：';
			}elseif($order['type'] == 3){
				$addr = '联系方式：' . $order['consignee'] . ' ' .$order['phone']. "\r\n";
				$addr .= '地址：';
			}
			$pay_name = '';
			$this->load->model('roomservice/roomservice_orders_model');
			$ordermodel = $this->roomservice_orders_model;
			if($order['pay_way']==3){
				$pay_name = '线下支付-'.$ordermodel->os_array[$order['order_status']];
			}else{
				$pay_name = $ordermodel->pay_way_array[$order['pay_way']].'支付-'.$ordermodel->ps_array[$order['pay_status']];
			}
			//$orderInfo = '<CB>' . $order ['hname'] . '</CB><BR><BR>'; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo = '新订单通知' . "\r\n";
			$orderInfo .= $addr . $order ['address'] . "\r\n";
			$orderInfo .= $pay_name . "\r\n";
			$orderInfo .= "订单号：" . $order['order_sn'] . "\r\n";
			$orderInfo .= date('Y-m-d H:i:s') . "\r\n";
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}
		//房间微服务 催单
		function roomservice_remind_order($order) {
			$addr = '';
			if($order['type'] == 1){//房间号
				$addr = '房间号：';
			}elseif($order['type'] == 2){//桌号
				$addr = '桌号：';
			}elseif($order['type'] == 3){
				$addr = '联系方式：' . $order['consignee'] . ' ' .$order['phone']. "\r\n";
				$addr .= '地址：';
			}
			$pay_name = '';
			$this->load->model('roomservice/roomservice_orders_model');
			$ordermodel = $this->roomservice_orders_model;
			if($order['pay_way']==3){
				$pay_name = '线下支付-'.$ordermodel->os_array[$order['order_status']];
			}else{
				$pay_name = $ordermodel->pay_way_array[$order['pay_way']].'支付-'.$ordermodel->ps_array[$order['pay_status']];
			}
			//$orderInfo = '<CB>' . $order ['hname'] . '</CB><BR><BR>'; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo = '顾客催单通知' . "\r\n";
			$orderInfo .= $addr . $order ['address'] . "\r\n";
			$orderInfo .= $pay_name . "\r\n";
			$orderInfo .= "订单号：" . $order['order_sn'] . "\r\n";
			$orderInfo .= date('Y-m-d H:i:s') . "\r\n";
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}

		//快乐付 新订单
		function okpay_new_order($order) {
			$pay_type = array('1'=>'微信支付','2'=>'余额支付','3'=>'设备付款','11'=>'威富通支付');
			$orderInfo = '<C>'.$order['hotel_name'].'</C>'. "\r\n";
			$orderInfo .= '<C>顾客消费小票</C>';
			$orderInfo .= '<CB>'.$pay_type[$order['pay_way']] . '-已支付</CB>'. "\r\n"; // 标题字体如需居中放大,就需要用标签套上
			$orderInfo .= "-------------------------------\r\n";
			$orderInfo .= "订单号：" . $order['out_trade_no'] . "\r\n";
			$orderInfo .= "支付时间：" . date('Y-m-d H:i:s',$order['pay_time']) . "\r\n";
			$orderInfo .= "支付场景：" . $order['pay_type_desc'] . "\r\n";
			$orderInfo .= "消费金额：" . $order['money'] . "元\r\n";
			$orderInfo .= "优惠金额：" . $order['discount_money'] . "元\r\n";
			$orderInfo .= "实付金额：" . $order['pay_money'] . "元\r\n";
			$orderInfo .= "-------------------------------\r\n";
			$orderInfo .= "\r\n\r\n";
			$orderInfo .= "顾客签名：_________________". "\r\n";
			$orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
			return $orderInfo;
		}

        //后台取号
        public function appointment_offer_text($order)
        {
            $orderInfo = '<C>'.$order['title'].'</C>'. "\r\n";
            $orderInfo .= '<CB>'.$order['name'].'</CB>'. "\r\n";
            $orderInfo .= '<C>'.$order['text'].'</C>'. "\r\n"; // 标题字体如需居中放大,就需要用标签套上
            $orderInfo .= "\r\n";
            $orderInfo .= '<C>'.$order['time'].'</C>';
            $orderInfo .= "\r\n\r\n\r\n\r\n\r\n";
            return $orderInfo;
        }

		//计算个数 返回需要补全空格数
		//$num 汉字占位限制 一个汉字=两个空格占位
		public function cal_count($str,$num = 6){
			preg_match_all("/[0-9\.\-\_]{1}/",$str,$arrNum);
			preg_match_all("/[a-zA-Z]{1}/",$str,$arrAl);
			preg_match_all("/([\x{4e00}-\x{9fa5}]){1}/u",$str,$arrCh);
			$str_count = 0;//总的空格数 一个数字或字母=0.5个汉字占位
			if(isset($arrNum[0]) && !empty($arrNum[0])){
				$str_count += count($arrNum[0]);
			}elseif(isset($arrAl[0]) && !empty($arrAl[0])){
				$str_count += count($arrAl[0]);
			}elseif(isset($arrCh[0]) && !empty($arrCh[0])){
				$str_count += count($arrCh[0]) * 2;
			}
			return  $str_count;
		}
	}
	function sendSelfFormatMessage($msgInfo) {
		$client = new feie_print_v1\HttpClient ( FEIE_HOST, FEIE_PORT );
		if (! $client->post ( '/FeieServer/printSelfFormatOrder', $msgInfo )) { // 提交失败
			return 'faild';
		} else {
			return $client->getContent ();
		}
	}
	function wp_print($msgInfo){
		$client = new feie_print_v2\HttpClient(FEIE_HOST_V2,FEIE_PORT_V2);
		if(!$client->post('/FeieServer/printOrderAction',$msgInfo)){
			return 'faild';
		}
		else{
			return $client->getContent ();
		}

	}
	function sendDefaultFormatMessage($msgInfo) {
		$client = new HttpClient ( FEIE_HOST, FEIE_PORT );
		if (! $client->post ( '/FeieServer/printDefalutFormatOrder', $msgInfo )) { // 提交失败
			return 'faild';
		} else {
			return $client->getContent ();
		}
	}

	// 查询打印机状态(根据DEVICE_NO 查询)
	function queryPrinterStatus($device_no) {
		$client = new HttpClient ( FEIE_HOST, FEIE_PORT );
		if (! $client->get ( '/FeieServer/queryprinterstatus?clientCode=' . $device_no )) { // 请求失败
			return 'faild';
		} else {
			$result = $client->getContent ();
			echo $result;
			return $result;
		}
	}
	function queryOrderNumbersByTime($device_no, $date) {
		$msgInfo = array (
				'clientCode' => $device_no,
				'date' => $date
		);
		$client = new HttpClient ( FEIE_HOST, FEIE_PORT );
		if (! $client->post ( '/FeieServer/queryorderinfo', $msgInfo )) { // 提交失败
			return 'faild';
		} else {
			$result = $client->getContent ();
			echo $result;
			return $result;
		}
	}

