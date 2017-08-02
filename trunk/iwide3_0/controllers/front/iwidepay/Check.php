<?php
/*
 * 分账
 * date 2017-06-28
 * author chenjunyu
 */
use App\services\soma\SeparateBillingService;

class Check extends MY_Controller {
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
    protected function redis_lock($type='set' ,$key='synchro_lock' ,$value='lock'){
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

    //同步订单状态
    public function synchro(){
    	$this->check_arrow();
    	//上锁
    	// $ok = $this->redis_lock();
     //    if(!$ok){
     //        //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
     //        MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_check');
     //        exit('FAILURE!');
     //    }
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        MYLOG::w('开始订单状态同步的脚本', 'iwidepay_check');
        $this->load->model('iwidepay/Iwidepay_model');
        //查出需同步的状态的全部订单,按模块分组返回
        $enddate = date('Y-m-d 00:00:00');
        $orders = $this->Iwidepay_model->get_sync_orders('',$enddate);

        foreach ($orders as $k => $order) {
        	//同步分销状态
        	$res = $this->sync_dist($order);

	        //分模块同步订单完结状态
	        switch ($order['module']) {
	        	case 'hotel':
	        		//离店
	        		$this->sync_hotel($order);
	        		break;
	        	case 'soma':
	        		//单店：不可退款，集团：已核销
	        		$this->sync_soma($order);
	        		break;
	        	case 'dc':
	        		//订单完结
	        		$this->sync_dc($order);
	        		break;
	        	default:
	        		# code...
	        		break;
	        }
	    }

        //释放锁
        // $this->redis_lock('delete');
        MYLOG::w('结束订单状态同步的脚本', 'iwidepay_check');
        echo '订单状态同步完毕';
    }

    /*
	 * 同步分销数据
	 */
	protected function sync_dist($params){
		$dist_amts = 0;	
		if($params['module']=='hotel'){
			//查出子订单号
			$itemids = $this->Iwidepay_model->get_hotel_order_items($params['order_no']);
			if(!empty($itemids)){
				$item_num = count($itemids);
				$inum = 0;
				foreach ($itemids as $k => $itemid) {
					$grade_entity_all = $this->Iwidepay_model->get_single_grade_base_all($params['inter_id'],$itemid['id']);
					$grade_entity_extends = $this->Iwidepay_model->get_single_grade_base_extends($params['inter_id'],$itemid['id']);
					if(!empty($grade_entity_all)||!empty($grade_entity_extends)){
						$dist_amt = $this->deal_grades($params,$grade_entity_all,$grade_entity_extends);
						//更新分账订单分销状态，结束
						if(!is_numeric($dist_amt)){
							if($dist_amt=='dist_status'){
								return true;
							}
							MYLOG::w('info:'.$dist_amt,'iwidepay_check');
							return false;
						}
						$dist_amts += $dist_amt;
					}else{
						$inum++;
						continue;
					}
				}
				if($inum == $item_num){
					//无分销
					return true;
				}
			}else{
				return true;
			}
		}else{
			$grade_entity_all = $this->Iwidepay_model->get_single_grade_base_all($params['inter_id'],$params['order_no']);
			$grade_entity_extends = $this->Iwidepay_model->get_single_grade_base_extends($params['inter_id'],$params['order_no']);
			if(!empty($grade_entity_all)||!empty($grade_entity_extends)){
				$dist_amt = $this->deal_grades($params,$grade_entity_all,$grade_entity_extends);
				//更新分账订单分销状态，结束
				if(!is_numeric($dist_amt)){
					if($dist_amt=='dist_status'){
						return true;
					}
					MYLOG::w('info:'.$dist_amt,'iwidepay_check');
					return false;
				}
				$dist_amts = $dist_amt;
			}else{
				return true;
			}
		}
		//更新分账订单的分销金额
		$res = $this->Iwidepay_model->edit_order_dist($params['inter_id'],$params['module'],$params['order_no'],2,$dist_amts);
		if($res){
			return true;
		}
		MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].'-'.$dist_amts.' dist_amts update fail','iwidepay_check');
	}

	protected function deal_grades($params,$grade_entity_all,$grade_entity_extends){
		$dist_amts = 0;
		$all_amt = 0;
		foreach ($grade_entity_all as $ka => $va) {
			if($va['saler'] >0){
				//查询分销员信息
				$this->load->model('distribute/staff_model');
				$saler_query = $this->staff_model->get_my_base_info_saler ( $params ['inter_id'], $va['saler'] );
				if(empty($saler_query) || empty($saler_query['openid']) || $saler_query['status']!= 2){
					continue;//不是分销员 或者状态不对
				}
			}
			if($va['status'] != 1 && $va['status'] != 2){
				if($va['status'] == 5 || $va['status'] == 99){
					continue;
				}
				//更新分账订单分销状态，该订单有分销
				$res = $this->Iwidepay_model->edit_order_dist($params['inter_id'],$params['module'],$params['order_no']);
				if($res){
					return 'dist_status';
				}
				return $params['inter_id'].'-'.$params['order_no'].' dist_status update fail';
			}else{
				$all_amt += $va['grade_total']*100;
			}
		}
		$dist_amts += $all_amt;
		$extends_amt = 0;
		foreach ($grade_entity_extends as $ke => $ve) {
			if($ve['saler'] >0){
				//查询分销员信息
				$this->load->model('distribute/staff_model');
				$saler_query = $this->staff_model->get_my_base_info_saler ( $params ['inter_id'], $ve['saler'] );
				if(empty($saler_query) || empty($saler_query['openid']) || $saler_query['status']!= 2){
					continue;//不是分销员 或者状态不对
				}
			}
			if($ve['status'] != 1 && $ve['status'] != 2){
				if($ve['status'] == 5 || $ve['status'] == 99){
					continue;
				}
				//更新分账订单分销状态，该订单有分销
				$this->Iwidepay_model->edit_order_dist($params['inter_id'],$params['module'],$params['order_no']);
				if($res){
					return 'dist_status';
				}
				return $params['inter_id'].'-'.$params['order_no'].' dist_status update fail';
			}else{
				$extends_amt += $ve['grade_total']*100;
			}
		}
		$dist_amts += $extends_amt;
		return $dist_amts;
	}

	/*
	 * 同步订房订单状态
	 */
	protected function sync_hotel($params){
		$order = $this->Iwidepay_model->get_hotel_order($params['order_no']);
		if(!empty($order)){
			if($order['status']==3){
				//离店，更新分账状态为2待分
				$res = $this->Iwidepay_model->update_transfer_status($params['inter_id'],$params['module'],$params['order_no'],2);
				if(!$res){
					MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' transfer_status update fail','iwidepay_check');
					return false;
				}
			}
		}
	}

	/*
	 * 同步商城订单状态
	 */
	protected function sync_soma($params){
		$sbs = SeparateBillingService::getInstance();
		$refund_status_arr = $sbs->getOrderRefundInfo($params['inter_id'], array($params['order_no']));
		if(!empty($refund_status_arr)&&$refund_status_arr['status']==1){
			foreach ($refund_status_arr['data']['info'] as $kr => $vr) {
				//更新商城订单状态
				$res = $this->Iwidepay_model->update_refund_status($params['inter_id'],$params['module'],$kr,$vr['refund_status']);
				if(!$res){
					MYLOG::w('info:'.$params['inter_id'].'-'.$kr.' update refund_status fail','iwidepay_check');
					return false;
				}
			}
		}else{
			MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' get refund_status fail，'.json_encode($refund_status_arr),'iwidepay_check');
			return false;
		}
		$data = $sbs->getCanPaySeparateBilling(array($params['order_no']));
		if(!empty($data)){
			foreach ($data as $key => $value) {
				$data[$key]['handle_status'] = 1;
				$data[$key]['add_time'] = date('Y-m-d H:i:s');
			}
			$order_qty = $data[0]['order_qty'];//订购总量
			//更新订单表订购总量
			$res = $this->Iwidepay_model->update_bill_num($params['inter_id'],$params['module'],$params['order_no'],$order_qty);
			if(!$res){
				MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' update bill_num fail','iwidepay_check');
				return false;
			}
			//记录商城票券核销记录,批量
			$res = $this->Iwidepay_model->save_bill_record($data);
			if(!$res){
				MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' insert bill_record fail,'.json_encode($data),'iwidepay_check');
				return false;
			}
		}
	}

	/*
	 * 同步快乐送订单状态
	 */
	protected function sync_dc($params){
		$order = $this->Iwidepay_model->get_dc_order($params['order_no']);
		if(!empty($order)){
			if($order['order_status']==20){
				//完结，更新分账状态为2待分
				$tf_status = $this->Iwidepay_model->get_iwidepay_order($params['order_no']);
				if(!empty($tf_status)&&($tf_status['transfer_status']==1||$tf_status['transfer_status']==8)){
					$res = $this->Iwidepay_model->update_transfer_status($params['inter_id'],$params['module'],$params['order_no'],2);
					if(!$res){
						MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' transfer_status update fail','iwidepay_check');
						return false;
					}
				}
			}
		}
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
