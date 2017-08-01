<?php

namespace App\controllers\front\traits;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 *
 * MY_Front.php 直接继承了CI_Controller，没有继承MY_Controller
 * 所以为了防止写两次，使用trait解决
 *
 * Class Controller
 * @package App\Controllers\Front\Traits
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
trait Controller {

    /**
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    private function initMonoLog()
    {
        $log = new Logger('mono');

        $handler = new RotatingFileHandler(APPPATH . 'logs/soma/main.log', 20, Logger::ERROR, false);

        $log->pushHandler($handler);

        $this->monoLog = $log;
    }

    /**
     * @param $className
     * @param $module
     * @param $logLevel
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function controllerLogHandler($className, $logLevel = 200, $module = 'soma' )
    {
        $className = strtolower($className);
        $module = strtolower($module);

        $fileName = 'controller.log';

        $path = APPPATH . "logs/$module/$className/$fileName";
        $handler = new RotatingFileHandler( $path, 20, $logLevel, false);
        $this->monoLog->pushHandler($handler);
    }

}