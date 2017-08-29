<?php
class Iwidepay_debt_model extends MY_Model{
	const TAB_TRANSFER = 'iwidepay_transfer';
	const TAB_REFUND = 'iwidepay_refund';
	const TAB_DEBT_RECORD = 'iwidepay_debt_record';
	const TAB_OFFLINE_TRANSFER = 'iwidepay_offline_transfer';
	const TAB_RULE = 'iwidepay_rule';
	function __construct() {
		parent::__construct ();
	}

	/*
	 * 取前一天现付订单的金房卡、分销、集团分成
	 */
	public function get_no_hotel_offlines($startdate = '' , $enddate = ''){
		$this->db->where(array(
			'status' => 2,
			));
		$this->db->where_in('type',array('group','dist','jfk'));
		if(!empty($startdate)){
            $this->db->where('add_time>=',$startdate);
        }
        if(!empty($enddate)){
            $this->db->where('add_time<',$enddate);
        }
        $res = $this->db->get(self::TAB_OFFLINE_TRANSFER)->result_array();
        $result = array();
        foreach ($res as $key => $value) {
        	$result[$value['module'].'_'.$value['order_no']][] = $value;
        }
        return $result;
	}

	/*
	 * 保存欠款单记录
	 */
	public function save_debt_record($debt_data){
		if(!empty($debt_data)){
			$this->db->where(array(
				'inter_id' => $debt_data['inter_id'],
				'hotel_id' => $debt_data['hotel_id'],
				'module' => $debt_data['module'],
				'order_no' => $debt_data['order_no'],
				'order_type' => $debt_data['order_type'],
				));
			$res = $this->db->get(self::TAB_DEBT_RECORD)->row_array();
			if(!$res){
				$res = $this->db->insert(self::TAB_DEBT_RECORD,$debt_data);
				if($res){
					return true;
				}
				return $debt_data;
			}
			return 'data already exists';
		}
		return true;
	}
}