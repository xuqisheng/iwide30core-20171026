<?php
/*
 * 分账
 * date 2017-06-28
 * author chenjunyu
 */
use App\services\soma\SeparateBillingService;
defined('BASEPATH') OR exit('No direct script access allowed');
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
        if(ENVIRONMENT === 'production'){
	        $arrow_ip = array('118.178.228.168','118.178.133.170','114.55.234.45');//只允许服务器自动访问，不能手动
	        if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
	            exit('非法访问！');
	        }
	    }else{
	    	return true;
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
    	// 上锁
    	$ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay_check');
            exit('FAILURE!');
        }
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        MYLOG::w('info:开始订单状态同步的脚本', 'iwidepay_check');
        $this->load->model('iwidepay/Iwidepay_model');
        //查出需同步的状态的全部订单,按模块分组返回
        $enddate = date('Y-m-d 00:00:00');
        $orders = $this->Iwidepay_model->get_sync_orders('',$enddate);
        if(empty($orders)){
        	MYLOG::w('err:data is empty', 'iwidepay_check');
        	//释放锁
        	$this->redis_lock('delete');
        	exit('无可同步的订单数据');
        }
        foreach ($orders as $k => $order) {
        	//同步分销状态
        	$res = $this->sync_dist($order);

	        //分模块同步订单完结状态
	        switch ($order['module']) {
	        	case 'hotel':
	        		//订房离店
	        		$this->sync_hotel($order);
	        		break;
	        	case 'soma':
	        		//商城单店：不可退款，集团：已核销
	        		$this->sync_soma($order);
	        		break;
	        	case 'dc':
	        		//快乐送订单完结
	        		$this->sync_dc($order);
	        		break;
	        	case 'vip':
	        		//会员
	        		$this->sync_vip($order);
	        		break;
	        	case 'okpay':
	        		//快乐付
	        		$this->sync_okpay($order);
	        		break;
	        	case 'ticket':
	        		//预约核销
	        		$this->sync_ticket($order);
	        		break;
	        	default:
	        		# code...
	        		break;
	        }
	    }

        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('info:结束订单状态同步的脚本', 'iwidepay_check');
        echo '订单状态同步完毕';
    }

    /*
	 * 同步分销数据
	 */
	protected function sync_dist($params){
		$dist_amts = 0;	
		if($params['module']=='hotel'){
			//1.按主单查
			$grade_entity_all_orderid = $this->Iwidepay_model->get_single_grade_base_all($params['inter_id'],$params['order_no'],'orderid');
			MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' hotel_dist by orderid-'.json_encode($grade_entity_all_orderid), 'iwidepay_check');
			$dist_amts = $this->deal_grades($params,$grade_entity_all_orderid,array());
			//2.按子单查，先查出子订单号
			$itemids = $this->Iwidepay_model->get_hotel_order_items($params['order_no']);
			if(!empty($itemids)){
				foreach ($itemids as $k => $itemid) {
					$grade_entity_all = $this->Iwidepay_model->get_single_grade_base_all($params['inter_id'],$itemid['id']);
					MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].'hotel_dist by itemid_all-'.json_encode($grade_entity_all), 'iwidepay_check');
					$grade_entity_extends = $this->Iwidepay_model->get_single_grade_base_extends($params['inter_id'],$itemid['id']);
					MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].'hotel_dist by itemid_extends-'.json_encode($grade_entity_extends), 'iwidepay_check');
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
						if(is_numeric($dist_amts)){
							$dist_amts += $dist_amt;
						}else{
							$dist_amts = $dist_amt;
						}
					}else{
						continue;
					}
				}
			}else{
				return true;
			}
		}else{
			$grade_entity_all = $this->Iwidepay_model->get_single_grade_base_all($params['inter_id'],$params['order_no']);
			MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' other_dist by all-'.json_encode($grade_entity_all), 'iwidepay_check');
			$grade_entity_extends = $this->Iwidepay_model->get_single_grade_base_extends($params['inter_id'],$params['order_no']);
			MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].' other_dist by extends-'.json_encode($grade_entity_extends), 'iwidepay_check');
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
		MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].'-'.$dist_amts.' dist_amts update fail','iwidepay_check');
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
		}
		$dist_amts += $all_amt;
		$extends_amt = 0;
		foreach ($grade_entity_extends as $ke => $ve) {
			if($ve['saler'] >0){
				/*
				 * 泛分销不需要查分销员是否存在
				 */
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
		}
		$dist_amts += $extends_amt;
		return $dist_amts;
	}

	/*
	 * 同步订房订单状态
	 */
	protected function sync_hotel($params){
		$s_status = 0;//离店完结状态
		$f_price = 0;
		$order = $this->Iwidepay_model->get_hotel_order($params['order_no']);
		if(!empty($order)){
			//handled=1表明该订单包含所有子订单都已完结
			if($order['handled']==1){
				if($order['status']==3){
					$s_status = 1;
				}
				$res = $this->Iwidepay_model->get_hotel_order_items($params['order_no']);
				if(!empty($res)){
					foreach ($res as $kr => $vr) {
						//今天完结的不同步
						if(strtotime($vr['leavetime'])>=strtotime(date('Y-m-d 00:00:00'))){
							return true;
						}

						if($vr['istatus']==3){
							//记录订单最终金额
							$s_status = 1;
							$f_price += $vr['iprice']*100;
						}
					}
				}
			}
		}	
		if($s_status==1){
			$res = $this->Iwidepay_model->update_transfer_status($params['inter_id'],$params['module'],$params['order_no'],2);
			if(!$res){
				MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' transfer_status update fail','iwidepay_check');
				return false;
			}
			//判断是否开启了现付结算
			// $this->load->model('iwidepay/Iwidepay_clears_model');
   //  		$open_offline = $this->Iwidepay_clears_model->get_configs($params['inter_id'],'synchro_hotel_order');
   //  		MYLOG::w('info:synchro_hotel_order config_'.$params['inter_id'].'-'.json_encode($open_offline),'iwidepay_check');
   //  		if(!empty($open_offline['value'])&&$open_offline['value']==1){
				//更新同步订单最终金额
				$res = $this->Iwidepay_model->edit_order_amt($params['inter_id'],$params['module'],$params['order_no'],$f_price);
				if(!$res){
					MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].':'.$f_price.' order_amt update fail','iwidepay_check');
					return false;
				}
			// }
		}
		if($order['handled']==1){
			//完结，更新分账状态为2待分,订单完结状态为1已完结
			$res = $this->Iwidepay_model->update_handled_status($params['inter_id'],$params['module'],$params['order_no'],1);
			if(!$res){
				MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' handled_status update fail','iwidepay_check');
				return false;
			}
		}
	}

	/*
	 * 同步商城订单状态
	 */
	protected function sync_soma($params){
		$sbs = SeparateBillingService::getInstance();
		$refund_status_arr = $sbs->getOrderRefundInfo($params['inter_id'], array($params['order_no']));
		MYLOG::w('info:soma_refund-'.$params['inter_id'].'-'.$params['order_no'].'|'.json_encode($refund_status_arr),'iwidepay_check');
		if(!empty($refund_status_arr)&&$refund_status_arr['status']==1){
			foreach ($refund_status_arr['data']['info'] as $kr => $vr) {
				//更新商城订单状态
				$res = $this->Iwidepay_model->update_refund_status($params['inter_id'],$params['module'],$kr,$vr['refund_status']);
				if(!$res){
					MYLOG::w('err:'.$params['inter_id'].'-'.$kr.' update refund_status fail','iwidepay_check');
					return false;
				}
			}
		}else{
			MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' get refund_status fail，'.json_encode($refund_status_arr),'iwidepay_check');
			return false;
		}
		//返回该订单的已核销的票券记录，示例：一个订单有5张票券，已核销3张，则返回该三张票券的核销记录，未核销的不返回
		$data = $sbs->getCanPaySeparateBilling(array($params['order_no']));
		MYLOG::w('info:soma_bill-'.$params['inter_id'].'-'.$params['order_no'].'|'.json_encode($data),'iwidepay_check');
		if(!empty($data)){
			foreach ($data as $key => $value) {
				//今天核销的不同步
				if(strtotime($value['bill_time'])>=strtotime(date('Y-m-d 00:00:00'))){
					continue;
				}

				$data[$key]['handle_status'] = 1;
				$data[$key]['add_time'] = date('Y-m-d H:i:s');
			}
			$order_qty = $data[0]['order_qty'];//订购总量
			//更新订单表订购总量
			$res = $this->Iwidepay_model->update_bill_num($params['inter_id'],$params['module'],$params['order_no'],$order_qty);
			if(!$res){
				MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' update bill_num fail','iwidepay_check');
				return false;
			}
			//记录商城票券核销记录,批量
			$res = $this->Iwidepay_model->save_bill_record($data);
			if(!$res){
				MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' insert bill_record fail,'.json_encode($data),'iwidepay_check');
				return false;
			}
		}
		//查询判断bill_record记录数等于bill_num，则该订单已完结，handled标记为1
		$bill_record_num = $this->Iwidepay_model->get_bill_record_num($params['inter_id'],$params['order_no']);
		$order = $this->Iwidepay_model->get_iwidepay_order($params['order_no']);
		if(!empty($order)&&!empty($bill_record_num)){
			if($bill_record_num==$order['bill_num']){
				//全部核销完毕
				$res = $this->Iwidepay_model->update_handled_status($params['inter_id'],$params['module'],$params['order_no'],1);
				if(!$res){
					MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' handled_status update fail','iwidepay_check');
					return false;
				}
			}
		}
	}

	/*
	 * 同步快乐送订单状态
	 */
	protected function sync_dc($params){
		$order = $this->Iwidepay_model->get_dc_order($params['order_no']);
		MYLOG::w('info:dc_order-'.$params['inter_id'].'-'.$params['order_no'].'|'.json_encode($order),'iwidepay_check');
		if(!empty($order)){
			if($order['order_status']==20){
				//完结，更新分账状态为2待分
				$tf_status = $this->Iwidepay_model->get_iwidepay_order($params['order_no']);
				if(!empty($tf_status)&&$tf_status['transfer_status']==1){
					$res = $this->Iwidepay_model->update_transfer_status($params['inter_id'],$params['module'],$params['order_no'],2);
					if(!$res){
						MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' transfer_status update fail','iwidepay_check');
						return false;
					}
				}
			}
			if(in_array($order['order_status'],array(20,25,26,27))){
				//完结
				$res = $this->Iwidepay_model->update_handled_status($params['inter_id'],$params['module'],$params['order_no'],1);
				if(!$res){
					MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' handled_status update fail','iwidepay_check');
					return false;
				}
			}
		}
	}

	/*
	 * 同步会员订单状态
	 */
	protected function sync_vip($params){
		$this->load->model('membervip/common/Member_cross',"MemberModel");
        $sales = $this->MemberModel->get_sales_info_by_orderid($params['inter_id'],$params['order_no']);
        MYLOG::w('info:'.$params['inter_id'].'-'.$params['order_no'].'|'.json_encode($sales),'iwidepay_check');
        if(!empty($sales)&&!empty($sales['hotel_id'])){
        	//更新会员订单hotel_id所属
        	$res = $this->Iwidepay_model->update_order_hotelid($params['inter_id'],$params['module'],$params['order_no'],$sales['hotel_id']);
        	if(!$res){
        		MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].'|'.json_encode($sales).' vip_order hotel_id update fail','iwidepay_check');
        	}
        }
        //默认完结
		$res = $this->Iwidepay_model->update_handled_status($params['inter_id'],$params['module'],$params['order_no'],1);
		if(!$res){
			MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' handled_status update fail','iwidepay_check');
			return false;
		}
	}

	/*
	 * 同步快乐付订单状态
	 */
	protected function sync_okpay($params){
        //默认完结
		$res = $this->Iwidepay_model->update_handled_status($params['inter_id'],$params['module'],$params['order_no'],1);
		if(!$res){
			MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' handled_status update fail','iwidepay_check');
			return false;
		}
	}

	/*
	 * 同步预约核销订单状态
	 */
	protected function sync_ticket($params){
		$order = $this->Iwidepay_model->get_ticket_order($params['order_no']);
		MYLOG::w('info:ticket_order-'.$params['inter_id'].'-'.$params['order_no'].'|'.json_encode($order),'iwidepay_check');
		if(!empty($order)){
			if($order['order_status']==20){
				//完结，更新分账状态为2待分
				$tf_status = $this->Iwidepay_model->get_iwidepay_order($params['order_no']);
				if(!empty($tf_status[0])&&$tf_status[0]['transfer_status']==1){
					$res = $this->Iwidepay_model->update_transfer_status($params['inter_id'],$params['module'],$params['order_no'],2);
					if(!$res){
						MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' transfer_status update fail','iwidepay_check');
						return false;
					}
				}
			}
			if(in_array($order['order_status'],array(20,25,26,27))){
				//完结
				$res = $this->Iwidepay_model->update_handled_status($params['inter_id'],$params['module'],$params['order_no'],1);
				if(!$res){
					MYLOG::w('err:'.$params['inter_id'].'-'.$params['order_no'].' handled_status update fail','iwidepay_check');
					return false;
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
