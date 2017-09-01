<?php
class User_info_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	function get_saler_info($inter_id, $openid, $status = NULL) {
		$this->load->model ( 'distribute/Staff_model' );
		$saler_info = $this->Staff_model->saler_info ( $openid, $inter_id, FALSE );
		if (! empty ( $status ) && ! empty ( $saler_info )) {
			if ($status == 'valid' && $saler_info ['status'] == 2) {
				return $saler_info;
			} else if ($status == 'process' && $saler_info ['status'] != 2) {
				return $saler_info;
			} else {
				return NULL;
			}
		}
		return $saler_info;
	}
}