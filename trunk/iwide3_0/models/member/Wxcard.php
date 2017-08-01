<?php
/**
 * 微信卡券相关类
 * @author Administrator
 *
 */
class Wxcard extends CI_Model
{
	//创建微信卡券必填字段
	protected $necessary_fields = array('logo_url','code_type','brand_name','title','color','notice','description','sku_total_quantity','date_info_type');
	
	/**
	 * 创建微信卡券
	 * @param unknown $ci_id
	 * @param unknown $inter_id
	 * @return multitype:boolean string |Ambigous <boolean, multitype:boolean string >|Ambigous <multitype:boolean , multitype:boolean string >|multitype:boolean
	 */
    public function createCard($ci_id,$inter_id)
    {
    	$this->load->model('member/icard');
    	$card = $this->icard->getCardById($ci_id);
    	
    	if(!isset($card->ci_id)) return array('error'=>true,'errmsg'=>'卡券不存在!');
    	if(!empty($card->card_id)) return array('error'=>true,'errmsg'=>'微信卡券已经存在!');
    	
    	$cardtype = $this->icard->getCardTypeById($card->ct_id,$inter_id);
    	if(!isset($cardtype->is_package) || !$cardtype->is_package) return array('error'=>true,'errmsg'=>'此卡券不能建立微信卡券!');
    	
    	$wxcardtype = strtoupper($cardtype->card_type);
    	
    	//检查必填字段
    	$result = $this->checkFields($card,$wxcardtype); 	
    	if(is_array($result) && $result['error']==true) {
    		return $result;
    	}
    	
    	//上传卡券logo
    	$result = $this->uploadLogo($inter_id,$card);
    	if($result['error']) return $result;
    	
    	//填充卡券数据
    	$cardinfo = $this->fillField($card,$wxcardtype);

    	//向微信请求创建卡券
    	$result   = $this->createWxCard($inter_id,$cardinfo);

    	if($result->errcode==0 && $result->errmsg=='ok') {
    		$this->icard->updateCard($ci_id,array('card_id'=>$result->card_id,'status'=>'CARD_STATUS_NOT_VERIFY'));
    		return array('error'=>false);
    	} else {
    		return array('error'=>true,'errmsg'=>$result->errcode.":".$result->errmsg);
    	}
    }
    
    /**
     * 获取卡券状态
     * @param unknown $ci_id
     * @param unknown $inter_id
     * @return multitype:boolean string |multitype:boolean NULL
     */
    public function getCardDetail($ci_id,$inter_id)
    {
    	$this->load->model('wx/Access_token_model');
    	$access_token = $this->Access_token_model->get_access_token($inter_id);
    	$url ="https://api.weixin.qq.com/card/get?access_token=".$access_token;
    	
    	$this->load->model('member/icard');
    	$card = $this->icard->getCardById($ci_id);
    	
    	if(!isset($card->card_id) || empty($card->card_id)) return array('error'=>true,'errmsg'=>'此卡券还没创建微信卡券!');
    
    	$data['card_id']=$card->card_id;
    	 
    	$result = $this->http_post($url, json_encode($data));
    	$result = json_decode($result);
    	
    	if($result->errcode==0 && $result->errmsg=='ok') {
    		$cardtype=strtolower($result->card->card_type);
  		
    		if($card->status != $result->card->$cardtype->base_info->status) {
    			$this->icard->updateCard($ci_id,array('status'=>$result->card->$cardtype->base_info->status));
    		}
    		
    		return array('error'=>false,'errmsg'=>$result->card->$cardtype->base_info->status);
    	} else {
    		return array('error'=>true,'errmsg'=>$result->errmsg);
    	}
    }
    
