<?php
/**
 * Created by PhpStorm.
 * User: vvanjack
 * Date: 2017/1/5
 * Time: 12:33
 */

class Wechat_membercard_model extends MY_Model_Member {

//    protected $table_config = 'iwide_wxmember_config';//会员卡配置（会员数据库内） ---停用
    protected $table_config = 'iwide_wechat_card_config';//会员卡配置（会员数据库内）
    protected $table_card_code_record = 'iwide_wechat_member_card';   //用户领取
    protected $table_wechat_member_card = 'iwide_wechat_member_card'; //用户会员卡操作记录

    protected $create_url = 'https://api.weixin.qq.com/card/create?access_token=';         //创建会员卡
    protected $update_card_url ="https://api.weixin.qq.com/card/update?access_token=";      //更新会员卡
    protected $del_card_url = "https://api.weixin.qq.com/card/delete?access_token=";      //删除会员卡
    protected $card_info_url = "https://api.weixin.qq.com/card/get?access_token=";      //获取卡配置信息

    protected $user_card_info_url = 'https://api.weixin.qq.com/card/membercard/userinfo/get?access_token='; //用户卡券
    protected $update_user_card_url = 'https://api.weixin.qq.com/card/membercard/updateuser?access_token=';  //更新用户会员卡信息
    protected $modify_stock_url = 'https://api.weixin.qq.com/card/modifystock?access_token=';//更改库存接口


    protected $decrypt_code_url =  "https://api.weixin.qq.com/card/code/decrypt?access_token="; //解密code

    protected $upload_img_url =  "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=";     //上传多媒体
    protected $qrc_code_url = "https://api.weixin.qq.com/card/qrcode/create?access_token="; //领取链接（二维码）信息


    protected $wx_pms_activate_url = "https://api.weixin.qq.com/card/membercard/activate?access_token=";
    protected $wx_activate_card_set_url = "https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token="; //微信一键激活设定字段地址

    const MEMBER_CARD = 'MEMBER_CARD'; //微信会员卡

    //卡时间
    const DATE_FOREVER = "DATE_TYPE_PERMANENT"; //失效时间：永不失效
    const DATE_RANGE = "DATE_TYPE_FIX_TIME_RANGE";  //固定时间区间
    const DATE_FIX  ="DATE_TYPE_FIX_TERM";      //表示自领取后多少天内有效

    //会员卡类别
    const CARD_TYPE_LEVEL  =    'FIELD_NAME_TYPE_LEVEL';//等级
    const CARD_TYPE_COUPON =    'FIELD_NAME_TYPE_COUPON';//优惠券                
    const CARD_TYPE_STAMP =    'FIELD_NAME_TYPE_STAMP';//印花
    const CARD_TYPE_DISCOUNT =    'FIELD_NAME_TYPE_DISCOUNT';// 折扣
    const CARD_TYPE_ACHIEVEMENT =    'FIELD_NAME_TYPE_ACHIEVEMEN';//成就
    const CARD_TYPE_MILEAGE =    'FIELD_NAME_TYPE_MILEAGE';//  里程
    const CARD_TYPE_POINTS =    'FIELD_NAME_TYPE_SET_POINTS';//集点
    const CARD_TYPE_TIMES =    'FIELD_NAME_TYPE_TIMS';//次数
    //会员卡类别

    protected $code_type = array(
        'CODE_TYPE_TEXT', //文本
        'CODE_TYPE_BARCODE', //一维码
        'CODE_TYPE_QRCODE', //二维码
        'CODE_TYPE_ONLY_QRCODE', //仅显示二维码    
        'CODE_TYPE_ONLY_BARCODE',  //仅显示一维码   
        'CODE_TYPE_NONE'        //不显示任何码型
    );

    /**
     * *获取会员卡本地配置信息
     * @param $inter_id
     * @param string $wx_card_id 扩展用
     */
    public function get_config($inter_id ,$wx_card_id ='',$status = 1){
        if(empty($inter_id)) return false;
        $where = array(
            'inter_id' => $inter_id,
            'status'    => $status
        );


        if(!empty($wx_card_id)) $where['card_id'] = $wx_card_id;
        $config_info = $this->_shard_db()->select('*')
            ->get_where($this->table_config , $where)
            ->row_array();
        if($config_info){
            return $config_info;
        }else{
            return array();
        }

    }


