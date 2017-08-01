<?php
namespace App\services\soma\express;


/**
 * Class ExpressProvider
 * @package App\services\express
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class ExpressProvider
{
    /**
     * 顺丰
     */
    const TYPE_SF = 1;

    /**
     * @param $type
     * @return ShunFeng
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function resolve($type)
    {
        switch($type) {
            case self::TYPE_SF:
                $express = new ShunFeng();
                break;
            default:
                throw new \RuntimeException('没找到对应的快递类');
        }

        return $express;
    }


}