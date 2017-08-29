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
            MYLOG::w('err:'.__METHOD__ . ' lock fail!', 'iwidepay_split');
            exit('FAILURE!');
        }
        MYLOG::w('info:开始生成欠款单的脚本', 'iwidepay_debt');
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        $this->load->model('iwidepay/Iwidepay_debt_model');
        $res = $this->handle_debt();
        echo $res;

        //释放锁
        $this->redis_lock('delete');
        MYLOG::w('info:结束生成欠款单的脚本', 'iwidepay_debt');
        exit('欠款单生成完毕');
    }

    protected function handle_debt(){
    	//取前一天现付订单的金房卡、分销、集团分成数据
    	$enddate = date('Y-m-d 00:00:00');
    	$startdate = date('Y-m-d 00:00:00',strtotime('-1 day'));
    	$no_hotel_offlines = $this->Iwidepay_debt_model->get_no_hotel_offlines($startdate,$enddate);
    	if(empty($no_hotel_offlines)){
    		MYLOG::w('err:data is empty by get_no_hotel_offlines','iwidepay_debt');
    		echo 'data is empty by get_no_hotel_offlines|';
    	}
    	foreach ($no_hotel_offlines as $key => $value) {
    		$total_amount = 0;
    		if(!empty($value)){
	    		foreach ($value as $k => $val) {
	    			$total_amount += $val['amount'];
	    		}
	    		//组装欠款单数据
	    		$debt_data = array(
	    			'inter_id' => $val[0]['inter_id'],
	    			'hotel_id' => $val[0]['hotel_id'],
	    			'module' => $val[0]['module'],
	    			'order_no' => $val[0]['order_no'],
	    			'amount' => $total_amount,
	    			'order_type' => 'order',
	    			'add_time' => date('Y-m-d H:i:s'),
	    			'up_time' => date('Y-m-d H:i:s'),
	    			);
	    		//保存欠款单记录
	    		$res = $this->Iwidepay_debt_model->save_debt_record($debt_data);
	    		if($res!==true){
                    MYLOG::w('err:insert debt_record fail-'.json_encode($res),'iwidepay_debt');
                }
	    	}
    	}

    	//取前一天的垫付退款数据
    	


    	//取月费配置
    	
    }	

}