    /**
     * @param $inter_id
     * @param $wx_card_id
     */
    public function save_config($inter_id,$wx_card_id,$config =''){
        $data = array(
            'inter_id'  => $inter_id,
            'create_time' => date("Y-m-d H:i:s",time()),
            'card_id'   => $wx_card_id
        );

        if(!empty($data)){
            $data['content'] = json_encode($config);
        }

        $result = $this->_shard_db(true)->set($data)->insert($this->table_config );
        return $result;
    }

    public function update_config($inter_id,$wx_card_id,$data){
       $where = array(
           'inter_id'   => $inter_id,
           'card_id'    => $wx_card_id
       );
        $result = $this->_shard_db(true)->where($where)->set($data)->update($this->table_config );
        return $result;

    }

    /**
     * /从微信同步数据
     * @param $inter_id
     * @param $card_id
     * @return array
     */
    public function get_config_from_wechat($inter_id,$card_id){
        $this->load->model ( 'wx/access_token_model' );
        $url = $this->card_info_url. $this->access_token_model->get_access_token ( $inter_id );
        $data = array(
            'card_id' => $card_id
        );
        $result = $this->doCurl($url,  json_encode($data) ,"get_card");
        $result = json_decode($result,true);
        if (isset ( $result['errcode'] ) &&  $result['errcode'] == 0 && isset($result['card']))
            return $result['card'];
        else
            return array();
    }



    //卡code解密
    /**
     * @param $inter_id
     * @param $code
     * @return bool
     */
    public function code_decrypt($inter_id, $code) {
        $this->load->model ( 'wx/access_token_model' );
        $url = $this->decrypt_code_url. $this->access_token_model->get_access_token ( $inter_id );

        $data = array(
            'encrypt_code' => $code
        );

        $result = $this->doCurl($url,  json_encode($data) ,"decrypt");
        $codeArr = json_decode($result,true);
        if (!empty($codeArr) && isset ( $codeArr['code'] ))
            return $codeArr['code'];
        else
            return FALSE;
    }



    /**
     * 创建会员卡
     * @param $inter_id
     * @param $data
     * @return array|bool|mixed
     */
    public function create_wechat_membercard($inter_id,$data){
        if(empty($inter_id)) return false;
        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);

        $card['card_type'] = self::MEMBER_CARD;

        //永远有效
        $data['base_info']['date_info'] = array(
            "type" => self::DATE_FOREVER
        );

        $check_result = $this->verify_create_card($data);
        if(!isset($check_result['err'])){
            return false;
        }elseif(  $check_result['err'] == 2){
            return json_encode($check_result);
        }

        //发送创建请求到微信
        $card['member_card'] = $data;
        $wechatCardInfo['card'] = $card;

        $wechatCardInfoJson = json_encode($wechatCardInfo, JSON_UNESCAPED_UNICODE);
        // 发送微信
        if (!$wechatCardInfoJson) return false;
        $result = $this->doCurl($this->create_url.$access_token, $wechatCardInfoJson,'create');