    /**
     * 检查微信卡券必填字段
     * @param unknown $card
     * @param unknown $cardtype
     * @return multitype:boolean string |boolean
     */
    protected function checkFields($card,$cardtype)
    {
    	$cardtype = strtoupper($cardtype);
    	
    	foreach($this->necessary_fields as $field)
    	{
    		if(!isset($card->$field) || empty($card->$field)) {
    			return array('error'=>true,'errmsg'=>$field." can not be empty!");
    		}
    	}
    	
    	if($cardtype=='GENERAL_COUPON') {
    		if(empty($card->default_detail)) return array('error'=>true,'errmsg'=>"default_detail can not be empty!");
    	} elseif($cardtype=='GROUPON') {
    		if(empty($card->deal_detail)) return array('error'=>true,'errmsg'=>"deal_detail can not be empty!");
    	} elseif($cardtype=='CASH') {
    		if(empty($card->least_cost)) return array('error'=>true,'errmsg'=>"least_cost can not be empty!");
    		if(empty($card->reduce_cost)) return array('error'=>true,'errmsg'=>"reduce_cost can not be empty!");
    	} elseif($cardtype=='DISCOUNT') {
    		if(empty($card->discount)) return array('error'=>true,'errmsg'=>"discount can not be empty!");
    	} elseif($cardtype=='GIFT') {
    		if(empty($card->gift)) return array('error'=>true,'errmsg'=>"gift can not be empty!");
    	}
    	
    	if($card->date_info_type == "DATE_TYPE_FIX_TIME_RANGE") {
    		if(empty($card->date_info_begin_timestamp) || empty($card->date_info_end_timestamp)) return array('error'=>true,'errmsg'=>"date_info_begin_timestamp or date_info_end_timestamp can not be empty!");
    	}
    	if($card->date_info_type == "DATE_TYPE_FIX_TERM") {
    		if(empty($card->date_info_fixed_term) && empty($card->date_info_fixed_begin_term)) return array('error'=>true,'errmsg'=>"date_info_fixed_term or date_info_fixed_begin_term can not be empty!");
    	}
    	
    	if(empty($card->sku_total_quantity)) return array('error'=>true,'errmsg'=>"sku quantity can not be empty!");
    	
    	return true;
    }
    
    /**
     * 上传卡券LOGO
     * @param unknown $inter_id
     * @param unknown $card
     * @return multitype:boolean |multitype:boolean string
     */
    public function uploadLogo($inter_id,$card)
    {
    	$this->load->model('member/iconfig');
    	$value = $this->iconfig->getConfig('wx_logo_'.$card->ci_id,null,$inter_id);
    	if($value) {
    		$card->logo_url = $value->value;
    		return array('error'=>false);
    	}

    	$this->load->model('wx/Access_token_model');
    	$access_token = $this->Access_token_model->get_access_token($inter_id);
    	
    	$file = dirname(dirname(dirname(__FILE__))).DS.'cache'.DS.substr($card->logo_url, strrpos($card->logo_url,'/')+1);
    	file_put_contents($file, file_get_contents($card->logo_url));

    	if(realpath($file)) {
	    	$data['buffer']   = new CURLFile(realpath($file));
	    	//$data['buffer']   = '@'.$a;
	    	$url      = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$access_token;
	    	$result     = $this->http_post($url,$data);
	    	
	    	$result = json_decode($result);
	    	
	    	if(isset($result->url)) {
	    		$this->iconfig->addConfig('wx_logo_'.$card->ci_id,$result->url,null,$inter_id);
	    		$card->logo_url = $result->url;
	    		@unlink($file);
	    		return array('error'=>false);
	    	} else {
	    		return array('error'=>true,'errmsg'=>$result->errcode.":".$result->errmsg);
	    	}
    	}
    	
    	return array('error'=>true,'errmsg'=>"上传失败!");
    }
    
