<?php
class Hotel_weixin_reply_model extends CI_Model {
	
	const TAB_H = 'iwide_hotels';
	const TAB_HA = 'iwide_hotel_additions';
	const TAB_HF = 'iwide_hotel_staff';
	
	
	
	function __construct() {
		parent::__construct ();
	}
	
	
	public function subscribeRun($openid,$inter_id){
		
		//新关注的粉丝要调速8接口发请求
		if($inter_id == "a455510007"){
			
			
			$this->load->library ( 'Baseapi/Subaapi_webservice',array(
						
					'_testModel'=>false
						
			) );
			
			$suba = new Subaapi_webservice(false);
			
			$suba->SubscribeWeixin($openid);
			
		}
	
		
	}
	
	/**
	 * 
	 * @param unknown $openid
	 * @param unknown $inter_id
	 * @param unknown $qrcode_id //天
	 * @param number $firsh_view 是不是第一次关注，是1，否0
	 * @return boolean
	 */
	public function reply_by_qrcode($openid,$inter_id,$qrcode_id,$firsh_view){
		
		$this->load->library ( 'Baseapi/Subaapi_webservice',array(
					
				'_testModel'=>false
					
		) );
		
		$suba = new Subaapi_webservice(false);
		
		$this->load->library ("MYLOG");
		
		MYLOG::w(" Qrc : ".$qrcode_id,'debug-log');
		
		//速8作定制，前20000个由他们来处理，后20000用我们原逻辑
		if($inter_id == "a455510007"){
		    /* 查询是否注册过 */
		    /*定时推送注册消息需求*/
		    if ($firsh_view){
		        //第一次关注进入消息队列
		        $this->load->model('member/Auto_send');
		        $this->Auto_send->add($openid,$inter_id);
		    
		    }else{
		        //之前关注过，查询是否会员
		        $this->load->model('member/member');
		        $member=$this->member->getMemberInfoById($openid);
		        if (empty($member)||empty($member->membership_number)){
		            //不是会员，加入消息列表
		            $this->load->model('member/Auto_send');
		            $this->Auto_send->add($openid,$inter_id);
		        }
		        //是会员不做处理
		    
		    }
		    /*定时推送注册消息需求END*/
		    
            //速8作定制,900000以后的为激活绑卡的扫描操作
			if( $qrcode_id >= 900000 ){
				
				/*访问限制*/
				$redis = new Redis();
				$redis->connect('10.25.168.86',6379);
				$key = $openid.$qrcode_id;
				$timeRecord = $redis->get($key);
				if($timeRecord){			
					if(time() - $timeRecord > 2){
						$redis->del($key);
						$flag = $redis->setNx($key,time());
						if(!$flag){
						 MYLOG::w(__LINE__." Reply_by_qrcode result : 会员卡绑定失败：扫描绑定间隔低于2秒 | ".$qrcode_id,'debug-log');
						 exit();
						}
					}else{
						MYLOG::w(__LINE__." Reply_by_qrcode result : 会员卡绑定失败：扫描绑定间隔低于2秒 | ".$qrcode_id,'debug-log');
						exit();
					}					
				
				}else{
					$flag = $redis->setNX($key,time());
					if(!$flag){
						 MYLOG::w(__LINE__." Reply_by_qrcode result : 会员卡绑定失败：扫描绑定间隔低于2秒 | ".$qrcode_id,'debug-log');
						 exit();
					}
					$redis->expire($key,2);
				}
				/*访问限制*/
				
				$suba = new Subaapi_webservice(false);

                //查询会员
				$userInfoRes = $suba->GetCustomerByCustomerID($qrcode_id,$openid);

                MYLOG::w("GetCustomerByCustomerIDResult result : ".json_encode($userInfoRes),'debug-log');
                if (!empty($userInfoRes) && isset($userInfoRes['GetCustomerByCustomerIDResult']) &&   !$userInfoRes['GetCustomerByCustomerIDResult']['Content']){
                    $str = '会员卡绑定失败：'.$userInfoRes['message'];
                    return $this->returnContent($str);
                }
                $userInfo = $userInfoRes['GetCustomerByCustomerIDResult']['Content'];

                //绑定会员
                $band_res = $suba->ScanCodeBindWeixinCustomer($openid,$userInfo['MainCardNO']);
                MYLOG::w("ScanCodeBindWeixinCustomer result : ".json_encode($band_res),'debug-log');
                if(!$band_res['status']){
                    $str = '会员卡绑定失败';
                    return $this->returnContent($str);
                }
                
                
               //更新粉丝表
               if(isset($userInfo['Distributor']) && !empty($userInfo['Distributor'])){
                   $sourceId = $userInfo['Distributor'];
                   $sql = "UPDATE iwide_fans_subs
                           SET
                           source = $sourceId
                           WHERE  openid = '$openid' AND inter_id = '$inter_id' AND  source = $qrcode_id";
                   $updateRes = $this->db->query ( $sql );

                   //发放绩效
                   if($updateRes){
                       $this->load->model ( 'hotel/pms/suba_hotel_model' );
                       $this->load->model('member/member');
                       $memberObject = $this->member->getMemberDetailById($openid);
                       $this->suba_hotel_model->addRegisterDistributeBySub($inter_id,$openid,$memberObject->ma_id,$userInfo['MainCardNO']);
                   }
                }

               return false;
			}
			
			
						
			//后20000如果是分销员，取分销员的酒店wifi,如果不是按原关键词逻辑
			if( $qrcode_id >= 20000 ){
				
				$sql = "
						SELECT
							H.*,HA.hotel_web_id
						FROM
							".self::TAB_H." AS H,
							".self::TAB_HA." AS HA,
							".self::TAB_HF." AS HF
						WHERE
							H.hotel_id = HA.hotel_id
							AND HA.inter_id = '{$inter_id}'
							AND HA.hotel_id = HF.hotel_id
							AND HF.qrcode_id = '{$qrcode_id}'
							AND HF.inter_id = '{$inter_id}'
											";
			
				$hotel_data = $this->db->query ( $sql )->result_array ();
			
				
				if($hotel_data){
					
					if( isset( $hotel_data[0]['hotel_web_id'] ) ){
							
							
						$hotel_name = $hotel_data[0]['name'];
							
						$hotel_web_id = $hotel_data[0]['hotel_web_id'];
							
						$this->load->library ( 'Baseapi/Subaapi_webservice',array(
					
								'_testModel'=>false
									
						) );
							
						$suba = new Subaapi_webservice(false);
						$wifi_info = $suba->GetHotelWifiInfo($hotel_web_id);
						
				
						if( !$wifi_info['GetHotelWifiInfoResult']['IsError'] && $wifi_info['GetHotelWifiInfoResult']['ResultCode'] == "00"){
					
					
							$wifi_name = "";
							$wifi_pwd = '';
					
							if(  isset( $wifi_info['GetHotelWifiInfoResult']['Content']['WifiName'] ) ){
									
								$wifi_name = $wifi_info['GetHotelWifiInfoResult']['Content']['WifiName'];
								$wifi_pwd = $wifi_info['GetHotelWifiInfoResult']['Content']['WifiPassword'];
									
							}
					
							if($firsh_view == 1){
									
								/* $str = "小8终于把你盼来了！👫 10元速8余额在您注册或绑定会员卡后打入您会员帐户中。欢迎下榻速8酒店（{$hotel_name}），住客免费畅享wifi，网络名称：{$wifi_name}，密码：{$wifi_pwd}” 。
							
							👏点击“预订酒店”可查看你所在位置附近有哪些速8，也可按条件查询；
							
							👏点击“我的速8”，查看订单和会员帐户；
							
							👏点击“精彩活动”，了解优惠促销活动。"; */
								$str = $this->noViewHaveWifi($hotel_name,$wifi_name,$wifi_pwd);
									
							}else{						
								
								$str = $this->haveViewHaveWifi($hotel_name,$wifi_name,$wifi_pwd);
	
								
							}
					
					
							return $this->returnContent($str);
					
					
						}else{
					
							if($firsh_view == 0){
								
								$str = $this->noViewNoWifi($hotel_name);
									
							}
					
							return $this->returnContent($str);
							//return false;
					
						}
					}
					
					
				}else{
					
					return false;
					
				}
				//return false;
				
			}else{
				
				$hotel_id = $qrcode_id;
			
				if($hotel_id == 10){
					
					$hotel_id = 409;
					
				}
				
				$sql = "
						SELECT
							H.*,HA.hotel_web_id
						FROM
							".self::TAB_H." AS H,
							".self::TAB_HA." AS HA
						WHERE
							H.hotel_id = HA.hotel_id
							AND HA.inter_id = '{$inter_id}'
							AND HA.hotel_web_id = '{$hotel_id}'
						
						";
			
				//$this->db->query($sql);
	
				$hotel_data = $this->db->query ( $sql )->result_array ();
				
				/* if($qrcode_id == 10){
						
					$this->load->model ( 'wx/Weixin_model' );
						
					$this->Weixin_model->replyText($sql);
					exit;
						
				} */
				
				if( isset( $hotel_data[0]['hotel_web_id'] ) ){
					
					
					$hotel_name = $hotel_data[0]['name'];
					
					$hotel_web_id = $hotel_data[0]['hotel_web_id'];
					
					$this->load->library ( 'Baseapi/Subaapi_webservice',array(
				
						'_testModel'=>false	
							
					) );
					
					$suba = new Subaapi_webservice(false);
					
					$wifi_info = $suba->GetHotelWifiInfo($hotel_web_id);
					
					if( !$wifi_info['GetHotelWifiInfoResult']['IsError'] && $wifi_info['GetHotelWifiInfoResult']['ResultCode'] == "00"){
						
						$wifi_name = "";
						$wifi_pwd = '';
						
						if(  isset( $wifi_info['GetHotelWifiInfoResult']['Content']['WifiName'] ) ){
							
							$wifi_name = $wifi_info['GetHotelWifiInfoResult']['Content']['WifiName'];
							$wifi_pwd = $wifi_info['GetHotelWifiInfoResult']['Content']['WifiPassword'];
							
						}
						
						if($firsh_view == 1){
							
							
							$str = $this->noViewHaveWifi($hotel_name,$wifi_name,$wifi_pwd);
							
						}else{
							
							$str = $this->haveViewHaveWifi($hotel_name,$wifi_name,$wifi_pwd);
							
							
							
						}
						
						
						return $this->returnContent($str);
						
						
					}else{
						
						if($firsh_view == 1){
			
							$str = $this->noViewNoWifi($hotel_name,$wifi_name,$wifi_pwd);
							
						}else{
							
							$str = $this->haveViewNoWifi($hotel_name,$wifi_name,$wifi_pwd);
							
							
						}
						
						return $this->returnContent($str);
						//return false;
						
					}
					
					
				}else{
					
					return false;

					
				}
				
				
			}
			
			
		}
		
		
		return false;
		
		
		
	}
	
