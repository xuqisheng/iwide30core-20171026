<?php

namespace App\core\Db;

use RuntimeException,MYLOG;

defined('BASEPATH') OR exit('No direct script access allowed');

class DbModel implements DbInterface
{
    protected $db_write = 'iwide_rw';
    protected $db_read = 'iwide_r1';
    private $db_member = 'iwide_vip';
    private $db_conf = [];
    private $config = [];
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

    /**
     * @author liwensong
     * @param bool $db_write false[读库]／true[写库]
     * @return CI_DB
     */
    public function _shard_db($select = '', $db_write = FALSE)
    {
        $db = [];
        if (!file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php')
            && !file_exists($file_path = APPPATH . 'config/database.php')) {
            show_error('The configuration file database.php does not exist.');
        }

        include $file_path; //引入配置

        // Make packages contain database config files,given that the controller instance already exists
        foreach ($this->getCI()->load->get_package_paths() as $path) {
            if ($path !== APPPATH) {
                if (file_exists($file_path = $path . 'config/' . ENVIRONMENT . '/database.php')) {
                    include($file_path);
                } elseif (file_exists($file_path = $path . 'config/database.php')) {
                    include($file_path);
                }
            }
        }

        $this->db_conf = $db;
        if (!isset($this->db_conf) OR count($this->db_conf) === 0) {
            show_error('No database connection settings were found in the database config file.');
        }

        return $this->_db($select,$db_write);
    }

    public function _db($select = '', $db_write = FALSE)
    {
        $str_db_write = var_export($db_write, true);
        if (empty($select)) {
            $select = $this->db_member;
        }

        $md5 = md5($select . $str_db_write);

        if (!isset($this->db_resource[$md5])) {
            $this->db_resource[$md5] = $this->database($select, TRUE, $db_write);
        }
        return $this->db_resource[$md5];
    }

    /**
     * Database Loader
     *
     * @param    mixed $params Database configuration options
     * @param    bool $return Whether to return the database object
     * @param    bool $query_builder Whether to enable Query Builder
     *                    (overrides the configuration setting)
     *
     * @return    object|bool    Database object if $return is set to TRUE,
     *                    FALSE on failure, CI_Loader instance in any other case
     */
    public function database($params = '', $return = FALSE, $db_write = FALSE, $query_builder = NULL)
    {
        // Grab the super object
        // Do we even need to load the database class?
        if ($return === FALSE && $query_builder === NULL && isset($this->db) && is_object($this->db) && !empty($this->db->conn_id)) {
            return FALSE;
        }

        // Load the DB config file if a DSN string wasn't passed
        if (is_string($params) && strpos($params, '://') === FALSE) {
            if (empty($this->db_conf) OR count($this->db_conf) === 0) {
                show_error('No database connection settings were found in the database config file.');
            }

            if ($params !== '') {
                $active_group = $params;
            }

            if (!isset($active_group)) {
                show_error('You have not specified a database connection group via $active_group in your config/database.php file.');
            } elseif (!isset($this->db_conf[$active_group])) {
                show_error('You have specified an invalid database connection group (' . $active_group . ') in your config/database.php file.');
            }

            $this->config = $this->db_conf[$active_group];
        } elseif (is_string($params)) {
            /**
             * Parse the URL from the DSN string
             * Database settings can be passed as discreet
             * parameters or as a data source name in the first
             * parameter. DSNs must have this prototype:
             * $dsn = 'driver://username:password@hostname/database';
             */

            $this->config = self::parseDsn($params);
        }

        $DB = $this->initConnect($return, $db_write, $query_builder);
        return $DB;
    }