    /**
     * 填充创建卡券数组
     * @param unknown $card
     * @param unknown $cardtype
     * @return boolean
     */
    protected function fillField($card,$cardtype)
    {
    	$wxcardtype_field = strtolower($cardtype);
    	
    	$cardinfo = array();
    	$cardinfo['card']['card_type']=$cardtype;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["logo_url"] = $card->logo_url;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["brand_name"] = $card->brand_name;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["code_type"] = $card->code_type;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["title"] = $card->title;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["color"] = $card->color;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["notice"] =  $card->notice;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["description"] = $card->description;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["sku"]["quantity"] = $card->sku_total_quantity;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["get_limit"] = $card->get_limit;
    	$cardinfo['card'][$wxcardtype_field]["base_info"]["use_custom_code"] = true;
    	
    	
    	if($cardtype=='GENERAL_COUPON') {
    	    $cardinfo['card'][$wxcardtype_field]["default_detail"] = $card->default_detail;
    	} elseif($cardtype=='GROUPON') {
    		$cardinfo['card'][$wxcardtype_field]["deal_detail"] = $card->deal_detail;
    	} elseif($cardtype=='CASH') {
    		$cardinfo['card'][$wxcardtype_field]["least_cost"] = $card->least_cost;
    		$cardinfo['card'][$wxcardtype_field]["reduce_cost"] = $card->reduce_cost;
    	} elseif($cardtype=='DISCOUNT') {
    		$cardinfo['card'][$wxcardtype_field]["discount"] = $card->discount;
    	} elseif($cardtype=='GIFT') {
    		$cardinfo['card'][$wxcardtype_field]["gift"] = $card->gift;
    	}
    	
    	if($card->date_info_type=='DATE_TYPE_FIX_TIME_RANGE') {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["date_info"]["type"] = "DATE_TYPE_FIX_TIME_RANGE";
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["date_info"]["begin_timestamp"] = $card->date_info_begin_timestamp;
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["date_info"]["end_timestamp"] = $card->date_info_end_timestamp;
    	}
    	
    	if($card->date_info_type=='DATE_TYPE_FIX_TERM') {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["date_info"]["type"] = "DATE_TYPE_FIX_TERM";
    		if(!empty($card->date_info_fixed_term)) {
    			$cardinfo['card'][$wxcardtype_field]["base_info"]["date_info"]["fixed_term"] = $card->date_info_fixed_term;
    		}
    		if(!empty($card->date_info_fixed_begin_term)) {
    			$cardinfo['card'][$wxcardtype_field]["base_info"]["date_info"]["fixed_begin_term"] = $card->date_info_fixed_begin_term;
    		}
    	}
    	
    	if(empty($card->bind_openid)) {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["bind_openid"] = false;
    	} else {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["bind_openid"] = true;           
    	}
    	
    	if(empty($card->can_give_friend)) {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["can_give_friend"]  = false;
    	} else {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["can_give_friend"]  = true;
    	}
    	if(empty($card->can_share)) {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["can_share"]        = false;
    	} else {
    		$cardinfo['card'][$wxcardtype_field]["base_info"]["can_share"]        = true;
    	}
    	
    	if(!empty($card->location_id_list))        $cardinfo['card'][$wxcardtype_field]["base_info"]["location_id_list"]        = $card->location_id_list;
    	if(!empty($card->sub_title))               $cardinfo['card'][$wxcardtype_field]["base_info"]["sub_title"]               = $card->sub_title;
    	if(!empty($card->service_phone))           $cardinfo['card'][$wxcardtype_field]["base_info"]["service_phone"]           = $card->service_phone;
    	if(!empty($card->custom_url_name))         $cardinfo['card'][$wxcardtype_field]["base_info"]["custom_url_name"]         = $card->custom_url_name;
    	if(!empty($card->custom_url))              $cardinfo['card'][$wxcardtype_field]["base_info"]["custom_url"]              = $card->custom_url;
    	if(!empty($card->custom_url_sub_title))    $cardinfo['card'][$wxcardtype_field]["base_info"]["custom_url_sub_title"]    = $card->custom_url_sub_title;
    	if(!empty($card->promotion_url_name))      $cardinfo['card'][$wxcardtype_field]["base_info"]["promotion_url_name"]      = $card->promotion_url_name;
    	if(!empty($card->promotion_url))           $cardinfo['card'][$wxcardtype_field]["base_info"]["promotion_url"]           = $card->promotion_url;
    	if(!empty($card->promotion_url_sub_title)) $cardinfo['card'][$wxcardtype_field]["base_info"]["promotion_url_sub_title"] = $card->promotion_url_sub_title;
    	if(!empty($card->center_title))            $cardinfo['card'][$wxcardtype_field]["base_info"]["center_title"]            = $card->center_title;
    	if(!empty($card->center_sub_title))        $cardinfo['card'][$wxcardtype_field]["base_info"]["center_sub_title"]        = $card->center_sub_title;
    	if(!empty($card->center_url))              $cardinfo['card'][$wxcardtype_field]["base_info"]["center_url"]              = $card->center_url;
    	
    	return $cardinfo;
    }
    
