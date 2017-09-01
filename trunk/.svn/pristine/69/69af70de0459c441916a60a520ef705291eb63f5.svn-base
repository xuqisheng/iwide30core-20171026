<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Test extends CI_Controller {
	public $common_data;
	
	function __construct() {
		parent::__construct ();
		
		
	}
	
	public function index(){
		$this->load->model ( 'api/common_model' );
		$token = $this->common_model->get_inter_id_token("d460605501");
		echo $token;
		//echo json_encode(array('errmsg'=>'faild'));
	}
	
	
	private function get_reply_contents_by_keyword($keyword,$openid='',$qrcode_id='') {
		/*start 为富林酒店定制功能，对接其已接入的免费wifi接口*/
		//phone_wifi增加在自动回复里，用手机连接wifi接口调用，返回url
		if($keyword == "我要上网"){
			/* $type = 'text';
			//用户微信openid
			$openid = $openid;
			//公众号的微信号；如果无，则传入公众号的openid
			$weixin_id = $this->wx_master_id;
			$content = '单击这里<a href="'.$this->getWifiRandomUrl($openid,$weixin_id).'">一键上网</a>';
			$arr = array();
			array_push($arr,array (
					'Content' => $content
			) );
			return array($arr,$type); */
			return null;
		}
		//company_wifi增加在自动回复里，用电脑连wifi返回密码
		if($keyword == '获取验证码'){
			/* $type = 'text';
			//用户微信openid
			$openid = $openid;
			//公众号的微信号；如果无，则传入公众号的openid
			$weixin_id = $this->wx_master_id;
				
			$wifi_password = $this->getWifiPasswordForComputer($openid, $weixin_id);
				
			$content = "您的上网验证码是：{$wifi_password}。15分钟有效。";
				
			$arr = array();
			array_push($arr,array('Content'=>$content));
				
			return array($arr,$type); */
			return null;
		}
		/*end 为富林酒店定制功能，对接其已接入的免费wifi接口*/
	
	
		$this->load->model ('wx/Keyword_reply_model');
		$result = $this->Keyword_reply_model->get_keyword_reply_text_all($keyword,"a455510007");
		
		
		if((!empty($result)) && $result->num_rows() > 0){
			$arr = array();
			$type = 'text';
				
			$data = $result->result();
			foreach ($data as $item){
				$url = $item->url;
	
				$url = str_replace("openid=1",'openid='.$openid,$url);
				if(strpos($url,'saler=1')!==false){
					$url = str_replace("saler=1",'saler='.$qrcode_id,$url);
				}
	
				if($item->type == 1){
					$type='news';
				}
	
				array_push($arr,array (
						'Title' => $item->title,
						'Description' => $item -> description,
						'PicUrl' => $item ->pic_url,
						'Url' => $url
				));
			}
			return array($arr,$type);
		}else{
			return "";
		}
	}
	
}