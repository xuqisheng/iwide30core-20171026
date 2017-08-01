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
$member_group = 'iwide_vip';
$member_group_w = 'iwide_vipw';
$active_group = 'iwide_rw';
$query_builder = TRUE;

$db['default'] = array(
		'dsn'	=> '',
		'hostname' => '127.0.0.1', //兼容性问题，请勿用 localhost
		'database' => 'iwide30dev_2016011813',
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

/** ************ 备份数据库连接 ************* **/
$db[$active_group]['failover'][0] = $db['default'];
$db[$active_group]['failover'][0]['hostname'] = '127.0.0.1'; //兼容性问题，请勿用 localhost
$db[$active_group]['failover'][0]['database'] = 'iwide30dev';
// $db[$active_group]['failover'][0]['username'] = '';
// $db[$active_group]['failover'][0]['password'] = '';


/** ************ 当前数据库连接 ************* **/
$db[$active_group] = $db['default'];
$db[$active_group]['hostname'] = '30.iwide.cn'; //兼容性问题，请勿用 localhost
$db[$active_group]['database'] = 'iwide30dev_2016011813'; //兼容性问题，请勿用 localhost
$db[$active_group]['username'] = 'iwide30dev';
$db[$active_group]['password'] = 'jcSYwAEBahq3taFB';


/** ************ 多数据库连接配置 ************* **/
$db['iwide_r1'] = $db[$active_group];
// $db['iwide_r1']['hostname'] = 'iwide30r1.mysql.rds.aliyuncs.com'; //兼容性问题，请勿用 localhost
// $db['iwide_r1']['database'] = 'iwide30dev'; //兼容性问题，请勿用 localhost


$db['ticket_center'] = $db[$active_group];
$db['ticket_center']['database'] = 'ticket_center';

$db['iwide_soma'] = $db[$active_group];
$db['iwide_soma']['database'] = 'iwide30soma';
$db['iwide_soma_r'] = $db['iwide_soma'];

$db['member_write'] = $db[$active_group];
$db['member_read'] = $db[$active_group];

//$db['price'] = $db[$active_group];
//$db['price']['hostname'] = 'iwide30soma.mysql.rds.aliyuncs.com';
//$db['price']['database'] = 'iwide30price';
//$db['price']['username'] = 'iwide30price';
//$db['price']['password'] = 'kk6593%jdfk87tH';
$db['price'] = $db[$active_group];
$db['price']['hostname'] = '120.27.132.97';
$db['price']['database'] = 'iwide30price';
$db['price']['username'] = 'iwide30price';
$db['price']['password'] = '@iwide30price123';



$db[$member_group] = $db[$active_group];
$db[$member_group]['hostname'] = '30.iwide.cn';    //兼容性问题，请勿用 localhost
$db[$member_group]['database'] = 'iwide30vip';  //新会员VIP数据库镜像
$db[$member_group]['username'] = 'vip';
$db[$member_group]['password'] = 'jcSYwAEBahq3taFB';

$db[$member_group_w] = $db[$active_group];
$db[$member_group_w]['hostname'] = '30.iwide.cn';    //兼容性问题，请勿用 localhost
$db[$member_group_w]['database'] = 'iwide30vip';  //新会员VIP数据库镜像
$db[$member_group_w]['username'] = 'vip';
$db[$member_group_w]['password'] = 'jcSYwAEBahq3taFB';



