<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 君亭定制类，处理君亭定制业务
 *
 * @author Yang
 *        
 */
class Junting extends MY_Front_Member
{

    private $url = 'http://60.191.125.141:8070/OtaDataEngine.asmx?wsdl';

    private $soap;

    private $user = 'iwide';

    private $pwd = 'iwide';

    public function __construct()
    {
        parent::__construct();
        if ($this->inter_id != 'a480930558' && $this->inter_id != 'a483407432') {
            exit();
        }
        $this->soap = new SoapClient($this->url, array(
            'trace' => true
        ));
    }

    public function index()
    {
        $data['inter_id'] = $this->inter_id;
        $this->_template = 'junting';
        $this->template_show('member',$this->_template,'validate',$data);
    }

    /**
     * 验证方法，校验会员卡是否合法是否可用。
     */
    public function validate()
    {
        $card_num = $_POST['card_num'];
        if (empty($card_num)) {
            $msginfo['err'] = '40003';
            $msginfo['msg'] = '请输入会员卡号';
            echo json_encode($msginfo);
            exit();
        }
        if (mb_strlen($card_num) != 11) {
            $msginfo['err'] = '40003';
            $msginfo['msg'] = '会员卡号长度不合法';
            echo json_encode($msginfo);
            exit();
        }
        $validate_1 = substr($card_num, 0, 3);
        // 第一号段 规则：代表发卡渠道：
        // 其中（600）代表自媒体发卡源
        // （660）代表线下发卡源
        // （680）代表官网发卡源
        $validate_2 = substr($card_num, 3, 2);
        // 第二号段 规则: 代表会员卡种类：
        // 06 代表普卡
        // 08 代表金卡
        // $validate_3 = substr($card_num, 10);
        if (($validate_1 != '600' && $validate_1 != '660' && $validate_1 != '680') || $validate_2 != '08' || $card_num['10'] == 4) {
            // 序列号逢4不使用
            $msginfo['err'] = '40003';
            $msginfo['msg'] = '请输入正确的会员卡号';
            echo json_encode($msginfo);
            exit();
        }
        
        $pms_data = [
            'strCardNo' => $card_num,
            'strCardtype' => 2, // 如果第二号卡段为06，则是普通会员卡
            'strCannelId' => 'iwide',
            'strCannelPassWord' => 'iwide'
        ];
        
        $pms_validate = $this->soapRequest('GetCardCheck', $pms_data);
        if ($pms_validate['GetCardCheckResult'] == 3) {
            $post_center_url = PMS_PATH_URL . "member/center";
            $post_center_data = array(
                'inter_id' => $this->inter_id,
                'openid' => $this->openid
            );
            // 请求用户登录(默认)会员卡信息(注：第一次有可能返回的数据是空)
            $center_data = $this->doCurlPostRequest($post_center_url, $post_center_data);
            $member_data = $center_data['data'];
            if (! empty($member_data['membership_number']) && $member_data['member_mode'] == 2) {
                // 会员已经登陆，直接升级
                $pms_data = [
                    'strCardNo' => $member_data['membership_number'],
                    'strOldCardType' => str_replace(',', '', $member_data['lvl_pms_code']),
                    'strNewCardType' => 'VIP2',
                    'strPointsChange' => '0',
                    'strNewCardNo' => $card_num,
                    'strRemarks' => '绑定实体卡号升级',
                    'strCannelId' => $this->user,
                    'strCannelPassWord' => $this->pwd
                ];
                $lvl_up_res = $this->soapRequest('GetCardUp', $pms_data);
                if (! $lvl_up_res || $lvl_up_res['GetCardUpResult'] != 7) {
                    $msginfo['err'] = '40003';
                    $msginfo['msg'] = '当前会员卡升级失败';
                    echo json_encode($msginfo);
                    exit();
                } else {
                    $msginfo['err'] = '0';
                    $msginfo['url'] = 'center';
                    $msginfo['msg'] = '恭喜你已经成功升级为君亭四季金卡';
                   //强制刷新一次会员信息
                    $this->doCurlPostRequest($post_center_url, $post_center_data);
                    echo json_encode($msginfo);
                    exit();
                }
            } else {
                // 未登录，跳转到登陆页面
                $msginfo['err'] = '0';
                $msginfo['msg'] = '欢迎绑定你的四季会员卡';
                $msginfo['url'] = 'login?junting_card=' . $card_num;
                echo json_encode($msginfo);
                exit();
            }
        } elseif ($pms_validate['GetCardCheckResult'] == 0) {
            $msginfo['err'] = '40003';
            $msginfo['msg'] = '该卡号已经使用';
            echo json_encode($msginfo);
            exit();
        } else {
            $msginfo['err'] = '40003';
            $msginfo['msg'] = '请输入正确的会员卡号';
            echo json_encode($msginfo);
            exit();
        }
    }

    protected function soapRequest($func, $data)
    {
        $startTime = microtime(true);
        $res = $this->soap->$func($data);
        $end = microtime(true);
        // 保存信息方便排错
        $this->LastRequest = $this->soap->__getLastRequest();
        $this->LastResponse = $this->soap->__getLastResponse();
        $time = round($end - $startTime, 6);
        $arr = json_decode(json_encode($res), true);
        
        MYLOG::pms_access_record('a480930558', $startTime, $time, $func, $this->url, json_encode($data), json_encode($arr), "君亭");
        return $arr;
    }
}