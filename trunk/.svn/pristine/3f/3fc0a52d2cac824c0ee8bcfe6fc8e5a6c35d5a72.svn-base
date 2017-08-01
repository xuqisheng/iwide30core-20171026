<?php
namespace App\services\soma\express;

use App\libraries\Http;
use App\libraries\Support\Collection;
use App\libraries\Support\Log;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

/**
 * Class Express
 * @package App\services\express
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Express
{
    /**
     * @var \MY_Front_Soma $CI
     */
    static private $CI;

    /**
     * Http instance.
     *
     * @var Http
     */
    protected $http;

    /**
     * Express constructor.
     */
    public function __construct()
    {
        if (empty(self::$CI)) {
            self::$CI = &get_instance();
        }
    }

    /**
     * @return \MY_Front_Soma
     * @author renshuai  <renshuai@mofly.cn>
     */
    protected function getCI()
    {
        return self::$CI;
    }



    /**
     * @return Http
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function getHttp()
    {
        if (is_null($this->http)) {
            $this->http = new Http();
        }

        if (count($this->http->getMiddlewares()) === 0) {
            $this->registerHttpMiddlewares();
        }

        return $this->http;
    }

    /**
     * @param $method
     * @param $args
     * @return Collection
     * @throws \Exception
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function parseJson($method, $args)
    {
        $http = $this->getHttp();
        $contents = $http->parseJSON(call_user_func_array([$http, $method], $args));

        return new Collection($contents);
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // log
        $this->http->addMiddleware($this->logMiddleware());
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        return Middleware::tap(function (RequestInterface $request, $options) {
            Log::debug("Request: {$request->getMethod()} {$request->getUri()} ".json_encode($options));
            Log::debug('Request headers:'.json_encode($request->getHeaders()));
        });
    }


}