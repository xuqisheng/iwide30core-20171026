<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author John
 * @since 2016-04-20
 * @package models\distribute
 */
class Distribute_notice_model extends MY_Model {

	/**
	 * @param unknown $params
	 * @return boolean
	 */
	public function create_notice($params){
		$this->load->helper ( 'guid' );
		$nid = Guid::toString ();
		$datas ['nid']         = $nid;
		$datas ['inter_id']    = empty ( $params ['inter_id'] ) ? $this->session->userdata('inter_id') : $params ['inter_id'];
		$datas ['hotel_id']    = $params ['hotel_id'] != 'ALL' ? $params ['hotel_id'] : 0;
		$datas ['title']       = $params ['title'];
		$datas ['sub_title']   = isset ( $params ['sub_title'] ) ? $params ['sub_title'] : '';
		$datas ['create_time'] = date ( 'Y-m-d H:i:s' );
		$datas ['content']     = $params ['content'];
		$datas ['status']      = isset ( $params ['status'] ) ? $params ['status'] : 0;
		$datas ['msg_typ']     = $params ['msg_typ'];
		$datas ['top']         = isset ( $params ['top'] ) ? $params ['top'] : 0;

		$datas ['remark']      = isset ( $params ['remark'] ) ? $params ['remark'] : '';
		$this->_db('iwide_rw')->trans_begin ();
		$this->_db('iwide_rw')->insert ( 'distribute_notice', $datas );

		$sql = "INSERT INTO iwide_distribute_notice_ext (eid,to_staff,nid)";
		if (empty ( $params ['openid'] )) {
			$sql .= " SELECT UUID(),openid,? FROM iwide_hotel_staff WHERE inter_id=? AND status=2";
			$query_params[] = $nid;
			$query_params[] = $datas ['inter_id'];
			if($datas ['hotel_id'] > 0){
				$sql .= ' AND hotel_id=?';
				$query_params[] = $datas ['hotel_id'];
			}
		} else {
			$sql .= " VALUES (UUID(),'" . $params ['openid'] . "',?)";
			$query_params[] = $nid;
		}
		$this->_db('iwide_rw')->query($sql, $query_params);
		if ($this->_db('iwide_rw')->trans_status () === FALSE) {
			$this->_db('iwide_rw')->trans_rollback ();
			return FALSE;
		} else {
			$this->_db('iwide_rw')->trans_commit ();
			return TRUE;
		}
	}
	public function update_notice($params){

	}

	/**
	 * 将消息标记为已读
	 * @param unknown $msg_id
	 */
	public function do_read_msg($msg_id){
		$this->_db('iwide_rw')->where(array('eid'=>$msg_id));
		return $this->_db('iwide_rw')->update('distribute_notice_ext',array('readtime'=>date('Y-m-d H:i:s'),'flag'=>1));
	}

	public function delete_notice(){

	}

	/**
	 * 取未读消息数
	 * @param unknown $openid
	 */
	public function get_my_new_msg_count($openid,$category = NULL,$top=NULL){
		$sql = 'SELECT COUNT(*) nums FROM '.$this->_db('iwide_r1')->dbprefix('distribute_notice_ext').' WHERE to_staff=? AND flag=?';
		$params = array($openid,0);
		if(!empty($category)){
			$sql .= is_array($category) ? ' AND msg_typ IN ?' : ' AND msg_typ=?';
			$params[] = $category;
		}
		if(!empty($top)){
			$sql .= is_array($top) ? ' AND top IN ?' : ' AND top=?';
			$params[] = $top;
		}
		$query = $this->_db('iwide_r1')->query($sql,$params)->row();
		return $query->nums;
	}