    /**
     * 发送请求创建卡券
     * @param unknown $inter_id
     * @param unknown $cardinfo
     * @return mixed
     */
    public function createWxCard($inter_id,$cardinfo)
    {
    	$this->load->model('wx/Access_token_model');
    	$access_token = $this->Access_token_model->get_access_token($inter_id);
    	 
    	$url = "https://api.weixin.qq.com/card/create?access_token=".$access_token;
    	
    	$result = $this->http_post($url, json_encode($cardinfo,JSON_UNESCAPED_UNICODE));
    	 
    	return json_decode($result);
    }
    
//     public function wxDepositCode($inter_id,$card_id,$codes)
//     {
//     	$error_codes = $not_exist_codes = array();
    	
//     	$this->load->model('wx/Access_token_model');
//     	$access_token = $this->Access_token_model->get_access_token($inter_id);
//     	$url = "http://api.weixin.qq.com/card/code/deposit?access_token=".$access_token;

//     	$time = ceil($codes/100);
    	
//     	for($i=0;$i<$time;$i++) {
//     		$tmp_codes = array_slice($codes,$i*100,100);
//     		$data = array(
//     			'card_id'=>$card_id,
//     			'code'=>$tmp_codes
//     		);
//     		$result = $this->http_post($url, json_encode($data));
    		
//     		if($result->errcode==0 && $result->errmsg='ok') {
//     			$checkresult = $this->wxCheckCode($inter_id,$card_id,$tmp_codes);
//     			if($checkresult) {
//     				$not_exist_codes = array_merge($not_exist_codes,$checkresult['not_exist_code']);
//     			}
//     		} else {
//     			$error_codes = array($error_codes,$tmp_codes);
//     		}
//     	}
    	
//     	if(count($not_exist_codes)) {
//     		$tmp_error_codes = $this->wxDepositCode($inter_id, $card_id, $not_exist_codes);
//     	}
    	
//     	$error_codes = array_merge($error_codes,$tmp_error_codes);
    	
//     	return $error_codes;
//     }
    
//     public function wxCheckCode($inter_id,$card_id,$codes)
//     {
//     	$this->load->model('wx/Access_token_model');
//     	$access_token = $this->Access_token_model->get_access_token($inter_id);
//     	$url = "http://api.weixin.qq.com/card/code/checkcode?access_token=".$access_token;
    	
//     	$data = array(
//     		'card_id'=>$card_id,
//     		'code'=>$codes
//     	);
//     	$result = $this->http_post($url, json_encode($data));
    	
//     	if($result->errcode==0 && $result->errmsg='ok') {
//     		 return array('not_exist_code'=>$result->not_exist_code);
//     	}
    	
//     	return false;
//     }
    
    /**
     * 获取特定openid下的微信卡券
     * @param unknown $inter_id
     * @param unknown $openid
     * @param string $card_id
     * @return multitype:NULL
     */
    public function getWxCardlist($inter_id,$openid,$card_id='')
    {
    	$this->load->model('wx/Access_token_model');
    	$access_token = $this->Access_token_model->get_access_token($inter_id);
    	$url='https://api.weixin.qq.com/card/user/getcardlist?access_token='.$access_token;
    	
    	if($card_id) {
    		$params = array('openid'=>$openid,'card_id'=>$card_id);
    	} else {
    		$params = array('openid'=>$openid);
    	}
    	 
    	$result = $this->http_post($url, json_encode($params));
        $result = json_decode($result);
        $ret=array();
    	if($result->errcode==0 && $result->errmsg=='ok') {
    		foreach($result->card_list as $card) {
    			$ret[]=$card->code;
    		}
    	}
    	
    	return $ret;
    }
    
    /**
     * 根据卡券的code码确定卡券是否可以核销
     * @param unknown $inter_id
     * @param unknown $card_id
     * @param unknown $code
     * @return boolean
     */
    public function getWxCardStatus($inter_id,$card_id,$code)
    {
    	$this->load->model('wx/Access_token_model');
    	$access_token = $this->Access_token_model->get_access_token($inter_id);
    	$url ="https://api.weixin.qq.com/card/code/get?access_token=".$access_token;
    
    	$data['card_id']=$card_id;
    	$data['code']=$code;
    	$data['check_consume']=true;
    	 
    	$result = $this->http_post($url, json_encode($data));
    	$result = json_decode($result);

    	if($result->errcode==0 && $result->errmsg=='ok') {
    		if($result->user_card_status=='NORMAL' && $result->can_consume==true) return true;
    	}
    	
    	return false;
    }
    
