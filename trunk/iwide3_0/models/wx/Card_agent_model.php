<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * @author John
 * @package models\wx
 */
class Card_agent_model extends MY_Model {
	/**
	 * @const 商户单个查询URI
	 */
	const CARD_MERCHANT_QUERY_URL       = 'http://api.weixin.qq.com/cgi-bin/component/get_card_merchant?access_token=';
	/**
	 * @const 商户批量查询URI
	 */
	const CARD_MERCHANT_BATCH_QUERY_URL = 'http://api.weixin.qq.com/cgi-bin/component/batchget_card_merchant?access_token=';
	const CARD_APPLY_PROTOCOL_URL       = 'https://api.weixin.qq.com/card/getapplyprotocol?access_token=';
	const CARD_CODE_QUERY_URL           = 'https://api.weixin.qq.com/card/code/get?access_token=';
	const CARD_USER_GET_CARD_LIST       = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=';
	const CARD_GET                      = 'https://api.weixin.qq.com/card/get?access_token=';
	const CARD_GET_BATCH                = 'https://api.weixin.qq.com/card/batchget?access_token=';
	public function get_resource_name() {
		return 'card_agent';
	}
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	
	/**
	 * 卡券开放类目查询接口
	 * 
	 * @param string $access_token 公众号ACCESS_TOKEN
	 * @return result json
	 *         <pre>{
	 *         "category": [
	 *         {"primary_category_id": 1,//一级类目id
	 *         "category_name": "美食",
	 *         "secondary_category": [
	 *         {"secondary_category_id": 101,//二级类目id
	 *         "category_name": "粤菜",
	 *         "need_qualification_stuffs": [
	 *         "food_service_license_id",
	 *         "food_service_license_bizmedia_id"], "can_choose_prepaid_card": 1, "can_choose_payment_card": 1 } ],
	 *         } ], "errcode": 0, "errmsg": "ok" }</pre>
	 */
	public function get_card_apply_protocol($access_token = NULL) {
		if (empty ( $access_token )) {
			$this->load->model ( 'wx/access_token_model' );
			$access_token = $this->access_token_model->get_component_access_token ();
		}
		$this->load->helper ( 'common' );
		return json_decode ( doCurlGetRequest ( self::CARD_APPLY_PROTOCOL_URL . $access_token ) );
	}
	
	/**
	 * 拉取单个子商户信息接口
	 * 
	 * @param string $appid 公众号APPID
	 * @return json <pre>{"appid":"wxd395ea50d8b6867a","name":"灰太狼烧烤店","primary_category_id":1,"secondary_category_id":101,"submit_time":1440580664,"result":"RESULT_PASS"}</pre>
	 */
	public function get_sub_merchant_info($appid) {
		$this->load->helper ( 'common' );
		$this->load->model ( 'wx/access_token_model' );
		$access_token = $this->access_token_model->get_component_access_token ();
		return json_decode ( doCurlPostRequest ( self::CARD_MERCHANT_QUERY_URL . $access_token, json_encode ( array ( 'appid' => $appid ) ) ) );
	}
	
	/**
	 * 拉取子商户列表接口
	 * 
	 * @param string $next_get        	
	 * @return json <pre>{"list":[
	 *         {"appid":"wxd395ea50d8b6867a",
	 *         "name":"灰太狼烧烤店",//子商户的商户名,显示在卡券券面的商户名称,支持12个汉字
	 *         "primary_category_id":1,//一级类目id
	 *         "secondary_category_id":101,//二级类目id
	 *         "submit_time":1440580664,//子商户资料提交时间
	 *         "result":"RESULT_PASS"
	 *         },{"appid":"wx6384a98262a87262",
	 *         "name":"灰太狼烧烤店",
	 *         "primary_category_id":1,
	 *         "secondary_category_id":101,
	 *         "submit_time":1440580515,
	 *         "result":"RESULT_PASS"
	 *         }],"next_get":"13"}</pre>
	 */
	public function batch_get_merchant_info($next_get = '') {
		$this->load->helper ( 'common' );
		$this->load->model ( 'wx/access_token_model' );
		$access_token = $this->access_token_model->get_component_access_token ();
		return json_decode ( doCurlPostRequest ( self::CARD_MERCHANT_BATCH_QUERY_URL . $access_token, json_encode ( array (	'next_get' => $next_get ) ) ) );
	}
	
