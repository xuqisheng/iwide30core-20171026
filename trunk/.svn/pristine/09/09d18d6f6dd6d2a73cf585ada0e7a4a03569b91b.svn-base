<?php
class Iwidepay_model extends MY_Model{

	const TAB_IIP_O = 'iwidepay_order';
	const TAB_H = 'hotels';
	const TAB_IIP_S = 'iwidepay_split';
	const TAB_IIP_L = 'iwidepay_log';
	const TAB_HO = 'hotel_orders';
	const TAB_P = 'publics';
	const SN_PRE = 'LS';
	function __construct() {
		parent::__construct ();
	}
    
	function save_iwidepay_order($order){
		$res = $this->db->where('order_no',$order['order_no'])->get(self::TAB_IIP_O)->row_array();
		if($res){
			return true;
		}
		$this->db->trans_begin();
		$res = $this->db->insert(self::TAB_IIP_O,$order);
		if(!$res){
			$this->db->trans_rollback();
			return false;
		}
		$pk_id = $this->db->insert_id();
		$sn = 150000000+$pk_id;
		$order_sn = self::SN_PRE.$sn;
		$res = $this->db->where('id',$pk_id)->update(self::TAB_IIP_O,array('order_sn'=>$order_sn));
		if(!$res){
			$this->db->trans_rollback();
			return false;
		}
		if ($this->db->trans_status () === FALSE) {
			$this->db->trans_rollback();
			return false;
		}
		$this->db->trans_commit();
		return true;
	}

	function get_iwidepay_order($order_no){
		$this->db->where('pay_no',$order_no);
		$this->db->or_where('order_no',$order_no);
		return $this->db->get(self::TAB_IIP_O)->row_array();
	}

	function get_order_data($start_date='',$end_date=''){
        if(empty($start_date)){
            $start_date = date('Y-m-d',strtotime('-1 days'));
        }
        if(empty($end_date)){
            $end_date = date('Y-m-d');
        }
        $this->db->from(self::TAB_IIP_O.' a')->join(self::TAB_H.' b','a.inter_id=b.inter_id and a.hotel_id=b.hotel_id', 'left');
		$this->db->where(array(
			'add_time>' => $start_date,
			'add_time<=' => $end_date,
			));
		$res = $this->db->get()->result_array();
		$transfer_status = array(1=>'待定',2=>'待分',3=>'已分',4=>'异常');
		foreach ($res as $k => $v) {
			$res[$k]['transfer_status'] = $transfer_status[$v['transfer_status']];
		}
		return $res;
	}

	/**
	 * 供分销模块调用，修改订单分销金额和状态
	 */
	function edit_order_dist($inter_id,$order_no,$is_dist=1,$dist_amt=0){
		$this->load->helpers('common');
		MYLOG::w('分销调用数据：'.json_encode(func_get_args()),'iwidepay_model');
		$this->db->where(array(
			'inter_id' => $inter_id,
			'order_no' => $order_no,
			));
		$res = $this->db->get(self::TAB_IIP_O)->row_array();
		if(!$res){
			//不存在该订单
			return false;
		}
		if($res['is_dist']==3||$res['transfer_status']==3){
			//该订单已经处理
			return false;
		}
		if($dist_amt>0&&$is_dist!=2){
			//传分销金额状态，分销必须是2
			return false;
		}
		$this->db->where(array(
			'inter_id' => $inter_id,
			'order_no' => $order_no,
			));
		$data = array(
			'is_dist' => $is_dist,
			'dist_amt' => intval($dist_amt)+intval($res['dist_amt']),
			);
		return $this->db->update(self::TAB_IIP_O,$data);
	}

	function get_split_data($start_date,$end_date){
		if(empty($start_date)){
            $start_date = date('Y-m-d',strtotime('-1 days'));
        }
        if(empty($end_date)){
            $end_date = date('Y-m-d');
        }
        $this->db->from(self::TAB_IIP_S.' a')->join(self::TAB_H.' b','a.inter_id=b.inter_id and a.hotel_id=b.hotel_id', 'left');
		$this->db->where(array(
			'create_time>' => $start_date,
			'create_time<=' => $end_date,
			));
		$res = $this->db->get()->result_array();
		$type = array('group'=>'集团','hotel'=>'门店','jfk'=>'金房卡','dist'=>'分销','cost'=>'支付成本');
		foreach ($res as $k => $v) {
			$res[$k]['type'] = $type[$v['type']];
		}
		return $res;
	}

