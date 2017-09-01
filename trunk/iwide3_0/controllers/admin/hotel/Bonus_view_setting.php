<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Bonus_view_setting extends MY_Admin {
	protected $label_controller = '积分显示设置';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'hotel/Bonus_view_model';
	}
	public function index() {
		$data = $this->common_data;
		$this->load->model ( 'hotel/Hotel_config_model' );
		$model = $this->_load_model ( $this->main_model_name () );

        $config_data = $this->Hotel_config_model->get_hotels_config_row ( $this->inter_id, 'HOTEL', 0, 'BONUS_VIEW_SETTING' );

        $data['value'] = 0;

		if (! empty ( $config_data )) {
            $data['value'] = $config_data['param_value'];
		}

		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}

    public function edit_post() {

        $data = $this->input->post();

        $return = array(
            'code'=>1,
            'info'=>'保存失败'
        );

        if(isset($data['value'])){

            $this->load->model ( 'hotel/Hotel_config_model' );
            $update = array('param_value'=>$data['value']);
            $model = $this->_load_model ( $this->main_model_name () );

            $config_data = $this->Hotel_config_model->get_hotels_config_row ( $this->inter_id, 'HOTEL', 0, 'BONUS_VIEW_SETTING' );

            if(!empty($config_data)){
                $res = $model->update_hotel_config($this->inter_id, 'BONUS_VIEW_SETTING',$update);
            }else{
                $res = $model->new_bonus_view_setting($this->inter_id,$update);
            }


            if($res){
                $return['code'] = 2;
                $return['info'] = '保存成功';
            }
        }

        return $return;


	}
}
