<?php

class Wx_card_model extends MY_Model{
	const CARD_CREATE_URL = 'https://api.weixin.qq.com/card/create?access_token=';
	
	/**
	 * @var 卡券类型，现仅支持代金券类型和兑换券类型，填写CASH或者GIFT
	 * required
	 */
	public $card_type = '';
	/**
	 * @var 代金券类型json结构函数名
	 */
	public $cash = '';
	/**
	 * @var 代金券专用，表示减免金额（单位为分），不可填0。
	 */
	public $reduce_cost = 10;
	/**
	 * @var 兑换券兑换商品名字，限6个汉字
	 */
	public $gift_name = '';
	/**
	 * @var 兑换券兑换商品数目，限三位数字
	 */
	public $gift_num = 0;
	/**
	 * @var 兑换券兑换商品的数量单位，限两个汉字
	 */
	public $gift_unit = '';
	/**
	 * @var 兑换券类型时显示的礼品详情
	 */
	public $gift = '';
	
	
	//Base Info
	/**
	 * @var 卡券商家LOGO，请使用调用上传图片接口获得的url
	 */
	public $logo_url = '';
	/**
	 * @var 商家名字,上限为12个汉字
	 */
	public $brand_name = '';
	/**
	 * @var 卡券的code类型,枚举值
	 * CODE_TYPE_TEXT|文本
	 * CODE_TYPE_BARCODE|一维码
	 * CODE_TYPE_QRCODE|二维码
	 * CODE_TYPE_ONLY_QRCODE|二维码无code显示
	 * CODE_TYPE_ONLY_BARCODE|一维码无code显示
	 * CODE_TYPE_NONE|无code类型
	 */
	public $code_type = '';
	/**
	 * @var 券颜色,枚举值
	 * Color010	#63b359
	 * Color020	#2c9f67
	 * Color030	#509fc9
	 * Color040	#5885cf
	 * Color050	#9062c0
	 * Color060	#d09a45
	 * Color070	#e4b138
	 * Color080	#ee903c
	 * Color081	#f08500
	 * Color082	#a9d92d
	 * Color090	#dd6549
	 * Color100	#cc463d
	 * Color101	#cf3e36
	 * Color102	#5E6671
	 */
	public $color = '';
	/**
	 * @var 使用提醒，上限为12个汉字（一句话描述，展示在首页，示例：请出示二维码核销卡券）
	 */
	public $notice = '';
	/**
	 * @var 使用说明。长文本描述，可以分行，上限为1000个汉字
	 */
	public $description = '';
	/**
	 * @var 使用日期，有效期的信息，仅支持DATE_TYPE_FIX_TIME_RANGE
	 * array('type'=>DATE_TYPE_FIX_TIME_RANGE,'begin_timestamp'=>'','end_timestamp'=>'')
	 * Array[begin_timestamp]DATE_TYPE_FIX_TIME_RANGE时专用，表示起用时间。从1970年1月1日00:00:00至起用时间的秒数，最终需转换为字符串形态传入，下同。（单位为秒）
	 * Array[end_timestamp]DATE_TYPE_FIX_TIME_RANGE表示结束时间。从1970年1月1日00:00:00至起用时间的秒数，最终需转换为字符串形态传入。（单位为秒）
	 */
	public $date_info = array();
	/**
	 * @var 门店位置ID，请参考微信门店接口文档，朋友的券须至少传入一个可用poi_id,否则报错
	 * @link http://mp.weixin.qq.com/wiki/11/081986f089826bf94393bef9bf287b8b.html#.E5.BE.AE.E4.BF.A1.E9.97.A8.E5.BA.97.E6.8E.A5.E5.8F.A3.E6.96.87.E6.A1.A3
	 */
	public $location_id_list = '';
	/**
	 * @var 是否支持分享到对话、朋友圈，与share_friends字段互斥，若创建朋友共享券此处应填入false,不可为空
	 */
	public $can_share = '';
	/**
	 * @var 是否支持赠送，与share_friends字段互斥，若创建朋友共享券此处应填入false,不可为空
	 */
	public $can_give_friend = '';
	/**
	 * @var 客服电话
	 */
	public $service_phone = '';
	/**
	 * @var 领取限制，限制用户扫码或点击H5领取的次数
	 */
	public $get_limit = 38;
	/**
	 * @var 居中置顶的url标题，一般为快速核销或者快速买单，用于跳转商户自己开发的核销或者买单页面，9个中文字符以内。该cell仅限卡券状态正常，且处于有效期内的时候显示。
	 */
	public $center_title = '';
	/**
	 * @var 居中置顶的url副标题，显示在标题下方，12个中文字符以内。该标题仅限卡券状态正常，且处于有效期内的时候显示。
	 */
	public $center_sub_title = '';
	/**
	 * @var 居中置顶的url，该url仅限卡券状态正常，且处于有效期内的时候显示。
	 */
	public $center_url = '';
	/**
	 * @var 商家自定义入口名称，与custom_url字段共同使用，长度限制在5个汉字内
	 */
	public $custom_url_name = '';
	/**
	 * @var 商家自定义入口跳转外链的地址链接,跳转页面内容需与自定义cell名称保持匹配
	 */
	public $custom_url = '';
	/**
	 * @var 显示在入口右侧的tips，长度限制在6个汉字内
	 */
	public $custom_url_sub_title = '';
	/**
	 * @var 营销场景的自定义入口
	 */
	public $promotion_url_name = '';
	/**
	 * @var 入口跳转外链的地址链接。
	 */
	public $promotion_url = '';
	/**
	 * @var 显示在入口右侧的tips，长度限制在6个汉字内
	 */
	public $promotion_url_sub_title = '';
	
