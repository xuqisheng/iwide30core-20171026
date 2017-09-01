<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_ajax_request'))
{
    /**
     * 判断请求是否为ajax请求
     * @return boolean
     */
    function is_ajax_request()
    {
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
            strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest" )
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
if ( ! function_exists('front_site_url'))
{
    function front_site_url($inter_id=NULL, $real_domain=TRUE )
    {
        if( $real_domain==TRUE && defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
            if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ) {
                return 'http://mk2016.iwide.cn';
            } else {
                return 'http://mooncake.iwide.cn';
            }
        }
        //本地调试
        if($_SERVER['HTTP_HOST']== 'ta.iwide.cn') return 'http://tf.iwide.cn';
        //测试服务器
        //else if($_SERVER['HTTP_HOST']== '30.iwide.cn') return 'http://ihotels.iwide.cn';
        else {
            $CI= &get_instance();
            $CI->load->model('wx/publics_model');
            $model= $CI->publics_model->get_public_by_id($inter_id);
            if(isset($model['domain']) && $model['domain'] ){
                $domain= $model['domain'];
            } else {
                $domain= 'ihotels.iwide.cn';
            }
            return 'http://'. $domain;
        }
    }
}
if ( ! function_exists('file_site_url'))
{
    function file_site_url()
    {
        //本地调试
        if($_SERVER['HTTP_HOST']== 'ta.iwide.cn') return 'http://ta.iwide.cn';
        //测试服务器
        //else if($_SERVER['HTTP_HOST']== '30.iwide.cn') return 'http://30.iwide.cn';
        else {
            //return 'http://mp.iwide.cn';
            return 'http://file.iwide.cn';
        }
    }
}
if ( ! function_exists('show_admin_head'))
{
    /**
     * 以注释形式显示字符
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function show_admin_head($url, $size='100' )
    {
        if(!$url && defined('URL_MEDIA')) $url= '/'. FD_PUBLIC. '/AdminLTE/dist/img/iwide_logo.png';
        return "<img src='{$url}' width='{$size}' height='{$size}' />";
    }
}
if ( ! function_exists('show_cat_img'))
{
    /**
     * 以注释形式显示字符
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function show_cat_img($url, $size='100', $height='-' )
    {
        if(!$url ) $url= '/'. FD_PUBLIC. '/mall/common/cat_img/default.png';
        if( !preg_match('/^http.*/i', $url) ) $url= file_site_url(). $url;
        if($height=== '-') $h_html= " height='{$size}'";
        else $h_html='';
        return "<img src='{$url}' width='{$size}' $h_html />";
    }
}

if(!function_exists('base64_url_encode')) {
    /**
     * 将数据转成base64编码，用于在url内作为参数传输
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    function base64_url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
if(!function_exists('base64_url_decode')) {
    /**
     * 将url中base64编码后的数据解码
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    function base64_url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    }
}