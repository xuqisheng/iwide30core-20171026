<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author stgc
 * @msgauth models\okpay
 */
class Okpay_msgauth_model extends MY_Model {

	public function get_resource_name()
	{
		return '模板消息授权';
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
		return 'okpay_msgadmins';
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
	    	$params = array('qrcode_id'=>$this->input->post('admin'),'create_time'=>date('Y-m-d H:i:s'),'status'=>1,'remote_ip'=>getIp(),'user_agent'=>$this->agent->agent_string(),'operater'=>$admin_profiler['admin_id'],'operater_name'=>$admin_profiler['nickname'],'inter_id'=>$admin_profiler['inter_id']);
	    	return $this->_db('iwide_rw')->insert('okpay_msgadmins',$params) > 0 ? array('res' => true,'errmsg' => '管理员创建成功') : array('res' => false,'errmsg' => '数据写入数据库失败');
	    }else{
	    	return array('res' => false,'errmsg' => '管理员已存在');
	    }
    }




    public function is_admin_token_exist($inter_id,$admin_id){
    	$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'admin_id'=>$admin_id,'valid_time >='=>date('Y-m-d H:i:s')));
    	$this->_db('iwide_rw')->limit(1);
    	$query = $this->_db('iwide_rw')->get('welfare_auth_records');
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
     * @param string $limit
     * @param number $offset
     */
    public function get_admins($inter_id,$limit = NULL,$offset = 0){
    	$sql = "SELECT w.*,h.master_dept,h.name,h.cellphone,h.hotel_name FROM iwide_okpay_msgadmins w LEFT JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE  w.inter_id=?";
    	$params[] = $inter_id;
    	if(!empty($limit)){
    		$sql .= " LIMIT ?,?";
    		$params[] = $offset;
    		$params[] = $limit;
    	}
    	return $this->_db('iwide_rw')->query($sql,$params);
    }

    /*获取授权的管理员openid
     * @param string $inter_id
     * */
    public function get_auth_admins_openid($inter_id){
        $sql = "SELECT w.*,h.master_dept,h.name,h.cellphone,h.hotel_name,h.openid FROM iwide_okpay_msgadmins w LEFT JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE  w.inter_id=? and w.status = 1";
        $params[] = $inter_id;
        return $this->_db('iwide_rw')->query($sql,$params)->result_array();
    }
    public function get_admins_count($inter_id){
    	$sql = "SELECT COUNT(w.id) nums FROM iwide_okpay_msgadmins w LEFT JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE w.inter_id=?";
    	$query = $this->_db('iwide_rw')->query($sql,array($inter_id))->row();
    	return is_null($query->nums) ? 0 : $query->nums;
    }

    /**
     * 管理员状态更新
     * @param unknown $inter_id
     * @param unknown $qrcode_id
     * @param unknown $status
     */
    public function _update_admin_status($inter_id,$qrcode_id,$status,$typ=1){
    	$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$qrcode_id));
    	return $this->_db('iwide_rw')->update('okpay_msgadmins',array('status'=>$status,'last_update_time'=>date('Y-m-d H:i:s')));
    }

    /**
     * OPENID是否通有权限
     * @param unknown $inter_id
     * @param unknown $openid
     * @return boolean
     */
    public function is_openid_valid($inter_id,$openid,$typ=1){
    	$sql = "SELECT COUNT(w.id) nums FROM iwide_welfare_admins w INNER JOIN iwide_hotel_staff h ON w.inter_id=h.inter_id AND w.qrcode_id=h.qrcode_id WHERE w.typ=? AND w.inter_id=? AND h.openid=? AND w.status=1";
    	$query = $this->_db('iwide_rw')->query($sql,array($typ,$inter_id,$openid))->row();
    	log_message('error', 'WELFARE_IS_OPENID_VALID | '.$this->_db('iwide_rw')->last_query());
    	return $query->nums > 0;
    }

    public function is_admin_exist($inter_id,$qrcode_id){
    	$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$qrcode_id));
    	$this->_db('iwide_rw')->select('COUNT(id) nums');
    	$query = $this->_db('iwide_rw')->get('okpay_msgadmins')->row();
    	log_message('error', 'WELFARE_IS_ADMIN_EXIST | '.$this->_db('iwide_rw')->last_query());
    	return $query->nums > 0;
    }
    
    /**
     * 查询管理员类型
     * @param string $inter_id
     * @param int $qrcode_id
     * @return string|boolean
     */
    public function get_admin_typ($inter_id,$qrcode_id){
		$this->_db ( 'iwide_rw' )->where ( array ( 'inter_id' => $inter_id, 'qrcode_id' => $qrcode_id ) );
		$this->_db ( 'iwide_rw' )->select ( 'typ' );
		$query = $this->_db ( 'iwide_rw' )->get ( 'welfare_admins' )->result_array ();
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
