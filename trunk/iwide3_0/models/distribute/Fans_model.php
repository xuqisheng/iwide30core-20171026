<?php
/**
 * @author OuNianfeng
* @package models\distribute
*/
class Fans_model extends MY_Model{

	/**
	 * @todo 根据员工openid查询我的粉丝关注基本信息
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid
	 * @param int $offset
	 * @param int $limit
	 */
	public function get_my_fans_by_openid_lite($inter_id,$openid,$offset=NULL,$limit=20){
		$this->load->model('distribute/staff_model');
		$saler_base_info = $this->staff_model->get_saler_base_info($inter_id,$openid);
		if($saler_base_info && isset($saler_base_info['qrecode_id'])){
			return $this->get_my_fans_by_saler_id_lite($inter_id, $saler_base_info['qrcode_id'], $offset, $limit);
		}else
			return false;
	}
	/**
	 * @todo 根据员工分销id查询我的粉丝关注基本信息
	 * @param string $inter_id 公众号唯一识别号
	 * @param int $saler_id
	 * @param int $offset
	 * @param int $limit
	 */
	public function get_my_fans_by_saler_id_lite($inter_id,$saler_id,$offset=NULL,$limit=20){
		$this->_update_my_fans_amount($inter_id, $saler_id);
		$this->_db('iwide_r1')->where(array('source'=>$saler_id,'inter_id'=>$inter_id));
		if(!is_null($offset))
			$this->_db('iwide_r1')->limit($offset,$limit);
			return $this->_db('iwide_r1')->get('fans_subs')->result();
	}
	/**
	 * @todo 根据员工openid查询我的粉丝关注基本信息
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid
	 * @param int $offset
	 * @param int $limit
	 */
	public function get_my_fans_by_openid($inter_id,$saler_id,$offset=0,$limit=20,$order_by='event_time',$order_type='ASC'){
		$this->load->model('distribute/staff_model');
		$saler_base_info = $this->staff_model->get_saler_base_info($inter_id,$openid);
		if($saler_base_info && isset($saler_base_info['qrecode_id'])){
			return $this->get_my_fans_by_saler_id($inter_id,$saler_id,$offset=0,$limit=20,$order_by='event_time',$order_type='ASC');
		}else
			return false;
	}
	/**
	 * @todo 根据员工分销id查询我的粉丝关注基本信息
	 * @param string $inter_id 公众号唯一识别号
	 * @param int $saler_id
	 * @param int $offset
	 * @param int $limit
	 */
	public function get_my_fans_by_saler_id($inter_id,$saler_id,$offset=0,$limit=20,$order_by='event_time',$order_type='ASC'){
		$this->_update_my_fans_amount($inter_id, $saler_id);
		$sql = "SELECT fs.id,fs.event_time,fs.openid,fs.inter_id,fs.hotel_id,fs.amount,fs.last_order_date,f.headimgurl,f.nickname FROM iwide_fans_subs fs INNER JOIN iwide_fans f ON fs.inter_id=f.inter_id AND fs.openid=f.openid WHERE fs.inter_id=? AND fs.source=? ORDER BY $order_by $order_type LIMIT ?,?";
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$saler_id,$offset,$limit))->result_array();
	}
	/**
	 * @todo 查询粉丝归属、关注时间等信息
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid
	 */
	public function get_fans_beloning($inter_id,$openid){
		$this->_db('iwide_r1')->where(array('openid'=>$openid,'inter_id'=>$inter_id));
		$this->_db('iwide_r1')->limit(1);
		return $this->_db('iwide_r1')->get('fans_subs')->row();
	}
	/**
	 * 根据粉丝ID取粉丝详细信息
	 * @param string $inter_id 公众号唯一识别号
	 * @param int $fans_id
	 * @return result row
	 */
	public function get_fans_info_by_id($inter_id,$fans_id,$f_key='id',$continue = TRUE){
		$sql = 'SELECT fs.id,fs.amount,fs.event_time,fs.source,fs.hotel_id,fs.last_order_date,f.openid,f.nickname,f.sex,f.province,f.city,f.country,f.headimgurl,f.privilege,f.unionid,f.subscribe_time,f.inter_id,f.fans_key';
		$sql .= ' FROM iwide_fans_subs fs INNER JOIN iwide_fans f ON fs.openid=f.openid AND fs.inter_id=f.inter_id WHERE fs.'.$f_key.'=? AND fs.inter_id=? AND fs.`event`=2 LIMIT 1';
		$res = $this->_db('iwide_r1')->query ( $sql, array ( $fans_id, $inter_id ) )->row_array();
		if((!isset($res['headimgurl']) || empty($res['headimgurl'])) && (isset($res['openid']) && !empty($res['openid'])) && $continue){
			$this->load->model('wx/Publics_model');
			$this->Publics_model->update_wxuser_info($inter_id,$res['openid']);
			return $this->get_fans_info_by_id($inter_id, $fans_id,$f_key,FALSE);
		}
		return $res;
	}

	/**
	 * 取粉丝的基本信息
	 * @param string $inter_id
	 * @param string $openid
	 * @return Sql-Query-Row
	 */
	public function get_fans_info_by_openid($inter_id,$openid){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		$this->_db('iwide_r1')->limit(1);
		return $this->_db('iwide_r1')->get('fans')->row();
	}

	/**
	 * 取分销2.0上迁移过来的尚未绑定OPENID的分销员数据
	 * @param string $inter_id 公众号唯一识别号
	 */
	public function get_unbind_staffs($inter_id){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'is_distributed'=>1,'openid'=>'','status'=>0));
		return $this->_db('iwide_r1')->get('hotel_staff');
	}

	/**
	 * 更新分销员粉丝消费金额
	 * @param string $inter_id 公众号唯一识别号
	 * @param int $saler 分销号
	 */
	private function _update_my_fans_amount($inter_id,$saler){
		$sql = 'UPDATE iwide_fans_subs fs,(SELECT SUM(grade_total) totals,inter_id,saler,grade_openid
FROM iwide_distribute_grade_all WHERE inter_id=? AND (`status`=1 OR `status`=2) AND saler=? GROUP BY grade_openid) ga
SET fs.amount=ga.totals
WHERE fs.openid=ga.grade_openid AND fs.inter_id=ga.inter_id AND fs.source=ga.saler AND fs.inter_id=? AND fs.source=?';
		return $this->_db('iwide_rw')->query($sql,array($inter_id,$saler,$inter_id,$saler));
	}

	/**
	 * 粉丝关注分销记录
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid
	 * @param int $source 关注来源（二维码ID）
	 * @param int $event 事件类型  1|取消关注，2|关注
	 * @param int $fans_id 粉丝关注编号
	 * @param string $event_time 关注时间
	 * @return boolean
	 */
	public function mark_fans_grades($inter_id,$openid,$source,$event,$fans_id,$event_time = NULL){
		if($event == 1){
			return $this->_unsubcribe($inter_id, $openid);
		}
		if($event == 2){
			$club_source = $source;
			//检验是否为社群客发展粉丝
			$this->load->model ( 'club/Clubs_model' );
			$check=$this->Clubs_model->getSalerByClubQrcode($inter_id,$source);

			if($check && !empty($check)){
				$club_source=$source;
				if($check['is_grade']==1){    //检查粉丝归属开关
					$source=$check['qrcode_id'];
				}else{
					$this->db->insert('weixin_text',array('content'=>'club_is_grade+'.$inter_id.'+'.$source,'edit_date'=>date('Y-m-d H:i:s')));
				}
			}else{
				$club_source=NULL;
			}
			//检查结束
			// 			$this->load->model('distribute/staff_model');
			// 			$saler_info = $this->staff_model->get_my_base_info_saler ( $inter_id, $source );

			$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$source));
			$this->_db('iwide_r1')->limit(1);
			$saler_info = $this->_db('iwide_r1')->get('hotel_staff')->row_array();
			if(!isset($saler_info['hotel_id'])) $saler_info['hotel_id'] = -1;


			if($this->can_renew_sub_info($inter_id, $openid)){
				$this->load->model('distribute/Grades_model');

				$this->_create_fans_sub_info($inter_id,$openid,$source,$saler_info['hotel_id'],$event_time,$club_source);

				//取粉丝关注绩效配置
				$settings = $this->Grades_model->get_grades_settings($inter_id,1);

				$params['inter_id'] = $inter_id;
				$params['grade_id'] = $fans_id;
				$params['saler']    = $club_source;
				$params['grade_id_name'] = 'id';
				$params['grade_table']   = 'iwide_fans_sub_log';
				$params['product']       = '粉丝关注';
				$params['order_id']      = $fans_id;
				$params['grade_openid']  = $openid;
				$params['order_amount']  = 0;
				if(isset($settings->excitation_type) && $settings->excitation_type == 2){
					$params['status'] = 6;
				}else{
					$params['status'] = 1;
				}
				if(isset($settings->excitation_type)){
					$params['grade_rate_type']   = $settings->excitation_type;
					$params['grade_amount_rate'] = $settings->excitation_value;
				}
				if(empty($saler_info['hotel_id']))
					$saler_info['hotel_id'] = -1;

					$params['grade_amount'] = 0;
					$params['grade_total']  = isset($settings->excitation_value) ? $settings->excitation_value : 0;
					$params['hotel_id']     = $saler_info['hotel_id'];
					$params['fans_hotel']   = $saler_info['hotel_id'];
					$params['order_status'] = 1;
					$params['name'] = isset($saler_info['name']) ? $saler_info['name'] : '';
					// 				var_dump($params);die;
					return $this->Grades_model->_create_grade($params);
			}else{
				//分销员工二维码关注，发送重复关注信息
				if($source > 0 && !empty($saler_info['openid'])){
					$this->load->model('distribute/Distribute_notice_model');

					$this->_db('iwide_r1')->where(array('openid'=>$openid,'inter_id'=>$inter_id));
					$this->_db('iwide_r1')->limit(1);
					$fans_info = $this->_db('iwide_r1')->get('fans')->row();
					$nickname = isset($fans_info->nickname) ? $fans_info->nickname : '';
					$params = array('saler' => $club_source,'openid'=>$saler_info['openid'],'nickname'=>$nickname,'inter_id'=>$inter_id);
					$this->Distribute_notice_model->create_dup_subscribe_notice($params);
				}
				return $this->_fans_sub_update($inter_id, $openid);
			}
		}else{
			return false;
		}
	}

	/**
	 * 是否新关注粉丝
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid OPENID
	 * @return boolean
	 */
	private function is_fans_new_subcribe($inter_id,$openid){
		$sql = "SELECT IFNULL(COUNT(id), 0) `nums` FROM `iwide_fans_sub_log` WHERE `inter_id` = ? AND `openid` = ? AND `event` = 2";
		$dis_config = json_decode($this->get_redis_key_status('FANS_SOURCE_INFO_UPDATE'.$inter_id));
		$params = array($inter_id,$openid);
		if($dis_config && $dis_config->do_update){
			$sql .= ' AND `source`>0';
			if(!empty($dis_config->time_before)){
				$sql .= ' AND `event_time`<=?';
				$params[] = $dis_config->time_before;
			}
		}
		$query = $this->_db('iwide_r1')->query($sql,$params)->row()->nums < 2;
		return $query;
	}
	private function can_renew_sub_info($inter_id, $openid) {
		$sql = "SELECT * FROM `iwide_fans_subs` WHERE `inter_id` = ? AND `openid` = ? LIMIT 1";
		$dis_config = json_decode ( $this->get_redis_key_status ( 'FANS_SOURCE_INFO_UPDATE' . $inter_id ) );
		$params = array ( $inter_id, $openid );
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params );
		if ($query->num_rows () > 0) {
			$query = $query->row ();
			if ($dis_config && $dis_config->do_update) {
				if (! empty ( $dis_config->time_before )) {
					return $query->source < 1 && strtotime ( $dis_config->time_before ) - strtotime ( $query->event_time ) >= 0;
				} else
					return $query->source < 1;
			} else
				return FALSE;
		} else
			return TRUE;
	}

	/**
	 * 检查粉丝关注记录
	 * @param unknown $inter_id
	 * @param unknown $openid
	 */
	public function check_fans_sub_info($inter_id,$openid){
		$this->_db('iwide_r1')->where(array('openid'=>$openid,'inter_id'=>$inter_id));
		$this->_db('iwide_r1')->limit(1);
		$res = $this->_db('iwide_r1')->get('fans_subs')->num_rows();
		if($res < 1){
			$this->_db('iwide_r1')->where(array('openid'=>$openid,'inter_id' => $inter_id,'event'=>2));
			$this->_db('iwide_r1')->limit(1);
			$this->_db('iwide_r1')->order_by('id ASC');
			$fsl = $this->_db('iwide_r1')->get('fans_sub_log');
			if($fsl->num_rows() > 0){
				$fsl = $fsl->row();
				$this->_create_fans_sub_info($fsl->inter_id, $fsl->openid, $fsl->source, 0);
			}else{
				$this->_create_fans_sub_info($inter_id, $openid, -1, 0);
			}
		}
		return ;
	}

	/**
	 * 粉丝关注互动更新
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid OPENID
	 */
	public function active_fans_subcribe_grades($inter_id,$openid){
		// 		return true;
		$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'grade_openid'=>$openid,'grade_table'=>'iwide_fans_sub_log','status'=>6));
		return $this->_db('iwide_rw')->update('distribute_grade_all',array('status'=>1,'grade_time'=>date('Y-m-d H:i:s')));
	}

	/**
	 * 写入粉丝关注信息
	 *
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid
	 * @param int $source 关注来源
	 * @param int $hotel_id 粉丝所属酒店
	 * @param datetime $event_time
	 */
	private function _create_fans_sub_info($inter_id, $openid, $source, $hotel_id, $event_time = NULL, $club_source = NULL) {
		if ($this->_is_sub_info_exist ( $inter_id, $openid )) {
			$sql = "UPDATE `iwide_fans_subs` SET `source`=?,`hotel_id`=?,`event_time`=? WHERE `inter_id`=? AND `openid`=? LIMIT 1";
			$params = array ( $source, $hotel_id, $event_time, $inter_id, $openid );
			return $this->_db ( 'iwide_rw' )->query ( $sql, $params );
		} else {
			$sql = "INSERT IGNORE INTO iwide_fans_subs (`inter_id`,`event`,`openid`,`source`,`hotel_id`,`club_source`,`event_time`) VALUES (?,?,?,?,?,?,?)";
			$params = array ( $inter_id, 2, $openid, $source, empty ( $hotel_id ) ? - 1 : $hotel_id, $club_source, empty ( $event_time ) ? date ( 'Y-m-d H:i:s' ) : $event_time );
			return $this->_db ( 'iwide_rw' )->query ( $sql, $params );
		}
	}
	private function _is_sub_info_exist($inter_id, $openid) {
		$this->_db ( 'iwide_r1' )->where ( array ( 'inter_id' => $inter_id, 'openid' => $openid ) );
		$this->_db ( 'iwide_r1' )->limit ( 1 );
		return $this->_db ( 'iwide_r1' )->get ( 'fans_subs' )->num_rows () > 0;
	}

	/**
	 * 粉丝取消关注状态更新
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid OPENID
	 */
	public function _unsubcribe($inter_id,$openid){
		$this->_db('iwide_rw')->WHERE(array('inter_id'=>$inter_id,'openid'=>$openid));
		return $this->_db('iwide_rw')->update('fans_subs',array('cur_status'=>2,'unsubcribe_time'=>date('Y-m-d H:i:s')));
	}
	/**
	 * 粉丝关注状态更新
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $openid OPENID
	 */
	private function _fans_sub_update($inter_id,$openid){
		$this->_db('iwide_rw')->WHERE(array('inter_id'=>$inter_id,'openid'=>$openid));
		return $this->_db('iwide_rw')->update('fans_subs',array('cur_status'=>1));
	}

	/**
	 * 根据指定条件获取粉丝关注数
	 * @param Array $inter_id 公众号唯一识别号
	 * @param string $begin_time 起始时间
	 * @param string $end_time 结束时间
	 * @return int 粉丝数
	 */
	public function get_fans_count_by_time($inter_id = array(),$begin_time = '',$end_time = ''){
		$sql = "SELECT COUNT(openid) counts FROM iwide_fans_subs WHERE 1";
		if(!empty($inter_id) && $inter_id !='ALL_PRIVILEGES'){
			if(is_array($inter_id)){
				$sql .= " AND inter_id IN ?";
				$params[] = $inter_id;
			}else{
				$sql .= " AND inter_id=?";
				$params[] = $inter_id;
			}
		}
		if(!empty($begin_time)){
			$sql .= " AND event_time>=?";
			$params[] = $begin_time;
		}
		if(!empty($end_time)){
			$sql .= " AND event_time<?";
			$params[] = $end_time;
		}
		$query = $this->_db('iwide_r1')->query($sql,$params)->row();
		return is_null($query->counts) ? 0 : $query->counts;
	}
	/**
	 * 获取指定条件的粉丝订房间夜数及金额
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $begin_time 起始时间，默认前一个月1号
	 * @param string $end_time 结束时间，默认本月1号
	 */
	public function get_fans_room_summ($inter_id = array(),$begin_time = '',$end_time = ''){
		if(empty($begin_time)){
			$begin_time = date('Y-m-01',strtotime('-1 month'));
		}
		if(empty($end_time)){
			$end_time = date('Y-m-01');
		}
		$params = array($begin_time,$end_time);
		$sql = "SELECT COUNT(DISTINCT openid) counts,SUM(i.iprice) total_amount FROM iwide_hotel_orders o LEFT JOIN iwide_hotel_order_items i ON o.orderid=i.orderid AND o.inter_id=i.inter_id WHERE o.openid IN (SELECT openid FROM iwide_fans_subs WHERE event_time BETWEEN ? AND ?)";
		if(!empty($inter_id) && $inter_id !='ALL_PRIVILEGES'){
			if(is_array($inter_id)) {
				$sql .= " AND o.inter_id IN ?";
				$params [] = $inter_id;
			} else {
				$sql .= " AND o.inter_id=?";
				$params [] = $inter_id;
			}
		}
		$sql .= " AND o.order_time BETWEEN ? AND ?";
		$params[] = strtotime($begin_time);
		$params[] = strtotime($end_time);
		// 		$query = $this->_db('iwide_r1')->query($sql,$params)->row();
		// 		echo $this->_db('iwide_r1')->last_query();
		// 		echo '<br />';
		// 		return $query;
		return $this->_db('iwide_r1')->query($sql,$params)->row();
	}
	/**
	 * 获取指定条件的粉丝购买商品数及金额
	 * @param string $inter_id 公众号唯一识别号
	 * @param string $begin_time 起始时间，默认前一个月1号
	 * @param string $end_time 结束时间，默认本月1号
	 */
	public function get_fans_mall_summ($inter_id = array(),$begin_time = '',$end_time = ''){
		if(!empty($begin_time)){
			$begin_time = date('Y-m-01',strtotime('-1 month'));
		}
		if(!empty($end_time)){
			$end_time = date('Y-m-01');
		}
		$sql = "SELECT COUNT(DISTINCT openid) counts,SUM(actually_paid) total_amount FROM iwide_mall_order_summary WHERE openid IN (SELECT openid FROM iwide_fans_subs WHERE event_time BETWEEN ? AND ?)";
		$params[] = $begin_time;
		$params[] = $end_time;
		if(!empty($inter_id) && $inter_id !='ALL_PRIVILEGES'){
			if(is_array($inter_id)){
				$sql .= " AND inter_id IN ?";
				$params[] = $inter_id;
			}else{
				$sql .= " AND inter_id=?";
				$params[] = $inter_id;
			}
		}
		$this->_db('iwide_r1')->query($sql,$params)->row();
	}

	public function set_fans_source_update_config($inter_id, $do_update, $time_before = '') {
		return $this->set_redis_key_status ( 'FANS_SOURCE_INFO_UPDATE' . $inter_id, json_encode ( array ( 'do_update' => $do_update, 'time_before' => $time_before ) ) );
	}

	protected function _load_cache($name = 'Cache') {
		if (! $name || $name == 'cache')
			$name = 'Cache';
			$this->load->driver ( 'cache', array ( 'adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_' ), $name );
			return $this->$name;
	}
	public function get_redis_key_status($key) {
		$cache = $this->_load_cache ();
		$redis = $cache->redis->redis_instance ();
		return $redis->get ( $key );
	}
	public function set_redis_key_status($key, $val) {
		$cache = $this->_load_cache ();
		$redis = $cache->redis->redis_instance ();
		return $redis->set ( $key, $val );
	}
}
