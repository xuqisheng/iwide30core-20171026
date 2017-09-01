<?php
//include_once '../iwidelibraries/Baseapi/Suba_webservice.php';
class Suba_hotel_model extends CI_Model {
	
	const TAB_HOA = 'hotel_order_additions';
	const TAB_PAY_LOG = 'pay_log';
	
	//速8要求不能订多于5间
	const min_room_num = 5;
	
	//特殊说明状态暗窗				
	private $window_des_array = array(
			
			"0"=>"暗窗",
			"1"=>"廊窗",
			"2"=>"无窗",
			"3"=>"半地下",
			"4"=>"地下",
			"5"=>"无烟房",
			
	);
	
	function __construct() {
		parent::__construct ();
	}
	function test() {
	}
	function get_rooms_change($rooms, $idents, $condit, $pms_set = array()) {
		
		
		
		$this->load->library ( 'Baseapi/Subaapi_webservice',array(
				
			'_testModel'=>false	
		) );
		
		
		$pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
		
		
		$arrDate = date("Y-m-d",strtotime($condit['startdate']));
		
		$outDate = date("Y-m-d",strtotime($condit['enddate']));
	
		
		//所订房间数量数组，不存在数据数组，默认所有都为1间，元素：room_id=>$room_num
		if(!isset($condit['nums'])){
			$nums = array();
			foreach($rooms as $temp_room){
				
				$nums[$temp_room['room_id']] = 1;
				
			}
		}else{
			
			$nums = $condit['nums'];
			
		}
		
		
		//对接速8的hotel id
		$hotel_web_id = $pms_set['hotel_web_id'];
		
		$suba = new Subaapi_webservice(true);
		
		//$suba->local_test = true;
	
		//取速8房间信息
		$data = $suba->GetHotelRooms($hotel_web_id, $arrDate, $outDate, 1);
	
	
		//返回错误或为空时返回空
		if($data['GetHotelRoomsResult']['IsError'] ||  $data['GetHotelRoomsResult']['ResultCode'] != '00' || $data['GetHotelRoomsResult']['Content'] == "" ){
			
			return array ();
			
		}
	
		//过漏顶层，只取房间的数组
		$suba_rooms = $data['GetHotelRoomsResult']['Content']['HotelRoom'];
		
		

		//临时记录数组
		$temp_suba_rooms = array();
		
		//以速8,房间id对应房间数据，即 s8_room_id => room_info
		foreach($suba_rooms as $key => $suba_one_room){
		
			$temp_suba_rooms[$suba_one_room['RoomTypeID']] = $suba_one_room;
		
		}
	
		
		$chooseRooms = array();
		
		
		foreach($rooms as $rooom_key => $one_room){
		
			
			if( isset( $temp_suba_rooms[ $one_room['webser_id'] ] ) ){
				$chooseRooms[$one_room['room_id']]['suba'] = $temp_suba_rooms[ $one_room['webser_id'] ];
				$chooseRooms[$one_room['room_id']]['iwide'] = $one_room;
				$chooseRooms[$one_room['room_id']]['book_num'] = $nums[ $one_room['room_id'] ];
			}
		}
		
	
		/*
		 * 取当前会员等级，并对应速8的设置，取得当前会员的价格代码
		 */
		$this->load->model ( 'common/Webservice_model' );
		$member_lev = $this->Webservice_model->get_web_reflect ( $this->inter_id, 0, $pms_set['pms_type'], array (
				'member_price_code'
		), 1, 'l2w' );		
		$this_member_lev = null;		
		if( isset( $member_lev['member_price_code'][ $this->member_lv ] ) ){
				
			$this_member_lev = $member_lev['member_price_code'][ $this->member_lv ];
				
		}
		
	/* 	print_r($suba_one_room['IfAdvancePayMent']);
		exit;
		 */
		//按接口取得对接数组，详细接口信息参考在线文档，$suba_one_room['IfAdvancePayMent']酒店支持预付就所有房都支持
		$stateArray = $this->getStateArray($chooseRooms, $suba_one_room['IfAdvancePayMent'],$this_member_lev);
		
		if( isset( $condit['is_ajax']) ){
			
			$temp_stateArray = array();
			
			foreach($stateArray as $temp_state){
				
				$temp_stateArray[] = $temp_state;
				
			}
			
			$stateArray = $temp_stateArray;
			
			
		}
	
		
		
		return $stateArray;
		
	}
	function get_rooms_change_allpms($pms_state, $rooms, $params) {
		$data = array ();
		foreach ( $rooms ['rooms'] as $rm ) {
			if (! empty ( $pms_state ['pms_state'] [$rm ['webser_id']] )) {
				$data [$rm ['room_id']] ['room_info'] = $rm;
				$data [$rm ['room_id']] ['state_info'] = empty ( $pms_state ['pms_state'] [$rm ['webser_id']] ) ? array () : $pms_state ['pms_state'] [$rm ['webser_id']];
				$data [$rm ['room_id']] ['show_info'] = array ();
				$data [$rm ['room_id']] ['lowest'] = min ( $pms_state ['exprice'] [$rm ['webser_id']] );
				$data [$rm ['room_id']] ['highest'] = max ( $pms_state ['exprice'] [$rm ['webser_id']] );
			}
		}
		return $data;
	}
	function get_web_roomtype($pms_set, $web_price_code, $startdate, $enddate, $params = array()) {
		$sh_hotels = new HotelInfo ();
		$sh_hotels->arr_dt = date ( 'Y-m-d', strtotime ( $startdate ) );
		$sh_hotels->dpt_dt = date ( 'Y-m-d', strtotime ( $enddate ) );
		$sh_hotels->rp_nm = $pms_set ['pms_auth'] ['rp_nm'];
		$sh_hotels->htlcls = 99;
		$sh_hotels->T_channel = $pms_set ['pms_auth'] ['channel'];
		$sh_hotels->htlcd = $pms_set ['hotel_web_id'];
		$pms_state = array ();
		// $valid_state = array ();
		$exprice = array ();
		$rm_cds = implode ( ',', array_keys ( $params ['web_rids'] ) );
		$sh_hotels->rm_list [0] = $rm_cds;
		$web_left = $this->get_web_nums ( $pms_set, $sh_hotels->arr_dt, $sh_hotels->dpt_dt, $rm_cds );
		foreach ( $web_price_code as $code ) {
			$sh_hotels->rp_cd = $code;
			$result = $this->Get_Hotels ( $pms_set, $sh_hotels );
			if (! empty ( $result ['rooms'] )) {
				foreach ( $result ['rooms'] as $r ) {
					$min_price = array ();
					if (! empty ( $r->drt_amt )) {
						$pms_state [$r->rm_cd] [$code] ['price_name'] = $result ['code_name'];
						$pms_state [$r->rm_cd] [$code] ['price_type'] = 'pms';
						$pms_state [$r->rm_cd] [$code] ['extra_info'] = array (
								'type' => 'code',
								'pms_code' => $code 
						);
						$pms_state [$r->rm_cd] [$code] ['price_code'] = $code;
						$pms_state [$r->rm_cd] [$code] ['des'] = '';
						$pms_state [$r->rm_cd] [$code] ['sort'] = 0;
						$pms_state [$r->rm_cd] [$code] ['disp_type'] = 'buy';
						$web_set = array ();
						if (isset ( $params ['web_reflect'] ['web_price_code_set'] [$code] )) {
							$web_set = json_decode ( $params ['web_reflect'] ['web_price_code_set'] [$code], TRUE );
						}
						$pms_state [$r->rm_cd] [$code] ['condition'] ['pre_pay'] = isset ( $web_set ['pre_pay'] ) ? $web_set ['pre_pay'] : '';
						$pms_state [$r->rm_cd] [$code] ['condition'] ['no_pay_way'] = isset ( $web_set ['no_pay_way'] ) ? $web_set ['no_pay_way'] : '';
						$allprice = '';
						$amount = '';
						$p = explode ( ',', $r->drt_amt ); // 取结果中的每日价格数组
						for($n = 0; $n < $params ['countday']; $n ++) {
							$pms_state [$r->rm_cd] [$code] ['date_detail'] [date ( "Ymd", strtotime ( "+" . $n . "day", strtotime ( $startdate ) ) )] = array (
									'price' => $p [$n],
									'nums' => isset ( $web_left [$r->rm_cd] ['everyday_num'] [$n] ) ? $web_left [$r->rm_cd] ['everyday_num'] [$n] : 0 
							);
							$allprice .= ',' . $p [$n];
							$amount += $p [$n];
						}
						if (isset ( $params ['web_rids'] [$r->rm_cd] )) {
							$nums = empty ( $params ['condit'] ['nums'] [$params ['web_rids'] [$r->rm_cd]] ) ? 1 : $params ['condit'] ['nums'] [$params ['web_rids'] [$r->rm_cd]];
						} else {
							$nums = 1;
						}
						$pms_state [$r->rm_cd] [$code] ['allprice'] = substr ( $allprice, 1 );
						$pms_state [$r->rm_cd] [$code] ['total'] = $amount;
						$pms_state [$r->rm_cd] [$code] ['related_des'] = '';
						$pms_state [$r->rm_cd] [$code] ['total_price'] = $amount * $nums;
						$pms_state [$r->rm_cd] [$code] ['avg_price'] = number_format ( $amount / $params ['countday'], 2, '.', '' );
						$pms_state [$r->rm_cd] [$code] ['price_resource'] = 'webservice';
						$left_nums = isset ( $web_left [$r->rm_cd] ['nums'] ) ? $web_left [$r->rm_cd] ['nums'] : 0;
						$pms_state [$r->rm_cd] [$code] ['least_num'] = $left_nums;
						$book_status = 'full';
						if ($left_nums >= $nums)
							$book_status = 'available';
						$pms_state [$r->rm_cd] [$code] ['book_status'] = $book_status;
						$exprice [$r->rm_cd] [] = $pms_state [$r->rm_cd] [$code] ['avg_price'];
						// if ($room_detail ['canBook'] == 1) {
						// $valid_state [$r->rm_cd] [$code] = $pms_state [$r->rm_cd] [$code];
						// }
					}
				}
			}
		}
		return array (
				'pms_state' => $pms_state,
				// 'valid_state' => $valid_state,
				'exprice' => $exprice 
		);
	}
	function get_web_nums($pms_set, $startdate, $enddate, $rm_cd) {
		$sg = new GetRmAvl ();
		$sg->arr_dt = $startdate;
		$sg->dpt_dt = $enddate;
		$sg->htl_list = $pms_set ['hotel_web_id'];
		$sg->rm_list = $rm_cd;
		$sg->channel_cd = $pms_set ['pms_auth'] ['channel'];
		$s = $this->sub_to_web ( $pms_set, 'Get_RmAvl', array (
				'sG' => $sg 
		) );
		$data = array ();
		if (! empty ( $s->Get_RmAvlResult->GetRmAvl ) && $s->Err->string [1]) {
			$room = $s->Get_RmAvlResult->GetRmAvl;
			if (count ( $room ) == 1) {
				$room = array (
						'0' => $room 
				);
			}
			foreach ( $room as $r ) {
				$data [$r->rm_list] ['total_num'] = $r->tot_avl;
				$data [$r->rm_list] ['nums'] = $r->rm_avl;
				$data [$r->rm_list] ['everyday_num'] = explode ( ',', $r->day_amt );
			}
		}
		return $data;
	}
	
