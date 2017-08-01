<?php

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/2/28
 * Time: 17:00
 */
class MY_Loader extends CI_Loader
{
    //service path
    protected $_ci_services_paths = array(APPPATH);

    //service class
    protected $_ci_services = array();

    /**
     * @param	string	$model		Model name
     * @param	string	$name		An optional object name to assign to
     * @param	CI_DB_query_builder	$db_conn
     * @param	CI_DB_query_builder	$db_conn_read
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function modelWithDBconn($model, $name, $db_conn, $db_conn_read)
    {
        $this->model($model, $name);

        if ($db_conn && $db_conn_read) {
            $CI =& get_instance();
            $CI->$name->setDbConn($db_conn);
            $CI->$name->setDbConnRead($db_conn_read);
        }
    }

    /**
     * Service Loader
     *
     * Loads and instantiates libraries.
     * Designed to be called from application controllers.
     *
     * @param	string|array	$service    Service name
     * @param	array	$params		Optional parameters to pass to the library class constructor
     * @param	string	$object_name	An optional object name to assign to
     * @return	object
     */

    public function service($service, $params = NULL, $object_name = NULL)
    {
        if (empty($service))
        {
            return $this;
        }
        elseif (is_array($service))
        {
            foreach ($service as $key => $value)
            {
                if (is_int($key))
                {
                    $this->service($value, $params);
                }
                else
                {
                    $this->service($key, $params, $value);
                }
            }

            return $this;
        }

        $path = '';

        // Is the service in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($service, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($service, 0, ++$last_slash);

            // And the service name behind it
            $service = substr($service, $last_slash);
        }

        if (empty($object_name))
        {
            $object_name = $service;
        }

        $object_name = strtolower($object_name);
        if (in_array($object_name, $this->_ci_services, TRUE))
        {
            return $this;
        }

        $CI =& get_instance();
        if (isset($CI->$object_name))
        {
            throw new RuntimeException('The service name you are loading is the name of a resource that is already being used: '.$object_name);
        }
        //load MY_Service
        $class = config_item('subclass_prefix').'Service';
        $app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;


        if(!class_exists($class, FALSE))
        {
            if (file_exists($app_path.$class.'.php'))
            {
                require_once($app_path.$class.'.php');
                if (!class_exists($class, FALSE))
                {
                    throw new RuntimeException($app_path.$class.".php exists, but doesn't declare class ".$class);
                }
            }
        }

        $service = ucfirst($service);
        if (!class_exists($service, FALSE))
        {
            //load service files
            foreach ($this->_ci_services_paths as $service_path)
            {
                if ( ! file_exists($service_path.'services/'.$path.$service.'.php'))
                {
                    continue;
                }
                //default path application/services/
                include_once($service_path.'services/'.$path.$service.'.php');

                $CI = &get_instance();

                if($params !== NULL)
                {
                    $CI->$object_name = new $service($params);
                }
                else
                {
                    $CI->$object_name = new $service();
                }

                $this->_ci_services[] = $object_name;

                if (!class_exists($service, FALSE))
                {
                    throw new RuntimeException($service_path."services/".$path.$service.".php exists, but doesn't declare class ".$service);
                }

                break;
            }

        }

        return $this;
    }

    /**
     * @param	mixed	$params		Database configuration options
     * @param	bool	$query_builder	Whether to enable Query Builder
     *					(overrides the configuration setting)
     *
     * @return	object|bool	Database object if $return is set to TRUE,
     *					FALSE on failure, CI_Loader instance in any other case
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function somaDatabase($params = '', $query_builder = NULL)
    {
        // Grab the super object
        $CI =& get_instance();

        // Do we even need to load the database class?
        if ($query_builder === NULL && isset($CI->soma_db_conn) && is_object($CI->soma_db_conn) && ! empty($CI->soma_db_conn->conn_id))
        {
            return false;
        }

        // Initialize the db variable. Needed to prevent
        // reference errors with some configurations
        $CI->soma_db_conn = '';

        // Load the DB class
        $CI->soma_db_conn =& DB($params, $query_builder);
        return $this;
    }

    /**
     * @param	mixed	$params		Database configuration options
     * @param	bool	$query_builder	Whether to enable Query Builder
     *					(overrides the configuration setting)
     *
     * @return	object|bool	Database object if $return is set to TRUE,
     *					FALSE on failure, CI_Loader instance in any other case
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function somaDatabaseRead($params = '', $query_builder = NULL)
    {
        // Grab the super object
        $CI =& get_instance();

        // Do we even need to load the database class?
        if ($query_builder === NULL && isset($CI->soma_db_conn_read) && is_object($CI->soma_db_conn_read) && ! empty($CI->soma_db_conn_read->conn_id))
        {
            return false;
        }

        // Initialize the db variable. Needed to prevent
        // reference errors with some configurations
        $CI->soma_db_conn_read = '';

        // Load the DB class
        $CI->soma_db_conn_read =& DB($params, $query_builder);
        return $this;
    }


    public function get_loaded_classes()
    {
        return $this->_ci_classes;
    }

    public function get_loaded_helpers()
    {
        $loaded_helpers = array();
        if(sizeof($this->_ci_helpers)!== 0) {
            foreach ($this->_ci_helpers as $key => $value)
            {
                $loaded_helpers[] = $key;
            }
        }
        return $loaded_helpers;
    }

    public function get_loaded_models()
    {
        return $this->_ci_models;
    }

}