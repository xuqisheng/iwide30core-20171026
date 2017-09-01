<?php
/**
 * Created by PhpStorm.
 * User: vvanjack
 * Date: 2016/12/26
 * Time: 20:06
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 保存文件到本地
 * @param 文件路径 $url
 * @param 保存本地路径 $savePath
 * @return string
 */
function downloadFile($url,$savePath='')
{
    $fileName = getUrlFileExt($url);
    $fileName = rand(0,1000) . '.' .$fileName;
    $file = file_get_contents($url);
    file_put_contents($savePath.'/'.$fileName,$file);
    chmod($savePath .'/' . $fileName, 0777);
    return $savePath .'/' . $fileName;
}

/**
 * 获取文件扩展名
 * @param 网页URL $url
 * @return string
 */
function getUrlFileExt($url)
{
    $ary = parse_url($url);
    $file = basename($ary['path']);
    $ext = explode('.',$file);
    return $ext[1];
}