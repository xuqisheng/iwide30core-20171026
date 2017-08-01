<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(ENVIRONMENT=='development') error_reporting(E_ALL);

class Handle extends MY_Front {
    
	public function __construct()
	{
		parent::__construct();
	    //echo $this->openid;
	    //print_r($this);die;
	    
		$this->datas['signPackage']= $this->getSignPackage();
		//print_r($this->datas['signPackage']);die;
	}
	
	protected function _view($file, $datas=array() )
	{
	    $path= $this->module. '/'. $this->controller. '/';
	    $this->load->view($path. $file, $datas);
	}
	
	public function test_scan()
	{
	    //$ddata = array('card_id'=>$dcardid,'code'=>$dcode);
	    //$dret = qfpost('https://api.weixin.qq.com/card/code/consume?access_token='.$accesstoken, json_encode($ddata));
	    /**
	     * wx.scanQRCode({
	    	needResult: 1, //0：微信直接处理；1:返回扫码结果
	    	scanType: ["qrCode","barCode"],
	    	success: function (res) {
	    		var result = res.resultStr;
	    		$.post('/index.php/hoteladmin/comsume',{'dcardid':'<?php echo $cardid;?>','dcode':result},function(d){
	    			if(d.errcode==0){
						alert('核销成功！');
		    		}
	    			else {
	    				alert('核销失败！失败信息为：'+d.errmsg);
		    		}
	    		},'json');
			}
		});
	     */
	    $this->_view('header', $this->datas);
	    $this->_view('scan');
	    $this->_view('footer');
	}

	public function consume_callback()
	{
	    $this->load->helper('encrypt');
	    $encrypt_util= new Encrypt();
	    $token= $encrypt_util->encrypt($this->openid. date('YmdH') );
	    $return= array('status'=>2, 'message'=>'校验码超时。');
	
	    try {
	        $t= $this->input->post('t');
	        $openid= $this->input->post('openid');
	        $code= $this->input->post('code');
	        $this->load->model('mall/shp_orders');
	        $result= $this->shp_orders->qr_consumer($code, $openid, $this->inter_id);
	        if($token==$t){
	            if( isset($result['status']) && $result['status']==1){
	                $return['status']= 1;
	                $return['message']= $result['message'];
	
	                //记录最后操作时间
	                $this->load->model("core/priv_admin_authid", 'authid');
	                $this->authid->update_last_operation($this->openid);
	
	            } else {
	                $return['message']= $result['message'];
	            }
	        }
	        echo json_encode($return);
	
	    } catch (Exception $e) {
	        //echo $e->getMessage();
	        $return['message']= '处理过程出现问题！';
	        echo json_encode($return);
	    }
	}
	
	/**
	 * 测试地址：http://credit.iwide.cn/index.php/mall/handle/consume?id=a450089706&openid= 
	 */
	public function consume()
	{
	    //print_r($this);die;
	    //TODO: 是否在管理员授权表中状态正常
	    $this->load->model('core/priv_admin_authid', 'admin_authid');
	    $is_permit= $this->admin_authid->can_access($this->openid);
	    
	    $this->_view('header', $this->datas);
	    if($is_permit){
	        $this->load->helper('encrypt');
	        $encrypt_util= new Encrypt();
	        $token= $encrypt_util->encrypt($this->openid. date('YmdH') );
	        $data= array(
	            'title'=> '认证成功',
	            'message'=> '<a style="color:#000" href="javascript:call_qrcode();">点击开启扫码功能</a>',
	            'callback'=> EA_const_url::inst()->get_url('*/*/consume_callback', array('id'=> $this->inter_id)),
	            'openid'=> $this->openid,
	            't'=> $token,
	        );
	        $this->_view('consume', $data);
	        
	    } else {
	        $message= '您的微信号未经授权，不能进行此操作。';
	        $this->_view('deny', array('message'=> $message) );
	    }
	    $this->_view('footer');
	}

	private function getSignPackage($url='')
	{
	    $this->load->helper('common');
	    $this->load->model('wx/publics_model', 'publics');
	    $this->load->model('wx/access_token_model');
	    $jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'));
	    //$jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'), $this->openid);
	
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    if(!$url)
	        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	    $timestamp = time();
	    $nonceStr = createNonceStr();
	    $public = $this->publics->get_public_by_id( $this->session->userdata('inter_id') );

	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	    $signature = sha1($string);
	    $signPackage = array(
	        "appId"     => $public['app_id'],
	        "nonceStr"  => $nonceStr,
	        "timestamp" => $timestamp,
	        "url"       => $url,
	        "signature" => $signature,
	        "rawString" => $string
	    );
	    return $signPackage;
	}
}