	//Advanced Info
	/**
	 * @var 使用门槛（条件）字段
	 * Array['accept_category']=>'指定可用的商品类目，仅用于代金券类型，填入后将在券面拼写适用于xxx，标题自动拼为xxx减50元（若仅填入5个字），50元代金券（填入5个字以上）。',
	 * Array['reject_category']=>'指定不可用的商品类目，仅用于代金券类型，填入后将在券面拼写不适用于xxx',
	 * Array['least_cost']=>'满减门槛字段，可用于兑换券和代金券，填入后将在全面拼写消费满xx元可用，标题自动拼为满xx减xx/满xx送xx(gift_name)',
	 * Array['object_use_for']=>'购买xx可用类型门槛，仅用于兑换，填入后自动拼写购买xxx可用，标题自动拼为买xx送xx(gift_name)',
	 * Array['can_use_with_other_discount']=>'不可以与其他类型共享门槛，填写false时系统将在使用须知里拼写不可与其他优惠共享，默认为true')
	 */
	public $use_condition =array('accept_category'=>'','reject_category'=>'','can_use_with_other_discount'=>TRUE,'least_cost'=>'','object_use_for'=>'');
	/**
	 * @var 封面摘要结构体名称
	 * Array[abstract]=>封面摘要简介。
	 * Array[icon_url_list]=>封面图片列表，仅支持填入一个封面图片链接，上传图片接口上传获取图片获得链接，填写非CDN链接会报错，并在此填入。建议图片尺寸像素850*350
	 */
	public $abstract = array('abstract'=>'','icon_url_list' => array());
	/**
	 * @var 图文列表，显示在详情内页，优惠券券开发者须至少传入一组图文列表
	 * array(array('image_url'=>'','text'=>''),array('image_url'=>'','text'=>'')...)
	 */
	public $text_image_list = array();
	/**
	 * @var 商家服务类型
	 * BIZ_SERVICE_DELIVER|外卖服务；BIZ_SERVICE_FREE_PARK|停车位；BIZ_SERVICE_WITH_PET|可带宠物；BIZ_SERVICE_FREE_WIFI|免费wifi，可多选
	 */
	public $business_service = array();
	/**
	 * @var 使用时段限制
	 * array(array('type'=>'','begin_hour'=>'','end_hour'=>'','begin_minute'=>'','end_minute'=>''),array()...)
	 * Array[Array['type']]限制类型枚举值：支持填入MONDAY|周一,TUESDAY|周二,WEDNESDAY|周三,THURSDAY|周四,FRIDAY|周五,SATURDAY|周六,SUNDAY|周日,HOLIDAY|假期通用,此处只控制显示，不控制实际使用逻辑，不填默认不显示
	 * Array[Array['begin_hour']]当前type类型下的起始时间（小时），如当前结构体内填写了MONDAY，此处填写了10，则此处表示周一 10:00可用
	 * Array[Array['begin_minute']]当前type类型下的起始时间（分钟），如当前结构体内填写了MONDAY，begin_hour填写10，此处填写了59，则此处表示周一 10:59可用
	 * Array[Array['end_hour']]当前type类型下的结束时间（小时），如当前结构体内填写了MONDAY，此处填写了20，则此处表示周一 10:00-20:00可用
	 * Array[Array['end_minute']]当前type类型下的结束时间（分钟），如当前结构体内填写了MONDAY，begin_hour填写10，此处填写了59，则此处表示周一 10:59-00:59可用
	 */
	public $time_limit = array();
	/**
	 * @var 核销后送券的数量，可设置核销后送本卡券的数量，限制传入1张，与consume_share_card_list字段互斥
	 */
	public $consume_share_self_num = '';
	/**
	 * @var 核销后赠送其他卡券的列表，与consume_share_self_num字段互斥
	 */
	public $consume_share_card_list = '';
	/**
	 * @var card_id
	 */
	public $card_id = '';
	/**
	 * @var 核销后赠送的该card_id数目，目前仅支持填1
	 */
	public $num = '';
	/**
	 * @var 是否支持分享给朋友使用，填写true优惠券才可被共享
	 */
	public $share_friends = '';
	
