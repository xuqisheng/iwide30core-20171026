<?php

namespace App\core\Db;

use RuntimeException;

defined('BASEPATH') OR exit('No direct script access allowed');

class DbModel implements DbInterface
{
    protected $db_write = 'iwide_rw';
    protected $db_read = 'iwide_r1';
    protected $db_resource = array();

    /**
     * @author liwensong <septet-l@outlook.com>
     */
    public static function getInstance()
    {
        throw new RuntimeException('Db does not implement getInstance method.');
    }

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
    public static function init($serviceClass)
    {
        if (!isset(self::$services[$serviceClass])) {
            self::$services[$serviceClass] = new $serviceClass();
        }
        return self::$services[$serviceClass];
    }

    /**
     * @return \CI_Controller
     * @author liwensong  <septet-l@outlook.com>
     */
    public function getCI()
    {
        if (empty(self::$CI)) {
            self::$CI = &get_instance();
        }
        return self::$CI;
    }

    public function _shard_db($select = '')
    {
        return $this->_db($select);
    }

    protected function _db($select = '')
    {
        $select = !empty($select) ? $select : $this->db_write;
        if (!isset($this->db_resource[$select])) {
            $this->db_resource[$select] = $this->getCI()->load->database($select, TRUE);
        }
        return $this->db_resource[$select];
    }
}