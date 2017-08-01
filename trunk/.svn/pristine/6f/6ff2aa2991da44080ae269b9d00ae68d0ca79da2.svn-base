<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 新会员模块数据库相关处理
 * User: liwensong
 * Date: 16/7/28
 * Time: 下午6:03
 */
class MY_Model_Member extends MY_Model {
    const STATUS_TRUE = 1;
    const STATUS_FALSE = 2;
    public $db_member= 'iwide_vip'; //vip新会员数据库连接信息组名  定位database的配置
    private $db_conf = [];
    private $config = [];

    /**
     * @author liwensong
     * @param bool $db_write false[读库]／true[写库]
     * @return CI_DB
     */
    public function _shard_db($db_write = FALSE) {
        $db = [];
        if ( ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
            && ! file_exists($file_path = APPPATH.'config/database.php'))
        {
            show_error('The configuration file database.php does not exist.');
        }

        include $file_path; //引入配置

        // Make packages contain database config files,given that the controller instance already exists
        if (class_exists('CI_Controller', FALSE)) {
            foreach (get_instance()->load->get_package_paths() as $path) {
                if ($path !== APPPATH) {
                    if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php')) {
                        include($file_path);
                    } elseif (file_exists($file_path = $path.'config/database.php')) {
                        include($file_path);
                    }
                }
            }
        }

        $this->db_conf = $db;
        if ( ! isset($this->db_conf) OR count($this->db_conf) === 0)
        {
            show_error('No database connection settings were found in the database config file.');
        }