	function order_to_web($inter_id, $orderid, $params = array(), $pms_set = array()) {
		
		//$this->load->model ( 'hotel/Member_model' );
		/* print_r($pms_set);
		exit; */
		
		$this->load->model('member/member');
		
		$this->load->model ( 'hotel/Order_model' );
		
		//$memberObject = $this->getMemberModel ()->getMemberDetailById ( $openid );
		
		$order = $this->Order_model->get_main_order ( $inter_id, array (
				'orderid' => $orderid,
				'idetail' => array (
						'i' 
				) 
		) );
		
		
		if (! empty ( $order )) {
			
			$order = $order [0];
			
			//取速8的会员卡号
			$pms_member_no = $order['member_no']?$order['member_no']:0;
		
			
			
			$this->load->library ( 'Baseapi/Subaapi_webservice',array(
			
					'_testModel'=>false
			) );
			


			$order_id = $order['orderid'];
			
			$HotelID = $pms_set['hotel_web_id'];
			
			
			//入住日期
			$ArrDate = date("Y-m-d",strtotime( $order['startdate'] ) );
			
			//离店日期
			$OutDate = date("Y-m-d",strtotime( $order['enddate'] ) );
			
			$must_checkin_time = trim( $this->input->post("must_checkin_time") );
			
			
			if(strpos($must_checkin_time, "次日") === false){
				
				//当天入住
				$HoldTime = $ArrDate." ".$must_checkin_time;
				
				
			}else{
				
				//次天入住
				$must_checkin_time = trim( str_replace("次日", "", $must_checkin_time) );
				$HoldTime =  date("Y-m-d",strtotime($ArrDate)+86401)." ".$must_checkin_time;
				
			}
			
			//最晚订记时间
			//$HoldTime = $ArrDate." ".$order['holdtime'];
			
			//房间数
			$RoomCount = $order['roomnums'];
			
			//住客名称
			$GuestName = $order['name'];
			
			//住客电话
			$GuestMobile = $order['tel'];
			
			//要确定这个价是我们加入还是按接口
			$RateCode = $order['first_detail']['price_code'];

			//联系人名称，暂与住客名称一样
			$ContactName = $order['name'];
			
			//联系人电话，暂与住客电话一样
			$ContactMobile = $order['tel'];
			
			//由之前取房时处理的付加消息传到此
			$extro_info = (array)@json_decode($order['room_codes']);
			
			//作数组处理，取内容
			$extro_data = "";
			foreach($extro_info as $key=>$extro_data){
				
				$extro_data = $extro_data;
				
				continue;
				
			}
				
			//取付加信息
			if( $extro_data != "" && isset( $extro_data->code->extra_info )){
				
				//每日BreakfastTypes值，速8需要传过去
				$daily_extro = (array)$extro_data->code->extra_info->daily_info;
				
				/*
						 * 优惠券使用限制数组，如果为空，侧不可使用
						 * [0] => stdClass Object
                                                        (
                                                            [RoomDay] => 2016-04-27
                                                            [Amount] => 30 //最高使用额
                                                        )

                                                    [1] => stdClass Object
                                                        (
                                                            [RoomDay] => 2016-04-28
                                                            [Amount] => 30
                                                        )
						 */
				$coupon_limit_array = (array)$extro_data->code->extra_info->coupon_limit;
				
			}else{
				
				return $this->sendMsg(0, '支付失败，附加消息有误！');
				
			}
			
			
			//需额外增加roomtype
			if(isset($extro_data->room->webser_id) && $extro_data->room->webser_id>0){
				
				$RoomTypeID = $extro_data->room->webser_id;
				
			}else{
				
				return $this->sendMsg(0, '支付失败，房号类型值有误！');
				
			}
			
			
				
			//用储值付
			$UsedAmAccount = 0;
			
			//支付方式id
			$PayChannelID = 0;
			
			//暂不储值支付
			if($order['paytype'] == 'balance'){
			
				$PayType = 3;
				$PayChannelID = 1005;
				$UsedAmAccount = $order['price'];
			
			}
			
			if($order['paytype'] == 'weixin'){
				/*
				 支付宝			1001
				微信			1003
				账户余额		1005
				代金券			1006 */
				$PayChannelID = 1003;
				
				if($order['balance_part'] > 0){
					
					$UsedAmAccount = $order['balance_part'];
					
				}
			
				$PayType = 3;
			
			}
			
			if($order['paytype'] == 'daofu'){
			
				
				$PayType = 1;
			
			}
			
			
			/*
			 *  1：前台现付；
				3：预付；
				4：担保；
			 */
			$order['paytype'] == 'balance'?$PayType = 3:"";
			$order['paytype'] == 'weixin'?$PayType = 3:"";
			$order['paytype'] == 'daofu'?$PayType = 1:"";
			
		
			//未必须传
			$CustomerID = 0;
			
			//会员卡，如果使用优惠就必填
			$CardNo = $pms_member_no;
			$CardNo = $CardNo?$CardNo:0;
		
	
			
			//使用优惠券
			$UsedCoupons = array();
			//$order['coupon_used'] = 1;
			//可用优惠券
			if($order['coupon_used'] == 1){
				
				
				
				//为空数组，代表此房间不能用代金券，提示错误
				if( count( $coupon_limit_array ) < 1 ){
					
					return $this->sendMsg(0,'此房间不能使用代金券！');
					
				}
				
				
				
				$coupon_info = json_decode($order['coupon_des'],true);
				
				
				
				if($CardNo){
				
					
					$room_num = $order['roomnums'];
									
					$set_num = 0;
					
					/* [cash_token] => Array
					 (
					 		[0] => stdClass Object
					 		(
					 				[amount] => 10
					 				[code] => 1733448990
					 				[title] => 10元
					 				[is_wxcard] => 0
					 				[wxcard_id] => 0
					 				[extra] => 5165498
					 		) */
					$coupons_array = $coupon_info['cash_token'];
					
					/*
					优惠券数组处理，传给速8时需要
					CouponInfoID	Int	是	代金券标示
					UsedAmount	Double	是	使用金额
					RoomDay	String	是	使用日期
					CouponNo	String	是	代金券券号 */
					foreach($daily_extro as $daily_room){
						
					
						
						for($i = 0 ; $i<$room_num;$i++){
							
							if( isset( $coupons_array[$set_num] ) ){
								
								//后来确认不用传
								//$temp_Coupons['CouponInfoID'] = $coupons_array[$set_num]['extra'];

								//金额要向下兼容处理，即房最多使用20元，而券是30元，侧只使用20元。
								//$temp_Coupons['UsedAmount'] = $coupons_array[$set_num]['amount'];
								foreach( $coupon_limit_array as $coupon_limit ){
									
									$coupon_limit = (array)$coupon_limit;
									
									if( $coupon_limit['RoomDay'] == $daily_room->RoomDay ){
										
										if( ( $coupon_limit['Amount'] > $coupons_array[$set_num]['amount']) ){
											
											$temp_Coupons['UsedAmount'] = $coupons_array[$set_num]['amount'];
											
										}else{
											
											$temp_Coupons['UsedAmount'] = $coupon_limit['Amount'];	
											
										}								
										
									}
									
								}
							
								
								$temp_Coupons['RoomDay'] = $daily_room->RoomDay;
								
								$temp_Coupons['CouponNo'] = $coupons_array[$set_num]['code'];
								
								
								$UsedCoupons[] = $temp_Coupons;
								
								//$coupon_info[$set_num]
								
							}
							
						
							$set_num++;
						}
						
					}
					
				
					
					
				}else{
					
					return $this->sendMsg(0,'用户会员身份有误~');
					
				}
				
			}
			
			$UsedCoupons = $UsedCoupons?$UsedCoupons:0;

			$suba = new Subaapi_webservice(false);
			
			//计算总价，传给速8
			$total=0;
			foreach ($order['order_details'] as $od){
				
				$total+=array_sum(explode(',', $od['allprice']));
				
			}

			//确定$order['price']
			$TotalPrice = $total;
			
		
			foreach($daily_extro as $key => $daily_room_temp){
				
				if($daily_room_temp){
					
					$daily_extro[$key] = (array)$daily_room_temp;
					unset($daily_extro[$key]['BreakfastName']);
					
				}

				
			}
			//用额外的信息去取
			//CouponAmount 是否不能为空
			$DailyPrices = $daily_extro;
			

			/*
			 * 
			$searchModel:
			
			 数据变量名	数据类型	必填	作用说明	备注
			HotelID	Int	是	酒店标示
			Channel
			Int	是	渠道标示
			RoomTypeID	Int	是	房型标示
			ArrDate	String	是	入住日期	“yyyy-MM-dd”
			OutDate	String	是	离店日期	“yyyy-MM-dd”
			HoldTime	String	是	保留时间	“yyyy-MM-dd HH:mm”
			RoomCount	Int	是	房间数量
			GuestName	String	是	入住人姓名	可多个: a,b,c
			GuestMobile	String	是	入住人手机
			RateCode
			String	是	房价代码
			TotalPrice	Double	是	总房价
			ContactName	String	是	联系人姓名
			ContactMobile	String	是	联系人手机
			ContactEmail	String	否	联系人邮箱
			DailyPrices	List<DailyPrice>
			是	每日价格
			UsedAmAccount	Double	否	订单使用账户余额金额
			UsedCoupons	List<UsedCoupon>
			否	订单使用代金券信息
			PayType	Int	是	支付类型	1：前台现付；
			3：预付；
			4：担保；
			PayChannelID
			Nullable<int>	否	支付方式	支付类型为前台现付不传值
			GuaranteeInfo	GuaranteeInfo	否	担保信息	预付或担保订单时传值
			CustomerID	Nullable<int>	否	会员标示
			CustomerName	String	否	会员姓名
			CardNo	String	否	会员卡号
			CardTypeID	Nullable<int>	否	会员卡类型标示
			Remark	String	否	订单备注
				 */
			$searchModel = array(
			
					'HotelID'=>$HotelID,
					'Channel'=>50,
					'RoomTypeID'=>$RoomTypeID,
			
					'ArrDate'=>$ArrDate,
					'OutDate'=>$OutDate,
					'HoldTime'=>$HoldTime,
					'RoomCount'=>$RoomCount,
					'GuestName'=>$GuestName,
					'GuestMobile'=>$GuestMobile,
					'RateCode'=>$RateCode,
					'TotalPrice'=>$TotalPrice,
					'ContactName'=>$ContactName,
					'ContactMobile'=>$ContactMobile,
					'DailyPrices'=>$DailyPrices,
					'UsedAmAccount'=>$UsedAmAccount,
					'UsedCoupons'=>$UsedCoupons,
					'PayType'=>$PayType,
					'PayChannelID'=>$PayChannelID,
					'CardNo'=>$CardNo,
					'ChannelCardNo'=>$order['openid']
					/* 'PayChannelID'=>1005,
						'GuaranteeInfo'=>'',  */
					/* 'CustomerID'=>$CustomerID,
					 'CardNo'=>$CardNo */
					
			);
			
			if(!$CardNo){
				
				unset($searchModel['CardNo']);
				
			}
	
	
			$searchModel = array(
			
					"bookInfo"=>$searchModel
			
			);
			
			//$suba->local_test = true;
			$result = $suba->BookOrder($searchModel);
		
		/* 	ob_clean();
			print_r($result);
			exit; */
			
			if (! empty ( $result ) && !$result['BookOrderResult']['IsError'] && $result['BookOrderResult']['ResultCode'] == '00') {
				$web_orderid = $result['BookOrderResult']['Content'];
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_order_additions', array (
						'web_orderid' => $web_orderid 
				) );
				
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id']
				) );
				$this->db->update ( 'iwide_hotel_orders', array (
						'holdtime' => $HoldTime
				) );
				
				/* echo $this->db->last_query();
				exit; */
				if (! empty ( $paras ['trans_no'] )) { // 提交账务
					$this->add_web_bill ( $web_orderid, $order, $pms_set, $paras ['trans_no'] );
				}
				
				
				return $this->sendMsg(1, "");
				//json_decode
			} else {
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $order ['inter_id'] 
				) );
				$this->db->update ( 'hotel_orders', array ( // 提交失败，把订单状态改为下单失败
						'status' => 10 
				) );
				return array ( // 返回失败
						's' => 0,
						'errmsg' => '提交订单失败' . ',' . $result ['BookOrderResult'] ['Message'] 
				);
			}
		}
		
		return $this->sendMsg(0, '提交订单失败');
	}
	function update_web_order($inter_id, $order, $pms_set) {
		switch ($inter_id) {
			case 'a455510007' :
				return $this->update_web_order_sub ( $inter_id, $order, $pms_set );
				break;
			default :
				return $this->update_web_order_main ( $inter_id, $order, $pms_set );
				break;
		}
		return FALSE;
	}
	
	
	

	function update_web_order_sub($inter_id, $order, $pms_set) {
		$web_order = $this->get_order_info ( $order, $pms_set);
		$istatus = - 1;
		if (! empty ( $web_order )) {
			$this->load->model ( 'hotel/Order_model' );
			$ensure_check = 0;
			$i = 0;
			//速8未支付订单，如果未支付
			if ($order['status']==9&&($web_order['OrderStatus']==5||$web_order['OrderStatus']==1)&&$web_order['PayStatus']!=1){
				return 9;
			}	
			foreach ( $order ['order_details'] as $od ) {
				if (empty ( $od ['webs_orderid'] )) {
					$webs_orderid = $web_order['sub_orderids'][$i];
					$i ++;
					$this->db->where ( array (
							'id' => $od ['sub_id'],
							'inter_id' => $inter_id,
							'orderid' => $order ['orderid']
					) );
					$this->db->update ( 'hotel_order_items', array (
							'webs_orderid' => $webs_orderid
					) );
				} else {
					$webs_orderid = $od ['webs_orderid'];
				}
				if (! empty ( $web_order['room_info'] [$webs_orderid] )) {
					$istatus = $web_order['room_info'] [$webs_orderid]['istatus'];
					if ($od ['istatus'] == 4 && $istatus == 5) {
						$istatus = 4;
					}
					//速八的不是即时确认，如果获取到的状态属于确认状态的后续状态 ，先确认再进行其他操作
					if ($istatus != 0 && $order ['status'] == 0 && $ensure_check == 0) {
						$this->db->where ( array (
								'orderid' => $order ['orderid'],
								'inter_id' => $inter_id
						) );
						$this->db->update ( 'hotel_orders', array (
								'status' => 1
						) );
						$this->Order_model->handle_order ( $inter_id, $order ['orderid'], 1, '', array (
								'no_tmpmsg' => 1
						) );
						$ensure_check = 1;
					}
					$web_start = $web_order['room_info'] [$webs_orderid]['startdate'];
					$web_end = $web_order['room_info'] [$webs_orderid]['enddate'];
					$web_end = $web_end == $web_start ? date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $web_start ) ) ) : $web_end;
					$ori_day_diff = ceil ( (strtotime ( $od ['enddate'] ) - strtotime ( $od ['startdate'] )) / 86400 );
					$web_day_diff = ceil ( (strtotime ( $web_end ) - strtotime ( $web_start )) / 86400 );
					$day_diff = $web_day_diff - $ori_day_diff;
					
					
					$updata = array ();
					
					if ($istatus != $od ['istatus']) {
						$updata ['istatus'] = $istatus;
					}

					$updata ['startdate'] = $web_start;
					$updata ['enddate'] = $web_end;

					if ( $day_diff != 0 || $web_start != $od ['startdate'] || $web_end != $od ['enddate']) {
						$updata ['no_check_date'] = 1;
					}
					$web_price = $web_order['room_info'] [$webs_orderid]['price'];
					if (empty ( $web_price )) {
						if ($web_day_diff == 1) {
							$web_price=floatval ( $od ['allprice'] );
						}
					}

					if(!empty($web_price)&&$web_price!=$od['iprice']){
						$updata['new_price']=$web_price;
						$updata ['no_check_date'] = 1;
					}
					if (! empty ( $updata )) {
						$this->Order_model->update_order_item ( $inter_id, $order ['orderid'], $od ['sub_id'], $updata );
					}
				}
			}
			if ($web_order['order_status']!=$order['status']){
				$this->db->where ( array (
						'orderid' => $order ['orderid'],
						'inter_id' => $inter_id
				) );
				$this->db->update ( 'hotel_orders', array (
						'status' => $web_order['order_status']
				) );
			}
		}
		return $istatus;
	}
	function update_web_order_main($inter_id, $order, $params) {
	}
	function cancel_order_web($inter_id, $order, $pms_set = array()) {
		if (empty ( $order ['web_orderid'] )) {
			return array (
					's' => 0,
					'errmsg' => '取消失败' 
			);
		}
		
		$this->load->library ( 'Baseapi/Subaapi_webservice',array(
		
				'_testModel'=>false
		) );
		
		$suba = new Subaapi_webservice(false);
		
		
		
		
		$web_orderid = $order['web_orderid'];
		
		
		if($web_orderid){
			
			//$suba->local_test = true;
			
			$result = $suba->CancelOrder($web_orderid, "");
			
			//exit;
			
			if(! empty ( $result ) && !$result['CancelOrderResult']['IsError'] ){
				
				return array (
						's' => 1,
						'errmsg' => '取消成功'
				);
				
			}else{
				$msg=empty($result['CancelOrderResult']['Message'])?'':$result['CancelOrderResult']['Message'];
				return array (
						's' => 0,
						'errmsg' => '取消失败。'.$msg
				);
				
			}
			
			
			
		}else{
			
			return array (
					's' => 0,
					'errmsg' => '取消失败'
			);
			
		}
		
		
	}
	function add_web_bill($web_orderid, $order, $pms_set, $trans_no) {
		
		$check = FALSE;
		$web_paid = 2;
		
		
		//print_r($order);
		
		$order_addition = $this->getPayLogByOrderNo( $order['orderid'] );

		$order_addition = $order_addition[0];
		
		//$web_orderid
		$web_orderid = $web_orderid;
		

		/*
		 支付宝			1001
		微信			1003
		账户余额		1005
		代金券			1006 */
		$PayChannelID = 1003;
		
		$PayType = 3;
		
		
		//速8会员卡卡号
		$cardNo = 0;
		
		//支付数,需确认是不是price字段，是单价还是总价
		$PayAmount = $order['price'];
		
		//余额支付数，要确认是不是直接输总价
		$AccountBalance = 0;
		

		$this->load->library ( 'Baseapi/Subaapi_webservice',array(
		
				'_testModel'=>false
		) );
		
		$suba = new Subaapi_webservice(false);
		
		//取微信支付记录
		$weixin_log = simplexml_load_string( $order_addition['rtn_content'],'SimpleXMLElement', LIBXML_NOCDATA);
		
		

		/* OrderNo	String	是	订单号
			 PayChannel
			Int	是	支付渠道
			PayType	Int	是	支付类型	1：前台现付；
			3：预付；
			4：担保；
			RefNumber	String	否	外部单号
			CardMaster	String	否	持卡人
			PayAmount	Double	是	支付金额
			CardNo	String	否	会员卡号	使用账户余额时必传
			AccountBalance	Double	是	使用账户余额
			AlipayPayMentInfo	AlipayPayMentInfo	否	支付宝返回信息	支付宝支付成功返回信息
			WeChatPayMentInfo	WeChatPayMentInfo	否	微信支付返回信息	微信宝支付成功返回信息 */
		$searchModel = array(
		
				'OrderNo'=>$web_orderid,
				'PayChannel'=>$PayChannelID,
				'PayType'=>$PayType,
				'RefNumber'=>$trans_no,
				'CardMaster'=>$cardNo, //持卡人
				'PayAmount'=>$PayAmount,
				'CardNo'=>$cardNo,
				'AccountBalance'=>$AccountBalance,
				'WeChatPayMentInfo'=>$weixin_log
	
		);
		
		$searchModel = array(
		
				"paymentInfo"=>$searchModel
		
		);
		
		//设置日志记录的openid 和 inter_id
		$suba->setLogInterIdAndOpenId($order['inter_id'], $order['openid']);

		//订单支付接口
		$result = $suba->PaymentOrder($searchModel);
	
		if (!empty ( $result ) && !$result['PaymentOrderResult']['IsError'] && $result['PaymentOrderResult']['ResultCode'] == '00') {
		
			$web_paid = 1;
			$check=TRUE;
		}else{
			
			//echo "shit";
			$check= false;
			
		}
		
		
		$this->db->where ( array (
				'orderid' => $order ['orderid'],
				'inter_id' => $order ['inter_id'] 
		) );
		$this->db->update ( 'hotel_order_additions', array ( 
				'web_paid' => $web_paid 
		) );
		

		return $check;
		
		
	}
	
	/*
	 * 查看订单状态
	 */
	public function get_order_info($order, $pms_set = array()) {
		$this->load->model ( 'hotel/pms/Suba_hotel_ext_model' );
		$s8 = $this->Suba_hotel_ext_model->get_web_obj ();
		$result = $s8->GetOrder ( $order ['web_orderid'] );
		$data = array ();
		if (! empty ( $result ['GetOrderResult'] ['Content'] ) && $result ['GetOrderResult'] ['IsError'] == FALSE) {
			$room_info = $result ['GetOrderResult'] ['Content'] ['RoomInfos'] ['RoomInfo'];
			$data = $result ['GetOrderResult'] ['Content'];
			if (isset ( $room_info ['DailyPrices'] )) {
				$room_info = array (
						$room_info 
				);
			}
			$rooms = array ();
			$sub_orderids=array();
			$status_reflect = $this->Suba_hotel_ext_model->pms_enum ( 'order_status' );
			foreach ( $room_info as $rk => $ri ) {
				$k=$ri['OrderGuestInfoID'];
				$price=0;
				if (isset ( $ri ['DailyPrices'] ['DailyPrice'] ['RoomDay'] )) {
					$room_info [$rk] ['DailyPrices'] ['DailyPrice'] = array (
							$room_info [$rk] ['DailyPrices'] ['DailyPrice'] 
					);
				}
				foreach ( $room_info [$rk] ['DailyPrices'] ['DailyPrice'] as $daily ) {
					$date = date ( 'Ymd', strtotime ( $daily ['RoomDay'] ) );
					$rooms [$k] ['prices'] [$date] ['date'] = $date;
					$rooms [$k] ['prices'] [$date] ['ori_price'] = $daily ['Price'];
					$rooms [$k] ['prices'] [$date] ['coupon_favour'] = $daily ['CouponAmount'];
					$rooms [$k] ['prices'] [$date] ['price'] = $daily ['Price'] - $daily ['CouponAmount'];
					$price += $rooms [$k] ['prices'] [$date] ['price'];
				}
				if ($ri ['RoomStatus']==10||$ri ['RoomStatus']==20||$ri ['RoomStatus']==30)
					$rooms [$k] ['startdate'] = date ( 'Ymd', strtotime ( $ri ['ArrDate'] ) );
				else 
					$rooms [$k] ['startdate'] = date ( 'Ymd', strtotime ( $order ['startdate'] ) );
				$rooms [$k] ['enddate'] = date ( 'Ymd', strtotime ( $ri ['OutDate'] ) );
				$rooms [$k] ['istatus'] = isset ( $status_reflect [$ri ['RoomStatus']] ) ? $status_reflect [$ri ['RoomStatus']] : 0;
				$rooms [$k] ['name'] = $ri['GuestName'];
				$rooms [$k] ['webs_orderid'] = $ri['OrderGuestInfoID'];
				$rooms [$k] ['price'] = $price;
				$sub_orderids[]=$k;
			}
			$data ['room_info'] = $rooms;
			$data ['sub_orderids'] = $sub_orderids;
			$data ['order_status'] = isset ( $status_reflect [$data ['OrderStatus']] ) ? $status_reflect [$data ['OrderStatus']] : 0;
		}
		return $data;
	}
	
	function sub_to_web($pms_set, $fun_name, $params) {
		if (! is_array ( $pms_set ['pms_auth'] ))
			$pms_set ['pms_auth'] = json_decode ( $pms_set ['pms_auth'], TRUE );
		$soap = new soapclient ( $pms_set ['pms_auth'] ['url'], array (
				'encoding' => 'UTF-8' 
		) );
		$Err = array (
				'0',
				'0',
				'0' 
		);
		$params ['Err'] = $Err;
		$params ['user_cd'] = $pms_set ['pms_auth'] ['user'];
		$params ['password'] = $pms_set ['pms_auth'] ['pwd'];
		$params ['lang'] = $pms_set ['pms_auth'] ['lang'];
		$now = time ();
		$s = $soap->__Call ( $fun_name, array (
				'parameters' => $params 
		) );
		$mirco_time = microtime ();
		$mirco_time = explode ( ' ', $mirco_time );
		$wait_time = $mirco_time [1] - $now + number_format ( $mirco_time [0], 2, '.', '' );
		$this->db->insert ( 'webservice_record', array (
				'send_content' => json_encode ( $params ),
				'receive_content' => json_encode ( $s ),
				'record_time' => $now,
				'inter_id' => $pms_set ['inter_id'],
				'service_type' => 'zhongruan',
				'web_path' => $pms_set ['pms_auth'] ['url'] . '/' . $fun_name,
				'record_type' => 'webservice',
				'openid' => $this->session->userdata ( $pms_set ['inter_id'] . 'openid' ),
				'wait_time' => $wait_time 
		) );
		return $s;
	}
	
	

	private function getStateArray($chooseRooms,$pre_pay = 0,$this_member_lev){
	
		$returnData = array();
	
	
		
		foreach($chooseRooms as $room_id => $chooseroom){
	
			//速8房间信息
			$room = $chooseroom['suba'];
			
			
			
			//我们房间传入信息
			$iwide_room = $chooseroom['iwide'];
			
			//购买数量
			$buy_num = $chooseroom['book_num'];
	
			$temp_room_price_array = array();
	
			$price_array = array();
	
			//没房，速8不返回
			if( count($room['RoomPrices'])<1 ){
				
				$price_array = array();
				
			}else{
	
				//统一成二维数组，速8接口的多维数组如果只有一个元素时，会变成一维数组
				if( isset( $room['RoomPrices']['RoomPrice'][0] ) ){
						
					//多天
					$price_array = $room['RoomPrices']['RoomPrice'];
						
				}else{
						
					//一天
					$price_array[] = $room['RoomPrices']['RoomPrice'];
						
				}	
				
			}
			
			if( count($price_array) < 1  ){
				
				continue;
				//return null;
				
			}
	
			
	
			$rooms_num_array = array();
	
	
			$room_daily_array = array();
	
			//统一成二维数组，速8接口的多维数组如果只有一个元素时，会变成一维数组
			if(isset ($room['RoomQuantities']['RoomQuantity'][0]) ){
					
				$room_daily_array = $room['RoomQuantities']['RoomQuantity'];
					
			}else{
					
				$room_daily_array[] = $room['RoomQuantities']['RoomQuantity'];
					
			}
	
			//处理，使每日房数的数组，由日期（Y-m-d)作下标，代潜0-X，用于查找值
			foreach($room_daily_array as $room_num){
					
				$rooms_num_array[ $room_num['RoomDay'] ] = $room_num;
					
			}
			
			
			$room_use_coupons_limit = array();
			if( count( $room['RoomCoupons'] ) > 0 ){
				
				if( isset($room['RoomCoupons']['RoomCoupon'][0]) ){
					
					$room_use_coupons_limit = $room['RoomCoupons']['RoomCoupon'];
					
				}else{
					
					$room_use_coupons_limit[] = $room['RoomCoupons']['RoomCoupon'];
					
				}
				
			}

	
			//取速8价格代码配置
			$price_setting_array = $this->getPriceCodeSetting();
			
	
			
			//$price_code:价格代码
			foreach($price_setting_array as $price_code => $price_code_array){

				//团购，特价数组
				$special_tag = array();
				
				//价格描述
				$price_des = "";
				
				//价格代码中文名称
				$temp_room_price_array[ $price_code ]['price_name'] = $price_code_array['price_name'];
				
				//价格代码类型，pms上的价格代码填pms，否则填本地的类型
				$temp_room_price_array[ $price_code ]['price_type'] = 'pms';
					
				/*
				 * 额外信息，当pms上的价格代码有一些要用到的信息是这个结构没有的，
				就把这些信息填到这里，比如价格代码冲突了，就可以把实际的价格代码写到这里来，
				下单时传的价格代码从这里取。这里列的值仅作示例		
				 */
				$temp_room_price_array[ $price_code ]['price_code'] = $price_code;
					
				//价格代码描述，如含双早
				$temp_room_price_array[ $price_code ]['des'] = "";
					
				$temp_room_price_array[ $price_code ]['sort'] = 0;
					
				//展示方式，buy为可购买，only_show为仅作展示，buy_show为展示并可购买
				$temp_room_price_array[ $price_code ]['disp_type'] = 'buy';
					
				//预付，速8接口是针对整个酒店支不支持预付，房间没有支不支持预付这属性
				/* $temp_room_price_array[ $price_code ]['condition'] = array(
							
						'pre_pay'=>0
							
				); */
				
				if($pre_pay == 1){
					$temp_room_price_array[ $price_code ]['condition'] = array(
								
							'pre_pay'=>0,
								
					);
				}else{
					
					$temp_room_price_array[ $price_code ]['condition'] = array(
					
							'pre_pay'=>0,
							'no_pay_way'=>array('weixin','balance')
					
					);
					
				}	
				//$temp_state['condition']['no_pay_way'] = array('daofu');

				$price_daily_temp = array();
				$total_one_price = 0;
				$total_price = 0;
					
				$extro_array = array();
					
				foreach($price_array as  $daily_price){
					
					$day_key = date("Ymd",strtotime($daily_price['RoomDay']));
	
					$temp_room_price_array[ $price_code ]['date_detail'][$day_key]=array(
	
							'price'=>$daily_price [ $price_code_array['price_key'] ],
							'nums'=>$rooms_num_array[ $daily_price['RoomDay'] ] ['CurQuantity']
	
					);
	
					//关闭了房，房数设为0
					if($rooms_num_array[ $daily_price['RoomDay'] ] ['RoomState'] == 0){
							
						$temp_room_price_array[ $price_code ]['date_detail'][$day_key]['nums'] = 0;
							
					}
	
					
					$price_daily_temp[] = $daily_price [ $price_code_array['price_key'] ];
	
					//总价
					$total_price += ($daily_price [ $price_code_array['price_key'] ] * $buy_num );
	
					//总单价
					$total_one_price += $daily_price [ $price_code_array['price_key'] ];
	
					
					$temp_DailyPrices_for_order = array();
					$temp_DailyPrices_for_order['RoomDay'] = $daily_price['RoomDay'];
					$temp_DailyPrices_for_order['Price'] = $daily_price [ $price_code_array['price_key'] ];

					
					$temp_DailyPrices_for_order['BreakfastID'] = 0;
					
				
					$temp_DailyPrices_for_order['BreakfastName'] = $daily_price['BreakfastTypeName'];
					
					if( count( $daily_price['BreakfastTypes']) > 0){
						
						//付加信息，用于速8接口下单时转过去的早餐配置设定
						foreach($daily_price['BreakfastTypes']['BreakfastType'] as $BreakfastTypes){
							
							if( $BreakfastTypes['RateCode'] == $price_code ){
								
								
								$temp_DailyPrices_for_order['BreakfastID'] = $BreakfastTypes['BreakfastTypeID'];
								
								
								
							}
						
							
						}
					}
					
					

					
					//代金券金额，临时，生成订单时更新
					$temp_DailyPrices_for_order['CouponAmount'] = 0;
					
					
					
					$extro_array[] = $temp_DailyPrices_for_order;
	
				}
				
				//print_r($room);
				
				//@Editor lGh 2016-5-11 19:30:21 格式化担保内容
				$guaran=array();
				if (!empty($room['RoomGuaranty'])){
					
					if( isset( $room['RoomGuaranty']['StartTime'] )&& !empty($room['RoomGuaranty']['StartTime']) ){
						
						$guaran['stime']=date('H:i',strtotime($room['RoomGuaranty']['StartTime']));
						
					}else{			
						//如果为空，不影响前端
						$guaran['stime']="";
						
					}
					
					if( isset( $room['RoomGuaranty']['EndTime'] )&& !empty($room['RoomGuaranty']['EndTime']) ){
					
						$guaran['etime']=date('H:i',strtotime($room['RoomGuaranty']['EndTime']));
					
					}else{
						//如果为空，不影响前端
						$guaran['etime']="";
					
					}
					
					
					
					$guaran['etime']=empty($room['RoomGuaranty']['EndTime'])?'':date('H:i',strtotime($room['RoomGuaranty']['EndTime']));
					//$guaran['minnum']=date('H:i',strtotime($room['RoomGuaranty']['BeginNum']));
					$guaran['minnum'] = intval($room['RoomGuaranty']['BeginNum']);
					//是否可预付，0不，1可预付
					$guaran['pre_pay'] = $pre_pay;
					
					
				}
				
				
				$extro_info_array  = array(
						
						/*
						 * 下单时，需要用到的每日价格数据
						 */
						'daily_info' => $extro_array,
						
						/*
						 * 优惠券使用限制数组，如果为空，侧不可使用
						 * [0] => stdClass Object
                                                        (
                                                            [RoomDay] => 2016-04-27
                                                            [Amount] => 30 最高使用额
                                                        )

                                                    [1] => stdClass Object
                                                        (
                                                            [RoomDay] => 2016-04-28
                                                            [Amount] => 30
                                                        )
						 */
						'coupon_limit'=> $room_use_coupons_limit,
						
						//速8增加扣保信息
						'guaran'=>$guaran
						
				);
				
			

				//付加信息，用于速8接口下单时转过去的早餐配置设定
				$temp_room_price_array[ $price_code ]['extra_info'] = $extro_info_array;
					
				//所有的价格序列，如选了20160418-20160419，18日价钱为611，19日价钱为611，则allprice为'611,611'
				$temp_room_price_array[ $price_code ]['allprice'] = implode(',', $price_daily_temp);
					
				//总单价
				$temp_room_price_array[ $price_code ]['total'] = $total_one_price;
					
				//与关联的价格代码计算的描述，如'7折'，对pms价格代码无用,填空即可
				$temp_room_price_array[ $price_code ]['related_des'] = "";
					
				/*
				 * 总价（总单价*房间数），下单时采用此数值作为订单价格 房间数会在传进的参数
				 *  $condit 数组里，键为'nums'，结构为 'nums'=>array('本地room_id'=>房间数量)，
				 *  这里需要判断如果房间数量为空，则给它赋值1
				 */
				$temp_room_price_array[ $price_code ]['total_price'] = $total_price;
			
					
				//$temp_room_price_array[ $price_code ]['avg_price'] = round( $total_price/( $buy_num * count($extro_array)) ,2);					
				//改成了显示首日价
				$temp_room_price_array[ $price_code ]['avg_price'] =  $price_array[0] [ $price_code_array['price_key'] ];
				
									
					
				////价格来源，本地就是jinfangka，外部是webservice	
				$temp_room_price_array[ $price_code ]['price_resource'] = 'webservice';
					
				//速8要求不能订多于5间
				if($room['MinRoomQuantity'] > self::min_room_num){
					
					$temp_room_price_array[ $price_code ]['least_num'] = self::min_room_num;
					
				}else{
					
					$temp_room_price_array[ $price_code ]['least_num'] = $room['MinRoomQuantity'];
					
				}
				//$temp_room_price_array[ $price_code ]['least_num'] = $room['MinRoomQuantity'];
					
				/*
				 * 预订状态，available为可订，full为满房，不可订 将least_num与
				 * 房间数比较得出 下单时以这个值来判断是否可下单
				 */
				if($temp_room_price_array[ $price_code ]['least_num'] < 1){
	
					$temp_room_price_array[ $price_code ]['book_status'] = 'full';
	
				}else{
	
					$temp_room_price_array[ $price_code ]['book_status'] = 'available';
	
				}
					
				/*
				 [allprice] => 611,611						//所有的价格序列，如选了20160418-20160419，18日价钱为611，19日价钱为611，则allprice为'611,611'
				[total] => 1222								//总单价
				[related_des] => 							//与关联的价格代码计算的描述，如'7折'，对pms价格代码无用,填空即可
				[total_price] => 1222						//总价（总单价*房间数），下单时采用此数值作为订单价格 房间数会在传进的参数 $condit 数组里，键为'nums'，结构为 'nums'=>array('本地room_id'=>房间数量)，这里需要判断如果房间数量为空，则给它赋值1
				[avg_price] => 611.00						//每天均价（总单价/天数）
				[price_resource] => webservice				//价格来源，本地就是jinfangka，外部是webservice
				[least_num] => 0							//所选日期中的最少可用房数
				[book_status] => full */
					
				
				//显示早餐，或无早
				$price_des = empty($room['RoomPrices']['RoomPrice']['BreakfastTypeName'])?'':$room['RoomPrices']['RoomPrice']['BreakfastTypeName'];
					
				//特价不
				$super_price_des = "";
				
				if( $pre_pay == 0){
				
					$price_des .= '不可预付';
					$super_price_des .= '不可预付';
				
				}
				
				//设置价格描述
				$temp_room_price_array[ $price_code ]['des'] = $price_des;
					
			}
			
			
	
			//取当前会员价格代码能买到的等级价格
			$state_array = array();
			
			//取要显示其他会员价格
			$other_state_array = array();
			
			//@Editor lGh 2016-4-28 15:09:54 增加是否全满判断
			$all_full=1;
			
			foreach($temp_room_price_array as $price_code => $price_data){
				
				//增加了show_price字段，用于是否显示此价格
				if( $this_member_lev == $price_code){
						
					$state_array[$price_code] = $price_data;
					
					if($price_data['book_status']=='available')
						$all_full=0;
						
				}else{
					
					//官网价不比价
					if( $price_code != "WEB" ){
					
						$other_state_array[$price_code] = $price_data;
						
					}
					
				}
				
			}
		
			//usort会重置第一维的key
			/* usort($other_state_array, function($a, $b) {
            				
							if( $a['avg_price'] > $b['avg_price']){
								
								return 1;
								
							}else{
								
								return -1;
								
							}
  
       		 });
			
			$sort_other_state_array = array();
			foreach($other_state_array as $sort_array){
				
				$sort_other_state_array[$sort_array['price_code']] = $sort_array;
				
			}
			
			$other_state_array = $sort_other_state_array;
			 */
		
			if(count( $room['TeamBuyRooms']) > 0){
				
				//优惠价格数组
				$discount_price_array = array();
				
				if( isset( $room['TeamBuyRooms']['TeamBuyRoom'][0] ) ){
					
					$discount_price_array = $room['TeamBuyRooms']['TeamBuyRoom'];
					
				}else{
					
					$discount_price_array[] = $room['TeamBuyRooms']['TeamBuyRoom'];
					
				}
				
				//存在优惠价格
				if( count( $discount_price_array[0] ) > 0 ){
					
					//$tmp_num = 0;
					//取第一个价格代码设置
					foreach($state_array as $temp_state_info){
						
						break;	
						
					}
					
					//存在团购数据
					if( isset( $temp_state_info['least_num'] ) ){
						
						if($temp_state_info['least_num'] > 0){
							
							$closeRoom = 1;
							
						}else{
							
							$closeRoom = 0;
							
						}
						
						//团购代金券限制跟普通方式一样
						$coupon_limit = $temp_state_info['extra_info']['coupon_limit'];
						
						$RoomGuaranty = $temp_state_info['extra_info']['guaran'];
						
						$least_room_num = $temp_state_info['least_num'];
						
						if(count($price_array) != count($discount_price_array) ){
						
							$least_room_num = 0;
							$closeRoom = 0;
						
						}
						
						//取团购的state_info
						$discount_state_array = $this->getDiscountPriceState($discount_price_array, $buy_num, $pre_pay, $closeRoom, $coupon_limit, $least_room_num,$super_price_des,$RoomGuaranty,$temp_state_info['extra_info']);
					
						
						
						$state_array = array_merge($state_array, $discount_state_array);
						
					}
					
					
					
				}
				
				//$returnData[$room_id]['special_tag'] = $all_full;
				array_push($special_tag, '团');
				
			
			}
			
			//记录最大平均价
			$max_avg = 0;
			
			//记录最小平均价
			$min_avg = 10000000000;
			
			
			//avg_price
			foreach($state_array as $temp_state_array){
				
				
				//计算并记最大最小平均价
				if($temp_state_array['avg_price'] > $max_avg){
						
					$max_avg = $temp_state_array['avg_price'];
						
				}
				
				//计算并记最大最小平均价
				if($temp_state_array['avg_price'] < $min_avg){
				
					$min_avg = $temp_state_array['avg_price'];
				
				}
				
			}
			
			//列表价格根据需求从上到下是从低价到高价
			 usort($state_array, function($a, $b) {
			
					if( $a['avg_price'] > $b['avg_price']){
			
					return 1;
			
					}else{
			
					return -1;
			
					}
			
					});
				
			$sort_other_state_array = array();
			foreach($state_array as $sort_array){
			
				$sort_other_state_array[$sort_array['price_code']] = $sort_array;
			
			}
				
			$state_array = $sort_other_state_array;
			//sssss
			
			$window_des = $this->getSettingBybinary($this->window_des_array, intval( $room['SpecialDesc'] ) );
			
			$window_des = implode(" ", $window_des);
			//print_r($room);
			//exit;sssss RoomArea
			$iwide_room['sub_des'] = $room['BedName']." ".intval($room['RoomArea'])."㎡ ".$window_des;
//sdfdsf

			//传入来平台记录的room信息，原样返回
			$returnData[$room_id]['room_info'] = $iwide_room;
			
			//针对当前会员的价格及房间数组
			$returnData[$room_id]['state_info'] = $state_array;
			
			//显示会员价时用，只做展示用的价格代码,结构与上面的一致，disp_type要改为only_show
			$returnData[$room_id]['show_info'] = $other_state_array;
			
			//所有可用的价格代码中最低的 avg_price
			$returnData[$room_id]['lowest'] = $min_avg;
			
			//所有可用的价格代码中最高的 avg_price
			$returnData[$room_id]['highest'] = $max_avg;

			//@Editor lGh 2016-4-28 14:52:55 增加点击展示价格时跳转链接
			$returnData[$room_id]['all_full'] = $all_full;
			$returnData[$room_id]['disp_price_url'] = site_url('member/center/memberIntro').'?id='.$iwide_room['inter_id'];

			/* [lowest] => 611.00				//所有可用的价格代码中最低的 avg_price
			 [highest] => 672.00 */
			
			
			//增加针对速房间是否可用到coupon的设定，1为可用，0为不可用
			//@Editor lGh 2016-5-4 09:57:59 增加非标准输出的字段时，要使用否定形式以便兼容
// 			$returnData[$room_id]['show_coupon'] = 1;
			$returnData[$room_id]['no_coupon'] = 0;
			
			if(count($room_use_coupons_limit) > 0){
					
				$returnData[$room_id]['no_coupon'] = 0;
					
			}else{
					
				$returnData[$room_id]['no_coupon'] = 1;
					
			}
			
			//团购特价
			$returnData[$room_id]['special_tag'] = $special_tag;
			

			
	
	
		}
	
		//速8要求列表房间时，由价格低至高价
		usort($returnData, function($a, $b) {
				
			if( $a['lowest'] > $b['lowest']){
					
				return 1;
					
			}else{
					
				return -1;
					
			}
				
		});
		

		//usort去掉了原来的key，重新补充，并且将满房排至最后
		$temp_returnData = array();
		foreach($returnData as $rdata){
			
			if($rdata['all_full'] == 0){
				$temp_returnData[ $rdata['room_info']['room_id'] ] = $rdata;
			}
			
		}
		
		foreach($returnData as $rdata){
				
			if($rdata['all_full'] == 1){
				$temp_returnData[ $rdata['room_info']['room_id'] ] = $rdata;
			}
				
		}
		
	

	
		return $temp_returnData;
	
	
	
	}
	
	/**
	 * 取优惠价格显示代码
	 * @param unknown $discount_price_array
	 * @param unknown $pre_pay 支不支持预付，由酒店传入值定
	 * @param int $closeRoom 是否关闭了房 0为关闭
	 * @param array $coupon_limit 跟房间的stateInfo的extra的coupon_limit一样
	 * @param int $least_room_num 最少房间数，不能超5间
	 * @param string $des 描述
	 */
	private function getDiscountPriceState($discount_price_array,$buy_num,$pre_pay,$closeRoom,$coupon_limit,$least_room_num,$des,$RoomGuaranty,$extra){
		
		//价格名称
		$temp_state['price_name'] = $discount_price_array[0]['PriceName'];
		
		//
		$temp_state['price_type'] = 'pms';
		
		//RateCode
		$temp_state['price_code'] = $discount_price_array[0]['RateCode'];
		
		$temp_state['des'] = '';
		
		$temp_state['sort'] = 0;
		
		$temp_state['disp_type'] = 'buy';
		
			
		$temp_state['condition'] = array(
					
				'pre_pay'=>0
					
		);
		//预付，添加预付时不可用的支付方式
		//$temp_state['condition']['no_pay_way']=$pre_pay==0?array('weixin','balance'):array();
		//促销不支持预付，暂只有super特价
		$temp_state['condition']['no_pay_way'] = array('daofu');
		
		//总价
		$total_price = 0;
		
		$price_csv_arr = array();
		
		//总单价
		$total_one_price = 0;
		
		//$temp_state['date_detail']['no_pay_way']=$pre_pay==0?array('weixin','balance'):array();
		foreach( $discount_price_array as $discount_price ){
			
					$day_key = date("Ymd",strtotime($discount_price['RoomDay']));
	
					$temp_state['date_detail'][$day_key]=array(
	
							'price'=>$discount_price['Price'],
							'nums'=>$discount_price['RoomNum']
	
					);
	
					//关闭了房，房数设为0
					if($closeRoom == 0){
							
						$temp_state['date_detail'][$day_key]['nums'] = 0;
							
					}
					
					
					$temp_DailyPrices_for_order = array();
					$temp_DailyPrices_for_order['RoomDay'] = $discount_price['RoomDay'];
					$temp_DailyPrices_for_order['Price'] = $discount_price['Price'];
					$temp_DailyPrices_for_order['BreakfastID'] = $discount_price['BreakfastTypeID'];
					$temp_DailyPrices_for_order['BreakfastName'] = $extra['daily_info'][0]['BreakfastName'];
					
					
					//代金券金额，临时，生成订单时更新
					$temp_DailyPrices_for_order['CouponAmount'] = 0;
					
			
					$temp_state['extra_info']['daily_info'][] = $temp_DailyPrices_for_order;
			
					$total_price += $buy_num * $discount_price['Price'];
					
					$total_one_price += $discount_price['Price'];
					
					$price_csv_arr[] = $discount_price['Price'];
					
		}
		
		
		
		$temp_state['extra_info']['coupon_limit'] = $coupon_limit;
		
		//担保信息
		$temp_state['extra_info']['guaran'] = $RoomGuaranty;
		
			
		//所有的价格序列，如选了20160418-20160419，18日价钱为611，19日价钱为611，则allprice为'611,611'
		$temp_state['allprice'] = implode(',', $price_csv_arr);
					
		//总单价
		$temp_state['total'] = $total_one_price;
					
		//与关联的价格代码计算的描述，如'7折'，对pms价格代码无用,填空即可
		$temp_state['related_des'] = "";
					
				/*
				 * 总价（总单价*房间数），下单时采用此数值作为订单价格 房间数会在传进的参数
				 *  $condit 数组里，键为'nums'，结构为 'nums'=>array('本地room_id'=>房间数量)，
				 *  这里需要判断如果房间数量为空，则给它赋值1
				 */
		$temp_state['total_price'] = $total_price;
		
		//原指平均价，目前速8以首天价格
		$temp_state['avg_price'] = $discount_price_array[0]['Price'];
		
		$temp_state['price_resource'] = 'webservice';
		
		
			//速8要求不能订多于5间
		/* if($least_room_num > self::min_room_num){
					
				$temp_state['least_num'] = self::min_room_num;
					
		}else{
					
				$temp_state['least_num'] = $least_room_num;
				
		} */
		
		//不能多于促销限定房数
		/* /* if($temp_state['least_num'] > $discount_price['RoomNum']){
				
			$temp_state['least_num'] = $discount_price['RoomNum'];
				
		} */ 
		
		
		
		//房间是否可用设
		if($least_room_num < 1 || $discount_price['RoomNum'] < 1){
		
			$temp_state['least_num'] = 0;
			$temp_state['book_status'] = 'full';
		
		}else{
			
			//团购只能选一间房
			$temp_state['least_num'] = 1;
		
			$temp_state['book_status'] = 'available';
		
		}
		
		$temp_state['des'] = $des." 预付";
		
		
		$returnData[ $discount_price_array[0]['RateCode'] ] = $temp_state;
		
		return $returnData;
		
		
		
		
	}
	
	

	
	private function getPriceCodeSetting(){
	
		return Array(
				
				'WEB'=>array(
	
						'price_key'=>'WebPrice',
						'price_code'=>'WEB',
						'price_name'=>'官网价'
			
				),
				'EVIP'=>array(
							
						'price_key'=>'EVIPPrice',
						'price_code'=>'EVIP',
						'price_name'=>'网络会员价'
		
				),
				'VIP'=>array(
							
						'price_key'=>'VIPPrice',
						'price_code'=>'VIP',
						'price_name'=>'贵宾会员价'
		
				),
				'GOLD'=>array(
							
						'price_key'=>'GoldPrice',
						'price_code'=>'GOLD',
						'price_name'=>'金卡会员价'
		
				),
				'SUPER'=>array(
							
						'price_key'=>'SuperPrice',
						'price_code'=>'SUPER',
						'price_name'=>'超级会员价'
		
				),
					
		);
	
	
	}
	
	
	private function sendMsg($status,$err = ""){
	
		return array (
				's' => $status,
				'errmsg' => $err
		);
	
	}
	
	
	private function getPayLogByOrderNo($order_no){
		
		
		$sql = "
				SELECT rtn_content
				FROM
					".$this->readDB()->dbprefix ( self::TAB_PAY_LOG )."
				WHERE
					out_trade_no = '{$order_no}'
				";
		
		
		return $this->readDB()->query ( $sql )->result_array ();
		
		
	}
	
	/**
	 * 速8转有的注册送绩效方法，新版分销上线后重新调用新版分销的送绩效系统。
	 */
	public function addDistributeForAddMember($money,$inter_id,$saler,$grade_openid,$member_id,$member_no,$hotel_id,$hotel_name,$staff_name,$phone){
		
		/* INSERT INTO IWIDE_DISTRIBUTE_GRADE_ALL (`inter_id`,`saler`,`grade_openid`,`grade_id`,`grade_table`,`grade_id_name`,`grade_amount`,`grade_total`,`order_amount`,`grade_time`,`status`,`grade_amount_rate`,`grade_rate_type`,`hotel_id`) VALUES (NEW.`inter_id`,@saler,@openid,NEW.`id`,'iwide_hotels_order','id',@distribute_amount,@amount_total,NEW.`iprice`,NOW(),1,@ex_val,@amount_type,@hotel_id);
		SELECT LAST_INSERT_ID() INTO @last_id;
		INSERT INTO IWIDE_DISTRIBUTE_GRADE_EXT (`hotel_name`,`staff_name`,`cellphone`,`product`,`order_id`,`grade_id`,`inter_id`,`distribute`) VALUES (@hotel_name,@staff_name,@cellphone,NEW.roomname,NEW.orderid,@last_id,NEW.inter_id,@distributed);
			 */
		/* $sql = "
				SELECT `source` as saler,`event_time` as subcribe_time
			    FROM iwide_fans_sub_log 
				WHERE inter_id= {$inter_id}
					AND `event`=2 
					AND openid='{$grade_openid}' limit 1
				";
		$log_data = $this->db->query ( $sql )->result_array ();
		//if($log_data[0])
		
		
		$sql = "SELECT `name` AS staff_name,
						`hotel_name` AS hotel_name,
						`cellphone` AS staff_name,
						'is_distributed' AS distributed
			   FORM
					
				
				" */
		
		
		
		$sql = "
				INSERT INTO iwide_distribute_grade_all
					(`inter_id`,`saler`,`grade_openid`,`grade_id`,`grade_table`,
					`grade_id_name`,`grade_amount`,`grade_total`,
					`order_amount`,`grade_time`,`status`,
					`grade_amount_rate`,`grade_rate_type`,`hotel_id`) 
				VALUES
					(
						'{$inter_id}',
						'{$saler}',
						'{$grade_openid}',
						'{$member_id}',
						'iwide_member_additional',
						'ma_id',
						'{$money}',
						'{$money}',
						'0',
						NOW(),
						'1',
						'1',
						'5',
						'{$hotel_id}'
					)
				
				";
	
		$this->db->query ( $sql );
		
		$grade_id = $this->db->insert_id ();
		
		$sql = "
				INSERT INTO 
					iwide_distribute_grade_ext 
					(`hotel_name`,`staff_name`,`cellphone`,
					`product`,`order_id`,`grade_id`,
					`inter_id`,`distribute`) 
				VALUES 
					('{$hotel_name}',
					 '{$staff_name}',
					 '{$phone}',
					  '会员注册',
					  '{$member_no}',
					  '{$grade_id}',
					  '{$inter_id}',
					  '1')
		
				
				";

		$this->db->query($sql);
		
	}
	
	/**
	 * 速8转有的注册送绩效方法，新版分销上线后重新调用新版分销的送绩效系统。
	 */
	public function getSaler($inter_id,$grade_openid){
		
		$sql = "
		SELECT `source` as saler,`event_time` as subcribe_time
		FROM iwide_fans_sub_log
		WHERE inter_id= '{$inter_id}'
		AND `event`=2
		AND source > 0
		AND openid='{$grade_openid}' limit 1
		";
		
		$saler_data = $this->readDB()->query ( $sql )->result_array ();
		$saler_data = $saler_data[0];
		
		if($saler_data['saler']){
			
			$sql = "SELECT 
						`name` AS staff_name,
						`hotel_name` AS hotel_name,
						`cellphone` AS cellphone,
						is_distributed AS distributed,
						hotel_id,
						qrcode_id as saler
					FROM
						iwide_hotel_staff
					WHERE
						qrcode_id='{$saler_data['saler']}' AND inter_id='{$inter_id}'
			";
			
			$saler_all_data = $this->readDB()->query ( $sql )->result_array ();
			
			$saler_all_data = $saler_all_data[0];
			
			return $saler_all_data;
			
		}else{
			
			return null;
			
		}

		
	}
	
	/**
	 * 速8转有的注册送绩效方法，新版分销上线后重新调用新版分销的送绩效系统。
	 */
	public function getSalerBySub($inter_id,$grade_openid){
	
		$sql = "
		SELECT `source` as saler,`event_time` as subcribe_time
		FROM iwide_fans_subs
		WHERE inter_id= '{$inter_id}'
		AND `event`=2
		AND openid='{$grade_openid}' limit 1
		";
	
		$saler_data = $this->readDB()->query ( $sql )->result_array ();
		$saler_data = $saler_data[0];
	
		if($saler_data['saler']){
			
		$sql = "SELECT
			`name` AS staff_name,
			`hotel_name` AS hotel_name,
			`cellphone` AS cellphone,
			is_distributed AS distributed,
			hotel_id,
			qrcode_id as saler
			FROM
			iwide_hotel_staff
			WHERE
			qrcode_id='{$saler_data['saler']}' AND inter_id='{$inter_id}'
			";
				
			$saler_all_data = $this->readDB()->query ( $sql )->result_array ();
				
			$saler_all_data = $saler_all_data[0];
				
			return $saler_all_data;
				
		}else{
			
		return null;
				
		}
	
	
		}
	
	/**
	 * 速8转有的注册送绩效方法，新版分销上线后重新调用新版分销的送绩效系统。
	 */
	private function getHaveDistributeByOpenid($inter_id,$open_id){
		
		$sql = "
		SELECT
			COUNT(*) AS num
		FROM
			iwide_distribute_grade_all
		WHERE
			inter_id = '{$inter_id}'
		AND
			grade_rate_type = 5
		AND
			grade_openid = '{$open_id}'
		AND 
			saler < 90000
		AND saler != 0
		";
		$data = $this->readDB()->query ( $sql )->result_array ();
		
		return $data[0]['num'];
		
	}
	
	/**
	 * 速8转有的注册送绩效方法，新版分销上线后重新调用新版分销的送绩效系统。
	 */
	public function addRegisterDistributeBySub($inter_id,$openid,$member_add_id,$member_no){
	
	
		//$this->load->model ( 'hotel/pms/suba_hotel_model' );
	
		/* $inter_id = 'a455510007';
			$openid = 'osk49wF-evSZX9ZeBReYv6L8bfDs';
		*/
	
	
	
		$have_num = $this->getHaveDistributeByOpenid($inter_id,$openid);
	
		if($have_num < 1){
	
			$saler_data = $this->getSalerBySub($inter_id,$openid);
				
			$member_id = $member_add_id;
			$member_no = $member_no;
				
			$this->addDistributeForAddMember(1,$inter_id,$saler_data['saler'],$openid,$member_id
					,$member_no,$saler_data['hotel_id'],$saler_data['hotel_name']
					,$saler_data['staff_name'],$saler_data['cellphone']
			);
	
		}
	
	}
	
	/**
	 * 速8转有的注册送绩效方法，新版分销上线后重新调用新版分销的送绩效系统。
	 */
	public function addRegisterDistribute($inter_id,$openid,$member_add_id,$member_no){
		
		
		//$this->load->model ( 'hotel/pms/suba_hotel_model' );
		
		/* $inter_id = 'a455510007';
		$openid = 'osk49wF-evSZX9ZeBReYv6L8bfDs';
		 */
		
		
		
		$have_num = $this->getHaveDistributeByOpenid($inter_id,$openid);
		
		if($have_num < 1){

			$saler_data = $this->getSaler($inter_id,$openid);
			
			$member_id = $member_add_id;
			$member_no = $member_no;
			
			$this->addDistributeForAddMember(1,$inter_id,$saler_data['saler'],$openid,$member_id
					,$member_no,$saler_data['hotel_id'],$saler_data['hotel_name']
					,$saler_data['staff_name'],$saler_data['cellphone']
			);
				
		}
		
	}
	
	/**
	 * 速8 1<<2  1<<3 作输出参数 如 11将两次的值都输出
	 * @param unknown $binaryArray 
	 * 					    数组下标为需移位的数，右边为显示值
	 *                     array(
	 *                     			'1'=>'无窗'
	 *                     			'2'=>'无老鼠'
	 *                     )
	 * @param unknown $binary 转来的2进制数，如上例 1(01) 即输出无窗，3(11)，即输出无窗  无老鼠
	 */
	public function getSettingBybinary($bin_array,$binary){
		
		
		$string = decbin($binary);
		
		/* $bin_array = array(
				'0'=>'无米',
		
				'1'=>'无窗',
				'2'=>'无老鼠'
		); */
		
		$str_arr = $string;
		
		$len = strlen($string);
		
		$arr = array();
		foreach( $bin_array as $num => $value ){
		
			if($len - $num - 1 >= 0){
				if( $str_arr[ $len - $num - 1 ] == 1){
			
					$arr[] = $value;
			
				}
			}
		
		}
		
		return $arr;
		
		
	}
	
	function getFans(){
		
		$sql = "
		SELECT openid FROM `iwide_fans` 
				WHERE inter_id = 'a455510007' 
				AND `subscribe_time` > '2016-10-01' 
				AND id > 4076244
		ORDER BY id asc
		";
		$data = $this->readDB()->query ( $sql )->result_array ();
		
		return $data;
		
		
	}

	private function readDB(){
		static $db_read;
		if(!$db_read){
			$db_read = $this->load->database('iwide_r1',true);
		}
		return $db_read;
	}
	
}