	/**
	 * 查询Code接口
	 * 
	 * @todo 查询code接口可以查询当前code是否可以被核销并检查code状态。当前可以被定位的状态为正常、已核销、转赠中、已删除、已失效和无效code。
	 * @see http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272&token=&lang=zh_CN
	 * @param string $inter_id 公众号唯一标识
	 * @param string $card_id 卡券ID代表一类卡券。自定义code卡券必填
	 * @param string $code  单张卡券的唯一标识
	 * @param boolean $check_consume 是否校验code核销状态,填入true和false时的code异常状态返回数据不同。
	 * @return JSON
	 */
	public function query_code_by_inter_id($inter_id, $card_id = '', $code, $check_consume = TRUE) {
		$this->load->model ( 'wx/access_token_model' );
		return $this->query_code ( $this->access_token_model->get_access_token ( $inter_id ), $card_id, $code, $check_consume );
	}
	/**
	 * 查询Code接口
	 * 
	 * @todo 查询code接口可以查询当前code是否可以被核销并检查code状态。当前可以被定位的状态为正常、已核销、转赠中、已删除、已失效和无效code。
	 * @see http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272&token=&lang=zh_CN
	 * @param string $access_token 调用接口凭证
	 * @param string $card_id 卡券ID代表一类卡券。自定义code卡券必填
	 * @param string $code  单张卡券的唯一标识
	 * @param boolean $check_consume 是否校验code核销状态,填入true和false时的code异常状态返回数据不同。
	 * @return JSON
	 */
	public function query_code_by_access_token($access_token, $card_id = '', $code, $check_consume = TRUE) {
		$this->load->helper ( 'common' );
		return json_decode ( doCurlPostRequest ( self::CARD_CODE_QUERY_URL.$this->access_token_model->get_access_token($inter_id), json_encode ( array (
				'card_id' => $card_id,
				'code' => $code,
				'check_consume' => $check_consume 
		) ) ) );
	}
	
	/**
	 * 获取用户已领取卡券接口
	 * @todo 用于获取用户卡包里的,属于该appid下所有可用卡券,包括正常状态和未生效状态。
	 * @see http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272&token=&lang=zh_CN
	 * @param string $openid 用户身份唯一标识
	 * @param string $inter_id 公众号身份唯一标识
	 * @param string $card_id 卡券ID代表一类卡券
	 * @return JSON
	 */
	public function user_get_cards($openid,$inter_id,$card_id = ''){
		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
		return json_decode(doCurlPostRequest(self::CARD_USER_GET_CARD_LIST.$this->access_token_model->get_access_token($inter_id), json_encode(array('openid'=>$openid,'card_id'=>$card_id))));
	}
	
	/**
	 * 查看卡券详情
	 * @todo 开发者可以调用该接口查询某个card_id的创建信息、审核状态以及库存数量。
	 * @see http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272&token=&lang=zh_CN
	 * @param string $inter_id 公众号唯一身份标识
	 * @param string $card_id 卡券ID代表一类卡券
	 * @return JSON
	 */
	public function get_card($inter_id,$card_id){
		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
		return json_decode(doCurlPostRequest(self::CARD_GET.$this->access_token_model->get_access_token($inter_id), json_encode(array('card_id'=>$card_id))));
	}
	
	/**
	 * 批量查询卡券列表
	 * @see http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025272&token=&lang=zh_CN
	 * @param string $inter_id
	 * @param number $offset 查询卡列表的起始偏移量，从0开始，即offset: 5是指从从列表里的第六个开始读取。
	 * @param number $count 需要查询的卡片的数量（数量最大50）。
	 * @param array $status_list CARD_STATUS_NOT_VERIFY|待审核<br/>CARD_STATUS_VERIFY_FAIL|审核失败<br/>CARD_STATUS_VERIFY_OK|通过审核<br/>CARD_STATUS_DELETE|卡券被商户删除<br/>CARD_STATUS_DISPATCH|在公众平台投放过的卡券
	 * @return JSON
	 */
	public function get_card_batch($inter_id,$offset = 0,$count = 10,$status_list = array()){
		if (empty ( $status_list )) {
			$status_list = array (
					'CARD_STATUS_NOT_VERIFY',
					'CARD_STATUS_VERIFY_FAIL',
					'CARD_STATUS_VERIFY_OK',
					'CARD_STATUS_DELETE',
					'CARD_STATUS_DISPATCH' 
			);
		}
		$this->load->helper ( 'common' );
		$this->load->model ( 'wx/access_token_model' );
		return json_decode ( doCurlPostRequest ( self::CARD_GET . $this->access_token_model->get_access_token ( $inter_id ), json_encode ( array ('card_id' => $card_id ) ) ) );
	}
	
}
