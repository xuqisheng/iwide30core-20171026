<?php
/*
 * 定时处理订单消息提醒任务
 * author chenjunyu 2016-10-27
 */
class Auto_notify extends MY_Controller {
	function __construct() {
		parent::__construct ();
		$this->debug = $this->input->get ( 'debug' );
		error_reporting ( 0 );
		if (! empty ( $this->debug )) {
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 1 );
		}
		$this->output->enable_profiler ( false );
		$this->load->library('MYLOG');
	}

	//定时处理消息提醒队列任务
	public function auto_task(){
		set_time_limit ( 0 );
		$this->db->where(array(
			'type'=>1,//微信提醒
			'wx_type!='=>15,
			'locked'=>2,
			'flag'=>2,
			'oper_times<'=>3,
			'create_time>='=>time()-10*60,
			));
		$data = $this->db->get('hotels_notify_queue')->result_array();
		MYLOG::w('队列记录：'.json_encode($data).'|'.$this->db->last_query(),'Auto_notify');
		$this->load->model('hotel/hotel_notify_model');
		if($data&&is_array($data)){
			foreach ($data as $k => $v) {
				// $this->db->where(array(
				// 	'inter_id'=>$v['inter_id'],
				// 	));
				// $wxconf = $this->db->get('hotels_notify_config')->result_array();
				// if(empty($wxconf[0])){
				// 	$this->load->model('hotel/hotel_notify_model');
				// 	$wxconf[0] = $this->hotel_notify_model->notify_default_config();
				// }
				// if($wxconf[0]['is_weixin']==1&&(in_array($v['wx_type'],explode(',',$wxconf[0]['wx_notify']))||$wxconf[0]['wx_notify']=='all')){//配置微信提醒开启
					//上锁
					$this->db->where(array('id'=>$v['id']));
					$locked = $this->db->update('hotels_notify_queue',array(
						'locked'=>1,
						'update_time'=>time(),
						'oper_times'=>$v['oper_times']+1
						));
					$this->db->where(array(
						'inter_id'=>$v['inter_id'],
						'status'=>1,
						'uptime<='=>$v['create_time'],
						));
					$this->db->where_in('hotel_id',array(0,$v['hotel_id']));
					$regs = $this->db->get('hotels_notify_reg')->result_array();
					MYLOG::w('接收人员：'.json_encode($regs).'|'.$this->db->last_query(),'Auto_notify');
					$this->load->model ( 'plugins/Template_msg_model' );
					if($locked){
						if(!empty($regs)){
							$is_success = false;
							foreach ($regs as $kr => $vr) {
								// 校验用户身份和权限
								MYLOG::w('接收验证：'.json_encode($vr).'|'.$v['wx_type'],'Auto_notify');
								if(!$this->hotel_notify_model->check_reg($vr,$v['wx_type'])){
									continue;
								}
								//发消息
								$order_data = json_decode($v['order_data'],1);
								$order_data['openid'] = $vr['openid'];
								//商城消息提醒
								if($v['module']=='soma'){
									$order_data['touser'] = $vr['openid'];
									$res = $this->Template_msg_model->send_template_msg($v['inter_id'], $order_data);
								}else{
									$res = $this->Template_msg_model->send_hotel_order_msg ( $order_data,$v['tmp_type'] ,1,array('wx_notify'=>explode(',',$vr['wx_notify'])));
								}
								if($res['s']==0){
									MYLOG::w('发送失败|'.$res['errmsg'].'|'.$order_data['openid'].json_encode($v),'Auto_notify');
								}
								if($res['s']==1){
									$is_success = true;
								}
							}
							//第三次不成功记录日志并不再调用该条数据
							$this->db->where(array('id'=>$v['id']));
							if(!$is_success&&$v['oper_times']==2){
								MYLOG::w('三次失败|'.json_encode($v),'Auto_notify');
								$this->db->update('hotels_notify_queue',array(
									'locked'=>2,
									'update_time'=>time(),
									'flag'=>3,//三次失败
								));
							}elseif($is_success){
								//成功
								$this->db->update('hotels_notify_queue',array(
										'locked'=>2,
										'update_time'=>time(),
										'flag'=>1,
									));
							}else{
								//失败
								$this->db->update('hotels_notify_queue',array(
									'locked'=>2,
									'update_time'=>time(),
								));
							}
						}else{
							//没有登记人员，解锁
							$this->db->where(array('id'=>$v['id']));
							$this->db->update('hotels_notify_queue',array(
								'locked'=>2,
								'update_time'=>time(),
							));
						}
					}
				// }
			}
		}
	}
}