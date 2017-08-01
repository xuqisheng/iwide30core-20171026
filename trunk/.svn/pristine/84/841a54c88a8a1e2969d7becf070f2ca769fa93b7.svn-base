<?php
namespace App\services;

use GuzzleHttp\Client;
use Lunatic\Monolog\Formatter\BunyanFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Tatocaster\Monolog\Formatter\JsonPrettyUnicodePrintFormatter;
use Vube\Monolog\Formatter\SplunkLineFormatter;

/**
 * Class Base_Service
 * @package App\services
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class BaseService implements BaseServiceInterface
{

    /**
     * @var array
     */
    static private $services = array();

    /**
     * @var \CI_Controller $CI
     */
    static private $CI;

    /**
     * @param string $serviceClass
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    protected static function init($serviceClass)
    {
        if (empty(self::$CI)) {
            self::$CI = &get_instance();
        }

        if (!isset(self::$services[$serviceClass]))
        {
            self::$services[$serviceClass] = new $serviceClass();
        }
        return self::$services[$serviceClass];
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
     * @author renshuai  <renshuai@mofly.cn>
     */
    public static function getInstance(){
        throw new \RuntimeException('Service does not implement getInstance method.');
    }

    /**
     * @param $serviceName
     * @param int $logLevel
     * @param string $module
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function serviceLogHandler($serviceName, $logLevel = 200, $module = 'soma' )
    {
        $class = explode('\\', strtolower($serviceName));
        $serviceName = end($class);

        $module = strtolower($module);

        $fileName = 'service.log';

        $path = APPPATH . "logs/$module/services/$serviceName/$fileName";
        $handler = new RotatingFileHandler($path, 20, $logLevel, false);
        $formatter = new JsonPrettyUnicodePrintFormatter();
        $handler->setFormatter($formatter);
        self::$CI->monoLog->pushHandler($handler);
    }

    /**
     * @param $uri
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function request($uri)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $uri);
            $res = json_decode($res->getBody(), true);
            return $res;
        } catch(\Exception $e) {
            $handler = new RotatingFileHandler(APPPATH . 'logs/soma/service/request.log', 20, Logger::ERROR, false);
            self::$CI->monoLog->pushHandler($handler);
            self::$CI->monoLog->error($e->getCode() . $e->getMessage(), $e->getTrace());
        }
    }

}