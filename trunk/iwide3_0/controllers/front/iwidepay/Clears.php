<?php
/*
 * 欠款抵扣、清算汇总
 * date 2017-08-29
 * author chenjunyu
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Clears extends MY_Controller {
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
    protected function redis_lock($type='set' ,$key='clears_lock' ,$value='lock'){
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

    public function handle(){
    	$this->check_arrow();
        // 上锁
        $ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__METHOD__ . ' lock fail!', 'iwidepay_clears');
            exit('FAILURE!');
        }
        MYLOG::w('info:开始清算汇总的脚本', 'iwidepay_clears');
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        $this->load->model('iwidepay/Iwidepay_clears_model');
        // 清算流程
        $hotel_advances = $this->deductible();

        //汇总流程
        $this->summary($hotel_advances);

        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('info:结束清算汇总的脚本', 'iwidepay_clears');
        exit('清算汇总完毕');
    }

    // 清算流程
    protected function deductible(){
    	//取预付订单T+1应付给门店的金额
    	$startdate = date('Y-m-d 00:00:00');
    	$enddate = date('Y-m-d 00:00:00',strtotime('+1 day'));
    	$hotel_advances = $this->Iwidepay_clears_model->get_settlement_record($startdate,$enddate);

    	foreach ($hotel_advances as $kh => $vh) {
    		$deductible_amount = $vh['amount'];
	    	//取门店最近一条未结清的结余金额
	    	$last_surplus = $this->Iwidepay_clears_model->get_last_surplus_record($vh['inter_id'],$vh['hotel_id']);
	    	//汇总抵扣金额
	    	$last_surplus_id = 0;
	    	if(!empty($last_surplus['amount'])){
	    		$deductible_amount += $last_surplus['amount'];
	    		$last_surplus_id = $last_surplus['id'];
	    	}

	    	//查出酒店的所有未结清欠款记录
        	$debt_records = $this->Iwidepay_clears_model->get_debt_record($vh['inter_id'],$vh['hotel_id']);

        	MYLOG::w('info:'.json_encode($vh).'|deductible_amount-'.$deductible_amount.'|debt_records-'.json_encode($debt_records),'iwidepay_clears');

        	//抵扣逻辑
        	$return = $this->deductible_process($deductible_amount,$debt_records);
        	//记录结余金额和是否有未结清的欠款记录
        	$hotel_advances[$kh]['balance_amount'] = $return['amount'];
        	$hotel_advances[$kh]['is_settle'] = $return['is_settle'];
        	$hotel_advances[$kh]['debt_records'] = $return['debted_records'];

        	//没有欠款跳过该条汇总
        	if(empty($debt_records)){
        		continue;
        	}

        	//判断结余金额
	    	if($return['amount']>=0){
	    		//生成结余记录
	    		if($return['is_settle']==0){
	    			$res = $this->Iwidepay_clears_model->save_residual_record($vh['inter_id'],$vh['hotel_id'],0,$last_surplus_id);
	    		}else{
	    			$res = $this->Iwidepay_clears_model->save_residual_record($vh['inter_id'],$vh['hotel_id'],$return['amount'],$last_surplus_id);
	    		}
	    		if($res!==true){
					MYLOG::w('err:save_residual_record fail-'.json_encode($res),'iwidepay_clears');
				}
	    	}else{
	    		MYLOG::w('err:balance_amount<0 -'.json_encode($vh).'-'.json_encode($debt_records),'iwidepay_clears');
	    	}
	    }
	    return $hotel_advances;
    }

    //抵扣逻辑
    protected function deductible_process($deductible_amount,$debt_records){
    	//欠款分类
    	$refund_records = array();
    	$order_records = array();
    	$base_records = array();
    	$orderReward_records = array();
    	$extraReward_records = array();
    	foreach ($debt_records as $kd => $vd) {
    		switch ($vd['order_type']) {
    			case 'refund':
    				$refund_records[] = $vd;
     				break;
    			case 'order':
    				$order_records[] = $vd;
    				break;
    			case 'base_pay':
    				$base_records[] = $vd;
    				break;
    			case 'orderReward':
    				$orderReward_records[] = $vd;
    				break;
    			case 'extraReward':
    				$extraReward_records[] = $vd;
    				break;
    			default:
    				# code...
    				break;
    		}
    	}
    	
    	//记录是否有未结清的欠款记录
    	$is_settle = 0;
    	$debted_records = array();
    	//垫付退款抵扣逻辑
    	$return = $this->deductible_handle($deductible_amount,$refund_records);
    	if($return['is_settle']==1){
    		$is_settle = 1;
    	}
    	$debted_records = array_merge($debted_records,$return['debted_records']);

    	if($return['amount']>0){
    		//首单奖励抵扣逻辑
    		$return = $this->deductible_handle($return['amount'],$orderReward_records);
    		if($return['is_settle']==1){
    			$is_settle = 1;
    		}
    		$debted_records = array_merge($debted_records,$return['debted_records']);

    		if($return['amount']>0){
    			//额外奖励抵扣逻辑
    			$return = $this->deductible_handle($return['amount'],$extraReward_records);
    			if($return['is_settle']==1){
    				$is_settle = 1;
    			}
    			$debted_records = array_merge($debted_records,$return['debted_records']);

	    		if($return['amount']>0){
	    			//订单分成抵扣逻辑
	    			$return = $this->deductible_handle($return['amount'],$order_records);
	    			if($return['is_settle']==1){
    					$is_settle = 1;
    				}
    				$debted_records = array_merge($debted_records,$return['debted_records']);

	    			if($return['amount']>0){
	    				//基础月费抵扣逻辑
	    				$return = $this->deductible_handle($return['amount'],$base_records);
	    				if($return['is_settle']==1){
    						$is_settle = 1;
    					}
    					$debted_records = array_merge($debted_records,$return['debted_records']);
	    			}
	    		}
    		}
    	}
    	return array('amount'=>$return['amount'],'is_settle'=>$is_settle,'debted_records'=>$debted_records);
    }

    //抵扣处理
    protected function deductible_handle($amount,$records){
    	$msg = array('amount'=>0,'is_settle'=>0,'debted_records'=>array());
    	if($amount>0&&!empty($records)){
    		foreach ($records as $kr => $vr) {
    			if($amount==0){
    				break;
    			}
    			if(($amount-$vr['amount'])>=0){
    				$amount = $amount-$vr['amount'];
    				//更新该欠款记录为已结清
    				$res = $this->Iwidepay_clears_model->update_debt_data(array('id'=>$vr['id']),array('status'=>1,'up_time'=>date('Y-m-d H:i:s')));
    				if($res!==true){
    					MYLOG::w('err:update debt_status fail-'.json_encode($res),'iwidepay_clears');
    				}
    				$msg['debted_records'][] = $vr;
    			}else{
    				$msg['is_settle'] = 1;
    			}
    		}
    	}
    	$msg['amount'] = $amount;
    	return $msg;
    }

    //汇总流程
    protected function summary($hotel_advances){
    	$group_total_amounts = array();
    	$jfk_total_amount = 0;
		$debt_ids = array();
    	MYLOG::w('info:summary_hotel_advances-'.json_encode($hotel_advances),'iwidepay_clears');
    	foreach ($hotel_advances as $ka => $va) {
    		if(empty($va['debt_records'])){
    			continue;
    		}
    		$debt_ids_va = array();
    		foreach ($va['debt_records'] as $kd => $vd) {
    			if($vd['order_type']=='order'){
	    			$ext_info_arr = json_decode($vd['ext_info'],true);
	    			//集团汇总
	    			$group_total_amounts[$vd['inter_id']]['total_amount'] += $ext_info_arr['group_amount'];
	    			$group_total_amounts[$vd['inter_id']]['debt_ids'][] = $vd['id'];
	    			//金房卡汇总
	    			$jfk_total_amount += $ext_info_arr['jfk_amount'];
	    			$jfk_total_amount += $ext_info_arr['dist_amount'];
	    			//关联id汇总
    				$debt_ids[] = $vd['id'];
    				$debt_ids_va[] = $vd['id'];
    			}elseif($vd['order_type']=='base_pay'){
    				$jfk_total_amount += $vd['amount'];
	    			//关联id汇总
    				$debt_ids[] = $vd['id'];
    				$debt_ids_va[] = $vd['id'];
    			}elseif ($vd['order_type']=='orderReward') {
    				$jfk_total_amount += $vd['amount'];
    				//关联id汇总
    				$debt_ids[] = $vd['id'];
    				$debt_ids_va[] = $vd['id'];
    			}elseif ($vd['order_type']=='extraReward') {
    				$jfk_total_amount += $vd['amount'];
    				//关联id汇总
    				$debt_ids[] = $vd['id'];
    				$debt_ids_va[] = $vd['id'];
    			}
    		}

    		//门店汇总
			//更新门店转账汇总记录金额
			$res = $this->Iwidepay_clears_model->handle_settlement_record($va,$va['is_settle'],$debt_ids_va);
    		if($res!==true){
				MYLOG::w('err:handle settlement_record fail-'.json_encode($res),'iwidepay_clears');
			}
    	}
    	MYLOG::w('info:group_total_amounts-'.json_encode($group_total_amounts),'iwidepay_clears');
    	foreach ($group_total_amounts as $kg => $vg) {
			//更新集团转账汇总记录金额
			$res = $this->Iwidepay_clears_model->save_settlement_record($kg,0,'group',$vg['total_amount'],$vg['debt_ids']);
			if($res!==true){
				MYLOG::w('err:save settlement_record fail-'.json_encode($res),'iwidepay_clears');
			}
		}
		MYLOG::w('info:jfk_total_amount-'.$jfk_total_amount,'iwidepay_clears');
		//更新金房卡转账汇总记录金额
		$res = $this->Iwidepay_clears_model->save_settlement_record('jinfangka',0,'jfk',$jfk_total_amount,$debt_ids);
		if($res!==true){
			MYLOG::w('err:save settlement_record fail-'.json_encode($res),'iwidepay_clears');
		}  	
    }
}