<?php
/*
 * 比价系统
 * Date 2016-10-30
 * author chenjunyu
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Paritys_model extends MY_Model {
	private $roomkeys = array(
		'first'=>array('高级','豪华','行政','总统','商务',),
		'refirst'=>array('皇室','蜜月','家庭','精品','情侣','亲子','童趣','观景','休闲','温泉','主题','婚庆','棋牌','特惠','麻将','花园','标准','海景','园景','江','湖景','山景','雅景','高尔夫景','无障碍','雅致','和式','贵宾','平江','天香','世家','女宾','特价','好莱坞','庭院','景观','特色','情趣','热销','府邸','小镇','天井小院','时尚','休闲','数码','迷你','圆床','中式','日式','风情','风雅','精选','精致','都会','顶级','普通','温馨','概念','欧式','水床','娱乐','生日','假日','钟点','零压','阳光','浴缸','现代','简约','街','精英','迷幻','日租','经济','白银时代','流金岁月','书香门第',),
		'second'=>array('单床','大床','双床','多床','三床','四床',),
		'third'=>array('无早','单早','双早','三早','四早','五早','六早','七早','八早','九早','十早',),
		'fourth'=>array('套','别墅','一卧','二卧','三卧','四卧','五卧','六卧','七卧',),
		);
	private $timerooms = array('钟点','时租','半日',);
	private $prior = array('总统',);//如果房型属性包含当中一种，则直接判断为匹配对
	private $special = array('高级商务'=>'商务','行政商务'=>'行政','豪华行政'=>'行政','豪华商务'=>'豪华','高级商务'=>'高级','豪华商务'=>'商务',);//一些特殊的房型名，用于特别匹配，'房型名'=>'可匹配的房型名'
	private $ignore = array('总统','别墅',);//如果房型名完全相同又都包含这些属性，则直接匹配
	private $htmldir = '/data/parity/';
	private $ctriprooms = array();
	private $norefirst = array();
	private $currcname = '';
	private $curriname = '';
	private $date = '';
	private $operate = '';
	const TAB_PTH = 'price_third_hotels';//酒店匹配表
	const TAB_PWR = 'price_weixin_rooms';//微信房型信息表
	const TAB_PRP = 'price_room_parity';//房型匹配表
	const TAB_PTR = 'price_third_rooms';//第三方酒店房型信息表
	const TAB_PHC = 'price_hotels_count';//酒店比价统计结果表
	const TAB_CA = 'core_admin';//后台管理员用户表
	const TAB_PRPM = 'price_room_parity_manual';//手动匹配房型结果表
	const TAB_H = 'hotels';//酒店表
	const TAB_HR = 'hotel_rooms';//房型表
	const TAB_HPI = 'hotel_price_info';//价格代码表
	const TAB_PSC = 'price_smart_config';//智能调价配置表
	const TAB_PSCL = 'price_smart_config_list';//智能调价配置详细表
	const TAB_PSCG = 'price_smart_config_log';//智能调价配置操作记录表
	const TAB_PSAL = 'price_smart_adjust_log';//调价通知人员操作记录追踪表
	const TAB_HRS = 'hotel_room_state';//酒店房态表
	const TAB_PEPL = 'price_edit_price_log';//智能定价价格修改记录表
	const TAB_P = 'publics';//公众号表
	public function __construct(){
		parent::__construct();
		$this->load->library('MYLOG');
		$this->price_db = $this->load->database('price',TRUE);//生产
		// $this->price_db = $this->db;//测试
		$this->date = $this->getNewDate();//生产
		// $this->date = '2016-12-26';//测试
	}

	//检查用户登录账号密码
 	function checkSignIn($username,$password){
 		if(empty($username)||empty($password)){
 			return false;
 		}
 		$this->db->where(array(
 			'username'=>addslashes($username),
 			));
 		$userinfo = $this->db->get(self::TAB_CA)->row_array();
 		if(empty($userinfo)){
 			return false;
 		}
 		$password = do_hash($password);
 		if($password!=$userinfo['password']){
 			return false;
 		}
 		//写入session
 		$this->session->set_userdata(array('userinfo'=>
 			array(
 			'admin_id'=> $userinfo['admin_id'],
	        'inter_id'=> $userinfo['inter_id'],
	        'hotel_ids'=> $userinfo['entity_id'],
	        'username'=> $userinfo['username'],
	        'nickname'=> $userinfo['nickname'],
	        'head_pic'=> $userinfo['head_pic'],
	        'update_time'=> $userinfo['update_time'],
 			)));
 		return true;
 	}

	/*
	 * 获取全部可用酒店信息
	 */
	public function getAllHotels($hotel_ids='',$inter_id=''){
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		$this->db->where(array(
			'inter_id'=>$inter_id,
			'status'=>1,
			));
		if(!empty($hotel_ids)){
			$this->db->where_in(
				'hotel_id', explode(',',$hotel_ids)
				);
		}
		$fields = 'hotel_id,inter_id,name';
		return $this->db->select($fields)->get(self::TAB_H)->result_array();
	}

	/*
	 * 获取酒店最近更新时间
	 */
	public function getUptime($hotel_id,$inter_id=''){
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		if(!empty($hotel_id)){
			$this->price_db->where(array(
				'hotel_id' => $hotel_id
				));
		}
		$this->price_db->where(array(
			'inter_id' => $inter_id,
			))->order_by('addtime DESC,batch DESC');
		$result = $this->price_db->get(self::TAB_PHC)->row_array();
		return $result;
	}

	// 默认配置
	public function defaultConf(){
		return array(
			'rooms_list'=>array(),
			'configs'=>array(),
			'configs_list'=>array(),
			);
	}

	/*
	 * 获取智能调价配置信息
	 */
	public function getSmartConfig($hotel_id,$inter_id=''){
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		$result = array();
		// 查询房型智能调价配置的详细
		$this->db->where(array(
			'inter_id' =>$inter_id,
			'hotel_id' =>$hotel_id,
			'room_id>' =>0,
			'price_code>' =>0,
			));
		$result['rooms_list'] = $this->db->get(self::TAB_PSCL)->result_array();
		foreach($result['rooms_list'] as $k=>$res){
			$result['rooms_list'][$k]['price_section'] = json_decode($res['price_section'],true);
		}
		//查询基本配置
		$this->db->where(array(
			'inter_id' =>$inter_id,
			'hotel_id' =>$hotel_id,
			));
		$result['configs'] = $this->db->get(self::TAB_PSC)->row_array();
		if(!empty($result['configs'])){
			$result['configs']['exec_date'] = !empty($result['configs']['exec_date'])?json_decode($result['configs']['exec_date'],true):'';
		}
		if(empty($result['configs'])){
			// 调用集团统一配置
			$this->db->where(array(
				'inter_id' =>$inter_id,
				'hotel_id' =>0,
				));
			$result['configs'] = $this->db->get(self::TAB_PSC)->row_array();
			if(empty($result['configs'])){
				//调用默认配置
				return $this->defaultConf();
			}
			$result['configs']['exec_date'] = !empty($result['configs']['exec_date'])?json_decode($result['configs']['exec_date'],true):'';
			$this->db->where(array(
				'inter_id' =>$inter_id,
				'hotel_id' =>0,
				'room_id' =>0,
				'price_code' =>0,
				));
			$result['configs_list'] = $this->db->get(self::TAB_PSCL)->row_array();
			if(!empty($result['configs_list'])){
				$result['configs_list']['price_section'] = json_decode($result['configs_list']['price_section'],true);
			}
			return $result;
		}
		//查询统一配置的详细
		$this->db->where(array(
			'inter_id' =>$inter_id,
			'hotel_id' =>$hotel_id,
			'room_id' =>0,
			'price_code' =>0,
			));
		$result['configs_list'] = $this->db->get(self::TAB_PSCL)->row_array();
		if(!empty($result['configs_list'])){
			$result['configs_list']['price_section'] = json_decode($result['configs_list']['price_section'],true);
		}
		return $result;
	}

	/*
	 * 获取有设置智能定价规则的酒店
	 */
	public function getUseSmartRule($inter_id){
		$sql = "SELECT a.hotel_id FROM ".$this->db->dbprefix(self::TAB_PSCL)." a LEFT JOIN ".$this->db->dbprefix(self::TAB_PSC)." b ON a.inter_id=b.inter_id AND a.hotel_id=b.hotel_id AND a.conf_type=b.conf_type WHERE a.inter_id='".$inter_id."' GROUP BY a.hotel_id";
		$list = $this->db->query($sql)->result_array();
		$hotel_ids = array();
		foreach($list as $v){
			$hotel_ids[] = $v['hotel_id'];
		}
		return $hotel_ids;
	}

	/*
	 * 操作智能调价配置
	 */
	public function editSmartConfig($data,$inter_id=''){
		if(empty($data['inter_id'])){
			$data['inter_id'] = $this->inter_id;
		}
		$this->db->where(array(
			'inter_id' => $data['inter_id'],
			));
		$where['inter_id'] = $data['inter_id'];
		if(isset($data['hotel_id'])){
			$this->db->where(array(
				'hotel_id' => $data['hotel_id'],
				));
			$where['hotel_id'] = $data['hotel_id'];
		}
		$res = $this->db->get(self::TAB_PSC)->row_array();
		if($res){
			$this->operate = 'edit';
			$this->db->where($where);
			unset($data['addtime']);
			return $this->db->update(self::TAB_PSC,$data);
		}
		$this->operate = 'add';
		return $this->db->insert(self::TAB_PSC,$data);
	}

	/*
	 * 操作智能调价配置详细
	 */
	public function editSmartConfigList($data,$inter_id=''){
		if(empty($data['inter_id'])){
			$data['inter_id'] = $this->inter_id;
		}
		$this->db->where(array(
			'inter_id' => $data['inter_id'],
			));
		$where['inter_id'] = $data['inter_id'];
		if(isset($data['hotel_id'])){
			$this->db->where(array(
				'hotel_id' => $data['hotel_id'],
				));
			$where['hotel_id'] = $data['hotel_id'];
		}
		if(!empty($data['room_id'])){
			$this->db->where(array(
				'room_id' => $data['room_id'],
				));
			$where['room_id'] = $data['room_id'];
		}
		if(!empty($data['price_code'])){
			$this->db->where(array(
				'price_code' => $data['price_code'],
				));
			$where['price_code'] = $data['price_code'];
		}
		if(!empty($data['conf_type'])){
			$this->db->where(array(
				'conf_type' => $data['conf_type'],
				));
			$where['conf_type'] = $data['conf_type'];
		}
		$res = $this->db->get(self::TAB_PSCL)->row_array();
		if($res){
			$this->operate = 'edit';
			$this->db->where($where);
			unset($data['addtime']);
			return $this->db->update(self::TAB_PSCL,$data);
		}
		$this->operate = 'add';
		return $this->db->insert(self::TAB_PSCL,$data);
	}

	//房型配置保存
	public function editRoomConfig($lists){
		if(!empty($lists)){
			$this->db->trans_begin();
			foreach ($lists as $k => $data) {
				$res = $this->editSmartConfigList($data);
				if(!$res){
					$this->db->trans_rollback ();
					return $res;
				}
				if(!empty($data)){
					if($data['range_type']==1){
						$data['wx_fix'] = json_decode($data['price_section'],true);
					}elseif ($data['range_type']==2) {
						$data['wx_per'] = json_decode($data['price_section'],true);
					}
					if($data['range_type']==1){
						$data['wx_fix'] = json_decode($data['price_section'],true);
					}elseif ($data['range_type']==2) {
						$data['wx_per'] = json_decode($data['price_section'],true);
					}
					if($data['compare_type']==1){
						$data['ch_fix'] = $data['low_section'];
					}elseif($data['compare_type']==2){
						$data['ch_per'] = $data['low_section'];
					}
				}
				$ress = $this->saveSmartLog($data);
				if(!$ress){
					$this->db->trans_rollback ();
					return $ress;
				}
			}
			if ($this->db->trans_status () === FALSE) {
				$this->db->trans_rollback ();
				return false;
			}else{
				$this->db->trans_commit ();
				return true;
			}
		}
		return 'null';
	}

	//统一配置保存
	public function editConfig($data1,$data2=array()){
		$this->db->trans_begin();
		$res1 = $this->editSmartConfig($data1);
		if(!$res1){
			$this->db->trans_rollback();
			return 1;
		}
		if(!empty($data2)){
			$res2 = $this->editSmartConfigList($data2);
			if(!$res2){
				$this->db->trans_rollback ();
				return $res2;
			}
		}
		$data1['exec_date'] = json_decode($data1['exec_date'],true);
		if(!empty($data2)){
			if($data2['range_type']==1){
				$data2['wx_fix'] = json_decode($data2['price_section'],true);
			}elseif ($data2['range_type']==2) {
				$data2['wx_per'] = json_decode($data2['price_section'],true);
			}
			if($data2['range_type']==1){
				$data2['wx_fix'] = json_decode($data2['price_section'],true);
			}elseif ($data2['range_type']==2) {
				$data2['wx_per'] = json_decode($data2['price_section'],true);
			}
			if($data2['compare_type']==1){
				$data2['ch_fix'] = $data2['low_section'];
			}elseif($data2['compare_type']==2){
				$data2['ch_per'] = $data2['low_section'];
			}
		}
		$data = array_merge($data1,$data2);
		$res = $this->saveSmartLog($data);
		if ($this->db->trans_status () === FALSE) {
			$this->db->trans_rollback ();
			return false;
		}else{
			$this->db->trans_commit ();
			return true;
		}
	}

	//智能调价操作记录翻译
	public function logDesc($data){
		$str = '';
		foreach($data as $k=>$v){
			switch ($k) {
				case 'operate':
					if($v=='add'){
						$str .= '创建规则（';
					}elseif($v=='edit'){
						$str .= '修改规则（';
					}
					break;
				case 'conf_type':
					if($v==1){
						$str .= '配置类型：统一配置，';
					}elseif($v==2){
						$str .= '配置类型：分别配置，';
					}else{
						$str .= '配置类型：空，';
					}
					break;
				case 'exec_date':
					if(!empty($v)){
						$str .= '执行日期：'.$v[0].'~'.$v[1].'，';
					}else{
						$str .= '执行日期：空，';
					}
					break;
				case 'effect_time':
					$str .= '有效时长：'.$v.'分钟，';
					break;
				case 'adjust_type':
					if($v==1){
						$str .= '调整方式：确认后调价，';
					}elseif ($v==2) {
						$str .= '调整方式：直接调价，';
					}else{
						$str .= '调整方式：空，';
					}
					break;
				case 'hotel_name':
					if($v===0){
						$str .= '全部酒店-';
					}else{
						$str .= $v.'-';
					}
					break;
				case 'room_name':
					$str .= $v;
					break;
				case 'price_name':
					$str .= $v;
					break;
				case 'wx_fix':
					$str .= '固定最低'.$v['min'].'元，固定最高'.$v['max'].'元，';
					break;
				case 'wx_per':
					$str .= '不低于微信价'.$v['min'].'%，不高于微信价'.$v['max'].'%，';
					break;
				case 'ch_fix':
					$str .= '比携程价低'.$v.'元，';
					break;
				case 'ch_per':
					$str .= '是携程价的'.$v.'%';
					break;
				default:
					# code...
					break;
			}
		}
		return $str.'）';
	}

	/*
	 * 智能调价配置操作日志记录
	 */
	public function saveSmartLog($data){
		$row = array();
		$userinfo = $this->session->get_admin_profile();
		$row['addtime'] = date('Y-m-d H:i:s');
		$inter_id = !empty($data['inter_id'])?$data['inter_id']:$this->inter_id;
		$row['inter_id'] = $inter_id;
		$row['hotel_id'] = $data['hotel_id'];
		$row['admin_id'] = $userinfo['admin_id'];
		$row['username'] = $userinfo['username'];
		$row['nickname'] = $userinfo['nickname'];
		$this->load->helper('common');
		$row['ip'] = getIp();
		$row['operate_info'] = $data;
		$_data = array();
		$_data['operate'] = $this->operate;
		if(!empty($data['conf_type'])){
			$_data['conf_type'] = $data['conf_type'];
		}
		if(!empty($data['exec_date'])){
			$_data['exec_date'] = $data['exec_date'];
		}
		if(isset($data['effect_time'])){
			$_data['effect_time'] = $data['effect_time'];
		}
		if(!empty($data['adjust_type'])){
			$_data['adjust_type'] = $data['adjust_type'];
		}
		if($data['hotel_id']>0){
			$this->db->where(array(
				'inter_id'=>$inter_id,
				'hotel_id'=>$data['hotel_id'],
				));
			$this->db->select('name');
			$hotel_info = $this->db->get(self::TAB_H)->row_array();
			$_data['hotel_name'] = $hotel_info['name'];
		}else{
			$_data['hotel_name'] = $data['hotel_id'];
		}
		if(!empty($data['room_id'])){
			$this->db->where(array(
				'inter_id'=>$inter_id,
				'hotel_id'=>$data['hotel_id'],
				'room_id'=>$data['room_id'],
				));
			$this->db->select('name');
			$hotel_info = $this->db->get(self::TAB_HR)->row_array();
			$_data['room_name'] = $hotel_info['name'];
			if(!empty($data['price_code'])){
				$this->db->where(array(
					'inter_id'=>$inter_id,
					'price_code'=>$data['price_code'],
					));
				$this->db->select('price_name');
				$hotel_info = $this->db->get(self::TAB_HPI)->row_array();
				$_data['price_name'] = $hotel_info['price_name'];
			}
		}
		if(!empty($data['wx_fix'])){
			$_data['wx_fix'] = $data['wx_fix'];
		}
		if(!empty($data['wx_per'])){
			$_data['wx_per'] = $data['wx_per'];
		}
		if(!empty($data['ch_fix'])){
			$_data['ch_fix'] = $data['ch_fix'];
		}
		if(!empty($data['ch_per'])){
			$_data['ch_per'] = $data['ch_per'];
		}
		$row['operate_info'] = json_encode($data);
		$row['operate_desc'] = $this->logDesc($_data);
		$res = $this->db->insert(self::TAB_PSCG,$row);
		if($res){
			return true;
		}
		return false;
	}

	/*
	 * 获取智能调价配置操作记录
	 */
	public function getSmartLogs($inter_id='',$count=false,$offset=0,$perpage=5){
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		$this->db->where(array(
			'inter_id'=>$inter_id,
			))->order_by('addtime','DESC');
		if($count==true){
			$res = $this->db->select('count(*) as count')->get(self::TAB_PSCG)->row_array();
			return !empty($res['count'])?$res['count']:0;
		}
		$this->db->limit($perpage,$offset);
		$res = $this->db->get(self::TAB_PSCG)->result_array();
		foreach ($res as $k=>$val) {
			$res[$k]['operate_type'] = '后台操作';
		}
		return $res;
	}

	public function saveAdjustLog($inter_id,$hotel_id,$openid,$date,$batch,$optype,$opresult=1,$info=array()){
		!empty($inter_id)?$inter_id:$this->inter_id;
		$condits = array(
			'inter_id'=>$inter_id,
			'hotel_id'=>$hotel_id,
			'day'=>$date,
			'batch'=>$batch,
			'operate_type'=>$optype,
			'operate_result'=>$opresult,
			);
		if($optype=='see'||$optype=='send'){
			$condits['openid'] = $openid;
		}
		$this->db->where($condits);
		$res = $this->db->get(self::TAB_PSAL)->row_array();
		if($res){
			// 已查看
			return 'al';
		}
		$data = array(
			'inter_id'=>$inter_id,
			'hotel_id'=>$hotel_id,
			'openid'=>$openid,
			'day'=>$date,
			'batch'=>$batch,
			'operate_time'=>date('Y-m-d H:i:s'),
			'operate_type'=>$optype,
			'operate_result'=>$opresult,
			'operate_info'=>json_encode($info),
			);
		$res = $this->db->insert(self::TAB_PSAL,$data);
		return $res;
	}

	/*
	 * 获取酒店比价
	 */
	public function getRooms($hotel_id,$third_type='ctrip',$inter_id='',$batch=0){
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		$batchstr = '';
		if($batch>0){
			$batchstr = ' AND a.batch='.$batch;
		}else{
			$maxbatch = $this->getMaxBatch($inter_id,$hotel_id,self::TAB_PRP);
			$batchstr = ' AND a.batch='.$maxbatch;
		}
		$sql = "SELECT a.*,b.price ctrip_price,b.room_name ctrip_name,b.bed,b.breakfast,c.room_name iwide_name,c.total_price iwide_price,c.price_name,c.breakfast ibreakfast FROM ".$this->price_db->dbprefix(self::TAB_PRP)." a LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PTR)." b ON a.third_room_id=b.id AND a.third_id=b.third_id AND a.batch=b.batch LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PWR)." c ON a.inter_id=c.inter_id AND a.hotel_id=c.hotel_id AND a.room_id=c.room_id AND a.price_code=c.price_code AND a.batch=c.batch WHERE a.inter_id='$inter_id' AND a.hotel_id=$hotel_id AND a.third_type='$third_type' AND a.adddate='".$this->date."' AND c.adddate='".$this->date."'".$batchstr." AND (b.adddate='".$this->date."' OR a.third_room_id=0)";
		$list = $this->price_db->query($sql)->result_array();
		$result = array();
		$ctrip_prices = array();
		foreach ($list as $k => $val) {
			if(!empty($val['third_room_id'])){
				$result[$val['room_id']]['room'][$val['price_code']] = $val;
				$ctrip_prices[$val['room_id'].'_'.$val['price_code']][] = $val['ctrip_price'];
			}else{
				$result[$val['room_id']]['no_rooms'][] = $val;
			}
		}
		foreach ($result as $kr => $vr) {
			if(!empty($vr['room'])){
				foreach ($vr['room'] as $k => $v) {
					$result[$kr]['room'][$v['price_code']]['ctrip_prices'] = implode('/',$ctrip_prices[$v['room_id'].'_'.$v['price_code']]);
					$result[$kr]['room'][$v['price_code']]['chajia'] = $v['iwide_price']-$v['ctrip_price'];
					unset($result[$kr]['room'][$v['price_code']]['third_id']);
					unset($result[$kr]['room'][$v['price_code']]['third_room_id']);
					unset($result[$kr]['room'][$v['price_code']]['ctrip_price']);
				}
			}
		}
		return $result;
	}

	//计算调价结果
	public function getResultPrice($rooms,$configs){
		$data = array();
		foreach($rooms as $k=>$room){
			if(!empty($room['room'])){
				foreach($room['room'] as $kr=>$vr){
					if(!empty($configs['configs']['price_codes'])&&$configs['configs']['conf_type']==1&&!in_array($vr['price_code'],explode(',',$configs['configs']['price_codes']))){
						continue;
					}
					$cprices = explode('/',$vr['ctrip_prices']);
					$room['room'][$kr]['ctrip_price'] = min($cprices);
					//生成调价后的价格
					if($configs['configs']['conf_type']==1){
						//统一配置
						$room['room'][$kr]['confs_list'] = $configs['configs_list'];
					}elseif($configs['configs']['conf_type']==2){
						//分别配置
						if(!empty($configs['rooms_list'])){
							foreach($configs['rooms_list'] as $kd=>$vd){
								if($vd['inter_id']==$vr['inter_id']&&$vd['hotel_id']==$vr['hotel_id']&&$vd['room_id']==$vr['room_id']&&$vd['price_code']==$vr['price_code']){
									$room['room'][$kr]['confs_list'] = $vd;
									break;
								}
							}
						}else{
							//如没有配置则使用统一配置
							$room['room'][$kr]['confs_list'] = $configs['configs_list'];
						}
					}
					// 计算调价结果
					if(!empty($room['room'][$kr]['confs_list'])){
						// 价格范围
						if($room['room'][$kr]['confs_list']['range_type']==1){
							//数值
							$min_price = $room['room'][$kr]['confs_list']['price_section']['min'];
							$max_price = $room['room'][$kr]['confs_list']['price_section']['max'];
						}elseif($room['room'][$kr]['confs_list']['range_type']==2){
							//百分比
							$min_price = $vr['iwide_price']*$room['room'][$kr]['confs_list']['price_section']['min']/100;
							$max_price = $vr['iwide_price']*$room['room'][$kr]['confs_list']['price_section']['max']/100;
						}
						//比携程价
						if($room['room'][$kr]['confs_list']['compare_type']==1){
							//数值
							$price_change = bcsub($room['room'][$kr]['ctrip_price'],$room['room'][$kr]['confs_list']['low_section'],2);
						}elseif($room['room'][$kr]['confs_list']['compare_type']==2){
							//百分比
							$price_change = $room['room'][$kr]['ctrip_price']*$room['room'][$kr]['confs_list']['low_section']/100;
						}
						$room['room'][$kr]['iwide_price_change'] = sprintf("%1\$.2f",(ceil(min(max($price_change,$min_price),$max_price))));
						// $data[$k] = $room['room'];
						$data[$k][$kr] = $room['room'][$kr];
					}
				}
			}
		}
		return $data;
	}

	function save_room_price($inter_id, $hotel_id, $room_arr, $day,$batch,$user) {
		// $this->db->trans_begin ();
		$prices_arr = array();
		$this->load->model('hotel/room_status_model');
		foreach ( $room_arr as $room){
			foreach ( $room as $price_code ) {
				$price_arr = array (
						'inter_id' => $inter_id,
						'room_id' => $price_code['room_id'],
						'hotel_id' => $hotel_id,
						'date' => $day,
						'price' => $price_code['iwide_price_change'],
						'oprice' => 0,
						'price_code' => $price_code['price_code'],
						// 'nums' => $nums,
						'channel_code' => 'Weixin',
						'edittime' => time () 
				) ;
				// $this->db->replace ( self::TAB_HRS, $price_arr);
				$day_arr = array();
				$day_arr[] = $day;
				$rres = $this->room_status_model->save_room_price ( $inter_id, $hotel_id, $price_code['room_id'], $price_code['price_code'], $price_code['iwide_price_change'], '-', $day_arr,'parity' );
				// if(!$rres){
				// 	$this->db->trans_rollback ();
				// 	return false;
				// }
				if(!$rres){
					MYLOG::w('智能调价价格修改失败：'.json_encode($price_code),'paritys');
				}
				$price_arr['oprice'] = $price_code['iwide_price'];
				$prices_arr[] = $price_arr;
			}
		}
		// 记录改价日志
		$res = $this->db->insert_batch(self::TAB_PEPL,$prices_arr);
		// if(!$res){
		// 	$this->db->trans_rollback ();
		// 	return false;
		// }
		if(!$res){
			MYLOG::w('记录改价日志失败：'.json_encode($price_arr),'paritys');
		}
		$res = $this->paritys_model->saveAdjustLog($inter_id,$hotel_id,$user,$day,$batch,'confirm',1,$room_arr);
		// $this->db->trans_complete ();
		// if ($this->db->trans_status () === FALSE) {
		// 	$this->db->trans_rollback ();
		// 	return false;
		// } else {
		// 	$this->db->trans_commit ();
		// 	return true;
		// }
		if(!$res){
			MYLOG::w('记录调价确认操作失败：'.$inter_id.'-'.$hotel_id.'-'.$user.'-'.$day.'-'.$batch.'-confirm-1','paritys');
		}
		return true;
	}

	//获取指定酒店智能定价操作记录
	public function getAdjustInfo($inter_id,$hotel_id,$date,$batch,$operate_type){
		$this->db->where(array(
			'inter_id'=>$inter_id,
			'hotel_id'=>$hotel_id,
			'operate_type'=>$operate_type,
			'day'=>$date,
			'batch'=>$batch,
			));
		return $this->db->get(self::TAB_PSAL)->row_array();
	}

	// 获取当前酒店当天剩余可生成比价次数
	public function getParityNum($inter_id=''){
		$this->date = date('Y-m-d');
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		$this->price_db->where(array(
			'a.adddate'=>$this->date,
			'b.inter_id'=> $inter_id,
			))->order_by('batch','DESC');
		$res = $this->price_db->from(self::TAB_PTR.' a')->join(self::TAB_PTH.' b','b.ctrip_id = a.third_id')->select('a.batch')->limit(1)->get()->row_array();
		// return $this->db->last_query();
		return !empty($res)?10-$res['batch']:10;
	}
	

	/*
	 * 获取数据最新更新日期
	 */
	public function getNewDate($addtime=0){
		$field = 'adddate';
		if($addtime==1){
			$field = 'addtime';
		}
		$result = $this->price_db->query("SELECT ".$field." FROM ".$this->price_db->dbprefix(self::TAB_PHC)." ORDER BY adddate DESC LIMIT 1")->row_array();
		return !empty($result[$field])?$result[$field]:($field=='adddate'?date('Y-m-d'):date('Y-m-d 09:00:00'));
	}


	/*
	 * 获取比价数据
	 */
	public function getParitys($inter_id,$third_type,$search_conditions=array(),$count=false,$offset=null,$perpage=null){
		if(empty($inter_id)||empty($third_type)){
			return false;
		}
		$where = " where (ctrip_id is not null or ctrip_id<>'') and grab_flag=1 and inter_id='".$inter_id."'";
		if(!empty($search_conditions['hotel_ids'])){
			$where .= ' and hotel_id in ('.implode(',', $search_conditions['hotel_ids']).')';
		}
		if(!empty($search_conditions['hotel_name'])){
			$where .= " and `name` like '%".$search_conditions['hotel_name']."%'";
		}
		if(!empty($search_conditions['wd'])){
			$where .= " and (`name` like '%".$search_conditions['wd']."%' or city like '%".$search_conditions['wd']."%')";
		}
		if(!is_null($offset)&&!is_null($perpage)){
			$where .= " limit $offset,$perpage";
		}
		if($count===true){
			$out = $this->price_db->query("select count(*) count from ".$this->price_db->dbprefix(self::TAB_PTH).$where)->result_array();
			return isset($out[0])?$out[0]['count']:0;
		}
		$out = $this->price_db->query("select * from ".$this->price_db->dbprefix(self::TAB_PTH).$where);
		$result_array_sort = array();
		$result_array_sort = $out->result_array();
		$lists = array();
		if(!empty($search_conditions['batch'])){
			$wherep = " AND a.batch=".$search_conditions['batch'];
		}else{
			$maxbatch = $this->getMaxBatch($inter_id,'',self::TAB_PRP);
			$wherep = " AND a.batch=".$maxbatch;
		}
		foreach ($result_array_sort as $kr => $vr) {
			$prpSql = "SELECT a.*,b.room_name ctrip_name,b.price ctrip_price,b.bed,b.breakfast,c.room_name iwide_name,c.total_price iwide_price,c.price_name,c.book_status,c.breakfast ibreakfast,c.id cid FROM ".$this->price_db->dbprefix(self::TAB_PRP)." a LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PTR)." b ON a.third_room_id=b.id AND a.third_id=b.third_id AND a.batch=b.batch LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PWR)." c ON a.inter_id=c.inter_id AND a.hotel_id=c.hotel_id AND a.room_id=c.room_id AND a.price_code=c.price_code AND a.batch=c.batch WHERE a.inter_id='$inter_id' AND a.hotel_id='{$vr['hotel_id']}' AND a.third_type='$third_type'".$wherep." AND a.adddate='".$this->date."' AND c.adddate='".$this->date."' AND (b.adddate='".$this->date."' OR a.third_room_id=0) ORDER BY c.id ASC";
			$listone = $this->price_db->query($prpSql)->result_array();
			$wxsql = "SELECT * FROM ".$this->price_db->dbprefix(self::TAB_PWR)." a WHERE a.inter_id='$inter_id' AND a.hotel_id='{$vr['hotel_id']}' AND a.adddate='".$this->date."'".$wherep." ORDER BY id ASC";
			$listonewx = $this->price_db->query($wxsql)->result_array();
			if(!empty($listone)){
				$list1 = array();
				foreach ($listone as $kl => $vl) {
					$list1[] = array(
						'hotel_id' => $vl['hotel_id'],
						'ctrip_name'=>$vl['ctrip_name'],
						'ctrip_price'=>$vl['ctrip_price'],
						'ctrip_bed'=>$vl['bed'],
						'ctrip_breakfast'=>$vl['breakfast'],
						'iwide_name'=>$vl['iwide_name'],
						'iwide_price'=>$vl['iwide_price'],
						'iwide_price_name'=>$vl['price_name'],
						'chajia'=>bcsub($vl['ctrip_price'],$vl['iwide_price'],2),
						'chajia_rev'=>!empty($vl['ctrip_price'])?bcsub($vl['iwide_price'],$vl['ctrip_price'],2):'0.00',
						'book_status'=>$vl['book_status'],
						'ibreakfast'=>$vl['ibreakfast'],
						'cid'=>$vl['cid'],
						);
				}
				//补全未匹配的微信房型,按微信房型信息表顺序
				foreach($listonewx as $kw=>$vw){
					$arr = array(
						'hotel_id' => $vw['hotel_id'],
						'ctrip_name'=>'',
						'ctrip_price'=>'',
						'ctrip_bed'=>'',
						'ctrip_breakfast'=>'',
						'iwide_name'=>$vw['room_name'],
						'iwide_price'=>$vw['total_price'],
						'iwide_price_name'=>$vw['price_name'],
						'chajia'=>'',
						'chajia_rev'=>'0.00',
						'book_status'=>$vw['book_status'],
						'ibreakfast'=>$vw['ibreakfast'],
						'cid'=>$vw['id'],
						);
					$s = 0;
					foreach($list1 as $vl1){
						if($vl1['cid']==$vw['id']){
							$list[] = $vl1;
							$s = 1;
						}
					}
					if($s==0){
						$list[] = $arr;
					}
				}
				// 排序
				if(!empty($search_conditions['order'])){
					$sort_key = array();
					foreach ($list as $kl => $vl) {
						$sort_key[] = $vl['chajia_rev'];
					}
					if($search_conditions['order']=='asc'){
						array_multisort($sort_key,SORT_ASC,SORT_NUMERIC,$list);
					}elseif($search_conditions['order']=='desc'){
						array_multisort($sort_key,SORT_DESC,SORT_NUMERIC,$list);
					}
				}
				$lists[$vr['name']] = $list;
			}else{
				$lists[$vr['name']] = array();
			}
		}
		if(!empty($lists)){
			return $lists;
		}
		return false;
	}

	/*
	 * 获取全部有效公众号id,有匹配到第三方酒店的 
	 */
	public function getAllInterids($inter_id=null){
		$all = array();
		$where = '';
		$and = '';
		if(!is_null($inter_id)){
			$where .= " WHERE inter_id='".$inter_id."'";
			$and .= " AND inter_id='".$inter_id."'";
		}
		$nsql = "SELECT count(*) count,inter_id FROM ".$this->price_db->dbprefix(self::TAB_PTH).$where." GROUP BY inter_id";
		$nres = $this->price_db->query($nsql)->result_array();
		$inter_count = array();
		if(!empty($nres)){
			foreach ($nres as $key => $value) {
				$inter_count[$value['inter_id']] = $value['count'];
			}
		}
		$all['inter_count'] = $inter_count;
		$isql = "SELECT inter_id,hotel_id FROM ".$this->price_db->dbprefix(self::TAB_PTH)." WHERE (ctrip_id is not null or ctrip_id<>'') and grab_flag=1".$and;
		$ires = $this->price_db->query($isql)->result_array();
		$inter_ids = array();
		if(!empty($ires)){
			foreach ($ires as $key => $value) {
				$inter_ids[$value['inter_id']][] = $value['hotel_id'];
			}
		}
		$all['hotel_ids'] = $inter_ids;
		return $all;
	}

	/*
	 * 获取当前公众号下的全部有匹配到第三方酒店的所有酒店
	 */
	public function getHotels($inter_id,$hotel_ids=''){
		if(empty($inter_id)){
			return false;
		}
		$where = " where (ctrip_id is not null or ctrip_id<>'') and grab_flag=1 and inter_id='".$inter_id."'";
		if(!empty($hotel_ids)){
			$where .= ' and hotel_id in ('.$hotel_ids.')';
		}
		$sql = "SELECT * FROM ".$this->price_db->dbprefix(self::TAB_PTH).$where;
		return $this->price_db->query($sql)->result_array();
	}

	/*
	 * 获取全部酒店的房型数
	 */
	public function getInterRooms($third_type,$inter_id=null){
		if(empty($third_type)){
			return false;
		}
		$all = array();
		$and = " AND third_type='$third_type'";
		$int = " AND third_type='$third_type'";
		if(!is_null($inter_id)){
			$and .= " AND inter_id='".$inter_id."'";
			// $int .= " AND inter_id='".$inter_id."'";
			// $maxbatch_w = $this->getMaxBatch($inter_id,'',self::TAB_PWR);
			// $int .= " AND batch=".$maxbatch_w;
		}
		// $and .= " AND batch=(SELECT MAX(batch) FROM ".$this->price_db->dbprefix(self::TAB_PRP)." WHERE inter_id=a.inter_id AND adddate='".$this->date."')";
		// $csql = "SELECT COUNT(*) count,inter_id FROM ".$this->price_db->dbprefix(self::TAB_PWR)." a WHERE adddate='".$this->date."'".$int." GROUP BY inter_id";
		// $cres = $this->price_db->query($csql)->result_array();
		// foreach ($cres as $key => $value) {
		// 	$all['room_nums'][$value['inter_id']] = $value['count'];
		// }
		// $tsql = "SELECT COUNT(*) count,inter_id FROM (SELECT * FROM ".$this->price_db->dbprefix(self::TAB_PRP)." a WHERE third_room_id>0 AND adddate='".$this->date."'".$and." GROUP BY inter_id,hotel_id,room_id,price_code) s GROUP BY inter_id";
		$msql = "SELECT MAX(batch) maxbatch,inter_id FROM ".$this->price_db->dbprefix(self::TAB_PHC)." WHERE adddate='".$this->date."'".$and." GROUP BY inter_id";
		$mres = $this->price_db->query($msql)->result_array();
		foreach ($mres as $km => $vm){
			$csql = "SELECT COUNT(*) count,inter_id FROM ".$this->price_db->dbprefix(self::TAB_PWR)." a WHERE adddate='".$this->date."' AND inter_id='{$vm['inter_id']}' AND batch={$vm['maxbatch']}";
			$cres = $this->price_db->query($csql)->row_array();
			$all['room_nums'][$vm['inter_id']] = $cres['count'];
			$vsql = "SELECT COUNT(*) count FROM (SELECT inter_id FROM ".$this->price_db->dbprefix(self::TAB_PRP)." WHERE third_room_id>0 AND adddate='".$this->date."'".$int." AND batch={$vm['maxbatch']} AND inter_id='{$vm['inter_id']}' GROUP BY inter_id,hotel_id,room_id,price_code) s";
			$vres = $this->price_db->query($vsql)->row_array();
			$all['al_nums'][$vm['inter_id']] = $vres['count'];
		}
		// $tres = $this->price_db->query($tsql)->result_array();
		// foreach ($tres as $key => $value) {
		// 	$all['al_nums'][$value['inter_id']] = $value['count'];
		// }
		return $all;
	}

	/*
	 * 获取酒店集团下所有酒店的匹配详情
	 */
	public function getParityInfo($inter_id,$third_type,$condits=array()){
		if(empty($inter_id)||empty($third_type)){
			MYLOG::w('getParityInfo参数有误:'.$inter_id.'&'.$third_type,'paritys');
			return false;
		}
		$where = '';
		if(!empty($condits['hotel_ids'])){
			$where .= " AND hotel_id IN (".implode(',',$condits['hotel_ids']).")"; 
		}
		$hsql = "SELECT * FROM ".$this->price_db->dbprefix(self::TAB_PTH)." WHERE inter_id='$inter_id'".$where." GROUP BY hotel_id";
		$hotels = $this->price_db->query($hsql)->result_array();
		// MYLOG::w('getParityInfo1:'.$hsql.'&'.json_encode($hotels),'paritys');
		$maxbatch = $this->getMaxBatch($inter_id,'',self::TAB_PRP);
		$where .= " AND batch=".$maxbatch;
		$csql = "SELECT COUNT(*) count,hotel_id,inter_id FROM ".$this->price_db->dbprefix(self::TAB_PWR)." a WHERE adddate='".$this->date."' AND inter_id='$inter_id'".$where." GROUP BY hotel_id";
		$cres = $this->price_db->query($csql)->result_array();
		$room_nums = array();
		foreach ($cres as $key => $value) {
			$room_nums[$value['hotel_id']] = $value['count'];
		}
		// MYLOG::w('getParityInfo2:'.$csql.'&'.json_encode($room_nums),'paritys');
		$psql = "SELECT count(*) count,hotel_id,inter_id FROM (SELECT * FROM ".$this->price_db->dbprefix(self::TAB_PRP)." WHERE third_room_id>0 AND inter_id='$inter_id' AND third_type='$third_type' AND adddate='".$this->date."'".$where." GROUP BY inter_id,hotel_id,room_id,price_code) s GROUP BY hotel_id";
		$counts = $this->price_db->query($psql)->result_array();
		$room_al_nums = array();
		foreach ($counts as $key => $value) {
			$room_al_nums[$value['hotel_id']] = $value['count'];
		}
		// MYLOG::w('getParityInfo3:'.$psql.'&'.json_encode($room_al_nums),'paritys');
		foreach ($hotels as $k => $hotel) {
			$hotels[$k]['room_num'] = isset($room_nums[$hotel['hotel_id']])?$room_nums[$hotel['hotel_id']]:0;
			$hotels[$k]['room_al_num'] = isset($room_al_nums[$hotel['hotel_id']])?$room_al_nums[$hotel['hotel_id']]:0;
			if(isset($room_nums[$hotel['hotel_id']])&&isset($room_al_nums[$hotel['hotel_id']])){
				$hotels[$k]['room_nal_num'] = $room_nums[$hotel['hotel_id']] - $room_al_nums[$hotel['hotel_id']];
			}elseif(isset($room_nums[$hotel['hotel_id']])&&!isset($room_al_nums[$hotel['hotel_id']])){
				$hotels[$k]['room_nal_num'] = $room_nums[$hotel['hotel_id']];
			}else{
				$hotels[$k]['room_nal_num'] = 0;
			}
		}
		MYLOG::w('getParityInfo4:'.json_encode($hotels),'paritys');
		return $hotels;
	}

	/*
	 * 获取指定酒店匹配信息
	 */
	public function getHotelParity($inter_id,$hotel_id,$third_type){
		if(empty($inter_id)||empty($hotel_id)||empty($third_type)){
			return false;
		}
		$this->price_db->where(array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			));
		return $this->price_db->get(self::TAB_PTH)->row_array();
	}

	/*
	 * 获取酒店房型匹配信息
	 */
	public function getRoomParity($inter_id,$hotel_id,$third_type){
		if(empty($inter_id)||empty($hotel_id)||empty($third_type)){
			return false;
		}
		$paritys = array();
		$hotel_parity = $this->getHotelParity($inter_id,$hotel_id,$third_type);
		$maxbatch = $this->getMaxBatch($inter_id,$hotel_id,self::TAB_PRP);
		if(!empty($hotel_parity)){
			$sql = "SELECT a.*,b.room_id,b.room_name w_room_name,b.price_name,b.total_price,b.breakfast ibreakfast,c.room_name c_room_name,c.breakfast,c.bed,c.price,c.remark,c.gift FROM ".$this->price_db->dbprefix(self::TAB_PRP)." a LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PWR)." b ON a.inter_id=b.inter_id AND a.hotel_id=b.hotel_id AND a.room_id=b.room_id AND a.price_code=b.price_code AND a.batch=b.batch LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PTR)." c ON a.third_id=c.third_id AND a.third_room_id=c.id AND a.batch=c.batch WHERE a.inter_id='$inter_id' AND a.hotel_id IN(".$hotel_id.") AND a.third_type='$third_type' AND a.adddate='".$this->date."' AND b.adddate='".$this->date."' AND a.batch=".$maxbatch." AND (c.adddate='".$this->date."' OR a.third_room_id=0) ORDER BY b.id ASC";
			$room_parity = $this->price_db->query($sql)->result_array();
			foreach ($room_parity as $key => $value) {
				if($value['gift']=='y'){
					$room_parity[$key]['gift'] = '(礼)';
				}else{
					$room_parity[$key]['gift'] = '';
				}
				if(!empty($value['price'])){
					$room_parity[$key]['price'] = '¥'.$value['price'];
				}
				if(!empty($value['total_price'])){
					$room_parity[$key]['total_price'] = '¥'.$value['total_price'];
				}
				if(!empty($value['price'])&&!empty($value['total_price'])){
					$room_parity[$key]['chajia'] = '¥'.bcsub($value['total_price'],$value['price'],2);
				}
			}
			$paritys['hotel_parity'] = $hotel_parity;
			$paritys['room_parity'] = $room_parity;
		}
		return $paritys;
	}

	/*
	 * 获取指定酒店的所有第三方房型
	 */
	public function getThirdRooms($inter_id,$hotel_id,$third_type){
		if(empty($inter_id)||empty($hotel_id)||empty($third_type)){
			return false;
		}
		$hotel_parity = $this->getHotelParity($inter_id,$hotel_id,$third_type);
		if(!empty($hotel_parity)){
			$maxbatch = $this->getMaxBatch($hotel_parity['ctrip_id'],'',self::TAB_PTR);
			$this->price_db->where(array(
				'third_id' => $hotel_parity['ctrip_id'],
				'adddate' => $this->date,
				'third_type' => $third_type,
				'batch'=>$maxbatch,
				));
			$third_rooms = $this->price_db->get(self::TAB_PTR)->result_array();
			foreach ($third_rooms as $key => $value) {
				if($value['gift']=='y'){
					$third_rooms[$key]['gift'] = '(礼)';
				}else{
					$third_rooms[$key]['gift'] = '';
				}
				if(!empty($value['price'])){
					$third_rooms[$key]['price'] = '¥'.$value['price'];
				}
			}
			return $third_rooms;
		}
		return array();
	}

	public function getMaxBatch($inter_id='',$hotel_id='',$tablename=self::TAB_PHC){
		if(!empty($inter_id)){
			if($tablename==self::TAB_PTR){
				$this->price_db->where(array(
					'third_id'=>$inter_id,
					));
			}else{
				$this->price_db->where(array(
					'inter_id'=>$inter_id,
					));
			}
		}
		if(!empty($hotel_id)){
			$this->price_db->where(array(
				'hotel_id'=>$hotel_id,
				));
		}
		$this->price_db->where(array(
			'adddate'=>$this->date,
			));
		$maxbatch = $this->price_db->select('MAX(batch) maxbatch')->get($tablename)->row_array();
		return !empty($maxbatch['maxbatch'])?$maxbatch['maxbatch']:0;
	}

	/*
	 * 获取倒挂率结果
	 */
	public function getDownRate($inter_id,$third_type,$condits=array(),$count=false){
		if(empty($inter_id)||empty($third_type)){
			return false;
		}
		$where = " WHERE a.inter_id='$inter_id' AND third_type='$third_type' AND adddate='".$this->date."'";
		if(isset($condits['batch'])){
			$where .= " AND a.batch=".$condits['batch'];
		}else{
			$maxbatch = $this->getMaxBatch($inter_id,'',self::TAB_PHC);
			$where .= " AND a.batch=".$maxbatch;
		}
		$join = "";
		if(!empty($condits['hotel_ids'])){
			$where .= " AND a.hotel_id IN (".implode(',', $condits['hotel_ids']).")";
		}
		if(!empty($condits['wd'])){
			$where .= " AND (b.name like '%".$condits['wd']."%' OR b.city LIKE '%".$condits['wd']."%')";
			$join = " LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PTH)." b ON a.inter_id=b.inter_id AND a.hotel_id=b.hotel_id";
		}
		if($count==true){
			$sql = "SELECT count(*) count FROM ".$this->price_db->dbprefix(self::TAB_PHC).' a'.$join.$where;
			$count = $this->price_db->query($sql)->row_array();
			return $count?$count['count']:0;
		}
		if(!empty($condits['order'])){
			$where .= " ORDER BY ".$condits['order'];
		}
		if(!empty($condits['offset'])&&!empty($condits['nums'])){
			$where .= " LIMIT ".$condits['offset'].",".$condits['nums'];
		}elseif(!empty($condits['nums'])){
			$where .= " LIMIT ".$condits['nums'];
		}
		$sql = "SELECT a.*,b.name hotel_name,b.city FROM ".$this->price_db->dbprefix(self::TAB_PHC)." a LEFT JOIN ".$this->price_db->dbprefix(self::TAB_PTH)." b ON a.inter_id=b.inter_id AND a.hotel_id=b.hotel_id".$where;
		return $this->price_db->query($sql)->result_array();
	}

	//获取比价生成开始时间
	public function getStartInfo($inter_id,$condits){
		$this->price_db->where(array(
			'inter_id'=>$inter_id,
			));
		if(!empty($condits['adddate'])){
			$this->price_db->where(array(
				'adddate'=>$condits['adddate'],
				));
		}
		if(!empty($condits['batch'])){
			$this->price_db->where(array(
				'batch'=>$condits['batch'],
				));
		}
		return $this->price_db->order_by('addtime','asc')->limit(1)->get(self::TAB_PWR)->row_array();
	}

	/*
	 * 获取平均差价结果
	 */
	public function getAvgDiffPrice($inter_id,$third_type,$condits=array()){
		if(empty($inter_id)||empty($third_type)){
			return false;
		}
		$lists = $this->getParitys($inter_id,$third_type,$condits['hotel_ids'],false,$condits['offset'],$condits['nums']);
		$datas = array();
		if(empty($lists)){
			return $datas;
		}
		// 计算倒挂率
		foreach ($lists as $k => $v) {
			$total_price = 0;
			$n = 0;
			foreach ($v as $key => $value) {
				if($value['ctrip_name']!=''&&$value['iwide_name']!=''){
					$n++;
					$total_price += $value['chajia_rev'];
				}
			}
			$hotel_id = !empty($v[0]['hotel_id'])?$v[0]['hotel_id']:0;
			$datas[$hotel_id] = $n>0?sprintf("%1\$.2f",round($total_price/$n,2)):'0.00';
		}
		return $datas;
	}

	/*
	 * 修改酒店匹配结果
	 */
	public function editHotelParity($inter_id,$hotel_id,$data=array()){
		if(empty($inter_id)||empty($hotel_id)||empty($data)){
			return 'false';
		}
		$this->price_db->where(array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			));
		$res = $this->price_db->update(self::TAB_PTH,$data);
		return $res?'success':'false';
	}

	/*
	 * 修改房型匹配结果
	 */
	public function editRoomParity($inter_id,$hotel_id,$pid,$third_type,$data){
		if(empty($inter_id)||empty($hotel_id)||empty($pid)||empty($third_type)||empty($data)){
			return 'false';
		}
		$this->price_db->where(array(
			'id' => $pid,
			'third_type' => $third_type,
			));
		$room = $this->price_db->get(self::TAB_PRP)->row_array();
		// 判断数据今日数据是否已经更新
		if($room['adddate']!=$this->date){
			return 'new';
		}
		$data_m = $data;
		unset($data['athour']);
		//开启手动事务
		$this->price_db->trans_strict(FALSE);
		$this->price_db->trans_begin();
		$this->price_db->where(array(
			'id' => $pid,
			'third_type' => $third_type,
			'adddate' => $this->date,
			));
		$pres = $this->price_db->update(self::TAB_PRP,$data);
		if(!$pres){
			$this->price_db->trans_rollback();
			return 'false';
		}
		$this->price_db->where(array(
			'id' => $pid,
			));
		$prpres = $this->price_db->get(self::TAB_PRP)->row_array();
		if(!$prpres){
			MYLOG::w('editRoomParity1查询匹配信息出错id:'.$pid,'paritys');
			$this->price_db->trans_rollback();
			return false;
		}
		$this->price_db->where(array(
			'inter_id' => $prpres['inter_id'],
			'hotel_id' => $prpres['hotel_id'],
			'room_id' => $prpres['room_id'],
			'price_code' => $prpres['price_code'],
			)
		);
		$prpmres = $this->price_db->get(self::TAB_PRPM)->row_array();
		if($data['third_room_id']>0){
			$this->price_db->where(array(
				'id' => $data['third_room_id'],
				));
			$third_room = $this->price_db->get(self::TAB_PTR)->row_array();
			if(!$third_room){
				MYLOG::w('editRoomParity2查询手动匹配信息出错tr_id:'.$data['third_room_id'],'paritys');
				$this->price_db->trans_rollback();
				return false;
			}
			if(!$prpmres){
				$data_m['inter_id'] = $prpres['inter_id'];
				$data_m['hotel_id'] = $prpres['hotel_id'];
				$data_m['room_id'] = $prpres['room_id'];
				$data_m['price_code'] = $prpres['price_code'];
				$data_m['third_type'] = $prpres['third_type'];
				$data_m['hotel_name'] = $third_room['hotel_name'];
				$data_m['room_name'] = $third_room['room_name'];
				$data_m['breakfast'] = $third_room['breakfast'];
				$data_m['bed'] = $third_room['bed'];
				unset($data_m['third_room_id']);
				$mres = $this->price_db->insert(self::TAB_PRPM,$data_m);
			}else{
				$data_m['third_type'] = $prpres['third_type'];
				$data_m['hotel_name'] = $third_room['hotel_name'];
				$data_m['room_name'] = $third_room['room_name'];
				$data_m['breakfast'] = $third_room['breakfast'];
				$data_m['bed'] = $third_room['bed'];
				unset($data_m['third_room_id']);
				$this->price_db->where(array(
					'inter_id' => $prpres['inter_id'],
					'hotel_id' => $prpres['hotel_id'],
					'room_id' => $prpres['room_id'],
					'price_code' => $prpres['price_code'],
					)
				);
				$mres = $this->price_db->update(self::TAB_PRPM,$data_m);
			}
			if(!$mres){
				MYLOG::w('入手动匹配表失败:'.json_encode($data_m),'paritys');
				$this->price_db->trans_rollback();
				return 'false';
			}
		}
		//修改对应的酒店倒挂率、平均差价、房型匹配率
		$cres = $this->updateHotelCount($inter_id,$hotel_id,$third_type);
		if(!$cres){
			$this->price_db->trans_rollback();
			MYLOG::w('修改统计结果失败:'.json_encode($cres),'paritys');
			return 'false';
		}
		if($this->price_db->trans_status() === TRUE){
			$this->price_db->trans_commit();
			return 'success';
		}else{
			$this->price_db->trans_rollback();
			MYLOG::w('trans_status不为true,commit失败','paritys');
			return 'false';
		}
	}

	/*
	 * 更新酒店数据统计(倒挂率、平均差价、房型匹配率)
	 */
	public function updateHotelCount($inter_id,$hotel_id,$third_type){
		if(empty($inter_id)||empty($hotel_id)||empty($third_type)){
			return false;
		}
		$lists = $this->getParitys($inter_id,$third_type,array('hotel_ids'=>array($hotel_id)));
		if(!$lists){
			MYLOG::w('获取比价数据失败getParitys:'.json_encode($lists),'paritys');
			return false;
		}
		$info = $this->getParityInfo($inter_id,$third_type,array('hotel_ids'=>array($hotel_id)));
		if(!$info){
			MYLOG::w('获取匹配详情失败getParityInfo:'.json_encode($info),'paritys');
			return false;
		}
		$infos = array();
		foreach ($info as $ki => $vi) {
			$infos[$vi['name']] = $vi;
		}
		$datetime = date('Y-m-d H:i:s');
		$datas = array();
		// 计算倒挂率
		foreach ($lists as $k => $v) {
			$total_price = 0;
			$n= 0;
			$m = 0;
			foreach ($v as $key => $value) {
				if($value['iwide_name']!=''&&$value['ctrip_name']!=''){
					$n++;
					$total_price += $value['chajia_rev'];
				}
				if($value['ctrip_name']!=''&&$value['iwide_name']!=''&&$value['chajia']<0){
					$m++;
				}
			}
			$match_rate = $infos[$k]['room_num']>0?sprintf("%1\$.2f",round(($infos[$k]['room_al_num']/$infos[$k]['room_num'])*100,2)):'0.00';
			$datas = array(
				'uptime' => $datetime,
				'down_rate' => $n>0?sprintf("%1\$.2f",round(($m/$n)*100,2)):'0.00',
				'avg_diffprice' => $n>0?sprintf("%1\$.2f",round($total_price/$n,2)):'0.00',
				'match_rate' => $match_rate,
				);
		}
		// 更新
		if(!empty($datas)){
			$this->price_db->where(array(
				'adddate' => $this->date,
				'inter_id' => $inter_id,
				'hotel_id' => $hotel_id,
				'third_type' => $third_type,
				));
			$res = $this->price_db->update(self::TAB_PHC,$datas);
			if(!$res){
				MYLOG::w('修改统计结果入库失败:'.json_encode($datas),'paritys');
				return false;
			}
			return true;
		}
		return false;
	}

	//redis cache
	public function redisGo($key,$type='get',$value='',$exp=1800){
		$this->load->library('Cache/Redis_proxy',array(
			'not_init'=>FALSE,
			'module'=>'common',
			'refresh'=>FALSE,
			'environment'=>ENVIRONMENT
			),'redis_proxy'
		);
		if($type=='set'){
			return $this->redis_proxy->set($key,$value,$exp);
		}
		return $this->redis_proxy->get($key);
	}

	// 获取有开通比价的公众号数据
	public function get_hotels_group($is_parity=1){
		$this->price_db->where(array(
			'is_parity'=>$is_parity,
			));
		return $this->price_db->select('name,inter_id,status,is_parity')->get(self::TAB_P)->result_array();
	}

	//同步公众号信息和酒店信息
	public function syncHotelInfo($inter_id=null){
		$inter_id = !empty($inter_id)?$inter_id:$this->inter_id;
		//验证当前公众号是否已经开通比价
		$res = $this->price_db->where(array('inter_id'=>$inter_id))->select('inter_id,status,is_parity')->get(self::TAB_P)->row_array();
		if(!empty($res)){
			if($res['is_parity']==1){
				return 'exist';
			}
		}
		//同步公众号信息
		$public_res = $this->db->where(array('inter_id'=>$inter_id))->select('name,inter_id,status,domain,token')->get(self::TAB_P)->row_array();
		$this->price_db->trans_begin();
		if(!empty($public_res)){
			$public_res['is_parity'] = 1;
			if(!empty($res)){
				//更新
				$r = $this->price_db->where(array('inter_id'=>$inter_id))->update(self::TAB_P,$public_res);
			}else{
				//插入
				$r = $this->price_db->insert(self::TAB_P,$public_res);
			}
			if(!$r){
				$this->price_db->trans_rollback();
				return false;
			}
		}
		//同步酒店信息
		$hotel_res = $this->db->where(array('inter_id'=>$inter_id))->select('hotel_id,inter_id,name,address,country,province,city,status')->get(self::TAB_H)->result_array();
		if(!empty($hotel_res)){
			$hotel_ids = array();
			$hotels = array();
			foreach($hotel_res as $hotel){
				$hotel_ids[] = $hotel['hotel_id'];
				$hotels[$hotel['hotel_id']] = $hotel;
			}
			$hotel_price = $this->price_db->where(array('inter_id'=>$inter_id))->select('hotel_id')->get(self::TAB_H)->result_array();
			$hotel_price_ids = array();
			foreach($hotel_price as $hid){
				$hotel_price_ids[] = $hid['hotel_id'];
			}
			$update_ids = array();
			$insert_ids = array();
			foreach($hotel_ids as $id){
				if(in_array($id,$hotel_price_ids)){
					$update_ids[] = $id; 
				}else{
					$insert_ids[] = $id;
				}
			}
			//更新
			foreach($update_ids as $id){
				$data_u = $hotels[$id];
				$r = $this->price_db->where(array('inter_id'=>$inter_id,'hotel_id'=>$id))->update(self::TAB_H,$data_u);
				if(!$r){
					$this->price_db->trans_rollback();
					return false;
				}
			}
			//插入
			$data_i = array();
			foreach($insert_ids as $id){
				$data_i[] = $hotels[$id];
			}
			if(!empty($data_i)){
				$r = $this->price_db->insert_batch(self::TAB_H,$data_i);
				if(!$r){
					$this->price_db->trans_rollback();
					return false;
				}
			}
			$this->price_db->trans_commit();
			return true;
		}
	}

	/*
	 * 对比价格
	 */
	public function showList($inter_id){
		$third_type = 'ctrip'; //后续增加这个第三方类型的判断
		$where = "where (ctrip_id is not null or ctrip_id<>'') and grab_flag=1 and inter_id='".$inter_id."'";
		$out = $this->price_db->query("select * from ".$this->price_db->dbprefix(self::TAB_PTH)." ".$where);
		$result_array = $out->result_array();
		$result_array_sort = array();
		$result_array_sort = $out->result_array();
		foreach ($result_array_sort as $ch => $vh) {
			$outinfo = $this->price_db->query("SELECT * FROM ".$this->price_db->dbprefix(self::TAB_PWR)." WHERE inter_id='{$vh['inter_id']}' AND hotel_id='{$vh['hotel_id']}' AND adddate='".$this->date."'");
			$iwiderooms = $outinfo->result_array();
			$htmlfile = $vh['ctrip_id'].'.html';
			$this->price_db->where(array(
				'third_id' => $vh['ctrip_id'],
				'third_type' => $third_type,//ctrip.携程，meituan.美团，alitrip.阿里，qunar.去哪儿
				'adddate' => $this->date, 
				));
			$rooms = $this->price_db->get(self::TAB_PTR)->result_array();
			if(empty($rooms)){
				$rooms = $this->getCtriprooms($htmlfile);
				if(is_array($rooms)){
					// 入库
					$rooms = trimArray($rooms);
					$hotelname = !empty($rooms['hotelname'])?$rooms['hotelname']:'';
					if(empty($rooms['room'])){
						MYLOG::w('第三方房型信息为空getCtriprooms|'.$vh['ctrip_id'],'paritys');
						continue;
					}
					foreach ($rooms['room'] as $k => $room) {
						$datetime = date('Y-m-d H:i:s');
						$date = date('Y-m-d');
						$price = !empty($room['price'])?trim($room['price'],'¥'):0;
						$remark = !empty($room['remark'])?$room['remark']:'';
						$gift = !empty($room['gift'])?'y':'n';
						$bed = !empty($room['bed'])?$room['bed']:'';
						$breakfast = !empty($room['breakfast'])?$room['breakfast']:'';
						$inrooms = array(
							'third_id' =>$vh['ctrip_id'],
							'addtime' => $datetime,
							'adddate' => $date,
							'hotel_name' => $hotelname,
							'room_name' => $room['roomname'],
							'breakfast' => $breakfast,
							'bed' => $bed,
							'price' => $price,
							'remark' => $remark,
							'gift' => $gift,
							'third_type' =>$third_type,
							);
						$rows = $this->price_db->insert(self::TAB_PTR,$inrooms);
						if($rows===false){
							MYLOG::w('第三方酒店房型信息入库失败|'.$inrooms,'paritys');
							continue;
						}
						$numb = $this->price_db->insert_id();
						$rooms['room'][$k]['trid'] = $numb;
					}
					$mins = $this->brushrooms($rooms);
					if(empty($mins)){
						MYLOG::w('第三方房型信息为空brushrooms|'.$vh['ctrip_id'],'paritys');
						continue;
					}
				}else{
					MYLOG::w($rooms,'paritys');
					continue;
				}
			}else{
				$roomss = array();
				$roomss['hotelname'] = $vh['ctrip_name'];
				foreach ($rooms as $k => $room) {
					$rarr = array(
						'trid' => $room['id'],
						'roomname' => $room['room_name'],
						'breakfast' => $room['breakfast'],
						'bed' => $room['bed'],
						'price' => '¥'.$room['price'],
						);
					if(isset($room['gift'])&&$room['gift']=='y'){
						$rarr['gift'] = '礼';
					}
					if(isset($room['remark'])){
						$rarr['remark'] = $room['remark'];
					}
					$roomss['room'][] = $rarr;
				}
				$mins = $this->brushrooms($roomss);
				if(empty($mins)){
					MYLOG::w('第三方房型信息为空brushrooms|'.$vh['ctrip_id'],'paritys');
					continue;
				}
			}
			$nomins = $mins;
			$attrs = array();
			foreach ($iwiderooms as $key => $value) {
				$attrs1 = $value['inter_id'].$value['hotel_id'].$value['hotel_name'].$value['room_name'].$value['price_name'].$value['price_code'].$value['total_price'].$value['book_status'];
				$attrs[$key] = $attrs1;
			}
			$attrskey = array_keys(array_unique($attrs));
			$iwideroomsarr = array();
			$k = 0;	
			foreach ($iwiderooms as $key => $value) {
				if(in_array($key, $attrskey)){
					$iwideroomsarr[$k]['price'] = $value['total_price'];
					$bf = '';
					if($value['price_name']){
						if($pos = mb_strpos($value['price_name'],'早')){
							$bf = mb_substr($value['price_name'],$pos-1);
						}
					}
					$iwideroomsarr[$k]['roomname'] = trim($value['room_name']).$bf;
					$iwideroomsarr[$k]['room_id'] = $value['room_id'];
					$iwideroomsarr[$k]['price_name'] = trim($value['room_name']).'_'.$value['price_name'];
					$iwideroomsarr[$k]['book_status'] = trim($value['book_status'])=='full'?'(已满)':'';
					$iwideroomsarr[$k]['room_name'] = trim($value['room_name']);
					$iwideroomsarr[$k]['price_code'] = trim($value['price_code']);
					$k++;
				}
			}
			$noiwiderooms = $iwideroomsarr;
			$data['hotel_name'][] = $vh['name'];
			$output = array();
			$repeatroom = array();//记录已经出现过的两边完全相同的房型名
			$crefirst = array();
			foreach ($mins as $km => $vm) {
				$kroom = $this->splitRoomName(trim($vm['roomname']));
				if(isset($kroom['refirst'])){
					$crefirst = array_merge($crefirst,$kroom['refirst']);
				}
			}
			$crefirst = array_unique($crefirst);
			$irefirst = array();
			foreach ($iwideroomsarr as $ki => $vi) {
				$iroom = $this->splitRoomName(trim($vi['roomname']));
				if(isset($iroom['refirst'])){
					$irefirst = array_merge($irefirst,$iroom['refirst']);
				}
			}
			$irefirst = array_unique($irefirst);
			$this->norefirst = array_intersect($crefirst, $irefirst);
			$isfor = 0;
			foreach ($iwideroomsarr as $ke => $val) {
				$ok = 0;
				$roomarr = array();
				$reoutput = array();
				$samename = true;
				$this->curriname = $val['room_name'];
				foreach ($mins as $k => $v) {
					// $status = $this->analysisRoom($v['roomname'],$val['roomname']);var_dump($status);exit;
					$this->currcname = $v['room_name'];
					//房型名去掉类似（xx）这种
					if(($lpos=mb_strpos($this->currcname,'('))!==false&&($rpos=mb_strpos($this->currcname,')'))!==false){
						$this->currcname = mb_substr($this->currcname,0,$lpos).mb_substr($this->currcname,$rpos+1);
					}elseif(($lpos=mb_strpos($this->currcname,'（'))!==false&&($rpos=mb_strpos($this->currcname,'）'))!==false){
						$this->currcname = mb_substr($this->currcname,0,$lpos).mb_substr($this->currcname,$rpos+1);
					}
					//判断当前房型是是否是钟点房或时租房
					$iszd = false; //默认不是
					foreach ($this->ctriprooms as $kc => $vc) {
						if($vc['nkey']==$v['nkey']){
							foreach ($this->timerooms as $vt) {
								if(isset($vc['remark'])&&mb_strpos($vc['remark'],$vt)!==false){
									$iszd = true;
									$remarks = $vc['remark'];
									break;
								}
							}
							if($iszd){
								break;
							}
						}
					}
					//兼容房型名中的"间"和"房"
					if(in_array(mb_substr($this->curriname, -1,1),array('间','房'))&&in_array(mb_substr($this->currcname, -1,1),array('间','房'))){
						$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-1).mb_substr($this->currcname,-1,1);
					}
					if(mb_strpos($val['price_name'], '积分')===false&&$this->analysisRoom($v['roomname'],$val['roomname'])){						
						//如果是钟点/时租房且当前公众号房型又不是钟点/时租房则跳过此次循环
						$istime = false;
						foreach ($this->timerooms as $vt) {
							if($iszd===true&&mb_strpos($val['price_name'], $vt)===false){
								$istime = true;
								break;
							}
						}
						if($istime===true){
							continue;
						}

						//如果当前公众号房型是钟点/时租房而携程房型不是则跳过此次循环
						$isnotime = false;
						foreach ($this->timerooms as $vt) {
							if(mb_strpos($val['price_name'], $vt)!==false&&mb_strpos($v['roomname'], $vt)===false){
								$isnotime = true;
								break;
							}
						}
						if($isnotime){
							continue;
						}

						//如果当前公众号房型是日租房而携程房型不是则跳过此次循环
						if(mb_strpos($val['price_name'], '日租')!==false&&mb_strpos($v['roomname'], '日租')===false){
							continue;
						}
						//差价大于或等于公众号本身价格的视为匹配异常
						if(bcsub($v['price'],$val['price'],2)>=$val['price']){
							continue;
						}
						//如果公众号房型名没有“房”字而携程有且两边都有字母，则补上.“间”与“房”视为相同
						preg_match_all("/[a-zA-Z]{1}/",$this->currcname,$arrc);
						$cnum = count($arrc[0]);
						preg_match_all("/[a-zA-Z]{1}/",$this->curriname,$arri);
						$inum = count($arri[0]);
						$cletter = mb_substr($this->currcname,mb_strlen($this->currcname)-$cnum,$cnum);
						$iletter = mb_substr($this->curriname,mb_strlen($this->curriname)-$cnum,$inum);
						if(preg_match ("/^[A-Za-z]/",$cletter)&&preg_match ("/^[A-Za-z]/",$iletter)){ 
							if(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '房')!==false&&mb_strpos($this->curriname, '间')===false){
								$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'间'.$iletter;
							}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '间')!==false&&mb_strpos($this->curriname, '房')===false){
								$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'房'.$iletter;
							}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '房')===false){
								$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'房'.$iletter;
							}elseif(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '间')===false){
								$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'间'.$iletter;
							}
						}else{
							//房型名不包含字母，两边都去掉“房”、“间”
							if(in_array(mb_substr($this->currcname, -1,1),array('间','房'))){
								$this->currcname = mb_substr($this->currcname,0,mb_strlen($this->currcname)-1);
							}
							if(in_array(mb_substr($this->curriname, -1,1),array('间','房'))){
								$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-1);
							}
						}
						$roomarr[] = $this->curriname;
						$issame = false;
						if(strnatcasecmp($this->currcname,$this->curriname)==0){
							$issame = true;
							$samename = false;
							$repeatroom[] = $this->curriname;
						}
						$reoutput[] = array(
							'trid' => $this->getCtripOriginal($v['nkey'],true),
							'ctrip_name'=>$this->getCtripOriginal($v['nkey']),
							'cname'=>$this->currcname,
							'ctrip_price'=>'¥'.number_format((float)$v['price'],2),
							'iwide_name'=>$val['price_name'],
							'iname'=>$this->curriname,
							'iwide_price'=>'¥'.number_format((float)$val['price'],2),
							'chajia'=>bcsub($v['price'],$val['price'],2),
							'book_status'=>$val['book_status'],
							'issame'=>$issame,
							'room_id'=>$val['room_id'],
							'price_code'=>$val['price_code'],
							);
						$ok = 1;
						unset($nomins[$k]);
					}
				}
				if($ok==0){
					if(mb_strpos($val['price_name'],'积分')===false){
						$output[] = array(
							'ctrip_name'=>'',
							'cname'=>'',
							'ctrip_price'=>'',
							'iwide_name'=>$val['price_name'],
							'iname'=>$this->curriname,
							'iwide_price'=>'¥'.number_format((float)$val['price'],2),
							'chajia'=>'',
							'book_status'=>'',
							'room_id'=>$val['room_id'],
							'price_code'=>$val['price_code'],
							);
						unset($noiwiderooms[$ke]);
					}
				}else{
					$isfor = 1;
					if(in_array($this->currcname,$roomarr)||in_array(strtolower($this->currcname),$roomarr)||in_array(strtoupper($this->currcname),$roomarr)){
						if($samename===false){
							foreach ($reoutput as $key => $value) {
								if($value['issame']===false){
									unset($reoutput[$key]);
								}
							}
						}
					}
					if(!empty($reoutput)&&is_array($reoutput)){
						$ismore = true;
						foreach ($reoutput as $key => $value) {
							if($value['chajia']>=0.00){
								$ismore = false;
							}
						}
						if($ismore===false){
							foreach ($reoutput as $key => $value) {
								$reoutput[$key]['ismore'] = 1;
								break;
							}
						}
					}
					$output = array_merge($output,$reoutput);
					unset($noiwiderooms[$ke]);
				}
			}
			if($isfor==0){
				MYLOG::w('该酒店无一匹配，name:'.$vh['name'].'，inter_id:'.$vh['inter_id'].'，hotel_id:'.$vh['hotel_id'],'paritys');
			}
			$renoiwiderooms = $noiwiderooms;
			foreach ($noiwiderooms as $ke=>$val) {
				foreach ($nomins as $k => $v) {					
					//兼容房型名中的"间"和"房"
					if(in_array(mb_substr($val['room_name'], -1,1),array('间','房'))&&in_array(mb_substr($v['room_name'], -1,1),array('间','房'))){
						$val['room_name'] = mb_substr($val['room_name'],0,mb_strlen($val['room_name'])-1).mb_substr($v['room_name'],-1,1);
					}
					if($val['room_name']==$v['room_name']){
						foreach ($output as $key => $value) {
							if($value['ctrip_name']==$this->getCtripOriginal($v['nkey'])&&$value['iwide_name']==''&&mb_strpos($val['price_name'],'积分')===false){
								//排除钟点/时租房
								$iszsr = false;
								foreach ($this->timerooms as $vt) {
									if((mb_strpos($value['ctrip_name'], $vt)!==false&&mb_strpos($value['price_name'], $vt)===false)||(mb_strpos($value['ctrip_name'], $vt)===false&&mb_strpos($value['price_name'], $vt)!==false)){
										$iszsr = true;
									}
								}
								if($iszsr===true){
									break;
								}
								if((mb_strpos($value['ctrip_name'], '日租')!==false&&mb_strpos($value['price_name'], '日租')===false)||(mb_strpos($value['ctrip_name'], '日租')===false&&mb_strpos($value['price_name'], '日租')!==false)){
									break;
								}
								$output[$key]['iwide_name'] = $val['price_name'];
								$output[$key]['iwide_price'] = '¥'.number_format((float)$val['price'],2);
								$output[$key]['chajia'] = bcsub($v['price'],$val['price'],2);
								$output[$key]['book_status'] = $val['book_status'];
								unset($renoiwiderooms[$ke]);
								break;
							}
						}
					}
				}
			}
			foreach ($renoiwiderooms as $nv){ 
				if(mb_strpos($nv['price_name'],'积分')!==false){
					$iwide_price = number_format($nv['price']).'积分';
				}else{
					$iwide_price = '¥'.number_format((float)$nv['price'],2);
				}
				$output[] = array(
					'ctrip_name'=>'',
					'cname'=>'',
					'ctrip_price'=>'',
					'iwide_name'=>$nv['price_name'],
					'iname'=>$this->curriname,
					'iwide_price'=>$iwide_price,
					'chajia'=>'',
					'book_status'=>$nv['book_status'],
					'room_id'=>$nv['room_id'],
					'price_code'=>$nv['price_code'],
					);					
			}
			foreach ($nomins as $vm) {
				$ctrip_price = '¥'.number_format((float)$vm['price'],2);
				$output[] = array(
					'trid' => $this->getCtripOriginal($vm['nkey'],true),
					'ctrip_name'=>$this->getCtripOriginal($vm['nkey']),
					'cname'=>$this->currcname,
					'ctrip_price'=>$ctrip_price,
					'iwide_name'=>'',
					'iname'=>'',
					'iwide_price'=>'',
					'chajia'=>'',
					);
			}

			foreach ($output as $ko => $vo) {
				// 剔除两边房型名不一样，公众号房型名又有在别的房型匹配中被因为房型名完全相同而匹配过
				if(!empty($vo['cname'])&&!empty($vo['iname'])&&$vo['cname']!=$vo['iname']&&in_array($vo['iname'],$repeatroom)){
					unset($output[$ko]);
				}	
			}
			//重置索引
			$output = array_values($output);

			$o = 1;
			$ciminprices = array();
			$zdian1 = true;//默认全是带钟点/时租或日租的房型
			$zdian2 = true;//默认全是带钟点/时租或日租的房型
			$zdianx1 = true;
			$zdianx2 = true;
			foreach ($output as $ko => $vo) {
				foreach ($this->timerooms as $vt) {
					if(!empty($vo['ctrip_name'])&&mb_strpos($vo['ctrip_name'],$vt)===false&&mb_strpos($vo['ctrip_name'],'日租')===false){
						$zdianx1 = false;
					}else{
						$zdianx1 = true;
					}
					if(!empty($vo['iwide_name'])&&mb_strpos($vo['iwide_name'],$vt)===false&&mb_strpos($vo['iwide_name'],'日租')===false){
						$zdianx2 = false;
					}else{
						$zdianx2 = true;
					}
				}
				if($zdianx1===false){
					$zdian1 = false;
				}
				if($zdianx2===false){
					$zdian2 = false;
				}
			}
			foreach ($output as $ko=>$vo) {
				if($zdian1){
					if(!empty($vo['ctrip_price'])){
						$ciminprices['cprices'][] = str_replace(',','',trim($vo['ctrip_price'],'¥'));
						$ciminprices['croominfo'][] = $vo['ctrip_name'];
					}
				}else{
					if(!empty($vo['ctrip_price'])&&mb_strpos($vo['ctrip_name'],'钟点')===false&&mb_strpos($vo['ctrip_name'],'时租')===false&&mb_strpos($vo['ctrip_name'],'半日')===false&&mb_strpos($vo['ctrip_name'],'日租')===false){
						$ciminprices['cprices'][] = str_replace(',','',trim($vo['ctrip_price'],'¥'));
						$ciminprices['croominfo'][] = $vo['ctrip_name'];
					}
				}
				if($zdian2){
					if(!empty($vo['iwide_price'])&&mb_strpos($vo['iwide_price'],'积分')===false){
						$ciminprices['iprices'][] = str_replace(',','',trim($vo['iwide_price'],'¥'));
						$ciminprices['iroominfo'][] = $vo['iwide_name'];
					}
				}else{
					if(!empty($vo['iwide_price'])&&mb_strpos($vo['iwide_price'],'积分')===false&&mb_strpos($vo['iwide_name'],'钟点')===false&&mb_strpos($vo['iwide_name'],'时租')===false&&mb_strpos($vo['iwide_name'],'半日')===false&&mb_strpos($vo['iwide_name'],'日租')===false){
						$ciminprices['iprices'][] = str_replace(',','',trim($vo['iwide_price'],'¥'));
						$ciminprices['iroominfo'][] = $vo['iwide_name'];
					}
				}
				if($ko>=1&&$vo['ctrip_name']==$output[$ko-1]['ctrip_name']&&!empty($vo['ctrip_name'])){
					$o++;
					if($ko==(count($output)-1)){
						$output[$ko-$o+1]['cop'] = $o;
					}
				}else{
					if($ko==0){
						$output[0]['cop'] = $o;
					}else{
						$output[$ko-$o]['cop'] = $o;
					}
					$o=1;
				}
			}
			// var_dump($ciminprices);exit;
			$unshift = array();
			if(!empty($ciminprices['cprices'])&&!empty($ciminprices['iprices'])&&is_array($ciminprices['cprices'])&&is_array($ciminprices['iprices'])){
				$cprice = min($ciminprices['cprices']);
				$croominfo = $ciminprices['croominfo'][array_search($cprice,$ciminprices['cprices'])];
				$iprice = min($ciminprices['iprices']);
				$iroominfo = $ciminprices['iroominfo'][array_search($iprice,$ciminprices['iprices'])];
				$unshift = array(
					'ctrip_name'=>'最低价房型：'.$croominfo,
					'ctrip_price'=>'¥'.$cprice,
					'iwide_name'=>'最低价房型：'.$iroominfo,
					'iwide_price'=>'¥'.$iprice,
					'chajia'=>bcsub($cprice,$iprice,2),
					'book_status'=>'',
					);
			}
			$lists[$vh['name']] = $output;
			//房型匹配入库
			if(!empty($output)){
				$partiesSql = 'INSERT INTO '.$this->price_db->dbprefix(self::TAB_PRP).' VALUES ';
				$datetime = date('Y-m-d H:i:s');
				$date = date('Y-m-d');
				$parties = '';
				foreach ($output as $ko => $vo) {
					if($vo['iwide_name']!=''){
						$trid = 0;
						if(!empty($vo['trid'])){
							$trid = $vo['trid'];
						}
						$price_code = 0;
						if(!empty($vo['price_code'])){
							$price_code = $vo['price_code'];
						}
						$parties .= "(null,'$inter_id','{$vh['hotel_id']}','{$vo['room_id']}','$price_code','$third_type','{$vh['ctrip_id']}','$trid','$datetime','$date'),";
					}
				}

				if(!empty($parties)){
					$partiesSql .= rtrim($parties,',');
					$rows = $this->price_db->query($partiesSql);	
					if($rows===false){
						MYLOG::w('房型匹配结果入库失败|'.$parties,'paritys');
					}
				}
			}
			// if(!empty($unshift)){
			// 	array_unshift($lists[$vh['name']],$unshift);
			// }
			$this->ctriprooms = array();
			// var_dump($lists);exit;
		}
		// return $lists;
	}

	/*
	 * 取回携程初始房型属性
	 * @param1 String $roomname 需要找回的名字
	 * @param2 boolean $bool true表示获取第三方酒店房型表id,默认false
	 * return String 初始属性
	 */
	private function getCtripOriginal($nkey,$bool = false){
		if($nkey===null||$nkey===false){
			return false;
		}
		foreach ($this->ctriprooms as $key => $value) {
			if($value['nkey']==$nkey){
				if(!empty($value['trid'])){
					if($bool===true){
						return $value['trid'];
					}
					unset($value['trid']);
				}
				unset($value['price']);
				$gift = '';
				if(!empty($value['gift'])){
					$gift = '('.$value['gift'].')';
					unset($value['gift']);
				}
				unset($value['nkey']);
				$res = implode('--',$value);
				return $res?$res.$gift:false;				
			}
		}
		MYLOG::w('获取携程初始房型属性失败，roomname:'.$roomname,'paritys');
	}

	/*
	 * 截取房型内容
	 * param1 $htmlfile 网页文本字符串
	 * return Array 对应携程酒店房型属性
	 */
	private function getCtriprooms($htmlfile){
		if(file_exists($this->htmldir)){
			$handler = @fopen($this->htmldir.$htmlfile, 'r');
			if(!$handler){
				return 'ERROR:File cannot open';
			}
			$gistate = 0;
			$s = '';
			while(!feof($handler)&&$line = fgets($handler,4096)){
				if(mb_strpos($line,'<div id="ComboInfo"></div>')!==false){
					break;
				}
				switch($gistate){
					case 0:
						$ptag = '<h1>';
						$ftag = '</h1>';
						$p = mb_strpos($line,$ptag);
						$fp = mb_strpos($line,$ftag);
						if($p!==false&&$fp!==false){
							$p += mb_strlen($ptag);
							$s .= 'hotelname::'.mb_substr($line,$p,$fp-$p).',,';
							$gistate = 1;
						}
						break;
					case 1:
						$ptag = 'onclick="HotelRoom.onNameClick(this)">';
						$p = mb_strpos($line,$ptag);
						if($p!==false){
							$gistate = 2;
						}
						break;
					case 2:
						$s .= 'roomname::'.$line.',,';
						$gistate = 3;
						break;
					case 3:
						$ptag = 'onclick="HotelRoom.onNameClick(this)">';
						$p = mb_strpos($line,$ptag); 
						if($p!==false){
							$gistate = 2;
						}else{
							$ptag = '<span class="room_type_name">';
							$ftag = '</span>';
							$p = mb_strpos($line,$ptag);
							$fp = mb_strpos($line,$ftag);
							if($p!==false&&$fp!==false){
								$p += mb_strlen($ptag);
								$mb = mb_substr($line,$p,$fp-$p);
								if($mb==strip_tags($mb)){
									$s .= 'remark::'.$mb.',,';
								}else{
									$s .= 'remark::'.strip_tags($mb).',,';
								}
								$gistate = 4;
							}
						}
						break;
					case 4:
						$ptag = '<span class="label_onsale_orange " data-role="jmp"';
						$ftag = '>';
						$fftag = '</span>';
						$p = mb_strpos($line, $ptag);
						$fp = mb_strpos($line, $ftag);
						$ffp = mb_strpos($line, $fftag);
						if($p!==false&&$fp!==false&&$ffp!==false){
							$s .= 'gift::'.mb_substr($line, $fp+1,$ffp-$fp-1).',,';
							$gistate = 4;
						}else{
							$ptag = '<td>';
							$ftag = '</td>';
							$p = mb_strpos($line,$ptag);
							$fp = mb_strpos($line,$ftag);
							if($p!==false&&$fp!==false){
								$p += mb_strlen($ptag);
								$s .= 'bed::'.mb_substr($line,$p,$fp-$p).',,';
								$gistate = 5;
							}
						}
						break;
					case 5:
						$ptag = '<td>';
						$ftag = '</td>';
						$p = mb_strpos($line,$ptag);
						$fp = mb_strpos($line,$ftag);
						if($p!==false&&$fp!==false){
							$p += mb_strlen($ptag);
							$s .= 'breakfast::'.mb_substr($line,$p,$fp-$p).',,';
							$gistate = 6;
						}
						break;
					// case 6:
					// 	$ptag = '<span class="base_price"><dfn>';
					// 	$ftag = '</dfn>';
					// 	$p = mb_strpos($line,$ptag);
					// 	$fp = mb_strpos($line,$ftag);
					// 	if($p!==false&&$fp!==false){
					// 		$p += mb_strlen($ptag);
					// 		$s .= 'price:'.mb_substr($line,$p,$fp-$p);
					// 		$fftag = '</span>';
					// 		$ffp = mb_strpos($line,$fftag);
					// 		if($ffp!==false){
					// 			$fp += mb_strlen($ftag);
					// 			$isprice = mb_substr($line,$fp,$ffp-$fp);
					// 			$s .= mb_substr($line,$fp,$ffp-$fp).';';
					// 			$gistate = 3;
					// 		}
					// 	}
					case 6:
						$ptag = '<del class="rt_origin_price"><dfn>';
						$ftag = '</dfn>';
						$p = mb_strpos($line,$ptag);
						$fp = mb_strpos($line, $ftag);
						if($p!==false&&$fp!==false){
							$fftag = '</del>';
							$pptag = '<span class="label_onsale_txt"><i>';
							$ffftag = '</span>';
							$ffp = mb_strpos($line, $fftag);
							$fffp = mb_strpos($line, $pptag);
							$fpr = mb_strrpos($line, $ftag);
							$ffffp = mb_strpos($line, $ffftag);
							if($ffp!==false&&$fffp!==false&&$fpr!==false&&$ffffp!==false){
								$flen = mb_strlen($ftag);
								$fp += $flen;
								$originprice = mb_substr($line, $fp,$ffp-$fp);
								$fpr += $flen;
								$saleprice = mb_substr($line, $fpr,$ffffp-$fpr);
								$isprice = $originprice-$saleprice;
								$s .= 'price::¥'.$isprice.';;';
								$gistate = 3;
							}else{
								$flen = mb_strlen($ftag);
								$fp += $flen;
								$isprice = mb_substr($line, $fp,$ffp-$fp);
								$s .= 'price::¥'.$isprice.';;';
								$gistate = 3;
							} 
						}else{
							// $ptag = '<a data-ismember="false" class="btn_buy" data-price="';
							$ptag = '<a data-ismember="false" class="btns_base22" data-price="';
							$ftag = '" rel="nofollow"';
							$p = mb_strpos($line,$ptag);
							$fp = mb_strpos($line,$ftag);
							if($p!==false&&$fp!==false){
								$p += mb_strlen($ptag);
								$s .= 'price::¥'.mb_substr($line,$p,$fp-$p).';;';
								$gistate = 3;
							}
						}
						break;
					default:
						break;
				}
			}
			fclose($handler);
			$m = explode(';;',htmlspecialchars_decode(rtrim($s,';;')));
			if(mb_strpos(end($m),'{')!==false||mb_strpos(end($m),'$')!==false||mb_strpos(end($m),'}')!==false){
				unset($m[count($m)-1]);
			}
			$res = array();
			$p = 0;
			$roomname = '';
			if(!empty($m)&&is_array($m)){
				foreach ($m as $key => $value) {
					$arr1 = explode(',,',$value);
					if(!empty($arr1)&&is_array($arr1)){
						foreach ($arr1 as $ke => $val) {
							$arr2 = explode('::',$val);
							if(!empty($arr2[0])&&!empty($arr2[1])){
								if($arr2[0]=='roomname'){
									$roomname = $arr2[1];
								}
								if(mb_strpos($value,'remark')!==false&&mb_strpos($value,'套票')!==false){
									continue;
								}else{
									if($arr2[0]=='hotelname'){
										$res['hotelname'] = $arr2[1];
									}else{
										$res['room'][$p]['roomname'] = $roomname;
										$res['room'][$p][$arr2[0]] = $arr2[1]; 
									}
								}
							}
						}
					}
					$p++;
				}
			}
			return $res;
		}else{
			return 'ERROR:File do not exist or cannot open:'.$this->htmldir.$htmlfile;
		}
	}

	/*
	 * 刷选酒店同一房型中的最低价格
	 * @param1 Array $rooms 酒店所有房型数据
	 * return Array $brushrooms 刷选后的酒店房型数据
	 */
	private function brushRooms($rooms){
		if(is_array($rooms)){
			$brushrooms = trimArray($rooms);
		}
		$prices = array();
		foreach ($brushrooms as $k => $v) {
			if($k=='room'){
				foreach ($v as $key => $value) {
					$value['nkey'] = $key;
					$this->ctriprooms[] = $value;
					if(!empty($value['bed'])&&mb_strpos($value['bed'],'/')!==false){
						$biganddouble = explode('/',$value['bed']);
						$big = $biganddouble[0].'床';
						$double = $biganddouble[1].'床';
						$prices[] = array(
							'roomname'=>isset($value['roomname'])?$value['roomname']:'',
							'bed'=>$big,'price'=>isset($value['price'])?ltrim($value['price'],'¥'):0.00,
							'breakfast'=>isset($value['breakfast'])?$value['breakfast']:'',
							'nkey'=>$value['nkey'],
							'remark'=>isset($value['remark'])?$value['remark']:''
							);
						$prices[] = array(
							'roomname'=>isset($value['roomname'])?$value['roomname']:'',
							'bed'=>$double,'price'=>isset($value['price'])?ltrim($value['price'],'¥'):0.00,
							'breakfast'=>isset($value['breakfast'])?$value['breakfast']:'',
							'nkey'=>$value['nkey'],
							'remark'=>isset($value['remark'])?$value['remark']:''
							);
					}else{
						$bed = !empty($value['bed'])?$value['bed']:'';
						$prices[] = array(
							'roomname'=>isset($value['roomname'])?$value['roomname']:'',
							'bed'=>$bed,'price'=>isset($value['price'])?ltrim($value['price'],'¥'):0.00,
							'breakfast'=>isset($value['breakfast'])?$value['breakfast']:'',
							'nkey'=>$value['nkey'],
							'remark'=>isset($value['remark'])?$value['remark']:''
							);
					}
				}
			}
		}
		$mins = array();
		foreach ($prices as $ke => $val) {
			$mins[$val['roomname'].$val['bed'].$val['breakfast']][$val['nkey']] = $val['price'];
			$mins[$val['roomname'].$val['bed'].$val['breakfast']]['room_name'] = $val['roomname'];
			if(mb_strpos($val['remark'],'钟点')!==false){
				if(isset($mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price'])){
					if($mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price']['price']>$val['price']){
						$mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price'] = array('price'=>$val['price'],'nkey'=>$val['nkey']);
					}
				}else{
					$mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price'] = array('price'=>$val['price'],'nkey'=>$val['nkey']);
				}
			}
		}
		$minarr = array();
		foreach ($mins as $mk => &$mv) {
			$room_name = $mv['room_name'];
			unset($mins[$mk]['room_name']);
			if(isset($mv['zd_price'])){
				$minarr[] = array('roomname'=>$mk,'price'=>$mv['zd_price']['price'],'room_name'=>$room_name,'nkey'=>$mv['zd_price']['nkey']);
				unset($mins[$mk][$mv['zd_price']['nkey']]);
				unset($mins[$mk]['zd_price']);
			}
			if(!empty($mv)){
				asort($mv);
				$minvalue = reset($mv);
				$minkey = key($mv);
				$minarr[] = array('roomname'=>$mk,'price'=>$minvalue,'room_name'=>$room_name,'nkey'=>$minkey);
			}
		}
		return $minarr;
	}

	/*
	 * 比较携程网房型和iwide房型是否是同一种
	 * @param1 携程网房型名字 
	 * @param2 iwide房型名字
	 * return boolean true相同，false不同
	 */
	private function analysisRoom($ctripname,$iwidename){
		if($ctripname&&$iwidename){
			$cp = $this->splitRoomName(trim($ctripname));
			$iwp = $this->splitRoomName(trim($iwidename));
			$status = false;
			$roomkeys = array_keys($this->roomkeys);
			foreach ($roomkeys as $rval) {
				if(isset($cp[$rval])&&isset($iwp[$rval])){
					$cp_int = array_intersect($cp[$rval], $this->prior);
					$iwp_int = array_intersect($iwp[$rval], $this->prior);
				}
				if(!empty($cp_int)&&!empty($iwp_int)){
					return true;
				}
			}
			if($this->currcname==$this->curriname){
				foreach ($this->ignore as $vi) {
					if(mb_strpos($this->currcname,$vi)!==false){
						return true;
					}
				}
				$status = true;
			}elseif(mb_strpos($this->curriname,$this->currcname)!==false){
				$status = true;
			}else{
				//如果两边有一边除房型名以外，没有其他属性或两边都没有，则判断若一边包含一边则直接匹配，否则继续往下走
				if((!isset($cp['refirst'])&&!isset($cp['second'])&&!isset($cp['third'])&&!isset($cp['fourth']))||(!isset($iwp['refirst'])&&!isset($iwp['second'])&&!isset($iwp['third'])&&!isset($iwp['fourth']))){
					//如果公众号房型名没有“房”字而携程有且两边都有字母，则补上.“间”与“房”视为相同
					preg_match_all("/[a-zA-Z]{1}/",$this->currcname,$arrc);
					$cnum = count($arrc[0]);
					preg_match_all("/[a-zA-Z]{1}/",$this->curriname,$arri);
					$inum = count($arri[0]);
					$cletter = mb_substr($this->currcname,mb_strlen($this->currcname)-$cnum,$cnum);
					$iletter = mb_substr($this->curriname,mb_strlen($this->curriname)-$cnum,$inum);
					if(preg_match ("/^[A-Za-z]/",$cletter)&&preg_match ("/^[A-Za-z]/",$iletter)){ 
						if(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '房')!==false&&mb_strpos($this->curriname, '间')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'间'.$iletter;
						}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '间')!==false&&mb_strpos($this->curriname, '房')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'房'.$iletter;
						}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '房')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'房'.$iletter;
						}elseif(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '间')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'间'.$iletter;
						}
					}
					if((mb_strpos($this->curriname,$this->currcname)!==false)||(mb_strpos($this->currcname,$this->curriname)!==false)){
						return true;
					}
				}			
				if(isset($cp['first'])&&isset($iwp['first'])&&isset($cp['refirst'])&&isset($iwp['refirst'])){
					$cp1 = implode('', $cp['first']);
					$iwp1 = implode('', $iwp['first']);
					$recp1 = implode('', $cp['refirst']);
					$reiwp1 = implode('', $iwp['refirst']);
					similar_text($cp1.$recp1, $iwp1.$reiwp1,$per1);
					similar_text($cp1, $iwp1,$per2);
					similar_text($recp1, $reiwp1,$per3);
					if($per1==100){
						$status = true;
					}elseif($per2==100&&$per3>=50){
						$status = true;
					}
				}elseif(isset($cp['first'])&&isset($iwp['first'])){
					$cp1 = implode('', $cp['first']);
					$iwp1 = implode('', $iwp['first']);
					if(in_array($cp1, array_keys($this->special))){
						$wd = $this->special[$cp1];
						if($wd==$iwp1){
							$status = true;
						}
					}elseif (in_array($iwp1, array_keys($this->special))) {
						$wd = $this->special[$iwp1];
						if($wd==$cp1){
							$status = true;
						}
					}else{	
						similar_text($cp1, $iwp1,$per2);
						if($per2==100){
							$status = true;
							if((isset($cp['refirst'])&&array_intersect($cp['refirst'],$this->norefirst))||(isset($iwp['refirst'])&&array_intersect($iwp['refirst'],$this->norefirst))){
								$status = false;
							}
						} 
					}						
				}elseif(isset($cp['refirst'])&&isset($iwp['refirst'])){
					$recp1 = implode('', $cp['refirst']);
					$reiwp1 = implode('', $iwp['refirst']);
					similar_text($recp1, $reiwp1,$per3);
					preg_match_all("/[a-zA-Z]{1}/",$this->currcname,$arrc);
					$cnum = count($arrc[0]);
					preg_match_all("/[a-zA-Z]{1}/",$this->curriname,$arri);
					$inum = count($arri[0]);
					$cletter = mb_substr($this->currcname,mb_strlen($this->currcname)-$cnum,$cnum);
					$iletter = mb_substr($this->curriname,mb_strlen($this->curriname)-$cnum,$inum);
					if(preg_match ("/^[A-Za-z]/",$cletter)&&preg_match ("/^[A-Za-z]/",$iletter)){
						if($per3==100&&$cletter==$iletter){
							$status = true;
							if(isset($cp['first'])||isset($iwp['first'])){
								$status = false;
							}
						}
					}else{
						if($per3==100){
							$status = true;
							if(isset($cp['first'])||isset($iwp['first'])){
								$status = false;
							}
						}
					}
				}elseif(!isset($cp['first'])&&!isset($iwp['first'])&&!isset($cp['refirst'])&&!isset($iwp['refirst'])){
					$status = true;
				}else{
					$status = false;
				}
			}
			//前面匹配为true，判断如果一边带“标间”且没有床型属性,而另一边又不带“双床”，则到此步骤为不匹配
			if($status){
				if(isset($cp['second'])&&!isset($iwp['second'])&&!in_array('双床',$cp['second'])&&mb_strpos($iwidename,'标间')!==false){
					$status = false;
				}elseif(isset($iwp['second'])&&!isset($cp['second'])&&!in_array('双床',$iwp['second'])&&mb_strpos($ctripname,'标间')!==false){
					$status = false;
				}
			}
			//两边房型名带有“标准”或“标间”则到此步骤为匹配
			if(!$status){
				if(isset($cp['first'])&&isset($iwp['first'])){
					$imcp = implode('', $cp['first']);
					$imiwp = implode('', $iwp['first']);
					similar_text($imcp, $imiwp,$imper);
					if($imper==100){
						if((mb_strpos($ctripname, '标准')!==false&&mb_strpos($iwidename, '标间')!==false)||(mb_strpos($iwidename, '标准')!==false&&mb_strpos($ctripname, '标间')!==false)){
							$status = true;
						}
					}
				}elseif(!isset($cp['first'])&&!isset($iwp['first'])){
					if((mb_strpos($ctripname, '标准')!==false&&mb_strpos($iwidename, '标间')!==false)||(mb_strpos($iwidename, '标准')!==false&&mb_strpos($ctripname, '标间')!==false)){
						$status = true;
					}
					if(mb_strpos($iwidename, '标准')!==false&&(mb_strpos($ctripname, '双床')!==false||mb_strpos($ctripname, '双人')!==false)){
						$status = true;
					}
				}
			}
			if(isset($cp['second'])&&isset($iwp['second'])){
				if($status){
					foreach ($cp['second'] as $cpf) {
						foreach ($iwp['second'] as $ipf) {
							if($cpf==$ipf){
								$status = true;
							}elseif(($cpf=='大床'||$cpf=='单床')&&($ipf=='大床'||$ipf=='单床')){
								$status = true;
							}else{
								$status = false;
								break;
							}
						}
						if($status){
							break;
						}
					}
				}
			}else{
				$status = $status?true:false;
			}
			if(isset($cp['third'])&&isset($iwp['third'])){
				if($status){
					foreach ($cp['third'] as $cpf) {
						foreach ($iwp['third'] as $ipf) {
							if($cpf==$ipf){
								$status = true;
							}else{
								$status = false;
								break;
							}
						}
						if(!$status){
							break;
						}
					}
				}
			}else{
				$status = $status?true:false;
			}
			if(isset($cp['fourth'])&&isset($iwp['fourth'])){
				if($status){
					if($cp['fourth'][0] == $iwp['fourth'][0]){
						$status = true;
					}else{
						$status = false;
					}
				}
			}elseif(!isset($cp['fourth'])&&!isset($iwp['fourth'])&&$status){
				$status = true;
			}else{
				$status = false;
			}
			return $status;
		}
		return false;
	}

	/*
	 * 房型分词
	 * @param1 $roomname 房型名称
	 * return Array 分词结果 
	 */
	private function splitRoomName($roomname){
		$item = array();
		foreach ($this->roomkeys as $k=>$val) {
			foreach ($val as $rk=>$rkeys) {
				if($k=='second'){
					$key1 = mb_substr($rkeys,0,1);
					if(($pos = mb_strpos($roomname,'('.$key1.')'))!==false||($pos = mb_strpos($roomname,'（'.$key1.'）'))!==false){
						$roomname = mb_substr($roomname,0,$pos).$key1.'床'.mb_substr($roomname,$pos+mb_strlen('('.$key1.')'));
					}
					if(($pos = mb_strpos($roomname,$key1.'人'))!==false&&mb_strpos($roomname, $key1.'床')===false){
						$roomname = mb_substr($roomname, 0,$pos).$key1.'床'.mb_substr($roomname,$pos+mb_strlen($key1.'人'));
					}
				}
				if($k=='third'){
					$key2 = $rk.'早';
					if(($pos = mb_strpos($roomname,$key2))!==false&&mb_strpos($roomname,$rkeys)===false){
						$roomname = mb_substr($roomname,0,$pos).$rkeys.mb_substr($roomname,$pos+mb_strlen($key2));
					}
				}
				if(mb_strpos($roomname,$rkeys)!==false){
					$item[$k][] = $rkeys;
				}
			}
		}
		return $item;
	}

	/*
	 * 计算倒挂率并入库
 	 */
	public function calDownRate($inter_id,$third_type){
		if(empty($inter_id)||empty($third_type)){
			return false;
		}
		$lists = $this->getParitys($inter_id,$third_type);
		$date = date('Y-m-d');
		$datetime = date('Y-m-d H:i:s');
		$datas = array();
		// 计算倒挂率
		foreach ($lists as $k => $v) {
			$n= 0;
			$m = 0;
			foreach ($v as $key => $value) {
				if($value['iwide_name']!=''&&$value['ctrip_name']!=''){
					$n++;
				}
				if($value['ctrip_name']!=''&&$value['iwide_name']!=''&&$value['chajia']<0){
					$m++;
				}
			}
			$datas[] = array(
				'addtime' => $datetime,
				'adddate' => $date,
				'inter_id' => $inter_id,
				'hotel_id' => !empty($v[0]['hotel_id'])?$v[0]['hotel_id']:0,
				'down_rate' => $n>0?sprintf("%1\$.2f",round(($m/$n)*100,2)):'0.00',
				);
		}
		// 入库
		if(!empty($datas)){
			$res = $this->price_db->insert_batch(self::TAB_PHC,$datas);
			if(!$res){
				MYLOG::w('倒挂率计算入库失败'.$inter_id,'paritys');
			}
		}
	}
}

	/*
	 * 去除数组所有元素两边的空格
	 * @param1 Array $input 要处理的数组
	 * return Array 返回处理过的数组
	 */
	function trimArray($input){
		if(!is_array($input)){
			return trim($input);
		}
		return array_map('trimArray',$input);
	}