	/**
	 * 返回忽略
	 */
	private function returnIgnore(){
		
		return false;
		
	}
	
	/**
	 * 返回内容
	 * @param string $content
	 * @return multitype:multitype: array
	 */
	private function returnContent($content){
		
		$type = 'text';
		
		$arr = array();
		
		array_push($arr,array (
			'Description' => $content			
		) );
		
		//返回内容
		return array($arr,$type);
		
		
		//return $content;
		
	}
	
	
	private function haveViewHaveWifi($hotel_name = "",$wifi_name="",$wifi_pwd = ""){
		
		$str = "欢迎下榻{$hotel_name}，住客免费畅享wifi，网络名称：{$wifi_name}，密码：{$wifi_pwd} 。";
		$str .= "\r\n通过微信下单并入住离店后获得2-18元随机现金券，赶快行动吧！！！";
		$str .= "\r\n👏点击“预订酒店”可查看你所在位置附近有哪些速8，也可按条件查询；";
		$str .= "\r\n👏点击“我的速8”，查看订单和会员帐户；";
		$str .= "\r\n👏点击“精彩活动”，了解优惠促销活动。";
		return $str;
		
	}
	
	private function noViewHaveWifi($hotel_name = "",$wifi_name="",$wifi_pwd = ""){
	
		$str = "小8终于把您盼来了！10元速8现金券在您首次注册或首次绑定会员卡后打入您会员帐户中。欢迎下榻{$hotel_name}，住客免费畅享wifi，网络名称：{$wifi_name}，密码：{$wifi_pwd} 。";
		$str .= "\r\n通过微信下单并入住离店后获得2-18元随机现金券，赶快行动吧！！！";
		$str .= "\r\n👏点击“预订酒店”可查看你所在位置附近有哪些速8，也可按条件查询；";
			$str .= "\r\n👏点击“我的速8”，查看订单和会员帐户；";
			$str .= "\r\n👏点击“精彩活动”，了解优惠促销活动。";
			return $str;
	
	}
	
	
	private function haveViewNoWifi($hotel_name = ""){
	
		$str = "欢迎下榻{$hotel_name}。";
		$str .= "\r\n通过微信下单并入住离店后获得2-18元随机现金券，赶快行动吧！！！";
		$str .= "\r\n👏点击“预订酒店”可查看你所在位置附近有哪些速8，也可按条件查询；";
		$str .= "\r\n👏点击“我的速8”，查看订单和会员帐户；";
		$str .= "\r\n👏点击“精彩活动”，了解优惠促销活动。";
		return $str;
	
	}
	
