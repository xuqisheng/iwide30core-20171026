<?php
/**
 * @todo 公共输出接口基本验证
 * @author ounianfeng
 * @version 1.0
 * @since 2016-01-15
 * @return Array 返回如果验证通过返回接口传过来的数据，否则直接输出错误信息退出处理
 */
class Common_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	/**
	 * @todo 取公共接口双方约定的token
	 * @param string 公众号识别码
	 * @return string 双方约定的token
	 */
	public function get_inter_id_token($inter_id){
		$this->db->where(array('inter_id'=>$inter_id));
		$this->db->limit(1);
		$res = $this->db->get('publics')->row_array();
		return $res['token'];
	}

	/**
	 * @todo 基本信息校验
	 * @return array 基础数据
	 */
	public function _base_input_valid(){
		$source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
		if ($source) {
			if (! isset ( $source ['signature'] )) {
				$rs = '{"errmsg":"Invalid Signature"}';
				echo $rs;
				$this->write_log('BASE_INPUT_VALID | '.file_get_contents ( 'php://input' ).' | '.$rs);
				exit ();
			}
			$sign = $source ['signature'];
			unset ( $source ['signature'] );
			$this->load->model ( 'api/signiture_model' );
			$this->load->model ( 'api/common_model' );
			$token = $this->common_model->get_inter_id_token ( $source ['itd'] );
			if (empty ( $token )) {
				$rs = '{"errmsg":"Invalid Parameter\"itd\",'.json_encode($source).' "}';
				echo $rs;
				$this->write_log('BASE_INPUT_VALID | '.file_get_contents ( 'php://input' ).' | '.$rs);
				exit ();
			}
			$signature = $this->signiture_model->get_sign ( $source, $token );
			if ($sign != $signature) {
				$rs = '{"errmsg":"Signiture error"}';
				echo $rs;
				$this->write_log('BASE_INPUT_VALID | '.file_get_contents ( 'php://input' ).' | '.$rs);
				exit ();
			}
			return $source;
		} else {
			$rs = '{"error":"-1"}';
			echo $rs;
			$this->write_log('BASE_INPUT_VALID | '.file_get_contents ( 'php://input' ).' | '.$rs);
			exit;
		}
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