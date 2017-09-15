<?php
/*
 * 线下订单同步回来线下分账订单表
 * date 2017-08-24
 * author situguanchen
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Ext_handle extends MY_Controller {
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
    protected function redis_lock($type='set' ,$key='_sync_offline_lock' ,$value='lock'){
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

    //同步线下订单
    public function run_offline(){
    	$this->check_arrow();
    	// 上锁
    	$ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__FUNCTION__ . ' lock fail!', 'iwidepay/sync_offline');
            exit('FAILURE!');
        }
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        MYLOG::w('info:开始订单同步的脚本', 'iwidepay/sync_offline');
        
        //先查询未完结的订单 重新同步金额和分销金额
        $this->sync_update_offline_order();
        sleep(1);	
        //同步订单
        $this->sync_offline_order();
        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('info:结束订单状态同步的脚本', 'iwidepay/sync_offline');
        echo '订单状态同步完毕';
    }

    //线下订单同步处理
    protected function sync_offline_order(){
    	$this->load->model('iwidepay/Iwidepay_model');
    	$orders = $this->Iwidepay_model->get_offline_hotel_order();
        if(empty($orders)){
        	MYLOG::w('订房表data is empty', 'iwidepay/sync_offline');
        	return false;
        }
        foreach ($orders as $k => $order) {
        	$arr = array();
        	$arr['inter_id'] = $order['inter_id'];
        	$arr['hotel_id'] = $order['hotel_id'];
        	$arr['module'] = 'hotel';
        	$arr['openid'] = $order['openid'];
        	//$arr['order_no_main'] = $order['orderid'];
        	$arr['order_no'] = $order['orderid'];
        	$arr['orig_amt'] = $order['price'] * 100;//单位是分
        	//先判断订单是否是完结状态，完结了直接计算子单金额 分销金额
        	if($order['handled'] == 1){
        		$arr['transfer_status'] = 2;//待分
        		//查询主单每个orderid的子单金额
        		$trans_amt = $this->sync_sub_hotel_order($order['orderid']);
        		$arr['trans_amt'] = $trans_amt;
        		//查询分销
        		$dist_amt = $this->sync_dist($order['inter_id'],$order['orderid'],'hotel');
        		$arr['dist_amt'] = $dist_amt;
        		$arr['handled'] = 1;
        	}else{
        		$arr['trans_amt'] = 0;
        		$arr['dist_amt'] = 0;
        		$arr['transfer_status'] = 1;//待定
        		$arr['handled'] = 0;
        	}
        	$arr['add_time'] = date('Y-m-d H:i:s');//var_dump($arr);die;
        	$this->Iwidepay_model->save_sync_offline_order($arr);// 需要加唯一索引
	    }
    }

    /*public function sync_offline_order_nobiguiyuan(){
        $this->load->model('iwidepay/Iwidepay_model');
        $orders = $this->Iwidepay_model->get_offline_hotel_order_nobiguiyuan();
        if(empty($orders)){
            MYLOG::w('订房表data is empty', 'iwidepay/sync_offline');
            return false;
        }
        foreach ($orders as $k => $order) {
            $arr = array();
            $arr['inter_id'] = $order['inter_id'];
            $arr['hotel_id'] = $order['hotel_id'];
            $arr['module'] = 'hotel';
            $arr['openid'] = $order['openid'];
            //$arr['order_no_main'] = $order['orderid'];
            $arr['order_no'] = $order['orderid'];
            $arr['orig_amt'] = $order['price'] * 100;//单位是分
            //先判断订单是否是完结状态，完结了直接计算子单金额 分销金额
            if($order['handled'] == 1){
                $arr['transfer_status'] = 2;//待分
                //查询主单每个orderid的子单金额
                $trans_amt = $this->sync_sub_hotel_order($order['orderid']);
                $arr['trans_amt'] = $trans_amt;
                //查询分销
                $dist_amt = $this->sync_dist($order['inter_id'],$order['orderid'],'hotel');
                $arr['dist_amt'] = $dist_amt;
                $arr['handled'] = 1;
            }else{
                $arr['trans_amt'] = 0;
                $arr['dist_amt'] = 0;
                $arr['transfer_status'] = 1;//待定
                $arr['handled'] = 0;
            }
            $arr['add_time'] = date('Y-m-d H:i:s');//var_dump($arr);die;
            $this->Iwidepay_model->save_sync_offline_order($arr);// 需要加唯一索引
        }
    }*/

    //线下订单更新同步状态
    protected function sync_update_offline_order(){
    	$this->load->model('iwidepay/Iwidepay_model');
        $unsplit_order = $this->Iwidepay_model->get_unsplit_orders(array('handled'=>0));
        if(empty($unsplit_order)){
            MYLOG::w('err:unsplit_order is empty', 'iwidepay/sync_offline');
            return false;
        }
        foreach($unsplit_order as $uk=>$uv){//直接通过orderid查询该订单的完结状态，没完结
            //查询订房的表，handled=1表示已经完结
            $order = $this->Iwidepay_model->get_hotel_order($uv['order_no']);
            if(!empty($order)){
                if($order['handled'] != 1){//还没完结
                    continue;
                }
                //已完结的，重新统计子订单金额和统计分销金额
                $f_price = $this->sync_sub_hotel_order($uv['order_no']);
                //统计分销金额 没有就是0
                $dis_amount = $this->sync_dist($uv['inter_id'],$uv['order_no'],$uv['module']);
                //更新unsplit_order表
                $where = array('id'=>$uv['id'],'order_no'=>$uv['order_no']);
                $update = array('transfer_status'=>2,'trans_amt'=>$f_price,'dist_amt'=>$dis_amount,'handled'=>1);
                $res = $this->Iwidepay_model->update_unsplit_order($where,$update);
                if(!$res){
                    MYLOG::w('线下同步脚本返回影响行数为0|order_no'.$uv['order_no'], 'iwidepay/sync_offline');
                }
            }
        }
    }

    //同步分销杂七杂八的奖励：粉丝关注 首单奖励 额外奖励（查询绩效表）
    protected function sycn_distribute_record(){
        $this->load('iwidepay/IwidePay_configs_model');
        $this->load->model('iwidepay/Iwidepay_model');
        $this->load->model('iwidepay/Iwidepay_debt_model');
        //查询分销配置信息 
        $res = $this->IwidePay_configs_model->get_iwidepay_dis_config('inter_id,type');
        if(empty($res)){
            return false;
        }
        $result = $inter_ids = $subs_inter_ids = array();
        foreach($res as $k=>$v){
            if($v['type'] == 'fans_subs_reward'){//粉丝关注inter_id
                if(!in_array($v['inter_id'],$subs_inter_ids)){
                    $subs_inter_ids[] = $v['inter_id'];
                }
            }else{
                $result[$v['inter_id']][] = $v['type'];
                if(!in_array($v['inter_id'],$inter_ids)){
                    $inter_ids[] = $v['inter_id'];
                }
            }
        }
        unset($res);
        
        //查询绩效表 粉丝关注 首单奖励 额外奖励(分组奖励)
        foreach($subs_inter_ids as $sk=>$sv){//粉丝关注 按酒店进行汇总
            $subs_res = $this->Iwidepay_model->get_sync_dis_fans_subs($sv);
            if(!empty($subs_res)){
                $data = array();
                $data['inter_id'] = $sv;//inter_id
                $data['hotel_id'] = $subs_res['hotel_id'];
                $data['module'] = 'dis';
                $data['openid'] = '';
                $data['order_no'] = 'subs_' . date('Ymd');
                $data['order_status'] = 0;
                $data['transfer_status'] = 3;//直接设为已分账
                $data['orig_amt'] = $subs_res['sum_total'];
                $data['trans_amt'] = $subs_res['sum_total'];
                $data['dist_amts'] = 0;
                $data['add_time'] = date('Y-m-d H:i:s');
                $data['handled'] = 1;
                if($this->Iwidepay_model->save_sync_offline_order($data)){//保存在offline_order表后 再保存在debt_record表
                    $debt_data = array();
                    $debt_data['inter_id'] = $data['inter_id'];//inter_id
                    $debt_data['hotel_id'] = $data['hotel_id'];
                    $debt_data['module'] = $data['module'];
                    $debt_data['order_no'] = $data['order_no'];
                    $debt_data['amount'] = $data['trans_amt'];
                    $debt_data['status'] = 0;
                    $debt_data['order_type'] = 'fans_subs';
                    $debt_data['debt_type'] = 1;
                    $debt_data['add_time'] = date('Y-m-d H:i:s');
                    $debt_data['up_time'] = date('Y-m-d H:i:s');
                    $ins_res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
                    if(is_array($ins_res)){
                        MYLOG::w('记录保存到debt表失败：order:'.$data['order_no'], 'iwidepay/sync_offline');
                    }
                }else{
                    MYLOG::w('同步分销关注奖励记录保存到offline_order表失败：order:'.$data['order_no'], 'iwidepay/sync_offline');
                }
            }
        }
        //查询首单奖励 分组奖励什么的 这些奖励目前是都是有分销员的，不用做saler身份判断
        $dis_order = $this->Iwidepay_model->get_sync_dis_record($inter_ids);
        if(!empty($dis_order)){            
            foreach($dis_order as $dk=>$dv){
                if(isset($result[$dv['inter_id']]) && in_array($dv['grade_table'],$result[$dv['inter_id']])){
                    $data = array();
                    $order_no = '';
                    $order_type = '';
                    if($dv['grade_table'] == 'iwide_distribute_group_member'){
                        $order_no = 'dis_group_' . $dv['grade_id'];
                        $order_type = 'dis_group';
                    }elseif($dv['grade_table'] == 'iwide_firstorder_reward'){
                        $order_no = 'dis_first_o_' . $dv['grade_id'];
                        $order_type = 'first_order';
                    }
                    $data['inter_id'] = $dv['inter_id'];//inter_id
                    $data['hotel_id'] = $dv['hotel_id'];
                    $data['module'] = 'dis';
                    $data['openid'] = '';
                    $data['order_no'] = $order_no;
                    $data['order_status'] = 0;
                    $data['transfer_status'] = 3;//直接设为已分账
                    $data['orig_amt'] = $dv['grade_total'];
                    $data['trans_amt'] = $dv['grade_total'];
                    $data['dist_amts'] = 0;
                    $data['add_time'] = date('Y-m-d H:i:s');
                    $data['handled'] = 1;
                    if($this->Iwidepay_model->save_sync_offline_order($data)){
                        $debt_data = array();
                        $debt_data['inter_id'] = $data['inter_id'];//inter_id
                        $debt_data['hotel_id'] = $data['hotel_id'];
                        $debt_data['module'] = $data['module'];
                        $debt_data['order_no'] = $data['order_no'];
                        $debt_data['amount'] = $data['trans_amt'];
                        $debt_data['status'] = 0;
                        $debt_data['order_type'] = $order_type;
                        $debt_data['debt_type'] = 1;
                        $debt_data['add_time'] = date('Y-m-d H:i:s');
                        $debt_data['up_time'] = date('Y-m-d H:i:s');
                        $ins_res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
                        if(is_array($ins_res)){
                            MYLOG::w('记录保存到debt表失败：order:'.$data['order_no'], 'iwidepay/sync_offline');
                        }
                    }else{
                        MYLOG::w('同步分销奇葩奖励记录保存到offline_order表失败：order:'.$data['order_no'], 'iwidepay/sync_offline');
                    }
                }
            }   
        }
    }

    /*
	 * 同步分销数据
	 */
	protected function sync_dist($inter_id,$order_no,$module){
		$dist_amts = 0;	
		if($module=='hotel'){
			//1.按主单查
			$grade_entity_all_orderid = $this->Iwidepay_model->get_single_grade_base_all($inter_id,$order_no,'orderid');
			MYLOG::w('info:'.$inter_id.'-'.$order_no.' hotel_dist by orderid-'.json_encode($grade_entity_all_orderid), 'iwidepay/sync_offline');
			$dist_amts = $this->deal_grades($inter_id,$grade_entity_all_orderid,array());
			//2.按子单查，先查出子订单号
			$itemids = $this->Iwidepay_model->get_hotel_order_items($order_no);
			if(!empty($itemids)){
				$item_num = count($itemids);
				$inum = 0;
				foreach ($itemids as $k => $itemid) {
					$grade_entity_all = $this->Iwidepay_model->get_single_grade_base_all($inter_id,$itemid['id']);
					MYLOG::w('info:'.$inter_id.'-'.$order_no.'hotel_dist by itemid_all-'.json_encode($grade_entity_all), 'iwidepay/sync_offline');
					$grade_entity_extends = $this->Iwidepay_model->get_single_grade_base_extends($inter_id,$itemid['id']);
					MYLOG::w('info:'.$inter_id.'-'.$order_no.'hotel_dist by itemid_extends-'.json_encode($grade_entity_extends), 'iwidepay/sync_offline');
					if(!empty($grade_entity_all)||!empty($grade_entity_extends)){
						$dist_amt = $this->deal_grades($inter_id,$grade_entity_all,$grade_entity_extends);
						$dist_amts += $dist_amt;
					}else{
						$inum++;
						continue;
					}
				}
			}
			return $dist_amts;
		}else{
			$grade_entity_all = $this->Iwidepay_model->get_single_grade_base_all($inter_id,$order_no);
			MYLOG::w('info:'.$inter_id.'-'.$order_no.' other_dist by all-'.json_encode($grade_entity_all), 'iwidepay/sync_offline');
			$grade_entity_extends = $this->Iwidepay_model->get_single_grade_base_extends($inter_id,$order_no);
			MYLOG::w('info:'.$inter_id.'-'.$order_no.' other_dist by extends-'.json_encode($grade_entity_extends), 'iwidepay/sync_offline');
			if(!empty($grade_entity_all)||!empty($grade_entity_extends)){
				$dist_amt = $this->deal_grades($inter_id,$grade_entity_all,$grade_entity_extends);
				$dist_amts = $dist_amt;
			}
			return $dist_amts;
		}
		
	}

	protected function deal_grades($inter_id,$grade_entity_all,$grade_entity_extends){
		$dist_amts = 0;
		$all_amt = 0;
		foreach ($grade_entity_all as $ka => $va) {
			if($va['saler'] >0){
				//查询分销员信息
				$this->load->model('distribute/staff_model');
				$saler_query = $this->staff_model->get_my_base_info_saler ( $inter_id, $va['saler'] );
				if(empty($saler_query) || empty($saler_query['openid']) || $saler_query['status']!= 2){
					continue;//不是分销员 或者状态不对
				}
				if($va['status'] != 1 && $va['status'] != 2){
					if($va['status'] == 5 || $va['status'] == 99){
						continue;
					}
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
				}else{
					$extends_amt += $ve['grade_total']*100;
				}
			}
		}
		$dist_amts += $extends_amt;
		return $dist_amts;
	}

	/*
	 * 统计子单的金额
	 */
	protected function sync_sub_hotel_order($order_no){
		$f_price = 0;
		//handled=1表明该订单包含所有子订单都已完结
		$res = $this->Iwidepay_model->get_hotel_order_items($order_no);
		if(!empty($res)){
			foreach ($res as $kr => $vr) {
				if($vr['istatus']==3){
					//记录订单最终金额
					$f_price += $vr['iprice']*100;
				}
			}
		}
		return $f_price;
	}    
}
