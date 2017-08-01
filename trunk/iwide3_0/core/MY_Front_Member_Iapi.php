<?php
// +----------------------------------------------------------------------
// | 前台会员基础类
// +----------------------------------------------------------------------
// | Auth: Jperation http://www.iwide.cn
// +----------------------------------------------------------------------
// | Create: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// MY_Front_Member_Iapi.php 2017-07-12
// +----------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Front_Member_Iapi extends MY_Front_Iapi{
	protected $_token = '';
	protected $_template;
    protected $_template_filed_names;
    protected $user_info = array();

	public function __construct() {
		parent::__construct ();
		
		MYLOG::member_tracker($this->openid, $this->inter_id);
		
//		$this->get_Token();
		$this->create_member_info( $this->inter_id , $this->openid );
		//获取到微信信息，开始修改保存
		$this->updateWxInfo( $this->inter_id , $this->openid );
		//获取默认模板
		$this->member_template( $this->inter_id );
        //自定义文字设定
        $this->template_filed_name_set();
	}

    /**
     * 会员前后端分离数据解析通用方法
     * @param string $type  api-请求结果, countent - 内容输出
     * @param array $result 数据内容，如果有请求接口的数据，放在$data['curl_data']
     *                    eg: array(
    'a'=>'xxx',
     *                          'b'=>'xxx',
     *                          'curl_data'=>array(
    'c'=>'xxx'
     *                                          'd'=>'xxx'
     *                          )
     *
     *                       )
     * @param string $fun  调用的方法的标识 详情见MY_Front_Iapi::out_put_msg
     * @param array $extra 非主体数据 详情见MY_Front_Iapi::out_put_msg
     * @param int $msg_lv 消息级别 详情见MY_Front_Iapi::out_put_msg
     */
	protected function sp_out_put_msg($type = 'api',$result = array(),$fun = '',$extra = array(),$msg_lv = 0){
	    if($type == 'api'){
            if(empty($result)) {
                $this->out_put_msg(3,'请求失败',array(),$fun,$extra,$msg_lv);
            }

            $curl_result = $this->parse_curl_msg($result);
            if($curl_result['code']==1000){
                $this->out_put_msg(1,$curl_result['msg'],$curl_result['data'],$fun,$extra);
            }else{
                $this->out_put_msg(3,$curl_result['msg'],$curl_result['data'],$fun,$extra,$msg_lv);
            }
        }elseif($type == 'countent'){
            if(!empty($result['curl_data'])){
                $curl_data = $result['curl_data'];
                unset($result['curl_data']);
                if(is_array($curl_data)){
                    foreach ($curl_data as $key => $item){
                        $_curl_data = $this->parse_curl_msg($item);
                        if($_curl_data['code'] == '1000' && !empty($_curl_data['data'])){
                            $_data[$key] = $_curl_data['data'];
                            $result = array_merge($result,$_data);
                        }
                    }
                }
            }
            $code = !empty($result)?1:3;
            $msg = !empty($result)?'ok':'返回数据为空';
            $this->out_put_msg($code,$msg,$result,$fun,$extra,$msg_lv);
        }
    }

    /**
     * 解析接口返回的数据
     * @param array $return 接口返回的信息
     * @return array
     */
    protected function parse_curl_msg($return = array()){
        $result = 1004;
        $err = '40003';
        $msg = '请求失败';
        $data = array();
        if(isset($return['err'])){
            $err = $return['err'];
            if($return['err'] == '0') {
                $result = 1000;
                $msg = !empty($return['msg'])?$return['msg']:'ok';
            }else {
                $msg = !empty($return['msg'])?$return['msg']:'请求失败';
            }
        }elseif (!isset($return['err'])){
            if(!empty($return['data'])) {
                $result = 1000;
                $err = 0;
                $msg = !empty($return['msg'])?$return['msg']:'ok';
            }else {
                $msg = !empty($return['msg'])?$return['msg']:'请求失败';
            }
        }
        $data = !empty($return['data'])?$return['data']:array();
        return array(
            'err'=>$err,
            'code'=>$result,
            'msg'=>$msg,
            'data'=>$data
        );
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

	//会员模块信息建立
	protected function create_member_info( $inter_id , $openid ){
        //获取用户的信息
        $this->load->model('wx/Publics_model');
        $userinfo =$this->Publics_model->get_fans_info($openid);

        $post_create_member = array(
            'inter_id'=>$inter_id,
            'token' =>$this->_token,
            'openid'=>$openid,
            'nickname'=>!empty($userinfo['nickname'])?$userinfo['nickname']:'',
        );
		$result = $this->doCurlPostRequest(INTER_PATH_URL."member/create_update_member_info" , $post_create_member );
		if(!empty($result['data'])){
		    $this->user_info = $result['data'];
        }
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
        $startime = microtime(true);
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $url .= '?t='.time();
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, false);
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
        $endtime = microtime(true);
        //写入日志
        $log = [
            'namespace'=>'core/MY_Front_Member',
            'curl'=>$url,
            'param'=>$post_data,
            'timeout'=>$timeout,
            'usetime'=>($endtime - $startime),
            'result'=>$res
        ];
        $this->write_log(@json_encode($log),'membervip/access_log');
        return json_decode($res,true);
	}

    public function write_log($log,$path = '',$key=''){
        $this->load->library('MYLOG');
        MYLOG::w($log,$path,$key);
    }

	/**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function api_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d'). '.txt';
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

    protected function updateWxInfo( $inter_id , $openid ){
    	//获取用户的信息
    	$this->load->model('wx/Publics_model');
	    $userinfo =$this->Publics_model->get_fans_info($openid);
	    //更新用户的信息
	    $updateInfo = array(
	    	'nickname'=>$userinfo['nickname'],
			'is_auto'=>1,
	    	);
	    $post_savevip_url = PMS_PATH_URL."member/save_memberinfo";
		$post_savevip_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'data'=>$updateInfo,
			);
		$this->doCurlPostRequest( $post_savevip_url , $post_savevip_data );
    }

    protected function member_template( $inter_id ){
	    $post_tem_url = PMS_PATH_URL."member/member_template";
		$post_tem_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			);
		$result = $this->doCurlPostRequest( $post_tem_url , $post_tem_data );
		$this->_template = $result['data'];
    }

    protected function template_filed_name_set($inter_id =''){
        $fields_array = array(
            'credit'   => '积分',
            'balance'  => '余额',
            'coupon'   => '优惠券'
        );
        $post_data = array(
            'inter_id' => empty($inter_id)?$this->inter_id:$inter_id
        );
        $custom_config =  $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_custom_field_rule" , $post_data );
        if(isset($custom_config['value']) && !empty($custom_config['value'])){
            $data = json_decode($custom_config['value'],true);
            $data['config_id'] = $custom_config['id'];
        }
        foreach($fields_array as $key => $v){
            if(isset($data[$key]['name']) && !empty($data[$key]['name'])){
                $fields_array[$key] = $data[$key]['name'];
            }
        }

        $display_arr = array();
        foreach($fields_array as $k => $v){
            $display_arr[$k."_name"] = $v;
        }
        $this->_template_filed_names = $display_arr;

    }
    /**
     * Ajax方式返回数据到客户端
     * @param string $message
     * @param array $data
     * @param int $status
     */
    protected function _ajaxReturn($message='', $data=array(), $status=0){
        $result= new stdClass();
        $result->message= $message;
        $result->data= $data;
        $result->status= $status;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($result));
    }

    /**
     * @param $route
     * @param $template
     * @param $file_name
     * @param array $data
     */
    protected function template_show($route,$template,$file_name,$data = array()){
        $view_path  = $route."/";
        if(!empty($template)){
            $view_path .= $template."/";
        }
        $file =  VIEWPATH.$view_path.$file_name.".php";
        if((file_exists($file))){
            $display_path = $view_path.$file_name;
        }else if($template !='version4'){
            $display_path = $route."/phase2/".$file_name;
        }else{
            $display_path = $route."/version4/".$file_name;
        }

        $this->load->view($display_path,$data);
    }
}
