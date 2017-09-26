<?php
class Coupon_new_model extends CI_Model {
    protected $CI;
    protected $obj;
    protected $pms_set;

    function __construct() {
        parent::__construct ();
        $this->CI = & get_instance ();

//        $this->vid=$this->pms_set;
        $this->_server=INTER_PATH_URL;
//        $this->_server='http://vip.iwide.cn/api2/';
    }

    //订房接入会员4.0
    protected function request_post($url,$params,$timeout=5){
       $this->load->model('hotel/Member_new_model');
       return $this->Member_new_model->request_post($url,$params,$timeout);
    }

    protected function api_write_log( $content, $type='request' )     //记录日志
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'hotel'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }




    function getNewCoupon($openid,$inter_id,$card_id,$uu_code){         //会员获取一张新的优惠券


        $url= $this->_server. 'intercard/receive';
        $params['openid']= $openid;
        $params['inter_id']=$inter_id;
        $params['module']='hotel';
        $params['card_id']=$card_id;
        $params['uu_code']=$uu_code;
//        $params['uu_code']=time().rand(0,9999);

        $result=$this->request_post($url,$params);

        return $result;

    }

    function myCoupons($openid,$inter_id,$num='',$pms='',$params=array()){         //获取会员所有领取过的优惠券

        $url= $this->_server. 'membercard/getlist';
        $params['openid']= $openid;
        $params['inter_id']=$inter_id;
        $params['module']='hotel';
//        $params['next_id']=199;
        $params['num']='250';//会员模块返回的数量写死在最多100，最少10
        $params['type']='';
        $params['is_pms']=$pms;
        $params['uu_code']=time().rand(0,9999);

        $coupon_list=$this->request_post($url,$params);
        if (!empty($coupon_list['data'])){
            $now=time();
            foreach ($coupon_list['data'] as $k=>$d){
                if (!empty($d['use_time_start'])&&$d['use_time_start']>$now){
                    unset($coupon_list['data'][$k]);
                }
            }
        }
        return $coupon_list;

    }

    function allCouponsList($inter_id){         //获取酒店可用的所有优惠券


        $url= $this->_server. 'intercard/getlist';
        $params['inter_id']=$inter_id;
        $params['module']='hotel';

        $coupon_list=$this->request_post($url,$params);

        return $coupon_list;

    }


    function userCoupon($openid,$inter_id,$member_card_id,$scene='',$remark='',$offline='',$params=array()){         //会员使用一张优惠券


        $url= $this->_server. 'membercard/useone';
        $params['openid']= $openid;
        $params['inter_id']=$inter_id;
        $params['module']='hotel';
        $params['member_card_id']=$member_card_id;
        $params['scene']=$scene;
        $params['remark']=$remark;
        $params['offline']=$offline;

        $result=$this->request_post($url,$params);

        return $result;

    }


    function userOffCoupon($openid,$inter_id,$member_card_id,$scene='',$remark='',$offline='',$params=array()){         //会员核销一张优惠券


        $url= $this->_server. 'membercard/useoff';
        $params['openid']= $openid;
        $params['inter_id']=$inter_id;
        $params['module']='hotel';
        $params['member_card_id']=$member_card_id;
        $params['scene']=$scene;
        $params['remark']=$remark;
        $params['offline']=$offline;

        $result=$this->request_post($url,$params);

        return $result;

    }



    function returnCoupon($openid,$inter_id,$member_card_id,$scene='',$remark='',$offline='',$params=array()){         //会员返还一张优惠券


        $url= $this->_server. 'membercard/rollback';
        $params['openid']= $openid;
        $params['inter_id']=$inter_id;
        $params['module']='hotel';
        $params['member_card_id']=$member_card_id;
        $params['scene']=$scene;
        $params['remark']=$remark;
        $params['offline']=$offline;

        $result=$this->request_post($url,$params);

        return $result;

    }

    function change_coupon_status($openid,$inter_id,$member_card_id,$funtion,$scene='',$remark='',$offline='',$params=array()){
    	switch ($funtion){
    		case 'userCoupon':
    			$result=$this->userCoupon($openid, $inter_id, $member_card_id, $scene, $remark, $offline,$params);
    			if (isset($result['err'])&&$result['err']==0){
    				return true;
    			}
    			break;
    		case 'userOffCoupon':
    			$result=$this->userOffCoupon($openid, $inter_id, $member_card_id, $scene, $remark, $offline,$params);
    			if (isset($result['err'])&&$result['err']==0){
    				return true;
    			}
    			break;
    		case 'returnCoupon':
    			$result=$this->returnCoupon($openid, $inter_id, $member_card_id, $scene, $remark, $offline,$params);
    			if (isset($result['err'])&&$result['err']==0){
    				return true;
    			}
    			break;
    		default:
    			break;
    	}
    	return false;
    }

    function myCouponsTypes($openid,$inter_id){         //获取会员所有领取过的优惠券
    	$types=array();
    	$all_cards=$this->myCoupons($openid,$inter_id);
    	if (!empty($all_cards['data'])){
    		foreach ($all_cards['data'] as $card){
    			//取出卡券数据 @ 大鹏
			    $types[$card['card_id']]=$card;
    		}
    	}

    	return $types;
    
    }
}