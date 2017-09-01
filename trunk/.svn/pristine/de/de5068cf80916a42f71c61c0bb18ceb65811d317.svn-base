<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/

$active_group = 'iwide_rw';
$query_builder = TRUE;

$db['default'] = array(
		'dsn'	=> '',
		'hostname' => '120.24.222.162', //兼容性问题，请勿用 localhost
		'database' => 'iwide30dev',
		'username' => 'iwide30dev',
		'password' => 'jcSYwAEBahq3taFB',
		'port'	   => '3306',
		'dbdriver' => 'mysqli',
		'dbprefix' => 'iwide_',
		'pconnect' => FALSE,
		'db_debug' => TRUE,
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'save_queries' => TRUE
);

/** ************ 灾备数据库连接 ************* **/
$db[$active_group]['failover'][0] = $db['default'];
$db[$active_group]['failover'][0]['hostname'] = '120.24.222.162'; //兼容性问题，请勿用 localhost
// $db[$active_group]['failover'][0]['database'] = '';
// $db[$active_group]['failover'][0]['username'] = '';
// $db[$active_group]['failover'][0]['password'] = '';

/** ************ 当前数据库连接 ************* **/
$db[$active_group] = $db['default'];
$db[$active_group]['hostname'] = '120.24.222.162'; //兼容性问题，请勿用 localhost



/** ************ 多数据库连接配置 ************* **/
$db['iwide_r1'] = $db[$active_group];
$db['iwide_r1']['hostname'] = '120.24.222.162'; //兼容性问题，请勿用 localhost

$db['member_write'] = $db[$active_group];
$db['member_read'] = $db[$active_group];



/** 此代码用于部分功能仅在生产环境中出现 **/
if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
    //生产环境关闭
    $db[$active_group]['db_debug']= FALSE;

} elseif( isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['PATH_INFO']) ) {
    //显示测试效果的ip白名单
    $match_ip= array(
        '113.67.132.60',
    );
    //在测试环境中调试个别页面代码
    $match_url= array(
        //'/index.php/member/memberlist/grid',
    );
    if( count($match_ip)>0 && in_array($_SERVER['REMOTE_ADDR'], $match_ip)
        && count($match_url)>0 && in_array($_SERVER['PATH_INFO'], $match_url) )
    {
        //定制化数据库链接参数
        //$db[$active_group]['hostname'] = 'localhost';
        $db[$active_group]['database'] = 'iwide30dev_2016011813';  //生产环境1月18号13点 数据库镜像
        $db[$active_group]['username'] = 'iwide30dev';
        $db[$active_group]['password'] = 'jcSYwAEBahq3taFB';

        if( !defined('IS_SHOW_DBV') ){
            echo '数据版本：'. substr($db[$active_group]['database'], -10);
            define('IS_SHOW_DBV', TRUE);
        }
    }
}
/** 此代码用于部分功能仅在生产环境中出现 **/

