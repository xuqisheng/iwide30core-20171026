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
        }elseif ($type == 'get') {
            $ok = $this->redis_proxy->get( $key );
        }elseif ($type == 'update') {
            $ok = $this->redis_proxy->set( $key, $value );
        }
        return $ok;
    }

    /**
     * [check_key 检测此前是否有脚本未正常执行完成]
     */
    protected function check_key(){
        // 获取key
        $val = $this->redis_lock('get','IWIDEPAY_EXECUTE_SORT');
        $this->load->library('IwidePay/IwidePayExecute',null,'IwidePayExecute');
        if($val!=IwidePayExecute::EXT_HANDLE_RUN_OFFLINE_SORT){
            MYLOG::w('err:上一个的脚本未正常执行完成', 'iwidepay/sync_offline');
            exit('上一个的脚本未正常执行完成');
        }
    }

    //同步线下订单
    public function run_offline(){
    	$this->check_arrow();
        $this->check_key();
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
        MYLOG::w('info:开始同步线下订单拉拉拉拉啦', 'iwidepay/sync_offline');
        $this->sync_offline_order();
        //同步分销其他奇葩奖励
        sleep(1); 
         MYLOG::w('info:开始同步分销奇葩奖励', 'iwidepay/sync_offline');
        $this->sycn_distribute_record();
        //释放锁
        $this->redis_lock('delete');
        //执行顺序+1
        $this->redis_lock('update','IWIDEPAY_EXECUTE_SORT',IwidePayExecute::EXT_HANDLE_RUN_OFFLINE_SORT+1);
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
        $this->load->model('iwidepay/IwidePay_configs_model');
        $this->load->model('iwidepay/Iwidepay_model');
        $this->load->model('iwidepay/Iwidepay_debt_model');
        //查询开启分账的inter_id 
        $res = $this->IwidePay_configs_model->get_transfer_inter_id();
        //查询不启用分销代发的inter_id
        $unsplit_ids = $this->IwidePay_configs_model->get_unsplit_configs_by_iwidepay();
        MYLOG::w('不进行分销的inter_id：inter_id数组:'.json_encode($unsplit_ids), 'iwidepay/sync_offline');
        //查出所有开启分账的
        if(empty($res)){
            return false;
        }
        $inter_ids = array();
        foreach($res as $k=>$v){
            if(!in_array($v['inter_id'],$unsplit_ids)){//没有启用分销代发才进来
                $inter_ids[] = $v['inter_id'];
            }
        }
        if(empty($inter_ids)){
            MYLOG::w('没有可以进行同步奇葩分销奖励的inter_id', 'iwidepay/sync_offline');
            return false;
        }
        //查询绩效表 粉丝关注 首单奖励 额外奖励(分组奖励)
        foreach($inter_ids as $sk=>$sv){//粉丝关注 按酒店进行汇总
            $subs_res = $this->Iwidepay_model->get_sync_dis_fans_subs($sv);
            if(!empty($subs_res)){
                foreach($subs_res as $subk=>$subv){
                    $debt_data = array();
                    $debt_data['inter_id'] = $subv['inter_id'];//inter_id
                    $debt_data['hotel_id'] = $subv['hotel_id'];
                    $debt_data['module'] = 'extra_dist';
                    $debt_data['order_no'] = 'fans_subs_' . date('Ymd');
                    $debt_data['amount'] = $subv['sum_total'] * 100;
                    $debt_data['status'] = 0;
                    $debt_data['order_type'] = 'extra_dist';
                    $debt_data['debt_type'] = 1;
                    $debt_data['add_time'] = date('Y-m-d H:i:s');
                    $debt_data['up_time'] = date('Y-m-d H:i:s');
                    $ins_res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
                    if(is_array($ins_res)){
                        MYLOG::w('记录保存到debt表失败：order:'.$debt_data['order_no'], 'iwidepay/sync_offline');
                    }
                }
            }
        }
        //查询首单奖励 分组奖励什么的 这些奖励目前是都是有分销员的，不用做saler身份判断
        $dis_type = array('iwide_distribute_group_member','iwide_firstorder_reward');
        foreach($dis_type as $dis_k=>$dis_v){
            $dis_order = $this->Iwidepay_model->get_sync_dis_record($inter_ids,$dis_v);
            if(!empty($dis_order)){            
                foreach($dis_order as $dk=>$dv){
                    $order_no = '';
                    if($dis_v == 'iwide_distribute_group_member'){
                        $order_no = 'dis_group_' . date('Ymd');
                    }elseif($dis_v == 'iwide_firstorder_reward'){
                        $order_no = 'dis_firstorder_' . date('Ymd');
                    }
                    $debt_data = array();
                    $debt_data['inter_id'] = $dv['inter_id'];//inter_id
                    $debt_data['hotel_id'] = $dv['hotel_id'];
                    $debt_data['module'] = 'extra_dist';
                    $debt_data['order_no'] = $order_no;
                    $debt_data['amount'] = $dv['sum_total'] * 100;
                    $debt_data['status'] = 0;
                    $debt_data['order_type'] = 'extra_dist';
                    $debt_data['debt_type'] = 1;
                    $debt_data['add_time'] = date('Y-m-d H:i:s');
                    $debt_data['up_time'] = date('Y-m-d H:i:s');
                    $ins_res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
                    if(is_array($ins_res)){
                        MYLOG::w('记录保存到debt表失败：inter_id:'.$debt_data['inter_id'].'|hotel_id:'.$debt_data['hotel_id'], 'iwidepay/sync_offline');
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
