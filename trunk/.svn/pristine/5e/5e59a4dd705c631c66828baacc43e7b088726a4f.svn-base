<?php
class Iwidepay_split_model extends MY_Model{

	const TAB_IIP_O = 'iwidepay_order';
	const TAB_IIP_R = 'iwidepay_rule';
	const TAB_IIP_MHI = 'iwidepay_merchant_info';
	const TAB_IIP_S = 'iwidepay_split';
	const JFK_ITD = 'jinfangka';
	function __construct() {
		parent::__construct ();
	}

    /*
     * 获取未分账订单
     */
    public function get_no_split_order($startdate = '' , $enddate = ''){
        //分账状态:1待定、2待分、5待定未分完、8部分正常退款
        $this->db->where_in('transfer_status', array(1,2,5,8));
        $this->db->where_in('is_dist', array(0,2));
        if(!empty($startdate)){
            $this->db->where('add_time>=',$startdate);
        }
        if(!empty($enddate)){
            $this->db->where('add_time<',$enddate);
        }
        $res = $this->db->get(self::TAB_IIP_O)->result_array();
        return $res;
    }

    /*
     * 获取分账规则
     */
    public function get_split_rule($inter_ids,$hotel_id=null){
    	$this->db->where_in(
    		'inter_id' , $inter_ids
    		);
    	if(!is_null($hotel_id)){
    		$this->db->where(array(
    			'hotel_id' => $hotel_id,
    		));
    	}
    	$this->db->where(array(
    		'status' => 1,
    		));
		$rules = $this->db->get(self::TAB_IIP_R)->result_array();
		return $rules;
    }

    /*
     * 获取银行卡信息
     */
    public function get_bank_info($inter_ids,$hotel_id=0,$type=''){
    	$inter_ids[] = self::JFK_ITD;
    	$this->db->where_in(
    		'inter_id' , $inter_ids
    		);
    	$this->db->where(array(
    		'status' => 1,
    		));
    	if($hotel_id>0){
    		$this->db->where(array(
    			'hotel_id' => $hotel_id,
    		));
    	}
    	if(!empty($type)){
    		$this->db->where(array(
    			'type' => $type,
    		));
    	}
    	$bank_infos = $this->db->get(self::TAB_IIP_MHI)->result_array();
    	if($hotel_id==0&&$type==''){
	    	$result = array();
	    	foreach ($bank_infos as $k => $v) {
	    		$result[$v['inter_id'].'_'.$v['hotel_id'].'_'.$v['type']] = $v;
	    	}
	    	return $result;
	    }
    	return $bank_infos;
    }

    /*
     * 分账记录入库
     */
    public function save_split_record($splitresult){
    	if(!empty($splitresult)){
    		$this->db->trans_begin ();
    		foreach ($splitresult as $k => $val) {
    			$res = $this->db->where(array(
    				'order_no'=>$val['order_no'],
    				'inter_id'=>$val['inter_id'],
    				'hotel_id'=>$val['hotel_id'],
    				'type'=>$val['type'],
    				'module'=>$val['module'],
    				))->get(self::TAB_IIP_S)->row_array();
    			if(!$res){
	    			$res = $this->db->insert(self::TAB_IIP_S,$val);
	    			if(!$res){
	    				$this->db->trans_rollback ();
	    				return $res;
	    			}
	    		}
    		}
    		$this->db->trans_commit ();
    		return $res;
    	}
        return true;
    }

    /**
     * 查出未分完订单剩余金额
     */
    function get_remain_amt($order){
    	$this->db->where(array(
    		'inter_id' => $order['inter_id'],
    		'hotel_id' => $order['hotel_id'],
    		'order_no' => $order['order_no'],
    		));
    	$res = $this->db->get(self::TAB_IIP_S)->result_array();
    	$sum = 0;
    	foreach ($res as $k => $v) {
    		$sum += $v['amount'];
    	}
    	return $sum;
    }

}