<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author John
 * @package models\distribute
 */
class Welfare_auth_model extends MY_Model {

	public function get_resource_name()
	{
		return '福利信息';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function _shard_db($inter_id=NULL)
    {
        return $this->_db();
    }

    public function _shard_table($basename, $inter_id=NULL )
    {
        return $basename;
    }

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'welfare_auth_records';
	}

    /**
     * 创建新的发放权限
     */
    public function create_admin(){
    	$admin_profiler = $this->session->userdata('admin_profile');
    	log_message('error', 'WELFARE_CREATE_ADMIN_POSTS | '.json_encode($this->input->post()));
    	if(!$this->is_admin_exist($admin_profiler['inter_id'],$this->input->post('admin'),$this->input->post('typ'))){
	    	$this->load->helper('common');
	    	$this->load->library('user_agent');
	    	$params = array('qrcode_id'=>$this->input->post('admin'),'create_time'=>date('Y-m-d H:i:s'),'status'=>1,'remote_ip'=>getIp(),'user_agent'=>$this->agent->agent_string(),'operater'=>$admin_profiler['admin_id'],'operater_name'=>$admin_profiler['nickname'],'inter_id'=>$admin_profiler['inter_id'],'typ' => $this->input->post('typ'));
	    	return $this->_db('iwide_rw')->insert('welfare_admins',$params) > 0 ? array('res' => true,'errmsg' => '管理员创建成功') : array('res' => false,'errmsg' => '数据写入数据库失败');
	    }else{
	    	return array('res' => false,'errmsg' => '管理员已存在');
	    }
    }

    /**
     * 创建新的授权项
     * @param char $inter_id
     * @param int $admin_id
     * @param guid $id
     */
    public function new_auth($inter_id,$admin_id,$id,$admin_username){
    	$this->load->library('user_agent');
    	return $this->_db('iwide_rw')->insert('welfare_auth_records',array('inter_id'=>$inter_id,'id'=>$id,'admin_id'=>$admin_id,'admin_username'=>$admin_username,'create_time'=>date('Y-m-d H:i:s'),'admin_agent'=>$this->agent->agent_string()));
    }

    public function check($inter_id,$status_id,$typ=1){
    	$sql = "SELECT * FROM iwide_welfare_auth_records WHERE inter_id=? AND id=? AND valid_time>=? ORDER BY valid_time DESC LIMIT 1";
    	$query = $this->_db('iwide_r1')->query($sql,array($inter_id,$status_id,date('Y-m-d H:i:s')));
    	if($query->num_rows() > 0){
    		$query = $query->row();
    		$admin_typ = $this->get_admin_typ($inter_id, $query->auth_qrcode);
    		switch ($admin_typ){
    			case 'BOTH'://双重身份
    				return TRUE;
    			case 'HOTEL'://酒店管理员，只能通过酒店发放
    				return $typ == 1;
    			case 'JFK'://金房卡管理员，只能通过金房卡发放
    				return $typ == 2;
    		}
    		return FALSE;
    	}else{
    		return FALSE;
    	}
    }