        return $this->_db($db_write);
    }

    protected function _db($db_write=FALSE) {
        $select= $this->db_member;
        $str_db_write = var_export($db_write,true);
        if (empty($select)) {
            $select = 'iwide_vip';
        }

        $md5 = md5($select.$str_db_write);

        if( !isset($this->db_resource[$md5]) ) {
            $this->db_resource[$md5]= $this->database($select, TRUE,$db_write);
        }
        return $this->db_resource[$md5];
    }

    public function write_log( $content, $tmpfile )
    {
        //echo $tmpfile;die;
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $tmpfile, 'a');

        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $ip. "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 加载缓存组件
     * @see MY_Controller::_load_cache()
     */
    protected function _load_cache( $name='Cache' )
    {
        $success = Soma_base::inst()->check_cache_redis();
        if( !$success){
            //redis故障关闭cache
            Soma_base::inst()->show_exception('当前访问用户过多，请稍后再试！', TRUE );
        }
        if(!$name || $name=='cache') //不能为小写cache
            $name='Cache';

        $this->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'membervip_'),
            $name
        );
        return $this->$name;
    }

    public function get_hotels_hash()
    {
        $this->_init_admin_hotels();
        $publics = $hotels= array();
        $filter= $filterH= NULL;
        if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
        else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
        if(is_array($filter)){
            $this->load->model('wx/publics_model');
            $publics= $this->publics_model->get_public_hash($filter);
            $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
            //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
        }

        if( $this->_admin_hotels== FULL_ACCESS )
            $filterH= array();
        else if( is_array($this->_admin_hotels) && count($this->_admin_hotels)>0 )
            $filterH= array( 'inter_id'=> $this->_admin_inter_id, 'hotel_id'=> $this->_admin_hotels);
        else
            $filterH= array( 'inter_id'=> $this->_admin_inter_id );

        if( $publics && is_array($filterH)){
            $this->load->model('hotel/hotel_model');
            $hotels= $this->hotel_model->get_hotel_hash($filterH);
            $hotels= $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
            //$hotels= $hotels+ array('0'=>'-不限定-');
        }
        return array('filter'=> $filter, 'filterH'=> $filterH, 'publics'=> $publics, 'hotels'=>$hotels );
    }

    /**
     * Database Loader
     *
     * @param	mixed	$params		Database configuration options
     * @param	bool	$return 	Whether to return the database object
     * @param	bool	$query_builder	Whether to enable Query Builder
     *					(overrides the configuration setting)
     *
     * @return	object|bool	Database object if $return is set to TRUE,
     *					FALSE on failure, CI_Loader instance in any other case
     */
    public function database($params = '', $return = FALSE, $db_write = FALSE, $query_builder = NULL) {
        // Grab the super object
        // Do we even need to load the database class?
        if ($return === FALSE && $query_builder === NULL && isset($this->db) && is_object($this->db) && ! empty($this->db->conn_id)) {
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
                show_error('You have specified an invalid database connection group ('.$active_group.') in your config/database.php file.');
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

        $DB = $this->initConnect($return,$db_write,$query_builder);
        return $DB;
    }

    /**
     * 初始化数据库连接
     * @access protected
     * @param boolean $master 主服务器
     * @return void
     */
    private function initConnect($return = FALSE,$db_write=false,$query_builder = NULL) {
        $config = $this->multiConnect($db_write); // 采用分布式数据库 (兼容以前的单数据库)
        $this->load->library("MYLOG");
        MYLOG::w(json_encode(array('db_write'=>$db_write,'config'=>$config)),'membervip/db_model_member','conf');
        if(!empty($this->config['deploy'])){
            if ($return === TRUE) {
                return MemberDB($config,$query_builder);
            }
            $this->db = & MemberDB($config,$query_builder);
        }else{
            // 默认单数据库
            if ($return === TRUE) {
                return MemberDB($config,$query_builder);
            }
            if ( !$this->db ) $this->db = & MemberDB($config,$query_builder);
        }
        return $this;
    }


    /**
     * 获取连接分布式服务器
     * @access protected
     * @param boolean $master 主服务器
     * @return void
     */
    private function multiConnect($db_write=false) {
        // 分布式数据库配置解析
        $_config['username']    =   explode(',',$this->config['username']);
        $_config['password']    =   explode(',',$this->config['password']);
        $_config['hostname']    =   explode(',',$this->config['hostname']);
        $_config['hostport']    =   explode(',',$this->config['port']);
        $_config['database']    =   explode(',',$this->config['database']);
        $_config['dsn']         =   explode(',',$this->config['dsn']);
        $_config['charset']     =   explode(',',$this->config['char_set']);

        if(!isset($this->config['master_num']) || empty($this->config['master_num'])) $this->config['master_num'] = 1;
        $m     =   floor(mt_rand(0,$this->config['master_num']-1));

        // 数据库读写是否分离
        if(isset($this->config['rw_separate']) && $this->config['rw_separate']){
            // 主从式采用读写分离
            if($db_write===true){
                // 主服务器写入
                $r  =   $m;
                if(isset($this->config['slave_no']) && is_numeric($this->config['slave_no'])) {// 指定服务器读
                    $r = $this->config['slave_no'];
                }
            }else{
                if(isset($this->config['slave_no']) && is_numeric($this->config['slave_no'])) {// 指定服务器读
                    $r = $this->config['slave_no'];
                }else{
                    // 读操作连接从服务器
                    $r = floor(mt_rand($this->config['master_num'],count($_config['hostname'])-1));   // 每次随机连接的数据库
                }
            }
        }else{
            // 读写操作不区分服务器
            $r = floor(mt_rand(0,count($_config['hostname'])-1));   // 每次随机连接的数据库
        }

        if($db_write===true){
            $db_master  =   array(
                'username'  =>  isset($_config['username'][$m])?$_config['username'][$m]:$_config['username'][0],
                'password'  =>  isset($_config['password'][$m])?$_config['password'][$m]:$_config['password'][0],
                'hostname'  =>  isset($_config['hostname'][$m])?$_config['hostname'][$m]:$_config['hostname'][0],
                'port'  =>  isset($_config['hostport'][$m])?$_config['hostport'][$m]:$_config['hostport'][0],
                'database'  =>  isset($_config['database'][$m])?$_config['database'][$m]:$_config['database'][0],
                'dsn'       =>  isset($_config['dsn'][$m])?$_config['dsn'][$m]:$_config['dsn'][0],
                'char_set'   =>  isset($_config['charset'][$m])?$_config['charset'][$m]:$_config['charset'][0],
            );
            $this->config = array_merge($this->config,$db_master);
        }else{
            $db_config = array(
                'username'  =>  isset($_config['username'][$r])?$_config['username'][$r]:$_config['username'][0],
                'password'  =>  isset($_config['password'][$r])?$_config['password'][$r]:$_config['password'][0],
                'hostname'  =>  isset($_config['hostname'][$r])?$_config['hostname'][$r]:$_config['hostname'][0],
                'port'  =>  isset($_config['hostport'][$r])?$_config['hostport'][$r]:$_config['hostport'][0],
                'database'  =>  isset($_config['database'][$r])?$_config['database'][$r]:$_config['database'][0],
                'dsn'       =>  isset($_config['dsn'][$r])?$_config['dsn'][$r]:$_config['dsn'][0],
                'char_set'   =>  isset($_config['charset'][$r])?$_config['charset'][$r]:$_config['charset'][0],
            );
            $this->config = array_merge($this->config,$db_config);
        }

        return $this->config;
    }


    /**
     * Parse the URL from the DSN string
     * Database settings can be passed as discreet
     * parameters or as a data source name in the first
     * parameter. DSNs must have this prototype:
     * $dsn = 'driver://username:password@hostname/database';
     */
    static private function parseDsn($dsnStr = ''){
        if( empty($dsnStr) ){return false;}
        $info = parse_url($dsnStr);
        if(!$info) {
            return false;
        }
        $dsn = array(
            'dbdriver'	=> $info['scheme'],
            'username'  =>  isset($info['user']) ? $info['user'] : '',
            'password'  =>  isset($info['pass']) ? $info['pass'] : '',
            'hostname'  =>  isset($info['host']) ? $info['host'] : '',
            'port'  =>  isset($info['port']) ? $info['port'] : '',
            'database'  =>  isset($info['path']) ? substr($info['path'],1) : '',
            'char_set'   =>  isset($info['fragment'])?$info['fragment']:'utf8',
            'dbprefix' =>  'iwide_',
            'pconnect' =>  false,
            'db_debug' =>  true,
            'cache_on' =>  false,
            'cachedir' =>  '',
            'dbcollat' =>  'utf8_general_ci',
            'swap_pre' =>  '',
            'encrypt' =>  false,
            'compress' =>  false,
            'stricton' =>  false,
            'save_queries' =>  true,
        );

        if(isset($info['query'])) {
            parse_str($info['query'],$dsn['params']);
        }else{
            $dsn['params']  =   array();
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
     * @param Array GET 参数（过滤，排序，分页）
     * @param String $format 有2种数据规格：
     * 		'array':返回datatable组件所需要的数组形式
     * 		'':返回普通的对象数组
     * grid过滤，排序，分页时，过滤参数
     * 如需定制，请重写此函数
     */
    public function filter( $params=array(), $select= array(), $format='array' )
    {
        $exp=array(' >',' <',' !=');
        $table= $this->table_name();
        $where= $where_in= array();
        $dbfields= array_values($fields= $this->_shard_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                    if($_exp && isset($v[1]))
                        $where[$k.$_exp]=$v[1];
                    else
                        $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }
        }

        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else
            $pk= $this->table_primary_key();
        $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        $current_page= isset($params['page_num'])? $params['page_num']: 1;

        if(count($select)==0) {
            $select= $this->grid_fields();
        }
        $select= count($select)==0? '*': implode(',', $select);

        //echo $select;die;
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }
        $total= $this->_shard_db()->select(" {$select} ")->get_where($table, $where)->num_rows();
        //echo $total;

        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }
        $result= $this->_shard_db()->select(" {$select} ")->order_by($sort)
            ->limit($page_size, $offset)->get_where($table, $where)
            ->result_array();
        if($this->input->get('debug')==1){
            echo $this->_shard_db()->last_query();echo '<br/>';
        }
        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid');
            foreach ($result as $k=> $v){
                //判断combobox类型需要对值进行转换
                foreach($field_config as $sk=>$sv){
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]])){
                            $v[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        }
                        else $v[$sk]= '--';
                    }

                    if($field_config[$sk]['type']=='datetime') {
                        if(isset($v[$sk]) && strpos($v[$sk],'-')===false){
                            $v[$sk]= date('Y-m-d H:i:s',$v[$sk]);
                        }
                        else $v[$sk]= '--';
                    }

                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $v[$sk]= call_user_func_array ($fun, $funp);
                    } else if( $field_config[$sk]['function'] ) {
                        $funp= explode('|', $field_config[$sk]['function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $v[$sk]= call_user_func_array ($fun, $funp);
                    }
                }//---

                $el= array_values($v);
                $el['DT_RowId']= $v[$this->table_primary_key()];
                $tmp[]= $el;
            }
            $result= $tmp;
        }

        return array(
            'total'=>$total,
            'data'=>$result,
            'page_size'=>$page_size,
            'page_num'=>$current_page,
        );
    }
}

