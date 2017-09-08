<?php
/**
 * @author John
 * 消息发放记录
 */
class Send_records_model extends MY_Model{
	
	/**
	 * 成功发放状态
	 * @var integer
	 */
	const SEND_STATUS_SUCCESS = 1;
	/**
	 * 发放失败状态
	 * @var integer
	 */
	const SEND_STATUS_FAILD   = 2;
	
	/**
	 * 主发放记录
	 * 
	 * @param unknown $inter_id        	
	 * @param unknown $limit        	
	 * @param number $offset        	
	 */
	function get_parent_records($inter_id, $batch_no = '', $begin_date = '', $end_date = '', $limit = null, $offset = 0,$source = 1) {
		$sql = "SELECT *,COUNT(*) times,SUM(send_amount) amount,COUNT(DISTINCT `status`) sts FROM iwide_distribute_send_record dc WHERE inter_id=? AND batch_no<>''";
		$param [] = $inter_id;
		if(!empty($batch_no)){
			$sql .= ' AND dc.batch_no LIKE ?';
			$param [] = '%'.$batch_no.'%';
		}
		if(!empty($begin_date)){
			$sql .= ' AND dc.send_time >= ?';
			$param [] = $begin_date;
		}
		if(!empty($end_date)){
			$sql .= ' AND dc.send_time <= ?';
			$param [] = $end_date;
		}
		if(!empty($source)){
			$sql .= ' AND dc.source = ?';
			$param [] = $source;
		}
		$sql .= ' GROUP BY dc.batch_no ORDER BY dc.send_time DESC';
		if (! empty ( $limit )) {
			$sql .= ' LIMIT ?,?';
			$param [] = $offset;
			$param [] = $limit;
		}
// 		return $this->_db('iwide_r1')->query ( $sql, $param );
		$query = $this->_db('iwide_r1')->query ( $sql, $param );
		return $query;
	}
	
	/**
	 * @todo 发放项目明细
	 * @param string $inter_id
	 * @param string $batch_no
	 * @param int $status
	 * @param string $order_no
	 * @param int $saler_no
	 * @param string $saler_name
	 * @param int $limit
	 * @param int $offset
	 */
	function get_records($inter_id, $batch_no, $status = 0, $order_no = '', $saler_no = '', $saler_name = '', $limit = NULL, $offset = 0, $source = 1) {
		if($source == 2)
			return $this->get_records_exts($inter_id, $batch_no, $status, $order_no, $saler_no, $saler_name, $limit, $offset);
		$sql = "SELECT dc.batch_no,dc.inter_id,dc.saler,dc.openid,ga.hotel_id,dc.`status`,dc.remark,ga.grade_table,ga.grade_openid,
                        ga.grade_id,ga.order_amount,ga.grade_total,ga.grade_amount,ga.grade_time,ga.partner_trade_no,ge.hotel_name,dc.id gid,
                        ge.order_id,ge.product,ge.staff_name,dc.send_time,dc.send_by, ga.grade_table,hoi.startdate,hoi.enddate
				FROM iwide_distribute_send_record dc 
				LEFT JOIN iwide_distribute_send_grade_rel gr ON dc.id=gr.sr_id 
				LEFT JOIN ";
		$sql .= $source == 1 ? 'iwide_distribute_grade_all' : 'iwide_distribute_extends';
		$sql .= " ga ON ga.inter_id=dc.inter_id AND gr.ga_id=ga.id
				LEFT JOIN iwide_distribute_grade_ext ge ON ge.inter_id=ga.inter_id AND ga.id=ge.grade_id ";

        $sql .= " LEFT JOIN iwide_hotel_order_items AS hoi ON ga.grade_table = 'iwide_hotels_order' AND hoi.id = ga.grade_id AND hoi.istatus = 3";

        $sql .= " WHERE ga.grade_total > 0 AND dc.inter_id=? AND dc.batch_no=?";
		$param = array ( $inter_id, $batch_no );
		if (! empty ( $status )) {
			$sql .= ' AND dc.`status`=?';
			$param [] = $status;
		}
		if (! empty ( $source )) {
			$sql .= ' AND dc.`source`=?';
			$param [] = $source;
		}
		
		if (! empty ( $saler_no )) {
			$sql .= ' AND ga.saler=?';
			$param [] = $saler_no;
		}
		if (! empty ( $order_no )) {
			$sql .= ' AND ge.order_id LIKE ?';
			$param [] = '%'.$order_no.'%';
		}
		if (! empty ( $saler_name )) {
			$sql .= ' AND ge.staff_name LIKE ?';
			$param [] = '%'.$saler_name.'%';
		}
		$sql .= ' ORDER BY send_time desc';
		if (! empty ( $limit )) {
			$sql .= ' LIMIT ?,?';
			$param [] = $offset;
			$param [] = $limit;
		}
		$query = $this->_db('iwide_r1')->query ( $sql, $param );
		if($this->input->get('debug') == 1) {
			echo $this->_db('iwide_r1')->last_query();exit;
		}
		return $query;
	}
	/**
	 * @todo 发放项目明细(泛分销)
	 * @param string $inter_id
	 * @param string $batch_no
	 * @param int $status
	 * @param string $order_no
	 * @param int $saler_no
	 * @param string $saler_name
	 * @param int $limit
	 * @param int $offset
	 */
	function get_records_exts($inter_id, $batch_no, $status = 0, $order_no = '', $saler_no = '', $saler_name = '', $limit = NULL, $offset = 0) {
		$sql = "SELECT dc.batch_no,dc.inter_id,dc.saler,dc.openid,ga.hotel_id,dc.`status`,dc.remark,ga.grade_table,ga.order_amount,ga.grade_total,ga.grade_amount,ga.grade_id order_id,ga.product,ga.grade_time,ga.partner_trade_no,f.nickname staff_name,dc.send_time,dc.send_by 
				FROM iwide_distribute_send_record dc 
				LEFT JOIN iwide_distri_sgrade_ext_rel gr ON dc.id=gr.sr_id 
				LEFT JOIN iwide_distribute_extends ga ON ga.inter_id=dc.inter_id AND gr.ga_id=ga.id
				LEFT JOIN iwide_fans f ON f.inter_id=ga.inter_id AND ga.saler=f.fans_key WHERE ga.grade_total > 0 AND dc.inter_id=? AND dc.batch_no=?";
		$param = array ( $inter_id, $batch_no );
		if (! empty ( $status )) {
			$sql .= ' AND dc.`status`=?';
			$param [] = $status;
		}
		if (! empty ( $source )) {
			$sql .= ' AND dc.`source`=?';
			$param [] = $source;
		}
		
		if (! empty ( $saler_no )) {
			$sql .= ' AND ga.saler=?';
			$param [] = $saler_no;
		}
		if (! empty ( $order_no )) {
			$sql .= ' AND ga.grade_id LIKE ?';
			$param [] = '%'.$order_no.'%';
		}
		if (! empty ( $saler_name )) {
			$sql .= ' AND f.nickname LIKE ?';
			$param [] = '%'.$saler_name.'%';
		}
		$sql .= ' ORDER BY send_time desc';
		if (! empty ( $limit )) {
			$sql .= ' LIMIT ?,?';
			$param [] = $offset;
			$param [] = $limit;
		}
		$query = $this->_db('iwide_r1')->query ( $sql, $param );
		if($this->input->get('debug') == 1) {
			echo $this->_db('iwide_r1')->last_query();exit;
		}
		return $query;
	}
	
