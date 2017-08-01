<?php
class Order_api_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	function get_roomstate($inter_id, $hotel_id, $params = array()) {
		$this->load->helper ( 'common' );
		$this->load->model ( 'interface/Isigniture_model' );
		$this->load->model ( 'wx/Publics_model' );
		$public = $this->Publics_model->get_public_by_id ( $inter_id );
		$url = $public['domain'].'/index.php/interface/order_interface/get_roomstate';
		$data = array (
				'timestamp' => time (),
				'noncestr' => createNoncestr (),
				'itd' => $inter_id 
		);
		$data ['hotel_id'] = $hotel_id;
		$data ['startdate'] = empty ( $params ['startdate'] ) ? date ( 'Ymd', time () ) : date ( 'Ymd', strtotime ( $params ['startdate'] ) );
		$data ['enddate'] = empty ( $params ['enddate'] ) ? date ( 'Ymd', strtotime ( '+ 1 day', strtotime ( $data ['startdate'] ) ) ) : date ( 'Ymd', strtotime ( $params ['enddate'] ) );
		$data ['signature'] = $this->Isigniture_model->get_sign ( $data, $public ['token'] );
		return json_decode ( doCurlPostRequest ( $url, json_encode ( $data ), '', 30 ), true );
	}
}