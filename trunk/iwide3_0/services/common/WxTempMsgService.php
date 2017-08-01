<?php

namespace App\services\common;
use App\libraries\Http;
use App\libraries\Support\Log;
use App\services\BaseService;


/**
 * Class WxTempMsgService
 * @package App\services\common
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class WxTempMsgService extends BaseService
{

    /**
     * 一次性订阅消息, 引导用户在微信客户端打开如下链接
     *
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1500374289_66bvB
     *
     */
    const GUIDE_URL = "https://mp.weixin.qq.com/mp/subscribemsg?action=%s&appid=%s&scene=%s&template_id=%s&redirect_url=%s&reserved=%s#wechat_redirect";

    /**
     * 推送订阅模板消息给到授权微信用户的API
     */
    const SEND_API = 'https://api.weixin.qq.com/cgi-bin/message/template/subscribe';

    /**
     *
     */
    const RESERVED_KEY = 'COMMON:GUIDE_URL';

    /**
     * @return WxTempMsgService
     * @author renshuai  <renshuai@jperation.cn>
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     *
     * @param string $interID
     * @param int $scene 0-10000的整形值
     * @param string $url
     * @param $reserved
     * @param string $action
     * @return string
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function getGuideUrl($interID, $scene, $url, $reserved, $action = 'get_confirm')
    {
        $this->getCI()->load->model('wx/Publics_model');
        $public = $this->getCI()->Publics_model->get_public_by_id($interID); // todo public info里加入一次性消息模版ID

        $appID = $public['app_id'];
        $templateID = 'ViwYokCgkwdfmB4kgnw8rk5bXZv9hpJWvbMm5Epk7rg';
        $redirectUrl = urlencode($url);

        $this->getCI()->session->set_tempdata(self::RESERVED_KEY, $reserved, 1200);

        Log::debug('getGuideUrl reserved', $this->getCI()->session->tempdata(self::RESERVED_KEY));

        return sprintf(self::GUIDE_URL, $action, $appID, $scene, $templateID, $redirectUrl, $reserved);
    }

    /**
     * @param $interID
     * @param $openid
     * @param $templateID
     * @param $scene
     * @return array|mixed
     * @throws \Exception
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function sendMsg($interID, $openid, $templateID, $scene)
    {
        $this->getCI()->load->model('wx/access_token_model');

        //todo 垃圾获取access token 机制
        $row = $this->getCI()->access_token_model->get_access_token($interID, true);
        Log::debug('sendMsg ', $row);
        $access_token = $row['access_token'];

        $client = new Http();
        $requestData = [
            'touser' => $openid,
            'template_id' => $templateID,
            'scene' => $scene,
        ];

        $requestData = array_merge($requestData, $this->_getDataOfScene($scene));

        Log::debug('sendMsg 1', $requestData);

        //todo http 层加入access token错误重试机制
        $result = $client->json(self::SEND_API, $requestData, 256, ['access_token' => $access_token]);
        $data = $client->parseJSON($result);

        Log::debug('sendMsg 2', $data);

        return $data;

    }

    /**
     * @param $scene
     * @return array
     * @author renshuai  <renshuai@jperation.cn>
     */
    private function _getDataOfScene($scene)
    {
        $data = [
            123 => [
                'title' => 'title',
                'data' => [],
                'url' => 'http://credit.iwide.cn/soma/package/index?id=a450089706'
            ]
        ];

        if (isset($data[$scene])) {
            return $data[$scene];
        } else {
            return [];
        }
    }

    /**
     * @param null $index
     * @return array|null|int
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function getSceneKey($index = null)
    {
        $keys = [
            'test' => 123
        ];

        if (is_null($index)) {
            return $keys;
        } elseif(isset($keys[$index])) {
            return $keys[$index];
        } else {
            return null;
        }
    }

}