	public function get_batch_logs($inter_id, $batch_no = '', $begin_date = '', $end_date = '', $limit = null, $offset = 0, $source = 1) {
		$sql = "SELECT gst.id,gst.batch_no,gst.inter_id,gst.send_time,COUNT(saler) saler_count,SUM(`status`=1) success_saler,SUM(send_amount) total_amount,
				SUM(IF(`status`=1,send_amount,0)) success_amount,SUM(times) total_times,SUM(success_count) success_total_count 
				FROM (SELECT dc.id,dc.batch_no,dc.inter_id,dc.saler,dc.send_time,dc.send_amount,dc.partner_trade_no,dc.`status`,dc.send_by,
				COUNT(*) times,SUM(`status`=1) success_count FROM iwide_distribute_send_record dc 
				LEFT JOIN iwide_distribute_send_grade_rel gr ON dc.id=gr.sr_id 
				WHERE inter_id=?";
		$param [] = $inter_id;
		if(!empty($batch_no)){
			$sql .= ' AND dc.batch_no LIKE ?';
			$param [] = '%'.$batch_no.'%';
		}else{
			$sql .= " AND batch_no<>''";
		}
		if(!empty($begin_date)){
			$sql .= ' AND dc.send_time >= ?';
			$param [] = $begin_date;
		}
		if(!empty($end_date)){
			$sql .= ' AND dc.send_time <= ?';
			$param [] = $end_date;
		}
		if(!empty($source)){
			$sql .= ' AND dc.source = ?';
			$param [] = $source;
		}
		$sql .= ' GROUP BY partner_trade_no) gst GROUP BY batch_no ORDER BY send_time DESC';
		if (! empty ( $limit )) {
			$sql .= ' LIMIT ?,?';
			$param [] = $offset;
			$param [] = $limit;
		}
		return $this->_db('iwide_r1')->query ( $sql, $param );
	}
	
	public function get_salers_log($inter_id, $batch_no, $status = 0, $saler_no = '', $saler_name = '', $limit = NULL, $offset = 0,$source = 1) {
		$sql = "SELECT dc.id,dc.batch_no,dc.inter_id,dc.saler,dc.send_time,dc.send_amount,dc.partner_trade_no,dc.`status`,dc.send_by,dc.send_inter_id,COUNT(dc.id) times,hs.`name`,hs.hotel_name,dc.send_by 
FROM iwide_distribute_send_record dc 
LEFT JOIN iwide_distribute_send_grade_rel gr ON dc.id=gr.sr_id 
LEFT JOIN iwide_hotel_staff hs ON dc.inter_id=hs.inter_id AND dc.saler=hs.qrcode_id
WHERE dc.inter_id=? AND dc.batch_no=?";
		$param = array ( $inter_id, $batch_no );
		if (! empty ( $status )) {
			$sql .= ' AND dc.`status`=?';
			$param [] = $status;
		}
		if (! empty ( $source )) {
			$sql .= ' AND dc.`source`=?';
			$param [] = $source;
		}
		
		if (! empty ( $saler_no )) {
			$sql .= ' AND dc.saler=?';
			$param [] = $saler_no;
		}
		if (! empty ( $saler_name )) {
			$sql .= ' AND hs.`name` LIKE ?';
			$param [] = '%'.$saler_name.'%';
		}
		$sql .= ' GROUP BY dc.partner_trade_no ORDER BY send_time DESC';
		if (! empty ( $limit )) {
			$sql .= ' LIMIT ?,?';
			$param [] = $offset;
			$param [] = $limit;
		}
		return $this->_db('iwide_r1')->query ( $sql, $param );
	}
	
	public function get_partner_logs($inter_id,$send_record_id,$limit = null,$offset = 0){
		$sql = 'SELECT ga.id,ga.inter_id,ga.hotel_id,ga.order_hotel,ga.saler,ga.order_amount,ga.grade_total,ga.grade_amount,ga.grade_time,ga.`status`,ge.product,ge.order_id,ge.staff_name 
				FROM iwide_distribute_grade_all ga LEFT JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id 
				LEFT JOIN iwide_distribute_send_grade_rel gr ON gr.ga_id=ga.id WHERE ga.inter_id=? AND gr.sr_id=?';
		$param = array($inter_id,$send_record_id);
		if (! empty ( $limit )) {
			$sql .= ' LIMIT ?,?';
			$param [] = $offset;
			$param [] = $limit;
		}
		return $this->_db('iwide_r1')->query ( $sql, $param );
	}
	/**
	 * 手工重置发放异常绩效状态
	 * @param integer $id 发放ID
	 * @param integer $status 新发放状态
	 * @param boolean $is_staff 是否成功
	 * @return boolean
	 */
	public function set_status($id,$status) {
		$where = [ 'distribute_send_record.id' => $id ];
		if ($this->_admin_inter_id != FULL_ACCESS && !empty($this->_admin_inter_id))
			$where ['inter_id'] = $this->_admin_inter_id;
		$this->_db ( 'iwide_r1' )->where ( $where );
		$this->_db ( 'iwide_r1' )->select ( 'distribute_send_grade_rel.ga_id,distribute_send_record.remark,distribute_send_record.source' );
		$this->_db ( 'iwide_r1' )->from ( 'distribute_send_record' );
		$this->_db ( 'iwide_r1' )->join ( 'distribute_send_grade_rel', 'distribute_send_grade_rel.sr_id=distribute_send_record.id' );
		$query = $this->_db ( 'iwide_r1' )->get ()->result_array ();
		$ids = array_column ( $query, 'ga_id' );
		
		if (count ( $ids ) > 0) {
			//发放状态重置
			$this->_db ( 'iwide_rw' )->trans_begin ();
			$this->_db ( 'iwide_rw' )->where($where);
			$this->_db ( 'iwide_rw' )->update('distribute_send_record',['status'=>$status,'remark' => $query[0]['remark'].' | 管理员 '.$this->session->admin_profile['username'].' | '.$this->session->admin_profile['admin_id'].' 于 '.date('Y-m-d H:i:s').' 重置状态']);
			if ($this->_admin_inter_id != FULL_ACCESS && !empty($this->_admin_inter_id))
				$this->_db ( 'iwide_rw' )->where('inter_id', $this->_admin_inter_id);
			$this->_db ( 'iwide_rw' )->where_in('id',$ids);
			//员工/粉丝分销绩效状态重置
			if( $query[0]['source'] == 1)//员工
				$this->_db ( 'iwide_rw' )->update ( 'distribute_grade_all',['status' => $status == self::SEND_STATUS_SUCCESS ? 2 : 1] );
			else//粉丝
				$this->_db ( 'iwide_rw' )->update ( 'distribute_extends',['status' => $status == self::SEND_STATUS_SUCCESS ? 2 : 1] );
			if ($this->_db ( 'iwide_rw' )->trans_status () === FALSE) {
				$this->_db ( 'iwide_rw' )->trans_rollback ();
				return FALSE;
			} else {
				$this->_db ( 'iwide_rw' )->trans_commit ();
				return TRUE;
			}
		}
		return FALSE;
	}
}