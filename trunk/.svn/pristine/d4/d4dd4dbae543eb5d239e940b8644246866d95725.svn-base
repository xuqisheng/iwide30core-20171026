<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getcardrecord extends MY_Model
{
	const TABLE_GETCARD_RECORD     = 'iwide_member_record_rule_getcard';
	
	public function updateRecord($mem_id, $ci_id, $record_id, $num, $inter_id)
	{
		try {
		    $writeAdapter = $this->load->database('member_write',true);
			$object = $this->getRecordByMemRecord($mem_id,$ci_id,$record_id,$inter_id);
			if($object) {
				$result = $writeAdapter->update(self::TABLE_GETCARD_RECORD, array('num'=>$num), array('rrg_id' => $object->rrg_id));
			} else {
				$data = array('inter_id'=>$inter_id,'ci_id'=>$ci_id,'mem_id'=>$mem_id,'record_id'=>$record_id,'num'=>$num);
				return $writeAdapter->insert(self::TABLE_GETCARD_RECORD, $data);
			}
		} catch (Exception $e) {
			return false;
		}
	
		return false;
	}
	
	public function getRecordByMemRecord($mem_id,$ci_id,$record_id,$inter_id)
	{
		$readAdapter = $this->load->database('member_read',true);
		$query = $readAdapter->get_where(self::TABLE_GETCARD_RECORD, array('mem_id'=>$mem_id,'ci_id'=>$ci_id,'record_id'=>$record_id,'inter_id'=>$inter_id));
		return $query->row();
	}
}