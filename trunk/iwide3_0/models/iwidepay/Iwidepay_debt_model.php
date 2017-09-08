<?php
class Iwidepay_debt_model extends MY_Model{
	const TAB_TRANSFER = 'iwidepay_transfer';
	const TAB_REFUND = 'iwidepay_refund';
	const TAB_DEBT_RECORD = 'iwidepay_debt_record';
	const TAB_OFFLINE_TRANSFER = 'iwidepay_offline_transfer';
	const TAB_RULE = 'iwidepay_rule';
	const TAB_PUBLICS = 'publics';
	const TAB_MERCHANT_INFO = 'iwidepay_merchant_info';
	function __construct() {
		parent::__construct ();
	}

	/*
	 * 取现付订单的金房卡、分销、集团分成
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
				return array('info'=>'insert fail','data'=>$debt_data);
			}
			return array('info'=>'data already exists','data'=>$debt_data);
		}
		return true;
	}

	/*
	 * 取垫付退款成功的数据
	 */
	public function get_refund_orders($startdate = '' , $enddate = ''){
		$this->db->where_in('refund_status',array(1,2,3));
		$this->db->where_in('type',array(2,4));
		if(!empty($startdate)){
            $this->db->where('add_time>=',$startdate);
        }
        if(!empty($enddate)){
            $this->db->where('add_time<',$enddate);
        }
        $res = $this->db->get(self::TAB_REFUND)->result_array();
        return $res;
	}

	/*
	 * 查出启用分账的公众号
	 */
	public function get_split_status(){
		$this->db->where('split_status',1);
		return $this->db->get(self::TAB_PUBLICS)->result_array();
	}

	/*
	 * 取所有添加有效账户的酒店
	 */
	public function get_merchant_hotels($inter_id){
		$this->db->where(array(
			'inter_id' => $inter_id,
			'status' => 1,
			'hotel_id >' => 0,
			));
		$this->db->group_by('hotel_id');
		return $this->db->get(self::TAB_MERCHANT_INFO)->result_array();
	}

	/*
	 * 取月费配置
	 */
	public function get_basepay_rules($inter_id){
		$this->db->where(array(
			'inter_id' => $inter_id,
			'module' => 'base_pay',
			'status' => 1,
			));
		$res = $this->db->get(self::TAB_RULE)->result_array();
		$result = array();
		foreach ($res as $k => $v) {
			$result[$v['inter_id'].'_'.$v['hotel_id']] = $v;
		}
		return $result;
	}

	/*
	 * 查询门店本月月费欠款记录是否存在
	 */
	public function get_basepay_record($inter_id,$hotel_id){
		$this->db->where(array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'module' => 'base_pay',
			'order_no' => date('Ym'),
			'order_type'=>'base_pay',
			));
		$res = $this->db->get(self::TAB_DEBT_RECORD)->row_array();
		return $res;
	}

}