<?php
class Distribution extends CI_Controller {
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 写入分销绩效
	 */
	public function index() {
		$this->load->model ( 'api/common_model' );
		$this->load->model ( 'api/signiture_model' );
		$source = $this->common_model->_base_input_valid ();
		$field_array = array('order_id','product','id','inter_id','hotel_id','order_hotel','order_status','fans_hotel','hotel_rate','group_rate','jfk_rate','hotel_grades','group_grades','jfk_grades','order_time','saler','grade_openid','grade_table','grade_id','grade_id_name','order_amount','grade_total','grade_amount','grade_time','status','grade_amount_rate','grade_rate_type','remark','deliver_batch','last_update_time','partner_trade_no','send_time','deliver_fail','grade_typ');
		$data = json_decode(str_replace('\"', '"', $source['data']),TRUE);
		foreach ($data as $k=>$v){
			if(!in_array($k, $field_array))
				unset($data[$k]);
		}
		if(!isset($data['grade_id']) || empty($data['grade_id']))
			$data['grade_id'] = $data['order_id'];
		if(!isset($data['grade_id_name']) || empty($data['grade_id_name']))
			$data['grade_id_name'] = 'order_id';
		if(!isset($data['grade_table']) || empty($data['grade_table']))
			$data['grade_table'] = 'iwide_api';
		if(!isset($data['intet_id']) || empty($data['intet_id']))
			$data['inter_id'] = $source['itd'];
		$this->load->model ( 'distribute/idistribute_model' );
		if ($this->idistribute_model->create_dist ( $data))
			$rs = '{"errmsg":"ok"}';
		else
			$rs = '{"errmsg":"参数错误"}';
		echo $rs;
		$this->write_log( 'WRITE_GRADES | '. file_get_contents ( 'php://input' ).' | '.$rs);
	}
	
	/**
	 * 获取分销员信息
	 * @param POST {'itd':'','openid':'','saler':'','signature':''}
	 */
	public function get_saler(){
		$this->load->model ( 'api/common_model' );
		$this->load->model ( 'api/signiture_model' );
		$source = $this->common_model->_base_input_valid ();
		$this->load->model('distribute/qrcodes_model');
		if((!isset($source['openid']) && !isset($source['saler_id'])) || (empty($source['openid']) && empty($source['saler_id']))){
			$res = '{"errmsg":"参数错误"}';
			echo $res;
			$this->write_log( 'GET_SALER | '. file_get_contents ( 'php://input' ).' | '.$res);
			exit();
		}
		$params = array('inter_id'=>$source['itd']);
		if(isset($source['openid']) && !empty($source['openid'])){
			$params['openid'] = $source['openid'];
		}
		if(isset($source['saler_id']) && !empty($source['saler_id'])){
			$params['qrcode_id'] = $source['saler_id'];
		}
		$saler_info = $this->qrcodes_model->get_qrcodes_base($params,array('name','hotel_name','cellphone','qrcode_id','master_dept','openid'),'array');
		if($saler_info){
			$saler_info = $saler_info[0];
			$saler_info['saler_id'] = $saler_info['qrcode_id'];
			$saler_info['dept']     = $saler_info['master_dept'];
			unset($saler_info['qrcode_id']);
			unset($saler_info['master_dept']);
			$rs = json_encode($saler_info,JSON_UNESCAPED_UNICODE);
		}else{
			$rs = '{"errmsg":"查找失败"}';
		}
		echo $rs;
		$this->write_log('GET_SALER | '. file_get_contents ( 'php://input' ).' | '.$rs);
	}
	
	
	private function write_log($content) {
		$file = date ( 'Y-m-d' ) . '.txt';
		$path = APPPATH . 'logs' . DS . 'api' . DS . 'distribution' . DS;
		if (! file_exists ( $path )) {
			@mkdir ( $path, 0777, TRUE );
		}
		$fp = fopen ( $path . $file, 'a' );
		
		$CI = & get_instance ();
		$ip = $CI->input->ip_address ();
		$content = "[" . date ( 'Y-m-d H:i:s' ) . ']' . " | " . $ip . " | " . $content . "\n";
		fwrite ( $fp, $content );
		fclose ( $fp );
	}
}