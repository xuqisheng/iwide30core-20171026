<?php

use App\services\member\CardcenterService;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *    会员优惠券中心
 * @author  liwensong
 * @copyright www.iwide.cn
 * @version 4.0
 * @Email 171708252lux@gmail.com
 */
class Cardcenter extends MY_Front_Member
{
    private $initTime;
    private $endt;

    private $show_openid;
    private $show_inter_id;
    private $assign_data = array();
    private $url_group = array();
    private $org_domain = '';
    private $share_domain = '';
    private $segments = '';
    private $extra = array();
    protected $args = array();

    public function __construct()
    {
        $this->initTime = microtime(true);
        parent::__construct();
        $this->endt = microtime(true);
        $this->load->library("MYLOG");
        $this->load->helper('common_helper');
        $this->args = get_args();

        $sem = $this->input->get('f');
        if ($sem) $sem = base64_decode($sem);
        $segment_arr = explode('***', $sem);
        if (empty($segment_arr[0]) || empty($segment_arr[1]) || empty($segment_arr[2])) {
            die('参数不全');
//            redirect ( site_url ( '/distribute/dis_ext/perror' ) . '?id=' . $this->input->get ( 'id' ) );
        }
        $this->load->model('wx/Publics_model', 'publics_model');
        $this->load->helper('qfglobal');
        //验证签名
        $m_inter_id = $segment_arr[0];
        $m_open_id = $segment_arr[1];
        $decrypt = $segment_arr[2];
        $m_public = $this->publics_model->get_public_by_id($m_inter_id);
        $key = $m_public['app_secret'];
        $ec_data = $m_inter_id . $m_open_id;
        $encrypt = urlencode(kecrypt($ec_data, $key));
        if ($decrypt != $encrypt) {
            die('验证不通过');
        }

        $this->show_inter_id = $m_inter_id;
        $this->show_openid = $m_open_id;

        $this->load->model('distribute/openid_rel_model');
        $flag = $this->openid_rel_model->new_rel(array(
            'openid' => $this->show_openid,
            'inter_id' => $this->show_inter_id,
            'm_inter_id' => $this->session->userdata('inter_id'),
            'm_openid' => $this->session->userdata($this->session->userdata('inter_id') . 'openid')
        ));
        if ($flag) {
            $this->member_template($this->show_inter_id); //模板设置
            $this->template_filed_name_set($this->show_inter_id); //自定义名字
        } else {
            log_message('error', '公众号openid关联失败，FROM:' . $this->input->post('inter_id') . '-' . $this->input->post('openid') . ' TO:' . $this->input->post('inter_id'));
        }

        $ec_data = $this->show_inter_id . $this->show_openid;
        $encrypt = urlencode(kecrypt($ec_data, $key));
        $this->segments = base64_encode("{$this->show_inter_id}***{$this->show_openid}***{$encrypt}");
        $this->assign_data['segments'] = $this->segments;
        $this->assign_data['org_inter_id'] = $this->show_inter_id;
        $this->assign_data['share_inter_id'] = $this->inter_id;


        $this->assign_data['org_domain'] = '';
        $this->assign_data['share_domain'] = '';
        $_pattern = "/^((http:\/\/)|(https:\/\/))?([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}/"; //匹配网址域名
        preg_match_all($_pattern, $m_public['domain'], $match);
        if (!$match) {
            $this->assign_data['org_domain'] = $m_public['domain'];
            $this->org_domain = $m_public['domain'];
        } else {
            if (strpos($match[0][0], 'http://') !== false OR strpos($match[0][0], 'https://') !== false) {
                $public_host = $match[0][0];
            } else {
                $public_host = "http://{$match[0][0]}";
            }
            $this->assign_data['org_domain'] = $public_host;
            $this->org_domain = $public_host;
        }

        $public = $this->publics_model->get_public_by_id($this->inter_id);
        preg_match_all($_pattern, $public['domain'], $matchs);
        if (!$matchs) {
            $this->assign_data['share_domain'] = $public['domain'];
            $this->share_domain = $m_public['domain'];
        } else {
            if (strpos($matchs[0][0], 'http://') !== false OR strpos($matchs[0][0], 'https://') !== false) {
                $public_host = $matchs[0][0];
            } else {
                $public_host = "http://{$matchs[0][0]}";
            }
            $this->assign_data['share_domain'] = $public_host;
            $this->share_domain = $public_host;
        }

        //设置前端需要用到的URL
        $this->url_group['cardcenter_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['cardinfo_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/cardinfo?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['pcardinfo_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/pcardinfo?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['center_url'] = "{$this->assign_data['share_domain']}/membervip/center?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['qrcodecon_url'] = "{$this->assign_data['share_domain']}/membervip/center/qrcodecon?id={$this->inter_id}";
        $this->url_group['gift_card_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/gift_card?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['passwduseoff_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/passwduseoff?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['getpackage_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/getpackage?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['addcard_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/addcard?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['givecard_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/givecard?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['hang_card_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/hang_card?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['savegivecard_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/savegivecard?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['receive_card_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/receive_card?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['check_useoff_url'] = "{$this->assign_data['share_domain']}/membervip/cardcenter/check_useoff?id={$this->inter_id}&f={$this->segments}";

        $this->url_group['iapi']['cardcenter_url'] = "{$this->share_domain}/iapi/membervip/cardcenter?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['cardinfo_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/cardinfo?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['pcardinfo_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/pcardinfo?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['gift_card_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/gift_card?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['passwduseoff_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/passwduseoff?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['getpackage_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/getpackage?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['addcard_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/addcard?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['givecard_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/givecard?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['hang_card_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/hang_card?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['savegivecard_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/savegivecard?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['receive_card_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/receive_card?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['check_useoff_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/check_useoff?id={$this->inter_id}&f={$this->segments}";
        $this->url_group['iapi']['receive_url'] = "{$this->share_domain}/iapi/membervip/cardcenter/receive?id={$this->inter_id}&f={$this->segments}";
    }

    //会员卡券列表
    public function index()
    {
        $data['data'] = array();
        if (!$this->is_restful()) {
            $data = CardcenterService::getInstance()->index($this->inter_id, $this->openid, $this->inter_id, $this->_template, $this->url_group);
        } else {
            $data['data'] = $this->url_group;
        }
        $data['data']['page_title'] = '我的优惠券';
        $this->template_show('member', $this->_template, 'card', $data['data']);
    }

    //获取pms卡券列表-隐居定制
    public function pcard()
    {
        $data['data'] = array();
        if (!$this->is_restful()) {
            $data = CardcenterService::getInstance()->pcard($this->show_inter_id, $this->show_openid, $this->url_group);
        } else {
            $data['data'] = $this->url_group;
        }
        $data['data']['page_title'] = '我的优惠券';
        $this->template_show('member', $this->_template, 'card', $data['data']);
    }

    public function cardinfo()
    {
        $member_card_id = !empty($this->args['member_card_id']) ? $this->args['member_card_id'] : '';
        $extra = array(
            'share_domain' => $this->share_domain,
            'org_domain' => $this->org_domain,
            'segments' => $this->segments
        );
        $is_restful = $this->is_restful() ? false : true;
        $data = CardcenterService::getInstance()->cardinfo($this->show_inter_id, $this->show_openid, $this->inter_id, $member_card_id, $this->url_group, $extra, $is_restful);
        if ($data['status'] == '3' && $data['jump'] == '1') {
            redirect($data['redirect_uri']);
        }
        $data['data']['page_title'] = '优惠券详情';
        $this->template_show('member', $this->_template, 'cardinfo', $data['data']);
    }

    //加密链接参数
    public function encrypt($inter_id, $open_id)
    {
        $this->load->model('wx/Publics_model', 'publics_model');

        //加密参数
        $this->load->helper('qfglobal');
        $public = $this->publics_model->get_public_by_id($this->inter_id);
        $key = $public['app_secret'];
        $ec_data = $inter_id . $open_id;
        $sign = kecrypt($ec_data, $key);
        $this->load->model('wx/Publics_model', 'publics_model');
        return base64_encode($this->inter_id . '***' . $this->openid . '***' . $sign);
    }

    /**
     * 获取微信JSSDK配置信息
     * @param $inter_id
     * @param string $url
     * @return array
     */
    protected function _get_sign_package($inter_id, $url = '')
    {
        $this->load->helper('common');
        $this->load->model('wx/publics_model', 'publics');
        $this->load->model('wx/access_token_model');
        $jsapiTicket = $this->access_token_model->get_api_ticket($inter_id);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if (!$url)
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();
        $public = $this->publics->get_public_by_id($inter_id);

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId" => $public['app_id'],
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    //转赠优惠券挂起
    public function gift_card()
    {
        $data = array();
        if (!$this->is_restful()) {
            $member_card_id = !empty($this->args['mcid']) ? floatval($this->args['mcid']) : '';
            $module = !empty($this->args['module']) ? $this->args['module'] : '';
            $card_code = !empty($this->args['card_code']) ? trim($this->args['card_code']) : '';
            $data = CardcenterService::getInstance()->gift_card($this->show_inter_id, $this->show_openid, $member_card_id, $module, $card_code);
        }
        echo json_encode($data);
    }

    /**
     *    消费码核销
     *
     */
    public function passwduseoff()
    {
        $data = array();
        if (!$this->is_restful()) {
            $member_card_id = !empty($this->args['member_card_id']) ? floatval($this->args['member_card_id']) : '';
            $passwd = !empty($this->args['passwd']) ? $this->args['passwd'] : '';
            $data = CardcenterService::getInstance()->passwduseoff($this->show_inter_id, $this->show_openid, $member_card_id, $passwd);
        }
        echo json_encode($data);
    }

    public function pcardinfo()
    {
        $data['data'] = array(
            'page_title' => '优惠券详情'
        );
        if (!$this->is_restful()) {
            $member_card_id = !empty($this->args['member_card_id']) ? $this->args['member_card_id'] : '';
            $data = CardcenterService::getInstance()->pcardinfo($this->show_inter_id, $this->show_openid, $member_card_id, $this->url_group);
        }
        $this->template_show('member', $this->_template, 'pcardinfo', $data['data']);
    }

    //领取卡券
    public function addcard()
    {
        $data = array();
        if (!$this->is_restful()) {
            $card_rule_id = !empty($this->args['card_rule_id']) ? $this->args['card_rule_id'] : 0;
            $data = CardcenterService::getInstance()->addcard($this->show_inter_id, $this->show_openid, $card_rule_id);
        }
        echo json_encode($data);
    }


    public function getpackage()
    {
        $data = array();
        if (!$this->is_restful()) {
            $package_id = isset($this->args['package_id']) ? (int)$this->args['package_id'] : 0;
            $frequency = isset($this->args['frequency']) ? (int)$this->args['frequency'] : 0;
            $card_rule_id = isset($this->args['card_rule_id']) ? intval($this->args['card_rule_id']) : 0;
            $data = CardcenterService::getInstance()->getpackage($this->show_inter_id, $this->show_openid, $package_id, $frequency, $card_rule_id);
        }
        echo json_encode($data);
    }

    //卡券转赠页面
    public function givecard()
    {
        if (!empty($this->assign_data['org_domain'])) {
            redirect("{$this->assign_data['org_domain']}/upgrade_page?id={$this->show_inter_id}");
        } else {
            redirect(site_url('./upgrade_page') . '?id=' . $this->show_inter_id);
        }
        $this->load->model('wx/Publics_model');
        $data['info'] = $this->Publics_model->get_fans_info($this->show_openid);
//	    $this->check_user_login();
        //获取卡券的详细
        $card_openid = isset($_GET['cardOpenid']) ? $_GET['cardOpenid'] : $this->show_openid;
        $member_card_id = isset($_GET['member_card_id']) ? (int)$_GET['member_card_id'] : 0;
        $post_card_info_data = array(
            'token' => $this->_token,
            'inter_id' => $this->show_inter_id,
            'openid' => $card_openid,
            'member_card_id' => $member_card_id,
        );
        $card_info = $this->doCurlPostRequest(INTER_PATH_URL . "membercard/getinfo", $post_card_info_data);
        if (isset($card_info['data'])) {
            $data['card_info'] = $card_info['data'];
        } else {
            $data['card_info'] = array();
        }
        $data['card_openid'] = $card_openid;
        $data['openid'] = $this->show_openid;
        $data['inter_id'] = $this->show_inter_id;
        $this->load->model('wx/access_token_model');
        $this->load->model('wx/Publics_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->show_inter_id);
        $data['public'] = $this->Publics_model->get_public_by_id($this->show_inter_id);
        $this->load->model('wx/access_token_model');
        $data['signpackage'] = $this->access_token_model->getSignPackage($this->show_inter_id);
        $this->template_show('member', $this->_template, 'givecard', $data);
    }

    //转赠卡券挂起
    public function hang_card()
    {
        $data = array();
        if (!$this->is_restful()) {
            $member_card_id = !empty($this->args['card_id']) ? $this->args['card_id'] : 0;
            $data = CardcenterService::getInstance()->hang_card($this->show_inter_id, $this->show_openid, $member_card_id);
        }
        echo json_encode($data);
    }

    //保存卡券转赠信息
    public function savegivecard()
    {
        $data = array();
        if (!$this->is_restful()) {
            $from_openid = isset($this->args['cardOpenid']) ? $this->args['cardOpenid'] : '';
            $card_id = isset($this->args['card_id']) ? $this->args['card_id'] : '';
            $cardModule = 'vip';
            $data = CardcenterService::getInstance()->savegivecard($this->show_inter_id, $this->show_openid, $from_openid, $card_id, $cardModule);
        }
        echo json_encode($data);
    }


    public function before_receive()
    {
        $gift_encrypt = $this->input->get('sf');
        $public = $this->publics_model->get_public_by_id($this->show_inter_id);
        $_pattern = "/^((http:\/\/)|(https:\/\/))?([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}/"; //匹配网址域名
        preg_match_all($_pattern, $public['domain'], $match);
        if (!$match) {
            $org_domain = $public['domain'];
        } else {
            if (strpos($match[0][0], 'http://') !== false OR strpos($match[0][0], 'https://') !== false) {
                $public_host = $match[0][0];
            } else {
                $public_host = "http://{$match[0][0]}";
            }
            $org_domain = $public_host;
        }
        $share_url = "{$org_domain}/membervip/card/receive?id={$this->show_inter_id}&share_interid={$this->inter_id}&sf={$gift_encrypt}";
        redirect($share_url);
    }

    //卡券详细页面
    public function receive()
    {
        $ec_code = !empty($this->args['sf']) ? trim($this->args['sf']) : '';
        $extra = array(
            'org_domain' => $this->org_domain
        );
        $is_restful = $this->is_restful() ? false : true;
        $data = CardcenterService::getInstance()->receive($this->show_inter_id, $this->show_openid, $ec_code, $this->url_group, $extra, $is_restful);
        if ($data['status'] == '3' && $data['jump'] == '1') {
            redirect($data['redirect_uri']);
        }

        $data['data']['page_title'] = '领取优惠券';
        $data['data']['sf'] = $ec_code;
        if (!empty($data['data']['iapi']['receive_url'])) $data['data']['iapi']['receive_url'] .= '&sf=' . $ec_code;
        $this->template_show('member', $this->_template, 'receive', $data['data']);
    }


    //转赠优惠券挂起
    public function receive_card()
    {
        $data = array();
        if (!$this->is_restful()) {
            $ec_code = !empty($this->args['ec_code']) ? trim($this->args['ec_code']) : 0;
            $data = CardcenterService::getInstance()->receive_card($this->show_inter_id, $this->show_openid, $ec_code, $this->url_group);
        }
        echo json_encode($data);
    }

    //通过券码检测优惠券是否已经使用和核销
    public function check_useoff()
    {
        if (is_ajax_request()) {
            $this->load->model('membervip/common/Public_model', 'common_model');
            $coupon_code = $this->input->post("coupon_code");

            $this->load->model('membervip/front/Member_model', 'mem');
            $user = $this->mem->get_user_info($this->show_inter_id, $this->show_openid, 'member_info_id');

            $where = array(
                'open_id' => $this->show_openid,
                'inter_id' => $this->show_inter_id,
                'coupon_code' => $coupon_code,
                'member_info_id' => !empty($user['member_info_id']) ? $user['member_info_id'] : 0
            );

            $member_card = $this->common_model->get_info($where, 'member_card', 'is_use,is_useoff');
            if (!empty($member_card) && $member_card['is_use'] == 't' && $member_card['is_useoff'] == 't') {
                $this->_ajaxReturn('使用核销成功!', $this->assign_data['cardcenter_url'], 1);
            }
            $this->_ajaxReturn('使用核销失败!');
        }
        $this->_ajaxReturn('很抱歉，请求失败，请联系管理员');
    }
}

?>