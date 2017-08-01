<?php
class Booking_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}

	const TAB_OKPAY_ORDERS = 'okpay_orders';

	public function get_resource_name()
	{
		return 'Okpay_model';
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
		return self::TAB_OKPAY_ORDERS;
	}

	public function table_primary_key()
	{
		return 'id';
	}

	/**
	 * 读取订单列表
	 */
	public function get_booking_item($params,$limit=NULL,$offset=0){
		$inter_id = $params['inter_id'];
		$sql = "select * from iwide_booking_item where inter_id = '{$params['inter_id']}'";
		if(!empty($params['status']) && $params['status'] > -1){//array()
			$sql .= " and status =" . intval($params['status']);
		}
        if(!empty($params['in_status']) && $params['in_status']){//array()
            $sql .= " and status in(" . implode(',',$params['in_status']).')';
        }
        if(!empty($params['openid']) && $params['openid']){//array()
            $sql .= " and openid = '{$params['openid']}'" ;
        }
		//酒店名称
		if(!empty($params['wd'])){
			$sql = $sql." and (name like '%".$params['wd']."%' or phone like '%".$params['wd']."%' ) ";
		}
		//开始时间
		if(!empty($params['start_time'])){
			$sql = $sql." and book_time >='".$params['start_time']."' ";
		}
		//结束时间
		if(!empty($params['end_time'])){
			//结束到23:59:59
			//$params['end_time'] .= strtotime($params['end_time']) . " 23:59:59";
			$sql = $sql." and book_time <'".$params['end_time']." 23:59:59' ";
		}
		//canting
		if(!empty($params['shop_id'])){
			$sql = $sql." and shop_id ='".$params['shop_id']."' ";
		}

		$sql = $sql." order by id desc  ";
//
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = intval($limit);
		}
		$query = $this->db->query($sql,$argvs)->result_array();
		return $query;
	}

	/**
	 * 读取符合条件de  总数
	 */
	public function get_booking_item_count($params){
        $inter_id = $params['inter_id'];
        $sql = "select count(*) as nums from iwide_booking_item where inter_id = '{$params['inter_id']}'";
        if(!empty($params['status']) && $params['status'] > -1){//array()
            $sql .= " and status =" . intval($params['status']);
        }
        //酒店名称
        if(!empty($params['wd'])){
            $sql = $sql." and name like '%".$params['wd']."%' ";
        }
        //开始时间
        if(!empty($params['start_time'])){
            $sql = $sql." and book_time >='".$params['start_time']."' ";
        }
        //结束时间
        if(!empty($params['end_time'])){
            //结束到23:59:59
            //$params['end_time'] .= strtotime($params['end_time']) . " 23:59:59";
            $sql = $sql." and book_time <'".$params['end_time']."' ";
        }
        //canting
        if(!empty($params['shop_id'])){
            $sql = $sql." and shop_id ='".$params['shop_id']."' ";
        }

        $sql = $sql." order by id desc  ";
//

		$query = $this->db->query($sql)->row();
		return $query->nums;
	}


}
