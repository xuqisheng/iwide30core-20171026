<?php

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/3/1
 * Time: 11:14
 *
 * @property Publics_model $publicModel
 */
class MY_Service
{
    /**
     * 数据库配置数组的key
     */
    CONST DB = 'iwide_soma';

    /**
     * 数据库配置数组的key
     */
    CONST DB_READ = 'iwide_soma_r';


    /**
     * @var CI_Controller $CI
     */
    protected $CI;

    /**
     * @var CI_DB_query_builder $db
     */
    protected $db;

    /**
     * @var CI_DB_query_builder $db_read
     */
    protected $db_read;

    /**
     * Order_Service constructor.
     */
    public function __construct()
    {
        $this->CI = & get_instance();

        $this->CI->soma_db_conn || $this->CI->load->somaDatabase(self::DB);

        $this->db = $this->CI->soma_db_conn;

        $this->CI->soma_db_conn_read || $this->CI->load->somaDatabaseRead(self::DB_READ);

        $this->db_read = $this->CI->soma_db_conn_read;

    }

    /**
     * @param $key
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    function __get($key)
    {
        return $this->CI->$key;
    }

    /**
     * @param $modelName
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function modelAlias($modelName)
    {
        $modelName = str_replace('_', ' ', $modelName);
        return 'soma' . str_replace(' ', '', ucwords($modelName));
    }

    /**
     * @param $modelName
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function modelName($modelName)
    {
        return "soma/$modelName";
    }


    /**
     * @param $model
     * @param null $profile
     * @author renshuai  <renshuai@mofly.cn>
     */
    protected function _log($model, $profile=NULL)
    {
        $this->load->library('EA_behavior_log');
        if( !$profile) {
            $profile= $this->session->get_admin_profile();
        }
        EA_behavior_log::inst()->record($profile, $model);
    }


}