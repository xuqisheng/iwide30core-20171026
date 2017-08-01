<?php
namespace App\services\soma\express;
use App\libraries\Http;
use App\libraries\Support\Log;
use RuntimeException;

/**
 * Class ShunFengToken
 * @package App\services\soma\express
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class ShunFengToken extends Express
{

    /**
     * @var string $appId 顺丰appid
     */
    private $appId;

    /**
     * @var string $appKey 顺丰appKey
     */
    private $appKey;

    /**
     * 缓存key
     */
    const ACCESS_TOKNE_KEY = 'SF_TOKEN_KEY';

    /**
     * 申请token类型
     */
    const GET_TOKEN_TYPE = 301;

    /**
     * 申请token的调用地址
     */
    const GET_ACCESS_TOKNE_URL = 'https://open-sbox.sf-express.com/public/v1.0/security/access_token/sf_appid/%s/sf_appkey/%s';
    const GET_ACCESS_TOKNE_URL_PRODUCT = 'https://open-prod.sf-express.com/public/v1.0/security/access_token/sf_appid/%s/sf_appkey/%s';
    //todo 方便测试

    /**
     * ShunFengToken constructor.
     *
     */
    public function __construct($appId, $appKey)
    {
        parent::__construct();

        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    /**
     * @throws \Exception
     * @author renshuai  <renshuai@jperation.cn>
     *
     * @return array
     */
    public function getTokenFromServer()
    {
        $postData = array(
            "head" => [
                "transMessageId" => date('YmdHis', time()).mt_rand(1000, 9999),
                "transType" => self::GET_TOKEN_TYPE
            ],
            "body" => null
        );
        $client = new Http();
        if (ENVIRONMENT == 'production'){
            $url = sprintf(self::GET_ACCESS_TOKNE_URL_PRODUCT, $this->appId, $this->appKey);
        } else {
            $url = sprintf(self::GET_ACCESS_TOKNE_URL, $this->appId, $this->appKey);
        }

        $response = $client->json($url, $postData);
        $token = $client->parseJSON($response);

        if (empty($token['body']['accessToken'])) {
            throw new RuntimeException('Request AccessToken fail. response: '.json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }

    /**
     *
     * @param bool $forceRefresh
     *
     * @return string
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function getToken($forceRefresh = false)
    {
        $redis = $this->getCI()->get_redis_instance();
        $cached = $redis->get(self::ACCESS_TOKNE_KEY);

        if (empty($cached) || $forceRefresh) {
            $token = $this->getTokenFromServer();

            $redis->set(self::ACCESS_TOKNE_KEY, $token['body']['accessToken'],  3500);
            return $token['body']['accessToken'];
        }

        return $cached;

    }

    public function getQueryName()
    {
        return 'access_token';
    }



}