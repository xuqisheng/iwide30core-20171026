<?php
/**
* 酒店提醒
* author chenjunyu 2016-10-19
*/

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
header("Content-type:text/html;charset=utf-8");

class Hotel_notify extends MY_Front{

	function __construct(){
		parent::__construct();
		$this->inter_id=$this->session->userdata('inter_id');
		$this->openid=$this->session->userdata($this->inter_id.'openid');
		MYLOG::hotel_tracker($this->openid,  $this->inter_id);
		$this->datas['inter_id'] = $this->inter_id;
		if($this->input->get('asd')){
			$this->output->enable_profiler(true);
		}
	}

	public function index(){
		
	}

	public function register(){
		$data = array();
		$this->load->model('hotel/hotel_notify_model');
		//查询该用户的绑定情况和状态
		$reg_info = $this->hotel_notify_model->get_config($this->openid,$this->inter_id);
		if($reg_info){
			redirect(site_url('hotel_notify/hotel_notify/result').'?id='.$this->inter_id);
		}else{
			//查出当前酒店内部id下的所有酒店信息
			$data['hotels'] = $this->hotel_notify_model->get_all_hotels($this->inter_id);
			//压入全部酒店选项
			array_unshift($data['hotels'], array('hotel_id'=>0,'inter_id'=>$this->inter_id,'name'=>'全部酒店'));
			$this->display('hotel_notify/register',$data);
		}
	}

	//异步注册请求
	public function toRegister(){
		$this->load->model('hotel/hotel_notify_model');
		if($errmsg = $this->hotel_notify_model->save()){
			echo json_encode($errmsg);
		}else{
			echo json_encode($errmsg);
		}
	}


	function result(){
		$this->load->model ( 'hotel/hotel_notify_model' );
		$reg_info = $this->hotel_notify_model->get_config ( $this->openid, $this->inter_id );
		if(!$reg_info){
			redirect(site_url('hotel_notify/hotel_notify/register').'?id='.$this->inter_id);
		}else{
			$datas ['status'] = $reg_info['status']==1?'complete':'unbind';
			if($reg_info['hotel_id']==0){
				$datas ['hotelname'] = '全部酒店';
			}else{
				$this->db->where(array('inter_id'=>$this->inter_id,'hotel_id'=>$reg_info['hotel_id']));
				$hotels = $this->db->get('hotels')->result_array();
				$datas ['hotelname'] = !empty($hotels[0])?$hotels[0]['name']:'';
			}
			$this->display('hotel_notify/register',$datas);
		}
	}

