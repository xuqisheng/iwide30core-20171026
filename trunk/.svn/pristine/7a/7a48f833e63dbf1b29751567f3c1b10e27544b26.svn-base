<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('show_compose'))
{
    /**
     * 效果：豪华海景房1间, 温泉票2张, 自助2张
     * array(
     *     'compose_1'=> array('content'=>'商品1', 'num'=> '2'),
     *     'compose_2'=> array('content'=>'商品2', 'num'=> '4'),
     *     'compose_2'=> array('content'=>'商品3', 'num'=> '3'),
     * )
     */
    function show_compose($string)
    {
        $array= unserialize($string);
        $string= '';
        if($array && is_array($array)){
            foreach ($array as $k=>$v){
                if($v['content'] && $v['num'] ) $string.= $v['content']. '*'. $v['num']. ', ';
            }
        }
        return substr($string, 0, -2);
    }
}

if ( ! function_exists('show_date'))
{
    /**
     * array unique_rand( int $min, int $max, int $num )
     * 生成一定数量的不重复随机数
     * $min 和 $max: 指定随机数的范围
     * $num: 指定生成数量
     */
    function show_date($string)
    {
        if($string) return date('Y年m月d日', strtotime($string) );
        else '--';
    }
}

if ( ! function_exists('write_log'))
{
    function write_log( $content, $file=NULL, $path=NULL )
    {
        if(!$file) $file= date('Y-m-d'). '.txt';
        if(!$path) $path= APPPATH. 'logs'. DS. 'soma'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $ip. "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
}

if ( ! function_exists('show_face_img'))
{
    /**
     * 以注释形式显示字符
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function show_face_img($url, $size='100', $height='-' )
    {
        if(!$url ) $url= '/'. FD_PUBLIC. '/mall/common/cat_img/default.png';
        if($height=== '-') $h_html= " height='{$size}'";
        else $h_html='';
        return "<img src='{$url}' width='{$size}' $h_html />";
    }
}

