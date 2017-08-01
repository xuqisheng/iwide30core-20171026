<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Okpay extends MY_Front {
	public $common_data;
	public $openid;
    protected $_token;

	function __construct() {

		parent::__construct ();
        /*$this->session->set_userdata (array (
            'inter_id' => 'a429262687' ,
            'hotel_id' => 1026,
            'pay_code' => 9,
            'a429262687openid' => 'oX3Wojj8h46C_CN5NoRPfbXwxND8'
        ));*/
        $this->get_Token();
		//统计探针
		$this->load->library('MYLOG');
		MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));
	}
	
	function pay_show(){
		$data = $this->common_data;
		
		$data['timestamp'] = timespan();
		$data['inter_id'] = $this->input->get('id', TRUE );
		$data['hotel_id'] = $this->input->get('hotelid', TRUE );
		$data['pay_code'] = $this->input->get('paycode', TRUE );
		$data['pay_type'] = $this->input->get('paytype', TRUE );
		
		//ck 检测是否是酒店二维码，还是 分销二维码， 酒店通用二维码为1  分销二维码为2 最终记录中paycode=0 的代表酒店通用销售记录
		$data['ck_sale'] = $this->input->get('ck', TRUE ); 
		
		if(!empty($data['inter_id']) && !empty($data['hotel_id'])){
			$this->session->set_userdata (array (
					'inter_id' => $this->inter_id ,
					'hotel_id' => $data['hotel_id'],
					'pay_code' => $data['pay_code'] 
			));
		}
		
		
		$this->load->model ('okpay/Okpay_type_model' );
		$paytype = $this->Okpay_type_model->get_okpay_type_detail($data['pay_type'],$this->inter_id,$data['hotel_id']);
		
		
		if(!empty($paytype)){
			$data['pay_type_desc'] = $paytype['name'];
		}else{
			$data['pay_type_desc'] = "";
		}
		
		//$inter_id, $hotel_id
		//$this->public = $this->Publics_model->get_public_by_id($this->inter_id );
		$this->load->model ( 'hotel/Hotel_model' );
		$data['hotel']  = $this->Hotel_model->get_hotel_detail($data['inter_id'],$data['hotel_id']);
		//--添加余额支付方式 start--//
		$this->load->model ( 'pay/Pay_model' );
		$pay_ways = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => 'hotel',//暂用订房的配置
				'status' => 1,
				'hotel_ids' => $data['hotel_id'] 
		));
		$data['banlance_off'] = true;//默认关闭余额支付
        $data['wx_pay_url'] = site_url('wxpay/okpay_pay') . '?id=' .$data['inter_id'] .'&hid=' .$data['hotel_id'];//默认使用微信原生配置
        $okpay_weixin = $this->Pay_model->get_okpay_config($data['inter_id']);//默认支付方式
		foreach ($pay_ways as $payway) {
			if($payway->pay_type == 'balance'){
				$data['banlance_off'] = false;
			}
            //wft
            if($payway->pay_type == 'weifutong' && $okpay_weixin=='weifutong'){
                $data['wx_pay_url'] = site_url('wftpay/okpay_pay') . '?id=' .$data['inter_id'] .'&hid=' .$data['hotel_id'];
            }
		}
		if($data['banlance_off']){
			$data ['membermoney'] = 0;
		}else{
			$this->load->library ( 'PMS_Adapter', array (
					'inter_id' => $this->inter_id,
					'hotel_id' => $data['hotel_id'] 
			), 'pub_pmsa' );
			//获取用户信息
			$openid	= $this->session->userdata($this->inter_id."openid");
			$member = $this->pub_pmsa->check_openid_member ( $this->inter_id, $openid, array (
					'create' => TRUE,
					'update' => TRUE 
			) );
			$data ['membermoney'] = $member->balance;
		}
		
		/*
		$this->load->model ( 'hotel/Hotel_config_model' );
		$config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', $data['hotel_id'], array (
				'BANCLANCE_COMSUME_CODE_NEED'
		) );
		// 储值消费码
		$data ['banlance_code'] = 0;
		if (! empty ( $config_data ['BANCLANCE_COMSUME_CODE_NEED'] ) && $config_data ['BANCLANCE_COMSUME_CODE_NEED'] == 1) {
			$data ['banlance_code'] = 1;
		}*/
		//添加余额支付方式 end--//
		
		if(!empty($data['pay_type']) && empty($paytype)){
			$this->display('okpay/pay_stop',$data);
		}else{
			//读取活动信息
			$this->load->model ( 'okpay/Okpay_activities_model' );
            $data['activity'] = '';
			$act = $this->Okpay_activities_model->get_okpay_activities_detail($data['inter_id'], $data['hotel_id'],$data['pay_type']);
            if(!empty($act)){
                //星期几
                $date = date('w')?date('w'):7;
                $no_exec_day = empty($act['no_exec_day'])?array():explode(',',$act['no_exec_day']);
                if(!in_array($date,$no_exec_day)) {//在执行日中
                    //对比领取限制配置
                    $start = $end = '';
                    $limit = isset($act['gift_limit']) && !empty($act['gift_limit']) ? explode('|', $act['gift_limit']) : array();
                    if (!empty($limit)) {
                        if (isset($limit[0])) {
                            if ($limit[0] == 'd') {
                                $start = date('Y-m-d 00:00:00');
                                $end = date('Y-m-d 23:59:59');
                            } elseif ($limit[0] == 'm') {
                                $start = date('Y-m-01 00:00:00');
                                $end = date('Y-m-d 23:59:59');
                            } else {
                                $start = date('Y-01-31 00:00:00');
                                $end = date('Y-12-30 23:59:59');
                            }
                        }
                    }
                    $start = strtotime($start);
                    $end = strtotime($end);
                    //查询参加活动记录 stgc 20161019
                    $openid	= $this->session->userdata($this->inter_id."openid");
                    $record = $this->Okpay_activities_model->get_act_record($this->inter_id, $act['id'], $openid, $start, $end);
                    if (isset($limit[1]) && $limit[1] > $record) {//满足条件 才允许参加活动
                        $data['activity'] = $act;
                    }
                }
            }
            //查询余额支付是否设置密码 by:stgc 20161031
           /* $url = INTER_PATH_URL.'setpassword/pay_password_status'; //查询
            $post_data = array(
                'token' => $this->_token,
                'inter_id'=>$this->inter_id,
                'openid' => $openid,
            );
            $info = $this->doCurlPostRequest( $url , $post_data );
            if(0 == $info['err']){//设置了
                $data['set_pass'] = 1;
            }else{
                $data['set_pass'] = 0;
            }*/ //stgc 20161107 先屏蔽 都为空
            $data['set_pass'] = 0; //设为空
            if(isset($paytype['no_sale']) && !empty($paytype['no_sale'])){
                $this->display('okpay/pay_show2',$data);//显示不优惠金额选项
            }else{
                $this->display('okpay/pay_show',$data);
            }
		}
	}
	
	/**
	 * ajax 保存订单，
	 * 适用于：[default];
	 */
	public function new_okpay_order()
	{
		$arr['openid']		= $this->session->userdata($this->inter_id."openid");
		$arr['inter_id']	= $this->session->userdata("inter_id");
		$arr['hotel_id']	= $this->session->userdata("hotel_id");
		$arr['sale']		= $this->input->post("pay_code",true);

		$arr['money'] = $_REQUEST['money'];
		$arr['pay_money'] = $_REQUEST['pay_money'];
        $arr['no_sale_money'] = isset($_REQUEST['no_sale_money'])?$_REQUEST['no_sale_money']:0;
        $arr['discount_money'] = $_REQUEST['discount_money'];
		$arr['pay_type'] = $_REQUEST['pay_type'];//根据场景id查询活动优惠 stgc 20160919
        if(empty($arr['pay_money']) || $arr['pay_money'] < 0){
            return false;
        }
		//读取活动信息
		$this->load->model ( 'okpay/Okpay_activities_model' );
		$act = $this->Okpay_activities_model->get_okpay_activities_detail($arr['inter_id'],$arr['hotel_id'], $arr['pay_type']);
		//使用总金额减去不打折金额 再和活动的满减金额对比 确定应付金额 再和传过来的应付金额对比
		if(!empty($act)){
            //查看活动限制
            //星期几
            $date = date('w')?date('w'):7;
            $no_exec_day = empty($act['no_exec_day'])?array():explode(',',$act['no_exec_day']);
            if(!in_array($date,$no_exec_day)){//在执行日中
                //对比领取限制配置
                $start = $end = '';
                $limit = isset($act['gift_limit'])&&!empty($act['gift_limit'])?explode('|',$act['gift_limit']):array();
                if(!empty($limit)){
                    if(isset($limit[0])){
                        if($limit[0] == 'd'){
                            $start = date('Y-m-d 00:00:00');
                            $end = date('Y-m-d 23:59:59');
                        }elseif($limit[0] == 'm'){
                            $start = date('Y-m-01 00:00:00');
                            $end = date('Y-m-d 23:59:59');
                        }else{
                            $start = date('Y-01-31 00:00:00');
                            $end = date('Y-12-30 23:59:59');
                        }
                    }
                }
                $start = strtotime($start);
                $end = strtotime($end);
                //查询参加活动记录 stgc 20161019
                $record = $this->Okpay_activities_model->get_act_record($arr['inter_id'],$act['id'],$arr['openid'],$start,$end);
                if(isset($limit[1]) && $limit[1] > $record){//满足条件 才允许参加活动
                    //$this->load->model('distribute/follower_report_model');
                    //$this->follower_report_model->write_log(json_encode($act).' | 传过来的：'.json_encode($arr));
                    $money = $arr['money'] - $arr['no_sale_money'];//传过来的应参与打折金额
                    //实际打折金额和设置的金额对比
                    if($money >= $act['isfor_money'] && !empty($act['isfor_money'])){//符合在打折
                        $pay_money = 0;
                        if($act['isfor'] == 1){//每满减  减多次
                            $pay_money = $money - floor($money/$act['isfor_money']) * $act['discount_amount'];
                            //加上不打折金额和传过来的pay_money 对比 一样说明没问题，不一样则报错
                            $pay_money = $pay_money + $arr['no_sale_money'];
                            $arr['pay_money'] = round($arr['pay_money'],2);
                            $pay_money = round($pay_money,2);
                            if(!empty(bccomp($arr['pay_money'],$pay_money,2))){
                                //$this->follower_report_model->write_log('数据有误，终止');
                                return false;
                            }
                        }elseif($act['isfor'] == 2){//单满减 减一次
                            $pay_money = $money - $act['discount_amount'];
                            $pay_money = $pay_money + $arr['no_sale_money'];//var_dump($arr['pay_money']);var_dump($pay_money);die;
                            $arr['pay_money'] = round($arr['pay_money'],2);
                            $pay_money = round($pay_money,2);
                            if(!empty(bccomp($arr['pay_money'],$pay_money,2))){
                                //$this->follower_report_model->write_log('数据有误，终止');
                                return false;
                            }
                        }
                        //$this->follower_report_model->write_log('pay_money：'.json_encode($pay_money));
                        //随机减的另外判断
                        if($act['isfor'] == 3 && !empty($money)){//随机减 money 应该大于0
                            //取出配置
                            $config = isset($act['cut_config'])?unserialize($act['cut_config']) : array();
                            if(!empty($config)){//$config = array('con1'=>array(0=>min,1=>max,2=>rate),'con2'=>array(0=>min,1=>max,2=>rate),'con3'=>array(0=>min,1=>max,2=>rate));
                                $cut_money = 0;
                                $rand_res = $this->get_rand(array('a'=>$config['con1'][2],'b'=>$config['con2'][2],'c'=>$config['con3'][2]));
                                if($rand_res == 'c'){//规则3
                                    $percent = mt_rand($config['con3'][0],$config['con3'][1]);
                                    $cut_money = $money * ($percent/100);
                                }elseif($rand_res == 'b'){//规则2
                                    //随机一个百分数
                                    $percent = mt_rand($config['con2'][0],$config['con2'][1]);
                                    $cut_money = $money * ($percent/100);
                                }else{//规则1
                                    $percent = mt_rand($config['con1'][0],$config['con1'][1]);
                                    $cut_money = $money * ($percent/100);
                                }
                                $cut_money = round($cut_money,2);
                                //应付金额
                                if($money == $cut_money){//免单？
                                    $pay_money = 0.01 + $arr['no_sale_money'];
                                }else{
                                    $pay_money = $money - $cut_money + $arr['no_sale_money'];
                                }
                                /*						$this->follower_report_model->write_log('配置:'.json_encode($config).' | 随机'.'| 百分比'.$percent.'  |pay_money：'.$pay_money.' | 减的金额：'.$cut_money);*/
                                $arr['pay_money'] = $pay_money;
                                $arr['discount_money'] = $cut_money;
                            }
                        }
                        //活动id记录 stgc26161019
                        $arr['activity_id'] = $act['id'];
                    }
                }
            }
		}
		//读取昵称
		$this->load->model('okpay/okpay_fans_model');
		$fans = $this->okpay_fans_model->get_fans_nickname($arr['inter_id'],$arr['openid']);
		$arr['nickname'] = $fans['nickname'];
		
		
		//需要根据支付类型id 从支付类型表中读取类型信息显示。。
		$arr['pay_type_desc'] = "快乐付";
		$arr['pay_type'] = $_REQUEST['pay_type'];
		if(!empty($arr['pay_type'])){
			$this->load->model('okpay/Okpay_type_model');
			$type = $this->Okpay_type_model->get_okpay_type_detail($arr['pay_type'],$arr['inter_id'],$arr['hotel_id']);
			if(!empty($type)){
				$arr['pay_type_desc'] = $type['name'];
			}
		}
		//OKP+时间戳+随机三位数
		$arr['out_trade_no'] = "OP".time().rand(1000,9909);
        //微信选择支付方式
        $this->load->model ( 'pay/Pay_model' );
        $okpay_weixin = $this->Pay_model->get_okpay_config($this->inter_id);//默认支付方式
        if($okpay_weixin == 'weifutong'){
            $arr['pay_way'] = 11;//weifutong
        }

        $this->load->model ( 'hotel/Hotel_model' );
		$hotel = $this->Hotel_model->get_hotel_detail($this->inter_id,$arr['hotel_id']);
		$arr['hotel_name'] = $hotel['name'];

		$this->load->model('okpay/okpay_model');
		$res = $this->okpay_model->create_new_okpay_order($arr);
		if($res){
			echo json_encode(array('errmsg'=>'ok', 'oid'=>$arr['out_trade_no']));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	
	function pay_consular(){
		//echo 'hello';
        $data['hotel'] = array('intro_img'=>'http://file.iwide.cn/public/uploads/201512/qf171108342713.jpg','name'=>'foofofo');
        $data['gift'] = array('credit'=>1,'card'=>array(0=>array('title'=>'20元券','card_id'=>234,'reduce_cost'=>20,'use_time_end'=>1457572967)));
        $data['inter_id'] = $this->inter_id;
        $this->display('okpay/pay_consular',$data);
	}

	function pay_info(){
		$data = $this->common_data;

		$order_id   = $this->input->get("oid",true);
		$saler_id   = $this->input->get("paycode",true);
		$hotel_id   = $this->input->get("hotel_id",true);

		//加载分销员信息
		//$this->load->model ( 'distribute/Staff_model' );
		//$data['saler'] = $this->Staff_model->saler_info($this->openid,$this->inter_id);
		//加载酒店信息
		$this->load->model ( 'hotel/Hotel_model' );
		$data['hotel']  = $this->Hotel_model->get_hotel_detail($this->inter_id,$hotel_id);
		//加载订单信息
		$this->load->model ( 'okpay/Okpay_model' );
		$data['order'] = $this->Okpay_model->get_okpay_order_detail($order_id,$this->openid);
	
		if(!empty($data['order'])){
			$this->load->model ('okpay/Okpay_type_model' );
			$paytype = $this->Okpay_type_model->get_okpay_type_detail($data['order']['pay_type'],$this->inter_id,$hotel_id);
			if(!empty($paytype)){
				$data['store_name'] = $paytype['store_name'];
				$data['store_url']  = $paytype['store_url'];
			}else{
				$data['store_name'] = "";
				$data['store_url']  = "";
			}
            //微信选择支付方式
            $this->load->model ( 'pay/Pay_model' );
            $okpay_weixin = $this->Pay_model->get_okpay_config($this->inter_id);//默认支付方式
            $data['wx_pay_url'] = site_url('wxpay/okpay_pay') . '?id=' .$this->inter_id .'&hid=' .$hotel_id .'&oid='.$data['order']['out_trade_no'];
            if($okpay_weixin == 'weifutong'){
                $data['wx_pay_url'] = site_url('wftpay/okpay_pay') . '?id=' .$this->inter_id .'&hid=' .$hotel_id .'&oid='.$data['order']['out_trade_no'];
            }
		}else{
			echo 'data error！';
			die;	
		}

		$this->display('okpay/pay_info',$data);

	}


	/**
	 * 订单支付详情
	 */
	function pay_detail(){
		$data = $this->common_data;

		$order_id   = addslashes($_REQUEST['oid']);
		//加载分销员信息
		$this->load->model ( 'distribute/Staff_model' );
		$data['saler'] = $this->Staff_model->saler_info($this->openid,$this->inter_id);
		//加载酒店信息
		$this->load->model ( 'hotel/Hotel_model' );
		$data['hotel']  = $this->Hotel_model->get_hotel_detail($this->inter_id,$data['saler']['hotel_id']);
		//加载订单信息
		$this->load->model ( 'okpay/Okpay_model' );
		$data['order'] = $this->Okpay_model->get_saler_okpay_order_detail($this->inter_id,$data['saler']['qrcode_id'],$order_id);
		//读取消费者信息
		if(!empty($data['order']['openid'])){
			$fans	= $this->Okpay_model->get_fans_info($data['order']['openid']);
			$data['fans_nickname'] = $fans['nickname'];
		}
		$this->display('okpay/pay_detail',$data);
	}
	
	function pay_gather_list(){
		$data = $this->common_data;

		$type = $this->input->get("type");
		$data['page_type'] = $type;

		$this->load->model ( 'distribute/Staff_model' );
		$data['saler'] = $this->Staff_model->saler_info($this->openid,$this->inter_id);

		$begin_time	= "";
		$end_time	= "";
		if($type == 2){
			$begin_time = strtotime(date("Y-m-d",time()));
			$end_time   = $begin_time + 86400;
		}else if($type == 3){
			$begin_time = strtotime(date("Y-m",time()));
			$end_time   = time();

		}
		//根据分销员的id 读取分销数据
		$this->load->model ( 'okpay/Okpay_model' );
		$data['sale_list']	= $this->Okpay_model->get_saler_okpay_recode($this->inter_id,$data['saler']['qrcode_id'],"",$begin_time,$end_time);
		$data['sale_count']	= $this->Okpay_model->get_saler_okpay_count($this->inter_id,$data['saler']['qrcode_id'],3,$begin_time,$end_time);

		//....读取所有交易次数
		$begin_time	= "";
		$end_time	= "";
		$data['sale_all_count']	= $this->Okpay_model->get_saler_okpay_times($this->inter_id,$data['saler']['qrcode_id'],$begin_time,$end_time);
		//读取今天
		$begin_time = strtotime(date("Y-m-d",time()));
		$end_time   = $begin_time + 86400;
		$data['sale_day_count']	= $this->Okpay_model->get_saler_okpay_times($this->inter_id,$data['saler']['qrcode_id'],$begin_time,$end_time);

		//读取本月
		$begin_time = strtotime(date("Y-m",time()));
		$end_time   = time();
		$data['sale_month_count']	= $this->Okpay_model->get_saler_okpay_times($this->inter_id,$data['saler']['qrcode_id'],$begin_time,$end_time);


		$this->display('okpay/pay_gather_list',$data);
	}
	
	function pay_no_task(){
	
	}
	
	function pay_select(){
	
	}

	function pay_error(){
		$data = $this->common_data;

		$openid		= $this->session->userdata($this->inter_id."openid");
		$inter_id	= $this->session->userdata("inter_id");
		$hotel_id	= $this->session->userdata("hotel_id");
		$order_id = addslashes($_REQUEST['oid']);

		//加载酒店信息
		$this->load->model ( 'hotel/Hotel_model' );
		$data['hotel']  = $this->Hotel_model->get_hotel_detail($inter_id,$hotel_id);

		$this->load->model ( 'okpay/Okpay_model' );
		$data['order'] = $this->Okpay_model->get_okpay_order_detail($order_id,$openid);
        if(empty($data['order'])){
            echo 'order error!';
            die;
        }
        //微信选择支付方式
        $this->load->model ( 'pay/Pay_model' );
        $okpay_weixin = $this->Pay_model->get_okpay_config($this->inter_id);//默认支付方式
        $data['wx_pay_url'] = site_url('wxpay/okpay_pay') . '?id=' .$inter_id .'&hid=' .$hotel_id .'&oid='.$data['order']['out_trade_no'];
        if($okpay_weixin == 'weifutong'){
            $data['wx_pay_url'] = site_url('wftpay/okpay_pay') . '?id=' .$inter_id .'&hid=' .$hotel_id .'&oid='.$data['order']['out_trade_no'];
        }

		if(!empty($data['order']) && 3 == $data['order']['pay_status']){
			$this->display('okpay/pay_success',$data);
		}else{
			$this->display('okpay/pay_error',$data);
		}

	}
	
	function pay_success(){
		$data = $this->common_data;

		$openid		= $this->session->userdata($this->inter_id."openid");
		$inter_id	= $this->session->userdata("inter_id");
		$hotel_id	= $this->session->userdata("hotel_id");
		$order_id   = addslashes($_REQUEST['oid']);

		//加载酒店信息
		$this->load->model ( 'hotel/Hotel_model' );
		$data['hotel']  = $this->Hotel_model->get_hotel_detail($inter_id,$hotel_id);

		$this->load->model ( 'okpay/Okpay_model' );
		$data['order'] = $this->Okpay_model->get_okpay_order_detail($order_id,$openid);
		
		if(!empty($data['order'])){
            if(3 != $data['order']['pay_status']){
                //支付失败跳转到失败页面
                redirect(site_url ( 'okpay/okpay/pay_error' ) . '?id=' . $inter_id .'&oid='.$order_id);
                die;
            }
			$this->load->model ('okpay/Okpay_type_model' );
			$paytype = $this->Okpay_type_model->get_okpay_type_detail($data['order']['pay_type'],$inter_id,$hotel_id);
			if(!empty($paytype)){
				$data['store_name'] = $paytype['store_name'];
				$data['store_url'] = $paytype['store_url'];
			}else{
				$data['store_name'] = "";
				$data['store_url'] = "";
			}
            //添加礼包规则判断
            $can_get_package = 0;
            if(3 == $data['order']['pay_status']){
                //读取是否有可领取的礼包规则(指定时间有效)  多个符合只读最新的
                $this->load->model('okpay/okpay_package_model');
                $package = $this->okpay_package_model->get_package_detail($inter_id,$hotel_id,$data['order']['pay_type']);
                if(!empty($package)){
                    //判断消费起额是否满足
                    if(bcsub($data['order']['pay_money'],$package['start_money'],2) >= 0 ){//满足
                            //星期几
                            $date = date('w')?date('w'):7;
                            $no_exec_day = empty($package['no_exec_day'])?array():explode(',',$package['no_exec_day']);
                            if(!in_array($date,$no_exec_day)){//在执行日中
                                //查询改订单号是否有领取记录 （礼包规则id查）
                                $res = $this->okpay_package_model->get_package_record_count($inter_id,$openid,$package['id'],$data['order']['out_trade_no']);
                                if(empty($res)){//没有领取
                                    //对比领取限制配置
                                    $start = $end = '';
                                    $limit = isset($package['gift_limit'])&&!empty($package['gift_limit'])?explode('|',$package['gift_limit']):array();
                                    if(!empty($limit)){
                                        if(isset($limit[0])){
                                            if($limit[0] == 'd'){
                                                $start = date('Y-m-d 00:00:00');
                                                $end = date('Y-m-d 23:59:59');
                                            }elseif($limit[0] == 'm'){
                                                $start = date('Y-m-01 00:00:00');
                                                $end = date('Y-m-d 23:59:59');
                                            }else{
                                                $start = date('Y-01-31 00:00:00');
                                                $end = date('Y-12-30 23:59:59');
                                            }
                                        }
                                    }
                                    //查看该openid所有的已领取记录
                                    $get_record = $this->okpay_package_model->get_package_record_count($inter_id,$openid,$package['id'],'',$start,$end);
                                    //比较是否满足领取次数限制
                                    if(isset($limit[1]) && $get_record < $limit[1]){//满足条件 可以出现领取入口
                                        $can_get_package = 1;
                                    }
                                }
                        }
                    }
                }
            }
		}
        $data['can_get_package'] = $can_get_package;
        if($can_get_package){
            //领取入口链接
            $data['package_url'] = site_url('okpay/okpay/get_package?id='.$inter_id.'&order_sn='.$data['order']['out_trade_no'].'&pid='.$package['id']);
        }

		if(!empty($data['order']) && 3 != $data['order']['pay_status']){
			$this->display('okpay/pay_error',$data);
		}else{
			$this->display('okpay/pay_success',$data);
		}
	}
	
	function pay_record(){
		$data = $this->common_data;

		$openid		= $this->session->userdata($this->inter_id."openid");
		$inter_id	= $this->session->userdata("inter_id");
		$hotel_id	= $this->session->userdata("hotel_id");

		//加载酒店信息
		$this->load->model ( 'hotel/Hotel_model' );
		$data['hotel']  = $this->Hotel_model->get_hotel_detail($inter_id,$hotel_id);


		//加载对应酒店消费信息
		$this->load->model ( 'okpay/Okpay_model' );
		$data['recods'] = $this->Okpay_model->get_hotel_okpay_recode($hotel_id,$inter_id,$openid);
		
		if(empty($data['recods'])){
			$this->display('okpay/pay_no_record', $data);
		}else{
			$data['paycount'] = $this->Okpay_model->get_hotel_pay_count($hotel_id,$inter_id,$openid,3);
			$this->display('okpay/pay_record',$data);
		}
	}
	
	//使用余额支付快乐付
	function okpay_by_banlance(){
		$arr['openid']		= $this->session->userdata($this->inter_id."openid");
		$arr['inter_id']	= $this->session->userdata("inter_id");
		$arr['hotel_id']	= $this->session->userdata("hotel_id");
		$arr['sale']		= $this->input->post("pay_code",true);

		$arr['money'] = $_REQUEST['money'];
		$arr['pay_money'] = $_REQUEST['pay_money'];
		$arr['no_sale_money'] = isset($_REQUEST['no_sale_money'])?$_REQUEST['no_sale_money']:0;
		$arr['discount_money'] = $_REQUEST['discount_money'];
        if(empty($arr['pay_money']) || $arr['pay_money'] < 0){
            return false;
        }
        //添加余额支付密码判断 stgc 20161031
        //查询余额支付是否设置密码 by:stgc 20161031
        /*$url = INTER_PATH_URL.'setpassword/pay_password_status'; //查询
        $post_data = array(
            'token' => $this->_token,
            'inter_id'=>$this->inter_id,
            'openid' => $arr['openid'],
        );
        $info = $this->doCurlPostRequest( $url , $post_data );
        if(0 == $info['err']){//设置了
            $banlance_pwd = $this->input->post("banlance_pwd",true);
            //获取余额支付密码
            $get_pw_url = INTER_PATH_URL.'setpassword/get_pay_password';  //获取支付密码
            $password = $this->doCurlPostRequest( $get_pw_url , $post_data );
            if(0 == $password['err']){
                if(sha1($banlance_pwd.'jfkhp') != $password['data']){
                    echo json_encode(array('errmsg'=>'pwfail','msg'=>'支付密码错误'));
                    die;
                }
            }else{
                echo json_encode(array('errmsg'=>'pwfail','msg'=>$password['msg']));
                die;
            }
        }*/  //20161107  先屏蔽

		$arr['pay_type'] = $_REQUEST['pay_type'];//根据场景id查询优惠 stgc 20160919.
		//读取活动信息
		$this->load->model ( 'okpay/Okpay_activities_model' );
		$act = $this->Okpay_activities_model->get_okpay_activities_detail($arr['inter_id'],$arr['hotel_id'],$arr['pay_type']);
		//使用总金额减去不打折金额 再和活动的满减金额对比 确定应付金额 再和传过来的应付金额对比
		if(!empty($act)){
            //查看活动限制
            //星期几
            $date = date('w')?date('w'):7;
            $no_exec_day = empty($act['no_exec_day'])?array():explode(',',$act['no_exec_day']);
            if(!in_array($date,$no_exec_day)) {//在执行日中
                //对比领取限制配置
                $start = $end = '';
                $limit = isset($act['gift_limit']) && !empty($act['gift_limit']) ? explode('|', $act['gift_limit']) : array();
                if (!empty($limit)) {
                    if (isset($limit[0])) {
                        if ($limit[0] == 'd') {
                            $start = date('Y-m-d 00:00:00');
                            $end = date('Y-m-d 23:59:59');
                        } elseif ($limit[0] == 'm') {
                            $start = date('Y-m-01 00:00:00');
                            $end = date('Y-m-d 23:59:59');
                        } else {
                            $start = date('Y-01-31 00:00:00');
                            $end = date('Y-12-30 23:59:59');
                        }
                    }
                }
                $start = strtotime($start);
                $end = strtotime($end);
                //查询参加活动记录 stgc 20161019
                $record = $this->Okpay_activities_model->get_act_record($arr['inter_id'], $act['id'], $arr['openid'], $start, $end);
                if (isset($limit[1]) && $limit[1] > $record) {//满足条件 才允许参加活动
                    //$this->load->model('distribute/follower_report_model');
                    //$this->follower_report_model->write_log(json_encode($act).' | 传过来的：'.json_encode($arr));
                    $money = $arr['money'] - $arr['no_sale_money'];//传过来的应参与打折金额
                    //实际打折金额和设置的金额对比
                    if($money >= $act['isfor_money'] && !empty($act['isfor_money'])){//符合在打折
                        $pay_money = 0;
                        if($act['isfor'] == 1){//每满减  减多次
                            $pay_money = $money - floor($money/$act['isfor_money']) * $act['discount_amount'];
                            //加上不打折金额和传过来的pay_money 对比 一样说明没问题，不一样则报错
                            $pay_money = $pay_money + $arr['no_sale_money'];
                            $arr['pay_money'] = round($arr['pay_money'],2);
                            $pay_money = round($pay_money,2);
                            if(!empty(bccomp($arr['pay_money'],$pay_money,2))){
                                //$this->follower_report_model->write_log('数据有误，终止');
                                return false;
                            }
                        }elseif($act['isfor'] == 2){//单满减 减一次
                            $pay_money = $money - $act['discount_amount'];
                            $pay_money = $pay_money + $arr['no_sale_money'];
                            $arr['pay_money'] = round($arr['pay_money'],2);
                            $pay_money = round($pay_money,2);
                            if(!empty(bccomp($arr['pay_money'],$pay_money,2))){
                                //$this->follower_report_model->write_log('数据有误，终止');
                                return false;
                            }
                        }
                        //$this->follower_report_model->write_log('pay_money：'.json_encode($pay_money));
                        //随机减的另外判断
                        if($act['isfor'] == 3 && !empty($money)){//随机减
                            //取出配置
                            $config = isset($act['cut_config'])?unserialize($act['cut_config']) : array();
                            if(!empty($config)){//$config = array('con1'=>array(0=>min,1=>max,2=>rate),'con2'=>array(0=>min,1=>max,2=>rate),'con3'=>array(0=>min,1=>max,2=>rate));
                                $cut_money = 0;
                                $rand_res = $this->get_rand(array('a'=>$config['con1'][2],'b'=>$config['con2'][2],'c'=>$config['con3'][2]));
                                if($rand_res == 'c'){
                                    $percent = mt_rand($config['con3'][0],$config['con3'][1]);
                                    $cut_money = $money * ($percent/100);
                                }elseif($rand_res == 'b'){
                                    //随机一个百分数
                                    $percent = mt_rand($config['con2'][0],$config['con2'][1]);
                                    $cut_money = $money * ($percent/100);
                                }else{
                                    $percent = mt_rand($config['con1'][0],$config['con1'][1]);
                                    $cut_money = $money * ($percent/100);
                                }
                                //应付金额
                                if($money == $cut_money){//免单？
                                    $pay_money = 0.01 + $arr['no_sale_money'];
                                }else{
                                    $pay_money = $money - $cut_money + $arr['no_sale_money'];
                                }
                                /*						$this->follower_report_model->write_log('配置:'.json_encode($config).' | 随机'.$rand.'| 百分比'.$percent.'  |pay_money：'.$pay_money.' | 减的金额：'.$cut_money);*/
                                $arr['pay_money'] = $pay_money;
                                $arr['discount_money'] = $cut_money;
                            }
                        }
                        //活动id记录 stgc26161019
                        $arr['activity_id'] = $act['id'];
                    }
                }
            }

		}
		//读取昵称
		$this->load->model('okpay/okpay_fans_model');
		$fans = $this->okpay_fans_model->get_fans_nickname($arr['inter_id'],$arr['openid']);
		$arr['nickname'] = $fans['nickname'];
		
		
		//需要根据支付类型id 从支付类型表中读取类型信息显示。。
		$arr['pay_type_desc'] = "快乐付";
		$arr['pay_type'] = $_REQUEST['pay_type'];
		if(!empty($arr['pay_type'])){
			$this->load->model('okpay/Okpay_type_model');
			$type = $this->Okpay_type_model->get_okpay_type_detail($arr['pay_type'],$arr['inter_id'],$arr['hotel_id']);
			if(!empty($type)){
				$arr['pay_type_desc'] = $type['name'];
			}
		}
		//OKP+时间戳+随机三位数
		$arr['out_trade_no'] = "OP".time().rand(1000,9909);

		$this->load->model ( 'hotel/Hotel_model' );
		$hotel = $this->Hotel_model->get_hotel_detail($this->inter_id,$arr['hotel_id']);
		$arr['hotel_name'] = $hotel['name'];
		
		$this->load->model('okpay/okpay_model');
		$res = $this->okpay_model->create_okpay_by_banlance($arr);
		if($res){
			echo json_encode(array('errmsg'=>'ok', 'oid'=>$arr['out_trade_no']));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}

    //快乐付消费礼包领取结果通知页面
    public function get_package(){
        $data['inter_id'] = addslashes($this->input->get('id', TRUE ));
        $data['pid'] = (int)$this->input->get('pid', TRUE );//礼包规则的id( 和 礼包package_id有区别)
        $data['order_sn'] = $this->input->get('order_sn', TRUE );
        $data['openid']		= $this->session->userdata($this->inter_id."openid");
        $msg = '';
        if(empty($data['inter_id']) && empty($data['pid']) && empty($data['order_sn'])){
            echo 'params empty!';
            die;
        }
        //查询订单信息
        $this->load->model('okpay/okpay_package_model');
        $order = $this->okpay_package_model->get_order_info($data);
        if(!empty($order)){
            //是否存在可领取礼包的规则 (根据礼包规则id查)
            $package = $this->okpay_package_model->get_package_detail($data['inter_id'],$order['hotel_id'],$order['pay_type'],$data['pid']);//var_dump($package);die;
            if(!empty($package) && (bcsub($order['pay_money'],$package['start_money'],2) >= 0)){//满足消费起额
                //不执行日
                //星期几
                $date = date('w')?date('w'):7;
                $no_exec_day = empty($package['no_exec_day'])?array():explode(',',$package['no_exec_day']);
                if(in_array($date,$no_exec_day)){
                    echo '不符合规则';
                    die;
                }
                //查询该订单是否已经有领取记录(根据礼包规则id查)
                $times = $this->okpay_package_model->get_package_record_count($data['inter_id'],$data['openid'],$data['pid'],$data['order_sn']);
                if(empty($times)){//没有领取
                    //对比领取限制配置
                    $start = $end = '';
                    $limit = isset($package['gift_limit'])&&!empty($package['gift_limit'])?explode('|',$package['gift_limit']):array();
                    if(!empty($limit)){
                        if(isset($limit[0])){
                            if($limit[0] == 'd'){
                                $start = date('Y-m-d 00:00:00');
                                $end = date('Y-m-d 23:59:59');
                            }elseif($limit[0] == 'm'){
                                $start = date('Y-m-01 00:00:00');
                                $end = date('Y-m-d 23:59:59');
                            }else{
                                $start = date('Y-01-31 00:00:00');
                                $end = date('Y-12-30 23:59:59');
                            }
                        }
                    }
                    //查看所有的已领取记录
                    $get_record = $this->okpay_package_model->get_package_record_count($data['inter_id'],$data['openid'],$data['pid'],'',$start,$end);
                    //比较是否满足领取次数限制
                    if(isset($limit[1]) && $get_record < $limit[1]){//满足条件 可以领取
                        //查询礼包是否存在
                        $package_url = INTER_PATH_URL.'package/getinfo'; //获取单个礼包信息
                        $post_data = array(
                            'token' => $this->_token,
                            'inter_id'=>$this->inter_id,
                            'package_id' => $package['package_id'],
                            'status' => 1
                        );
                        $package_info = $this->doCurlPostRequest( $package_url , $post_data );
                        MYLOG::w($this->inter_id.'__快乐付获取礼包信息：'.json_encode($package_info), 'okpay/package');
                        if(isset($package_info['data']) && empty($package_info['data'])){//空礼包
                            echo 'ERROR,礼包信息为空。';
                            die;
                        }
                        $packge_url = INTER_PATH_URL.'package/receive'; //领取礼包
                                $package_data = array(
                                    'token'=>$this->_token,
                                    'inter_id'=>$this->inter_id,
                                    'openid'=>$this->openid,
                                   // 'uu_code'=>uniqid(),
                                    'package_id'=>$package['package_id'],
                                );
                                //查看发送份数
                                if($package['count']){
                                        $package_data['count'] = $package['count'];//需要领取礼包的份数
                                        $package_data['uu_code'] = uniqid();
                                        //发送
                                        $res = $this->doCurlPostRequest( $packge_url , $package_data );//var_dump($res);die;
                                    MYLOG::w($this->inter_id.'__快乐付礼包领取结果：'.json_encode($res), 'okpay/package');
                                    if(isset($res['err']) && $res['err'] == 0 ){//组装礼包内容信息
                                        //插入领取记录表
                                        $arr = array(
                                            'inter_id'=>$this->inter_id,
                                            'openid'=>$this->openid,
                                            'package_id'=>$package['id'],
                                            'count'=>$package['count'],
                                            'order_sn'=>$data['order_sn'],
                                            'add_time'=>date('Y-m-d H:i:s')
                                        );
                                        $insert = $this->db->insert('okpay_package_log',$arr);
                                        if($insert){//插入成功
                                            $arr = array();
                                            if(isset($package_info['data']) && !empty($package_info['data'])){
                                                if(isset($package_info['data']['credit']) && !empty($package_info['data']['credit']) && $package_info['data']['credit']!='0.00'){
                                                    $arr['credit'] = $package_info['data']['credit'];
                                                }
                                                if(isset($package_info['data']['card']) && !empty($package_info['data']['card'])){
                                                    $card_url = INTER_PATH_URL.'intercard/getinfo'; //
                                                    $card_data = array(
                                                        'token' => $this->_token,
                                                        'inter_id'=>$this->inter_id,
                                                    );
                                                    $tmp = array();
                                                    $title = array();
                                                    foreach($package_info['data']['card'] as $k=>$v){
                                                        $card_data['card_id'] = $v['value'];
                                                        $res = $this->doCurlPostRequest( $card_url , $card_data );//var_dump($res);die;
                                                        // $res = json_decode($res,true);
                                                        if(!empty($res['data'])){
                                                            $title[] = $res['data']['title'];
                                                            $tmp['title'] = $res['data']['title'];
                                                            $tmp['card_id'] = $res['data']['card_id'];
                                                            $tmp['use_time_end'] = $res['data']['use_time_end'];
                                                            $tmp['reduce_cost'] = $res['data']['reduce_cost'];
                                                        }
                                                        $arr['card'][] = $tmp;
                                                    }
                                                }
                                            }
                                            $data['gift'] = $arr;
                                            $data['title'] = implode('、',$title);
                                            //加载酒店信息
                                            $this->load->model ( 'hotel/Hotel_model' );
                                            $data['hotel']  = $this->Hotel_model->get_hotel_detail($this->inter_id,$order['hotel_id']);
                                            $data['inter_id'] = $this->inter_id;
                                            $this->display('okpay/pay_consular',$data);
                                        }else{
                                            $msg =  '程序有误,请联系技术。';
                                        }
                                    }else{
                                        $msg =  !empty( $res['msg'])? $res['msg']:'操作有误';
                                    }
                                }else{
                                    $msg = '配置数据有误！';
                                }
                    }else{
                        $msg =  '超出领取限制!';
                    }
                }else{
                    $msg =  '该订单号 已经领取过!';
                }
            }else{
                $msg =  '不符合领取条件!';
            }
        }else{
            $msg =  '该订单不存在!';
        }
        if(!empty($msg)){
            $this->display('okpay/pay_consular',array('msg'=>$msg));
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
    //随机概率
    protected function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);             //抽取随机数
            if ($randNum <= $proCur) {
                $result = $key;                         //得出结果
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}