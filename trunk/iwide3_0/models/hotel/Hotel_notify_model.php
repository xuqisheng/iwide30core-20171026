<?php
class Hotel_notify_model extends CI_Model {

	const TAB_H = 'hotels';
	const TAB_HO = 'hotel_orders';
	const TAB_HNC = 'hotels_notify_config';
	const TAB_HNR = 'hotels_notify_reg';
	const TAB_HNQ = 'hotels_notify_queue';
	function __construct() {
		parent::__construct ();
	}

	/*
	* 获取当前内部id的所有酒店分店
	* @param1 $inter_id 内部酒店id
	* return Array 所有分店信息
	*/
	function get_all_hotels($inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$sql = "SELECT hotel_id,inter_id,`name`,`status` FROM ".$this->db->dbprefix ( self::TAB_H )." WHERE inter_id='$inter_id' AND `status` IN(1,2) ORDER BY `status` ASC";
		$res = $db_read->query($sql)->result_array();
		foreach ($res as $key => $value) {
			if($value['status']==2){
				$res[$key]['name'] .= '(下线)';
				unset($res[$key]['status']);
			}
		}
		return $res;
	}
	/*
	* 查询当前访问的用户是否已经绑定酒店
	*/
	function get_config($openid,$inter_id,$status = NULL){
		$db_read = $this->load->database('iwide_r1',true);
		$where['openid']   = $openid;
		$where['inter_id'] = $inter_id;
		if(!empty($status)){
			$where['status'] = $status;
		}
		$db_read->where($where);
		$db_read->limit(1);
		$res = $db_read->get(self::TAB_HNR)->row_array();
		return !empty($res)?$res:false;
	}

	/*
	* 登记信息入库
	*/
	function save(){
		$db_read = $this->load->database('iwide_r1',true);
		$openid = $this->session->userdata($this->session->userdata('inter_id').'openid');
		if($openid){
			$datas['openid']    = $openid;
			$datas['name']      = $this->input->get('name');
			$datas['hotel_id'] = $this->input->get('hotel_id');
			$datas['inter_id']  = $this->session->userdata('inter_id');
			$datas['addtime'] = $datas['uptime'] = time();
			if($this->input->post('key')){
				
			}else{
				$db_read->where(array('openid'=>$openid,'inter_id'=>$this->session->userdata('inter_id')));
				$db_read->limit(1);
				$query = $db_read->get(self::TAB_HNR);
				$datas['status']    = 2;
				if($query->num_rows() > 0){
					return array('errmsg'=>'用户已登记过');
				}
				if($this->db->insert(self::TAB_HNR,$datas) > 0){
					return array('errmsg'=>'ok');
				}else{
					return array('errmsg'=>'登记失败');
				}
			}
		}else{
			return array('errmsg'=>'授权错误');	
		}
	}

	//后台消息提醒内容设置
	function notify_check_config(){
		return array(
			'all'=>array(
				'name'=>'single[all]',
				'text'=>'提醒所有',
				'label'=>'checkall'
				),
			1 => array(
				'name'=>'single[new]',
				'text'=>'新订单(仅提醒)',
				'label'=>'single'
				),
			'new_deal' => array(
				'name'=>'single[new_deal]',
				'text'=>'新订单处理',
				'label'=>'single'
				),
			4 => array(
				'name'=>'single[ucancel]',
				'text'=>'用户取消',
				'label'=>'single'
				),
			11 => array(
				'name'=>'single[scancel]',
				'text'=>'系统取消',
				'label'=>'single'
				),
			'checkout' => array(
				'name'=>'single[checkout]',
				'text'=>'预约退房提醒',
				'label'=>'single'
				),
			'down' => array(
				'name'=>'single[down]',
				'text'=>'比价结果通知',
				'label'=>'single'
				),
			'change' => array(
				'name'=>'single[change]',
				'text'=>'调价完成提醒',
				'label'=>'single'
				),
			16 => array(
				'name'=>'single[soma_order]',
				'text'=>'商城新订单',
				'label'=>'single'
				),
			);
	}
	public function weeks_config(){
		return array(
			1=>'一',
			2=>'二',
			3=>'三',
			4=>'四',
			5=>'五',
			6=>'六',
			7=>'日',
			);
	}

	//后台消息提醒默认配置
	function notify_default_config(){
		return array(
			'is_popup'=>1,//弹窗开启
			'is_voice'=>1,//声音开启
			'is_weixin'=>1,//微信开启
			'wx_notify'=>'1',//新订单

			);
	}

