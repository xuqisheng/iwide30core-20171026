<?php
/*
 * 智能调价前台
 * date 2017-03-15
 * author chenjunyu 
 */
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Smarts extends MY_Front{
	function __construct(){
		parent::__construct();
		$this->inter_id=$this->session->userdata('inter_id');
		$this->openid=$this->session->userdata($this->inter_id.'openid');
	}

	//生成调价结果
	public function result_price(){
		$data = array();
		$inter_id = !empty($this->input->get('id'))?$this->input->get('id'):$this->inter_id;
		if(empty($inter_id)){
			exit('wrong inter_id');
		}
		$data['inter_id'] = $inter_id;
		$hotel_id = $this->input->get('hid');
		if(empty($hotel_id)){
			exit('wrong hotel_id');
		}
		$data['hotel_id'] = $hotel_id;
		$batch = !empty($this->input->get('batch'))?$this->input->get('batch'):1;
		// 校验用户身份和权限
		$this->load->model('hotel/hotel_notify_model');
		$this->load->library('MYLOG');
		$reg = $this->hotel_notify_model->get_config($this->openid,$inter_id);
		MYLOG::w('查看调价结果用户身份校验0：'.$this->openid.json_encode($reg),'smarts');
		if(!$reg||!$this->hotel_notify_model->check_reg($reg,'down')){
			redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=non'));
		}
		$this->load->model('price/paritys_model');
		//记录操作日志
		$info = array(
			'inter_id'=>$inter_id,
			'hotel_id'=>$hotel_id,
			);
		$data['date'] = $this->input->get('day');
		$up_result = $this->paritys_model->getUptime($hotel_id ,$inter_id);
		if(!empty($up_result)){
			$data['uptime'] = $up_result['addtime'];
			$up_date = $up_result['adddate'];
			$nowbatch = $up_result['batch'];
		}
		//获取智能调价配置
		$data['configs'] = $this->paritys_model->getSmartConfig($hotel_id ,$inter_id);
		// 判断此次调价执行日期和有效时长
		if(!empty($data['configs']['configs'])&&!empty($up_result)){
			$oktime = strtotime($data['uptime'])+$data['configs']['configs']['effect_time']*60;
			$exec_dates = $data['configs']['configs']['exec_date'];
			if(strtotime($data['date'])<strtotime($up_date)||$oktime<time()||$nowbatch>$batch||strtotime($exec_dates[0])>time()||strtotime($exec_dates[1])<time()){
				redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=ot'));
			}
		}else{
			redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=nor'));
		}
		$data['adjust'] = '';
		$data['adjust'] = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$data['date'],$batch,'cancel');
		if(empty($data['adjust'])){
			$data['adjust'] = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$data['date'],$batch,'confirm');
		}
		$paritys = $this->paritys_model->getHotelParity($inter_id,$hotel_id,'ctrip');
		$data['ctrip_url'] = 'http://hotels.ctrip.com/hotel/'.$paritys['ctrip_id'].'.html';
		$hotels = $this->paritys_model->getAllHotels($hotel_id);
		$data['hotel_name'] = !empty(current($hotels)['name'])?current($hotels)['name']:'';
		// var_dump($data['configs']);exit;
		//获取比价数据
		$rooms = $this->paritys_model->getRooms($hotel_id,'ctrip',$inter_id);
		//计算调价结果
		$data['rooms'] = $this->paritys_model->getResultPrice($rooms,$data['configs']);
		$data['room_num'] = count($data['rooms']);
		$data['price_code_num'] = 0;
		foreach($data['rooms'] as $room){
			$data['price_code_num'] +=  count($room);
		}
		$info = $data['rooms'];
		$info['batch'] = $batch;
		$data['batch'] = $batch; 
		//查看成功
		if(!empty($rooms)&&!empty($up_result)){
			$this->paritys_model->saveAdjustLog($inter_id,$hotel_id,$this->openid,$data['date'],$batch,'see',1,$info);
		}
		// var_dump($data['rooms']);exit;
		$this->display('price/result_price',$data);
	}


	//确定调价
	public function confirm(){
		$data = array();
		$data['h'] = $this->input->get('h');
		$batch = $this->input->get('batch');
		$date = $this->input->get('date');
		$time = date('Y-m-d H:i:s');
		$optype = '';
		$opresult = 0;
		$inter_id = !empty($this->input->get('id'))?$this->input->get('id'):$this->inter_id;
		$hotel_id = $this->input->get('hotel_id');
		$this->load->library('MYLOG');
		switch ($data['h']) {
			case 'ok':
				$data['msg'] = '已确认调整价格';
				$data['img'] = base_url('public/price/default/images/gou.png');
				$optype = 'confirm';
				break;
			case 'cancel':
				$data['msg'] = '已取消调整价格';
				$data['img'] = base_url('public/price/default/images/gou.png');
				$optype = 'cancel';
				$opresult = 1;
				break;
			case 'ot':
				$data['msg'] = '此次调整不在可执行日期或已过期';
				$data['img'] = base_url('public/price/default/images/none.png');
				break;
			case 'non':
				$data['msg'] = '您没有权限操作';
				$data['img'] = base_url('public/price/default/images/none.png');
				break;
			case 'close':
				$data['msg'] = '此次调整已关闭';
				$data['img'] = base_url('public/price/default/images/none.png');
				break;
			case 'nor':
				$data['msg'] = '请完善调价规则';
				$data['img'] = base_url('public/price/default/images/none.png');
				break;
			default:
				$data['msg'] = '非法操作';
				$data['img'] = base_url('public/price/default/images/none.png');
				break;
		}
		$this->load->model('price/paritys_model');
		//记录操作日志 暂不处理
		if($optype=='cancel'){
			$info = array();
			$res = $this->paritys_model->saveAdjustLog($inter_id,$hotel_id,$this->openid,$date,$batch,$optype,$opresult,$info);
			if($res=='al'){
				redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=close'));
			}
		}
		if($optype=='confirm'){
			//验证用户身份和权限
			$this->load->model('hotel/hotel_notify_model');
			$reg = $this->hotel_notify_model->get_config($this->openid,$inter_id);
			MYLOG::w('查看调价结果用户身份校验1：'.$this->openid.json_encode($reg),'smarts');
			if(!$reg||!$this->hotel_notify_model->check_reg($reg,'down')){
				redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=non'));
			}
			//判断此次调价是否已关闭
			$adjust = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$date,$batch,'cancel');
			MYLOG::w('查看此次调价是否已关闭0：'.$this->openid.json_encode($adjust),'smarts');
			if(!empty($adjust)&&$adjust['operate_type']=='cancel'&&$adjust['operate_result']==1){
				redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=close'));
			}
			$adjust = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$date,$batch,'confirm');
			MYLOG::w('查看此次调价是否已关闭1：'.$this->openid.json_encode($adjust),'smarts');
			if(!empty($adjust)&&$adjust['operate_type']=='confirm'&&$adjust['operate_result']==1){
				redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=close'));
			}
			$info = array();
			$up_result = $this->paritys_model->getUptime($hotel_id ,$inter_id);
			$data['uptime'] = $up_result['addtime'];
			$nowbatch = $up_result['batch'];
			//获取智能调价配置
			$data['configs'] = $this->paritys_model->getSmartConfig($hotel_id ,$inter_id);
			MYLOG::w('查看此次调价配置是否已关闭：'.$this->openid.'-'.$inter_id.'-'.$hotel_id.json_encode($data['configs']),'smarts');
			//校验执行日期和有效时长
			if(!empty($data['configs']['configs'])){
				//有效时长
				$oktime = strtotime($data['uptime'])+$data['configs']['configs']['effect_time']*60;
				if($oktime<time()||$nowbatch>$batch){
					redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=ot'));
				}
				//校验执行日期
				$exec_date = $data['configs']['configs']['exec_date'];
				if(!empty($exec_date)&&(time()<strtotime($exec_date[0])||time()>strtotime($exec_date[1]))){
					redirect(site_url('price/smarts/confirm?id='.$inter_id.'&hotel_id='.$hotel_id.'&h=ot'));
				}
			}
			//修改价格
			//获取比价数据
			$rooms = $this->paritys_model->getRooms($hotel_id,'ctrip',$inter_id);
			//计算调价结果
			$result_prices = $this->paritys_model->getResultPrice($rooms,$data['configs']);
			MYLOG::w('调价结果：'.json_encode($result_prices),'smarts');
			//调价
			$day = date('Ymd');
			$res = $this->paritys_model->save_room_price($inter_id, $hotel_id,$result_prices, $day,$batch,$this->openid);
			$opresult = !$res?0:1;
			if($opresult==1){
				$this->load->model('plugins/template_msg_model');
				$condits = array(
					// 'adddate'=>date('Y-m-d'),
					'hotel_ids'=>array($hotel_id),
					'batch'=>$batch,
				);
				$hotels = $this->paritys_model->getDownRate($inter_id,'ctrip',$condits);
				// var_dump($hotels);exit;
				foreach ($hotels as $k => $v) {
					//查出符合接收模板消息的人员信息
					$hotel_ids = array('hotel_ids'=>array(0,$v['hotel_id']));
					$regs = $this->hotel_notify_model->get_hotels_reg($inter_id,$hotel_ids,true);
					if(!empty($regs)){
						foreach($regs as $r=>$reg){
							if($this->hotel_notify_model->check_reg($reg,'change')){
								// 发送模板消息 智能调价通知
								$info = array(
									'inter_id'=>$inter_id,
									'hotel_id'=>$v['hotel_id'],
									'batch'=>$batch,
									'openid'=>$reg['openid'],
									'hotel'=>$v['hotel_name'],
									'warn_type'=> 'change',
									'remark_type'=> 'change',
									'warndate'=> date('Y-m-d H:i:s'),
								);
								$result = $this->template_msg_model->send_smart_price_msg ( $inter_id,$info,'smart_price_complete_notice');
								if($result['s']!=1||$result['errmsg']!='ok'){
									MYLOG::w('智能调价完成模板消息发送失败:'.json_encode($info).'|'.json_encode($result),'smarts');
								}
							}
						}
					}
				}
			}else{
				MYLOG::w('调价失败：'.$inter_id.'-'.$hotel_id.'-'.$day.'-'.json_encode($result_prices).'-'.json_encode($res),'smarts');
			}
		}
		$this->display('price/confirm',$data);
	}
}
