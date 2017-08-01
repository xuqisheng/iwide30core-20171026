<?php
class Icommon_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * @todo 基本信息校验
	 * @return array 基础数据
	 */
	public function _base_input_valid() {
		$source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		if ($source) {
			if (! isset ( $source ['signature'] )) {
				$this->out_put_msg ( FALSE, 'Invalid Signature' );
			}
			$sign = $source ['signature'];
			unset ( $source ['signature'] );
			$this->load->model ( 'interface/Isigniture_model' );
			$token = $this->get_inter_id_token ( $source ['itd'] );
			if (empty ( $token )) {
				$this->out_put_msg ( FALSE, 'Invalid Parameter "itd"' );
			}
			$signature = $this->Isigniture_model->get_sign ( $source, $token );
			if ($sign != $signature) {
				$this->out_put_msg ( FALSE, 'Signiture error' );
			}
			return $source;
		} else {
			$this->out_put_msg ( FALSE );
			exit ();
		}
	}
	public function get_inter_id_token($inter_id){
		$db=$this->load->database('iwide_r1',true);
		$db->where(array('inter_id'=>$inter_id));
		$db->limit(1);
		$res = $db->get('publics')->row_array();
		if (!empty($res))
			return $res['token'];
		return NULL;
	}
	public function out_put_msg($result, $msg = '', $data = array(), $err_code = NULL,$exit=TRUE) {
		$info = array ();
		if (empty ( $msg )) {
			$msg = $result === TRUE ? 'ok' : 'unknown error';
		}
		$result = $result === TRUE ? 1 : 0;
		$info ['result_code'] = $result;
		$info ['errmsg'] = $msg;
		isset ( $err_code ) ? $info ['errcode'] = $err_code : 1;
		empty ( $data ) ?  : $info ['data'] = $data;
		echo json_encode ( $info );
		if ($exit){
			exit ();
		}
	}
}