	public function deal_order(){
        $orderid = $this->input->get ( 'oid', TRUE );
		$data['title'] = '新订单处理';
        if(empty($orderid)){
			$data['info'] = '订单不存在';
			$this->display('hotel_notify/fail',$data);
			return;
		}
		//查询指定订单
		$this->load->model ( 'hotel/Order_model' );
		$idents = array(
				'idetail' => array (
						'r' 
				),
				'orderid'=>$orderid
			);
		$order = $this->Order_model->get_main_order($this->inter_id,$idents);
		if(!isset($order[0])){
			$data['info'] = '订单不存在';
			$this->display('hotel_notify/fail',$data);
			return;
		}
		$order[0] ['real_price'] = 0;
		foreach ( $order[0] ['order_details'] as $ok => $od ) {
			$order[0] ['real_price'] += $od ['iprice'];
		}
		$data['order'] = $order[0];
		//权限验证
		$conf = $this->check_auth($data['order'],$this->openid);
		if(!$conf){
			$data['info'] = '权限不足';
			$this->display('hotel_notify/fail',$data);
			return;
		}
		$data['status_des'] = array(
				0=>"待确认",
				1=>"已确认",
				2=>"已确认",
				3=>"已确认",
				4=>"已取消",
				5=>"已取消"
			);
		if($data['order']['status'] != 0){//已处理
			$this->load->model('hotel/Hotel_log_model');
			$record = $this->Hotel_log_model->get_admin_log($this->inter_id,array('ident' => 'Order/items#'.$data['order']['first_detail']['id'],'log_type'=>array('save_1','save_4','save_5'),'order_by'=>'log_id asc'));
			if(!empty($record[0])){
				if($data['order']['status'] == 4){//用户取消
					$data['tips'] = '客人于'.$record[0]['record_time'].'取消了订单';
				}else{
					$this->load->model('hotel/hotel_notify_model');
					$admin_info = $this->hotel_notify_model->get_config($record[0]['admin_name'],$this->inter_id);
					if(!empty($admin_info['name'])){
						$data['tips'] = $admin_info['name'].'于'.$record[0]['record_time'].str_replace('已','',$data['status_des'][$data['order']['status']]).'了订单';
					}
				}
			}
		}
		//查询未确认订单
		$idents = array(
				'idetail' => array (
						'r' 
				),
				'status'=>0,
				'order_by'=>'o.id desc'
			);
		if($conf['hotel_id']!=0){
			$idents['hotel_id'] = $conf['hotel_id'];
		}
		$data['order_list'] = $this->Order_model->get_order_list($this->inter_id, $idents);
		foreach ($data['order_list'] as $k => $od) {
			if($od['orderid'] == $data['order']['orderid']){
				unset($data['order_list'][$k]);
				continue;
			}
			$data['order_list'][$k]['real_price'] = 0;
			foreach ( $od ['order_details'] as $ok => $od ) {
				$data['order_list'][$k]['real_price'] += $od ['iprice'];
			}
		}
		$this->load->model ( 'pay/Pay_model' );

		$data['pay_ways'] = $this->Pay_model->get_pay_way ( array (
				'inter_id' => $this->inter_id,
				'module' => 'hotel',
				'key' => 'value' 
		) );
		$data['pay_ways'] ['bonus'] = new stdClass ();
		$data['pay_ways'] ['bonus']->pay_name = '积分支付';

		$this->load->model ( 'common/Enum_model' );
		$data['pay_des'] = $this->Enum_model->get_enum_des ( array (
				'HOTEL_ORDER_PAY_STATUS' 
		) );
		$this->display('hotel_notify/deal_order',$data);
	}

	public function update_order_status() {
		$this->load->model ( 'hotel/Order_model' );
		$orderid = $this->input->get ( 'oid' ,TRUE );
		$status = intval ( $this->input->get ( 'status' ,TRUE ) );
		$allow_status = array(1,5);
		if(!in_array($status,$allow_status)){
			echo json_encode(array('status'=>1,'msg'=>'非法操作'));
			exit;
		}
		$idents = array(
				'orderid'=>$orderid
			);
		$order = $this->Order_model->get_main_order($this->inter_id,$idents);
		if(!isset($order[0])){
			echo json_encode(array('status'=>1,'msg'=>'订单不存在'));
			exit;
		}
		$order = $order[0];
		//权限验证
		$conf = $this->check_auth($order,$this->openid);
		if(!$conf){
			echo json_encode(array('status'=>1,'msg'=>'权限不足'));
			exit;
		}
		if ($status==1 && $mt_orderid = $this->input->get('mt_orderid',true)){
		    $this->db->where ( array (
		            'orderid' => $orderid,
		            'inter_id' => $this->inter_id
		    ) );
		    $this->db->update ( 'hotel_orders', array (
		            'mt_pms_orderid' => $mt_orderid
		    ) );
		}
		$date = date('Y-m-d H:i:s');
		if ($this->Order_model->update_order_status ( $this->inter_id, $orderid, $status )) {
			if($status==1){
				$msg = '确认成功';
				$tips = $conf['name'].'于'.$date.'确认了订单';
			}else{
				$msg = '取消成功';
				$tips = $conf['name'].'于'.$date.'取消了订单';
			}
			$return = array('status'=>0,'msg'=>$msg);
			$record = intval ( $this->input->get ( 'record' ,TRUE ) );
			if($record == 1) $return['tips'] = $tips;
			echo json_encode($return);

		} else
			echo json_encode(array('status'=>1,'msg'=>'修改失败,稍后重试'));
	}

	private function check_auth($order,$openid){
		if(empty($order['hotel_id'])){
			return false;
		}
		$this->load->model('hotel/hotel_notify_model');
		$conf = $this->hotel_notify_model->get_config($openid,$this->inter_id,1);
		$wx_notifys = explode(',',$conf['wx_notify']);
		if( $conf['hotel_id']!=0 && $conf['hotel_id']!=$order['hotel_id'] ){
			return false;
		}

		if( !in_array('all',$wx_notifys) && !in_array('new_deal',$wx_notifys) ){
			return false;
		}

		return $conf;
	}
}