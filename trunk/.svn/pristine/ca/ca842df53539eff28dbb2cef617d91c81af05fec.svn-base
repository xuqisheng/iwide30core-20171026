<?php

class Wftpay extends MY_Admin{
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '支付配置';
	protected $label_action = '';
	const PAY_TYPE = 'weifutong';

	function __construct(){
		parent::__construct();
		$this->inter_id = $this->session->get_admin_inter_id();
		$this->module = 'pay';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash();
		// $this->output->enable_profiler ( true );
	}

	protected function main_model_name(){
		return 'pay/Pay_model';
	}

	public function pay_para(){
		$data = $this->common_data;
		$model = $this->_load_model($this->main_model_name());
		$list = $model->get_pay_paras($this->inter_id, self::PAY_TYPE);

		$this->load->model('hotel/Hotel_model');
		$all_hotels=$this->Hotel_model->get_all_hotels($this->inter_id,1);

		if(empty ($list)){
			$list = array(
				'mch_id' => '',
				'key'    => '',
			);
			foreach($all_hotels as $v){
				$list['sub_mch_id_h_'.$v['hotel_id']]='';
				$list['sub_key_h_'.$v['hotel_id']]='';
			}

		} else{
			foreach($list as $k => $l){
				$list[$k] = substr_replace($l, '****', strlen($l) * 0.2, strlen($l) * 0.5);
			}
		}
		$data ['list'] = $list;
		$data['hotels']=$all_hotels;
		$this->_render_content($this->_load_view_file('pay_edit'), $data, false);
	}

	public function edit_post(){
		$mch_id = $this->input->post('mch_id', true);
		$key = $this->input->post('key', true);
		$model = $this->_load_model($this->main_model_name());
		$data = array(
			'inter_id' => $this->inter_id,
			'pay_type' => self::PAY_TYPE
		);
		$params = array(
			'mch_id',
			'key',
		);

		$this->load->model('hotel/Hotel_model');
		$all_hotels=$this->Hotel_model->get_all_hotels($this->inter_id,1);
		foreach($all_hotels as $v){
			$params[]='sub_mch_id_h_'.$v['hotel_id'];
			$params[]='sub_key_h_'.$v['hotel_id'];
		}

		$list = $model->get_pay_paras($this->inter_id, self::PAY_TYPE);
		foreach($params as $p){
			$tmp = $this->input->post($p, true);

			if(!empty($tmp)){
				$data ['param_name'] = $p;
				$data ['param_value'] = $tmp;
				$model->replace_param($data);
			}

			/*if(empty ($list)){
				if(!empty ($tmp)){
					$data ['param_name'] = $p;
					$data ['param_value'] = $tmp;
					$model->replace_param($data);
				}
			} else{
				if(isset ($list [$p])){
					$data ['param_name'] = $p;
					$data ['param_value'] = $tmp;
					$model->replace_param($data);
				} elseif(!empty ($tmp)){
					$data ['param_name'] = $p;
					$data ['param_value'] = $tmp;
					$model->replace_param($data);
				}
			}*/
		}
		redirect(site_url('pay/pay/ways'));
	}
}