	private function noViewNoWifi($hotel_name = ""){
	
		$str = "小8终于把您盼来了！10元速8现金券在您首次注册或首次绑定会员卡后打入您会员帐户中。欢迎下榻{$hotel_name}。";
		$str .= "\r\n通过微信下单并入住离店后获得2-18元随机现金券，赶快行动吧！！！";
		$str .= "\r\n👏点击“预订酒店”可查看你所在位置附近有哪些速8，也可按条件查询；";
		$str .= "\r\n👏点击“我的速8”，查看订单和会员帐户；";
		$str .= "\r\n👏点击“精彩活动”，了解优惠促销活动。";
		
		return $str;
	
	}
	
	private function defaultView($hotel_name = ""){
	
		$str = "小8终于把您盼来了！10元速8现金券在您首次注册或首次绑定会员卡后打入您会员帐户中。欢迎下榻{$hotel_name}。";
		$str .= "\r\n通过微信下单并入住离店后获得2-18元随机现金券，赶快行动吧！！！";
		$str .= "\r\n👏点击“预订酒店”可查看你所在位置附近有哪些速8，也可按条件查询；";
		$str .= "\r\n👏点击“我的速8”，查看订单和会员帐户；";
		$str .= "\r\n👏点击“精彩活动”，了解优惠促销活动。";
		
		return $str;
	
	}
	
	
	
	
	
	
	
	
}