	//退款写在model里面 $order:iwidepay_order 的信息 $refund_data:退款组装数组 $order_no:原订单号
	public function refund($refund_data = array(),$order_no  = ''){
		$this->load->library('IwidePay/IwidePayApi',null,'IwidePayApi');

		$iwidepay_order = $this->get_iwidepay_order($order_no);
        if(empty($iwidepay_order)){
            $return['status'] = 2;
            $return['message'] = 'id error';
            return $return;
        }
        if($iwidepay_order['transfer_status'] == 6 || $iwidepay_order['transfer_status'] == 7){
        	MYLOG::w('分账订单已经全部退款，inter_id:'.$iwidepay_order['inter_id'].'|order_no：'.$iwidepay_order['order_no'], 'iwidepay/refund');
            $return['status'] = 2;
            $return['message'] = '分账订单已经全部退款';//这里是这个单不能再退了
            return $return;
        }
        $type = 1;
        $t_status = 0;//记录退款后的订单状态
        $leave_amount = 0;//记录正常部分退款后，剩下的订单金额
        $jfk_cost = empty($iwidepay_order['regular_jfk_cost'])?0:$iwidepay_order['regular_jfk_cost'];//记录分账时使用的费率
        if($iwidepay_order['transfer_status'] == 3 || $iwidepay_order['transfer_status']==5||$iwidepay_order['transfer_status']==9){
            if($refund_data['transAmt'] == $iwidepay_order['trans_amt'] ){//全部退
            	$type = 2;//垫付全部退款
            	$t_status = 7;
            }elseif($refund_data['transAmt'] < $iwidepay_order['trans_amt']){//部分退
            	$type = 4;//垫付部分退款
            	$t_status = 9;
            }else{
            	MYLOG::w('退款金额超了，inter_id:'.$iwidepay_order['inter_id'].'|order_no：'.$iwidepay_order['order_no'], 'iwidepay/refund');
            	$return['status'] = 2;
            	$return['message'] = '退款金额超了';//这里是这个单不能再退了
            	return $return;
            }
        }elseif($iwidepay_order['transfer_status'] == 1 || $iwidepay_order['transfer_status']==2||$iwidepay_order['transfer_status']==8){
        	$this->load->model('iwidepay/iwidepay_transfer_model');
        	$rules = $this->iwidepay_transfer_model->get_rules_by_filter(array('inter_id'=>$iwidepay_order['inter_id'],'module'=>$iwidepay_order['module'],'status'=>1));
        	if(empty($rules)){

        	}
        	foreach($rules as $rk=>$rv){
        		if($rv['hotel_id'] == $iwidepay_order['hotel_id']){
        			$jfk_cost = $rv['regular_jfk_cost'];
        			break;
        		}elseif($rv['hotel_id'] == '-1'){
        			$jfk_cost = $rv['regular_jfk_cost'];
        		}
        	}
        	if($refund_data['transAmt'] == $iwidepay_order['trans_amt'] ){//全部退
            	$type = 1;//正常全部退款
            	$t_status = 6;
            }elseif($refund_data['transAmt'] < $iwidepay_order['trans_amt']){//部分退
            	$type = 3;//正常部分退款
            	$t_status = 8;
            	$leave_amount = $iwidepay_order['trans_amt']-$refund_data['transAmt'];
            }else{
            	MYLOG::w('退款金额超了，inter_id:'.$iwidepay_order['inter_id'].'|order_no：'.$iwidepay_order['order_no'], 'iwidepay/refund');
            	$return['status'] = 2;
            	$return['message'] = '退款金额超了';
            	return $return;
            }
        }else{
        	MYLOG::w('分账状态异常，inter_id:'.$iwidepay_order['inter_id'].'|order_no：'.$iwidepay_order['order_no'], 'iwidepay/refund');
            $return['status'] = 2;
            $return['message'] = '分账状态异常';
            return $return;
        }
        if(is_numeric($jfk_cost)){

        }else{
        	$persent = str_replace('%', '', $jfk_cost);
            $jfk_cost = round($refund_data['transAmt'] * $persent / 100);
        }
		//退款先插一条退款记录
		$refund = array(
			'inter_id' =>$iwidepay_order['inter_id'],
			'hotel_id' => $iwidepay_order['hotel_id'],
			'openid' => $iwidepay_order['openid'],
			'transid' => '02',
			'merno' => IwidePayConfig::MERNO,
			'amount' => $iwidepay_order['trans_amt'],
			'refund_order_no' =>$refund_data['orderNo'],
			'refund_order_date'=>isset($refund_data['orderDate'])?$refund_data['orderDate']:date('Ymd'),
			'orig_order_no' =>$order_no,//原订单单号
			'orig_order_date' => !empty($iwidepay_order['order_date'])?$iwidepay_order['order_date']:date('Ymd'),
			'ori_pay_no' => $iwidepay_order['pay_no'],
			'type'=>$type,
			'refund_status' =>0,//退款中
			'refund_amt' => $refund_data['transAmt'],
			'module'=>$iwidepay_order['module'],
			'add_time' => date('Y-m-d H:i:s'),
			'charge'=>$jfk_cost,//退款手续费
		);
		$this->db->insert('iwidepay_refund',$refund);
		$refund_id = $this->db->insert_id();
		if(empty($refund_id)){
			echo '生成退款订单失败';
			MYLOG::w('生成退款订单失败', 'iwidepay/refund');
			die;
		}
		//装装数据
		$arr = array(
            'orderDate' => isset($refund_data['orderDate'])?$refund_data['orderDate']:date('Ymd'),
            'orderNo' => $refund_data['orderNo'],
            'requestNo' => $refund_data['requestNo'],
            'transAmt' => $refund_data['transAmt'],//单位：分
            'transId' => '02',
            'origOrderDate' => !empty($iwidepay_order['order_date'])?$iwidepay_order['order_date']:date('Ymd'),
            'origOrderNo' =>$iwidepay_order['pay_no'],
            'returnUrl' => $refund_data['returnUrl'],
           // 'notifyUrl' => 'http://www.baidu.com',
            'refundReson' => $refund_data['refundReson'],
        );
         MYLOG::w('退款数据组装请求:' . json_encode($arr), 'iwidepay/refund');
         //发起退款请求
         $data = $this->IwidePayApi->refundRequest($arr);
         MYLOG::w('退款数据返回:' . $data, 'iwidepay/refund');//要是没到这里，有可能返回验签不通过
         //解析返回
         $url = IwidePayConfig::REQUEST_URL;
         if(!empty($arr['requestUrl'])){
        	$url = $arr['requestUrl'];
         }
         $res = parse_url($url . "?" . $data);
         $arr_query = convertUrlQuery($res['query'],true);//var_dump($arr_query);die;
         if(isset($arr_query['respCode'])){
         	 //开启事务
         	 //$this->db->trans_begin();
	         if($arr_query['respCode'] === '0000'){
	         	$up_data = array();
	         	//先更新iwidepay_orders的订单状态 ，再改变refund表的退款状态
	         	if($t_status == 8){//正常部分退款
	         		$up_data = array('transfer_status'=>$t_status,'trans_amt'=>$leave_amount);
	         	}elseif($t_status == 6){//正常全部退款
	         		$up_data = array('transfer_status'=>$t_status);
	         	}
	         	if(!empty($up_data)){
	         		$order_update = $this->update_iwidepay_order($order_no,$iwidepay_order['module'],$up_data);
	         	}
	         	$this->db->where(array('id'=>$refund_id));
	         	$refund_update = $this->db->update('iwidepay_refund',array('refund_status'=>1));
	         }elseif($arr_query['respCode'] == 'P000' || $arr_query['respCode'] == '9999'|| $arr_query['respCode'] == '9997'|| $arr_query['respCode'] == '0028'){
	         	$order_update = $this->update_iwidepay_order($order_no,$iwidepay_order['module'],array('transfer_status'=>10));//异常
	         	$this->db->where(array('id'=>$refund_id));
	         	$update = $this->db->update('iwidepay_refund',array('refund_status'=>3));//异常
	         }else{
	         	$this->db->where(array('id'=>$refund_id));
	         	$update = $this->db->update('iwidepay_refund',array('refund_status'=>2));//失败
	         }
	         $return_res = $arr_query;
	         switch($iwidepay_order['module']){
	         	case 'okpay':
	         		$return_res = $this->okpay_refund($arr_query);
	         		break;
	         	case 'dc':
	         		$return_res = $this->dc_refund($arr_query);
	         		break;
	         	default :
	         		break;
	        }
	        return $return_res;
         }else{
         	//无返回
         	return false;
         }
         
	}

