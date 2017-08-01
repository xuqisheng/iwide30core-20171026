<?php

class Vmember_model extends MY_Model{

	/**
	 * 获取会员资料
	 * @param $openid
	 * @param $inter_id
	 * @return array
	 */
	public function getUserInfo($openid, $inter_id){
		static $exists_usr = array();
		$k = md5($inter_id . '|' . $openid);
		if(isset($exists_usr[$k])){
			return $exists_usr[$k];
		}
		$this->load->model('hotel/Member_model');

		$this->load->helper('common');
		$check = $this->Member_model->check_openid_member($inter_id, $openid);
		$check=obj2array($check);
		$exists_usr[$k] = $check;

		return $check;
	}

	public function getPmsUserId($openid, $inter_id){
		$member = $this->getUserInfo($openid, $inter_id);
		if($member){
			return $member['pms_user_id'];
		}
		return '';
	}

	public function getLvlPmsCode($openid, $inter_id){
		$member = $this->getUserInfo($openid, $inter_id);
		if($member){
			return $member['lvl_pms_code'];
		}
		return '';
	}



	public function getUserCoupon($openid, $inter_id, $module = 'hotel', $num = '', $type = '', $is_pms = 0){
		/*
		 * $api_post = array(
				'openid'   => $openid,
				'token'    => $token,
				'inter_id' => $inter_id,
				'module'   => 'hotel',
				'num'      => '',
				'type'     => '',
				'is_pms'   => 1,
			);
		 */
		$token = $this->getApiToken();
		$params = array(
			'openid'   => $openid,
			'inter_id' => $inter_id,
			'module'   => $module,
			'num'      => $num,
			'type'     => $type,
			'is_pms'   => $is_pms,
			'token'    => $token,
		);


		$result = $this->curl_post('membercard/getlist', $params);
		if(!empty($result['data'])){
			return $result['data'];
		}
		return array();
	}

	public function getCouponInfo($openid, $inter_id, $member_card_id, $is_pms = 0){
		$token = $this->getApiToken();
		$params = array(
			'openid'         => $openid,
			'inter_id'       => $inter_id,
			'member_card_id' => $member_card_id,
			'is_pms'         => $is_pms,
			'token'          => $token,
		);
		$result = $this->curl_post('membercard/getinfo', $params);
		if(!empty($result['data'])){
			return $result['data'];
		}
		return array();
	}

	public function useCoupon($params){
		$token = $this->getApiToken();
		$params['token'] = $token;
		$res = $this->curl_post('membercard/useone', $params);

		if(isset($res['data']['code']) && $res['data']['code'] == 0){
			return true;
		}
		return false;
	}

	public function refundCoupon($params){
		$token = $this->getApiToken();
		$params['token'] = $token;
		$res = $this->curl_post('membercard/rollback', $params);
		if(isset($res['data']['code']) && $res['data']['code'] == 0){
			return true;
		}
		return false;
	}

	/**
	 * 获取请求TOKEN
	 * @return string
	 */
	private function getApiToken(){
		$token_res = $this->curl_post('accesstoken/get', array(
			'id'     => 'hotel',
			'secret' => 'iwide30hotel'
		));
		if(!empty($token_res['data'])){
			return $token_res['data'];
		}
		return '';
	}

	protected function curl_post($uri, $data){
		$url = INTER_PATH_URL . $uri;
		$this->load->helper('common');
		$data = http_build_query($data);
		$return = doCurlPostRequest($url, $data);
		$result = json_decode($return, true);
		pms_logger(func_get_args(), $result, __METHOD__, $this->session->userdata('inter_id'));
		return $result;
	}
}