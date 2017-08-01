<?php
//error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wxauth extends CI_Controller {
    
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
	}
	/**
	 * 返回Access_token 
	 */
	public function get_access_token(){
		try {
			$this->load->model('wx/access_token_model');
			$this->load->model ( 'api/common_model' );
			$source = $this->common_model->_base_input_valid();
			$this->load->model ( 'api/signiture_model' );
			$access_token = $this->access_token_model->get_access_token($source['itd'],TRUE);
			if(!isset($access_token['access_token'])){
				echo '{"errmsg":"system error"}';
				exit;
			}
			$this->load->helper('common');
			$token = $this->common_model->get_inter_id_token ( $source ['itd'] );
			$access_token['timestamp'] = time();
			$access_token['itd']       = $source ['itd'];
			$access_token['noncestr']  = createNoncestr();
			$access_token['signiture'] = $this->signiture_model->get_sign ( $access_token, $token );
			echo json_encode($access_token);
			exit;
		}catch (Exception $ex){
			throw $ex;
// 			echo '{"errmsg":"system error"}';
// 			exit;
		}
	}
	/**
	 * 刷新并返回Access_token 
	 */
	public function reflash_access_token(){
		try {
			$this->load->model('wx/access_token_model');
			$this->load->model ( 'api/common_model' );
			$source = $this->common_model->_base_input_valid();
			$this->load->model ( 'api/signiture_model' );
			$access_token = $this->access_token_model->reflash_access_token($source['itd'],TRUE);
			if(!isset($access_token['access_token'])){
				echo '{"errmsg":"system error"}';
				exit;
			}
			$this->load->helper('common');
			$token = $this->common_model->get_inter_id_token ( $source ['itd'] );
			$access_token['timestamp'] = time();
			$access_token['itd']       = $source ['itd'];
			$access_token['noncestr']  = createNoncestr();
			$access_token['signiture'] = $this->signiture_model->get_sign ( $access_token, $token );
			echo json_encode($access_token);
			exit;
		}catch (Exception $ex){
			echo '{"errmsg":"system error"}';
			exit;
		}
	}
	/**
	 * 返回Api_ticket 
	 */
	public function get_api_ticket(){
// 		$this->load->model('wx/access_token_model');
// 		echo $this->access_token_model->get_api_ticket($this->input->get('itd'));
// 		echo '{"errmsg":"接口尚未开放,敬请期待"}';
// 		exit;
		try {
			$this->load->model('wx/access_token_model');
			$this->load->model ( 'api/common_model' );
			$source = $this->common_model->_base_input_valid();
			$this->load->model ( 'api/signiture_model' );
			$ticket = $this->access_token_model->get_api_ticket($source['itd']);
			if(!isset($ticket)){
				echo '{"errmsg":"system error"}';
				exit;
			}
			echo json_encode(array('errmsg'=>'ok','data'=>$ticket));
			exit;
		}catch (Exception $ex){
// 			throw $ex;
			echo '{"errmsg":"system error"}';
			exit;
		}
	}
	
	public function sign(){
		$nonce_str = '702jovy3f5ckigtvh0wn4lbbz0um9lyi';
		$this->load->model ( 'api/signiture_model' );
		$source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		echo $this->signiture_model->get_sign ( $source, $nonce_str );
	}
	public function redpack() {
		try {
			$this->load->model ( 'api/common_model' );
			$source = $this->common_model->_base_input_valid ();
			$this->load->model ( 'api/signiture_model' );
			$this->load->helper ( 'common' );
			$token = $this->common_model->get_inter_id_token ( $source ['itd'] );
				
			$this->load->model ( 'pay/wxpay_model' );
			$arr ['nonce_str'] = $this->wxpay_model->createNoncestr();
			$pay_params = $this->wxpay_model->get_pay_paras($source ['itd']);
			if(isset($pay_params['pay_mch_id'])){
				$pay_params['mch_id'] = $pay_params['pay_mch_id'];
			}
			if(isset($pay_params['pay_key'])){
				$pay_params['key'] = $pay_params['pay_key'];
			}
			//@Editor lGh 2016-5-23 00:31:19 放下面干嘛呢
			if(isset($pay_params['pay_app_id'])){
				$pay_params['app_id'] = $pay_params['pay_app_id'];
			}
			// 组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
			$arr ['mch_billno'] = $pay_params['mch_id'] . date ( 'Ymd' ) . substr ( time (), 3 ) . mt_rand ( 100, 999 );
			$arr ['mch_id'] = $pay_params['mch_id'];
			$arr ['wxappid'] = $pay_params['app_id'];
			$arr ['nick_name'] = isset ( $source ['nick_name'] ) ? $source ['nick_name'] : '';
			$arr ['send_name'] = isset ( $source ['send_name'] ) ? $source ['send_name'] : '';
			$arr ['re_openid'] = isset ( $source ['re_openid'] ) ? $source ['re_openid'] : '';
			$arr ['total_amount'] = isset ( $source ['total_amount'] ) ? $source ['total_amount'] : '';
			$arr ['min_value'] = isset ( $source ['min_value'] ) ? $source ['min_value'] : '';
			$arr ['max_value'] = isset ( $source ['max_value'] ) ? $source ['max_value'] : '';
			$arr ['total_num'] = isset ( $source ['total_num'] ) ? $source ['total_num'] : '';
			$arr ['wishing'] = isset ( $source ['wishing'] ) ? $source ['wishing'] : '';
			$arr ['client_ip'] = $_SERVER ["REMOTE_ADDR"];
			$arr ['act_name'] = isset ( $source ['act_name'] ) ? $source ['act_name'] : '';
			$arr ['remark'] = isset ( $source ['remark'] ) ? $source ['remark'] : '';
			$this->load->model ( 'wxpay_model' );
// 			if(isset($pay_params['pay_app_id'])){
// 				$pay_params['app_id'] = $pay_params['pay_app_id'];
// 			}
			$arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ( 'key' => $pay_params['key'], 'app_id' => $pay_params['app_id'] ) );
			$this->load->helper ( 'common' );
			$extras = array();
			// 			$extras['CURLOPT_CAINFO'] = dirname(__FILE__) . '/rootca.pem';
			// 			$extras['CURLOPT_SSLCERT'] = dirname(__FILE__) . '/apiclient_cert.pem';
			// 			$extras['CURLOPT_SSLKEY'] = dirname(__FILE__) . '/apiclient_key.pem';
			$extras ['CURLOPT_CAINFO'] = realpath('../certs').DS."rootca_" . $pay_params ['mch_id'] . ".pem";
			$extras ['CURLOPT_SSLCERT'] = realpath('../certs').DS."apiclient_cert_" . $pay_params ['mch_id'] . '.pem';
			$extras ['CURLOPT_SSLKEY'] = realpath('../certs').DS."apiclient_key_" . $pay_params ['mch_id'] . '.pem';
			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack ';
			// 		exit('11');
			$result = doCurlPostRequest($url,$this->wxpay_model->arrayToXml($arr),$extras);
			$data=json_decode(json_encode(simplexml_load_string($result,NULL,LIBXML_NOCDATA)),true);
			$this->db->insert('weixin_text',array('content'=>':redpack->send->'.$this->wxpay_model->arrayToXml($arr).',extra:'.json_encode($extras).',pay_param:'.json_encode($pay_params),'edit_date'=>date('Y-m-d H:i:s')));
			$this->db->insert('weixin_text',array('content'=>':redpack->receive->'.$result,'edit_date'=>date('Y-m-d H:i:s')));
			// 			if($data['return_code']=='SUCCESS'&&$data['result_code']=='SUCCESS')
				// 				echo true;
			echo $result;
				// 			else
					// 				echo false;
				exit;
		}catch (Exception $ex){
			echo $ex;
		}
	}
}

/* End of file Wxpaytest.php */
/* Location: ./application/controllers/Wxpaytest.php */