<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Wxpay extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '支付配置';
	protected $label_action = '';
	const WX_PAY_TYPE = 'weixin';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'pay';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'pay/Pay_model';
	}
	public function pay_para() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$list = $model->get_pay_paras ( $this->inter_id, 'weixin' );
		if (empty ( $list ))
			$list = array (
					'mch_id' => '',
					'sub_mch_id' => '',
					'key' => '',
					'app_id' => '', 
					'pay_mch_id' => '',
					'pay_key' => '',
					'pay_app_id' => '',
					'pay_cmbc_chnl_id' => '',//民生分账支付子商户号
			);
			else{
				foreach($list as $k=>$l){
					$list[$k]=substr_replace($l,'****',strlen ( $l ) * 0.2,strlen ( $l ) * 0.5);
				}
			}
		$data ['list'] = $list;
		$this->_render_content ( $this->_load_view_file ( 'pay_edit' ), $data, false );
	}
	public function edit_post() {
		$mch_id = $this->input->post ( 'mch_id', true );
		$app_id = $this->input->post ( 'app_id', true );
		$key = $this->input->post ( 'key', true );
		$sub_mch_id = $this->input->post ( 'sub_mch_id', true );
		//民生分账支付子商户号
		$pay_cmbc_chnl_id = $this->input->post ( 'pay_cmbc_chnl_id', true );
		$model = $this->_load_model ( $this->main_model_name () );
		$data = array (
				'inter_id' => $this->inter_id,
				'pay_type' => 'weixin' 
		);
		$params = array (
				'key',
				'mch_id',
				'app_id',
				'pay_key',
				'pay_mch_id',
				'pay_app_id',
				'sub_mch_id',
				'pay_cmbc_chnl_id', //民生分账支付子商户号
		);
		$list = $model->get_pay_paras ( $this->inter_id, 'weixin' );
		foreach ( $params as $p ) {
			$tmp = $this->input->post ( $p, true );
			if (empty ( $list )) {
				if (! empty ( $tmp )) {
					$data ['param_name'] = $p;
					$data ['param_value'] = $tmp;
					$model->replace_param ( $data );
				}
			} else {
				if (isset ( $list [$p] ) && !empty($tmp)) {
					$data ['param_name'] = $p;
					$data ['param_value'] = $tmp;
					$model->replace_param ( $data );
				} elseif (! empty ( $tmp )) {
					$data ['param_name'] = $p;
					$data ['param_value'] = $tmp;
					$model->replace_param ( $data );
				}
			}
		}
		redirect(site_url('pay/pay/ways'));
	}
	public function cert_upload_url(){
		$model = $this->_load_model ( $this->main_model_name () );
		$list = $model->get_pay_paras ( $this->inter_id, 'weixin' );
		$config ['upload_path'] = '../certs';
		if (! file_exists ( $config ['upload_path'] )) {
			mkdir ( $config ['upload_path'], 0777, true );
		}
		if(empty ( $list )){
			echo json_encode ( array ( 'error' => '请先保存商户信息' ) );die;
		}
		$filename = explode ( '.', $_FILES ['imgFile'] ['name'] );
		if(isset($list['pay_mch_id']))
			$config ['file_name'] = $filename[0].'_'.$list['pay_mch_id'];
		else 
			$config ['file_name'] = $filename[0].'_'.$list['mch_id'];
// 		$config ['allowed_types'] = 'p12|pem|txt';
		$config ['allowed_types'] = '*';
		$config ['max_size'] = '200';
		$config ['overwrite'] = TRUE;
		$this->load->library ( 'upload', $config );
		$this->upload->initialize ( $config );
		
		if ($this->upload->do_upload ( 'imgFile' )) {
			echo json_encode ( array ( 'errormsg' => 'ok' ) );
		} else {
			echo json_encode ( array ( 'errormsg' => $this->upload->display_errors () ) );
		}
	}
}
