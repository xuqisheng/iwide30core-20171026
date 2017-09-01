<?php
class Icommon_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	public function _base_input_valid() {
		$source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		if ($source) {
			// if (! isset ( $source ['signature'] )) {
			// $this->out_put_msg ( FALSE, 'Invalid Signature' );
			// }
			// $sign = $source ['signature'];
			// unset ( $source ['signature'] );
			// $this->load->model ( 'interface/Isigniture_model' );
			// $token = $this->get_inter_id_token ( $source ['itd'] );
			// if (empty ( $token )) {
			// $this->out_put_msg ( FALSE, 'Invalid Parameter "itd"' );
			// }
			// $signature = $this->Isigniture_model->get_sign ( $source, $token );
			// if ($sign != $signature) {
			// $this->out_put_msg ( FALSE, 'Signiture error' );
			// }
			return $source;
		} else {
			$this->out_put_msg ( 1 );
			exit ();
		}
	}
	public function out_put_msg($result, $msg = '', $data = array(), $msg_lv = 0, $exit = TRUE) {
		$info = array ();
		$status_arr = $this->enums ( 'status' );
		$msg_lvs = $this->enums ( 'msg_lv' );
		$result = isset ( $status_arr [$result] ) ? $status_arr [$result] : 1004;
		$info ['status'] = $result;
		$info ['msg'] = $msg;
		$info ['msg_type'] = $msg_lvs [$msg_lv];
		empty ( $data ) ?  : $info ['web_data'] = $data;
		echo json_encode ( $info, JSON_UNESCAPED_UNICODE );
		if ($exit) {
			exit ();
		}
	}
	public function enums($type) {
		switch ($type) {
			case 'status' :
				// 1000:成功
				// 1001：失败，前端用toast显示错误提示（不需要用户操作，自动消失）
				// 1002：失败，前端用alert显示错误提示（要点击确认）
				// 1003：未登状态。
				return array (
						1 => 1000,
						2 => 1001,
						3 => 1000,
						4 => 1003 
				);
				break;
			case 'errcode' :
				return array ();
				break;
			case 'msg_lv' :
				return array (
						0 => '',
						1 => 'toast',
						2 => 'alert' 
				);
				break;
			default :
				return array ();
				break;
		}
	}
}
