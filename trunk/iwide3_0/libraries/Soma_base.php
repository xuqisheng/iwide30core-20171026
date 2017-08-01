<?php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

defined('BASEPATH') OR exit('No direct script access allowed');

class Soma_base
{
    //正确的状态标记
    const STATUS_TRUE	=1;
    const STATUS_FALSE	=2;

    /**
     * @var array
     */
    private static $_objs = array();  //对象容器

    /**
     * Soma_base constructor.
     */
    public function __construct()
	{
	    return $this;
	}

	/**
	 * @param string $className
	 * @return mixed|Soma_base
     *
     * todo 这里的参数没有意思，这里的objs不需要
	 * @author renshuai  <renshuai@mofly.cn>
	 */
	public static function inst($className=__CLASS__)
	{
		if(isset(self::$_objs[$className])) {
			return self::$_objs[$className];
		} else {
			return self::$_objs[$className] = new $className(null);
		}
	}
	
	public static function get_status_options( $alias= array() )
	{
	    if( count($alias)>1 ){
	        $array= $alias;
	        
	    } else {
	        $array= array(
	            self::STATUS_TRUE=> '正常',
	            self::STATUS_FALSE=> '禁用',
	        );
	    }
		return $array;
	}
	public static function get_status_label($value=null)
	{
		$array = self::get_status_options();
		if ($value===null || !isset($array[$value]) ) {
			return '-';
		} else {
			return $array[$value];
		}
	}

	/**
	 * 把请求/返回记录记入文件
	 * @param String $content
	 * @param string $type
	 */
	protected function _write_log( $content, $type='plain' )
	{
	    $CI = & get_instance();

        $handler = new StreamHandler(APPPATH . 'logs/soma/exception/log_' . date('Y-m-d') . '.log', Logger::DEBUG);
        $CI->monoLog->setHandlers(array($handler));

        $ip = $CI->input->ip_address();
        $content = 'type :' . $type . ' ip:' . $ip . ' content:' .$content;

        $CI->monoLog->info($content);
	}

	public function show_exception($message, $is_silent= FALSE )
	{
	    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == 'xmlhttprequest' ) {
	        $message= json_encode(  array('status'=>self::STATUS_FALSE, 'message'=>$message)  );
	        $this->_write_log($message, 'Ajax');
	        die($message);
	        
	    } else {
	        if( $is_silent) {
	            return '';
	            
	        } else {
	            $this->_write_log($message, 'Exception');
	            die($message);
	        }
	    }
	}

	/**
	 * 检测Cache Redis的健康状况
	 * @return boolean
	 */
	public function check_cache_redis()
	{
	    $CI= & get_instance();
	    if ( $CI->config->load('redis', TRUE, TRUE) ) {
	        $config = $CI->config->item('redis');
	    } else {
	        return FALSE;
	    }
	    $redis= new Redis();
	    $success = $redis->connect($config['host'], $config['port'], $config['timeout']);
	    
	    if( $success ){
	        return TRUE;
	    } else {
	        return FALSE;
	    }
	}
	
	public function str_encrypt( $data, $at_url= FALSE )
	{
        $CI = & get_instance();
	    $CI->load->helper('encrypt');
        $encrypt_util= new Encrypt();
        $content= $encrypt_util->encrypt( $data );
        return $at_url? base64_encode($content): $content;
	}
	public function str_decrypt( $string, $at_url= FALSE  )
	{
	    $string= $at_url? base64_decode($string): $string;
        $CI = & get_instance();
	    $CI->load->helper('encrypt');
	    $encrypt_util= new Encrypt();
	    return $content= $encrypt_util->decrypt( $string );
	}

	public function __get($key)
	{
		if(isset($this->$key))
		{
			return $this->$key;
		}
		return get_instance()->$key;
	}
}
