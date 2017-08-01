<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
echo BASEPATH. '<br/>'; 	//F:\web\host\test.mofly\htdocs\system\
echo APPPATH. '<br/>'; 		//F:\web\host\test.mofly\htdocs\demo\
echo VIEWPATH. '<br/>'; 	//F:\web\host\test.mofly\htdocs\demo\views\
echo FCPATH. '<br/>'; 		//F:\web\host\test.iwide.cn\htdocs\www_front/
echo SYSDIR. '<br/>'; 		//system	defined in index.php
echo SELF. '<br/>'; 		//index.php
 */
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('FD_PUBLIC', 'public');
define('ADMINHTML', 'adminhtml');
define('FULL_ACCESS', 'ALL_PRIVILEGES');
define('CORP_URL', 'www.iwide.cn');
define('CORP_NAME', '金房卡');
define('VERSION', '3.0');

if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' )
    define('CONSTANTS_BSN', '月饼');
else
    define('CONSTANTS_BSN', '套票');

//模块的面包屑名称
define('NAV_PRIVILEGE', '系统管理');
define('NAV_BASIC', '基础功能');
define('NAV_SOMA', CONSTANTS_BSN. '管理');
define('NAV_PACKAGE_GROUPON', CONSTANTS_BSN. '拼团');
define('NAV_MALL', '社交商城');
define('NAV_HOTEL', '酒店订房');
define('NAV_HOTELS', '酒店列表');
define('NAV_HOTEL_ROOM_STATUS', '房态维护');

//酒店社交相关常量
define("DOMAIN", trim($_SERVER['HTTP_HOST']));

//会员接口地址设定  代码更新下来后，本地人员自己修改，但是不要上传！否则会报错(Frandon 	)
if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
    define('PMS_PATH_URL', 'http://member.iwide.cn/vapi/');
    define('INTER_PATH_URL', 'http://member.iwide.cn/api/');
    define('INTER_PATH_URL2', 'http://member.iwide.cn/api2/');

} elseif( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='testing' ) {
    define('PMS_PATH_URL', 'http://vip.iwide.cn/vapi/');
	define('INTER_PATH_URL', 'http://vip.iwide.cn/api/');
	define('INTER_PATH_URL2', 'http://vip.iwide.cn/api2/');

} else{
	define('PMS_PATH_URL', 'http://vip.iwide.cn/vapi/');
    define('INTER_PATH_URL', 'http://vip.iwide.cn/api/');
    define('INTER_PATH_URL2', 'http://vip.iwide.cn/api2/');
}