	/**
	 * @todo 取openid下的系统消息
	 * @param string $openid
	 * @param string $inter_id 公众号ID
	 * @param int $offset 起始位置
	 * @param int $limit 取的数量
	 */
	public function get_my_notices($openid,$inter_id,$offset = 0,$limit = 20){
		$sql = 'SELECT nt.*,ne.eid,ne.to_staff,ne.flag,ne.readtime FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid WHERE nt.inter_id=? AND ne.to_staff=? ORDER BY nt.create_time DESC LIMIT ?,?';
		$query = $this->_db('iwide_r1')->query($sql,array($inter_id,$openid,$offset,$limit))->result();
		return $query;
	}
	/**
	 * @todo 取openid下的系统消息
	 * @param string $openid
	 * @param string $inter_id 公众号ID
	 * @param int $category 消息分类 -1表示所有分类
	 * @param int $offset 起始位置
	 * @param int $limit 取的数量
	 */
	public function get_my_notices_by_category($openid,$inter_id,$category=null,$offset = 0,$limit = 20){
		$sql = 'SELECT nt.*,ne.eid,ne.to_staff,ne.flag,ne.readtime FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid WHERE nt.inter_id=? AND ne.to_staff=?';
		if($category != null){
			$sql .= ' AND nt.msg_typ='.$category;
		}else{
			$sql .= ' AND (nt.msg_typ=0 OR nt.msg_typ=1)';
		}
		$sql .= ' ORDER BY nt.create_time DESC LIMIT ?,?';
		$query = $this->_db('iwide_r1')->query($sql,array($inter_id,$openid,$offset,$limit))->result();
		return $query;
	}
	/**
	 * @todo 取单条系统消息
	 * @param string $nc_id 消息ID
	 * @param string $inter_id 公众号ID
	 * @param string $openid 用户openid
	 */
	public function get_single_notice($nc_id,$inter_id,$openid = NULL){
		$sql = 'SELECT nt.*,ne.eid,ne.to_staff,ne.flag,ne.readtime FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid WHERE ne.eid=? AND nt.inter_id=? ';
		$params[] = $nc_id;
		$params[] = $inter_id;
		if(!is_null($openid)){
			$sql .= ' AND ne.to_staff=? ';
			$params[] = $openid;
		}
		$sql .= 'limit 1';
		return $this->_db('iwide_r1')->query($sql,$params)->row();
	}

