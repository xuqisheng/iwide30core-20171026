<?php

namespace App\services\soma\package;

/**
 * Class Package
 * @package App\services\package
 * @author liguanglong  <liguanglong@mofly.cn>
 *
 */
class Package
{
    /**
     * @var \MY_Front_Soma $CI
     */
    static private $CI;

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

    public function create()
    {
        return 1;
    }

}