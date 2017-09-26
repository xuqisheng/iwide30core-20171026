<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('member_level')) {
    function member_level($level = null)
    {
        static $memberLevel;

        if (!isset($memberLevel)) {
            $CI =& get_instance();

            $CI->load->model('member/config', 'mconfig');
            $memberLevel = $CI->mconfig->getConfig('level', true)->value;
        }

        if (isset($memberLevel[$level])) {
            return $memberLevel[$level]['name'];
        } else {
            return $memberLevel;
        }
    }
}

if (!function_exists('card_type')) {
    function card_type($id = null)
    {
        static $cts;

        if (!isset($cts)) {
            $CI =& get_instance();

            $CI->load->model('member/icard');
            $cardTypes = $CI->icard->getCardTypeList();

            $cts = array();
            foreach ($cardTypes as $cardtype) {
                $cts[$cardtype->ct_id] = $cardtype;
            }
        }

        if (isset($cts[$id])) {
            return $cts[$id]->type_name;
        } else {
            return $cts;
        }
    }
}

if (!function_exists('check_separate_backend_frontend')) {
    function check_separate_backend_frontend($id = '')
    {
        $where = array(
            'inter_id' => 'ALL_INTER_ID',
            'type_code' => 'SEPARATE_BACKEND_FRONTEND'
        );
        $conf = IWIDE_DB()->where($where)->limit(1)->get('inter_member_config')->row_array();
        if (empty($conf)) return false;
        $ids = explode(',', $conf['value']);
        if (in_array($id, $ids)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('MDB')) {
    /**
     * Initialize the database
     *
     * @category    Database
     * @author    EllisLab Dev Team
     * @link    http://codeigniter.com/user_guide/database/
     *
     * @param    string|string[] $params
     * @param    bool $query_builder_override
     *                Determines if query builder should be used or not
     */
    function &MDB($params = '', $query_builder_override = NULL)
    {
        // No DB specified yet? Beat them senseless...
        if (empty($params['dbdriver'])) {
            show_error('You have not selected a database type to connect to.');
        }

        // Load the DB classes. Note: Since the query builder class is optional
        // we need to dynamically create a class that extends proper parent class
        // based on whether we're using the query builder class or not.
        if ($query_builder_override !== NULL) {
            $query_builder = $query_builder_override;
        }
        // Backwards compatibility work-around for keeping the
        // $active_record config variable working. Should be
        // removed in v3.1
        elseif (!isset($query_builder) && isset($active_record)) {
            $query_builder = $active_record;
        }

        require_once(BASEPATH . 'database/DB_driver.php');

        if (!isset($query_builder) OR $query_builder === TRUE) {
            require_once(BASEPATH . 'database/DB_query_builder.php');
            if (!class_exists('CI_DB', FALSE)) {
                /**
                 * CI_DB
                 *
                 * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
                 *
                 * @see    CI_DB_query_builder
                 * @see    CI_DB_driver
                 */
                class CI_DB extends CI_DB_query_builder
                {
                }
            }
        } elseif (!class_exists('CI_DB', FALSE)) {
            /**
             * @ignore
             */
            class CI_DB extends CI_DB_driver
            {
            }
        }

        // Load the DB driver
        $driver_file = BASEPATH . 'database/drivers/' . $params['dbdriver'] . '/' . $params['dbdriver'] . '_driver.php';

        file_exists($driver_file) OR show_error('Invalid DB driver');
        require_once($driver_file);

        // Instantiate the DB adapter
        $driver = 'CI_DB_' . $params['dbdriver'] . '_driver';
        $DB = new $driver($params);

        // Check for a subdriver
        if (!empty($DB->subdriver)) {
            $driver_file = BASEPATH . 'database/drivers/' . $DB->dbdriver . '/subdrivers/' . $DB->dbdriver . '_' . $DB->subdriver . '_driver.php';

            if (file_exists($driver_file)) {
                require_once($driver_file);
                $driver = 'CI_DB_' . $DB->dbdriver . '_' . $DB->subdriver . '_driver';
                $DB = new $driver($params);
            }
        }

        $DB->initialize();
        return $DB;
    }
}

if (!function_exists('IWIDE_DB')) {
    /**
     * 获取数据库连接
     * @param string $name 驱动名称
     * @param string $select 连接名
     * @param boolean $read 是否读库 【会员用】
     * @return bool|CI_DB
     */
    function IWIDE_DB($select = '', $read = false)
    {
        static $_model = array();
        $namespace = 'App\\core\\Db\\';
        $class = $namespace . 'DbModel';
        $linktp = var_export($read, true);
        $guid = $select . $linktp . '_IWIDE_DB_' . $class;
        if (class_exists($class)) {
            if (!isset($_model[$guid])) {
                $driver = new $class();
                if(method_exists($driver,'_shard_db')){
                    $_model[$guid] = $driver->_shard_db($select,$read);
                }else{
                    show_error('The method \'_shard_db\' does not exist in the \'DbModel\' class! ');
                }
            }
            return $_model[$guid];
        }
        return false;
    }
}


if (!function_exists('my_listorder')){
    /**
     * 自定义排序（顺序排序）
     * @param integer $a 第一个比较值
     * @param integer $b 第二个比较值
     * @return int
     */
    function my_listorder($a,$b){
        if(is_array($a)) $a = end($a);
        if(is_array($b)) $b = end($b);
        if ($a===$b) return 0;
        return ($a<$b)?-1:1;
    }
}


if (!function_exists('my_rlistorder')){
    /**
     * 自定义排序 (逆向排序)
     * @param integer $a 第一个比较值
     * @param integer $b 第二个比较值
     * @return int
     */
    function my_rlistorder($a,$b){
        if(is_array($a)) $a = end($a);
        if(is_array($b)) $b = end($b);
        if ($a===$b) return 0;
        return ($a>$b)?-1:1;
    }
}