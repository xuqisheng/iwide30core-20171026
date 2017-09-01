<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	用户注册
*	@author  Frandon
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 489291589@qq.com
*/
class Reg extends MY_Front_Member_Wxapp
{
	//会员卡登录页面
	public function index(){
		$post_config_url = PMS_PATH_URL."adminmember/getregconfig";
		$post_config_data =  array(
			'inter_id'=>$this->inter_id,
			);
		//请求注册配置
		$data['login_config'] = $this->doCurlPostRequest( $post_config_url , $post_config_data )['data'];
        $data['inter_id'] = $this->inter_id;
		//$this->load->view('member/'.$this->_template.'/reg',$data);
		$this->out_put_msg ( 1,'', $data ,'membervip/reg/index');
		
	}
    
    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 注册登录
     */
	public function savereg(){
		
        //验证图片验证码
		$_POST = $this->source['send_data'];
		//碧桂园先写死验证
		if($this->inter_id == 'a421641095'){
			
			
			if( !isset($_POST['name']) || $_POST['name'] == ""){
					
				$this->out_put_msg ( 2,'姓名不能为空', $login_result ,'membervip/reg/savereg');
				return;
					
			}
			
			if( !isset($_POST['password']) || $_POST['password'] == ""){
					
				$this->out_put_msg ( 2,'密码不能为空', $login_result ,'membervip/reg/savereg');
				return;
					
			}
				
			if( !isset($_POST['smspic']) || $_POST['smspic'] == ""){
			
				$this->out_put_msg ( 2,'图片验证码为空', $login_result ,'membervip/reg/savereg');
				return;
			
			}
			
			if( !isset($_POST['phonesms']) || $_POST['phonesms'] == ""){
		
				$this->out_put_msg ( 2,'手机验证码为空', $login_result ,'membervip/reg/savereg');
				return;
		
			}
				
		}
        if(isset($_POST['smspic'])){
        	//$this->set_user_session($key, $value)
        	$code_value = $this->user_session("code");
            if($code_value != $_POST['smspic']){
                $msginfo['err'] = '40003';
                $msginfo['msg'] = '图片验证码错误';
                //echo json_encode($msginfo);exit;
                $this->out_put_msg ( 2, $msginfo['msg'], "" ,'membervip/reg/savereg');
                return;
            }
        }

		$post_login_url = PMS_PATH_URL."member/reg";
		$post_login_data = array(
			'inter_id'=>$this->inter_id,
			'openid'=>$this->openid,
			'data'=>$_POST,
        );
		
		
		
        //如果有验证码,验证
        if(isset($_POST['phonesms'])){
            $checkSmsData = $post_login_data;
            $checkSmsData['data']['sms']=$_POST['phonesms'];
            $checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
            $checkSmsData['cellphone']=$checkSmsData['phone'];
            $checkSmsData['sms']=$_POST['phonesms'];
            $checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
            $res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
            if($res['err']>0){
                //echo json_encode($res);exit;
            	$this->out_put_msg ( 2, $res['msg'], $login_result ,'membervip/reg/savereg');
            	return;
            }
        }

		$login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
		if($login_result['err']==0){
            //获取优惠信息
            $post_card = array(
                'token'=>$this->_token,
                'inter_id'=>$this->inter_id,
                'is_active'=>'t',
                'type'=>'reg',
            );
            $rule_info= $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_rule_info" , $post_card );
            $rule_infos = array();
            if(isset($rule_info['data']) && !empty($rule_info['data'])){
                $rule_infos = $rule_info['data'];
            }

            if(!empty($rule_infos) && is_array($rule_infos)){
                $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
                $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
                foreach ($rule_infos as $key => $item){
                    if( isset($item['is_package']) && $item['is_package']=='t'){
                        $package_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'uu_code'=>md5(uniqid()),
                            'package_id'=>$item['package_id'],
                            'card_rule_id'=>$item['card_rule_id'],
                            'number'=>$item['frequency']
                        );
                        $this->doCurlPostRequest( $packge_url , $package_data );
                    }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                        $card_data = array(
                            'token'=>$this->_token,
                            'inter_id'=>$this->inter_id,
                            'openid'=>$this->openid,
                            'card_id'=>$item['card_id'],
                            'type'=>'reg'
                        );
                        $this->doCurlPostRequest( $card_url , $card_data );
                    }
                }
            }
        }
         if($login_result['err']>0){
         	$this->out_put_msg ( 2, $login_result['msg'], $login_result ,'membervip/reg/savereg');
         }else{
         	$this->out_put_msg ( 1, $login_result['msg'], $login_result ,'membervip/reg/savereg');
         }
        	
		//echo json_encode($login_result);
	}
	
	
	
	/**
	 * 2017-2-23更新公众号注册方法
	 * 2016-07-20
	 * @author knight
	 * 变更领取卡劵和礼包的方式,改为接口请求
	 * 注册登录
	 */
	public function savereg2(){
		//$this->session->unset_tempdata($this->inter_id.'vip_user');
		//验证图片验证码
		if(isset($_POST['smspic'])){
			if($_SESSION['code'] != $_POST['smspic']){
				$msginfo['err'] = '40003';
				$msginfo['msg'] = '图片验证码错误';
				echo json_encode($msginfo);exit;
			}
		}
	
	
		if($this->inter_id == 'a472731996'){ //雅思特定制
			$this->load->model ( 'distribute/Fans_model' );
			$fans = $this->Fans_model->get_fans_beloning($this->inter_id,$this->openid);
	
			$SalesINFO = array();
	
			if(!empty($fans)){
				$hotel_id  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '';
	
				if($hotel_id){
					$hotelInfo = $this->db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$this->inter_id' AND hotel_id= $hotel_id ")->row();
	
					if(!empty($hotelInfo) && isset($hotelInfo->hotel_web_id) && ( $hotelInfo->hotel_web_id > 0) ){
						$soap = new SoapClient('http://121.41.82.114:9026/IWideService.asmx?wsdl');
						$start = microtime(true);
						$SalesINFO = $soap->GetSellerListBySellerDepID(array('SellerDepID'=>($hotelInfo->hotel_web_id)));
						$end = microtime(true);
						$time = round( $end - $start , 6 );
						$this->load->library ("MYLOG");
						// 转换成数组
						$SalesINFO = json_decode(json_encode($SalesINFO), true);
						MYLOG::pms_access_record('a472731996',date("Y-m-d H:i:s"),$time,'GetSellerListBySellerDepID','',json_encode(array('SellerDepID'=>$hotel_id['hotel_web_id'])),json_encode($SalesINFO),"雅思特");
	
						if(!empty($SalesINFO)){
							$_POST['seller_id'] = $SalesINFO['GetSellerListBySellerDepIDResult'];
							$_POST['hotel_id'] = $hotelInfo->hotel_web_id;
						}
					}
				}
			}
	
			if(empty($SalesINFO) ){
				$_POST['seller_id'] = 99;
				$_POST['hotel_id'] = 99;
			}
	
		}
	
		if($this->inter_id == 'a480304439'){ //优程定制
			$this->load->model ( 'distribute/Fans_model' );
			$fans = $this->Fans_model->get_fans_beloning($this->inter_id,$this->openid);
			if(!empty($fans)){
				$_POST['hotel_id']  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '';
			}
	
		}
	
		$post_login_url = PMS_PATH_URL."member/reg";
		$post_login_data = array(
				'inter_id'=>$this->inter_id,
				'openid'=>$this->openid,
				'data'=>$_POST,
		);
	
		//如果有验证码,验证
		$conf_url = PMS_PATH_URL."adminmember/getregconfig";
		$post_data =  array('inter_id'=>$this->inter_id);
		//请求注册配置
		$regconfig = $this->doCurlPostRequest($conf_url,$post_data);
		$regconfig = isset($regconfig['data'])?$regconfig['data']:array();
		if(isset($regconfig['phonesms']) && $regconfig['phonesms']['show']=='1' && $regconfig['phonesms']['check']=='1'){
			if(!isset($_POST['phonesms'])) {
				echo json_encode(array('err'=>'40003','msg'=>'验证码不存在'));exit;
			}
			$checkSmsData = $post_login_data;
			$checkSmsData['data']['sms']=$_POST['phonesms'];
			$checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
			$checkSmsData['cellphone']=$checkSmsData['phone'];
			$checkSmsData['sms']=$_POST['phonesms'];
			$checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
			$res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
			if($res['err']>0){
				echo json_encode($res);exit;
			}
		}
	
		$login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
		$is_package = false;
		if($login_result['err']=='0'){
	
	
			/*注册分销绩效*/
			//暂去掉
			/*end注册分销绩效*/
	
			$this->load->model('membervip/front/Member_model');
			$this->Member_model->check_user_info($this->inter_id,$this->openid);
			//获取优惠信息
			$post_card = array(
					'token'=>$this->_token,
					'inter_id'=>$this->inter_id,
					'is_active'=>'t',
					'type'=>'reg',
			);
			$rule_info= $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_rule_info" , $post_card );
			$rule_infos = array();
			if(isset($rule_info['data']) && !empty($rule_info['data'])){
				$rule_infos = $rule_info['data'];
			}
	
	
			if(!empty($rule_infos) && is_array($rule_infos)){
				$packge_url = INTER_PATH_URL.'package/give'; //领取礼包
				$card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
				foreach ($rule_infos as $key => $item){
					if( isset($item['is_package']) && $item['is_package']=='t'){
						$package_data = array(
								'token'=>$this->_token,
								'inter_id'=>$this->inter_id,
								'openid'=>$this->openid,
								'uu_code'=>md5(uniqid()),
								'package_id'=>$item['package_id'],
								'card_rule_id'=>$item['card_rule_id'],
								'number'=>$item['frequency']
						);
						$result = $this->doCurlPostRequest( $packge_url , $package_data );
						if(isset($result['err']) && $result['err']=='0'){
							$is_package = true;
						}
					}elseif (isset($item['is_package']) && $item['is_package']=='f'){
						$card_data = array(
								'token'=>$this->_token,
								'inter_id'=>$this->inter_id,
								'openid'=>$this->openid,
								'card_id'=>$item['card_id'],
								'type'=>'reg'
						);
						$this->doCurlPostRequest( $card_url , $card_data );
					}
				}
			}
		}
		if(is_array($login_result)) $login_result['is_package'] = 2;
		if(!empty($login_result) && is_array($login_result) && $is_package===true) $login_result['is_package'] = 1;
		//echo json_encode($login_result);
		
		if($login_result['err']>0){
			$this->out_put_msg ( 2, $login_result['msg'], $login_result ,'membervip/reg/savereg');
		}else{
			$this->out_put_msg ( 1, $login_result['msg'], $login_result ,'membervip/reg/savereg');
		}
	}

    public function pic_code(){
        //生成验证码图片
        $im = imagecreate(60,20); // 画一张指定宽高的图片
        $back = ImageColorAllocate($im, 245,245,245); // 定义背景颜色
        imagefill($im,0,0,$back); //把背景颜色填充到刚刚画出来的图片中
        $vcodes = "";
        srand((double)microtime()*1000000);
        //生成4位数字
        for($i=0;$i<4;$i++){
            $font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255)); // 生成随机颜色
            $authnum=rand(1,9);
            $vcodes.=$authnum;
            imagestring($im, 5, 2+$i*10, 1, $authnum, $font);
        }
        //$_SESSION['code'] = $vcodes;
        
        $this->set_user_session("code", $vcodes);

        for($i=0;$i<100;$i++) //加入干扰象素
        {
            $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
            imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); // 画像素点函数
        }
        ob_clean();
        Header("Content-type: image/PNG");
        ImagePNG($im);
        ImageDestroy($im);
    }

}
?>