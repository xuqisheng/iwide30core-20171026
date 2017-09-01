<?php
/**
 * 清风基础函数库 * 
 */
function dhtmlspecialchars($string) {
    return is_array($string) ? array_map('dhtmlspecialchars', $string) : str_replace('&amp;', '&', htmlspecialchars($string, ENT_QUOTES));
}

function daddslashes($string) {
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = daddslashes($val);
	return $string;
}

function dstripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = dstripslashes($val);
	return $string;
}

function dsafe($string) {
	if(is_array($string)) {
		return array_map('dsafe', $string);
	} else {
		if(strlen($string) < 20) return $string;
		$match = array("/&#([a-z0-9]+)([;]*)/i", "/(j[\s\r\n\t]*a[\s\r\n\t]*v[\s\r\n\t]*a[\s\r\n\t]*s[\s\r\n\t]*c[\s\r\n\t]*r[\s\r\n\t]*i[\s\r\n\t]*p[\s\r\n\t]*t|jscript|js|vbscript|vbs|about|expression|script|frame|link|import)/i", "/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i");
		$replace = array("", "<d>\\1</d>", "on\n\\1");
		return preg_replace($match, $replace, $string);
	}
}

function dtrim($string, $js = false) {
	$string = str_replace(array(chr(10), chr(13)), array('', ''), $string);
	return $js ? str_replace("'", "\'", $string) : $string;
}

function dheader($url) {
	header('location:'.$url);
}

function qfdate($t) {
	$t = intval($t);
	if ($t<1) {
		$t = time();
	}
	return date('Y-m-d H:i:s',$t);
}

function qfshow($d) {
	
	if ($d=='1') {
		return '显示';
	} else {
		return '隐藏';
	}
	
}

function qfempty($d) {
	if ($d=='1') {
		return '不为空';
	}
	else {
		return '可为空';
	}
}

function dsubstr($string, $length, $suffix = '', $start = 0) {
	if($start) {
		$tmp = dsubstr($string, $start);
		$string = substr($string, strlen($tmp));
	}
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array('&quot;', '&lt;', '&gt;'), array('"', '<', '>'), $string);
	$length = $length - strlen($suffix);
	$str = '';
	if(strtolower(DT_CHARSET) == 'utf-8') {
		$n = $tn = $noc = 0;
		while($n < $strlen)	{
			$t = ord($string{$n});
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$str = substr($string, 0, $n);
	} else {
		for($i = 0; $i < $length; $i++) {
			$str .= ord($string{$i}) > 127 ? $string{$i}.$string{++$i} : $string{$i};
		}
	}
	$str = str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $str);
	return $str == $string ? $str : $str.$suffix;
}

function qfexport($data,$title=array()) {

	global $application_folder;$num = 1;$numk=1;
	require_once $application_folder . '/libraries/Export/export.php';
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
	->setLastModifiedBy("Maarten Balliauw")
	->setTitle("Office 2007 XLSX Document")
	->setSubject("Office 2007 XLSX Document")
	->setDescription("document for Office 2007 XLSX.")
	->setKeywords("office 2007 openxml")
	->setCategory("result file");
	$item = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
	
	if ($title) {
		foreach ($title as $k=>$t) {
			$objPHPExcel->setActiveSheetIndex()->setCellValue($item[$k].'1', $t);
		} 
	}
	
	foreach ($data as $v) {
		$numj=0;
		$numk = $numk+1;
		foreach ($v as $k=>$j) {
			if ($num==1) {
				if (!$title) {
					$objPHPExcel->setActiveSheetIndex()->setCellValue($item[$numj].'1', $k);
				}
			}
	
			$objPHPExcel->setActiveSheetIndex()->setCellValue($item[$numj].$numk, $j);
			$numj += 1;
		} 
		$num+=1;	
	}

	$objPHPExcel->getActiveSheet()->setTitle('data export');
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="01simple.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
}

function encrypt($txt, $key = '') {
	$rnd = md5(microtime());
	$len = strlen($txt);
	$ren = strlen($rnd);
	$ctr = 0;
	$str = '';
	for($i = 0; $i < $len; $i++) {
		$ctr = $ctr == $ren ? 0 : $ctr;
		$str .= $rnd[$ctr].($txt[$i] ^ $rnd[$ctr++]);
	}
	return str_replace('=', '', base64_encode(kecrypt($str, $key)));
}

