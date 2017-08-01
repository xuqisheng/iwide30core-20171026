<?php
namespace App\libraries\Support;


/**
 *
 * 工具类
 *
 * Class Tool
 * @package App\libraries\Support
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Tool
{

    /**
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     */
    public static function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

}