<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Center_openid_map_model extends MY_Model_Soma {

	public function table_name() {
		return 'soma_center_openid_map';
	}

	/**
	 * 通过酒店inter_id，openid查询中心平台openid信息
	 *
	 * @param      string  $inter_id  公众号ID
	 * @param      string  $openid    公众号对应的openid
	 *
	 * @return     array   一个openid映射记录
	 */
	public function get_center_openid_info($inter_id, $openid) {
		$table = $this->_shard_table_r($this->table_name());
		return $this->_shard_db_r('iwide_soma_r')
			->where('hotel_inter_id', $inter_id)
			->where('hotel_openid', $openid)
			->limit(1)->get($table)->row_array();
	}


	/**
	 * 数据写入前校验,已有的记录不再更新
	 *
	 * @param      array    $data   接收到的数据
	 *
	 * @return     boolean  允许写入返回true,不允许写入返回false;
	 */
	public function data_validation($data) {
		$this->load->library('form_validation');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('center_inter_id', '中心平台-公众号', 'required');
        $this->form_validation->set_rules('center_openid', '中心平台-openid', 'required');
        $this->form_validation->set_rules('hotel_inter_id', '酒店平台-公众号', 'required');
        $this->form_validation->set_rules('hotel_openid', '酒店平台-openid', 'required');

        if($this->form_validation->run()) {
        	$center_info = $this->get_center_openid_info($data['hotel_inter_id'], $data['hotel_openid']);
        	if(count($center_info) <= 0) { return true; }
        }

        return false;
	}

	/**
	 * 将数据格式化为openid映射记录格式
	 *
	 * @param      string  $inter_id    中心平台公众号ID
	 * @param      string  $openid      中心平台openid
	 * @param      array   $input_data  用户输入数据
	 */
	public function format_map_record_data($inter_id, $openid, $data) {
		$_fmt_data['center_inter_id'] = $inter_id;
		$_fmt_data['center_openid'] = $openid;
		if(isset($data['inter_id'])) {
			$_fmt_data['hotel_inter_id'] = $data['inter_id'];
		}
		if(isset($data['openid'])) {
			$_fmt_data['hotel_openid'] = $data['openid'];
		}
		if(isset($data['hotel_id'])) {
			$_fmt_data['hotel_hotel_id'] = $data['hotel_id'];
		}
		return $_fmt_data;
	}

	/**
	 * 保存一个映射记录
	 *
	 * @param      array    $data   需要插入的数据
	 *
	 * @return     boolean  插入数据成功返回true,失败返回false.
	 */
	public function save_map_record($data) {
		$table = $this->_shard_table($this->table_name());
		try {
			return $this->_shard_db()->insert($table, $data);
		} catch (Exception $e) {
			return false;
		}
	}


	/**
	 * 获取酒店openid集合
	 *
	 * @param      string  $inter_id  中心平台inter_id
	 * @param      string  $openid    中心平台openid
	 */
	public function get_hotel_openid_collection($inter_id, $openid) {
		$table = $this->_shard_table($this->table_name());
		$result = $this->_shard_db()
			->where('center_inter_id', $inter_id)
			->where('center_openid', $openid)->get($table)->result_array();

		$collection = array();
		foreach ($result as $row) {
			$collection[] = $row['hotel_openid'];
		}
		return $collection;
	}

}