	/**
	 * 创建新代金券
	 * 
	 * @param unknown $inter_id
	 * @return {  "errcode": 0,  "errmsg": "ok",  "card_id": "pbLatjtQrAGz1Iaz08qB_H3NSBrc" }
	 */
	public function new_cash($inter_id) {
		$this->load->helper ( 'common' );
		$this->load->model('wx/access_token_model');
		$card_info = array (
				'card' => array (
						'card_type' => 'CASH',
						'cash'      => array (
								'base_info'     => $this->_get_base_info (),
								'advanced_info' => $this->_get_advanced_info (),
								'reduce_cost'   => $this->reduce_cost 
						) 
				) 
		);
		$card_json = json_encode ( $card_info );
		return json_decode ( doCurlPostRequest ( SELF::CARD_CREATE_URL.$this->access_token_model->get_access_token($inter_id), $card_json ) );
	}
	/**
	 * @param unknown $inter_id
	 * @return {  "errcode": 0,  "errmsg": "ok",  "card_id": "pbLatjtQrAGz1Iaz08qB_H3NSBrc" }
	 * @see http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025278&token=&lang=zh_CN
	 */
	public function new_gift($inter_id){
		$this->load->helper ( 'common' );
		$this->load->model('wx/access_token_model');
		$card_info = array (
				'card' => array (
						'card_type' => 'GIFT',
						'gift'      => array (
								'base_info'     => $this->_get_base_info (),
								'advanced_info' => $this->_get_advanced_info (),
								'gift_name'     => $this->gift_name,
								'gift'          => $this->gift 
						) 
				) 
		);
		if(!empty($this->gift_num))
			$card_info['card']['gift']['gift_num']  = $this->gift_num;
		if(!empty($this->gift_unit))
			$card_info['card']['gift']['gift_unit'] = $this->gift_unit;
		$card_json = json_encode ( $card_info );
		return json_decode ( doCurlPostRequest ( SELF::CARD_CREATE_URL.$this->access_token_model->get_access_token($inter_id), $card_json ) );
	}
	
	private function _get_base_info(){
		$base_info['logo_url']         = $this->logo_url;
		$base_info['brand_name']       = $this->brand_name;
		$base_info['code_type']        = $this->code_type;
		$base_info['color']            = $this->color;
		$base_info['notice']           = $this->notice;
		$base_info['description']      = $this->description;
		$base_info['date_info']        = $this->date_info;
		$base_info['location_id_list'] = $this->location_id_list;
		$base_info['can_share']        = $this->can_share;
		$base_info['can_give_friend']  = $this->can_give_friend;
		if(empty($this->service_phone))
			$base_info['service_phone'] = $this->service_phone;
		if(empty($this->get_limit))
			$base_info['get_limit'] = $this->service_phone;
		if(empty($this->center_title))
			$base_info['center_title'] = $this->center_title;
		if(empty($this->center_sub_title))
			$base_info['center_sub_title'] = $this->center_sub_title;
		if(empty($this->center_url))
			$base_info['center_url'] = $this->center_url;
		if(empty($this->custom_url_name))
			$base_info['custom_url_name'] = $this->custom_url_name;
		if(empty($this->custom_url))
			$base_info['custom_url'] = $this->custom_url;
		if(empty($this->custom_url_sub_title))
			$base_info['custom_url_sub_title'] = $this->custom_url_sub_title;
		if(empty($this->promotion_url_name))
			$base_info['promotion_url_name'] = $this->promotion_url_name;
		if(empty($this->promotion_url))
			$base_info['promotion_url'] = $this->promotion_url;
		if(empty($this->promotion_url_sub_title))
			$base_info['promotion_url_sub_title'] = $this->promotion_url_sub_title;
		return $base_info;
	}
	private function _get_advanced_info($is_cash = TRUE){
		if(!empty($this->use_condition)){
			if($is_cash){
				unset($this->use_condition['object_use_for']);
			}else{
				unset($this->use_condition['accept_category']);
				unset($this->use_condition['reject_category']);
			}
			$adv_info['use_condition'] = $this->use_condition;
		}
		$adv_info['abstract'] = $this->abstract;
		if(!empty($this->text_image_list))
			$adv_info['text_image_list'] = $this->text_image_list;
		if(!empty($this->time_limit))
			$adv_info['time_limit'] = $this->time_limit;
		if(!empty($this->business_service))
			$adv_info['business_service'] = $this->business_service;
		if(!empty($this->consume_share_self_num))
			$adv_info['consume_share_self_num'] = $this->consume_share_self_num;
		if(!empty($this->consume_share_card_list))
			$adv_info['consume_share_card_list'] = $this->consume_share_card_list;
		$adv_info['share_friends'] = $this->share_friends;
	}
}
