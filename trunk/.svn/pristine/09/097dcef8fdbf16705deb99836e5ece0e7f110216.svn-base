<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 查询卡券Code
 * 查询code接口可以查询当前code是否可以被核销并检查code状态。当前可以被定位的状态为正常、已核销、转赠中、已删除、已失效和无效code。
 * 
 * @param string 调用接口凭证        	
 * @param string 单张卡券的唯一标准。        	
 * @param string 卡券ID代表一类卡券。        	
 * @param boolean 是否校验code核销状态，填入true和false时的code异常状态返回数据不同。        	
 * @see http://mp.weixin.qq.com/wiki/11/86a64e386347a760fa30618bb6f11ea7.html#.E6.9F.A5.E8.AF.A2Code.E6.8E.A5.E5.8F.A3
 * @return JSON卡券状态
 */
function check_card_code($access_token, $code, $card_id = NULL, $check_consume = FALSE) {
	$url = 'https://api.weixin.qq.com/card/code/get?access_token=' . $access_token;
	$datas ['code'] = $code;
	if (! empty ( $card_id ))
		$data ['card_id'] = $card_id;
	if ($check_consume)
		$data ['check_consume'] = $check_consume;
	return do_post_action ( $url, json_encode ( $datas ) );
}

/**
 * 获取用户已领取卡券接口
 * 用于获取用户卡包里的，属于该appid下的卡券。
 * 
 * @param string 调用接口凭证        	
 * @param string 需要查询的用户openid        	
 * @see http://mp.weixin.qq.com/wiki/11/86a64e386347a760fa30618bb6f11ea7.html#.E8.8E.B7.E5.8F.96.E7.94.A8.E6.88.B7.E5.B7.B2.E9.A2.86.E5.8F.96.E5.8D.A1.E5.88.B8.E6.8E.A5.E5.8F.A3
 * @param
 *        	$card_id
 */
function get_card_list($access_token, $openid, $card_id = NULL) {
	$url = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=' . $access_token;
	$datas ['openid'] = $openid;
	if (! empty ( $card_id ))
		$data ['card_id'] = $card_id;
	return do_post_action ( $url, json_encode ( $datas ) );
}
/**
 * 批量查询卡列表
 * 
 * @param string 调用接口凭证        	
 * @param int 查询卡列表的起始偏移量，从0开始，即offset:
 *        	5是指从从列表里的第六个开始读取。
 * @param int 需要查询的卡片的数量（数量最大50）。        	
 * @param string 支持开发者拉出指定状态的卡券列表，例：仅拉出通过审核的卡券。<br/>eg.“CARD_STATUS_NOT_VERIFY”,<br/>待审核；“CARD_STATUS_VERIFY_FAIL”,<br/>审核失败；“CARD_STATUS_VERIFY_OK”，<br/>通过审核；“CARD_STATUS_USER_DELETE”，卡券被商户删除；<br/>“CARD_STATUS_DISPATCH”，在公众平台投放过的卡券        	
 * @see http://mp.weixin.qq.com/wiki/11/86a64e386347a760fa30618bb6f11ea7.html#.E6.89.B9.E9.87.8F.E6.9F.A5.E8.AF.A2.E5.8D.A1.E5.88.97.E8.A1.A8
 * @return eg.{"errcode":0,"errmsg":"ok","card_id_list":["ph_gmt7cUVrlRk8swPwx7aDyF-pg"],"total_num":1 }
 */
function batch_get($access_token, $offset, $count, $status_list = array()) {
	$url = 'https://api.weixin.qq.com/card/batchget?access_token=' . $access_token;
	$datas ['offset'] = $offset;
	$datas ['count'] = $count;
	if (! empty ( $status_list )) {
		$temp_status = '[';
		foreach ( $status_list as $status ) {
			$temp_status .= '"' . $status . '",';
		}
		$temp_status = substr ( $temp_status, 0, - 1 );
		$datas ['status_list'] = $temp_status . ']';
	}
	return do_post_action ( $url, json_encode ( $datas ) );
}

/**
 * 查询可用卡券列表
 * 
 * @param string 调用接口凭证        	
 * @param array 卡券数组array('card_id'=>array(card_code,card_code,...))        	
 * @param array 忽略数组array(card_code,card_code,...)        	
 * @return 可用卡券数组
 */
function get_normal_cards($access_token, $card_codes, $ignore_codes) {
	$res_arr = array ();
	foreach ( $card_codes as $key => $codes ) {
		foreach ( $codes as $code ) {
			if (! in_array ( $code, $ignore_codes ))
				$res = check_card_code ( $access_token, $key, $code );
		}
	}
	return $res_arr;
}
function consume_card($access_token,$code,$card_id = NULL){
	$url = 'https://api.weixin.qq.com/card/code/consume?access_token='.$access_token;
	$datas['code'] = $code;
	if(!empty($card_id)){
		$datas['card_id'] = $card_id;
	}
}
function do_post_action($url, $datas) {
	$CI = & get_instance ();
	$CI->load->helper ( 'common' );
	return doCurlPostRequest ( $url, $datas );
}