<?php

/**
 * 二维数组排序
 * @param array $array
 * @param string $sort_order 排序顺序(SORT_ASC|SORT_DESC)
 * @param string $sort_type 排序类型(SORT_REGULAR|SORT_NUMERIC|SORT_STRING)
 * @param string $sort_key
 */
function sort_arr($array, $sort_key = '', $sort_order = SORT_DESC, $sort_type = SORT_STRING) {
	$arr_sort = array ();
	foreach ( $array as $uniqid => $row ) {
		foreach ( $row as $key => $value ) {
			$arr_sort [$key] [$uniqid] = $value;
		}
	}
	if ($sort_order) {
		array_multisort ( $arr_sort [$sort_key], $sort_order, $sort_type, $array );
	}
	return $array;
}
if ( ! function_exists('jqjson2arr'))
{
	/**
	 * 将jquery	serializeArray 方法生成的json数组转成键值对形式数组
	 * @param array $jqjson
	 */
	function jqjson2arr($jqjson){
		$arr=array();
		foreach($jqjson as $i){
			$str_len=strlen($i['name']);
			if($str_len>2&&substr($i['name'],$str_len-2)==='[]'){
				$arr[substr($i['name'],0,$str_len-2)][]=$i['value'];
			}else{
				$arr[$i['name']]=$i['value'];
			}
		}
		return $arr;
	}
}