function decrypt($txt, $key = '') {
	$txt = kecrypt(base64_decode($txt), $key);
	$len = strlen($txt);
	$str = '';
	for($i = 0; $i < $len; $i++) {
		$tmp = $txt[$i];
		$str .= $txt[++$i] ^ $tmp;
	}
	return $str;
}

function kecrypt($txt, $key) {
	$key = md5($key);
	$len = strlen($txt);
	$ken = strlen($key);
	$ctr = 0;
	$str = '';
	for($i = 0; $i < $len; $i++) {
		$ctr = $ctr == $ken ? 0 : $ctr;
		$str .= $txt[$i] ^ $key[$ctr++];
	}
	return $str;
}

function strtohex($str) {
	$hex = '';
	for($i = 0; $i < strlen($str); $i++) {
		$hex .= dechex(ord($str[$i]));
	}
	return $hex;
}

function hextostr($hex) {
	$str = '';
	for($i = 0; $i < strlen($hex) - 1; $i += 2) {
		$str .= chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return $str;
}

function dround($var, $precision = 2, $sprinft = false) {
	$var = round(floatval($var), $precision);
	if($sprinft) $var = sprintf('%.'.$precision.'f', $var);
	return $var;
}

function dalloc($i, $n = 5000) {
	return ceil($i/$n);
}

function strip_sql($string) {
	$search = array("/union([[:space:]\/])/i","/select([[:space:]\/])/i","/update([[:space:]\/])/i","/replace([[:space:]\/])/i","/delete([[:space:]\/])/i","/drop([[:space:]\/])/i","/outfile([[:space:]\/])/i","/dumpfile([[:space:]\/])/i","/load_file\(/i","/substring\(/i","/ascii\(/i","/hex\(/i","/ord\(/i","/char\(/i");
	$replace = array('union\\1','select\\1','update\\1','replace\\1','delete\\1','drop\\1','outfile\\1','dumpfile\\1','load_file(','substring(','ascii(','hex(','ord(','char(');
	return is_array($string) ? array_map('strip_sql', $string) : preg_replace($search, $replace, $string);
}

function strip_nr($string, $js = false) {
	$string =  str_replace(array(chr(13), chr(10), "\n", "\r", "\t", '  '),array('', '', '', '', '', ''), $string);
	if($js) $string = str_replace("'", "\'", $string);
	return $string;
}

function random($length, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++)	{
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function word_count($string) {
	$string = convert($string, DT_CHARSET, 'gbk');
	$length = strlen($string);
	$count = 0;
	for($i = 0; $i < $length; $i++) {
		$t = ord($string[$i]);
		if($t > 127) $i++;
		$count++;
	}
	return $count;
}

function cache_read($file, $dir = '', $mode = '') {
	$file = $dir ? DT_CACHE.'/'.$dir.'/'.$file : DT_CACHE.'/'.$file;
	if(!is_file($file)) return array();
	return $mode ? file_get($file) : include $file;
}

function cache_write($file, $string, $dir = '') {
	if(is_array($string)) $string = "<?php defined('IN_DESTOON') or exit('Access Denied'); return ".strip_nr(var_export($string, true))."; ?>";
	$file = $dir ? DT_CACHE.'/'.$dir.'/'.$file : DT_CACHE.'/'.$file;
	$strlen = file_put($file, $string);
	return $strlen;
}

function cache_delete($file, $dir = '') {
	$file = $dir ? DT_CACHE.'/'.$dir.'/'.$file : DT_CACHE.'/'.$file;
	return file_del($file);
}

function cache_clear($str, $type = '', $dir = '') {
	$dir = $dir ? DT_CACHE.'/'.$dir.'/' : DT_CACHE.'/';
	$files = glob($dir.'*');
	if(is_array($files)) {
		if($type == 'dir') {
			foreach($files as $file) {
				if(is_dir($file)) {dir_delete($file);} else {if(file_ext($file) == $str) file_del($file);}
			}
		} else {
			foreach($files as $file) {
				if(!is_dir($file) && strpos(basename($file), $str) !== false) file_del($file);
			}
		}
	}
}

function content_table($moduleid, $itemid, $split, $table_data = '') {
	if($split) {
		return split_table($moduleid, $itemid);
	} else {
		$table_data or $table_data = get_table($moduleid, 1);
		return $table_data;
	}
}

function split_id($id) {
	return $id > 0 ? ceil($id/500000) : 1;
}


function banword($WORD, $string, $extend = true) {
	$string = stripslashes($string);
	foreach($WORD as $v) {
		$v[0] = preg_quote($v[0]);
		$v[0] = str_replace('/', '\/', $v[0]);
		$v[0] = str_replace("\*", ".*", $v[0]);
		if($v[2] && $extend) {
			if(preg_match("/".$v[0]."/i", $string)) dalert(lang('include->msg_word_ban'));
		} else {
			if($string == '') break;
			if(preg_match("/".$v[0]."/i", $string)) $string = preg_replace("/".$v[0]."/i", $v[1], $string);
		}
	}
	return addslashes($string);
}

function get_env($type) {
	switch($type) {
		case 'ip':
			isset($_SERVER['HTTP_X_FORWARDED_FOR']) or $_SERVER['HTTP_X_FORWARDED_FOR'] = '';
			isset($_SERVER['REMOTE_ADDR']) or $_SERVER['REMOTE_ADDR'] = '';
			isset($_SERVER['HTTP_CLIENT_IP']) or $_SERVER['HTTP_CLIENT_IP'] = '';
			if($_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['REMOTE_ADDR']) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				if(strpos($ip, ',') !== false) {
					$tmp = explode(',', $ip);
					$ip = trim(end($tmp));
				}
				if(is_ip($ip)) return $ip;
			}
			if(is_ip($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
			if(is_ip($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
			return 'unknown';
		break;
		case 'self':
			return isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
		break;
		case 'referer':
			return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		break;
		case 'domain':
			if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx')
				return $_SERVER['HTTP_HOST'];
		    else 
				return $_SERVER['SERVER_NAME'];
		break;
		case 'scheme':
			return $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		break;
		case 'port':
			return $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
		break;
		case 'url':
			if(isset($_SERVER['REQUEST_URI'])) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $_SERVER['PHP_SELF'];
				if(isset($_SERVER['argv'])) {
					if(isset($_SERVER['argv'][0])) $uri .= '?'.$_SERVER['argv'][0];
				} else {
					$uri .= '?'.$_SERVER['QUERY_STRING'];
				}
			}
			$uri = dhtmlspecialchars($uri);
			return get_env('scheme').$_SERVER['HTTP_HOST'].(strpos($_SERVER['HTTP_HOST'], ':') === false ? get_env('port') : '').$uri;
		break;
	}
}


function check_group($groupid, $groupids) {
	if(!$groupids || $groupid == 1) return true;
	if($groupid == 4) $groupid = 3;
	return in_array($groupid, explode(',', $groupids));
}

function set_style($string, $style = '', $tag = 'span') {
	if(preg_match("/^#[0-9a-zA-Z]{6}$/", $style)) $style = 'color:'.$style;
	return $style ? '<'.$tag.' style="'.$style.'">'.$string.'</'.$tag.'>' : $string;
}

function imgurl($imgurl = '', $absurl = 1) {
	return $imgurl ? $imgurl : DT_SKIN.'image/nopic.gif';
}


function lang($str, $arr = array()) {
	if(strpos($str, '->') !== false) {
		$t = explode('->', $str);
		include load($t[0].'.lang');
		$str = $L[$t[1]];
	}
	if($arr) {
		foreach($arr as $k=>$v) {
			$str = str_replace('{V'.$k.'}', $v, $str);
		}
	}
	return $str;
}

function check_name($username) {
	if(strpos($username, '__') !== false || strpos($username, '--') !== false) return false; 
	return preg_match("/^[a-z0-9]{1}[a-z0-9_\-]{0,}[a-z0-9]{1}$/", $username);
}

function is_robot() {
	if(strpos($_SERVER['HTTP_USER_AGENT'], '://') === false && preg_match("/(MSIE|Netscape|Opera|Konqueror|Mozilla)/i", $_SERVER['HTTP_USER_AGENT'])) {
		return false;
	} else if(preg_match("/(Spider|Bot|Crawl|Slurp|lycos|robozilla)/i", $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	} else {
		return false;
	}
}

function is_ip($ip) {
	return preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/", $ip);
}

function is_md5($password) {
	return preg_match("/^[a-z0-9]{32}$/", $password);
}

function getcn($str,$charset='utf8'){
	$charset = strtolower($charset);
	if($charset=='utf-8'){$charset='utf8';}
	if($charset!='utf8'){$str = mb_convert_encoding($str,'utf-8',$charset);}
	if(!preg_match_all("/[\x{4e00}-\x{9fa5}A-Za-z0-9\~\~]+/u",$str,$match)){return false;}
	if($charset!='utf8'){return mb_convert_encoding(implode(' ',$match[0]),$charset,'utf-8');}
	return implode(' ',$match[0]);
}
?>