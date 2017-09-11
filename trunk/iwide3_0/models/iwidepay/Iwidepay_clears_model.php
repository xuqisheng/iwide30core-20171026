<?php
class Iwidepay_clears_model extends MY_Model{
	const TAB_SETTLEMENT = 'iwidepay_settlement';
	const TAB_DEBT_RECORD = 'iwidepay_debt_record';
	const TAB_CONFIGS = 'iwidepay_configs';
	function __construct() {
		parent::__construct ();
	}

	/**
	 * 取预付订单T+1应付给门店的金额
	 */
	public function get_settlement_record($startdate = '' , $enddate = ''){
		$this->db->where(array(
			'status' => 0,
			'type' => 'hotel',
			));
		if(!empty($startdate)){
            $this->db->where('add_time>=',$startdate);
        }
        if(!empty($enddate)){
            $this->db->where('add_time<',$enddate);
        }
        return $this->db->get(self::TAB_SETTLEMENT)->result_array();
	}

	/**
	 * 取门店最新一条未结清的结余金额
	 */
	public function get_last_surplus_record($inter_id,$hotel_id){
		$this->db->where(array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'order_type' => 'balance',
			'debt_type' => 2,
			'status' => 0,
			));
		$this->db->order_by('add_time','desc');
        $this->db->get(self::TAB_DEBT_RECORD)->row_array();
	}

	/**
	 * 查出酒店的所有未结清欠款记录
	 */
	public function get_debt_record($inter_id,$hotel_id){
		$this->db->where(array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'status' => 0,
			'debt_type' => 1,
			'order_type !=' => 'balance',
			'add_time<' => date('Y-m-d 00:00:00',strtotime('+1 day')), 
			));
		return $this->db->get(self::TAB_DEBT_RECORD)->result_array();
	}


	/**
	 * 更新该欠款记录未已结清
	 */
	public function update_debt_data($where = array(),$value = array()){
		if(!empty($where)&&!empty($value)){
			$this->db->where($where);
			$res = $this->db->update(self::TAB_DEBT_RECORD,$value);
			if(!$res){
				return array('msg'=>'update fail','where'=>$where,'value'=>$value);
			}
			return true;
		}
		return array('msg'=>'param is empty','where'=>$where,'value'=>$value);
	}

	/**
	 * 生成结余记录
	 */
	public function save_residual_record($inter_id,$hotel_id,$amount,$last_surplus_id=0){
		$this->db->trans_begin();
		if($last_surplus_id>0){
			//修改上一个结余记录为已结清
			$res = $this->db->where('id',$last_surplus_id)->update(self::TAB_DEBT_RECORD,array('status'=>1));
			if(!$res){
				$this->db->trans_rollback();
				return func_get_args();
			}
		}
		$data = array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'module' => 'balance',
			'order_no' => date('Ymd'),
			'amount' => $amount,
			'order_type' => 'balance',
			'debt_type' => 2,
			'add_time' => date('Y-m-d H:i:s'),
			'up_time' => date('Y-m-d H:i:s'),
			);
		$res = $this->db->insert(self::TAB_DEBT_RECORD,$data);
		if(!$res){
			$this->db->trans_rollback();
			return $data;
		}
		$res = $this->db->trans_commit();
		if(!$res){
			return func_get_args();
		}
		return true;
	}

	/**
	 * 更新门店转账汇总记录金额
	 */
	public function handle_settlement_record($record = array(),$is_settle = 0,$debt_ids = array()){
		if(!empty($record)){
			$obj = $this->db->where(array(
				'inter_id' => $record['inter_id'],
				'hotel_id' => $record['hotel_id'],
				'type' => 'hotel',
				'status' => 0,
				'add_time>=' => date('Y-m-d 00:00:00'),
				'add_time<' => date('Y-m-d 00:00:00',strtotime('+1 day')),
				))->get(self::TAB_SETTLEMENT);
			$row = $obj->row_array();
			if(!$row){
				return array('msg'=>'record not exists','param'=>$record);
			}
			$nums = $obj->num_rows();
			if($nums>1){
				return array('msg'=>'record nums>1','param'=>func_get_args());
			}
			$this->db->trans_begin();
			//更新汇总金额
			$update_amount = $is_settle==1?0:$record['balance_amount'];
			$res = $this->db->where('id',$row['id'])->update(self::TAB_SETTLEMENT,array('amount'=>$update_amount));
			if(!$res){
				$this->db->trans_rollback();
				return array('msg'=>'record update fail','param'=>$record);
			}
			$sid = $row['id'];
			$res = $this->update_debt_set_id($debt_ids,$sid,'set_id');
			if(!$res){
				$this->db->trans_rollback();
				return array('msg'=>'update debt_set_id fail','param'=>$record);
			}
			$res = $this->db->trans_commit();
			if(!$res){
				return array('msg'=>'record update trans_commit fail','data'=>$new_record);
			}
			return true;
		}
		return array('msg'=>'param is empty','param'=>$record);
	}

	/**
	 * 更新转账汇总记录金额
	 */
	public function save_settlement_record($inter_id,$hotel_id=0,$type,$amount,$debt_ids){
		$obj = $this->db->where(array(
			'inter_id' => $inter_id,
			'hotel_id' => $hotel_id,
			'type' => $type,
			'status' => 0,
			'add_time>=' => date('Y-m-d 00:00:00'),
			'add_time<' => date('Y-m-d 00:00:00',strtotime('+1 day')),
			))->get(self::TAB_SETTLEMENT);
		$row = $obj->row_array();
		if(!$row){
			return array('msg'=>'record not exists','param'=>func_get_args());
		}
		$nums = $obj->num_rows();
		if($nums>1){
			return array('msg'=>'record nums>1','param'=>func_get_args());
		}
		$this->db->trans_begin();
		$res = $this->db->where('id',$row['id'])->update(self::TAB_SETTLEMENT,array('amount'=>$amount+$row['amount']));
		if(!$res){
			$this->db->trans_rollback();
			return array('msg'=>'record update fail','param'=>$record);
		}
		$sid = $row['id'];
		$res = $this->update_debt_set_id($debt_ids,$sid,$type.'_id');
		if(!$res){
			$this->db->trans_rollback();
			return array('msg'=>'update debt_set_id fail','data'=>$new_record);
		}
		$res = $this->db->trans_commit();
		if(!$res){
			return array('msg'=>'record update trans_commit fail','data'=>$new_record);
		}
		return true;
	}

	/**
	 * 更新欠款记录id
	 */
	public function update_debt_set_id($debt_ids = array(),$sid =0 ,$field = 'set_id'){
		if(!empty($debt_ids)){
			$this->db->where_in('id',$debt_ids);
			return $this->db->update(self::TAB_DEBT_RECORD,array($field=>$sid));
		}
		return array('msg'=>'debt_ids is empty','data'=>func_get_args());
	}

	/**
	 * 获取分账通用类型配置
	 */
	public function get_configs($inter_id,$type,$module='hotel'){
		$this->db->where(array(
			'inter_id' => $inter_id,
			'module' => $module,
			'type' => $type,
			));
		return $this->db->get(self::TAB_CONFIGS)->row_array();
	}
}