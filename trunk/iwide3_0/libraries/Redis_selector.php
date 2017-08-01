<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Redis_selector {

    /**
     * Gets the soma redis.
     *
     * @param      <type>         $select  The select
     *
     * @return     Redis|boolean  The soma redis.
     */
    function get_soma_redis($select) {
        $CI =& get_instance();
        $CI->config->load('redis', TRUE, TRUE);
        $redis_config = $CI->config->item('redis');
        $config = $redis_config[$select];

        if(!is_array($config)) {
            return false;
        }

        $redis = new Redis();

        $success = $redis->connect($config['host'], $config['port'], $config['timeout']);
        if(!$success) {
            return false;
        }

        return $redis;
    }
    
}