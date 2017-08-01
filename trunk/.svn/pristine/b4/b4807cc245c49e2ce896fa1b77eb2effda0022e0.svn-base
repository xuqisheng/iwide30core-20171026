<?php
/**
 * 数据对象基础函数
 */

//拼接url
function spliceURL($data = array())
{
	//按字典序排序参数
	$data = argSort($data);
    $o = "";
    foreach ($data as $k => $v) {
        $o .= "$k=" . $v . "&";
    }
    $str = substr($o, 0, -1);
    return $str;
}


function convertUrlQuery($query,$split=false)
{
    $queryParts = explode('&', $query);
    if($split)
    {
    	$params = array();
    	foreach ($queryParts as $param)
	    {
	        $item = explode('=', $param);
	        $params[$item[0]] = $item[1];
	    }
	    return $params;
    }
    return $queryParts;
}

function getUrlQuery($array_query)
{
    $tmp = array();
    foreach ($array_query as $k => $param) {
        $tmp[] = $k . '=' . $param;
    }
    $params = implode('&', $tmp);
    return $params;
}

function roundnum($num)
{
    $numbers = range(1, 9);
	//shuffle 将数组顺序随即打乱
    shuffle($numbers);
	//array_slice 取该数组中的某一段
    $result = array_slice($numbers, 0, $num);

    $str = '';
    foreach ($result as $v) {
        $str .= $v;
    }
    return $str;
}

/**
 * 将数组转换为string
 *
 * @param $para 数组        	
 * @param $sort 是否需要排序        	
 * @param $encode 是否需要URL编码        	
 * @return string
 */
function createLinkString($para, $sort, $encode) 
{
	if($para == NULL || !is_array($para))
		return "";
	
	$linkString = "";
	if ($sort) {
		$para = argSort ( $para );
	}
	while ( list ( $key, $value ) = each ( $para ) ) {
		if ($encode) {
			$value = urlencode ( $value );
		}
		$linkString .= $key . "=" . $value . "&";
	}
	// 去掉最后一个&字符
	$linkString = substr ( $linkString, 0, count ( $linkString ) - 2 );
	
	return $linkString;
}

/**
 * 对数组排序
 *
 * @param $para 排序前的数组
 *        	return 排序后的数组
 */
function argSort($para) {
	ksort ( $para );
	reset ( $para );
	return $para;
}

/**
 * key1=value1&key2=value2转array
 * @param $str key1=value1&key2=value2的字符串
 * @param $needUrlDecode 是否需要解url编码，默认不需要
 */
function parseQString($str, $needUrlDecode=false){
	$result = array();
	$len = strlen($str);
	$temp = "";
	$curChar = "";
	$key = "";
	$isKey = true;
	$isOpen = false;
	$openName = "\0";

	for($i=0; $i<$len; $i++){
		$curChar = $str[$i];
		if($isOpen){
			if( $curChar == $openName){
				$isOpen = false;
			}
			$temp = $temp . $curChar;
		} elseif ($curChar == "{"){
			$isOpen = true;
			$openName = "}";
			$temp = $temp . $curChar;
		} elseif ($curChar == "["){
			$isOpen = true;
			$openName = "]";
			$temp = $temp . $curChar;
		} elseif ($isKey && $curChar == "="){
			$key = $temp;
			$temp = "";
			$isKey = false;
		} elseif ( $curChar == "&" && !$isOpen){
			putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
			$temp = "";
			$isKey = true;
		} else {
			$temp = $temp . $curChar;
		}
	}
	putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
	return $result;
}

function putKeyValueToDictionary($temp, $isKey, $key, &$result, $needUrlDecode) {
	if ($isKey) {
		$key = $temp;
		if (strlen ( $key ) == 0) {
			return false;
		}
		$result [$key] = "";
	} else {
		if (strlen ( $key ) == 0) {
			return false;
		}
		if ($needUrlDecode)
			$result [$key] = urldecode ( $temp );
		else
			$result [$key] = $temp;
	}
}