        return $result;

    }

    public function wx_activate_card_set($inter_id,$data){
        if(empty($inter_id)) return false;
        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $wechatCardInfoJson = json_encode($data, JSON_UNESCAPED_UNICODE);

        // 发送微信
        if (!$wechatCardInfoJson) return false;

        $result = $this->doCurl($this->wx_activate_card_set_url.$access_token, $wechatCardInfoJson,'wx_active_set');
        return $result;
    }

    /**
     * //更新会员卡属性
     * @param $inter_id
     * @param $data
     */
    public function update_card($inter_id,$data){
        if(empty($inter_id)) return false;
        $card_id = $data['card_id'];
        $card_data = $this->verify_update_card_data($data);

        $post_data = array(
            'card_id'   => $card_id,
            'member_card' => $card_data
        );
        $wechatCardJson = json_encode($post_data,JSON_UNESCAPED_UNICODE);

        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);

        $res_json = $this->doCurl($this->update_card_url.$access_token, $wechatCardJson,'update_card');

        return $res_json;

    }

    /**
     * /删除微信卡（慎用！）
     * @param $inter_id
     * @param $card_id
     */
    public function del_wechat_card($inter_id,$card_id){
        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $post_data = array(
                'card_id' => $card_id
        );
        $res_json = $this->doCurl($this->update_card_url.$access_token, $post_data,'update_card');

        return $res_json;
    }

    public function verify_update_card_data($data){
        //可更新字段,未补全，详细请查看接口文档：https://mp.weixin.qq.com/wiki
        $base_fields = array(
            'title',
            'logo_url',
            'notice',
            'description',
            'service_phone',
            'color',
            'center_title',
            'center_sub_title',
            'center_url',
            'code_type',
            'promotion_url_name',
            'promotion_url',
            'promotion_url_sub_title'
        );
        //非基础属性以外的字段，未补全，详细请查看接口文档：https://mp.weixin.qq.com/wiki
        $card_fields = array(
            "base_info",
            "background_pic_url",
            "bonus_url",
            "balance_url",
            "prerogative",
            "custom_field1",
            "custom_field2",
            "custom_field3",
            "custom_cell1",
            "custom_cell2",
        );

        foreach($data as $key => $value){
            if(!in_array($key,$card_fields)){
                unset($data[$key]);
            }elseif(in_array($key,array("custom_field1","custom_field2","custom_field3")) && empty($value['name']) && empty($value['url'])){
                unset($data[$key]);
            }
        }

        if(isset($data['base_info']) && !empty($data['base_info'])){
            foreach($data['base_info'] as $key => $value){
                if(!in_array($key,$base_fields)){
                    unset($data['base_info'][$key]);
                }elseif(in_array($key,array("custom_cell1","custom_cell2")) && empty($value['name'])){
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }


    /**
     * 上传图片到微信
     * @param $inter_id
     * @param $url
     * @return array|bool
     */
    public function upload_img($inter_id,$url){
        if(empty($inter_id)) return false;
        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $post_url = $this->upload_img_url.$access_token;

        $this->load->helper('download');
        // 保存网络图片至本地,返回本地地址
        $file = downloadFile($url, $_SERVER['DOCUMENT_ROOT'] . '/public/uploads');
        if(realpath($file)) {
            $data['buffer']   = new CURLFile(realpath($file));

            $result = $this->http_post($post_url, $data, 'upload');
            if(isset($result['url'])) {
                @unlink($file);
                return array('error'=>false,'url'=>$result['url']);
            } else {
                return array('error'=>true,'errmsg'=>$result['errcode'].":".$result['errmsg']);
            }
        }
    }


    //验证常见会员卡字段
    public function verify_create_card($card_info){

        $err_array = array();
        if(empty($card_info)) return $err_array = array( 'status' => 2, 'msg' =>'数据为空,请填写数据' );


        if(!isset($card_info['base_info']) || empty($card_info['base_info'])) {
            $err_array['msg'][] = '基本的卡券数据为空';
        }

        /*会员卡基础信息*/
        $base_info = $card_info['base_info'];
        //logo
        if(!isset($base_info['logo_url']) || empty($base_info['logo_url'])){
            $err_array['msg'][] = '卡券商户logo为空';
        }

        //code_type
        if(!isset($base_info['code_type']) || empty($base_info['code_type'])){
            $err_array['msg'][] = 'Code展示类型为空';
        }elseif( !in_array($base_info['code_type'],$this->code_type)){
            $err_array['msg'][] = 'Code展示类型不吻合';
        }

        //商户名字,字数上限为12个汉字。            
        if(!isset($base_info['brand_name']) || empty($base_info['brand_name'])){
            $err_array['msg'][] = '商户名称为空';
        }elseif(mb_strlen($base_info['brand_name'], 'utf8') > 12){
            $err_array['msg'][] = '长度超过规定长度';
        }

        //卡名字,字数上限为9个汉字。            
        if(!isset($base_info['title']) || empty($base_info['title'])){
            $err_array['msg'][] = '会员卡为空';
        }elseif(mb_strlen($base_info['title'], 'utf8') > 9){
            $err_array['msg'][] = '长度超过规定长度';
        }

        //券颜色。按色彩规范标注填写Color010-Color100           
        if(!isset($base_info['color']) || empty($base_info['color'])){
            $err_array['msg'][] = '券颜色为空';
        }

        //卡券使用提醒，字数上限为16个汉字。            
        if(!isset($base_info['notice']) || empty($base_info['notice'])){
            $err_array['msg'][] = '使用提醒为空';
        }elseif(mb_strlen($base_info['notice'], 'utf8') > 9){
            $err_array['msg'][] = '使用须知超过规定长度';
        }

        //使用说明，字数上限为1024个汉字。
        if(!isset($base_info['description']) || empty($base_info['description'])){
            $err_array['msg'][] = '使用说明为空';
        }elseif(mb_strlen($base_info['notice'], 'utf8') > 1024){
            $err_array['msg'][] = '使用说明超过规定长度';
        }

        //卡券库存的数量，不支持填写0，上限为100000000。
        if(!isset($base_info['sku']['quantity']) || empty($base_info['sku']['quantity']) || $base_info['sku']['quantity'] < 1 || $base_info['sku']['quantity'] > 100000000 ){
            $err_array['msg'][] = '卡券库存的数量有误';
        }
        /*会员卡基础信息*/

        //会员卡特权说明
        if(!isset($card_info['prerogative']) || empty($card_info['prerogative'] )){
            $err_array['msg'][] = '会员卡特权说明为空';
        }elseif(mb_strlen($base_info['title'], 'utf8') > 1000){
            $err_array['msg'][] = '会员卡特权说明超过规定长度';
        }



        if(isset( $err_array['msg']) && !empty( $err_array['msg'])){
            $err_array['err'] = 2;
        }else{
            $err_array['err'] = 0;
        }
        return $err_array;
    }



    /*新增领卡数据*/
    public function add_get_card_info($inter_id,$data){
        if(empty($inter_id) || empty($data)) return false;
        $dbData = array(
            'inter_id'  => $inter_id,
            'open_id'   => $data['FromUserName'],
            'card_id'   => $data['CardId'],
            'code'      => $data['UserCardCode']
        );

        $where = $dbData;
        $result = $this->_shard_db()->select('*')
            ->get_where($this->table_wechat_member_card , $where)
            ->row_array();

        if($result){
            //重新找回
            if(isset($data['IsRestoreMemberCard']) && $data['IsRestoreMemberCard'] == 1){
                $result = $this->_shard_db(true)->where($where)->set(array('status' => 1 ,'last_update_time' => date("Y-m-d H:i:s")  ))->update($this->table_wechat_member_card);
            }

            return $result['wechat_card_id'];
        }else{
            /*检查是否有分销信息*/
            if(( isset($data['OuterStr']) && !empty($data['OuterStr']) )|| ( isset($data['OuterId']) && !empty($data['OuterId']) )){
                $this->load->model('membervip/admin/Distribution_model');
                $record = $this->Distribution_model->check_distribution_relation($inter_id,$data['FromUserName']);
                if(empty($record)){
                    $relation_data = array(
                        'saler_openid' => $data['OuterStr'],
                        'fans_openid'   =>  $data['FromUserName'],
                        'source'    => isset($data['SourceScene']) ? $data['SourceScene']: 1

                    );
                    if(isset($data['OuterId'])) $relation_data['saler_id'] = $data['OuterId'];
                    if(isset($data['OuterStr'])) $relation_data['saler_openid'] = $data['OuterStr'];
                    $rid = $this->Distribution_model->add_distribution_relation($inter_id,$relation_data);
                    $this->load->library('MYLOG');
                    MYLOG::w("Type:Insert | Insert relation data : ".json_encode($data)." | Result ". $rid,'membervip/distribution');
                }
            }

            $dbData['add_time'] =  $dbData['last_update_time'] = date("Y-m-d H:i:s",$data['CreateTime']);
            $this->_shard_db(true)->set($dbData)->insert($this->table_wechat_member_card);
            $last_id = $this->_shard_db(true)->insert_id();
            return $last_id;
        }

    }



    /**
     * 删除已领取的卡券-->更改状态(本地库)
     * @param $inter_id
     * @param $data
     * @return bool
     */
    public function del_card_info($inter_id,$data){
        if(empty($inter_id) || empty($data)) return false;
        $where = array(
            'inter_id'  => $inter_id,
            'open_id'   => $data['FromUserName'],
            'card_id'   => $data['CardId'],
            'code'      => $data['UserCardCode']
        );

        $result = $this->_shard_db(true)->where($where)->set(array('status' => 0 ,'last_update_time' => date("Y-m-d H:i:s")  ))->update($this->table_wechat_member_card);
        if($result){
            return true;
        }else{
            return false;
        }

    }




    /*同步会员数据到微信会员卡*/
    public function sys_member_info($inter_id,$data){
        $code =  $data['UserCardCode'];
        $card_id = $data['CardId'];
        $open_id = $data['FromUserName'];

        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=> $inter_id,
            'openid' => $open_id,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $info_res = $this->doCurlPostRequest( $post_center_url , $post_center_data , "wechatcard_syc");

        //获取用户信息
        if(empty($info_res) || !isset($info_res['data']) || empty($info_res['data']))   MYLOG::w("Failed to get user info from member/center","wechat_member_card/Error");; //获取失败，错误记录

        $member_info = $info_res['data'];

        //获取会员卡配置
        $config = $this->get_config($inter_id);
        $updateUserInfo = array();

        /*初始化数据*/
        if(empty($config)) return  MYLOG::w("Failed to update user : Empty card config","wechat_member_card/Warning");
        $config = json_decode($config['content'],true);
        $cardConfig = $config['member_card'];
        if(isset($cardConfig['supply_bonus']) && $cardConfig['supply_bonus'])  $updateUserInfo['bonus'] = $member_info['credit'];   //有配置积分
        if(isset($cardConfig['supply_balance']) && $cardConfig['supply_balance'])  $updateUserInfo['balance'] = $member_info['balance'] * 100; //有配置余额，微信会员卡需要*100
        //自定义快捷栏
        for($i=0;$i<3;$i++){
            if(!isset($cardConfig['custom_field'.$i]) || ( empty($cardConfig['custom_field'.$i]) || empty($cardConfig['custom_field'.$i]['name']) )) continue;
            switch($cardConfig['custom_field'.$i]['name_type']){
                case "FIELD_NAME_TYPE_LEVEL":  //等级
                    $updateUserInfo['custom_field_value'.$i] = $member_info['lvl_name'];
                    break;
                case "FIELD_NAME_TYPE_COUPON":  //优惠券
                    $updateUserInfo['custom_field_value'.$i] = $member_info['card_count'];
                    break;
                default:
                    break;
            }
        }
        /*初始化数据 end*/

        //模板消息提醒配置
        $updateUserInfo['notify_optional'] = array(
            "is_notify_bonus"=> false,          //积分变更提醒
            "is_notify_balance"=> false,         //余额变更提醒
            "is_notify_custom_field1"=> false,     //自定义
            "is_notify_custom_field2"=> false,     //自定义
            "is_notify_custom_field3"=> false     //自定义

        );

        $updateUserInfo['code'] = $code;
        $updateUserInfo['card_id'] = $card_id;

        $this->load->model ( 'wx/access_token_model' );
        $url = $this->update_user_card_url. $this->access_token_model->get_access_token ( $inter_id );


        $post_data = json_encode($updateUserInfo,JSON_UNESCAPED_UNICODE);
        $res_json = $this->doCurl($url, $post_data,'update_user');
        $update_result = json_decode($res_json,true);
        if(empty($update_result) || !isset($update_result['errcode']) || $update_result['errcode'] != 0 )   MYLOG::w("Failed to update user : ".$update_result['errmsg'],"wechat_member_card/Warning"); //获取失败，错误记录

    }


    /**
     * 修改库存
     * @param $inter_id
     * @param $card_id
     * @param int $increase /增加数目
     * @param int $reduce   /减少数目
     * @return array|mixed
     */
    public function modify_stock($inter_id,$card_id,$increase=0,$reduce=0){
        if(empty($card_id) || $increase< 0 || $reduce < 0 )
            return array(
                "err" => 40003,
                "参数有误，增加、减少库存数量不能为负数"
            );

        $this->load->model ( 'wx/access_token_model' );
        $url = $this->modify_stock_url.$this->access_token_model->get_access_token($inter_id);
        $postData = array(
            'card_id'   => $card_id,
            'increase_stock_value'  => $increase,
            'reduce_stock_value'  =>$reduce
        );
        $post_data = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res_json = $this->doCurl($url, $post_data,'modify_stock');
        $update_result = json_decode($res_json,true);
        if(empty($update_result) || !isset($update_result['errcode']) || $update_result['errcode'] != 0 )   MYLOG::w("Failed to modify stock . Params :".json_encode($postData) ." | Result : ".$update_result['errmsg'],"wechat_member_card/Warning"); //获取失败，错误记录
        return $res_json;
    }

    /**
     * //微信会员卡选择一键激活时的同步
     * @param $inter_id
     * @param $data
     */
    public function wx_activate_syc($inter_id,$data){
        $code =  $data['UserCardCode'];
        $card_id = $data['CardId'];
        $open_id = $data['FromUserName'];

        $post_center_url = PMS_PATH_URL."member/center";
        $post_center_data =  array(
            'inter_id'=> $inter_id,
            'openid' => $open_id,
        );
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        $info_res = $this->doCurlPostRequest( $post_center_url , $post_center_data , "wechatcard_syc");
        //请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
        if(empty($info_res) || !isset($info_res['data']) || empty($info_res['data']) ){
            echo json_encode(array('err'=> 400,'msg'=>'会员卡有误'));
            exit;
        }else{
            $memberInfo =  $info_res['data'];
        }

        $param = $this->format_data($inter_id,$card_id,$code,$memberInfo,false);
        $res = $this->do_active($inter_id,$param ,$card_id ,$open_id);
        if(empty($res) || !isset($res['errcode']) || $res['errcode'] != 0 )   MYLOG::w("Failed to wx_activate user : ".json_encode($res)." Params : ".json_encode($param),"wechat_member_card/Warning"); //获取失败，错误记录
    }



    /**
     * 卡券变动激活
     * @param $inter_id
     * @param $avgs Array
     * */
    public function do_active($inter_id,$avgs,$card_id,$open_id){
        if(empty($inter_id) || empty($avgs) || empty($card_id) || empty($open_id)) return false;

        /*检查卡号是否重复激活*/
        $where = array(
            'inter_id'  => $inter_id,
            'membership_number'   =>  $avgs['membership_number']
        );
        $record = $this->_shard_db()->select('*')
            ->get_where($this->table_wechat_member_card , $where)
            ->row_array();
        if($record && !empty($record)){
            return array(
                'err' => '40003',
                'msg'   => '该会员号已被激活，请更换卡号再试'
            );
        }


        $this->load->model ( 'wx/access_token_model' );
        $url = $this->wx_pms_activate_url.$this->access_token_model->get_access_token($inter_id);
        $res = json_decode($this->doCurl($url, json_encode($avgs,JSON_UNESCAPED_UNICODE),'activate_user'),true);
        if($res && isset($res['errcode']) && $res['errcode'] == 0 && $res['errmsg'] == 'ok'){
            //更新卡membership_number
            $where = array(
                'inter_id'  => $inter_id,
                'card_id'   => $card_id,
                'code'      => $avgs['code'],
                'open_id'   => $open_id
            );
           $this->_shard_db(true)->where($where)->set(array( 'membership_number' => $avgs['membership_number']))->update($this->table_wechat_member_card);

           //更新会员分销关系
            $this->load->model('membervip/admin/Distribution_model');
            $record = $this->Distribution_model->check_distribution_relation($inter_id,$open_id,array('status'=>1));
            if(!empty($record)){
                $updateRelationData = array(
                    'status'    => 2,
                    'updatetime'    => date("Y-m-d H:i:s",time())
                );
                $where = array(
                    'fans_openid'   => $open_id,
                    'inter_id'  => $inter_id,
                    'status' => 1
                );
                $rid = $this->Distribution_model->update_distribution_relation($inter_id,$updateRelationData,$where);
                $this->load->library('MYLOG');
                MYLOG::w("Type:Update | Update relation data : ".json_encode($updateRelationData)." | Where : " .json_encode($where). " Result row". $rid,'membervip/distribution');
                /*发送会员分销绩效绑定*/
                $this->load->model('wx/Qrcode_model');
                $this->load->model('wx/Publics_model');
                $event_time = date('Y-m-d H:i:s');
                $this->load->model('distribute/fans_model');
                if(isset($data['OuterId']) && $data['OuterId'] > 0)
                    $fans_id = $this->Qrcode_model->event_log( $data['OuterId'],$this->token,2,$data['FromUserName'],$event_time);
                $this->fans_model->mark_fans_grades($this->token,$data['FromUserName'],$data['OuterId'],2,$fans_id,date('Y-m-d H:i:s'));


            }
        }

        return $res;
    }

    /**
     * *获取会员卡券信息
     * @param $inter_id
     * @param $open_id
     * @param $card_id
     * @return bool|mixed
     */
    public function get_user_card_info_by_openid($inter_id,$open_id,$card_id){
        $where = array(
            'inter_id'  => $inter_id,
            'open_id'   => $open_id,
            'card_id'   => $card_id
        );
        $codeInfo = $this->_shard_db()->select('*')
            ->get_where($this->table_wechat_member_card , $where)
            ->row_array();

        if(empty($codeInfo)) return false;
        $code = $codeInfo['code'];

        $data = array(
            'card_id'    => $card_id,
            'code'      => $code
        );
        $postData = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $result = $this->doCurl($this->wx_get_user_base_info_url.$access_token, $postData,"get_use_base_info");

        return $result;
    }

    /**
     * *获取会员卡券信息
     * @param $inter_id
     * @param $open_id
     * @param $card_id
     * @return bool|mixed
     */
    public function get_user_card_info_by_code($inter_id,$code,$card_id){
        $data = array(
            'card_id'    => $card_id,
            'code'      => $code
        );
        $postData = json_encode($data,JSON_UNESCAPED_UNICODE);

        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $result = $this->doCurl($this->wx_get_user_base_info_url.$access_token, $postData,"get_use_base_info");

        return $result;
    }

    //激活初始化数据格式化
    /**
     * @param $inter_id
     * @param $card_id
     * @param $code
     * @param $memberInfo
     * @param bool $need_decrypt_code //code是否需要解密
     * @return mixed
     */
    public function format_data($inter_id,$card_id,$code,$memberInfo,$need_decrypt_code = true){

        $this->load->model('membervip/front/Member_model');
        $dbCardData = $this->get_config($inter_id,$card_id);

        if(empty($dbCardData)){
            echo json_encode(array('err'=> 400,'msg'=>'不存在此微信卡'));
            exit;
        }

        $card_data = json_decode($dbCardData['content'],true);

        $cardConfig = $card_data['member_card'];


        if($need_decrypt_code){
            $decrypt_code = $this->code_decrypt($inter_id,$code);
        }else{
            $decrypt_code = $code;
        }

        $this->Member_model->update_wechat_card_code($memberInfo['open_id'],$inter_id,$decrypt_code,$card_id);
        /*初始化数据*/
        if(isset($cardConfig['supply_bonus']) && $cardConfig['supply_bonus']) $param['init_bonus'] = $memberInfo['credit'];     //有配置积分
        if(isset($cardConfig['supply_balance']) && $cardConfig['supply_balance']) $param['init_balance'] = $memberInfo['balance'] * 100; //有配置余额，微信需要乘以100
        $param['membership_number'] = $memberInfo['membership_number'];
        $param['code'] = $decrypt_code;
        //自定义快捷栏
        for($i=0;$i<3;$i++){
            if(!isset($cardConfig['custom_field'.$i]) || ( empty($cardConfig['custom_field'.$i]) || empty($cardConfig['custom_field'.$i]['name']) )) continue;
            switch($cardConfig['custom_field'.$i]['name_type']){
                case "FIELD_NAME_TYPE_LEVEL":  //等级
                    $param['init_custom_field_value'.$i] = $memberInfo['lvl_name'];
                    break;
                case "FIELD_NAME_TYPE_COUPON":  //优惠券
                    $param['init_custom_field_value'.$i] = $memberInfo['card_count'];
                    break;
                default:
                    break;
            }
        }
        /*初始化数据 end*/

        return $param;
    }

    public function syc_qrc($inter_id,$card_id){
        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $data = array(
            "action_name" => "QR_CARD",
            "action_info"   => array(
                "card"  => array(
                    'card_id'    => $card_id
                )
            )
        );
        $postData = json_encode($data,JSON_UNESCAPED_UNICODE);
        $result = $this->doCurl($this->qrc_code_url.$access_token, $postData);
        return $result;
    }

    /**
     * @param $inter_id
     * @param $open_id
     * @param $card_id
     * @return mixed
     */
    public function get_distribution_qrc($inter_id,$card_id,$open_id,$outer_id = 0 ){
        $this->load->model('wx/Access_token_model');
        $access_token = $this->Access_token_model->get_access_token($inter_id);
        $data = array(
            "action_name" => "QR_CARD",
            "action_info"   => array(
                "card"  => array(
                    'card_id'    => $card_id,
                    'outer_id'  => $outer_id,
                    'outer_str' => $open_id
                )
            )
        );
        $postData = json_encode($data,JSON_UNESCAPED_UNICODE);
        $result = $this->doCurl($this->qrc_code_url.$access_token, $postData);
        return $result;
    }

    /**
     * 调用自身会员系统接口
     * @param $url
     * @param $post_data
     * @param string $log_path
     * @param int $timeout
     * @return bool|mixed
     */
    function doCurlPostRequest( $url , $post_data , $log_path ='',$timeout = 20) {
        $start = microtime(true);
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        $end = microtime( true );
        $time = round( $end - $start , 6 );
        MYLOG::w("Type :".$log_path ." Post Data : ". json_encode($post_data)." | URL : ". $url  ." | Result " . json_encode($res) ." | Use Time : ". $time,"wechat_member_card");
        return json_decode($res,true);
    }

    /*post*/
    function http_post($url,$post_data , $log_path ='' ,$timeout =20)
    {
        $start = microtime(true);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        $end = microtime( true );
        $time = round( $end - $start , 6 );
        MYLOG::w("Type :".$log_path ." Post Data : ". json_encode($post_data)." | URL : ". $url  ." | Result " . json_encode($res) ." | Use Time : ". $time,"wechat_member_card");
        return json_decode($res,true);
    }

    /**
     * 封装curl的调用接口，post的请求方式_微信交互
     * @param string URL
     * @param string POST表单值
     * @param array  扩展字段值
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    function doCurl($url, $requestString, $log_path='',$extra = array(), $timeout = 5){
        $start = microtime(true);
        if($url == "" || $requestString == "" || $timeout <= 0){
            return false;
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);

        if(!empty ($extra) && is_array($extra)){
            $headers = array();
            foreach($extra as $opt => $value){
                if(strexists($opt, 'CURLOPT_')){
                    curl_setopt($con, constant($opt), $value);
                } elseif(is_numeric($opt)){
                    curl_setopt($con, $opt, $value);
                } else{
                    $headers [] = "{$opt}: {$value}";
                }
            }
            if(!empty ($headers)){
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
        $end = microtime( true );
        $time = round( $end - $start , 6 );
        MYLOG::w("Type :".$log_path ." Post Data : ". $requestString." | URL : ". $url  ." | Result " . json_encode($res) ." | Use Time : ". $time,"wechat_member_card");
        return $res;
    }

}
