<?php

/**
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Member_model extends MY_Model_Member {

    /**
     * 获取会员卡信息(优先拿取PMS会员卡信息)
     * @param string $inter_id 酒店集团ID
     * @param string $openid 微信openid
     * @param string $field 获取字段
     * @param array $extra 扩展参数
     * @return array
     */
    public function get_user_info($inter_id='',$openid='',$field='*',$extra = array()){
        if(empty($inter_id) || empty($openid)) return array();
        $where['inter_id'] = $inter_id;
        $where['open_id'] = $openid;
        $where['member_mode'] = !empty($extra['member_mode'])?$extra['member_mode']:2;
        $where['is_active'] = !empty($extra['is_active'])?$extra['is_active']:'t';
        $where['is_login'] = !empty($extra['is_login'])?$extra['is_login']:'t';
        $user = $this->_shard_db()->select($field)->where($where)->get('member_info')->row_array();
        if(empty($user)){
            unset($where['is_login']);
            $where['member_mode'] = 1;
            $user = $this->_shard_db()->select($field)->where($where)->get('member_info')->row_array();
        }
        if(empty($user)) return array();
        return $user;
    }

    public function get_member_info($mid=0){
        $info = $this->_shard_db()->where(array('member_info_id'=>$mid))->get('member_info')->row_array();
        if($info) return $info;
        return array();
    }

    public function get_member_card($inter_id='',$openid='',$field='member_info_id'){
        if(empty($inter_id) || empty($openid)) return array();
        $where['inter_id'] = $inter_id;
        $where['open_id'] = $openid;
        $where['member_mode'] = 2;
        $where['is_active'] = 't';
        $user = $this->_shard_db()->select($field)->where($where)->get('member_info')->row_array();
        if(empty($user)) return array();
        return $user;
    }


    public function update_wechat_card_code($openid,$inter_id,$code ,$card_id = '', $field = 'code'){
        if(empty($openid) || empty($openid) || empty($code) ) return false;
        $where['inter_id'] = $inter_id;
        $where['open_id'] = $openid;
        $code_res = $this->_shard_db()->select($field)->where($where)->get('wechat_member_card')->row_array();
        if(empty($code_res)){
            $data['inter_id'] = $inter_id;
            $data['open_id'] = $openid;
            $data['code'] = $code;
            $data['card_id'] = $card_id;
            return $this->_shard_db(true)->insert('wechat_member_card',$data);
        }

    }

    public function get_member_level_name($inter_id,$lvl_id,$field='lvl_name'){
        if(empty($inter_id) || empty($lvl_id)) return '微信会员';
        $where['inter_id'] = $inter_id;
        $where['member_lvl_id'] = $lvl_id;
        $lvl_name = $this->_shard_db()->select($field)->where($where)->get('member_lvl')->row_array();
        if(!empty($lvl_name)){
            return $lvl_name['lvl_name'];
        }else{
            return '微信会员';
        }

    }

    /**
     * 检测并返回会员信息
     * @param string $inter_id 酒店集团ID
     * @param string $openid 微信openid
     * @return array
     */
    public function check_user_info($inter_id='',$openid=''){
        if(empty($inter_id) || empty($openid)) return array();
        $user = $this->get_user_info($inter_id,$openid);
        $member_card = $this->get_member_card($inter_id,$openid);
        $user_info = array();
        if(!empty($user)){
            $user_info['credit'] = $user['credit'];
            $user_info['member_lvl_id'] = $user['member_lvl_id'];
            $user_info['member_info_id'] = $user['member_info_id'];
            $user_info['member_id'] = $user['member_id'];
            $user_info['open_id'] = $user['open_id'];
            $user_info['open_id_current_login'] = $user['open_id_current_login'];
            $user_info['inter_id'] = $user['inter_id'];
            $user_info['member_mode'] = $user['member_mode'];
            $user_info['name'] = $user['name'];
            $user_info['telephone'] = $user['telephone'];
            $user_info['cellphone'] = $user['cellphone'];
            $user_info['membership_number'] = $user['membership_number'];
            $user_info['pms_user_id'] = $user['pms_user_id'];
            $user_info['is_login'] = $user['is_login'];
            $user_info['ismembercard'] = !empty($member_card)?1:2;
            $user_info['createtime'] = $user['createtime'];
            $this->session->set_tempdata($inter_id.'vip_user',$user_info,3600);//缓存一小时
        }
        return $user_info;
    }

    /**
     * 读取优惠券规则列表
     */
    public function get_card_rule($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $mcrtab = 'member_card_rule';
        $ctab = 'card';
        $ptab = 'package';
        if(!isset($params['field'])) $params['field'] = $mcrtab.'.*';
        if(strpos($params['field'], ',')!==FALSE) {
            $fields = explode(',', $params['field']);
            $field = '';
            foreach ($fields as $v){
                $field.=$mcrtab.'.'.$v.',';
            }
            $params['field'] = substr($field, 0,-1);
        }

        $params['field'] .= ','.$ctab.'.title,'.$ctab.'.card_type,'.$ctab.'.card_stock,'.$ctab.'.card_note';
        $params['field'] .= ','.$ptab.'.name,'.$ptab.'.remark';

        $this->_shard_db()->select($params['field']);
        $this->_shard_db()->join($ctab,$ctab.'.card_id = '.$mcrtab.'.card_id','left');
        $this->_shard_db()->join($ptab,$ptab.'.package_id = '.$mcrtab.'.package_id','left');
        $where=array($mcrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        /*if(!empty($params['begin_time'])){
            $begin_time = strtotime($params['begin_time']);
            $this->_shard_db()->where('createtime >',$begin_time);
        }

        if(!empty($params['end_time'])){
            $end_time = strtotime($params['end_time']);
            $this->_shard_db()->where('createtime <=',$end_time);
        }*/

        $list = $this->_shard_db()
            ->order_by('createtime desc')
            ->limit($limit,$offset)
            ->get($mcrtab)->result_array();
//        echo '<pre>';
//        print_r($list);exit;

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        $this->_shard_db()->get($mcrtab)->free_result();
        return $list;
    }

    /**
     * 读取优惠券规则列表 总数
     */
    public function get_card_rule_total($params){
        $inter_id = $params['inter_id'];
        $mcrtab = 'member_card_rule';
        $ctab = 'card';
        $ptab = 'package';
        $this->_shard_db()->join($ctab,$ctab.'.card_id = '.$mcrtab.'.card_id','left');
        $this->_shard_db()->join($ptab,$ptab.'.package_id = '.$mcrtab.'.package_id','left');
        $where=array($mcrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);

        /*if(!empty($params['begin_time'])){
            $begin_time = strtotime($params['begin_time']);
            $this->_shard_db()->where(array('createtime >'=>$begin_time));
        }

        if(!empty($params['end_time'])){
            $end_time = strtotime($params['end_time']);
            $this->_shard_db()->where(array('createtime <='=>$end_time));
        }*/

        $count = $this->_shard_db()->get($mcrtab)->num_rows();
        return $count;
    }

    /**
     * 读取优惠券列表
     */
    public function getMemberCard($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $ctab = 'card';
        if(!isset($params['field'])) $params['field'] = $ctab.'.*';

        $this->_shard_db()->select($params['field']);
        $where=array($ctab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $list = $this->_shard_db()
            ->order_by('createtime desc')
            ->limit($limit,$offset)
            ->get($ctab)->result_array();
//        echo '<pre>';
//        print_r($list);exit;

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        $this->_shard_db()->get($ctab)->free_result();
        return $list;
    }

    /**
     * 读取优惠券列表 总数
     */
    public function getMemberCardTotal($params){
        $inter_id = $params['inter_id'];
        $ctab = 'card';
        $where=array($ctab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $count = $this->_shard_db()->get($ctab)->num_rows();
        return $count;
    }

    /**
     * 读取储值规则列表
     */
    public function get_deposit_card($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $cdrtab = 'deposit_card';
        if(!isset($params['field'])) $params['field'] = $cdrtab.'.*';

        $this->_shard_db()->select($params['field']);
        $where=array($cdrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $list = $this->_shard_db()
            ->order_by('createtime desc')
            ->limit($limit,$offset)
            ->get($cdrtab)->result_array();
//        echo '<pre>';
//        print_r($list);exit;

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        $this->_shard_db()->get($cdrtab)->free_result();
        return $list;
    }

    /**
     * 读取储值规则列表 总数
     */
    public function get_deposit_card_total($params){
        $inter_id = $params['inter_id'];
        $cdrtab = 'deposit_card';
        $where=array($cdrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $count = $this->_shard_db()->get($cdrtab)->num_rows();
        return $count;
    }

    public function get_member_card_info($inter_id='',$openid=''){
        $info = $this->_shard_db()->where(array('inter_id'=>$inter_id,'open_id'=>$openid,'member_mode'=>2))
                                  ->get('member_info')->row_array();
        if(empty($info)){
            $info = $this->_shard_db()->where(array('inter_id'=>$inter_id,'open_id'=>$openid,'member_mode'=>1))
                ->get('member_info')->row_array();
        }
        if($info) return $info;
        return array();
    }

    public function check_fans_and_member($inter_id='',$openid=''){
        $return['status'] = 'fail';
        $fans = $this->get_fans_info($inter_id,$openid);
        $this->_write_log($fans,'get_fans_info');
        $member_info = $this->get_member_card_info($inter_id,$openid);
        $this->_write_log($member_info,'get_member_card_info');
        if(empty($member_info)){
            if(!empty($fans) && !empty($fans['subscribe_time']))
                $return['status'] = '2'; //没有注册，已关注
            else
                $return['status'] = '1'; //没有注册，没有关注
        }
        if(!empty($member_info)){
            if(!empty($member_info['cellphone']) && !empty($fans) && !empty($fans['subscribe_time'])) $return['status'] = 'ok'; //已经注册，已关注
            if(empty($member_info['cellphone']) && !empty($fans) && !empty($fans['subscribe_time'])) $return['status'] = '2'; //没有注册，已关注
            if(!empty($member_info['cellphone']) && (empty($fans) || empty($fans['subscribe_time']))) $return['status'] = '3'; //已经注册，没关注
            if(empty($member_info['cellphone']) && (empty($fans) || empty($fans['subscribe_time']))) $return['status'] = '1'; //没有注册，没有关注
        }
        $this->_write_log($return,'check_fans_and_member');
        return $return;
    }

    /**
     * 获取粉丝信息
     * @param $openid 微信ID
     * @return mixed
     */
    public function get_fans_info($inter_id='',$openid='') {
        $where = array('openid'=>$openid);
        if(!empty($inter_id))
            $where['inter_id'] = $inter_id;
        $this->db->where($where);
        $query = $this->db->get ('fans')->row_array ();
        return $query;
    }

    /**
     * 通过微信接口获取微信用户信息
     * @param $inter_id 酒店集团ID
     * @param $openid 微信openid
     * @param null $accesstoken
     * @param bool $continue
     * @return mixed
     */
    public function get_wxuser_info($inter_id, $openid, $accesstoken = null,$continue = TRUE) {
        $this->load->model ( 'wx/Access_token_model' );
        $access_token = $this->Access_token_model->get_access_token ( $inter_id );
        if ($accesstoken)
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accesstoken&openid=$openid&lang=zh_CN";
        else
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid;

        $con = curl_init ( $url );
        curl_setopt ( $con, CURLOPT_HEADER, false );
        curl_setopt ( $con, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $con, CURLOPT_SSL_VERIFYPEER, false );
        $result = curl_exec ( $con );

        $result = json_decode ( $result, TRUE );
        if(isset($result['errcode']) && ($result['errcode'] == 40001 || $result['errcode'] == 42001) && $continue){
            $access_token = $this->Access_token_model->reflash_access_token($inter_id);
            return $this->get_wxuser_info($inter_id, $openid, $access_token, false);
        }
        return $result;
    }

    //会员登录
    public function member_login($data){
        $post_login_url = PMS_PATH_URL."member/login";
        $inter_id = $data['inter_id'];
        $open_id = $data['openid'];
        $post_login_data = array(
            'inter_id'=>$inter_id,
            'openid'=> $open_id,
            'data'=> $_POST,
        );

        //如果有验证码,验证
        $conf_url = PMS_PATH_URL."adminmember/getloginconfig";
        $post_data =  array('inter_id'=>$inter_id);
        //请求登录配置
        $loginconfig = $this->doCurlPostRequest($conf_url,$post_data);
        $loginconfig = isset($loginconfig['data'])?$loginconfig['data']:array();
        if(isset($loginconfig['phonesms']) && $loginconfig['phonesms']['show']=='1' && $loginconfig['phonesms']['check']=='1'){
            if(!isset($_POST['phonesms'])) {
                return array('err'=>'40003','msg'=>'验证码不存在');
            }
            $checkSmsData = $post_login_data;
            $checkSmsData['data']['sms']=$_POST['phonesms'];
            $checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
            $checkSmsData['cellphone']=$checkSmsData['phone'];
            $checkSmsData['sms']=$_POST['phonesms'];
            $checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
            $res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
            if($res['err']>0){
                return $res;
            }
        }

        $login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
        if($login_result['err']=='0'){
            $this->load->model('membervip/front/Member_model');
            $this->Member_model->check_user_info($inter_id,$open_id);
        }
        return $login_result;


    }

    //会员注册
    public function member_reg($data){
        $this->load->model ( 'distribute/Fans_model' );
        $inter_id = $data['inter_id'];
        $open_id = $data['openid'];
        if($inter_id == 'a472731996'){ //雅思特定制
            $fans = $this->Fans_model->get_fans_beloning($inter_id,$open_id);

            $SalesINFO = array();

            if(!empty($fans)){
                $hotel_id  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '';

                if($hotel_id){
                    $hotelInfo = $this->db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$inter_id' AND hotel_id= $hotel_id ")->row();

                    if(!empty($hotelInfo) && isset($hotelInfo->hotel_web_id) && ( $hotelInfo->hotel_web_id > 0) ){
                        $soap = new SoapClient('http://121.41.82.114:9026/IWideService.asmx?wsdl');
                        $start = microtime(true);
                        $SalesINFO = $soap->GetSellerListBySellerDepID(array('SellerDepID'=>($hotelInfo->hotel_web_id)));
                        $end = microtime(true);
                        $time = round( $end - $start , 6 );
                        $this->load->library ("MYLOG");
                        // 转换成数组
                        $SalesINFO = json_decode(json_encode($SalesINFO), true);
                        MYLOG::pms_access_record($inter_id,date("Y-m-d H:i:s"),$time,'GetSellerListBySellerDepID','',json_encode(array('SellerDepID'=>$hotel_id['hotel_web_id'])),json_encode($SalesINFO),"雅思特");

                        if(!empty($SalesINFO)){
                            $_POST['seller_id'] = $SalesINFO['GetSellerListBySellerDepIDResult'];
                            $_POST['hotel_id'] = $hotelInfo->hotel_web_id;
                        }
                    }
                }
            }

            if(empty($SalesINFO) ){
                $_POST['seller_id'] = 99;
                $_POST['hotel_id'] = 99;
            }

        }

        if($inter_id == 'a480304439'){ //优程定制

            $fans = $this->Fans_model->get_fans_beloning($inter_id,$open_id);
            if(!empty($fans)){
                $_POST['hotel_id']  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '';
            }
        }
        if($inter_id == 'a468919145'){ //恒大定制
            $fans = $this->Fans_model->get_fans_beloning($inter_id,$open_id);

            if(!empty($fans)){
                $hotel_id  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '';

                if($hotel_id){
                    $hotelInfo = $this->db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$inter_id' AND hotel_id= $hotel_id ")->row();
                    if(!empty($hotelInfo) && isset($hotelInfo->hotel_web_id) && ( $hotelInfo->hotel_web_id > 0) ){
                        $_POST['hotel_id']  = $hotelInfo->hotel_web_id;
                    }else{
                        $_POST['hotel_id']='G000001';
                    }
                }
            }
        }
        if($inter_id == 'a457946152'){ //隐居定制
            $fans = $this->Fans_model->get_fans_beloning($inter_id,$open_id);

            if(!empty($fans) && isset($fans->source)&&$fans->source>0){
                $_POST['cardSales']=$fans->source;
            }else{
                $_POST['cardSales']='0';
            }

            if(!empty($fans)){
                $hotel_id  = ($fans->hotel_id > 0 ) ? $fans->hotel_id : '';
                MYLOG::w($hotel_id.'&'.$inter_id.date('Y-M-d H:i:s',time()));
                if($hotel_id){
                    $hotelInfo = $this->db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$inter_id' AND hotel_id= $hotel_id ")->row();
                    MYLOG::w(json_encode($hotelInfo).date('Y-M-d H:i:s',time()));
                    if(!empty($hotelInfo) && isset($hotelInfo->hotel_web_id)){

                        $_POST['hotel_id']  = $hotelInfo->hotel_web_id;
                    }else{
                        $_POST['hotel_id']='0';
                    }
                }
            }
        }


        $post_login_url = PMS_PATH_URL."member/reg";
        $post_login_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$open_id,
            'data'=>$_POST,
        );

        //如果有验证码,验证
        $conf_url = PMS_PATH_URL."adminmember/getregconfig";
        $post_data =  array('inter_id'=>$inter_id);
        //请求注册配置
        $regconfig = $this->doCurlPostRequest($conf_url,$post_data);
        $regconfig = isset($regconfig['data'])?$regconfig['data']:array();
        if(isset($regconfig['phonesms']) && $regconfig['phonesms']['show']=='1' && $regconfig['phonesms']['check']=='1'){
            if(!isset($_POST['phonesms'])) {
                return json_encode(array('err'=>'40003','msg'=>'验证码不存在'));exit;
            }
            $checkSmsData = $post_login_data;
            $checkSmsData['data']['sms']=$_POST['phonesms'];
            $checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
            $checkSmsData['cellphone']=$checkSmsData['phone'];
            $checkSmsData['sms']=$_POST['phonesms'];
            $checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:0;
            $res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
            if($res['err']>0){
                return json_encode($res);exit;
            }
        }

        $login_result = $this->doCurlPostRequest( $post_login_url , $post_login_data );
        $is_package = false;
        if($login_result['err']=='0'){


            /*注册分销绩效*/
            $this->load->model('membervip/admin/Distribution_model');
            $rule_info = $this->Distribution_model->get_distribution_rule($inter_id,'reg','t');
            if($rule_info){
                /*判断是否有分销员信息*/
                $fan = $this->Fans_model->get_fans_beloning($this->inter_id,$this->openid);
                if(!empty($fan) && $fan->source > 1){
                    /*注册分销绩效*/
                    $this->load->model('membervip/admin/Distribution_model');
                    /*分销绩效记录写入*/
                    $dis_record = array(
                        'open_id' => $this->openid,
                        'type' =>  $rule_info['rule_type'],
                        'reward' => $rule_info['reward'],
                        'record_title'=> $rule_info['title'],
                        'sn'    => $login_result['data']['membership_number'],
                        'status' => 'f',
                    );
                    $this->load->model('distribute/Staff_model');
                    $sales = $this->Staff_model->get_my_base_info_saler($this->inter_id,$fan->source);
                    $dis_record['sales_id'] = $fan->source;
                    $dis_record['sales_name'] = $sales['name'];
                    $dis_record['hotel_name']  = $sales['hotel_name'];
                    $record_id = $this->Distribution_model->add_distribution_record($this->inter_id,$dis_record);
                    if(!$record_id){
                        MYLOG::w("Distribution Record Reg Insert :".json_encode($dis_record)." | Result Failed ",'distribution_record/failed');
                    }
                }/*end分销员判断*/
            }
            /*end注册分销绩效*/

            $this->load->model('membervip/front/Member_model');
            $this->Member_model->check_user_info($inter_id,$open_id);
            //获取优惠信息
            $post_card = array(
//                'token'=>$this->_token,
                'inter_id'=>$inter_id,
                'is_active'=>'t',
                'type'=>'reg',
            );
            $rule_info= $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_package_card_rule_info" , $post_card );
            $rule_infos = array();
            if(isset($rule_info['data']) && !empty($rule_info['data'])){
                $rule_infos = $rule_info['data'];
            }


            if(!empty($rule_infos) && is_array($rule_infos)){
                $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
                $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
                foreach ($rule_infos as $key => $item){
                    if( isset($item['is_package']) && $item['is_package']=='t'){
                        $package_data = array(
//                            'token'=>$this->_token,
                            'inter_id'=>$inter_id,
                            'openid'=>$open_id,
                            'uu_code'=>md5(uniqid()),
                            'package_id'=>$item['package_id'],
                            'card_rule_id'=>$item['card_rule_id'],
                            'number'=>$item['frequency']
                        );
                        $result = $this->doCurlPostRequest( $packge_url , $package_data );
                        if(isset($result['err']) && $result['err']=='0'){
                            $is_package = true;
                        }
                    }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                        $card_data = array(
//                            'token'=>$this->_token,
                            'inter_id'=>$inter_id,
                            'openid'=>$open_id,
                            'card_id'=>$item['card_id'],
                            'type'=>'reg'
                        );
                        $this->doCurlPostRequest( $card_url , $card_data );
                    }
                }
            }
        }
        if(is_array($login_result)) $login_result['is_package'] = 2;
        if(!empty($login_result) && is_array($login_result) && $is_package===true) $login_result['is_package'] = 1;
        return $login_result;



    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
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
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->_write_log($log_data,"Member Model" );
        return json_decode($res,true);
    }

    /**
     * 运行日志记录
     * @param String $content
     */
    protected function _write_log( $content,$type ) {
        if(is_array($content) || is_object($content)) $content = json_encode($content);
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'membervip'. DS. 'customize'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] $type '". $content. "' starting...";
        fwrite($fp, $content);
        fclose($fp);
    }
}