<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use App\controllers\front\traits\Controller;
use App\controllers\front\traits\Soma;

class MY_Controller extends CI_Controller
{
    use Controller, Soma;

    //if change frontend skin you must define it.
    //  eg: “ $sub_template= 'bgy' ”
    protected $sub_template = '';
    protected $priv_dir = 'privilege';

    protected $module = '';
    protected $controller = '';
    protected $action = '';

    public function __construct()
    {
        parent::__construct();
        $this->initMonoLog();
        $this->_init_skin_url();
        $this->_init_router();

        /* $arr= config_item('disable_session_module');
         if( is_array($arr) && in_array($this->module, $arr) ){
             //不载入session组件

         } else {
             $this->load->library('session');
         } */

        //$this->_update_db();	//Must set it after parent::__construct();
        //$this->_load_cache();
        return $this;
    }

    protected function _update_db()
    {
        $this->load->library('migration');
        //$this->migration->latest();   //Up to the latest migration file
        //$this->migration->current();	//Up to the migration config file version
        //$this->migration->version(5);
        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        }
        return $this;
    }

    protected function _init_skin_url()
    {
        $skin_dir = FCPATH . FD_PUBLIC . DS . $this->sub_template;
        $view_dir = VIEWPATH . $this->sub_template;
        if (!$this->sub_template || !file_exists($skin_dir) || !file_exists($view_dir)) {
            $this->sub_template = 'default';
        }

        // 仅适用于后台获取前台public目录，如  htdocs\www_front/public\
        if (defined('WEB_AREA') && WEB_AREA == 'admin') {
            define('FRONT_FD_', FCPATH . '..' . DS . 'www_front' . DS . FD_PUBLIC . DS);
        }

        // 前台调用效果：htdocs\www_front/public\
        // 后台调用效果：htdocs\www_admin/public\
        define('FD_', FCPATH . FD_PUBLIC . DS);
        // 前台调用效果：htdocs\www_front/public\bgy\images\
        // 后台调用效果：htdocs\www_admin/public\bgy\images\
        define('FD_IMG', FCPATH . FD_PUBLIC . DS . $this->sub_template . DS . 'images' . DS);
        define('FD_CSS', FCPATH . FD_PUBLIC . DS . $this->sub_template . DS . 'css' . DS);
        define('FD_JS', FCPATH . FD_PUBLIC . DS . $this->sub_template . DS . 'js' . DS);
        define('FD_MEDIA', FCPATH . FD_PUBLIC . DS . 'media' . DS);

        //http://tf.iwide.cn/public/bgy/images
        define('URL_IMG', base_url(FD_PUBLIC . '/' . $this->sub_template . '/images/'));
        define('URL_CSS', base_url(FD_PUBLIC . '/' . $this->sub_template . '/css/'));
        define('URL_JS', base_url(FD_PUBLIC . '/' . $this->sub_template . '/js/'));
        define('URL_MEDIA', base_url(FD_PUBLIC . '/' . 'media/'));

        //echo FD_IMG;
        //echo URL_IMG;
        return $this;
    }

    protected function _init_router()
    {
        $URI =& load_class('URI', 'core', NULL);
        $segments = $URI->segments;
        $this->module = $segments[1];
        $this->controller = isset($segments[2]) ? $segments[2] : 'index';
        $this->action = isset($segments[3]) ? $segments[3] : 'index';
        return;
    }

    protected function _load_cache($name = 'Cache')
    {
        $success = Soma_base::inst()->check_cache_redis();
        if (!$success) {
            //redis故障关闭cache
            Soma_base::inst()->show_exception('当前访问用户过多，请稍后再试！', TRUE);
        }
        if (!$name || $name == 'cache') //不能为小写cache
        {
            $name = 'Cache';
        }

        $this->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'frt_'),
            $name
        );
        return $this->$name;
    }

    protected function _load_view($file, $data = array(), $return = FALSE)
    {
        $data['tpl'] = $this->sub_template;
        return $this->load->view($this->sub_template . '/' . $file, $data, $return);
    }

    public function _set_template($name = 'default')
    {
        $this->sub_template = $name;
        return $this;
    }

    public function _get_template()
    {
        return $this->sub_template;
    }

    protected function _redirect($url)
    {
        redirect($url, 'location', 301);
        die;
    }

    /**
     * @author libinyan@mofly.cn
     */
    protected function _ftp_server($env = 'prod')
    {
        $this->load->library('ftp');
        if ($env == 'test') {
            $configftp['hostname'] = config_item('ftp_hostname');
            $configftp['username'] = config_item('ftp_username');
            $configftp['password'] = config_item('ftp_password');
            $configftp['passive'] = config_item('ftp_passive');
            $configftp['port'] = config_item('ftp_port');
            $configftp['debug'] = config_item('ftp_debug');
            $result = $this->ftp->connect($configftp);

            $this->ftp->floder = config_item('ftp_floder');
            $this->ftp->weburl = config_item('ftp_url');

            if (isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
                //生产环境不能开启debug模式
            } else {
                $this->ftp->debug = TRUE;
            }
        } else {
            $configftp['hostname'] = config_item('ftphostname');
            $configftp['username'] = config_item('ftpusername');
            $configftp['password'] = config_item('ftppassword');
            $configftp['passive'] = config_item('ftppassive');
            $configftp['port'] = config_item('ftpport');
            $configftp['debug'] = config_item('ftpdebug');
            $result = $this->ftp->connect($configftp);

            $this->ftp->floder = config_item('ftpfloder');
            $this->ftp->weburl = config_item('ftpurl');

            if (isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
                //生产环境不能开启debug模式
            } else {
                $this->ftp->debug = TRUE;
            }
        }
        //var_dump($this->ftp);
        return $this->ftp;
    }

    /**
     * @author libinyan@mofly.cn
     * @param  [type]  $content  [二维码内容]
     * @param  boolean $filename [生成图片名，文件名空则直接显示图片，不保存文件]
     * @param  integer $size [图片大小]
     * @param  integer $margin [白边举例]
     * @return [type]
     */
    public function _get_qrcode_png($content, $filename = FALSE, $size = 5, $margin = 1, $base_path = FALSE)
    {
        $this->load->helper('phpqrcode');
        if ($filename === FALSE) {
            QRcode::png($content, FALSE, 'Q', $size, $margin, TRUE);
            return TRUE;

        } else {
            if ($base_path == FALSE) {
                $base_path = 'qrcode' . '/' . $this->module . '/' . $this->controller . '/' . $this->action;
            }
            $path = FCPATH . FD_PUBLIC . '/' . $base_path;
            //echo $path;die;
            if (!file_exists($path)) {
                @mkdir($path, 755, TRUE);
            }
            $file = $path . '/' . $filename . '.png';
            //echo $file;die;
            QRcode::png($content, $file, 'Q', $size, $margin);

//ftp开始，初始化测试服务器ftp
            if (isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
                $this->ftp = $this->_ftp_server('prod');
            } else {
                $this->ftp = $this->_ftp_server('test');
            }
//$to_file = '/public_html'. $file_system_path;
            $to_file = $this->ftp->floder . FD_PUBLIC . '/' . $base_path . '/';
//echo $to_file;die;  //   /public_html/public/qrcode/mall/test/qr/
            $isdir = $this->ftp->list_files('./public_html/public/qrcode/mall');
            if (empty($isdir)) {
                $newpath = '/';
                $arrpath = explode('/', $to_file);
                foreach ($arrpath as $v) {
                    if ($v && $v != $this->ftp->floder) {
                        $newpath .= $v . '/';
                        $isdirchild = $this->ftp->list_files($newpath);
                        if (empty($isdirchild)) {
                            $this->ftp->mkdir($newpath);
                        }
                    }
                }
            }
            $upload_name = $filename . '.png';
            $to_file = str_replace(array('\\', '//'), array('/', '/'), $to_file . '/' . $upload_name);
            if (!file_exists($file)) {
                echo '原上传文件不存在！';
            } else {
                $result = $this->ftp->upload($file, $to_file, 'binary', 0775);
            }
            $this->ftp->close();
//ftp结束

//@unlink($file);   //二维码留底，不删除
            $upload_url = $this->ftp->weburl . '/' . FD_PUBLIC . '/' . $base_path . '/' . $upload_name;

            return $upload_url;
        }
    }

    /**
     * @param $serviceName
     * @param string $prefix
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function serviceAlias($serviceName, $prefix = 'soma')
    {
        return $prefix . '_' . strtolower($serviceName);
    }

    /**
     * @param string $serviceName
     * @param string $prefix
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function serviceName($serviceName, $prefix = 'soma')
    {
        return "$prefix/$serviceName";

    }

}

//管理后台用Controller by libinyan
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Admin.php";

//前端专用Controller by liganghao
require_once dirname(__FILE__) .DIRECTORY_SEPARATOR ."MY_Front.php";

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "MY_Front_Iapi.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "MY_Admin_Iapi.php";