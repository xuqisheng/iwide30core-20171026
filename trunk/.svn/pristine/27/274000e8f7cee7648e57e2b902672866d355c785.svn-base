<?php
class Str_Py{

	private static $py_list = [
		'亳'=>'B',
		'婺'=>'W',
		'暹'=>'X',
		'栾'=>'L',
		'泸'=>'L',
		'涞'=>'L',
		'涠'=>'W',
		'涿'=>'Z',
		'滕'=>'T',
		'漯'=>'L',
		'濮'=>'P',
		'荥'=>'Y',
		'莞'=>'W',
		'藁'=>'G',
		'衢'=>'Q',
		'郓'=>'Y',
        '兖'=>'Y',
		'鄄'=>'J',
        '茌'=>'C',
	];

	public static function getPy($str){
		return isset(self::$py_list[$str])?self::$py_list[$str]:null;
	}
	public static $special_py=array(
			'重庆'=>'C'
	);
	public static function check_special_py($str){
		foreach (self::$special_py as $sk=>$sy){
			if (strpos($str,$sk)===0){
				return $sy;
			}
		}
		return NULL;
	}
}
