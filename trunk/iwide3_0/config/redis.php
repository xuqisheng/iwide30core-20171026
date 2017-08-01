<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
    $config['socket_type'] = 'tcp'; //`tcp` or `unix`
    //$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
    $config['password'] = NULL;
    $config['timeout'] = 0;
    if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
        $config['cachedb'] = 3;
        $config['host'] = 'redis02';
        $config['port'] = 6382;
        
    } else {
        $config['cachedb'] = 2;
        $config['host'] = 'redis01';
        $config['port'] = 6380;
    }
    
} else {
    $config['socket_type'] = 'tcp'; //`tcp` or `unix`
    //$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
    $config['host'] = '30.iwide.cn';
    //$config['host'] = '10.0.0.250';
    //$config['host'] = 'redis01';
    $config['password'] = NULL;
    $config['port'] = 16379;
    //$config['port'] = 6379;
    //$config['port'] = 6381;
    $config['timeout'] = 3;
    if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' )
        $config['cachedb'] = 3;
    else 
        $config['cachedb'] = 2;
}