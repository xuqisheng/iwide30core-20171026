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
        $this->get_Token();
        //统计探针
       /* $this->load->library('MYLOG');
        MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));*/

	}

    /**
     * 带智能检测用户关注情况，视情况进行高级授权跳转
     */
    private function _get_wx_userinfo()
    {
        $this->load->model('wx/publics_model');
        $fans= $this->publics_model->get_fans_info( $this->openid,$this->inter_id );

        if( !$fans || empty($fans['nickname']) || empty($fans['headimgurl']) ){
            $userinfo= $this->publics_model->get_wxuser_info($this->inter_id, $this->openid );

            if( isset($userinfo['subscribe']) && $userinfo['subscribe']==0 ){
// return array();//BUG未解决先返回空数据
                //微信返回的信息显示没有关注，跳到图文页关注
                $redirect_url = 'http://www.baidu.com';
                redirect(  $redirect_url  );

            } else {
                $this->publics_model->update_wxuser_info($this->inter_id, $this->openid );
                return $userinfo;
            }

        } else {
            return $fans;
        }
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
        $this->display('tips/index',$data);
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
        //$arr['pay_money'] = $arr['row_total'] = 0.01;//测试
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
        //$order_id=(int)$this->input->get('order_id');
        $this->db->where(array(
            'order_id'=>(int)$this->input->get('order_id',true),
            'inter_id'=>$this->inter_id,
        ));
        $this->db->limit(1);
        $order = $this->db->get('tips_orders')->row_array();
        if($order && $order['pay_status'] == 2){//支付成功
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

            $this->display('tips/pay_res',$data);
        }else{
            //支付失败，调回首页
            redirect(site_url('tips/tips/index?id='.$order['inter_id'].'&saler='.$order['saler']));
            die;
        }
    }

    //查询是否是会员
    private function get_member_info(){

    }

    //是会员的就领券，不是会员的就登记
    public function get_reward(){
        $member_info = $this->get_member_info();
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        if($member_info){//如果是会员的就直接发放

            //发放
            //发放成功记录起来
        }else{
            $return['errcode'] = 999;
            $return['msg'] = '不是会员';
        }
        echo json_encode($return);
        die;
    }

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
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'membervip'. DS;
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