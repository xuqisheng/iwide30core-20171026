<?php
/*
 * 定时检测速8打赏的粉丝是否绑定会员
 * author situguanchen  2017-05-06
 */
class Autorun extends MY_Controller {
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
        $arrow_ip = array('10.25.168.86','10.25.3.85');//只允许服务器自动访问，不能手动
        if(!in_array($_SERVER['REMOTE_ADDR'],$arrow_ip)/*&&$_SERVER['SERVER_ADDR']!=$_SERVER['REMOTE_ADDR']*/){
            exit('非法访问！');
        }
    }

    protected function _load_cache( $name='Cache' ){
        if(!$name || $name=='cache')
            $name='Cache';
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
        return $this->$name;
    }


    //处理订单
    public function check(){
        $this->check_arrow();

        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');

        $cache= $this->_load_cache();
        $redis= $cache->redis->redis_instance();

        //检查是否只有一个实例
        $key = 'subatips_send_lock';
        $ok = $redis->setNX($key,'on');//var_dump($redis->del($key));die;
        if(!$ok){
            $last_time = $redis->get('subatips_last_time');//var_dump($last_time);die;
            if(!empty($last_time) && $last_time+600 < time()){//超过十分钟
                MYLOG::w('自动任务进行中，已经超过了十分钟|','tips');
                //todo 发短信通知
                $this->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
                $suba = new Subaapi_webservice(false);
                $suba->SendSMS('13580370331','hey,速8打赏脚本锁住了');
                echo '超时';
                die;
            }
            echo '有其他任务正在运行，请稍候！';exit;
        }
        //记录开始时间
        $redis->set('subatips_last_time',time());
        //取 未绑定的记录，检测是否有绑定，绑定了就发放奖品
        $this->load->model('tips/tips_reward_model');
        $orders = $this->tips_reward_model->get_unblind_list('id,inter_id,hotel_id,openid,order_id,member_card_no,reward_id,reward_type,balance,is_send,handle_times');
        $success = $fail = 0;//var_dump($orders);die;
        if(!empty($orders)){
            foreach($orders as $k=>$v){
                if(empty($v['member_card_no']) && $v['is_send'] == 0){//没有绑定会员号，并且没有发放的
                    //查询是否已经绑定
                    $arr = array();
                    $arr['handle_times'] = $v['handle_times'] + 1;
                    $member_info = $this->get_member_info($v['inter_id'],$v['openid']);
                    if($member_info && !empty($member_info->mem_card_no)){//绑定了就更新会员号 ，发放虚拟福利,实物的就更新下发放状态
                        MYLOG::w('速8用户绑定查询：member:'.serialize($member_info), 'tips');
                        $arr['member_card_no'] = $member_info->mem_card_no;
                        if($v['reward_type']==2){//发放虚拟的
                            $params = array('balance'=>$v['balance'],'mem_card_no'=>$member_info->mem_card_no);
                            $send_res = $this->send_banlance($params);
                            //$send_res = json_decode($send_res,true);
                            if($send_res['BalanceRechargeBySourceResult']['Content'] === false){//失败
                                $fail++;
                                $arr['is_send'] = 2;//失败
                            }elseif($send_res['BalanceRechargeBySourceResult']['Content']){
                                $success++;
                                $arr['is_send'] = 1;
                            }else{
                                $fail++;
                                $arr['is_send'] = 3;//无返回
                            }
                        }else{//实物的,或者没中奖，更新下状态
                            $arr['is_send'] = 1;
                        }
                    }else{
                        $fail++;
                    }
                    $this->db->where(array(
                        'id'    =>$v['id'],
                        'inter_id' => $v['inter_id'],
                    ));
                    $this->db->update('tips_reward_record',$arr);
                }
            }
        }
        //遍历结束，解锁
        $redis->del($key);
        //结果信息
        echo "执行完成:总记录:".count($orders)."，发放成功:".$success."，发放失败:".$fail;
    }

    //查询是否是会员
    private function get_member_info($inter_id = 'a455510007' ,$openid = ''){
       // $inter_id = $this->inter_id;
        $this->load->library ( 'PMS_Adapter', array (
            'inter_id' => $inter_id,//写死su8
            'hotel_id' => 0
        ), 'pub_pmsa' );

        $member = $this->pub_pmsa->check_openid_member ( $inter_id, $openid, array (
            'create' => TRUE
        ) );
        return $member;
    }

    //发放余额接口
    public function send_banlance($data){
        $this->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
        $suba = new Subaapi_webservice(false);
        $param = array(
            'cardNo'=>$data['mem_card_no'],
            'balance'=>$data['balance'],
            'source'=>'微信打赏'
        );
        $time1 = microtime(true);
        $res = $suba->BalanceRechargeBySource($param);
        $time2 = microtime(true);
        MYLOG::w('速8打赏发放余额：s_time:'.$time1.'|e_time:'.$time2.'--request_param:'.json_encode($param).'--res:'.json_encode($res), 'tips');
        return $res;
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