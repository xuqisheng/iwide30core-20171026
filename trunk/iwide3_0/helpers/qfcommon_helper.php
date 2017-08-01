<?php 
/**
 * 清风基础方法库
 */
function retdata($d){
	return $d;
}

/**
 * 数组编码转码
 * @param string $in_charset
 * @param string $out_charset
 * @param array $arr
 */
function array_iconv($in_charset,$out_charset,$arr){
	return eval('return '.iconv($in_charset,$out_charset,var_export($arr,true).';'));
}

function qqface($str) {
	$str = str_replace("\n", "<br/>",$str);
	$str = preg_replace('/\[em_([0-9]*)\]/',"<img class=face_img src=/public/chat/public/qqface/face/$1.gif border=0 />", $str);
	return $str;
}

function qfpost($url,$data=''){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);

    if (is_array($data)) {
    	curl_setopt($curl, CURLOPT_POST, 1);
   		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    else {
    	curl_setopt($curl, CURLOPT_POST, 1);
   		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    //curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
       echo 'Errno'.curl_error($curl);
    }
    curl_close($curl);
    return $tmpInfo;
}


function accesstoken($hotelid, $db) {
	
}


function wxtiket($hotelid,$db) {
	
}

function newcrypt($string, $operation, $key = '') {
	$key = md5 ( $key );
	$key_length = strlen ( $key );
	$string = $operation == 'D' ? base64_decode ( $string ) : substr ( md5 ( $string . $key ), 0, 8 ) . $string;
	$string_length = strlen ( $string );
	$rndkey = $box = array ();
	$result = '';
	for($i = 0; $i <= 255; $i ++) {
		$rndkey [$i] = ord ( $key [$i % $key_length] );
		$box [$i] = $i;
	}
	for($j = $i = 0; $i < 256; $i ++) {
		$j = ($j + $box [$i] + $rndkey [$i]) % 256;
		$tmp = $box [$i];
		$box [$i] = $box [$j];
		$box [$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i ++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box [$a]) % 256;
		$tmp = $box [$a];
		$box [$a] = $box [$j];
		$box [$j] = $tmp;
		$result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
	}
	if ($operation == 'D') {
		if (substr ( $result, 0, 8 ) == substr ( md5 ( substr ( $result, 8 ) . $key ), 0, 8 )) {
			return substr ( $result, 8 );
		} else {
			return '';
		}
	} else {
		return str_replace ( '=', '', base64_encode ( $result ) );
	}
}

function getapp($inter_id,$openid,$db) {
	
}

function qfglob($this) {
	
}

function qfselect($sql,$db) {
	$query = $db->query($sql);
	return $query->result_array($query);
}
?>