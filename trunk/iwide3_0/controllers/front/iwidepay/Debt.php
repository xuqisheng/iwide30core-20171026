<?php
/*
 * 生成欠款记录
 * date 2017-08-28
 * author chenjunyu
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Debt extends MY_Controller {
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
    protected function redis_lock($type='set' ,$key='debt_lock' ,$value='lock'){
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

    public function create(){
    	$this->check_arrow();
        // 上锁
        $ok = $this->redis_lock();
        if(!$ok){
            //程序锁住，记录报警日志并终止执行，上线将此日志交博士加入报警短信
            MYLOG::w('err:'.__METHOD__ . ' lock fail!', 'iwidepay_debt');
            exit('FAILURE!');
        }
        MYLOG::w('info:开始生成欠款单的脚本', 'iwidepay_debt');
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        $this->load->model('iwidepay/Iwidepay_debt_model');
        MYLOG::w('info:开始生成order类型欠款单的脚本', 'iwidepay_debt');
        $res = $this->debt_order();
        MYLOG::w('info:结束生成order类型欠款单的脚本', 'iwidepay_debt');
        echo $res;
        MYLOG::w('info:开始生成refund类型欠款单的脚本', 'iwidepay_debt');
        $res = $this->debt_refund();
        MYLOG::w('info:结束生成refund类型欠款单的脚本', 'iwidepay_debt');
        echo $res;
        MYLOG::w('info:开始生成basepay类型欠款单的脚本', 'iwidepay_debt');
        $res = $this->debt_basepay();
        MYLOG::w('info:结束生成basepay类型欠款单的脚本', 'iwidepay_debt');
        echo $res;

        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('info:结束生成欠款单的脚本', 'iwidepay_debt');
        exit('欠款单生成完毕');
    }

    //分成欠款
    protected function debt_order(){
    	//取前一天现付订单的金房卡、分销、集团分成数据(当天当前脚本执行前生成)
    	$startdate = date('Y-m-d 00:00:00');
    	$enddate = date('Y-m-d 00:00:00',strtotime('+1 day'));
    	$no_hotel_offlines = $this->Iwidepay_debt_model->get_no_hotel_offlines($startdate,$enddate);
    	if(empty($no_hotel_offlines)){
    		MYLOG::w('err:data is empty by debt_order','iwidepay_debt');
    		return 'data is empty by debt_order|';
    	}
    	foreach ($no_hotel_offlines as $key => $value) {
    		$total_amount = 0;
    		$ext_info_arr = array();
    		if(!empty($value)){
	    		foreach ($value as $k => $val) {
	    			$total_amount += $val['amount'];
	    			$ext_info_arr[$val['type'].'_amount'] = $val['amount'];
	    		}
	    		$ext_info_arr['orig_amount'] = $total_amount;
	    		$ext_info_json = json_encode($ext_info_arr);
	    		//组装欠款单数据
	    		$debt_data = array(
	    			'inter_id' => $value[0]['inter_id'],
	    			'hotel_id' => $value[0]['hotel_id'],
	    			'module' => $value[0]['module'],
	    			'order_no' => $value[0]['order_no'],
	    			'amount' => $total_amount,
	    			'order_type' => 'order',
	    			'ext_info' => $ext_info_json,
	    			'add_time' => date('Y-m-d H:i:s'),
	    			'up_time' => date('Y-m-d H:i:s'),
	    			);
	    		//保存欠款单记录
	    		$res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
	    		if($res!==true){
                    MYLOG::w('err:insert split debt_record fail-'.json_encode($res),'iwidepay_debt');
                }
	    	}
    	}
    	return 'this is done by debt_order|';
    }

    //垫付退款欠款
    protected function debt_refund(){
    	//取前一天垫付退款成功的数据
    	$startdate = date('Y-m-d 00:00:00',strtotime('-1 day'));
    	$enddate = date('Y-m-d 00:00:00');
    	$refund_orders = $this->Iwidepay_debt_model->get_refund_orders($startdate,$enddate);
    	if(empty($refund_orders)){
    		MYLOG::w('err:data is empty by debt_refund','iwidepay_debt');
    		return 'data is empty by debt_refund|';
    	}
    	foreach ($refund_orders as $k => $v) {
    		//组装欠款单数据
    		$debt_data = array(
    			'inter_id' => $v['inter_id'],
    			'hotel_id' => $v['hotel_id'],
    			'module' => $v['module'],
    			'order_no' => $v['orig_order_no'],
    			'amount' => $v['amount'],
    			'order_type' => 'refund',
    			'ori_pay_no' => $v['ori_pay_no'],
    			'add_time' => date('Y-m-d H:i:s'),
    			'up_time' => date('Y-m-d H:i:s'),
    			);
    		//保存欠款单记录
    		$res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
    		if($res!==true){
                MYLOG::w('err:insert refund debt_record fail-'.json_encode($res),'iwidepay_debt');
            }
    	}
    }

    //基础月费欠款
    protected function debt_basepay(){
    	//查出启用分账的公众号
    	$open_splits = $this->Iwidepay_debt_model->get_split_status();
    	foreach ($open_splits as $ko => $vo) {
    		//判断是否开启了基础月费结算
			$this->load->model('iwidepay/Iwidepay_configs_model');
    		$base_pay_settle = $this->Iwidepay_configs_model->get_configs_by_interid($vo['inter_id'],1,'base_pay_settle','base_pay');
    		MYLOG::w('info:base_pay_settle config_'.$vo['inter_id'].'-'.json_encode($base_pay_settle),'iwidepay_debt');
    		if(empty($base_pay_settle['value'])||$base_pay_settle['value']!=1){
    			continue;
    		}

    		//取所有添加有效账户的酒店
    		$merchant_hotels = $this->Iwidepay_debt_model->get_merchant_hotels($vo['inter_id']);
    		if(empty($merchant_hotels)){
    			MYLOG::w('err:Effective account is empty :'.$vo['inter_id'],'iwidepay_debt');
    			continue;
    		}

    		//取月费配置
    		$base_pay_rules = $this->Iwidepay_debt_model->get_basepay_rules($vo['inter_id']);
    		MYLOG::w('info:the base_pay rule of '.$vo['inter_id'].'-'.json_encode($base_pay_rules),'iwidepay_debt');

    		foreach ($merchant_hotels as $kb => $vb) {
    			//查询门店本月月费欠款记录是否存在
		    	$exist_basepay = $this->Iwidepay_debt_model->get_basepay_record($vb['inter_id'],$vb['hotel_id']);
		    	if($exist_basepay){
		    		//已存在
		    		MYLOG::w('info:the base_pay of this month already exists-'.json_encode($vb),'iwidepay_debt');
		    		continue;
		    	}
	    		if(!empty($vb)){
	    			//匹配规则，没有门店规则，取公众号通用规则
	    			$regular_base = !empty($base_pay_rules[$vb['inter_id'].'_'.$vb['hotel_id']])?$base_pay_rules[$vb['inter_id'].'_'.$vb['hotel_id']]:(!empty($base_pay_rules[$vb['inter_id'].'_-1'])?$base_pay_rules[$vb['inter_id'].'_-1']:array());
	    			if(!empty($regular_base)){
		    			//生成月费欠款记录
		    			$debt_data = array(
		    				'inter_id' => $vb['inter_id'],
			    			'hotel_id' => $vb['hotel_id'],
			    			'module' => 'base_pay',
			    			'order_no' => date('Ym'),
			    			'amount' => $regular_base['regular_base'],
			    			'order_type' => 'base_pay',
			    			'add_time' => date('Y-m-d H:i:s'),
			    			'up_time' => date('Y-m-d H:i:s'),
		    				);
		    			//保存欠款单记录
			    		$res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
			    		if($res!==true){
			                MYLOG::w('err:insert refund debt_record fail-'.json_encode($res),'iwidepay_debt');
			            }
			        }
	    		}
	    	}
    	}
    	return 'this is done by debt_basepay|';
    }	
}