    function http_post($url,$data)
    {
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
    	$result = curl_exec($curl);
    	if (curl_errno($curl)) {
    		return 'ERROR '.curl_error($curl);
    	}
    	curl_close($curl);
    	return $result;
    }
    
    //==========================================================================================

    //卡号解密
    public function code_decrypt($inter_id, $code) {
    	$this->load->model ( 'wx/access_token_model' );
    	$url = "https://api.weixin.qq.com/card/code/decrypt?access_token=" . $this->access_token_model->get_access_token ( $inter_id );
    	$this->load->helper ( 'common' );
    	$res = json_decode ( doCurlPostRequest ( $url, json_encode ( array ( 'encrypt_code' => $code ) ) ) );
    	if ($res && isset ( $res->code ))
    		return $res->code;
    		else
    			return FALSE;
    }
    /**
     * 积分变动
     * @param $inter_id
     * @param $card_id 卡券ID
     * @param $code 用户领取的卡券的Code
     * @param $bones 积分总数
     * @param $add_bonus 积分变动数量，整数+，负数-
     * @param $record_bonus 变动说明
     * */
    public function up_bones($inter_id,$card_id,$code,$bones,$add_bonus,$record_bonus){
    	$params['code'] = $code;
    	$params['card_id'] = $card_id;
    	$params['bonus'] = $bones;//积分总数
    	$params['add_bonus'] = $add_bonus;//积分变动数量，整数+，负数-
    	$params['record_bonus'] = $record_bonus;//变动说明
    	return $this->update_card_info($inter_id, json_encode($params));
    }
    /**
     * 卡券变动
     * @param $inter_id
     * @param $card_id 卡券ID
     * @param $code 用户领取的卡券的Code
     * @param $coupon 卡券信息
     * */
    public function up_coupon($inter_id,$card_id,$code,$coupon){
    	$params['code'] = $code;
    	$params['card_id'] = $card_id;
    	$params['init_custom_field_value1'] = $coupon;
    	return $this->update_card_info($inter_id, json_encode($params));
    }
    /**
     * 卡券变动
     * @param $inter_id
     * @param $card_id 卡券ID
     * @param $code 用户领取的卡券的Code
     * @param $level_string 等级信息
     * */
    public function up_level($inter_id,$card_id,$code,$level_string){
    	$params['code'] = $code;
    	$params['card_id'] = $card_id;
    	$params['init_custom_field_value1'] = $level_string;
    	return $this->update_card_info($inter_id, json_encode($params));
    }
    /**
     * 卡券变动
     * @param $inter_id
     * @param $avgs Array
     * */
    public function do_active($inter_id,$avgs){
    	$params["init_bonus"]=isset($avgs['init_bonus']) ? $avgs['init_bonus'] : 0;//初始化积分数
//    	$params["init_bonus_record"] = isset($avgs["init_bonus_record"]) ? $avgs["init_bonus_record"] : "旧积分同步";//积分获得来源说明
    	$params["membership_number"]=$avgs["membership_number"];//会员号
    	$params["code"]=$avgs["code"];//卡券编号
    	$params["card_id"]=$avgs["card_id"];//卡券ID
    	// 		$params["background_pic_url"]="https://mmbiz.qlogo.cn/mmbiz/0?wx_fmt=jpeg";//卡券背景
    	$params["init_custom_field_value1"]=$avgs["init_custom_field_value1"];//初始化等级信息
//    	$params["init_custom_field_value2"]=$avgs["init_custom_field_value2"];//初始化卡券信息
    	$this->load->helper('common');
    	$url = "https://api.weixin.qq.com/card/membercard/activate?access_token=".$this->access_token_model->get_access_token($inter_id);
    	return json_decode(doCurlPostRequest($url, json_encode($params,JSON_UNESCAPED_UNICODE)),true);
    }
    public function update_card_info($inter_id,$info_string){
    	$this->load->model('wx/access_token_model');
    	$url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token=".$this->access_token_model->get_access_token($inter_id);
    	$this->load->helper('common');
    	return json_decode(doCurlPostRequest($url, $info_string));
    }
}