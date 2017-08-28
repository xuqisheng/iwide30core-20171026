<?php

namespace App\services\member;

use App\services\MemberBaseService;

/**
 * Class RegService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class RegService extends MemberBaseService
{

    /**
     * 获取服务实例方法
     * @return RegService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     * 检查是否为已登录的会员卡
     * @param string $inter_id 酒店集团ID
     * @param string $openid 微信用户ID
     * @return bool|integer
     */
    public function check_member_card_ogin($inter_id = '', $openid = '')
    {
        $this->getCI()->load->model('membervip/front/Member_model', 'member_model');
        $userinfo = $this->getCI()->member_model->get_user_info($inter_id, $openid, 'member_info_id,member_mode,is_login');
        if (empty($userinfo) OR $userinfo['member_mode'] == 1 OR $userinfo['is_login'] == 'f') return false;
        return $userinfo['member_info_id'];
    }

    //注册页面
    public function index($inter_id)
    {
        $post_config_url = PMS_PATH_URL . "adminmember/getregconfig";
        $post_config_data = array(
            'inter_id' => $inter_id,
        );
        $this->getCI()->load->model('wx/publics_model', 'publics');
        $public = $this->getCI()->publics->get_public_by_id($inter_id);
        $data['public'] = $public;
        //请求注册配置
        $data['login_config'] = $this->doCurlPostRequest($post_config_url, $post_config_data)['data'];
        $data['inter_id'] = $inter_id;
        $data['sales_id'] = $this->getCI()->input->get('salesId') ? intval($this->getCI()->input->get('salesId')) : 0;
        if ($this->getCI()->input->get('redir')) {
            $data['succ_url'] = urldecode($this->getCI()->input->get('redir'));
            $data['redir'] = urlencode($this->getCI()->input->get('redir'));
        } else {
            $data['succ_url'] = site_url('membervip/center') . '?id=' . $inter_id;
            $data['redir'] = '';
        }

        return $data;

    }

    /**
     * 2016-07-20
     * @author knight
     * 变更领取卡劵和礼包的方式,改为接口请求
     * 注册登录
     */
    public function savereg($inter_id, $openid, $_token)
    {
        $this->getCI()->session->unset_tempdata($inter_id . 'vip_user');
        //验证图片验证码
        if (isset($_POST['smspic'])) {
            if ($_SESSION['code'] != $_POST['smspic']) {
                return array('err' => '40003', 'msg' => '图片验证码错误');
            }
        }
        $this->getCI()->load->model('distribute/Fans_model');
        if ($inter_id == 'a472731996') { //雅思特定制
            $fans = $this->getCI()->Fans_model->get_fans_beloning($inter_id, $openid);

            $SalesINFO = array();

            if (!empty($fans)) {
                $hotel_id = ($fans->hotel_id > 0) ? $fans->hotel_id : '';

                if ($hotel_id) {
                    $db = $this->getCI()->load->database('iwide_r1', true);
                    $hotelInfo = $db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$inter_id' AND hotel_id= $hotel_id ")->row();

                    if (!empty($hotelInfo) && isset($hotelInfo->hotel_web_id) && ($hotelInfo->hotel_web_id > 0)) {
                        $soap = new \SoapClient('http://121.41.82.114:9026/IWideService.asmx?wsdl');
                        $start = microtime(true);
                        $SalesINFO = $soap->GetSellerListBySellerDepID(array('SellerDepID' => ($hotelInfo->hotel_web_id)));
                        $end = microtime(true);
                        $time = round($end - $start, 6);
                        // 转换成数组
                        $SalesINFO = json_decode(json_encode($SalesINFO), true);
                        \MYLOG::pms_access_record('a472731996', date("Y-m-d H:i:s"), $time, 'GetSellerListBySellerDepID', '', json_encode(array('SellerDepID' => $hotel_id['hotel_web_id'])), json_encode($SalesINFO), "雅思特");

                        if (!empty($SalesINFO)) {
                            $_POST['seller_id'] = $SalesINFO['GetSellerListBySellerDepIDResult'];
                            $_POST['hotel_id'] = $hotelInfo->hotel_web_id;
                        }
                    }
                }
            }

            if (empty($SalesINFO)) {
                $_POST['seller_id'] = 99;
                $_POST['hotel_id'] = 99;
            }

        }

        if ($inter_id == 'a480304439') { //优程定制
            $fans = $this->getCI()->Fans_model->get_fans_beloning($inter_id, $openid);
            if (!empty($fans)) {
                $_POST['hotel_id'] = ($fans->hotel_id > 0) ? $fans->hotel_id : '';
            }
        }
        if ($inter_id == 'a468919145') { //恒大定制
            $fans = $this->getCI()->Fans_model->get_fans_beloning($inter_id, $openid);

            $SalesINFO = array();

            if (!empty($fans)) {
                $hotel_id = ($fans->hotel_id > 0) ? $fans->hotel_id : '';
                \MYLOG::w($hotel_id . '&' . $inter_id . date('Y-M-d H:i:s', time()));
                if ($hotel_id) {
                    $db = $this->getCI()->load->database('iwide_r1', true);
                    $hotelInfo = $db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$inter_id' AND hotel_id= $hotel_id ")->row();
                    \MYLOG::w(json_encode($hotelInfo) . date('Y-M-d H:i:s', time()));
                    if (!empty($hotelInfo) && isset($hotelInfo->hotel_web_id)) {
                        $_POST['hotel_id'] = $hotelInfo->hotel_web_id;
                    } else {
                        $_POST['hotel_id'] = 'G000001';
                    }
                }
            }
        }
        if ($inter_id == 'a457946152' || $inter_id == 'a484619482') { //隐居定制
            $fans = $this->getCI()->Fans_model->get_fans_beloning($inter_id, $openid);

            if (!empty($fans) && isset($fans->source) && $fans->source > 0) {
                $_POST['cardSales'] = $fans->source;
            } else {
                $_POST['cardSales'] = '0';
            }

            if (!empty($fans)) {
                $hotel_id = ($fans->hotel_id > 0) ? $fans->hotel_id : '';
                \MYLOG::w($hotel_id . '&' . $inter_id . date('Y-M-d H:i:s', time()));
                if ($hotel_id) {
                    $db = $this->getCI()->load->database('iwide_r1', true);
                    $hotelInfo = $db->query("SELECT * FROM `iwide_hotel_additions` WHERE inter_id='$inter_id' AND hotel_id= $hotel_id ")->row();
                    \MYLOG::w(json_encode($hotelInfo) . date('Y-M-d H:i:s', time()));
                    if (!empty($hotelInfo) && isset($hotelInfo->hotel_web_id)) {

                        $_POST['hotel_id'] = $hotelInfo->hotel_web_id;
                    } else {
                        $_POST['hotel_id'] = '0';
                    }
                }
            }
        }


        $post_login_url = PMS_PATH_URL . "member/reg";
        $post_login_data = array(
            'inter_id' => $inter_id,
            'openid' => $openid,
            'data' => $_POST,
        );

        //如果有验证码,验证
        $conf_url = PMS_PATH_URL . "adminmember/getregconfig";
        $post_data = array('inter_id' => $inter_id);
        //请求注册配置
        $regconfig = $this->doCurlPostRequest($conf_url, $post_data);
        $regconfig = isset($regconfig['data']) ? $regconfig['data'] : array();
        if (isset($regconfig['phonesms']) && $regconfig['phonesms']['show'] == '1' && $regconfig['phonesms']['check'] == '1') {
            if (!isset($_POST['phonesms'])) {
                return array('err' => '40003', 'msg' => '验证码不存在');
            }
            $checkSmsData = $post_login_data;
            $checkSmsData['data']['sms'] = $_POST['phonesms'];
            $checkSmsData['phone'] = isset($_POST['phone']) ? $_POST['phone'] : 0;
            $checkSmsData['cellphone'] = $checkSmsData['phone'];
            $checkSmsData['sms'] = $_POST['phonesms'];
            $checkSmsData['smstype'] = isset($_POST['smstype']) ? $_POST['smstype'] : 0;
            $res = $this->doCurlPostRequest(PMS_PATH_URL . "member/checksms", $checkSmsData);
            if ($res['err'] > 0) {
                return $res;

            }
        }

        $login_result = $this->doCurlPostRequest($post_login_url, $post_login_data);
        $is_package = false;
        if ($login_result['err'] == '0') {
            /* 注册发送模板消息-添加模板消息队列-start */
            $this->getCI()->load->model('membervip/common/Wxtemp_model', 'wxtemp');
            $this->getCI()->load->model('membervip/common/Public_model', '_public');
            $param = array(
                'inter_id' => $inter_id,
                'openid' => $openid,
                'business_model' => 4,
                'message_type' => 12
            );

            $member_lvl = $this->getCI()->_public->get_field_by_level_config($inter_id);

            $membership_number = !empty($login_result['data']['membership_number']) ? $login_result['data']['membership_number'] : '';
            $nickname = !empty($login_result['data']['nickname']) ? $login_result['data']['nickname'] : '';
            $name = !empty($login_result['data']['name']) ? $login_result['data']['name'] : $nickname;
            $telephone = !empty($login_result['data']['telephone']) ? $login_result['data']['telephone'] : '';
            $member_lvl_id = !empty($login_result['data']['member_lvl_id']) ? $login_result['data']['member_lvl_id'] : 0;
            $lvl_info = !empty($member_lvl[$member_lvl_id]) ? $member_lvl[$member_lvl_id] : '';
            $content = "{$membership_number}|{$name}|{$telephone}|{$lvl_info}";
            $_wxtemp = $this->getCI()->wxtemp->create_message_queue($param, $content);
            \MYLOG::w(@json_encode(array($_wxtemp, $param, $content)), 'membervip/debug-log');
            /* 注册发送模板消息-添加模板消息队列-end */

            /*注册分销绩效*/
            $this->getCI()->session->set_userdata($inter_id . $openid . '_logined', 1);
            $this->reg_sales_excute($inter_id, $openid, $login_result);
            /*end注册分销绩效*/


            $this->getCI()->load->model('membervip/front/Member_model');
            $this->getCI()->Member_model->check_user_info($inter_id, $openid);
            //获取优惠信息
            $post_card = array(
                'token' => $_token,
                'inter_id' => $inter_id,
                'is_active' => 't',
                'type' => 'reg',
            );
            $rule_info = $this->doCurlPostRequest(PMS_PATH_URL . "cardrule/get_package_card_rule_info", $post_card);
            $rule_infos = array();
            if (isset($rule_info['data']) && !empty($rule_info['data'])) {
                $rule_infos = $rule_info['data'];
            }


            if (!empty($rule_infos) && is_array($rule_infos)) {
                $packge_url = INTER_PATH_URL . 'package/give'; //领取礼包
                $card_url = PMS_PATH_URL . 'cardrule/reg_gain_card'; //领取卡劵
                foreach ($rule_infos as $key => $item) {
                    if (isset($item['is_package']) && $item['is_package'] == 't') {
                        $package_data = array(
                            'token' => $_token,
                            'inter_id' => $inter_id,
                            'openid' => $openid,
                            'uu_code' => md5(uniqid()),
                            'package_id' => $item['package_id'],
                            'card_rule_id' => $item['card_rule_id'],
                            'number' => $item['frequency']
                        );
                        $result = $this->doCurlPostRequest($packge_url, $package_data);
                        if (isset($result['err']) && $result['err'] == '0') {
                            $is_package = true;
                        }
                    } elseif (isset($item['is_package']) && $item['is_package'] == 'f') {
                        $card_data = array(
                            'token' => $_token,
                            'inter_id' => $inter_id,
                            'openid' => $openid,
                            'card_id' => $item['card_id'],
                            'type' => 'reg'
                        );
                        $this->doCurlPostRequest($card_url, $card_data);
                    }
                }
            }
        }
        if (is_array($login_result)) $login_result['is_package'] = 2;
        if (!empty($login_result) && is_array($login_result) && $is_package === true) $login_result['is_package'] = 1;
        return $login_result;
    }


    public function reg_sales_excute($inter_id = '', $openid = '', $login_result = array())
    {
        if (empty($inter_id) OR empty($openid) OR empty($login_result) OR is_string($login_result) OR $login_result['err'] > 0) return false;
        $this->getCI()->load->model('membervip/admin/Distribution_model');
        $rule_info = $this->getCI()->Distribution_model->get_distribution_rule($inter_id, 'reg', 't');
        if ($rule_info) {
            /*注册分销绩效*/
            $saler_id = 0;
            switch ($rule_info['belonging']) {
                case 1://粉丝归属
                    $fan = $this->getCI()->Fans_model->get_fans_beloning($inter_id, $openid);
                    if (!empty($fan) && $fan->source > 0) {
                        $saler_id = $fan->source;
                    }
                    break;
                case 2://归属于链接分销号
                    $saler_id = intval($this->getCI()->input->post('salesId'));
                    break;
                case 3://优先归属于链接分销号
                    $saler_id = intval($this->getCI()->input->post('salesId'));
                    if (!$saler_id) {
                        $fan = $this->getCI()->Fans_model->get_fans_beloning($inter_id, $openid);
                        if (!empty($fan) && $fan->source > 0) {
                            $saler_id = $fan->source;
                        }
                    }
                    break;
                default:
                    break;
            }

            $saler_id = \App\services\member\SupportService::getInstance()->check_set_saler($inter_id, $openid, $saler_id, 'reg'); //分销保护

            if ($saler_id) {
                $dis_record = array(
                    'open_id' => $openid,
                    'type' => $rule_info['rule_type'],
                    'reward' => $rule_info['reward'],
                    'record_title' => $rule_info['title'],
                    'sn' => $login_result['data']['membership_number'],
                    'status' => 'f',
                );
                $this->getCI()->load->model('distribute/Staff_model');
                $sales = $this->getCI()->Staff_model->get_my_base_info_saler($inter_id, $saler_id);
                if ($sales) {
                    $dis_record['sales_id'] = $sales['qrcode_id'];
                    $dis_record['sales_name'] = $sales['name'];
                    $dis_record['hotel_name'] = $sales['hotel_name'];
                    /*分销绩效记录写入*/
                    $record_id = $this->getCI()->Distribution_model->add_distribution_record($inter_id, $dis_record);
                    if (!$record_id) {
                        \MYLOG::w("Distribution Record Reg Insert :" . json_encode($dis_record) . '|' . json_encode($sales) . '|' . $inter_id . " | Result Failed ", 'distribution_record/failed');
                    }
                } else {
                    \MYLOG::w("Staff not found :" . json_encode($dis_record) . '|' . $inter_id . '|' . $saler_id . " | Staff Failed ", 'distribution_record/failed');
                }
            }
        }
    }

    public function send_tmp_msg($inter_id, $openid)
    {
        $retrun['code'] = '501';
        $retrun['msg'] = '参数不全';
        $get = $_GET;
        //碧桂园注册送礼包
        if ($inter_id == 'a421641095') {
            $this->getCI()->load->model('membervip/front/Kiminvited_model');
            $subdata['name'] = isset($get['name']) ? $get['name'] : '微信用户';
            $subdata['count'] = 1;
            $subdata['curtime'] = time();
            $retrun = $this->getCI()->Kiminvited_model->_send_tmp($inter_id, $openid, $subdata, 5);
            $this->getCI()->Kiminvited_model->_write_log($retrun, '_send_tmp');
        }
        return $retrun;
    }

    public function pic_code()
    {
        //生成验证码图片
        $im = imagecreate(60, 20); // 画一张指定宽高的图片
        $back = ImageColorAllocate($im, 245, 245, 245); // 定义背景颜色
        imagefill($im, 0, 0, $back); //把背景颜色填充到刚刚画出来的图片中
        $vcodes = "";
        srand((double)microtime() * 1000000);
        //生成4位数字
        for ($i = 0; $i < 4; $i++) {
            $font = ImageColorAllocate($im, rand(100, 255), rand(0, 100), rand(100, 255)); // 生成随机颜色
            $authnum = rand(1, 9);
            $vcodes .= $authnum;
            imagestring($im, 5, 2 + $i * 10, 1, $authnum, $font);
        }
        $_SESSION['code'] = $vcodes;

        for ($i = 0; $i < 100; $i++) //加入干扰象素
        {
            $randcolor = ImageColorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($im, rand() % 70, rand() % 30, $randcolor); // 画像素点函数
        }
        ob_clean();
        Header("Content-type: image/PNG");
        ImagePNG($im);
        ImageDestroy($im);
    }

    //会员卡激活页面
    public function activate($inter_id)
    {
        /*会员卡验证*/
        $post_config_url = PMS_PATH_URL . "adminmember/getloginconfig";
        $post_config_data = array(
            'inter_id' => $inter_id,
        );
        //请求注册配置
        $data['login_config'] = $this->doCurlPostRequest($post_config_url, $post_config_data)['data'];
        $data['inter_id'] = $inter_id;
        $data['card_code'] = (isset($_GET['encrypt_code'])) ? $_GET['encrypt_code'] : '';
        $data['card_id'] = (isset($_GET['card_id'])) ? $_GET['card_id'] : '';
        return $data;
    }

    //激活会员卡保存
    public function do_activate($inter_id, $openid, $_token)
    {
        $this->getCI()->session->unset_tempdata($inter_id . 'vip_user');

        $post_login_url = PMS_PATH_URL . "member/activatecard";
        $post_login_data = array(
            'inter_id' => $inter_id,
            'openid' => $openid,
            'data' => $_POST,
        );

        //如果有验证码,验证
        $conf_url = PMS_PATH_URL . "adminmember/getregconfig";
        $post_data = array('inter_id' => $inter_id);
        //请求注册配置
        $regconfig = $this->doCurlPostRequest($conf_url, $post_data);
        $regconfig = isset($regconfig['data']) ? $regconfig['data'] : array();
//        if(isset($regconfig['phonesms']) && $regconfig['phonesms']['show']=='1' && $regconfig['phonesms']['check']=='1'){
//            if(!isset($_POST['phonesms'])) {
//                echo json_encode(array('err'=>'40003','msg'=>'验证码不存在'));exit;
//            }
//            $checkSmsData = $post_login_data;
//            $checkSmsData['data']['sms']=$_POST['phonesms'];
//            $checkSmsData['phone']=isset($_POST['phone'])?$_POST['phone']:0;
//            $checkSmsData['cellphone']=$checkSmsData['phone'];
//            $checkSmsData['sms']=$_POST['phonesms'];
//            $checkSmsData['smstype'] = isset($_POST['smstype'])?$_POST['smstype']:2;
//            $res = $this->doCurlPostRequest(PMS_PATH_URL."member/checksms",$checkSmsData);
//            if($res['err']>0){
//                echo json_encode($res);exit;
//            }
//        }

        $login_result = $this->doCurlPostRequest($post_login_url, $post_login_data);
        $is_package = false;
        $this->getCI()->load->model('membervip/front/Member_model');
        $user_membercard_info = $this->getCI()->Member_model->check_user_info($inter_id, $openid);
        \MYLOG::w("get_member_info" . json_encode($user_membercard_info) . " | Result " . json_encode($login_result), 'activate_wechat_card');
        //发礼包
        if ($login_result['err'] == '0' && isset($login_result['flag']) && $login_result['flag'] == 'register') {

            //获取优惠信息
            $post_card = array(
                'token' => $_token,
                'inter_id' => $inter_id,
                'is_active' => 't',
                'type' => 'reg',
            );
            $rule_info = $this->doCurlPostRequest(PMS_PATH_URL . "cardrule/get_package_card_rule_info", $post_card);
            $rule_infos = array();
            if (isset($rule_info['data']) && !empty($rule_info['data'])) {
                $rule_infos = $rule_info['data'];
            }


            if (!empty($rule_infos) && is_array($rule_infos)) {
                $packge_url = INTER_PATH_URL . 'package/give'; //领取礼包
                $card_url = PMS_PATH_URL . 'cardrule/reg_gain_card'; //领取卡劵
                foreach ($rule_infos as $key => $item) {
                    if (isset($item['is_package']) && $item['is_package'] == 't') {
                        $package_data = array(
                            'token' => $_token,
                            'inter_id' => $inter_id,
                            'openid' => $openid,
                            'uu_code' => md5(uniqid()),
                            'package_id' => $item['package_id'],
                            'card_rule_id' => $item['card_rule_id'],
                            'number' => $item['frequency']
                        );
                        $result = $this->doCurlPostRequest($packge_url, $package_data);
                        if (isset($result['err']) && $result['err'] == '0') {
                            $is_package = true;
                        }
                    } elseif (isset($item['is_package']) && $item['is_package'] == 'f') {
                        $card_data = array(
                            'token' => $_token,
                            'inter_id' => $inter_id,
                            'openid' => $openid,
                            'card_id' => $item['card_id'],
                            'type' => 'reg'
                        );
                        $this->doCurlPostRequest($card_url, $card_data);
                    }
                }
            }
        }

        //登录或者注册后，激活卡
        if ($login_result['err'] == '0') {
            $this->getCI()->load->model('member/Wxcard');


            $code = $_POST['card_code'];
            $card_id = $_POST['card_id'];


            $this->getCI()->Member_model->update_wechat_card_code($openid, $inter_id, $code, $card_id);

            $params['init_bonus'] = $user_membercard_info['credit'];
            $params['membership_number'] = $user_membercard_info['membership_number'];
            $params['card_id'] = $card_id; //暂时写死
            $params['code'] = $this->getCI()->Wxcard->code_decrypt($inter_id, $code);
            $params['init_custom_field_value1'] = $this->getCI()->Member_model->get_member_level_name($inter_id, $user_membercard_info['member_lvl_id']);
            //$params['init_custom_field_value2'] = 1;

            //激活卡
            $wechat_activate_res = $this->getCI()->Wxcard->do_active($inter_id, $params);

            $reffer_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            \MYLOG::w("params : " . json_encode($params) . " | Wechat Card Result " . json_encode($wechat_activate_res) . " | refer URL :" . $reffer_url, 'activate_wechat_card');

            if (isset($wechat_activate_res['errcode']) && $wechat_activate_res['errcode'] == 0) {
                $login_result['err'] = 0;
            } else {
                $login_result['err'] = 40003;
                $login_result['msg'] = '激活微信卡券失败';
            }

        }

        if (is_array($login_result)) $login_result['is_package'] = 2;
        if (!empty($login_result) && is_array($login_result) && $is_package === true) $login_result['is_package'] = 1;
        return $login_result;
    }
}