/**
 * Initialize the database
 *
 * @category	Database
 * @author	EllisLab Dev Team
 * @link	http://codeigniter.com/user_guide/database/
 *
 * @param 	string|string[]	$params
 * @param 	bool		$query_builder_override
 *				Determines if query builder should be used or not
 */
function &MemberDB($params = '',$query_builder_override = NULL)
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
    elseif ( ! isset($query_builder) && isset($active_record)) {
        $query_builder = $active_record;
    }

    require_once(BASEPATH.'database/DB_driver.php');

    if ( ! isset($query_builder) OR $query_builder === TRUE) {
        require_once(BASEPATH.'database/DB_query_builder.php');
        if ( ! class_exists('CI_DB', FALSE)) {
            /**
             * CI_DB
             *
             * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
             *
             * @see	CI_DB_query_builder
             * @see	CI_DB_driver
             */
            class CI_DB extends CI_DB_query_builder { }
        }
    } elseif ( ! class_exists('CI_DB', FALSE)) {
        /**
         * @ignore
         */
        class CI_DB extends CI_DB_driver { }
    }

    // Load the DB driver
    $driver_file = BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';

    file_exists($driver_file) OR show_error('Invalid DB driver');
    require_once($driver_file);

    // Instantiate the DB adapter
    $driver = 'CI_DB_'.$params['dbdriver'].'_driver';
    $DB = new $driver($params);

    // Check for a subdriver
    if ( ! empty($DB->subdriver)) {
        $driver_file = BASEPATH.'database/drivers/'.$DB->dbdriver.'/subdrivers/'.$DB->dbdriver.'_'.$DB->subdriver.'_driver.php';

        if (file_exists($driver_file)) {
            require_once($driver_file);
            $driver = 'CI_DB_'.$DB->dbdriver.'_'.$DB->subdriver.'_driver';
            $DB = new $driver($params);
        }
    }

    $DB->initialize();
    return $DB;
}