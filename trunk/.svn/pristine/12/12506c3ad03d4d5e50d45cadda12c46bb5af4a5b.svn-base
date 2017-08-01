<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Tips extends MY_Front {//su8单独用这个，定制的额。。
    public $openid;
    public $module;
    public $fans_info;
    protected $_token;
	function __construct() {
		parent::__construct ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
       // $this->inter_id = 'a455510007';//su8
       // $this->get_Token();
        //统计探针
       /* $this->load->library('MYLOG');
        MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));*/

	}

    /**
     * 带智能检测用户关注情况，视情况进行高级授权跳转
     */
    private function _get_wx_userinfo()
    {
        //$this->load->model('wx/publics_model');
        //$fans= $this->publics_model->get_fans_info( $this->openid,$this->inter_id );

        //if( !$fans || empty($fans['nickname']) || empty($fans['headimgurl']) ){
            $userinfo= $this->publics_model->get_wxuser_info($this->inter_id, $this->openid );

            if( isset($userinfo['subscribe']) && $userinfo['subscribe']==0 ){
// return array();//BUG未解决先返回空数据
                //微信返回的信息显示没有关注，跳到图文页关注
                $redirect_url = 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MjM5NDI1MDcyMA==&scene=124&devicetype=android-22&version=26050734&lang=zh_CN&nettype=WIFI&a8scene=3&pass_ticket=9xSczyHpYrKlvpG84udZyHUi7Fahkw3KKWDHVP3tqnw%3D&wx_header=1';
                redirect(  $redirect_url  );

            } else {
                $this->publics_model->update_wxuser_info($this->inter_id, $this->openid );
                return $userinfo;
            }

        //} else {
         //   return $fans;
       // }
    }
	
	public function index(){
        $this->fans_info = $this->_get_wx_userinfo();
		$data['inter_id'] = $this->inter_id;
		$data['saler'] = (int)$this->input->get('saler', TRUE );
        //查询分销员信息
        $this->load->model('distribute/staff_model');
        $saler = $this->staff_model->get_my_base_info_saler($this->inter_id,$data['saler']);
        if(empty($saler) || $saler['is_distributed'] != 1 || $saler['status'] != 2 || empty($saler['openid'])){
            echo '该分销员信息有误!';
            die;
        }
        //查询分销员头像
        $this->load->model('wx/publics_model');
        $saler_info = $this->publics_model->get_fans_info( $saler['openid'],$this->inter_id );
        $saler['headimgurl'] = isset($saler_info['headimgurl'])?$saler_info['headimgurl']:'';
        $data['saler_info'] = $saler;
        //$data['fans_info'] = $this->fans_info;
        //统计该分销员的打赏记录
        $this->load->model('tips/tips_orders_model');
        $tips_record = $this->tips_orders_model->get_saler_tips_record($this->inter_id,$data['saler']);
        $data['tips_count'] = 0;
        $data['avg_score'] = 0;
        if($tips_record){
            $data['tips_count'] = isset($tips_record['c'])?$tips_record['c']:0;
            $data['avg_score'] = empty($data['tips_count'])?0:($tips_record['sum_score']/$data['tips_count']);
        }
        $this->display('subatips/index',$data);
	}

    //生成订单
    public function save_order(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $arr = array();
        $arr['saler']		= (int)$this->input->post("saler",true);
        $arr['row_total'] = intval($this->input->post('pay_money',true));
        $arr['pay_money'] = $arr['row_total'];
        $arr['score']		= (int)$this->input->post("score",true);
        if(empty($arr['pay_money']) || $arr['pay_money'] < 1 ){
            $return['msg'] = '金额必须大于1元';
            echo json_encode($return);
            die;
        }
        //查询分销员信息
        $this->load->model('distribute/staff_model');
        $saler = $this->staff_model->get_my_base_info_saler($this->inter_id,$arr['saler']);
        if(empty($saler) || $saler['is_distributed'] != 1 || $saler['status'] != 2 || empty($saler['openid'])){
            $return['msg'] = '分销员信息有误';
            echo json_encode($return);
            die;
        }
        //
        $arr['pay_money'] = $arr['row_total'] = 0.01;//测试
        //
        $this->load->model('wx/publics_model');
        $fans= $this->publics_model->get_fans_info( $this->openid,$this->inter_id );
        $this->load->model('tips/tips_orders_model');
        $arr['inter_id'] = $this->inter_id;
        $arr['hotel_id'] = $saler['hotel_id'];
        $arr['saler_name'] = $saler['name'];
        $arr['pay_openid'] = $this->openid;
        $arr['pay_name'] = isset($fans['nickname'])?$fans['nickname']:'';
        $arr['pay_way'] = 1;//默认是微信支付
        $arr['pay_status'] = 1;//未支付
        $arr['add_time'] = date('Y-m-d H:i:s');
        $CI = & get_instance();
        $arr['from_ip'] =  $CI->input->ip_address();
        //插入前先查一次，是否有重复重新生成
        do{
            //生成订单号
            $order_sn_num = 'TIPS' . time () . str_pad ( mt_rand ( 0, 99999 ), 5, '0', STR_PAD_LEFT );
            $order_res = $this->tips_orders_model->check_order_sn($order_sn_num);
        }
        while($order_res == 0);//订单号重复重新生成
        $arr['order_sn'] = $order_sn_num;
        $res = $this->db->insert('tips_orders',$arr);
        $order_id = $this->db->insert_id ();
        if($res){
            $pay_url = site_url('wxpay/tips_pay') . '?id=' . $this->inter_id . '&saler=' . $arr['saler'] . '&order_id=' . $order_id;
            $return['errcode'] = 0;
            $return['msg'] = '打赏成功';
            $return['data'] = array('order_id'=>$order_id,'pay_url'=>$pay_url);
            echo json_encode($return);
            die;
        }else{
            $return['msg'] = '打赏失败';
            echo json_encode($return);
            die;
        }
    }

    //打赏结果页
    public function pay_res(){
        $order_id=(int)$this->input->get('order_id');
        $this->db->where(array(
            'order_id'=>$order_id,
            'inter_id'=>$this->inter_id,
            'pay_openid'=>$this->openid,
        ));
        $this->db->limit(1);
        $order = $this->db->get('tips_orders')->row_array();
        if($order && $order['pay_status'] == 2){//支付成功
            $data['order'] = $order;
            //这里先去查询一次有没有奖励记录 没有就插入抽奖记录
            $this->db->where(
                array(
                    'inter_id'=>$this->inter_id,
                    'openid'=>$this->openid,
                    'order_id'=>$order_id,
                )
            );
            $reward = $this->db->get('tips_reward_record')->row_array();
            $prize_result = array();
            if(empty($reward)){
                //抽奖程序
                $prize_result = $this->drawing($this->inter_id,$this->openid);
                if(empty($prize_result)){
                    echo '系统错误';
                    die;
                }
                //记录数组
                $record = array();
                $record['inter_id'] = $this->inter_id;
                $record['order_id'] = $order['order_id'];
                $record['hotel_id'] = $order['hotel_id'];
                $record['openid'] = $this->openid;
                $record['reward_id'] = $prize_result['reward_id'];
                $record['reward_type'] = $prize_result['reward_type'];
                $record['balance'] = $prize_result['balance'];
                $record['is_send'] = 0;
                $record['is_print'] = 0;
                $record['add_time'] = date('Y-m-d H:i:s');
                $result = $this->db->insert('tips_reward_record',$record);
            }else{
                //查询奖品数据
                $this->load->model('tips/tips_reward_model');
                $prize_result = $this->tips_reward_model->get_single_prize_info($reward['reward_id']);
            }
            if(isset($reward['is_send']) && $reward['is_send']==1 || !empty($reward['is_print'])){//已经发了 或者点过领取
                $redirect_url = site_url('subatips/tips/index').'?id='.$order['inter_id'].'&saler='.$order['saler'];
                redirect(  $redirect_url  );
                die;
            }
            $data['prize_result']  = $prize_result;
            //获取商城地址
           // http://hotels.iwide.cn/index.php/soma/package/index?id=a481256342
            $public = $this->db->get_where ( 'publics', array (
                'inter_id' => $this->inter_id
            ) )->row_array ();
            $data['url'] = '';
            if($public && !empty($public['domain'])){
                $host = 'http://'.$public['domain'] . '/index.php/';
                $url= $host . 'soma/package/index?id='.$this->inter_id;
                $data['url'] = $url;
            }
            //直接查询是否是会员
            $member_info = $this->get_member_info();
            $is_member = 0;//不是会员
            if($member_info && !empty($member_info->mem_card_no)){
                $is_member = 1;//Huiyuan
            }
            $data['is_member'] = $is_member;
            $this->display('subatips/pay_res',$data);
        }else{
            //支付失败，调回首页
            redirect(site_url('subatips/tips/index?id='.$order['inter_id'].'&saler='.$order['saler']));
            die;
        }
    }

    //查询是否是会员
    private function get_member_info(){
        $inter_id = $this->inter_id;
        $this->load->library ( 'PMS_Adapter', array (
            'inter_id' => $inter_id,//写死su8
            'hotel_id' => 0
        ), 'pub_pmsa' );

        $member = $this->pub_pmsa->check_openid_member ( $inter_id, $this->openid, array (
            'create' => TRUE
        ) );
        return $member;
    }

    //是会员的就领券，不是会员的就登记
    public function get_reward(){
        $order_id = (int)$this->input->post('order_id', TRUE );
      //  $reward_id = (int)$this->input->get('reward_id', TRUE );
        //查询一次订单
        $this->db->where(array(
            'order_id'=>$order_id,
            'inter_id'=>$this->inter_id,
            'pay_openid'=>$this->openid,
        ));
        $this->db->limit(1);
        $order = $this->db->get('tips_orders')->row_array();
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        if(!$order || $order['pay_status'] != 2){//没有成功
            $return['msg'] = '该订单没有成功';
            echo json_encode($return);
            die;
        }
        $this->db->where(
            array(
                'inter_id'=>$this->inter_id,
                'openid'=>$this->openid,
                'order_id'=>$order_id,
            )
        );
        $reward = $this->db->get('tips_reward_record')->row_array();
        if(empty($reward)){
            $return['msg'] = '奖品信息有误';
            echo json_encode($return);
            die;
        }
        if($reward['is_send'] == 1){
            $return['msg'] = '奖品已发放';
            echo json_encode($return);
            die;
        }
        $member_info = $this->get_member_info();
        $record = array();
        $record['is_print'] = 1;//已经刮
        if($member_info && !empty($member_info->mem_card_no)){//如果是会员的就直接发放
            //发放
            $member_card_no = $member_info->mem_card_no;
            $record['member_card_no'] = $member_card_no;
            if($reward['reward_type']==2){
                $params = array('balance'=>$reward['balance'],'mem_card_no'=>$member_card_no);
                $res = $this->send_banlance($params);//var_dump($res);die;
                if($res['BalanceRechargeBySourceResult']['Content'] === false){
                    //shibai
                    $record['is_send'] = 2;
                    $return['msg'] = '发放余额失败,请联系客服';
                }elseif($res['BalanceRechargeBySourceResult']['Content']){
                    //发放成功记录起来
                    $record['is_send'] = 1;
                    $return['errcode'] = 0;
                    $return['msg'] = '成功';
                }else{
                    $record['is_send'] = 3;
                    $return['msg'] = '返回错误！';
                }
            }elseif($reward['reward_type']==1){//实物
                $record['is_send'] = 1;
                $return['errcode'] = 0;
                $return['msg'] = '已存入账号，等专人通知！';
            }else{
                //$record['is_send'] = 1;
                $return['errcode'] = 0;
                $return['msg'] = '发放成功';
            }
        }else{
            $return['errcode'] = 999;
            $return['msg'] = '不是会员';
        }
        $this->db->where(array(
            'id'=>$reward['id'],
        ));
        $this->db->update('tips_reward_record',$record);
        echo json_encode($return);
        die;
    }

    //打赏通知速8
    public function notify_suba($data = array()){
        $this->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
        $suba = new Subaapi_webservice(false);
        $time1 = microtime(true);
        $res = $suba->SetRewardInfo($data);
        $time2 = microtime(true);
        $this->api_write_log('打赏完成通知su8：s_time:'.$time1.'|e_time:'.$time2.'--request_param:'.json_encode($data).'--res:'.json_encode($res));
        return $res;
    }

    //封装发放余额接口
    public function send_banlance($data){
        $this->load->library('Baseapi/Subaapi_webservice',array('testModel'=>true));
        $suba = new Subaapi_webservice(false);
        $param = array(
            'cardNo'=>$data['mem_card_no'],
            'balance'=>(int)$data['balance'],
            'source'=>'微信打赏',
        );
        $time1 = microtime(true);
        $res = $suba->BalanceRechargeBySource($param);
        $time2 = microtime(true);
        $this->api_write_log('打赏发放余额：s_time:'.$time1.'|e_time:'.$time2.'--request_param:'.json_encode($param).'--res:'.json_encode($res));
        return $res;
    }

    //点击不绑定模板消息通知
    public function send_tmmsg(){
        $order_id = (int)$this->input->post('order_id', TRUE );
        $this->db->where(array(
            'order_id'=>$order_id,
            'inter_id'=>$this->inter_id,
            'pay_openid'=>$this->openid,
        ));
        $this->db->limit(1);
        $order = $this->db->get('tips_orders')->row_array();
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        if(!$order || $order['pay_status'] != 2){//没有成功
            $return['msg'] = '该订单没有成功';
            echo json_encode($return);
            die;
        }
        $member_info = $this->get_member_info();
        if($member_info && !empty($member_info->mem_card_no)){//如果是会员的就不通知了
            $return['msg'] = '你已经是会员了';
            echo json_encode($return);
            die;
        }
        //发送没有绑定模板消息
        $this->load->model ( 'plugins/Template_msg_model' );
        $order['openid'] = $this->openid;//通知打赏人
        $res = $this->Template_msg_model->send_tips_success_msg ( $order, 'tips_blinds_member' );
        $return['errcode'] = 0;
        $return['msg'] = '通知成功';
        echo json_encode($return);
        die;
    }

    //抽奖程序
    private function drawing($inter_id='a455510007',$openid = ''){
        //查询奖品信息
        $date = date('Ym');
        $this->load->model('tips/tips_reward_model');
        $prize_info = $this->tips_reward_model->get_prize_info('*',$date);
        if(empty($prize_info)){
            return false;
        }
        //不中奖
        $no_prize_key = '';
        foreach($prize_info as $k=>$v){
            if($v['date_stock'] == 0 && $prize_info[$k]['probability'] != 0){//库存为0的，概率改为0
                $prize_info[$k]['probability'] = 0;
            }
            if($v['reward_type'] == 0){//没中奖
                $no_prize_key = $k;
            }
        }
        $all = 0;
        foreach ($prize_info as $key => $value) {//统计所有概率总和
            $all = bcadd($value['probability'],$all,30);
        }
        //把大奖减去的概率补给补空奖品
        $prize_info[$no_prize_key]['probability'] = bcadd(bcsub(70000,$all,30),$prize_info[$no_prize_key]['probability'],30);
        $probability = array();//初始化概率参数
        foreach ($prize_info as $key => $value) {
            $probability[] = $value['probability'];
        }

        //引入随机算法
        $re = $this->get_rand($probability);
        //return $re;
        $reward_id = $prize_info[$re]['reward_id'];
        //修改奖品库存,获取影响行数
        $row = $this->tips_reward_model->update_prize_stock($reward_id,$date);
        if($row == 0){//影响行数=0? 抽奖结果改为默认奖品
            $re = $no_prize_key;
            //$reward_id = $prize_info[$re]['reward_id'];
        }
        //如果是实物的，查询该用户这个月是否有中过实物奖
        if($prize_info[$re]['reward_type'] == 1){
           $user_prize_record = $this->tips_reward_model->get_user_prize_record($inter_id,$openid,'old');
            if(!empty($user_prize_record)){
                foreach($user_prize_record as $k=>$v){
                    if($v['reward_type'] == 1){//有中过实物，就不给你中了
                        $re = $no_prize_key;
                        //$reward_id = $prize_info[$re]['reward_id'];
                    }
                }
            }
        }
        //判断库存 如果没库存 设成默认奖
        if($prize_info[$re]['reward_type'] == 1 || $prize_info[$re]['reward_type'] == 2){
            $count = $this->tips_reward_model->get_reward_record_by_reward_id($prize_info[$re]['reward_id'],$prize_info[$re]['reward_type'],'old');
            if($count >= $prize_info[$re]['date_stock']){
                $re = $no_prize_key;
            }
        }

        //返回抽奖信息
        return $prize_info[$re];
    }

    //随机概率
    protected function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);             //抽取随机数
            if ($randNum <= $proCur) {
                $result = $key;                         //得出结果
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    public function test_draw(){
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        $a = array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'');
        for($i = 0;$i<100000;$i++){
            $res = $this->drawing();
           switch($res['reward_id']){
               case 1:
                   $a[0]=$a[0]+1;
                   break;
               case 2:
                   $a[1]=$a[1]+1;
                   break;
               case 3:
                   $a[2]=$a[2]+1;
                   break;
               case 4:
                   $a[3]=$a[3]+1;
                   break;
               case 5:
                   $a[4]=$a[4]+1;
                   break;
               case 6:
                   $a[5]=$a[5]+1;
                   break;
               case 7:
                   $a[6]=$a[6]+1;
                   break;
               case 8:
                   $a[7]=$a[7]+1;
                   break;
           }
        }
        foreach($a as $k=>$v){
            echo $k.'的数量；'.$v;
            echo '<hr/>';
        }
    }

    //测试
    /*public function ceshi(){
        $order_id = (int)$this->input->get('order_id', TRUE );
        $this->db->where(array(
            'order_id'=>$order_id,
            'inter_id'=>$this->inter_id,
            'pay_openid'=>$this->openid,
        ));
        $this->db->limit(1);
        $order = $this->db->get('tips_orders')->row_array();
        $notify =array();
        $notify['OpenID'] = $this->openid;
        $notify['HotelID'] = $order['hotel_id'];
        $notify['RewardName'] = $order['saler_name'];//分销员名字
        $notify['RewardPosition'] = '测试';
        $notify['RewardAmount'] = $order['pay_money'];
        $notify['DistributorID'] = $order['saler'];
        $notify['Score'] = $order['score'];
        $notify_res = $this->notify_suba($notify);
        var_dump($notify_res);die;
    }*/
    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->api_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function api_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'su8tips'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    //获取授权token
    protected function get_Token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        $this->_token = isset($token_info['data'])?$token_info['data']:"";
    }

}