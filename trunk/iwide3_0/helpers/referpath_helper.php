<?php
function referurl($type,$name,$location=2,$media_path=''){
	$CI=& get_instance();
	$url='';
	$path=explode('/',$media_path,2);
	switch($type){
		case 'css':
			if($location==1){
				$url='<link rel="stylesheet" href="'.get_cdn_url('public/'.$media_path.'/styles/'.$name).'" type="text/css"/>';
			}
			else if($location==2){
				$url='<link rel="stylesheet" href="'.get_cdn_url('public/'.$path[0].'/public/styles/'.$name).'" type="text/css"/>';
			}
			else if($location==3){
				$url='<link rel="stylesheet" href="'.get_cdn_url('public/media/styles/'.$name).'" type="text/css"/>';
			}
			break;
		case 'js':	
			if($location==1){
				$url='<script type="text/javascript" src="'.get_cdn_url('public/'.$media_path.'/scripts/'.$name).'"></script>';
			}
			else if($location==2){
				$url='<script type="text/javascript" src="'.get_cdn_url('public/'.$path[0].'/public/scripts/'.$name).'"></script>';
			}
			else if($location==3){
				$url='<script type="text/javascript" src="'.get_cdn_url('public/media/scripts/'.$name).'"></script>';
			}
			break;
		case 'img':	
			if($location==1){
				$url=get_cdn_url('public/'.$media_path.'/images/'.$name);
			}
			else if($location==2){
				$url=get_cdn_url('public/'.$path[0].'/public/images/'.$name);
			}
			else if($location==3){
				$url=get_cdn_url('public/media/images/'.$name);
			}
			break;
		case 'sound':	
			if($location==1){
				$url=get_cdn_url('public/'.$media_path.'/sounds/'.$name);
			}
			else if($location==2){
				$url=get_cdn_url('public/'.$path[0].'/public/sounds/'.$name);
			}
			else if($location==3){
				$url=get_cdn_url('public/media/sounds/'.$name);
			}
			break;
		default:
			break;
	}	
	return $url;
}
/**后台根据传入资源名返回对应资源路径
 * @param string $name 引用资源名
 * @param string $path 预定义(ADMIN)，或自定义路径(末尾不需加斜杠)
 * @param string $filename manifest文件名
 * @return string 对应资源名，若manifest中不存在则原样返回
 */
function refer_res($name, $path = 'ADMIN', $filename = 'manifest.json', $version = '') {

    $paths = array (
            'ADMIN' => 'public/admin/',
			'SOMA' => 'public/soma/vue/'.$version.'/',
            //更新完要删除
            'SOMAACCOR' => 'public/soma/vue_accor/'.$version.'/',
            'SOMAOLD' => 'public/soma/vueold/',
            'SOMAGIFT' => 'public/soma/vue_gift/',
    );

    $config = & get_config ();
    $res_domain = '';
    if (WEB_AREA == 'front') {
        if (isset ( $config ['cdn_host'] ) && $config ['cdn_host'] != "" && isLoadCdnFileIp()) {
            $res_domain = $config ['cdn_host'] . '/public/';
            $filename = 'manifest-cdn.json';
        } else {
            $CI = & get_instance ();
            $res_domain = $CI->config->base_url ().'public/';
        }
    }
    $filename = isset ( $paths [$path] ) ? $paths [$path] . $filename : $path . '/' . $filename;

    if (file_exists ( $filename )) {
        $file = file_get_contents ( $filename );
        $manifest = json_decode ( $file, TRUE );
        return isset ( $manifest [$name] ) ? $res_domain . $manifest [$name] : $res_domain . $name;
    }
    else{
        $filename = 'public/soma/vue/'.$filename;
        $file = file_get_contents ( $filename );
        $manifest = json_decode ( $file, TRUE );
        return isset ( $manifest [$name] ) ? $res_domain . $manifest [$name] : $res_domain . $name;
    }

    return $name;
}
/**
 * 取cdn
 * @param unknown $url
 * @return string
 */
function get_cdn_url($url){
	
	include 'public/version.php';

	$ver = isset($js_css_version)?$js_css_version:"0";
	
	$config = & get_config();
	
	$inter_id = isset($_GET['id'])?$_GET['id']:"";
	
	// && $inter_id == "a426755343"
	
	if(isset($config['cdn_host']) && $config['cdn_host'] != "" && isLoadCdnFileIp()){
		
		return $config['cdn_host']."/".$url."?v={$ver}";
		
				
	}else{
		$CI=& get_instance();
		return $CI->config->base_url( $url );
	}
}


/**
 * 替换http参数
 * @param $url
 * @param $key
 * @param $value
 * @return string
 * @author liguanglong  <liguanglong@mofly.cn>
 */
function urlSetValue($url, $key, $value)
{
    $a=explode('?',$url);
    $url_f=$a[0];
    $query=$a[1];
    parse_str($query,$arr);
    $arr[$key]=$value;
    return $url_f.'?'.http_build_query($arr);
}


/**
 *
 * @return bool
 * @author liguanglong  <liguanglong@mofly.cn>
 */
function isLoadCdnFileIp(){

    $is = true;

    $host = $_SERVER['SERVER_ADDR'];
    //测试、预发布、灰度（商城）
    $isLoadLocalFileIps = ['120.27.132.97', '120.27.136.161', '118.31.113.217'];
    if(in_array($host, $isLoadLocalFileIps)){
        $is = false;
    }

    return $is;
}