<?php
class MY_Front_Member_Wxapp extends MY_Front_Wxapp{
	protected $_token;
	protected $_template;
	public function __construct() {
		parent::__construct ();
// 		$this->openid = 'oGaHQjgZz6OrKB_MIjgrQAyrmhRI';
		$this->get_Token();
		$this->create_member_info( $this->inter_id , $this->openid );
		//获取到微信信息，开始修改保存
		$this->updateWxInfo( $this->inter_id , $this->openid );
		//获取默认模板
		$this->member_template( $this->inter_id );
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
		$post_create_member = array(
			'inter_id'=>$inter_id,
			'token' =>$this->_token,
			'openid'=>$openid,
			);
		$this->doCurlPostRequest( INTER_PATH_URL."member/notify_new" , $post_create_member );
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
		MYLOG::w(serialize($log_data),"wxapp_member_api");
		//$this->api_write_log(serialize($log_data) );
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
}
