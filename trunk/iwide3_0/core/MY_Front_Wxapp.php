<?php
// require_once dirname ( dirname ( __FILE__ ) ) . "/libraries/App/Wxapp_conf.php";
class MY_Front_Wxapp extends CI_Controller {
	public $inter_id;
	public $token;
	public $source;
	public $fans_ext;
	public $openid;
	public $wxapp_openid;
	public function __construct() {
		parent::__construct ();
		$this->debug = TRUE;
		$this->_init_router ();
		$this->_base_input_valid ();
// 		$this->openid = 'oX3WojhfNUD4JzmlwTzuKba1MywY';
	}
	protected function _base_input_valid() {
		$this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		//判断是否是要用到get请求，如果是则将source中的token和inter_id附值
		//部分如图片验证码，二维码功能需要用到
		MYLOG::w(json_encode($this->source),"wxapp");
		if($this->checkActionRequestUseGet()){
		
			$this->source['token'] = $_GET['token'];
			$this->source['inter_id'] = $_GET['inter_id'];
		}
		if(isset($_GET['debug']) && $_GET['debug'] == 1){
			$this->source = $_REQUEST;
			$this->source['inter_id'] = $_REQUEST['id'];
			$this->source['send_data'] = $_REQUEST;
		}
		
		if ($this->source) {
			$_REQUEST = $this->source['send_data'];
			$_GET = $this->source['send_data'];
			$_POST = $this->source['send_data'];
			// if (! isset ( $source ['signature'] )) {
			// $this->out_put_msg ( FALSE, 'Invalid Signature' );
			// }
			// $sign = $source ['signature'];
			// unset ( $source ['signature'] );
			// $this->load->model ( 'interface/Isigniture_model' );
			// $token = $this->get_inter_id_token ( $source ['itd'] );
			// if (empty ( $token )) {
			// $this->out_put_msg ( FALSE, 'Invalid Parameter "itd"' );
			// }
			// $signature = $this->Isigniture_model->get_sign ( $source, $token );
			// if ($sign != $signature) {
			// $this->out_put_msg ( FALSE, 'Signiture error' );
			// }
			if (empty ( $this->source ['inter_id'] )) {
				$this->out_put_msg ( 1 );
			}
			$this->inter_id = $this->get_source ( 'inter_id', '', FALSE );
			$nobug = $this->get_source ( 'nobug', '', FALSE );
			$this->inter_id = substr ( $this->inter_id, 0, 10 );
			if (($this->action !== 'wx_login') && ($this->debug !==TRUE || empty($nobug))) {
				if (empty ( $this->source ['token'] )) {
					$this->out_put_msg ( 4 );
				}
				$this->token = $this->get_source ( 'token', '', FALSE ); 
				if ($this->module == 'user' && $this->action == 'login'){
					$this->fans_ext = $this->get_token_session ( $this->inter_id, $this->token,'login' ); 
				}else{
					$this->fans_ext = $this->get_token_session ( $this->inter_id, $this->token,'db' );
				}
				if (empty ( $this->fans_ext['wxapp_openid'] )) {
					$this->out_put_msg ( 4 );
				}
				$this->wxapp_openid = $this->fans_ext['wxapp_openid'];

				//$this->openid = $this->fans_ext['wxapp_openid'];
				$this->openid = empty($this->fans_ext['openid'])?$this->fans_ext['wxapp_openid']:$this->fans_ext['openid'];
				//$this->openid = 'oo89wt8lQ2CVjHalpgluewtC5t1A';
				/*if($this->inter_id == 'a421641095'){
					$this->openid = 'oGaHQjiAXn1o_wv9LRNBV7CvADLA';
				}*/
				
				MYLOG::Wxapp_tracker($this->openid, $this->inter_id);
				//Wxapp_tracker
				
			}
		} else {
			$this->out_put_msg ( 1 );
			exit ();
		}
		$fake_inter_id=$this->get_source('fake_inter_id');
		if ($fake_inter_id&&$this->debug){
			$this->inter_id=$fake_inter_id;
		}
	}
	protected function get_token_session($inter_id, $token,$from='db') {
		
		//暂时写死interid 为a450089706的帐号
		/*if($inter_id == 'a450089706'){
			
			$arr = array();
			$arr['wxapp_openid'] = 'o9Vbtw30wn-MHB5TLqac2jJNvha4';
			$arr['openid'] = 'o9Vbtw30wn-MHB5TLqac2jJNvha4';
			return $arr;
		}*/

		//暂时写死interid 为a450089706的帐号
                if($inter_id == 'a441098524'){
			if($token == "123456"){
                        	$arr = array();
                        	$arr['wxapp_openid'] = 'osy3r0N6iGze_KhXT-U5W_-g30og';
                        	$arr['openid'] = 'oolmns8z7D4VhPm4kIddhNntRHCc';
                        	return $arr;
			}
                }

		
		if ($from=='db'){
			$this->db->limit ( 1 );
			$this->db->where ( array (
					'inter_id' => $inter_id,
					'wxapp_token' => $token 
			) );
			$ext = $this->db->get ( "fans_ext" )->row_array ();
			if (empty ( $ext ) || $ext ['session_key_validtime'] < time ()) {
				return NULL;
			} else
				return $ext;
		}else if ($from=='login'){
			$ext=json_decode($this->user_session('fans_ext'),TRUE);
			if (!empty($ext)&& $ext ['session_key_validtime'] > time ()){
				return $ext;
			}
			return NULL;
		}
	}
	/**
	 * @param int $result 运行结果 具体值看Wxapp_conf
	 * @param string $msg 显示给用户的信息
	 * @param array $data 数据集
	 * @param string $fun 调用的方法的标识 如hotel/search
	 * @param number $msg_lv 消息级别  具体值看Wxapp_conf
	 * @param string $exit 输出数据后是否退出整个程序
	 */
	protected function out_put_msg($result, $msg = '', $data = array(), $fun = '', $msg_lv = 0, $exit = TRUE) {
		require_once dirname ( dirname ( __FILE__ ) ) . "/libraries/App/Wxapp_conf.php";
		$info = array ();
		$status_arr = Wxapp_conf::get_enums ( 'status' );
		$msg_lvs = Wxapp_conf::get_enums ( 'msg_lv' );
		$result = isset ( $status_arr [$result] ) ? $status_arr [$result] : 1004;
		$info ['status'] = $result;
		$info ['msg'] = $msg;
		$info ['msg_type'] = $msg_lvs [$msg_lv];
		if (! empty ( $data )) { 
			$data = json_decode ( json_encode ( $data ), TRUE );
			$info ['web_data'] = $this->data_dehydrate ( $data, Wxapp_conf::get_dehydrate_samples ( $fun ) );
		}
		ob_clean();
		echo json_encode ( $info, JSON_UNESCAPED_UNICODE );
		if ($exit) {
			exit ();
		}
	}
	/**返回source中数据
	 * @param string $index 数据在数组中下标
	 * @param string $filter 过滤函数 按需添加
	 * @param string $in TRUE 在send_data中取,FALSE 在外层数据取
	 * @return NULL|mixed
	 */
	protected function get_source($index = '', $filter = '', $in = TRUE) {
		if ($index === '')
			return $this->source;
		if ($in)
			$data = isset ( $this->source ['send_data'] [$index] ) ? $this->source ['send_data'] [$index] : NULL;
		else
			$data = isset ( $this->source [$index] ) ? $this->source [$index] : NULL;
		if (isset ( $data ) && ! empty ( $filter )) {
			switch ($filter) {
				case 'int' :
					$data = intval ( $data );
					break;
				default :
					break;
			}
		}
		return $data;
	}
	/** 获得对应token的session数据
	 * @param unknown $key
	 * @return mixed|NULL|mixed|NULL
	 */
	protected function user_session($key) {
		$session_driver=$this->get_session_driver();
		$user_key=$this->inter_id.$this->token;
		$data=$session_driver->get_data($user_key);
		if (!empty($data)){
			$data=json_decode($data,TRUE);
			if ($key === ''){
				return $data;
			}
			return empty($data[$key])?NULL:$data[$key];
		}
		return NULL;
	}
	/**设置对应token的session数据，session 所用key是$inter_id.$token，值为json字符串，取时先拿出来decode再赋值
	 * @param unknown $key
	 * @param unknown $value
	 * @param unknown $time
	 * @return boolean
	 */
	protected function set_user_session($key,$value,$time=NULL) {
		$session_driver=$this->get_session_driver();
		$user_key=$this->inter_id.$this->token;
		$origin=$this->user_session($user_key);
		empty($origin)?$origin=array($key=>$value):$origin[$key]=$value;
		if ($session_driver->set_data($user_key,json_encode($origin),$time))
			return TRUE;
		return FALSE;
	}
	/**
	 * 获取当前使用的session驱动 
	 */
	protected function get_session_driver(){
		if (!isset($this->wxapp_session_driver)){
			$this->load->model('wxapp/Redis_model','wxapp_session_driver');
		}
		return $this->wxapp_session_driver;
	}
	/**
	 * @param unknown $data 传入数据，只能为数组
	 * @param unknown $mode 数据筛选模板 Wxapp_conf中$dehydrate_samples定义
	 * @return unknown|NULL[]|unknown
	 */
	public function data_dehydrate($data, $mode) {
		if (empty ( $mode ))
			return $data;
		$tmp = array ();
		if (! empty ( $mode ['ks'] )) {
			$mode ['ks'] = array_flip ( $mode ['ks'] );
			$tmp = array_intersect_key ( $data, $mode ['ks'] );
		}
		if (! empty ( $mode ['kas'] )) {
			foreach ( $mode ['kas'] as $mk => $mod ) {
				$tmp [$mk] =isset($data [$mk])? $this->data_dehydrate ( $data [$mk], $mod ):NULL;
			}
		}
		if (! empty ( $mode ['fks'] )) {
			foreach ( $mode ['fks'] as $mk => $mod ) {
				if (isset($data [$mk])){
					foreach ( $data [$mk] as $fk => $fm ) {
						$tmp [$mk] [$fk] = $this->data_dehydrate ( $fm, $mod );
					}
				}else {
					$tmp [$mk] = NULL;
				}
			}
		}
		return $tmp;
	}
	protected $module = '';
	protected $controller = '';
	protected $action = '';
	/**
	 * @author libinyan          
	 */
	protected function _init_router() {
		$URI = & load_class ( 'URI', 'core', NULL );
		$segments = $URI->segments;
		$this->module = $segments [1];
		$this->controller = isset ( $segments [2] ) ? $segments [2] : 'index';
		$this->action = isset ( $segments [3] ) ? $segments [3] : 'index';
		return;
	}
	
	/**
	 * 
	 * 判断当前action是否用get请求，部分功能如图片验证码，二维码等需要用到
	 * 如果是使用get的action，则返回true,否侧返回false
	 */
	protected function checkActionRequestUseGet(){
		
		//以后移至配置文件里
		$_use_GET_action = array("pic_code");
		
		if(in_array($this->action,$_use_GET_action)){
			return true;
		}else{
			return false;
		}
		
		
	}
	
	
}
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Front_Member_Wxapp.php";
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Front_Soma_Wxapp.php";