	/**
	 * dc_refund　订餐模块返回参数组装
	 */
	private function dc_refund($arr = array()){
		$array = array();
		if($arr['respCode'] === '0000'){
			$array['return_code'] = 'SUCCESS';
			$array['result_code'] = 'SUCCESS';
			$array['transaction_id'] = '';
			$array['refund_id'] = 'iwidepay'.time();
		}else{
			$array['return_code'] = 'FAIL';
		}
		return $array;
	}

	/**
	 * okpay_refund　订餐模块返回参数组装
	 */
	private function okpay_refund($arr = array()){
		$array = array();
		if($arr['respCode'] === '0000'){
			$array['return_code'] = 'SUCCESS';
			$array['result_code'] = 'SUCCESS';
			$array['transaction_id'] = '';
			$array['mch_id'] = '';
			$array['refund_fee'] = $arr['transAmt'];
			$array['refund_id'] = 'iwidepay'.time();
		}else{
			$array['return_code'] = 'FAIL';
		}
		return $array;
	}

	/**
	 * 支付回调log
	 */
	function save_payreturn_log($in_arr){
		return $this->db->insert(self::TAB_IIP_L,$in_arr);
	}

	/**
	 * 根据orderid查出订单信息
	 */
	function get_hotel_order($orderid){
		$this->db->where(array(
			'orderid' => $orderid,
			));
		return $this->db->get(self::TAB_HO)->row_array();
	}