    public function is_admin_token_exist($inter_id,$admin_id){
    	$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'admin_id'=>$admin_id,'valid_time >='=>date('Y-m-d H:i:s')));
    	$this->_db('iwide_r1')->limit(1);
    	$query = $this->_db('iwide_r1')->get('welfare_auth_records');
    	if($query->num_rows() > 0){
    		return $query->row()->id;
    	}else{
    		return false;
    	}
    }
    public function _update_auth_status($inter_id,$openid,$status_id,$valid_time){
    	$this->load->library('user_agent');
    	$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'id'=>$status_id));
    	return $this->_db('iwide_rw')->update('welfare_auth_records',array('auth_time'=>date('Y-m-d H:i:s'),'valid_time'=>$valid_time,'auth_openid'=>$openid,'auth_agent'=>$this->agent->agent_string()));
    }
    public function _do_auth($inter_id,$openid,$qrcode_id,$status_id,$valid_time){
    	$this->load->library('user_agent');
    	$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'id'=>$status_id));
    	$query = $this->_db('iwide_rw')->update('welfare_auth_records',array('auth_time'=>date('Y-m-d H:i:s'),'valid_time'=>$valid_time,'auth_openid'=>$openid,'auth_qrcode'=>$qrcode_id,'auth_agent'=>$this->agent->agent_string()));
    	log_message('error', 'WELFARE_DO_AUTH | '.$this->_db('iwide_rw')->last_query());
    	return $query;
    }

    /**
     * 管理员列表
     * @param string $inter_id
     * @param int $typ 管理员类型，1：酒店管理员，2：金房卡管理员
     * @param string $limit
     * @param number $offset
     */
    public function get_admins($inter_id,$typ=1,$limit = NULL,$offset = 0){
    	$sql = "SELECT w.*,h.master_dept,h.name,h.cellphone,h.hotel_name FROM iwide_welfare_admins w LEFT JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE w.typ=? AND w.inter_id=?";
    	$params[] = $typ;
    	$params[] = $inter_id;
    	if(!empty($limit)){
    		$sql .= " LIMIT ?,?";
    		$params[] = $offset;
    		$params[] = $limit;
    	}
    	return $this->_db('iwide_r1')->query($sql,$params);
    }
    public function get_admins_count($inter_id,$typ=1){
    	$sql = "SELECT COUNT(w.id) nums FROM iwide_welfare_admins w LEFT JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE w.typ=? AND w.inter_id=?";
    	$query = $this->_db('iwide_r1')->query($sql,array($typ, $inter_id))->row();
    	return is_null($query->nums) ? 0 : $query->nums;
    }

    /**
     * 管理员状态更新
     * @param unknown $inter_id
     * @param unknown $qrcode_id
     * @param unknown $status
     */
    public function _update_admin_status($inter_id,$qrcode_id,$status,$typ=1){
    	$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$qrcode_id,'typ'=>$typ));
    	return $this->_db('iwide_rw')->update('welfare_admins',array('status'=>$status,'last_update_time'=>date('Y-m-d H:i:s')));
    }

    /**
     * OPENID是否通有权限
     * @param unknown $inter_id
     * @param unknown $openid
     * @return boolean
     */
    public function is_openid_valid($inter_id,$openid,$typ=1){
    	$sql = "SELECT COUNT(w.id) nums FROM iwide_welfare_admins w INNER JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE w.typ=? AND w.inter_id=? AND h.openid=? AND w.status=1";
    	$query = $this->_db('iwide_r1')->query($sql,array($typ,$inter_id,$openid))->row();
    	log_message('error', 'WELFARE_IS_OPENID_VALID | '.$this->_db('iwide_r1')->last_query());
    	return $query->nums > 0;
    }

    public function is_admin_exist($inter_id,$qrcode_id,$typ=1){
    	$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$qrcode_id,'typ'=>$typ));
    	$this->_db('iwide_r1')->select('COUNT(id) nums');
    	$query = $this->_db('iwide_r1')->get('welfare_admins')->row();
    	log_message('error', 'WELFARE_IS_ADMIN_EXIST | '.$this->_db('iwide_r1')->last_query());
    	return $query->nums > 0;
    }
    
    /**
     * 查询管理员类型
     * @param string $inter_id
     * @param int $qrcode_id
     * @return string|boolean
     */
    public function get_admin_typ($inter_id,$qrcode_id){
		$this->_db ( 'iwide_r1' )->where ( array ( 'inter_id' => $inter_id, 'qrcode_id' => $qrcode_id ) );
		$this->_db ( 'iwide_r1' )->select ( 'typ' );
		$query = $this->_db ( 'iwide_r1' )->get ( 'welfare_admins' )->result_array ();
		$res = array_column ( $query, 'typ', 'typ');
		if (array_key_exists ( 1, $res ) && array_key_exists ( 2, $res ))
			return 'BOTH';
		else if (array_key_exists ( 1, $res ))
			return 'HOTEL';
		else if (array_key_exists ( 2, $res ))
			return 'JFK';
		else
			return FALSE;
	}
}