	public function get_notices($inter_id,$cate=null,$limit=NULL,$offset=0){
		$sql = "SELECT nt.*,ne.eid,ne.to_staff,ne.flag,ne.readtime,s.name staff_name FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid INNER JOIN iwide_hotel_staff s ON ne.to_staff=s.openid AND nt.inter_id=s.inter_id WHERE nt.inter_id=? AND s.openid<>''";
		$params[] = $inter_id;
		if($cate == NULL){
			$sql .= ' AND (nt.msg_typ=0 OR nt.msg_typ=1)';
		}else{
			$sql .= is_array($cate) ? ' AND nt.msg_typ IN ?' : ' AND nt.msg_typ=?';
			$params[] = $cate;
		}
		$sql .= ' ORDER BY nt.create_time DESC ';
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params)->result();
	}
	public function get_notices_count($inter_id,$cate=null){
		$sql = 'SELECT count(*) nums FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid WHERE nt.inter_id=? ';
		$params[] = $inter_id;
		if($cate == NULL){
			$sql .= ' AND (nt.msg_typ=0 OR nt.msg_typ=1)';
		}else{
			$sql .= ' AND nt.msg_typ=?';
			$params[] = $cate;
		}
		$query = $this->_db('iwide_r1')->query($sql,$params)->row();
		return $query->nums;
	}

	public function get_group_notices($inter_id,$cate=null,$limit=NULL,$offset=0){
		$sql = "SELECT nt.*,ne.eid,ne.to_staff,ne.flag,ne.readtime,s.name staff_name FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid INNER JOIN iwide_hotel_staff s ON ne.to_staff=s.openid AND nt.inter_id=s.inter_id WHERE nt.inter_id=?  AND s.openid<>''";
		$params[] = $inter_id;
		if($cate == NULL){
			$sql .= ' AND (nt.msg_typ=0 OR nt.msg_typ=1)';
		}else{
			$sql .= ' AND nt.msg_typ=?';
			$params[] = $cate;
		}
		$sql .= ' ORDER BY nt.create_time DESC ';
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params)->result();
	}

	/**
	 * 创建发放信息
	 * @param unknown $inter_id
	 * @param unknown $amount
	 * @param unknown $end_time
	 * @param unknown $status
	 * @return boolean
	 */
	public function create_deliver_notice($inter_id,$amount,$end_time,$status,$openid){
		$this->load->helper('guid');

		$data = array('status'=>0,'msg_typ'=>0,'create_time'=>date('Y-m-d H:i:s'),'inter_id'=>$inter_id,'openid'=>$openid);
		$data['title']     = '亲，您的绩效消息提醒';
		$data['sub_title'] = '发放金额:￥'.$amount;
		$data['content']   = '<div class="webkitbox"><p>截止时段：</p><p>'.$end_time.'</p></div><div class="webkitbox"><p>发放金额：</p><p>￥'.$amount.'</p></div><div class="webkitbox"><p>发放状态：</p><p>'.$status.'</p><p>金额已发放到微信钱包零钱账号。</p></div>';
		return $this->create_notice($data);
	}
	public function create_deliver_notice_content($inter_id,$amount,$end_time,$content,$openid,$remark='',$msg_typ=0){
		$this->load->helper('guid');

		$data = array('status'=>0,'msg_typ'=>$msg_typ,'create_time'=>date('Y-m-d H:i:s'),'inter_id'=>$inter_id,'openid'=>$openid);
		$data['title']     = '亲，您的绩效消息提醒';
		$data['sub_title'] = '发放金额:￥'.$amount;
		// 		$data['content']   = '<div class="webkitbox"><p>截止时段：</p><p>'.$end_time.'</p></div><div class="webkitbox"><p>发放金额：</p><p>￥'.$amount.'</p></div><div class="webkitbox"><p>发放状态：</p><p>'.$status.'</p><p>金额已发放到微信钱包零钱账号。</p></div>';
		$data['content']   = $content;
		$data['remark']    = $remark;
		return $this->create_notice($data);
	}
	public function create_dup_subscribe_notice($params){
		$this->load->helper ( 'guid' );
		if (empty ( $params ['fans_name'] ))
			$params ['fans_name'] = '粉丝';
		$params ['nid'] = Guid::toString ();
		$params ['title'] = '此粉丝已名花有主';
		if (! isset ( $params ['sub_title'] ) || empty ( $params ['sub_title'] ))
			$params ['sub_title'] = '手太慢，' . $params ['fans_name'] . '已经通过其他渠道关注过';
		$params ['create_time'] = date ( 'Y-m-d H:i:s' );
		if (! isset ( $params ['content'] ) || empty ( $params ['content'] ))
			$params ['content'] = '手太慢，' . $params ['fans_name'] . '已经通过其他渠道关注过，不能成为您的粉丝。亲，请发展新粉丝才可以计入“我的粉丝”归属于您，加油！';
		$params ['status'] = 0;
		$params ['msg_typ'] = 1;
		return $this->create_notice($params);
		// INSERT INTO iwide_distribute_notice (`nid`,`inter_id`, `hotel_id`, `title`, `sub_title`, `create_time`, `content`, `status`, `msg_typ`) VALUES
		// (@muid,NEW.inter_id,@hid,'此粉丝已名花有主',CONCAT('手太慢，',@fans_name,'已经通过其他渠道关注过'),NOW(),CONCAT('手太慢，',@fans_name,'已经通过其他渠道关注过，不能成为您的粉丝。亲，请发展新粉丝才可以计入“我的粉丝”归属于您，加油！'),0,1);
		// INSERT INTO iwide_distribute_notice_ext (`eid`,`to_staff`, `flag`, `nid`) VALUES (UUID(),@staff_openid,0,@muid);
	}
	public function get_top_notice($inter_id, $openid, $top=2,$limit=20){
		$sql = 'SELECT nt.*,ne.eid,ne.to_staff,ne.flag,ne.readtime FROM iwide_distribute_notice nt INNER JOIN iwide_distribute_notice_ext ne ON nt.nid=ne.nid WHERE nt.inter_id=? AND ne.to_staff=? AND nt.top=? AND nt.msg_typ=1 AND ne.flag=0 AND nt.push_time<=NOW() ORDER BY nt.create_time DESC LIMIT ?';
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$openid,$top,$limit))->result();
	}
}