	//获取当前酒店的消息提醒配置
	function get_admin_notify_config($inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('inter_id'=>$inter_id,));
		$res = $db_read->get(self::TAB_HNC)->result_array();
		//如果没有配置，则调默认配置
		if(!empty($res[0])){
			$res = $res[0];
		}else{
			$res = $this->notify_default_config();
		}
		return $res;
	}

	//保存酒店消息提醒设置
	function save_hotels_notify($data){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('inter_id'=>$this->inter_id));
		$is_conf = $db_read->get(self::TAB_HNC)->result_array();
		if(!empty($is_conf)){
			$data['uptime'] = time();
			$this->db->where(array('inter_id'=>$this->inter_id));
			return $this->db->update(self::TAB_HNC,$data);
		}
		$data['addtime'] = $data['uptime'] = time();
		return $this->db->insert(self::TAB_HNC,$data);
	}

	//获取当前酒店下的所有消息提醒成员信息
	function get_hotels_reg($inter_id='',$condits=array(),$handle=false){
		$db_read = $this->load->database('iwide_r1',true);
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		$where = '';
		if(!empty($condits['hotel_ids'])){
			$where .= ' AND a.hotel_id IN('.implode(',',$condits['hotel_ids']).')';
		}
		$sql = "SELECT a.*,b.`name` hname FROM ".$this->db->dbprefix (self::TAB_HNR)." a LEFT JOIN ".$this->db->dbprefix (self::TAB_H)." b ON a.inter_id=b.inter_id AND a.hotel_id=b.hotel_id WHERE a.inter_id='".$inter_id."'".$where." GROUP BY a.id ORDER BY a.id";
		$res = $db_read->query($sql);
		$regs = $res->result_array();
		if($handle==true){
			return $regs;
		}
		$check_config = $this->notify_check_config();
		if(!empty($regs)){
			foreach ($regs as $rk => $rv) {
				if($rv['hotel_id']==0){
					$regs[$rk]['hname'] = '全部酒店';
				}
				$wx_notifys = explode(',',$rv['wx_notify']);
				$str = '';
				foreach ($wx_notifys as $wk => $wv) {
					if(isset($check_config[$wv])){
						$str .= $check_config[$wv]['text'].' ';
					}
				}
				$regs[$rk]['wx_notify'] = rtrim($str,' ');
				$weeks = explode(',',$rv['weeks']);
				$str = '';
				$weekss = $this->weeks_config();
				foreach ($weeks as $wk => $wv) {
					if(isset($weekss[$wv])){
						$str .= $weekss[$wv].' ';
					}
				}
				$regs[$rk]['weeks'] = rtrim($str,' ');
			}
			return $regs;
		}
	}

	//修改消息提醒人员审核状态
	function save_reg_permit($per,$rid){
		if(!empty($rid)&&$per>0){
			if($rid=='all'){
				if($per==1){
					$status = 2;
				}else{
					$status = 1;
				}
				$this->db->where(array('inter_id'=>$this->inter_id,'status'=>$status));
			}else{
				$this->db->where(array('inter_id'=>$this->inter_id));
				$this->db->where_in('id',explode(',', $rid));
			}
			return $this->db->update(self::TAB_HNR,array('status'=>$per,'uptime'=>time()));
		}
		return false;
	}

	//修改消息提醒人员绑定酒店所属
	function edit_hotel($hid,$rid){
		if($rid){
			$this->db->where(array('inter_id'=>$this->inter_id,'id'=>$rid));
			return $this->db->update(self::TAB_HNR,array('hotel_id'=>$hid,'uptime'=>time()));
		}
		return false;
	}

	// 查询登记人员信息 By Id
	function get_reg_info($id){
		$db_read = $this->load->database('iwide_r1',true);
		if($id>0){
			$db_read->where('id',$id);
			return $db_read->get(self::TAB_HNR)->row_array();
		}
		return false;
	}

	// 更新登记人员信息
	function edit_regs($data){
		if(!empty($data)){
			$this->db->where(array(
				'id' => $data['id'],
				));
			$data['uptime'] = time();
			return $this->db->update(self::TAB_HNR,$data);
		}
		return false;
	}

	// 校验用户身份和权限
	function check_reg($reg,$type){
		if($reg['status']!=1){
			return false;
		}
		$reg_arr = explode(',',$reg['wx_notify']);
		if(in_array('all',$reg_arr)||in_array($type,$reg_arr) || ($type==1 && in_array('new_deal',$reg_arr)) ){
			$week = date("w")=='0'?7:date("w");
			$week_arr = explode(',',$reg['weeks']);
			if(in_array($week,$week_arr)){
				return true;
			}
		}
		return false;
	}

	// 将模板消息数据写入队列表
	function insert_wxmsg_queue($inter_id,$hotel_id,$module,$type,$data){
		MYLOG::w(__METHOD__.':'.json_encode(func_get_args()),'hotel_notify');
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array(
			'inter_id'=>$inter_id,
			'status'=>1,
			));
		$db_read->where_in('hotel_id',array(0,$hotel_id));
		$regs = $db_read->get(self::TAB_HNR)->result_array();
		//存在该酒店审核通过的人员才写入队列
		if(!empty($regs)){
			$info = array(
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'module' => $module,
				'orderid' => '',
				'create_time' => time(),
	    		'locked' => 2,//1.锁定，2.开放
	    		'flag' => 2,//1.已处理，2.未处理
	    		'wx_type' => $type,
	    		'update_time' => time(),
	    		'oper_times' => 0,
	    		'out_time' => 0,
				'tmp_type' => '',
	    		'order_data' => $data,
	    		'type' =>1,//微信提醒
				);
			return $this->db->insert(self::TAB_HNQ,$info);
		}
		return false;
	}
}