    /**
     * Parse the URL from the DSN string
     * Database settings can be passed as discreet
     * parameters or as a data source name in the first
     * parameter. DSNs must have this prototype:
     * $dsn = 'driver://username:password@hostname/database';
     */
    static private function parseDsn($dsnStr = '')
    {
        if (empty($dsnStr)) {
            return false;
        }
        $info = parse_url($dsnStr);
        if (!$info) {
            return false;
        }
        $dsn = array(
            'dbdriver' => $info['scheme'],
            'username' => isset($info['user']) ? $info['user'] : '',
            'password' => isset($info['pass']) ? $info['pass'] : '',
            'hostname' => isset($info['host']) ? $info['host'] : '',
            'port' => isset($info['port']) ? $info['port'] : '',
            'database' => isset($info['path']) ? substr($info['path'], 1) : '',
            'char_set' => isset($info['fragment']) ? $info['fragment'] : 'utf8',
            'dbprefix' => 'iwide_',
            'pconnect' => false,
            'db_debug' => true,
            'cache_on' => false,
            'cachedir' => '',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => false,
            'compress' => false,
            'stricton' => false,
            'save_queries' => true,
        );

        if (isset($info['query'])) {
            parse_str($info['query'], $dsn['params']);
        } else {
            $dsn['params'] = array();
        }

        if (isset($info['query'])) {
            parse_str($info['query'], $extra);

            foreach ($extra as $key => $val) {
                if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL'))) {
                    $val = var_export($val, TRUE);
                }

                $dsn[$key] = $val;
            }
        }
        return $dsn;
    }

    /**
     * 获取连接分布式服务器
     * @access protected
     * @param boolean $master 主服务器
     * @return array
     */
    private function multiConnect($db_write = false)
    {
        // 分布式数据库配置解析
        $_config['username'] = explode(',', $this->config['username']);
        $_config['password'] = explode(',', $this->config['password']);
        $_config['hostname'] = explode(',', $this->config['hostname']);
        $_config['hostport'] = explode(',', $this->config['port']);
        $_config['database'] = explode(',', $this->config['database']);
        $_config['dsn'] = explode(',', $this->config['dsn']);
        $_config['charset'] = explode(',', $this->config['char_set']);

        if (!isset($this->config['master_num']) || empty($this->config['master_num'])) $this->config['master_num'] = 1;
        $m = floor(mt_rand(0, $this->config['master_num'] - 1));

        // 数据库读写是否分离
        if (isset($this->config['rw_separate']) && $this->config['rw_separate']) {
            // 主从式采用读写分离
            if ($db_write === true) {
                // 主服务器写入
                $r = $m;
                if (isset($this->config['slave_no']) && is_numeric($this->config['slave_no'])) {// 指定服务器读
                    $r = $this->config['slave_no'];
                }
            } else {
                if (isset($this->config['slave_no']) && is_numeric($this->config['slave_no'])) {// 指定服务器读
                    $r = $this->config['slave_no'];
                } else {
                    // 读操作连接从服务器
                    $r = floor(mt_rand($this->config['master_num'], count($_config['hostname']) - 1));   // 每次随机连接的数据库
                }
            }
        } else {
            // 读写操作不区分服务器
            $r = floor(mt_rand(0, count($_config['hostname']) - 1));   // 每次随机连接的数据库
        }

        if ($db_write === true) {
            $db_master = array(
                'username' => isset($_config['username'][$m]) ? $_config['username'][$m] : $_config['username'][0],
                'password' => isset($_config['password'][$m]) ? $_config['password'][$m] : $_config['password'][0],
                'hostname' => isset($_config['hostname'][$m]) ? $_config['hostname'][$m] : $_config['hostname'][0],
                'port' => isset($_config['hostport'][$m]) ? $_config['hostport'][$m] : $_config['hostport'][0],
                'database' => isset($_config['database'][$m]) ? $_config['database'][$m] : $_config['database'][0],
                'dsn' => isset($_config['dsn'][$m]) ? $_config['dsn'][$m] : $_config['dsn'][0],
                'char_set' => isset($_config['charset'][$m]) ? $_config['charset'][$m] : $_config['charset'][0],
            );
            $this->config = array_merge($this->config, $db_master);
        } else {
            $db_config = array(
                'username' => isset($_config['username'][$r]) ? $_config['username'][$r] : $_config['username'][0],
                'password' => isset($_config['password'][$r]) ? $_config['password'][$r] : $_config['password'][0],
                'hostname' => isset($_config['hostname'][$r]) ? $_config['hostname'][$r] : $_config['hostname'][0],
                'port' => isset($_config['hostport'][$r]) ? $_config['hostport'][$r] : $_config['hostport'][0],
                'database' => isset($_config['database'][$r]) ? $_config['database'][$r] : $_config['database'][0],
                'dsn' => isset($_config['dsn'][$r]) ? $_config['dsn'][$r] : $_config['dsn'][0],
                'char_set' => isset($_config['charset'][$r]) ? $_config['charset'][$r] : $_config['charset'][0],
            );
            $this->config = array_merge($this->config, $db_config);
        }

        return $this->config;
    }

    /**
     * 初始化数据库连接
     * @access protected
     * @param boolean $master 主服务器
     * @return object
     */
    private function initConnect($return = FALSE, $db_write = false, $query_builder = NULL)
    {
        $config = $this->multiConnect($db_write); // 采用分布式数据库 (兼容以前的单数据库)
        $this->getCI()->load->helper('member_helper');
        MYLOG::w(json_encode(array('db_write' => $db_write, 'config' => $config)), 'membervip/db_model_member', 'conf');
        if (!empty($this->config['deploy'])) {
            if ($return === TRUE) {
                return MDB($config, $query_builder);
            }
            $this->db = &MDB($config, $query_builder);
        } else {
            // 默认单数据库
            if ($return === TRUE) {
                return MDB($config, $query_builder);
            }
            if (!$this->db) $this->db = &MDB($config, $query_builder);
        }
        return $this;
    }
}