<?php
class Okpay_package_model extends MY_Model{

    function __construct() {
        parent::__construct ();

    }

    const TAB_OKPAY_ACTIVITIES = 'okpay_package';
    public function get_resource_name()
    {
    	return 'okpay_package_model';
    }
    
    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function table_name()
    {
    	return 'okpay_package';
    }
    
    public function table_primary_key()
    {
    	return 'id';
    }

    
    public function delete($id,$inter_id,$hotel_id){
    	$result = $this->db->delete(self::TAB_OKPAY_ACTIVITIES, array('id' => $id,"inter_id"=>$inter_id,"hotel_id"=>$hotel_id));
    	return $result;	
    }
    

	//获取单条信息
	public function get($id = 0,$inter_id = ''){
		$sql = "select * from iwide_okpay_package where id = {$id} and inter_id = '{$inter_id}'";
		$res = $this->_db('iwide_r1')->query($sql);
		return $res->result_array()[0]?$res->result_array()[0]:false;
	}

	//获取信息列表
	public function get_package_info_list($filter = array(),$limit = null,$offset = 0){
		$sql = "select * from iwide_okpay_package where 1=1 ";
		if(isset($filter['inter_id'])){
			$sql .= " and inter_id = '{$filter['inter_id']}'";
		}
		if(isset($filter['id']) && !empty($filter['id'])){
			$sql .= " and id = " . intval($filter['id']);
		}
		if(isset($filter['start_time']) && !empty($filter['start_time'])){
			$sql .= " and start_time >= '{$filter['start_time']}'";
		}
		if(isset($filter['end_time']) && !empty($filter['end_time'])){
			$end = $filter['end_time']." 23:59:59";
			$sql .= " and end_time < '{$end}'";
		}
		if(isset($filter['status']) && $filter['status'] >= 0){
			$sql .= " and status = " . $filter['status'];
		}
		if(isset($filter['hotel_id']) && $filter['hotel_id'] > 0){
			$sql .= " and hotel_id = " . $filter['hotel_id'];
		}
		if(isset($filter['type_id']) && $filter['type_id'] > 0){
			$sql .= " and type_id = " . $filter['type_id'];
		}
		$sql .= ' order by id desc';
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}

		$query = $this->_db('iwide_r1')->query($sql,$argvs);

		return $query->result_array();
	}
	//获取优惠信息数量
	public function get_package_info_count($filter = array(),$limit = null,$offset = 0){
		$sql = "select count(*) as cc from iwide_okpay_package where 1=1 ";
		if(isset($filter['inter_id'])){
			$sql .= " and inter_id = '{$filter['inter_id']}'";
		}
		if(isset($filter['id']) && !empty($filter['id'])){
			$sql .= " and id = " . intval($filter['id']);
		}
        if(isset($filter['start_time']) && !empty($filter['start_time'])){
            $sql .= " and start_time >= '{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && !empty($filter['end_time'])){
            $end = $filter['end_time']." 23:59:59";
            $sql .= " and end_time < '{$end}'";
        }
		if(isset($filter['status']) && $filter['status'] >= 0){
			$sql .= " and status = " . $filter['status'];
		}
		if(isset($filter['hotel_id']) && $filter['hotel_id'] > 0){
			$sql .= " and hotel_id = " . $filter['hotel_id'];
		}
		if(isset($filter['type_id']) && $filter['type_id'] > 0){
			$sql .= " and type_id = " . $filter['type_id'];
		}
		$sql .= ' order by id desc';
		$argvs = array();

		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();

		return $query->cc?$query->cc:0;
	}
	//获取所有该inter_id 的场景
	public function get_all_type_by_inter_id($inter_id = ''){
		$sql = "select * from iwide_okpay_type where inter_id = '{$inter_id}' and status = 1";
		$query = $this->_db('iwide_r1')->query($sql);
		return $query->result_array();
	}

    //读取最新的礼包规则
    public function get_package_detail($inter_id = '',$hotel_id = 0,$type_id =0,$id = 0){
        $now = date('Y-m-d H:i:s');
        $sql = "select * from iwide_okpay_package where inter_id = '{$inter_id}' ";
        if(!empty($hotel_id)){
            $sql .= " and hotel_id = " . intval($hotel_id);
        }
        if(!empty($type_id)){
            $sql .= " and type_id =  " . intval($type_id);
        }
        if(!empty($id)){
            $sql .= " and id = " . intval($id);
        }
        $sql .= " and status = 1 and '" . $now . "' >= start_time and '" . $now . "' < end_time";
        if(empty($orderby)){
            $orderby = " order by id desc";
        }
        $sql .= $orderby;
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        return isset($query[0])?$query[0]:array();
    }

    //获取领取记录表 对应的领取记录
    public function get_package_record_count($inter_id = '',$openid = '',$package_id = 0,$order_sn = '',$start = '',$end = ''){
        $sql = "select count(*) as cc from iwide_okpay_package_log where inter_id = '{$inter_id}' ";//and openid = '{$openid}' and package_id = $package_id and order_sn = '{$order_sn}'";
        if(!empty($openid)){
            $sql .= " and openid = '{$openid}' ";
        }
        if(!empty($package_id)){
            $sql .= " and package_id = " . intval($package_id);//对应的是礼包规则表的id字段，这里可能有重复
        }
        if(!empty($order_sn)){
            $sql .= " and order_sn = '{$order_sn}'";
;        }
        if(!empty($start)){
            $sql .= " and add_time >= '{$start}'";
        }
        if(!empty($end)){
            $sql .= " and add_time < '{$end}'";
        }
        $query = $this->_db('iwide_r1')->query($sql)->row();
        return $query->cc?$query->cc:0;
    }

    //获取订单信息
    public function get_order_info($data = array()){
        $sql = "select * from iwide_okpay_orders where inter_id = '{$data['inter_id']}' and out_trade_no = '{$data['order_sn']}' and openid = '{$data['openid']}' and pay_status = 3 limit 1";
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        return isset($query[0])?$query[0]:array();
    }






}
