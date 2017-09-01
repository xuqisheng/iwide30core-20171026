<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Pay extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '支付配置';
	protected $label_action = '';
	const ENUM_PAY_TYPE = 'PAY_WAY';
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
	public function index() {
		$data = $this->common_data;
		$model = $this->_load_model ( $this->main_model_name () );
		$list = $model->get_module_pay_config ( $this->inter_id, array (), 1 );
		$data ['list'] = $list;
        $types = array('weixin','weifutong');//默认微信
        $okpay_list = array();
        if(!empty($list)){
            foreach($list['hotel']['pay_ways'] as $k=>$v){
                if(in_array($v['pay_type'],$types) && $v['status'] == 1){
                    $okpay_list[$v['pay_type']] = $v;
                }
            }
        }
        $data['okpay_list'] = $okpay_list;//快乐付配置，设置默认支付方式
        //获取默认配置
        $okpay = $model->get_okpay_config($this->inter_id);
        if(empty($okpay)){
            $okpay = 'weixin';//weixin
        }
        $data['okpay'] = $okpay;
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}
	public function save_config() {
		$module = $this->input->post ( 'module' );
		$datas = json_decode ( $this->input->post ( 'datas' ), TRUE );
		if (! empty ( $datas )) {
			$this->load->model ( 'common/Enum_model' );
			$pay_types = $this->Enum_model->get_enum_des ( self::ENUM_PAY_TYPE ,1,$this->inter_id);
			$model = $this->_load_model ( $this->main_model_name () );
			foreach ( $datas as $k => $d ) {
				if (array_key_exists ( $k, $pay_types )) {
					$model->update_pay_config ( $this->inter_id, $module, $k, $d );
				}elseif ($k == 'outtime') {
					$model->replace_param(array(
						'inter_id' => $this->inter_id,
						'pay_type' => 'weixin',
						'param_name' => 'outtime',
						'param_value' => $d
						)
					);
				}
			}
			echo 1;
		} else
			echo 0;
	}
    //快乐付默认配置
    public function save_okpay_config(){
        $okpay = $this->input->post ( 'okpay_type' );
        if(!in_array($okpay,array('weixin','weifutong'))){
            echo 0;
            die;
        }

        $model = $this->_load_model ( $this->main_model_name () );
        $model->replace_param(array(
                'inter_id' => $this->inter_id,
                'pay_type' => 'weixin',
                'param_name' => 'okpay_type',
                'param_value' => $okpay,
            )
        );
        echo 1;
        die;
    }

	public function ways(){
		$data = $this->common_data;
		$model = $this->_load_model($this->main_model_name());
		$list = $model->get_module_pay_config($this->inter_id, array(), 1);

		$param = [
			'weixin'    => site_url('pay/wxpay/pay_para'),
			'weifutong' => site_url('pay/wftpay/pay_para'),
// 			'point'     => site_url('pay/jfpay/pay_para'),
			'lakala'     => site_url('pay/lakalapay/pay_para'),
			'lakala_y'     => site_url('pay/lakalapay/pay_para'),
			'unionpay'     => site_url('pay/unionpay/pay_para')
		];
		$flag = false;
		$result = [];
		foreach($list as $v){
			if(!empty($v['pay_ways'])){
				foreach($v['pay_ways'] as $t){
					if(array_key_exists($t['pay_type'], $param)){
						if(($t['pay_type']=='lakala' || $t['pay_type']=='lakala_y') && $flag){
							continue;
						}
						if($t['pay_type']=='lakala' || $t['pay_type']=='lakala_y'){
							$flag = true;
							$t['pay_name'] = '拉卡拉支付';
						}
						$result[] = [
							'url'  => $param[$t['pay_type']],
							'name' => $t['pay_name']
						];
					}
				}
			}
		}

		$data['lists'] = $result;

		$this->_render_content($this->_load_view_file('ways'), $data, false);
	}
}
