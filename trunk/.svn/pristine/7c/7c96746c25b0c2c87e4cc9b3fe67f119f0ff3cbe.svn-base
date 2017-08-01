<?php
class Wechatcard extends MY_Front_Member{

	function index(){
		$package = $this->getSignPackage();
        $inter_id = $this->input->get('id');
        $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
        $data = $this->wechatMembercard->get_config($inter_id);

		$tmp['cardId']=$data['card_id'];

		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
// 		$apiTicket = 'm7RQzjA_ljjEkt-JCoklRI_esR4R_mcK6OomDr8pNPWvKoaWpJZc3ZZ-z6SMeEo4FtE3u9bAzqHK8DnVsBtHjQ';
		$apiTicket = $this->access_token_model->get_card_ticket($inter_id);
		$str = createNonceStr();
		$p=$this->getSignCard($tmp['cardId'],$apiTicket,null,$str);

		$tmp['cardExt']=json_encode(array(
				'timestamp'=>$p['timestamp'],
				'signature'=>$p['signature'],
				'nonce_str'=>$str),JSON_FORCE_OBJECT);
		$str = <<<EOF
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset="utf-8">
		<title>领取会员卡</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
		</head>
		<body ontouchstart="">
		</body>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script>
		wx.config({
			debug: false,
			appId: '{$package['appId']}',
			timestamp: {$package['timestamp']},
			nonceStr: '{$package['nonceStr']}',
			signature: '{$package['signature']}',
			jsApiList: [ 'addCard', 'chooseCard', 'openCard' ]
		});
		wx.ready(function () {
		  wx.addCard({
			      cardList: [
			        {cardId: '{$tmp['cardId']}',
			          cardExt: '{$tmp['cardExt']}'}
			      ],
			      success: function (res) {alert('已添加会员卡' );},
			      cancel: function (res) {WeixinJSBridge.call('closeWindow');}
			    });
		    });
			</script>
			</html>
EOF;
		echo $str;
	}
	public function getSignPackage($url='') {
		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
		$jsapiTicket = $this->access_token_model->get_api_ticket($this->input->get('id'));
// 		$jsapiTicket = 'm7RQzjA_ljjEkt-JCoklRI_esR4R_mcK6OomDr8pNPWvKoaWpJZc3ZZ-z6SMeEo4FtE3u9bAzqHK8DnVsBtHjQ';
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		if(!$url)
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$timestamp = time();
			$nonceStr = createNonceStr();
			// 这里参数的顺序要按照 key 值 ASCII 码升序排序
			$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

			$signature = sha1($string);

			$this->load->model('wx/publics_model');
			$public=$this->publics_model->get_public_by_id($this->input->get('id'));
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
	public function getSignCard($card_id,$app_secret,$code=null,$str=''){
		$timestamp = time();
		$signature = new Signature();
		$signature->add_data( $timestamp );
		if(!is_null($str))$signature->add_data( $str );
		$signature->add_data( $card_id );
		$signature->add_data( $app_secret );
		if(!is_null($code))$signature->add_data( $code );
		return array('signature'=>$signature->get_signature(),'timestamp'=>$timestamp);
	}


    //监测是否已经登录了
    public function verify_user_login(){
        $this->load->model('wx/Publics_model');
        $data['info'] =$this->Publics_model->get_fans_info($this->openid);
        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $centerData = $this->doCurlPostRequest( $post_center_url , $post_center_data );
        $params = http_build_query($_GET);
        if(!empty($centerData) && isset($centerData['data']) && !empty($centerData['data'])){
            $memberInfo = $centerData['data'];

            $centerData['card_id'] = $_GET['card_id'];
            $centerData['card_code'] = $_GET['encrypt_code'];
            $centerData['params'] = $params;
            //已经登录了
            if($memberInfo['member_mode'] == 2 && $memberInfo['is_login'] == 't'){


                /*获取微信JSSDK配置*/
                $centerData['wx_config'] = $this->_get_sign_package($this->inter_id);
                $js_api_list = $menu_show_list = $menu_hide_list= '';
                $centerData['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
                $centerData['js_api_list'] = $centerData['base_api_list'];

                foreach ($centerData['js_api_list'] as $v){
                    $js_api_list.= "'{$v}',";
                }

                $centerData['js_api_list']= substr($js_api_list, 0, -1);

                //主动显示某些菜单
                $centerData['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline');
                $menu_show_list = '';
                foreach ($centerData['js_menu_show'] as $v){
                    $menu_show_list.= "'{$v}',";
                }
                $centerData['js_menu_show']= substr($menu_show_list, 0, -1);

                //主动隐藏某些菜单
                $centerData['js_menu_hide']= array('menuItem:share:appMessage','menuItem:share:timeline','menuItem:copyUrl','menuItem:share:email','menuItem:originPage','menuItem:favorite');
                $menu_hide_list = '';
                foreach ($centerData['js_menu_hide'] as $v){
                    $menu_hide_list.= "'{$v}',";
                }
                $centerData['js_menu_hide']= substr($menu_hide_list, 0, -1);
                /*end配置*/
                
                
                $this->template_show('member',$this->_template,'confirm_activate',$centerData);
                return;
            }
        }

        redirect('membervip/wechatcard/login_activate?'.$params);

    }

    //通过已经登录的信息激活
    public function do_activate(){
        $inter_id = $this->inter_id;
        $open_id = $this->openid;
        $card_id = $_POST['card_id'];
        $code = $_POST['card_code'];

        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=>$this->inter_id,
            'openid' =>$this->openid,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $centerData = $this->doCurlPostRequest( $post_center_url , $post_center_data );
        if(empty($centerData) || !isset($centerData['data']) || empty($centerData['data']) || $centerData['data']['member_mode'] != 2  || $centerData['data']['is_login'] != 't'){
            echo json_encode(array('err'=> 400,'msg'=>'会员卡有误'));
            exit;
        }else{
            $memberInfo =  $centerData['data'];
        }
        $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");


        $param = $this->wechatMembercard->format_data($inter_id,$card_id,$code,$memberInfo);
        $res = $this->wechatMembercard->do_active($inter_id,$param ,$card_id ,$open_id);
        echo json_encode($res);
    }

    //通过登录激活
    public function login_activate(){
        /*获取微信JSSDK配置*/
        $data['wx_config'] = $this->_get_sign_package($this->inter_id);
        $js_api_list = $menu_show_list = $menu_hide_list= '';
        $data['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        $data['js_api_list'] = $data['base_api_list'];

        foreach ($data['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }

        $data['js_api_list']= substr($js_api_list, 0, -1);

        //主动显示某些菜单
        $data['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline');
        $menu_show_list = '';
        foreach ($data['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $data['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        $data['js_menu_hide']= array('menuItem:share:appMessage','menuItem:share:timeline','menuItem:copyUrl','menuItem:share:email','menuItem:originPage','menuItem:favorite');
        $menu_hide_list = '';
        foreach ($data['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $data['js_menu_hide']= substr($menu_hide_list, 0, -1);
        /*end配置*/

        /*会员卡验证*/
        $post_config_url = PMS_PATH_URL."adminmember/getloginconfig";
        $post_config_data =  array(
            'inter_id'=>$this->inter_id,
        );
        $data['params'] = http_build_query($_GET);
        //请求注册配置
        $data['login_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $data['inter_id'] = $this->inter_id;
        $data['card_code'] = (isset($_GET['encrypt_code']))?$_GET['encrypt_code']:'';
        $data['card_id'] = (isset($_GET['card_id']))?$_GET['card_id']:'';
        $this->template_show('member',$this->_template,'login_activate',$data);
    }

    //通过登录激活请求
    public function login_activate_post(){

        $inter_id = $this->inter_id;
        $open_id = $this->openid;
        $card_id = $_POST['card_id'];
        $code = $_POST['card_code'];

        $this->session->unset_tempdata($inter_id.'vip_user');
        $post_login_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$open_id,
            'data'=>$_POST,
        );


        /*验证是否登录了*/
        $this->load->model("membervip/front/Member_model","memberModel");
        $res = $this->memberModel->member_login($post_login_data);


        if(!empty($res) && isset($res['err']) && $res['err'] == 0 && isset($res['data'])){
            /*为保持数据一致性，获取卡券*/
            $post_center_url = PMS_PATH_URL."member/center";
            $post_center_data =  array(
                'inter_id'=>$this->inter_id,
                'openid' =>$this->openid,
            );
            $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
            //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
            $centerData = $this->doCurlPostRequest( $post_center_url , $post_center_data );
            $param = $this->wechatMembercard->format_data($inter_id,$card_id,$code,$centerData['data']);

            $res = $this->wechatMembercard->do_active($inter_id,$param,$card_id,$open_id);
            echo json_encode($res);
            exit;
        }else{

            echo json_encode($res);
        }




    }

    //通过注册激活
    public function reg_activate(){

        /*获取微信JSSDK配置*/
        $data['wx_config'] = $this->_get_sign_package($this->inter_id);
        $js_api_list = $menu_show_list = $menu_hide_list= '';
        $data['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        $data['js_api_list'] = $data['base_api_list'];

        foreach ($data['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }

        $data['js_api_list']= substr($js_api_list, 0, -1);

        //主动显示某些菜单
        $data['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline');
        $menu_show_list = '';
        foreach ($data['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $data['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        $data['js_menu_hide']= array('menuItem:share:appMessage','menuItem:share:timeline','menuItem:copyUrl','menuItem:share:email','menuItem:originPage','menuItem:favorite');
        $menu_hide_list = '';
        foreach ($data['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $data['js_menu_hide']= substr($menu_hide_list, 0, -1);
        /*end配置*/

        $post_config_url = PMS_PATH_URL."adminmember/getregconfig";
        $post_config_data =  array(
            'inter_id'=>$this->inter_id,
        );
        $this->load->model('wx/publics_model', 'publics');
        $public = $this->publics->get_public_by_id( $this->inter_id );
        $data['public'] = $public;
        //请求注册配置
        $data['login_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $data['inter_id'] = $this->inter_id;

        $this->template_show('member',$this->_template,'reg_activate',$data);
    }

    //通过注册激活请求
    public function reg_activate_post(){
        $inter_id = $this->inter_id;
        $open_id = $this->openid;
        $card_id = $_POST['card_id'];
        $code = $_POST['card_code'];

        $this->session->unset_tempdata($this->inter_id.'vip_user');
        //验证图片验证码
        if(isset($_POST['smspic'])){
            if($_SESSION['code'] != $_POST['smspic']){
                $msginfo['err'] = '40003';
                $msginfo['msg'] = '图片验证码错误';
                echo json_encode($msginfo);exit;
            }
        }
        $post_login_data = $_POST;
        $post_login_data['inter_id'] = $inter_id;
        $post_login_data['openid'] = $open_id;
        $this->load->model("membervip/front/Member_model","memberModel");
        $login_res = $this->memberModel->member_reg($post_login_data);

        if(!empty($login_res) && isset($login_res['err']) && $login_res['err'] == 0 && isset($login_res['data'])){
            /*为保持数据一致性，获取卡券*/
            $post_center_url = PMS_PATH_URL."member/center";
            $post_center_data =  array(
                'inter_id'=>$this->inter_id,
                'openid' =>$this->openid,
            );
            //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
            $centerData = $this->doCurlPostRequest( $post_center_url , $post_center_data );
            $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
            $param = $this->wechatMembercard->format_data($inter_id,$card_id,$code,$centerData['data']);

            $res = $this->wechatMembercard->do_active($inter_id,$param,$card_id,$open_id);
            if(!empty($res)){
                $res['is_package'] = $login_res['is_package'];
            }
            echo json_encode($res);
            exit;
        }

        echo json_encode($login_res);
    }


//    //激活初始化数据格式化
//    public function format_data($inter_id,$card_id,$code,$memberInfo){
//
//        $this->load->model('membervip/front/Member_model');
//        $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
//        $dbCardData = $this->wechatMembercard->get_config($inter_id,$card_id);
//
//        if(empty($dbCardData)){
//            echo json_encode(array('err'=> 400,'msg'=>'不存在此微信卡'));
//            exit;
//        }
//
//        $card_data = json_decode($dbCardData['content'],true);
//
//        $cardConfig = $card_data['member_card'];
//
//        $decrypt_code = $this->wechatMembercard->code_decrypt($this->inter_id,$code);
//        $this->Member_model->update_wechat_card_code($this->openid,$this->inter_id,$decrypt_code,$card_id);
//
//
//        /*初始化数据*/
//        if(isset($cardConfig['supply_bonus']) && $cardConfig['supply_bonus']) $param['init_bonus'] = $memberInfo['credit'];     //有配置积分
//        if(isset($cardConfig['supply_balance']) && $cardConfig['supply_balance']) $param['init_balance'] = $memberInfo['balance'] * 100; //有配置余额，微信需要乘以100
//        $param['membership_number'] = $memberInfo['membership_number'];
//        $param['code'] = $decrypt_code;
//        //自定义快捷栏
//        for($i=0;$i<3;$i++){
//            if(!isset($cardConfig['custom_field'.$i]) || ( empty($cardConfig['custom_field'.$i]) || empty($cardConfig['custom_field'.$i]['name']) )) continue;
//            switch($cardConfig['custom_field'.$i]['name_type']){
//                case "FIELD_NAME_TYPE_LEVEL":  //等级
//                    $param['init_custom_field_value'.$i] = $memberInfo['lvl_name'];
//                    break;
//                case "FIELD_NAME_TYPE_COUPON":  //优惠券
//                    $param['init_custom_field_value'.$i] = $memberInfo['card_count'];
//                    break;
//                default:
//                    break;
//            }
//        }
//        /*初始化数据 end*/
//
//        return $param;
//    }

    /*检查是否合法请求*/
    function profile_validate(){
        if($_GET['openid'] != $this->openid) return false;


        return true;
    }


    /**
     * 获取微信JSSDK配置信息
     * @param $inter_id
     * @param string $url
     * @return array
     */
    protected function _get_sign_package($inter_id, $url=''){
        $this->load->helper('common');
        $this->load->model('wx/publics_model', 'publics');
        $this->load->model('wx/access_token_model');
        $jsapiTicket = $this->access_token_model->get_api_ticket( $inter_id );

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if(!$url)
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();
        $public = $this->publics->get_public_by_id( $inter_id );

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


    /**分销**/
    function my_qrcode(){
        $this->load->model('distribute/staff_model');
        $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
        $saler_info = $this->staff_model->get_my_info($this->openid,$this->inter_id);
        $is_saler_valid = $this->staff_model->saler_is_valid($this->inter_id,$this->openid);
        if(!isset($saler_info['id']) || !$is_saler_valid){
            redirect ( site_url ( 'distribute/distribute/reg' ) . '?id=' . $this->inter_id );
        }

        $inter_id = $this->input->get('id');
//        $card_id = $this->input->get('card_id');
        $data = $this->wechatMembercard->get_config($inter_id);
        $card_id = $data['card_id'];

        $open_id = $this->openid;
        $qrcJson = $this->wechatMembercard->get_distribution_qrc($inter_id,$card_id,$open_id,$saler_info['id'] );
        $result = json_decode($qrcJson,true);
        if($result && isset($result['errcode'])){
            if($result['errcode'] == 0){ //正常获取
                $data['qrc_link'] = $result['show_qrcode_url'];
                $this->template_show('member',$this->_template,'dis_qrcode',$data);
            }else{ //获取状态有误
                print_r($result);exit;
            }
        }else{ //微信返回有误

        }
    }

}
class Signature{
	function __construct(){
		$this->data = array();
	}
	function add_data($str){
		array_push($this->data, (string)$str);
	}
	function get_signature(){
		sort( $this->data,SORT_LOCALE_STRING );
		$string = implode( $this->data );
		return sha1( $string );
	}
}
?>