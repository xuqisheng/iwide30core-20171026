<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Member_interface extends CI_Controller {
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
	public function get_openid_member() {
		try {
			$now = time ();
			$this->load->model ( 'interface/Icommon_model' );
			$this->load->model ( 'interface/Isigniture_model' );
			$this->load->model ( 'hotel/Member_model' );
			$source = $this->Icommon_model->_base_input_valid ();
			if (empty ( $source ['openid'] )) {
				$this->Icommon_model->out_put_msg ( FALSE, 'wrong openid' );
			}
			$inter_id = $source ['itd'];
			$result = array (
					's' => 0,
					'errmsg' => '' 
			);
			
			$member = $this->Member_model->check_openid_member ( $inter_id, $source ['openid'] );
			
			$this->load->helper ( 'common' );
			$this->load->model ( 'common/Webservice_model' );
			$this->Webservice_model->add_webservice_record ( $inter_id, 'localmember', $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'], $source, $member, 'rec_post', $now, microtime (), getIp () );
			
			if (! empty ( $member ) && $member->is_login == t && ! empty ( $member->membership_number )) {
				$member = $this->Member_model->get_openid_member ( $inter_id, $source ['openid'], array (
						'update' => 1 
				) );
				if (! empty ( $member )) {
					unset ( $member['cardListDto'] );
					unset ( $member['resultCode'] );
					$info ['member_info'] = $member;
					// $info ['member_info'] = empty ( $member->pms_info ) ? array () : $member->pms_info;
					$this->Icommon_model->out_put_msg ( TRUE, '', $info );
				} else {
					$this->Icommon_model->out_put_msg ( FALSE, '无会员登录信息' );
				}
			} else {
				$this->Icommon_model->out_put_msg ( FALSE, '无会员登录信息' );
			}
		} catch ( Exception $ex ) {
			$this->Icommon_model->out_put_msg ( FALSE );
		}
	}

}