	/**
	 * 更新分账订单信息
	 */
	function update_iwidepay_order($orderno,$module,$order){
		$this->db->where(array(
			'order_no' => $orderno,
			'module' => $module,
		));
		return $this->db->update(self::TAB_IIP_O,$order);	
	}

	/**
	 * 关闭订单
	 */
	function close_order($order_no,$module=''){
		$order = $this->db->where(array(
			'order_no' => $order_no,
			))->get(self::TAB_IIP_O)->row_array();
		if(!empty($order)){
			$data = array(
				'requestNo' => time().rand(10000,99999),
				'transId' => '21',
				'orderDate' => date('Ymd'),
				'orderNo' => time().rand(1000,9999),
				'origOrderNo' => $order['pay_no'],//原支付订单号
				'origOrderDate' => $order['order_date'],//原订单日期
				);
			$this->load->helpers('common');
			MYLOG::w('关闭订单发送数据：'.json_encode($data),'iwidepay_model');
			$this->load->library('IwidePay/IwidePayApi',null,'IwidePayApi');
			$res = $this->IwidePayApi->closeOrderRequest($data);
			MYLOG::w('关闭订单响应数据：'.$res,'iwidepay_model');
			//转数组
        	$res = parseQString($res);
			if(!empty($res)&&$res['respCode']!=='0000'){
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 订单查询
	 */
	public function order_query($order_no){
		$order = $this->db->where(array(
			'order_no' => $order_no,
			))->get(self::TAB_IIP_O)->row_array();
		if(!empty($order)){
			$data = array(
				'requestNo' => time().rand(10000,99999),
				'transId' => '04',
				'orderDate' => date('Ymd'),
				'orderNo' => $order['pay_no'],
				'orderDate' => $order['order_date'],//原订单日期
				);
			$this->load->helpers('common');
			MYLOG::w('查询订单发送数据：'.json_encode($data),'iwidepay_model');
			$this->load->library('IwidePay/IwidePayApi',null,'IwidePayApi');
			$res = $this->IwidePayApi->queryOrderRequest($data);
			MYLOG::w('查询订单响应数据：'.$res,'iwidepay_model');
			//转数组
        	$res = parseQString($res);
        	//return $res;
			if(!empty($res)&&$res['respCode']!=='0000'){//查询成功
				return false;
			}
			return $res;
		}
		return false;
	}

	/**
	 * 供商城模块调用，修改通票核销hotel_id
	 */
	function edit_order_sell_hotel($inter_id,$order_no,$sell_hotel_id=0){
		$this->load->helpers('common');
		MYLOG::w('商城通票调用数据：'.json_encode(func_get_args()),'iwidepay_model');
		$this->db->where(array(
			'inter_id' => $inter_id,
			'order_no' => $order_no,
			));
		$res = $this->db->get(self::TAB_IIP_O)->row_array();
		if(!$res){
			//不存在该订单
			return false;
		}
		if($res['trans_status']==3){
			//该订单已经处理
			return false;
		}
		$this->db->where(array(
			'inter_id' => $inter_id,
			'order_no' => $order_no,
			));
		$data = array(
			'sell_hotel_id' => $sell_hotel_id,
			);
		return $this->db->update(self::TAB_IIP_O,$data);
	}

	/**
	 * 查出该公众号分账状态
	 */
	function get_split_status($inter_id){
		$res = $this->db->where('inter_id',$inter_id)->get(self::TAB_P)->row_array();
		return !empty($res)?$res['split_status']:0;
	}

	/**
	 * 查出需同步的状态的全部订单,按模块分组返回
	 */
	function get_sync_orders(){
		$this->db->where_in('transfer_status',array(1,2,5));
		$this->db->where(array(
			'is_dist'=>1,
			'dist_amt'=>0,
			));
		$res = $this->db->get(self::TAB_IIP_O)->result_array();
		return $res;
	}
}
