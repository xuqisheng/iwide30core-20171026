<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Hotel_interface extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
		ini_set ( 'display_errors', 0 );
		if (version_compare ( PHP_VERSION, '5.3', '>=' )) {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_USER_NOTICE & ~ E_USER_DEPRECATED );
		} else {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_STRICT & ~ E_USER_NOTICE );
		}
	}
	public function send_order_template_msg() {
		try {
			$this->load->model ( 'plugins/Template_msg_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$this->load->model ( 'interface/Isigniture_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$this->load->model ( 'hotel/Order_check_model' );
			if (empty ( $source ['order_id'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'wrong order id' );
			}
			$inter_id = $source ['itd'];
			$order = $this->Order_check_model->get_order_by_weborderid ( $inter_id, $source ['order_id'] );
			if (empty ( $order )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'no order found' );
			}
			$result = array (
					's' => 0,
					'errmsg' => '' 
			);
			if (! empty ( $source ['template_type'] )) {
				$result = $this->Template_msg_model->send_hotel_order_msg ( $order, $source ['template_type'],NULL );
			} else {
				if (empty ( $source ['template_content'] ) || empty ( json_decode ( $source ['template_content'] ) )) {
					$this->Icommon_model->out_put_msg ( FALSE, 'no or wrong template content (json required)' );
				}
				$msg_type = 'hotel_interface';
				$json = json_decode ( $source ['template_content'], TRUE );
				$json ['touser'] = $order ['openid'];
				if (! empty ( $source ['url_type'] )) {
					$this->load->model ( 'common/Enum_model' );
					$urls = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_TMPMSG_URL' );
					if (! empty ( $urls [$source ['url_type']] )) {
						$find_replace = $this->Template_msg_model->order_find_replace ( $order );
						$this->load->model ( 'wx/Publics_model' );
						$public = $this->Publics_model->get_public_by_id ( $order ['inter_id'] );
						$json ['url'] = $public ['domain'] . str_replace ( $find_replace ['find'], $find_replace ['replace'], $urls [$source ['url_type']] );
					}
				}
				$result = $this->Template_msg_model->send_template_msg ( $inter_id, $json, $msg_type );
			}
			$errmsg = empty ( $result ['errmsg'] ) ? '' : $result ['errmsg'];
			if ($result ['s'] == 1) {
				$this->Icommon_model->out_put_msg ( TRUE, $errmsg );
			} else {
				$datas = empty ( $result ['datas'] ) ? array () : $result ['datas'];
				$this->Icommon_model->out_put_msg ( FALSE, $errmsg, $datas );
			}
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	public function send_wx_template_msg() {
		try {
			$this->load->model ( 'plugins/Template_msg_model' );
			$this->load->model ( 'interface/Icommon_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			$inter_id = $source ['itd'];
			$result = array (
					's' => 0,
					'errmsg' => '' 
			);
			$json = json_decode ( $source ['template_content'], TRUE );
			if (empty($json)){
				$this->Icommon_model->out_put_msg ( FALSE, 'no or wrong template content (json required)' );
			}
			$result = $this->Template_msg_model->send_template_msg ( $inter_id, $json, 'interface' );
			$errmsg = empty ( $result ['errmsg'] ) ? '' : $result ['errmsg'];
			if ($result ['s'] == 1) {
				$this->Icommon_model->out_put_msg ( TRUE, $errmsg );
			} else {
				$datas = empty ( $result ['datas'] ) ? array () : $result ['datas'];
				$this->Icommon_model->out_put_msg ( FALSE, $errmsg, $datas );
			}
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
	public function get_wx_url() {
		try {
			$this->load->model ( 'common/Enum_model' );
			$urls = $this->Enum_model->get_enum_des ( 'HOTEL_ORDER_TMPMSG_URL' );
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}
}